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
        .correct-answer {
            background-color: #22c55e !important;
            color: white !important;
            border-color: #16a34a !important;
            transform: scale(1.05);
        }
        .wrong-answer {
            background-color: #ef4444 !important;
            color: white !important;
            border-color: #dc2626 !important;
        }
        .disabled-option {
            pointer-events: none;
            opacity: 0.7;
        }
        @keyframes sparkle {
            0% { transform: scale(0) rotate(0deg); opacity: 0; }
            50% { transform: scale(1.2) rotate(10deg); opacity: 1; }
            100% { transform: scale(1) rotate(0deg); opacity: 1; }
        }
        .sparkle-animation {
            animation: sparkle 0.5s ease-out forwards;
        }
        #timer-bar-inner {
            transition: width 1s linear, height 0.3s ease, background-color 0.3s ease;
        }
        #timer-bar-track {
            transition: height 0.3s ease;
        }
        .timer-bar-warning {
            background-color: #ef4444 !important;
            height: 16px !important;
        }
        .timer-track-warning {
            height: 16px !important;
        }
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
                <h1 id="intro-title" class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-8 leading-tight"></h1>
                <div class="mb-10 space-y-4">
                     <p class="text-gray-600 text-xl">Sei pronto per iniziare?</p>
                     <div id="intro-time-box" class="inline-block px-6 py-3 bg-blue-50 rounded-xl border border-blue-100">
                        <p id="intro-time-msg" class="text-2xl font-bold text-blue-800"></p>
                     </div>
                </div>
                <button id="start-button" class="w-full md:w-auto bg-blue-600 hover:bg-blue-700 text-white font-bold py-4 px-12 rounded-xl text-2xl transition-all duration-200 transform hover:scale-105 shadow-lg hover:shadow-xl focus:outline-none focus:ring-4 focus:ring-blue-300">
                    INIZIA
                </button>
            </div>

            <div id="activity-container" class="hidden w-full max-w-2xl bg-white rounded-2xl shadow-lg p-6 md:p-8 transition-all duration-300 relative">

                <div id="correct-feedback" class="hidden absolute inset-0 flex items-center justify-center bg-white/50 backdrop-blur-sm z-20">
                    <svg class="w-24 h-24 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>

                <div id="header" class="mb-6">
                    <h1 id="activity-title" class="text-2xl md:text-3xl font-bold text-gray-800 text-center"></h1>
                    
                    <div id="timer-bar-container" class="hidden w-full my-4">
                        <div id="timer-bar-track" class="h-2 bg-gray-200 rounded-full">
                            <div id="timer-bar-inner" class="h-2 bg-blue-600 rounded-full" style="width: 100%;"></div>
                        </div>
                    </div>
                    <div id="timer-container" class="hidden text-xl font-bold text-gray-700 text-center my-4">
                        Tempo: <span id="timer-display" class="text-blue-600"></span>
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-2.5 mt-4">
                        <div id="progress-bar" class="bg-blue-600 h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                    </div>
                    <p id="progress-text" class="text-center text-sm text-gray-500 mt-2"></p>
                </div>

                <div id="audio-container" class="hidden mb-8 p-4 bg-blue-50 rounded-xl border border-blue-100 text-center">
                    <p class="text-blue-800 font-semibold mb-2">Ascolta la registrazione:</p>
                    <audio id="audio-player" controls class="w-full focus:outline-none"></audio>
                </div>

                <div id="question-area">
                    <p id="question-text" class="text-lg md:text-xl text-gray-700 mb-6 text-center min-h-[60px]"></p>
                    <div id="options-container" class="grid grid-cols-1 gap-4"></div>
                </div>

                <div id="results-screen" class="hidden text-center">
                    <h2 id="results-title" class="text-3xl font-bold text-gray-800">Attività Completata!</h2>
                    <p class="text-5xl font-bold text-blue-600 my-4" id="score-text"></p>
                    <p class="text-gray-600" id="score-subtitle"></p>
                    <div id="review-container" class="mt-8 text-left border-t pt-6"></div>
                    
                    <button onclick="window.location.reload()" id="retry-button" class="mt-8 bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition-transform transform hover:scale-105">
                        Riprova
                    </button>

                    @if ($isPreview)
                        <div class="mt-8 p-4 bg-blue-50 border-t-4 border-blue-500 rounded-b text-blue-900">
                            <p class="font-bold">Modalità Anteprima</p>
                            <div class="mt-4 flex flex-col sm:flex-row justify-center items-center gap-4">
                                <a href="{{ route('dashboard') }}" class="w-full sm:w-auto text-center bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg">Sì, Conferma</a>
                                <form action="{{ route('attivita.destroy', ['microAttivita' => $activityId]) }}" method="POST" class="w-full sm:w-auto inline">
                                    @csrf
                                    @method('DELETE')
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
        // Recupero dati da Blade (ignora le linee rosse in VS Code, sono normali)
        const activityData = @json($activityData);
        const limiteTempo = @json($limiteTempo);
        const activityId = @json($activityId);
        const isPreview = @json($isPreview);
        const audioUrl = @json($audioUrl);

        // Riferimenti DOM
        const introScreenEl = document.getElementById('intro-screen');
        const activityContainerEl = document.getElementById('activity-container');
        const titleEl = document.getElementById('activity-title');
        const headerEl = document.getElementById('header');
        const questionAreaEl = document.getElementById('question-area');
        const resultsScreenEl = document.getElementById('results-screen');
        const audioContainerEl = document.getElementById('audio-container');
        const audioPlayerEl = document.getElementById('audio-player');
        
        // Riferimenti Timer e Progresso
        const timerContainerEl = document.getElementById('timer-container');
        const timerDisplayEl = document.getElementById('timer-display');
        const timerBarContainerEl = document.getElementById('timer-bar-container');
        const timerBarInnerEl = document.getElementById('timer-bar-inner');
        const timerBarTrackEl = document.getElementById('timer-bar-track');
        const progressBarEl = document.getElementById('progress-bar');
        const progressTextEl = document.getElementById('progress-text');

        // Riferimenti Testi e Bottoni
        const questionTextEl = document.getElementById('question-text');
        const optionsContainerEl = document.getElementById('options-container');
        const correctFeedbackEl = document.getElementById('correct-feedback');
        const resultsTitleEl = document.getElementById('results-title');
        const scoreTextEl = document.getElementById('score-text');
        const scoreSubtitleEl = document.getElementById('score-subtitle');
        const reviewContainerEl = document.getElementById('review-container');
        const retryButton = document.getElementById('retry-button');
        
        let timerInterval = null; 
        let tempoRimanente = 0;
        let currentQuestionIndex = 0;
        let score = 0;
        let userAnswers = [];

        // 1. Inizializzazione Schermata Intro
        if (activityData) {
            document.title = activityData.titolo;
            document.getElementById('intro-title').textContent = activityData.titolo;
            titleEl.textContent = activityData.titolo;

            const introMsgEl = document.getElementById('intro-time-msg');
            const introBoxEl = document.getElementById('intro-time-box');

            if (limiteTempo > 0) {
                const m = Math.floor(limiteTempo / 60);
                const s = limiteTempo % 60;
                introMsgEl.textContent = `Tempo a disposizione: ${m > 0 ? m + ' min ' : ''}${s > 0 ? s + ' sec' : ''}`;
                introBoxEl.className = "inline-block px-6 py-3 rounded-xl border bg-blue-50 border-blue-100";
                introMsgEl.className = "text-2xl font-bold text-blue-800";
            } else {
                introMsgEl.textContent = "Nessun limite di tempo. Fai con calma!";
                introBoxEl.className = "inline-block px-6 py-3 rounded-xl border bg-green-50 border-green-100";
                introMsgEl.className = "text-2xl font-bold text-green-700";
            }
        }

        // 2. Avvio Attività (Click su INIZIA)
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

            // Gestione Audio
            if (audioUrl) {
                audioPlayerEl.src = audioUrl;
                audioContainerEl.classList.remove('hidden');
            } else {
                audioContainerEl.classList.add('hidden');
            }
            
            // Gestione Timer
            if (timerInterval) clearInterval(timerInterval);
            if (limiteTempo > 0) {
                tempoRimanente = limiteTempo;
                timerContainerEl.classList.remove('hidden');
                timerBarContainerEl.classList.remove('hidden');
                timerBarTrackEl.classList.remove('timer-track-warning');
                timerBarInnerEl.classList.remove('timer-bar-warning');
                timerBarInnerEl.style.width = '100%';
                updateTimer();
                timerInterval = setInterval(tickTimer, 1000);
            } else {
                timerContainerEl.classList.add('hidden');
                timerBarContainerEl.classList.add('hidden');
            }

            showQuestion();
        }

        function tickTimer() {
            tempoRimanente--;
            updateTimer();
            if (tempoRimanente <= 0) {
                clearInterval(timerInterval);
                resultsTitleEl.textContent = "Tempo Scaduto!";
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
            progressTextEl.textContent = `Domanda ${currentQuestionIndex + 1} di ${activityData.domande.length}`;
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
                    "w-full p-4 border-2 border-green-300 rounded-lg text-lg text-green-700 font-bold hover:bg-green-100 transition-all duration-200" :
                    "w-full p-4 border-2 border-red-300 rounded-lg text-lg text-red-700 font-bold hover:bg-red-100 transition-all duration-200";
            } else {
                btn.className = "w-full p-4 border-2 border-gray-300 rounded-lg text-lg text-gray-700 font-semibold hover:bg-gray-100 hover:border-blue-500 transition-all duration-200";
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

            // Disabilita tutti i bottoni
            optionsContainerEl.querySelectorAll('button').forEach(b => b.classList.add('disabled-option'));

            if (limiteTempo > 0) {
                // MODALITÀ TEST (Veloce, nessun feedback)
                nextQuestion();
            } else {
                // MODALITÀ STUDIO (Feedback visivo)
                if (isCorrect) {
                    btn.classList.add('correct-answer');
                    correctFeedbackEl.classList.remove('hidden');
                    correctFeedbackEl.querySelector('svg').classList.add('sparkle-animation');
                } else {
                    btn.classList.add('wrong-answer');
                    // Mostra quella giusta
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
            timerContainerEl.classList.add('hidden');
            timerBarContainerEl.classList.add('hidden');
            audioContainerEl.classList.add('hidden');
            if (audioPlayerEl) audioPlayerEl.pause();

            headerEl.classList.add('hidden');
            questionAreaEl.classList.add('hidden');
            resultsScreenEl.classList.remove('hidden');

            progressBarEl.style.width = `100%`;
            scoreTextEl.textContent = `${score} / ${activityData.domande.length}`;
            scoreSubtitleEl.textContent = "risposte corrette";

            if (limiteTempo > 0 && tempoRimanente <= 0) {
                 retryButton.disabled = true;
                 retryButton.textContent = "Tempo Scaduto";
                 retryButton.classList.add('opacity-50', 'cursor-not-allowed');
            } else {
                 retryButton.disabled = false;
                 retryButton.textContent = "Riprova";
                 retryButton.classList.remove('opacity-50', 'cursor-not-allowed');
            }

            generateReview();
            if (!isPreview) saveResult();
        }

        function generateReview() {
            reviewContainerEl.innerHTML = '<h3 class="text-xl font-bold text-gray-800 mb-4 text-center">Riepilogo delle tue risposte</h3><ul class="space-y-4"></ul>';
            const list = reviewContainerEl.querySelector('ul');
            userAnswers.forEach(ans => {
                const li = document.createElement('li');
                li.className = `p-4 rounded-lg ${ans.isCorrect ? 'bg-green-50 border-l-4 border-green-500' : 'bg-red-50 border-l-4 border-red-500'}`;
                li.innerHTML = `
                    <p class="font-semibold text-gray-700">${ans.questionText}</p>
                    <p class="text-gray-600 mt-1">La tua risposta: <span class="${ans.isCorrect ? 'text-green-500' : 'text-red-500'} font-bold">${ans.isCorrect ? '✔' : '✖'} ${ans.selectedAnswer}</span></p>
                    ${!ans.isCorrect ? `<p class="text-sm text-green-700 mt-1">Risposta corretta: ${ans.correctAnswer}</p>` : ''}
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