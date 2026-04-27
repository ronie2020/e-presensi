<x-app-layout>
    {{-- Tambahkan SweetAlert via CDN jika belum ada di layout utama --}}
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                @if(session('success'))
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, customClass: { popup: 'rounded-xl' } });
                @endif
            });
        </script>
    @endpush

     <x-slot name="header">
        <h2 class="font-semibold text-xl text-elevate-dark leading-tight">
            {{ __('Bank Soal Terpusat') }}
        </h2>
    </x-slot>

    {{-- Root Alpine Data untuk Search & Modal --}}
    <div class="py-8 sm:py-10 font-sans text-[#2c3f61]" 
         x-data="{ 
            search: '',
            createModalOpen: false,
            
            // --- STATE UNTUK EDIT BANK ---
            editModalOpen: false,
            editUrl: '',
            editData: { id: '', title: '', subject_name: '', class_level: '' },

            openEditModal(bank, url) {
                this.editData = bank;
                this.editUrl = url;
                this.editModalOpen = true;
            },
            
            // Fungsi Pencarian Cerdas untuk Folder & Isi
            folderHasVisibleCards(folderEl) {
                if (this.search === '') return true;
                const searchLower = this.search.toLowerCase();
                
                // Jika nama folder cocok, tampilkan folder
                if (folderEl.dataset.folderName.toLowerCase().includes(searchLower)) return true;
                
                // Jika ada kartu di dalam folder yang cocok, tampilkan folder
                const cards = folderEl.querySelectorAll('.bank-card');
                for (let card of cards) {
                    if (card.dataset.search.includes(searchLower)) return true;
                }
                
                return false;
            },
            
            // Konfirmasi Hapus dengan SweetAlert
            confirmDelete(id) {
                Swal.fire({
                    title: 'Hapus Bank Soal?',
                    text: 'Semua soal di dalamnya akan ikut terhapus permanen!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    customClass: { popup: 'rounded-[2rem]' }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + id).submit();
                    }
                });
            }
         }">
         
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- HERO SECTION MICROSOFT ELEVATE THEME --}}
             <div class="relative rounded-[2rem] bg-elevate-gradient-main p-8 text-elevate-dark shadow-xl shadow-elevate-accent/10 overflow-hidden border border-white/60">
                 {{-- Abstract Shapes Ornaments --}}
                <div class="absolute -top-10 -left-10 w-48 h-48 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/20 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>
                <div class="absolute top-10 right-32 w-24 h-24 bg-white/40 rounded-2xl rotate-45 pointer-events-none shadow-sm"></div>
                
               <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <a href="{{ route('cbt.index') }}" class="text-xs font-bold text-elevate-primary hover:text-elevate-dark transition flex items-center gap-1 bg-white/60 px-3 py-1 rounded-full border border-white backdrop-blur-sm shadow-sm">
                                <i class="ph-bold ph-arrow-left"></i> Dashboard Ujian
                            </a>
                            <span class="text-elevate-dark/30 text-xs">•</span>
                            <span class="text-[10px] font-bold text-elevate-dark/70 uppercase tracking-wider">Gudang Soal</span>
                        </div>
                        <h1 class="text-4xl font-extrabold tracking-tight leading-none text-elevate-dark mb-2">Bank Soal Sekolah</h1>
                        <p class="text-elevate-dark/80 text-sm font-medium">Kelola repositori soal berdasarkan folder mata pelajaran.</p>
                    </div>
                    
                     {{-- Tombol Buat Bank Baru --}}
                    <div>
                        <button @click="createModalOpen = true" class="group flex items-center gap-3 px-6 py-4 bg-white text-elevate-dark rounded-2xl font-bold hover:bg-slate-50 transition shadow-lg shadow-elevate-dark/10 active:scale-95 border border-white">
                            <div class="w-8 h-8 rounded-full bg-elevate-accent/20 text-elevate-primary flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="ph-bold ph-folder-plus text-xl"></i>
                            </div>
                            <span>Buat Bank Soal</span>
                        </button>
                    </div>
                </div>
              
                {{-- Input Pencarian dalam Hero --}}
                <div class="mt-8 relative max-w-lg">
                    <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-elevate-dark/50 text-lg"></i>
                    <input type="text" x-model="search" placeholder="Cari nama mapel, judul paket soal, atau kode..." 
                        class="w-full pl-12 pr-4 py-3.5 bg-white/60 border border-white rounded-xl text-elevate-dark placeholder-elevate-dark/40 focus:bg-white focus:ring-elevate-accent focus:border-elevate-accent transition font-bold backdrop-blur-md shadow-sm">
                </div>
            </div>

            {{-- MODAL BUAT BANK --}}
            <div x-show="createModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">
                
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="createModalOpen = false"></div>
                <div class="flex min-h-screen items-center justify-center p-4">
                    <div class="relative bg-white rounded-[2.5rem] shadow-2xl max-w-md w-full p-8 border border-slate-100"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                        
                        <h3 class="text-xl font-black text-[#2c3f61] mb-6 flex items-center gap-2">
                            <div class="w-10 h-10 bg-[#56bbf1]/20 text-[#0d52a1] rounded-full flex items-center justify-center shrink-0"><i class="ph-fill ph-folder-plus text-xl"></i></div>
                            Bank Soal Baru
                        </h3>
                        <form action="{{ route('bank.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Judul Paket Soal</label>
                                <input type="text" name="title" required class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white font-bold text-[#2c3f61] py-3.5 px-4 focus:ring-[#56bbf1] focus:border-[#56bbf1] transition-colors" placeholder="Contoh: PTS Ganjil Tahun Ini">
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Mata Pelajaran (Folder)</label>
                                <div class="relative">
                                    <select name="subject_name" required class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white font-bold text-[#2c3f61] py-3.5 px-4 focus:ring-[#56bbf1] focus:border-[#56bbf1] appearance-none cursor-pointer transition-colors">
                                        <option value="" disabled selected>Pilih Mapel...</option>
                                        @if(isset($subjects) && $subjects->count() > 0)
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->name }}">{{ $subject->name }} ({{ $subject->code ?? '-' }})</option>
                                            @endforeach
                                        @else
                                            <option value="" disabled>Belum ada data mapel</option>
                                        @endif
                                    </select>
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                                </div>
                                @if(!isset($subjects) || $subjects->count() == 0)
                                    <p class="text-[10px] text-rose-500 mt-1 font-bold">* Tambahkan data Mata Pelajaran di menu Pengaturan terlebih dahulu.</p>
                                @endif
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tingkat Kelas</label>
                                <div class="relative">
                                    <select name="class_level" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white font-bold text-[#2c3f61] py-3.5 px-4 focus:ring-[#56bbf1] focus:border-[#56bbf1] appearance-none cursor-pointer transition-colors">
                                        <option value="" disabled selected>Pilih Tingkat Kelas...</option>
                                        <option value="7">Kelas 7</option>
                                        <option value="8">Kelas 8</option>
                                        <option value="9">Kelas 9</option>                                                                                                           
                                    </select>
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                                </div>
                            </div>

                            <div class="pt-6 flex gap-3">
                                <button type="button" @click="createModalOpen = false" class="flex-1 py-3.5 rounded-xl border border-slate-200 bg-white font-bold text-slate-600 hover:bg-slate-50 transition-colors shadow-sm">Batal</button>
                                <button type="submit" class="flex-1 py-3.5 rounded-xl bg-[#2c3f61] text-white font-bold hover:bg-[#1c2940] shadow-lg shadow-[#2c3f61]/30 transition-colors">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- MODAL EDIT BANK --}}
            <div x-show="editModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0">
                
                <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="editModalOpen = false"></div>
                <div class="flex min-h-screen items-center justify-center p-4">
                    <div class="relative bg-white rounded-[2.5rem] shadow-2xl max-w-md w-full p-8 border border-slate-100"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                        
                        <h3 class="text-xl font-black text-[#2c3f61] mb-6 flex items-center gap-2">
                            <div class="w-10 h-10 bg-[#f9a282]/20 text-[#c86845] rounded-full flex items-center justify-center shrink-0"><i class="ph-fill ph-pencil-simple text-xl"></i></div>
                            Edit Bank Soal
                        </h3>
                        <form :action="editUrl" method="POST" class="space-y-4">
                            @csrf
                            @method('PUT')
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Judul Paket Soal</label>
                                <input type="text" name="title" x-model="editData.title" required class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white font-bold text-[#2c3f61] py-3.5 px-4 focus:ring-[#56bbf1] focus:border-[#56bbf1] transition-colors">
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Mata Pelajaran (Folder)</label>
                                <div class="relative">
                                    <select name="subject_name" x-model="editData.subject_name" required class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white font-bold text-[#2c3f61] py-3.5 px-4 focus:ring-[#56bbf1] focus:border-[#56bbf1] appearance-none cursor-pointer transition-colors">
                                        <option value="" disabled>Pilih Mapel...</option>
                                        @if(isset($subjects) && $subjects->count() > 0)
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->name }}">{{ $subject->name }} ({{ $subject->code ?? '-' }})</option>
                                            @endforeach
                                        @else
                                            <option value="" disabled>Belum ada data mapel</option>
                                        @endif
                                    </select>
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tingkat Kelas</label>
                                <div class="relative">
                                    <select name="class_level" x-model="editData.class_level" required class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white font-bold text-[#2c3f61] py-3.5 px-4 focus:ring-[#56bbf1] focus:border-[#56bbf1] appearance-none cursor-pointer transition-colors">
                                        <option value="" disabled>Pilih Tingkat Kelas...</option>
                                        <option value="7">Kelas 7</option>
                                        <option value="8">Kelas 8</option>
                                        <option value="9">Kelas 9</option>                                                                                                           
                                    </select>
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                                </div>
                            </div>

                            <div class="pt-6 flex gap-3">
                                <button type="button" @click="editModalOpen = false" class="flex-1 py-3.5 rounded-xl border border-slate-200 bg-white font-bold text-slate-600 hover:bg-slate-50 transition-colors shadow-sm">Batal</button>
                                <button type="submit" class="flex-1 py-3.5 rounded-xl bg-[#2c3f61] text-white font-bold hover:bg-[#1c2940] shadow-lg shadow-[#2c3f61]/30 transition-colors">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- LOGIKA PENGELOMPOKAN FOLDER --}}
            @php
                $bankItems = method_exists($banks, 'items') ? collect($banks->items()) : collect($banks);
                $groupedBanks = $bankItems->sortBy('subject_name')->groupBy('subject_name');
            @endphp

            {{-- LIST FOLDER & BANK SOAL --}}
            <div class="space-y-10">
                @forelse($groupedBanks as $subjectName => $subjectBanks)
                    
                    {{-- WRAPPER FOLDER --}}
                    <div class="folder-group" data-folder-name="{{ $subjectName }}" x-show="folderHasVisibleCards($el)" x-transition.duration.300ms>
                        
                        {{-- HEADER FOLDER --}}
                        <div class="flex items-center gap-4 mb-5">
                            <div class="bg-white border border-slate-200 rounded-[1rem] p-3 pr-5 flex items-center gap-3 shadow-sm w-max hover:shadow-md transition-shadow cursor-default group/folder">
                                <div class="w-10 h-10 rounded-[0.75rem] flex items-center justify-center shadow-sm border bg-[#e5eff5] border-[#56bbf1]/30 text-[#0d52a1] group-hover/folder:bg-[#0d52a1] group-hover/folder:text-white transition-colors">
                                    <i class="ph-fill ph-folder-open text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-[10px] uppercase font-black tracking-wider text-slate-400 mb-0.5">Folder Mata Pelajaran</p>
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-sm font-black text-[#2c3f61]">{{ $subjectName }}</h3>
                                        <span class="text-[10px] bg-slate-100 text-slate-500 font-bold px-1.5 py-0.5 rounded-md">{{ count($subjectBanks) }} File</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-1 h-px bg-gradient-to-r from-slate-200 to-transparent"></div>
                        </div>

                        {{-- GRID KARTU DALAM FOLDER --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @foreach($subjectBanks as $bank)
                                <div class="bank-card bg-white border border-slate-100 rounded-[2rem] p-6 hover:shadow-xl hover:shadow-[#56bbf1]/10 hover:border-[#56bbf1]/50 transition-all duration-300 group relative flex flex-col h-full"
                                     data-search="{{ strtolower($bank->title . ' ' . $bank->subject_name . ' ' . $bank->code) }}"
                                     x-show="search === '' || $el.dataset.search.includes(search.toLowerCase()) || $el.closest('.folder-group').dataset.folderName.toLowerCase().includes(search.toLowerCase())"
                                     x-transition.duration.300ms>
                                    
                                    {{-- Info Atas (Kelas & Kode) --}}
                                    <div class="mb-4">
                                        <div class="flex justify-between items-start mb-3">
                                            <span class="inline-block px-3 py-1 bg-[#56bbf1]/10 text-[#0d52a1] border border-[#56bbf1]/20 rounded-lg text-[10px] font-black uppercase tracking-wide">
                                                Kelas {{ $bank->class_level }}
                                            </span>
                                            <span class="text-[10px] bg-slate-50 px-2 py-0.5 rounded border border-slate-100 font-mono text-slate-400 group-hover:text-[#0d52a1] transition" title="Kode Bank Soal">#{{ $bank->code }}</span>
                                        </div>
                                        <h4 class="font-black text-xl text-[#2c3f61] leading-tight group-hover:text-[#0d52a1] transition-colors line-clamp-2">
                                            {{ $bank->title }}
                                        </h4>
                                    </div>
                                    
                                    {{-- File Tally --}}
                                    <div class="flex-1 flex items-end mb-5">
                                        <div class="flex items-center gap-2 text-slate-500 text-xs font-bold bg-[#e5eff5]/40 group-hover:bg-[#e5eff5] px-4 py-2.5 rounded-xl w-full border border-slate-100 transition-colors">
                                            <i class="ph-duotone ph-files text-[#0d52a1] text-lg shrink-0"></i>
                                            <span class="text-[#2c3f61] text-sm">{{ $bank->questions_count }}</span> Soal Tersedia
                                        </div>
                                    </div>

                                    {{-- Footer Actions --}}
                                    <div class="pt-4 border-t border-slate-50 flex gap-2">
                                        <a href="{{ route('bank.manage', $bank->id) }}" class="flex-1 flex items-center justify-center p-2.5 bg-[#2c3f61] text-white rounded-xl text-xs font-bold hover:bg-[#1c2940] transition-all shadow-lg shadow-[#2c3f61]/20 active:scale-95" title="Kelola Soal">
                                            <i class="ph-bold ph-list-plus text-lg mr-2"></i> Isi Soal
                                        </a>
                                        
                                        {{-- Button Edit --}}
                                        <button type="button" @click="openEditModal({{ json_encode(['id' => $bank->id, 'title' => $bank->title, 'subject_name' => $bank->subject_name, 'class_level' => $bank->class_level]) }}, '{{ route('bank.update', $bank->id) }}')" class="w-10 h-10 flex items-center justify-center bg-white border border-[#f9a282]/50 text-[#c86845] rounded-xl hover:bg-[#f9a282]/10 hover:border-[#f9a282] transition-all active:scale-95" title="Edit Identitas Bank Soal">
                                            <i class="ph-bold ph-pencil-simple text-lg"></i>
                                        </button>

                                        {{-- Form Delete --}}
                                        <form id="delete-form-{{ $bank->id }}" action="{{ route('bank.destroy', $bank->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="button" @click="confirmDelete({{ $bank->id }})" class="w-10 h-10 flex items-center justify-center bg-white border border-rose-200 text-rose-500 rounded-xl hover:bg-rose-50 hover:border-rose-300 hover:text-rose-600 transition-all active:scale-95" title="Hapus Bank Soal">
                                                <i class="ph-bold ph-trash text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @empty
                    {{-- EMPTY STATE --}}
                    <div class="col-span-full text-center py-20 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200">
                        <div class="w-24 h-24 bg-[#56bbf1]/10 text-[#0d52a1] rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="ph-duotone ph-folder-dashed text-5xl"></i>
                        </div>
                       <h3 class="text-elevate-dark font-bold text-xl mb-2">Gudang Soal Masih Kosong</h3>
                        <p class="text-slate-500 text-sm max-w-sm mx-auto mb-6">Buat bank soal pertama Anda untuk mulai menabung butir soal berdasarkan mata pelajaran.</p>
                        <button @click="createModalOpen = true" class="inline-flex items-center gap-2 px-6 py-3 bg-elevate-primary text-white rounded-xl font-bold hover:bg-elevate-dark transition shadow-lg shadow-elevate-primary/20 text-sm">
                            <i class="ph-bold ph-plus"></i> Buat Bank Soal
                        </button>
                    </div>
                @endforelse
                
                {{-- State Kosong Pencarian --}}
                <div x-show="search !== '' && document.querySelectorAll('.bank-card[style*=\'display: none\']').length === document.querySelectorAll('.bank-card').length" 
                     class="col-span-full text-center py-16" style="display: none;">
                    <div class="w-20 h-20 bg-elevate-accent/10 text-elevate-primary/50 rounded-full flex items-center justify-center mx-auto mb-4">
                         <i class="ph-duotone ph-magnifying-glass text-4xl"></i>
                    </div>
                    <p class="text-elevate-dark font-bold text-lg mb-1">Hasil Tidak Ditemukan</p>
                    <p class="text-slate-500 text-sm">Coba gunakan kata kunci pencarian yang lain.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>