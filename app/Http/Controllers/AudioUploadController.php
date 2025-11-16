<?php

namespace App\Http\Controllers;

use App\Models\AudioClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AudioUploadController extends Controller
{
    /**
     * Mostra il modulo di upload per una specifica classe.
     */
    public function showForm(string $public_id)
    {
        $audioClass = AudioClass::where('public_id', $public_id)->firstOrFail();
        return view('upload.form', ['audioClass' => $audioClass]);
    }

    /**
     * Gestisce l'upload del file audio.
     */
    public function store(Request $request, string $public_id)
    {
        $audioClass = AudioClass::where('public_id', $public_id)->first();
        if (!$audioClass) {
             return response()->json(['success' => false, 'message' => '失敗 (Classe non trovata)'], 404);
        }

        // --- INIZIO TEST DI DEBUG ---
        // Controlliamo se il file è arrivato a Laravel
        // Se $request->hasFile('file_audio') è false, significa che
        // PHP lo ha bloccato PRIMA (per colpa di post_max_size o permessi sulla cartella tmp)
        if (!$request->hasFile('file_audio')) {
            return response()->json([
                'success' => false, 
                'message' => '失敗 (File non ricevuto dal server. Controlla i permessi di /tmp e post_max_size nel php.ini di Apache.)'
            ], 400);
        }
        // --- FINE TEST DI DEBUG ---

        // Se il file è arrivato, procediamo con la validazione
        $request->validate([
            'file_audio' => 'required|file|max:51200', // 50MB
        ]);

        try {
            $file = $request->file('file_audio');
            
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeName = Str::slug($originalName);
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . $safeName . '.' . $extension;

            $path = $file->storeAs("audio/{$audioClass->public_id}", $fileName, 'public');

            return response()->json([
                'success' => true,
                'message' => 'アップロード成功しました！'
            ]);

        } catch (\Exception $e) {
            \Log::error('Errore upload: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => '失敗 (Errore server)'], 500);
        }
    }
}