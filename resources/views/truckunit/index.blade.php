@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Truck List</h1>

    <!-- Add Truck Button -->
    <a href="{{ route('trucks.create') }}" class="btn btn-primary mb-3">Add Truck</a>

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Trucks Table -->
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Plat No</th>
                <th>Brand</th>
                <th>No STNK</th>
                <th>No KIR</th>
                <th>No Pajak</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($trucks as $truck)
                <tr>
                    <td>{{ $truck->id }}</td>
                    <td>
                        @if ($truck->image)
                            <img src="{{ asset('storage/' . $truck->image) }}" alt="Truck Image" style="width: 100px; height: 60px; object-fit: cover;">
                        @else
                            <span>No Image</span>
                        @endif
                    </td>
                    <td>{{ $truck->plat_no }}</td>
                    <td>{{ $truck->brand_truk }}</td>
                    <td>{{ $truck->no_stnk }}</td>
                    <td>{{ $truck->no_kir }}</td>
                    <td>{{ $truck->no_pajak }}</td>
                    <td>
                        <!-- Show Button -->
                        <a href="{{ route('trucks.show', $truck->id) }}" class="btn btn-info btn-sm">Show</a>

                        <!-- Edit Button -->
                        <a href="{{ route('trucks.edit', $truck->id) }}" class="btn btn-warning btn-sm">Edit</a>

                        <!-- Delete Form -->
                        <form action="{{ route('trucks.destroy', $truck->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">No trucks found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection