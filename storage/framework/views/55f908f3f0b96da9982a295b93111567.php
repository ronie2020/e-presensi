<!-- 1. FORM PORTAL (Search) -->
<form x-show="mode === 'portal'" @submit="isLoading = true" action="<?php echo e(route('portal.search')); ?>" method="POST" class="relative">
    <?php echo csrf_field(); ?>
    <div class="relative group">
        <div class="absolute inset-y-0 left-0 pl-4 md:pl-6 flex items-center pointer-events-none">
            <i class="ph-bold ph-identification-card text-xl md:text-2xl text-slate-400 group-focus-within:text-slate-700 transition-colors"></i>
        </div>
        
        <input type="text" name="student_id" class="block w-full pl-12 md:pl-16 pr-14 md:pr-32 py-4 md:py-5 bg-slate-50 text-slate-800 text-base md:text-lg font-bold rounded-2xl focus:ring-4 focus:ring-slate-200 focus:bg-white transition-all placeholder:text-slate-400 border-none outline-none" placeholder="Masukkan NISN Siswa" required autocomplete="off">
        
        <button type="submit" :disabled="isLoading" class="absolute right-2 top-2 bottom-2 bg-slate-800 hover:bg-slate-900 disabled:bg-slate-400 text-white w-11 md:w-auto px-0 md:px-8 rounded-xl font-bold transition-all shadow-lg shadow-slate-800/20 flex items-center justify-center gap-2 group/btn">
            <span x-show="!isLoading" class="hidden md:inline">Cari</span>
            <i x-show="!isLoading" class="ph-bold ph-arrow-right text-lg md:text-base group-hover/btn:translate-x-1 transition-transform"></i>
            <svg x-show="isLoading" x-cloak class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        </button>
    </div>
    <p class="text-xs text-slate-400 mt-3 px-4 text-center font-medium">Fitur ini untuk Orang Tua mengecek data siswa tanpa perlu login.</p>
</form>

<!-- 2. FORM LOGIN LMS (Biru) -->
<form x-show="mode === 'lms'" @submit="isLoading = true" action="<?php echo e(route('student.login.post')); ?>" method="POST" class="relative" x-cloak>
    <?php echo csrf_field(); ?>
    <input type="hidden" name="intended_app" value="lms">
    <div class="relative group">
        <div class="absolute inset-y-0 left-0 pl-4 md:pl-6 flex items-center pointer-events-none">
            <i class="ph-bold ph-student text-xl md:text-2xl text-blue-400 group-focus-within:text-blue-600 transition-colors"></i>
        </div>
        
        <input type="text" name="student_id" class="block w-full pl-12 md:pl-16 pr-14 md:pr-40 py-4 md:py-5 bg-blue-50/50 text-slate-800 text-base md:text-lg font-bold rounded-2xl focus:ring-4 focus:ring-blue-100 focus:bg-white transition-all placeholder:text-blue-300 border-none outline-none" placeholder="NISN Siswa" required autocomplete="off">
        
        <button type="submit" :disabled="isLoading" class="absolute right-2 top-2 bottom-2 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white w-11 md:w-auto px-0 md:px-6 rounded-xl font-bold transition-all shadow-lg shadow-blue-600/20 flex items-center justify-center gap-2 group/btn">
            <span x-show="!isLoading" class="hidden md:inline">Masuk Kelas</span>
            <i x-show="!isLoading" class="ph-bold ph-sign-in text-lg md:text-base group-hover/btn:translate-x-1 transition-transform"></i>
            <svg x-show="isLoading" x-cloak class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        </button>
    </div>
    <p class="text-xs text-blue-400/80 mt-3 px-4 text-center font-medium">Masuk untuk mengakses materi pelajaran dan tugas.</p>
</form>

<!-- 3. FORM LOGIN CBT (Merah) -->
<form x-show="mode === 'cbt'" @submit="isLoading = true" action="<?php echo e(route('student.login.post')); ?>" method="POST" class="relative" x-cloak>
    <?php echo csrf_field(); ?>
    <input type="hidden" name="intended_app" value="cbt">
    <div class="relative group">
        <div class="absolute inset-y-0 left-0 pl-4 md:pl-6 flex items-center pointer-events-none">
            <i class="ph-bold ph-lock-key text-xl md:text-2xl text-rose-400 group-focus-within:text-rose-600 transition-colors"></i>
        </div>
        
        <input type="text" name="student_id" class="block w-full pl-12 md:pl-16 pr-14 md:pr-40 py-4 md:py-5 bg-rose-50/50 text-slate-800 text-base md:text-lg font-bold rounded-2xl focus:ring-4 focus:ring-rose-100 focus:bg-white transition-all placeholder:text-rose-300 border-none outline-none" placeholder="NISN Siswa" required autocomplete="off">
        
        <button type="submit" :disabled="isLoading" class="absolute right-2 top-2 bottom-2 bg-rose-600 hover:bg-rose-700 disabled:bg-rose-400 text-white w-11 md:w-auto px-0 md:px-6 rounded-xl font-bold transition-all shadow-lg shadow-rose-600/20 flex items-center justify-center gap-2 group/btn">
            <span x-show="!isLoading" class="hidden md:inline">Mulai Ujian</span>
            <i x-show="!isLoading" class="ph-bold ph-arrow-right text-lg md:text-base group-hover/btn:translate-x-1 transition-transform"></i>
            <svg x-show="isLoading" x-cloak class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
        </button>
    </div>
    <p class="text-xs text-rose-400/80 mt-3 px-4 text-center font-bold"><i class="ph-fill ph-warning-circle"></i> Pastikan Anda berada di ruangan ujian yang benar.</p>
</form><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\students\portal\partials\home-guest-forms.blade.php ENDPATH**/ ?>