<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-fredoka font-semibold text-2xl text-emerald-700 leading-tight">
                    {{ $session->title }}
                </h2>
                <p class="text-xs text-gray-400 mt-1">Dibuat oleh Anda pada {{ $session->created_at->format('d M Y, H:i') }} WIB</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('play', $session->game_code) }}" target="_blank" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-fredoka font-semibold text-xs rounded-xl shadow-md transition duration-150 flex items-center">
                    <span class="mr-1.5 text-base">🎮</span> Mulai Game
                </a>
                <a href="{{ route('guru.sessions.print', $session) }}" target="_blank" class="px-4 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-fredoka font-semibold text-xs rounded-xl border border-emerald-200 transition duration-150 flex items-center">
                    <span class="mr-1.5 text-base">🖨️</span> Cetak Stiker QR
                </a>
                <a href="{{ route('play.live-leaderboard', $session->game_code) }}" target="_blank" class="px-4 py-2 bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 text-white font-fredoka font-semibold text-xs rounded-xl shadow-md transition duration-150 flex items-center">
                    <span class="mr-1.5 text-base">📺</span> Mode Proyektor Kelas
                </a>
                <a href="{{ route('guru.sessions.index') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-fredoka font-semibold text-xs rounded-xl transition duration-150 flex items-center">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data="{ 
        tab: 'scores', 
        duelStarted: {{ $session->is_started ? 'true' : 'false' }}, 
        gameMode: '{{ $session->game_mode }}',
        toggleDuel() {
            fetch('{{ route('guru.sessions.toggle-status', $session) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    this.duelStarted = data.is_started;
                }
            });
        }
    }">
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

            <!-- Atas: QR Code Sharing & Status Sesi -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                <!-- QR Code & Link Sharing Card -->
                <div class="bg-white rounded-3xl p-6 shadow-md border border-gray-100 md:col-span-2 flex flex-col md:flex-row items-center gap-6">
                    <div class="bg-emerald-50 p-4 rounded-2xl flex items-center justify-center border border-emerald-100 shadow-inner">
                        <!-- QR Code Generator -->
                        {!! QrCode::size(160)->margin(1)->backgroundColor(240, 253, 244)->color(6, 78, 59)->generate(route('play', $session->game_code)) !!}
                    </div>
                    <div class="flex-1 w-full text-center md:text-left">
                        <span class="px-2.5 py-1 text-[10px] font-bold tracking-wider uppercase bg-emerald-100 text-emerald-800 rounded-full">Bagikan ke Murid</span>
                        <h3 class="font-fredoka font-bold text-xl text-gray-800 mt-2 mb-1.5">Scan QR & Main Instan</h3>
                        <p class="text-xs text-gray-400 mb-4 font-light">Tampilkan QR Code ini di proyektor kelas atau bagikan link game langsung ke grup murid/orang tua untuk mulai memilah sampah!</p>
                        
                        <!-- Share Link Input & Copy & Print -->
                        <div class="flex flex-col sm:flex-row gap-3 items-center max-w-lg">
                            <div x-data="{ copied: false, shareUrl: '{{ route('play', $session->game_code) }}' }" class="flex items-center bg-gray-50 rounded-2xl p-1 border border-gray-100 flex-1 w-full">
                                <input type="text" x-model="shareUrl" readonly class="bg-transparent border-none focus:outline-none focus:ring-0 text-xs text-gray-500 font-mono flex-1 px-3 py-1">
                                <button @click="navigator.clipboard.writeText(shareUrl); copied = true; setTimeout(() => copied = false, 2000)" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-fredoka font-semibold text-xs shadow-sm transition duration-150">
                                    <span x-show="!copied">Salin Link</span>
                                    <span x-show="copied" x-cloak>Tersalin!</span>
                                </button>
                            </div>
                            <a href="{{ route('guru.sessions.print', $session) }}" target="_blank" class="px-4 py-3 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-2xl font-fredoka font-semibold text-xs transition duration-150 w-full sm:w-auto text-center">
                                🖨️ Cetak Stiker
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Info Ringkasan Status Sesi -->
                <div class="bg-white rounded-3xl p-6 shadow-md border border-gray-100 flex flex-col justify-between">
                    <div>
                        <h4 class="font-semibold text-gray-400 text-xs uppercase tracking-wider mb-3">Informasi Sesi</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Kode Sesi:</span>
                                <strong class="font-mono text-emerald-700">{{ $session->game_code }}</strong>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Mode Game:</span>
                                <span class="font-semibold text-gray-800 capitalize" x-text="gameMode === 'quizizz' ? 'Quizizz (Mandiri)' : (gameMode === 'belajar' ? 'Belajar (AI Suara)' : 'Duel (Terkontrol)')"></span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Status Akses:</span>
                                @if($session->is_active)
                                    <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">DIBUKA (Siswa Bisa Masuk)</span>
                                @else
                                    <span class="text-xs font-bold text-red-600 bg-red-50 px-2 py-0.5 rounded-full">DITUTUP (Terkunci)</span>
                                @endif
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Jumlah Soal Aktif:</span>
                                <span class="font-semibold text-gray-800">{{ $session->questions->count() }} Soal</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-400">Total Siswa Main:</span>
                                <span class="font-semibold text-gray-800">{{ $session->gameScores->count() }} Kali</span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-gray-50 flex flex-col gap-2">
                        <!-- Tampilkan tombol Mulai Duel jika mode game nya adalah duel -->
                        <template x-if="gameMode === 'duel'">
                            <button @click="toggleDuel()" :class="duelStarted ? 'bg-red-650 hover:bg-red-700' : 'bg-emerald-650 hover:bg-emerald-700'" class="w-full text-center py-2.5 text-white font-fredoka font-bold text-xs rounded-xl shadow-sm border-b-2 transition duration-150">
                                <span x-show="!duelStarted">⚡ Mulai Duel (Izinkan Murid)</span>
                                <span x-show="duelStarted" x-cloak>🛑 Hentikan Duel (Kunci Layar)</span>
                            </button>
                        </template>

                        <a href="{{ route('guru.sessions.edit', $session) }}" class="text-center py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 font-fredoka font-semibold text-xs rounded-xl transition duration-150 border border-gray-100">
                            Edit Sesi & Ubah Mode
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tabbed Navigation -->
            <div class="flex space-x-1 bg-gray-200/50 p-1 rounded-2xl mb-6 max-w-md">
                <button @click="tab = 'scores'" :class="tab === 'scores' ? 'bg-white text-emerald-700 shadow-sm' : 'text-gray-500 hover:text-gray-700'" class="flex-1 py-2 font-fredoka font-bold text-sm rounded-xl transition-all duration-200">
                    🏆 Hasil Skor Kelas
                </button>
                <button @click="tab = 'questions'" :class="tab === 'questions' ? 'bg-white text-emerald-700 shadow-sm' : 'text-gray-500 hover:text-gray-700'" class="flex-1 py-2 font-fredoka font-bold text-sm rounded-xl transition-all duration-200">
                    🗑️ Soal Sampah
                </button>
                <button @click="tab = 'analytics'" :class="tab === 'analytics' ? 'bg-white text-emerald-700 shadow-sm' : 'text-gray-500 hover:text-gray-700'" class="flex-1 py-2 font-fredoka font-bold text-sm rounded-xl transition-all duration-200">
                    📊 Analisis Miskonsepsi
                </button>
            </div>

            <!-- TAB 1: HASIL SKOR KELAS -->
            <div x-show="tab === 'scores'" class="bg-white rounded-3xl p-6 shadow-md border border-gray-100">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-fredoka font-bold text-lg text-gray-800">Rekapitulasi Nilai Siswa SD (Kelas 1–3)</h3>
                    @if($session->gameScores->count() > 0)
                        <a href="{{ route('guru.sessions.export', $session) }}" class="px-3.5 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-fredoka font-semibold rounded-xl text-xs border border-emerald-100 transition duration-150 flex items-center">
                            📥 Ekspor Excel (CSV)
                        </a>
                    @endif
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-xs uppercase font-semibold text-gray-400 border-b border-gray-100 pb-3">
                                <th class="py-3">Peringkat</th>
                                <th class="py-3">Nama Siswa</th>
                                <th class="py-3">Kelas SD</th>
                                <th class="py-3">Jawaban Benar</th>
                                <th class="py-3 text-right">Skor Akhir</th>
                                <th class="py-3 text-right">Waktu Main</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 text-sm text-gray-600">
                            @forelse($session->gameScores->sortByDesc('skor_akhir') as $index => $score)
                                <tr class="{{ $index < 3 ? 'bg-emerald-50/20 font-medium' : '' }}">
                                    <td class="py-3">
                                        @if($index == 0)
                                            <span class="text-base">🥇</span> <span class="font-fredoka font-bold text-emerald-700">1</span>
                                        @elseif($index == 1)
                                            <span class="text-base">🥈</span> <span class="font-fredoka font-bold text-gray-700">2</span>
                                        @elseif($index == 2)
                                            <span class="text-base">🥉</span> <span class="font-fredoka font-bold text-amber-700">3</span>
                                        @else
                                            <span class="pl-2.5 font-bold text-gray-400">{{ $index + 1 }}</span>
                                        @endif
                                    </td>
                                    <td class="py-3 font-semibold text-gray-800">{{ $score->nama_siswa }}</td>
                                    <td class="py-3">
                                        <span class="px-2 py-0.5 bg-gray-100 rounded-full text-xs font-semibold text-gray-600">
                                            Kelas {{ $score->kelas }}
                                        </span>
                                    </td>
                                    <td class="py-3">{{ $score->jawaban_benar }} dari {{ $score->total_sampah }} Sampah</td>
                                    <td class="py-3 text-right font-fredoka font-bold text-emerald-600 text-base">{{ $score->skor_akhir }}</td>
                                    <td class="py-3 text-right text-gray-400 text-xs">{{ $score->created_at->format('d M Y, H:i') }} WIB</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-gray-400">
                                        Belum ada data nilai. Bagikan QR Code di atas agar anak-anak mulai bermain!
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TAB 2: SOAL SAMPAH -->
            <div x-show="tab === 'questions'" class="space-y-6" x-cloak>
                <div class="bg-white rounded-3xl p-6 shadow-md border border-gray-100">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                        <div>
                            <h3 class="font-fredoka font-bold text-lg text-gray-800">Daftar Soal Sampah Kelas Ini</h3>
                            <p class="text-xs text-gray-400 mt-1">Soal-soal yang akan muncul di layar handphone anak-anak saat bermain di sesi ini.</p>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            <!-- Import Default jika belum terisi maksimal -->
                            <form action="{{ route('guru.sessions.import-default', $session) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-fredoka font-semibold text-xs rounded-xl border border-emerald-100 transition duration-150">
                                    📥 Impor Soal Default
                                </button>
                            </form>
                            <!-- Tambah Custom -->
                            <a href="{{ route('guru.sessions.questions.create', $session) }}" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-fredoka font-semibold text-xs rounded-xl shadow-md transition duration-150 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                </svg>
                                Buat Soal Kustom
                            </a>
                        </div>
                    </div>

                    <!-- Grid Soal -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @forelse($session->questions as $question)
                            <div class="bg-gray-50 rounded-3xl p-4 shadow-sm border border-gray-100 flex flex-col justify-between transform hover:scale-[1.01] transition-transform duration-150 relative">
                                <div>
                                    <!-- Image Thumbnail -->
                                    <div class="w-full aspect-square bg-white rounded-2xl mb-3 flex items-center justify-center overflow-hidden border border-gray-100 relative group">
                                        @if($question->gambar)
                                            <img src="{{ asset($question->gambar) }}" alt="{{ $question->nama_sampah }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" onerror="this.onerror=null; this.src='https://placehold.co/150?text={{ urlencode($question->nama_sampah) }}';">
                                        @else
                                            <div class="text-3xl">🗑️</div>
                                        @endif

                                        <!-- Badge Kategori -->
                                        <div class="absolute top-2 left-2">
                                            @if($question->kategori === 'organik')
                                                <span class="px-2 py-0.5 text-[9px] font-bold text-emerald-800 bg-emerald-100 rounded-full">Organik</span>
                                            @elseif($question->kategori === 'anorganik')
                                                <span class="px-2 py-0.5 text-[9px] font-bold text-yellow-800 bg-yellow-100 rounded-full">Anorganik</span>
                                            @else
                                                <span class="px-2 py-0.5 text-[9px] font-bold text-red-800 bg-red-100 rounded-full">B3</span>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Nama Sampah -->
                                    <h4 class="font-fredoka font-bold text-base text-gray-800 mb-1">{{ $question->nama_sampah }}</h4>
                                    <!-- Fakta Singkat -->
                                    <p class="text-[10px] text-gray-400 line-clamp-2 mb-3">{{ $question->fakta_edukasi ?? '-' }}</p>
                                </div>

                                <!-- Aksi Hapus / Edit -->
                                <div class="flex items-center justify-between pt-2 border-t border-gray-100 text-xs">
                                    @if($question->is_default)
                                        <span class="text-[9px] font-bold text-gray-400 uppercase tracking-wider">Default Admin</span>
                                    @else
                                        <span class="text-[9px] font-bold text-blue-500 uppercase tracking-wider">Kustom Anda</span>
                                    @endif

                                    <div class="flex space-x-1">
                                        <!-- Edit jika milik guru -->
                                        @if(!$question->is_default && $question->user_id === auth()->id())
                                            <a href="{{ route('guru.questions.edit', [$question->id, 'session_id' => $session->id]) }}" class="p-1 bg-yellow-50 hover:bg-yellow-100 text-yellow-600 rounded-lg transition" title="Edit Soal">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125"/>
                                                </svg>
                                            </a>
                                        @endif

                                        <!-- Hapus (Lepas/Detach) -->
                                        <form action="{{ route('guru.questions.destroy', $question) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus soal ini dari kelas Anda?');">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="session_id" value="{{ $session->id }}">
                                            <button type="submit" class="p-1 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition" title="Hapus Soal">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-1.5 12a1.5 1.5 0 01-1.5 1.5H7.5A1.5 1.5 0 016 20.25l-1.5-12m3 0h12m-9 0V5.25A1.5 1.5 0 019 3.75h6A1.5 1.5 0 0116.5 5.25V8.25m-7.5 0h7.5"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full py-12 text-center text-gray-400 bg-white rounded-3xl p-8 border border-gray-100">
                                <span class="text-4xl block mb-2">🗑️</span>
                                Belum ada soal terhubung ke sesi ini. Silakan klik **Impor Soal Default** atau **Buat Soal Kustom** di atas!
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- TAB 3: ANALISIS MISKONSEPSI -->
            <div x-show="tab === 'analytics'" class="bg-white rounded-3xl p-6 shadow-md border border-gray-100" x-cloak>
                <div class="mb-6">
                    <h3 class="font-fredoka font-bold text-lg text-gray-800">Analisis Miskonsepsi Siswa SD</h3>
                    <p class="text-xs text-gray-400 mt-1">Mendeteksi jenis sampah mana yang paling membingungkan bagi siswa kelas Anda. Gunakan data ini untuk menjelaskan kembali di kelas.</p>
                </div>

                @if($misconceptions->count() > 0)
                    <div class="space-y-6">
                        @foreach($misconceptions as $mc)
                            <div class="p-5 bg-gray-50 rounded-3xl border border-gray-100 flex flex-col md:flex-row items-center gap-6">
                                <!-- Thumbnail Gambar -->
                                <div class="w-20 h-20 bg-white rounded-2xl border border-gray-100 flex items-center justify-center overflow-hidden">
                                    <img src="{{ asset($mc->gambar) }}" alt="{{ $mc->nama_sampah }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='https://placehold.co/150?text={{ urlencode($mc->nama_sampah) }}';">
                                </div>
                                <div class="flex-1 w-full text-center md:text-left">
                                    <div class="flex flex-col md:flex-row md:items-center gap-2 mb-1.5 justify-center md:justify-start">
                                        <h4 class="font-fredoka font-bold text-lg text-gray-800">{{ $mc->nama_sampah }}</h4>
                                        <div>
                                            @if($mc->kategori === 'organik')
                                                <span class="px-2.5 py-0.5 text-xs font-semibold text-emerald-800 bg-emerald-100 rounded-full">Kategori Benar: Organik</span>
                                            @elseif($mc->kategori === 'anorganik')
                                                <span class="px-2.5 py-0.5 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">Kategori Benar: Anorganik</span>
                                            @else
                                                <span class="px-2.5 py-0.5 text-xs font-semibold text-red-800 bg-red-100 rounded-full">Kategori Benar: B3</span>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- Progress Bar Salah -->
                                    <div class="w-full bg-gray-200 rounded-full h-4 relative overflow-hidden mb-2 shadow-inner">
                                        <div class="bg-gradient-to-r from-red-400 to-red-600 h-4 rounded-full" style="width: {{ $mc->wrong_percentage }}%"></div>
                                        <div class="absolute inset-0 flex items-center justify-center text-[10px] font-bold text-gray-700">
                                            {{ $mc->wrong_percentage }}% Salah Memilah ({{ $mc->pivot->wrong_count }} dari {{ $mc->pivot->total_count }} kali muncul)
                                        </div>
                                    </div>
                                    <!-- Tip review -->
                                    <p class="text-xs text-gray-500 font-light mt-1">
                                        💡 <strong>Saran Ulasan Guru:</strong> {{ $mc->fakta_edukasi }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-12 text-center text-gray-400">
                        <span class="text-4xl block mb-2">📊</span>
                        Belum ada analisis terkumpul. Siswa perlu memainkan game ini terlebih dahulu untuk menghasilkan data analitik miskonsepsi.
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
