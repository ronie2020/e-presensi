<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 font-sans text-elevate-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HERO --}}
            <div class="relative rounded-[2rem] bg-gradient-to-br from-violet-500 via-purple-500 to-indigo-500 p-8 mb-8 shadow-xl overflow-hidden border border-white/20">
                <div class="absolute -top-16 -right-16 w-56 h-56 bg-white/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <a href="{{ route('library.dashboard') }}" class="px-3 py-1 bg-white/20 hover:bg-white/40 rounded-lg text-xs font-bold text-white transition flex items-center gap-1 border border-white/30">
                                <i class="ph-bold ph-arrow-left"></i> Dashboard
                            </a>
                        </div>
                        <h1 class="text-3xl font-black tracking-tight text-white flex items-center gap-3">
                            <span class="text-4xl">📋</span> Antrean Reservasi Buku
                        </h1>
                        <p class="text-white/80 text-sm font-semibold mt-1">Siswa yang mengantre buku ketika stok habis. Notifikasi otomatis dikirim saat buku dikembalikan.</p>
                    </div>
                    <div class="hidden md:flex flex-col items-center gap-2">
                        <div class="flex gap-3">
                            <div class="text-center px-4 py-3 bg-white/20 rounded-2xl border border-white/30">
                                <p class="text-2xl font-black text-white">{{ $stats['total_waiting'] }}</p>
                                <p class="text-xs font-bold text-white/70">Mengantre</p>
                            </div>
                            <div class="text-center px-4 py-3 bg-white/20 rounded-2xl border border-white/30">
                                <p class="text-2xl font-black text-emerald-300">{{ $stats['total_ready'] }}</p>
                                <p class="text-xs font-bold text-white/70">Siap Ambil</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- TABEL --}}
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-violet-50/50">
                            <tr>
                                <th class="text-left px-6 py-4 text-xs font-black text-violet-700/80 uppercase tracking-wider">Siswa</th>
                                <th class="text-left px-4 py-4 text-xs font-black text-violet-700/80 uppercase tracking-wider">Buku</th>
                                <th class="text-center px-4 py-4 text-xs font-black text-violet-700/80 uppercase tracking-wider">Posisi</th>
                                <th class="text-center px-4 py-4 text-xs font-black text-violet-700/80 uppercase tracking-wider">Status</th>
                                <th class="text-left px-4 py-4 text-xs font-black text-violet-700/80 uppercase tracking-wider">Tanggal</th>
                                <th class="text-left px-4 py-4 text-xs font-black text-violet-700/80 uppercase tracking-wider">Batas Ambil</th>
                                <th class="text-center px-6 py-4 text-xs font-black text-violet-700/80 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($reservations as $index => $res)
                            <tr id="res-row-{{ $res->id }}" class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <a href="{{ route('library.members.show', $res->student) }}" class="flex items-center gap-3 group">
                                        <div class="w-9 h-9 bg-violet-100 rounded-xl flex items-center justify-center shrink-0 group-hover:bg-violet-200 transition">
                                            <i class="ph-bold ph-user text-violet-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-elevate-dark group-hover:text-violet-600 transition text-sm">{{ $res->student?->name }}</p>
                                            <p class="text-xs text-elevate-dark/50">{{ $res->student?->schoolClass?->name ?? '-' }}</p>
                                        </div>
                                    </a>
                                </td>
                                <td class="px-4 py-4">
                                    <p class="font-bold text-elevate-dark text-sm leading-tight max-w-[180px] truncate">{{ $res->book?->title }}</p>
                                    <p class="text-xs text-elevate-dark/50">Stok: {{ $res->book?->stock ?? 0 }}</p>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    <span class="w-8 h-8 bg-violet-100 text-violet-700 font-black text-sm rounded-full inline-flex items-center justify-center">
                                        {{ $index + 1 }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-center">
                                    @if($res->status === 'ready')
                                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-black flex items-center gap-1 justify-center">
                                            <i class="ph-bold ph-check-circle"></i> Siap Diambil
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-violet-100 text-violet-700 rounded-full text-xs font-black flex items-center gap-1 justify-center">
                                            <i class="ph-bold ph-hourglass"></i> Mengantre
                                        </span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-elevate-dark/60 font-medium text-xs">
                                    {{ $res->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-4 py-4">
                                    @if($res->expires_at)
                                        @if($res->is_expired)
                                            <span class="text-xs font-bold text-rose-600">Kadaluarsa</span>
                                        @else
                                            <span class="text-xs font-bold text-amber-600">{{ $res->expires_at->format('d M Y') }}</span>
                                        @endif
                                    @else
                                        <span class="text-xs text-elevate-dark/30">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <button onclick="cancelReservation({{ $res->id }}, '{{ $res->student?->name }}')"
                                        class="px-3 py-1.5 bg-slate-100 hover:bg-rose-100 text-slate-500 hover:text-rose-600 text-xs font-bold rounded-xl transition-all active:scale-95">
                                        <i class="ph-bold ph-x"></i> Batalkan
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="py-20 text-center">
                                    <i class="ph-duotone ph-queue text-5xl text-slate-200 block mb-3"></i>
                                    <p class="text-elevate-dark/40 font-bold">Belum ada antrean reservasi aktif.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($reservations->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">{{ $reservations->links() }}</div>
                @endif
            </div>

        </div>
    </div>

    <script>
    async function cancelReservation(id, name) {
        const result = await Swal.fire({
            title: 'Batalkan Reservasi?',
            text: `Hapus antrean reservasi milik ${name}?`,
            icon: 'warning', showCancelButton: true,
            confirmButtonText: 'Ya, Batalkan', cancelButtonText: 'Tidak',
            confirmButtonColor: '#dc2626', customClass: { popup: 'rounded-[2rem]' }
        });
        if (!result.isConfirmed) return;

        const res = await fetch(`/library/reservations/${id}/cancel`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({})
        });
        const data = await res.json();
        if (data.success) {
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: data.message, showConfirmButton: false, timer: 2000 });
            document.getElementById('res-row-' + id)?.remove();
        }
    }
    </script>
</x-app-layout>
