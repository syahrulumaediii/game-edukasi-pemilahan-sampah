<x-app-layout>
    <x-slot name="header">
        <h2 class="font-fredoka font-semibold text-2xl text-emerald-700 leading-tight">
            {{ __('Edit Soal Kustom') }}: {{ $question->nama_sampah }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl p-8 shadow-md border border-gray-100">
                <form method="POST" action="{{ route('guru.questions.update', $question) }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <!-- ID Sesi Rujukan (untuk redirect setelah update) -->
                    <input type="hidden" name="session_id" value="{{ $sessionId }}">

                    <!-- Nama Sampah -->
                    <div>
                        <x-input-label for="nama_sampah" :value="__('Nama Sampah / Objek')" />
                        <x-text-input id="nama_sampah" class="block mt-1 w-full" type="text" name="nama_sampah" :value="old('nama_sampah', $question->nama_sampah)" required autofocus placeholder="Contoh: Kertas Struk, Cangkang Kerang" />
                        <x-input-error :messages="$errors->get('nama_sampah')" class="mt-2" />
                    </div>

                    <!-- Kategori Sampah -->
                    <div>
                        <x-input-label for="kategori" :value="__('Kategori Sampah')" />
                        <select id="kategori" name="kategori" required class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="organik" {{ old('kategori', $question->kategori) === 'organik' ? 'selected' : '' }}>Organik (Hijau - Mudah Membusuk)</option>
                            <option value="anorganik" {{ old('kategori', $question->kategori) === 'anorganik' ? 'selected' : '' }}>Anorganik (Kuning - Didaur Ulang)</option>
                            <option value="b3" {{ old('kategori', $question->kategori) === 'b3' ? 'selected' : '' }}>B3 (Merah - Berbahaya/Beracun)</option>
                        </select>
                        <x-input-error :messages="$errors->get('kategori')" class="mt-2" />
                    </div>

                    <!-- Preview & Edit Gambar -->
                    <div>
                        <x-input-label :value="__('Gambar Saat Ini')" />
                        <div class="mt-2 mb-4 w-28 h-28 bg-gray-50 rounded-2xl border border-gray-100 flex items-center justify-center overflow-hidden">
                            @if($question->gambar)
                                <img src="{{ asset($question->gambar) }}" alt="{{ $question->nama_sampah }}" class="w-full h-full object-cover" onerror="this.onerror=null; this.src='https://placehold.co/150?text={{ urlencode($question->nama_sampah) }}';">
                            @else
                                <span class="text-3xl">🗑️</span>
                            @endif
                        </div>

                        <x-input-label for="gambar" :value="__('Ganti Gambar (Opsional)')" />
                        <input id="gambar" type="file" name="gambar" accept="image/*" class="block mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2 text-sm text-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                        <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak ingin mengubah gambar. Gambar disarankan rasio 1:1 maksimal 2MB.</p>
                        <x-input-error :messages="$errors->get('gambar')" class="mt-2" />
                    </div>

                    <!-- Fakta Edukasi Singkat -->
                    <div>
                        <x-input-label for="fakta_edukasi" :value="__('Fakta Edukasi Pendek (Maksimal 1 Kalimat)')" />
                        <textarea id="fakta_edukasi" name="fakta_edukasi" required rows="3" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" placeholder="Contoh: Kertas struk belanja tidak bisa didaur ulang karena mengandung bahan kimia thermal sablon!">{{ old('fakta_edukasi', $question->fakta_edukasi) }}</textarea>
                        <p class="text-xs text-gray-400 mt-1">Tulis kalimat ringkas yang menarik dan mudah dipahami siswa SD.</p>
                        <x-input-error :messages="$errors->get('fakta_edukasi')" class="mt-2" />
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                        @if($sessionId)
                            <a href="{{ route('guru.sessions.show', $sessionId) }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-fredoka font-semibold text-sm rounded-2xl transition duration-150">
                                Batal
                            </a>
                        @else
                            <a href="{{ route('guru.dashboard') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-fredoka font-semibold text-sm rounded-2xl transition duration-150">
                                Batal
                            </a>
                        @endif
                        <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-fredoka font-semibold text-sm rounded-2xl shadow-md transition duration-150">
                            Perbarui Soal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
