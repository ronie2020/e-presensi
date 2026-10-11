@component('cbt.seb_landing')

    {{-- KONTEN UTAMA --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ activeTab: 'hp' }">
        
        <!-- HERO CARD -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-sky-400 to-blue-600 rounded-[2rem] shadow-xl shadow-sky-500/30 mb-6 text-white text-4xl border border-sky-300/30 transform hover:scale-110 transition duration-500">
                <i class="ph-duotone ph-shield-check"></i>
            </div>
            <h3 class="text-3xl md:text-5xl font-black text-white mb-3 tracking-tight">
                Portal Keamanan <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 via-sky-200 to-blue-400">Ujian</span>
            </h3>
            <p class="text-slate-300 text-sm md:text-base max-w-lg mx-auto leading-relaxed font-medium">
                Pilih perangkat yang Anda gunakan. Sistem akan mengunci layar perangkat selama ujian berlangsung.
            </p>
        </div>

        <!-- SWITCHER DEVICE -->
        <div class="flex justify-center mb-10">
            <div class="bg-slate-900/80 p-1.5 rounded-[1.5rem] shadow-2xl border border-white/15 flex relative backdrop-blur-md">
                <button @click="activeTab = 'hp'" 
                    :class="activeTab === 'hp' ? 'bg-gradient-to-r from-sky-500 to-blue-600 text-white shadow-lg shadow-sky-500/25' : 'text-slate-400 hover:text-white'"
                    class="px-8 py-3 rounded-2xl text-sm font-bold transition-all flex items-center gap-2.5">
                    <i class="ph-bold ph-device-mobile text-lg"></i> HP (Android/iOS)
                </button>
                <button @click="activeTab = 'laptop'" 
                    :class="activeTab === 'laptop' ? 'bg-gradient-to-r from-sky-500 to-blue-600 text-white shadow-lg shadow-sky-500/25' : 'text-slate-400 hover:text-white'"
                    class="px-8 py-3 rounded-2xl text-sm font-bold transition-all flex items-center gap-2.5">
                    <i class="ph-bold ph-laptop text-lg"></i> Laptop / PC
                </button>
            </div>
        </div>

        <!-- CONTENT AREA -->
        <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 p-8 md:p-12 relative overflow-hidden backdrop-blur-xl">
            {{-- Background Accent --}}
            <div class="absolute top-0 right-0 w-64 h-64 bg-sky-500/10 rounded-bl-[10rem] pointer-events-none blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-600/10 rounded-tr-[10rem] pointer-events-none blur-2xl"></div>

            <!-- TAMPILAN 1: UNTUK PENGGUNA HP (SEB MOBILE) -->
            <div x-show="activeTab === 'hp'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                
                <div class="flex flex-col md:flex-row items-start gap-8 md:gap-12 relative z-10">
                    <div class="flex-1 text-center md:text-left">
                        <span class="inline-block px-3 py-1 bg-emerald-500/20 text-emerald-300 rounded-full text-[10px] font-black uppercase tracking-wider mb-4 border border-emerald-500/30">
                            Rekomendasi Utama
                        </span>
                        <h4 class="text-2xl font-black text-white mb-2">Mulai Ujian di HP</h4>
                        <p class="text-slate-300 mb-6 leading-relaxed text-sm font-medium">
                            Gunakan aplikasi <b class="text-sky-300">Safe Exam Browser (ETH Zurich)</b> atau Exambro sekolah. 
                        </p>
                        
                        <div class="flex flex-col gap-3">
                            @php
                                $configUrl = route('cbt.download_seb', $exam->id ?? 0);
                                $deepLink = str_replace(['https://', 'http://'], ['sebs://', 'sebs://'], $configUrl);
                                $emergencyLink = route('student.exam.show', ['exam' => $exam->id ?? 0, 'strict_mode' => 1]);
                            @endphp

                            {{-- Tombol 1: Buka Otomatis (Deep Link) --}}
                            <a href="{{ $deepLink }}" class="flex items-center justify-center px-6 py-4 bg-gradient-to-r from-sky-500 to-blue-600 text-white font-bold rounded-2xl hover:from-sky-400 hover:to-blue-500 transition shadow-xl shadow-sky-500/25 group w-full active:scale-95 border border-transparent">
                                <i class="ph-bold ph-rocket-launch text-2xl mr-3 group-hover:-translate-y-1 transition text-sky-200"></i>
                                <div class="text-left">
                                    <span class="block text-[10px] uppercase font-bold text-sky-200">Paling Aman</span>
                                    <span class="block text-lg leading-none">Buka Aplikasi SEB</span>
                                </div>
                            </a>
                            
                            {{-- Link Download Aplikasi --}}
                            <div class="flex justify-center gap-4 mt-2 mb-6">
                                <a href="https://play.google.com/store/apps/details?id=org.safeexambrowser.app" target="_blank" class="text-xs text-sky-300 hover:text-white font-bold flex items-center bg-slate-900/80 px-4 py-2 rounded-xl border border-white/10 hover:border-sky-400/40 transition">
                                    <i class="ph-fill ph-google-play-logo mr-1.5 text-emerald-400"></i> Download SEB Resmi
                                </a>
                            </div>

                            <hr class="border-white/10 mb-4">

                            {{-- OPSI DARURAT --}}
                            <div class="bg-amber-500/10 border border-amber-500/30 rounded-[1.5rem] p-5 text-left">
                                <h6 class="font-black text-amber-300 flex items-center gap-2 text-sm mb-2">
                                    <i class="ph-fill ph-warning-circle text-lg"></i> HP Tidak Bisa Install App?
                                </h6>
                                <p class="text-xs text-amber-200/80 mb-3 leading-relaxed font-medium">
                                    Gunakan mode darurat via Chrome. Sistem akan mengawasi layar Anda. Jika berpindah tab/keluar, <b>ujian otomatis terkunci</b>.
                                </p>
                                
                                <a href="{{ $emergencyLink }}" onclick="event.preventDefault(); confirmEmergencyMode('{{ $emergencyLink }}')" 
                                   class="block w-full py-3 bg-slate-900/90 border border-amber-400/40 hover:bg-amber-500/20 text-amber-300 font-bold rounded-xl text-sm text-center transition shadow-sm active:scale-95">
                                    Masuk Mode Darurat (Browser Biasa)
                                </a>
                            </div>

                        </div>
                    </div>
                    
                    {{-- QR Code Section --}}
                    <div class="w-full md:w-auto bg-slate-900/80 p-6 rounded-[2rem] border border-white/10 text-center flex flex-col items-center shadow-inner">
                        <h5 class="font-black text-sky-300 mb-3 text-sm">Scan untuk Masuk</h5>
                        <div class="bg-white p-3 rounded-2xl border border-white shadow-md">
                            <div id="qrcode" class="w-[140px] h-[140px] flex items-center justify-center"></div>
                        </div>
                        <p class="text-[10px] text-slate-400 font-bold mt-3 max-w-[150px] leading-tight">
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
                        <span class="inline-block px-3 py-1 bg-sky-500/20 text-sky-300 rounded-full text-[10px] font-black uppercase tracking-wider mb-4 border border-sky-400/30">
                            Lab Komputer / PC
                        </span>
                        <h4 class="text-2xl font-black text-white mb-2">Safe Exam Browser (SEB)</h4>
                        <p class="text-slate-300 mb-6 leading-relaxed text-sm font-medium">
                            Pastikan software SEB sudah terinstall di laptop/komputer. Download file konfigurasi di bawah, lalu buka file tersebut (Double Click).
                        </p>
                        
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('cbt.download_seb', $exam->id ?? 0) }}" class="flex-1 flex items-center justify-center px-6 py-4 bg-gradient-to-r from-sky-500 to-blue-600 text-white font-bold rounded-2xl hover:from-sky-400 hover:to-blue-500 transition shadow-xl shadow-sky-500/25 group active:scale-95 border border-transparent">
                                <i class="ph-bold ph-file-lock text-2xl mr-3 group-hover:rotate-12 transition"></i>
                                <div class="text-left">
                                    <span class="block text-[10px] uppercase font-bold text-sky-200">File Masuk</span>
                                    <span class="block text-sm leading-none">Download Config (.seb)</span>
                                </div>
                            </a>
                        </div>
                    </div>
                    
                    {{-- Ilustrasi --}}
                    <div class="w-full md:w-64 flex justify-center opacity-70">
                        <i class="ph-duotone ph-laptop text-[8rem] text-sky-400/30"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-10 text-center">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-white transition py-2.5 px-5 rounded-xl bg-slate-900/80 border border-white/10 shadow-sm hover:border-white/20">
                <i class="ph-bold ph-arrow-left"></i> Kembali ke Dashboard Utama
            </a>
        </div>
    </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    function confirmEmergencyMode(url) {
        Swal.fire({
            title: 'PERINGATAN KERAS!',
            html: '<div class="text-left text-sm mt-2 space-y-2"><p class="text-slate-300">Sistem pengawasan layar akan aktif.</p><p class="text-rose-400">Jika Anda mencoba membuka <b>WA, Google, atau Notifikasi</b>, ujian akan langsung <b>TERKUNCI OTOMATIS</b>.</p></div>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#475569',
            confirmButtonText: '<i class="ph-bold ph-check-circle"></i> Ya, Saya Paham',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-[2rem] bg-slate-900 text-white border border-white/10',
                confirmButton: 'rounded-xl font-bold px-6 py-3',
                cancelButton: 'rounded-xl font-bold px-6 py-3'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Memproses...', text: 'Membuka Mode Darurat', allowOutsideClick: false,
                    didOpen: () => { Swal.showLoading() }, 
                    customClass: { popup: 'rounded-[2rem] bg-slate-900 text-white border border-white/10' }
                });
                window.location.href = url;
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        var deepLinkUrl = "{{ $deepLink }}";
        var qrContainer = document.getElementById("qrcode");
        
        if (qrContainer) {
            qrContainer.innerHTML = "";
            new QRCode(qrContainer, {
                text: deepLinkUrl,
                width: 140,
                height: 140,
                colorDark : "#021124",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.M
            });
        }
    });
</script>
@endcomponent