<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                File Studenti: {{ $class->name }}
            </h2>
            <a href="{{ route('classes.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                Torna alle Classi
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('error'))
                <div class="p-4 bg-red-100 text-red-700 rounded-lg shadow-sm">
                    {{ session('error') }}
                </div>
            @endif
            @if (session('success'))
                <div class="p-4 bg-green-100 text-green-700 rounded-lg shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white p-6 rounded-2xl shadow-lg flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">
                        Totale file: {{ count($files) }}
                    </h3>
                    <p class="text-gray-500 text-sm">
                        In questa pagina puoi vedere e scaricare le registrazioni degli studenti.
                    </p>
                </div>
                
                @if(count($files) > 0)
                    <a href="{{ route('classes.downloadAll', $class) }}" class="flex items-center bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg shadow transition-transform transform hover:scale-105">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Scarica Tutto (ZIP)
                    </a>
                @endif
            </div>

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-6 py-3">Nome File</th>
                            <th class="px-6 py-3">Data Caricamento</th>
                            <th class="px-6 py-3">Dimensione</th>
                            <th class="px-6 py-3 text-center">Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($files as $file)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900 truncate max-w-md" title="{{ $file['name'] }}">
                                    {{ $file['name'] }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ date('d/m/Y H:i', $file['last_modified']) }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ number_format($file['size'] / 1024 / 1024, 2) }} MB
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center space-x-4">
                                        
                                        @php
                                            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
                                            $audioExtensions = ['mp3', 'm4a', 'wav', 'ogg', 'aac'];
                                            $isAudio = in_array(strtolower($extension), $audioExtensions);
                                        @endphp

                                        @if ($isAudio)
                                            <a href="{{ Storage::url($file['path']) }}" target="_blank" class="text-blue-600 hover:text-blue-700" title="Ascolta">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8.002v3.996a1 1 0 001.555.832l3.197-2.002a1 1 0 000-1.664l-3.197-1.996z" clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                        @else
                                            <a href="{{ Storage::url($file['path']) }}" target="_blank" class="text-blue-600 hover:text-blue-700" title="Scarica/Visualizza">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                            </a>
                                        @endif
                                        <form action="{{ route('classes.files.destroy', $class) }}" method="POST" onsubmit="return confirm('Sei sicuro di voler eliminare questo file?');">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="filename" value="{{ $file['name'] }}">
                                            <button type="submit" class="text-gray-400 hover:text-red-600" title="Elimina">
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
                                <td colspan="4" class="px-6 py-16 text-center text-gray-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z" />
                                    </svg>
                                    Nessun file caricato dagli studenti per questa classe.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>