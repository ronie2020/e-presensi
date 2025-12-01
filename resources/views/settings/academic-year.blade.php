<x-app-layout>
    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-3xl font-black text-slate-800 tracking-tight leading-tight flex items-center gap-3">
                    <i class="ph-duotone ph-calendar-check text-blue-600"></i> Tahun Ajaran
                </h1>
                <p class="text-slate-500 mt-2 text-lg">
                    Atur periode akademik dan semester yang aktif.
                </p>
            </div>

            {{-- Alert Sukses --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-emerald-100 rounded-full text-emerald-600">
                            <i class="ph-bold ph-check"></i>
                        </div>
                        <span class="font-bold">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="hover:bg-emerald-100 p-1 rounded-lg transition"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                {{-- FORM TAMBAH --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden relative sticky top-24">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-blue-500"></div>
                        <div class="p-6">
                            <h2 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                                <i class="ph-duotone ph-plus-circle text-blue-500"></i> Buat Periode Baru
                            </h2>
                            <form action="{{ route('settings.academic.store') }}" method="POST" class="space-y-5">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Tahun Ajaran</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                            <i class="ph-bold ph-calendar"></i>
                                        </div>
                                        <input type="text" name="name" placeholder="Contoh: 2025/2026" required 
                                               class="w-full pl-10 pr-4 py-3 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-bold text-slate-700 transition-colors placeholder:font-normal">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Semester</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                            <i class="ph-bold ph-clock"></i>
                                        </div>
                                        <select name="semester" class="w-full pl-10 pr-10 py-3 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-medium text-slate-700 transition-colors appearance-none cursor-pointer">
                                            <option value="Ganjil">Ganjil (1)</option>
                                            <option value="Genap">Genap (2)</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-400">
                                            <i class="ph-bold ph-caret-down"></i>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="w-full py-3 px-6 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/30 flex items-center justify-center gap-2 transform active:scale-[0.98]">
                                    <i class="ph-bold ph-floppy-disk"></i>
                                    Simpan Data
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- DAFTAR TAHUN AJARAN --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                        <div class="p-6 border-b border-slate-50 flex justify-between items-center">
                            <h2 class="text-lg font-bold text-slate-800">Riwayat Tahun Ajaran</h2>
                            <span class="bg-slate-100 text-xs font-bold px-3 py-1 rounded-full text-slate-500 border border-slate-200">{{ $years->count() }} Periode</span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm text-slate-600">
                                <thead class="bg-slate-50 text-xs font-bold text-slate-400 uppercase">
                                    <tr>
                                        <th class="px-6 py-4">Tahun & Semester</th>
                                        <th class="px-6 py-4">Status</th>
                                        <th class="px-6 py-4 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @foreach($years as $year)
                                        <tr class="group hover:bg-slate-50/50 transition-colors {{ $year->is_active ? 'bg-blue-50/30' : '' }}">
                                            <td class="px-6 py-4">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg {{ $year->is_active ? 'bg-blue-100 text-blue-600' : 'bg-slate-100 text-slate-400' }}">
                                                        <i class="ph-duotone ph-calendar-blank"></i>
                                                    </div>
                                                    <div>
                                                        <p class="font-bold text-slate-800 text-sm">{{ $year->name }}</p>
                                                        <p class="text-xs text-slate-500">Semester {{ $year->semester }}</p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4">
                                                @if($year->is_active)
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-100">
                                                        <span class="relative flex h-2 w-2">
                                                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                                          <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                                        </span>
                                                        Sedang Aktif
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-500 text-xs font-bold border border-slate-200">
                                                        Tidak Aktif
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 text-right">
                                                <div class="flex justify-end gap-2">
                                                    @if(!$year->is_active)
                                                        <form action="{{ route('settings.academic.activate', $year->id) }}" method="POST">
                                                            @csrf @method('PATCH')
                                                            <button type="submit" class="px-3 py-1.5 bg-blue-50 text-blue-600 rounded-lg text-xs font-bold hover:bg-blue-100 transition-colors flex items-center gap-1">
                                                                <i class="ph-bold ph-power"></i> Aktifkan
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('settings.academic.destroy', $year->id) }}" method="POST" onsubmit="return confirm('Hapus tahun ajaran ini?');">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="p-2 text-slate-300 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus">
                                                                <i class="ph-bold ph-trash text-lg"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="text-xs font-bold text-blue-600/50 italic flex items-center justify-end gap-1">
                                                            <i class="ph-fill ph-lock-key"></i> Terkunci
                                                        </span>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>