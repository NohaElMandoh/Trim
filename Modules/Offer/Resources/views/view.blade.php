@extends('dashboard.layouts.app')
@section('title', __('Viewing').' '.ucfirst(__('offer')))
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Name') }}</div>
    <div class="panel-body">{{ $row->name }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">Service</div>
    <div class="panel-body">{{ $row->service->title }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Description') }}</div>
    <div class="panel-body">{{ $row->description }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Image') }}</div>
    {{-- <div class="panel-body"><img src="{{ route('file_show', $row->image) }}" /></div> --}}
    <img src="@if (!empty($row->image)) {{ url($row->image)}} @else url('uploads/user.png') @endif" /> 
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Price') }}</div>
    <div class="panel-body">{{ $row->price }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('User') }}</div>
    <div class="panel-body">{{ $row->user()->first()->name ?? '' }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Category') }}</div>
    <div class="panel-body">{{ $row->category()->first()->name ?? '' }}</div>
</div>
{{-- <div class="panel panel-default">
    <div class="panel-heading">{{ __('Sponsored') }}</div>
    <div class="panel-body">{{ $row->is_sponsored }}</div>
</div> --}}
@endsection
@section('css')

@endsection
@section('js')

@endsection