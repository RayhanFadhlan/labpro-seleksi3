<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css'])
    <meta name="description"
        content="Website untuk membeli film dan menonton film, mirip netflix.">
    <title>@yield('title', 'Film Labpro')</title>
</head>

<body class="bg-gray-900 text-white">

    <nav class="p-4 bg-gray-800 flex justify-between">
        <div>
        
            <a href="/" class="ml-4 text-white">Browse Films</a>
            @if ($user)
                <a href="/dashboard" class="ml-4 text-white">Dashboard</a>
            @endif
        </div>
        <div class="flex items-center">
            @if ($user)
                <span class="ml-4 text-white">Balance: {{ $user->balance }}</span>
                <span class="ml-4 text-white">{{ $user->username }}</span>
                <form action="{{ route('logout') }}" method="POST" class="inline ml-4">
                    @csrf
                    <button type="submit" class="text-white">Logout</button>
                </form>
            @else
                <a href="/login" class="ml-4 text-white">Login</a>
                <a href="/register" class="ml-4 text-white">Register</a>
            @endif
        </div>
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