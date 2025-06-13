show.blade.php
<br><a href="{{ route('notes.index') }}">Back to Notes</a> <br><hr>
<p>Title: {{ $note->title }}</p>
<p>Body: {{ $note->body }}</p>
<a href="{{ route('notes.edit', $note->id) }}">Edit Note</a>
<br>
<form action="{{ route('notes.destroy', $note->id) }}" method="POST" style="display:inline;">
    @csrf
    @method('DELETE')
    <button type="submit">Delete Note</button>
</form> <br><hr>

