<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-fredoka font-semibold text-2xl text-emerald-700 leading-tight">
                    {{ __('Dashboard Pemantauan Guru') }} 👨‍🏫
                </h2>
                <p class="text-xs text-gray-400 mt-1">Kelola kelas, bagikan misi pemilahan, dan pantau pemahaman siswa secara langsung.</p>
            </div>
            <a href="{{ route('guru.sessions.create') }}" class="px-5 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-650 hover:to-emerald-700 text-white font-fredoka font-bold text-sm rounded-2xl shadow-md border-b-4 border-emerald-800 transition duration-150 flex items-center justify-center gap-1.5 self-start sm:self-auto">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Buat Sesi Kelas Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Success -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border-l-4 border-emerald-500 rounded-r-lg text-emerald-800 shadow-sm flex items-center">
                    <svg class="w-5 h-5 mr-2 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <!-- Welcome Mission Control Banner -->
            <div class="bg-gradient-to-r from-emerald-500 to-teal-600 rounded-[2rem] p-8 text-white shadow-xl mb-8 relative overflow-hidden">
                <div class="relative z-10 max-w-2xl">
                    <span class="px-2.5 py-1 text-[9px] font-bold uppercase tracking-wider bg-white/20 rounded-full border border-white/10">Markas Detektif Sampah</span>
                    <h3 class="font-fredoka font-bold text-3xl mt-3 mb-2">Selamat Datang Kembali, {{ Auth::user()->name }}!</h3>
                    <p class="text-sm opacity-90 leading-relaxed font-light">
                        Sekolah Aktif: <strong class="underline decoration-yellow-300 decoration-2">{{ Auth::user()->nama_sekolah ?? 'Belum Diatur' }}</strong>. 
                        Ayo buka sesi kelas baru, arahkan proyektor, dan latih motorik serta kesadaran lingkungan anak-anak lewat kompetisi memilah sampah yang menyenangkan!
                    </p>
                </div>
                <div class="absolute right-8 -bottom-8 text-[9rem] opacity-15 select-none pointer-events-none font-bold animate-pulse">🕵️‍♂️</div>
            </div>

            <!-- Gamified Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Stat 1: Total Kelas -->
                <div style="background: linear-gradient(135deg, #10b981, #059669);" class="rounded-3xl p-6 text-white shadow-lg transform hover:scale-[1.02] transition-transform duration-200 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider opacity-90">Total Sesi Kelas</p>
                        <h4 class="text-4xl font-fredoka font-bold mt-2">{{ $totalSessions }}</h4>
                    </div>
                    <div class="p-3 bg-white/20 rounded-2xl text-3xl">🏫</div>
                </div>

                <!-- Stat 2: Total Siswa Bermain -->
                <div style="background: linear-gradient(135deg, #0ea5e9, #2563eb);" class="rounded-3xl p-6 text-white shadow-lg transform hover:scale-[1.02] transition-transform duration-200 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider opacity-90">Siswa Telah Bermain</p>
                        <h4 class="text-4xl font-fredoka font-bold mt-2">{{ $totalPlays }}</h4>
                    </div>
                    <div class="p-3 bg-white/20 rounded-2xl text-3xl">🧒</div>
                </div>

                <!-- Stat 3: Rata-Rata Skor -->
                <div style="background: linear-gradient(135deg, #f97316, #d97706);" class="rounded-3xl p-6 text-white shadow-lg transform hover:scale-[1.02] transition-transform duration-200 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider opacity-90">Skor Rata-Rata Kelas</p>
                        <h4 class="text-4xl font-fredoka font-bold mt-2">{{ $averageScore }}</h4>
                    </div>
                    <div class="p-3 bg-white/20 rounded-2xl text-3xl">🏆</div>
                </div>
            </div>

            <!-- Detail Grid Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- KIRI: Daftar Sesi Kelas (Col-span 2) -->
                <div class="bg-[#dcf0ea] rounded-3xl p-6 shadow-md border border-[#bedcd2] lg:col-span-2">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="font-fredoka font-bold text-lg text-emerald-950 flex items-center">
                            <span class="mr-2">🎮</span> Sesi Kelas Aktif Saat Ini
                        </h3>
                        <a href="{{ route('guru.sessions.index') }}" class="text-xs font-bold text-emerald-700 hover:text-emerald-800 flex items-center">
                            Lihat Semua <svg class="w-3.5 h-3.5 ml-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/></svg>
                        </a>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-xs uppercase font-bold text-emerald-800 border-b border-[#bedcd2] pb-3">
                                    <th class="py-3">Nama Sesi</th>
                                    <th class="py-3">Kode Game</th>
                                    <th class="py-3">Soal Aktif</th>
                                    <th class="py-3">Mode Game</th>
                                    <th class="py-3">Status</th>
                                    <th class="py-3 text-right">Misi Bermain</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#cbe8df] text-sm text-gray-700">
                                @forelse($sessions->take(5) as $session)
                                    <tr class="hover:bg-[#cbe8df]/60 transition duration-150">
                                        <td class="py-4 font-bold text-emerald-950">{{ $session->title }}</td>
                                        <td class="py-4">
                                            <span class="px-3 py-1 bg-emerald-100/80 text-emerald-900 font-mono font-bold text-xs rounded-xl border border-emerald-200 shadow-sm">
                                                {{ $session->game_code }}
                                            </span>
                                        </td>
                                        <td class="py-4 font-semibold text-emerald-900">{{ $session->questions()->count() }} Soal</td>
                                        <td class="py-4">
                                            @if($session->game_mode === 'duel')
                                                <span class="px-2.5 py-1 text-[10px] font-bold text-purple-900 bg-purple-100/90 border border-purple-200 rounded-full uppercase tracking-wider inline-flex items-center gap-1">
                                                    ⚔️ Mode Duel
                                                </span>
                                            @elseif($session->game_mode === 'belajar')
                                                <span class="px-2.5 py-1 text-[10px] font-bold text-teal-900 bg-teal-100/90 border border-teal-200 rounded-full uppercase tracking-wider inline-flex items-center gap-1">
                                                    📖 Belajar
                                                </span>
                                            @else
                                                <span class="px-2.5 py-1 text-[10px] font-bold text-blue-900 bg-blue-100/90 border border-blue-200 rounded-full uppercase tracking-wider inline-flex items-center gap-1">
                                                    🎯 Quizizz
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-4">
                                            @if($session->is_active)
                                                <span class="px-2.5 py-0.5 text-[10px] font-bold text-emerald-900 bg-emerald-200/70 rounded-full uppercase tracking-wider">Dibuka</span>
                                            @else
                                                <span class="px-2.5 py-0.5 text-[10px] font-bold text-red-900 bg-red-100 rounded-full uppercase tracking-wider">Ditutup</span>
                                            @endif
                                        </td>
                                        <td class="py-4 text-right">
                                            <div class="inline-flex gap-1.5">
                                                <!-- Detail -->
                                                <a href="{{ route('guru.sessions.show', $session) }}" class="px-2.5 py-1 bg-[#ecf7f4] hover:bg-[#d8ece7] text-emerald-800 font-bold rounded-xl text-xs transition duration-150 border border-[#bedcd2] shadow-sm">
                                                    Detail
                                                </a>
                                                <!-- Mulai -->
                                                <a href="{{ route('play', $session->game_code) }}" target="_blank" class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white font-fredoka font-bold rounded-xl text-xs shadow-sm transition duration-150 border-b-2 border-emerald-800">
                                                    🎮 Mulai
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-12 text-center text-gray-500">
                                            <span class="text-4xl block mb-2">📁</span>
                                            Belum ada sesi kelas dibuat. Silakan klik **Buat Sesi Kelas Baru** di atas!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- KANAN: Aktivitas Nilai Siswa Terbaru -->
                <div class="bg-[#dcf0ea] rounded-3xl p-6 shadow-md border border-[#bedcd2]">
                    <h3 class="font-fredoka font-bold text-lg text-emerald-950 mb-6 flex items-center">
                        <span class="mr-2">⚡</span> Skor Terbaru Masuk
                    </h3>
                    <div class="space-y-4 max-h-[360px] overflow-y-auto pr-1">
                        @forelse($recentScores as $score)
                            <div class="flex items-center justify-between p-3.5 bg-[#ecf7f4] rounded-2.5xl border border-[#bedcd2] hover:bg-[#dcefe9] hover:scale-[1.01] transition duration-150">
                                <div class="min-w-0">
                                    <h5 class="font-bold text-emerald-950 text-sm truncate flex items-center gap-1.5">
                                        ⭐ {{ $score->nama_siswa }}
                                    </h5>
                                    <p class="text-[10px] text-emerald-800 mt-1 font-bold uppercase">
                                        Kelas {{ $score->kelas }} SD • {{ $score->gameSession->title }}
                                    </p>
                                </div>
                                <div class="text-right shrink-0">
                                    <span class="font-fredoka font-bold text-base text-emerald-700 block leading-tight">{{ $score->skor_akhir }}</span>
                                    <span class="text-[9px] text-emerald-650 font-bold">{{ $score->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        @empty
                            <div class="py-12 text-center text-emerald-700/60 text-sm">
                                <span class="text-3xl block mb-2">🧒</span>
                                Belum ada riwayat siswa bermain di sesi kelas Anda.
                            </div>
                        @endforelse
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</x-app-layout>
