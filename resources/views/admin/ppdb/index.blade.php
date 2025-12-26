<x-app-layout>
    {{-- CDN SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans text-slate-800">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- HEADER: STATISTIK & JADWAL --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- 1. PANEL PENGATURAN JADWAL --}}
                @php
                    // Ambil data jadwal dari controller
                    $announcementTime = isset($scheduleData['announcement_date']) ? \Carbon\Carbon::parse($scheduleData['announcement_date']) : null;
                    $isSet = $announcementTime != null;
                    $isPast = $isSet && \Carbon\Carbon::now()->greaterThanOrEqualTo($announcementTime);
                @endphp
                
                <div class="lg:col-span-2 bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden p-8">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
                    
                    <div class="flex flex-col md:flex-row gap-8 items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-xl shadow-sm border border-indigo-100">
                                    <i class="ph-duotone ph-calendar-check"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-black text-slate-800 leading-tight">Jadwal Pengumuman</h3>
                                    <p class="text-xs text-slate-500 font-bold">Atur waktu pembukaan akses hasil seleksi</p>
                                </div>
                            </div>
                            
                            <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200">
                                @if($isSet)
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="relative flex h-3 w-3">
                                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $isPast ? 'bg-emerald-400' : 'bg-blue-400' }} opacity-75"></span>
                                          <span class="relative inline-flex rounded-full h-3 w-3 {{ $isPast ? 'bg-emerald-500' : 'bg-blue-500' }}"></span>
                                        </span>
                                        <span class="text-xs font-black uppercase tracking-wider {{ $isPast ? 'text-emerald-600' : 'text-blue-600' }}">
                                            {{ $isPast ? 'Sudah Dibuka' : 'Terjadwal (Menunggu Waktu)' }}
                                        </span>
                                    </div>
                                    <p class="text-slate-600 text-sm font-medium">
                                        Waktu Pengumuman: <br>
                                        <strong class="text-slate-900 text-lg">{{ $announcementTime->translatedFormat('l, d F Y - H:i') }} WIB</strong>
                                    </p>
                                @else
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="h-3 w-3 rounded-full bg-amber-400"></span>
                                        <span class="text-xs font-black uppercase tracking-wider text-amber-600">Belum Diatur</span>
                                    </div>
                                    <p class="text-slate-400 text-sm italic">Siswa belum dapat melihat hasil seleksi.</p>
                                @endif
                            </div>
                        </div>

                        <div class="w-full md:w-1/2">
                            <form action="{{ route('admin.ppdb.set_schedule') }}" method="POST" class="space-y-3">
                                @csrf
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Set Waktu Serentak</label>
                                <div class="relative group">
                                    <i class="ph-bold ph-clock absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-600 transition-colors"></i>
                                    <input type="datetime-local" name="announcement_date" required 
                                           value="{{ $isSet ? $announcementTime->format('Y-m-d\TH:i') : '' }}"
                                           class="block w-full pl-9 pr-4 py-2.5 rounded-xl border-slate-200 bg-white focus:border-indigo-500 focus:ring-indigo-500 text-sm font-bold text-slate-700 shadow-sm transition-all">
                                </div>
                                <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/20 transition-all text-sm flex items-center justify-center gap-2">
                                    <i class="ph-bold ph-floppy-disk"></i> Simpan Jadwal
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- 2. KARTU STATISTIK RINGKAS --}}
                <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-[2.5rem] shadow-xl p-8 text-white relative overflow-hidden flex flex-col justify-center">
                    <div class="absolute top-0 right-0 p-4 opacity-10"><i class="ph-fill ph-users-three text-9xl"></i></div>
                    
                    <h3 class="text-lg font-bold mb-6 flex items-center gap-2 relative z-10">
                        <i class="ph-fill ph-chart-bar"></i> Total Pendaftar
                    </h3>
                    
                    <div class="space-y-4 relative z-10">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400 text-sm">Total Masuk</span>
                            <span class="text-2xl font-black">{{ $stats['total'] }}</span>
                        </div>
                        <div class="w-full bg-slate-700/50 rounded-full h-1.5">
                            <div class="bg-blue-500 h-1.5 rounded-full" style="width: 100%"></div>
                        </div>
                        <div class="grid grid-cols-3 gap-2 text-center pt-2">
                            <div>
                                <span class="block text-xs text-slate-400">Pending</span>
                                <span class="block font-bold text-yellow-400">{{ $stats['pending'] }}</span>
                            </div>
                            <div>
                                <span class="block text-xs text-slate-400">Verified</span>
                                <span class="block font-bold text-blue-400">{{ $stats['verified'] }}</span>
                            </div>
                            <div>
                                <span class="block text-xs text-slate-400">Diterima</span>
                                <span class="block font-bold text-emerald-400">{{ $stats['accepted'] }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION: TABEL DATA --}}
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative min-h-[600px] flex flex-col">
                
                {{-- Toolbar Table --}}
                <div class="p-8 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex flex-wrap gap-2">
                        <a href="{{ route('admin.ppdb.index') }}" class="px-4 py-2 text-xs font-bold rounded-xl border transition-all {{ !request('status') ? 'bg-slate-800 text-white border-slate-800' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">Semua</a>
                        <a href="{{ route('admin.ppdb.index', ['status' => 'pending']) }}" class="px-4 py-2 text-xs font-bold rounded-xl border transition-all {{ request('status') == 'pending' ? 'bg-yellow-500 text-white border-yellow-500' : 'bg-white text-slate-600 border-slate-200 hover:text-yellow-600' }}">Pending</a>
                        <a href="{{ route('admin.ppdb.index', ['status' => 'verified']) }}" class="px-4 py-2 text-xs font-bold rounded-xl border transition-all {{ request('status') == 'verified' ? 'bg-blue-500 text-white border-blue-500' : 'bg-white text-slate-600 border-slate-200 hover:text-blue-600' }}">Verified</a>
                        <a href="{{ route('admin.ppdb.index', ['status' => 'accepted']) }}" class="px-4 py-2 text-xs font-bold rounded-xl border transition-all {{ request('status') == 'accepted' ? 'bg-emerald-500 text-white border-emerald-500' : 'bg-white text-slate-600 border-slate-200 hover:text-emerald-600' }}">Diterima</a>
                    </div>
                    
                    <form method="GET" class="relative group w-full md:w-72">
                        <input type="hidden" name="status" value="{{ request('status') }}">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama / NISN..." class="pl-10 pr-4 py-2.5 border-slate-200 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500 w-full bg-slate-50 focus:bg-white transition-all font-bold text-slate-600">
                        <i class="ph-bold ph-magnifying-glass absolute left-3.5 top-3 text-slate-400 group-focus-within:text-indigo-500 transition-colors"></i>
                    </form>
                </div>

                {{-- Tabel --}}
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-sm text-left text-slate-600">
                        <thead class="text-xs font-bold text-slate-400 uppercase bg-slate-50/80 sticky top-0 backdrop-blur-sm z-10">
                            <tr>
                                <th class="px-6 py-4">No. Daftar</th>
                                <th class="px-6 py-4">Identitas Siswa</th>
                                <th class="px-6 py-4 text-center">Jalur</th>
                                <th class="px-6 py-4 text-center">Nilai</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($registrants as $item)
                            <tr class="hover:bg-blue-50/30 transition-colors group">
                                <td class="px-6 py-4">
                                    <span class="font-mono font-bold text-slate-500 bg-slate-100 px-2 py-1 rounded text-xs">{{ $item->registration_number }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        <div class="font-black text-slate-700">{{ $item->full_name }}</div>
                                        <div class="text-xs text-slate-400 font-bold mt-0.5">{{ $item->school_origin }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-1 rounded-lg text-[10px] font-bold uppercase border bg-white {{ $item->track == 'prestasi' ? 'text-purple-600 border-purple-200' : ($item->track == 'afirmasi' ? 'text-orange-600 border-orange-200' : 'text-blue-600 border-blue-200') }}">
                                        {{ $item->track }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center font-bold text-slate-700">{{ $item->average_grade }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if($item->status == 'pending')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-yellow-50 text-yellow-600 border border-yellow-100"><span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></span> Pending</span>
                                    @elseif($item->status == 'verified')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-blue-50 text-blue-600 border border-blue-100"><i class="ph-bold ph-check"></i> Verified</span>
                                    @elseif($item->status == 'accepted')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100"><i class="ph-bold ph-medal"></i> Diterima</span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-50 text-rose-600 border border-rose-100"><i class="ph-bold ph-x"></i> Ditolak</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        {{-- TOMBOL DETAIL --}}
                                        <a href="{{ route('admin.ppdb.show', $item->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-white border border-slate-200 rounded-lg font-bold text-xs text-slate-600 hover:bg-blue-600 hover:text-white hover:border-blue-600 transition shadow-sm" title="Lihat Detail">
                                            Detail
                                        </a>

                                        {{-- TOMBOL HAPUS (BARU) --}}
                                        <form action="{{ route('admin.ppdb.destroy', $item->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn-delete-confirm inline-flex items-center justify-center w-8 h-8 bg-white border border-slate-200 rounded-lg text-slate-400 hover:bg-rose-50 hover:text-rose-600 hover:border-rose-200 transition shadow-sm" 
                                                data-name="{{ $item->full_name }}" title="Hapus Data">
                                                <i class="ph-bold ph-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-400">
                                        <i class="ph-duotone ph-folder-open text-4xl mb-3"></i>
                                        <p class="text-sm font-medium">Belum ada data pendaftar.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="p-4 border-t border-slate-50 bg-slate-50/50">
                    {{ $registrants->withQueryString()->links() }}
                </div>
            </div>

        </div>
    </div>

    {{-- SCRIPT SWEETALERT2 --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. FLASH MESSAGES (SUCCESS / ERROR)
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#1e3a8a', // Blue 900
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#e11d48', // Rose 600
                });
            @endif

            // 2. KONFIRMASI HAPUS
            // Menggunakan Event Delegation
            document.body.addEventListener('click', function(e) {
                // Cek jika yang diklik adalah tombol delete atau ikon di dalamnya
                const button = e.target.closest('.btn-delete-confirm');
                
                if(button) {
                    e.preventDefault();
                    const form = button.closest('form');
                    const name = button.getAttribute('data-name');

                    Swal.fire({
                        title: 'Hapus Pendaftar?',
                        html: `Data pendaftar <b>${name}</b> akan dihapus permanen. <br><span class="text-xs text-rose-500">Berkas upload juga akan dihapus.</span>`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e11d48', // Rose 600
                        cancelButtonColor: '#64748b',  // Slate 500
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        focusCancel: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            });
        });
    </script>
</x-app-layout>