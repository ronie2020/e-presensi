{{-- Halaman ini adalah tampilan untuk resources/views/discipline/index.blade.php --}}
<x-app-layout>
    {{-- 
        FIX: Script dipindah langsung ke sini (keluar dari @push) 
        untuk memastikan library terbaca meskipun Layout utama tidak memiliki stack scripts.
    --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    
    <style>
        /* Fix style agar kamera scanner mengisi area dengan pas */
        #reader video {
            object-fit: cover;
            width: 100% !important;
            height: 100% !important;
            border-radius: 0.75rem;
        }
        #reader {
            width: 100%;
        }
    </style>

    <div class="py-6 sm:py-8">
        
        {{-- Header Page --}}
        <div class="mb-8 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-gray-800 tracking-tight leading-tight">
                    Catatan Kedisiplinan
                </h1>
                <p class="text-gray-500 mt-1">
                    Kelola poin pelanggaran dan prestasi siswa untuk membangun karakter positif.
                </p>
            </div>
            <a href="{{ route('discipline-types.index') }}" class="group flex items-center gap-2 bg-white border border-gray-200 text-gray-600 px-4 py-2.5 rounded-xl font-bold text-sm hover:bg-gray-50 hover:text-blue-600 transition-all shadow-sm">
                <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Kelola Jenis Pelanggaran
            </a>
        </div>

        {{-- Pesan Flash --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl font-medium text-sm flex justify-between items-center shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-400 hover:text-emerald-600">&times;</button>
            </div>
        @endif

        <!-- BAGIAN 1: INPUT FORM (GRID 2 KOLOM MODERN) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8 mb-10">
            
            <!-- Form Pelanggaran (Rose Theme) -->
            <div class="bg-white rounded-3xl shadow-sm border border-rose-100 overflow-hidden relative group hover:shadow-lg hover:shadow-rose-100/50 transition-all duration-300">
                <div class="p-6 md:p-8 relative z-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-14 h-14 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-rose-100 group-hover:rotate-12 transition-transform duration-300">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-gray-800">Input Pelanggaran</h3>
                            <p class="text-sm text-gray-500">Catat perilaku indisipliner siswa</p>
                        </div>
                    </div>

                    <form action="{{ route('discipline.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <input type="hidden" name="date" value="{{ \Carbon\Carbon::today()->toDateString() }}">
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Pilih Siswa</label>
                            <div class="flex gap-2">
                                <select name="student_id" id="student_select_violation" required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-rose-500 focus:ring-rose-500 text-sm py-3 font-medium transition-colors">
                                    <option value="">-- Cari Nama Siswa --</option>
                                    {{-- 
                                        FIX: Tambahkan 'data-student-id' agar sesuai dengan QR Code dari halaman siswa.
                                    --}}
                                    @foreach ($students as $student)
                                        <option value="{{ $student->id }}" 
                                                data-nis="{{ $student->nis ?? '' }}" 
                                                data-nisn="{{ $student->nisn ?? '' }}"
                                                data-student-id="{{ $student->student_id ?? '' }}">
                                            {{ $student->name }} ({{ $student->schoolClass->name ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                                {{-- Tombol Scan QR --}}
                                <button type="button" onclick="startScanner('student_select_violation')" class="shrink-0 bg-gray-800 text-white p-3 rounded-xl hover:bg-gray-700 transition-colors" title="Scan QR Code">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                </button>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Jenis Pelanggaran</label>
                            <select name="discipline_type_id" required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-rose-500 focus:ring-rose-500 text-sm py-3 font-medium transition-colors">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($violationTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }} (-{{ $type->point_value }} Poin)</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Kronologi / Catatan</label>
                            <textarea name="notes" rows="2" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-rose-500 focus:ring-rose-500 text-sm transition-colors" placeholder="Jelaskan singkat kejadiannya..."></textarea>
                        </div>

                        <button type="submit" class="w-full py-3.5 px-4 bg-rose-600 text-white font-bold rounded-xl hover:bg-rose-700 transition-all shadow-lg shadow-rose-200 flex items-center justify-center gap-2 mt-2 group-hover:translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            Simpan Data Pelanggaran
                        </button>
                    </form>
                </div>
                {{-- Background Decor --}}
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-rose-50 rounded-full blur-2xl opacity-50 pointer-events-none"></div>
            </div>

            <!-- Form Kebaikan (Emerald Theme) -->
            <div class="bg-white rounded-3xl shadow-sm border border-emerald-100 overflow-hidden relative group hover:shadow-lg hover:shadow-emerald-100/50 transition-all duration-300">
                <div class="p-6 md:p-8 relative z-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-emerald-100 group-hover:rotate-12 transition-transform duration-300">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-gray-800">Input Prestasi</h3>
                            <p class="text-sm text-gray-500">Apresiasi kebaikan siswa</p>
                        </div>
                    </div>

                    <form action="{{ route('discipline.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <input type="hidden" name="date" value="{{ \Carbon\Carbon::today()->toDateString() }}">
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Pilih Siswa</label>
                            <div class="flex gap-2">
                                <select name="student_id" id="student_select_merit" required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 font-medium transition-colors">
                                    <option value="">-- Cari Nama Siswa --</option>
                                    @foreach ($students as $student)
                                        <option value="{{ $student->id }}" 
                                                data-nis="{{ $student->nis ?? '' }}" 
                                                data-nisn="{{ $student->nisn ?? '' }}"
                                                data-student-id="{{ $student->student_id ?? '' }}">
                                            {{ $student->name }} ({{ $student->schoolClass->name ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                                {{-- Tombol Scan QR --}}
                                <button type="button" onclick="startScanner('student_select_merit')" class="shrink-0 bg-gray-800 text-white p-3 rounded-xl hover:bg-gray-700 transition-colors" title="Scan QR Code">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                </button>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Jenis Kebaikan</label>
                            <select name="discipline_type_id" required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 font-medium transition-colors">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($meritTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }} (+{{ $type->point_value }} Poin)</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Detail Tambahan</label>
                            <textarea name="notes" rows="2" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 text-sm transition-colors" placeholder="Keterangan prestasi..."></textarea>
                        </div>

                        <button type="submit" class="w-full py-3.5 px-4 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200 flex items-center justify-center gap-2 mt-2 group-hover:translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Simpan Data Kebaikan
                        </button>
                    </form>
                </div>
                {{-- Background Decor --}}
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-emerald-50 rounded-full blur-2xl opacity-50 pointer-events-none"></div>
            </div>
        </div>

        <!-- BAGIAN 2: RINGKASAN POIN (Leaderboard Style) -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-10">
            <div class="p-6 border-b border-gray-100 bg-gray-50/30">
                <h3 class="text-lg font-black text-gray-800 flex items-center gap-2">
                    <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-amber-100 text-amber-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </span>
                    Top Aktivitas Siswa
                </h3>
                <p class="text-xs text-gray-500 mt-1 ml-10">10 Siswa dengan aktivitas tercatat terbanyak</p>
            </div>
            
            <div class="overflow-x-auto w-full max-w-[calc(100vw-3rem)] md:max-w-full">
                <table class="w-full text-left border-collapse" style="min-width: 800px;">
                    <thead>
                        <tr class="border-b border-gray-100 bg-white">
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider w-16 text-center">Rank</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Siswa</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Kelas</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Poin Pelanggaran</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Poin Kebaikan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($studentSummaries as $index => $summary)
                            <tr class="hover:bg-blue-50/30 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($index == 0)
                                        <div class="w-8 h-8 mx-auto rounded-full bg-amber-100 text-amber-600 flex items-center justify-center font-black text-sm ring-4 ring-amber-50">1</div>
                                    @elseif($index == 1)
                                        <div class="w-8 h-8 mx-auto rounded-full bg-slate-200 text-slate-600 flex items-center justify-center font-bold text-sm ring-4 ring-slate-50">2</div>
                                    @elseif($index == 2)
                                        <div class="w-8 h-8 mx-auto rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-bold text-sm ring-4 ring-orange-50">3</div>
                                    @else
                                        <span class="text-sm font-bold text-gray-400">#{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-bold text-gray-800 group-hover:text-blue-600 transition-colors">{{ $summary->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 rounded bg-gray-100 text-xs font-bold text-gray-500">{{ $summary->class }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($summary->total_violation > 0)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold text-rose-700 bg-rose-100">
                                            - {{ $summary->total_violation }}
                                        </span>
                                    @else
                                        <span class="text-gray-300 text-xs font-medium">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($summary->total_merit > 0)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold text-emerald-700 bg-emerald-100">
                                            + {{ $summary->total_merit }}
                                        </span>
                                    @else
                                        <span class="text-gray-300 text-xs font-medium">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <p class="text-gray-500 font-medium">Belum ada catatan disiplin.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Custom Pagination --}}
            @if($historyRecords->hasPages())
                <div class="p-4 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between gap-4">
                    <div class="text-sm text-gray-500 text-center md:text-left">
                        Showing <span class="font-bold text-gray-800">{{ $historyRecords->firstItem() }}</span> to <span class="font-bold text-gray-800">{{ $historyRecords->lastItem() }}</span> of <span class="font-bold text-gray-800">{{ $historyRecords->total() }}</span> results
                    </div>
                    <div class="flex items-center rounded-lg border border-gray-200 bg-white shadow-sm overflow-hidden">
                        @if ($historyRecords->onFirstPage())
                            <span class="px-3 py-2 text-gray-300 bg-gray-50 border-r border-gray-200 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                            </span>
                        @else
                            <a href="{{ $historyRecords->previousPageUrl() }}" class="px-3 py-2 text-gray-600 bg-white hover:bg-gray-50 border-r border-gray-200 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                            </a>
                        @endif
                        @foreach ($historyRecords->getUrlRange(max($historyRecords->currentPage() - 2, 1), min($historyRecords->currentPage() + 2, $historyRecords->lastPage())) as $page => $url)
                            @if ($page == $historyRecords->currentPage())
                                <span class="px-4 py-2 text-sm font-bold text-blue-600 bg-blue-50 border-r border-gray-200">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="px-4 py-2 text-sm font-medium text-gray-600 bg-white hover:bg-gray-50 border-r border-gray-200 transition-colors">{{ $page }}</a>
                            @endif
                        @endforeach
                        @if ($historyRecords->hasMorePages())
                            <a href="{{ $historyRecords->nextPageUrl() }}" class="px-3 py-2 text-gray-600 bg-white hover:bg-gray-50 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        @else
                            <span class="px-3 py-2 text-gray-300 bg-gray-50 cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- MODAL SCANNER QR CODE --}}
    <div id="qrModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeScanner()"></div>

            <!-- Modal panel -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Scan Kartu Siswa
                            </h3>
                            <div class="mt-4">
                                {{-- 
                                    FIX: Wrapper relatif untuk kamera + status.
                                    Div status DIPISAH dari div #reader agar tidak terhapus saat kamera start.
                                --}}
                                <div class="relative w-full rounded-xl overflow-hidden min-h-[300px] bg-black">
                                    {{-- Div Kamera --}}
                                    <div id="reader" class="w-full h-full"></div>
                                    
                                    {{-- Status Overlay (Posisi Absolute di atas kamera) --}}
                                    <div id="scanner-status" class="absolute inset-0 flex items-center justify-center text-white text-sm font-medium z-10 p-4 text-center pointer-events-none">
                                        Menunggu Kamera...
                                    </div>
                                </div>

                                <div id="error-message" class="text-red-500 text-xs mt-2 hidden text-center bg-red-50 p-2 rounded-lg border border-red-100"></div>
                                <p class="text-sm text-gray-500 mt-2 text-center">Arahkan kamera ke QR Code Siswa</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-2">
                    <button type="button" class="w-full inline-flex justify-center rounded-xl border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:w-auto sm:text-sm" onclick="closeScanner()">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT SCANNER --}}
    <script>
        let html5QrcodeScanner = null;
        let currentTargetInput = null;

        // Helper untuk update status di layar kamera
        function updateStatus(message) {
            const statusEl = document.getElementById('scanner-status');
            if(statusEl) statusEl.innerText = message;
        }

        function startScanner(targetInputId) {
            // Check if library is loaded
            if (typeof Html5Qrcode === 'undefined') {
                alert('Library Scanner belum siap/gagal dimuat. Periksa koneksi internet Anda dan coba refresh halaman.');
                return;
            }

            currentTargetInput = targetInputId;
            const modal = document.getElementById('qrModal');
            const errorMsg = document.getElementById('error-message');
            const statusEl = document.getElementById('scanner-status');

            // Reset UI State
            errorMsg.classList.add('hidden');
            errorMsg.innerText = '';
            modal.classList.remove('hidden');
            
            // Pastikan status terlihat kembali
            if(statusEl) {
                statusEl.style.display = 'flex';
                statusEl.innerText = "Memulai kamera...";
                statusEl.classList.remove('bg-emerald-500/80');
            }

            setTimeout(() => {
                if (html5QrcodeScanner === null) {
                    html5QrcodeScanner = new Html5Qrcode("reader");
                }

                // Langkah 1: Cek Kamera yang tersedia
                Html5Qrcode.getCameras().then(devices => {
                    if (devices && devices.length) {
                        // Cari kamera belakang (environment) jika ada, jika tidak pakai yang terakhir
                        let cameraId = devices[0].id; // Default kamera pertama
                        
                        // Coba cari kamera belakang (biasanya labelnya mengandung "back" atau "environment")
                        // Atau logika sederhana: di HP biasanya kamera terakhir adalah belakang
                        if (devices.length > 1) {
                            // Preferensi kamera terakhir (biasanya belakang di mobile)
                             cameraId = devices[devices.length - 1].id;
                        }

                        updateStatus("Kamera ditemukan. Membuka...");

                        const config = { 
                            fps: 10, 
                            qrbox: { width: 250, height: 250 },
                            aspectRatio: 1.0 
                        };

                        // Start kamera by ID (lebih stabil daripada facingMode di beberapa device)
                        html5QrcodeScanner.start(
                            cameraId, 
                            config, 
                            onScanSuccess,
                            (errorMessage) => {
                                // Error scanning (biasa terjadi frame-by-frame, abaikan saja agar tidak spam)
                            }
                        ).then(() => {
                            updateStatus(""); // Hapus teks loading jika sukses
                            // Sembunyikan overlay status agar video terlihat jelas
                            if(statusEl) statusEl.style.display = 'none';
                        }).catch(err => {
                            console.error("Gagal start kamera", err);
                            showError("Gagal membuka kamera: " + err);
                        });
                    } else {
                        showError("Tidak ada kamera yang terdeteksi di perangkat ini.");
                    }
                }).catch(err => {
                    console.error("Error getCameras", err);
                    showError("Izin kamera ditolak atau error: " + err);
                });
            }, 500); // Delay sedikit lebih lama agar modal render sempurna
        }

        function showError(msg) {
            const errorMsg = document.getElementById('error-message');
            const statusEl = document.getElementById('scanner-status');
            
            if(errorMsg) {
                errorMsg.innerText = msg;
                errorMsg.classList.remove('hidden');
            }
            
            if(statusEl) statusEl.innerText = "Error Kamera";
        }

        function onScanSuccess(decodedText, decodedResult) {
            // Bersihkan teks hasil scan (hapus spasi depan/belakang)
            const scannedText = decodedText.trim();

            // Cari dropdown yang sedang aktif
            let selectElement = document.getElementById(currentTargetInput);
            let found = false;
            let foundName = "";

            // FIX: Logika pencarian diperbarui untuk mengecek 'data-student-id' juga
            for (let i = 0; i < selectElement.options.length; i++) {
                const option = selectElement.options[i];
                const value = option.value; // ID Database
                const nis = option.getAttribute('data-nis');
                const nisn = option.getAttribute('data-nisn');
                const studentId = option.getAttribute('data-student-id'); // Kolom student_id (NISN Inputan)

                // Cek kecocokan dengan semua kemungkinan data
                if (value == scannedText || 
                   (nis && nis == scannedText) || 
                   (nisn && nisn == scannedText) || 
                   (studentId && studentId == scannedText)) {
                    
                    selectElement.selectedIndex = i;
                    found = true;
                    foundName = option.text;
                    break;
                }
            }

            if (found) {
                const statusEl = document.getElementById('scanner-status');
                if(statusEl) {
                    statusEl.style.display = 'flex';
                    statusEl.innerText = "✅ " + foundName;
                    statusEl.classList.add('bg-emerald-500/80');
                }
                
                // Audio beep feedback (opsional)
                // new Audio('https://www.soundjay.com/buttons/beep-01a.mp3').play().catch(e => {});

                setTimeout(() => {
                    // Alert sukses diganti notifikasi halus atau langsung tutup
                    closeScanner();
                }, 800);
            } else {
                alert(`Data siswa dengan kode "${scannedText}" tidak ditemukan di daftar.\nPastikan QR Code berisi ID, NIS, atau NISN yang terdaftar.`);
            }
        }

        function closeScanner() {
            const modal = document.getElementById('qrModal');
            
            if (html5QrcodeScanner) {
                html5QrcodeScanner.stop().then(() => {
                    console.log("Scanner stopped");
                    finishClose();
                }).catch((err) => {
                    console.warn("Stop failed/already stopped", err);
                    finishClose();
                });
            } else {
                finishClose();
            }
        }

        function finishClose() {
            const modal = document.getElementById('qrModal');
            const statusEl = document.getElementById('scanner-status');

            if(modal) modal.classList.add('hidden');
            
            // Reset status text untuk next open
            if(statusEl) {
                statusEl.style.display = 'flex';
                statusEl.innerText = "Menunggu Kamera...";
                statusEl.classList.remove('bg-emerald-500/80');
            }
        }
    </script>
</x-app-layout>