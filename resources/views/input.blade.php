@if($type != 'textarea')
<div class="form-group form-md-line-input  @if($errors->has(''.$slot)) has-error @endif" @isset($block_id) id="{{ $block_id }}" @endisset>
    <label class="col-md-2 control-label" for="{{ $slot }}">{{ __("$label") }} @if($required) <span class="required">*</span> @endif</label>
    <div class="col-md-10">
        <input type="{{ $type }}" step="any" class="form-control" @if(!isset($id)) id="{{ $slot }}" @endif name="{{ $slot }}" placeholder="{{ __('Enter the') }} {{ __(''.$label) }}" @if(isset($value)) value="{{ $value }}"  @else value="{{ old(''.$slot) }}" @endif>
        <div class="form-control-focus"> </div>
    </div>
</div>
@else 
<div class="form-group form-md-line-input @if($errors->has(''.$slot)) has-error @endif" @isset($block_id) id="{{ $block_id }}" @endisset>
    <label class="col-md-2 control-label" for="{{ $slot }}">{{ __("$label") }} @if($required) <span class="required">*</span> @endif</label>
    <div class="col-md-10">
        <textarea class="form-control @isset($class) {{ $class }} @endisset" rows="7" id="{{ $slot }}" name="{{ $slot }}" placeholder="{{ __('Enter the') }} {{ __(''.$label) }}">@if(isset($value)) {!! $value !!}@else{!! old("$slot") !!}@endif</textarea>
        <div class="form-control-focus"> </div>
    </div>
</div>
@endif