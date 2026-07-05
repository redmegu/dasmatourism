@extends('layouts.app')

@section('title', 'Search Results - Dasmariñas Tourism')

@section('content')
    <div class="py-5">
        <div class="container">
            <!-- Search Header -->
            <div class="mb-4">
                <h2 class="fw-bold">Search Results</h2>
                <p class="text-muted">Showing results for: <strong>"{{ $query }}"</strong></p>
            </div>

            <!-- Search Bar -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="{{ route('search') }}">
                        <div class="input-group input-group-lg">
                            <span class="input-group-text">
                                <i class='bx bx-search'></i>
                            </span>
                            <input type="text" name="q" class="form-control"
                                placeholder="Search attractions, businesses..." value="{{ $query }}" required>
                            <button type="submit" class="btn btn-primary">
                                Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Attractions Results -->
            @if ($attractions->count() > 0)
                <div class="mb-5">
                    <h4 class="fw-bold mb-3">
                        <i class='bx bx-map-pin'></i> Attractions ({{ $attractions->count() }})
                    </h4>

                    <div class="row g-4">
                        @foreach ($attractions as $attraction)
                            <div class="col-md-4">
                                <div class="card h-100">
                                    @if ($attraction->primaryImage)
                                        <img src="{{ asset('storage/' . $attraction->primaryImage->image_path) }}"
                                            class="card-img-top" alt="{{ $attraction->name }}">
                                    @else
                                        <div class="card-img-top img-placeholder">
                                            <i class='bx bx-image'></i>
                                        </div>
                                    @endif
                                    <div class="card-body">
                                        <span class="badge bg-primary mb-2">{{ $attraction->category->name }}</span>
                                        <h5 class="card-title">{{ $attraction->name }}</h5>
                                        <p class="card-text text-muted small">
                                            <i class='bx bx-map'></i> {{ Str::limit($attraction->address, 50) }}
                                        </p>
                                        <a href="{{ route('attractions.show', $attraction->slug) }}"
                                            class="btn btn-outline-primary btn-sm">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Businesses Results -->
            @if ($businesses->count() > 0)
                <div class="mb-5">
                    <h4 class="fw-bold mb-3">
                        <i class='bx bx-store'></i> Businesses ({{ $businesses->count() }})
                    </h4>

                    <div class="row g-4">
                        @foreach ($businesses as $business)
                            <div class="col-md-4">
                                <div class="card h-100">
                                    @if ($business->primaryImage)
                                        <img src="{{ asset('storage/' . $business->primaryImage->image_path) }}"
                                            class="card-img-top" alt="{{ $business->name }}">
                                    @else
                                        <div class="card-img-top img-placeholder">
                                            <i class='bx bx-store'></i>
                                        </div>
                                    @endif
                                    <div class="card-body">
                                        <span class="badge bg-success mb-2">{{ $business->category->name }}</span>
                                        <h5 class="card-title">{{ $business->name }}</h5>
                                        <p class="card-text text-muted small">
                                            <i class='bx bx-map'></i> {{ Str::limit($business->address, 50) }}
                                        </p>
                                        <a href="{{ route('businesses.show', $business->slug) }}"
                                            class="btn btn-outline-success btn-sm">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- No Results -->
            @if ($attractions->count() === 0 && $businesses->count() === 0)
                <div class="text-center py-5">
                    <i class='bx bx-search-alt' style="font-size: 5rem; color: #cbd5e1;"></i>
                    <h4 class="mt-3">No Results Found</h4>
                    <p class="text-muted">Try searching with different keywords</p>
                    <a href="{{ route('home') }}" class="btn btn-primary mt-3">
                        <i class='bx bx-home'></i> Back to Home
                    </a>
                </div>
            @endif
        </div>
    </div>
@endsection
