<x-app-layout>
    {{-- Hapus @section('content') dan @extends --}}
    
    <div class="space-y-6 animate-in fade-in duration-500">
        
        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-800">Monitoring Literasi</h1>
                <p class="text-slate-500 text-sm">Pantau aktivitas membaca siswa di rumah.</p>
            </div>
            
            {{-- Filter --}}
            <form method="GET" class="flex flex-col sm:flex-row gap-2">
                <select name="class_id" class="px-4 py-2 rounded-xl border-none bg-white shadow-sm text-sm font-bold text-slate-600 focus:ring-2 focus:ring-teal-200" onchange="this.form.submit()">
                    <option value="">Semua Kelas</option>
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                    @endforeach
                </select>
                <input type="date" name="date" value="{{ request('date') }}" class="px-4 py-2 rounded-xl border-none bg-white shadow-sm text-sm font-bold text-slate-600 focus:ring-2 focus:ring-teal-200" onchange="this.form.submit()">
                <a href="{{ route('admin.literacy.index') }}" class="px-4 py-2 bg-slate-200 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-300 transition flex items-center justify-center">
                    Reset
                </a>
            </form>
        </div>

        {{-- CONTENT --}}
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            @if($journals->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100 text-xs uppercase tracking-wider text-slate-500">
                                <th class="p-6 font-bold">Siswa</th>
                                <th class="p-6 font-bold">Buku & Ringkasan</th>
                                <th class="p-6 font-bold text-center">Bukti</th>
                                <th class="p-6 font-bold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($journals as $item)
                            <tr class="group hover:bg-slate-50/50 transition">
                                <td class="p-6 align-top">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center font-bold text-sm">
                                            {{ substr($item->student->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800 text-sm">{{ $item->student->name }}</p>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase">{{ $item->student->schoolClass->name ?? 'No Kelas' }}</p>
                                            <p class="text-[10px] text-slate-400 mt-1">
                                                {{ \Carbon\Carbon::parse($item->created_at)->format('d M H:i') }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-6 align-top max-w-md">
                                    <div class="mb-1">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-100 text-slate-600 text-[10px] font-bold border border-slate-200">
                                            <i class="ph-bold ph-book-open"></i> {{ $item->pages_read }} Hal
                                        </span>
                                    </div>
                                    <h4 class="font-bold text-slate-800 text-sm mb-1">{{ $item->title }}</h4>
                                    <p class="text-xs text-slate-500 italic mb-2">Penulis: {{ $item->author ?? '-' }}</p>
                                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 text-xs text-slate-600 leading-relaxed">
                                        "{{ Str::limit($item->summary, 150) }}"
                                    </div>
                                </td>
                                <td class="p-6 align-top text-center">
                                    @if($item->proof_image)
                                        <a href="{{ asset('storage/'.$item->proof_image) }}" target="_blank" class="inline-block relative group/img">
                                            <img src="{{ asset('storage/'.$item->proof_image) }}" class="h-16 w-16 object-cover rounded-lg border border-slate-200 shadow-sm transition transform group-hover/img:scale-105">
                                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover/img:opacity-100 rounded-lg flex items-center justify-center transition">
                                                <i class="ph-bold ph-eye text-white"></i>
                                            </div>
                                        </a>
                                    @else
                                        <span class="text-slate-300 text-xs italic">Tanpa Foto</span>
                                    @endif
                                </td>
                                <td class="p-6 align-top text-right">
                                    <div class="flex justify-end gap-2">
                                        @if(!$item->verified_at)
                                            <form action="{{ route('admin.literacy.verify', $item->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-4 py-2 bg-teal-500 hover:bg-teal-600 text-white rounded-xl text-xs font-bold shadow-lg shadow-teal-200 transition flex items-center gap-2">
                                                    <i class="ph-bold ph-check"></i> Verifikasi
                                                </button>
                                            </form>
                                        @else
                                            <button disabled class="px-4 py-2 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-xl text-xs font-bold cursor-default flex items-center gap-2">
                                                <i class="ph-fill ph-check-circle"></i> Selesai
                                            </button>
                                        @endif

                                        <form action="{{ route('admin.literacy.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus jurnal ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 bg-rose-50 text-rose-500 hover:bg-rose-100 rounded-xl transition border border-rose-100">
                                                <i class="ph-bold ph-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-6 border-t border-slate-50">
                    {{ $journals->withQueryString()->links() }}
                </div>
            @else
                <div class="text-center py-20">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                        <i class="ph-duotone ph-books text-4xl"></i>
                    </div>
                    <h3 class="font-bold text-slate-700">Belum ada data jurnal</h3>
                    <p class="text-xs text-slate-400 mt-1">Siswa belum menginput jurnal literasi sesuai filter.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>