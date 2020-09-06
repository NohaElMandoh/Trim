@extends('dashboard.layouts.app')
@section('title', __('Viewing').' '.ucfirst(__('captain')))
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Name') }}</div>
    <div class="panel-body">{{ $row->name }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('E-Mail Address') }}</div>
    <div class="panel-body">{{ $row->email }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Phone') }}</div>
    <div class="panel-body">{{ $row->phone }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Image') }}</div>
    <div class="panel-body">
        <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;">
            <img src="{{ route('file_show', $row->image) }}" /> 
        </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Captain ID') }}</div>
    <div class="panel-body"><a href="{{ route('file_show', $row->id_photo) }}" target="_blank">{{ $row->id_photo }}</a></div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Active') }}</div>
    <div class="panel-body">{{ $row->is_active }}</div>
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
    <div class="panel-heading">{{ ucfirst(__('Gender')) }}</div>
    <div class="panel-body">{{ __(''.$row->gender) }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Points') }}</div>
    <div class="panel-body">{{ $row->points }}</div>
</div>
@endsection
@section('css')

@endsection
@section('js')

@endsection