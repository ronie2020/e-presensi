<x-app-layout>
    {{-- X-DATA: Mengatur Tab Aktif (Default: 'mapel' untuk input jadwal pelajaran) --}}
    <div class="py-6 sm:py-8" x-data="{ activeTab: 'mapel' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER HALAMAN --}}
            <div class="mb-8 flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-black text-slate-800 tracking-tight leading-tight flex items-center gap-3">
                        <i class="ph-duotone ph-calendar-plus text-blue-600"></i> Atur Jadwal
                    </h1>
                    <p class="text-slate-500 mt-2 text-lg">
                        Kelola jadwal pelajaran guru dan jam kerja sekolah.
                    </p>
                </div>
                
                {{-- TAB SWITCHER (TOMBOL GANTI TAB) --}}
                <div class="bg-white p-1 rounded-xl border border-slate-200 shadow-sm flex overflow-x-auto">
                    <button @click="activeTab = 'mapel'" 
                        :class="activeTab === 'mapel' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'"
                        class="px-5 py-2.5 rounded-lg text-sm font-bold transition-all flex items-center gap-2 whitespace-nowrap">
                        <i class="ph-bold ph-book-open"></i> Jadwal Pelajaran
                    </button>
                    <button @click="activeTab = 'jam'" 
                        :class="activeTab === 'jam' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'"
                        class="px-5 py-2.5 rounded-lg text-sm font-bold transition-all flex items-center gap-2 whitespace-nowrap">
                        <i class="ph-bold ph-clock"></i> Jam Sekolah (Bel)
                    </button>
                </div>
            </div>

            {{-- ALERT MESSAGES --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center gap-3 shadow-sm">
                    <i class="ph-fill ph-check-circle text-xl"></i>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl flex items-center gap-3 shadow-sm">
                    <i class="ph-fill ph-warning-circle text-xl"></i>
                    <span class="font-bold">{{ session('error') }}</span>
                </div>
            @endif
            @if($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl shadow-sm">
                    <ul class="list-disc list-inside text-sm font-bold">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- ========================================================= --}}
            {{-- TAB 1: INPUT JADWAL PELAJARAN (MAPEL - GURU - KELAS)      --}}
            {{-- ========================================================= --}}
            <div x-show="activeTab === 'mapel'" x-transition:enter="transition ease-out duration-300">
                
                {{-- FORM INPUT --}}
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 mb-8 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-blue-500"></div>
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl">
                            <i class="ph-duotone ph-plus-circle"></i>
                        </div>
                        <h2 class="text-lg font-bold text-slate-800">Tambah Jadwal Pelajaran</h2>
                    </div>

                    <form action="{{ route('schedules.store') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                            {{-- Pilih Kelas --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">Kelas</label>
                                <select name="school_class_id" required class="w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-bold text-slate-700">
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($classes as $c)
                                        <option value="{{ $c->id }}" {{ old('school_class_id') == $c->id ? 'selected' : '' }}>
                                            {{ $c->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Pilih Mapel --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">Mata Pelajaran</label>
                                <select name="subject_id" required class="w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-bold text-slate-700">
                                    <option value="">-- Pilih Mapel --</option>
                                    @foreach($subjects as $s)
                                        <option value="{{ $s->id }}" {{ old('subject_id') == $s->id ? 'selected' : '' }}>
                                            {{ $s->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Pilih Guru --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">Guru Pengampu</label>
                                <select name="teacher_id" required class="w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-bold text-slate-700">
                                    <option value="">-- Pilih Guru --</option>
                                    @foreach($teachers as $t)
                                        <option value="{{ $t->id }}" {{ old('teacher_id') == $t->id ? 'selected' : '' }}>
                                            {{ $t->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Pilih Hari --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">Hari</label>
                                <select name="day" required class="w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-bold text-slate-700">
                                    @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $day)
                                        <option value="{{ $day }}" {{ old('day') == $day ? 'selected' : '' }}>{{ $day }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Jam Mulai --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">Jam Mulai</label>
                                <input type="time" name="start_time" required value="{{ old('start_time') }}" 
                                    class="w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-bold text-slate-700">
                            </div>

                            {{-- Jam Selesai --}}
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">Jam Selesai</label>
                                <input type="time" name="end_time" required value="{{ old('end_time') }}" 
                                    class="w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-bold text-slate-700">
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg shadow-blue-500/30 transition flex items-center gap-2">
                                <i class="ph-bold ph-floppy-disk"></i> Simpan Jadwal
                            </button>
                        </div>
                    </form>
                </div>

                {{-- TABEL DAFTAR JADWAL --}}
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-50 flex flex-col sm:flex-row justify-between items-center gap-4 bg-slate-50/50">
                        <div class="flex items-center gap-3">
                            <h2 class="text-lg font-bold text-slate-800">Daftar Jadwal Terdaftar</h2>
                            <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-xs font-bold">{{ $schedules->count() }} Data</span>
                        </div>
                        
                        {{-- Filter Kelas --}}
                        <form method="GET" class="flex items-center gap-2">
                            <select name="class_id" class="text-sm font-bold text-slate-600 rounded-xl border-slate-200 focus:ring-blue-500 py-2 pl-3 pr-8" onchange="this.form.submit()">
                                <option value="">Semua Kelas</option>
                                @foreach($classes as $c)
                                    <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>
                                        Kelas {{ $c->name }}
                                    </option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-600">
                            <thead class="bg-slate-50 text-xs font-bold text-slate-400 uppercase">
                                <tr>
                                    <th class="px-6 py-4">Hari & Jam</th>
                                    <th class="px-6 py-4">Kelas</th>
                                    <th class="px-6 py-4">Mata Pelajaran</th>
                                    <th class="px-6 py-4">Guru Pengampu</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($schedules as $item)
                                <tr class="hover:bg-slate-50/80 transition group">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-800 text-base mb-1">{{ $item->day }}</div>
                                        <div class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-slate-100 text-slate-500 text-xs font-mono font-bold">
                                            <i class="ph-bold ph-clock"></i>
                                            {{ \Carbon\Carbon::parse($item->start_time)->format('H:i') }} - 
                                            {{ \Carbon\Carbon::parse($item->end_time)->format('H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 rounded-lg bg-blue-50 text-blue-700 text-xs font-bold border border-blue-100">
                                            {{ $item->schoolClass->name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-700">{{ $item->subject->name }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-slate-600">
                                                {{ substr($item->teacher->name, 0, 1) }}
                                            </div>
                                            <span class="font-bold text-slate-600">{{ $item->teacher->name }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <form action="{{ route('schedules.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 text-slate-300 hover:text-rose-600 hover:bg-rose-50 rounded-xl transition">
                                                <i class="ph-bold ph-trash text-lg"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center">
                                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <i class="ph-duotone ph-calendar-slash text-4xl text-slate-300"></i>
                                        </div>
                                        <p class="text-slate-500 font-bold">Belum ada jadwal pelajaran yang diatur.</p>
                                        <p class="text-slate-400 text-xs mt-1">Silakan tambah jadwal melalui form di atas.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- ========================================================= --}}
            {{-- TAB 2: PENGATURAN JAM SEKOLAH (BEL)                       --}}
            {{-- ========================================================= --}}
            <div x-show="activeTab === 'jam'" x-cloak x-transition:enter="transition ease-out duration-300">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    
                    {{-- 1. JAM REGULER (SENIN-JUMAT) --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                        <div class="p-6 border-b border-slate-50 flex items-center gap-3 bg-slate-50/50">
                            <div class="w-10 h-10 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center text-xl">
                                <i class="ph-duotone ph-clock"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-800">Jam Sekolah Reguler</h3>
                                <p class="text-xs text-slate-500">Atur jam masuk & pulang standar.</p>
                            </div>
                        </div>

                        <form action="{{ route('schedules.regular.store') }}" method="POST" class="p-6">
                            @csrf
                            <div class="space-y-6">
                                <!-- Hari Biasa -->
                                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="font-bold text-slate-700">Senin - Kamis</h4>
                                        <input type="hidden" name="day_type[]" value="Biasa">
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[10px] uppercase font-bold text-slate-400 mb-1">Masuk (Awal-Akhir)</label>
                                            <div class="flex gap-2">
                                                <input type="time" name="start_in[]" value="{{ isset($regularSchedules['Biasa']->start_in) ? \Carbon\Carbon::parse($regularSchedules['Biasa']->start_in)->format('H:i') : '' }}" class="w-full rounded-lg border-slate-200 text-xs font-bold text-center">
                                                <input type="time" name="end_in[]" value="{{ isset($regularSchedules['Biasa']->end_in) ? \Carbon\Carbon::parse($regularSchedules['Biasa']->end_in)->format('H:i') : '' }}" class="w-full rounded-lg border-slate-200 text-xs font-bold text-center">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] uppercase font-bold text-slate-400 mb-1">Pulang (Awal-Akhir)</label>
                                            <div class="flex gap-2">
                                                <input type="time" name="start_out[]" value="{{ isset($regularSchedules['Biasa']->start_out) ? \Carbon\Carbon::parse($regularSchedules['Biasa']->start_out)->format('H:i') : '' }}" class="w-full rounded-lg border-slate-200 text-xs font-bold text-center">
                                                <input type="time" name="end_out[]" value="{{ isset($regularSchedules['Biasa']->end_out) ? \Carbon\Carbon::parse($regularSchedules['Biasa']->end_out)->format('H:i') : '' }}" class="w-full rounded-lg border-slate-200 text-xs font-bold text-center">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Hari Jumat -->
                                <div class="bg-purple-50 p-4 rounded-2xl border border-purple-100">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="font-bold text-purple-700">Hari Jum'at</h4>
                                        <input type="hidden" name="day_type[]" value="Jumat">
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[10px] uppercase font-bold text-purple-400 mb-1">Masuk</label>
                                            <div class="flex gap-2">
                                                <input type="time" name="start_in[]" value="{{ isset($regularSchedules['Jumat']->start_in) ? \Carbon\Carbon::parse($regularSchedules['Jumat']->start_in)->format('H:i') : '' }}" class="w-full rounded-lg border-purple-200 text-xs font-bold text-center focus:ring-purple-500">
                                                <input type="time" name="end_in[]" value="{{ isset($regularSchedules['Jumat']->end_in) ? \Carbon\Carbon::parse($regularSchedules['Jumat']->end_in)->format('H:i') : '' }}" class="w-full rounded-lg border-purple-200 text-xs font-bold text-center focus:ring-purple-500">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] uppercase font-bold text-purple-400 mb-1">Pulang</label>
                                            <div class="flex gap-2">
                                                <input type="time" name="start_out[]" value="{{ isset($regularSchedules['Jumat']->start_out) ? \Carbon\Carbon::parse($regularSchedules['Jumat']->start_out)->format('H:i') : '' }}" class="w-full rounded-lg border-purple-200 text-xs font-bold text-center focus:ring-purple-500">
                                                <input type="time" name="end_out[]" value="{{ isset($regularSchedules['Jumat']->end_out) ? \Carbon\Carbon::parse($regularSchedules['Jumat']->end_out)->format('H:i') : '' }}" class="w-full rounded-lg border-purple-200 text-xs font-bold text-center focus:ring-purple-500">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="mt-6 w-full py-3 bg-slate-800 text-white font-bold rounded-xl hover:bg-slate-700 transition">Simpan Jam Reguler</button>
                        </form>
                    </div>

                    {{-- 2. JADWAL KHUSUS / LIBUR --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden" x-data="{ isHoliday: false }">
                        <div class="p-6 border-b border-slate-50 flex items-center gap-3 bg-slate-50/50">
                            <div class="w-10 h-10 bg-orange-50 text-orange-600 rounded-xl flex items-center justify-center text-xl">
                                <i class="ph-duotone ph-calendar-blank"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-800">Jadwal Khusus / Libur</h3>
                                <p class="text-xs text-slate-500">Atur tanggal merah atau acara.</p>
                            </div>
                        </div>

                        <form action="{{ route('schedules.special.store') }}" method="POST" class="p-6">
                            @csrf
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal</label>
                                    <input type="date" name="date" required class="w-full rounded-xl border-slate-200 font-bold text-slate-700">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Keterangan</label>
                                    <input type="text" name="description" placeholder="Contoh: Rapat Guru" class="w-full rounded-xl border-slate-200 text-sm">
                                </div>
                                
                                {{-- Toggle Libur --}}
                                <div class="bg-rose-50 p-3 rounded-xl border border-rose-100 flex items-center gap-3 cursor-pointer" @click="isHoliday = !isHoliday">
                                    <input type="checkbox" name="is_holiday" value="1" class="w-5 h-5 text-rose-600 rounded focus:ring-rose-500" x-model="isHoliday">
                                    <span class="text-sm font-bold text-rose-700">Set sebagai Hari Libur</span>
                                </div>

                                {{-- Jam Khusus (Hidden if Holiday) --}}
                                <div x-show="!isHoliday" x-transition class="space-y-3 bg-slate-50 p-3 rounded-xl border border-slate-200">
                                    <p class="text-xs font-bold text-center text-slate-400">Jam Operasional (Jika Masuk)</p>
                                    <div class="grid grid-cols-2 gap-2">
                                        <input type="time" name="start_in" class="rounded-lg border-slate-200 text-xs text-center">
                                        <input type="time" name="end_in" class="rounded-lg border-slate-200 text-xs text-center">
                                        <input type="time" name="start_out" class="rounded-lg border-slate-200 text-xs text-center">
                                        <input type="time" name="end_out" class="rounded-lg border-slate-200 text-xs text-center">
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="mt-6 w-full py-3 bg-orange-600 text-white font-bold rounded-xl hover:bg-orange-700 transition">Tambah Jadwal Khusus</button>
                        </form>

                        {{-- List Jadwal Khusus --}}
                        <div class="px-6 pb-6">
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Daftar Jadwal Khusus</h4>
                            <div class="space-y-2 max-h-48 overflow-y-auto custom-scrollbar pr-1">
                                @foreach($specialSchedules as $ss)
                                    <div class="flex items-center justify-between p-3 rounded-xl border {{ $ss->is_holiday ? 'bg-rose-50 border-rose-100' : 'bg-blue-50 border-blue-100' }}">
                                        <div>
                                            <p class="text-xs font-bold {{ $ss->is_holiday ? 'text-rose-700' : 'text-blue-700' }}">
                                                {{ \Carbon\Carbon::parse($ss->date)->format('d M Y') }}
                                            </p>
                                            <p class="text-[10px] text-slate-500 truncate max-w-[120px]">{{ $ss->description }}</p>
                                        </div>
                                        <form action="{{ route('schedules.special.destroy', $ss->id) }}" method="POST" onsubmit="return confirm('Hapus?')">
                                            @csrf @method('DELETE')
                                            <button class="text-slate-400 hover:text-rose-500"><i class="ph-bold ph-trash"></i></button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>