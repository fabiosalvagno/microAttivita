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
        Schema::create('audio_classes', function (Blueprint $table) {
            $table->id();
            // Colleghiamo la classe all'insegnante
            $table->foreignId('user_id')->constrained('insegnanti')->onDelete('cascade');
            // Un codice pubblico univoco per il link del QR
            $table->string('public_id')->unique();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('tags')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audio_classes');
    }
};
