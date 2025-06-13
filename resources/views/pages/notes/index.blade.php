index.blade.php
<br><a href="{{ route('dashboard')}}">return to dashboard</a>
<br><a href="{{ route('notes.create') }}">Create Note</a>
<hr>

@foreach($notes as $note)
    <div>
        <p>Title: {{ $note->title }}</p>
        <p>Body: {{ $note->body }}</p>
        <a href="{{ route('notes.show', $note->id) }}">View</a><br>
    </div>
    <hr>
@endforeach

@if($notes->isEmpty())
    <p>No notes found. <br><a href="{{ route('notes.create') }}">Create your first note</a></p>
@endif
