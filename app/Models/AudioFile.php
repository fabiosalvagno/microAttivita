<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AudioFile extends Model
{
    use HasFactory;

    protected $table = 'audio_files';

    protected $fillable = [
        'user_id',
        'filename',
        'original_name',
        'description',
        'tags',
    ];

    /**
     * Relazione: ogni file audio appartiene a un insegnante.
     */
    public function insegnante()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}