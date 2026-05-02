<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-elevate-dark leading-tight">
            {{ __('Buat Bank Soal Mapel') }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-10 font-sans text-elevate-dark">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO SECTION MICROSOFT ELEVATE THEME --}}
            <div class="relative rounded-[2rem] bg-gradient-to-r from-elevate-accent via-elevate-soft to-elevate-peach-light p-8 mb-8 text-elevate-dark shadow-xl shadow-elevate-accent/10 overflow-hidden border border-white/60">
                {{-- Abstract Shapes Ornaments --}}
                <div class="absolute -top-10 -left-10 w-48 h-48 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/20 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <a href="{{ route('bank.show', $folder_id) }}" class="text-xs font-bold text-elevate-primary hover:text-elevate-dark transition flex items-center gap-1 bg-white/60 px-3 py-1 rounded-full border border-white backdrop-blur-sm shadow-sm">
                                <i class="ph-bold ph-arrow-left"></i> Kembali
                            </a>
                            <span class="text-elevate-dark/30 text-xs">•</span>
                            <span class="text-[10px] font-bold text-elevate-dark/70 uppercase tracking-wider">Mapel Baru</span>
                        </div>
                        <h1 class="text-3xl font-extrabold tracking-tight leading-none text-elevate-dark mb-2">Buat Bank Soal</h1>
                        <p class="text-elevate-dark/80 text-sm font-medium">Memasukkan modul soal ke dalam folder: <strong>{{ $folder->name }}</strong></p>
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
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-elevate-accent/10 border border-slate-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 bg-elevate-soft/30 flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-elevate-accent/20 text-elevate-primary flex items-center justify-center text-xl shadow-sm">
                        <i class="ph-bold ph-folder-plus"></i>
                    </div>
                    <h3 class="font-bold text-elevate-dark text-lg">Detail Bank Soal</h3>
                </div>

                <div class="p-8">
                    <form action="{{ route('bank.store') }}" method="POST" class="space-y-8">
                        @csrf
                        {{-- Hidden Input untuk memastikan ID Folder terkirim --}}
                        <input type="hidden" name="folder_id" value="{{ $folder_id }}">

                        <!-- Judul Modul / Bank Soal -->
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Nama Topik / Modul Bank Soal <span class="text-rose-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title') }}" required 
                                   class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/50 font-bold text-elevate-dark py-3.5 px-5 transition-all placeholder:font-normal placeholder:text-slate-400 @error('title') border-rose-500 bg-rose-50 @enderror" 
                                   placeholder="Contoh: Ulangan Harian Bab 1 Algoritma">
                            @error('title') <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Mapel -->
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Mata Pelajaran <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-book-bookmark"></i></div>
                                    <select name="subject_name" required class="w-full pl-11 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/50 font-bold text-elevate-dark py-3.5 px-5 appearance-none cursor-pointer transition-all @error('subject_name') border-rose-500 @enderror">
                                        <option value="" disabled selected>-- Pilih Mapel --</option>
                                        @foreach($subjects as $subject)
                                            <option value="{{ $subject->name }}" {{ old('subject_name') == $subject->name ? 'selected' : '' }}>
                                                {{ $subject->name }}
                                            </option>
                                        @endforeach
                                        <option value="Lainnya">Mapel Lainnya (Ketik Manual Nanti)</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                                </div>
                                @error('subject_name') <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                            
                            <!-- Kelas -->
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tingkat Kelas <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <select name="class_level" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/50 font-bold text-elevate-dark py-3.5 px-5 appearance-none cursor-pointer transition-all">
                                        <option value="" disabled selected>-- Pilih Kelas --</option>
                                        <option value="7" {{ old('class_level') == '7' ? 'selected' : '' }}>Kelas 7</option>
                                        <option value="8" {{ old('class_level') == '8' ? 'selected' : '' }}>Kelas 8</option>
                                        <option value="9" {{ old('class_level') == '9' ? 'selected' : '' }}>Kelas 9</option>
                                        <option value="Umum" {{ old('class_level') == 'Umum' ? 'selected' : '' }}>Umum / Semua Kelas</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                                </div>
                                @error('class_level') <p class="text-xs text-rose-500 mt-1 font-bold">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Action Buttons (Gaya CBT) --}}
                        <div class="pt-4 border-t border-slate-100 flex flex-col-reverse md:flex-row justify-end gap-3">
                            <a href="{{ route('bank.show', $folder_id) }}" class="w-full md:w-auto text-center px-6 py-3.5 bg-white border border-slate-200 rounded-xl text-slate-600 font-bold hover:bg-slate-50 transition text-sm shadow-sm">
                                Batal
                            </a>
                            <button type="submit" class="w-full md:w-auto px-8 py-3.5 bg-elevate-dark text-white rounded-xl font-bold hover:bg-elevate-primary transition shadow-lg shadow-elevate-dark/30 text-sm flex items-center justify-center gap-2 transform active:scale-95 border border-transparent">
                                <i class="ph-bold ph-floppy-disk text-lg"></i> Buat Bank Soal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</x-app-layout>