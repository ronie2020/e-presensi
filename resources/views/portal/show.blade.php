@extends('layouts.public')

@section('content')
    <!-- Header -->
    <div class="mb-6 text-center">
        <h1 class="text-3xl font-bold text-gray-800">Portal Siswa</h1>
        <p class="text-gray-600 mt-1">Cek Rekap Kehadiran & Poin Disiplin Anda</p>
    </div>

    <!-- Kotak Selamat Datang -->
    <div class="bg-white p-6 rounded-xl shadow-lg mb-8 max-w-2xl mx-auto text-center">
        <p class="text-gray-600">Selamat Datang,</p>
        <h2 class="text-2xl font-bold text-blue-800 mt-1">{{ $student->name }}</h2>
        <p class="text-gray-500 font-medium">Kelas: {{ $student->schoolClass->name ?? 'N/A' }}</p>
    </div>

    <!-- Rekap Kehadiran -->
    <div class="mb-8">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Rekap Kehadiran Tahun Ini</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <!-- Hadir -->
            <div class="bg-white p-5 rounded-xl shadow border-l-4 border-green-500">
                <p class="text-sm font-medium text-gray-500">Hadir</p>
                <p class="text-4xl font-bold text-green-600">{{ $hadir }}</p>
            </div>
            <!-- Sakit -->
            <div class="bg-white p-5 rounded-xl shadow border-l-4 border-blue-500">
                <p class="text-sm font-medium text-gray-500">Sakit</p>
                <p class="text-4xl font-bold text-blue-600">{{ $sakit }}</p>
            </div>
            <!-- Izin -->
            <div class="bg-white p-5 rounded-xl shadow border-l-4 border-yellow-500">
                <p class="text-sm font-medium text-gray-500">Izin</p>
                <p class="text-4xl font-bold text-yellow-600">{{ $izin }}</p>
            </div>
            <!-- Alpa -->
            <div class="bg-white p-5 rounded-xl shadow border-l-4 border-red-500">
                <p class="text-sm font-medium text-gray-500">Alpa</p>
                <p class="text-4xl font-bold text-red-600">{{ $alpa }}</p>
            </div>
        </div>
    </div>

    <!-- Rekap Poin Disiplin -->
    <div class="mb-8">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Rekap Poin Disiplin</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Pelanggaran -->
            <div class="bg-white p-5 rounded-xl shadow border-l-4 border-red-500">
                <p class="text-sm font-medium text-gray-500">Total Poin Pelanggaran</p>
                <p class="text-4xl font-bold text-red-600">{{ $poin_pelanggaran ?? 0 }}</p>
            </div>
            <!-- Kebaikan -->
            <div class="bg-white p-5 rounded-xl shadow border-l-4 border-green-500">
                <p class="text-sm font-medium text-gray-500">Total Poin Kebaikan</p>
                <p class="text-4xl font-bold text-green-600">{{ $poin_kebaikan ?? 0 }}</p>
            </div>
        </div>
    </div>

    <!-- Riwayat Catatan Disiplin -->
    <div>
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Riwayat Catatan Disiplin</h3>
        <div class="bg-white rounded-xl shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Catatan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Poin</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dicatat Oleh</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($discipline_history as $record)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                    {{ \Carbon\Carbon::parse($record->date)->translatedFormat('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 font-medium">
                                    {{ $record->disciplineType->name ?? 'N/A' }} <!-- Asumsi: 'discipline_types' punya kolom 'name' -->
                                    <p class="text-xs text-gray-500">{{ $record->notes ?? '' }}</p> <!-- Menggunakan kolom 'notes' -->
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold 
                                    {{ $record->disciplineType->type == 'Kebaikan' ? 'text-green-600' : 'text-red-600' }}"> <!-- Cek type dari relasi -->
                                    {{ $record->disciplineType->type == 'Kebaikan' ? '+' : '-' }}{{ $record->disciplineType->point_value ?? 0 }} <!-- PERBAIKAN: points -> point_value -->
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $record->recorder->name ?? 'Sistem' }} <!-- Ambil nama dari relasi 'recorder' -->
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                    Tidak ada riwayat catatan disiplin.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection