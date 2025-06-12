<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NoteController extends Controller implements HasMiddleware
{
    /**
     * Get the middleware that should be assigned to the controller
     */
    public static function middleware(): array
    {
        return [
            'auth',
        ];
    }

    /**
     * Display a listing of the user's notes
     */
    public function index()
    {
        try {
            $notes = Auth::user()->notes()
                ->orderBy('updated_at', 'desc')
                ->paginate(10);
            // Log the successful retrieval of notes
            Log::info('Notes loaded successfully', [
                'user_id' => Auth::id(),
                'notes_count' => $notes->count(),
            ]);
            // Return the view with notes
            return view('pages.notes.index', compact('notes'));
        } catch (Exception $e) {
            Log::error('Failed to load notes', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to load notes. Please try again.');
        }
    }

    /**
     * Show the form for creating a new note
     */
    public function create()
    {
        return view('pages.notes.create');
    }

    /**
     * Store a newly created note in storage
     */
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'nullable|string',
        ]);

        try {
            Auth::user()->notes()->create([
                'title' => $request->title,
                'body' => $request->body,
            ]);

            return redirect()->route('notes.index')
                ->with('success', 'Note created successfully!');

        } catch (Exception $e) {
            Log::error('Note creation failed', [
                'user_id' => Auth::id(),
                'title' => $request->title,
                'error' => $e->getMessage(),
            ]);

            return back()->withInput()
                ->with('error', 'Failed to create note. Please try again.');
        }
    }

    /**
     * Display the specified note
     */
    public function show(Note $note)
    {
        // Check authorization
        if ($note->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action. This note does not belong to you.');
        }

        return view('pages.notes.show', compact('note'));
    }

    /**
     * Show the form for editing the specified note
     */
    public function edit(Note $note)
    {
        // Check authorization
        if ($note->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action. This note does not belong to you.');
        }

        return view('pages.notes.edit', compact('note'));
    }

    /**
     * Update the specified note in storage
     */
    public function update(Request $request, Note $note)
    {
        // Check authorization
        if ($note->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action. This note does not belong to you.');
        }

        // Validate the request data
        $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'nullable|string',
        ]);

        try {
            $note->update([
                'title' => $request->title,
                'body' => $request->body,
            ]);

            return redirect()->route('notes.index')
                ->with('success', 'Note updated successfully!');

        } catch (Exception $e) {
            Log::error('Note update failed', [
                'user_id' => Auth::id(),
                'note_id' => $note->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withInput()
                ->with('error', 'Failed to update note. Please try again.');
        }
    }

    /**
     * Remove the specified note from storage
     */
    public function destroy(Note $note)
    {
        // Check authorization
        if ($note->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action. This note does not belong to you.');
        }

        try {
            $note->delete();

            return redirect()->route('notes.index')
                ->with('success', 'Note deleted successfully.');

        } catch (Exception $e) {
            Log::error('Note deletion failed', [
                'user_id' => Auth::id(),
                'note_id' => $note->id,
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Failed to delete note. Please try again.');
        }
    }
}
