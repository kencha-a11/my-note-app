<br><a href="{{ route('dashboard')}}">return to dashboard</a>
<hr>

@foreach($notes as $note)
    <div>
        <h3>{{ $note->title }}</h3>
        <p>{{ $note->body }}</p>
        <a href="{{ route('notes.show', $note->id) }}">View</a><br>
        <a href="{{ route('notes.edit', $note->id) }}">Edit</a><br>
        <form action="{{ route('notes.destroy', $note->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit">Delete</button>
        </form>
    </div>
    <hr>
@endforeach

@if($notes->isEmpty())
    <p>No notes found. <a href="{{ route('notes.create') }}">Create your first note</a></p>
@endif
