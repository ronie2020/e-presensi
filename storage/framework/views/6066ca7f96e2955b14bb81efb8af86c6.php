<?php $__env->startSection('content'); ?>

<style>
    .glass-card {
        background: rgba(15, 23, 42, 0.7);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
    
    .tab-btn-dark { 
        @apply px-6 py-4 font-bold text-sm whitespace-nowrap transition-all border-b-2 flex items-center gap-2 outline-none; 
    }
    .tab-btn-active { 
        @apply text-blue-400 border-blue-500 bg-blue-500/5; 
    }
    .tab-btn-inactive { 
        @apply text-slate-500 border-transparent hover:text-slate-300 hover:bg-white/5; 
    }

    /* Custom File Input for Dark Theme */
    .file-input-dark::-webkit-file-upload-button {
        @apply bg-blue-600 text-white border-0 py-3 px-6 mr-4 rounded-xl font-bold cursor-pointer hover:bg-blue-500 transition-all;
    }
    
    .animate-fade-in { animation: fadeIn 0.5s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="min-h-screen w-full relative overflow-hidden bg-slate-950 font-sans pb-20 flex flex-col items-center justify-center py-10">
    
    
    <div class="absolute inset-0 z-0 pointer-events-none">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900"></div>
        <div class="absolute top-0 left-0 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
        <div class="absolute -top-40 -right-40 w-[600px] h-[600px] bg-blue-600/10 rounded-full blur-[120px]"></div>
        <div class="absolute -bottom-40 -left-40 w-[600px] h-[600px] bg-indigo-600/10 rounded-full blur-[120px]"></div>
    </div>

    <div class="relative z-10 w-full max-w-5xl px-4">
        
        
        <div class="text-center mb-10" data-aos="fade-down">
            <span class="inline-flex items-center gap-2 py-1.5 px-4 rounded-full bg-blue-500/10 text-blue-400 border border-blue-500/20 text-[10px] font-black uppercase tracking-[0.2em] mb-4">
                <span class="w-2 h-2 rounded-full bg-blue-500 animate-ping"></span>
                Fitur Guru / Kolektif
            </span>
            <h1 class="text-3xl md:text-5xl font-black text-white tracking-tight mb-4 text-shadow">
                Pendaftaran <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-indigo-500">Massal</span>
            </h1>
            <p class="text-slate-400 text-sm max-w-xl mx-auto">
                Khusus untuk Bapak/Ibu Guru pembimbing guna mempercepat proses input data siswa secara kolektif menggunakan format Excel.
            </p>
        </div>

        
        <div class="glass-card rounded-[2.5rem] overflow-hidden" x-data="{ step: 1 }">
            
            
            <div class="flex border-b border-white/5 bg-black/20 overflow-x-auto">
                <button type="button" @click="step = 1" :class="step === 1 ? 'tab-btn-active' : 'tab-btn-inactive'" class="tab-btn-dark w-1/2 justify-center">
                    <span class="w-6 h-6 rounded-full border-2 border-current flex items-center justify-center text-[10px]">1</span>
                    Unduh Template
                </button>
                <button type="button" @click="step = 2" :class="step === 2 ? 'tab-btn-active' : 'tab-btn-inactive'" class="tab-btn-dark w-1/2 justify-center">
                    <span class="w-6 h-6 rounded-full border-2 border-current flex items-center justify-center text-[10px]">2</span>
                    Unggah Data
                </button>
            </div>

            <div class="p-8 md:p-12 min-h-[400px]">
                
                
                <div x-show="step === 1" class="animate-fade-in h-full flex flex-col justify-between">
                    <div class="max-w-3xl mx-auto w-full">
                        <div class="mb-8 text-center md:text-left">
                            <h3 class="text-2xl font-bold text-white mb-2">Langkah 1: Siapkan Data Excel</h3>
                            <p class="text-slate-400 leading-relaxed text-sm">
                                Silakan unduh template Excel di bawah ini. Pastikan Anda <strong>tidak mengubah struktur kolom (header)</strong> yang ada agar sistem dapat memproses data dengan benar. Isi data siswa sesuai kolom yang tersedia.
                            </p>
                        </div>
                        
                        <div class="bg-gradient-to-br from-blue-900/40 to-slate-900/40 border border-blue-500/20 rounded-3xl p-6 md:p-8 flex flex-col md:flex-row items-center justify-between gap-6 hover:border-blue-500/40 transition-colors group">
                            <div class="flex items-center gap-5">
                                <div class="w-20 h-20 bg-blue-600/20 rounded-2xl flex items-center justify-center text-blue-400 border border-blue-500/30 group-hover:scale-110 transition-transform duration-300">
                                    <i class="ph-duotone ph-file-xls text-5xl"></i>
                                </div>
                                <div class="text-center md:text-left">
                                    <h4 class="text-white font-bold text-lg md:text-xl">Template_PPDB_2025.xlsx</h4>
                                    <p class="text-slate-500 text-xs uppercase tracking-wider font-bold mt-1">Format Resmi Sekolah</p>
                                </div>
                            </div>
                            <a href="<?php echo e(route('ppdb.download_template')); ?>" class="w-full md:w-auto px-8 py-4 bg-white text-blue-900 font-bold rounded-xl hover:bg-blue-50 transition-all flex items-center justify-center gap-2 shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                                <i class="ph-bold ph-download-simple text-xl"></i> Unduh Sekarang
                            </a>
                        </div>

                        <div class="mt-8 flex items-start gap-3 p-4 bg-amber-500/10 border border-amber-500/20 rounded-xl">
                            <i class="ph-fill ph-warning-circle text-amber-500 text-xl mt-0.5"></i>
                            <div>
                                <h5 class="text-amber-500 font-bold text-sm">Perhatian</h5>
                                <p class="text-amber-200/70 text-xs mt-1">Pastikan NISN siswa unik. Jika NISN sudah terdaftar sebelumnya, data siswa tersebut akan dilewati.</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-10 mt-4 border-t border-white/5">
                        <button type="button" @click="step = 2" class="px-8 py-4 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-500 transition-all flex items-center gap-2 shadow-lg shadow-blue-600/20">
                            Lanjut ke Unggah <i class="ph-bold ph-arrow-right"></i>
                        </button>
                    </div>
                </div>

                
                <div x-show="step === 2" class="animate-fade-in h-full flex flex-col justify-between" style="display: none;">
                    <div class="max-w-2xl mx-auto w-full">
                        <div class="text-center mb-8">
                            <h3 class="text-2xl font-bold text-white mb-2">Langkah 2: Import Data</h3>
                            <p class="text-slate-400 text-sm">Unggah file Excel yang telah selesai Anda lengkapi.</p>
                        </div>
                        
                        
                        <?php if(session('success')): ?>
                            <div class="mb-6 p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-xs font-bold flex items-start gap-3">
                                <i class="ph-fill ph-check-circle text-xl shrink-0"></i>
                                <div><?php echo e(session('success')); ?></div>
                            </div>
                        <?php endif; ?>

                        <?php if(session('error')): ?>
                            <div class="mb-6 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-bold flex items-start gap-3">
                                <i class="ph-fill ph-warning-circle text-xl shrink-0"></i>
                                <div><?php echo session('error'); ?></div>
                            </div>
                        <?php endif; ?>

                        <form action="<?php echo e(route('ppdb.import')); ?>" method="POST" enctype="multipart/form-data" class="space-y-6 bg-slate-900/40 p-6 rounded-3xl border border-white/5">
                            <?php echo csrf_field(); ?>
                            
                            <div class="relative group">
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-3 ml-1">Pilih File Excel (.xlsx / .xls)</label>
                                <div class="relative">
                                    <input type="file" name="file_excel" accept=".xlsx, .xls" required
                                        class="file-input-dark block w-full text-sm text-slate-300 border border-white/10 rounded-2xl bg-slate-950/50 p-2 focus:ring-4 focus:ring-blue-500/10 transition-all cursor-pointer hover:border-blue-500/30
                                    "/>
                                </div>
                                <p class="text-[10px] text-slate-500 mt-2 ml-1">* Maksimal ukuran file 5MB.</p>
                            </div>

                            <button type="submit" class="w-full py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-bold rounded-2xl shadow-lg shadow-blue-600/30 transform hover:-translate-y-1 transition-all flex items-center justify-center gap-3">
                                <span>Proses Import Data</span>
                                <i class="ph-bold ph-rocket-launch text-xl"></i>
                            </button>
                        </form>
                    </div>
                    
                    <div class="flex justify-between pt-10 mt-4 border-t border-white/5">
                        <button type="button" @click="step = 1" class="px-6 py-4 text-slate-400 font-bold hover:text-white transition-all flex items-center gap-2">
                            <i class="ph-bold ph-arrow-left"></i> Kembali
                        </button>
                        <a href="<?php echo e(route('ppdb.create')); ?>" class="px-6 py-4 text-xs font-bold text-blue-400 hover:text-blue-300 transition-colors flex items-center gap-2 bg-blue-500/5 rounded-xl border border-blue-500/10 hover:border-blue-500/30">
                            <i class="ph-bold ph-user"></i> Pendaftaran Reguler
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <div class="mt-8 text-center border-t border-white/5 pt-6">
            <p class="text-xs text-slate-500 font-medium tracking-wide">&copy; <?php echo e(date('Y')); ?> Panitia PPDB SMPN 3 Lakbok</p>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/ppdb/collective.blade.php ENDPATH**/ ?>