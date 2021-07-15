@extends('dashboard.layouts.app')
@section('title', __('Viewing').' '.ucfirst(__('subscription')))
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Title') }}</div>
    <div class="panel-body">{{ $row->title }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Description') }}</div>
    <div class="panel-body">{{ $row->desc }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Price') }}</div>
    <div class="panel-body">{{ $row->price }}</div>
</div>
<div class="panel panel-default">
    
    <div class="panel-heading">{{ __('Inested Of') }}</div>
    <div class="panel-body">{{ $row->origion_price }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Months') }}</div>
    <div class="panel-body">{{ $row->months }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Currency') }}</div>
    <div class="panel-body">{{ $row->currency }}</div>
</div>
@endsection
@section('css')

@endsection
@section('js')

@endsection