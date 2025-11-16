<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Libreria Audio') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                Torna al Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="p-6 bg-white rounded-2xl shadow-lg">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Carica Nuovo File Audio</h3>
                
                @if ($errors->any())
                    <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('audio.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label for="audio_file" class="block text-sm font-medium text-gray-700 mb-1">File Audio (MP3, M4A, WAV)</label>
                            <input type="file" name="audio_file" id="audio_file" required accept="audio/*"
                                   class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Descrizione (Opzionale)</label>
                            <input type="text" name="description" id="description" placeholder="Es: Dialogo al bar, Lezione 1"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="tags" class="block text-sm font-medium text-gray-700 mb-1">Tags (Opzionale)</label>
                            <input type="text" name="tags" id="tags" placeholder="#A1 #cibo"
                                   class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-colors">
                            Carica File
                        </button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">ID</th>
                                <th scope="col" class="px-6 py-3">Nome File</th>
                                <th scope="col" class="px-6 py-3">Descrizione</th>
                                <th scope="col" class="px-6 py-3">Tags</th>
                                <th scope="col" class="px-6 py-3">Caricato il</th>
                                <th scope="col" class="px-6 py-3 text-center">Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($audioFiles as $file)
                                <tr class="bg-white border-b hover:bg-gray-50">
                                    <td class="px-6 py-4 font-bold text-blue-600">
                                        <div class="flex items-center space-x-2">
                                            <span>{{ $file->id }}</span>
                                            <button onclick="copyAudioId({{ $file->id }})" title="Copia ID per l'attività" class="p-1 text-gray-400 hover:text-blue-600 bg-gray-100 hover:bg-blue-50 rounded transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-medium text-gray-900 truncate max-w-xs" title="{{ $file->original_name }}">
                                        {{ $file->original_name }}
                                    </td>
                                    <td class="px-6 py-4">{{ $file->description ?? '-' }}</td>
                                    <td class="px-6 py-4">
                                        {!! \App\Helpers\TagHelper::generateTagsHTML($file->tags) !!}
                                    </td>
                                    <td class="px-6 py-4">{{ $file->created_at->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <a href="{{ Storage::url($file->filename) }}" target="_blank" title="Ascolta" class="p-2 text-gray-500 hover:text-green-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('audio.destroy', $file) }}" method="POST" class="inline" onsubmit="return confirm('Sei sicuro di voler eliminare questo file audio?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Elimina" class="p-2 text-gray-500 hover:text-red-600">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                                        La tua libreria audio è vuota. Carica il primo file!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Funzione per copiare l'ID negli appunti
        function copyAudioId(id) {
            const textToCopy = `audio: ${id}`;
            navigator.clipboard.writeText(textToCopy).then(() => {
                // Feedback visivo temporaneo (opzionale, ma carino)
                // Potresti mostrare un piccolo "tooltip" o cambiare il colore dell'icona per un secondo.
                alert(`Copiato: "${textToCopy}"`); 
            }).catch(err => {
                console.error('Errore nella copia: ', err);
            });
        }
    </script>
</x-app-layout>