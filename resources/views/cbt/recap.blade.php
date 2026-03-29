<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between print:hidden">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Rekapitulasi Nilai') }}
            </h2>
        </div>
    </x-slot>

    {{-- Tambahkan Style khusus Print --}}
    <style>
        @media print {
            body { background: white; }
            .no-print { display: none !important; }
            .print-area { box-shadow: none !important; border: none !important; }
            table { width: 100%; font-size: 12px; color: black; }
            th, td { border: 1px solid #ddd !important; padding: 8px !important; }
        }
    </style>

    <div class="py-8 sm:py-10 font-sans text-slate-800" x-data="{ search: '' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- HERO SECTION --}}
            <div class="relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-slate-900 via-indigo-900 to-purple-900 p-8 text-white shadow-xl shadow-indigo-900/30 overflow-hidden border border-white/10 print:hidden">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <a href="{{ route('cbt.index') }}" class="text-xs font-bold text-indigo-300 hover:text-white transition flex items-center gap-1">
                                <i class="ph-bold ph-arrow-left"></i> Dashboard
                            </a>
                            <span class="text-white/30 text-xs">•</span>
                            <span class="text-[10px] font-bold text-indigo-200 uppercase tracking-wider">Laporan Hasil</span>
                        </div>
                        <h1 class="text-3xl font-black tracking-tight leading-none text-white mb-1">{{ $exam->title }}</h1>
                        <p class="text-indigo-200 text-sm font-medium">Mapel: {{ $exam->subject_name }} • Kelas {{ $exam->class_level }}</p>
                    </div>

                    {{-- Tombol Aksi (Hanya muncul jika BUKAN Google Form) --}}
                    @if(!isset($exam->exam_type) || $exam->exam_type !== 'google_form')
                    <div class="flex flex-wrap gap-3">
                        
                        {{-- Tombol Analisis Butir Soal --}}
                        <a href="{{ route('cbt.analysis', $exam->id) }}" class="group px-5 py-3 bg-white text-indigo-900 font-bold rounded-2xl hover:bg-indigo-50 transition flex items-center gap-2 shadow-lg shadow-black/10">
                            <i class="ph-duotone ph-chart-pie-slice text-xl"></i>
                            <span class="hidden sm:inline">Analisis Soal</span>
                        </a>

                        {{-- Tombol Export Excel --}}
                        <a href="{{ route('cbt.export', ['id' => $exam->id, 'type' => 'excel']) }}" target="_blank" class="group px-5 py-3 bg-emerald-600 text-white font-bold rounded-2xl hover:bg-emerald-500 transition flex items-center gap-2 shadow-lg shadow-emerald-900/20">
                            <i class="ph-duotone ph-microsoft-excel-logo text-xl group-hover:scale-110 transition-transform"></i> 
                            <span class="hidden sm:inline">Excel</span>
                        </a>
                        
                        {{-- Tombol Export PDF --}}
                        <a href="{{ route('cbt.export', ['id' => $exam->id, 'type' => 'pdf']) }}" target="_blank" class="group px-5 py-3 bg-rose-600 text-white font-bold rounded-2xl hover:bg-rose-500 transition flex items-center gap-2 shadow-lg shadow-rose-900/20">
                            <i class="ph-duotone ph-file-pdf text-xl group-hover:scale-110 transition-transform"></i> 
                            <span class="hidden sm:inline">PDF</span>
                        </a>

                        {{-- Tombol Posting ke Gradebook --}}
                        <button type="button" onclick="confirmSync()" class="group px-5 py-3 bg-amber-500 text-white font-bold rounded-2xl hover:bg-amber-400 transition flex items-center gap-2 shadow-lg shadow-amber-900/20 border border-amber-400">
                            <i class="ph-bold ph-book-bookmark text-xl group-hover:scale-110 transition-transform"></i>
                            <span class="hidden sm:inline">Post Nilai</span>
                        </button>
                        
                        {{-- Hidden Form untuk Sync --}}
                        <form id="syncForm" action="{{ route('cbt.sync_grades', $exam->id) }}" method="POST" class="hidden">
                            @csrf
                        </form>

                        {{-- Tombol Print Browser --}}
                        <button onclick="window.print()" class="group px-5 py-3 bg-white/10 backdrop-blur-md text-white font-bold rounded-2xl hover:bg-white/20 transition flex items-center gap-2 border border-white/10" title="Cetak Halaman">
                            <i class="ph-bold ph-printer text-xl"></i>
                        </button>
                    </div>
                    @endif
                </div>
            </div>

            {{-- PENGECEKAN TIPE UJIAN (BLOCK JIKA GOOGLE FORM) --}}
            @if(isset($exam->exam_type) && $exam->exam_type == 'google_form')
                <div class="bg-white rounded-[2.5rem] p-12 text-center border border-emerald-200 shadow-xl shadow-emerald-100/50 relative overflow-hidden print:hidden mt-8">
                    <div class="absolute -top-24 -right-24 w-64 h-64 bg-emerald-50 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="w-24 h-24 bg-emerald-100 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-emerald-500 shadow-inner relative z-10">
                        <i class="ph-duotone ph-google-logo text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-slate-800 mb-2 relative z-10">Rekapitulasi Nilai Google Form</h3>
                    <p class="text-slate-500 font-medium max-w-lg mx-auto mb-8 relative z-10">Ujian ini diselenggarakan melalui tautan Google Formulir. Seluruh data jawaban, analisis butir soal, dan rekapitulasi nilai dapat Anda kelola secara langsung melalui dashboard Google Workspace (Google Drive/Classroom) Anda.</p>
                    
                    <a href="{{ $exam->google_form_url }}" target="_blank" class="inline-flex items-center justify-center px-8 py-4 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition shadow-lg shadow-emerald-600/30 gap-2 relative z-10 active:scale-95">
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
                    <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm print:border-black print:rounded-none">
                        <div class="flex items-center gap-3 mb-2 print:hidden">
                            <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center"><i class="ph-bold ph-chart-line-up"></i></div>
                            <span class="text-xs font-bold text-slate-400 uppercase">Rata-rata</span>
                        </div>
                        <div class="hidden print:block text-xs font-bold uppercase mb-1">Rata-rata</div>
                        <p class="text-2xl font-black text-slate-800">{{ number_format($stats['average'], 1) }}</p>
                    </div>

                    {{-- Tertinggi --}}
                    <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm print:border-black print:rounded-none">
                        <div class="flex items-center gap-3 mb-2 print:hidden">
                            <div class="w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center"><i class="ph-bold ph-crown"></i></div>
                            <span class="text-xs font-bold text-slate-400 uppercase">Tertinggi</span>
                        </div>
                        <div class="hidden print:block text-xs font-bold uppercase mb-1">Tertinggi</div>
                        <p class="text-2xl font-black text-slate-800">{{ $stats['max_score'] }}</p>
                    </div>

                    {{-- Terendah --}}
                    <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm print:border-black print:rounded-none">
                        <div class="flex items-center gap-3 mb-2 print:hidden">
                            <div class="w-8 h-8 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center"><i class="ph-bold ph-trend-down"></i></div>
                            <span class="text-xs font-bold text-slate-400 uppercase">Terendah</span>
                        </div>
                        <div class="hidden print:block text-xs font-bold uppercase mb-1">Terendah</div>
                        <p class="text-2xl font-black text-slate-800">{{ $stats['min_score'] }}</p>
                    </div>

                    {{-- Total Peserta --}}
                    <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm print:border-black print:rounded-none">
                        <div class="flex items-center gap-3 mb-2 print:hidden">
                            <div class="w-8 h-8 rounded-full bg-purple-50 text-purple-600 flex items-center justify-center"><i class="ph-bold ph-users"></i></div>
                            <span class="text-xs font-bold text-slate-400 uppercase">Peserta</span>
                        </div>
                        <div class="hidden print:block text-xs font-bold uppercase mb-1">Total Peserta</div>
                        <div class="flex items-end justify-between">
                            <p class="text-2xl font-black text-slate-800">{{ $results->count() }} <span class="text-sm text-slate-400 font-bold print:hidden">Siswa</span></p>
                            
                            {{-- Indikator Persentase Kelulusan Visual --}}
                            @php
                                $lulusCount = $results->where('total_score', '>=', $exam->passing_grade)->count();
                                $passRate = $results->count() > 0 ? round(($lulusCount / $results->count()) * 100) : 0;
                            @endphp
                            <div class="text-right print:hidden">
                                <span class="text-[10px] font-bold text-emerald-500">{{ $passRate }}% Lulus</span>
                                <div class="w-16 h-1.5 bg-slate-100 rounded-full mt-1 overflow-hidden" title="{{ $lulusCount }} Siswa Lulus">
                                    <div class="h-full bg-emerald-500" style="width: {{ $passRate }}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TABEL HASIL --}}
                <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden print:shadow-none print:border-none print:rounded-none print-area">
                    <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row justify-between items-center gap-4 print:hidden">
                        <h4 class="font-bold text-slate-700 flex items-center gap-2 text-lg">
                            <i class="ph-fill ph-trophy text-amber-500"></i> Peringkat Hasil
                        </h4>
                        <div class="relative w-full md:w-72">
                            <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" x-model="search" placeholder="Cari nama siswa..." class="w-full pl-10 pr-4 py-2.5 text-sm font-bold border-slate-200 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 bg-white shadow-sm transition-shadow">
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-600">
                            <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100 sticky top-0 z-10 print:static print:bg-white print:text-black">
                                <tr>
                                    <th class="px-6 py-4 text-center w-16">Rank</th>
                                    <th class="px-6 py-4">Nama Siswa</th>
                                    <th class="px-6 py-4 text-center">Percobaan</th> {{-- KOLOM BARU --}}
                                    <th class="px-6 py-4 text-center">Benar / Salah</th>
                                    <th class="px-6 py-4 text-center">Nilai Akhir</th>
                                    <th class="px-6 py-4 text-center">Status</th>
                                    <th class="px-6 py-4 text-right print:hidden">Aksi</th> {{-- DIUBAH DARI DETAIL --}}
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 print:divide-gray-300">
                                @forelse($results as $index => $res)
                                    <tr x-show="search === '' || '{{ strtolower($res->student_name) }}'.includes(search.toLowerCase())" 
                                        class="hover:bg-indigo-50/30 transition group print:hover:bg-transparent">
                                        
                                        {{-- Ranking Badge --}}
                                        <td class="px-6 py-4 text-center">
                                            @if($index == 0)
                                                <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center mx-auto shadow-sm print:shadow-none print:bg-transparent print:text-black"><i class="ph-fill ph-crown"></i> 1</div>
                                            @elseif($index == 1)
                                                <div class="w-8 h-8 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center mx-auto shadow-sm font-bold print:shadow-none print:bg-transparent print:text-black">2</div>
                                            @elseif($index == 2)
                                                <div class="w-8 h-8 rounded-full bg-orange-100 text-orange-700 flex items-center justify-center mx-auto shadow-sm font-bold print:shadow-none print:bg-transparent print:text-black">3</div>
                                            @else
                                                <span class="font-bold text-slate-400 print:text-black">{{ $index + 1 }}</span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4">
                                            <p class="font-bold text-slate-800 text-base print:text-black">{{ $res->student_name }}</p>
                                            <p class="text-xs text-slate-400 font-mono mt-0.5 print:text-black">{{ $res->student_nisn ?? 'NISN -' }}</p>
                                        </td>

                                        {{-- Kolom Indikator Percobaan --}}
                                        <td class="px-6 py-4 text-center">
                                            <span class="text-xs font-bold text-slate-500 bg-slate-100 px-2.5 py-1 rounded-md border border-slate-200 print:border-none print:bg-transparent print:p-0 print:text-black">
                                                {{ $res->attempt_count ?? 1 }}x
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            <div class="inline-flex items-center gap-2 bg-slate-100 rounded-lg p-1.5 print:bg-transparent print:p-0">
                                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded text-xs font-bold print:bg-transparent print:text-black print:border print:border-black" title="Benar">{{ $res->correct_answers ?? 0 }}</span>
                                                <span class="text-slate-300 print:text-black">/</span>
                                                <span class="px-2 py-0.5 bg-rose-100 text-rose-700 rounded text-xs font-bold print:bg-transparent print:text-black print:border print:border-black" title="Salah">{{ $res->wrong_answers ?? 0 }}</span>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            <span class="text-xl font-black {{ $res->total_score >= $exam->passing_grade ? 'text-emerald-600' : 'text-rose-500' }} print:text-black">
                                                {{ $res->total_score ?? 0 }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            @if(($res->total_score ?? 0) >= $exam->passing_grade)
                                                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-full text-[10px] font-black uppercase tracking-wider print:bg-transparent print:text-black print:border-black">
                                                    Lulus
                                                </span>
                                            @else
                                                <span class="px-3 py-1 bg-rose-50 text-rose-600 border border-rose-100 rounded-full text-[10px] font-black uppercase tracking-wider print:bg-transparent print:text-black print:border-black">
                                                    Remedial
                                                </span>
                                            @endif
                                        </td>

                                        {{-- Kolom Aksi (Hidden on Print) --}}
                                        <td class="px-6 py-4 text-right print:hidden">
                                            <div class="flex items-center justify-end gap-2">
                                                {{-- Tombol Detail --}}
                                                <a href="{{ route('cbt.result.detail', ['exam' => $exam->id, 'student' => $res->student_id]) }}" 
                                                   class="w-8 h-8 rounded-xl bg-white border border-slate-200 text-slate-400 hover:bg-indigo-600 hover:text-white hover:border-indigo-600 transition inline-flex items-center justify-center shadow-sm" 
                                                   title="Lihat Detail Jawaban">
                                                    <i class="ph-bold ph-eye"></i>
                                                </a>
                                                
                                                {{-- Tombol Kerjakan Ulang --}}
                                                <button type="button" 
                                                    onclick="confirmRetake('{{ route('cbt.student.retake', ['exam' => $exam->id, 'student' => $res->student_id]) }}', '{{ addslashes($res->student_name) }}', {{ $res->attempt_count ?? 1 }})" 
                                                    class="w-8 h-8 rounded-xl bg-white border border-slate-200 text-amber-500 hover:bg-amber-500 hover:text-white hover:border-amber-500 transition inline-flex items-center justify-center shadow-sm" 
                                                    title="Izinkan Kerjakan Ulang (Reset Ujian)">
                                                    <i class="ph-bold ph-arrow-counter-clockwise"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        {{-- Updated colspan to 7 --}}
                                        <td colspan="7" class="px-6 py-12 text-center">
                                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-300">
                                                <i class="ph-duotone ph-file-x text-3xl"></i>
                                            </div>
                                            <p class="text-slate-500 font-bold">Belum ada data nilai masuk.</p>
                                        </td>
                                    </tr>
                                @endforelse
                                
                                {{-- Notifikasi jika SEARCH tidak menemukan hasil (Alpine JS Logic) --}}
                                <tr x-show="search !== '' && $el.parentElement.querySelectorAll('tr[x-show]').length > 0 && Array.from($el.parentElement.querySelectorAll('tr')).filter(r => r.style.display !== 'none' && !r.hasAttribute('x-show-empty')).length === 0" 
                                    x-show-empty 
                                    style="display: none;">
                                    {{-- Updated colspan to 7 --}}
                                    <td colspan="7" class="px-6 py-8 text-center text-slate-500">
                                        <p class="font-medium">Tidak ditemukan siswa dengan nama "<span x-text="search" class="font-bold text-slate-800"></span>"</p>
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
            
            // Format teks dinamis menyesuaikan apakah ini ujian ke-2 atau lebih
            let attemptText = `percobaan ke-<b>${nextAttempt}</b>`;
            if (nextAttempt === 2) {
                attemptText = `mengerjakan soal untuk yang <b>kedua kalinya</b>`;
            }
            
            Swal.fire({
                title: 'Izinkan Ujian Ulang?',
                html: `Siswa <b>${studentName}</b> sudah menyelesaikan ujian ini.<br><br>Jika Anda mengizinkan, siswa akan ${attemptText}. Data jawaban sebelumnya akan di-reset. Apakah Anda yakin?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f59e0b', // Amber sesuai tombol
                cancelButtonColor: '#64748b',
                confirmButtonText: '<i class="ph-bold ph-arrow-counter-clockwise"></i> Ya, Izinkan!',
                cancelButtonText: 'Batal',
                customClass: { 
                    popup: 'rounded-[1.5rem]',
                    confirmButton: 'rounded-xl px-4 py-2 font-bold',
                    cancelButton: 'rounded-xl px-4 py-2 font-bold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Membuat form hidden agar request terkirim sebagai POST (Aman dari serangan GET / Link Injection)
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
                        customClass: { popup: 'rounded-[1.5rem]' } 
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
                confirmButtonColor: '#f59e0b',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Posting!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-[1.5rem]',
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
                        customClass: {
                            popup: 'rounded-[1.5rem]'
                        }
                    });
                    document.getElementById('syncForm').submit();
                }
            })
        }

        // Tampilkan Flash Message dari Session (Success/Error)
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                timer: 3000,
                showConfirmButton: false,
                customClass: { popup: 'rounded-[1.5rem]' }
            });
        @endif
        
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: "{{ session('error') }}",
                customClass: { popup: 'rounded-[1.5rem]' }
            });
        @endif
    </script>
    @endpush

</x-app-layout>