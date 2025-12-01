{{-- Halaman ini adalah tampilan untuk resources/views/schedules/index.blade.php --}}
<x-app-layout>
    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header Page --}}
            <div class="mb-8">
                <h1 class="text-3xl font-black text-slate-800 tracking-tight leading-tight flex items-center gap-3">
                    <i class="ph-duotone ph-calendar-check text-blue-600"></i> Manajemen Jadwal
                </h1>
                <p class="text-slate-500 mt-2 text-lg">
                    Atur jam masuk, jam pulang, dan kalender akademik (hari libur/khusus).
                </p>
            </div>

            {{-- Pesan Flash (Style disamakan dengan Subjects) --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-emerald-100 rounded-full text-emerald-600">
                            <i class="ph-bold ph-check"></i>
                        </div>
                        <span class="font-bold">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="hover:bg-emerald-100 p-1 rounded-lg transition"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            @if ($errors->any())
                <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl flex items-center gap-3 shadow-sm">
                    <div class="p-2 bg-rose-100 rounded-full text-rose-600 shrink-0">
                        <i class="ph-bold ph-warning"></i>
                    </div>
                    <div>
                        <p class="font-bold">Terdapat kesalahan input:</p>
                        <ul class="list-disc list-inside text-sm mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- BAGIAN 1: JADWAL REGULER -->
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mb-10">
                <div class="p-6 md:p-8 border-b border-slate-50 flex items-center gap-3 bg-slate-50/50">
                    <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl">
                        <i class="ph-duotone ph-clock"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-black text-slate-800">Jadwal Sekolah Reguler</h3>
                        <p class="text-xs text-slate-500 font-medium">Atur jam operasional standar mingguan (Gunakan format 24 Jam, misal 14:00).</p>
                    </div>
                </div>

                <form action="{{ route('schedules.regular.store') }}" method="POST" class="p-6 md:p-8">
                    @csrf
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        
                        <!-- Jadwal Hari Biasa (Senin-Kamis) -->
                        <div class="bg-blue-50/50 rounded-3xl p-6 border border-blue-100 relative group hover:bg-blue-50 transition-colors">
                            <div class="flex items-center justify-between mb-6">
                                <h4 class="font-bold text-blue-800 flex items-center gap-2">
                                    <span class="w-2 h-6 bg-blue-500 rounded-full"></span>
                                    Senin - Kamis
                                </h4>
                                <span class="text-[10px] font-bold bg-blue-100 text-blue-600 px-2 py-1 rounded uppercase tracking-wide border border-blue-200">Hari Biasa</span>
                            </div>
                            <input type="hidden" name="day_type[]" value="Biasa">
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {{-- Jam Masuk --}}
                                <div class="bg-white p-4 rounded-2xl border border-blue-100 shadow-sm group-hover:border-blue-200 transition-colors">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 text-center tracking-wider flex items-center justify-center gap-1">
                                        <i class="ph-bold ph-sun-horizon"></i> Jam Masuk
                                    </label>
                                    <div class="flex items-center gap-2">
                                        <input type="time" name="start_in[]" 
                                            value="{{ isset($regularSchedules['Biasa']->start_in) ? \Carbon\Carbon::parse($regularSchedules['Biasa']->start_in)->format('H:i') : '05:30' }}" 
                                            class="flex-1 min-w-0 text-center font-bold text-sm text-slate-700 bg-slate-50 border-0 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white px-1 py-2.5 transition-all">
                                        <span class="text-slate-300 font-bold text-xs">-</span>
                                        <input type="time" name="end_in[]" 
                                            value="{{ isset($regularSchedules['Biasa']->end_in) ? \Carbon\Carbon::parse($regularSchedules['Biasa']->end_in)->format('H:i') : '07:00' }}" 
                                            class="flex-1 min-w-0 text-center font-bold text-sm text-slate-700 bg-slate-50 border-0 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white px-1 py-2.5 transition-all">
                                    </div>
                                </div>

                                {{-- Jam Pulang --}}
                                <div class="bg-white p-4 rounded-2xl border border-blue-100 shadow-sm group-hover:border-blue-200 transition-colors">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 text-center tracking-wider flex items-center justify-center gap-1">
                                        <i class="ph-bold ph-moon-stars"></i> Jam Pulang
                                    </label>
                                    <div class="flex items-center gap-2">
                                        <input type="time" name="start_out[]" 
                                            value="{{ isset($regularSchedules['Biasa']->start_out) ? \Carbon\Carbon::parse($regularSchedules['Biasa']->start_out)->format('H:i') : '14:20' }}" 
                                            class="flex-1 min-w-0 text-center font-bold text-sm text-slate-700 bg-slate-50 border-0 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white px-1 py-2.5 transition-all">
                                        <span class="text-slate-300 font-bold text-xs">-</span>
                                        <input type="time" name="end_out[]" 
                                            value="{{ isset($regularSchedules['Biasa']->end_out) ? \Carbon\Carbon::parse($regularSchedules['Biasa']->end_out)->format('H:i') : '17:00' }}" 
                                            class="flex-1 min-w-0 text-center font-bold text-sm text-slate-700 bg-slate-50 border-0 rounded-xl focus:ring-2 focus:ring-blue-500 focus:bg-white px-1 py-2.5 transition-all">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Jadwal Hari Jum'at -->
                        <div class="bg-purple-50/50 rounded-3xl p-6 border border-purple-100 relative group hover:bg-purple-50 transition-colors">
                            <div class="flex items-center justify-between mb-6">
                                <h4 class="font-bold text-purple-800 flex items-center gap-2">
                                    <span class="w-2 h-6 bg-purple-500 rounded-full"></span>
                                    Hari Jum'at
                                </h4>
                                <span class="text-[10px] font-bold bg-purple-100 text-purple-600 px-2 py-1 rounded uppercase tracking-wide border border-purple-200">Khusus</span>
                            </div>
                            <input type="hidden" name="day_type[]" value="Jumat">
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {{-- Jam Masuk --}}
                                <div class="bg-white p-4 rounded-2xl border border-purple-100 shadow-sm group-hover:border-purple-200 transition-colors">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 text-center tracking-wider flex items-center justify-center gap-1">
                                        <i class="ph-bold ph-sun-horizon"></i> Jam Masuk
                                    </label>
                                    <div class="flex items-center gap-2">
                                        <input type="time" name="start_in[]" 
                                            value="{{ isset($regularSchedules['Jumat']->start_in) ? \Carbon\Carbon::parse($regularSchedules['Jumat']->start_in)->format('H:i') : '05:30' }}" 
                                            class="flex-1 min-w-0 text-center font-bold text-sm text-slate-700 bg-slate-50 border-0 rounded-xl focus:ring-2 focus:ring-purple-500 focus:bg-white px-1 py-2.5 transition-all">
                                        <span class="text-slate-300 font-bold text-xs">-</span>
                                        <input type="time" name="end_in[]" 
                                            value="{{ isset($regularSchedules['Jumat']->end_in) ? \Carbon\Carbon::parse($regularSchedules['Jumat']->end_in)->format('H:i') : '07:00' }}" 
                                            class="flex-1 min-w-0 text-center font-bold text-sm text-slate-700 bg-slate-50 border-0 rounded-xl focus:ring-2 focus:ring-purple-500 focus:bg-white px-1 py-2.5 transition-all">
                                    </div>
                                </div>

                                {{-- Jam Pulang --}}
                                <div class="bg-white p-4 rounded-2xl border border-purple-100 shadow-sm group-hover:border-purple-200 transition-colors">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2 text-center tracking-wider flex items-center justify-center gap-1">
                                        <i class="ph-bold ph-moon-stars"></i> Jam Pulang
                                    </label>
                                    <div class="flex items-center gap-2">
                                        <input type="time" name="start_out[]" 
                                            value="{{ isset($regularSchedules['Jumat']->start_out) ? \Carbon\Carbon::parse($regularSchedules['Jumat']->start_out)->format('H:i') : '11:00' }}" 
                                            class="flex-1 min-w-0 text-center font-bold text-sm text-slate-700 bg-slate-50 border-0 rounded-xl focus:ring-2 focus:ring-purple-500 focus:bg-white px-1 py-2.5 transition-all">
                                        <span class="text-slate-300 font-bold text-xs">-</span>
                                        <input type="time" name="end_out[]" 
                                            value="{{ isset($regularSchedules['Jumat']->end_out) ? \Carbon\Carbon::parse($regularSchedules['Jumat']->end_out)->format('H:i') : '15:00' }}" 
                                            class="flex-1 min-w-0 text-center font-bold text-sm text-slate-700 bg-slate-50 border-0 rounded-xl focus:ring-2 focus:ring-purple-500 focus:bg-white px-1 py-2.5 transition-all">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="w-full sm:w-auto py-3 px-6 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/30 flex items-center justify-center gap-2 transform active:scale-[0.98]">
                            <i class="ph-bold ph-floppy-disk"></i>
                            Simpan Perubahan Reguler
                        </button>
                    </div>
                </form>
            </div>

            <!-- BAGIAN 2: JADWAL KHUSUS (GRID 2 KOLOM) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- KOLOM KIRI: FORM INPUT KHUSUS -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden h-full flex flex-col sticky top-24" x-data="{ isHoliday: false }">
                        <div class="p-6 border-b border-slate-50 bg-slate-50/50 flex items-center gap-3">
                            <div class="w-8 h-8 bg-orange-50 text-orange-600 rounded-lg flex items-center justify-center">
                                <i class="ph-duotone ph-calendar-plus"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-800">Jadwal Khusus</h3>
                                <p class="text-xs text-slate-500 font-medium">Hari libur atau acara tertentu.</p>
                            </div>
                        </div>
                        
                        <form action="{{ route('schedules.special.store') }}" method="POST" class="p-6 flex-1 flex flex-col">
                            @csrf
                            
                            <div class="space-y-5 flex-1">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Tanggal</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                            <i class="ph-bold ph-calendar-blank"></i>
                                        </div>
                                        <input type="date" name="date" required 
                                               class="w-full pl-10 pr-4 py-3 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 font-bold text-slate-700 transition-colors">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Keterangan</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                            <i class="ph-bold ph-text-t"></i>
                                        </div>
                                        <input type="text" name="description" placeholder="Contoh: Ujian Akhir Semester" 
                                               class="w-full pl-10 pr-4 py-3 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 font-medium text-sm text-slate-700 transition-colors">
                                    </div>
                                </div>
                                
                                <!-- Toggle Hari Libur -->
                                <div class="bg-rose-50 p-4 rounded-2xl border border-rose-100 flex items-center gap-3 cursor-pointer hover:bg-rose-100/50 transition-colors" @click="isHoliday = !isHoliday">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" name="is_holiday" value="1" class="peer sr-only" x-model="isHoliday">
                                        <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-500"></div>
                                    </div>
                                    <label class="text-sm font-bold text-rose-700 cursor-pointer select-none flex-1">
                                        Set Hari Libur
                                        <span class="block text-[10px] text-rose-500 font-normal">Tidak ada KBM</span>
                                    </label>
                                    <i class="ph-bold ph-coffee text-rose-400 text-xl"></i>
                                </div>

                                <!-- Input Jam (Hanya jika bukan hari libur) -->
                                <div x-show="!isHoliday" 
                                     x-transition:enter="transition ease-out duration-300" 
                                     x-transition:enter-start="opacity-0 -translate-y-2" 
                                     x-transition:enter-end="opacity-100 translate-y-0" 
                                     class="space-y-4 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                    <p class="text-xs font-bold text-slate-400 uppercase text-center mb-2 border-b border-slate-200 pb-2">Jam Operasional Khusus</p>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="text-[10px] text-slate-500 font-bold text-center block mb-1">Masuk Mulai</label>
                                            <input type="time" name="start_in" class="w-full text-xs text-center font-bold rounded-lg border-slate-200 bg-white px-1 py-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        <div>
                                            <label class="text-[10px] text-slate-500 font-bold text-center block mb-1">Masuk Akhir</label>
                                            <input type="time" name="end_in" class="w-full text-xs text-center font-bold rounded-lg border-slate-200 bg-white px-1 py-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        <div>
                                            <label class="text-[10px] text-slate-500 font-bold text-center block mb-1">Pulang Mulai</label>
                                            <input type="time" name="start_out" class="w-full text-xs text-center font-bold rounded-lg border-slate-200 bg-white px-1 py-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                        <div>
                                            <label class="text-[10px] text-slate-500 font-bold text-center block mb-1">Pulang Akhir</label>
                                            <input type="time" name="end_out" class="w-full text-xs text-center font-bold rounded-lg border-slate-200 bg-white px-1 py-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="submit" class="mt-6 w-full py-3 px-6 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/30 flex items-center justify-center gap-2 transform active:scale-[0.98]">
                                <i class="ph-bold ph-plus"></i>
                                Tambah Jadwal
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- KOLOM KANAN: DAFTAR JADWAL -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden h-full flex flex-col">
                        <div class="p-6 border-b border-slate-50 flex items-center justify-between">
                            <h3 class="text-lg font-bold text-slate-800">Daftar Jadwal Khusus</h3>
                            <span class="bg-slate-100 text-xs font-bold px-3 py-1 rounded-full border border-slate-200 text-slate-500">
                                {{ $specialSchedules->count() }} Agenda
                            </span>
                        </div>
                        
                        <div class="overflow-x-auto flex-1">
                            <table class="w-full text-left text-sm text-slate-600">
                                <thead class="bg-slate-50 text-xs font-bold text-slate-400 uppercase">
                                    <tr>
                                        <th class="px-6 py-4">Tanggal</th>
                                        <th class="px-6 py-4">Keterangan</th>
                                        <th class="px-6 py-4 text-center">Status</th>
                                        <th class="px-6 py-4 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @forelse ($specialSchedules as $schedule)
                                        <tr class="hover:bg-slate-50/50 transition-colors group">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center text-lg">
                                                        <i class="ph-duotone ph-calendar-blank"></i>
                                                    </div>
                                                    <span class="font-bold text-slate-700">
                                                        {{ \Carbon\Carbon::parse($schedule->date)->translatedFormat('d M Y') }}
                                                    </span>
                                                </div>
                                                <div class="text-xs text-slate-400 ml-11">{{ \Carbon\Carbon::parse($schedule->date)->translatedFormat('l') }}</div>
                                            </td>
                                            <td class="px-6 py-4">
                                                <p class="text-sm font-bold text-slate-800">{{ $schedule->description }}</p>
                                                @if(!$schedule->is_holiday)
                                                    <p class="text-xs text-slate-400 mt-1 font-mono flex items-center gap-1">
                                                        <i class="ph-bold ph-clock"></i>
                                                        {{ \Carbon\Carbon::parse($schedule->start_in)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_out)->format('H:i') }}
                                                    </p>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-center">
                                                @if($schedule->is_holiday)
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-rose-50 text-rose-700 border border-rose-100">
                                                        <i class="ph-bold ph-coffee"></i> Libur
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-700 border border-blue-100">
                                                        <i class="ph-bold ph-info"></i> Khusus
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <form action="{{ route('schedules.special.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 text-slate-300 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus Jadwal">
                                                        <i class="ph-bold ph-trash text-lg"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-12 text-center text-slate-400 italic">
                                                <div class="flex flex-col items-center justify-center gap-2">
                                                    <i class="ph-duotone ph-calendar-slash text-3xl text-slate-300"></i>
                                                    <span>Belum ada jadwal khusus atau libur yang diatur.</span>
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
        </div>
    </div>
</x-app-layout>