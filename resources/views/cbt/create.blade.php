<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#2c3f61] leading-tight">
            {{ __('Buat Jadwal Ujian') }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-10 font-sans text-[#2c3f61]">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO SECTION MICROSOFT ELEVATE THEME --}}
            <div class="relative rounded-[2rem] bg-gradient-to-r from-[#56bbf1] via-[#e5eff5] to-[#f4d1c0] p-8 mb-8 text-[#2c3f61] shadow-xl shadow-[#56bbf1]/10 overflow-hidden border border-white/60">
                {{-- Abstract Shapes Ornaments --}}
                <div class="absolute -top-10 -left-10 w-48 h-48 bg-[#0d52a1]/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-[#f9a282]/20 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <a href="{{ route('cbt.index') }}" class="text-xs font-bold text-[#0d52a1] hover:text-[#2c3f61] transition flex items-center gap-1 bg-white/60 px-3 py-1 rounded-full border border-white backdrop-blur-sm shadow-sm">
                                <i class="ph-bold ph-arrow-left"></i> Kembali
                            </a>
                            <span class="text-[#2c3f61]/30 text-xs">•</span>
                            <span class="text-[10px] font-bold text-[#2c3f61]/70 uppercase tracking-wider">Jadwal Baru</span>
                        </div>
                        <h1 class="text-3xl font-extrabold tracking-tight leading-none text-[#2c3f61] mb-2">Buat Ujian Baru</h1>
                        <p class="text-[#2c3f61]/80 text-sm font-medium">Atur detail pelaksanaan ujian, metode, durasi, dan kelas target.</p>
                    </div>
                </div>
            </div>

            {{-- ERROR SUMMARY --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-[2rem] flex items-start gap-3">
                    <i class="ph-fill ph-warning-circle text-rose-500 text-xl mt-0.5"></i>
                    <div>
                        <h3 class="text-sm font-bold text-rose-800">Terdapat kesalahan pada formulir:</h3>
                        <ul class="list-disc list-inside text-xs text-rose-600 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- FORM CARD --}}
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-[#56bbf1]/10 border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 bg-[#e5eff5]/30 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#56bbf1]/20 text-[#0d52a1] flex items-center justify-center text-xl shadow-sm">
                        <i class="ph-bold ph-pencil-simple-line"></i>
                    </div>
                    <h3 class="font-bold text-[#2c3f61] text-lg">Formulir Ujian</h3>
                </div>

                <div class="p-8">
                    {{-- INIT ALPINE: Tambahkan state examType --}}
                    <form action="{{ route('cbt.store') }}" method="POST" class="space-y-8" 
                          x-data="{ 
                              startTime: '{{ old('start_time') }}', 
                              endTime: '{{ old('end_time') }}',
                              examType: '{{ old('exam_type', 'cbt') }}' 
                          }">
                        @csrf
                        
                        {{-- 1. PILIHAN METODE UJIAN --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-3 ml-1">Metode Pelaksanaan Ujian <span class="text-rose-500">*</span></label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Opsi 1: CBT Internal -->
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="exam_type" value="cbt" x-model="examType" class="peer sr-only">
                                    <div class="p-5 rounded-[1.5rem] border-2 transition-all peer-checked:border-[#56bbf1] peer-checked:bg-[#e5eff5]/50 bg-white border-slate-200 hover:border-[#56bbf1]/50">
                                        <div class="flex items-center gap-4 relative z-10">
                                            <div class="w-12 h-12 rounded-xl bg-[#56bbf1]/20 text-[#0d52a1] flex items-center justify-center text-2xl shrink-0 transition-transform peer-checked:scale-110">
                                                <i class="ph-fill ph-desktop"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-[#2c3f61] text-sm mb-0.5">CBT Internal</h4>
                                                <p class="text-xs text-slate-500 font-medium">Buat soal di sistem ini atau ambil dari Bank Soal.</p>
                                            </div>
                                        </div>
                                        <div class="absolute top-5 right-5 w-5 h-5 rounded-full border-2 border-slate-200 flex items-center justify-center peer-checked:border-[#0d52a1] peer-checked:bg-[#0d52a1] text-transparent peer-checked:text-white transition-all">
                                            <i class="ph-bold ph-check text-xs"></i>
                                        </div>
                                    </div>
                                </label>

                                <!-- Opsi 2: Google Form -->
                                <label class="relative cursor-pointer group">
                                    <input type="radio" name="exam_type" value="google_form" x-model="examType" class="peer sr-only">
                                    <div class="p-5 rounded-[1.5rem] border-2 transition-all peer-checked:border-emerald-500 peer-checked:bg-emerald-50/50 bg-white border-slate-200 hover:border-emerald-200">
                                        <div class="flex items-center gap-4 relative z-10">
                                            <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl shrink-0 transition-transform peer-checked:scale-110">
                                                <i class="ph-fill ph-google-logo"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-bold text-[#2c3f61] text-sm mb-0.5">Google Form</h4>
                                                <p class="text-xs text-slate-500 font-medium">Sematkan ujian menggunakan tautan Google Formulir.</p>
                                            </div>
                                        </div>
                                        <div class="absolute top-5 right-5 w-5 h-5 rounded-full border-2 border-slate-200 flex items-center justify-center peer-checked:border-emerald-500 peer-checked:bg-emerald-500 text-transparent peer-checked:text-white transition-all">
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
                             class="p-6 bg-gradient-to-br from-emerald-50 to-teal-50 rounded-[1.5rem] border border-emerald-100/60 shadow-inner">
                            <label class="block text-xs font-bold text-emerald-700 uppercase tracking-wider mb-2 ml-1">Tautan / Link Google Form <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-emerald-500"><i class="ph-bold ph-link"></i></div>
                                <input type="url" name="google_form_url" value="{{ old('google_form_url') }}" :required="examType === 'google_form'" 
                                       class="w-full pl-11 rounded-2xl border-emerald-200 bg-white focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 font-bold text-[#2c3f61] py-3.5 px-4 transition-all placeholder:text-slate-400 shadow-sm" 
                                       placeholder="Contoh: https://forms.gle/xyz123...">
                            </div>
                            <div class="flex gap-2 mt-3 p-3 bg-white/60 rounded-xl border border-emerald-100">
                                <i class="ph-fill ph-info text-emerald-500 text-lg shrink-0"></i>
                                <p class="text-[11px] text-emerald-700 font-medium leading-snug">Pastikan pengaturan privasi Google Form Anda sudah disetting menjadi <strong>"Publik"</strong> atau dapat diakses oleh siswa agar form dapat dimuat di dalam sistem.</p>
                            </div>
                            @error('google_form_url') <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <hr class="border-slate-100">
                        <!-- [BARU] Pilih Kegiatan / Folder Event -->
                        <div class="mb-6">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Kategori Kegiatan / Folder <span class="text-rose-500">*</span></label>
                            <div class="relative flex items-center">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-[#56bbf1]">
                                    <i class="ph-fill ph-folder-open text-xl"></i>
                                </div>
                                <select name="cbt_event_id" required class="w-full pl-12 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-[#56bbf1] focus:ring-[#56bbf1] font-bold text-[#2c3f61] py-3.5 px-5 appearance-none cursor-pointer transition-all @error('cbt_event_id') border-rose-500 @enderror">
                                    <option value="" disabled {{ !old('cbt_event_id') && !isset($selectedEventId) ? 'selected' : '' }}>-- Pilih Folder Kegiatan --</option>
                                    @foreach($events as $evt)
                                        <option value="{{ $evt->id }}" {{ (old('cbt_event_id') ?? ($selectedEventId ?? '')) == $evt->id ? 'selected' : '' }}>
                                            {{ $evt->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-500">
                                    <i class="ph-bold ph-caret-down"></i>
                                </div>
                            </div>
                            <p class="text-[10px] text-slate-400 mt-1.5 ml-1 font-medium">Ujian ini akan dimasukkan ke dalam folder kegiatan yang dipilih.</p>
                            @error('cbt_event_id') <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <!-- Judul Ujian -->
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Nama / Judul Ujian <span class="text-rose-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}" required 
                                   class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-[#56bbf1] focus:ring-[#56bbf1] font-bold text-[#2c3f61] py-3.5 px-5 transition-all placeholder:font-normal placeholder:text-slate-400 @error('title') border-rose-500 bg-rose-50 @enderror" 
                                   placeholder="Contoh: Penilaian Tengah Semester (PTS) Matematika">
                            @error('title') <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Mapel -->
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Mata Pelajaran <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-book-bookmark"></i></div>
                                    <select name="subject_name" required class="w-full pl-11 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-[#56bbf1] focus:ring-[#56bbf1] font-bold text-[#2c3f61] py-3.5 px-5 appearance-none cursor-pointer transition-all @error('subject_name') border-rose-500 @enderror">
                                        <option value="" disabled selected>-- Pilih Mapel --</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->name }}" {{ old('subject_name') == $subject->name ? 'selected' : '' }}>
                                                {{ $subject->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                                </div>
                                @error('subject_name') <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                            
                            <!-- Kelas -->
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tingkat Kelas <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <select name="class_level" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-[#56bbf1] focus:ring-[#56bbf1] font-bold text-[#2c3f61] py-3.5 px-5 appearance-none cursor-pointer transition-all">
                                        <option value="7" {{ old('class_level') == '7' ? 'selected' : '' }}>Kelas 7</option>
                                        <option value="8" {{ old('class_level') == '8' ? 'selected' : '' }}>Kelas 8</option>
                                        <option value="9" {{ old('class_level') == '9' ? 'selected' : '' }}>Kelas 9</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Waktu Mulai -->
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Waktu Mulai <span class="text-rose-500">*</span></label>
                                <input type="datetime-local" name="start_time" x-model="startTime" value="{{ old('start_time') }}" required 
                                       class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-[#56bbf1] focus:ring-[#56bbf1] font-bold text-[#2c3f61] py-3.5 px-5 transition-all @error('start_time') border-rose-500 @enderror">
                                @error('start_time') <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                            
                            <!-- Waktu Selesai -->
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Waktu Selesai <span class="text-rose-500">*</span></label>
                                <input type="datetime-local" name="end_time" x-model="endTime" value="{{ old('end_time') }}" required 
                                       class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-[#56bbf1] focus:ring-[#56bbf1] font-bold text-[#2c3f61] py-3.5 px-5 transition-all @error('end_time') border-rose-500 @enderror"
                                       :min="startTime">
                                @error('end_time') <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                                
                                <p x-show="startTime && endTime && endTime < startTime" class="text-[10px] text-rose-500 font-bold mt-1 flex items-center gap-1 animate-pulse">
                                    <i class="ph-bold ph-warning"></i> Waktu selesai tidak boleh sebelum waktu mulai!
                                </p>
                            </div>
                        </div>

                        <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                            <!-- Durasi -->
                            <div class="col-span-1">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Durasi (Menit)</label>
                                <div class="relative">
                                    <input type="number" name="duration_minutes" value="{{ old('duration_minutes', 90) }}" required class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-[#56bbf1] focus:ring-[#56bbf1] font-bold text-[#2c3f61] py-3.5 px-5 transition-all text-center">
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400 text-[10px] font-bold">MIN</div>
                                </div>
                            </div>

                            <!-- Jumlah Soal Ditampilkan -->
                            <div class="col-span-1" x-show="examType === 'cbt'">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tampilkan Soal</label>
                                <div class="relative">
                                    <input type="number" name="question_limit" value="{{ old('question_limit', 0) }}" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-[#56bbf1] focus:ring-[#56bbf1] font-bold text-[#2c3f61] py-3.5 px-5 transition-all text-center" placeholder="0 = Semua">
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400 text-[10px] font-bold">SOAL</div>
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1 ml-1 font-medium">*Isi 0 untuk menampilkan semua soal di bank</p>
                            </div>
                            
                            <!-- KKM -->
                            <div class="col-span-1" x-show="examType === 'cbt'">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">KKM / Kriteria</label>
                                <input type="number" name="passing_grade" value="{{ old('passing_grade', 75) }}" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-[#56bbf1] focus:ring-[#56bbf1] font-bold text-[#2c3f61] py-3.5 px-5 transition-all text-center">
                            </div>

                             {{-- FITUR ANTI KECURANGAN --}}
                                <div class="col-span-2 md:col-span-3 mt-4" x-show="examType === 'cbt'">
                                    <div class="bg-rose-50/50 rounded-2xl p-5 border border-rose-100">
                                        <h4 class="text-sm font-black text-rose-900 flex items-center gap-2 mb-4">
                                            <i class="ph-fill ph-shield-warning text-rose-500 text-lg"></i> Keamanan & Anti-Kecurangan Lanjutan
                                        </h4>
                                        
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <!-- Acak Soal -->
                                            <label class="flex items-start gap-4 p-4 bg-white rounded-[1.5rem] border border-rose-100 cursor-pointer hover:border-rose-300 transition-colors group">
                                                <div class="relative flex items-center mt-1">
                                                    <input type="checkbox" name="randomize_questions" value="1" {{ old('randomize_questions') ? 'checked' : '' }} class="peer sr-only">
                                                    <div class="w-10 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-500"></div>
                                                </div>
                                                <div>
                                                    <p class="font-bold text-[#2c3f61] text-sm group-hover:text-rose-600 transition-colors">Acak Urutan Soal</p>
                                                    <p class="text-xs text-slate-500 font-medium leading-relaxed mt-0.5">Setiap siswa akan mendapatkan urutan nomor soal yang berbeda.</p>
                                                </div>
                                            </label>

                                            <!-- Acak Opsi -->
                                            <label class="flex items-start gap-4 p-4 bg-white rounded-[1.5rem] border border-rose-100 cursor-pointer hover:border-rose-300 transition-colors group">
                                                <div class="relative flex items-center mt-1">
                                                    <input type="checkbox" name="randomize_options" value="1" {{ old('randomize_options') ? 'checked' : '' }} class="peer sr-only">
                                                    <div class="w-10 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-rose-500"></div>
                                                </div>
                                                <div>
                                                    <p class="font-bold text-[#2c3f61] text-sm group-hover:text-rose-600 transition-colors">Acak Opsi Jawaban (A, B, C, D)</p>
                                                    <p class="text-xs text-slate-500 font-medium leading-relaxed mt-0.5">Posisi opsi akan diacak (Hanya berlaku untuk Pilihan Ganda).</p>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                             <!-- Token -->
                             <div class="col-span-2 md:col-span-1">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Token (Opsional)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-key"></i></div>
                                    <input type="text" name="token" value="{{ old('token') }}" maxlength="6" class="w-full pl-11 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-[#56bbf1] focus:ring-[#56bbf1] font-mono font-black text-[#2c3f61] py-3.5 px-5 transition-all uppercase tracking-widest placeholder:tracking-normal @error('token') border-rose-500 @enderror" placeholder="AUTO">
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1 ml-1 font-medium">*Kosongkan untuk generate otomatis</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-4 bg-[#e5eff5]/50 rounded-2xl border border-[#56bbf1]/30">
                            <div class="relative flex items-center">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', 1) ? 'checked' : '' }} id="active" class="peer sr-only">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500 cursor-pointer"></div>
                            </div>
                            <label for="active" class="text-sm font-bold text-[#2c3f61] cursor-pointer select-none">Aktifkan ujian segera setelah disimpan</label>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex flex-col-reverse md:flex-row justify-end gap-3">
                            <a href="{{ route('cbt.index') }}" class="w-full md:w-auto text-center px-6 py-3.5 bg-white border border-slate-200 rounded-xl text-slate-600 font-bold hover:bg-slate-50 transition text-sm shadow-sm">Batal</a>
                            <button type="submit" class="w-full md:w-auto px-8 py-3.5 bg-[#2c3f61] text-white rounded-xl font-bold hover:bg-[#1c2940] transition shadow-lg shadow-[#2c3f61]/30 text-sm flex items-center justify-center gap-2 transform active:scale-95 border border-transparent">
                                <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan Jadwal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>