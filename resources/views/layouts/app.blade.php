
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css'])
    <title>@yield('title', 'My Website')</title>
</head>

<body class="bg-gray-900 text-white">


    <nav class="p-4 bg-gray-800">
        <a href="/" class="text-white">Home</a>
        <a href="/browse" class="ml-4 text-white">Browse Films</a>
        <a href="/login" class="ml-4 text-white">Login</a>
        <a href="/register" class="ml-4 text-white">Register</a>
        \
    </nav>


    <div class="container mx-auto p-4">
        @yield('content')
    </div>

</body>

</html>