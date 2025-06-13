{{-- resources/views/admin/users/index.blade.php --}}
index.blade.php
<h1>User Management</h1>
<br><a href="{{ route('dashboard') }}">Return to dashboard</a><hr>


{{-- Admin Only Content --}}
@if(auth()->user()->is_admin)

    {{-- Success/Error Messages --}}
    @if(session('success'))
        <div>{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div>{{ session('error') }}</div>
    @endif

    {{-- Actions --}}
    <div>
        <a href="{{ route('admin.users.create') }}">Create New User</a>
        <a href="{{ route('profile.show') }}">Back to My Profile</a>
    </div>

    {{-- Users Table --}}
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->is_admin)
                            <strong>Admin</strong>
                        @else
                            User
                        @endif
                    </td>
                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('admin.users.show', $user) }}">View</a>
                        <a href="{{ route('admin.users.edit', $user) }}">Edit</a>

                        {{-- Toggle Admin Status (except for current user) --}}
                        @if(auth()->id() !== $user->id)
                            <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}" style="display:inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit">
                                    @if($user->is_admin)
                                        Remove Admin
                                    @else
                                        Make Admin
                                    @endif
                                </button>
                            </form>

                            {{-- Delete User (except current user) --}}
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display:inline" onsubmit="return confirm('Are you sure you want to delete this user?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Delete</button>
                            </form>
                        @else
                            <em>(You)</em>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">No users found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- Pagination --}}
    {{ $users->links() }}

@else
    {{-- Non-admin users shouldn't see this page --}}
    <p>Access denied. Administrator privileges required.</p>
@endif
