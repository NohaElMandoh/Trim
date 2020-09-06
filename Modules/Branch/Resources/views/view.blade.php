@extends('dashboard.layouts.app')
@section('title', __('Viewing').' '.ucfirst(__('branch')))
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Salon') }}</div>
    <div class="panel-body">{{ $row->user()->first()->name ?? '' }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Address') }}</div>
    <div class="panel-body">{{ $row->address }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('lat') }}</div>
    <div class="panel-body">{{ $row->lat }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('lng') }}</div>
    <div class="panel-body">{{ $row->lng }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Order') }}</div>
    <div class="panel-body">{{ $row->order }}</div>
</div>
@endsection
