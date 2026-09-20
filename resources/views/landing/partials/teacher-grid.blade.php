<div class="grid grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
    @forelse($teachers as $index => $teacher)
        @php
            $displayRole = $teacher->position;
            if (empty($displayRole)) {
                $decodedRoles = is_string($teacher->role) ? json_decode($teacher->role, true) : $teacher->role;
                $displayRole = is_array($decodedRoles) ? implode(', ', $decodedRoles) : $teacher->role;
            }
        @endphp
        <div @click="openModal({
                name: '{{ addslashes($teacher->name) }}',
                nip: '{{ $teacher->nip ?? '-' }}',
                pangkat: '{{ $teacher->pangkat ?? '-' }}',
                position: '{{ addslashes($displayRole) }}',
                bio: '{{ addslashes($teacher->bio ?? 'Belum ada pesan & kesan.') }}',
                keahlian: '{{ addslashes($teacher->keahlian ?? '') }}',
                hobi: '{{ addslashes($teacher->hobi ?? '') }}',
                phone: '{{ $teacher->phone }}',
                instagram: '{{ $teacher->instagram }}',
                tiktok: '{{ $teacher->tiktok }}',
                facebook: '{{ $teacher->facebook }}',
                photo_url: '{{ $teacher->photo_path ? asset('storage/' . $teacher->photo_path) : '' }}',
                profile_url: '{{ route('teachers.show', $teacher->id) }}',
                cv_url: '{{ route('teachers.cv', $teacher->id) }}',
                vcard_url: '{{ route('teachers.vcard', $teacher->id) }}'
             })"
             class="animate-enter group bg-white/5 backdrop-blur-xl rounded-[2rem] overflow-hidden shadow-[0_8px_32px_rgba(0,0,0,0.3)] hover:shadow-[0_8px_32px_rgba(86,187,241,0.2)] hover:border-elevate-accent/40 hover:-translate-y-2 transition-all duration-500 border border-white/10 flex flex-col h-full relative cursor-pointer"
             style="animation-delay: {{ ($index % 4) * 100 }}ms">
            
            <!-- Foto Guru -->
            <div class="aspect-[4/5] sm:aspect-square bg-white/5 relative overflow-hidden">
                @if($teacher->photo_path)
                    <img src="{{ asset('storage/' . $teacher->photo_path) }}" alt="{{ $teacher->name }}" loading="lazy" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 filter grayscale group-hover:grayscale-0" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="hidden w-full h-full flex-col items-center justify-center bg-white/5 text-elevate-accent">
                        <span class="text-4xl font-bold opacity-30">{{ substr($teacher->name, 0, 2) }}</span>
                    </div>
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center bg-white/5 text-elevate-accent">
                        <span class="text-6xl sm:text-7xl font-black opacity-30 select-none uppercase group-hover:scale-110 transition-transform duration-500">{{ substr($teacher->name, 0, 1) }}</span>
                    </div>
                @endif
                
                <!-- Overlay Text -->
                <div class="absolute inset-0 bg-elevate-accent/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center backdrop-blur-[2px]">
                    <span class="bg-[#021124]/90 backdrop-blur text-elevate-accent border border-elevate-accent/30 text-xs font-bold px-4 py-2 rounded-full shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-transform">Lihat Profil Lengkap</span>
                </div>
            </div>

            <!-- Info Singkat -->
            <div class="p-5 text-center flex-1 flex flex-col relative bg-transparent">
                <div class="absolute -top-4 left-0 right-0 flex justify-center px-4">
                    <span class="inline-block px-4 py-1.5 bg-[#021124] text-elevate-accent text-[10px] font-black uppercase tracking-wider rounded-full shadow-lg border border-elevate-accent/30 transform group-hover:scale-105 transition-transform truncate max-w-full" title="{{ $displayRole }}">
                        {{ $displayRole }}
                    </span>
                </div>
                <div class="mt-4 mb-2">
                    <h3 class="text-base sm:text-lg font-bold text-white leading-tight group-hover:text-elevate-accent transition-colors line-clamp-1">{{ $teacher->name }}</h3>
                    @if($teacher->nip)
                        <p class="text-[10px] sm:text-xs text-slate-400 font-mono mt-1 font-medium bg-white/5 inline-block px-2 py-0.5 rounded border border-white/10">{{ $teacher->nip }}</p>
                    @endif
                </div>
            </div>
        </div>
     @empty
        <div class="col-span-2 lg:col-span-4 py-24 text-center animate-enter bg-white/5 backdrop-blur-xl rounded-[2.5rem] border-2 border-dashed border-white/10">
            <div class="inline-flex bg-elevate-accent/10 p-6 rounded-full mb-6 text-elevate-accent border border-elevate-accent/20"><i class="ph-duotone ph-magnifying-glass text-5xl"></i></div>
            <h3 class="text-xl font-bold text-white mb-2">Data Tidak Ditemukan</h3>
            <p class="text-slate-400 text-sm max-w-md mx-auto mb-6">Maaf, kami tidak dapat menemukan data guru dengan kata kunci atau filter tersebut.</p>
        </div>
    @endforelse
</div>

<div class="mt-16 px-4 animate-enter pagination-links">{{ $teachers->withQueryString()->links() }}</div>
