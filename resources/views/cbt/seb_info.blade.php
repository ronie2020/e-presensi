{{-- 
    FILE TAMPILAN (VIEW)
    Ini adalah apa yang dilihat siswa jika mereka belum menggunakan Aplikasi Ujian.
--}}
@component('cbt.seb_landing')

    @slot('header')
        <h2 class="font-bold text-xl text-slate-800 leading-tight flex items-center gap-2">
            <i class="ph-duotone ph-device-mobile-camera text-blue-600"></i>
            {{ __('Pilih Perangkat Ujian') }}
        </h2>
    @endslot

    {{-- KONTEN UTAMA --}}
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="{ activeTab: 'hp' }">
        
        <!-- Judul & Intro -->
        <div class="text-center mb-8">
            <h3 class="text-2xl font-black text-slate-800 mb-2">Akses Ditolak / Belum Masuk</h3>
            <p class="text-slate-500">Anda mencoba mengakses ujian menggunakan Browser biasa.<br>Silakan masuk menggunakan Aplikasi Ujian Resmi.</p>
        </div>

        <!-- Tombol Pilihan Tab (HP vs Laptop) -->
        <div class="bg-slate-100 p-1 rounded-xl flex mb-8 max-w-md mx-auto relative">
            <button @click="activeTab = 'hp'" 
                :class="activeTab === 'hp' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                class="flex-1 py-2.5 rounded-lg text-sm font-bold transition-all flex items-center justify-center gap-2 relative z-10">
                <i class="ph-bold ph-device-mobile"></i> HP (Android)
            </button>
            <button @click="activeTab = 'laptop'" 
                :class="activeTab === 'laptop' ? 'bg-white text-blue-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                class="flex-1 py-2.5 rounded-lg text-sm font-bold transition-all flex items-center justify-center gap-2 relative z-10">
                <i class="ph-bold ph-laptop"></i> Laptop / PC
            </button>
        </div>

        <!-- TAMPILAN 1: UNTUK PENGGUNA HP (APK) -->
        <div x-show="activeTab === 'hp'" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             class="bg-white rounded-3xl shadow-xl shadow-slate-200 border border-slate-100 p-8 text-center">
            
            <div class="w-16 h-16 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="ph-duotone ph-android-logo text-3xl"></i>
            </div>
            <h4 class="text-xl font-bold text-slate-800 mb-2">Aplikasi Ujian Sekolah</h4>
            <p class="text-sm text-slate-500 mb-6 max-w-md mx-auto">
                Wajib menggunakan aplikasi khusus sekolah (Exambrowser) untuk mencegah kecurangan. Ringan & Hemat Kuota.
            </p>

            <div class="space-y-6 max-w-sm mx-auto">
                
                <!-- TOMBOL DOWNLOAD APK -->
                <div class="text-left bg-purple-50 p-5 rounded-xl border border-purple-100 relative overflow-hidden">
                    <p class="text-xs font-bold text-purple-500 uppercase flex items-center gap-1 mb-1">
                        <i class="ph-fill ph-download-simple"></i> Langkah 1
                    </p>
                    <p class="text-sm font-bold text-purple-900 mb-4">Download & Install Aplikasi</p>
                    
                    {{-- 
                        !!! PENTING !!!
                        Ganti href="#" dengan Link Google Drive APK Anda.
                    --}}
                    <a href="#" target="_blank" class="flex items-center justify-center w-full py-3 bg-purple-600 text-white font-bold rounded-lg hover:bg-purple-700 transition shadow-lg shadow-purple-500/20 gap-2">
                        <i class="ph-bold ph-android-logo"></i> Download APK
                    </a>
                    <p class="text-[10px] text-purple-600/70 mt-2 text-center">
                        *Install aplikasi, lalu buka ujian dari dalam aplikasi tersebut.
                    </p>
                </div>

                <!-- OPSI DARURAT (CHROME) -->
                <div class="text-left bg-white p-4 rounded-xl border-2 border-slate-100 hover:border-amber-200 transition-colors group">
                    <p class="text-xs font-bold text-amber-500 uppercase mb-1 flex items-center gap-1">
                        <i class="ph-fill ph-warning"></i> Darurat / HP Tidak Support
                    </p>
                    <p class="text-sm font-bold text-slate-800 mb-1">Masuk via Chrome Biasa</p>
                    
                    {{-- Link ini akan diblokir lagi oleh Middleware KECUALI Anda menghapus blokirannya, 
                         tapi ini berguna jika Anda ingin memberi akses darurat --}}
                    <a href="{{ route('student.login') }}" onclick="return confirm('PERINGATAN: Segala aktivitas membuka aplikasi lain akan tercatat sebagai pelanggaran. Lanjutkan?')" class="flex items-center justify-center w-full py-2 bg-white border border-slate-300 text-slate-600 font-bold rounded-lg hover:bg-slate-50 hover:text-slate-800 transition gap-2 text-xs mt-2">
                        Lanjut dengan Chrome (Diawasi)
                    </a>
                </div>

            </div>
        </div>

        <!-- TAMPILAN 2: UNTUK PENGGUNA LAPTOP (SEB) -->
        <div x-show="activeTab === 'laptop'" style="display: none;" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             class="bg-white rounded-3xl shadow-xl shadow-slate-200 border border-slate-100 p-8 text-center">
            
            <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i class="ph-duotone ph-laptop text-3xl"></i>
            </div>
            <h4 class="text-xl font-bold text-slate-800 mb-6">Mode Lab Komputer (SEB)</h4>

            <div class="space-y-4 max-w-sm mx-auto">
                <div class="text-left bg-blue-50 p-5 rounded-xl border border-blue-100">
                    <p class="text-xs font-bold text-blue-500 uppercase mb-1 flex items-center gap-1">
                        <i class="ph-fill ph-key"></i> File Masuk
                    </p>
                    <p class="text-sm font-bold text-blue-900 mb-4">Download Konfigurasi (.seb)</p>
                    
                    <a href="{{ route('cbt.download_seb', $exam->id) }}" class="flex items-center justify-center w-full py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition shadow-lg shadow-blue-500/20 gap-2">
                        <i class="ph-bold ph-download-simple"></i> Download Config
                    </a>
                </div>
            </div>
        </div>
        
         <div class="mt-8 text-center">
            <a href="{{ route('cbt.index') }}" class="text-sm font-bold text-slate-400 hover:text-slate-600 transition">
                &larr; Kembali ke Dashboard
            </a>
        </div>
    </div>

@endcomponent