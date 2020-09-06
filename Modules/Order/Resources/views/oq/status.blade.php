@extends('dashboard.layouts.app')
@section('title', __('Change status').' '.ucfirst(__('OQ')))
@section('content')
<form role="form" class="form-horizontal" method="POST" action="{{ route('oq_orders.status', $row->id) }}" enctype="multipart/form-data">
    @csrf
    <div class="form-body">
        <div class="form-group form-md-line-input">
            <label class="col-md-2 control-label">{{ ucfirst(__('status')) }}</label>
            <div class="col-md-10">
                <select class="js-example-basic-single js-states form-control" id="status_id" name="status_id">
                    @foreach(\Modules\Status\Entities\Status::all() as $status)
                    <option value="{{ $status->id }}" {{ old('status_id') == $status->id || $row->status_id == $status->id ? 'selected' : ''}}>{{ $status->name }}</option>
                    @endforeach
                </select>
                <div class="form-control-focus"> </div>
            </div>
        </div>
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