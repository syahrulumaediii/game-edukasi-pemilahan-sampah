<x-app-layout>
    <x-slot name="header">
        <h2 class="font-fredoka font-semibold text-2xl text-emerald-700 leading-tight">
            {{ __('Tambah Soal Default Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl p-8 shadow-md border border-gray-100">
                <form method="POST" action="{{ route('admin.questions.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Nama Sampah -->
                    <div>
                        <x-input-label for="nama_sampah" :value="__('Nama Sampah / Objek')" />
                        <x-text-input id="nama_sampah" class="block mt-1 w-full" type="text" name="nama_sampah" :value="old('nama_sampah')" required autofocus placeholder="Contoh: Kulit Pisang, Botol Plastik" />
                        <x-input-error :messages="$errors->get('nama_sampah')" class="mt-2" />
                    </div>

                    <!-- Kategori Sampah -->
                    <div>
                        <x-input-label for="kategori" :value="__('Kategori Sampah')" />
                        <select id="kategori" name="kategori" required class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm">
                            <option value="">-- Pilih Kategori --</option>
                            <option value="organik" {{ old('kategori') === 'organik' ? 'selected' : '' }}>Organik (Hijau - Mudah Membusuk)</option>
                            <option value="anorganik" {{ old('kategori') === 'anorganik' ? 'selected' : '' }}>Anorganik (Kuning - Bisa Didaur Ulang)</option>
                            <option value="b3" {{ old('kategori') === 'b3' ? 'selected' : '' }}>B3 (Merah - Berbahaya/Beracun)</option>
                        </select>
                        <x-input-error :messages="$errors->get('kategori')" class="mt-2" />
                    </div>

                    <!-- Gambar Objek -->
                    <div>
                        <x-input-label for="gambar" :value="__('Gambar Sampah (Ilustrasi Kartun/PNG)')" />
                        <input id="gambar" type="file" name="gambar" required accept="image/*" class="block mt-1 w-full border border-gray-300 rounded-md shadow-sm p-2 text-sm text-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-500" />
                        <p class="text-xs text-gray-400 mt-1">Gunakan gambar berformat PNG/JPG transparan dengan rasio 1:1 (kotak) maksimal 2MB.</p>
                        <x-input-error :messages="$errors->get('gambar')" class="mt-2" />
                    </div>

                    <!-- Fakta Edukasi Singkat -->
                    <div>
                        <x-input-label for="fakta_edukasi" :value="__('Fakta Edukasi Pendek (Maksimal 1 Kalimat)')" />
                        <textarea id="fakta_edukasi" name="fakta_edukasi" required rows="3" class="block mt-1 w-full border-gray-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-md shadow-sm" placeholder="Contoh: Kulit pisang bisa diolah jadi pupuk kompos yang bagus untuk tanaman!">{{ old('fakta_edukasi') }}</textarea>
                        <p class="text-xs text-gray-400 mt-1">Berikan kalimat ringkas yang mudah dibaca oleh siswa SD.</p>
                        <x-input-error :messages="$errors->get('fakta_edukasi')" class="mt-2" />
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('admin.questions.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-fredoka font-semibold text-sm rounded-2xl transition duration-150">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-fredoka font-semibold text-sm rounded-2xl shadow-md transition duration-150">
                            Simpan Soal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
