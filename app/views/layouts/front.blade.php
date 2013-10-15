@extends('layouts.master')

@section('menu')
    @if (isset($menu))
    <ul class="nav navbar-nav">
        @foreach ($menu as $item)
        <li>
            <a href="{{ route('page', array('slug' => $item['slug'])) }}">{{ $item['title'] }}</a>
        </li>
        @endforeach
    </ul>
    @endif

    <ul class="nav navbar-nav pull-right">
        <li>
            <a href="{{ route('pages.index') }}"><span class="glyphicon glyphicon-wrench"></span> Manage</a>
        </li>
    </ul>
@stop

@section('content')
    {{ $content }}
@stop