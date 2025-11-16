<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Crea Nuova Attività') }}
            </h2>
            <a href="{{ route('attivita.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                Visualizza Attività
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg p-8">

                @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-lg" role="alert">
                    <p class="font-bold">Errore</p>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('attivita.store') }}" method="POST">
                    @csrf <div>
                        <label for="activity_text" class="block text-sm font-medium text-gray-700">Contenuto Attività</label>
                        <textarea name="activity_text" id="activity_text" rows="10" class="mt-1 w-full p-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition font-mono text-sm" placeholder="Incolla qui il testo della tua attività...">{{ old('activity_text') }}</textarea>
                    </div>

                    <div class="mt-4">
                        <label for="limite_tempo" class="block text-sm font-medium text-gray-700">Tempo limite (in secondi)</label>
                        <input type="number" name="limite_tempo" id="limite_tempo"
                            class="mt-1 block w-full md:w-1/3 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            placeholder="Es: 120 (lascia vuoto per nessun limite)"
                            value="{{ old('limite_tempo') }}">
                    </div>

                    <div class="mt-6 p-4 bg-gray-900 text-gray-300 rounded-lg text-xs font-mono">
                        <h4 class="font-bold mb-2 text-white">Formato richiesto:</h4>
                        <pre class="whitespace-pre-wrap"><span class="text-yellow-400"># Esempio Scelta Multipla</span>
titolo: Articoli Indeterminativi

Oggi mangio ___ mela.
una, uno, un
una

<span class="text-yellow-400"># Esempio Vero / Falso</span>
titolo: La famiglia italiana

La moglie fa la maggior parte dei lavori di casa.
Vero

<span class="text-yellow-400"># Esempio Ascolto</span>
titolo: Dialogo al Bar
audio: 42

Cosa prende Luigi?
Caffè, Tè, Acqua
Caffè

<span class="text-yellow-400"># I tag vanno sempre alla fine</span>
Tags: #articoli #livelloA1</pre>
                    </div>

                    <div class="mt-8 text-center">
                        <button type="submit" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-12 rounded-lg transition-transform transform hover:scale-105">
                            Crea Attività
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>