<?php

namespace App\Http\Controllers;

use App\Models\MicroAttivita;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\AudioFile;
use Illuminate\Support\Facades\Storage;

class MicroAttivitaController extends Controller
{
    /**
     * Mostra una lista di tutte le attività dell'insegnante.
     */
    public function index()
    {
        $id_insegnante = Auth::id();
        $attivita_lista = MicroAttivita::where('id_insegnante', $id_insegnante)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('attivita.index', [
            'attivita_lista' => $attivita_lista
        ]);
    }

    /**
     * Mostra il form per creare una nuova attività.
     */
    public function create()
    {
        return view('attivita.create');
    }

    /**
     * Salva una nuova attività nel database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'activity_text' => 'required|string',
            'limite_tempo' => 'nullable|integer|min:1',
        ]);

        try {
            $parsedData = $this->parseTextToData($request->input('activity_text'));

            $microAttivita = new MicroAttivita();
            $microAttivita->id_univoco = 'attivita_' . Str::random(16);
            $microAttivita->id_insegnante = Auth::id();
            $microAttivita->json_data = $parsedData['attivita_array'];
            $microAttivita->tags = $parsedData['tags_string'];
            $microAttivita->limite_tempo = $request->input('limite_tempo');

            $microAttivita->save();
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['activity_text' => $e->getMessage()]);
        }

        return redirect()->route('attivita.index')->with('success', 'Attività creata con successo!');
    }

    /**
     * Mostra la pagina pubblica per svolgere un'attività.
     */
    public function svolgi(string $id_univoco)
    {
        $attivita = MicroAttivita::where('id_univoco', $id_univoco)->firstOrFail();

        $is_preview = request()->has('preview') &&
            Auth::check() &&
            Auth::id() == $attivita->id_insegnante;

        // --- NUOVO: Gestione Audio ---
        $audioUrl = null;
        if (!empty($attivita->json_data['audio_id'])) {
            $audioFile = AudioFile::find($attivita->json_data['audio_id']);
            if ($audioFile) {
                $audioUrl = Storage::url($audioFile->filename);
            }
        }
        // -----------------------------

        return view('attivita.svolgi', [
            'activityData' => $attivita->json_data,
            'limiteTempo' => $attivita->limite_tempo,
            'activityId' => $attivita->id_univoco,
            'isPreview' => $is_preview,
            'id_univoco_attivita' => $attivita->id_univoco,
            'audioUrl' => $audioUrl, // Passiamo l'URL alla view
        ]);
    }

    /**
     * Mostra il form per modificare un'attività esistente.
     */
    public function edit(MicroAttivita $microAttivita)
    {
        if ($microAttivita->id_insegnante !== Auth::id()) {
            abort(403, 'Non autorizzato');
        }

        // Convertiamo il JSON in testo
        $testo_attivita = $this->dataToText($microAttivita->json_data, $microAttivita->tags);

        return view('attivita.edit', [
            'attivita' => $microAttivita,
            'testo_attivita' => $testo_attivita
        ]);
    }

    /**
     * Aggiorna un'attività esistente nel database.
     */
    public function update(Request $request, MicroAttivita $microAttivita)
    {
        if ($microAttivita->id_insegnante !== Auth::id()) {
            abort(403, 'Non autorizzato');
        }

        $request->validate([
            'activity_text' => 'required|string',
            'limite_tempo' => 'nullable|integer|min:1',
        ]);

        try {
            $parsedData = $this->parseTextToData($request->input('activity_text'));

            $microAttivita->json_data = $parsedData['attivita_array'];
            $microAttivita->tags = $parsedData['tags_string'];
            $microAttivita->limite_tempo = $request->input('limite_tempo');

            $microAttivita->save();
        } catch (\Exception $e) {
            return back()->withInput()->withErrors(['activity_text' => $e->getMessage()]);
        }

        return redirect()->route('attivita.index')->with('success', 'Attività modificata con successo!');
    }


