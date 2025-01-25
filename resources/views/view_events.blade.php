@extends('layouts.layout')

@section('content')

<section class="filler-div">

</section>

<div class="student-events-ctn">
    <div class="student-events-header">
        <a href="{{ route('view_events')}}"><h1>Events</h1></a>
    </div>

    <div class="student-each-events">

        @php
            $todayDate = \Carbon\Carbon::now()->toDateString();
            $eventDate = \Carbon\Carbon::parse($event->date)->toDateString()
        @endphp

        @if($events->isNotEmpty())
            @foreach($events as $event)
                <div class="student-event-item {{ \Carbon\Carbon::parse($event->date)->toDateString() === $todayDate ? 'today' : 'upcoming' }}">
                    
                    <div class="event-item-profile">
                        <div class="news-logo">
                            <img src="{{ asset('storage/' . $event->logo) }}" alt="{{ $event->org }} Logo" class="logo">
                        </div>
                        
                        <div class="event-item-info">
                            <h3>{{ $event->name }}</h3>
                            <p>{{ \Carbon\Carbon::parse($event->date)->format('F j, Y, g:i A') }}</p> 
                            <p>{{ $event->venue }}</p>
                        </div>
                    </div>

                    @if($eventDate === $todayDate)
                        <div class="event-item-status today">
                            <span>Today!</span>
                        </div>
                    @elseif($eventDate > $todayDate)
                        <div class="event-item-status upcoming">
                            <span>Upcoming!</span>
                        </div>
                    @else
                        <div class="event-item-status passed">
                            <span>Finished</span>
                        </div>
                    @endif


                </div>
            @endforeach
        @else
            <p>No events available.</p>
        @endif
    </div>

</div>

<script>

</script>

@endsection
