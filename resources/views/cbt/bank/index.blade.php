<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-elevate-dark leading-tight">
            {{ __('Bank Soal - Kategori Folder') }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-10 font-sans text-elevate-dark relative overflow-hidden min-h-screen" 
         x-data="{ 
            openModal: false, 
            editModal: false,
            editFormAction: '',
            editName: '',
            editDesc: '',
            openEditModal(btn) {
                this.editName = btn.dataset.name;
                this.editDesc = btn.dataset.desc;
                this.editFormAction = btn.dataset.action;
                this.editModal = true;
            }
         }">

        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            {{-- NOTIFIKASI --}}
            @if(session('success'))
                <div id="flash-success" data-message="{{ session('success') }}"></div>
            @endif

            {{-- HERO SECTION --}}
            <x-hero-section
                badge="Gudang Soal CBT"
                badgeIcon="ph-stack"
                title="Bank Soal"
                titleHighlight="Sekolah Terpadu"
                description="Kelola arsip butir soal dan kisi-kisi berdasarkan folder tahun ajaran atau jenis asesmen agar tertata rapi."
                :chips="[
                    ['icon' => 'ph-folder-notch-open', 'label' => count($categories ?? []) . ' Folder Tersimpan'],
                    ['icon' => 'ph-check-circle', 'label' => 'Validasi Otomatis'],
                    ['icon' => 'ph-lock-key', 'label' => 'Aman & Terenkripsi']
                ]"
                heroIcon="ph-stack"
                :showcaseNumber="count($categories ?? [])"
                showcaseLabel="Total Folder"
                statusOrb="Arsip Aktif"
                statusColor="sky"
            >
                <x-slot:cta>
                    <div class="flex flex-wrap gap-3">
                        <button @click="openModal = true" class="px-6 py-3 rounded-2xl bg-gradient-to-r from-[#56bbf1] to-[#0d52a1] text-white font-black text-xs uppercase tracking-wider shadow-lg shadow-[#56bbf1]/30 hover:shadow-[#56bbf1]/50 hover:scale-[1.02] active:scale-95 transition-all flex items-center gap-2 border border-white/20">
                            <i class="ph-bold ph-folder-plus text-base"></i>
                            <span>Buat Folder Baru</span>
                        </button>
                        <a href="{{ route('cbt.index') }}" class="px-5 py-3 rounded-2xl bg-white/10 hover:bg-white/20 text-slate-200 hover:text-white font-bold text-xs tracking-wide border border-white/15 backdrop-blur-md transition-all flex items-center gap-2">
                            <i class="ph-bold ph-desktop text-base"></i>
                            <span>Menu Ujian CBT</span>
                        </a>
                        <a href="{{ route('dashboard') }}" class="px-4 py-3 rounded-2xl bg-white/5 hover:bg-white/10 text-slate-300 hover:text-white font-bold text-xs tracking-wide border border-white/10 transition-all flex items-center gap-1.5">
                            <i class="ph-bold ph-arrow-left text-sm"></i> Dashboard
                        </a>
                    </div>
                </x-slot:cta>
            </x-hero-section>

            {{-- HEADER LIST & FILTER --}}
            <h3 class="font-bold text-elevate-dark text-xl flex items-center gap-3 mb-6">
                <div class="w-2 h-6 bg-elevate-accent rounded-full"></div>
                Daftar Folder Tersedia
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($bankFolders as $folder)
                    <div class="bg-elevate-surface border border-slate-200 rounded-[2rem] hover:shadow-xl hover:border-elevate-accent/50 transition-all duration-300 group relative block overflow-hidden">
                        
                        <div class="absolute inset-0 bg-elevate-soft/0 group-hover:bg-elevate-soft/30 transition-colors pointer-events-none z-0"></div>

                        <div class="absolute top-5 right-5 z-20 opacity-0 group-hover:opacity-100 transition-opacity translate-y-1 group-hover:translate-y-0">
                            <button type="button" @click="openEditModal($event.currentTarget)"
                                    data-name="{{ $folder->name }}" data-desc="{{ $folder->description }}" data-action="{{ route('bank.folder.update', $folder->id) }}"
                                    class="w-10 h-10 flex items-center justify-center bg-white/90 backdrop-blur-sm text-slate-600 rounded-xl hover:bg-elevate-peach hover:text-white shadow-sm border border-slate-200 transition-all">
                                <i class="ph-bold ph-pencil-simple text-lg"></i>
                            </button>
                        </div>

                        <a href="{{ route('bank.show', $folder->id) }}" class="p-6 block relative z-10 h-full">
                            <div class="w-14 h-14 bg-elevate-soft text-elevate-primary rounded-2xl flex items-center justify-center text-3xl mb-4 group-hover:bg-elevate-primary group-hover:text-white transition-all border border-elevate-primary/10">
                                <i class="ph-duotone ph-folder-open"></i>
                            </div>
                            <h4 class="font-black text-2xl text-elevate-dark mb-2 group-hover:text-elevate-primary transition-colors">{{ $folder->name }}</h4>
                            <p class="text-slate-500 text-sm font-medium line-clamp-2 mb-4 h-10">{{ $folder->description ?? 'Tidak ada deskripsi.' }}</p>
                            
                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between mt-auto">
                                <div class="flex items-center gap-2 text-elevate-dark font-bold text-xs bg-elevate-soft px-3 py-1.5 rounded-full border border-elevate-primary/10">
                                    <i class="ph-bold ph-books text-elevate-primary"></i> {{ $folder->banks_count ?? 0 }} Mapel
                                </div>
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-elevate-primary group-hover:text-white transition-all"><i class="ph-bold ph-arrow-right"></i></div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-span-full text-center py-20 bg-elevate-surface rounded-[2.5rem] border-2 border-dashed border-slate-200">
                        <div class="w-24 h-24 bg-elevate-peach-light rounded-full flex items-center justify-center mx-auto mb-6 text-elevate-primary">
                            <i class="ph-duotone ph-folder-dashed text-5xl"></i>
                        </div>
                        <h3 class="text-elevate-dark font-bold text-xl mb-2">Belum Ada Folder</h3>
                        <p class="text-elevate-dark/70 max-w-xs mx-auto mb-8 text-sm">Buat Folder/Kegiatan pertama Anda, seperti "Bank Soal 2026".</p>
                        <button @click="openModal = true" class="px-6 py-3 bg-elevate-dark text-white rounded-full font-bold hover:bg-elevate-primary text-sm mt-4 transition-colors">
                            Buat Folder Pertama
                        </button>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- MODAL CREATE FOLDER --}}
        <div x-show="openModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" style="display: none;">
            <div @click.away="openModal = false" class="bg-elevate-surface rounded-[2rem] w-full max-w-md p-6 shadow-2xl border border-slate-100">
                <h3 class="text-xl font-black text-elevate-dark mb-4">Buat Folder Bank Soal</h3>
                <form action="{{ route('bank.folder.store') }}" method="POST">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Nama Folder</label>
                            <input type="text" name="name" required class="w-full rounded-2xl border-slate-200 bg-slate-50 py-3.5 px-4 focus:bg-white focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Deskripsi</label>
                            <textarea name="description" rows="2" class="w-full rounded-2xl border-slate-200 bg-slate-50 py-3.5 px-4 focus:bg-white focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent transition-all"></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex gap-3">
                        <button type="button" @click="openModal = false" class="flex-1 py-3.5 border-2 border-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-50 transition-colors">Batal</button>
                        <button type="submit" class="flex-1 py-3.5 bg-elevate-dark text-white rounded-xl font-bold hover:bg-elevate-primary shadow-lg shadow-elevate-dark/20 transition-all">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL EDIT FOLDER --}}
        <div x-show="editModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4" style="display: none;">
            <div @click.away="editModal = false" class="bg-elevate-surface rounded-[2rem] w-full max-w-md p-6 shadow-2xl border border-slate-100">
                <h3 class="text-xl font-black text-elevate-dark mb-4">Edit Folder</h3>
                <form :action="editFormAction" method="POST">
                    @csrf @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Nama Folder</label>
                            <input type="text" name="name" x-model="editName" required class="w-full rounded-2xl border-slate-200 bg-slate-50 py-3.5 px-4 focus:bg-white focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Deskripsi</label>
                            <textarea name="description" x-model="editDesc" rows="2" class="w-full rounded-2xl border-slate-200 bg-slate-50 py-3.5 px-4 focus:bg-white focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent transition-all"></textarea>
                        </div>
                    </div>
                    <div class="mt-6 flex gap-3 flex-wrap">
                        <button type="button" @click="editModal = false" class="flex-1 py-3.5 border-2 border-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-50 transition-colors">Batal</button>
                        <button type="submit" class="flex-1 py-3.5 bg-elevate-dark text-white rounded-xl font-bold hover:bg-elevate-primary shadow-lg shadow-elevate-dark/20 transition-all">Update</button>
                    </div>
                    <div class="mt-4 border-t border-slate-100 pt-4">
                        <button type="button" onclick="confirmDelete(this.dataset.deleteUrl)" :data-delete-url="editFormAction" class="w-full py-3.5 bg-rose-50 text-rose-600 rounded-xl font-bold hover:bg-rose-100 flex items-center justify-center gap-2 transition-colors">
                            <i class="ph-bold ph-trash"></i> Hapus Folder
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <form id="delete-folder-form" action="" method="POST" class="hidden">@csrf @method('DELETE')</form>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(url) {
            Swal.fire({ title: 'Yakin Hapus?', text: "Semua Mapel & Soal di folder ini akan ikut terhapus permanen!", icon: 'warning', showCancelButton: true, confirmButtonColor: '#e11d48', confirmButtonText: 'Ya, Hapus!', customClass: { popup: 'rounded-[2rem]' }
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = document.getElementById('delete-folder-form');
                    form.action = url; form.submit();
                }
            })
        }
    </script>
</x-app-layout>