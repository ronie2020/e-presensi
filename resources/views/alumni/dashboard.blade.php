@extends('layouts.public')

@section('content')
    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { height: 0px; background: transparent; }
        .custom-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    {{-- X-DATA UNTUK MENGATUR TAB --}}
    <div class="min-h-screen bg-slate-50 font-sans text-slate-800 pb-20"
         x-data="{ activeTab: 'ringkasan' }">
        
        {{-- 1. HERO SECTION --}}
        <div class="relative bg-slate-900 pb-20 pt-24 lg:pt-32 overflow-hidden rounded-b-[3rem] shadow-xl mb-8">
            {{-- Decoration --}}
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
            <div class="absolute top-0 right-0 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none translate-x-1/2 -translate-y-1/2"></div>
            
            <div class="max-w-6xl mx-auto px-6 relative z-10">
                <div class="flex flex-col md:flex-row items-center gap-8">
                    {{-- Avatar --}}
                    <div class="relative shrink-0">
                        <div class="w-24 h-24 md:w-32 md:h-32 rounded-full border-4 border-amber-500/30 p-1 bg-slate-800 shadow-2xl">
                            @if($student->photo_path)
                                <img src="{{ asset('storage/' . $student->photo_path) }}" class="w-full h-full object-cover rounded-full bg-slate-700" alt="{{ $student->name }}">
                            @else
                                <div class="w-full h-full rounded-full bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-3xl font-black text-white">
                                    {{ substr($student->name, 0, 2) }}
                                </div>
                            @endif
                        </div>
                        <div class="absolute -bottom-2 -right-2 bg-amber-500 text-slate-900 text-[10px] font-black px-3 py-1 rounded-full shadow-lg border border-slate-900 flex items-center gap-1">
                            <i class="ph-fill ph-graduation-cap"></i> ALUMNI {{ $student->graduation_year ?? date('Y') }}
                        </div>
                    </div>

                    {{-- Text --}}
                    <div class="text-center md:text-left flex-1">
                        <h1 class="text-3xl md:text-4xl font-black text-white mb-2 tracking-tight">
                            Halo, {{ $student->nickname ?? explode(' ', $student->name)[0] }}!
                        </h1>
                        <p class="text-slate-400 text-sm md:text-base max-w-2xl">
                            Selamat datang di Dashboard Alumni. Ini adalah pusat data kelulusan dan arsip sekolahmu.
                        </p>
                    </div>

                    {{-- Logout --}}
                    <form method="POST" action="{{ route('student.logout') }}">
                        @csrf
                        <button type="submit" class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white rounded-xl text-xs font-bold backdrop-blur-md transition border border-white/10 flex items-center gap-2">
                            <i class="ph-bold ph-sign-out"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- 2. NAVIGATION TABS --}}
        <div class="max-w-6xl mx-auto px-4 sm:px-6 -mt-12 relative z-20 mb-6">
            <div class="bg-white/90 backdrop-blur-xl p-1.5 rounded-2xl shadow-lg border border-slate-200/60 overflow-x-auto custom-scrollbar flex justify-center">
                <div class="flex items-center gap-1 w-max">
                    <button @click="activeTab = 'ringkasan'" 
                        :class="activeTab === 'ringkasan' ? 'bg-slate-900 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700'"
                        class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 flex items-center gap-2 whitespace-nowrap">
                        <i class="ph-bold ph-squares-four text-lg"></i> Ringkasan & Karir
                    </button>
                    <button @click="activeTab = 'prestasi'" 
                        :class="activeTab === 'prestasi' ? 'bg-emerald-600 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50 hover:text-emerald-700'"
                        class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 flex items-center gap-2 whitespace-nowrap">
                        <i class="ph-bold ph-trophy text-lg"></i> Riwayat Prestasi
                    </button>
                     <button @click="activeTab = 'perpustakaan'" 
                        :class="activeTab === 'perpustakaan' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50 hover:text-indigo-700'"
                        class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 flex items-center gap-2 whitespace-nowrap">
                        <i class="ph-bold ph-books text-lg"></i> Riwayat Pustaka
                    </button>
                    <button @click="activeTab = 'akademik'" 
                        :class="activeTab === 'akademik' ? 'bg-rose-600 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50 hover:text-rose-700'"
                        class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 flex items-center gap-2 whitespace-nowrap">
                        <i class="ph-bold ph-file-text text-lg"></i> Nilai Rapor
                    </button>
                </div>
            </div>
        </div>

        {{-- 3. CONTENT AREAS --}}
        <div class="max-w-6xl mx-auto px-4 sm:px-6 relative z-10">
            
            {{-- === TAB 1: RINGKASAN === --}}
            <div x-show="activeTab === 'ringkasan'" x-transition.duration.300ms>
                
                {{-- ALERT STATUS TRACER --}}
                @if(!$isTracerFilled)
                <div class="bg-amber-50 border border-amber-200 rounded-[2rem] p-6 md:p-8 shadow-lg shadow-amber-900/5 mb-8 flex flex-col md:flex-row items-center gap-6 animate-pulse">
                    <div class="w-16 h-16 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center text-3xl shrink-0">
                        <i class="ph-duotone ph-warning-circle"></i>
                    </div>
                    <div class="flex-1 text-center md:text-left">
                        <h3 class="text-lg font-bold text-amber-900 mb-1">Data Alumni Belum Lengkap!</h3>
                        <p class="text-sm text-amber-700/80">
                            Mohon luangkan waktu untuk mengisi data Sekolah Lanjutan atau Pekerjaan Anda saat ini demi kelengkapan database alumni.
                        </p>
                    </div>
                    <a href="{{ route('alumni.tracer') }}" class="px-8 py-3 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl shadow-lg shadow-amber-500/30 transition-all transform hover:-translate-y-1 whitespace-nowrap">
                        <i class="ph-bold ph-pencil-simple mr-1"></i> Isi Tracer Study
                    </a>
                </div>
                @else
                <div class="bg-emerald-50 border border-emerald-100 rounded-[2rem] p-6 mb-8 flex flex-col sm:flex-row items-center gap-4 shadow-sm">
                    <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-2xl shrink-0">
                        <i class="ph-fill ph-check-circle"></i>
                    </div>
                    <div class="flex-1 text-center sm:text-left">
                        <h3 class="text-base font-bold text-emerald-800">Data Alumni Terverifikasi</h3>
                        <p class="text-sm text-emerald-600">Terima kasih telah berkontribusi. Data Anda aman tersimpan.</p>
                    </div>
                    <a href="{{ route('alumni.tracer') }}" class="text-sm font-bold text-emerald-700 underline hover:text-emerald-900">
                        Edit Data
                    </a>
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    {{-- KARTU STATUS SAAT INI --}}
                    <div class="md:col-span-2 bg-white rounded-[2.5rem] p-8 shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-40 h-40 bg-slate-50 rounded-full mix-blend-multiply filter blur-3xl opacity-50 -mr-10 -mt-10 group-hover:bg-blue-50 transition-colors"></div>
                        
                        <div class="flex items-center gap-3 mb-6 relative z-10">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl"><i class="ph-bold ph-briefcase"></i></div>
                            <h3 class="text-lg font-black text-slate-800">Aktivitas Saat Ini</h3>
                        </div>

                        @if($profile)
                            <div class="flex flex-col md:flex-row gap-6 items-center md:items-start bg-slate-50 p-6 rounded-3xl border border-slate-100">
                                <div class="w-20 h-20 rounded-2xl flex items-center justify-center text-4xl shadow-sm bg-white text-slate-700">
                                    @if(in_array($profile->activity_status, ['SMA', 'SMK', 'MA']))
                                        <i class="ph-duotone ph-student text-blue-500"></i>
                                    @elseif($profile->activity_status == 'Pesantren')
                                        <i class="ph-duotone ph-mosque text-emerald-500"></i>
                                    @elseif($profile->activity_status == 'Bekerja')
                                        <i class="ph-duotone ph-briefcase text-amber-500"></i>
                                    @else
                                        <i class="ph-duotone ph-user text-slate-500"></i>
                                    @endif
                                </div>
                                <div class="text-center md:text-left">
                                    <span class="inline-block px-3 py-1 bg-white border border-slate-200 rounded-lg text-[10px] font-bold uppercase tracking-wider text-slate-500 mb-2">
                                        {{ $profile->activity_status }}
                                    </span>
                                    <h4 class="text-xl font-bold text-slate-900 mb-1">
                                        {{ $profile->campus_name ?? $profile->company_name ?? 'Data Belum Lengkap' }}
                                    </h4>
                                    <p class="text-sm text-slate-500">
                                        {{ $profile->campus_major ?? $profile->position ?? '-' }} 
                                        @if($profile->campus_entry_year) • Angkatan {{ $profile->campus_entry_year }} @endif
                                    </p>
                                </div>
                            </div>
                            
                            <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="flex items-center gap-3 p-3 rounded-2xl border border-slate-100 hover:bg-slate-50 transition">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500"><i class="ph-fill ph-whatsapp-logo"></i></div>
                                    <span class="text-sm font-bold text-slate-600">{{ $profile->phone_number ?? '-' }}</span>
                                </div>
                                <div class="flex items-center gap-3 p-3 rounded-2xl border border-slate-100 hover:bg-slate-50 transition">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-500"><i class="ph-fill ph-envelope-simple"></i></div>
                                    <span class="text-sm font-bold text-slate-600 truncate">{{ $profile->email ?? '-' }}</span>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-10 bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                                <i class="ph-duotone ph-folder-dashed text-4xl text-slate-300 mb-2"></i>
                                <p class="text-sm text-slate-400 font-medium">Data belum tersedia</p>
                            </div>
                        @endif
                    </div>

                {{-- MENU LAINNYA --}}
                    <div class="space-y-6">
                        <div class="bg-white rounded-[2.5rem] p-6 shadow-xl shadow-slate-200/50 border border-slate-100">
                            <h3 class="text-base font-bold text-slate-800 mb-4 px-2">Menu Lainnya</h3>
                            <div class="space-y-3">
                                {{-- PERBAIKAN: Mengganti tombol "Lihat Web Publik" (portal.show) menjadi "Jejak Alumni" (public.testimonials) --}}
                                <a href="{{ route('public.testimonials') }}" class="flex items-center gap-4 p-4 rounded-2xl bg-slate-50 hover:bg-blue-50 hover:text-blue-700 transition group">
                                    <div class="w-10 h-10 rounded-xl bg-white text-slate-400 shadow-sm flex items-center justify-center group-hover:text-blue-600 transition">
                                        <i class="ph-bold ph-users-three"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-bold text-sm">Jejak Alumni</p>
                                        <p class="text-[10px] text-slate-400 group-hover:text-blue-400">Lihat sebaran alumni lain</p>
                                    </div>
                                    <i class="ph-bold ph-arrow-right text-sm opacity-0 group-hover:opacity-100 transition-opacity"></i>
                                </a>

                                <button onclick="window.print()" class="w-full flex items-center gap-4 p-4 rounded-2xl bg-slate-50 hover:bg-indigo-50 hover:text-indigo-700 transition group text-left">
                                    <div class="w-10 h-10 rounded-xl bg-white text-slate-400 shadow-sm flex items-center justify-center group-hover:text-indigo-600 transition">
                                        <i class="ph-bold ph-printer"></i>
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-bold text-sm">Cetak Biodata</p>
                                        <p class="text-[10px] text-slate-400 group-hover:text-indigo-400">Arsip data diri</p>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- === TAB 2: PRESTASI === --}}
            <div x-show="activeTab === 'prestasi'" x-cloak x-transition.duration.300ms>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {{-- Summary Card --}}
                    <div class="lg:col-span-1">
                        <div class="bg-white p-6 rounded-3xl shadow-lg shadow-emerald-100/50 border border-emerald-100 sticky top-4">
                            <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl mb-4">
                                <i class="ph-duotone ph-trophy"></i>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 mb-1">Daftar Prestasi</h3>
                            <p class="text-sm text-slate-500 mb-6">Akumulasi prestasi selama bersekolah.</p>
                            
                            <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-2xl p-6 text-center text-white shadow-lg shadow-emerald-500/30">
                                <p class="text-4xl font-black mb-1">{{ isset($achievements) ? count($achievements) : 0 }}</p>
                                <p class="text-xs font-medium opacity-80 uppercase tracking-widest">PENGHARGAAN</p>
                            </div>
                        </div>
                    </div>

                    {{-- Timeline --}}
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden min-h-[400px]">
                            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                                <h3 class="font-bold text-slate-800">Jejak Histori Prestasi</h3>
                            </div>
                            <div class="divide-y divide-slate-50">
                                @if(isset($achievements) && count($achievements) > 0)
                                    @foreach($achievements as $record)
                                    <div class="p-6 hover:bg-emerald-50/30 transition-colors flex gap-4 items-start">
                                        <div class="flex-shrink-0 w-14 text-center">
                                            <div class="text-2xl font-black text-slate-300">
                                                {{ \Carbon\Carbon::parse($record->date)->format('d') }}
                                            </div>
                                            <div class="text-[10px] font-bold text-slate-400 uppercase">
                                                {{ \Carbon\Carbon::parse($record->date)->translatedFormat('M Y') }}
                                            </div>
                                        </div>
                                        <div class="flex-grow">
                                            <div class="flex justify-between items-start mb-1">
                                                {{-- PERBAIKAN: Memanggil kolom bawaan title dan level --}}
                                                <h4 class="font-bold text-slate-800 text-lg">{{ $record->title ?? 'Prestasi' }}</h4>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-bold bg-emerald-100 text-emerald-700">
                                                    {{ $record->level ?? '-' }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-slate-500">{{ $record->description ?? 'Tanpa keterangan' }}</p>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="p-16 text-center flex flex-col items-center justify-center">
                                        <i class="ph-duotone ph-star text-4xl text-slate-200 mb-3"></i>
                                        <p class="text-slate-400 text-sm">Belum ada data prestasi tercatat.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- === TAB 3: PERPUSTAKAAN === --}}
            <div x-show="activeTab === 'perpustakaan'" x-cloak x-transition.duration.300ms>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {{-- Summary --}}
                    <div class="lg:col-span-1">
                        <div class="bg-white p-6 rounded-3xl shadow-lg shadow-indigo-100/50 border border-indigo-100 sticky top-4">
                            <div class="w-14 h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-2xl mb-4">
                                <i class="ph-duotone ph-books"></i>
                            </div>
                            <h3 class="text-lg font-bold text-slate-800 mb-1">Literasi</h3>
                            <p class="text-sm text-slate-500 mb-6">Ringkasan aktivitas perpustakaan.</p>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between items-center p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                    <span class="text-xs font-bold text-slate-500 uppercase">Total Kunjungan</span>
                                    <span class="text-xl font-black text-slate-800">{{ $library_visits ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between items-center p-4 bg-indigo-50 rounded-2xl border border-indigo-100">
                                    <span class="text-xs font-bold text-indigo-600 uppercase">Total Buku Dipinjam</span>
                                    <span class="text-xl font-black text-indigo-700">{{ isset($library_history) ? count($library_history) : 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- History List --}}
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden min-h-[400px]">
                            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                                <h3 class="font-bold text-slate-800">Riwayat Peminjaman Buku</h3>
                            </div>
                            <div class="divide-y divide-slate-50">
                                @if(isset($library_history) && count($library_history) > 0)
                                    @foreach($library_history as $book)
                                     <div class="p-5 hover:bg-indigo-50/30 transition-colors flex items-center gap-5">
                                        <div class="w-12 h-16 bg-slate-200 rounded-md flex-shrink-0 flex items-center justify-center text-slate-400 shadow-sm border border-slate-300/50">
                                            <i class="ph-fill ph-book-open text-2xl"></i>
                                        </div>
                                        <div class="flex-grow min-w-0">
                                            <h4 class="font-bold text-slate-800 truncate mb-1" title="{{ $book->book->title ?? $book->title ?? 'Buku Perpustakaan' }}">{{ $book->book->title ?? $book->title ?? 'Buku Perpustakaan' }}</h4>
                                            <div class="flex flex-wrap items-center gap-3 text-xs text-slate-500">
                                                <span class="flex items-center gap-1">
                                                    <i class="ph-bold ph-calendar-blank"></i> 
                                                    {{ \Carbon\Carbon::parse($book->borrow_date)->translatedFormat('d M Y') }}
                                                </span>
                                            </div>
                                        </div>
                                        <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-[10px] font-bold uppercase tracking-wide border border-slate-200">
                                            {{ $book->status ?? 'Kembali' }}
                                        </span>
                                    </div>
                                    @endforeach
                                @else
                                    <div class="p-16 text-center flex flex-col items-center justify-center">
                                        <i class="ph-duotone ph-book-bookmark text-4xl text-slate-200 mb-3"></i>
                                      <p class="text-slate-400 text-sm">Tidak ada riwayat peminjaman buku.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

              {{-- === TAB 4: AKADEMIK (NILAI RAPOR) === --}}
            <div x-show="activeTab === 'akademik'" x-cloak x-transition.duration.300ms>
                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden min-h-[400px]">
                    <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                        <div class="w-10 h-10 bg-rose-50 text-rose-600 rounded-xl flex items-center justify-center text-xl shrink-0">
                            <i class="ph-bold ph-file-text"></i>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-800">Transkrip Nilai Akademik</h3>
                            <p class="text-xs text-slate-500">Rekapitulasi nilai rapor dari Kelas VII hingga Kelas IX.</p>
                        </div>
                    </div>
                    
                    <div class="p-6 md:p-8">
                        @php
                            // Ambil daftar mata pelajaran dari database
                            $mapelInduk = \App\Models\Subject::orderBy('order')->get();
                        @endphp

                        @if(isset($mapelInduk) && $mapelInduk->count() > 0)
                            <div class="overflow-x-auto border border-slate-200 rounded-2xl custom-scrollbar">
                                <table class="w-full text-sm text-left whitespace-nowrap">
                                    <thead class="bg-slate-100 text-slate-600 uppercase text-[10px] font-black tracking-wider text-center">
                                        <tr>
                                            <th rowspan="2" class="px-4 py-3 border-r border-b border-slate-200 w-10">No</th>
                                            <th rowspan="2" class="px-4 py-3 border-r border-b border-slate-200 text-left min-w-[200px]">Mata Pelajaran</th>
                                            <th colspan="2" class="px-4 py-2 border-r border-b border-slate-200 bg-blue-50/50 text-blue-800">Kelas VII</th>
                                            <th colspan="2" class="px-4 py-2 border-r border-b border-slate-200 bg-emerald-50/50 text-emerald-800">Kelas VIII</th>
                                            <th colspan="2" class="px-4 py-2 border-b border-slate-200 bg-amber-50/50 text-amber-800">Kelas IX</th>
                                        </tr>
                                        <tr>
                                            <th class="px-3 py-2 border-r border-b border-slate-200 bg-blue-50/30">Smt 1</th>
                                            <th class="px-3 py-2 border-r border-b border-slate-200 bg-blue-50/30">Smt 2</th>
                                            <th class="px-3 py-2 border-r border-b border-slate-200 bg-emerald-50/30">Smt 1</th>
                                            <th class="px-3 py-2 border-r border-b border-slate-200 bg-emerald-50/30">Smt 2</th>
                                            <th class="px-3 py-2 border-r border-b border-slate-200 bg-amber-50/30">Smt 1</th>
                                            <th class="px-3 py-2 border-b border-slate-200 bg-amber-50/30">Smt 2</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 text-center">
                                        @php
                                            $no = 1;
                                            $totals = ['71' => 0, '72' => 0, '81' => 0, '82' => 0, '91' => 0, '92' => 0];
                                            $counts = ['71' => 0, '72' => 0, '81' => 0, '82' => 0, '91' => 0, '92' => 0];
                                        @endphp

                                        @foreach($mapelInduk as $mapel)
                                        @php
                                            $v71 = $student->getScore($mapel->name, 7, 1);
                                            $v72 = $student->getScore($mapel->name, 7, 2);
                                            $v81 = $student->getScore($mapel->name, 8, 1);
                                            $v82 = $student->getScore($mapel->name, 8, 2);
                                            $v91 = $student->getScore($mapel->name, 9, 1);
                                            $v92 = $student->getScore($mapel->name, 9, 2);

                                            if(is_numeric($v71)) { $totals['71'] += (float)$v71; $counts['71']++; }
                                            if(is_numeric($v72)) { $totals['72'] += (float)$v72; $counts['72']++; }
                                            if(is_numeric($v81)) { $totals['81'] += (float)$v81; $counts['81']++; }
                                            if(is_numeric($v82)) { $totals['82'] += (float)$v82; $counts['82']++; }
                                            if(is_numeric($v91)) { $totals['91'] += (float)$v91; $counts['91']++; }
                                            if(is_numeric($v92)) { $totals['92'] += (float)$v92; $counts['92']++; }
                                        @endphp
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="px-4 py-3 border-r border-slate-100 text-slate-500 font-medium">{{ $no++ }}</td>
                                            <td class="px-4 py-3 border-r border-slate-100 text-left font-bold text-slate-700 whitespace-normal">{{ $mapel->name }}</td>
                                            
                                            {{-- Kelas 7 --}}
                                            <td class="px-3 py-3 border-r border-slate-100 font-medium {{ is_numeric($v71) ? 'text-slate-800' : 'text-slate-300' }}">{{ $v71 }}</td>
                                            <td class="px-3 py-3 border-r border-slate-100 font-medium {{ is_numeric($v72) ? 'text-slate-800' : 'text-slate-300' }}">{{ $v72 }}</td>
                                            
                                            {{-- Kelas 8 --}}
                                            <td class="px-3 py-3 border-r border-slate-100 font-medium {{ is_numeric($v81) ? 'text-slate-800' : 'text-slate-300' }}">{{ $v81 }}</td>
                                            <td class="px-3 py-3 border-r border-slate-100 font-medium {{ is_numeric($v82) ? 'text-slate-800' : 'text-slate-300' }}">{{ $v82 }}</td>
                                            
                                            {{-- Kelas 9 --}}
                                            <td class="px-3 py-3 border-r border-slate-100 font-medium {{ is_numeric($v91) ? 'text-slate-800' : 'text-slate-300' }}">{{ $v91 }}</td>
                                            <td class="px-3 py-3 font-medium {{ is_numeric($v92) ? 'text-slate-800' : 'text-slate-300' }}">{{ $v92 }}</td>
                                        </tr>
                                        @endforeach
                                        
                                        <!-- BARIS RATA-RATA NILAI -->
                                        <tr class="bg-slate-100 font-black text-slate-700">
                                            <td colspan="2" class="px-4 py-3 border-r border-slate-200 text-right uppercase tracking-wider text-xs">Rata-rata Nilai</td>
                                            
                                            <td class="px-3 py-3 border-r border-slate-200 text-blue-700">{{ $counts['71'] > 0 ? round($totals['71'] / $counts['71'], 1) : '-' }}</td>
                                            <td class="px-3 py-3 border-r border-slate-200 text-blue-700">{{ $counts['72'] > 0 ? round($totals['72'] / $counts['72'], 1) : '-' }}</td>
                                            
                                            <td class="px-3 py-3 border-r border-slate-200 text-emerald-700">{{ $counts['81'] > 0 ? round($totals['81'] / $counts['81'], 1) : '-' }}</td>
                                            <td class="px-3 py-3 border-r border-slate-200 text-emerald-700">{{ $counts['82'] > 0 ? round($totals['82'] / $counts['82'], 1) : '-' }}</td>
                                            
                                            <td class="px-3 py-3 border-r border-slate-200 text-amber-700">{{ $counts['91'] > 0 ? round($totals['91'] / $counts['91'], 1) : '-' }}</td>
                                            <td class="px-3 py-3 text-amber-700">{{ $counts['92'] > 0 ? round($totals['92'] / $counts['92'], 1) : '-' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            {{-- Info Tambahan --}}
                            <div class="mt-4 flex items-start gap-2 bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                                <i class="ph-fill ph-info text-blue-500 text-lg shrink-0"></i>
                                <p class="text-xs text-blue-800 font-medium leading-relaxed">
                                    Ini adalah salinan digital transkrip nilai berdasarkan Buku Induk. Tanda strip (-) menunjukkan bahwa nilai pada semester tersebut belum/tidak diinput ke dalam sistem.
                                </p>
                            </div>

                        @else
                            <div class="p-16 text-center flex flex-col items-center justify-center border-2 border-dashed border-slate-100 rounded-3xl bg-slate-50/50">
                                <i class="ph-duotone ph-file-dashed text-5xl text-slate-300 mb-4"></i>
                                <h4 class="font-bold text-slate-700 mb-1">Data Mata Pelajaran Kosong</h4>
                                <p class="text-slate-500 text-sm max-w-sm mx-auto">Sistem tidak menemukan master data mata pelajaran untuk menampilkan matriks nilai.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection