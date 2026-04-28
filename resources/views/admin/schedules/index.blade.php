<x-app-layout>
    {{-- 
        X-DATA CONTEXT:
        State 'activeTab' untuk perpindahan antar menu (Mapel vs Jam Sekolah).
    --}}
    <div x-data="{ activeTab: 'mapel' }" class="py-8 sm:py-10 font-sans text-elevate-dark relative overflow-hidden">
        
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        {{-- HERO SECTION (ELEVATED THEME) --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10 relative z-10">
            <div class="relative rounded-[2.5rem] bg-elevate-gradient-main p-8 sm:p-10 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden border border-white/60 group">
                
                {{-- Background Decorations --}}
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-white/40 rounded-full blur-3xl pointer-events-none group-hover:bg-white/60 transition-all duration-700"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    <div class="max-w-2xl">
                        <a href="{{ route('dashboard') }}" class="group bg-white/50 hover:bg-white/80 text-elevate-primary px-5 py-3 rounded-2xl font-bold text-sm backdrop-blur-sm border border-white/60 transition-all flex items-center gap-2 shadow-sm w-fit mb-4 mx-auto xl:mx-0">
                            <i class="ph-bold ph-arrow-left text-sm group-hover:-translate-x-1 transition-transform"></i>
                            <span>Kembali ke Dashboard</span>
                        </a>
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-elevate-soft border border-elevate-accent/30 text-elevate-primary text-[10px] font-bold uppercase tracking-widest mb-4 backdrop-blur-sm shadow-sm">
                            <i class="ph-fill ph-calendar-plus"></i> Akademik & KBM
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-3 flex items-center gap-3 text-elevate-dark leading-tight">
                            Atur Jadwal
                        </h1>
                        <p class="text-elevate-dark/80 text-sm md:text-base font-semibold leading-relaxed max-w-lg">
                            Kelola jadwal pelajaran (KBM) per kelas dengan format Jam Pelajaran (JP) serta jam operasional scanner.
                        </p>
                    </div>
                    
                    {{-- Navigasi Tab Switcher --}}
                    <div class="bg-white/40 backdrop-blur-md p-1.5 rounded-2xl border border-white/50 flex flex-col sm:flex-row gap-1 shadow-sm w-full md:w-auto">
                        <button @click="activeTab = 'mapel'" 
                            :class="activeTab === 'mapel' ? 'bg-elevate-dark text-white shadow-lg shadow-elevate-dark/30' : 'text-elevate-dark/70 hover:bg-white/60 hover:text-elevate-dark'"
                            class="px-6 py-3.5 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2 whitespace-nowrap border border-transparent">
                            <i class="ph-bold ph-book-open text-lg"></i> Jadwal Mapel
                        </button>
                        <button @click="activeTab = 'jam'" 
                            :class="activeTab === 'jam' ? 'bg-elevate-dark text-white shadow-lg shadow-elevate-dark/30' : 'text-elevate-dark/70 hover:bg-white/60 hover:text-elevate-dark'"
                            class="px-6 py-3.5 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2 whitespace-nowrap border border-transparent">
                            <i class="ph-bold ph-clock text-lg"></i> Jam Sekolah
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

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
            
            {{-- Error Validation Alert --}}
            @if ($errors->any())
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-8 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-[2rem] flex items-start gap-3 shadow-sm relative">
                    <div class="p-2 bg-rose-100 rounded-full text-rose-600 shrink-0 ml-2 mt-0.5">
                        <i class="ph-bold ph-warning-circle text-xl"></i>
                    </div>
                    <div>
                        <p class="font-bold text-sm mb-1">Terjadi kesalahan input:</p>
                        <ul class="list-disc list-inside text-xs font-medium">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button @click="show = false" class="absolute top-4 right-4 text-rose-400 hover:text-rose-600"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            {{-- ========================================== --}}
            {{-- TAB 1: JADWAL MAPEL                        --}}
            {{-- ========================================== --}}
            <div x-show="activeTab === 'mapel'" x-transition:enter="transition ease-out duration-300 transform opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                    
                    {{-- Form Input --}}
                    <div class="lg:col-span-1">
                        <div class="bg-white p-8 rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden sticky top-24 group/form hover:shadow-2xl hover:shadow-elevate-accent/10 transition-all duration-300">
                            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-elevate-primary to-elevate-accent"></div>
                            
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-12 h-12 bg-elevate-soft text-elevate-primary rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-elevate-accent/20 group-hover/form:scale-110 transition-transform">
                                    <i class="ph-duotone ph-plus-square"></i>
                                </div>
                                <h2 class="text-xl font-black text-elevate-dark">Tambah Jadwal</h2>
                            </div>

                            <form action="{{ route('schedules.store') }}" method="POST" class="space-y-5">
                                @csrf
                                
                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Kelas</label>
                                    <div class="relative group">
                                        <select name="school_class_id" required class="w-full pl-4 pr-10 py-3.5 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 text-sm font-bold text-elevate-dark transition-all shadow-sm appearance-none cursor-pointer @error('school_class_id') border-rose-300 bg-rose-50 @enderror">
                                            <option value="">-- Pilih Kelas --</option>
                                            @foreach($classes as $c)
                                                <option value="{{ $c->id }}" {{ old('school_class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                            @endforeach
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                    </div>
                                    @error('school_class_id') <span class="text-[10px] text-rose-500 font-bold ml-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Mata Pelajaran</label>
                                    <div class="relative group">
                                        <select name="subject_id" required class="w-full pl-4 pr-10 py-3.5 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 text-sm font-bold text-elevate-dark transition-all shadow-sm appearance-none cursor-pointer">
                                            <option value="">-- Pilih Mapel --</option>
                                            @foreach($subjects as $s)
                                                <option value="{{ $s->id }}" {{ old('subject_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                            @endforeach
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Guru Pengampu</label>
                                    <div class="relative group">
                                        <select name="teacher_id" required class="w-full pl-4 pr-10 py-3.5 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 text-sm font-bold text-elevate-dark transition-all shadow-sm appearance-none cursor-pointer @error('teacher_id') border-rose-300 bg-rose-50 @enderror">
                                            <option value="">-- Pilih Guru --</option>
                                            @foreach($teachers as $t)
                                                <option value="{{ $t->id }}" {{ old('teacher_id') == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                                            @endforeach
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                    </div>
                                    @error('teacher_id') <span class="text-[10px] text-rose-500 font-bold ml-1">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Hari</label>
                                    <div class="grid grid-cols-3 gap-2">
                                        @foreach(['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'] as $day)
                                            <label class="cursor-pointer">
                                                <input type="radio" name="day" value="{{ $day }}" class="peer sr-only" {{ old('day') == $day ? 'checked' : '' }} required>
                                                <div class="text-center py-2.5 rounded-xl bg-elevate-soft border border-slate-200 text-xs font-bold text-elevate-dark/60 peer-checked:bg-elevate-primary peer-checked:text-white peer-checked:border-elevate-primary peer-checked:shadow-md transition-all hover:bg-white hover:border-elevate-accent">
                                                    {{ substr($day, 0, 3) }}
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Mulai Jam Ke-</label>
                                        <div class="relative">
                                            <select name="start_time" required class="w-full text-center py-3.5 rounded-2xl border-slate-200 bg-elevate-soft font-bold text-elevate-dark focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 appearance-none cursor-pointer transition-all shadow-sm">
                                                @for ($i = 1; $i <= 12; $i++)
                                                    <option value="{{ $i }}" {{ old('start_time') == $i ? 'selected' : '' }}>Jam {{ $i }}</option>
                                                @endfor
                                            </select>
                                            <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Selesai Jam Ke-</label>
                                        <div class="relative">
                                            <select name="end_time" required class="w-full text-center py-3.5 rounded-2xl border-slate-200 bg-elevate-soft font-bold text-elevate-dark focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 appearance-none cursor-pointer transition-all shadow-sm">
                                                @for ($i = 1; $i <= 12; $i++)
                                                    <option value="{{ $i }}" {{ old('end_time') == $i ? 'selected' : '' }}>Jam {{ $i }}</option>
                                                @endfor
                                            </select>
                                            <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="w-full mt-6 py-4 px-6 bg-elevate-dark text-white font-bold rounded-2xl hover:bg-elevate-primary transition-all shadow-lg shadow-elevate-dark/30 flex items-center justify-center gap-2 transform active:scale-95 border border-transparent">
                                    <i class="ph-bold ph-floppy-disk text-lg"></i>
                                    Simpan Jadwal
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Tabel Jadwal --}}
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden flex flex-col relative min-h-[600px]">
                            <div class="p-8 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-elevate-soft text-elevate-primary rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-elevate-accent/20">
                                        <i class="ph-duotone ph-list-dashes"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-black text-elevate-dark">Daftar Jadwal</h3>
                                        <p class="text-xs text-elevate-dark/60 font-bold uppercase tracking-wider mt-1">Total {{ $schedules->count() }} Sesi</p>
                                    </div>
                                </div>
                                
                                <form method="GET" class="w-full sm:w-auto">
                                    <div class="relative group">
                                        <select name="class_id" onchange="this.form.submit()" class="w-full sm:w-56 pl-11 pr-10 py-3 rounded-2xl border-slate-200 bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 text-sm font-bold text-elevate-dark transition-all shadow-sm appearance-none cursor-pointer">
                                            <option value="">Semua Kelas</option>
                                            @foreach($classes as $c)
                                                <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>Kelas {{ $c->name }}</option>
                                            @endforeach
                                        </select>
                                        <i class="ph-bold ph-funnel absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-elevate-primary transition-colors"></i>
                                        <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                    </div>
                                </form>
                            </div>
                            
                            <div class="overflow-x-auto flex-1 custom-scrollbar">
                                <table class="min-w-full text-left text-sm text-elevate-dark">
                                    <thead class="bg-elevate-soft/50 text-xs font-bold text-elevate-primary uppercase tracking-wider sticky top-0 z-10 border-b border-slate-100">
                                        <tr>
                                            <th class="px-8 py-5">Hari & Jam Pelajaran</th>
                                            <th class="px-6 py-5">Kelas</th>
                                            <th class="px-6 py-5">Mata Pelajaran</th>
                                            <th class="px-6 py-5">Guru</th>
                                            <th class="px-8 py-5 text-right">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-50">
                                        @forelse($schedules as $item)
                                        <tr class="hover:bg-elevate-soft/30 transition-colors group">
                                            <td class="px-8 py-5 whitespace-nowrap">
                                                <div class="flex flex-col">
                                                    <span class="font-black text-elevate-dark text-base mb-1">{{ $item->day }}</span>
                                                    {{-- MENGGUNAKAN ACCESSOR DARI MODEL --}}
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-elevate-soft text-elevate-primary text-[10px] font-mono font-bold border border-elevate-accent/20 w-fit shadow-sm">
                                                        <i class="ph-bold ph-clock"></i>
                                                        JP {{ $item->clean_start_time }} - {{ $item->clean_end_time }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <span class="px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-elevate-dark text-xs font-bold shadow-sm">
                                                    {{ $item->schoolClass->name }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <div class="font-bold text-elevate-dark text-sm">{{ $item->subject->name }}</div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-xl bg-elevate-peach-light/40 flex items-center justify-center text-[10px] font-black text-elevate-peach-dark border border-elevate-peach/30 shadow-sm">
                                                        {{ substr($item->teacher->name, 0, 1) }}
                                                    </div>
                                                    <span class="font-bold text-elevate-dark/80 text-xs">{{ $item->teacher->name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-8 py-5 whitespace-nowrap text-right">
                                                <div class="flex justify-end items-center opacity-0 group-hover:opacity-100 transition-opacity transform translate-x-2 group-hover:translate-x-0 duration-200">
                                                    <form action="{{ route('schedules.destroy', $item->id) }}" 
                                                          method="POST" 
                                                          id="delete-schedule-{{ $item->id }}"
                                                          class="shrink-0 block">
                                                        @csrf @method('DELETE')
                                                        
                                                        <button type="button" 
                                                                onclick="confirmDelete('delete-schedule-{{ $item->id }}', 'Hapus jadwal mapel {{ $item->subject->name }} di kelas {{ $item->schoolClass->name }}?')"
                                                                class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 transition-all shadow-sm">
                                                            <i class="ph-bold ph-trash text-lg leading-none"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="px-8 py-20 text-center">
                                                <div class="flex flex-col items-center justify-center">
                                                    <div class="w-20 h-20 bg-elevate-soft rounded-full flex items-center justify-center mb-4 text-elevate-primary shadow-inner border border-elevate-accent/20">
                                                        <i class="ph-duotone ph-calendar-slash text-5xl"></i>
                                                    </div>
                                                    <p class="text-base font-black text-elevate-dark mb-1">Belum ada jadwal pelajaran.</p>
                                                    <p class="text-sm text-elevate-dark/60 font-medium">Gunakan formulir di kiri untuk menambah jadwal.</p>
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

            {{-- ========================================== --}}
            {{-- TAB 2: JAM SEKOLAH (BEL)                   --}}
            {{-- ========================================== --}}
            <div x-show="activeTab === 'jam'" x-cloak x-transition:enter="transition ease-out duration-300 transform opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                    
                    {{-- Jam Reguler --}}
                    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative group/card hover:shadow-2xl hover:shadow-elevate-accent/10 transition-all duration-300">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-elevate-primary to-elevate-accent"></div>
                        
                        <div class="p-8 border-b border-slate-100 flex items-center gap-4">
                            <div class="w-14 h-14 bg-elevate-soft text-elevate-primary rounded-2xl flex items-center justify-center text-3xl shadow-sm border border-elevate-accent/20 group-hover/card:scale-110 transition-transform">
                                <i class="ph-duotone ph-clock"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-elevate-dark">Jam Sekolah Reguler</h3>
                                <p class="text-xs text-elevate-dark/60 font-bold uppercase tracking-wider mt-1">Setting Bel Masuk & Pulang</p>
                            </div>
                        </div>

                        <form action="{{ route('schedules.regular.store') }}" method="POST" class="p-6 sm:p-8 space-y-8">
                            @csrf
                            
                            {{-- Hari Biasa --}}
                            <div class="bg-elevate-soft/50 p-6 rounded-[2rem] border border-slate-100 relative transition-colors hover:bg-elevate-soft hover:border-elevate-accent/30">
                                <div class="flex items-center justify-between mb-6">
                                    <h4 class="font-black text-elevate-dark flex items-center gap-2 text-lg">
                                        <span class="w-2 h-8 bg-elevate-primary rounded-full"></span>
                                        Senin - Kamis
                                    </h4>
                                    <input type="hidden" name="day_type[]" value="Biasa">
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 sm:gap-4">
                                    <div>
                                        <label class="block text-[10px] uppercase font-bold text-elevate-primary/70 mb-3 sm:text-center tracking-wider"><i class="ph-bold ph-sun-horizon"></i> Masuk</label>
                                        <div class="flex gap-2">
                                            <div class="w-full">
                                                <input type="time" name="start_in[]" value="{{ optional($regularSchedules->get('Biasa'))->start_in ? \Carbon\Carbon::parse($regularSchedules->get('Biasa')->start_in)->format('H:i') : '' }}" 
                                                    class="w-full rounded-xl border-slate-200 text-sm font-black text-center text-elevate-dark bg-white py-3 shadow-sm focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent transition-all cursor-pointer" title="Jam Buka Scanner Masuk">
                                                <p class="text-[9px] font-bold text-elevate-dark/50 text-center mt-1.5 uppercase">Buka Scan</p>
                                            </div>
                                            <div class="w-full">
                                                <input type="time" name="end_in[]" value="{{ optional($regularSchedules->get('Biasa'))->end_in ? \Carbon\Carbon::parse($regularSchedules->get('Biasa')->end_in)->format('H:i') : '' }}" 
                                                    class="w-full rounded-xl border-slate-200 text-sm font-black text-center text-elevate-dark bg-white py-3 shadow-sm focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent transition-all cursor-pointer" title="Batas Terlambat">
                                                <p class="text-[9px] font-bold text-elevate-dark/50 text-center mt-1.5 uppercase">Batas Telat</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] uppercase font-bold text-elevate-primary/70 mb-3 mt-2 sm:mt-0 sm:text-center tracking-wider"><i class="ph-bold ph-moon-stars text-elevate-dark"></i> Pulang</label>
                                        <div class="flex gap-2">
                                            <div class="w-full">
                                                <input type="time" name="start_out[]" value="{{ optional($regularSchedules->get('Biasa'))->start_out ? \Carbon\Carbon::parse($regularSchedules->get('Biasa')->start_out)->format('H:i') : '' }}" 
                                                    class="w-full rounded-xl border-slate-200 text-sm font-black text-center text-elevate-dark bg-white py-3 shadow-sm focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent transition-all cursor-pointer" title="Jam Boleh Pulang">
                                                <p class="text-[9px] font-bold text-elevate-dark/50 text-center mt-1.5 uppercase">Boleh Plg</p>
                                            </div>
                                            <div class="w-full">
                                                <input type="time" name="end_out[]" value="{{ optional($regularSchedules->get('Biasa'))->end_out ? \Carbon\Carbon::parse($regularSchedules->get('Biasa')->end_out)->format('H:i') : '' }}" 
                                                    class="w-full rounded-xl border-slate-200 text-sm font-black text-center text-elevate-dark bg-white py-3 shadow-sm focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent transition-all cursor-pointer" title="Tutup Scanner Pulang">
                                                <p class="text-[9px] font-bold text-elevate-dark/50 text-center mt-1.5 uppercase">Tutup Scan</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Hari Jumat --}}
                            <div class="bg-elevate-peach-light/20 p-6 rounded-[2rem] border border-elevate-peach/30 relative transition-colors hover:bg-elevate-peach-light/40 hover:border-elevate-peach/50">
                                <div class="flex items-center justify-between mb-6">
                                    <h4 class="font-black text-elevate-dark flex items-center gap-2 text-lg">
                                        <span class="w-2 h-8 bg-elevate-peach-dark rounded-full"></span>
                                        Hari Jum'at
                                    </h4>
                                    <input type="hidden" name="day_type[]" value="Jumat">
                                </div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 sm:gap-4">
                                    <div>
                                        <label class="block text-[10px] uppercase font-bold text-elevate-peach-dark/80 mb-3 sm:text-center tracking-wider"><i class="ph-bold ph-sun-horizon"></i> Masuk</label>
                                        <div class="flex gap-2">
                                            <div class="w-full">
                                                <input type="time" name="start_in[]" value="{{ optional($regularSchedules->get('Jumat'))->start_in ? \Carbon\Carbon::parse($regularSchedules->get('Jumat')->start_in)->format('H:i') : '' }}" 
                                                    class="w-full rounded-xl border-slate-200 text-sm font-black text-center text-elevate-dark bg-white py-3 shadow-sm focus:ring-4 focus:ring-elevate-peach/30 focus:border-elevate-peach transition-all cursor-pointer">
                                                <p class="text-[9px] font-bold text-elevate-peach-dark/60 text-center mt-1.5 uppercase">Buka Scan</p>
                                            </div>
                                            <div class="w-full">
                                                <input type="time" name="end_in[]" value="{{ optional($regularSchedules->get('Jumat'))->end_in ? \Carbon\Carbon::parse($regularSchedules->get('Jumat')->end_in)->format('H:i') : '' }}" 
                                                    class="w-full rounded-xl border-slate-200 text-sm font-black text-center text-elevate-dark bg-white py-3 shadow-sm focus:ring-4 focus:ring-elevate-peach/30 focus:border-elevate-peach transition-all cursor-pointer">
                                                <p class="text-[9px] font-bold text-elevate-peach-dark/60 text-center mt-1.5 uppercase">Batas Telat</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] uppercase font-bold text-elevate-peach-dark/80 mb-3 mt-2 sm:mt-0 sm:text-center tracking-wider"><i class="ph-bold ph-moon-stars text-elevate-dark"></i> Pulang</label>
                                        <div class="flex gap-2">
                                            <div class="w-full">
                                                <input type="time" name="start_out[]" value="{{ optional($regularSchedules->get('Jumat'))->start_out ? \Carbon\Carbon::parse($regularSchedules->get('Jumat')->start_out)->format('H:i') : '' }}" 
                                                    class="w-full rounded-xl border-slate-200 text-sm font-black text-center text-elevate-dark bg-white py-3 shadow-sm focus:ring-4 focus:ring-elevate-peach/30 focus:border-elevate-peach transition-all cursor-pointer">
                                                <p class="text-[9px] font-bold text-elevate-peach-dark/60 text-center mt-1.5 uppercase">Boleh Plg</p>
                                            </div>
                                            <div class="w-full">
                                                <input type="time" name="end_out[]" value="{{ optional($regularSchedules->get('Jumat'))->end_out ? \Carbon\Carbon::parse($regularSchedules->get('Jumat')->end_out)->format('H:i') : '' }}" 
                                                    class="w-full rounded-xl border-slate-200 text-sm font-black text-center text-elevate-dark bg-white py-3 shadow-sm focus:ring-4 focus:ring-elevate-peach/30 focus:border-elevate-peach transition-all cursor-pointer">
                                                <p class="text-[9px] font-bold text-elevate-peach-dark/60 text-center mt-1.5 uppercase">Tutup Scan</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="w-full py-4 px-6 bg-elevate-dark text-white font-bold rounded-2xl hover:bg-elevate-primary transition-all shadow-lg shadow-elevate-dark/30 flex items-center justify-center gap-2 transform active:scale-95 border border-transparent">
                                <i class="ph-bold ph-floppy-disk text-xl"></i>
                                Simpan Jam Reguler
                            </button>
                        </form>
                    </div>

                    {{-- Jadwal Khusus --}}
                    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative group/card2 hover:shadow-2xl hover:shadow-elevate-accent/10 transition-all duration-300" x-data="{ isHoliday: false }">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-elevate-peach to-elevate-peach-dark"></div>
                        
                        <div class="p-8 border-b border-slate-100 flex items-center gap-4">
                            <div class="w-14 h-14 bg-elevate-peach-light/40 text-elevate-peach-dark rounded-2xl flex items-center justify-center text-3xl shadow-sm border border-elevate-peach/30 group-hover/card2:scale-110 transition-transform">
                                <i class="ph-duotone ph-calendar-blank"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-elevate-dark">Jadwal Khusus / Libur</h3>
                                <p class="text-xs text-elevate-dark/60 font-bold uppercase tracking-wider mt-1">Tanggal Merah & Acara</p>
                            </div>
                        </div>

                        <div class="p-8 space-y-8">
                            <form action="{{ route('schedules.special.store') }}" method="POST" class="space-y-5">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Tanggal</label>
                                    <div class="relative">
                                        <i class="ph-bold ph-calendar absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="date" name="date" required class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 font-bold text-elevate-dark text-sm shadow-sm transition-all outline-none cursor-pointer">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Keterangan</label>
                                    <div class="relative">
                                        <i class="ph-bold ph-text-t absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" name="description" placeholder="Contoh: Rapat Guru" class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 font-bold text-elevate-dark text-sm shadow-sm placeholder:font-medium placeholder:text-slate-400 transition-all outline-none">
                                    </div>
                                </div>
                                
                                <div class="bg-rose-50 p-4 rounded-2xl border border-rose-100 flex items-center gap-4 cursor-pointer hover:bg-rose-100 transition-colors select-none shadow-sm" @click="isHoliday = !isHoliday">
                                    <div class="relative flex items-center ml-1">
                                        <input type="checkbox" name="is_holiday" value="1" class="peer sr-only" x-model="isHoliday">
                                        <div class="w-10 h-6 bg-slate-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-500 shadow-inner"></div>
                                    </div>
                                    <div class="flex-1">
                                        <span class="block text-sm font-black text-rose-800">Set Sebagai Hari Libur</span>
                                    </div>
                                    <i class="ph-duotone ph-coffee text-rose-500 text-2xl mr-1"></i>
                                </div>

                                <div x-show="!isHoliday" x-transition class="bg-elevate-soft/50 p-5 rounded-2xl border border-elevate-accent/20 space-y-4">
                                    <p class="text-[10px] font-black text-center text-elevate-primary/70 uppercase tracking-wider border-b border-elevate-accent/20 pb-3">Jam Operasional (Opsional)</p>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="text-center">
                                            <input type="time" name="start_in" class="w-full rounded-xl border-slate-200 text-xs text-center font-bold py-2.5 px-1 bg-white focus:ring-2 focus:ring-elevate-accent shadow-sm outline-none cursor-pointer">
                                            <p class="text-[9px] text-elevate-dark/50 font-bold mt-1.5 uppercase">Buka Masuk</p>
                                        </div>
                                        <div class="text-center">
                                            <input type="time" name="end_in" class="w-full rounded-xl border-slate-200 text-xs text-center font-bold py-2.5 px-1 bg-white focus:ring-2 focus:ring-elevate-accent shadow-sm outline-none cursor-pointer">
                                            <p class="text-[9px] text-elevate-dark/50 font-bold mt-1.5 uppercase">Batas Telat</p>
                                        </div>
                                        <div class="text-center">
                                            <input type="time" name="start_out" class="w-full rounded-xl border-slate-200 text-xs text-center font-bold py-2.5 px-1 bg-white focus:ring-2 focus:ring-elevate-accent shadow-sm outline-none cursor-pointer">
                                            <p class="text-[9px] text-elevate-dark/50 font-bold mt-1.5 uppercase">Boleh Plg</p>
                                        </div>
                                        <div class="text-center">
                                            <input type="time" name="end_out" class="w-full rounded-xl border-slate-200 text-xs text-center font-bold py-2.5 px-1 bg-white focus:ring-2 focus:ring-elevate-accent shadow-sm outline-none cursor-pointer">
                                            <p class="text-[9px] text-elevate-dark/50 font-bold mt-1.5 uppercase">Tutup Plg</p>
                                        </div>
                                    </div>
                                </div>

                                <button type="submit" class="w-full py-4 px-6 bg-elevate-dark text-white font-bold rounded-2xl hover:bg-elevate-primary transition-all shadow-lg shadow-elevate-dark/30 flex items-center justify-center gap-2 transform active:scale-95 border border-transparent">
                                    <i class="ph-bold ph-plus-circle text-lg"></i>
                                    Tambah Jadwal Khusus
                                </button>
                            </form>

                            <div>
                                <h4 class="text-[10px] font-black text-elevate-dark/50 uppercase tracking-wider mb-3 ml-1">Terbaru Ditambahkan</h4>
                                <div class="space-y-3 max-h-48 overflow-y-auto custom-scrollbar pr-2">
                                    @forelse($specialSchedules as $ss)
                                        <div class="flex items-center justify-between p-4 rounded-2xl border shadow-sm transition-colors hover:shadow-md {{ $ss->is_holiday ? 'bg-rose-50 border-rose-100 hover:border-rose-200' : 'bg-elevate-soft border-elevate-accent/20 hover:border-elevate-accent/40' }}">
                                            <div class="overflow-hidden mr-3"> 
                                                <p class="text-xs font-black {{ $ss->is_holiday ? 'text-rose-700' : 'text-elevate-primary' }}">
                                                    {{ \Carbon\Carbon::parse($ss->date)->format('d M Y') }}
                                                </p>
                                                <p class="text-xs font-bold text-elevate-dark truncate max-w-[180px] mt-0.5">{{ $ss->description }}</p>
                                            </div>
                                            
                                            <form action="{{ route('schedules.special.destroy', $ss->id) }}" 
                                                  method="POST"
                                                  id="delete-special-{{ $ss->id }}"
                                                  class="shrink-0 block">
                                                @csrf @method('DELETE')
                                                
                                                <button type="button"
                                                        onclick="confirmDelete('delete-special-{{ $ss->id }}', 'Hapus agenda {{ addslashes($ss->description) }} pada tanggal {{ \Carbon\Carbon::parse($ss->date)->format('d M Y') }}?')"
                                                        class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-slate-200 hover:border-rose-200 text-slate-400 hover:bg-rose-50 hover:text-rose-600 transition-all shadow-sm">
                                                    <i class="ph-bold ph-trash text-lg leading-none"></i>
                                                </button>
                                            </form>
                                        </div>
                                    @empty
                                        <div class="text-center py-6 text-xs font-bold text-elevate-dark/40 italic bg-slate-50 rounded-xl border border-slate-100">Belum ada data jadwal khusus.</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- SweetAlert2 Library --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // Fungsi Generic untuk menghapus data di halaman ini
        function confirmDelete(formId, message) {
            Swal.fire({
                title: 'Hapus Data?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2rem] font-sans border-0 shadow-2xl',
                    confirmButton: 'bg-rose-600 text-white px-6 py-3.5 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-2 shadow-lg shadow-rose-600/30',
                    cancelButton: 'bg-slate-100 text-slate-600 px-6 py-3.5 rounded-xl font-bold hover:bg-slate-200 transition-colors mx-2'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById(formId);
                    if (form) {
                        form.submit();
                    } else {
                        console.error('Form not found:', formId);
                        Swal.fire('Error', 'Form tidak ditemukan. Silakan refresh halaman.', 'error');
                    }
                }
            });
        }
    </script>
</x-app-layout>