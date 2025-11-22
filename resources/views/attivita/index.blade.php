<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Le Tue Attività') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                Torna al Menu
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 table-fixed">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 w-3/12">Titolo</th>
                                <th scope="col" class="px-6 py-3 w-2/12">Tipo</th> <th scope="col" class="px-6 py-3 w-1/12">Tempo</th>
                                <th scope="col" class="px-6 py-3 w-4/12">Tags</th> <th scope="col" class="px-6 py-3 text-center w-2/12">Azioni</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($attivita_lista as $attivita)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-normal break-words">
                                    {{ $attivita->json_data['titolo'] ?? 'Titolo non disponibile' }}
                                </th>

                                <td class="px-6 py-4">
                                    @php
                                    $tipo = $attivita->json_data['tipo'] ?? 'scelta_multipla';
                                    @endphp
                                    @if ($tipo === 'vero_falso')
                                    <span class="bg-sky-100 text-sky-800 text-xs font-medium px-2.5 py-0.5 rounded-full whitespace-nowrap">Vero / Falso</span>
                                    @else
                                    <span class="bg-teal-100 text-teal-800 text-xs font-medium px-2.5 py-0.5 rounded-full whitespace-nowrap">Scelta Multipla</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($attivita->limite_tempo)
                                    @php
                                    $minuti = floor($attivita->limite_tempo / 60);
                                    $secondi = $attivita->limite_tempo % 60;
                                    @endphp
                                    <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                        {{ $minuti }}m {{ $secondi }}s
                                    </span>
                                    @else
                                    <span class="text-gray-400 italic text-xs">Nessuno</span>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-normal align-top" id="tags-container-{{ $attivita->id }}">
                                    <div class="tags-display flex flex-wrap gap-2 justify-start items-center group">
                                        <div class="tags-content">
                                            {!! \App\Helpers\TagHelper::generateTagsHTML($attivita->tags) !!}
                                        </div>

                                        <button class="edit-tags-btn invisible group-hover:visible p-1 text-gray-400 hover:text-blue-600 flex-shrink-0" data-id="{{ $attivita->id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                            </svg>
                                        </button>
                                    </div>

                                    <div class="tags-edit hidden mt-2">
                                        <input type="text" class="tags-input w-full text-sm border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ $attivita->tags }}">
                                        <div class="mt-2 flex space-x-2 text-xs">
                                            <button class="save-tags-btn bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700" data-id="{{ $attivita->id }}">Salva</button>
                                            <button class="cancel-tags-btn bg-gray-400 text-white px-2 py-1 rounded hover:bg-gray-500">Annulla</button>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 text-center whitespace-nowrap">
                                    <div class="flex items-center justify-center space-x-2">
                                        <button title="Mostra QR Code" class="qr-code-btn p-2 text-gray-500 hover:text-indigo-600"
                                            data-id="{{ $attivita->id_univoco }}"
                                            data-title="{{ $attivita->json_data['titolo'] ?? 'Attività' }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                <path d="M5 5a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1H6a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1v-2zM13 4a1 1 0 00-1 1v2a1 1 0 001 1h2a1 1 0 001-1V5a1 1 0 00-1-1h-2zM9 9a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1h-2a1 1 0 01-1-1V9z" />
                                                <path fill-rule="evenodd" d="M3 3a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H4a1 1 0 01-1-1V3zm2 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H6a1 1 0 01-1-1v-4zM11 3a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H6a1 1 0 01-1-1V3zm5 8a1 1 0 00-1 1v2a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 00-1-1h-2z" clip-rule="evenodd" />
                                            </svg>
                                        </button>

                                        <span class="text-gray-300">|</span>

                                        <a href="{{ route('attivita.edit', $attivita) }}" title="Modifica" class="p-2 text-gray-500 hover:text-green-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>

                                        <form action="{{ route('attivita.duplicate', $attivita) }}" method="POST" class="inline" onsubmit="return confirm('Vuoi duplicare questa attività?');">
                                            @csrf
                                            <button type="submit" title="Duplica" class="p-2 text-gray-500 hover:text-purple-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                                    <path d="M7 9a2 2 0 012-2h6a2 2 0 012 2v6a2 2 0 01-2 2H9a2 2 0 01-2-2V9z" />
                                                    <path d="M5 3a2 2 0 00-2 2v6a2 2 0 002 2V5h6a2 2 0 00-2-2H5z" />
                                                </svg>
                                            </button>
                                        </form>

                                        <a href="{{ route('attivita.stats', $attivita) }}" title="Statistiche" class="p-2 text-gray-500 hover:text-yellow-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                            </svg>
                                        </a>

                                        <form action="{{ route('attivita.destroy', $attivita) }}" method="POST" class="inline" onsubmit="return confirm('Sei sicuro di voler eliminare questa attività?');">
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
                                <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                                    Non hai ancora creato nessuna attività. <a href="#" class="text-blue-600 hover:underline">Inizia ora!</a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="qrModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center hidden">
        <div class="relative mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-2" id="qrModalTitle">QR Code</h3>
                <div class="mt-2 px-7 py-3 flex justify-center">
                    <div id="qrcode-container"></div>
                </div>
                <div class="text-xs text-gray-500 break-all p-2 bg-gray-100 rounded" id="qr-url"></div>
                <div class="items-center px-4 py-3 flex space-x-4">
                    <button id="copyQrLinkBtn" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-400">
                        Copia Link
                    </button>
                    <button id="closeQrModal" class="px-4 py-2 bg-blue-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Chiudi
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // ---- Codice per la gestione del QR Code ----
            const qrModal = document.getElementById('qrModal');
            const closeQrModalBtn = document.getElementById('closeQrModal');
            const qrCodeContainer = document.getElementById('qrcode-container');
            const qrModalTitle = document.getElementById('qrModalTitle');
            const qrUrlSpan = document.getElementById('qr-url');
            const copyQrLinkBtn = document.getElementById('copyQrLinkBtn');

            const closeModal = () => {
                qrModal.classList.add('hidden');
                qrCodeContainer.innerHTML = '';
                copyQrLinkBtn.textContent = 'Copia Link';
            };

            closeQrModalBtn.addEventListener('click', closeModal);
            qrModal.addEventListener('click', (e) => {
                if (e.target === qrModal) {
                    closeModal();
                }
            });

            copyQrLinkBtn.addEventListener('click', () => {
                const linkToCopy = qrUrlSpan.textContent;
                navigator.clipboard.writeText(linkToCopy).then(() => {
                    copyQrLinkBtn.textContent = 'Copiato!';
                    setTimeout(() => {
                        copyQrLinkBtn.textContent = 'Copia Link';
                    }, 2000);
                }).catch(err => {
                    console.error('Errore durante la copia del link: ', err);
                });
            });

            document.querySelectorAll('.qr-code-btn').forEach(button => {
                button.addEventListener('click', (e) => {
                    const id = e.currentTarget.dataset.id;
                    const title = e.currentTarget.dataset.title;

                    // 1. Genera un URL di base con un segnaposto "DUMMY"
                    const baseUrl = '{{ route("attivita.svolgi", ["id_univoco" => "DUMMY"]) }}';

                    // 2. Sostituisci "DUMMY" con il vero ID
                    const activityUrl = baseUrl.replace('DUMMY', id);
                    qrModalTitle.textContent = `QR Code per: ${title}`;
                    qrUrlSpan.textContent = activityUrl;
                    qrCodeContainer.innerHTML = '';

                    new QRCode(qrCodeContainer, {
                        text: activityUrl,
                        width: 200,
                        height: 200,
                    });

                    qrModal.classList.remove('hidden');
                });
            });

            // Qui andrà il codice per la modifica dei tag,
            // ma richiede una rotta API per salvare (lo faremo dopo).
        });

        // ... (dopo il codice del QR code)

        // --- Gestione Modifica Tag ---

        // 1. Clic su "Modifica" (l'icona matita)
        document.querySelectorAll('.edit-tags-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const container = e.currentTarget.closest('td');
                container.querySelector('.tags-display').classList.add('hidden');
                container.querySelector('.tags-edit').classList.remove('hidden');
            });
        });

        // 2. Clic su "Annulla"
        document.querySelectorAll('.cancel-tags-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const container = e.currentTarget.closest('td');
                container.querySelector('.tags-display').classList.remove('hidden');
                container.querySelector('.tags-edit').classList.add('hidden');
            });
        });

        // 3. Clic su "Salva" (Chiamata AJAX a Laravel)
        document.querySelectorAll('.save-tags-btn').forEach(btn => {
            btn.addEventListener('click', async (e) => {
                const id = e.currentTarget.dataset.id;
                const container = document.getElementById(`tags-container-${id}`);
                const input = container.querySelector('.tags-input');
                const newTags = input.value;

                // Disabilita il bottone mentre carica
                e.currentTarget.disabled = true;
                e.currentTarget.textContent = '...';

                try {
                    // Costruiamo l'URL della rotta. 
                    // Usiamo un placeholder '000' per l'ID e poi lo sostituiamo.
                    let url = "{{ route('attivita.updateTags', 000) }}";
                    url = url.replace('000', id);

                    const response = await fetch(url, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}', // Fondamentale per Laravel
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            tags: newTags
                        })
                    });

                    if (!response.ok) throw new Error('Errore di rete');
                    const data = await response.json();

                    if (data.success) {
                        // Aggiorna l'HTML dei tag e torna alla visualizzazione normale
                        container.querySelector('.tags-content').innerHTML = data.tags_html;
                        container.querySelector('.tags-display').classList.remove('hidden');
                        container.querySelector('.tags-edit').classList.add('hidden');
                    }

                } catch (error) {
                    alert('Errore durante il salvataggio dei tag.');
                    console.error(error);
                } finally {
                    // Riabilita il bottone
                    e.currentTarget.disabled = false;
                    e.currentTarget.textContent = 'Salva';
                }
            });
        });
    </script>
</x-app-layout>