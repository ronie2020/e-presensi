<?php $__env->startSection('content'); ?>
<?php
    $targetDate = isset($announcementDate) ? $announcementDate : \Carbon\Carbon::now()->addYear();
    $currentTime = \Carbon\Carbon::now();
    $isOpen = $currentTime->greaterThanOrEqualTo($targetDate);
?>

<style>
    /* Pola Grid Halus */
    .bg-grid-pattern {
        background-image: linear-gradient(to right, rgba(255,255,255,0.03) 1px, transparent 1px),
                          linear-gradient(to bottom, rgba(255,255,255,0.03) 1px, transparent 1px);
        background-size: 40px 40px;
    }
    .text-shadow { text-shadow: 0 4px 20px rgba(0,0,0,0.5); }
    
    /* Kartu Kaca Premium */
    .glass-card {
        background: rgba(15, 23, 42, 0.6); /* Slate-900 transparan */
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
    
    /* Animasi Hover */
    .countdown-item {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .countdown-item:hover {
        transform: translateY(-5px);
        background: rgba(255, 255, 255, 0.05);
        border-color: rgba(59, 130, 246, 0.5); /* Blue-500 */
    }
</style>

<div class="min-h-screen w-full flex flex-col items-center justify-center relative overflow-hidden bg-slate-950 font-sans">
    
    <!-- BACKGROUND UTAMA (Konsisten dengan Dashboard) -->
    <div class="absolute inset-0 z-0">
        
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900"></div>
        
        <div class="absolute top-0 left-0 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
        <div class="absolute -top-40 -right-40 w-[500px] h-[500px] bg-blue-600/20 rounded-full blur-[100px] animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-[500px] h-[500px] bg-sky-600/10 rounded-full blur-[100px]"></div>
    </div>

    <!-- MAIN CONTAINER -->
    <div class="relative z-10 w-full max-w-4xl px-4 py-10">
        
        <!-- HEADER -->
        <div class="text-center mb-10" data-aos="fade-down">
            <div class="inline-block p-4 rounded-[2rem] bg-white/5 backdrop-blur-md border border-white/10 mb-6 shadow-2xl shadow-blue-900/30 group hover:bg-white/10 transition-colors">
                <?php if (isset($component)) { $__componentOriginal8892e718f3d0d7a916180885c6f012e7 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal8892e718f3d0d7a916180885c6f012e7 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.application-logo','data' => ['class' => 'w-20 h-20 fill-current text-white drop-shadow-lg']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('application-logo'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'w-20 h-20 fill-current text-white drop-shadow-lg']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal8892e718f3d0d7a916180885c6f012e7)): ?>
<?php $attributes = $__attributesOriginal8892e718f3d0d7a916180885c6f012e7; ?>
<?php unset($__attributesOriginal8892e718f3d0d7a916180885c6f012e7); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal8892e718f3d0d7a916180885c6f012e7)): ?>
<?php $component = $__componentOriginal8892e718f3d0d7a916180885c6f012e7; ?>
<?php unset($__componentOriginal8892e718f3d0d7a916180885c6f012e7); ?>
<?php endif; ?>
            </div>
            <h1 class="text-4xl md:text-6xl font-black text-white tracking-tight leading-tight mb-3 text-shadow uppercase">
                Pengumuman <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-300 to-blue-500">Kelulusan</span>
            </h1>
            <p class="text-blue-200 text-lg font-medium tracking-widest uppercase opacity-80">
                Tahun Pelajaran <?php echo e(date('Y')); ?>/<?php echo e(date('Y')+1); ?>

            </p>
        </div>

        <!-- CONTENT CARD -->
        <div class="glass-card rounded-[2.5rem] overflow-hidden relative" data-aos="fade-up">
            
            <div class="h-1.5 w-full bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600"></div>

            <div class="p-8 md:p-12">
                
                
                <div id="countdown-wrapper" class="<?php echo e($isOpen ? 'hidden' : 'block'); ?>">
                    <div class="text-center max-w-3xl mx-auto">
                        <div class="mb-10">
                            <span class="inline-flex items-center gap-2 py-1.5 px-4 rounded-full bg-blue-500/10 text-blue-300 text-xs font-bold tracking-widest border border-blue-500/20 mb-6 uppercase">
                                <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span> Segera Hadir
                            </span>
                            <h2 class="text-3xl font-bold text-white mb-2">Menuju Pengumuman</h2>
                            <?php if(isset($announcementDate)): ?>
                                <p class="text-slate-400">Hasil kelulusan akan dibuka pada:</p>
                                <p class="text-lg text-sky-400 font-bold mt-1 font-mono tracking-wide"><?php echo e($announcementDate->translatedFormat('l, d F Y - H:i')); ?> WIB</p>
                            <?php endif; ?>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-8">
                            <!-- Komponen Waktu -->
                            <?php $__currentLoopData = ['Hari' => 'days', 'Jam' => 'hours', 'Menit' => 'minutes', 'Detik' => 'seconds']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $label => $id): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="countdown-item bg-slate-800/50 border border-white/5 rounded-3xl p-5 shadow-inner relative group">
                                    <span id="<?php echo e($id); ?>" class="block text-4xl md:text-6xl font-black text-white font-mono tracking-tighter group-hover:text-sky-400 transition-colors">00</span>
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mt-1 block"><?php echo e($label); ?></span>
                                    <?php if($id == 'seconds'): ?>
                                        <div class="absolute inset-0 rounded-3xl border border-sky-500/30 opacity-0 group-hover:opacity-100 transition-opacity animate-pulse"></div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <p class="text-sm text-slate-500 italic">
                            Halaman akan dimuat ulang secara otomatis saat waktu hitung mundur selesai.
                        </p>
                    </div>
                </div>

                
                <div id="main-content" class="<?php echo e($isOpen ? 'block animate-fade-in' : 'hidden'); ?>">
                    <?php if(!isset($student)): ?>
                    
                    <div class="max-w-xl mx-auto text-center">
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-white mb-2">Cek Status Kelulusan</h2>
                            <p class="text-slate-400">Masukkan Nomor Induk Siswa Nasional (NISN) Anda.</p>
                        </div>

                        <form action="<?php echo e(route('graduation.check')); ?>" method="POST" class="relative group">
                            <?php echo csrf_field(); ?>
                            <div class="relative mb-6">
                                <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                                    <i class="ph-bold ph-identification-card text-2xl text-slate-500 group-focus-within:text-blue-400 transition-colors"></i>
                                </div>
                                <input type="text" name="nisn" class="block w-full pl-16 pr-6 py-5 bg-slate-900/50 border-2 border-slate-700 text-white text-lg font-bold rounded-2xl focus:ring-4 focus:ring-blue-500/20 focus:border-blue-500 transition-all placeholder:text-slate-600 outline-none shadow-inner" placeholder="Nomor NISN Siswa" required autocomplete="off" autofocus>
                            </div>
                            
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white font-bold py-4 rounded-2xl shadow-lg shadow-blue-600/20 transform hover:-translate-y-1 transition-all flex items-center justify-center gap-3">
                                <span>Periksa Data</span>
                                <i class="ph-bold ph-magnifying-glass text-xl"></i>
                            </button>
                        </form>

                        <?php if(session('error')): ?>
                            <div class="mt-8 p-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl flex items-center gap-4 text-left animate-pulse">
                                <div class="bg-rose-500/20 p-2.5 rounded-full text-rose-400"><i class="ph-fill ph-warning-circle text-xl"></i></div>
                                <div>
                                    <h4 class="font-bold text-rose-400 text-sm">Data Tidak Ditemukan</h4>
                                    <p class="text-xs text-rose-300/80"><?php echo e(session('error')); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <?php else: ?>
                    
                    <div class="animate-fade-in-up">
                        <div class="flex flex-col md:flex-row gap-8 items-start">
                            
                            <div class="w-full md:w-1/3 flex flex-col items-center text-center">
                                <div class="relative w-48 h-48 mb-6 group">
                                    <div class="absolute inset-0 bg-gradient-to-br from-sky-400 to-blue-600 rounded-full blur-xl opacity-50 group-hover:opacity-75 transition-opacity"></div>
                                    <div class="relative w-full h-full rounded-full p-1.5 bg-gradient-to-br from-slate-700 to-slate-800 border border-slate-600 overflow-hidden shadow-2xl">
                                        <div class="w-full h-full rounded-full bg-slate-800 overflow-hidden relative">
                                            <?php if($student->photo_path): ?>
                                                <img src="<?php echo e(asset('storage/' . $student->photo_path)); ?>" class="w-full h-full object-cover">
                                            <?php else: ?>
                                                <div class="w-full h-full flex items-center justify-center text-5xl font-black text-slate-600 bg-slate-900 select-none">
                                                    <?php echo e(substr($student->name, 0, 1)); ?>

                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <h3 class="text-2xl font-black text-white leading-tight mb-2"><?php echo e($student->name); ?></h3>
                                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-slate-800 border border-slate-700">
                                    <span class="text-slate-400 text-xs font-bold uppercase tracking-wider">NISN</span>
                                    <span class="text-sky-400 font-mono font-bold"><?php echo e($student->student_id); ?></span>
                                </div>
                            </div>

                            
                            <div class="w-full md:w-2/3">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                                    <div class="bg-slate-800/50 p-5 rounded-3xl border border-white/5 hover:border-white/10 transition-colors">
                                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Kelas</p>
                                        <p class="font-bold text-white text-xl"><?php echo e($student->schoolClass->name ?? '-'); ?></p>
                                    </div>
                                    <div class="bg-slate-800/50 p-5 rounded-3xl border border-white/5 hover:border-white/10 transition-colors">
                                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Tempat, Tanggal Lahir</p>
                                        <p class="font-bold text-white text-xl"><?php echo e($student->pob); ?>, <?php echo e(\Carbon\Carbon::parse($student->dob)->format('d M Y')); ?></p>
                                    </div>
                                </div>

                                
                                <?php if($student->graduation->status === 'LULUS'): ?>
                                    <div class="relative bg-gradient-to-r from-emerald-600 to-teal-600 rounded-[2rem] p-8 text-center text-white shadow-2xl shadow-emerald-900/50 overflow-hidden mb-6 border border-emerald-400/30">
                                        
                                        <div class="absolute top-0 left-0 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/confetti.png')] opacity-20"></div>
                                        
                                        <div class="relative z-10">
                                            <h2 class="text-sm font-bold text-emerald-100 mb-2 uppercase tracking-widest">Keputusan Rapat Dewan Guru</h2>
                                            <div class="inline-block border-y-2 border-emerald-100/30 py-2 mb-4">
                                                <h1 class="text-5xl md:text-6xl font-black tracking-tight drop-shadow-md">LULUS</h1>
                                            </div>
                                            <p class="text-emerald-100 font-medium">Selamat! Anda telah menyelesaikan pendidikan dengan baik.</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex flex-col sm:flex-row gap-4">
                                        <a href="<?php echo e(route('graduation.print', $student->id)); ?>" target="_blank" class="flex-1 bg-white text-slate-900 hover:bg-sky-50 font-bold py-4 rounded-2xl transition-all flex items-center justify-center gap-2 shadow-lg group">
                                            <i class="ph-printer text-xl group-hover:scale-110 transition-transform"></i> 
                                            <span>Cetak SKL</span>
                                        </a>
                                        <a href="<?php echo e(route('graduation.index')); ?>" class="px-8 py-4 bg-slate-800 border border-slate-600 text-slate-300 hover:text-white hover:border-slate-500 font-bold rounded-2xl transition-all flex items-center justify-center gap-2">
                                            <i class="ph-arrow-counter-clockwise text-xl"></i> Cari Lain
                                        </a>
                                    </div>
                                <?php else: ?>
                                    <div class="bg-gradient-to-r from-rose-600 to-red-700 rounded-[2rem] p-8 text-center text-white shadow-2xl shadow-rose-900/50 overflow-hidden mb-6 border border-rose-500/30">
                                        <div class="relative z-10">
                                            <h2 class="text-sm font-bold text-rose-100 mb-2 uppercase tracking-widest">Keputusan Rapat Dewan Guru</h2>
                                            <h1 class="text-4xl md:text-5xl font-black tracking-tight mb-2 drop-shadow-md uppercase"><?php echo e($student->graduation->status ?? 'DITUNDA'); ?></h1>
                                            <p class="text-rose-100 font-medium">Mohon hubungi Wali Kelas atau Bagian Kurikulum.</p>
                                        </div>
                                    </div>
                                    <a href="<?php echo e(route('graduation.index')); ?>" class="w-full block text-center px-6 py-4 bg-slate-800 border border-slate-600 text-slate-300 hover:text-white font-bold rounded-2xl transition-all">Kembali ke Pencarian</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

            </div>
            
            
            <div class="bg-slate-900/80 border-t border-white/5 p-4 text-center backdrop-blur-sm">
                <p class="text-xs text-slate-500 font-medium tracking-wide">&copy; <?php echo e(date('Y')); ?> Sistem Informasi Akademik SMPN 3 Lakbok</p>
            </div>
        </div>
    </div>
</div>


<?php if(!$isOpen): ?>
<script>
    const targetDateStr = "<?php echo e($targetDate->format('Y-m-d H:i:s')); ?>";
    const countDownDate = new Date(targetDateStr).getTime();

    const x = setInterval(function() {
        const now = new Date().getTime();
        const distance = countDownDate - now;

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        document.getElementById("days").innerText = days < 10 ? "0" + days : days;
        document.getElementById("hours").innerText = hours < 10 ? "0" + hours : hours;
        document.getElementById("minutes").innerText = minutes < 10 ? "0" + minutes : minutes;
        document.getElementById("seconds").innerText = seconds < 10 ? "0" + seconds : seconds;

        if (distance < 0) {
            clearInterval(x);
            document.getElementById("countdown-wrapper").style.display = "none";
            document.getElementById("main-content").style.display = "block";
            document.getElementById("main-content").classList.add('animate-fade-in');
        }
    }, 1000);
</script>
<?php endif; ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/graduation/index.blade.php ENDPATH**/ ?>