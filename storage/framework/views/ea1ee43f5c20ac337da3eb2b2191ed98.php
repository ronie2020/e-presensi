<?php $__env->startComponent('cbt.seb_landing'); ?>

    
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ activeTab: 'hp' }">
        
        <!-- HERO CARD -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-[#56bbf1] to-[#0d52a1] rounded-[2rem] shadow-xl shadow-[#56bbf1]/30 mb-6 text-white text-4xl transform hover:scale-110 transition duration-500 border border-white/20">
                <i class="ph-duotone ph-shield-check"></i>
            </div>
            <h3 class="text-3xl md:text-4xl font-black text-[#2c3f61] mb-3 tracking-tight">Portal Keamanan Ujian</h3>
            <p class="text-slate-500 text-lg max-w-lg mx-auto leading-relaxed">
                Pilih perangkat yang Anda gunakan. Sistem akan mengunci layar perangkat selama ujian berlangsung.
            </p>
        </div>

        <!-- SWITCHER DEVICE -->
        <div class="flex justify-center mb-10">
            <div class="bg-white p-1.5 rounded-[1.25rem] shadow-xl shadow-[#56bbf1]/10 border border-slate-100 flex relative">
                <button @click="activeTab = 'hp'" 
                    :class="activeTab === 'hp' ? 'bg-[#2c3f61] text-white shadow-md' : 'text-slate-400 hover:text-[#0d52a1] hover:bg-[#e5eff5]/50'"
                    class="px-8 py-3 rounded-[1rem] text-sm font-bold transition-all flex items-center gap-2.5">
                    <i class="ph-bold ph-device-mobile text-lg"></i> HP (Android/iOS)
                </button>
                <button @click="activeTab = 'laptop'" 
                    :class="activeTab === 'laptop' ? 'bg-[#2c3f61] text-white shadow-md' : 'text-slate-400 hover:text-[#0d52a1] hover:bg-[#e5eff5]/50'"
                    class="px-8 py-3 rounded-[1rem] text-sm font-bold transition-all flex items-center gap-2.5">
                    <i class="ph-bold ph-laptop text-lg"></i> Laptop / PC
                </button>
            </div>
        </div>

        <!-- CONTENT AREA -->
        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-[#56bbf1]/10 border border-slate-100 p-8 md:p-12 relative overflow-hidden">
            
            <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-[#e5eff5] to-[#f4d1c0] rounded-bl-[10rem] opacity-30 pointer-events-none"></div>

            <!-- TAMPILAN 1: UNTUK PENGGUNA HP (SEB MOBILE) -->
            <div x-show="activeTab === 'hp'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                
                <div class="flex flex-col md:flex-row items-start gap-8 md:gap-12 relative z-10">
                    <div class="flex-1 text-center md:text-left">
                        <span class="inline-block px-3 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-[10px] font-black uppercase tracking-wider mb-4 border border-emerald-200">Rekomendasi Utama</span>
                        <h4 class="text-2xl font-black text-[#2c3f61] mb-3">Mulai Ujian di HP</h4>
                        <p class="text-slate-500 mb-6 leading-relaxed text-sm font-medium">
                            Gunakan aplikasi <b>Safe Exam Browser (ETH Zurich)</b> atau Exambro sekolah. 
                        </p>
                        
                        <div class="flex flex-col gap-3">
                            <?php
                                $configUrl = route('cbt.download_seb', $exam->id ?? 0);
                                $deepLink = str_replace(['https://', 'http://'], ['sebs://', 'sebs://'], $configUrl);
                                $emergencyLink = route('student.exam.show', ['exam' => $exam->id ?? 0, 'strict_mode' => 1]);
                            ?>

                            
                            <a href="<?php echo e($deepLink); ?>" class="flex items-center justify-center px-6 py-4 bg-[#2c3f61] text-white font-bold rounded-2xl hover:bg-[#1c2940] transition shadow-xl shadow-[#2c3f61]/20 group w-full active:scale-95">
                                <i class="ph-bold ph-rocket-launch text-2xl mr-3 group-hover:-translate-y-1 transition text-[#56bbf1]"></i>
                                <div class="text-left">
                                    <span class="block text-[10px] uppercase font-bold text-[#56bbf1]">Paling Aman</span>
                                    <span class="block text-lg leading-none">Buka Aplikasi SEB</span>
                                </div>
                            </a>
                            
                            
                            <div class="flex justify-center gap-4 mt-2 mb-6">
                                <a href="https://play.google.com/store/apps/details?id=org.safeexambrowser.app" target="_blank" class="text-xs text-[#0d52a1] hover:underline font-bold flex items-center bg-[#e5eff5] px-3 py-1.5 rounded-lg border border-[#56bbf1]/30">
                                    <i class="ph-fill ph-google-play-logo mr-1"></i> Download SEB Resmi
                                </a>
                            </div>

                            <hr class="border-slate-100 mb-4">

                            
                            <div class="bg-[#f9a282]/10 border border-[#f9a282]/30 rounded-[1.5rem] p-4 text-left">
                                <h6 class="font-black text-[#c86845] flex items-center gap-2 text-sm mb-2">
                                    <i class="ph-fill ph-warning-circle text-lg"></i> HP Tidak Bisa Install App?
                                </h6>
                                <p class="text-xs text-[#c86845]/80 mb-3 leading-relaxed font-medium">
                                    Gunakan mode darurat via Chrome. Sistem akan mengawasi layar Anda. Jika berpindah tab/keluar, <b>ujian otomatis terkunci</b>.
                                </p>
                                
                                <a href="<?php echo e($emergencyLink); ?>" onclick="event.preventDefault(); confirmEmergencyMode('<?php echo e($emergencyLink); ?>')" 
                                   class="block w-full py-3 bg-white border border-[#f9a282]/50 hover:bg-[#f9a282]/20 text-[#c86845] font-bold rounded-xl text-sm text-center transition shadow-sm active:scale-95">
                                    Masuk Mode Darurat (Browser Biasa)
                                </a>
                            </div>

                        </div>
                    </div>
                    
                    
                    <div class="w-full md:w-auto bg-[#e5eff5]/50 p-6 rounded-[2rem] border border-[#56bbf1]/20 text-center flex flex-col items-center">
                        <h5 class="font-black text-[#0d52a1] mb-3 text-sm">Scan untuk Masuk</h5>
                        <div class="bg-white p-3 rounded-2xl border border-slate-200 shadow-sm">
                            <div id="qrcode" class="w-[140px] h-[140px] flex items-center justify-center"></div>
                        </div>
                        <p class="text-[10px] text-[#2c3f61]/60 font-bold mt-3 max-w-[150px] leading-tight">
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
                
                <div class="flex flex-col md:flex-row items-center gap-8 md:gap-12 relative z-10">
                    <div class="flex-1 text-center md:text-left">
                        <span class="inline-block px-3 py-1 bg-[#56bbf1]/20 text-[#0d52a1] rounded-lg text-[10px] font-black uppercase tracking-wider mb-4 border border-[#56bbf1]/30">Lab Komputer / PC</span>
                        <h4 class="text-2xl font-black text-[#2c3f61] mb-3">Safe Exam Browser (SEB)</h4>
                        <p class="text-slate-500 mb-6 leading-relaxed text-sm font-medium">
                            Pastikan software SEB sudah terinstall di laptop/komputer. Download file konfigurasi di bawah, lalu buka file tersebut (Double Click).
                        </p>
                        
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="<?php echo e(route('cbt.download_seb', $exam->id ?? 0)); ?>" class="flex-1 flex items-center justify-center px-6 py-4 bg-[#0d52a1] text-white font-bold rounded-2xl hover:bg-[#0a4282] transition shadow-xl shadow-[#0d52a1]/30 group active:scale-95 border border-transparent">
                                <i class="ph-bold ph-file-lock text-2xl mr-3 group-hover:rotate-12 transition"></i>
                                <div class="text-left">
                                    <span class="block text-[10px] uppercase font-bold text-[#56bbf1]">File Masuk</span>
                                    <span class="block text-sm leading-none">Download Config (.seb)</span>
                                </div>
                            </a>
                        </div>
                    </div>
                    
                    
                    <div class="w-full md:w-64 flex justify-center opacity-70">
                        <i class="ph-duotone ph-laptop text-[8rem] text-[#56bbf1]/40"></i>
                    </div>
                </div>
            </div>
        </div>
        
         <div class="mt-10 text-center">
            <a href="<?php echo e(route('dashboard')); ?>" class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-[#0d52a1] transition py-2 px-4 rounded-xl hover:bg-white border border-transparent hover:border-slate-200 shadow-sm hover:shadow-md">
                <i class="ph-bold ph-arrow-left"></i> Kembali ke Dashboard Utama
            </a>
        </div>
    </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    function confirmEmergencyMode(url) {
        Swal.fire({
            title: 'PERINGATAN KERAS!',
            html: '<div class="text-left text-sm mt-2 space-y-2"><p>Sistem pengawasan layar akan aktif.</p><p>Jika Anda mencoba membuka <b class="text-rose-600">WA, Google, atau Notifikasi</b>, ujian akan langsung <b class="text-rose-600">TERKUNCI OTOMATIS</b>.</p></div>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: '<i class="ph-bold ph-check-circle"></i> Ya, Saya Paham',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-[2rem]',
                confirmButton: 'rounded-xl font-bold px-6 py-3',
                cancelButton: 'rounded-xl font-bold px-6 py-3'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memproses...', text: 'Membuka Mode Darurat', allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading() }, customClass: { popup: 'rounded-[2rem]' }
                });
                window.location.href = url;
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        var deepLinkUrl = "<?php echo e($deepLink); ?>";
        var qrContainer = document.getElementById("qrcode");
        
        if (qrContainer) {
            qrContainer.innerHTML = "";
            new QRCode(qrContainer, {
                text: deepLinkUrl,
                width: 140,
                height: 140,
                colorDark : "#2c3f61",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.M
            });
        }
    });
</script>
<?php echo $__env->renderComponent(); ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/cbt/seb_info.blade.php ENDPATH**/ ?>