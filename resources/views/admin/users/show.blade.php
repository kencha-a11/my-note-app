<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    @vite('resources/css/app.css') {{-- Link to your compiled Tailwind CSS --}}
</head>
<body class="bg-gray-100 font-sans antialiased min-h-screen flex flex-col">

    {{-- Header --}}
    <header class="bg-white shadow-md">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-2xl font-bold text-gray-800">Your App Name</a>
            <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900 transition duration-200">Return to Dashboard</a>
        </nav>
    </header>

    {{-- Main Content --}}
    <main class="container mx-auto px-6 py-8 flex-grow">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-2xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">My Profile</h1>

            {{-- Success/Error Messages --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Success!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            {{-- Profile Information --}}
            <div class="mb-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-4 border-b pb-2">Profile Information</h2>

                <p class="mb-3 text-lg text-gray-700">
                    <strong class="font-medium text-gray-900">Name:</strong> {{ $user->name }}
                </p>
                <p class="mb-3 text-lg text-gray-700">
                    <strong class="font-medium text-gray-900">Email:</strong> {{ $user->email }}
                </p>
                <p class="mb-3 text-lg text-gray-700">
                    <strong class="font-medium text-gray-900">Member Since:</strong> {{ $user->created_at->format('M d, Y') }}
                </p>

                {{-- Show role status --}}
                <p class="mb-3 text-lg text-gray-700 flex items-center">
                    <strong class="font-medium text-gray-900 mr-2">Role:</strong>
                    @if($user->is_admin)
                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">Administrator</span>
                    @else
                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">Regular User</span>
                    @endif
                </p>
            </div>

            {{-- Action Buttons for the authenticated user --}}
            <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('admin.users.edit', $user->id) }}"
                   class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200 w-full sm:w-auto text-center">
                    Edit Profile
                </a>

                {{-- Admin can access admin panel --}}
                @if(auth()->user()->is_admin)
                    <a href="{{ route('admin.users.index') }}"
                       class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 w-full sm:w-auto text-center">
                        Admin Panel
                    </a>
                @endif

                {{-- Logout Button --}}
                {{-- <form action="{{ route('sessions.destroy')}}" method="POST" class="w-full sm:w-auto">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg w-full transition duration-200">
                        Logout
                    </button>
                </form> --}}

                {{-- Delete Account (with confirmation) --}}
                {{-- <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.')" class="w-full sm:w-auto">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200 w-full">
                        Delete Account
                    </button>
                </form> --}}
            </div>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="bg-white shadow-md mt-auto">
        <div class="container mx-auto px-6 py-4 text-center text-gray-600">
            &copy; {{ date('Y') }} Your App Name. All rights reserved.
        </div>
    </footer>

</body>
</html>
