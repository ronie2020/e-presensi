{{-- Halaman ini adalah tampilan untuk resources/views/reports/religious.blade.php --}}
<x-app-layout>
    {{-- Header Page & Filter --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">
                Rekapitulasi Keagamaan
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Data pelaksanaan <span class="font-bold text-blue-600">{{ $selectedActivity }}</span> pada tanggal <span class="font-bold text-gray-700">{{ $selectedDate_db->translatedFormat('d F Y') }}</span>.
            </p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3 items-center">
            {{-- Tab Switcher Kegiatan (Dhuha / Dhuhur) --}}
            <div class="bg-gray-100 p-1 rounded-xl flex items-center">
                <a href="{{ route('reports.religious', ['activity' => 'Dhuha', 'date' => $selectedDate_db->format('Y-m-d')]) }}" 
                   class="px-4 py-2 rounded-lg text-sm font-bold transition-all duration-200 {{ $selectedActivity == 'Dhuha' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                    🌞 Dhuha
                </a>
                {{-- PERBAIKAN: activity='Dhuhur' (tambah huruf h) --}}
                <a href="{{ route('reports.religious', ['activity' => 'Dhuhur', 'date' => $selectedDate_db->format('Y-m-d')]) }}" 
                   class="px-4 py-2 rounded-lg text-sm font-bold transition-all duration-200 {{ $selectedActivity == 'Dhuhur' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                    🕌 Dhuhur
                </a>
            </div>

            {{-- Filter Tanggal --}}
            <form action="{{ route('reports.religious') }}" method="GET" class="flex items-center gap-2 bg-white p-1 rounded-xl shadow-sm border border-gray-100">
                <input type="hidden" name="activity" value="{{ $selectedActivity }}">
                <input type="date" name="date" 
                       value="{{ $selectedDate_db->format('Y-m-d') }}" 
                       class="border-0 focus:ring-0 text-sm font-semibold text-gray-600 bg-transparent rounded-lg cursor-pointer">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg transition-colors shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </button>
            </form>
        </div>
    </div>

    {{-- Pesan Flash --}}
    @if (session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-xl flex items-center gap-3 shadow-sm">
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- 
        =========================================
        BAGIAN 1: KARTU RINGKASAN
        =========================================
    --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <!-- Hadir -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center group hover:shadow-md transition-all">
            <div class="w-10 h-10 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            {{-- DATA DARI CONTROLLER (INTEGER) --}}
            <h3 class="text-2xl font-extrabold text-gray-800">{{ $hadirCount }}</h3>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Hadir</p>
        </div>

        <!-- Uzur Syar'i -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center group hover:shadow-md transition-all">
            <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
            <h3 class="text-2xl font-extrabold text-gray-800">{{ $izinUzurCount }}</h3>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Uzur Syar'i</p>
        </div>

        <!-- Belum Absen -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center group hover:shadow-md transition-all">
            <div class="w-10 h-10 bg-red-100 text-red-500 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </div>
            <h3 class="text-2xl font-extrabold text-gray-800">{{ $belumAbsenCount }}</h3>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Belum Absen</p>
        </div>

        <!-- Persentase Total -->
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col items-center justify-center text-center group hover:shadow-md transition-all">
            <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
            </div>
            <h3 class="text-2xl font-extrabold text-gray-800">{{ $kehadiranPercentage }}%</h3>
            <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Partisipasi</p>
        </div>
    </div>

    {{-- 
        =========================================
        BAGIAN 2: FORM INPUT MANUAL (Sama)
        =========================================
    --}}
    <div x-data="{ openInput: false }" class="mb-8">
        <button @click="openInput = !openInput" class="flex items-center justify-between w-full px-6 py-4 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md transition-all text-left">
            <div class="flex items-center gap-3">
                <div class="bg-blue-50 p-2 rounded-lg text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Input Keagamaan Manual</h3>
                    <p class="text-xs text-gray-500">Input manual untuk siswa yang lupa bawa kartu atau uzur.</p>
                </div>
            </div>
            <svg :class="{'rotate-180': openInput}" class="w-5 h-5 text-gray-400 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </button>

        <div x-show="openInput" x-collapse class="mt-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <form action="{{ route('reports.storeManual') }}" method="POST">
                @csrf
                <input type="hidden" name="attendance_type" value="Keagamaan">
                <input type="hidden" name="activity" value="{{ $selectedActivity }}">
                <input type="hidden" name="date" value="{{ $selectedDate_db->format('Y-m-d') }}">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label for="student_id" class="block text-sm font-bold text-gray-700 mb-2">Pilih Siswa</label>
                        <select name="student_id" id="student_id" required class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-2.5">
                            <option value="">-- Siswa Belum Absen {{ $selectedActivity }} --</option>
                            @foreach ($belumAbsenList as $student)
                                <option value="{{ $student->id }}">{{ $student->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="status" class="block text-sm font-bold text-gray-700 mb-2">Status</label>
                        <select name="status" id="status" required class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-2.5">
                            <option value="Hadir">Hadir</option>
                            <option value="Uzur Syar'i">Uzur Syar'i</option>
                        </select>
                    </div>
                    <div>
                        <label for="notes" class="block text-sm font-bold text-gray-700 mb-2">Catatan</label>
                        <input type="text" name="notes" id="notes" class="w-full rounded-xl border-gray-300 shadow-sm text-sm py-2.5">
                    </div>
                </div>
                <button type="submit" class="mt-6 px-6 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-xl">Simpan</button>
            </form>
        </div>
    </div>

    {{-- 
        =========================================
        BAGIAN 3: TABEL LOG KEAGAMAAN
        =========================================
    --}}
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h3 class="text-lg font-bold text-gray-800">Log {{ $selectedActivity }}</h3>
             <form method="POST" action="{{ route('reports.destroyReligious') }}" onsubmit="return confirm('Yakin hapus semua data?')">
                @csrf @method('DELETE')
                <input type="hidden" name="date" value="{{ $selectedDate_db->format('Y-m-d') }}">
                <input type="hidden" name="activity" value="{{ $selectedActivity }}">
                <button type="submit" class="px-4 py-2 bg-gray-100 text-gray-600 text-xs font-bold rounded-lg">Reset Data</button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Waktu Input</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Keterangan</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($todayAttendances as $attendance)
                        <tr class="hover:bg-gray-50/80 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold">
                                        {{ substr($attendance->student->name ?? 'X', 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-sm font-bold text-gray-800">{{ $attendance->student->name ?? 'Siswa Dihapus' }}</p>
                                        <p class="text-xs text-gray-400">{{ $attendance->student->student_id ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 font-medium">
                                {{ $attendance->student->schoolClass->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    // GUNAKAN status_final HASIL GROUPING
                                    $statusColor = match($attendance->status_final) {
                                        'Hadir' => 'bg-emerald-100 text-emerald-700',
                                        'Uzur Syar\'i' => 'bg-blue-100 text-blue-700',
                                        default => 'bg-red-100 text-red-700'
                                    };
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $statusColor }}">
                                    {{ $attendance->status_final }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 font-mono">
                                {{-- Waktu Input (bisa dari created_at record pertama atau time_in) --}}
                                {{ $attendance->created_at ? $attendance->created_at->format('H:i') : '--:--' }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 italic max-w-xs truncate">
                                {{ $attendance->notes_final }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button onclick="openEditModalReligious(
                                            {{ $attendance->id }}, 
                                            '{{ $attendance->student->name ?? 'Siswa' }}', 
                                            '{{ $attendance->status_final }}', 
                                            `{{ $attendance->notes_final }}`,
                                            '{{ $attendance->activity }}'
                                        )" 
                                        class="p-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    </button>
                                    <form action="{{ route('reports.delete', $attendance->id) }}" method="POST" onsubmit="return confirm('Hapus data?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <p class="text-gray-500 font-medium">Belum ada data {{ $selectedActivity }} pada tanggal ini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    {{-- MODAL EDIT KEAGAMAAN (SCRIPT) --}}
    <div id="editReligiousModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md p-6">
            <div class="flex justify-between mb-4">
                <h3 class="font-bold">Edit Keagamaan</h3>
                <button onclick="closeEditModalReligious()">X</button>
            </div>
            <form id="editReligiousForm" method="POST">
                @csrf @method('PUT')
                <p id="modal-religious-student-name" class="text-blue-800 font-bold mb-4"></p>
                <input type="hidden" name="activity" id="modal-religious-activity">
                
                <select name="status" id="modal-religious-status" class="w-full border rounded mb-4">
                    <option value="Hadir">Hadir</option>
                    <option value="Uzur Syar'i">Uzur Syar'i</option>
                </select>
                <textarea name="notes" id="modal-religious-notes" class="w-full border rounded mb-4"></textarea>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded">Simpan</button>
            </form>
        </div>
    </div>

    <script>
        const religiousModal = document.getElementById('editReligiousModal');
        const religiousForm = document.getElementById('editReligiousForm');
        const religiousStudentNameDisplay = document.getElementById('modal-religious-student-name');
        const religiousActivitySelect = document.getElementById('modal-religious-activity');
        const religiousStatusSelect = document.getElementById('modal-religious-status');
        const religiousNotesInput = document.getElementById('modal-religious-notes');
        
        function openEditModalReligious(attendanceId, studentName, currentStatus, currentNotes, currentActivity) {
            const updateRoute = '{{ route('reports.update', ['attendance' => '__ID__']) }}'.replace('__ID__', attendanceId);
            religiousForm.action = updateRoute;
            religiousStudentNameDisplay.textContent = studentName; 
            religiousActivitySelect.value = currentActivity;
            religiousStatusSelect.value = currentStatus;
            religiousNotesInput.value = currentNotes;
            religiousModal.classList.remove('hidden');
        }

        function closeEditModalReligious() {
            religiousModal.classList.add('hidden');
        }
    </script>
</x-app-layout>