@extends('layouts.app')

@section('title', 'Items from ' . $manufacturer)

@section('content')
<div class="manufacturer-items-container">
    <h1 class="text-center">Products from <span class="pink-highlight"> {{ $manufacturer }}</span></h1>

    <!-- Loop through items -->
    <div class="row">
        @foreach ($items as $item)
            <div class="col-md-4">
                <div class="card-custom">
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
                    <div class="card-body">
                        <h5 class="card-title pink-highlight">{{ $item->name }}</h5>
                        <p class="card-text"><strong>Price:</strong> ${{ $item->price }}</p>
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
