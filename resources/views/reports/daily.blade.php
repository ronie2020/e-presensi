{{-- Halaman ini adalah tampilan untuk resources/views/reports/daily.blade.php --}}
<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js" defer></script>

    {{-- Header Page --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">Rekap Absensi Harian</h1>
            <p class="text-sm text-gray-500 mt-1">Tanggal <span class="font-bold text-blue-600">{{ $selectedDate_db->translatedFormat('d F Y') }}</span>.</p>
        </div>
        <form action="{{ route('reports.daily') }}" method="GET" class="flex items-center gap-2 bg-white p-1 rounded-xl shadow-sm border">
            <input type="date" name="date" value="{{ $selectedDate_db->format('Y-m-d') }}" class="border-0 text-sm font-bold text-gray-600 rounded-lg">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg></button>
        </form>
    </div>

    {{-- Pesan Flash --}}
    @if (session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-xl font-medium text-sm">{{ session('success') }}</div>
    @endif

    {{-- Kartu Ringkas --}}
    <div class="grid grid-cols-3 gap-3 mb-6">
        <div class="bg-white p-4 rounded-xl shadow-sm border text-center"><h3 class="text-2xl font-bold text-emerald-600">{{ $hadirCount }}</h3><p class="text-[10px] font-bold text-gray-400 uppercase">Hadir</p></div>
        <div class="bg-white p-4 rounded-xl shadow-sm border text-center"><h3 class="text-2xl font-bold text-amber-500">{{ $sakitCount + $izinCount + $alfaCount }}</h3><p class="text-[10px] font-bold text-gray-400 uppercase">Ket. Lain</p></div>
        <div class="bg-white p-4 rounded-xl shadow-sm border text-center"><h3 class="text-2xl font-bold text-gray-400">{{ $belumAbsenList->count() }}</h3><p class="text-[10px] font-bold text-gray-400 uppercase">Belum Absen</p></div>
    </div>

    {{-- TABEL UTAMA --}}
    <div x-data="{ activeTab: 'hadir' }" class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        
        {{-- Tabs --}}
        <div class="flex border-b border-gray-100 overflow-x-auto">
            <button @click="activeTab = 'hadir'" :class="activeTab === 'hadir' ? 'border-b-2 border-emerald-500 text-emerald-600 bg-emerald-50/50' : 'text-gray-500'" class="flex-1 py-4 px-6 text-sm font-bold whitespace-nowrap transition-colors">✅ Hadir</button>
            <button @click="activeTab = 'belum'" :class="activeTab === 'belum' ? 'border-b-2 border-gray-500 text-gray-700 bg-gray-50/50' : 'text-gray-500'" class="flex-1 py-4 px-6 text-sm font-bold whitespace-nowrap transition-colors">⚪ Belum Absen</button>
            <button @click="activeTab = 'lain'" :class="activeTab === 'lain' ? 'border-b-2 border-amber-500 text-amber-600 bg-amber-50/50' : 'text-gray-500'" class="flex-1 py-4 px-6 text-sm font-bold whitespace-nowrap transition-colors">⚠️ Sakit / Izin / Alfa</button>
        </div>

        {{-- Content --}}
        <div class="w-full overflow-x-auto pb-4">
            
            {{-- TAB HADIR --}}
            <div x-show="activeTab === 'hadir'" x-transition:enter.duration.300ms>
                <table class="min-w-full text-left border-collapse">
                    <thead class="bg-gray-50"><tr><th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase whitespace-nowrap">Siswa</th><th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase whitespace-nowrap">Masuk</th><th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase whitespace-nowrap">Pulang</th><th class="px-6 py-3 text-right">Aksi</th></tr></thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($todayAttendances as $att)
                            @if($att->status_final == 'Hadir')
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $att->student->name }} <br> <span class="text-xs text-gray-400">{{ $att->student->schoolClass->name }}</span></td>
                                    <td class="px-6 py-4 whitespace-nowrap font-mono text-emerald-600">{{ $att->time_in_final ? \Carbon\Carbon::parse($att->time_in_final)->format('H:i') : '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-mono text-indigo-600">{{ $att->time_out_final ? \Carbon\Carbon::parse($att->time_out_final)->format('H:i') : '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right"><button onclick="openEditModal({{ $att->id }}, '{{ $att->student->name }}', '{{ $att->status_final }}', `{{ $att->notes_final }}`, '{{ $att->time_in_final }}', '{{ $att->time_out_final }}')" class="text-blue-600 font-bold text-xs">Edit</button></td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <div class="p-4">{{ $todayAttendances->appends(request()->query())->links() }}</div>
            </div>

            {{-- TAB BELUM ABSEN --}}
            <div x-show="activeTab === 'belum'" x-transition:enter.duration.300ms style="display: none;">
                <table class="min-w-full text-left border-collapse">
                    <thead class="bg-gray-50"><tr><th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase whitespace-nowrap">Siswa</th><th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase whitespace-nowrap">Kelas</th><th class="px-6 py-3 text-right">Aksi</th></tr></thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($belumAbsenList as $student)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $student->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $student->schoolClass->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <button onclick="openManualModalDaily({{ $student->id }}, '{{ $student->name }}')" class="bg-blue-50 text-blue-600 px-3 py-1 rounded-lg text-xs font-bold hover:bg-blue-100 border border-blue-200">Input Manual</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- TAB KET. LAIN --}}
            <div x-show="activeTab === 'lain'" x-transition:enter.duration.300ms style="display: none;">
                <table class="min-w-full text-left border-collapse">
                    <thead class="bg-gray-50"><tr><th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase whitespace-nowrap">Siswa</th><th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase whitespace-nowrap">Status</th><th class="px-6 py-3 text-xs font-bold text-gray-500 uppercase whitespace-nowrap">Keterangan</th><th class="px-6 py-3 text-right">Aksi</th></tr></thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($todayAttendances as $att)
                            @if($att->status_final != 'Hadir')
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ $att->student->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 py-1 rounded text-xs font-bold {{ $att->status_final == 'Alfa' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700' }}">{{ $att->status_final }}</span></td>
                                    <td class="px-6 py-4 text-sm text-gray-500 min-w-[200px]">{{ $att->notes_final }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right"><button onclick="openEditModal({{ $att->id }}, '{{ $att->student->name }}', '{{ $att->status_final }}', `{{ $att->notes_final }}`, '', '')" class="text-blue-600 font-bold text-xs">Edit</button></td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- 
        ===================================================================
        MODAL INPUT MANUAL
        ===================================================================
    --}}
    <div id="manualModalDaily" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Absensi Manual / Keterangan</h3>
                        <p class="text-sm text-gray-500" id="daily-manual-name-display">Nama Siswa</p>
                    </div>
                    <button onclick="closeManualModalDaily()" class="text-gray-400 hover:text-gray-600">&times;</button>
                </div>

                <form action="{{ route('reports.storeManual') }}" method="POST">
                    @csrf
                    <input type="hidden" name="attendance_type" value="Harian">
                    <input type="hidden" name="student_id" id="daily-manual-id">

                    <div class="space-y-4">
                        {{-- Input Tanggal --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tanggal</label>
                            <input type="date" name="date" id="daily-manual-date" value="{{ $selectedDate_db->format('Y-m-d') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" readonly>
                        </div>
                        
                        {{-- Dropdown Status (Ada ID untuk Javascript) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="daily-manual-status" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" onchange="toggleTimeInput()">
                                <option value="Hadir">Hadir (Manual)</option>
                                <option value="Sakit">Sakit</option>
                                <option value="Izin">Izin</option>
                                <option value="Alfa">Alfa</option>
                            </select>
                        </div>

                        {{-- Grid Jam Masuk & Pulang (Ada ID Container) --}}
                        <div class="grid grid-cols-2 gap-4" id="time-input-container">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Waktu Masuk</label>
                                <input type="time" name="time_in" id="daily-manual-time-in" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Waktu Pulang</label>
                                <input type="time" name="time_out" id="daily-manual-time-out" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                        </div>

                        {{-- Textarea Keterangan --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Keterangan Tambahan</label>
                            <textarea name="notes" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" onclick="closeManualModalDaily()" class="bg-gray-200 text-gray-800 py-2 px-4 rounded-md hover:bg-gray-300">Batal</button>
                        <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT --}}
    <div id="editAttendanceModal" class="fixed inset-0 bg-gray-900/50 hidden flex items-center justify-center p-4 z-50">
        <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-xl">
            <div class="flex justify-between mb-4"><h3 class="font-bold">Edit Data</h3><button onclick="closeEditModal()">x</button></div>
            <form id="editForm" method="POST">
                @csrf @method('PUT')
                <p id="modal-student-name" class="font-bold text-blue-600 mb-3"></p>
                
                {{-- Status Edit (Juga ada toggle) --}}
                <select name="status" id="modal-status" class="w-full border rounded mb-2" onchange="toggleEditTimeInput()">
                    <option value="Hadir">Hadir</option>
                    <option value="Sakit">Sakit</option>
                    <option value="Izin">Izin</option>
                    <option value="Alfa">Alfa</option>
                </select>
                
                {{-- Waktu Edit --}}
                <div class="grid grid-cols-2 gap-2 mb-2" id="edit-time-container">
                    <input type="time" name="time_in" id="modal-time_in" class="border rounded">
                    <input type="time" name="time_out" id="modal-time_out" class="border rounded">
                </div>
                
                <textarea name="notes" id="modal-notes" class="w-full border rounded mb-3"></textarea>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded font-bold">Update</button>
            </form>
        </div>
    </div>

    <script>
        // --- LOGIKA TOGGLE WAKTU (MODAL INPUT MANUAL) ---
        function toggleTimeInput() {
            const status = document.getElementById('daily-manual-status').value;
            const timeContainer = document.getElementById('time-input-container');
            
            // Jika Hadir, Tampilkan. Jika Sakit/Izin/Alfa, Sembunyikan.
            if (status === 'Hadir') {
                timeContainer.classList.remove('hidden');
            } else {
                timeContainer.classList.add('hidden');
            }
        }

        // --- LOGIKA TOGGLE WAKTU (MODAL EDIT) ---
        function toggleEditTimeInput() {
            const status = document.getElementById('modal-status').value;
            const timeContainer = document.getElementById('edit-time-container');
            
            if (status === 'Hadir') {
                timeContainer.classList.remove('hidden');
            } else {
                timeContainer.classList.add('hidden');
            }
        }

        function openManualModalDaily(id, name) { 
            document.getElementById('daily-manual-id').value = id; 
            document.getElementById('daily-manual-name-display').textContent = name; 
            
            // Set Default
            document.getElementById('daily-manual-status').value = 'Hadir';
            toggleTimeInput(); // Pastikan waktu muncul saat buka pertama kali

            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            document.getElementById('daily-manual-time-in').value = `${hours}:${minutes}`;
            
            document.getElementById('manualModalDaily').classList.remove('hidden'); 
        }
        
        function closeManualModalDaily() { 
            document.getElementById('manualModalDaily').classList.add('hidden'); 
        }
        
        // Modal Edit Logic
        const modal = document.getElementById('editAttendanceModal');
        const form = document.getElementById('editForm');
        function openEditModal(id, name, status, notes, timeIn, timeOut) {
            form.action = '{{ route('reports.update', ['attendance' => '__ID__']) }}'.replace('__ID__', id);
            document.getElementById('modal-student-name').textContent = name;
            document.getElementById('modal-status').value = status;
            document.getElementById('modal-notes').value = notes;
            document.getElementById('modal-time_in').value = timeIn ? timeIn.substring(0,5) : '';
            document.getElementById('modal-time_out').value = timeOut ? timeOut.substring(0,5) : '';
            
            toggleEditTimeInput(); // Cek status saat modal edit dibuka
            modal.classList.remove('hidden');
        }
        function closeEditModal() { modal.classList.add('hidden'); }
    </script>
</x-app-layout>