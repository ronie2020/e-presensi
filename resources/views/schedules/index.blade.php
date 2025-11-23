{{-- Halaman ini adalah tampilan untuk resources/views/schedules/index.blade.php --}}
<x-app-layout>
    <div class="py-6 sm:py-8">
        
        {{-- Header Page --}}
        <div class="mb-8">
            <h1 class="text-3xl font-black text-gray-800 tracking-tight leading-tight">
                Manajemen Jadwal
            </h1>
            <p class="text-gray-500 mt-1">
                Atur jam masuk, jam pulang, dan kalender akademik (hari libur/khusus).
            </p>
        </div>

        {{-- Pesan Flash --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-emerald-100 rounded-full text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-400 hover:text-emerald-600">&times;</button>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl shadow-sm">
                <ul class="list-disc list-inside text-sm font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- BAGIAN 1: JADWAL REGULER -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-10">
            <div class="p-6 md:p-8 border-b border-gray-100 flex items-center gap-3 bg-gray-50/50">
                <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h3 class="text-lg font-black text-gray-800">Jadwal Sekolah Reguler</h3>
                    <p class="text-xs text-gray-500">Atur jam operasional standar mingguan.</p>
                </div>
            </div>

            <form action="{{ route('schedules.regular.store') }}" method="POST" class="p-6 md:p-8">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    
                    <!-- Jadwal Hari Biasa (Senin-Kamis) -->
                    <div class="bg-blue-50/50 rounded-2xl p-6 border border-blue-100 relative group hover:bg-blue-50 transition-colors">
                        <div class="flex items-center justify-between mb-6">
                            <h4 class="font-bold text-blue-800 flex items-center gap-2">
                                <span class="w-2 h-6 bg-blue-500 rounded-full"></span>
                                Senin - Kamis
                            </h4>
                            <span class="text-xs font-bold bg-blue-100 text-blue-600 px-2 py-1 rounded">Hari Biasa</span>
                        </div>
                        <input type="hidden" name="day_type[]" value="Biasa">
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3"> {{-- Changed gap to 3 --}}
                            {{-- Jam Masuk --}}
                            <div class="bg-white p-2.5 rounded-xl border border-blue-100 shadow-sm"> {{-- Reduced padding --}}
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1.5 text-center tracking-wider">Jam Masuk</label>
                                <div class="flex items-center gap-1.5"> {{-- Reduced gap --}}
                                    <input type="time" name="start_in[]" 
                                        value="{{ isset($regularSchedules['Biasa']->start_in) ? \Carbon\Carbon::parse($regularSchedules['Biasa']->start_in)->format('H:i') : '05:30' }}" 
                                        class="flex-1 min-w-0 text-center font-bold text-sm text-gray-700 bg-gray-50 border-0 rounded-lg focus:ring-2 focus:ring-blue-500 px-1 py-2">
                                    <span class="text-gray-300 font-bold">-</span>
                                    <input type="time" name="end_in[]" 
                                        value="{{ isset($regularSchedules['Biasa']->end_in) ? \Carbon\Carbon::parse($regularSchedules['Biasa']->end_in)->format('H:i') : '07:00' }}" 
                                        class="flex-1 min-w-0 text-center font-bold text-sm text-gray-700 bg-gray-50 border-0 rounded-lg focus:ring-2 focus:ring-blue-500 px-1 py-2">
                                </div>
                            </div>

                            {{-- Jam Pulang --}}
                            <div class="bg-white p-2.5 rounded-xl border border-blue-100 shadow-sm">
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1.5 text-center tracking-wider">Jam Pulang</label>
                                <div class="flex items-center gap-1.5">
                                    <input type="time" name="start_out[]" 
                                        value="{{ isset($regularSchedules['Biasa']->start_out) ? \Carbon\Carbon::parse($regularSchedules['Biasa']->start_out)->format('H:i') : '14:20' }}" 
                                        class="flex-1 min-w-0 text-center font-bold text-sm text-gray-700 bg-gray-50 border-0 rounded-lg focus:ring-2 focus:ring-blue-500 px-1 py-2">
                                    <span class="text-gray-300 font-bold">-</span>
                                    <input type="time" name="end_out[]" 
                                        value="{{ isset($regularSchedules['Biasa']->end_out) ? \Carbon\Carbon::parse($regularSchedules['Biasa']->end_out)->format('H:i') : '17:00' }}" 
                                        class="flex-1 min-w-0 text-center font-bold text-sm text-gray-700 bg-gray-50 border-0 rounded-lg focus:ring-2 focus:ring-blue-500 px-1 py-2">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Jadwal Hari Jum'at -->
                    <div class="bg-purple-50/50 rounded-2xl p-6 border border-purple-100 relative group hover:bg-purple-50 transition-colors">
                        <div class="flex items-center justify-between mb-6">
                            <h4 class="font-bold text-purple-800 flex items-center gap-2">
                                <span class="w-2 h-6 bg-purple-500 rounded-full"></span>
                                Hari Jum'at
                            </h4>
                            <span class="text-xs font-bold bg-purple-100 text-purple-600 px-2 py-1 rounded">Khusus</span>
                        </div>
                        <input type="hidden" name="day_type[]" value="Jumat">
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            {{-- Jam Masuk --}}
                            <div class="bg-white p-2.5 rounded-xl border border-purple-100 shadow-sm">
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1.5 text-center tracking-wider">Jam Masuk</label>
                                <div class="flex items-center gap-1.5">
                                    <input type="time" name="start_in[]" 
                                        value="{{ isset($regularSchedules['Jumat']->start_in) ? \Carbon\Carbon::parse($regularSchedules['Jumat']->start_in)->format('H:i') : '05:30' }}" 
                                        class="flex-1 min-w-0 text-center font-bold text-sm text-gray-700 bg-gray-50 border-0 rounded-lg focus:ring-2 focus:ring-purple-500 px-1 py-2">
                                    <span class="text-gray-300 font-bold">-</span>
                                    <input type="time" name="end_in[]" 
                                        value="{{ isset($regularSchedules['Jumat']->end_in) ? \Carbon\Carbon::parse($regularSchedules['Jumat']->end_in)->format('H:i') : '07:00' }}" 
                                        class="flex-1 min-w-0 text-center font-bold text-sm text-gray-700 bg-gray-50 border-0 rounded-lg focus:ring-2 focus:ring-purple-500 px-1 py-2">
                                </div>
                            </div>

                            {{-- Jam Pulang --}}
                            <div class="bg-white p-2.5 rounded-xl border border-purple-100 shadow-sm">
                                <label class="block text-[10px] font-bold text-gray-400 uppercase mb-1.5 text-center tracking-wider">Jam Pulang</label>
                                <div class="flex items-center gap-1.5">
                                    <input type="time" name="start_out[]" 
                                        value="{{ isset($regularSchedules['Jumat']->start_out) ? \Carbon\Carbon::parse($regularSchedules['Jumat']->start_out)->format('H:i') : '11:00' }}" 
                                        class="flex-1 min-w-0 text-center font-bold text-sm text-gray-700 bg-gray-50 border-0 rounded-lg focus:ring-2 focus:ring-purple-500 px-1 py-2">
                                    <span class="text-gray-300 font-bold">-</span>
                                    <input type="time" name="end_out[]" 
                                        value="{{ isset($regularSchedules['Jumat']->end_out) ? \Carbon\Carbon::parse($regularSchedules['Jumat']->end_out)->format('H:i') : '15:00' }}" 
                                        class="flex-1 min-w-0 text-center font-bold text-sm text-gray-700 bg-gray-50 border-0 rounded-lg focus:ring-2 focus:ring-purple-500 px-1 py-2">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-end">
                    <button type="submit" class="py-3 px-6 bg-gray-900 text-white font-bold rounded-xl hover:bg-black transition-all shadow-lg shadow-gray-200 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        Simpan Perubahan Reguler
                    </button>
                </div>
            </form>
        </div>

        <!-- BAGIAN 2: JADWAL KHUSUS (GRID 2 KOLOM) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- KOLOM KIRI: FORM INPUT KHUSUS -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden h-full flex flex-col" x-data="{ isHoliday: false }">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-lg font-black text-gray-800">Tambah Jadwal Khusus</h3>
                        <p class="text-xs text-gray-500">Untuk hari libur atau acara tertentu.</p>
                    </div>
                    
                    <form action="{{ route('schedules.special.store') }}" method="POST" class="p-6 flex-1 flex flex-col">
                        @csrf
                        
                        <div class="space-y-5 flex-1">
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Tanggal</label>
                                <input type="date" name="date" required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 font-bold text-gray-700 transition-colors">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Keterangan</label>
                                <input type="text" name="description" placeholder="Contoh: Ujian Akhir Semester" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm p-3 transition-colors">
                            </div>
                            
                            <!-- Toggle Hari Libur -->
                            <div class="bg-red-50 p-4 rounded-xl border border-red-100 flex items-center gap-3 cursor-pointer" @click="isHoliday = !isHoliday">
                                <input type="checkbox" name="is_holiday" value="1" class="w-5 h-5 text-red-600 border-gray-300 rounded focus:ring-red-500 cursor-pointer" x-model="isHoliday">
                                <label class="text-sm font-bold text-red-700 cursor-pointer select-none">Tandai sebagai Hari Libur (Tidak ada KBM)</label>
                            </div>

                            <!-- Input Jam (Hanya jika bukan hari libur) -->
                            <div x-show="!isHoliday" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="space-y-4 bg-gray-50 p-4 rounded-xl border border-gray-100">
                                <p class="text-xs font-bold text-gray-400 uppercase text-center mb-2">Jam Operasional Khusus</p>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="text-[10px] text-gray-500 font-bold text-center block mb-1">Masuk Mulai</label>
                                        <input type="time" name="start_in" class="w-full text-sm text-center rounded-lg border-gray-300 px-1">
                                    </div>
                                    <div>
                                        <label class="text-[10px] text-gray-500 font-bold text-center block mb-1">Masuk Akhir</label>
                                        <input type="time" name="end_in" class="w-full text-sm text-center rounded-lg border-gray-300 px-1">
                                    </div>
                                    <div>
                                        <label class="text-[10px] text-gray-500 font-bold text-center block mb-1">Pulang Mulai</label>
                                        <input type="time" name="start_out" class="w-full text-sm text-center rounded-lg border-gray-300 px-1">
                                    </div>
                                    <div>
                                        <label class="text-[10px] text-gray-500 font-bold text-center block mb-1">Pulang Akhir</label>
                                        <input type="time" name="end_out" class="w-full text-sm text-center rounded-lg border-gray-300 px-1">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="mt-6 w-full py-3 px-6 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-200">
                            Simpan Jadwal
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- KOLOM KANAN: DAFTAR JADWAL -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden h-full flex flex-col">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                        <h3 class="text-lg font-black text-gray-800">Daftar Jadwal Khusus</h3>
                        <span class="bg-white text-xs font-bold px-2 py-1 rounded border border-gray-200 text-gray-500">{{ $specialSchedules->count() }} Agenda</span>
                    </div>
                    
                    <div class="overflow-x-auto flex-1">
                        <table class="min-w-full text-left">
                            <thead class="bg-white border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Keterangan</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-center">Status</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse ($specialSchedules as $schedule)
                                    <tr class="hover:bg-blue-50/30 transition-colors group">
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-700">
                                            {{ \Carbon\Carbon::parse($schedule->date)->translatedFormat('l, d M Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-bold text-gray-800">{{ $schedule->description }}</p>
                                            @if(!$schedule->is_holiday)
                                                <p class="text-xs text-gray-400 mt-1 font-mono">
                                                    {{ \Carbon\Carbon::parse($schedule->start_in)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_out)->format('H:i') }}
                                                </p>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($schedule->is_holiday)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                                    ⛔ Libur
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-100 text-blue-700 border border-blue-200">
                                                    🕒 Khusus
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <form action="{{ route('schedules.special.destroy', $schedule->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-gray-300 hover:text-red-500 hover:bg-red-50 p-2 rounded-lg transition-all">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-gray-400 italic">
                                            Belum ada jadwal khusus atau libur yang diatur.
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
</x-app-layout>