<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-elevate-dark leading-tight">
            {{ __('Kelola Pokok Bahasan (Bab)') }}
        </h2>
    </x-slot>

    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>

    <div class="py-8 sm:py-10 font-sans text-elevate-dark bg-elevate-surface min-h-screen relative overflow-hidden pb-20">
        {{-- Efek Latar Belakang --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- HERO HEADER --}}
            <div class="animate-enter relative rounded-[2rem] bg-gradient-to-r from-elevate-accent via-elevate-peach-light to-elevate-peach p-8 md:p-10 mb-8 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden border border-white/60 group">
                <div class="absolute -top-10 -left-10 w-56 h-56 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/40 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-xl group-hover:rotate-0 transition-transform duration-700"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-black mb-2 tracking-tight">Struktur Materi (Bab)</h1>
                        <p class="text-elevate-dark/80 text-sm font-semibold max-w-xl leading-relaxed">
                            Buat dan kelola Pokok Bahasan/Bab untuk setiap mata pelajaran. Bab ini akan menjadi wadah urutan belajar siswa (Learning Player).
                        </p>
                    </div>
                    <a href="{{ route('lms.materials.index') }}" class="w-full md:w-auto inline-flex justify-center items-center gap-2 px-6 py-3.5 bg-white/60 hover:bg-white rounded-xl text-sm font-bold backdrop-blur-md transition-all duration-300 text-elevate-dark border border-white/60 shadow-sm hover:shadow-md active:scale-95 shrink-0">
                        <i class="ph-bold ph-arrow-left"></i> Kembali ke Materi
                    </a>
                </div>
            </div>

            {{-- ERROR & SUCCESS MESSAGE --}}
            @if ($errors->any())
                <div class="mb-6 bg-[#FDE7E9] border border-[#F4C3C9] p-5 rounded-[1.5rem] flex items-start gap-4 shadow-sm animate-enter">
                    <div class="p-2 bg-white text-[#D13438] rounded-xl shrink-0"><i class="ph-bold ph-warning text-xl"></i></div>
                    <div>
                        <ul class="list-disc list-inside text-sm text-[#D13438] font-bold mt-1">
                            @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- KIRI: FORM TAMBAH BAB --}}
                <div class="lg:col-span-1 animate-enter" style="animation-delay: 100ms">
                    <div class="bg-white rounded-[2.5rem] p-6 shadow-xl shadow-slate-200/40 border border-slate-100">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-xl bg-elevate-soft text-elevate-primary flex items-center justify-center text-xl shadow-sm"><i class="ph-bold ph-plus"></i></div>
                            <h3 class="font-black text-lg text-elevate-dark">Tambah Bab Baru</h3>
                        </div>

                        <form id="createForm" action="{{ route('lms.topics.store') }}" method="POST" class="space-y-5">
                            @csrf
                            <div>
                                <label for="subject_id_create" class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Mapel <span class="text-[#D13438]">*</span></label>
                                <div class="relative group">
                                    <select id="subject_id_create" name="subject_id" required class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-bold text-elevate-dark focus:bg-white focus:border-elevate-accent focus:ring-2 focus:ring-elevate-accent/20 h-12 px-4 appearance-none text-sm cursor-pointer shadow-sm transition-all duration-300">
                                        <option value="">-- Pilih Mapel --</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500 group-hover:text-elevate-primary transition-colors"><i class="ph-bold ph-caret-down"></i></div>
                                </div>
                            </div>
                            
                            <div>
                                <label for="title_create" class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Judul Bab <span class="text-[#D13438]">*</span></label>
                                <input id="title_create" type="text" name="title" value="{{ old('title') }}" required class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-bold text-elevate-dark text-sm focus:bg-white focus:border-elevate-accent focus:ring-2 focus:ring-elevate-accent/20 h-12 px-4 shadow-sm transition-all duration-300" placeholder="Cth: Bab 1: Sistem Tata Surya">
                            </div>

                            <div>
                                <label for="order_number_create" class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Urutan Ke- <span class="text-[#D13438]">*</span></label>
                                <input id="order_number_create" type="number" name="order_number" value="{{ old('order_number', 1) }}" min="1" required class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-bold text-elevate-dark text-sm focus:bg-white focus:border-elevate-accent focus:ring-2 focus:ring-elevate-accent/20 h-12 px-4 shadow-sm transition-all duration-300">
                            </div>

                            <div>
                                <label for="description_create" class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Deskripsi Singkat (Opsional)</label>
                                <textarea id="description_create" name="description" rows="3" class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-medium text-sm focus:bg-white focus:border-elevate-accent focus:ring-2 focus:ring-elevate-accent/20 p-4 shadow-sm placeholder:text-slate-400 transition-all duration-300" placeholder="Apa yang dipelajari di bab ini?">{{ old('description') }}</textarea>
                            </div>

                            <button type="submit" id="createBtn" class="w-full py-3.5 bg-elevate-dark text-white font-bold rounded-2xl shadow-lg shadow-elevate-dark/30 hover:bg-elevate-primary transition-all duration-300 flex items-center justify-center gap-2 text-sm active:scale-95 group">
                                <i class="ph-bold ph-floppy-disk text-lg group-hover:scale-110 transition-transform"></i> 
                                <span>Simpan Bab</span>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- KANAN: DAFTAR BAB --}}
                <div class="lg:col-span-2 animate-enter" style="animation-delay: 200ms">
                    <div class="bg-white rounded-[2.5rem] p-6 shadow-xl shadow-slate-200/40 border border-slate-100 min-h-[500px]">
                        
                        {{-- Filter Area --}}
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 pb-4 border-b border-slate-100">
                            <h3 class="font-black text-lg text-elevate-dark flex items-center gap-2">
                                <div class="p-1.5 bg-elevate-soft rounded-lg text-elevate-primary"><i class="ph-bold ph-list-dashes"></i></div>
                                Daftar Bab Tersimpan
                            </h3>
                            
                            <form action="{{ route('lms.topics.index') }}" method="GET" class="w-full sm:w-auto flex gap-2">
                                <div class="relative group w-full sm:w-56">
                                    <select name="subject_id" onchange="this.form.submit()" class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-elevate-dark hover:bg-slate-100 focus:bg-white focus:ring-2 focus:ring-elevate-accent/20 text-xs h-10 px-3 appearance-none cursor-pointer transition-all">
                                        <option value="">Filter Mapel...</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-500 group-hover:text-elevate-primary transition-colors"><i class="ph-bold ph-funnel text-sm"></i></div>
                                </div>
                                @if(request('subject_id'))
                                    <a href="{{ route('lms.topics.index') }}" class="w-10 h-10 bg-[#FDE7E9] text-[#D13438] rounded-xl flex items-center justify-center shrink-0 border border-[#F4C3C9] hover:bg-[#F4C3C9] transition-colors shadow-sm" title="Hapus Filter"><i class="ph-bold ph-x"></i></a>
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
                                        <div class="bg-elevate-soft/50 px-4 py-2 rounded-xl mt-6 first:mt-0 flex items-center gap-2">
                                            <div class="w-2 h-2 rounded-full bg-elevate-primary"></div>
                                            <p class="text-[11px] font-black text-elevate-primary uppercase tracking-widest">{{ $topic->subject->name }}</p>
                                        </div>
                                        @php $currentSubject = $topic->subject_id; @endphp
                                    @endif

                                    {{-- Card Item --}}
                                    <div class="flex items-center justify-between p-4 border border-slate-100 rounded-2xl hover:border-elevate-accent/50 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 group bg-white">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-xl bg-slate-50 group-hover:bg-elevate-soft group-hover:text-elevate-primary transition-colors border border-slate-200 flex items-center justify-center font-black text-slate-500 shrink-0">
                                                {{ $topic->order_number }}
                                            </div>
                                            <div>
                                                <h4 class="font-black text-elevate-dark text-sm group-hover:text-elevate-primary transition-colors">{{ $topic->title }}</h4>
                                                @if($topic->description)
                                                    <p class="text-xs text-slate-500 font-medium truncate max-w-[180px] sm:max-w-xs md:max-w-md mt-0.5">{{ $topic->description }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        {{-- ACTION BUTTONS --}}
                                        <div class="flex items-center gap-2 opacity-100 sm:opacity-0 sm:group-hover:opacity-100 transition-opacity duration-300">
                                            <!-- Tombol Edit -->
                                            <a href="{{ route('lms.topics.edit', $topic->id) }}" class="w-8 h-8 bg-white border border-[#B8D8FA] text-[#005FB8] rounded-lg hover:bg-[#E6F2FF] hover:scale-105 flex items-center justify-center transition-all shadow-sm" title="Edit Bab">
                                                <i class="ph-bold ph-pencil-simple"></i>
                                            </a>
                                            
                                            <!-- Tombol Hapus dengan SweetAlert2 -->
                                            <form action="{{ route('lms.topics.destroy', $topic->id) }}" method="POST" class="form-delete m-0">
                                                @csrf @method('DELETE')
                                                <button type="button" class="btn-delete w-8 h-8 bg-white border border-[#F4C3C9] text-[#D13438] rounded-lg hover:bg-[#FDE7E9] hover:scale-105 flex items-center justify-center transition-all shadow-sm" title="Hapus Bab">
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
                                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mb-4 border border-slate-100 shadow-inner">
                                    <i class="ph-duotone ph-books text-5xl text-slate-300"></i>
                                </div>
                                <h4 class="font-black text-lg text-elevate-dark">Belum Ada Bab Materi</h4>
                                <p class="text-sm text-slate-500 mt-2 max-w-xs mx-auto">Silakan gunakan form di samping kiri untuk mulai menyusun materi pelajaran.</p>
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
                customClass: { popup: 'rounded-2xl border border-[#B7DFB9] bg-[#DFF6DD] text-[#107C10] shadow-md' }
            });
        @endif
        @if(session('error'))
            Swal.fire({ 
                icon: 'error', title: 'Gagal!', text: "{{ session('error') }}",
                customClass: { popup: 'rounded-2xl' }
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
                    showCancelButton: true,
                    confirmButtonColor: '#D13438',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        popup: 'rounded-[2rem] border border-slate-100 shadow-xl',
                        confirmButton: 'rounded-xl font-bold px-5 py-2.5',
                        cancelButton: 'rounded-xl font-bold px-5 py-2.5'
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