@extends('adminlte::page')

@section('title', 'Event Dashboard')

@section('content_header')
    <h1>Event Dashboard</h1>
    @auth
        <p>
            {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
            <a href="{{ route('logout') }}">Logout</a>
        </p>
    @endauth

    @guest
        <a href="{{ route('login') }}">Login</a>
        <a href="{{ route('register') }}">Register</a>
    @endguest
@stop

@section('content')
    <div class="row">
        <div class="col-4">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Events</h4>
                    <p class="card-text">
                        @if (count($events) > 0)
                            <ul id="events">
                                @foreach ($events as $event)
                                    <li><a href="{{ route('event', $event->id) }}">{{ $event->title }}</a></li>
                                @endforeach
                            </ul>
                        @else
                            <p>No events found.</p>
                        @endif
                    </p>
                </div>
            </div>
            @auth
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">My Events</h4>
                        <p class="card-text">
                            @if (count($myEvents) > 0)
                                <ul id="myEvents">
                                    @foreach ($myEvents as $event)
                                        <li><a href="{{ route('event', $event->id) }}">{{ $event->title }}</a></li>
                                    @endforeach
                                </ul>
                            @else
                                <p>No events found.</p>
                            @endif
                        </p>
                    </div>
                </div>
            @endauth
        </div>
        <div class="col-8">
            <div class="card">
                <div class="card-body">
                    @if (isset($currentEvent))
                        <p>Event: {{ $currentEvent->title }}</p>
                        <p>Description: {{ $currentEvent->description }}</p>
                        <p>Creator: <a href="{{ route('user', $currentEvent->creator->id) }}">{{ $currentEvent->creator->first_name }}
                                {{ $currentEvent->creator->last_name }}</a></p>
                        @auth
                            @if ($participoned)
                                <p>You have already participated in this event.</p>
                                <a href="{{ route('event.participate', $currentEvent->id) }}" class="btn btn-primary">Un-participate</a>
                            @else
                                <p>You have not participated in this event.</p>
                                <a href="{{ route('event.participate', $currentEvent->id) }}" class="btn btn-primary">Participate</a>
                            @endif
                        @endauth
                        <p>Participants:</p>
                        <ul id="participants">
                            @foreach ($currentEvent->participants as $participant)
                                <li><a href="{{ route('user', $participant->user_id) }}">{{ $participant->user->first_name }}
                                        {{ $participant->user->last_name }}</a></li>
                            @endforeach
                        </ul>
                    @else
                        <p>Pick an event to see its details.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        setInterval(function() {
            $.ajax({
                url: '{{ route ('api.events') }}',
                method: 'GET',
                success: function(response) {
                    if (response.error) {
                        console.error(response.error);
                        return;
                    }
                    $('#events').html('');
                    $.each(response.result, function(index, value) {
                        $('#events').append('<li><a href="/event/' + value.id + '">' + value.title + '</a></li>');
                    });
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
            @if(isset($currentEvent))
                $.ajax({
                    url: '{{ route ('api.events.participants') }}',
                    method: 'GET',
                    data: { event_id: '{{ $currentEvent->id }}' },
                    success: function(response) {
                        if (response.error) {
                            console.error(response.error);
                            return;
                        }
                        $('#participants').html('');
                        $.each(response.result, function(index, value) {
                            $('#participants').append('<li><a href="/user/' + value.user_id + '">' + value.user.first_name + ' ' + value.user.last_name + '</a></li>');
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            @endif
        }, 30_000);
    </script>
@stop
