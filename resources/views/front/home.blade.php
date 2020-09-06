@extends('front.layouts.app')
@section('content')
<!--Start Section2---->

	<section class="sex_shap_sec">
		<div class="sex_shap_div"></div>
		<div class="container">
			<div class="row">

				<div class="col-md-6 img-col">
					<img src="{{ route('file_show', $settings->header_screenshot) }}">
				</div>
				<div class="col text-col text-center">
					<h1 class="">{{ __('common.Trim Application') }}</h1>
					<p>{{ __('common.Header section description') }}</p>
					<a href="{{ $settings->app_store_user_app }}" target="_blank"><img class="app-img" src="{{ route('file_show', $settings->app_store_logo) }}"></a>
					<a href="{{ $settings->google_play_user_app }}" target="_blank"><img class="googl-img" src="{{ route('file_show', $settings->google_play_logo) }}"></a>
				</div>
			</div>
		</div>


	</section>

	<!--End Section2---->


	<!--Start Section About App ---->

	<section class="about-app">

		<div class="container">

			<div class="row">

				<div class="col right-col">

					<h2>{{ __('common.About application') }}</h2>

					<p class="lead">{!! $settings->description !!}</p>

				</div>
				<div class="col-md-6 left-col text-center ">

					<img class="top-img" src="{{ route('file_show', $settings->logo) }}">
					<img class="border-img wow fadeOutRightBig" src="{{ asset('dest/img/border.png') }}">

				</div>
			</div>
		</div>
	</section>

	<!--End Section About App ---->


	<!--Start Section  App features ---->

	<section class="App-features">

		<div class="container">
			<div class="row Features-row">
				<div class="col-md-5 left-col-img">
					<img src="{{ route('file_show', $settings->app_features_image) }}">
				</div>
				<div class=" col right-col-features">
					<h2>{{ __('common.App features') }}</h2>
					<p class="lead">{{ __('common.App features description') }}</p>
					<div class="row">
						@foreach ($features->chunk(ceil(count($features)/3)) as $chunk)
							<div class="col">
								<ul class="list-unstyled">
									@foreach ($chunk as $feature)
										<li><img src="{{ route('file_show', $feature->image) }}"><span>{{ $feature->title }}</span></li>
									@endforeach
								</ul>
							</div>
						@endforeach
					</div>
				</div>
			</div>
		</div>
	</section>

	<!--End Section  App features ---->



	<!--Start Section  Delivery Men ---->

	<section class="Delivery-Men text-center">
		<div class="container">
			<div class="row Delivery-row">
				<div class="col-md-4 Delivery-Men-img">
					<img src="{{ route('file_show', $settings->delivery_image) }}">
				</div>
				<div class="col Delivery-right-col">
					<h3>{{ __('common.If you a delivery man') }}</h3>
					<p>{{ __('common.If you a delivery man description') }}</p>
                    <a href="{{ $settings->app_store_captain_app }}" target="_blank"><img class="app-img" src="{{ route('file_show', $settings->app_store_logo) }}"></a>
					<a href="{{ $settings->google_play_captain_app }}" target="_blank"><img class="google-img" src="{{ route('file_show', $settings->google_play_logo) }}"></a>
				</div>
			</div>
		</div>
	</section>

	<!--End Section  Delivery Men ---->

	<!--Start Section  Screen shots ---->

	<section class="Screen-shots text-center">

		<div class="container">

			<h2>{{ __('common.Screen shots') }}</h2>
			<p>{{ __('common.Screen shots description') }} </p>
			<div class="owl-carousel owl-theme">
				@foreach($screenshots as $screenshot)
				<div class="item"><img src="{{ route('file_show', $screenshot->image) }}"></div>
				@endforeach
			</div>

		</div>
	</section>

	<!--End Section  Screen shots ---->
@endsection