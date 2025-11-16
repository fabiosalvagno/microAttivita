<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MicroAttivita extends Model
{
    use HasFactory;

    /**
     * Il nome della tabella associata al modello.
     *
     * @var string
     */
    protected $table = 'micro_attivita'; // Corrisponde al nome della tabella

    /**
     * Gli attributi che sono assegnabili in massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_univoco',
        'id_insegnante',
        'json_data',
        'tags',
        'limite_tempo',
    ];

    /**
     * Gli attributi che dovrebbero essere castati.
     *
     * @var array<string, string>
     */
    protected $casts = [
        // Questa riga dice a Laravel di convertire automaticamente
        // la colonna 'json_data' (che è testo) in un array/oggetto PHP.
        'json_data' => 'array',
    ];

    /**
     * Ottiene l'insegnante (User) a cui appartiene questa attività.
     * * Questo definisce la relazione inversa di quella in User.php
     */
    public function insegnante()
    {
        // 'App\Models\User' è il modello User
        // 'id_insegnante' è la colonna chiave esterna in questa tabella
        return $this->belongsTo(User::class, 'id_insegnante');
    }

    /**
     * Ottiene i risultati associati a questa attività.
     * Relazione 1-a-Molti personalizzata perché usiamo 'id_univoco' invece di 'id'.
     */
    public function risultati()
    {
        // hasMany(ModelloCorrelato, ChiaveEsternaSuRisultati, ChiaveLocaleSuAttivita)
        return $this->hasMany(Risultato::class, 'id_attivita_univoco', 'id_univoco');
    }
}