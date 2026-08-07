<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\GameSession;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class SessionQuestionController extends Controller
{
    /**
     * Show the form for creating a new custom question.
     */
    public function create(GameSession $session)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($session->user_id !== $user->id) {
            abort(403);
        }

        return view('guru.questions.create', compact('session'));
    }

    /**
     * Store a newly created custom question in storage.
     */
    public function store(Request $request, GameSession $session)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($session->user_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'nama_sampah' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'in:organik,anorganik,b3'],
            'gambar' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'fakta_edukasi' => ['nullable', 'string'],
        ]);

        $data = [
            'nama_sampah' => $request->nama_sampah,
            'kategori' => $request->kategori,
            'fakta_edukasi' => $request->fakta_edukasi,
            'is_default' => false,
            'user_id' => $user->id,
        ];

        if ($request->hasFile('gambar')) {
            $file = $request->file('gambar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            if (!File::exists(public_path('images/sampah'))) {
                File::makeDirectory(public_path('images/sampah'), 0755, true);
            }

            $file->move(public_path('images/sampah'), $filename);
            $data['gambar'] = 'images/sampah/' . $filename;
        }

        $question = Question::create($data);

        // Hubungkan ke sesi game
        $session->questions()->attach($question->id, [
            'wrong_count' => 0,
            'total_count' => 0,
        ]);

        return redirect()->route('guru.sessions.show', $session)->with('success', 'Soal kustom berhasil dibuat.');
    }

    /**
     * Show the form for editing the specified custom question.
     */
    public function edit(Question $question)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Hanya bisa edit jika ini soal buatan guru itu sendiri
        if ($question->user_id !== $user->id) {
            abort(403, 'Anda tidak diizinkan mengubah soal default admin.');
        }

        // Ambil session_id rujukan dari parameter untuk tombol kembali
        $sessionId = request()->query('session_id');

        return view('guru.questions.edit', compact('question', 'sessionId'));
    }

    /**
     * Update the specified custom question in storage.
     */
    public function update(Request $request, Question $question)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($question->user_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'nama_sampah' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'in:organik,anorganik,b3'],
            'gambar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'fakta_edukasi' => ['nullable', 'string'],
        ]);

        $data = [
            'nama_sampah' => $request->nama_sampah,
            'kategori' => $request->kategori,
            'fakta_edukasi' => $request->fakta_edukasi,
        ];

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada
            if ($question->gambar && File::exists(public_path($question->gambar))) {
                // File::delete(public_path($question->gambar));
            }

            $file = $request->file('gambar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/sampah'), $filename);
            $data['gambar'] = 'images/sampah/' . $filename;
        }

        $question->update($data);

        $sessionId = $request->input('session_id');
        if ($sessionId) {
            return redirect()->route('guru.sessions.show', $sessionId)->with('success', 'Soal kustom berhasil diperbarui.');
        }

        return redirect()->route('guru.dashboard')->with('success', 'Soal kustom berhasil diperbarui.');
    }

    /**
     * Remove the specified question from a game session (or delete permanently if custom and requested).
     */
    public function destroy(Request $request, Question $question)
    {
        $request->validate([
            'session_id' => ['required', 'exists:game_sessions,id'],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();

        $session = GameSession::findOrFail($request->session_id);

        if ($session->user_id !== $user->id) {
            abort(403);
        }

        // Lepas hubungan (detach) dari sesi
        $session->questions()->detach($question->id);

        // Jika ini soal kustom milik guru tersebut dan sudah tidak terhubung ke sesi manapun,
        // kita bisa menghapusnya secara permanen dari database
        if ($question->user_id === $user->id && $question->gameSessions()->count() === 0) {
            if ($question->gambar && File::exists(public_path($question->gambar))) {
                File::delete(public_path($question->gambar));
            }
            $question->delete();
        }

        return redirect()->route('guru.sessions.show', $session)->with('success', 'Soal berhasil dihapus dari sesi kelas ini.');
    }
}
