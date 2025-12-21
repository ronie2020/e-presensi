<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('CBT Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- NOTIFIKASI SWEETALERT (Akan dipanggil via Script di bawah) --}}
            @if(session('success'))
                <div id="flash-success" data-message="{{ session('success') }}"></div>
            @endif

            {{-- HEADER STATS (Diadopsi dari Halaman Jurnal) --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                {{-- Kartu 1: Info Hari Ini (Gradient) --}}
                <div class="bg-gradient-to-br from-indigo-600 to-purple-700 rounded-[2rem] p-8 text-white shadow-xl shadow-indigo-500/20 relative overflow-hidden group">
                    <div class="absolute right-0 top-0 opacity-10 transform translate-x-8 -translate-y-8 group-hover:scale-110 transition-transform duration-500">
                        <i class="ph-fill ph-monitor-play text-[10rem]"></i>
                    </div>
                    <div class="relative z-10">
                        <p class="text-indigo-100 font-medium mb-1 flex items-center gap-2"><i class="ph-bold ph-calendar-blank"></i> Hari Ini</p>
                        <h3 class="text-2xl font-black tracking-tight">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</h3>
                        <div class="mt-6 flex items-center gap-3">
                            <span class="bg-white/20 backdrop-blur-md px-4 py-2 rounded-xl text-sm font-bold border border-white/10 shadow-sm">
                                {{ $exams->count() }} Ujian Terdaftar
                            </span>
                        </div>
                    </div>
                </div>
                
                {{-- Kartu 2: Welcome & Action (Putih) --}}
                <div class="md:col-span-2 bg-white rounded-[2rem] p-8 shadow-sm border border-slate-100 flex flex-col md:flex-row items-center justify-between relative overflow-hidden">
                    <div class="absolute inset-0 bg-slate-50/50 opacity-0 md:opacity-100 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:16px_16px]"></div>
                    
                    <div class="relative z-10 max-w-lg mb-6 md:mb-0 text-center md:text-left">
                        <h3 class="font-bold text-slate-800 text-2xl mb-2">Halo, {{ Auth::user()->name }}! 🚀</h3>
                        <p class="text-slate-500 leading-relaxed">
                            Kelola ujian berbasis komputer dengan mudah. Pantau nilai siswa dan aktivasi token ujian di sini.
                        </p>
                    </div>

                    <div class="relative z-10">
                        <a href="{{ route('cbt.create') }}" class="group flex items-center gap-3 px-6 py-4 bg-blue-600 text-white rounded-2xl font-bold hover:bg-blue-700 active:scale-95 transition-all shadow-lg shadow-blue-500/30">
                            <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center group-hover:scale-110 transition">
                                <i class="ph-bold ph-plus text-white"></i>
                            </div>
                            <span>Buat Jadwal Baru</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- LIST JADWAL HEADER --}}
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-8 gap-4">
                <h3 class="font-black text-slate-800 text-2xl flex items-center gap-3">
                    <span class="bg-blue-100 text-blue-600 w-10 h-10 rounded-xl flex items-center justify-center text-xl shadow-sm border border-blue-200">
                        <i class="ph-bold ph-list-numbers"></i>
                    </span>
                    Daftar Ujian CBT
                </h3>
                
                {{-- Filter Status (Opsional, Pemanis Tampilan) --}}
                <div class="flex p-1 bg-white border border-slate-200 rounded-xl shadow-sm">
                    <button class="px-4 py-1.5 rounded-lg bg-slate-100 text-slate-700 text-xs font-bold shadow-sm">Semua</button>
                    <button class="px-4 py-1.5 rounded-lg text-slate-500 text-xs font-bold hover:bg-slate-50 transition">Aktif</button>
                </div>
            </div>

            {{-- GRID CARD UJIAN --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($exams as $exam)
                    <div class="bg-white border border-slate-200 rounded-[2rem] p-6 hover:shadow-xl hover:border-indigo-200 transition-all duration-300 group relative flex flex-col h-full">
                        
                        <!-- Status Badge Overlay -->
                        <div class="absolute top-6 right-6">
                            @if($exam->is_active)
                                <span class="flex items-center gap-1.5 px-3 py-1 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-full text-[10px] font-black uppercase tracking-wider shadow-sm animate-pulse">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Aktif
                                </span>
                            @else
                                <span class="flex items-center gap-1.5 px-3 py-1 bg-slate-100 text-slate-500 border border-slate-200 rounded-full text-[10px] font-black uppercase tracking-wider">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Non-Aktif
                                </span>
                            @endif
                        </div>

                        <!-- Header Card -->
                        <div class="mb-5">
                            <span class="inline-block px-3 py-1 bg-indigo-50 text-indigo-600 border border-indigo-100 rounded-lg text-[11px] font-bold uppercase tracking-wide mb-3">
                                {{ $exam->subject_name }}
                            </span>
                            <h4 class="font-black text-xl text-slate-800 leading-tight group-hover:text-indigo-600 transition-colors line-clamp-2" title="{{ $exam->title }}">
                                {{ $exam->title }}
                            </h4>
                        </div>
                        
                        <!-- Content: Token & Waktu -->
                        <div class="flex-1 space-y-4">
                            <!-- Token Box -->
                            <div class="bg-slate-50 border border-slate-100 rounded-2xl p-4 flex items-center justify-between group/token cursor-pointer hover:bg-slate-100 transition" onclick="copyToken('{{ $exam->token }}')">
                                <div>
                                    <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider block mb-0.5">Token Ujian</span>
                                    <span class="font-mono font-black text-xl text-slate-700 tracking-widest">{{ $exam->token }}</span>
                                </div>
                                <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center text-slate-400 shadow-sm border border-slate-100 group-hover/token:text-blue-500 group-hover/token:scale-110 transition">
                                    <i class="ph-bold ph-copy"></i>
                                </div>
                            </div>

                            <!-- Info Grid -->
                            <div class="grid grid-cols-2 gap-3 text-xs">
                                <div class="flex items-center gap-2 text-slate-600">
                                    <i class="ph-bold ph-users text-purple-500"></i>
                                    <span class="font-bold">Kelas {{ $exam->class_level }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-slate-600">
                                    <i class="ph-bold ph-clock text-blue-500"></i>
                                    <span class="font-bold">{{ $exam->duration_minutes }} Menit</span>
                                </div>
                                <div class="col-span-2 flex items-center gap-2 text-slate-500 pt-2 border-t border-slate-100">
                                    <i class="ph-bold ph-calendar-blank"></i>
                                    <span>{{ \Carbon\Carbon::parse($exam->start_time)->format('d M Y, H:i') }} WIB</span>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Actions -->
                        <div class="mt-6 pt-4 border-t border-slate-100 grid grid-cols-5 gap-2">
                            <!-- Tombol Kelola (Lebar) -->
                            <a href="{{ route('cbt.questions.manage', $exam->id) }}" class="col-span-2 flex flex-col items-center justify-center p-2 bg-indigo-50 text-indigo-600 rounded-xl text-xs font-bold hover:bg-indigo-600 hover:text-white transition-all group/btn border border-indigo-100">
                                <i class="ph-bold ph-list-numbers text-lg mb-1"></i>
                                <span>Soal</span>
                            </a>
                            
                            <!-- Tombol Monitor (Lebar) -->
                            <a href="{{ route('cbt.monitoring', $exam->id) }}" class="col-span-2 flex flex-col items-center justify-center p-2 bg-emerald-50 text-emerald-600 rounded-xl text-xs font-bold hover:bg-emerald-600 hover:text-white transition-all group/btn border border-emerald-100">
                                <i class="ph-bold ph-desktop text-lg mb-1"></i>
                                <span>Monitor</span>
                            </a>

                            <!-- Tombol Hapus (Kecil) -->
                            <button onclick="confirmDelete('{{ $exam->id }}')" class="col-span-1 flex flex-col items-center justify-center p-2 bg-rose-50 text-rose-500 rounded-xl text-xs font-bold hover:bg-rose-500 hover:text-white transition-all border border-rose-100">
                                <i class="ph-bold ph-trash text-lg mb-1"></i>
                                <span>Hapus</span>
                            </button>

                            {{-- Form Hapus Tersembunyi (Dipicu oleh JS SweetAlert) --}}
                            <form id="delete-form-{{ $exam->id }}" action="{{ route('cbt.destroy', $exam->id) }}" method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-20 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200">
                        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300 animate-bounce-slow">
                            <i class="ph-duotone ph-ghost text-5xl"></i>
                        </div>
                        <h3 class="text-slate-800 font-bold text-xl mb-2">Belum ada Jadwal Ujian</h3>
                        <p class="text-slate-500 max-w-xs mx-auto mb-8">Data ujian masih kosong. Yuk buat jadwal ujian pertamamu sekarang!</p>
                        <a href="{{ route('cbt.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white rounded-full font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-500/30">
                            <i class="ph-bold ph-plus"></i> Buat Jadwal Baru
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- CDN SweetAlert2 & Script Custom --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Copy Token Function dengan Toast SweetAlert
        function copyToken(token) {
            navigator.clipboard.writeText(token).then(() => {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                })

                Toast.fire({
                    icon: 'success',
                    title: 'Token ' + token + ' berhasil disalin!'
                })
            });
        }

        // Delete Confirmation dengan SweetAlert2
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Ujian?',
                text: "Data ujian, jawaban siswa, dan nilai akan dihapus permanen! Tindakan ini tidak bisa dibatalkan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48', // Rose-600
                cancelButtonColor: '#64748b', // Slate-500
                confirmButtonText: 'Ya, Hapus Semuanya!',
                cancelButtonText: 'Batal',
                background: '#fff',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl px-4 py-2 font-bold',
                    cancelButton: 'rounded-xl px-4 py-2 font-bold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }

        // Cek Flash Message dari Session Laravel
        document.addEventListener("DOMContentLoaded", function() {
            const flashSuccess = document.getElementById('flash-success');
            if (flashSuccess) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: flashSuccess.getAttribute('data-message'),
                    timer: 3000,
                    showConfirmButton: false,
                    customClass: {
                        popup: 'rounded-2xl'
                    }
                });
            }
        });
    </script>
</x-app-layout>