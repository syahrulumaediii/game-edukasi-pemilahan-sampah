<section class="space-y-4">
    <header class="border-b border-red-200/80 pb-3">
        <h2 class="text-base font-fredoka font-bold text-red-700 flex items-center gap-2">
            <span class="p-1.5 bg-red-100 text-red-700 rounded-xl text-xs">⚠️</span> Zona Bahaya: Hapus Akun
        </h2>
        <p class="mt-1 text-xs text-gray-600 leading-relaxed">
            Setelah akun Anda dihapus, semua data sesi kelas, kuis, dan riwayat nilai siswa Anda akan dihapus secara permanen dari server.
        </p>
    </header>

    <button type="button"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-5 py-2.5 font-fredoka font-bold text-xs rounded-xl shadow-xs border-b-3 transition duration-150 flex items-center gap-1.5 tap-scale"
        style="background-color: #dc2626; color: #ffffff; border-color: #991b1b;"
    >
        🗑️ Hapus Akun Saya Permanen
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6 text-left space-y-4">
            @csrf
            @method('delete')

            <div class="flex items-center gap-3">
                <span class="text-3xl">⚠️</span>
                <div>
                    <h3 class="text-base font-fredoka font-bold text-red-700">
                        Apakah Anda Yakin Ingin Menghapus Akun?
                    </h3>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Tindakan ini tidak dapat dibatalkan. Masukkan kata sandi Anda untuk mengonfirmasi konfirmasi penghapusan permanen.
                    </p>
                </div>
            </div>

            <div>
                <label for="password" class="font-fredoka font-bold text-xs uppercase tracking-wider text-gray-700 block mb-1">Kata Sandi Anda</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="w-full border-2 border-red-200 focus:border-red-500 focus:ring-red-500 rounded-xl p-3 text-sm font-semibold text-gray-800"
                    placeholder="Ketik kata sandi Anda untuk konfirmasi..."
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1.5 text-xs text-red-600 font-bold" />
            </div>

            <div class="flex items-center justify-end space-x-3 pt-3 border-t border-gray-100">
                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-fredoka font-bold text-xs rounded-xl transition">
                    Batal
                </button>
                <button type="submit" 
                        class="px-5 py-2 font-fredoka font-bold text-xs rounded-xl shadow-md border-b-2 transition"
                        style="background-color: #dc2626; color: #ffffff; border-color: #991b1b;">
                    Ya, Hapus Akun Permanen
                </button>
            </div>
        </form>
    </x-modal>
</section>
