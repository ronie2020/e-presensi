<x-app-layout>
    <div class="py-6 sm:py-8">
        
        {{-- Header Modern (Tanpa x-slot) --}}
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-black text-gray-800 tracking-tight leading-tight">
                    Master Data Disiplin
                </h1>
                <p class="text-gray-500 mt-1">
                    Atur jenis pelanggaran dan poin penghargaan di sini.
                </p>
            </div>
            <a href="{{ route('discipline.index') }}" class="group flex items-center gap-2 bg-white border border-gray-200 text-gray-600 px-4 py-2.5 rounded-xl font-bold text-sm hover:bg-gray-50 transition-all shadow-sm">
                <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm">
                <span class="font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('success') }}
                </span>
                <button @click="show = false" class="text-emerald-400 hover:text-emerald-600">&times;</button>
            </div>
        @endif
        
        @if ($errors->any())
            <div class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl shadow-sm">
                <ul class="list-disc list-inside text-sm font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- FORM TAMBAH DATA (CARD MODERN) -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-8 mb-10 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-2 h-full bg-blue-600"></div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-6">
                    <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    </div>
                    <h3 class="text-lg font-black text-gray-800">Tambah Jenis Baru</h3>
                </div>

                <form action="{{ route('discipline-types.store') }}" method="POST" class="flex flex-col md:flex-row gap-4 items-end">
                    @csrf
                    <div class="w-full md:flex-1">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Nama Kategori</label>
                        <input type="text" name="name" required placeholder="Contoh: Terlambat, Atribut Tidak Lengkap..." 
                               class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm py-3 font-medium transition-colors">
                    </div>
                    
                    <div class="w-full md:w-48">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Tipe</label>
                        <select name="type" required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm py-3 font-medium transition-colors">
                            <option value="Pelanggaran">🔴 Pelanggaran</option>
                            <option value="Kebaikan">🟢 Kebaikan</option>
                        </select>
                    </div>
                    
                    <div class="w-full md:w-32">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Poin</label>
                        <input type="number" name="point_value" required min="1" 
                               class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm py-3 font-medium transition-colors text-center">
                    </div>
                    
                    <button type="submit" class="w-full md:w-auto bg-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Simpan
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            
            <!-- TABEL PELANGGARAN -->
            <div class="bg-white rounded-3xl shadow-sm border border-rose-100 overflow-hidden h-full">
                <div class="p-5 bg-rose-50/50 border-b border-rose-100 flex items-center justify-between">
                    <h3 class="font-bold text-rose-700 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span> Daftar Pelanggaran
                    </h3>
                    <span class="text-xs font-bold bg-white px-2 py-1 rounded-md text-rose-400 border border-rose-100">{{ $violationTypes->count() }} Item</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left">
                        <thead class="bg-white border-b border-gray-50">
                            <tr>
                                <th class="px-5 py-3 text-xs font-bold text-gray-400 uppercase">Nama</th>
                                <th class="px-5 py-3 text-center text-xs font-bold text-gray-400 uppercase">Poin</th>
                                <th class="px-5 py-3 text-right text-xs font-bold text-gray-400 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($violationTypes as $item)
                                <tr class="hover:bg-rose-50/30 transition-colors group">
                                    <td class="px-5 py-3 text-sm font-medium text-gray-700">{{ $item->name }}</td>
                                    <td class="px-5 py-3 text-center">
                                        <span class="inline-block px-2 py-1 rounded bg-rose-100 text-rose-700 text-xs font-bold">-{{ $item->point_value }}</span>
                                    </td>
                                    <td class="px-5 py-3 text-right">
                                        <form action="{{ route('discipline-types.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus jenis pelanggaran ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-gray-300 hover:text-rose-500 transition-colors p-1 rounded hover:bg-rose-50">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-5 py-8 text-center text-gray-400 text-sm">Belum ada data pelanggaran.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- TABEL KEBAIKAN -->
            <div class="bg-white rounded-3xl shadow-sm border border-emerald-100 overflow-hidden h-full">
                <div class="p-5 bg-emerald-50/50 border-b border-emerald-100 flex items-center justify-between">
                    <h3 class="font-bold text-emerald-700 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Daftar Kebaikan
                    </h3>
                    <span class="text-xs font-bold bg-white px-2 py-1 rounded-md text-emerald-400 border border-emerald-100">{{ $meritTypes->count() }} Item</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full text-left">
                        <thead class="bg-white border-b border-gray-50">
                            <tr>
                                <th class="px-5 py-3 text-xs font-bold text-gray-400 uppercase">Nama</th>
                                <th class="px-5 py-3 text-center text-xs font-bold text-gray-400 uppercase">Poin</th>
                                <th class="px-5 py-3 text-right text-xs font-bold text-gray-400 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($meritTypes as $item)
                                <tr class="hover:bg-emerald-50/30 transition-colors group">
                                    <td class="px-5 py-3 text-sm font-medium text-gray-700">{{ $item->name }}</td>
                                    <td class="px-5 py-3 text-center">
                                        <span class="inline-block px-2 py-1 rounded bg-emerald-100 text-emerald-700 text-xs font-bold">+{{ $item->point_value }}</span>
                                    </td>
                                    <td class="px-5 py-3 text-right">
                                        <form action="{{ route('discipline-types.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus jenis kebaikan ini?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-gray-300 hover:text-rose-500 transition-colors p-1 rounded hover:bg-rose-50">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="px-5 py-8 text-center text-gray-400 text-sm">Belum ada data kebaikan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>