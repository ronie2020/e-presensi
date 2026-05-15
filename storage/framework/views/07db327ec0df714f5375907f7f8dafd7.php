<?php $__env->startSection('content'); ?>
    <?php 
        \Carbon\Carbon::setLocale('id'); 
        $user = Auth::guard('student')->user();
        
        // Data Hari Ini
        $todayHabit = $todayHabit ?? \App\Models\StudentHabit::where('student_id', $user->id)
                        ->whereDate('report_date', now())
                        ->first();

        // Cek Misi Habit Selesai
        $isMissionComplete = false;
        if($todayHabit) {
            $isMissionComplete = $todayHabit->habit_1 && 
                                 $todayHabit->habit_2 && 
                                 ($todayHabit->prayer_subuh || $todayHabit->prayer_dzuhur || $todayHabit->prayer_ashar || $todayHabit->prayer_maghrib || $todayHabit->prayer_isya || $todayHabit->prayer_dhuha) &&
                                 $todayHabit->habit_3 && 
                                 $todayHabit->habit_4 && 
                                 $todayHabit->habit_5 && 
                                 $todayHabit->habit_6 && 
                                 $todayHabit->habit_7;   
        }

        // Default Fallback jika Controller belum mengirim data Level (Bisa dihapus jika murni dari Controller)
        $totalPoinKarakter = $totalPoints ?? 0;
        $levelName = $levelName ?? 'Pemula';
        $levelIcon = $levelIcon ?? 'ph-medal';
        $levelColor = $levelColor ?? 'text-slate-400';
    ?>

    
    <style>
        .fluent-card {
            box-shadow: 0 1.6px 3.6px 0 rgba(0, 0, 0, 0.132), 0 0.3px 0.9px 0 rgba(0, 0, 0, 0.108);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        .fluent-card:hover {
            box-shadow: 0 6.4px 14.4px 0 rgba(0, 0, 0, 0.132), 0 1.2px 3.6px 0 rgba(0, 0, 0, 0.108);
            transform: translateY(-2px);
        }
        [x-cloak] { display: none !important; }
    </style>

    
    <div x-data="{ activeTab: 'habits' }" class="max-w-6xl mx-auto px-4 sm:px-6 pb-20 pt-24 font-sans text-[#2A3B52] bg-[#f8fafc] min-h-screen">
        
        
        <div class="relative bg-gradient-to-br from-[#25D0FF] via-[#5295FF] to-[#FFC9B9] rounded-[2.5rem] p-8 md:p-12 overflow-hidden shadow-[0_10px_40px_-10px_rgba(37,208,255,0.4)] mb-8 text-[#2A3B52] border border-white/40 group">
            
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-64 h-64 bg-white/30 rounded-full blur-[80px] group-hover:opacity-70 transition-all duration-1000 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-white/20 rounded-full blur-[60px] pointer-events-none"></div>
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.03] mix-blend-overlay pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-8">
                <div class="text-center md:text-left flex-1">
                    <a href="<?php echo e(route('portal.show', Auth::guard('student')->id() ?? 0)); ?>" class="inline-flex items-center gap-2 text-[#2A3B52] hover:bg-white/60 transition-colors mb-4 text-[10px] font-bold uppercase tracking-[0.2em] bg-white/40 px-4 py-1.5 rounded-full border border-white/50 backdrop-blur-sm shadow-sm">
                        <i class="ph-bold ph-arrow-left"></i> Kembali ke profil
                    </a>
                    
                    <h1 class="text-3xl md:text-5xl font-black mb-3 leading-tight tracking-tight text-[#2A3B52]">
                        Halo, <span><?php echo e($user->name ?? 'Sahabat'); ?>!</span> 👋
                    </h1>
                    
                    <p class="text-[#2A3B52]/80 text-sm md:text-base max-w-xl leading-relaxed font-bold mb-6 flex items-center justify-center md:justify-start gap-2">
                        Level saat ini: 
                        <span class="bg-white/50 px-3 py-1 rounded-lg uppercase tracking-widest text-[10px] shadow-sm border border-white/50 flex items-center gap-1.5">
                            <i class="ph-fill <?php echo e($levelIcon); ?> <?php echo e($levelColor); ?> text-sm"></i> <?php echo e($levelName); ?>

                        </span>
                    </p>
                    
                    
                    <div class="mt-4 flex flex-wrap gap-3 justify-center md:justify-start">
                        <?php if(!$todayHabit || !$isMissionComplete): ?>
                            <a href="<?php echo e(route('student.habits.index')); ?>" class="px-5 py-3 bg-[#D83B01] hover:bg-[#b53201] text-white text-xs font-bold rounded-xl shadow-md transition-all flex items-center gap-2 active:scale-95">
                                <i class="ph-bold ph-pencil-simple text-lg"></i> Isi Jurnal Habit
                            </a>
                        <?php endif; ?>
                        
                        <button @click="activeTab = 'literasi'" class="px-5 py-3 bg-white/60 hover:bg-white border border-white/50 text-[#2A3B52] text-xs font-bold rounded-xl shadow-sm transition-all flex items-center gap-2 backdrop-blur-md active:scale-95">
                            <i class="ph-bold ph-book-open-text text-[#5295FF] text-lg"></i> Tulis Jurnal Buku
                        </button>
                    </div>
                </div>

                
                <div class="shrink-0 relative group">
                    <div class="absolute inset-0 bg-white/40 rounded-full blur-xl group-hover:blur-2xl transition-all"></div>
                    <div class="relative w-40 h-40 md:w-52 md:h-52 bg-white/30 rounded-full flex flex-col items-center justify-center backdrop-blur-md border border-white/50 shadow-sm transition-transform hover:scale-105">
                        <p class="text-[#2A3B52] text-[10px] md:text-xs font-bold uppercase tracking-widest mb-1">Total Poin Gabungan</p>
                        <h2 class="text-5xl md:text-6xl font-black text-[#2A3B52] tracking-tighter"><?php echo e(number_format($totalPoinKarakter)); ?></h2>
                    </div>
                    <?php if($totalPoinKarakter > 100): ?>
                    <div class="absolute -bottom-2 -right-2 bg-[#D83B01] text-white p-3 rounded-2xl shadow-sm border-2 border-white animate-bounce">
                        <i class="ph-fill ph-trophy text-2xl"></i>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        
        <div class="flex justify-center mb-8">
            <div class="bg-white p-1.5 rounded-2xl border border-slate-200 shadow-sm inline-flex">
                <button @click="activeTab = 'habits'" 
                        :class="activeTab === 'habits' ? 'bg-[#5295FF] text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50'"
                        class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2">
                    <i class="ph-bold ph-list-checks text-lg"></i> Jurnal Kebiasaan
                </button>
                <button @click="activeTab = 'literasi'" 
                        :class="activeTab === 'literasi' ? 'bg-[#5295FF] text-white shadow-sm' : 'text-slate-500 hover:bg-slate-50'"
                        class="px-6 py-2.5 rounded-xl text-sm font-bold transition-all flex items-center gap-2">
                    <i class="ph-bold ph-books text-lg"></i> Jurnal Literasi
                </button>
            </div>
        </div>

        
        
        
        <div x-show="activeTab === 'habits'" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="w-full">
            
            
            <?php echo $__env->make('students.portal.partials.tab-kebiasaan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?> 

        </div>

        
        <div x-show="activeTab === 'literasi'" x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="w-full">
            
            
            <?php echo $__env->make('students.portal.partials.tab-literasi-mandiri', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?> 

        </div>

    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            <?php if(session('success')): ?>
                Swal.fire({
                    icon: 'success', title: 'Hebat!', text: "<?php echo e(session('success')); ?>",
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 5000,
                    customClass: { popup: 'fluent-modal rounded-xl border border-[#B7DFB9] bg-white', title: 'text-[#107C10] font-bold' }
                });
            <?php endif; ?>
            <?php if(session('error')): ?>
                Swal.fire({
                    icon: 'error', title: 'Oops...', text: "<?php echo e(session('error')); ?>",
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 5000,
                    customClass: { popup: 'fluent-modal rounded-xl border border-[#F4C3C9] bg-white' }
                });
            <?php endif; ?>
        });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/habits/student_dashboard.blade.php ENDPATH**/ ?>