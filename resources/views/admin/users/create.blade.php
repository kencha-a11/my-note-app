{{-- resources/views/admin/users/create.blade.php --}}
<h1>Create New User</h1>

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

    {{-- Create User Form --}}
    <form method="POST" action="{{ route('admin.users.store') }}">
        @csrf

        <div>
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
        </div>

        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div>
            <label for="password_confirmation">Confirm Password:</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>

        {{-- Admin Status Selection --}}
        <div>
            <label>User Role:</label>
            <label>
                <input type="radio" name="is_admin" value="0" {{ old('is_admin', '0') == '0' ? 'checked' : '' }}>
                Regular User
            </label>
            <label>
                <input type="radio" name="is_admin" value="1" {{ old('is_admin') == '1' ? 'checked' : '' }}>
                Administrator
            </label>
        </div>

        <div>
            <button type="submit">Create User</button>
            <a href="{{ route('admin.users.index') }}">Cancel</a>
        </div>
    </form>

    {{-- Help Text --}}
    <div>
        <hr>
        <h3>Notes</h3>
        <ul>
            <li>The new user will receive login credentials via email (if email system is configured)</li>
            <li>Regular users can only manage their own profile</li>
            <li>Administrators can manage all users and access the admin panel</li>
            <li>You can change user roles later from the user management page</li>
        </ul>
    </div>

@else
    {{-- Non-admin shouldn't see this page --}}
    <p>Access denied. Administrator privileges required.</p>
@endif
