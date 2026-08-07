<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Detektif Sampah - Masuk</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

        <style>
            body {
                font-family: 'Poppins', sans-serif;
            }
            .font-fredoka {
                font-family: 'Fredoka', sans-serif;
            }
        </style>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gradient-to-b from-[#ecfdf5] to-[#d1fae5] text-gray-900 min-h-screen flex items-center justify-center p-6">
        <div class="w-full sm:max-w-md bg-white rounded-[2.5rem] p-8 text-center shadow-2xl border-4 border-emerald-500 relative overflow-hidden flex flex-col items-center">
            
            <!-- Floating Shapes -->
            <div class="absolute -top-10 -left-10 w-20 h-20 bg-emerald-100 rounded-full opacity-60"></div>
            <div class="absolute -bottom-10 -right-10 w-24 h-24 bg-emerald-100 rounded-full opacity-60"></div>

            <!-- Logo / Mascot -->
            <div class="relative z-10 space-y-2 mb-6">
                <a href="/" class="text-6xl block select-none hover:scale-105 transition duration-150">🕵️‍♂️♻️</a>
                <h1 class="font-fredoka font-bold text-2xl text-emerald-800 tracking-wide">DETEKTIF SAMPAH</h1>
                <p class="text-[10px] text-gray-400 font-semibold uppercase tracking-wider">Area Guru & Admin</p>
            </div>

            <!-- Form Content Slot -->
            <div class="w-full text-left relative z-10">
                {{ $slot }}
            </div>
            
            <!-- Footer -->
            <p class="text-[9px] text-gray-300 font-semibold uppercase tracking-wider mt-6 relative z-10">Copyright by Syahrul Umaedi</p>
        </div>
    </body>
</html>
