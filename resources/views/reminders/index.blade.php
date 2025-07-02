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
            <input type="date" name="deadline" class="form-control" id="deadline" required>
        </div>
        <div class="form-group mt-2">
            <label>Countdown:</label>
            <div id="countdown" class="fw-bold text-primary"></div>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Add Reminder</button>
    </form>
</div>

<script>
function updateCountdown() {
    const deadlineInput = document.getElementById('deadline');
    const countdownDiv = document.getElementById('countdown');
    const deadlineVal = deadlineInput.value;
    if (!deadlineVal) {
        countdownDiv.textContent = '';
        return;
    }
    const deadline = new Date(deadlineVal + 'T23:59:59');
    const now = new Date();
    let diff = deadline - now;
    if (diff <= 0) {
        countdownDiv.innerHTML = '<span class="text-danger">Expired</span>';
        return;
    }

    let seconds = Math.floor(diff / 1000);
    let years = Math.floor(seconds / (365*24*60*60));
    seconds -= years * 365*24*60*60;
    let months = Math.floor(seconds / (30*24*60*60));
    seconds -= months * 30*24*60*60;
    let days = Math.floor(seconds / (24*60*60));
    seconds -= days * 24*60*60;
    let hours = Math.floor(seconds / (60*60));
    seconds -= hours * 60*60;
    let minutes = Math.floor(seconds / 60);
    seconds = seconds % 60;

    countdownDiv.textContent = 
        years + ' Years ' +
        months + ' Month ' +
        days + ' Day ' +
        hours + ' Hours ' +
        minutes + ' Minute ' +
        seconds + ' Second';
}

document.getElementById('deadline').addEventListener('input', function() {
    updateCountdown();
});
setInterval(updateCountdown, 1000);
</script>
@endsection