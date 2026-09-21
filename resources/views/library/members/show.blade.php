<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 font-sans bg-[#020b18] text-slate-100 min-h-screen relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-[300px] bg-sky-600/10 -z-10 blur-3xl pointer-events-none"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HERO: Info Siswa --}}
            <div class="relative rounded-[2rem] bg-gradient-to-r from-[#031d3d] via-[#021124] to-[#020b18] p-8 mb-8 shadow-2xl overflow-hidden border border-white/10 text-white">
                <div class="absolute -top-20 -right-20 w-64 h-64 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center gap-6">
                    <div class="w-24 h-24 rounded-[1.5rem] bg-slate-900 flex items-center justify-center border-4 border-white/10 shadow-lg overflow-hidden shrink-0">
                        @if($student->photo)
                            <img src="{{ asset('storage/' . $student->photo) }}" class="w-full h-full object-cover">
                        @else
                            <i class="ph-duotone ph-user text-5xl text-sky-400"></i>
                        @endif
                    </div>
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <a href="{{ route('library.circulation.index') }}" class="px-3 py-1 bg-slate-900/80 hover:bg-slate-900 rounded-lg text-xs font-bold text-sky-400 hover:text-white transition flex items-center gap-1 border border-white/10 backdrop-blur-sm">
                                <i class="ph-bold ph-arrow-left"></i> Sirkulasi
                            </a>
                            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Profil Anggota Pustaka</span>
                        </div>
                        <h1 class="text-3xl font-black tracking-tight text-white">{{ $student->name }}</h1>
                        <div class="flex flex-wrap gap-3 mt-2 text-sm font-semibold text-slate-300">
                            <span class="flex items-center gap-1.5"><i class="ph-bold ph-identification-badge text-sky-400"></i> {{ $student->student_id ?? $student->nis }}</span>
                            <span class="flex items-center gap-1.5"><i class="ph-bold ph-chalkboard-teacher text-sky-400"></i> {{ $student->schoolClass?->name ?? 'Tanpa Kelas' }}</span>
                            @if($stats['favourite_genre'])
                            <span class="flex items-center gap-1.5"><i class="ph-bold ph-heart text-rose-400"></i> Suka: {{ $stats['favourite_genre'] }}</span>
                            @endif
                        </div>
                    </div>
                    <div class="shrink-0">
                        <a href="{{ route('library.tools.print-card', ['mode' => 'single', 'nisn' => $student->student_id]) }}" target="_blank"
                            class="flex items-center gap-2 px-5 py-3 bg-[#0d52a1] text-white font-bold rounded-2xl hover:bg-sky-600 shadow-lg shadow-sky-950/50 transition-all active:scale-95 text-sm border border-sky-400/30">
                            <i class="ph-bold ph-printer"></i> Cetak Kartu
                        </a>
                    </div>
                </div>
            </div>

            {{-- STATISTIK 4 KARTU --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                @php
                    $statCards = [
                        ['label' => 'Total Dipinjam', 'value' => $stats['total_loans'], 'icon' => 'ph-books', 'color' => 'text-sky-400', 'bg' => 'bg-sky-500/20 border border-sky-500/30'],
                        ['label' => 'Aktif Sekarang', 'value' => $stats['active_loans'], 'icon' => 'ph-book-open', 'color' => 'text-blue-400', 'bg' => 'bg-blue-500/20 border border-blue-500/30'],
                        ['label' => 'Terlambat', 'value' => $stats['overdue_loans'], 'icon' => 'ph-warning-circle', 'color' => 'text-rose-400', 'bg' => 'bg-rose-500/20 border border-rose-500/30'],
                        ['label' => 'Denda Belum Lunas', 'value' => 'Rp ' . number_format($stats['unpaid_fine'], 0, ',', '.'), 'icon' => 'ph-coins', 'color' => 'text-amber-400', 'bg' => 'bg-amber-500/20 border border-amber-500/30'],
                    ];
                @endphp
                @foreach($statCards as $card)
                <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2rem] p-5 border border-white/10 shadow-2xl flex items-center gap-4 hover:border-sky-500/50 transition-all">
                    <div class="w-12 h-12 {{ $card['bg'] }} rounded-2xl flex items-center justify-center shrink-0">
                        <i class="ph-duotone {{ $card['icon'] }} text-2xl {{ $card['color'] }}"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">{{ $card['label'] }}</p>
                        <p class="text-xl font-black text-white leading-tight">{{ $card['value'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- TABEL RIWAYAT PEMINJAMAN --}}
            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2rem] border border-white/10 shadow-2xl overflow-hidden">
                <div class="p-6 border-b border-white/10 flex items-center justify-between">
                    <h2 class="font-black text-white text-lg flex items-center gap-2">
                        <i class="ph-duotone ph-clock-counter-clockwise text-sky-400 text-xl"></i>
                        Riwayat Peminjaman
                    </h2>
                    <span class="px-3 py-1 bg-sky-500/20 text-sky-300 border border-sky-500/30 rounded-full text-xs font-black">{{ $loans->total() }} transaksi</span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-900/80">
                            <tr>
                                <th class="text-left px-6 py-4 text-xs font-black text-sky-400 uppercase tracking-wider">Buku</th>
                                <th class="text-left px-4 py-4 text-xs font-black text-sky-400 uppercase tracking-wider">Tgl Pinjam</th>
                                <th class="text-left px-4 py-4 text-xs font-black text-sky-400 uppercase tracking-wider">Jatuh Tempo</th>
                                <th class="text-left px-4 py-4 text-xs font-black text-sky-400 uppercase tracking-wider">Status</th>
                                <th class="text-right px-6 py-4 text-xs font-black text-sky-400 uppercase tracking-wider">Denda</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($loans as $loan)
                            <tr class="hover:bg-slate-900/50 transition-colors text-slate-200">
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="font-bold text-white text-sm leading-tight">{{ $loan->book?->title ?? 'Buku Dihapus' }}</p>
                                        @if($loan->is_extended)
                                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-purple-300 bg-purple-950/60 border border-purple-500/30 px-2 py-0.5 rounded-full mt-1">
                                                <i class="ph-bold ph-arrows-clockwise"></i> Diperpanjang
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-slate-300 font-medium">{{ \Carbon\Carbon::parse($loan->borrow_date)->format('d M Y') }}</td>
                                <td class="px-4 py-4 text-slate-300 font-medium">{{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}</td>
                                <td class="px-4 py-4">
                                    @if($loan->status === 'borrowed' && \Carbon\Carbon::now()->gt($loan->due_date))
                                        <span class="px-2.5 py-1 bg-rose-950/80 text-rose-300 border border-rose-500/30 rounded-full text-xs font-black">⚠️ Terlambat {{ \Carbon\Carbon::now()->diffInDays($loan->due_date) }}h</span>
                                    @elseif($loan->status === 'borrowed')
                                        <span class="px-2.5 py-1 bg-blue-950/80 text-blue-300 border border-blue-500/30 rounded-full text-xs font-black">📖 Dipinjam</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-emerald-950/80 text-emerald-300 border border-emerald-500/30 rounded-full text-xs font-black">✅ Kembali</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right font-bold">
                                    @if(($loan->fine_amount ?? 0) > 0)
                                        <span class="{{ $loan->fine_paid ? 'text-emerald-400' : 'text-rose-400' }}">
                                            Rp {{ number_format($loan->fine_amount, 0, ',', '.') }}
                                            @if($loan->fine_paid) <i class="ph-bold ph-check-circle"></i> @endif
                                        </span>
                                    @else
                                        <span class="text-slate-600">—</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <i class="ph-duotone ph-books text-5xl text-slate-600 block mb-3"></i>
                                    <p class="text-slate-400 font-bold">Siswa ini belum pernah meminjam buku.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($loans->hasPages())
                <div class="px-6 py-4 border-t border-white/10">
                    {{ $loans->links() }}
                </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
