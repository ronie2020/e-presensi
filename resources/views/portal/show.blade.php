@extends('layouts.public')

@section('content')
<!-- Container Utama dengan Alpine.js untuk Tab -->
<div class="w-full max-w-6xl mx-auto" x-data="{ activeTab: 'ringkasan' }">
    
    <!-- 1. HEADER PROFIL (THEMA: DARK BLUE) -->
    <div class="bg-white rounded-[2rem] shadow-xl overflow-hidden mb-6 border border-gray-100 relative group">
        
        <!-- Background Banner (Biru Tua) -->
        <div class="absolute top-0 left-0 w-full h-44 z-0 overflow-hidden bg-slate-900">
            <!-- Gambar Background Tipis -->
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80')] bg-cover bg-center opacity-20 mix-blend-overlay"></div>
            
            <!-- Gradasi Biru Tua -->
            <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-blue-900/80 to-slate-900"></div>
            
            <!-- Pattern Kubus Halus -->
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
            
            <!-- Dekorasi Cahaya (Glow Effect) -->
            <div class="absolute top-0 right-0 w-[300px] h-[300px] bg-blue-500 rounded-full mix-blend-screen filter blur-[80px] opacity-20 -mr-20 -mt-20"></div>
            <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-indigo-500 rounded-full mix-blend-screen filter blur-[80px] opacity-20 -ml-20 -mb-20"></div>
        </div>
        
        <!-- Konten Header -->
        <div class="relative z-10 px-6 sm:px-10 pt-28 pb-8 flex flex-col md:flex-row items-center md:items-center text-center md:text-left">
            
            <!-- Foto Siswa -->
            <div class="relative group shrink-0">
                <div class="w-36 h-36 rounded-full bg-white p-1.5 shadow-2xl relative z-10 transform group-hover:scale-105 transition-transform duration-300">
                    <div class="w-full h-full rounded-full bg-gray-100 flex items-center justify-center overflow-hidden border-4 border-blue-50 relative">
                        @if($student->photo_path)
                            <img src="{{ asset('storage/' . $student->photo_path) }}" alt="{{ $student->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-blue-50 to-indigo-50 flex items-center justify-center text-5xl font-black text-blue-300 select-none">
                                {{ substr($student->name, 0, 1) }}
                            </div>
                        @endif
                    </div>
                </div>
                <!-- Status Badge -->
                <div class="absolute bottom-2 right-2 z-20 bg-emerald-500 text-white text-[10px] font-bold px-2 py-1 rounded-full border-2 border-white shadow-sm flex items-center gap-1">
                    <div class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></div> SISWA AKTIF
                </div>
            </div>
            
            <!-- Info Siswa -->
            <div class="md:ml-8 mt-4 md:mt-0 flex-1 min-w-0 pt-2">
                <!-- NAMA SISWA (Capitalize & Warna Gelap) -->
                <h1 class="text-3xl sm:text-4xl font-black text-slate-800 tracking-tight leading-tight mb-3 break-words capitalize drop-shadow-sm">
                    {{ strtolower($student->name) }}
                </h1>
                
                <div class="flex flex-wrap justify-center md:justify-start gap-3 text-sm font-medium">
                    <span class="flex items-center bg-blue-50 px-4 py-1.5 rounded-full text-blue-700 border border-blue-100 transition hover:bg-blue-100">
                        <i class="ph-fill ph-chalkboard-teacher mr-2 text-lg"></i>
                        Kelas {{ $student->schoolClass->name ?? 'Unassigned' }}
                    </span>
                    <span class="flex items-center bg-slate-50 px-4 py-1.5 rounded-full text-slate-600 border border-slate-200 font-mono transition hover:bg-gray-100">
                        <i class="ph-fill ph-identification-card mr-2 text-lg text-slate-400"></i>
                        {{ $student->student_id }}
                    </span>
                </div>
            </div>

            <!-- Tombol Cari Lain -->
            <div class="md:ml-auto mt-6 md:mt-0 shrink-0">
                <a href="{{ route('portal.index') }}" class="inline-flex items-center px-6 py-3 bg-white border-2 border-gray-100 rounded-2xl text-sm font-bold text-gray-600 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all shadow-sm group">
                    <i class="ph-bold ph-magnifying-glass mr-2 group-hover:scale-110 transition-transform"></i>
                    Cari Siswa Lain
                </a>
            </div>
        </div>
    </div>

    <!-- 2. NAVIGATION TABS -->
    <div class="mb-8 sticky top-24 z-40">
        <div class="bg-white/80 backdrop-blur-md p-1.5 rounded-2xl shadow-lg border border-gray-100 overflow-x-auto custom-scrollbar">
            <div class="flex space-x-1 min-w-max">
                <!-- Tab: Ringkasan -->
                <button @click="activeTab = 'ringkasan'" 
                    :class="activeTab === 'ringkasan' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-500 hover:bg-gray-50'"
                    class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 flex items-center gap-2">
                    <i class="ph-bold ph-squares-four text-lg"></i> Ringkasan
                </button>

                <!-- Tab: Kehadiran -->
                <button @click="activeTab = 'kehadiran'" 
                    :class="activeTab === 'kehadiran' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-500 hover:bg-gray-50'"
                    class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 flex items-center gap-2">
                    <i class="ph-bold ph-calendar-check text-lg"></i> Kehadiran
                </button>

                <!-- Tab: Keagamaan -->
                <button @click="activeTab = 'keagamaan'" 
                    :class="activeTab === 'keagamaan' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-500 hover:bg-gray-50'"
                    class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 flex items-center gap-2">
                    <i class="ph-bold ph-book-open-text text-lg"></i> Keagamaan
                </button>

                <!-- Tab: Disiplin -->
                <button @click="activeTab = 'disiplin'" 
                    :class="activeTab === 'disiplin' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-500 hover:bg-gray-50'"
                    class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 flex items-center gap-2">
                    <i class="ph-bold ph-warning-circle text-lg"></i> Disiplin
                </button>

                <!-- Tab: Prestasi -->
                <button @click="activeTab = 'prestasi'" 
                    :class="activeTab === 'prestasi' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-500 hover:bg-gray-50'"
                    class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 flex items-center gap-2">
                    <i class="ph-bold ph-trophy text-lg"></i> Prestasi
                </button>

                <!-- Tab: Perpustakaan -->
                <button @click="activeTab = 'perpustakaan'" 
                    :class="activeTab === 'perpustakaan' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-500 hover:bg-gray-50'"
                    class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-200 flex items-center gap-2">
                    <i class="ph-bold ph-books text-lg"></i> Pustaka
                </button>
            </div>
        </div>
    </div>

    <!-- 3. TAB CONTENT AREAS -->
    <div class="min-h-[400px]">
        
        <!-- KONTEN TAB: RINGKASAN -->
        <div x-show="activeTab === 'ringkasan'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Card Kehadiran -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-lg transition">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition">
                        <i class="ph-fill ph-chart-pie-slice text-9xl text-blue-500"></i>
                    </div>
                    <h3 class="text-gray-500 text-sm font-bold uppercase tracking-wider mb-2">Persentase Kehadiran</h3>
                    <div class="flex items-baseline gap-2">
                        @php $total_hari = $hadir + $sakit + $izin + $alpa; $persen = $total_hari > 0 ? round(($hadir/$total_hari)*100) : 0; @endphp
                        <span class="text-5xl font-black text-gray-800">{{ $persen }}<span class="text-2xl text-gray-400">%</span></span>
                    </div>
                    <div class="mt-4 flex gap-2">
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold">Hadir: {{ $hadir }}</span>
                        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-lg text-xs font-bold">Alpa: {{ $alpa }}</span>
                    </div>
                </div>

                <!-- Card Poin Karakter -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-lg transition">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition">
                        <i class="ph-fill ph-star text-9xl text-yellow-500"></i>
                    </div>
                    <h3 class="text-gray-500 text-sm font-bold uppercase tracking-wider mb-2">Poin Karakter</h3>
                    <div class="grid grid-cols-2 gap-4 mt-2">
                        <div>
                            <p class="text-xs text-green-600 font-bold mb-1">Kebaikan</p>
                            <p class="text-3xl font-black text-green-600">+{{ $poin_kebaikan ?? 0 }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-red-600 font-bold mb-1">Pelanggaran</p>
                            <p class="text-3xl font-black text-red-600">{{ $poin_pelanggaran ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <!-- Card Perpustakaan -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-lg transition">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition">
                        <i class="ph-fill ph-books text-9xl text-orange-500"></i>
                    </div>
                    <h3 class="text-gray-500 text-sm font-bold uppercase tracking-wider mb-2">Literasi</h3>
                    <div class="flex items-baseline gap-2">
                        <span class="text-5xl font-black text-gray-800">{{ $library_visits }}</span>
                        <span class="text-sm text-gray-400 font-medium">Kunjungan</span>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">Rajin membaca pangkal pandai!</p>
                </div>
            </div>
        </div>

        <!-- KONTEN TAB: KEHADIRAN -->
        <div x-show="activeTab === 'kehadiran'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                 <!-- Statistik Cards -->
                <div class="bg-emerald-50 p-4 rounded-2xl border border-emerald-100 text-center">
                    <div class="text-3xl font-black text-emerald-600">{{ $hadir }}</div>
                    <div class="text-xs font-bold text-emerald-600 uppercase tracking-wide">Hadir</div>
                </div>
                <div class="bg-blue-50 p-4 rounded-2xl border border-blue-100 text-center">
                    <div class="text-3xl font-black text-blue-600">{{ $sakit }}</div>
                    <div class="text-xs font-bold text-blue-600 uppercase tracking-wide">Sakit</div>
                </div>
                <div class="bg-amber-50 p-4 rounded-2xl border border-amber-100 text-center">
                    <div class="text-3xl font-black text-amber-600">{{ $izin }}</div>
                    <div class="text-xs font-bold text-amber-600 uppercase tracking-wide">Izin</div>
                </div>
                <div class="bg-rose-50 p-4 rounded-2xl border border-rose-100 text-center">
                    <div class="text-3xl font-black text-rose-600">{{ $alpa }}</div>
                    <div class="text-xs font-bold text-rose-600 uppercase tracking-wide">Alpa</div>
                </div>
            </div>

            <!-- List Riwayat Terakhir -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                    <h3 class="font-bold text-gray-800">Riwayat Kehadiran Terakhir</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($attendance_history as $log)
                    <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50 transition">
                        <div>
                            <p class="font-bold text-gray-800">{{ \Carbon\Carbon::parse($log->attendance_date)->translatedFormat('l, d F Y') }}</p>
                            <p class="text-xs text-gray-500">
                                Masuk: {{ $log->time_in ? \Carbon\Carbon::parse($log->time_in)->format('H:i') : '-' }} | 
                                Pulang: {{ $log->time_out ? \Carbon\Carbon::parse($log->time_out)->format('H:i') : '-' }}
                            </p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase 
                            {{ ($log->status == 'Hadir' || $log->status == 'Masuk' || $log->status == 'Terlambat') ? 'bg-green-100 text-green-700' : 
                              ($log->status == 'Sakit' ? 'bg-blue-100 text-blue-700' : 
                              ($log->status == 'Izin' ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700')) }}">
                            {{ $log->status }}
                        </span>
                    </div>
                    @empty
                    <div class="p-8 text-center text-gray-400">Belum ada data kehadiran.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- KONTEN TAB: KEAGAMAAN -->
        <div x-show="activeTab === 'keagamaan'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
             <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Card Dhuha -->
                <div class="bg-white p-6 rounded-3xl border border-teal-100 shadow-sm flex items-center gap-6">
                    <div class="w-16 h-16 rounded-2xl bg-teal-100 flex items-center justify-center text-teal-600">
                        <i class="ph-duotone ph-sun-horizon text-3xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-500 uppercase tracking-widest">Sholat Dhuha</h4>
                        <p class="text-4xl font-black text-gray-800">{{ $sholat_dhuha }} <span class="text-base font-medium text-gray-400">Kali</span></p>
                    </div>
                </div>
                <!-- Card Dhuhur -->
                <div class="bg-white p-6 rounded-3xl border border-orange-100 shadow-sm flex items-center gap-6">
                    <div class="w-16 h-16 rounded-2xl bg-orange-100 flex items-center justify-center text-orange-600">
                        <i class="ph-duotone ph-clock-afternoon text-3xl"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-bold text-gray-500 uppercase tracking-widest">Sholat Dhuhur</h4>
                        <p class="text-4xl font-black text-gray-800">{{ $sholat_dhuhur }} <span class="text-base font-medium text-gray-400">Kali</span></p>
                    </div>
                </div>
             </div>

             <!-- Timeline Ibadah -->
             <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-800 mb-4">Aktivitas Ibadah Terakhir</h3>
                <div class="space-y-4">
                    @forelse($religious_history as $rel)
                    <div class="flex gap-4">
                        <div class="flex flex-col items-center">
                            <div class="w-2 h-2 bg-gray-200 rounded-full"></div>
                            <div class="w-0.5 flex-1 bg-gray-100 my-1"></div>
                        </div>
                        <div class="pb-4">
                            <p class="text-sm font-bold text-gray-800">Melaksanakan Sholat {{ $rel->activity }}</p>
                            <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($rel->created_at)->translatedFormat('l, d F Y H:i') }} WIB</p>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-400 text-sm text-center">Belum ada data ibadah.</p>
                    @endforelse
                </div>
             </div>
        </div>

        <!-- KONTEN TAB: DISIPLIN -->
        <div x-show="activeTab === 'disiplin'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
             <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 mb-6">
                 <div class="grid grid-cols-2 gap-4">
                    <div class="p-6 bg-rose-50 rounded-2xl text-center border border-rose-100">
                        <i class="ph-duotone ph-warning-circle text-3xl text-rose-500 mb-2"></i>
                        <p class="text-xs font-bold text-rose-500 uppercase tracking-widest mb-1">Pelanggaran</p>
                        <p class="text-5xl font-black text-rose-600">{{ $poin_pelanggaran ?? 0 }}</p>
                        <p class="text-[10px] text-gray-400 mt-1">Poin Terakumulasi</p>
                    </div>
                    <div class="p-6 bg-emerald-50 rounded-2xl text-center border border-emerald-100">
                        <i class="ph-duotone ph-medal text-3xl text-emerald-500 mb-2"></i>
                        <p class="text-xs font-bold text-emerald-500 uppercase tracking-widest mb-1">Kebaikan</p>
                        <p class="text-5xl font-black text-emerald-600">{{ $poin_kebaikan ?? 0 }}</p>
                        <p class="text-[10px] text-gray-400 mt-1">Poin Terakumulasi</p>
                    </div>
                 </div>
             </div>

             <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800">Catatan Disiplin Terbaru</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse ($discipline_history as $record)
                        <div class="flex items-start gap-4 p-5 hover:bg-gray-50 transition">
                            <div class="mt-1">
                                @if($record->disciplineType && $record->disciplineType->type == 'Kebaikan')
                                    <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600"><i class="ph-fill ph-thumbs-up text-lg"></i></div>
                                @else
                                    <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center text-rose-600"><i class="ph-fill ph-warning text-lg"></i></div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-start">
                                    <h4 class="font-bold text-gray-800">{{ $record->disciplineType->name ?? 'Data Dihapus' }}</h4>
                                    @if($record->disciplineType)
                                    <span class="text-xs font-black px-2 py-1 rounded {{ $record->disciplineType->type == 'Kebaikan' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                        {{ $record->disciplineType->type == 'Kebaikan' ? '+' : '-' }}{{ $record->disciplineType->point_value }}
                                    </span>
                                    @endif
                                </div>
                                <p class="text-sm text-gray-500 mt-1">{{ $record->description ?? 'Tidak ada keterangan tambahan.' }}</p>
                                <p class="text-xs text-gray-400 mt-2 flex items-center gap-1">
                                    <i class="ph-regular ph-calendar"></i> {{ \Carbon\Carbon::parse($record->date)->translatedFormat('d M Y') }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-sm text-gray-400 py-8">Belum ada catatan disiplin.</div>
                    @endforelse
                </div>
             </div>
        </div>

        <!-- KONTEN TAB: PRESTASI -->
        <div x-show="activeTab === 'prestasi'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
             @if(count($achievements) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($achievements as $ach)
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 flex items-start gap-4 relative overflow-hidden group hover:shadow-lg transition">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-yellow-400 opacity-10 rounded-full blur-2xl -mr-6 -mt-6"></div>
                        
                        <div class="w-16 h-16 rounded-2xl bg-yellow-50 border border-yellow-100 flex items-center justify-center flex-shrink-0">
                            <i class="ph-duotone ph-trophy text-3xl text-yellow-600"></i>
                        </div>
                        <div>
                            <span class="inline-block px-2 py-1 bg-yellow-100 text-yellow-700 text-[10px] font-bold uppercase rounded-md mb-2">
                                {{ $ach->level ?? 'Sekolah' }}
                            </span>
                            <h3 class="font-bold text-gray-800 text-lg leading-tight">{{ $ach->name }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ $ach->description ?? 'Juara prestasi membanggakan.' }}</p>
                            <p class="text-xs text-gray-400 mt-3 font-mono">{{ \Carbon\Carbon::parse($ach->date)->translatedFormat('d F Y') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
             @else
                <div class="bg-white rounded-3xl border border-dashed border-gray-300 p-12 text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="ph-duotone ph-trophy text-4xl text-gray-300"></i>
                    </div>
                    <h3 class="font-bold text-gray-800">Belum Ada Data Prestasi</h3>
                    <p class="text-gray-500 text-sm mt-1">Teruslah berusaha dan ukir prestasimu!</p>
                </div>
             @endif
        </div>

        <!-- KONTEN TAB: PERPUSTAKAAN -->
        <div x-show="activeTab === 'perpustakaan'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
             <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden h-full">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <p class="text-3xl font-black text-gray-800">{{ $library_visits }}</p>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total Kunjungan</p>
                    </div>
                    <div class="p-3 bg-orange-50 rounded-2xl text-orange-500">
                        <i class="ph-duotone ph-read-cv-logo text-4xl"></i>
                    </div>
                </div>

                <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3 ml-1">Buku Dipinjam Terakhir</h4>
                <div class="space-y-3">
                    @forelse($borrowing_history as $loan)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-gray-50 border border-gray-100 hover:bg-white hover:shadow-md transition">
                            <div class="flex items-center gap-3 overflow-hidden">
                                <div class="flex-shrink-0 w-10 h-14 bg-gray-200 rounded overflow-hidden shadow-sm">
                                     @if($loan->book->cover_path)
                                        <img src="{{ asset('storage/' . $loan->book->cover_path) }}" class="w-full h-full object-cover">
                                     @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-400"><i class="ph-fill ph-book"></i></div>
                                     @endif
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-gray-800 truncate">{{ $loan->book->title }}</p>
                                    <p class="text-[10px] text-gray-500">Pinjam: {{ \Carbon\Carbon::parse($loan->borrow_date)->format('d M Y') }}</p>
                                </div>
                            </div>
                            
                            <span class="px-2 py-1 rounded text-[10px] font-bold uppercase {{ $loan->status == 'returned' ? 'bg-blue-100 text-blue-600' : 'bg-orange-100 text-orange-600' }}">
                                {{ $loan->status == 'returned' ? 'Kembali' : 'Dipinjam' }}
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-400">
                            <i class="ph-duotone ph-book-open text-3xl mb-2"></i>
                            <p class="text-sm">Belum pernah meminjam buku.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    /* Utility agar scrollbar di tab menu terlihat rapi/hidden */
    .custom-scrollbar::-webkit-scrollbar { height: 0px; background: transparent; }
    [x-cloak] { display: none !important; }
</style>
@endsection