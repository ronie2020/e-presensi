<x-guest-layout>
    {{-- Kita tidak perlu logo di sini, karena sudah ada di layout --}}

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <h1 class="text-3xl font-bold text-gray-900 mb-2">Login</h1>
    <p class="text-sm text-gray-600 mb-6">Selamat datang kembali! Silakan masuk ke akun Anda.</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Alamat Email -->
        <div>
            <label for="email" class="block text-sm font-medium text-gray-700">Alamat Email</label>
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Kita hapus "Remember me" dan "Forgot Password" agar sesuai desain --}}

        <div class="flex items-center justify-end mt-6">
            <x-primary-button class="w-full justify-center">
                {{ __('Login') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>