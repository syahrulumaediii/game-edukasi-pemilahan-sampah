<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'game_session_id',
        'nama_siswa',
        'kelas',
        'skor_akhir',
        'jawaban_benar',
        'total_sampah',
    ];

    /**
     * Skor diperoleh dari partisipasi di sesi game tertentu.
     */
    public function gameSession(): BelongsTo
    {
        return $this->belongsTo(GameSession::class);
    }
}
