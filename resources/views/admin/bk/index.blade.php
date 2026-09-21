<x-app-layout>
    <style>
        /* Sembunyikan scrollbar pada menu filter tab */
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* FullCalendar Dark Overrides */
        .fc { color: #f8fafc !important; }
        .fc .fc-toolbar-title { color: #ffffff !important; font-size: 1rem !important; font-weight: 800 !important; }
        .fc .fc-button-primary { background-color: rgba(255,255,255,0.1) !important; border-color: rgba(255,255,255,0.15) !important; color: #56bbf1 !important; font-weight: 700 !important; text-transform: uppercase; font-size: 0.75rem !important; }
        .fc .fc-button-primary:hover { background-color: rgba(86,187,241,0.2) !important; border-color: rgba(86,187,241,0.4) !important; }
        .fc .fc-button-active { background-color: #56bbf1 !important; border-color: #56bbf1 !important; color: #020b18 !important; }
        .fc th { border-color: rgba(255,255,255,0.1) !important; background-color: rgba(255,255,255,0.05) !important; color: #94a3b8 !important; text-transform: uppercase; font-size: 0.7rem; }
        .fc td, .fc-theme-standard td, .fc-theme-standard th { border-color: rgba(255,255,255,0.08) !important; }
        .fc .fc-daygrid-day-number { color: #cbd5e1 !important; font-weight: 600 !important; }
        .fc .fc-list-day-cushion, .fc .fc-list-event:hover td { background-color: rgba(255,255,255,0.05) !important; }

        /* CSS Khusus untuk Mode Cetak (Print / Save as PDF) */
        @media print {
            body { background-color: white !important; color: black !important; }
            .print\:hidden { display: none !important; }
            .print\:block { display: block !important; }
            .shadow-xl, .shadow-sm, .shadow-md, .shadow-lg, .shadow-2xl { box-shadow: none !important; border: none !important; }
            .bg-white, .bg-slate-50, .bg-\[\#020b18\], .bg-\[\#021124\] { background-color: white !important; color: black !important; }
            .table-container { overflow: visible !important; }
            table { width: 100% !important; border-collapse: collapse !important; color: black !important; }
            th, td { border: 1px solid #cbd5e1 !important; padding: 12px !important; color: black !important; }
            @page { margin: 1.5cm; size: landscape; } /* Landscape agar kolom tabel muat */
        }
    </style>

     {{-- TAMBAHKAN x-data UNTUK ALPINE.JS BULK ACTION MANAGER --}}
     <div x-data="bulkActionManager()" class="py-8 sm:py-10 font-sans bg-[#020b18] text-slate-100 min-h-screen relative">
        
        {{-- FLOATING BULK ACTION BAR --}}
        <div x-show="selected.length > 0" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-10"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform translate-y-10"
             class="fixed bottom-8 left-1/2 -translate-x-1/2 bg-[#021124] text-white px-6 py-4 rounded-full shadow-2xl z-50 flex items-center gap-4 print:hidden border border-white/15 w-max max-w-[90vw]" style="display: none;">
            
            <div class="flex items-center gap-2 bg-white/10 px-3 py-1.5 rounded-full text-xs font-black tracking-widest uppercase">
                <span class="w-5 h-5 bg-[#56bbf1] text-[#020b18] rounded-full flex items-center justify-center leading-none" x-text="selected.length"></span>
                Terpilih
            </div>
            
            <div class="w-px h-6 bg-white/20 hidden sm:block"></div>
            
            <button @click="submitBulk('wa')" class="text-[10px] sm:text-xs font-bold uppercase tracking-wider flex items-center gap-2 hover:text-emerald-400 transition-colors active:scale-95">
                <i class="ph-fill ph-whatsapp-logo text-lg sm:text-xl"></i> <span class="hidden sm:inline">Panggil WA Massal</span>
            </button>
            
            <div class="w-px h-6 bg-white/20"></div>
            
            <button @click="submitBulk('finish')" class="text-[10px] sm:text-xs font-bold uppercase tracking-wider flex items-center gap-2 hover:text-[#56bbf1] transition-colors active:scale-95">
                <i class="ph-fill ph-check-circle text-lg sm:text-xl"></i> <span class="hidden sm:inline">Tandai Selesai</span>
            </button>

            <div class="w-px h-6 bg-white/20"></div>

            <button @click="clearSelection()" class="text-slate-400 hover:text-white transition-colors p-1" title="Batal Pilih">
                <i class="ph-bold ph-x text-lg"></i>
            </button>
        </div>

        {{-- ========================================================= --}}
        {{-- KOP SURAT (HANYA MUNCUL SAAT DI-PRINT / CETAK PDF)        --}}
        {{-- ========================================================= --}}
        <div class="hidden print:block w-full border-b-4 border-double border-slate-800 pb-4 mb-8 text-center text-black">
            <h3 class="text-sm font-bold uppercase tracking-widest text-slate-600 mb-1">Pemerintah Provinsi Daerah</h3>
            <h1 class="text-2xl font-black uppercase tracking-wider text-slate-900 mb-1">Nama Sekolah Anda</h1>
            <p class="text-xs font-medium text-slate-700">Jl. Contoh Alamat Sekolah No. 123, Kota/Kabupaten, Kode Pos 12345</p>
            <h2 class="text-lg font-bold uppercase tracking-widest text-slate-800 mt-6 underline decoration-2 underline-offset-4">Rekapitulasi Data Bimbingan Konseling</h2>
            <p class="text-xs font-bold text-slate-500 mt-2">Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB</p>
        </div>

       {{-- HERO SECTION ELEVATE DARK GLASS THEME --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 print:hidden">
            <div class="relative rounded-[2.5rem] bg-gradient-to-r from-[#031d3d] via-[#021124] to-[#031d3d] p-8 sm:p-10 text-white shadow-2xl overflow-hidden border border-white/10 group">
                <div class="absolute -top-10 -left-10 w-48 h-48 bg-[#56bbf1]/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-[#56bbf1]/5 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    <div class="space-y-2 max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/10 text-[#56bbf1] text-[10px] font-bold uppercase tracking-widest backdrop-blur-sm shadow-sm">
                            <i class="ph-fill ph-hand-heart text-[#56bbf1]"></i> Student Care Center
                        </div>
                        <h1 class="text-3xl sm:text-4xl font-black tracking-tight leading-tight text-white">
                            E-Counseling & Bimbingan
                        </h1>
                        <p class="text-slate-300 text-sm sm:text-base font-medium leading-relaxed">
                            Kelola antrian konseling, jadwalkan pertemuan, dan pantau perkembangan siswa secara real-time.
                        </p>
                    </div>

                    {{-- Quick Action --}}
                    <div class="hidden md:block">
                        <div class="bg-white/5 backdrop-blur-md border border-white/10 rounded-2xl p-4 flex items-center gap-4 shadow-sm">
                            <div class="p-3 bg-[#56bbf1]/20 rounded-xl text-[#56bbf1] shadow-inner">
                                <i class="ph-duotone ph-calendar-check text-2xl"></i>
                            </div>
                            <div>
                                <div class="text-xs text-[#56bbf1] font-bold uppercase tracking-wider">Hari Ini</div>
                                <div class="text-lg font-black text-white">{{ now()->translatedFormat('l, d F Y') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- STATISTIK CARDS --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6 print:hidden">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <!-- Pending -->
                <a href="{{ route('admin.bk.index', ['status' => 'pending']) }}" class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-5 rounded-[1.5rem] shadow-xl border border-white/10 hover:border-amber-500/50 hover:-translate-y-1 transition-all group">
                    <div class="flex justify-between items-start mb-2">
                        <div class="p-2 bg-amber-500/10 rounded-xl text-amber-400 group-hover:bg-amber-500/20 transition-colors shadow-sm border border-amber-500/20">
                            <i class="ph-bold ph-hourglass text-xl"></i>
                        </div>
                        <span class="bg-amber-500/10 text-amber-400 border border-amber-500/20 py-1 px-2 rounded-lg text-[10px] font-bold uppercase">Pending</span>
                    </div>
                    <div class="text-3xl font-black text-white">{{ $stats['pending'] }}</div>
                    <div class="text-xs font-bold text-slate-400 mt-1">Menunggu Respon</div>
                </a>

                <!-- Approved (Terjadwal) -->
                <a href="{{ route('admin.bk.index', ['status' => 'approved']) }}" class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-5 rounded-[1.5rem] shadow-xl border border-white/10 hover:border-[#56bbf1]/50 hover:-translate-y-1 transition-all group">
                    <div class="flex justify-between items-start mb-2">
                        <div class="p-2 bg-[#56bbf1]/10 rounded-xl text-[#56bbf1] group-hover:bg-[#56bbf1]/20 transition-colors shadow-sm border border-[#56bbf1]/20">
                            <i class="ph-bold ph-calendar-check text-xl"></i>
                        </div>
                        <span class="bg-[#56bbf1]/10 text-[#56bbf1] border border-[#56bbf1]/20 py-1 px-2 rounded-lg text-[10px] font-bold uppercase">Terjadwal</span>
                    </div>
                    <div class="text-3xl font-black text-white">{{ $stats['approved'] }}</div>
                    <div class="text-xs font-bold text-slate-400 mt-1">Akan Datang</div>
                </a>

                <!-- Finished -->
                <a href="{{ route('admin.bk.index', ['status' => 'finished']) }}" class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-5 rounded-[1.5rem] shadow-xl border border-white/10 hover:border-emerald-500/50 hover:-translate-y-1 transition-all group">
                    <div class="flex justify-between items-start mb-2">
                        <div class="p-2 bg-emerald-500/10 rounded-xl text-emerald-400 group-hover:bg-emerald-500/20 transition-colors shadow-sm border border-emerald-500/20">
                            <i class="ph-bold ph-check-circle text-xl"></i>
                        </div>
                        <span class="bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 py-1 px-2 rounded-lg text-[10px] font-bold uppercase">Selesai</span>
                    </div>
                    <div class="text-3xl font-black text-white">{{ $stats['finished'] }}</div>
                    <div class="text-xs font-bold text-slate-400 mt-1">Bulan Ini</div>
                </a>

                <!-- Rejected -->
                <a href="{{ route('admin.bk.index', ['status' => 'rejected']) }}" class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-5 rounded-[1.5rem] shadow-xl border border-white/10 hover:border-rose-500/50 hover:-translate-y-1 transition-all group">
                    <div class="flex justify-between items-start mb-2">
                        <div class="p-2 bg-rose-500/10 rounded-xl text-rose-400 group-hover:bg-rose-500/20 transition-colors shadow-sm border border-rose-500/20">
                            <i class="ph-bold ph-x-circle text-xl"></i>
                        </div>
                        <span class="bg-rose-500/10 text-rose-400 border border-rose-500/20 py-1 px-2 rounded-lg text-[10px] font-bold uppercase">Ditolak</span>
                    </div>
                    <div class="text-3xl font-black text-white">{{ $stats['rejected'] }}</div>
                    <div class="text-xs font-bold text-slate-400 mt-1">Bulan Ini</div>
                </a>
            </div>
        </div>

        {{-- FILTER & SEARCH BAR YANG DIPERBARUI --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6 print:hidden" x-data="{ showAdvanced: {{ request('class_id') || request('start_date') || request('end_date') ? 'true' : 'false' }} }">
            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] backdrop-blur-xl p-6 rounded-[2rem] shadow-2xl border border-white/10 flex flex-col gap-5 transition-all">
                
                {{-- BARIS 1: JUDUL & PENCARIAN --}}
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 w-full">
                    <div class="flex items-center gap-3 text-sm font-black text-white uppercase tracking-wider shrink-0">
                        <div class="p-2 bg-[#56bbf1]/10 text-[#56bbf1] rounded-xl border border-[#56bbf1]/20">
                            <i class="ph-bold ph-funnel text-lg"></i>
                        </div>
                        Filter & Pencarian
                    </div>

                    {{-- SEARCH BAR DENGAN ADVANCED TOGGLE --}}
                    <form method="GET" action="{{ route('admin.bk.index') }}" class="w-full flex flex-col gap-4">
                        @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
                        @if(request('type')) <input type="hidden" name="type" value="{{ request('type') }}"> @endif
                        
                        {{-- Baris Pencarian Dasar --}}
                        <div class="flex flex-col sm:flex-row justify-end gap-3 w-full">
                            <div class="relative w-full md:max-w-md">
                                <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Siswa / Topik..." 
                                       class="w-full pl-11 pr-4 py-3 rounded-2xl border-white/15 bg-[#021124]/90 text-sm font-bold text-white focus:bg-[#021124] focus:ring-[#56bbf1] focus:border-[#56bbf1] transition-all shadow-sm placeholder:text-slate-500">
                            </div>
                            
                            <div class="flex gap-2">
                                <button type="button" @click="showAdvanced = !showAdvanced" class="bg-white/10 text-slate-300 border border-white/15 hover:bg-white/20 px-4 py-3 rounded-2xl text-sm font-bold transition-all shadow-sm flex items-center gap-2">
                                    <i class="ph-bold ph-faders"></i> <span class="hidden sm:inline">Advanced</span>
                                </button>
                                <button type="submit" class="bg-gradient-to-r from-[#56bbf1] to-[#3b82f6] text-white hover:brightness-110 px-6 py-3 rounded-2xl text-sm font-bold shadow-lg shadow-[#56bbf1]/20 transition-all active:scale-95">
                                    Cari
                                </button>
                                @if(request()->except('page'))
                                    <a href="{{ route('admin.bk.index') }}" class="bg-white/10 hover:bg-rose-500/20 text-slate-400 hover:text-rose-400 border border-white/15 hover:border-rose-500/30 px-4 py-3 rounded-2xl text-sm font-bold transition-all flex items-center justify-center shadow-sm" title="Reset Filter">
                                        <i class="ph-bold ph-arrow-counter-clockwise text-lg"></i>
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- Baris Advanced Filter (Kelas & Tanggal) --}}
                        <div x-show="showAdvanced" style="display: none;" x-transition.opacity class="w-full pt-4 border-t border-white/10 grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Pilih Kelas</label>
                                <select name="class_id" class="w-full rounded-xl border-white/15 bg-[#021124] font-bold text-sm text-white focus:ring-[#56bbf1] focus:border-[#56bbf1] transition-all py-2.5 px-4 cursor-pointer">
                                    <option value="" class="bg-[#021124] text-white">-- Semua Kelas --</option>
                                    @if(isset($classes))
                                        @foreach($classes as $cls)
                                            <option value="{{ $cls->id }}" {{ request('class_id') == $cls->id ? 'selected' : '' }} class="bg-[#021124] text-white">{{ $cls->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Dari Tanggal</label>
                                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full rounded-xl border-white/15 bg-[#021124] text-sm font-bold text-white focus:ring-[#56bbf1] focus:border-[#56bbf1] transition-all py-2.5 px-4">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Sampai Tanggal</label>
                                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full rounded-xl border-white/15 bg-[#021124] text-sm font-bold text-white focus:ring-[#56bbf1] focus:border-[#56bbf1] transition-all py-2.5 px-4">
                            </div>
                        </div>
                    </form>
                </div>

                {{-- DIVIDER --}}
                <div class="w-full h-px bg-white/10 mt-2"></div>

                {{-- BARIS 2: KELOMPOK FILTER TAB --}}
                <div class="w-full overflow-x-auto hide-scrollbar relative">
                    {{-- Hint Shadow Kanan untuk Scroll di HP --}}
                    <div class="absolute right-0 top-0 bottom-0 w-8 bg-gradient-to-l from-[#021124] to-transparent pointer-events-none md:hidden z-10"></div>
                    
                    <div class="flex items-center gap-4 w-max pb-1 pr-8">
                        {{-- Filter Status --}}
                        <div class="flex items-center gap-3">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status:</span>
                            <div class="p-1 bg-[#021124]/80 border border-white/10 rounded-xl flex gap-1 shadow-inner">
                                @foreach(['pending' => 'Pending', 'approved' => 'Terjadwal', 'all' => 'Semua'] as $key => $label)
                                    <a href="{{ request()->fullUrlWithQuery(['status' => $key, 'page' => 1]) }}" 
                                       class="px-4 py-2 rounded-lg text-xs font-bold text-center transition-all whitespace-nowrap
                                       {{ (request('status') == $key || ($key == 'all' && !request('status'))) 
                                            ? 'bg-[#56bbf1] text-[#020b18] shadow-sm font-black' 
                                            : 'text-slate-400 hover:text-white' }}">
                                       {{ $label }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        {{-- Divider Vertikal --}}
                        <div class="w-px h-8 bg-white/10 mx-2"></div>

                        {{-- Filter Tipe --}}
                        <div class="flex items-center gap-3">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tipe:</span>
                            <div class="p-1 bg-[#021124]/80 border border-white/10 rounded-xl flex gap-1 shadow-inner">
                                @foreach(['all' => 'Semua Tipe', 'bermasalah' => 'Bermasalah', 'berprestasi' => 'Berprestasi', 'mandiri' => 'Pengajuan Siswa'] as $key => $label)
                                    @php
                                        $activeClass = 'bg-white/15 text-white border border-white/20';
                                        if($key == 'bermasalah') $activeClass = 'bg-rose-500/20 text-rose-300 shadow-sm border border-rose-500/30';
                                        if($key == 'berprestasi') $activeClass = 'bg-[#56bbf1]/20 text-[#56bbf1] shadow-sm border border-[#56bbf1]/30';
                                        if($key == 'mandiri') $activeClass = 'bg-emerald-500/20 text-emerald-300 shadow-sm border border-emerald-500/30';
                                    @endphp
                                    <a href="{{ request()->fullUrlWithQuery(['type' => $key, 'page' => 1]) }}" 
                                       class="px-4 py-2 rounded-lg text-xs font-bold text-center transition-all whitespace-nowrap
                                       {{ (request('type') == $key || ($key == 'all' && !request('type'))) 
                                            ? $activeClass 
                                            : 'text-slate-400 hover:text-white' }}">
                                       {{ $label }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

        {{-- ANALYTICS & CALENDAR SECTION --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 print:hidden">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- Kiri: Chart Analitik --}}
                <div class="lg:col-span-1 flex flex-col gap-6">
                    {{-- Chart Kategori --}}
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-6 rounded-[2rem] shadow-2xl border border-white/10 flex-1">
                        <h3 class="text-sm font-black text-white mb-4 flex items-center gap-2 uppercase tracking-wider">
                            <i class="ph-bold ph-chart-pie-slice text-[#56bbf1] text-xl"></i> Sebaran Topik
                        </h3>
                        <div class="relative w-full h-48">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>
                    
                    {{-- Chart Kelas --}}
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-6 rounded-[2rem] shadow-2xl border border-white/10 flex-1">
                        <h3 class="text-sm font-black text-white mb-4 flex items-center gap-2 uppercase tracking-wider">
                            <i class="ph-bold ph-chart-bar text-[#56bbf1] text-xl"></i> Kelas Terbanyak
                        </h3>
                        <div class="relative w-full h-48">
                            <canvas id="classChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- Kanan: Kalender Jadwal --}}
                <div class="lg:col-span-2 bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-6 rounded-[2rem] shadow-2xl border border-white/10">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-black text-white flex items-center gap-2 uppercase tracking-wider">
                            <i class="ph-bold ph-calendar text-[#56bbf1] text-xl"></i> Jadwal Konseling
                        </h3>
                        <span class="px-3 py-1 bg-[#56bbf1]/10 text-[#56bbf1] text-[10px] font-bold rounded-lg border border-[#56bbf1]/20">
                            Hanya yang disetujui
                        </span>
                    </div>
                    <div id="calendar" class="w-full"></div>
                </div>

            </div>
        </div>
      
        {{-- MAIN CONTENT: TABEL DAFTAR --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden">
                
                {{-- Table Header --}}
                <div class="px-8 py-6 border-b border-white/10 bg-white/5 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-2">
                        <i class="ph-fill ph-list-dashes text-[#56bbf1] text-xl"></i>
                        <span class="text-sm font-black text-white">
                            @if(request('status') || request('type') || request('search'))
                                Hasil Pencarian Sesi
                            @else
                                Daftar Antrian & Riwayat Terbaru
                            @endif
                        </span>
                    </div>                    
                     {{-- TOMBOL EXPORT (EXCEL & PDF) --}}
                    <div class="flex flex-wrap items-center gap-2 print:hidden w-full sm:w-auto">
                        <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="flex-1 sm:flex-none text-xs font-bold text-emerald-400 bg-emerald-500/10 hover:bg-emerald-500/20 px-5 py-2.5 rounded-xl border border-emerald-500/20 transition-colors flex items-center justify-center gap-2 shadow-sm">
                            <i class="ph-bold ph-file-csv text-base"></i> Unduh Excel
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank" class="flex-1 sm:flex-none text-xs font-bold text-rose-400 bg-rose-500/10 hover:bg-rose-500/20 px-5 py-2.5 rounded-xl border border-rose-500/20 transition-colors flex items-center justify-center gap-2 shadow-sm">
                            <i class="ph-bold ph-printer text-base"></i> Cetak Laporan
                        </a>
                    </div>
                </div>

                {{-- Table Body --}}
                <div class="overflow-x-auto table-container">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-white/5 text-[10px] uppercase font-black text-slate-400 tracking-widest border-b border-white/10">
                            <tr>
                                {{-- HEADER CHECKBOX UNTUK SELECT ALL --}}
                                <th class="px-6 py-5 pl-8 w-12 print:hidden">
                                    <input type="checkbox" x-model="selectAll" @change="toggleAll" class="w-4 h-4 rounded border-white/20 bg-[#021124] text-[#56bbf1] focus:ring-[#56bbf1] transition-colors cursor-pointer shadow-sm">
                                </th>
                                <th class="px-2 py-5">Identitas Siswa</th>
                                <th class="px-6 py-5">Topik & Pesan</th>
                                <th class="px-6 py-5 print:hidden">Metode</th>
                                <th class="px-6 py-5">Status & Jadwal</th>
                                <th class="px-6 py-5 text-right pr-8 print:hidden">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5 text-sm">
                            @forelse($sessions as $session)
                            
                            {{-- LOGIKA SLA / OVERDUE: Tiket lebih dari 2 hari belum direspon --}}
                            @php
                                $isOverdue = $session->status == 'pending' && $session->created_at->diffInHours(now()) > 48;
                            @endphp

                            {{-- INTERAKTIF UX: Seluruh Baris Bisa Diklik Menuju Detail --}}
                            <tr class="hover:bg-white/5 transition-colors group cursor-pointer" onclick="window.location.href='{{ route('admin.bk.show', $session->id) }}'">
                                
                                {{-- CHECKBOX ITEM --}}
                                <td class="px-6 py-5 pl-8 w-12 print:hidden align-top" @click.stop>
                                    <input type="checkbox" class="session-checkbox w-4 h-4 rounded border-white/20 bg-[#021124] text-[#56bbf1] focus:ring-[#56bbf1] transition-colors cursor-pointer shadow-sm" value="{{ $session->id }}" x-model="selected">
                                </td>

                                <td class="px-2 py-5 align-top">
                                    <div class="flex items-center gap-3">
                                        <!-- Avatar -->
                                        <div class="w-10 h-10 rounded-[1rem] bg-white/10 flex items-center justify-center text-[#56bbf1] font-black text-xs shrink-0 overflow-hidden border border-white/10 shadow-sm print:hidden group-hover:border-[#56bbf1]/50 transition-colors">
                                            @if($session->student && $session->student->photo_path)
                                                <img src="{{ asset('storage/' . $session->student->photo_path) }}" class="w-full h-full object-cover">
                                            @else
                                                {{ substr($session->student?->name ?? '?', 0, 1) }}
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-black text-white group-hover:text-[#56bbf1] transition-colors leading-tight line-clamp-1">{{ $session->student?->name ?? 'Data Terhapus' }}</div>
                                            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ $session->student?->schoolClass?->name ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <div class="flex flex-wrap items-center gap-2 mb-2">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-white/10 border border-white/15 text-slate-200 text-[9px] font-black uppercase tracking-wider print:border-none print:px-0 print:py-0 print:bg-transparent shadow-sm">
                                            <i class="ph-bold ph-tag print:hidden text-[#56bbf1]"></i> {{ $session->category?->name ?? 'Umum' }}
                                        </span>
                                        
                                        @if($session->is_system_generated ?? false)
                                            @if(str_contains($session->initial_message, 'PELANGGARAN'))
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-500/20 text-rose-300 text-[9px] font-black rounded-lg border border-rose-500/30 uppercase tracking-widest animate-pulse print:border-none print:px-0 print:py-0 print:bg-transparent shadow-sm">
                                                    <i class="ph-bold ph-warning print:hidden"></i> Panggilan Sistem
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-[#56bbf1]/20 text-[#56bbf1] border border-[#56bbf1]/30 text-[9px] font-black rounded-lg uppercase tracking-widest print:border-none print:px-0 print:py-0 print:bg-transparent shadow-sm">
                                                    <i class="ph-bold ph-medal print:hidden"></i> Apresiasi Sistem
                                                </span>
                                            @endif
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-white/10 text-slate-400 text-[9px] font-black rounded-lg border border-white/15 uppercase tracking-widest print:hidden shadow-sm">
                                                <i class="ph-bold ph-user text-slate-400"></i> Pengajuan Siswa
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-sm text-slate-300 font-medium leading-relaxed line-clamp-2 max-w-xs md:max-w-md italic print:max-w-none print:whitespace-normal" title="{{ $session->initial_message }}">
                                        "{{ $session->initial_message }}"
                                    </p>
                                    
                                    <div class="text-[10px] mt-2 flex items-center gap-1.5 font-bold uppercase tracking-widest">
                                        <span class="text-slate-400 flex items-center gap-1"><i class="ph-bold ph-clock"></i> {{ $session->created_at->diffForHumans() }}</span>
                                        
                                        @if($isOverdue)
                                            <span class="ml-2 bg-rose-500/20 text-rose-300 border border-rose-500/30 px-1.5 py-0.5 rounded font-black tracking-wider uppercase animate-pulse print:hidden">
                                                > 48 Jam
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-sm text-slate-400 print:hidden align-top">
                                    @if($session->method == 'online')
                                        <div class="flex items-center gap-2">
                                            <div class="p-1.5 rounded-lg bg-[#56bbf1]/20 text-[#56bbf1] border border-[#56bbf1]/30">
                                                <i class="ph-bold ph-globe text-base"></i>
                                            </div>
                                            <span class="font-bold text-xs text-white uppercase tracking-wider">Online</span>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-2">
                                            <div class="p-1.5 rounded-lg bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                                                <i class="ph-bold ph-users text-base"></i>
                                            </div>
                                            <span class="font-bold text-xs text-white uppercase tracking-wider">Tatap Muka</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap align-top">
                                    @php
                                        $colors = [
                                            'pending' => 'bg-amber-500/20 text-amber-300 border-amber-500/30',
                                            'approved' => 'bg-[#56bbf1]/20 text-[#56bbf1] border-[#56bbf1]/30', 
                                            'ongoing' => 'bg-indigo-500/20 text-indigo-300 border-indigo-500/30',
                                            'finished' => 'bg-emerald-500/20 text-emerald-300 border-emerald-500/30',
                                            'rejected' => 'bg-rose-500/20 text-rose-300 border-rose-500/30',
                                        ];
                                        $statusClass = $colors[$session->status] ?? 'bg-white/10 text-slate-400 border-white/15';
                                    @endphp
                                    <span class="px-3 py-1.5 inline-flex text-[9px] font-black uppercase tracking-widest rounded-lg border {{ $statusClass }} print:border-none print:px-0 print:py-0 print:bg-transparent shadow-sm">
                                        {{ ucfirst($session->status == 'approved' ? 'Terjadwal' : $session->status) }}
                                    </span>
                                    
                                    @if($session->scheduled_at && $session->status == 'approved')
                                        <div class="text-[10px] font-black uppercase tracking-widest text-[#56bbf1] mt-2 flex items-center gap-1.5 bg-[#56bbf1]/10 px-2.5 py-1.5 rounded-lg border border-[#56bbf1]/20 w-fit print:bg-transparent print:p-0">
                                            <i class="ph-bold ph-calendar-check print:hidden"></i> {{ \Carbon\Carbon::parse($session->scheduled_at)->format('d M Y, H:i') }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-5 text-center print:hidden pr-8 align-top">
                                    <div class="flex items-center justify-end gap-2">
                                        {{-- TOMBOL SHORTCUT WA --}}
                                        @if($session->student && $session->student->parent_wa_number)
                                            <a href="https://wa.me/{{ preg_replace('/^0/', '62', $session->student->parent_wa_number) }}" 
                                               target="_blank" 
                                               @click.stop 
                                               class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-white/10 border border-white/15 text-emerald-400 hover:bg-emerald-500 hover:border-emerald-500 hover:text-white transition-all shadow-sm" title="WA Orang Tua">
                                                <i class="ph-fill ph-whatsapp-logo text-xl"></i>
                                            </a>
                                        @endif
                                        
                                        {{-- TOMBOL DETAIL --}}
                                        <div class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-[#56bbf1] text-[#020b18] group-hover:bg-[#56bbf1]/80 transition-all duration-300 shadow-md shadow-[#56bbf1]/20" title="Buka Detail">
                                            <i class="ph-bold ph-caret-right text-lg"></i>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <div class="w-20 h-20 bg-white/5 border border-white/10 rounded-full flex items-center justify-center shadow-inner">
                                            @if(request('type') == 'berprestasi')
                                                <i class="ph-duotone ph-medal text-4xl text-slate-500"></i>
                                            @elseif(request('type') == 'bermasalah')
                                                <i class="ph-duotone ph-warning-octagon text-4xl text-slate-500"></i>
                                            @else
                                                <i class="ph-duotone ph-clipboard-text text-4xl text-slate-500"></i>
                                            @endif
                                        </div>
                                        <span class="font-bold text-slate-400 text-base">
                                            @if(request('type') == 'berprestasi')
                                                Belum ada data siswa berprestasi.
                                            @elseif(request('type') == 'bermasalah')
                                                Belum ada data siswa bermasalah.
                                            @elseif(request('search'))
                                                Tidak ada data yang cocok dengan pencarian Anda.
                                            @else
                                                Belum ada data pengajuan konseling.
                                            @endif
                                        </span>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                {{-- Pagination --}}
                <div class="px-6 py-4 border-t border-white/10 bg-white/5 print:hidden">
                    {{ $sessions->links() }}
                </div>
            </div>
                        
              {{-- BAGIAN TANDA TANGAN (HANYA MUNCUL SAAT DI-PRINT REKAP TABEL) --}}
            <div class="hidden print:flex justify-between items-end mt-12 px-8 break-inside-avoid">
                <div class="text-center">
                    <p class="text-sm font-medium mb-16">Mengetahui,<br>Kepala Sekolah</p>
                    <p class="text-sm font-bold underline decoration-1 underline-offset-2">_________________________</p>
                    <p class="text-xs mt-1">NIP. ..............................</p>
                </div>
                <div class="text-center">
                    <p class="text-sm font-medium mb-16">Kota/Kabupaten, {{ now()->translatedFormat('d F Y') }}<br>Guru Bimbingan Konseling</p>
                    <p class="text-sm font-bold underline decoration-1 underline-offset-2">{{ Auth::user()->name ?? '_________________________' }}</p>
                    <p class="text-xs mt-1">NIP. ..............................</p>
                </div>
            </div>

        </div>
    </div>

    
    {{-- SCRIPT SWEETALERT2 & ALPINE JS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // --- LOGIKA BULK ACTION (ALPINE.JS) ---
        function bulkActionManager() {
            return {
                selected: [],
                selectAll: false,
                toggleAll() {
                    if (this.selectAll) {
                        this.selected = Array.from(document.querySelectorAll('.session-checkbox')).map(cb => cb.value);
                    } else {
                        this.selected = [];
                    }
                },
                clearSelection() {
                    this.selected = [];
                    this.selectAll = false;
                },
                submitBulk(actionType) {
                    if(this.selected.length === 0) return;

                    let titleText = actionType === 'finish' ? 'Tandai Selesai?' : 'Kirim Panggilan WA?';
                    let descText = actionType === 'finish' 
                        ? `Anda akan menandai ${this.selected.length} sesi sebagai selesai.` 
                        : `Sistem akan mengirim pesan WA massal ke ortu dari ${this.selected.length} siswa terpilih.`;
                    let confirmColor = actionType === 'finish' ? '#10b981' : '#56bbf1';

                    Swal.fire({
                        title: titleText,
                        text: descText,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: confirmColor,
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, Lanjutkan',
                        cancelButtonText: 'Batal',
                        background: '#021124',
                        color: '#ffffff',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-[2.5rem] font-sans border border-white/15 shadow-2xl',
                            confirmButton: 'rounded-xl font-bold px-6 py-3',
                            cancelButton: 'rounded-xl font-bold px-6 py-3'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Membuat Form Submit secara Dinamis
                            let form = document.createElement('form');
                            form.method = 'POST';
                            form.action = '{{ route("admin.bk.bulk_action") }}';

                            let csrf = document.createElement('input');
                            csrf.type = 'hidden';
                            csrf.name = '_token';
                            csrf.value = '{{ csrf_token() }}';
                            form.appendChild(csrf);

                            let actionInput = document.createElement('input');
                            actionInput.type = 'hidden';
                            actionInput.name = 'action_type';
                            actionInput.value = actionType;
                            form.appendChild(actionInput);

                            this.selected.forEach(id => {
                                let input = document.createElement('input');
                                input.type = 'hidden';
                                input.name = 'ids[]';
                                input.value = id;
                                form.appendChild(input);
                            });

                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success', title: 'Berhasil!', text: "{!! session('success') !!}",
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 3000,
                    background: '#021124', color: '#ffffff',
                    customClass: { popup: 'rounded-2xl border border-white/15 shadow-lg font-sans' }
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error', title: 'Oops...', text: "{!! session('error') !!}",
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 4000,
                    background: '#021124', color: '#ffffff',
                    customClass: { popup: 'rounded-2xl border border-white/15 shadow-lg font-sans' }
                });
            @endif
        });
    </script>

    {{-- Script untuk Chart & Calendar --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data dari Controller
            const categoryData = @json($chartCategoryData);
            const classData = @json($chartClassData);
            
            // 1. Render Category Chart (Pie)
            const ctxCat = document.getElementById('categoryChart').getContext('2d');
            new Chart(ctxCat, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(categoryData),
                    datasets: [{
                        data: Object.values(categoryData),
                        backgroundColor: ['#56bbf1', '#8b5cf6', '#ec4899', '#f59e0b', '#10b981'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right', labels: { boxWidth: 12, font: { size: 10 }, color: '#94a3b8' } }
                    }
                }
            });

            // 2. Render Class Chart (Bar)
            const ctxClass = document.getElementById('classChart').getContext('2d');
            new Chart(ctxClass, {
                type: 'bar',
                data: {
                    labels: Object.keys(classData),
                    datasets: [{
                        label: 'Total Kasus',
                        data: Object.values(classData),
                        backgroundColor: '#56bbf1',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { 
                        y: { beginAtZero: true, ticks: { stepSize: 1, color: '#94a3b8' }, grid: { color: 'rgba(255,255,255,0.05)' } },
                        x: { ticks: { color: '#94a3b8' }, grid: { display: false } }
                    },
                    plugins: { legend: { display: false } }
                }
            });

            // 3. Render FullCalendar
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                height: 400,
                events: {!! $calendarEvents ?? '[]' !!},
                eventClick: function(info) {
                    info.jsEvent.preventDefault(); // don't let the browser navigate
                    if (info.event.url) {
                        window.open(info.event.url, '_blank');
                    }
                }
            });
            calendar.render();
        });
    </script>
</x-app-layout>