    /**
     * Helper privato per il parsing del testo (dal vecchio funzioni.php)
     */
    /**
     * Helper privato per il parsing del testo.
     * VERSIONE AGGIORNATA: Supporta il comando 'audio:'
     */
    private function parseTextToData($text)
    {
        // Normalizza gli "a capo"
        $text = trim(str_replace(["\r\n", "\r"], "\n", $text));

        $attivita = [
            'titolo' => '',
            'tipo' => '',
            'audio_id' => null, // NUOVO CAMPO
            'domande' => []
        ];
        $tags_string = '';

        // 1. Estrae il Titolo
        // MODIFICA: Ora cerca solo "titolo:" invece di "titolo dell'attivita:"
        if (preg_match('/^titolo:\s*(.*)/im', $text, $matches)) {
            $attivita['titolo'] = trim($matches[1]);
            $text = trim(str_replace($matches[0], '', $text));
        } else {
            // Aggiorniamo anche il messaggio di errore
            throw new \Exception("Formato non valido: manca la riga 'titolo:'.");
        }

        // 2. Estrae l'Audio ID (NUOVO)
        // Cerca una riga che inizia con 'audio:' seguita da un numero
        if (preg_match('/^audio:\s*(\d+)/im', $text, $matches)) {
            $attivita['audio_id'] = (int)$matches[1];
            // Rimuove la riga dal testo per non confondere il parser delle domande
            $text = trim(str_replace($matches[0], '', $text));
        }

        // 3. Estrae i Tag
        if (preg_match('/Tags:\s*(.*)/i', $text, $matches)) {
            $tags_raw = trim($matches[1]);
            $tags_array = preg_split('/\s+/', $tags_raw, -1, PREG_SPLIT_NO_EMPTY);
            $tags_clean = array_map(function ($t) {
                return ltrim($t, '#');
            }, $tags_array);
            $tags_string = implode(', ', $tags_clean);
            $text = trim(str_replace($matches[0], '', $text));
        }

        // 4. Analizza le domande (il resto del testo)
        $stringa_domande = trim($text);
        if (empty($stringa_domande)) {
            throw new \Exception("Nessuna domanda trovata.");
        }

        $blocchi_domande = preg_split('/\n{2,}/', $stringa_domande);
        $tipi_domande = [];

        foreach ($blocchi_domande as $blocco) {
            $blocco_pulito = trim($blocco);
            if (empty($blocco_pulito)) continue;

            $parti_domanda = explode("\n", $blocco_pulito);
            $numero_righe = count($parti_domanda);

            if ($numero_righe === 2) {
                $tipi_domande[] = 'vero_falso';
                $attivita['domande'][] = [
                    'testo' => trim($parti_domanda[0]),
                    'risposta_corretta' => ucfirst(strtolower(trim($parti_domanda[1])))
                ];
            } elseif ($numero_righe === 3) {
                $tipi_domande[] = 'scelta_multipla';
                $opzioni = array_map('trim', explode(',', $parti_domanda[1]));
                $risposta_corretta = trim($parti_domanda[2]);
                if (!in_array($risposta_corretta, $opzioni)) throw new \Exception("La risposta '{$risposta_corretta}' non è tra le opzioni.");
                $attivita['domande'][] = [
                    'testo' => trim($parti_domanda[0]),
                    'opzioni' => $opzioni,
                    'risposta_corretta' => $risposta_corretta
                ];
            } else {
                throw new \Exception("Blocco domanda non valido con {$numero_righe} righe.");
            }
        }

        if (empty($attivita['domande'])) throw new \Exception("Nessuna domanda valida trovata.");

        $tipi_unici = array_unique($tipi_domande);
        if (count($tipi_unici) > 1) throw new \Exception("Le attività non possono contenere un mix di domande.");

        $attivita['tipo'] = reset($tipi_unici);

        return [
            'attivita_array' => $attivita,
            'tags_string' => $tags_string
        ];
    }

