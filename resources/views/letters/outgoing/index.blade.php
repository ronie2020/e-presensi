<x-app-layout>
    {{-- Load SweetAlert --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans text-slate-700 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO SECTION MICROSOFT ELEVATE THEME --}}
            <div class="relative rounded-[2rem] bg-elevate-gradient-main p-8 mb-8 text-elevate-dark shadow-xl shadow-elevate-accent/10 overflow-hidden border border-white/60">
                <div class="absolute -top-10 -left-10 w-48 h-48 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/20 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>
                <div class="absolute top-10 right-32 w-24 h-24 bg-white/40 rounded-2xl rotate-45 pointer-events-none shadow-sm"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-center md:text-left">
                        <div class="flex items-center justify-center md:justify-start gap-2 mb-2">
                            <span class="text-[10px] font-bold text-elevate-dark/70 uppercase tracking-wider bg-white/50 px-3 py-1 rounded-full border border-white/60 backdrop-blur-sm shadow-sm">Administrasi Sekolah</span>
                        </div>
                        <h1 class="text-3xl font-extrabold tracking-tight mb-2 flex items-center justify-center md:justify-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-elevate-accent/20 text-elevate-primary flex items-center justify-center shrink-0">
                                <i class="ph-bold ph-paper-plane-tilt text-xl"></i>
                            </div>
                            Arsip Surat Keluar
                        </h1>
                        <p class="text-elevate-dark/80 text-sm font-medium leading-relaxed max-w-lg ml-0 md:ml-12">
                            Kelola dokumen persuratan keluar. Terintegrasi langsung dengan pembuatan draf Surat Perintah Tugas (SPT) dan SPPD.
                        </p>
                        
                        <div class="mt-6 flex flex-wrap justify-center md:justify-start gap-3 ml-0 md:ml-12">
                            <a href="{{ route('letters.outgoing.create') }}" class="group bg-white text-elevate-dark px-5 py-3 rounded-2xl font-bold text-sm transition-all hover:bg-slate-50 flex items-center gap-2 shadow-lg shadow-elevate-dark/5 border border-white active:scale-95">
                                <div class="w-7 h-7 rounded-full bg-elevate-accent/20 text-elevate-primary flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <i class="ph-bold ph-plus text-sm"></i>
                                </div>
                                <span>Buat Surat Keluar</span>
                            </a>
                        </div>
                    </div>
                    
                    {{-- Statistik Ringkas --}}
                    <div class="flex gap-3">
                        <div class="bg-white/60 backdrop-blur-md px-6 py-5 rounded-[2rem] border border-white shadow-sm text-center min-w-[140px]">
                            <span class="block text-4xl font-black text-elevate-dark mb-1">{{ $letters->total() }}</span>
                            <span class="text-[10px] uppercase font-bold text-elevate-primary tracking-wider">Total Keluar</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Toolbar & Table --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row gap-4 justify-between items-center">
                    <h3 class="font-black text-elevate-dark text-lg flex items-center gap-2">
                        <i class="ph-fill ph-list-dashes text-elevate-primary"></i> Data Surat Keluar
                    </h3>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50/80 text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-5 w-48">Agenda & Tgl</th>
                                <th class="px-6 py-5">Identitas Surat</th>
                                <th class="px-6 py-5 w-1/3">Tujuan & Perihal</th>
                                <th class="px-6 py-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($letters as $letter)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-6 py-5 align-top">
                                    <div class="font-mono font-black text-elevate-primary bg-elevate-accent/10 px-3 py-1.5 rounded-lg border border-elevate-accent/20 inline-block text-sm mb-2 shadow-sm">
                                        #{{ $letter->nomor_agenda }}
                                    </div>
                                    <div class="text-xs text-slate-500 font-medium flex items-center gap-1.5 mt-1">
                                        <i class="ph-bold ph-calendar-blank"></i> {{ \Carbon\Carbon::parse($letter->tgl_surat)->format('d M Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <div class="font-bold text-elevate-dark text-sm mb-2 leading-snug">{{ $letter->nomor_surat }}</div>
                                    <span class="inline-flex px-2 py-1 rounded border border-slate-200 bg-slate-50 text-[10px] font-bold text-slate-600 tracking-wider">
                                        {{ $letter->sifat_surat }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <div class="flex items-start gap-2 mb-2">
                                        <i class="ph-fill ph-buildings text-elevate-primary mt-0.5"></i>
                                        <span class="font-bold text-elevate-dark text-sm">{{ $letter->tujuan_surat }}</span>
                                    </div>
                                    <p class="text-sm text-slate-600 leading-relaxed line-clamp-2 font-medium">
                                        {{ $letter->perihal }}
                                    </p>
                                </td>
                                <td class="px-6 py-5 align-top text-right">
                                    <div class="flex flex-col items-end gap-2">
                                        @if($letter->file_path)
                                            <a href="{{ asset('storage/' . $letter->file_path) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-elevate-accent/10 border border-elevate-accent/20 text-elevate-primary hover:bg-elevate-primary hover:text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                                                <i class="ph-bold ph-download-simple text-base"></i> Lampiran
                                            </a>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-50 text-slate-400 rounded-lg text-xs font-medium border border-slate-100">
                                                <i class="ph-bold ph-file-dashed"></i> No File
                                            </span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-20 text-center">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                        <i class="ph-duotone ph-paper-plane-tilt text-4xl"></i>
                                    </div>
                                    <h3 class="text-elevate-dark font-bold text-lg">Belum ada Surat Keluar</h3>
                                    <p class="text-slate-500 text-sm mt-1">Silakan buat surat keluar baru melalui tombol di atas.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="p-6 border-t border-slate-100 bg-slate-50/50">
                    {{ $letters->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>