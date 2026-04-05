<x-app-layout>
    {{-- Tambahkan CDN SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans text-slate-800" 
         x-data="{ 
            // 1. TAB PERSISTENCE: Mengambil tab terakhir dari SessionStorage, default ke 'pendidikan'
            activeTab: sessionStorage.getItem('portfolioActiveTab') || 'pendidikan',
            
            editModalOpen: false,
            editType: '', 
            editData: {},
            editFormAction: '',
            
            init() {
                // Pantau perubahan activeTab, lalu simpan ke SessionStorage
                this.$watch('activeTab', value => {
                    sessionStorage.setItem('portfolioActiveTab', value);
                });
            },

            openEditModal(type, item, url) {
                this.editType = type;
                this.editData = JSON.parse(JSON.stringify(item)); 
                
                if (type === 'art' && this.editData.published_at) {
                    this.editData.published_at = this.editData.published_at.substring(0, 10);
                }
                
                this.editFormAction = url;
                this.editModalOpen = true;
                document.body.style.overflow = 'hidden';
            },
            
            closeEditModal() {
                this.editModalOpen = false;
                document.body.style.overflow = 'auto';
                setTimeout(() => { 
                    this.editData = {}; 
                    this.editType = ''; 
                    this.editFormAction = '';
                }, 300);
            },

            confirmDelete(event, message) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444', 
                    cancelButtonColor: '#94a3b8', 
                    confirmButtonText: '<i class=\'ph-bold ph-trash\'></i> Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true, 
                    customClass: {
                        popup: 'rounded-3xl',
                        confirmButton: 'px-5 py-2.5 rounded-xl font-bold flex items-center gap-2',
                        cancelButton: 'px-5 py-2.5 rounded-xl font-bold'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        event.target.submit(); 
                    }
                })
            }
         }">
         
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
           {{-- Header --}}
            <div class="animate-enter relative rounded-[2.5rem] bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 p-6 md:p-10 text-white shadow-xl shadow-blue-900/30 overflow-hidden group border border-white/10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                
                {{-- Background Texture (Religious Style) --}}
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>

                <div class="relative z-10 flex items-center gap-5">
                    <div class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/20 shadow-inner shrink-0">
                        <i class="ph-duotone ph-chart-polar text-4xl text-blue-300"></i>
                    </div>
                    <div>
                        {{-- PERBAIKAN: Menampilkan Multi Role dan Jabatan secara dinamis di Header Admin Portofolio --}}
                        @php
                            $managedUser = $targetUser ?? auth()->user();
                            
                            $userRoles = is_string($managedUser->role) ? json_decode($managedUser->role, true) : $managedUser->role;
                            if (!is_array($userRoles)) {
                                $userRoles = is_string($managedUser->role) ? explode(',', $managedUser->role) : [$managedUser->role];
                            }
                            $userRoles = array_filter(array_map('trim', $userRoles));
                        @endphp

                        <div class="flex flex-wrap gap-2 mb-2">
                            @foreach($userRoles as $role)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 border border-white/10 text-blue-200 text-[10px] font-bold uppercase tracking-wider backdrop-blur-sm shadow-sm">
                                    <i class="ph-bold ph-check-circle"></i> {{ $role }}
                                </span>
                            @endforeach
                            
                            @if(!empty($managedUser->position) && $managedUser->position !== '-')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-emerald-200 text-[10px] font-bold uppercase tracking-wider backdrop-blur-sm shadow-sm">
                                    <i class="ph-bold ph-briefcase"></i> {{ $managedUser->position }}
                                </span>
                            @endif
                        </div>

                        <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight leading-tight text-white">
                            <i class="ph-duotone ph-medal text-blue-300"></i> 
                            Kelola Portofolio {{ isset($targetUser) && $targetUser->id !== auth()->id() ? '- ' . $targetUser->name : '' }}
                        </h2>
                        <p class="text-blue-100/80 text-sm mt-1">Tambahkan karya, materi, dan pengalaman untuk ditampilkan di direktori publik.</p>
                    </div>
                </div>
                <div class="relative z-10 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 w-full md:w-auto">
                    <a href="{{ route('teachers.show', request('user_id') ?? auth()->id()) }}" target="_blank" class="w-full md:w-auto justify-center px-5 py-3 bg-slate-900/50 backdrop-blur-sm border border-white/10 text-white font-bold rounded-2xl hover:bg-white/10 transition-colors flex items-center gap-2 shadow-sm">
                        <i class="ph-bold ph-eye"></i> Lihat Profil Publik
                    </a>
                </div>
            </div>

            {{-- Toast Success --}}
            @if(session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.mixin({
                            toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true,
                            customClass: { popup: 'rounded-2xl border border-emerald-100 shadow-lg' },
                            didOpen: (toast) => { toast.addEventListener('mouseenter', Swal.stopTimer); toast.addEventListener('mouseleave', Swal.resumeTimer); }
                        }).fire({ icon: 'success', title: '{{ session('success') }}' });
                    });
                </script>
            @endif

            {{-- Pop-up Error Validasi --}}
            @if($errors->any())
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error', title: 'Gagal Menyimpan!',
                            html: `
                                <div class="text-left text-sm mt-2">
                                    <p class="text-slate-600 mb-2">Mohon perbaiki kesalahan berikut:</p>
                                    <ul class="list-disc pl-5 space-y-1">
                                        @foreach($errors->all() as $error)
                                            <li class="text-rose-500 font-medium">{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            `,
                            confirmButtonColor: '#3b82f6', confirmButtonText: 'Tutup & Perbaiki', customClass: { popup: 'rounded-3xl' }
                        });
                    });
                </script>
            @endif

            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden flex flex-col md:flex-row min-h-[600px] relative mt-8">
                
                {{-- SIDEBAR TABS --}}
                <div class="md:w-64 bg-slate-50 border-r border-slate-100 p-6 shrink-0 z-10">
                    <nav class="flex md:flex-col gap-2 overflow-x-auto custom-scrollbar pb-2 md:pb-0 hide-scroll-mobile">
                        <button @click="activeTab = 'pendidikan'" :class="activeTab === 'pendidikan' ? 'bg-cyan-600 text-white shadow-lg shadow-cyan-600/20' : 'text-slate-500 hover:bg-slate-200 hover:text-slate-700'" class="w-full text-left px-4 py-3.5 rounded-2xl font-bold text-sm transition-all flex items-center gap-3 whitespace-nowrap">
                            <i class="ph-bold ph-graduation-cap text-lg"></i> Pendidikan
                        </button>
                        <button @click="activeTab = 'pengalaman'" :class="activeTab === 'pengalaman' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-500 hover:bg-slate-200 hover:text-slate-700'" class="w-full text-left px-4 py-3.5 rounded-2xl font-bold text-sm transition-all flex items-center gap-3 whitespace-nowrap">
                            <i class="ph-bold ph-student text-lg"></i> Pengalaman
                        </button>
                        <button @click="activeTab = 'materi'" :class="activeTab === 'materi' ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/20' : 'text-slate-500 hover:bg-slate-200 hover:text-slate-700'" class="w-full text-left px-4 py-3.5 rounded-2xl font-bold text-sm transition-all flex items-center gap-3 whitespace-nowrap">
                            <i class="ph-bold ph-presentation-chart text-lg"></i> Materi & Media
                        </button>
                        <button @click="activeTab = 'portofolio'" :class="activeTab === 'portofolio' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : 'text-slate-500 hover:bg-slate-200 hover:text-slate-700'" class="w-full text-left px-4 py-3.5 rounded-2xl font-bold text-sm transition-all flex items-center gap-3 whitespace-nowrap">
                            <i class="ph-bold ph-trophy text-lg"></i> Prestasi / Galeri
                        </button>
                        <button @click="activeTab = 'artikel'" :class="activeTab === 'artikel' ? 'bg-orange-600 text-white shadow-lg shadow-orange-600/20' : 'text-slate-500 hover:bg-slate-200 hover:text-slate-700'" class="w-full text-left px-4 py-3.5 rounded-2xl font-bold text-sm transition-all flex items-center gap-3 whitespace-nowrap">
                            <i class="ph-bold ph-article text-lg"></i> Artikel Tulisan
                        </button>
                    </nav>
                </div>

                {{-- KONTEN UTAMA --}}
                <div class="p-6 md:p-8 flex-1 overflow-hidden">

                    {{-- 1. TAB PENGALAMAN --}}
                    <div x-show="activeTab === 'pengalaman'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="flex items-center gap-3 mb-6 pb-2 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center"><i class="ph-bold ph-student"></i></div>
                            <h3 class="text-lg font-black text-slate-800">Riwayat Pelatihan & Sertifikasi</h3>
                        </div>
                        
                    <form action="{{ route('portfolio.exp.store') }}" method="POST" enctype="multipart/form-data" x-data="{ isSubmitting: false }" @submit="isSubmitting = true" class="bg-slate-50/50 p-6 rounded-3xl border border-slate-100 mb-8 grid grid-cols-1 md:grid-cols-4 gap-4">
                        @csrf
                        @if(request('user_id')) <input type="hidden" name="user_id" value="{{ request('user_id') }}"> @endif
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tahun</label>
                            <input type="number" name="year" value="{{ old('year') }}" placeholder="2023" class="w-full rounded-2xl border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-500 font-bold text-slate-700" required>
                        </div>
                        <div class="md:col-span-3">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Nama Pelatihan / Sertifikasi</label>
                            <input type="text" name="title" value="{{ old('title') }}" placeholder="Cth: Diklat Guru Penggerak..." class="w-full rounded-2xl border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-500 font-bold text-slate-700" required>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Penyelenggara</label>
                            <input type="text" name="organizer" value="{{ old('organizer') }}" placeholder="Cth: Kemdikbud..." class="w-full rounded-2xl border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-500 font-bold text-slate-700">
                        </div>
                        
                        {{-- UPLOAD SERTIFIKAT --}}
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Upload Sertifikat (PDF/Gambar)</label>
                            <input type="file" name="certificate" accept=".pdf,image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:font-bold file:bg-blue-50 file:text-blue-700 bg-white rounded-2xl border border-slate-200">
                        </div>

                        <div class="md:col-span-4 flex justify-end">
                            <button type="submit" :disabled="isSubmitting" :class="{'opacity-70 cursor-wait': isSubmitting}" class="w-full md:w-auto px-8 py-3 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 shadow-lg shadow-blue-500/20 transition-all flex justify-center items-center gap-2">
                                <span x-show="!isSubmitting"><i class="ph-bold ph-plus"></i> Tambah</span>
                                <span x-show="isSubmitting" x-cloak><i class="ph-bold ph-spinner animate-spin"></i> Loading...</span>
                            </button>
                        </div>
                    </form>

                        <div class="space-y-3">
                            @forelse($experiences ?? [] as $exp)
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 border border-slate-200 rounded-2xl hover:bg-blue-50/50 transition-colors group gap-3 sm:gap-0">
                                    <div class="flex items-start gap-4 pr-4">
                                        <div class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-black mt-1">{{ $exp->year }}</div>
                                        <div>
                                            <h4 class="font-bold text-slate-800">{{ $exp->title }}</h4>
                                            <p class="text-sm text-slate-500 mt-0.5">{{ $exp->organizer }}</p>
                                            
                                            {{-- TOMBOL LIHAT SERTIFIKAT --}}
                                            @if($exp->certificate_path)
                                                <a href="{{ asset('storage/' . $exp->certificate_path) }}" target="_blank" class="inline-flex items-center gap-1 mt-2 text-[10px] uppercase tracking-wider font-black text-blue-600 bg-blue-50 px-2 py-1 rounded-lg hover:bg-blue-100 transition-colors">
                                                    <i class="ph-bold ph-certificate"></i> Lihat Sertifikat
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity justify-end border-t border-slate-100 sm:border-0 pt-3 sm:pt-0">
                                        <button type="button" @click="openEditModal('exp', @js($exp), '{{ route('portfolio.exp.update', ['id' => $exp->id, 'user_id' => request('user_id')]) }}')" class="w-9 h-9 sm:w-8 sm:h-8 flex items-center justify-center text-amber-500 bg-amber-50 sm:text-slate-400 sm:bg-transparent sm:hover:text-amber-500 sm:hover:bg-amber-50 rounded-xl transition-all" title="Edit">
                                            <i class="ph-bold ph-pencil-simple text-lg sm:text-base"></i>
                                        </button>
                                        <form action="{{ route('portfolio.exp.destroy', ['id' => $exp->id, 'user_id' => request('user_id')]) }}" method="POST" @submit.prevent="confirmDelete($event, 'Menghapus riwayat pelatihan tidak dapat dibatalkan.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-9 h-9 sm:w-8 sm:h-8 flex items-center justify-center text-rose-500 bg-rose-50 sm:text-slate-400 sm:bg-transparent sm:hover:text-rose-500 sm:hover:bg-rose-50 rounded-xl transition-all" title="Hapus">
                                                <i class="ph-bold ph-trash text-lg sm:text-base"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-10 bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                                    <i class="ph-duotone ph-folder-open text-4xl text-slate-300 mb-2"></i>
                                    <p class="text-slate-500 text-sm font-medium">Belum ada data pengalaman ditambahkan.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- 2. TAB MATERI & MEDIA --}}
                    <div x-show="activeTab === 'materi'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="flex items-center gap-3 mb-6 pb-2 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center"><i class="ph-bold ph-presentation-chart"></i></div>
                            <h3 class="text-lg font-black text-slate-800">Materi & Media Pembelajaran</h3>
                        </div>
                        
                        <form action="{{ route('portfolio.mat.store') }}" method="POST" enctype="multipart/form-data" x-data="{ isSubmitting: false, fileName: '' }" @submit="isSubmitting = true" class="bg-slate-50/50 p-6 rounded-3xl border border-slate-100 mb-8 grid grid-cols-1 md:grid-cols-2 gap-5">
                            @csrf
                            @if(request('user_id')) <input type="hidden" name="user_id" value="{{ request('user_id') }}"> @endif
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Judul Materi</label>
                                <input type="text" name="title" value="{{ old('title') }}" class="w-full rounded-2xl border-slate-200 bg-white focus:border-purple-500 focus:ring-purple-500 font-bold text-slate-700" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tipe (Misal: Modul PDF, Slide PPT)</label>
                                <input type="text" name="type" value="{{ old('type') }}" class="w-full rounded-2xl border-slate-200 bg-white focus:border-purple-500 focus:ring-purple-500 font-bold text-slate-700">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Link URL (Jika File di GDrive/Youtube)</label>
                                <input type="url" name="file_url" value="{{ old('file_url') }}" placeholder="https://..." class="w-full rounded-2xl border-slate-200 bg-white focus:border-purple-500 focus:ring-purple-500 font-bold text-slate-700">
                            </div>
                            <div class="md:col-span-2 p-4 bg-white border-2 border-dashed border-slate-200 rounded-2xl">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Atau Upload File Langsung (Opsional)</label>
                                <div class="flex items-center gap-3">
                                    <input type="file" name="file" @change="fileName = $event.target.files[0].name" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                                </div>
                            </div>
                            <div class="md:col-span-2 flex justify-end">
                                <button type="submit" :disabled="isSubmitting" :class="{'opacity-70 cursor-wait': isSubmitting}" class="w-full md:w-auto px-8 py-3 bg-purple-600 text-white font-bold rounded-2xl hover:bg-purple-700 shadow-lg shadow-purple-500/20 transition-all flex justify-center items-center gap-2">
                                    <span x-show="!isSubmitting"><i class="ph-bold ph-upload-simple"></i> Simpan Materi</span>
                                    <span x-show="isSubmitting" x-cloak><i class="ph-bold ph-spinner animate-spin"></i> Mengunggah...</span>
                                </button>
                            </div>
                        </form>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @forelse($materials ?? [] as $mat)
                                <div class="flex flex-col sm:flex-row items-start gap-4 p-5 border border-slate-200 rounded-3xl bg-white shadow-sm hover:shadow-md transition-shadow relative group">
                                    <div class="flex gap-4 w-full">
                                        <div class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center shrink-0">
                                            <i class="{{ $mat->icon ?? 'ph-file-text text-slate-500' }} text-3xl"></i>
                                        </div>
                                        <div class="flex-1 sm:pr-14">
                                            <h4 class="font-bold text-sm text-slate-800 line-clamp-2 leading-tight">{{ $mat->title }}</h4>
                                            <p class="text-xs font-medium text-slate-500 mt-1 mb-2">{{ $mat->type }}</p>
                                            @if($mat->file_url) 
                                                <a href="{{ $mat->file_url }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] uppercase tracking-wider font-black text-purple-600 bg-purple-50 px-2 py-1 rounded-lg hover:bg-purple-100"><i class="ph-bold ph-link"></i> Buka Link</a>
                                            @elseif($mat->file_path)
                                                <a href="{{ asset('storage/'.$mat->file_path) }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] uppercase tracking-wider font-black text-blue-600 bg-blue-50 px-2 py-1 rounded-lg hover:bg-blue-100"><i class="ph-bold ph-download-simple"></i> Download</a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex flex-row sm:flex-col gap-2 w-full sm:w-auto justify-end sm:absolute sm:top-4 sm:right-4 border-t sm:border-t-0 border-slate-100 pt-3 sm:pt-0 mt-2 sm:mt-0 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                        <button type="button" @click="openEditModal('mat', @js($mat), '{{ route('portfolio.mat.update', ['id' => $mat->id, 'user_id' => request('user_id')]) }}')" class="flex-1 sm:flex-none h-9 sm:w-8 sm:h-8 flex items-center justify-center rounded-xl bg-amber-50 text-amber-500 sm:bg-transparent sm:text-slate-400 sm:hover:bg-amber-50 sm:hover:text-amber-500 transition-colors"><i class="ph-bold ph-pencil-simple"></i></button>
                                        <form action="{{ route('portfolio.mat.destroy', ['id' => $mat->id, 'user_id' => request('user_id')]) }}" method="POST" @submit.prevent="confirmDelete($event, 'File materi yang dihapus tidak dapat dikembalikan.')" class="flex-1 sm:flex-none">
                                            @csrf @method('DELETE')
                                            <button class="w-full h-9 sm:w-8 sm:h-8 flex items-center justify-center rounded-xl bg-rose-50 text-rose-500 sm:bg-transparent sm:text-slate-400 sm:hover:bg-rose-50 sm:hover:text-rose-500 transition-colors"><i class="ph-bold ph-trash"></i></button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="sm:col-span-2 text-center py-10 bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                                    <p class="text-slate-500 text-sm font-medium">Belum ada materi/media dibagikan.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- 3. TAB PRESTASI / GALERI (DENGAN CLIENT-SIDE VALIDATION SIZE) --}}
                    <div x-show="activeTab === 'portofolio'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="flex items-center gap-3 mb-6 pb-2 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center"><i class="ph-bold ph-trophy"></i></div>
                            <h3 class="text-lg font-black text-slate-800">Galeri Portofolio & Pencapaian</h3>
                        </div>
                        
                        <form action="{{ route('portfolio.port.store') }}" method="POST" enctype="multipart/form-data" 
                              x-data="{ isSubmitting: false, imagePreviews: [] }" 
                              @submit.prevent="
                                  let fileInput = $el.querySelector('input[type=file]');
                                  let files = fileInput.files;
                                  let overSize = false;
                                  let totalSize = 0;
                                  for(let i=0; i<files.length; i++) {
                                      if(files[i].size > 2048 * 1024) overSize = true; // max 2MB/file
                                      totalSize += files[i].size;
                                  }
                                  
                                  if(overSize) {
                                      Swal.fire({ icon: 'warning', title: 'Foto Terlalu Besar', text: 'Ukuran maksimal 1 foto adalah 2MB. Silakan kompres foto Anda terlebih dahulu.', confirmButtonColor: '#10b981', customClass: { popup: 'rounded-3xl' } });
                                      return;
                                  }
                                  if(totalSize > 8388608) { // 8MB
                                      Swal.fire({ icon: 'warning', title: 'Kapasitas Penuh', text: 'Total keseluruhan foto melebihi kapasitas memori server (8MB). Silakan kurangi jumlah foto yang diupload bersamaan.', confirmButtonColor: '#10b981', customClass: { popup: 'rounded-3xl' } });
                                      return;
                                  }
                                  
                                  isSubmitting = true; 
                                  $el.submit();
                              " 
                              class="bg-slate-50/50 p-6 rounded-3xl border border-slate-100 mb-8">
                            @csrf
                            @if(request('user_id')) <input type="hidden" name="user_id" value="{{ request('user_id') }}"> @endif
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 items-end">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Judul Kegiatan / Prestasi</label>
                                    <input type="text" name="title" value="{{ old('title') }}" class="w-full rounded-2xl border-slate-200 bg-white focus:border-emerald-500 focus:ring-emerald-500 font-bold text-slate-700" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tahun</label>
                                    <input type="text" name="year" value="{{ old('year') }}" class="w-full rounded-2xl border-slate-200 bg-white focus:border-emerald-500 focus:ring-emerald-500 font-bold text-slate-700">
                                </div>
                                <div class="md:col-span-3 p-4 bg-white border-2 border-dashed border-slate-200 rounded-2xl flex flex-col md:flex-row gap-4 items-center justify-between">
                                    <div class="flex-1 w-full">
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Upload Foto Dokumentasi (Bisa Pilih Lebih Dari Satu)</label>
                                        <input type="file" name="images[]" accept="image/*" multiple @change="imagePreviews = Array.from($event.target.files).map(file => URL.createObjectURL(file))" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:font-bold file:bg-emerald-50 file:text-emerald-700" required>
                                    </div>
                                    
                                    {{-- Area Preview Multiple --}}
                                    <template x-if="imagePreviews.length > 0">
                                        <div class="flex gap-2 overflow-x-auto max-w-[200px] custom-scrollbar">
                                            <template x-for="img in imagePreviews">
                                                <div class="w-16 h-16 rounded-xl overflow-hidden border border-slate-200 shrink-0 shadow-sm">
                                                    <img :src="img" class="w-full h-full object-cover">
                                                </div>
                                            </template>
                                        </div>
                                    </template>

                                    <button type="submit" :disabled="isSubmitting" :class="{'opacity-70 cursor-wait': isSubmitting}" class="px-8 py-3 bg-emerald-600 text-white font-bold rounded-2xl hover:bg-emerald-700 shadow-lg shadow-emerald-500/20 transition-all shrink-0 w-full md:w-auto flex justify-center items-center gap-2">
                                        <span x-show="!isSubmitting"><i class="ph-bold ph-upload-simple"></i> Simpan</span>
                                        <span x-show="isSubmitting" x-cloak><i class="ph-bold ph-spinner animate-spin"></i> Uploading...</span>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @forelse($portfolios ?? [] as $port)
                                @php
                                    $images = json_decode($port->image_path, true);
                                    if (!is_array($images)) {
                                        $images = $port->image_path ? [$port->image_path] : [];
                                    }
                                    $port->images_array = $images;
                                @endphp
                                <div class="relative group rounded-3xl overflow-hidden border border-slate-200 shadow-sm">
                                    <div class="aspect-square bg-slate-100 relative">
                                        @if(count($images) > 0)
                                            <img src="{{ asset('storage/' . $images[0]) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                            @if(count($images) > 1)
                                                <div class="absolute top-3 left-3 bg-black/50 backdrop-blur-md text-white text-[10px] font-black px-2.5 py-1 rounded-lg flex items-center gap-1 shadow-sm">
                                                    <i class="ph-bold ph-images"></i> +{{ count($images) - 1 }} Foto
                                                </div>
                                            @endif
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                                <i class="ph-duotone ph-image text-4xl"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/40 to-black/20 md:to-transparent opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity flex flex-col justify-between p-4">
                                        <div class="flex justify-end gap-2">
                                            <button type="button" @click="openEditModal('port', @js($port), '{{ route('portfolio.port.update', ['id' => $port->id, 'user_id' => request('user_id')]) }}')" class="w-8 h-8 bg-white/30 backdrop-blur text-white rounded-xl hover:bg-amber-500 transition-colors flex items-center justify-center"><i class="ph-bold ph-pencil-simple"></i></button>
                                            <form action="{{ route('portfolio.port.destroy', ['id' => $port->id, 'user_id' => request('user_id')]) }}" method="POST" @submit.prevent="confirmDelete($event, 'Foto galeri yang dihapus tidak dapat dikembalikan.')">
                                                @csrf @method('DELETE')
                                                <button class="w-8 h-8 bg-white/30 backdrop-blur text-white rounded-xl hover:bg-rose-500 transition-colors flex items-center justify-center"><i class="ph-bold ph-trash"></i></button>
                                            </form>
                                        </div>
                                        <div>
                                            <span class="inline-block px-2 py-1 bg-emerald-500 text-white text-[10px] font-black rounded-lg mb-1">{{ $port->year }}</span>
                                            <h4 class="text-white font-bold text-sm leading-tight line-clamp-2">{{ $port->title }}</h4>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-2 md:col-span-3 text-center py-10 bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                                    <p class="text-slate-500 text-sm font-medium">Belum ada foto galeri.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- 4. TAB ARTIKEL --}}
                    <div x-show="activeTab === 'artikel'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="flex items-center gap-3 mb-6 pb-2 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center"><i class="ph-bold ph-article"></i></div>
                            <h3 class="text-lg font-black text-slate-800">Artikel & Tulisan Terpublikasi</h3>
                        </div>
                        
                        <form action="{{ route('portfolio.art.store') }}" method="POST" enctype="multipart/form-data" x-data="{ isSubmitting: false, imagePreview: null }" @submit="isSubmitting = true" class="bg-slate-50/50 p-6 rounded-3xl border border-slate-100 mb-8 grid grid-cols-1 md:grid-cols-2 gap-5">
                            @csrf
                            @if(request('user_id')) <input type="hidden" name="user_id" value="{{ request('user_id') }}"> @endif
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Judul Artikel / Opini</label>
                                <input type="text" name="title" value="{{ old('title') }}" class="w-full rounded-2xl border-slate-200 bg-white focus:border-orange-500 focus:ring-orange-500 font-bold text-slate-700" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Kategori Topik</label>
                                <input type="text" name="category" value="{{ old('category') }}" placeholder="Pendidikan, Opini..." class="w-full rounded-2xl border-slate-200 bg-white focus:border-orange-500 focus:ring-orange-500 font-bold text-slate-700">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tanggal Publikasi</label>
                                <input type="date" name="published_at" value="{{ old('published_at') }}" class="w-full rounded-2xl border-slate-200 bg-white focus:border-orange-500 focus:ring-orange-500 font-bold text-slate-700 text-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Ringkasan / Excerpt</label>
                                <textarea name="excerpt" rows="2" class="w-full rounded-2xl border-slate-200 bg-white focus:border-orange-500 focus:ring-orange-500 font-medium text-slate-700">{{ old('excerpt') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Link URL Artikel Asli (Opsional)</label>
                                <input type="url" name="url" value="{{ old('url') }}" placeholder="https://..." class="w-full rounded-2xl border-slate-200 bg-white focus:border-orange-500 focus:ring-orange-500 font-bold text-slate-700">
                            </div>
                            
                            <div class="flex items-center gap-4">
                                <div class="flex-1">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Thumbnail Cover (Opsional)</label>
                                    <input type="file" name="image" accept="image/*" @change="imagePreview = URL.createObjectURL($event.target.files[0])" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:font-bold file:bg-orange-50 file:text-orange-600 bg-white rounded-2xl border border-slate-200 overflow-hidden">
                                </div>
                                <template x-if="imagePreview">
                                    <img :src="imagePreview" class="w-12 h-12 rounded-xl object-cover border border-slate-200 mt-5 shadow-sm">
                                </template>
                            </div>

                            <div class="md:col-span-2 flex justify-end mt-2">
                                <button type="submit" :disabled="isSubmitting" :class="{'opacity-70 cursor-wait': isSubmitting}" class="w-full md:w-auto px-8 py-3 bg-orange-500 text-white font-bold rounded-2xl hover:bg-orange-600 shadow-lg shadow-orange-500/20 transition-all flex justify-center items-center gap-2">
                                    <span x-show="!isSubmitting"><i class="ph-bold ph-floppy-disk"></i> Simpan Artikel</span>
                                    <span x-show="isSubmitting" x-cloak><i class="ph-bold ph-spinner animate-spin"></i> Menyimpan...</span>
                                </button>
                            </div>
                        </form>

                        <div class="space-y-4">
                            @forelse($articles ?? [] as $art)
                                <div class="flex flex-col sm:flex-row items-stretch gap-4 p-4 border border-slate-200 rounded-3xl bg-white relative group hover:shadow-md transition-all">
                                    <div class="flex gap-4">
                                        @if($art->image_path)
                                            <div class="w-24 h-24 rounded-2xl overflow-hidden shrink-0 bg-slate-100">
                                                <img src="{{ asset('storage/' . $art->image_path) }}" class="w-full h-full object-cover">
                                            </div>
                                        @endif
                                        <div class="flex-1 py-1 sm:pr-16">
                                            <span class="inline-block px-2 py-1 bg-orange-100 text-orange-700 text-[10px] font-black rounded-lg uppercase tracking-wider mb-1">{{ $art->category ?? 'Umum' }}</span>
                                            <h4 class="font-bold text-slate-800 text-base leading-tight">{{ $art->title }}</h4>
                                            <p class="text-xs font-medium text-slate-500 mt-1 line-clamp-2">{{ $art->excerpt }}</p>
                                            @if($art->url) 
                                                <a href="{{ $art->url }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] uppercase tracking-wider font-black text-blue-600 hover:text-blue-800 mt-2"><i class="ph-bold ph-link"></i> Baca di Web Asli</a> 
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex flex-row sm:flex-col gap-2 justify-end sm:absolute sm:top-4 sm:right-4 border-t sm:border-t-0 border-slate-100 pt-3 sm:pt-0 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity">
                                        <button type="button" @click="openEditModal('art', @js($art), '{{ route('portfolio.art.update', ['id' => $art->id, 'user_id' => request('user_id')]) }}')" class="flex-1 sm:flex-none h-9 sm:w-8 sm:h-8 flex items-center justify-center rounded-xl bg-amber-50 text-amber-500 sm:bg-transparent sm:text-slate-400 sm:hover:bg-amber-50 sm:hover:text-amber-500 transition-colors"><i class="ph-bold ph-pencil-simple"></i></button>
                                        <form action="{{ route('portfolio.art.destroy', ['id' => $art->id, 'user_id' => request('user_id')]) }}" method="POST" @submit.prevent="confirmDelete($event, 'Menghapus data artikel tidak dapat dibatalkan.')" class="flex-1 sm:flex-none">
                                            @csrf @method('DELETE')
                                            <button class="w-full h-9 sm:w-8 sm:h-8 flex items-center justify-center rounded-xl bg-rose-50 text-rose-500 sm:bg-transparent sm:text-slate-400 sm:hover:bg-rose-50 sm:hover:text-rose-500 transition-colors"><i class="ph-bold ph-trash"></i></button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-10 bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                                    <p class="text-slate-500 text-sm font-medium">Belum ada tulisan artikel.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- 5. TAB PENDIDIKAN --}}
                    <div x-show="activeTab === 'pendidikan'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="flex items-center gap-3 mb-6 pb-2 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-lg bg-cyan-100 text-cyan-600 flex items-center justify-center"><i class="ph-bold ph-graduation-cap"></i></div>
                            <h3 class="text-lg font-black text-slate-800">Riwayat Pendidikan Formal</h3>
                        </div>
                        
                        <form action="{{ route('portfolio.edu.store') }}" method="POST" x-data="{ isSubmitting: false }" @submit="isSubmitting = true" class="bg-slate-50/50 p-6 rounded-3xl border border-slate-100 mb-8 grid grid-cols-1 md:grid-cols-4 gap-4">
                            @csrf
                            @if(request('user_id')) <input type="hidden" name="user_id" value="{{ request('user_id') }}"> @endif
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Nama Institusi / Universitas</label>
                                <input type="text" name="institution" value="{{ old('institution') }}" placeholder="Cth: Universitas Pendidikan..." class="w-full rounded-2xl border-slate-200 bg-white focus:border-cyan-500 focus:ring-cyan-500 font-bold text-slate-700" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Gelar / Jurusan</label>
                                <input type="text" name="degree" value="{{ old('degree') }}" placeholder="Cth: S1 Pendidikan Matematika" class="w-full rounded-2xl border-slate-200 bg-white focus:border-cyan-500 focus:ring-cyan-500 font-bold text-slate-700">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tahun Masuk</label>
                                <input type="number" name="start_year" value="{{ old('start_year') }}" placeholder="Cth: 2010" class="w-full rounded-2xl border-slate-200 bg-white focus:border-cyan-500 focus:ring-cyan-500 font-bold text-slate-700">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tahun Lulus</label>
                                <input type="number" name="end_year" value="{{ old('end_year') }}" placeholder="Cth: 2014" class="w-full rounded-2xl border-slate-200 bg-white focus:border-cyan-500 focus:ring-cyan-500 font-bold text-slate-700">
                            </div>
                            <div class="md:col-span-2 flex items-end justify-end">
                                <button type="submit" :disabled="isSubmitting" :class="{'opacity-70 cursor-wait': isSubmitting}" class="w-full md:w-auto px-8 py-3 bg-cyan-600 text-white font-bold rounded-2xl hover:bg-cyan-700 shadow-lg shadow-cyan-500/20 transition-all flex justify-center items-center gap-2">
                                    <span x-show="!isSubmitting"><i class="ph-bold ph-plus"></i> Tambah Riwayat</span>
                                    <span x-show="isSubmitting" x-cloak><i class="ph-bold ph-spinner animate-spin"></i> Menyimpan...</span>
                                </button>
                            </div>
                        </form>

                        <div class="space-y-3">
                            @forelse($educations ?? [] as $edu)
                                <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 border border-slate-200 rounded-2xl hover:bg-cyan-50/50 transition-colors group gap-3 sm:gap-0">
                                    <div class="flex items-start gap-4 pr-4">
                                        <div class="px-3 py-1 bg-cyan-100 text-cyan-700 rounded-lg text-xs font-black mt-1">{{ $edu->start_year ?? '-' }} - {{ $edu->end_year ?? 'Skrg' }}</div>
                                        <div>
                                            <h4 class="font-bold text-slate-800">{{ $edu->institution }}</h4>
                                            <p class="text-sm text-slate-500 mt-0.5">{{ $edu->degree }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 opacity-100 md:opacity-0 md:group-hover:opacity-100 transition-opacity justify-end border-t border-slate-100 sm:border-0 pt-3 sm:pt-0">
                                        <button type="button" @click="openEditModal('edu', @js($edu), '{{ route('portfolio.edu.update', ['id' => $edu->id, 'user_id' => request('user_id')]) }}')" class="w-9 h-9 sm:w-8 sm:h-8 flex items-center justify-center text-amber-500 bg-amber-50 sm:text-slate-400 sm:bg-transparent sm:hover:text-amber-500 sm:hover:bg-amber-50 rounded-xl transition-all"><i class="ph-bold ph-pencil-simple text-lg sm:text-base"></i></button>
                                        <form action="{{ route('portfolio.edu.destroy', ['id' => $edu->id, 'user_id' => request('user_id')]) }}" method="POST" @submit.prevent="confirmDelete($event, 'Apakah Anda ingin menghapus riwayat pendidikan ini?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="w-9 h-9 sm:w-8 sm:h-8 flex items-center justify-center text-rose-500 bg-rose-50 sm:text-slate-400 sm:bg-transparent sm:hover:text-rose-500 sm:hover:bg-rose-50 rounded-xl transition-all"><i class="ph-bold ph-trash text-lg sm:text-base"></i></button>
                                        </form>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-10 bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                                    <p class="text-slate-500 text-sm font-medium">Belum ada riwayat pendidikan ditambahkan.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
            
            {{-- ========================================== --}}
            {{-- MODAL EDIT DINAMIS (GLOBAL UNTUK SEMUA TAB) --}}
            {{-- ========================================== --}}
            <div x-show="editModalOpen" x-cloak class="fixed inset-0 z-[60] flex items-center justify-center p-4 sm:p-6">
                <!-- Backdrop -->
                <div x-show="editModalOpen" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="closeEditModal()"></div>

                <!-- Modal Panel -->
                <div x-show="editModalOpen" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative bg-white rounded-[2rem] shadow-2xl w-full max-w-2xl border border-slate-100 flex flex-col max-h-[90vh] overflow-hidden" 
                     @click.away="closeEditModal()">
                     
                    <!-- Modal Header -->
                    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50 shrink-0">
                        <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                            <i class="ph-bold ph-pencil-simple text-blue-600"></i> Edit Data
                        </h3>
                        <button @click="closeEditModal()" type="button" class="text-slate-400 hover:text-slate-600 bg-white hover:bg-slate-100 p-2 rounded-full transition-colors border border-slate-200">
                            <i class="ph-bold ph-x"></i>
                        </button>
                    </div>

                     <!-- Modal Body / Form -->
                    <form :action="editFormAction" method="POST" enctype="multipart/form-data" class="p-6 overflow-y-auto custom-scrollbar flex-1" 
                          x-data="{ isModalSubmitting: false, editImagePreview: null, editImagePreviews: [] }" 
                          @submit.prevent="
                              let fileInputs = $el.querySelectorAll('input[type=file]');
                              let overSize = false;
                              let totalSize = 0;
                              fileInputs.forEach(input => {
                                  let files = input.files;
                                  for(let i=0; i<files.length; i++) {
                                      if(files[i].size > 2048 * 1024) overSize = true; // max 2MB
                                      totalSize += files[i].size;
                                  }
                              });
                              if(overSize) {
                                  Swal.fire({ icon: 'warning', title: 'File Terlalu Besar', text: 'Ukuran maksimal per file adalah 2MB.', confirmButtonColor: '#3b82f6', customClass: { popup: 'rounded-3xl' } });
                                  return;
                              }
                              if(totalSize > 8388608) { // 8MB
                                  Swal.fire({ icon: 'warning', title: 'Kapasitas Penuh', text: 'Total file melampaui batas server (8MB).', confirmButtonColor: '#3b82f6', customClass: { popup: 'rounded-3xl' } });
                                  return;
                              }
                              isModalSubmitting = true; 
                              $el.submit();
                          ">
                        @csrf
                        @method('PUT')
                        @if(request('user_id')) <input type="hidden" name="user_id" value="{{ request('user_id') }}"> @endif

                        <!-- Form Edit: Pengalaman (exp) -->
                        <template x-if="editType === 'exp'">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Tahun</label>
                                    <input type="number" name="year" x-model="editData.year" class="w-full rounded-2xl border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-500 font-bold text-slate-700" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Nama Pelatihan / Sertifikasi</label>
                                    <input type="text" name="title" x-model="editData.title" class="w-full rounded-2xl border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-500 font-bold text-slate-700" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Penyelenggara</label>
                                    <input type="text" name="organizer" x-model="editData.organizer" class="w-full rounded-2xl border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-500 font-bold text-slate-700">
                                </div>
                                
                                {{-- TAMBAHAN: Upload Sertifikat di Modal Edit --}}
                                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Ganti Sertifikat (Opsional)</label>
                                    <p class="text-xs text-slate-500 mb-2">Biarkan kosong jika tidak ingin mengubah sertifikat.</p>
                                    <input type="file" name="certificate" accept=".pdf,image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-100 file:text-blue-700">
                                    
                                    <template x-if="editData.certificate_path">
                                        <a :href="'{{ asset('storage') }}/' + editData.certificate_path" target="_blank" class="inline-flex items-center gap-1 mt-2 text-xs font-bold text-blue-600 hover:text-blue-800">
                                            <i class="ph-bold ph-link"></i> Lihat Sertifikat Saat Ini
                                        </a>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <!-- Form Edit: Materi (mat) -->
                        <template x-if="editType === 'mat'">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Judul Materi</label>
                                    <input type="text" name="title" x-model="editData.title" class="w-full rounded-2xl border-slate-200 bg-white focus:border-purple-500 focus:ring-purple-500 font-bold text-slate-700" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Tipe</label>
                                    <input type="text" name="type" x-model="editData.type" class="w-full rounded-2xl border-slate-200 bg-white focus:border-purple-500 focus:ring-purple-500 font-bold text-slate-700">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Link URL</label>
                                    <input type="url" name="file_url" x-model="editData.file_url" class="w-full rounded-2xl border-slate-200 bg-white focus:border-purple-500 focus:ring-purple-500 font-bold text-slate-700">
                                </div>
                                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Ganti File (Opsional)</label>
                                    <p class="text-xs text-slate-500 mb-2">Biarkan kosong jika tidak ingin mengubah file.</p>
                                    <input type="file" name="file" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-purple-100 file:text-purple-700">
                                </div>
                            </div>
                        </template>

                       <!-- Form Edit: Portofolio/Galeri (port) -->
                        <template x-if="editType === 'port'">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Judul Kegiatan</label>
                                    <input type="text" name="title" x-model="editData.title" class="w-full rounded-2xl border-slate-200 bg-white focus:border-emerald-500 focus:ring-emerald-500 font-bold text-slate-700" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Tahun</label>
                                    <input type="text" name="year" x-model="editData.year" class="w-full rounded-2xl border-slate-200 bg-white focus:border-emerald-500 focus:ring-emerald-500 font-bold text-slate-700">
                                </div>
                                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl flex flex-col gap-3">
                                    <div class="flex-1">
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Ganti Foto (Opsional, Bisa Lebih Dari Satu)</label>
                                        <p class="text-xs text-slate-500 mb-2">Pilih beberapa foto sekaligus. Biarkan kosong jika tidak ingin mengubah foto lama.</p>
                                        <input type="file" name="images[]" multiple accept="image/*" @change="editImagePreviews = Array.from($event.target.files).map(file => URL.createObjectURL(file))" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-emerald-100 file:text-emerald-700">
                                    </div>
                                    
                                    <!-- Preview Foto Baru -->
                                    <template x-if="editImagePreviews.length > 0">
                                        <div class="flex gap-2 overflow-x-auto custom-scrollbar pb-2">
                                            <template x-for="img in editImagePreviews">
                                                <img :src="img" class="w-16 h-16 rounded-lg object-cover border border-slate-200 shrink-0">
                                            </template>
                                        </div>
                                    </template>

                                    <!-- Preview Foto Lama -->
                                    <template x-if="editImagePreviews.length === 0 && editData.images_array && editData.images_array.length > 0">
                                        <div class="flex gap-2 overflow-x-auto custom-scrollbar pb-2">
                                            <template x-for="img in editData.images_array">
                                                <img :src="'{{ asset('storage') }}/' + img" class="w-16 h-16 rounded-lg object-cover border border-slate-200 shrink-0">
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                        
                        <!-- Form Edit: Artikel (art) -->
                        <template x-if="editType === 'art'">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Judul Artikel</label>
                                    <input type="text" name="title" x-model="editData.title" class="w-full rounded-2xl border-slate-200 bg-white focus:border-orange-500 focus:ring-orange-500 font-bold text-slate-700" required>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Kategori</label>
                                        <input type="text" name="category" x-model="editData.category" class="w-full rounded-2xl border-slate-200 bg-white focus:border-orange-500 focus:ring-orange-500 font-bold text-slate-700">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Tanggal Publikasi</label>
                                        <input type="date" name="published_at" x-model="editData.published_at" class="w-full rounded-2xl border-slate-200 bg-white focus:border-orange-500 focus:ring-orange-500 font-bold text-slate-700 text-sm">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Ringkasan</label>
                                    <textarea name="excerpt" x-model="editData.excerpt" rows="2" class="w-full rounded-2xl border-slate-200 bg-white focus:border-orange-500 focus:ring-orange-500 font-medium text-slate-700"></textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Link URL Artikel</label>
                                    <input type="url" name="url" x-model="editData.url" class="w-full rounded-2xl border-slate-200 bg-white focus:border-orange-500 focus:ring-orange-500 font-bold text-slate-700">
                                </div>
                                <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl flex items-center gap-4">
                                    <div class="flex-1">
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Ganti Thumbnail (Opsional)</label>
                                        <p class="text-xs text-slate-500 mb-2">Biarkan kosong jika tidak ingin mengubah thumbnail.</p>
                                        <input type="file" name="image" accept="image/*" @change="editImagePreview = URL.createObjectURL($event.target.files[0])" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-orange-100 file:text-orange-700">
                                    </div>
                                    <template x-if="editImagePreview">
                                        <img :src="editImagePreview" class="w-16 h-16 rounded-lg object-cover border border-slate-200">
                                    </template>
                                </div>
                            </div>
                        </template>

                        <!-- Form Edit: Pendidikan (edu) -->
                        <template x-if="editType === 'edu'">
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Nama Institusi</label>
                                    <input type="text" name="institution" x-model="editData.institution" class="w-full rounded-2xl border-slate-200 bg-white focus:border-cyan-500 focus:ring-cyan-500 font-bold text-slate-700" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Gelar / Jurusan</label>
                                    <input type="text" name="degree" x-model="editData.degree" class="w-full rounded-2xl border-slate-200 bg-white focus:border-cyan-500 focus:ring-cyan-500 font-bold text-slate-700">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Tahun Masuk</label>
                                        <input type="number" name="start_year" x-model="editData.start_year" class="w-full rounded-2xl border-slate-200 bg-white focus:border-cyan-500 focus:ring-cyan-500 font-bold text-slate-700">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Tahun Lulus</label>
                                        <input type="number" name="end_year" x-model="editData.end_year" class="w-full rounded-2xl border-slate-200 bg-white focus:border-cyan-500 focus:ring-cyan-500 font-bold text-slate-700">
                                    </div>
                                </div>
                            </div>
                        </template>

                        <!-- Submit Button -->
                        <div class="mt-8 pt-4 border-t border-slate-100 flex justify-end gap-3 shrink-0">
                            <button type="button" @click="closeEditModal()" class="px-6 py-2.5 rounded-xl font-bold text-slate-500 hover:bg-slate-100 transition-colors">Batal</button>
                            <button type="submit" :disabled="isModalSubmitting" :class="{'opacity-70 cursor-wait': isModalSubmitting}" class="px-6 py-2.5 rounded-xl font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-500/20 transition-all flex items-center gap-2">
                                <span x-show="!isModalSubmitting"><i class="ph-bold ph-check"></i> Simpan Perubahan</span>
                                <span x-show="isModalSubmitting" x-cloak><i class="ph-bold ph-spinner animate-spin"></i> Menyimpan...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>