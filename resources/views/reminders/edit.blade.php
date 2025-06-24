@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Reminder for {{ $truck->plat_no }}</h1>
    <form action="{{ route('trucks.reminders.update', [$truck->id, $reminder->id]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="deadline">Deadline</label>
            <input type="date" name="deadline" class="form-control" value="{{ $reminder->deadline }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Reminder</button>
    </form>
</div>
@endsection