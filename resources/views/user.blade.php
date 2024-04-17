@extends('adminlte::page')

@section('title', 'User profile')

@section('content_header')
    <h1>{{ $user->first_name }} {{ $user->last_name }}</h1>
@stop

@section('content')
    <p>Login: {{ $user->login }}</p>
    <p>Email: {{ $user->email }}</p>
    <p>Birthday: {{ $user->birthday ?? 'N/A' }}</p>
    <p>First name: {{ $user->first_name }}</p>
    <p>Last name: {{ $user->last_name }}</p>
    <p>Created at: {{ $user->created_at }}</p>
    User events:
    <ul>
        @foreach($user->events as $event)
            <li><a href="{{ route('event', $event->id) }}">{{ $event->title }}</a></li>
        @endforeach
    </ul>
@stop