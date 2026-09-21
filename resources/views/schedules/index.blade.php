<x-app-layout>
    {{-- Tambahan CSS khusus untuk mencegah input time bawaan browser memakan tempat --}}
    <style>
        input[type="time"]::-webkit-calendar-picker-indicator {
            margin-left: 2px;
            padding: 0;
            cursor: pointer;
            filter: invert(1);
        }
        input[type="time"]::-webkit-datetime-edit {
            padding: 0;
        }
        input[type="time"]::-webkit-datetime-edit-fields-wrapper {
            padding: 0;
        }
        /* Memastikan tabel bisa di-scroll dengan mulus di HP */
        .table-responsive-wrapper {
            -webkit-overflow-scrolling: touch;
        }
    </style>

    <div class="py-6 sm:py-10 font-sans text-slate-100 bg-[#020b18] relative overflow-hidden min-h-screen">
        
        {{-- Efek Latar Belakang Halus --}}
        <div class="bg-gradient-to-b from-[#0d52a1]/20 via-[#031d3d]/10 to-transparent opacity-30 pointer-events-none -z-10 blur-3xl absolute inset-0"></div>

        {{-- HERO SECTION --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 sm:mb-10 relative z-10">
            <x-hero-section
                badge="KONFIGURASI OPERASIONAL"
                badgeIcon="ph-fill ph-gear"
                showcaseIcon="ph-duotone ph-calendar-plus"
                showcaseTitle="Jadwal & Kalender"
                showcaseSubtitle="Jam Masuk & Pulang">
                <x-slot:title>
                    <span class="block text-slate-100">Jam Operasional &</span>
                    <span class="block mt-1 sm:mt-1.5 text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] via-sky-200 to-[#38bdf8]">
                        Jadwal Khusus
                    </span>
                </x-slot:title>
                <x-slot:description>
                    Pusat pengaturan jam operasional sekolah harian (reguler) serta kalender agenda kegiatan khusus.
                </x-slot:description>
                <x-slot:chips>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-white/10 text-slate-300 text-xs font-semibold">
                        <i class="ph-bold ph-clock text-sky-400"></i> Jam Masuk & Pulang
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-white/10 text-slate-300 text-xs font-semibold">
                        <i class="ph-bold ph-calendar-star text-amber-400"></i> Agenda Khusus
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-white/10 text-slate-300 text-xs font-semibold">
                        <i class="ph-bold ph-bell text-emerald-400"></i> Toleransi Presensi
                    </span>
                </x-slot:chips>
                <x-slot:showcaseStats>
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-2xl bg-slate-900/80 border border-white/10 backdrop-blur-md">
                        <i class="ph-fill ph-calendar-plus text-amber-400 text-sm"></i>
                        <span class="text-xs font-bold text-slate-300">Agenda Khusus:</span>
                        <span class="text-sm font-black text-white font-mono">{{ $specialSchedules->count() }}</span>
                    </div>
                </x-slot:showcaseStats>
            </x-hero-section>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            {{-- Pesan Flash --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 sm:mb-8 p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 rounded-2xl flex items-center justify-between shadow-xl backdrop-blur-md">
                    <div class="flex items-center gap-3 px-2">
                        <div class="p-2 bg-emerald-500/20 rounded-full text-emerald-400 border border-emerald-500/30 shrink-0">
                            <i class="ph-bold ph-check-circle text-xl"></i>
                        </div>
                        <span class="font-bold text-xs sm:text-sm">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-200 p-2 rounded-xl hover:bg-emerald-500/20 transition shrink-0"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 sm:mb-8 p-4 bg-rose-500/10 border border-rose-500/30 text-rose-200 rounded-2xl flex items-start gap-3 shadow-xl backdrop-blur-md">
                    <div class="p-2 bg-rose-500/20 rounded-full text-rose-400 border border-rose-500/30 shrink-0 ml-1 mt-0.5">
                        <i class="ph-bold ph-warning-circle text-xl"></i>
                    </div>
                    <div>
                        <p class="font-bold text-sm mb-1 text-rose-300">Periksa inputan anda:</p>
                        <ul class="list-disc list-inside text-xs font-medium text-rose-200/90">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- LAYOUT UTAMA: KIRI (Reguler) & KANAN (Khusus) -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-start mb-10">

                <!-- BAGIAN KIRI: JADWAL REGULER -->
                <div class="lg:col-span-7 bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-5 sm:p-6 md:p-8 rounded-[2rem] sm:rounded-[2.5rem] shadow-2xl border border-white/10 backdrop-blur-xl relative overflow-hidden group">
                    {{-- Aksen Header --}}
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-[#0d52a1] via-[#56bbf1] to-[#0d52a1]"></div>

                    <div class="flex items-center gap-4 mb-6 sm:mb-8">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-[#0d52a1]/20 text-sky-400 rounded-2xl flex items-center justify-center text-2xl sm:text-3xl shadow-sm border border-sky-400/30 group-hover:scale-110 transition-transform shrink-0">
                            <i class="ph-duotone ph-clock"></i>
                        </div>
                        <div>
                            <h2 class="text-lg sm:text-xl font-black text-white">Jadwal Sekolah Reguler</h2>
                            <p class="text-xs sm:text-sm text-slate-400 font-medium mt-1">Pengaturan jam masuk dan pulang mingguan.</p>
                        </div>
                    </div>

                    <form action="{{ route('schedules.regular.store') }}" method="POST">
                        @csrf
                        <div class="space-y-6">
                            
                            <!-- Jadwal Hari Biasa (Senin-Kamis) -->
                            <div class="bg-slate-900/80 rounded-[1.5rem] sm:rounded-[2rem] p-4 sm:p-5 md:p-6 border border-white/10 relative hover:border-white/20 transition-all duration-300">
                                <div class="flex items-center gap-3 mb-5 sm:mb-6">
                                    <div class="w-2 h-6 sm:h-8 bg-sky-500 rounded-full"></div>
                                    <h4 class="font-black text-white text-base sm:text-lg">Senin - Kamis</h4>
                                </div>
                                <input type="hidden" name="day_type[]" value="Biasa">
                                
                                <div class="flex flex-col xl:flex-row gap-4 sm:gap-5">
                                    
                                    {{-- KELOMPOK MASUK --}}
                                    <div class="flex-1 bg-slate-950/60 p-3 sm:p-3.5 rounded-2xl sm:rounded-[1.5rem] border border-white/10 shadow-sm">
                                        <div class="text-center mb-3">
                                            <span class="text-[10px] font-bold text-sky-300 uppercase tracking-wider flex justify-center items-center gap-1">
                                                <i class="ph-bold ph-sun-horizon"></i> MASUK
                                            </span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2 sm:gap-3">
                                            <div class="flex flex-col items-center w-full">
                                                <input type="time" name="start_in[]" value="{{ isset($regularSchedules['Biasa']->start_in) ? \Carbon\Carbon::parse($regularSchedules['Biasa']->start_in)->format('H:i') : '05:30' }}" 
                                                    class="w-full text-center font-bold text-xs sm:text-sm text-white bg-slate-900 border border-white/10 rounded-xl py-2 px-1 focus:ring-2 focus:ring-sky-400/20 focus:border-sky-400 outline-none transition-all cursor-pointer [color-scheme:dark]">
                                                <span class="text-[8px] font-bold text-slate-400 uppercase mt-1.5 tracking-wider text-center line-clamp-1">Buka Scan</span>
                                            </div>
                                            <div class="flex flex-col items-center w-full">
                                                <input type="time" name="end_in[]" value="{{ isset($regularSchedules['Biasa']->end_in) ? \Carbon\Carbon::parse($regularSchedules['Biasa']->end_in)->format('H:i') : '07:00' }}" 
                                                    class="w-full text-center font-bold text-xs sm:text-sm text-white bg-slate-900 border border-white/10 rounded-xl py-2 px-1 focus:ring-2 focus:ring-sky-400/20 focus:border-sky-400 outline-none transition-all cursor-pointer [color-scheme:dark]">
                                                <span class="text-[8px] font-bold text-slate-400 uppercase mt-1.5 tracking-wider text-center line-clamp-1">Batas Telat</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- PEMBATAS --}}
                                    <div class="hidden xl:flex items-center justify-center">
                                        <div class="w-px h-12 bg-white/10"></div>
                                    </div>

                                    {{-- KELOMPOK PULANG --}}
                                    <div class="flex-1 bg-slate-950/60 p-3 sm:p-3.5 rounded-2xl sm:rounded-[1.5rem] border border-white/10 shadow-sm">
                                        <div class="text-center mb-3">
                                            <span class="text-[10px] font-bold text-sky-300 uppercase tracking-wider flex justify-center items-center gap-1">
                                                <i class="ph-bold ph-moon-stars"></i> PULANG
                                            </span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2 sm:gap-3">
                                            <div class="flex flex-col items-center w-full">
                                                <input type="time" name="start_out[]" value="{{ isset($regularSchedules['Biasa']->start_out) ? \Carbon\Carbon::parse($regularSchedules['Biasa']->start_out)->format('H:i') : '14:00' }}" 
                                                    class="w-full text-center font-bold text-xs sm:text-sm text-white bg-slate-900 border border-white/10 rounded-xl py-2 px-1 focus:ring-2 focus:ring-sky-400/20 focus:border-sky-400 outline-none transition-all cursor-pointer [color-scheme:dark]">
                                                <span class="text-[8px] font-bold text-slate-400 uppercase mt-1.5 tracking-wider text-center line-clamp-1">Boleh Plg</span>
                                            </div>
                                            <div class="flex flex-col items-center w-full">
                                                <input type="time" name="end_out[]" value="{{ isset($regularSchedules['Biasa']->end_out) ? \Carbon\Carbon::parse($regularSchedules['Biasa']->end_out)->format('H:i') : '15:00' }}" 
                                                    class="w-full text-center font-bold text-xs sm:text-sm text-white bg-slate-900 border border-white/10 rounded-xl py-2 px-1 focus:ring-2 focus:ring-sky-400/20 focus:border-sky-400 outline-none transition-all cursor-pointer [color-scheme:dark]">
                                                <span class="text-[8px] font-bold text-slate-400 uppercase mt-1.5 tracking-wider text-center line-clamp-1">Tutup Scan</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            
                            <!-- Jadwal Hari Jum'at -->
                            <div class="bg-slate-900/80 rounded-[1.5rem] sm:rounded-[2rem] p-4 sm:p-5 md:p-6 border border-white/10 relative hover:border-white/20 transition-all duration-300">
                                <div class="flex items-center gap-3 mb-5 sm:mb-6">
                                    <div class="w-2 h-6 sm:h-8 bg-amber-400 rounded-full"></div>
                                    <h4 class="font-black text-white text-base sm:text-lg">Hari Jum'at</h4>
                                </div>
                                <input type="hidden" name="day_type[]" value="Jumat">
                                
                                <div class="flex flex-col xl:flex-row gap-4 sm:gap-5">
                                    
                                    {{-- KELOMPOK MASUK --}}
                                    <div class="flex-1 bg-slate-950/60 p-3 sm:p-3.5 rounded-2xl sm:rounded-[1.5rem] border border-white/10 shadow-sm">
                                        <div class="text-center mb-3">
                                            <span class="text-[10px] font-bold text-amber-300 uppercase tracking-wider flex justify-center items-center gap-1">
                                                <i class="ph-bold ph-sun-horizon"></i> MASUK
                                            </span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2 sm:gap-3">
                                            <div class="flex flex-col items-center w-full">
                                                <input type="time" name="start_in[]" value="{{ isset($regularSchedules['Jumat']->start_in) ? \Carbon\Carbon::parse($regularSchedules['Jumat']->start_in)->format('H:i') : '05:30' }}" 
                                                    class="w-full text-center font-bold text-xs sm:text-sm text-white bg-slate-900 border border-white/10 rounded-xl py-2 px-1 focus:ring-2 focus:ring-amber-400/20 focus:border-amber-400 outline-none transition-all cursor-pointer [color-scheme:dark]">
                                                <span class="text-[8px] font-bold text-slate-400 uppercase mt-1.5 tracking-wider text-center line-clamp-1">Buka Scan</span>
                                            </div>
                                            <div class="flex flex-col items-center w-full">
                                                <input type="time" name="end_in[]" value="{{ isset($regularSchedules['Jumat']->end_in) ? \Carbon\Carbon::parse($regularSchedules['Jumat']->end_in)->format('H:i') : '07:00' }}" 
                                                    class="w-full text-center font-bold text-xs sm:text-sm text-white bg-slate-900 border border-white/10 rounded-xl py-2 px-1 focus:ring-2 focus:ring-amber-400/20 focus:border-amber-400 outline-none transition-all cursor-pointer [color-scheme:dark]">
                                                <span class="text-[8px] font-bold text-slate-400 uppercase mt-1.5 tracking-wider text-center line-clamp-1">Batas Telat</span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- PEMBATAS --}}
                                    <div class="hidden xl:flex items-center justify-center">
                                        <div class="w-px h-12 bg-white/10"></div>
                                    </div>

                                    {{-- KELOMPOK PULANG --}}
                                    <div class="flex-1 bg-slate-950/60 p-3 sm:p-3.5 rounded-2xl sm:rounded-[1.5rem] border border-white/10 shadow-sm">
                                        <div class="text-center mb-3">
                                            <span class="text-[10px] font-bold text-amber-300 uppercase tracking-wider flex justify-center items-center gap-1">
                                                <i class="ph-bold ph-moon-stars"></i> PULANG
                                            </span>
                                        </div>
                                        <div class="grid grid-cols-2 gap-2 sm:gap-3">
                                            <div class="flex flex-col items-center w-full">
                                                <input type="time" name="start_out[]" value="{{ isset($regularSchedules['Jumat']->start_out) ? \Carbon\Carbon::parse($regularSchedules['Jumat']->start_out)->format('H:i') : '10:45' }}" 
                                                    class="w-full text-center font-bold text-xs sm:text-sm text-white bg-slate-900 border border-white/10 rounded-xl py-2 px-1 focus:ring-2 focus:ring-amber-400/20 focus:border-amber-400 outline-none transition-all cursor-pointer [color-scheme:dark]">
                                                <span class="text-[8px] font-bold text-slate-400 uppercase mt-1.5 tracking-wider text-center line-clamp-1">Boleh Plg</span>
                                            </div>
                                            <div class="flex flex-col items-center w-full">
                                                <input type="time" name="end_out[]" value="{{ isset($regularSchedules['Jumat']->end_out) ? \Carbon\Carbon::parse($regularSchedules['Jumat']->end_out)->format('H:i') : '15:00' }}" 
                                                    class="w-full text-center font-bold text-xs sm:text-sm text-white bg-slate-900 border border-white/10 rounded-xl py-2 px-1 focus:ring-2 focus:ring-amber-400/20 focus:border-amber-400 outline-none transition-all cursor-pointer [color-scheme:dark]">
                                                <span class="text-[8px] font-bold text-slate-400 uppercase mt-1.5 tracking-wider text-center line-clamp-1">Tutup Scan</span>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="mt-6 sm:mt-8 flex justify-end pt-2">
                            <button type="submit" class="w-full sm:w-auto py-3.5 px-6 sm:px-8 bg-gradient-to-r from-[#0d52a1] via-[#0d52a1] to-sky-600 hover:from-sky-600 hover:to-[#0d52a1] text-white font-bold rounded-2xl transition-all shadow-xl shadow-sky-950/40 flex items-center justify-center gap-2 transform active:scale-95 border border-sky-400/30 text-sm sm:text-base">
                                <i class="ph-bold ph-floppy-disk text-xl"></i>
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- BAGIAN KANAN: FORM INPUT KHUSUS -->
                <div class="lg:col-span-5">
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-5 sm:p-6 md:p-8 rounded-[2rem] sm:rounded-[2.5rem] shadow-2xl border border-white/10 backdrop-blur-xl relative overflow-hidden group/form" x-data="{ isHoliday: false }">
                        {{-- Aksen Header --}}
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-amber-400 to-amber-600"></div>

                        <div class="flex items-center gap-4 mb-6 sm:mb-8">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-amber-500/10 text-amber-400 rounded-2xl flex items-center justify-center text-2xl sm:text-3xl shadow-sm border border-amber-400/30 group-hover/form:scale-110 transition-transform shrink-0">
                                <i class="ph-duotone ph-calendar-plus"></i>
                            </div>
                            <div>
                                <h3 class="text-lg sm:text-xl font-black text-white">Agenda Baru</h3>
                                <p class="text-xs sm:text-sm text-slate-400 font-medium mt-1">Tambahkan hari libur/khusus.</p>
                            </div>
                        </div>

                        <form action="{{ route('schedules.special.store') }}" method="POST">
                            @csrf
                            <div class="space-y-5 sm:space-y-6">
                                {{-- Tanggal --}}
                                <div>
                                    <label class="block text-xs font-bold text-sky-300 uppercase tracking-wider mb-2 ml-1">Tanggal</label>
                                    <div class="relative group">
                                        <i class="ph-bold ph-calendar-blank absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-sky-400 transition-colors"></i>
                                        <input type="date" name="date" required 
                                               class="w-full pl-11 pr-4 py-3.5 text-sm sm:text-base rounded-2xl border-white/10 bg-slate-900/80 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 font-bold text-white transition-all shadow-sm cursor-pointer outline-none [color-scheme:dark]">
                                    </div>
                                </div>

                                {{-- Keterangan --}}
                                <div>
                                    <label class="block text-xs font-bold text-sky-300 uppercase tracking-wider mb-2 ml-1">Keterangan</label>
                                    <div class="relative group">
                                        <i class="ph-bold ph-text-t absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-sky-400 transition-colors"></i>
                                        <input type="text" name="description" placeholder="Contoh: Rapat Guru" required
                                               class="w-full pl-11 pr-4 py-3.5 text-sm sm:text-base rounded-2xl border-white/10 bg-slate-900/80 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 font-bold text-white transition-all shadow-sm placeholder:font-normal placeholder:text-slate-500 outline-none">
                                    </div>
                                </div>
                                
                                <!-- Toggle Hari Libur -->
                                <div class="bg-rose-500/10 p-3 sm:p-4 rounded-2xl border border-rose-500/30 flex items-center justify-between cursor-pointer hover:bg-rose-500/20 transition-colors select-none shadow-sm" @click="isHoliday = !isHoliday">
                                    <div class="flex items-center gap-3">
                                        <div class="relative flex items-center shrink-0">
                                            <input type="checkbox" name="is_holiday" value="1" class="peer sr-only" x-model="isHoliday">
                                            <div class="w-10 h-6 bg-slate-700 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-600 shadow-inner"></div>
                                        </div>
                                        <span class="block text-xs sm:text-sm font-black text-rose-300 line-clamp-1">Set Sebagai Libur</span>
                                    </div>
                                    <i class="ph-duotone ph-coffee text-rose-400 text-xl sm:text-2xl mr-1 shrink-0"></i>
                                </div>

                                <!-- Input Jam Operasional Opsional -->
                                <div x-show="!isHoliday" 
                                     x-transition:enter="transition ease-out duration-300" 
                                     x-transition:enter-start="opacity-0 -translate-y-2" 
                                     x-transition:enter-end="opacity-100 translate-y-0" 
                                     class="bg-slate-900/80 p-4 sm:p-5 rounded-2xl border border-white/10">
                                    <p class="text-[10px] font-black text-sky-400 uppercase text-center mb-4">Jam Operasional (Opsional)</p>
                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                        <div class="flex flex-col items-center w-full">
                                            <input type="time" name="start_in" class="w-full text-xs sm:text-sm text-center font-bold rounded-xl border-white/10 bg-slate-950/80 text-white py-2.5 px-1 focus:ring-2 focus:ring-sky-400/20 focus:border-sky-400 outline-none cursor-pointer [color-scheme:dark]">
                                            <span class="text-[8px] font-bold text-slate-400 uppercase mt-1.5 text-center line-clamp-1">Buka Masuk</span>
                                        </div>
                                        <div class="flex flex-col items-center w-full">
                                            <input type="time" name="end_in" class="w-full text-xs sm:text-sm text-center font-bold rounded-xl border-white/10 bg-slate-950/80 text-white py-2.5 px-1 focus:ring-2 focus:ring-sky-400/20 focus:border-sky-400 outline-none cursor-pointer [color-scheme:dark]">
                                            <span class="text-[8px] font-bold text-slate-400 uppercase mt-1.5 text-center line-clamp-1">Batas Telat</span>
                                        </div>
                                        <div class="flex flex-col items-center w-full">
                                            <input type="time" name="start_out" class="w-full text-xs sm:text-sm text-center font-bold rounded-xl border-white/10 bg-slate-950/80 text-white py-2.5 px-1 focus:ring-2 focus:ring-sky-400/20 focus:border-sky-400 outline-none cursor-pointer [color-scheme:dark]">
                                            <span class="text-[8px] font-bold text-slate-400 uppercase mt-1.5 text-center line-clamp-1">Boleh Plg</span>
                                        </div>
                                        <div class="flex flex-col items-center w-full">
                                            <input type="time" name="end_out" class="w-full text-xs sm:text-sm text-center font-bold rounded-xl border-white/10 bg-slate-950/80 text-white py-2.5 px-1 focus:ring-2 focus:ring-sky-400/20 focus:border-sky-400 outline-none cursor-pointer [color-scheme:dark]">
                                            <span class="text-[8px] font-bold text-slate-400 uppercase mt-1.5 text-center line-clamp-1">Tutup Scan</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <button type="submit" class="mt-6 sm:mt-8 w-full py-3.5 sm:py-4 px-6 bg-gradient-to-r from-[#0d52a1] via-[#0d52a1] to-sky-600 hover:from-sky-600 hover:to-[#0d52a1] text-white font-bold rounded-2xl transition-all shadow-xl shadow-sky-950/40 flex items-center justify-center gap-2 transform active:scale-95 border border-sky-400/30 text-sm sm:text-base">
                                <i class="ph-bold ph-plus-circle text-lg"></i>
                                Simpan Agenda
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- BAGIAN BAWAH: TABEL DAFTAR JADWAL KHUSUS -->
            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2rem] sm:rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden flex flex-col relative backdrop-blur-xl">
                {{-- Aksen Header --}}
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-[#0d52a1] via-[#56bbf1] to-[#0d52a1]"></div>

                <div class="p-5 sm:p-6 md:p-8 border-b border-white/10 bg-[#021124]/80 flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 text-white">
                    <h3 class="text-base sm:text-lg font-black flex items-center gap-2">
                        <i class="ph-fill ph-list-dashes text-sky-400"></i> Riwayat Agenda Khusus
                    </h3>
                    <span class="bg-slate-900/80 border border-white/10 text-[10px] font-black px-3.5 py-1.5 rounded-xl text-sky-300 shadow-sm self-start sm:self-auto">
                        {{ $specialSchedules->count() }} Data Tersimpan
                    </span>
                </div>
                
                <div class="overflow-x-auto table-responsive-wrapper w-full">
                    <table class="w-full min-w-[700px] text-left text-sm text-slate-100">
                        <thead class="bg-[#021124]/90 text-xs font-bold text-sky-300 uppercase tracking-wider border-b border-white/10 whitespace-nowrap">
                            <tr>
                                <th class="px-5 sm:px-6 md:px-8 py-4 sm:py-5">Tanggal</th>
                                <th class="px-5 sm:px-6 py-4 sm:py-5 w-1/3">Keterangan</th>
                                <th class="px-5 sm:px-6 py-4 sm:py-5 text-center">Status</th>
                                <th class="px-5 sm:px-6 md:px-8 py-4 sm:py-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse ($specialSchedules as $schedule)
                                <tr class="hover:bg-white/5 transition-colors group">
                                    <td class="px-5 sm:px-6 md:px-8 py-4 sm:py-5 whitespace-nowrap">
                                        <div class="flex items-center gap-3 sm:gap-4">
                                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-slate-900 text-sky-400 flex items-center justify-center text-lg sm:text-xl shadow-sm border border-white/10 shrink-0">
                                                <i class="ph-duotone ph-calendar-blank"></i>
                                            </div>
                                            <div>
                                                <p class="font-black text-white text-sm sm:text-base">{{ \Carbon\Carbon::parse($schedule->date)->translatedFormat('d M Y') }}</p>
                                                <p class="text-[10px] sm:text-xs text-slate-400 font-bold uppercase tracking-wide">{{ \Carbon\Carbon::parse($schedule->date)->translatedFormat('l') }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-5 sm:px-6 py-4 sm:py-5">
                                        <p class="text-xs sm:text-sm font-bold text-white leading-snug">{{ $schedule->description }}</p>
                                        @if(!$schedule->is_holiday)
                                            <div class="inline-flex flex-wrap items-center gap-1.5 mt-2 bg-sky-500/10 px-2.5 py-1 rounded-lg border border-sky-400/30">
                                                <i class="ph-bold ph-clock text-sky-400 text-xs"></i>
                                                <span class="text-[10px] font-mono font-bold text-sky-300 whitespace-nowrap">
                                                    {{ \Carbon\Carbon::parse($schedule->start_in)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_out)->format('H:i') }}
                                                </span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-5 sm:px-6 py-4 sm:py-5 text-center">
                                        @if($schedule->is_holiday)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] sm:text-xs font-black uppercase bg-rose-500/10 text-rose-300 border border-rose-500/30 shadow-sm whitespace-nowrap">
                                                <i class="ph-bold ph-coffee"></i> Libur
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[10px] sm:text-xs font-black uppercase bg-emerald-500/10 text-emerald-300 border border-emerald-500/30 shadow-sm whitespace-nowrap">
                                                <i class="ph-bold ph-info"></i> Khusus
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-5 sm:px-6 md:px-8 py-4 sm:py-5 text-right">
                                        <form action="{{ route('schedules.special.destroy', $schedule->id) }}" method="POST" id="delete-form-{{ $schedule->id }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete('{{ $schedule->id }}')" class="w-8 h-8 sm:w-9 sm:h-9 ml-auto flex items-center justify-center rounded-xl bg-slate-800/80 border border-white/10 text-slate-300 hover:text-rose-300 hover:border-rose-500/30 hover:bg-rose-500/20 transition-all shadow-sm shrink-0" title="Hapus Jadwal">
                                                <i class="ph-bold ph-trash text-base sm:text-lg text-rose-400"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 sm:px-8 py-16 sm:py-20 text-center">
                                        <div class="w-14 h-14 sm:w-16 sm:h-16 bg-sky-500/10 border border-sky-400/20 rounded-full flex items-center justify-center mx-auto mb-4 text-sky-400 shadow-inner">
                                            <i class="ph-duotone ph-calendar-slash text-3xl sm:text-4xl"></i>
                                        </div>
                                        <p class="text-xs sm:text-sm font-bold text-white">Tidak ada jadwal khusus.</p>
                                        <p class="text-[10px] sm:text-xs text-slate-400 mt-1">Tambahkan hari libur atau kegiatan khusus pada form di atas.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    {{-- SweetAlert2 Library --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Jadwal?',
                text: "Yakin ingin menghapus jadwal khusus ini?",
                icon: 'warning',
                showCancelButton: true,
                background: '#021124',
                color: '#fff',
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#475569',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2rem] font-sans border border-white/10 bg-[#021124] text-white shadow-2xl w-[90%] max-w-md',
                    confirmButton: 'bg-rose-600 text-white px-5 sm:px-6 py-2.5 sm:py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-1 sm:mx-2 shadow-lg shadow-rose-950/40 text-sm',
                    cancelButton: 'bg-slate-800 text-slate-300 px-5 sm:px-6 py-2.5 sm:py-3 rounded-xl font-bold hover:bg-slate-700 transition-colors mx-1 sm:mx-2 text-sm border border-white/10'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('delete-form-' + id);
                    if (form) form.submit();
                }
            });
        }
    </script>
</x-app-layout>