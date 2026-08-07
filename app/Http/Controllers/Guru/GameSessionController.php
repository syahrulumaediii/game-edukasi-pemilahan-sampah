<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\GameSession;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class GameSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $sessions = GameSession::where('user_id', $user->id)
            ->withCount('questions', 'gameScores')
            ->latest()
            ->paginate(10);

        return view('guru.sessions.index', compact('sessions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('guru.sessions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'game_mode' => ['required', 'in:quizizz,belajar,duel'],
        ]);

        // Generate kode game unik (6 karakter huruf/angka acak agar mudah diketik anak SD)
        do {
            $gameCode = strtoupper(Str::random(6));
        } while (GameSession::where('game_code', $gameCode)->exists());

        $session = GameSession::create([
            'user_id' => $user->id,
            'title' => $request->title,
            'game_code' => $gameCode,
            'is_active' => true,
            'game_mode' => $request->game_mode,
            'is_started' => false,
        ]);

        // Secara default, otomatis impor bank soal default admin saat membuat sesi baru agar guru siap pakai
        $defaultQuestions = Question::where('is_default', true)->get();
        foreach ($defaultQuestions as $question) {
            $session->questions()->attach($question->id, [
                'wrong_count' => 0,
                'total_count' => 0,
            ]);
        }

        return redirect()->route('guru.sessions.show', $session)
            ->with('success', 'Sesi permainan baru berhasil dibuat dan soal default otomatis diimpor.');
    }

    /**
     * Display the specified resource.
     */
    public function show(GameSession $session)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($session->user_id !== $user->id) {
            abort(403);
        }

        $session->load(['questions' => function ($q) {
            $q->withPivot('wrong_count', 'total_count');
        }, 'gameScores' => function ($q) {
            $q->latest();
        }]);

        // Mengambil statistik miskonsepsi untuk grafik/tabel (FR-G07)
        // Ambil soal yang memiliki total_count > 0, urutkan berdasarkan persentase salah tertinggi
        $misconceptions = $session->questions()
            ->wherePivot('total_count', '>', 0)
            ->get()
            ->map(function ($question) {
                $wrong = $question->pivot->wrong_count;
                $total = $question->pivot->total_count;
                $percentage = $total > 0 ? round(($wrong / $total) * 100) : 0;
                $question->wrong_percentage = $percentage;
                return $question;
            })
            ->sortByDesc('wrong_percentage')
            ->take(5);

        return view('guru.sessions.show', compact('session', 'misconceptions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GameSession $session)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($session->user_id !== $user->id) {
            abort(403);
        }

        return view('guru.sessions.edit', compact('session'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GameSession $session)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($session->user_id !== $user->id) {
            abort(403);
        }

        $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'is_active' => ['required', 'boolean'],
            'game_mode' => ['required', 'in:quizizz,belajar,duel'],
        ]);

        $session->update([
            'title' => $request->title,
            'is_active' => $request->is_active,
            'game_mode' => $request->game_mode,
        ]);

        return redirect()->route('guru.sessions.index')->with('success', 'Data sesi permainan berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GameSession $session)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($session->user_id !== $user->id) {
            abort(403);
        }

        $session->delete();

        return redirect()->route('guru.sessions.index')->with('success', 'Sesi permainan berhasil dihapus.');
    }

    /**
     * Import Default Questions manually.
     */
    public function importDefaultQuestions(GameSession $session)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($session->user_id !== $user->id) {
            abort(403);
        }

        $defaultQuestions = Question::where('is_default', true)->get();
        $importedCount = 0;

        foreach ($defaultQuestions as $question) {
            if (!$session->questions()->where('question_id', $question->id)->exists()) {
                $session->questions()->attach($question->id, [
                    'wrong_count' => 0,
                    'total_count' => 0,
                ]);
                $importedCount++;
            }
        }

        return redirect()->route('guru.sessions.show', $session)
            ->with('success', "$importedCount Soal Master Default berhasil ditambahkan ke sesi ini.");
    }

    /**
     * Print QR Code sticker page.
     */
    public function printQr(GameSession $session)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($session->user_id !== $user->id) {
            abort(403);
        }

        return view('guru.sessions.print', compact('session'));
    }

    /**
     * Export Game Scores to CSV.
     */
    public function exportScores(GameSession $session)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($session->user_id !== $user->id) {
            abort(403);
        }

        $scores = $session->gameScores()->orderByDesc('skor_akhir')->get();

        $filename = 'rekap_nilai_' . Str::slug($session->title) . '_' . date('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function () use ($scores) {
            $file = fopen('php://output', 'w');

            // Tambahkan BOM agar Excel membaca karakter UTF-8 dengan benar
            fputs($file, "\xEF\xBB\xBF");

            // Header kolom
            fputcsv($file, ['Peringkat', 'Nama Siswa', 'Kelas SD', 'Jawaban Benar', 'Total Sampah', 'Skor Akhir', 'Waktu Bermain'], ';');

            foreach ($scores as $index => $score) {
                fputcsv($file, [
                    $index + 1,
                    $score->nama_siswa,
                    'Kelas ' . $score->kelas,
                    $score->jawaban_benar,
                    $score->total_sampah,
                    $score->skor_akhir,
                    $score->created_at->format('Y-m-d H:i:s'),
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Toggle Duel Game Session status (is_started).
     */
    public function toggleStatus(GameSession $session)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($session->user_id !== $user->id) {
            abort(403);
        }

        $session->update([
            'is_started' => !$session->is_started
        ]);

        return response()->json([
            'status' => 'success',
            'is_started' => $session->is_started
        ]);
    }
}
