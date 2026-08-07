<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\GameSession;
use App\Models\GameScore;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin global dashboard.
     */
    public function index()
    {
        $totalSchools = User::where('role', 'guru')
            ->whereNotNull('nama_sekolah')
            ->where('nama_sekolah', '!=', '')
            ->distinct()
            ->count('nama_sekolah');

        $totalTeachers = User::where('role', 'guru')->count();
        $totalSessions = GameSession::count();
        $totalScores = GameScore::count();

        // 5 sesi game terbaru
        $recentSessions = GameSession::with('user')
            ->latest()
            ->limit(5)
            ->get();

        // 5 skor tertinggi
        $topScores = GameScore::with('gameSession')
            ->orderByDesc('skor_akhir')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalSchools',
            'totalTeachers',
            'totalSessions',
            'totalScores',
            'recentSessions',
            'topScores'
        ));
    }
}
