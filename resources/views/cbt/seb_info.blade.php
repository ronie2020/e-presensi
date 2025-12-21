{{-- 
    FILE TAMPILAN (VIEW)
    Ini adalah apa yang dilihat siswa jika mereka belum menggunakan Aplikasi Ujian.
--}}
@component('cbt.seb_landing')

    {{-- KONTEN UTAMA --}}
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8" x-data="{ activeTab: 'hp' }">
        
        <!-- HERO CARD -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-[2rem] shadow-xl shadow-blue-500/30 mb-6 text-white text-4xl transform hover:scale-110 transition duration-500">
                <i class="ph-duotone ph-shield-check"></i>
            </div>
            <h3 class="text-3xl md:text-4xl font-black text-slate-800 mb-3 tracking-tight">Portal Ujian Sekolah</h3>
            <p class="text-slate-500 text-lg max-w-lg mx-auto leading-relaxed">
                Silakan pilih perangkat yang Anda gunakan untuk memulai ujian dengan aman.
            </p>
        </div>

        <!-- SWITCHER DEVICE -->
        <div class="flex justify-center mb-10">
            <div class="bg-white p-1.5 rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 flex relative">
                <button @click="activeTab = 'hp'" 
                    :class="activeTab === 'hp' ? 'bg-slate-900 text-white shadow-md' : 'text-slate-500 hover:text-slate-800 hover:bg-slate-50'"
                    class="px-8 py-3 rounded-xl text-sm font-bold transition-all flex items-center gap-2.5">
                    <i class="ph-bold ph-device-mobile text-lg"></i> HP (Android)
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
            {{-- Background Accent --}}
            <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-blue-50 to-purple-50 rounded-bl-[10rem] opacity-50 pointer-events-none"></div>

            <!-- TAMPILAN 1: UNTUK PENGGUNA HP (APK) -->
            <div x-show="activeTab === 'hp'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                
                <div class="flex flex-col md:flex-row items-center gap-8 md:gap-12">
                    <div class="flex-1 text-center md:text-left">
                        <span class="inline-block px-3 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold uppercase tracking-wider mb-4 border border-green-200">Disarankan</span>
                        <h4 class="text-2xl font-black text-slate-800 mb-3">Aplikasi Exambrowser Android</h4>
                        <p class="text-slate-500 mb-6 leading-relaxed">
                            Aplikasi wajib untuk pengguna Android. Mencegah kecurangan, memblokir notifikasi, dan lebih hemat kuota internet.
                        </p>
                        
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="#" target="_blank" class="flex-1 flex items-center justify-center px-6 py-4 bg-slate-900 text-white font-bold rounded-2xl hover:bg-slate-800 transition shadow-xl shadow-slate-900/20 group">
                                <i class="ph-fill ph-google-play-logo text-2xl mr-3 group-hover:scale-110 transition"></i>
                                <div class="text-left">
                                    <span class="block text-[10px] uppercase font-bold text-slate-400">Download via</span>
                                    <span class="block text-sm leading-none">Google Drive</span>
                                </div>
                            </a>
                        </div>
                    </div>
                    
                    {{-- Opsi Darurat --}}
                    <div class="w-full md:w-72 bg-slate-50 p-6 rounded-[2rem] border border-slate-100 text-center">
                        <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="ph-bold ph-warning text-2xl"></i>
                        </div>
                        <h5 class="font-bold text-slate-800 mb-1">Kendala Aplikasi?</h5>
                        <p class="text-xs text-slate-500 mb-4">Gunakan mode browser biasa dengan pengawasan ketat.</p>
                        <a href="{{ route('student.login') }}" onclick="return confirm('PERINGATAN: Segala aktivitas membuka aplikasi lain akan tercatat sebagai pelanggaran. Lanjutkan?')" class="block w-full py-2.5 bg-white border-2 border-slate-200 hover:border-amber-400 hover:text-amber-600 text-slate-600 font-bold rounded-xl text-sm transition">
                            Masuk via Chrome
                        </a>
                    </div>
                </div>
            </div>

            <!-- TAMPILAN 2: UNTUK PENGGUNA LAPTOP (SEB) -->
            <div x-show="activeTab === 'laptop'" style="display: none;" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4"
                 x-transition:enter-end="opacity-100 translate-y-0">
                
                <div class="flex flex-col md:flex-row items-center gap-8 md:gap-12">
                    <div class="flex-1 text-center md:text-left">
                        <span class="inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-bold uppercase tracking-wider mb-4 border border-blue-200">Lab Komputer</span>
                        <h4 class="text-2xl font-black text-slate-800 mb-3">Safe Exam Browser (SEB)</h4>
                        <p class="text-slate-500 mb-6 leading-relaxed">
                            Mode aman untuk pengguna Laptop/PC. Pastikan software SEB sudah terinstall di komputer Anda sebelum mendownload konfigurasi ini.
                        </p>
                        
                        <div class="flex flex-col sm:flex-row gap-4">
                            <a href="{{ route('cbt.download_seb', $exam->id ?? 0) }}" class="flex-1 flex items-center justify-center px-6 py-4 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 transition shadow-xl shadow-blue-600/30 group">
                                <i class="ph-bold ph-file-lock text-2xl mr-3 group-hover:rotate-12 transition"></i>
                                <div class="text-left">
                                    <span class="block text-[10px] uppercase font-bold text-blue-200">File Masuk</span>
                                    <span class="block text-sm leading-none">Download Config (.seb)</span>
                                </div>
                            </a>
                        </div>
                    </div>
                    
                    {{-- Ilustrasi --}}
                    <div class="w-full md:w-64 flex justify-center opacity-80">
                        <i class="ph-duotone ph-laptop text-[8rem] text-slate-200"></i>
                    </div>
                </div>
            </div>
        </div>
        
         <div class="mt-10 text-center">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-slate-700 transition py-2 px-4 rounded-lg hover:bg-slate-100">
                <i class="ph-bold ph-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>

@endcomponent