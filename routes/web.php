<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MicroAttivitaController;
use App\Http\Controllers\RisultatoController;
use App\Http\Controllers\AudioFileController;
use App\Http\Controllers\AudioUploadController;
use App\Http\Controllers\AudioClassController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- ROTTE PUBBLICHE ---
Route::get('/', function () {
    return redirect()->route('login'); // Meglio reindirizzare al login se non hai una home pubblica
});

Route::get('/svolgi/{id_univoco}', [MicroAttivitaController::class, 'svolgi'])->name('attivita.svolgi');
Route::post('/risultati/salva', [RisultatoController::class, 'store'])->name('risultati.store');

// ROTTE STUDENTI (Rinominate per evitare conflitti)
Route::get('/upload/{public_id}', [AudioUploadController::class, 'showForm'])->name('student.upload.form');
Route::post('/upload/{public_id}', [AudioUploadController::class, 'store'])->name('student.upload.store');


// --- ROTTE PROTETTE (Insegnante) ---
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Attività
    Route::get('/attivita', [MicroAttivitaController::class, 'index'])->name('attivita.index');
    Route::get('/attivita/crea', [MicroAttivitaController::class, 'create'])->name('attivita.create');
    Route::post('/attivita', [MicroAttivitaController::class, 'store'])->name('attivita.store');
    Route::get('/attivita/{microAttivita}/modifica', [MicroAttivitaController::class, 'edit'])->name('attivita.edit');
    Route::patch('/attivita/{microAttivita}', [MicroAttivitaController::class, 'update'])->name('attivita.update');
    Route::post('/attivita/{microAttivita}/duplica', [MicroAttivitaController::class, 'duplicate'])->name('attivita.duplicate');
    Route::delete('/attivita/{microAttivita}', [MicroAttivitaController::class, 'destroy'])->name('attivita.destroy');
    Route::patch('/attivita/{microAttivita}/tags', [MicroAttivitaController::class, 'updateTags'])->name('attivita.updateTags');
    Route::get('/attivita/{microAttivita}/statistiche', [MicroAttivitaController::class, 'stats'])->name('attivita.stats');

    // Libreria Audio (Insegnante)
    // Ora queste sono le uniche con questi nomi, quindi funzioneranno!
    Route::get('/audio', [AudioFileController::class, 'index'])->name('audio.index');
    Route::post('/audio', [AudioFileController::class, 'store'])->name('audio.store');
    Route::delete('/audio/{audioFile}', [AudioFileController::class, 'destroy'])->name('audio.destroy');

    // Gestione Classi & File Studenti
    Route::get('/classi', [AudioClassController::class, 'index'])->name('classes.index');
    Route::post('/classi', [AudioClassController::class, 'store'])->name('classes.store');
    Route::patch('/classi/{audioClass}', [AudioClassController::class, 'update'])->name('classes.update');
    Route::delete('/classi/{audioClass}', [AudioClassController::class, 'destroy'])->name('classes.destroy');
    Route::get('/classi/{audioClass}/files', [AudioClassController::class, 'showFiles'])->name('classes.files');
    Route::get('/classi/{audioClass}/download-all', [AudioClassController::class, 'downloadAll'])->name('classes.downloadAll');
    // --- NUOVA ROTTA PER ELIMINARE UN SINGOLO FILE ---
    Route::delete('/classi/{audioClass}/files', [AudioClassController::class, 'destroyFile'])->name('classes.files.destroy');


    
});

require __DIR__ . '/auth.php';
