@extends('layouts.public')

@section('content')
    {{-- Set Locale --}}
    @php \Carbon\Carbon::setLocale('id'); @endphp

    <div class="max-w-5xl mx-auto px-4 sm:px-6 pb-20 pt-24">
        
        <div class="space-y-8">
            
            {{-- HEADER SECTION --}}
            <div class="animate-enter relative rounded-[2.5rem] bg-gradient-to-r from-blue-900 via-indigo-900 to-slate-900 p-8 md:p-10 mb-8 text-white shadow-2xl shadow-blue-900/30 overflow-hidden border border-white/10">
               {{-- Background Pattern --}}
               <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
               <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500/30 rounded-full blur-3xl pointer-events-none"></div>

               <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <a href="{{ route('student.habits.dashboard') }}" class="inline-flex items-center gap-2 text-blue-300 hover:text-white transition-colors mb-4 text-[10px] font-bold uppercase tracking-[0.2em]">
                            <i class="ph-bold ph-arrow-left"></i> Kembali ke Dashboard
                        </a>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-2">Jurnal Misi Harian</h1>
                        <p class="text-blue-100/70 text-sm max-w-xl leading-relaxed">
                            "Amalan yang paling dicintai Allah adalah amalan yang rutin dilakukan meskipun sedikit."
                        </p>
                    </div>
                    <div class="hidden md:block">
                         <div class="w-16 h-16 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center border border-white/20 shadow-inner">
                            <i class="ph-duotone ph-pencil-simple-line text-3xl text-blue-200"></i>
                        </div>
                    </div>
               </div>
            </div>

            {{-- ERROR HANDLING --}}
            @if ($errors->any())
                <div class="bg-rose-50 border border-rose-100 rounded-2xl p-6 animate-enter mb-6">
                    <div class="flex items-center gap-3 mb-2">
                        <i class="ph-fill ph-warning-circle text-rose-500 text-xl"></i>
                        <h3 class="font-bold text-rose-800">Periksa Kembali Isian Anda</h3>
                    </div>
                    <ul class="list-disc list-inside text-sm text-rose-600 space-y-1 ml-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- FORM JURNAL --}}
            <form action="{{ route('student.habits.store') }}" method="POST" enctype="multipart/form-data" id="habitForm" class="animate-enter" style="animation-delay: 100ms;">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                    <!-- 1. BANGUN PAGI -->
                    <label class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 relative group cursor-pointer hover:border-blue-300 transition-all md:col-span-2">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center text-2xl shrink-0"><i class="ph-duotone ph-sun-horizon"></i></div>
                            <div class="flex-1">
                                <h3 class="font-bold text-slate-800 text-lg mb-1">1. Bangun Pagi</h3>
                                <p class="text-sm text-slate-500 mb-4">Apakah kamu bangun sebelum adzan subuh hari ini?</p>
                                
                                <div class="flex items-center gap-4">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" name="check_bangun" class="w-6 h-6 rounded-lg border-2 border-slate-300 text-blue-600 focus:ring-blue-500 transition-all" 
                                            {{ old('check_bangun', $todayEntry->habit_1 ?? false) ? 'checked' : '' }}>
                                        <span class="ml-3 font-bold text-slate-700">Ya, saya bangun pagi</span>
                                    </div>
                                    <input type="time" name="habit_1_time" value="{{ old('habit_1_time', $todayEntry->habit_1_time ?? '') }}" class="rounded-xl border-slate-200 text-sm font-bold text-slate-600 focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>
                    </label>

                    <!-- 2. IBADAH HARIAN (SHALAT + ODOA) -->
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 group md:row-span-2"
                         x-data="audioRecorder">
                        
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl"><i class="ph-duotone ph-mosque"></i></div>
                            <div>
                                <h3 class="font-black text-slate-800 text-lg">2. Ibadah Harian</h3>
                                <p class="text-xs text-slate-400 font-medium">Shalat & Tadarus Al-Qur'an</p>
                            </div>
                        </div>
                        
                        {{-- A. SHALAT --}}
                        <div class="space-y-3 mb-8">
                            <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-2 flex items-center gap-2">
                                <i class="ph-bold ph-hands-praying"></i> A. Shalat Wajib & Sunnah
                            </h4>
                            @php 
                                $prayers = [
                                    ['key' => 'prayer_subuh', 'label' => 'Subuh'],
                                    ['key' => 'prayer_dhuha', 'label' => 'Dhuha (Sunnah)', 'scan' => true],
                                    ['key' => 'prayer_dzuhur', 'label' => 'Dzuhur', 'scan' => true],
                                    ['key' => 'prayer_ashar', 'label' => 'Ashar'],
                                    ['key' => 'prayer_maghrib', 'label' => 'Maghrib'],
                                    ['key' => 'prayer_isya', 'label' => 'Isya'],
                                ]; 
                            @endphp

                            @foreach($prayers as $p)
                                @php
                                    // LOGIKA HYBRID: Cek Verifikasi Sekolah
                                    $isVerifiedSchool = false;
                                    if($p['key'] == 'prayer_dhuha') $isVerifiedSchool = $schoolDhuha ?? false;
                                    if($p['key'] == 'prayer_dzuhur') $isVerifiedSchool = $schoolDzuhur ?? false;

                                    // Status Checkbox: Checked jika verified sekolah ATAU input manual ada
                                    $isChecked = old($p['key'], $todayEntry->{$p['key']} ?? false) || $isVerifiedSchool;
                                @endphp

                                <label class="flex items-center justify-between p-3 rounded-2xl border transition-all 
                                    {{ $isVerifiedSchool 
                                        ? 'bg-blue-50 border-blue-200 cursor-not-allowed opacity-90' 
                                        : ($isChecked ? 'bg-emerald-50 border-emerald-200 cursor-pointer' : 'bg-white border-slate-100 cursor-pointer') 
                                    }}">
                                    
                                    <div class="flex items-center gap-3">
                                        <span class="text-sm font-bold {{ $isChecked ? ($isVerifiedSchool ? 'text-blue-700' : 'text-emerald-700') : 'text-slate-600' }}">
                                            {{ $p['label'] }}
                                        </span>
                                        
                                        @if($isVerifiedSchool)
                                            <span class="text-[9px] px-2 py-0.5 rounded bg-blue-100 text-blue-600 font-bold border border-blue-200 flex items-center gap-1">
                                                <i class="ph-fill ph-seal-check"></i> TERVERIFIKASI
                                            </span>
                                        @elseif(isset($p['scan']))
                                            <span class="text-[9px] px-2 py-0.5 rounded bg-slate-100 text-slate-500 font-bold border border-slate-200">
                                                MANUAL
                                            </span>
                                        @endif
                                    </div>

                                    <div class="relative">
                                        <input type="checkbox" name="{{ $p['key'] }}" 
                                            class="w-5 h-5 rounded focus:ring-emerald-500 
                                            {{ $isVerifiedSchool ? 'text-blue-500 border-blue-300 bg-blue-100' : 'text-emerald-600' }}" 
                                            {{ $isChecked ? 'checked' : '' }}
                                            {{ $isVerifiedSchool ? 'disabled' : '' }}>
                                        
                                        {{-- Hidden input agar data tetap "ada" di form submit walaupun disabled --}}
                                        @if($isVerifiedSchool)
                                            <input type="hidden" name="{{ $p['key'] }}" value="1">
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        {{-- B. ODOA (REKAMAN) --}}
                        <div class="pt-6 border-t border-slate-100 border-dashed">
                            <div class="flex items-center justify-between mb-4">
                                <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest flex items-center gap-2">
                                    <i class="ph-bold ph-microphone-stage"></i> B. One Day One Ayat
                                </h4>
                                <div class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-[10px] font-bold animate-pulse">REKAM SUARA</div>
                            </div>

                            {{-- Input Surat & Ayat --}}
                            <div class="grid grid-cols-3 gap-3 mb-4">
                                <div class="col-span-2">
                                    <input type="text" name="odoa_surah" value="{{ old('odoa_surah', $todayEntry->odoa_surah ?? '') }}" placeholder="Nama Surat" class="w-full text-sm rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 placeholder:text-slate-400 font-medium">
                                </div>
                                <div class="col-span-1">
                                    <input type="text" name="odoa_ayat" value="{{ old('odoa_ayat', $todayEntry->odoa_ayat ?? '') }}" placeholder="Ayat" class="w-full text-sm rounded-xl border-slate-200 focus:border-emerald-500 focus:ring-emerald-500 text-center placeholder:text-slate-400 font-medium">
                                </div>
                            </div>

                            {{-- Audio Recorder UI --}}
                            <div class="bg-slate-50 rounded-3xl border border-slate-200 p-5 text-center relative overflow-hidden">
                                
                                @if($todayEntry && $todayEntry->odoa_audio_path)
                                    <div class="mb-5 bg-white p-3 rounded-2xl border border-emerald-100 flex items-center gap-3 shadow-sm" x-show="!isRecording && !audioBlob">
                                        <div class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0">
                                            <i class="ph-fill ph-play"></i>
                                        </div>
                                        <div class="text-left flex-1 overflow-hidden">
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Rekaman Tersimpan</p>
                                            <audio controls class="w-full h-8 mt-1 rounded-lg">
                                                <source src="{{ asset('storage/'.$todayEntry->odoa_audio_path) }}" type="audio/mpeg">
                                            </audio>
                                        </div>
                                    </div>
                                @endif

                                <div x-show="!isSupported" class="mb-4 text-xs text-rose-500 font-bold bg-rose-50 p-2 rounded-lg border border-rose-100" style="display: none;">
                                    <i class="ph-bold ph-warning"></i> Browser tidak mendukung akses mikrofon. Gunakan HTTPS.
                                </div>

                                <div class="flex flex-col items-center gap-3">
                                    <button type="button" @click="toggleRecording" 
                                        class="w-16 h-16 rounded-full flex items-center justify-center transition-all shadow-xl transform active:scale-90 border-4"
                                        :class="isRecording ? 'bg-rose-500 border-rose-200 animate-pulse text-white' : 'bg-white border-slate-100 text-emerald-600 hover:border-emerald-200 hover:text-emerald-500'">
                                        <i class="text-3xl" :class="isRecording ? 'ph-fill ph-stop' : 'ph-fill ph-microphone'"></i>
                                    </button>

                                    <div class="text-center">
                                        <p class="text-sm font-bold text-slate-700" x-text="isRecording ? 'Sedang Merekam...' : (audioBlob ? 'Rekaman Selesai' : 'Tekan untuk Merekam')"></p>
                                        <p class="text-xs font-mono text-slate-400 mt-1" x-show="isRecording" x-text="formatTime(recordingTime)"></p>
                                    </div>
                                </div>

                                <div x-show="audioBlob" class="mt-5 pt-5 border-t border-slate-200 animate-enter" style="display: none;">
                                    <div class="bg-emerald-50 p-3 rounded-2xl border border-emerald-100">
                                        <p class="text-[10px] font-bold text-emerald-600 mb-2 uppercase tracking-wide flex items-center gap-1">
                                            <i class="ph-fill ph-check-circle"></i> Siap Diupload
                                        </p>
                                        <audio x-ref="audioPlayer" controls class="w-full h-8 rounded-lg mb-2"></audio>
                                        <button type="button" @click="resetRecording" class="text-[10px] text-rose-500 font-bold hover:underline flex items-center gap-1 justify-center w-full">
                                            <i class="ph-bold ph-trash"></i> Hapus & Rekam Ulang
                                        </button>
                                    </div>
                                </div>

                                <input type="file" name="odoa_audio" x-ref="audioInput" class="hidden" accept="audio/*">
                            </div>
                        </div>
                    </div>

                    <!-- 3. KEBERSIHAN DIRI -->
                    <label class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 relative group cursor-pointer hover:border-blue-300 transition-all">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-cyan-50 text-cyan-500 flex items-center justify-center text-2xl shrink-0"><i class="ph-duotone ph-drop"></i></div>
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg mb-1">3. Mandi & Gosok Gigi</h3>
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="check_mandi" class="w-6 h-6 rounded-lg border-2 border-slate-300 text-blue-600 focus:ring-blue-500 transition-all" 
                                        {{ old('check_mandi', $todayEntry->habit_2 ?? false) ? 'checked' : '' }}>
                                    <span class="font-bold text-slate-700 text-sm">Sudah Mandi</span>
                                </div>
                            </div>
                        </div>
                    </label>

                    <!-- 4. OLAHRAGA -->
                    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 relative hover:border-blue-300 transition-all">
                        <div class="flex items-start gap-4 mb-3">
                            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center text-2xl shrink-0"><i class="ph-duotone ph-sneaker-move"></i></div>
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg mb-1">4. Olahraga</h3>
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="check_olahraga" class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500" 
                                        {{ old('check_olahraga', $todayEntry->habit_3 ?? false) ? 'checked' : '' }}>
                                    <span class="font-bold text-slate-600 text-sm">Melakukan aktivitas fisik</span>
                                </div>
                            </div>
                        </div>
                        <input type="text" name="habit_3_activity" value="{{ old('habit_3_activity', $todayEntry->habit_3_activity ?? '') }}" placeholder="Contoh: Lari pagi, Senam" class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 placeholder:text-slate-400">
                    </div>

                    <!-- 5. MAKAN SEHAT (SCAN MBG) -->
                    @php
                        // Logika Makan Bergizi (MBG)
                        $schoolMenu = $schoolMbgMenu ?? null; 
                        $displayValue = $schoolMenu ? $schoolMenu : old('habit_5_menu', $todayEntry->habit_5_menu ?? '');
                        $isLockedMBG = !empty($schoolMenu);
                    @endphp
                    <div class="bg-white p-6 rounded-[2rem] shadow-sm border relative transition-all {{ $isLockedMBG ? 'border-lime-200 bg-lime-50/30' : 'border-slate-100 hover:border-blue-300' }}">
                        <div class="flex items-start gap-4 mb-3">
                            <div class="w-12 h-12 rounded-2xl bg-lime-50 text-lime-600 flex items-center justify-center text-2xl shrink-0"><i class="ph-duotone ph-carrot"></i></div>
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg mb-1">5. Makan Bergizi</h3>
                                
                                @if($isLockedMBG)
                                    <div class="inline-flex items-center gap-1.5 px-2 py-1 rounded-lg bg-lime-100 text-lime-700 text-[10px] font-black uppercase tracking-wider mb-2">
                                        <i class="ph-fill ph-qr-code"></i> TERDATA OTOMATIS
                                    </div>
                                    <input type="hidden" name="check_makan" value="1">
                                @else
                                    <div class="flex items-center gap-2">
                                        <input type="checkbox" name="check_makan" class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500" 
                                            {{ old('check_makan', $todayEntry->habit_5 ?? false) ? 'checked' : '' }}>
                                        <span class="font-bold text-slate-600 text-sm">Makan sayur/buah</span>
                                    </div>
                                    <div class="mt-2 inline-flex items-center gap-1.5 px-2 py-1 rounded-lg bg-lime-50 border border-lime-100 text-lime-700 text-[10px] font-black uppercase tracking-wider">
                                        <i class="ph-bold ph-qr-code"></i> Scan MBG / Manual
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <input type="text" 
                               name="habit_5_menu" 
                               value="{{ $displayValue }}" 
                               placeholder="Menu hari ini..." 
                               class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 placeholder:text-slate-400 {{ $isLockedMBG ? 'bg-lime-100 text-lime-800 font-bold cursor-not-allowed border-lime-200' : '' }}"
                               {{ $isLockedMBG ? 'readonly' : '' }}>
                    </div>

                    <!-- 6. BELAJAR -->
                    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 relative hover:border-blue-300 transition-all">
                        <div class="flex items-start gap-4 mb-3">
                            <div class="w-12 h-12 rounded-2xl bg-violet-50 text-violet-600 flex items-center justify-center text-2xl shrink-0"><i class="ph-duotone ph-book-open-text"></i></div>
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg mb-1">6. Gemar Belajar</h3>
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="check_belajar" class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500" 
                                        {{ old('check_belajar', $todayEntry->habit_4 ?? false) ? 'checked' : '' }}>
                                    <span class="font-bold text-slate-600 text-sm">Belajar mandiri di rumah</span>
                                </div>
                            </div>
                        </div>
                        <input type="text" name="habit_4_subject" value="{{ old('habit_4_subject', $todayEntry->habit_4_subject ?? '') }}" placeholder="Materi yang dipelajari..." class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 placeholder:text-slate-400">
                    </div>

                    <!-- 7. SOSIAL & BANTU ORTU -->
                    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 relative hover:border-blue-300 transition-all">
                        <div class="flex items-start gap-4 mb-3">
                            <div class="w-12 h-12 rounded-2xl bg-pink-50 text-pink-500 flex items-center justify-center text-2xl shrink-0"><i class="ph-duotone ph-heart-handshake"></i></div>
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg mb-1">7. Membantu Orang Tua</h3>
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="check_sosial" class="w-5 h-5 rounded border-slate-300 text-blue-600 focus:ring-blue-500" 
                                        {{ old('check_sosial', $todayEntry->habit_6 ?? false) ? 'checked' : '' }}>
                                    <span class="font-bold text-slate-600 text-sm">Melakukan kebaikan</span>
                                </div>
                            </div>
                        </div>
                        <input type="text" name="habit_6_activity" value="{{ old('habit_6_activity', $todayEntry->habit_6_activity ?? '') }}" placeholder="Contoh: Menyapu, Cuci Piring" class="w-full text-sm rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 placeholder:text-slate-400">
                    </div>

                    <!-- 8. TIDUR TERATUR -->
                    <label class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 relative group cursor-pointer hover:border-blue-300 transition-all">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center text-2xl shrink-0"><i class="ph-duotone ph-moon-stars"></i></div>
                            <div class="flex-1">
                                <h3 class="font-bold text-slate-800 text-lg mb-1">8. Tidur Teratur</h3>
                                <p class="text-sm text-slate-500 mb-4">Tidur maksimal jam 21.00 malam.</p>
                                
                                <div class="flex items-center gap-4">
                                    <div class="relative flex items-center">
                                        <input type="checkbox" name="check_tidur" class="w-6 h-6 rounded-lg border-2 border-slate-300 text-blue-600 focus:ring-blue-500 transition-all" 
                                            {{ old('check_tidur', $todayEntry->habit_7 ?? false) ? 'checked' : '' }}>
                                        <span class="ml-3 font-bold text-slate-700">Ya, tidur tepat waktu</span>
                                    </div>
                                    <input type="time" name="habit_7_time" value="{{ old('habit_7_time', $todayEntry->habit_7_time ?? '') }}" class="rounded-xl border-slate-200 text-sm font-bold text-slate-600 focus:border-blue-500 focus:ring-blue-500">
                                </div>
                            </div>
                        </div>
                    </label>

                    <!-- UPLOAD FOTO KEGIATAN -->
                    <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 md:col-span-2">
                        <div class="flex flex-col md:flex-row items-center gap-6">
                            <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-3xl shrink-0"><i class="ph-duotone ph-camera"></i></div>
                            <div class="flex-1 text-center md:text-left">
                                <h3 class="font-bold text-slate-800 text-xl mb-1">Bukti Foto Kegiatan</h3>
                                <p class="text-slate-500 text-sm mb-4">Upload foto kolase kegiatanmu hari ini (Max 5MB).</p>
                                
                                <input type="file" name="habit_photo" class="block w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition cursor-pointer border border-slate-200 rounded-xl">
                            </div>
                        </div>
                        @if($todayEntry && $todayEntry->photo_path)
                            <div class="mt-6 p-4 border border-slate-100 rounded-2xl bg-slate-50/50">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Foto Terupload:</p>
                                <img src="{{ asset('storage/' . $todayEntry->photo_path) }}" class="h-48 rounded-xl object-cover shadow-sm mx-auto md:mx-0">
                            </div>
                        @endif
                    </div>

                </div>

                {{-- TOMBOL SUBMIT --}}
                <div class="mt-10 mb-20">
                    <button type="submit" class="w-full py-5 bg-blue-600 hover:bg-blue-700 text-white font-black text-lg rounded-2xl shadow-xl shadow-blue-600/30 transition-all transform hover:scale-[1.01] active:scale-[0.99] flex items-center justify-center gap-3">
                        <i class="ph-bold ph-paper-plane-right text-2xl"></i>
                        SIMPAN JURNAL SAYA
                    </button>
                    <p class="text-center text-slate-400 text-sm mt-4 font-medium">Pastikan semua data sudah benar sebelum disimpan.</p>
                </div>
            </form>
        </div>
    </div>

    {{-- SCRIPT PENDUKUNG --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('audioRecorder', () => ({
                isRecording: false,
                isSupported: true,
                mediaRecorder: null,
                audioChunks: [],
                audioBlob: null,
                recordingTime: 0,
                timerInterval: null,

                init() {
                    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                        this.isSupported = false;
                        console.warn("Mikrofon tidak didukung atau tidak menggunakan HTTPS.");
                    }
                },

                async toggleRecording() {
                    if (this.isRecording) {
                        this.stopRecording();
                    } else {
                        await this.startRecording();
                    }
                },

                async startRecording() {
                    if (!this.isSupported) {
                        alert("Browser Anda tidak mendukung perekaman audio atau koneksi tidak aman (HTTPS required).");
                        return;
                    }

                    try {
                        const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                        this.mediaRecorder = new MediaRecorder(stream);
                        this.audioChunks = [];

                        this.mediaRecorder.ondataavailable = event => {
                            this.audioChunks.push(event.data);
                        };

                        this.mediaRecorder.onstop = () => {
                            this.audioBlob = new Blob(this.audioChunks, { type: 'audio/webm' });
                            const audioUrl = URL.createObjectURL(this.audioBlob);
                            this.$refs.audioPlayer.src = audioUrl;
                            
                            const file = new File([this.audioBlob], "rec_" + Date.now() + ".webm", { type: "audio/webm" });
                            
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(file);
                            this.$refs.audioInput.files = dataTransfer.files;
                        };

                        this.mediaRecorder.start();
                        this.isRecording = true;
                        this.recordingTime = 0;
                        
                        this.timerInterval = setInterval(() => {
                            this.recordingTime++;
                            if(this.recordingTime >= 300) { 
                                this.stopRecording();
                                alert('Batas waktu perekaman maksimal 5 menit tercapai.');
                            }
                        }, 1000);

                    } catch (err) {
                        console.error("Error akses mikrofon:", err);
                        alert("Gagal mengakses mikrofon. Pastikan Anda mengizinkan akses di browser.");
                    }
                },

                stopRecording() {
                    if (this.mediaRecorder && this.mediaRecorder.state !== 'inactive') {
                        this.mediaRecorder.stop();
                        this.mediaRecorder.stream.getTracks().forEach(track => track.stop());
                    }
                    this.isRecording = false;
                    clearInterval(this.timerInterval);
                },

                resetRecording() {
                    this.audioBlob = null;
                    this.audioChunks = [];
                    this.$refs.audioPlayer.src = '';
                    this.$refs.audioInput.value = '';
                },

                formatTime(seconds) {
                    const min = Math.floor(seconds / 60);
                    const sec = seconds % 60;
                    return `${min}:${sec < 10 ? '0' : ''}${sec}`;
                }
            }));
        });
    </script>

    {{-- SCRIPT SWEETALERT --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success', title: 'Berhasil', text: "{{ session('success') }}",
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 3000,
                    customClass: { popup: 'rounded-xl shadow-lg border border-emerald-100 bg-white' }
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error', title: 'Oops...', text: "{{ session('error') }}",
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 4000,
                    customClass: { popup: 'rounded-xl shadow-lg border border-rose-100 bg-white' }
                });
            @endif
        });
    </script>
@endsection