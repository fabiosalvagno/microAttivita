<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
/**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('risultati', function (Blueprint $table) {
            // `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY
            $table->id();

            // `id_attivita_univoco` varchar(255) NOT NULL
            $table->string('id_attivita_univoco');

            // `punteggio` int(11) NOT NULL
            $table->integer('punteggio');

            // `punteggio_massimo` int(11) NOT NULL
            $table->integer('punteggio_massimo');

            // `data_svolgimento` timestamp NOT NULL DEFAULT current_timestamp()
            // Usiamo il metodo standard di Laravel
            $table->timestamps();
            
            // Aggiungiamo un indice per velocizzare le ricerche sull'ID dell'attività
            $table->index('id_attivita_univoco');
        });
    }

/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risultati');
    }
};
