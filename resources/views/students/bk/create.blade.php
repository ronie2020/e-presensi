@extends('layouts.public')

@section('content')
<div class="py-6 md:py-10 font-sans text-slate-800">
    <div class="w-full max-w-4xl mx-auto px-4 sm:px-6">
        
        <!-- Tombol Kembali -->
        <a href="{{ route('student.bk.index') }}" class="group inline-flex items-center text-sm text-slate-500 hover:text-blue-600 font-bold mb-6 transition-colors">
            <i class="ph-bold ph-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i> Kembali ke Riwayat
        </a>

        <div class="bg-white rounded-[2rem] shadow-xl shadow-blue-900/5 border border-slate-100 overflow-hidden relative">
            <!-- Header Dekorasi -->
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-500 via-indigo-500 to-emerald-500"></div>
            
            <div class="p-6 md:p-10">
                <div class="flex items-center gap-5 mb-8">
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-3xl shadow-sm border border-blue-100">
                        <i class="ph-duotone ph-heart-beat"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-slate-800">Mulai Konsultasi</h2>
                        <p class="text-slate-500 text-sm font-medium">Ceritakan masalahmu, privasi dijamin aman 100%.</p>
                    </div>
                </div>
                
                <form action="{{ route('student.bk.store') }}" method="POST" class="space-y-8">
                    @csrf
                    
                    <!-- 1. Kategori Masalah -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 uppercase tracking-wider mb-3">Topik Permasalahan <span class="text-rose-500">*</span></label>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            @foreach($categories as $cat)
                            <label class="relative cursor-pointer group">
                                <input type="radio" name="bk_category_id" value="{{ $cat->id }}" class="peer sr-only" required>
                                <div class="rounded-xl border-2 border-slate-100 p-4 hover:border-blue-300 hover:bg-blue-50/50 transition-all peer-checked:border-blue-600 peer-checked:bg-blue-50 peer-checked:shadow-md flex items-center gap-3">
                                    <div class="w-5 h-5 rounded-full border-2 border-slate-300 peer-checked:border-blue-600 peer-checked:bg-blue-600 flex items-center justify-center transition-all">
                                        <div class="w-2 h-2 rounded-full bg-white opacity-0 peer-checked:opacity-100"></div>
                                    </div>
                                    <span class="text-sm font-bold text-slate-600 peer-checked:text-blue-800 transition-colors">{{ $cat->name }}</span>
                                </div>
                            </label>
                            @endforeach
                        </div>
                        @error('bk_category_id') <p class="text-rose-500 text-xs mt-2 font-bold flex items-center gap-1"><i class="ph-bold ph-warning"></i> {{ $message }}</p> @enderror
                    </div>

                    <!-- 2. Pesan Awal -->
                    <div>
                        <label for="initial_message" class="block text-sm font-bold text-slate-700 uppercase tracking-wider mb-3">Apa yang sedang kamu rasakan? <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <textarea name="initial_message" id="initial_message" rows="6" 
                                class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white shadow-sm focus:border-blue-500 focus:ring-blue-500 p-5 text-slate-700 leading-relaxed resize-none transition-all placeholder:text-slate-400" 
                                placeholder="Contoh: Saya merasa kesulitan membagi waktu antara belajar dan ekskul, nilai saya jadi turun..." required>{{ old('initial_message') }}</textarea>
                            <div class="absolute top-4 right-4 p-2 bg-white rounded-lg text-slate-300 shadow-sm border border-slate-100">
                                <i class="ph-duotone ph-pencil-simple text-xl"></i>
                            </div>
                        </div>
                        <p class="text-xs text-slate-400 mt-2 text-right font-medium">Minimal 10 karakter</p>
                        @error('initial_message') <p class="text-rose-500 text-xs mt-2 font-bold flex items-center gap-1"><i class="ph-bold ph-warning"></i> {{ $message }}</p> @enderror
                    </div>

                    <!-- 3. Metode Konseling -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 uppercase tracking-wider mb-3">Metode Konseling <span class="text-rose-500">*</span></label>
                        <div class="flex flex-col md:flex-row gap-4">
                            <label class="flex-1 relative cursor-pointer group">
                                <input type="radio" name="method" value="offline" class="peer sr-only" checked>
                                <div class="p-5 rounded-2xl border-2 border-slate-100 hover:bg-slate-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 transition-all flex items-start gap-4">
                                    <div class="p-3 bg-white rounded-xl border border-slate-200 peer-checked:border-blue-200 text-blue-600 text-2xl shadow-sm">
                                        <i class="ph-duotone ph-users-three"></i>
                                    </div>
                                    <div>
                                        <span class="block font-black text-slate-800 peer-checked:text-blue-800 mb-1">Tatap Muka</span>
                                        <span class="text-xs text-slate-500 font-medium leading-tight block">Bertemu langsung dengan Guru BK di ruangan.</span>
                                    </div>
                                </div>
                            </label>
                            <label class="flex-1 relative cursor-pointer group">
                                <input type="radio" name="method" value="online" class="peer sr-only">
                                <div class="p-5 rounded-2xl border-2 border-slate-100 hover:bg-slate-50 peer-checked:border-indigo-500 peer-checked:bg-indigo-50 transition-all flex items-start gap-4">
                                    <div class="p-3 bg-white rounded-xl border border-slate-200 peer-checked:border-indigo-200 text-indigo-600 text-2xl shadow-sm">
                                        <i class="ph-duotone ph-chat-circle-text"></i>
                                    </div>
                                    <div>
                                        <span class="block font-black text-slate-800 peer-checked:text-indigo-800 mb-1">Online (Chat/WA)</span>
                                        <span class="text-xs text-slate-500 font-medium leading-tight block">Konseling jarak jauh melalui media komunikasi.</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Footer & Button -->
                    <div class="pt-8 border-t border-slate-100 flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="flex items-center gap-3 text-slate-500 text-xs bg-slate-50 px-4 py-3 rounded-xl border border-slate-100">
                            <i class="ph-fill ph-lock-key text-blue-400 text-lg"></i>
                            <span>Data ini bersifat <strong>RAHASIA</strong> & hanya diketahui Guru BK.</span>
                        </div>
                        
                        <button type="submit" class="w-full md:w-auto inline-flex justify-center items-center px-8 py-4 border border-transparent font-bold rounded-xl text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-xl shadow-blue-500/30 hover:-translate-y-1 transition-all duration-300">
                            <span>Kirim Pengajuan</span>
                            <i class="ph-bold ph-paper-plane-right ml-2 text-lg"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection