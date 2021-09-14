@extends('dashboard.layouts.app')
@section('title',  __('Change password').' '.ucfirst(__('delegate')))
@section('content')
<form role="form" class="form-horizontal" method="POST" action="{{ route('delegates.password', $row->id) }}" enctype="multipart/form-data">
    @csrf
    <div class="form-body">
        @component('input', ['type' => 'password', 'label' => 'Password', 'required' => true])
            password
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
