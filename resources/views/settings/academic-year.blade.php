<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4">
            
            <h1 class="text-2xl font-black text-gray-800 mb-6">Pengaturan Tahun Ajaran</h1>

            {{-- Alert --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-xl font-bold border border-emerald-100 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                {{-- FORM TAMBAH --}}
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 h-fit">
                    <h2 class="text-lg font-bold text-gray-800 mb-4">Tambah Baru</h2>
                    <form action="{{ route('settings.academic.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Tahun Ajaran</label>
                            <input type="text" name="name" placeholder="Contoh: 2025/2026" required 
                                   class="w-full rounded-xl border-gray-200 focus:ring-indigo-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Semester</label>
                            <select name="semester" class="w-full rounded-xl border-gray-200 focus:ring-indigo-500">
                                <option value="Ganjil">Ganjil (1)</option>
                                <option value="Genap">Genap (2)</option>
                            </select>
                        </div>
                        <button type="submit" class="w-full py-2 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition">
                            Simpan
                        </button>
                    </form>
                </div>

                {{-- DAFTAR TAHUN AJARAN --}}
                <div class="md:col-span-2 bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gray-50">
                        <h2 class="text-lg font-bold text-gray-800">Daftar Tahun Ajaran</h2>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-gray-600">
                            <thead class="bg-white border-b border-gray-100 text-xs uppercase text-gray-400">
                                <tr>
                                    <th class="px-6 py-3">Tahun</th>
                                    <th class="px-6 py-3">Semester</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($years as $year)
                                    <tr class="hover:bg-indigo-50/30 {{ $year->is_active ? 'bg-indigo-50/50' : '' }}">
                                        <td class="px-6 py-4 font-bold">{{ $year->name }}</td>
                                        <td class="px-6 py-4">{{ $year->semester }}</td>
                                        <td class="px-6 py-4">
                                            @if($year->is_active)
                                                <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-md text-xs font-bold uppercase">Aktif</span>
                                            @else
                                                <span class="px-2 py-1 bg-gray-100 text-gray-500 rounded-md text-xs font-bold uppercase">Tidak Aktif</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-right flex justify-end gap-2">
                                            @if(!$year->is_active)
                                                <form action="{{ route('settings.academic.activate', $year->id) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <button type="submit" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 bg-indigo-50 px-3 py-1.5 rounded-lg transition">
                                                        Aktifkan
                                                    </button>
                                                </form>
                                                <form action="{{ route('settings.academic.destroy', $year->id) }}" method="POST" onsubmit="return confirm('Hapus tahun ajaran ini?');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="text-xs font-bold text-rose-500 hover:text-rose-700 p-1.5">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-xs text-gray-400 italic">Sedang Digunakan</span>
                                            @endif
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
</x-app-layout>