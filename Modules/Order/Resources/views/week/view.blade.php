@extends('dashboard.layouts.app')
@section('title', __('Viewing').' '.ucfirst(__('Week')))
@section('content')
<div class="panel panel-default">
    <div class="panel-heading">{{ __('User') }}</div>
    <div class="panel-body">{{ $row->user()->first()->name ?? '' }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Captain') }}</div>
    <div class="panel-body">{{ $row->captain()->first()->name ?? '' }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Status') }}</div>
    <div class="panel-body">{{ $row->status()->first()->name ?? '' }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Delivery location') }}</div>
    <div class="panel-body">
        <div id="delivery-canvas" class="form-control"></div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Buy locations') }}</div>
    <div class="panel-body">
        <div id="buy-canvas" class="form-control"></div>
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Captain rate') }}</div>
    <div class="panel-body">
        {{ __('Rate') }} : {{ $row->captain_rate }} <br />
        {{ __('Review') }} : {{ $row->captain_review }} <br />
        @if($row->captain_review_image)
        {{ __('Image') }} : <img src="{{ route('file_show', $row->captain_review_image) }}" style="height: 60px" /> <br />
        @endif
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Payment method') }}</div>
    <div class="panel-body">{{ $row->payment_method }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Delivery fee') }}</div>
    <div class="panel-body">{{ $row->delivery_fee }}</div>
</div>
@if($row->payment_coupon)
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Coupon') }}</div>
    <div class="panel-body">{{ $row->payment_coupon }}</div>
</div>
@endif
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Shops') }}</div>
    <div class="panel-body">
        @foreach ($row->buy_locations()->get() as $buy_location)
        <div class="panel panel-default">
            <div class="panel-heading">{{ $buy_location->name }}</div>
            <div class="panel-body">
                @foreach($buy_location->items()->get() as $item)
                <div class="col-md-3">
                    {{ __('Name') }} : {{ $item->name }} <br />
                    {{ __('Quantity') }} : {{ $item->qty }} <br />
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
@section('css')
<style>
    #delivery-canvas, #buy-canvas{
        height: 400px;
    }
</style>
@endsection
@section('js')
<script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyA9UBKQHciVMSJZEoM640mtwKkTXavjrD4&libraries=places"></script>
<script>
var map = new google.maps.Map(document.getElementById('delivery-canvas'), {
    center:{
        lat: {{ $row->delivery_lat }},
        lng: {{ $row->delivery_lng }}
    },
    zoom: 15
});
var marker = new google.maps.Marker({
    position:{
        lat: {{ $row->delivery_lat }},
        lng: {{ $row->delivery_lng }}
    },
    map: map,
    draggable: false
});
@foreach($row->buy_locations()->get() as $buy_location)
@if($loop->first)
var map{{ $loop->index }} = new google.maps.Map(document.getElementById('buy-canvas'), {
    center:{
        lat: {{ $buy_location->lat }},
        lng: {{ $buy_location->lng }}
    },
    zoom: 15
});
@endif
var marker{{ $loop->index }} = new google.maps.Marker({
    position:{
        lat: {{ $buy_location->lat }},
        lng: {{ $buy_location->lng }}
    },
    map: map0,
    draggable: false
});
@endforeach
</script>
@endsection