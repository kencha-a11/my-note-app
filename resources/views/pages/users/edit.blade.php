{{-- resources/views/pages/users/edit.blade.php --}}
<h1>Edit My Profile</h1>

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

{{-- Profile Edit Form --}}
<form method="POST" action="{{ route('profile.update') }}">
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

    {{-- Show admin status (read-only for users, they can't change their own admin status) --}}
    <div>
        <label>Current Role:</label>
        @if($user->is_admin)
            <span><strong>Administrator</strong></span>
        @else
            <span>Regular User</span>
        @endif
        <p><em>Note: Contact an administrator to change your role.</em></p>
    </div>

    <div>
        <button type="submit">Update Profile</button>
        <a href="{{ route('profile.show') }}">Cancel</a>
    </div>
</form>

{{-- Admin Quick Link --}}
@if(auth()->user()->is_admin)
    <div>
        <hr>
        <p><strong>Admin:</strong> <a href="{{ route('admin.users.index') }}">Go to User Management</a></p>
    </div>
@endif
