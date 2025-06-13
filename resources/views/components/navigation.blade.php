{{-- resources/views/components/navigation.blade.php --}}
navigation.blade.php
<nav>
    <ul>
        <li><a href="{{ route('home') }}">Home</a></li>

        @guest
            <li><a href="{{ route('login') }}">Login</a></li>
            <li><a href="{{ route('register') }}">Register</a></li>
        @endguest

        @auth
            <li><a href="{{ route('dashboard') }}">Dashboard</a></li>

            {{-- Regular User Links --}}
            <li><a href="{{ route('profile.show') }}">My Profile</a></li>
            <li><a href="{{ route('profile.edit') }}">Edit Profile</a></li>

            {{-- Admin Only Links --}}
            @if(auth()->user()->is_admin)
                <li><strong>Admin Panel:</strong></li>
                <li><a href="{{ route('admin.users.index') }}">Manage Users</a></li>
                <li><a href="{{ route('admin.users.create') }}">Create User</a></li>
            @endif

            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </li>
        @endauth
    </ul>
</nav>
