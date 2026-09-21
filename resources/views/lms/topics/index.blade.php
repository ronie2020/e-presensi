<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Kelola Pokok Bahasan (Bab)') }}
        </h2>
    </x-slot>

    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>

    <div class="py-8 sm:py-10 font-sans min-h-screen text-slate-100 bg-[#020b18] relative overflow-hidden pb-20">
        {{-- Efek Latar Belakang --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-gradient-to-b from-sky-600/10 via-blue-600/5 to-transparent pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- HERO SECTION --}}
            <x-hero-section
                badge="Kurikulum & Silabus"
                badgeIcon="ph-list-dashes"
                title="Pokok Bahasan"
                titleHighlight="Bab & Topik"
                description="Buat dan kelola bab materi pembelajaran untuk setiap mata pelajaran sebagai alur urutan belajar siswa (Learning Player)."
                :chips="[
                    ['icon' => 'ph-books', 'label' => 'Struktur Kurikulum'],
                    ['icon' => 'ph-play-circle', 'label' => 'Learning Player Ready'],
                    ['icon' => 'ph-tree-structure', 'label' => 'Modul Terstruktur']
                ]"
                heroIcon="ph-list-dashes"
                :showcaseNumber="isset($topics) ? ($topics->total() ?? count($topics)) : 0"
                showcaseLabel="Total Bab"
                showcaseSubtitle="Silabus Pembelajaran"
                statusOrb="Sistem Aktif"
                statusColor="sky"
            >
                <x-slot:cta>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('lms.materials.index') }}" class="px-5 py-2.5 bg-gradient-to-r from-sky-400 to-[#0d52a1] hover:from-sky-300 hover:to-sky-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-sky-500/25 hover:shadow-sky-500/40 hover:scale-[1.02] transition-all flex items-center gap-2 border border-white/20 active:scale-95">
                            <i class="ph-bold ph-book-open-text text-base"></i> Kelola Materi
                        </a>
                        <a href="{{ route('lms.assignments.index') }}" class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-slate-200 hover:text-white font-bold text-xs rounded-xl border border-white/15 backdrop-blur-md hover:scale-[1.02] transition-all flex items-center gap-1.5">
                            <i class="ph-bold ph-pencil-simple text-base text-sky-400"></i> Kelola Tugas
                        </a>
                        <a href="{{ route('dashboard') }}" class="px-4 py-2.5 bg-white/10 hover:bg-white/20 text-slate-200 hover:text-white font-bold text-xs rounded-xl border border-white/15 backdrop-blur-md hover:scale-[1.02] transition-all flex items-center gap-1.5">
                            <i class="ph-bold ph-arrow-left text-sm text-sky-400"></i> Dashboard
                        </a>
                    </div>
                </x-slot:cta>
            </x-hero-section>

            {{-- ERROR & SUCCESS MESSAGE --}}
            @if ($errors->any())
                <div class="mb-6 bg-rose-500/10 border border-rose-500/20 p-5 rounded-[1.5rem] flex items-start gap-4 shadow-sm animate-enter backdrop-blur-md">
                    <div class="p-2 bg-rose-500/20 text-rose-400 rounded-xl shrink-0"><i class="ph-bold ph-warning text-xl"></i></div>
                    <div>
                        <ul class="list-disc list-inside text-sm text-rose-300 font-bold mt-1 space-y-0.5">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- KIRI: FORM TAMBAH BAB --}}
                <div class="lg:col-span-1 animate-enter" style="animation-delay: 100ms">
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] p-6 shadow-2xl border border-white/10 backdrop-blur-xl">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-sky-500/10 text-sky-400 border border-sky-500/20 flex items-center justify-center text-xl shadow-sm"><i class="ph-bold ph-plus"></i></div>
                            <h3 class="font-black text-lg text-white">Tambah Bab Baru</h3>
                        </div>

                        <form id="createForm" action="{{ route('lms.topics.store') }}" method="POST" class="space-y-5">
                            @csrf
                            <div>
                                <label for="subject_id_create" class="block text-[10px] font-bold text-sky-400 uppercase tracking-widest mb-2 ml-1">Mapel <span class="text-rose-400">*</span></label>
                                <div class="relative group">
                                    <select id="subject_id_create" name="subject_id" required class="w-full rounded-2xl border border-white/10 bg-slate-900/80 font-bold text-white focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 h-12 px-4 appearance-none text-sm cursor-pointer shadow-sm transition-all duration-300 [color-scheme:dark]">
                                        <option value="" class="bg-slate-900 text-white">-- Pilih Mapel --</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }} class="bg-slate-900 text-white">{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500 group-hover:text-sky-400 transition-colors"><i class="ph-bold ph-caret-down"></i></div>
                                </div>
                            </div>
                            
                            <div>
                                <label for="title_create" class="block text-[10px] font-bold text-sky-400 uppercase tracking-widest mb-2 ml-1">Judul Bab <span class="text-rose-400">*</span></label>
                                <input id="title_create" type="text" name="title" value="{{ old('title') }}" required class="w-full rounded-2xl border border-white/10 bg-slate-900/80 font-bold text-white text-sm focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 h-12 px-4 shadow-sm transition-all duration-300 placeholder-slate-500" placeholder="Cth: Bab 1: Sistem Tata Surya">
                            </div>

                            <div>
                                <label for="order_number_create" class="block text-[10px] font-bold text-sky-400 uppercase tracking-widest mb-2 ml-1">Urutan Ke- <span class="text-rose-400">*</span></label>
                                <input id="order_number_create" type="number" name="order_number" value="{{ old('order_number', 1) }}" min="1" required class="w-full rounded-2xl border border-white/10 bg-slate-900/80 font-bold text-white text-sm focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 h-12 px-4 shadow-sm transition-all duration-300">
                            </div>

                            <div>
                                <label for="description_create" class="block text-[10px] font-bold text-sky-400 uppercase tracking-widest mb-2 ml-1">Deskripsi Singkat (Opsional)</label>
                                <textarea id="description_create" name="description" rows="3" class="w-full rounded-2xl border border-white/10 bg-slate-900/80 font-medium text-sm text-white focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 p-4 shadow-sm placeholder:text-slate-500 transition-all duration-300" placeholder="Apa yang dipelajari di bab ini?">{{ old('description') }}</textarea>
                            </div>

                            <button type="submit" id="createBtn" class="w-full py-3.5 bg-gradient-to-r from-sky-500 to-blue-600 text-white font-bold rounded-2xl shadow-lg shadow-sky-500/25 hover:from-sky-400 hover:to-blue-500 transition-all duration-300 flex items-center justify-center gap-2 text-sm active:scale-95 group">
                                <i class="ph-bold ph-floppy-disk text-lg group-hover:scale-110 transition-transform"></i> 
                                <span>Simpan Bab</span>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- KANAN: DAFTAR BAB --}}
                <div class="lg:col-span-2 animate-enter" style="animation-delay: 200ms">
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] p-6 shadow-2xl border border-white/10 min-h-[500px] backdrop-blur-xl">
                        
                        {{-- Filter Area --}}
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 pb-4 border-b border-white/10">
                            <h3 class="font-black text-lg text-white flex items-center gap-2">
                                <div class="p-1.5 bg-sky-500/10 rounded-lg text-sky-400 border border-sky-500/20"><i class="ph-bold ph-list-dashes"></i></div>
                                Daftar Bab Tersimpan
                            </h3>
                            
                            <form action="{{ route('lms.topics.index') }}" method="GET" class="w-full sm:w-auto flex gap-2">
                                <div class="relative group w-full sm:w-56">
                                    <select name="subject_id" onchange="this.form.submit()" class="w-full rounded-xl border border-white/10 bg-slate-900/80 font-bold text-white focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 text-xs h-10 px-3 appearance-none cursor-pointer transition-all [color-scheme:dark]">
                                        <option value="" class="bg-slate-900 text-white">Filter Mapel...</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }} class="bg-slate-900 text-white">{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-500 group-hover:text-sky-400 transition-colors"><i class="ph-bold ph-funnel text-sm"></i></div>
                                </div>
                                @if(request('subject_id'))
                                    <a href="{{ route('lms.topics.index') }}" class="w-10 h-10 bg-rose-500/10 text-rose-400 rounded-xl flex items-center justify-center shrink-0 border border-rose-500/20 hover:bg-rose-500/20 transition-colors shadow-sm" title="Hapus Filter"><i class="ph-bold ph-x"></i></a>
                                @endif
                            </form>
                        </div>

                        {{-- List Table --}}
                        @if($topics->count() > 0)
                            <div class="space-y-4">
                                @php $currentSubject = null; @endphp
                                @foreach($topics as $topic)
                                    
                                    {{-- Grouping Header per Mapel --}}
                                    @if($currentSubject !== $topic->subject_id)
                                        <div class="bg-sky-500/10 border border-sky-500/20 px-4 py-2 rounded-xl mt-6 first:mt-0 flex items-center gap-2">
                                            <div class="w-2 h-2 rounded-full bg-sky-400"></div>
                                            <p class="text-[11px] font-black text-sky-300 uppercase tracking-widest">{{ $topic->subject->name }}</p>
                                        </div>
                                        @php $currentSubject = $topic->subject_id; @endphp
                                    @endif

                                    {{-- Card Item --}}
                                    <div class="flex items-center justify-between p-4 border border-white/10 rounded-2xl hover:border-sky-400/40 transition-all duration-300 group bg-slate-900/60">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-xl bg-slate-900 text-sky-400 border border-sky-500/20 flex items-center justify-center font-black text-sm shrink-0">
                                                {{ $topic->order_number }}
                                            </div>
                                            <div>
                                                <h4 class="font-black text-white text-sm group-hover:text-sky-300 transition-colors">{{ $topic->title }}</h4>
                                                @if($topic->description)
                                                    <p class="text-xs text-slate-400 font-medium truncate max-w-[180px] sm:max-w-xs md:max-w-md mt-0.5">{{ $topic->description }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        {{-- ACTION BUTTONS --}}
                                        <div class="flex items-center gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity duration-300">
                                            <!-- Tombol Edit -->
                                            <a href="{{ route('lms.topics.edit', $topic->id) }}" class="w-8 h-8 bg-sky-500/10 border border-sky-500/20 text-sky-400 rounded-xl hover:bg-sky-500 hover:text-white flex items-center justify-center transition-all shadow-sm" title="Edit Bab">
                                                <i class="ph-bold ph-pencil-simple"></i>
                                            </a>
                                            
                                            <!-- Tombol Hapus dengan SweetAlert2 -->
                                            <form action="{{ route('lms.topics.destroy', $topic->id) }}" method="POST" class="form-delete m-0">
                                                @csrf @method('DELETE')
                                                <button type="button" class="btn-delete w-8 h-8 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-xl hover:bg-rose-500 hover:text-white flex items-center justify-center transition-all shadow-sm" title="Hapus Bab">
                                                    <i class="ph-bold ph-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            
                            {{-- Pagination --}}
                            <div class="mt-8">
                                {{ $topics->links() }}
                            </div>
                        @else
                            {{-- Empty State --}}
                            <div class="flex flex-col items-center justify-center py-16 text-center">
                                <div class="w-24 h-24 bg-slate-900/80 border border-white/10 rounded-full flex items-center justify-center mb-4 shadow-inner">
                                    <i class="ph-duotone ph-books text-5xl text-slate-500"></i>
                                </div>
                                <h4 class="font-black text-lg text-white">Belum Ada Bab Materi</h4>
                                <p class="text-sm text-slate-400 mt-2 max-w-xs mx-auto">Silakan gunakan form di samping kiri untuk mulai menyusun materi pelajaran.</p>
                            </div>
                        @endif

                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Efek loading saat tambah form disubmit
        document.getElementById('createForm').addEventListener('submit', function() {
            const btn = document.getElementById('createBtn');
            btn.innerHTML = '<i class="ph-bold ph-spinner animate-spin text-lg"></i> <span>Menyimpan...</span>';
            btn.classList.add('opacity-80', 'cursor-not-allowed', 'pointer-events-none');
        });

        // Notifikasi Session
        @if(session('success'))
            Swal.fire({
                icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}",
                toast: true, position: 'top-end', showConfirmButton: false, timer: 3000,
                background: '#021124', color: '#fff',
                customClass: { popup: 'rounded-2xl border border-emerald-500/20 bg-[#021124] text-emerald-300 shadow-md' }
            });
        @endif
        @if(session('error'))
            Swal.fire({ 
                icon: 'error', title: 'Gagal!', text: "{{ session('error') }}",
                background: '#021124', color: '#fff',
                customClass: { popup: 'rounded-2xl border border-white/10 bg-[#021124] text-white' }
            });
        @endif

        // Konfirmasi Hapus SweetAlert2
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function(e) {
                const form = this.closest('form');
                
                Swal.fire({
                    title: 'Hapus Bab ini?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    background: '#021124',
                    color: '#fff',
                    showCancelButton: true,
                    confirmButtonColor: '#e11d48',
                    cancelButtonColor: '#334155',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'rounded-[2rem] border border-white/10 bg-[#021124] text-white shadow-2xl',
                        confirmButton: 'bg-rose-600 text-white rounded-xl font-bold px-5 py-2.5 hover:bg-rose-700 transition-colors',
                        cancelButton: 'bg-slate-800 text-slate-300 rounded-xl font-bold px-5 py-2.5 hover:bg-slate-700 transition-colors'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); 
                    }
                });
            });
        });
    </script>
    @endpush
</x-app-layout>