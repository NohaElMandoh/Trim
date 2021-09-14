@extends('dashboard.layouts.app')
@section('title',  __('Creating').' '.ucfirst(__('delegates')))
@section('content')
<form role="form" class="form-horizontal" method="POST" action="{{ route('delegates.store') }}" enctype="multipart/form-data">
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
            @component('input', ['type' => 'text', 'label' => 'address', 'required' => true])
                address
        @endcomponent
        @component('input', ['type' => 'password', 'label' => 'Password', 'required' => true])
            password
        @endcomponent

        @component('input_image', ['width' => 200, 'height' => 200, 'label' => 'Image'])
            image
        @endcomponent

            <div class="form-group form-md-line-input">
                <label class="col-md-2 control-label">{{ ucfirst(__('city')) }}</label>
                <div class="col-md-10">
                    <select class="js-example-basic-single js-states form-control" id="city_id" name="city_id">

                        @foreach(\Modules\City\Entities\City::latest()->get() as $city)
                            <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected': ''}}>{{ $city->name }}</option>
                        @endforeach
                    </select>
                    <div class="form-control-focus"> </div>
                </div>
            </div>
        <div class="form-group form-md-line-input">
{{--            <label class="col-md-2 control-label">{{ ucfirst(__('roles')) }}</label>--}}
            <div class="col-md-10">
{{--                <select class="js-example-basic-single js-states form-control" id="roles[]" name="roles[]" >--}}

{{--                    <option value="4"  selected>{{ucfirst(__('factories'))}}</option>--}}

{{--                </select>--}}

                <input name="roles[]" value="5" hidden>
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

});
</script>
@endsection
