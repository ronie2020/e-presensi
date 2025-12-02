{{-- Halaman ini adalah tampilan untuk resources/views/reports/daily.blade.php --}}
<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- FIX: Ambil tab aktif dari URL agar tidak reset saat ganti halaman --}}
    <div class="py-6 sm:py-8" x-data="{ activeTab: '{{ request('activeTab', 'hadir') }}' }">
        
        {{-- Header Page --}}
        <div class="mb-8 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-gray-800 tracking-tight leading-tight">Rekap Absensi Harian</h1>
                <p class="text-gray-500 mt-1">
                    Laporan kehadiran siswa tanggal <span class="font-bold text-blue-600">{{ $selectedDate_db->translatedFormat('d F Y') }}</span>.
                </p>
            </div>
            
            <form action="{{ route('reports.daily') }}" method="GET" class="flex items-center gap-2 bg-white p-1.5 rounded-2xl shadow-sm border border-gray-100">
                {{-- FIX: Simpan tab saat ganti tanggal --}}
                <input type="hidden" name="activeTab" x-model="activeTab">
                
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <input type="date" name="date" value="{{ $selectedDate_db->format('Y-m-d') }}" 
                           class="pl-10 border-0 focus:ring-0 text-sm font-bold text-gray-600 rounded-xl bg-transparent cursor-pointer hover:bg-gray-50 transition-colors">
                </div>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white p-2.5 rounded-xl transition-all shadow-lg shadow-blue-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
            </form>
        </div>

        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl font-medium text-sm flex justify-between items-center shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-400 hover:text-emerald-600">&times;</button>
            </div>
        @endif

        {{-- Kartu Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between group hover:shadow-md transition-all duration-300">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Total Hadir</p>
                    <h3 class="text-3xl font-extrabold text-gray-800 group-hover:text-emerald-600 transition-colors">{{ $hadirCount }}</h3>
                    @if($terlambatCount > 0)
                        <span class="inline-flex items-center mt-2 px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-700">Termasuk {{ $terlambatCount }} Terlambat</span>
                    @endif
                </div>
                <div class="h-12 w-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between group hover:shadow-md transition-all duration-300">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Sakit / Izin / Alfa</p>
                    <h3 class="text-3xl font-extrabold text-gray-800 group-hover:text-amber-500 transition-colors">{{ $sakitCount + $izinCount + $alfaCount }}</h3>
                </div>
                <div class="h-12 w-12 bg-amber-50 text-amber-500 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between group hover:shadow-md transition-all duration-300">
                <div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Belum Absen</p>
                    <h3 class="text-3xl font-extrabold text-gray-800 group-hover:text-gray-600 transition-colors">{{ $belumAbsenList->count() }}</h3>
                </div>
                <div class="h-12 w-12 bg-gray-50 text-gray-400 rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
        </div>

        {{-- TABEL UTAMA --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            
            <div class="flex border-b border-gray-100 overflow-x-auto bg-gray-50/50 p-2 gap-2 flex-nowrap no-scrollbar">
                <button @click="activeTab = 'hadir'" :class="activeTab === 'hadir' ? 'bg-white text-emerald-600 shadow-sm ring-1 ring-emerald-100' : 'text-gray-500 hover:bg-white/60'" class="flex-none py-3 px-6 rounded-xl text-sm font-bold whitespace-nowrap transition-all duration-200">Hadir / Terlambat</button>
                <button @click="activeTab = 'belum'" :class="activeTab === 'belum' ? 'bg-white text-gray-700 shadow-sm ring-1 ring-gray-200' : 'text-gray-500 hover:bg-white/60'" class="flex-none py-3 px-6 rounded-xl text-sm font-bold whitespace-nowrap transition-all duration-200">Belum Absen <span class="ml-1 px-1.5 py-0.5 bg-red-100 text-red-600 rounded text-[10px]">{{ $belumAbsenList->count() }}</span></button>
                <button @click="activeTab = 'lain'" :class="activeTab === 'lain' ? 'bg-white text-amber-600 shadow-sm ring-1 ring-amber-100' : 'text-gray-500 hover:bg-white/60'" class="flex-none py-3 px-6 rounded-xl text-sm font-bold whitespace-nowrap transition-all duration-200">Sakit / Izin / Alfa</button>
            </div>

            <div class="w-full relative min-h-[300px]">
                
                {{-- TAB HADIR (Halaman Khusus) --}}
                <div x-show="activeTab === 'hadir'" class="w-full">
                    <div class="overflow-x-auto w-full max-w-[calc(100vw-3rem)] md:max-w-full pb-4">
                        <table class="w-full text-left border-collapse" style="min-width: 800px;">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-1/3">Siswa</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-1/6">Masuk</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-1/6">Pulang</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider w-1/6">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse ($attendancesHadir as $att)
                                    <tr class="hover:bg-blue-50/50 transition-colors group">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-bold text-gray-900">{{ $att->student->name }}</div>
                                            <div class="text-xs text-gray-400">{{ $att->student->schoolClass->name ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($att->status_final == 'Terlambat')
                                                <div class="flex flex-col items-start">
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-amber-100 text-amber-800 font-mono ring-1 ring-amber-200">
                                                        {{ $att->time_in_final ? \Carbon\Carbon::parse($att->time_in_final)->format('H:i') : '-' }}
                                                    </span>
                                                    <span class="text-[10px] font-bold text-amber-600 mt-1 uppercase tracking-wide">Terlambat</span>
                                                </div>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-emerald-100 text-emerald-800 font-mono">
                                                    {{ $att->time_in_final ? \Carbon\Carbon::parse($att->time_in_final)->format('H:i') : '-' }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-sm font-medium bg-indigo-100 text-indigo-800 font-mono">
                                                {{ $att->time_out_final ? \Carbon\Carbon::parse($att->time_out_final)->format('H:i') : '-' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <button onclick="openEditModal({{ $att->id }}, '{{ $att->student->name }}', '{{ $att->status_final }}', `{{ $att->notes_final }}`, '{{ $att->time_in_final }}', '{{ $att->time_out_final }}')" 
                                                class="text-gray-400 hover:text-blue-600 transition-colors p-2 hover:bg-blue-100 rounded-lg">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                                </div>
                                                <p class="text-sm font-medium">Belum ada data kehadiran.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- Pagination Khusus Hadir (Menyimpan State Tab) --}}
                    @if($attendancesHadir->hasPages())
                        <div class="p-4 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between gap-4">
                            {{ $attendancesHadir->appends(request()->query() + ['activeTab' => 'hadir'])->links() }}
                        </div>
                    @endif
                </div>

                {{-- TAB BELUM ABSEN --}}
                <div x-show="activeTab === 'belum'" style="display: none;" class="w-full">
                    @if($belumAbsenList->count() > 0)
                        <div class="p-4 bg-red-50 border-b border-red-100 flex flex-col md:flex-row justify-between items-center gap-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-red-100 text-red-600 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                </div>
                                <div>
                                    <h4 class="font-bold text-red-800 text-sm">Aksi Massal</h4>
                                    <p class="text-xs text-red-600">Tandai semua siswa di bawah ini sebagai Alpa (+Poin Pelanggaran).</p>
                                </div>
                            </div>
                            <form id="bulk-alpha-form" action="{{ route('reports.bulkAlpha') }}" method="POST">
                                @csrf
                                <input type="hidden" name="date" value="{{ $selectedDate_db->format('Y-m-d') }}">
                                <input type="hidden" name="type" value="Harian">
                                <button type="button" onclick="confirmBulkAlpha('{{ $belumAbsenList->count() }}')" 
                                    class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-lg shadow-red-200 transition-all flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path></svg>
                                    Tandai {{ $belumAbsenList->count() }} Siswa Alpa
                                </button>
                            </form>
                        </div>
                    @endif
                    <div class="overflow-x-auto w-full max-w-[calc(100vw-3rem)] md:max-w-full">
                        <table class="min-w-full divide-y" style="min-width: 800px;">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-1/3">Siswa</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-1/3">Kelas</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider w-1/3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse ($belumAbsenList as $student)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $student->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $student->schoolClass->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <button onclick="openManualModalDaily({{ $student->id }}, '{{ $student->name }}')" 
                                                class="inline-flex items-center gap-2 bg-white border border-blue-200 text-blue-600 px-3 py-1.5 rounded-lg text-xs font-bold hover:bg-blue-50 transition-colors shadow-sm">
                                                Input Manual
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

                {{-- TAB KET. LAIN (Halaman Khusus) --}}
                <div x-show="activeTab === 'lain'" style="display: none;" class="w-full">
                    <div class="overflow-x-auto w-full max-w-[calc(100vw-3rem)] md:max-w-full border rounded-lg">
                        <table class="w-full text-left border-collapse" style="min-width: 800px;">
                            <thead class="bg-gray-50 border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-1/4">Siswa</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-1/6">Status</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider w-1/3">Keterangan</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider w-1/6">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse ($attendancesLain as $att)
                                    <tr class="hover:bg-amber-50/30 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $att->student->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold {{ $att->status_final == 'Alfa' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700' }}">
                                                {{ $att->status_final }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 min-w-[200px] italic">{{ $att->notes_final ?: '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right">
                                            <button onclick="openEditModal({{ $att->id }}, '{{ $att->student->name }}', '{{ $att->status_final }}', `{{ $att->notes_final }}`, '', '')" 
                                                class="text-gray-400 hover:text-blue-600 transition-colors p-2 hover:bg-blue-100 rounded-lg">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400 italic">Tidak ada data keterangan lain.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- Pagination Khusus Lain (Menyimpan State Tab) --}}
                    @if($attendancesLain->hasPages())
                        <div class="p-4 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between gap-4">
                            {{ $attendancesLain->appends(request()->query() + ['activeTab' => 'lain'])->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL MANUAL/EDIT + SCRIPT --}}
    <div id="manualModalDaily" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 transition-opacity">
        <div class="relative top-10 mx-auto p-0 border-0 w-full max-w-lg shadow-2xl rounded-2xl bg-white overflow-hidden transform transition-all">
            <div class="bg-blue-600 px-6 py-4 flex justify-between items-center">
                <h3 class="text-lg font-bold text-white">Input Absensi Manual</h3>
                <button onclick="closeManualModalDaily()" class="text-white/70 hover:text-white text-2xl leading-none">&times;</button>
            </div>
            <div class="p-6">
                <p class="text-sm text-gray-500 mb-4">Input data untuk siswa: <span id="daily-manual-name-display" class="font-bold text-gray-800 text-lg block">Nama Siswa</span></p>
                <form action="{{ route('reports.storeManual') }}" method="POST">
                    @csrf
                    <input type="hidden" name="attendance_type" value="Harian">
                    <input type="hidden" name="student_id" id="daily-manual-id">
                    <div class="space-y-4">
                        <div class="bg-gray-50 p-3 rounded-xl border border-gray-100">
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Tanggal</label>
                            <input type="date" name="date" id="daily-manual-date" value="{{ $selectedDate_db->format('Y-m-d') }}" class="block w-full rounded-lg border-gray-300 shadow-sm text-sm font-semibold bg-white" readonly>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status Kehadiran</label>
                            <select name="status" id="daily-manual-status" required class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" onchange="toggleTimeInput()">
                                <option value="Hadir">Hadir (Manual)</option>
                                <option value="Terlambat">Terlambat</option>
                                <option value="Sakit">Sakit</option>
                                <option value="Izin">Izin</option>
                                <option value="Alfa">Alfa</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4 p-4 bg-blue-50 rounded-xl border border-blue-100" id="time-input-container">
                            <div>
                                <label class="block text-xs font-bold text-blue-800 uppercase mb-1">Waktu Masuk</label>
                                <input type="time" name="time_in" id="daily-manual-time-in" class="block w-full rounded-lg border-blue-200 shadow-sm focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-blue-800 uppercase mb-1">Waktu Pulang</label>
                                <input type="time" name="time_out" id="daily-manual-time-out" class="block w-full rounded-lg border-blue-200 shadow-sm focus:ring-blue-500">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan Tambahan (Opsional)</label>
                            <textarea name="notes" rows="3" class="block w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: Datang terlambat karena ban bocor..."></textarea>
                        </div>
                    </div>
                    <div class="mt-8 flex justify-end space-x-3 pt-4 border-t border-gray-100">
                        <button type="button" onclick="closeManualModalDaily()" class="bg-white text-gray-600 font-bold py-2.5 px-5 rounded-xl border border-gray-200 hover:bg-gray-50 transition-colors">Batal</button>
                        <button type="submit" class="bg-blue-600 text-white font-bold py-2.5 px-5 rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-200 transition-colors">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="editAttendanceModal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4 z-50 transition-opacity">
        <div class="bg-white rounded-2xl p-0 w-full max-w-md shadow-2xl overflow-hidden">
            <div class="bg-gray-800 px-6 py-4 flex justify-between items-center">
                <h3 class="font-bold text-white">Edit Data Presensi</h3>
                <button onclick="closeEditModal()" class="text-white/60 hover:text-white">&times;</button>
            </div>
            <form id="editForm" method="POST" class="p-6">
                @csrf @method('PUT')
                <div class="mb-4">
                    <label class="text-xs font-bold text-gray-400 uppercase">Nama Siswa</label>
                    <p id="modal-student-name" class="font-bold text-xl text-gray-800"></p>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select name="status" id="modal-status" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500" onchange="toggleEditTimeInput()">
                        <option value="Hadir">Hadir</option>
                        <option value="Terlambat">Terlambat</option>
                        <option value="Sakit">Sakit</option>
                        <option value="Izin">Izin</option>
                        <option value="Alfa">Alfa</option>
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-3 mb-4 p-3 bg-gray-50 rounded-xl border border-gray-100" id="edit-time-container">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Masuk</label>
                        <input type="time" name="time_in" id="modal-time_in" class="w-full border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Pulang</label>
                        <input type="time" name="time_out" id="modal-time_out" class="w-full border-gray-300 rounded-lg">
                    </div>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Catatan</label>
                    <textarea name="notes" id="modal-notes" class="w-full border-gray-300 rounded-xl shadow-sm focus:border-blue-500 focus:ring-blue-500" rows="3"></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-md">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function confirmBulkAlpha(count) {
            Swal.fire({
                title: 'Tandai ' + count + ' Siswa Alpa?',
                text: "Siswa yang belum absen akan otomatis tercatat sebagai ALPA dan mendapat Poin Pelanggaran.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Proses Sekarang!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('bulk-alpha-form').submit();
                }
            })
        }
        function toggleTimeInput() {
            const status = document.getElementById('daily-manual-status').value;
            const timeContainer = document.getElementById('time-input-container');
            if (status === 'Hadir' || status === 'Terlambat') {
                timeContainer.classList.remove('hidden', 'opacity-50', 'pointer-events-none');
            } else {
                timeContainer.classList.add('hidden', 'opacity-50', 'pointer-events-none');
            }
        }
        function toggleEditTimeInput() {
            const status = document.getElementById('modal-status').value;
            const timeContainer = document.getElementById('edit-time-container');
            if (status === 'Hadir' || status === 'Terlambat') {
                timeContainer.classList.remove('hidden');
            } else {
                timeContainer.classList.add('hidden');
            }
        }
        function openManualModalDaily(id, name) { 
            document.getElementById('daily-manual-id').value = id; 
            document.getElementById('daily-manual-name-display').textContent = name; 
            document.getElementById('daily-manual-status').value = 'Hadir';
            toggleTimeInput(); 
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            document.getElementById('daily-manual-time-in').value = `${hours}:${minutes}`;
            document.getElementById('manualModalDaily').classList.remove('hidden'); 
        }
        function closeManualModalDaily() { 
            document.getElementById('manualModalDaily').classList.add('hidden'); 
        }
        const modal = document.getElementById('editAttendanceModal');
        const form = document.getElementById('editForm');
        function openEditModal(id, name, status, notes, timeIn, timeOut) {
            form.action = '{{ route('reports.update', ['attendance' => '__ID__']) }}'.replace('__ID__', id);
            document.getElementById('modal-student-name').textContent = name;
            document.getElementById('modal-status').value = status;
            document.getElementById('modal-notes').value = notes;
            document.getElementById('modal-time_in').value = timeIn ? timeIn.substring(0,5) : '';
            document.getElementById('modal-time_out').value = timeOut ? timeOut.substring(0,5) : '';
            toggleEditTimeInput(); 
            modal.classList.remove('hidden');
        }
        function closeEditModal() { modal.classList.add('hidden'); }
    </script>
</x-app-layout>