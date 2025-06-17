<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\Auth\GoogleController;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Redirect to Google if not authenticated
Route::get('/login', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return redirect()->route('auth.google');
})->name('login');

// Google OAuth
Route::get('auth/google', [GoogleController::class, 'redirect'])->name('auth.google');
Route::get('auth/callback', [GoogleController::class, 'callback']);

// Redirect base URL to dashboard
Route::get('/', fn () => redirect('/dashboard'));


/*
|--------------------------------------------------------------------------
| Protected Routes (Auth Required)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {
    
    // Dashboard / Notes List
    Route::get('/dashboard', [NoteController::class, 'index'])->name('dashboard');

    // Notes CRUD
    Route::get('/notes/create', [NoteController::class, 'create'])->name('notes.create');
    Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');
    Route::get('/notes/{note}/edit', [NoteController::class, 'edit'])->name('notes.edit');
    Route::put('/notes/{note}', [NoteController::class, 'update'])->name('notes.update');
    Route::delete('/notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');

    // AI-enhance feature
    Route::post('/api/notes/enhance', function (Request $request) {
        $openAiKey = env('OPENAI_API_KEY');
        $content = $request->input('content');

        $response = Http::withToken($openAiKey)->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4.1-nano-2025-04-14',
            'messages' => [
                ['role' => 'system', 'content' => 'You are a helpful assistant that summarizes notes.'],
                ['role' => 'user', 'content' => "Summarize this note:\n\n$content"]
            ],
            'temperature' => 0.7,
        ]);

        return response()->json([
            'result' => $response->json()['choices'][0]['message']['content'] ?? 'AI response failed.',
        ]);
    })->name('notes.enhance');
});
