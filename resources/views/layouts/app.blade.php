<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav style="background-color: #333; padding: 10px; display: flex; justify-content: space-between; align-items: center;">
            <!-- Logo -->
            <a href="{{ url('/') }}" class="navbar-brand">
                <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 120px; height: auto;">
            </a>
            
            <div style="display: flex; gap: 10px; margin-right: 10px; align-items: center;">
                @auth
                    <a href="{{ url('/questionnaires') }}" style="color: white; text-decoration: none;">Home</a>
                    
                    <!-- User Name -->
                    <span style="color: white;">{{ Auth::user()->name }}</span>
                    
                    <!-- Logout Button -->
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" style="background-color: red; color: white; border: none; padding: 5px 10px; cursor: pointer; border-radius: 5px;">
                            Logout
                        </button>
                    </form>
                @else
                    @if (Route::has('login'))
                        <a href="{{ route('login') }}" style="color: white; text-decoration: none;">Login</a>
                    @endif
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" style="color: white; text-decoration: none;">Register</a>
                    @endif
                @endauth
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
