<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    @vite('resources/css/app.css') {{-- Or your equivalent Tailwind CSS include --}}
</head>
<body class="bg-gray-100 font-sans antialiased">

    <header class="bg-white shadow-md">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-2xl font-bold text-gray-800">Your App Name</a>
            <div>
                @auth
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900 mx-2">Dashboard</a>
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-gray-600 hover:text-gray-900 mx-2">Logout</button>
                    </form>
                @else
                    {{-- <a href="{{ route('login') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-full mx-2">Login</a>
                    <a href="{{ route('register') }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-full mx-2">Register</a> --}}
                @endauth
            </div>
        </nav>
    </header>

    <main class="container mx-auto px-6 py-8 h-193">
        <div class="bg-white p-8 rounded-lg shadow-lg text-center">
            <h1 class="text-4xl font-bold text-gray-800 mb-4">Welcome to Our Application!</h1>
            <p class="text-lg text-gray-600 mb-6">This is your home page. Feel free to explore.</p>

            @auth
                <p class="text-xl text-blue-600">You are logged in!</p>
            @else
                <p class="text-xl text-green-600">Please login or register to continue.</p>
                <div class="mt-8">
                    <a href="{{ route('login') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-full text-lg mr-4">Login</a>
                    <a href="{{ route('register') }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-6 rounded-full text-lg">Register</a>
                </div>
            @endauth
        </div>
    </main>

    <footer class="bg-white shadow-md mt-8">
        <div class="container mx-auto px-6 py-4 text-center text-gray-600">
            &copy; {{ date('Y') }} Your App Name. All rights reserved.
        </div>
    </footer>

</body>
</html>
