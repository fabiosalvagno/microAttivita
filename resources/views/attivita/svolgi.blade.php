<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Svolgi Attività</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .correct-answer { background-color: #22c55e !important; color: white !important; border-color: #16a34a !important; transform: scale(1.05); }
        .wrong-answer { background-color: #ef4444 !important; color: white !important; border-color: #dc2626 !important; }
        .disabled-option { pointer-events: none; opacity: 0.7; }
        @keyframes sparkle { 0% { transform: scale(0) rotate(0deg); opacity: 0; } 50% { transform: scale(1.2) rotate(10deg); opacity: 1; } 100% { transform: scale(1) rotate(0deg); opacity: 1; } }
        .sparkle-animation { animation: sparkle 0.5s ease-out forwards; }
        #timer-bar-inner { transition: width 1s linear, height 0.3s ease, background-color 0.3s ease; }
        #timer-bar-track { transition: height 0.3s ease; }
        .timer-bar-warning { background-color: #ef4444 !important; height: 16px !important; }
        .timer-track-warning { height: 16px !important; }
    </style>
</head>

<body class="bg-gray-50">
    <div class="flex flex-col items-center justify-center min-h-screen p-4">

        @if (!isset($activityData))
            <div class="bg-white p-8 rounded-2xl shadow-lg text-center">
                <h1 class="text-2xl font-bold text-red-600">Oops!</h1>
                <p class="text-gray-700 mt-2">Errore nel caricamento dell'attività.</p>
            </div>
        @else
            
            <div id="intro-screen" class="w-full max-w-2xl bg-white rounded-2xl shadow-lg p-8 text-center">
                <h1 id="intro-title" class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-2 leading-tight"></h1>
                
                <div class="text-left bg-gray-50 p-6 rounded-xl border border-gray-200 mb-8 mt-6">
                    <h3 class="font-bold text-gray-800 mb-4 text-lg border-b pb-2">
                        📝 Istruzioni / 説明
                    </h3>
                    <ul class="space-y-4 text-gray-700">
                        
                        @if($audioUrl)
                        <li class="flex items-start">
                            <span class="mr-3 text-2xl">🎧</span>
                            <div>
                                <p class="font-bold">Ascolta l'audio.</p>
                                <p class="text-sm text-gray-500">音声を聴いてください。一時停止や巻き戻しも可能です。</p>
                            </div>
                        </li>
                        @endif

                        <li class="flex items-start">
                            <span class="mr-3 text-2xl">👆</span>
                            <div>
                                <p class="font-bold">Scegli la risposta.</p>
                                <p class="text-sm text-gray-500">正しいと思う答えを選択してください。</p>
                            </div>
                        </li>
                        <li class="flex items-start">
                            <span class="mr-3 text-2xl">✅</span>
                            <div>
                                <p class="font-bold">Conferma.</p>
                                <p class="text-sm text-gray-500">自動的に次の質問に進みます。</p>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="mb-10">
                     <div id="intro-time-box" class="inline-block px-6 py-3 bg-blue-50 rounded-xl border border-blue-100">
                        <p id="intro-time-msg" class="text-xl font-bold text-blue-800"></p>
                        <p id="intro-time-sub" class="text-sm text-blue-600 mt-1"></p>
                     </div>
                </div>

                <button id="start-button" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-16 rounded-xl text-xl transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-blue-300">
                    INIZIA / スタート
                </button>
            </div>

            <div id="activity-container" class="hidden w-full max-w-2xl bg-white rounded-2xl shadow-lg p-6 md:p-8 transition-all duration-300 relative">

                <div id="correct-feedback" class="hidden absolute inset-0 flex items-center justify-center bg-white/50 backdrop-blur-sm z-20">
                    <svg class="w-24 h-24 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>

                <div id="header" class="mb-6">
                    <h1 id="activity-title" class="text-xl md:text-2xl font-bold text-gray-800 text-center mb-4"></h1>
                    
                    <div id="timer-block" class="hidden">
                        <div id="timer-bar-container" class="w-full mb-2">
                            <div id="timer-bar-track" class="h-2 bg-gray-200 rounded-full">
                                <div id="timer-bar-inner" class="h-2 bg-blue-600 rounded-full" style="width: 100%;"></div>
                            </div>
                        </div>
                        <div class="text-center text-lg font-bold text-gray-700">
                            <span id="timer-display" class="text-blue-600"></span>
                        </div>
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-2.5 mt-4">
                        <div id="progress-bar" class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                    <p id="progress-text" class="text-center text-sm text-gray-500 mt-1"></p>
                </div>

                <div id="audio-container" class="hidden mb-8 p-4 bg-indigo-50 rounded-xl border border-indigo-100">
                    <div class="text-center mb-3">
                        <p class="text-indigo-800 font-bold text-sm">Ascolta la registrazione</p>
                        <p class="text-indigo-600 text-xs">音声を再生 (一時停止可能)</p>
                    </div>
                    
                    <audio id="audio-player" class="w-full mb-4 h-8 hidden"></audio>

                    <div class="flex justify-center items-center gap-4">
                        <button id="btn-rewind" class="flex flex-col items-center justify-center w-16 h-16 bg-white text-indigo-600 rounded-full shadow border border-indigo-200 hover:bg-indigo-100 active:scale-95 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12.066 11.2a1 1 0 000 1.6l5.334 4A1 1 0 0019 16V8a1 1 0 00-1.6-.8l-5.333 4zM4.066 11.2a1 1 0 000 1.6l5.334 4A1 1 0 0011 16V8a1 1 0 00-1.6-.8l-5.334 4z" />
                            </svg>
                            <span class="text-xs font-bold">-10s</span>
                        </button>

                        <button id="btn-play-pause" class="flex items-center justify-center w-20 h-20 bg-indigo-600 text-white rounded-full shadow-lg hover:bg-indigo-700 active:scale-95 transition-all">
                            <svg id="icon-play" xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <svg id="icon-pause" xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </button>

                        <button id="btn-forward" class="flex flex-col items-center justify-center w-16 h-16 bg-white text-indigo-600 rounded-full shadow border border-indigo-200 hover:bg-indigo-100 active:scale-95 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.933 12.8a1 1 0 000-1.6L6.6 7.2A1 1 0 005 8v8a1 1 0 001.6.8l5.333-4zM19.933 12.8a1 1 0 000-1.6l-5.333-4A1 1 0 0013 8v8a1 1 0 001.6.8l5.333-4z" />
                            </svg>
                            <span class="text-xs font-bold">+10s</span>
                        </button>
                    </div>
                    <p id="audio-error-msg" class="text-red-600 text-sm mt-2 hidden text-center">Errore: File audio non trovato.</p>
                </div>

                <div id="question-area">
                    <p id="question-text" class="text-lg md:text-xl text-gray-700 mb-6 text-center min-h-[60px] font-medium"></p>
                    <div id="options-container" class="grid grid-cols-1 gap-4"></div>
                </div>

                <div id="results-screen" class="hidden text-center">
                    <h2 id="results-title" class="text-3xl font-bold text-gray-800 mb-1">Attività Completata!</h2>
                    <p id="results-subtitle-jp" class="text-gray-500 mb-4">お疲れ様でした！</p>

                    <p class="text-5xl font-bold text-blue-600 my-4" id="score-text"></p>
                    <p class="text-gray-600" id="score-subtitle"></p>
                    
                    <div id="review-container" class="mt-8 text-left border-t pt-6"></div>
                    
                    <button onclick="window.location.reload()" id="retry-button" class="mt-8 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition-transform transform hover:scale-105">
                        Riprova / もう一度
                    </button>

                    @if ($isPreview)
                        <div class="mt-8 p-4 bg-blue-50 border-t-4 border-blue-500 rounded-b text-blue-900">
                            <p class="font-bold">Modalità Anteprima</p>
                            <div class="mt-4 flex flex-col sm:flex-row justify-center items-center gap-4">
                                <a href="{{ route('dashboard') }}" class="w-full sm:w-auto text-center bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg">Sì, Conferma</a>
                                <form action="{{ route('attivita.destroy', ['microAttivita' => $activityId]) }}" method="POST" class="w-full sm:w-auto inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="w-full text-center bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg" onclick="return confirm('Sei sicuro di voler eliminare questa attività?');">
                                        No, Elimina
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>

    <script>
        const activityData = @json($activityData);
        const limiteTempo = @json($limiteTempo);
        const activityId = @json($activityId);
        const isPreview = @json($isPreview);
        const audioUrl = @json($audioUrl);

        // DOM Elements
        const introScreenEl = document.getElementById('intro-screen');
        const activityContainerEl = document.getElementById('activity-container');
        const titleEl = document.getElementById('activity-title');
        const headerEl = document.getElementById('header');
        const questionAreaEl = document.getElementById('question-area');
        const resultsScreenEl = document.getElementById('results-screen');
        
        // Audio
        const audioContainerEl = document.getElementById('audio-container');
        const audioPlayerEl = document.getElementById('audio-player');
        const btnPlayPause = document.getElementById('btn-play-pause');
        const iconPlay = document.getElementById('icon-play');
        const iconPause = document.getElementById('icon-pause');
        const btnRewind = document.getElementById('btn-rewind');
        const btnForward = document.getElementById('btn-forward');
        const audioErrorMsg = document.getElementById('audio-error-msg');

        // Timer
        const timerBlock = document.getElementById('timer-block');
        const timerContainerEl = document.getElementById('timer-container');
        const timerDisplayEl = document.getElementById('timer-display');
        const timerBarInnerEl = document.getElementById('timer-bar-inner');
        const timerBarTrackEl = document.getElementById('timer-bar-track');
        
        // Progress & Content
        const progressBarEl = document.getElementById('progress-bar');
        const progressTextEl = document.getElementById('progress-text');
        const questionTextEl = document.getElementById('question-text');
        const optionsContainerEl = document.getElementById('options-container');
        const correctFeedbackEl = document.getElementById('correct-feedback');
        
        // Results
        const resultsTitleEl = document.getElementById('results-title');
        const resultsSubtitleJp = document.getElementById('results-subtitle-jp');
        const scoreTextEl = document.getElementById('score-text');
        const scoreSubtitleEl = document.getElementById('score-subtitle');
        const reviewContainerEl = document.getElementById('review-container');
        const retryButton = document.getElementById('retry-button');
        
        let timerInterval = null; 
        let tempoRimanente = 0;
        let currentQuestionIndex = 0;
        let score = 0;
        let userAnswers = [];

        function setupAudioControls() {
            btnPlayPause.addEventListener('click', () => {
                if (audioPlayerEl.paused) { audioPlayerEl.play(); } 
                else { audioPlayerEl.pause(); }
            });
            audioPlayerEl.addEventListener('play', () => { iconPlay.classList.add('hidden'); iconPause.classList.remove('hidden'); });
            audioPlayerEl.addEventListener('pause', () => { iconPause.classList.add('hidden'); iconPlay.classList.remove('hidden'); });
            btnRewind.addEventListener('click', () => { audioPlayerEl.currentTime = Math.max(0, audioPlayerEl.currentTime - 10); });
            btnForward.addEventListener('click', () => { audioPlayerEl.currentTime = Math.min(audioPlayerEl.duration, audioPlayerEl.currentTime + 10); });
            
            // DEBUG ERRORI AUDIO
            audioPlayerEl.addEventListener('error', (e) => {
                console.error("Errore audio:", e);
                audioErrorMsg.classList.remove('hidden');
                audioErrorMsg.textContent = "Errore: File audio non trovato o formato non supportato.";
            });
        }

        // INIZIALIZZAZIONE
        if (activityData) {
            document.title = activityData.titolo;
            document.getElementById('intro-title').textContent = activityData.titolo;
            titleEl.textContent = activityData.titolo;

            const introMsgEl = document.getElementById('intro-time-msg');
            const introSubEl = document.getElementById('intro-time-sub');
            const introBoxEl = document.getElementById('intro-time-box');

            if (limiteTempo > 0) {
                const m = Math.floor(limiteTempo / 60);
                const s = limiteTempo % 60;
                introMsgEl.textContent = `Tempo a disposizione: ${m > 0 ? m + ' min ' : ''}${s > 0 ? s + ' sec' : ''}`;
                introSubEl.textContent = `制限時間: ${m > 0 ? m + '分' : ''}${s > 0 ? s + '秒' : ''}`;
                introBoxEl.className = "inline-block px-6 py-3 rounded-xl border bg-blue-50 border-blue-100";
                introMsgEl.className = "text-xl font-bold text-blue-800";
            } else {
                introMsgEl.textContent = "Nessun limite di tempo.";
                introSubEl.textContent = "時間制限はありません。ゆっくり解いてください。";
                introBoxEl.className = "inline-block px-6 py-3 rounded-xl border bg-green-50 border-green-100";
                introMsgEl.className = "text-xl font-bold text-green-700";
            }
        }

        document.getElementById('start-button').addEventListener('click', () => {
            introScreenEl.classList.add('hidden');
            activityContainerEl.classList.remove('hidden');
            startActivity();
        });

        function startActivity() {
            currentQuestionIndex = 0;
            score = 0;
            userAnswers = [];
            resultsScreenEl.classList.add('hidden');
            headerEl.classList.remove('hidden');
            questionAreaEl.classList.remove('hidden');
            resultsTitleEl.textContent = "Attività Completata!"; 
            resultsSubtitleJp.textContent = "お疲れ様でした！";

            if (audioUrl) {
                audioPlayerEl.src = audioUrl;
                // FORZA IL CARICAMENTO
                audioPlayerEl.load();
                audioContainerEl.classList.remove('hidden');
                setupAudioControls();
            } else {
                audioContainerEl.classList.add('hidden');
            }
            
            if (timerInterval) clearInterval(timerInterval);
            if (limiteTempo > 0) {
                tempoRimanente = limiteTempo;
                timerBlock.classList.remove('hidden');
                timerBarTrackEl.classList.remove('timer-track-warning');
                timerBarInnerEl.classList.remove('timer-bar-warning');
                timerBarInnerEl.style.width = '100%';
                updateTimer();
                timerInterval = setInterval(tickTimer, 1000);
            } else {
                timerBlock.classList.add('hidden');
            }

            showQuestion();
        }

        function tickTimer() {
            tempoRimanente--;
            updateTimer();
            if (tempoRimanente <= 0) {
                clearInterval(timerInterval);
                resultsTitleEl.textContent = "Tempo Scaduto!";
                resultsSubtitleJp.textContent = "時間切れです！";
                showResults();
            }
        }

        function updateTimer() {
            const m = Math.floor(tempoRimanente / 60);
            const s = tempoRimanente % 60;
            timerDisplayEl.textContent = `${m}:${s < 10 ? '0' : ''}${s}`;
            const percent = (tempoRimanente / limiteTempo) * 100;
            timerBarInnerEl.style.width = `${percent}%`;
            if (tempoRimanente <= 10) {
                timerBarTrackEl.classList.add('timer-track-warning');
                timerBarInnerEl.classList.add('timer-bar-warning');
            }
        }

        function showQuestion() {
            if (limiteTempo > 0 && tempoRimanente <= 0) return;

            const q = activityData.domande[currentQuestionIndex];
            const progress = (currentQuestionIndex / activityData.domande.length) * 100;
            progressBarEl.style.width = `${progress}%`;
            progressTextEl.textContent = `Domanda / 質問 ${currentQuestionIndex + 1} / ${activityData.domande.length}`;
            questionTextEl.textContent = q.testo;
            optionsContainerEl.innerHTML = '';

            const type = activityData.tipo || 'scelta_multipla';
            if (type === 'vero_falso') {
                optionsContainerEl.className = "grid grid-cols-2 gap-4";
                ['Vero', 'Falso'].forEach(opt => createOptionBtn(opt, q.risposta_corretta, true));
            } else {
                optionsContainerEl.className = "grid grid-cols-1 gap-4";
                [...q.opzioni].sort(() => Math.random() - 0.5).forEach(opt => createOptionBtn(opt, q.risposta_corretta, false));
            }
        }

        function createOptionBtn(text, correct, isVF) {
            const btn = document.createElement('button');
            btn.textContent = text;
            if (isVF) {
                btn.className = (text === 'Vero') ? 
                    "w-full p-4 border-2 border-green-300 rounded-lg text-lg text-green-700 font-bold hover:bg-green-100 transition-all duration-200 shadow-sm" :
                    "w-full p-4 border-2 border-red-300 rounded-lg text-lg text-red-700 font-bold hover:bg-red-100 transition-all duration-200 shadow-sm";
            } else {
                btn.className = "w-full p-4 border-2 border-gray-200 rounded-lg text-lg text-gray-700 font-semibold hover:bg-blue-50 hover:border-blue-300 transition-all duration-200 shadow-sm hover:shadow-md text-left";
            }
            btn.onclick = () => selectAnswer(btn, text, correct);
            optionsContainerEl.appendChild(btn);
        }

        function selectAnswer(btn, selected, correct) {
            if (limiteTempo > 0 && tempoRimanente <= 0) return;

            const isCorrect = selected === correct;
            if (isCorrect) score++;
            
            userAnswers.push({
                questionText: activityData.domande[currentQuestionIndex].testo,
                selectedAnswer: selected,
                correctAnswer: correct,
                isCorrect: isCorrect
            });

            optionsContainerEl.querySelectorAll('button').forEach(b => b.classList.add('disabled-option'));

            if (limiteTempo > 0) {
                nextQuestion();
            } else {
                if (isCorrect) {
                    btn.classList.add('correct-answer');
                    correctFeedbackEl.classList.remove('hidden');
                    correctFeedbackEl.querySelector('svg').classList.add('sparkle-animation');
                } else {
                    btn.classList.add('wrong-answer');
                    optionsContainerEl.querySelectorAll('button').forEach(b => {
                        if (b.textContent === correct) b.classList.add('correct-answer');
                    });
                }
                setTimeout(() => {
                    correctFeedbackEl.classList.add('hidden');
                    correctFeedbackEl.querySelector('svg').classList.remove('sparkle-animation');
                    nextQuestion();
                }, 1500);
            }
        }

        function nextQuestion() {
            currentQuestionIndex++;
            if (currentQuestionIndex < activityData.domande.length) {
                showQuestion();
            } else {
                showResults();
            }
        }

        function showResults() {
            if (timerInterval) clearInterval(timerInterval);
            timerBlock.classList.add('hidden');
            audioContainerEl.classList.add('hidden');
            if (audioPlayerEl) audioPlayerEl.pause();

            headerEl.classList.add('hidden');
            questionAreaEl.classList.add('hidden');
            resultsScreenEl.classList.remove('hidden');

            progressBarEl.style.width = `100%`;
            scoreTextEl.textContent = `${score} / ${activityData.domande.length}`;
            scoreSubtitleEl.textContent = "risposte corrette / 正解";

            retryButton.disabled = false;
            retryButton.textContent = "Riprova / もう一度";
            retryButton.classList.remove('opacity-50', 'cursor-not-allowed');

            generateReview();
            if (!isPreview) saveResult();
        }

        function generateReview() {
            reviewContainerEl.innerHTML = '<h3 class="text-xl font-bold text-gray-800 mb-4 text-center border-b pb-2">Riepilogo / 結果詳細</h3><ul class="space-y-4"></ul>';
            const list = reviewContainerEl.querySelector('ul');
            userAnswers.forEach(ans => {
                const li = document.createElement('li');
                li.className = `p-4 rounded-lg ${ans.isCorrect ? 'bg-green-50 border-l-4 border-green-500' : 'bg-red-50 border-l-4 border-red-500'}`;
                li.innerHTML = `
                    <p class="font-semibold text-gray-800">${ans.questionText}</p>
                    <div class="mt-2 text-sm">
                        <p class="text-gray-700">La tua risposta / あなたの答え:</p>
                        <p class="${ans.isCorrect ? 'text-green-600' : 'text-red-600'} font-bold text-lg">
                            ${ans.isCorrect ? '✔' : '✖'} ${ans.selectedAnswer}
                        </p>
                    </div>
                    ${!ans.isCorrect ? `
                        <div class="mt-2 text-sm">
                            <p class="text-green-700">Risposta corretta / 正解:</p>
                            <p class="text-green-800 font-bold">${ans.correctAnswer}</p>
                        </div>
                    ` : ''}
                `;
                list.appendChild(li);
            });
        }

        async function saveResult() {
            try {
                await fetch('{{ route("risultati.store") }}', {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        id_attivita_univoco: activityId,
                        punteggio: score,
                        punteggio_massimo: activityData.domande.length
                    })
                });
            } catch (e) { console.error('Errore salvataggio:', e); }
        }
    </script>
</body>
</html>