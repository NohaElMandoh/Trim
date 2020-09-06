@extends('dashboard.layouts.app')
@section('title', __('Viewing').' '.ucfirst(__('governorate')))
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Name') }}</div>
    <div class="panel-body">{{ $row->name }}</div>
</div>
@endsection
@section('css')

@endsection
@section('js')

@endsection