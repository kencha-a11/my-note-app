<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New User - Admin</title>
    @vite('resources/css/app.css') {{-- Link to your compiled Tailwind CSS --}}
</head>
<body class="bg-gray-100 font-sans antialiased min-h-screen flex flex-col">

    {{-- Header --}}
    <header class="bg-white shadow-md">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-2xl font-bold text-gray-800">Your App Name</a>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.users.index') }}" class="text-gray-600 hover:text-gray-900 transition duration-200">Back to Users List</a>
                <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900 transition duration-200">Return to Dashboard</a>
            </div>
        </nav>
    </header>

    {{-- Main Content --}}
    <main class="container mx-auto px-6 py-8 flex-grow">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-2xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Create New User</h1>

            {{-- Admin Only Content --}}
            @if(auth()->user()->is_admin)

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

                {{-- Create User Form --}}
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name:</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('name') border-red-500 @enderror">
                        @error('name')
                            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror">
                        @error('email')
                            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password:</label>
                        <input type="password" id="password" name="password" required
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror">
                        @error('password')
                            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Confirm Password:</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>

                    {{-- Admin Status Selection --}}
                    <div class="mb-6">
                        <label class="block text-gray-700 text-sm font-bold mb-2">User Role:</label>
                        <div class="flex items-center space-x-4">
                            <label class="inline-flex items-center">
                                <input type="radio" name="is_admin" value="0" {{ old('is_admin', '0') == '0' ? 'checked' : '' }} class="form-radio text-blue-600 h-4 w-4">
                                <span class="ml-2 text-gray-700">Regular User</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="is_admin" value="1" {{ old('is_admin') == '1' ? 'checked' : '' }} class="form-radio text-blue-600 h-4 w-4">
                                <span class="ml-2 text-gray-700">Administrator</span>
                            </label>
                        </div>
                        @error('is_admin')
                            <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-200">
                            Create User
                        </button>
                        <a href="{{ route('admin.users.index') }}"
                           class="inline-block align-baseline font-bold text-sm text-gray-500 hover:text-gray-800 transition duration-200">
                            Cancel
                        </a>
                    </div>
                </form>

                {{-- Help Text --}}
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Important Notes</h3>
                    <ul class="list-disc list-inside text-gray-600 space-y-2">
                        <li>The new user will receive login credentials via email (if your application's email system is configured).</li>
                        <li>Regular users can only manage their own profile and access non-admin features.</li>
                        <li>Administrators can manage all users and access the full admin panel.</li>
                        <li>You can change user roles later from the <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:underline">User Management page</a>.</li>
                    </ul>
                </div>

            @else
                {{-- Non-admin shouldn't see this page --}}
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative text-center text-lg mt-8" role="alert">
                    <strong class="font-bold">Access Denied!</strong>
                    <span class="block sm:inline">You do not have administrator privileges to view this page.</span>
                    <p class="mt-2"><a href="{{ route('dashboard') }}" class="text-blue-700 hover:text-blue-900 underline">Return to Dashboard</a></p>
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
