@extends('layouts.public')

@section('content')
    {{-- Set Locale --}}
    @php \Carbon\Carbon::setLocale('id'); @endphp

    <div class="max-w-5xl mx-auto px-4 sm:px-6 pb-20 pt-24">
        
        <div class="space-y-8">
            
            {{-- HEADER SECTION: Deep Blue Style --}}
            <div class="animate-enter relative rounded-[2.5rem] bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 p-8 md:p-10 mb-8 text-white shadow-2xl shadow-blue-900/30 overflow-hidden border border-white/10">
                <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-blue-500 rounded-full mix-blend-overlay filter blur-[120px] opacity-20"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <a href="{{ route('student.habits.dashboard') }}" class="inline-flex items-center gap-2 text-blue-300 hover:text-white transition-colors mb-4 text-[10px] font-bold uppercase tracking-[0.2em]">
                            <i class="ph-bold ph-arrow-left"></i> Kembali ke Dashboard
                        </a>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-2">Jurnal Misi Harian</h1>
                        <p class="text-blue-100/70 text-sm max-w-xl leading-relaxed">
                            Setiap kebiasaan baik yang kamu catat adalah satu langkah lebih dekat menuju masa depan yang hebat.
                        </p>
                    </div>
                    
                    <div class="bg-white/10 backdrop-blur-md px-6 py-4 rounded-[2rem] border border-white/20 flex items-center gap-4 shrink-0 shadow-inner">
                        <div class="text-right">
                            <p class="text-[10px] text-blue-300 font-bold uppercase tracking-wider">Laporan Hari Ini</p>
                            <p class="text-lg font-black text-white">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-600 text-white rounded-2xl flex items-center justify-center text-2xl shadow-lg border border-blue-400">
                            <i class="ph-fill ph-calendar-check"></i>
                        </div>
                    </div>
                </div>
            </div>

            @if($todayEntry)
                {{-- STATE: SUDAH MENGISI (Tampilan Apresiasi) --}}
                <div class="space-y-6 animate-enter" style="animation-delay: 100ms">
                    <div class="bg-white rounded-[3rem] p-12 text-center border border-slate-100 shadow-xl shadow-blue-900/5 relative overflow-hidden group">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl -mr-32 -mt-32"></div>
                        <div class="relative z-10">
                            <div class="w-24 h-24 bg-emerald-50 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner animate-bounce">
                                <i class="ph-fill ph-seal-check text-5xl"></i>
                            </div>
                            <h2 class="text-3xl font-black text-slate-800 mb-2">Misi Selesai! ✨</h2>
                            <p class="text-slate-500 text-base max-w-md mx-auto mb-10">
                                Kamu telah menyelesaikan seluruh tantangan hari ini. Terus pertahankan konsistensimu, jagoan!
                            </p>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-lg mx-auto mb-10">
                                <div class="bg-slate-50 p-5 rounded-3xl border border-slate-100 flex items-center gap-4 text-left">
                                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-2xl text-blue-600 shadow-sm"><i class="ph-fill ph-clock"></i></div>
                                    <div>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase">Waktu Lapor</p>
                                        <p class="font-black text-slate-700">{{ $todayEntry->created_at->format('H:i') }} WIB</p>
                                    </div>
                                </div>
                                <div class="bg-slate-50 p-5 rounded-3xl border border-slate-100 flex items-center gap-4 text-left">
                                    <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-2xl text-emerald-500 shadow-sm"><i class="ph-fill ph-star"></i></div>
                                    <div>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase">Status Misi</p>
                                        <p class="font-black text-slate-700">Telah Diverifikasi</p>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('student.habits.dashboard') }}" class="inline-flex items-center gap-3 px-10 py-4 bg-slate-900 hover:bg-blue-600 text-white font-black rounded-2xl shadow-xl transition-all hover:-translate-y-1">
                                <i class="ph-bold ph-house-line text-lg"></i>
                                Kembali ke Dashboard
                            </a>
                        </div>
                    </div>

                    {{-- FEEDBACK GURU --}}
                    @if($todayEntry->teacher_feedback)
                        <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-[2.5rem] p-8 text-white shadow-xl shadow-blue-500/20 relative overflow-hidden">
                            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                            <div class="relative z-10">
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center text-3xl text-white shadow-lg border border-white/20">
                                        <i class="ph-fill ph-chat-circle-dots"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-black text-xl">Apresiasi Bapak/Ibu Guru</h3>
                                        <p class="text-xs text-blue-100 font-medium opacity-80 uppercase tracking-widest">Feedback Hari Ini</p>
                                    </div>
                                </div>
                                <div class="bg-white p-6 rounded-[2rem] shadow-inner">
                                    <p class="italic text-slate-700 text-lg leading-relaxed font-medium">"{{ $todayEntry->teacher_feedback }}"</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

            @else
                {{-- FORM PENGISIAN: Bento Style Cards --}}
                <form action="{{ route('student.habits.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8 animate-enter" x-data="{
                    habit1: {{ old('check_1') || old('habit_1_time') ? 'true' : 'false' }}, 
                    habit3: {{ old('check_3') || old('habit_3_activity') ? 'true' : 'false' }}, 
                    habit4: {{ old('check_4') || old('habit_4_subject') ? 'true' : 'false' }}, 
                    habit5: {{ old('check_5') || old('habit_5_menu') ? 'true' : 'false' }}, 
                    habit6: {{ old('check_6') || old('habit_6_activity') ? 'true' : 'false' }}, 
                    habit7: {{ old('check_7') || old('habit_7_time') ? 'true' : 'false' }},
                    previewUrl: null,
                    fileChosen(event) {
                        const file = event.target.files[0];
                        if (file) { this.previewUrl = URL.createObjectURL(file); }
                    }
                }">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- 1. BANGUN PAGI (Warna Blue) -->
                        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border {{ $errors->has('habit_1_time') ? 'border-red-300 bg-red-50' : 'border-slate-100' }} group transition-all duration-300">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-3xl shadow-sm group-hover:scale-110 transition-transform">
                                        <i class="ph-duotone ph-sun-horizon"></i>
                                    </div>
                                    <h3 class="font-black text-slate-800 text-lg">1. Bangun Pagi</h3>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="check_1" x-model="habit1" class="sr-only peer">
                                    <div class="w-12 h-7 bg-slate-200 rounded-full peer peer-checked:bg-blue-600 transition-colors after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:after:translate-x-full shadow-inner"></div>
                                </label>
                            </div>
                            <div x-show="habit1" x-collapse>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Jam Bangun <span class="text-red-500">*</span></label>
                                        <input type="time" name="habit_1_time" value="{{ old('habit_1_time') }}" class="w-full text-sm rounded-2xl border-slate-100 bg-slate-50 focus:ring-blue-500 focus:bg-white">
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Ibadah Utama</label>
                                        <input type="text" name="habit_1_note" value="{{ old('habit_1_note') }}" placeholder="Contoh: Sholat Subuh" class="w-full text-sm rounded-2xl border-slate-100 bg-slate-50 focus:ring-blue-500 focus:bg-white">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 2. KEBERSIHAN (Warna Cyan) -->
                        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 group">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-3xl shadow-sm group-hover:scale-110 transition-transform">
                                        <i class="ph-duotone ph-drop"></i>
                                    </div>
                                    <h3 class="font-black text-slate-800 text-lg">2. Mandi & Rapi</h3>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="check_2" class="sr-only peer" {{ old('check_2') ? 'checked' : '' }}>
                                    <div class="w-12 h-7 bg-slate-200 rounded-full peer peer-checked:bg-cyan-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:after:translate-x-full"></div>
                                </label>
                            </div>
                        </div>

                        <!-- 3. OLAHRAGA (Warna Indigo) -->
                        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 group">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-3xl shadow-sm group-hover:scale-110 transition-transform">
                                        <i class="ph-duotone ph-sneaker-move"></i>
                                    </div>
                                    <h3 class="font-black text-slate-800 text-lg">3. Olahraga</h3>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="check_3" x-model="habit3" class="sr-only peer">
                                    <div class="w-12 h-7 bg-slate-200 rounded-full peer peer-checked:bg-indigo-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:after:translate-x-full"></div>
                                </label>
                            </div>
                            <div x-show="habit3" x-collapse>
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Jenis Aktivitas</label>
                                <input type="text" name="habit_3_activity" value="{{ old('habit_3_activity') }}" placeholder="Misal: Lari Pagi / Stretching" class="w-full text-sm rounded-2xl border-slate-100 bg-slate-50 focus:ring-indigo-500">
                            </div>
                        </div>

                        <!-- 4. BELAJAR (Warna Blue) -->
                        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 group">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-3xl shadow-sm group-hover:scale-110 transition-transform">
                                        <i class="ph-duotone ph-book-open-text"></i>
                                    </div>
                                    <h3 class="font-black text-slate-800 text-lg">4. Belajar Mandiri</h3>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="check_4" x-model="habit4" class="sr-only peer">
                                    <div class="w-12 h-7 bg-slate-200 rounded-full peer peer-checked:bg-blue-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:after:translate-x-full"></div>
                                </label>
                            </div>
                            <div x-show="habit4" x-collapse>
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Mata Pelajaran / Topik</label>
                                <input type="text" name="habit_4_subject" value="{{ old('habit_4_subject') }}" placeholder="Apa yang kamu pelajari hari ini?" class="w-full text-sm rounded-2xl border-slate-100 bg-slate-50 focus:ring-blue-500">
                            </div>
                        </div>

                        <!-- 5. MAKAN SEHAT (Warna Cyan) -->
                        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 group">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-3xl shadow-sm group-hover:scale-110 transition-transform">
                                        <i class="ph-duotone ph-carrot"></i>
                                    </div>
                                    <h3 class="font-black text-slate-800 text-lg">5. Makan Sehat</h3>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="check_5" x-model="habit5" class="sr-only peer">
                                    <div class="w-12 h-7 bg-slate-200 rounded-full peer peer-checked:bg-cyan-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:after:translate-x-full"></div>
                                </label>
                            </div>
                            <div x-show="habit5" x-collapse>
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Menu Makanan Utama</label>
                                <textarea name="habit_5_menu" rows="2" placeholder="Sebutkan menu sehatmu (sayur, buah, protein)" class="w-full text-sm rounded-2xl border-slate-100 bg-slate-50 focus:ring-cyan-500">{{ old('habit_5_menu') }}</textarea>
                            </div>
                        </div>

                        <!-- 6. SOSIAL (Warna Indigo) -->
                        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 group">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-3xl shadow-sm group-hover:scale-110 transition-transform">
                                        <i class="ph-duotone ph-users-three"></i>
                                    </div>
                                    <h3 class="font-black text-slate-800 text-lg">6. Bantu Orang Tua</h3>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="check_6" x-model="habit6" class="sr-only peer">
                                    <div class="w-12 h-7 bg-slate-200 rounded-full peer peer-checked:bg-indigo-600 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:after:translate-x-full"></div>
                                </label>
                            </div>
                            <div x-show="habit6" x-collapse>
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Bentuk Bantuan</label>
                                <textarea name="habit_6_activity" rows="2" placeholder="Misal: Mencuci piring / Membereskan tempat tidur" class="w-full text-sm rounded-2xl border-slate-100 bg-slate-50 focus:ring-indigo-500">{{ old('habit_6_activity') }}</textarea>
                            </div>
                        </div>

                        <!-- 7. TIDUR CUKUP (Double Width) -->
                        <div class="bg-slate-900 p-8 rounded-[2.5rem] shadow-xl md:col-span-2 group text-white">
                            <div class="flex items-center justify-between mb-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 rounded-2xl bg-white/10 text-blue-300 flex items-center justify-center text-3xl shadow-sm group-hover:rotate-12 transition-transform">
                                        <i class="ph-duotone ph-moon-stars"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-black text-white text-lg leading-tight">7. Tidur Tepat Waktu</h3>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase mt-1">Istirahat Berkualitas</p>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="check_7" x-model="habit7" class="sr-only peer">
                                    <div class="w-12 h-7 bg-white/10 rounded-full peer peer-checked:bg-emerald-500 after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:after:translate-x-full"></div>
                                </label>
                            </div>
                            <div x-show="habit7" x-collapse>
                                <div class="flex flex-col sm:flex-row items-center gap-4 bg-white/5 p-4 rounded-3xl border border-white/5">
                                    <span class="text-sm text-slate-300 font-medium">Jam berapa kamu tidur semalam?</span>
                                    <input type="time" name="habit_7_time" value="{{ old('habit_7_time') }}" class="w-32 text-sm rounded-xl border-none bg-white text-slate-900 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- UPLOAD BUKTI: Styled Modern --}}
                    <div class="bg-white p-8 md:p-12 rounded-[3rem] shadow-sm border {{ $errors->has('habit_photo') ? 'border-red-300 bg-red-50' : 'border-slate-100' }} text-center relative overflow-hidden group">
                        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-500 via-cyan-500 to-indigo-500"></div>
                        <h3 class="font-black text-slate-800 text-xl mb-4">Lengkapi Dokumentasi Misi <span class="text-red-500">*</span></h3>
                        <p class="text-slate-400 text-sm mb-8 max-w-sm mx-auto">Upload foto kolase saat kamu melakukan berbagai kebiasaan baik hari ini.</p>
                        
                        @error('habit_photo') <p class="text-red-600 font-bold text-sm mb-4">{{ $message }}</p> @enderror

                        <div class="flex justify-center w-full">
                            <label for="habit_photo" class="flex flex-col items-center justify-center w-full max-w-2xl h-72 border-2 {{ $errors->has('habit_photo') ? 'border-red-300' : 'border-slate-200' }} border-dashed rounded-[2.5rem] cursor-pointer bg-slate-50/50 hover:bg-blue-50/50 hover:border-blue-400 transition-all group relative overflow-hidden">
                                
                                <img x-show="previewUrl" :src="previewUrl" class="absolute inset-0 w-full h-full object-cover z-10 transition-transform duration-500 group-hover:scale-105">
                                
                                <div class="flex flex-col items-center justify-center pt-5 pb-6 relative z-20" :class="previewUrl ? 'bg-white/80 p-6 rounded-[2rem] backdrop-blur-md shadow-lg border border-white/50' : ''">
                                    <i class="ph-duotone ph-camera-plus text-5xl text-slate-300 group-hover:text-blue-500 mb-4 transition-colors"></i>
                                    <p class="text-sm text-slate-600 font-bold"><span class="font-black text-blue-600">Pilih Foto</span> Kolase Kegiatan</p>
                                    <p class="text-[10px] text-slate-400 mt-2 font-bold uppercase tracking-wider">Format: JPG, PNG • Maks: 3MB</p>
                                </div>
                                <input id="habit_photo" name="habit_photo" type="file" class="hidden" accept="image/*" @change="fileChosen" required>
                            </label>
                        </div>
                    </div>

                    <div class="flex flex-col md:flex-row justify-end items-center gap-6 pt-6">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-[0.2em] italic">Pastikan data yang diisi sudah benar sebelum dikirim</p>
                        <button type="submit" class="w-full md:w-auto px-12 py-5 bg-blue-600 hover:bg-blue-700 text-white font-black rounded-3xl shadow-2xl shadow-blue-600/30 transition-all transform hover:scale-105 flex items-center justify-center gap-3">
                            <i class="ph-bold ph-paper-plane-right text-xl"></i>
                            Kirim Jurnal Sekarang
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