<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User: {{ $user->name }} - Admin</title>
    @vite('resources/css/app.css')
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
            <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Edit User: {{ $user->name }}</h1>

            {{-- Admin Only Content --}}
            @if(auth()->user()->is_admin)

                {{-- Check if the admin is trying to edit their own account OR another admin's account --}}
                @if(auth()->id() === $user->id || $user->is_admin)
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative text-center text-lg mb-6" role="alert">
                        @if(auth()->id() === $user->id)
                            <strong class="font-bold">Cannot Edit Self!</strong>
                            <span class="block sm:inline">You cannot edit or delete your own administrator account through this panel. Please manage your own profile via the <a href="{{ route('profile.show') }}" class="text-yellow-800 hover:text-yellow-900 underline font-medium">My Profile</a> page.</span>
                        @else {{-- $user->is_admin is true and it's not the current admin --}}
                            <strong class="font-bold">Admin Privileges!</strong>
                            <span class="block sm:inline">You cannot edit or delete another administrator's account. This action is restricted to prevent accidental or unauthorized changes to admin accounts.</span>
                        @endif
                    </div>

                    {{-- Display read-only profile information --}}
                    <div class="mb-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
                        <h3 class="text-xl font-semibold text-gray-800 mb-3 border-b pb-2">User Information (Read-only)</h3>
                        <p class="mb-2 text-gray-700"><strong>Name:</strong> {{ $user->name }}</p>
                        <p class="mb-2 text-gray-700"><strong>Email:</strong> {{ $user->email }}</p>
                        <p class="mb-2 text-gray-700"><strong>Role:</strong>
                            @if($user->is_admin)
                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">Administrator</span>
                            @else
                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-gray-100 text-gray-800">Regular User</span>
                            @endif
                        </p>
                        <p class="mb-2 text-gray-700"><strong>Created:</strong> {{ $user->created_at->format('F j, Y g:i A') }}</p>
                        <p class="mb-2 text-gray-700"><strong>Last Updated:</strong> {{ $user->updated_at->format('F j, Y g:i A') }}</p>
                        <p class="text-gray-700"><strong>User ID:</strong> <span class="font-mono text-sm bg-gray-200 px-2 py-1 rounded">{{ $user->id }}</span></p>
                    </div>

                    <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-4">
                        @if(auth()->id() === $user->id)
                            <a href="{{ route('profile.edit') }}"
                               class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200 w-full sm:w-auto text-center">
                                Go to My Profile Edit
                            </a>
                        @endif
                        <a href="{{ route('admin.users.index') }}"
                           class="inline-block align-baseline font-bold text-sm text-gray-500 hover:text-gray-800 transition duration-200 w-full sm:w-auto text-center">
                            Back to Users List
                        </a>
                    </div>
                @else
                    {{-- Admin editing a non-admin user --}}

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

                    {{-- User Edit Form (Admin Version) --}}
                    <form method="POST" action="{{ route('admin.users.update', $user) }}">
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

                        {{-- Admin Status (Admin can modify other users' roles) --}}
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Admin Status:</label>
                            <div class="flex items-center space-x-4">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="is_admin" value="0" {{ old('is_admin', $user->is_admin) == '0' ? 'checked' : '' }} class="form-radio text-blue-600 h-4 w-4">
                                    <span class="ml-2 text-gray-700">Regular User</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="is_admin" value="1" {{ old('is_admin', $user->is_admin) == '1' ? 'checked' : '' }} class="form-radio text-blue-600 h-4 w-4">
                                    <span class="ml-2 text-gray-700">Administrator</span>
                                </label>
                            </div>
                            @error('is_admin')
                                <p class="text-red-500 text-xs italic mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Account Information (Read-only) --}}
                        <div class="mb-8 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <h3 class="text-xl font-semibold text-gray-800 mb-3 border-b pb-2">Account Information</h3>
                            <p class="mb-2 text-gray-700"><strong>Created:</strong> {{ $user->created_at->format('F j, Y g:i A') }}</p>
                            <p class="mb-2 text-gray-700"><strong>Last Updated:</strong> {{ $user->updated_at->format('F j, Y g:i A') }}</p>
                            <p class="text-gray-700"><strong>User ID:</strong> <span class="font-mono text-sm bg-gray-200 px-2 py-1 rounded">{{ $user->id }}</span></p>
                        </div>

                        <div class="flex flex-col sm:flex-row justify-between items-center space-y-4 sm:space-y-0 sm:space-x-4">
                            <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline transition duration-200 w-full sm:w-auto text-center">
                                Update User
                            </button>
                            <a href="{{ route('admin.users.show', $user) }}"
                               class="inline-block align-baseline font-bold text-sm text-gray-500 hover:text-gray-800 transition duration-200 w-full sm:w-auto text-center">
                                Cancel
                            </a>
                            <a href="{{ route('admin.users.index') }}"
                               class="inline-block align-baseline font-bold text-sm text-gray-500 hover:text-gray-800 transition duration-200 w-full sm:w-auto text-center">
                                Back to Users List
                            </a>
                        </div>
                    </form>

                    {{-- Danger Zone (for non-admin users) --}}
                    <div class="mt-8 pt-6 border-t border-red-200">
                        <h3 class="text-2xl font-bold text-red-700 mb-4">Danger Zone</h3>

                        <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                            {{-- Toggle Admin Status --}}
                            <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}"
                                    onsubmit="return confirm('Are you sure you want to {{ $user->is_admin ? 'remove admin privileges from' : 'grant admin privileges to' }} {{ $user->name }}?')"
                                    class="w-full sm:w-auto">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="
                                    {{ $user->is_admin ? 'bg-orange-500 hover:bg-orange-600' : 'bg-purple-600 hover:bg-purple-700' }}
                                    text-white font-bold py-2 px-4 rounded-lg transition duration-200 w-full">
                                    {{ $user->is_admin ? 'Remove Admin Privileges' : 'Grant Admin Privileges' }}
                                </button>
                            </form>

                            {{-- Delete User --}}
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                    onsubmit="return confirm('Are you sure you want to delete {{ $user->name }}? This action cannot be undone.')"
                                    class="w-full sm:w-auto">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg transition duration-200 w-full">
                                    Delete User
                                </button>
                            </form>
                        </div>
                    </div>
                @endif {{-- End of @if(auth()->id() === $user->id || $user->is_admin) --}}

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
