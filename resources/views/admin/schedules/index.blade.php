<x-app-layout>
    {{-- 
        X-DATA CONTEXT:
        Menambahkan state untuk tab navigasi antara Jam Sekolah (Absen) dan Jam Pembelajaran (Bel)
    --}}
    <div x-data="{ activeTab: 'jam_sekolah' }" class="py-8 sm:py-10 font-sans text-elevate-dark relative overflow-hidden">
        
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
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-elevate-soft border border-elevate-accent/30 text-elevate-primary text-[10px] font-bold uppercase tracking-widest mb-4 backdrop-blur-sm shadow-sm">
                            <i class="ph-fill ph-timer"></i> Konfigurasi Sistem
                        </div>
                        <h1 class="text-3xl md:text-5xl font-black tracking-tight mb-4 flex items-center gap-4 text-elevate-dark">
                            Pengaturan Waktu
                        </h1>
                        <p class="text-elevate-dark/80 text-sm font-semibold leading-relaxed">
                            Atur batas waktu <i>Scan RFID</i> untuk kedatangan/kepulangan, serta tentukan jam bel otomatis untuk jam pelajaran.
                        </p>
                    </div>

                    {{-- Quick Stats --}}
                    <div class="flex gap-4 w-full md:w-auto">
                        <div class="bg-white/60 backdrop-blur-md px-6 py-4 rounded-2xl border border-white/80 flex-1 md:flex-none text-center shadow-sm">
                            <span class="block text-2xl font-black text-elevate-dark mb-1">{{ count($specialSchedules ?? []) }}</span>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-elevate-primary">Libur Khusus</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Alert Messages --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm animate-enter">
                    <span class="font-bold text-sm flex items-center gap-2"><i class="ph-fill ph-check-circle text-lg"></i> {{ session('success') }}</span>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 transition-colors"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif
            @if (session('error') || $errors->any())
                <div x-data="{ show: true }" x-show="show" class="mb-8 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl flex items-center justify-between shadow-sm animate-enter">
                    <span class="font-bold text-sm flex items-center gap-2"><i class="ph-fill ph-warning-circle text-lg"></i> {{ session('error') ?? 'Terdapat kesalahan pada input.' }}</span>
                    <button @click="show = false" class="text-rose-400 hover:text-rose-600 transition-colors"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            {{-- MAIN CONTENT --}}
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                <div class="absolute top-0 right-0 w-96 h-96 bg-elevate-primary/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>

                {{-- TABS NAVIGATION --}}
                <div class="flex border-b border-slate-100 px-6 sm:px-8 relative z-10 bg-slate-50/50">
                    <button @click="activeTab = 'jam_sekolah'" :class="{ 'border-elevate-primary text-elevate-primary bg-white/50': activeTab === 'jam_sekolah', 'border-transparent text-slate-500 hover:text-elevate-primary': activeTab !== 'jam_sekolah' }" class="px-6 py-5 font-black text-sm uppercase tracking-wider transition-all border-b-2 flex items-center gap-2 backdrop-blur-sm focus:outline-none">
                        <i class="ph-bold ph-timer text-lg"></i>
                        Jam Absen & Libur
                    </button>
                    <button @click="activeTab = 'jam_pembelajaran'" :class="{ 'border-elevate-primary text-elevate-primary bg-white/50': activeTab === 'jam_pembelajaran', 'border-transparent text-slate-500 hover:text-elevate-primary': activeTab !== 'jam_pembelajaran' }" class="px-6 py-5 font-black text-sm uppercase tracking-wider transition-all border-b-2 flex items-center gap-2 backdrop-blur-sm focus:outline-none">
                        <i class="ph-bold ph-bell-ringing text-lg"></i>
                        Jadwal Bel & Pelajaran
                    </button>
                </div>

                {{-- TAB 1: JAM SEKOLAH & LIBUR --}}
                <div x-show="activeTab === 'jam_sekolah'" x-cloak class="p-6 sm:p-10 relative z-10 animate-enter">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
                        
                        {{-- LEFT COLUMN: JAM REGULER --}}
                        <div class="space-y-6">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-12 h-12 rounded-2xl bg-elevate-soft text-elevate-primary flex items-center justify-center border border-elevate-accent/30 shadow-sm">
                                    <i class="ph-duotone ph-clock text-2xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-xl font-black text-elevate-dark">Jam Reguler</h3>
                                    <p class="text-xs font-semibold text-slate-500 mt-0.5">Batas waktu absensi harian</p>
                                </div>
                            </div>
                            
                            <form action="{{ route('schedules.regular.store') }}" method="POST" class="bg-slate-50 p-6 rounded-[2rem] border border-slate-200 shadow-sm">
                                @csrf
                                <div class="space-y-6">
                                    {{-- Row 1: Hari Biasa --}}
                                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden group hover:border-elevate-accent transition-colors">
                                        <div class="absolute top-0 left-0 w-1 h-full bg-elevate-primary"></div>
                                        <h4 class="font-black text-sm text-elevate-dark mb-4 flex items-center gap-2 pl-2">
                                            <i class="ph-fill ph-calendar-blank text-elevate-primary"></i> Hari Biasa (Senin - Kamis)
                                        </h4>
                                        <input type="hidden" name="day_type[]" value="Biasa">
                                        
                                        <div class="grid grid-cols-1 gap-4">
                                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Scan Datang</label>
                                                <div class="flex items-center gap-2">
                                                    <input type="time" name="start_in[]" value="{{ old('start_in.0', $regularSchedules['Biasa']->start_in ?? '') }}" required class="w-full text-xs font-bold rounded-lg border-slate-200 focus:ring-elevate-accent py-2.5 bg-white shadow-sm">
                                                    <span class="text-slate-400 font-bold shrink-0">-</span>
                                                    <input type="time" name="end_in[]" value="{{ old('end_in.0', $regularSchedules['Biasa']->end_in ?? '') }}" required class="w-full text-xs font-bold rounded-lg border-slate-200 focus:ring-elevate-accent py-2.5 bg-white shadow-sm">
                                                </div>
                                            </div>
                                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Scan Pulang</label>
                                                <div class="flex items-center gap-2">
                                                    <input type="time" name="start_out[]" value="{{ old('start_out.0', $regularSchedules['Biasa']->start_out ?? '') }}" required class="w-full text-xs font-bold rounded-lg border-slate-200 focus:ring-elevate-accent py-2.5 bg-white shadow-sm">
                                                    <span class="text-slate-400 font-bold shrink-0">-</span>
                                                    <input type="time" name="end_out[]" value="{{ old('end_out.0', $regularSchedules['Biasa']->end_out ?? '') }}" required class="w-full text-xs font-bold rounded-lg border-slate-200 focus:ring-elevate-accent py-2.5 bg-white shadow-sm">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Row 2: Hari Jumat --}}
                                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative overflow-hidden group hover:border-emerald-400 transition-colors">
                                        <div class="absolute top-0 left-0 w-1 h-full bg-emerald-500"></div>
                                        <h4 class="font-black text-sm text-elevate-dark mb-4 flex items-center gap-2 pl-2">
                                            <i class="ph-fill ph-mosque text-emerald-500"></i> Hari Jumat
                                        </h4>
                                        <input type="hidden" name="day_type[]" value="Jumat">
                                        
                                        <div class="grid grid-cols-1 gap-4">
                                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Scan Datang</label>
                                                <div class="flex items-center gap-2">
                                                    <input type="time" name="start_in[]" value="{{ old('start_in.1', $regularSchedules['Jumat']->start_in ?? '') }}" required class="w-full text-xs font-bold rounded-lg border-slate-200 focus:ring-emerald-500 py-2.5 bg-white shadow-sm">
                                                    <span class="text-slate-400 font-bold shrink-0">-</span>
                                                    <input type="time" name="end_in[]" value="{{ old('end_in.1', $regularSchedules['Jumat']->end_in ?? '') }}" required class="w-full text-xs font-bold rounded-lg border-slate-200 focus:ring-emerald-500 py-2.5 bg-white shadow-sm">
                                                </div>
                                            </div>
                                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Scan Pulang</label>
                                                <div class="flex items-center gap-2">
                                                    <input type="time" name="start_out[]" value="{{ old('start_out.1', $regularSchedules['Jumat']->start_out ?? '') }}" required class="w-full text-xs font-bold rounded-lg border-slate-200 focus:ring-emerald-500 py-2.5 bg-white shadow-sm">
                                                    <span class="text-slate-400 font-bold shrink-0">-</span>
                                                    <input type="time" name="end_out[]" value="{{ old('end_out.1', $regularSchedules['Jumat']->end_out ?? '') }}" required class="w-full text-xs font-bold rounded-lg border-slate-200 focus:ring-emerald-500 py-2.5 bg-white shadow-sm">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-6">
                                    <button type="submit" class="w-full bg-elevate-dark hover:bg-elevate-primary text-white font-bold py-3.5 rounded-xl transition-all shadow-lg shadow-elevate-dark/20 active:scale-95 flex justify-center items-center gap-2 text-sm">
                                        <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan Jam Reguler
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- RIGHT COLUMN: JADWAL KHUSUS / LIBUR --}}
                        <div class="space-y-6">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center border border-rose-200 shadow-sm">
                                        <i class="ph-duotone ph-calendar-x text-2xl"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-black text-elevate-dark">Jadwal Khusus & Libur</h3>
                                        <p class="text-xs font-semibold text-slate-500 mt-0.5">Penyesuaian jam di hari tertentu</p>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Form Tambah Libur --}}
                            <form action="{{ route('schedules.special.store') }}" method="POST" class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm" x-data="{ isHoliday: true }">
                                @csrf
                                <div class="space-y-5">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-[10px] font-bold text-elevate-primary uppercase mb-2 ml-1">Tanggal</label>
                                            <input type="date" name="date" value="{{ old('date') }}" required class="w-full rounded-xl border-slate-200 focus:ring-elevate-accent text-sm font-bold bg-slate-50 py-3 px-4">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-elevate-primary uppercase mb-2 ml-1">Keterangan / Acara</label>
                                            <input type="text" name="description" value="{{ old('description') }}" placeholder="Cth: Hari Pahlawan" class="w-full rounded-xl border-slate-200 focus:ring-elevate-accent text-sm font-bold bg-slate-50 py-3 px-4">
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center gap-3 bg-slate-50 p-3 rounded-xl border border-slate-100">
                                        <input type="checkbox" name="is_holiday" id="is_holiday" x-model="isHoliday" class="w-5 h-5 text-rose-500 border-slate-300 rounded focus:ring-rose-500 cursor-pointer">
                                        <label for="is_holiday" class="text-sm font-bold text-slate-700 cursor-pointer select-none">Tandai Sebagai Hari Libur (Sekolah Tutup)</label>
                                    </div>

                                    <div x-show="!isHoliday" x-collapse>
                                        <div class="p-4 sm:p-5 bg-slate-50 border border-slate-200 rounded-xl mt-2 grid grid-cols-1 gap-4 relative overflow-hidden">
                                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-elevate-primary"></div>
                                            
                                            <div class="bg-white p-3.5 rounded-xl border border-slate-100 shadow-sm">
                                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Scan Datang Khusus</label>
                                                <div class="flex items-center gap-2">
                                                    <input type="time" name="start_in" class="w-full text-xs font-bold rounded-lg border-slate-200 focus:ring-elevate-accent py-2.5">
                                                    <span class="text-slate-400 font-bold shrink-0">-</span>
                                                    <input type="time" name="end_in" class="w-full text-xs font-bold rounded-lg border-slate-200 focus:ring-elevate-accent py-2.5">
                                                </div>
                                            </div>
                                            
                                            <div class="bg-white p-3.5 rounded-xl border border-slate-100 shadow-sm">
                                                <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2">Scan Pulang Khusus</label>
                                                <div class="flex items-center gap-2">
                                                    <input type="time" name="start_out" class="w-full text-xs font-bold rounded-lg border-slate-200 focus:ring-elevate-accent py-2.5">
                                                    <span class="text-slate-400 font-bold shrink-0">-</span>
                                                    <input type="time" name="end_out" class="w-full text-xs font-bold rounded-lg border-slate-200 focus:ring-elevate-accent py-2.5">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-5 text-right">
                                    <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-elevate-primary hover:bg-elevate-dark text-white font-bold rounded-xl transition-all shadow-md active:scale-95 text-sm inline-flex justify-center items-center gap-2">
                                        <i class="ph-bold ph-plus"></i> Tambahkan Jadwal
                                    </button>
                                </div>
                            </form>

                            {{-- Tabel Daftar Libur --}}
                            <div class="bg-white rounded-[2rem] border border-slate-200 overflow-hidden shadow-sm">
                                <div class="overflow-x-auto overflow-y-auto max-h-[300px] custom-scrollbar">
                                    <table class="w-full text-left text-sm relative">
                                        <thead class="bg-slate-50/90 backdrop-blur-sm text-xs font-bold text-elevate-primary uppercase border-b border-slate-100 sticky top-0 z-10">
                                            <tr>
                                                <th class="px-5 py-3">Tanggal</th>
                                                <th class="px-5 py-3">Keterangan</th>
                                                <th class="px-5 py-3 text-center">Tipe</th>
                                                <th class="px-5 py-3 text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-50">
                                            @forelse($specialSchedules ?? [] as $ss)
                                            <tr class="hover:bg-slate-50/50 transition-colors">
                                                <td class="px-5 py-3 font-bold text-slate-700 whitespace-nowrap">
                                                    {{ \Carbon\Carbon::parse($ss->date)->locale('id')->translatedFormat('d M Y') }}
                                                </td>
                                                <td class="px-5 py-3 text-xs font-semibold text-slate-600">
                                                    {{ $ss->description ?? '-' }}
                                                </td>
                                                <td class="px-5 py-3 text-center">
                                                    @if($ss->is_holiday)
                                                        <span class="inline-flex px-2 py-1 bg-rose-50 text-rose-600 rounded-md text-[10px] font-bold border border-rose-200">Libur</span>
                                                    @else
                                                        <span class="inline-flex px-2 py-1 bg-elevate-soft text-elevate-primary rounded-md text-[10px] font-bold border border-elevate-accent/30">Khusus</span>
                                                    @endif
                                                </td>
                                                <td class="px-5 py-3 text-center">
                                                    <form id="delete-form-{{ $ss->id }}" action="{{ route('schedules.special.destroy', $ss->id) }}" method="POST">
                                                        @csrf @method('DELETE')
                                                        <button type="button" onclick="confirmDelete('delete-form-{{ $ss->id }}', 'Yakin ingin menghapus jadwal tanggal ini?')" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white transition-all">
                                                            <i class="ph-bold ph-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="px-5 py-8 text-center text-slate-400 font-bold text-sm bg-slate-50/50">Belum ada jadwal khusus / hari libur.</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TAB 2: JAM PEMBELAJARAN (BEL) --}}
                <div x-show="activeTab === 'jam_pembelajaran'" x-cloak class="p-6 sm:p-10 relative z-10 animate-enter">
                    <div class="max-w-3xl mx-auto space-y-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center border border-emerald-200 shadow-sm">
                                <i class="ph-duotone ph-speaker-hifi text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-elevate-dark">Jadwal Bel & Pelajaran</h3>
                                <p class="text-xs font-semibold text-slate-500 mt-0.5">Atur waktu dan upload audio untuk memicu notifikasi otomatis di layar Kiosk/Scanner.</p>
                            </div>
                        </div>
                        
                        {{-- Form Input Bel (DITAMBAHKAN ENCTYPE UNTUK UPLOAD FILE) --}}
                        <form action="{{ route('schedules.learning.store') }}" method="POST" enctype="multipart/form-data" class="bg-slate-50 p-6 rounded-[2rem] border border-slate-200 shadow-sm">
                            @csrf
                            <div class="grid grid-cols-1 gap-4">
                                <div class="bg-white p-4 rounded-xl border border-slate-100 shadow-sm flex flex-col md:flex-row items-start md:items-end gap-4">
                                    <div class="w-full md:flex-1">
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2 ml-1">Nama Kegiatan</label>
                                        <input type="text" name="activity_name" placeholder="Cth: Jam ke-1" required class="w-full text-sm font-bold rounded-lg border-slate-200 focus:ring-elevate-accent py-2.5">
                                    </div>
                                    <div class="w-full md:w-32 shrink-0">
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2 ml-1">Waktu</label>
                                        <input type="time" name="trigger_time" required class="w-full text-sm font-bold rounded-lg border-slate-200 focus:ring-elevate-accent py-2.5">
                                    </div>
                                    <div class="w-full md:w-1/3 shrink-0">
                                        <label class="block text-[10px] font-bold text-slate-500 uppercase mb-2 ml-1">File Audio (Opsional)</label>
                                        <input type="file" name="audio_file" accept=".mp3,.wav" class="w-full text-xs font-bold rounded-lg border border-slate-200 focus:ring-elevate-accent p-2 bg-slate-50 file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-bold file:bg-elevate-primary file:text-white hover:file:bg-elevate-dark">
                                    </div>
                                    <button type="submit" class="w-full md:w-auto bg-elevate-primary hover:bg-elevate-dark text-white px-6 py-2.5 rounded-lg font-bold transition-all shadow-md active:scale-95 flex justify-center items-center gap-2 h-[42px] shrink-0">
                                        <i class="ph-bold ph-plus"></i> Tambah
                                    </button>
                                </div>
                                <p class="text-[10px] text-slate-400 font-semibold ml-2">*Biarkan file audio kosong jika ingin menggunakan suara bel standar.</p>
                            </div>
                        </form>

                        {{-- Tabel Daftar Bel --}}
                        <div class="bg-white rounded-[2rem] border border-slate-200 overflow-hidden shadow-sm mt-6">
                            <div class="overflow-x-auto overflow-y-auto max-h-[400px] custom-scrollbar">
                                <table class="w-full text-left text-sm relative">
                                    <thead class="bg-slate-50/90 backdrop-blur-sm text-xs font-bold text-elevate-primary uppercase border-b border-slate-100 sticky top-0 z-10">
                                        <tr>
                                            <th class="px-5 py-3">Waktu</th>
                                            <th class="px-5 py-3">Nama Kegiatan</th>
                                            <th class="px-5 py-3">Suara Bel</th>
                                            <th class="px-5 py-3 text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-50">
                                        @forelse($learningSchedules ?? [] as $ls)
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="px-5 py-3 font-bold text-slate-700 whitespace-nowrap">
                                                <i class="ph-fill ph-clock text-elevate-primary mr-1"></i>
                                                {{ \Carbon\Carbon::parse($ls->trigger_time)->format('H:i') }}
                                            </td>
                                            <td class="px-5 py-3 text-xs font-semibold text-slate-600">
                                                {{ $ls->activity_name }}
                                            </td>
                                            <td class="px-5 py-3">
                                                @if(isset($ls->audio_file) && $ls->audio_file)
                                                    <audio controls class="h-8 w-40 rounded-full">
                                                        <source src="{{ asset('storage/' . $ls->audio_file) }}" type="audio/mpeg">
                                                    </audio>
                                                @else
                                                    <span class="text-[10px] font-bold bg-slate-100 text-slate-500 px-2 py-1 rounded-md border border-slate-200">Default Sound</span>
                                                @endif
                                            </td>
                                            <td class="px-5 py-3 text-center">
                                                <form id="delete-bel-{{ $ls->id }}" action="{{ route('schedules.learning.destroy', $ls->id) }}" method="POST">
                                                    @csrf @method('DELETE')
                                                    <button type="button" onclick="confirmDelete('delete-bel-{{ $ls->id }}', 'Yakin ingin menghapus jadwal bel ini?')" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white transition-all">
                                                        <i class="ph-bold ph-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="3" class="px-5 py-8 text-center text-slate-400 font-bold text-sm bg-slate-50/50">Belum ada jadwal bel pembelajaran yang diatur.</td>
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
    </div>

    {{-- SweetAlert2 Library --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
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
                    if (form) form.submit();
                }
            });
        }
    </script>
</x-app-layout>