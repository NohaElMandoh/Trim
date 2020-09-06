@extends('dashboard.layouts.app')
@section('title', __('Viewing').' '.ucfirst(__('social')))
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">{{ __('URL') }}</div>
    <div class="panel-body">{{ $row->url }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Image') }}</div>
    <div class="panel-body"><img src="{{ route('file_show', $row->image) }}" /></div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Order') }}</div>
    <div class="panel-body">{{ $row->order }}</div>
</div>
@endsection
