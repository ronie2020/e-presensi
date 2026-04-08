@extends('layouts.public')

@section('content')
<div class="py-6 md:py-10 font-sans text-slate-800">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        
        <!-- Breadcrumb / Back -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <a href="{{ route('student.bk.index') }}" class="group inline-flex items-center text-sm text-slate-500 hover:text-blue-600 font-bold transition-colors">
                <i class="ph-bold ph-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i> Kembali
            </a>
            <div class="px-3 py-1 rounded-full bg-slate-100 border border-slate-200 text-xs font-mono font-bold text-slate-500">
                TIKET ID: #{{ $session->id }}
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
            
            <!-- KOLOM KIRI: STATUS & INFO -->
            <div class="space-y-6">
                <!-- Card Status -->
                <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-xl shadow-slate-200/50 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-slate-50 to-blue-50 rounded-bl-[5rem] -mr-6 -mt-6 z-0"></div>
                    <div class="relative z-10">
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Status Tiket</p>
                        @php
                            $statusColor = match($session->status) {
                                'pending' => 'text-amber-700 bg-amber-50 border-amber-200',
                                'approved' => 'text-blue-700 bg-blue-50 border-blue-200',
                                'finished' => 'text-emerald-700 bg-emerald-50 border-emerald-200',
                                'rejected' => 'text-rose-700 bg-rose-50 border-rose-200',
                                default => 'text-slate-600 bg-slate-50 border-slate-200'
                            };
                            $statusText = match($session->status) {
                                'pending' => 'Menunggu Respon',
                                'approved' => 'Disetujui',
                                'finished' => 'Selesai',
                                'rejected' => 'Ditolak',
                                default => '-'
                            };
                            $statusIcon = match($session->status) {
                                'pending' => 'ph-hourglass',
                                'approved' => 'ph-check-circle',
                                'finished' => 'ph-medal',
                                'rejected' => 'ph-x-circle',
                                default => 'ph-question'
                            };
                        @endphp
                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-black border {{ $statusColor }}">
                            <i class="ph-fill {{ $statusIcon }}"></i> {{ $statusText }}
                        </span>

                        <div class="mt-8 space-y-5">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 border border-slate-100">
                                    <i class="ph-fill ph-calendar-blank text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Diajukan Pada</p>
                                    <p class="text-sm font-bold text-slate-700">{{ $session->created_at->translatedFormat('d F Y, H:i') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 border border-slate-100">
                                    @if($session->method == 'online')
                                        <i class="ph-fill ph-globe text-lg"></i>
                                    @else
                                        <i class="ph-fill ph-users text-lg"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Metode</p>
                                    <p class="text-sm font-bold text-slate-700">{{ $session->method == 'online' ? 'Online (Daring)' : 'Tatap Muka' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Info -->
                @if($session->status == 'approved')
                <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-[2rem] p-6 shadow-xl shadow-blue-600/20 text-white relative overflow-hidden">
                    <i class="ph-duotone ph-info text-9xl absolute -bottom-6 -right-6 text-white opacity-10"></i>
                    <h4 class="font-black text-lg mb-2 relative z-10 flex items-center gap-2">
                        <i class="ph-fill ph-bell-ringing"></i> Pengingat
                    </h4>
                    <p class="text-blue-100 text-sm font-medium leading-relaxed relative z-10">
                        Sesi kamu telah disetujui. Mohon hadir tepat waktu. Jika berhalangan, harap hubungi Guru BK segera.
                    </p>
                </div>
                @endif
            </div>

            <!-- KOLOM KANAN: DETAIL & RESPON -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Detail Pengajuan -->
                <div class="bg-white rounded-[2rem] p-8 border border-slate-100 shadow-xl shadow-slate-200/50">
                    <h3 class="font-black text-xl text-slate-800 mb-6 flex items-center gap-2">
                        <i class="ph-duotone ph-file-text text-blue-500"></i> Detail Masalah
                    </h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">
                                {{ ($session->is_system_generated ?? false) ? 'Pemberitahuan dari Sekolah' : 'Keluhan / Pesan Kamu' }}
                            </label>
                            
                            @if($session->is_system_generated ?? false)
                                @php
                                    $isPrestasi = str_contains($session->initial_message, 'PRESTASI');
                                    $sysBg = $isPrestasi ? 'bg-blue-50' : 'bg-rose-50';
                                    $sysBorder = $isPrestasi ? 'border-blue-100' : 'border-rose-100';
                                    $sysText = $isPrestasi ? 'text-blue-800' : 'text-rose-800';
                                    $sysIconColor = $isPrestasi ? 'text-blue-200' : 'text-rose-200';
                                    $sysIcon = $isPrestasi ? 'ph-medal' : 'ph-warning-circle';
                                @endphp
                                <div class="mt-3 p-6 {{ $sysBg }} rounded-2xl border {{ $sysBorder }} {{ $sysText }} font-medium leading-relaxed relative">
                                    <i class="ph-fill {{ $sysIcon }} text-4xl {{ $sysIconColor }} absolute -top-3 -left-2"></i>
                                    <span class="relative z-10 whitespace-pre-line">{{ $session->initial_message }}</span>
                                </div>
                            @else
                                <div class="mt-3 p-6 bg-slate-50 rounded-2xl border border-slate-100 text-slate-700 leading-relaxed italic relative">
                                    <i class="ph-fill ph-quotes text-4xl text-slate-200 absolute -top-3 -left-2"></i>
                                    <span class="relative z-10">"{{ $session->initial_message }}"</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- RESPON GURU (Jika Ada) -->
                @if($session->status != 'pending')
                    <div class="bg-white rounded-[2rem] p-8 border-l-4 border-blue-500 shadow-xl shadow-slate-200/50 relative">
                        <h3 class="font-black text-xl text-blue-800 mb-6 flex items-center gap-2">
                            <i class="ph-duotone ph-chat-centered-text"></i> Respon Guru BK
                        </h3>

                        <!-- Jadwal -->
                        @if($session->scheduled_at)
                        <div class="flex flex-col sm:flex-row items-start gap-5 mb-6 p-5 bg-blue-50/50 rounded-2xl border border-blue-100">
                            <div class="p-3 bg-blue-100 text-blue-600 rounded-xl shrink-0 shadow-sm">
                                <i class="ph-fill ph-clock-countdown text-3xl"></i>
                            </div>
                            <div>
                                <p class="text-xs font-black text-blue-400 uppercase tracking-widest mb-1">Jadwal Konseling</p>
                                <p class="text-xl font-black text-slate-800">{{ $session->scheduled_at->translatedFormat('l, d F Y') }}</p>
                                <p class="text-base font-bold text-slate-600">Pukul {{ $session->scheduled_at->format('H:i') }} WIB</p>
                                <div class="flex items-center gap-2 mt-2 pt-2 border-t border-blue-100/50">
                                    <i class="ph-bold ph-user-circle text-blue-400"></i>
                                    <p class="text-xs text-slate-500 font-bold">Konselor: {{ $session->teacher->name ?? 'Guru BK' }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        <!-- Pesan Balasan -->
                        @if($session->response_message)
                            <div>
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-widest">Pesan dari Guru</label>
                                <div class="mt-2 text-slate-700 leading-relaxed p-4 bg-white rounded-xl border border-slate-100 shadow-sm">
                                    {{ $session->response_message }}
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection