<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">
                {{ __('Monitoring') }}
            </h2>
            <div class="flex items-center gap-2 md:gap-3">
                <!-- Auto Refresh (Compact di Mobile) -->
                <div x-data="{ count: 30 }" x-init="setInterval(() => { count > 0 ? count-- : location.reload() }, 1000)" 
                     class="text-[10px] md:text-xs font-bold text-slate-400 bg-white px-2 py-1 md:px-3 md:py-1.5 rounded-lg border border-slate-200 shadow-sm flex items-center gap-1">
                    <i class="ph-bold ph-arrows-clockwise animate-spin-slow"></i>
                    <span class="hidden md:inline">Auto-refresh:</span> 
                    <span x-text="count">30</span>s
                </div>
                <a href="{{ route('cbt.index') }}" class="text-xs md:text-sm font-bold text-slate-500 hover:text-slate-800 transition px-2 py-1 bg-slate-100 rounded-lg">
                    Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 md:space-y-6">

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-r shadow-sm text-sm" role="alert">
                    <p class="font-bold">Sukses!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <!-- INFO HEADER: Stack di HP -->
            <div class="bg-white p-4 md:p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide {{ $exam->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $exam->is_active ? 'AKTIF' : 'NON-AKTIF' }}
                        </span>
                        <span class="text-slate-400 text-xs font-bold">Kelas {{ $exam->class_level }}</span>
                    </div>
                    <h3 class="text-xl md:text-2xl font-black text-slate-800">{{ $exam->title }}</h3>
                </div>

                <!-- STATISTIK REALTIME -->
                <div class="flex gap-2 w-full md:w-auto overflow-x-auto pb-1 md:pb-0">
                    <div class="flex-1 min-w-[90px] md:w-32 bg-blue-50 p-2 md:p-3 rounded-xl border border-blue-100 text-center">
                        <h4 class="text-xl md:text-2xl font-black text-blue-600">{{ $stats['working'] }}</h4>
                        <p class="text-[10px] uppercase font-bold text-blue-400">Ujian</p>
                    </div>
                    <div class="flex-1 min-w-[90px] md:w-32 bg-green-50 p-2 md:p-3 rounded-xl border border-green-100 text-center">
                        <h4 class="text-xl md:text-2xl font-black text-green-600">{{ $stats['finished'] }}</h4>
                        <p class="text-[10px] uppercase font-bold text-green-400">Selesai</p>
                    </div>
                    <div class="flex-1 min-w-[90px] md:w-32 bg-slate-50 p-2 md:p-3 rounded-xl border border-slate-200 text-center">
                        <h4 class="text-xl md:text-2xl font-black text-slate-600">{{ $stats['not_started'] }}</h4>
                        <p class="text-[10px] uppercase font-bold text-slate-400">Belum</p>
                    </div>
                </div>
            </div>

            <!-- TABEL PESERTA (Hybrid View) -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden" x-data="{ search: '' }">
                <div class="p-4 border-b border-slate-100 bg-slate-50 flex flex-col md:flex-row justify-between items-center gap-3">
                    <h4 class="font-bold text-slate-700 flex items-center gap-2 text-sm md:text-base w-full md:w-auto">
                        <i class="ph-bold ph-users-three"></i> Peserta ({{ $stats['total_students'] }})
                    </h4>
                    <input type="text" x-model="search" placeholder="Cari siswa..." class="text-sm border-slate-300 rounded-lg px-3 py-2 w-full md:w-64">
                </div>
                
                <!-- DESKTOP TABLE -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-white text-slate-500 font-bold uppercase text-[11px] border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-3 w-10">No</th>
                                <th class="px-6 py-3">Nama Siswa</th>
                                <th class="px-6 py-3">Status</th>
                                <th class="px-6 py-3">Mulai</th>
                                <th class="px-6 py-3 text-center">Skor</th>
                                <th class="px-6 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($monitoringData as $index => $student)
                            <tr x-show="search === '' || '{{ strtolower($student->name) }}'.includes(search.toLowerCase())" 
                                class="hover:bg-slate-50 transition {{ $student->is_active ? 'bg-blue-50/30' : '' }}">
                                <td class="px-6 py-3 font-medium">{{ $index + 1 }}</td>
                                <td class="px-6 py-3 font-bold text-slate-800">{{ $student->name }}</td>
                                <td class="px-6 py-3">
                                    <!-- Badges Status (Sama seperti sebelumnya) -->
                                    @if($student->status == 'Sedang Mengerjakan')
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                                            <span class="animate-ping h-2 w-2 rounded-full bg-blue-500 opacity-75"></span> Mengerjakan
                                        </span>
                                    @elseif($student->status == 'Selesai')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Selesai</span>
                                    @else
                                        <span class="text-xs font-bold text-slate-400">Belum</span>
                                    @endif
                                </td>
                                <td class="px-6 py-3 text-xs">{{ $student->start_time }}</td>
                                <td class="px-6 py-3 text-center font-bold text-slate-800">{{ $student->score }}</td>
                                <td class="px-6 py-3 text-right">
                                    @if($student->is_active)
                                        <form action="{{ route('cbt.reset', ['exam' => $exam->id, 'student' => $student->id]) }}" method="POST" onsubmit="return confirm('Reset login?')">
                                            @csrf
                                            <button class="text-xs font-bold text-red-500 hover:text-red-700 bg-red-50 px-3 py-1 rounded-lg">Reset</button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- MOBILE CARD VIEW -->
                <div class="md:hidden bg-slate-50 p-3 space-y-3">
                    @foreach($monitoringData as $index => $student)
                    <div x-show="search === '' || '{{ strtolower($student->name) }}'.includes(search.toLowerCase())" 
                         class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm relative overflow-hidden">
                        
                        <!-- Status Bar di kiri -->
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $student->status == 'Sedang Mengerjakan' ? 'bg-blue-500' : ($student->status == 'Selesai' ? 'bg-green-500' : 'bg-slate-200') }}"></div>
                        
                        <div class="pl-3 flex justify-between items-start">
                            <div>
                                <h5 class="font-bold text-slate-800 text-sm">{{ $student->name }}</h5>
                                <p class="text-xs text-slate-400 mb-2">{{ $student->class }}</p>
                                
                                <div class="flex items-center gap-2">
                                    @if($student->status == 'Sedang Mengerjakan')
                                        <span class="text-[10px] font-bold bg-blue-100 text-blue-700 px-2 py-0.5 rounded-md flex items-center gap-1">
                                            <span class="block w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span> Sedang Ujian
                                        </span>
                                    @elseif($student->status == 'Selesai')
                                        <span class="text-[10px] font-bold bg-green-100 text-green-700 px-2 py-0.5 rounded-md">Selesai</span>
                                    @else
                                        <span class="text-[10px] font-bold bg-slate-100 text-slate-400 px-2 py-0.5 rounded-md">Belum Mulai</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Nilai & Reset -->
                            <div class="text-right">
                                <div class="bg-slate-50 rounded-lg p-2 mb-2 text-center border border-slate-100">
                                    <span class="block text-[10px] text-slate-400 font-bold uppercase">Nilai</span>
                                    <span class="block text-lg font-black text-slate-800">{{ $student->score }}</span>
                                </div>

                                @if($student->is_active)
                                    <form action="{{ route('cbt.reset', ['exam' => $exam->id, 'student' => $student->id]) }}" method="POST" onsubmit="return confirm('Reset login siswa ini?')">
                                        @csrf
                                        <button class="text-[10px] font-bold text-red-600 bg-red-50 border border-red-100 px-2 py-1.5 rounded-lg w-full">
                                            Reset Login
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</x-app-layout>