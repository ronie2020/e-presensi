<x-app-layout>
    {{-- Load SweetAlert --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans text-slate-100 bg-[#020b18] min-h-screen" x-data="{ addModalOpen: false, editModalOpen: false, editData: {} }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
            {{-- HERO SECTION --}}
            <div class="mb-8 sm:mb-10 relative z-10">
                <x-hero-section
                    badge="MODUL KESISWAAN"
                    badgeIcon="ph-fill ph-star"
                    showcaseIcon="ph-duotone ph-sparkle"
                    showcaseTitle="Ekstrakurikuler"
                    showcaseSubtitle="Bakat & Minat Siswa">
                    <x-slot:title>
                        <span class="block text-slate-100">Manajemen</span>
                        <span class="block mt-1 sm:mt-1.5 text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] via-sky-200 to-[#38bdf8]">
                            Ekstrakurikuler
                        </span>
                    </x-slot:title>
                    <x-slot:description>
                        Wadahi bakat dan minat siswa. Kelola jadwal latihan, pantau keanggotaan, dan rekap kehadiran kegiatan dalam satu panel.
                    </x-slot:description>
                    <x-slot:chips>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-calendar text-sky-400"></i> Jadwal Mingguan
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-users-three text-emerald-400"></i> Keanggotaan
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-clipboard-text text-cyan-400"></i> Presensi Khusus
                        </span>
                    </x-slot:chips>
                    <x-slot:cta>
                        <button @click="addModalOpen = true"
                                class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gradient-to-r from-[#0d52a1] via-sky-600 to-[#56bbf1] hover:brightness-110 text-white font-bold text-sm shadow-lg shadow-sky-950/50 transition-all duration-300 hover:scale-[1.02] active:scale-[0.98]">
                            <i class="ph-bold ph-plus-circle text-lg"></i>
                            <span>Tambah Ekskul</span>
                        </button>
                        <a href="{{ route('extracurriculars.reports') }}"
                           class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-slate-800/80 hover:bg-slate-700 text-slate-200 hover:text-white font-bold text-sm border border-slate-700 transition-all duration-300">
                            <i class="ph-bold ph-file-text text-lg text-[#56bbf1]"></i>
                            <span>Laporan Absensi</span>
                        </a>
                    </x-slot:cta>
                    <x-slot:showcaseStats>
                        @php $totalMembers = $extracurriculars->sum('members_count'); @endphp
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-2xl bg-slate-900/80 border border-slate-700/80 backdrop-blur-md">
                            <i class="ph-fill ph-star text-amber-400 text-sm"></i>
                            <span class="text-xs font-bold text-slate-300">Kegiatan:</span>
                            <span class="text-sm font-black text-white font-mono">{{ $extracurriculars->count() }}</span>
                        </div>
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-2xl bg-slate-900/80 border border-emerald-500/30 backdrop-blur-md">
                            <i class="ph-fill ph-users text-emerald-400 text-sm"></i>
                            <span class="text-xs font-bold text-slate-300">Siswa Aktif:</span>
                            <span class="text-sm font-black text-emerald-400 font-mono">{{ $totalMembers }}</span>
                        </div>
                    </x-slot:showcaseStats>
                </x-hero-section>
            </div>
        </div>

        {{-- SweetAlert Flash Message Handling --}}
        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: "{{ session('success') }}",
                        timer: 3000,
                        showConfirmButton: false,
                        toast: true,
                        position: 'top-end',
                        background: '#031d3d',
                        color: '#ffffff'
                    });
                });
            </script>
        @endif

        {{-- Grid Card Layout --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($extracurriculars as $ekskul)
                    <div class="bg-gradient-to-br from-[#031d3d]/90 via-[#021124]/95 to-[#031d3d]/90 backdrop-blur-xl rounded-[2rem] border border-white/10 p-6 hover:shadow-2xl hover:border-[#56bbf1]/30 transition-all duration-300 group flex flex-col h-full relative overflow-hidden">
                        
                        {{-- Header Card --}}
                        <div class="flex items-start justify-between mb-6 relative z-10">
                            <div class="w-16 h-16 shrink-0 rounded-2xl bg-[#56bbf1]/10 border border-[#56bbf1]/20 flex items-center justify-center text-[#56bbf1] text-3xl shadow-sm group-hover:shadow-md group-hover:bg-gradient-to-r group-hover:from-[#0d52a1] group-hover:to-[#56bbf1] group-hover:text-white transition-all duration-300">
                                @if(Str::startsWith($ekskul->icon, 'storage/'))
                                    <img src="{{ asset($ekskul->icon) }}" class="w-full h-full object-cover rounded-2xl">
                                @elseif(Str::startsWith($ekskul->icon, 'http'))
                                    <img src="{{ $ekskul->icon }}" class="w-full h-full object-cover rounded-2xl">
                                @else
                                    <i class="{{ $ekskul->icon ?? 'ph-fill ph-star' }}"></i>
                                @endif
                            </div>
                            
                            {{-- Dropdown Actions --}}
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" @click.away="open = false" class="w-10 h-10 flex items-center justify-center rounded-xl text-slate-400 hover:bg-white/10 hover:text-white transition-colors">
                                    <i class="ph-bold ph-dots-three-vertical text-xl"></i>
                                </button>
                                <div x-show="open" class="absolute right-0 mt-2 w-48 bg-[#031d3d] rounded-2xl shadow-2xl border border-white/15 z-20 py-2" style="display: none;" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100">
                                    
                                    <button @click="
                                        editModalOpen = true; 
                                        editData = JSON.parse('{{ json_encode($ekskul, JSON_HEX_APOS | JSON_HEX_QUOT) }}');
                                        open = false;
                                        setTimeout(() => setupEditForm(editData), 50);
                                    " class="w-full text-left px-4 py-2.5 text-xs font-bold text-slate-300 hover:bg-white/10 hover:text-[#56bbf1] flex items-center gap-2 transition-colors">
                                        <i class="ph-bold ph-pencil-simple text-base"></i> Edit Data
                                    </button>
                                    
                                    <div class="border-t border-white/10 my-1"></div>

                                    <form action="{{ route('extracurriculars.destroy', $ekskul->id) }}" method="POST" class="delete-form">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn-delete w-full text-left px-4 py-2.5 text-xs font-bold text-rose-400 hover:bg-rose-500/10 flex items-center gap-2 transition-colors">
                                            <i class="ph-bold ph-trash text-base"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="flex-1 relative z-10">
                            <h3 class="text-xl font-black text-white mb-1 group-hover:text-[#56bbf1] transition-colors line-clamp-2">{{ $ekskul->name }}</h3>
                            <div class="flex items-center gap-2 text-xs font-bold text-slate-400 mb-6 uppercase tracking-wide">
                                <i class="ph-bold ph-user-circle text-[#56bbf1] text-lg"></i>
                                <span class="truncate">{{ $ekskul->coach_name ?? 'Belum ada pembina' }}</span>
                            </div>

                            <div class="space-y-3">
                                {{-- Jadwal Row --}}
                                <div class="flex items-center justify-between p-3 rounded-xl bg-[#021124]/80 border border-white/10">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">Jadwal</span>
                                    <span class="text-xs font-bold text-slate-200 flex items-center gap-1.5 text-right">
                                        <div class="w-2 h-2 rounded-full bg-emerald-400 shrink-0"></div>
                                        <span class="truncate max-w-[120px]">{{ $ekskul->schedule ?? '-' }}</span>
                                    </span>
                                </div>
                                
                                {{-- Anggota Row --}}
                                <div class="flex items-center justify-between p-3 rounded-xl bg-[#021124]/80 border border-white/10">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase">Partisipan</span>
                                    <div class="flex items-center -space-x-2">
                                        <div class="w-6 h-6 rounded-full border-2 border-[#021124] bg-sky-500/40"></div>
                                        <div class="w-6 h-6 rounded-full border-2 border-[#021124] bg-indigo-500/40"></div>
                                        <div class="w-6 h-6 rounded-full border-2 border-[#021124] bg-white/10 flex items-center justify-center text-[8px] font-bold text-white">
                                            +{{ $ekskul->members_count }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Footer Link --}}
                        <div class="mt-6 pt-4 relative z-10">
                            <a href="{{ route('extracurriculars.members', ['ekskul_id' => $ekskul->id]) }}" class="w-full py-3 rounded-xl bg-[#56bbf1]/10 border border-[#56bbf1]/20 text-[#56bbf1] font-bold text-sm flex items-center justify-center gap-2 group-hover:bg-gradient-to-r group-hover:from-[#0d52a1] group-hover:to-[#56bbf1] group-hover:text-white transition-all shadow-sm group-hover:shadow-lg group-hover:shadow-sky-950/50">
                                <span>Kelola Anggota</span>
                                <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center bg-gradient-to-br from-[#031d3d]/90 via-[#021124]/95 to-[#031d3d]/90 backdrop-blur-xl rounded-[2.5rem] border border-white/10 shadow-2xl">
                        <div class="w-24 h-24 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner border border-white/10">
                            <i class="ph-duotone ph-puzzle-piece text-5xl text-slate-400"></i>
                        </div>
                        <h3 class="text-xl font-black text-white mb-2">Belum ada Ekstrakurikuler</h3>
                        <p class="text-slate-400 text-sm max-w-xs mx-auto">Tambahkan kegiatan baru untuk memulai manajemen bakat siswa.</p>
                        <button @click="addModalOpen = true" class="mt-6 px-6 py-2.5 bg-gradient-to-r from-[#0d52a1] to-[#56bbf1] text-white rounded-xl font-bold text-sm hover:brightness-110 transition shadow-lg shadow-sky-950/50">
                            + Tambah Data
                        </button>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- MODAL TAMBAH --}}
        <div x-show="addModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity" @click="addModalOpen = false"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="bg-[#031d3d] rounded-[2rem] shadow-2xl w-full max-w-lg overflow-hidden transform transition-all border border-white/15 text-white">
                    <div class="bg-gradient-to-r from-[#0d52a1] to-[#56bbf1] p-6 flex justify-between items-center">
                        <h3 class="text-lg font-black text-white flex items-center gap-2">
                            <i class="ph-bold ph-plus-circle text-white"></i> Tambah Ekskul Baru
                        </h3>
                        <button @click="addModalOpen = false" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-colors"><i class="ph-bold ph-x"></i></button>
                    </div>
                    <form action="{{ route('extracurriculars.store') }}" method="POST" enctype="multipart/form-data" class="p-6 md:p-8 space-y-5">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Nama Ekskul <span class="text-rose-400">*</span></label>
                            <input type="text" name="name" required placeholder="Contoh: Basket Putra" class="w-full rounded-2xl border-white/15 bg-[#021124]/90 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm py-3 px-4 font-bold text-white transition-all placeholder:text-slate-500">
                        </div>
                        <div class="grid grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Nama Pembina</label>
                                <input type="text" name="coach_name" placeholder="Bpk/Ibu..." class="w-full rounded-2xl border-white/15 bg-[#021124]/90 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm py-3 px-4 font-bold text-white transition-all placeholder:text-slate-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Jadwal Latihan</label>
                                <input type="text" name="schedule" placeholder="Senin, 15:00" class="w-full rounded-2xl border-white/15 bg-[#021124]/90 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm py-3 px-4 font-bold text-white transition-all placeholder:text-slate-500">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Ikon / Logo</label>
                            <div x-data="{ type: 'upload' }" class="p-4 bg-[#021124]/80 rounded-2xl border border-white/10">
                                <div class="flex gap-4 mb-4">
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <div class="w-4 h-4 rounded-full border-2 border-white/20 flex items-center justify-center group-hover:border-[#56bbf1] transition-colors">
                                            <div class="w-2 h-2 rounded-full bg-[#56bbf1] opacity-0" :class="{'opacity-100': type === 'upload'}"></div>
                                        </div>
                                        <input type="radio" x-model="type" value="upload" class="hidden">
                                        <span class="text-xs font-bold text-slate-300 group-hover:text-[#56bbf1] transition-colors">Upload Gambar</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer group">
                                        <div class="w-4 h-4 rounded-full border-2 border-white/20 flex items-center justify-center group-hover:border-[#56bbf1] transition-colors">
                                            <div class="w-2 h-2 rounded-full bg-[#56bbf1] opacity-0" :class="{'opacity-100': type === 'icon'}"></div>
                                        </div>
                                        <input type="radio" x-model="type" value="icon" class="hidden">
                                        <span class="text-xs font-bold text-slate-300 group-hover:text-[#56bbf1] transition-colors">Phosphor Icon</span>
                                    </label>
                                </div>
                                <div x-show="type === 'upload'">
                                    <input type="file" name="image_file" accept="image/*" class="block w-full text-xs text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#56bbf1]/10 file:text-[#56bbf1] hover:file:bg-[#56bbf1]/20 transition-colors cursor-pointer"/>
                                </div>
                                <div x-show="type === 'icon'" style="display: none;">
                                    <div class="relative">
                                        <i class="ph-bold ph-code absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" name="icon_text" placeholder="Contoh: ph-fill ph-basketball" class="w-full rounded-xl border-white/15 pl-9 text-sm py-2.5 bg-[#021124] focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] font-mono text-white placeholder:text-slate-500">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pt-2 flex gap-3">
                            <button type="button" @click="addModalOpen = false" class="flex-1 py-3.5 rounded-xl bg-white/5 border border-white/10 text-slate-300 font-bold text-sm hover:bg-white/10 hover:text-white transition-colors">Batal</button>
                            <button type="submit" class="flex-1 py-3.5 rounded-xl bg-gradient-to-r from-[#0d52a1] via-sky-600 to-[#56bbf1] text-white text-sm font-bold hover:brightness-110 shadow-lg shadow-sky-950/50 transition-all transform active:scale-95">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

         {{-- MODAL EDIT --}}
        <div x-show="editModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity" @click="editModalOpen = false"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div x-show="editModalOpen" x-transition.scale.95 class="bg-[#031d3d] rounded-[2rem] shadow-2xl w-full max-w-lg overflow-hidden border border-white/15 text-white">
                    <div class="bg-gradient-to-r from-[#0d52a1] to-[#56bbf1] p-6 flex justify-between items-center">
                        <h3 class="text-lg font-black text-white flex items-center gap-2">
                            <i class="ph-bold ph-pencil-simple text-white"></i> Edit Ekstrakurikuler
                        </h3>
                        <button @click="editModalOpen = false" class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-white hover:bg-white/20 transition-colors focus:outline-none"><i class="ph-bold ph-x"></i></button>
                    </div>
                   <form id="editForm" method="POST" enctype="multipart/form-data" class="p-6 md:p-8 space-y-5">
                        @csrf @method('PUT')
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Nama Ekskul</label>
                            <input type="text" name="name" id="edit_name" required class="w-full rounded-2xl border-white/15 bg-[#021124]/90 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm py-3 px-4 font-bold text-white transition-all">
                        </div>
                        <div class="grid grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Nama Pembina</label>
                                <input type="text" name="coach_name" id="edit_coach" class="w-full rounded-2xl border-white/15 bg-[#021124]/90 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm py-3 px-4 font-bold text-white transition-all">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Jadwal</label>
                                <input type="text" name="schedule" id="edit_schedule" class="w-full rounded-2xl border-white/15 bg-[#021124]/90 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm py-3 px-4 font-bold text-white transition-all">
                            </div>
                        </div>
                        <div>
                             <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Update Tampilan</label>
                            <div class="p-4 bg-[#021124]/80 rounded-2xl border border-white/10">
                                <div class="mb-4">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2">Ganti Gambar (Opsional)</label>
                                    <input type="file" name="image_file" accept="image/*" class="block w-full text-xs text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#56bbf1]/10 file:text-[#56bbf1] hover:file:bg-[#56bbf1]/20 transition-colors cursor-pointer border border-dashed border-white/15 bg-[#021124]"/>
                                </div>
                                <div class="border-t border-white/10 my-4 relative">
                                    <span class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 bg-[#021124] px-2 text-[10px] font-bold text-slate-400">ATAU</span>
                                </div>
                                <div>
                                   <label class="block text-[10px] font-bold text-slate-400 uppercase mb-2">Ganti Kode Ikon</label>
                                    <div class="relative">
                                        <i class="ph-bold ph-code absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" name="icon_text" id="edit_icon_text" placeholder="Contoh: ph-fill ph-trophy" class="w-full rounded-xl border-white/15 pl-9 text-sm py-2.5 bg-[#021124] focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] font-mono text-white shadow-sm placeholder:text-slate-500">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="pt-2 flex gap-3">
                            <button type="button" @click="editModalOpen = false" class="flex-1 py-3.5 rounded-xl bg-white/5 border border-white/10 text-slate-300 font-bold text-sm hover:bg-white/10 hover:text-white transition-colors">Batal</button>
                            <button type="submit" class="flex-1 py-3.5 rounded-xl bg-gradient-to-r from-[#0d52a1] via-sky-600 to-[#56bbf1] text-white text-sm font-bold hover:brightness-110 shadow-lg shadow-sky-950/50 transition-all transform active:scale-95">Update Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script>
        function setupEditForm(ekskul) {
            if(!ekskul) return; 

            document.getElementById('edit_name').value = ekskul.name;
            document.getElementById('edit_coach').value = ekskul.coach_name;
            document.getElementById('edit_schedule').value = ekskul.schedule;
            
            if (ekskul.icon && !ekskul.icon.startsWith('storage/')) {
                document.getElementById('edit_icon_text').value = ekskul.icon;
            } else {
                document.getElementById('edit_icon_text').value = '';
            }

            let url = "{{ route('extracurriculars.update', 0) }}";
            let form = document.getElementById('editForm');
            form.action = url.replace('/0', '/' + ekskul.id);
        }

        // Handle Delete with SweetAlert
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const form = this.closest('.delete-form');
                    Swal.fire({
                        title: 'Hapus Ekskul?',
                        text: "Data ekskul beserta riwayat absensinya akan dihapus permanen.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e11d48',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        background: '#031d3d',
                        color: '#ffffff',
                        customClass: {
                            popup: 'rounded-[2.5rem] font-sans border border-white/10 shadow-2xl',
                            confirmButton: 'bg-rose-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-2 shadow-lg shadow-rose-950/50',
                            cancelButton: 'bg-white/10 text-slate-300 px-6 py-3 rounded-xl font-bold hover:bg-white/20 transition-colors mx-2'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>