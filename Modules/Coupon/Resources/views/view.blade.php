@extends('dashboard.layouts.app')
@section('title', __('Viewing').' '.ucfirst(__('coupon')))
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Title') }}</div>
    <div class="panel-body">{{ $row->title }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Code') }}</div>
    <div class="panel-body">{{ $row->code }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Name') }}</div>
    <div class="panel-body">{{ $row->name }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Price') }}</div>
    <div class="panel-body">{{ $row->price }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Duration') }}</div>
    <div class="panel-body">{{ $row->duration }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Usage number of times') }}</div>
    <div class="panel-body">{{ $row->usage_number_times }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Image') }}</div>
    <div class="panel-body"><img src="{{ route('file_show', $row->image) }}" style="height: 100px" /></div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Any where orders') }}</div>
    <div class="panel-body">{{ $row->anywhere }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('More way orders') }}</div>
    <div class="panel-body">{{ $row->moreway }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('One way orders') }}</div>
    <div class="panel-body">{{ $row->oneway }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('OQ orders') }}</div>
    <div class="panel-body">{{ $row->oq }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Week orders') }}</div>
    <div class="panel-body">{{ $row->week }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Governorate') }}</div>
    <div class="panel-body">{{ $row->governorate()->first()->name ?? '' }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('City') }}</div>
    <div class="panel-body">{{ $row->city()->first()->name ?? '' }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Roles') }}</div>
    <div class="panel-body">
        @foreach($row->roles()->get() as $role)
        <p>{{ $role->name }}</p> <br />
        @endforeach
    </div>
</div>
@endsection
@section('css')

@endsection
@section('js')

@endsection