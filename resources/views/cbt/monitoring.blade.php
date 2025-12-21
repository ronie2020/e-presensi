<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Monitoring Live') }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-10 font-sans text-slate-800" 
         x-data="{ 
            search: '', 
            count: 30,
            isPaused: false,
            init() {
                setInterval(() => {
                    if (this.search === '') {
                        if (this.count > 0) this.count--; else location.reload();
                        this.isPaused = false;
                    } else {
                        this.isPaused = true;
                        this.count = 30;
                    }
                }, 1000);
            }
         }">
         
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HEADER INFO (DARK BLUE HERO) --}}
            <div class="relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-slate-900 via-blue-900 to-blue-800 p-8 mb-8 text-white shadow-xl shadow-blue-900/30 overflow-hidden border border-white/10">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            @if($exam->is_active)
                                <span class="bg-emerald-500/20 border border-emerald-500/50 text-emerald-300 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider animate-pulse flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span> Live Active
                                </span>
                            @else
                                <span class="bg-white/10 border border-white/10 text-slate-300 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider">
                                    Non-Aktif
                                </span>
                            @endif
                            <span class="text-blue-300 text-xs font-bold uppercase tracking-wider">Kelas {{ $exam->class_level }}</span>
                        </div>
                        <h1 class="text-3xl font-black tracking-tight leading-none mb-2">{{ $exam->title }}</h1>
                        <p class="text-blue-200 text-sm font-medium flex items-center gap-2">
                            Token Akses: <span class="font-mono bg-white/10 px-2 py-0.5 rounded text-white font-bold">{{ $exam->token }}</span>
                        </p>
                    </div>

                    {{-- STATISTIK RINGKAS --}}
                    <div class="flex gap-3 w-full md:w-auto">
                        <div class="bg-white/10 backdrop-blur-md px-5 py-3 rounded-2xl border border-white/10 text-center min-w-[90px]">
                            <h4 class="text-2xl font-black text-white leading-none">{{ $stats['working'] }}</h4>
                            <p class="text-[9px] uppercase font-bold text-blue-300 mt-1">Proses</p>
                        </div>
                        <div class="bg-emerald-500/20 backdrop-blur-md px-5 py-3 rounded-2xl border border-emerald-500/30 text-center min-w-[90px]">
                            <h4 class="text-2xl font-black text-emerald-300 leading-none">{{ $stats['finished'] }}</h4>
                            <p class="text-[9px] uppercase font-bold text-emerald-200 mt-1">Selesai</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TOOLBAR & REFRESH --}}
            <div class="flex flex-col md:flex-row justify-between items-center gap-4 mb-6">
                <a href="{{ route('cbt.index') }}" class="group inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-blue-600 transition">
                    <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali
                </a>

                <div class="text-[10px] font-bold px-3 py-1.5 rounded-full border shadow-sm flex items-center gap-2 transition-colors duration-300"
                     :class="isPaused ? 'bg-amber-50 text-amber-600 border-amber-200' : 'bg-white text-slate-400 border-slate-200'">
                    <template x-if="!isPaused">
                        <div class="flex items-center gap-1.5">
                            <i class="ph-bold ph-arrows-clockwise animate-spin text-blue-500"></i>
                            <span>Refresh: <span x-text="count" class="font-mono text-slate-700"></span>s</span>
                        </div>
                    </template>
                    <template x-if="isPaused">
                        <div class="flex items-center gap-1.5">
                            <i class="ph-fill ph-pause-circle text-amber-500"></i>
                            <span>Paused (Sedang mencari...)</span>
                        </div>
                    </template>
                </div>
            </div>

            <!-- TABEL PESERTA -->
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden min-h-[500px]">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row justify-between items-center gap-4">
                    <h4 class="font-bold text-slate-700 flex items-center gap-2 text-lg">
                        <i class="ph-fill ph-users-three text-blue-500"></i> Peserta Ujian <span class="bg-slate-200 text-slate-600 text-xs px-2 py-0.5 rounded-full">{{ $stats['total_students'] }}</span>
                    </h4>
                    <div class="relative w-full md:w-72">
                        <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" x-model="search" placeholder="Cari siswa..." class="w-full pl-10 pr-4 py-2.5 text-sm font-bold border-slate-200 rounded-xl focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm">
                    </div>
                </div>
                
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4 w-16 text-center">No</th>
                                <th class="px-6 py-4">Nama Siswa</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-center">Mulai</th>
                                <th class="px-6 py-4 text-center">Skor</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($monitoringData as $index => $student)
                            <tr x-show="search === '' || '{{ strtolower($student->name) }}'.includes(search.toLowerCase())" 
                                class="hover:bg-blue-50/20 transition group {{ $student->is_active ? 'bg-blue-50/10' : '' }}">
                                <td class="px-6 py-4 text-center font-bold text-slate-400">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-bold text-slate-800">{{ $student->name }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if($student->status == 'Sedang Mengerjakan')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[10px] font-black bg-blue-100 text-blue-700 border border-blue-200 uppercase tracking-wide">
                                            <span class="block w-1.5 h-1.5 rounded-full bg-blue-600 animate-pulse"></span> Proses
                                        </span>
                                    @elseif($student->status == 'Selesai')
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[10px] font-black bg-emerald-100 text-emerald-700 border border-emerald-200 uppercase tracking-wide">
                                            <i class="ph-bold ph-check"></i> Selesai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-black bg-slate-100 text-slate-400 border border-slate-200 uppercase tracking-wide">
                                            Belum
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center font-mono text-xs font-medium">{{ $student->start_time ?? '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-black text-slate-800 text-lg {{ $student->score > 0 ? 'text-blue-600' : 'text-slate-300' }}">
                                        {{ $student->score > 0 ? $student->score : '0' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($student->is_active)
                                        <form action="{{ route('cbt.reset', ['exam' => $exam->id, 'student' => $student->id]) }}" method="POST" onsubmit="return confirm('Peringatan: Reset akan memaksa siswa logout. Lanjutkan?')">
                                            @csrf
                                            <button class="text-xs font-bold text-rose-500 hover:text-white hover:bg-rose-500 border border-rose-200 bg-rose-50 px-3 py-1.5 rounded-lg transition flex items-center gap-1 ml-auto" title="Reset Login">
                                                <i class="ph-bold ph-power"></i> Reset
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="px-6 py-12 text-center text-slate-400">Belum ada siswa yang login.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- MOBILE LIST -->
                <div class="md:hidden p-4 space-y-3 bg-slate-50/50">
                    @forelse($monitoringData as $index => $student)
                    <div x-show="search === '' || '{{ strtolower($student->name) }}'.includes(search.toLowerCase())" 
                         class="bg-white p-4 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden">
                        
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $student->status == 'Sedang Mengerjakan' ? 'bg-blue-500' : ($student->status == 'Selesai' ? 'bg-emerald-500' : 'bg-slate-200') }}"></div>
                        
                        <div class="pl-3 flex justify-between items-start">
                            <div>
                                <h5 class="font-bold text-slate-800 text-sm mb-1">{{ $student->name }}</h5>
                                <div class="flex items-center gap-2">
                                    @if($student->status == 'Sedang Mengerjakan')
                                        <span class="text-[10px] font-bold bg-blue-50 text-blue-700 px-2 py-0.5 rounded border border-blue-100">Sedang Ujian</span>
                                    @elseif($student->status == 'Selesai')
                                        <span class="text-[10px] font-bold bg-emerald-50 text-emerald-700 px-2 py-0.5 rounded border border-emerald-100">Selesai</span>
                                    @else
                                        <span class="text-[10px] font-bold text-slate-400">Belum Login</span>
                                    @endif
                                </div>
                            </div>

                            <div class="text-right">
                                <span class="block text-[10px] text-slate-400 font-bold uppercase mb-0.5">Skor</span>
                                <span class="block text-xl font-black text-slate-800 leading-none">{{ $student->score > 0 ? $student->score : '0' }}</span>
                            </div>
                        </div>
                        
                        @if($student->is_active)
                        <div class="mt-3 pt-3 border-t border-slate-50 text-right">
                             <form action="{{ route('cbt.reset', ['exam' => $exam->id, 'student' => $student->id]) }}" method="POST" onsubmit="return confirm('Reset login siswa ini?')">
                                @csrf
                                <button class="text-xs font-bold text-rose-600 flex items-center gap-1 ml-auto">
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