    /**
     * Helper privato per convertire JSON in testo (dal vecchio funzioni.php)
     */
    /**
     * Helper privato per convertire i dati in testo (per il form di modifica).
     */
    private function dataToText($json_data, $tags)
    {
        // Gestisce sia array (nuovi) che stringhe JSON (vecchi)
        if (is_string($json_data)) {
            $data = json_decode($json_data, true);
        } else {
            $data = $json_data;
        }

        if (!$data || !is_array($data)) {
            return "Errore: impossibile leggere i dati dell'attività.";
        }

        $output = "titolo: " . ($data['titolo'] ?? '') . "\n";

        // NUOVO: Se c'è un ID audio, aggiungiamo la riga
        if (!empty($data['audio_id'])) {
            $output .= "audio: " . $data['audio_id'] . "\n";
        }

        $output .= "\n"; // Linea vuota dopo l'intestazione

        $activity_type = $data['tipo'] ?? 'scelta_multipla';

        foreach ($data['domande'] ?? [] as $domanda) {
            $output .= ($domanda['testo'] ?? '') . "\n";
            if ($activity_type === 'scelta_multipla') {
                // Assicuriamoci che 'opzioni' sia un array prima di fare implode
                $opzioni = $domanda['opzioni'] ?? [];
                if (is_array($opzioni)) {
                    $output .= implode(', ', $opzioni) . "\n";
                }
            }
            $output .= ($domanda['risposta_corretta'] ?? '') . "\n\n";
        }

        $output = trim($output);

        if (!empty($tags)) {
            $tags_array = explode(',', $tags);
            // Pulizia extra: rimuove eventuali spazi vuoti dagli elementi
            $tags_array = array_map('trim', $tags_array);
            // Filtra eventuali elementi vuoti
            $tags_array = array_filter($tags_array);

            if (!empty($tags_array)) {
                $tags_formatted = array_map(function ($t) {
                    // Assicura che ci sia solo un '#' all'inizio
                    return '#' . ltrim($t, '#');
                }, $tags_array);
                $output .= "\n\nTags: " . implode(' ', $tags_formatted);
            }
        }

        return $output;
    }

    /**
     * Elimina un'attività dal database.
     */
    public function destroy(MicroAttivita $microAttivita)
    {
        // Controllo di sicurezza: l'utente è il proprietario?
        if ($microAttivita->id_insegnante !== Auth::id()) {
            abort(403, 'Non autorizzato');
        }

        // Elimina l'attività
        $microAttivita->delete();

        // Reindirizza alla lista con un messaggio
        return redirect()->route('attivita.index')->with('success', 'Attività eliminata con successo.');
    }

    /**
     * Duplica un'attività esistente.
     */
    public function duplicate(MicroAttivita $microAttivita)
    {
        // Controllo di sicurezza
        if ($microAttivita->id_insegnante !== Auth::id()) {
            abort(403, 'Non autorizzato');
        }

        // Crea una nuova istanza del modello con gli stessi dati
        $nuovaAttivita = $microAttivita->replicate();

        // Genera un nuovo ID univoco e aggiorna il titolo
        $nuovaAttivita->id_univoco = 'attivita_' . Str::random(16);

        // Opzionale: aggiunge "(Copia)" al titolo nel JSON
        $dati = $nuovaAttivita->json_data;
        $dati['titolo'] .= ' (Copia)';
        $nuovaAttivita->json_data = $dati;

        // Salva la nuova attività
        $nuovaAttivita->save();

        return redirect()->route('attivita.index')->with('success', 'Attività duplicata con successo.');
    }
    /**
     * Mostra le statistiche (risultati) di un'attività.
     */
    public function stats(MicroAttivita $microAttivita)
    {
        // Controllo sicurezza
        if ($microAttivita->id_insegnante !== Auth::id()) {
            abort(403, 'Non autorizzato');
        }

        // Recuperiamo i risultati usando la relazione che abbiamo appena creato
        $risultati = $microAttivita->risultati()->orderBy('created_at', 'desc')->get();

        return view('attivita.stats', [
            'attivita' => $microAttivita,
            'risultati' => $risultati
        ]);
    }

    /**
     * Aggiorna solo i tag di un'attività (chiamato via AJAX dalla lista).
     */
    public function updateTags(Request $request, MicroAttivita $microAttivita)
    {
        // 1. Controllo di sicurezza
        if ($microAttivita->id_insegnante !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Non autorizzato'], 403);
        }

        // 2. Validazione
        $request->validate(['tags' => 'nullable|string']);

        // 3. Aggiornamento
        $microAttivita->tags = $request->input('tags');
        $microAttivita->save();

        // 4. Generazione del nuovo HTML per i tag usando il nostro Helper
        // (Assicurati di aver importato App\Helpers\TagHelper in cima al file se serve, 
        //  oppure usa il percorso completo come qui sotto)
        $newTagsHTML = \App\Helpers\TagHelper::generateTagsHTML($microAttivita->tags);

        // 5. Risposta JSON
        return response()->json([
            'success' => true,
            'tags_html' => $newTagsHTML
        ]);
    }
}
