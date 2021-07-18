@extends('front.layouts.app')
@section('css')
    <!-- Bootstrap CSS -->


    <style>
        .header {
            background: #00C9FF;
        }

        .bi {
            color: #00C9FF;
        }

        .price {
            color: white;
            font-size: 150px;
            font-weight: 800;
            padding-top: -80% !important;
        }

        /* The flip card container - set the width and height to whatever you want. We have added the border property to demonstrate that the flip itself goes out of the box on hover (remove perspective if you don't want the 3D effect */
        .flip-card {
            background-color: transparent;
            width: auto;
            height: auto;
            perspective: 1000px;
            /* Remove this if you don't want the 3D effect */
        }

        /* This container is needed to position the front and back side */
        .flip-card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            text-align: center;
            transition: transform 0.8s;
            transform-style: preserve-3d;
        }

        /* Do an horizontal flip when you move the mouse over the flip box container */
        .flip-card:hover .flip-card-inner {
            transform: rotateY(180deg);
        }

        /* Position the front and back side */
        .flip-card-front,
        .flip-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            -webkit-backface-visibility: hidden;
            /* Safari */
            backface-visibility: hidden;
        }

        /* Style the front side (fallback if image is missing) */
        .flip-card-front {
            background-color: #00C9FF;
            color: white;
            height: auto;
            padding: 50px 0px;
        }

        /* Style the back side */
        .flip-card-back {
            background-color: white;
            color: black;
            transform: rotateY(180deg);
            padding: 50px 0px;
        }

    </style>
@endsection
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
                    <a href="{{ $settings->app_store_user_app }}" target="_blank"><img class="app-img"
                            src="{{ route('file_show', $settings->app_store_logo) }}"></a>
                    <a href="{{ $settings->google_play_user_app }}" target="_blank"><img class="googl-img"
                            src="{{ route('file_show', $settings->google_play_logo) }}"></a>
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
                                    <li><img
                                            src="{{ route('file_show', $feature->image) }}"><span>{{ $feature->title }}</span>
                                    </li>
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
                    <a href="{{ $settings->app_store_captain_app }}" target="_blank"><img class="app-img"
                            src="{{ route('file_show', $settings->app_store_logo) }}"></a>
                    <a href="{{ $settings->google_play_captain_app }}" target="_blank"><img class="google-img"
                            src="{{ route('file_show', $settings->google_play_logo) }}"></a>
                </div>
            </div>
        </div>
    </section>

    <!--End Section  Delivery Men ---->
    <!--  pricing area start -->

        <div class="container p-5" style="display: none">
            <div class="row">
                <div class="col-lg-4 col-md-12 mb-4">
                    <div class="h-100 flip-card">
                        <div class="flip-card-inner">
                            <div class="flip-card-front">

                                <span class="price">$8</span><br>/month
                                <br>
                                <h2 class="card-title">Basic</h2>
                                <small>Individual</small>
                            </div>
                            <div class="flip-card-back">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><svg xmlns="http://www.w3.org/2000/svg" width="16"
                                            height="16" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                                            <path
                                                d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z" />
                                        </svg> Cras justo odio</li>
                                    <li class="list-group-item"><svg xmlns="http://www.w3.org/2000/svg" width="16"
                                            height="16" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                                            <path
                                                d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z" />
                                        </svg> Dapibus ac facilisis in</li>
                                    <li class="list-group-item"><svg xmlns="http://www.w3.org/2000/svg" width="16"
                                            height="16" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                                            <path
                                                d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z" />
                                        </svg> Vestibulum at eros</li>
                                </ul>
                                <button class="my-5 btn btn-outline-success btn-lg">Select</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-12 mb-4">
                    <div class="h-100 flip-card">
                        <div class="flip-card-inner">
                            <div class="flip-card-front">

                                <span class="price">$20</span><br>/month
                                <br>
                                <h2 class="card-title">Standard</h2>
                                <small>Small Business</small>
                            </div>
                            <div class="flip-card-back">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><svg xmlns="http://www.w3.org/2000/svg" width="16"
                                            height="16" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                                            <path
                                                d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z" />
                                        </svg> Cras justo odio</li>
                                    <li class="list-group-item"><svg xmlns="http://www.w3.org/2000/svg" width="16"
                                            height="16" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                                            <path
                                                d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z" />
                                        </svg> Dapibus ac facilisis in</li>
                                    <li class="list-group-item"><svg xmlns="http://www.w3.org/2000/svg" width="16"
                                            height="16" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                                            <path
                                                d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z" />
                                        </svg> Vestibulum at eros</li>
                                </ul>
                                <button class="my-5 btn btn-outline-success btn-lg">Select</button>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-lg-4 col-md-12 mb-4">
                    <div class="h-100 flip-card">
                        <div class="flip-card-inner">
                            <div class="flip-card-front">

                                <span class="price">$40</span><br>/month
                                <br>
                                <h2 class="card-title">Premium</h2>
                                <small>Large Company</small>
                            </div>
                            <div class="flip-card-back">
                                <ul class="list-group list-group-flush">
                                    <li class="list-group-item"><svg xmlns="http://www.w3.org/2000/svg" width="16"
                                            height="16" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                                            <path
                                                d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z" />
                                        </svg> Cras justo odio</li>
                                    <li class="list-group-item"><svg xmlns="http://www.w3.org/2000/svg" width="16"
                                            height="16" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                                            <path
                                                d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z" />
                                        </svg> Dapibus ac facilisis in</li>
                                    <li class="list-group-item"><svg xmlns="http://www.w3.org/2000/svg" width="16"
                                            height="16" fill="currentColor" class="bi bi-check" viewBox="0 0 16 16">
                                            <path
                                                d="M10.97 4.97a.75.75 0 0 1 1.07 1.05l-3.99 4.99a.75.75 0 0 1-1.08.02L4.324 8.384a.75.75 0 1 1 1.06-1.06l2.094 2.093 3.473-4.425a.267.267 0 0 1 .02-.022z" />
                                        </svg> Vestibulum at eros</li>
                                </ul>
                                <button class="my-5 btn btn-outline-success btn-lg">Select</button>
                            </div>
                        </div>
                    </div>
                </div>






            </div>
        </div>

    <!--  pricing area end -->
    <!--Start Section  Screen shots ---->

    <section class="Screen-shots text-center">

        <div class="container">

            <h2>{{ __('common.Screen shots') }}</h2>
            <p>{{ __('common.Screen shots description') }} </p>
            <div class="owl-carousel owl-theme">
                @foreach ($screenshots as $screenshot)
                    <div class="item"><img src="{{ route('file_show', $screenshot->image) }}"></div>
                @endforeach
            </div>

        </div>
    </section>

    <!--End Section  Screen shots ---->

@endsection
