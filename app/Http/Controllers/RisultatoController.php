<?php

namespace App\Http\Controllers;

use App\Models\Risultato;
use Illuminate\Http\Request;

class RisultatoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Salva un nuovo risultato nel database.
     * Questa è la funzione chiamata da svolgi.blade.php
     */
    public function store(Request $request)
    {
        // 1. Valida i dati in arrivo dal JavaScript
        $datiValidati = $request->validate([
            'id_attivita_univoco' => 'required|string|max:255',
            'punteggio' => 'required|integer',
            'punteggio_massimo' => 'required|integer',
        ]);

        // 2. Crea un nuovo record nel database usando il Model
        $risultato = new Risultato();
        $risultato->id_attivita_univoco = $datiValidati['id_attivita_univoco'];
        $risultato->punteggio = $datiValidati['punteggio'];
        $risultato->punteggio_massimo = $datiValidati['punteggio_massimo'];

        // Il Model gestirà automaticamente created_at/updated_at
        $risultato->save();

        // 3. Rispondi al JavaScript con un messaggio di successo
        return response()->json([
            'success' => true,
            'message' => 'Risultato salvato con successo.'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Risultato $risultato)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Risultato $risultato)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Risultato $risultato)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Risultato $risultato)
    {
        //
    }
}
