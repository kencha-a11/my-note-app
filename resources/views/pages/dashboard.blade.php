this is dashboard

{{-- working --}}
<form action="{{ route('sessions.destroy') }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit">Logout</button>
</form>
<hr>

<a href="{{ route('notes.index') }}">Index Note</a><br>
{{-- <a href="{{ route('notes.show', 1) }}">Show Note</a><br> --}}
<a href="{{ route('notes.create') }}">Create Note</a><br>
{{-- <a href="{{ route('notes.store') }}">Store Note</a><br> --}}
{{-- <a href="{{ route('notes.edit', 1) }}">Edit Note</a><br> --}}
{{-- <a href="{{ route('notes.update', 1) }}">Update Note</a><br> --}}
{{-- <a href="{{ route('notes.destroy', 1) }}">Delete Note</a><br> --}}
