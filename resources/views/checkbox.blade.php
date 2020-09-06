<div class="form-group form-md-line-input">
    <label class="col-md-2 control-label"></label>
    <div class="col-md-10">
        <div class="md-checkbox-inline">
            <div class="md-checkbox">
                <input type="checkbox" id="{{ $slot }}" name="{{ $slot }}" class="md-check"  @if(old("$slot")) checked @elseif(isset($value)) @if($value) checked @endif @endif >
                <label for="{{ $slot }}">
                    <span></span>
                    <span class="check"></span>
                    <span class="box"></span> @if(isset($label)) {{ ucfirst(__("$label")) }} @else {{ ucfirst(__("$slot")) }} @endif</label>
            </div>
        </div>
    </div>
</div>