<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-elevate-dark leading-tight">
            <?php echo e(__('Jadwal Mengajar')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes wave { 0% { transform: rotate(0deg); } 10% { transform: rotate(14deg); } 20% { transform: rotate(-8deg); } 30% { transform: rotate(14deg); } 40% { transform: rotate(-4deg); } 50% { transform: rotate(10deg); } 60% { transform: rotate(0deg); } 100% { transform: rotate(0deg); } }
        .animate-wave { animation: wave 2.5s infinite; transform-origin: 70% 70%; }
    </style>

    <div class="py-8 sm:py-10 font-sans text-elevate-dark bg-elevate-surface min-h-screen relative overflow-hidden pb-20">
        
        
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            
            <?php if(session('success')): ?>
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-5 bg-[#DFF6DD] border border-[#B7DFB9] text-[#107C10] rounded-[1.5rem] flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <i class="ph-fill ph-check-circle text-xl"></i>
                        <span class="font-bold text-sm"><?php echo e(session('success')); ?></span>
                    </div>
                    <button @click="show = false" class="hover:bg-[#B7DFB9]/50 p-2 rounded-full transition-colors"><i class="ph-bold ph-x"></i></button>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-5 bg-[#FDE7E9] border border-[#F4C3C9] text-[#D13438] rounded-[1.5rem] flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <i class="ph-fill ph-warning-circle text-xl"></i>
                        <span class="font-bold text-sm"><?php echo e(session('error')); ?></span>
                    </div>
                    <button @click="show = false" class="hover:bg-[#F4C3C9]/50 p-2 rounded-full transition-colors"><i class="ph-bold ph-x"></i></button>
                </div>
            <?php endif; ?>

            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
                
                
                <div class="relative rounded-[2rem] bg-gradient-to-r from-elevate-accent via-elevate-peach-light to-elevate-peach p-8 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden border border-white/60 animate-enter group">
                    <div class="absolute -top-10 -left-10 w-40 h-40 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-xl"></div>
                    <div class="absolute -bottom-10 -right-10 w-40 h-40 bg-white/30 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="absolute right-0 top-0 opacity-10 transform translate-x-8 -translate-y-8 group-hover:scale-110 transition-transform duration-500 text-white pointer-events-none">
                        <i class="ph-fill ph-calendar-check text-[10rem]"></i>
                    </div>
                    
                    <div class="relative z-10 h-full flex flex-col justify-between">
                        <a href="<?php echo e(route('dashboard')); ?>" class="group/btn bg-white/40 hover:bg-white text-elevate-dark px-4 py-2.5 rounded-xl font-bold text-xs backdrop-blur-sm border border-white/50 transition-all flex items-center gap-2 shadow-sm w-fit mb-4 active:scale-95">
                            <i class="ph-bold ph-arrow-left group-hover/btn:-translate-x-1 transition-transform"></i>
                            <span>Dashboard</span>
                        </a>
                        <div>
                            <p class="text-elevate-dark/80 font-black text-sm mb-1 flex items-center gap-2 uppercase tracking-wider"><i class="ph-bold ph-calendar-blank"></i> Hari Ini</p>
                            <h3 class="text-2xl sm:text-3xl font-black tracking-tight leading-tight"><?php echo e(\Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y')); ?></h3>
                        </div>
                        <div class="mt-6">
                            <span class="bg-white/60 backdrop-blur-md px-4 py-2.5 rounded-xl text-sm font-bold border border-white/50 shadow-sm inline-flex items-center gap-2 text-elevate-dark">
                                <span class="bg-[#107C10] w-2.5 h-2.5 rounded-full animate-pulse"></span>
                                <?php echo e($schedules->count()); ?> Sesi Pelajaran
                            </span>
                        </div>
                    </div>
                </div>
                
                
                <div class="lg:col-span-2 bg-white rounded-[2rem] p-8 border border-slate-100 shadow-xl shadow-slate-200/40 flex items-center justify-between relative overflow-hidden animate-enter" style="animation-delay: 100ms">
                    <div class="relative z-10 max-w-lg">
                        <h3 class="font-black text-elevate-dark text-3xl mb-3 flex items-center gap-2">
                            Halo, <?php echo e(Auth::user()->name); ?>! <span class="animate-wave origin-bottom-right inline-block">👋</span>
                        </h3>
                        <p class="text-elevate-dark/70 leading-relaxed font-semibold text-sm">
                            Sudah siap mengajar hari ini? Pastikan jurnal terisi dan absensi siswa tercatat dengan baik.
                        </p>
                    </div>
                    
                    <div class="hidden md:block relative z-10">
                        <div class="w-24 h-24 bg-elevate-peach-light rounded-[2rem] flex items-center justify-center text-elevate-peach-dark shadow-sm border border-elevate-peach rotate-3 hover:rotate-6 transition-transform">
                            <i class="ph-duotone ph-chalkboard-teacher text-5xl"></i>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="flex items-center justify-between mb-6 animate-enter" style="animation-delay: 200ms">
                <h3 class="font-black text-elevate-dark text-xl flex items-center gap-3">
                    <div class="w-2 h-6 bg-elevate-accent rounded-full"></div>
                    Agenda <?php echo e(\Carbon\Carbon::now()->locale('id')->translatedFormat('l')); ?>

                </h3>
            </div>

            <?php if($schedules->count() > 0): ?>
                <div class="grid grid-cols-1 gap-5">
                    <?php $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $session = $schedule->todaySession;
                            $startJP = $schedule->clean_start_time;
                            $endJP   = $schedule->clean_end_time;

                            if (!$session) {
                                $status = 'waiting'; 
                                $borderClass = 'border-l-[6px] border-l-elevate-accent';
                                $bgIcon = 'bg-elevate-soft text-elevate-primary border-slate-200';
                                $btnClass = 'bg-elevate-dark hover:bg-elevate-primary text-white shadow-elevate-dark/30'; 
                            } elseif ($session->status == 'open') {
                                $status = 'ongoing';
                                $borderClass = 'border-l-[6px] border-l-[#107C10] ring-2 ring-[#107C10]/10';
                                $bgIcon = 'bg-[#DFF6DD] text-[#107C10] border-[#B7DFB9]';
                                $btnClass = 'bg-[#107C10] hover:bg-[#0c5c0c] text-white shadow-[#107C10]/30'; 
                            } else {
                                $status = 'done';
                                $borderClass = 'border-l-[6px] border-l-slate-300 bg-slate-50/50';
                                $bgIcon = 'bg-white text-slate-400 border-slate-200';
                            }
                        ?>

                        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/40 p-6 <?php echo e($borderClass); ?> flex flex-col md:flex-row justify-between items-center gap-6 group relative overflow-hidden animate-enter" style="animation-delay: <?php echo e(($index + 3) * 100); ?>ms">
                            
                            <div class="flex items-center gap-5 w-full md:w-auto z-10">
                                <div class="flex flex-col items-center justify-center w-20 h-20 rounded-2xl <?php echo e($bgIcon); ?> shrink-0 shadow-sm border transition-colors">
                                    <span class="text-[10px] font-bold uppercase tracking-wider opacity-70">Jam Ke</span>
                                    <span class="text-3xl font-black leading-none"><?php echo e($startJP); ?></span>
                                    <?php if($startJP != $endJP): ?>
                                        <span class="text-xs font-bold -mt-1 opacity-70">- <?php echo e($endJP); ?></span>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <h4 class="font-black text-elevate-dark text-xl md:text-2xl group-hover:text-elevate-primary transition-colors"><?php echo e($schedule->subject->name); ?></h4>
                                    <div class="flex flex-wrap gap-2 mt-2">
                                        <span class="flex items-center gap-1.5 text-xs font-bold text-slate-500 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200">
                                            <i class="ph-bold ph-users-three"></i> Kelas <?php echo e($schedule->schoolClass->name); ?>

                                        </span>
                                        <span class="flex items-center gap-1.5 text-xs font-bold text-slate-500 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200">
                                            <i class="ph-bold ph-clock"></i> JP <?php echo e($startJP); ?> - <?php echo e($endJP); ?>

                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="w-full md:w-auto z-10">
                                <?php if($status == 'waiting'): ?>
                                    <form action="<?php echo e(route('teaching.start', $schedule->id)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="w-full md:w-auto px-8 py-3.5 <?php echo e($btnClass); ?> font-bold rounded-2xl shadow-lg transition transform flex items-center justify-center gap-2 active:scale-95 text-sm border border-transparent">
                                            <i class="ph-bold ph-play-circle text-xl"></i> Mulai Mengajar
                                        </button>
                                    </form>
                                <?php elseif($status == 'ongoing'): ?>
                                    <div class="flex flex-col md:items-end gap-3">
                                        <div class="flex items-center justify-center md:justify-end gap-2 text-[#107C10] font-black text-[10px] uppercase tracking-widest bg-[#DFF6DD] px-3 py-1.5 rounded-full border border-[#B7DFB9]">
                                            <span class="w-2 h-2 rounded-full bg-[#107C10] animate-pulse"></span> Sedang Berlangsung
                                        </div>
                                        <a href="<?php echo e(route('teaching.show', $session->id)); ?>" class="w-full md:w-auto px-8 py-3.5 <?php echo e($btnClass); ?> font-bold rounded-2xl shadow-lg transition transform flex items-center justify-center gap-2 active:scale-95 text-sm border border-transparent">
                                            Buka Kelas <i class="ph-bold ph-arrow-right"></i>
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <div class="flex items-center gap-3">
                                        <span class="px-5 py-3.5 bg-slate-100 text-slate-400 font-bold text-sm rounded-2xl flex items-center gap-2 border border-slate-200 cursor-not-allowed">
                                            <i class="ph-fill ph-check-circle"></i> Selesai
                                        </span>
                                        <a href="<?php echo e(route('teaching.show', $session->id)); ?>" class="p-3 bg-white border border-slate-200 rounded-2xl text-slate-400 hover:text-elevate-primary hover:border-elevate-accent/50 hover:bg-elevate-soft transition-all shadow-sm active:scale-95" title="Lihat Detail">
                                            <i class="ph-bold ph-eye text-xl"></i>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="text-center py-24 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200 animate-enter delay-200">
                    <div class="w-24 h-24 bg-elevate-soft rounded-full flex items-center justify-center mx-auto mb-6 text-elevate-primary border border-slate-100">
                        <i class="ph-duotone ph-coffee text-5xl"></i>
                    </div>
                    <h3 class="text-elevate-dark font-black text-xl mb-2">Tidak Ada Jadwal Hari Ini</h3>
                    <p class="text-slate-500 max-w-xs mx-auto text-sm font-medium leading-relaxed">
                        Hari ini (<?php echo e(\Carbon\Carbon::now()->locale('id')->translatedFormat('l')); ?>) Anda tidak memiliki jadwal kelas.
                    </p>
                </div>
            <?php endif; ?>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/teaching/index.blade.php ENDPATH**/ ?>