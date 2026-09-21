@extends('layouts.public')

@section('content')
<div class="min-h-screen bg-[#020b18] font-sans text-slate-100 pb-20 pt-24">
    <div class="w-full max-w-6xl mx-auto px-4 sm:px-6">
        
        <div class="flex justify-between items-center mb-8">
            <div>
                <a href="{{ route('portal.show', Auth::guard('student')->id()) }}" class="inline-flex items-center px-5 py-2.5 bg-white/10 hover:bg-white/20 border border-white/10 text-[#56bbf1] hover:text-white text-sm font-bold rounded-xl transition-all shadow-lg backdrop-blur-sm hover:-translate-y-1">
                    <i class="ph-bold ph-arrow-left mr-2"></i> Kembali ke halaman profil
                </a>
                <h1 class="text-2xl md:text-3xl font-extrabold text-white mt-4">Riwayat Konseling</h1>
                <p class="text-slate-400 text-sm mt-1">Daftar konsultasi yang pernah kamu ajukan.</p>
            </div>
            <a href="{{ route('student.bk.create') }}" class="inline-flex items-center px-5 py-2.5 bg-gradient-to-r from-[#56bbf1] to-[#3b82f6] text-white text-sm font-bold rounded-xl transition-all shadow-lg shadow-[#56bbf1]/20 hover:brightness-110 hover:-translate-y-1">
                <i class="ph-bold ph-plus mr-2"></i> Buat Baru
            </a>
        </div>

        @if(session('success'))
            <div class="mb-8 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl flex items-start gap-3">
                <i class="ph-fill ph-check-circle text-emerald-400 text-xl mt-0.5"></i>
                <div>
                    <h4 class="font-bold text-emerald-300 text-sm">Berhasil!</h4>
                    <p class="text-emerald-300 text-sm">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] backdrop-blur-xl rounded-3xl shadow-2xl border border-white/10 overflow-hidden">
            @if($histories->count() > 0)
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-white/5 border-b border-white/10">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Topik</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Tanggal Pengajuan</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Jadwal / Guru</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($histories as $item)
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-[#56bbf1]/10 text-[#56bbf1] border border-[#56bbf1]/20 mb-1">
                                        {{ $item->category->name }}
                                    </span>
                                    
                                    @if($item->is_system_generated ?? false)
                                        @if(str_contains($item->initial_message, 'PELANGGARAN'))
                                            <span class="inline-flex items-center px-2 py-1 ml-1 rounded text-[10px] font-black bg-rose-500/20 text-rose-300 border border-rose-500/30 uppercase tracking-wider mb-1 animate-pulse">
                                                <i class="ph-fill ph-warning mr-1"></i> Panggilan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 ml-1 rounded text-[10px] font-black bg-[#56bbf1]/20 text-[#56bbf1] border border-[#56bbf1]/30 uppercase tracking-wider mb-1">
                                                <i class="ph-fill ph-medal mr-1"></i> Apresiasi
                                            </span>
                                        @endif
                                    @endif

                                    <p class="text-sm text-slate-300 truncate max-w-[200px]" title="{{ $item->initial_message }}">
                                        {{ Str::limit($item->initial_message, 40) }}
                                    </p>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-300 font-medium">
                                    {{ $item->created_at->translatedFormat('d F Y') }}
                                    <span class="block text-xs text-slate-400">{{ $item->created_at->format('H:i') }} WIB</span>
                                </td>
                                <td class="px-6 py-4">
                                    @if($item->scheduled_at)
                                        <div class="flex flex-col">
                                            <span class="text-sm font-bold text-[#56bbf1]">{{ $item->scheduled_at->translatedFormat('d M, H:i') }}</span>
                                            <span class="text-xs text-slate-400 mt-0.5">Oleh: {{ $item->teacher->name ?? 'Guru BK' }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400 italic bg-white/5 border border-white/10 px-2 py-1 rounded">Belum ditentukan</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $statusClass = match($item->status) {
                                            'pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                            'approved' => 'bg-[#56bbf1]/10 text-[#56bbf1] border-[#56bbf1]/20',
                                            'ongoing' => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20',
                                            'finished' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                            'rejected' => 'bg-rose-500/10 text-rose-400 border-rose-500/20',
                                            default => 'bg-slate-500/10 text-slate-400 border-slate-500/20'
                                        };
                                        $statusLabel = match($item->status) {
                                            'pending' => 'Menunggu',
                                            'approved' => 'Disetujui',
                                            'ongoing' => 'Berlangsung',
                                            'finished' => 'Selesai',
                                            'rejected' => 'Ditolak',
                                            default => $item->status
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $statusClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('student.bk.show', $item->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white/10 border border-white/10 text-slate-300 hover:text-[#56bbf1] hover:border-[#56bbf1]/40 hover:bg-white/15 transition-all shadow-sm">
                                        <i class="ph-bold ph-caret-right"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-white/10 bg-white/5">
                    {{ $histories->links() }}
                </div>
            @else
                <div class="text-center py-20 px-4">
                    <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400 border border-white/10">
                        <i class="ph-duotone ph-chats-teardrop text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white">Belum Ada Riwayat</h3>
                    <p class="mt-2 text-slate-400 text-sm max-w-sm mx-auto">Kamu belum pernah mengajukan sesi konseling. Jangan ragu untuk bercerita jika ada masalah.</p>
                    <a href="{{ route('student.bk.create') }}" class="inline-block mt-6 px-6 py-2.5 bg-gradient-to-r from-[#56bbf1] to-[#3b82f6] text-white font-bold rounded-xl text-sm hover:brightness-110 transition-all shadow-lg shadow-[#56bbf1]/20">
                        Mulai Konsultasi
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection