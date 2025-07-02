@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Safety Check for {{ $truck->plat_no }}</h1>
    <form action="{{ route('trucks.safety-checks.update', [$truck->id, $safetyCheck->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="trip_date">Trip Date</label>
            <input type="date" name="trip_date" class="form-control" value="{{ old('trip_date', $safetyCheck->trip_date ?? '') }}" required>
        </div>
        <div class="form-group">
            <label for="pick_up_point">Pick-Up Point</label>
            <input type="text" name="pick_up_point" class="form-control" value="{{ $safetyCheck->pick_up_point }}" required>
        </div>
        <div class="form-group">
            <label for="destination">Destination</label>
            <input type="text" name="destination" class="form-control" value="{{ $safetyCheck->destination }}" required>
        </div>
        <div class="form-group">
            <label for="pdf_file">PDF File</label>
            <input type="file" name="pdf_file" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Update Safety Check</button>
    </form>
</div>
@endsection