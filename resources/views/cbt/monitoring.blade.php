<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-lg md:text-xl text-gray-800 leading-tight">
                {{ __('Monitoring Live') }}
            </h2>
            <div class="flex items-center gap-2 md:gap-3">
                <!-- Auto Refresh -->
                <div x-data="{ count: 30 }" x-init="setInterval(() => { count > 0 ? count-- : location.reload() }, 1000)" 
                     class="text-[10px] md:text-xs font-bold text-slate-400 bg-white px-2 py-1 md:px-3 md:py-1.5 rounded-lg border border-slate-200 shadow-sm flex items-center gap-1">
                    <i class="ph-bold ph-arrows-clockwise animate-spin-slow text-blue-500"></i>
                    <span class="hidden md:inline">Auto-refresh:</span> 
                    <span x-text="count" class="font-mono text-slate-600">30</span>s
                </div>
                <a href="{{ route('cbt.index') }}" class="text-xs md:text-sm font-bold text-slate-500 hover:text-slate-800 transition px-3 py-1.5 bg-slate-100 rounded-lg hover:bg-slate-200">
                    &larr; Kembali
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 md:py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('success'))
                <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-4 py-3 rounded-xl shadow-sm flex items-center gap-3">
                    <i class="ph-fill ph-check-circle text-xl"></i>
                    <p class="font-bold text-sm">{{ session('success') }}</p>
                </div>
            @endif

            <!-- HEADER INFO -->
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm relative overflow-hidden">
                <div class="absolute right-0 top-0 h-full w-32 bg-gradient-to-l from-blue-50 to-transparent"></div>
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wide border {{ $exam->is_active ? 'bg-green-50 text-green-700 border-green-100' : 'bg-rose-50 text-rose-700 border-rose-100' }}">
                                {{ $exam->is_active ? 'UJIAN AKTIF' : 'NON-AKTIF' }}
                            </span>
                            <span class="text-slate-400 text-xs font-bold bg-slate-50 px-2 py-1 rounded-lg border border-slate-100">Kelas {{ $exam->class_level }}</span>
                        </div>
                        <h3 class="text-2xl font-black text-slate-800 leading-none">{{ $exam->title }}</h3>
                        <p class="text-slate-400 text-xs font-bold mt-2 uppercase tracking-wider">Token: <span class="font-mono text-slate-800 text-base bg-slate-100 px-2 py-0.5 rounded">{{ $exam->token }}</span></p>
                    </div>

                    <!-- STATISTIK -->
                    <div class="flex gap-3 w-full md:w-auto overflow-x-auto pb-2 md:pb-0 no-scrollbar">
                        <div class="flex-1 min-w-[100px] bg-blue-50 p-3 rounded-2xl border border-blue-100 text-center">
                            <h4 class="text-2xl font-black text-blue-600">{{ $stats['working'] }}</h4>
                            <p class="text-[10px] uppercase font-bold text-blue-400 mt-1">Mengerjakan</p>
                        </div>
                        <div class="flex-1 min-w-[100px] bg-emerald-50 p-3 rounded-2xl border border-emerald-100 text-center">
                            <h4 class="text-2xl font-black text-emerald-600">{{ $stats['finished'] }}</h4>
                            <p class="text-[10px] uppercase font-bold text-emerald-400 mt-1">Selesai</p>
                        </div>
                        <div class="flex-1 min-w-[100px] bg-slate-50 p-3 rounded-2xl border border-slate-200 text-center">
                            <h4 class="text-2xl font-black text-slate-600">{{ $stats['not_started'] }}</h4>
                            <p class="text-[10px] uppercase font-bold text-slate-400 mt-1">Belum</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TABEL PESERTA -->
            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden" x-data="{ search: '' }">
                <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row justify-between items-center gap-4">
                    <h4 class="font-bold text-slate-700 flex items-center gap-2">
                        <i class="ph-fill ph-users-three text-blue-500"></i> Daftar Peserta ({{ $stats['total_students'] }})
                    </h4>
                    <div class="relative w-full md:w-64">
                        <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" x-model="search" placeholder="Cari nama siswa..." class="w-full pl-9 pr-4 py-2 text-sm border-slate-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm">
                    </div>
                </div>
                
                <!-- DESKTOP TABLE -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-white text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4 w-12 text-center">No</th>
                                <th class="px-6 py-4">Nama Siswa</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-center">Mulai</th>
                                <th class="px-6 py-4 text-center">Skor Sementara</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($monitoringData as $index => $student)
                            <tr x-show="search === '' || '{{ strtolower($student->name) }}'.includes(search.toLowerCase())" 
                                class="hover:bg-blue-50/30 transition group {{ $student->is_active ? 'bg-blue-50/10' : '' }}">
                                <td class="px-6 py-4 text-center font-bold text-slate-300">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-bold text-slate-800">{{ $student->name }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if($student->status == 'Sedang Mengerjakan')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold bg-blue-100 text-blue-700 border border-blue-200">
                                            <span class="block w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span> Mengerjakan
                                        </span>
                                    @elseif($student->status == 'Selesai')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                            <i class="ph-bold ph-check mr-1"></i> Selesai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-slate-100 text-slate-400 border border-slate-200">
                                            Belum Mulai
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center font-mono text-xs">{{ $student->start_time ?? '-' }}</td>
                                <td class="px-6 py-4 text-center font-black text-slate-800 text-base">
                                    {{ $student->score > 0 ? $student->score : '-' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($student->is_active)
                                        <form action="{{ route('cbt.reset', ['exam' => $exam->id, 'student' => $student->id]) }}" method="POST" onsubmit="return confirm('Peringatan: Reset akan memaksa siswa logout. Lanjutkan?')">
                                            @csrf
                                            <button class="text-xs font-bold text-rose-500 hover:text-white hover:bg-rose-500 border border-rose-200 bg-rose-50 px-3 py-1.5 rounded-lg transition" title="Reset Login">
                                                <i class="ph-bold ph-power"></i> Reset
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-400">
                                        <i class="ph-duotone ph-users text-4xl mb-2 text-slate-300"></i>
                                        <p class="font-medium">Belum ada siswa yang login.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- MOBILE LIST (Tetap ada untuk HP) -->
                <div class="md:hidden p-4 space-y-3 bg-slate-50">
                    @forelse($monitoringData as $index => $student)
                    <div x-show="search === '' || '{{ strtolower($student->name) }}'.includes(search.toLowerCase())" 
                         class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden">
                        
                        <div class="absolute left-0 top-0 bottom-0 w-1 {{ $student->status == 'Sedang Mengerjakan' ? 'bg-blue-500' : ($student->status == 'Selesai' ? 'bg-emerald-500' : 'bg-slate-300') }}"></div>
                        
                        <div class="pl-3 flex justify-between items-start">
                            <div>
                                <h5 class="font-bold text-slate-800 text-sm mb-1">{{ $student->name }}</h5>
                                <div class="flex items-center gap-2">
                                    @if($student->status == 'Sedang Mengerjakan')
                                        <span class="text-[10px] font-bold bg-blue-100 text-blue-700 px-2 py-0.5 rounded border border-blue-200">Sedang Ujian</span>
                                    @elseif($student->status == 'Selesai')
                                        <span class="text-[10px] font-bold bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded border border-emerald-200">Selesai</span>
                                    @else
                                        <span class="text-[10px] font-bold text-slate-400">Belum Login</span>
                                    @endif
                                </div>
                            </div>

                            <div class="text-right">
                                <span class="block text-[10px] text-slate-400 font-bold uppercase mb-0.5">Skor</span>
                                <span class="block text-xl font-black text-slate-800 leading-none">{{ $student->score }}</span>
                            </div>
                        </div>
                        
                        @if($student->is_active)
                        <div class="mt-3 pt-3 border-t border-slate-50 text-right">
                             <form action="{{ route('cbt.reset', ['exam' => $exam->id, 'student' => $student->id]) }}" method="POST" onsubmit="return confirm('Reset login siswa ini?')">
                                @csrf
                                <button class="text-xs font-bold text-rose-600 flex items-center gap-1 ml-auto hover:underline">
                                    <i class="ph-bold ph-power"></i> Reset Login
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                    @empty
                        <div class="text-center py-8 text-slate-400 italic text-sm">Belum ada peserta.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>