@extends('dashboard.layouts.app')
@section('title', __('Viewing').' '.ucfirst(__('address')))
@section('content')
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
