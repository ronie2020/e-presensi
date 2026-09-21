<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Cetak Kartu Peserta') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-[#020b18] text-slate-100 relative overflow-hidden py-8 sm:py-12 font-sans">
        {{-- Ambient Glow Effects --}}
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-600/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/3 right-10 w-96 h-96 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 backdrop-blur-xl overflow-hidden">
                
                {{-- HERO SECTION ELEVATE DARK GLASS --}}
                <div class="p-8 md:p-10 text-center relative overflow-hidden bg-gradient-to-r from-[#031d3d]/90 via-[#021124]/90 to-[#020b18]/90 border-b border-white/10">
                    <div class="absolute -top-10 -left-10 w-48 h-48 bg-[#56bbf1]/10 rounded-3xl rotate-12 pointer-events-none blur-2xl"></div>
                    <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-blue-600/10 rounded-[3rem] -rotate-12 pointer-events-none blur-2xl"></div>
                    
                    <div class="relative z-10">
                        <div class="w-20 h-20 bg-white/5 backdrop-blur-md rounded-[1.5rem] flex items-center justify-center mx-auto mb-5 border border-white/10 text-sky-400 text-4xl shadow-xl shadow-sky-500/10">
                            <i class="ph-duotone ph-address-book"></i>
                        </div>
                        <h3 class="text-2xl md:text-3xl font-black text-white mb-2 tracking-tight">Cetak Kartu Ujian</h3>
                        <p class="text-slate-400 text-sm max-w-md mx-auto font-medium">Pilih tingkat angkatan atau kelas spesifik untuk mencetak kartu login peserta ujian yang berisi QR Code.</p>
                    </div>
                </div>

                <div class="p-8 md:p-10" x-data="{ mode: 'level' }">
                    <form action="{{ route('cbt.cards.print') }}" method="GET" target="_blank">
                        <div class="mb-8">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 ml-1">Pilih Mode Cetak</label>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                
                                {{-- OPSI 1: CETAK PER TINGKAT --}}
                                <div class="group cursor-pointer transition-all duration-300 rounded-[1.5rem] border-2 relative overflow-hidden"
                                     :class="mode === 'level' ? 'border-[#56bbf1] bg-sky-500/10' : 'border-white/10 bg-slate-900/60 hover:border-white/20'"
                                     @click="mode = 'level'">
                                    
                                    {{-- Hidden Radio --}}
                                    <input type="radio" name="mode" value="level" x-model="mode" class="hidden">
                                    
                                    <div class="p-5">
                                        <div class="flex items-center gap-4 mb-2">
                                            <div class="w-12 h-12 rounded-[1rem] flex items-center justify-center text-2xl shrink-0 transition-colors shadow-sm"
                                                 :class="mode === 'level' ? 'bg-[#56bbf1] text-slate-950 border border-[#56bbf1]' : 'bg-white/5 border border-white/10 text-slate-400'">
                                                <i class="ph-fill ph-stack"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-white text-sm mb-0.5">Cetak Per Tingkat</h4>
                                                <p class="text-xs text-slate-400 font-medium">Pilih angkatan kelas.</p>
                                            </div>
                                            
                                            {{-- Checklist Kanan Atas --}}
                                            <div class="absolute top-5 right-5 w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all"
                                                 :class="mode === 'level' ? 'border-[#56bbf1] bg-[#56bbf1] text-slate-950' : 'border-slate-700 text-transparent'">
                                                <i class="ph-bold ph-check text-xs"></i>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Form Select --}}
                                    <div class="px-5 pb-5" x-show="mode === 'level'" x-transition @click.stop>
                                        <div class="relative mt-2">
                                            <select name="level" class="w-full rounded-xl border-white/10 bg-slate-900/90 font-bold text-white py-3.5 pl-4 pr-10 appearance-none cursor-pointer focus:ring-[#56bbf1] focus:border-[#56bbf1] transition-shadow shadow-sm [color-scheme:dark]" :disabled="mode !== 'level'">
                                                <option value="all" class="bg-slate-900 text-white">Semua Tingkat (Seluruh Siswa)</option>
                                                <option value="7" class="bg-slate-900 text-white">Kelas 7</option>
                                                <option value="8" class="bg-slate-900 text-white">Kelas 8</option>
                                                <option value="9" class="bg-slate-900 text-white">Kelas 9</option>
                                            </select>
                                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                                                <i class="ph-bold ph-caret-down text-lg"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- OPSI 2: CETAK PER KELAS SPESIFIK --}}
                                <div class="group cursor-pointer transition-all duration-300 rounded-[1.5rem] border-2 relative overflow-hidden"
                                     :class="mode === 'class' ? 'border-[#56bbf1] bg-sky-500/10' : 'border-white/10 bg-slate-900/60 hover:border-white/20'"
                                     @click="mode = 'class'">
                                    
                                    {{-- Hidden Radio --}}
                                    <input type="radio" name="mode" value="class" x-model="mode" class="hidden">
                                    
                                    <div class="p-5">
                                        <div class="flex items-center gap-4 mb-2">
                                            <div class="w-12 h-12 rounded-[1rem] flex items-center justify-center text-2xl shrink-0 transition-colors shadow-sm"
                                                 :class="mode === 'class' ? 'bg-[#56bbf1] text-slate-950 border border-[#56bbf1]' : 'bg-white/5 border border-white/10 text-slate-400'">
                                                <i class="ph-fill ph-users-three"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-white text-sm mb-0.5">Cetak Per Kelas</h4>
                                                <p class="text-xs text-slate-400 font-medium">Pilih kelas spesifik.</p>
                                            </div>
                                            
                                            {{-- Checklist Kanan Atas --}}
                                            <div class="absolute top-5 right-5 w-5 h-5 rounded-full border-2 flex items-center justify-center transition-all"
                                                 :class="mode === 'class' ? 'border-[#56bbf1] bg-[#56bbf1] text-slate-950' : 'border-slate-700 text-transparent'">
                                                <i class="ph-bold ph-check text-xs"></i>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Form Select --}}
                                    <div class="px-5 pb-5" x-show="mode === 'class'" x-transition @click.stop>
                                        <div class="relative mt-2">
                                            <select name="class_id" class="w-full rounded-xl border-white/10 bg-slate-900/90 font-bold text-white py-3.5 pl-4 pr-10 appearance-none cursor-pointer focus:ring-[#56bbf1] focus:border-[#56bbf1] transition-shadow shadow-sm [color-scheme:dark]" :disabled="mode !== 'class'">
                                                @foreach($classes as $c)
                                                    <option value="{{ $c->id }}" class="bg-slate-900 text-white">{{ $c->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400">
                                                <i class="ph-bold ph-caret-down text-lg"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>

                        {{-- TOMBOL SUBMIT --}}
                        <div class="pt-6 border-t border-white/10 flex justify-end">
                            <button type="submit" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white rounded-2xl font-bold shadow-lg shadow-sky-500/25 transition-all flex items-center justify-center gap-3 transform active:scale-95 border border-sky-400/30">
                                <i class="ph-bold ph-printer text-xl"></i> 
                                <span>Generate Kartu (PDF)</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>