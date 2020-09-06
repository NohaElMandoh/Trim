@extends('dashboard.layouts.app')
@section('title',  __('Creating').' '.ucfirst(__('coupon')))
@section('content')
<form role="form" class="form-horizontal" method="POST" action="{{ route('coupons.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="form-body">
        @component('input_trans', ['type' => 'text', 'label' => 'Title', 'required' => true])
            title
        @endcomponent
        @component('input', ['type' => 'text', 'label' => 'Code', 'required' => true])
            code
        @endcomponent
        @component('input', ['type' => 'number', 'label' => 'Duration(hours)', 'required' => true])
            duration
        @endcomponent
        @component('input', ['type' => 'number', 'label' => 'Price', 'required' => true])
            price
        @endcomponent
        @component('input', ['type' => 'number', 'label' => 'Usage number of times', 'required' => true])
            usage_number_times
        @endcomponent
        @component('input_image', ['width' => 800, 'height' => 400, 'label' => 'Image'])
            image
        @endcomponent
        @component('input', ['type' => 'text', 'label' => 'Name(user or captain)', 'required' => false])
            name
        @endcomponent
        <div class="form-group form-md-line-input">
            <label class="col-md-2 control-label">{{ ucfirst(__('roles')) }}</label>
            <div class="col-md-10">
                <select class="js-example-basic-single js-states form-control" id="roles[]" name="roles[]" multiple="multiple">
                    @foreach(config('permission.models.role')::latest()->get() as $role)
                    <option value="{{ $role->id }}" @if(old('roles')) @if(in_array($role->id, old('roles'))) selected @endif @endif>{{ $role->name }}</option>
                    @endforeach
                </select>
                <div class="form-control-focus"> </div>
            </div>
        </div>
        <div class="form-group form-md-line-input">
            <label class="col-md-2 control-label">{{ ucfirst(__('governorate')) }}</label>
            <div class="col-md-10">
                <select class="js-example-basic-single js-states form-control" id="governorate_id" name="governorate_id">
                    <option value=""></option>
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
        @component('checkbox', ['label' => 'Any where orders'])
            anywhere
        @endcomponent
        @component('checkbox', ['label' => 'More way orders'])
            moreway
        @endcomponent
        @component('checkbox', ['label' => 'One way orders'])
            oneway
        @endcomponent
        @component('checkbox', ['label' => 'OQ orders'])
            oq
        @endcomponent
        @component('checkbox', ['label' => 'Week orders'])
            week
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
                $("#city_id").append(`<option value=""></option>`)
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