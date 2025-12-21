<x-app-layout>
    {{-- Header Judul (Hidden secara visual karena ada di Hero, tapi tetap ada untuk aksesibilitas/breadcrumb jika perlu) --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Materi Pelajaran') }}
        </h2>
    </x-slot>

    <div class="py-8 font-sans text-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO SECTION --}}
            <div class="relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 p-8 mb-8 text-white shadow-xl shadow-blue-900/30 overflow-hidden border border-white/10">
                {{-- Dekorasi Latar --}}
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-blue-400/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-center md:text-left">
                        <h2 class="text-3xl font-extrabold tracking-tight mb-2 flex items-center justify-center md:justify-start gap-3">
                            <span class="text-4xl">📚</span> Kelola Materi
                        </h2>
                        <p class="text-blue-300 text-sm font-medium max-w-lg leading-relaxed">
                            Bagikan bahan ajar digital (Dokumen, Video, Link) kepada siswa untuk mendukung pembelajaran mandiri.
                        </p>
                    </div>
                    
                    {{-- Tombol Aksi Utama --}}
                    <a href="{{ route('lms.materials.create') }}" class="group bg-white text-blue-900 px-6 py-3.5 rounded-2xl font-bold text-sm shadow-lg hover:bg-blue-50 hover:scale-105 transition-all duration-300 flex items-center gap-2">
                        <i class="ph-bold ph-plus-circle text-xl group-hover:rotate-90 transition-transform duration-300"></i>
                        <span>Upload Materi Baru</span>
                    </a>
                </div>
            </div>

            {{-- NOTIFIKASI SUKSES --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl font-bold text-sm flex justify-between items-center shadow-sm">
                    <div class="flex items-center gap-2">
                        <i class="ph-fill ph-check-circle text-xl"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 p-1 rounded-lg hover:bg-emerald-100 transition"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            {{-- LIST MATERI (GRID SYSTEM) --}}
            @if($materials->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($materials as $material)
                        <div class="group relative bg-white border border-slate-100 rounded-[2rem] p-6 hover:shadow-xl hover:shadow-blue-900/5 hover:border-blue-200 transition-all duration-300 flex flex-col h-full">
                            
                            {{-- Badge Tipe File --}}
                            <div class="absolute top-5 right-5 z-10">
                                @if($material->type == 'document')
                                    <span class="bg-rose-50 text-rose-600 border border-rose-100 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider flex items-center gap-1">
                                        <i class="ph-bold ph-file-text"></i> Dokumen
                                    </span>
                                @elseif($material->type == 'video')
                                    <span class="bg-red-50 text-red-600 border border-red-100 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider flex items-center gap-1">
                                        <i class="ph-bold ph-youtube-logo"></i> Video
                                    </span>
                                @else
                                    <span class="bg-sky-50 text-sky-600 border border-sky-100 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider flex items-center gap-1">
                                        <i class="ph-bold ph-link"></i> Link
                                    </span>
                                @endif
                            </div>

                            {{-- Icon Header --}}
                            <div class="mb-4">
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl transition-colors duration-300
                                    {{ $material->type == 'document' ? 'bg-rose-100 text-rose-600 group-hover:bg-rose-600 group-hover:text-white' : 
                                      ($material->type == 'video' ? 'bg-red-100 text-red-600 group-hover:bg-red-600 group-hover:text-white' : 'bg-sky-100 text-sky-600 group-hover:bg-sky-600 group-hover:text-white') }}">
                                    @if($material->type == 'document') <i class="ph-duotone ph-file-pdf"></i>
                                    @elseif($material->type == 'video') <i class="ph-duotone ph-play-circle"></i>
                                    @else <i class="ph-duotone ph-globe"></i>
                                    @endif
                                </div>
                            </div>

                            {{-- Judul & Meta --}}
                            <div class="mb-3">
                                <h3 class="font-bold text-lg text-slate-800 group-hover:text-blue-700 transition-colors line-clamp-2 leading-tight min-h-[3.5rem]">
                                    {{ $material->title }}
                                </h3>
                                <div class="flex flex-wrap gap-2 mt-3">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-50 text-slate-500 border border-slate-100">
                                        {{ $material->subject->name ?? 'Mapel Umum' }}
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-50 text-blue-600 border border-blue-100">
                                        {{ $material->schoolClass->name ?? 'Semua Kelas' }}
                                    </span>
                                </div>
                            </div>

                            {{-- Deskripsi --}}
                            <div class="text-sm text-slate-500 mb-6 line-clamp-3 flex-grow leading-relaxed">
                                {{ $material->description ?? 'Tidak ada deskripsi tambahan untuk materi ini.' }}
                            </div>

                            {{-- Footer Actions --}}
                            <div class="pt-4 border-t border-slate-50 mt-auto flex items-end justify-between">
                                <div class="text-xs text-slate-400 flex flex-col gap-0.5">
                                    <span class="font-bold uppercase tracking-wider text-[10px]">Diupdate</span>
                                    <span class="font-medium text-slate-600">{{ $material->created_at->diffForHumans() }}</span>
                                </div>
                                
                                <div class="flex items-center gap-2">
                                    {{-- Tombol Buka --}}
                                    @if($material->type == 'document')
                                        <a href="{{ asset('storage/'.$material->file_path) }}" target="_blank" class="w-10 h-10 rounded-xl bg-slate-50 text-slate-600 hover:bg-blue-600 hover:text-white hover:shadow-lg hover:shadow-blue-200 transition-all flex items-center justify-center" title="Download File">
                                            <i class="ph-bold ph-download-simple text-lg"></i>
                                        </a>
                                    @else
                                        <a href="{{ $material->video_link }}" target="_blank" class="w-10 h-10 rounded-xl bg-slate-50 text-slate-600 hover:bg-blue-600 hover:text-white hover:shadow-lg hover:shadow-blue-200 transition-all flex items-center justify-center" title="Buka Link">
                                            <i class="ph-bold ph-arrow-square-out text-lg"></i>
                                        </a>
                                    @endif

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('lms.materials.destroy', $material->id) }}" method="POST" onsubmit="return confirm('Hapus materi ini? File terkait akan hilang permanen.');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-10 h-10 rounded-xl bg-rose-50 text-rose-500 hover:bg-rose-600 hover:text-white hover:shadow-lg hover:shadow-rose-200 transition-all flex items-center justify-center" title="Hapus Materi">
                                            <i class="ph-bold ph-trash text-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $materials->links() }}
                </div>
            @else
                {{-- Empty State --}}
                <div class="bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200 p-12 flex flex-col items-center justify-center text-center">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-6 animate-pulse">
                        <i class="ph-duotone ph-books text-5xl"></i>
                    </div>
                    <h3 class="font-black text-slate-800 text-xl mb-2">Belum Ada Materi</h3>
                    <p class="text-slate-500 text-sm max-w-md mx-auto leading-relaxed mb-8">
                        Anda belum mengunggah materi pelajaran apapun. Mulailah berbagi ilmu dengan mengupload dokumen atau video pertama Anda.
                    </p>
                    <a href="{{ route('lms.materials.create') }}" class="px-8 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-200 hover:-translate-y-1 transform flex items-center gap-2">
                        <i class="ph-bold ph-plus"></i> Tambah Materi Pertama
                    </a>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>