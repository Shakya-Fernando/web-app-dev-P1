<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Review App')</title>
    <!-- Include Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg">
        <a class="navbar-brand" href="{{ route('home') }}"><span class="pink-highlight">Pixel</span> PC</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <!-- List of Views -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}"><strong>Home</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('items') }}"><strong>Browse</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('manufacturers') }}"><strong>Manufacturers</strong></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('aboutus') }}"><strong>About Us</strong></a>
                </li>
                <!-- Profile Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link profile-link" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <strong>Profile</strong> <img src="{{ asset('images/feature1.png') }}" alt="Profile" class="rounded-circle">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="{{ route('profile') }}"><strong>View Profile</strong></a>
                        <a class="dropdown-item" href="{{ route('settings') }}"><strong>Settings</strong></a>
                        <a class="dropdown-item" href="{{ route('home') }}"><strong>Logout</strong></a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content Area -->
    <div class="container mt-4">
        @yield('content')
    </div>

    <!-- Include Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
