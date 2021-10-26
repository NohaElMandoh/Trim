@extends('dashboard.layouts.app')
@section('title', __('Editing').' '.ucfirst(__('user')))
@section('content')
<form role="form" class="form-horizontal" method="POST" action="{{ route('users.update', $row->id) }}" enctype="multipart/form-data">
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
        {{--@component('input_image', ['width' => 200, 'height' => 200, 'label' => 'Image', 'src' => route('file_show', $row->image)])
            image
        @endcomponent--}}
        @if (!empt
                @component('input_image', ['width' => 200, 'height' => 200, 'label' => 'Image', 'src' => url($row->image)])
                    image
                @endcomponent
            @else @component('input_image', ['width' => 200, 'height' => 200, 'label' => 'Image', 'src' =>
                url('uploads/user.png')])
                image
            @endcomponent
        @endif
        <div class="form-group form-md-line-input">
            <label class="col-md-2 control-label">{{ ucfirst(__('roles')) }}</label>
            <div class="col-md-10">
                <select class="js-example-basic-single js-states form-control" id="roles[]" name="roles[]" multiple="multiple">
                    @foreach($roles as $role)
                    <option value="{{ $role->id }}" @if(old('roles')) @if(in_array($role->id, old('roles'))) selected @endif @else @if(in_array($role->id, $selected)) selected @endif  @endif>{{ $role->name }}</option>
                    @endforeach
                </select>
                <div class="form-control-focus"> </div>
            </div>
        </div>
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