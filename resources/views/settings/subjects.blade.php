<x-app-layout>
    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-3xl font-black text-slate-800 tracking-tight leading-tight flex items-center gap-3">
                    <i class="ph-duotone ph-books text-blue-600"></i> Mata Pelajaran
                </h1>
                <p class="text-slate-500 mt-2 text-lg">
                    Kelola daftar mata pelajaran kurikulum merdeka.
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
                
                {{-- FORM TAMBAH (KIRI) --}}
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 sticky top-24">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-xl">
                                <i class="ph-duotone ph-book-bookmark"></i>
                            </div>
                            <h2 class="text-lg font-bold text-slate-800">Tambah Mapel</h2>
                        </div>

                        <form action="{{ route('subjects.store') }}" method="POST" class="space-y-5">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Nama Mapel</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                        <i class="ph-bold ph-text-t"></i>
                                    </div>
                                    <input type="text" name="name" placeholder="Contoh: Matematika" required 
                                           class="w-full pl-10 pr-4 py-3 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 text-sm font-bold text-slate-700 transition-colors placeholder:font-normal">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Kode</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                            <i class="ph-bold ph-tag"></i>
                                        </div>
                                        <input type="text" name="code" placeholder="MTK" 
                                               class="w-full pl-10 pr-4 py-3 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 text-sm font-bold text-slate-700 uppercase transition-colors placeholder:font-normal">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">No. Urut</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                            <i class="ph-bold ph-sort-ascending"></i>
                                        </div>
                                        <input type="number" name="order" value="{{ $subjects->count() + 1 }}" required 
                                               class="w-full pl-10 pr-4 py-3 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 text-sm font-bold text-slate-700 transition-colors">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Kelompok</label>
                                <div class="relative">
                                    <select name="group" class="w-full pl-4 pr-10 py-3 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 text-sm font-medium text-slate-700 transition-colors appearance-none cursor-pointer">
                                        <option value="A">Kelompok A (Umum)</option>
                                        <option value="B">Kelompok B (Muatan Lokal)</option>
                                        <option value="C">Kelompok C (Peminatan)</option>
                                        <option value="P5">Projek (P5)</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-400">
                                        <i class="ph-bold ph-caret-down"></i>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="w-full py-3 px-6 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-500/30 flex items-center justify-center gap-2 transform active:scale-[0.98]">
                                <i class="ph-bold ph-floppy-disk"></i>
                                Simpan Mapel
                            </button>
                        </form>
                    </div>
                </div>

                {{-- DAFTAR MAPEL (KANAN) --}}
                <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-50 flex justify-between items-center">
                        <h2 class="text-lg font-bold text-slate-800">Daftar Mapel Aktif</h2>
                        <span class="bg-slate-100 text-xs font-bold px-3 py-1 rounded-full text-slate-500 border border-slate-200">
                            Total: {{ $subjects->count() }}
                        </span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm text-slate-600">
                            <thead class="bg-slate-50 text-xs font-bold text-slate-400 uppercase">
                                <tr>
                                    <th class="px-6 py-4 text-center w-16">Urutan</th>
                                    <th class="px-6 py-4">Mata Pelajaran</th>
                                    <th class="px-6 py-4">Kelompok</th>
                                    <th class="px-6 py-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($subjects as $subject)
                                    <tr class="hover:bg-slate-50/50 transition-colors group">
                                        <td class="px-6 py-4 text-center">
                                            <span class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 font-black flex items-center justify-center text-xs">
                                                {{ $subject->order }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-slate-800 text-base">{{ $subject->name }}</div>
                                            <div class="text-xs text-slate-400 font-mono mt-0.5">{{ $subject->code }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @php
                                                $badgeClass = match($subject->group) {
                                                    'A' => 'bg-blue-50 text-blue-700 border-blue-100',
                                                    'B' => 'bg-orange-50 text-orange-700 border-orange-100',
                                                    'C' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                                    'P5' => 'bg-purple-50 text-purple-700 border-purple-100',
                                                    default => 'bg-slate-50 text-slate-600 border-slate-200'
                                                };
                                            @endphp
                                            <span class="px-3 py-1 rounded-lg text-xs font-bold border {{ $badgeClass }}">
                                                Kelompok {{ $subject->group }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                
                                                {{-- TOMBOL EDIT --}}
                                                <button onclick="openEditModal('{{ $subject->id }}', '{{ $subject->name }}', '{{ $subject->code }}', '{{ $subject->group }}', '{{ $subject->order }}')" 
                                                    class="p-2 text-slate-300 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit Mapel">
                                                    <i class="ph-bold ph-pencil-simple text-lg"></i>
                                                </button>

                                                {{-- TOMBOL HAPUS --}}
                                                <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST" onsubmit="return confirm('Hapus mapel ini? Data nilai terkait mungkin akan hilang.');">
                                                    @csrf @method('DELETE')
                                                    <button type="submit" class="p-2 text-slate-300 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus Mapel">
                                                        <i class="ph-bold ph-trash text-lg"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-slate-400 italic">
                                            Belum ada mata pelajaran.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT MAPEL --}}
    <div id="editModal" class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4 transition-opacity duration-300">
        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden transform scale-95 transition-transform duration-300" id="editModalContent">
            <div class="p-6 border-b border-slate-50 flex justify-between items-center">
                <h3 class="text-lg font-bold text-slate-800">Edit Mata Pelajaran</h3>
                <button onclick="closeEditModal()" class="text-slate-400 hover:text-slate-600 transition">
                    <i class="ph-bold ph-x text-xl"></i>
                </button>
            </div>
            
            {{-- Form Update akan diisi action via JS --}}
            <form id="editForm" method="POST" class="p-6 space-y-5">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Nama Mapel</label>
                    <input type="text" name="name" id="edit_name" required class="w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-bold text-slate-700">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Kode</label>
                        <input type="text" name="code" id="edit_code" class="w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-bold text-slate-700 uppercase">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">No. Urut</label>
                        <input type="number" name="order" id="edit_order" required class="w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-bold text-slate-700">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Kelompok</label>
                    <select name="group" id="edit_group" class="w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-medium text-slate-700">
                        <option value="A">Kelompok A (Umum)</option>
                        <option value="B">Kelompok B (Muatan Lokal)</option>
                        <option value="C">Kelompok C (Peminatan)</option>
                        <option value="P5">Projek (P5)</option>
                    </select>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-500/30">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- SCRIPT MODAL EDIT --}}
    <script>
        function openEditModal(id, name, code, group, order) {
            // Isi Form dengan data
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_code').value = code;
            document.getElementById('edit_group').value = group;
            document.getElementById('edit_order').value = order;
            
            // Set Action Form (Ganti ID di URL)
            // Asumsi route: /subjects/{id}
            let actionUrl = "{{ route('subjects.update', ':id') }}";
            actionUrl = actionUrl.replace(':id', id);
            document.getElementById('editForm').action = actionUrl;

            // Tampilkan Modal
            const modal = document.getElementById('editModal');
            const content = document.getElementById('editModalContent');
            modal.classList.remove('hidden');
            // Animasi kecil
            setTimeout(() => {
                content.classList.remove('scale-95');
                content.classList.add('scale-100');
            }, 10);
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            const content = document.getElementById('editModalContent');
            
            content.classList.remove('scale-100');
            content.classList.add('scale-95');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 150);
        }

        // Close modal when clicking outside
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    </script>
</x-app-layout>