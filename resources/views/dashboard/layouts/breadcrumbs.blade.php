@if (count($breadcrumbs))
<ol class="breadcrumb">
    @foreach ($breadcrumbs as $breadcrumb)
    @if ($breadcrumb->url && $loop->first)
    <li>
        <a href="{{ $breadcrumb->url }}"> <i class="fa fa-dashboard"></i> {{ $breadcrumb->title }}</a>
    </li>
    @elseif ($breadcrumb->url && !$loop->last)
    <li>
        <a href="{{ $breadcrumb->url }}">{{ $breadcrumb->title }}</a>
    </li>
    @else
    <li class="active">
        {{ $breadcrumb->title }}
    </li>
    @endif
    @endforeach
</ol>
@endif