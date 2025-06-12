<form method="POST" action="{{ route('sessions.store') }}">
    @csrf
        <label for="email" >Email</label>
        <input type="email" name="email" id="email" required autofocus >
        <label for="password" >Password</label>
        <input type="password" name="password" id="password" required >
    <button type="submit">Login</button>
</form>

<hr>
<a href="{{ route('register') }}">Don't have an account? Register</a> <br>
<a href="{{ route('home') }}">Back to Home</a>

@if (session('status'))
    <p>{{ session('status') }}</p>
@endif
    @if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif


