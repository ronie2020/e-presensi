<?php $__env->startSection('content'); ?>
<?php
    $currentTime = \Carbon\Carbon::now();
    $isOpen = isset($announcementDate) ? $currentTime->greaterThanOrEqualTo($announcementDate) : false;
    
    // Debug/Fallback
    if(isset($customError) || session('error')) {
        $isOpen = true; 
    }
?>


<style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    
    .countdown-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
</style>

<div class="min-h-screen w-full flex flex-col items-center justify-center relative overflow-hidden bg-elevate-surface font-sans py-12 px-4 sm:px-6">
    
    
    <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>
    <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-elevate-peach-light/30 rounded-full blur-3xl pointer-events-none -z-10"></div>

    <div class="w-full max-w-lg z-10 animate-enter">
        
        
        <div class="text-center mb-12 animate-enter">
            <div class="inline-flex items-center gap-2 py-1.5 px-4 rounded-full bg-white border border-blue-100 mb-6 shadow-sm">
                <span class="relative flex h-2.5 w-2.5">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-blue-600"></span>
                </span>
                <span class="text-xs font-black text-slate-600 tracking-wider uppercase">Portal Pengumuman</span>
            </div>
            
            <h1 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tight mb-4">
                Hasil Seleksi <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">PPDB <?php echo e(date('Y')); ?></span>
            </h1>
        </div>

        <div class="bg-white rounded-[2.5rem] overflow-hidden relative shadow-2xl shadow-slate-200/50 border border-slate-100 animate-enter" style="animation-delay: 100ms">
            
            <div class="p-8 md:p-16">
                
                
                <div id="countdown-wrapper" class="<?php echo e($isOpen ? 'hidden' : 'block'); ?>">
                    <div class="text-center max-w-3xl mx-auto">
                        <div class="mb-10">
                            <h2 class="text-2xl font-black text-slate-800 mb-2">Pengumuman Segera Dibuka</h2>
                            <?php if(isset($announcementDate)): ?>
                                <p class="text-slate-500 font-medium mb-6">Hasil seleksi dapat diakses secara online pada:</p>
                                <div class="inline-block px-6 py-3 rounded-2xl bg-blue-50 border border-blue-100">
                                    <p class="text-xl text-blue-600 font-black font-mono"><?php echo e($announcementDate->translatedFormat('l, d F Y - H:i')); ?> WIB</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                            <?php $__currentLoopData = ['Hari' => 'days', 'Jam' => 'hours', 'Menit' => 'minutes', 'Detik' => 'seconds']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $id): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="countdown-box rounded-2xl p-6 flex flex-col items-center justify-center aspect-square md:aspect-auto group hover:border-blue-200 transition-colors">
                                    <span id="<?php echo e($id); ?>" class="text-4xl md:text-5xl font-black text-slate-800 font-mono tracking-tighter mb-2 group-hover:text-blue-600 transition-colors">00</span>
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest"><?php echo e($label); ?></span>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>

                
                <div id="main-content" class="<?php echo e($isOpen ? 'block animate-enter' : 'hidden'); ?>">
                    <div class="max-w-xl mx-auto">
                        <div class="text-center mb-10">
                            <h2 class="text-2xl font-black text-slate-800 mb-2">Cek Status Kelulusan</h2>
                            <p class="text-slate-500 font-medium text-sm">Masukkan Nomor Pendaftaran (Format: REG-xxxx) atau NISN Siswa.</p>
                        </div>

                        <form action="<?php echo e(route('ppdb.search')); ?>" method="POST" class="relative group">
                            <?php echo csrf_field(); ?>
                            <div class="relative mb-6">
                                <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                                    <i class="ph-duotone ph-magnifying-glass text-3xl text-slate-400 group-focus-within:text-blue-600 transition-colors"></i>
                                </div>
                                <input type="text" name="search" class="search-input block w-full pl-16 pr-6 py-5 text-slate-800 text-lg font-bold rounded-2xl placeholder:text-slate-400 placeholder:font-normal outline-none" placeholder="Cari data siswa..." required autocomplete="off">
                            </div>
                            
                            <button type="submit" class="w-full bg-slate-900 hover:bg-blue-600 text-white font-bold py-5 rounded-2xl shadow-lg shadow-slate-900/20 hover:shadow-blue-600/30 transform hover:-translate-y-1 transition-all flex items-center justify-center gap-3 text-lg">
                                <span>Lihat Hasil Seleksi</span>
                                <i class="ph-bold ph-arrow-right"></i>
                            </button>
                        </form>

                        <?php if(session('error') || isset($customError)): ?>
                            <div class="mt-8 p-5 bg-rose-50 border border-rose-100 rounded-2xl flex items-start gap-4">
                                <div class="bg-rose-100 p-2 rounded-xl text-rose-600 mt-0.5"><i class="ph-fill ph-warning-circle text-xl"></i></div>
                                <div>
                                    <h4 class="font-bold text-rose-700 text-sm mb-1">Data Tidak Ditemukan</h4>
                                    <p class="text-xs text-rose-600/80 font-medium leading-relaxed"><?php echo e(session('error') ?? $customError); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
            
            <div class="bg-slate-50 border-t border-slate-100 p-6 text-center">
                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">&copy; <?php echo e(date('Y')); ?> Panitia PPDB SMPN 3 Lakbok</p>
            </div>
        </div>
    </div>
</div>

<?php if(!$isOpen && isset($announcementDate)): ?>
<script>
    const targetDateStr = "<?php echo e($announcementDate->format('Y-m-d H:i:s')); ?>";
    const countDownDate = new Date(targetDateStr).getTime();

    const x = setInterval(function() {
        const now = new Date().getTime();
        const distance = countDownDate - now;

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        if(document.getElementById("days")) {
            document.getElementById("days").innerText = days < 10 ? "0" + days : days;
            document.getElementById("hours").innerText = hours < 10 ? "0" + hours : hours;
            document.getElementById("minutes").innerText = minutes < 10 ? "0" + minutes : minutes;
            document.getElementById("seconds").innerText = seconds < 10 ? "0" + seconds : seconds;
        }

        if (distance < 0) {
            clearInterval(x);
            location.reload();
        }
    }, 1000);
</script>
<?php endif; ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/ppdb/check.blade.php ENDPATH**/ ?>