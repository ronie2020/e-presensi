<x-app-layout>
    {{-- 
        LAYOUT MODERN (BLUE THEME)
        - Menyesuaikan style halaman Kelulusan
        - Warna dominan: Blue-900 (Hero), Blue-600 (Primary)
        - PENINGKATAN: Clickable Rows, Quick WA, SLA Tracker, & Print Mode
    --}}
    
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

    <div class="py-8 sm:py-10 font-sans text-slate-800">
        
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

        {{-- HERO SECTION --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 print:hidden">
            <div class="relative rounded-[2.5rem] bg-gray-900 bg-gradient-to-br from-blue-950 via-blue-900 to-slate-900 p-8 sm:p-10 text-white shadow-2xl shadow-blue-900/40 overflow-hidden border border-white/10 group">
                
                {{-- Background Decorations --}}
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none group-hover:bg-emerald-500/30 transition-all duration-700"></div>
                <div class="absolute bottom-0 right-20 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    <div class="space-y-2">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-300 text-xs font-bold uppercase tracking-widest backdrop-blur-sm">
                            <i class="ph-fill ph-hand-heart"></i> Student Care Center
                        </div>
                        <h1 class="text-3xl sm:text-4xl font-black tracking-tight leading-tight">
                            E-Counseling & <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-emerald-200">Bimbingan</span>
                        </h1>
                        <p class="text-blue-200/80 text-sm sm:text-base max-w-xl font-medium">
                            Kelola antrian konseling, jadwalkan pertemuan, dan pantau perkembangan siswa secara real-time.
                        </p>
                    </div>

                    {{-- Quick Action --}}
                    <div class="hidden md:block">
                        <div class="bg-white/10 backdrop-blur-md border border-white/10 rounded-2xl p-4 flex items-center gap-4">
                            <div class="p-3 bg-blue-500 rounded-xl text-white shadow-lg shadow-blue-500/30">
                                <i class="ph-duotone ph-calendar-check text-2xl"></i>
                            </div>
                            <div>
                                <div class="text-xs text-blue-200 font-bold uppercase">Hari Ini</div>
                                <div class="text-lg font-bold text-white">{{ now()->translatedFormat('l, d F Y') }}</div>
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
                <a href="{{ route('admin.bk.index', ['status' => 'pending']) }}" class="bg-white p-5 rounded-2xl shadow-sm border border-amber-100 hover:border-amber-300 hover:shadow-md transition group">
                    <div class="flex justify-between items-start mb-2">
                        <div class="p-2 bg-amber-50 rounded-lg text-amber-600 group-hover:bg-amber-100 transition">
                            <i class="ph-bold ph-hourglass text-xl"></i>
                        </div>
                        <span class="bg-amber-100 text-amber-700 py-1 px-2 rounded text-[10px] font-bold uppercase">Pending</span>
                    </div>
                    <div class="text-3xl font-black text-slate-800">{{ $stats['pending'] }}</div>
                    <div class="text-xs font-bold text-slate-400 mt-1">Menunggu Respon</div>
                </a>

                <!-- Approved -->
                <a href="{{ route('admin.bk.index', ['status' => 'approved']) }}" class="bg-white p-5 rounded-2xl shadow-sm border border-blue-100 hover:border-blue-300 hover:shadow-md transition group">
                    <div class="flex justify-between items-start mb-2">
                        <div class="p-2 bg-blue-50 rounded-lg text-blue-600 group-hover:bg-blue-100 transition">
                            <i class="ph-bold ph-calendar-check text-xl"></i>
                        </div>
                        <span class="bg-blue-100 text-blue-700 py-1 px-2 rounded text-[10px] font-bold uppercase">Terjadwal</span>
                    </div>
                    <div class="text-3xl font-black text-slate-800">{{ $stats['approved'] }}</div>
                    <div class="text-xs font-bold text-slate-400 mt-1">Akan Datang</div>
                </a>

                <!-- Finished -->
                <a href="{{ route('admin.bk.index', ['status' => 'finished']) }}" class="bg-white p-5 rounded-2xl shadow-sm border border-emerald-100 hover:border-emerald-300 hover:shadow-md transition group">
                    <div class="flex justify-between items-start mb-2">
                        <div class="p-2 bg-emerald-50 rounded-lg text-emerald-600 group-hover:bg-emerald-100 transition">
                            <i class="ph-bold ph-check-circle text-xl"></i>
                        </div>
                        <span class="bg-emerald-100 text-emerald-700 py-1 px-2 rounded text-[10px] font-bold uppercase">Selesai</span>
                    </div>
                    <div class="text-3xl font-black text-slate-800">{{ $stats['finished'] }}</div>
                    <div class="text-xs font-bold text-slate-400 mt-1">Bulan Ini</div>
                </a>

                <!-- Rejected -->
                <a href="{{ route('admin.bk.index', ['status' => 'rejected']) }}" class="bg-white p-5 rounded-2xl shadow-sm border border-rose-100 hover:border-rose-300 hover:shadow-md transition group">
                    <div class="flex justify-between items-start mb-2">
                        <div class="p-2 bg-rose-50 rounded-lg text-rose-600 group-hover:bg-rose-100 transition">
                            <i class="ph-bold ph-x-circle text-xl"></i>
                        </div>
                        <span class="bg-rose-100 text-rose-700 py-1 px-2 rounded text-[10px] font-bold uppercase">Ditolak</span>
                    </div>
                    <div class="text-3xl font-black text-slate-800">{{ $stats['rejected'] }}</div>
                    <div class="text-xs font-bold text-slate-400 mt-1">Bulan Ini</div>
                </a>
            </div>
        </div>

        {{-- FILTER & SEARCH BAR --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6 print:hidden">
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex flex-col gap-5">
                
                {{-- BARIS 1: JUDUL & PENCARIAN --}}
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 w-full">
                    <div class="flex items-center gap-3 text-sm font-bold text-slate-600">
                        <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                            <i class="ph-bold ph-funnel text-lg"></i>
                        </div>
                        Filter & Pencarian Sesi
                    </div>

                    {{-- SEARCH BAR --}}
                    <form method="GET" action="{{ route('admin.bk.index') }}" class="w-full md:w-auto shrink-0 flex flex-col sm:flex-row gap-2">
                        @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
                        @if(request('type')) <input type="hidden" name="type" value="{{ request('type') }}"> @endif
                        
                        <div class="relative w-full sm:w-64 lg:w-72">
                            <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Siswa / Topik..." 
                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 bg-slate-50 text-sm font-bold focus:ring-blue-500 focus:border-blue-500 transition-all">
                        </div>
                        <div class="flex gap-2 w-full sm:w-auto">
                            <button type="submit" class="flex-1 sm:flex-none bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm transition-colors text-center">
                                Cari
                            </button>
                            @if(request('search') || request('status') || request('type'))
                                <a href="{{ route('admin.bk.index') }}" class="flex-1 sm:flex-none bg-slate-100 hover:bg-slate-200 text-slate-600 px-5 py-2.5 rounded-xl text-sm font-bold transition-colors flex items-center justify-center">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>

                {{-- DIVIDER --}}
                <div class="w-full h-px bg-slate-100"></div>

                {{-- BARIS 2: KELOMPOK FILTER TAB --}}
                <div class="w-full overflow-x-auto hide-scrollbar">
                    <div class="flex items-center gap-4 w-max pb-1">
                        
                        {{-- Filter Status --}}
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Status:</span>
                            <div class="p-1 bg-slate-50 border border-slate-100 rounded-xl flex gap-1">
                                @foreach(['pending' => 'Pending', 'approved' => 'Terjadwal', 'all' => 'Semua Status'] as $key => $label)
                                    <a href="{{ request()->fullUrlWithQuery(['status' => $key, 'page' => 1]) }}" 
                                       class="px-4 py-2 rounded-lg text-xs font-bold text-center transition-all whitespace-nowrap
                                       {{ (request('status') == $key || ($key == 'all' && !request('status'))) 
                                            ? 'bg-white text-blue-600 shadow-sm border border-slate-200/60' 
                                            : 'text-slate-500 hover:text-slate-700' }}">
                                       {{ $label }}
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        {{-- Divider Vertikal --}}
                        <div class="w-px h-8 bg-slate-200 mx-2"></div>

                        {{-- Filter Tipe --}}
                        <div class="flex items-center gap-3">
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tipe:</span>
                            <div class="p-1 bg-slate-50 border border-slate-100 rounded-xl flex gap-1">
                                @foreach(['all' => 'Semua Tipe', 'bermasalah' => 'Bermasalah', 'berprestasi' => 'Berprestasi', 'mandiri' => 'Pengajuan Siswa'] as $key => $label)
                                    @php
                                        $activeClass = 'bg-white text-slate-800 shadow-sm border border-slate-200/60';
                                        if($key == 'bermasalah') $activeClass = 'bg-rose-50 text-rose-600 shadow-sm border border-rose-200/60';
                                        if($key == 'berprestasi') $activeClass = 'bg-blue-50 text-blue-600 shadow-sm border border-blue-200/60';
                                        if($key == 'mandiri') $activeClass = 'bg-indigo-50 text-indigo-600 shadow-sm border border-indigo-200/60';
                                    @endphp
                                    <a href="{{ request()->fullUrlWithQuery(['type' => $key, 'page' => 1]) }}" 
                                       class="px-4 py-2 rounded-lg text-xs font-bold text-center transition-all whitespace-nowrap
                                       {{ (request('type') == $key || ($key == 'all' && !request('type'))) 
                                            ? $activeClass 
                                            : 'text-slate-500 hover:text-slate-700' }}">
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
            <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden">
                
                {{-- Table Header --}}
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <div class="flex items-center gap-2">
                        <i class="ph-duotone ph-list-dashes text-blue-500 text-lg"></i>
                        <span class="text-sm font-bold text-slate-700">
                            @if(request('status') || request('type') || request('search'))
                                Hasil Filter Rekam Konseling
                            @else
                                Daftar Antrian & Riwayat Terbaru
                            @endif
                        </span>
                    </div>
                    
                    {{-- TOMBOL CETAK TABEL --}}
                    <button onclick="window.print()" class="print:hidden text-xs font-bold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 px-4 py-2 rounded-xl border border-indigo-200 transition-colors flex items-center gap-2">
                        <i class="ph-bold ph-printer"></i> Cetak Tabel Ini
                    </button>
                </div>

                {{-- Table Body --}}
                <div class="overflow-x-auto table-container">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 text-xs uppercase font-bold text-slate-400 tracking-wider">
                            <tr>
                                <th class="px-6 py-4 rounded-tl-2xl">Siswa</th>
                                <th class="px-6 py-4">Topik & Pesan</th>
                                <th class="px-6 py-4 print:hidden">Metode</th>
                                <th class="px-6 py-4">Status & Jadwal</th>
                                <th class="px-6 py-4 rounded-tr-2xl text-center print:hidden">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm">
                            @forelse($sessions as $session)
                            
                            {{-- LOGIKA SLA / OVERDUE: Tiket lebih dari 2 hari belum direspon --}}
                            @php
                                $isOverdue = $session->status == 'pending' && $session->created_at->diffInHours(now()) > 48;
                            @endphp

                            {{-- INTERAKTIF UX: Seluruh Baris Bisa Diklik Menuju Detail --}}
                            <tr class="hover:bg-blue-50/30 transition-colors group cursor-pointer" onclick="window.location.href='{{ route('admin.bk.show', $session->id) }}'">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <!-- Avatar -->
                                        <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-bold text-xs shrink-0 overflow-hidden border-2 border-white shadow-sm print:hidden">
                                            @if($session->student && $session->student->photo_path)
                                                <img src="{{ asset('storage/' . $session->student->photo_path) }}" class="w-full h-full object-cover">
                                            @else
                                                {{ substr($session->student->name ?? '?', 0, 1) }}
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-800 group-hover:text-blue-600 transition">{{ $session->student->name ?? 'Data Terhapus' }}</div>
                                            <div class="text-xs text-slate-400 font-medium">{{ $session->student->schoolClass->name ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap items-center gap-2 mb-1">
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-slate-100 border border-slate-200 text-slate-600 text-[10px] font-bold uppercase print:border-none print:px-0 print:py-0 print:bg-transparent">
                                                <i class="ph-bold ph-tag print:hidden"></i> {{ $session->category->name ?? 'Umum' }}
                                            </span>
                                            
                                            @if($session->is_system_generated ?? false)
                                                @if(str_contains($session->initial_message, 'PELANGGARAN'))
                                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-rose-50 text-rose-700 text-[10px] font-black rounded-lg border border-rose-200 uppercase tracking-widest animate-pulse print:border-none print:px-0 print:py-0 print:bg-transparent print:text-rose-600">
                                                        <i class="ph-bold ph-warning print:hidden"></i> Urgent: Sistem
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 text-blue-700 text-[10px] font-black rounded-lg border border-blue-200 uppercase tracking-widest print:border-none print:px-0 print:py-0 print:bg-transparent">
                                                        <i class="ph-bold ph-medal print:hidden"></i> Apresiasi Sistem
                                                    </span>
                                                @endif
                                            @else
                                                <span class="inline-flex items-center gap-1 px-2 py-1 bg-slate-50 text-slate-500 text-[10px] font-bold rounded-lg border border-slate-200 uppercase tracking-widest print:hidden">
                                                    <i class="ph-bold ph-user"></i> Pengajuan Siswa
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-slate-600 truncate w-48 italic print:w-auto print:whitespace-normal" title="{{ $session->initial_message }}">
                                            "{{ $session->initial_message }}"
                                        </p>
                                        
                                        <div class="text-[10px] mt-1 flex items-center gap-1 font-medium">
                                            <span class="text-slate-400"><i class="ph-bold ph-clock"></i> {{ $session->created_at->diffForHumans() }}</span>
                                            
                                            {{-- LENCANA SLA / KETERLAMBATAN --}}
                                            @if($isOverdue)
                                                <span class="ml-2 bg-rose-100 text-rose-700 border border-rose-200 px-1.5 py-0.5 rounded font-black tracking-wider uppercase animate-pulse print:hidden">
                                                    > 48 Jam
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 print:hidden">
                                    @if($session->method == 'online')
                                        <div class="flex items-center gap-2">
                                            <div class="p-1.5 rounded-full bg-purple-100 text-purple-600">
                                                <i class="ph-bold ph-globe"></i>
                                            </div>
                                            <span class="font-bold text-xs text-slate-600">Online</span>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-2">
                                            <div class="p-1.5 rounded-full bg-teal-100 text-teal-600">
                                                <i class="ph-bold ph-users"></i>
                                            </div>
                                            <span class="font-bold text-xs text-slate-600">Tatap Muka</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $colors = [
                                            'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                            'approved' => 'bg-blue-100 text-blue-700 border-blue-200', 
                                            'ongoing' => 'bg-purple-100 text-purple-700 border-purple-200',
                                            'finished' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                            'rejected' => 'bg-rose-100 text-rose-700 border-rose-200',
                                        ];
                                        $statusClass = $colors[$session->status] ?? 'bg-slate-100 text-slate-700';
                                    @endphp
                                    <span class="px-2.5 py-1 inline-flex text-[10px] font-black uppercase tracking-wide rounded-lg border {{ $statusClass }} print:border-none print:px-0 print:py-0 print:bg-transparent">
                                        {{ ucfirst($session->status == 'approved' ? 'Terjadwal' : $session->status) }}
                                    </span>
                                    
                                    @if($session->scheduled_at && $session->status == 'approved')
                                        <div class="text-xs text-blue-600 font-bold mt-1.5 flex items-center gap-1.5 bg-blue-50 px-2 py-1 rounded-md w-fit print:bg-transparent print:p-0">
                                            <i class="ph-bold ph-calendar-check print:hidden"></i> {{ $session->scheduled_at->format('d M, H:i') }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center print:hidden">
                                    <div class="flex items-center justify-center gap-2">
                                        {{-- TOMBOL SHORTCUT WA (Tanpa masuk halaman detail) --}}
                                        @if($session->student && $session->student->parent_wa_number)
                                            <a href="https://wa.me/{{ preg_replace('/^0/', '62', $session->student->parent_wa_number) }}" 
                                               target="_blank" 
                                               onclick="event.stopPropagation();" 
                                               class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-emerald-50 text-emerald-500 hover:bg-emerald-500 hover:text-white transition-all" title="WA Orang Tua">
                                                <i class="ph-fill ph-whatsapp-logo text-lg"></i>
                                            </a>
                                        @endif
                                        
                                        {{-- TOMBOL DETAIL --}}
                                        <div class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-slate-100 text-slate-400 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300" title="Buka Detail">
                                            <i class="ph-bold ph-caret-right text-lg"></i>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                    <div class="flex flex-col items-center justify-center gap-3">
                                        <div class="p-4 bg-slate-50 rounded-full">
                                            @if(request('type') == 'berprestasi')
                                                <i class="ph-duotone ph-medal text-3xl text-slate-300"></i>
                                            @elseif(request('type') == 'bermasalah')
                                                <i class="ph-duotone ph-warning-octagon text-3xl text-slate-300"></i>
                                            @else
                                                <i class="ph-duotone ph-clipboard-text text-3xl text-slate-300"></i>
                                            @endif
                                        </div>
                                        <span class="font-medium">
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
                <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 print:hidden">
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
</x-app-layout>