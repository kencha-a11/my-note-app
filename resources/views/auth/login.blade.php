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
        <form method="POST" action="{{ route('sessions.store') }}">
            @csrf
                <label for="email" >Email</label>
                <input type="email" name="email" id="email" required autofocus >
                <label for="password" >Password</label>
                <input type="password" name="password" id="password" required >
            <button type="submit">Login</button>
        </form>
        <div>
            <a href="{{ route('register') }}">Don't have an account? Register</a>
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
