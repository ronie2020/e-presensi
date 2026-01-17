<div class="space-y-6 font-sans text-slate-800">
    
    <!-- Header Tab -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-6 md:p-8 rounded-[2rem] shadow-xl shadow-slate-200/40 border border-slate-100 relative overflow-hidden">
        <!-- Dekorasi Background Halus -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-full blur-3xl -mr-16 -mt-16 opacity-50 pointer-events-none"></div>

        <div class="relative z-10">
            <h3 class="text-xl md:text-2xl font-black text-slate-800 flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl shadow-sm border border-blue-100">
                    <i class="ph-duotone ph-heart-beat"></i>
                </div>
                Layanan BK
            </h3>
            <p class="text-slate-500 font-medium text-sm mt-2 max-w-md leading-relaxed">
                Ruang aman untuk bercerita, konsultasi, dan pengembangan diri. Privasi kamu adalah prioritas kami.
            </p>
        </div>
        
        <a href="{{ route('student.bk.create') }}" class="relative z-10 inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-blue-500/30 group hover:-translate-y-1">
            <i class="ph-bold ph-plus-circle text-xl group-hover:scale-110 transition-transform"></i>
            Ajukan Konseling
        </a>
    </div>

    <!-- Statistik Ringkas -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center gap-2 mb-2">
                <div class="p-1.5 bg-slate-50 rounded-lg text-slate-400">
                    <i class="ph-bold ph-files"></i>
                </div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Sesi</p>
            </div>
            <p class="text-3xl font-black text-slate-800">{{ $bkSessions->count() }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-blue-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <i class="ph-duotone ph-calendar-check text-5xl text-blue-600"></i>
            </div>
            <div class="flex items-center gap-2 mb-2">
                <div class="p-1.5 bg-blue-50 rounded-lg text-blue-600">
                    <i class="ph-bold ph-calendar-check"></i>
                </div>
                <p class="text-xs font-bold text-blue-400 uppercase tracking-wider">Akan Datang</p>
            </div>
            <p class="text-3xl font-black text-slate-800">{{ $bkSessions->where('status', 'approved')->count() }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-amber-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <i class="ph-duotone ph-hourglass text-5xl text-amber-600"></i>
            </div>
            <div class="flex items-center gap-2 mb-2">
                <div class="p-1.5 bg-amber-50 rounded-lg text-amber-600">
                    <i class="ph-bold ph-hourglass"></i>
                </div>
                <p class="text-xs font-bold text-amber-400 uppercase tracking-wider">Menunggu</p>
            </div>
            <p class="text-3xl font-black text-slate-800">{{ $bkSessions->where('status', 'pending')->count() }}</p>
        </div>
        <div class="bg-white p-5 rounded-2xl border border-emerald-100 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <i class="ph-duotone ph-check-circle text-5xl text-emerald-600"></i>
            </div>
            <div class="flex items-center gap-2 mb-2">
                <div class="p-1.5 bg-emerald-50 rounded-lg text-emerald-600">
                    <i class="ph-bold ph-check-circle"></i>
                </div>
                <p class="text-xs font-bold text-emerald-400 uppercase tracking-wider">Selesai</p>
            </div>
            <p class="text-3xl font-black text-slate-800">{{ $bkSessions->where('status', 'finished')->count() }}</p>
        </div>
    </div>

    <!-- Daftar Riwayat -->
    <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <h4 class="font-bold text-slate-800 flex items-center gap-2">
                <i class="ph-duotone ph-list-dashes text-blue-500 text-lg"></i>
                Riwayat Konsultasi
            </h4>
            <a href="{{ route('student.bk.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-700 hover:underline">
                Lihat Semua
            </a>
        </div>
        
        @if($bkSessions->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 text-xs uppercase font-bold text-slate-400 tracking-wider">
                        <tr>
                            <th class="px-6 py-4 rounded-tl-2xl">Topik</th>
                            <th class="px-6 py-4">Waktu Pengajuan</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 rounded-tr-2xl text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @foreach($bkSessions->take(5) as $session)
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-xl shrink-0 border border-slate-100
                                        {{ $session->method == 'online' ? 'bg-indigo-50 text-indigo-600' : 'bg-blue-50 text-blue-600' }}">
                                        @if($session->method == 'online') 
                                            <i class="ph-duotone ph-chat-text"></i>
                                        @else
                                            <i class="ph-duotone ph-users-three"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800 text-sm group-hover:text-blue-600 transition-colors">{{ $session->category->name }}</p>
                                        <p class="text-xs text-slate-400 mt-0.5 truncate max-w-[150px]">{{ $session->initial_message }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-700">{{ $session->created_at->translatedFormat('d M Y') }}</span>
                                    @if($session->scheduled_at)
                                        <span class="text-xs text-blue-600 font-bold mt-0.5 flex items-center gap-1">
                                            <i class="ph-bold ph-clock"></i> {{ $session->scheduled_at->format('H:i') }}
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Belum dijadwalkan</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusStyle = match($session->status) {
                                        'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                        'approved' => 'bg-blue-100 text-blue-700 border-blue-200',
                                        'ongoing' => 'bg-purple-100 text-purple-700 border-purple-200',
                                        'finished' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                        'rejected' => 'bg-rose-100 text-rose-700 border-rose-200',
                                        default => 'bg-slate-100 text-slate-600'
                                    };
                                    $statusLabel = match($session->status) {
                                        'pending' => 'Menunggu',
                                        'approved' => 'Disetujui',
                                        'ongoing' => 'Berlangsung',
                                        'finished' => 'Selesai',
                                        'rejected' => 'Ditolak',
                                        default => '-'
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wide border {{ $statusStyle }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('student.bk.show', $session->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-xl bg-slate-100 text-slate-400 hover:bg-blue-600 hover:text-white hover:shadow-lg hover:shadow-blue-500/30 transition-all duration-300">
                                    <i class="ph-bold ph-caret-right text-lg"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-16 px-4">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300 border-4 border-slate-100">
                    <i class="ph-duotone ph-chats-teardrop text-5xl"></i>
                </div>
                <h4 class="text-xl font-black text-slate-800">Belum Ada Riwayat</h4>
                <p class="text-slate-500 text-sm mt-2 max-w-sm mx-auto mb-8">Jangan ragu untuk berkonsultasi mengenai masalah akademik maupun non-akademik.</p>
                <a href="{{ route('student.bk.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-white border border-slate-200 text-slate-700 font-bold rounded-xl text-sm hover:border-blue-500 hover:text-blue-600 hover:shadow-md transition-all">
                    Mulai Konsultasi
                </a>
            </div>
        @endif
    </div>
</div>