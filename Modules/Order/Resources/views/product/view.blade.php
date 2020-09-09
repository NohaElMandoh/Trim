@extends('dashboard.layouts.app')
@section('title', __('Viewing').' '.ucfirst(__('Product')))
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">{{ __('User') }}</div>
    <div class="panel-body">{{ $row->user()->first()->name ?? '' }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Phone') }}</div>
    <div class="panel-body">{{ $row->phone }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Address') }}</div>
    <div class="panel-body">
        <p>{{ $row->address }}</p>
        <div id="address-canvas" class="form-control"></div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Status') }}</div>
    <div class="panel-body">{{ $row->status()->first()->name ?? '' }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Rate') }}</div>
    <div class="panel-body">
        {{ __('Rate') }} : {{ $row->rate }} <br />
        {{ __('Review') }} : {{ $row->review }} <br />
        @if($row->review_image)
        {{ __('Image') }} : <img src="{{ route('file_show', $row->review_image) }}" style="height: 60px" /> <br />
        @endif
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Payment method') }}</div>
    <div class="panel-body">{{ $row->payment_method }}</div>
</div>
@if($row->payment_coupon)
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Coupon') }}</div>
    <div class="panel-body">{{ $row->payment_coupon }}</div>
</div>
@endif
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Products') }}</div>
    <div class="panel-body">
        @foreach ($row->products()->get() as $product)
            <div class="col-md-3">
                {{ __('Name') }} : {{ $product->name }} <br />
                {{ __('Quantity') }} : {{ $product->pivot->qty }} <br />
                {{ __('Price') }} : {{ $product->price }} <br />
            </div>
        @endforeach
    </div>
</div>
@endsection
@section('css')
<style>
    #address-canvas{
        height: 400px;
    }
</style>
@endsection
@section('js')
<script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyA9UBKQHciVMSJZEoM640mtwKkTXavjrD4&libraries=places"></script>
<script>
var map = new google.maps.Map(document.getElementById('address-canvas'), {
    center:{
        lat: {{ $row->lat }},
        lng: {{ $row->lng }}
    },
    zoom: 15
});
var marker = new google.maps.Marker({
    position:{
        lat: {{ $row->lat }},
        lng: {{ $row->lng }}
    },
    map: map,
    draggable: false
});
</script>
@endsection