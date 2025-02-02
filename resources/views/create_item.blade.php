@extends('layouts.app')

@section('title', 'Add New Item')

@section('content')
<div class="item-detail-container">
    <h1 class="text-center"><span class="pink-highlight">Add New Item</span></h1>

    <!-- Diplay any errors (if any) that are passed from the route in this box-->
    @if(session('errors'))
        <div class="alert error-msg">
            @foreach (session('errors') as $error)
                {{ $error }}<br> <!-- Line break -->
            @endforeach
        </div>
    @endif

    <form action="{{ url('/item/store') }}" method="POST" enctype="multipart/form-data">
        <!-- Include a CSRF token to the form ensuring that the form submission is coming from the authenticated user -->
        @csrf
        <div class="form-group">
            <label for="name">Item Name</label>
            <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" required>
            <!-- old() is used to retrieve the old input data from the previous request. So if there was errors,
             you can repopulate the form fields with the data that the user had previously entered, so they don't have to retype everything.-->
        </div>

        <div class="form-group">
            <label for="manufacturer">Manufacturer</label>
            <input type="text" name="manufacturer" class="form-control" id="manufacturer" value="{{ old('manufacturer') }}" required>
        </div>

        <div class="form-group">
            <label for="price">Price</label>
            <input type="number" step="0.01" name="price" class="form-control" id="price" value="{{ old('price') }}" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" class="form-control" id="description" rows="4" required>{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label for="image">Item Image</label>
            <input type="file" name="image" class="form-control-file" id="image" required>
        </div>

        <button type="submit" class="btn btn-custom">Add Item</button>
    </form>
</div>
@endsection
