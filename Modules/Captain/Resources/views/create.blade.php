@extends('dashboard.layouts.app')
@section('title',  __('Creating').' '.ucfirst(__('captain')))
@section('content')
<form role="form" class="form-horizontal" method="POST" action="{{ route('captains.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="form-body">
        @component('input', ['type' => 'text', 'label' => 'Name', 'required' => true])
            name
        @endcomponent
        @component('input', ['type' => 'text', 'label' => 'E-Mail Address', 'required' => true])
            email
        @endcomponent
        @component('input', ['type' => 'text', 'label' => 'Phone', 'required' => true])
            phone
        @endcomponent
        @component('input', ['type' => 'password', 'label' => 'Password', 'required' => true])
            password
        @endcomponent
        @component('input_image', ['width' => 200, 'height' => 200, 'label' => 'Image'])
            image
        @endcomponent
        @component('input', ['type' => 'file', 'label' => 'Captain ID', 'required' => true])
            id_photo
        @endcomponent
        <div class="form-group form-md-line-input">
            <label class="col-md-2 control-label">{{ ucfirst(__('governorate')) }}</label>
            <div class="col-md-10">
                <select class="js-example-basic-single js-states form-control" id="governorate_id" name="governorate_id">
                    @foreach(\Modules\Governorate\Entities\Governorate::latest()->get() as $governorate)
                    <option value="{{ $governorate->id }}" {{ old('governorate_id') == $governorate->id ? 'selected': ''}}>{{ $governorate->name }}</option>
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
                    <option value="male" {{ old('gender') == 'male' ? 'selected': ''}}>{{ ucfirst(__('male')) }}</option>
                    <option value="female" {{ old('gender') == 'female' ? 'selected': ''}}>{{ ucfirst(__('female')) }}</option>
                </select>
                <div class="form-control-focus"> </div>
            </div>
        </div>
        @component('checkbox', ['label' => 'Active'])
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
                    $("#city_id").append(`<option value="${cities[i].id}">${cities[i].name}</option>`)
                }
            }
        });
    }
    loadCities();
    $('#governorate_id').change(loadCities)
});
</script>
@endsection