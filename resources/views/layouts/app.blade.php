<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css'])
    <title>@yield('title', 'Film Labpro')</title>
</head>

<body class="bg-gray-900 text-white">

    
    <nav class="p-4 bg-gray-800">
        <a href="/" class="text-white">Home</a>
        <a href="/browse" class="ml-4 text-white">Browse Films</a>
        @if ($user)
            <a href="/dashboard" class="ml-4 text-white">Dashboard</a>
            <a href="/logout" class="ml-4 text-white">Logout</a>
            <span class="ml-4 text-white">Hello, {{ $user->username }}</span>
            <span class="ml-4 text-white">Balance: {{ $user->balance }}</span>
        @else
            <a href="/login" class="ml-4 text-white">Login</a>
            <a href="/register" class="ml-4 text-white">Register</a>
        @endif

    </nav>


    <div class="container mx-auto p-4">
        @if(session('message'))
            <div class="bg-blue-500 text-white p-4 rounded mb-4 transition-opacity duration-500 max-w-lg mx-auto">
                {{ session('message') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-500 text-white p-4 rounded mb-4 transition-opacity duration-500 max-w-lg mx-auto">
                {{ session('error') }}
            </div>
        @endif
        @yield('content')
    </div>
</body>

</html>