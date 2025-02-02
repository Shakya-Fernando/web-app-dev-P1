@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="profile-container">
    <div class="profile-header">
        <h1> <span class="pink-highlight">Welcome </span>to My Profile</h1>
    </div>
    
    <div class="profile-content">
        <div class="profile-info">
            <!-- Round/Circle image container -->
            <div class="profile-image">
                <img src="{{ asset('images/feature1.png') }}" alt="Profile Image">
            </div>

            <!-- Box under for Name and Date -->
            <div class="profile-details-box mt-3">
                <p><strong class="pink-highlight">Name:</strong> Pengu The Third</p>
                <p><strong class="pink-highlight">Birthday:</strong> 16/08/2001</p>
            </div>
        </div>
        
        <div class="profile-details">
            <h2>Your <span class="pink-highlight">Reviews:</span></h2>
            <p>display ALL reviews this use had made</p>
            <p>the profile picture and details should change for each user</p>
            <!-- Add more details -->
        </div>
    </div>
</div>
@endsection
