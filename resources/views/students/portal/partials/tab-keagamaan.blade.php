<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="bg-white p-6 rounded-3xl border border-teal-100 shadow-sm flex items-center gap-6 group hover:border-teal-200 transition-colors">
        <div class="w-16 h-16 rounded-2xl bg-teal-50 flex items-center justify-center text-teal-600 group-hover:scale-110 transition-transform"><i class="ph-duotone ph-sun-horizon text-3xl"></i></div>
        <div><h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Sholat Dhuha</h4><p class="text-4xl font-black text-slate-800">{{ $sholat_dhuha ?? 0 }} <span class="text-sm font-bold text-slate-400">Kali</span></p></div>
    </div>
    <div class="bg-white p-6 rounded-3xl border border-orange-100 shadow-sm flex items-center gap-6 group hover:border-orange-200 transition-colors">
        <div class="w-16 h-16 rounded-2xl bg-orange-50 flex items-center justify-center text-orange-600 group-hover:scale-110 transition-transform"><i class="ph-duotone ph-clock-afternoon text-3xl"></i></div>
        <div><h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Sholat Dhuhur</h4><p class="text-4xl font-black text-slate-800">{{ $sholat_dhuhur ?? 0 }} <span class="text-sm font-bold text-slate-400">Kali</span></p></div>
    </div>
</div>