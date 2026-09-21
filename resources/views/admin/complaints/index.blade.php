<x-app-layout>
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>

    <div class="font-sans p-4 md:p-8 space-y-8 min-h-screen bg-[#020b18] text-slate-100">
        
        {{-- HERO SECTION ELEVATE DARK GLASS --}}
        <div class="animate-enter relative rounded-[3rem] bg-gradient-to-r from-[#031d3d] via-[#021124] to-[#031d3d] p-8 md:p-12 text-white shadow-2xl overflow-hidden group border border-white/10">
            <div class="absolute -top-10 -left-10 w-48 h-48 bg-[#56bbf1]/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
            <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-[#56bbf1]/5 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>
            
            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-10">
                <div class="flex-1">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/10 text-[#56bbf1] text-[10px] font-black uppercase tracking-[0.2em] mb-6 backdrop-blur-md shadow-sm">
                        <i class="ph-fill ph-shield-check text-[#56bbf1] text-sm"></i> Panel Manajemen Keamanan
                    </div>
                    <h1 class="text-4xl md:text-6xl font-black text-white tracking-tighter mb-4 leading-none">
                        Tindak Lanjut <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] to-[#3b82f6]">Laporan</span>
                    </h1>
                    <p class="text-slate-300 text-sm md:text-lg max-w-xl leading-relaxed font-medium">
                        Dengarkan suara siswa dan berikan solusi terbaik untuk lingkungan sekolah yang aman.
                    </p>
                </div>

                <div class="flex gap-4 shrink-0">
                    <div class="bg-white/5 backdrop-blur-md p-6 rounded-[2.5rem] border border-white/10 text-center min-w-[140px] shadow-sm">
                        <p class="text-[10px] font-black text-amber-400 uppercase tracking-widest mb-1">Menunggu</p>
                        <p class="text-4xl font-black text-white tracking-tight">{{ $complaints->where('status', 'pending')->count() }}</p>
                    </div>
                    <div class="bg-white/5 backdrop-blur-md p-6 rounded-[2.5rem] border border-white/10 text-center min-w-[140px] shadow-sm">
                        <p class="text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-1">Selesai</p>
                        <p class="text-4xl font-black text-white tracking-tight">{{ $complaints->where('status', 'resolved')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- FILTER PANEL --}}
        <div class="animate-enter" style="animation-delay: 100ms">
            <form action="{{ route('complaints.index') }}" method="GET" class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] backdrop-blur-xl p-6 rounded-[2.5rem] border border-white/10 shadow-2xl grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-2">
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Cari Kata Kunci</label>
                    <div class="relative">
                        <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama siswa atau isi laporan..." class="w-full pl-11 pr-4 py-3 bg-[#021124]/90 border border-white/15 rounded-2xl text-sm font-bold focus:ring-[#56bbf1] focus:border-[#56bbf1] transition-all text-white placeholder:text-slate-500">
                    </div>
                </div>
                <div>
                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-2 block">Tanggal Kejadian</label>
                    <div class="relative">
                        <i class="ph-bold ph-calendar absolute left-4 top-1/2 -translate-y-1/2 text-[#56bbf1]"></i>
                        <input type="date" name="date" value="{{ $date ?? request('date') }}" class="w-full pl-11 pr-4 py-3 bg-[#021124]/90 border border-white/15 rounded-2xl text-sm font-bold focus:ring-[#56bbf1] focus:border-[#56bbf1] transition-all text-white" onchange="this.form.submit()">
                    </div>
                </div>
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-[#56bbf1] to-[#3b82f6] text-white py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:brightness-110 shadow-lg shadow-[#56bbf1]/20 transition-all active:scale-95">Filter</button>
                    <a href="{{ route('complaints.index') }}" class="px-4 py-3 bg-white/10 text-slate-400 rounded-2xl hover:bg-rose-500/20 hover:text-rose-400 transition-all"><i class="ph-bold ph-arrow-counter-clockwise"></i></a>
                </div>
            </form>
        </div>

        {{-- DAFTAR LAPORAN --}}
        <div class="animate-enter bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] backdrop-blur-xl rounded-[3rem] shadow-2xl border border-white/10 overflow-hidden mb-12" style="animation-delay: 200ms">
            <div class="divide-y divide-white/5">
                @forelse($complaints as $complaint)
                <div class="p-8 md:p-10 hover:bg-white/5 transition-all group relative overflow-hidden">
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $complaint->status == 'resolved' ? 'bg-emerald-400' : 'bg-amber-400' }}"></div>

                    <div class="flex flex-col lg:flex-row gap-10">
                        <div class="flex-1 space-y-6">
                            <div class="flex flex-wrap items-center gap-3">
                                <span class="px-4 py-1 rounded-xl text-[10px] font-black border uppercase bg-[#56bbf1]/10 text-[#56bbf1] border-[#56bbf1]/20">
                                    {{ $complaint->category }}
                                </span>
                                <span class="text-[10px] text-slate-400 font-black uppercase tracking-widest">
                                    {{ $complaint->created_at->translatedFormat('d M Y, H:i') }}
                                </span>
                            </div>

                            <div class="flex items-start gap-5">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0 border border-white/10 shadow-sm bg-[#021124]
                                    {{ $complaint->is_anonymous ? 'text-slate-400' : 'text-[#56bbf1]' }}">
                                    <i class="ph-fill {{ $complaint->is_anonymous ? 'ph-mask-spy' : 'ph-user-focus' }} text-3xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-black text-white tracking-tight leading-tight">
                                        {{ $complaint->is_anonymous ? 'Siswa Anonim' : ($complaint->student->name ?? 'Siswa Tidak Ditemukan') }}
                                    </h4>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1">
                                        NISN: {{ $complaint->student->student_id ?? '-' }} &bull; Kelas: {{ $complaint->student->schoolClass->name ?? '-' }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="bg-white/5 p-6 rounded-[2rem] border border-white/10 shadow-sm relative">
                                <div class="flex items-center gap-2 mb-3 text-rose-400">
                                    <i class="ph-fill ph-map-pin text-lg"></i>
                                    <span class="text-xs font-black uppercase tracking-widest">{{ $complaint->location }}</span>
                                </div>
                                <p class="text-slate-300 text-sm leading-relaxed font-medium italic">"{{ $complaint->description }}"</p>
                                
                                @if($complaint->evidence_path)
                                    <div class="mt-4 pt-4 border-t border-white/10">
                                        <a href="{{ asset('storage/' . $complaint->evidence_path) }}" target="_blank" class="inline-flex items-center gap-2 text-xs font-bold text-[#56bbf1] hover:text-white transition-colors">
                                            <i class="ph-bold ph-image text-lg"></i> Lihat Bukti Lampiran
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="w-full lg:w-72 shrink-0 flex flex-col justify-center gap-4 bg-white/5 p-6 rounded-[2.5rem] border border-white/10">
                            @if($complaint->status == 'resolved')
                                <div class="text-center space-y-3">
                                    <div class="w-16 h-16 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-[1.5rem] flex items-center justify-center mx-auto shadow-sm">
                                        <i class="ph-fill ph-check-circle text-3xl"></i>
                                    </div>
                                    <h5 class="text-sm font-black text-emerald-400 uppercase tracking-tight">Selesai</h5>
                                </div>
                            @else
                                <button onclick="confirmResolve('{{ $complaint->id }}')" 
                                        class="w-full py-4 bg-gradient-to-r from-emerald-500 to-teal-600 hover:brightness-110 text-white rounded-2xl text-[10px] font-black uppercase tracking-[0.15em] shadow-lg shadow-emerald-500/20 transition-all transform active:scale-95 flex items-center justify-center gap-3 group/btn">
                                    Tandai Selesai
                                    <i class="ph-bold ph-check-square-offset text-xl group-hover/btn:scale-110 transition-transform"></i>
                                </button>

                                <form id="resolve-form-{{ $complaint->id }}" action="{{ route('complaints.resolve', $complaint->id) }}" method="POST" class="hidden">
                                    @csrf
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="py-32 text-center flex flex-col items-center justify-center">
                    <div class="w-24 h-24 bg-white/5 rounded-[2rem] flex items-center justify-center mb-8 border border-white/10 shadow-sm group transition-all">
                        <i class="ph-duotone ph-tray text-6xl text-slate-500"></i>
                    </div>
                    <h3 class="text-2xl font-black text-white tracking-tight">Kotak Laporan Kosong</h3>
                    <p class="text-slate-400 font-medium mt-2">Tidak ada laporan masuk yang perlu ditindaklanjuti.</p>
                </div>
                @endforelse
            </div>
            
            @if($complaints->hasPages())
            <div class="p-8 border-t border-white/10 bg-white/5">
                {{ $complaints->links() }}
            </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmResolve(id) {
            Swal.fire({
                title: 'Tandai Selesai?',
                text: "Masalah ini telah terselesaikan dan laporan akan ditutup statusnya.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Selesaikan!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                background: '#021124',
                color: '#ffffff',
                customClass: {
                    popup: 'rounded-[2.5rem] font-sans border border-white/10 shadow-2xl',
                    confirmButton: 'bg-emerald-500 text-white px-6 py-3 rounded-xl font-bold hover:bg-emerald-600 transition-colors mx-2 shadow-lg shadow-emerald-500/20',
                    cancelButton: 'bg-white/10 text-slate-300 px-6 py-3 rounded-xl font-bold hover:bg-white/20 transition-colors mx-2'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('resolve-form-' + id).submit();
                }
            })
        }

        // Cek Session Success
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    background: '#021124',
                    color: '#ffffff',
                    confirmButtonColor: '#56bbf1',
                    customClass: { popup: 'rounded-[2rem] shadow-xl border border-white/10' }
                });
            @endif
        });
    </script>
    @endpush
</x-app-layout>