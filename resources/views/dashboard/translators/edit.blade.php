@extends('dashboard.layouts.app')
@section('title', __('Editing').' '.ucfirst(__('translator')))
@section('content')
<form role="form" class="form-horizontal" method="POST" action="{{ route('translators.update', $id) }}"  enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="form-body">
        @foreach($rows['en'] as $key=>$value)
            @if($key)
            <h5>{{$key}}</h5>
                @foreach(LaravelLocalization::getSupportedLocales() as $lang => $properties) 
                    @if(isset($rows[$lang][$key]) && !is_array($rows[$lang][$key]))
                        @component('input', ['type' => 'text', 'label' => ucfirst($lang), 'required' => false, 'value' => $rows[$lang][$key]])
                            {{ $lang }}[{{ $key }}]
                        @endcomponent
                    @endif
                @endforeach
            @endif
        @endforeach
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