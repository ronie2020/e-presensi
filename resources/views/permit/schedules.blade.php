<x-app-layout>
    <div class="py-8 sm:py-10 relative min-h-screen font-sans text-white bg-elevate-surface pb-32 overflow-hidden">
        {{-- Background Ambient --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 md:space-y-8 relative z-10">
            
            {{-- HERO HEADER --}}
            <div class="relative rounded-[2.5rem] bg-gradient-to-br from-[#0d52a1]/85 via-[#031d3d]/90 to-[#021124]/95 p-8 md:p-10 text-white shadow-2xl shadow-[#0d52a1]/25 border border-white/20 backdrop-blur-2xl overflow-hidden group">
                <div class="absolute -top-16 -right-16 w-80 h-80 bg-[#56bbf1]/20 rounded-full blur-[100px] pointer-events-none"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 text-sky-300 text-xs font-bold uppercase tracking-wider backdrop-blur-md shadow-sm mb-3">
                            <i class="ph-bold ph-calendar-check text-sky-400"></i> Pengaturan Master Piket
                        </div>
                        <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight">
                            Jadwal Piket Guru Mingguan
                        </h1>
                        <p class="text-slate-300 text-sm mt-1">
                            Tahun Ajaran: <span class="text-sky-300 font-bold">{{ $activeYear->name ?? 'Aktif' }}</span> — Pengaturan petugas piket sekolah dari hari Senin s/d Sabtu.
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <a href="{{ route('permit.index') }}" class="px-5 py-3 rounded-2xl bg-white/10 hover:bg-white/20 text-white font-bold text-sm border border-white/20 backdrop-blur-md transition-all flex items-center gap-2 shadow-sm">
                            <i class="ph-bold ph-arrow-left text-lg"></i> Kembali ke Pos Piket
                        </a>
                    </div>
                </div>
            </div>

            {{-- NOTIFIKASI SUCCESS / ERROR --}}
            @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-500/20 border border-emerald-400/40 text-emerald-200 text-sm font-bold flex items-center gap-3 backdrop-blur-md">
                <i class="ph-bold ph-check-circle text-2xl text-emerald-400"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-500/20 border border-rose-400/40 text-rose-200 text-sm font-bold flex items-center gap-3 backdrop-blur-md">
                <i class="ph-bold ph-warning-circle text-2xl text-rose-400"></i>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            {{-- FORM TAMBAH PETUGAS PIKET --}}
            <div class="bg-[#031d3d]/90 backdrop-blur-xl border border-white/15 p-6 sm:p-8 rounded-[2.5rem] shadow-xl">
                <h3 class="font-black text-lg text-white mb-6 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-sky-500/20 text-sky-400 border border-sky-400/30 flex items-center justify-center">
                        <i class="ph-bold ph-user-plus text-xl"></i>
                    </div>
                    Plotting Guru Piket Baru
                </h3>

                <form action="{{ route('permit.schedules.store') }}" method="POST" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 items-end">
                    @csrf
                    
                    {{-- Pilih Hari --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Hari Piket</label>
                        <select name="day_name" required class="w-full px-4 py-3 rounded-xl border border-white/15 bg-[#021124] text-white font-bold text-sm focus:border-sky-400 focus:ring-2 focus:ring-sky-400/30 outline-none">
                            @foreach($days as $day)
                                <option value="{{ $day }}">{{ $day }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Pilih Guru --}}
                    <div class="lg:col-span-2">
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Pilih Guru / PTK</label>
                        <select name="user_id" required class="w-full px-4 py-3 rounded-xl border border-white/15 bg-[#021124] text-white font-bold text-sm focus:border-sky-400 focus:ring-2 focus:ring-sky-400/30 outline-none">
                            <option value="">-- Pilih Guru --</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}">{{ $teacher->name }} {{ $teacher->nip ? '('.$teacher->nip.')' : '' }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Pos / Lokasi Piket --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2">Pos / Lokasi</label>
                        <select name="post_location" class="w-full px-4 py-3 rounded-xl border border-white/15 bg-[#021124] text-white font-bold text-sm focus:border-sky-400 focus:ring-2 focus:ring-sky-400/30 outline-none">
                            <option value="Lobi Utama">Lobi Utama</option>
                            <option value="Gerbang 5S">Gerbang 5S (Pagi)</option>
                            <option value="Ruang KBM & Inval">Ruang KBM & Inval</option>
                            <option value="Kedisiplinan & Ketertiban">Kedisiplinan & Ketertiban</option>
                        </select>
                    </div>

                    {{-- Tombol Submit & Koordinator --}}
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <input type="checkbox" name="is_coordinator" value="1" id="is_coord" class="rounded border-slate-700 text-sky-500 focus:ring-sky-400 bg-slate-900">
                            <label for="is_coord" class="text-xs font-bold text-slate-300 cursor-pointer">Koordinator?</label>
                        </div>
                        <button type="submit" class="w-full py-3 px-6 rounded-xl bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white font-bold text-sm shadow-lg shadow-sky-500/25 transition-all active:scale-95 flex items-center justify-center gap-2">
                            <i class="ph-bold ph-plus"></i> Tambahkan
                        </button>
                    </div>
                </form>
            </div>

            {{-- GRID JADWAL SENIN S/D SABTU --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($days as $day)
                @php
                    $dayStaff = $schedules->get($day, collect());
                @endphp
                <div class="bg-[#031d3d]/85 backdrop-blur-xl border border-white/15 rounded-[2rem] p-6 shadow-xl flex flex-col justify-between">
                    <div>
                        {{-- Header Hari --}}
                        <div class="flex justify-between items-center pb-4 mb-4 border-b border-white/10">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white/10 text-sky-400 border border-white/15 flex items-center justify-center font-black">
                                    {{ substr($day, 0, 2) }}
                                </div>
                                <div>
                                    <h4 class="font-black text-lg text-white">{{ $day }}</h4>
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $dayStaff->count() }} Guru Bertugas</span>
                                </div>
                            </div>
                        </div>

                        {{-- List Guru Piket Hari Ini --}}
                        <div class="space-y-3">
                            @forelse($dayStaff as $staff)
                            <div class="flex items-center justify-between p-3 rounded-xl bg-[#021124]/70 border border-white/10 group hover:border-sky-400/40 transition-all">
                                <div class="flex items-center gap-3 min-w-0 pr-2">
                                    <div class="w-10 h-10 rounded-full bg-slate-800 border border-white/20 overflow-hidden shrink-0 flex items-center justify-center text-xs font-bold text-sky-300">
                                        @if($staff->teacher->photo_path)
                                            <img src="{{ asset('storage/' . $staff->teacher->photo_path) }}" alt="" class="w-full h-full object-cover">
                                        @else
                                            {{ substr($staff->teacher->name, 0, 2) }}
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <div class="text-sm font-bold text-white truncate flex items-center gap-1.5">
                                            {{ $staff->teacher->name }}
                                            @if($staff->is_coordinator)
                                                <span class="px-1.5 py-0.5 rounded text-[9px] bg-amber-500/20 text-amber-300 border border-amber-400/40 font-black">KOOR</span>
                                            @endif
                                        </div>
                                        <span class="text-[10px] font-bold text-sky-400/90 block truncate">{{ $staff->post_location }}</span>
                                    </div>
                                </div>

                                {{-- Form Hapus --}}
                                <form action="{{ route('permit.schedules.destroy', $staff->id) }}" method="POST" onsubmit="return confirm('Hapus petugas ini dari jadwal piket {{ $day }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 transition-colors" title="Hapus">
                                        <i class="ph-bold ph-trash text-lg"></i>
                                    </button>
                                </form>
                            </div>
                            @empty
                            <div class="py-8 text-center text-slate-400 text-xs font-bold italic">
                                Belum ada guru piket yang dijadwalkan pada hari ini.
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</x-app-layout>
