<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $note->title }}</title>
    @vite('resources/css/app.css') {{-- Link to your compiled Tailwind CSS --}}
</head>
<body class="bg-gray-100 font-sans antialiased">

    {{-- Header --}}
    <header class="bg-white shadow-md">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-2xl font-bold text-gray-800">Your App Name</a>
            <a href="{{ route('notes.index') }}" class="text-gray-600 hover:text-gray-900">Back to Notes</a>
        </nav>
    </header>

    {{-- Main Content --}}
    <main class="container mx-auto px-6 py-8">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-3xl mx-auto">
            <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">{{ $note->title }}</h1>

            <div class="mb-8">
                <p class="text-lg text-gray-700 leading-relaxed whitespace-pre-wrap">{{ $note->body }}</p>
            </div>

            <hr class="my-6 border-gray-300">

            <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('notes.edit', $note->id) }}"
                   class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200 w-full sm:w-auto text-center">
                    Edit Note
                </a>

                <form action="{{ route('notes.destroy', $note->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this note?')" class="w-full sm:w-auto">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200 w-full">
                        Delete Note
                    </button>
                </form>
            </div>
        </div>
    </main>

    {{-- Footer --}}
    <footer class="bg-white shadow-md mt-8">
        <div class="container mx-auto px-6 py-4 text-center text-gray-600">
            &copy; {{ date('Y') }} Your App Name. All rights reserved.
        </div>
    </footer>

</body>
</html>
