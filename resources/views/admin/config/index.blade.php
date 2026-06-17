<x-app-layout>
    {{-- Load SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans text-elevate-text bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO SECTION MICROSOFT ELEVATE THEME --}}
            <div class="relative rounded-[2rem] bg-elevate-gradient-main p-8 mb-8 text-elevate-dark shadow-xl shadow-elevate-accent/10 overflow-hidden border border-white/60">
                <div class="absolute -top-10 -left-10 w-48 h-48 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/20 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>
                <div class="absolute top-10 right-32 w-24 h-24 bg-white/40 rounded-2xl rotate-45 pointer-events-none shadow-sm"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-center md:text-left">
                        <div class="flex items-center justify-center md:justify-start gap-2 mb-2">
                            <span class="text-[10px] font-bold text-elevate-dark/70 uppercase tracking-wider bg-white/50 px-3 py-1 rounded-full border border-white/60 backdrop-blur-sm shadow-sm">Administrasi Sistem</span>
                        </div>
                        <h1 class="text-3xl font-extrabold tracking-tight mb-2 flex items-center justify-center md:justify-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-elevate-accent/20 text-elevate-primary flex items-center justify-center shrink-0">
                                <i class="ph-bold ph-clock-countdown text-xl"></i>
                            </div>
                            Jadwal Mesin Absen
                        </h1>
                        <p class="text-elevate-dark/80 text-sm font-medium leading-relaxed max-w-lg ml-0 md:ml-12">
                            Atur rentang jam operasional untuk perpindahan otomatis pada perangkat Kiosk Absensi Harian, Sholat, dan Makan Siang siswa.
                        </p>
                    </div>

                    {{-- Animasi Dekoratif di Kanan --}}
                    <div class="hidden lg:flex gap-3">
                        <div class="bg-white/60 backdrop-blur-md w-20 h-20 rounded-[2rem] border border-white shadow-sm flex items-center justify-center animate-pulse">
                            <i class="ph-duotone ph-gear text-4xl text-elevate-primary"></i>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Container --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden p-6 sm:p-10">
                
                <form action="{{ route('admin.attendance-config.update') }}" method="POST" class="space-y-8">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                        
                        {{-- 1. KARTU PENGATURAN DHUHA --}}
                        <div class="bg-slate-50/50 rounded-[2rem] border border-slate-100 p-6 shadow-sm hover:shadow-md transition-shadow group">
                            <div class="flex items-center gap-4 border-b border-slate-200/60 pb-5">
                                <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-3xl group-hover:scale-110 group-hover:rotate-6 transition-all shadow-inner">
                                    <i class="ph-fill ph-sun-horizon"></i>
                                </div>
                                <div>
                                    <h4 class="font-black text-elevate-dark text-lg tracking-tight">Sholat Dhuha</h4>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Ibadah Pagi</p>
                                </div>
                            </div>
                            <div class="space-y-5 pt-5">
                                <div>
                                    <label class="text-[11px] font-black text-slate-500 uppercase tracking-wider block mb-2"><i class="ph-bold ph-play-circle text-emerald-500 mr-1"></i> Jam Mulai</label>
                                    <div class="relative">
                                        <input type="time" name="dhuha_start" value="{{ old('dhuha_start', substr($config->dhuha_start, 0, 5)) }}" class="w-full pl-4 pr-10 py-3.5 rounded-2xl border-slate-200 bg-white font-mono font-bold text-slate-800 focus:ring-emerald-500 focus:border-emerald-500 transition-all shadow-sm">
                                        <i class="ph-bold ph-clock absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    </div>
                                    @error('dhuha_start') <p class="text-xs text-rose-500 mt-1.5 font-semibold">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="text-[11px] font-black text-slate-500 uppercase tracking-wider block mb-2"><i class="ph-bold ph-stop-circle text-rose-400 mr-1"></i> Jam Selesai</label>
                                    <div class="relative">
                                        <input type="time" name="dhuha_end" value="{{ old('dhuha_end', substr($config->dhuha_end, 0, 5)) }}" class="w-full pl-4 pr-10 py-3.5 rounded-2xl border-slate-200 bg-white font-mono font-bold text-slate-800 focus:ring-rose-500 focus:border-rose-500 transition-all shadow-sm">
                                        <i class="ph-bold ph-clock absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    </div>
                                    @error('dhuha_end') <p class="text-xs text-rose-500 mt-1.5 font-semibold">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- 2. KARTU PENGATURAN MAKAN SIANG --}}
                        <div class="bg-slate-50/50 rounded-[2rem] border border-slate-100 p-6 shadow-sm hover:shadow-md transition-shadow group">
                            <div class="flex items-center gap-4 border-b border-slate-200/60 pb-5">
                                <div class="w-14 h-14 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center text-3xl group-hover:scale-110 group-hover:-rotate-6 transition-all shadow-inner">
                                    <i class="ph-fill ph-bowl-food"></i>
                                </div>
                                <div>
                                    <h4 class="font-black text-elevate-dark text-lg tracking-tight">Makan Siang</h4>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Scan Ambil Gizi</p>
                                </div>
                            </div>
                            <div class="space-y-5 pt-5">
                                <div>
                                    <label class="text-[11px] font-black text-slate-500 uppercase tracking-wider block mb-2"><i class="ph-bold ph-play-circle text-amber-500 mr-1"></i> Jam Mulai</label>
                                    <div class="relative">
                                        <input type="time" name="makan_start" value="{{ old('makan_start', substr($config->makan_start, 0, 5)) }}" class="w-full pl-4 pr-10 py-3.5 rounded-2xl border-slate-200 bg-white font-mono font-bold text-slate-800 focus:ring-amber-500 focus:border-amber-500 transition-all shadow-sm">
                                        <i class="ph-bold ph-clock absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    </div>
                                    @error('makan_start') <p class="text-xs text-rose-500 mt-1.5 font-semibold">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="text-[11px] font-black text-slate-500 uppercase tracking-wider block mb-2"><i class="ph-bold ph-stop-circle text-rose-400 mr-1"></i> Jam Selesai</label>
                                    <div class="relative">
                                        <input type="time" name="makan_end" value="{{ old('makan_end', substr($config->makan_end, 0, 5)) }}" class="w-full pl-4 pr-10 py-3.5 rounded-2xl border-slate-200 bg-white font-mono font-bold text-slate-800 focus:ring-rose-500 focus:border-rose-500 transition-all shadow-sm">
                                        <i class="ph-bold ph-clock absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    </div>
                                    @error('makan_end') <p class="text-xs text-rose-500 mt-1.5 font-semibold">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        {{-- 3. KARTU PENGATURAN DHUHUR --}}
                        <div class="bg-slate-50/50 rounded-[2rem] border border-slate-100 p-6 shadow-sm hover:shadow-md transition-shadow group">
                            <div class="flex items-center gap-4 border-b border-slate-200/60 pb-5">
                                <div class="w-14 h-14 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-3xl group-hover:scale-110 group-hover:rotate-6 transition-all shadow-inner">
                                    <i class="ph-fill ph-moon-stars"></i>
                                </div>
                                <div>
                                    <h4 class="font-black text-elevate-dark text-lg tracking-tight">Sholat Dhuhur</h4>
                                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">Ibadah Siang</p>
                                </div>
                            </div>
                            <div class="space-y-5 pt-5">
                                <div>
                                    <label class="text-[11px] font-black text-slate-500 uppercase tracking-wider block mb-2"><i class="ph-bold ph-play-circle text-indigo-500 mr-1"></i> Jam Mulai</label>
                                    <div class="relative">
                                        <input type="time" name="dhuhur_start" value="{{ old('dhuhur_start', substr($config->dhuhur_start, 0, 5)) }}" class="w-full pl-4 pr-10 py-3.5 rounded-2xl border-slate-200 bg-white font-mono font-bold text-slate-800 focus:ring-indigo-500 focus:border-indigo-500 transition-all shadow-sm">
                                        <i class="ph-bold ph-clock absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    </div>
                                    @error('dhuhur_start') <p class="text-xs text-rose-500 mt-1.5 font-semibold">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="text-[11px] font-black text-slate-500 uppercase tracking-wider block mb-2"><i class="ph-bold ph-stop-circle text-rose-400 mr-1"></i> Jam Selesai</label>
                                    <div class="relative">
                                        <input type="time" name="dhuhur_end" value="{{ old('dhuhur_end', substr($config->dhuhur_end, 0, 5)) }}" class="w-full pl-4 pr-10 py-3.5 rounded-2xl border-slate-200 bg-white font-mono font-bold text-slate-800 focus:ring-rose-500 focus:border-rose-500 transition-all shadow-sm">
                                        <i class="ph-bold ph-clock absolute right-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    </div>
                                    @error('dhuhur_end') <p class="text-xs text-rose-500 mt-1.5 font-semibold">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- Tombol Submit --}}
                    <div class="pt-8 mt-2 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="px-8 py-4 bg-elevate-dark hover:bg-elevate-primary text-white font-bold text-sm rounded-2xl transition-all shadow-lg shadow-elevate-dark/20 active:scale-95 flex items-center gap-3">
                            <i class="ph-bold ph-floppy-disk text-xl"></i> Simpan Konfigurasi Waktu
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- Script Notifikasi Menggunakan Style SPPD --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            @if(session('success'))
                const Toast = Swal.mixin({
                    toast: true, 
                    position: 'top-end', 
                    showConfirmButton: false, 
                    timer: 3000,
                    timerProgressBar: true, 
                    customClass: { popup: 'rounded-xl' }
                });
                Toast.fire({ icon: 'success', title: '{{ session("success") }}' });
            @endif
        });
    </script>
</x-app-layout>