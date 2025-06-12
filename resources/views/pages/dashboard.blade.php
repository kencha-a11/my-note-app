this is dashboard

{{-- working --}}
<form action="{{ route('sessions.destroy') }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit">Logout</button>
</form>
<hr>
