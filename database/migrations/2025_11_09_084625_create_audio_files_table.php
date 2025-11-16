<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audio_files', function (Blueprint $table) {
            $table->id(); // L'ID che userai (es. 42)

            // Colleghiamo il file all'insegnante
            $table->foreignId('user_id')->constrained('insegnanti')->onDelete('cascade');

            $table->string('filename');      // Nome del file sul disco (es. asd876asd.mp3)
            $table->string('original_name'); // Nome originale (es. lezione1.mp3)
            $table->string('description')->nullable(); // La tua descrizione breve
            $table->string('tags')->nullable();        // I tuoi tag per ritrovarlo

            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audio_files');
    }
};
