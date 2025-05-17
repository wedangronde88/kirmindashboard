@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Reminder for {{ $truck->plat_no }}</h1>
    <form action="{{ route('trucks.reminders.update', [$truck->id, $reminder->id]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="remind_from">Remind From</label>
            <input type="date" name="remind_from" class="form-control" value="{{ $reminder->remind_from }}" required>
        </div>
        <div class="form-group">
            <label for="remind_every">Remind Every (years)</label>
            <input type="number" name="remind_every" class="form-control" value="{{ $reminder->remind_every }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Reminder</button>
    </form>
</div>
@endsection