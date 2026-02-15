<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Bank Soal Terpusat') }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-10 font-sans text-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- HERO SECTION --}}
            <div class="relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-slate-900 via-indigo-900 to-indigo-800 p-8 text-white shadow-xl shadow-indigo-900/30 overflow-hidden border border-white/10">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <a href="{{ route('cbt.index') }}" class="text-xs font-bold text-indigo-300 hover:text-white transition flex items-center gap-1">
                                <i class="ph-bold ph-arrow-left"></i> Dashboard Ujian
                            </a>
                            <span class="text-white/30 text-xs">•</span>
                            <span class="text-[10px] font-bold text-indigo-200 uppercase tracking-wider">Gudang Soal</span>
                        </div>
                        <h1 class="text-3xl font-black tracking-tight leading-none text-white mb-2">Bank Soal Sekolah</h1>
                        <p class="text-indigo-200 text-sm font-medium">Buat paket soal sekali, gunakan berkali-kali untuk berbagai ujian.</p>
                    </div>
                    
                    {{-- Tombol Buat Bank Baru --}}
                    <div x-data="{ open: false }">
                        <button @click="open = true" class="group flex items-center gap-3 px-6 py-4 bg-white text-indigo-900 rounded-2xl font-bold hover:bg-indigo-50 transition shadow-lg">
                            <i class="ph-bold ph-plus-circle text-xl"></i>
                            <span>Buat Bank Soal</span>
                        </button>

                        {{-- MODAL BUAT BANK --}}
                        <div x-show="open" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
                            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="open = false"></div>
                            <div class="flex min-h-screen items-center justify-center p-4">
                                <div class="relative bg-white rounded-[2.5rem] shadow-2xl max-w-md w-full p-8 border border-slate-100">
                                    <h3 class="text-xl font-black text-slate-800 mb-6 flex items-center gap-2">
                                        <i class="ph-fill ph-folder-plus text-indigo-600"></i> Bank Soal Baru
                                    </h3>
                                    <form action="{{ route('bank.store') }}" method="POST" class="space-y-4">
                                        @csrf
                                        <div>
                                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Judul Paket Soal</label>
                                            <input type="text" name="title" required class="w-full rounded-xl border-slate-200 font-bold text-slate-700 py-3 px-4 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Contoh: Bank Soal Matematika Bab 1">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Mata Pelajaran</label>
                                            <input type="text" name="subject_name" required class="w-full rounded-xl border-slate-200 font-bold text-slate-700 py-3 px-4 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Nama Mapel">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Kelas</label>
                                            <select name="class_level" class="w-full rounded-xl border-slate-200 font-bold text-slate-700 py-3 px-4 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                                                <option value="7">Kelas 7</option>
                                                <option value="8">Kelas 8</option>
                                                <option value="9">Kelas 9</option>
                                            </select>
                                        </div>
                                        <div class="pt-4 flex gap-3">
                                            <button type="button" @click="open = false" class="flex-1 py-3 rounded-xl border border-slate-200 font-bold text-slate-500 hover:bg-slate-50">Batal</button>
                                            <button type="submit" class="flex-1 py-3 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-500/30">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- LIST BANK SOAL --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($banks as $bank)
                    <div class="bg-white border border-slate-100 rounded-[2rem] p-6 hover:shadow-xl hover:shadow-indigo-900/5 hover:border-indigo-200 transition-all duration-300 group relative flex flex-col h-full">
                        <div class="mb-5">
                            <div class="flex justify-between items-start mb-3">
                                <span class="inline-block px-3 py-1 bg-indigo-50 text-indigo-600 border border-indigo-100 rounded-lg text-[10px] font-black uppercase tracking-wide">
                                    {{ $bank->subject_name }} • Kls {{ $bank->class_level }}
                                </span>
                                <span class="text-[10px] font-mono text-slate-300 group-hover:text-slate-400 transition">{{ $bank->code }}</span>
                            </div>
                            <h4 class="font-black text-xl text-slate-800 leading-tight group-hover:text-indigo-600 transition-colors line-clamp-2">
                                {{ $bank->title }}
                            </h4>
                        </div>
                        
                        <div class="flex-1 flex items-end">
                            <div class="flex items-center gap-2 text-slate-500 text-xs font-bold bg-slate-50 px-4 py-2 rounded-xl w-full border border-slate-100">
                                <i class="ph-fill ph-files text-indigo-400 text-lg"></i>
                                <span class="text-slate-700 text-sm">{{ $bank->questions_count }}</span> Soal
                            </div>
                        </div>

                        <div class="mt-6 pt-4 border-t border-slate-50 flex gap-2">
                            <a href="{{ route('bank.manage', $bank->id) }}" class="flex-1 flex items-center justify-center p-2.5 bg-indigo-600 text-white rounded-xl text-xs font-bold hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/20">
                                <i class="ph-bold ph-list-plus text-lg mr-2"></i> Isi Soal
                            </a>
                            <form action="{{ route('bank.destroy', $bank->id) }}" method="POST" onsubmit="return confirm('Hapus Bank Soal ini? Semua soal di dalamnya akan terhapus.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="w-10 h-10 flex items-center justify-center bg-white border border-rose-100 text-rose-500 rounded-xl hover:bg-rose-50 hover:border-rose-200 transition">
                                    <i class="ph-bold ph-trash text-lg"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-20 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200">
                        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                            <i class="ph-duotone ph-folder-open text-5xl"></i>
                        </div>
                        <h3 class="text-slate-800 font-bold text-xl mb-2">Belum ada Bank Soal</h3>
                        <p class="text-slate-500 text-sm">Buat bank soal pertama Anda untuk mulai menabung soal.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>