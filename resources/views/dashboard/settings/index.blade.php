@extends('dashboard.layouts.app')
@section('title', __('Editing').' '.ucfirst(__('Settings')))
@section('content')
<form role="form" class="form-horizontal" method="POST" action="{{ route('settings.update', $site->id) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <div class="form-body">
        @component('input_trans', ['type' => 'text', 'label' => 'Title', 'required' => true, 'model' => $site])
            title
        @endcomponent
        @component('input_trans', ['type' => 'textarea', 'label' => 'Description', 'required' => true, 'model' => $site])
            description
        @endcomponent
        @component('input_trans', ['type' => 'text', 'label' => 'Copyrights', 'required' => false, 'model' => $site])
            copyrights
        @endcomponent
        @component('input_trans', ['type' => 'textarea', 'label' => 'Privacy', 'required' => false, 'model' => $site])
            privacy
        @endcomponent
        @component('input_trans', ['type' => 'textarea', 'label' => 'How does OQ work ?', 'required' => false, 'model' => $site])
            how_it_works
        @endcomponent
        @component('input_trans', ['type' => 'textarea', 'label' => 'How to work in OQ ?', 'required' => false, 'model' => $site])
            work_in_oq
        @endcomponent
        @component('input', ['type' => 'number', 'label' => 'Point price', 'required' => true, 'value' => $site->point_price])
            point_price
        @endcomponent
        @component('input', ['type' => 'text', 'label' => 'Google play user app url', 'required' => false, 'value' => $site->google_play_user_app])
            google_play_user_app
        @endcomponent
        @component('input', ['type' => 'text', 'label' => 'Google play user captain url', 'required' => false, 'value' => $site->google_play_captain_app])
            google_play_captain_app
        @endcomponent
        @component('input', ['type' => 'text', 'label' => 'App store user app url', 'required' => false, 'value' => $site->app_store_user_app])
            app_store_user_app
        @endcomponent
        @component('input', ['type' => 'text', 'label' => 'App store captain app url', 'required' => false, 'value' => $site->app_store_captain_app])
            app_store_captain_app
        @endcomponent
        @component('input_image', ['width' => 55, 'height' => 55, 'label' => 'Header logo', 'src' => route('file_show', $site->header_logo)])
            header_logo
        @endcomponent
        @component('input_image', ['width' => 170, 'height' => 60, 'label' => 'Google play logo', 'src' => route('file_show', $site->google_play_logo)])
            google_play_logo
        @endcomponent
        @component('input_image', ['width' => 170, 'height' => 60, 'label' => 'App store logo', 'src' => route('file_show', $site->app_store_logo)])
            app_store_logo
        @endcomponent
        @component('input_image', ['width' => 350, 'height' => 550, 'label' => 'Header screenshot', 'src' => route('file_show', $site->header_screenshot)])
            header_screenshot
        @endcomponent
        @component('input_image', ['width' => 500, 'height' => 450, 'label' => 'App features image', 'src' => route('file_show', $site->app_features_image)])
            app_features_image
        @endcomponent
        @component('input_image', ['width' => 350, 'height' => 350, 'label' => 'Delivery image', 'src' => route('file_show', $site->delivery_image)])
            delivery_image
        @endcomponent
        @component('input_image', ['width' => 200, 'height' => 200, 'label' => 'Logo', 'src' => route('file_show', $site->logo)])
            logo
        @endcomponent
        @component('input_image', ['width' => 16, 'height' => 16, 'label' => 'Icon', 'src' => route('file_show', $site->icon)])
            icon
        @endcomponent
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


@endsection
@section('js')
<script>
    function readURL(input, id) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $(id).attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
    $(document).ready(function () {
        $("#header_logo").change(function() {
            readURL(this, "#header_logo_image");
        });
        $("#google_play_logo").change(function() {
            readURL(this, "#google_play_logo_image");
        });
        $("#app_store_logo").change(function() {
            readURL(this, "#app_store_logo_image");
        });
        $("#header_screenshot").change(function() {
            readURL(this, "#header_screenshot_image");
        });
        $("#app_features_image").change(function() {
            readURL(this, "#app_features_image_image");
        });
        $("#delivery_image").change(function() {
            readURL(this, "#delivery_image_image");
        });
        $("#logo").change(function() {
            readURL(this, "#logo_image");
        });
        $("#icon").change(function() {
            readURL(this, "#icon_image");
        });
    });
</script>
@endsection