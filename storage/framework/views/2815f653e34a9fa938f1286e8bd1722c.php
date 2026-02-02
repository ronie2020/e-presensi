<?php $__env->startSection('content'); ?>
<?php
    // Logika Waktu (Sama seperti sebelumnya, tapi disesuaikan variabelnya)
    $currentTime = \Carbon\Carbon::now();
    $isOpen = isset($announcementDate) ? $currentTime->greaterThanOrEqualTo($announcementDate) : false;
    
    // Paksa buka jika ada error (agar user bisa baca errornya)
    if(isset($customError) || session('error')) {
        $isOpen = true; 
    }
?>


<style>
    .glass-card {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
    .text-shadow { text-shadow: 0 4px 20px rgba(0,0,0,0.5); }
    .countdown-item { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    .countdown-item:hover { transform: translateY(-5px); background: rgba(255, 255, 255, 0.05); border-color: rgba(59, 130, 246, 0.5); }
</style>

<div class="min-h-screen w-full flex flex-col items-center justify-center relative overflow-hidden bg-slate-950 font-sans py-10">
    
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900"></div>
        <div class="absolute top-0 left-0 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
        <div class="absolute -top-40 -right-40 w-[500px] h-[500px] bg-blue-600/20 rounded-full blur-[100px] animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-[500px] h-[500px] bg-indigo-600/10 rounded-full blur-[100px]"></div>
    </div>

    <div class="relative z-10 w-full max-w-4xl px-4">
        
        <div class="text-center mb-10" data-aos="fade-down">
            <h1 class="text-4xl md:text-6xl font-black text-white tracking-tight leading-tight mb-3 text-shadow uppercase">
                Pengumuman <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-500">PPDB</span>
            </h1>
            <p class="text-blue-200 text-lg font-medium tracking-widest uppercase opacity-80">
                Penerimaan Peserta Didik Baru <?php echo e(date('Y')); ?>

            </p>
        </div>

        <div class="glass-card rounded-[2.5rem] overflow-hidden relative" data-aos="fade-up">
            
            <div class="h-1.5 w-full bg-gradient-to-r from-blue-500 via-indigo-600 to-violet-600"></div>

            <div class="p-8 md:p-12">
                
                
                <div id="countdown-wrapper" class="<?php echo e($isOpen ? 'hidden' : 'block'); ?>">
                    <div class="text-center max-w-3xl mx-auto">
                        <div class="mb-10">
                            <span class="inline-flex items-center gap-2 py-1.5 px-4 rounded-full bg-amber-500/10 text-amber-300 text-xs font-bold tracking-widest border border-amber-500/20 mb-6 uppercase">
                                <span class="w-2 h-2 rounded-full bg-amber-400 animate-pulse"></span> Segera Dibuka
                            </span>
                            <h2 class="text-3xl font-bold text-white mb-2">Menuju Pengumuman Resmi</h2>
                            <?php if(isset($announcementDate)): ?>
                                <p class="text-slate-400">Hasil seleksi PPDB dapat diakses pada:</p>
                                <p class="text-lg text-amber-400 font-bold mt-1 font-mono tracking-wide"><?php echo e($announcementDate->translatedFormat('l, d F Y - H:i')); ?> WIB</p>
                            <?php endif; ?>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-8">
                            <?php $__currentLoopData = ['Hari' => 'days', 'Jam' => 'hours', 'Menit' => 'minutes', 'Detik' => 'seconds']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $id): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="countdown-item bg-slate-800/50 border border-white/5 rounded-3xl p-5 shadow-inner">
                                    <span id="<?php echo e($id); ?>" class="block text-4xl md:text-6xl font-black text-white font-mono tracking-tighter">00</span>
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1 block"><?php echo e($label); ?></span>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>

                
                <div id="main-content" class="<?php echo e($isOpen ? 'block animate-fade-in' : 'hidden'); ?>">
                    <div class="max-w-xl mx-auto text-center">
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-white mb-2">Cek Hasil Seleksi</h2>
                            <p class="text-slate-400">Masukkan Nomor Pendaftaran atau NISN Siswa.</p>
                        </div>

                        <form action="<?php echo e(route('ppdb.search')); ?>" method="POST" class="relative group">
                            <?php echo csrf_field(); ?>
                            <div class="relative mb-6">
                                <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                                    <i class="ph-bold ph-magnifying-glass text-2xl text-slate-500 group-focus-within:text-blue-400 transition-colors"></i>
                                </div>
                                <input type="text" name="search" class="block w-full pl-16 pr-6 py-5 bg-slate-900/50 border-2 border-slate-700 text-white text-lg font-bold rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-600 outline-none shadow-inner" placeholder="Contoh: REG-2025-001" required autocomplete="off">
                            </div>
                            
                            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-600/30 transform hover:-translate-y-1 transition-all flex items-center justify-center gap-3">
                                <span>Cek Status Sekarang</span>
                                <i class="ph-bold ph-arrow-right text-xl"></i>
                            </button>
                        </form>

                        <?php if(session('error') || isset($customError)): ?>
                            <div class="mt-8 p-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl flex items-center gap-4 text-left animate-pulse">
                                <div class="bg-rose-500/20 p-2.5 rounded-full text-rose-400"><i class="ph-fill ph-warning-circle text-xl"></i></div>
                                <div>
                                    <h4 class="font-bold text-rose-400 text-sm">Pemberitahuan</h4>
                                    <p class="text-xs text-rose-300/80"><?php echo e(session('error') ?? $customError); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
            
            <div class="bg-slate-900/80 border-t border-white/5 p-4 text-center backdrop-blur-sm">
                <p class="text-xs text-slate-500 font-medium tracking-wide">&copy; <?php echo e(date('Y')); ?> Panitia PPDB SMPN 3 Lakbok</p>
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
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/ppdb/check.blade.php ENDPATH**/ ?>