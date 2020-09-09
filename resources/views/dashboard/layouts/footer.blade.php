<!-- REQUIRED JS SCRIPTS -->
<script>
    window.Laravel = {!! json_encode([
            'user' => auth()->check() ? auth()->user()->id : null,
        ]) !!};
    window.sound    = "{{ asset('to-the-point.mp3') }}";
</script>
<script src="https://cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>
<script src="{{ asset('js/app.js') }}"></script>
<!-- jQuery 2.2.3 -->
<script src="{{ asset('plugins/jQuery/jquery-2.2.3.min.js') }}"></script>
<!-- Bootstrap 3.3.6 -->
<script src="{{ asset('bootstrap/js/bootstrap.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('plugins/select2/select2.full.min.js') }}"></script>
<!-- Toaster -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
@yield('js')
<!-- Bootstrap WYSIHTML5 -->
<script src="{{ asset('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>
<!-- Slimscroll -->
<script src="{{ asset('plugins/slimScroll/jquery.slimscroll.min.js') }}"></script>
<!-- FastClick -->
<script src="{{ asset('plugins/fastclick/fastclick.js') }}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('dist/js/app.min.js') }}"></script>
<script>
    $(document).ready(function()
    {
        @if ($errors->any())
        @foreach ($errors->all() as $error)
        toastr['error']("{{ $error }}")
        @endforeach
        @endif
        @if(session('status', '') == 'success')
        toastr['success']("{{ session('message', '') }}")
        @elseif(session('status', '') == 'error')
        toastr['error']("{{ session('message', '') }}")
        @endif

        if(
            $('#feature_sidebar').hasClass('active') || 
            $('#screenshot_sidebar').hasClass('active') ||
            $('#career_sidebar').hasClass('active')
        ) {
            $('#website_management_sidebar').addClass('active');
        }
        if($('#website_management_sidebar .treeview-menu').children().length == 0) {
            $('#website_management_sidebar').hide()
        }
        


        if(
            $('#role_sidebar').hasClass('active') || 
            $('#user_sidebar').hasClass('active') || 
            $('#salon_sidebar').hasClass('active') || 
            $('#branch_sidebar').hasClass('active') || 
            $('#captain_sidebar').hasClass('active') ||
            $('#city_sidebar').hasClass('active') ||
            $('#governorate_sidebar').hasClass('active') 
        ) {
            $('#user_management_sidebar').addClass('active');
        }
        if($('#user_management_sidebar .treeview-menu').children().length == 0) {
            $('#user_management_sidebar').hide()
        }
        

        if(
            $('#service_sidebar').hasClass('active') || 
            $('#category_sidebar').hasClass('active') || 
            $('#product_sidebar').hasClass('active') || 
            $('#offer_sidebar').hasClass('active') || 
            $('#package_sidebar').hasClass('active') || 
            $('#vehicle_sidebar').hasClass('active') || 
            $('#price_sidebar').hasClass('active') ||
            $('#word_sidebar').hasClass('active') || 
            $('#coupon_sidebar').hasClass('active')  ||
            $('#course_sidebar').hasClass('active')  ||
            $('#lesson_sidebar').hasClass('active')  ||
            $('#complaint_sidebar').hasClass('active') 
            ) {
            $('#app_management_sidebar').addClass('active');
        }
        if($('#app_management_sidebar .treeview-menu').children().length == 0) {
            $('#app_management_sidebar').hide()
        }

        if(
            $('#salon_order_sidebar').hasClass('active') ||
            $('#captain_order_sidebar').hasClass('active')  ||
            $('#children_order_sidebar').hasClass('active')  ||
            $('#product_order_sidebar').hasClass('active') 
            ) {
            $('#orders_sidebar').addClass('active');
        }
        if($('#orders_sidebar .treeview-menu').children().length == 0) {
            $('#orders_sidebar').hide()
        }

        
        if($('#phone_sidebar').hasClass('active') || $('#address_sidebar').hasClass('active') || $('#social_sidebar').hasClass('active') || $('#email_sidebar').hasClass('active')) {
            $('#contact_management_sidebar').addClass('active');
        }
        if($('#contact_management_sidebar .treeview-menu').children().length == 0) {
            $('#contact_management_sidebar').hide()
        }
    })
</script>