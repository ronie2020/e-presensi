<x-app-layout>
    <style>
        /* Sembunyikan scrollbar pada menu filter tab */
        .hide-scrollbar::-webkit-scrollbar { display: none; }
        .hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* CSS Khusus untuk Mode Cetak (Print / Save as PDF) */
        @media print {
            body { background-color: white !important; }
            .print\:hidden { display: none !important; }
            .print\:block { display: block !important; }
            .shadow-xl, .shadow-sm, .shadow-md, .shadow-lg { box-shadow: none !important; border: none !important; }
            .bg-white, .bg-slate-50 { background-color: white !important; }
            .table-container { overflow: visible !important; }
            table { width: 100% !important; border-collapse: collapse !important; }
            th, td { border: 1px solid #cbd5e1 !important; padding: 12px !important; }
            @page { margin: 1.5cm; size: landscape; } /* Landscape agar kolom tabel muat */
        }
    </style>

     {{-- TAMBAHKAN x-data UNTUK ALPINE.JS BULK ACTION MANAGER --}}
     <div x-data="bulkActionManager()" class="py-8 sm:py-10 font-sans text-elevate-text bg-slate-50 min-h-screen relative">
        
        {{-- FLOATING BULK ACTION BAR --}}
        <div x-show="selected.length > 0" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-10"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform translate-y-0"
             x-transition:leave-end="opacity-0 transform translate-y-10"
             class="fixed bottom-8 left-1/2 -translate-x-1/2 bg-elevate-dark text-white px-6 py-4 rounded-full shadow-2xl shadow-elevate-dark/40 z-50 flex items-center gap-4 print:hidden border border-white/10 w-max max-w-[90vw]" style="display: none;">
            
            <div class="flex items-center gap-2 bg-white/10 px-3 py-1.5 rounded-full text-xs font-black tracking-widest uppercase">
                <span class="w-5 h-5 bg-elevate-accent text-elevate-dark rounded-full flex items-center justify-center leading-none" x-text="selected.length"></span>
                Terpilih
            </div>
            
            <div class="w-px h-6 bg-white/20 hidden sm:block"></div>
            
            <button @click="submitBulk('wa')" class="text-[10px] sm:text-xs font-bold uppercase tracking-wider flex items-center gap-2 hover:text-emerald-400 transition-colors active:scale-95">
                <i class="ph-fill ph-whatsapp-logo text-lg sm:text-xl"></i> <span class="hidden sm:inline">Panggil WA Massal</span>
            </button>
            
            <div class="w-px h-6 bg-white/20"></div>
            
            <button @click="submitBulk('finish')" class="text-[10px] sm:text-xs font-bold uppercase tracking-wider flex items-center gap-2 hover:text-elevate-accent transition-colors active:scale-95">
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
        <div class="hidden print:block w-full border-b-4 border-double border-slate-800 pb-4 mb-8 text-center">
            <h3 class="text-sm font-bold uppercase tracking-widest text-slate-600 mb-1">Pemerintah Provinsi Daerah</h3>
            <h1 class="text-2xl font-black uppercase tracking-wider text-slate-900 mb-1">Nama Sekolah Anda</h1>
            <p class="text-xs font-medium text-slate-700">Jl. Contoh Alamat Sekolah No. 123, Kota/Kabupaten, Kode Pos 12345</p>
            <h2 class="text-lg font-bold uppercase tracking-widest text-slate-800 mt-6 underline decoration-2 underline-offset-4">Rekapitulasi Data Bimbingan Konseling</h2>
            <p class="text-xs font-bold text-slate-500 mt-2">Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB</p>
        </div>

       {{-- HERO SECTION MICROSOFT ELEVATE THEME --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 print:hidden">
            <div class="relative rounded-[2rem] bg-elevate-gradient-main p-8 sm:p-10 text-elevate-dark shadow-xl shadow-elevate-accent/10 overflow-hidden border border-white/60 group">
                {{-- Background Decorations --}}
                <div class="absolute -top-10 -left-10 w-48 h-48 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/20 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>
                <div class="absolute top-10 right-32 w-24 h-24 bg-white/40 rounded-2xl rotate-45 pointer-events-none shadow-sm"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    <div class="space-y-2 max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/50 border border-white/60 text-elevate-dark text-[10px] font-bold uppercase tracking-widest backdrop-blur-sm shadow-sm">
                            <i class="ph-fill ph-hand-heart text-elevate-primary"></i> Student Care Center
                        </div>
                        <h1 class="text-3xl sm:text-4xl font-black tracking-tight leading-tight text-elevate-dark">
                            E-Counseling & Bimbingan
                        </h1>
                        <p class="text-elevate-dark/80 text-sm sm:text-base font-medium leading-relaxed">
                            Kelola antrian konseling, jadwalkan pertemuan, dan pantau perkembangan siswa secara real-time.
                        </p>
                    </div>

                    {{-- Quick Action --}}
                    <div class="hidden md:block">
                        <div class="bg-white/60 backdrop-blur-md border border-white rounded-2xl p-4 flex items-center gap-4 shadow-sm">
                            <div class="p-3 bg-elevate-accent/20 rounded-xl text-elevate-primary shadow-inner">
                                <i class="ph-duotone ph-calendar-check text-2xl"></i>
                            </div>
                            <div>
                                <div class="text-xs text-elevate-primary font-bold uppercase tracking-wider">Hari Ini</div>
                                <div class="text-lg font-black text-elevate-dark">{{ now()->translatedFormat('l, d F Y') }}</div>
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
                <a href="{{ route('admin.bk.index', ['status' => 'pending']) }}" class="bg-white p-5 rounded-[1.5rem] shadow-sm border border-amber-100 hover:border-amber-300 hover:shadow-md hover:-translate-y-1 transition-all group">
                    <div class="flex justify-between items-start mb-2">
                        <div class="p-2 bg-amber-50 rounded-xl text-amber-600 group-hover:bg-amber-100 transition-colors shadow-sm border border-amber-100">
                            <i class="ph-bold ph-hourglass text-xl"></i>
                        </div>
                        <span class="bg-amber-100 text-amber-700 py-1 px-2 rounded-lg text-[10px] font-bold uppercase">Pending</span>
                    </div>
                    <div class="text-3xl font-black text-elevate-dark">{{ $stats['pending'] }}</div>
                    <div class="text-xs font-bold text-slate-400 mt-1">Menunggu Respon</div>
                </a>

                <!-- Approved (Terjadwal) -->
                <a href="{{ route('admin.bk.index', ['status' => 'approved']) }}" class="bg-white p-5 rounded-[1.5rem] shadow-sm border border-elevate-primary/20 hover:border-elevate-primary/50 hover:shadow-md hover:-translate-y-1 transition-all group">
                    <div class="flex justify-between items-start mb-2">
                        <div class="p-2 bg-elevate-accent/10 rounded-xl text-elevate-primary group-hover:bg-elevate-primary group-hover:text-white transition-colors shadow-sm border border-elevate-accent/20">
                            <i class="ph-bold ph-calendar-check text-xl"></i>
                        </div>
                        <span class="bg-elevate-accent/10 text-elevate-primary border border-elevate-accent/20 py-1 px-2 rounded-lg text-[10px] font-bold uppercase">Terjadwal</span>
                    </div>
                    <div class="text-3xl font-black text-elevate-dark">{{ $stats['approved'] }}</div>
                    <div class="text-xs font-bold text-slate-400 mt-1">Akan Datang</div>
                </a>

                <!-- Finished -->
                <a href="{{ route('admin.bk.index', ['status' => 'finished']) }}" class="bg-white p-5 rounded-[1.5rem] shadow-sm border border-emerald-100 hover:border-emerald-300 hover:shadow-md hover:-translate-y-1 transition-all group">
                    <div class="flex justify-between items-start mb-2">
                        <div class="p-2 bg-emerald-50 rounded-xl text-emerald-600 group-hover:bg-emerald-100 transition-colors shadow-sm border border-emerald-100">
                            <i class="ph-bold ph-check-circle text-xl"></i>
                        </div>
                        <span class="bg-emerald-100 text-emerald-700 py-1 px-2 rounded-lg text-[10px] font-bold uppercase">Selesai</span>
                    </div>
                    <div class="text-3xl font-black text-elevate-dark">{{ $stats['finished'] }}</div>
                    <div class="text-xs font-bold text-slate-400 mt-1">Bulan Ini</div>
                </a>

                <!-- Rejected -->
                <a href="{{ route('admin.bk.index', ['status' => 'rejected']) }}" class="bg-white p-5 rounded-[1.5rem] shadow-sm border border-rose-100 hover:border-rose-300 hover:shadow-md hover:-translate-y-1 transition-all group">
                    <div class="flex justify-between items-start mb-2">
                        <div class="p-2 bg-rose-50 rounded-xl text-rose-600 group-hover:bg-rose-100 transition-colors shadow-sm border border-rose-100">
                            <i class="ph-bold ph-x-circle text-xl"></i>
                        </div>
                        <span class="bg-rose-100 text-rose-700 py-1 px-2 rounded-lg text-[10px] font-bold uppercase">Ditolak</span>
                    </div>
                    <div class="text-3xl font-black text-elevate-dark">{{ $stats['rejected'] }}</div>
                    <div class="text-xs font-bold text-slate-400 mt-1">Bulan Ini</div>
                </a>
            </div>
        </div>

        {{-- FILTER & SEARCH BAR YANG DIPERBARUI --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6 print:hidden" x-data="{ showAdvanced: {{ request('class_id') || request('start_date') || request('end_date') ? 'true' : 'false' }} }">
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col gap-5 transition-all">
                
                {{-- BARIS 1: JUDUL & PENCARIAN --}}
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 w-full">
                    <div class="flex items-center gap-3 text-sm font-black text-elevate-dark uppercase tracking-wider shrink-0">
                        <div class="p-2 bg-elevate-accent/10 text-elevate-primary rounded-xl border border-elevate-accent/20">
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
                                       class="w-full pl-11 pr-4 py-3 rounded-2xl border-slate-200 bg-slate-50 text-sm font-bold text-elevate-dark focus:bg-white focus:ring-elevate-primary focus:border-elevate-primary transition-all shadow-sm">
                            </div>
                            
                            <div class="flex gap-2">
                                <button type="button" @click="showAdvanced = !showAdvanced" class="bg-slate-50 text-slate-500 border border-slate-200 hover:bg-slate-100 px-4 py-3 rounded-2xl text-sm font-bold transition-all shadow-sm flex items-center gap-2">
                                    <i class="ph-bold ph-faders"></i> <span class="hidden sm:inline">Advanced</span>
                                </button>
                                <button type="submit" class="bg-elevate-dark hover:bg-elevate-primary text-white px-6 py-3 rounded-2xl text-sm font-bold shadow-lg shadow-elevate-dark/20 transition-all active:scale-95">
                                    Cari
                                </button>
                                @if(request()->except('page'))
                                    <a href="{{ route('admin.bk.index') }}" class="bg-white hover:bg-rose-50 text-slate-400 hover:text-rose-500 border border-slate-200 hover:border-rose-200 px-4 py-3 rounded-2xl text-sm font-bold transition-all flex items-center justify-center shadow-sm" title="Reset Filter">
                                        <i class="ph-bold ph-arrow-counter-clockwise text-lg"></i>
                                    </a>
                                @endif
                            </div>
                        </div>

                        {{-- Baris Advanced Filter (Kelas & Tanggal) --}}
                        <div x-show="showAdvanced" style="display: none;" x-transition.opacity class="w-full pt-4 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Pilih Kelas</label>
                                <select name="class_id" class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-sm text-elevate-dark focus:ring-elevate-primary focus:border-elevate-primary transition-all py-2.5 px-4 cursor-pointer">
                                    <option value="">-- Semua Kelas --</option>
                                    @if(isset($classes))
                                        @foreach($classes as $cls)
                                            <option value="{{ $cls->id }}" {{ request('class_id') == $cls->id ? 'selected' : '' }}>{{ $cls->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Dari Tanggal</label>
                                <input type="date" name="start_date" value="{{ request('start_date') }}" class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm font-bold text-elevate-dark focus:ring-elevate-primary focus:border-elevate-primary transition-all py-2.5 px-4">
                            </div>
                            <div>
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Sampai Tanggal</label>
                                <input type="date" name="end_date" value="{{ request('end_date') }}" class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm font-bold text-elevate-dark focus:ring-elevate-primary focus:border-elevate-primary transition-all py-2.5 px-4">
                            </div>
                        </div>
                    </form>
                </div>

                {{-- DIVIDER --}}
                <div class="w-full h-px bg-slate-100 mt-2"></div>

                {{-- BARIS 2: KELOMPOK FILTER TAB --}}
                <div class="w-full overflow-x-auto hide-scrollbar relative">
                    {{-- Hint Shadow Kanan untuk Scroll di HP --}}
                    <div class="absolute right-0 top-0 bottom-0 w-8 bg-gradient-to-l from-white to-transparent pointer-events-none md:hidden z-10"></div>
                    
                    <div class="flex items-center gap-4 w-max pb-1 pr-8">
                        {{-- Filter Status --}}
                        <div class="flex items-center gap-3">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Status:</span>
                            <div class="p-1 bg-slate-50 border border-slate-100 rounded-xl flex gap-1 shadow-inner">
                                @foreach(['pending' => 'Pending', 'approved' => 'Terjadwal', 'all' => 'Semua'] as $key => $label)
                                    <a href="{{ request()->fullUrlWithQuery(['status' => $key, 'page' => 1]) }}" 
                                       class="px-4 py-2 rounded-lg text-xs font-bold text-center transition-all whitespace-nowrap
                                       {{ (request('status') == $key || ($key == 'all' && !request('status'))) 
                                            ? 'bg-white text-elevate-primary shadow-sm border border-slate-200/60' 
                                            : 'text-slate-500 hover:text-elevate-dark' }}">
                                       {{ $label }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        {{-- Divider Vertikal --}}
                        <div class="w-px h-8 bg-slate-200 mx-2"></div>

                        {{-- Filter Tipe --}}
                        <div class="flex items-center gap-3">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Tipe:</span>
                            <div class="p-1 bg-slate-50 border border-slate-100 rounded-xl flex gap-1 shadow-inner">
                                @foreach(['all' => 'Semua Tipe', 'bermasalah' => 'Bermasalah', 'berprestasi' => 'Berprestasi', 'mandiri' => 'Pengajuan Siswa'] as $key => $label)
                                    @php
                                        $activeClass = 'bg-white text-elevate-dark shadow-sm border border-slate-200/60';
                                        if($key == 'bermasalah') $activeClass = 'bg-rose-50 text-rose-600 shadow-sm border border-rose-200/60';
                                        if($key == 'berprestasi') $activeClass = 'bg-elevate-accent/10 text-elevate-primary shadow-sm border border-elevate-accent/20';
                                        if($key == 'mandiri') $activeClass = 'bg-emerald-50 text-emerald-600 shadow-sm border border-emerald-200/60';
                                    @endphp
                                    <a href="{{ request()->fullUrlWithQuery(['type' => $key, 'page' => 1]) }}" 
                                       class="px-4 py-2 rounded-lg text-xs font-bold text-center transition-all whitespace-nowrap
                                       {{ (request('type') == $key || ($key == 'all' && !request('type'))) 
                                            ? $activeClass 
                                            : 'text-slate-500 hover:text-elevate-dark' }}">
                                       {{ $label }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

      
        {{-- MAIN CONTENT: TABEL DAFTAR --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                
                {{-- Table Header --}}
                <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-2">
                        <i class="ph-fill ph-list-dashes text-elevate-primary text-xl"></i>
                        <span class="text-sm font-black text-elevate-dark">
                            @if(request('status') || request('type') || request('search'))
                                Hasil Pencarian Sesi
                            @else
                                Daftar Antrian & Riwayat Terbaru
                            @endif
                        </span>
                    </div>                    
                     {{-- TOMBOL EXPORT (EXCEL & PDF) --}}
                    <div class="flex flex-wrap items-center gap-2 print:hidden w-full sm:w-auto">
                        <a href="{{ request()->fullUrlWithQuery(['export' => 'excel']) }}" class="flex-1 sm:flex-none text-xs font-bold text-emerald-600 bg-emerald-50 hover:bg-emerald-600 hover:text-white px-5 py-2.5 rounded-xl border border-emerald-200 transition-colors flex items-center justify-center gap-2 shadow-sm">
                            <i class="ph-bold ph-file-csv text-base"></i> Unduh Excel
                        </a>
                        <a href="{{ request()->fullUrlWithQuery(['export' => 'pdf']) }}" target="_blank" class="flex-1 sm:flex-none text-xs font-bold text-rose-600 bg-rose-50 hover:bg-rose-600 hover:text-white px-5 py-2.5 rounded-xl border border-rose-200 transition-colors flex items-center justify-center gap-2 shadow-sm">
                            <i class="ph-bold ph-printer text-base"></i> Cetak Laporan
                        </a>
                    </div>
                </div>

                {{-- Table Body --}}
                <div class="overflow-x-auto table-container">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 text-[10px] uppercase font-black text-slate-400 tracking-widest">
                            <tr>
                                {{-- HEADER CHECKBOX UNTUK SELECT ALL --}}
                                <th class="px-6 py-5 pl-8 w-12 print:hidden">
                                    <input type="checkbox" x-model="selectAll" @change="toggleAll" class="w-4 h-4 rounded border-slate-300 text-elevate-primary focus:ring-elevate-primary transition-colors cursor-pointer shadow-sm">
                                </th>
                                <th class="px-2 py-5">Identitas Siswa</th>
                                <th class="px-6 py-5">Topik & Pesan</th>
                                <th class="px-6 py-5 print:hidden">Metode</th>
                                <th class="px-6 py-5">Status & Jadwal</th>
                                <th class="px-6 py-5 text-right pr-8 print:hidden">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm">
                            @forelse($sessions as $session)
                            
                            {{-- LOGIKA SLA / OVERDUE: Tiket lebih dari 2 hari belum direspon --}}
                            @php
                                $isOverdue = $session->status == 'pending' && $session->created_at->diffInHours(now()) > 48;
                            @endphp

                            {{-- INTERAKTIF UX: Seluruh Baris Bisa Diklik Menuju Detail --}}
                            <tr class="hover:bg-slate-50/80 transition-colors group cursor-pointer" onclick="window.location.href='{{ route('admin.bk.show', $session->id) }}'">
                                
                                {{-- CHECKBOX ITEM (Diperbaiki dengan @click.stop) --}}
                                <td class="px-6 py-5 pl-8 w-12 print:hidden align-top" @click.stop>
                                    <input type="checkbox" class="session-checkbox w-4 h-4 rounded border-slate-300 text-elevate-primary focus:ring-elevate-primary transition-colors cursor-pointer shadow-sm" value="{{ $session->id }}" x-model="selected">
                                </td>

                                <td class="px-2 py-5 align-top">
                                    <div class="flex items-center gap-3">
                                        <!-- Avatar -->
                                        <div class="w-10 h-10 rounded-[1rem] bg-slate-100 flex items-center justify-center text-elevate-primary font-black text-xs shrink-0 overflow-hidden border border-slate-200 shadow-sm print:hidden group-hover:border-elevate-primary/30 transition-colors">
                                            @if($session->student && $session->student->photo_path)
                                                <img src="{{ asset('storage/' . $session->student->photo_path) }}" class="w-full h-full object-cover">
                                            @else
                                                {{ substr($session->student?->name ?? '?', 0, 1) }}
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-black text-elevate-dark group-hover:text-elevate-primary transition-colors leading-tight line-clamp-1">{{ $session->student?->name ?? 'Data Terhapus' }}</div>
                                            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-0.5">{{ $session->student?->schoolClass?->name ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                    <td class="px-6 py-5 align-top">
                                        <div class="flex flex-wrap items-center gap-2 mb-2">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-white border border-slate-200 text-slate-600 text-[9px] font-black uppercase tracking-wider print:border-none print:px-0 print:py-0 print:bg-transparent shadow-sm">
                                                <i class="ph-bold ph-tag print:hidden text-elevate-primary"></i> {{ $session->category?->name ?? 'Umum' }}
                                            </span>
                                            
                                            @if($session->is_system_generated ?? false)
                                                @if(str_contains($session->initial_message, 'PELANGGARAN'))
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-50 text-rose-700 text-[9px] font-black rounded-lg border border-rose-200 uppercase tracking-widest animate-pulse print:border-none print:px-0 print:py-0 print:bg-transparent print:text-rose-600 shadow-sm">
                                                        <i class="ph-bold ph-warning print:hidden"></i> Panggilan Sistem
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-elevate-accent/10 text-elevate-primary border border-elevate-accent/20 text-[9px] font-black rounded-lg uppercase tracking-widest print:border-none print:px-0 print:py-0 print:bg-transparent shadow-sm">
                                                        <i class="ph-bold ph-medal print:hidden"></i> Apresiasi Sistem
                                                    </span>
                                                @endif
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-slate-100 text-slate-500 text-[9px] font-black rounded-lg border border-slate-200 uppercase tracking-widest print:hidden shadow-sm">
                                                    <i class="ph-bold ph-user text-slate-400"></i> Pengajuan Siswa
                                                </span>
                                            @endif
                                        </div>
                                        {{-- Diperbaiki menggunakan line-clamp agar lebih dinamis --}}
                                        <p class="text-sm text-slate-600 font-medium leading-relaxed line-clamp-2 max-w-xs md:max-w-md italic print:max-w-none print:whitespace-normal" title="{{ $session->initial_message }}">
                                            "{{ $session->initial_message }}"
                                        </p>
                                        
                                        <div class="text-[10px] mt-2 flex items-center gap-1.5 font-bold uppercase tracking-widest">
                                            <span class="text-slate-400 flex items-center gap-1"><i class="ph-bold ph-clock"></i> {{ $session->created_at->diffForHumans() }}</span>
                                            
                                            {{-- LENCANA SLA / KETERLAMBATAN --}}
                                            @if($isOverdue)
                                                <span class="ml-2 bg-rose-100 text-rose-700 border border-rose-200 px-1.5 py-0.5 rounded font-black tracking-wider uppercase animate-pulse print:hidden">
                                                    > 48 Jam
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                <td class="px-6 py-5 whitespace-nowrap text-sm text-slate-500 print:hidden align-top">
                                    @if($session->method == 'online')
                                        <div class="flex items-center gap-2">
                                            <div class="p-1.5 rounded-lg bg-elevate-accent/10 text-elevate-primary border border-elevate-accent/20">
                                                <i class="ph-bold ph-globe text-base"></i>
                                            </div>
                                            <span class="font-bold text-xs text-elevate-dark uppercase tracking-wider">Online</span>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-2">
                                            <div class="p-1.5 rounded-lg bg-emerald-50 text-emerald-600 border border-emerald-100">
                                                <i class="ph-bold ph-users text-base"></i>
                                            </div>
                                            <span class="font-bold text-xs text-elevate-dark uppercase tracking-wider">Tatap Muka</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap align-top">
                                    @php
                                        $colors = [
                                            'pending' => 'bg-amber-50 text-amber-600 border-amber-200',
                                            'approved' => 'bg-elevate-accent/10 text-elevate-primary border-elevate-accent/20', 
                                            'ongoing' => 'bg-indigo-50 text-indigo-600 border-indigo-200',
                                            'finished' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                            'rejected' => 'bg-rose-50 text-rose-600 border-rose-200',
                                        ];
                                        $statusClass = $colors[$session->status] ?? 'bg-slate-50 text-slate-500 border-slate-200';
                                    @endphp
                                    <span class="px-3 py-1.5 inline-flex text-[9px] font-black uppercase tracking-widest rounded-lg border {{ $statusClass }} print:border-none print:px-0 print:py-0 print:bg-transparent shadow-sm">
                                        {{ ucfirst($session->status == 'approved' ? 'Terjadwal' : $session->status) }}
                                    </span>
                                    
                                    @if($session->scheduled_at && $session->status == 'approved')
                                        <div class="text-[10px] font-black uppercase tracking-widest text-elevate-primary mt-2 flex items-center gap-1.5 bg-elevate-accent/5 px-2.5 py-1.5 rounded-lg border border-elevate-accent/20 w-fit print:bg-transparent print:p-0">
                                            <i class="ph-bold ph-calendar-check print:hidden"></i> {{ \Carbon\Carbon::parse($session->scheduled_at)->format('d M Y, H:i') }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-5 text-center print:hidden pr-8 align-top">
                                    <div class="flex items-center justify-end gap-2">
                                        {{-- TOMBOL SHORTCUT WA (Tanpa masuk halaman detail - Diperbaiki dgn @click.stop) --}}
                                        @if($session->student && $session->student->parent_wa_number)
                                            <a href="https://wa.me/{{ preg_replace('/^0/', '62', $session->student->parent_wa_number) }}" 
                                               target="_blank" 
                                               @click.stop 
                                               class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-white border border-slate-200 text-emerald-500 hover:bg-emerald-500 hover:border-emerald-500 hover:text-white transition-all shadow-sm" title="WA Orang Tua">
                                                <i class="ph-fill ph-whatsapp-logo text-xl"></i>
                                            </a>
                                        @endif
                                        
                                        {{-- TOMBOL DETAIL --}}
                                        <div class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-elevate-dark text-white group-hover:bg-elevate-primary transition-all duration-300 shadow-md shadow-elevate-dark/20" title="Buka Detail">
                                            <i class="ph-bold ph-caret-right text-lg"></i>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <div class="w-20 h-20 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center shadow-inner">
                                            @if(request('type') == 'berprestasi')
                                                <i class="ph-duotone ph-medal text-4xl text-slate-300"></i>
                                            @elseif(request('type') == 'bermasalah')
                                                <i class="ph-duotone ph-warning-octagon text-4xl text-slate-300"></i>
                                            @else
                                                <i class="ph-duotone ph-clipboard-text text-4xl text-slate-300"></i>
                                            @endif
                                        </div>
                                        <span class="font-bold text-slate-500 text-base">
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
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50 print:hidden">
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
                    let confirmColor = actionType === 'finish' ? '#10b981' : '#0f172a'; // emerald or dark

                    Swal.fire({
                        title: titleText,
                        text: descText,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: confirmColor,
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Ya, Lanjutkan',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-[2.5rem] font-sans border border-slate-100 shadow-2xl',
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
                    customClass: { popup: 'rounded-2xl border border-slate-100 shadow-lg font-sans' }
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error', title: 'Oops...', text: "{!! session('error') !!}",
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 4000,
                    customClass: { popup: 'rounded-2xl border border-slate-100 shadow-lg font-sans' }
                });
            @endif
        });
    </script>
</x-app-layout>