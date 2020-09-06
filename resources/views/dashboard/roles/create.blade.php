@extends('dashboard.layouts.app')
@section('title', __('Creating').' '.ucfirst(__('role')))
@section('content')
<form role="form" class="form-horizontal" method="POST" action="{{ route('roles.store') }}">
    @csrf
    <div class="form-body">
        @component('input', ['type' => 'text', 'label' => 'Name', 'required' => true])
            name
        @endcomponent
        <div class="form-group form-md-line-input">
            <label class="col-md-2 control-label">{{ ucfirst(__('permissions')) }}</label>
            <div class="col-md-10">
                @foreach(config('permission.models.permission')::all() as $permission)
                <div class="col-md-3">
                    <div class="md-checkbox-inline">
                        <div class="md-checkbox">
                            <input type="checkbox" id="permission_{{ $permission->id }}" name="permissions[]" class="md-check"  value="{{ $permission->id }}" @if(old('permissions')) @if(in_array($permission->id, old('permissions'))) checked @endif @endif >
                            <label for="permission_{{ $permission->id }}">
                                <span></span>
                                <span class="check"></span>
                                <span class="box"></span> {{ $permission->name }}</label>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
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
    $('.js-example-basic-single').select2({     theme: "classic"     });
});
</script>
@endsection