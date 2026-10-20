<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between print:hidden">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Hasil Ujian CBT Global') }}
            </h2>
        </div>
    </x-slot>

    <div class="min-h-screen bg-[#020b18] text-slate-100 relative overflow-hidden py-8 sm:py-10 font-sans">
        {{-- Ambient Glow Effects --}}
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-600/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/3 right-10 w-96 h-96 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8 relative z-10">

            {{-- HERO SECTION ELEVATE DARK GLASS --}}
            <div class="relative rounded-[2.5rem] bg-gradient-to-r from-[#031d3d]/90 via-[#021124]/90 to-[#020b18]/90 p-8 text-white shadow-2xl backdrop-blur-xl border border-white/10 overflow-hidden">
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <a href="{{ route('cbt.index') }}" class="text-xs font-bold text-sky-400 hover:text-white transition flex items-center gap-1 bg-white/5 hover:bg-white/10 px-3 py-1.5 rounded-full border border-white/10 backdrop-blur-md">
                                <i class="ph-bold ph-arrow-left"></i> Dashboard CBT
                            </a>
                            <span class="text-slate-600 text-xs">•</span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Hasil Seluruh Ujian</span>
                        </div>
                        <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-white flex items-center gap-3">
                            <i class="ph-fill ph-chart-bar text-sky-400"></i> Rekap Nilai Global
                        </h1>
                        <p class="text-slate-300 text-sm mt-1 max-w-xl">
                            Daftar riwayat penyelesaian seluruh ujian CBT dari semua mata pelajaran dan kelas.
                        </p>
                    </div>

                    <div class="flex items-center gap-3">
                        <span class="px-4 py-2 rounded-2xl bg-white/5 border border-white/10 text-xs font-bold text-slate-300">
                            Total Selesai: <span class="text-sky-400 font-black">{{ $results->total() }}</span>
                        </span>
                    </div>
                </div>
            </div>

            {{-- TABEL DATA HASIL --}}
            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-300">
                        <thead class="text-[10px] text-slate-400 uppercase tracking-wider bg-white/5 border-b border-white/10">
                            <tr>
                                <th class="px-6 py-4">No</th>
                                <th class="px-6 py-4">Siswa</th>
                                <th class="px-6 py-4">Kelas</th>
                                <th class="px-6 py-4">Judul Ujian</th>
                                <th class="px-6 py-4">Mata Pelajaran</th>
                                <th class="px-6 py-4 text-center">Nilai</th>
                                <th class="px-6 py-4 text-center">Waktu Selesai</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($results as $index => $row)
                                <tr class="hover:bg-white/[0.02] transition">
                                    <td class="px-6 py-4 font-mono text-xs text-slate-400">
                                        {{ $results->firstItem() + $index }}
                                    </td>
                                    <td class="px-6 py-4 font-bold text-white">
                                        {{ $row->student_name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-white/5 border border-white/10 text-slate-300">
                                            {{ $row->class_name ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-medium text-slate-200">
                                        {{ $row->exam_title }}
                                    </td>
                                    <td class="px-6 py-4 text-slate-400 text-xs">
                                        {{ $row->subject_name ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 rounded-xl font-black text-sm {{ ($row->total_score ?? 0) >= 75 ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20' : 'bg-rose-500/10 text-rose-400 border border-rose-500/20' }}">
                                            {{ number_format($row->total_score ?? 0, 1) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center font-mono text-xs text-slate-400">
                                        {{ \Carbon\Carbon::parse($row->created_at)->translatedFormat('d M Y H:i') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-16 text-slate-400">
                                        <i class="ph-duotone ph-clipboard-text text-5xl mb-3 block text-slate-600 mx-auto"></i>
                                        <p class="font-bold">Belum ada riwayat ujian yang selesai.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($results->hasPages())
                    <div class="p-6 border-t border-white/10">
                        {{ $results->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
