<?php $__env->startSection('content'); ?>

<style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

    .status-badge-accepted {
        background: radial-gradient(circle at top right, #34d399, #059669);
        box-shadow: 0 20px 40px -10px rgba(16, 185, 129, 0.4);
    }
    
    .status-badge-rejected {
        background: radial-gradient(circle at top right, #f472b6, #e11d48);
        box-shadow: 0 20px 40px -10px rgba(225, 29, 72, 0.4);
    }
    
    .status-badge-pending {
        background: radial-gradient(circle at top right, #fbbf24, #d97706);
        box-shadow: 0 20px 40px -10px rgba(217, 119, 6, 0.4);
    }
</style>

<div class="min-h-screen w-full flex flex-col items-center justify-center relative overflow-hidden bg-slate-50 font-sans py-10">
    
    
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute top-[-10%] right-[-5%] w-[600px] h-[600px] bg-emerald-100/50 rounded-full blur-[100px] opacity-60 mix-blend-multiply"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[500px] h-[500px] bg-blue-100/50 rounded-full blur-[100px] opacity-60 mix-blend-multiply"></div>
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-30"></div>
    </div>

    <div class="relative z-10 w-full max-w-5xl px-4">
        
        <div class="mb-8 flex justify-between items-center animate-enter">
            <a href="<?php echo e(route('ppdb.check')); ?>" class="group inline-flex items-center gap-2 text-slate-500 hover:text-blue-600 transition font-bold text-sm bg-white px-5 py-2.5 rounded-2xl border border-slate-200 hover:border-blue-200 hover:shadow-sm">
                <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali
            </a>
            <span class="text-xs font-mono text-slate-400 font-bold bg-white px-3 py-1 rounded-lg border border-slate-200">Session: <?php echo e(date('Ymd-Hi')); ?></span>
        </div>

        <div class="bg-white rounded-[2.5rem] overflow-hidden relative shadow-2xl shadow-slate-200/50 border border-slate-100 animate-enter" style="animation-delay: 100ms">
            <div class="p-8 md:p-12">
                <div class="flex flex-col lg:flex-row gap-10 items-start">
                    
                    
                    <div class="w-full lg:w-1/3 flex flex-col items-center text-center">
                        <div class="relative w-56 h-56 mb-8 group">
                            
                            <?php
                                $glowClass = match($registrant->status) {
                                    'accepted' => 'bg-emerald-500',
                                    'rejected' => 'bg-rose-500',
                                    default => 'bg-amber-500',
                                };
                                $borderClass = match($registrant->status) {
                                    'accepted' => 'border-emerald-100 text-emerald-600',
                                    'rejected' => 'border-rose-100 text-rose-600',
                                    default => 'border-amber-100 text-amber-600',
                                };
                            ?>
                            <div class="absolute inset-0 <?php echo e($glowClass); ?> rounded-full blur-[60px] opacity-10 group-hover:opacity-20 transition-opacity duration-700"></div>
                            
                            <div class="relative w-full h-full rounded-full p-2 bg-white border border-slate-100 shadow-xl">
                                <div class="w-full h-full rounded-full bg-slate-50 overflow-hidden relative flex items-center justify-center border border-slate-100">
                                    <?php if($registrant->file_photo): ?>
                                        <img src="<?php echo e(asset('storage/' . $registrant->file_photo)); ?>" class="w-full h-full object-cover transform transition hover:scale-110 duration-700">
                                    <?php else: ?>
                                        <span class="text-7xl font-black text-slate-300 select-none"><?php echo e(substr($registrant->full_name, 0, 1)); ?></span>
                                    <?php endif; ?>
                                </div>
                                
                                
                                <div class="absolute bottom-2 right-2 w-14 h-14 rounded-full <?php echo e($glowClass); ?> flex items-center justify-center border-4 border-white text-white shadow-lg">
                                    <?php if($registrant->status === 'accepted'): ?>
                                        <i class="ph-bold ph-check text-2xl"></i>
                                    <?php elseif($registrant->status === 'rejected'): ?>
                                        <i class="ph-bold ph-x text-2xl"></i>
                                    <?php else: ?>
                                        <i class="ph-bold ph-hourglass text-2xl animate-spin-slow"></i>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <h3 class="text-2xl font-black text-slate-800 leading-tight mb-2"><?php echo e($registrant->full_name); ?></h3>
                        <div class="flex flex-wrap justify-center gap-2">
                            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-lg bg-slate-50 border border-slate-200">
                                <span class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">No. Reg</span>
                                <span class="text-blue-600 font-mono font-bold text-sm"><?php echo e($registrant->registration_number); ?></span>
                            </div>
                            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-lg bg-slate-50 border border-slate-200">
                                <span class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">NISN</span>
                                <span class="text-slate-700 font-mono font-bold text-sm"><?php echo e($registrant->nisn); ?></span>
                            </div>
                        </div>
                    </div>

                    
                    <div class="w-full lg:w-2/3">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                            <div class="bg-slate-50 p-5 rounded-3xl border border-slate-100 flex flex-col justify-center">
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Jalur Pendaftaran</p>
                                <div class="flex items-center gap-2">
                                    <i class="ph-duotone ph-path text-blue-500 text-lg"></i>
                                    <p class="font-bold text-slate-800 text-lg capitalize"><?php echo e(str_replace('_', ' ', $registrant->track)); ?></p>
                                </div>
                            </div>
                            <div class="bg-slate-50 p-5 rounded-3xl border border-slate-100 flex flex-col justify-center">
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Asal Sekolah</p>
                                <div class="flex items-center gap-2">
                                    <i class="ph-duotone ph-buildings text-purple-500 text-lg"></i>
                                    <p class="font-bold text-slate-800 text-lg truncate"><?php echo e($registrant->school_origin); ?></p>
                                </div>
                            </div>
                        </div>

                        
                        <?php if($registrant->status === 'accepted'): ?>
                            <div class="status-badge-accepted rounded-[2rem] p-8 md:p-10 text-center text-white relative overflow-hidden mb-6 group">
                                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/confetti.png')] opacity-20 mix-blend-overlay"></div>
                                <div class="relative z-10">
                                    <h2 class="text-xs font-bold text-emerald-100 mb-3 uppercase tracking-[0.2em] border-b border-white/20 inline-block pb-1">Keputusan Panitia</h2>
                                    <h1 class="text-5xl md:text-6xl font-black tracking-tight mb-4 drop-shadow-sm">DITERIMA</h1>
                                    <p class="text-emerald-50 text-lg font-medium leading-relaxed max-w-lg mx-auto">
                                        Selamat! Anda dinyatakan lulus seleksi penerimaan siswa baru. Silakan cetak bukti kelulusan di bawah ini.
                                    </p>
                                </div>
                            </div>

                            <a href="<?php echo e(route('ppdb.print.letter', $registrant->id)); ?>" target="_blank" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-5 rounded-2xl transition-all flex items-center justify-center gap-3 shadow-xl shadow-slate-900/10 hover:-translate-y-1 group">
                                <i class="ph-bold ph-printer text-xl"></i>
                                <span class="text-lg">Cetak Surat Kelulusan</span>
                            </a>

                        <?php elseif($registrant->status === 'rejected'): ?>
                            <div class="status-badge-rejected rounded-[2rem] p-8 md:p-10 text-center text-white relative overflow-hidden mb-6">
                                <div class="relative z-10">
                                    <h2 class="text-xs font-bold text-rose-100 mb-3 uppercase tracking-[0.2em] border-b border-white/20 inline-block pb-1">Keputusan Panitia</h2>
                                    <h1 class="text-4xl md:text-5xl font-black tracking-tight mb-4 drop-shadow-sm leading-tight">MOHON MAAF</h1>
                                    <p class="text-rose-50 text-lg font-medium leading-relaxed max-w-lg mx-auto">
                                        Anda belum lolos seleksi penerimaan siswa baru tahun ini. Tetap semangat dan jangan putus asa.
                                    </p>
                                </div>
                            </div>
                        
                        <?php else: ?>
                            <div class="status-badge-pending rounded-[2rem] p-8 md:p-10 text-center text-white relative overflow-hidden mb-6">
                                <div class="relative z-10">
                                    <h2 class="text-xs font-bold text-amber-100 mb-3 uppercase tracking-[0.2em] border-b border-white/20 inline-block pb-1">Status Data</h2>
                                    <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-4 drop-shadow-sm">VERIFIKASI</h1>
                                    <p class="text-amber-50 text-lg font-medium leading-relaxed max-w-lg mx-auto">
                                        Data pendaftaran Anda sedang dalam proses pemeriksaan oleh panitia. Mohon cek kembali secara berkala.
                                    </p>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/ppdb/status.blade.php ENDPATH**/ ?>