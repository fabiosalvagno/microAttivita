<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Il nome della tabella associata al modello.
     *
     * @var string
     */
    protected $table = 'insegnanti'; // <-- MODIFICA PRINCIPALE

    /**
     * Gli attributi che sono assegnabili in massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // Abbiamo rimosso 'name' perché non è nella nostra tabella
        'email',
        'password',
    ];

    /**
     * Gli attributi che dovrebbero essere nascosti per la serializzazione.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token', // Questo corrisponde alla colonna 'remember_token'
    ];

    /**
     * Gli attributi che dovrebbero essere castati.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

// Dentro User.php...

    // Definiamo la relazione: un insegnante (User) può avere molte micro_attivita
    public function microAttivita()
    {
        // 'id_insegnante' è la colonna in micro_attivita che ci collega
        return $this->hasMany(MicroAttivita::class, 'id_insegnante');
    }

    /**
     * Un insegnante può aver caricato molti file audio.
     */
    public function audioFiles()
    {
        return $this->hasMany(AudioFile::class, 'user_id');
    }
}