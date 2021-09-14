@extends('dashboard.layouts.app')
@section('title', __('Viewing').' '.ucfirst(__('factory')))
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
    <div class="panel-heading">{{ __('address') }}</div>
    <div class="panel-body">{{ $row->address }}</div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">{{ __('Sales manager name') }}</div>
    <div class="panel-body">{{ $row->name_sales }}</div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">{{ __('Sales phone') }}</div>
    <div class="panel-body">{{ $row->phone2 }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Image') }}</div>
    <div class="panel-body">
        <div class="fileinput-new thumbnail" style="width: 200px; height: 200px;">
            <img src="{{ asset('/'.$row->image)}}" />
        </div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Points') }}</div>
    <div class="panel-body">{{ $row->points }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ ucfirst(__('roles')) }}</div>
    <div class="panel-body">
        <div class="row">

            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">

                {{ucfirst(__('factories'))}}

</div>

        </div>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">{{ __('Active') }}</div>
    <div class="panel-body">{{ $row->is_active }}</div>
</div>
@endsection
@section('css')

@endsection
@section('js')

@endsection
