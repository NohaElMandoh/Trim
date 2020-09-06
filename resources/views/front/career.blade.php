@extends('front.layouts.app')
@section('css')
<link rel="stylesheet" href="{{ asset('dest/css/career.css') }}">
@endsection
@section('content')
<section class="text-center career">
    <div class="container">
        <h1>{{ __('common.Career') }}</h1>
        <form method="POST" action="{{ route('career') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <input type="text" class="form-control" placeholder="{{ __('common.Enter your name') }}" name="name" value="{{ old('name') }}">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" placeholder="{{ __('common.Enter your email') }}" name="email" value="{{ old('email') }}">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" placeholder="{{ __('common.Enter your phone') }}" name="phone" value="{{ old('phone') }}">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" placeholder="{{ __('common.Enter your job') }}" name="job" value="{{ old('job') }}">
            </div>
            <div class="custom-file">
                <input type="file" class="custom-file-input" id="validatedCustomFile" name="cv" required>
                <label class="custom-file-label" for="validatedCustomFile">{{ __('common.Upload your CV') }}</label>
            </div>
            <button type="submit" class="btn btn-primary submitBtn">{{ __('common.Send') }}</button>
        </form>
    </div>
</section>
@endsection