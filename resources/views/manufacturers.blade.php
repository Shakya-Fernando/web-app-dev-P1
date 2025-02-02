<!-- resources/views/manufacturers.blade.php -->
@extends('layouts.app')

@section('title', 'Manufacturers')

@section('content')
<div class="manufacturers-container">
    <h1 class="text-center"><span class="pink-highlight">Our</span> Manufacturers</h1>

    <!-- Loop through manu -->
    <div class="row">
        @foreach ($manufacturers as $manufacturer)
            <div class="col-md-4">
                <div class="card-custom">
                    <div class="card-body">
                        <h5 class="card-title pink-highlight">{{ $manufacturer->manufacturer }}</h5>
                        <p class="card-text"><strong>Average Rating:</strong> 
                            @if($manufacturer->manufacturer_avg_rating !== null)
                                {{ number_format($manufacturer->manufacturer_avg_rating, 1) }} / 5
                            @else
                                No ratings yet
                            @endif
                        </p>
                        <p class="card-text"><strong>Total Reviews:</strong> 
                            {{ $manufacturer->total_reviews }}
                        </p>
                        <a href="{{ url('/manufacturer/' . $manufacturer->manufacturer) }}" class="btn btn-custom">View Items</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
