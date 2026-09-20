<x-app-layout>
    {{-- CUSTOM STYLES FLUENT --}}
    <style>
        .fluent-card { box-shadow: 0 1.6px 3.6px 0 rgba(0, 0, 0, 0.05), 0 0.3px 0.9px 0 rgba(0, 0, 0, 0.03); border: 1px solid rgba(0, 0, 0, 0.05); transition: all 0.3s ease; }
        .fluent-modal { box-shadow: 0 25.6px 57.6px 0 rgba(0, 0, 0, 0.15), 0 4.8px 14.4px 0 rgba(0, 0, 0, 0.1); border: 1px solid rgba(0, 0, 0, 0.05); }
    </style>

    <div class="py-8 sm:py-10 font-sans text-elevate-text bg-elevate-surface min-h-screen relative overflow-hidden">
        
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-10 pointer-events-none -z-10 blur-3xl"></div>

        {{-- HERO SECTION (UNIFIED ELEVATE DARK GLASS - AQUALIFE & E-LEARNING) --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10 relative z-10">
            <div class="animate-enter relative rounded-[2.5rem] bg-gradient-to-br from-[#0d52a1]/85 via-[#031d3d]/90 to-[#021124]/95 p-8 sm:p-10 text-white shadow-2xl shadow-[#0d52a1]/25 border border-white/20 backdrop-blur-2xl overflow-hidden group">
                {{-- Specular Top Rim Light (Ref 2) --}}
                <div class="absolute inset-0 rounded-[2.5rem] pointer-events-none border-t border-l border-white/30"></div>
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>

                {{-- Ambient Radiant Glow Orbs (Ref 1 & 2) --}}
                <div class="absolute -top-16 -right-16 w-80 h-80 bg-[#56bbf1]/20 rounded-full blur-[100px] pointer-events-none"></div>
                <div class="absolute -bottom-16 -left-16 w-72 h-72 bg-[#0d52a1]/30 rounded-full blur-[90px] pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    
                    {{-- KIRI: Text Content & Actions --}}
                    <div class="space-y-4 max-w-2xl">
                        <div class="flex flex-wrap items-center gap-2">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white border border-white/15 text-xs font-bold transition-all shadow-sm">
                                <i class="ph-bold ph-arrow-left text-sky-400"></i>
                                <span>Dashboard</span>
                            </a>
                            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 text-sky-300 text-xs font-bold uppercase tracking-wider backdrop-blur-md shadow-sm">
                                <i class="ph-fill ph-users-three text-sky-400"></i> Akses & Keamanan Akun
                            </div>
                        </div>

                        <h1 class="text-3xl sm:text-4xl md:text-5xl font-black tracking-tight leading-snug sm:leading-snug md:leading-normal text-white">
                            <span class="block text-slate-100">Manajemen &</span>
                            <span class="block mt-1 sm:mt-1.5 text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] via-sky-200 to-[#38bdf8]">Otoritas Pengguna</span>
                        </h1>
                        <p class="text-slate-300 text-sm sm:text-base font-medium leading-relaxed max-w-lg">
                            Kelola hak akses akun untuk Administrator, Kepala Sekolah, Tata Usaha, Dewan Guru, dan Staf sekolah secara terpusat.
                        </p>

                        {{-- Feature Highlight Chips (Ref 1 & 2) --}}
                        <div class="flex flex-wrap items-center gap-2.5 pt-1">
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-white/5 border border-white/10 text-slate-200 text-xs font-semibold backdrop-blur-sm shadow-sm">
                                <i class="ph-bold ph-check text-sky-400"></i> Multi-Role RBAC
                            </div>
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-white/5 border border-white/10 text-slate-200 text-xs font-semibold backdrop-blur-sm shadow-sm">
                                <i class="ph-bold ph-check text-sky-400"></i> Keamanan Enkripsi Akun
                            </div>
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-white/5 border border-white/10 text-slate-200 text-xs font-semibold backdrop-blur-sm shadow-sm">
                                <i class="ph-bold ph-check text-sky-400"></i> Log Aktivitas Terpantau
                            </div>
                        </div>

                        {{-- Dual Action Buttons (Ref 2) --}}
                        <div class="flex flex-wrap items-center gap-3 pt-2">
                            <button onclick="document.querySelector('input[name=name]').focus(); document.querySelector('input[name=name]').scrollIntoView({behavior: 'smooth', block: 'center'})" class="group bg-gradient-to-r from-[#0d52a1] via-sky-600 to-[#56bbf1] hover:from-sky-600 hover:to-sky-400 text-white shadow-lg shadow-sky-600/25 border border-white/20 px-6 py-3.5 rounded-2xl font-bold text-sm transition-all flex items-center justify-center gap-2 active:scale-95">
                                <i class="ph-bold ph-user-plus text-lg"></i>
                                <span>Tambah Pengguna</span>
                                <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </button>
                        </div>
                    </div>
                    
                    {{-- KANAN: Luminous Circular Showcase (Ref 1 & 2) --}}
                    <div class="relative flex items-center justify-center shrink-0 w-full md:w-auto mt-4 md:mt-0">
                        <div class="absolute w-52 h-52 rounded-full border border-[#56bbf1]/30 animate-pulse pointer-events-none"></div>
                        <div class="absolute w-60 h-60 rounded-full border border-sky-400/15 pointer-events-none"></div>

                        {{-- Core Glowing Card --}}
                        <div class="relative z-10 w-44 h-44 rounded-full bg-gradient-to-br from-[#0d52a1]/80 via-[#031d3d]/90 to-[#021124] p-1 border-2 border-[#56bbf1]/50 shadow-2xl shadow-sky-500/30 backdrop-blur-xl flex flex-col items-center justify-center text-center">
                            <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-sky-400 to-[#0d52a1] flex items-center justify-center text-white shadow-lg shadow-sky-400/40 mb-1 border border-white/20">
                                <i class="ph-bold ph-user-circle-gear text-xl"></i>
                            </div>
                            <span class="text-3xl font-black tracking-tight text-white leading-none">
                                {{ $users->total() }}
                            </span>
                            <span class="text-[10px] font-bold text-sky-300 uppercase tracking-widest mt-1">Total Akun</span>
                            <div class="mt-1.5 inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-500/20 border border-emerald-400/30 text-emerald-300 text-[10px] font-bold">
                                <i class="ph-fill ph-check-circle"></i> Terverifikasi
                            </div>
                        </div>

                        {{-- Floating Status Bubbles (Ref 1) --}}
                        <div class="absolute -top-2 -right-2 z-20 flex items-center gap-1.5 px-3 py-1 rounded-full bg-[#031d3d]/90 border border-white/20 text-white text-[11px] font-bold shadow-xl backdrop-blur-md">
                            <i class="ph-bold ph-shield-check text-sky-400"></i>
                            <span>Protected</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            {{-- 1. ALERT SUKSES --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center justify-between fluent-card">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-white rounded-xl text-emerald-600 shadow-sm border border-emerald-100">
                            <i class="ph-bold ph-check-circle text-xl"></i>
                        </div>
                        <span class="font-bold text-sm">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-600 hover:bg-emerald-100 p-2 rounded-xl transition"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            {{-- 2. ALERT ERROR (SESSION) --}}
            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl flex items-center justify-between fluent-card">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-white rounded-xl text-rose-600 shadow-sm border border-rose-100">
                            <i class="ph-bold ph-warning-circle text-xl"></i>
                        </div>
                        <span class="font-bold text-sm">{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-rose-600 hover:bg-rose-100 p-2 rounded-xl transition"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            {{-- 3. ALERT ERROR VALIDASI --}}
            @if ($errors->any())
                <div x-data="{ show: true }" x-show="show" class="mb-8 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl flex items-start gap-4 fluent-card">
                    <div class="p-2 bg-white rounded-xl text-rose-600 shrink-0 shadow-sm border border-rose-100 mt-0.5">
                        <i class="ph-bold ph-warning-circle text-xl"></i>
                    </div>
                    <div>
                        <p class="font-black text-sm mb-1.5">Terdapat kesalahan input:</p>
                        <ul class="list-disc list-inside text-xs font-bold space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                {{-- KOLOM KIRI: FORM TAMBAH USER (QUICK ADD) --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden sticky top-24 relative group transition-all duration-300">
                        
                        {{-- Card Header --}}
                        <div class="bg-gradient-to-r from-elevate-primary to-elevate-accent p-8 text-white relative overflow-hidden border-b border-white/20">
                            <div class="absolute -right-4 -bottom-4 text-white/20 text-8xl pointer-events-none group-hover:scale-110 transition-transform duration-500">
                                <i class="ph-fill ph-user-plus"></i>
                            </div>
                            <h3 class="text-xl font-black relative z-10">User Baru</h3>
                            <p class="text-white/80 text-xs font-medium relative z-10 mt-1">Registrasi akun cepat.</p>
                        </div>

                        <div class="p-6 md:p-8 relative z-10">
                            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                                @csrf
                                
                                {{-- 1. DATA AKUN DASAR --}}
                                <div>
                                    <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2 ml-1">Nama Lengkap</label>
                                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Contoh: Budi Santoso, S.Pd."
                                           class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-4 transition-colors placeholder:font-medium">
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2 ml-1">Email Login</label>
                                    <input type="email" name="email" value="{{ old('email') }}" required placeholder="email@sekolah.sch.id"
                                           class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-4 transition-colors placeholder:font-medium">
                                </div>

                                <div>
                                    <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2 ml-1">Peran (Role)</label>
                                    <div class="relative">
                                        <select name="role[]" required multiple class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3 px-4 transition-colors appearance-none cursor-pointer h-32 custom-scrollbar">
                                            <option value="Guru">Guru (Umum)</option>
                                            <option value="Guru Mata Pelajaran">Guru Mata Pelajaran</option>
                                            <option value="Wali Kelas">Wali Kelas</option>
                                            <option value="TU">TU (Tata Usaha)</option>
                                            <option value="Guru Piket">Guru Piket</option>
                                            <option value="Kepala Sekolah">Kepala Sekolah</option>
                                            <option value="Admin">Admin (IT)</option>
                                        </select>
                                        <p class="text-[10px] text-slate-400 mt-2 ml-1 font-medium">
                                            *Tahan tombol <kbd class="bg-slate-100 px-1 rounded border border-slate-200">CTRL</kbd> atau <kbd class="bg-slate-100 px-1 rounded border border-slate-200">CMD</kbd> untuk memilih > 1 role.
                                        </p>
                                    </div>
                                </div>

                                {{-- 2. PASSWORD --}}
                                <div class="grid grid-cols-2 gap-4 pt-5 border-t border-dashed border-slate-200">
                                    <div>
                                        <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2 ml-1">Password</label>
                                        <input type="password" name="password" required 
                                               class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-4 transition-colors">
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-2 ml-1">Konfirmasi</label>
                                        <input type="password" name="password_confirmation" required 
                                               class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-3.5 px-4 transition-colors">
                                    </div>
                                </div>

                                <button type="submit" class="w-full py-4 px-6 bg-elevate-dark text-white font-bold rounded-xl hover:bg-elevate-primary transition-all shadow-lg shadow-elevate-dark/20 flex items-center justify-center gap-2 transform active:scale-95 mt-4">
                                    <i class="ph-bold ph-user-plus text-lg"></i>
                                    Daftarkan Akun
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: DAFTAR USER (TABLE LENGKAP) --}}
                <div class="lg:col-span-2" x-data="{ showImport: false }">
                    <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden flex flex-col h-full min-h-[600px] relative">
                        
                        {{-- Toolbar Table --}}
                        <div class="p-6 md:p-8 border-b border-slate-50 bg-elevate-peach-light/30 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <h3 class="text-xl font-black text-elevate-dark flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white text-elevate-primary flex items-center justify-center border border-elevate-peach/50 shadow-sm">
                                    <i class="ph-bold ph-users text-xl"></i>
                                </div>
                                Daftar Pengguna
                                <span class="bg-white border border-slate-200 text-[10px] font-black px-3 py-1.5 rounded-full text-elevate-primary shadow-sm ml-1">
                                    {{ $users->total() }}
                                </span>
                            </h3>
                            
                            {{-- BUTTON GROUP: EXPORT & IMPORT --}}
                            <div class="flex items-center gap-3">
                                {{-- Tombol Export --}}
                                <a href="{{ route('users.export') }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-50 text-emerald-600 text-xs font-bold border border-emerald-200 hover:bg-emerald-600 hover:text-white transition-colors shadow-sm group">
                                    <i class="ph-bold ph-file-xls text-lg group-hover:-translate-y-0.5 transition-transform"></i>
                                    <span class="hidden sm:inline">Export</span>
                                </a>

                                {{-- Tombol Import --}}
                                <button @click="showImport = true" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-elevate-peach-light text-elevate-primary text-xs font-bold border border-elevate-peach/50 hover:bg-elevate-primary hover:text-white transition-colors shadow-sm group">
                                    <i class="ph-bold ph-upload-simple text-lg group-hover:-translate-y-0.5 transition-transform"></i>
                                    <span class="hidden sm:inline">Import</span>
                                </button>
                            </div>
                        </div>

                        {{-- MODAL IMPORT (POPUP) --}}
                        <div x-show="showImport" style="display: none;" 
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="opacity-0 backdrop-blur-none"
                             x-transition:enter-end="opacity-100 backdrop-blur-sm"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 backdrop-blur-sm"
                             x-transition:leave-end="opacity-0 backdrop-blur-none"
                             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-elevate-dark/70 backdrop-blur-sm">
                            
                            <div @click.away="showImport = false" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 scale-90 translate-y-8"
                                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                                 x-transition:leave-end="opacity-0 scale-90 translate-y-8"
                                 class="bg-white w-full max-w-md p-8 rounded-[2rem] shadow-2xl shadow-elevate-dark/30 relative border border-slate-100">
                                
                                {{-- Close Button --}}
                                <button @click="showImport = false" class="absolute top-6 right-6 w-10 h-10 flex items-center justify-center rounded-full bg-slate-50 text-slate-400 hover:bg-rose-50 hover:text-rose-600 transition-colors">
                                    <i class="ph-bold ph-x"></i>
                                </button>

                                <div class="text-center mb-8 mt-2">
                                    <div class="w-20 h-20 bg-elevate-peach-light rounded-[1.5rem] flex items-center justify-center mx-auto mb-5 text-elevate-primary border border-elevate-peach/50">
                                        <i class="ph-duotone ph-file-xls text-4xl"></i>
                                    </div>
                                    <h3 class="text-2xl font-black text-elevate-dark">Import Data User</h3>
                                    <p class="text-slate-500 text-sm font-medium mt-2 leading-relaxed px-4">
                                        Upload file Excel (.xlsx) untuk menambahkan user secara massal ke dalam sistem.
                                    </p>
                                </div>

                                <form action="{{ route('users.import') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                                    @csrf
                                    
                                    {{-- Custom File Input --}}
                                    <div class="relative group cursor-pointer">
                                        <input type="file" name="file" required accept=".xlsx, .xls"
                                               class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                               onchange="document.getElementById('fileNameDisplay').innerText = this.files[0].name; document.getElementById('fileIcon').className='ph-duotone ph-check-circle text-4xl text-emerald-500 mb-3'; document.getElementById('fileContainer').classList.add('border-emerald-200', 'bg-emerald-50');">
                                        
                                        <div id="fileContainer" class="border-2 border-dashed border-slate-200 rounded-2xl p-8 text-center group-hover:border-elevate-accent group-hover:bg-elevate-accent/5 transition-all duration-300">
                                            <i id="fileIcon" class="ph-duotone ph-cloud-arrow-up text-4xl text-slate-300 group-hover:text-elevate-primary mb-3 transition-colors"></i>
                                            <p class="text-sm font-bold text-slate-600 group-hover:text-elevate-dark transition-colors" id="fileNameDisplay">
                                                Klik atau seret file Excel
                                            </p>
                                            <p class="text-[10px] font-black text-elevate-primary mt-2 uppercase tracking-wider bg-elevate-peach-light px-2 py-1 rounded inline-block border border-elevate-peach/30">Maks. 5MB</p>
                                        </div>
                                    </div>

                                    <div class="flex flex-col gap-3">
                                        <button type="submit" class="w-full py-4 rounded-xl bg-elevate-dark text-white font-bold shadow-lg shadow-elevate-dark/20 hover:bg-elevate-primary transition-all transform active:scale-95 flex items-center justify-center gap-2 border border-transparent">
                                            <i class="ph-bold ph-upload-simple text-lg"></i> Proses Import File
                                        </button>
                                        
                                        {{-- Link Template --}}
                                        <a href="{{ asset('template/template_users.xlsx') }}" class="w-full py-3.5 rounded-xl bg-white border border-slate-200 text-slate-500 font-bold hover:bg-elevate-peach-light hover:text-elevate-primary hover:border-elevate-peach/50 transition-colors text-center text-sm flex items-center justify-center gap-2">
                                            <i class="ph-bold ph-download-simple"></i> Download Template Excel
                                        </a>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto flex-1 custom-scrollbar">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-slate-50 text-[10px] font-black text-elevate-primary uppercase tracking-widest border-b border-slate-100">
                                    <tr>
                                        <th class="px-6 py-5">Identitas Diri</th>
                                        <th class="px-6 py-5">Peran & Jabatan</th>
                                        <th class="px-6 py-5 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @forelse ($users as $user)
                                        <tr class="group hover:bg-slate-50 transition-colors">
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <div class="flex items-center gap-4">
                                                    {{-- Avatar --}}
                                                    <div class="relative shrink-0">
                                                        @if($user->photo_path)
                                                            <img src="{{ asset('storage/' . $user->photo_path) }}" class="w-12 h-12 rounded-[1rem] object-cover shadow-sm border border-slate-200">
                                                        @else
                                                            <div class="w-12 h-12 rounded-[1rem] bg-elevate-peach-light flex items-center justify-center text-elevate-primary font-black text-base border border-elevate-peach/50 shadow-sm">
                                                                {{ substr($user->name, 0, 2) }}
                                                            </div>
                                                        @endif
                                                        
                                                        {{-- Online/Sosmed Indicator --}}
                                                        @if($user->instagram || $user->facebook || $user->tiktok)
                                                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-white rounded-full flex items-center justify-center shadow-sm border border-slate-100" title="Data Sosmed Tersedia">
                                                                <div class="w-2.5 h-2.5 bg-emerald-500 rounded-full"></div>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <div class="font-bold text-elevate-dark text-sm group-hover:text-elevate-primary transition-colors">{{ $user->name }}</div>
                                                        <div class="text-xs text-elevate-text/60 font-medium">{{ $user->email }}</div>
                                                        {{-- Tampilkan NIP jika ada --}}
                                                        @if($user->nip)
                                                            <div class="text-[10px] text-elevate-text/50 font-mono mt-1 font-bold">NIP. {{ $user->nip }}</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <div class="flex flex-col items-start gap-2">
                                                    <div class="flex flex-wrap gap-1.5 max-w-[220px]">
                                                        @foreach($user->roles as $roleItem)
                                                            @php
                                                                $badgeClass = match($roleItem->name) {
                                                                    'Admin' => 'bg-rose-50 text-rose-600 border-rose-200',
                                                                    'Kepala Sekolah' => 'bg-elevate-primary/10 text-elevate-primary border-elevate-primary/30',
                                                                    'TU' => 'bg-slate-100 text-slate-600 border-slate-200',
                                                                    'Wali Kelas' => 'bg-amber-50 text-amber-600 border-amber-200',
                                                                    'Guru Mata Pelajaran' => 'bg-emerald-50 text-emerald-600 border-emerald-200',
                                                                    'Guru Piket' => 'bg-elevate-accent/10 text-elevate-dark border-elevate-accent/30',
                                                                    default => 'bg-slate-50 text-slate-500 border-slate-200',
                                                                };
                                                            @endphp
                                                            <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider border shadow-sm {{ $badgeClass }}">
                                                                {{ $roleItem->name }}
                                                            </span>
                                                        @endforeach
                                                    </div>

                                                    {{-- Tampilkan Jabatan & Pangkat jika ada --}}
                                                    @if($user->position || $user->pangkat)
                                                        <div class="text-[11px] text-elevate-text/70 font-bold flex flex-col gap-1 mt-1">
                                                            @if($user->position)
                                                                <span class="flex items-center gap-1.5"><i class="ph-fill ph-briefcase text-elevate-primary"></i> {{ $user->position }}</span>
                                                            @endif
                                                            @if($user->pangkat)
                                                                <span class="flex items-center gap-1 text-[10px] text-elevate-text/50 bg-slate-100 px-2 py-0.5 rounded-md w-fit border border-slate-200">{{ $user->pangkat }}</span>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap text-right">
                                                <div class="flex justify-end gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                                    @if(Auth::id() != $user->id)
                                                        <a href="{{ route('users.edit', $user->id) }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-white hover:bg-elevate-primary hover:border-elevate-primary transition-all shadow-sm" title="Edit Data Lengkap">
                                                            <i class="ph-bold ph-pencil-simple text-lg"></i>
                                                        </a>

                                                        {{-- Konfirmasi Hapus --}}
                                                        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline" 
                                                              onsubmit="event.preventDefault(); 
                                                                        const form = this;
                                                                        Swal.fire({
                                                                            title: 'Hapus Pengguna?',
                                                                            text: 'Apakah Anda yakin ingin menghapus pengguna {{ $user->name }}? Data tidak dapat dikembalikan.',
                                                                            icon: 'warning',
                                                                            showCancelButton: true,
                                                                            confirmButtonColor: '#e11d48',
                                                                            cancelButtonColor: '#94a3b8',
                                                                            confirmButtonText: 'Ya, Hapus!',
                                                                            cancelButtonText: 'Batal',
                                                                            reverseButtons: true,
                                                                            buttonsStyling: false,
                                                                            customClass: {
                                                                                popup: 'rounded-[2rem] border border-slate-100 shadow-2xl font-sans',
                                                                                confirmButton: 'bg-rose-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-2 shadow-lg border border-transparent',
                                                                                cancelButton: 'bg-slate-100 text-slate-600 px-6 py-3 rounded-xl font-bold hover:bg-slate-200 transition-colors mx-2 border border-transparent'
                                                                            }
                                                                        }).then((result) => {
                                                                            if (result.isConfirmed) {
                                                                                form.submit();
                                                                            }
                                                                        });">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-white hover:border-rose-500 hover:bg-rose-500 transition-all shadow-sm" title="Hapus User">
                                                                <i class="ph-bold ph-trash text-lg"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 text-slate-400 text-[10px] font-black uppercase tracking-wider select-none cursor-not-allowed">
                                                            <i class="ph-bold ph-user"></i> Anda
                                                        </span>
                                                        <a href="{{ route('profile.edit') }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-elevate-primary hover:text-white hover:border-elevate-primary hover:bg-elevate-primary transition-all shadow-sm" title="Edit Profil Saya">
                                                            <i class="ph-bold ph-gear text-lg"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="px-6 py-24 text-center">
                                                <div class="w-20 h-20 bg-elevate-peach-light rounded-2xl flex items-center justify-center mx-auto mb-4 text-elevate-primary border border-elevate-peach/30">
                                                    <i class="ph-duotone ph-users-three text-5xl"></i>
                                                </div>
                                                <p class="text-base font-black text-elevate-dark">Belum ada data pengguna lain.</p>
                                                <p class="text-xs font-medium text-slate-400 mt-1">Tambahkan akun melalui form di samping.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        
                        {{-- Pagination --}}
                        <div class="p-6 border-t border-slate-100 bg-slate-50/50">
                            {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- SweetAlert2 Library --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    {{-- SCRIPT AUTO OPEN MODAL JIKA ERROR --}}
    @if($errors->has('file'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let alpineComponent = document.querySelector('[x-data="{ showImport: false }"]');
            if(alpineComponent) {
                alpineComponent.__x.$data.showImport = true;
            }
            
            Swal.fire({
                icon: 'error',
                title: 'Gagal Upload',
                text: {!! json_encode($errors->first('file')) !!},
                customClass: { popup: 'rounded-[2rem] border border-slate-100 font-sans shadow-2xl' }
            });
        });
    </script>
    @endif
</x-app-layout>