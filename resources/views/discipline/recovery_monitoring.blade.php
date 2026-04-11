<x-app-layout>
    <div class="py-8 font-sans text-slate-800 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
                <div>
                    <h1 class="text-3xl font-black text-slate-900 flex items-center gap-3">
                        <i class="ph-duotone ph-leaf text-emerald-500"></i>
                        Program Pemulihan Siswa
                    </h1>
                    <p class="text-slate-500 font-medium">Log amnesti poin dan monitoring penyusutan poin otomatis (Point Decay).</p>
                </div>
                
               <div class="flex gap-2">
                    {{-- TOMBOL SIMULASI DECAY OTOMATIS --}}
                    <form action="{{ route('recovery.trigger_decay') }}" method="POST" onsubmit="return confirm('Jalankan pengecekan point decay otomatis sekarang?');">
                        @csrf
                        <button type="submit" class="px-5 py-2.5 bg-blue-600 rounded-2xl text-white font-bold hover:bg-blue-700 transition-all flex items-center gap-2 shadow-lg shadow-blue-200">
                            <i class="ph-bold ph-robot"></i> Cek Poin Kebaikan 
                        </button>
                    </form>

                    {{-- TOMBOL INPUT TUGAS POSITIF (MANUAL) --}}
                    <button onclick="document.getElementById('modalAmnesti').classList.remove('hidden')" class="px-5 py-2.5 bg-emerald-600 rounded-2xl text-white font-bold hover:bg-emerald-700 transition-all flex items-center gap-2 shadow-lg shadow-emerald-200">
                        <i class="ph-bold ph-plus-circle"></i> Input Manual Tugas Positif
                    </button>
                    
                    <a href="{{ route('discipline.index') }}" class="px-5 py-2.5 bg-white border border-slate-200 rounded-2xl text-slate-600 font-bold hover:bg-slate-50 transition-all flex items-center gap-2">
                        <i class="ph-bold ph-arrow-left"></i> Catatan Disiplin
                    </a>
                </div>
            </div>

            {{-- STATS CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-[2rem] border border-emerald-100 shadow-sm">
                    <div class="text-[10px] font-black text-emerald-500 uppercase tracking-widest mb-1">Total Poin Terpulihkan</div>
                    <div class="text-3xl font-black text-slate-800">{{ number_format($totalRecovered) }} <span class="text-sm text-slate-400">Poin</span></div>
                </div>
                <div class="bg-white p-6 rounded-[2rem] border border-blue-100 shadow-sm">
                    <div class="text-[10px] font-black text-blue-500 uppercase tracking-widest mb-1">Siswa Aktif Berusaha</div>
                    <div class="text-3xl font-black text-slate-800">{{ $activeCount }} <span class="text-sm text-slate-400">Siswa</span></div>
                </div>
                <div class="bg-slate-900 p-6 rounded-[2rem] text-white shadow-xl shadow-blue-900/10">
                    <div class="text-[10px] font-black text-blue-300/60 uppercase tracking-widest mb-1">Sistem Point Decay</div>
                    <div class="text-3xl font-black">Aktif <span class="text-sm font-medium text-blue-400">Bulanan</span></div>
                </div>
            </div>

            {{-- TABLE LOG --}}
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-50 bg-slate-50/30">
                    <h3 class="font-black text-slate-800 flex items-center gap-2">
                        <i class="ph-bold ph-list-star text-blue-600"></i>
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
                                    <div class="font-black text-slate-800 text-sm uppercase tracking-tight">{{ $record->student->name }}</div>
                                    <div class="text-[10px] font-bold text-slate-400 uppercase">{{ $record->student->schoolClass->name ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-5">
                                    @if(str_contains(strtolower($record->disciplineType->name), 'decay'))
                                        <span class="px-3 py-1 rounded-lg bg-blue-50 text-blue-700 text-[9px] font-black uppercase border border-blue-100">Point Decay</span>
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
            <h3 class="text-xl font-black text-slate-800 mb-6 flex items-center gap-2">
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
</x-app-layout>