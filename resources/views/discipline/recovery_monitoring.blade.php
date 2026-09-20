<x-app-layout>
    <div class="py-8 font-sans text-elevate-text bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
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
                                            cancelButtonColor: '#94a3b8',
                                            confirmButtonText: 'Ya, Jalankan!',
                                            cancelButtonText: 'Batal',
                                            reverseButtons: true,
                                            customClass: {
                                                popup: 'rounded-[2rem] font-sans border-0 shadow-2xl',
                                                confirmButton: 'bg-sky-600 text-white px-5 py-2.5 rounded-xl font-bold hover:bg-sky-700 transition-colors mx-2 shadow-lg shadow-sky-500/20',
                                                cancelButton: 'bg-slate-100 text-slate-600 px-5 py-2.5 rounded-xl font-bold hover:bg-slate-200 transition-colors mx-2'
                                            },
                                            buttonsStyling: false
                                        }).then((result) => {
                                            if (result.isConfirmed) form.submit();
                                        });">
                            @csrf
                            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-slate-800/80 hover:bg-slate-700 text-slate-200 hover:text-white font-bold text-xs border border-slate-700 transition-all duration-300">
                                <i class="ph-bold ph-robot text-base text-sky-400"></i>
                                <span>Cek Poin Kebaikan</span>
                            </button>
                        </form>

                        {{-- TOMBOL INPUT TUGAS POSITIF (MANUAL) --}}
                        <button onclick="document.getElementById('modalAmnesti').classList.remove('hidden')"
                                class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white font-bold text-xs shadow-lg shadow-emerald-500/20 transition-all duration-300">
                            <i class="ph-bold ph-plus-circle text-base"></i>
                            <span>Input Tugas Positif</span>
                        </button>
                        
                        <a href="{{ route('discipline.index') }}"
                           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-slate-800/80 hover:bg-slate-700 text-slate-300 hover:text-white font-bold text-xs border border-slate-700 transition-all duration-300">
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
                <div class="bg-white p-6 rounded-[2rem] border border-emerald-100 shadow-sm">
                    <div class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-1">Total Poin Terpulihkan</div>
                    <div class="text-3xl font-black text-elevate-dark">{{ number_format($totalRecovered) }} <span class="text-sm text-slate-400">Poin</span></div>
                </div>
                <div class="bg-white p-6 rounded-[2rem] border border-elevate-accent/30 shadow-sm">
                    <div class="text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-1">Siswa Aktif Berusaha</div>
                    <div class="text-3xl font-black text-elevate-dark">{{ $activeCount }} <span class="text-sm text-slate-400">Siswa</span></div>
                </div>
                <div class="bg-elevate-dark p-6 rounded-[2rem] text-white shadow-xl shadow-elevate-dark/10">
                    <div class="text-[10px] font-black text-elevate-accent/60 uppercase tracking-widest mb-1">Sistem Point Decay</div>
                    <div class="text-3xl font-black">Aktif <span class="text-sm font-medium text-elevate-accent">Bulanan</span></div>
                </div>
            </div>

            {{-- TABLE LOG --}}
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/30">
                    <h3 class="font-black text-elevate-dark flex items-center gap-2">
                        <i class="ph-bold ph-list-star text-elevate-primary"></i>
                        Riwayat Pemutihan & Bonus Decay
                    </h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                            <tr>
                                <th class="px-8 py-5">Tanggal</th>
                                <th class="px-6 py-5">Siswa</th>
                                <th class="px-6 py-5">Jenis Pemulihan</th>
                                <th class="px-6 py-5 text-center">Saldo (+)</th>
                                <th class="px-6 py-5">Keterangan</th>
                                <th class="px-8 py-5 text-right">Oleh</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($recoveryRecords as $record)
                            <tr class="hover:bg-emerald-50/30 transition-colors">
                                <td class="px-8 py-5">
                                    <div class="text-xs font-bold text-slate-500">{{ \Carbon\Carbon::parse($record->date)->translatedFormat('d M Y') }}</div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="font-black text-elevate-dark text-sm uppercase tracking-tight">{{ $record->student->name }}</div>
                                    <div class="text-[10px] font-bold text-slate-400 uppercase">{{ $record->student->schoolClass->name ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-5">
                                    @if(str_contains(strtolower($record->disciplineType->name), 'decay'))
                                        <span class="px-3 py-1 rounded-lg bg-elevate-accent/10 text-elevate-primary text-[9px] font-black uppercase border border-elevate-accent/20">Point Decay</span>
                                    @else
                                        <span class="px-3 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-[9px] font-black uppercase border border-emerald-100">Amnesti Manual</span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 text-center">
                                    <div class="text-emerald-600 font-black">+{{ $record->disciplineType->point_value }}</div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="text-xs text-slate-600 italic leading-relaxed">"{{ $record->notes }}"</div>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <div class="text-xs font-bold text-slate-500">{{ $record->recorder->name ?? 'Sistem' }}</div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-8 py-20 text-center text-slate-400 italic">Belum ada data pemulihan poin.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-8 py-4 bg-slate-50">
                    {{ $recoveryRecords->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL INPUT TUGAS --}}
    <div id="modalAmnesti" class="fixed inset-0 z-50 hidden bg-slate-900/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-[2.5rem] w-full max-w-lg p-8 shadow-2xl">
            <h3 class="text-xl font-black text-elevate-dark mb-6 flex items-center gap-2">
                <i class="ph-fill ph-leaf text-emerald-500"></i> Validasi Tugas Pemulihan
            </h3>
            <form action="{{ route('recovery.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Pilih Siswa</label>
                        <select name="student_id" class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm font-bold focus:ring-emerald-500" required>
                            <option value="">-- Pilih Siswa --</option>
                            @forelse($students as $s)
                                <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->schoolClass->name ?? '-' }})</option>
                            @empty
                                <option value="" disabled>-- Tidak ada siswa aktif --</option>
                            @endforelse
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Jenis Amnesti</label>
                        <select name="discipline_type_id" class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm font-bold" required>
                            <option value="">-- Pilih Jenis Amnesti --</option>
                            @forelse($recoveryTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }} (+{{ $type->point_value }} Poin)</option>
                            @empty
                                <option value="" disabled>⚠️ Belum Ada Master Data Amnesti!</option>
                            @endforelse
                        </select>
                        @if($recoveryTypes->isEmpty())
                            <p class="text-xs text-rose-500 mt-2 font-medium">
                                Tambahkan jenis kabaikan yang namanya mengandung kata <b>"Amnesti"</b> atau <b>"Pemutihan"</b> di menu Jenis Pelanggaran terlebih dahulu.
                            </p>
                        @endif
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Keterangan Tugas</label>
                        <textarea name="notes" placeholder="Tulis tugas yang diselesaikan siswa..." class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm" rows="3" required></textarea>
                    </div>
                </div>
                <div class="flex gap-3 mt-8">
                    <button type="submit" class="flex-1 py-3 bg-emerald-600 text-white font-black rounded-xl hover:bg-emerald-700 transition-all" {{ $recoveryTypes->isEmpty() ? 'disabled' : '' }}>Simpan & Kurangi Poin</button>
                    <button type="button" onclick="document.getElementById('modalAmnesti').classList.add('hidden')" class="flex-1 py-3 bg-slate-100 text-slate-500 font-black rounded-xl">Batal</button>
                </div>
            </form>
        </div>
    </div>

    {{-- SweetAlert2 Library --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</x-app-layout>