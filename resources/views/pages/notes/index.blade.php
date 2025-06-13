<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Notes</title>
    @vite('resources/css/app.css') {{-- Link to your compiled Tailwind CSS --}}
</head>
<body class="bg-gray-100 font-sans antialiased">

    {{-- Header --}}
    <header class="bg-white shadow-md">
        <nav class="container mx-auto px-6 py-4 flex justify-between items-center">
            <a href="{{ url('/') }}" class="text-2xl font-bold text-gray-800">Your App Name</a>
            <div class="flex items-center space-x-4">
                <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-gray-900">Return to Dashboard</a>
                <a href="{{ route('notes.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition duration-200">Create New Note</a>
            </div>
        </nav>
    </header>

    {{-- Main Content --}}
    <main class="container mx-auto px-6 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">My Notes</h1>

        @if($notes->isEmpty())
            <div class="bg-white p-8 rounded-lg shadow-lg text-center max-w-lg mx-auto">
                <p class="text-xl text-gray-700 mb-4">No notes found.</p>
                <a href="{{ route('notes.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-full text-lg transition duration-200">
                    Create your first note
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($notes as $note)
                    <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200 hover:shadow-lg transition duration-200 flex flex-col justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-800 mb-2">{{ $note->title }}</h2>
                            <p class="text-gray-600 mb-4 line-clamp-3">{{ Str::limit($note->body, 150) }}</p> {{-- Added Str::limit for preview --}}
                        </div>
                        <div class="mt-4 flex justify-end">
                            <a href="{{ route('notes.show', $note->id) }}" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-lg text-sm transition duration-200">
                                View Note
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Optional: Add pagination if you have many notes --}}
            {{-- <div class="mt-8">
                {{ $notes->links() }}
            </div> --}}
        @endif
    </main>

    {{-- Footer --}}
    <footer class="bg-white shadow-md mt-8">
        <div class="container mx-auto px-6 py-4 text-center text-gray-600">
            &copy; {{ date('Y') }} Your App Name. All rights reserved.
        </div>
    </footer>

</body>
</html>
