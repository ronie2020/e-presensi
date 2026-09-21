<x-app-layout>
    <div class="py-8 font-sans text-slate-100 bg-[#020b18] min-h-screen relative overflow-hidden">
        
        {{-- Efek Latar Belakang Glowing --}}
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-emerald-600/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/3 right-10 w-96 h-96 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- HERO SECTION --}}
            <div class="mb-8 relative z-10">
                <x-hero-section
                    badge="PROGRAM RESTORASI SISWA"
                    badgeIcon="ph-fill ph-leaf"
                    showcaseIcon="ph-duotone ph-heartbeat"
                    showcaseTitle="Pemulihan Karakter"
                    showcaseSubtitle="Amnesti & Point Decay">
                    <x-slot:title>
                        <span class="block text-slate-100">Program Restorasi &</span>
                        <span class="block mt-1 sm:mt-1.5 text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] via-sky-200 to-[#38bdf8]">
                            Pemulihan Poin Siswa
                        </span>
                    </x-slot:title>
                    <x-slot:description>
                        Log amnesti poin positif, monitoring penyusutan poin pelanggaran secara otomatis (Point Decay), dan rekonsiliasi perilaku siswa.
                    </x-slot:description>
                    <x-slot:chips>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-robot text-sky-400"></i> Auto Point Decay
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-sparkle text-emerald-400"></i> Tugas Positif
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-shield-check text-cyan-400"></i> Rekonsiliasi Perilaku
                        </span>
                    </x-slot:chips>
                    <x-slot:cta>
                        {{-- TOMBOL SIMULASI DECAY OTOMATIS --}}
                        <form action="{{ route('recovery.trigger_decay') }}" method="POST" 
                               onsubmit="event.preventDefault(); 
                                         const form = this;
                                         Swal.fire({
                                             title: 'Jalankan Point Decay?',
                                             text: 'Sistem akan mengecek dan memulihkan poin siswa yang berhak secara otomatis. Lanjutkan?',
                                             icon: 'question',
                                             showCancelButton: true,
                                             confirmButtonColor: '#0284c7',
                                             cancelButtonColor: '#475569',
                                             confirmButtonText: 'Ya, Jalankan!',
                                             cancelButtonText: 'Batal',
                                             reverseButtons: true,
                                             background: '#0f172a',
                                             color: '#f8fafc',
                                             customClass: {
                                                 popup: 'rounded-[2rem] font-sans border border-white/10 shadow-2xl',
                                                 confirmButton: 'bg-sky-600 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-sky-500 transition-colors mx-2 shadow-lg shadow-sky-950/40',
                                                 cancelButton: 'bg-slate-800 text-slate-300 px-5 py-2.5 rounded-xl font-bold hover:bg-slate-700 transition-colors mx-2'
                                             },
                                             buttonsStyling: false
                                         }).then((result) => {
                                             if (result.isConfirmed) form.submit();
                                         });">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-white/5 hover:bg-white/10 text-slate-200 hover:text-white font-bold text-xs border border-white/10 transition-all duration-300 shadow-sm backdrop-blur-md">
                                <i class="ph-bold ph-robot text-base text-sky-400"></i>
                                <span>Cek Poin Kebaikan</span>
                            </button>
                        </form>

                        {{-- TOMBOL INPUT TUGAS POSITIF (MANUAL) --}}
                        <button onclick="document.getElementById('modalAmnesti').classList.remove('hidden')"
                                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white font-bold text-xs shadow-lg shadow-emerald-500/20 border border-emerald-400/30 transition-all duration-300">
                            <i class="ph-bold ph-plus-circle text-base"></i>
                            <span>Input Tugas Positif</span>
                        </button>
                        
                        <a href="{{ route('discipline.index') }}"
                           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-white/5 hover:bg-white/10 text-slate-300 hover:text-white font-bold text-xs border border-white/10 transition-all duration-300 shadow-sm backdrop-blur-md">
                            <i class="ph-bold ph-arrow-left"></i>
                            <span>Catatan Disiplin</span>
                        </a>
                    </x-slot:cta>
                    <x-slot:showcaseStats>
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-2xl bg-slate-900/80 border border-emerald-500/30 backdrop-blur-md">
                            <i class="ph-fill ph-leaf text-emerald-400 text-sm"></i>
                            <span class="text-xs font-bold text-slate-300">Terpulihkan:</span>
                            <span class="text-sm font-black text-emerald-400 font-mono">{{ number_format($totalRecovered) }} Poin</span>
                        </div>
                    </x-slot:showcaseStats>
                </x-hero-section>
            </div>

            {{-- STATS CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-6 rounded-[2rem] border border-emerald-500/30 shadow-2xl backdrop-blur-xl">
                    <div class="text-[10px] font-black text-emerald-400 uppercase tracking-widest mb-1">Total Poin Terpulihkan</div>
                    <div class="text-3xl font-black text-white">{{ number_format($totalRecovered) }} <span class="text-sm text-slate-400">Poin</span></div>
                </div>
                <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-6 rounded-[2rem] border border-sky-500/30 shadow-2xl backdrop-blur-xl">
                    <div class="text-[10px] font-black text-sky-400 uppercase tracking-widest mb-1">Siswa Aktif Berusaha</div>
                    <div class="text-3xl font-black text-white">{{ $activeCount }} <span class="text-sm text-slate-400">Siswa</span></div>
                </div>
                <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-6 rounded-[2rem] border border-white/10 shadow-2xl backdrop-blur-xl">
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Sistem Point Decay</div>
                    <div class="text-3xl font-black text-white">Aktif <span class="text-sm font-medium text-sky-400">Bulanan</span></div>
                </div>
            </div>

            {{-- TABLE LOG --}}
            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden backdrop-blur-xl mb-10">
                <div class="px-8 py-6 border-b border-white/10 bg-white/5">
                    <h3 class="font-black text-white flex items-center gap-2 text-lg">
                        <i class="ph-bold ph-list-star text-sky-400"></i>
                        Riwayat Pemutihan & Bonus Decay
                    </h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-white/5 border-b border-white/10 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            <tr>
                                <th class="px-8 py-5">Tanggal</th>
                                <th class="px-6 py-5">Siswa</th>
                                <th class="px-6 py-5">Jenis Pemulihan</th>
                                <th class="px-6 py-5 text-center">Saldo (+)</th>
                                <th class="px-6 py-5">Keterangan</th>
                                <th class="px-8 py-5 text-right">Oleh</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($recoveryRecords as $record)
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="text-xs font-bold text-white">{{ \Carbon\Carbon::parse($record->date)->translatedFormat('d M Y') }}</div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="font-black text-white text-sm uppercase tracking-tight group-hover:text-sky-400 transition-colors">{{ $record->student->name }}</div>
                                    <div class="text-[10px] font-bold text-slate-400 uppercase">{{ $record->student->schoolClass->name ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-5">
                                    @if(str_contains(strtolower($record->disciplineType->name), 'decay'))
                                        <span class="px-3 py-1 rounded-lg bg-sky-500/20 text-sky-300 text-[9px] font-black uppercase border border-sky-500/30">Point Decay</span>
                                    @else
                                        <span class="px-3 py-1 rounded-lg bg-emerald-500/20 text-emerald-300 text-[9px] font-black uppercase border border-emerald-500/30">Amnesti Manual</span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <div class="text-emerald-400 font-black">+{{ $record->disciplineType->point_value }}</div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="text-xs text-slate-300 italic leading-relaxed">"{{ $record->notes }}"</div>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="text-xs font-bold text-slate-400">{{ $record->recorder->name ?? 'Sistem' }}</div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-8 py-20 text-center text-slate-500 italic">Belum ada data pemulihan poin.</td>
                            </tr>
                            @empty
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-8 py-4 border-t border-white/10 bg-white/5">
                    {{ $recoveryRecords->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL INPUT TUGAS --}}
    <div id="modalAmnesti" class="fixed inset-0 z-50 hidden bg-slate-950/80 backdrop-blur-md flex items-center justify-center p-4">
        <div class="bg-[#031d3d] border border-white/10 rounded-[2.5rem] w-full max-w-lg p-8 shadow-2xl text-white">
            <h3 class="text-xl font-black text-white mb-6 flex items-center gap-2">
                <i class="ph-fill ph-leaf text-emerald-400"></i> Validasi Tugas Pemulihan
            </h3>
            <form action="{{ route('recovery.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Pilih Siswa</label>
                        <select name="student_id" class="w-full rounded-xl border-white/10 bg-slate-900/90 text-sm font-bold text-white focus:ring-emerald-500 focus:border-emerald-500 p-3.5 [color-scheme:dark]" required>
                            <option value="" class="bg-slate-900 text-slate-400">-- Pilih Siswa --</option>
                            @forelse($students as $s)
                                <option value="{{ $s->id }}" class="bg-slate-900 text-white">{{ $s->name }} ({{ $s->schoolClass->name ?? '-' }})</option>
                            @empty
                                <option value="" disabled class="bg-slate-900 text-slate-500">-- Tidak ada siswa aktif --</option>
                            @endforelse
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Jenis Amnesti</label>
                        <select name="discipline_type_id" class="w-full rounded-xl border-white/10 bg-slate-900/90 text-sm font-bold text-white focus:ring-emerald-500 focus:border-emerald-500 p-3.5 [color-scheme:dark]" required>
                            <option value="" class="bg-slate-900 text-slate-400">-- Pilih Jenis Amnesti --</option>
                            @forelse($recoveryTypes as $type)
                                <option value="{{ $type->id }}" class="bg-slate-900 text-white">{{ $type->name }} (+{{ $type->point_value }} Poin)</option>
                            @empty
                                <option value="" disabled class="bg-slate-900 text-slate-500">⚠️ Belum Ada Master Data Amnesti!</option>
                            @endforelse
                        </select>
                        @if($recoveryTypes->isEmpty())
                            <p class="text-xs text-rose-400 mt-2 font-medium">
                                Tambahkan jenis kabaikan yang namanya mengandung kata <b>"Amnesti"</b> atau <b>"Pemutihan"</b> di menu Jenis Pelanggaran terlebih dahulu.
                            </p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Keterangan Tugas</label>
                        <textarea name="notes" placeholder="Tulis tugas yang diselesaikan siswa..." class="w-full rounded-xl border-white/10 bg-slate-900/90 text-sm font-medium p-4 text-white placeholder:text-slate-500 focus:ring-emerald-500 focus:border-emerald-500 [color-scheme:dark]" rows="3" required></textarea>
                    </div>
                </div>
                <div class="flex gap-3 mt-8">
                    <button type="submit" class="flex-1 py-3.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl transition-all shadow-lg shadow-emerald-950/40 border border-emerald-500/30" {{ $recoveryTypes->isEmpty() ? 'disabled' : '' }}>Simpan & Kurangi Poin</button>
                    <button type="button" onclick="document.getElementById('modalAmnesti').classList.add('hidden')" class="flex-1 py-3.5 bg-white/5 hover:bg-white/10 text-slate-300 font-bold rounded-xl border border-white/10 transition-colors">Batal</button>
                </div>
            </form>
        </div>
    </div>

    {{-- SweetAlert2 Library --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</x-app-layout>