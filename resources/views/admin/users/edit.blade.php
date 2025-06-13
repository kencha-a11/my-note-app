{{-- resources/views/admin/users/edit.blade.php --}}
<h1>Edit User: {{ $user->name }}</h1>

{{-- Admin Only Content --}}
@if(auth()->user()->is_admin)

    {{-- Error Messages --}}
    @if($errors->any())
        <div>
            <h4>Please fix the following errors:</h4>
            <ul>
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

        <div>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
        </div>

        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>

        <div>
            <label for="password">New Password (leave empty to keep current):</label>
            <input type="password" id="password" name="password">
        </div>

        <div>
            <label for="password_confirmation">Confirm New Password:</label>
            <input type="password" id="password_confirmation" name="password_confirmation">
        </div>

        {{-- Admin Status (Admin can modify, but not their own) --}}
        <div>
            <label>Admin Status:</label>
            @if(auth()->id() === $user->id)
                {{-- Current admin editing themselves --}}
                <input type="hidden" name="is_admin" value="1">
                <span><strong>Administrator</strong> (You cannot change your own admin status)</span>
            @else
                {{-- Admin editing another user --}}
                <label>
                    <input type="radio" name="is_admin" value="0" {{ !$user->is_admin ? 'checked' : '' }}>
                    Regular User
                </label>
                <label>
                    <input type="radio" name="is_admin" value="1" {{ $user->is_admin ? 'checked' : '' }}>
                    Administrator
                </label>
            @endif
        </div>

        {{-- Account Information --}}
        <div>
            <h3>Account Information</h3>
            <p><strong>Created:</strong> {{ $user->created_at->format('F j, Y g:i A') }}</p>
            <p><strong>Last Updated:</strong> {{ $user->updated_at->format('F j, Y g:i A') }}</p>
            <p><strong>User ID:</strong> {{ $user->id }}</p>
        </div>

        <div>
            <button type="submit">Update User</button>
            <a href="{{ route('admin.users.show', $user) }}">Cancel</a>
            <a href="{{ route('admin.users.index') }}">Back to Users List</a>
        </div>
    </form>

    {{-- Danger Zone (if not editing self) --}}
    @if(auth()->id() !== $user->id)
        <div>
            <hr>
            <h3>Danger Zone</h3>

            {{-- Toggle Admin Status --}}
            <form method="POST" action="{{ route('admin.users.toggle-admin', $user) }}" style="display:inline">
                @csrf
                @method('PATCH')
                <button type="submit" onclick="return confirm('Are you sure you want to change this user\'s admin status?')">
                    @if($user->is_admin)
                        Remove Admin Privileges
                    @else
                        Grant Admin Privileges
                    @endif
                </button>
            </form>

            {{-- Delete User --}}
            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display:inline">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                    Delete User
                </button>
            </form>
        </div>
    @endif

@else
    {{-- Non-admin shouldn't see this page --}}
    <p>Access denied. Administrator privileges required.</p>
@endif
