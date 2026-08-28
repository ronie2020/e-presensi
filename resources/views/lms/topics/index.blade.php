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
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/40 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-black mb-2 tracking-tight">Struktur Materi (Bab)</h1>
                        <p class="text-elevate-dark/80 text-sm font-semibold max-w-xl">
                            Buat dan kelola Pokok Bahasan/Bab untuk setiap mata pelajaran. Bab ini akan menjadi wadah urutan belajar siswa (Learning Player).
                        </p>
                    </div>
                    <a href="{{ route('lms.materials.index') }}" class="w-full md:w-auto inline-flex justify-center items-center gap-2 px-6 py-3.5 bg-white/60 hover:bg-white rounded-xl text-sm font-bold backdrop-blur-md transition-colors text-elevate-dark border border-white/60 shadow-sm active:scale-95 shrink-0">
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

                        <form action="{{ route('lms.topics.store') }}" method="POST" class="space-y-5">
                            @csrf
                            <div>
                                <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Mapel <span class="text-[#D13438]">*</span></label>
                                <div class="relative group">
                                    <select name="subject_id" required class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-bold text-elevate-dark focus:bg-white focus:border-elevate-accent h-12 px-4 appearance-none text-sm cursor-pointer shadow-sm">
                                        <option value="">-- Pilih Mapel --</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Judul Bab <span class="text-[#D13438]">*</span></label>
                                <input type="text" name="title" value="{{ old('title') }}" required class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-bold text-elevate-dark text-sm focus:bg-white h-12 px-4 shadow-sm" placeholder="Cth: Bab 1: Sistem Tata Surya">
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Urutan Ke- <span class="text-[#D13438]">*</span></label>
                                <input type="number" name="order_number" value="{{ old('order_number', 1) }}" min="1" required class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-bold text-elevate-dark text-sm focus:bg-white h-12 px-4 shadow-sm">
                            </div>

                            <div>
                                <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Deskripsi Singkat (Opsional)</label>
                                <textarea name="description" rows="3" class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-medium text-sm focus:bg-white p-4 shadow-sm placeholder:text-slate-400" placeholder="Apa yang dipelajari di bab ini?">{{ old('description') }}</textarea>
                            </div>

                            <button type="submit" class="w-full py-3.5 bg-elevate-dark text-white font-bold rounded-2xl shadow-lg shadow-elevate-dark/30 hover:bg-elevate-primary transition-all flex items-center justify-center gap-2 text-sm active:scale-95">
                                <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan Bab
                            </button>
                        </form>
                    </div>
                </div>

                {{-- KANAN: DAFTAR BAB --}}
                <div class="lg:col-span-2 animate-enter" style="animation-delay: 200ms">
                    <div class="bg-white rounded-[2.5rem] p-6 shadow-xl shadow-slate-200/40 border border-slate-100 min-h-[500px]">
                        
                        {{-- Filter Area --}}
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 pb-4 border-b border-slate-100">
                            <h3 class="font-black text-lg text-elevate-dark flex items-center gap-2"><i class="ph-duotone ph-list-dashes text-elevate-primary text-2xl"></i> Daftar Bab Tersimpan</h3>
                            
                            <form action="{{ route('lms.topics.index') }}" method="GET" class="w-full sm:w-auto flex gap-2">
                                <div class="relative group w-full sm:w-56">
                                    <select name="subject_id" onchange="this.form.submit()" class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-elevate-dark text-xs h-10 px-3 appearance-none cursor-pointer">
                                        <option value="">Filter Mapel...</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-funnel text-sm"></i></div>
                                </div>
                                @if(request('subject_id'))
                                    <a href="{{ route('lms.topics.index') }}" class="w-10 h-10 bg-[#FDE7E9] text-[#D13438] rounded-xl flex items-center justify-center shrink-0 border border-[#F4C3C9] hover:bg-[#F4C3C9]"><i class="ph-bold ph-x"></i></a>
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
                                        <div class="bg-elevate-soft/50 px-4 py-2 rounded-xl mt-6 first:mt-0">
                                            <p class="text-xs font-black text-elevate-primary uppercase tracking-widest">{{ $topic->subject->name }}</p>
                                        </div>
                                        @php $currentSubject = $topic->subject_id; @endphp
                                    @endif

                                    <div class="flex items-center justify-between p-4 border border-slate-100 rounded-2xl hover:border-elevate-accent/50 hover:shadow-md transition-all group bg-white">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center font-black text-slate-500 shrink-0">
                                                {{ $topic->order_number }}
                                            </div>
                                            <div>
                                                <h4 class="font-black text-elevate-dark text-sm">{{ $topic->title }}</h4>
                                                @if($topic->description)
                                                    <p class="text-xs text-slate-500 font-medium truncate max-w-xs sm:max-w-md mt-0.5">{{ $topic->description }}</p>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center gap-2">
                                            <form action="{{ route('lms.topics.destroy', $topic->id) }}" method="POST" onsubmit="return confirm('Hapus Bab ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="w-8 h-8 bg-white border border-[#F4C3C9] text-[#D13438] rounded-lg hover:bg-[#FDE7E9] flex items-center justify-center transition-colors shadow-sm" title="Hapus Bab">
                                                    <i class="ph-bold ph-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-6">{{ $topics->links() }}</div>
                        @else
                            <div class="flex flex-col items-center justify-center py-10 text-center">
                                <i class="ph-duotone ph-list-dashes text-6xl text-slate-200 mb-3"></i>
                                <h4 class="font-black text-elevate-dark">Belum Ada Bab</h4>
                                <p class="text-xs text-slate-400 mt-1">Silakan gunakan form di samping untuk menambahkan bab pertama.</p>
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
        @if(session('success'))
            Swal.fire({
                icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}",
                toast: true, position: 'top-end', showConfirmButton: false, timer: 3000,
                customClass: { popup: 'rounded-2xl border border-[#B7DFB9] bg-[#DFF6DD] text-[#107C10] shadow-md' }
            });
        @endif
        @if(session('error'))
            Swal.fire({ icon: 'error', title: 'Gagal!', text: "{{ session('error') }}" });
        @endif
    </script>
    @endpush
</x-app-layout>