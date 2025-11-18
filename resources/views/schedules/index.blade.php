{{-- Halaman ini adalah tampilan untuk resources/views/schedules/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Jadwal & Kehadiran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Tampilkan pesan sukses jika ada --}}
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Tampilkan error validasi jika ada --}}
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- 1. Form Pengaturan Jadwal Sekolah (Reguler) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium mb-4">Pengaturan Jadwal Sekolah</h3>
                    <form action="{{ route('schedules.regular.store') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <!-- Jadwal Hari Biasa (Senin-Kamis) -->
                            <div>
                                <h4 class="font-medium text-gray-700">Jadwal Hari Biasa (Senin-Kamis)</h4>
                                <input type="hidden" name="day_type[]" value="Biasa">
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-2">
                                    <div>
                                        <label class="block text-sm">Masuk Mulai</label>
                                        <input type="time" name="start_in[]" value="{{ $regularSchedules['Biasa']->start_in ?? '05:30' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm">Masuk Akhir</label>
                                        <input type="time" name="end_in[]" value="{{ $regularSchedules['Biasa']->end_in ?? '07:00' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm">Pulang Mulai</label>
                                        <input type="time" name="start_out[]" value="{{ $regularSchedules['Biasa']->start_out ?? '14:20' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm">Pulang Akhir</label>
                                        <input type="time" name="end_out[]" value="{{ $regularSchedules['Biasa']->end_out ?? '17:00' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Jadwal Hari Jum'at -->
                            <div>
                                <h4 class="font-medium text-gray-700">Jadwal Hari Jum'at</h4>
                                <input type="hidden" name="day_type[]" value="Jumat">
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-2">
                                    <div>
                                        <label class="block text-sm">Masuk Mulai</label>
                                        <input type="time" name="start_in[]" value="{{ $regularSchedules['Jumat']->start_in ?? '05:30' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm">Masuk Akhir</label>
                                        <input type="time" name="end_in[]" value="{{ $regularSchedules['Jumat']->end_in ?? '07:00' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm">Pulang Mulai</label>
                                        <input type="time" name="start_out[]" value="{{ $regularSchedules['Jumat']->start_out ?? '11:00' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm">Pulang Akhir</label>
                                        <input type="time" name="end_out[]" value="{{ $regularSchedules['Jumat']->end_out ?? '15:00' }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="mt-6 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                            Simpan Jadwal Reguler
                        </button>
                    </form>
                </div>
            </div>

            <!-- 2. Form Jadwal Khusus & Hari Libur -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kolom Form -->
                        <div>
                            <h3 class="text-lg font-medium mb-4">Tambah Jadwal Khusus / Hari Libur</h3>
                            <form action="{{ route('schedules.special.store') }}" method="POST">
                                @csrf
                                
                                <div class="mb-4">
                                    <label class="block text-sm">Tanggal</label>
                                    <input type="date" name="date" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm">Deskripsi (Opsional)</label>
                                    <input type="text" name="description" placeholder="Contoh: Ujian Akhir Semester" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                </div>
                                
                                <div class="mb-4">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" name="is_holiday" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm">
                                        <span class="ms-2 text-sm text-gray-600">Tandai sebagai Hari Libur (Tidak ada KBM)</span>
                                    </label>
                                </div>

                                <h4 class="font-medium text-gray-700 text-sm mt-6 mb-2">Jam Khusus (Isi jika BUKAN hari libur)</h4>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm">Masuk Mulai</label>
                                        <input type="time" name="start_in" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm">Masuk Akhir</label>
                                        <input type="time" name="end_in" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm">Pulang Mulai</label>
                                        <input type="time" name="start_out" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                    <div>
                                        <label class="block text-sm">Pulang Akhir</label>
                                        <input type="time" name="end_out" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    </div>
                                </div>
                                
                                <button type="submit" class="mt-6 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                    Simpan Jadwal Khusus
                                </button>
                            </form>
                        </div>
                        
                        <!-- Kolom Daftar -->
                        <div>
                            <h3 class="text-lg font-medium mb-4">Daftar Jadwal Khusus</h3>
                            <div class="overflow-x-auto border rounded-lg max-h-96 overflow-y-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keterangan</th>
                                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse ($specialSchedules as $schedule)
                                            <tr>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                    {{ \Carbon\Carbon::parse($schedule->date)->translatedFormat('l, d M Y') }}
                                                    @if($schedule->is_holiday)
                                                        <span class="ms-2 inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10">Libur</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $schedule->description }}
                                                </td>
                                                <td class="px-4 py-3 whitespace-nowrap text-sm">
                                                    <form action="{{ route('schedules.special.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jadwal khusus ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                                    Belum ada jadwal khusus.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>