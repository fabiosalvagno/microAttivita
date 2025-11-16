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
        // Modifichiamo 'users' in 'insegnanti'
        Schema::create('insegnanti', function (Blueprint $table) {

            // $table->id();
            // Corrisponde a: `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY
            $table->id();

            // $table->string('email')->unique();
            // Corrisponde a: `email` varchar(255) NOT NULL UNIQUE
            $table->string('email')->unique();

            // Nota: abbiamo rimosso la colonna 'name' che Laravel mette di default
            // perché non c'era nella tua tabella originale

            // $table->string('password');
            // Corrisponde a: `password_hash` varchar(255) NOT NULL
            // Laravel usa 'password' come nome standard
            $table->string('password');

            // $table->rememberToken();
            // Corrisponde a: `reset_token_hash` varchar(64) DEFAULT NULL
            // Questa è la versione di Laravel per il "Ricordami"
            $table->rememberToken();

            // $table->timestamps();
            // Corrisponde a: `data_registrazione` timestamp NOT NULL DEFAULT current_timestamp()
            // Questo crea `created_at` e `updated_at`, che è lo standard di Laravel
            $table->timestamps();

            // NOTA: Non serve aggiungere 'reset_token_expires_at'
            // Laravel gestisce questo in automatico in una tabella separata
            // (che si chiama `password_reset_tokens` e ha già una sua migrazione).
        });
    }

    /**
     * Reverse the migrations.
     */
/**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Modifichiamo 'users' in 'insegnanti'
        Schema::dropIfExists('insegnanti');
    }
};
