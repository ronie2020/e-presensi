<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Konfirmasi Ujian') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 relative">
                
                <!-- Indikator Aman SEB -->
                <div class="absolute top-0 right-0 m-6">
                    <span class="bg-green-100 text-green-700 text-xs font-bold px-3 py-1 rounded-full border border-green-200 flex items-center gap-1">
                        <i class="ph-fill ph-shield-check"></i> Terproteksi SEB
                    </span>
                </div>

                <h3 class="text-xl font-bold text-slate-800 mb-2">{{ $exam->title }}</h3>
                <p class="text-slate-500 mb-6">
                    Mata Pelajaran: {{ $exam->subject_name }} <br>
                    Durasi: {{ $exam->duration_minutes }} Menit
                </p>

                <!-- Peringatan -->
                <div class="bg-amber-50 border-l-4 border-amber-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="ph-fill ph-warning text-amber-400 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-amber-700">
                                Selama ujian berlangsung:
                                <ul class="list-disc list-inside mt-1 ml-1 text-xs font-bold">
                                    <li>Anda tidak bisa membuka aplikasi lain.</li>
                                    <li>Dilarang mencoba mematikan komputer paksa.</li>
                                    <li>Segala aktivitas diawasi sistem.</li>
                                </ul>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Form untuk Submit Token dan Mulai -->
                <form action="{{ route('student.exam.start', $exam->id) }}" method="POST">
                    @csrf
                    
                    @if($exam->token)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-slate-700 mb-2">Masukkan Token Ujian</label>
                            <input type="text" name="token" required 
                                class="w-full rounded-lg border-slate-300 shadow-sm focus:ring-blue-500 focus:border-blue-500 uppercase text-center text-2xl font-mono tracking-widest p-3" 
                                placeholder="TOKEN" autocomplete="off">
                            <p class="text-xs text-slate-400 mt-2">*Minta token kepada pengawas ujian</p>
                            @error('token')
                                <p class="text-red-500 text-sm mt-1 font-bold">{{ $message }}</p>
                            @enderror
                        </div>
                    @else
                        <div class="bg-blue-50 text-blue-700 p-4 rounded-lg mb-6 text-sm flex items-center gap-2">
                            <i class="ph-fill ph-info"></i>
                            Ujian ini tidak memerlukan token khusus.
                        </div>
                    @endif

                    <div class="flex items-center justify-between border-t border-slate-100 pt-6">
                        <!-- Tombol Keluar SEB -->
                        <a href="javascript:window.close()" onclick="return confirm('Yakin ingin menutup SEB?')" class="text-slate-500 hover:text-red-600 font-medium flex items-center gap-1">
                            <i class="ph-bold ph-power"></i> Keluar SEB
                        </a>

                        <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-lg font-bold hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition transform active:scale-95">
                            Mulai Kerjakan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>