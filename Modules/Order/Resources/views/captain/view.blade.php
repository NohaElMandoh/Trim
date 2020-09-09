@extends('dashboard.layouts.app')
@section('title', __('Viewing').' '.ucfirst(__('Captain')))
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
    <div class="panel-heading">{{ __('Captain') }}</div>
    <div class="panel-body">{{ $row->barber()->first()->name ?? '' }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Status') }}</div>
    <div class="panel-body">{{ $row->status()->first()->name ?? '' }}</div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Captain rate') }}</div>
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
    <div class="panel-heading">{{ __('Fast order') }}</div>
    <div class="panel-body">{{ $row->is_now }}</div>
</div>
@if($row->is_now == 0)
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Date') }}</div>
    <div class="panel-body">{{ \App\User::days()[$row->work_day()->first()->day] }}</div>
</div>
@endif
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Service') }}</div>
    <div class="panel-body">
        @foreach ($row->services()->get() as $service)
            <div class="col-md-3">
                {{ __('Name') }} : {{ $service->title }} <br />
                {{ __('Quantity') }} : {{ $service->pivot->qty }} <br />
                {{ __('Price') }} : {{ $service->price_type == 'normal' ? $service->price : $service->min_price . ' - '. $service->max_price }} <br />
            </div>
        @endforeach
    </div>
</div>
<div class="panel panel-default">
    <div class="panel-heading">{{ __('Offers') }}</div>
    <div class="panel-body">
        @foreach ($row->offers()->get() as $offer)
            <div class="col-md-3">
                {{ __('Name') }} : {{ $offer->name }} <br />
                {{ __('Quantity') }} : {{ $offer->pivot->qty }} <br />
                {{ __('Price') }} : {{ $offer->price }} <br />
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