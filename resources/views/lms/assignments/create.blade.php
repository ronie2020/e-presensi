<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-elevate-dark leading-tight">
            {{ __('Buat Tugas Baru') }}
        </h2>
    </x-slot>

    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>

    <div class="py-8 sm:py-10 font-sans text-elevate-dark bg-elevate-surface min-h-screen relative overflow-hidden pb-20">
        
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- HERO HEADER ELEVATE --}}
            <div class="relative rounded-[2rem] bg-gradient-to-r from-elevate-accent via-elevate-peach-light to-elevate-peach p-8 md:p-10 mb-8 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden border border-white/60 group">
                <div class="absolute -top-10 -left-10 w-56 h-56 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/40 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-black mb-2 tracking-tight">Setting Penugasan</h1>
                        <p class="text-elevate-dark/80 text-sm font-semibold">Atur detail tugas, kuis, atau instruksi untuk siswa.</p>
                    </div>
                    <a href="{{ route('lms.assignments.index') }}" class="w-full md:w-auto inline-flex justify-center items-center gap-2 px-6 py-3.5 bg-white/60 hover:bg-white rounded-xl text-sm font-bold backdrop-blur-md transition-colors text-elevate-dark border border-white/60 shadow-sm active:scale-95 btn-cancel-confirm shrink-0">
                        <i class="ph-bold ph-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            {{-- ERROR BLOCK --}}
            @if ($errors->any())
                <div class="mb-8 bg-[#FDE7E9] border border-[#F4C3C9] p-5 rounded-[1.5rem] flex items-start gap-4 shadow-sm animate-pulse">
                    <div class="w-10 h-10 bg-white text-[#D13438] border border-[#F4C3C9] rounded-xl shrink-0 shadow-sm flex items-center justify-center">
                        <i class="ph-bold ph-warning-octagon text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-black text-[#D13438] uppercase tracking-wider mb-1 mt-1">Gagal Menyimpan</h3>
                        <ul class="list-disc list-inside text-sm text-[#D13438] space-y-1 font-bold">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- FORM CARD --}}
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden">
                <form action="{{ route('lms.assignments.store') }}" method="POST" id="createAssignmentForm" 
                      x-data="{ 
                          targetType: 'class', 
                          assignmentType: 'file_upload', 
                          questions: [] 
                      }">
                    @csrf

                    <div class="p-6 md:p-10 space-y-10">
                        
                        <!-- 1. IDENTITAS TUGAS -->
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-12 h-12 rounded-xl bg-elevate-peach-light/40 text-elevate-peach-dark flex items-center justify-center text-2xl border border-elevate-peach/30 shadow-sm"><i class="ph-bold ph-info"></i></div>
                                <h3 class="text-xl font-black text-elevate-dark">Informasi Dasar</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="col-span-2">
                                    <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Judul Tugas <span class="text-[#D13438]">*</span></label>
                                    <input type="text" name="title" value="{{ old('title') }}" required class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-black text-elevate-dark focus:bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent h-14 px-5 placeholder:font-bold placeholder:text-slate-400 transition-colors shadow-sm" placeholder="Contoh: Ulangan Harian Bab 1">
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Mata Pelajaran <span class="text-[#D13438]">*</span></label>
                                    <div class="relative group">
                                        <select name="subject_id" required class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-bold text-elevate-dark focus:bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent h-14 px-5 appearance-none transition-colors cursor-pointer shadow-sm">
                                            <option value="">-- Pilih Mapel --</option>
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-elevate-primary"><i class="ph-bold ph-caret-down text-lg"></i></div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Deadline <span class="text-[#D13438]">*</span></label>
                                    <input type="datetime-local" name="deadline" value="{{ old('deadline') }}" required class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-bold text-elevate-dark focus:bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent h-14 px-5 transition-colors shadow-sm">
                                </div>

                                <div class="col-span-2">
                                    <label class="inline-flex items-center cursor-pointer group">
                                        <div class="relative">
                                            <input type="checkbox" name="allow_late_submission" class="sr-only peer" {{ old('allow_late_submission') ? 'checked' : '' }}>
                                            <div class="w-12 h-7 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-elevate-primary shadow-inner"></div>
                                        </div>
                                        <span class="ml-3 text-sm font-bold text-slate-500 group-hover:text-elevate-primary transition-colors">Izinkan pengumpulan terlambat</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="h-px bg-slate-100"></div>

                        <!-- 2. PILIHAN TIPE TUGAS -->
                        <div>
                            <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-4 ml-1">Jenis Penugasan <span class="text-[#D13438]">*</span></label>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                {{-- Card 1: Upload File --}}
                                <label class="cursor-pointer group relative">
                                    <input type="radio" name="assignment_type" value="file_upload" x-model="assignmentType" class="peer sr-only">
                                    <div class="p-6 rounded-2xl border-2 border-slate-100 bg-white hover:border-elevate-accent/50 hover:bg-elevate-soft/50 transition-all peer-checked:border-elevate-primary peer-checked:bg-elevate-soft/30 peer-checked:shadow-md flex flex-col items-center justify-center text-center h-full gap-4">
                                        <div class="w-14 h-14 rounded-2xl bg-slate-100 border border-slate-200 text-slate-500 flex items-center justify-center text-3xl peer-checked:bg-elevate-primary peer-checked:border-elevate-primary peer-checked:text-white transition-colors shadow-sm">
                                            <i class="ph-duotone ph-upload-simple"></i>
                                        </div>
                                        <div>
                                            <span class="block font-black text-elevate-dark peer-checked:text-elevate-primary text-base">Upload File/Foto</span>
                                            <span class="block text-xs text-slate-500 font-medium mt-1">Siswa mengunggah bukti/jawaban</span>
                                        </div>
                                        <div class="absolute top-4 right-4 text-elevate-primary opacity-0 peer-checked:opacity-100 transition-opacity"><i class="ph-fill ph-check-circle text-2xl"></i></div>
                                    </div>
                                </label>

                                {{-- Card 2: Kuis Online --}}
                                <label class="cursor-pointer group relative">
                                    <input type="radio" name="assignment_type" value="quiz" x-model="assignmentType" class="peer sr-only">
                                    <div class="p-6 rounded-2xl border-2 border-slate-100 bg-white hover:border-purple-300 hover:bg-purple-50/50 transition-all peer-checked:border-purple-500 peer-checked:bg-purple-50/50 peer-checked:shadow-md flex flex-col items-center justify-center text-center h-full gap-4">
                                        <div class="w-14 h-14 rounded-2xl bg-slate-100 border border-slate-200 text-slate-500 flex items-center justify-center text-3xl peer-checked:bg-purple-600 peer-checked:border-purple-600 peer-checked:text-white transition-colors shadow-sm">
                                            <i class="ph-duotone ph-brain"></i>
                                        </div>
                                        <div>
                                            <span class="block font-black text-elevate-dark peer-checked:text-purple-700 text-base">Kuis Online (CBT)</span>
                                            <span class="block text-xs text-slate-500 font-medium mt-1">Buat soal PG atau Essai</span>
                                        </div>
                                        <div class="absolute top-4 right-4 text-purple-600 opacity-0 peer-checked:opacity-100 transition-opacity"><i class="ph-fill ph-check-circle text-2xl"></i></div>
                                    </div>
                                </label>

                                {{-- Card 3: Link Eksternal --}}
                                <label class="cursor-pointer group relative">
                                    <input type="radio" name="assignment_type" value="link" x-model="assignmentType" class="peer sr-only">
                                    <div class="p-6 rounded-2xl border-2 border-slate-100 bg-white hover:border-[#FFD8A8] hover:bg-[#FFEFD6]/50 transition-all peer-checked:border-[#D83B01] peer-checked:bg-[#FFEFD6]/30 peer-checked:shadow-md flex flex-col items-center justify-center text-center h-full gap-4">
                                        <div class="w-14 h-14 rounded-2xl bg-slate-100 border border-slate-200 text-slate-500 flex items-center justify-center text-3xl peer-checked:bg-[#D83B01] peer-checked:border-[#D83B01] peer-checked:text-white transition-colors shadow-sm">
                                            <i class="ph-duotone ph-link"></i>
                                        </div>
                                        <div>
                                            <span class="block font-black text-elevate-dark peer-checked:text-[#D83B01] text-base">Link Eksternal</span>
                                            <span class="block text-xs text-slate-500 font-medium mt-1">GForm, Quizizz, YouTube, dll</span>
                                        </div>
                                        <div class="absolute top-4 right-4 text-[#D83B01] opacity-0 peer-checked:opacity-100 transition-opacity"><i class="ph-fill ph-check-circle text-2xl"></i></div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- 3. KONTEN DINAMIS -->
                        <div class="bg-elevate-soft/30 rounded-[2rem] p-6 md:p-8 border border-slate-100">
                            
                            <!-- A. JIKA UPLOAD FILE -->
                            <div x-show="assignmentType === 'file_upload'">
                                <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Instruksi / Soal</label>
                                <textarea name="description_file" rows="5" class="w-full rounded-2xl border-slate-200 bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent p-5 text-elevate-dark font-medium placeholder:font-normal placeholder:text-slate-400 transition-colors shadow-sm" placeholder="Tuliskan soal atau instruksi pengerjaan disini...">{{ old('description_file') }}</textarea>
                            </div>

                            <!-- B. JIKA LINK EKSTERNAL -->
                            <div x-show="assignmentType === 'link'" style="display: none;">
                                <div class="mb-5">
                                    <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">URL Link Tugas <span class="text-[#D13438]">*</span></label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-elevate-primary"><i class="ph-bold ph-link text-lg"></i></div>
                                        <input type="url" name="link_url" value="{{ old('link_url') }}" class="w-full rounded-2xl border-slate-200 bg-white pl-12 font-bold text-elevate-primary focus:ring-elevate-accent/30 focus:border-elevate-accent h-14 transition-colors shadow-sm" placeholder="https://...">
                                    </div>
                                </div>
                                <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Instruksi Tambahan</label>
                                <textarea name="description_link" rows="4" class="w-full rounded-2xl border-slate-200 bg-white focus:ring-[#D83B01]/30 focus:border-[#D83B01] p-5 font-medium transition-colors shadow-sm" placeholder="Silakan kerjakan link di atas...">{{ old('description_link') }}</textarea>
                            </div>

                            <!-- C. JIKA KUIS ONLINE -->
                            <div x-show="assignmentType === 'quiz'" style="display: none;">
                                <div class="mb-8 flex flex-col md:flex-row gap-5">
                                    <div class="flex-1">
                                        <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Instruksi Kuis</label>
                                        <textarea name="description_quiz" rows="2" class="w-full rounded-2xl border-slate-200 bg-white focus:ring-purple-500/30 focus:border-purple-500 p-4 transition-colors shadow-sm" placeholder="Kerjakan dengan jujur...">{{ old('description_quiz') }}</textarea>
                                    </div>
                                    <div class="w-full md:w-1/3">
                                        <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Durasi (Menit) <span class="text-[#D13438]">*</span></label>
                                        <div class="relative group">
                                            <input type="number" name="duration_minutes" value="{{ old('duration_minutes', 60) }}" class="w-full rounded-2xl border-slate-200 bg-white font-black text-elevate-dark focus:ring-purple-500/30 focus:border-purple-500 h-14 pl-5 pr-12 transition-colors shadow-sm">
                                            <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-400 text-[10px] font-black tracking-widest">MIN</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-5 mb-6">
                                    <template x-for="(q, index) in questions" :key="index">
                                        <div class="bg-white p-6 rounded-[2rem] border border-slate-200 shadow-sm relative group hover:border-purple-300 transition-all hover:shadow-md">
                                            <button type="button" @click="questions = questions.filter((_, i) => i !== index)" class="absolute top-5 right-5 w-10 h-10 rounded-xl bg-[#FDE7E9] text-[#D13438] border border-[#F4C3C9] hover:bg-[#D13438] hover:text-white flex items-center justify-center transition-colors shadow-sm">
                                                <i class="ph-bold ph-trash text-lg"></i>
                                            </button>
                                            
                                            <div class="flex flex-col sm:flex-row gap-5">
                                                <span class="bg-purple-100 text-purple-700 border border-purple-200 w-12 h-12 flex items-center justify-center rounded-2xl font-black text-lg shrink-0 shadow-sm" x-text="index + 1"></span>
                                                <div class="flex-1 w-full pr-12 sm:pr-0">
                                                    <div class="flex flex-col sm:flex-row gap-4 mb-4">
                                                        <select :name="'questions['+index+'][type]'" x-model="q.type" class="text-sm font-bold text-elevate-dark rounded-xl border-slate-200 bg-elevate-soft h-12 focus:ring-purple-500 focus:bg-white px-4 transition-colors">
                                                            <option value="multiple_choice">Pilihan Ganda</option>
                                                            <option value="essay">Essai / Jawaban Panjang</option>
                                                        </select>
                                                        <input type="number" :name="'questions['+index+'][points]'" x-model="q.points" class="text-sm font-bold text-elevate-dark rounded-xl border-slate-200 bg-elevate-soft focus:bg-white w-full sm:w-32 h-12 px-4 focus:ring-purple-500 transition-colors" placeholder="Poin">
                                                    </div>
                                                    
                                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 ml-1">Pertanyaan / Soal</label>
                                                    <textarea :name="'questions['+index+'][text]'" x-model="q.text" rows="3" class="w-full rounded-2xl border-slate-200 text-base mb-5 focus:ring-purple-500/30 focus:border-purple-500 font-medium shadow-sm p-4 bg-slate-50 focus:bg-white transition-colors" placeholder="Tuliskan pertanyaan..."></textarea>
                                                    
                                                    <!-- UI UNTUK PILIHAN GANDA -->
                                                    <div x-show="q.type === 'multiple_choice'" class="space-y-3">
                                                        <template x-for="opt in ['A', 'B', 'C', 'D', 'E']">
                                                            <div class="flex items-center gap-3">
                                                                <input type="radio" :name="'questions['+index+'][correct]'" :value="opt" class="w-5 h-5 text-[#107C10] focus:ring-[#107C10] border-slate-300 cursor-pointer">
                                                                <div class="flex-1 relative group/opt">
                                                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 text-sm font-black" x-text="opt"></div>
                                                                    <input type="text" :name="'questions['+index+'][options]['+opt+']'" class="w-full rounded-xl border-slate-200 bg-elevate-soft text-sm py-3.5 pl-10 focus:bg-white focus:ring-purple-500/30 focus:border-purple-500 transition-colors font-semibold text-elevate-dark" :placeholder="'Pilihan Jawaban ' + opt">
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </div>

                                                    <!-- UI UNTUK ESSAY -->
                                                    <div x-show="q.type === 'essay'" class="mt-5">
                                                        <div class="bg-[#FFEFD6] border border-[#FFD8A8] rounded-2xl p-5 shadow-sm">
                                                            <div class="flex items-start gap-4">
                                                                <div class="p-2.5 bg-white text-[#D83B01] border border-[#FFD8A8] rounded-xl shrink-0 shadow-sm">
                                                                    <i class="ph-bold ph-pencil-simple-line text-xl"></i>
                                                                </div>
                                                                <div class="w-full">
                                                                    <h4 class="font-black text-[#D83B01] text-sm mb-1">Referensi Jawaban (Opsional)</h4>
                                                                    <p class="text-xs text-[#D83B01]/80 mb-3 font-semibold">Anda dapat memasukkan poin-poin penting dari jawaban yang diharapkan. (Hanya terlihat oleh Guru)</p>
                                                                    <textarea 
                                                                        :name="'questions['+index+'][answer_key]'" 
                                                                        rows="3" 
                                                                        class="w-full rounded-xl border-[#FFD8A8] bg-white text-sm focus:ring-[#D83B01] focus:border-[#D83B01] text-[#2A3B52] p-4 transition-colors shadow-sm"
                                                                        placeholder="Contoh: Jawaban harus mencakup definisi fotosintesis dan menyebutkan peran klorofil..."></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <button type="button" @click="questions.push({type: 'multiple_choice', text: '', points: 10})" 
                                        class="w-full py-4 border-2 border-dashed border-purple-300 bg-purple-50/50 text-purple-600 rounded-[1.5rem] font-bold text-sm hover:bg-purple-100 hover:border-purple-400 transition-all flex items-center justify-center gap-2 active:scale-95">
                                    <i class="ph-bold ph-plus text-lg"></i> Tambah Pertanyaan
                                </button>
                            </div>
                        </div>

                        <!-- 4. TARGET PENERIMA -->
                        <div class="bg-elevate-soft/50 p-6 md:p-8 rounded-[2rem] border border-slate-100">
                            <label class="block text-xs font-black text-elevate-primary uppercase tracking-widest mb-4 flex items-center gap-2">
                                <i class="ph-fill ph-users-three text-lg"></i> Target Penerima <span class="text-[#D13438]">*</span>
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <label class="flex-1 inline-flex items-center cursor-pointer group bg-white px-4 py-3.5 border border-slate-200 rounded-xl hover:border-elevate-accent shadow-sm transition-all">
                                        <div class="relative flex items-center">
                                            <input type="radio" name="target_type" value="class" x-model="targetType" class="peer sr-only">
                                            <div class="w-5 h-5 border-2 border-slate-300 rounded-full peer-checked:border-elevate-primary peer-checked:bg-elevate-primary transition-colors"></div>
                                        </div>
                                        <span class="ml-3 text-sm font-black text-elevate-dark group-hover:text-elevate-primary transition-colors">Satu Kelas</span>
                                    </label>
                                    <label class="flex-1 inline-flex items-center cursor-pointer group bg-white px-4 py-3.5 border border-slate-200 rounded-xl hover:border-elevate-accent shadow-sm transition-all">
                                        <div class="relative flex items-center">
                                            <input type="radio" name="target_type" value="grade" x-model="targetType" class="peer sr-only">
                                            <div class="w-5 h-5 border-2 border-slate-300 rounded-full peer-checked:border-elevate-primary peer-checked:bg-elevate-primary transition-colors"></div>
                                        </div>
                                        <span class="ml-3 text-sm font-black text-elevate-dark group-hover:text-elevate-primary transition-colors">Satu Jenjang</span>
                                    </label>
                                </div>

                                <div>
                                    <div x-show="targetType === 'class'">
                                        <div class="relative group">
                                            <select name="class_id" 
                                                    :required="targetType === 'class'"
                                                    class="w-full text-sm font-bold rounded-xl border-slate-200 bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent h-14 px-5 appearance-none shadow-sm cursor-pointer text-elevate-dark transition-colors">
                                                <option value="">-- Pilih Kelas --</option>
                                                @foreach($classes as $class)
                                                    <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-elevate-primary"><i class="ph-bold ph-caret-down text-lg"></i></div>
                                        </div>
                                    </div>
                                    <div x-show="targetType === 'grade'" style="display: none;">
                                        <div class="relative group">
                                            <select name="target_grade" 
                                                    :required="targetType === 'grade'"
                                                    class="w-full text-sm font-bold rounded-xl border-slate-200 bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent h-14 px-5 appearance-none shadow-sm cursor-pointer text-elevate-dark transition-colors">
                                                <option value="7" {{ old('target_grade') == '7' ? 'selected' : '' }}>Kelas 7</option>
                                                <option value="8" {{ old('target_grade') == '8' ? 'selected' : '' }}>Kelas 8</option>
                                                <option value="9" {{ old('target_grade') == '9' ? 'selected' : '' }}>Kelas 9</option>
                                            </select>
                                            <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-elevate-primary"><i class="ph-bold ph-caret-down text-lg"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FOOTER ACTIONS -->
                    <div class="bg-elevate-soft/30 px-6 py-6 md:px-10 md:py-8 flex flex-col sm:flex-row justify-end gap-4 border-t border-slate-100">
                        <a href="{{ route('lms.assignments.index') }}" class="w-full sm:w-auto px-8 py-4 bg-white border-2 border-slate-200 text-elevate-dark font-bold rounded-2xl hover:bg-elevate-soft transition-colors text-center text-sm shadow-sm btn-cancel-confirm active:scale-95">Batal</a>
                        
                        <button type="submit" class="w-full sm:w-auto px-8 py-4 bg-elevate-dark text-white font-bold rounded-2xl shadow-lg shadow-elevate-dark/30 hover:bg-elevate-primary transition-all flex items-center justify-center gap-2 text-sm border border-transparent active:scale-95">
                            <i class="ph-bold ph-paper-plane-tilt text-lg"></i>
                            <span>Terbitkan Tugas</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- SCRIPT SWEETALERT2 --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // 1. Proteksi Tombol Batal/Kembali
            const cancelButtons = document.querySelectorAll('.btn-cancel-confirm');
            cancelButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const href = this.getAttribute('href');

                    Swal.fire({
                        title: 'Batalkan Tugas?',
                        text: "Data yang sudah diisi akan hilang jika Anda keluar.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#2c3f61', 
                        cancelButtonColor: '#e5eff5', 
                        confirmButtonText: 'Ya, Batalkan',
                        cancelButtonText: '<span class="text-elevate-dark">Lanjut Mengisi</span>',
                        customClass: {
                            popup: 'rounded-[2rem] font-sans border-0 shadow-2xl',
                            confirmButton: 'rounded-xl px-5 py-2.5 font-bold shadow-sm',
                            cancelButton: 'rounded-xl px-5 py-2.5 font-bold shadow-sm'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = href;
                        }
                    });
                });
            });

            // 2. Loading saat Submit
            const form = document.getElementById('createAssignmentForm');
            if(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (!this.checkValidity()) {
                        this.reportValidity();
                        return;
                    }

                    Swal.fire({
                        title: 'Sedang Menerbitkan...',
                        text: 'Mohon tunggu sebentar.',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                        customClass: {
                            popup: 'rounded-[2rem] font-sans border-0 shadow-2xl',
                            title: 'text-xl font-black text-elevate-dark'
                        }
                    });

                    setTimeout(() => {
                        this.submit();
                    }, 500);
                });
            }
        });
    </script>
    @endpush
</x-app-layout>