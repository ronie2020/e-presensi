<x-app-layout>
    {{-- 
        X-DATA CONTEXT:
        Menggabungkan state pencarian dan state modal dalam satu scope Alpine.js 
        agar kode lebih rapi dan reaktif.
    --}}
    <div x-data="{ 
            search: '', 
            editModalOpen: false,
            editData: {
                id: null,
                name: '',
                code: '',
                order: '',
                group: 'A',
                actionUrl: ''
            },
            openEdit(subject) {
                this.editData = {
                    id: subject.id,
                    name: subject.name,
                    code: subject.code,
                    order: subject.order,
                    group: subject.group,
                    actionUrl: '{{ route('subjects.update', ':id') }}'.replace(':id', subject.id)
                };
                this.editModalOpen = true;
                
                // Fokus otomatis ke input nama setelah modal terbuka
                this.$nextTick(() => { document.getElementById('edit_name').focus(); });
            }
        }" 
        class="py-6 sm:py-8">
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header (Disesuaikan dengan gaya Welcome & Dashboard) --}}
            <div class="mb-8 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-black text-slate-800 tracking-tight leading-tight flex items-center gap-3">
                        {{-- Menggunakan warna Blue-600 sesuai tema utama --}}
                        <i class="ph-duotone ph-books text-blue-600"></i> Mata Pelajaran
                    </h1>
                    <p class="text-slate-500 mt-2 text-lg">
                        Kelola daftar mata pelajaran kurikulum merdeka.
                    </p>
                </div>
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

            @if($errors->any())
                <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl flex items-center gap-3 shadow-sm">
                    <div class="p-2 bg-rose-100 rounded-full text-rose-600 shrink-0">
                        <i class="ph-bold ph-warning"></i>
                    </div>
                    <div>
                        <p class="font-bold">Terdapat kesalahan input:</p>
                        <ul class="list-disc list-inside text-sm mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                {{-- FORM TAMBAH (KIRI) --}}
                <div class="lg:col-span-1">
                    {{-- Menggunakan border slate-100 dan shadow-sm agar clean seperti dashboard --}}
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 sticky top-24">
                        <div class="flex items-center gap-3 mb-6">
                            {{-- Ganti Indigo ke Blue --}}
                            <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl">
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
                                    {{-- Ganti focus ring ke Blue --}}
                                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Matematika" required 
                                           class="w-full pl-10 pr-4 py-3 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-bold text-slate-700 transition-colors placeholder:font-normal @error('name') border-rose-300 bg-rose-50 @enderror">
                                </div>
                                @error('name') <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Kode</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                            <i class="ph-bold ph-tag"></i>
                                        </div>
                                        <input type="text" name="code" value="{{ old('code') }}" placeholder="MTK" 
                                               class="w-full pl-10 pr-4 py-3 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-bold text-slate-700 uppercase transition-colors placeholder:font-normal @error('code') border-rose-300 bg-rose-50 @enderror">
                                    </div>
                                    @error('code') <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">No. Urut</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                            <i class="ph-bold ph-sort-ascending"></i>
                                        </div>
                                        <input type="number" name="order" value="{{ old('order', $subjects->count() + 1) }}" required 
                                               class="w-full pl-10 pr-4 py-3 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-bold text-slate-700 transition-colors">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Kelompok</label>
                                <div class="relative">
                                    <select name="group" class="w-full pl-4 pr-10 py-3 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-medium text-slate-700 transition-colors appearance-none cursor-pointer">
                                        <option value="A" {{ old('group') == 'A' ? 'selected' : '' }}>Kelompok A (Umum)</option>
                                        <option value="B" {{ old('group') == 'B' ? 'selected' : '' }}>Kelompok B (Muatan Lokal)</option>
                                        <option value="C" {{ old('group') == 'C' ? 'selected' : '' }}>Kelompok C (Peminatan)</option>
                                        <option value="P5" {{ old('group') == 'P5' ? 'selected' : '' }}>Projek (P5)</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-slate-400">
                                        <i class="ph-bold ph-caret-down"></i>
                                    </div>
                                </div>
                            </div>

                            {{-- TOMBOL UTAMA (Disamakan dengan style Welcome Page: Blue + Shadow Glow) --}}
                            <button type="submit" class="w-full py-3 px-6 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-500/30 flex items-center justify-center gap-2 transform active:scale-[0.98]">
                                <i class="ph-bold ph-floppy-disk"></i>
                                Simpan Mapel
                            </button>
                        </form>
                    </div>
                </div>

                {{-- DAFTAR MAPEL (KANAN) --}}
                <div class="lg:col-span-2 bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden min-h-[500px] flex flex-col">
                    <div class="p-6 border-b border-slate-50 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                        <div class="flex items-center gap-3">
                            <h2 class="text-lg font-bold text-slate-800">Daftar Mapel</h2>
                            <span class="bg-slate-100 text-xs font-bold px-3 py-1 rounded-full text-slate-500 border border-slate-200">
                                Total: {{ $subjects->count() }}
                            </span>
                        </div>
                        {{-- Search Box dengan nuansa Blue --}}
                        <div class="relative w-full sm:w-64">
                            <input x-model="search" type="text" placeholder="Cari mapel..." class="w-full pl-10 pr-4 py-2 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-medium transition-colors">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                <i class="ph-bold ph-magnifying-glass"></i>
                            </div>
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto flex-1">
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
                                    <tr class="hover:bg-slate-50/50 transition-colors group"
                                        x-show="search === '' || '{{ strtolower($subject->name) }}'.includes(search.toLowerCase()) || '{{ strtolower($subject->code) }}'.includes(search.toLowerCase())"
                                        x-transition.opacity>
                                        
                                        <td class="px-6 py-4 text-center">
                                            <span class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 font-black flex items-center justify-center text-xs">
                                                {{ $subject->order }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-slate-800 text-base">{{ $subject->name }}</div>
                                            @if($subject->code)
                                                <div class="text-xs text-slate-400 font-mono mt-0.5">{{ $subject->code }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            {{-- Badge Styling tetap distinct agar mudah dibedakan --}}
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
                                                
                                                {{-- Tombol Aksi menggunakan Hover Blue --}}
                                                <button @click="openEdit({{ $subject }})"
                                                    class="p-2 text-slate-300 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit Mapel">
                                                    <i class="ph-bold ph-pencil-simple text-lg"></i>
                                                </button>

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
                                
                                <tr x-show="search !== '' && $el.parentNode.querySelectorAll('tr[x-show]:not([style*=\'display: none\'])').length === 0" style="display: none;">
                                    <td colspan="4" class="px-6 py-8 text-center text-slate-400">
                                        Tidak ditemukan mapel dengan kata kunci "<span x-text="search" class="font-bold text-slate-600"></span>"
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL EDIT MAPEL --}}
        <div x-show="editModalOpen" style="display: none;" 
            class="fixed inset-0 z-50 flex items-center justify-center p-4">
            
            <div @click="editModalOpen = false" 
                x-show="editModalOpen"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"></div>

            <div x-show="editModalOpen"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                class="bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden relative z-10">
                
                <div class="p-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/50">
                    <h3 class="text-lg font-bold text-slate-800">Edit Mata Pelajaran</h3>
                    <button @click="editModalOpen = false" class="text-slate-400 hover:text-slate-600 transition">
                        <i class="ph-bold ph-x text-xl"></i>
                    </button>
                </div>
                
                <form :action="editData.actionUrl" method="POST" class="p-6 space-y-5">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Nama Mapel</label>
                        {{-- Update Focus Ring ke Blue --}}
                        <input type="text" name="name" id="edit_name" x-model="editData.name" required 
                               class="w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-bold text-slate-700">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Kode</label>
                            <input type="text" name="code" x-model="editData.code" 
                                   class="w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-bold text-slate-700 uppercase">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">No. Urut</label>
                            <input type="number" name="order" x-model="editData.order" required 
                                   class="w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-bold text-slate-700">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Kelompok</label>
                        <select name="group" x-model="editData.group" class="w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-medium text-slate-700">
                            <option value="A">Kelompok A (Umum)</option>
                            <option value="B">Kelompok B (Muatan Lokal)</option>
                            <option value="C">Kelompok C (Peminatan)</option>
                            <option value="P5">Projek (P5)</option>
                        </select>
                    </div>

                    <div class="pt-2 flex gap-3">
                        <button type="button" @click="editModalOpen = false" class="flex-1 py-3 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition">
                            Batal
                        </button>
                        {{-- Update tombol simpan modal ke Blue + Shadow --}}
                        <button type="submit" class="flex-1 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-500/30">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>