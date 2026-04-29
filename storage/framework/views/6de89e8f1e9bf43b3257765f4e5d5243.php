<?php $__env->startSection('content'); ?>

<style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

    .file-drop-zone {
        background-color: #f8fafc;
        background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' rx='24' ry='24' stroke='%23cbd5e1' stroke-width='2' stroke-dasharray='12%2c 12' stroke-dashoffset='0' stroke-linecap='square'/%3e%3c/svg%3e");
        transition: all 0.3s ease;
    }
    .file-drop-zone:hover, .file-drop-zone.active {
        background-color: #e5eff5;
        background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' rx='24' ry='24' stroke='%2356bbf1' stroke-width='2' stroke-dasharray='12%2c 12' stroke-dashoffset='0' stroke-linecap='square'/%3e%3c/svg%3e");
    }
</style>

<div class="min-h-screen w-full flex flex-col items-center justify-center relative overflow-hidden bg-elevate-surface font-sans py-12 px-4 sm:px-6" x-data="{ step: 1, fileName: '' }">
    
    
    <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>
    <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-elevate-peach-light/30 rounded-full blur-3xl pointer-events-none -z-10"></div>

    <div class="w-full max-w-2xl z-10 animate-enter">
        
        
        <div class="flex justify-between items-center mb-6">
            <a href="<?php echo e(url('/')); ?>" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white/60 border border-white/60 text-elevate-dark font-bold text-sm hover:bg-white transition-all shadow-sm backdrop-blur-md active:scale-95">
                <i class="ph-bold ph-arrow-left"></i> Beranda
            </a>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden">
            
            
            <div class="bg-elevate-gradient-card p-8 md:p-10 border-b border-slate-100 relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-elevate-primary/10 rounded-full blur-xl pointer-events-none"></div>
                
                <div class="flex flex-col md:flex-row gap-6 items-center md:items-start relative z-10 text-center md:text-left">
                    <div class="w-20 h-20 bg-white rounded-2xl shadow-sm border border-slate-100 flex items-center justify-center shrink-0 rotate-3 text-elevate-primary">
                        <i class="ph-duotone ph-file-xls text-4xl"></i>
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest bg-white border border-slate-100 text-elevate-primary mb-3 shadow-sm">
                            <i class="ph-bold ph-users-three"></i> Khusus Sekolah Asal
                        </div>
                        <h2 class="text-2xl md:text-3xl font-black text-elevate-dark tracking-tight mb-2">Pendaftaran Kolektif</h2>
                        <p class="text-sm text-elevate-dark/70 font-semibold leading-relaxed">
                            Mendaftarkan banyak siswa sekaligus menggunakan format file Excel (.xlsx). Mempercepat proses pendaftaran dari SD/MI Asal.
                        </p>
                    </div>
                </div>
            </div>

            <div class="p-8 md:p-10">
                
                
                <?php if(session('success')): ?>
                    <div class="mb-8 p-5 bg-[#DFF6DD] border border-[#B7DFB9] text-[#107C10] rounded-2xl font-bold flex gap-3 shadow-sm animate-enter">
                        <i class="ph-fill ph-check-circle text-xl shrink-0 mt-0.5"></i>
                        <p><?php echo e(session('success')); ?></p>
                    </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="mb-8 p-5 bg-[#FDE7E9] border border-[#F4C3C9] text-[#D13438] rounded-2xl shadow-sm animate-enter">
                        <div class="flex items-center gap-2 font-black mb-2 uppercase tracking-wider text-xs">
                            <i class="ph-fill ph-warning-octagon text-lg"></i> Terdapat Kesalahan:
                        </div>
                        <ul class="list-disc list-inside text-sm font-bold opacity-90 space-y-1">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                <?php endif; ?>

                
                <div x-show="step === 1" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                    <h3 class="text-lg font-black text-elevate-dark mb-4 flex items-center gap-3">
                        <span class="w-8 h-8 rounded-full bg-elevate-soft text-elevate-primary flex items-center justify-center text-sm border border-slate-200 shadow-sm">1</span> 
                        Download Format
                    </h3>
                    <div class="bg-elevate-soft/50 border border-slate-100 p-6 rounded-2xl mb-6">
                        <p class="text-sm text-elevate-dark/80 font-medium leading-relaxed mb-5">
                            Silakan unduh template Excel resmi kami. Isi data siswa sesuai dengan kolom yang telah disediakan, jangan mengubah struktur header pada template tersebut.
                        </p>
                        
                        <a href="<?php echo e(route('ppdb.download_template')); ?>" class="inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-white border border-slate-200 text-elevate-primary font-bold rounded-xl shadow-sm hover:bg-elevate-soft hover:border-elevate-accent transition-all active:scale-95 w-full sm:w-auto">
                            <i class="ph-bold ph-download-simple text-lg"></i> Unduh Template Excel
                        </a>
                    </div>
                    
                    <div class="flex justify-end pt-4 border-t border-slate-100">
                        <button type="button" @click="step = 2" class="px-8 py-4 bg-elevate-dark text-white font-bold rounded-2xl shadow-lg shadow-elevate-dark/30 hover:bg-elevate-primary transform active:scale-95 transition-all flex items-center gap-2 border border-transparent">
                            Lanjut Upload <i class="ph-bold ph-arrow-right"></i>
                        </button>
                    </div>
                </div>

                
                <div x-show="step === 2" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-x-4" x-transition:enter-end="opacity-100 translate-x-0">
                    <h3 class="text-lg font-black text-elevate-dark mb-4 flex items-center gap-3">
                        <span class="w-8 h-8 rounded-full bg-elevate-soft text-elevate-primary flex items-center justify-center text-sm border border-slate-200 shadow-sm">2</span> 
                        Upload Data
                    </h3>
                    
                    <form action="<?php echo e(route('ppdb.import')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <div class="mb-8">
                            <div class="relative file-drop-zone rounded-[2rem] p-10 flex flex-col items-center justify-center cursor-pointer group"
                                 @dragover.prevent="$el.classList.add('active')"
                                 @dragleave.prevent="$el.classList.remove('active')"
                                 @drop.prevent="$el.classList.remove('active'); $refs.fileInput.files = $event.dataTransfer.files; fileName = $refs.fileInput.files[0].name">
                                
                                <input type="file" name="excel_file" accept=".xlsx, .xls" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" x-ref="fileInput" @change="fileName = $event.target.files[0].name">
                                
                                <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center text-elevate-primary mb-4 shadow-sm group-hover:scale-110 transition-transform duration-300">
                                    <i class="ph-duotone ph-upload-simple text-4xl"></i>
                                </div>
                                <h4 class="text-lg font-black text-elevate-dark mb-2">Pilih File Excel</h4>
                                <p class="text-sm text-slate-500 font-medium text-center">Tarik file ke sini atau klik untuk mencari.</p>
                                
                                <div x-show="fileName" style="display: none;" class="mt-6 px-4 py-2 bg-white border border-[#B7DFB9] rounded-xl flex items-center gap-2 text-[#107C10] shadow-sm">
                                    <i class="ph-fill ph-file-xls text-lg"></i>
                                    <span class="font-bold text-sm" x-text="fileName"></span>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 bg-[#FFEFD6] border border-[#FFD8A8] rounded-xl flex gap-3 mb-8 shadow-sm">
                            <i class="ph-fill ph-info text-[#D83B01] text-xl shrink-0 mt-0.5"></i>
                            <p class="text-xs text-[#D83B01] font-bold leading-relaxed">
                                Pastikan file yang diunggah sesuai format. Proses import mungkin memakan waktu beberapa saat tergantung jumlah data siswa.
                            </p>
                        </div>

                        <button type="submit" class="w-full py-4 bg-elevate-dark text-white font-bold rounded-2xl shadow-lg shadow-elevate-dark/30 hover:bg-elevate-primary transform active:scale-95 transition-all flex items-center justify-center gap-3 border border-transparent">
                            <span>Proses Import Data</span>
                            <i class="ph-bold ph-rocket-launch text-xl"></i>
                        </button>
                    </form>
                    
                    <div class="flex justify-between pt-8 mt-4 border-t border-slate-100">
                        <button type="button" @click="step = 1" class="px-6 py-4 text-slate-400 font-bold hover:text-elevate-dark transition-all flex items-center gap-2 rounded-xl hover:bg-elevate-soft">
                            <i class="ph-bold ph-arrow-left"></i> Kembali
                        </button>
                        <a href="<?php echo e(route('ppdb.create')); ?>" class="px-6 py-4 text-xs font-bold text-elevate-primary hover:text-white transition-colors flex items-center gap-2 bg-elevate-soft rounded-xl border border-slate-200 hover:bg-elevate-primary hover:border-elevate-primary">
                            Pendaftaran Reguler <i class="ph-bold ph-arrow-right"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <div class="mt-8 text-center pt-6">
            <p class="text-xs text-slate-400 font-bold tracking-widest uppercase">&copy; <?php echo e(date('Y')); ?> Panitia PPDB SMPN 3 Lakbok</p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/ppdb/collective.blade.php ENDPATH**/ ?>