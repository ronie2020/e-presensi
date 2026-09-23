<!-- KATA MEREKA / BUKU TAMU -->
<div class="py-20 relative overflow-hidden transition-colors duration-300">
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[300px] bg-elevate-accent/10 rounded-full blur-[140px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-12" data-aos="fade-up">
            <h2 class="text-3xl font-black text-white">Kata Mereka</h2>
            <p class="text-slate-300 mt-2 mb-6 font-medium">Pesan dan kesan dari pengunjung sekolah kami.</p>
            
            <div class="flex items-center justify-center gap-3 flex-wrap">
                <button @click="guestBookModalOpen = true" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-gradient-to-r from-elevate-accent to-elevate-primary text-white text-sm font-bold hover:shadow-[0_0_20px_rgba(86,187,241,0.4)] border border-elevate-accent/30 transition shadow-lg">
                    <i class="ph-bold ph-pencil-simple-line"></i> Isi Buku Tamu
                </button>
                <button @click="guestListModalOpen = true" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-white/5 backdrop-blur-md border border-white/15 text-slate-300 text-sm font-bold hover:border-elevate-accent/40 hover:text-elevate-accent hover:bg-white/10 transition shadow-sm">
                    <i class="ph-bold ph-list-dashes text-elevate-accent"></i> Lihat Semua Tamu
                </button>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @forelse($guestbooks as $guest)
                <div class="bg-white/5 backdrop-blur-xl p-6 rounded-[2rem] shadow-[0_8px_32px_rgba(0,0,0,0.3)] border border-white/10 hover:border-elevate-accent/30 hover:bg-white/10 transition-all h-full flex flex-col" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-elevate-accent/20 text-elevate-accent flex items-center justify-center font-bold shrink-0 border border-elevate-accent/30 shadow-sm">
                            {{ substr($guest->name, 0, 1) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-white text-sm line-clamp-1">{{ $guest->name }}</h4>
                            <p class="text-xs text-slate-400 line-clamp-1">{{ $guest->institution }}</p>
                        </div>
                    </div>
                    <div class="relative flex-1 bg-white/5 border border-white/5 p-4 rounded-xl">
                        <i class="ph-fill ph-quotes text-elevate-accent/30 text-2xl absolute -top-2 -left-1"></i>
                        <p class="text-slate-200 text-sm italic leading-relaxed relative z-10 pl-2 font-medium">
                            "{{ Str::limit($guest->message, 150) }}"
                        </p>
                    </div>
                    <div class="mt-3 text-[10px] text-slate-500 text-right font-medium">
                        {{ $guest->created_at->diffForHumans() }}
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-16 bg-white/5 backdrop-blur-xl rounded-[2.5rem] border-2 border-dashed border-white/10">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-elevate-accent/10 mb-4 text-elevate-accent shadow-sm border border-elevate-accent/20">
                        <i class="ph-duotone ph-chats-teardrop text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-white">Belum Ada Pesan</h3>
                    <p class="text-slate-400 text-sm mt-1">Jadilah pengunjung pertama yang memberikan kesan!</p>
                    <button @click="guestBookModalOpen = true" class="mt-4 px-6 py-2.5 bg-gradient-to-r from-elevate-accent to-elevate-primary text-white text-sm font-bold rounded-xl hover:shadow-[0_0_20px_rgba(86,187,241,0.4)] border border-elevate-accent/30 transition shadow-lg">
                        Isi Buku Tamu
                    </button>
                </div>
            @endforelse
        </div>
    </div>
</div>