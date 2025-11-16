<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>オーディオアップロード (Caricamento Audio)</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style> body { font-family: sans-serif; } #progress-bar { transition: width 0.3s ease; } </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-md bg-white rounded-xl shadow-md p-6 sm:p-8">
        
        <h1 class="text-2xl font-bold text-gray-800 text-center mb-6">
            {{ $audioClass->name }}
        </h1>

        <div id="message-container" class="mb-6"></div>

        <form id="upload-form" action="{{ route('student.upload.store', $audioClass->public_id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            <div>
                <label for="file_audio" class="block text-sm font-medium text-gray-700 mb-2">ファイルを選択 (Seleziona file)</label>
                <input type="file" name="file_audio" id="file_audio" required
                       class="block w-full text-sm text-slate-500
                              file:mr-4 file:py-2 file:px-4
                              file:rounded-full file:border-0
                              file:text-sm file:font-semibold
                              file:bg-blue-50 file:text-blue-700
                              hover:file:bg-blue-100">
            </div>

            <div id="progress-container" class="w-full bg-gray-200 rounded-full h-4 hidden overflow-hidden">
                <div id="progress-bar" class="bg-blue-600 h-4 text-xs font-medium text-blue-100 text-center p-0.5 leading-none" style="width: 0%"></div>
            </div>

            <button type="submit" id="submit-button" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-base font-bold text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                アップロード (Upload)
            </button>
        </form>
    </div>

    <script>
        const form = document.getElementById('upload-form');
        const progressBarContainer = document.getElementById('progress-container');
        const progressBar = document.getElementById('progress-bar');
        const messageContainer = document.getElementById('message-container');
        const submitButton = document.getElementById('submit-button');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const fileInput = document.getElementById('file_audio');
            if (fileInput.files.length === 0) return;

            // Reset interfaccia
            progressBar.style.width = '0%';
            progressBarContainer.classList.remove('hidden');
            messageContainer.innerHTML = '';
            submitButton.disabled = true;
            submitButton.textContent = 'アップロード中... (Caricamento...)';

            const formData = new FormData(form);
            const xhr = new XMLHttpRequest();

            // Progresso
            xhr.upload.addEventListener('progress', (e) => {
                if (e.lengthComputable) {
                    const percent = Math.round((e.loaded / e.total) * 100);
                    progressBar.style.width = percent + '%';
                }
            });

            // Completamento
            xhr.addEventListener('load', () => {
                submitButton.disabled = false;
                submitButton.textContent = 'アップロード (Upload)';
                progressBarContainer.classList.add('hidden');

                let response;
                try {
                    response = JSON.parse(xhr.responseText);
                } catch (err) {
                    response = { success: false, message: '失敗 (Errore imprevisto)' };
                }

                const colorClass = response.success ? 'bg-green-100 text-green-800 border-green-500' : 'bg-red-100 text-red-800 border-red-500';
                messageContainer.innerHTML = `<div class="p-4 border-l-4 rounded ${colorClass}">${response.message || '失敗'}</div>`;
                
                if (response.success) form.reset();
            });

            // Errore di rete
            xhr.addEventListener('error', () => {
                submitButton.disabled = false;
                submitButton.textContent = 'アップロード (Upload)';
                progressBarContainer.classList.add('hidden');
                messageContainer.innerHTML = `<div class="p-4 border-l-4 rounded bg-red-100 text-red-800 border-red-500">失敗 (Errore di rete)</div>`;
            });

            xhr.open('POST', form.action);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken); // Importante per Laravel!
            xhr.send(formData);
        });
    </script>
</body>
</html>