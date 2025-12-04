<x-app-layout>
    <div class="py-6 sm:py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER --}}
            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <div>
                    <h1 class="text-2xl font-black text-slate-800 flex items-center gap-2">
                        <i class="ph-duotone ph-clock-counter-clockwise text-blue-600"></i> Riwayat Mengajar
                    </h1>
                    <p class="text-slate-500 text-sm mt-1">Arsip kegiatan belajar mengajar Anda.</p>
                </div>

                {{-- Filter Bulan --}}
                <form method="GET" action="{{ route('teaching.history') }}" class="flex items-center gap-2 bg-white p-1 rounded-xl border border-slate-200 shadow-sm">
                    <input type="month" name="month" value="{{ $month }}" onchange="this.form.submit()" 
                        class="border-none text-sm font-bold text-slate-600 focus:ring-0 rounded-lg cursor-pointer">
                </form>
            </div>

            {{-- LIST RIWAYAT --}}
            <div class="space-y-6">
                @forelse($histories as $history)
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-md transition-all duration-300">
                        {{-- Garis Indikator --}}
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-gradient-to-b from-blue-500 to-indigo-600"></div>

                        <div class="flex flex-col md:flex-row gap-6">
                            {{-- Tanggal & Waktu --}}
                            <div class="flex flex-row md:flex-col items-center md:items-start gap-3 md:gap-1 md:w-32 shrink-0 border-b md:border-b-0 md:border-r border-slate-100 pb-4 md:pb-0">
                                <div class="text-3xl font-black text-slate-800 leading-none">
                                    {{ \Carbon\Carbon::parse($history->date)->format('d') }}
                                </div>
                                <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                                    {{ \Carbon\Carbon::parse($history->date)->translatedFormat('M Y') }}
                                </div>
                                <div class="md:mt-2 px-2 py-1 bg-slate-50 rounded-lg text-xs font-mono font-bold text-slate-500">
                                    {{ \Carbon\Carbon::parse($history->started_at)->format('H:i') }}
                                </div>
                            </div>

                            {{-- Konten Utama --}}
                            <div class="flex-1">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h3 class="font-bold text-lg text-slate-800 group-hover:text-blue-600 transition-colors">
                                            {{ $history->schedule->subject->name ?? 'Mapel Dihapus' }}
                                        </h3>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100 mt-1">
                                            <i class="ph-bold ph-chalkboard"></i> Kelas {{ $history->schedule->schoolClass->name ?? '-' }}
                                        </span>
                                    </div>
                                    
                                    {{-- Tombol Lihat Detail --}}
                                    <a href="{{ route('teaching.show', $history->id) }}" class="p-2 text-slate-300 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition">
                                        <i class="ph-bold ph-arrow-right text-xl"></i>
                                    </a>
                                </div>

                                {{-- Materi --}}
                                <div class="bg-slate-50 rounded-xl p-3 mb-4">
                                    <p class="text-xs font-bold text-slate-400 uppercase mb-1">Materi / Topik:</p>
                                    <p class="text-sm font-medium text-slate-700 line-clamp-2">
                                        {{ $history->topic ?? 'Tidak ada judul topik.' }}
                                    </p>
                                </div>

                                {{-- Statistik & Bukti --}}
                                <div class="flex items-center justify-between border-t border-slate-50 pt-3">
                                    {{-- Kehadiran --}}
                                    <div class="flex items-center gap-3 text-xs">
                                        <span class="flex items-center gap-1 font-bold text-emerald-600" title="Hadir">
                                            <i class="ph-fill ph-check-circle"></i> {{ $history->hadir }}
                                        </span>
                                        <span class="flex items-center gap-1 font-bold text-rose-500" title="Alpha">
                                            <i class="ph-fill ph-x-circle"></i> {{ $history->alpha }}
                                        </span>
                                        <span class="flex items-center gap-1 font-bold text-blue-500" title="Sakit/Izin">
                                            <i class="ph-fill ph-info"></i> {{ $history->sakit + $history->izin }}
                                        </span>
                                    </div>

                                    {{-- Indikator Bukti --}}
                                    <div class="flex gap-2">
                                        @if($history->photo_proof)
                                            <span class="text-purple-500" title="Ada Foto"><i class="ph-fill ph-image"></i></span>
                                        @endif
                                        @if($history->video_link)
                                            <span class="text-red-500" title="Ada Video"><i class="ph-fill ph-youtube-logo"></i></span>
                                        @endif
                                        @if($history->reference_link)
                                            <span class="text-blue-500" title="Ada Link Materi"><i class="ph-fill ph-link"></i></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-16">
                        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="ph-duotone ph-notebook text-4xl text-slate-300"></i>
                        </div>
                        <h3 class="text-slate-800 font-bold text-lg">Belum Ada Riwayat</h3>
                        <p class="text-slate-500 text-sm mt-1">Anda belum memiliki sesi mengajar yang selesai bulan ini.</p>
                        <a href="{{ route('teaching.index') }}" class="inline-block mt-4 px-6 py-2 bg-blue-600 text-white rounded-xl text-sm font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 transition">
                            Mulai Mengajar
                        </a>
                    </div>
                @endforelse

                {{-- Pagination --}}
                <div class="pt-4">
                    {{ $histories->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>