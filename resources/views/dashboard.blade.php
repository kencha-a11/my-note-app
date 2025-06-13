{{-- resources/views/dashboard.blade.php --}}
<h1>Dashboard</h1>

{{-- Welcome Message --}}
<div>
    <h2>Welcome, {{ auth()->user()->name }}!</h2>

    {{-- Show different content based on user role --}}
    @if(auth()->user()->is_admin)
        <p>You are logged in as an <strong>Administrator</strong>.</p>
    @else
        <p>You are logged in as a regular user.</p>
    @endif
</div>

{{-- Quick Actions --}}
<div>
    <h3>Quick Actions</h3>

    {{-- Common User Actions --}}
    <ul>
        <li><a href="{{ route('profile.show') }}">View My Profile</a></li>
        <li><a href="{{ route('profile.edit') }}">Edit My Profile</a></li>
        <li><a href="{{ route('notes.index') }}">List Of Notes</a></li>
    </ul>

    {{-- Admin Only Actions --}}
    @if(auth()->user()->is_admin)
        <h4>Admin Actions</h4>
        <ul>
            <li><a href="{{ route('admin.users.index') }}">Manage All Users</a></li>
            <li><a href="{{ route('admin.users.create') }}">Create New User</a></li>
        </ul>
    @endif
</div>

{{-- Statistics (Admin Only) --}}
@if(auth()->user()->is_admin)
    <div>
        <h3>System Statistics</h3>
        <ul>
            <li>Total Users: {{ \App\Models\User::count() }}</li>
            <li>Total Admins: {{ \App\Models\User::where('is_admin', true)->count() }}</li>
            <li>Regular Users: {{ \App\Models\User::where('is_admin', false)->count() }}</li>
            <li>New Users This Month: {{ \App\Models\User::whereMonth('created_at', now()->month)->count() }}</li>
        </ul>
    </div>
@endif

{{-- Account Information --}}
<div>
    <h3>Account Information</h3>
    <ul>
        <li>Email: {{ auth()->user()->email }}</li>
        <li>Member Since: {{ auth()->user()->created_at->format('F j, Y') }}</li>
        <li>Last Updated: {{ auth()->user()->updated_at->format('F j, Y g:i A') }}</li>

        @if(auth()->user()->is_admin)
            <li>Role: Administrator</li>
        @else
            <li>Role: Regular User</li>
        @endif
    </ul>
</div>

{{-- Recent Activity (Admin Only) --}}
@if(auth()->user()->is_admin)
    <div>
        <h3>Recent Users</h3>
        <table border="1">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined</th>
                </tr>
            </thead>
            <tbody>
                @foreach(\App\Models\User::latest()->take(5)->get() as $recentUser)
                    <tr>
                        <td>{{ $recentUser->name }}</td>
                        <td>{{ $recentUser->email }}</td>
                        <td>
                            @if($recentUser->is_admin)
                                Admin
                            @else
                                User
                            @endif
                        </td>
                        <td>{{ $recentUser->created_at->format('M j, Y') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
