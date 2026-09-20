<div class="mb-8 sticky top-4 z-40 transition-all duration-300" id="sticky-nav">
    <div class="bg-[#031d3d]/90 dark:bg-[#031d3d]/95 backdrop-blur-2xl p-2 rounded-[1.5rem] shadow-2xl shadow-black/40 border border-white/15 relative group transition-colors duration-300">
        {{-- Gradient Indikator Scroll Kanan (Mobile) --}}
        <div class="absolute right-0 top-0 bottom-0 w-8 bg-gradient-to-l from-[#031d3d] to-transparent pointer-events-none md:hidden z-10 rounded-r-[1.5rem]"></div>
        {{-- Gradient Indikator Scroll Kiri (Mobile) --}}
        <div class="absolute left-0 top-0 bottom-0 w-8 bg-gradient-to-r from-[#031d3d] to-transparent pointer-events-none md:hidden z-10 rounded-l-[1.5rem]"></div>
        
        <div class="overflow-x-auto custom-scrollbar w-full scroll-smooth px-1 md:overflow-visible">
            <div class="flex items-center gap-2 w-max md:w-full md:flex-wrap md:justify-center py-1"> 
                
                @if(isset($tabs) && is_array($tabs))
                    @foreach($tabs as $key => $tab)
                        <button @click="
                                    updateTab('{{ $key }}');
                                    $el.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
                                " 
                            :class="activeTab === '{{ $key }}' 
                                ? 'bg-gradient-to-r from-elevate-primary via-[#0d52a1] to-sky-600 text-white shadow-lg shadow-elevate-primary/40 border border-elevate-accent/40 scale-100' 
                                : 'bg-transparent text-slate-300 hover:bg-white/10 hover:text-white border border-transparent'"
                            class="relative px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 flex items-center gap-2 whitespace-nowrap flex-shrink-0 outline-none group">
                            
                            {{-- Ikon Dinamis --}}
                            <div class="w-6 h-6 rounded-md flex items-center justify-center transition-colors"
                                 :class="activeTab === '{{ $key }}' ? 'bg-white/20 text-white' : 'bg-white/10 text-elevate-accent group-hover:bg-elevate-primary group-hover:text-white'">
                                <i :class="activeTab === '{{ $key }}' ? 'ph-fill' : 'ph-bold'" 
                                   class="ph-{{ $tab['icon'] }} text-base transition-colors duration-300"></i>
                            </div>
                             
                            {{ $tab['label'] }}

                            {{-- NOTIFICATION BADGES --}}
                            @if(isset($tab['badge']) && $tab['badge'] > 0)
                                <span class="absolute -top-1.5 -right-1 flex h-4 w-4 z-20">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-500 opacity-75"></span>
                                    <span class="relative inline-flex items-center justify-center rounded-full h-4 w-4 bg-rose-600 text-[9px] font-black text-white border border-white shadow-sm">
                                        {{ $tab['badge'] > 9 ? '9+' : $tab['badge'] }}
                                    </span>
                                </span>
                            @endif
                        </button>
                    @endforeach
                @else
                    <div class="text-xs text-red-400 font-bold px-4 py-2">Error: Menu Tabs tidak dimuat dari Controller.</div>
                @endif

            </div>
        </div>
    </div>
</div>