@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Truck Profile: {{ $truck->plat_no }}</h1>

    <!-- Truck Details -->
    <div class="card mb-4 position-relative">
        <div class="card-body" style="position:relative;">
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

            {{-- Reminders at the bottom right of the truck details card --}}
            @php
                $now = \Carbon\Carbon::now();
                $reminders = $truck->reminders->keyBy('document_type');
                $reminderDocs = ['STID', 'KIR', 'STNK', 'PKB', 'Plat Nomor'];
            @endphp
            <div class="position-absolute" style="right: 20px; bottom: 20px; background:rgba(255,255,255,0.95); border-radius:8px; padding:10px 16px; min-width:180px; box-shadow:0 2px 8px rgba(0,0,0,0.08); z-index:10;">
                <div class="fw-bold mb-1" style="font-size:13px;">Reminders</div>
                @foreach($reminderDocs as $doc)
                    @if(isset($reminders[$doc]))
                        @php
                            $deadline = \Carbon\Carbon::parse($reminders[$doc]->deadline)->setTime(23,59,59);
                            $deadlineJs = $deadline->format('Y-m-d\TH:i:s');
                            $diff = $now->diffInDays($deadline, false);
                            $isExpired = $diff < 0;
                        @endphp
                        <div style="font-size:12px;" class="mb-1">
                            <span class="fw-bold">{{ $doc }}:</span>
                            @if($isExpired)
                                <span class="text-danger">Expired</span>
                            @else
                                <span id="timer-detail-{{ $reminders[$doc]->id }}" class="fw-bold"></span>
                                <script>
                                    (function() {
                                        function updateTimerDetail{{ $reminders[$doc]->id }}() {
                                            var deadline = new Date("{{ $deadlineJs }}").getTime();
                                            var now = new Date().getTime();
                                            var distance = deadline - now;
                                            if (distance < 0) {
                                                document.getElementById("timer-detail-{{ $reminders[$doc]->id }}").innerHTML = '<span class="text-danger">Expired</span>';
                                                clearInterval(window['intervalDetail{{ $reminders[$doc]->id }}']);
                                                return;
                                            }
                                            var totalSeconds = Math.floor(distance / 1000);
                                            var years = Math.floor(totalSeconds / (365*24*60*60));
                                            totalSeconds -= years * 365*24*60*60;
                                            var months = Math.floor(totalSeconds / (30*24*60*60));
                                            totalSeconds -= months * 30*24*60*60;
                                            var days = Math.floor(totalSeconds / (24*60*60));
                                            totalSeconds -= days * 24*60*60;
                                            var hours = Math.floor(totalSeconds / (60*60));
                                            totalSeconds -= hours * 60*60;
                                            var minutes = Math.floor(totalSeconds / 60);
                                            var seconds = totalSeconds % 60;
                                            var color = (years === 0 && months === 0 && days < 30) ? 'text-danger' : 'text-success';
                                            document.getElementById("timer-detail-{{ $reminders[$doc]->id }}").innerHTML =
                                                '<span class="' + color + '">' +
                                                years + 'Y ' +
                                                months + 'M ' +
                                                days + 'D ' +
                                                hours + 'H ' +
                                                minutes + 'm ' +
                                                seconds + 's' +
                                                '</span>';
                                        }
                                        window['intervalDetail{{ $reminders[$doc]->id }}'] = setInterval(updateTimerDetail{{ $reminders[$doc]->id }}, 1000);
                                        updateTimerDetail{{ $reminders[$doc]->id }}();
                                    })();
                                </script>
                            @endif
                        </div>
                    @endif
                @endforeach
            </div>
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
                        <th>Trip Date</th>
                        <th>Pick-Up Point</th>
                        <th>Destination</th>
                        <th>PDF File</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($truck->safetyChecks as $check)
                        <tr>
                            <td>{{ $check->trip_date }}</td>
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
                            <td colspan="5" class="text-center">No safety checks found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Reminders Tab --}}
<div class="tab-pane fade" id="reminders" role="tabpanel" aria-labelledby="reminders-tab">
    <h2 class="mt-4">Expiration Reminders</h2>
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('trucks.reminders.create', $truck->id) }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Reminder
        </a>
    </div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Document Type</th>
                <th>Deadline</th>
                <th>Countdown</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($truck->reminders as $reminder)
                @php
                    $deadline = \Carbon\Carbon::parse($reminder->deadline)->setTime(23,59,59);
                    $deadlineJs = $deadline->format('Y-m-d\TH:i:s');
                    $now = \Carbon\Carbon::now();
                    $diff = $now->diffInDays($deadline, false);
                    $isExpired = $diff < 0;
                @endphp
                <tr>
                    <td>{{ $reminder->document_type }}</td>
                    <td>{{ $deadline->format('d-m-Y') }}</td>
                    <td>
                        @if($isExpired)
                            <span class="text-danger">Masa dokumen telah kedaluwarsa, silahkan perbaharui</span>
                        @else
                            <span id="timer-{{ $reminder->id }}" class="fw-bold"></span>
                            <script>
                                (function() {
                                    function updateTimer{{ $reminder->id }}() {
                                        var deadline = new Date("{{ $deadlineJs }}").getTime();
                                        var now = new Date().getTime();
                                        var distance = deadline - now;

                                        if (distance < 0) {
                                            document.getElementById("timer-{{ $reminder->id }}").innerHTML = '<span class="text-danger">Masa dokumen telah kedaluwarsa, silahkan perbaharui</span>';
                                            clearInterval(window['interval{{ $reminder->id }}']);
                                            return;
                                        }

                                        var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                        var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                        var seconds = Math.floor((distance % (1000 * 60)) / 1000);

                                        var color = (days < 30) ? 'text-danger' : 'text-success';
                                        document.getElementById("timer-{{ $reminder->id }}").innerHTML =
                                            '<span class="' + color + '">' +
                                            days + ' hari ' + hours + ' jam ' + minutes + ' menit ' + seconds + ' detik' +
                                            '</span>';
                                    }
                                    window['interval{{ $reminder->id }}'] = setInterval(updateTimer{{ $reminder->id }}, 1000);
                                    updateTimer{{ $reminder->id }}();
                                })();
                            </script>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('trucks.reminders.edit', [$truck->id, $reminder->id]) }}" class="btn btn-warning btn-sm mb-1">
                            <i class="bi bi-pencil"></i> Edit
                        </a>
                        <form action="{{ route('trucks.reminders.destroy', [$truck->id, $reminder->id]) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this reminder?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm mb-1">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                        <button class="btn btn-success btn-sm" onclick="renewReminder({{ $reminder->id }})">Renewed</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <script>
    function renewReminder(reminderId) {
        if (confirm('Apakah Anda yakin ingin memperbaharui dokumen ini?')) {
            fetch('/reminders/' + reminderId + '/renew', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success){
                    alert('Selamat dokumen telah diperbaharui');
                    location.reload();
                }
            });
        }
    }
    </script>
</div>
    </div>
</div>
@endsection