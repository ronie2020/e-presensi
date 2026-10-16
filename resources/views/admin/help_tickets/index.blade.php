<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-sky-500/10 border border-sky-400/20 text-sky-400 text-xs font-bold uppercase tracking-wider mb-2">
                    <i class="ph-bold ph-lifebuoy text-sm"></i>
                    <span>Pusat Layanan IT & Helpdesk</span>
                </div>
                <h2 class="text-2xl sm:text-3xl font-black text-white tracking-tight flex items-center gap-3">
                    Tiket Kendala Aplikasi
                </h2>
                <p class="text-xs sm:text-sm text-slate-400 mt-1">
                    Kelola dan pantau seluruh kendala yang dihadapi Siswa & Guru saat mengakses SIMADU
                </p>
            </div>

            <div class="flex items-center gap-3">
                <button type="button" 
                        @click="$dispatch('open-create-ticket-modal')"
                        class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white font-bold text-xs sm:text-sm flex items-center gap-2 shadow-lg shadow-sky-500/20 transition-all active:scale-95 cursor-pointer">
                    <i class="ph-bold ph-plus-circle text-base"></i>
                    <span>Catat Kendala Manual</span>
                </button>
            </div>
        </div>
    </x-slot>

    <div x-data="helpTicketsManager()" class="py-6 space-y-6">

        {{-- FLASH ALERTS --}}
        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-500/15 border border-emerald-500/30 text-emerald-300 text-sm font-semibold flex items-center justify-between gap-3 shadow-lg">
                <div class="flex items-center gap-3">
                    <i class="ph-bold ph-check-circle text-xl shrink-0"></i>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" @click="$el.parentElement.remove()" class="text-emerald-400 hover:text-white">
                    <i class="ph-bold ph-x"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 rounded-2xl bg-rose-500/15 border border-rose-500/30 text-rose-300 text-sm font-semibold flex items-center justify-between gap-3 shadow-lg">
                <div class="flex items-center gap-3">
                    <i class="ph-bold ph-warning-circle text-xl shrink-0"></i>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" @click="$el.parentElement.remove()" class="text-rose-400 hover:text-white">
                    <i class="ph-bold ph-x"></i>
                </button>
            </div>
        @endif

        {{-- KPI STATS CARDS --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            {{-- Total Tiket --}}
            <div class="p-5 rounded-2xl bg-[#031d3d]/90 border border-white/10 shadow-xl relative overflow-hidden group hover:border-sky-400/40 transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Kendala</p>
                        <h3 class="text-2xl sm:text-3xl font-black text-white mt-1">{{ $stats['total'] }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-sky-500/20 text-sky-400 border border-sky-400/30 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        <i class="ph-fill ph-ticket"></i>
                    </div>
                </div>
                <div class="mt-3 flex items-center gap-3 text-[11px] text-slate-400 pt-2 border-t border-white/5">
                    <span>Siswa: <strong class="text-sky-300 font-bold">{{ $stats['siswa_count'] }}</strong></span>
                    <span>&bull;</span>
                    <span>Guru: <strong class="text-purple-300 font-bold">{{ $stats['guru_count'] }}</strong></span>
                </div>
            </div>

            {{-- Menunggu Penanganan (Pending) --}}
            <div class="p-5 rounded-2xl bg-[#031d3d]/90 border border-white/10 shadow-xl relative overflow-hidden group hover:border-amber-400/40 transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-amber-300 uppercase tracking-wider flex items-center gap-1.5">
                            <span class="w-2 h-2 rounded-full bg-amber-400 animate-ping"></span>
                            Menunggu Respon
                        </p>
                        <h3 class="text-2xl sm:text-3xl font-black text-amber-400 mt-1">{{ $stats['pending'] }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-amber-500/20 text-amber-400 border border-amber-400/30 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        <i class="ph-fill ph-clock-countdown"></i>
                    </div>
                </div>
                <div class="mt-3 text-[11px] text-slate-400 pt-2 border-t border-white/5">
                    Memerlukan perhatian segera
                </div>
            </div>

            {{-- Sedang Diproses (In Progress) --}}
            <div class="p-5 rounded-2xl bg-[#031d3d]/90 border border-white/10 shadow-xl relative overflow-hidden group hover:border-blue-400/40 transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-blue-300 uppercase tracking-wider">Sedang Ditangani</p>
                        <h3 class="text-2xl sm:text-3xl font-black text-blue-400 mt-1">{{ $stats['in_progress'] }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-500/20 text-blue-400 border border-blue-400/30 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        <i class="ph-fill ph-gear"></i>
                    </div>
                </div>
                <div class="mt-3 text-[11px] text-slate-400 pt-2 border-t border-white/5">
                    Dalam proses perbaikan teknis
                </div>
            </div>

            {{-- Selesai (Resolved) --}}
            <div class="p-5 rounded-2xl bg-[#031d3d]/90 border border-white/10 shadow-xl relative overflow-hidden group hover:border-emerald-400/40 transition-all">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[11px] font-bold text-emerald-300 uppercase tracking-wider">Telah Selesai</p>
                        <h3 class="text-2xl sm:text-3xl font-black text-emerald-400 mt-1">{{ $stats['resolved'] }}</h3>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-emerald-500/20 text-emerald-400 border border-emerald-400/30 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        <i class="ph-fill ph-check-circle"></i>
                    </div>
                </div>
                <div class="mt-3 text-[11px] text-slate-400 pt-2 border-t border-white/5">
                    Solusi telah diberikan ke pelapor
                </div>
            </div>
        </div>

        {{-- FILTER & SEARCH TOOLBAR --}}
        <div class="p-5 rounded-2xl bg-[#031d3d]/80 border border-white/10 shadow-xl">
            <form method="GET" action="{{ route('admin.help-tickets.index') }}" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    
                    {{-- Pencarian Kata Kunci --}}
                    <div>
                        <label class="block text-[11px] font-bold text-slate-400 mb-1">Cari Tiket / Pelapor</label>
                        <div class="relative">
                            <i class="ph-bold ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" 
                                   name="search" 
                                   value="{{ request('search') }}"
                                   placeholder="Kode, nama, NISN/NIP, kendala..." 
                                   class="w-full pl-10 pr-3 py-2 rounded-xl bg-[#021124] border border-white/15 text-white font-semibold text-xs focus:border-sky-400 focus:ring-1 focus:ring-sky-400 outline-none">
                        </div>
                    </div>

                    {{-- Filter Peran --}}
                    <div>
                        <label class="block text-[11px] font-bold text-slate-400 mb-1">Peran Pelapor</label>
                        <select name="reporter_type" 
                                class="w-full px-3 py-2 rounded-xl bg-[#021124] border border-white/15 text-white font-semibold text-xs focus:border-sky-400 outline-none">
                            <option value="">Semua Peran (Siswa & Guru)</option>
                            <option value="siswa" {{ request('reporter_type') === 'siswa' ? 'selected' : '' }}>Siswa Saja</option>
                            <option value="guru" {{ request('reporter_type') === 'guru' ? 'selected' : '' }}>Guru / Tenaga Pendidik</option>
                            <option value="umum" {{ request('reporter_type') === 'umum' ? 'selected' : '' }}>Umum / Tamu</option>
                        </select>
                    </div>

                    {{-- Filter Status --}}
                    <div>
                        <label class="block text-[11px] font-bold text-slate-400 mb-1">Status Penanganan</label>
                        <select name="status" 
                                class="w-full px-3 py-2 rounded-xl bg-[#021124] border border-white/15 text-white font-semibold text-xs focus:border-sky-400 outline-none">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Menunggu Respon (Pending)</option>
                            <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Sedang Ditangani (In Progress)</option>
                            <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Selesai (Resolved)</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Ditolak (Rejected)</option>
                        </select>
                    </div>

                    {{-- Filter Kategori --}}
                    <div>
                        <label class="block text-[11px] font-bold text-slate-400 mb-1">Kategori Masalah</label>
                        <select name="category" 
                                class="w-full px-3 py-2 rounded-xl bg-[#021124] border border-white/15 text-white font-semibold text-xs focus:border-sky-400 outline-none">
                            <option value="">Semua Kategori</option>
                            <option value="Gagal Login / Lupa Akun" {{ request('category') === 'Gagal Login / Lupa Akun' ? 'selected' : '' }}>Gagal Login / Lupa Akun</option>
                            <option value="Kendala Ujian CBT / Reset Token" {{ request('category') === 'Kendala Ujian CBT / Reset Token' ? 'selected' : '' }}>Kendala Ujian CBT / Reset Token</option>
                            <option value="Kendala Presensi / Scan Wajah" {{ request('category') === 'Kendala Presensi / Scan Wajah' ? 'selected' : '' }}>Kendala Presensi / Scan Wajah</option>
                            <option value="Kendala Input Nilai / Rapor (Guru)" {{ request('category') === 'Kendala Input Nilai / Rapor (Guru)' ? 'selected' : '' }}>Kendala Input Nilai / Rapor (Guru)</option>
                            <option value="Kendala Jurnal Mengajar / Inval (Guru)" {{ request('category') === 'Kendala Jurnal Mengajar / Inval (Guru)' ? 'selected' : '' }}>Kendala Jurnal Mengajar / Inval (Guru)</option>
                            <option value="Error Halaman / Tampilan Blank" {{ request('category') === 'Error Halaman / Tampilan Blank' ? 'selected' : '' }}>Error Halaman / Tampilan Blank</option>
                            <option value="Lainnya" {{ request('category') === 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>

                </div>

                <div class="flex items-center justify-between pt-2 border-t border-white/5">
                    <div class="text-xs text-slate-400">
                        Menampilkan <strong class="text-white">{{ $tickets->total() }}</strong> tiket kendala
                    </div>
                    <div class="flex items-center gap-2">
                        @if(request()->anyFilled(['search', 'reporter_type', 'status', 'category']))
                            <a href="{{ route('admin.help-tickets.index') }}" 
                               class="px-3 py-1.5 rounded-lg bg-white/5 hover:bg-white/10 text-slate-300 text-xs font-bold transition">
                                Reset Filter
                            </a>
                        @endif
                        <button type="submit" 
                                class="px-4 py-1.5 rounded-lg bg-sky-500 hover:bg-sky-400 text-white font-bold text-xs flex items-center gap-1.5 shadow-md transition">
                            <i class="ph-bold ph-funnel"></i>
                            <span>Terapkan Filter</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- DAFTAR TIKET KENDALA --}}
        @if($tickets->count() > 0)
            <div class="space-y-4">
                @foreach($tickets as $ticket)
                    @php
                        // Badge Status
                        $statusConfig = match($ticket->status) {
                            'pending'     => ['bg' => 'bg-amber-500/15', 'text' => 'text-amber-300', 'border' => 'border-amber-400/30', 'label' => 'Menunggu Respon', 'icon' => 'ph-clock-countdown'],
                            'in_progress' => ['bg' => 'bg-blue-500/15', 'text' => 'text-blue-300', 'border' => 'border-blue-400/30', 'label' => 'Sedang Ditangani', 'icon' => 'ph-gear'],
                            'resolved'    => ['bg' => 'bg-emerald-500/15', 'text' => 'text-emerald-300', 'border' => 'border-emerald-400/30', 'label' => 'Selesai', 'icon' => 'ph-check-circle'],
                            'rejected'    => ['bg' => 'bg-rose-500/15', 'text' => 'text-rose-300', 'border' => 'border-rose-400/30', 'label' => 'Ditolak / Selesai Tanpa Aksi', 'icon' => 'ph-x-circle'],
                            default       => ['bg' => 'bg-slate-500/15', 'text' => 'text-slate-300', 'border' => 'border-slate-400/30', 'label' => ucfirst($ticket->status), 'icon' => 'ph-info']
                        };

                        // Sanitasi nomor HP untuk WhatsApp
                        $phone = preg_replace('/[^0-9]/', '', $ticket->phone ?? '');
                        if (str_starts_with($phone, '0')) {
                            $phone = '62' . substr($phone, 1);
                        }
                        $waText = "Halo Bapak/Ibu/Ananda {$ticket->name}, kami dari Tim IT SIMADU SMPN 3 Lakbok menindaklanjuti ajuan kendala Anda dengan kode [{$ticket->ticket_code}]:\n\n"
                                . "Kategori: {$ticket->category}\n"
                                . "Keterangan: \"{$ticket->description}\"\n\n";
                        $waUrl = $phone ? "https://wa.me/{$phone}?text=" . urlencode($waText) : null;
                    @endphp

                    <div class="p-5 sm:p-6 rounded-2xl bg-[#031d3d]/90 border border-white/10 hover:border-white/20 transition-all shadow-xl space-y-4">
                        
                        {{-- Bar Atas: Kode Tiket, Waktu, & Status --}}
                        <div class="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-white/10">
                            <div class="flex items-center gap-3">
                                <span class="px-3 py-1 rounded-lg bg-sky-500/20 border border-sky-400/30 text-sky-300 font-mono font-black text-xs tracking-wider">
                                    {{ $ticket->ticket_code }}
                                </span>

                                {{-- Reporter Badge --}}
                                @if($ticket->reporter_type === 'siswa')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-cyan-500/15 text-cyan-300 border border-cyan-400/25 text-[11px] font-bold">
                                        <i class="ph-bold ph-student"></i> Siswa
                                    </span>
                                @elseif($ticket->reporter_type === 'guru')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-purple-500/15 text-purple-300 border border-purple-400/25 text-[11px] font-bold">
                                        <i class="ph-bold ph-chalkboard-teacher"></i> Guru / PTK
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-slate-500/15 text-slate-300 border border-slate-400/25 text-[11px] font-bold">
                                        <i class="ph-bold ph-user"></i> Umum
                                    </span>
                                @endif

                                <span class="text-xs text-slate-400 flex items-center gap-1">
                                    <i class="ph-bold ph-clock"></i>
                                    {{ $ticket->created_at->diffForHumans() }} ({{ $ticket->created_at->format('d M Y, H:i') }})
                                </span>
                            </div>

                            {{-- Status Badge --}}
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold {{ $statusConfig['bg'] }} {{ $statusConfig['text'] }} border {{ $statusConfig['border'] }}">
                                    <i class="ph-bold {{ $statusConfig['icon'] }}"></i>
                                    <span>{{ $statusConfig['label'] }}</span>
                                </span>
                            </div>
                        </div>

                        {{-- Bar Tengah: Profil Pelapor & Detail Kendala --}}
                        <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                            
                            {{-- Info Pelapor (4 Kolom) --}}
                            <div class="md:col-span-4 space-y-2 p-3.5 rounded-xl bg-[#021124]/80 border border-white/5">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Data Pelapor</p>
                                <div>
                                    <h4 class="font-bold text-white text-sm flex items-center gap-1.5">
                                        <i class="ph-bold ph-user-circle text-sky-400 text-base"></i>
                                        {{ $ticket->name }}
                                    </h4>
                                    @if($ticket->class_or_role)
                                        <p class="text-xs text-slate-300 mt-0.5 pl-5">
                                            {{ $ticket->class_or_role }}
                                        </p>
                                    @endif
                                </div>

                                <div class="text-xs text-slate-400 space-y-1 pt-1 border-t border-white/5 pl-5">
                                    @if($ticket->identity_number)
                                        <p class="flex items-center gap-1.5 font-mono text-slate-300">
                                            <span class="text-slate-500 text-[11px]">{{ $ticket->reporter_type === 'guru' ? 'NIP/NUPTK' : 'NISN' }}:</span>
                                            {{ $ticket->identity_number }}
                                        </p>
                                    @endif
                                    @if($ticket->phone)
                                        <p class="flex items-center gap-1.5 text-slate-300">
                                            <i class="ph-bold ph-phone text-emerald-400 text-xs"></i>
                                            <span>{{ $ticket->phone }}</span>
                                        </p>
                                    @endif
                                </div>

                                {{-- URL Halaman yang Sedang Diakses saat Error --}}
                                @if($ticket->page_url)
                                    <div class="pt-2 border-t border-white/5 text-[11px]">
                                        <span class="text-slate-400 block mb-0.5">Halaman saat kendala terjadi:</span>
                                        <a href="{{ $ticket->page_url }}" 
                                           target="_blank" 
                                           class="text-sky-400 hover:underline truncate block font-mono text-[10px] bg-black/30 p-1.5 rounded-lg border border-white/5" 
                                           title="{{ $ticket->page_url }}">
                                            <i class="ph-bold ph-arrow-square-out mr-1"></i>
                                            {{ Str::limit($ticket->page_url, 40) }}
                                        </a>
                                    </div>
                                @endif
                            </div>

                            {{-- Isi Kendala & Screenshot (8 Kolom) --}}
                            <div class="md:col-span-8 space-y-3">
                                <div>
                                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-sky-500/10 text-sky-300 border border-sky-400/20 text-xs font-bold mb-2">
                                        <i class="ph-bold ph-tag"></i>
                                        <span>{{ $ticket->category }}</span>
                                    </div>

                                    <div class="p-3.5 rounded-xl bg-[#021124] border border-white/10 text-xs sm:text-sm text-slate-200 leading-relaxed whitespace-pre-line">
                                        {{ $ticket->description }}
                                    </div>
                                </div>

                                {{-- Screenshot Bukti Kendala --}}
                                @if($ticket->screenshot_path)
                                    <div class="flex items-center gap-3 p-3 rounded-xl bg-white/5 border border-white/10">
                                        <div class="w-16 h-16 rounded-lg overflow-hidden bg-black/50 border border-white/10 shrink-0 cursor-pointer group/img"
                                             @click="openLightbox('{{ asset('storage/' . $ticket->screenshot_path) }}')">
                                            <img src="{{ asset('storage/' . $ticket->screenshot_path) }}" 
                                                 alt="Bukti Error" 
                                                 class="w-full h-full object-cover group-hover/img:scale-110 transition-transform">
                                        </div>
                                        <div class="min-w-0">
                                            <p class="text-xs font-bold text-white flex items-center gap-1">
                                                <i class="ph-bold ph-image text-sky-400"></i> Lampiran Gambar / Screenshot Error
                                            </p>
                                            <p class="text-[11px] text-slate-400 mt-0.5">Klik untuk melihat tampilan layar kendala secara penuh</p>
                                            <button type="button" 
                                                    @click="openLightbox('{{ asset('storage/' . $ticket->screenshot_path) }}')"
                                                    class="text-xs font-bold text-sky-400 hover:text-sky-300 mt-1 inline-flex items-center gap-1">
                                                <i class="ph-bold ph-magnifying-glass-plus"></i> Perbesar Gambar
                                            </button>
                                        </div>
                                    </div>
                                @endif

                                {{-- Catatan Solusi / Penanganan Sebelumnya jika Ada --}}
                                @if($ticket->admin_notes)
                                    <div class="p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-xs">
                                        <div class="flex items-center gap-1.5 text-emerald-400 font-bold mb-1">
                                            <i class="ph-bold ph-note-pencil"></i>
                                            <span>Catatan Penanganan Admin:</span>
                                            @if($ticket->resolver)
                                                <span class="text-slate-400 font-normal">oleh {{ $ticket->resolver->name }}</span>
                                            @endif
                                            @if($ticket->resolved_at)
                                                <span class="text-slate-400 font-normal">({{ $ticket->resolved_at->format('d M Y H:i') }})</span>
                                            @endif
                                        </div>
                                        <p class="text-slate-200 leading-relaxed">{{ $ticket->admin_notes }}</p>
                                    </div>
                                @endif
                            </div>

                        </div>

                        {{-- Bar Bawah: Tombol Aksi Admin --}}
                        <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-white/10">
                            <div class="text-[11px] text-slate-400">
                                @if($ticket->resolved_at)
                                    Diselesaikan pada: <strong class="text-emerald-300 font-mono">{{ $ticket->resolved_at->format('d/m/Y H:i') }} WIB</strong>
                                @else
                                    Status saat ini: <strong class="text-white">{{ ucfirst($ticket->status) }}</strong>
                                @endif
                            </div>

                            <div class="flex items-center gap-2">
                                {{-- Tombol WhatsApp Pelapor --}}
                                @if($waUrl)
                                    <a href="{{ $waUrl }}" 
                                       target="_blank"
                                       class="px-3.5 py-2 rounded-xl bg-emerald-500/20 hover:bg-emerald-500 text-emerald-300 hover:text-white border border-emerald-400/30 text-xs font-bold transition flex items-center gap-1.5 active:scale-95"
                                       title="Kirim pesan langsung ke WhatsApp pelapor">
                                        <i class="ph-bold ph-whatsapp-logo text-sm"></i>
                                        <span>Chat WhatsApp Pelapor</span>
                                    </a>
                                @endif

                                {{-- Tombol Update Status & Beri Solusi --}}
                                <button type="button" 
                                        @click="openUpdateModal({{ json_encode($ticket) }})"
                                        class="px-3.5 py-2 rounded-xl bg-sky-500 hover:bg-sky-400 text-white font-bold text-xs flex items-center gap-1.5 shadow-md transition active:scale-95 cursor-pointer">
                                    <i class="ph-bold ph-check-square-offset text-sm"></i>
                                    <span>Update Status & Solusi</span>
                                </button>

                                {{-- Tombol Hapus Tiket --}}
                                <form action="{{ route('admin.help-tickets.destroy', $ticket->id) }}" 
                                      method="POST" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus tiket bantuan ini?');"
                                      class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="p-2 rounded-xl bg-rose-500/10 hover:bg-rose-500 text-rose-400 hover:text-white border border-rose-500/20 text-xs transition active:scale-95 cursor-pointer"
                                            title="Hapus Tiket">
                                        <i class="ph-bold ph-trash text-base"></i>
                                    </button>
                                </form>
                            </div>
                        </div>

                    </div>
                @endforeach

                {{-- PAGINATION --}}
                <div class="pt-4">
                    {{ $tickets->links() }}
                </div>
            </div>
        @else
            {{-- EMPTY STATE --}}
            <div class="p-12 rounded-3xl bg-[#031d3d]/50 border border-white/10 text-center space-y-4">
                <div class="w-20 h-20 rounded-full bg-sky-500/10 text-sky-400 border border-sky-400/20 mx-auto flex items-center justify-center text-3xl shadow-inner">
                    <i class="ph-bold ph-chat-circle-check"></i>
                </div>
                <div>
                    <h3 class="text-lg font-black text-white">Tidak Ada Tiket Kendala Ditemukan</h3>
                    <p class="text-xs text-slate-400 max-w-md mx-auto mt-1">
                        @if(request()->anyFilled(['search', 'reporter_type', 'status', 'category']))
                            Tidak ada tiket yang cocok dengan kriteria filter Anda. Coba reset filter.
                        @else
                            Bagus sekali! Saat ini belum ada ajuan kendala yang masuk dari siswa maupun guru. Sistem berjalan lancar.
                        @endif
                    </p>
                </div>
                @if(request()->anyFilled(['search', 'reporter_type', 'status', 'category']))
                    <a href="{{ route('admin.help-tickets.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs transition">
                        <i class="ph-bold ph-arrow-counter-clockwise"></i> Reset Filter
                    </a>
                @endif
            </div>
        @endif

        {{-- ================================================================= --}}
        {{-- MODAL 1: UPDATE STATUS & CATATAN SOLUSI                           --}}
        {{-- ================================================================= --}}
        <div x-show="isUpdateModalOpen" 
             style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto"
             role="dialog" 
             aria-modal="true">
            
            <div class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity" 
                 @click="isUpdateModalOpen = false"></div>

            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative w-full max-w-lg rounded-3xl bg-[#031d3d] border border-white/15 p-6 shadow-2xl text-white space-y-5">
                    
                    <div class="flex items-center justify-between pb-3 border-b border-white/10">
                        <div class="flex items-center gap-2.5">
                            <div class="w-10 h-10 rounded-xl bg-sky-500/20 text-sky-400 border border-sky-400/30 flex items-center justify-center text-xl">
                                <i class="ph-bold ph-check-square-offset"></i>
                            </div>
                            <div>
                                <h3 class="font-black text-white text-base">Tindak Lanjut Tiket Kendala</h3>
                                <p class="text-xs text-slate-400" x-text="'Kode: ' + currentTicket?.ticket_code"></p>
                            </div>
                        </div>
                        <button type="button" @click="isUpdateModalOpen = false" class="text-slate-400 hover:text-white">
                            <i class="ph-bold ph-x text-lg"></i>
                        </button>
                    </div>

                    <form :action="'{{ url('admin/help-tickets') }}/' + currentTicket?.id" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        {{-- Ringkasan Pelapor --}}
                        <div class="p-3 rounded-xl bg-[#021124] border border-white/10 text-xs space-y-1">
                            <p class="text-slate-400">Pelapor: <strong class="text-white" x-text="currentTicket?.name"></strong> (<span x-text="currentTicket?.class_or_role || 'Siswa/Guru'"></span>)</p>
                            <p class="text-slate-400">Kendala: <strong class="text-sky-300" x-text="currentTicket?.category"></strong></p>
                        </div>

                        {{-- Pilihan Status --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-300 mb-1.5">Ubah Status Penanganan *</label>
                            <select name="status" 
                                    x-model="updateFormStatus"
                                    class="w-full px-3.5 py-2.5 rounded-xl bg-[#021124] border border-white/15 text-white font-bold text-xs focus:border-sky-400 outline-none">
                                <option value="pending">⏳ Menunggu Respon (Pending)</option>
                                <option value="in_progress">⚙️ Sedang Ditangani (In Progress)</option>
                                <option value="resolved">✅ Selesai Ditangani (Resolved)</option>
                                <option value="rejected">❌ Ditolak / Tidak Memerlukan Aksi</option>
                            </select>
                        </div>

                        {{-- Catatan Penanganan / Solusi --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-300 mb-1.5">
                                Catatan Solusi / Tindakan Admin
                            </label>
                            <textarea name="admin_notes" 
                                      x-model="updateFormNotes"
                                      rows="4"
                                      placeholder="Contoh: Sesi CBT telah di-reset oleh proktor. Siswa sudah dicoba login ulang dan berhasil masuk."
                                      class="w-full px-3.5 py-2.5 rounded-xl bg-[#021124] border border-white/15 text-white font-medium text-xs focus:border-sky-400 outline-none placeholder:text-slate-500"></textarea>
                            <p class="text-[11px] text-slate-400 mt-1">
                                Catatan ini akan terdokumentasi rapi agar admin lain atau guru piket memahami solusi yang diberikan.
                            </p>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-3 border-t border-white/10">
                            <button type="button" 
                                    @click="isUpdateModalOpen = false" 
                                    class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/15 text-white font-bold text-xs transition">
                                Batal
                            </button>
                            <button type="submit" 
                                    class="px-5 py-2 rounded-xl bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white font-bold text-xs shadow-lg transition">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        {{-- ================================================================= --}}
        {{-- MODAL 2: CATAT KENDALA MANUAL (DARI SISWA/GURU PIKET)             --}}
        {{-- ================================================================= --}}
        <div x-show="isCreateModalOpen" 
             style="display: none;"
             class="fixed inset-0 z-50 overflow-y-auto"
             role="dialog" 
             aria-modal="true">
            
            <div class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity" 
                 @click="isCreateModalOpen = false"></div>

            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative w-full max-w-lg rounded-3xl bg-[#031d3d] border border-white/15 p-6 shadow-2xl text-white space-y-5">
                    
                    <div class="flex items-center justify-between pb-3 border-b border-white/10">
                        <div class="flex items-center gap-2.5">
                            <div class="w-10 h-10 rounded-xl bg-sky-500/20 text-sky-400 border border-sky-400/30 flex items-center justify-center text-xl">
                                <i class="ph-bold ph-plus-circle"></i>
                            </div>
                            <div>
                                <h3 class="font-black text-white text-base">Catat Kendala Manual</h3>
                                <p class="text-xs text-slate-400">Input laporan lisan dari Siswa atau Guru</p>
                            </div>
                        </div>
                        <button type="button" @click="isCreateModalOpen = false" class="text-slate-400 hover:text-white">
                            <i class="ph-bold ph-x text-lg"></i>
                        </button>
                    </div>

                    <form action="{{ route('help-tickets.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-300 mb-1">Peran Pelapor *</label>
                                <select name="reporter_type" required class="w-full px-3 py-2 rounded-xl bg-[#021124] border border-white/15 text-white font-bold text-xs focus:border-sky-400 outline-none">
                                    <option value="siswa">Siswa</option>
                                    <option value="guru">Guru / PTK</option>
                                    <option value="umum">Umum</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-300 mb-1">Nama Lengkap *</label>
                                <input type="text" name="name" required placeholder="Nama siswa/guru" class="w-full px-3 py-2 rounded-xl bg-[#021124] border border-white/15 text-white text-xs font-semibold focus:border-sky-400 outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-300 mb-1">NISN / NIP</label>
                                <input type="text" name="identity_number" placeholder="Nomor NISN/NIP" class="w-full px-3 py-2 rounded-xl bg-[#021124] border border-white/15 text-white text-xs font-mono focus:border-sky-400 outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-300 mb-1">Kelas / Mapel</label>
                                <input type="text" name="class_or_role" placeholder="Contoh: Kelas 8A / Guru IPA" class="w-full px-3 py-2 rounded-xl bg-[#021124] border border-white/15 text-white text-xs font-semibold focus:border-sky-400 outline-none">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-300 mb-1">No. WhatsApp</label>
                                <input type="text" name="phone" placeholder="08xxxxxxxxxx" class="w-full px-3 py-2 rounded-xl bg-[#021124] border border-white/15 text-white text-xs focus:border-sky-400 outline-none">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-300 mb-1">Kategori Kendala *</label>
                                <select name="category" required class="w-full px-3 py-2 rounded-xl bg-[#021124] border border-white/15 text-white font-bold text-xs focus:border-sky-400 outline-none">
                                    <option value="Kendala Ujian CBT / Reset Token">Kendala Ujian CBT / Reset Token</option>
                                    <option value="Gagal Login / Lupa Akun">Gagal Login / Lupa Akun</option>
                                    <option value="Kendala Presensi / Scan Wajah">Kendala Presensi / Scan Wajah</option>
                                    <option value="Kendala Input Nilai / Rapor (Guru)">Kendala Input Nilai / Rapor (Guru)</option>
                                    <option value="Kendala Jurnal Mengajar / Inval (Guru)">Kendala Jurnal Mengajar / Inval (Guru)</option>
                                    <option value="Error Halaman / Tampilan Blank">Error Halaman / Tampilan Blank</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-300 mb-1">Deskripsi Kendala *</label>
                            <textarea name="description" required rows="3" placeholder="Ceritakan detail kendala yang dialami..." class="w-full px-3 py-2 rounded-xl bg-[#021124] border border-white/15 text-white text-xs focus:border-sky-400 outline-none"></textarea>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-300 mb-1">Foto Bukti / Screenshot (Opsional)</label>
                            <input type="file" name="screenshot" accept="image/*" class="w-full text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-sky-500/20 file:text-sky-300 hover:file:bg-sky-500/30">
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-3 border-t border-white/10">
                            <button type="button" @click="isCreateModalOpen = false" class="px-4 py-2 rounded-xl bg-white/10 hover:bg-white/15 text-white font-bold text-xs transition">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2 rounded-xl bg-sky-500 hover:bg-sky-400 text-white font-bold text-xs shadow-lg transition">
                                Simpan Kendala
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>

        {{-- ================================================================= --}}
        {{-- MODAL 3: LIGHTBOX PREVIEW SCREENSHOT                              --}}
        {{-- ================================================================= --}}
        <div x-show="lightboxUrl" 
             style="display: none;"
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-black/90 backdrop-blur-md"
             @click.self="lightboxUrl = null"
             @keydown.escape.window="lightboxUrl = null">
            
            <div class="relative max-w-4xl max-h-[90vh] overflow-hidden rounded-2xl border border-white/20 shadow-2xl bg-[#021124]">
                <button type="button" 
                        @click="lightboxUrl = null" 
                        class="absolute top-3 right-3 w-9 h-9 rounded-full bg-black/60 text-white hover:bg-rose-500 flex items-center justify-center transition z-10">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
                <img :src="lightboxUrl" alt="Preview Kendala" class="w-full h-auto max-h-[85vh] object-contain">
            </div>
        </div>

    </div>

    {{-- SCRIPT ALPINE JS MANAGER --}}
    <script>
        function helpTicketsManager() {
            return {
                isUpdateModalOpen: false,
                isCreateModalOpen: false,
                currentTicket: null,
                updateFormStatus: 'pending',
                updateFormNotes: '',
                lightboxUrl: null,

                init() {
                    window.addEventListener('open-create-ticket-modal', () => {
                        this.isCreateModalOpen = true;
                    });
                },

                openUpdateModal(ticket) {
                    this.currentTicket = ticket;
                    this.updateFormStatus = ticket.status || 'pending';
                    this.updateFormNotes = ticket.admin_notes || '';
                    this.isUpdateModalOpen = true;
                },

                openLightbox(url) {
                    this.lightboxUrl = url;
                }
            };
        }
    </script>
</x-app-layout>
