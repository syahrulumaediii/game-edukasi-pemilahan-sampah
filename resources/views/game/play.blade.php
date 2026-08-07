<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.5, maximum-scale=1.5, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detektif Sampah - Sesi Kelas</title>
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css'])
    
    <!-- Poppins & Fredoka Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #ecfdf5;
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
        }
        .font-fredoka {
            font-family: 'Fredoka', sans-serif;
        }
        [x-cloak] { display: none !important; }
        .tap-scale:active {
            transform: scale(0.92);
        }
    </style>
</head>
<body class="flex flex-col items-center justify-center min-h-screen p-4 overflow-x-hidden">

    <!-- GAME ENGINE CONTAINER -->
    <div x-data="gameEngine()" x-init="preloadAssets()" class="w-full max-w-md bg-white rounded-[2.5rem] shadow-2xl border-4 border-emerald-500 overflow-hidden relative" x-cloak>
        
        <!-- ================= FASE 1: LOADING SCREEN ================= -->
        <div x-show="gameState === 'loading'" class="p-8 py-16 text-center space-y-6">
            <div class="text-6xl animate-bounce">🕵️‍♂️🔎</div>
            <h2 class="font-fredoka font-bold text-2xl text-emerald-800">Menyiapkan Game...</h2>
            <div class="w-full bg-emerald-100 rounded-full h-4 overflow-hidden border border-emerald-200">
                <div class="bg-gradient-to-r from-emerald-400 to-emerald-600 h-4 rounded-full transition-all duration-300" :style="`width: ${loadingProgress}%`"></div>
            </div>
            <p class="text-xs text-gray-400 font-semibold uppercase tracking-widest">Memuat Gambar Sampah</p>
        </div>

        <!-- ================= FASE 2: WELCOME / LOGIN SCREEN ================= -->
        <div x-show="gameState === 'welcome'" class="p-8 text-center space-y-6">
            <!-- Header Mascot -->
            <div class="space-y-2">
                <div class="text-7xl">🕵️‍♂️</div>
                <h1 class="font-fredoka font-bold text-3xl text-emerald-800 tracking-wide">DETEKTIF SAMPAH</h1>
                <p class="text-xs text-emerald-600 bg-emerald-50 py-1.5 px-3 rounded-full inline-block font-semibold">Sesi: {{ $session->title }}</p>
            </div>

            <!-- Form Identitas -->
            <div class="space-y-4 text-left">
                <!-- Input Nama -->
                <div>
                    <label class="font-fredoka font-bold text-sm text-gray-700 block mb-1">Siapa Namamu?</label>
                    <input type="text" x-model="studentName" placeholder="Tulis nama panggilanmu..." class="w-full border-2 border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500 rounded-2xl p-4 text-center font-fredoka font-semibold text-lg text-gray-800 shadow-inner placeholder:text-gray-300">
                    <p x-show="showNameError" class="text-xs text-red-500 font-bold mt-1">⚠️ Tolong tulis namamu dulu ya!</p>
                </div>

                <!-- Input Kelas (Tombol Besar) -->
                <div>
                    <label class="font-fredoka font-bold text-sm text-gray-700 block mb-2">Kamu Kelas Berapa?</label>
                    <div class="grid grid-cols-3 gap-3">
                        <button @click="selectGrade('1')" :class="studentGrade === '1' ? 'bg-emerald-500 text-white ring-4 ring-emerald-200' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-800'" class="py-4 font-fredoka font-bold text-2xl rounded-2xl transition duration-150 border-2 border-emerald-100 shadow-sm tap-scale">
                            1
                        </button>
                        <button @click="selectGrade('2')" :class="studentGrade === '2' ? 'bg-emerald-500 text-white ring-4 ring-emerald-200' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-800'" class="py-4 font-fredoka font-bold text-2xl rounded-2xl transition duration-150 border-2 border-emerald-100 shadow-sm tap-scale">
                            2
                        </button>
                        <button @click="selectGrade('3')" :class="studentGrade === '3' ? 'bg-emerald-500 text-white ring-4 ring-emerald-200' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-800'" class="py-4 font-fredoka font-bold text-2xl rounded-2xl transition duration-150 border-2 border-emerald-100 shadow-sm tap-scale">
                            3
                        </button>
                    </div>
                    <p x-show="showGradeError" class="text-xs text-red-500 font-bold mt-1">⚠️ Pilih kelasmu dulu ya!</p>
                </div>
            </div>

            <!-- Tombol Mulai Main -->
            <button @click="startGame()" class="w-full py-4.5 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-fredoka font-bold text-xl rounded-2xl shadow-lg border-b-4 border-emerald-700 transform active:translate-y-0.5 active:border-b-2 transition-all tap-scale">
                MULAI MAIN! 🎮
            </button>
        </div>

        <!-- ================= FASE 2.5: WAITING DUEL SCREEN ================= -->
        <div x-show="gameState === 'waiting_duel'" class="p-8 py-16 text-center space-y-6" x-cloak>
            <div class="text-6xl animate-bounce inline-block">⏳</div>
            <h2 class="font-fredoka font-bold text-2xl text-emerald-850">Menunggu Gurumu...</h2>
            <p class="text-sm text-gray-500 font-medium leading-relaxed">
                Halo <strong x-text="studentName" class="text-emerald-700 font-bold"></strong>, pendaftaran berhasil!<br>
                Harap bersiap di tempat dudukmu ya, duel akan segera dimulai secara serentak oleh gurumu dari layar proyektor. ⚡
            </p>
            <div class="px-4 py-2.5 bg-emerald-50 rounded-xl border border-emerald-100 inline-block text-xs font-semibold text-emerald-800">
                Mode Permainan: Duel Kelas ⚔️
            </div>
        </div>

        <!-- ================= FASE 3: GAMEPLAY SCREEN ================= -->
        <div x-show="gameState === 'playing'" class="flex flex-col justify-between min-h-[480px]">
            <!-- Top HUD -->
            <div class="p-6 bg-emerald-50 border-b-2 border-emerald-100 flex items-center justify-between">
                <!-- Score Display -->
                <div class="flex items-center gap-2">
                    <span class="text-xl">🏆</span>
                    <div>
                        <span class="text-[9px] text-gray-400 font-bold uppercase tracking-wider block leading-none">Skor Detektif</span>
                        <span x-text="score" class="font-fredoka font-bold text-lg text-emerald-800">0</span>
                    </div>
                </div>

                <!-- Combo/Streak display -->
                <div class="flex items-center gap-1.5" x-show="combo >= 3">
                    <span class="text-xl animate-bounce">🔥</span>
                    <span x-text="`x${combo}`" class="font-fredoka font-extrabold text-orange-600 text-lg">x3</span>
                </div>

                <!-- Timer Display -->
                <div class="flex items-center gap-2">
                    <span class="text-xl">⏰</span>
                    <div class="text-right">
                        <span class="text-[9px] text-gray-400 font-bold uppercase tracking-wider block leading-none">Sisa Waktu</span>
                        <span x-text="`${timer}s`" :class="timer <= 5 ? 'text-red-600 animate-pulse font-extrabold' : 'text-emerald-800 font-bold'" class="font-fredoka text-lg">45s</span>
                    </div>
                </div>
            </div>

            <!-- Progress Bar Waktu -->
            <div class="w-full bg-gray-100 h-2">
                <div class="bg-gradient-to-r h-2 transition-all duration-100" :class="timer <= 5 ? 'from-red-500 to-red-600' : 'from-emerald-400 to-teal-500'" :style="`width: ${(timer / maxTimer) * 100}%`"></div>
            </div>

            <!-- Center: Sampah display -->
            <div class="flex-1 p-8 flex flex-col items-center justify-center relative bg-gradient-to-b from-white to-emerald-50/20">
                <!-- Container Kaca Pembesar -->
                <div class="w-44 h-44 bg-white rounded-full border-8 border-emerald-500 shadow-xl flex items-center justify-center p-3 relative transform hover:scale-[1.02] transition-transform duration-200">
                    <template x-if="currentQuestion">
                        <img :src="currentQuestion.gambar" :alt="currentQuestion.nama_sampah" class="w-full h-full object-contain">
                    </template>
                </div>

                <!-- Label Nama Sampah (Besar & Tebal) -->
                <template x-if="currentQuestion">
                    <h2 x-text="currentQuestion.nama_sampah" class="font-fredoka font-extrabold text-2xl text-emerald-950 mt-6 tracking-wide uppercase">Apel</h2>
                </template>
            </div>

            <!-- Bottom: Tombol Bak Sampah (2 bak untuk Kelas 1, 3 bak untuk Kelas 2-3) -->
            <div class="p-6 bg-white border-t border-gray-100 grid gap-4" :class="studentGrade === '1' ? 'grid-cols-2' : 'grid-cols-3'">
                <!-- Bak Hijau (Organik) -->
                <button @click="sortWaste('organik')" class="py-4.5 bg-gradient-to-b from-emerald-400 to-emerald-600 hover:from-emerald-500 hover:to-emerald-700 text-white rounded-3xl shadow-md border-b-4 border-emerald-700 flex flex-col items-center justify-center gap-1 tap-scale">
                    <span class="text-3xl">🟢</span>
                    <span class="font-fredoka font-bold text-xs uppercase tracking-wider">Organik</span>
                </button>

                <!-- Bak Kuning (Anorganik) -->
                <button @click="sortWaste('anorganik')" class="py-4.5 bg-gradient-to-b from-yellow-400 to-yellow-600 hover:from-yellow-500 hover:to-yellow-700 text-white rounded-3xl shadow-md border-b-4 border-yellow-700 flex flex-col items-center justify-center gap-1 tap-scale">
                    <span class="text-3xl">🟡</span>
                    <span class="font-fredoka font-bold text-xs uppercase tracking-wider">Anorganik</span>
                </button>

                <!-- Bak Merah (B3) - Hanya muncul untuk Kelas 2 & 3 -->
                <template x-if="studentGrade !== '1'">
                    <button @click="sortWaste('b3')" class="py-4.5 bg-gradient-to-b from-red-400 to-red-600 hover:from-red-500 hover:to-red-700 text-white rounded-3xl shadow-md border-b-4 border-red-700 flex flex-col items-center justify-center gap-1 tap-scale">
                        <span class="text-3xl">🔴</span>
                        <span class="font-fredoka font-bold text-xs uppercase tracking-wider">B3</span>
                    </button>
                </template>
            </div>
        </div>

        <!-- ================= FASE 4: POST-GAME / RESULTS SCREEN ================= -->
        <div x-show="gameState === 'results'" class="p-8 text-center space-y-6 max-h-[90vh] overflow-y-auto" x-cloak>
            
            <!-- Result Title -->
            <div class="space-y-1">
                <div class="text-6xl animate-bounce">🏆</div>
                <h2 class="font-fredoka font-bold text-2xl text-emerald-800">Bermain Selesai!</h2>
                <p class="text-xs text-gray-400">Kerja bagus, Detektif Cilik!</p>
            </div>

            <!-- Skor & Medal Card -->
            <div class="bg-gradient-to-b from-emerald-500 to-teal-600 rounded-3xl p-6 text-white shadow-lg space-y-2 relative overflow-hidden">
                <p class="text-xs font-semibold opacity-90 uppercase tracking-widest">Skor Akhirmu</p>
                <h3 x-text="score" class="font-fredoka font-extrabold text-5xl tracking-wide">1250</h3>
                
                <div class="pt-4 border-t border-white/20 flex justify-between text-xs font-medium px-2">
                    <div>
                        <span class="opacity-80 block text-[9px] uppercase">Benar</span>
                        <strong x-text="`${correctCount}/${totalSorted}`" class="text-sm">12/15</strong>
                    </div>
                    <div>
                        <span class="opacity-80 block text-[9px] uppercase">Gelar Detektif</span>
                        <strong x-text="detectiveTitle" class="text-sm">Master Kompos</strong>
                    </div>
                    <div x-show="globalRank !== null">
                        <span class="opacity-80 block text-[9px] uppercase">Peringkat</span>
                        <strong x-text="`#${globalRank}`" class="text-sm">#3</strong>
                    </div>
                </div>
            </div>

            <!-- Fakta Edukasi Super Singkat -->
            <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 text-left">
                <h4 class="font-fredoka font-bold text-xs text-emerald-800 mb-1 flex items-center">
                    <span class="mr-1">💡</span> Tahukah Kamu?
                </h4>
                <p x-text="randomFact" class="text-xs text-emerald-950 font-medium leading-relaxed">
                    Botol plastik butuh ratusan tahun untuk hancur di tanah!
                </p>
            </div>

            <!-- Review Kesalahan (Self-Correction) -->
            <div x-show="mistakeList.length > 0" class="text-left space-y-3">
                <h4 class="font-fredoka font-bold text-sm text-gray-800 flex items-center">
                    <span class="mr-1.5">🔍</span> Review Salah (Ayo Belajar!)
                </h4>
                <div class="space-y-2 max-h-48 overflow-y-auto pr-1">
                    <template x-for="item in mistakeList">
                        <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-2xl border border-gray-100">
                            <!-- Image Thumbnail -->
                            <img :src="item.image" class="w-10 h-10 object-contain bg-white rounded-lg border border-gray-100 p-0.5" onerror="this.onerror=null; this.src='https://placehold.co/100?text=';">
                            <div class="flex-1 min-w-0">
                                <h5 x-text="item.name" class="font-bold text-xs text-gray-800 truncate">Kulit Telur</h5>
                                <p class="text-[9px] text-red-500 font-semibold mt-0.5">
                                    Kamu pilih: <span class="capitalize" x-text="item.chosen"></span> • 
                                    <span class="text-emerald-600">Harusnya: <span class="capitalize" x-text="item.correct"></span></span>
                                </p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="grid grid-cols-2 gap-3 pt-3 border-t border-gray-100">
                <button @click="resetGame()" class="py-4 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white font-fredoka font-bold text-sm rounded-2xl shadow-md border-b-4 border-emerald-700 tap-scale">
                    Main Lagi 🔄
                </button>
                <a href="/" class="py-4 bg-gray-100 hover:bg-gray-200 text-gray-700 font-fredoka font-bold text-sm rounded-2xl transition text-center flex items-center justify-center">
                    Keluar 🚪
                </a>
            </div>
        </div>

        <!-- ================= FASE 3.5: POP-UP BELAJAR MODAL (Belajar Mandiri) ================= -->
        <div x-show="showBelajarModal" class="absolute inset-0 bg-emerald-950/90 backdrop-blur-sm z-50 flex items-center justify-center p-6" x-cloak>
            <div class="bg-white rounded-[2.5rem] p-6 text-center w-full max-w-sm border-4 border-emerald-500 shadow-2xl flex flex-col items-center justify-between space-y-5 animate-scale-in">
                
                <!-- Status Header -->
                <div>
                    <span x-show="lastAnswerCorrect" class="text-5xl block animate-bounce">🎉</span>
                    <span x-show="!lastAnswerCorrect" class="text-5xl block animate-pulse">😢</span>
                    <h3 x-text="lastAnswerCorrect ? 'HEBAT! BENAR!' : 'YAH, KURANG TEPAT!'" :class="lastAnswerCorrect ? 'text-emerald-600' : 'text-red-500'" class="font-fredoka font-extrabold text-xl mt-2 tracking-wider"></h3>
                </div>

                <!-- Objek / Sampah info -->
                <div class="bg-emerald-50 p-4 rounded-3xl border border-emerald-100 flex items-center justify-center w-28 h-28 shadow-inner">
                    <template x-if="lastQuestion">
                        <img :src="lastQuestion.gambar" :alt="lastQuestion.nama_sampah" class="w-full h-full object-contain">
                    </template>
                </div>

                <div class="space-y-1">
                    <h4 x-text="lastQuestion ? lastQuestion.nama_sampah : ''" class="font-fredoka font-bold text-lg text-emerald-950 uppercase tracking-wide"></h4>
                    <div>
                        <span x-show="lastQuestion && lastQuestion.kategori === 'organik'" class="px-3 py-1 text-[10px] font-bold text-emerald-800 bg-emerald-100 rounded-full border border-emerald-200">Kategori: Organik 🟢</span>
                        <span x-show="lastQuestion && lastQuestion.kategori === 'anorganik'" class="px-3 py-1 text-[10px] font-bold text-yellow-800 bg-yellow-100 rounded-full border border-yellow-200">Kategori: Anorganik 🟡</span>
                        <span x-show="lastQuestion && lastQuestion.kategori === 'b3'" class="px-3 py-1 text-[10px] font-bold text-red-800 bg-red-100 rounded-full border border-red-200">Kategori: B3 🔴</span>
                    </div>
                </div>

                <!-- Fakta Edukasi Text -->
                <div class="bg-gray-50 rounded-2xl p-4 text-xs font-semibold text-gray-700 leading-relaxed border border-gray-100 text-left">
                    <p x-text="lastQuestion ? lastQuestion.fakta_edukasi : ''"></p>
                </div>

                <!-- Lanjut Button -->
                <button @click="nextFromBelajarModal()" class="w-full py-3.5 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-fredoka font-bold text-base rounded-2xl shadow-md border-b-4 border-emerald-700 transition duration-100 tap-scale flex items-center justify-center gap-1">
                    Lanjut Bermain <span class="text-lg">➡️</span>
                </button>
            </div>
        </div>

    </div>

    <!-- JAVASCRIPT GAME ENGINE LOGIC -->
    <script>
        function gameEngine() {
            return {
                // Sesi data dari server
                sessionCode: '{{ $session->game_code }}',
                allQuestions: @json($questions),
                gameMode: '{{ $session->game_mode }}',
                isStartedFromServer: {{ $session->is_started ? 'true' : 'false' }},
                
                // Game States
                gameState: 'loading',
                loadingProgress: 0,
                
                // Student Identity
                studentName: '',
                studentGrade: '',
                showNameError: false,
                showGradeError: false,

                // Playing states
                filteredQuestions: [],
                currentIndex: 0,
                currentQuestion: null,
                timer: 45,
                maxTimer: 45,
                timerInterval: null,
                
                // Mode Belajar States
                showBelajarModal: false,
                lastQuestion: null,
                lastAnswerCorrect: true,

                // Mode Duel States
                duelPollInterval: null,
                
                // Stats
                score: 0,
                combo: 0,
                correctCount: 0,
                totalSorted: 0,
                globalRank: null,

                // Arrays for submit score analytics
                questionsShownIds: [],
                questionsWrongIds: [],
                mistakeList: [], // { name, category, chosen, correct, image, fact }

                // Facts and Titles list
                randomFact: '',
                detectiveTitle: 'Detektif Cilik',

                // Synth Web Audio Context
                audioCtx: null,

                // Background music state
                bgMusicInterval: null,
                bgMusicIndex: 0,
                // A happy, upbeat melody arpeggio: C4, E4, G4, C5, E5, C5, G4, E4, etc.
                bgMusicNotes: [
                    261.63, 329.63, 392.00, 523.25, 659.25, 523.25, 392.00, 329.63, // C Major
                    293.66, 349.23, 440.00, 587.33, 698.46, 587.33, 440.00, 349.23, // D Minor
                    349.23, 440.00, 523.25, 698.46, 880.00, 698.46, 523.25, 440.00, // F Major
                    392.00, 493.88, 587.33, 783.99, 987.77, 783.99, 587.33, 493.88  // G Major
                ],

                // PRELOAD ASSETS
                preloadAssets() {
                    let loaded = 0;
                    const total = this.allQuestions.length;

                    if (total === 0) {
                        this.loadingProgress = 100;
                        this.gameState = 'welcome';
                        return;
                    }

                    this.allQuestions.forEach((q) => {
                        const img = new Image();
                        img.src = q.gambar;
                        img.onload = () => {
                            loaded++;
                            this.loadingProgress = Math.round((loaded / total) * 100);
                            if (loaded === total) {
                                setTimeout(() => {
                                    this.gameState = 'welcome';
                                }, 500);
                            }
                        };
                        img.onerror = () => {
                            loaded++;
                            this.loadingProgress = Math.round((loaded / total) * 100);
                            if (loaded === total) {
                                setTimeout(() => {
                                    this.gameState = 'welcome';
                                }, 500);
                            }
                        };
                    });
                },

                // SELECT GRADE
                selectGrade(grade) {
                    this.studentGrade = grade;
                    this.showGradeError = false;
                    this.playSynthSound('select');
                },

                // START GAME
                startGame() {
                    // Reset Errors
                    this.showNameError = !this.studentName.trim();
                    this.showGradeError = !this.studentGrade;

                    if (this.showNameError || this.showGradeError) {
                        this.playSynthSound('error');
                        return;
                    }

                    // Setup Audio Context
                    this.initAudio();

                    // Filter Questions based on difficulty
                    // Jika Kelas 1, kita skip soal B3 agar tidak membingungkan anak umur 6-7 tahun
                    if (this.studentGrade === '1') {
                        this.filteredQuestions = this.allQuestions.filter(q => q.kategori !== 'b3');
                        this.timer = 60;
                        this.maxTimer = 60;
                    } else {
                        this.filteredQuestions = [...this.allQuestions];
                        this.timer = 45;
                        this.maxTimer = 45;
                    }

                    // Shuffle filtered questions
                    this.filteredQuestions = this.filteredQuestions.sort(() => Math.random() - 0.5);

                    // LOGIKA MODE DUEL: Tahan jika belum di-start oleh guru
                    if (this.gameMode === 'duel' && !this.isStartedFromServer) {
                        this.gameState = 'waiting_duel';
                        this.playSynthSound('select');

                        // Jalankan polling status setiap 2 detik
                        this.duelPollInterval = setInterval(() => {
                            fetch(`/play/${this.sessionCode}/status`)
                                .then(res => res.json())
                                .then(data => {
                                    if (data.is_started) {
                                        clearInterval(this.duelPollInterval);
                                        this.isStartedFromServer = true;
                                        this.launchActiveGame();
                                    }
                                })
                                .catch(err => console.error('Error polling status:', err));
                        }, 2000);
                    } else {
                        this.launchActiveGame();
                    }
                },

                // LAUNCH ACTIVE GAMEPLAY SCREEN
                launchActiveGame() {
                    // Reset gameplay stats
                    this.score = 0;
                    this.combo = 0;
                    this.correctCount = 0;
                    this.totalSorted = 0;
                    this.currentIndex = 0;
                    this.questionsShownIds = [];
                    this.questionsWrongIds = [];
                    this.mistakeList = [];
                    this.globalRank = null;

                    // Load first question
                    this.loadQuestion();

                    // Change state
                    this.gameState = 'playing';

                    // Play start sound & BGM
                    this.playSynthSound('start');
                    this.startBgMusic();

                    // Start Timer
                    this.startTimerLoop();
                },

                // START TIMER LOOP
                startTimerLoop() {
                    if (this.timerInterval) clearInterval(this.timerInterval);
                    this.timerInterval = setInterval(() => {
                        // Jangan kurangi waktu jika pop-up belajar sedang aktif
                        if (this.showBelajarModal) return;

                        this.timer--;
                        if (this.timer <= 5 && this.timer > 0) {
                            this.playSynthSound('tick');
                        }
                        if (this.timer <= 0) {
                            this.endGame();
                        }
                    }, 1000);
                },

                // LOAD QUESTION
                loadQuestion() {
                    if (this.currentIndex >= this.filteredQuestions.length) {
                        // Jika soal habis sebelum waktu habis, acak ulang dan loop kembali
                        this.filteredQuestions = this.filteredQuestions.sort(() => Math.random() - 0.5);
                        this.currentIndex = 0;
                    }

                    this.currentQuestion = this.filteredQuestions[this.currentIndex];
                    
                    // Rekam ID soal yang ditampilkan
                    if (!this.questionsShownIds.includes(this.currentQuestion.id)) {
                        this.questionsShownIds.push(this.currentQuestion.id);
                    }
                },

                // SORT WASTE (BAK CLICKED)
                sortWaste(chosenCategory) {
                    if (!this.currentQuestion) return;

                    const correctCategory = this.currentQuestion.kategori;
                    this.totalSorted++;

                    let isCorrect = (chosenCategory === correctCategory);

                    if (isCorrect) {
                        // JAWABAN BENAR
                        this.correctCount++;
                        this.combo++;
                        
                        // Kalkulasi poin
                        let pts = 100;
                        if (this.combo >= 3) {
                            // Bonus combo streak 🔥
                            pts += 20;
                        }
                        this.score += pts;

                        this.playSynthSound('correct');
                    } else {
                        // JAWABAN SALAH
                        this.combo = 0;
                        this.score = Math.max(0, this.score - 20); // Pengurangan skor, minimum 0

                        // Rekam ID untuk analytics
                        if (!this.questionsWrongIds.includes(this.currentQuestion.id)) {
                            this.questionsWrongIds.push(this.currentQuestion.id);
                        }

                        // Rekam ke list review kesalahan
                        this.mistakeList.push({
                            name: this.currentQuestion.nama_sampah,
                            category: correctCategory,
                            chosen: chosenCategory,
                            correct: correctCategory,
                            image: this.currentQuestion.gambar,
                            fact: this.currentQuestion.fakta_edukasi
                        });

                        this.playSynthSound('wrong');
                    }

                    // LOGIKA MODE BELAJAR MANDIRI
                    if (this.gameMode === 'belajar') {
                        this.lastQuestion = this.currentQuestion;
                        this.lastAnswerCorrect = isCorrect;
                        this.showBelajarModal = true;
                        
                        // Hentikan BGM agar anak bisa mendengar suara dubbing dengan jelas
                        this.stopBgMusic();

                        // Jalankan Text-to-Speech AI
                        this.speakFact(this.currentQuestion.fakta_edukasi);
                    } else {
                        // Pindah ke soal berikutnya langsung (Mode default/duel)
                        this.currentIndex++;
                        this.loadQuestion();
                    }
                },

                // LANJUT DARI MODAL BELAJAR
                nextFromBelajarModal() {
                    // Batalkan dubbing yang sedang berjalan
                    if (window.speechSynthesis) {
                        window.speechSynthesis.cancel();
                    }

                    this.showBelajarModal = false;
                    
                    // Lanjutkan BGM
                    this.startBgMusic();

                    // Pindah ke soal berikutnya
                    this.currentIndex++;
                    this.loadQuestion();
                },

                // DUBBING AI MENGGUNAKAN WEB SPEECH API SYNTHESIS
                speakFact(text) {
                    if (!window.speechSynthesis) return;

                    // Batalkan dubbing sebelumnya agar tidak bertumpuk
                    window.speechSynthesis.cancel();

                    const utterance = new SpeechSynthesisUtterance(text);
                    utterance.lang = 'id-ID'; // Gunakan suara bahasa Indonesia

                    // Cari suara bahasa Indonesia asli dari list browser jika tersedia
                    const voices = window.speechSynthesis.getVoices();
                    const idVoice = voices.find(voice => voice.lang.includes('id-ID') || voice.lang.includes('id_ID'));
                    if (idVoice) {
                        utterance.voice = idVoice;
                    }

                    // Nada tinggi (1.25) dan tempo dinamis (1.02) agar suara terdengar ceria/gembira layaknya karakter kartun
                    utterance.pitch = 1.25; 
                    utterance.rate = 1.02;

                    window.speechSynthesis.speak(utterance);
                },

                // END GAME
                endGame() {
                    clearInterval(this.timerInterval);
                    if (this.duelPollInterval) {
                        clearInterval(this.duelPollInterval);
                        this.duelPollInterval = null;
                    }
                    if (window.speechSynthesis) {
                        window.speechSynthesis.cancel();
                    }
                    this.gameState = 'results';
                    this.playSynthSound('gameover');
                    
                    // Stop background chiptune music
                    this.stopBgMusic();

                    // Dapatkan Gelar Detektif Cilik berdasarkan performa
                    const accuracy = this.correctCount / (this.totalSorted || 1);
                    if (accuracy >= 0.9) {
                        this.detectiveTitle = '🕵️‍♂️ Detektif Master';
                    } else if (accuracy >= 0.7) {
                        this.detectiveTitle = '🕵️‍♀️ Agen Lingkungan';
                    } else if (accuracy >= 0.4) {
                        this.detectiveTitle = '🌱 Sahabat Bumi';
                    } else {
                        this.detectiveTitle = '🧹 Relawan Hijau';
                    }

                    // Tentukan pesan fakta edukasi acak
                    if (this.mistakeList.length > 0) {
                        // Jika ada kesalahan, tampilkan fakta dari salah satu sampah yang salah
                        const randomMistake = this.mistakeList[Math.floor(Math.random() * this.mistakeList.length)];
                        this.randomFact = randomMistake.fact || 'Sampah organik bisa diolah jadi kompos!';
                    } else if (this.allQuestions.length > 0) {
                        // Jika benar semua, ambil fakta acak global
                        const randomQ = this.allQuestions[Math.floor(Math.random() * this.allQuestions.length)];
                        this.randomFact = randomQ.fakta_edukasi || 'Mari pilah sampah setiap hari!';
                    } else {
                        this.randomFact = 'Sampah plastik butuh ratusan tahun untuk hancur!';
                    }

                    // Submit skor ke backend Laravel
                    this.submitScoreToDatabase();
                },

                // SUBMIT SCORE TO SERVER
                submitScoreToDatabase() {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                    fetch(`/play/${this.sessionCode}/submit-score`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({
                            nama_siswa: this.studentName,
                            kelas: this.studentGrade,
                            skor_akhir: this.score,
                            jawaban_benar: this.correctCount,
                            total_sampah: this.totalSorted || 1,
                            questions_shown: this.questionsShownIds,
                            questions_wrong: this.questionsWrongIds
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            this.globalRank = data.ranking;
                        }
                    })
                    .catch(err => console.error('Error submitting score:', err));
                },

                // RESET GAME (PLAY AGAIN)
                resetGame() {
                    this.gameState = 'welcome';
                    this.score = 0;
                    this.combo = 0;
                    this.correctCount = 0;
                    this.totalSorted = 0;
                    this.globalRank = null;
                    this.mistakeList = [];
                    
                    if (this.duelPollInterval) {
                        clearInterval(this.duelPollInterval);
                        this.duelPollInterval = null;
                    }
                    this.isStartedFromServer = false;
                    if (window.speechSynthesis) {
                        window.speechSynthesis.cancel();
                    }

                    // Stop background chiptune music
                    this.stopBgMusic();
                },

                // BACKGROUND MUSIC METHODS (CHIPTUNE NES-LIKE LOOP)
                startBgMusic() {
                    if (!this.audioCtx) return;
                    
                    this.stopBgMusic();
                    this.bgMusicIndex = 0;

                    // Jalankan interval tempo arpeggio (180ms per note)
                    this.bgMusicInterval = setInterval(() => {
                        if (this.gameState !== 'playing') {
                            this.stopBgMusic();
                            return;
                        }

                        const noteFreq = this.bgMusicNotes[this.bgMusicIndex];
                        this.playSingleBgNote(noteFreq);

                        this.bgMusicIndex = (this.bgMusicIndex + 1) % this.bgMusicNotes.length;
                    }, 180);
                },

                stopBgMusic() {
                    if (this.bgMusicInterval) {
                        clearInterval(this.bgMusicInterval);
                        this.bgMusicInterval = null;
                    }
                },

                playSingleBgNote(frequency) {
                    if (!this.audioCtx || this.audioCtx.state === 'suspended') return;

                    const osc = this.audioCtx.createOscillator();
                    const gain = this.audioCtx.createGain();
                    osc.connect(gain);
                    gain.connect(this.audioCtx.destination);

                    const now = this.audioCtx.currentTime;

                    osc.type = 'triangle'; // Soft retro synth wave
                    osc.frequency.setValueAtTime(frequency, now);

                    // Volume sangat lembut agar tidak menusuk telinga dan bentrok dengan SFX
                    gain.gain.setValueAtTime(0.015, now);
                    gain.gain.exponentialRampToValueAtTime(0.001, now + 0.16);

                    osc.start(now);
                    osc.stop(now + 0.18);
                },

                // ================= SYNTH AUDIO ENGINE (WEB AUDIO API) =================
                // 100% Lag-free, 0 bytes download, works everywhere!
                initAudio() {
                    try {
                        window.AudioContext = window.AudioContext || window.webkitAudioContext;
                        this.audioCtx = new AudioContext();
                    } catch(e) {
                        console.error('Web Audio API not supported');
                    }
                },

                playSynthSound(type) {
                    if (!this.audioCtx) return;

                    // Resume Audio Context jika ditangguhkan browser
                    if (this.audioCtx.state === 'suspended') {
                        this.audioCtx.resume();
                    }

                    const osc = this.audioCtx.createOscillator();
                    const gain = this.audioCtx.createGain();
                    osc.connect(gain);
                    gain.connect(this.audioCtx.destination);

                    const now = this.audioCtx.currentTime;

                    if (type === 'start') {
                        // Suara gembira pembuka
                        osc.type = 'triangle';
                        osc.frequency.setValueAtTime(261.63, now); // C4
                        osc.frequency.exponentialRampToValueAtTime(523.25, now + 0.3); // C5
                        gain.gain.setValueAtTime(0.15, now);
                        gain.gain.exponentialRampToValueAtTime(0.01, now + 0.3);
                        osc.start(now);
                        osc.stop(now + 0.3);
                    }
                    else if (type === 'select') {
                        // Suara klik ringan
                        osc.type = 'sine';
                        osc.frequency.setValueAtTime(440, now); // A4
                        gain.gain.setValueAtTime(0.1, now);
                        gain.gain.exponentialRampToValueAtTime(0.01, now + 0.08);
                        osc.start(now);
                        osc.stop(now + 0.08);
                    }
                    else if (type === 'correct') {
                        // Beep gembira ganda (ting-ting)
                        osc.type = 'sine';
                        osc.frequency.setValueAtTime(523.25, now); // C5
                        osc.frequency.setValueAtTime(659.25, now + 0.1); // E5
                        gain.gain.setValueAtTime(0.15, now);
                        gain.gain.setValueAtTime(0.15, now + 0.1);
                        gain.gain.exponentialRampToValueAtTime(0.01, now + 0.25);
                        osc.start(now);
                        osc.stop(now + 0.25);
                    }
                    else if (type === 'wrong') {
                        // Buzz rendah lembut (tet-tot)
                        osc.type = 'sawtooth';
                        osc.frequency.setValueAtTime(150, now);
                        osc.frequency.linearRampToValueAtTime(100, now + 0.25);
                        gain.gain.setValueAtTime(0.12, now);
                        gain.gain.exponentialRampToValueAtTime(0.01, now + 0.25);
                        osc.start(now);
                        osc.stop(now + 0.25);
                    }
                    else if (type === 'tick') {
                        // Ketukan jam detik terakhir
                        osc.type = 'sine';
                        osc.frequency.setValueAtTime(880, now); // A5
                        gain.gain.setValueAtTime(0.08, now);
                        gain.gain.exponentialRampToValueAtTime(0.001, now + 0.05);
                        osc.start(now);
                        osc.stop(now + 0.05);
                    }
                    else if (type === 'error') {
                        // Buzz peringatan salah form
                        osc.type = 'square';
                        osc.frequency.setValueAtTime(220, now);
                        gain.gain.setValueAtTime(0.1, now);
                        gain.gain.exponentialRampToValueAtTime(0.01, now + 0.15);
                        osc.start(now);
                        osc.stop(now + 0.15);
                    }
                    else if (type === 'gameover') {
                        // Fanfare kecil menang
                        osc.type = 'triangle';
                        osc.frequency.setValueAtTime(523.25, now); // C5
                        osc.frequency.setValueAtTime(659.25, now + 0.15); // E5
                        osc.frequency.setValueAtTime(783.99, now + 0.3); // G5
                        osc.frequency.setValueAtTime(1046.50, now + 0.45); // C6
                        gain.gain.setValueAtTime(0.15, now);
                        gain.gain.exponentialRampToValueAtTime(0.01, now + 0.75);
                        osc.start(now);
                        osc.stop(now + 0.75);
                    }
                }
            };
        }
    </script>
</body>
</html>
