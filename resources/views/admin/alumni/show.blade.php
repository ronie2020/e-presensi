<x-app-layout>
    <div class="py-8 font-sans text-slate-800">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER NAVIGATION --}}
            <div class="flex items-center gap-4 mb-8">
                <a href="{{ route('admin.alumni.index') }}" class="p-3 bg-white rounded-xl border border-gray-200 hover:bg-gray-50 transition shadow-sm text-slate-500">
                    <i class="ph-bold ph-arrow-left text-xl"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-black text-slate-800">Detail Alumni</h1>
                    <p class="text-slate-500 text-sm">Informasi lengkap data siswa dan tracer study.</p>
                </div>
                
                <div class="ml-auto flex gap-2">
                    <a href="{{ route('admin.alumni.edit', $student->id) }}" class="px-5 py-2.5 bg-amber-50 text-amber-600 border border-amber-200 rounded-xl font-bold text-sm hover:bg-amber-100 flex items-center gap-2 transition">
                        <i class="ph-bold ph-pencil-simple"></i> Edit Data
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- KOLOM KIRI: PROFIL UTAMA --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-[2.5rem] p-8 shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden text-center">
                        {{-- Background Decor --}}
                        <div class="absolute top-0 left-0 w-full h-24 bg-gradient-to-br from-blue-600 to-indigo-700"></div>
                        
                        <div class="relative z-10 -mt-12 mb-4">
                            <div class="w-32 h-32 mx-auto rounded-full border-4 border-white shadow-lg bg-slate-100 overflow-hidden">
                                @if($student->photo_path)
                                    <img src="{{ asset('storage/' . $student->photo_path) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-slate-200 text-slate-400 text-4xl font-bold">
                                        {{ substr($student->name, 0, 1) }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <h2 class="text-xl font-black text-slate-900 mb-1">{{ $student->name }}</h2>
                        <p class="text-sm text-slate-500 font-mono mb-4">{{ $student->nisn ?? $student->student_id }}</p>

                        <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-slate-100 rounded-full text-xs font-bold text-slate-600 uppercase tracking-wide mb-6">
                            <i class="ph-fill ph-graduation-cap"></i>
                            Angkatan {{ $student->graduation_year ?? \Carbon\Carbon::parse($student->graduated_date)->year }}
                        </div>

                        <div class="space-y-3 text-left bg-slate-50 p-5 rounded-2xl border border-slate-100">
                            <div class="flex items-center gap-3 text-sm">
                                <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-slate-400 shadow-sm"><i class="ph-bold ph-gender-intersex"></i></div>
                                <span class="font-bold text-slate-700">{{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                            </div>
                            <div class="flex items-center gap-3 text-sm">
                                <div class="w-8 h-8 rounded-full bg-white flex items-center justify-center text-slate-400 shadow-sm"><i class="ph-bold ph-map-pin"></i></div>
                                <span class="font-bold text-slate-700 truncate" title="{{ $student->address }}">{{ Str::limit($student->address ?? 'Alamat tidak ada', 25) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: TRACER STUDY --}}
                <div class="lg:col-span-2 space-y-6">
                    
                    {{-- Status Tracer --}}
                    @if($student->alumniProfile)
                        <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-200 relative overflow-hidden">
                            <div class="absolute top-0 right-0 p-6 opacity-5">
                                <i class="ph-fill ph-briefcase text-9xl text-slate-800"></i>
                            </div>

                            <h3 class="text-lg font-black text-slate-800 mb-6 flex items-center gap-2">
                                <i class="ph-fill ph-chart-polar text-blue-600"></i> Status Saat Ini
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Aktivitas</p>
                                    <div class="text-xl font-black text-slate-800 flex items-center gap-2">
                                        @php $status = $student->alumniProfile->activity_status; @endphp
                                        <span class="w-3 h-3 rounded-full 
                                            {{ $status == 'SMA' ? 'bg-blue-500' : '' }}
                                            {{ $status == 'SMK' ? 'bg-orange-500' : '' }}
                                            {{ $status == 'MA' ? 'bg-emerald-500' : '' }}
                                            {{ $status == 'Pesantren' ? 'bg-teal-500' : '' }}
                                            {{ $status == 'Bekerja' ? 'bg-slate-500' : '' }}">
                                        </span>
                                        {{ $status }}
                                    </div>
                                </div>

                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Instansi / Tempat</p>
                                    <div class="text-lg font-bold text-slate-700">
                                        {{ $student->alumniProfile->campus_name ?? $student->alumniProfile->company_name ?? '-' }}
                                    </div>
                                    @if($student->alumniProfile->campus_major || $student->alumniProfile->position)
                                        <div class="text-sm text-slate-500 mt-1">
                                            {{ $student->alumniProfile->campus_major ?? $student->alumniProfile->position }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="mt-8 pt-6 border-t border-slate-100 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase mb-1">Nomor WhatsApp</p>
                                    <a href="https://wa.me/{{ $student->alumniProfile->phone_number }}" target="_blank" class="text-sm font-bold text-emerald-600 hover:underline flex items-center gap-2">
                                        <i class="ph-bold ph-whatsapp-logo"></i> {{ $student->alumniProfile->phone_number ?? '-' }}
                                    </a>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase mb-1">Email</p>
                                    <p class="text-sm font-bold text-slate-700">{{ $student->alumniProfile->email ?? '-' }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Testimoni --}}
                        @if($student->alumniProfile->testimony)
                        <div class="bg-white rounded-[2.5rem] p-8 shadow-sm border border-slate-200">
                            <h3 class="text-lg font-black text-slate-800 mb-4 flex items-center gap-2">
                                <i class="ph-fill ph-quotes text-amber-500"></i> Kesan & Pesan
                            </h3>
                            <div class="bg-amber-50/50 p-6 rounded-2xl border border-amber-100 italic text-slate-600 leading-relaxed relative">
                                <i class="ph-fill ph-quotes text-4xl text-amber-200 absolute -top-2 -left-2"></i>
                                "{{ $student->alumniProfile->testimony }}"
                            </div>
                            <div class="mt-4 flex items-center gap-2">
                                <span class="text-xs font-bold text-slate-400 uppercase">Rating Sekolah:</span>
                                <div class="flex text-amber-400 text-sm">
                                    @for($i=0; $i < ($student->alumniProfile->rating ?? 5); $i++) <i class="ph-fill ph-star"></i> @endfor
                                </div>
                            </div>
                        </div>
                        @endif

                    @else
                        {{-- JIKA BELUM MENGISI --}}
                        <div class="bg-white rounded-[2.5rem] p-12 shadow-sm border border-slate-200 text-center">
                            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                                <i class="ph-duotone ph-clipboard-text text-4xl"></i>
                            </div>
                            <h3 class="text-lg font-black text-slate-800 mb-2">Belum Mengisi Tracer Study</h3>
                            <p class="text-slate-500 text-sm max-w-xs mx-auto mb-6">Alumni ini belum memperbarui data kelulusannya.</p>
                            
                            <a href="{{ route('admin.alumni.edit', $student->id) }}" class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-600/20">
                                <i class="ph-bold ph-pencil-simple"></i> Input Data Manual
                            </a>
                        </div>
                    @endif

                </div>
            </div>

        </div>
    </div>
</x-app-layout>