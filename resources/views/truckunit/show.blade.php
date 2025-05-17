@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Truck Profile: {{ $truck->plat_no }}</h1>

    <!-- Truck Details -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">{{ $truck->brand_truk }}</h5>
            <p><strong>Plate No:</strong> {{ $truck->plat_no }}</p>
            <p><strong>No STNK:</strong> {{ $truck->no_stnk }}</p>
            <p><strong>No KIR:</strong> {{ $truck->no_kir }}</p>
            <p><strong>No Pajak:</strong> {{ $truck->no_pajak }}</p>
            @if ($truck->image)
                <img src="{{ asset('storage/' . $truck->image) }}" alt="Truck Image" style="width: 300px; height: auto;">
            @else
                <p>No Image Available</p>
            @endif
        </div>
    </div>

    <!-- Tabbed Layout -->
    <ul class="nav nav-tabs" id="truckTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="service-records-tab" data-bs-toggle="tab" data-bs-target="#service-records" type="button" role="tab" aria-controls="service-records" aria-selected="true">Service Records</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="safety-checks-tab" data-bs-toggle="tab" data-bs-target="#safety-checks" type="button" role="tab" aria-controls="safety-checks" aria-selected="false">Safety Checks</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="reminders-tab" data-bs-toggle="tab" data-bs-target="#reminders" type="button" role="tab" aria-controls="reminders" aria-selected="false">Reminders</button>
        </li>
    </ul>
    <div class="tab-content" id="truckTabsContent">
        <!-- Service Records Tab -->
        <div class="tab-pane fade show active" id="service-records" role="tabpanel" aria-labelledby="service-records-tab">
            <h2 class="mt-4">Service Records</h2>
            <a href="{{ route('trucks.service-records.create', $truck->id) }}" class="btn btn-primary mb-3">Add Service Record</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Service Date</th>
                        <th>Service Type</th>
                        <th>Costs</th>
                        <th>Remarks</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($truck->serviceRecords as $record)
                        <tr>
                            <td>{{ $record->service_date }}</td>
                            <td>{{ $record->service_type }}</td>
                            <td>{{ $record->costs }}</td>
                            <td>{{ $record->remarks }}</td>
                            <td>
                                <a href="{{ route('trucks.service-records.edit', [$truck->id, $record->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('trucks.service-records.destroy', [$truck->id, $record->id]) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No service records found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Safety Checks Tab -->
        <div class="tab-pane fade" id="safety-checks" role="tabpanel" aria-labelledby="safety-checks-tab">
            <h2 class="mt-4">Safety Check Records</h2>
            <a href="{{ route('trucks.safety-checks.create', $truck->id) }}" class="btn btn-primary mb-3">Add Safety Check</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Pick-Up Point</th>
                        <th>Destination</th>
                        <th>PDF File</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($truck->safetyChecks as $check)
                        <tr>
                            <td>{{ $check->pick_up_point }}</td>
                            <td>{{ $check->destination }}</td>
                            <td><a href="{{ asset('storage/' . $check->pdf_file) }}" target="_blank">View PDF</a></td>
                            <td>
                                <a href="{{ route('trucks.safety-checks.edit', [$truck->id, $check->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('trucks.safety-checks.destroy', [$truck->id, $check->id]) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No safety checks found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Reminders Tab -->
        <div class="tab-pane fade" id="reminders" role="tabpanel" aria-labelledby="reminders-tab">
    <h2 class="mt-4">Expiration Reminders</h2>
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
            events: '/trucks/{{ $truck->id }}/reminders', // Fetch events dynamically from Laravel
            selectable: true, // Allow date selection
            select: function(info) {
                // Prompt user for event details
                var title = prompt('Enter Event Title:');
                if (title) {
                    // Send event data to the server
                    fetch('/trucks/{{ $truck->id }}/reminders', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            title: title,
                            start: info.startStr,
                            end: info.endStr
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Event added successfully!');
                            calendar.refetchEvents(); // Refresh calendar events
                        } else {
                            alert('Failed to add event.');
                        }
                    });
                }
                calendar.unselect(); // Clear selection
            },
            eventClick: function(info) {
                alert('Event: ' + info.event.title + '\nDescription: ' + info.event.extendedProps.description);
            }
        });

        calendar.render();
    });
</script>
        </tbody>
    </table>
</div>
    </div>
</div>
@endsection