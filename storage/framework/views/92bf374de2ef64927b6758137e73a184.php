<?php $__env->startSection('content'); ?>

<?php
    \Carbon\Carbon::setLocale('id');
    $isAlumni = $student->status === 'graduated';
?>

<style>
    .custom-scrollbar::-webkit-scrollbar { height: 0px; background: transparent; }
    .custom-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    [x-cloak] { display: none !important; }
    .ph-fill, .ph-duotone, .ph-bold { vertical-align: middle; }
</style>

<!-- X-DATA: Main Controller -->
<div class="w-full max-w-6xl mx-auto pb-20 px-4 sm:px-6"
     x-data="{ 
        activeTab: new URLSearchParams(window.location.search).get('tab') || 'ringkasan',
        updateTab(val) {
            this.activeTab = val;
            const url = new URL(window.location);
            url.searchParams.set('tab', val);
            window.history.pushState({}, '', url);
            
            // Trigger resize untuk Chart.js saat tab berubah
            if(val === 'akademik' || val === 'kehadiran') {
                setTimeout(() => { 
                    window.dispatchEvent(new Event('resize')); 
                }, 100);
            }
        }
     }">
    
    
    <?php echo $__env->make('students.portal.partials.header', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <?php echo $__env->make('students.portal.partials.tabs-nav', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    
    <div class="min-h-[400px]">
        
        <!-- Tab Ringkasan -->
        <div x-show="activeTab === 'ringkasan'" x-transition:enter="transition ease-out duration-300">
            <?php echo $__env->make('students.portal.partials.tab-ringkasan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        <?php if(!$isAlumni): ?>
            <!-- Tab 7 Kebiasaan -->
            <div x-show="activeTab === 'kebiasaan'" x-cloak x-transition:enter="transition ease-out duration-300">
                <?php echo $__env->make('students.portal.partials.tab-kebiasaan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

            <!-- Tab Buku Penghubung -->
            <div x-show="activeTab === 'penghubung'" x-cloak x-transition:enter="transition ease-out duration-300">
                <?php echo $__env->make('students.portal.partials.tab-penghubung', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
            
            <!-- [BARU] TAB E-COUNSELING (BK) -->
            <div x-show="activeTab === 'bk'" x-cloak x-transition:enter="transition ease-out duration-300">
                <?php echo $__env->make('students.portal.partials.tab-bk', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
            <!-- ============================== -->

            <!-- Tab Pengaduan -->
            <div x-show="activeTab === 'pengaduan'" x-cloak x-transition:enter="transition ease-out duration-300">
                <?php echo $__env->make('students.portal.partials.tab-pengaduan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

            <!-- Tab Jadwal -->
            <div x-show="activeTab === 'jadwal'" x-cloak x-transition:enter="transition ease-out duration-300">
                <?php echo $__env->make('students.portal.partials.tab-jadwal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

            <!-- Tab LMS (Tugas) -->
            <div x-show="activeTab === 'lms'" x-cloak x-transition:enter="transition ease-out duration-300">
                <?php echo $__env->make('students.portal.partials.tab-lms', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

            <!-- Tab Jurnal KBM -->
            <div x-show="activeTab === 'kbm'" x-cloak x-transition:enter="transition ease-out duration-300">
                <?php echo $__env->make('students.portal.partials.tab-kbm', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

            <!-- Tab Akademik (Nilai) -->
            <div x-show="activeTab === 'akademik'" x-cloak x-transition:enter="transition ease-out duration-300">
                <?php echo $__env->make('students.portal.partials.tab-akademik', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

            <!-- Tab Kehadiran -->
            <div x-show="activeTab === 'kehadiran'" x-cloak x-transition:enter="transition ease-out duration-300">
                <?php echo $__env->make('students.portal.partials.tab-kehadiran', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

            <!-- Tab Disiplin -->
            <div x-show="activeTab === 'disiplin'" x-cloak x-transition:enter="transition ease-out duration-300">
                <?php echo $__env->make('students.portal.partials.tab-disiplin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>

            <!-- Tab Keagamaan (New/Separate) -->
            <div x-show="activeTab === 'keagamaan'" x-cloak x-transition:enter="transition ease-out duration-300">
                <?php echo $__env->make('students.portal.partials.tab-keagamaan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
            <!-- Tab Ramadan Jurnal -->   
            <div x-show="activeTab === 'ramadan_jurnal'" x-cloak x-transition:enter="transition ease-out duration-300">
                <?php echo $__env->make('students.portal.partials.tab-ramadan-jurnal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
            <!-- Tab Leaderboard Ramadhan -->
            <div x-show="activeTab === 'ramadan_rank'" x-cloak x-transition:enter="transition ease-out duration-300">
                <?php echo $__env->make('students.portal.partials.tab-ramadan-leaderboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            </div>
        <?php endif; ?>
        
        <!-- Tab Prestasi (Alumni & Siswa) -->
        <div x-show="activeTab === 'prestasi'" x-cloak x-transition:enter="transition ease-out duration-300">
            <?php echo $__env->make('students.portal.partials.tab-prestasi', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>

        <!-- Tab Perpustakaan (Alumni & Siswa) -->
        <div x-show="activeTab === 'perpustakaan'" x-cloak x-transition:enter="transition ease-out duration-300">
            <?php echo $__env->make('students.portal.partials.tab-perpustakaan', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>


    </div>
</div>


<?php echo $__env->make('students.portal.partials.scripts', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/students/portal/show.blade.php ENDPATH**/ ?>