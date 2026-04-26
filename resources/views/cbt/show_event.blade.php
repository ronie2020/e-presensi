<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Daftar Ujian - ' . $event->name) }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-10 font-sans text-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- NOTIFIKASI --}}
            @if(session('success'))
                <div id="flash-success" data-message="{{ session('success') }}"></div>
            @endif

            {{-- BREADCRUMB & HEADER --}}
            <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-5 rounded-[2rem] shadow-sm border border-slate-100">
                <div>
                    <div class="flex items-center gap-2 text-slate-400 text-sm font-bold mb-1">
                        <a href="{{ route('cbt.index') }}" class="hover:text-[#0d52a1] transition flex items-center gap-1">
                            <i class="ph-bold ph-folders"></i> Dashboard Folder
                        </a>
                        <span>/</span>
                        <span class="text-[#0d52a1]">{{ $event->name }}</span>
                    </div>
                    <h1 class="text-2xl font-black text-[#2c3f61] tracking-tight">Manajemen Ujian</h1>
                </div>
                <div class="flex gap-2 w-full md:w-auto">
                    <a href="{{ route('cbt.create', ['event_id' => $event->id]) }}" class="w-full md:w-auto px-6 py-3 bg-[#2c3f61] text-white font-bold rounded-xl hover:bg-[#1c2940] transition shadow-lg shadow-[#2c3f61]/20 flex items-center justify-center gap-2">
                        <i class="ph-bold ph-plus-circle text-lg"></i> Buat Ujian
                    </a>
                </div>
            </div>

           {{-- STATISTIK (Elevate Colors) --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-[2rem] p-5 border border-slate-100 shadow-xl shadow-[#56bbf1]/5 flex items-center gap-4 hover:-translate-y-1 transition-transform">
                    <div class="w-12 h-12 bg-[#56bbf1]/20 text-[#0d52a1] rounded-[1rem] flex items-center justify-center text-xl shrink-0 border border-[#56bbf1]/30"><i class="ph-bold ph-check-circle"></i></div>
                    <div><p class="text-[10px] text-slate-400 font-bold uppercase mb-0.5 tracking-wider">Ujian Aktif</p><h4 class="text-2xl font-black text-[#2c3f61]">{{ $stats['active_exams'] }}</h4></div>
                </div>
                <div class="bg-white rounded-[2rem] p-5 border border-slate-100 shadow-xl shadow-[#56bbf1]/5 flex items-center gap-4 hover:-translate-y-1 transition-transform">
                    <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-[1rem] flex items-center justify-center text-xl shrink-0"><i class="ph-bold ph-list-numbers"></i></div>
                    <div><p class="text-[10px] text-slate-400 font-bold uppercase mb-0.5 tracking-wider">Total Soal</p><h4 class="text-2xl font-black text-[#2c3f61]">{{ number_format($stats['total_questions']) }}</h4></div>
                </div>
                <div class="bg-white rounded-[2rem] p-5 border border-slate-100 shadow-xl shadow-[#56bbf1]/5 flex items-center gap-4 hover:-translate-y-1 transition-transform">
                    <div class="w-12 h-12 bg-[#f9a282]/20 text-[#c86845] rounded-[1rem] flex items-center justify-center text-xl shrink-0"><i class="ph-bold ph-users"></i></div>
                    <div><p class="text-[10px] text-slate-400 font-bold uppercase mb-0.5 tracking-wider">Siswa Ujian</p><h4 class="text-2xl font-black text-[#2c3f61]">{{ number_format($stats['students_working']) }}</h4></div>
                </div>
                <div class="bg-white rounded-[2rem] p-5 border border-slate-100 shadow-xl shadow-[#56bbf1]/5 flex items-center gap-4 hover:-translate-y-1 transition-transform">
                    <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-[1rem] flex items-center justify-center text-xl shrink-0"><i class="ph-bold ph-chart-line-up"></i></div>
                    <div><p class="text-[10px] text-slate-400 font-bold uppercase mb-0.5 tracking-wider">Rata Nilai</p><h4 class="text-2xl font-black text-[#2c3f61]">{{ number_format($stats['avg_score'], 1) }}</h4></div>
                </div>
            </div>

             {{-- LOGIK PENGURUTAN & PENGELOMPOKAN BERDASARKAN TANGGAL --}}
            @php
                $examItems = method_exists($exams, 'items') ? collect($exams->items()) : collect($exams);
                $sortedExams = $examItems->sortBy(function($exam) {
                    return $exam->start_time ? \Carbon\Carbon::parse($exam->start_time)->timestamp : 9999999999;
                });
                $groupedExams = $sortedExams->groupBy(function($exam) {
                    if (!$exam->start_time) return 'Belum Dijadwalkan';
                    return \Carbon\Carbon::parse($exam->start_time)->locale('id')->isoFormat('dddd, D MMMM Y');
                });
            @endphp

            {{-- GRID KARTU MATA PELAJARAN --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($groupedExams as $dateLabel => $dailyExams)
                    
                     {{-- HEADER PEMISAH HARI --}}
                    <div class="col-span-full mb-2 mt-4 first:mt-0">
                        <div class="flex items-center gap-4">
                            <div class="bg-white border border-slate-200 rounded-[1rem] p-3 pr-5 flex items-center gap-3 shadow-sm w-max cursor-default">
                                <div class="w-10 h-10 rounded-[0.75rem] flex items-center justify-center shadow-sm border {{ $dateLabel === 'Belum Dijadwalkan' ? 'bg-rose-50 border-rose-100 text-rose-500' : 'bg-[#e5eff5] border-[#56bbf1]/30 text-[#0d52a1]' }}">
                                    @if($dateLabel === 'Belum Dijadwalkan')
                                        <i class="ph-bold ph-calendar-slash text-xl"></i>
                                    @else
                                        <i class="ph-bold ph-calendar-check text-xl"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-[10px] uppercase font-black tracking-wider text-slate-400 mb-0.5">Jadwal Ujian</p>
                                    <h3 class="text-sm font-black {{ $dateLabel === 'Belum Dijadwalkan' ? 'text-rose-600' : 'text-[#2c3f61]' }}">{{ $dateLabel }}</h3>
                                </div>
                            </div>
                            <div class="flex-1 h-px bg-gradient-to-r from-slate-200 to-transparent"></div>
                        </div>
                    </div>

                    {{-- KARTU UJIAN --}}
                    @foreach($dailyExams as $exam)
                        <div class="bg-white border border-slate-100 rounded-[2.5rem] p-6 hover:shadow-xl hover:shadow-[#56bbf1]/10 hover:border-[#56bbf1]/50 transition-all duration-300 group relative flex flex-col h-full">
                            
                           {{-- SWITCH TOGGLE STATUS --}}
                           <div class="absolute top-6 right-6 z-10" title="Aktifkan / Nonaktifkan Ujian">
                                <label class="relative inline-flex items-center cursor-pointer group/toggle">
                                    <input type="checkbox" class="sr-only peer" 
                                           {{ $exam->is_active ? 'checked' : '' }} 
                                           data-url="{{ route('cbt.toggle_status', $exam->id) }}" 
                                           data-id="{{ $exam->id }}"
                                           onchange="toggleStatus(this)">
                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500 shadow-inner"></div>
                                </label>
                            </div>

                            <div class="mb-4 pr-16">
                                <div class="flex flex-wrap items-center gap-2 mb-3">
                                    <span class="inline-block px-3 py-1 bg-[#56bbf1]/10 text-[#0d52a1] border border-[#56bbf1]/20 rounded-lg text-[10px] font-black uppercase tracking-wide">
                                        {{ $exam->subject_name }}
                                    </span>
                                    @if(isset($exam->exam_type) && $exam->exam_type == 'google_form')
                                        <span class="inline-block px-3 py-1 bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-lg text-[10px] font-black uppercase tracking-wide">
                                            <i class="ph-bold ph-google-logo"></i> G-Form
                                        </span>
                                    @else
                                        <span class="inline-block px-3 py-1 bg-slate-100 text-slate-500 border border-slate-200 rounded-lg text-[10px] font-black uppercase tracking-wide">
                                            <i class="ph-bold ph-desktop"></i> CBT
                                        </span>
                                    @endif

                                    {{-- BADGE LABEL STATUS AKTIF --}}
                                    <span id="status-badge-{{ $exam->id }}" class="inline-block px-3 py-1 border rounded-lg text-[10px] font-black uppercase tracking-wide transition-colors {{ $exam->is_active ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100' }}">
                                        @if($exam->is_active)
                                            <i class="ph-bold ph-check-circle"></i> Aktif
                                        @else
                                            <i class="ph-bold ph-x-circle"></i> Tidak Aktif
                                        @endif
                                    </span>
                                </div>
                                <h4 class="font-black text-xl text-[#2c3f61] leading-tight group-hover:text-[#0d52a1] transition-colors line-clamp-2">{{ $exam->title }}</h4>
                            </div>

                            {{-- WAKTU MULAI DAN AKHIR --}}
                            <div class="mb-4 p-3 bg-slate-50 border border-slate-100 rounded-2xl space-y-2">
                                <div class="flex items-start text-xs font-bold text-slate-600">
                                    <i class="ph-fill ph-play-circle text-emerald-500 text-base mr-2 mt-0.5 shrink-0"></i>
                                    <span class="w-12 text-slate-400 shrink-0">Mulai</span>
                                    <span class="leading-tight">: {{ $exam->start_time ? \Carbon\Carbon::parse($exam->start_time)->locale('id')->isoFormat('dddd, D MMMM Y - HH:mm') : 'Belum diatur' }}</span>
                                </div>
                                <div class="flex items-start text-xs font-bold text-slate-600">
                                    <i class="ph-fill ph-stop-circle text-rose-500 text-base mr-2 mt-0.5 shrink-0"></i>
                                    <span class="w-12 text-slate-400 shrink-0">Akhir</span>
                                    <span class="leading-tight">: {{ $exam->end_time ? \Carbon\Carbon::parse($exam->end_time)->locale('id')->isoFormat('dddd, D MMMM Y - HH:mm') : 'Belum diatur' }}</span>
                                </div>
                            </div>
                            
                            <div class="flex-1 space-y-4">
                                <div class="bg-white border border-slate-200 rounded-2xl p-4 flex items-center justify-between group/token cursor-pointer hover:bg-[#e5eff5]/50 hover:border-[#56bbf1]/50 transition shadow-sm" onclick="copyToken('{{ $exam->token }}')">
                                    <div>
                                        <span class="text-[10px] text-slate-400 uppercase font-bold tracking-wider block mb-0.5">Token Ujian</span>
                                        <span class="font-mono font-black text-xl text-[#2c3f61] tracking-widest group-hover/token:text-[#0d52a1]">{{ $exam->token }}</span>
                                    </div>
                                    <div class="w-8 h-8 bg-slate-50 rounded-lg flex items-center justify-center text-slate-400 shadow-sm border border-slate-100 group-hover/token:text-[#0d52a1] transition"><i class="ph-bold ph-copy"></i></div>
                                </div>
                                <div class="flex items-center gap-4 text-xs text-slate-500 font-bold">
                                    <span class="flex items-center gap-1.5"><i class="ph-bold ph-users text-[#c86845]"></i> Kelas {{ $exam->class_level }}</span>
                                    <span class="flex items-center gap-1.5"><i class="ph-bold ph-clock text-[#56bbf1]"></i> {{ $exam->duration_minutes }} Menit</span>
                                </div>
                            </div>

                            <!-- Footer Actions -->
                            <div class="mt-5 pt-4 border-t border-slate-100 grid grid-cols-2 gap-2">
                                <!-- Baris 1: Soal & Monitor -->
                                @if(isset($exam->exam_type) && $exam->exam_type == 'google_form')
                                    <a href="{{ $exam->google_form_url }}" target="_blank" class="flex items-center justify-center p-2.5 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-xl text-xs font-bold hover:bg-emerald-600 hover:text-white transition-all group/btn" title="Buka Link Google Form">
                                        <i class="ph-bold ph-google-logo text-lg mr-2"></i> Form
                                    </a>
                                @else
                                    <a href="{{ route('cbt.questions.manage', $exam->id) }}" class="flex items-center justify-center p-2.5 bg-[#e5eff5] text-[#0d52a1] border border-[#56bbf1]/30 rounded-xl text-xs font-bold hover:bg-[#0d52a1] hover:text-white transition-all group/btn active:scale-95" title="Kelola Soal Ujian">
                                        <i class="ph-bold ph-list-numbers text-lg mr-2"></i> Soal
                                    </a>
                                @endif
                                <a href="{{ route('cbt.monitoring', $exam->id) }}" class="flex items-center justify-center p-2.5 bg-[#56bbf1]/10 text-[#0d52a1] rounded-xl text-xs font-bold hover:bg-[#56bbf1] hover:text-white transition-all active:scale-95">
                                    <i class="ph-bold ph-desktop text-lg mr-2"></i> Monitor
                                </a>

                                <!-- Baris 2: Rekap -->
                                <a href="{{ route('cbt.recap', $exam->id) }}" class="col-span-2 flex items-center justify-center p-2.5 bg-[#2c3f61] text-white rounded-xl text-xs font-bold hover:bg-[#1c2940] transition-all shadow-md shadow-[#2c3f61]/20 active:scale-95">
                                    <i class="ph-bold ph-chart-bar text-lg mr-2"></i> Rekapitulasi Nilai
                                </a>

                                <!-- Baris 3: SEB & Edit -->
                                <a href="{{ route('cbt.download_seb', $exam->id) }}" class="col-span-1 flex items-center justify-center p-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-50 hover:text-[#0d52a1] hover:border-[#56bbf1]/50 transition-all">
                                    <i class="ph-bold ph-file-lock text-lg mr-2"></i> SEB
                                </a>
                                <a href="{{ route('cbt.edit', $exam->id) }}" class="col-span-1 flex items-center justify-center p-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-50 hover:text-[#0d52a1] hover:border-[#56bbf1]/50 transition-all">
                                    <i class="ph-bold ph-pencil-simple text-lg mr-2"></i> Edit
                                </a>

                                <!-- Baris 4: Duplikat & Hapus -->
                                <form action="{{ route('cbt.clone', $exam->id) }}" method="POST" class="col-span-1">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center justify-center p-2.5 bg-white border border-[#f9a282]/50 text-[#c86845] rounded-xl text-xs font-bold hover:bg-[#f9a282]/10 transition-all active:scale-95" onclick="return confirm('Menduplikasi ujian ini beserta semua soalnya?')">
                                        <i class="ph-bold ph-copy text-lg mr-2"></i> Clone
                                    </button>
                                </form>
                                <button onclick="confirmDelete('{{ $exam->id }}')" class="col-span-1 flex items-center justify-center p-2.5 bg-white border border-rose-200 text-rose-500 rounded-xl text-xs font-bold hover:bg-rose-50 hover:text-rose-600 transition-all active:scale-95">
                                    <i class="ph-bold ph-trash text-lg mr-2"></i> Hapus
                                </button>

                                <form id="delete-form-{{ $exam->id }}" action="{{ route('cbt.destroy', $exam->id) }}" method="POST" class="hidden">
                                    @csrf @method('DELETE')
                                </form>
                                
                                <!-- Baris 5: Cetak Dokumen -->
                                <a href="{{ route('cbt.attendance', $exam->id) }}" target="_blank" class="col-span-1 flex items-center justify-center p-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-50 hover:text-[#0d52a1] hover:border-[#56bbf1]/50 transition-all">
                                    <i class="ph-bold ph-users-three text-lg mr-2"></i> Absen
                                </a>
                                <a href="{{ route('cbt.minutes', $exam->id) }}" target="_blank" class="col-span-1 flex items-center justify-center p-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-50 hover:text-[#0d52a1] hover:border-[#56bbf1]/50 transition-all">
                                    <i class="ph-bold ph-file-text text-lg mr-2"></i> Berita Acara
                                </a>
                            </div>
                        </div>
                    @endforeach
                    
               @empty
                    {{-- EMPTY STATE --}}
                    <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-20 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200 mt-4">
                        <div class="w-24 h-24 bg-[#e5eff5] rounded-full flex items-center justify-center mx-auto mb-6 text-[#0d52a1]/50">
                            <i class="ph-duotone ph-file-dashed text-5xl"></i>
                        </div>
                        <h3 class="text-[#2c3f61] font-bold text-xl mb-2">Folder Ini Masih Kosong</h3>
                        <p class="text-slate-500 max-w-xs mx-auto mb-8 text-sm">Silakan buat jadwal ujian baru yang akan dimasukkan ke dalam kegiatan <b>{{ $event->name }}</b>.</p>
                        <a href="{{ route('cbt.create', ['event_id' => $event->id]) }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-[#2c3f61] text-white rounded-xl font-bold hover:bg-[#1c2940] transition shadow-lg shadow-[#2c3f61]/30 text-sm">
                            <i class="ph-bold ph-plus"></i> Tambah Ujian Mapel
                        </a>
                    </div>
                @endforelse
            </div>
            
            {{-- PAGINATION --}}
            @if(method_exists($exams, 'links'))
                <div class="mt-8">{{ $exams->links() }}</div>
            @endif
        </div>
    </div>

    {{-- Script Validasi & Notifikasi --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function toggleStatus(element) {
            const url = element.getAttribute('data-url');
            const id = element.getAttribute('data-id');
            const isChecked = element.checked;

            fetch(url, {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            }).then(res => res.json()).then(data => {
                if (data.success) {
                    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: data.message, showConfirmButton: false, timer: 2000, customClass: { popup: 'rounded-xl' }});
                    
                    const badge = document.getElementById('status-badge-' + id);
                    if (badge) {
                        if (data.is_active) {
                            badge.className = 'inline-block px-3 py-1 border rounded-lg text-[10px] font-black uppercase tracking-wide transition-colors bg-emerald-50 text-emerald-600 border-emerald-100';
                            badge.innerHTML = '<i class="ph-bold ph-check-circle"></i> Aktif';
                        } else {
                            badge.className = 'inline-block px-3 py-1 border rounded-lg text-[10px] font-black uppercase tracking-wide transition-colors bg-rose-50 text-rose-600 border-rose-100';
                            badge.innerHTML = '<i class="ph-bold ph-x-circle"></i> Tidak Aktif';
                        }
                    }
                } else {
                    element.checked = !isChecked;
                }
            }).catch(error => {
                element.checked = !isChecked;
                console.error('Error:', error);
            });
        }
        
        function copyToken(token) {
            navigator.clipboard.writeText(token).then(() => {
                Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Token disalin!', showConfirmButton: false, timer: 2000, customClass: { popup: 'rounded-xl' }});
            });
        }
        
        function confirmDelete(id) {
            Swal.fire({ title: 'Hapus Ujian?', text: "Data ujian, jawaban, dan nilai dihapus permanen!", icon: 'warning', showCancelButton: true, confirmButtonColor: '#e11d48', cancelButtonColor: '#64748b', confirmButtonText: 'Hapus!', cancelButtonText: 'Batal', customClass: { popup: 'rounded-[2rem]' }
            }).then((result) => { if (result.isConfirmed) document.getElementById('delete-form-' + id).submit(); })
        }
        
        document.addEventListener("DOMContentLoaded", function() {
            const flash = document.getElementById('flash-success');
            if (flash) Swal.fire({ icon: 'success', title: 'Berhasil!', text: flash.getAttribute('data-message'), timer: 3000, showConfirmButton: false, customClass: { popup: 'rounded-[2rem]' }});
        });
    </script>
</x-app-layout>