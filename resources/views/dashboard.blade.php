<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    @vite('resources/css/app.css') {{-- Link to your compiled Tailwind CSS --}}
</head>
<body class="bg-gray-100 font-sans antialiased">

    {{-- Header --}}
    <header class="bg-white shadow-md">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-2xl font-bold text-gray-800">Your App Name</a>
            <div>
                {{-- <span class="text-gray-600 mx-2">Welcome, {{ auth()->user()->name }}!</span> --}}
                {{-- <form action="{{ route('sessions.destroy') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-full ml-4">Logout</button>
                </form> --}}
            </div>
        </nav>
    </header>

    {{-- Main Content --}}
    <main class="container mx-auto px-6 py-8">
        <h1 class="text-4xl font-bold text-gray-800 mb-6">Dashboard</h1>

        {{-- Welcome Message --}}
        <div class="bg-white p-6 rounded-lg shadow-md mb-8">
            <h2 class="text-2xl font-semibold text-gray-700 mb-2">Welcome, {{ auth()->user()->name }}!</h2>
            @if(auth()->user()->is_admin)
                <p class="text-lg text-blue-600">You are logged in as an <strong>Administrator</strong>.</p>
            @else
                <p class="text-lg text-gray-600">You are logged in as a regular user.</p>
            @endif
        </div>

        {{-- Quick Actions --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold text-gray-700 mb-4">Quick Actions</h3>
                <ul class="space-y-2">
                    <li><a href="{{ route('profile.show') }}" class="text-blue-500 hover:text-blue-700 font-medium">View My Profile</a></li>
                    {{-- <li><a href="{{ route('profile.edit') }}" class="text-blue-500 hover:text-blue-700 font-medium">Edit My Profile</a></li> --}}
                    <li><a href="{{ route('notes.index') }}" class="text-blue-500 hover:text-blue-700 font-medium">List Of Notes</a></li>
                </ul>
            </div>

            {{-- Admin Only Actions --}}
            @if(auth()->user()->is_admin)
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">Admin Actions</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('admin.users.index') }}" class="text-purple-600 hover:text-purple-800 font-medium">Manage All Users</a></li>
                        <li><a href="{{ route('admin.users.create') }}" class="text-purple-600 hover:text-purple-800 font-medium">Create New User</a></li>
                    </ul>
                </div>
            @endif

            {{-- System Statistics (Admin Only) --}}
            @if(auth()->user()->is_admin)
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">System Statistics</h3>
                    <ul class="space-y-2 text-gray-600">
                        <li>Total Users: <strong class="text-gray-800">{{ \App\Models\User::count() }}</strong></li>
                        <li>Total Admins: <strong class="text-gray-800">{{ \App\Models\User::where('is_admin', true)->count() }}</strong></li>
                        <li>Regular Users: <strong class="text-gray-800">{{ \App\Models\User::where('is_admin', false)->count() }}</strong></li>
                        <li>New Users This Month: <strong class="text-gray-800">{{ \App\Models\User::whereMonth('created_at', now()->month)->count() }}</strong></li>
                    </ul>
                </div>
            @endif
        </div>

        {{-- Account Information & Recent Activity --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- Account Information --}}
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-semibold text-gray-700 mb-4">Account Information</h3>
                <ul class="space-y-2 text-gray-600">
                    <li>Email: <strong class="text-gray-800">{{ auth()->user()->email }}</strong></li>
                    <li>Member Since: <strong class="text-gray-800">{{ auth()->user()->created_at->format('F j, Y') }}</strong></li>
                    <li>Last Updated: <strong class="text-gray-800">{{ auth()->user()->updated_at->format('F j, Y g:i A') }}</strong></li>
                    <li>Role:
                        @if(auth()->user()->is_admin)
                            <strong class="text-blue-600">Administrator</strong>
                        @else
                            <strong class="text-gray-800">Regular User</strong>
                        @endif
                    </li>
                </ul>
            </div>

            {{-- Recent Activity (Admin Only) --}}
            @if(auth()->user()->is_admin)
                <div class="bg-white p-6 rounded-lg shadow-md overflow-x-auto">
                    <h3 class="text-xl font-semibold text-gray-700 mb-4">Recent Users</h3>
                    <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 text-gray-700 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left">Name</th>
                                <th class="py-3 px-6 text-left">Email</th>
                                <th class="py-3 px-6 text-left">Role</th>
                                <th class="py-3 px-6 text-left">Joined</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            @foreach(\App\Models\User::latest()->take(5)->get() as $recentUser)
                                <tr class="border-b border-gray-200 hover:bg-gray-100">
                                    <td class="py-3 px-6 text-left whitespace-nowrap">{{ $recentUser->name }}</td>
                                    <td class="py-3 px-6 text-left">{{ $recentUser->email }}</td>
                                    <td class="py-3 px-6 text-left">
                                        @if($recentUser->is_admin)
                                            <span class="bg-blue-200 text-blue-800 py-1 px-3 rounded-full text-xs">Admin</span>
                                        @else
                                            <span class="bg-gray-200 text-gray-800 py-1 px-3 rounded-full text-xs">User</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-6 text-left">{{ $recentUser->created_at->format('M j, Y') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </main>

    {{-- Footer --}}
    <footer class="bg-white shadow-md mt-8">
        <div class="container mx-auto px-6 py-4 text-center text-gray-600">
            &copy; {{ date('Y') }} Your App Name. All rights reserved.
        </div>
    </footer>

</body>
</html>
