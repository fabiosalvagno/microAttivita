<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Gestione Classi Audio') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                Torna al Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="p-4 bg-green-100 text-green-700 rounded-lg shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="p-6 bg-white rounded-2xl shadow-lg">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Crea Nuova Classe</h3>
                <form action="{{ route('classes.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nome Classe</label>
                            <input type="text" name="name" required placeholder="Es: Prima A" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Descrizione (Opzionale)</label>
                            <input type="text" name="description" placeholder="Es: Anno 2023/24" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tags (Opzionale)</label>
                            <input type="text" name="tags" placeholder="#corso #mattina" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition-colors">Crea Classe</button>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                        <tr>
                            <th class="px-6 py-3">Nome</th>
                            <th class="px-6 py-3">Descrizione</th>
                            <th class="px-6 py-3">Tags</th>
                            <th class="px-6 py-3 text-center">Azioni</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($classes as $class)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-bold text-gray-900">{{ $class->name }}</td>
                                <td class="px-6 py-4">{{ $class->description ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    {!! \App\Helpers\TagHelper::generateTagsHTML($class->tags) !!}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex items-center justify-center space-x-3">
                                        <a href="{{ route('classes.files', $class) }}" class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full font-bold hover:bg-blue-200 transition-colors flex items-center text-xs" title="Vedi file caricati">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z" />
                                            </svg>
                                            File
                                        </a>

                                        <button class="qr-btn bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full font-bold hover:bg-indigo-200 transition-colors flex items-center text-xs"
                                                data-url="{{ route('student.upload.form', $class->public_id) }}"
                                                data-name="{{ $class->name }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z" /></svg>
                                            QR
                                        </button>

                                        <button class="edit-btn text-gray-500 hover:text-blue-600 transition-colors"
                                                data-id="{{ $class->id }}"
                                                data-name="{{ $class->name }}"
                                                data-description="{{ $class->description }}"
                                                data-tags="{{ $class->tags }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>

                                        <form action="{{ route('classes.destroy', $class) }}" method="POST" onsubmit="return confirm('Sei sicuro di voler eliminare questa classe?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-gray-500 hover:text-red-600 transition-colors">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="px-6 py-10 text-center text-gray-500">Nessuna classe creata.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div id="classQrModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center hidden z-50">
        <div class="relative mx-auto p-5 border w-96 shadow-xl rounded-xl bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-bold text-gray-900 mb-4" id="classQrModalTitle"></h3>
                <div class="flex justify-center p-4 bg-gray-50 rounded-lg border-2 border-dashed border-gray-200">
                    <div id="class-qrcode-container"></div>
                </div>
                <div class="mt-4 text-xs text-gray-500 break-all p-2 bg-gray-100 rounded" id="class-qr-url"></div>
                <div class="mt-6 flex space-x-3">
                    <button id="copyClassLinkBtn" class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 font-bold rounded-lg hover:bg-gray-200 transition-colors">Copia Link</button>
                    <button id="closeClassQrModal" class="flex-1 px-4 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition-colors">Chiudi</button>
                </div>
            </div>
        </div>
    </div>

    <div id="editClassModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center hidden z-50">
        <div class="relative mx-auto p-6 border w-full max-w-md shadow-xl rounded-xl bg-white">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Modifica Classe</h3>
            <form id="editClassForm" action="#" method="POST" class="space-y-4">
                @csrf
                @method('PATCH')
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nome Classe</label>
                    <input type="text" id="edit_name" name="name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Descrizione</label>
                    <input type="text" id="edit_description" name="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tags</label>
                    <input type="text" id="edit_tags" name="tags" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button type="button" id="closeEditModal" class="px-4 py-2 bg-gray-200 text-gray-700 font-bold rounded-lg hover:bg-gray-300 transition-colors">Annulla</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition-colors">Salva Modifiche</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const qrModal = document.getElementById('classQrModal');
            const qrContainer = document.getElementById('class-qrcode-container');
            const qrTitleEl = document.getElementById('classQrModalTitle');
            const qrUrlEl = document.getElementById('class-qr-url');
            const copyBtn = document.getElementById('copyClassLinkBtn');

            document.getElementById('closeClassQrModal').onclick = () => qrModal.classList.add('hidden');
            qrModal.onclick = (e) => { if(e.target === qrModal) qrModal.classList.add('hidden'); }

            copyBtn.onclick = () => {
                navigator.clipboard.writeText(qrUrlEl.textContent);
                copyBtn.textContent = 'Copiato!';
                setTimeout(() => copyBtn.textContent = 'Copia Link', 2000);
            };

            document.querySelectorAll('.qr-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    const url = btn.dataset.url;
                    qrTitleEl.textContent = `QR Code: ${btn.dataset.name}`;
                    qrUrlEl.textContent = url;
                    qrContainer.innerHTML = '';
                    new QRCode(qrContainer, { text: url, width: 200, height: 200 });
                    qrModal.classList.remove('hidden');
                });
            });

            const editModal = document.getElementById('editClassModal');
            const editForm = document.getElementById('editClassForm');
            const editName = document.getElementById('edit_name');
            const editDesc = document.getElementById('edit_description');
            const editTags = document.getElementById('edit_tags');

            document.getElementById('closeEditModal').onclick = () => editModal.classList.add('hidden');
            editModal.onclick = (e) => { if(e.target === editModal) editModal.classList.add('hidden'); }

            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', () => {
                    editName.value = btn.dataset.name;
                    editDesc.value = btn.dataset.description || '';
                    editTags.value = btn.dataset.tags || '';
                    let updateUrl = "{{ route('classes.update', '___ID___') }}";
                    editForm.action = updateUrl.replace('___ID___', btn.dataset.id);
                    editModal.classList.remove('hidden');
                });
            });
        });
    </script>
</x-app-layout>