@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Truck</h1>
    <form action="{{ route('trucks.update', $truck->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="image">Truck Image</label>
            <input type="file" name="image" class="form-control">
        </div>
        <div class="form-group">
            <label for="plat_no">Plat No</label>
            <input type="text" name="plat_no" class="form-control" value="{{ $truck->plat_no }}" required>
        </div>
        <div class="form-group">
            <label for="brand_truk">Brand</label>
            <input type="text" name="brand_truk" class="form-control" value="{{ $truck->brand_truk }}" required>
        </div>
        <div class="form-group">
            <label for="no_stnk">No STNK</label>
            <input type="text" name="no_stnk" class="form-control" value="{{ $truck->no_stnk }}" required>
        </div>
        <div class="form-group">
            <label for="no_kir">No KIR</label>
            <input type="text" name="no_kir" class="form-control" value="{{ $truck->no_kir }}" required>
        </div>
        <div class="form-group">
            <label for="no_pajak">No Pajak</label>
            <input type="text" name="no_pajak" class="form-control" value="{{ $truck->no_pajak }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Truck</button>
    </form>
</div>
@endsection