<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="font-fredoka font-semibold text-sm text-gray-700 block mb-1">Email Guru / Admin</label>
            <input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" class="w-full border-2 border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500 rounded-2xl p-3.5 text-sm text-gray-800 shadow-inner placeholder:text-gray-300" placeholder="nama@email.com">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="font-fredoka font-semibold text-sm text-gray-700 block mb-1">Kata Sandi (Password)</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" class="w-full border-2 border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500 rounded-2xl p-3.5 text-sm text-gray-800 shadow-inner placeholder:text-gray-300" placeholder="••••••••">
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between text-xs">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-emerald-300 text-emerald-600 shadow-sm focus:ring-emerald-500" name="remember">
                <span class="ms-2 text-gray-500 font-medium">{{ __('Ingat Saya') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="underline text-emerald-600 hover:text-emerald-700 font-semibold rounded-md focus:outline-none" href="{{ route('password.request') }}">
                    Lupa Password?
                </a>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="pt-2">
            <button type="submit" class="w-full py-4 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-fredoka font-bold text-sm rounded-2xl shadow-md border-b-4 border-emerald-700 transition duration-150 text-center uppercase tracking-wider">
                Masuk ke Kelas 🚪
            </button>
        </div>
    </form>
</x-guest-layout>
