@extends('layouts.public')

@section('content')
    {{-- SET LOCALE INDONESIA --}}
    @php \Carbon\Carbon::setLocale('id'); @endphp

    {{-- Container Utama --}}
    <div class="max-w-5xl mx-auto px-4 sm:px-6 pb-20">
        
        <div class="space-y-8">
            
            {{-- HEADER SECTION --}}
            <div class="animate-enter relative rounded-[2.5rem] bg-gradient-to-r from-rose-900 via-slate-800 to-slate-900 p-6 md:p-10 mb-8 text-white shadow-2xl shadow-rose-900/20 overflow-hidden border border-white/10">
                {{-- Background Decoration --}}
                <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-rose-500 rounded-full mix-blend-overlay filter blur-[120px] opacity-20"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div class="max-w-3xl">
                        {{-- Tombol Kembali --}}
                        <a href="{{ route('portal.index') }}" class="inline-flex items-center gap-2 text-rose-200 hover:text-white transition-colors mb-4 text-xs font-bold uppercase tracking-widest">
                            <i class="ph-bold ph-arrow-left"></i> Kembali ke Dashboard
                        </a>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-500/20 border border-rose-400/30 text-rose-100 text-xs font-bold uppercase tracking-wider mb-3">
                            <i class="ph-fill ph-shield-warning"></i> Zona Aman Bercerita
                        </div>
                        <h1 class="text-2xl md:text-4xl font-extrabold tracking-tight mb-4">Layanan Pengaduan</h1>
                        <p class="text-rose-100/90 text-sm md:text-base leading-relaxed">
                            Layanan ini <strong>dikhususkan bagi siswa yang memiliki masalah di sekolah</strong>, baik itu perundungan (bullying) maupun hal lainnya. 
                            Sekolah akan <strong>segera menindaklanjuti</strong> laporanmu. Identitasmu aman jika memilih opsi anonim.
                        </p>
                    </div>
                    
                    <a href="{{ route('student.complaints.create') }}" class="group bg-white text-rose-600 px-6 py-3 rounded-xl font-bold shadow-lg shadow-rose-900/30 hover:bg-rose-50 transition-all flex items-center gap-2 shrink-0">
                        <i class="ph-bold ph-megaphone text-xl group-hover:rotate-12 transition-transform"></i>
                        Buat Laporan
                    </a>
                </div>
            </div>

            {{-- STATISTIK SINGKAT --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 animate-enter" style="animation-delay: 100ms">
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i class="ph-bold ph-paper-plane-right text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Laporan</p>
                        <p class="text-2xl font-black text-slate-800">{{ $complaints->count() }}</p>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                        <i class="ph-bold ph-hourglass text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Sedang Diproses</p>
                        <p class="text-2xl font-black text-slate-800">{{ $complaints->where('status', 'pending')->count() }}</p>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="ph-bold ph-check-circle text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Selesai</p>
                        <p class="text-2xl font-black text-slate-800">{{ $complaints->where('status', 'resolved')->count() }}</p>
                    </div>
                </div>
            </div>

            {{-- List Laporan --}}
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden animate-enter" style="animation-delay: 200ms">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                        <i class="ph-duotone ph-list-dashes text-slate-400"></i> Riwayat Laporan Kamu
                    </h3>
                </div>
                
                @if($complaints->count() > 0)
                    <div class="divide-y divide-slate-100">
                        @foreach($complaints as $item)
                        <div class="p-6 hover:bg-slate-50 transition-colors group">
                            <div class="flex flex-col md:flex-row justify-between gap-4">
                                <div class="flex gap-4">
                                    {{-- Icon Category --}}
                                    <div class="shrink-0 w-12 h-12 rounded-2xl flex items-center justify-center text-xl
                                        {{ $item->category == 'Bullying' ? 'bg-rose-50 text-rose-600' : 
                                        ($item->category == 'Fasilitas' ? 'bg-slate-100 text-slate-600' : 'bg-blue-50 text-blue-600') }}">
                                        <i class="ph-bold {{ $item->category == 'Bullying' ? 'ph-warning' : 'ph-wrench' }}"></i>
                                    </div>
                                    
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="text-sm font-bold text-slate-800">{{ $item->category }}</span>
                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-500 uppercase">
                                                {{ $item->created_at->translatedFormat('d F Y') }}
                                            </span>
                                            @if($item->is_anonymous)
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-800 text-white flex items-center gap-1"><i class="ph-bold ph-mask-happy"></i> Anonim</span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-slate-600 line-clamp-2 mb-2">{{ $item->description }}</p>
                                        
                                        {{-- Status Badge --}}
                                        @php
                                            $statusColor = match($item->status) {
                                                'pending' => 'bg-amber-50 text-amber-600 border-amber-200',
                                                'investigating' => 'bg-blue-50 text-blue-600 border-blue-200',
                                                'resolved' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                                default => 'bg-slate-50 text-slate-600 border-slate-200'
                                            };
                                            $statusLabel = match($item->status) {
                                                'pending' => 'Menunggu Respon',
                                                'investigating' => 'Sedang Diselidiki',
                                                'resolved' => 'Masalah Selesai',
                                                default => 'Ditolak'
                                            };
                                        @endphp
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold border {{ $statusColor }}">
                                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span> {{ $statusLabel }}
                                        </span>
                                    </div>
                                </div>

                                {{-- Action Button (Status Selesai) --}}
                                @if($item->status == 'resolved')
                                    <div class="flex items-center">
                                        <span class="text-xs text-emerald-600 font-bold flex items-center gap-1 bg-emerald-50 px-3 py-1.5 rounded-full">
                                            <i class="ph-bold ph-check-circle"></i> Selesai
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="p-10 text-center flex flex-col items-center">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100">
                            <i class="ph-duotone ph-shield-check text-4xl text-slate-300"></i>
                        </div>
                        <h3 class="text-slate-800 font-bold">Belum ada laporan</h3>
                        <p class="text-slate-500 text-sm mt-1">Lingkungan sekolah aman dan kondusif.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection