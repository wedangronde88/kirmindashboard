@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add Service Record for {{ $truck->plat_no }}</h1>
    <form action="{{ route('trucks.service-records.store', $truck->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="service_date">Service Date</label>
            <input type="date" name="service_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="service_type">Service Type</label>
            <input type="text" name="service_type" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="costs">Costs</label>
            <input type="number" name="costs" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="remarks">Remarks</label>
            <textarea name="remarks" class="form-control"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Add Service Record</button>
    </form>
</div>
@endsection