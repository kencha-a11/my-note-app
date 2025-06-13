<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gray-100 font-sans antialiased min-h-screen flex flex-col">

    {{-- Header --}}
    <header class="bg-white shadow-md">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-2xl font-bold text-gray-800">Your App Name</a>
            <div class="flex items-center space-x-4">
                <a href="{{ route('profile.show') }}" class="text-gray-600 hover:text-gray-900 transition duration-200">Back to Profile</a>
                <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900 transition duration-200">Dashboard</a>
            </div>
        </nav>
    </header>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="container mx-auto px-6 pt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mx-auto px-6 pt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    {{-- Main Content --}}
    <main class="container mx-auto px-6 py-8 flex-grow">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-2xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Edit My Profile</h1>

            {{-- Error Messages --}}
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                    <strong class="font-bold">Whoops!</strong>
                    <span class="block sm:inline">Please fix the following errors:</span>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Profile Edit Form --}}
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror">
                    @error('email')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-gray-700 text-sm font-bold mb-2">New Password (leave empty to keep current):</label>
                    <input type="password" id="password" name="password"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror">
                    @error('password')
                        <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Confirm New Password:</label>
                    <input type="password" id="password_confirmation" name="password_confirmation"
                           class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                {{-- Show admin status (read-only for users, they can't change their own admin status) --}}
                <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Current Role:</label>
                    @if($user->is_admin)
                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                            </svg>
                            Administrator
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                            </svg>
                            Regular User
                        </span>
                    @endif
                    <p class="text-sm text-gray-500 mt-2"><em>Note: Contact an administrator to change your role.</em></p>
                </div>

                {{-- Account Information --}}
                <div class="mb-6 p-4 bg-gray-50 rounded-lg border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3 border-b pb-2">Account Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                        <p class="text-gray-700"><strong>Member Since:</strong><br>{{ $user->created_at->format('F j, Y') }}</p>
                        <p class="text-gray-700"><strong>Last Updated:</strong><br>{{ $user->updated_at->format('F j, Y g:i A') }}</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0 sm:space-x-4">
                    <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg focus:outline-none focus:shadow-outline transition duration-200 w-full sm:w-auto">
                        Update Profile
                    </button>
                    <a href="{{ route('profile.show') }}"
                       class="inline-block align-baseline font-bold text-sm text-gray-500 hover:text-gray-800 transition duration-200 w-full sm:w-auto text-center">
                        Cancel
                    </a>
                </div>
            </form>

            {{-- Admin Quick Link --}}
            @if(auth()->user()->is_admin)
                <div class="mt-8 pt-6 border-t border-gray-200 text-center">
                    <div class="bg-blue-50 rounded-lg p-4">
                        <p class="text-blue-700 font-semibold text-lg mb-2">Administrator Access</p>
                        <div class="flex flex-col sm:flex-row justify-center items-center space-y-2 sm:space-y-0 sm:space-x-4">
                            <a href="{{ route('admin.users.index') }}"
                               class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                                Manage Users
                            </a>
                            <a href="{{ route('admin.users.show', auth()->user()) }}"
                               class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200">
                                View My Admin Profile
                            </a>
                        </div>
                    </div>
                </div>
            @endif
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
