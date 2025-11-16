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
        Schema::create('micro_attivita', function (Blueprint $table) {
            // `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY
            $table->id();

            // `id_univoco` varchar(255) NOT NULL UNIQUE
            $table->string('id_univoco')->unique();

            // `id_insegnante` int(11) NOT NULL
            // Usiamo il tipo standard di Laravel per le chiavi esterne
            $table->unsignedBigInteger('id_insegnante');

            // `json_data` longtext NOT NULL
            $table->longText('json_data');

            // `tags` text DEFAULT NULL
            $table->text('tags')->nullable();
            
            // `limite_tempo` INT(11) NULL DEFAULT NULL
            $table->integer('limite_tempo')->nullable();

            // `data_creazione` timestamp NOT NULL DEFAULT current_timestamp()
            // `timestamps()` crea `created_at` (per data_creazione) e `updated_at`
            $table->timestamps();

            // --- Definizione della Chiave Esterna ---
            // Corrisponde a: CONSTRAINT `fk_insegnante` FOREIGN KEY (`id_insegnante`) REFERENCES `insegnanti` (`id`) ON DELETE CASCADE
            $table->foreign('id_insegnante')
                  ->references('id')
                  ->on('insegnanti')
                  ->onDelete('cascade');
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
        Schema::dropIfExists('micro_attivita');
    }
};
