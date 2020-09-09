@extends('dashboard.layouts.app')
@section('title', __('Messages').' '.ucfirst(__('Captain')))
@section('content')
<div style="overflow: hidden">
@foreach($row->messages()->oldest()->get() as $message)
<div class="panel panel-{{ $message->user_id == $row->user_id ? 'default' : 'primary' }}" style="width: 60%; float: {{ $message->user_id == $row->user_id ? 'left' : 'right' }}">
    <div class="panel-heading">{{ $message->user()->first()->name ?? '' }} {{ $message->created_at }}</div>
    <div class="panel-body">
        @if($message->type == 'text')
        {{ $message->message }}
        @elseif($message->type == 'image')
        <img src="{{ route('file_show', $message->message) }}" style='height: 100%' />
        @endif
    </div>
</div>
@endforeach
</div>
@endsection
@section('css')
@endsection
@section('js')
@endsection