<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Kelulusan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- ALERT SUKSES/ERROR -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Berhasil!</strong>
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Error!</strong>
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- CARD 1: PENGATURAN WAKTU GLOBAL -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-indigo-100">
                <div class="p-6 bg-indigo-50/50 border-b border-indigo-100">
                    <h3 class="text-lg font-bold text-indigo-900 flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Pengaturan Waktu Pengumuman
                    </h3>
                    <p class="text-sm text-indigo-600 mt-1">Atur kapan siswa bisa melihat hasil kelulusan secara serentak.</p>
                </div>
                <div class="p-6 bg-white">
                    <form action="{{ route('admin.graduation.set_date') }}" method="POST" class="flex flex-col md:flex-row gap-4 items-end">
                        @csrf
                        <div class="w-full md:w-1/3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Kelas (Opsional)</label>
                            <select name="class_filter" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Semua Kelas --</option>
                                @foreach($classes as $cls)
                                    <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-full md:w-1/3">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal & Jam Buka</label>
                            <input type="datetime-local" name="global_date" required class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md font-bold hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                            Terapkan Tanggal
                        </button>
                    </form>
                </div>
            </div>

            <!-- CARD 2: DAFTAR SISWA & INPUT NILAI -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-100">
                <!-- Filter & Search -->
                <div class="p-4 border-b border-gray-100 flex flex-col md:flex-row justify-between gap-4 items-center bg-gray-50">
                    <form method="GET" class="flex gap-2 w-full md:w-auto">
                        <select name="class_id" class="rounded-md border-gray-300 text-sm focus:ring-blue-500 focus:border-blue-500" onchange="this.form.submit()">
                            <option value="">-- Filter Kelas --</option>
                            @foreach($classes as $cls)
                                <option value="{{ $cls->id }}" {{ request('class_id') == $cls->id ? 'selected' : '' }}>{{ $cls->name }}</option>
                            @endforeach
                        </select>
                    </form>
                    <div class="text-sm text-gray-500">
                        Menampilkan {{ $students->count() }} dari {{ $students->total() }} siswa
                    </div>
                </div>

                <!-- Table Input -->
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-100 border-b">
                            <tr>
                                <th class="px-6 py-3">Identitas Siswa</th>
                                <th class="px-6 py-3 text-center">Status</th>
                                <th class="px-6 py-3 text-center">Nilai Rata-rata</th>
                                <th class="px-6 py-3 text-center">No. SKL</th>
                                <th class="px-6 py-3 text-center">Waktu Pengumuman</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($students as $student)
                            <tr class="bg-white hover:bg-gray-50 transition">
                                <form action="{{ route('admin.graduation.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="student_id" value="{{ $student->id }}">
                                    
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-900">{{ $student->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $student->student_id }} | {{ $student->schoolClass->name ?? '-' }}</div>
                                    </td>
                                    
                                    <td class="px-6 py-4 text-center">
                                        <select name="status" class="text-xs rounded-full border-gray-200 py-1 px-3 font-bold focus:ring-2 focus:ring-blue-500 {{ ($student->graduation->status ?? '') == 'LULUS' ? 'bg-green-100 text-green-700' : 'bg-red-50 text-red-700' }}">
                                            <option value="LULUS" {{ ($student->graduation->status ?? '') == 'LULUS' ? 'selected' : '' }}>LULUS</option>
                                            <option value="TIDAK LULUS" {{ ($student->graduation->status ?? '') == 'TIDAK LULUS' ? 'selected' : '' }}>TIDAK</option>
                                            <option value="DITUNDA" {{ ($student->graduation->status ?? '') == 'DITUNDA' ? 'selected' : '' }}>PENDING</option>
                                        </select>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <input type="number" step="0.01" name="average_score" value="{{ $student->graduation->average_score ?? '' }}" class="w-20 text-center text-sm border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="0.00">
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <input type="text" name="skl_number" value="{{ $student->graduation->skl_number ?? '' }}" class="w-32 text-xs border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="Nomor SKL">
                                    </td>

                                    <td class="px-6 py-4">
                                        <input type="datetime-local" name="announcement_date" value="{{ isset($student->graduation->announcement_date) ? \Carbon\Carbon::parse($student->graduation->announcement_date)->format('Y-m-d\TH:i') : '' }}" class="w-full text-xs border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                    </td>
                                    
                                    <td class="px-6 py-4 text-center">
                                        <button type="submit" class="text-white bg-blue-600 hover:bg-blue-700 p-2 rounded-lg transition shadow-md" title="Simpan Data">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                                            </svg>
                                        </button>
                                    </td>
                                </form>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-4 border-t border-gray-100">
                    {{ $students->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>