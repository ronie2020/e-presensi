<x-app-layout>
    {{-- Tambahkan CDN SweetAlert2 di bagian head atau stack styles jika perlu, 
         tapi biasanya cukup di script bawah --}}
    
    <div class="space-y-8">
        
        {{-- HEADER SECTION --}}
        <div class="bg-gradient-to-r from-slate-800 to-slate-900 rounded-3xl p-8 text-white shadow-xl shadow-slate-900/20 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-5 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row justify-between gap-6 items-start md:items-center">
                <div>
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/20 text-blue-300 text-xs font-bold uppercase tracking-wider mb-3">
                        <i class="ph-fill ph-shield-check"></i> Admin Dashboard
                    </div>
                    <h1 class="text-3xl font-extrabold tracking-tight">Pusat Tindak Lanjut Laporan</h1>
                    <p class="text-slate-400 text-sm mt-2 max-w-2xl">
                        Pantau dan kelola laporan siswa. Pastikan setiap masalah mendapatkan penanganan yang tepat dan tuntas.
                    </p>
                </div>
                
                {{-- Statistik Card Kecil --}}
                <div class="flex gap-4">
                    <div class="bg-white/10 backdrop-blur-sm p-4 rounded-2xl border border-white/10 text-center">
                        <span class="block text-2xl font-black text-amber-400">{{ $complaints->where('status', 'pending')->count() }}</span>
                        <span class="text-[10px] uppercase font-bold text-slate-400">Menunggu</span>
                    </div>
                    <div class="bg-white/10 backdrop-blur-sm p-4 rounded-2xl border border-white/10 text-center">
                        <span class="block text-2xl font-black text-emerald-400">{{ $complaints->where('status', 'resolved')->count() }}</span>
                        <span class="text-[10px] uppercase font-bold text-slate-400">Selesai</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- DAFTAR LAPORAN --}}
        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                    <i class="ph-fill ph-inbox text-blue-600"></i> Laporan Masuk
                </h3>
            </div>
            
            <div class="divide-y divide-slate-100">
                @forelse($complaints as $complaint)
                <div class="p-6 hover:bg-slate-50 transition-colors group relative">
                    {{-- Marker Status di Kiri --}}
                    <div class="absolute left-0 top-0 bottom-0 w-1 {{ $complaint->status == 'resolved' ? 'bg-emerald-500' : 'bg-amber-500' }}"></div>

                    <div class="flex flex-col md:flex-row gap-6 pl-2">
                        
                        {{-- INFO PELAPOR & KONTEN --}}
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-3 mb-4">
                                {{-- Badge Kategori --}}
                                <span class="px-3 py-1 rounded-lg text-[10px] font-bold border uppercase tracking-wider shadow-sm
                                    {{ $complaint->category == 'Bullying' ? 'bg-rose-100 text-rose-700 border-rose-200' : 
                                      ($complaint->category == 'Fasilitas' ? 'bg-indigo-100 text-indigo-700 border-indigo-200' : 'bg-blue-100 text-blue-700 border-blue-200') }}">
                                    {{ $complaint->category }}
                                </span>
                                
                                <span class="text-xs text-slate-400 font-bold flex items-center gap-1">
                                    <i class="ph-fill ph-calendar-blank"></i> {{ $complaint->created_at->translatedFormat('d F Y, H:i') }}
                                </span>
                            </div>

                            <div class="flex items-start gap-4 mb-4">
                                {{-- Avatar --}}
                                <div class="w-12 h-12 rounded-full flex items-center justify-center shrink-0 border-2
                                    {{ $complaint->is_anonymous ? 'bg-slate-800 text-slate-400 border-slate-700' : 'bg-blue-100 text-blue-600 border-blue-200' }}">
                                    <i class="ph-fill {{ $complaint->is_anonymous ? 'ph-spy' : 'ph-student' }} text-2xl"></i>
                                </div>
                                
                                <div>
                                    <h4 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                        @if($complaint->is_anonymous)
                                            Pelapor Merahasiakan Identitas
                                            <span class="px-2 py-0.5 rounded bg-slate-200 text-slate-600 text-[10px] font-bold uppercase">Anonim</span>
                                        @else
                                            {{ $complaint->student->name ?? 'Data Siswa Tidak Ditemukan' }}
                                        @endif
                                    </h4>
                                    
                                    @if(!$complaint->is_anonymous)
                                        <p class="text-xs text-slate-500 font-bold">
                                            NISN: {{ $complaint->student->student_id ?? '-' }} &bull; 
                                            Kelas: {{ $complaint->student->schoolClass->name ?? '-' }}
                                        </p>
                                    @else
                                        <p class="text-xs text-slate-400 italic flex items-center gap-1">
                                            <i class="ph-fill ph-lock-key"></i> Identitas disembunyikan atas permintaan siswa.
                                        </p>
                                    @endif
                                </div>
                            </div>
                            
                            {{-- Isi Laporan --}}
                            <div class="bg-slate-50 p-5 rounded-2xl border border-slate-200/60 relative">
                                <p class="text-sm font-bold text-slate-700 mb-1 flex items-center gap-2">
                                    <i class="ph-fill ph-map-pin text-rose-500"></i> {{ $complaint->location }}
                                </p>
                                <p class="text-slate-600 text-sm leading-relaxed">"{{ $complaint->description }}"</p>
                                
                                {{-- Bukti --}}
                                @if($complaint->evidence_path)
                                <div class="mt-4 pt-3 border-t border-slate-200">
                                    <a href="{{ asset('storage/' . $complaint->evidence_path) }}" target="_blank" class="inline-flex items-center gap-2 text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline">
                                        <i class="ph-bold ph-image"></i> Lihat Bukti Lampiran
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- PANEL STATUS & AKSI --}}
                        <div class="w-full md:w-64 flex flex-col gap-4 border-t md:border-t-0 md:border-l border-slate-100 pt-4 md:pt-0 md:pl-6 bg-white">
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Status Laporan</p>
                                
                                @if($complaint->status == 'resolved')
                                    {{-- Status: Selesai --}}
                                    <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-center mb-2">
                                        <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-2">
                                            <i class="ph-fill ph-check text-xl"></i>
                                        </div>
                                        <h5 class="text-sm font-bold text-emerald-800">Masalah Selesai</h5>
                                        <p class="text-[10px] text-emerald-600 mt-1">Laporan telah ditutup.</p>
                                    </div>
                                @else
                                    {{-- Status: Pending / Proses --}}
                                    <div class="p-4 bg-amber-50 border border-amber-100 rounded-2xl text-center mb-3">
                                        <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-2 animate-pulse">
                                            <i class="ph-fill ph-hourglass-medium text-xl"></i>
                                        </div>
                                        <h5 class="text-sm font-bold text-amber-800">Sedang Diproses</h5>
                                        <p class="text-[10px] text-amber-600 mt-1">Laporan diterima sistem.</p>
                                    </div>

                                    {{-- Tombol Aksi --}}
                                    <button onclick="confirmResolve('{{ $complaint->id }}')" 
                                            class="w-full py-3 px-4 bg-slate-900 hover:bg-blue-600 text-white rounded-xl text-xs font-bold shadow-lg shadow-slate-900/10 transition-all flex items-center justify-center gap-2 group transform active:scale-95">
                                        <i class="ph-bold ph-check-square-offset text-lg group-hover:scale-110 transition-transform"></i>
                                        Tandai Selesai
                                    </button>

                                    {{-- Form Tersembunyi untuk SweetAlert --}}
                                    <form id="resolve-form-{{ $complaint->id }}" action="{{ route('complaints.resolve', $complaint->id) }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
                @empty
                <div class="py-24 text-center flex flex-col items-center justify-center">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mb-6 border border-slate-100">
                        <i class="ph-duotone ph-tray text-5xl text-slate-300"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800">Tidak Ada Laporan Baru</h3>
                    <p class="text-slate-500 text-sm mt-2">Semua laporan telah ditangani atau belum ada yang masuk.</p>
                </div>
                @endforelse
            </div>
            
            @if($complaints->hasPages())
            <div class="p-6 border-t border-slate-100 bg-slate-50">
                {{ $complaints->links() }}
            </div>
            @endif
        </div>
    </div>

    @push('scripts')
    {{-- SWEETALERT 2 CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Notifikasi Sukses dari Session
        @if(session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3b82f6',
                timer: 3000,
                timerProgressBar: true
            });
        @endif

        // Konfirmasi Tandai Selesai
        function confirmResolve(id) {
            Swal.fire({
                title: 'Tandai Selesai?',
                text: "Status laporan akan berubah menjadi 'Selesai' dan siswa akan diberitahu.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#10b981', // Emerald Green
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Selesaikan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('resolve-form-' + id).submit();
                }
            })
        }
    </script>
    @endpush
</x-app-layout>