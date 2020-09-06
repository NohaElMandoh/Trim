@can(["$slot.list", "$slot.create"])
<li id="{{ $slot }}_sidebar" class="@if(isset($plural)) @if(strpos(LaravelLocalization::getLocalizedURL(), route($plural.'.index')) !== false) active @endif @else @if(strpos(LaravelLocalization::getLocalizedURL(), route($slot.'s.index')) !== false) active @endif @endif">
    <a href="#">
        <i class="{{ $icon }}"></i>
        <span>@if(isset($plural_name)) {{ ucfirst(__("$plural_name")) }} @else {{ ucfirst(__(''.$label.'s')) }}@endif</span>
    </a>
    <ul class="treeview-menu">
        @can("$slot.list")
        <li @if(isset($plural)) @if(LaravelLocalization::getLocalizedURL() ==  route($plural.'.index')) class="active" @endif @elseif(LaravelLocalization::getLocalizedURL() ==  route($slot.'s.index')) class="active" @endif><a href="@if(isset($plural)) {{ route($plural.'.index') }} @else {{ route($slot.'s.index') }} @endif"><i class="fa fa-circle-o"></i> {{ ucfirst(__('all')) }} @if(isset($plural_name)) {{ __("$plural_name") }} @else {{ __(''.$label.'s') }}@endif</a></li>
        @endcan
        @can("$slot.create")
        <li @if(isset($plural)) @if(LaravelLocalization::getLocalizedURL() ==  route($plural.'.create')) class="active" @endif @elseif(LaravelLocalization::getLocalizedURL() ==  route($slot.'s.create')) class="active" @endif><a href="@if(isset($plural)) {{ route($plural.'.create') }} @else {{ route($slot.'s.create') }} @endif"><i class="fa fa-circle-o"></i> {{ ucfirst(__('create')) }} {{ __(''.$label) }}</a></li>
        @endcan  
    </ul>
</li>
@endcan        