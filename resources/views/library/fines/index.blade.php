<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 font-sans bg-[#020b18] text-slate-100 min-h-screen relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-[300px] bg-amber-400/10 -z-10 blur-3xl pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HERO SECTION --}}
            <div class="mb-8 relative z-10">
                <x-hero-section
                    badge="KEUANGAN PERPUSTAKAAN"
                    badgeIcon="ph-fill ph-coins"
                    showcaseIcon="ph-duotone ph-coins"
                    showcaseTitle="Manajemen Denda"
                    showcaseSubtitle="Keterlambatan Pustaka">
                    <x-slot:title>
                        <span class="block text-slate-100">Manajemen &</span>
                        <span class="block mt-1 sm:mt-1.5 text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] via-sky-200 to-[#38bdf8]">
                            Pelunasan Denda
                        </span>
                    </x-slot:title>
                    <x-slot:description>
                        Kelola dan konfirmasi pembayaran denda keterlambatan pengembalian buku siswa secara transparan dan akuntabel.
                    </x-slot:description>
                    <x-slot:chips>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-coins text-amber-400"></i> Rekap Denda
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-check-circle text-emerald-400"></i> Konfirmasi Bayar
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-clock-counter-clockwise text-rose-400"></i> Tunggakan
                        </span>
                    </x-slot:chips>
                    <x-slot:cta>
                        <a href="{{ route('library.tools.index') }}"
                           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-slate-800/80 hover:bg-slate-700 text-slate-300 hover:text-white font-bold text-xs border border-slate-700 transition-all duration-300">
                            <i class="ph-bold ph-arrow-left"></i>
                            <span>Menu Tools</span>
                        </a>
                    </x-slot:cta>
                    <x-slot:showcaseStats>
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-2xl bg-slate-900/80 border border-rose-500/30 backdrop-blur-md">
                            <span class="text-xs font-bold text-slate-300">Tunggakan:</span>
                            <span class="text-sm font-black text-rose-400 font-mono">Rp {{ number_format($summary['total_unpaid'], 0, ',', '.') }}</span>
                        </div>
                    </x-slot:showcaseStats>
                </x-hero-section>
            </div>

            {{-- SUMMARY CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2rem] p-6 border border-white/10 shadow-2xl flex items-center gap-4 text-white">
                    <div class="w-14 h-14 bg-rose-500/20 border border-rose-500/30 rounded-2xl flex items-center justify-center shrink-0">
                        <i class="ph-duotone ph-coins text-2xl text-rose-400"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Belum Lunas</p>
                        <p class="text-2xl font-black text-rose-400">Rp {{ number_format($summary['total_unpaid'], 0, ',', '.') }}</p>
                        <p class="text-xs text-slate-400 font-medium mt-0.5">{{ $summary['count_unpaid'] }} transaksi</p>
                    </div>
                </div>
                <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2rem] p-6 border border-white/10 shadow-2xl flex items-center gap-4 text-white">
                    <div class="w-14 h-14 bg-emerald-500/20 border border-emerald-500/30 rounded-2xl flex items-center justify-center shrink-0">
                        <i class="ph-duotone ph-check-circle text-2xl text-emerald-400"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Sudah Lunas</p>
                        <p class="text-2xl font-black text-emerald-400">Rp {{ number_format($summary['total_paid'], 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2rem] p-6 border border-white/10 shadow-2xl flex items-center gap-4 text-white">
                    <div class="w-14 h-14 bg-amber-500/20 border border-amber-500/30 rounded-2xl flex items-center justify-center shrink-0">
                        <i class="ph-duotone ph-chart-line text-2xl text-amber-400"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Terkumpul</p>
                        <p class="text-2xl font-black text-amber-400">Rp {{ number_format($summary['total_unpaid'] + $summary['total_paid'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- FILTER --}}
            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2rem] p-5 border border-white/10 shadow-2xl mb-6">
                <form method="GET" class="flex flex-wrap items-center gap-3">
                    <div class="flex bg-slate-900/80 p-1 rounded-xl border border-white/10">
                        @foreach(['unpaid' => 'Belum Lunas', 'paid' => 'Sudah Lunas', 'all' => 'Semua'] as $val => $label)
                        <a href="{{ request()->fullUrlWithQuery(['filter' => $val]) }}"
                            class="{{ $filter === $val ? 'bg-[#0d52a1] text-white shadow-sm font-black' : 'text-slate-400 font-bold hover:text-white' }} px-4 py-2 text-xs rounded-lg transition-all">
                            {{ $label }}
                        </a>
                        @endforeach
                    </div>
                    <select name="class_id" onchange="this.form.submit()" class="px-4 py-2 rounded-2xl border-white/10 bg-slate-900/80 font-bold text-white text-sm focus:ring-4 focus:ring-sky-500/20 focus:border-[#56bbf1] appearance-none cursor-pointer [color-scheme:dark]">
                        <option value="" class="bg-slate-900 text-slate-300">Semua Kelas</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }} class="bg-slate-900 text-white">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            {{-- TABEL DENDA --}}
            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2rem] border border-white/10 shadow-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-slate-900/80">
                            <tr>
                                <th class="text-left px-6 py-4 text-xs font-black text-amber-400 uppercase tracking-wider">Siswa</th>
                                <th class="text-left px-4 py-4 text-xs font-black text-amber-400 uppercase tracking-wider">Buku</th>
                                <th class="text-center px-4 py-4 text-xs font-black text-amber-400 uppercase tracking-wider">Terlambat</th>
                                <th class="text-right px-4 py-4 text-xs font-black text-amber-400 uppercase tracking-wider">Denda</th>
                                <th class="text-center px-6 py-4 text-xs font-black text-amber-400 uppercase tracking-wider">Status</th>
                                <th class="text-center px-6 py-4 text-xs font-black text-amber-400 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($loans as $loan)
                            <tr id="row-{{ $loan->id }}" class="hover:bg-slate-900/50 transition-colors text-slate-200">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 bg-sky-500/20 border border-sky-500/30 rounded-xl flex items-center justify-center shrink-0">
                                            <i class="ph-bold ph-user text-sky-400 text-sm"></i>
                                        </div>
                                        <div>
                                            <a href="{{ route('library.members.show', $loan->student) }}" class="font-bold text-white hover:text-sky-400 transition text-sm leading-tight block">{{ $loan->student?->name }}</a>
                                            <p class="text-xs text-slate-400">{{ $loan->student?->schoolClass?->name ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <p class="font-bold text-white text-sm leading-tight max-w-[200px] truncate">{{ $loan->book?->title ?? '-' }}</p>
                                    <p class="text-xs text-slate-400">{{ \Carbon\Carbon::parse($loan->borrow_date)->format('d M Y') }}</p>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="px-2 py-1 bg-rose-950/80 text-rose-300 border border-rose-500/30 rounded-full text-xs font-black">{{ $loan->overdue_days }} hari</span>
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <span class="font-black text-white">Rp {{ number_format($loan->fine_amount, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($loan->fine_paid)
                                        <span class="px-3 py-1 bg-emerald-950/80 text-emerald-300 border border-emerald-500/30 rounded-full text-xs font-black flex items-center gap-1 justify-center">
                                            <i class="ph-bold ph-check-circle"></i> Lunas
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-rose-950/80 text-rose-300 border border-rose-500/30 rounded-full text-xs font-black flex items-center gap-1 justify-center">
                                            <i class="ph-bold ph-clock"></i> Belum Lunas
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if(!$loan->fine_paid)
                                    <button onclick="markPaid({{ $loan->id }}, '{{ $loan->student?->name }}', {{ $loan->fine_amount }})"
                                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-xl transition-all active:scale-95 shadow-sm border border-emerald-400/30">
                                        <i class="ph-bold ph-check"></i> Lunas
                                    </button>
                                    @else
                                    <button onclick="markUnpaid({{ $loan->id }})"
                                        class="px-4 py-2 bg-slate-900 hover:bg-rose-950/60 text-slate-400 hover:text-rose-400 border border-white/10 hover:border-rose-500/30 text-xs font-bold rounded-xl transition-all active:scale-95">
                                        <i class="ph-bold ph-arrow-u-up-left"></i> Undo
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-20 text-center">
                                    <i class="ph-duotone ph-coins text-5xl text-slate-600 block mb-3"></i>
                                    <p class="text-slate-400 font-bold">Tidak ada data denda untuk filter ini.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($loans->hasPages())
                <div class="px-6 py-4 border-t border-white/10">{{ $loans->links() }}</div>
                @endif
            </div>
        </div>
    </div>

    <script>
    async function markPaid(id, name, amount) {
        const result = await Swal.fire({
            title: 'Konfirmasi Pembayaran',
            html: `Tandai denda <b>Rp ${amount.toLocaleString('id-ID')}</b> dari <b>${name}</b> sebagai <span class="text-emerald-400 font-bold">LUNAS</span>?`,
            icon: 'question', showCancelButton: true,
            confirmButtonText: 'Ya, Tandai Lunas', cancelButtonText: 'Batal',
            confirmButtonColor: '#059669',
            background: '#021124', color: '#fff',
            customClass: { popup: 'rounded-[2.5rem] border border-white/10 bg-[#021124] text-white' }
        });
        if (!result.isConfirmed) return;

        const res = await fetch(`/library/fines/${id}/pay`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({})
        });
        const data = await res.json();
        if (data.success) {
            Swal.fire({
                toast: true, position: 'top-end', icon: 'success', title: data.message, showConfirmButton: false, timer: 2500,
                background: '#021124', color: '#fff',
                customClass: { popup: 'rounded-2xl border border-white/10 bg-[#021124] text-white' }
            });
            document.getElementById('row-' + id)?.remove();
        } else {
            Swal.fire({
                icon: 'error', title: 'Gagal', text: data.message,
                background: '#021124', color: '#fff', confirmButtonColor: '#0d52a1',
                customClass: { popup: 'rounded-[2.5rem] border border-white/10 bg-[#021124] text-white' }
            });
        }
    }

    async function markUnpaid(id) {
        const res = await fetch(`/library/fines/${id}/unpay`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({})
        });
        const data = await res.json();
        if (data.success) {
            Swal.fire({
                toast: true, position: 'top-end', icon: 'info', title: data.message, showConfirmButton: false, timer: 2000,
                background: '#021124', color: '#fff',
                customClass: { popup: 'rounded-2xl border border-white/10 bg-[#021124] text-white' }
            });
            setTimeout(() => location.reload(), 1500);
        }
    }
    </script>
</x-app-layout>
