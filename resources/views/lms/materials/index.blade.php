<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Materi Pelajaran') }}
        </h2>
    </x-slot>

    {{-- CUSTOM STYLES FLUENT --}}
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        .fluent-card { box-shadow: 0 1.6px 3.6px 0 rgba(0, 0, 0, 0.132), 0 0.3px 0.9px 0 rgba(0, 0, 0, 0.108); border: 1px solid rgba(0, 0, 0, 0.05); transition: all 0.3s ease; }
        .fluent-card:hover { box-shadow: 0 6.4px 14.4px 0 rgba(0, 0, 0, 0.132), 0 1.2px 3.6px 0 rgba(0, 0, 0, 0.108); transform: translateY(-2px); }
        .fluent-modal { box-shadow: 0 25.6px 57.6px 0 rgba(0, 0, 0, 0.22), 0 4.8px 14.4px 0 rgba(0, 0, 0, 0.18); border: 1px solid rgba(0, 0, 0, 0.05); }
    </style>

    <div class="py-6 md:py-10 font-sans text-slate-800 pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO SECTION ELEVATE --}}
            <div class="animate-enter relative rounded-xl bg-gradient-to-br from-[#25D0FF] via-[#5295FF] to-[#FFC9B9] p-8 md:p-10 mb-10 text-[#2A3B52] shadow-[0_10px_40px_-10px_rgba(37,208,255,0.4)] overflow-hidden border border-white/40 group">
                
                {{-- Dekorasi Background --}}
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-white/30 rounded-full blur-[80px] pointer-events-none group-hover:bg-white/40 transition-all duration-700"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-white/20 rounded-full blur-[80px] pointer-events-none group-hover:bg-white/30 transition-all duration-700"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
                    <div class="text-center md:text-left">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/40 border border-white/50 text-[#2A3B52] text-[10px] font-black uppercase tracking-widest mb-3 backdrop-blur-md shadow-sm">
                            <i class="ph-fill ph-chalkboard-teacher"></i> Area Guru
                        </div>
                        <h2 class="text-3xl md:text-4xl font-black tracking-tight mb-2 flex items-center justify-center md:justify-start gap-3 text-[#2A3B52]">
                            Kelola Materi
                        </h2>
                        <p class="text-[#2A3B52]/80 text-sm md:text-base font-medium max-w-lg leading-relaxed">
                            Bagikan bahan ajar digital (Dokumen, Video, Link) kepada siswa untuk mendukung kegiatan belajar mengajar.
                        </p>
                    </div>
                    
                    {{-- Tombol Upload Responsif --}}
                    <a href="{{ route('lms.materials.create') }}" class="w-full md:w-auto group bg-[#2A3B52] text-white px-7 py-4 rounded-xl font-bold text-sm shadow-md hover:bg-[#182436] transition-all duration-300 flex items-center justify-center gap-3 active:scale-95 border border-transparent">
                        <div class="bg-white/20 text-white w-8 h-8 rounded-lg flex items-center justify-center group-hover:bg-white group-hover:text-[#2A3B52] transition-colors">
                            <i class="ph-bold ph-plus"></i>
                        </div>
                        <span>Upload Materi Baru</span>
                    </a>
                </div>
            </div>

            {{-- SEARCH & FILTER BAR --}}
            <div class="animate-enter mb-10 bg-white p-4 rounded-xl fluent-card flex flex-col md:flex-row gap-4" style="animation-delay: 100ms">
                <form action="{{ route('lms.materials.index') }}" method="GET" class="flex-1 flex flex-col md:flex-row gap-4 w-full">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-magnifying-glass text-lg"></i></div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul materi..." class="w-full pl-11 pr-4 h-12 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:ring-[#5295FF] focus:border-[#5295FF] text-sm font-medium transition-colors">
                    </div>
                    <div class="flex gap-2">
                        <div class="relative w-full md:w-48">
                            <select name="subject" class="w-full h-12 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:ring-[#5295FF] focus:border-[#5295FF] text-sm font-medium transition-colors appearance-none px-4">
                                <option value="">Semua Mapel</option>
                                @if(isset($subjects))
                                    @foreach($subjects as $sub)
                                        <option value="{{ $sub->id }}" {{ request('subject') == $sub->id ? 'selected' : '' }}>{{ $sub->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                        </div>
                        <button type="submit" class="h-12 px-6 bg-[#2A3B52] hover:bg-[#182436] text-white font-bold rounded-xl transition-all shadow-sm text-sm active:scale-95 border border-transparent">Filter</button>
                    </div>
                </form>
            </div>

            {{-- LIST MATERI --}}
            @if($materials->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($materials as $index => $material)
                        {{-- 
                            1. LOGIKA FILE PATH & URL (Fix 404)
                        --}}
                        @php
                            $realType = $material->type;
                            $realPath = $material->file_path;
                            $realLink = $material->video_link;
                            
                            // Cek lampiran jika data utama kosong
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

                            // Konfigurasi Ikon Tipe File
                            $typeConfig = match($realType) {
                                'document' => ['icon' => 'ph-file-pdf', 'label' => 'Dokumen'],
                                'video' => ['icon' => 'ph-youtube-logo', 'label' => 'Video'],
                                default => ['icon' => 'ph-link', 'label' => 'Link']
                            };
                        @endphp

                        {{-- 
                            2. LOGIKA TEMA WARNA BERDASARKAN MAPEL 
                        --}}
                        @php
                            $subjectName = strtolower($material->subject->name ?? 'umum');
                            $theme = ['bg' => 'bg-slate-50', 'text' => 'text-slate-600', 'border' => 'border-slate-200', 'light' => 'bg-slate-100', 'ring' => 'ring-slate-100'];

                            if (str_contains($subjectName, 'matematika') || str_contains($subjectName, 'fisika')) {
                                $theme = ['bg' => 'bg-[#F3F9FD]', 'text' => 'text-[#5295FF]', 'border' => 'border-[#D0E7F8]', 'light' => 'bg-[#E0F0FC]', 'ring' => 'ring-[#D0E7F8]'];
                            } elseif (str_contains($subjectName, 'indonesia') || str_contains($subjectName, 'inggris') || str_contains($subjectName, 'jawa')) {
                                $theme = ['bg' => 'bg-[#FDE7E9]', 'text' => 'text-[#D13438]', 'border' => 'border-[#F4C3C9]', 'light' => 'bg-[#FDE7E9]', 'ring' => 'ring-[#F4C3C9]'];
                            } elseif (str_contains($subjectName, 'ipa') || str_contains($subjectName, 'biologi') || str_contains($subjectName, 'kimia') || str_contains($subjectName, 'alam')) {
                                $theme = ['bg' => 'bg-[#DFF6DD]', 'text' => 'text-[#107C10]', 'border' => 'border-[#B7DFB9]', 'light' => 'bg-[#DFF6DD]', 'ring' => 'ring-[#B7DFB9]'];
                            } elseif (str_contains($subjectName, 'ips') || str_contains($subjectName, 'sejarah') || str_contains($subjectName, 'geografi') || str_contains($subjectName, 'ekonomi')) {
                                $theme = ['bg' => 'bg-[#FFEFD6]', 'text' => 'text-[#D83B01]', 'border' => 'border-[#FFD8A8]', 'light' => 'bg-[#FFEFD6]', 'ring' => 'ring-[#FFD8A8]'];
                            } elseif (str_contains($subjectName, 'agama') || str_contains($subjectName, 'pai') || str_contains($subjectName, 'quran')) {
                                $theme = ['bg' => 'bg-teal-50', 'text' => 'text-teal-700', 'border' => 'border-teal-200', 'light' => 'bg-teal-100', 'ring' => 'ring-teal-100'];
                            } elseif (str_contains($subjectName, 'seni') || str_contains($subjectName, 'budaya') || str_contains($subjectName, 'prakarya')) {
                                $theme = ['bg' => 'bg-purple-50', 'text' => 'text-purple-700', 'border' => 'border-purple-200', 'light' => 'bg-purple-100', 'ring' => 'ring-purple-100'];
                            } elseif (str_contains($subjectName, 'informatika') || str_contains($subjectName, 'tik') || str_contains($subjectName, 'komputer')) {
                                $theme = ['bg' => 'bg-[#F3F9FD]', 'text' => 'text-[#5295FF]', 'border' => 'border-[#D0E7F8]', 'light' => 'bg-[#E0F0FC]', 'ring' => 'ring-[#D0E7F8]'];
                            } elseif (str_contains($subjectName, 'pjok') || str_contains($subjectName, 'olahraga')) {
                                $theme = ['bg' => 'bg-[#DFF6DD]', 'text' => 'text-[#107C10]', 'border' => 'border-[#B7DFB9]', 'light' => 'bg-[#DFF6DD]', 'ring' => 'ring-[#B7DFB9]'];
                            } elseif (str_contains($subjectName, 'pkn') || str_contains($subjectName, 'pancasila')) {
                                $theme = ['bg' => 'bg-[#FDE7E9]', 'text' => 'text-[#D13438]', 'border' => 'border-[#F4C3C9]', 'light' => 'bg-[#FDE7E9]', 'ring' => 'ring-[#F4C3C9]'];
                            }
                        @endphp

                        {{-- START CARD --}}
                        <div class="animate-enter group relative bg-white rounded-xl fluent-card flex flex-col h-full hover:border-transparent hover:ring-2 {{ $theme['ring'] }}" style="animation-delay: {{ ($index + 1) * 100 }}ms">
                            
                            {{-- Inner Card Content --}}
                            <div class="p-6 h-full flex flex-col relative overflow-hidden">
                                
                                {{-- Background Dekoratif Halus --}}
                                <div class="absolute -right-10 -top-10 w-32 h-32 rounded-full {{ $theme['bg'] }} opacity-50 group-hover:scale-150 transition-transform duration-500 pointer-events-none"></div>

                                {{-- Header: Ikon File & Tipe --}}
                                <div class="flex justify-between items-start mb-4 relative z-10">
                                    <div class="w-14 h-14 rounded-xl flex items-center justify-center text-3xl shadow-sm border {{ $theme['border'] }} {{ $theme['bg'] }} {{ $theme['text'] }} group-hover:scale-110 transition-transform duration-300">
                                        <i class="ph-duotone {{ $typeConfig['icon'] }}"></i>
                                    </div>
                                    
                                    <div class="flex flex-col items-end gap-1">
                                        {{-- Badge Tipe File --}}
                                        <span class="px-2.5 py-1 rounded-lg bg-slate-50 text-slate-500 border border-slate-100 text-[10px] font-black uppercase tracking-wider shadow-sm">
                                            {{ $typeConfig['label'] }}
                                        </span>
                                        <!-- Indikator Jumlah Lampiran -->
                                        @if($material->attachments->count() > 1)
                                            <span class="text-[10px] font-bold text-slate-400 bg-slate-50 px-2 py-0.5 rounded-md border border-slate-100 shadow-sm">
                                                +{{ $material->attachments->count() - 1 }} File Lain
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Judul Materi --}}
                                <div class="mb-4 relative z-10">
                                    <h3 class="font-bold text-lg text-[#2A3B52] group-hover:text-[#5295FF] transition-colors line-clamp-2 leading-snug min-h-[3rem]" title="{{ $material->title }}">
                                        {{ $material->title }}
                                    </h3>
                                    
                                    {{-- Badge Mata Pelajaran & Kelas --}}
                                    <div class="flex flex-wrap gap-2 mt-3">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-xs font-bold border {{ $theme['bg'] }} {{ $theme['text'] }} {{ $theme['border'] }} shadow-sm">
                                            <i class="ph-fill ph-book-bookmark"></i>
                                            {{ $material->subject->name ?? 'Mapel Umum' }}
                                        </span>
                                        
                                        {{-- LOGIKA DISPLAY TARGET (KELAS / JENJANG / BULK) --}}
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold bg-white text-slate-500 border border-slate-200 shadow-sm" 
                                              title="{{ $material->is_bulk ? 'Materi ini dibagikan ke beberapa kelas sekaligus' : 'Materi khusus satu kelas' }}">
                                            <i class="ph-fill ph-users"></i>
                                            @if($material->is_bulk)
                                                Semua Kelas {{ $material->target_grade ?? '' }} ({{ $material->total_classes }} Rombel)
                                            @else
                                                {{ $material->schoolClass->name ?? 'Semua Kelas' }}
                                            @endif
                                        </span>
                                    </div>
                                </div>

                                {{-- Deskripsi Singkat --}}
                                <div class="relative z-10 bg-slate-50/50 rounded-lg p-3 border border-slate-100 mb-6 flex-grow">
                                    <p class="text-sm text-slate-500 line-clamp-3 leading-relaxed italic">
                                        {{ $material->resume ?? $material->description ?? 'Tidak ada deskripsi tambahan.' }}
                                    </p>
                                </div>

                                {{-- Footer Actions --}}
                                <div class="pt-4 border-t border-slate-100 mt-auto flex items-center justify-between relative z-10">
                                    <div class="text-[11px] font-bold text-slate-400 flex items-center gap-1">
                                        <i class="ph-fill ph-clock"></i> {{ $material->created_at->diffForHumans() }}
                                    </div>
                                    
                                    <div class="flex items-center gap-2">
                                        {{-- Tombol Buka --}}
                                        <a href="{{ $finalUrl }}" target="{{ $targetAttr }}" class="w-9 h-9 rounded-lg {{ $theme['bg'] }} {{ $theme['text'] }} hover:bg-[#2A3B52] hover:text-white hover:border-[#2A3B52] transition-all flex items-center justify-center active:scale-95 border {{ $theme['border'] }} shadow-sm" title="Buka / Download">
                                            @if($realType == 'video' || $realType == 'link')
                                                <i class="ph-bold ph-arrow-square-out text-lg"></i>
                                            @else
                                                <i class="ph-bold ph-download-simple text-lg"></i>
                                            @endif
                                        </a>

                                        {{-- Tombol Edit --}}
                                        <a href="{{ route('lms.materials.edit', $material->id) }}" class="w-9 h-9 rounded-lg bg-white text-[#D83B01] hover:bg-[#FFEFD6] transition-all flex items-center justify-center active:scale-95 border border-[#FFD8A8] shadow-sm" title="Edit Materi">
                                            <i class="ph-bold ph-pencil-simple text-lg"></i>
                                        </a>

                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('lms.materials.destroy', $material->id) }}" method="POST" class="form-delete-material">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn-delete w-9 h-9 rounded-lg bg-white text-[#D13438] hover:bg-[#FDE7E9] transition-all flex items-center justify-center active:scale-95 border border-[#F4C3C9] shadow-sm" title="Hapus Materi">
                                                <i class="ph-bold ph-trash text-lg"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-10 animate-enter px-4" style="animation-delay: 500ms">
                    {{ $materials->links() }}
                </div>
            @else
                {{-- Empty State --}}
                <div class="animate-enter bg-white rounded-xl fluent-card border-2 border-dashed border-slate-200 p-12 flex flex-col items-center justify-center text-center group hover:border-[#5295FF] transition-colors" style="animation-delay: 200ms">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-6 group-hover:bg-[#F3F9FD] group-hover:text-[#5295FF] transition-all duration-500 border border-slate-100 group-hover:border-[#D0E7F8]">
                        <i class="ph-duotone ph-books text-5xl"></i>
                    </div>
                    <h3 class="font-black text-[#2A3B52] text-xl mb-2">{{ request('search') ? 'Materi Tidak Ditemukan' : 'Belum Ada Materi' }}</h3>
                    <p class="text-slate-500 text-sm max-w-md mx-auto leading-relaxed mb-8">
                        {{ request('search') ? 'Coba ubah kata kunci pencarian atau filter mapel Anda.' : 'Anda belum mengunggah materi pelajaran apapun. Mulailah berbagi ilmu.' }}
                    </p>
                    <a href="{{ route('lms.materials.create') }}" class="px-8 py-3.5 bg-[#2A3B52] text-white font-bold rounded-xl hover:bg-[#182436] transition shadow-md flex items-center gap-2 active:scale-95 border border-transparent">
                        <i class="ph-bold ph-plus"></i> {{ request('search') ? 'Upload Materi Baru' : 'Tambah Materi Pertama' }}
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
                        showCancelButton: true,
                        confirmButtonColor: '#D13438',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-xl fluent-modal font-sans border-0',
                            confirmButton: 'bg-[#D13438] text-white px-6 py-2.5 rounded-lg text-sm font-bold shadow-sm border border-transparent',
                            cancelButton: 'bg-slate-100 text-slate-600 px-6 py-2.5 rounded-lg text-sm font-bold hover:bg-slate-200 border border-transparent'
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
                    showConfirmButton: false,
                    timer: 2000,
                    customClass: {
                        popup: 'rounded-xl fluent-modal font-sans border-0'
                    }
                });
            @endif
        });
    </script>
    @endpush
</x-app-layout>