@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add Reminder for {{ $truck->plat_no }}</h1>
    <form action="{{ route('trucks.reminders.store', $truck->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="document_type">Document Type</label>
            <select name="document_type" class="form-control" required>
                @foreach(['STID', 'KIR', 'STNK', 'PKB', 'Plat Nomor'] as $type)
                    <option value="{{ $type }}">{{ $type }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="deadline">Deadline</label>
            <input type="date" name="deadline" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Reminder</button>
    </form>
</div>
@endsection