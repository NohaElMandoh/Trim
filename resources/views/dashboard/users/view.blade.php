@extends('dashboard.layouts.app')
@section('title', __('Viewing').' '.ucfirst(__('user')))
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Name') }}</div>
    <div class="panel-body">{{ $row->name }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('E-Mail Address') }}</div>
    <div class="panel-body">{{ $row->email }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Phone') }}</div>
    <div class="panel-body">{{ $row->phone }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Image') }}</div>
    <div class="panel-body">
        <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;">
            <img src="{{ route('file_show', $row->image) }}" /> 
        </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Points') }}</div>
    <div class="panel-body">{{ $row->points }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ ucfirst(__('roles')) }}</div>
    <div class="panel-body">
        <div class="row">
        @forelse ($row->roles()->get() as $role)
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">{{ $role->name }}</div>
        @empty
            <div class="alert alert-danger">{{ __('No roles') }}</div>
        @endforelse
        </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ ucfirst(__('Gender')) }}</div>
    <div class="panel-body">{{ __(''.$row->gender) }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Governorate') }}</div>
    <div class="panel-body">{{ $row->governorate()->first()->name ?? '' }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('City') }}</div>
    <div class="panel-body">{{ $row->city()->first()->name ?? '' }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Active') }}</div>
    <div class="panel-body">{{ $row->is_active }}</div>
</div>
@endsection
@section('css')

@endsection
@section('js')

@endsection