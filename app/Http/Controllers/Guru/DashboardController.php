<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\GameSession;
use App\Models\GameScore;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the teacher dashboard.
     */
    public function index()
    {
        $userId = auth()->id();

        $sessions = GameSession::where('user_id', $userId)->latest()->get();
        $sessionIds = $sessions->pluck('id');

        $totalSessions = $sessions->count();
        
        $totalPlays = GameScore::whereIn('game_session_id', $sessionIds)->count();
        
        $averageScore = GameScore::whereIn('game_session_id', $sessionIds)->avg('skor_akhir') ?? 0;
        $averageScore = round($averageScore, 1);

        $recentScores = GameScore::with('gameSession')
            ->whereIn('game_session_id', $sessionIds)
            ->latest()
            ->limit(5)
            ->get();

        return view('guru.dashboard', compact(
            'sessions',
            'totalSessions',
            'totalPlays',
            'averageScore',
            'recentScores'
        ));
    }
}
