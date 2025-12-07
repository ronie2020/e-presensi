<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('CBT Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6 md:py-12"> <!-- Padding lebih kecil di HP -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl md:rounded-lg p-4 md:p-6">
                
                <div class="space-y-6 md:space-y-8">
                    <!-- Header Page: Flex column di HP, Row di Desktop -->
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
                        <div>
                            <h2 class="text-xl md:text-2xl font-bold text-slate-800">CBT & Ujian Online</h2>
                            <p class="text-sm md:text-base text-slate-500 mt-1">Manajemen jadwal ujian, bank soal, dan pemantauan hasil.</p>
                        </div>
                        <div class="w-full md:w-auto">
                            <a href="{{ route('cbt.create') }}" class="w-full md:w-auto justify-center px-4 py-3 md:py-2 bg-blue-600 text-white rounded-xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-500/30 flex items-center gap-2">
                                <i class="ph-bold ph-plus"></i> Buat Jadwal Ujian
                            </a>
                        </div>
                    </div>

                    <!-- Statistik Ringkas: Grid 2 kolom di HP, 4 di Desktop -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-6">
                        <div class="bg-white p-4 md:p-6 rounded-2xl border border-slate-100 shadow-sm">
                            <div class="flex justify-between items-start mb-2 md:mb-4">
                                <div class="p-2 md:p-3 bg-blue-50 text-blue-600 rounded-xl"><i class="ph-fill ph-monitor-play text-xl md:text-2xl"></i></div>
                                <span class="px-2 py-1 bg-green-100 text-green-700 text-[10px] md:text-xs font-bold rounded-lg">Live</span>
                            </div>
                            <h3 class="text-2xl md:text-3xl font-black text-slate-800">{{ $stats['active_exams'] ?? 0 }}</h3>
                            <p class="text-xs md:text-sm text-slate-500 font-medium mt-1">Ujian Aktif</p>
                        </div>
                        <div class="bg-white p-4 md:p-6 rounded-2xl border border-slate-100 shadow-sm">
                            <div class="flex justify-between items-start mb-2 md:mb-4">
                                <div class="p-2 md:p-3 bg-purple-50 text-purple-600 rounded-xl"><i class="ph-fill ph-list-numbers text-xl md:text-2xl"></i></div>
                            </div>
                            <h3 class="text-2xl md:text-3xl font-black text-slate-800">{{ $stats['total_questions'] ?? 0 }}</h3>
                            <p class="text-xs md:text-sm text-slate-500 font-medium mt-1">Total Soal</p>
                        </div>
                        <div class="bg-white p-4 md:p-6 rounded-2xl border border-slate-100 shadow-sm">
                            <div class="flex justify-between items-start mb-2 md:mb-4">
                                <div class="p-2 md:p-3 bg-orange-50 text-orange-600 rounded-xl"><i class="ph-fill ph-users text-xl md:text-2xl"></i></div>
                            </div>
                            <h3 class="text-2xl md:text-3xl font-black text-slate-800">{{ $stats['students_working'] ?? 0 }}</h3>
                            <p class="text-xs md:text-sm text-slate-500 font-medium mt-1">Siswa Mengerjakan</p>
                        </div>
                        <div class="bg-white p-4 md:p-6 rounded-2xl border border-slate-100 shadow-sm">
                            <div class="flex justify-between items-start mb-2 md:mb-4">
                                <div class="p-2 md:p-3 bg-emerald-50 text-emerald-600 rounded-xl"><i class="ph-fill ph-check-circle text-xl md:text-2xl"></i></div>
                            </div>
                            <h3 class="text-2xl md:text-3xl font-black text-slate-800">{{ number_format($stats['avg_score'] ?? 0, 1) }}</h3>
                            <p class="text-xs md:text-sm text-slate-500 font-medium mt-1">Rata-rata Nilai</p>
                        </div>
                    </div>

                    <!-- Container Jadwal -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="p-4 md:p-6 border-b border-slate-100 bg-slate-50/50">
                            <h3 class="font-bold text-lg text-slate-800">Jadwal Ujian Terbaru</h3>
                        </div>

                        <!-- TAMPILAN DESKTOP (TABEL BIASA) - Hidden di HP -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="w-full text-left text-sm text-slate-600">
                                <thead class="bg-slate-50 text-slate-800 font-bold uppercase text-xs">
                                    <tr>
                                        <th class="px-6 py-4">Nama Ujian</th>
                                        <th class="px-6 py-4 text-center">Token</th>
                                        <th class="px-6 py-4">Waktu</th>
                                        <th class="px-6 py-4">Status</th>
                                        <th class="px-6 py-4 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse($exams as $exam)
                                    <tr class="hover:bg-slate-50 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <span class="font-bold text-slate-800">{{ $exam->title }}</span>
                                                <span class="text-xs text-slate-500">{{ $exam->subject_name }} (Kelas {{ $exam->class_level }})</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($exam->token)
                                                <div class="flex items-center justify-center gap-2 group">
                                                    <span class="bg-slate-800 text-white font-mono font-bold px-3 py-1 rounded tracking-widest text-sm">
                                                        {{ $exam->token }}
                                                    </span>
                                                    <form action="{{ route('cbt.refresh_token', $exam->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        <button type="submit" title="Ganti Token" class="text-slate-300 hover:text-blue-600 transition">
                                                            <i class="ph-bold ph-arrows-clockwise"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            @else
                                                <span class="text-slate-400 italic text-xs">Tidak ada token</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                <span class="font-bold">{{ \Carbon\Carbon::parse($exam->start_time)->format('d M Y') }}</span>
                                                <span class="text-xs text-slate-400">
                                                    {{ \Carbon\Carbon::parse($exam->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($exam->end_time)->format('H:i') }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if($exam->is_active)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Aktif</span>
                                            @else
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-500">Non-Aktif</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right space-x-2">
                                            <a href="{{ route('cbt.questions.manage', $exam->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg text-xs font-bold hover:bg-indigo-100 transition">
                                                <i class="ph-bold ph-list-numbers"></i> Kelola
                                            </a>
                                            <a href="{{ route('cbt.monitoring', $exam->id) }}" class="text-slate-400 hover:text-blue-600 font-bold text-xs uppercase p-1" title="Monitor">
                                                <i class="ph-bold ph-desktop text-lg"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="5" class="px-6 py-8 text-center text-slate-500 italic">Belum ada jadwal ujian.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- TAMPILAN MOBILE (CARD VIEW) - Muncul di HP -->
                        <div class="md:hidden p-4 space-y-4 bg-slate-50">
                            @forelse($exams as $exam)
                                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
                                    <div class="flex justify-between items-start mb-3">
                                        <div>
                                            <h4 class="font-bold text-slate-800 text-lg leading-tight">{{ $exam->title }}</h4>
                                            <p class="text-xs text-slate-500 font-bold mt-1 uppercase">{{ $exam->subject_name }} &bull; Kelas {{ $exam->class_level }}</p>
                                        </div>
                                        @if($exam->is_active)
                                            <span class="px-2 py-1 rounded-lg text-[10px] font-bold bg-green-100 text-green-700">AKTIF</span>
                                        @else
                                            <span class="px-2 py-1 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-500">NON-AKTIF</span>
                                        @endif
                                    </div>

                                    <!-- Token & Waktu -->
                                    <div class="bg-slate-50 rounded-xl p-3 mb-4 space-y-2 border border-slate-100">
                                        <div class="flex justify-between items-center border-b border-slate-200 pb-2 mb-2">
                                            <span class="text-xs font-bold text-slate-400 uppercase">Token</span>
                                            <div class="flex items-center gap-3">
                                                <span class="font-mono font-bold text-slate-800 text-lg tracking-widest">{{ $exam->token ?? '-' }}</span>
                                                @if($exam->token)
                                                <form action="{{ route('cbt.refresh_token', $exam->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="w-6 h-6 flex items-center justify-center bg-blue-100 text-blue-600 rounded-full">
                                                        <i class="ph-bold ph-arrows-clockwise text-xs"></i>
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-xs font-bold text-slate-400 uppercase">Waktu</span>
                                            <span class="text-xs font-bold text-slate-700 text-right">
                                                {{ \Carbon\Carbon::parse($exam->start_time)->format('d M, H:i') }} <br>
                                                s.d {{ \Carbon\Carbon::parse($exam->end_time)->format('H:i') }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Action Buttons Full Width -->
                                    <div class="grid grid-cols-2 gap-3">
                                        <a href="{{ route('cbt.questions.manage', $exam->id) }}" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-50 text-indigo-700 rounded-xl text-sm font-bold hover:bg-indigo-100">
                                            <i class="ph-bold ph-list-numbers"></i> Kelola Soal
                                        </a>
                                        <a href="{{ route('cbt.monitoring', $exam->id) }}" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-600 rounded-xl text-sm font-bold hover:bg-slate-50">
                                            <i class="ph-bold ph-desktop"></i> Monitoring
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-slate-400 italic">Belum ada jadwal ujian.</div>
                            @endforelse
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>