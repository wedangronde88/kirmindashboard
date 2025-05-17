@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add Truck</h1>
    <form action="{{ route('trucks.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="image">Truck Image</label>
            <input type="file" name="image" class="form-control">
        </div>
        <div class="form-group">
            <label for="plat_no">Plat No</label>
            <input type="text" name="plat_no" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="brand_truk">Brand</label>
            <input type="text" name="brand_truk" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="no_stnk">No STNK</label>
            <input type="text" name="no_stnk" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="no_kir">No KIR</label>
            <input type="text" name="no_kir" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="no_pajak">No Pajak</label>
            <input type="text" name="no_pajak" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Add Truck</button>
    </form>
</div>
@endsection