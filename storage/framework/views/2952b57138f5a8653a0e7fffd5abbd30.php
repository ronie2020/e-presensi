<div class="grid grid-cols-3 gap-2 mb-3 p-1.5 bg-slate-100/80 rounded-[1.5rem] md:rounded-[2rem] border border-slate-200">
    <button @click="mode = 'portal'" 
            class="py-2 md:py-3 rounded-[1.2rem] md:rounded-[1.5rem] text-[10px] md:text-sm font-bold transition-all duration-300 flex flex-col sm:flex-row items-center justify-center gap-1 md:gap-2" 
            :class="mode === 'portal' ? 'bg-white text-slate-800 shadow-md scale-100 ring-1 ring-black/5' : 'text-slate-500 hover:text-slate-700 hover:bg-white/50'">
        <i class="ph-bold ph-magnifying-glass text-lg md:text-xl"></i> <span>Cek Data</span>
    </button>
    <button @click="mode = 'lms'" 
            class="py-2 md:py-3 rounded-[1.2rem] md:rounded-[1.5rem] text-[10px] md:text-sm font-bold transition-all duration-300 flex flex-col sm:flex-row items-center justify-center gap-1 md:gap-2" 
            :class="mode === 'lms' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/30 scale-100' : 'text-slate-500 hover:text-blue-600 hover:bg-white/50'">
        <i class="ph-bold ph-books text-lg md:text-xl"></i> <span>Masuk Kelas</span>
    </button>
    <button @click="mode = 'cbt'" 
            class="py-2 md:py-3 rounded-[1.2rem] md:rounded-[1.5rem] text-[10px] md:text-sm font-bold transition-all duration-300 flex flex-col sm:flex-row items-center justify-center gap-1 md:gap-2" 
            :class="mode === 'cbt' ? 'bg-rose-600 text-white shadow-md shadow-rose-500/30 scale-100' : 'text-slate-500 hover:text-rose-600 hover:bg-white/50'">
        <i class="ph-bold ph-desktop text-lg md:text-xl"></i> <span>Masuk Ujian</span>
    </button>
</div><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/students/portal/partials/home-switcher.blade.php ENDPATH**/ ?>