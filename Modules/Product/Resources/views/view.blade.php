@extends('dashboard.layouts.app')
@section('title', __('Viewing').' '.ucfirst(__('product')))
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Name') }}</div>
    <div class="panel-body">{{ $row->name }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Image') }}</div>
    {{-- <div class="panel-body"><img src="{{ route('file_show', $row->image) }}" /></div> --}}
    <img src="@if (!empty($row->image))  {{url($row->image)}} @else url('uploads/product.png') @endif" width="200px" height="200px" />
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Price') }}</div>
    <div class="panel-body">{{ $row->price }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Category') }}</div>
    <div class="panel-body">{{ $row->category()->first()->name ?? '' }}</div>
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
