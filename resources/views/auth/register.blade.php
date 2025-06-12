
    <form method="POST" action="{{ route('users.store') }}">
        @csrf
        <label for="name">Name</label>
        <input type="text" name="name" id="name" required autofocus>

        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>

        <label for="password_confirmation">Confirm Password</label>
        <input type="password" name="password_confirmation" id="password_confirmation" required>

        <button type="submit">Register</button>
    </form><hr>

        <a href="{{ route('login') }}">Already have an account? Login</a><br>
        <a href="{{ route('home') }}">Back to Home</a>

        @if (session('status'))
            <p>{{ session('status') }}</p>
        @endif
        @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
        @endif

