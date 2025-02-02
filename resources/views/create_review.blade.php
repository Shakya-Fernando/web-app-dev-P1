@extends('layouts.app')

@section('title', 'Add Review for ' . $item->name)

@section('content')
<div class="item-detail-container">
    <h1 class="text-center">Add Review for <span class="pink-highlight">{{ $item->name }}</span></h1>
    
    <!-- Display Success Message -->
    @if(session('errors'))
        <div class="alert error-msg">
            @foreach (session('errors') as $error)
                {{ $error }}<br>
            @endforeach
        </div>
    @endif

    <form action="{{ url('/item/' . $item->id . '/review/store') }}" method="POST">
        <!-- Include a CSRF token to the form - protect against Cross-Site Request Forgery attacks by ensuring that the form submission is coming from the authenticated user -->
        @csrf
        <div class="form-group">
            <label for="username">Your Name</label>
            <!-- Prefill the username with session value if it exists, but allow editing -->
            <input type="text" name="username" class="form-control" id="username" 
                   value="{{ session('username', old('username')) }}" required>
            <!-- if readonly then use after value {{ session('username') ? 'readonly' : '' }} required>-->
        </div>

        <div class="form-group">
            <label for="rating">Rating</label>
            <input type="number" name="rating" class="form-control" id="rating" value="{{ old('rating') }}" required> <!-- could add client side validation: step="1" min="1" max="5" -->
        </div>

        <div class="form-group">
            <label for="review_text">Review</label>
            <textarea name="review_text" class="form-control" id="review_text" rows="4" required>{{ old('review_text') }}</textarea> <!-- the textarea element is designed to hold its value as its content, not as an attribute. -->
        </div>

        <button type="submit" class="btn btn-custom">Submit Review</button>
    </form>
</div>
@endsection
