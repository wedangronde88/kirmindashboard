@extends('layouts.app')

@section('content')
<style>
    .truck-card {
        transition: box-shadow 0.18s cubic-bezier(.4,0,.2,1), transform 0.16s cubic-bezier(.4,0,.2,1);
        border: none;
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 2px 12px 0 rgba(34,44,54,0.07);
        overflow: hidden;
        cursor: pointer;
    }
    .truck-card:hover, .truck-card:focus-within {
        box-shadow: 0 8px 32px 0 rgba(34,44,54,0.13);
        transform: translateY(-4px) scale(1.025);
        border-color: #0d6efd22;
    }
    .truck-card .card-img-top {
        border-radius: 0;
        border-bottom: 1px solid #f0f0f0;
        background: #f8fafc;
    }
    .truck-card .card-title {
        font-size: 1.15rem;
        letter-spacing: 0.5px;
    }
    .truck-card .badge {
        font-size: 0.85rem;
        border-radius: 8px;
        padding: 0.35em 0.7em;
    }
    .truck-card .dropdown-menu {
        border-radius: 12px;
        min-width: 140px;
        font-size: 0.97rem;
    }
    .truck-card .btn-light {
        background: #f6f8fa;
        border: none;
    }
    .truck-card .btn-light:hover {
        background: #e3e7ed;
    }
    .kebab-icon {
        display: inline-block;
        width: 20px;
        height: 20px;
        vertical-align: middle;
    }
    .kebab-icon span {
        display: block;
        width: 4px;
        height: 4px;
        margin: 2.5px auto;
        border-radius: 50%;
        background: #222c36;
        opacity: 0.7;
    }
    .btn-light:focus .kebab-icon span,
    .btn-light:hover .kebab-icon span {
        background: #0d6efd;
        opacity: 1;
    }
</style>
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
                <div class="card truck-card h-100 position-relative">
                    <a href="{{ route('trucks.show', $truck->id) }}" class="stretched-link text-decoration-none text-dark">
                        @if ($truck->image)
                            <img src="{{ asset('storage/' . $truck->image) }}" class="card-img-top" alt="Truck Image" style="height:180px; object-fit:cover;">
                        @else
                            <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height:180px;">
                                <span class="text-muted">No Image</span>
                            </div>
                        @endif
                        <div class="card-body pb-3">
                            <h5 class="card-title fw-bold mb-2">{{ $truck->plat_no }} <span class="text-primary">- {{ $truck->brand_truk }}</span></h5>
                            <div class="mb-2">
                                <span class="badge bg-info text-dark me-1">STNK: {{ $truck->no_stnk }}</span>
                                <span class="badge bg-success me-1">KIR: {{ $truck->no_kir }}</span>
                                <span class="badge bg-warning text-dark">Pajak: {{ $truck->no_pajak }}</span>
                            </div>
                        </div>
                    </a>
                    <!-- Kebab Menu Button -->
                    <div class="position-absolute top-0 end-0 m-2" style="z-index:2;">
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm rounded-circle shadow-sm" type="button" id="dropdownMenuButton{{ $truck->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="kebab-icon">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                </span>
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