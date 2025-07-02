@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Safety Checks for {{ $truck->plat_no }}</h1>
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
            @foreach ($safetyChecks as $check)
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
            @endforeach
        </tbody>
    </table>
</div>
@endsection