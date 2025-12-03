<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('CBT Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <div class="space-y-8">
                    <!-- Header Page -->
                    <div class="flex justify-between items-end">
                        <div>
                            <h2 class="text-2xl font-bold text-slate-800">CBT & Ujian Online</h2>
                            <p class="text-slate-500 mt-1">Manajemen jadwal ujian, bank soal, dan pemantauan hasil siswa.</p>
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('cbt.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-500/30 flex items-center gap-2">
                                <i class="ph-bold ph-plus"></i> Buat Jadwal Ujian
                            </a>
                        </div>
                    </div>

                    <!-- Statistik Ringkas (Sama seperti sebelumnya) -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                            <div class="flex justify-between items-start mb-4">
                                <div class="p-3 bg-blue-50 text-blue-600 rounded-xl"><i class="ph-fill ph-monitor-play text-2xl"></i></div>
                                <span class="px-2 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-lg">Live</span>
                            </div>
                            <h3 class="text-3xl font-black text-slate-800">{{ $stats['active_exams'] ?? 0 }}</h3>
                            <p class="text-sm text-slate-500 font-medium mt-1">Ujian Aktif</p>
                        </div>
                        <!-- ... statistik lainnya biarkan sama ... -->
                         <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                            <div class="flex justify-between items-start mb-4">
                                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl"><i class="ph-fill ph-check-circle text-2xl"></i></div>
                            </div>
                            <h3 class="text-3xl font-black text-slate-800">{{ number_format($stats['avg_score'] ?? 0, 1) }}</h3>
                            <p class="text-sm text-slate-500 font-medium mt-1">Rata-rata Nilai</p>
                        </div>
                    </div>

                    <!-- Tabel Jadwal Ujian -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                            <h3 class="font-bold text-lg text-slate-800">Jadwal Ujian Terbaru</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-slate-600">
                                <thead class="bg-slate-50 text-slate-800 font-bold uppercase text-xs">
                                    <tr>
                                        <th class="px-6 py-4">Nama Ujian</th>
                                        <th class="px-6 py-4">Mapel / Kelas</th>
                                        <th class="px-6 py-4">Waktu</th>
                                        <th class="px-6 py-4">Status</th>
                                        <th class="px-6 py-4 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($exams as $exam)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="px-6 py-4 font-bold text-slate-800">{{ $exam->title }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <span class="font-bold">{{ $exam->subject_name }}</span>
                                                <span class="text-xs">Kelas {{ $exam->class_level }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <span class="font-bold">{{ \Carbon\Carbon::parse($exam->start_time)->format('d M Y') }}</span>
                                                <span class="text-xs text-slate-400">
                                                    {{ \Carbon\Carbon::parse($exam->start_time)->format('H:i') }} - 
                                                    {{ \Carbon\Carbon::parse($exam->end_time)->format('H:i') }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($exam->is_active)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Aktif</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500">Non-Aktif</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-2">
                                            <!-- TOMBOL KELOLA SOAL -->
                                            <a href="{{ route('cbt.questions.manage', $exam->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-bold hover:bg-indigo-100 transition">
                                                <i class="ph-bold ph-list-numbers"></i> Kelola Soal
                                            </a>
                                            
                                            <a href="{{ route('cbt.monitoring', $exam->id) }}" class="text-slate-400 hover:text-blue-600 font-bold text-xs uppercase" title="Monitor">
                                                <i class="ph-bold ph-desktop text-lg"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-slate-500 italic">Belum ada jadwal ujian.</td>
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
</x-app-layout>