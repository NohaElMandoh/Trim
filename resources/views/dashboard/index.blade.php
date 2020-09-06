@extends('dashboard.layouts.app')
@section('title', __('Dashboard'))
@section('content')
<div class="row">
    @can('user.list')
    <div class="col-lg-3 col-xs-6">
        <a href="">
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{{ \App\User::whereDoesntHave('roles', function ($query) {
                            $query->where('name', 'shop')->orWhere('name', 'captain');
                        })->count() }}</h3>
                    <p>{{ ucfirst(__('users')) }}</p>
                    <div class="icon">
                        <i class="fa fa-user"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>
    @endcan
</div>
<div class="clearfix"> </div> <br /> 
<h2 class="text-center">{{ __('Send notifications') }}</h2>
<form role="form" class="form-horizontal" method="POST" action="{{ route('dashboard.send_notification') }}" >
    @csrf
    <div class="form-body">
        @component('input', ['type' => 'text', 'label' => 'Name', 'required' => false])
            name
        @endcomponent
        <div class="form-group form-md-line-input">
            <label class="col-md-2 control-label">{{ ucfirst(__('Type')) }} <span class="required">*</span></label>
            <div class="col-md-10">
                <select class="js-example-basic-single js-states form-control" id="type" name="type">
                    <option value="user_app" {{ old('type') == 'user_app' ? 'selected': ''}}>{{ __('User app') }}</option>
                    <option value="captain_app" {{ old('type') == 'captain_app' ? 'selected': ''}}>{{ __('Captain app') }}</option>
                </select>
                <div class="form-control-focus"> </div>
            </div>
        </div>
        @component('input', ['type' => 'text', 'label' => 'Title', 'required' => true])
            title
        @endcomponent
        @component('input', ['type' => 'textarea', 'label' => 'Description', 'required' => false])
            description
        @endcomponent
    </div>
    <div class="form-actions">
        <div class="row">
            <div class="col-md-offset-2 col-md-10">
                <button type="submit" class="btn btn-primary">{{ __('Send') }}</button>
            </div>
        </div>
    </div>
</form>
@endsection
@section('js')
<!-- DataTables -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/dataTables.bootstrap.min.js') }}"></script>
@endsection
@section('css')
@if(App::getLocale() != 'ar')
<link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap.css') }}">
@else 
<link rel="stylesheet" href="{{ asset('plugins/datatables/dataTables.bootstrap-rtl.css') }}">
@endif
@endsection