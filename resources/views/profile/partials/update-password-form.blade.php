<section>
    <header class="border-b border-emerald-100/80 pb-4">
        <h2 class="text-lg font-fredoka font-bold text-emerald-950 flex items-center gap-2">
            <span class="p-1.5 bg-blue-100 text-blue-700 rounded-xl text-base">🔒</span> Keamanan & Ubah Kata Sandi
        </h2>
        <p class="mt-1 text-xs text-gray-500">
            Pastikan akun Anda menggunakan kata sandi yang kuat dan aman untuk menjaga data siswa.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('put')

        <!-- Kata Sandi Saat Ini -->
        <div>
            <label for="update_password_current_password" class="font-fredoka font-bold text-xs uppercase tracking-wider text-gray-700 block mb-1.5">Kata Sandi Saat Ini</label>
            <input id="update_password_current_password" name="current_password" type="password" class="w-full border-2 border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500 rounded-2xl p-3.5 text-sm font-semibold text-gray-800 shadow-xs placeholder:text-gray-300" autocomplete="current-password" placeholder="Masukkan kata sandi lama Anda...">
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1.5" />
        </div>

        <!-- Kata Sandi Baru -->
        <div>
            <label for="update_password_password" class="font-fredoka font-bold text-xs uppercase tracking-wider text-gray-700 block mb-1.5">Kata Sandi Baru</label>
            <input id="update_password_password" name="password" type="password" class="w-full border-2 border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500 rounded-2xl p-3.5 text-sm font-semibold text-gray-800 shadow-xs placeholder:text-gray-300" autocomplete="new-password" placeholder="Masukkan kata sandi baru (min. 8 karakter)...">
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1.5" />
        </div>

        <!-- Konfirmasi Kata Sandi Baru -->
        <div>
            <label for="update_password_password_confirmation" class="font-fredoka font-bold text-xs uppercase tracking-wider text-gray-700 block mb-1.5">Konfirmasi Kata Sandi Baru</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="w-full border-2 border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500 rounded-2xl p-3.5 text-sm font-semibold text-gray-800 shadow-xs placeholder:text-gray-300" autocomplete="new-password" placeholder="Ketik ulang kata sandi baru Anda...">
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1.5" />
        </div>

        <div class="flex items-center gap-4 pt-3 border-t border-emerald-100/80">
            <button type="submit" 
                    class="px-6 py-3 font-fredoka font-bold text-sm rounded-2xl shadow-md border-b-4 transition duration-150 flex items-center gap-2 tap-scale"
                    style="background-color: #2563eb; color: #ffffff; border-color: #1d4ed8;">
                🔑 Perbarui Kata Sandi
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3500)"
                    class="text-xs font-bold text-blue-700 bg-blue-100 px-3 py-1.5 rounded-full border border-blue-200 flex items-center gap-1.5"
                >
                    <span>✅ Kata sandi berhasil diperbarui!</span>
                </p>
            @endif
        </div>
    </form>
</section>
