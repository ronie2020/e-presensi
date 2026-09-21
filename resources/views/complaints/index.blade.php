@extends('layouts.public')

@section('content')
    {{-- SET LOCALE INDONESIA --}}
    @php \Carbon\Carbon::setLocale('id'); @endphp

    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>

    <div class="min-h-screen bg-[#020b18] text-slate-100 font-sans pb-20 pt-24 relative z-10">
        <div class="max-w-5xl mx-auto px-4 sm:px-6">
            
            <div class="space-y-8">
                
                {{-- HERO SECTION ELEVATE DARK GLASS --}}
                <div class="animate-enter relative rounded-[3rem] bg-gradient-to-r from-[#031d3d] via-[#021124] to-[#031d3d] p-8 md:p-12 mb-8 text-white shadow-2xl overflow-hidden border border-white/10">
                    <div class="absolute top-0 right-0 -mt-20 -mr-20 w-[400px] h-[400px] bg-[#56bbf1]/10 rounded-full blur-[80px] opacity-60"></div>
                    <div class="absolute -bottom-20 -left-10 w-64 h-64 bg-[#56bbf1]/5 rounded-full blur-3xl"></div>
                    
                    <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                        <div class="max-w-2xl">                        
                            <a href="{{ route('portal.show', Auth::guard('student')->id()) }}" class="inline-flex items-center gap-2 text-[#56bbf1] hover:text-white transition-colors mb-6 text-[10px] font-black uppercase tracking-[0.2em]">
                                <i class="ph-bold ph-arrow-left"></i> Kembali ke profil
                            </a>
                            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/10 text-[#56bbf1] text-[10px] font-black uppercase tracking-widest mb-4 backdrop-blur-md shadow-sm">
                                <i class="ph-fill ph-shield-check text-[#56bbf1] text-sm"></i> Zona Aman Bercerita
                            </div>
                            <h1 class="text-3xl md:text-5xl font-black tracking-tighter mb-4 leading-tight text-white">
                                Layanan <br class="md:hidden"><span class="text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] to-[#3b82f6]">Pengaduan Siswa</span>
                            </h1>
                            <p class="text-slate-300 text-sm md:text-base leading-relaxed font-medium">
                                Suaramu sangat berharga. Kami siap mendengarkan dan membantu menyelesaikan masalahmu di sekolah dengan aman dan rahasia.
                            </p>
                        </div>
                        
                        <a href="{{ route('student.complaints.create') }}" class="group bg-gradient-to-r from-[#56bbf1] to-[#3b82f6] text-white px-8 py-4 rounded-[1.5rem] font-black shadow-xl shadow-[#56bbf1]/20 hover:brightness-110 transition-all flex items-center gap-3 shrink-0 active:scale-95 text-xs uppercase tracking-widest">
                            <i class="ph-bold ph-megaphone text-xl group-hover:scale-110 transition-transform"></i>
                            Buat Laporan
                        </a>
                    </div>
                </div>

                {{-- BENTO STATS --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 animate-enter" style="animation-delay: 100ms">
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-6 rounded-[2rem] border border-white/10 shadow-xl flex items-center gap-5 group hover:border-[#56bbf1]/50 transition-all">
                        <div class="w-14 h-14 rounded-2xl bg-[#56bbf1]/10 text-[#56bbf1] flex items-center justify-center text-2xl shadow-inner group-hover:scale-110 transition-transform border border-[#56bbf1]/20">
                            <i class="ph-fill ph-paper-plane-tilt"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Laporan</p>
                            <p class="text-3xl font-black text-white tracking-tight">{{ $complaints->count() }}</p>
                        </div>
                    </div>
                    
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-6 rounded-[2rem] border border-white/10 shadow-xl flex items-center gap-5 group hover:border-amber-500/50 transition-all">
                        <div class="w-14 h-14 rounded-2xl bg-amber-500/10 text-amber-400 flex items-center justify-center text-2xl shadow-inner group-hover:scale-110 transition-transform border border-amber-500/20">
                            <i class="ph-fill ph-hourglass-high"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Diproses</p>
                            <p class="text-3xl font-black text-white tracking-tight">{{ $complaints->where('status', 'pending')->count() }}</p>
                        </div>
                    </div>

                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-6 rounded-[2rem] border border-white/10 shadow-xl flex items-center gap-5 group hover:border-emerald-500/50 transition-all">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform shadow-inner border border-emerald-500/20">
                            <i class="ph-fill ph-check-circle"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Selesai</p>
                            <p class="text-3xl font-black text-white tracking-tight">{{ $complaints->where('status', 'resolved')->count() }}</p>
                        </div>
                    </div>
                </div>

                {{-- LIST LAPORAN --}}
                <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] backdrop-blur-xl rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden animate-enter" style="animation-delay: 200ms">
                    <div class="p-8 border-b border-white/10 flex items-center justify-between bg-white/5">
                        <h3 class="font-black text-white text-lg flex items-center gap-3 tracking-tight">
                            <i class="ph-bold ph-list-dashes text-[#56bbf1]"></i> Riwayat Laporan Kamu
                        </h3>
                    </div>
                    
                    @if($complaints->count() > 0)
                        <div class="divide-y divide-white/5">
                            @foreach($complaints as $item)
                            <div class="p-8 hover:bg-white/5 transition-all group relative">
                                <div class="flex flex-col md:flex-row justify-between gap-6">
                                    <div class="flex gap-6">
                                        {{-- Icon Category --}}
                                        @php
                                            $catColor = match($item->category) {
                                                'Bullying' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                                                'Fasilitas' => 'bg-slate-500/10 text-slate-300 border-slate-500/20',
                                                'Kehilangan' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                                default => 'bg-[#56bbf1]/10 text-[#56bbf1] border-[#56bbf1]/20'
                                            };
                                            $catIcon = match($item->category) {
                                                'Bullying' => 'ph-mask-sad',
                                                'Fasilitas' => 'ph-wrench',
                                                'Kehilangan' => 'ph-magnifying-glass',
                                                default => 'ph-megaphone'
                                            };
                                        @endphp
                                        <div class="shrink-0 w-16 h-16 rounded-[1.5rem] flex items-center justify-center text-3xl border {{ $catColor }} shadow-inner transition-transform group-hover:scale-110">
                                            <i class="ph-fill {{ $catIcon }}"></i>
                                        </div>
                                        
                                        <div class="flex-1">
                                            <div class="flex flex-wrap items-center gap-3 mb-2">
                                                <span class="text-sm font-black text-white uppercase tracking-tight">{{ $item->category }}</span>
                                                <span class="text-[9px] font-black px-3 py-1 rounded-full bg-white/10 text-slate-300 border border-white/10 uppercase tracking-widest">
                                                    {{ $item->created_at->translatedFormat('d F Y') }}
                                                </span>
                                                @if($item->is_anonymous)
                                                    <span class="text-[9px] font-black px-3 py-1 rounded-full bg-gradient-to-r from-[#56bbf1] to-[#3b82f6] text-white flex items-center gap-1.5 uppercase tracking-widest shadow-sm">
                                                        <i class="ph-fill ph-spy"></i> Anonim
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-slate-300 line-clamp-2 mb-4 font-medium leading-relaxed italic">"{{ $item->description }}"</p>
                                            
                                            {{-- Status Badge --}}
                                            @php
                                                $statusStyle = match($item->status) {
                                                    'pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                                    'investigating' => 'bg-[#56bbf1]/10 text-[#56bbf1] border-[#56bbf1]/20',
                                                    'resolved' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                                    default => 'bg-slate-500/10 text-slate-400 border-slate-500/20'
                                                };
                                                $statusText = match($item->status) {
                                                    'pending' => 'Menunggu Respon',
                                                    'investigating' => 'Sedang Diproses',
                                                    'resolved' => 'Masalah Selesai',
                                                    default => 'Dibatalkan'
                                                };
                                            @endphp
                                            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-xl text-[9px] font-black border uppercase tracking-widest {{ $statusStyle }}">
                                                <span class="w-1.5 h-1.5 rounded-full bg-current animate-pulse"></span> {{ $statusText }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Action / Detail --}}
                                    <div class="flex items-center justify-end">
                                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] group-hover:text-[#56bbf1] transition-colors">
                                            ID #{{ str_pad($item->id, 5, '0', STR_PAD_LEFT) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="p-24 text-center flex flex-col items-center">
                            <div class="w-24 h-24 bg-white/5 rounded-[2rem] flex items-center justify-center mb-6 border border-white/10 shadow-inner">
                                <i class="ph-duotone ph-shield-check text-5xl text-slate-500"></i>
                            </div>
                            <h3 class="text-white font-black text-xl tracking-tight">Sekolah Aman & Kondusif</h3>
                            <p class="text-slate-400 text-sm mt-2 max-w-xs mx-auto font-medium">Belum ada laporan yang kamu buat. Terima kasih telah menjaga kedamaian di sekolah!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection