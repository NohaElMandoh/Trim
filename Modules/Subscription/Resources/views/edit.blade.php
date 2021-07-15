@extends('dashboard.layouts.app')
@section('title', __('Editing').' '.ucfirst(__('subscription')))
@section('content')
<form role="form" class="form-horizontal" method="POST" action="{{ route('subscription.update', $row->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="form-body">
        @component('input_trans', ['type' => 'text', 'label' => 'Title', 'required' => true, 'model' => $row,'value' => $row->title])
            title
        @endcomponent
    </div>
    <div class="form-body">
        @component('input_trans', ['type' => 'textarea', 'label' => 'Description', 'model' => $row,'value' => $row->desc, 'required' => false])
            desc
        @endcomponent
        </div>
    <div class="form-body">
        @component('input', ['type' => 'number', 'label' => 'Price', 'required' => true,'value' => $row->price])
            price
        @endcomponent
    </div>
    <div class="form-body">
        @component('input', ['type' => 'number', 'label' => 'Origion Price','value' => $row->origion_price, 'required' => true])
            origion_price
        @endcomponent
    </div>
    <div class="form-body">
        @component('input', ['type' => 'number', 'label' => 'Months', 'required' => true,'value' => $row->months])
            months
        @endcomponent
    </div>
    <div class="form-body">
        @component('input', ['type' => 'text', 'label' => 'Currency', 'required' => true,'value' => $row->currency])
            currency
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