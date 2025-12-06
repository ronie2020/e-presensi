<x-student-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 leading-tight flex items-center gap-2">
            <i class="ph-duotone ph-info text-blue-600"></i>
            {{ __('Konfirmasi Ujian') }}
        </h2>
    </x-slot>

    <div class="max-w-xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl shadow-slate-200 sm:rounded-3xl p-8 relative border border-slate-100">
            
            <!-- Indikator Aman SEB -->
            <div class="absolute top-0 right-0 m-6">
                <span class="bg-emerald-50 text-emerald-600 text-[10px] font-black px-3 py-1 rounded-full border border-emerald-100 flex items-center gap-1 uppercase tracking-wide">
                    <i class="ph-fill ph-shield-check text-base"></i> Terproteksi SEB
                </span>
            </div>

            <div class="mb-6">
                <h3 class="text-2xl font-black text-slate-800 mb-1 leading-tight">{{ $exam->title }}</h3>
                <p class="text-slate-500 font-medium">{{ $exam->subject_name }}</p>
            </div>

            <div class="flex items-center gap-4 mb-8 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                <div class="flex-1 flex items-center gap-3">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-blue-600 shadow-sm">
                        <i class="ph-fill ph-timer text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase">Durasi</p>
                        <p class="font-bold text-slate-800">{{ $exam->duration_minutes }} Menit</p>
                    </div>
                </div>
                <div class="w-px h-10 bg-slate-200"></div>
                <div class="flex-1 flex items-center gap-3 pl-4">
                    <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-purple-600 shadow-sm">
                        <i class="ph-fill ph-question text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase">Jumlah Soal</p>
                        <p class="font-bold text-slate-800">{{ $exam->questions_count ?? '-' }} Soal</p>
                    </div>
                </div>
            </div>

            <!-- Peringatan -->
            <div class="bg-amber-50 border border-amber-100 rounded-2xl p-5 mb-8">
                <div class="flex gap-4">
                    <div class="flex-shrink-0">
                        <i class="ph-fill ph-warning-octagon text-amber-500 text-2xl"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-amber-800 text-sm mb-1">Peraturan Ujian</h4>
                        <ul class="text-xs text-amber-700 space-y-1 font-medium list-disc list-inside">
                            <li>Dilarang membuka aplikasi lain selain SEB.</li>
                            <li>Dilarang meninggalkan komputer selama ujian.</li>
                            <li>Sistem akan mencatat segala bentuk kecurangan.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Form untuk Submit Token dan Mulai -->
            <form action="{{ route('student.exam.start', $exam->id) }}" method="POST">
                @csrf
                
                @if($exam->token)
                    <div class="mb-8">
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">Masukkan Token Ujian</label>
                        <input type="text" name="token" required 
                            class="w-full rounded-xl border-slate-300 shadow-sm focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 uppercase text-center text-3xl font-black tracking-[0.5em] p-4 text-slate-800 placeholder-slate-300" 
                            placeholder="TOKEN" autocomplete="off">
                        <p class="text-xs text-slate-400 mt-2 text-center font-medium">*Minta token kepada pengawas ujian</p>
                        @error('token')
                            <p class="text-rose-500 text-sm mt-2 font-bold text-center bg-rose-50 p-2 rounded-lg border border-rose-100">{{ $message }}</p>
                        @enderror
                    </div>
                @else
                    <div class="bg-blue-50 text-blue-700 p-4 rounded-xl mb-8 text-sm flex items-center justify-center gap-2 border border-blue-100 font-bold">
                        <i class="ph-fill ph-info"></i>
                        Ujian ini tidak memerlukan token.
                    </div>
                @endif

                <div class="flex flex-col-reverse sm:flex-row items-center justify-between gap-4 pt-2">
                    <!-- Tombol Keluar SEB -->
                    <a href="javascript:window.close()" onclick="return confirm('Yakin ingin menutup SEB?')" class="text-slate-400 hover:text-rose-600 text-sm font-bold flex items-center gap-2 transition-colors py-2">
                        <i class="ph-bold ph-power"></i> Keluar Aplikasi
                    </a>

                    <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all transform active:scale-95 flex items-center justify-center gap-2">
                        Mulai Kerjakan <i class="ph-bold ph-arrow-right"></i>
                    </button>
                </div>
            </form>

        </div>
    </div>
</x-student-layout>