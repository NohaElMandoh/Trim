@extends('dashboard.layouts.app')
@section('title', __('Editing').' '.ucfirst(__('service')))
@section('content')
<form role="form" class="form-horizontal" method="POST" action="{{ route('services.update', $row->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="form-body">
        @component('input_trans', ['type' => 'text', 'label' => 'Title', 'required' => true, 'model' => $row])
            title
        @endcomponent
        @component('input_trans', ['type' => 'textarea', 'label' => 'Description', 'required' => false, 'model' => $row])
            description
        @endcomponent
        <div class="form-group form-md-line-input">
            <label class="col-md-2 control-label">{{ ucfirst(__('Price type')) }} <span class="required">*</span></label>
            <div class="col-md-10">
                <select class="js-example-basic-single js-states form-control" id="price_type" name="price_type">
                    <option value="normal" {{ old('price_type') == 'normal' || $row->price_type == 'normal' ? 'selected': ''}}>{{ __('Normal') }}</option>
                    <option value="range" {{ old('price_type') == 'range' || $row->price_type == 'range' ? 'selected': ''}}>{{ __('Range') }}</option>
                </select>
                <div class="form-control-focus"> </div>
            </div>
        </div>
        @component('input', ['label' => 'Price', 'type' => 'number', 'block_id' => 'price_block', 'required' => false, 'value' => $row->price])
            price
        @endcomponent
        @component('input', ['label' => 'Min price', 'type' => 'number', 'block_id' => 'min_price_block', 'required' => false, 'value' => $row->min_price])
            min_price
        @endcomponent
        @component('input', ['label' => 'Max price', 'type' => 'number', 'block_id' => 'max_price_block', 'required' => false, 'value' => $row->max_price])
            max_price
        @endcomponent
        <div class="form-group form-md-line-input">
            <label class="col-md-2 control-label">{{ ucfirst(__('Gender')) }}</label>
            <div class="col-md-10">
                <select class="js-example-basic-single js-states form-control" id="gender" name="gender">
                    <option value="male" {{ old('gender') == 'male' || $row->gender == 'male' ? 'selected': ''}}>{{ ucfirst(__('male')) }}</option>
                    <option value="female" {{ old('gender') == 'female' || $row->gender == 'female' ? 'selected': ''}}>{{ ucfirst(__('female')) }}</option>
                </select>
                <div class="form-control-focus"> </div>
            </div>
        </div>
        @component('checkbox', ['label' => 'For children', 'value' => $row->for_children])
            for_children
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
    function typeChange () {
        if($('#price_type').val() == 'normal') {
            $('#min_price_block').hide();
            $('#max_price_block').hide();
            $('#price_block').show();
        } else {
            $('#min_price_block').show();
            $('#max_price_block').show();
            $('#price_block').hide();
        }
    }
    typeChange();
    $('#price_type').change(typeChange);
});
</script>
@endsection