<x-app-layout>
    {{-- 
        X-DATA CONTEXT:
        Menggabungkan state pencarian dan state modal dalam satu scope Alpine.js 
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
                this.$nextTick(() => { document.getElementById('edit_name').focus(); });
            }
        }" 
        class="py-8 sm:py-10 font-sans text-slate-800">
        
        {{-- HERO SECTION --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
            <div class="relative rounded-[2.5rem] bg-gray-900 bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 p-8 sm:p-10 text-white shadow-2xl shadow-blue-900/40 overflow-hidden border border-white/10 group">
                
                {{-- Background Decorations --}}
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-blue-500/20 rounded-full blur-3xl pointer-events-none group-hover:bg-blue-500/30 transition-all duration-700"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    
                    {{-- Text Content --}}
                    <div class="max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-200 text-[10px] font-bold uppercase tracking-widest mb-4 backdrop-blur-sm">
                            <i class="ph-fill ph-books"></i> Kurikulum Merdeka
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-3 flex items-center gap-3 text-white leading-tight">
                            Mata Pelajaran
                        </h1>
                        <p class="text-blue-100/80 text-sm md:text-base font-medium leading-relaxed max-w-lg">
                            Kelola daftar mata pelajaran, kodefikasi, dan pengelompokan (A, B, C, P5) untuk keperluan rapor dan jadwal.
                        </p>
                    </div>
                    
                    {{-- Stats Cards --}}
                    <div class="flex gap-4">
                        <div class="bg-white/10 backdrop-blur-md px-6 py-5 rounded-2xl border border-white/10 min-w-[140px] text-center md:text-left hover:bg-white/15 transition-colors">
                            <div class="flex items-center justify-center md:justify-start gap-2 mb-1 text-blue-300">
                                <i class="ph-duotone ph-book-open-text text-lg"></i>
                                <span class="text-[10px] font-bold uppercase tracking-wider">Total Mapel</span>
                            </div>
                            <span class="block text-3xl font-black text-white tracking-tight">{{ $subjects->count() }}</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Alert Sukses --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-emerald-100 rounded-full text-emerald-600">
                            <i class="ph-bold ph-check-circle text-xl"></i>
                        </div>
                        <span class="font-bold text-sm">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 p-1 rounded-md hover:bg-emerald-100 transition"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            @if($errors->any())
                <div x-data="{ show: true }" x-show="show" class="mb-8 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl flex items-start gap-3 shadow-sm">
                    <div class="p-2 bg-rose-100 rounded-full text-rose-600 shrink-0">
                        <i class="ph-bold ph-warning-circle text-xl"></i>
                    </div>
                    <div>
                        <p class="font-bold text-sm mb-1">Terdapat kesalahan input:</p>
                        <ul class="list-disc list-inside text-xs font-medium">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                {{-- KOLOM KIRI: FORM TAMBAH --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden sticky top-24 relative group hover:shadow-2xl hover:shadow-blue-900/10 transition-all duration-300">
                        
                        {{-- Card Header --}}
                        <div class="bg-gradient-to-r from-blue-900 to-blue-800 p-8 text-white relative overflow-hidden">
                            <div class="absolute -right-6 -top-6 text-white/5 text-9xl pointer-events-none">
                                <i class="ph-fill ph-plus-circle"></i>
                            </div>
                            <h3 class="text-xl font-black relative z-10">Tambah Mapel</h3>
                            <p class="text-blue-200 text-sm font-medium relative z-10 mt-1">Input data pelajaran baru.</p>
                        </div>

                        <div class="p-8 relative z-10">
                            <form action="{{ route('subjects.store') }}" method="POST" class="space-y-6">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Nama Mapel</label>
                                    <div class="relative group">
                                        <i class="ph-bold ph-text-t absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-600 transition-colors"></i>
                                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Matematika" required 
                                               class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm font-bold text-slate-700 transition-colors placeholder:font-normal shadow-sm">
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Kode</label>
                                        <div class="relative group">
                                            <i class="ph-bold ph-tag absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-600 transition-colors"></i>
                                            <input type="text" name="code" value="{{ old('code') }}" placeholder="MTK" 
                                                   class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm font-bold text-slate-700 uppercase transition-colors placeholder:font-normal shadow-sm">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">No. Urut</label>
                                        <div class="relative group">
                                            <i class="ph-bold ph-sort-ascending absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-600 transition-colors"></i>
                                            <input type="number" name="order" value="{{ old('order', $subjects->count() + 1) }}" required 
                                                   class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm font-bold text-slate-700 transition-colors shadow-sm">
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Kelompok</label>
                                    <div class="relative group">
                                        <select name="group" class="w-full pl-4 pr-10 py-3.5 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm font-bold text-slate-700 transition-colors appearance-none cursor-pointer shadow-sm">
                                            <option value="A" {{ old('group') == 'A' ? 'selected' : '' }}>Kelompok A (Umum)</option>
                                            <option value="B" {{ old('group') == 'B' ? 'selected' : '' }}>Kelompok B (Muatan Lokal)</option>
                                            <option value="C" {{ old('group') == 'C' ? 'selected' : '' }}>Kelompok C (Peminatan)</option>
                                            <option value="P5" {{ old('group') == 'P5' ? 'selected' : '' }}>Projek (P5)</option>
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                    </div>
                                </div>

                                <button type="submit" class="w-full py-3.5 px-6 bg-blue-900 text-white font-bold rounded-2xl hover:bg-blue-800 transition-all shadow-lg shadow-blue-900/30 flex items-center justify-center gap-2 transform active:scale-[0.98]">
                                    <i class="ph-bold ph-floppy-disk text-lg"></i>
                                    Simpan Mapel
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: DAFTAR MAPEL --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden flex flex-col h-full min-h-[600px]">
                        <div class="p-8 border-b border-slate-50 bg-slate-50/30 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                            <div class="flex items-center gap-3">
                                <h2 class="text-lg font-black text-slate-800 flex items-center gap-2">
                                    <i class="ph-fill ph-list-dashes text-blue-900"></i> Daftar Mapel
                                </h2>
                                <span class="bg-white border border-slate-200 text-[10px] font-black px-3 py-1.5 rounded-xl text-slate-500 shadow-sm">
                                    {{ $subjects->count() }} Data
                                </span>
                            </div>
                            {{-- Search Box --}}
                            <div class="relative w-full sm:w-64 group">
                                <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-600 transition-colors"></i>
                                <input x-model="search" type="text" placeholder="Cari mapel..." class="w-full pl-11 pr-4 py-2.5 rounded-xl border-slate-200 bg-white focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm font-bold transition-colors shadow-sm">
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto flex-1 custom-scrollbar">
                            <table class="w-full text-left text-sm text-slate-600">
                                <thead class="bg-slate-50 text-xs font-bold text-slate-400 uppercase tracking-wider">
                                    <tr>
                                        <th class="px-6 py-5 text-center w-20">Urut</th>
                                        <th class="px-6 py-5">Mata Pelajaran</th>
                                        <th class="px-6 py-5">Kelompok</th>
                                        <th class="px-6 py-5 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @forelse($subjects as $subject)
                                        <tr class="hover:bg-blue-50/30 transition-colors group"
                                            x-show="search === '' || '{{ strtolower($subject->name) }}'.includes(search.toLowerCase()) || '{{ strtolower($subject->code) }}'.includes(search.toLowerCase())"
                                            x-transition.opacity>
                                            
                                            <td class="px-6 py-5 text-center">
                                                <div class="w-8 h-8 rounded-lg bg-slate-100 border border-slate-200 text-slate-500 font-black flex items-center justify-center text-xs mx-auto">
                                                    {{ $subject->order }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-5">
                                                <div class="font-bold text-slate-800 text-base mb-0.5">{{ $subject->name }}</div>
                                                @if($subject->code)
                                                    <span class="text-[10px] font-mono font-bold text-slate-400 bg-slate-50 px-1.5 py-0.5 rounded border border-slate-100">{{ $subject->code }}</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-5">
                                                @php
                                                    $badgeClass = match($subject->group) {
                                                        'A' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                        'B' => 'bg-orange-50 text-orange-700 border-orange-200',
                                                        'C' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                        'P5' => 'bg-purple-50 text-purple-700 border-purple-200',
                                                        default => 'bg-slate-50 text-slate-600 border-slate-200'
                                                    };
                                                @endphp
                                                <span class="px-3 py-1.5 rounded-xl text-xs font-black border {{ $badgeClass }}">
                                                    Kelompok {{ $subject->group }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-5 text-right">
                                                <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity transform translate-x-2 group-hover:translate-x-0 duration-200">
                                                    
                                                    <button @click="openEdit({{ $subject }})"
                                                        class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50 transition-all shadow-sm" title="Edit Mapel">
                                                        <i class="ph-bold ph-pencil-simple text-lg"></i>
                                                    </button>

                                                    <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST" onsubmit="return confirm('Hapus mapel ini? Data nilai terkait mungkin akan hilang.');">
                                                        @csrf @method('DELETE')
                                                        <button type="submit" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 transition-all shadow-sm" title="Hapus Mapel">
                                                            <i class="ph-bold ph-trash text-lg"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-20 text-center">
                                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                                    <i class="ph-duotone ph-books text-4xl"></i>
                                                </div>
                                                <p class="text-sm font-bold text-slate-600">Belum ada mata pelajaran.</p>
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
                class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

            <div x-show="editModalOpen"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                class="bg-white rounded-[2rem] shadow-2xl w-full max-w-md overflow-hidden relative z-10 border border-white/20">
                
                <div class="bg-blue-900 p-6 flex justify-between items-center text-white">
                    <h3 class="text-lg font-black flex items-center gap-2">
                        <i class="ph-bold ph-pencil-simple text-blue-300"></i> Edit Mata Pelajaran
                    </h3>
                    <button @click="editModalOpen = false" class="w-8 h-8 flex items-center justify-center rounded-full bg-white/10 hover:bg-white/20 transition-colors">
                        <i class="ph-bold ph-x"></i>
                    </button>
                </div>
                
                <form :action="editData.actionUrl" method="POST" class="p-8 space-y-5">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Nama Mapel</label>
                        <input type="text" name="name" id="edit_name" x-model="editData.name" required 
                               class="w-full px-4 py-3.5 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm font-bold text-slate-700 transition-all">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Kode</label>
                            <input type="text" name="code" x-model="editData.code" 
                                   class="w-full px-4 py-3.5 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm font-bold text-slate-700 uppercase transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">No. Urut</label>
                            <input type="number" name="order" x-model="editData.order" required 
                                   class="w-full px-4 py-3.5 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm font-bold text-slate-700 transition-all">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Kelompok</label>
                        <div class="relative">
                            <select name="group" x-model="editData.group" class="w-full px-4 py-3.5 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm font-bold text-slate-700 appearance-none cursor-pointer transition-all">
                                <option value="A">Kelompok A (Umum)</option>
                                <option value="B">Kelompok B (Muatan Lokal)</option>
                                <option value="C">Kelompok C (Peminatan)</option>
                                <option value="P5">Projek (P5)</option>
                            </select>
                            <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                        </div>
                    </div>

                    <div class="pt-4 flex gap-3">
                        <button type="button" @click="editModalOpen = false" class="flex-1 py-3.5 bg-slate-100 text-slate-600 font-bold rounded-2xl hover:bg-slate-200 transition-colors text-sm">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 py-3.5 bg-blue-900 text-white font-bold rounded-2xl hover:bg-blue-800 transition-all shadow-lg shadow-blue-900/20 text-sm transform active:scale-95">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>