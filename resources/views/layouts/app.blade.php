<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ config('app.name', 'CareChain') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />

    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <style>
        body {
            background: url('/images/background.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Nunito', sans-serif;
            color: #333;
            min-height: 100vh;
            direction: ltr;
        }
        .navbar {
            background-color: #ffffffcc;
            box-shadow: 0 2px 6px rgb(0 0 0 / 0.1);
        }
        .nav-link {
            color: #333 !important;
            transition: color 0.3s ease;
        }
        .nav-link:hover {
            color: #007bff !important;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #0056b3;
        }
        main.py-4 {
            padding-top: 3rem !important;
            padding-bottom: 3rem !important;
            min-height: calc(100vh - 70px);
        }
        .container {
            background-color: rgba(255,255,255,0.9);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 0 15px rgb(0 0 0 / 0.1);
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light shadow-sm">
            <div class="container">
                <a class="navbar-brand fw-bold text-primary" href="{{ url('/') }}">
                    {{ config('app.name', 'CareChain') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side -->
                    <ul class="navbar-nav me-auto">
                        @auth
                            @if(auth()->user()->role === 'patient')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('family.dashboard') }}">Notes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('medications.index') }}">Medications</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('appointments.index') }}">Appointments</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('medical_files.create', ['patient' => auth()->id()]) }}">Medical Files</a>
                            </li>
                            @elseif(auth()->user()->role === 'caregiver')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('caregiver_patients') }}">Patients</a>
                            </li>
                            @endif
                        @endauth
                    </ul>

                    <!-- Right Side -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register_choice') }}">Register</a>
                            </li>
                        @else
                        <li class="nav-item d-flex align-items-center">
            <span class="nav-link">{{ Auth::user()->name }}</span>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="ms-2">
                @csrf
                <button type="submit" class="btn btn-link nav-link" style="padding: 0; border: none; cursor: pointer;">
                    Logout
                </button>
            </form>
        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const logoutLink = document.getElementById('logout-link');
            if(logoutLink) {
                logoutLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.getElementById('logout-form').submit();
                });
            }
        });
    </script>
</body>
</html>

