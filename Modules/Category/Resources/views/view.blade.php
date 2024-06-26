@extends('dashboard.layouts.app')
@section('title', __('Viewing').' '.ucfirst(__('category')))
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Name') }}</div>
    <div class="panel-body">{{ $row->name }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Image') }}</div>
    {{-- <div class="panel-body"><img src="{{ route('file_show', $row->image) }}" /></div> --}}
    <img src="@if (!empty($row->image)) {{ url($row->image)}} @else url('uploads/user.png') @endif" /> 
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Order') }}</div>
    <div class="panel-body">{{ $row->order }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('For offers') }}</div>
    <div class="panel-body">{{ $row->for_offers }}</div>
</div>
@endsection
@section('css')

@endsection
@section('js')

@endsection