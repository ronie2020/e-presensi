<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Materi Pelajaran') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Header Konten -->
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        📚 Kelola Materi
                    </h2>
                    <p class="text-gray-500 text-sm">Bagikan bahan ajar digital kepada siswa.</p>
                </div>
                
                <a href="{{ route('lms.materials.create') }}" class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-500/30 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Upload Materi Baru
                </a>
            </div>

            <!-- Notifikasi Sukses -->
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" class="mb-6 bg-green-50 border border-green-200 text-green-700 p-4 rounded-xl flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-green-500 hover:text-green-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            @endif

            <!-- List Card Materi -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    @if($materials->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($materials as $material)
                                <div class="group relative bg-white border border-gray-200 rounded-xl p-5 hover:shadow-xl hover:border-blue-300 transition-all duration-300 flex flex-col h-full">
                                    
                                    <!-- Icon Tipe -->
                                    <div class="absolute top-4 right-4 z-10">
                                        @if($material->type == 'document')
                                            <span class="bg-red-100 text-red-600 px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider border border-red-200">Dokumen</span>
                                        @elseif($material->type == 'video')
                                            <span class="bg-red-100 text-red-600 px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider border border-red-200">Video</span>
                                        @else
                                            <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider border border-blue-200">Link</span>
                                        @endif
                                    </div>

                                    <!-- Judul & Mapel -->
                                    <div class="mb-3 pr-14">
                                        <h3 class="font-bold text-lg text-gray-800 group-hover:text-blue-600 transition-colors line-clamp-2 leading-tight">
                                            {{ $material->title }}
                                        </h3>
                                        <div class="flex flex-wrap gap-2 mt-2">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">
                                                {{ $material->subject->name ?? 'Mapel Umum' }}
                                            </span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">
                                                {{ $material->schoolClass->name ?? 'Semua Kelas' }}
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Deskripsi Singkat -->
                                    <div class="text-sm text-gray-500 mb-4 line-clamp-3 flex-grow">
                                        {{ $material->description ?? 'Tidak ada deskripsi tambahan.' }}
                                    </div>

                                    <!-- Footer Card -->
                                    <div class="pt-4 border-t border-gray-100 mt-auto flex items-center justify-between">
                                        <div class="text-xs text-gray-400 flex flex-col">
                                            <span>Diupdate:</span>
                                            <span class="font-medium text-gray-600">{{ $material->created_at->diffForHumans() }}</span>
                                        </div>
                                        
                                        <div class="flex gap-2">
                                            <!-- Tombol Download/Lihat -->
                                            @if($material->type == 'document')
                                                <a href="{{ asset('storage/'.$material->file_path) }}" target="_blank" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition" title="Download File">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                </a>
                                            @else
                                                <a href="{{ $material->video_link }}" target="_blank" class="p-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition" title="Buka Link">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                                </a>
                                            @endif

                                            <!-- Tombol Hapus -->
                                            <form action="{{ route('lms.materials.destroy', $material->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini? Data file juga akan terhapus.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 bg-red-50 text-red-500 rounded-lg hover:bg-red-100 transition" title="Hapus Permanen">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="mt-8">
                            {{ $materials->links() }}
                        </div>
                    @else
                        <!-- State Kosong -->
                        <div class="text-center py-16">
                            <div class="bg-blue-50 rounded-full w-20 h-20 flex items-center justify-center mx-auto mb-4 animate-pulse">
                                <svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900">Belum ada materi pelajaran</h3>
                            <p class="mt-2 text-gray-500 text-sm max-w-md mx-auto">Anda belum mengunggah materi apapun. Mulailah dengan menambahkan modul, presentasi, atau video pembelajaran untuk siswa.</p>
                            <a href="{{ route('lms.materials.create') }}" class="mt-6 inline-flex items-center px-6 py-3 bg-blue-600 text-white text-sm font-bold rounded-xl hover:bg-blue-700 transition shadow-lg hover:shadow-xl hover:-translate-y-1">
                                Tambah Materi Pertama
                            </a>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</x-app-layout>