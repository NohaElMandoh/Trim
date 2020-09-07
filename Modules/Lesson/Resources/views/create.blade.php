@extends('dashboard.layouts.app')
@section('title',  __('Creating').' '.ucfirst(__('lesson')))
@section('content')
<form role="form" class="form-horizontal" method="POST" action="{{ route('lessons.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="form-body">
        <div class="form-group form-md-line-input">
            <label class="col-md-2 control-label">{{ ucfirst(__('course')) }}</label>
            <div class="col-md-10">
                <select class="js-example-basic-single js-states form-control" id="course_id" name="course_id">
                    @foreach(\Modules\Course\Entities\Course::latest()->get() as $course)
                    <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected': ''}}>{{ $course->name }}</option>
                    @endforeach
                </select>
                <div class="form-control-focus"> </div>
            </div>
        </div>
        @component('input_trans', ['type' => 'text', 'label' => 'Name', 'required' => true])
            name
        @endcomponent
        @component('input_image', ['width' => 400, 'height' => 400, 'label' => 'Image'])
            image
        @endcomponent
        @component('input', ['type' => 'file', 'label' => 'Video', 'required' => true])
            video
        @endcomponent
        @component('input', ['label' => 'Order', 'type' => 'number', 'required' => true])
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