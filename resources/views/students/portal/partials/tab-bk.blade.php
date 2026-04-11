<div class="space-y-8 font-sans text-slate-800 animate-in fade-in duration-500" 
     x-data="{ 
        showRatingModal: false, 
        selectedSessionId: null, 
        selectedTopic: '',
        ratingValue: 5,
        hoverValue: 0
     }">
    
    <!-- 1. HEADER VIBRANT (Redesigned) -->
    <div class="bg-gradient-to-r from-blue-700 to-indigo-600 rounded-[2.5rem] p-8 md:p-10 text-white shadow-xl shadow-blue-900/10 relative overflow-hidden flex flex-col md:flex-row items-start md:items-center justify-between gap-8">
        <div class="absolute top-0 right-0 opacity-10 pointer-events-none">
            <i class="ph-fill ph-heart-beat text-[200px] transform translate-x-10 -translate-y-10"></i>
        </div>
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-white/5 rounded-full blur-3xl -ml-20 -mb-20 pointer-events-none"></div>

        <div class="relative z-10 max-w-2xl">
            <div class="flex items-center gap-5 mb-4">
                <div class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center text-3xl shadow-inner border border-white/20">
                    <i class="ph-duotone ph-chats-circle text-white"></i>
                </div>
                <div>
                    <h3 class="text-3xl font-black tracking-tight mb-1">Layanan BK Digital</h3>
                    <div class="flex items-center gap-2 text-blue-100 text-xs font-bold uppercase tracking-widest opacity-80">
                        <span class="w-2 h-2 rounded-full bg-blue-300"></span>
                        Bimbingan & Konseling
                    </div>
                </div>
            </div>
            <p class="text-blue-50/90 text-sm md:text-base leading-relaxed pl-1">
                "Umpan balikmu adalah kompas kami. Berikan penilaian setelah sesi selesai untuk membantu kami melayani lebih baik."
            </p>
        </div>
        
        <div class="relative z-10 flex flex-col sm:flex-row gap-3 shrink-0 w-full md:w-auto">
            <a href="{{ route('student.bk.create') }}" class="group bg-white text-blue-600 px-8 py-4 rounded-[1.5rem] font-black shadow-xl shadow-blue-900/20 hover:bg-blue-50 transition-all flex items-center justify-center gap-3 active:scale-95 text-xs uppercase tracking-widest border-2 border-transparent">
                <i class="ph-bold ph-chats text-xl group-hover:scale-110 transition-transform"></i>
                Konsultasi Baru
            </a>
        </div>
    </div>

    <!-- 2. STATISTIK RINGKAS -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-md transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-slate-50 rounded-2xl text-slate-400 group-hover:bg-slate-100 group-hover:text-slate-600 transition-colors">
                    <i class="ph-bold ph-files text-xl"></i>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total</span>
            </div>
            <p class="text-3xl font-black text-slate-800">{{ $bkSessions->count() }}</p>
            <p class="text-xs text-slate-400 font-medium mt-1">Sesi Diajukan</p>
        </div>

        <div class="bg-white p-5 rounded-[2rem] border border-blue-100 shadow-sm hover:shadow-md transition-all group relative overflow-hidden">
            <div class="relative z-10 flex justify-between items-start mb-4">
                <div class="p-3 bg-blue-50 rounded-2xl text-blue-600 shadow-sm">
                    <i class="ph-bold ph-calendar-check text-xl"></i>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-blue-400">Jadwal</span>
            </div>
            <p class="text-3xl font-black text-slate-800 relative z-10">{{ $bkSessions->where('status', 'approved')->count() }}</p>
            <p class="text-xs text-slate-400 font-medium mt-1 relative z-10">Akan Datang</p>
        </div>

        <div class="bg-white p-5 rounded-[2rem] border border-amber-100 shadow-sm hover:shadow-md transition-all group relative overflow-hidden">
            <div class="relative z-10 flex justify-between items-start mb-4">
                <div class="p-3 bg-amber-50 rounded-2xl text-amber-600 shadow-sm">
                    <i class="ph-bold ph-hourglass text-xl"></i>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-amber-400">Proses</span>
            </div>
            <p class="text-3xl font-black text-slate-800 relative z-10">{{ $bkSessions->where('status', 'pending')->count() }}</p>
            <p class="text-xs text-slate-400 font-medium mt-1 relative z-10">Menunggu Respon</p>
        </div>

        <div class="bg-white p-5 rounded-[2rem] border border-emerald-100 shadow-sm hover:shadow-md transition-all group relative overflow-hidden">
            <div class="relative z-10 flex justify-between items-start mb-4">
                <div class="p-3 bg-emerald-50 rounded-2xl text-emerald-600 shadow-sm">
                    <i class="ph-bold ph-check-circle text-xl"></i>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-400">Selesai</span>
            </div>
            <p class="text-3xl font-black text-slate-800 relative z-10">{{ $bkSessions->where('status', 'finished')->count() }}</p>
            <p class="text-xs text-slate-400 font-medium mt-1 relative z-10">Telah Dinilai</p>
        </div>
    </div>

    <!-- 3. DAFTAR RIWAYAT DENGAN OPSI RATING -->
    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
            <h4 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                <i class="ph-duotone ph-list-dashes text-blue-500 text-xl"></i>
                Log Konsultasi & Penilaian
            </h4>
        </div>
        
        @if($bkSessions->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 text-[10px] uppercase font-black text-slate-400 tracking-widest">
                        <tr>
                            <th class="px-6 py-5">Topik & Konselor</th>
                            <th class="px-6 py-5">Status</th>
                            <th class="px-6 py-5">Umpan Balik Siswa</th>
                            <th class="px-6 py-5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm">
                        @foreach($bkSessions as $session)
                        <tr class="hover:bg-blue-50/30 transition-colors group">
                            <td class="px-6 py-5">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-lg shrink-0 border border-slate-100 shadow-sm bg-blue-50 text-blue-600">
                                        <i class="ph-duotone ph-user-focus"></i>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="font-bold text-slate-800 text-sm group-hover:text-blue-600 transition-colors">{{ $session->category->name }}</span>
                                        </div>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">
                                            Guru: {{ $session->teacher->name ?? 'Belum Ditentukan' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                @php
                                    $statusStyle = match($session->status) {
                                        'finished' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                        'approved' => 'bg-blue-100 text-blue-700 border-blue-200',
                                        'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                        'rejected' => 'bg-rose-100 text-rose-700 border-rose-200',
                                        default => 'bg-slate-100 text-slate-600'
                                    };
                                    $statusLabel = match($session->status) {
                                        'finished' => 'Selesai',
                                        'approved' => 'Dijadwalkan',
                                        'pending' => 'Menunggu',
                                        'rejected' => 'Ditolak',
                                        default => $session->status
                                    };
                                @endphp
                                <span class="px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-wide border {{ $statusStyle }} inline-flex items-center gap-1.5 shadow-sm">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                @if($session->status == 'finished')
                                    @if($session->rating)
                                        <div class="flex items-center gap-0.5 text-amber-400">
                                            @for($i=1; $i<=5; $i++)
                                                <i class="{{ $i <= $session->rating ? 'ph-fill' : 'ph-bold' }} ph-star text-sm"></i>
                                            @endfor
                                        </div>
                                        <p class="text-[10px] text-slate-400 mt-1 font-bold italic">Dinilai pada {{ Carbon::parse($session->feedback_at)->translatedFormat('d M') }}</p>
                                    @else
                                        <button @click="showRatingModal = true; selectedSessionId = {{ $session->id }}; selectedTopic = '{{ $session->category->name }}'" 
                                                class="flex items-center gap-2 px-3 py-1.5 bg-amber-50 text-amber-600 border border-amber-200 rounded-lg text-[10px] font-black uppercase hover:bg-amber-500 hover:text-white transition-all shadow-sm">
                                            <i class="ph-bold ph-star-half"></i> Beri Rating
                                        </button>
                                    @endif
                                @else
                                    <span class="text-[10px] text-slate-400 italic">Tersedia setelah sesi selesai</span>
                                @endif
                            </td>
                            <td class="px-6 py-5 text-center">
                                <a href="{{ route('student.bk.show', $session->id) }}" class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-white border-2 border-slate-100 text-slate-400 hover:bg-blue-600 hover:text-white transition-all">
                                    <i class="ph-bold ph-caret-right text-lg"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-20 px-4 bg-slate-50/50">
                <i class="ph-duotone ph-chats-teardrop text-5xl text-slate-300 mb-6 block"></i>
                <h4 class="text-xl font-black text-slate-800">Belum Ada Riwayat</h4>
                <p class="text-slate-500 text-sm mt-2 max-w-sm mx-auto mb-8 leading-relaxed">Jangan ragu untuk berkonsultasi mengenai masalah akademik maupun non-akademik. Kami siap membantu.</p>
                <a href="{{ route('student.bk.create') }}" class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-white border-2 border-slate-200 text-slate-700 font-bold rounded-2xl text-sm hover:border-blue-500 hover:text-blue-600 transition-all">
                    <i class="ph-bold ph-plus-circle"></i> Mulai Konsultasi
                </a>
            </div>
        @endif
    </div>

    <!-- MODAL RATING & FEEDBACK (ALPINE JS) -->
    <div x-show="showRatingModal" 
         x-transition.opacity 
         x-cloak 
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
         @keydown.escape.window="showRatingModal = false">
        
        <div class="bg-white rounded-[2.5rem] w-full max-w-lg p-8 shadow-2xl animate-in zoom-in duration-300" 
             @click.away="showRatingModal = false">
            
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4">
                    <i class="ph-fill ph-star"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-800 leading-tight">Kepuasan Layanan</h3>
                <p class="text-sm text-slate-500 mt-2 font-medium">Bantu kami meningkatkan kualitas layanan konseling <span class="text-blue-600 font-bold" x-text="selectedTopic"></span>.</p>
            </div>

            <!-- Form dikirim ke storeBkFeedback -->
            <form :action="'{{ url('student/portal/bk-feedback') }}/' + selectedSessionId" method="POST">
                @csrf
                
                <!-- Input Rating (Visual Stars Interaktif) -->
                <div class="flex justify-center gap-3 mb-8">
                    <template x-for="i in 5">
                        <button type="button" 
                                @click="ratingValue = i" 
                                @mouseover="hoverValue = i" 
                                @mouseleave="hoverValue = 0"
                                class="text-4xl transition-all transform hover:scale-125 focus:outline-none" 
                                :class="(hoverValue || ratingValue) >= i ? 'text-amber-400' : 'text-slate-200'">
                            <i :class="(hoverValue || ratingValue) >= i ? 'ph-fill ph-star' : 'ph-bold ph-star'"></i>
                        </button>
                    </template>
                    <input type="hidden" name="rating" :value="ratingValue">
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Ulasan Kamu (Opsional)</label>
                    <textarea name="feedback" 
                              rows="3" 
                              maxlength="500"
                              placeholder="Ceritakan pengalamanmu... Apakah Guru membantu? Apakah solusi yang diberikan bermanfaat?" 
                              class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 text-sm font-medium focus:bg-white focus:ring-2 focus:ring-blue-500 outline-none transition-all placeholder:italic"></textarea>
                    <p class="text-[10px] text-slate-400 mt-2 italic">* Ulasan ini akan menjadi bahan evaluasi internal Guru BK.</p>
                </div>

                <div class="flex gap-3">
                    <button type="button" 
                            @click="showRatingModal = false" 
                            class="flex-1 py-4 bg-slate-100 text-slate-500 font-black rounded-2xl hover:bg-slate-200 transition-all">
                        Tutup
                    </button>
                    <button type="submit" 
                            class="flex-1 py-4 bg-blue-600 text-white font-black rounded-2xl shadow-lg shadow-blue-500/30 hover:bg-blue-700 transition-all">
                        Kirim Penilaian
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>