@extends('layouts.layout')

@section('content')

<section class="filler-div">
</section>

<div id="calendar"></div>

<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.6/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: [
                    @foreach($events as $event)
                    {
                        title: "{{ $event->name }} - {{ $event->org }}",
                        start: "{{ $event->date }}",
                    },
                    @endforeach
                ]
            });
            calendar.render();
        });
    </script>

@endsection