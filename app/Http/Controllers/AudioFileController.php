<?php

namespace App\Http\Controllers;

use App\Models\AudioFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AudioFileController extends Controller
{
    /**
     * Mostra la lista dei file audio dell'insegnante.
     */
    public function index()
    {
        // Recupera solo i file dell'utente loggato, ordinati dal più recente
        $audioFiles = AudioFile::where('user_id', Auth::id())
                               ->orderBy('created_at', 'desc')
                               ->get();

        return view('audio.index', ['audioFiles' => $audioFiles]);
    }

    /**
     * Salva un nuovo file audio.
     */
    public function store(Request $request)
    {
        // 1. Validazione
        $request->validate([
            'audio_file' => 'required|file|mimes:mp3,wav,m4a,aac|max:51200', // Max 50MB
            'description' => 'nullable|string|max:255',
            'tags' => 'nullable|string|max:255',
        ]);

        // 2. Preparazione file
        $file = $request->file('audio_file');
        $originalName = $file->getClientOriginalName();
        // Creiamo un nome sicuro per il disco: timestamp + nome sanificato
        $filename = time() . '_' . Str::slug(pathinfo($originalName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();

        // 3. Salvataggio su disco (nella cartella 'public/audio_library')
        $path = $file->storeAs('audio_library', $filename, 'public');

        // 4. Salvataggio nel database
        AudioFile::create([
            'user_id' => Auth::id(),
            'filename' => $path,          // Salviamo il percorso relativo
            'original_name' => $originalName,
            'description' => $request->input('description'),
            'tags' => $request->input('tags'),
        ]);

        return redirect()->route('audio.index')->with('success', 'File audio caricato con successo!');
    }

    /**
     * Elimina un file audio.
     */
    public function destroy(AudioFile $audioFile)
    {
        // 1. Controllo di sicurezza: il file appartiene all'utente loggato?
        if ($audioFile->user_id !== Auth::id()) {
            abort(403, 'Non autorizzato');
        }

        // 2. Elimina il file fisico dal disco
        if (Storage::disk('public')->exists($audioFile->filename)) {
            Storage::disk('public')->delete($audioFile->filename);
        }

        // 3. Elimina il record dal database
        $audioFile->delete();

        return redirect()->route('audio.index')->with('success', 'File audio eliminato.');
    }
}