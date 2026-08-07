<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'nama_sampah',
        'kategori',
        'gambar',
        'fakta_edukasi',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * Soal kustom dibuat oleh seorang guru/user (Master default bernilai null).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Soal terhubung dengan banyak sesi game (pivot).
     */
    public function gameSessions(): BelongsToMany
    {
        return $this->belongsToMany(GameSession::class, 'game_session_questions')
                    ->withPivot('wrong_count', 'total_count')
                    ->withTimestamps();
    }
}
