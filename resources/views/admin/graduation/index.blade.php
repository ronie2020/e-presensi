<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Kelulusan') }}
            </h2>
            <div class="flex gap-2">
                {{-- Tombol Auto Generate --}}
                <button onclick="openModal('modalGenerate')" class="bg-gray-800 hover:bg-gray-900 text-white text-sm font-bold py-2 px-4 rounded-lg shadow flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                    Auto Generate SKL
                </button>
                
                {{-- Tombol Import --}}
                <button onclick="openModal('modalImport')" class="bg-green-600 hover:bg-green-700 text-white text-sm font-bold py-2 px-4 rounded-lg shadow flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                    Import Nilai (CSV)
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- STATISTIK -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white p-4 rounded-lg shadow border-l-4 border-blue-500">
                    <div class="text-sm text-gray-500">Total Siswa</div>
                    <div class="text-2xl font-bold">{{ $students->total() }}</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow border-l-4 border-green-500">
                    <div class="text-sm text-gray-500">Lulus</div>
                    <div class="text-2xl font-bold text-green-600">{{ \App\Models\Graduation::where('status', 'LULUS')->count() }}</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow border-l-4 border-red-500">
                    <div class="text-sm text-gray-500">Tidak Lulus</div>
                    <div class="text-2xl font-bold text-red-600">{{ \App\Models\Graduation::where('status', 'TIDAK LULUS')->count() }}</div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow border-l-4 border-yellow-500">
                    <div class="text-sm text-gray-500">Ditunda</div>
                    <div class="text-2xl font-bold text-yellow-600">{{ \App\Models\Graduation::where('status', 'DITUNDA')->count() }}</div>
                </div>
            </div>

            <!-- ALERT SYSTEM -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative shadow-sm">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative shadow-sm">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative shadow-sm">
                    <ul class="list-disc list-inside">@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul>
                </div>
            @endif

            <!-- INFO JADWAL & PENGATURAN -->
            @php
                $sampleSchedule = \App\Models\Graduation::whereNotNull('announcement_date')->orderBy('updated_at', 'desc')->value('announcement_date');
                $scheduleCarbon = $sampleSchedule ? \Carbon\Carbon::parse($sampleSchedule) : null;
                $isSet = $scheduleCarbon != null;
                $isPast = $isSet && \Carbon\Carbon::now()->greaterThanOrEqualTo($scheduleCarbon);
            @endphp

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-indigo-100">
                <div class="p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    
                    <!-- Kiri: Informasi Status -->
                    <div class="flex-1">
                        <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Status Jadwal Pengumuman
                        </h3>
                        
                        <div class="mt-3">
                            @if($isSet)
                                <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $isPast ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                    <span class="w-2 h-2 mr-2 rounded-full {{ $isPast ? 'bg-green-500' : 'bg-blue-500 animate-pulse' }}"></span>
                                    {{ $isPast ? 'SUDAH DIBUKA (Siswa bisa melihat)' : 'TERJADWAL (Menunggu waktu)' }}
                                </div>
                                <p class="text-gray-600 mt-2 text-sm">
                                    Waktu diset pada: <strong class="text-gray-900">{{ $scheduleCarbon->isoFormat('D MMMM Y, HH:mm') }} WIB</strong>
                                </p>
                            @else
                                <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                    <span class="w-2 h-2 mr-2 rounded-full bg-yellow-500"></span>
                                    BELUM DIATUR
                                </div>
                                <p class="text-gray-500 mt-2 text-sm italic">Siswa tidak akan bisa melihat hasil sampai Anda mengatur tanggal.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Kanan: Form Setting -->
                    <div class="w-full md:w-1/2 bg-gray-50 p-4 rounded-xl border border-gray-200">
                        <form action="{{ route('admin.graduation.set_date') }}" method="POST">
                            @csrf
                            <label class="block text-sm font-medium text-gray-700 mb-2">Atur Ulang Waktu Pengumuman (Serentak)</label>
                            <div class="flex gap-2">
                                <input type="datetime-local" name="global_date" required 
                                       value="{{ $isSet ? $scheduleCarbon->format('Y-m-d\TH:i') : '' }}"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md shadow-sm text-sm whitespace-nowrap">
                                    Simpan Jadwal
                                </button>
                            </div>
                            <p class="text-xs text-gray-400 mt-2">*Pengaturan ini akan diterapkan ke semua siswa Kelas 9.</p>
                        </form>
                    </div>
                </div>
            </div>

            <!-- TABEL DAN FORM -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100 relative">
                <div class="p-4 border-b bg-gray-50 flex justify-between items-center">
                    <form method="GET" class="flex gap-2">
                        <input type="text" name="search" placeholder="Cari Nama/NISN..." value="{{ request('search') }}" class="rounded-md border-gray-300 text-sm">
                        <select name="class_id" class="rounded-md border-gray-300 text-sm" onchange="this.form.submit()">
                            <option value="">-- Semua Kelas --</option>
                            @foreach($classes as $cls)
                                <option value="{{ $cls->id }}" {{ request('class_id') == $cls->id ? 'selected' : '' }}>{{ $cls->name }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="bg-blue-500 text-white px-3 py-2 rounded text-sm">Filter</button>
                    </form>
                </div>

                <!-- Form Bulk Update (Form Besar) -->
                <form action="{{ route('admin.graduation.bulk_update') }}" method="POST">
                    @csrf
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-100 border-b">
                                <tr>
                                    <th class="px-6 py-3">Nama Siswa</th>
                                    <th class="px-6 py-3 text-center">Status</th>
                                    <th class="px-6 py-3 text-center">Nilai</th>
                                    <th class="px-6 py-3 text-center">No. SKL</th>
                                    <th class="px-6 py-3 text-center">Waktu Pengumuman</th>
                                    <th class="px-6 py-3 text-center">Aksi</th> <!-- Kolom Baru -->
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($students as $student)
                                <tr class="bg-white hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900">{{ $student->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $student->student_id }} | {{ $student->schoolClass->name ?? '-' }}</div>
                                    </td>
                                    
                                    <!-- Status -->
                                    <td class="px-6 py-4 text-center">
                                        <select name="students[{{ $student->id }}][status]" id="status_{{ $student->id }}" class="text-xs rounded border-gray-200">
                                            <option value="LULUS" {{ ($student->graduation->status ?? '') == 'LULUS' ? 'selected' : '' }}>LULUS</option>
                                            <option value="TIDAK LULUS" {{ ($student->graduation->status ?? '') == 'TIDAK LULUS' ? 'selected' : '' }}>TIDAK LULUS</option>
                                            <option value="DITUNDA" {{ ($student->graduation->status ?? '') == 'DITUNDA' ? 'selected' : '' }}>DITUNDA</option>
                                        </select>
                                    </td>
                                    
                                    <!-- Nilai -->
                                    <td class="px-6 py-4 text-center">
                                        <input type="number" step="0.01" name="students[{{ $student->id }}][average_score]" id="score_{{ $student->id }}" value="{{ $student->graduation->average_score ?? 0 }}" class="w-20 text-center text-xs border-gray-300 rounded">
                                    </td>
                                    
                                    <!-- No SKL -->
                                    <td class="px-6 py-4 text-center">
                                        <input type="text" name="students[{{ $student->id }}][skl_number]" id="skl_{{ $student->id }}" value="{{ $student->graduation->skl_number ?? '' }}" class="w-32 text-xs border-gray-300 rounded">
                                    </td>
                                    
                                    <!-- Waktu -->
                                    <td class="px-6 py-4 text-center">
                                        <input type="datetime-local" name="students[{{ $student->id }}][announcement_date]" id="date_{{ $student->id }}" value="{{ isset($student->graduation->announcement_date) ? \Carbon\Carbon::parse($student->graduation->announcement_date)->format('Y-m-d\TH:i') : '' }}" class="w-full text-xs border-gray-300 rounded">
                                    </td>

                                    <!-- Tombol Simpan Per Siswa -->
                                    <td class="px-6 py-4 text-center">
                                        <button type="button" onclick="saveRow({{ $student->id }})" class="bg-blue-600 hover:bg-blue-700 text-white p-2 rounded-lg shadow-sm transition-all" title="Simpan Baris Ini">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                            </svg>
                                        </button>
                                        <span id="msg_{{ $student->id }}" class="hidden text-[10px] text-green-600 font-bold block mt-1">OK!</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Footer Simpan Massal -->
                    <div class="p-4 border-t bg-gray-50 flex justify-between items-center sticky bottom-0 shadow-lg bg-white z-10">
                        {{ $students->links() }}
                        <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-6 rounded shadow hover:bg-blue-700 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                            Simpan Perubahan (Massal)
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL 1: IMPORT CSV -->
    <div id="modalImport" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeModal('modalImport')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('admin.graduation.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Import Nilai & Status</h3>
                        <div class="mt-2">
                            <p class="text-sm text-gray-500 mb-4">Upload file CSV dengan format: <strong>NISN, Status (LULUS/TIDAK LULUS), Nilai</strong>.</p>
                            <a href="{{ route('admin.graduation.template') }}" class="text-blue-600 text-sm hover:underline mb-4 block">Download Template CSV</a>
                            <input type="file" name="file" accept=".csv, .txt" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"/>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Import</button>
                        <button type="button" onclick="closeModal('modalImport')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODAL 2: AUTO GENERATE SKL -->
    <div id="modalGenerate" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeModal('modalGenerate')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form action="{{ route('admin.graduation.auto_generate') }}" method="POST">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Auto Generate Nomor SKL</h3>
                        <div class="mt-2 space-y-4">
                            <p class="text-sm text-gray-500">Sistem akan membuat nomor SKL otomatis untuk semua siswa Kelas 9 diurutkan berdasarkan Nama.</p>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Format Nomor</label>
                                <input type="text" name="format" value="421.3/{no}/SMP.03/{year}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                                <p class="text-xs text-gray-400 mt-1">Gunakan <code>{no}</code> untuk nomor urut dan <code>{year}</code> untuk tahun.</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nomor Mulai</label>
                                <input type="number" name="start_number" value="1" class="mt-1 block w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" required>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Generate</button>
                        <button type="button" onclick="closeModal('modalGenerate')" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }
        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        // FUNGSI SIMPAN AJAX PER BARIS
        function saveRow(studentId) {
            const status = document.getElementById('status_' + studentId).value;
            const score = document.getElementById('score_' + studentId).value;
            const skl = document.getElementById('skl_' + studentId).value;
            const date = document.getElementById('date_' + studentId).value;
            
            // Efek Loading pada tombol
            const btn = event.currentTarget;
            const originalIcon = btn.innerHTML;
            btn.innerHTML = '...'; 
            btn.disabled = true;

            fetch("{{ route('admin.graduation.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    student_id: studentId,
                    status: status,
                    average_score: score,
                    skl_number: skl,
                    announcement_date: date
                })
            })
            .then(response => response.json())
            .then(data => {
                btn.innerHTML = originalIcon;
                btn.disabled = false;
                const msg = document.getElementById('msg_' + studentId);
                msg.classList.remove('hidden');
                setTimeout(() => msg.classList.add('hidden'), 2000);
            })
            .catch(error => {
                console.error('Error:', error);
                btn.innerHTML = originalIcon;
                btn.disabled = false;
                alert('Gagal menyimpan.');
            });
        }
    </script>
</x-app-layout>