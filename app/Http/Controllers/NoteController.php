<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function index()
    {
        $notes = Note::where('user_id', Auth::id())->get();
        return Inertia::render('Notes/Index', ['notes' => $notes]);
    }

    public function create()
    {
        return Inertia::render('Notes/Editor', ['note' => null]);
    }

    public function store(Request $request)
	{
		$request->validate([
			'title' => 'required|string|max:255',
			'content' => 'required|string',
		]);

		$request->user()->notes()->create([
			'title' => $request->title,
			'content' => $request->content,
		]);

		// ✅ Redirect instead of JSON
		return redirect()->route('dashboard');
	}
    public function edit(Note $note)
    {
        $this->authorize('update', $note);
        return Inertia::render('Notes/Editor', ['note' => $note]);
    }

    public function update(Request $request, Note $note)
	{
		$this->authorize('update', $note);

		$validated = $request->validate([
			'title' => 'required|string|max:255',
			'content' => 'nullable|string',
		]);

		$note->update($validated);

		// 🧠 If the request expects JSON (auto-save), return JSON
		if ($request->wantsJson() || $request->header('X-Inertia') === 'true') {
			return response()->json(['status' => 'saved']);
		}

		// 🖱️ Manual form save
		return redirect()->route('dashboard');
	}


    public function destroy(Note $note)
	{
		$this->authorize('delete', $note);
		$note->delete();

		return redirect()->route('dashboard');
	}
}
