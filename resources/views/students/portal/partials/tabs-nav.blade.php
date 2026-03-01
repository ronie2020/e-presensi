<div class="mb-8 sticky top-4 z-40 transition-all duration-300" id="sticky-nav">
    <div class="bg-white/90 backdrop-blur-xl p-1.5 rounded-2xl shadow-lg border border-gray-100/50 relative group">
        {{-- Gradient Indikator Scroll Kanan (Mobile) --}}
        <div class="absolute right-0 top-0 bottom-0 w-8 bg-gradient-to-l from-white to-transparent pointer-events-none md:hidden z-10 rounded-r-2xl"></div>
        {{-- Gradient Indikator Scroll Kiri (Mobile) --}}
        <div class="absolute left-0 top-0 bottom-0 w-8 bg-gradient-to-r from-white to-transparent pointer-events-none md:hidden z-10 rounded-l-2xl"></div>
        
        <div class="overflow-x-auto custom-scrollbar w-full pb-0.5 md:pb-0 scroll-smooth px-1 md:overflow-visible">
            <div class="flex items-center gap-1 w-max md:w-full md:flex-wrap md:justify-center"> 
                
                @if(isset($tabs) && is_array($tabs))
                    @foreach($tabs as $key => $tab)
                        {{-- Class 'relative' ditambahkan agar posisi badge (absolute) sesuai dengan tombol ini --}}
                        <button @click="
                                    updateTab('{{ $key }}');
                                    // PENGEMBANGAN: Auto-scroll menu yang diklik ke tengah layar (Berguna untuk Mobile)
                                    $el.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                                " 
                            :class="activeTab === '{{ $key }}' ? 'bg-slate-900 text-white shadow-lg shadow-slate-300 transform scale-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700'"
                            class="relative px-4 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 flex items-center gap-2 whitespace-nowrap flex-shrink-0 outline-none focus:ring-2 focus:ring-slate-200 mb-1">
                            
                            {{-- PENGEMBANGAN: Ikon Dinamis (ph-fill jika aktif, ph-bold jika tidak aktif) --}}
                            <i :class="activeTab === '{{ $key }}' ? 'ph-fill text-blue-300' : 'ph-bold'" 
                               class="ph-{{ $tab['icon'] }} text-lg transition-colors duration-300"></i> 
                            {{ $tab['label'] }}

                            {{-- IMPLEMENTASI IDE NO 2: NOTIFICATION BADGES --}}
                            @if(isset($tab['badge']) && $tab['badge'] > 0)
                                <span class="absolute -top-1.5 -right-1 flex h-4 w-4 z-20">
                                    {{-- Animasi berdenyut (Pulse/Ping) --}}
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                    {{-- Angka notifikasi --}}
                                    <span class="relative inline-flex items-center justify-center rounded-full h-4 w-4 bg-rose-500 text-[9px] font-black text-white border border-white shadow-sm">
                                        {{ $tab['badge'] > 9 ? '9+' : $tab['badge'] }}
                                    </span>
                                </span>
                            @endif

                        </button>
                    @endforeach
                @else
                    {{-- Fallback jika variabel controller gagal (Jaga-jaga) --}}
                    <div class="text-xs text-red-500 font-bold px-4 py-2">Error: Menu Tabs tidak dimuat dari Controller.</div>
                @endif

            </div>
        </div>
    </div>
</div>