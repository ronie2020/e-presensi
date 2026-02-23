<!-- GURU SECTION -->
    <div id="guru" class="py-24 bg-slate-50 relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="px-3 py-1 bg-blue-50 text-blue-600 rounded-full text-xs font-bold uppercase tracking-widest border border-blue-100">SDM Unggul</span>
                <h2 class="text-3xl font-extrabold text-slate-900 sm:text-4xl mt-4">Tenaga Pendidik</h2>
                <p class="mt-4 text-lg text-slate-600 max-w-2xl mx-auto">Dibimbing oleh guru-guru profesional yang berdedikasi tinggi.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse($teachers as $teacher)
                    @php
                        // Logika untuk mendecode Role yang berbentuk JSON
                        $displayRole = $teacher->position;
                        if (empty($displayRole)) {
                            $decodedRoles = is_string($teacher->role) ? json_decode($teacher->role, true) : $teacher->role;
                            $displayRole = is_array($decodedRoles) ? implode(', ', $decodedRoles) : $teacher->role;
                        }
                    @endphp
                    <div class="group relative bg-white rounded-2xl overflow-hidden border border-slate-100 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 h-full flex flex-col" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <div class="aspect-[3/4] w-full relative overflow-hidden bg-slate-100">
                            @if($teacher->photo_path)
                                <img src="{{ asset('storage/' . $teacher->photo_path) }}" loading="lazy" alt="{{ $teacher->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                <div class="w-full h-full hidden flex-col items-center justify-center bg-gradient-to-br from-slate-200 to-slate-300 text-slate-500">
                                    <span class="text-6xl font-black opacity-30 select-none uppercase">{{ substr($teacher->name, 0, 2) }}</span>
                                </div>
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100 text-blue-600">
                                    <span class="text-7xl font-black opacity-20 select-none uppercase group-hover:scale-110 transition-transform">{{ substr($teacher->name, 0, 2) }}</span>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-gradient-to-t from-blue-900/80 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                        <div class="p-5 text-center relative bg-white flex-1 flex flex-col justify-end">
                            <div class="absolute -top-4 left-0 right-0 flex justify-center px-4">
                                {{-- Menambahkan truncate dan max-w-full agar rapi jika teksnya panjang --}}
                                <span class="bg-blue-600 text-white text-[10px] font-bold uppercase tracking-wider py-1 px-3 rounded-full shadow-lg border-2 border-white truncate max-w-full" title="{{ $displayRole }}">
                                    {{ $displayRole }}
                                </span>
                            </div>
                            <h3 class="mt-4 text-lg font-bold text-slate-900 group-hover:text-blue-600 transition-colors line-clamp-1">{{ $teacher->name }}</h3>
                            @if(!empty($teacher->nip))
                                <p class="text-xs text-slate-500 font-medium mt-1">NIP. {{ $teacher->nip }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12"><p class="text-slate-500">Belum ada data tenaga pendidik.</p></div>
                @endforelse
            </div>
            <div class="text-center mt-12" data-aos="fade-up">
                <a href="{{ route('teachers.index') }}" class="inline-flex items-center justify-center px-6 py-3 text-sm font-bold text-slate-700 bg-white border border-slate-200 rounded-full hover:bg-slate-50 hover:text-blue-600 hover:border-blue-200 transition-all shadow-sm hover:shadow-md">Lihat Seluruh Staff <i class="ph-bold ph-arrow-right ml-2"></i></a>
            </div>
        </div>
    </div>