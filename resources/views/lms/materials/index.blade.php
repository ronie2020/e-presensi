<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Materi Pelajaran') }}
        </h2>
    </x-slot>

    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>

    <div class="py-8 sm:py-10 font-sans min-h-screen text-slate-100 bg-[#020b18] relative overflow-hidden pb-20">
        
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-gradient-to-b from-sky-600/10 via-blue-600/5 to-transparent pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- HERO SECTION --}}
            <x-hero-section
                badge="E-Learning & Bahan Ajar"
                badgeIcon="ph-book-open-text"
                title="Kelola Materi"
                titleHighlight="Pelajaran Digital"
                description="Bagikan bahan ajar digital (Dokumen, Video Pembelajaran, Tautan Web) kepada siswa untuk mendukung kegiatan belajar mengajar."
                :chips="[
                    ['icon' => 'ph-files', 'label' => 'Materi Multi-Format'],
                    ['icon' => 'ph-chalkboard-teacher', 'label' => 'Terintegrasi Jadwal'],
                    ['icon' => 'ph-lightning', 'label' => 'Akses Langsung Siswa']
                ]"
                heroIcon="ph-book-open-text"
                :showcaseNumber="$materials->total() ?? count($materials)"
                showcaseLabel="Total Materi"
                showcaseSubtitle="Bahan Ajar Digital"
                statusOrb="Katalog Aktif"
                statusColor="emerald"
            >
                <x-slot:cta>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('lms.materials.create') }}" class="px-5 py-2.5 bg-gradient-to-r from-sky-400 to-[#0d52a1] hover:from-sky-300 hover:to-sky-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-sky-500/25 hover:shadow-sky-500/40 hover:scale-[1.02] transition-all flex items-center gap-2 border border-white/20 active:scale-95">
                            <i class="ph-bold ph-plus text-base"></i> Upload Materi
                        </a>
                        <a href="{{ route('lms.topics.index') }}" class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-slate-200 hover:text-white font-bold text-xs rounded-xl border border-white/15 backdrop-blur-md hover:scale-[1.02] transition-all flex items-center gap-1.5">
                            <i class="ph-bold ph-list-dashes text-base text-sky-400"></i> Kelola Bab
                        </a>
                        <a href="{{ route('dashboard') }}" class="px-4 py-2.5 bg-white/10 hover:bg-white/20 text-slate-200 hover:text-white font-bold text-xs rounded-xl border border-white/15 backdrop-blur-md hover:scale-[1.02] transition-all flex items-center gap-1.5">
                            <i class="ph-bold ph-arrow-left text-sm text-sky-400"></i> Dashboard
                        </a>
                    </div>
                </x-slot:cta>
            </x-hero-section>

            {{-- SEARCH & FILTER BAR ELEVATE --}}
            <div class="animate-enter mb-10 bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-5 rounded-[2rem] border border-white/10 shadow-2xl flex flex-col md:flex-row gap-4 backdrop-blur-xl" style="animation-delay: 100ms">
                <form action="{{ route('lms.materials.index') }}" method="GET" class="flex-1 flex flex-col md:flex-row gap-4 w-full">
                    <div class="relative flex-1 group">
                        <div class="absolute inset-y-0 left-5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-sky-400 transition-colors"><i class="ph-bold ph-magnifying-glass text-lg"></i></div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul materi..." class="w-full pl-12 pr-5 h-14 rounded-2xl border border-white/10 bg-slate-900/80 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 text-sm font-bold text-white transition-all shadow-sm placeholder-slate-500">
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                        <div class="relative w-full sm:w-56 group">
                            <select name="subject" class="w-full h-14 rounded-2xl border border-white/10 bg-slate-900/80 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 text-sm font-bold text-white transition-all appearance-none px-5 shadow-sm cursor-pointer [color-scheme:dark]">
                                <option value="" class="bg-slate-900 text-white">Semua Mapel</option>
                                @if(isset($subjects))
                                    @foreach($subjects as $sub)
                                        <option value="{{ $sub->id }}" {{ request('subject') == $sub->id ? 'selected' : '' }} class="bg-slate-900 text-white">{{ $sub->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-sky-400 transition-colors"><i class="ph-bold ph-caret-down"></i></div>
                        </div>
                        <button type="submit" class="h-14 px-8 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white font-bold rounded-2xl transition-all shadow-lg shadow-sky-500/25 text-sm active:scale-95 border border-transparent w-full sm:w-auto flex justify-center items-center gap-2">
                            <i class="ph-bold ph-funnel text-lg"></i> Filter
                        </button>
                    </div>
                </form>
            </div>

            {{-- LIST MATERI --}}
            @if($materials->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($materials as $index => $material)
                        @php
                            $realType = $material->type;
                            $realPath = $material->file_path;
                            $realLink = $material->video_link;
                            
                            if (empty($realPath) && empty($realLink) && $material->attachments->isNotEmpty()) {
                                $firstAtt = $material->attachments->first();
                                $realPath = $firstAtt->file_path;
                                
                                if ($firstAtt->file_type == 'file') {
                                    $realType = 'document';
                                } elseif ($firstAtt->file_type == 'video') {
                                    $realType = 'video';
                                    $realLink = $firstAtt->file_path; 
                                } else {
                                    $realType = 'link';
                                    $realLink = $firstAtt->file_path;
                                }
                            }

                            $finalUrl = '#';
                            $targetAttr = '';
                            if ($realType == 'document' && !empty($realPath)) {
                                $cleanPath = str_replace(['public/', 'public\\'], '', $realPath);
                                $finalUrl = asset('storage/' . $cleanPath);
                                $targetAttr = '_blank';
                            } elseif (($realType == 'video' || $realType == 'link') && !empty($realLink)) {
                                $finalUrl = $realLink;
                                $targetAttr = '_blank';
                            }

                            $typeConfig = match($realType) {
                                'document' => ['icon' => 'ph-file-pdf', 'label' => 'Dokumen'],
                                'video' => ['icon' => 'ph-youtube-logo', 'label' => 'Video'],
                                default => ['icon' => 'ph-link', 'label' => 'Link']
                            };
                        @endphp

                        @php
                            $subjectName = strtolower($material->subject->name ?? 'umum');
                            $theme = ['bg' => 'bg-sky-500/10', 'text' => 'text-sky-400', 'border' => 'border-sky-500/20'];

                            if (str_contains($subjectName, 'indonesia') || str_contains($subjectName, 'inggris') || str_contains($subjectName, 'jawa') || str_contains($subjectName, 'pkn') || str_contains($subjectName, 'pancasila')) {
                                $theme = ['bg' => 'bg-rose-500/10', 'text' => 'text-rose-400', 'border' => 'border-rose-500/20'];
                            } elseif (str_contains($subjectName, 'ipa') || str_contains($subjectName, 'biologi') || str_contains($subjectName, 'kimia') || str_contains($subjectName, 'alam') || str_contains($subjectName, 'pjok') || str_contains($subjectName, 'olahraga')) {
                                $theme = ['bg' => 'bg-emerald-500/10', 'text' => 'text-emerald-400', 'border' => 'border-emerald-500/20'];
                            } elseif (str_contains($subjectName, 'ips') || str_contains($subjectName, 'sejarah') || str_contains($subjectName, 'geografi') || str_contains($subjectName, 'ekonomi')) {
                                $theme = ['bg' => 'bg-amber-500/10', 'text' => 'text-amber-400', 'border' => 'border-amber-500/20'];
                            }
                        @endphp

                        {{-- START CARD --}}
                        <div class="animate-enter group relative bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-2xl shadow-xl flex flex-col h-full border border-white/10 transition-all duration-300 hover:-translate-y-1 hover:border-sky-400/40 backdrop-blur-xl overflow-hidden" style="animation-delay: {{ ($index + 1) * 100 }}ms">
                            
                            {{-- CARD COVER THUMBNAIL --}}
                            <div class="relative h-40 w-full overflow-hidden shrink-0 bg-slate-900">
                                @if($material->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($material->cover_image))
                                    <img src="{{ asset('storage/' . $material->cover_image) }}" alt="{{ $material->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    <div class="absolute inset-0 bg-gradient-to-t from-[#021124] via-transparent to-black/30"></div>
                                @else
                                    @php
                                        $gradientTheme = match(true) {
                                            str_contains($subjectName, 'matematika') => ['bg' => 'from-blue-600 via-indigo-700 to-slate-900', 'icon' => 'ph-calculator'],
                                            str_contains($subjectName, 'ipa') || str_contains($subjectName, 'biologi') || str_contains($subjectName, 'fisika') => ['bg' => 'from-emerald-600 via-teal-700 to-slate-900', 'icon' => 'ph-flask'],
                                            str_contains($subjectName, 'inggris') || str_contains($subjectName, 'bahasa') || str_contains($subjectName, 'indonesia') => ['bg' => 'from-purple-600 via-indigo-800 to-slate-900', 'icon' => 'ph-translate'],
                                            str_contains($subjectName, 'informatika') || str_contains($subjectName, 'tik') => ['bg' => 'from-sky-600 via-blue-800 to-slate-950', 'icon' => 'ph-code'],
                                            str_contains($subjectName, 'ips') || str_contains($subjectName, 'sejarah') => ['bg' => 'from-amber-600 via-orange-800 to-slate-950', 'icon' => 'ph-compass'],
                                            default => ['bg' => 'from-sky-600 via-indigo-800 to-[#021124]', 'icon' => 'ph-book-open-text']
                                        };
                                    @endphp
                                    <div class="w-full h-full bg-gradient-to-br {{ $gradientTheme['bg'] }} p-4 flex flex-col justify-between relative overflow-hidden group-hover:scale-105 transition-transform duration-500">
                                        <div class="absolute -right-6 -bottom-6 text-white/10 text-8xl pointer-events-none">
                                            <i class="ph-duotone {{ $gradientTheme['icon'] }}"></i>
                                        </div>
                                        <div class="flex items-center justify-between relative z-10">
                                            <span class="w-8 h-8 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-base text-white">
                                                <i class="ph-bold {{ $gradientTheme['icon'] }}"></i>
                                            </span>
                                            <span class="text-[9px] font-black tracking-widest text-white/70 uppercase">MODUL DIGITAL</span>
                                        </div>
                                        <div class="relative z-10">
                                            <p class="text-[11px] font-black text-white/90 uppercase tracking-widest">{{ $material->subject->name ?? 'Mata Pelajaran' }}</p>
                                        </div>
                                        <div class="absolute inset-0 bg-gradient-to-t from-[#021124] via-transparent to-transparent"></div>
                                    </div>
                                @endif

                                {{-- Badges Overlay di atas Cover --}}
                                <div class="absolute top-3 left-3 right-3 z-10 flex items-center justify-between gap-2 pointer-events-none">
                                    <span class="px-2.5 py-1 rounded-lg bg-[#021124]/85 backdrop-blur-md text-sky-300 border border-white/20 text-[10px] font-black uppercase tracking-wider shadow-md truncate max-w-[170px]">
                                        <i class="ph-fill ph-book-bookmark text-sky-400 mr-1"></i>{{ $material->subject->name ?? 'Mapel Umum' }}
                                    </span>
                                    <div class="flex items-center gap-1 shrink-0">
                                        <span class="px-2.5 py-1 rounded-lg bg-slate-900/85 backdrop-blur-md border border-white/20 text-slate-200 text-[10px] font-bold shadow-md flex items-center gap-1">
                                            <i class="ph-bold {{ $typeConfig['icon'] }} text-sky-400"></i>
                                            {{ $typeConfig['label'] }}
                                        </span>
                                        @if($material->attachments->count() > 1)
                                            <span class="px-1.5 py-1 rounded-lg bg-black/70 backdrop-blur-md border border-white/10 text-[9px] font-bold text-slate-300 shadow-md">
                                                +{{ $material->attachments->count() - 1 }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            {{-- Inner Card Content --}}
                            <div class="p-4 sm:p-5 flex flex-col flex-1 justify-between relative overflow-hidden">
                                
                                {{-- Background Glow Halus --}}
                                <div class="absolute -right-10 -top-10 w-28 h-28 rounded-full {{ $theme['bg'] }} opacity-20 pointer-events-none blur-xl"></div>

                                <div>
                                    {{-- Class badge & Target --}}
                                    <div class="flex items-center gap-2 mb-2 relative z-10">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md bg-white/5 border border-white/10 text-[11px] font-semibold text-slate-300" 
                                              title="{{ $material->is_bulk ? 'Materi ini dibagikan ke beberapa kelas sekaligus' : 'Materi khusus satu kelas' }}">
                                            <i class="ph-fill ph-users text-sky-400"></i>
                                            @if($material->is_bulk)
                                                Semua Kelas {{ $material->target_grade ?? '' }} ({{ $material->total_classes }})
                                            @else
                                                {{ $material->schoolClass->name ?? 'Semua Kelas' }}
                                            @endif
                                        </span>
                                    </div>

                                    {{-- Judul Materi --}}
                                    <h3 class="font-bold text-base text-white group-hover:text-sky-300 transition-colors line-clamp-2 leading-snug relative z-10" title="{{ $material->title }}">
                                        {{ $material->title }}
                                    </h3>

                                    {{-- Deskripsi Singkat Ringkas --}}
                                    @php
                                        $desc = trim(strip_tags($material->resume ?? $material->description ?? ''));
                                    @endphp
                                    @if(!empty($desc))
                                        <p class="text-xs text-slate-400 line-clamp-2 leading-relaxed mt-2 font-normal relative z-10" title="{{ $desc }}">
                                            {{ $desc }}
                                        </p>
                                    @endif
                                </div>

                                {{-- Footer Actions --}}
                                <div class="pt-3.5 border-t border-white/10 mt-3.5 flex items-center justify-between gap-2 relative z-10">
                                    <div class="text-[11px] font-medium text-slate-400 flex items-center gap-1.5 truncate">
                                        <i class="ph-fill ph-clock shrink-0 text-sky-400"></i> 
                                        <span class="truncate">{{ $material->created_at->diffForHumans() }}</span>
                                    </div>
                                    
                                    <div class="flex items-center gap-1.5 shrink-0">
                                        {{-- Tombol Buka --}}
                                        <a href="{{ $finalUrl }}" target="{{ $targetAttr }}" class="w-8 h-8 rounded-lg bg-gradient-to-r from-sky-500 to-blue-600 text-white hover:from-sky-400 hover:to-blue-500 transition-all flex items-center justify-center active:scale-95 shadow-md shadow-sky-500/25 shrink-0" title="Buka / Download">
                                            @if($realType == 'video' || $realType == 'link')
                                                <i class="ph-bold ph-arrow-square-out text-sm"></i>
                                            @else
                                                <i class="ph-bold ph-download-simple text-sm"></i>
                                            @endif
                                        </a>

                                        {{-- Tombol Lihat Pembaca --}}
                                        <a href="{{ route('lms.materials.readers', $material->id) }}" class="w-8 h-8 rounded-lg bg-sky-500/10 text-sky-400 border border-sky-500/20 hover:bg-sky-500 hover:text-white transition-all flex items-center justify-center active:scale-95 shadow-sm shrink-0" title="Riwayat Pembaca">
                                            <i class="ph-bold ph-users text-sm"></i>
                                        </a>

                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('lms.materials.edit', $material->id) }}" class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-400 border border-amber-500/20 hover:bg-amber-500 hover:text-white transition-all flex items-center justify-center active:scale-95 shadow-sm shrink-0" title="Edit Materi">
                                            <i class="ph-bold ph-pencil-simple text-sm"></i>
                                        </a>
                                        
                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('lms.materials.destroy', $material->id) }}" method="POST" class="form-delete-material shrink-0 m-0">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn-delete w-8 h-8 rounded-lg bg-rose-500/10 text-rose-400 border border-rose-500/20 hover:bg-rose-500 hover:text-white transition-all flex items-center justify-center active:scale-95 shadow-sm" title="Hapus Materi">
                                                <i class="ph-bold ph-trash text-sm"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-10 animate-enter px-4 flex justify-center" style="animation-delay: 500ms">
                    {{ $materials->links() }}
                </div>
            @else
                {{-- Empty State --}}
                <div class="animate-enter bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] border-2 border-dashed border-white/10 p-16 flex flex-col items-center justify-center text-center group hover:border-sky-400/50 transition-colors backdrop-blur-xl" style="animation-delay: 200ms">
                    <div class="w-24 h-24 bg-slate-900/80 rounded-full flex items-center justify-center text-sky-400 mb-6 group-hover:bg-sky-500 group-hover:text-white transition-all duration-500 border border-white/10 shadow-inner">
                        <i class="ph-duotone ph-books text-5xl"></i>
                    </div>
                    <h3 class="font-black text-white text-2xl mb-2">{{ request('search') ? 'Materi Tidak Ditemukan' : 'Belum Ada Materi' }}</h3>
                    <p class="text-slate-400 text-sm max-w-md mx-auto leading-relaxed font-medium mb-8">
                        {{ request('search') ? 'Coba ubah kata kunci pencarian atau filter mapel Anda.' : 'Anda belum mengunggah materi pelajaran apapun. Mulailah berbagi ilmu.' }}
                    </p>
                    <a href="{{ route('lms.materials.create') }}" class="px-8 py-4 bg-gradient-to-r from-sky-500 to-blue-600 text-white font-bold rounded-2xl hover:from-sky-400 hover:to-blue-500 transition-colors shadow-lg shadow-sky-500/25 flex items-center gap-2 active:scale-95 border border-transparent text-sm">
                        <i class="ph-bold ph-plus text-lg"></i> {{ request('search') ? 'Upload Materi Baru' : 'Tambah Materi Pertama' }}
                    </a>
                </div>
            @endif

        </div>
    </div>

    {{-- Script SweetAlert --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Handler Tombol Hapus
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function (e) {
                    e.preventDefault(); 
                    const form = this.closest('.form-delete-material');

                    Swal.fire({
                        title: 'Yakin Hapus Materi?',
                        text: "Data yang dihapus tidak bisa dikembalikan.",
                        icon: 'warning',
                        background: '#021124',
                        color: '#fff',
                        showCancelButton: true,
                        confirmButtonColor: '#e11d48',
                        cancelButtonColor: '#334155',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-[2rem] font-sans border border-white/10 bg-[#021124] text-white shadow-2xl',
                            confirmButton: 'bg-rose-600 text-white px-6 py-3 rounded-xl text-sm font-bold hover:bg-rose-700 transition-colors',
                            cancelButton: 'bg-slate-800 text-slate-300 px-6 py-3 rounded-xl text-sm font-bold hover:bg-slate-700 transition-colors'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // Handler Flash Message Success
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    background: '#021124',
                    color: '#fff',
                    showConfirmButton: false,
                    timer: 2500,
                    customClass: {
                        popup: 'rounded-[2rem] font-sans border border-white/10 bg-[#021124] text-white shadow-2xl'
                    }
                });
            @endif
        });
    </script>
    @endpush
</x-app-layout>