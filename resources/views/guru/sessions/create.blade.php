<x-app-layout>
    <x-slot name="header">
        <h2 class="font-fredoka font-semibold text-2xl text-emerald-700 leading-tight">
            {{ __('Buat Sesi Kelas Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl p-8 shadow-md border border-gray-100">
                <form method="POST" action="{{ route('guru.sessions.store') }}" class="space-y-6">
                    @csrf

                    <!-- Judul Sesi / Nama Kelas -->
                    <div>
                        <x-input-label for="title" :value="__('Nama Sesi / Judul Game Kelas')" />
                        <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus placeholder="Contoh: Kuis Pemilahan Kelas 3A, KKM Kelompok 5 SD Cerdas" />
                        <p class="text-xs text-gray-400 mt-2">Nama ini akan muncul pada layar selamat datang permainan siswa.</p>
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <!-- Mode Game -->
                    <div>
                        <x-input-label for="game_mode" :value="__('Pilih Mode Permainan')" />
                        <select id="game_mode" name="game_mode" required class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                            <option value="quizizz" {{ old('game_mode') === 'quizizz' ? 'selected' : '' }}>Mode Quizizz (Mandiri, review di akhir)</option>
                            <option value="belajar" {{ old('game_mode') === 'belajar' ? 'selected' : '' }}>Mode Belajar Mandiri (Pop-up edukasi instan + Dubbing Suara AI 🔊)</option>
                            <option value="duel" {{ old('game_mode', 'quizizz') === 'duel' ? 'selected' : '' }}>Mode Duel (Kontrol Guru, siswa menjawab bersamaan ⚡)</option>
                        </select>
                        <p class="text-xs text-gray-400 mt-2">
                            <strong>Mode Belajar Mandiri:</strong> Memberikan penjelasan edukatif bergambar dan bersuara ramah anak setelah siswa menjawab tiap soal.<br>
                            <strong>Mode Duel:</strong> Guru mengontrol mulai permainan dari layar proyektor untuk kompetisi kelas secara serentak.
                        </p>
                        <x-input-error :messages="$errors->get('game_mode')" class="mt-2" />
                    </div>

                    <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 text-sm text-emerald-800">
                        <strong>Info Penting:</strong> Sesi game baru secara otomatis akan langsung mengimpor **20 Soal Master Default** (seperti Kulit Pisang, Botol Plastik, dll.) dari Super Admin, sehingga Anda bisa langsung memainkannya tanpa repot! Anda juga bisa menambahkan soal kustom buatan sendiri setelah sesi ini dibuat.
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('guru.sessions.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-fredoka font-semibold text-sm rounded-2xl transition duration-150">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-fredoka font-semibold text-sm rounded-2xl shadow-md transition duration-150">
                            Buat & Impor Soal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
