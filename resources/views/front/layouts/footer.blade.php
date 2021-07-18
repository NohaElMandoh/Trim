<!--Start footer ---->

	<section class="footer">

		<div class="container">


			<div class="row footer-row">

				<div class="col follow-us">

					<h4>{{ __('common.Follow Us On') }}</h4>
					<div class="follow-img">
                        @foreach(\Modules\Social\Entities\Social::orderBy('order')->get() as $social)
						<a href="{{ $social->url }}" target="_blank"><img src="{{ route('file_show', $social->image) }}"></a>
						@endforeach
					</div>
				</div>
				<div class="col contact-us">

					<h4>{{ __('common.Contact Us') }}</h4>
                    @foreach(\Modules\Email\Entities\Email::orderBy('order')->get() as $email)
					<a href="mailTo:{{ $email->email }}" target="_blank"><img src="{{ asset('dest/img/email.png') }}"></a>
                    @endforeach
                    @foreach(\Modules\Phone\Entities\Phone::orderBy('order')->get() as $phone)
					<a href="tel:{{ $phone->phone }}" target="_blank"><img src="{{ asset('dest/img/headphones.png') }}"></a>
                    @endforeach
                    @foreach(\Modules\Address\Entities\Address::orderBy('order')->get() as $address)
					<a href="https://www.google.com.eg/maps/place/{{ $address->lat }}+{{ $address->lng }}" target="_blank"><img src="{{ asset('dest/img/pin.png') }}"></a>
                    @endforeach
				</div>
			</div>
		</div>
	</section>

	<!--End footer ---->
	
	<script src="{{ asset('dest/js/jquery-3.2.1.min.js') }}"></script>
	<script src="{{ asset('dest/js/jquery.js') }}"></script>
	<script src="{{ asset('dest/js/jquery2.js') }}"></script>
	<script src="{{ asset('dest/js/popper.min.js')}}"></script>
	<script src="{{ asset('dest/js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('dest/js/main.js')}}"></script>
	<script src="{{ asset('dest/js/owl.carousel.min.js') }}"></script>
	<script src="{{ asset('dest/js/wow.min.js') }}"></script>
	<script>
		new WOW().init();
	</script>
</body>

</html>