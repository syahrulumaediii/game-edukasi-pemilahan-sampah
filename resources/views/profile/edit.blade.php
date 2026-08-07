<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="p-2 bg-emerald-100 dark:bg-emerald-950/60 rounded-xl text-xl">⚙️</span>
            <div>
                <h2 class="font-fredoka font-bold text-2xl text-emerald-800 dark:text-emerald-300 tracking-wide">
                    {{ __('Pengaturan Profil') }}
                </h2>
                <p class="text-xs text-emerald-600 dark:text-emerald-400 mt-0.5">
                    Kelola informasi akun, kata sandi, dan privasi Anda
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-50/50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">

            {{-- Informasi Profil --}}
            <div class="bg-gradient-to-br from-[#e8f6f2] to-[#d4ebe3] dark:from-gray-800 dark:to-gray-800/90 border border-[#bedcd2]/70 dark:border-emerald-900/40 shadow-sm hover:shadow-md transition-all duration-300 sm:rounded-[2rem] p-6 sm:p-8 backdrop-blur-sm">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            {{-- Ubah Kata Sandi --}}
            <div class="bg-gradient-to-br from-[#e8f6f2] to-[#d4ebe3] dark:from-gray-800 dark:to-gray-800/90 border border-[#bedcd2]/70 dark:border-emerald-900/40 shadow-sm hover:shadow-md transition-all duration-300 sm:rounded-[2rem] p-6 sm:p-8 backdrop-blur-sm">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            {{-- Hapus Akun (Peringatan Khusus) --}}
            <div class="bg-gradient-to-br from-rose-50/70 to-red-100/50 dark:from-red-950/20 dark:to-gray-800 border border-red-200/80 dark:border-red-900/30 shadow-sm hover:shadow-md transition-all duration-300 sm:rounded-[2rem] p-6 sm:p-8 backdrop-blur-sm">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>