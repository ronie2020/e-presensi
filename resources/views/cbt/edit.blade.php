<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Edit Jadwal Ujian') }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-10 font-sans text-slate-800">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO SECTION (PURPLE THEME FOR EDIT MODE) --}}
            <div class="relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-slate-900 via-purple-900 to-purple-800 p-8 mb-8 text-white shadow-xl shadow-purple-900/30 overflow-hidden border border-white/10">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div class="flex items-center gap-2 text-purple-300 text-sm font-bold mb-2">
                            <a href="{{ route('cbt.index') }}" class="hover:text-white transition flex items-center gap-1">
                                <i class="ph-bold ph-arrow-left"></i> Kembali
                            </a>
                            <span class="opacity-50">/</span>
                            <span>Edit Jadwal</span>
                        </div>
                        <h1 class="text-3xl font-black tracking-tight leading-none text-white mb-2">Edit Data Ujian</h1>
                        <p class="text-purple-200 text-sm font-medium">Perbarui detail pelaksanaan, durasi, atau token ujian.</p>
                    </div>
                </div>
            </div>

            {{-- ERROR SUMMARY --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-start gap-3">
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
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center text-xl shadow-sm">
                        <i class="ph-bold ph-pencil-simple-line"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg">Formulir Perubahan Data</h3>
                </div>

                <div class="p-8">
                    {{-- Init Alpine dengan format tanggal yang benar untuk input datetime-local --}}
                    <form action="{{ route('cbt.update', $exam->id) }}" method="POST" class="space-y-6" 
                          x-data="{ 
                              startTime: '{{ \Carbon\Carbon::parse($exam->start_time)->format('Y-m-d\TH:i') }}', 
                              endTime: '{{ \Carbon\Carbon::parse($exam->end_time)->format('Y-m-d\TH:i') }}' 
                          }">
                        @csrf
                        @method('PUT') {{-- PENTING: Method PUT untuk update --}}
                        
                        <!-- Judul Ujian -->
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Nama / Judul Ujian <span class="text-rose-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $exam->title) }}" required 
                                   class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-purple-500 focus:ring-purple-500 font-bold text-slate-700 py-3.5 px-5 transition-all placeholder:font-normal placeholder:text-slate-400 @error('title') border-rose-500 bg-rose-50 @enderror" 
                                   placeholder="Contoh: Penilaian Tengah Semester (PTS) Matematika">
                            @error('title') <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Mapel -->
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Mata Pelajaran <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-book-bookmark"></i></div>
                                    <input type="text" name="subject_name" value="{{ old('subject_name', $exam->subject_name) }}" required 
                                           class="w-full pl-11 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-purple-500 focus:ring-purple-500 font-bold text-slate-700 py-3.5 px-5 transition-all @error('subject_name') border-rose-500 @enderror" placeholder="Nama Mapel">
                                </div>
                                @error('subject_name') <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                            
                            <!-- Kelas -->
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tingkat Kelas <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <select name="class_level" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-purple-500 focus:ring-purple-500 font-bold text-slate-700 py-3.5 px-5 appearance-none cursor-pointer transition-all">
                                        <option value="7" {{ old('class_level', $exam->class_level) == '7' ? 'selected' : '' }}>Kelas 7</option>
                                        <option value="8" {{ old('class_level', $exam->class_level) == '8' ? 'selected' : '' }}>Kelas 8</option>
                                        <option value="9" {{ old('class_level', $exam->class_level) == '9' ? 'selected' : '' }}>Kelas 9</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Waktu Mulai -->
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Waktu Mulai <span class="text-rose-500">*</span></label>
                                <input type="datetime-local" name="start_time" x-model="startTime" required 
                                       class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-purple-500 focus:ring-purple-500 font-bold text-slate-700 py-3.5 px-5 transition-all @error('start_time') border-rose-500 @enderror">
                                @error('start_time') <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                            
                            <!-- Waktu Selesai -->
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Waktu Selesai <span class="text-rose-500">*</span></label>
                                <input type="datetime-local" name="end_time" x-model="endTime" required 
                                       class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-purple-500 focus:ring-purple-500 font-bold text-slate-700 py-3.5 px-5 transition-all @error('end_time') border-rose-500 @enderror"
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
                                    <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $exam->duration_minutes) }}" required class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-purple-500 focus:ring-purple-500 font-bold text-slate-700 py-3.5 px-5 transition-all text-center">
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400 text-[10px] font-bold">MIN</div>
                                </div>
                            </div>
                            
                            <!-- KKM -->
                            <div class="col-span-1">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">KKM / Kriteria</label>
                                <input type="number" name="passing_grade" value="{{ old('passing_grade', $exam->passing_grade) }}" required class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-purple-500 focus:ring-purple-500 font-bold text-slate-700 py-3.5 px-5 transition-all text-center">
                            </div>

                             <!-- Token -->
                             <div class="col-span-2 md:col-span-1">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Token (Opsional)</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-key"></i></div>
                                    <input type="text" name="token" value="{{ old('token', $exam->token) }}" maxlength="6" class="w-full pl-11 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-purple-500 focus:ring-purple-500 font-mono font-black text-slate-800 py-3.5 px-5 transition-all uppercase tracking-widest placeholder:tracking-normal @error('token') border-rose-500 @enderror" placeholder="KOSONG = TETAP">
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1 ml-1 font-medium">*Kosongkan jika tidak ingin mengubah token</p>
                                @error('token') <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-4 bg-purple-50 rounded-2xl border border-purple-100">
                            <div class="relative flex items-center">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $exam->is_active) ? 'checked' : '' }} id="active" class="peer sr-only">
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600 cursor-pointer"></div>
                            </div>
                            <label for="active" class="text-sm font-bold text-slate-700 cursor-pointer select-none">Status Ujian Aktif</label>
                        </div>

                        <div class="pt-4 border-t border-slate-100 flex flex-col-reverse md:flex-row justify-end gap-3">
                            <a href="{{ route('cbt.index') }}" class="w-full md:w-auto text-center px-6 py-3.5 bg-white border border-slate-200 rounded-xl text-slate-600 font-bold hover:bg-slate-50 transition text-sm">Batal</a>
                            <button type="submit" class="w-full md:w-auto px-8 py-3.5 bg-purple-600 text-white rounded-xl font-bold hover:bg-purple-700 transition shadow-lg shadow-purple-500/30 text-sm flex items-center justify-center gap-2 transform active:scale-95">
                                <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>