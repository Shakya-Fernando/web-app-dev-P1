@extends('layouts.app')

@section('title', 'Home')

@section('content')

<!-- Hero Section/Banner with Full-Width Background -->
<div class="jumbotron jumbotron-custom">
    <h1 class="display-4"><span class="pink-highlight">Welcome </span> to Pixel PC</h1>
    <p class="lead">Explore our latest products of Custom PCs.</p>
    <a class="btn btn-custom btn-lg" href="{{ route('items') }}" role="button">Browse Now</a>
</div>

<!-- Features Section with Full-Width Cards -->
<div class="cards-container container-fluid mt-5">
    <h1 class="cards-container-title">
        <span class="most-popular">Most Popular</span> <span class="pink-highlight">Right Now</span>
    </h1>

<!-- Loop through TopItems -->
    <div class="row">
        @foreach ($topItems as $item)
            <div class="col-md-4">
                <div class="card-custom">
                    <img src="{{ asset('storage/' . $item->image) }}" class="card-img-top" alt="{{ $item->name }}">
                    <div class="card-body">
                        <h5 class="card-title pink-highlight">{{ $item->name }}</h5>
                        <p class="card-text"><strong>Price:</strong> ${{ $item->price }}</p>
                        <p class="card-text"><strong>Manufacturer:</strong> {{ $item->manufacturer }}</p>
                        <p class="card-text"><strong>Average Rating:</strong> <!-- Chewck if item has avg rating, if so displau X/5 to 1 d.p -->
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
