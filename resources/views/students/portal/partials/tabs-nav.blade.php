<div class="mb-8 sticky top-4 z-40 transition-all duration-300" id="sticky-nav">
    <div class="bg-white/90 backdrop-blur-xl p-1.5 rounded-2xl shadow-lg border border-gray-100/50 relative group">
        <div class="absolute right-0 top-0 bottom-0 w-8 bg-gradient-to-l from-white to-transparent pointer-events-none md:hidden z-10 rounded-r-2xl"></div>
        
        <div class="overflow-x-auto custom-scrollbar w-full pb-0.5 md:pb-0 scroll-smooth px-1 md:overflow-visible">
            <div class="flex items-center gap-1 w-max md:w-full md:flex-wrap md:justify-center"> 
                
                {{-- PERBAIKAN: Gunakan variabel $tabs dari Controller, jangan definisikan ulang disini! --}}
                @if(isset($tabs) && is_array($tabs))
                    @foreach($tabs as $key => $tab)
                        <button @click="updateTab('{{ $key }}')" 
                            :class="activeTab === '{{ $key }}' ? 'bg-slate-900 text-white shadow-lg shadow-slate-300 transform scale-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700'"
                            class="px-4 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 flex items-center gap-2 whitespace-nowrap flex-shrink-0 outline-none focus:ring-2 focus:ring-slate-200 mb-1">
                            <i class="ph-bold ph-{{ $tab['icon'] }} text-lg"></i> {{ $tab['label'] }}
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