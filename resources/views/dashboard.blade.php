<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pannello di Controllo') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-3xl font-bold text-gray-800 mb-6">Menu Insegnante</h1>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-6">
                        <a href="{{ route('attivita.create') }}" class="block p-6 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition-colors">
                            <h3 class="text-xl font-bold mb-2">Crea Nuova Attività</h3>
                            <p class="text-blue-100">Crea una nuova attività (Scelta Multipla, Vero/Falso, Ascolto).</p>
                        </a>
                        
                        <a href="{{ route('attivita.index') }}" class="block p-6 bg-gray-600 hover:bg-gray-700 text-white rounded-lg shadow transition-colors">
                            <h3 class="text-xl font-bold mb-2">Visualizza Attività</h3>
                            <p class="text-gray-100">Gestisci le tue attività e vedi i risultati.</p>
                        </a>

                        <a href="{{ route('audio.index') }}" class="block p-6 bg-purple-600 hover:bg-purple-700 text-white rounded-lg shadow transition-colors">
                            <h3 class="text-xl font-bold mb-2">Libreria Audio</h3>
                            <p class="text-purple-100">Carica i file audio da usare nelle tue attività.</p>
                        </a>

                        <a href="{{ route('classes.index') }}" class="block p-6 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow transition-colors">
                            <h3 class="text-xl font-bold mb-2">Gestione Classi (Upload)</h3>
                            <p class="text-indigo-100">Crea classi e ottieni i QR code per far caricare i file agli studenti.</p>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>