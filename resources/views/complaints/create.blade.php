@extends('layouts.public')

@section('content')
    {{-- CUSTOM STYLES --}}
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        /* Custom Checkbox/Radio Style */
        .radio-card:checked + div { border-color: #3b82f6; background-color: #eff6ff; color: #1d4ed8; }
        .radio-card:checked + div .check-icon { opacity: 1; transform: scale(1); }
    </style>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 pb-20" 
         x-data="{ 
            isAnonymous: false,
            category: '',
            previewUrl: null,
            fileChosen(event) {
                const file = event.target.files[0];
                if (file) {
                    this.previewUrl = URL.createObjectURL(file);
                }
            }
         }">

        {{-- HEADER SECTION --}}
        <div class="animate-enter relative rounded-[2.5rem] bg-gradient-to-r from-slate-900 via-blue-900 to-slate-900 p-8 md:p-10 mb-8 text-white shadow-2xl shadow-blue-900/20 overflow-hidden border border-white/10">
            <!-- Dekorasi Background -->
            <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-rose-500 rounded-full mix-blend-overlay filter blur-[120px] opacity-10"></div>
            <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-blue-500 rounded-full mix-blend-overlay filter blur-[100px] opacity-20"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <a href="{{ route('student.complaints.index') }}" class="inline-flex items-center gap-2 text-blue-200 hover:text-white transition-colors mb-4 text-xs font-bold uppercase tracking-widest">
                        <i class="ph-bold ph-arrow-left"></i> Kembali ke Riwayat
                    </a>
                    <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-2">Buat Laporan Baru</h1>
                    <p class="text-blue-100/80 max-w-xl leading-relaxed">
                        Jangan takut untuk berbicara. Sekolah menjamin keamanan dan kerahasiaan identitasmu jika kamu memilih opsi <span class="text-white font-bold bg-white/10 px-2 py-0.5 rounded border border-white/10">Anonim</span>.
                    </p>
                </div>
                
                {{-- Icon Besar --}}
                <div class="hidden md:flex w-20 h-20 bg-white/10 rounded-2xl items-center justify-center border border-white/20 backdrop-blur-sm shadow-lg">
                    <i class="ph-duotone ph-shield-check text-4xl text-blue-200"></i>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 animate-enter" style="animation-delay: 100ms">
            
            {{-- KOLOM KIRI: FORM --}}
            <div class="lg:col-span-2">
                <form action="{{ route('student.complaints.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 md:p-8 rounded-[2rem] shadow-sm border border-slate-100 relative overflow-hidden">
                    @csrf
                    
                    {{-- Alert Errors --}}
                    @if ($errors->any())
                        <div class="mb-6 bg-rose-50 border border-rose-100 rounded-xl p-4 flex gap-3 items-start">
                            <i class="ph-fill ph-warning-circle text-rose-500 text-xl shrink-0 mt-0.5"></i>
                            <div>
                                <h4 class="text-sm font-bold text-rose-700">Terjadi Kesalahan</h4>
                                <ul class="text-xs text-rose-600 mt-1 list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    {{-- 1. KATEGORI --}}
                    <div class="mb-8">
                        <label class="block text-sm font-bold text-slate-700 mb-4 uppercase tracking-wider">Kategori Laporan</label>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <!-- Bullying -->
                            <label class="cursor-pointer group">
                                <input type="radio" name="category" value="Bullying" class="radio-card hidden" x-model="category">
                                <div class="border-2 border-slate-100 rounded-2xl p-4 flex flex-col items-center justify-center gap-2 hover:border-rose-200 hover:bg-rose-50 transition-all h-full relative overflow-hidden">
                                    <i class="ph-duotone ph-mask-sad text-3xl text-rose-500 group-hover:scale-110 transition-transform"></i>
                                    <span class="text-xs font-bold text-slate-600 group-hover:text-rose-700">Bullying</span>
                                    <div class="check-icon absolute top-2 right-2 opacity-0 transition-all duration-300 transform scale-50">
                                        <i class="ph-fill ph-check-circle text-blue-500 text-lg"></i>
                                    </div>
                                </div>
                            </label>
                            
                            <!-- Kehilangan -->
                            <label class="cursor-pointer group">
                                <input type="radio" name="category" value="Kehilangan" class="radio-card hidden" x-model="category">
                                <div class="border-2 border-slate-100 rounded-2xl p-4 flex flex-col items-center justify-center gap-2 hover:border-amber-200 hover:bg-amber-50 transition-all h-full relative overflow-hidden">
                                    <i class="ph-duotone ph-magnifying-glass text-3xl text-amber-500 group-hover:scale-110 transition-transform"></i>
                                    <span class="text-xs font-bold text-slate-600 group-hover:text-amber-700">Kehilangan</span>
                                    <div class="check-icon absolute top-2 right-2 opacity-0 transition-all duration-300 transform scale-50">
                                        <i class="ph-fill ph-check-circle text-blue-500 text-lg"></i>
                                    </div>
                                </div>
                            </label>

                             <!-- Fasilitas -->
                             <label class="cursor-pointer group">
                                <input type="radio" name="category" value="Fasilitas" class="radio-card hidden" x-model="category">
                                <div class="border-2 border-slate-100 rounded-2xl p-4 flex flex-col items-center justify-center gap-2 hover:border-emerald-200 hover:bg-emerald-50 transition-all h-full relative overflow-hidden">
                                    <i class="ph-duotone ph-wrench text-3xl text-emerald-500 group-hover:scale-110 transition-transform"></i>
                                    <span class="text-xs font-bold text-slate-600 group-hover:text-emerald-700">Fasilitas</span>
                                    <div class="check-icon absolute top-2 right-2 opacity-0 transition-all duration-300 transform scale-50">
                                        <i class="ph-fill ph-check-circle text-blue-500 text-lg"></i>
                                    </div>
                                </div>
                            </label>

                             <!-- Lainnya -->
                             <label class="cursor-pointer group">
                                <input type="radio" name="category" value="Lainnya" class="radio-card hidden" x-model="category">
                                <div class="border-2 border-slate-100 rounded-2xl p-4 flex flex-col items-center justify-center gap-2 hover:border-blue-200 hover:bg-blue-50 transition-all h-full relative overflow-hidden">
                                    <i class="ph-duotone ph-dots-three-circle text-3xl text-blue-500 group-hover:scale-110 transition-transform"></i>
                                    <span class="text-xs font-bold text-slate-600 group-hover:text-blue-700">Lainnya</span>
                                    <div class="check-icon absolute top-2 right-2 opacity-0 transition-all duration-300 transform scale-50">
                                        <i class="ph-fill ph-check-circle text-blue-500 text-lg"></i>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>

                    {{-- 2. DETAIL KEJADIAN --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Kejadian</label>
                            <div class="relative">
                                <input type="date" name="incident_date" required max="{{ date('Y-m-d') }}" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-3 font-semibold">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Lokasi Kejadian</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none text-slate-400">
                                    <i class="ph-bold ph-map-pin"></i>
                                </div>
                                <input type="text" name="location" placeholder="Cth: Kantin, Toilet Lt.2" required class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block pl-10 p-3 font-semibold placeholder-slate-400">
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Ceritakan Kronologi</label>
                        <textarea name="description" rows="5" required placeholder="Jelaskan secara detail apa yang terjadi, siapa yang terlibat, dan bagaimana kejadiannya..." class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block p-4 font-medium placeholder-slate-400"></textarea>
                        <p class="text-[10px] text-slate-400 mt-2 font-bold flex items-center gap-1">
                            <i class="ph-fill ph-info"></i> Ceritakan sejujurnya, kami akan memverifikasi laporan ini.
                        </p>
                    </div>

                    {{-- 3. BUKTI FOTO --}}
                    <div class="mb-8">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Bukti Foto (Opsional)</label>
                        <div class="flex items-center justify-center w-full">
                            <label for="evidence" class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-300 border-dashed rounded-2xl cursor-pointer bg-slate-50 hover:bg-blue-50 hover:border-blue-400 transition-all group overflow-hidden relative">
                                
                                {{-- Preview Image --}}
                                <img x-show="previewUrl" :src="previewUrl" class="absolute inset-0 w-full h-full object-cover opacity-80 z-10">
                                
                                <div class="flex flex-col items-center justify-center pt-5 pb-6 relative z-20" :class="previewUrl ? 'bg-white/80 p-2 rounded-xl backdrop-blur-sm' : ''">
                                    <i class="ph-duotone ph-camera text-3xl text-slate-400 group-hover:text-blue-500 mb-2 transition-colors"></i>
                                    <p class="text-xs text-slate-500 font-bold"><span class="font-extrabold text-blue-600">Klik upload</span> atau drag & drop</p>
                                    <p class="text-[10px] text-slate-400">PNG, JPG (MAX. 2MB)</p>
                                </div>
                                <input id="evidence" name="evidence" type="file" class="hidden" accept="image/*" @change="fileChosen">
                            </label>
                        </div>
                    </div>

                    {{-- BUTTON SUBMIT --}}
                    <div class="pt-6 border-t border-slate-100 flex items-center justify-between">
                        <p class="text-xs text-slate-400 font-medium">Pastikan data yang diisi benar.</p>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-bold shadow-lg shadow-blue-900/30 transition-all hover:scale-105 active:scale-95 flex items-center gap-2">
                            <span>Kirim Laporan</span>
                            <i class="ph-bold ph-paper-plane-right"></i>
                        </button>
                    </div>
                </form>
            </div>

            {{-- KOLOM KANAN: PENGATURAN PRIVASI --}}
            <div class="lg:col-span-1 space-y-6">
                
                {{-- KARTU IDENTITAS PELAPOR --}}
                <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 transition-all duration-500 relative overflow-hidden group">
                    
                    {{-- Efek Visual Anonim --}}
                    <div class="absolute inset-0 bg-slate-800 transition-transform duration-500 z-0"
                         :class="isAnonymous ? 'translate-y-0' : 'translate-y-full'"></div>
                    
                    <div class="relative z-10">
                        <h3 class="font-bold mb-4 flex items-center gap-2" :class="isAnonymous ? 'text-white' : 'text-slate-800'">
                            <i class="ph-fill ph-identification-card text-xl"></i> Identitas Pelapor
                        </h3>

                        <div class="flex items-center gap-4 mb-6">
                            {{-- Avatar --}}
                            <div class="w-14 h-14 rounded-full border-2 flex items-center justify-center shrink-0 transition-all duration-300"
                                 :class="isAnonymous ? 'bg-slate-700 border-slate-600 text-slate-400' : 'bg-blue-100 border-blue-200 text-blue-600'">
                                <i class="ph-duotone text-3xl" :class="isAnonymous ? 'ph-spy' : 'ph-student'"></i>
                            </div>

                            {{-- Nama --}}
                            <div>
                                <p class="text-xs font-bold uppercase tracking-widest mb-0.5" :class="isAnonymous ? 'text-slate-400' : 'text-slate-400'">
                                    Status Laporan
                                </p>
                                <p class="text-lg font-black transition-all duration-300" :class="isAnonymous ? 'text-white' : 'text-slate-800'">
                                    <span x-text="isAnonymous ? 'Disembunyikan' : '{{ Auth::user()->name }}'"></span>
                                </p>
                            </div>
                        </div>

                        {{-- Toggle Switch --}}
                        <div class="bg-slate-50/10 p-4 rounded-xl border border-slate-200/50 backdrop-blur-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold" :class="isAnonymous ? 'text-white' : 'text-slate-700'">Kirim Sebagai Anonim</span>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_anonymous" class="sr-only peer" x-model="isAnonymous">
                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-500"></div>
                                </label>
                            </div>
                            <p class="text-[10px] mt-2 leading-relaxed transition-colors" :class="isAnonymous ? 'text-slate-300' : 'text-slate-500'">
                                Jika aktif, nama kamu tidak akan ditampilkan kepada Guru/BK di dashboard, namun tetap tercatat di sistem database untuk keperluan darurat.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- INFO SIDEBAR --}}
                <div class="bg-blue-50 p-6 rounded-[2rem] border border-blue-100">
                    <h3 class="font-bold text-blue-800 text-sm mb-3 flex items-center gap-2">
                        <i class="ph-fill ph-info"></i> Alur Laporan
                    </h3>
                    <ul class="space-y-4 relative pl-2">
                        <!-- Garis Alur -->
                        <div class="absolute left-[11px] top-2 bottom-2 w-0.5 bg-blue-200"></div>

                        <li class="relative pl-6 flex flex-col gap-0.5">
                            <span class="absolute left-0 top-1.5 w-6 h-0.5 bg-blue-200"></span>
                            <span class="text-xs font-black text-blue-700 uppercase">Langkah 1</span>
                            <span class="text-xs text-blue-600">Siswa mengirim laporan (Anonim/Publik).</span>
                        </li>
                        <li class="relative pl-6 flex flex-col gap-0.5">
                            <span class="absolute left-0 top-1.5 w-6 h-0.5 bg-blue-200"></span>
                            <span class="text-xs font-black text-blue-700 uppercase">Langkah 2</span>
                            <span class="text-xs text-blue-600">Guru BK/Wali Kelas menerima notifikasi.</span>
                        </li>
                        <li class="relative pl-6 flex flex-col gap-0.5">
                            <span class="absolute left-0 top-1.5 w-6 h-0.5 bg-blue-200"></span>
                            <span class="text-xs font-black text-blue-700 uppercase">Langkah 3</span>
                            <span class="text-xs text-blue-600">Investigasi & Pemanggilan (jika perlu).</span>
                        </li>
                        <li class="relative pl-6 flex flex-col gap-0.5">
                            <span class="absolute left-0 top-1.5 w-6 h-0.5 bg-blue-200"></span>
                            <span class="text-xs font-black text-blue-700 uppercase">Selesai</span>
                            <span class="text-xs text-blue-600">Masalah diselesaikan & status ditutup.</span>
                        </li>
                    </ul>
                </div>

            </div>
        </div>
    </div>
@endsection