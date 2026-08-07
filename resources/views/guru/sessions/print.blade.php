<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak QR Code - {{ $session->title }}</title>
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css'])
    
    <!-- Poppins & Fredoka Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: white;
        }
        .font-fredoka {
            font-family: 'Fredoka', sans-serif;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                background: white;
            }
            .print-card {
                border: 2px dashed #059669 !important;
                box-shadow: none !important;
                margin: 0 !important;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body class="p-8 flex flex-col items-center justify-center min-h-screen bg-gray-50">

    <!-- Tombol Cetak Manual (Hanya muncul di layar, tidak saat print) -->
    <div class="no-print mb-8 flex gap-3">
        <button onclick="window.print()" class="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-fredoka font-semibold text-sm rounded-2xl shadow-md transition duration-150">
            🖨️ Cetak Stiker Sekarang
        </button>
        <button onclick="window.close()" class="px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-fredoka font-semibold text-sm rounded-2xl transition duration-150">
            Tutup Halaman
        </button>
    </div>

    <!-- Sticker QR Card Layout -->
    <div class="print-card w-full max-w-md bg-white border-4 border-dashed border-emerald-500 rounded-[2.5rem] p-8 text-center shadow-xl relative overflow-hidden">
        
        <!-- Frame background accent (hidden in print depending on printer, styled as print-safe) -->
        <div class="absolute -top-10 -right-10 w-24 h-24 bg-emerald-100 rounded-full opacity-50"></div>
        <div class="absolute -bottom-10 -left-10 w-24 h-24 bg-emerald-100 rounded-full opacity-50"></div>

        <!-- Mascot / Title -->
        <div class="flex items-center justify-center gap-2 mb-4">
            <span class="text-3xl">🕵️‍♂️</span>
            <h1 class="font-fredoka font-bold text-2xl text-emerald-800 tracking-wide">DETEKTIF SAMPAH</h1>
        </div>

        <p class="text-xs text-gray-400 font-medium mb-6 uppercase tracking-widest border-b border-gray-100 pb-3">Sesi Kelas: {{ $session->title }}</p>

        <!-- QR Code Container -->
        <div class="inline-block p-4 bg-emerald-50 rounded-[2rem] border-2 border-emerald-100 mb-6 shadow-inner">
            {!! QrCode::size(240)->margin(1)->backgroundColor(240, 253, 244)->color(6, 78, 59)->generate(route('play', $session->game_code)) !!}
        </div>

        <!-- Kode Alternatif -->
        <div class="mb-6">
            <span class="text-[10px] text-gray-400 uppercase tracking-widest block mb-1">Kode Kelas</span>
            <span class="px-4 py-1.5 bg-emerald-100 text-emerald-800 font-mono font-bold text-lg rounded-xl inline-block border border-emerald-200">
                {{ $session->game_code }}
            </span>
        </div>

        <!-- Simple Step Instructions (Children friendly) -->
        <div class="bg-gray-50 rounded-3xl p-4 text-left border border-gray-100">
            <h3 class="font-fredoka font-bold text-sm text-emerald-800 mb-2.5 flex items-center justify-center md:justify-start">
                Cara Bermain Cepat:
            </h3>
            <ol class="text-xs text-gray-500 space-y-2 font-medium">
                <li class="flex items-start">
                    <span class="font-fredoka font-bold text-emerald-600 bg-emerald-100 w-5 h-5 rounded-full inline-flex items-center justify-center mr-2 text-[10px] shrink-0">1</span>
                    <span>Pindai (Scan) QR Code di atas menggunakan kamera handphone/tablet.</span>
                </li>
                <li class="flex items-start">
                    <span class="font-fredoka font-bold text-emerald-600 bg-emerald-100 w-5 h-5 rounded-full inline-flex items-center justify-center mr-2 text-[10px] shrink-0">2</span>
                    <span>Masukkan **Nama Lengkap** dan pilih **Kelasmu**.</span>
                </li>
                <li class="flex items-start">
                    <span class="font-fredoka font-bold text-emerald-600 bg-emerald-100 w-5 h-5 rounded-full inline-flex items-center justify-center mr-2 text-[10px] shrink-0">3</span>
                    <span>Tekan tombol **"Mulai Main"** dan bantu Detektif memilah sampah! 🎮</span>
                </li>
            </ol>
        </div>

        <!-- Footer -->
        <p class="text-[9px] text-gray-300 mt-6 font-semibold uppercase tracking-wider">Copyright © KKM Universitas. Semua Hak Dilindungi.</p>
    </div>

    <!-- Auto Print Script -->
    <script>
        window.onload = function() {
            // Berikan jeda 1 detik agar QR Code ter-render sempurna sebelum print dialog muncul
            setTimeout(function() {
                window.print();
            }, 800);
        };
    </script>
</body>
</html>
