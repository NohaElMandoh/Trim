@extends('dashboard.layouts.app')
@section('title', __('Viewing').' '.ucfirst(__('salon')))
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Name') }}</div>
    <div class="panel-body">{{ $row->name }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Description') }}</div>
    <div class="panel-body">{{ $row->description }}</div>
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
        <div class="fileinput-new thumbnail" style="width: 200px;">
            <img src="{{ route('file_show', $row->image) }}" /> 
        </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Cover') }}</div>
    <div class="panel-body">
        <div class="fileinput-new thumbnail" style="width: 400px;">
            <img src="{{ route('file_show', $row->cover) }}" /> 
        </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Commercial register') }}</div>
    <div class="panel-body"><a href="{{ route('file_show', $row->commercial_register) }}" target="_blank">{{ $row->commercial_register }}</a></div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Active') }}</div>
    <div class="panel-body">{{ $row->is_active }}</div>
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
    <div class="panel-heading">{{ ucfirst(__('Gender')) }}</div>
    <div class="panel-body">{{ __(''.$row->gender) }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Points') }}</div>
    <div class="panel-body">{{ $row->points }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Working days') }}</div>
    <div class="panel-body">
        @foreach($row->works()->get() as $work) 
        <div class="row">
            <div class="col-md-4">{{ __('From') }}: {{ $work->from }}</div>
            <div class="col-md-4">{{ __('To') }}: {{ $work->to }}</div>
            <div class="col-md-4">{{ __('Day') }}: {{ \App\User::days()[$work->day] }}</div>
        </div>
        @endforeach
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ ucfirst(__('services')) }}</div>
    <div class="panel-body">
        <div class="row">
        @forelse ($row->services()->get() as $service)
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">{{ $service->title }}</div>
        @empty
            <div class="alert alert-danger">{{ __('No services') }}</div>
        @endforelse
        </div>
    </div>
</div>
@endsection
@section('css')

@endsection
@section('js')

@endsection