<x-guest-layout>

    {{-- ============================================================ --}}
    {{-- TAB SWITCHER: Guru / Staff  vs  Siswa                        --}}
    {{-- State dikelola Alpine.js: activeTab = 'guru' | 'siswa'       --}}
    {{-- ============================================================ --}}
    <div x-data="{ activeTab: '{{ $errors->has('student_id') ? 'siswa' : 'guru' }}' }">

        {{-- Session Status (Guru) --}}
        <x-auth-session-status
            class="mb-4 bg-emerald-50 text-emerald-600 px-4 py-3 rounded-xl text-sm font-medium border border-emerald-100 flex items-center gap-2"
            :status="session('status')" />

        {{-- Error session dari login siswa --}}
        @if (session('error'))
            <div class="mb-4 bg-rose-50 text-rose-600 px-4 py-3 rounded-xl text-sm font-medium border border-rose-100 flex items-center gap-2">
                <i class="ph-fill ph-warning-circle text-base"></i>
                {{ session('error') }}
            </div>
        @endif

        {{-- ===== TAB PILLS ===== --}}
        <div class="grid grid-cols-2 gap-1.5 bg-slate-100 p-1.5 rounded-2xl mb-6">

            {{-- Tab: Guru / Staff --}}
            <button
                type="button"
                @click="activeTab = 'guru'"
                :class="activeTab === 'guru'
                    ? 'bg-elevate-dark text-white shadow-md shadow-elevate-dark/30'
                    : 'text-slate-400 hover:text-slate-600 hover:bg-white/60'"
                class="flex items-center justify-center gap-2 py-2.5 px-3 rounded-xl text-sm font-extrabold transition-all duration-250 cursor-pointer">
                <i class="ph-duotone ph-chalkboard-teacher text-base"></i>
                Guru / Staff
            </button>

            {{-- Tab: Siswa --}}
            <button
                type="button"
                @click="activeTab = 'siswa'"
                :class="activeTab === 'siswa'
                    ? 'bg-gradient-to-r from-elevate-primary to-elevate-accent text-white shadow-md shadow-elevate-primary/30'
                    : 'text-slate-400 hover:text-slate-600 hover:bg-white/60'"
                class="flex items-center justify-center gap-2 py-2.5 px-3 rounded-xl text-sm font-extrabold transition-all duration-250 cursor-pointer">
                <i class="ph-duotone ph-student text-base"></i>
                Siswa
            </button>
        </div>


        {{-- ================================================================ --}}
        {{-- FORM A: GURU / STAFF (Email + Password)                          --}}
        {{-- ================================================================ --}}
        <form
            x-show="activeTab === 'guru'"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            method="POST"
            action="{{ route('login') }}"
            class="space-y-4"
            x-data="{ isLoggingIn: false }"
            @submit="isLoggingIn = true">
            @csrf

            {{-- Email --}}
            <div class="space-y-1">
                <label for="email" class="text-xs font-extrabold text-slate-600 ml-0.5 uppercase tracking-wide">Email / NIP</label>
                <div class="relative group">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 group-focus-within:text-elevate-primary transition-colors">
                        <i class="ph-duotone ph-envelope-simple text-lg"></i>
                    </div>
                    <x-text-input
                        id="email"
                        class="block w-full rounded-xl border-slate-200 bg-slate-50/50 py-3 pl-10 pr-4 text-sm focus:border-elevate-primary focus:bg-white focus:ring-elevate-primary transition-all shadow-sm"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required autofocus
                        autocomplete="username"
                        placeholder="nama@sekolah.sch.id" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-rose-500 font-semibold ml-0.5" />
            </div>

            {{-- Password --}}
            <div class="space-y-1">
                <label for="password" class="text-xs font-extrabold text-slate-600 ml-0.5 uppercase tracking-wide">Password</label>
                <div class="relative group" x-data="{ show: false }">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 group-focus-within:text-elevate-primary transition-colors">
                        <i class="ph-duotone ph-lock-key text-lg"></i>
                    </div>
                    <x-text-input
                        id="password"
                        class="block w-full rounded-xl border-slate-200 bg-slate-50/50 py-3 pl-10 pr-11 text-sm focus:border-elevate-primary focus:bg-white focus:ring-elevate-primary transition-all shadow-sm"
                        ::type="show ? 'text' : 'password'"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••" />
                    <button type="button" @click="show = !show"
                        class="absolute inset-y-0 right-0 flex items-center pr-3.5 text-slate-400 hover:text-slate-600 transition-colors focus:outline-none"
                        tabindex="-1">
                        <i class="ph-bold text-sm" :class="show ? 'ph-eye' : 'ph-eye-slash'"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-rose-500 font-semibold ml-0.5" />
            </div>

            {{-- Remember Me & Lupa Password --}}
            <div class="flex items-center justify-between pt-0.5">
                <label for="remember_me" class="inline-flex items-center cursor-pointer group select-none">
                    <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-elevate-primary shadow-sm focus:ring-elevate-primary cursor-pointer" name="remember">
                    <span class="ml-2 text-xs font-bold text-slate-500 group-hover:text-elevate-primary transition-colors">Ingat Saya</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs font-bold text-elevate-primary hover:text-elevate-dark transition-colors hover:underline">
                        Lupa Password?
                    </a>
                @endif
            </div>

            {{-- Tombol Submit --}}
            <button
                type="submit"
                :disabled="isLoggingIn"
                :class="{ 'opacity-70 cursor-wait': isLoggingIn, 'hover:shadow-lg hover:shadow-elevate-dark/25 hover:-translate-y-px active:scale-[0.98]': !isLoggingIn }"
                class="group relative flex w-full justify-center rounded-xl bg-elevate-dark py-3.5 px-4 text-sm font-extrabold text-white transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-elevate-primary focus:ring-offset-2 overflow-hidden transform">

                <span x-show="!isLoggingIn" class="relative z-10 flex items-center gap-2">
                    Masuk Sekarang
                    <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </span>
                <span x-show="isLoggingIn" class="relative z-10 flex items-center gap-2" style="display:none">
                    <i class="ph-bold ph-spinner animate-spin text-base"></i> Memverifikasi...
                </span>
                <div x-show="!isLoggingIn" class="absolute inset-0 -translate-x-full group-hover:animate-[shimmer_1.5s_infinite] bg-gradient-to-r from-transparent via-white/10 to-transparent z-0"></div>
            </button>
        </form>


        {{-- ================================================================ --}}
        {{-- FORM B: SISWA (NISN saja, tanpa password)                        --}}
        {{-- ================================================================ --}}
        <form
            x-show="activeTab === 'siswa'"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            method="POST"
            action="{{ route('student.login.post') }}"
            class="space-y-4"
            x-data="{ isLoggingIn: false }"
            @submit="isLoggingIn = true"
            style="display:none">
            @csrf

            {{-- Info Helper --}}
            <div class="flex items-start gap-2.5 bg-elevate-soft border border-elevate-accent/30 rounded-xl px-4 py-3">
                <i class="ph-duotone ph-info text-elevate-primary text-lg shrink-0 mt-0.5"></i>
                <p class="text-xs font-semibold text-elevate-primary/80 leading-relaxed">
                    Masukkan <strong>NISN</strong> atau <strong>NIS</strong> kamu. Tidak perlu password — cukup nomor identitasmu.
                </p>
            </div>

            {{-- Input NISN --}}
            <div class="space-y-1">
                <label for="student_id" class="text-xs font-extrabold text-slate-600 ml-0.5 uppercase tracking-wide">NISN / NIS</label>
                <div class="relative group">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400 group-focus-within:text-elevate-primary transition-colors">
                        <i class="ph-duotone ph-identification-card text-lg"></i>
                    </div>
                    <input
                        id="student_id"
                        name="student_id"
                        type="text"
                        autocomplete="off"
                        autofocus
                        value="{{ old('student_id') }}"
                        placeholder="Contoh: 0056789012"
                        class="block w-full rounded-xl border border-slate-200 bg-slate-50/50 py-3 pl-10 pr-4 text-sm font-bold tracking-wide focus:border-elevate-primary focus:bg-white focus:ring-2 focus:ring-elevate-primary/20 transition-all shadow-sm outline-none"
                        style="font-size:0.9rem; letter-spacing:0.04em;" />
                </div>
                <x-input-error :messages="$errors->get('student_id')" class="mt-1 text-xs text-rose-500 font-semibold ml-0.5" />
            </div>

            {{-- Spacer agar tinggi form sama dengan tab Guru --}}
            <div class="pt-[3.35rem]"></div>

            {{-- Tombol Submit --}}
            <button
                type="submit"
                :disabled="isLoggingIn"
                :class="{ 'opacity-70 cursor-wait': isLoggingIn, 'hover:shadow-lg hover:shadow-elevate-primary/25 hover:-translate-y-px active:scale-[0.98]': !isLoggingIn }"
                class="group relative flex w-full justify-center rounded-xl bg-gradient-to-r from-elevate-primary to-elevate-accent py-3.5 px-4 text-sm font-extrabold text-white transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-elevate-primary focus:ring-offset-2 overflow-hidden transform">

                <span x-show="!isLoggingIn" class="relative z-10 flex items-center gap-2">
                    Masuk Sekarang
                    <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                </span>
                <span x-show="isLoggingIn" class="relative z-10 flex items-center gap-2" style="display:none">
                    <i class="ph-bold ph-spinner animate-spin text-base"></i> Memverifikasi...
                </span>
                <div x-show="!isLoggingIn" class="absolute inset-0 -translate-x-full group-hover:animate-[shimmer_1.5s_infinite] bg-gradient-to-r from-transparent via-white/20 to-transparent z-0"></div>
            </button>
        </form>

    </div>{{-- end x-data --}}
</x-guest-layout>