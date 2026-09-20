<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 font-sans text-elevate-dark">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HERO SECTION --}}
            <div class="mb-8 relative z-10">
                <x-hero-section
                    badge="LAYANAN SIRKULASI BUKU"
                    badgeIcon="ph-fill ph-clock-countdown"
                    showcaseIcon="ph-duotone ph-clipboard-text"
                    showcaseTitle="Reservasi Buku"
                    showcaseSubtitle="Antrean & Notifikasi">
                    <x-slot:title>
                        <span class="block text-slate-100">Antrean &</span>
                        <span class="block mt-1 sm:mt-1.5 text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] via-sky-200 to-[#38bdf8]">
                            Reservasi Buku
                        </span>
                    </x-slot:title>
                    <x-slot:description>
                        Daftar siswa yang mengantre buku ketika stok habis. Notifikasi otomatis dikirim saat buku telah dikembalikan dan siap diambil.
                    </x-slot:description>
                    <x-slot:chips>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-hourglass-high text-sky-400"></i> Antrean FIFO
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-bell text-emerald-400"></i> Auto Notif
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-calendar-check text-cyan-400"></i> Batas Pengambilan
                        </span>
                    </x-slot:chips>
                    <x-slot:cta>
                        <a href="{{ route('library.dashboard') }}"
                           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-slate-800/80 hover:bg-slate-700 text-slate-300 hover:text-white font-bold text-xs border border-slate-700 transition-all duration-300">
                            <i class="ph-bold ph-arrow-left"></i>
                            <span>Dashboard Pustaka</span>
                        </a>
                    </x-slot:cta>
                    <x-slot:showcaseStats>
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-2xl bg-slate-900/80 border border-slate-700/80 backdrop-blur-md">
                            <span class="text-xs font-bold text-slate-300">Antre:</span>
                            <span class="text-sm font-black text-white font-mono">{{ $stats['total_waiting'] }}</span>
                        </div>
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-2xl bg-slate-900/80 border border-emerald-500/30 backdrop-blur-md">
                            <span class="text-xs font-bold text-slate-300">Siap:</span>
                            <span class="text-sm font-black text-emerald-400 font-mono">{{ $stats['total_ready'] }}</span>
                        </div>
                    </x-slot:showcaseStats>
                </x-hero-section>
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
