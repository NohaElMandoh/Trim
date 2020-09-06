@extends('dashboard.layouts.app')
@section('title', __('Viewing').' '.ucfirst(__('feature')))
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Title') }}</div>
    <div class="panel-body">{{ $row->title }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Image') }}</div>
    <div class="panel-body"><img src="{{ route('file_show', $row->image) }}" style="height: 30px;" /></div>
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