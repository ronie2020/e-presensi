<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Tugas / Kuis Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-2xl border border-gray-100 overflow-hidden">
                
                <form action="{{ route('lms.assignments.store') }}" method="POST" 
                      x-data="{ 
                          targetType: 'class', 
                          assignmentType: 'file_upload', 
                          questions: [] 
                      }">
                    @csrf

                    <!-- HEADER -->
                    <div class="bg-gray-50 px-8 py-6 border-b border-gray-100 flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Setting Penugasan</h2>
                            <p class="text-sm text-gray-500">Pilih jenis tugas yang akan diberikan kepada siswa.</p>
                        </div>
                        <a href="{{ route('lms.assignments.index') }}" class="text-gray-500 hover:text-gray-700 font-medium text-sm flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Kembali
                        </a>
                    </div>

                    <div class="p-8 space-y-8">
                        
                        <!-- 1. IDENTITAS TUGAS -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Judul -->
                            <div class="col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Judul Tugas <span class="text-red-500">*</span></label>
                                <input type="text" name="title" required class="w-full rounded-xl border-gray-300 focus:ring-blue-500" placeholder="Contoh: Ulangan Harian Bab 1">
                            </div>

                            <!-- Mapel & Deadline -->
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Mata Pelajaran <span class="text-red-500">*</span></label>
                                <select name="subject_id" required class="w-full rounded-xl border-gray-300 focus:ring-blue-500">
                                    <option value="">-- Pilih Mapel --</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Batas Waktu (Deadline) <span class="text-red-500">*</span></label>
                                <input type="datetime-local" name="deadline" required class="w-full rounded-xl border-gray-300 focus:ring-blue-500">
                            </div>

                            <!-- Opsi Tambahan -->
                            <div class="col-span-2 flex gap-6">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="allow_late_submission" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-600">Izinkan pengumpulan terlambat</span>
                                </label>
                            </div>
                        </div>

                        <hr class="border-gray-100">

                        <!-- 2. PEMILIHAN TIPE TUGAS -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-4">Jenis Penugasan <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Tipe: Upload File -->
                                <label class="cursor-pointer relative">
                                    <input type="radio" name="assignment_type" value="file_upload" x-model="assignmentType" class="peer sr-only">
                                    <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50 transition text-center h-full flex flex-col items-center justify-center gap-2">
                                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm text-blue-600 mb-2"><i class="ph-duotone ph-upload-simple text-2xl"></i></div>
                                        <span class="font-bold text-gray-700">Upload File/Foto</span>
                                        <span class="text-xs text-gray-500">Siswa mengunggah jawaban (PDF/JPG)</span>
                                    </div>
                                </label>

                                <!-- Tipe: Kuis Online -->
                                <label class="cursor-pointer relative">
                                    <input type="radio" name="assignment_type" value="quiz" x-model="assignmentType" class="peer sr-only">
                                    <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50 transition text-center h-full flex flex-col items-center justify-center gap-2">
                                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm text-purple-600 mb-2"><i class="ph-duotone ph-list-checks text-2xl"></i></div>
                                        <span class="font-bold text-gray-700">Kuis Online (CBT)</span>
                                        <span class="text-xs text-gray-500">Buat soal PG/Essai langsung disini</span>
                                    </div>
                                </label>

                                <!-- Tipe: Link Eksternal -->
                                <label class="cursor-pointer relative">
                                    <input type="radio" name="assignment_type" value="link" x-model="assignmentType" class="peer sr-only">
                                    <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50 transition text-center h-full flex flex-col items-center justify-center gap-2">
                                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-sm text-orange-600 mb-2"><i class="ph-duotone ph-link text-2xl"></i></div>
                                        <span class="font-bold text-gray-700">Link Eksternal</span>
                                        <span class="text-xs text-gray-500">Quizizz, GForm, Wordwall, dll</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- 3. KONTEN DINAMIS BERDASARKAN TIPE -->
                        <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200">
                            
                            <!-- A. JIKA UPLOAD FILE (DEFAULT) -->
                            <div x-show="assignmentType === 'file_upload'">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Instruksi / Soal</label>
                                <textarea name="description" rows="5" class="w-full rounded-xl border-gray-300 focus:ring-blue-500" placeholder="Tuliskan soal atau instruksi pengerjaan disini..."></textarea>
                            </div>

                            <!-- B. JIKA LINK EKSTERNAL -->
                            <div x-show="assignmentType === 'link'" style="display: none;">
                                <div class="mb-4">
                                    <label class="block text-sm font-bold text-gray-700 mb-2">URL Link Tugas <span class="text-red-500">*</span></label>
                                    <input type="url" name="link_url" class="w-full rounded-xl border-gray-300 focus:ring-blue-500" placeholder="https://quizizz.com/join?gc=...">
                                </div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Instruksi Tambahan</label>
                                <textarea name="description" rows="3" class="w-full rounded-xl border-gray-300 focus:ring-blue-500" placeholder="Contoh: Silakan kerjakan kuis di link tersebut, lalu klik tombol 'Tandai Selesai' di aplikasi ini jika sudah."></textarea>
                            </div>

                            <!-- C. JIKA KUIS ONLINE (BUILDER SOAL) -->
                            <div x-show="assignmentType === 'quiz'" style="display: none;">
                                <div class="mb-6 flex gap-4">
                                    <div class="flex-1">
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Instruksi Kuis</label>
                                        <textarea name="description" rows="2" class="w-full rounded-xl border-gray-300 focus:ring-blue-500" placeholder="Kerjakan dengan jujur..."></textarea>
                                    </div>
                                    <div class="w-1/3">
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Durasi (Menit) <span class="text-red-500">*</span></label>
                                        <input type="number" name="duration_minutes" min="5" value="60" class="w-full rounded-xl border-gray-300 focus:ring-blue-500">
                                    </div>
                                </div>

                                <div class="space-y-4 mb-6">
                                    <!-- LIST PERTANYAAN -->
                                    <template x-for="(q, index) in questions" :key="index">
                                        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm relative group">
                                            <div class="absolute top-4 right-4">
                                                <button type="button" @click="questions = questions.filter((_, i) => i !== index)" class="text-gray-400 hover:text-red-500 transition"><i class="ph-bold ph-trash text-lg"></i></button>
                                            </div>
                                            
                                            <div class="flex gap-4 mb-4">
                                                <span class="bg-blue-100 text-blue-700 w-8 h-8 flex items-center justify-center rounded-full font-bold text-sm" x-text="index + 1"></span>
                                                <div class="flex-1">
                                                    <div class="flex gap-4 mb-2">
                                                        <select :name="'questions['+index+'][type]'" x-model="q.type" class="text-sm rounded-lg border-gray-300 bg-gray-50 font-bold text-gray-700">
                                                            <option value="multiple_choice">Pilihan Ganda</option>
                                                            <option value="essay">Essai</option>
                                                        </select>
                                                        <input type="number" :name="'questions['+index+'][points]'" x-model="q.points" class="text-sm rounded-lg border-gray-300 w-24" placeholder="Bobot" title="Bobot Nilai">
                                                    </div>
                                                    <textarea :name="'questions['+index+'][text]'" x-model="q.text" rows="2" class="w-full rounded-lg border-gray-300 text-sm mb-3" placeholder="Tuliskan pertanyaan..."></textarea>
                                                    
                                                    <!-- OPSI JAWABAN (Hanya utk PG) -->
                                                    <div x-show="q.type === 'multiple_choice'" class="space-y-2 ml-2 pl-4 border-l-2 border-gray-100">
                                                        <template x-for="opt in ['A', 'B', 'C', 'D', 'E']">
                                                            <div class="flex items-center gap-3">
                                                                <input type="radio" :name="'questions['+index+'][correct]'" :value="opt" class="text-green-600 focus:ring-green-500 cursor-pointer">
                                                                <span class="font-bold text-gray-500 text-sm w-4" x-text="opt + '.'"></span>
                                                                <input type="text" :name="'questions['+index+'][options]['+opt+']'" class="flex-1 rounded-md border-gray-300 text-sm py-1.5" :placeholder="'Pilihan ' + opt">
                                                            </div>
                                                        </template>
                                                        <p class="text-[10px] text-gray-400 mt-1">*Pilih radio button untuk menentukan kunci jawaban.</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <button type="button" @click="questions.push({type: 'multiple_choice', text: '', points: 10, options: {}})" 
                                        class="w-full py-3 border-2 border-dashed border-blue-300 text-blue-600 rounded-xl font-bold hover:bg-blue-50 transition flex items-center justify-center gap-2">
                                    <i class="ph-bold ph-plus-circle text-xl"></i> Tambah Pertanyaan
                                </button>
                            </div>

                        </div>

                        <!-- 4. TARGET SISWA (Sama seperti sebelumnya) -->
                        <div class="bg-white p-6 rounded-2xl border border-gray-200">
                            <label class="block text-sm font-bold text-gray-700 mb-3">Target Penerima</label>
                            <div class="space-y-3">
                                <div class="flex gap-4">
                                    <label class="inline-flex items-center"><input type="radio" name="target_type" value="class" x-model="targetType" class="text-blue-600 focus:ring-blue-500"><span class="ml-2 text-sm">Satu Kelas</span></label>
                                    <label class="inline-flex items-center"><input type="radio" name="target_type" value="grade" x-model="targetType" class="text-blue-600 focus:ring-blue-500"><span class="ml-2 text-sm">Satu Jenjang</span></label>
                                </div>
                                <div x-show="targetType === 'class'">
                                    <select name="class_id" class="w-full text-sm rounded-lg border-gray-300">
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div x-show="targetType === 'grade'" style="display: none;">
                                    <select name="target_grade" class="w-full text-sm rounded-lg border-gray-300">
                                        <option value="7">Kelas 7</option>
                                        <option value="8">Kelas 8</option>
                                        <option value="9">Kelas 9</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- FOOTER -->
                    <div class="bg-gray-50 px-8 py-5 flex justify-end gap-3 border-t border-gray-100">
                        <a href="{{ route('lms.assignments.index') }}" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition">Batal</a>
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-bold rounded-xl shadow-lg hover:bg-blue-700 hover:-translate-y-0.5 transition transform flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Terbitkan Tugas
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>