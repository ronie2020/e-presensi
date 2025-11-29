<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Rekap Kehadiran Ekstrakurikuler</h2>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <!-- Filter -->
                <form method="GET" action="{{ route('extracurriculars.reports') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-6 items-end">
                    
                    <!-- Kolom 1: Pilih Kegiatan (Lebar 4/12) -->
                    <div class="md:col-span-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kegiatan</label>
                        <select name="ekskul_id" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">-- Semua Kegiatan --</option>
                            @foreach($extracurriculars as $ekskul)
                                <option value="{{ $ekskul->id }}" {{ $selectedEkskulId == $ekskul->id ? 'selected' : '' }}>{{ $ekskul->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Kolom 2: Dari Tanggal (Lebar 3/12) -->
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Kolom 3: Sampai Tanggal (Lebar 3/12) -->
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Kolom 4: Tombol Aksi (Lebar 2/12) -->
                    <div class="md:col-span-2 flex gap-2">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 w-full md:w-auto shadow-sm transition-colors">
                            Filter
                        </button>
                        
                        @if($selectedEkskulId)
                            <a href="{{ route('extracurriculars.reports.export', request()->query()) }}" target="_blank" class="bg-green-600 text-white px-3 py-2 rounded-md hover:bg-green-700 shadow-sm transition-colors flex items-center justify-center" title="Cetak Laporan">
                                <i class="ph-bold ph-printer text-xl"></i>
                            </a>
                        @endif
                    </div>
                </form>

                <!-- Tabel Log -->
                <div class="overflow-x-auto border rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jam</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Siswa</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kegiatan</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($attendances as $log)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($log->date)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-600">
                                    {{ $log->time_in }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-700">
                                    {{ $log->student->schoolClass->name ?? $log->student->class_name ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800">
                                    {{ $log->student->name }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                                        {{ $log->extracurricular->name }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500 italic">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="ph-duotone ph-clipboard-text text-4xl mb-2 text-gray-300"></i>
                                        <span>Tidak ada data kehadiran pada periode ini.</span>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>