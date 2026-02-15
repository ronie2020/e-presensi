<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Cetak Kartu Peserta') }}
        </h2>
    </x-slot>

    <div class="py-12 font-sans text-slate-800">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <div class="p-8 text-center bg-slate-900 relative overflow-hidden">
                    <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                    <div class="relative z-10">
                        <div class="w-20 h-20 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/20 text-white text-4xl shadow-lg">
                            <i class="ph-duotone ph-identification-card"></i>
                        </div>
                        <h3 class="text-2xl font-black text-white mb-2">Cetak Kartu Ujian</h3>
                        <p class="text-slate-400 text-sm max-w-md mx-auto">Pilih kelas atau tingkat untuk mencetak kartu login siswa secara massal. Kartu dilengkapi QR Code untuk login cepat.</p>
                    </div>
                </div>

                <div class="p-8" x-data="{ mode: 'level' }">
                    <form action="{{ route('cbt.cards.print') }}" method="GET" target="_blank" class="space-y-6">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Pilihan 1: Per Tingkat --}}
                            <div class="bg-slate-50 p-5 rounded-2xl border transition group cursor-pointer relative"
                                 :class="mode === 'level' ? 'border-blue-500 ring-1 ring-blue-500 bg-blue-50/50' : 'border-slate-200 hover:border-blue-300'">
                                
                                <input type="radio" name="mode" value="level" class="peer sr-only" id="mode_level" x-model="mode">
                                <label for="mode_level" class="absolute inset-0 cursor-pointer z-0"></label>
                                
                                <div class="flex items-center gap-3 mb-3 relative z-10 pointer-events-none">
                                    <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors"
                                         :class="mode === 'level' ? 'border-blue-600 bg-blue-600' : 'border-slate-300'">
                                        <div class="w-2.5 h-2.5 bg-white rounded-full" x-show="mode === 'level'"></div>
                                    </div>
                                    <h4 class="font-bold text-slate-700">Cetak Per Tingkat</h4>
                                </div>
                                
                                {{-- PERBAIKAN: Tambahkan relative & z-10 agar bisa diklik --}}
                                <select name="level" class="w-full rounded-xl border-slate-200 text-sm font-bold focus:ring-blue-500 mt-2 relative z-10 cursor-pointer" 
                                        :disabled="mode !== 'level'">
                                    <option value="all">Semua Tingkat</option>
                                    <option value="7">Kelas 7</option>
                                    <option value="8">Kelas 8</option>
                                    <option value="9">Kelas 9</option>
                                </select>
                            </div>

                            {{-- Pilihan 2: Per Kelas Spesifik --}}
                            <div class="bg-slate-50 p-5 rounded-2xl border transition group cursor-pointer relative"
                                 :class="mode === 'class' ? 'border-blue-500 ring-1 ring-blue-500 bg-blue-50/50' : 'border-slate-200 hover:border-blue-300'">
                                
                                <input type="radio" name="mode" value="class" class="peer sr-only" id="mode_class" x-model="mode">
                                <label for="mode_class" class="absolute inset-0 cursor-pointer z-0"></label>
                                
                                <div class="flex items-center gap-3 mb-3 relative z-10 pointer-events-none">
                                    <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center transition-colors"
                                         :class="mode === 'class' ? 'border-blue-600 bg-blue-600' : 'border-slate-300'">
                                        <div class="w-2.5 h-2.5 bg-white rounded-full" x-show="mode === 'class'"></div>
                                    </div>
                                    <h4 class="font-bold text-slate-700">Cetak Per Kelas</h4>
                                </div>

                                {{-- PERBAIKAN: Tambahkan relative & z-10 agar bisa diklik --}}
                                <select name="class_id" class="w-full rounded-xl border-slate-200 text-sm font-bold focus:ring-blue-500 mt-2 relative z-10 cursor-pointer"
                                        :disabled="mode !== 'class'">
                                    @foreach($classes as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex justify-end">
                            <button type="submit" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-bold shadow-lg shadow-blue-500/30 transition flex items-center gap-3 transform active:scale-95">
                                <i class="ph-bold ph-printer text-xl"></i> Generate Kartu (PDF)
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>