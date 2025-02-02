@extends('layouts.app')

@section('title', $item->name)

@section('content')
<div class="item-detail-container">
    <h1 class="text-center"><span class="pink-highlight">{{ $item->name }}</span></h1>

    <!-- Display Success Message -->
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Item Detailks Section -->
    <div class="row mt-4">
        <div class="col-md-6 img-detail">
            <!-- asset generates full URL path to public/storage & item->image is dynamic img file name in db-->
            <img src="{{ asset('storage/' . $item->image) }}" class="img-fluid" alt="{{ $item->name }}">
        </div>
        <div class="col-md-6">
            <h3><u>Item Details</u></h3>
            <p><strong>Manufacturer:</strong> {{ $item->manufacturer }}</p>
            <p><strong>Price:</strong> ${{ $item->price }}</p>
            <p><strong>Description:</strong> {{ $item->description }}</p>
            
            <!-- Delete Button -->
            <!-- Static AND Dynamic URL -->
            <form action="{{ url('/item/' . $item->id . '/delete') }}" method="POST" class="mt-4"> <!-- HTML only supports GET and POST methods by default -->
                <!-- Include a CSRF token to the form ensuring that the form submission is coming from the authenticated user -->
                <!-- Prevents XSS Attacks -->
                @csrf
                @method('DELETE') <!-- Laravel feature -->
                <button type="submit" class="btn btn-danger btn-delete">Delete Item</button>
            </form>
        </div>
    </div>
    
    <!-- Reviews Section for that Item -->
    <div class="reviews-container mt-5">
        <h3 class="text-center"><span class="pink-highlight"> Member </span>Reviews</h3>

        <!-- Add Review Button -->
        <div class="text-right mb-4">
            <a href="{{ url('/item/' . $item->id . '/review/create') }}" class="btn btn-custom btn-review">Add Review</a>
        </div>

        <!-- If no reviews -->
        @if(empty($reviews))
            <p class="text-center">No reviews yet for this item.</p>
        @else

            <!-- Loop through reviews for this particular item -->
            <div class="review-list">
                @foreach ($reviews as $review)
                    <div class="review-item mb-4">
                        <h5 class="pink-highlight">{{ $review->username }}</h5>
                        <p><strong>Rating:</strong> {{ $review->rating }} / 5</p> <!-- X/5 -->
                        <p>{{ $review->review_text }}</p>
                        <!-- strtotine converts date string (in the db) into a Unix timestamp for date() -->
                        <p><small>Reviewed on {{ date('F j, Y', strtotime($review->date_posted)) }}</small></p> <!-- F - full month name, j - day number, Y - year -->

                        <!-- Edit and Delete Buttons for Reviews -->
                        <div>
                            <a href="{{ url('/item/' . $item->id . '/review/' . $review->id . '/edit') }}" class="btn btn-edit-sm">Edit</a> <!-- GET method to fetch existing data -->
                            
                            <form action="{{ url('/item/' . $item->id . '/review/' . $review->id . '/delete') }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-delete-sm">Delete</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
