<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="p-2.5 bg-emerald-100 text-emerald-700 rounded-2xl text-2xl shadow-xs border border-emerald-200">👨‍🏫</span>
                <div>
                    <h2 class="font-fredoka font-bold text-2xl text-emerald-800 tracking-wide">
                        {{ __('Pengaturan Profil & Akun Guru') }}
                    </h2>
                    <p class="text-xs text-gray-500 mt-0.5">
                        Kelola data pribadi, informasi sekolah tempat mengajar, serta keamanan kata sandi Anda.
                    </p>
                </div>
            </div>
            <a href="{{ route('guru.dashboard') }}" class="px-4 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-fredoka font-bold text-xs rounded-xl border border-emerald-200 shadow-xs transition duration-150 flex items-center gap-1.5 self-start sm:self-auto tap-scale">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
                Kembali ke Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-10 bg-[#f4faf7] min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Hero Profile Banner Card (High Contrast & Balanced Grid) -->
            <div class="rounded-[2.5rem] p-6 sm:p-7 relative overflow-hidden shadow-xl border-4"
                 style="background: linear-gradient(135deg, #059669 0%, #0d9488 50%, #047857 100%); border-color: #34d399; box-shadow: 0 15px 40px rgba(5, 150, 105, 0.3);">
                <!-- Background Decorative Pattern -->
                <div class="absolute -right-4 -bottom-4 text-9xl opacity-10 select-none pointer-events-none font-bold" style="color: #ffffff;">👨‍🏫</div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center relative z-10">
                    
                    <!-- Left Profile Info (Col 8) -->
                    <div class="md:col-span-8 flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-4">
                        <div class="w-18 h-18 sm:w-20 sm:h-20 rounded-2xl flex items-center justify-center text-4xl shrink-0 shadow-md"
                             style="background: rgba(255, 255, 255, 0.25); border: 3px solid #ffffff;">
                            👨‍🏫
                        </div>

                        <div class="space-y-2">
                            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2.5">
                                <h3 class="font-fredoka font-extrabold text-2xl sm:text-3xl text-white tracking-wide"
                                    style="text-shadow: 0 2px 4px rgba(0,0,0,0.4);">
                                    {{ $user->name }}
                                </h3>
                                <span class="px-3 py-1 font-fredoka font-extrabold text-xs rounded-full uppercase tracking-wider shadow-xs"
                                      style="background-color: #f59e0b; color: #78350f;">
                                    {{ $user->role === 'admin' ? '🕵️‍♂️ Super Admin' : '👨‍🏫 Guru Pengajar' }}
                                </span>
                            </div>
                            
                            <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 text-xs font-semibold text-white">
                                <span class="px-3 py-1.5 rounded-full flex items-center gap-1.5 shadow-2xs"
                                      style="background: rgba(0, 0, 0, 0.25); border: 1px solid rgba(255, 255, 255, 0.3);">
                                    <span>🏫</span> {{ $user->nama_sekolah ?? 'Nama Sekolah Belum Diatur' }}
                                </span>
                                <span class="px-3 py-1.5 rounded-full flex items-center gap-1.5 shadow-2xs"
                                      style="background: rgba(0, 0, 0, 0.25); border: 1px solid rgba(255, 255, 255, 0.3);">
                                    <span>✉️</span> {{ $user->email }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Quick Stats (Col 4 - White Cards with High Contrast Text) -->
                    <div class="md:col-span-4 grid grid-cols-2 gap-3">
                        <div class="rounded-2xl p-3 text-center shadow-md"
                             style="background: #ffffff; border: 2px solid #a7f3d0; box-shadow: 0 4px 14px rgba(0,0,0,0.12);">
                            <span class="text-[10px] uppercase font-fredoka font-bold tracking-wider block" style="color: #065f46;">Total Sesi</span>
                            <strong class="text-xl font-fredoka font-extrabold block mt-0.5" style="color: #047857;">
                                {{ $totalSessions ?? 0 }} Sesi
                            </strong>
                        </div>
                        <div class="rounded-2xl p-3 text-center shadow-md"
                             style="background: #ffffff; border: 2px solid #a7f3d0; box-shadow: 0 4px 14px rgba(0,0,0,0.12);">
                            <span class="text-[10px] uppercase font-fredoka font-bold tracking-wider block" style="color: #065f46;">Siswa Bermain</span>
                            <strong class="text-xl font-fredoka font-extrabold block mt-0.5" style="color: #0284c7;">
                                {{ $totalPlays ?? 0 }} Siswa
                            </strong>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Main 2-Column Dashboard Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- KIRI: Edit Profile & Change Password (Col-span 2) -->
                <div class="lg:col-span-2 space-y-8">
                    
                    <!-- Card 1: Informasi Profil & Sekolah -->
                    <div class="bg-white rounded-[2rem] p-6 sm:p-8 shadow-md border border-gray-100">
                        @include('profile.partials.update-profile-information-form')
                    </div>

                    <!-- Card 2: Keamanan & Ubah Kata Sandi -->
                    <div class="bg-white rounded-[2rem] p-6 sm:p-8 shadow-md border border-gray-100">
                        @include('profile.partials.update-password-form')
                    </div>

                </div>

                <!-- KANAN: Sidebar Status Akun & Danger Zone (Col-span 1) -->
                <div class="space-y-8">
                    
                    <!-- Card 3: Status Ringkasan Akun -->
                    <div class="bg-[#dcf0ea] rounded-[2rem] p-6 shadow-md border border-[#bedcd2] space-y-4">
                        <h3 class="font-fredoka font-bold text-lg text-emerald-950 flex items-center gap-2">
                            <span>📋</span> Informasi Akun Guru
                        </h3>

                        <div class="space-y-3 text-xs divide-y divide-[#cbe8df]">
                            <div class="pt-2 flex items-center justify-between">
                                <span class="text-gray-600 font-semibold">Status Lisensi:</span>
                                <span class="px-2.5 py-0.5 bg-emerald-200 text-emerald-900 font-bold rounded-full text-[10px] uppercase">
                                    ✓ Aktif Terverifikasi
                                </span>
                            </div>
                            <div class="pt-3 flex items-center justify-between">
                                <span class="text-gray-600 font-semibold">Peran Pengguna:</span>
                                <span class="font-bold text-emerald-950">
                                    {{ $user->role === 'admin' ? 'Administrator Utama' : 'Guru Pengajar KKM' }}
                                </span>
                            </div>
                            <div class="pt-3 flex items-center justify-between">
                                <span class="text-gray-600 font-semibold">Terdaftar Pada:</span>
                                <span class="font-mono font-bold text-emerald-900">
                                    {{ $user->created_at ? $user->created_at->format('d M Y') : '-' }}
                                </span>
                            </div>
                            <div class="pt-3 flex items-center justify-between">
                                <span class="text-gray-600 font-semibold">Keamanan Akses:</span>
                                <span class="font-bold text-emerald-800 flex items-center gap-1">
                                    <span>🔒</span> Enkripsi SSL Safe
                                </span>
                            </div>
                        </div>

                        <div class="p-3 bg-[#ecf7f4] rounded-2xl border border-[#bedcd2] text-[11px] text-emerald-900 leading-relaxed font-medium">
                            💡 <strong>Tips Guru:</strong> Pastikan Nama Sekolah diisi dengan benar agar tertera pada halaman rekap nilai kuis siswa Anda.
                        </div>
                    </div>

                    <!-- Card 4: Hapus Akun (Zona Bahaya) -->
                    <div class="bg-red-50/60 rounded-[2rem] p-6 shadow-md border border-red-100">
                        @include('profile.partials.delete-user-form')
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>