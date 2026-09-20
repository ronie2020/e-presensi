<x-app-layout>
    @push('styles')
    <style>        
        /* Animations */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        @keyframes wiggle { 0%, 100% { transform: rotate(0deg); } 25% { transform: rotate(-10deg); } 75% { transform: rotate(10deg); } }
        .group:hover .animate-wiggle { animation: wiggle 0.5s ease-in-out; }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
                
       /* Table Styles */
        .status-border-left { border-left: 4px solid transparent; transition: border-color 0.2s; }
        .tr-active:hover .status-border-left { border-left-color: #f9a282; } /* elevate-peach */
        .tr-returned:hover .status-border-left { border-left-color: #10b981; } /* emerald-500 */
        .tr-overdue:hover .status-border-left { border-left-color: #e11d48; } /* rose-600 */
    </style>
    @endpush

    <div class="py-8 sm:py-10 font-sans text-white bg-elevate-surface relative overflow-hidden min-h-screen">
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 md:space-y-8 relative z-10">

            {{-- HERO SECTION (UNIFIED ELEVATE DARK GLASS - AQUALIFE & E-LEARNING) --}}
            <div class="animate-enter relative rounded-[2.5rem] bg-gradient-to-br from-[#0d52a1]/85 via-[#031d3d]/90 to-[#021124]/95 p-8 md:p-10 mb-8 text-white shadow-2xl shadow-[#0d52a1]/25 border border-white/20 backdrop-blur-2xl overflow-hidden group">
                
                {{-- Specular Top Rim Light (Ref 2) --}}
                <div class="absolute inset-0 rounded-[2.5rem] pointer-events-none border-t border-l border-white/30"></div>
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>

                {{-- Ambient Radiant Glow Orbs (Ref 1 & 2) --}}
                <div class="absolute -top-16 -right-16 w-80 h-80 bg-[#56bbf1]/20 rounded-full blur-[100px] pointer-events-none"></div>
                <div class="absolute -bottom-16 -left-16 w-72 h-72 bg-[#0d52a1]/30 rounded-full blur-[90px] pointer-events-none"></div>

                <div class="relative z-10 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-8">
                    
                    {{-- KIRI: Judul, Intro, Chips & Tombol --}}
                    <div class="space-y-4 max-w-2xl">
                        <div class="flex flex-wrap items-center gap-2">
                            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 text-sky-300 text-xs font-bold uppercase tracking-wider backdrop-blur-md shadow-sm">
                                <i class="ph-bold ph-archive"></i> Arsip Digital
                            </div>
                            <nav class="flex items-center gap-1.5 bg-white/10 backdrop-blur-md p-1 rounded-full border border-white/15">
                                <a href="{{ route('permit.index') }}" class="px-3 py-1 rounded-full text-xs font-bold text-slate-300 hover:text-white hover:bg-white/15 transition-all flex items-center gap-1.5">
                                    <i class="ph-bold ph-shield-check text-sky-400"></i> Pos Piket
                                </a>
                                <span class="px-3 py-1 rounded-full text-xs font-bold bg-sky-500/30 text-sky-200 border border-sky-400/40 shadow-sm flex items-center gap-1.5">
                                    <i class="ph-bold ph-clock-counter-clockwise"></i> Riwayat
                                </span>
                                <a href="{{ route('permit.analytics') }}" class="px-3 py-1 rounded-full text-xs font-bold text-slate-300 hover:text-white hover:bg-white/15 transition-all flex items-center gap-1.5">
                                    <i class="ph-bold ph-chart-polar text-amber-400"></i> Analitik
                                </a>
                            </nav>
                        </div>
                        
                        <h1 class="text-3xl sm:text-4xl md:text-5xl font-black tracking-tight leading-snug sm:leading-snug md:leading-normal text-white">
                            <span class="block text-slate-100">Riwayat &</span>
                            <span class="block mt-1 sm:mt-1.5 text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] via-sky-200 to-[#38bdf8]">Jejak Perizinan Siswa</span>
                        </h1>
                        <p class="text-slate-300 text-sm md:text-base font-medium leading-relaxed">
                            Pantau jejak aktivitas keluar-masuk siswa secara lengkap, real-time, dan terperinci. Terintegrasi dengan fitur filter tanggal dan ekspor data.
                        </p>

                        {{-- Feature Highlight Chips (Ref 1 & 2) --}}
                        <div class="flex flex-wrap items-center gap-2.5 pt-1">
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-white/5 border border-white/10 text-slate-200 text-xs font-semibold backdrop-blur-sm shadow-sm">
                                <i class="ph-bold ph-check text-sky-400"></i> Filter Tanggal Presisi
                            </div>
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-white/5 border border-white/10 text-slate-200 text-xs font-semibold backdrop-blur-sm shadow-sm">
                                <i class="ph-bold ph-check text-sky-400"></i> Pencatatan Menit Telat
                            </div>
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-white/5 border border-white/10 text-slate-200 text-xs font-semibold backdrop-blur-sm shadow-sm">
                                <i class="ph-bold ph-check text-sky-400"></i> Ekspor Excel & PDF
                            </div>
                        </div>

                        {{-- TOMBOL EXPORT (Ref 2 Dual CTA) --}}
                        <div class="flex flex-wrap items-center gap-3 pt-2">
                            <a href="{{ route('permit.export', request()->all()) }}" target="_blank" class="group flex items-center gap-2.5 px-6 py-3.5 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 border border-emerald-400/30 rounded-2xl text-xs sm:text-sm font-bold transition-all cursor-pointer text-white shadow-lg shadow-emerald-600/25 active:scale-95">
                                <i class="ph-bold ph-microsoft-excel-logo text-lg"></i>
                                <span>Export Excel</span>
                                <i class="ph-bold ph-arrow-down group-hover:translate-y-0.5 transition-transform"></i>
                            </a>
                            <a href="{{ route('permit.print', request()->all()) }}" target="_blank" class="group flex items-center gap-2.5 px-5 py-3.5 bg-white/10 hover:bg-white/20 border border-white/15 rounded-2xl text-xs sm:text-sm font-bold transition-all cursor-pointer text-white backdrop-blur-md shadow-sm active:scale-95">
                                <i class="ph-bold ph-printer text-lg text-sky-300"></i>
                                <span>Cetak Laporan</span>
                            </a>
                        </div>
                    </div>

                    {{-- KANAN: GLOWING FOCAL SHOWCASE (Ref 1 Glowing Ring & Floating Bubbles) --}}
                    <div class="relative flex items-center justify-center shrink-0 w-full lg:w-auto mt-4 lg:mt-0">
                        {{-- Multi-Layer Luminous Outer Ring --}}
                        <div class="absolute w-56 h-56 rounded-full border border-sky-400/20 bg-sky-500/5 blur-sm pointer-events-none"></div>
                        <div class="absolute w-48 h-48 rounded-full border border-sky-300/30 shadow-[0_0_40px_rgba(86,187,241,0.2)] pointer-events-none"></div>

                        {{-- Floating Micro Bubbles (Ref 1) --}}
                        <div class="absolute -top-2 -right-1 z-20 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-emerald-300 text-[10px] font-bold shadow-lg flex items-center gap-1.5">
                            <i class="ph-bold ph-check-circle text-emerald-400"></i>
                            Terverifikasi
                        </div>
                        <div class="absolute -bottom-2 -left-2 z-20 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-sky-300 text-[10px] font-bold shadow-lg flex items-center gap-1.5">
                            <i class="ph-bold ph-database text-sky-400"></i>
                            Cloud Archive
                        </div>

                        {{-- Core Showcase Card --}}
                        <div class="relative z-10 bg-[#031d3d]/90 backdrop-blur-xl border border-white/20 p-6 sm:p-7 rounded-[2.5rem] shadow-2xl flex flex-col items-center justify-center text-center min-w-[240px] group-hover:border-sky-400/40 transition-all">
                            <div class="w-16 h-16 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center text-3xl text-sky-400 shadow-inner mb-3">
                                <i class="ph-duotone ph-clock-counter-clockwise"></i>
                            </div>
                            <div class="text-3xl font-black text-white leading-none mb-1">{{ $permits->total() }}</div>
                            <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Total Riwayat Terdata</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- STATISTIK RINGKAS --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 animate-enter delay-100">
                <div class="group bg-[#031d3d]/80 backdrop-blur-md p-6 rounded-[2rem] border border-white/10 shadow-sm hover:shadow-xl hover:border-sky-400/30 transition-all duration-300">
                    <div class="flex justify-between items-start mb-4">
                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest group-hover:text-sky-400 transition-colors mt-1">Total Izin</div>
                        <div class="w-12 h-12 rounded-2xl bg-white/10 text-sky-400 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform border border-white/15">
                            <i class="ph-duotone ph-files text-2xl animate-wiggle"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-white">{{ $permits->total() }}</div>
                    <div class="text-[10px] text-slate-400 mt-1 font-bold uppercase tracking-wider">Data Sesuai Filter</div>
                </div>

                <div class="group bg-[#031d3d]/80 backdrop-blur-md p-6 rounded-[2rem] border border-white/10 shadow-sm hover:shadow-xl hover:border-amber-400/30 transition-all duration-300">
                    <div class="flex justify-between items-start mb-4">
                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest group-hover:text-amber-400 transition-colors mt-1">Sedang Keluar</div>
                        <div class="w-12 h-12 rounded-2xl bg-amber-500/10 text-amber-400 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform border border-amber-500/20">
                            <i class="ph-duotone ph-timer text-2xl animate-wiggle"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-white">{{ $permits->whereNull('time_in')->count() }}</div>
                    <div class="text-[10px] text-slate-400 mt-1 font-bold uppercase tracking-wider">Belum Kembali</div>
                </div>

                <div class="group bg-[#031d3d]/80 backdrop-blur-md p-6 rounded-[2rem] border border-white/10 shadow-sm hover:shadow-xl hover:border-emerald-400/30 transition-all duration-300">
                    <div class="flex justify-between items-start mb-4">
                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest group-hover:text-emerald-400 transition-colors mt-1">Sudah Kembali</div>
                        <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform border border-emerald-500/20">
                            <i class="ph-duotone ph-check-circle text-2xl animate-wiggle"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-white">{{ $permits->whereNotNull('time_in')->count() }}</div>
                    <div class="text-[10px] text-slate-400 mt-1 font-bold uppercase tracking-wider">Proses Selesai</div>
                </div>

                <div class="group bg-[#031d3d]/80 backdrop-blur-md p-6 rounded-[2rem] border border-white/10 shadow-sm hover:shadow-xl hover:border-sky-400/30 transition-all duration-300">
                    <div class="flex justify-between items-start mb-4">
                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest group-hover:text-sky-400 transition-colors mt-1">Tanggal Data</div>
                        <div class="w-12 h-12 rounded-2xl bg-white/10 text-sky-400 flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform border border-white/15">
                            <i class="ph-duotone ph-calendar-blank text-2xl animate-wiggle"></i>
                        </div>
                    </div>
                    <div class="text-lg font-black text-white mt-1 truncate">
                        {{ request('date') ? \Carbon\Carbon::parse(request('date'))->format('d M Y') : 'Semua Waktu' }}
                    </div>
                    <div class="text-[10px] text-slate-400 mt-1 font-bold uppercase tracking-wider">Filter Terpilih</div>
                </div>
            </div>

            {{-- MAIN CONTENT: FILTER & TABLE --}}
            <div class="bg-[#031d3d]/90 backdrop-blur-md rounded-[2.5rem] shadow-xl border border-white/10 overflow-hidden animate-enter delay-200 flex flex-col min-h-[500px]">
                
                {{-- FILTER SECTION --}}
                <div class="p-6 md:p-8 border-b border-white/10 bg-white/5">
                    <form action="{{ route('permit.history') }}" method="GET" id="filterForm" class="grid grid-cols-1 md:grid-cols-12 gap-5 items-end">
                        
                        {{-- Search --}}
                        <div class="md:col-span-4 relative group">
                            <label class="block text-[10px] font-bold text-sky-300 uppercase tracking-wide mb-2 ml-1">Cari Siswa</label>
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}" 
                                    class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-white/15 bg-[#021124]/80 focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 text-sm font-bold placeholder:text-slate-500 shadow-sm transition-all text-white outline-none" 
                                    placeholder="Nama atau NIS...">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-sky-400 transition-colors">
                                    <i class="ph-bold ph-magnifying-glass text-lg"></i>
                                </div>
                            </div>
                        </div>

                        {{-- Tanggal dengan Preset --}}
                        <div class="md:col-span-5">
                            <label class="block text-[10px] font-bold text-sky-300 uppercase tracking-wide mb-2 ml-1">Tanggal</label>
                            <div class="flex flex-col sm:flex-row gap-2">
                                <div class="relative flex-1 group">
                                    <input type="date" name="date" id="dateInput" value="{{ request('date', date('Y-m-d')) }}" 
                                        class="w-full px-4 py-3.5 rounded-2xl border border-white/15 bg-[#021124]/80 focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 text-sm font-bold text-white shadow-sm transition-all cursor-pointer outline-none">
                                </div>
                                <div class="flex gap-2 shrink-0">
                                    <button type="button" onclick="setDate('{{ date('Y-m-d') }}')" class="px-5 py-3.5 bg-white/10 hover:bg-white/20 text-white border border-white/15 rounded-2xl text-xs font-bold transition-all shadow-sm">Hari Ini</button>
                                    <button type="button" onclick="setDate('{{ date('Y-m-d', strtotime('-1 days')) }}')" class="px-5 py-3.5 bg-white/5 hover:bg-white/20 text-slate-300 hover:text-white border border-white/10 rounded-2xl text-xs font-bold transition-all shadow-sm">Kemarin</button>
                                </div>
                            </div>
                        </div>

                        {{-- Status & Submit --}}
                        <div class="md:col-span-3">
                            <label class="block text-[10px] font-bold text-sky-300 uppercase tracking-wide mb-2 ml-1">Status</label>
                            <div class="flex gap-2">
                                <div class="relative flex-1 group">
                                    <select name="status" class="w-full appearance-none pl-4 pr-10 py-3.5 rounded-2xl border border-white/15 bg-[#021124]/80 focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 text-sm font-bold text-white shadow-sm cursor-pointer transition-all outline-none">
                                        <option value="" class="bg-[#031d3d] text-white">Semua Status</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }} class="bg-[#031d3d] text-white">Di Luar</option>
                                        <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }} class="bg-[#031d3d] text-white">Kembali</option>
                                    </select>
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                        <i class="ph-bold ph-caret-down"></i>
                                    </div>
                                </div>
                                <button type="submit" class="bg-sky-600 hover:bg-sky-500 text-white px-5 rounded-2xl transition-all shadow-lg active:scale-95 flex items-center justify-center border border-sky-400/30">
                                    <i class="ph-bold ph-funnel text-xl"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- DATA TABLE --}}
                <div class="overflow-x-auto custom-scrollbar flex-1">
                    <table class="w-full whitespace-nowrap text-left text-sm">
                        <thead class="bg-[#021124]/90 backdrop-blur-sm border-b border-white/10">
                            <tr>
                                <th class="px-6 py-5 text-[10px] font-black text-sky-300 uppercase tracking-widest">Waktu</th>
                                <th class="px-6 py-5 text-[10px] font-black text-sky-300 uppercase tracking-widest">Siswa</th>
                                <th class="px-6 py-5 text-[10px] font-black text-sky-300 uppercase tracking-widest">Keperluan</th>
                                <th class="px-6 py-5 text-[10px] font-black text-sky-300 uppercase tracking-widest">Durasi</th>
                                <th class="px-6 py-5 text-[10px] font-black text-sky-300 uppercase tracking-widest">Status</th>
                                <th class="px-6 py-5 text-[10px] font-black text-sky-300 uppercase tracking-widest text-right">Kembali</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($permits as $permit)
                                @php
                                    $isReturned = $permit->time_in != null;
                                    $duration = $isReturned ? $permit->duration_minutes : \Carbon\Carbon::parse($permit->time_out)->diffInMinutes(now());
                                    $isOverdue = $duration > 15 && !$isReturned;
                                    
                                    $rowClass = $isReturned ? 'tr-returned' : ($isOverdue ? 'tr-overdue' : 'tr-active');
                                @endphp
                            <tr class="transition-colors group {{ $rowClass }} status-border-left hover:bg-white/5">
                                {{-- WAKTU --}}
                                <td class="px-6 py-5">
                                    <div class="flex flex-col">
                                        <span class="font-black text-white">{{ \Carbon\Carbon::parse($permit->time_out)->format('H:i') }}</span>
                                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wide">{{ \Carbon\Carbon::parse($permit->time_out)->format('d M') }}</span>
                                    </div>
                                </td>

                                {{-- SISWA --}}
                                <td class="px-6 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-black text-base shadow-sm transition-transform group-hover:scale-105 border
                                            {{ $isOverdue ? 'bg-rose-500/20 text-rose-400 border-rose-500/30' : 'bg-white/10 text-sky-400 border-white/15' }}">
                                            {{ substr($permit->student->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-white text-sm group-hover:text-sky-300 transition-colors">{{ $permit->student->name }}</div>
                                            <div class="text-xs text-slate-400 font-medium flex items-center gap-1.5 mt-0.5">
                                                <span>{{ $permit->student->schoolClass->name ?? '-' }}</span>
                                                <span class="text-slate-500">•</span> 
                                                <span class="font-mono text-slate-400">{{ $permit->student->student_id }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- ALASAN --}}
                                <td class="px-6 py-5">
                                    <div class="flex flex-col items-start gap-1.5">
                                        <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wide bg-white/10 text-white border border-white/15 shadow-sm">
                                            {{ $permit->reason_category }}
                                        </span>
                                        @if($permit->notes)
                                            <span class="text-xs text-slate-400 italic max-w-[150px] truncate font-medium" title="{{ $permit->notes }}">
                                                "{{ $permit->notes }}"
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- DURASI --}}
                                <td class="px-6 py-5">
                                    <div class="font-mono font-bold text-lg {{ $isOverdue ? 'text-rose-400 animate-pulse' : 'text-slate-200' }}">
                                        {{ $duration }}<span class="text-[10px] text-slate-400 ml-0.5 font-sans font-bold">mnt</span>
                                    </div>
                                </td>

                                {{-- STATUS --}}
                                <td class="px-6 py-5">
                                    @if($isReturned)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-bold uppercase bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 shadow-sm">
                                            <i class="ph-bold ph-check"></i> Kembali
                                        </span>
                                    @else
                                        @if($isOverdue)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-bold uppercase bg-rose-500/20 text-rose-400 border border-rose-500/30 shadow-sm animate-pulse">
                                                <i class="ph-bold ph-warning"></i> Telat
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] font-bold uppercase bg-amber-500/20 text-amber-400 border border-amber-500/30 shadow-sm">
                                                <i class="ph-bold ph-timer"></i> Di Luar
                                            </span>
                                        @endif
                                    @endif
                                </td>

                                {{-- WAKTU KEMBALI --}}
                                <td class="px-6 py-5 text-right">
                                    @if($permit->time_in)
                                        <span class="font-black text-white bg-white/10 px-3 py-1.5 rounded-xl border border-white/15 shadow-sm inline-block">{{ \Carbon\Carbon::parse($permit->time_in)->format('H:i') }}</span>
                                    @else
                                        <span class="text-slate-500 italic text-xl px-2">...</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-24 text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-400">
                                        <div class="w-24 h-24 bg-white/5 rounded-full flex items-center justify-center mb-4 border border-white/10 shadow-inner">
                                            <i class="ph-duotone ph-magnifying-glass text-5xl text-sky-400 opacity-80"></i>
                                        </div>
                                        <p class="font-black text-white text-xl mb-1">Data tidak ditemukan</p>
                                        <p class="text-sm font-medium text-slate-400">Coba sesuaikan filter tanggal atau kata kunci pencarian.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                @if($permits->hasPages())
                <div class="bg-white/5 px-8 py-6 border-t border-white/10">
                    {{ $permits->withQueryString()->links() }}
                </div>
                @endif
            </div>

            {{-- MOBILE VIEW (Cards) - Optimized --}}
            <div class="md:hidden space-y-4">
                @forelse($permits as $permit)
                    @php
                        $isReturned = $permit->time_in != null;
                        $duration = $isReturned ? $permit->duration_minutes : \Carbon\Carbon::parse($permit->time_out)->diffInMinutes(now());
                        $isOverdue = $duration > 15 && !$isReturned;
                        $borderColor = $isReturned ? 'border-emerald-500' : ($isOverdue ? 'border-rose-500' : 'border-amber-400');
                    @endphp
                    <div class="bg-[#031d3d]/90 backdrop-blur-md p-5 rounded-[1.5rem] shadow-sm border border-white/10 relative overflow-hidden text-white">
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $borderColor }}"></div>
                        
                        <div class="flex justify-between items-start mb-4 pl-2">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center font-black text-sky-400 text-base shadow-sm border border-white/15">
                                    {{ substr($permit->student->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-white text-sm">{{ $permit->student->name }}</div>
                                    <div class="text-[10px] text-slate-400 font-mono mt-0.5 font-bold">{{ $permit->student->student_id }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-mono font-black text-white text-lg leading-none">{{ \Carbon\Carbon::parse($permit->time_out)->format('H:i') }}</div>
                                <div class="text-[10px] text-slate-400 uppercase font-bold mt-1">{{ \Carbon\Carbon::parse($permit->time_out)->format('d M') }}</div>
                            </div>
                        </div>
                        
                        <div class="pl-2 space-y-3">
                            <div class="flex items-center justify-between bg-white/5 rounded-xl p-3 border border-white/10">
                                <div>
                                    <div class="text-[9px] uppercase font-bold text-slate-400 mb-0.5">Keperluan</div>
                                    <div class="text-xs font-black text-white">{{ $permit->reason_category }}</div>
                                </div>
                                @if($permit->notes)
                                <div class="text-right max-w-[50%]">
                                    <div class="text-[9px] uppercase font-bold text-slate-400 mb-0.5">Catatan</div>
                                    <div class="text-[10px] italic text-slate-400 truncate font-medium">{{ $permit->notes }}</div>
                                </div>
                                @endif
                            </div>

                            <div class="flex items-center justify-between pt-1">
                                <div class="font-bold text-xs text-slate-300">
                                    Durasi: <span class="{{ $isOverdue ? 'text-rose-400' : 'text-white' }} font-mono font-black text-sm ml-1">{{ $duration }}m</span>
                                </div>
                                <div>
                                    @if($isReturned)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl bg-emerald-500/20 text-emerald-400 text-[10px] font-bold border border-emerald-500/30">
                                            Kembali {{ \Carbon\Carbon::parse($permit->time_in)->format('H:i') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl {{ $isOverdue ? 'bg-rose-500/20 text-rose-400 border-rose-500/30' : 'bg-amber-500/20 text-amber-400 border-amber-500/30' }} text-[10px] font-bold border">
                                            {{ $isOverdue ? 'Telat' : 'Di Luar' }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center text-slate-400 bg-[#031d3d]/90 rounded-[2rem] border border-white/10 border-dashed">
                        <i class="ph-duotone ph-magnifying-glass text-4xl opacity-50 mb-3 text-sky-400"></i>
                        <p class="font-bold text-sm text-white">Tidak ada data ditemukan</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        function setDate(dateStr) {
            document.getElementById('dateInput').value = dateStr;
            document.getElementById('filterForm').submit();
        }
    </script>
</x-app-layout>