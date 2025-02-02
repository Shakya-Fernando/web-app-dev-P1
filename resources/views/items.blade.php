@extends('layouts.app')

@section('title', 'Browse Items')

@section('content')
<div class="items-container">

    <!-- Display Success Message -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <h1 class="text-center"><span class="pink-highlight">Browse</span> our Products</h1>

    <!-- Add New Item Button -->
    <div class="text-right mb-4">
        <a href="{{ url('/item/create') }}" class="btn btn-custom btn-add">Add New Item</a> <!-- Static URL -->
    </div>

    <!-- Sorting Options -->
    <div class="sort-options mb-4">
        <form action="{{ url('/items') }}" method="GET" class="form-inline">
            <label for="sort_by"><strong>Sort by:</strong></label>
            <select name="sort_by" class="form-control mr-2">
                <option value="review_count">Number of Reviews</option>
                <option value="avg_rating">Average Rating</option>
            </select>

            <label for="order"><strong>Order:</strong></label>
            <select name="order" class="form-control mr-2">
                <option value="asc">Ascending</option>
                <option value="desc">Descending</option>
            </select>

            <button type="submit" class="btn btn-sort">Sort</button>
        </form>
    </div>
    <!-- Loop through items -->
    <div class="row">
        @foreach ($items as $item)
            <div class="col-md-4">
                <div class="card-custom">
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
                    <div class="card-body">
                        <h5 class="card-title pink-highlight">{{ $item->name }}</h5>
                        <p class="card-text"><strong>Price:</strong> ${{ $item->price }}</p>
                        <p class="card-text"><strong>Manufacturer:</strong> {{ $item->manufacturer }}</p>
                        <p class="card-text"><strong>Number of Reviews:</strong> {{ $item->review_count }}</p>
                        <p class="card-text"><strong>Average Rating:</strong> 
                            @if($item->avg_rating)
                                {{ number_format($item->avg_rating, 1) }} / 5
                            @else
                                No ratings yet
                            @endif
                        </p>
                        <a href="{{ url('/item/' . $item->id) }}" class="btn btn-custom">View Item</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
