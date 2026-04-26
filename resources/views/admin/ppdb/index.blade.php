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

    <div class="relative space-y-6 md:space-y-8 min-h-screen pb-10 font-sans text-[#2A3B52] bg-[#f8fafc]">
        
        {{-- HERO SECTION (Tema Microsoft Elevate dari Dashboard) --}}
        <div class="animate-enter relative rounded-xl bg-gradient-to-br from-[#25D0FF] via-[#5295FF] to-[#FFC9B9] p-6 md:p-10 text-[#2A3B52] shadow-[0_10px_40px_-10px_rgba(37,208,255,0.4)] overflow-hidden group border border-white/40">
            
            {{-- Background Pattern & Blobs --}}
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.03] pointer-events-none mix-blend-overlay"></div>
            <div class="absolute top-0 left-0 w-[400px] h-[400px] bg-white/30 rounded-full blur-[100px] group-hover:opacity-70 transition-opacity duration-1000 pointer-events-none -ml-20 -mt-20"></div>
            <div class="absolute bottom-0 right-0 w-[300px] h-[300px] bg-white/20 rounded-full blur-[120px] pointer-events-none"></div>

            <div class="relative z-10 grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                
                {{-- KIRI: JUDUL & INTRO --}}
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/40 border border-white/50 text-[#2A3B52] text-xs font-bold uppercase tracking-wider mb-4 backdrop-blur-sm shadow-sm">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#D83B01] opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-[#D83B01]"></span>
                        </span>
                        Portal Penerimaan Siswa Baru
                    </div>
                    
                    <h1 class="text-3xl md:text-5xl font-extrabold text-[#2A3B52] tracking-tight mb-4 leading-tight">
                        Manajemen <br>
                        <span class="text-[#2A3B52]">PPDB Online</span> 
                    </h1>
                    <p class="text-[#2A3B52]/80 text-sm md:text-base max-w-xl leading-relaxed mb-8 font-medium">
                        Kelola data calon siswa, verifikasi berkas, dan atur jadwal pengumuman hasil seleksi secara terpusat.
                    </p>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-[#2A3B52] hover:bg-[#182436] text-white text-sm font-bold rounded-lg transition-all shadow-md flex items-center gap-2">
                            <i class="ph-bold ph-squares-four"></i> Dashboard Utama
                        </a>
                    </div>
                </div>

                {{-- KANAN: WIDGET JADWAL (GLASS LIGHT) --}}
                <div class="bg-white/30 backdrop-blur-md p-6 sm:p-8 rounded-[1.5rem] border border-white/40 shadow-sm relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 p-4 opacity-10 text-[#2A3B52] pointer-events-none transform rotate-12">
                        <i class="ph-fill ph-calendar-check text-[10rem]"></i>
                    </div>

                    <h3 class="text-xl font-black text-[#2A3B52] mb-2 flex items-center gap-2 relative z-10">
                        <i class="ph-duotone ph-clock text-[#5295FF]"></i> Jadwal Pengumuman
                    </h3>
                    <p class="text-[#2A3B52]/80 text-sm font-medium mb-6 relative z-10">Atur kapan hasil seleksi dapat dilihat publik.</p>
                    
                    {{-- Status Indikator --}}
                    <div class="mb-6 p-4 rounded-xl bg-white/50 border border-white/50 relative z-10 shadow-sm">
                        @if($isSet)
                            <div class="flex items-center gap-3 mb-2">
                                <span class="text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-md border {{ $isPast ? 'bg-[#DFF6DD] text-[#107C10] border-[#B7DFB9]' : 'bg-[#F3F9FD] text-[#5295FF] border-[#D0E7F8]' }}">
                                    {{ $isPast ? '● Sudah Dibuka' : '● Terjadwal' }}
                                </span>
                            </div>
                            <p class="text-[#2A3B52] text-lg font-bold tracking-wide font-mono">
                                {{ $announcementTime->translatedFormat('d M Y, H:i') }} WIB
                            </p>
                        @else
                            <div class="flex items-center gap-3 mb-2">
                                <span class="text-[10px] font-black uppercase tracking-wider px-2.5 py-1 rounded-md border bg-[#FFEFD6] text-[#D83B01] border-[#FFD8A8]">● Belum Diatur</span>
                            </div>
                            <p class="text-[#2A3B52]/70 text-sm">Siswa belum dapat melihat hasil.</p>
                        @endif
                    </div>

                    {{-- Form --}}
                    <form action="{{ route('admin.ppdb.set_schedule') }}" method="POST" class="space-y-2 relative z-10">
                        @csrf
                        <div class="flex flex-col sm:flex-row gap-3">
                            <input type="datetime-local" name="announcement_date" required 
                                   value="{{ $isSet ? $announcementTime->format('Y-m-d\TH:i') : '' }}"
                                   class="block w-full px-4 py-3 rounded-lg border-white/60 bg-white/60 focus:bg-white text-[#2A3B52] text-sm font-bold shadow-sm focus:ring-[#5295FF] focus:border-[#5295FF] transition-all cursor-pointer">
                            <button type="submit" class="px-6 py-3 bg-[#5295FF] hover:bg-[#3b7ee6] text-white font-bold rounded-lg shadow-md transition-all flex items-center justify-center gap-2 whitespace-nowrap" title="Simpan">
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
            <div class="group bg-white rounded-xl p-5 fluent-card relative overflow-hidden flex items-center gap-5 hover:border-[#5295FF]">
                <div class="w-14 h-14 rounded-lg flex items-center justify-center shadow-sm border transition-all duration-300 bg-[#F3F9FD] text-[#5295FF] border-[#D0E7F8] group-hover:bg-[#5295FF] group-hover:text-white group-hover:scale-110">
                    <i class="ph-duotone ph-users-three text-3xl animate-wiggle"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1 group-hover:text-[#5295FF] transition-colors">Total Pendaftar</p>
                    <h3 class="text-3xl font-black text-[#2A3B52] tracking-tight count-up" data-target="{{ $stats['total'] }}">0</h3>
                </div>
            </div>

            <!-- Diterima -->
            <div class="group bg-white rounded-xl p-5 fluent-card relative overflow-hidden flex items-center gap-5 hover:border-[#107C10]">
                <div class="w-14 h-14 rounded-lg flex items-center justify-center shadow-sm border transition-all duration-300 bg-[#DFF6DD] text-[#107C10] border-[#B7DFB9] group-hover:bg-[#107C10] group-hover:text-white group-hover:scale-110">
                    <i class="ph-duotone ph-medal text-3xl animate-wiggle"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1 group-hover:text-[#107C10] transition-colors">Lulus Seleksi</p>
                    <h3 class="text-3xl font-black text-[#2A3B52] tracking-tight count-up" data-target="{{ $stats['accepted'] }}">0</h3>
                </div>
            </div>

            <!-- Pending -->
            <div class="group bg-white rounded-xl p-5 fluent-card relative overflow-hidden flex items-center gap-5 hover:border-[#D83B01]">
                <div class="w-14 h-14 rounded-lg flex items-center justify-center shadow-sm border transition-all duration-300 bg-[#FFEFD6] text-[#D83B01] border-[#FFD8A8] group-hover:bg-[#D83B01] group-hover:text-white group-hover:scale-110">
                    <i class="ph-duotone ph-clock-countdown text-3xl animate-wiggle"></i>
                </div>
                <div>
                    <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1 group-hover:text-[#D83B01] transition-colors">Perlu Verifikasi</p>
                    <h3 class="text-3xl font-black text-[#2A3B52] tracking-tight count-up" data-target="{{ $stats['pending'] }}">0</h3>
                </div>
            </div>
        </div>

        {{-- MAIN CONTENT: TABEL DATA --}}
        <div class="animate-enter bg-white rounded-xl fluent-card overflow-hidden flex flex-col min-h-[600px]" style="animation-delay: 200ms">
            
            {{-- Toolbar --}}
            <div class="p-5 md:p-6 border-b border-slate-100 flex flex-col xl:flex-row xl:items-center justify-between gap-5">
                
                {{-- Tabs Filter --}}
                <div class="bg-slate-50 p-1.5 rounded-lg flex flex-wrap gap-1 w-full md:w-auto overflow-x-auto border border-slate-200 no-scrollbar">
                    @php
                        $tabClass = "px-4 py-2 rounded-md text-xs font-bold transition-all flex items-center gap-2 whitespace-nowrap";
                        $activeClass = "bg-white text-[#5295FF] shadow-sm border border-slate-200";
                        $inactiveClass = "text-slate-500 hover:text-[#2A3B52] hover:bg-slate-100";
                    @endphp

                    <a href="{{ route('admin.ppdb.index') }}" class="{{ $tabClass }} {{ !request('status') ? $activeClass : $inactiveClass }}">
                       <i class="ph-bold ph-squares-four"></i> Semua
                    </a>
                    <a href="{{ route('admin.ppdb.index', ['status' => 'pending']) }}" class="{{ $tabClass }} {{ request('status') == 'pending' ? 'bg-[#D83B01] text-white shadow-sm' : $inactiveClass }}">
                       <i class="ph-bold ph-clock"></i> Pending
                    </a>
                    <a href="{{ route('admin.ppdb.index', ['status' => 'verified']) }}" class="{{ $tabClass }} {{ request('status') == 'verified' ? 'bg-[#5295FF] text-white shadow-sm' : $inactiveClass }}">
                       <i class="ph-bold ph-check-circle"></i> Verified
                    </a>
                    <a href="{{ route('admin.ppdb.index', ['status' => 'accepted']) }}" class="{{ $tabClass }} {{ request('status') == 'accepted' ? 'bg-[#107C10] text-white shadow-sm' : $inactiveClass }}">
                       <i class="ph-bold ph-medal"></i> Diterima
                    </a>
                </div>
                
                {{-- Search & Bulk Actions --}}
                <div class="flex flex-col md:flex-row gap-3 w-full xl:w-auto">
                    <button type="button" onclick="submitBulk()" class="px-5 py-2.5 bg-[#2A3B52] text-white rounded-lg font-bold text-xs hover:bg-[#182436] transition flex items-center justify-center gap-2 shadow-sm">
                        <i class="ph-bold ph-user-plus"></i> Promote Terpilih
                    </button>

                    <form method="GET" class="relative group w-full md:w-64">
                        <input type="hidden" name="status" value="{{ request('status') }}">
                        <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-[#5295FF] transition-colors"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Siswa..." 
                               class="w-full pl-10 pr-4 py-2.5 rounded-lg border-slate-200 bg-slate-50 focus:bg-white focus:border-[#5295FF] focus:ring-[#5295FF] text-sm font-bold text-[#2A3B52] transition-all shadow-sm placeholder:font-medium">
                    </form>
                </div>
            </div>

            {{-- Table Wrapper --}}
            <div class="overflow-x-auto flex-1">
                <form action="{{ route('admin.ppdb.bulk_promote') }}" method="POST" id="bulkForm">
                    @csrf
                    <table class="w-full text-sm text-left text-[#2A3B52]">
                        <thead class="bg-slate-50 text-xs font-bold text-slate-500 uppercase tracking-wider sticky top-0 z-10 border-b border-slate-100">
                            <tr>
                                <th class="px-5 py-4 w-10 text-center">
                                    <input type="checkbox" id="checkAll" class="rounded border-slate-300 text-[#5295FF] focus:ring-[#5295FF] w-4 h-4 cursor-pointer bg-white">
                                </th>
                                <th class="px-5 py-4">Data Siswa</th>
                                <th class="px-5 py-4 text-center">Jalur & Nilai</th>
                                <th class="px-5 py-4 text-center">Status</th>
                                <th class="px-5 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($registrants as $item)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-5 py-4 text-center">
                                    @if($item->status == 'accepted')
                                        <input type="checkbox" name="selected_ids[]" value="{{ $item->id }}" class="check-item rounded border-slate-300 text-[#5295FF] focus:ring-[#5295FF] w-4 h-4 cursor-pointer">
                                    @else
                                        <i class="ph-bold ph-minus text-slate-300"></i>
                                    @endif
                                </td>
                                
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-500 font-black text-sm group-hover:scale-110 group-hover:border-[#5295FF] group-hover:text-[#5295FF] transition-all shadow-sm">
                                            {{ substr($item->full_name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-[#2A3B52] mb-0.5 group-hover:text-[#5295FF] transition-colors">{{ $item->full_name }}</div>
                                            <div class="text-[11px] text-slate-400 font-mono flex items-center gap-1.5">
                                                <i class="ph-bold ph-identification-card"></i> {{ $item->registration_number }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-center">
                                    <div class="flex flex-col items-center gap-1.5">
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider border 
                                            {{ $item->track == 'prestasi' ? 'bg-[#DFF6DD] text-[#107C10] border-[#B7DFB9]' : 'bg-[#F3F9FD] text-[#5295FF] border-[#D0E7F8]' }}">
                                            {{ $item->track }}
                                        </span>
                                        <span class="text-xs font-bold text-slate-500 bg-white border border-slate-200 px-2 py-0.5 rounded shadow-sm">{{ $item->average_grade }}</span>
                                    </div>
                                </td>

                                <td class="px-5 py-4 text-center">
                                    @if($item->status == 'pending')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-[#FFEFD6] text-[#D83B01] border border-[#FFD8A8]">
                                            <span class="w-1.5 h-1.5 rounded-full bg-[#D83B01] animate-pulse"></span> Pending
                                        </span>
                                    @elseif($item->status == 'accepted')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-[#DFF6DD] text-[#107C10] border border-[#B7DFB9]">
                                            <i class="ph-fill ph-check-circle"></i> Diterima
                                        </span>
                                    @elseif($item->status == 'verified')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-[#F3F9FD] text-[#5295FF] border border-[#D0E7F8]">
                                            <i class="ph-fill ph-shield-check"></i> Terverifikasi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold bg-[#FDE7E9] text-[#D13438] border border-[#F4C3C9]">
                                            <i class="ph-fill ph-x-circle"></i> Ditolak
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('admin.ppdb.show', $item->id) }}" class="p-2 rounded-lg text-slate-400 hover:text-[#5295FF] hover:bg-[#F3F9FD] hover:border-[#D0E7F8] border border-transparent transition-all" title="Detail">
                                            <i class="ph-bold ph-eye text-lg"></i>
                                        </a>
                                        <button type="button" onclick="confirmDelete('{{ $item->id }}', '{{ $item->full_name }}')" class="p-2 rounded-lg text-slate-400 hover:text-[#D13438] hover:bg-[#FDE7E9] hover:border-[#F4C3C9] border border-transparent transition-all" title="Hapus">
                                            <i class="ph-bold ph-trash text-lg"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-16 text-center">
                                    <div class="w-16 h-16 bg-slate-50 rounded-xl flex items-center justify-center mx-auto mb-4 border border-slate-100 text-slate-300">
                                        <i class="ph-duotone ph-folder-notch-open text-3xl"></i>
                                    </div>
                                    <p class="text-sm font-bold text-[#2A3B52]">Belum ada data pendaftar</p>
                                    <p class="text-xs text-slate-400 mt-1">Silakan sesuaikan filter pencarian.</p>
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
                    confirmButtonColor: '#107C10',
                    customClass: { popup: 'fluent-modal rounded-xl', confirmButton: 'px-6 py-2 rounded-lg font-bold' }
                });
            @endif
            @if(session('error'))
                Swal.fire({ icon: 'error', title: 'Error', text: '{{ session('error') }}', customClass: { popup: 'fluent-modal rounded-xl'} });
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
                Swal.fire({ icon: 'warning', title: 'Pilih Data', text: 'Centang minimal satu siswa.', customClass: { popup: 'fluent-modal rounded-xl' }, confirmButtonColor: '#2A3B52' });
                return;
            }
            Swal.fire({
                title: 'Promote Siswa?',
                text: `Pindahkan ${selected} siswa ke Data Induk Siswa Aktif?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Proses',
                confirmButtonColor: '#5295FF',
                cancelButtonColor: '#64748b',
                customClass: { popup: 'fluent-modal rounded-xl', confirmButton: 'rounded-lg font-bold', cancelButton: 'rounded-lg font-bold' }
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
                confirmButtonColor: '#D13438',
                customClass: { popup: 'fluent-modal rounded-xl', confirmButton: 'rounded-lg font-bold', cancelButton: 'rounded-lg font-bold' }
            }).then((res) => {
                if(res.isConfirmed) document.getElementById('delete-form-'+id).submit();
            });
        }
    </script>
</x-app-layout>