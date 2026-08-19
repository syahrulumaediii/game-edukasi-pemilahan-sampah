<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-fredoka font-semibold text-2xl text-emerald-700 leading-tight">
                {{ __('Super Admin Dashboard Monitoring') }} 🕵️‍♂️⚙️
            </h2>
            <span class="text-xs text-gray-400 font-medium font-mono hidden sm:inline-block">Mode: Pengawas Utama</span>
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

            <!-- Welcome Board -->
            <div class="bg-gradient-to-r from-teal-500 to-emerald-600 rounded-[2rem] p-8 text-white shadow-xl mb-8 relative overflow-hidden">
                <div class="relative z-10 max-w-2xl">
                    <span class="px-2.5 py-1 text-[9px] font-bold uppercase tracking-wider bg-white/20 rounded-full border border-white/10">Panel Super Admin</span>
                    <h3 class="font-fredoka font-bold text-3xl mt-3 mb-2">Halo, Pengawas Detektif!</h3>
                    <p class="text-sm opacity-90 leading-relaxed font-light">
                        Di sini Anda memiliki kontrol penuh atas master bank soal default, pengelolaan akun Guru sekolah sasaran, serta pemantauan aktivitas bermain siswa di seluruh daerah sasaran KKM.
                    </p>
                </div>
                <div class="absolute right-8 -bottom-8 text-[9rem] opacity-15 select-none pointer-events-none font-bold">⚙️</div>
            </div>

            <!-- Gamified Stats Cards (4 Cards) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                <!-- SD Sasaran -->
                <div style="background: linear-gradient(135deg, #10b981, #059669);" class="rounded-3xl p-6 text-white shadow-lg transform hover:scale-[1.02] transition-transform duration-200 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider opacity-90">SD Sasaran</p>
                        <h3 class="text-3xl font-fredoka font-bold mt-2">{{ $totalSchools }}</h3>
                    </div>
                    <div class="p-3 bg-white/20 rounded-2xl text-2xl">🏫</div>
                </div>

                <!-- Guru Aktif -->
                <div style="background: linear-gradient(135deg, #3b82f6, #1d4ed8);" class="rounded-3xl p-6 text-white shadow-lg transform hover:scale-[1.02] transition-transform duration-200 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider opacity-90">Guru Aktif</p>
                        <h3 class="text-3xl font-fredoka font-bold mt-2">{{ $totalTeachers }}</h3>
                    </div>
                    <div class="p-3 bg-white/20 rounded-2xl text-2xl">👨‍🏫</div>
                </div>

                <!-- Sesi Game Kelas -->
                <div style="background: linear-gradient(135deg, #a855f7, #7e22ce);" class="rounded-3xl p-6 text-white shadow-lg transform hover:scale-[1.02] transition-transform duration-200 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider opacity-90">Sesi Kelas</p>
                        <h3 class="text-3xl font-fredoka font-bold mt-2">{{ $totalSessions }}</h3>
                    </div>
                    <div class="p-3 bg-white/20 rounded-2xl text-2xl">🎮</div>
                </div>

                <!-- Siswa Bermain -->
                <div style="background: linear-gradient(135deg, #f97316, #ea580c);" class="rounded-3xl p-6 text-white shadow-lg transform hover:scale-[1.02] transition-transform duration-200 flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider opacity-90">Siswa Bermain</p>
                        <h3 class="text-3xl font-fredoka font-bold mt-2">{{ $totalScores }}</h3>
                    </div>
                    <div class="p-3 bg-white/20 rounded-2xl text-2xl">🧒</div>
                </div>
            </div>

            <!-- Detail Grid Layout -->
            <!-- Detail Grid Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Sesi Game Terbaru -->
                <div class="bg-[#dcf0ea] rounded-3xl p-6 shadow-md border border-[#bedcd2]">
                    <h3 class="font-fredoka font-bold text-lg text-emerald-950 mb-5 flex items-center">
                        <span class="mr-2">🕒</span> Sesi Game Kelas Terbaru
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-xs uppercase font-bold text-emerald-800 border-b border-[#bedcd2] pb-3">
                                    <th class="py-3">Nama Sesi</th>
                                    <th class="py-3">Guru & Sekolah</th>
                                    <th class="py-3">Kode Game</th>
                                    <th class="py-3">Mode Game</th>
                                    <th class="py-3">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#cbe8df] text-sm text-gray-700">
                                @forelse($recentSessions as $session)
                                    <tr class="hover:bg-[#cbe8df]/60 transition duration-150">
                                        <td class="py-3 font-bold text-emerald-950">{{ $session->title }}</td>
                                        <td class="py-3">
                                            <div class="font-bold text-emerald-900">{{ $session->user->name }}</div>
                                            <div class="text-[10px] text-emerald-700 font-bold uppercase tracking-wider mt-0.5">{{ $session->user->nama_sekolah ?? '-' }}</div>
                                        </td>
                                        <td class="py-3">
                                            <span class="px-2.5 py-0.5 bg-emerald-100/80 text-emerald-900 font-mono font-bold text-xs rounded-xl border border-emerald-250 shadow-sm">
                                                {{ $session->game_code }}
                                            </span>
                                        </td>
                                        <td class="py-3">
                                            @if($session->game_mode === 'duel')
                                                <span class="px-2.5 py-0.5 text-[10px] font-bold text-purple-900 bg-purple-100/90 border border-purple-200 rounded-full uppercase tracking-wider inline-flex items-center gap-1">
                                                    ⚔️ Mode Duel
                                                </span>
                                            @elseif($session->game_mode === 'belajar')
                                                <span class="px-2.5 py-0.5 text-[10px] font-bold text-teal-900 bg-teal-100/90 border border-teal-200 rounded-full uppercase tracking-wider inline-flex items-center gap-1">
                                                    📖 Belajar
                                                </span>
                                            @else
                                                <span class="px-2.5 py-0.5 text-[10px] font-bold text-blue-900 bg-blue-100/90 border border-blue-200 rounded-full uppercase tracking-wider inline-flex items-center gap-1">
                                                    🎯 Quizizz
                                                </span>
                                            @endif
                                        </td>
                                        <td class="py-3">
                                            @if($session->is_active)
                                                <span class="px-2 py-0.5 text-[10px] font-bold text-emerald-900 bg-emerald-200/70 rounded-full uppercase">Aktif</span>
                                            @else
                                                <span class="px-2 py-0.5 text-[10px] font-bold text-red-900 bg-red-100 rounded-full uppercase">Tutup</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="py-8 text-center text-gray-500">
                                            <span class="text-3xl block mb-1">🎮</span>
                                            Belum ada sesi game dibuat.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Top Skor Siswa -->
                <div class="bg-[#dcf0ea] rounded-3xl p-6 shadow-md border border-[#bedcd2]">
                    <h3 class="font-fredoka font-bold text-lg text-emerald-950 mb-5 flex items-center">
                        <span class="mr-2">🏆</span> Rekor Skor Tertinggi Siswa
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="text-xs uppercase font-bold text-emerald-800 border-b border-[#bedcd2] pb-3">
                                    <th class="py-3">Nama Siswa</th>
                                    <th class="py-3">Kelas SD</th>
                                    <th class="py-3">Sesi Kelas</th>
                                    <th class="py-3 text-right">Skor</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-[#cbe8df] text-sm text-gray-700">
                                @forelse($topScores as $score)
                                    <tr class="hover:bg-[#cbe8df]/60 transition duration-150">
                                        <td class="py-3 font-bold text-emerald-950 flex items-center">
                                            <span class="mr-1.5 text-base">⭐</span> {{ $score->nama_siswa }}
                                        </td>
                                        <td class="py-3">
                                            <span class="px-2 py-0.5 bg-[#ecf7f4] border border-[#bedcd2] rounded-full text-xs font-bold text-emerald-800">
                                                Kelas {{ $score->kelas }} SD
                                            </span>
                                        </td>
                                        <td class="py-3 text-emerald-900 font-semibold truncate max-w-[120px]">{{ $score->gameSession->title }}</td>
                                        <td class="py-3 text-right font-fredoka font-bold text-emerald-700 text-base">{{ $score->skor_akhir }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-8 text-center text-gray-500">
                                            <span class="text-3xl block mb-1">🧒</span>
                                            Belum ada siswa yang bermain.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
