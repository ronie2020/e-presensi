<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Tugas / Kuis Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            
            <!-- PESAN ERROR -->
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-xl">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-bold text-red-800">Ada kesalahan input:</h3>
                            <ul class="mt-1 list-disc list-inside text-sm text-red-700">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white shadow-xl sm:rounded-2xl border border-gray-100 overflow-hidden">
                <form action="{{ route('lms.assignments.store') }}" method="POST" 
                      x-data="{ 
                          targetType: 'class', 
                          assignmentType: 'file_upload', 
                          questions: [] 
                      }">
                    @csrf

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
                            <div class="col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Judul Tugas <span class="text-red-500">*</span></label>
                                <input type="text" name="title" value="{{ old('title') }}" required class="w-full rounded-xl border-gray-300 focus:ring-blue-500" placeholder="Contoh: Ulangan Harian Bab 1">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Mata Pelajaran <span class="text-red-500">*</span></label>
                                <select name="subject_id" required class="w-full rounded-xl border-gray-300 focus:ring-blue-500">
                                    <option value="">-- Pilih Mapel --</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Batas Waktu (Deadline) <span class="text-red-500">*</span></label>
                                <input type="datetime-local" name="deadline" value="{{ old('deadline') }}" required class="w-full rounded-xl border-gray-300 focus:ring-blue-500">
                            </div>

                            <!-- Opsi Tambahan (DIKEMBALIKAN) -->
                            <div class="col-span-2">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" name="allow_late_submission" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500" {{ old('allow_late_submission') ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-600 font-medium">Izinkan pengumpulan terlambat</span>
                                </label>
                            </div>
                        </div>

                        <hr class="border-gray-100">

                        <!-- 2. PEMILIHAN TIPE TUGAS -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-4">Jenis Penugasan <span class="text-red-500">*</span></label>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <label class="cursor-pointer relative">
                                    <input type="radio" name="assignment_type" value="file_upload" x-model="assignmentType" class="peer sr-only">
                                    <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50 transition text-center h-full flex flex-col items-center justify-center gap-2">
                                        <span class="font-bold text-gray-700">Upload File/Foto</span>
                                        <span class="text-xs text-gray-500">Siswa mengunggah jawaban</span>
                                    </div>
                                </label>

                                <label class="cursor-pointer relative">
                                    <input type="radio" name="assignment_type" value="quiz" x-model="assignmentType" class="peer sr-only">
                                    <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50 transition text-center h-full flex flex-col items-center justify-center gap-2">
                                        <span class="font-bold text-gray-700">Kuis Online (CBT)</span>
                                        <span class="text-xs text-gray-500">Buat soal PG/Essai</span>
                                    </div>
                                </label>

                                <label class="cursor-pointer relative">
                                    <input type="radio" name="assignment_type" value="link" x-model="assignmentType" class="peer sr-only">
                                    <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50 transition text-center h-full flex flex-col items-center justify-center gap-2">
                                        <span class="font-bold text-gray-700">Link Eksternal</span>
                                        <span class="text-xs text-gray-500">Quizizz, GForm, dll</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200">
                            
                            <!-- A. JIKA UPLOAD FILE -->
                            <div x-show="assignmentType === 'file_upload'">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Instruksi / Soal</label>
                                <textarea name="description_file" rows="5" class="w-full rounded-xl border-gray-300 focus:ring-blue-500" placeholder="Tuliskan soal atau instruksi pengerjaan disini...">{{ old('description_file') }}</textarea>
                            </div>

                            <!-- B. JIKA LINK EKSTERNAL -->
                            <div x-show="assignmentType === 'link'" style="display: none;">
                                <div class="mb-4">
                                    <label class="block text-sm font-bold text-gray-700 mb-2">URL Link Tugas <span class="text-red-500">*</span></label>
                                    <input type="url" name="link_url" value="{{ old('link_url') }}" class="w-full rounded-xl border-gray-300 focus:ring-blue-500" placeholder="https://...">
                                </div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Instruksi Tambahan</label>
                                <textarea name="description_link" rows="3" class="w-full rounded-xl border-gray-300 focus:ring-blue-500" placeholder="Silakan kerjakan link di atas...">{{ old('description_link') }}</textarea>
                            </div>

                            <!-- C. JIKA KUIS ONLINE (DIKEMBALIKAN INSTRUKSI KUIS) -->
                            <div x-show="assignmentType === 'quiz'" style="display: none;">
                                <div class="mb-6 flex flex-col md:flex-row gap-4">
                                    <div class="flex-1">
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Instruksi Kuis</label>
                                        <textarea name="description_quiz" rows="2" class="w-full rounded-xl border-gray-300 focus:ring-blue-500" placeholder="Kerjakan dengan jujur...">{{ old('description_quiz') }}</textarea>
                                    </div>
                                    <div class="w-full md:w-1/3">
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Durasi (Menit) <span class="text-red-500">*</span></label>
                                        <input type="number" name="duration_minutes" value="{{ old('duration_minutes', 60) }}" class="w-full rounded-xl border-gray-300 focus:ring-blue-500">
                                    </div>
                                </div>

                                <div class="space-y-4 mb-6">
                                    <template x-for="(q, index) in questions" :key="index">
                                        <div class="bg-white p-5 rounded-xl border border-gray-200 shadow-sm relative">
                                            <div class="absolute top-4 right-4">
                                                <button type="button" @click="questions = questions.filter((_, i) => i !== index)" class="text-red-500 hover:text-red-700 transition">Hapus</button>
                                            </div>
                                            <div class="flex gap-4 mb-4">
                                                <span class="bg-blue-100 text-blue-700 w-8 h-8 flex items-center justify-center rounded-full font-bold" x-text="index + 1"></span>
                                                <div class="flex-1">
                                                    <div class="flex gap-4 mb-2">
                                                        <select :name="'questions['+index+'][type]'" x-model="q.type" class="text-sm rounded-lg border-gray-300">
                                                            <option value="multiple_choice">Pilihan Ganda</option>
                                                            <option value="essay">Essai</option>
                                                        </select>
                                                        <input type="number" :name="'questions['+index+'][points]'" x-model="q.points" class="text-sm rounded-lg border-gray-300 w-24" placeholder="Poin">
                                                    </div>
                                                    <textarea :name="'questions['+index+'][text]'" x-model="q.text" rows="2" class="w-full rounded-lg border-gray-300 text-sm mb-3" placeholder="Tuliskan pertanyaan..."></textarea>
                                                    
                                                    <div x-show="q.type === 'multiple_choice'" class="space-y-2 ml-4">
                                                        <template x-for="opt in ['A', 'B', 'C', 'D', 'E']">
                                                            <div class="flex items-center gap-3">
                                                                <input type="radio" :name="'questions['+index+'][correct]'" :value="opt" class="text-green-600">
                                                                <input type="text" :name="'questions['+index+'][options]['+opt+']'" class="flex-1 rounded-md border-gray-300 text-sm py-1" :placeholder="'Opsi ' + opt">
                                                            </div>
                                                        </template>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <button type="button" @click="questions.push({type: 'multiple_choice', text: '', points: 10})" 
                                        class="w-full py-3 border-2 border-dashed border-blue-300 text-blue-600 rounded-xl font-bold hover:bg-blue-50 transition">
                                    + Tambah Pertanyaan
                                </button>
                            </div>
                        </div>

                        <div class="bg-white p-6 rounded-2xl border border-gray-200">
                            <label class="block text-sm font-bold text-gray-700 mb-3">Target Penerima <span class="text-red-500">*</span></label>
                            <div class="space-y-3">
                                <div class="flex gap-4">
                                    <label class="inline-flex items-center"><input type="radio" name="target_type" value="class" x-model="targetType" class="text-blue-600"><span class="ml-2 text-sm">Satu Kelas</span></label>
                                    <label class="inline-flex items-center"><input type="radio" name="target_type" value="grade" x-model="targetType" class="text-blue-600"><span class="ml-2 text-sm">Satu Jenjang</span></label>
                                </div>
                                <div x-show="targetType === 'class'">
                                    <select name="class_id" class="w-full text-sm rounded-lg border-gray-300">
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div x-show="targetType === 'grade'" style="display: none;">
                                    <select name="target_grade" class="w-full text-sm rounded-lg border-gray-300">
                                        <option value="7" {{ old('target_grade') == '7' ? 'selected' : '' }}>Kelas 7</option>
                                        <option value="8" {{ old('target_grade') == '8' ? 'selected' : '' }}>Kelas 8</option>
                                        <option value="9" {{ old('target_grade') == '9' ? 'selected' : '' }}>Kelas 9</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gray-50 px-8 py-5 flex justify-end gap-3 border-t border-gray-100">
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-bold rounded-xl shadow-lg hover:bg-blue-700 transition">
                            Terbitkan Tugas
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>