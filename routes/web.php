<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    /** @var \App\Models\User $user */
    $user = Auth::user();
    if ($user->role === 'super_admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('guru.dashboard');
})->middleware(['auth'])->name('dashboard');

// Super Admin Routes
Route::middleware(['auth', 'role:super_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('teachers', App\Http\Controllers\Admin\TeacherController::class);
    Route::resource('questions', App\Http\Controllers\Admin\QuestionController::class);
});

// Guru Routes
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Guru\DashboardController::class, 'index'])->name('dashboard');
    Route::get('sessions/{session}/print', [App\Http\Controllers\Guru\GameSessionController::class, 'printQr'])->name('sessions.print');
    Route::get('sessions/{session}/export', [App\Http\Controllers\Guru\GameSessionController::class, 'exportScores'])->name('sessions.export');
    Route::post('sessions/{session}/toggle-status', [App\Http\Controllers\Guru\GameSessionController::class, 'toggleStatus'])->name('sessions.toggle-status');
    Route::resource('sessions', App\Http\Controllers\Guru\GameSessionController::class);
    Route::post('sessions/{session}/import-default', [App\Http\Controllers\Guru\GameSessionController::class, 'importDefaultQuestions'])->name('sessions.import-default');
    Route::resource('sessions.questions', App\Http\Controllers\Guru\SessionQuestionController::class)->shallow();
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Student Gameplay Routes (No Authentication Needed)
Route::get('/play/{game_code}', [App\Http\Controllers\GameController::class, 'play'])->name('play');
Route::get('/play/{game_code}/status', [App\Http\Controllers\GameController::class, 'checkStatus'])->name('play.status');
Route::post('/play/{game_code}/submit-score', [App\Http\Controllers\GameController::class, 'submitScore'])->name('play.submit-score');
Route::get('/play/{game_code}/live', [App\Http\Controllers\GameController::class, 'liveLeaderboard'])->name('play.live-leaderboard');

require __DIR__ . '/auth.php';
