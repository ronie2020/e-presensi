<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            <?php echo e(__('Buat Tugas Baru')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-8 font-sans text-slate-800">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-slate-900 via-blue-900 to-blue-800 p-8 mb-8 text-white shadow-xl shadow-blue-900/30 overflow-hidden border border-white/10">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-extrabold mb-1 tracking-tight">Setting Penugasan</h1>
                        <p class="text-blue-300 text-sm font-medium">Atur detail tugas, kuis, atau instruksi untuk siswa.</p>
                    </div>
                    <a href="<?php echo e(route('lms.assignments.index')); ?>" class="hidden md:flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 rounded-xl text-sm font-bold backdrop-blur-sm transition text-white border border-white/10 btn-cancel-confirm">
                        <i class="ph-bold ph-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            
            <?php if($errors->any()): ?>
                <div class="mb-8 bg-rose-50 border border-rose-100 p-5 rounded-[1.5rem] flex items-start gap-4 shadow-sm animate-pulse">
                    <div class="p-2 bg-rose-100 text-rose-600 rounded-xl shrink-0">
                        <i class="ph-bold ph-warning-octagon text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-rose-800 uppercase tracking-wide mb-1">Gagal Menyimpan</h3>
                        <ul class="list-disc list-inside text-sm text-rose-700 space-y-1 font-medium">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            
            <div class="bg-white shadow-xl shadow-slate-200/50 rounded-[2rem] border border-slate-100 overflow-hidden">
                <form action="<?php echo e(route('lms.assignments.store')); ?>" method="POST" id="createAssignmentForm" 
                      x-data="{ 
                          targetType: 'class', 
                          assignmentType: 'file_upload', 
                          questions: [] 
                      }">
                    <?php echo csrf_field(); ?>

                    <div class="p-8 space-y-8">
                        
                        <!-- 1. IDENTITAS TUGAS -->
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl"><i class="ph-bold ph-info"></i></div>
                                <h3 class="text-lg font-black text-slate-800">Informasi Dasar</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="col-span-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Judul Tugas <span class="text-rose-500">*</span></label>
                                    <input type="text" name="title" value="<?php echo e(old('title')); ?>" required class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-slate-800 focus:ring-blue-500 focus:border-blue-500 h-12 px-4 placeholder:font-normal placeholder:text-slate-400 transition-colors" placeholder="Contoh: Ulangan Harian Bab 1">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Mata Pelajaran <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <select name="subject_id" required class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-slate-800 focus:ring-blue-500 focus:border-blue-500 h-12 px-4 appearance-none transition-colors">
                                            <option value="">-- Pilih Mapel --</option>
                                            <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($subject->id); ?>" <?php echo e(old('subject_id') == $subject->id ? 'selected' : ''); ?>><?php echo e($subject->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Deadline <span class="text-rose-500">*</span></label>
                                    <input type="datetime-local" name="deadline" value="<?php echo e(old('deadline')); ?>" required class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-slate-800 focus:ring-blue-500 focus:border-blue-500 h-12 px-4 transition-colors">
                                </div>

                                <div class="col-span-2">
                                    <label class="inline-flex items-center cursor-pointer group">
                                        <div class="relative">
                                            <input type="checkbox" name="allow_late_submission" class="sr-only peer" <?php echo e(old('allow_late_submission') ? 'checked' : ''); ?>>
                                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                        </div>
                                        <span class="ml-3 text-sm font-bold text-slate-600 group-hover:text-blue-700 transition">Izinkan pengumpulan terlambat</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="h-px bg-slate-100"></div>

                        <!-- 2. PILIHAN TIPE TUGAS -->
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 ml-1">Jenis Penugasan <span class="text-rose-500">*</span></label>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                
                                <label class="cursor-pointer group relative">
                                    <input type="radio" name="assignment_type" value="file_upload" x-model="assignmentType" class="peer sr-only">
                                    <div class="p-5 rounded-2xl border-2 border-slate-100 bg-white hover:border-blue-200 hover:bg-blue-50/30 transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:shadow-md flex flex-col items-center justify-center text-center h-full gap-3">
                                        <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-2xl peer-checked:bg-blue-600 peer-checked:text-white transition-colors">
                                            <i class="ph-duotone ph-upload-simple"></i>
                                        </div>
                                        <div>
                                            <span class="block font-bold text-slate-800 peer-checked:text-blue-700">Upload File/Foto</span>
                                            <span class="block text-xs text-slate-500 font-medium mt-1">Siswa mengunggah bukti/jawaban</span>
                                        </div>
                                        <div class="absolute top-4 right-4 text-blue-600 opacity-0 peer-checked:opacity-100 transition-opacity"><i class="ph-fill ph-check-circle text-xl"></i></div>
                                    </div>
                                </label>

                                
                                <label class="cursor-pointer group relative">
                                    <input type="radio" name="assignment_type" value="quiz" x-model="assignmentType" class="peer sr-only">
                                    <div class="p-5 rounded-2xl border-2 border-slate-100 bg-white hover:border-purple-200 hover:bg-purple-50/30 transition-all peer-checked:border-purple-500 peer-checked:bg-purple-50 peer-checked:shadow-md flex flex-col items-center justify-center text-center h-full gap-3">
                                        <div class="w-12 h-12 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-2xl peer-checked:bg-purple-600 peer-checked:text-white transition-colors">
                                            <i class="ph-duotone ph-brain"></i>
                                        </div>
                                        <div>
                                            <span class="block font-bold text-slate-800 peer-checked:text-purple-700">Kuis Online (CBT)</span>
                                            <span class="block text-xs text-slate-500 font-medium mt-1">Buat soal PG atau Essai</span>
                                        </div>
                                        <div class="absolute top-4 right-4 text-purple-600 opacity-0 peer-checked:opacity-100 transition-opacity"><i class="ph-fill ph-check-circle text-xl"></i></div>
                                    </div>
                                </label>

                                
                                <label class="cursor-pointer group relative">
                                    <input type="radio" name="assignment_type" value="link" x-model="assignmentType" class="peer sr-only">
                                    <div class="p-5 rounded-2xl border-2 border-slate-100 bg-white hover:border-sky-200 hover:bg-sky-50/30 transition-all peer-checked:border-sky-500 peer-checked:bg-sky-50 peer-checked:shadow-md flex flex-col items-center justify-center text-center h-full gap-3">
                                        <div class="w-12 h-12 rounded-full bg-sky-100 text-sky-600 flex items-center justify-center text-2xl peer-checked:bg-sky-600 peer-checked:text-white transition-colors">
                                            <i class="ph-duotone ph-link"></i>
                                        </div>
                                        <div>
                                            <span class="block font-bold text-slate-800 peer-checked:text-sky-700">Link Eksternal</span>
                                            <span class="block text-xs text-slate-500 font-medium mt-1">GForm, Quizizz, YouTube, dll</span>
                                        </div>
                                        <div class="absolute top-4 right-4 text-sky-600 opacity-0 peer-checked:opacity-100 transition-opacity"><i class="ph-fill ph-check-circle text-xl"></i></div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- 3. KONTEN DINAMIS -->
                        <div class="bg-slate-50 rounded-3xl p-6 border border-slate-100">
                            
                            <!-- A. JIKA UPLOAD FILE -->
                            <div x-show="assignmentType === 'file_upload'">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Instruksi / Soal</label>
                                <textarea name="description_file" rows="5" class="w-full rounded-2xl border-slate-200 bg-white focus:ring-blue-500 focus:border-blue-500 p-4 text-slate-700 font-medium placeholder:font-normal placeholder:text-slate-400 transition-colors" placeholder="Tuliskan soal atau instruksi pengerjaan disini..."><?php echo e(old('description_file')); ?></textarea>
                            </div>

                            <!-- B. JIKA LINK EKSTERNAL -->
                            <div x-show="assignmentType === 'link'" style="display: none;">
                                <div class="mb-4">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">URL Link Tugas <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-link"></i></div>
                                        <input type="url" name="link_url" value="<?php echo e(old('link_url')); ?>" class="w-full rounded-xl border-slate-200 bg-white pl-10 font-bold text-blue-600 focus:ring-blue-500 h-12 transition-colors" placeholder="https://...">
                                    </div>
                                </div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Instruksi Tambahan</label>
                                <textarea name="description_link" rows="3" class="w-full rounded-2xl border-slate-200 bg-white focus:ring-blue-500 p-4 font-medium transition-colors" placeholder="Silakan kerjakan link di atas..."><?php echo e(old('description_link')); ?></textarea>
                            </div>

                            <!-- C. JIKA KUIS ONLINE -->
                            <div x-show="assignmentType === 'quiz'" style="display: none;">
                                <div class="mb-6 flex flex-col md:flex-row gap-4">
                                    <div class="flex-1">
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Instruksi Kuis</label>
                                        <textarea name="description_quiz" rows="2" class="w-full rounded-xl border-slate-200 bg-white focus:ring-purple-500 p-3 transition-colors" placeholder="Kerjakan dengan jujur..."><?php echo e(old('description_quiz')); ?></textarea>
                                    </div>
                                    <div class="w-full md:w-1/3">
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Durasi (Menit) <span class="text-rose-500">*</span></label>
                                        <div class="relative">
                                            <input type="number" name="duration_minutes" value="<?php echo e(old('duration_minutes', 60)); ?>" class="w-full rounded-xl border-slate-200 bg-white font-bold text-slate-800 focus:ring-purple-500 h-11 pl-4 pr-10 transition-colors">
                                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400 text-xs font-bold">MIN</div>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-4 mb-6">
                                    <template x-for="(q, index) in questions" :key="index">
                                        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative group hover:border-purple-200 transition-colors">
                                            <button type="button" @click="questions = questions.filter((_, i) => i !== index)" class="absolute top-4 right-4 w-8 h-8 rounded-lg bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white flex items-center justify-center transition-colors">
                                                <i class="ph-bold ph-trash"></i>
                                            </button>
                                            
                                            <div class="flex gap-4">
                                                <span class="bg-purple-100 text-purple-700 w-8 h-8 flex items-center justify-center rounded-lg font-bold text-sm shrink-0" x-text="index + 1"></span>
                                                <div class="flex-1">
                                                    <div class="flex gap-4 mb-3">
                                                        <select :name="'questions['+index+'][type]'" x-model="q.type" class="text-xs font-bold rounded-lg border-slate-200 bg-slate-50 h-9">
                                                            <option value="multiple_choice">Pilihan Ganda</option>
                                                            <option value="essay">Essai / Jawaban Panjang</option>
                                                        </select>
                                                        <input type="number" :name="'questions['+index+'][points]'" x-model="q.points" class="text-xs font-bold rounded-lg border-slate-200 bg-slate-50 w-24 h-9 px-3" placeholder="Poin">
                                                    </div>
                                                    
                                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Pertanyaan / Soal</label>
                                                    <textarea :name="'questions['+index+'][text]'" x-model="q.text" rows="2" class="w-full rounded-xl border-slate-200 text-sm mb-4 focus:ring-purple-500 font-medium" placeholder="Tuliskan pertanyaan..."></textarea>
                                                    
                                                    <!-- UI UNTUK PILIHAN GANDA -->
                                                    <div x-show="q.type === 'multiple_choice'" class="space-y-2 ml-1">
                                                        <template x-for="opt in ['A', 'B', 'C', 'D', 'E']">
                                                            <div class="flex items-center gap-3">
                                                                <input type="radio" :name="'questions['+index+'][correct]'" :value="opt" class="text-emerald-600 focus:ring-emerald-500 cursor-pointer">
                                                                <div class="flex-1 relative">
                                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 text-xs font-bold" x-text="opt"></div>
                                                                    <input type="text" :name="'questions['+index+'][options]['+opt+']'" class="w-full rounded-lg border-slate-200 bg-slate-50 text-sm py-2 pl-8 focus:ring-purple-500" :placeholder="'Pilihan Jawaban ' + opt">
                                                                </div>
                                                            </div>
                                                        </template>
                                                    </div>

                                                    <!-- UI UNTUK ESSAY (BARU) -->
                                                    <div x-show="q.type === 'essay'" class="mt-4">
                                                        <div class="bg-amber-50 border border-amber-100 rounded-xl p-4">
                                                            <div class="flex items-start gap-3">
                                                                <div class="p-2 bg-amber-100 text-amber-600 rounded-lg shrink-0">
                                                                    <i class="ph-bold ph-pencil-simple-line"></i>
                                                                </div>
                                                                <div class="w-full">
                                                                    <h4 class="font-bold text-amber-800 text-sm mb-1">Referensi Jawaban (Opsional)</h4>
                                                                    <p class="text-xs text-amber-600 mb-2">Anda dapat memasukkan poin-poin penting dari jawaban yang diharapkan. (Hanya terlihat oleh Guru)</p>
                                                                    <textarea 
                                                                        :name="'questions['+index+'][answer_key]'" 
                                                                        rows="3" 
                                                                        class="w-full rounded-lg border-amber-200 bg-white text-sm focus:ring-amber-500 text-slate-700"
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
                                        class="w-full py-3 border-2 border-dashed border-purple-200 bg-purple-50 text-purple-600 rounded-xl font-bold text-sm hover:bg-purple-100 hover:border-purple-300 transition flex items-center justify-center gap-2">
                                    <i class="ph-bold ph-plus"></i> Tambah Pertanyaan
                                </button>
                            </div>
                        </div>

                        <!-- 4. TARGET PENERIMA -->
                        <div class="bg-blue-50/50 p-6 rounded-2xl border border-blue-100/50">
                            <label class="block text-xs font-black text-blue-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                                <i class="ph-fill ph-users-three"></i> Target Penerima <span class="text-rose-500">*</span>
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="flex gap-4">
                                    <label class="inline-flex items-center cursor-pointer group">
                                        <div class="relative flex items-center">
                                            <input type="radio" name="target_type" value="class" x-model="targetType" class="peer sr-only">
                                            <div class="w-5 h-5 border-2 border-slate-300 rounded-full peer-checked:border-blue-600 peer-checked:bg-blue-600 transition"></div>
                                        </div>
                                        <span class="ml-2 text-sm font-bold text-slate-600 group-hover:text-blue-700 transition">Satu Kelas</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer group">
                                        <div class="relative flex items-center">
                                            <input type="radio" name="target_type" value="grade" x-model="targetType" class="peer sr-only">
                                            <div class="w-5 h-5 border-2 border-slate-300 rounded-full peer-checked:border-blue-600 peer-checked:bg-blue-600 transition"></div>
                                        </div>
                                        <span class="ml-2 text-sm font-bold text-slate-600 group-hover:text-blue-700 transition">Satu Jenjang</span>
                                    </label>
                                </div>

                                <div>
                                    <div x-show="targetType === 'class'">
                                        <div class="relative">
                                            <select name="class_id" 
                                                    :required="targetType === 'class'"
                                                    class="w-full text-sm font-bold rounded-xl border-slate-200 bg-white focus:ring-blue-500 h-11 px-3 appearance-none transition-colors">
                                                <option value="">-- Pilih Kelas --</option>
                                                <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($class->id); ?>" <?php echo e(old('class_id') == $class->id ? 'selected' : ''); ?>><?php echo e($class->name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                                        </div>
                                    </div>
                                    <div x-show="targetType === 'grade'" style="display: none;">
                                        <div class="relative">
                                            <select name="target_grade" 
                                                    :required="targetType === 'grade'"
                                                    class="w-full text-sm font-bold rounded-xl border-slate-200 bg-white focus:ring-blue-500 h-11 px-3 appearance-none transition-colors">
                                                <option value="7" <?php echo e(old('target_grade') == '7' ? 'selected' : ''); ?>>Kelas 7</option>
                                                <option value="8" <?php echo e(old('target_grade') == '8' ? 'selected' : ''); ?>>Kelas 8</option>
                                                <option value="9" <?php echo e(old('target_grade') == '9' ? 'selected' : ''); ?>>Kelas 9</option>
                                            </select>
                                            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FOOTER ACTIONS -->
                    <div class="bg-slate-50 px-8 py-6 flex justify-end gap-3 border-t border-slate-100">
                        <a href="<?php echo e(route('lms.assignments.index')); ?>" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-100 transition text-center text-sm btn-cancel-confirm">Batal</a>
                        
                        <button type="submit" class="px-8 py-3 bg-blue-900 text-white font-bold rounded-xl shadow-lg shadow-blue-900/20 hover:bg-blue-800 hover:-translate-y-0.5 transition transform flex items-center justify-center gap-2 text-sm">
                            <i class="ph-bold ph-paper-plane-tilt text-lg"></i>
                            <span>Terbitkan Tugas</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    
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
                        confirmButtonColor: '#64748b', // Slate-500
                        cancelButtonColor: '#cbd5e1', // Slate-300
                        confirmButtonText: 'Ya, Batalkan',
                        cancelButtonText: 'Lanjut Mengisi',
                        customClass: {
                            popup: 'rounded-[2rem]',
                            confirmButton: 'rounded-xl px-4 py-2 font-bold',
                            cancelButton: 'rounded-xl px-4 py-2 font-bold text-slate-600'
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
                            popup: 'rounded-[2rem]'
                        }
                    });

                    setTimeout(() => {
                        this.submit();
                    }, 500);
                });
            }
        });
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/lms/assignments/create.blade.php ENDPATH**/ ?>