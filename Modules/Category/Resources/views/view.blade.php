@extends('dashboard.layouts.app')
@section('title', __('Viewing').' '.ucfirst(__('category')))
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Name') }}</div>
    <div class="panel-body">{{ $row->name }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Shop') }}</div>
    <div class="panel-body">{{ $row->is_shop }}</div>
</div>
@endsection
@section('css')

@endsection
@section('js')

@endsection