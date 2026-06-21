<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Riwayat Pembaca Materi') }}
            </h2>
            <a href="{{ route('lms.materials.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-lg text-sm font-bold hover:bg-gray-600 transition">
                <i class="ph-bold ph-arrow-left"></i> Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 mb-6">
                <div class="p-6 md:p-8 bg-blue-50 border-b border-blue-100 flex flex-col md:flex-row gap-4 justify-between items-center">
                    <div>
                        <span class="text-xs font-bold bg-blue-200 text-blue-800 px-3 py-1 rounded-full uppercase tracking-wider mb-2 inline-block">Detail Materi</span>
                        <h3 class="text-2xl font-black text-gray-900">{{ $material->title }}</h3>
                        <p class="text-sm font-medium text-gray-600 mt-1">Mata Pelajaran: {{ $material->subject->name }}</p>
                    </div>
                    <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 text-center min-w-[150px]">
                        <p class="text-xs font-bold text-gray-500 uppercase">Total Pembaca</p>
                        <p class="text-3xl font-black text-blue-600">{{ $logs->count() }} <span class="text-sm text-gray-400">Siswa</span></p>
                    </div>
                </div>

                <div class="p-6">
                    @if($logs->isEmpty())
                        <div class="text-center py-10 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                            <i class="ph-duotone ph-clock text-5xl text-gray-400 mb-3"></i>
                            <h4 class="text-lg font-bold text-gray-700">Belum ada data</h4>
                            <p class="text-gray-500 text-sm mt-1">Belum ada siswa yang membuka dan membaca materi ini.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Siswa</th>
                                        <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Kelas</th>
                                        <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Durasi Belajar (Time-on-Task)</th>
                                        <th class="px-6 py-4 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Terakhir Akses</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($logs as $log)
                                        @php
                                            $minutes = floor($log->time_spent_seconds / 60);
                                            $seconds = $log->time_spent_seconds % 60;
                                            
                                            // Warna indikator (Hijau jika baca > 3 menit, Merah jika < 1 menit)
                                            $color = 'text-green-600 bg-green-50 border-green-200';
                                            $icon = 'ph-check-circle';
                                            if($log->time_spent_seconds < 60) {
                                                $color = 'text-red-600 bg-red-50 border-red-200';
                                                $icon = 'ph-warning-circle';
                                            } elseif($log->time_spent_seconds < 180) {
                                                $color = 'text-yellow-600 bg-yellow-50 border-yellow-200';
                                                $icon = 'ph-clock';
                                            }
                                        @endphp
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="font-bold text-gray-900">{{ $log->student->name }}</div>
                                                <div class="text-xs text-gray-500">NIS: {{ $log->student->nis }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-700">
                                                {{ $log->student->schoolClass->name ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-bold border {{ $color }}">
                                                    <i class="ph-fill {{ $icon }} text-lg"></i>
                                                    {{ $minutes }} Menit {{ $seconds }} Detik
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $log->updated_at->translatedFormat('d M Y, H:i') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>