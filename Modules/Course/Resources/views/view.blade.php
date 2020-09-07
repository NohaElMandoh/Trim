@extends('dashboard.layouts.app')
@section('title', __('Viewing').' '.ucfirst(__('course')))
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
    <div class="panel-heading">{{ __('Image') }}</div>
    <div class="panel-body"><img src="{{ route('file_show', $row->image) }}" /></div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Price') }}</div>
    <div class="panel-body">{{ $row->price }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Order') }}</div>
    <div class="panel-body">{{ $row->order }}</div>
</div>
@endsection
@section('css')

@endsection
@section('js')

@endsection