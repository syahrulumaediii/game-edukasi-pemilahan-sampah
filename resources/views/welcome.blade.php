<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detektif Sampah - Ayo Pilah Sampah!</title>
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
            background-color: #ecfdf5; /* Emerald mint green bg */
        }
        .font-fredoka {
            font-family: 'Fredoka', sans-serif;
        }
        .tap-scale:active {
            transform: scale(0.95);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col items-center justify-center p-6 bg-gradient-to-b from-[#ecfdf5] to-[#d1fae5]">

    <div class="w-full max-w-md bg-white rounded-[2.5rem] p-8 text-center shadow-2xl border-4 border-emerald-500 relative overflow-hidden space-y-8" x-data="{ gameCode: '' }">
        <!-- Floating shapes -->
        <div class="absolute -top-10 -left-10 w-20 h-20 bg-emerald-100 rounded-full opacity-60"></div>
        <div class="absolute -bottom-10 -right-10 w-24 h-24 bg-emerald-100 rounded-full opacity-60"></div>

        <!-- Header / Logo -->
        <div class="relative z-10 space-y-2">
            <span class="text-7xl block animate-bounce">🕵️‍♂️♻️</span>
            <h1 class="font-fredoka font-bold text-3xl text-emerald-800 tracking-wide">DETEKTIF SAMPAH</h1>
            <p class="text-xs text-gray-400 font-medium">Bantu Detektif memilah sampah dan jaga sekolah kita tetap bersih!</p>
        </div>

        <!-- Section Siswa: Input Kode Kelas -->
        <div class="bg-emerald-50/50 p-6 rounded-3xl border border-emerald-100 space-y-4">
            <h3 class="font-fredoka font-bold text-base text-emerald-900">Punya Kode Sesi dari Guru?</h3>
            <div class="space-y-3">
                <input type="text" x-model="gameCode" placeholder="Masukkan 6 Huruf Kode..." maxlength="6" class="w-full border-2 border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500 rounded-2xl p-4 text-center font-mono font-bold text-xl uppercase tracking-widest text-emerald-800 placeholder:text-gray-300 shadow-inner">
                
                <button @click="if(gameCode.trim()) { window.location.href = '/play/' + gameCode.trim().toUpperCase() }" :disabled="!gameCode.trim()" class="w-full py-4 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-fredoka font-bold text-lg rounded-2xl shadow-md border-b-4 border-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed transition-all tap-scale">
                    Mulai Bermain! 🎮
                </button>
            </div>
        </div>

        <!-- Section Guru / Admin -->
        <div class="pt-4 border-t border-gray-100 space-y-3">
            <p class="text-xs text-gray-400 font-medium">Apakah kamu Guru atau Admin?</p>
            <div class="flex gap-2">
                @auth
                    <a href="{{ url('/dashboard') }}" class="flex-1 py-3 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-fredoka font-semibold text-xs rounded-xl border border-emerald-200 transition text-center flex items-center justify-center gap-1.5">
                        Masuk Dashboard 🏫
                    </a>
                @else
                    <a href="{{ route('login') }}" class="flex-1 py-3 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-fredoka font-semibold text-xs rounded-xl border border-emerald-200 transition text-center flex items-center justify-center gap-1.5">
                        Login Akun 🔑
                    </a>
                @endauth
            </div>
        </div>

        <!-- Footer -->
        <p class="text-[9px] text-gray-300 font-semibold uppercase tracking-wider">Copyright © KKM Universitas. Semua Hak Dilindungi.</p>
    </div>

</body>
</html>
