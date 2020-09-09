@extends('dashboard.layouts.app')
@section('title', __('Editing').' '.ucfirst(__('offer')))
@section('content')
<form role="form" class="form-horizontal" method="POST" action="{{ route('offers.update', $row->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="form-body">
        @component('input_trans', ['type' => 'text', 'label' => 'Name', 'required' => true, 'model' => $row])
            name
        @endcomponent
        @component('input_trans', ['type' => 'textarea', 'label' => 'Description', 'required' => true, 'model' => $row])
            description
        @endcomponent
        @component('input_image', ['width' => 800, 'height' => 400, 'label' => 'Image', 'src' => route('file_show', $row->image)])
            image
        @endcomponent
        @component('input', ['label' => 'Price', 'type' => 'number', 'required' => true, 'value' => $row->price])
            price
        @endcomponent
        <div class="form-group form-md-line-input">
            <label class="col-md-2 control-label">{{ ucfirst(__('user')) }}</label>
            <div class="col-md-10">
                <select class="js-example-basic-single js-states form-control" id="user_id" name="user_id">
                     @foreach(\App\User::whereHas('roles', function ($query) {
                        $query->where('name', 'salon')->orWhere('name', 'captain');
                    })->latest()->get() as $user)
                    <option value="{{ $user->id }}" {{ old('user_id') == $user->id || $row->user_id == $user->id ? 'selected': '' }}>{{ $user->name }}</option>
                    @endforeach
                </select>
                <div class="form-control-focus"> </div>
            </div>
        </div>
        <div class="form-group form-md-line-input">
            <label class="col-md-2 control-label">{{ ucfirst(__('category')) }}</label>
            <div class="col-md-10">
                <select class="js-example-basic-single js-states form-control" id="category_id" name="category_id">
                    @foreach(\Modules\Category\Entities\Category::where('for_offers', 1)->latest()->get() as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id || $category->id == $row->category_id ? 'selected': '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                <div class="form-control-focus"> </div>
            </div>
        </div>
        {{-- @component('checkbox', ['label' => 'Sponsored', 'value' => $row->is_sponsored])
            is_sponsored
        @endcomponent --}}
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