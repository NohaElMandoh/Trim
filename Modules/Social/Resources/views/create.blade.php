@extends('dashboard.layouts.app')
@section('title', __('Creating').' '.ucfirst(__('social')))
@section('content')
<form role="form" class="form-horizontal" method="POST" action="{{ route('socials.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="form-body">
        @component('input', ['type' => 'text', 'label' => 'URL', 'required' => true])
            url
        @endcomponent
        @component('input_image', ['width' => 32, 'height' => 32, 'label' => 'Image'])
            image
        @endcomponent
        @component('input', ['type' => 'number', 'label' => 'Order', 'required' => true])
            order
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
