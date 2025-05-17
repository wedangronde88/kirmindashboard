@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add Reminder for {{ $truck->plat_no }}</h1>
    <form action="{{ route('trucks.reminders.store', $truck->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="document_type">Document Type</label>
            <select name="document_type" class="form-control" required>
                <option value="SIM">SIM</option>
                <option value="STNK">STNK</option>
                <option value="KIR">KIR</option>
            </select>
        </div>
        <div class="form-group">
            <label for="remind_from">Start Date</label>
            <input type="date" name="remind_from" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="remind_every">Repeat Every (years)</label>
            <input type="number" name="remind_every" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Reminder</button>
    </form>
</div>
@endsection