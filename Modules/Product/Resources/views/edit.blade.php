@extends('dashboard.layouts.app')
@section('title', __('Editing').' '.ucfirst(__('product')))
@section('content')
<form role="form" class="form-horizontal" method="POST" action="{{ route('products.update', $row->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="form-body">
        @component('input_trans', ['type' => 'text', 'label' => 'Name', 'required' => true, 'model' => $row])
            name
        @endcomponent
        @if (!empty($row->image))
        @component('input_image', ['width' => 200, 'height' => 200, 'label' => 'Image', 'src' =>  url($row->image)  ])
            image
        @endcomponent
        @else @component('input_image', ['width' => 200, 'height' => 200, 'label' => 'Image', 'src' =>  url('uploads/product.png')  ])
        image
    @endcomponent 
    @endif
        @component('input', ['label' => 'Price', 'type' => 'number', 'required' => true, 'value' => $row->price])
            price
        @endcomponent
        <div class="form-group form-md-line-input">
            <label class="col-md-2 control-label">{{ ucfirst(__('category')) }}</label>
            <div class="col-md-10">
                <select class="js-example-basic-single js-states form-control" id="category_id" name="category_id">
                    @foreach(\Modules\Category\Entities\Category::where('for_offers', 0)->latest()->get() as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id || $category->id == $row->category_id ? 'selected': '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                <div class="form-control-focus"> </div>
            </div>
        </div>
        @component('input', ['label' => 'Order', 'type' => 'number', 'required' => true, 'value' => $row->order])
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