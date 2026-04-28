<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-elevate-dark leading-tight">
            {{ __('CBT Dashboard - Kategori Kegiatan') }}
        </h2>
    </x-slot>

    {{-- Tambahkan state untuk Edit Modal pada x-data --}}
    <div class="py-8 sm:py-10 font-sans text-elevate-dark bg-elevate-surface min-h-screen relative overflow-hidden" 
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
        
        {{-- Efek Latar Belakang Halus (Terinspirasi Poster Elevate) --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            {{-- NOTIFIKASI --}}
            @if(session('success'))
                <div id="flash-success" data-message="{{ session('success') }}"></div>
            @endif
            @if(session('error'))
                <div id="flash-error" data-message="{{ session('error') }}"></div>
            @endif

            {{-- HERO SECTION GRID --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                
                {{-- Kartu Info Utama (Span 2 Kolom) - Classic Style with Stronger Elevate Colors --}}
                <div class="md:col-span-2 relative rounded-[2rem] bg-gradient-to-r from-elevate-accent via-elevate-peach-light to-elevate-peach p-8 md:p-10 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden border border-white/60">
                    
                    {{-- Bentuk Dekoratif (Dikembalikan ke gaya awal dengan warna Elevate) --}}
                    <div class="absolute -top-10 -left-10 w-56 h-56 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-xl"></div>
                    <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/40 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-xl"></div>
                    <div class="absolute top-10 right-10 w-28 h-28 bg-white/40 rounded-[2rem] rotate-45 pointer-events-none shadow-sm backdrop-blur-md border border-white/50"></div>
                    
                    <div class="relative z-10 flex flex-col justify-center h-full">
                        <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-3 text-elevate-dark">Manajemen Ujian CBT</h1>
                        <p class="text-elevate-dark/80 text-sm font-semibold max-w-lg leading-relaxed">
                            Buat dan kelola folder kegiatan ujian (PTS, PAS, Asesmen Harian). Folder ini digunakan untuk mengelompokkan jadwal ujian siswa.
                        </p>
                    </div>
                </div>

                {{-- Kartu Aksi / Tombol Tambah (Span 1 Kolom) --}}
                <div class="bg-white rounded-[2rem] border border-slate-100 p-8 flex flex-col items-center justify-center text-center shadow-xl shadow-slate-200/50 relative overflow-hidden group">
                    <div class="w-16 h-16 bg-elevate-peach-light text-elevate-primary rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-sm border border-elevate-peach">
                        <i class="ph-bold ph-folder-plus text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-black text-elevate-dark mb-1">Kategori Baru</h3>
                    <p class="text-xs text-elevate-dark/70 font-medium mb-5">Buat folder ujian baru.</p>
                    
                    {{-- Tombol (Dikembalikan ke gaya rounded-xl) --}}
                    <button @click="openModal = true" class="w-full px-5 py-3.5 bg-elevate-dark text-white rounded-xl font-bold hover:bg-elevate-primary transition-all shadow-lg shadow-elevate-dark/30 active:scale-95">
                        Buat Sekarang
                    </button>
                </div>
            </div>

            {{-- HEADER LIST & FILTER --}}
            <form id="filterForm" action="{{ route('cbt.index') }}" method="GET" class="flex flex-col md:flex-row items-center justify-between mb-6 px-2 gap-4">
                <h3 class="font-bold text-elevate-dark text-xl flex items-center gap-3 shrink-0 w-full md:w-auto">
                    <div class="w-2 h-6 bg-elevate-accent rounded-full"></div>
                    Daftar Kegiatan CBT (Folder)
                </h3>

                <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                    {{-- Search Input --}}
                    <div class="relative w-full sm:w-64 group">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-elevate-primary transition-colors">
                            <i class="ph-bold ph-magnifying-glass"></i>
                        </div>
                        <input name="search" value="{{ request('search') }}" type="text" class="w-full pl-11 pr-4 py-2.5 rounded-full border-slate-200 bg-white text-sm focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 transition-all font-medium placeholder-slate-400 shadow-sm" placeholder="Cari Kegiatan...">
                        <button type="submit" class="hidden"></button>
                    </div>
                </div>
            </form>

            {{-- GRID CARD EVENT/FOLDER --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($events as $event)
                    <div class="bg-elevate-gradient-card border border-slate-200 rounded-[2rem] hover:shadow-2xl hover:shadow-elevate-accent/10 hover:border-elevate-accent/50 transition-all duration-300 group relative block overflow-hidden">
                        
                        <!-- Pattern Background on Hover -->
                        <div class="absolute inset-0 bg-elevate-peach-light/0 group-hover:bg-elevate-peach-light/40 transition-colors pointer-events-none z-0"></div>
                        <div class="absolute -right-4 -top-4 text-elevate-peach opacity-0 group-hover:opacity-20 transition-opacity transform group-hover:scale-110 pointer-events-none z-0">
                            <i class="ph-fill ph-folder text-[10rem]"></i>
                        </div>

                        <!-- TOMBOL EDIT FOLDER -->
                        <div class="absolute top-5 right-5 z-20 opacity-0 group-hover:opacity-100 transition-opacity translate-y-1 group-hover:translate-y-0">
                            <button type="button" 
                                    @click="openEditModal($event.currentTarget)"
                                    data-name="{{ $event->name }}"
                                    data-desc="{{ $event->description }}"
                                    data-action="{{ route('cbt.events.update', $event->id) }}"
                                    title="Edit Nama/Deskripsi Folder"
                                    class="w-10 h-10 flex items-center justify-center bg-white/90 backdrop-blur-sm text-elevate-primary rounded-xl hover:bg-elevate-primary hover:text-white shadow-sm border border-elevate-accent/30 transition-all">
                                <i class="ph-bold ph-pencil-simple text-lg"></i>
                            </button>
                        </div>

                        <!-- KONTEN UTAMA YANG BISA DI KLIK MENUJU DAFTAR SOAL -->
                        <a href="{{ route('cbt.events.show', $event->id) }}" class="p-6 block relative z-10 h-full">
                            <div class="flex items-start justify-between mb-4">
                                <div class="w-14 h-14 bg-elevate-peach-light text-elevate-primary rounded-2xl flex items-center justify-center text-3xl group-hover:scale-110 group-hover:bg-elevate-primary group-hover:text-white transition-all shadow-sm">
                                    <i class="ph-duotone ph-folder-open"></i>
                                </div>
                                <div class="w-10"></div>
                            </div>

                            <div class="flex justify-between items-center mb-2">
                                <h4 class="font-black text-2xl text-elevate-dark leading-tight group-hover:text-elevate-primary transition-colors">
                                    {{ $event->name }}
                                </h4>
                            </div>
                            
                            <p class="text-elevate-dark/70 text-sm font-medium line-clamp-2 mb-4 h-10">
                                {{ $event->description ?? 'Tidak ada deskripsi kegiatan.' }}
                            </p>

                            <div class="pt-4 border-t border-slate-100 flex items-center justify-between mt-auto">
                                <div class="flex items-center gap-2 text-elevate-primary font-bold text-sm bg-elevate-primary/10 px-4 py-2 rounded-full">
                                    <i class="ph-bold ph-files"></i> {{ $event->exams_count ?? 0 }} Jadwal
                                </div>
                                <div class="w-8 h-8 rounded-full bg-elevate-soft flex items-center justify-center text-elevate-primary/60 group-hover:bg-elevate-dark group-hover:text-white transition-all">
                                    <i class="ph-bold ph-arrow-right"></i>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-20 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200 mt-4">
                        <div class="w-24 h-24 bg-elevate-peach-light rounded-full flex items-center justify-center mx-auto mb-6 text-elevate-primary">
                            <i class="ph-duotone ph-folder-dashed text-5xl"></i>
                        </div>
                        <h3 class="text-elevate-dark font-bold text-xl mb-2">Belum Ada Kegiatan CBT</h3>
                        <p class="text-elevate-dark/70 max-w-xs mx-auto mb-8 text-sm">Buat Folder/Kegiatan pertama Anda, seperti "PSAT Genap 2026".</p>
                        <button @click="openModal = true" class="inline-flex items-center gap-2 px-6 py-3 bg-elevate-dark text-white rounded-full font-bold hover:bg-elevate-primary transition shadow-lg shadow-elevate-dark/30 text-sm">
                            <i class="ph-bold ph-plus"></i> Buat Kegiatan Baru
                        </button>
                    </div>
                @endforelse
            </div>

            {{-- PAGINATION --}}
            @if(method_exists($events, 'links'))
                <div class="mt-8">
                    {{ $events->links() }}
                </div>
            @endif
        </div>

        {{-- MODAL TAMBAH KEGIATAN --}}
        <div x-show="openModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div x-show="openModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-elevate-dark/60 backdrop-blur-sm" @click="openModal = false"></div>

                <div x-show="openModal" x-transition.scale.origin.bottom class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-[2rem] sm:my-8 sm:p-8 border border-slate-100">
                    <div class="absolute top-0 right-0 pt-6 pr-6">
                        <button @click="openModal = false" class="text-elevate-dark/60 hover:text-elevate-dark bg-elevate-soft hover:bg-elevate-peach-light rounded-full p-2 transition">
                            <i class="ph-bold ph-x text-lg"></i>
                        </button>
                    </div>

                    <div class="sm:flex sm:items-start mb-6">
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-elevate-peach-light rounded-2xl sm:mx-0 sm:h-12 sm:w-12 text-elevate-primary border border-elevate-peach">
                            <i class="ph-bold ph-folder-plus text-2xl"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-xl font-black leading-6 text-elevate-dark" id="modal-title">Buat Kegiatan CBT Baru</h3>
                            <div class="mt-2">
                                <p class="text-sm text-elevate-dark/70 font-medium">Contoh: Penilaian Sumatif Akhir Tahun, Try Out UNBK, dll.</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('cbt.events.store') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Nama Kegiatan <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" required class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-5 transition-all" placeholder="Misal: PSAT Kelas 7, 8, 9">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Deskripsi Singkat (Opsional)</label>
                                <textarea name="description" rows="2" class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-medium text-elevate-dark py-3.5 px-5 transition-all" placeholder="Tahun Ajaran 2025/2026..."></textarea>
                            </div>
                        </div>

                        <div class="mt-8 flex gap-3">
                            <button type="button" @click="openModal = false" class="w-full inline-flex justify-center px-4 py-3.5 border-2 border-slate-100 shadow-sm text-sm font-bold rounded-full text-elevate-dark bg-white hover:bg-elevate-soft transition-colors">Batal</button>
                            <button type="submit" class="w-full inline-flex justify-center px-4 py-3.5 border border-transparent shadow-lg shadow-elevate-dark/20 text-sm font-bold rounded-full text-white bg-elevate-dark hover:bg-elevate-primary transition-colors">Simpan Kegiatan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL EDIT KEGIATAN --}}
        <div x-show="editModal" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
            <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
                <div x-show="editModal" x-transition.opacity class="fixed inset-0 transition-opacity bg-elevate-dark/60 backdrop-blur-sm" @click="editModal = false"></div>

                <div x-show="editModal" x-transition.scale.origin.bottom class="relative inline-block w-full max-w-md p-6 overflow-hidden text-left align-middle transition-all transform bg-white shadow-2xl rounded-[2rem] sm:my-8 sm:p-8 border border-slate-100">
                    <div class="absolute top-0 right-0 pt-6 pr-6">
                        <button @click="editModal = false" class="text-elevate-dark/60 hover:text-elevate-dark bg-elevate-soft hover:bg-elevate-peach-light rounded-full p-2 transition">
                            <i class="ph-bold ph-x text-lg"></i>
                        </button>
                    </div>

                    <div class="sm:flex sm:items-start mb-6">
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-elevate-peach/20 rounded-2xl sm:mx-0 sm:h-12 sm:w-12 text-elevate-peach border border-elevate-peach/50">
                            <i class="ph-bold ph-pencil-simple text-2xl"></i>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-xl font-black leading-6 text-elevate-dark">Edit Kegiatan CBT</h3>
                            <div class="mt-2">
                                <p class="text-sm text-elevate-dark/70 font-medium">Perbarui nama atau deskripsi dari folder kegiatan ini.</p>
                            </div>
                        </div>
                    </div>

                    <form :action="editFormAction" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Nama Kegiatan <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" x-model="editName" required class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-5 transition-all" placeholder="Misal: PSAT Kelas 7, 8, 9">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Deskripsi Singkat (Opsional)</label>
                                <textarea name="description" x-model="editDesc" rows="2" class="w-full rounded-2xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-medium text-elevate-dark py-3.5 px-5 transition-all" placeholder="Tahun Ajaran 2025/2026..."></textarea>
                            </div>
                        </div>

                        <div class="mt-8 flex gap-3">
                            <button type="button" @click="editModal = false" class="w-full inline-flex justify-center px-4 py-3.5 border-2 border-slate-100 shadow-sm text-sm font-bold rounded-full text-elevate-dark bg-white hover:bg-elevate-soft transition-colors">Batal</button>
                            <button type="submit" class="w-full inline-flex justify-center px-4 py-3.5 border border-transparent shadow-lg shadow-elevate-dark/20 text-sm font-bold rounded-full text-white bg-elevate-dark hover:bg-elevate-primary transition-colors">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const flashSuccess = document.getElementById('flash-success');
            if (flashSuccess) {
                Swal.fire({
                    icon: 'success', title: 'Berhasil!',
                    text: flashSuccess.getAttribute('data-message'),
                    timer: 3000, showConfirmButton: false,
                    customClass: { popup: 'rounded-[2rem]' }
                });
            }
            const flashError = document.getElementById('flash-error');
            if (flashError) {
                Swal.fire({
                    icon: 'error', title: 'Gagal!',
                    text: flashError.getAttribute('data-message'),
                    customClass: { popup: 'rounded-[2rem]' }
                });
            }
        });
    </script>
</x-app-layout>