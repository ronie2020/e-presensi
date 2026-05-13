<div class="space-y-8 font-sans text-elevate-dark animate-in fade-in duration-500" 
     x-data="{ 
        showRatingModal: false, 
        selectedSessionId: null, 
        selectedTopic: '',
        ratingValue: 5,
        hoverValue: 0
     }">
    
    <!-- 1. HEADER VIBRANT (Elevate Style) -->
    <div class="bg-elevate-dark rounded-[2.5rem] p-8 md:p-10 text-white shadow-xl shadow-elevate-dark/10 relative overflow-hidden flex flex-col md:flex-row items-start md:items-center justify-between gap-8 border border-elevate-primary/30">
        <!-- Elevate Abstract Ornaments -->
        <div class="absolute top-0 right-0 opacity-[0.03] pointer-events-none">
            <i class="ph-fill ph-heart-beat text-[200px] transform translate-x-10 -translate-y-10 text-white"></i>
        </div>
        <div class="absolute top-[-20%] right-[-10%] w-[50%] h-[150%] bg-elevate-primary/40 rounded-full blur-[80px] pointer-events-none"></div>
        <div class="absolute bottom-[-50%] left-[-10%] w-[40%] h-[150%] bg-elevate-accent/20 rounded-full blur-[80px] pointer-events-none"></div>

        <div class="relative z-10 max-w-2xl">
            <div class="flex items-center gap-5 mb-4">
                <div class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-md flex items-center justify-center text-3xl shadow-inner border border-white/20">
                    <i class="ph-duotone ph-chats-circle text-elevate-accent"></i>
                </div>
                <div>
                    <h3 class="text-3xl font-black tracking-tight mb-1">Layanan BK Digital</h3>
                    <div class="flex items-center gap-2 text-elevate-soft text-xs font-bold uppercase tracking-widest opacity-90">
                        <span class="w-2 h-2 rounded-full bg-elevate-accent"></span>
                        Bimbingan & Konseling
                    </div>
                </div>
            </div>
            <p class="text-white/80 text-sm md:text-base leading-relaxed pl-1">
                "Umpan balikmu adalah kompas kami. Berikan penilaian setelah sesi selesai untuk membantu kami melayani lebih baik."
            </p>
        </div>
        
        <div class="relative z-10 flex flex-col sm:flex-row gap-3 shrink-0 w-full md:w-auto">
            <a href="<?php echo e(route('student.bk.create')); ?>" class="group bg-white text-elevate-dark px-8 py-4 rounded-[1.5rem] font-black shadow-xl shadow-white/10 hover:bg-elevate-soft transition-all flex items-center justify-center gap-3 active:scale-95 text-xs uppercase tracking-widest border border-transparent">
                <i class="ph-bold ph-chats text-xl text-elevate-primary group-hover:scale-110 transition-transform"></i>
                Konsultasi Baru
            </a>
        </div>
    </div>

    <!-- 2. STATISTIK RINGKAS -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm hover:border-elevate-accent/30 hover:shadow-md transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-slate-50 rounded-2xl text-slate-400 group-hover:bg-elevate-soft group-hover:text-elevate-primary transition-colors">
                    <i class="ph-bold ph-files text-xl"></i>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Total</span>
            </div>
            <p class="text-3xl font-black text-elevate-dark"><?php echo e($bkSessions->count()); ?></p>
            <p class="text-xs text-slate-400 font-medium mt-1">Sesi Diajukan</p>
        </div>

        <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm hover:border-elevate-primary/30 hover:shadow-md transition-all group relative overflow-hidden">
            <div class="relative z-10 flex justify-between items-start mb-4">
                <div class="p-3 bg-elevate-soft/50 rounded-2xl text-elevate-primary shadow-sm group-hover:bg-elevate-soft transition-colors">
                    <i class="ph-bold ph-calendar-check text-xl"></i>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-elevate-primary/60">Jadwal</span>
            </div>
            <p class="text-3xl font-black text-elevate-dark relative z-10"><?php echo e($bkSessions->where('status', 'approved')->count()); ?></p>
            <p class="text-xs text-slate-400 font-medium mt-1 relative z-10">Akan Datang</p>
        </div>

        <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm hover:border-elevate-peach/30 hover:shadow-md transition-all group relative overflow-hidden">
            <div class="relative z-10 flex justify-between items-start mb-4">
                <div class="p-3 bg-elevate-peach-light/20 rounded-2xl text-elevate-peach-dark shadow-sm group-hover:bg-elevate-peach-light/40 transition-colors">
                    <i class="ph-bold ph-hourglass text-xl"></i>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-elevate-peach">Proses</span>
            </div>
            <p class="text-3xl font-black text-elevate-dark relative z-10"><?php echo e($bkSessions->where('status', 'pending')->count()); ?></p>
            <p class="text-xs text-slate-400 font-medium mt-1 relative z-10">Menunggu</p>
        </div>

        <!-- Semantic Success Color -->
        <div class="bg-white p-5 rounded-[2rem] border border-slate-100 shadow-sm hover:border-emerald-200 hover:shadow-md transition-all group relative overflow-hidden">
            <div class="relative z-10 flex justify-between items-start mb-4">
                <div class="p-3 bg-emerald-50 rounded-2xl text-emerald-600 shadow-sm group-hover:bg-emerald-100 transition-colors">
                    <i class="ph-bold ph-check-circle text-xl"></i>
                </div>
                <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-400">Selesai</span>
            </div>
            <p class="text-3xl font-black text-elevate-dark relative z-10"><?php echo e($bkSessions->where('status', 'finished')->count()); ?></p>
            <p class="text-xs text-slate-400 font-medium mt-1 relative z-10">Tuntas</p>
        </div>
    </div>

    <!-- 3. DAFTAR RIWAYAT DENGAN OPSI RATING -->
    <div class="bg-white rounded-[2.5rem] shadow-xl shadow-elevate-dark/5 border border-slate-100 overflow-hidden">
        <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
            <h4 class="font-bold text-elevate-dark text-lg flex items-center gap-2">
                <i class="ph-duotone ph-list-dashes text-elevate-primary text-xl"></i>
                Log Konsultasi & Penilaian
            </h4>
        </div>
        
        <?php if($bkSessions->count() > 0): ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50/50 text-[10px] uppercase font-black text-slate-400 tracking-widest border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-5">Topik & Konselor</th>
                            <th class="px-6 py-5">Status</th>
                            <th class="px-6 py-5">Umpan Balik Siswa</th>
                            <th class="px-6 py-5 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 text-sm">
                        <?php $__currentLoopData = $bkSessions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-elevate-soft/30 transition-colors group">
                            <td class="px-6 py-5">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-2xl flex items-center justify-center text-lg shrink-0 border border-elevate-accent/20 shadow-sm bg-elevate-soft/50 text-elevate-primary">
                                        <i class="ph-duotone ph-user-focus"></i>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="font-bold text-elevate-dark text-sm group-hover:text-elevate-primary transition-colors"><?php echo e($session->category->name); ?></span>
                                        </div>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tight">
                                            Guru: <?php echo e($session->teacher->name ?? 'Belum Ditentukan'); ?>

                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5">
                                <?php
                                    $statusStyle = match($session->status) {
                                        'finished' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                        'approved' => 'bg-elevate-soft text-elevate-primary border-elevate-accent/30',
                                        'ongoing' => 'bg-elevate-peach-light/30 text-elevate-peach-dark border-elevate-peach/30',
                                        'pending' => 'bg-slate-50 text-slate-600 border-slate-200',
                                        'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                                        default => 'bg-slate-50 text-slate-600 border-slate-200'
                                    };
                                    $statusLabel = match($session->status) {
                                        'finished' => 'Selesai',
                                        'approved' => 'Dijadwalkan',
                                        'ongoing' => 'Chatting',
                                        'pending' => 'Menunggu',
                                        'rejected' => 'Ditolak',
                                        default => $session->status
                                    };
                                ?>
                                <span class="px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-wide border <?php echo e($statusStyle); ?> inline-flex items-center gap-1.5 shadow-sm">
                                    <?php echo e($statusLabel); ?>

                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <?php if($session->status == 'finished'): ?>
                                    <?php if($session->rating): ?>
                                        <div class="flex items-center gap-0.5 text-elevate-peach">
                                            <?php for($i=1; $i<=5; $i++): ?>
                                                <i class="<?php echo e($i <= $session->rating ? 'ph-fill' : 'ph-bold'); ?> ph-star text-sm"></i>
                                            <?php endfor; ?>
                                        </div>
                                        <p class="text-[10px] text-slate-400 mt-1 font-bold italic">Dinilai pada <?php echo e(\Carbon\Carbon::parse($session->feedback_at)->translatedFormat('d M')); ?></p>
                                    <?php else: ?>
                                        <button @click="showRatingModal = true; selectedSessionId = <?php echo e($session->id); ?>; selectedTopic = '<?php echo e($session->category->name); ?>'" 
                                                class="flex items-center gap-2 px-3 py-1.5 bg-elevate-peach-light/20 text-elevate-peach-dark border border-elevate-peach/30 rounded-lg text-[10px] font-black uppercase hover:bg-elevate-peach hover:text-white transition-all shadow-sm">
                                            <i class="ph-bold ph-star-half"></i> Beri Rating
                                        </button>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="text-[10px] text-slate-400 italic">Tersedia setelah tuntas</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <a href="<?php echo e(route('student.bk.show', $session->id)); ?>" class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-white border-2 border-slate-100 text-slate-400 hover:bg-elevate-primary hover:text-white hover:border-elevate-primary transition-all">
                                    <i class="ph-bold ph-caret-right text-lg"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-20 px-4 bg-slate-50/30">
                <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300 border-4 border-slate-50 shadow-sm">
                    <i class="ph-duotone ph-chats-teardrop text-5xl"></i>
                </div>
                <h4 class="text-xl font-black text-elevate-dark">Belum Ada Riwayat</h4>
                <p class="text-slate-500 text-sm mt-2 max-w-sm mx-auto mb-8 leading-relaxed">Jangan ragu untuk berkonsultasi mengenai masalah akademik maupun non-akademik. Kami siap membantu.</p>
                <a href="<?php echo e(route('student.bk.create')); ?>" class="inline-flex items-center justify-center gap-2 px-8 py-3 bg-white border-2 border-slate-200 text-slate-600 font-bold rounded-2xl text-sm hover:border-elevate-primary hover:text-elevate-primary hover:shadow-lg transition-all">
                    <i class="ph-bold ph-plus-circle"></i> Mulai Konsultasi
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- MODAL RATING & FEEDBACK (ALPINE JS) -->
    <div x-show="showRatingModal" 
         x-transition.opacity 
         x-cloak 
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-elevate-dark/60 backdrop-blur-sm"
         @keydown.escape.window="showRatingModal = false">
        
        <div class="bg-white rounded-[2.5rem] w-full max-w-lg p-8 shadow-2xl animate-in zoom-in duration-300 border border-slate-100" 
             @click.away="showRatingModal = false">
            
            <div class="text-center mb-8">
                <div class="w-16 h-16 bg-elevate-peach-light/30 text-elevate-peach-dark rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4 border border-elevate-peach/20">
                    <i class="ph-fill ph-star"></i>
                </div>
                <h3 class="text-2xl font-black text-elevate-dark leading-tight">Kepuasan Layanan</h3>
                <p class="text-sm text-slate-500 mt-2 font-medium">Bantu kami meningkatkan layanan untuk topik <span class="text-elevate-primary font-bold" x-text="selectedTopic"></span>.</p>
            </div>

            <form :action="'<?php echo e(url('student/portal/bk-feedback')); ?>/' + selectedSessionId" method="POST">
                <?php echo csrf_field(); ?>
                
                <div class="flex justify-center gap-3 mb-8">
                    <template x-for="i in 5">
                        <button type="button" 
                                @click="ratingValue = i" 
                                @mouseover="hoverValue = i" 
                                @mouseleave="hoverValue = 0"
                                class="text-4xl transition-all transform hover:scale-125 focus:outline-none" 
                                :class="(hoverValue || ratingValue) >= i ? 'text-elevate-peach' : 'text-slate-200'">
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
                              placeholder="Ceritakan pengalamanmu..." 
                              class="w-full bg-slate-50 border border-slate-200 rounded-2xl p-4 text-sm font-medium focus:bg-white focus:ring-2 focus:ring-elevate-accent outline-none transition-all placeholder:italic text-elevate-dark"></textarea>
                </div>

                <div class="flex gap-3">
                    <button type="button" 
                            @click="showRatingModal = false" 
                            class="flex-1 py-4 bg-slate-100 text-slate-500 font-black rounded-2xl hover:bg-slate-200 transition-all">
                        Tutup
                    </button>
                    <button type="submit" 
                            class="flex-1 py-4 bg-elevate-primary text-white font-black rounded-2xl shadow-lg shadow-elevate-primary/30 hover:bg-elevate-dark transition-all">
                        Kirim Penilaian
                    </button>
                </div>
            </form>
        </div>
    </div>
</div><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/students/portal/partials/tab-bk.blade.php ENDPATH**/ ?>