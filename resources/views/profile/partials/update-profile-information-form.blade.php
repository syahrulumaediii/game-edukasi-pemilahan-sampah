<section>
    <header class="border-b border-emerald-100/80 pb-4">
        <h2 class="text-lg font-fredoka font-bold text-emerald-950 flex items-center gap-2">
            <span class="p-1.5 bg-emerald-100 text-emerald-700 rounded-xl text-base">👤</span> Informasi Profil & Sekolah
        </h2>
        <p class="mt-1 text-xs text-gray-500">
            Perbarui nama lengkap, alamat email resmi, serta nama sekolah tempat Anda mengajar.
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('patch')

        <!-- Nama Lengkap -->
        <div>
            <label for="name" class="font-fredoka font-bold text-xs uppercase tracking-wider text-gray-700 block mb-1.5">Nama Lengkap & Gelar</label>
            <input id="name" name="name" type="text" class="w-full border-2 border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500 rounded-2xl p-3.5 text-sm font-semibold text-gray-800 shadow-xs placeholder:text-gray-300" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" placeholder="Tulis nama lengkap Anda...">
            <x-input-error class="mt-1.5" :messages="$errors->get('name')" />
        </div>

        <!-- Alamat Email -->
        <div>
            <label for="email" class="font-fredoka font-bold text-xs uppercase tracking-wider text-gray-700 block mb-1.5">Alamat Email Resmi</label>
            <input id="email" name="email" type="email" class="w-full border-2 border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500 rounded-2xl p-3.5 text-sm font-semibold text-gray-800 shadow-xs placeholder:text-gray-300" value="{{ old('email', $user->email) }}" required autocomplete="username" placeholder="alamat@email.com">
            <x-input-error class="mt-1.5" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2.5 p-3 bg-amber-50 rounded-xl border border-amber-200 text-xs">
                    <p class="text-amber-900 font-medium">
                        ⚠️ Email Anda belum diverifikasi.
                        <button form="send-verification" class="underline text-amber-700 hover:text-amber-900 font-bold ml-1">
                            Klik di sini untuk mengirim ulang email verifikasi.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-1.5 font-bold text-emerald-600">
                            ✓ Link verifikasi baru telah dikirim ke alamat email Anda.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Nama Sekolah -->
        <div>
            <label for="nama_sekolah" class="font-fredoka font-bold text-xs uppercase tracking-wider text-gray-700 block mb-1.5">Nama Sekolah Tempat Mengajar</label>
            <input id="nama_sekolah" name="nama_sekolah" type="text" placeholder="Contoh: SD Negeri 1 Cerdas, SDN 3 Bandung" class="w-full border-2 border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500 rounded-2xl p-3.5 text-sm font-semibold text-gray-800 shadow-xs placeholder:text-gray-300" value="{{ old('nama_sekolah', $user->nama_sekolah) }}">
            <p class="text-[11px] text-emerald-700 font-medium mt-1">Nama sekolah ini akan muncul di dashboard dan halaman laporan kuis siswa Anda.</p>
            <x-input-error class="mt-1.5" :messages="$errors->get('nama_sekolah')" />
        </div>

        <div class="flex items-center gap-4 pt-3 border-t border-emerald-100/80">
            <button type="submit" 
                    class="px-6 py-3 font-fredoka font-bold text-sm rounded-2xl shadow-md border-b-4 transition duration-150 flex items-center gap-2 tap-scale"
                    style="background-color: #059669; color: #ffffff; border-color: #047857;">
                💾 Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3500)"
                    class="text-xs font-bold text-emerald-700 bg-emerald-100 px-3 py-1.5 rounded-full border border-emerald-200 flex items-center gap-1.5"
                >
                    <span>✅ Profile berhasil diperbarui!</span>
                </p>
            @endif
        </div>
    </form>
</section>
