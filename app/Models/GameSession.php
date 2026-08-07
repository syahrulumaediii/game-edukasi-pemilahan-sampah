<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'game_code',
        'is_active',
        'game_mode',
        'is_started',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_started' => 'boolean',
    ];

    /**
     * Sesi game dibuat oleh seorang guru/user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Sesi game memiliki banyak soal pemilahan sampah (pivot).
     */
    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'game_session_questions')
                    ->withPivot('wrong_count', 'total_count')
                    ->withTimestamps();
    }

    /**
     * Sesi game menyimpan banyak data rekap skor siswa.
     */
    public function gameScores(): HasMany
    {
        return $this->hasMany(GameScore::class);
    }
}
