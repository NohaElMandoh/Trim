<div class="form-group form-md-line-input @if($errors->has('$slot')) has-error @endif" @isset($block_id) id="{{ $block_id }}" @endisset>
    <label class="col-md-2 control-label">{{ __("$label") }} <br>[{{ $width }}x{{ $height }}]</label>
    <div class="col-md-10">
        <div class="thumbnail" style="width: {{ $width > 400 ? '400' : $width }}px; height: {{ $height > 400 ? '400' : $height }}px;">
            <img id="{{ $slot }}_image" src="{{ $src ?? 'http://www.placehold.it/'.$width.'x'.$height.'/EFEFEF/AAAAAA&amp;text=no+image'}}" /> 
        </div>
        <input type="file" class="from-control" name="{{ $slot }}" id="{{ $slot }}" />
    </div>
</div>