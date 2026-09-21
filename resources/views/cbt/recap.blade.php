<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between print:hidden">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Rekapitulasi Nilai') }}
            </h2>
        </div>
    </x-slot>

    {{-- Tambahkan Style khusus Print --}}
    <style>
        @media print {
            body { background: white !important; color: black !important; }
            .no-print { display: none !important; }
            .print-area { box-shadow: none !important; border: none !important; background: white !important; }
            table { width: 100%; font-size: 12px; color: black !important; }
            th, td { border: 1px solid #ddd !important; padding: 8px !important; color: black !important; }
        }
    </style>

    <div class="min-h-screen bg-[#020b18] text-slate-100 relative overflow-hidden py-8 sm:py-10 font-sans" x-data="{ search: '' }">
        {{-- Ambient Glow Effects --}}
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-600/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/3 right-10 w-96 h-96 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 relative z-10">

            {{-- HERO SECTION ELEVATE DARK GLASS --}}
            <div class="relative rounded-[2.5rem] bg-gradient-to-r from-[#031d3d]/90 via-[#021124]/90 to-[#020b18]/90 p-8 text-white shadow-2xl backdrop-blur-xl border border-white/10 overflow-hidden print:hidden">
                <div class="absolute -top-10 -left-10 w-48 h-48 bg-[#56bbf1]/10 rounded-3xl rotate-12 pointer-events-none blur-2xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-blue-600/10 rounded-[3rem] -rotate-12 pointer-events-none blur-2xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <a href="{{ route('cbt.index') }}" class="text-xs font-bold text-sky-400 hover:text-white transition flex items-center gap-1 bg-white/5 hover:bg-white/10 px-3 py-1.5 rounded-full border border-white/10 backdrop-blur-md">
                                <i class="ph-bold ph-arrow-left"></i> Dashboard
                            </a>
                            <span class="text-slate-600 text-xs">•</span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Laporan Hasil</span>
                        </div>
                        <h1 class="text-3xl font-extrabold tracking-tight leading-none text-white mb-1">{{ $exam->title }}</h1>
                        <p class="text-slate-400 text-sm font-medium">Mapel: {{ $exam->subject_name }} • Kelas {{ $exam->class_level }}</p>
                    </div>

                    {{-- Tombol Aksi (Hanya muncul jika BUKAN Google Form) --}}
                    @if(!isset($exam->exam_type) || $exam->exam_type !== 'google_form')
                    <div class="flex flex-wrap gap-3">
                        
                        {{-- Tombol Analisis Butir Soal --}}
                        <a href="{{ route('cbt.analysis', $exam->id) }}" class="group px-5 py-3 bg-white/5 hover:bg-white/10 text-sky-400 font-bold rounded-2xl transition flex items-center gap-2 border border-white/10 backdrop-blur-md shadow-sm">
                            <i class="ph-duotone ph-chart-pie-slice text-xl text-[#56bbf1]"></i>
                            <span class="hidden sm:inline">Analisis Soal</span>
                        </a>

                        {{-- Tombol Export Excel --}}
                        <a href="{{ route('cbt.export', ['id' => $exam->id, 'type' => 'excel']) }}" target="_blank" class="group px-5 py-3 bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 font-bold rounded-2xl transition flex items-center gap-2 border border-emerald-500/30 backdrop-blur-md shadow-sm">
                            <i class="ph-duotone ph-microsoft-excel-logo text-xl group-hover:scale-110 transition-transform text-emerald-400"></i> 
                            <span class="hidden sm:inline">Excel</span>
                        </a>
                        
                        {{-- Tombol Export PDF --}}
                        <a href="{{ route('cbt.export', ['id' => $exam->id, 'type' => 'pdf']) }}" target="_blank" class="group px-5 py-3 bg-rose-500/20 hover:bg-rose-500/30 text-rose-300 font-bold rounded-2xl transition flex items-center gap-2 border border-rose-500/30 backdrop-blur-md shadow-sm">
                            <i class="ph-duotone ph-file-pdf text-xl group-hover:scale-110 transition-transform text-rose-400"></i> 
                            <span class="hidden sm:inline">PDF</span>
                        </a>

                        {{-- Tombol Posting ke Gradebook --}}
                        <button type="button" onclick="confirmSync()" class="group px-5 py-3 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white font-bold rounded-2xl transition flex items-center gap-2 shadow-lg shadow-sky-500/25 border border-sky-400/30">
                            <i class="ph-bold ph-book-bookmark text-xl group-hover:scale-110 transition-transform"></i>
                            <span class="hidden sm:inline">Post Nilai</span>
                        </button>
                        
                        {{-- Hidden Form untuk Sync --}}
                        <form id="syncForm" action="{{ route('cbt.sync_grades', $exam->id) }}" method="POST" class="hidden">
                            @csrf
                        </form>

                        {{-- Tombol Print Browser --}}
                        <button onclick="window.print()" class="group px-5 py-3 bg-white/5 hover:bg-white/10 text-slate-300 font-bold rounded-2xl transition flex items-center gap-2 border border-white/10 shadow-sm" title="Cetak Halaman">
                            <i class="ph-bold ph-printer text-xl"></i>
                        </button>
                    </div>
                    @endif
                </div>
            </div>

            {{-- PENGECEKAN TIPE UJIAN (BLOCK JIKA GOOGLE FORM) --}}
            @if(isset($exam->exam_type) && $exam->exam_type == 'google_form')
                <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] p-12 text-center border border-emerald-500/30 shadow-2xl backdrop-blur-xl relative overflow-hidden print:hidden mt-8">
                    <div class="absolute -top-24 -right-24 w-64 h-64 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="w-24 h-24 bg-emerald-500/20 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-emerald-400 border border-emerald-500/30 relative z-10">
                        <i class="ph-duotone ph-google-logo text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-2 relative z-10">Rekapitulasi Nilai Google Form</h3>
                    <p class="text-slate-400 font-medium max-w-lg mx-auto mb-8 relative z-10">Ujian ini diselenggarakan melalui tautan Google Formulir. Seluruh data jawaban, analisis butir soal, dan rekapitulasi nilai dapat Anda kelola secara langsung melalui dashboard Google Workspace (Google Drive/Classroom) Anda.</p>
                    
                    <a href="{{ $exam->google_form_url }}" target="_blank" class="inline-flex items-center justify-center px-8 py-4 bg-emerald-500 text-white font-bold rounded-xl hover:bg-emerald-400 transition shadow-lg shadow-emerald-500/30 gap-2 relative z-10 active:scale-95">
                        <i class="ph-bold ph-arrow-square-out text-xl"></i> Buka Hasil di Google Form
                    </a>
                </div>
            @else
                {{-- KONTEN REKAP CBT (NORMAL) --}}

                {{-- HEADER PRINT ONLY --}}
                <div class="hidden print:block text-center mb-6">
                    <h2 class="text-2xl font-bold uppercase">Laporan Hasil Ujian</h2>
                    <h3 class="text-xl">{{ $exam->title }} - {{ $exam->subject_name }}</h3>
                    <p>Kelas: {{ $exam->class_level }} | Tanggal Cetak: {{ date('d-m-Y') }}</p>
                </div>

                {{-- STATISTIK CARDS --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 print:grid-cols-4 print:gap-2">
                    {{-- Rata-rata --}}
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-5 rounded-[2.5rem] border border-white/10 shadow-2xl backdrop-blur-xl print:border-black print:rounded-none print:shadow-none print:bg-white">
                        <div class="flex items-center gap-3 mb-2 print:hidden">
                            <div class="w-10 h-10 rounded-xl bg-sky-500/20 text-[#56bbf1] border border-sky-500/20 flex items-center justify-center"><i class="ph-bold ph-chart-line-up text-lg"></i></div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Rata-rata</span>
                        </div>
                        <div class="hidden print:block text-xs font-bold uppercase mb-1">Rata-rata</div>
                        <p class="text-3xl font-black text-white print:text-black">{{ number_format($stats['average'], 1) }}</p>
                    </div>

                    {{-- Tertinggi --}}
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-5 rounded-[2.5rem] border border-white/10 shadow-2xl backdrop-blur-xl print:border-black print:rounded-none print:shadow-none print:bg-white">
                        <div class="flex items-center gap-3 mb-2 print:hidden">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/20 flex items-center justify-center"><i class="ph-bold ph-crown text-lg"></i></div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tertinggi</span>
                        </div>
                        <div class="hidden print:block text-xs font-bold uppercase mb-1">Tertinggi</div>
                        <p class="text-3xl font-black text-white print:text-black">{{ $stats['max_score'] }}</p>
                    </div>

                    {{-- Terendah --}}
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-5 rounded-[2.5rem] border border-white/10 shadow-2xl backdrop-blur-xl print:border-black print:rounded-none print:shadow-none print:bg-white">
                        <div class="flex items-center gap-3 mb-2 print:hidden">
                            <div class="w-10 h-10 rounded-xl bg-rose-500/20 text-rose-400 border border-rose-500/20 flex items-center justify-center"><i class="ph-bold ph-trend-down text-lg"></i></div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Terendah</span>
                        </div>
                        <div class="hidden print:block text-xs font-bold uppercase mb-1">Terendah</div>
                        <p class="text-3xl font-black text-white print:text-black">{{ $stats['min_score'] }}</p>
                    </div>

                    {{-- Total Peserta --}}
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-5 rounded-[2.5rem] border border-white/10 shadow-2xl backdrop-blur-xl print:border-black print:rounded-none print:shadow-none print:bg-white">
                        <div class="flex items-center gap-3 mb-2 print:hidden">
                            <div class="w-10 h-10 rounded-xl bg-white/5 text-[#56bbf1] border border-white/10 flex items-center justify-center"><i class="ph-bold ph-users text-lg"></i></div>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Peserta</span>
                        </div>
                        <div class="hidden print:block text-xs font-bold uppercase mb-1">Total Peserta</div>
                        <div class="flex items-end justify-between mt-1">
                            <p class="text-3xl font-black text-white print:text-black">{{ $results->count() }} <span class="text-sm text-slate-400 font-bold print:hidden">Siswa</span></p>
                            
                            {{-- Indikator Persentase Kelulusan Visual --}}
                            @php
                                $lulusCount = $results->where('total_score', '>=', $exam->passing_grade)->count();
                                $passRate = $results->count() > 0 ? round(($lulusCount / $results->count()) * 100) : 0;
                            @endphp
                            <div class="text-right print:hidden">
                                <span class="text-[10px] font-bold text-emerald-400">{{ $passRate }}% Lulus</span>
                                <div class="w-16 h-1.5 bg-slate-900 rounded-full mt-1 overflow-hidden border border-white/5" title="{{ $lulusCount }} Siswa Lulus">
                                    <div class="h-full bg-emerald-400" style="width: {{ $passRate }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TABEL HASIL --}}
                <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] border border-white/10 shadow-2xl backdrop-blur-xl overflow-hidden print:shadow-none print:border-none print:rounded-none print-area">
                    <div class="p-6 border-b border-white/10 bg-white/5 flex flex-col md:flex-row justify-between items-center gap-4 print:hidden">
                        <h4 class="font-bold text-white flex items-center gap-2 text-lg">
                            <i class="ph-fill ph-trophy text-amber-400"></i> Peringkat Hasil
                        </h4>
                        <div class="relative w-full md:w-72">
                            <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" x-model="search" placeholder="Cari nama siswa..." class="w-full pl-10 pr-4 py-2.5 text-sm font-bold border-white/10 rounded-xl focus:ring-[#56bbf1] focus:border-[#56bbf1] bg-slate-900/90 text-white placeholder:text-slate-500 shadow-sm transition-shadow [color-scheme:dark]">
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-300">
                            <thead class="bg-white/5 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-white/10 sticky top-0 z-10 print:static print:bg-white print:text-black">
                                <tr>
                                    <th class="px-6 py-4 text-center w-16">Rank</th>
                                    <th class="px-6 py-4">Nama Siswa</th>
                                    <th class="px-6 py-4 text-center">Percobaan</th>
                                    <th class="px-6 py-4 text-center">Benar / Salah</th>
                                    <th class="px-6 py-4 text-center">Nilai Akhir</th>
                                    <th class="px-6 py-4 text-center">Status</th>
                                    <th class="px-6 py-4 text-right print:hidden">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5 print:divide-gray-300">
                                @forelse($results as $index => $res)
                                    <tr x-show="search === '' || '{{ strtolower($res->student_name) }}'.includes(search.toLowerCase())" 
                                        class="hover:bg-white/5 transition group print:hover:bg-transparent">
                                        
                                        {{-- Ranking Badge --}}
                                        <td class="px-6 py-4 text-center">
                                            @if($index == 0)
                                                <div class="w-8 h-8 rounded-full bg-amber-500/20 text-amber-400 border border-amber-500/30 flex items-center justify-center mx-auto shadow-sm print:shadow-none print:bg-transparent print:text-black"><i class="ph-fill ph-crown"></i> 1</div>
                                            @elseif($index == 1)
                                                <div class="w-8 h-8 rounded-full bg-slate-400/20 text-slate-300 border border-slate-400/30 flex items-center justify-center mx-auto shadow-sm font-bold print:shadow-none print:bg-transparent print:text-black">2</div>
                                            @elseif($index == 2)
                                                <div class="w-8 h-8 rounded-full bg-amber-700/20 text-amber-500 border border-amber-700/30 flex items-center justify-center mx-auto shadow-sm font-bold print:shadow-none print:bg-transparent print:text-black">3</div>
                                            @else
                                                <span class="font-black text-slate-500 print:text-black">{{ $index + 1 }}</span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4">
                                            <p class="font-black text-white text-base print:text-black">{{ $res->student_name }}</p>
                                            <p class="text-xs text-slate-400 font-mono mt-0.5 print:text-black">{{ $res->student_nisn ?? 'NISN -' }}</p>
                                        </td>

                                        {{-- Kolom Indikator Percobaan --}}
                                        <td class="px-6 py-4 text-center">
                                            <span class="text-xs font-bold text-slate-300 bg-white/5 px-2.5 py-1 rounded-md border border-white/10 print:border-none print:bg-transparent print:p-0 print:text-black">
                                                {{ $res->attempt_count ?? 1 }}x
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            <div class="inline-flex items-center gap-2 bg-slate-900/80 rounded-xl p-1.5 border border-white/10 print:bg-transparent print:p-0 print:border-none">
                                                <span class="px-2 py-0.5 bg-emerald-500/20 text-emerald-400 rounded text-xs font-bold print:bg-transparent print:text-black print:border print:border-black" title="Benar">{{ $res->correct_answers ?? 0 }}</span>
                                                <span class="text-slate-600 print:text-black">/</span>
                                                <span class="px-2 py-0.5 bg-rose-500/20 text-rose-400 rounded text-xs font-bold print:bg-transparent print:text-black print:border print:border-black" title="Salah">{{ $res->wrong_answers ?? 0 }}</span>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            <span class="text-xl font-black {{ $res->total_score >= $exam->passing_grade ? 'text-emerald-400' : 'text-rose-400' }} print:text-black">
                                                {{ $res->total_score ?? 0 }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            @if(($res->total_score ?? 0) >= $exam->passing_grade)
                                                <span class="px-3 py-1 bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 rounded-full text-[10px] font-black uppercase tracking-wider print:bg-transparent print:text-black print:border-black">
                                                    Lulus
                                                </span>
                                            @else
                                                <span class="px-3 py-1 bg-rose-500/20 text-rose-300 border border-rose-500/30 rounded-full text-[10px] font-black uppercase tracking-wider print:bg-transparent print:text-black print:border-black">
                                                    Remedial
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Kolom Aksi (Hidden on Print) --}}
                                        <td class="px-6 py-4 text-right print:hidden">
                                            <div class="flex items-center justify-end gap-2">
                                                {{-- Tombol Detail --}}
                                                <a href="{{ route('cbt.result.detail', ['exam' => $exam->id, 'student' => $res->student_id]) }}" 
                                                   class="w-8 h-8 rounded-xl bg-white/5 border border-white/10 text-sky-400 hover:bg-white/10 transition inline-flex items-center justify-center shadow-sm" 
                                                   title="Lihat Detail Jawaban">
                                                    <i class="ph-bold ph-eye"></i>
                                                </a>
                                                
                                                {{-- Tombol Kerjakan Ulang --}}
                                                <button type="button" 
                                                    onclick="confirmRetake('{{ route('cbt.student.retake', ['exam' => $exam->id, 'student' => $res->student_id]) }}', '{{ addslashes($res->student_name) }}', {{ $res->attempt_count ?? 1 }})" 
                                                    class="w-8 h-8 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-400 hover:bg-amber-500/20 transition inline-flex items-center justify-center shadow-sm" 
                                                    title="Izinkan Kerjakan Ulang (Reset Ujian)">
                                                    <i class="ph-bold ph-arrow-counter-clockwise"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-12 text-center">
                                            <div class="w-16 h-16 bg-white/5 border border-white/10 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-500">
                                                <i class="ph-duotone ph-file-x text-3xl"></i>
                                            </div>
                                            <p class="text-slate-400 font-bold">Belum ada data nilai masuk.</p>
                                        </td>
                                    </tr>
                                @endforelse
                                
                                {{-- Notifikasi jika SEARCH tidak menemukan hasil (Alpine JS Logic) --}}
                                <tr x-show="search !== '' && $el.parentElement.querySelectorAll('tr[x-show]').length > 0 && Array.from($el.parentElement.querySelectorAll('tr')).filter(r => r.style.display !== 'none' && !r.hasAttribute('x-show-empty')).length === 0" 
                                    x-show-empty 
                                    style="display: none;">
                                    <td colspan="7" class="px-6 py-8 text-center text-slate-500">
                                        <p class="font-medium">Tidak ditemukan siswa dengan nama "<span x-text="search" class="font-bold text-white"></span>"</p>
                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- SCRIPTS (SWEETALERT2) --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // FUNGSI KONFIRMASI KERJAKAN ULANG
        function confirmRetake(url, studentName, currentAttempt) {
            const nextAttempt = currentAttempt + 1;
            
            let attemptText = `percobaan ke-<b>${nextAttempt}</b>`;
            if (nextAttempt === 2) {
                attemptText = `mengerjakan soal untuk yang <b>kedua kalinya</b>`;
            }
            
            Swal.fire({
                title: 'Izinkan Ujian Ulang?',
                html: `Siswa <b class="text-amber-400">${studentName}</b> sudah menyelesaikan ujian ini.<br><br>Jika Anda mengizinkan, siswa akan ${attemptText}. Data jawaban sebelumnya akan di-reset. Apakah Anda yakin?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f59e0b',
                cancelButtonColor: '#475569',
                confirmButtonText: '<i class="ph-bold ph-arrow-counter-clockwise"></i> Ya, Izinkan!',
                cancelButtonText: 'Batal',
                background: '#0f172a',
                color: '#f8fafc',
                customClass: { 
                    popup: 'rounded-[2rem] border border-white/10 shadow-2xl',
                    confirmButton: 'rounded-xl px-4 py-2 font-bold',
                    cancelButton: 'rounded-xl px-4 py-2 font-bold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    
                    const csrf = document.createElement('input');
                    csrf.type = 'hidden';
                    csrf.name = '_token';
                    csrf.value = '{{ csrf_token() }}';
                    
                    form.appendChild(csrf);
                    document.body.appendChild(form);
                    
                    Swal.fire({ 
                        title: 'Memproses Izin...', 
                        allowOutsideClick: false, 
                        didOpen: () => Swal.showLoading(), 
                        background: '#0f172a',
                        color: '#f8fafc',
                        customClass: { popup: 'rounded-[2rem] border border-white/10' } 
                    });
                    
                    form.submit();
                }
            });
        }

        // FUNGSI KONFIRMASI POSTING NILAI
        function confirmSync() {
            Swal.fire({
                title: 'Posting Nilai?',
                text: "Nilai ujian ini akan disinkronkan ke Buku Nilai (Gradebook/LMS). Nilai lama (jika ada) akan ditimpa.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0284c7',
                cancelButtonColor: '#475569',
                confirmButtonText: 'Ya, Posting!',
                cancelButtonText: 'Batal',
                background: '#0f172a',
                color: '#f8fafc',
                customClass: {
                    popup: 'rounded-[2rem] border border-white/10 shadow-2xl',
                    confirmButton: 'rounded-xl px-4 py-2 font-bold',
                    cancelButton: 'rounded-xl px-4 py-2 font-bold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        html: 'Sedang memposting nilai ke Gradebook.',
                        timerProgressBar: true,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                        background: '#0f172a',
                        color: '#f8fafc',
                        customClass: {
                            popup: 'rounded-[2rem] border border-white/10'
                        }
                    });
                    document.getElementById('syncForm').submit();
                }
            })
        }

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false,
                background: '#0f172a',
                color: '#f8fafc',
                customClass: { popup: 'rounded-[2rem] border border-white/10 shadow-2xl' }
            });
        @endif
        
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                background: '#0f172a',
                color: '#f8fafc',
                customClass: { popup: 'rounded-[2rem] border border-white/10 shadow-2xl' }
            });
        @endif
    </script>
    @endpush

</x-app-layout>