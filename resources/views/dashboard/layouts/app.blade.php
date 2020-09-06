<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="{{ App::getLocale() }}" dir="{{ LaravelLocalization::getCurrentLocaleDirection() }}">
    <!--<![endif]-->
    @include('dashboard.layouts.head')

    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <header class="main-header">
                <!-- Logo -->
                <a href="{{ route('dashboard') }}" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini">{{ $settings->title }}</span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg">{{ $settings->title }}</span>
                </a>
                <!-- Header Navbar: style can be found in header.less -->
                <nav class="navbar navbar-static-top">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    </a>
            
                    <div class="navbar-custom-menu">
                        
                        <ul class="nav navbar-nav">
                            <!-- Notifications: style can be found in dropdown.less -->
                            <li class="dropdown notifications-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-bell-o"></i>
                                <span class="label label-warning">{{ count(Auth::user()->unreadNotifications) > 0 ? count(Auth::user()->unreadNotifications) : '' }}</span>
                                </a>
                                <ul class="dropdown-menu">
                                    @if(count(Auth::user()->unreadNotifications) > 0)
                                    <li class="header">{{ __('You have') }} {{ count(Auth::user()->unreadNotifications) }} {{ __('notifications') }}</li>
                                    @endif
                                    <li>
                                        <!-- inner menu: contains the actual data -->
                                        <ul class="menu">
                                            @if(Auth::user()->notifications()->count() > 0)
                                            @foreach(Auth::user()->notifications as $notification)
                                            <li @if($notification->read_at) style="background-color: antiquewhite;" @endif>
                                                <a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), $notification->data['url']) }}">
                                                    <i class="fa fa-shopping-cart text-green"></i> {{  $notification->data['name'] }} {{  $notification->data['action'] }} 
                                                </a>
                                            </li>
                                            @endforeach
                                            @endif
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <!-- Languages -->
                            <li class="dropdown" >
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    @foreach(LaravelLocalization::getSupportedLocales(true) as $localeCode => $properties)
                                        @if($localeCode == App::getLocale())
                                            {{ $properties['native'] }} 
                                        @endif
                                    @endforeach
                                </a>
                                <ul class="dropdown-menu">
                                    @foreach(LaravelLocalization::getSupportedLocales(true) as $localeCode => $properties)
                                        @if($localeCode != App::getLocale())
                                        <li>
                                            <a rel="alternate" hreflang="{{ $localeCode }}" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                                {{ $properties['native'] }} 
                                            </a>
                                        </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </li>
                            <!-- User Account: style can be found in dropdown.less -->
                            <li class="dropdown user user-menu" >
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <img src="{{ route('file_show', Auth::user()->image)}}" class="user-image" alt="User Image">
                                    <span class="hidden-xs">{{ Auth::user()->name }}</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <!-- User image -->
                                    <li class="user-header">
                                        <img src="{{ route('file_show', Auth::user()->image)}}" class="img-circle" alt="User Image">
                        
                                        <p>
                                            {{ Auth::user()->name }}
                                            <small>{{ __("Member since") }} {{ date('Y-m-d H:i', strtotime(Auth::user()->created_at)) }}</small>
                                        </p>
                                    </li>
                                    <!-- Menu Footer-->
                                    <li class="user-footer">
                                        <div class="pull-right">
                                            <a href="{{ route('logout') }}"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-default btn-flat">{{ __('Logout') }}</a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                {{ csrf_field() }}
                                            </form>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
             <!-- Left side column. contains the logo and sidebar -->
            <aside class="main-sidebar">
                <!-- sidebar:  -->
                <section class="sidebar">
                <!-- Sidebar user panel -->
                <div class="user-panel">
                    <div class="pull-left image">
                    <img src="{{ route('file_show', Auth::user()->image)}}" class="img-circle" alt="User Image">
                    </div>
                    <div class="pull-left info">
                    <p>{{ Auth::user()->name }}</p>
                    <a href="#"><i class="fa fa-circle text-success"></i> {{ __('Online') }}</a>
                    </div>
                </div>
                <!-- sidebar menu: -->
                    <ul class="sidebar-menu">
                        <li @if(url()->current() == route('dashboard')) class="active" @endif><a href="{{ route('dashboard') }}"><i class="fa fa-dashboard"></i> <span>{{ __('Dashboard')}}</span></a></li>
                        @can("setting.view")                        
                        <li @if(url()->current() == route('settings.index')) class="active" @endif><a href="{{ route('settings.index') }}"><i class="fa fa-cogs"></i> <span>{{ __('Settings')}}</span></a></li>
                        @endcan 
                        @can("translator")                        
                        <li @if(url()->current() == route('translators.index')) class="active" @endif><a href="{{ route('translators.index') }}"><i class="fa fa-globe"></i> <span>{{ __('Translator')}}</span></a></li>
                        @endcan 
                        <li class="treeview" id="user_management_sidebar">
                            <a href="#">
                                <i class="fa fa fa-users"></i> <span>{{ __('User management') }}</span>
                                <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu" >
                                @component('sidebar_item', ['icon' => 'fa fa-users', 'label' => 'role'])
                                    role
                                @endcomponent
                                @component('sidebar_item', ['icon' => 'fa fa-user', 'label' => 'user'])
                                    user
                                @endcomponent
                                @component('sidebar_item', ['icon' => 'fa fa-shopping-bag', 'label' => 'salon'])
                                    salon
                                @endcomponent
                                @component('sidebar_item', ['icon' => 'fa fa-map-marker', 'label' => 'branch', 'plural' => 'branches', 'plural_name' => 'branches'])
                                    branch
                                @endcomponent
                                @component('sidebar_item', ['icon' => 'fa fa-motorcycle', 'label' => 'captain'])
                                    captain
                                @endcomponent
                                @component('sidebar_item', ['icon' => 'fa fa-th', 'label' => 'governorate'])
                                    governorate
                                @endcomponent
                                @component('sidebar_item', ['icon' => 'fa fa-building', 'label' => 'city', 'plural_name' => 'cities', 'plural' => 'cities'])
                                    city
                                @endcomponent
                            </ul>
                        </li>
                        <li class="treeview" id="website_management_sidebar">
                            <a href="#">
                                <i class="fa fa fa-users"></i> <span>{{ __('Website management') }}</span>
                                <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu" >
                                @component('sidebar_item', ['icon' => 'fa fa-microchip', 'label' => 'feature'])
                                    feature
                                @endcomponent
                                @component('sidebar_item', ['icon' => 'fa fa-image', 'label' => 'screenshot'])
                                    screenshot
                                @endcomponent
                                @can("career.view")                        
                                <li id="career_sidebar" @if(url()->current() == route('careers.index')) class="active" @endif><a href="{{ route('careers.index') }}"><i class="fa fa-shopping-cart"></i> <span>{{ __('Careers')}}</span></a></li>
                                @endcan 
                            </ul>
                        </li>
                        <li class="treeview" id="app_management_sidebar">
                            <a href="#">
                                <i class="fa fa fa-cog"></i> <span>{{ __('App Management') }}</span>
                                <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu" >
                                @component('sidebar_item', ['icon' => 'fa fa-server', 'label' => 'service'])
                                    service
                                @endcomponent
                                @component('sidebar_item', ['icon' => 'fa fa-bullhorn', 'label' => 'offer'])
                                    offer
                                @endcomponent
                                @component('sidebar_item', ['icon' => 'fa fa-cubes', 'label' => 'category', 'plural' => 'categories', 'plural_name' => 'categories'])
                                    category
                                @endcomponent
                                @component('sidebar_item', ['icon' => 'fa fa-coffee', 'label' => 'product'])
                                    product
                                @endcomponent
                                @component('sidebar_item', ['icon' => 'fa fa-folder', 'label' => 'package'])
                                    package
                                @endcomponent
                                @component('sidebar_item', ['icon' => 'fa fa-usd', 'label' => 'price'])
                                    price
                                @endcomponent
                                @component('sidebar_item', ['icon' => 'fa fa-address-book', 'label' => 'word'])
                                    word
                                @endcomponent
                                @component('sidebar_item', ['icon' => 'fa fa-bullhorn', 'label' => 'coupon'])
                                    coupon
                                @endcomponent
                            </ul>
                        </li>
                        <li class="treeview" id="orders_sidebar">
                            <a href="#">
                                <i class="fa fa fa-shopping-cart"></i> <span>{{ __('Orders') }}</span>
                                <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu" >
                                @can("oq_order.view")                        
                                <li id="oq_order_sidebar" @if(url()->current() == route('oq_orders.index')) class="active" @endif><a href="{{ route('oq_orders.index') }}"><i class="fa fa-shopping-cart"></i> <span>{{ __('OQ')}}</span></a></li>
                                @endcan 
                                @can("anywhere_order.view")                        
                                <li id="anywhere_order_sidebar" @if(url()->current() == route('anywhere_orders.index')) class="active" @endif><a href="{{ route('anywhere_orders.index') }}"><i class="fa fa-shopping-cart"></i> <span>{{ __('Any where')}}</span></a></li>
                                @endcan 
                                @can("week_order.view")                        
                                <li id="week_order_sidebar" @if(url()->current() == route('week_orders.index')) class="active" @endif><a href="{{ route('week_orders.index') }}"><i class="fa fa-shopping-cart"></i> <span>{{ __('Week')}}</span></a></li>
                                @endcan
                                @can("oneway_order.view")                        
                                <li id="oneway_order_sidebar" @if(url()->current() == route('oneway_orders.index')) class="active" @endif><a href="{{ route('oneway_orders.index') }}"><i class="fa fa-shopping-cart"></i> <span>{{ __('One way')}}</span></a></li>
                                @endcan  
                                @can("moreway_order.view")                        
                                <li id="moreway_order_sidebar" @if(url()->current() == route('moreway_orders.index')) class="active" @endif><a href="{{ route('moreway_orders.index') }}"><i class="fa fa-shopping-cart"></i> <span>{{ __('More way')}}</span></a></li>
                                @endcan 
                            </ul>
                        </li>
                        <li class="treeview" id="contact_management_sidebar">
                            <a href="#">
                                <i class="fa fa fa-phone"></i> <span>{{ __('Contact Management') }}</span>
                                <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu" >
                                @component('sidebar_item', ['icon' => 'fa fa-phone', 'label' => 'phone'])
                                    phone
                                @endcomponent
                                @component('sidebar_item', ['icon' => 'fa fa-map-marker', 'label' => 'address', 'plural' => 'addresses', 'plural_name' => 'addresses'])
                                    address
                                @endcomponent
                                @component('sidebar_item', ['icon' => 'fa fa-facebook', 'label' => 'social'])
                                    social
                                @endcomponent
                                @component('sidebar_item', ['icon' => 'fa fa-envelope', 'label' => 'email'])
                                    email
                                @endcomponent
                            </ul>
                        </li>
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>  
            <div class="content-wrapper">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>@yield('title')</h1>
                    @yield('breadcrumbs')
                </section>
                <!-- Main content -->
                <section class="content">
                    @yield('content')
                </section>
                <!-- /.content -->
            </div>
            <footer class="main-footer">
                <strong>{!! $settings->copyrights !!}</strong>
            </footer>  
        </div>
        @include('dashboard.layouts.footer')
    </body>
</html>