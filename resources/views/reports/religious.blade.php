{{-- Halaman ini adalah tampilan untuk resources/views/reports/religious.blade.php --}}
<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js" defer></script>

    @php
        $listHadir = $todayAttendances->where('status_final', 'Hadir');
        $listUzur  = $todayAttendances->where('status_final', '!=', 'Hadir');
        // $belumAbsenList sudah dikirim dari Controller
    @endphp

    <div class="py-6 sm:py-8">
        {{-- Header & Filter (TETAP SAMA) --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <h1 class="text-3xl font-black text-gray-800 tracking-tight leading-tight">
                    Rekapitulasi Keagamaan
                </h1>
                <p class="text-gray-500 mt-1">
                    Laporan aktivitas ibadah siswa tanggal <span class="font-bold text-gray-700">{{ $selectedDate_db->translatedFormat('d F Y') }}</span>.
                </p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 items-center">
                {{-- Switcher Kegiatan --}}
                <div class="bg-white p-1.5 rounded-2xl flex items-center shadow-sm border border-gray-100">
                    <a href="{{ route('reports.religious', ['activity' => 'Dhuha', 'date' => $selectedDate_db->format('Y-m-d')]) }}" 
                       class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 {{ $selectedActivity == 'Dhuha' ? 'bg-blue-600 text-white shadow-lg shadow-blue-200' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                    Shalat Dhuha
                    </a>
                    <a href="{{ route('reports.religious', ['activity' => 'Dhuhur', 'date' => $selectedDate_db->format('Y-m-d')]) }}" 
                       class="px-5 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 {{ $selectedActivity == 'Dhuhur' ? 'bg-orange-500 text-white shadow-lg shadow-orange-200' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' }}">
                    Shalat Dhuhur
                    </a>
                </div>

                {{-- Filter Tanggal Modern --}}
                <form action="{{ route('reports.religious') }}" method="GET" class="flex items-center gap-2 bg-white p-1.5 rounded-2xl shadow-sm border border-gray-100">
                    <input type="hidden" name="activity" value="{{ $selectedActivity }}">
                    <input type="date" name="date" 
                           value="{{ $selectedDate_db->format('Y-m-d') }}" 
                           class="border-0 focus:ring-0 text-sm font-bold text-gray-600 bg-transparent rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                    <button type="submit" class="bg-gray-800 hover:bg-gray-900 text-white p-2.5 rounded-xl transition-colors shadow-lg shadow-gray-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                </form>
            </div>
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

        {{-- STATISTIK (TETAP SAMA) --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center justify-center group hover:shadow-md transition-all">
                <div class="h-12 w-12 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h3 class="text-3xl font-extrabold text-gray-800">{{ $hadirCount }}</h3>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Sudah Absen</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center justify-center group hover:shadow-md transition-all">
                <div class="h-12 w-12 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-3xl font-extrabold text-gray-800">{{ $izinUzurCount }}</h3>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Izin / Uzur</p>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center justify-center group hover:shadow-md transition-all">
                <div class="h-12 w-12 bg-red-50 text-red-500 rounded-full flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <h3 class="text-3xl font-extrabold text-gray-800">{{ $belumAbsenCount }}</h3>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-1">Belum Absen</p>
            </div>
        </div>

        {{-- CONTAINER TABEL --}}
        <div x-data="{ activeTab: 'hadir' }" class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            
            {{-- Tabs Modern --}}
            <div class="flex border-b border-gray-100 overflow-x-auto bg-gray-50/50 p-2 gap-2 flex-nowrap no-scrollbar">
                <button @click="activeTab = 'hadir'" 
                        :class="activeTab === 'hadir' ? 'bg-white text-emerald-600 shadow-sm ring-1 ring-emerald-100' : 'text-gray-500 hover:bg-white/60'" 
                        class="flex-none py-3 px-6 rounded-xl text-sm font-bold transition-all duration-200 whitespace-nowrap">
                    Sudah Absen
                </button>
                <button @click="activeTab = 'belum'" 
                        :class="activeTab === 'belum' ? 'bg-white text-red-600 shadow-sm ring-1 ring-red-100' : 'text-gray-500 hover:bg-white/60'" 
                        class="flex-none py-3 px-6 rounded-xl text-sm font-bold transition-all duration-200 whitespace-nowrap">
                    Belum Absen <span class="ml-1 px-1.5 py-0.5 bg-red-100 text-red-600 rounded text-[10px]">{{ $belumAbsenCount }}</span>
                </button>
                <button @click="activeTab = 'uzur'" 
                        :class="activeTab === 'uzur' ? 'bg-white text-blue-600 shadow-sm ring-1 ring-blue-100' : 'text-gray-500 hover:bg-white/60'" 
                        class="flex-none py-3 px-6 rounded-xl text-sm font-bold transition-all duration-200 whitespace-nowrap">
                    Izin / Uzur
                </button>
            </div>

            {{-- Container Tabel --}}
            <div class="w-full relative min-h-[300px]">
                
                {{-- TAB 1: HADIR --}}
                <div x-show="activeTab === 'hadir'" class="w-full">
                    <div class="p-4 flex justify-between items-center bg-white border-b border-gray-100">
                        <h3 class="font-bold text-gray-800">Daftar Siswa Hadir</h3>
                        
                        <form id="reset-data-form" method="POST" action="{{ route('reports.destroyReligious') }}" class="hidden">
                            @csrf @method('DELETE')
                            <input type="hidden" name="date" value="{{ $selectedDate_db->format('Y-m-d') }}">
                            <input type="hidden" name="activity" value="{{ $selectedActivity }}">
                        </form>
                        <button type="button" onclick="confirmResetData()" class="text-xs font-bold text-red-500 hover:text-red-700 hover:bg-red-50 px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            Reset Data
                        </button>
                    </div>

                    <div class="overflow-x-auto w-full max-w-[calc(100vw-3rem)] md:max-w-full pb-4">
                        <table class="w-full text-left border-collapse" style="min-width: 800px;">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase whitespace-nowrap w-1/3">Nama Siswa</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase whitespace-nowrap w-1/4">Kelas</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase whitespace-nowrap w-1/4">Waktu</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase whitespace-nowrap text-right w-1/6">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse ($listHadir as $attendance)
                                    <tr class="hover:bg-emerald-50/30 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $attendance->student->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $attendance->student->schoolClass->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap font-mono text-emerald-600 font-bold bg-emerald-50 inline-block rounded px-2 py-0.5 mt-3 ml-6">{{ $attendance->created_at->format('H:i') }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <button onclick="openEditModalReligious({{ $attendance->id }}, '{{ $attendance->student->name }}', '{{ $attendance->status_final }}', `{{ $attendance->notes_final }}`, '{{ $attendance->activity }}')" 
                                                class="text-gray-400 hover:text-blue-600 p-2 hover:bg-blue-50 rounded-lg transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                            <div class="flex flex-col items-center justify-center">
                                                 <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                 </div>
                                                 <p class="text-sm font-medium">Belum ada data hadir untuk aktivitas ini.</p>
                                            </div>
                                        </td>
                                    </tr> 
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-gray-100">{{ $todayAttendances->appends(request()->query())->links() }}</div>
                </div>

                {{-- [UPDATE] TAB 2: BELUM ABSEN (ADA TOMBOL BULK ALPHA - TEXT UPDATED) --}}
                <div x-show="activeTab === 'belum'" style="display: none;" class="w-full">
                    
                    {{-- 1. TOMBOL PROSES AUTO ALPA KEAGAMAAN --}}
                    @if($belumAbsenList->count() > 0)
                        <div class="p-4 bg-red-50 border-b border-red-100 flex flex-col md:flex-row justify-between items-center gap-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-red-100 text-red-600 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-red-800 text-sm">Aksi Massal</h4>
                                    {{-- UPDATE TEKS: "Hanya Absensi" --}}
                                    <p class="text-xs text-red-600">Tandai {{ $belumAbsenList->count() }} siswa sebagai Alfa Shalat {{ $selectedActivity }} (Hanya Absensi).</p>
                                </div>
                            </div>
                            
                            {{-- Form Bulk Alpha Keagamaan --}}
                            <form id="bulk-alpha-religious-form" action="{{ route('reports.bulkAlpha') }}" method="POST">
                                @csrf
                                <input type="hidden" name="date" value="{{ $selectedDate_db->format('Y-m-d') }}">
                                <input type="hidden" name="type" value="Keagamaan">
                                <input type="hidden" name="activity" value="{{ $selectedActivity }}">
                                <button type="button" onclick="confirmBulkAlphaReligious('{{ $belumAbsenList->count() }}')" 
                                    class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-lg shadow-red-200 transition-all flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                    Proses Semua Alpa
                                </button>
                            </form>
                        </div>
                    @endif

                    <div class="overflow-x-auto w-full max-w-[calc(100vw-3rem)] md:max-w-full pb-4">
                        <table class="w-full text-left border-collapse" style="min-width: 800px;">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase whitespace-nowrap w-1/3">Nama Siswa</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase whitespace-nowrap w-1/3">Kelas</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider w-1/3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse ($belumAbsenList as $student)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $student->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $student->schoolClass->name ?? '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <button onclick="openManualModalForStudent({{ $student->id }}, '{{ $student->name }}')" 
                                                    class="inline-flex items-center gap-2 bg-white border border-blue-200 text-blue-600 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-blue-50 transition-colors shadow-sm">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                Beri Keterangan
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-12 text-center text-gray-400">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center mb-3">
                                                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                </div>
                                                <p class="text-sm font-medium text-green-600">Semua siswa sudah tercatat!</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- TAB 3: UZUR / IZIN (TETAP SAMA) --}}
                <div x-show="activeTab === 'uzur'" style="display: none;" class="w-full">
                    <div class="overflow-x-auto w-full max-w-[calc(100vw-3rem)] md:max-w-full pb-4">
                        <table class="w-full text-left border-collapse" style="min-width: 800px;">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase whitespace-nowrap w-1/3">Nama Siswa</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase whitespace-nowrap w-1/4">Kelas</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase whitespace-nowrap w-1/4">Keterangan</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase whitespace-nowrap text-right w-1/6">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse ($listUzur as $attendance)
                                    <tr class="hover:bg-blue-50/30 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $attendance->student->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $attendance->student->schoolClass->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2.5 py-1 rounded-lg bg-blue-100 text-blue-700 text-xs font-bold">{{ $attendance->status_final }}</span>
                                            @if($attendance->notes_final)
                                                <span class="text-xs text-gray-400 ml-2 italic">({{ $attendance->notes_final }})</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <button onclick="openEditModalReligious({{ $attendance->id }}, '{{ $attendance->student->name }}', '{{ $attendance->status_final }}', `{{ $attendance->notes_final }}`, '{{ $attendance->activity }}')" 
                                                class="text-gray-400 hover:text-blue-600 p-2 hover:bg-blue-50 rounded-lg transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400 italic">Tidak ada data izin/uzur.</td></tr> 
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL INPUT MANUAL, EDIT & SCRIPT --}}
    <div id="manualInputModal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
            <div class="bg-blue-600 px-6 py-4 flex justify-between items-center">
                <h3 class="font-bold text-white">Input Keterangan Manual</h3>
                <button onclick="closeManualModal()" class="text-white/70 hover:text-white text-2xl leading-none">&times;</button>
            </div>
            <form action="{{ route('reports.storeManual') }}" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="attendance_type" value="Keagamaan">
                <input type="hidden" name="activity" value="{{ $selectedActivity }}">
                <input type="hidden" name="date" value="{{ $selectedDate_db->format('Y-m-d') }}">
                <div class="space-y-4">
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase mb-1 block">Nama Siswa</label>
                        <input type="text" id="manual-student-name-display" class="w-full bg-gray-50 border-gray-200 rounded-xl text-gray-800 font-bold focus:ring-0" readonly>
                        <input type="hidden" name="student_id" id="manual-student-id">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase mb-1 block">Status</label>
                        <select name="status" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm">
                            <option value="Hadir">Hadir</option>
                            <option value="Uzur Syar'i">Uzur Syar'i</option>
                            <option value="Alfa">Alfa</option> {{-- Ditambahkan Opsi Alfa --}}
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-400 uppercase mb-1 block">Catatan</label>
                        <input type="text" name="notes" class="w-full border-gray-300 rounded-xl focus:ring-blue-500 focus:border-blue-500 shadow-sm" placeholder="Contoh: Sakit, Izin Lomba">
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition-colors shadow-lg shadow-blue-200 mt-2">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editReligiousModal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4 transition-opacity">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
            <div class="bg-gray-800 px-6 py-4 flex justify-between items-center">
                <h3 class="font-bold text-white">Edit Data Keagamaan</h3>
                <button onclick="closeEditModalReligious()" class="text-white/70 hover:text-white text-2xl leading-none">&times;</button>
            </div>
            <form id="editReligiousForm" method="POST" class="p-6">
                @csrf @method('PUT')
                <div class="mb-4">
                    <label class="text-xs font-bold text-gray-400 uppercase mb-1 block">Nama Siswa</label>
                    <p id="modal-religious-student-name" class="text-xl font-bold text-gray-800"></p>
                </div>
                <input type="hidden" name="activity" id="modal-religious-activity">
                <div class="mb-4">
                    <label class="text-xs font-bold text-gray-400 uppercase mb-1 block">Status</label>
                    <select name="status" id="modal-religious-status" class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="Hadir">Hadir</option>
                        <option value="Uzur Syar'i">Uzur Syar'i</option>
                        <option value="Alfa">Alfa</option>
                    </select>
                </div>
                <div class="mb-6">
                    <label class="text-xs font-bold text-gray-400 uppercase mb-1 block">Catatan</label>
                    <textarea name="notes" id="modal-religious-notes" class="w-full border-gray-300 rounded-xl shadow-sm focus:ring-blue-500 focus:border-blue-500" rows="3"></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeEditModalReligious()" class="px-4 py-2 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-md">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openManualModalForStudent(id, name) {
            document.getElementById('manual-student-id').value = id;
            document.getElementById('manual-student-name-display').value = name;
            document.getElementById('manualInputModal').classList.remove('hidden');
        }
        function closeManualModal() {
            document.getElementById('manualInputModal').classList.add('hidden');
        }
        
        // --- SAFE RESET LOGIC ---
        function confirmResetData() {
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: "Semua data kehadiran {{ $selectedActivity }} untuk tanggal ini akan dihapus permanen! Tindakan ini tidak dapat dibatalkan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus Semua!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('reset-data-form').submit();
                }
            })
        }

        // --- BULK ALPHA CONFIRMATION ---
        function confirmBulkAlphaReligious(count) {
            Swal.fire({
                title: 'Tandai ' + count + ' Siswa Alfa?',
                // UPDATE TEKS DI SWAL JUGA
                text: "Siswa akan ditandai Alfa untuk Shalat {{ $selectedActivity }}. (Tanpa Pengurangan Poin)",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Proses!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('bulk-alpha-religious-form').submit();
                }
            })
        }

        const religiousModal = document.getElementById('editReligiousModal');
        const religiousForm = document.getElementById('editReligiousForm');
        const religiousStudentNameDisplay = document.getElementById('modal-religious-student-name');
        const religiousActivitySelect = document.getElementById('modal-religious-activity');
        const religiousStatusSelect = document.getElementById('modal-religious-status');
        const religiousNotesInput = document.getElementById('modal-religious-notes');
        function openEditModalReligious(id, name, status, notes, activity) {
            const updateRoute = '{{ route('reports.update', ['attendance' => '__ID__']) }}'.replace('__ID__', id);
            religiousForm.action = updateRoute;
            religiousStudentNameDisplay.textContent = name; 
            religiousActivitySelect.value = activity;
            religiousStatusSelect.value = status;
            religiousNotesInput.value = notes;
            religiousModal.classList.remove('hidden');
        }
        function closeEditModalReligious() {
            religiousModal.classList.add('hidden');
        }
    </script>
</x-app-layout>