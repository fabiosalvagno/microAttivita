<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Statistiche: {{ $attivita->json_data['titolo'] ?? 'Attività' }}
            </h2>
            <a href="{{ route('attivita.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                Torna alla Lista
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if ($risultati->isEmpty())
                        <p class="text-center text-gray-500 py-10">
                            Nessuno ha ancora completato questa attività.
                        </p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">Data e Ora</th>
                                        <th scope="col" class="px-6 py-3">Punteggio</th>
                                        <th scope="col" class="px-6 py-3">Percentuale</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($risultati as $risultato)
                                        <tr class="bg-white border-b hover:bg-gray-50">
                                            <td class="px-6 py-4">
                                                {{ $risultato->created_at->format('d/m/Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 font-medium text-gray-900">
                                                {{ $risultato->punteggio }} / {{ $risultato->punteggio_massimo }}
                                            </td>
                                            <td class="px-6 py-4">
                                                @php
                                                    $percentuale = ($risultato->punteggio / $risultato->punteggio_massimo) * 100;
                                                    $colore = $percentuale >= 60 ? 'text-green-600' : 'text-red-600';
                                                @endphp
                                                <span class="font-bold {{ $colore }}">
                                                    {{ round($percentuale) }}%
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>