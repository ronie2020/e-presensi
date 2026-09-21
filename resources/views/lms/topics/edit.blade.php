<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('lms.topics.index') }}" class="w-10 h-10 rounded-xl bg-slate-900/80 border border-white/10 flex items-center justify-center text-white hover:bg-slate-800 transition-all shadow-sm">
                <i class="ph-bold ph-arrow-left"></i>
            </a>
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Edit Pokok Bahasan (Bab)') }}
            </h2>
        </div>
    </x-slot>

    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>

    <div class="py-8 sm:py-10 font-sans min-h-screen text-slate-100 bg-[#020b18] relative overflow-hidden pb-20">
        {{-- Efek Latar Belakang --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-gradient-to-b from-sky-600/10 via-blue-600/5 to-transparent pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- HERO SECTION --}}
            <x-hero-section
                badge="Silabus & Kurikulum"
                badgeIcon="ph-list-dashes"
                title="Edit Pokok Bahasan"
                titleHighlight="{{ $topic->title }}"
                description="Perbarui informasi bab untuk mengatur urutan materi dan alur belajar siswa (Learning Player)."
                :chips="[
                    ['icon' => 'ph-pencil-simple', 'label' => 'Revisi Bab'],
                    ['icon' => 'ph-books', 'label' => $topic->subject->name ?? 'Mata Pelajaran'],
                    ['icon' => 'ph-tree-structure', 'label' => 'Modul Terstruktur']
                ]"
                heroIcon="ph-list-dashes"
                showcaseValue="Edit"
                showcaseLabel="Update Bab"
                showcaseSubtitle="Silabus Pembelajaran"
                statusOrb="Mode Revisi"
                statusColor="amber"
            >
                <x-slot:cta>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('lms.topics.index') }}" class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-slate-200 hover:text-white font-bold text-xs rounded-xl border border-white/15 backdrop-blur-md hover:scale-[1.02] transition-all flex items-center gap-2">
                            <i class="ph-bold ph-arrow-left text-base text-sky-400"></i> Kembali ke Bab
                        </a>
                        <a href="{{ route('dashboard') }}" class="px-4 py-2.5 bg-white/5 hover:bg-white/10 text-slate-300 hover:text-white font-bold text-xs rounded-xl border border-white/10 transition-all flex items-center gap-1.5">
                            <i class="ph-bold ph-squares-four text-sm text-sky-400"></i> Dashboard
                        </a>
                    </div>
                </x-slot:cta>
            </x-hero-section>
            
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

            <div class="animate-enter bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] p-8 sm:p-10 shadow-2xl border border-white/10 backdrop-blur-xl">
                <div class="flex items-center gap-4 mb-8 pb-6 border-b border-white/10">
                    <div class="w-12 h-12 rounded-2xl bg-sky-500/10 text-sky-400 border border-sky-500/20 flex items-center justify-center text-2xl shadow-sm">
                        <i class="ph-bold ph-pencil-simple"></i>
                    </div>
                    <div>
                        <h3 class="font-black text-xl text-white">Ubah Data Bab</h3>
                        <p class="text-sm text-slate-400 font-medium mt-1">Perbarui informasi bab untuk mengatur urutan materi.</p>
                    </div>
                </div>

                <form id="editForm" action="{{ route('lms.topics.update', $topic->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="subject_id" class="block text-[11px] font-bold text-sky-400 uppercase tracking-widest mb-2 ml-1">Mapel <span class="text-rose-400">*</span></label>
                            <div class="relative group">
                                <select id="subject_id" name="subject_id" required class="w-full rounded-2xl border border-white/10 bg-slate-900/80 font-bold text-white focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 h-14 px-4 appearance-none text-sm cursor-pointer shadow-sm transition-all duration-300 [color-scheme:dark]">
                                    <option value="" class="bg-slate-900 text-white">-- Pilih Mapel --</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ (old('subject_id') ?? $topic->subject_id) == $subject->id ? 'selected' : '' }} class="bg-slate-900 text-white">
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500 group-hover:text-sky-400 transition-colors"><i class="ph-bold ph-caret-down"></i></div>
                            </div>
                        </div>

                        <div>
                            <label for="order_number" class="block text-[11px] font-bold text-sky-400 uppercase tracking-widest mb-2 ml-1">Urutan Ke- <span class="text-rose-400">*</span></label>
                            <input id="order_number" type="number" name="order_number" value="{{ old('order_number') ?? $topic->order_number }}" min="1" required class="w-full rounded-2xl border border-white/10 bg-slate-900/80 font-bold text-white text-sm focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 h-14 px-4 shadow-sm transition-all duration-300">
                        </div>
                    </div>
                    
                    <div>
                        <label for="title" class="block text-[11px] font-bold text-sky-400 uppercase tracking-widest mb-2 ml-1">Judul Bab <span class="text-rose-400">*</span></label>
                        <input id="title" type="text" name="title" value="{{ old('title') ?? $topic->title }}" required class="w-full rounded-2xl border border-white/10 bg-slate-900/80 font-bold text-white text-sm focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 h-14 px-4 shadow-sm transition-all duration-300 placeholder-slate-500" placeholder="Cth: Bab 1: Sistem Tata Surya">
                    </div>

                    <div>
                        <label for="description" class="block text-[11px] font-bold text-sky-400 uppercase tracking-widest mb-2 ml-1">Deskripsi Singkat (Opsional)</label>
                        <textarea id="description" name="description" rows="4" class="w-full rounded-2xl border border-white/10 bg-slate-900/80 font-medium text-sm text-white focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 p-4 shadow-sm placeholder:text-slate-500 transition-all duration-300" placeholder="Apa yang dipelajari di bab ini?">{{ old('description') ?? $topic->description }}</textarea>
                    </div>

                    <div class="pt-6 border-t border-white/10 flex items-center justify-end gap-3">
                        <a href="{{ route('lms.topics.index') }}" class="px-6 py-3.5 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold rounded-2xl transition-colors text-sm">
                            Batal
                        </a>
                        <button type="submit" id="submitBtn" class="px-8 py-3.5 bg-gradient-to-r from-sky-500 to-blue-600 text-white font-bold rounded-2xl shadow-lg shadow-sky-500/25 hover:from-sky-400 hover:to-blue-500 transition-all flex items-center justify-center gap-2 text-sm active:scale-95 group">
                            <i class="ph-bold ph-floppy-disk text-lg group-hover:scale-110 transition-transform"></i> 
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Efek loading saat form disubmit
        document.getElementById('editForm').addEventListener('submit', function() {
            const btn = document.getElementById('submitBtn');
            btn.innerHTML = '<i class="ph-bold ph-spinner animate-spin text-lg"></i> <span>Menyimpan...</span>';
            btn.classList.add('opacity-80', 'cursor-not-allowed', 'pointer-events-none');
        });
    </script>
    @endpush
</x-app-layout>