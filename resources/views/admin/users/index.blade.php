<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Admin</title>
    @vite('resources/css/app.css') {{-- Link to your compiled Tailwind CSS --}}
</head>
<body class="bg-gray-100 font-sans antialiased min-h-screen flex flex-col">

    {{-- Header --}}
    <header class="bg-white shadow-md">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-2xl font-bold text-gray-800">Your App Name</a>
            <div class="flex items-center space-x-4">
                <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900 transition duration-200">Return to Dashboard</a>
                {{-- <a href="{{ route('profile.show') }}" class="text-gray-600 hover:text-gray-900 transition duration-200">Back to My Profile</a> --}}
            </div>
        </nav>
    </header>

    {{-- Main Content --}}
    <main class="container mx-auto px-6 py-8 flex-grow">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-6xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">User Management</h1>

            {{-- Admin Only Content --}}
            @if(auth()->user()->is_admin)

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

                {{-- Actions --}}
                <div class="mb-6 flex justify-end">
                    <a href="{{ route('admin.users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-200">
                        Create New User
                    </a>
                </div>

                {{-- Users Table --}}
                <div class="overflow-x-auto"> {{-- Allows table to scroll horizontally on small screens --}}
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden">
                        <thead class="bg-gray-200 text-gray-700 uppercase text-sm leading-normal">
                            <tr>
                                <th class="py-3 px-6 text-left">ID</th>
                                <th class="py-3 px-6 text-left">Name</th>
                                <th class="py-3 px-6 text-left">Email</th>
                                <th class="py-3 px-6 text-left">Role</th>
                                <th class="py-3 px-6 text-left">Created</th>
                                <th class="py-3 px-6 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            @forelse($users as $user)
                                <tr class="border-b border-gray-200 hover:bg-gray-100">
                                    <td class="py-3 px-6 text-left whitespace-nowrap">{{ $user->id }}</td>
                                    <td class="py-3 px-6 text-left">{{ $user->name }}</td>
                                    <td class="py-3 px-6 text-left">{{ $user->email }}</td>
                                    <td class="py-3 px-6 text-left">
                                        @if($user->is_admin)
                                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">Admin</span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">User</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-6 text-left">{{ $user->created_at->format('M d, Y') }}</td>
                                    <td class="py-3 px-6 text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ route('admin.users.show', $user->id) }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-1 px-3 rounded-md text-xs transition duration-200">View</a>
                                            {{-- <a href="{{ route('admin.users.edit', $user->id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-1 px-3 rounded-md text-xs transition duration-200">Edit</a> --}}

                                            @if(auth()->id() !== $user->id)
                                                {{-- Toggle Admin Status --}}
                                                {{-- <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}" class="inline-block">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="
                                                        {{ $user->is_admin ? 'bg-red-500 hover:bg-red-600' : 'bg-purple-600 hover:bg-purple-700' }}
                                                        text-white font-bold py-1 px-3 rounded-md text-xs transition duration-200">
                                                        {{ $user->is_admin ? 'Remove Admin' : 'Make Admin' }}
                                                    </button>
                                                </form> --}}

                                                {{-- Delete User --}}
                                                {{-- <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-1 px-3 rounded-md text-xs transition duration-200">
                                                        Delete
                                                    </button>
                                                </form> --}}
                                            @else
                                                <span class="text-gray-400 text-xs italic">(You)</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-6 px-6 text-center text-gray-500 text-lg">No users found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $users->links() }} {{-- Laravel's default pagination for Tailwind --}}
                </div>

            @else
                {{-- Non-admin users shouldn't see this page --}}
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
