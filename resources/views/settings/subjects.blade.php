<x-app-layout>
    <div class="py-8">
        <div class="max-w-6xl mx-auto px-4">
            
            <h1 class="text-2xl font-black text-gray-800 mb-6">Data Mata Pelajaran</h1>

            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-xl font-bold border border-emerald-100 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- FORM TAMBAH --}}
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 h-fit">
                    <h2 class="text-lg font-bold text-gray-800 mb-4">Tambah Mapel Baru</h2>
                    <form action="{{ route('subjects.store') }}" method="POST" class="space-y-4">
                        @csrf
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Nama Mapel</label>
                            <input type="text" name="name" placeholder="Contoh: Matematika" required 
                                   class="w-full rounded-xl border-gray-200 focus:ring-indigo-500">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Kode (Singkatan)</label>
                                <input type="text" name="code" placeholder="MTK" 
                                       class="w-full rounded-xl border-gray-200 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1">No. Urut Rapor</label>
                                <input type="number" name="order" value="1" required 
                                       class="w-full rounded-xl border-gray-200 focus:ring-indigo-500">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Kelompok</label>
                            <select name="group" class="w-full rounded-xl border-gray-200 focus:ring-indigo-500">
                                <option value="A">Kelompok A (Umum)</option>
                                <option value="B">Kelompok B (Muatan Lokal)</option>
                                <option value="C">Kelompok C (Peminatan)</option>
                                <option value="P5">Projek (P5)</option>
                            </select>
                        </div>

                        <button type="submit" class="w-full py-2.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                            Simpan Mapel
                        </button>
                    </form>
                </div>

                {{-- DAFTAR MAPEL --}}
                <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                        <h2 class="text-lg font-bold text-gray-800">Daftar Mapel Aktif</h2>
                        <span class="text-xs font-bold bg-white border border-gray-200 px-3 py-1 rounded-full text-gray-500">
                            Total: {{ $subjects->count() }}
                        </span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-600">
                            <thead class="bg-white border-b border-gray-100 text-xs uppercase text-gray-400">
                                <tr>
                                    <th class="px-6 py-3 text-center w-16">Urutan</th>
                                    <th class="px-6 py-3">Mata Pelajaran</th>
                                    <th class="px-6 py-3">Kelompok</th>
                                    <th class="px-6 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse($subjects as $subject)
                                    <tr class="hover:bg-indigo-50/30 group">
                                        <td class="px-6 py-4 text-center font-mono font-bold text-gray-400">{{ $subject->order }}</td>
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-gray-800">{{ $subject->name }}</div>
                                            <div class="text-xs text-gray-400">{{ $subject->code }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 rounded-md text-xs font-bold uppercase 
                                                {{ $subject->group == 'A' ? 'bg-blue-50 text-blue-600' : '' }}
                                                {{ $subject->group == 'B' ? 'bg-orange-50 text-orange-600' : '' }}
                                                {{ $subject->group == 'P5' ? 'bg-purple-50 text-purple-600' : '' }}">
                                                Kelompok {{ $subject->group }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                <!-- Tombol Edit (Bisa dibuat modal, tapi delete dulu yang penting) -->
                                                <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST" onsubmit="return confirm('Hapus mapel ini? Data nilai terkait mungkin akan hilang.');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="p-2 text-rose-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition" title="Hapus">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-8 text-center text-gray-400 italic">Belum ada mata pelajaran.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>