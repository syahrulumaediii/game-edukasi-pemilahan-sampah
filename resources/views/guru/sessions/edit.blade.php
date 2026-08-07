<x-app-layout>
    <x-slot name="header">
        <h2 class="font-fredoka font-semibold text-2xl text-emerald-700 leading-tight">
            {{ __('Edit Sesi Kelas') }}: {{ $session->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl p-8 shadow-md border border-gray-100">
                <form method="POST" action="{{ route('guru.sessions.update', $session) }}" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <!-- Judul Sesi / Nama Kelas -->
                    <div>
                        <x-input-label for="title" :value="__('Nama Sesi / Judul Game Kelas')" />
                        <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $session->title)" required autofocus placeholder="Contoh: Kuis Pemilahan Kelas 3A" />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    <!-- Status Sesi -->
                    <div>
                        <x-input-label for="is_active" :value="__('Status Akses Game')" />
                        <select id="is_active" name="is_active" required class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                            <option value="1" {{ old('is_active', $session->is_active) ? 'selected' : '' }}>Dibuka (Siswa dapat memindai & bermain)</option>
                            <option value="0" {{ !old('is_active', $session->is_active) ? 'selected' : '' }}>Ditutup (Game dikunci, siswa tidak dapat mengakses)</option>
                        </select>
                        <x-input-error :messages="$errors->get('is_active')" class="mt-2" />
                    </div>

                    <!-- Mode Game -->
                    <div>
                        <x-input-label for="game_mode" :value="__('Pilih Mode Permainan')" />
                        <select id="game_mode" name="game_mode" required class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                            <option value="quizizz" {{ old('game_mode', $session->game_mode) === 'quizizz' ? 'selected' : '' }}>Mode Quizizz (Mandiri, review di akhir)</option>
                            <option value="belajar" {{ old('game_mode', $session->game_mode) === 'belajar' ? 'selected' : '' }}>Mode Belajar Mandiri (Pop-up edukasi instan + Dubbing Suara AI 🔊)</option>
                            <option value="duel" {{ old('game_mode', $session->game_mode) === 'duel' ? 'selected' : '' }}>Mode Duel (Kontrol Guru, siswa menjawab bersamaan ⚡)</option>
                        </select>
                        <x-input-error :messages="$errors->get('game_mode')" class="mt-2" />
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('guru.sessions.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-fredoka font-semibold text-sm rounded-2xl transition duration-150">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-fredoka font-semibold text-sm rounded-2xl shadow-md transition duration-150">
                            Perbarui Sesi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
