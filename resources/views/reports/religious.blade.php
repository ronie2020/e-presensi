{{-- Halaman ini adalah tampilan untuk resources/views/reports/religious.blade.php --}}
<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js" defer></script>

    {{-- Header & Filter --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">
                Rekapitulasi Keagamaan
            </h1>
            <p class="text-sm text-gray-500 mt-1">
                Data <span class="font-bold text-blue-600">{{ $selectedActivity }}</span> tanggal <span class="font-bold text-gray-700">{{ $selectedDate_db->translatedFormat('d F Y') }}</span>.
            </p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3 items-center">
            {{-- Switcher Kegiatan --}}
            <div class="bg-gray-100 p-1 rounded-xl flex items-center">
                <a href="{{ route('reports.religious', ['activity' => 'Dhuha', 'date' => $selectedDate_db->format('Y-m-d')]) }}" 
                   class="px-4 py-2 rounded-lg text-sm font-bold transition-all duration-200 {{ $selectedActivity == 'Dhuha' ? 'bg-white text-blue-600 shadow-sm' : 'text-gray-500 hover:text-gray-700' }}">
                    🌞 Dhuha
                </a>
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

    {{-- STATISTIK (Ringkas) --}}
    <div class="grid grid-cols-3 gap-3 mb-6">
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 text-center">
            <h3 class="text-2xl font-bold text-emerald-600">{{ $hadirCount }}</h3>
            <p class="text-xs font-bold text-gray-400 uppercase">Hadir</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 text-center">
            <h3 class="text-2xl font-bold text-blue-600">{{ $izinUzurCount }}</h3>
            <p class="text-xs font-bold text-gray-400 uppercase">Uzur</p>
        </div>
        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 text-center">
            <h3 class="text-2xl font-bold text-red-500">{{ $belumAbsenCount }}</h3>
            <p class="text-xs font-bold text-gray-400 uppercase">Belum Absen</p>
        </div>
    </div>

    {{-- ======================================================= --}}
    {{-- TABEL DENGAN TAB (LOGIKA DARI KEGIATAN.HTML)            --}}
    {{-- ======================================================= --}}
    
    {{-- Kita gunakan x-data untuk mengatur tab aktif --}}
    <div x-data="{ activeTab: 'hadir' }" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        
        {{-- Header Tab --}}
        <div class="flex border-b border-gray-100 overflow-x-auto">
            <button @click="activeTab = 'hadir'" 
                    :class="{ 'border-b-2 border-emerald-500 text-emerald-600 bg-emerald-50/50': activeTab === 'hadir', 'text-gray-500 hover:text-gray-700 hover:bg-gray-50': activeTab !== 'hadir' }"
                    class="flex-1 py-4 px-6 text-sm font-bold transition-colors whitespace-nowrap">
                ✅ Sudah Absen
            </button>
            <button @click="activeTab = 'belum'" 
                    :class="{ 'border-b-2 border-red-500 text-red-600 bg-red-50/50': activeTab === 'belum', 'text-gray-500 hover:text-gray-700 hover:bg-gray-50': activeTab !== 'belum' }"
                    class="flex-1 py-4 px-6 text-sm font-bold transition-colors whitespace-nowrap">
                ❌ Belum Absen
            </button>
            <button @click="activeTab = 'uzur'" 
                    :class="{ 'border-b-2 border-blue-500 text-blue-600 bg-blue-50/50': activeTab === 'uzur', 'text-gray-500 hover:text-gray-700 hover:bg-gray-50': activeTab !== 'uzur' }"
                    class="flex-1 py-4 px-6 text-sm font-bold transition-colors whitespace-nowrap">
                ℹ️ Izin / Uzur
            </button>
        </div>

        {{-- Container Tabel (Scroll Horizontal Aktif) --}}
        <div class="w-full overflow-x-auto pb-4">
            
            {{-- TAB 1: HADIR --}}
            <div x-show="activeTab === 'hadir'" x-transition:enter.duration.300ms>
                <div class="p-4 flex justify-between items-center bg-gray-50 border-b border-gray-100">
                    <h3 class="font-bold text-gray-700">Data Siswa Hadir</h3>
                    <form method="POST" action="{{ route('reports.destroyReligious') }}" onsubmit="return confirm('Hapus data?')">
                        @csrf @method('DELETE')
                        <input type="hidden" name="date" value="{{ $selectedDate_db->format('Y-m-d') }}">
                        <input type="hidden" name="activity" value="{{ $selectedActivity }}">
                        <button class="text-xs text-red-600 hover:underline">Reset Data</button>
                    </form>
                </div>
                <table class="min-w-full text-left border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase whitespace-nowrap">Nama Siswa</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase whitespace-nowrap">Kelas</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase whitespace-nowrap">Waktu</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase whitespace-nowrap text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @php $hasHadir = false; @endphp
                        @foreach ($todayAttendances as $attendance)
                            @if($attendance->status_final == 'Hadir')
                                @php $hasHadir = true; @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $attendance->student->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $attendance->student->schoolClass->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-mono text-emerald-600">{{ $attendance->created_at->format('H:i') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <button onclick="openEditModalReligious({{ $attendance->id }}, '{{ $attendance->student->name }}', '{{ $attendance->status_final }}', `{{ $attendance->notes_final }}`, '{{ $attendance->activity }}')" class="text-blue-600 hover:text-blue-800 text-xs font-bold">Edit</button>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        @if(!$hasHadir) <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400 italic">Tidak ada data hadir di halaman ini.</td></tr> @endif
                    </tbody>
                </table>
                {{-- Pagination hanya muncul di tab ini --}}
                <div class="p-4 border-t border-gray-100">{{ $todayAttendances->appends(request()->query())->links() }}</div>
            </div>

            {{-- TAB 2: BELUM ABSEN --}}
            <div x-show="activeTab === 'belum'" x-transition:enter.duration.300ms style="display: none;">
                <div class="p-4 bg-gray-50 border-b border-gray-100">
                    <h3 class="font-bold text-gray-700">Siswa Belum Absen</h3>
                </div>
                <table class="min-w-full text-left border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase whitespace-nowrap">Nama Siswa</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase whitespace-nowrap">Kelas</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase whitespace-nowrap text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($belumAbsenList as $student)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $student->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $student->schoolClass->name ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    {{-- Tombol Pintas: Beri Keterangan (Logic dari kegiatan.html) --}}
                                    <button onclick="openManualModalForStudent({{ $student->id }}, '{{ $student->name }}')" 
                                            class="bg-blue-50 text-blue-600 px-3 py-1 rounded-lg text-xs font-bold hover:bg-blue-100 border border-blue-200">
                                        Beri Keterangan
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="px-6 py-8 text-center text-gray-400 italic">Semua siswa sudah absen!</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- TAB 3: UZUR / IZIN --}}
            <div x-show="activeTab === 'uzur'" x-transition:enter.duration.300ms style="display: none;">
                <div class="p-4 bg-gray-50 border-b border-gray-100">
                    <h3 class="font-bold text-gray-700">Data Izin / Uzur</h3>
                </div>
                <table class="min-w-full text-left border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase whitespace-nowrap">Nama Siswa</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase whitespace-nowrap">Kelas</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase whitespace-nowrap">Keterangan</th>
                            <th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase whitespace-nowrap text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @php $hasUzur = false; @endphp
                        @foreach ($todayAttendances as $attendance)
                            @if($attendance->status_final != 'Hadir')
                                @php $hasUzur = true; @endphp
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $attendance->student->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $attendance->student->schoolClass->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-blue-600 italic">{{ $attendance->status_final }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <button onclick="openEditModalReligious({{ $attendance->id }}, '{{ $attendance->student->name }}', '{{ $attendance->status_final }}', `{{ $attendance->notes_final }}`, '{{ $attendance->activity }}')" class="text-blue-600 hover:text-blue-800 text-xs font-bold">Edit</button>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        @if(!$hasUzur) <tr><td colspan="4" class="px-6 py-8 text-center text-gray-400 italic">Tidak ada data uzur di halaman ini.</td></tr> @endif
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    {{-- MODAL INPUT MANUAL (Untuk tombol "Beri Keterangan") --}}
    <div id="manualInputModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
            <div class="flex justify-between mb-4">
                <h3 class="font-bold text-gray-800">Input Keterangan Manual</h3>
                <button onclick="closeManualModal()" class="text-gray-400 hover:text-gray-600">&times;</button>
            </div>
            <form action="{{ route('reports.storeManual') }}" method="POST">
                @csrf
                <input type="hidden" name="attendance_type" value="Keagamaan">
                <input type="hidden" name="activity" value="{{ $selectedActivity }}">
                <input type="hidden" name="date" value="{{ $selectedDate_db->format('Y-m-d') }}">
                
                <div class="space-y-4">
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Nama Siswa</label>
                        <input type="text" id="manual-student-name-display" class="w-full bg-gray-100 border-0 rounded-lg text-gray-700 font-bold" readonly>
                        <input type="hidden" name="student_id" id="manual-student-id">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Status</label>
                        <select name="status" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            <option value="Hadir">Hadir</option>
                            <option value="Uzur Syar'i" selected>Uzur Syar'i</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-xs font-bold text-gray-500 uppercase">Catatan</label>
                        <input type="text" name="notes" class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Sakit, Izin Lomba">
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition-colors">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div id="editReligiousModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md p-6">
            <div class="flex justify-between mb-4">
                <h3 class="font-bold">Edit Data</h3>
                <button onclick="closeEditModalReligious()" class="text-gray-400">&times;</button>
            </div>
            <form id="editReligiousForm" method="POST">
                @csrf @method('PUT')
                <p id="modal-religious-student-name" class="text-blue-600 font-bold mb-4"></p>
                <input type="hidden" name="activity" id="modal-religious-activity">
                <select name="status" id="modal-religious-status" class="w-full border rounded mb-4 p-2">
                    <option value="Hadir">Hadir</option>
                    <option value="Uzur Syar'i">Uzur Syar'i</option>
                </select>
                <textarea name="notes" id="modal-religious-notes" class="w-full border rounded mb-4 p-2"></textarea>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded font-bold">Update</button>
            </form>
        </div>
    </div>

    <script>
        // Script Modal "Beri Keterangan"
        function openManualModalForStudent(id, name) {
            document.getElementById('manual-student-id').value = id;
            document.getElementById('manual-student-name-display').value = name;
            document.getElementById('manualInputModal').classList.remove('hidden');
        }
        function closeManualModal() {
            document.getElementById('manualInputModal').classList.add('hidden');
        }

        // Script Modal Edit
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