{{-- resources/views/pages/users/show.blade.php --}}
show.blade.php
<h1>My Profile</h1>
<br><a href="{{ route('dashboard') }}">Return to dashboard</a><hr>

{{-- Success/Error Messages --}}
@if(session('success'))
    <div>{{ session('success') }}</div>
@endif

@if(session('error'))
    <div>{{ session('error') }}</div>
@endif

{{-- Profile Information --}}
<div>
    <h2>Profile Information</h2>

    <p><strong>Name:</strong> {{ $user->name }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Member Since:</strong> {{ $user->created_at->format('M d, Y') }}</p>

    {{-- Show admin status if user is admin --}}
    @if($user->is_admin)
        <p><strong>Role:</strong> Administrator</p>
    @else
        <p><strong>Role:</strong> User</p>
    @endif
</div>

{{-- Action Buttons --}}
<div>
    <a href="{{ route('profile.edit') }}">Edit Profile</a>

    {{-- Admin can access admin panel --}}
    @if(auth()->user()->is_admin)
        <a href="{{ route('admin.users.index') }}">Admin Panel</a>
    @endif

    {{-- Delete Account (with confirmation) --}}
    <form method="POST" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.')">
        @csrf
        @method('DELETE')
        <button type="submit">Delete Account</button>
    </form>
</div>
