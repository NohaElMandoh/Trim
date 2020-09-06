@extends('dashboard.layouts.app')
@section('title', __('Creating').' '.ucfirst(__('address')))
@section('content')
<form role="form" class="form-horizontal" method="POST" action="{{ route('addresses.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="form-body">
        @component('input_trans', ['type' => 'text', 'label' => 'Address', 'required' => true])
            address
        @endcomponent
        @component('input', ['type' => 'number', 'label' => 'Order', 'required' => true])
            order
        @endcomponent
        <div class="form-group form-md-line-input">
            <label for="search" class="col-md-2 control-label">{{ __('Search') }}</label>
            <div class="col-md-10">
                <input id="search"  placeholder="{{ __('Search') }}" class="form-control">
                <div class="form-control-focus"> </div>
            </div>
        </div>
        <input type="text" style="display: none" name="lat" id="lat" value="30.117215498424" />
        <input type="text" style="display: none" name="lng" id="lng" value="31.319449068262" />
        <div class="form-group">
            <div id="map-canvas" class="form-control"></div>
        </div>
    </div>
    <div class="form-actions">
        <div class="row">
            <div class="col-md-offset-2 col-md-10">
                <button type="rest" class="btn btn-default">{{ __('Cancel')}}</button>
                <button type="submit" class="btn btn-primary">{{ __('Submit')}}</button>
            </div>
        </div>
    </div>
</form>
@endsection
@section('css')
<style>
    #map-canvas{
        height: 600px;
    }
</style>
@endsection
@section('js')
<script type="text/javascript" src="https://maps.google.com/maps/api/js?key=AIzaSyA9UBKQHciVMSJZEoM640mtwKkTXavjrD4&libraries=places"></script>
<script>
var map = new google.maps.Map(document.getElementById('map-canvas'), {
    center:{
        lat: 30.117215498424,
        lng: 31.319449068262
    },
    zoom: 15
});
var marker = new google.maps.Marker({
    position:{
        lat: 30.117215498424,
        lng: 31.319449068262
    },
    map: map,
    draggable: true
});
google.maps.event.addListener(marker, 'dragend', function (){
    var lat = marker.getPosition().lat();
    var lng = marker.getPosition().lng();
    $("#lat").val(lat);
    $("#lng").val(lng);
});
@foreach(LaravelLocalization::getSupportedLanguagesKeys() as $local)
var address{{ $loop->index }}  = new google.maps.places.SearchBox(document.getElementById("{{trans('common.address')}}[{{ $local }}]"));
@endforeach
var searchBox  = new google.maps.places.SearchBox(document.getElementById('search'));
google.maps.event.addListener(searchBox , 'places_changed' , function(){
    var places = searchBox.getPlaces();
    var bounds = new google.maps.LatLngBounds();
     var i, place;
     for (i = 0; place = places[i]; i++) {
        bounds.extend(place.geometry.location)
        marker.setPosition(place.geometry.location)
     }
     map.fitBounds(bounds);
     map.setZoom(15);

});
google.maps.event.addListener(marker , 'position_changed' , function(){
    var lat = marker.getPosition().lat();
    var lng = marker.getPosition().lng();
    $("#lat").val(lat);
    $("#lng").val(lng);
});
</script>
@endsection
