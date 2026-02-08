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
    
    <style>
        @import url('https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap');

        .font-jakarta {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        @keyframes fadeInUp { 
            from { opacity: 0; transform: translateY(20px); } 
            to { opacity: 1; transform: translateY(0); } 
        }
        
        .animate-enter { 
            opacity: 0; 
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; 
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.5);
        }

        .page-container {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e2e8f0;
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #cbd5e1;
        }
    </style>

    <div class="page-container p-4 md:p-8 space-y-8 min-h-screen bg-slate-50 font-jakarta">
        
        
        <div class="animate-enter relative rounded-[3rem] bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 p-8 md:p-12 text-white shadow-2xl shadow-blue-900/30 overflow-hidden group border border-white/10">
            
            <div class="absolute top-0 right-0 -mt-20 -mr-20 w-[500px] h-[500px] bg-blue-500/20 rounded-full blur-[100px] group-hover:opacity-40 transition-opacity duration-1000"></div>
            <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-[400px] h-[400px] bg-indigo-500/10 rounded-full blur-[80px]"></div>
            
            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-10">
                <div class="flex-1">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 border border-white/10 text-blue-200 text-[10px] font-black uppercase tracking-[0.2em] mb-6 backdrop-blur-md shadow-inner">
                        <span class="relative flex h-2 w-2">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-400"></span>
                        </span>
                        Sistem Monitoring Karakter
                    </div>
                    <h1 class="text-4xl md:text-6xl font-black text-white tracking-tighter mb-4 leading-none">
                        Pantau <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-cyan-200">Kebiasaan</span> Siswa
                    </h1>
                    <p class="text-blue-100/60 text-sm md:text-lg max-w-xl leading-relaxed font-medium">
                        Kelola dan tinjau perkembangan karakter siswa secara real-time. Berikan apresiasi terbaik untuk setiap langkah kecil mereka.
                    </p>
                </div>

                
                <div class="w-full lg:w-auto shrink-0 flex flex-col gap-4">
                    
                    <form id="filterForm" action="<?php echo e(route('teacher.habits.index')); ?>" method="GET" class="bg-white/5 backdrop-blur-xl p-6 rounded-[2rem] border border-white/10 shadow-2xl flex flex-col gap-5 relative">
                        <div id="formLoading" class="hidden absolute inset-0 bg-slate-900/40 backdrop-blur-[4px] z-10 rounded-[2rem] flex items-center justify-center">
                            <i class="ph-bold ph-circle-notch animate-spin text-blue-400 text-3xl"></i>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-blue-200 uppercase tracking-widest ml-1 block">Periode</label>
                                <div class="relative group">
                                    <i class="ph-bold ph-calendar absolute left-4 top-1/2 -translate-y-1/2 text-blue-400"></i>
                                    <input type="date" id="filterDate" name="date" value="<?php echo e($date); ?>" 
                                        class="block w-full pl-11 pr-4 py-3 bg-white/10 border-white/10 rounded-2xl text-xs font-bold text-white focus:ring-blue-500 focus:border-blue-500 transition-all uppercase" 
                                        onchange="submitFilter()">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-blue-200 uppercase tracking-widest ml-1 block">Kelas</label>
                                <div class="relative group">
                                    <i class="ph-bold ph-users-three absolute left-4 top-1/2 -translate-y-1/2 text-blue-400"></i>
                                    <select id="filterClass" name="class_id" 
                                        class="block w-full pl-11 pr-10 py-3 bg-white/10 border-white/10 rounded-2xl text-xs font-bold text-white focus:ring-blue-500 focus:border-blue-500 transition-all appearance-none" 
                                        onchange="submitFilter()">
                                        <option value="" class="bg-slate-900 text-white">Pilih Kelas</option>
                                        <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($class->id); ?>" <?php echo e($classId == $class->id ? 'selected' : ''); ?> class="bg-slate-900 text-white"><?php echo e($class->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </form>

                    
                    <?php if($classId): ?>
                        <button onclick="printReport()" 
                            class="group w-full py-4 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-white rounded-[1.5rem] font-bold shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-3 transition-all active:scale-95 border border-white/10">
                            <div class="bg-white/20 p-1.5 rounded-lg group-hover:rotate-12 transition-transform">
                                <i class="ph-bold ph-printer text-lg"></i>
                            </div>
                            <span class="uppercase tracking-wider text-xs">Cetak Laporan Resmi</span>
                        </button>
                    <?php endif; ?>
                </div>    
                
                
                
            </div>
        </div>

        <?php if($classId): ?>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 animate-enter" style="animation-delay: 100ms">
                
                <div class="glass-card p-8 rounded-[2.5rem] shadow-sm flex items-center gap-6 group hover:border-emerald-200 transition-all duration-300">
                    <div class="w-16 h-16 rounded-[1.5rem] bg-emerald-50 text-emerald-600 flex items-center justify-center text-3xl group-hover:scale-110 transition-transform shadow-inner">
                        <i class="ph-fill ph-shield-check"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Sudah Melapor</p>
                        <p class="text-4xl font-black text-slate-800 tracking-tighter"><?php echo e($stats['submitted']); ?> <span class="text-sm font-bold text-slate-400">SISWA</span></p>
                    </div>
                </div>

                
                <div class="glass-card p-8 rounded-[2.5rem] shadow-sm flex items-center gap-6 group hover:border-rose-200 transition-all duration-300">
                    <div class="w-16 h-16 rounded-[1.5rem] bg-rose-50 text-rose-500 flex items-center justify-center text-3xl group-hover:scale-110 transition-transform shadow-inner">
                        <i class="ph-fill ph-clock-countdown"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Belum Melapor</p>
                        <p class="text-4xl font-black text-slate-800 tracking-tighter"><?php echo e($stats['missing']); ?> <span class="text-sm font-bold text-slate-400">SISWA</span></p>
                    </div>
                </div>

                
                <div class="bg-slate-900 p-8 rounded-[2.5rem] shadow-2xl shadow-blue-900/20 flex items-center gap-6 group hover:bg-slate-800 transition-all duration-300 border border-white/5 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-blue-500/10 rounded-full blur-3xl"></div>
                    <div class="w-16 h-16 rounded-[1.5rem] bg-white/10 text-blue-400 flex items-center justify-center text-3xl group-hover:rotate-12 transition-transform shadow-inner">
                        <i class="ph-fill ph-chart-line-up"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-blue-300/60 uppercase tracking-widest mb-1">Tingkat Partisipasi</p>
                        <p class="text-4xl font-black text-white tracking-tighter"><?php echo e($stats['percentage']); ?>%</p>
                    </div>
                </div>
            </div>

            
            <div class="animate-enter bg-white rounded-[3rem] shadow-sm border border-slate-100 overflow-hidden mb-12" style="animation-delay: 200ms">
                <div class="px-8 py-6 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <h2 class="text-xl font-black text-slate-800 flex items-center gap-3">
                        <i class="ph-bold ph-list-checks text-blue-600"></i> 
                        Status Monitoring Harian
                    </h2>
                    <div class="flex items-center gap-2">
                         <span class="px-4 py-1.5 rounded-xl bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-wider">
                            Kelas: <?php echo e($classes->find($classId)->name ?? '-'); ?>

                         </span>
                         <span class="px-4 py-1.5 rounded-xl bg-slate-50 text-slate-500 text-[10px] font-black uppercase tracking-wider">
                            <?php echo e(\Carbon\Carbon::parse($date)->translatedFormat('d F Y')); ?>

                         </span>
                    </div>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left">
                        
                        <thead class="bg-slate-50/50 text-slate-400 uppercase text-[9px] font-black tracking-[0.2em] border-b border-slate-100">
                            <tr>
                                <th class="px-10 py-6">Profil Siswa</th>
                                <th class="px-6 py-6 text-center">Status Jurnal</th>
                                <th class="px-6 py-6 text-center">Waktu Masuk</th>
                                <th class="px-6 py-6 text-center">Makan (MBG)</th>
                                <th class="px-10 py-6 text-right">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php $__empty_1 = true; $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr class="hover:bg-blue-50/30 transition-all group">
                                    <td class="px-10 py-5">
                                        <div class="flex items-center gap-5">
                                            <div class="w-12 h-12 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-600 font-black text-sm border border-slate-200 group-hover:bg-blue-600 group-hover:text-white group-hover:border-blue-500 transition-all duration-300">
                                                <?php echo e(substr($student->name, 0, 1)); ?>

                                            </div>
                                            <div>
                                                <div class="font-black text-slate-800 group-hover:text-blue-600 transition-colors uppercase tracking-tight text-sm"><?php echo e($student->name); ?></div>
                                                <div class="text-[9px] text-slate-400 font-bold tracking-widest uppercase mt-0.5"><?php echo e($student->student_id); ?></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <?php if($student->habit_status == 'submitted'): ?>
                                            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase tracking-widest border border-emerald-100">
                                                <i class="ph-fill ph-check-circle text-xs"></i> Sudah Lapor
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-slate-100 text-slate-400 text-[9px] font-black uppercase tracking-widest border border-slate-200">
                                                <i class="ph-bold ph-warning-circle text-xs"></i> Belum Ada
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-5 text-center">
                                        <?php if($student->habit_data): ?>
                                            <div class="flex items-center justify-center gap-2 text-slate-600 font-black text-xs">
                                                <i class="ph-bold ph-timer text-blue-500 text-sm"></i>
                                                <?php echo e($student->habit_data->created_at->format('H:i')); ?>

                                            </div>
                                        <?php else: ?>
                                            <span class="text-slate-300 text-xs font-bold italic tracking-tighter">MENUNGGU...</span>
                                        <?php endif; ?>
                                    </td>
                                    
                                    
                                    <td class="px-6 py-5 text-center">
                                        <?php if($student->habit_data && $student->habit_data->habit_5): ?> 
                                            <span class="inline-flex w-8 h-8 items-center justify-center rounded-full bg-orange-100 text-orange-600 shadow-sm" title="Sudah Mengambil Makan">
                                                <i class="ph-fill ph-check font-bold"></i>
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex w-8 h-8 items-center justify-center rounded-full bg-slate-50 text-slate-300 border border-slate-100" title="Belum Mengambil">
                                                <i class="ph-bold ph-minus"></i>
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="px-10 py-5 text-right">
                                        <?php if($student->habit_data): ?>
                                            <button onclick="openDetail(<?php echo e($student->habit_data->id); ?>)" 
                                                class="inline-flex items-center gap-2 text-blue-600 hover:text-white font-black text-[9px] uppercase tracking-[0.1em] bg-blue-50 hover:bg-blue-600 px-6 py-3 rounded-2xl transition-all active:scale-90 border border-blue-100 shadow-sm">
                                                <i class="ph-bold ph-notebook text-sm"></i> Tinjau Laporan
                                            </button>
                                        <?php else: ?>
                                            <span class="text-slate-300 text-[9px] font-black uppercase tracking-widest italic opacity-50">Laporan Kosong</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="5" class="px-8 py-24 text-center">
                                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                            <i class="ph-duotone ph-users-three text-3xl"></i>
                                        </div>
                                        <p class="text-slate-400 font-black uppercase tracking-widest text-[10px] italic">Tidak ada data siswa yang ditemukan</p>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else: ?>
            
            <div class="animate-enter text-center py-40 bg-white rounded-[3rem] border-2 border-dashed border-slate-200 shadow-inner group hover:border-blue-300 transition-colors" style="animation-delay: 100ms">
                <div class="w-32 h-32 bg-blue-50 rounded-[2.5rem] flex items-center justify-center mx-auto mb-10 text-blue-500 group-hover:scale-110 transition-all duration-500 shadow-inner rotate-3 group-hover:rotate-0">
                    <i class="ph-duotone ph-magnifying-glass text-6xl"></i>
                </div>
                <h3 class="text-3xl font-black text-slate-800 tracking-tighter">Pilih Kelas Monitoring</h3>
                <p class="text-slate-500 text-sm max-w-sm mx-auto mt-4 leading-relaxed font-medium">
                    Silakan gunakan panel filter di bagian atas untuk memilih kelas dan tanggal monitoring yang ingin Anda tinjau.
                </p>
            </div>
        <?php endif; ?>

    </div>

    
    <div id="detailModal" class="fixed inset-0 z-[100] hidden overflow-y-auto">
        <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-xl transition-opacity duration-500" onclick="closeDetail()"></div>
        <div class="flex items-center justify-center min-h-screen p-4 sm:p-6">
            <div class="bg-white rounded-[3.5rem] w-full max-w-2xl shadow-2xl relative transform transition-all overflow-hidden border border-white/20">
                
                <div class="h-2 bg-gradient-to-r from-blue-600 via-cyan-400 to-indigo-600"></div>
                
                <button onclick="closeDetail()" class="absolute top-8 right-8 z-10 text-slate-400 hover:text-rose-500 p-3 rounded-2xl hover:bg-rose-50 transition-all active:scale-90">
                    <i class="ph-bold ph-x text-2xl"></i>
                </button>
                
                <div id="modalContent" class="p-10 md:p-14 font-jakarta">
                    
                    <div class="flex flex-col items-center justify-center py-32">
                        <div class="relative">
                             <div class="w-20 h-20 border-4 border-blue-100 rounded-full"></div>
                             <div class="w-20 h-20 border-4 border-blue-600 border-t-transparent rounded-full animate-spin absolute top-0 left-0"></div>
                        </div>
                        <p class="mt-8 text-slate-400 text-[10px] font-black uppercase tracking-[0.4em]">Memproses Data Siswa...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function submitFilter() {
            document.getElementById('formLoading').classList.remove('hidden');
            document.getElementById('filterForm').submit();
        }

        function openDetail(id) {
            const modal = document.getElementById('detailModal');
            const content = document.getElementById('modalContent');
            
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            
            // Re-render loading state
            content.innerHTML = `
                <div class="flex flex-col items-center justify-center py-32">
                    <div class="relative">
                         <div class="w-20 h-20 border-4 border-blue-100 rounded-full"></div>
                         <div class="w-20 h-20 border-4 border-blue-600 border-t-transparent rounded-full animate-spin absolute top-0 left-0"></div>
                    </div>
                    <p class="mt-8 text-slate-400 text-[10px] font-black uppercase tracking-[0.4em]">Mengambil Jurnal...</p>
                </div>`;
            
            fetch(`<?php echo e(url('/teacher/habits/detail')); ?>/${id}`)
                .then(response => {
                    if (!response.ok) throw new Error('Network error');
                    return response.text();
                })
                .then(html => {
                    content.innerHTML = html;
                })
                .catch(err => {
                    content.innerHTML = `
                        <div class="text-center py-24">
                            <div class="w-20 h-20 bg-rose-50 text-rose-500 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-inner">
                                <i class="ph-bold ph-warning-circle text-4xl"></i>
                            </div>
                            <h3 class="text-xl font-black text-slate-800">Gagal Memuat Jurnal</h3>
                            <p class="text-slate-500 text-sm mb-10 font-medium">Terjadi gangguan pada koneksi server. Silakan coba beberapa saat lagi.</p>
                            <button onclick="openDetail(${id})" class="bg-blue-600 text-white px-10 py-4 rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-blue-500/30 active:scale-95 transition-all">
                                <i class="ph-bold ph-arrow-clockwise mr-2"></i> Muat Ulang
                            </button>
                        </div>`;
                });
        }

        function closeDetail() {
            document.getElementById('detailModal').classList.add('hidden');
            document.body.style.overflow = 'auto';
        }

         function printReport() {
            const date = document.getElementById('filterDate').value;
            const classId = document.getElementById('filterClass').value;
            
            if (!classId) {
                alert('Silakan pilih kelas terlebih dahulu.');
                return;
            }

            const url = `<?php echo e(route('teacher.habits.print')); ?>?date=${date}&class_id=${classId}`;
            window.open(url, '_blank');
        }
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\habits\teacher_index.blade.php ENDPATH**/ ?>