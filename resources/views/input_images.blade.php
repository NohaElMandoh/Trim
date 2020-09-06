<div class="form-group form-md-line-input  @if($errors->has(''.$slot)) has-error @endif">
    <label class="col-md-2 control-label" for="{{ $slot }}">{{ __("$label") }} @if($required) <span class="required">*</span> @endif</label>
    <div class="col-md-3">
        <input type="file"  class="form-control" id="{{ $slot }}" name="{{ $slot }}[]" multiple>
    </div>
</div>