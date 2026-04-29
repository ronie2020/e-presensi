<?php $__env->startSection('content'); ?>

<style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

    .file-drop-zone {
        background-color: #f8fafc;
        background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' rx='24' ry='24' stroke='%23cbd5e1' stroke-width='2' stroke-dasharray='12%2c 12' stroke-dashoffset='0' stroke-linecap='square'/%3e%3c/svg%3e");
        transition: all 0.3s ease;
    }
    .file-drop-zone:hover {
        background-color: #e5eff5; /* elevate-soft */
        background-image: url("data:image/svg+xml,%3csvg width='100%25' height='100%25' xmlns='http://www.w3.org/2000/svg'%3e%3crect width='100%25' height='100%25' fill='none' rx='24' ry='24' stroke='%230d52a1' stroke-width='2' stroke-dasharray='12%2c 12' stroke-dashoffset='0' stroke-linecap='square'/%3e%3c/svg%3e"); /* elevate-primary stroke */
    }
</style>

<div class="min-h-screen w-full relative overflow-hidden bg-elevate-surface font-sans pb-20 flex flex-col items-center justify-center py-10">
    
    
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute top-[-10%] left-[-5%] w-[600px] h-[600px] bg-elevate-primary/10 rounded-full blur-[100px] opacity-60 mix-blend-multiply"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[500px] h-[500px] bg-elevate-peach-light/40 rounded-full blur-[100px] opacity-60 mix-blend-multiply"></div>
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-30"></div>
    </div>

    <div class="relative z-10 w-full max-w-5xl px-4">
        
        
        <div class="text-center mb-10 animate-enter">
            <div class="inline-flex items-center gap-2 py-1.5 px-4 rounded-full bg-white border border-elevate-accent/30 mb-6 shadow-sm">
                <span class="w-2.5 h-2.5 rounded-full bg-elevate-primary animate-pulse"></span>
                <span class="text-xs font-black text-elevate-primary tracking-wider uppercase">Mode Guru / Kolektif</span>
            </div>
            <h1 class="text-3xl md:text-5xl font-black text-elevate-dark tracking-tight mb-4">
                Pendaftaran <span class="text-transparent bg-clip-text bg-gradient-to-r from-elevate-primary to-elevate-accent">Massal</span>
            </h1>
            <p class="text-slate-500 text-sm font-medium max-w-xl mx-auto">
                Fitur khusus pembimbing untuk mempercepat proses input data siswa secara kolektif via Excel.
            </p>
        </div>

        <div class="bg-white rounded-[2.5rem] overflow-hidden shadow-2xl shadow-elevate-dark/10 border border-slate-100 animate-enter" style="animation-delay: 100ms" x-data="{ step: 1 }">
            
            
            <div class="border-b border-slate-100 bg-slate-50/50 p-2">
                <div class="flex gap-2">
                    <button @click="step = 1" class="flex-1 py-3 px-4 rounded-xl flex items-center justify-center gap-3 transition-all"
                        :class="step === 1 ? 'bg-white text-elevate-primary shadow-sm ring-1 ring-slate-200' : 'text-slate-400 hover:bg-white hover:text-elevate-dark'">
                        <span class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-bold" 
                              :class="step === 1 ? 'bg-elevate-primary text-white' : 'bg-slate-200 text-slate-500'">1</span>
                        <span class="text-sm font-bold">Unduh Template</span>
                    </button>
                    <button @click="step = 2" class="flex-1 py-3 px-4 rounded-xl flex items-center justify-center gap-3 transition-all"
                        :class="step === 2 ? 'bg-white text-elevate-primary shadow-sm ring-1 ring-slate-200' : 'text-slate-400 hover:bg-white hover:text-elevate-dark'">
                        <span class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-bold" 
                              :class="step === 2 ? 'bg-elevate-primary text-white' : 'bg-slate-200 text-slate-500'">2</span>
                        <span class="text-sm font-bold">Unggah Data</span>
                    </button>
                </div>
            </div>

            <div class="p-8 md:p-12 min-h-[400px]">
                
                
                <div x-show="step === 1" class="animate-enter h-full flex flex-col justify-between">
                    <div class="max-w-3xl mx-auto w-full">
                        <div class="mb-8 text-center md:text-left">
                            <h3 class="text-2xl font-black text-elevate-dark mb-2">Persiapan Data</h3>
                            <p class="text-slate-500 leading-relaxed text-sm font-medium">
                                Unduh template Excel berikut. <strong>Jangan ubah header kolom</strong> agar sistem dapat membaca data dengan akurat.
                            </p>
                        </div>
                        
                        <div class="bg-slate-50 border border-slate-200 rounded-[2rem] p-6 flex flex-col md:flex-row items-center justify-between gap-6 hover:border-emerald-200 hover:shadow-lg hover:shadow-emerald-100/50 transition-all group">
                            <div class="flex items-center gap-5">
                                <div class="w-20 h-20 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600 border border-emerald-100 group-hover:scale-110 transition-transform duration-300 shadow-sm">
                                    <i class="ph-duotone ph-microsoft-excel-logo text-5xl"></i>
                                </div>
                                <div class="text-center md:text-left">
                                    <h4 class="text-elevate-dark font-black text-lg">Template_PPDB_2025.xlsx</h4>
                                    <p class="text-slate-400 text-xs uppercase tracking-wider font-bold mt-1">Format Resmi Sekolah</p>
                                </div>
                            </div>
                            <a href="<?php echo e(route('ppdb.download_template')); ?>" class="px-8 py-4 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition-all flex items-center gap-2 shadow-lg shadow-emerald-600/20 transform hover:-translate-y-1">
                                <i class="ph-bold ph-download-simple text-xl"></i> Unduh File
                            </a>
                        </div>
                    </div>

                    <div class="flex justify-end pt-8 mt-4 border-t border-slate-100">
                        <button type="button" @click="step = 2" class="px-8 py-4 bg-elevate-dark text-white font-bold rounded-xl hover:bg-elevate-primary transition-all flex items-center gap-2 shadow-lg shadow-elevate-dark/20 hover:shadow-elevate-primary/30">
                            Lanjut Unggah <i class="ph-bold ph-arrow-right"></i>
                        </button>
                    </div>
                </div>

                
                <div x-show="step === 2" class="animate-enter h-full flex flex-col justify-between" style="display: none;">
                    <div class="max-w-2xl mx-auto w-full">
                        <div class="text-center mb-8">
                            <h3 class="text-2xl font-black text-elevate-dark mb-2">Import Data Siswa</h3>
                            <p class="text-slate-500 text-sm font-medium">Upload file Excel yang sudah diisi.</p>
                        </div>
                        
                        <?php if(session('success')): ?>
                            <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-600 text-xs font-bold flex items-center gap-3">
                                <i class="ph-fill ph-check-circle text-xl shrink-0"></i> <?php echo e(session('success')); ?>

                            </div>
                        <?php endif; ?>

                        <?php if(session('error')): ?>
                            <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-100 text-rose-600 text-xs font-bold flex items-center gap-3">
                                <i class="ph-fill ph-warning-circle text-xl shrink-0"></i> <?php echo session('error'); ?>

                            </div>
                        <?php endif; ?>

                        <form action="<?php echo e(route('ppdb.import')); ?>" method="POST" enctype="multipart/form-data" class="space-y-6">
                            <?php echo csrf_field(); ?>
                            
                            <div class="relative group">
                                <div class="file-drop-zone h-48 rounded-[2rem] flex flex-col items-center justify-center cursor-pointer relative overflow-hidden">
                                    <input type="file" name="file_excel" accept=".xlsx, .xls" required 
                                           class="absolute inset-0 opacity-0 cursor-pointer z-10"
                                           onchange="document.getElementById('file-name').textContent = this.files[0].name">
                                    
                                    <div class="text-center pointer-events-none transition-transform group-hover:scale-110 duration-300">
                                        <i class="ph-duotone ph-cloud-arrow-up text-5xl text-slate-400 mb-3 group-hover:text-elevate-primary transition-colors"></i>
                                        <p class="text-elevate-dark font-bold text-sm">Klik atau geser file ke sini</p>
                                        <p class="text-slate-400 text-xs mt-1 font-medium" id="file-name">Format .xlsx / .xls (Max 5MB)</p>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="w-full py-4 bg-elevate-dark hover:bg-elevate-primary text-white font-bold rounded-xl shadow-lg shadow-elevate-dark/20 hover:shadow-elevate-primary/30 transform hover:-translate-y-1 transition-all flex items-center justify-center gap-3">
                                <span>Proses Import Data</span>
                                <i class="ph-bold ph-rocket-launch text-xl"></i>
                            </button>
                        </form>
                    </div>
                    
                    <div class="flex justify-between pt-8 mt-4 border-t border-slate-100">
                        <button type="button" @click="step = 1" class="px-6 py-4 text-slate-400 font-bold hover:text-elevate-dark transition-all flex items-center gap-2 rounded-xl hover:bg-slate-50">
                            <i class="ph-bold ph-arrow-left"></i> Kembali
                        </button>
                        <a href="<?php echo e(route('ppdb.create')); ?>" class="px-6 py-4 text-xs font-bold text-elevate-primary hover:text-elevate-dark transition-colors flex items-center gap-2 bg-elevate-soft rounded-xl border border-elevate-accent/30 hover:border-elevate-primary">
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
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/ppdb/collective.blade.php ENDPATH**/ ?>