@extends('dashboard.layouts.app')
@section('title', __('Viewing').' '.ucfirst(__('service')))
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Title') }}</div>
    <div class="panel-body">{{ $row->title }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Description') }}</div>
    <div class="panel-body">{{ $row->description }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Price type') }}</div>
    <div class="panel-body">{{ $row->price_type }}</div>
</div>
@if($row->price_type == 'normal')
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Price') }}</div>
    <div class="panel-body">{{ $row->price }}</div>
</div>
@else
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Min price') }}</div>
    <div class="panel-body">{{ $row->min_price }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Max price') }}</div>
    <div class="panel-body">{{ $row->max_price }}</div>
</div>
@endif
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Gender') }}</div>
    <div class="panel-body">{{ __(''.$row->gender) }}</div>
</div>
@endsection
@section('css')

@endsection
@section('js')

@endsection