<?php

namespace App\Http\Controllers;

use App\Models\AudioClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class AudioClassController extends Controller
{
    /**
     * Mostra la lista delle classi e il form di creazione.
     */
    public function index()
    {
        $classes = AudioClass::where('user_id', Auth::id())
                             ->orderBy('created_at', 'desc')
                             ->get();

        return view('classes.index', ['classes' => $classes]);
    }

    /**
     * Salva una nuova classe.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'tags' => 'nullable|string|max:255',
        ]);

        AudioClass::create([
            'user_id' => Auth::id(),
            'public_id' => 'class_' . Str::random(8),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'tags' => $request->input('tags'),
        ]);

        return redirect()->back()->with('success', 'Classe creata con successo!');
    }

    /**
     * Aggiorna una classe esistente.
     */
    public function update(Request $request, AudioClass $audioClass)
    {
        if ($audioClass->user_id !== Auth::id()) {
            abort(403, 'Non autorizzato');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'tags' => 'nullable|string|max:255',
        ]);

        $audioClass->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'tags' => $request->input('tags'),
        ]);

        return redirect()->back()->with('success', 'Classe aggiornata con successo!');
    }

    /**
     * Elimina una classe.
     */
    public function destroy(AudioClass $audioClass)
    {
        if ($audioClass->user_id !== Auth::id()) {
            abort(403);
        }
        $audioClass->delete();
        return redirect()->back()->with('success', 'Classe eliminata.');
    }

    /**
     * Mostra i file caricati dagli studenti per una specifica classe.
     */
    public function showFiles(AudioClass $audioClass)
    {
        if ($audioClass->user_id !== Auth::id()) {
            abort(403);
        }

        $directory = "audio/{$audioClass->public_id}";
        $files = Storage::disk('public')->files($directory);

        $fileData = [];
        foreach ($files as $file) {
            $fileData[] = [
                'path' => $file,
                'name' => basename($file),
                'size' => Storage::disk('public')->size($file),
                'last_modified' => Storage::disk('public')->lastModified($file),
            ];
        }

        usort($fileData, function($a, $b) {
            return $b['last_modified'] <=> $a['last_modified'];
        });

        return view('classes.files', [
            'class' => $audioClass,
            'files' => $fileData
        ]);
    }

    /**
     * Crea e scarica un ZIP con tutti i file della classe.
     */
    public function downloadAll(AudioClass $audioClass)
    {
        if ($audioClass->user_id !== Auth::id()) {
            abort(403);
        }

        $directory = "audio/{$audioClass->public_id}";
        $files = Storage::disk('public')->files($directory);

        if (empty($files)) {
            return back()->with('error', 'Nessun file da scaricare.');
        }

        $zipFileName = 'class_' . Str::slug($audioClass->name) . '_' . date('Ymd') . '.zip';
        $zipPath = storage_path('app/public/' . $zipFileName);

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($files as $file) {
                $zip->addFile(storage_path('app/public/' . $file), basename($file));
            }
            $zip->close();
        } else {
            return back()->with('error', 'Impossibile creare il file ZIP.');
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    /**
     * Elimina un singolo file caricato da uno studente.
     */
    public function destroyFile(Request $request, AudioClass $audioClass)
    {
        // 1. Controllo di sicurezza: l'utente è il proprietario della classe?
        if ($audioClass->user_id !== Auth::id()) {
            abort(403);
        }

        // 2. Validazione del nome del file che ci è stato inviato
        $data = $request->validate([
            'filename' => 'required|string',
        ]);

        $filename = $data['filename'];
        
        // 3. Costruisci il percorso e fai un controllo di sicurezza (prevenzione Path Traversal)
        // Ci assicuriamo che il file sia ESATTAMENTE in quella cartella
        $directory = "audio/{$audioClass->public_id}";
        // basename() pulisce il nome da eventuali tentativi di risalita (es. ../../)
        $path = $directory . '/' . basename($filename); 

        // 4. Controlla se il file esiste sul disco 'public'
        if (Storage::disk('public')->exists($path)) {
            // 5. Elimina il file
            Storage::disk('public')->delete($path);
            return redirect()->back()->with('success', 'File eliminato con successo.');
        }

        return redirect()->back()->with('error', 'File non trovato o errore imprevisto.');
    }
}