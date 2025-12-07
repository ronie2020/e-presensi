@extends('layouts.public')

@section('content')
<!-- 
    NOTE: Idealnya block <style> ini dipindahkan ke file CSS terpisah (misal: resources/css/app.css) 
    jika menggunakan Vite/Mix untuk performa caching yang lebih baik.
-->
<style>
    [x-cloak] { display: none !important; }
    .glass-effect {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
    }
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob {
        animation: blob 7s infinite;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    .animation-delay-4000 {
        animation-delay: 4s;
    }
</style>

<div class="w-full max-w-6xl mx-auto min-h-[85vh] flex flex-col justify-center" x-data="{ mode: 'portal', isLoading: false }">

    <!-- 1. HERO SECTION -->
    <div class="bg-white rounded-[2.5rem] shadow-2xl overflow-hidden mb-10 border border-gray-100 relative min-h-[600px] flex items-center justify-center text-center group transition-all duration-500 hover:shadow-blue-200/50">
        
        <!-- Background Decoration -->
        <div class="absolute inset-0 bg-slate-900 z-0">
            <!-- Gunakan asset() lokal jika memungkinkan untuk menghindari ketergantungan internet -->
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80')] bg-cover bg-center opacity-30 mix-blend-overlay group-hover:scale-105 transition-transform duration-[3s]"></div>
            <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-blue-950/80 to-slate-900"></div>
            <!-- Pattern Overlay -->
            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 30px 30px;"></div>
            
            <!-- Animated Blobs -->
            <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-blue-500/40 rounded-full mix-blend-screen filter blur-[100px] opacity-30 animate-blob"></div>
            <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-indigo-500/40 rounded-full mix-blend-screen filter blur-[100px] opacity-30 animate-blob animation-delay-2000"></div>
            <div class="absolute bottom-10 left-10 w-72 h-72 bg-purple-500/30 rounded-full mix-blend-screen filter blur-[80px] opacity-30 animate-blob animation-delay-4000"></div>
        </div>

        <!-- Konten Utama -->
        <div class="relative z-10 w-full max-w-3xl px-6 py-12 flex flex-col items-center">
            
            <!-- Logo Sekolah (Opsional/Placeholder) -->
            <div class="mb-6 w-20 h-20 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/20 shadow-lg" data-aos="fade-down">
                <img src="{{ asset('images/logo.png') }}" class="w-15 h-12 object-contain" alt="Logo">
            </div>

            <!-- Judul & Deskripsi -->
            <div class="mb-10" data-aos="fade-down" data-aos-delay="100">
                <!-- Label Badge -->
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border backdrop-blur-md text-xs font-bold uppercase tracking-widest mb-6 shadow-lg transition-all duration-300"
                     :class="mode === 'portal' ? 'bg-blue-500/20 border-blue-400/30 text-blue-100 ring-2 ring-blue-500/20' : 'bg-rose-500/20 border-rose-400/30 text-rose-100 ring-2 ring-rose-500/20'">
                    <i class="ph-fill" :class="mode === 'portal' ? 'ph-student' : 'ph-desktop'"></i>
                    <span x-text="mode === 'portal' ? 'Portal Siswa Terpadu' : 'Sistem Ujian Online'"></span>
                </div>

                <!-- Main Title with Dynamic Text -->
                <h1 class="text-4xl md:text-6xl font-black text-white tracking-tight leading-tight mb-4 drop-shadow-lg transition-all duration-300">
                    <template x-if="mode === 'portal'">
                        <span>Cek Progres <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-cyan-200">Akademikmu Disini</span></span>
                    </template>
                    <template x-if="mode === 'cbt'">
                        <span>Siap Hadapi <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-200 to-orange-200">Ujian Sekolah?</span></span>
                    </template>
                </h1>
                
                <!-- Subtitle -->
                <p class="text-lg text-blue-100/80 font-medium leading-relaxed max-w-xl mx-auto min-h-[3.5rem] transition-all duration-300">
                    <span x-show="mode === 'portal'" x-transition.opacity>Akses data kehadiran, nilai rapor, poin pelanggaran, dan riwayat perpustakaan secara realtime.</span>
                    <span x-show="mode === 'cbt'" x-cloak x-transition.opacity>Masukkan NISN dan Password untuk masuk ke ruang ujian. Pastikan jadwal ujian aktif hari ini.</span>
                </p>
            </div>

            <!-- TAB SWITCHER & FORM CARD -->
            <div class="glass-effect border border-white/40 p-2.5 rounded-[2rem] shadow-2xl relative w-full max-w-xl mx-auto transition-all duration-500 transform hover:scale-[1.01]" 
                 :class="mode === 'portal' ? 'shadow-blue-900/30' : 'shadow-rose-900/30'"
                 data-aos="fade-up" data-aos-delay="200">
                
                <!-- Tab Buttons -->
                <div class="grid grid-cols-2 gap-2 mb-2 p-1 bg-slate-900/5 rounded-3xl">
                    <button @click="mode = 'portal'" 
                        class="py-3.5 rounded-2xl text-sm font-bold transition-all duration-300 flex items-center justify-center gap-2"
                        :class="mode === 'portal' ? 'bg-white text-blue-700 shadow-md scale-100 ring-1 ring-black/5' : 'text-slate-600 hover:text-slate-800 hover:bg-white/40'">
                        <i class="ph-bold ph-magnifying-glass text-lg"></i> Cek Data
                    </button>
                    <button @click="mode = 'cbt'" 
                        class="py-3.5 rounded-2xl text-sm font-bold transition-all duration-300 flex items-center justify-center gap-2"
                        :class="mode === 'cbt' ? 'bg-white text-rose-600 shadow-md scale-100 ring-1 ring-black/5' : 'text-slate-600 hover:text-slate-800 hover:bg-white/40'">
                        <i class="ph-bold ph-desktop text-lg"></i> Masuk Ujian
                    </button>
                </div>

                <!-- FORM CONTAINER -->
                <div class="relative bg-white rounded-[1.8rem] p-3 transition-colors duration-300 ring-1 ring-black/5">
                    
                    <!-- Form Portal (Search) -->
                    <form x-show="mode === 'portal'" @submit="isLoading = true" action="{{ route('portal.search') }}" method="POST" class="relative" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                        @csrf
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                                <i class="ph-bold ph-identification-card text-2xl text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                            </div>
                            <input type="text" name="student_id" 
                                class="block w-full pl-16 pr-32 py-5 bg-slate-50 text-slate-800 text-lg font-bold rounded-2xl focus:ring-4 focus:ring-blue-100 focus:bg-white transition-all placeholder:text-slate-400 border-none outline-none" 
                                placeholder="Masukkan NISN Siswa" required autocomplete="off">
                            
                            <button type="submit" :disabled="isLoading" class="absolute right-2 top-2 bottom-2 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white px-8 rounded-xl font-bold transition-all shadow-lg shadow-blue-600/20 flex items-center gap-2 group/btn">
                                <span x-show="!isLoading">Cari</span>
                                <i x-show="!isLoading" class="ph-bold ph-arrow-right group-hover/btn:translate-x-1 transition-transform"></i>
                                <!-- Spinner Loading -->
                                <svg x-show="isLoading" x-cloak class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </button>
                        </div>
                        <p class="text-xs text-slate-400 mt-3 px-4 text-center">Contoh NISN: <span class="font-mono bg-slate-100 px-1 py-0.5 rounded text-slate-600">0051234567</span></p>
                    </form>

                    <!-- Form CBT (Login) -->
                    <form x-show="mode === 'cbt'" action="{{ route('student.login.post') }}" method="POST" class="relative" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                        @csrf
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                                <i class="ph-bold ph-lock-key text-2xl text-rose-400 group-focus-within:text-rose-500 transition-colors"></i>
                            </div>
                            <input type="text" name="student_id" 
                                class="block w-full pl-16 pr-36 py-5 bg-rose-50/50 text-slate-800 text-lg font-bold rounded-2xl focus:ring-4 focus:ring-rose-100 focus:bg-white transition-all placeholder:text-rose-300 border-none outline-none" 
                                placeholder="NISN Peserta" required autocomplete="off">
                            
                            <button type="submit" class="absolute right-2 top-2 bottom-2 bg-rose-600 hover:bg-rose-700 text-white px-6 rounded-xl font-bold transition-all shadow-lg shadow-rose-600/20 flex items-center gap-2 group/btn">
                                <span>Mulai Ujian</span>
                                <i class="ph-bold ph-sign-in group-hover/btn:translate-x-1 transition-transform"></i>
                            </button>
                        </div>
                        <p class="text-xs text-rose-300/80 mt-3 px-4 text-center">Pastikan sesi ujian Anda sudah dimulai.</p>
                    </form>

                </div>
            </div>

            <!-- Error Message -->
            @if(session('error') || $errors->any())
                <div class="mt-6 p-4 bg-rose-500/90 backdrop-blur-md border border-rose-400 rounded-2xl text-white flex items-center justify-center gap-3 animate-pulse shadow-xl max-w-lg mx-auto" role="alert">
                    <div class="bg-white/20 rounded-full p-1.5"><i class="ph-bold ph-warning text-white"></i></div>
                    <span class="font-bold text-sm">{{ session('error') ?? $errors->first() }}</span>
                </div>
            @endif
        </div>
        
        <!-- Bottom Decoration -->
        <div class="absolute bottom-0 w-full text-center pb-6 text-white/30 text-xs font-medium z-10">
            &copy; {{ date('Y') }} Sistem Informasi Sekolah. All rights reserved.
        </div>
    </div>

    <!-- 2. FITUR GRID -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 px-2 mb-12">
        <!-- Kartu 1: Absensi -->
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-xl hover:-translate-y-2 transition-all duration-300 cursor-default" data-aos="fade-up" data-aos-delay="200">
            <div class="absolute -top-2 -right-2 p-4 opacity-10 group-hover:opacity-20 transition transform group-hover:scale-110 rotate-12">
                <i class="ph-fill ph-calendar-check text-9xl text-green-500"></i>
            </div>
            <div class="w-14 h-14 bg-green-50 rounded-2xl flex items-center justify-center text-green-600 mb-5 shadow-sm group-hover:scale-110 transition-transform ring-4 ring-green-50/50">
                <i class="ph-bold ph-calendar-check text-2xl"></i>
            </div>
            <h3 class="text-slate-800 font-bold text-lg mb-2 group-hover:text-green-600 transition-colors">Absensi Realtime</h3>
            <p class="text-slate-500 text-sm leading-relaxed font-medium">Cek riwayat kehadiran masuk dan pulang sekolah secara langsung.</p>
        </div>

        <!-- Kartu 2: Nilai -->
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-xl hover:-translate-y-2 transition-all duration-300 cursor-default" data-aos="fade-up" data-aos-delay="300">
            <div class="absolute -top-2 -right-2 p-4 opacity-10 group-hover:opacity-20 transition transform group-hover:scale-110 rotate-12">
                <i class="ph-fill ph-chart-bar text-9xl text-blue-500"></i>
            </div>
            <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 mb-5 shadow-sm group-hover:scale-110 transition-transform ring-4 ring-blue-50/50">
                <i class="ph-bold ph-chart-bar text-2xl"></i>
            </div>
            <h3 class="text-slate-800 font-bold text-lg mb-2 group-hover:text-blue-600 transition-colors">Nilai Akademik</h3>
            <p class="text-slate-500 text-sm leading-relaxed font-medium">Pantau perkembangan nilai tugas, ulangan harian, dan rapor semester.</p>
        </div>

        <!-- Kartu 3: Poin -->
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-xl hover:-translate-y-2 transition-all duration-300 cursor-default" data-aos="fade-up" data-aos-delay="400">
            <div class="absolute -top-2 -right-2 p-4 opacity-10 group-hover:opacity-20 transition transform group-hover:scale-110 rotate-12">
                <i class="ph-fill ph-star text-9xl text-yellow-500"></i>
            </div>
            <div class="w-14 h-14 bg-yellow-50 rounded-2xl flex items-center justify-center text-yellow-600 mb-5 shadow-sm group-hover:scale-110 transition-transform ring-4 ring-yellow-50/50">
                <i class="ph-bold ph-star text-2xl"></i>
            </div>
            <h3 class="text-slate-800 font-bold text-lg mb-2 group-hover:text-yellow-600 transition-colors">Poin Karakter</h3>
            <p class="text-slate-500 text-sm leading-relaxed font-medium">Monitoring poin pelanggaran tata tertib dan poin kebaikan siswa.</p>
        </div>

        <!-- Kartu 4: Literasi -->
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-xl hover:-translate-y-2 transition-all duration-300 cursor-default" data-aos="fade-up" data-aos-delay="500">
            <div class="absolute -top-2 -right-2 p-4 opacity-10 group-hover:opacity-20 transition transform group-hover:scale-110 rotate-12">
                <i class="ph-fill ph-books text-9xl text-purple-500"></i>
            </div>
            <div class="w-14 h-14 bg-purple-50 rounded-2xl flex items-center justify-center text-purple-600 mb-5 shadow-sm group-hover:scale-110 transition-transform ring-4 ring-purple-50/50">
                <i class="ph-bold ph-books text-2xl"></i>
            </div>
            <h3 class="text-slate-800 font-bold text-lg mb-2 group-hover:text-purple-600 transition-colors">Literasi Sekolah</h3>
            <p class="text-slate-500 text-sm leading-relaxed font-medium">Riwayat kunjungan perpustakaan dan status peminjaman buku.</p>
        </div>
    </div>
</div>
@endsection