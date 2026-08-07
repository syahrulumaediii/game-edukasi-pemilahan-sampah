<x-app-layout>
    <x-slot name="header">
        <h2 class="font-fredoka font-semibold text-2xl text-emerald-700 leading-tight">
            {{ __('Edit Akun Guru') }}: {{ $teacher->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl p-8 shadow-md border border-gray-100">
                <form method="POST" action="{{ route('admin.teachers.update', $teacher) }}" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <!-- Nama Lengkap -->
                    <div>
                        <x-input-label for="name" :value="__('Nama Lengkap Guru')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $teacher->name)" required autofocus placeholder="Masukkan nama lengkap beserta gelar" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $teacher->email)" required placeholder="email@contoh.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Asal Sekolah -->
                    <div>
                        <x-input-label for="nama_sekolah" :value="__('Asal Sekolah')" />
                        <x-text-input id="nama_sekolah" class="block mt-1 w-full" type="text" name="nama_sekolah" :value="old('nama_sekolah', $teacher->nama_sekolah)" placeholder="SD Negeri Cerdas Ceria" />
                        <x-input-error :messages="$errors->get('nama_sekolah')" class="mt-2" />
                    </div>

                    <div class="p-4 bg-yellow-50/50 rounded-2xl border border-yellow-100 text-sm text-yellow-800">
                        <strong>Info:</strong> Kosongkan kolom password di bawah jika Anda tidak ingin mengubah password guru ini.
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Password Baru (Opsional)')" />
                        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" placeholder="Masukkan jika ingin diganti" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Konfirmasi Password -->
                    <div>
                        <x-input-label for="password_confirmation" :value="__('Konfirmasi Password Baru')" />
                        <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" placeholder="Ulangi password baru" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100">
                        <a href="{{ route('admin.teachers.index') }}" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-fredoka font-semibold text-sm rounded-2xl transition duration-150">
                            Batal
                        </a>
                        <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-fredoka font-semibold text-sm rounded-2xl shadow-md transition duration-150">
                            Perbarui Akun
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
