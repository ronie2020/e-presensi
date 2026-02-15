<?php $__env->startSection('content'); ?>


<style>
    @media print {
        body, .min-h-screen { 
            background: white !important; 
            height: auto !important; 
            overflow: visible !important; 
            display: block !important;
        }
        .no-print, .bg-ornaments { display: none !important; }
        .print-area {
            box-shadow: none !important;
            border: 2px solid #000 !important;
            background: white !important;
            color: black !important;
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            border-radius: 0 !important;
        }
        .text-white { color: black !important; }
        .text-slate-400 { color: #555 !important; }
        .bg-gradient-to-r { background: none !important; border-bottom: 2px solid #000; }
    }
    
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>

<div class="min-h-screen w-full flex flex-col items-center justify-center relative overflow-hidden bg-slate-50 font-sans py-10">
    
    
    <div class="fixed inset-0 z-0 pointer-events-none bg-ornaments overflow-hidden">
        <div class="absolute top-[-10%] right-[-5%] w-[600px] h-[600px] bg-blue-100/50 rounded-full blur-[100px] opacity-60 mix-blend-multiply"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[500px] h-[500px] bg-purple-100/50 rounded-full blur-[100px] opacity-60 mix-blend-multiply"></div>
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-30"></div>
    </div>

    <div class="relative z-10 w-full max-w-lg px-4">
        
        <div class="print-area bg-white rounded-[2.5rem] overflow-hidden shadow-2xl shadow-slate-200/50 border border-slate-100 animate-enter relative">
            
            
            <div class="h-2 w-full bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600"></div>

            <div class="p-8 md:p-10 text-center">
                
                <div class="w-24 h-24 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-6 text-emerald-600 shadow-sm border border-emerald-100">
                    <i class="ph-fill ph-check-circle text-6xl"></i>
                </div>
                
                <h1 class="text-2xl font-black text-slate-800 mb-2">Pendaftaran Berhasil!</h1>
                <p class="text-slate-500 font-medium text-sm mb-8 leading-relaxed">Data Anda telah tersimpan di sistem.<br>Simpan bukti pendaftaran ini.</p>

                
                <div class="bg-slate-50 rounded-[2rem] p-8 border border-slate-200 text-left mb-8 relative overflow-hidden group hover:border-blue-300 transition-colors">
                    
                    <div class="absolute -top-2 -right-2 opacity-5 group-hover:opacity-10 transition-opacity transform rotate-12">
                        <i class="ph-fill ph-ticket text-9xl text-slate-900"></i>
                    </div>

                    <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Nomor Registrasi</p>
                    <p class="text-4xl font-black text-blue-600 tracking-tighter mb-6"><?php echo e($registrant->registration_number); ?></p>

                    <div class="space-y-4 text-sm border-t border-slate-200 pt-6 relative z-10">
                        <div class="flex justify-between items-start">
                            <span class="text-slate-500 font-bold text-xs uppercase tracking-wide">Nama Siswa</span>
                            <span class="font-black text-slate-800 text-right w-1/2"><?php echo e($registrant->full_name); ?></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500 font-bold text-xs uppercase tracking-wide">Jalur</span>
                            <span class="font-bold text-xs text-white bg-blue-600 px-3 py-1 rounded-lg uppercase shadow-sm"><?php echo e($registrant->track); ?></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500 font-bold text-xs uppercase tracking-wide">Tanggal</span>
                            <span class="font-bold text-slate-700 font-mono"><?php echo e($registrant->created_at->format('d/m/Y H:i')); ?></span>
                        </div>
                    </div>
                </div>

                
                <div class="bg-amber-50 rounded-2xl p-4 text-left flex gap-3 border border-amber-100 mb-8">
                    <i class="ph-fill ph-info text-amber-600 text-xl shrink-0 mt-0.5"></i>
                    <p class="text-xs text-amber-900/80 leading-relaxed font-bold">
                        PENTING: Screenshot atau cetak halaman ini. Gunakan Nomor Registrasi untuk mengecek status kelulusan nanti.
                    </p>
                </div>

                
                <div class="flex flex-col sm:flex-row gap-3 no-print">
                    <a href="<?php echo e(url('/')); ?>" class="flex-1 py-3.5 bg-white text-slate-600 font-bold rounded-xl text-sm hover:bg-slate-50 transition text-center border border-slate-200 hover:border-slate-300">
                        Ke Beranda
                    </a>
                    <button onclick="window.print()" class="flex-1 py-3.5 bg-slate-900 text-white font-bold rounded-xl text-sm shadow-lg shadow-slate-900/20 hover:bg-blue-600 hover:shadow-blue-600/30 transition-all flex items-center justify-center gap-2">
                        <i class="ph-bold ph-printer text-lg"></i> Cetak Bukti
                    </button>
                </div>
            </div>
            
            <div class="bg-slate-50 p-4 text-center border-t border-slate-100">
                <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest">Panitia PPDB SMPN 3 Lakbok</p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.public', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/ppdb/success.blade.php ENDPATH**/ ?>