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
             class="animate-enter group bg-elevate-surface rounded-[2rem] overflow-hidden shadow-sm hover:shadow-xl hover:shadow-elevate-accent/10 hover:border-elevate-accent/30 hover:-translate-y-2 transition-all duration-500 border border-slate-100 flex flex-col h-full relative cursor-pointer"
             style="animation-delay: {{ ($index % 4) * 100 }}ms">
            
            <!-- Foto Guru -->
            <div class="aspect-[4/5] sm:aspect-square bg-elevate-soft relative overflow-hidden">
                @if($teacher->photo_path)
                    <img src="{{ asset('storage/' . $teacher->photo_path) }}" alt="{{ $teacher->name }}" loading="lazy" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110 filter grayscale group-hover:grayscale-0" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="hidden w-full h-full flex-col items-center justify-center bg-elevate-soft text-elevate-primary">
                        <span class="text-4xl font-bold opacity-30">{{ substr($teacher->name, 0, 2) }}</span>
                    </div>
                @else
                    <div class="w-full h-full flex flex-col items-center justify-center bg-elevate-soft text-elevate-primary">
                        <span class="text-6xl sm:text-7xl font-black opacity-30 select-none uppercase group-hover:scale-110 transition-transform duration-500">{{ substr($teacher->name, 0, 1) }}</span>
                    </div>
                @endif
                
                <!-- Overlay Text -->
                <div class="absolute inset-0 bg-elevate-primary/20 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center backdrop-blur-[2px]">
                    <span class="bg-white/95 backdrop-blur text-elevate-primary text-xs font-bold px-4 py-2 rounded-full shadow-lg transform translate-y-4 group-hover:translate-y-0 transition-transform">Lihat Profil Lengkap</span>
                </div>
            </div>

            <!-- Info Singkat -->
            <div class="p-5 text-center flex-1 flex flex-col relative bg-elevate-surface">
                <div class="absolute -top-4 left-0 right-0 flex justify-center px-4">
                    <span class="inline-block px-4 py-1.5 bg-elevate-dark text-white text-[10px] font-black uppercase tracking-wider rounded-full shadow-lg border-2 border-white transform group-hover:scale-105 transition-transform truncate max-w-full" title="{{ $displayRole }}">
                        {{ $displayRole }}
                    </span>
                </div>
                <div class="mt-4 mb-2">
                    <h3 class="text-base sm:text-lg font-bold text-elevate-dark leading-tight group-hover:text-elevate-primary transition-colors line-clamp-1">{{ $teacher->name }}</h3>
                    @if($teacher->nip)
                        <p class="text-[10px] sm:text-xs text-elevate-dark/50 font-mono mt-1 font-medium bg-slate-50 inline-block px-2 py-0.5 rounded border border-slate-100">{{ $teacher->nip }}</p>
                    @endif
                </div>
            </div>
        </div>
     @empty
        <div class="col-span-2 lg:col-span-4 py-24 text-center animate-enter bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200">
            <div class="inline-flex bg-elevate-soft p-6 rounded-full mb-6 text-elevate-primary ring-8 ring-elevate-soft/50"><i class="ph-duotone ph-magnifying-glass text-5xl"></i></div>
            <h3 class="text-xl font-bold text-elevate-dark mb-2">Data Tidak Ditemukan</h3>
            <p class="text-elevate-dark/60 text-sm max-w-md mx-auto mb-6">Maaf, kami tidak dapat menemukan data guru dengan kata kunci atau filter tersebut.</p>
        </div>
    @endforelse
</div>

<div class="mt-16 px-4 animate-enter pagination-links">{{ $teachers->withQueryString()->links() }}</div>
