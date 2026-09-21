<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Edit Jadwal Ujian') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-[#020b18] text-slate-100 relative overflow-hidden py-8 sm:py-10 font-sans">
        {{-- Ambient Glow Effects --}}
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-600/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/3 right-10 w-96 h-96 bg-amber-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- HERO SECTION ELEVATE DARK GLASS --}}
            <div class="relative rounded-[2.5rem] bg-gradient-to-r from-[#031d3d]/90 via-[#021124]/90 to-[#1e140a]/90 p-8 mb-8 text-white shadow-2xl backdrop-blur-xl border border-white/10 overflow-hidden">
                <div class="absolute -top-10 -left-10 w-48 h-48 bg-[#56bbf1]/10 rounded-3xl rotate-12 pointer-events-none blur-2xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-amber-500/10 rounded-[3rem] -rotate-12 pointer-events-none blur-2xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <a href="{{ route('cbt.index') }}" class="text-xs font-bold text-sky-400 hover:text-white transition flex items-center gap-1 bg-white/5 hover:bg-white/10 px-3 py-1.5 rounded-full border border-white/10 backdrop-blur-md">
                                <i class="ph-bold ph-arrow-left"></i> Kembali
                            </a>
                            <span class="text-slate-600 text-xs">•</span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Edit Jadwal</span>
                        </div>
                        <h1 class="text-3xl font-extrabold tracking-tight leading-none text-white mb-2">Edit Data Ujian</h1>
                        <p class="text-slate-400 text-sm font-medium">Perbarui detail pelaksanaan, metode ujian, durasi, atau token.</p>
                    </div>
                </div>
            </div>

            {{-- ERROR SUMMARY --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-950/40 border border-rose-500/30 rounded-[2rem] flex items-start gap-3 backdrop-blur-md">
                    <i class="ph-fill ph-warning-circle text-rose-400 text-xl mt-0.5"></i>
                    <div>
                        <h3 class="text-sm font-bold text-rose-200">Terdapat kesalahan pada formulir:</h3>
                        <ul class="list-disc list-inside text-xs text-rose-300 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- FORM CARD --}}
            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 backdrop-blur-xl overflow-hidden">
                <div class="px-8 py-6 border-b border-white/10 bg-white/5 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-xl shadow-sm border border-amber-500/20">
                        <i class="ph-bold ph-pencil-simple-line"></i>
                    </div>
                    <h3 class="font-bold text-white text-lg">Formulir Perubahan Data</h3>
                </div>

                <div class="p-8">
                    <form action="{{ route('cbt.update', $exam->id) }}" method="POST" class="space-y-8" 
                          x-data="{ 
                              startTime: '{{ \Carbon\Carbon::parse($exam->start_time)->format('Y-m-d\TH:i') }}', 
                              endTime: '{{ \Carbon\Carbon::parse($exam->end_time)->format('Y-m-d\TH:i') }}',
                              examType: '{{ old('exam_type', $exam->exam_type ?? 'cbt') }}'
                          }">
                        @csrf
                        @method('PUT')
                        
                        {{-- 1. PILIHAN METODE UJIAN --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 ml-1">Metode Pelaksanaan Ujian <span class="text-rose-400">*</span></label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Opsi 1: CBT Internal -->
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="exam_type" value="cbt" x-model="examType" class="peer sr-only">
                                    <div class="p-5 rounded-[1.5rem] border-2 transition-all peer-checked:border-[#56bbf1] peer-checked:bg-sky-500/10 bg-slate-900/60 border-white/10 hover:border-white/20">
                                        <div class="flex items-center gap-4 relative z-10">
                                            <div class="w-12 h-12 rounded-xl bg-sky-500/20 text-[#56bbf1] flex items-center justify-center text-2xl shrink-0 transition-transform peer-checked:scale-110 border border-sky-500/20">
                                                <i class="ph-fill ph-desktop"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-white text-sm mb-0.5">CBT Internal</h4>
                                                <p class="text-xs text-slate-400 font-medium">Buat soal di sistem ini atau ambil dari Bank Soal.</p>
                                            </div>
                                        </div>
                                        <div class="absolute top-5 right-5 w-5 h-5 rounded-full border-2 border-slate-700 flex items-center justify-center peer-checked:border-[#56bbf1] peer-checked:bg-[#56bbf1] text-transparent peer-checked:text-slate-950 transition-all">
                                            <i class="ph-bold ph-check text-xs"></i>
                                        </div>
                                    </div>
                                </label>

                                <!-- Opsi 2: Google Form -->
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="exam_type" value="google_form" x-model="examType" class="peer sr-only">
                                    <div class="p-5 rounded-[1.5rem] border-2 transition-all peer-checked:border-emerald-500 peer-checked:bg-emerald-500/10 bg-slate-900/60 border-white/10 hover:border-white/20">
                                        <div class="flex items-center gap-4 relative z-10">
                                            <div class="w-12 h-12 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-2xl shrink-0 transition-transform peer-checked:scale-110 border border-emerald-500/20">
                                                <i class="ph-fill ph-google-logo"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-white text-sm mb-0.5">Google Form</h4>
                                                <p class="text-xs text-slate-400 font-medium">Sematkan ujian menggunakan tautan Google Formulir.</p>
                                            </div>
                                        </div>
                                        <div class="absolute top-5 right-5 w-5 h-5 rounded-full border-2 border-slate-700 flex items-center justify-center peer-checked:border-emerald-500 peer-checked:bg-emerald-500 text-transparent peer-checked:text-slate-950 transition-all">
                                            <i class="ph-bold ph-check text-xs"></i>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- INPUT LINK GOOGLE FORM --}}
                        <div x-show="examType === 'google_form'" 
                             x-transition:enter="transition ease-out duration-300" 
                             x-transition:enter-start="opacity-0 transform -translate-y-4" 
                             x-transition:enter-end="opacity-100 transform translate-y-0" 
                             style="display: none;"
                             class="p-6 bg-emerald-950/20 rounded-[1.5rem] border border-emerald-500/30 backdrop-blur-md">
                            <label class="block text-xs font-bold text-emerald-400 uppercase tracking-wider mb-2 ml-1">Tautan / Link Google Form <span class="text-rose-400">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-emerald-400"><i class="ph-bold ph-link"></i></div>
                                <input type="url" name="google_form_url" value="{{ old('google_form_url', $exam->google_form_url ?? '') }}" :required="examType === 'google_form'" 
                                       class="w-full pl-11 rounded-2xl border-white/10 bg-slate-900/90 focus:bg-slate-900 focus:border-emerald-500 focus:ring-emerald-500 font-bold text-white py-3.5 px-4 transition-all placeholder:text-slate-500 [color-scheme:dark]" 
                                       placeholder="Contoh: https://forms.gle/xyz123...">
                            </div>
                            <div class="flex gap-2 mt-3 p-3 bg-emerald-500/10 rounded-xl border border-emerald-500/20">
                                <i class="ph-fill ph-info text-emerald-400 text-lg shrink-0"></i>
                                <p class="text-[11px] text-emerald-300 font-medium leading-snug">Pastikan pengaturan privasi Google Form Anda sudah disetting menjadi <strong>"Publik"</strong> atau dapat diakses oleh siswa agar form dapat dimuat di dalam sistem.</p>
                            </div>
                            @error('google_form_url') <p class="text-xs text-rose-400 mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <hr class="border-white/10">

                        <!-- Kategori Kegiatan / Folder Event -->
                        <div class="mb-6">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Kategori Kegiatan / Folder <span class="text-rose-400">*</span></label>
                            <div class="relative flex items-center">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-[#56bbf1]">
                                    <i class="ph-fill ph-folder-open text-xl"></i>
                                </div>
                                <select name="cbt_event_id" required class="w-full pl-12 rounded-2xl border-white/10 bg-slate-900/90 focus:bg-slate-900 focus:border-[#56bbf1] focus:ring-[#56bbf1] font-bold text-white py-3.5 px-5 appearance-none cursor-pointer transition-all [color-scheme:dark] @error('cbt_event_id') border-rose-500 @enderror">
                                    <option value="" disabled class="bg-slate-900 text-slate-400">-- Pilih Folder Kegiatan --</option>
                                    @foreach($events as $evt)
                                        <option value="{{ $evt->id }}" class="bg-slate-900 text-white" {{ old('cbt_event_id', $exam->cbt_event_id) == $evt->id ? 'selected' : '' }}>
                                            {{ $evt->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-400">
                                    <i class="ph-bold ph-caret-down"></i>
                                </div>
                            </div>
                            @error('cbt_event_id') <p class="text-xs text-rose-400 mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <!-- Judul Ujian -->
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Nama / Judul Ujian <span class="text-rose-400">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $exam->title) }}" required 
                                   class="w-full rounded-2xl border-white/10 bg-slate-900/90 focus:bg-slate-900 focus:border-[#56bbf1] focus:ring-[#56bbf1] font-bold text-white py-3.5 px-5 transition-all placeholder:font-normal placeholder:text-slate-500 [color-scheme:dark] @error('title') border-rose-500 bg-rose-950/20 @enderror" 
                                   placeholder="Contoh: Penilaian Tengah Semester (PTS) Matematika">
                            @error('title') <p class="text-xs text-rose-400 mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Mapel -->
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Mata Pelajaran <span class="text-rose-400">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-book-bookmark"></i></div>
                                    <select name="subject_name" required class="w-full pl-11 rounded-2xl border-white/10 bg-slate-900/90 focus:bg-slate-900 focus:border-[#56bbf1] focus:ring-[#56bbf1] font-bold text-white py-3.5 px-5 appearance-none cursor-pointer transition-all [color-scheme:dark] @error('subject_name') border-rose-500 @enderror">
                                        <option value="" disabled class="bg-slate-900 text-slate-400">-- Pilih Mapel --</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->name }}" class="bg-slate-900 text-white" {{ old('subject_name', $exam->subject_name) == $subject->name ? 'selected' : '' }}>
                                                {{ $subject->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                </div>
                                @error('subject_name') <p class="text-xs text-rose-400 mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                            
                            <!-- Kelas -->
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tingkat Kelas <span class="text-rose-400">*</span></label>
                                <div class="relative">
                                    <select name="class_level" class="w-full rounded-2xl border-white/10 bg-slate-900/90 focus:bg-slate-900 focus:border-[#56bbf1] focus:ring-[#56bbf1] font-bold text-white py-3.5 px-5 appearance-none cursor-pointer transition-all [color-scheme:dark]">
                                        <option value="7" class="bg-slate-900 text-white" {{ old('class_level', $exam->class_level) == '7' ? 'selected' : '' }}>Kelas 7</option>
                                        <option value="8" class="bg-slate-900 text-white" {{ old('class_level', $exam->class_level) == '8' ? 'selected' : '' }}>Kelas 8</option>
                                        <option value="9" class="bg-slate-900 text-white" {{ old('class_level', $exam->class_level) == '9' ? 'selected' : '' }}>Kelas 9</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Waktu Mulai -->
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Waktu Mulai <span class="text-rose-400">*</span></label>
                                <input type="datetime-local" name="start_time" x-model="startTime" required 
                                       class="w-full rounded-2xl border-white/10 bg-slate-900/90 focus:bg-slate-900 focus:border-[#56bbf1] focus:ring-[#56bbf1] font-bold text-white py-3.5 px-5 transition-all [color-scheme:dark] @error('start_time') border-rose-500 @enderror">
                                @error('start_time') <p class="text-xs text-rose-400 mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                            
                            <!-- Waktu Selesai -->
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Waktu Selesai <span class="text-rose-400">*</span></label>
                                <input type="datetime-local" name="end_time" x-model="endTime" required 
                                       class="w-full rounded-2xl border-white/10 bg-slate-900/90 focus:bg-slate-900 focus:border-[#56bbf1] focus:ring-[#56bbf1] font-bold text-white py-3.5 px-5 transition-all [color-scheme:dark] @error('end_time') border-rose-500 @enderror"
                                       :min="startTime">
                                @error('end_time') <p class="text-xs text-rose-400 mt-1 font-bold">{{ $message }}</p> @enderror
                                
                                <p x-show="startTime && endTime && endTime < startTime" class="text-[10px] text-rose-400 font-bold mt-1 flex items-center gap-1 animate-pulse">
                                    <i class="ph-bold ph-warning"></i> Waktu selesai tidak boleh sebelum waktu mulai!
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                            <!-- Durasi -->
                            <div class="col-span-1">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Durasi (Menit)</label>
                                <div class="relative">
                                    <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $exam->duration_minutes) }}" required class="w-full rounded-2xl border-white/10 bg-slate-900/90 focus:bg-slate-900 focus:border-[#56bbf1] focus:ring-[#56bbf1] font-bold text-white py-3.5 px-5 transition-all text-center [color-scheme:dark]">
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500 text-[10px] font-bold">MIN</div>
                                </div>
                            </div>

                            <!-- Jumlah Soal Ditampilkan -->
                            <div class="col-span-1" x-show="examType === 'cbt'">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tampilkan Soal</label>
                                <div class="relative">
                                    <input type="number" name="question_limit" value="{{ old('question_limit', $exam->question_limit ?? 0) }}" class="w-full rounded-2xl border-white/10 bg-slate-900/90 focus:bg-slate-900 focus:border-[#56bbf1] focus:ring-[#56bbf1] font-bold text-white py-3.5 px-5 transition-all text-center [color-scheme:dark]" placeholder="0 = Semua">
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500 text-[10px] font-bold">SOAL</div>
                                </div>
                                <p class="text-[10px] text-slate-500 mt-1 ml-1 font-medium">*Isi 0 untuk menampilkan semua soal di bank</p>
                            </div>
                            
                            <!-- KKM -->
                            <div class="col-span-1" x-show="examType === 'cbt'">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">KKM / Kriteria</label>
                                <input type="number" name="passing_grade" value="{{ old('passing_grade', $exam->passing_grade) }}" class="w-full rounded-2xl border-white/10 bg-slate-900/90 focus:bg-slate-900 focus:border-[#56bbf1] focus:ring-[#56bbf1] font-bold text-white py-3.5 px-5 transition-all text-center [color-scheme:dark]">
                            </div>

                             <!-- Token -->
                             <div class="col-span-2 md:col-span-1">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Token (Opsional)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-key"></i></div>
                                    <input type="text" name="token" value="{{ old('token', $exam->token) }}" maxlength="6" class="w-full pl-11 rounded-2xl border-white/10 bg-slate-900/90 focus:bg-slate-900 focus:border-[#56bbf1] focus:ring-[#56bbf1] font-mono font-black text-white py-3.5 px-5 transition-all uppercase tracking-widest placeholder:tracking-normal [color-scheme:dark] @error('token') border-rose-500 @enderror" placeholder="KOSONG = TETAP">
                                </div>
                                <p class="text-[10px] text-slate-500 mt-1 ml-1 font-medium">*Kosongkan jika tidak ingin mengubah token</p>
                                @error('token') <p class="text-xs text-rose-400 mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- FITUR ANTI KECURANGAN --}}
                        <div class="col-span-2 md:col-span-3 mt-4" x-show="examType === 'cbt'">
                            <div class="bg-rose-950/20 rounded-2xl p-5 border border-rose-500/20 backdrop-blur-md">
                                <h4 class="text-sm font-black text-rose-300 flex items-center gap-2 mb-4">
                                    <i class="ph-fill ph-shield-warning text-rose-400 text-lg"></i> Keamanan & Anti-Kecurangan Lanjutan
                                </h4>
                                
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <!-- Acak Soal -->
                                    <label class="flex items-start gap-4 p-4 bg-slate-900/80 rounded-[1.5rem] border border-white/10 cursor-pointer hover:border-rose-500/40 transition-colors group">
                                        <div class="relative flex items-center mt-1">
                                            <input type="checkbox" name="randomize_questions" value="1" {{ old('randomize_questions', $exam->randomize_questions) ? 'checked' : '' }} class="peer sr-only">
                                            <div class="w-10 h-6 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-500 border border-white/10"></div>
                                        </div>
                                        <div>
                                            <p class="font-bold text-white text-sm group-hover:text-rose-300 transition-colors">Acak Urutan Soal</p>
                                            <p class="text-xs text-slate-400 font-medium leading-relaxed mt-0.5">Setiap siswa akan mendapatkan urutan nomor soal yang berbeda.</p>
                                        </div>
                                    </label>

                                    <!-- Acak Opsi -->
                                    <label class="flex items-start gap-4 p-4 bg-slate-900/80 rounded-[1.5rem] border border-white/10 cursor-pointer hover:border-rose-500/40 transition-colors group">
                                        <div class="relative flex items-center mt-1">
                                            <input type="checkbox" name="randomize_options" value="1" {{ old('randomize_options', $exam->randomize_options) ? 'checked' : '' }} class="peer sr-only">
                                            <div class="w-10 h-6 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-500 border border-white/10"></div>
                                        </div>
                                        <div>
                                            <p class="font-bold text-white text-sm group-hover:text-rose-300 transition-colors">Acak Opsi Jawaban (A, B, C, D)</p>
                                            <p class="text-xs text-slate-400 font-medium leading-relaxed mt-0.5">Posisi opsi akan diacak (Hanya berlaku untuk Pilihan Ganda).</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Aktifkan Ujian --}}
                        <div class="flex flex-row items-center gap-4 bg-white/5 p-5 rounded-2xl border border-white/10">
                            <div class="relative flex items-center">
                                <input type="checkbox" id="active" name="is_active" value="1" {{ old('is_active', $exam->is_active) ? 'checked' : '' }} class="peer sr-only">
                                <div class="w-11 h-6 bg-slate-800 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500 cursor-pointer border border-white/10"></div>
                            </div>
                            <label for="active" class="text-sm font-bold text-white cursor-pointer select-none">Status Ujian Aktif</label>
                        </div>

                        {{-- Actions --}}
                        <div class="pt-4 border-t border-white/10 flex flex-col-reverse md:flex-row justify-end gap-3">
                            <a href="{{ route('cbt.index') }}" class="w-full md:w-auto text-center px-6 py-3.5 bg-slate-900 border border-white/10 rounded-xl text-slate-300 font-bold hover:bg-slate-800 transition text-sm shadow-sm">Batal</a>
                            <button type="submit" class="w-full md:w-auto px-8 py-3.5 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white rounded-xl font-bold transition shadow-lg shadow-sky-500/25 text-sm flex items-center justify-center gap-2 transform active:scale-95 border border-sky-400/30">
                                <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>