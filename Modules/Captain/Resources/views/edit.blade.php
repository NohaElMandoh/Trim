@extends('dashboard.layouts.app')
@section('title', __('Editing').' '.ucfirst(__('captain')))
@section('content')
<form service="form" class="form-horizontal" method="POST" action="{{ route('captains.update', $row->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="form-body">
        @component('input', ['type' => 'text', 'label' => 'Name', 'required' => true, 'value' => $row->name])
            name
        @endcomponent
        @component('input', ['type' => 'text', 'label' => 'E-Mail Address', 'required' => true, 'value' => $row->email])
            email
        @endcomponent
        @component('input', ['type' => 'text', 'label' => 'Phone', 'required' => true, 'value' => $row->phone])
            phone
        @endcomponent
        @component('input_image', ['width' => 200, 'height' => 200, 'label' => 'Image', 'src' => {{url('uploads/user.png')}} ])
            image
        @endcomponent
        <div class="form-group form-md-line-input">
            <div class="col-md-2"></div>
            <div class="col-md-10">
                <div class="panel panel-default">
                    <div class="panel-heading">{{ __('Captain ID') }}</div>
                    <div class="panel-body">
                        <a href="{{ route('file_show', $row->id_photo) }}" target="_blank">{{ $row->id_photo }}</a>
                    </div>
                </div>
            </div>
        </div>
        @component('input', ['type' => 'file', 'label' => 'Captain ID', 'required' => false])
            id_photo
        @endcomponent
        <div class="form-group form-md-line-input">
            <label class="col-md-2 control-label">{{ ucfirst(__('governorate')) }}</label>
            <div class="col-md-10">
                <select class="js-example-basic-single js-states form-control" id="governorate_id" name="governorate_id">
                    @foreach(\Modules\Governorate\Entities\Governorate::latest()->get() as $governorate)
                    <option value="{{ $governorate->id }}" {{ old('governorate_id') == $governorate->id || $governorate->id == $row->governorate_id ? 'selected': ''}}>{{ $governorate->name }}</option>
                    @endforeach
                </select>
                <div class="form-control-focus"> </div>
            </div>
        </div>
        <div class="form-group form-md-line-input">
            <label class="col-md-2 control-label">{{ ucfirst(__('city')) }}</label>
            <div class="col-md-10">
                <select class="js-example-basic-single js-states form-control" id="city_id" name="city_id">
                </select>
                <div class="form-control-focus"> </div>
            </div>
        </div>
        <div class="form-group form-md-line-input">
            <label class="col-md-2 control-label">{{ ucfirst(__('Gender')) }}</label>
            <div class="col-md-10">
                <select class="js-example-basic-single js-states form-control" id="gender" name="gender">
                    <option value="male" {{ old('gender') == 'male' || $row->gender == 'male' ? 'selected': ''}}>{{ ucfirst(__('male')) }}</option>
                    <option value="female" {{ old('gender') == 'female' || $row->gender == 'female'  ? 'selected': ''}}>{{ ucfirst(__('female')) }}</option>
                </select>
                <div class="form-control-focus"> </div>
            </div>
        </div>
        <div class="form-group form-md-line-input">
            <label class="col-md-2 control-label">{{ ucfirst(__('services')) }}</label>
            <div class="col-md-10">
                <select class="js-example-basic-single js-states form-control" id="services[]" name="services[]" multiple="multiple">
                    @foreach(\Modules\Service\Entities\Service::all() as $service)
                    <option value="{{ $service->id }}" @if(old('services')) @if(in_array($service->id, old('services'))) selected @endif @else @if(in_array($service->id, $selected)) selected @endif  @endif>{{ $service->title }}</option>
                    @endforeach
                </select>
                <div class="form-control-focus"> </div>
            </div>
        </div>
        @component('checkbox', ['label' => 'Sponsored', 'value' => $row->is_sponsored])
            is_sponsored
        @endcomponent
        @component('checkbox', ['label' => 'Active', 'value' => $row->is_active])
            is_active
        @endcomponent
    </div>
    <div class="form-actions">
        <div class="row">
            <div class="col-md-offset-2 col-md-10">
                <button type="rest" class="btn btn-default">{{ __('Cancel')}}</button>
                <button type="submit" class="btn btn-primary">{{ __('Submit')}}</button>
            </div>
        </div>
    </div>
</form>
@endsection
@section('css')
 
@endsection
@section('js')
<script>
$(document).ready(function() {
    $('.js-example-basic-single').select2();
    function readURL(input, id) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $(id).attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $("#image").change(function() {
        readURL(this, "#image_image");
    });
    function loadCities() {
        var selected_city = "{{ $row->city }}";
        var id = $('#governorate_id').val();
        var action = "{{ url('/api/governorates/find?include=cities') }}";
        $.ajax({
            url:  action,
            type: 'POST',
            dataType: 'JSON',
            data: {id: id},
            headers: {
                'X-localization': '{{ App::getLocale() }}',
            },
            success: function(data, status){
                console.log(data.data.cities.data)
                let cities = data.data.cities.data;
                var i;
                $("#city_id").empty()
                for(i = 0; i < cities.length; i++) {
                    $("#city_id").append(`<option value="${cities[i].id}" ${selected_city == cities[i].id ? 'selected': ''}>${cities[i].name}</option>`)
                }
            }
        });
    }
    loadCities();
    $('#governorate_id').change(loadCities)
});
</script>
@endsection