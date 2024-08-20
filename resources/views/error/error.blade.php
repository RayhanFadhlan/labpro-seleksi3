<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css'])
    <title>Error</title>
    
</head>

<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-red-600">Error</h1>
        <div id="error-message" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
            <!-- Error message will be inserted here -->
        </div>
        <a href="/" class="text-blue-500 hover:underline">Go back to Home</a>
    </div>

    <script>
        
        const urlParams = new URLSearchParams(window.location.search);
        const errorMessage = urlParams.get('message');

        
        if (errorMessage) {
            document.getElementById('error-message').innerText = errorMessage;
        } else {
            document.getElementById('error-message').innerText = 'An unknown error occurred.';
        }
    </script>
</body>

</html>