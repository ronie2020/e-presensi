<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 font-sans text-elevate-dark relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-[300px] bg-amber-400/10 -z-10 blur-3xl pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HERO --}}
            <div class="relative rounded-[2rem] bg-gradient-to-br from-amber-400 via-amber-300 to-yellow-300 p-8 mb-8 shadow-xl overflow-hidden border border-white/60">
                <div class="absolute -top-16 -right-16 w-56 h-56 bg-white/30 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <a href="{{ route('library.tools.index') }}" class="px-3 py-1 bg-white/50 hover:bg-white/80 rounded-lg text-xs font-bold text-amber-800 transition flex items-center gap-1 border border-white/60">
                                <i class="ph-bold ph-arrow-left"></i> Tools
                            </a>
                        </div>
                        <h1 class="text-3xl font-black tracking-tight text-amber-900 flex items-center gap-3">
                            <span class="text-4xl">💰</span> Manajemen Denda
                        </h1>
                        <p class="text-amber-800/80 text-sm font-semibold mt-1">Kelola dan konfirmasi pembayaran denda keterlambatan peminjaman buku.</p>
                    </div>
                    <div class="hidden md:flex items-center justify-center w-16 h-16 bg-white/50 rounded-2xl border border-white/60">
                        <i class="ph-duotone ph-coins text-4xl text-amber-600"></i>
                    </div>
                </div>
            </div>

            {{-- SUMMARY CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-lg flex items-center gap-4">
                    <div class="w-14 h-14 bg-rose-50 rounded-2xl flex items-center justify-center shrink-0">
                        <i class="ph-duotone ph-coins text-2xl text-rose-600"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-elevate-dark/50 uppercase tracking-wider">Belum Lunas</p>
                        <p class="text-2xl font-black text-rose-600">Rp {{ number_format($summary['total_unpaid'], 0, ',', '.') }}</p>
                        <p class="text-xs text-elevate-dark/40 font-medium mt-0.5">{{ $summary['count_unpaid'] }} transaksi</p>
                    </div>
                </div>
                <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-lg flex items-center gap-4">
                    <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center shrink-0">
                        <i class="ph-duotone ph-check-circle text-2xl text-emerald-600"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-elevate-dark/50 uppercase tracking-wider">Sudah Lunas</p>
                        <p class="text-2xl font-black text-emerald-600">Rp {{ number_format($summary['total_paid'], 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-lg flex items-center gap-4">
                    <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center shrink-0">
                        <i class="ph-duotone ph-chart-line text-2xl text-amber-600"></i>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-elevate-dark/50 uppercase tracking-wider">Total Terkumpul</p>
                        <p class="text-2xl font-black text-amber-600">Rp {{ number_format($summary['total_unpaid'] + $summary['total_paid'], 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- FILTER --}}
            <div class="bg-white rounded-[2rem] p-5 border border-slate-100 shadow-lg mb-6">
                <form method="GET" class="flex flex-wrap items-center gap-3">
                    <div class="flex bg-elevate-soft p-1 rounded-xl border border-slate-200">
                        @foreach(['unpaid' => 'Belum Lunas', 'paid' => 'Sudah Lunas', 'all' => 'Semua'] as $val => $label)
                        <a href="{{ request()->fullUrlWithQuery(['filter' => $val]) }}"
                            class="{{ $filter === $val ? 'bg-white text-elevate-primary shadow-sm font-black' : 'text-elevate-dark/60 font-bold hover:text-elevate-dark' }} px-4 py-2 text-xs rounded-lg transition-all">
                            {{ $label }}
                        </a>
                        @endforeach
                    </div>
                    <select name="class_id" onchange="this.form.submit()" class="px-4 py-2 rounded-2xl border-slate-200 bg-elevate-soft font-bold text-elevate-dark text-sm focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent appearance-none cursor-pointer">
                        <option value="">Semua Kelas</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            {{-- TABEL DENDA --}}
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-amber-50/60">
                            <tr>
                                <th class="text-left px-6 py-4 text-xs font-black text-amber-700/80 uppercase tracking-wider">Siswa</th>
                                <th class="text-left px-4 py-4 text-xs font-black text-amber-700/80 uppercase tracking-wider">Buku</th>
                                <th class="text-center px-4 py-4 text-xs font-black text-amber-700/80 uppercase tracking-wider">Terlambat</th>
                                <th class="text-right px-4 py-4 text-xs font-black text-amber-700/80 uppercase tracking-wider">Denda</th>
                                <th class="text-center px-6 py-4 text-xs font-black text-amber-700/80 uppercase tracking-wider">Status</th>
                                <th class="text-center px-6 py-4 text-xs font-black text-amber-700/80 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($loans as $loan)
                            <tr id="row-{{ $loan->id }}" class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 bg-elevate-soft rounded-xl flex items-center justify-center shrink-0">
                                            <i class="ph-bold ph-user text-elevate-primary text-sm"></i>
                                        </div>
                                        <div>
                                            <a href="{{ route('library.members.show', $loan->student) }}" class="font-bold text-elevate-dark hover:text-elevate-primary transition text-sm leading-tight block">{{ $loan->student?->name }}</a>
                                            <p class="text-xs text-elevate-dark/50">{{ $loan->student?->schoolClass?->name ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-4">
                                    <p class="font-bold text-elevate-dark text-sm leading-tight max-w-[200px] truncate">{{ $loan->book?->title ?? '-' }}</p>
                                    <p class="text-xs text-elevate-dark/50">{{ \Carbon\Carbon::parse($loan->borrow_date)->format('d M Y') }}</p>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="px-2 py-1 bg-rose-100 text-rose-700 rounded-full text-xs font-black">{{ $loan->overdue_days }} hari</span>
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <span class="font-black text-elevate-dark">Rp {{ number_format($loan->fine_amount, 0, ',', '.') }}</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($loan->fine_paid)
                                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-black flex items-center gap-1 justify-center">
                                            <i class="ph-bold ph-check-circle"></i> Lunas
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-rose-100 text-rose-700 rounded-full text-xs font-black flex items-center gap-1 justify-center">
                                            <i class="ph-bold ph-clock"></i> Belum Lunas
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if(!$loan->fine_paid)
                                    <button onclick="markPaid({{ $loan->id }}, '{{ $loan->student?->name }}', {{ $loan->fine_amount }})"
                                        class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl transition-all active:scale-95 shadow-sm">
                                        <i class="ph-bold ph-check"></i> Lunas
                                    </button>
                                    @else
                                    <button onclick="markUnpaid({{ $loan->id }})"
                                        class="px-4 py-2 bg-slate-100 hover:bg-rose-100 text-slate-500 hover:text-rose-600 text-xs font-bold rounded-xl transition-all active:scale-95">
                                        <i class="ph-bold ph-arrow-u-up-left"></i> Undo
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="py-20 text-center">
                                    <i class="ph-duotone ph-coins text-5xl text-slate-200 block mb-3"></i>
                                    <p class="text-elevate-dark/40 font-bold">Tidak ada data denda untuk filter ini.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($loans->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">{{ $loans->links() }}</div>
                @endif
            </div>
        </div>
    </div>

    <script>
    async function markPaid(id, name, amount) {
        const result = await Swal.fire({
            title: 'Konfirmasi Pembayaran',
            html: `Tandai denda <b>Rp ${amount.toLocaleString('id-ID')}</b> dari <b>${name}</b> sebagai <span class="text-emerald-600 font-bold">LUNAS</span>?`,
            icon: 'question', showCancelButton: true,
            confirmButtonText: 'Ya, Tandai Lunas', cancelButtonText: 'Batal',
            confirmButtonColor: '#059669', customClass: { popup: 'rounded-[2rem]' }
        });
        if (!result.isConfirmed) return;

        const res = await fetch(`/library/fines/${id}/pay`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({})
        });
        const data = await res.json();
        if (data.success) {
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: data.message, showConfirmButton: false, timer: 2500 });
            document.getElementById('row-' + id)?.remove();
        } else {
            Swal.fire({ icon: 'error', title: 'Gagal', text: data.message, customClass: { popup: 'rounded-[2rem]' } });
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
            Swal.fire({ toast: true, position: 'top-end', icon: 'info', title: data.message, showConfirmButton: false, timer: 2000 });
            setTimeout(() => location.reload(), 1500);
        }
    }
    </script>
</x-app-layout>
