<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
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
        @keyframes floatHint {
            0% { transform: translateY(12px) scale(0.92); opacity: 0; }
            60% { transform: translateY(-4px) scale(1.02); opacity: 1; }
            100% { transform: translateY(0) scale(1); opacity: 1; }
        }
        .animate-float-hint {
            animation: floatHint 0.35s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }
        @keyframes subtleShake {
            0%, 100% { transform: translateX(0); }
            20%, 60% { transform: translateX(-6px) rotate(-1deg); }
            40%, 80% { transform: translateX(6px) rotate(1deg); }
        }
        .animate-subtle-shake {
            animation: subtleShake 0.4s ease-in-out;
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
            <p class="text-xs text-gray-400 font-semibold uppercase tracking-widest">Memuat Gambar Sampah & Musik</p>
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
                        <button @click="selectGrade('1')" :class="studentGrade === '1' ? 'bg-emerald-500 text-white ring-4 ring-emerald-200' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-800'" class="py-3.5 flex flex-col items-center justify-center rounded-2xl transition duration-150 border-2 border-emerald-100 shadow-sm tap-scale">
                            <span class="font-fredoka font-bold text-2xl">1</span>
                            <span class="text-[9px] font-semibold opacity-85 mt-0.5">2 Bak Sampah</span>
                        </button>
                        <button @click="selectGrade('2')" :class="studentGrade === '2' ? 'bg-emerald-500 text-white ring-4 ring-emerald-200' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-800'" class="py-3.5 flex flex-col items-center justify-center rounded-2xl transition duration-150 border-2 border-emerald-100 shadow-sm tap-scale">
                            <span class="font-fredoka font-bold text-2xl">2</span>
                            <span class="text-[9px] font-semibold opacity-85 mt-0.5">3 Bak (+B3)</span>
                        </button>
                        <button @click="selectGrade('3')" :class="studentGrade === '3' ? 'bg-emerald-500 text-white ring-4 ring-emerald-200' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-800'" class="py-3.5 flex flex-col items-center justify-center rounded-2xl transition duration-150 border-2 border-emerald-100 shadow-sm tap-scale">
                            <span class="font-fredoka font-bold text-2xl">3</span>
                            <span class="text-[9px] font-semibold opacity-85 mt-0.5">3 Bak (+B3)</span>
                        </button>
                    </div>
                    <p x-show="showGradeError" class="text-xs text-red-500 font-bold mt-1">⚠️ Pilih kelasmu dulu ya!</p>
                </div>

                <!-- Pengaturan Suara Awal -->
                <div class="flex items-center justify-between bg-emerald-50/60 p-3 rounded-2xl border border-emerald-100 text-xs">
                    <span class="font-fredoka font-bold text-emerald-900 flex items-center gap-1.5">
                        <span>🔊</span> Suara & Musik Anak
                    </span>
                    <div class="flex items-center gap-2">
                        <button type="button" @click="toggleMusic()" class="px-2.5 py-1 rounded-xl text-xs font-bold transition flex items-center gap-1" :class="!isMusicMuted ? 'bg-emerald-500 text-white shadow-sm' : 'bg-gray-200 text-gray-500'">
                            <span x-text="!isMusicMuted ? '🎵 Musik Aktif' : '🔇 Musik Mati'"></span>
                        </button>
                    </div>
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
        <div x-show="gameState === 'playing'" class="flex flex-col justify-between min-h-[490px] relative">
            
            <!-- Top HUD -->
            <div class="p-4 sm:p-5 bg-emerald-50 border-b-2 border-emerald-100 flex items-center justify-between">
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

                <!-- Sound Controls Quick Toggle (Music & SFX) -->
                <div class="flex items-center gap-1 bg-white/90 px-2 py-1 rounded-2xl border border-emerald-200 shadow-xs">
                    <button @click="toggleMusic()" :title="isMusicMuted ? 'Nyalakan Musik' : 'Matikan Musik'" class="text-xs p-1 rounded-lg hover:bg-emerald-100 transition tap-scale" :class="isMusicMuted ? 'opacity-40 grayscale' : ''">
                        <span x-show="!isMusicMuted">🎵</span>
                        <span x-show="isMusicMuted">🔇</span>
                    </button>
                    <button @click="toggleSfx()" :title="isSfxMuted ? 'Nyalakan Suara' : 'Matikan Suara'" class="text-xs p-1 rounded-lg hover:bg-emerald-100 transition tap-scale" :class="isSfxMuted ? 'opacity-40 grayscale' : ''">
                        <span x-show="!isSfxMuted">🔊</span>
                        <span x-show="isSfxMuted">🔈</span>
                    </button>
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

            <!-- Banner Edukasi Informatif saat Salah Jawab (Rapi di bawah progress bar, tidak menimpa HUD / Skor) -->
            <div x-show="showWrongBanner" 
                 x-transition:enter="transition ease-out duration-250 transform" 
                 x-transition:enter-start="opacity-0 -translate-y-2 scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
                 x-transition:leave="transition ease-in duration-200 transform" 
                 x-transition:leave-start="opacity-100 scale-100" 
                 x-transition:leave-end="opacity-0 scale-95" 
                 class="mx-4 mt-2.5 p-3 bg-amber-50 rounded-2xl border-2 border-amber-300 shadow-md flex items-center gap-3 animate-float-hint z-20 shrink-0" 
                 x-cloak>
                <div class="text-2xl animate-bounce">💡</div>
                <div class="flex-1 text-left">
                    <p class="text-[10px] font-extrabold text-amber-800 uppercase tracking-wide flex items-center gap-1">
                        <span>Info Detektif Cilik</span>
                        <span class="text-xs">📢</span>
                    </p>
                    <p class="text-xs sm:text-sm font-bold text-gray-800 leading-tight" x-html="wrongBannerText"></p>
                </div>
                <button @click="showWrongBanner = false" class="text-gray-400 hover:text-gray-600 font-bold text-xs p-1">✕</button>
            </div>

            <!-- Center: Sampah display -->
            <div class="flex-1 p-4 sm:p-6 flex flex-col items-center justify-center relative bg-gradient-to-b from-white to-emerald-50/20" :class="isShaking ? 'animate-subtle-shake' : ''">
                <!-- Container Kaca Pembesar (Circular Frame - Jauh Lebih Besar & Jelas untuk HP) -->
                <div class="w-56 h-56 sm:w-64 sm:h-64 bg-white rounded-full border-8 sm:border-[10px] border-emerald-500 shadow-2xl flex items-center justify-center relative overflow-hidden aspect-square transform hover:scale-[1.02] transition-transform duration-200 group">
                    <template x-if="currentQuestion">
                        <img :src="getImageUrl(currentQuestion.gambar)" 
                             :alt="currentQuestion.nama_sampah" 
                             class="w-full h-full object-cover select-none pointer-events-none transition-transform duration-300"
                             x-on:error="$event.target.src='https://placehold.co/300?text=' + encodeURIComponent(currentQuestion ? currentQuestion.nama_sampah : 'Sampah')">
                    </template>
                    <!-- Glass Reflection Effect -->
                    <div class="absolute inset-0 rounded-full bg-gradient-to-tr from-transparent via-white/10 to-white/40 pointer-events-none"></div>
                </div>

                <!-- Label Nama Sampah (Besar & Tebal) -->
                <template x-if="currentQuestion">
                    <h2 x-text="currentQuestion.nama_sampah" class="font-fredoka font-extrabold text-2xl sm:text-3xl text-emerald-950 mt-4 tracking-wide uppercase drop-shadow-xs text-center px-2">Apel</h2>
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
                    <button @click="sortWaste('b3')" class="py-4.5 bg-gradient-to-b from-red-400 to-red-600 hover:from-red-500 hover:to-red-700 text-white rounded-3xl shadow-md border-b-4 border-red-700 flex flex-col items-center justify-center gap-0.5 tap-scale px-1">
                        <span class="text-3xl">🔴</span>
                        <span class="font-fredoka font-bold text-xs uppercase tracking-wider leading-none">B3</span>
                        <span class="text-[7.5px] font-bold opacity-90 uppercase tracking-tight text-center leading-none mt-0.5">Bahan Berbahaya & Beracun</span>
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
                            <div class="w-12 h-12 bg-white rounded-xl border border-gray-200 p-1 shrink-0 overflow-hidden flex items-center justify-center shadow-xs">
                                <img :src="getImageUrl(item.image)" class="w-full h-full object-contain rounded-lg" x-on:error="$event.target.src='https://placehold.co/100?text=' + encodeURIComponent(item.name)">
                            </div>
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
        <div x-show="showBelajarModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 backdrop-blur-none"
             x-transition:enter-end="opacity-100 backdrop-blur-md"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 backdrop-blur-md"
             x-transition:leave-end="opacity-0 backdrop-blur-none"
             class="fixed inset-0 bg-emerald-950/80 backdrop-blur-md z-50 flex items-center justify-center p-4 overflow-y-auto" 
             x-cloak>
            <div x-show="showBelajarModal"
                 x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 scale-90 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-90 translate-y-4"
                 class="bg-white rounded-[2rem] p-5 sm:p-6 text-center w-full max-w-sm border-4 border-emerald-500 shadow-2xl flex flex-col items-center justify-between space-y-3.5 my-auto max-h-[92vh] overflow-y-auto relative">
                
                <!-- Status Header -->
                <div class="space-y-0.5">
                    <span x-show="lastAnswerCorrect" class="text-4xl block animate-bounce">🎉</span>
                    <span x-show="!lastAnswerCorrect" class="text-4xl block animate-pulse">😢</span>
                    <h3 x-text="lastAnswerCorrect ? 'HEBAT! BENAR!' : 'YAH, KURANG TEPAT!'" 
                        :class="lastAnswerCorrect ? 'text-emerald-600' : 'text-red-500'" 
                        class="font-fredoka font-extrabold text-xl mt-1 tracking-wider"></h3>
                </div>

                <!-- Objek / Sampah info (Besar & Jelas) -->
                <div class="bg-emerald-50 rounded-2xl border-2 border-emerald-200 flex items-center justify-center w-32 h-32 sm:w-36 sm:h-36 shadow-inner shrink-0 overflow-hidden relative">
                    <template x-if="lastQuestion">
                        <img :src="getImageUrl(lastQuestion.gambar)" 
                             :alt="lastQuestion.nama_sampah" 
                             class="w-full h-full object-cover"
                             x-on:error="$event.target.src='https://placehold.co/200?text=' + encodeURIComponent(lastQuestion ? lastQuestion.nama_sampah : 'Sampah')">
                    </template>
                </div>

                <div class="space-y-1">
                    <h4 x-text="lastQuestion ? lastQuestion.nama_sampah : ''" class="font-fredoka font-extrabold text-lg text-emerald-950 uppercase tracking-wide"></h4>
                    <div>
                        <span x-show="lastQuestion && lastQuestion.kategori === 'organik'" class="px-3 py-1 text-xs font-bold text-emerald-800 bg-emerald-100 rounded-full border border-emerald-200 shadow-xs inline-flex items-center gap-1">
                            <span>Kategori: Organik</span> <span>🟢</span>
                        </span>
                        <span x-show="lastQuestion && lastQuestion.kategori === 'anorganik'" class="px-3 py-1 text-xs font-bold text-yellow-800 bg-yellow-100 rounded-full border border-yellow-200 shadow-xs inline-flex items-center gap-1">
                            <span>Kategori: Anorganik</span> <span>🟡</span>
                        </span>
                        <span x-show="lastQuestion && lastQuestion.kategori === 'b3'" class="px-3 py-1 text-xs font-bold text-red-800 bg-red-100 rounded-full border border-red-200 shadow-xs inline-flex items-center gap-1">
                            <span>Kategori: B3</span> <span>🔴</span>
                        </span>
                    </div>
                </div>

                <!-- Fakta Edukasi Text -->
                <div class="bg-emerald-50/60 rounded-2xl p-3.5 text-xs font-medium text-emerald-950 leading-relaxed border border-emerald-100 text-left w-full shadow-inner">
                    <div class="flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-emerald-700 mb-1">
                        <span>💡</span>
                        <span>Tahukah Kamu?</span>
                    </div>
                    <p x-text="lastQuestion ? lastQuestion.fakta_edukasi : ''" class="font-semibold"></p>
                </div>

                <!-- Lanjut Button -->
                <button @click="nextFromBelajarModal()" class="w-full py-3.5 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-fredoka font-bold text-base rounded-2xl shadow-lg border-b-4 border-emerald-700 transition active:translate-y-0.5 tap-scale flex items-center justify-center gap-1.5 shrink-0">
                    <span>Lanjut Bermain</span>
                    <span class="text-lg">➡️</span>
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
                getImageUrl(path) {
                    if (!path) return 'https://placehold.co/100?text=';
                    return path.startsWith('/') ? path : '/' + path;
                },
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
                isShaking: false,
                
                // Mode Belajar States
                showBelajarModal: false,
                lastQuestion: null,
                lastAnswerCorrect: true,

                // Educational Wrong Hint Banner
                showWrongBanner: false,
                wrongBannerText: '',
                wrongBannerTimeout: null,

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

                // Audio State & Engine
                audioCtx: null,
                bgmMasterGain: null,
                sfxMasterGain: null,
                isMusicMuted: false,
                isSfxMuted: false,
                bgMusicInterval: null,
                bgMusicStep: 0,

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
                        img.src = this.getImageUrl(q.gambar);
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

                // AUDIO CONTROLS
                toggleMusic() {
                    this.isMusicMuted = !this.isMusicMuted;
                    if (this.bgmMasterGain && this.audioCtx) {
                        this.bgmMasterGain.gain.setValueAtTime(this.isMusicMuted ? 0 : 0.12, this.audioCtx.currentTime);
                    }
                    if (!this.isMusicMuted && this.gameState === 'playing' && !this.bgMusicInterval) {
                        this.startBgMusic();
                    }
                },

                toggleSfx() {
                    this.isSfxMuted = !this.isSfxMuted;
                    if (this.sfxMasterGain && this.audioCtx) {
                        this.sfxMasterGain.gain.setValueAtTime(this.isSfxMuted ? 0 : 0.2, this.audioCtx.currentTime);
                    }
                },

                getKategoriLabel(kategori) {
                    if (kategori === 'organik') return 'Organik (Bak Hijau 🟢)';
                    if (kategori === 'anorganik') return 'Anorganik (Bak Kuning 🟡)';
                    if (kategori === 'b3') return 'B3 Berbahaya (Bak Merah 🔴)';
                    return kategori;
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
                    this.showWrongBanner = false;

                    // Load first question
                    this.loadQuestion();

                    // Change state
                    this.gameState = 'playing';

                    // Play start sound & BGM Backsound
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
                    const itemName = this.currentQuestion.nama_sampah;
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
                        this.showWrongBanner = false;
                    } else {
                        // JAWABAN SALAH
                        this.combo = 0;
                        this.score = Math.max(0, this.score - 20); // Pengurangan skor, minimum 0

                        // Animasi visual shake
                        this.isShaking = true;
                        setTimeout(() => { this.isShaking = false; }, 400);

                        // Rekam ID untuk analytics
                        if (!this.questionsWrongIds.includes(this.currentQuestion.id)) {
                            this.questionsWrongIds.push(this.currentQuestion.id);
                        }

                        // Rekam ke list review kesalahan
                        this.mistakeList.push({
                            name: itemName,
                            category: correctCategory,
                            chosen: chosenCategory,
                            correct: correctCategory,
                            image: this.currentQuestion.gambar,
                            fact: this.currentQuestion.fakta_edukasi
                        });

                        // Mainkan Audio Lucu & Menarik Saat Salah (Cartoon Uh-Oh & Boing!)
                        this.playSynthSound('wrong');

                        // Di Mode Standar / Duel: Tampilkan Banner Edukasi & Ucapkan Suara Petunjuk
                        if (this.gameMode !== 'belajar') {
                            this.showInformativeWrongHint(itemName, correctCategory);
                            this.speakWrongHint(itemName, correctCategory);
                        } else {
                            this.showWrongBanner = false;
                        }
                    }

                    // LOGIKA MODE BELAJAR MANDIRI
                    if (this.gameMode === 'belajar') {
                        this.showWrongBanner = false;
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

                // TAMPILKAN BANNER PETUNJUK EDUKASI
                showInformativeWrongHint(itemName, correctCategory) {
                    if (this.wrongBannerTimeout) {
                        clearTimeout(this.wrongBannerTimeout);
                    }
                    const badgeClass = correctCategory === 'organik' 
                        ? 'text-emerald-700 bg-emerald-100' 
                        : (correctCategory === 'anorganik' ? 'text-yellow-800 bg-yellow-100' : 'text-red-700 bg-red-100');
                    
                    this.wrongBannerText = `Ups! <strong>${itemName}</strong> harusnya masuk ke <span class="px-1.5 py-0.5 rounded-md ${badgeClass} font-extrabold uppercase">${correctCategory}</span> ya!`;
                    this.showWrongBanner = true;

                    this.wrongBannerTimeout = setTimeout(() => {
                        this.showWrongBanner = false;
                    }, 3200);
                },

                // SUARA PETUNJUK EDUKATIF RAMAH ANAK SAAT SALAH
                speakWrongHint(itemName, correctCategory) {
                    if (this.isSfxMuted || !window.speechSynthesis) return;

                    // Ducking BGM: kecilkan backsound saat dubbing berbicara
                    this.duckBgMusic(true);

                    window.speechSynthesis.cancel();
                    const text = `Ups! ${itemName} itu sampah ${correctCategory} ya!`;
                    const utterance = new SpeechSynthesisUtterance(text);
                    utterance.lang = 'id-ID';

                    const voices = window.speechSynthesis.getVoices();
                    const idVoice = voices.find(v => v.lang.includes('id-ID') || v.lang.includes('id_ID'));
                    if (idVoice) utterance.voice = idVoice;

                    // Karakter suara kartun ceria & ramah
                    utterance.pitch = 1.35;
                    utterance.rate = 1.1;

                    utterance.onend = () => {
                        this.duckBgMusic(false);
                    };
                    utterance.onerror = () => {
                        this.duckBgMusic(false);
                    };

                    window.speechSynthesis.speak(utterance);
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
                    if (this.isSfxMuted || !window.speechSynthesis) return;

                    // Batalkan dubbing sebelumnya agar tidak bertumpuk
                    window.speechSynthesis.cancel();

                    const utterance = new SpeechSynthesisUtterance(text);
                    utterance.lang = 'id-ID';

                    const voices = window.speechSynthesis.getVoices();
                    const idVoice = voices.find(voice => voice.lang.includes('id-ID') || voice.lang.includes('id_ID'));
                    if (idVoice) {
                        utterance.voice = idVoice;
                    }

                    // Nada ramah ceria
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
                    this.showWrongBanner = false;
                    this.gameState = 'results';
                    this.playSynthSound('gameover');
                    
                    // Stop background music
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
                        const randomMistake = this.mistakeList[Math.floor(Math.random() * this.mistakeList.length)];
                        this.randomFact = randomMistake.fact || 'Sampah organik bisa diolah jadi kompos!';
                    } else if (this.allQuestions.length > 0) {
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
                    this.showWrongBanner = false;
                    
                    if (this.duelPollInterval) {
                        clearInterval(this.duelPollInterval);
                        this.duelPollInterval = null;
                    }
                    this.isStartedFromServer = false;
                    if (window.speechSynthesis) {
                        window.speechSynthesis.cancel();
                    }

                    // Stop background music
                    this.stopBgMusic();
                },

                // ================= SYNTH AUDIO & MUSIC ENGINE =================
                initAudio() {
                    try {
                        if (!this.audioCtx) {
                            window.AudioContext = window.AudioContext || window.webkitAudioContext;
                            this.audioCtx = new AudioContext();

                            // Master BGM Gain Node
                            this.bgmMasterGain = this.audioCtx.createGain();
                            this.bgmMasterGain.gain.setValueAtTime(this.isMusicMuted ? 0 : 0.12, this.audioCtx.currentTime);
                            this.bgmMasterGain.connect(this.audioCtx.destination);

                            // Master SFX Gain Node
                            this.sfxMasterGain = this.audioCtx.createGain();
                            this.sfxMasterGain.gain.setValueAtTime(this.isSfxMuted ? 0 : 0.25, this.audioCtx.currentTime);
                            this.sfxMasterGain.connect(this.audioCtx.destination);
                        }
                        if (this.audioCtx.state === 'suspended') {
                            this.audioCtx.resume();
                        }
                    } catch(e) {
                        console.error('Web Audio API not supported', e);
                    }
                },

                // DUCK BGM VOLUME SAAT SUARA EDUKASI BERBICARA
                duckBgMusic(shouldDuck) {
                    if (!this.bgmMasterGain || !this.audioCtx || this.isMusicMuted) return;
                    const now = this.audioCtx.currentTime;
                    const targetVolume = shouldDuck ? 0.02 : 0.12;
                    this.bgmMasterGain.gain.cancelScheduledValues(now);
                    this.bgmMasterGain.gain.linearRampToValueAtTime(targetVolume, now + 0.15);
                },

                // ================= CHEERFUL KIDS BACKSOUND (BGM) =================
                // Melodi bertempo ceria khas game anak-anak (Mario/Animal Crossing style)
                startBgMusic() {
                    if (this.isMusicMuted) return;
                    this.initAudio();
                    this.stopBgMusic();

                    this.bgMusicStep = 0;

                    // 16-Step Melodic Pattern (C Major Progression)
                    // [Melody Freq, Bass Freq, IsPercussionBeat]
                    const pattern = [
                        // Bar 1 (C Major)
                        { mel: 523.25, bass: 130.81, perc: true },  // C5, C3
                        { mel: 659.25, bass: null,   perc: false }, // E5
                        { mel: 783.99, bass: null,   perc: true },  // G5 (beat)
                        { mel: 659.25, bass: null,   perc: false }, // E5
                        // Bar 2 (F Major)
                        { mel: 523.25, bass: 174.61, perc: true },  // C5, F3
                        { mel: 587.33, bass: null,   perc: false }, // D5
                        { mel: 659.25, bass: null,   perc: true },  // E5 (beat)
                        { mel: 783.99, bass: null,   perc: false }, // G5
                        // Bar 3 (G Major)
                        { mel: 880.00, bass: 196.00, perc: true },  // A5, G3
                        { mel: 783.99, bass: null,   perc: false }, // G5
                        { mel: 659.25, bass: null,   perc: true },  // E5 (beat)
                        { mel: 523.25, bass: null,   perc: false }, // C5
                        // Bar 4 (Turnaround to C)
                        { mel: 587.33, bass: 130.81, perc: true },  // D5, C3
                        { mel: 659.25, bass: null,   perc: false }, // E5
                        { mel: 587.33, bass: null,   perc: true },  // D5 (beat)
                        { mel: 523.25, bass: null,   perc: false }  // C5
                    ];

                    const stepDurationMs = 155; // ~97 BPM cheerful bounce

                    this.bgMusicInterval = setInterval(() => {
                        if (this.gameState !== 'playing') {
                            this.stopBgMusic();
                            return;
                        }

                        const currentNote = pattern[this.bgMusicStep % pattern.length];
                        this.playBgMusicStep(currentNote);
                        this.bgMusicStep++;
                    }, stepDurationMs);
                },

                stopBgMusic() {
                    if (this.bgMusicInterval) {
                        clearInterval(this.bgMusicInterval);
                        this.bgMusicInterval = null;
                    }
                },

                playBgMusicStep(noteData) {
                    if (!this.audioCtx || this.audioCtx.state === 'suspended' || this.isMusicMuted) return;

                    const now = this.audioCtx.currentTime;

                    // 1. Lead Chime / Marimba Melody
                    if (noteData.mel) {
                        const melOsc = this.audioCtx.createOscillator();
                        const melGain = this.audioCtx.createGain();
                        melOsc.type = 'triangle';
                        melOsc.frequency.setValueAtTime(noteData.mel, now);

                        melGain.gain.setValueAtTime(0.08, now);
                        melGain.gain.exponentialRampToValueAtTime(0.001, now + 0.14);

                        melOsc.connect(melGain);
                        melGain.connect(this.bgmMasterGain);

                        melOsc.start(now);
                        melOsc.stop(now + 0.15);
                    }

                    // 2. Bouncy Bassline
                    if (noteData.bass) {
                        const bassOsc = this.audioCtx.createOscillator();
                        const bassGain = this.audioCtx.createGain();
                        bassOsc.type = 'sine';
                        bassOsc.frequency.setValueAtTime(noteData.bass, now);

                        bassGain.gain.setValueAtTime(0.09, now);
                        bassGain.gain.exponentialRampToValueAtTime(0.001, now + 0.28);

                        bassOsc.connect(bassGain);
                        bassGain.connect(this.bgmMasterGain);

                        bassOsc.start(now);
                        bassOsc.stop(now + 0.29);
                    }

                    // 3. Playful Hi-hat / Percussion Click
                    if (noteData.perc) {
                        const percOsc = this.audioCtx.createOscillator();
                        const percGain = this.audioCtx.createGain();
                        percOsc.type = 'square';
                        percOsc.frequency.setValueAtTime(1200, now);
                        percOsc.frequency.exponentialRampToValueAtTime(300, now + 0.04);

                        percGain.gain.setValueAtTime(0.015, now);
                        percGain.gain.exponentialRampToValueAtTime(0.0001, now + 0.04);

                        percOsc.connect(percGain);
                        percGain.connect(this.bgmMasterGain);

                        percOsc.start(now);
                        percOsc.stop(now + 0.04);
                    }
                },

                // ================= SOUND EFFECTS (SFX) =================
                playSynthSound(type) {
                    if (this.isSfxMuted) return;
                    this.initAudio();
                    if (!this.audioCtx || this.audioCtx.state === 'suspended') return;

                    const now = this.audioCtx.currentTime;

                    if (type === 'start') {
                        // Fanfare gembira pembuka
                        const osc = this.audioCtx.createOscillator();
                        const gain = this.audioCtx.createGain();
                        osc.type = 'triangle';
                        osc.frequency.setValueAtTime(261.63, now); // C4
                        osc.frequency.exponentialRampToValueAtTime(523.25, now + 0.35); // C5
                        gain.gain.setValueAtTime(0.2, now);
                        gain.gain.exponentialRampToValueAtTime(0.01, now + 0.35);
                        osc.connect(gain);
                        gain.connect(this.sfxMasterGain);
                        osc.start(now);
                        osc.stop(now + 0.35);
                    }
                    else if (type === 'select') {
                        // Klik tombol ceria
                        const osc = this.audioCtx.createOscillator();
                        const gain = this.audioCtx.createGain();
                        osc.type = 'sine';
                        osc.frequency.setValueAtTime(587.33, now); // D5
                        gain.gain.setValueAtTime(0.12, now);
                        gain.gain.exponentialRampToValueAtTime(0.01, now + 0.08);
                        osc.connect(gain);
                        gain.connect(this.sfxMasterGain);
                        osc.start(now);
                        osc.stop(now + 0.08);
                    }
                    else if (type === 'correct') {
                        // Suara Bintang Menang (Ding-Ding-Ting!)
                        const notes = [523.25, 659.25, 783.99, 1046.50]; // C5, E5, G5, C6
                        notes.forEach((freq, i) => {
                            const osc = this.audioCtx.createOscillator();
                            const gain = this.audioCtx.createGain();
                            osc.type = 'sine';
                            osc.frequency.setValueAtTime(freq, now + (i * 0.06));

                            gain.gain.setValueAtTime(0.18, now + (i * 0.06));
                            gain.gain.exponentialRampToValueAtTime(0.001, now + (i * 0.06) + 0.22);

                            osc.connect(gain);
                            gain.connect(this.sfxMasterGain);
                            osc.start(now + (i * 0.06));
                            osc.stop(now + (i * 0.06) + 0.23);
                        });
                    }
                    else if (type === 'wrong') {
                        // ================= AUDIO KARTUN LUCU & MENARIK SAAT SALAH =================
                        // Efek "Wah-Wah-Wah" Kartun Melorot + "Boing!" Pegas yang menghibur anak
                        const wahNotes = [
                            { startFreq: 311.13, endFreq: 290, time: 0 },    // Eb4 -> D4
                            { startFreq: 293.66, endFreq: 270, time: 0.12 }, // D4 -> Db4
                            { startFreq: 277.18, endFreq: 230, time: 0.24 }  // Db4 -> Bb3
                        ];

                        wahNotes.forEach(w => {
                            const osc = this.audioCtx.createOscillator();
                            const gain = this.audioCtx.createGain();
                            osc.type = 'sawtooth';

                            // Efek vibrato meliuk
                            osc.frequency.setValueAtTime(w.startFreq, now + w.time);
                            osc.frequency.linearRampToValueAtTime(w.endFreq, now + w.time + 0.11);

                            gain.gain.setValueAtTime(0.12, now + w.time);
                            gain.gain.exponentialRampToValueAtTime(0.001, now + w.time + 0.11);

                            osc.connect(gain);
                            gain.connect(this.sfxMasterGain);
                            osc.start(now + w.time);
                            osc.stop(now + w.time + 0.12);
                        });

                        // Suara Pegas Kartun "Boing!" di akhir (0.36s)
                        const boingOsc = this.audioCtx.createOscillator();
                        const boingGain = this.audioCtx.createGain();
                        boingOsc.type = 'sine';

                        boingOsc.frequency.setValueAtTime(190, now + 0.36);
                        boingOsc.frequency.exponentialRampToValueAtTime(540, now + 0.44);
                        boingOsc.frequency.exponentialRampToValueAtTime(260, now + 0.65);

                        boingGain.gain.setValueAtTime(0.16, now + 0.36);
                        boingGain.gain.exponentialRampToValueAtTime(0.001, now + 0.65);

                        boingOsc.connect(boingGain);
                        boingGain.connect(this.sfxMasterGain);

                        boingOsc.start(now + 0.36);
                        boingOsc.stop(now + 0.66);
                    }
                    else if (type === 'tick') {
                        // Tik-tok jam detektif
                        const osc = this.audioCtx.createOscillator();
                        const gain = this.audioCtx.createGain();
                        osc.type = 'sine';
                        osc.frequency.setValueAtTime(880, now);
                        gain.gain.setValueAtTime(0.08, now);
                        gain.gain.exponentialRampToValueAtTime(0.001, now + 0.05);
                        osc.connect(gain);
                        gain.connect(this.sfxMasterGain);
                        osc.start(now);
                        osc.stop(now + 0.05);
                    }
                    else if (type === 'error') {
                        // Buzz peringatan form
                        const osc = this.audioCtx.createOscillator();
                        const gain = this.audioCtx.createGain();
                        osc.type = 'square';
                        osc.frequency.setValueAtTime(220, now);
                        gain.gain.setValueAtTime(0.1, now);
                        gain.gain.exponentialRampToValueAtTime(0.01, now + 0.15);
                        osc.connect(gain);
                        gain.connect(this.sfxMasterGain);
                        osc.start(now);
                        osc.stop(now + 0.15);
                    }
                    else if (type === 'gameover') {
                        // Fanfare kemenangan selesai game
                        const fanfare = [523.25, 659.25, 783.99, 1046.50];
                        fanfare.forEach((freq, idx) => {
                            const osc = this.audioCtx.createOscillator();
                            const gain = this.audioCtx.createGain();
                            osc.type = 'triangle';
                            osc.frequency.setValueAtTime(freq, now + (idx * 0.14));

                            gain.gain.setValueAtTime(0.18, now + (idx * 0.14));
                            gain.gain.exponentialRampToValueAtTime(0.001, now + (idx * 0.14) + 0.4);

                            osc.connect(gain);
                            gain.connect(this.sfxMasterGain);
                            osc.start(now + (idx * 0.14));
                            osc.stop(now + (idx * 0.14) + 0.41);
                        });
                    }
                }
            };
        }
    </script>
</body>
</html>
