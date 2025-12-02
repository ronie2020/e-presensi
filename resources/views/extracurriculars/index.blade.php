<x-app-layout>
    {{-- Load SweetAlert --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-6 sm:py-8" x-data="{ addModalOpen: false, editModalOpen: false, editData: {} }">
        
        {{-- Header --}}
        <div class="mb-8 px-4 sm:px-0 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight leading-tight flex items-center gap-3">
                    <i class="ph-duotone ph-basketball text-orange-600"></i> Manajemen Ekstrakurikuler
                </h1>
                <p class="text-slate-500 mt-2 text-lg">
                    Kelola data kegiatan, pembina, dan jadwal latihan.
                </p>
            </div>
            <button @click="addModalOpen = true" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-orange-600 text-white font-bold text-sm shadow-lg shadow-orange-500/30 hover:bg-orange-700 transition-all hover:-translate-y-0.5">
                <i class="ph-bold ph-plus"></i> Tambah Ekskul
            </button>
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
                        position: 'top-end'
                    });
                });
            </script>
        @endif

        {{-- Grid Card Layout --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 px-4 sm:px-0">
            @forelse($extracurriculars as $ekskul)
                <div class="bg-white rounded-3xl border border-slate-200 p-6 hover:shadow-xl hover:shadow-orange-500/5 hover:border-orange-200 transition-all duration-300 group flex flex-col h-full relative overflow-hidden">
                    
                    {{-- Dekorasi Background --}}
                    <div class="absolute top-0 right-0 p-6 opacity-5 group-hover:opacity-10 transition-opacity">
                        <i class="ph-fill ph-trophy text-8xl text-orange-500"></i>
                    </div>

                    {{-- Header Card --}}
                    <div class="flex items-start justify-between mb-4 relative z-10">
                        <div class="w-14 h-14 rounded-2xl bg-orange-50 border border-orange-100 flex items-center justify-center text-orange-600 text-2xl shadow-sm">
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
                            <button @click="open = !open" @click.away="open = false" class="p-2 rounded-lg text-slate-400 hover:bg-slate-50 hover:text-slate-600 transition-colors">
                                <i class="ph-bold ph-dots-three-vertical text-xl"></i>
                            </button>
                            <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-slate-100 z-20 py-1" style="display: none;">
                                <button @click="
                                    editModalOpen = true; 
                                    editData = {{ json_encode($ekskul) }};
                                    open = false;
                                    setTimeout(() => setupEditForm(editData), 50);
                                " class="w-full text-left px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 flex items-center gap-2">
                                    <i class="ph-bold ph-pencil-simple text-orange-500"></i> Edit Data
                                </button>
                                
                                {{-- Form Hapus dengan SweetAlert --}}
                                <form action="{{ route('extracurriculars.destroy', $ekskul->id) }}" method="POST" class="delete-form">
                                    @csrf @method('DELETE')
                                    <button type="button" class="btn-delete w-full text-left px-4 py-2 text-sm text-rose-600 hover:bg-rose-50 flex items-center gap-2">
                                        <i class="ph-bold ph-trash"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 relative z-10">
                        <h3 class="text-xl font-bold text-slate-800 mb-1">{{ $ekskul->name }}</h3>
                        <div class="flex items-center gap-2 text-sm text-slate-500 mb-4">
                            <i class="ph-duotone ph-user-circle text-orange-400"></i>
                            <span>{{ $ekskul->coach_name ?? 'Belum ada pembina' }}</span>
                        </div>

                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                                <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Jadwal</span>
                                <span class="text-xs font-bold text-slate-700 flex items-center gap-1">
                                    <i class="ph-fill ph-clock text-slate-400"></i> {{ $ekskul->schedule ?? '-' }}
                                </span>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-3 border border-slate-100">
                                <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider mb-1">Anggota</span>
                                <span class="text-xs font-bold text-slate-700 flex items-center gap-1">
                                    <i class="ph-fill ph-users text-slate-400"></i> {{ $ekskul->members_count }} Siswa
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Footer Link --}}
                    <div class="mt-auto border-t border-slate-100 pt-4 relative z-10">
                        <a href="{{ route('extracurriculars.members', ['ekskul_id' => $ekskul->id]) }}" class="flex items-center justify-center gap-2 text-sm font-bold text-orange-600 hover:text-orange-700 transition-colors">
                            Kelola Anggota <i class="ph-bold ph-arrow-right"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200">
                    <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm text-slate-300">
                        <i class="ph-duotone ph-puzzle-piece text-4xl"></i>
                    </div>
                    <h3 class="text-slate-600 font-bold text-lg">Belum ada Ekstrakurikuler</h3>
                    <p class="text-slate-400 text-sm mt-1">Tambahkan kegiatan baru untuk memulai.</p>
                </div>
            @endforelse
        </div>

        {{-- MODAL TAMBAH (Sama seperti sebelumnya) --}}
        <div x-show="addModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="addModalOpen = false"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden transform transition-all">
                    <div class="bg-orange-600 p-4 px-6 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-white">Tambah Ekskul Baru</h3>
                        <button @click="addModalOpen = false" class="text-orange-200 hover:text-white"><i class="ph-bold ph-x text-xl"></i></button>
                    </div>
                    <form action="{{ route('extracurriculars.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Nama Ekskul</label>
                            <input type="text" name="name" required class="w-full rounded-xl border-slate-300 focus:border-orange-500 focus:ring-orange-500 text-sm py-2.5">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Nama Pembina</label>
                                <input type="text" name="coach_name" class="w-full rounded-xl border-slate-300 focus:border-orange-500 focus:ring-orange-500 text-sm py-2.5">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Jadwal</label>
                                <input type="text" name="schedule" placeholder="Senin, 15:00" class="w-full rounded-xl border-slate-300 focus:border-orange-500 focus:ring-orange-500 text-sm py-2.5">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Ikon / Logo</label>
                            <div x-data="{ type: 'upload' }" class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                                <div class="flex gap-4 mb-4">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" x-model="type" value="upload" class="text-orange-600 focus:ring-orange-500">
                                        <span class="text-sm font-medium text-slate-600">Upload Gambar</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" x-model="type" value="icon" class="text-orange-600 focus:ring-orange-500">
                                        <span class="text-sm font-medium text-slate-600">Kode Phosphor Icon</span>
                                    </label>
                                </div>
                                <div x-show="type === 'upload'">
                                    <input type="file" name="image_file" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100"/>
                                </div>
                                <div x-show="type === 'icon'" style="display: none;">
                                    <input type="text" name="icon_text" placeholder="Contoh: ph-fill ph-basketball" class="w-full rounded-lg border-slate-300 text-sm py-2 bg-white">
                                </div>
                            </div>
                        </div>
                        <div class="pt-4 flex justify-end gap-3">
                            <button type="button" @click="addModalOpen = false" class="px-4 py-2 rounded-lg text-sm font-bold text-slate-600 hover:bg-slate-100">Batal</button>
                            <button type="submit" class="px-6 py-2 rounded-lg bg-orange-600 text-white text-sm font-bold hover:bg-orange-700 shadow-lg shadow-orange-500/20">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL EDIT (Sama seperti sebelumnya) --}}
        <div x-show="editModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="editModalOpen = false"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden transform transition-all">
                    <div class="bg-orange-600 p-4 px-6 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-white">Edit Ekstrakurikuler</h3>
                        <button @click="editModalOpen = false" class="text-orange-200 hover:text-white"><i class="ph-bold ph-x text-xl"></i></button>
                    </div>
                    <form id="editForm" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                        @csrf @method('PUT')
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1">Nama Ekskul</label>
                            <input type="text" name="name" id="edit_name" required class="w-full rounded-xl border-slate-300 focus:border-orange-500 focus:ring-orange-500 text-sm py-2.5">
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Nama Pembina</label>
                                <input type="text" name="coach_name" id="edit_coach" class="w-full rounded-xl border-slate-300 focus:border-orange-500 focus:ring-orange-500 text-sm py-2.5">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-1">Jadwal</label>
                                <input type="text" name="schedule" id="edit_schedule" class="w-full rounded-xl border-slate-300 focus:border-orange-500 focus:ring-orange-500 text-sm py-2.5">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Update Tampilan</label>
                            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                                <div class="mb-3">
                                    <label class="block text-xs font-medium text-slate-500 mb-1">Ganti Gambar (Opsional)</label>
                                    <input type="file" name="image_file" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100"/>
                                </div>
                                <div class="border-t border-slate-200 my-2"></div>
                                <div>
                                    <label class="block text-xs font-medium text-slate-500 mb-1">Atau Ganti Kode Ikon</label>
                                    <input type="text" name="icon_text" id="edit_icon_text" class="w-full rounded-lg border-slate-300 text-sm py-2 bg-white">
                                </div>
                            </div>
                        </div>
                        <div class="pt-4 flex justify-end gap-3">
                            <button type="button" @click="editModalOpen = false" class="px-4 py-2 rounded-lg text-sm font-bold text-slate-600 hover:bg-slate-100">Batal</button>
                            <button type="submit" class="px-6 py-2 rounded-lg bg-orange-600 text-white text-sm font-bold hover:bg-orange-700 shadow-lg shadow-orange-500/20">Update Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    <script>
        function setupEditForm(ekskul) {
            document.getElementById('edit_name').value = ekskul.name;
            document.getElementById('edit_coach').value = ekskul.coach_name;
            document.getElementById('edit_schedule').value = ekskul.schedule;
            
            if (ekskul.icon && !ekskul.icon.startsWith('storage/')) {
                document.getElementById('edit_icon_text').value = ekskul.icon;
            } else {
                document.getElementById('edit_icon_text').value = '';
            }

            // Replace URL ID dummy '0'
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
                        confirmButtonColor: '#e11d48', // Rose-600
                        cancelButtonColor: '#64748b', // Slate-500
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        borderRadius: '1rem'
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