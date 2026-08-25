<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Papan Peringkat Live - {{ $session->title }}</title>
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
            background-color: #022c22; /* Dark Emerald theme for projector contrast */
            color: white;
            overflow: hidden;
        }
        .font-fredoka {
            font-family: 'Fredoka', sans-serif;
        }
        [x-cloak] { display: none !important; }
    </style>


</head>
<body class="p-6 flex flex-col justify-between min-h-screen" x-data="liveLeaderboard()" x-init="startPolling()">

    <!-- Header Section -->
    <div class="flex items-center justify-between border-b border-emerald-800 pb-4 mb-4 shrink-0">
        <div class="flex items-center gap-3">
            <span class="text-4xl">🕵️‍♂️</span>
            <div>
                <h1 class="font-fredoka font-bold text-2xl text-emerald-300">PAPAN PERINGKAT LIVE</h1>
                <p class="text-xs text-emerald-500 font-medium">Sesi: {{ $session->title }} (Kode Kelas: <span class="font-mono font-bold text-white">{{ $session->game_code }}</span>)</p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <span class="relative flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-orange-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-orange-500"></span>
            </span>
            <span class="text-xs text-orange-400 font-semibold uppercase tracking-wider">Menunggu Nilai...</span>
        </div>
    </div>

    <!-- Center: Main Content Grid (Landscape) -->
    <div class="flex-1 grid grid-cols-1 lg:grid-cols-5 gap-8 items-center justify-center min-h-0">
        <!-- Kiri: Podium Top 3 (cols-3) -->
        <div class="lg:col-span-3 flex flex-col items-center justify-end h-full min-h-[300px]">
            <div class="flex items-end justify-center w-full max-w-lg gap-4">
                
                <!-- Peringkat 2 (Kiri) -->
                <div class="flex flex-col items-center w-1/3">
                    <template x-if="scores[1]">
                        <div class="text-center mb-3 animate-fade-in">
                            <span class="font-fredoka font-bold text-sm text-gray-200 block truncate max-w-[100px]" x-text="scores[1].nama_siswa">Siswa 2</span>
                            <span class="text-xs font-semibold text-emerald-400 font-fredoka" x-text="scores[1].skor_akhir">1000</span>
                        </div>
                    </template>
                    <!-- Tiang 2 -->
                    <div class="w-full bg-gradient-to-t from-emerald-800 to-emerald-700 rounded-t-3xl h-36 flex items-center justify-center shadow-lg border-t-4 border-gray-300">
                        <span class="text-3xl font-fredoka font-extrabold text-gray-300">2</span>
                    </div>
                </div>

                <!-- Peringkat 1 (Tengah - Paling Tinggi) -->
                <div class="flex flex-col items-center w-1/3">
                    <template x-if="scores[0]">
                        <div class="text-center mb-4 animate-bounce">
                            <span class="text-2xl block">👑</span>
                            <span class="font-fredoka font-bold text-base text-white block truncate max-w-[120px]" x-text="scores[0].nama_siswa">Siswa 1</span>
                            <span class="text-sm font-extrabold text-yellow-400 font-fredoka" x-text="scores[0].skor_akhir">1200</span>
                        </div>
                    </template>
                    <!-- Tiang 1 -->
                    <div class="w-full bg-gradient-to-t from-emerald-800 to-emerald-700 rounded-t-3xl h-48 flex items-center justify-center shadow-xl border-t-4 border-yellow-400">
                        <span class="text-4xl font-fredoka font-extrabold text-yellow-400">1</span>
                    </div>
                </div>

                <!-- Peringkat 3 (Kanan) -->
                <div class="flex flex-col items-center w-1/3">
                    <template x-if="scores[2]">
                        <div class="text-center mb-3 animate-fade-in">
                            <span class="font-fredoka font-bold text-sm text-gray-300 block truncate max-w-[100px]" x-text="scores[2].nama_siswa">Siswa 3</span>
                            <span class="text-xs font-semibold text-emerald-400 font-fredoka" x-text="scores[2].skor_akhir">800</span>
                        </div>
                    </template>
                    <!-- Tiang 3 -->
                    <div class="w-full bg-gradient-to-t from-emerald-800 to-emerald-700 rounded-t-3xl h-28 flex items-center justify-center shadow-lg border-t-4 border-amber-600">
                        <span class="text-3xl font-fredoka font-extrabold text-amber-600">3</span>
                    </div>
                </div>

            </div>
        </div>

        <!-- Kanan: Peringkat 4 - 10 (cols-2) -->
        <div class="lg:col-span-2 bg-emerald-950/60 rounded-3xl p-6 border border-emerald-800/80 shadow-2xl h-full flex flex-col justify-start overflow-y-auto max-h-[400px]">
            <h3 class="font-fredoka font-bold text-emerald-400 text-sm uppercase tracking-wider mb-4 border-b border-emerald-900 pb-2">Top 10 Detektif Cilik</h3>
            
            <div class="space-y-2.5">
                <template x-for="(score, index) in scores.slice(3)">
                    <div class="flex items-center justify-between p-2.5 bg-emerald-900/30 rounded-2xl border border-emerald-850 flex-row">
                        <div class="flex items-center gap-3">
                            <span class="font-fredoka font-bold text-emerald-500 w-6 text-center text-sm" x-text="index + 4">4</span>
                            <div>
                                <span class="font-bold text-sm text-white block" x-text="score.nama_siswa"></span>
                                <span class="text-[9px] text-emerald-400 uppercase tracking-widest font-semibold" x-text="`Kelas ${score.kelas} SD`"></span>
                            </div>
                        </div>
                        <span class="font-fredoka font-bold text-sm text-emerald-300 text-right" x-text="score.skor_akhir"></span>
                    </div>
                </template>

                <template x-if="scores.length === 0">
                    <div class="text-center py-12 text-gray-500 text-sm">
                        Belum ada siswa yang menyelesaikan permainan.
                    </div>
                </template>
            </div>
        </div>
    </div>

    <!-- Footer Projector Mode -->
    <div class="mt-4 border-t border-emerald-900 pt-3 flex justify-between items-center text-[10px] text-emerald-600 shrink-0 font-semibold uppercase tracking-wider">
        <span>TAMPILAN PROYEKTOR KELAS</span>
        <span>Yuk pilah sampah, jaga sekolah kita! 🏫🌿</span>
    </div>

    <!-- POLLING LEADERBOARD LOGIC -->
    <script>
        function liveLeaderboard() {
            return {
                gameCode: '{{ $session->game_code }}',
                scores: @json($scores),
                pollingInterval: null,

                startPolling() {
                    this.pollingInterval = setInterval(() => {
                        this.fetchLatestScores();
                    }, 3000); // Polling setiap 3 detik
                },

                fetchLatestScores() {
                    fetch(`/play/${this.gameCode}/live`, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.scores) {
                            this.scores = data.scores;
                        }
                    })
                    .catch(err => console.error('Error fetching live leaderboard:', err));
                }
            }
        }
    </script>

    
</body>
</html>
