create.blade.php
<br><a href="{{ route('notes.index') }}">Back to Notes</a><hr>

<form action="{{ route('notes.store') }}" method="POST">
    @csrf
    <label for="title">Title</label>
    <input type="text" name="title" id="title" required>
    <label for="body">Body</label>
    <textarea name="body" id="body" required></textarea>
    <button type="submit">Create Note</button>
</form>
