
<?php $__env->startComponent('cbt.seb_landing'); ?>

    
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ activeTab: 'hp' }">
        
        <!-- HERO CARD -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-[2rem] shadow-xl shadow-blue-500/30 mb-6 text-white text-4xl transform hover:scale-110 transition duration-500">
                <i class="ph-duotone ph-shield-check"></i>
            </div>
            <h3 class="text-3xl md:text-4xl font-black text-slate-800 mb-3 tracking-tight">Portal Keamanan Ujian</h3>
            <p class="text-slate-500 text-lg max-w-lg mx-auto leading-relaxed">
                Pilih perangkat yang Anda gunakan. Sistem akan mengunci perangkat selama ujian berlangsung.
            </p>
        </div>

        <!-- SWITCHER DEVICE -->
        <div class="flex justify-center mb-10">
            <div class="bg-white p-1.5 rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 flex relative">
                <button @click="activeTab = 'hp'" 
                    :class="activeTab === 'hp' ? 'bg-slate-900 text-white shadow-md' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"
                    class="px-8 py-3 rounded-xl text-sm font-bold transition-all flex items-center gap-2.5">
                    <i class="ph-bold ph-device-mobile text-lg"></i> HP (Android/iOS)
                </button>
                <button @click="activeTab = 'laptop'" 
                    :class="activeTab === 'laptop' ? 'bg-slate-900 text-white shadow-md' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"
                    class="px-8 py-3 rounded-xl text-sm font-bold transition-all flex items-center gap-2.5">
                    <i class="ph-bold ph-laptop text-lg"></i> Laptop / PC
                </button>
            </div>
        </div>

        <!-- CONTENT AREA -->
        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-100 p-8 md:p-12 relative overflow-hidden">
            
            <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-blue-50 to-purple-50 rounded-bl-[10rem] opacity-50 pointer-events-none"></div>

            <!-- TAMPILAN 1: UNTUK PENGGUNA HP (SEB MOBILE) -->
            <div x-show="activeTab === 'hp'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                
                <div class="flex flex-col md:flex-row items-start gap-8 md:gap-12">
                    <div class="flex-1 text-center md:text-left">
                        <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold uppercase tracking-wider mb-4 border border-green-200">Rekomendasi Utama</span>
                        <h4 class="text-2xl font-black text-slate-800 mb-3">Mulai Ujian di HP</h4>
                        <p class="text-slate-500 mb-6 leading-relaxed text-sm">
                            Gunakan aplikasi <b>Safe Exam Browser (ETH Zurich)</b> atau Exambro sekolah. 
                        </p>
                        
                        <div class="flex flex-col gap-3">
                            <?php
                                $configUrl = route('cbt.download_seb', $exam->id ?? 0);
                                $deepLink = str_replace(['https://', 'http://'], ['sebs://', 'sebs://'], $configUrl);
                                // Link Manual (Biasa)
                                $manualLink = route('student.exam.show', $exam->id ?? 0);
                                // Link Darurat (Strict Mode) - Menambahkan parameter bypass
                                $emergencyLink = route('student.exam.show', ['exam' => $exam->id ?? 0, 'strict_mode' => 1]);
                            ?>

                            
                            <a href="<?php echo e($deepLink); ?>" class="flex items-center justify-center px-6 py-4 bg-slate-900 text-white font-bold rounded-2xl hover:bg-slate-800 transition shadow-xl shadow-slate-900/20 group w-full">
                                <i class="ph-bold ph-rocket-launch text-2xl mr-3 group-hover:-translate-y-1 transition"></i>
                                <div class="text-left">
                                    <span class="block text-[10px] uppercase font-bold text-slate-400">Paling Aman</span>
                                    <span class="block text-lg leading-none">Buka Aplikasi SEB</span>
                                </div>
                            </a>
                            
                            
                            <div class="flex justify-center gap-4 mt-2 mb-6">
                                <a href="https://play.google.com/store/apps/details?id=org.safeexambrowser.app" target="_blank" class="text-xs text-blue-600 hover:underline font-semibold flex items-center">
                                    <i class="ph-fill ph-google-play-logo mr-1"></i> Download SEB Resmi
                                </a>
                            </div>

                            <hr class="border-slate-200 mb-4">

                            
                            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-left">
                                <h6 class="font-bold text-amber-800 flex items-center gap-2 text-sm mb-2">
                                    <i class="ph-bold ph-warning-circle text-lg"></i> HP Tidak Bisa Install App?
                                </h6>
                                <p class="text-xs text-amber-700 mb-3 leading-relaxed">
                                    Gunakan mode darurat via Chrome. Sistem akan mengawasi layar Anda. Jika berpindah tab/keluar, <b>ujian otomatis terkunci</b>.
                                </p>
                                <a href="<?php echo e($emergencyLink); ?>" onclick="return confirm('PERINGATAN KERAS:\n\nSistem pengawasan ketat akan aktif.\nJika Anda mencoba membuka WA, Google, atau Notifikasi, ujian akan langsung DIHENTIKAN.\n\nApakah Anda yakin ingin lanjut?')" 
                                   class="block w-full py-2.5 bg-white border border-amber-300 hover:bg-amber-100 text-amber-700 font-bold rounded-lg text-sm text-center transition shadow-sm">
                                    Masuk Mode Darurat (Tanpa Aplikasi)
                                </a>
                            </div>

                        </div>
                    </div>
                    
                    
                    <div class="w-full md:w-auto bg-slate-50 p-6 rounded-[2rem] border border-slate-100 text-center flex flex-col items-center">
                        <h5 class="font-bold text-slate-800 mb-3 text-sm">Scan untuk Masuk</h5>
                        <div class="bg-white p-3 rounded-xl border border-slate-200 shadow-sm">
                            <div id="qrcode" class="w-[140px] h-[140px] flex items-center justify-center"></div>
                        </div>
                        <p class="text-[10px] text-slate-400 mt-3 max-w-[150px] leading-tight">
                            Buka kamera HP Anda dan scan QR ini untuk langsung masuk.
                        </p>
                    </div>
                </div>
            </div>

            <!-- TAMPILAN 2: UNTUK PENGGUNA LAPTOP (SEB DESKTOP) -->
            <div x-show="activeTab === 'laptop'" style="display: none;" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                
                <div class="flex flex-col md:flex-row items-center gap-8 md:gap-12">
                    <div class="flex-1 text-center md:text-left">
                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-bold uppercase tracking-wider mb-4 border border-blue-200">Lab Komputer</span>
                        <h4 class="text-2xl font-black text-slate-800 mb-3">Safe Exam Browser (SEB)</h4>
                        <p class="text-slate-500 mb-6 leading-relaxed text-sm">
                            Pastikan software SEB sudah terinstall. Download file konfigurasi di bawah, lalu buka file tersebut (Double Click).
                        </p>
                        
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="<?php echo e(route('cbt.download_seb', $exam->id ?? 0)); ?>" class="flex-1 flex items-center justify-center px-6 py-4 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 transition shadow-xl shadow-blue-600/30 group">
                                <i class="ph-bold ph-file-lock text-2xl mr-3 group-hover:rotate-12 transition"></i>
                                <div class="text-left">
                                    <span class="block text-[10px] uppercase font-bold text-blue-200">File Masuk</span>
                                    <span class="block text-sm leading-none">Download Config (.seb)</span>
                                </div>
                            </a>
                        </div>
                    </div>
                    
                    
                    <div class="w-full md:w-64 flex justify-center opacity-80">
                        <i class="ph-duotone ph-laptop text-[8rem] text-slate-200"></i>
                    </div>
                </div>
            </div>
        </div>
        
         <div class="mt-10 text-center">
            <a href="<?php echo e(route('dashboard')); ?>" class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-slate-700 transition py-2 px-4 rounded-lg hover:bg-slate-100">
                <i class="ph-bold ph-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var deepLinkUrl = "<?php echo e($deepLink); ?>";
        var qrContainer = document.getElementById("qrcode");
        
        if (qrContainer) {
            qrContainer.innerHTML = "";
            new QRCode(qrContainer, {
                text: deepLinkUrl,
                width: 140,
                height: 140,
                colorDark : "#000000",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.M
            });
        }
    });
</script>

<?php echo $__env->renderComponent(); ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\cbt\seb_info.blade.php ENDPATH**/ ?>