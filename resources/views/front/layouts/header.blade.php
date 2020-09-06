<!DOCTYPE html>
<html>

<head>

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="shortcut icon" href="{{ route('file_show', $settings->icon) }}" type="image/png">
	<title>{{ $settings->title }}</title>
	<link rel="stylesheet" href="{{ asset('dest/css/bootstrap.min.css')}}">
	@if(App::getLocale() == 'ar')
	<link rel="stylesheet" href="{{ asset('dest/css/bootstrap-rtl.css') }}">
	@endif

	<link rel="stylesheet" href="{{ asset('dest/css/font-awesome.min.css') }}">
	<link rel="stylesheet" href="{{ asset('dest/css/animate.css') }}">
	<link rel="stylesheet" href="{{ asset('dest/css/owl.carousel.min.css') }}">
	<link rel="stylesheet" href="{{ asset('dest/css/owl.theme.default.min.css') }}">
	<link rel="stylesheet" href="{{ asset('dest/css/style.css') }}">
	@if(App::getLocale() == 'ar')
	<link rel="stylesheet" href="{{ asset('dest/css/RTL.css') }}">
	@endif
	<link rel="stylesheet" href="{{ asset('dest/css/mediaStyle.css') }}">
	@if(App::getLocale() == 'ar')
	<link rel="stylesheet" href="{{ asset('dest/css/Media_RTL.css') }}">
	@endif
	@yield('css')
</head>

<body>

	<!-- Start navbar----->

	<nav class="navbar navbar-expand-lg navbar-light ">

		<a class="navbar-brand" href="{{ route('home') }}">
			<img src="{{ route('file_show', $settings->header_logo) }}">
		</a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
			aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
			<ul class="navbar-nav ml-auto">
                @if($email = \Modules\Email\Entities\Email::orderBy('order')->first())
				<li class="nav-item ">
					<a class="nav-link" href="mailTo:{{ $email->email }}" target="_blank"><img src="{{ asset('dest/img/email.png') }}"><span>{{ $email->email }}</span></a>
				</li>
                @endif
                @if($phone = \Modules\Phone\Entities\Phone::orderBy('order')->first())
				<li class="nav-item ">
					<a class="nav-link" href="tel:{{ $phone->phone }}" target="_blank"><img src="{{ asset('dest/img/headphones.png') }}"><span>{{ $phone->phone }}</span></a>
				</li>
                @endif
                @if($address = \Modules\Address\Entities\Address::orderBy('order')->first())
                <li class="nav-item ">
					<a class="nav-link" href="https://www.google.com.eg/maps/place/{{ $address->lat }}+{{ $address->lng }}" target="_blank"><img src="{{ asset('dest/img/pin.png')}}"><span>{{ $address->address }}</span></a>
				</li>
                @endif
			</ul>
		</div>
	</nav>