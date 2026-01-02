<x-app-layout>
    <div class="py-8 font-sans text-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER NAVIGATION --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div class="flex items-center gap-4">
                    <a href="{{ route('admin.alumni.index') }}" class="p-3 bg-white rounded-xl border border-gray-200 hover:bg-gray-50 transition shadow-sm text-slate-500">
                        <i class="ph-bold ph-arrow-left text-xl"></i>
                    </a>
                    <div>
                        <h1 class="text-2xl font-black text-slate-800">Rekap Testimoni Alumni</h1>
                        <p class="text-slate-500 text-sm">Daftar pesan dan kesan dari alumni yang telah mengisi tracer study.</p>
                    </div>
                </div>
                
                {{-- Statistik Ringkas --}}
                <div class="px-5 py-2 bg-white rounded-xl border border-slate-200 shadow-sm flex items-center gap-3">
                    <div class="p-2 bg-amber-50 text-amber-600 rounded-lg">
                        <i class="ph-fill ph-quotes text-xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Testimoni</p>
                        <p class="text-lg font-black text-slate-800">{{ $testimonials->total() }}</p>
                    </div>
                </div>
            </div>

            {{-- CONTENT GRID --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($testimonials as $item)
                    <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full group">
                        
                        {{-- Header Profil --}}
                        <div class="flex items-center gap-4 mb-4 border-b border-slate-50 pb-4">
                            <div class="relative shrink-0">
                                <div class="w-12 h-12 rounded-full bg-slate-100 overflow-hidden border border-slate-200">
                                    @if($item->student && $item->student->photo_path)
                                        <img src="{{ asset('storage/' . $item->student->photo_path) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-400 font-bold text-lg">
                                            {{ substr($item->student->name ?? 'A', 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="absolute -bottom-1 -right-1 bg-white rounded-full p-0.5">
                                    @if($item->rating >= 4)
                                        <i class="ph-fill ph-star text-amber-400 text-sm drop-shadow-sm"></i>
                                    @else
                                        <i class="ph-fill ph-star text-slate-300 text-sm"></i>
                                    @endif
                                </div>
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-bold text-slate-800 text-sm truncate" title="{{ $item->student->name ?? 'Alumni' }}">
                                    {{ $item->student->name ?? 'Data Siswa Terhapus' }}
                                </h4>
                                <div class="flex items-center gap-2 mt-0.5">
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-500">
                                        Lulus {{ $item->student->graduation_year ?? '-' }}
                                    </span>
                                    <span class="text-[10px] text-slate-400 truncate max-w-[100px]">
                                        {{ $item->activity_status }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Isi Testimoni --}}
                        <div class="relative flex-1 mb-4">
                            <i class="ph-fill ph-quotes text-4xl text-slate-100 absolute -top-2 -left-2 -z-10"></i>
                            <p class="text-sm text-slate-600 leading-relaxed italic relative z-10">
                                "{{ Str::limit($item->testimony, 150) }}"
                            </p>
                            @if(strlen($item->testimony) > 150)
                                <button type="button" onclick="alert('{{ js_escape($item->testimony) }}')" class="text-xs font-bold text-blue-500 hover:text-blue-700 mt-1 cursor-pointer">
                                    Baca selengkapnya
                                </button>
                            @endif
                        </div>

                        {{-- Footer Card --}}
                        <div class="mt-auto pt-4 border-t border-slate-50 flex justify-between items-center text-xs">
                            <div class="text-slate-400 font-medium flex items-center gap-1">
                                <i class="ph-fill ph-calendar-blank"></i>
                                {{ $item->updated_at->format('d M Y') }}
                            </div>
                            <a href="{{ route('admin.alumni.show', $item->student_id) }}" class="font-bold text-blue-600 hover:text-blue-700 flex items-center gap-1 group-hover:gap-2 transition-all">
                                Detail Profil <i class="ph-bold ph-arrow-right"></i>
                            </a>
                        </div>

                    </div>
                @empty
                    <div class="col-span-full py-16 text-center">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                            <i class="ph-duotone ph-chat-teardrop-slash text-4xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-700">Belum Ada Testimoni</h3>
                        <p class="text-slate-500 text-sm">Belum ada alumni yang mengisi kolom testimoni pada tracer study.</p>
                    </div>
                @endforelse
            </div>

            {{-- PAGINATION --}}
            <div class="mt-8">
                {{ $testimonials->links() }}
            </div>

        </div>
    </div>
    
    {{-- Helper untuk escape JS string (Simpan di file helpers atau php block) --}}
    @php
        function js_escape($str) {
            return str_replace(["\r", "\n", "'"], [' ', '\\n', "\\'"], $str);
        }
    @endphp
</x-app-layout>