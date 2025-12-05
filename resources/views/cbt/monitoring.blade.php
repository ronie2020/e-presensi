<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Monitoring Ujian') }}
            </h2>
            <div class="flex items-center gap-3">
                <!-- Indikator Auto Refresh -->
                <div x-data="{ count: 30 }" x-init="setInterval(() => { count > 0 ? count-- : location.reload() }, 1000)" class="text-xs font-bold text-slate-400 bg-white px-3 py-1.5 rounded-lg border border-slate-200 shadow-sm hidden md:block">
                    Auto-refresh: <span x-text="count">30</span>s
                </div>
                <a href="{{ route('cbt.index') }}" class="text-sm font-bold text-slate-500 hover:text-slate-800 transition">
                    &larr; Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                    <span @click="show = false" class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer">
                        <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><title>Close</title><path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/></svg>
                    </span>
                </div>
            @endif

            <!-- INFO HEADER -->
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row justify-between items-center gap-6">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide {{ $exam->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $exam->is_active ? 'SEDANG AKTIF' : 'NON-AKTIF' }}
                        </span>
                        <span class="text-slate-400 text-xs font-bold">Kelas {{ $exam->class_level }}</span>
                    </div>
                    <h3 class="text-2xl font-black text-slate-800">{{ $exam->title }}</h3>
                    <p class="text-slate-500 text-sm font-medium">{{ $exam->subject_name }} &bull; {{ $exam->questions_count }} Soal &bull; {{ $exam->duration_minutes }} Menit</p>
                </div>

                <!-- STATISTIK REALTIME -->
                <div class="flex gap-4 w-full md:w-auto">
                    <div class="flex-1 md:w-32 bg-blue-50 p-3 rounded-xl border border-blue-100 text-center">
                        <h4 class="text-2xl font-black text-blue-600">{{ $stats['working'] }}</h4>
                        <p class="text-[10px] uppercase font-bold text-blue-400">Mengerjakan</p>
                    </div>
                    <div class="flex-1 md:w-32 bg-green-50 p-3 rounded-xl border border-green-100 text-center">
                        <h4 class="text-2xl font-black text-green-600">{{ $stats['finished'] }}</h4>
                        <p class="text-[10px] uppercase font-bold text-green-400">Selesai</p>
                    </div>
                    <div class="flex-1 md:w-32 bg-slate-50 p-3 rounded-xl border border-slate-200 text-center">
                        <h4 class="text-2xl font-black text-slate-600">{{ $stats['not_started'] }}</h4>
                        <p class="text-[10px] uppercase font-bold text-slate-400">Belum Mulai</p>
                    </div>
                </div>
            </div>

            <!-- TABEL PESERTA -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden" x-data="{ search: '' }">
                <div class="p-4 border-b border-slate-100 bg-slate-50 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <h4 class="font-bold text-slate-700 flex items-center gap-2">
                        <i class="ph-bold ph-users-three"></i> Daftar Peserta ({{ $stats['total_students'] }})
                    </h4>
                    
                    <!-- Search Sederhana -->
                    <input type="text" x-model="search" placeholder="Cari nama siswa..." class="text-sm border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 px-3 py-1.5 w-full sm:w-64">
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-white text-slate-500 font-bold uppercase text-[11px] border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-3 w-10">No</th>
                                <th class="px-6 py-3">Nama Siswa</th>
                                <th class="px-6 py-3">Kelas</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3">Waktu Mulai</th>
                                <th class="px-6 py-3 text-center">Nilai Sementara</th>
                                <th class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($monitoringData as $index => $student)
                            <tr x-show="search === '' || '{{ strtolower($student->name) }}'.includes(search.toLowerCase())" 
                                class="hover:bg-slate-50 transition {{ $student->is_active ? 'bg-blue-50/30' : '' }}">
                                <td class="px-6 py-3 font-medium">{{ $index + 1 }}</td>
                                <td class="px-6 py-3">
                                    <div class="font-bold text-slate-800">{{ $student->name }}</div>
                                </td>
                                <td class="px-6 py-3">
                                    <span class="bg-slate-100 text-slate-600 px-2 py-0.5 rounded text-xs font-bold border border-slate-200">
                                        {{ $student->class }}
                                    </span>
                                </td>
                                <td class="px-6 py-3">
                                    @if($student->status == 'Sedang Mengerjakan')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200">
                                            <span class="relative flex h-2 w-2">
                                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                                              <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-500"></span>
                                            </span>
                                            Mengerjakan
                                        </span>
                                    @elseif($student->status == 'Selesai')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700 border border-green-200">
                                            <i class="ph-bold ph-check mr-1"></i> Selesai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                            Belum Mulai
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 font-mono text-xs">
                                    {{ $student->start_time }}
                                </td>
                                <td class="px-6 py-3 text-center">
                                    @if($student->score !== '-')
                                        <span class="font-black text-slate-800 text-base">{{ $student->score }}</span>
                                    @else
                                        <span class="text-slate-300">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-right">
                                    @if($student->is_active)
                                        <form onsubmit="return confirm('RESET LOGIN SISWA?\n\nTindakan ini akan menghapus sesi ujian siswa. Siswa harus login ulang untuk melanjutkan. Jawaban yang sudah tersimpan di server aman.')" 
                                              action="{{ route('cbt.reset', ['exam' => $exam->id, 'student' => $student->id]) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="text-xs font-bold text-red-500 hover:text-red-700 hover:bg-red-50 px-3 py-1.5 rounded-lg transition border border-transparent hover:border-red-200">
                                                Reset Login
                                            </button>
                                        </form>
                                    @elseif($student->status == 'Selesai')
                                        <button disabled class="text-xs font-bold text-green-600 opacity-50 cursor-not-allowed">
                                            Terkunci
                                        </button>
                                    @else
                                        <span class="text-slate-300 text-xs italic">Menunggu...</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-slate-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <p class="font-bold">Tidak ada siswa ditemukan</p>
                                        <p class="text-xs">Pastikan ada siswa di Kelas {{ $exam->class_level }}.</p>
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