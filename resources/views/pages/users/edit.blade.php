edit.blade.php
<br><a href="{{ route('user.show', Auth::user()->id) }}">Back to Users</a><hr>

<form action="{{ route('user.update', Auth::user()->id) }}" method="POST">
    @csrf
    @method('PUT')
    <label for="name">Name</label>
    <input type="text" name="name" id="name" value="{{ Auth::user()->name }}" required>
    <label for="email">Email</label>
    <input type="email" name="email" id="email" value="{{ Auth::user()->email }}" required>
    <button type="submit">Update User</button>
</form>
