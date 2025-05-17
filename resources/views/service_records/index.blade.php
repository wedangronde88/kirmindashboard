@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Service Records for {{ $truck->plat_no }}</h1>
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
            @foreach ($serviceRecords as $record)
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
            @endforeach
        </tbody>
    </table>
</div>
@endsection