<x-guest-layout>
    <div class="mb-5 p-4 rounded-xl bg-emerald-50/80 border border-emerald-200/80 text-sm text-emerald-800 leading-relaxed shadow-sm">
        <div class="flex items-start gap-3">
            <span class="text-base mt-0.5">🔑</span>
            <p>
                {{ __('Lupa kata sandi? Jangan khawatir. Masukkan alamat email Anda, dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi baru.') }}
            </p>
        </div>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
