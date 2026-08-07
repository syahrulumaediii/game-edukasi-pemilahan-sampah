<?php

namespace App\Http\Controllers;

use App\Models\GameSession;
use App\Models\GameScore;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GameController extends Controller
{
    /**
     * Display the student play screen.
     */
    public function play(string $game_code)
    {
        $session = GameSession::where('game_code', $game_code)->first();

        if (!$session) {
            return view('game.error', [
                'message' => 'Kode permainan tidak ditemukan. Periksa kembali scan QR atau link Anda!'
            ]);
        }

        if (!$session->is_active) {
            return view('game.error', [
                'message' => 'Sesi permainan ini sedang ditutup oleh Guru. Silakan tunggu Guru membukanya!'
            ]);
        }

        // Ambil semua soal untuk sesi ini, acak urutannya agar bervariasi
        $questions = $session->questions()
            ->get()
            ->map(function ($q) {
                return [
                    'id' => $q->id,
                    'nama_sampah' => $q->nama_sampah,
                    'kategori' => $q->kategori,
                    'gambar' => asset($q->gambar),
                    'fakta_edukasi' => $q->fakta_edukasi,
                ];
            })
            ->shuffle();

        if ($questions->isEmpty()) {
            return view('game.error', [
                'message' => 'Belum ada soal sampah dimasukkan ke dalam sesi kelas ini oleh Guru.'
            ]);
        }

        return view('game.play', compact('session', 'questions'));
    }

    /**
     * Submit score from the play screen.
     */
    public function submitScore(Request $request, string $game_code)
    {
        $session = GameSession::where('game_code', $game_code)->first();

        if (!$session || !$session->is_active) {
            return response()->json(['error' => 'Sesi permainan tidak aktif.'], 403);
        }

        $request->validate([
            'nama_siswa' => ['required', 'string', 'max:255'],
            'kelas' => ['required', 'in:1,2,3'],
            'skor_akhir' => ['required', 'integer', 'min:0'],
            'jawaban_benar' => ['required', 'integer', 'min:0'],
            'total_sampah' => ['required', 'integer', 'min:1'],
            'questions_shown' => ['required', 'array'],
            'questions_wrong' => ['nullable', 'array'],
        ]);

        $jawaban_benar = $request->jawaban_benar;
        $total_sampah = $request->total_sampah;
        $skor_akhir = $request->skor_akhir;

        // --- ANTI-CHEAT LOGIC ---
        // Jumlah salah = total - benar
        $jawaban_salah = $total_sampah - $jawaban_benar;
        if ($jawaban_salah < 0) {
            return response()->json(['error' => 'Logika skor tidak valid.'], 422);
        }

        // Kalkulasi skor maksimum yang logis:
        // Poin benar (x100) + Poin streak bonus maksimum (jika benar semua, streak bonus adalah (benar - 2) * 20)
        // Poin salah (-20)
        $max_streak_bonus = max(0, ($jawaban_benar - 2) * 20);
        $max_logical_score = ($jawaban_benar * 100) + $max_streak_bonus;

        if ($skor_akhir > $max_logical_score) {
            return response()->json(['error' => 'Manipulasi skor terdeteksi.'], 403);
        }

        // --- SIMPAN SKOR ---
        $score = GameScore::create([
            'game_session_id' => $session->id,
            'nama_siswa' => $request->nama_siswa,
            'kelas' => $request->kelas,
            'skor_akhir' => $skor_akhir,
            'jawaban_benar' => $jawaban_benar,
            'total_sampah' => $total_sampah,
        ]);

        // --- UPDATE STATISTIK MISKONSEPSI PIVOT ---
        $shownIds = $request->questions_shown;
        $wrongIds = $request->questions_wrong ?? [];

        DB::transaction(function () use ($session, $shownIds, $wrongIds) {
            // Increment total_count untuk semua yang ditampilkan
            foreach ($shownIds as $qId) {
                $pivot = $session->questions()->where('question_id', $qId)->first()?->pivot;
                if ($pivot) {
                    $session->questions()->updateExistingPivot($qId, [
                        'total_count' => $pivot->total_count + 1,
                    ]);
                }
            }

            // Increment wrong_count untuk semua yang salah
            foreach ($wrongIds as $qId) {
                $pivot = $session->questions()->where('question_id', $qId)->first()?->pivot;
                if ($pivot) {
                    $session->questions()->updateExistingPivot($qId, [
                        'wrong_count' => $pivot->wrong_count + 1,
                    ]);
                }
            }
        });

        return response()->json([
            'status' => 'success',
            'ranking' => GameScore::where('game_session_id', $session->id)
                ->where('skor_akhir', '>', $skor_akhir)
                ->count() + 1
        ]);
    }

    /**
     * Display live leaderboard for proyektor mode.
     */
    public function liveLeaderboard(string $game_code)
    {
        $session = GameSession::where('game_code', $game_code)->first();

        if (!$session) {
            abort(404);
        }

        $scores = GameScore::where('game_session_id', $session->id)
            ->orderByDesc('skor_akhir')
            ->limit(10)
            ->get();

        // Jika request via AJAX / Fetch, return JSON untuk live reload
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'scores' => $scores
            ]);
        }

        return view('game.live', compact('session', 'scores'));
    }

    /**
     * Check game session status for Duel Mode polling.
     */
    public function checkStatus(string $game_code)
    {
        $session = GameSession::where('game_code', $game_code)->first();

        if (!$session) {
            return response()->json(['error' => 'Sesi tidak ditemukan.'], 404);
        }

        return response()->json([
            'is_active' => $session->is_active,
            'is_started' => $session->is_started,
            'game_mode' => $session->game_mode,
        ]);
    }
}
