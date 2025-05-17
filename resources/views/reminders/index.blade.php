@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Reminders for {{ $truck->plat_no }}</h1>
    <a href="{{ route('trucks.reminders.create', $truck->id) }}" class="btn btn-primary mb-3">Add Reminder</a>

    <!-- Calendar Container -->
    <div id="calendar"></div>
</div>

<!-- FullCalendar Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth', // Month view
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: '/trucks/{{ $truck->id }}/reminders', // Fetch events dynamically
            eventClick: function(info) {
                alert(info.event.title + '\n' + info.event.extendedProps.description);
            }
        });

        calendar.render();
    });
</script>
@endsection