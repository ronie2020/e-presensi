@extends('layouts.public')

@section('content')
    {{-- CUSTOM STYLES --}}
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        /* Modifikasi warna radio card ke Elevate Dark Glass Theme */
        .radio-card:checked + div { border-color: #56bbf1 !important; background-color: rgba(86, 187, 241, 0.15) !important; color: #ffffff !important; transform: scale(0.98); }
        .radio-card:checked + div .check-icon { opacity: 1; transform: scale(1); }
    </style>

    <div class="min-h-screen bg-[#020b18] text-slate-100 font-sans pb-20 pt-24 relative z-10" 
         x-data="{ 
            isAnonymous: false,
            category: '',
            previewUrl: null,
            fileChosen(event) {
                const file = event.target.files[0];
                if (file) { this.previewUrl = URL.createObjectURL(file); }
            }
         }">

        <div class="max-w-6xl mx-auto px-4 sm:px-6">

            {{-- HEADER SECTION ELEVATE DARK GLASS --}}
            <div class="animate-enter relative rounded-[3rem] bg-gradient-to-r from-[#031d3d] via-[#021124] to-[#031d3d] p-8 md:p-12 mb-10 text-white shadow-2xl overflow-hidden border border-white/10">
                <div class="absolute top-0 right-0 -mt-20 -mr-20 w-[400px] h-[400px] bg-[#56bbf1]/10 rounded-full blur-[80px] opacity-60"></div>
                <div class="absolute bottom-0 left-0 -mb-20 -ml-20 w-[300px] h-[300px] bg-[#56bbf1]/5 rounded-full blur-[80px]"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    <div>
                        <a href="{{ route('student.complaints.index') }}" class="inline-flex items-center gap-2 text-[#56bbf1] hover:text-white transition-colors mb-6 text-[10px] font-black uppercase tracking-[0.2em]">
                            <i class="ph-bold ph-arrow-left"></i> Kembali ke Riwayat
                        </a>
                        <h1 class="text-3xl md:text-5xl font-black tracking-tighter mb-4 leading-tight text-white">
                            Buat Laporan <br class="md:hidden"><span class="text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] to-[#3b82f6]">Keamanan</span>
                        </h1>
                        <p class="text-slate-300 text-sm md:text-base max-w-xl leading-relaxed font-medium">
                            Identitasmu adalah prioritas kami. Gunakan fitur <span class="text-[#56bbf1] font-black bg-white/10 px-3 py-1 rounded-xl border border-white/10 mx-1 shadow-sm">Anonim</span> jika kamu merasa tidak nyaman menampilkan nama.
                        </p>
                    </div>
                    
                    <div class="hidden md:flex w-24 h-24 bg-white/10 backdrop-blur-xl rounded-[2rem] items-center justify-center border border-white/10 shadow-lg group transition-transform hover:scale-110">
                        <i class="ph-duotone ph-shield-check text-5xl text-[#56bbf1]"></i>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 animate-enter" style="animation-delay: 100ms">
                
                {{-- KOLOM KIRI: FORM --}}
                <div class="lg:col-span-8">
                    <form action="{{ route('student.complaints.store') }}" method="POST" enctype="multipart/form-data" class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] backdrop-blur-xl p-8 md:p-12 rounded-[3rem] shadow-2xl border border-white/10 relative overflow-hidden group">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-[#56bbf1] to-[#3b82f6]"></div>
                        @csrf
                        
                        @if ($errors->any())
                            <div class="mb-8 bg-rose-500/10 border border-rose-500/20 rounded-[1.8rem] p-6 flex gap-4 items-start animate-pulse">
                                <i class="ph-fill ph-warning-circle text-rose-400 text-2xl shrink-0 mt-0.5"></i>
                                <div>
                                    <h4 class="text-sm font-black text-rose-300 uppercase tracking-tight">Ada Kendala Pengisian</h4>
                                    <ul class="text-xs text-rose-300 mt-2 list-disc list-inside font-medium leading-relaxed">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        {{-- 1. KATEGORI BENTO GRID --}}
                        <div class="mb-12 mt-4">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6 ml-1">Kategori Masalah <span class="text-rose-400">*</span></label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @php
                                    $categories = [
                                        ['val' => 'Bullying', 'label' => 'Perundungan', 'icon' => 'ph-mask-sad', 'color' => 'rose'],
                                        ['val' => 'Kehilangan', 'label' => 'Kehilangan', 'icon' => 'ph-magnifying-glass', 'color' => 'amber'],
                                        ['val' => 'Fasilitas', 'label' => 'Fasilitas', 'icon' => 'ph-wrench', 'color' => 'emerald'],
                                        ['val' => 'Lainnya', 'label' => 'Lainnya', 'icon' => 'ph-dots-three-circle', 'color' => 'slate'],
                                    ];
                                @endphp
                                @foreach($categories as $cat)
                                <label class="cursor-pointer group/cat">
                                    <input type="radio" name="category" value="{{ $cat['val'] }}" class="radio-card hidden" x-model="category" required>
                                    <div class="border-2 border-white/10 rounded-[1.8rem] p-6 flex flex-col items-center justify-center gap-3 bg-[#021124]/80 hover:border-[#56bbf1]/50 hover:bg-white/5 transition-all h-full relative overflow-hidden shadow-sm">
                                        <i class="ph-duotone {{ $cat['icon'] }} text-4xl text-[#56bbf1] group-hover/cat:scale-110 transition-transform"></i>
                                        <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest group-hover/cat:text-white">{{ $cat['label'] }}</span>
                                        <div class="check-icon absolute top-3 right-3 opacity-0 transition-all duration-300 transform scale-50">
                                            <i class="ph-fill ph-check-circle text-[#56bbf1] text-xl"></i>
                                        </div>
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- 2. DETAIL --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                            <div class="space-y-3">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Waktu Kejadian</label>
                                <input type="date" name="incident_date" required max="{{ date('Y-m-d') }}" class="w-full bg-[#021124]/90 border border-white/15 rounded-2xl focus:ring-4 focus:ring-[#56bbf1]/20 focus:border-[#56bbf1] py-4 px-5 font-bold text-white transition-all">
                            </div>
                            <div class="space-y-3">
                                <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Titik Lokasi</label>
                                <div class="relative">
                                    <i class="ph-bold ph-map-pin absolute left-5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="text" name="location" placeholder="Cth: Belakang Kantin" required class="w-full pl-12 pr-5 py-4 bg-[#021124]/90 border border-white/15 rounded-2xl focus:ring-4 focus:ring-[#56bbf1]/20 focus:border-[#56bbf1] font-bold text-white placeholder:text-slate-500 transition-all">
                                </div>
                            </div>
                        </div>

                        <div class="mb-8 space-y-3">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Ceritakan Secara Detail</label>
                            <textarea name="description" rows="6" required placeholder="Tuliskan kronologi kejadian sejujur-jujurnya..." class="w-full bg-[#021124]/90 border border-white/15 rounded-[2rem] focus:ring-4 focus:ring-[#56bbf1]/20 focus:border-[#56bbf1] p-6 text-sm font-medium text-white leading-relaxed transition-all placeholder:text-slate-500"></textarea>
                        </div>

                        {{-- 3. BUKTI FOTO --}}
                        <div class="mb-10">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1 mb-4">Bukti Pendukung (Opsional)</label>
                            <div class="flex items-center justify-center w-full">
                                <label for="evidence" class="flex flex-col items-center justify-center w-full h-48 border-2 border-white/15 border-dashed rounded-[2.5rem] cursor-pointer bg-[#021124]/60 hover:bg-white/5 hover:border-[#56bbf1] transition-all group/upload overflow-hidden relative shadow-inner">
                                    <img x-show="previewUrl" :src="previewUrl" class="absolute inset-0 w-full h-full object-cover z-10 transition-transform group-hover/upload:scale-105">
                                    <div class="flex flex-col items-center justify-center relative z-20" :class="previewUrl ? 'bg-black/60 p-6 rounded-3xl backdrop-blur-md shadow-xl' : ''">
                                        <i class="ph-duotone ph-camera-plus text-4xl text-slate-400 group-hover/upload:text-[#56bbf1] mb-3 transition-colors"></i>
                                        <p class="text-[11px] text-slate-300 font-black uppercase tracking-widest"><span class="text-[#56bbf1]">Pilih Foto</span> Bukti</p>
                                    </div>
                                    <input id="evidence" name="evidence" type="file" class="hidden" accept="image/*" @change="fileChosen">
                                </label>
                            </div>
                        </div>

                        <div class="pt-10 border-t border-white/10 flex flex-col md:flex-row items-center justify-between gap-6">
                            <p class="text-[10px] text-slate-400 font-black uppercase tracking-widest italic leading-relaxed text-center md:text-left">
                                <i class="ph-fill ph-warning-circle text-amber-400 text-sm"></i> Laporan palsu dapat merugikan dirimu sendiri.
                            </p>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection