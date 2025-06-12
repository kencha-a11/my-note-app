<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite('resources/css/app.css')
</head>
<body>

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
    </form>
    <div>
        <a href="{{ route('login') }}">Already have an account? Login</a>
    </div>
    <div>
        <a href="{{ route('home') }}">Back to Home</a>
    </div>
    <div>
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
    </div>
</body>
</html>
