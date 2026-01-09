@extends('layouts.public')

@section('content')
    {{-- Set Locale --}}
    @php \Carbon\Carbon::setLocale('id'); @endphp

    <div class="max-w-5xl mx-auto px-4 sm:px-6 pb-20 pt-24">
        
        <div class="space-y-8">
            
            {{-- HEADER SECTION (TETAP SAMA) --}}
            <div class="animate-enter relative rounded-[2.5rem] bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 p-8 md:p-10 mb-8 text-white shadow-2xl shadow-blue-900/30 overflow-hidden border border-white/10">
               {{-- ... (Header content sama) ... --}}
               <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <a href="{{ route('student.habits.dashboard') }}" class="inline-flex items-center gap-2 text-blue-300 hover:text-white transition-colors mb-4 text-[10px] font-bold uppercase tracking-[0.2em]">
                            <i class="ph-bold ph-arrow-left"></i> Kembali ke Dashboard
                        </a>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-2">Jurnal Misi Harian</h1>
                        <p class="text-blue-100/70 text-sm max-w-xl leading-relaxed">
                            Cicil misimu sepanjang hari. Jangan lupa simpan setiap perubahan!
                        </p>
                    </div>
                    {{-- ... --}}
               </div>
            </div>

            @php
                // Cek apakah semua habit inti (1-7) sudah terisi true
                $isFullyComplete = $todayEntry && 
                                   $todayEntry->habit_1 && 
                                   $todayEntry->habit_2 && 
                                   $todayEntry->habit_3 && 
                                   $todayEntry->habit_4 && 
                                   $todayEntry->habit_5 && 
                                   $todayEntry->habit_6 && 
                                   $todayEntry->habit_7;
            @endphp

            @if($isFullyComplete)
                {{-- TAMPILAN APRESIASI (Hanya Muncul Jika SUDAH FULL 100%) --}}
                <div class="space-y-6 animate-enter" style="animation-delay: 100ms">
                     {{-- ... (Copy tampilan 'Misi Selesai' yang lama di sini) ... --}}
                     <div class="bg-white rounded-[3rem] p-12 text-center border border-slate-100 shadow-xl shadow-blue-900/5 relative overflow-hidden group">
                        {{-- ... Content apresiasi ... --}}
                        <h2 class="text-3xl font-black text-slate-800 mb-2">Misi Hari Ini Sempurna! ✨</h2>
                        <p class="text-slate-500 text-base max-w-md mx-auto mb-10">
                            Hebat! Kamu sudah melengkapi seluruh rangkaian kebiasaan baik hari ini.
                        </p>
                        <a href="{{ route('student.habits.dashboard') }}" class="inline-flex items-center gap-3 px-10 py-4 bg-slate-900 hover:bg-blue-600 text-white font-black rounded-2xl shadow-xl transition-all">
                            <i class="ph-bold ph-house-line text-lg"></i> Kembali ke Dashboard
                        </a>
                    </div>
                </div>
            @else
                {{-- FORM PENGISIAN (SELALU MUNCUL SELAMA BELUM LENGKAP) --}}
                
                {{-- Notifikasi jika sudah ada data tapi belum lengkap --}}
                @if($todayEntry)
                <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-center gap-3 text-amber-800 text-sm font-bold mb-4">
                    <i class="ph-fill ph-info"></i>
                    <p>Kamu sudah mengisi sebagian. Silakan lanjutkan mengisi sisa misimu!</p>
                </div>
                @endif

                <form action="{{ route('student.habits.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8 animate-enter" 
                    x-data="{
                    habit1: {{ ($todayEntry->habit_1 ?? false) || old('check_1') ? 'true' : 'false' }}, 
                    habit3: {{ ($todayEntry->habit_3 ?? false) || old('check_3') ? 'true' : 'false' }}, 
                    habit4: {{ ($todayEntry->habit_4 ?? false) || old('check_4') ? 'true' : 'false' }}, 
                    habit5: {{ ($todayEntry->habit_5 ?? false) || old('check_5') ? 'true' : 'false' }}, 
                    habit6: {{ ($todayEntry->habit_6 ?? false) || old('check_6') ? 'true' : 'false' }}, 
                    habit7: {{ ($todayEntry->habit_7 ?? false) || old('check_7') ? 'true' : 'false' }},
                    previewUrl: '{{ $todayEntry && $todayEntry->photo_path ? asset('storage/'.$todayEntry->photo_path) : null }}',
                    fileChosen(event) {
                        const file = event.target.files[0];
                        if (file) { this.previewUrl = URL.createObjectURL(file); }
                    }
                }">
                    @csrf
                    
                    {{-- Input Hidden ID untuk update jika perlu di controller --}}
                    @if($todayEntry)
                        <input type="hidden" name="existing_id" value="{{ $todayEntry->id }}">
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- 1. BANGUN PAGI -->
                        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border {{ $errors->has('habit_1_time') ? 'border-red-300 bg-red-50' : 'border-slate-100' }} group transition-all duration-300">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-3xl shadow-sm group-hover:scale-110 transition-transform">
                                        <i class="ph-duotone ph-sun-horizon"></i>
                                    </div>
                                    <h3 class="font-black text-slate-800 text-lg">1. Bangun Pagi</h3>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="check_1" x-model="habit1" class="sr-only peer" {{ ($todayEntry->habit_1 ?? false) ? 'checked' : '' }}>
                                    <div class="w-12 h-7 bg-slate-200 rounded-full peer peer-checked:bg-blue-600 transition-colors after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:after:translate-x-full shadow-inner"></div>
                                </label>
                            </div>
                            <div x-show="habit1" x-collapse>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Jam Bangun</label>
                                        <input type="time" name="habit_1_time" value="{{ $todayEntry->habit_1_time ?? old('habit_1_time') }}" class="w-full text-sm rounded-2xl border-slate-100 bg-slate-50 focus:ring-blue-500 focus:bg-white">
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Ibadah Utama</label>
                                        <input type="text" name="habit_1_note" value="{{ $todayEntry->habit_1_note ?? old('habit_1_note') }}" placeholder="Contoh: Sholat Subuh" class="w-full text-sm rounded-2xl border-slate-100 bg-slate-50 focus:ring-blue-500 focus:bg-white">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. KEBERSIHAN -->
                        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 group">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-3xl shadow-sm group-hover:scale-110 transition-transform">
                                        <i class="ph-duotone ph-drop"></i>
                                    </div>
                                    <h3 class="font-black text-slate-800 text-lg">2. Mandi & Rapi</h3>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="check_2" class="sr-only peer" {{ ($todayEntry->habit_2 ?? false) || old('check_2') ? 'checked' : '' }}>
                                    <div class="w-12 h-7 bg-slate-200 rounded-full peer peer-checked:bg-cyan-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:after:translate-x-full"></div>
                                </label>
                            </div>
                        </div>

                        <!-- 3. OLAHRAGA -->
                        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 group">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-3xl shadow-sm group-hover:scale-110 transition-transform">
                                        <i class="ph-duotone ph-sneaker-move"></i>
                                    </div>
                                    <h3 class="font-black text-slate-800 text-lg">3. Olahraga</h3>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="check_3" x-model="habit3" class="sr-only peer" {{ ($todayEntry->habit_3 ?? false) ? 'checked' : '' }}>
                                    <div class="w-12 h-7 bg-slate-200 rounded-full peer peer-checked:bg-indigo-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:after:translate-x-full"></div>
                                </label>
                            </div>
                            <div x-show="habit3" x-collapse>
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Jenis Aktivitas</label>
                                <input type="text" name="habit_3_activity" value="{{ $todayEntry->habit_3_activity ?? old('habit_3_activity') }}" placeholder="Misal: Lari Pagi / Stretching" class="w-full text-sm rounded-2xl border-slate-100 bg-slate-50 focus:ring-indigo-500">
                            </div>
                        </div>

                        <!-- 4. BELAJAR -->
                        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 group">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-3xl shadow-sm group-hover:scale-110 transition-transform">
                                        <i class="ph-duotone ph-book-open-text"></i>
                                    </div>
                                    <h3 class="font-black text-slate-800 text-lg">4. Belajar Mandiri</h3>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="check_4" x-model="habit4" class="sr-only peer" {{ ($todayEntry->habit_4 ?? false) ? 'checked' : '' }}>
                                    <div class="w-12 h-7 bg-slate-200 rounded-full peer peer-checked:bg-blue-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:after:translate-x-full"></div>
                                </label>
                            </div>
                            <div x-show="habit4" x-collapse>
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Mata Pelajaran / Topik</label>
                                <input type="text" name="habit_4_subject" value="{{ $todayEntry->habit_4_subject ?? old('habit_4_subject') }}" placeholder="Apa yang kamu pelajari hari ini?" class="w-full text-sm rounded-2xl border-slate-100 bg-slate-50 focus:ring-blue-500">
                            </div>
                        </div>

                        <!-- 5. MAKAN SEHAT (INTEGRASI SCANNER) -->
                        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 group">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-3xl shadow-sm group-hover:scale-110 transition-transform">
                                        <i class="ph-duotone ph-carrot"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-black text-slate-800 text-lg">5. Makan Sehat</h3>
                                        {{-- Indikator Scan --}}
                                        @if($todayEntry && $todayEntry->habit_5)
                                            <span class="text-[10px] text-emerald-500 font-bold uppercase tracking-wide flex items-center gap-1">
                                                <i class="ph-fill ph-check-circle"></i> Terverifikasi (Scan/Manual)
                                            </span>
                                        @else
                                            <span class="text-[10px] text-orange-500 font-bold uppercase tracking-wide flex items-center gap-1">
                                                <i class="ph-bold ph-qr-code"></i> Scan QR di Kantin
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    {{-- Tetap allow manual input jika scan gagal --}}
                                    <input type="checkbox" name="check_5" x-model="habit5" class="sr-only peer" {{ ($todayEntry->habit_5 ?? false) ? 'checked' : '' }}>
                                    <div class="w-12 h-7 bg-slate-200 rounded-full peer peer-checked:bg-cyan-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:after:translate-x-full"></div>
                                </label>
                            </div>
                            <div x-show="habit5" x-collapse>
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Menu Makanan Utama</label>
                                <textarea name="habit_5_menu" rows="2" placeholder="Menu otomatis terisi jika scan, atau ketik manual jika lupa." class="w-full text-sm rounded-2xl border-slate-100 bg-slate-50 focus:ring-cyan-500">{{ $todayEntry->habit_5_menu ?? old('habit_5_menu') }}</textarea>
                            </div>
                        </div>

                        <!-- 6. SOSIAL -->
                        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 group">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-3xl shadow-sm group-hover:scale-110 transition-transform">
                                        <i class="ph-duotone ph-users-three"></i>
                                    </div>
                                    <h3 class="font-black text-slate-800 text-lg">6. Bantu Orang Tua</h3>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="check_6" x-model="habit6" class="sr-only peer" {{ ($todayEntry->habit_6 ?? false) ? 'checked' : '' }}>
                                    <div class="w-12 h-7 bg-slate-200 rounded-full peer peer-checked:bg-indigo-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:after:translate-x-full"></div>
                                </label>
                            </div>
                            <div x-show="habit6" x-collapse>
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Bentuk Bantuan</label>
                                <textarea name="habit_6_activity" rows="2" placeholder="Misal: Mencuci piring / Membereskan tempat tidur" class="w-full text-sm rounded-2xl border-slate-100 bg-slate-50 focus:ring-indigo-500">{{ $todayEntry->habit_6_activity ?? old('habit_6_activity') }}</textarea>
                            </div>
                        </div>

                        <!-- 7. TIDUR CUKUP -->
                        <div class="bg-slate-900 p-8 rounded-[2.5rem] shadow-xl md:col-span-2 group text-white">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl bg-white/10 text-blue-300 flex items-center justify-center text-3xl shadow-sm group-hover:rotate-12 transition-transform">
                                        <i class="ph-duotone ph-moon-stars"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-black text-white text-lg leading-tight">7. Tidur Tepat Waktu</h3>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase mt-1">Istirahat Semalam</p>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="check_7" x-model="habit7" class="sr-only peer" {{ ($todayEntry->habit_7 ?? false) ? 'checked' : '' }}>
                                    <div class="w-12 h-7 bg-white/10 rounded-full peer peer-checked:bg-emerald-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:after:translate-x-full"></div>
                                </label>
                            </div>
                            <div x-show="habit7" x-collapse>
                                <div class="flex flex-col sm:flex-row items-center gap-4 bg-white/5 p-4 rounded-3xl border border-white/5">
                                    <span class="text-sm text-slate-300 font-medium">Jam berapa kamu tidur <span class="text-white font-bold underline">semalam</span>?</span>
                                    <input type="time" name="habit_7_time" value="{{ $todayEntry->habit_7_time ?? old('habit_7_time') }}" class="w-32 text-sm rounded-xl border-none bg-white text-slate-900 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- UPLOAD BUKTI (Hanya wajib jika belum pernah upload) --}}
                    <div class="bg-white p-8 md:p-12 rounded-[3rem] shadow-sm border {{ $errors->has('habit_photo') ? 'border-red-300 bg-red-50' : 'border-slate-100' }} text-center relative overflow-hidden group">
                        {{-- ... (UI Sama) ... --}}
                        <h3 class="font-black text-slate-800 text-xl mb-4">Lengkapi Dokumentasi Misi</h3>
                        
                        {{-- Logic Photo Required --}}
                        @if($todayEntry && $todayEntry->photo_path)
                             <p class="text-emerald-500 font-bold text-sm mb-4"><i class="ph-fill ph-check-circle"></i> Foto sudah diupload. Upload ulang untuk mengganti.</p>
                        @else
                             <p class="text-slate-400 text-sm mb-8 max-w-sm mx-auto">Upload foto kolase saat kamu melakukan berbagai kebiasaan baik hari ini. <span class="text-red-500">*</span></p>
                        @endif

                        <div class="flex justify-center w-full">
                            <label for="habit_photo" class="flex flex-col items-center justify-center w-full max-w-2xl h-72 border-2 border-dashed border-slate-200 rounded-[2.5rem] cursor-pointer bg-slate-50/50 hover:bg-blue-50/50 hover:border-blue-400 transition-all group relative overflow-hidden">
                                
                                <img x-show="previewUrl" :src="previewUrl" class="absolute inset-0 w-full h-full object-cover z-10 transition-transform duration-500 group-hover:scale-105">
                                
                                <div class="flex flex-col items-center justify-center pt-5 pb-6 relative z-20" :class="previewUrl ? 'bg-white/80 p-6 rounded-[2rem] backdrop-blur-md shadow-lg border border-white/50' : ''">
                                    <i class="ph-duotone ph-camera-plus text-5xl text-slate-300 group-hover:text-blue-500 mb-4 transition-colors"></i>
                                    <p class="text-sm text-slate-600 font-bold"><span class="font-black text-blue-600">Pilih Foto</span> Kolase Kegiatan</p>
                                </div>
                                {{-- Jika sudah ada foto, tidak wajib (required hilang) --}}
                                <input id="habit_photo" name="habit_photo" type="file" class="hidden" accept="image/*" @change="fileChosen" {{ ($todayEntry && $todayEntry->photo_path) ? '' : 'required' }}>
                            </label>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row justify-end items-center gap-6 pt-6">
                        <button type="submit" class="w-full md:w-auto px-12 py-5 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-3xl shadow-2xl shadow-blue-600/30 transition-all transform hover:scale-105 flex items-center justify-center gap-3">
                            <i class="ph-bold ph-floppy-disk text-xl"></i>
                            {{ $todayEntry ? 'Simpan Perubahan' : 'Kirim Jurnal' }}
                        </button>
                    </div>
                </form>
            @endif
           {{-- RIWAYAT: Bottom List --}}
            <div class="pt-16 border-t border-slate-100 animate-enter" style="animation-delay: 200ms">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-black text-slate-800 flex items-center gap-4">
                        <i class="ph-bold ph-clock-counter-clockwise text-blue-600"></i> Riwayat Jurnal
                    </h2>
                    <span class="text-xs font-bold text-slate-400 bg-slate-50 px-4 py-2 rounded-xl border border-slate-100 uppercase tracking-widest">
                        {{ \Carbon\Carbon::now()->translatedFormat('F Y') }}
                    </span>
                </div>

                @if(isset($history) && $history->count() > 0)
                    <div class="grid grid-cols-1 gap-4">
                        @foreach($history->sortByDesc('report_date') as $item)
                            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm hover:border-blue-200 transition-all group overflow-hidden relative">
                                <div class="absolute top-0 right-0 w-24 h-24 bg-slate-50 rounded-full blur-2xl -mr-12 -mt-12 opacity-50"></div>
                                
                                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
                                    <div class="flex items-center gap-6">
                                        <div class="w-16 h-16 rounded-2xl bg-slate-50 border border-slate-100 flex flex-col items-center justify-center shrink-0 group-hover:bg-blue-600 group-hover:border-blue-500 transition-all duration-300">
                                            <span class="text-[10px] font-bold text-slate-400 group-hover:text-blue-200 uppercase tracking-tighter">{{ $item->report_date->translatedFormat('D') }}</span>
                                            <span class="text-2xl font-black text-slate-700 group-hover:text-white leading-none mt-1">{{ $item->report_date->format('d') }}</span>
                                        </div>
                                        <div>
                                            <h4 class="font-black text-slate-800 text-base mb-1">
                                                {{ $item->habit_1 ? 'Bangun Pukul ' . $item->habit_1_time : 'Jurnal Harian' }}
                                            </h4>
                                            <div class="flex flex-wrap gap-2">
                                                @if($item->habit_3) <span class="text-[9px] bg-blue-50 text-blue-600 px-2 py-0.5 rounded-lg border border-blue-100 font-black uppercase">Olahraga</span> @endif
                                                @if($item->habit_4) <span class="text-[9px] bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded-lg border border-indigo-100 font-black uppercase">Belajar</span> @endif
                                                @if($item->habit_7) <span class="text-[9px] bg-slate-900 text-blue-300 px-2 py-0.5 rounded-lg font-black uppercase">Tidur</span> @endif
                                            </div>
                                        </div>
                                    </div>

                                    @if($item->teacher_feedback)
                                        <div class="flex-1 md:max-w-md bg-blue-50/50 p-4 rounded-2xl border border-blue-100 relative group-hover:bg-white transition-colors shadow-inner">
                                            <p class="text-xs text-blue-700 italic leading-relaxed">
                                                <span class="font-black text-[9px] uppercase not-italic block mb-1 text-blue-400 tracking-wider">Pesan Guru:</span>
                                                "{{ $item->teacher_feedback }}"
                                            </p>
                                        </div>
                                    @else
                                        <div class="text-right">
                                            <span class="inline-flex items-center gap-2 text-[10px] font-bold text-slate-300 bg-slate-50 px-3 py-1.5 rounded-full uppercase italic border border-slate-100">
                                                <i class="ph-bold ph-hourglass"></i> Menunggu Feedback
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="bg-white rounded-[3rem] p-20 text-center border-2 border-dashed border-slate-200">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="ph-duotone ph-notebook text-4xl text-slate-300"></i>
                        </div>
                        <h4 class="font-black text-slate-700 mb-2">Belum Ada Jejak</h4>
                        <p class="text-sm text-slate-400 font-medium max-w-xs mx-auto">Ayo buat catatan kebiasaan pertamamu hari ini untuk memulai perjalanan hebat!</p>
                    </div>
                @endif
            </div>

        </div>
    </div>

    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        input[type="time"]::-webkit-calendar-picker-indicator { opacity: 0.3; }
        .custom-scrollbar::-webkit-scrollbar { height: 0px; background: transparent; }
    </style>
@endsection