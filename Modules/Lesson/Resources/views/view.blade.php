@extends('dashboard.layouts.app')
@section('title', __('Viewing').' '.ucfirst(__('lesson')))
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Course') }}</div>
    <div class="panel-body">{{ $row->course()->first()->name ?? '' }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Name') }}</div>
    <div class="panel-body">{{ $row->name }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Image') }}</div>
    <div class="panel-body"><img src="{{ route('file_show', $row->image) }}" /></div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Video') }}</div>
    <div class="panel-body"><a href="{{ route('file_show', $row->video) }}">{{ route('file_show', $row->video) }}</a></div>
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