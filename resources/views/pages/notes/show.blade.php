show.blade.php
<div>
    <h1>{{ $note->title }}</h1>
    <p>{{ $note->body }}</p>
</div>
<form action="{{ route('notes.destroy', $note->id) }}" method="POST" style="display:inline;">
    @csrf
    @method('DELETE')
    <button type="submit">Delete Note</button>
</form> <br>
<a href="{{ route('notes.index') }}">Back to Notes</a> <br>
<a href="{{ route('notes.edit', $note->id) }}">Edit Note</a>
<br>
<a href="{{ route('dashboard') }}">Return to Dashboard</a>
<br>
