<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AudioClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'public_id',
        'name',
        'description',
        'tags',
    ];

    // Relazione: una classe appartiene a un insegnante
    public function insegnante()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}