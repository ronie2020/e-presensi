@extends('layouts.public')

@section('content')
<div class="w-full max-w-5xl mx-auto pb-20 px-4 sm:px-6 pt-6 md:pt-10">
    
    <!-- Breadcrumb / Back -->
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('student.bk.index') }}" class="inline-flex items-center text-sm text-slate-500 hover:text-slate-800 font-bold transition-colors">
            <i class="ph-bold ph-arrow-left mr-2"></i> Kembali
        </a>
        <div class="text-xs font-mono text-slate-400">ID: #{{ $session->id }}</div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
        
        <!-- KOLOM KIRI: STATUS & INFO -->
        <div class="space-y-6">
            <!-- Card Status -->
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm relative overflow-hidden">
                <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-slate-50 to-slate-100 rounded-bl-[4rem] -mr-4 -mt-4 z-0"></div>
                <div class="relative z-10">
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Status Tiket</p>
                    @php
                        $statusColor = match($session->status) {
                            'pending' => 'text-amber-600 bg-amber-50 border-amber-100',
                            'approved' => 'text-blue-600 bg-blue-50 border-blue-100',
                            'finished' => 'text-green-600 bg-green-50 border-green-100',
                            'rejected' => 'text-red-600 bg-red-50 border-red-100',
                            default => 'text-slate-600 bg-slate-50 border-slate-100'
                        };
                        $statusText = match($session->status) {
                            'pending' => 'Menunggu Respon',
                            'approved' => 'Disetujui',
                            'finished' => 'Selesai',
                            'rejected' => 'Ditolak',
                            default => '-'
                        };
                    @endphp
                    <span class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-black border {{ $statusColor }}">
                        {{ $statusText }}
                    </span>

                    <div class="mt-6 space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400">
                                <i class="ph-fill ph-calendar-blank text-lg"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase">Diajukan Pada</p>
                                <p class="text-sm font-bold text-slate-700">{{ $session->created_at->translatedFormat('d F Y, H:i') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400">
                                @if($session->method == 'online')
                                    <i class="ph-fill ph-globe text-lg"></i>
                                @else
                                    <i class="ph-fill ph-users text-lg"></i>
                                @endif
                            </div>
                            <div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase">Metode</p>
                                <p class="text-sm font-bold text-slate-700">{{ $session->method == 'online' ? 'Online (Daring)' : 'Tatap Muka' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Info -->
            <div class="bg-blue-600 rounded-3xl p-6 shadow-lg shadow-blue-600/20 text-white relative overflow-hidden">
                <i class="ph-fill ph-info text-8xl absolute -bottom-4 -right-4 text-blue-500 opacity-50"></i>
                <h4 class="font-bold text-lg mb-2 relative z-10">Info Penting</h4>
                <p class="text-blue-100 text-sm leading-relaxed relative z-10">
                    Jika status sudah <strong>Disetujui</strong>, mohon hadir tepat waktu sesuai jadwal. Jika berhalangan, harap hubungi Guru BK segera.
                </p>
            </div>
        </div>

        <!-- KOLOM KANAN: DETAIL & RESPON -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Detail Pengajuan -->
            <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm">
                <h3 class="font-bold text-xl text-slate-800 mb-6 flex items-center gap-2">
                    <i class="ph-duotone ph-file-text text-pink-500"></i> Detail Masalah
                </h3>
                
                <div class="space-y-6">
                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Kategori</label>
                        <div class="mt-1">
                            <span class="inline-block px-3 py-1 bg-slate-100 text-slate-700 rounded-lg text-sm font-bold">
                                {{ $session->category->name }}
                            </span>
                        </div>
                    </div>
                    
                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Keluhan / Pesan Kamu</label>
                        <div class="mt-2 p-5 bg-slate-50 rounded-2xl border border-slate-100 text-slate-700 leading-relaxed italic relative">
                            <i class="ph-fill ph-quotes text-3xl text-slate-200 absolute top-2 right-4"></i>
                            "{{ $session->initial_message }}"
                        </div>
                    </div>
                </div>
            </div>

            <!-- RESPON GURU (Jika Ada) -->
            @if($session->status != 'pending')
                <div class="bg-white rounded-3xl p-8 border-l-4 border-blue-500 shadow-sm relative">
                    <h3 class="font-bold text-xl text-blue-800 mb-6 flex items-center gap-2">
                        <i class="ph-duotone ph-chat-centered-text"></i> Respon Guru BK
                    </h3>

                    <!-- Jadwal -->
                    @if($session->scheduled_at)
                    <div class="flex items-start gap-4 mb-6 p-4 bg-blue-50/50 rounded-2xl border border-blue-100">
                        <div class="p-3 bg-blue-100 text-blue-600 rounded-xl shrink-0">
                            <i class="ph-fill ph-clock-countdown text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-blue-400 uppercase tracking-wide mb-1">Jadwal Konseling</p>
                            <p class="text-lg font-black text-slate-800">{{ $session->scheduled_at->translatedFormat('l, d F Y') }}</p>
                            <p class="text-sm font-bold text-slate-600">Pukul {{ $session->scheduled_at->format('H:i') }} WIB</p>
                            <p class="text-xs text-slate-500 mt-1">Konselor: <span class="font-bold">{{ $session->teacher->name ?? '-' }}</span></p>
                        </div>
                    </div>
                    @endif

                    <!-- Pesan Balasan -->
                    @if($session->response_message)
                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-wide">Pesan dari Guru</label>
                            <p class="mt-2 text-slate-700 leading-relaxed">
                                {{ $session->response_message }}
                            </p>
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </div>
</div>
@endsection