@extends('dashboard.layouts.app')
@section('title', __('Editing').' '.ucfirst(__('category')))
@section('content')
<form role="form" class="form-horizontal" method="POST" action="{{ route('categories.update', $row->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="form-body">
        @component('input_trans', ['type' => 'text', 'label' => 'Name', 'required' => true, 'model' => $row])
            name
        @endcomponent
        {{-- @component('input_image', ['label' => 'Image', 'width' => 200, 'height' => 200, 'src' => route('file_show', $row->image)])
            image
        @endcomponent --}}
        @if (!empty($row->image))
        @component('input_image', ['width' => 200, 'height' => 200, 'label' => 'Image', 'src' =>  url($row->image)  ])
            image
        @endcomponent
        @else @component('input_image', ['width' => 200, 'height' => 200, 'label' => 'Image', 'src' =>  url('uploads/product.png')  ])
        image
    @endcomponent 
    @endif
        @component('input', ['label' => 'Order', 'type' => 'number', 'required' => true, 'value' => $row->order])
            order
        @endcomponent
        @component('checkbox', ['label' => 'For offers', 'value' => $row->for_offers])
            for_offers
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