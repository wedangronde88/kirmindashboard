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

    <!-- Card Grid -->
    <div class="row">
        @forelse ($trucks as $truck)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-lg border-0 rounded-4 overflow-hidden position-relative" style="cursor:pointer;">
                    <a href="{{ route('trucks.show', $truck->id) }}" class="stretched-link text-decoration-none text-dark">
                        @if ($truck->image)
                            <img src="{{ asset('storage/' . $truck->image) }}" class="card-img-top" alt="Truck Image" style="height:180px; object-fit:cover;">
                        @else
                            <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height:180px;">
                                <span class="text-muted">No Image</span>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-2">{{ $truck->plat_no }} <span class="text-primary">- {{ $truck->brand_truk }}</span></h5>
                            <div class="mb-2">
                                <span class="badge bg-info text-dark me-1">STNK: {{ $truck->no_stnk }}</span>
                                <span class="badge bg-success me-1">KIR: {{ $truck->no_kir }}</span>
                                <span class="badge bg-warning text-dark">Pajak: {{ $truck->no_pajak }}</span>
                            </div>
                        </div>
                    </a>
                    <!-- Hamburger Menu -->
                    <div class="position-absolute top-0 end-0 m-2" style="z-index:2;">
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm rounded-circle shadow-sm" type="button" id="dropdownMenuButton{{ $truck->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton{{ $truck->id }}">
                                <li>
                                    <a class="dropdown-item" href="{{ route('trucks.edit', $truck->id) }}">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                </li>
                                <li>
                                    <form action="{{ route('trucks.destroy', $truck->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center">
                <p>No trucks found.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection