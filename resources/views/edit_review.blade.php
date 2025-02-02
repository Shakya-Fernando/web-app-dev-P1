@extends('layouts.app')

@section('title', 'Edit Review for ' . $item->name)

@section('content')
<div class="item-detail-container">
    <h1 class="text-center"><span class="pink-highlight">Edit Review for {{ $item->name }}</span></h1>
    
    <!-- Display Success Message -->
    @if(session('errors'))
        <div class="alert error-msg">
            @foreach (session('errors') as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif

    <form action="{{ url('/item/' . $item->id . '/review/' . $review->id . '/update') }}" method="POST">
        <!-- Include a CSRF token to the form ensuring that the form submission is coming from the authenticated user -->
        @csrf
        <div class="form-group">
            <label for="username">Your Name</label>
            <input type="text" name="username" class="form-control" id="username" value="{{ $review->username }}" readonly> <!-- READONLY -->
        </div>

        <div class="form-group">
            <label for="rating">Rating</label>
            <input type="number" step="1" min="1" max="5" name="rating" class="form-control" id="rating" value="{{ $review->rating }}" required>
        </div>

        <div class="form-group">
            <label for="review_text">Review</label>
            <textarea name="review_text" class="form-control" id="review_text" rows="4" required>{{ $review->review_text }}</textarea>
        </div>

        <button type="submit" class="btn btn-custom">Update Review</button>
    </form>
</div>
@endsection
