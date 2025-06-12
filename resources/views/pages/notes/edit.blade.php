edit.blade.php
<form action="{{ route('notes.update', $note->id) }}" method="POST">
    @csrf
    @method('PUT')
    <input type="text" id="title" name="title" value="{{ $note->title }}" required>
    <textarea id="body" name="body">{{ $note->body }}</textarea>
    <button type="submit">Update Note</button>
</form>
