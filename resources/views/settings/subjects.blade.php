<x-app-layout>
    {{-- X-DATA CONTEXT: Alpine.js --}}
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
        class="py-8 sm:py-10 font-sans min-h-screen text-slate-100 bg-[#020b18] relative overflow-hidden">
        
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-gradient-to-b from-sky-600/10 via-blue-600/5 to-transparent pointer-events-none -z-10 blur-3xl"></div>

        {{-- HERO SECTION --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 sm:mb-10 relative z-10">
            <x-hero-section
                badge="KURIKULUM MERDEKA"
                badgeIcon="ph-fill ph-books"
                showcaseIcon="ph-duotone ph-book-open-text"
                showcaseTitle="Mata Pelajaran"
                showcaseSubtitle="Kurikulum & Kodefikasi">
                <x-slot:title>
                    <span class="block text-slate-100">Manajemen</span>
                    <span class="block mt-1 sm:mt-1.5 text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] via-sky-200 to-[#38bdf8]">
                        Mata Pelajaran
                    </span>
                </x-slot:title>
                <x-slot:description>
                    Kelola daftar mata pelajaran, kodefikasi, dan pengelompokan (A, B, C, P5) untuk keperluan rapor dan penjadwalan.
                </x-slot:description>
                <x-slot:chips>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-900/80 border border-white/10 text-slate-300 text-xs font-semibold">
                        <i class="ph-bold ph-squares-four text-sky-400"></i> Kelompok A, B, C & Muatan
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-900/80 border border-white/10 text-slate-300 text-xs font-semibold">
                        <i class="ph-bold ph-sparkle text-emerald-400"></i> P5 & Kokurikuler
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-900/80 border border-white/10 text-slate-300 text-xs font-semibold">
                        <i class="ph-bold ph-sort-ascending text-cyan-400"></i> Urutan Rapor
                    </span>
                </x-slot:chips>
                <x-slot:showcaseStats>
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-2xl bg-slate-900/80 border border-white/10 backdrop-blur-md">
                        <i class="ph-fill ph-book text-sky-400 text-sm"></i>
                        <span class="text-xs font-bold text-slate-300">Total:</span>
                        <span class="text-sm font-black text-white font-mono">{{ $subjects->count() }}</span>
                        <span class="text-xs text-slate-400">Mapel</span>
                    </div>
                </x-slot:showcaseStats>
            </x-hero-section>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            {{-- Alert Sukses --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-8 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 rounded-2xl flex items-center justify-between shadow-sm backdrop-blur-md">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-emerald-500/20 rounded-full text-emerald-400">
                            <i class="ph-bold ph-check-circle text-xl"></i>
                        </div>
                        <span class="font-bold text-sm">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-200 p-1 rounded-md transition"><i class="ph-bold ph-x text-lg"></i></button>
                </div>
            @endif

            @if($errors->any())
                <div x-data="{ show: true }" x-show="show" class="mb-8 p-4 bg-rose-500/10 border border-rose-500/20 text-rose-300 rounded-2xl flex items-start gap-3 shadow-sm backdrop-blur-md">
                    <div class="p-2 bg-rose-500/20 rounded-full text-rose-400 shrink-0">
                        <i class="ph-bold ph-warning-circle text-xl"></i>
                    </div>
                    <div>
                        <p class="font-bold text-sm mb-1">Terdapat kesalahan input:</p>
                        <ul class="list-disc list-inside text-xs font-medium space-y-0.5">
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
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden sticky top-24 relative group backdrop-blur-xl">
                        
                        {{-- Card Header --}}
                        <div class="p-8 relative overflow-hidden border-b border-white/10 bg-slate-900/40">
                            <div class="absolute -right-6 -top-6 text-sky-400/5 text-9xl pointer-events-none">
                                <i class="ph-fill ph-plus-circle"></i>
                            </div>
                            <h3 class="text-xl font-black text-white relative z-10 flex items-center gap-2">
                                <i class="ph-bold ph-plus-circle text-sky-400"></i> Tambah Mapel
                            </h3>
                            <p class="text-slate-400 text-sm font-medium relative z-10 mt-1">Input data pelajaran baru.</p>
                        </div>

                        <div class="p-8 relative z-10">
                            <form action="{{ route('subjects.store') }}" method="POST" class="space-y-6">
                                @csrf
                                <div>
                                    <label class="block text-xs font-bold text-sky-400 uppercase tracking-wider mb-2 ml-1">Nama Mapel</label>
                                    <div class="relative group">
                                        <i class="ph-bold ph-text-t absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-sky-400 transition-colors"></i>
                                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Matematika" required 
                                               class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-white/10 bg-slate-900/80 text-white placeholder-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 text-sm font-bold transition-all shadow-sm">
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-sky-400 uppercase tracking-wider mb-2 ml-1">Kode</label>
                                        <div class="relative group">
                                            <i class="ph-bold ph-tag absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-sky-400 transition-colors"></i>
                                            <input type="text" name="code" value="{{ old('code') }}" placeholder="MTK" 
                                                   class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-white/10 bg-slate-900/80 text-white placeholder-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 text-sm font-bold uppercase transition-all shadow-sm">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-sky-400 uppercase tracking-wider mb-2 ml-1">No. Urut</label>
                                        <div class="relative group">
                                            <i class="ph-bold ph-sort-ascending absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-sky-400 transition-colors"></i>
                                            <input type="number" name="order" value="{{ old('order', $subjects->count() + 1) }}" required 
                                                   class="w-full pl-11 pr-4 py-3.5 rounded-2xl border border-white/10 bg-slate-900/80 text-white focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 text-sm font-bold transition-all shadow-sm">
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-sky-400 uppercase tracking-wider mb-2 ml-1">Kelompok</label>
                                    <div class="relative group">
                                        <select name="group" class="w-full pl-4 pr-10 py-3.5 rounded-2xl border border-white/10 bg-slate-900/80 text-white focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 text-sm font-bold transition-all appearance-none cursor-pointer shadow-sm [color-scheme:dark]">
                                            <option value="A" {{ old('group') == 'A' ? 'selected' : '' }} class="bg-slate-900 text-white">Kelompok A (Umum)</option>
                                            <option value="B" {{ old('group') == 'B' ? 'selected' : '' }} class="bg-slate-900 text-white">Kelompok B (Muatan Lokal)</option>
                                            <option value="C" {{ old('group') == 'C' ? 'selected' : '' }} class="bg-slate-900 text-white">Kelompok C (Peminatan)</option>
                                            <option value="P5" {{ old('group') == 'P5' ? 'selected' : '' }} class="bg-slate-900 text-white">Projek (P5)</option>
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 pointer-events-none"></i>
                                    </div>
                                </div>

                                <button type="submit" class="w-full py-3.5 px-6 bg-gradient-to-r from-sky-500 to-blue-600 text-white font-bold rounded-2xl hover:from-sky-400 hover:to-blue-500 transition-all shadow-lg shadow-sky-500/25 flex items-center justify-center gap-2 transform active:scale-95 border border-transparent">
                                    <i class="ph-bold ph-floppy-disk text-lg"></i>
                                    Simpan Mapel
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: DAFTAR MAPEL --}}
                <div class="lg:col-span-2">
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden flex flex-col h-full min-h-[600px] backdrop-blur-xl">
                        <div class="p-8 border-b border-white/10 bg-slate-900/60 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
                            <div class="flex items-center gap-3">
                                <h2 class="text-lg font-black text-white flex items-center gap-2">
                                    <i class="ph-fill ph-list-dashes text-sky-400"></i> Daftar Mapel
                                </h2>
                                <span class="bg-sky-500/10 border border-sky-500/20 text-xs font-black px-3 py-1.5 rounded-xl text-sky-300 shadow-sm">
                                    {{ $subjects->count() }} Data
                                </span>
                            </div>
                            {{-- Search Box --}}
                            <div class="relative w-full sm:w-64 group">
                                <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-sky-400 transition-colors"></i>
                                <input x-model="search" type="text" placeholder="Cari mapel..." class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-white/10 bg-slate-900/80 text-white placeholder-slate-500 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 text-sm font-bold transition-all shadow-sm">
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto flex-1 custom-scrollbar">
                            <table class="w-full text-left text-sm text-slate-200">
                                <thead class="bg-slate-900/80 text-xs font-bold text-sky-400 uppercase tracking-wider border-b border-white/10">
                                    <tr>
                                        <th class="px-6 py-5 text-center w-20">Urut</th>
                                        <th class="px-6 py-5">Mata Pelajaran</th>
                                        <th class="px-6 py-5 whitespace-nowrap">Kelompok</th>
                                        <th class="px-6 py-5 text-right whitespace-nowrap">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @forelse($subjects as $subject)
                                        <tr class="hover:bg-white/[0.02] transition-colors group"
                                            x-show="search === '' || '{{ strtolower($subject->name) }}'.includes(search.toLowerCase()) || '{{ strtolower($subject->code) }}'.includes(search.toLowerCase())"
                                            x-transition.opacity>
                                            
                                            <td class="px-6 py-5 text-center">
                                                <div class="w-8 h-8 rounded-lg bg-slate-900/80 border border-white/10 text-slate-300 font-black flex items-center justify-center text-xs mx-auto">
                                                    {{ $subject->order }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-5">
                                                <div class="font-bold text-white text-base mb-0.5">{{ $subject->name }}</div>
                                                @if($subject->code)
                                                    <span class="text-[10px] font-mono font-bold text-sky-300 bg-sky-500/10 px-2 py-0.5 rounded border border-sky-500/20 shadow-sm">{{ $subject->code }}</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                @php
                                                    $badgeClass = match($subject->group) {
                                                        'A' => 'bg-sky-500/10 text-sky-400 border-sky-500/20',
                                                        'B' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                                        'C' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                                        'P5' => 'bg-slate-800 text-slate-300 border-white/10',
                                                        default => 'bg-slate-800 text-slate-300 border-white/10'
                                                    };
                                                @endphp
                                                <span class="px-3 py-1.5 rounded-xl text-xs font-black border {{ $badgeClass }}">
                                                    Kelompok {{ $subject->group }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-5 text-right whitespace-nowrap">
                                                <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity transform translate-x-2 group-hover:translate-x-0 duration-200">
                                                    
                                                    <button @click="openEdit({{ $subject }})"
                                                        class="w-9 h-9 flex items-center justify-center rounded-xl bg-sky-500/10 border border-sky-500/20 text-sky-400 hover:text-white hover:bg-sky-500 transition-all shadow-sm" title="Edit Mapel">
                                                        <i class="ph-bold ph-pencil-simple text-lg"></i>
                                                    </button>

                                                    <form action="{{ route('subjects.destroy', $subject->id) }}" 
                                                          method="POST" 
                                                          id="delete-subject-{{ $subject->id }}">
                                                        @csrf @method('DELETE')
                                                        
                                                        <button type="button" 
                                                                onclick="confirmDelete('{{ $subject->id }}', '{{ $subject->name }}')"
                                                                class="w-9 h-9 flex items-center justify-center rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 hover:text-white hover:bg-rose-500 transition-all shadow-sm" title="Hapus Mapel">
                                                            <i class="ph-bold ph-trash text-lg"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-6 py-20 text-center">
                                                <div class="w-16 h-16 bg-slate-900/80 border border-white/10 rounded-full flex items-center justify-center mx-auto mb-4 text-sky-400 shadow-inner">
                                                    <i class="ph-duotone ph-books text-4xl"></i>
                                                </div>
                                                <p class="text-sm font-bold text-slate-400">Belum ada mata pelajaran.</p>
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
                class="absolute inset-0 bg-slate-950/80 backdrop-blur-md transition-opacity"></div>

            <div x-show="editModalOpen"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                class="bg-gradient-to-b from-[#031d3d] via-[#021124] to-[#020b18] text-white rounded-[2rem] shadow-2xl w-full max-w-md overflow-hidden relative z-10 border border-white/10">
                
                <div class="bg-slate-900/60 p-6 flex justify-between items-center border-b border-white/10 text-white">
                    <h3 class="text-lg font-black flex items-center gap-2">
                        <i class="ph-bold ph-pencil-simple text-sky-400"></i> Edit Mata Pelajaran
                    </h3>
                    <button @click="editModalOpen = false" class="w-8 h-8 flex items-center justify-center rounded-full bg-slate-800 text-slate-400 hover:text-white shadow-sm transition-colors border border-white/10">
                        <i class="ph-bold ph-x"></i>
                    </button>
                </div>
                
                <form :action="editData.actionUrl" method="POST" class="p-8 space-y-5">
                    @csrf
                    @method('PUT')
                    
                    <div>
                        <label class="block text-xs font-bold text-sky-400 uppercase tracking-wider mb-2 ml-1">Nama Mapel</label>
                        <input type="text" name="name" id="edit_name" x-model="editData.name" required 
                               class="w-full px-4 py-3.5 rounded-2xl border border-white/10 bg-slate-900/80 text-white focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 text-sm font-bold transition-all shadow-sm">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-sky-400 uppercase tracking-wider mb-2 ml-1">Kode</label>
                            <input type="text" name="code" x-model="editData.code" 
                                   class="w-full px-4 py-3.5 rounded-2xl border border-white/10 bg-slate-900/80 text-white focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 text-sm font-bold uppercase transition-all shadow-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-sky-400 uppercase tracking-wider mb-2 ml-1">No. Urut</label>
                            <input type="number" name="order" x-model="editData.order" required 
                                   class="w-full px-4 py-3.5 rounded-2xl border border-white/10 bg-slate-900/80 text-white focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 text-sm font-bold transition-all shadow-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-sky-400 uppercase tracking-wider mb-2 ml-1">Kelompok</label>
                        <div class="relative">
                            <select name="group" x-model="editData.group" class="w-full px-4 py-3.5 rounded-2xl border border-white/10 bg-slate-900/80 text-white focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 text-sm font-bold appearance-none cursor-pointer transition-all shadow-sm [color-scheme:dark]">
                                <option value="A" class="bg-slate-900 text-white">Kelompok A (Umum)</option>
                                <option value="B" class="bg-slate-900 text-white">Kelompok B (Muatan Lokal)</option>
                                <option value="C" class="bg-slate-900 text-white">Kelompok C (Peminatan)</option>
                                <option value="P5" class="bg-slate-900 text-white">Projek (P5)</option>
                            </select>
                            <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                        </div>
                    </div>

                    <div class="pt-4 flex gap-3">
                        <button type="button" @click="editModalOpen = false" class="flex-1 py-3.5 bg-slate-800 text-slate-300 font-bold rounded-2xl hover:bg-slate-700 transition-colors text-sm border border-transparent">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 py-3.5 bg-gradient-to-r from-sky-500 to-blue-600 text-white font-bold rounded-2xl hover:from-sky-400 hover:to-blue-500 transition-all shadow-lg shadow-sky-500/25 text-sm transform active:scale-95 border border-transparent">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    {{-- SweetAlert2 Library --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function confirmDelete(id, name) {
            Swal.fire({
                title: 'Hapus Mapel?',
                text: `Yakin ingin menghapus ${name}? Data nilai siswa pada mapel ini mungkin akan hilang.`,
                icon: 'warning',
                background: '#021124',
                color: '#fff',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#334155',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2rem] font-sans border border-white/10 bg-[#021124] text-white shadow-2xl',
                    confirmButton: 'bg-rose-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-2 shadow-lg shadow-rose-900/40',
                    cancelButton: 'bg-slate-800 text-slate-300 px-6 py-3 rounded-xl font-bold hover:bg-slate-700 transition-colors mx-2'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('delete-subject-' + id);
                    if (form) form.submit();
                }
            });
        }
    </script>
</x-app-layout>