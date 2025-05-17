@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Service Record for {{ $truck->plat_no }}</h1>
    <form action="{{ route('trucks.service-records.update', [$truck->id, $serviceRecord->id]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="service_date">Service Date</label>
            <input type="date" name="service_date" class="form-control" value="{{ $serviceRecord->service_date }}" required>
        </div>
        <div class="form-group">
            <label for="service_type">Service Type</label>
            <input type="text" name="service_type" class="form-control" value="{{ $serviceRecord->service_type }}" required>
        </div>
        <div class="form-group">
            <label for="costs">Costs</label>
            <input type="number" name="costs" class="form-control" value="{{ $serviceRecord->costs }}" required>
        </div>
        <div class="form-group">
            <label for="remarks">Remarks</label>
            <textarea name="remarks" class="form-control">{{ $serviceRecord->remarks }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Service Record</button>
    </form>
</div>
@endsection