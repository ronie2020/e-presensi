<x-guest-layout>

    {{-- ============================================================ --}}
    {{-- TAB SWITCHER: Guru / Staff  vs  Siswa                        --}}
    {{-- State dikelola Alpine.js: activeTab = 'guru' | 'siswa'       --}}
    {{-- ============================================================ --}}
    <div x-data="{ activeTab: '{{ $errors->has('student_id') ? 'siswa' : 'guru' }}' }" class="relative z-10 w-full max-w-sm mx-auto">

        {{-- Session Status (Guru) --}}
        <x-auth-session-status
            class="mb-6 bg-emerald-500/20 text-emerald-300 px-5 py-4 rounded-2xl text-sm font-bold border border-emerald-500/30 shadow-lg flex items-center gap-3 transform transition-all duration-300"
            :status="session('status')" />

        {{-- Error session dari login siswa --}}
        @if (session('error'))
            <div class="mb-6 bg-rose-500/20 text-rose-300 px-5 py-4 rounded-2xl text-sm font-bold border border-rose-500/30 shadow-lg flex items-center gap-3 transform transition-all duration-300">
                <i class="ph-fill ph-warning-circle text-xl animate-pulse"></i>
                {{ session('error') }}
            </div>
        @endif

        {{-- ===== PREMIUM TAB PILLS ===== --}}
        <div class="relative p-1.5 bg-white/5 backdrop-blur-xl border border-white/10 rounded-2xl mb-8 shadow-inner overflow-hidden">
            {{-- Animated Background Slider --}}
            <div class="absolute inset-y-1.5 w-[calc(50%-0.375rem)] bg-gradient-to-r from-elevate-accent to-elevate-primary rounded-xl shadow-[0_0_15px_rgba(86,187,241,0.3)] transition-transform duration-500 ease-out z-0 border border-elevate-accent/30"
                 :class="activeTab === 'siswa' ? 'translate-x-full left-auto right-1.5' : 'translate-x-0 left-1.5'"></div>

            <div class="relative z-10 grid grid-cols-2 gap-1">
                {{-- Tab: Guru / Staff --}}
                <button
                    type="button"
                    @click="activeTab = 'guru'"
                    :class="activeTab === 'guru'
                        ? 'text-white font-black'
                        : 'text-slate-400 hover:text-white font-bold'"
                    class="flex items-center justify-center gap-2 py-3 px-3 rounded-xl text-[13px] transition-all duration-300 cursor-pointer tracking-wide">
                    <i class="ph-bold ph-chalkboard-teacher text-lg transition-transform duration-300" :class="activeTab === 'guru' ? 'scale-110 text-white' : ''"></i>
                    Guru / Staff
                </button>

                {{-- Tab: Siswa --}}
                <button
                    type="button"
                    @click="activeTab = 'siswa'"
                    :class="activeTab === 'siswa'
                        ? 'text-white font-black'
                        : 'text-slate-400 hover:text-white font-bold'"
                    class="flex items-center justify-center gap-2 py-3 px-3 rounded-xl text-[13px] transition-all duration-300 cursor-pointer tracking-wide">
                    <i class="ph-bold ph-student text-lg transition-transform duration-300" :class="activeTab === 'siswa' ? 'scale-110 text-white' : ''"></i>
                    Siswa
                </button>
            </div>
        </div>


        {{-- ================================================================ --}}
        {{-- FORM A: GURU / STAFF (Email + Password)                          --}}
        {{-- ================================================================ --}}
        <form
            x-show="activeTab === 'guru'"
            x-transition:enter="transition ease-out duration-300 delay-100"
            x-transition:enter-start="opacity-0 translate-x-4"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 absolute w-full top-0"
            x-transition:leave-end="opacity-0 -translate-x-4 absolute w-full top-0"
            method="POST"
            action="{{ route('login') }}"
            class="space-y-5 relative"
            x-data="{ isLoggingIn: false }"
            @submit="isLoggingIn = true">
            @csrf

            {{-- Email --}}
            <div class="space-y-1.5">
                <label for="email" class="text-[11px] font-black text-slate-300 ml-1 uppercase tracking-widest">Email / NIP</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none transition-colors duration-300 group-focus-within:text-elevate-accent text-slate-400">
                        <i class="ph-duotone ph-envelope-simple text-xl"></i>
                    </div>
                    <x-text-input
                        id="email"
                        class="block w-full rounded-2xl border-white/15 bg-white/5 py-3.5 pl-12 pr-4 text-sm font-semibold text-white placeholder-slate-400 backdrop-blur-md focus:border-elevate-accent focus:bg-white/10 focus:ring-2 focus:ring-elevate-accent/20 transition-all duration-300 shadow-inner"
                        type="email"
                        name="email"
                        :value="old('email')"
                        required autofocus
                        autocomplete="username"
                        placeholder="nama@sekolah.sch.id" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-rose-400 font-bold ml-1" />
            </div>

            {{-- Password --}}
            <div class="space-y-1.5">
                <label for="password" class="text-[11px] font-black text-slate-300 ml-1 uppercase tracking-widest">Password</label>
                <div class="relative group" x-data="{ show: false }">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none transition-colors duration-300 group-focus-within:text-elevate-accent text-slate-400">
                        <i class="ph-duotone ph-lock-key text-xl"></i>
                    </div>
                    <x-text-input
                        id="password"
                        class="block w-full rounded-2xl border-white/15 bg-white/5 py-3.5 pl-12 pr-12 text-sm font-semibold text-white placeholder-slate-400 backdrop-blur-md focus:border-elevate-accent focus:bg-white/10 focus:ring-2 focus:ring-elevate-accent/20 transition-all duration-300 shadow-inner"
                        type="password"
                        ::type="show ? 'text' : 'password'"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••" />
                    <button type="button" @click="show = !show"
                        class="absolute inset-y-0 right-0 flex items-center pr-4 text-slate-400 hover:text-elevate-accent transition-colors focus:outline-none cursor-pointer"
                        tabindex="-1"
                        :title="show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'"
                        :aria-label="show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'">
                        <i class="ph-bold text-lg transition-transform duration-200 hover:scale-110" :class="show ? 'ph-eye-slash' : 'ph-eye'"></i>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-rose-400 font-bold ml-1" />
            </div>

            {{-- Remember Me & Lupa Password --}}
            <div class="flex items-center justify-between pt-1">
                <label for="remember_me" class="inline-flex items-center cursor-pointer group select-none">
                    <div class="relative flex items-center">
                        <input id="remember_me" type="checkbox" class="peer h-5 w-5 rounded-md border-white/20 bg-white/10 text-elevate-primary shadow-sm focus:ring-elevate-accent/30 transition-all cursor-pointer" name="remember">
                    </div>
                    <span class="ml-2.5 text-xs font-semibold text-slate-300 group-hover:text-white transition-colors">Ingat Saya</span>
                </label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs font-bold text-elevate-accent hover:text-white transition-colors hover:underline underline-offset-4">
                        Lupa Password?
                    </a>
                @endif
            </div>

            {{-- Tombol Submit --}}
            <div class="pt-2">
                <button
                    type="submit"
                    :disabled="isLoggingIn"
                    :class="{ 'opacity-70 cursor-wait': isLoggingIn, 'hover:shadow-[0_0_25px_rgba(86,187,241,0.5)] hover:-translate-y-0.5 active:scale-[0.98]': !isLoggingIn }"
                    class="group relative flex w-full justify-center rounded-2xl bg-gradient-to-r from-elevate-accent to-elevate-primary py-4 px-4 text-[14px] font-black text-white transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-elevate-accent/30 overflow-hidden transform shadow-[0_0_20px_rgba(86,187,241,0.3)] border border-elevate-accent/30">
                    
                    <span x-show="!isLoggingIn" class="relative z-10 flex items-center gap-2.5 tracking-wide">
                        Masuk Sekarang
                        <i class="ph-bold ph-arrow-right text-lg group-hover:translate-x-1.5 transition-transform duration-300"></i>
                    </span>
                    <span x-show="isLoggingIn" class="relative z-10 flex items-center gap-2.5 tracking-wide" style="display:none">
                        <i class="ph-bold ph-spinner animate-spin text-lg"></i> Mengautentikasi...
                    </span>
                    <div x-show="!isLoggingIn" class="absolute inset-0 -translate-x-full group-hover:animate-[shimmer_1.5s_infinite] bg-gradient-to-r from-transparent via-white/20 to-transparent z-0"></div>
                </button>
            </div>
        </form>


        {{-- ================================================================ --}}
        {{-- FORM B: SISWA (NISN saja, tanpa password)                        --}}
        {{-- ================================================================ --}}
        <form
            x-show="activeTab === 'siswa'"
            x-transition:enter="transition ease-out duration-300 delay-100"
            x-transition:enter-start="opacity-0 -translate-x-4"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 absolute w-full top-0"
            x-transition:leave-end="opacity-0 translate-x-4 absolute w-full top-0"
            method="POST"
            action="{{ route('student.login.post') }}"
            class="space-y-5 relative"
            x-data="{ isLoggingIn: false }"
            @submit="isLoggingIn = true"
            style="display:none">
            @csrf

            {{-- Info Helper --}}
            <div class="flex items-start gap-3 bg-elevate-accent/10 backdrop-blur-md border border-elevate-accent/20 rounded-2xl px-5 py-4 shadow-sm">
                <div class="p-1.5 bg-elevate-accent/20 text-elevate-accent rounded-lg shrink-0">
                    <i class="ph-bold ph-info text-lg"></i>
                </div>
                <p class="text-xs font-semibold text-slate-300 leading-relaxed pt-0.5">
                    Masukkan <span class="text-white font-bold bg-white/10 px-1.5 py-0.5 rounded-md">NISN</span> atau <span class="text-white font-bold bg-white/10 px-1.5 py-0.5 rounded-md">NIS</span> kamu. Tidak perlu password untuk masuk.
                </p>
            </div>

            {{-- Input NISN --}}
            <div class="space-y-1.5">
                <label for="student_id" class="text-[11px] font-black text-slate-300 ml-1 uppercase tracking-widest">NISN / NIS</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none transition-colors duration-300 group-focus-within:text-elevate-accent text-slate-400">
                        <i class="ph-duotone ph-identification-card text-xl"></i>
                    </div>
                    <input
                        id="student_id"
                        name="student_id"
                        type="text"
                        autocomplete="off"
                        autofocus
                        value="{{ old('student_id') }}"
                        placeholder="Contoh: 0056789012"
                        class="block w-full rounded-2xl border-white/15 bg-white/5 py-3.5 pl-12 pr-4 text-sm font-bold text-white placeholder-slate-400 backdrop-blur-md focus:border-elevate-accent focus:bg-white/10 focus:ring-2 focus:ring-elevate-accent/20 transition-all duration-300 shadow-inner outline-none"
                        style="letter-spacing:0.05em;" />
                </div>
                <x-input-error :messages="$errors->get('student_id')" class="mt-1.5 text-xs text-rose-400 font-bold ml-1" />
            </div>

            {{-- Spacer agar tinggi form mirip --}}
            <div class="pt-[2rem]"></div>

            {{-- Tombol Submit --}}
            <div class="pt-2">
                <button
                    type="submit"
                    :disabled="isLoggingIn"
                    :class="{ 'opacity-70 cursor-wait': isLoggingIn, 'hover:shadow-[0_0_25px_rgba(86,187,241,0.5)] hover:-translate-y-0.5 active:scale-[0.98]': !isLoggingIn }"
                    class="group relative flex w-full justify-center rounded-2xl bg-gradient-to-r from-elevate-accent to-elevate-primary py-4 px-4 text-[14px] font-black text-white transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-elevate-accent/30 overflow-hidden transform shadow-[0_0_20px_rgba(86,187,241,0.3)] border border-elevate-accent/30">
                    
                    <span x-show="!isLoggingIn" class="relative z-10 flex items-center gap-2.5 tracking-wide">
                        Masuk Sebagai Siswa
                        <i class="ph-bold ph-arrow-right text-lg group-hover:translate-x-1.5 transition-transform duration-300"></i>
                    </span>
                    <span x-show="isLoggingIn" class="relative z-10 flex items-center gap-2.5 tracking-wide" style="display:none">
                        <i class="ph-bold ph-spinner animate-spin text-lg"></i> Mengautentikasi...
                    </span>
                    <div x-show="!isLoggingIn" class="absolute inset-0 -translate-x-full group-hover:animate-[shimmer_1.5s_infinite] bg-gradient-to-r from-transparent via-white/20 to-transparent z-0"></div>
                </button>
            </div>
        </form>

    </div>{{-- end x-data --}}
</x-guest-layout>