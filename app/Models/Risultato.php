<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Risultato extends Model
{
    use HasFactory;

    /**
     * Il nome della tabella associata al modello.
     *
     * @var string
     */
    protected $table = 'risultati'; // Corrisponde al nome della tabella

    /**
     * Gli attributi che sono assegnabili in massa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_attivita_univoco',
        'punteggio',
        'punteggio_massimo',
    ];

    /**
     * Indica se il modello deve essere marcato temporalmente.
     * Laravel gestirà 'created_at' e 'updated_at'
     *
     * @var bool
     */
    public $timestamps = true;

    // Nota: Non definiamo una relazione Eloquent qui perché
    // ci colleghiamo tramite 'id_univoco' e non tramite un ID numerico.
    // Possiamo comunque cercare i risultati facilmente in altri modi.
}