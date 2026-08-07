<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detektif Sampah - Oops!</title>
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css'])
    
    <!-- Poppins & Fredoka Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0fdf4;
        }
        .font-fredoka {
            font-family: 'Fredoka', sans-serif;
        }
    </style>
</head>
<body class="p-6 flex flex-col items-center justify-center min-h-screen">

    <div class="w-full max-w-sm bg-white rounded-[2.5rem] p-8 text-center shadow-xl border-4 border-dashed border-red-400 relative overflow-hidden">
        
        <!-- Mascot Error -->
        <div class="text-6xl mb-6 select-none animate-bounce">🕵️‍♂️💥</div>

        <h1 class="font-fredoka font-bold text-2xl text-red-600 mb-3 tracking-wide">Akses Ditolak</h1>

        <p class="text-sm text-gray-500 font-medium mb-8 leading-relaxed">
            {{ $message }}
        </p>

        <!-- Button Kembali -->
        <a href="/" class="block w-full py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-fredoka font-semibold text-sm rounded-2xl shadow-md transition duration-150 text-center">
            Kembali ke Beranda
        </a>

        <!-- Footer -->
        <p class="text-[9px] text-gray-300 mt-6 font-semibold uppercase tracking-wider">Detektif Sampah</p>
    </div>

</body>
</html>
