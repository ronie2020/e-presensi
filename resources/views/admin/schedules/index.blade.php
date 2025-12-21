<x-app-layout>
    {{-- 
        X-DATA CONTEXT:
        State 'activeTab' untuk perpindahan antar menu (Mapel vs Jam Sekolah).
    --}}
    <div x-data="{ activeTab: 'mapel' }" class="py-8 sm:py-10 font-sans text-slate-800">
        
        {{-- HERO SECTION --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
            <div class="relative rounded-[2.5rem] bg-gray-900 bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 p-8 sm:p-10 text-white shadow-2xl shadow-blue-900/40 overflow-hidden border border-white/10 group">
                
                {{-- Background Decorations --}}
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-purple-500/20 rounded-full blur-3xl pointer-events-none group-hover:bg-purple-500/30 transition-all duration-700"></div>
                <div class="absolute bottom-0 right-20 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    
                    {{-- Text Content --}}
                    <div class="max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-200 text-[10px] font-bold uppercase tracking-widest mb-4 backdrop-blur-sm">
                            <i class="ph-fill ph-calendar-plus"></i> Akademik & KBM
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-3 flex items-center gap-3 text-white leading-tight">
                            Atur Jadwal
                        </h1>
                        <p class="text-blue-100/80 text-sm md:text-base font-medium leading-relaxed max-w-lg">
                            Kelola jadwal pelajaran (KBM) per kelas dan pengaturan jam masuk/pulang (bel sekolah).
                        </p>
                    </div>
                    
                    {{-- Tab Switcher Modern (Integrated in Hero) --}}
                    <div class="bg-white/10 backdrop-blur-md p-1.5 rounded-2xl border border-white/10 flex flex-col sm:flex-row gap-1">
                        <button @click="activeTab = 'mapel'" 
                            :class="activeTab === 'mapel' ? 'bg-white text-blue-900 shadow-lg' : 'text-blue-100 hover:bg-white/10'"
                            class="px-6 py-3 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2 whitespace-nowrap">
                            <i class="ph-bold ph-book-open"></i> Jadwal Mapel
                        </button>
                        <button @click="activeTab = 'jam'" 
                            :class="activeTab === 'jam' ? 'bg-white text-blue-900 shadow-lg' : 'text-blue-100 hover:bg-white/10'"
                            class="px-6 py-3 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2 whitespace-nowrap">
                            <i class="ph-bold ph-clock"></i> Jam Sekolah
                        </button>
                    </div>

                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Pesan Flash --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-[2rem] flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3 px-2">
                        <div class="p-2 bg-emerald-100 rounded-full text-emerald-600">
                            <i class="ph-bold ph-check-circle text-xl"></i>
                        </div>
                        <span class="font-bold text-sm">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 p-2 rounded-xl hover:bg-emerald-100 transition"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif
            @if ($errors->any())
                <div class="mb-8 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-[2rem] flex items-center gap-3 shadow-sm">
                    <div class="p-2 bg-rose-100 rounded-full text-rose-600 shrink-0 ml-2">
                        <i class="ph-bold ph-warning-circle text-xl"></i>
                    </div>
                    <div>
                        <p class="font-bold text-sm mb-1">Periksa inputan anda:</p>
                        <ul class="list-disc list-inside text-xs font-medium">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- ========================================================= --}}
            {{-- TAB 1: INPUT JADWAL PELAJARAN (MAPEL)                     --}}
            {{-- ========================================================= --}}
            <div x-show="activeTab === 'mapel'" x-transition:enter="transition ease-out duration-300 transform opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                    
                    {{-- KOLOM KIRI: FORM INPUT (Style Akses Cepat) --}}
                    <div class="lg:col-span-1">
                        <div class="bg-white p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden sticky top-24">
                            {{-- Aksen Header --}}
                            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-500 to-indigo-600"></div>
                            
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl shadow-sm border border-blue-100">
                                    <i class="ph-duotone ph-plus-square"></i>
                                </div>
                                <h2 class="text-lg font-black text-slate-800">Tambah Jadwal</h2>
                            </div>

                            <form action="{{ route('schedules.store') }}" method="POST" class="space-y-4">
                                @csrf
                                
                                {{-- Pilih Kelas --}}
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Kelas</label>
                                    <div class="relative">
                                        <select name="school_class_id" required class="w-full pl-4 pr-10 py-3 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm font-bold text-slate-700 transition-all shadow-sm appearance-none cursor-pointer">
                                            <option value="">-- Pilih Kelas --</option>
                                            @foreach($classes as $c)
                                                <option value="{{ $c->id }}" {{ old('school_class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                            @endforeach
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                    </div>
                                </div>

                                {{-- Pilih Mapel --}}
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Mata Pelajaran</label>
                                    <div class="relative">
                                        <select name="subject_id" required class="w-full pl-4 pr-10 py-3 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm font-bold text-slate-700 transition-all shadow-sm appearance-none cursor-pointer">
                                            <option value="">-- Pilih Mapel --</option>
                                            @foreach($subjects as $s)
                                                <option value="{{ $s->id }}" {{ old('subject_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                            @endforeach
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                    </div>
                                </div>

                                {{-- Pilih Guru --}}
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Guru Pengampu</label>
                                    <div class="relative">
                                        <select name="teacher_id" required class="w-full pl-4 pr-10 py-3 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm font-bold text-slate-700 transition-all shadow-sm appearance-none cursor-pointer">
                                            <option value="">-- Pilih Guru --</option>
                                            @foreach($teachers as $t)
                                                <option value="{{ $t->id }}" {{ old('teacher_id') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                                            @endforeach
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                    </div>
                                </div>

                                {{-- Pilih Hari --}}
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Hari</label>
                                    <div class="grid grid-cols-3 gap-2">
                                        @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $day)
                                            <label class="cursor-pointer">
                                                <input type="radio" name="day" value="{{ $day }}" class="peer sr-only" {{ old('day') == $day ? 'checked' : '' }} required>
                                                <div class="text-center py-2 rounded-xl bg-slate-50 border border-slate-200 text-xs font-bold text-slate-500 peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-blue-600 peer-checked:shadow-md transition-all hover:bg-slate-100">
                                                    {{ substr($day, 0, 3) }}
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Jam --}}
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Mulai</label>
                                        <input type="time" name="start_time" required value="{{ old('start_time') }}" class="w-full text-center py-3 rounded-2xl border-slate-200 bg-slate-50 font-bold text-slate-700 focus:ring-blue-500">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Selesai</label>
                                        <input type="time" name="end_time" required value="{{ old('end_time') }}" class="w-full text-center py-3 rounded-2xl border-slate-200 bg-slate-50 font-bold text-slate-700 focus:ring-blue-500">
                                    </div>
                                </div>

                                <button type="submit" class="w-full mt-4 py-3.5 px-6 bg-blue-900 text-white font-bold rounded-2xl hover:bg-blue-800 transition-all shadow-lg shadow-blue-900/30 flex items-center justify-center gap-2 transform active:scale-[0.98]">
                                    <i class="ph-bold ph-floppy-disk text-lg"></i>
                                    Simpan Jadwal
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- KOLOM KANAN: TABEL JADWAL --}}
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden flex flex-col relative min-h-[600px]">
                            {{-- Aksen Header --}}
                            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-500 to-indigo-600"></div>

                            <div class="p-8 border-b border-slate-50 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-xl shadow-sm border border-indigo-100">
                                        <i class="ph-duotone ph-list-dashes"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-black text-slate-800">Daftar Jadwal</h3>
                                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Total {{ $schedules->count() }} Sesi</p>
                                    </div>
                                </div>
                                
                                {{-- Filter Kelas --}}
                                <form method="GET" class="w-full sm:w-auto">
                                    <div class="relative">
                                        <select name="class_id" onchange="this.form.submit()" class="w-full sm:w-48 pl-4 pr-10 py-2.5 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-bold text-slate-700 transition-all shadow-sm appearance-none cursor-pointer">
                                            <option value="">Semua Kelas</option>
                                            @foreach($classes as $c)
                                                <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>Kelas {{ $c->name }}</option>
                                            @endforeach
                                        </select>
                                        <i class="ph-bold ph-funnel absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                    </div>
                                </form>
                            </div>
                            
                            <div class="overflow-x-auto flex-1 custom-scrollbar">
                                <table class="min-w-full text-left text-sm text-slate-600">
                                    <thead class="bg-slate-50 text-xs font-bold text-slate-400 uppercase tracking-wider sticky top-0 z-10">
                                        <tr>
                                            <th class="px-6 py-5">Hari & Jam</th>
                                            <th class="px-6 py-5">Kelas</th>
                                            <th class="px-6 py-5">Mata Pelajaran</th>
                                            <th class="px-6 py-5">Guru</th>
                                            <th class="px-6 py-5 text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-50">
                                        @forelse($schedules as $item)
                                        <tr class="hover:bg-slate-50 transition-colors group">
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <div class="flex flex-col">
                                                    <span class="font-black text-slate-800 text-sm mb-1">{{ $item->day }}</span>
                                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-blue-50 text-blue-600 text-[10px] font-mono font-bold border border-blue-100 w-fit">
                                                        <i class="ph-bold ph-clock"></i>
                                                        {{ \Carbon\Carbon::parse($item->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->end_time)->format('H:i') }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <span class="px-3 py-1.5 rounded-xl bg-slate-100 text-slate-600 text-xs font-bold border border-slate-200">
                                                    {{ $item->schoolClass->name }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <div class="font-bold text-slate-700">{{ $item->subject->name }}</div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <div class="flex items-center gap-2">
                                                    <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-500">
                                                        {{ substr($item->teacher->name, 0, 1) }}
                                                    </div>
                                                    <span class="font-bold text-slate-600 text-xs">{{ $item->teacher->name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap text-right">
                                                <form action="{{ route('schedules.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus jadwal ini?')">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-300 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 transition-all shadow-sm">
                                                        <i class="ph-bold ph-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-20 text-center text-slate-400">
                                                <div class="flex flex-col items-center justify-center">
                                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-300 shadow-inner">
                                                        <i class="ph-duotone ph-calendar-slash text-4xl"></i>
                                                    </div>
                                                    <p class="text-sm font-bold text-slate-500">Belum ada jadwal pelajaran.</p>
                                                    <p class="text-xs text-slate-400 mt-1">Gunakan formulir di kiri untuk menambah.</p>
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

            {{-- ========================================================= --}}
            {{-- TAB 2: PENGATURAN JAM SEKOLAH (BEL)                       --}}
            {{-- ========================================================= --}}
            <div x-show="activeTab === 'jam'" x-cloak x-transition:enter="transition ease-out duration-300 transform opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                    
                    {{-- KARTU 1: JAM REGULER --}}
                    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-purple-500 to-fuchsia-600"></div>
                        
                        <div class="p-8 border-b border-slate-50 flex items-center gap-4">
                            <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-purple-100">
                                <i class="ph-duotone ph-clock"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-800">Jam Sekolah Reguler</h3>
                                <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Setting Jam Masuk & Pulang</p>
                            </div>
                        </div>

                        <form action="{{ route('schedules.regular.store') }}" method="POST" class="p-8 space-y-8">
                            @csrf
                            
                            {{-- Hari Biasa --}}
                            <div class="bg-slate-50/80 p-6 rounded-[2rem] border border-slate-200 relative group hover:border-blue-200 transition-colors">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="font-black text-slate-700 flex items-center gap-2">
                                        <i class="ph-fill ph-sun text-amber-500"></i> Senin - Kamis
                                    </h4>
                                    <input type="hidden" name="day_type[]" value="Biasa">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[10px] uppercase font-bold text-slate-400 mb-2 text-center">Masuk</label>
                                        <div class="flex gap-1.5">
                                            <input type="time" name="start_in[]" value="{{ isset($regularSchedules['Biasa']->start_in) ? \Carbon\Carbon::parse($regularSchedules['Biasa']->start_in)->format('H:i') : '' }}" class="w-full rounded-xl border-slate-200 text-xs font-bold text-center bg-white py-2.5 shadow-sm">
                                            <input type="time" name="end_in[]" value="{{ isset($regularSchedules['Biasa']->end_in) ? \Carbon\Carbon::parse($regularSchedules['Biasa']->end_in)->format('H:i') : '' }}" class="w-full rounded-xl border-slate-200 text-xs font-bold text-center bg-white py-2.5 shadow-sm">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] uppercase font-bold text-slate-400 mb-2 text-center">Pulang</label>
                                        <div class="flex gap-1.5">
                                            <input type="time" name="start_out[]" value="{{ isset($regularSchedules['Biasa']->start_out) ? \Carbon\Carbon::parse($regularSchedules['Biasa']->start_out)->format('H:i') : '' }}" class="w-full rounded-xl border-slate-200 text-xs font-bold text-center bg-white py-2.5 shadow-sm">
                                            <input type="time" name="end_out[]" value="{{ isset($regularSchedules['Biasa']->end_out) ? \Carbon\Carbon::parse($regularSchedules['Biasa']->end_out)->format('H:i') : '' }}" class="w-full rounded-xl border-slate-200 text-xs font-bold text-center bg-white py-2.5 shadow-sm">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Hari Jumat --}}
                            <div class="bg-purple-50/50 p-6 rounded-[2rem] border border-purple-100 relative group hover:border-purple-200 transition-colors">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="font-black text-purple-900 flex items-center gap-2">
                                        <i class="ph-fill ph-moon-stars text-purple-500"></i> Hari Jum'at
                                    </h4>
                                    <input type="hidden" name="day_type[]" value="Jumat">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-[10px] uppercase font-bold text-purple-400 mb-2 text-center">Masuk</label>
                                        <div class="flex gap-1.5">
                                            <input type="time" name="start_in[]" value="{{ isset($regularSchedules['Jumat']->start_in) ? \Carbon\Carbon::parse($regularSchedules['Jumat']->start_in)->format('H:i') : '' }}" class="w-full rounded-xl border-purple-200 text-xs font-bold text-center bg-white py-2.5 shadow-sm text-purple-900 focus:ring-purple-500">
                                            <input type="time" name="end_in[]" value="{{ isset($regularSchedules['Jumat']->end_in) ? \Carbon\Carbon::parse($regularSchedules['Jumat']->end_in)->format('H:i') : '' }}" class="w-full rounded-xl border-purple-200 text-xs font-bold text-center bg-white py-2.5 shadow-sm text-purple-900 focus:ring-purple-500">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] uppercase font-bold text-purple-400 mb-2 text-center">Pulang</label>
                                        <div class="flex gap-1.5">
                                            <input type="time" name="start_out[]" value="{{ isset($regularSchedules['Jumat']->start_out) ? \Carbon\Carbon::parse($regularSchedules['Jumat']->start_out)->format('H:i') : '' }}" class="w-full rounded-xl border-purple-200 text-xs font-bold text-center bg-white py-2.5 shadow-sm text-purple-900 focus:ring-purple-500">
                                            <input type="time" name="end_out[]" value="{{ isset($regularSchedules['Jumat']->end_out) ? \Carbon\Carbon::parse($regularSchedules['Jumat']->end_out)->format('H:i') : '' }}" class="w-full rounded-xl border-purple-200 text-xs font-bold text-center bg-white py-2.5 shadow-sm text-purple-900 focus:ring-purple-500">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="w-full py-3.5 px-6 bg-slate-900 text-white font-bold rounded-2xl hover:bg-slate-800 transition-all shadow-lg shadow-slate-900/30 flex items-center justify-center gap-2 transform active:scale-[0.98]">
                                <i class="ph-bold ph-floppy-disk text-lg"></i>
                                Simpan Jam Reguler
                            </button>
                        </form>
                    </div>

                    {{-- KARTU 2: JADWAL KHUSUS --}}
                    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative" x-data="{ isHoliday: false }">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-orange-500 to-rose-500"></div>
                        
                        <div class="p-8 border-b border-slate-50 flex items-center gap-4">
                            <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-orange-100">
                                <i class="ph-duotone ph-calendar-blank"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-800">Jadwal Khusus / Libur</h3>
                                <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Tanggal Merah & Acara</p>
                            </div>
                        </div>

                        <div class="p-8 space-y-8">
                            <form action="{{ route('schedules.special.store') }}" method="POST" class="space-y-5">
                                @csrf
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tanggal</label>
                                    <input type="date" name="date" required class="w-full px-4 py-3 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-orange-500 focus:ring-orange-500 font-bold text-slate-700 text-sm shadow-sm">
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Keterangan</label>
                                    <input type="text" name="description" placeholder="Contoh: Rapat Guru" class="w-full px-4 py-3 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-orange-500 focus:ring-orange-500 font-bold text-slate-700 text-sm shadow-sm placeholder:font-normal">
                                </div>
                                
                                {{-- Toggle Libur Modern --}}
                                <div class="bg-rose-50 p-3 rounded-2xl border border-rose-100 flex items-center gap-4 cursor-pointer hover:bg-rose-100 transition-colors select-none" @click="isHoliday = !isHoliday">
                                    <div class="relative flex items-center ml-1">
                                        <input type="checkbox" name="is_holiday" value="1" class="peer sr-only" x-model="isHoliday">
                                        <div class="w-10 h-6 bg-slate-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-500 shadow-inner"></div>
                                    </div>
                                    <div class="flex-1">
                                        <span class="block text-xs font-black text-rose-800">Set Sebagai Hari Libur</span>
                                    </div>
                                </div>

                                {{-- Jam Khusus --}}
                                <div x-show="!isHoliday" x-transition class="bg-slate-50 p-4 rounded-2xl border border-slate-200 space-y-3">
                                    <p class="text-[10px] font-black text-center text-slate-400 uppercase tracking-wide border-b border-slate-200 pb-2">Jam Operasional (Opsional)</p>
                                    <div class="grid grid-cols-2 gap-2">
                                        <input type="time" name="start_in" class="rounded-xl border-slate-200 text-xs text-center font-bold py-2">
                                        <input type="time" name="end_in" class="rounded-xl border-slate-200 text-xs text-center font-bold py-2">
                                        <input type="time" name="start_out" class="rounded-xl border-slate-200 text-xs text-center font-bold py-2">
                                        <input type="time" name="end_out" class="rounded-xl border-slate-200 text-xs text-center font-bold py-2">
                                    </div>
                                </div>

                                <button type="submit" class="w-full py-3.5 px-6 bg-orange-600 text-white font-bold rounded-2xl hover:bg-orange-700 transition-all shadow-lg shadow-orange-500/30 flex items-center justify-center gap-2 transform active:scale-[0.98]">
                                    <i class="ph-bold ph-plus-circle text-lg"></i>
                                    Tambah Jadwal Khusus
                                </button>
                            </form>

                            {{-- List Mini --}}
                            <div>
                                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-wider mb-3 ml-1">Terbaru Ditambahkan</h4>
                                <div class="space-y-3 max-h-48 overflow-y-auto custom-scrollbar pr-2">
                                    @forelse($specialSchedules as $ss)
                                        <div class="flex items-center justify-between p-4 rounded-2xl border {{ $ss->is_holiday ? 'bg-rose-50 border-rose-100' : 'bg-blue-50 border-blue-100' }}">
                                            <div>
                                                <p class="text-xs font-black {{ $ss->is_holiday ? 'text-rose-700' : 'text-blue-700' }}">
                                                    {{ \Carbon\Carbon::parse($ss->date)->format('d M Y') }}
                                                </p>
                                                <p class="text-[10px] font-bold text-slate-500 truncate max-w-[150px]">{{ $ss->description }}</p>
                                            </div>
                                            <form action="{{ route('schedules.special.destroy', $ss->id) }}" method="POST" onsubmit="return confirm('Hapus?')">
                                                @csrf @method('DELETE')
                                                <button class="w-8 h-8 flex items-center justify-center rounded-xl bg-white/50 hover:bg-white text-slate-400 hover:text-rose-600 transition-all shadow-sm">
                                                    <i class="ph-bold ph-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @empty
                                        <div class="text-center py-4 text-xs font-bold text-slate-400 italic">Belum ada data.</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>