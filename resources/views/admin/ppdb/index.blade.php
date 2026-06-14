<x-app-layout>
    {{-- CUSTOM STYLES & MICROSOFT FLUENT ELEVATION --}}
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .count-up { font-variant-numeric: tabular-nums; }
        @keyframes wiggle { 0%, 100% { transform: rotate(0deg); } 25% { transform: rotate(-10deg); } 75% { transform: rotate(10deg); } }
        .group:hover .animate-wiggle { animation: wiggle 0.5s ease-in-out; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        
        /* Microsoft Fluent Elevation Shadows */
        .fluent-card {
            box-shadow: 0 1.6px 3.6px 0 rgba(0, 0, 0, 0.132), 0 0.3px 0.9px 0 rgba(0, 0, 0, 0.108);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        .fluent-card:hover {
            box-shadow: 0 6.4px 14.4px 0 rgba(0, 0, 0, 0.132), 0 1.2px 3.6px 0 rgba(0, 0, 0, 0.108);
            transform: translateY(-2px);
        }
        .fluent-modal {
            box-shadow: 0 25.6px 57.6px 0 rgba(0, 0, 0, 0.22), 0 4.8px 14.4px 0 rgba(0, 0, 0, 0.18);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
    </style>

    {{-- LOGIC JADWAL --}}
    @php
        $announcementTime = isset($scheduleData['announcement_date']) ? \Carbon\Carbon::parse($scheduleData['announcement_date']) : null;
        $isSet = $announcementTime != null;
        $isPast = $isSet && \Carbon\Carbon::now()->greaterThanOrEqualTo($announcementTime);
    @endphp

    <div class="relative space-y-6 md:space-y-8 min-h-screen pb-10 font-sans text-elevate-dark bg-elevate-surface">
        
        {{-- HERO SECTION (Tema Elevate) --}}
        <div class="animate-enter relative rounded-[2rem] bg-elevate-gradient-main p-6 md:p-10 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden group border border-white/60">
            
            {{-- Background Pattern & Blobs --}}
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.03] pointer-events-none mix-blend-overlay"></div>
            <div class="absolute top-0 left-0 w-[400px] h-[400px] bg-white/30 rounded-full blur-[100px] group-hover:opacity-70 transition-opacity duration-1000 pointer-events-none -ml-20 -mt-20"></div>
            <div class="absolute bottom-0 right-0 w-[300px] h-[300px] bg-white/20 rounded-full blur-[120px] pointer-events-none"></div>

            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                
                {{-- KIRI: JUDUL & INTRO --}}
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/40 border border-white/50 text-elevate-dark text-xs font-bold uppercase tracking-wider mb-4 backdrop-blur-sm shadow-sm">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-elevate-peach-dark opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-elevate-peach-dark"></span>
                        </span>
                        Portal Penerimaan Siswa Baru
                    </div>
                    
                    <h1 class="text-3xl md:text-5xl font-extrabold text-elevate-dark tracking-tight mb-4 leading-tight">
                        Manajemen <br>
                        <span class="text-elevate-dark">PPDB Online</span> 
                    </h1>
                    <p class="text-elevate-dark/80 text-sm md:text-base max-w-xl leading-relaxed mb-8 font-medium">
                        Kelola data calon siswa, verifikasi berkas, dan atur jadwal pengumuman hasil seleksi secara terpusat.
                    </p>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-elevate-dark hover:bg-elevate-primary text-white text-sm font-bold rounded-xl transition-all shadow-md shadow-elevate-dark/20 flex items-center gap-2">
                            <i class="ph-bold ph-squares-four"></i> Dashboard Utama
                        </a>
                    </div>
                </div>

                {{-- KANAN: WIDGET JADWAL (GLASS LIGHT) --}}
                <div class="bg-white/40 backdrop-blur-md p-6 sm:p-8 rounded-[1.5rem] border border-white/50 shadow-sm relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 p-4 opacity-10 text-elevate-primary pointer-events-none transform rotate-12">
                        <i class="ph-fill ph-calendar-check text-[10rem]"></i>
                    </div>

                    <h3 class="text-xl font-black text-elevate-dark mb-2 flex items-center gap-2 relative z-10">
                        <i class="ph-duotone ph-clock text-elevate-primary"></i> Jadwal Pengumuman
                    </h3>
                    <p class="text-elevate-dark/80 text-sm font-medium mb-6 relative z-10">Atur kapan hasil seleksi dapat dilihat publik.</p>
                    
                    {{-- Status Indikator --}}
                    <div class="mb-6 p-4 rounded-xl bg-white/60 border border-white/60 relative z-10 shadow-sm">
                        @if($isSet)
                            <div class="flex items-center gap-3 mb-2">
                                <span class="text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-md border {{ $isPast ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-elevate-soft text-elevate-primary border-elevate-accent/30' }}">
                                    {{ $isPast ? '● Sudah Dibuka' : '● Terjadwal' }}
                                </span>
                            </div>
                            <p class="text-elevate-dark text-lg font-bold tracking-wide font-mono">
                                {{ $announcementTime->translatedFormat('d M Y, H:i') }} WIB
                            </p>
                        @else
                            <div class="flex items-center gap-3 mb-2">
                                <span class="text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-md border bg-amber-100 text-amber-700 border-amber-200">● Belum Diatur</span>
                            </div>
                            <p class="text-elevate-dark/70 text-sm">Siswa belum dapat melihat hasil.</p>
                        @endif
                    </div>

                    {{-- Form --}}
                    <form action="{{ route('admin.ppdb.set_schedule') }}" method="POST" class="space-y-2 relative z-10">
                        @csrf
                        <div class="flex flex-col sm:flex-row gap-3">
                            <input type="datetime-local" name="announcement_date" required 
                                   value="{{ $isSet ? $announcementTime->format('Y-m-d\TH:i') : '' }}"
                                   class="block w-full px-4 py-3 rounded-xl border-white/60 bg-white/70 focus:bg-white text-elevate-dark text-sm font-bold shadow-sm focus:ring-elevate-accent/30 focus:border-elevate-accent transition-all cursor-pointer">
                            <button type="submit" class="px-6 py-3 bg-elevate-primary hover:bg-elevate-dark text-white font-bold rounded-xl shadow-md transition-all flex items-center justify-center gap-2 whitespace-nowrap" title="Simpan">
                                <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- STATS CARDS --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 animate-enter" style="animation-delay: 100ms">
            <!-- Total Pendaftar -->
            <div class="group bg-white rounded-2xl p-5 fluent-card relative overflow-hidden flex items-center gap-5 hover:border-elevate-primary">
                <div class="w-14 h-14 rounded-xl flex items-center justify-center shadow-sm border transition-all duration-300 bg-elevate-soft text-elevate-primary border-elevate-accent/20 group-hover:bg-elevate-primary group-hover:text-white group-hover:scale-110">
                    <i class="ph-duotone ph-users-three text-3xl animate-wiggle"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1 group-hover:text-elevate-primary transition-colors">Total Pendaftar</p>
                    <h3 class="text-3xl font-black text-elevate-dark tracking-tight count-up" data-target="{{ $stats['total'] }}">0</h3>
                </div>
            </div>

            <!-- Diterima -->
            <div class="group bg-white rounded-2xl p-5 fluent-card relative overflow-hidden flex items-center gap-5 hover:border-emerald-600">
                <div class="w-14 h-14 rounded-xl flex items-center justify-center shadow-sm border transition-all duration-300 bg-emerald-50 text-emerald-600 border-emerald-200 group-hover:bg-emerald-600 group-hover:text-white group-hover:scale-110">
                    <i class="ph-duotone ph-medal text-3xl animate-wiggle"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1 group-hover:text-emerald-600 transition-colors">Lulus Seleksi</p>
                    <h3 class="text-3xl font-black text-elevate-dark tracking-tight count-up" data-target="{{ $stats['accepted'] }}">0</h3>
                </div>
            </div>

            <!-- Pending -->
            <div class="group bg-white rounded-2xl p-5 fluent-card relative overflow-hidden flex items-center gap-5 hover:border-amber-500">
                <div class="w-14 h-14 rounded-xl flex items-center justify-center shadow-sm border transition-all duration-300 bg-amber-50 text-amber-600 border-amber-200 group-hover:bg-amber-500 group-hover:text-white group-hover:scale-110">
                    <i class="ph-duotone ph-clock-countdown text-3xl animate-wiggle"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1 group-hover:text-amber-600 transition-colors">Perlu Verifikasi</p>
                    <h3 class="text-3xl font-black text-elevate-dark tracking-tight count-up" data-target="{{ $stats['pending'] }}">0</h3>
                </div>
            </div>
        </div>

        {{-- MAIN CONTENT: TABEL DATA --}}
        <div class="animate-enter bg-white rounded-2xl fluent-card overflow-hidden flex flex-col min-h-[600px]" style="animation-delay: 200ms">
            
            {{-- Toolbar --}}
            <div class="p-5 md:p-6 border-b border-slate-100 flex flex-col xl:flex-row xl:items-center justify-between gap-5">
                
                {{-- Tabs Filter --}}
                <div class="bg-slate-50 p-1.5 rounded-xl flex flex-wrap gap-1 w-full md:w-auto overflow-x-auto border border-slate-200 no-scrollbar">
                    @php
                        $tabClass = "px-4 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-2 whitespace-nowrap";
                        $activeClass = "bg-white text-elevate-primary shadow-sm border border-slate-200";
                        $inactiveClass = "text-slate-500 hover:text-elevate-dark hover:bg-slate-100";
                    @endphp

                    <a href="{{ route('admin.ppdb.index') }}" class="{{ $tabClass }} {{ !request('status') ? $activeClass : $inactiveClass }}">
                       <i class="ph-bold ph-squares-four"></i> Semua
                    </a>
                    <a href="{{ route('admin.ppdb.index', ['status' => 'pending']) }}" class="{{ $tabClass }} {{ request('status') == 'pending' ? 'bg-amber-500 text-white shadow-sm' : $inactiveClass }}">
                       <i class="ph-bold ph-clock"></i> Pending
                    </a>
                    <a href="{{ route('admin.ppdb.index', ['status' => 'verified']) }}" class="{{ $tabClass }} {{ request('status') == 'verified' ? 'bg-elevate-primary text-white shadow-sm' : $inactiveClass }}">
                       <i class="ph-bold ph-check-circle"></i> Verified
                    </a>
                    <a href="{{ route('admin.ppdb.index', ['status' => 'accepted']) }}" class="{{ $tabClass }} {{ request('status') == 'accepted' ? 'bg-emerald-600 text-white shadow-sm' : $inactiveClass }}">
                       <i class="ph-bold ph-medal"></i> Diterima
                    </a>
                </div>
                
                
                {{-- Search & Bulk Actions --}}
                <div class="flex flex-col md:flex-row gap-3 w-full xl:w-auto">
                    
                    {{-- [TAMBAHAN] Tombol Cetak Seluruh/Filter Data Siswa --}}
                    <a href="{{ route('admin.ppdb.print.recap', request()->query()) }}" target="_blank" class="px-5 py-2.5 bg-white border border-slate-200 text-elevate-dark rounded-xl font-bold text-xs hover:bg-slate-50 hover:border-elevate-accent/30 hover:text-elevate-primary transition flex items-center justify-center gap-2 shadow-sm">
                        <i class="ph-bold ph-printer text-base"></i> Cetak Data
                    </a>

                    {{-- PERBAIKAN: Tombol Promote HANYA MUNCUL JIKA filter status = accepted --}}
                    @if(request('status') == 'accepted')
                        <button type="button" onclick="submitBulk()" class="px-5 py-2.5 bg-emerald-600 text-white rounded-xl font-bold text-xs hover:bg-emerald-700 transition flex items-center justify-center gap-2 shadow-sm border border-emerald-700">
                            <i class="ph-bold ph-user-plus text-base"></i> Promote Terpilih
                        </button>
                        
                        {{-- TAMBAHAN: Tombol Bagi Kelas & Generate NIS Otomatis --}}
                        <form action="{{ route('admin.ppdb.auto_distribute') }}" method="POST" id="autoDistributeForm" class="m-0 p-0">
                            @csrf
                            <button type="button" onclick="confirmAutoDistribute()" class="px-5 py-2.5 bg-indigo-600 text-white rounded-xl font-bold text-xs hover:bg-indigo-700 transition flex items-center justify-center gap-2 shadow-sm border border-indigo-700 w-full md:w-auto">
                                <i class="ph-bold ph-magic-wand text-base"></i> Bagi Kelas Otomatis
                            </button>
                        </form>
                    @endif

                    <form method="GET" class="relative group w-full md:w-64">
                        <input type="hidden" name="status" value="{{ request('status') }}">
                        <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-elevate-primary transition-colors"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Siswa..." 
                               class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 text-sm font-bold text-elevate-dark transition-all shadow-sm placeholder:font-medium">
                    </form>
                </div>
            </div>

            {{-- Table Wrapper --}}
            <div class="overflow-x-auto flex-1">
                <form action="{{ route('admin.ppdb.bulk_promote') }}" method="POST" id="bulkForm">
                    @csrf
                    <table class="w-full text-sm text-left text-elevate-dark whitespace-nowrap md:whitespace-normal">
                        <thead class="bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wider sticky top-0 z-10 border-b border-slate-100">
                            <tr>
                                <th class="px-5 py-4 w-10 text-center">
                                    {{-- PERBAIKAN: Check All hanya aktif jika di tab Diterima --}}
                                    @if(request('status') == 'accepted')
                                        <input type="checkbox" id="checkAll" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-600 w-4 h-4 cursor-pointer bg-white">
                                    @else
                                        <i class="ph-bold ph-minus text-slate-300"></i>
                                    @endif
                                </th>
                                <th class="px-5 py-4">Data Siswa</th>
                                {{-- PERBAIKAN: Disembunyikan di HP agar tabel tidak terlalu lebar --}}
                                <th class="px-5 py-4 text-center hidden sm:table-cell">Jalur & Nilai</th>
                                <th class="px-5 py-4 text-center">Status</th>
                                <th class="px-5 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($registrants as $item)
                            
                            {{-- LOGIKA PENGECEKAN KE TABEL STUDENTS (Apakah sudah digenerate?) --}}
                            @php
                                $studentData = \App\Models\Student::with('schoolClass')->where('nisn', $item->nisn)->first();
                                $isPromoted = $studentData ? true : false;
                            @endphp

                            <tr class="hover:bg-slate-50/50 transition-colors group {{ $isPromoted ? 'bg-indigo-50/10' : '' }}">
                                <td class="px-5 py-4 text-center">
                                    @if($item->status == 'accepted' && !$isPromoted)
                                        <input type="checkbox" name="selected_ids[]" value="{{ $item->id }}" class="check-item rounded border-slate-300 text-emerald-600 focus:ring-emerald-600 w-4 h-4 cursor-pointer">
                                    @elseif($isPromoted)
                                        <i class="ph-fill ph-check-circle text-indigo-500 text-xl" title="Sudah Dipetakan"></i>
                                    @else
                                        <i class="ph-bold ph-minus text-slate-200"></i>
                                    @endif
                                </td>
                                
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 shrink-0 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-500 font-black text-sm group-hover:scale-110 group-hover:border-elevate-primary group-hover:text-elevate-primary transition-all shadow-sm">
                                            {{ substr($item->full_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-elevate-dark mb-0.5 group-hover:text-elevate-primary transition-colors">{{ $item->full_name }}</div>
                                            <div class="text-[11px] text-slate-400 font-mono flex items-center gap-1.5 mb-1">
                                                <i class="ph-bold ph-identification-card"></i> {{ $item->registration_number }}
                                            </div>
                                            
                                            {{-- BADGE INFO NIS & KELAS MUNCUL JIKA SUDAH TERPETAKAN --}}
                                            @if($isPromoted)
                                                <div class="inline-flex items-center gap-1.5 px-2 py-0.5 mt-0.5 rounded text-[10px] font-bold bg-indigo-100 text-indigo-700 border border-indigo-200 shadow-sm">
                                                    <i class="ph-fill ph-student"></i> NIS: {{ $studentData->nis }} | Kelas: {{ $studentData->schoolClass ? $studentData->schoolClass->name : 'Belum Ada' }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                {{-- PERBAIKAN: Disembunyikan di HP --}}
                                <td class="px-5 py-4 text-center hidden sm:table-cell">
                                    <div class="flex flex-col items-center gap-1.5">
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider border 
                                            {{ $item->track == 'prestasi' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : 'bg-elevate-soft text-elevate-primary border-elevate-accent/30' }}">
                                            {{ $item->track }}
                                        </span>
                                        <span class="text-xs font-bold text-slate-500 bg-white border border-slate-200 px-2 py-0.5 rounded shadow-sm">{{ $item->average_grade }}</span>
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-center">
                                    <div class="flex flex-col items-center gap-1.5">
                                        @if($item->status == 'pending')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending
                                            </span>
                                        @elseif($item->status == 'accepted')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                                <i class="ph-fill ph-check-circle"></i> Diterima
                                            </span>
                                        @elseif($item->status == 'verified')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-elevate-soft text-elevate-primary border border-elevate-accent/30">
                                                <i class="ph-fill ph-shield-check"></i> Terverifikasi
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-rose-50 text-rose-600 border border-rose-200">
                                                <i class="ph-fill ph-x-circle"></i> Ditolak
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('admin.ppdb.show', $item->id) }}" class="p-2 rounded-lg text-slate-400 hover:text-elevate-primary hover:bg-elevate-soft hover:border-elevate-accent/30 border border-transparent transition-all" title="Detail">
                                            <i class="ph-bold ph-eye text-lg"></i>
                                        </a>
                                        <button type="button" onclick="confirmDelete('{{ $item->id }}', '{{ $item->full_name }}')" class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 hover:border-rose-200 border border-transparent transition-all" title="Hapus">
                                            <i class="ph-bold ph-trash text-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-16 text-center">
                                    {{-- PERBAIKAN: Empty state diperjelas --}}
                                    <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-slate-200 text-slate-400 shadow-sm">
                                        <i class="ph-duotone ph-folder-notch-open text-3xl"></i>
                                    </div>
                                    <p class="text-sm font-bold text-elevate-dark">Belum ada data pendaftar ditemukan.</p>
                                    <p class="text-xs text-slate-400 mt-1">Cobalah mengubah filter status atau kata kunci pencarian Anda.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </form>

                @foreach($registrants as $item)
                    <form action="{{ route('admin.ppdb.destroy', $item->id) }}" method="POST" id="delete-form-{{ $item->id }}" class="hidden">
                        @csrf @method('DELETE')
                    </form>
                @endforeach
            </div>

            @if($registrants->hasPages())
            <div class="p-5 border-t border-slate-100 bg-white">
                {{ $registrants->withQueryString()->links() }}
            </div>
            @endif
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const counters = document.querySelectorAll('.count-up');
            counters.forEach(counter => {
                const target = +counter.getAttribute('data-target');
                let count = 0; const inc = Math.max(1, target / 30);
                const updateCount = () => {
                    count += inc;
                    if (count < target) { counter.innerText = Math.ceil(count); requestAnimationFrame(updateCount); } 
                    else { counter.innerText = target; }
                };
                updateCount();
            });

            @if(session('success'))
                Swal.fire({
                    icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}',
                    confirmButtonColor: '#10b981', // Tailwind Emerald 500
                    customClass: { popup: 'rounded-[2rem]', confirmButton: 'px-6 py-2 rounded-xl font-bold' }
                });
            @endif
            @if(session('error'))
                Swal.fire({ icon: 'error', title: 'Error', text: '{{ session('error') }}', customClass: { popup: 'rounded-[2rem]'} });
            @endif
            @if(session('warning'))
                Swal.fire({ 
                    icon: 'warning', 
                    title: 'Perhatian!', 
                    text: '{!! session('warning') !!}', 
                    confirmButtonColor: '#f59e0b',
                    customClass: { popup: 'rounded-[2rem]', confirmButton: 'px-6 py-2 rounded-xl font-bold' } 
                });
            @endif

            const checkAll = document.getElementById('checkAll');
            const checkItems = document.querySelectorAll('.check-item');
            if(checkAll) {
                checkAll.addEventListener('change', function() {
                    checkItems.forEach(item => item.checked = this.checked);
                });
            }
        });

        function submitBulk() {
            const selected = document.querySelectorAll('.check-item:checked').length;
            if(selected === 0) {
                Swal.fire({ icon: 'warning', title: 'Pilih Data', text: 'Centang minimal satu siswa yang belum dipetakan.', customClass: { popup: 'rounded-[2rem]' }, confirmButtonColor: '#2c3f61' });
                return;
            }
            Swal.fire({
                title: 'Promote Massal?',
                text: `Pindahkan ${selected} siswa yang terpilih ke Data Induk Siswa Aktif?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Pindahkan',
                confirmButtonColor: '#059669', // Emerald 600
                cancelButtonColor: '#64748b',
                customClass: { popup: 'rounded-[2rem]', confirmButton: 'rounded-xl font-bold', cancelButton: 'rounded-xl font-bold' }
            }).then((res) => {
                if(res.isConfirmed) document.getElementById('bulkForm').submit();
            });
        }

        function confirmDelete(id, name) {
            Swal.fire({
                title: 'Hapus Data?',
                html: `Data <b>${name}</b> akan dihapus permanen.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                confirmButtonColor: '#e11d48', // Tailwind Rose 600
                customClass: { popup: 'rounded-[2rem]', confirmButton: 'rounded-xl font-bold', cancelButton: 'rounded-xl font-bold' }
            }).then((res) => {
                if(res.isConfirmed) document.getElementById('delete-form-'+id).submit();
            });
        }

        // TAMBAHAN: Fungsi Konfirmasi Bagi Kelas Otomatis
        function confirmAutoDistribute() {
            Swal.fire({
                title: 'Eksekusi Pembagian Kelas?',
                text: "Sistem akan mengurutkan abjad seluruh siswa DITERIMA yang BELUM dipetakan, membuatkan NIS, membagi ke kelas 7A-7F secara merata, lalu memindahkannya ke Data Induk.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#4f46e5', // Indigo 600
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Proses Sekarang!',
                cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-[2rem]', confirmButton: 'rounded-xl font-bold', cancelButton: 'rounded-xl font-bold' }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('autoDistributeForm').submit();
                }
            });
        }
    </script>
</x-app-layout>