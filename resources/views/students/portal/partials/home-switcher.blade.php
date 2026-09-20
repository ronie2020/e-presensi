<div class="grid grid-cols-3 gap-2 mb-4 p-1.5 bg-[#021124]/70 rounded-2xl border border-white/15 backdrop-blur-md">
    <button @click="mode = 'portal'" 
            class="py-2.5 sm:py-3 rounded-xl text-xs sm:text-sm font-bold transition-all duration-300 flex flex-col sm:flex-row items-center justify-center gap-1.5" 
            :class="mode === 'portal' ? 'bg-elevate-accent text-[#021124] shadow-[0_0_15px_rgba(86,187,241,0.5)] font-black scale-100' : 'text-slate-300 hover:text-white hover:bg-white/10'">
        <i class="ph-bold ph-magnifying-glass text-base sm:text-lg"></i> <span>Cek Data (NISN)</span>
    </button>
    <button @click="mode = 'lms'" 
            class="py-2.5 sm:py-3 rounded-xl text-xs sm:text-sm font-bold transition-all duration-300 flex flex-col sm:flex-row items-center justify-center gap-1.5" 
            :class="mode === 'lms' ? 'bg-gradient-to-r from-blue-600 to-indigo-600 text-white shadow-[0_0_15px_rgba(37,99,235,0.5)] font-black scale-100' : 'text-slate-300 hover:text-white hover:bg-white/10'">
        <i class="ph-bold ph-books text-base sm:text-lg"></i> <span>Ruang Belajar</span>
    </button>
    <button @click="mode = 'cbt'" 
            class="py-2.5 sm:py-3 rounded-xl text-xs sm:text-sm font-bold transition-all duration-300 flex flex-col sm:flex-row items-center justify-center gap-1.5" 
            :class="mode === 'cbt' ? 'bg-gradient-to-r from-rose-600 to-red-600 text-white shadow-[0_0_15px_rgba(225,29,72,0.5)] font-black scale-100' : 'text-slate-300 hover:text-white hover:bg-white/10'">
        <i class="ph-bold ph-desktop text-base sm:text-lg"></i> <span>Ruang Ujian</span>
    </button>
</div>