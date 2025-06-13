show.blade.php
<br><a href="{{ route('dashboard') }}">Return dashboard</a>
<hr>
<h1>{{ $user->name }}</h1>
<p>user: {{ $user->name }}</p>
<p>Email: {{ $user->email }}</p>
<p>Joined: {{ $user->created_at->format('d M Y') }}</p>
<br><a href="{{ route('user.edit', Auth::user()->id) }}">Edit User</a>
<form action="{{ route('sessions.destroy') }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit">Logout</button>
</form>

