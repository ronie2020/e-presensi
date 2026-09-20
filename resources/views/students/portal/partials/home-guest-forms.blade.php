<!-- 1. FORM PORTAL (Search NISN Siswa) -->
<form x-show="mode === 'portal'" @submit="isLoading = true" action="{{ route('portal.search') }}" method="POST" class="relative">
    @csrf
    <div class="relative group">
        <div class="absolute inset-y-0 left-0 pl-4 sm:pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-elevate-accent transition-colors">
            <i class="ph-bold ph-identification-card text-xl sm:text-2xl"></i>
        </div>
        
        <input type="text" 
               name="student_id" 
               class="block w-full pl-12 sm:pl-14 pr-28 sm:pr-36 py-4 sm:py-4.5 bg-[#021124]/70 text-white text-sm sm:text-base font-bold rounded-2xl border border-white/15 focus:border-elevate-accent focus:bg-[#021124]/90 focus:ring-4 focus:ring-elevate-accent/20 transition-all placeholder:text-slate-500 outline-none" 
               placeholder="Masukkan 10 Digit NISN Siswa..." 
               required 
               autocomplete="off">
        
        <button type="submit" 
                :disabled="isLoading" 
                class="absolute right-2 top-2 bottom-2 bg-gradient-to-r from-elevate-primary via-[#0d52a1] to-elevate-accent hover:brightness-110 disabled:opacity-60 text-white px-5 sm:px-7 rounded-xl font-black text-xs sm:text-sm tracking-wide transition-all shadow-[0_0_20px_rgba(86,187,241,0.35)] flex items-center justify-center gap-2 group/btn active:scale-95">
            <span x-show="!isLoading">CARI DATA</span>
            <i x-show="!isLoading" class="ph-bold ph-arrow-right text-base group-hover/btn:translate-x-1 transition-transform"></i>
            <svg x-show="isLoading" x-cloak class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        </button>
    </div>
    <p class="text-xs text-slate-400 mt-3 px-2 text-center font-medium flex items-center justify-center gap-1.5">
        <i class="ph-bold ph-info text-elevate-accent"></i>
        <span>Layanan resmi bagi Orang Tua &amp; Siswa untuk memantau data tanpa kata sandi.</span>
    </p>
</form>

<!-- 2. FORM LOGIN LMS (Ruang Belajar) -->
<form x-show="mode === 'lms'" @submit="isLoading = true" action="{{ route('student.login.post') }}" method="POST" class="relative" x-cloak>
    @csrf
    <input type="hidden" name="intended_app" value="lms">
    <div class="relative group">
        <div class="absolute inset-y-0 left-0 pl-4 sm:pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-sky-400 transition-colors">
            <i class="ph-bold ph-books text-xl sm:text-2xl"></i>
        </div>
        
        <input type="text" 
               name="student_id" 
               class="block w-full pl-12 sm:pl-14 pr-32 sm:pr-40 py-4 sm:py-4.5 bg-[#021124]/70 text-white text-sm sm:text-base font-bold rounded-2xl border border-white/15 focus:border-sky-400 focus:bg-[#021124]/90 focus:ring-4 focus:ring-sky-400/20 transition-all placeholder:text-slate-500 outline-none" 
               placeholder="NISN Peserta Didik..." 
               required 
               autocomplete="off">
        
        <button type="submit" 
                :disabled="isLoading" 
                class="absolute right-2 top-2 bottom-2 bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-600 hover:brightness-110 disabled:opacity-60 text-white px-5 sm:px-6 rounded-xl font-black text-xs sm:text-sm tracking-wide transition-all shadow-[0_0_20px_rgba(37,99,235,0.4)] flex items-center justify-center gap-2 group/btn active:scale-95">
            <span x-show="!isLoading">MASUK KELAS</span>
            <i x-show="!isLoading" class="ph-bold ph-sign-in text-base group-hover/btn:translate-x-1 transition-transform"></i>
            <svg x-show="isLoading" x-cloak class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        </button>
    </div>
    <p class="text-xs text-sky-400/90 mt-3 px-2 text-center font-medium flex flex-wrap items-center justify-center gap-1.5">
        <span>Masuk untuk mengakses materi kurikulum merdeka &amp; tugas.</span>
        <a href="{{ route('student.login.learning') }}" class="underline font-bold hover:text-sky-300 inline-flex items-center gap-0.5">
            Buka Halaman Khusus <i class="ph-bold ph-arrow-up-right text-[10px]"></i>
        </a>
    </p>
</form>

<!-- 3. FORM LOGIN CBT (Ruang Ujian) -->
<form x-show="mode === 'cbt'" @submit="isLoading = true" action="{{ route('student.login.post') }}" method="POST" class="relative" x-cloak>
    @csrf
    <input type="hidden" name="intended_app" value="cbt">
    <div class="relative group">
        <div class="absolute inset-y-0 left-0 pl-4 sm:pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-rose-400 transition-colors">
            <i class="ph-bold ph-desktop text-xl sm:text-2xl"></i>
        </div>
        
        <input type="text" 
               name="student_id" 
               class="block w-full pl-12 sm:pl-14 pr-32 sm:pr-40 py-4 sm:py-4.5 bg-[#021124]/70 text-white text-sm sm:text-base font-bold rounded-2xl border border-white/15 focus:border-rose-400 focus:bg-[#021124]/90 focus:ring-4 focus:ring-rose-400/20 transition-all placeholder:text-slate-500 outline-none" 
               placeholder="NISN Peserta Ujian..." 
               required 
               autocomplete="off">
        
        <button type="submit" 
                :disabled="isLoading" 
                class="absolute right-2 top-2 bottom-2 bg-gradient-to-r from-rose-600 via-rose-700 to-red-600 hover:brightness-110 disabled:opacity-60 text-white px-5 sm:px-6 rounded-xl font-black text-xs sm:text-sm tracking-wide transition-all shadow-[0_0_20px_rgba(225,29,72,0.4)] flex items-center justify-center gap-2 group/btn active:scale-95">
            <span x-show="!isLoading">MULAI UJIAN</span>
            <i x-show="!isLoading" class="ph-bold ph-arrow-right text-base group-hover/btn:translate-x-1 transition-transform"></i>
            <svg x-show="isLoading" x-cloak class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        </button>
    </div>
    <p class="text-xs text-rose-400/90 mt-3 px-2 text-center font-bold flex flex-wrap items-center justify-center gap-1.5">
        <span><i class="ph-fill ph-warning-circle"></i> Pastikan berada di ruang ujian aktif.</span>
        <a href="{{ route('student.login.cbt') }}" class="underline font-bold hover:text-rose-300 inline-flex items-center gap-0.5">
            Buka Halaman Khusus <i class="ph-bold ph-arrow-up-right text-[10px]"></i>
        </a>
    </p>
</form>