<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4 bg-emerald-50 text-emerald-600 px-4 py-3 rounded-xl text-sm font-medium border border-emerald-100 flex items-center gap-2" :status="session('status')" />

    <!-- 
        MODIFIKASI 1: 
        Menambahkan x-data="{ isLoggingIn: false }" untuk state loading
        Menambahkan @submit="isLoggingIn = true" untuk memicu loading saat form dikirim
    -->
    <form method="POST" action="{{ route('login') }}" class="space-y-5" x-data="{ isLoggingIn: false }" @submit="isLoggingIn = true">
        @csrf

        <!-- Email Address -->
        <div class="space-y-1">
            <label for="email" class="text-sm font-bold text-slate-700 ml-1">Email / NIP</label>
            <div class="relative group">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-blue-600 transition-colors">
                    <i class="ph-duotone ph-envelope-simple text-xl"></i>
                </div>
                <x-text-input id="email" class="block w-full rounded-xl border-slate-200 bg-slate-50/50 py-3 pl-11 pr-4 text-sm focus:border-blue-500 focus:bg-white focus:ring-blue-500 transition-all shadow-sm group-hover:bg-slate-50" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@sekolah.sch.id" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-rose-500 font-medium ml-1" />
        </div>

        <!-- Password -->
        <div class="space-y-1">
            <div class="flex justify-between items-center">
                <label for="password" class="text-sm font-bold text-slate-700 ml-1">Password</label>
            </div>
            <div class="relative group" x-data="{ show: false }">
                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 group-focus-within:text-blue-600 transition-colors">
                    <i class="ph-duotone ph-lock-key text-xl"></i>
                </div>
                <x-text-input id="password" class="block w-full rounded-xl border-slate-200 bg-slate-50/50 py-3 pl-11 pr-12 text-sm focus:border-blue-500 focus:bg-white focus:ring-blue-500 transition-all shadow-sm group-hover:bg-slate-50"
                                ::type="show ? 'text' : 'password'"
                                name="password"
                                required autocomplete="current-password" 
                                placeholder="••••••••" />
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-slate-600 cursor-pointer focus:outline-none transition-colors" tabindex="-1">
                    <i class="ph-bold" :class="show ? 'ph-eye' : 'ph-eye-slash'"></i>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-rose-500 font-medium ml-1" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between pt-1">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group select-none">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500 cursor-pointer" name="remember">
                <span class="ml-2 text-xs font-bold text-slate-500 group-hover:text-blue-600 transition-colors">Ingat Saya</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 transition-colors hover:underline">
                    Lupa Password?
                </a>
            @endif
        </div>

        <!-- 
            MODIFIKASI 2: Button Submit
            - Menambahkan :disabled untuk mencegah klik ganda
            - Menambahkan dynamic class untuk efek visual saat disabled
            - Membagi konten menjadi dua bagian dengan x-show (Normal & Loading)
        -->
        <button type="submit" 
                :disabled="isLoggingIn"
                :class="{ 'opacity-75 cursor-wait': isLoggingIn, 'hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-900/30 active:scale-[0.98]': !isLoggingIn }"
                class="group relative flex w-full justify-center rounded-xl bg-slate-900 py-3.5 px-4 text-sm font-bold text-white transition-all focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 overflow-hidden transform">
            
            <!-- Tampilan Normal (Muncul jika isLoggingIn == false) -->
            <span x-show="!isLoggingIn" class="relative z-10 flex items-center gap-2">
                Masuk Sekarang <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
            </span>

            <!-- Tampilan Loading (Muncul jika isLoggingIn == true) -->
            <span x-show="isLoggingIn" class="relative z-10 flex items-center gap-2" style="display: none;">
                <i class="ph-bold ph-spinner animate-spin text-lg"></i> Memverifikasi...
            </span>

            <!-- Efek Kilau (Hanya muncul saat tidak loading) -->
            <div x-show="!isLoggingIn" class="absolute inset-0 -translate-x-full group-hover:animate-[shimmer_1.5s_infinite] bg-gradient-to-r from-transparent via-white/10 to-transparent z-0"></div>
        </button>
    </form>
</x-guest-layout>