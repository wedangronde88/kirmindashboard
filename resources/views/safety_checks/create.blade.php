@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add Safety Check for {{ $truck->plat_no }}</h1>
    <form action="{{ route('trucks.safety-checks.store', $truck->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="trip_date">Trip Date</label>
            <input type="date" name="trip_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="pick_up_point">Pick-Up Point</label>
            <input type="text" name="pick_up_point" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="destination">Destination</label>
            <input type="text" name="destination" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="pdf_file">PDF File</label>
            <input type="file" name="pdf_file" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Safety Check</button>
    </form>
</div>
@endsection