<!-- resources/views/aboutus.blade.php -->
@extends('layouts.app')

@section('title', 'About Us')

@section('content')
<!-- Random Plain Text -->
<div class="aboutus-container">
    <h1 class="text-center"><span class="pink-highlight"> About </span> Pixel PC</h1>

    <section class="our-mission mt-5">
        <h2>Our Mission</h2>
        <p>Best Review site for PC Parts</p>
    </section>

    <section class="our-values mt-5">
        <h2>Our Values</h2>
        <ul>
            <li><strong class="pink-highlight">Customization:</strong> Hello World.</li>
            <li><strong class="pink-highlight">Creativity:</strong> Hello World.</li>
            <li><strong class="pink-highlight">Community:</strong> Hello World.</li>
        </ul>
    </section>

    <section class="our-team mt-5">
        <h2>Our Team</h2>
        <p>Meet team: <strong class="pink-highlight">Shakya Fernando</strong></p>
    </section>

    <section class="our-journey mt-5">
        <h2>Our Journey</h2>
        <p>Hello World</p>
    </section>

    <section class="contact-info mt-5">
        <h2>Contact Us</h2>
        <p>Provide your feedback at <a href="mailto:support@pixelpc.com">support@pixelpc.com.</a></p>
    </section>
</div>
@endsection
