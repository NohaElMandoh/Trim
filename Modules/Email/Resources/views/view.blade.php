@extends('dashboard.layouts.app')
@section('title', __('Viewing').' '.ucfirst(__('email')))
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Email') }}</div>
    <div class="panel-body">{{ $row->email }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Order') }}</div>
    <div class="panel-body">{{ $row->order }}</div>
</div>
@endsection
