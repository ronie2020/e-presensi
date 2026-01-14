<div class="text-center py-6">
    <div class="inline-block p-2 px-4 rounded-full bg-emerald-100 text-emerald-700 font-bold text-xs uppercase mb-4 animate-pulse">
        <i class="ph-fill ph-check-circle"></i> Anda Sedang Login
    </div>
    <h3 class="text-xl font-bold text-slate-800 mb-1">Halo, <?php echo e(Auth::guard('student')->user()->name); ?></h3>
    <p class="text-slate-400 text-sm mb-6">Silakan pilih layanan untuk melanjutkan:</p>

    <!-- Tombol Dinamis Sesuai Mode -->
    <div x-show="mode === 'portal'" class="space-y-3">
        
        <a href="<?php echo e(route('portal.show', Auth::guard('student')->id())); ?>" class="w-full block py-4 bg-slate-800 hover:bg-slate-900 text-white rounded-xl font-bold shadow-lg transition-all flex items-center justify-center gap-2">
            <i class="ph-bold ph-user-circle text-xl"></i> Buka Profil Saya
        </a>

        
        <a href="<?php echo e(route('student.schedule.index')); ?>" class="w-full block py-4 bg-white border-2 border-slate-100 hover:border-blue-200 text-slate-700 hover:text-blue-600 rounded-xl font-bold transition-all flex items-center justify-center gap-2">
            <i class="ph-bold ph-calendar-blank text-xl"></i> Lihat Jadwal Pelajaran
        </a>

        
        <a href="<?php echo e(route('student.liaison.index')); ?>" class="w-full block py-4 bg-white border-2 border-indigo-100 hover:border-indigo-300 text-indigo-700 hover:text-indigo-800 rounded-xl font-bold transition-all flex items-center justify-center gap-2 shadow-sm hover:shadow-md">
            <i class="ph-bold ph-chat-circle-text text-xl"></i> Buku Penghubung & Chat
        </a>

        
        <a href="<?php echo e(route('student.complaints.index')); ?>" class="w-full block py-4 bg-white border-2 border-rose-100 hover:border-rose-300 text-rose-700 hover:text-rose-800 rounded-xl font-bold transition-all flex items-center justify-center gap-2 shadow-sm hover:shadow-md">
            <i class="ph-bold ph-warning-circle text-xl"></i> Layanan Pengaduan
        </a>

        
        <a href="<?php echo e(route('student.habits.dashboard')); ?>" class="w-full block py-4 bg-white border-2 border-emerald-100 hover:border-emerald-300 text-emerald-700 hover:text-emerald-800 rounded-xl font-bold transition-all flex items-center justify-center gap-2 shadow-sm hover:shadow-md">
            <i class="ph-bold ph-check-square-offset text-xl"></i> Jurnal Kebiasaan
        </a>
    </div>

    <div x-show="mode === 'lms'" x-cloak>
        <a href="<?php echo e(route('students.learning.index')); ?>" class="w-full block py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-bold shadow-lg shadow-blue-600/20 transition-all flex items-center justify-center gap-2">
            <i class="ph-bold ph-books text-xl"></i> Masuk Ruang Belajar
        </a>
    </div>

    <div x-show="mode === 'cbt'" x-cloak>
        <a href="<?php echo e(route('student.exam.index')); ?>" class="w-full block py-4 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-bold shadow-lg shadow-rose-600/20 transition-all flex items-center justify-center gap-2">
            <i class="ph-bold ph-desktop text-xl"></i> Masuk Ruang Ujian
        </a>
    </div>

    <div class="mt-4 pt-4 border-t border-slate-100">
        <form method="POST" action="<?php echo e(route('student.logout')); ?>">
            <?php echo csrf_field(); ?>
            <button type="submit" class="text-xs font-bold text-rose-500 hover:text-rose-700 hover:underline">
                Bukan Anda? Keluar Akun
            </button>
        </form>
    </div>
</div><?php /**PATH C:\Users\ronie\Documents\aplikasi\E-Presensi Netila\resources\views/students/portal/partials/home-auth-menu.blade.php ENDPATH**/ ?>