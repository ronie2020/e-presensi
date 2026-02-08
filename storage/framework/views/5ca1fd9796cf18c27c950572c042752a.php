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
    
    <script>
        window.MathJax = {
            tex: { inlineMath: [['$', '$'], ['\\(', '\\)']] },
            svg: { fontCache: 'global' }
        };
    </script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>

    <div x-data="{ 
        showImportModal: false, 
        showEditModal: false,
        questionSearch: '', 
        editState: {
            url: '',
            question_text: '',
            question_image: '', 
            option_A: '', option_B: '', option_C: '', option_D: '',
            correct_answer: 'A', score_weight: 2
        },
        newImagePreview: null,
        deleteImage: false,
        
        openEdit(data, url) {
            this.editState = { ...data, url: url };
            this.newImagePreview = null;
            this.deleteImage = false;
            this.showEditModal = true;
        },
        
        handleEditImage(event) {
            const file = event.target.files[0];
            if (file) {
                this.newImagePreview = URL.createObjectURL(file);
                this.deleteImage = false;
            }
        },

        removeCurrentImage() {
            this.deleteImage = true;
            this.newImagePreview = null;
            this.$refs.editFileInput.value = '';
        }
    }">
        
         <?php $__env->slot('header', null, []); ?> 
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                <?php echo e(__('Kelola Soal Ujian')); ?>

            </h2>
         <?php $__env->endSlot(); ?>

        <div class="py-8 sm:py-10 font-sans text-slate-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
                
                
                <div class="relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-slate-900 via-blue-900 to-blue-800 p-8 text-white shadow-xl shadow-blue-900/30 overflow-hidden border border-white/10">
                    <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                    
                    <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <a href="<?php echo e(route('cbt.index')); ?>" class="text-xs font-bold text-blue-300 hover:text-white transition flex items-center gap-1">
                                    <i class="ph-bold ph-arrow-left"></i> Dashboard
                                </a>
                                <span class="text-white/30 text-xs">•</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase tracking-wide bg-white/10 text-white border border-white/10">
                                    <?php echo e($exam->subject_name); ?>

                                </span>
                            </div>
                            <h1 class="text-3xl font-black tracking-tight leading-none text-white mb-1"><?php echo e($exam->title); ?></h1>
                        </div>
                        
                        <div class="flex items-center gap-4 w-full md:w-auto">
                            <button @click="showImportModal = true" class="group flex-1 md:flex-none px-5 py-3 bg-emerald-600 text-white font-bold rounded-2xl hover:bg-emerald-500 transition flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/20 active:scale-95">
                                <i class="ph-bold ph-microsoft-excel-logo text-xl"></i> 
                                <span>Import Excel</span>
                            </button>

                            <div class="hidden md:block bg-white/10 backdrop-blur-md px-5 py-2 rounded-2xl border border-white/10 text-center min-w-[100px]">
                                <p class="text-3xl font-black text-white leading-none"><?php echo e($exam->questions->count()); ?></p>
                                <p class="text-[9px] font-bold text-blue-300 uppercase tracking-wider mt-1">Total Soal</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col lg:flex-row gap-8 items-start">
                    
                    
                    <div class="w-full lg:w-2/5 order-2 lg:order-1">
                        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 lg:sticky lg:top-8"
                             x-data="{ createPreview: null }">
                             
                            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-50">
                                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-lg shadow-sm border border-blue-100">
                                    <i class="ph-fill ph-plus-circle"></i>
                                </div>
                                <div>
                                    <h3 class="font-black text-slate-800 text-lg leading-none">Buat Soal Baru</h3>
                                    <p class="text-xs text-slate-400 font-bold mt-1 uppercase tracking-wider">Input Manual</p>
                                </div>
                            </div>

                            <form action="<?php echo e(route('cbt.questions.store', $exam->id)); ?>" method="POST" enctype="multipart/form-data" class="space-y-5" id="createQuestionForm">
                                <?php echo csrf_field(); ?>
                                
                                
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Pertanyaan</label>
                                    <textarea name="question_text" rows="4" required class="w-full rounded-2xl border-slate-200 text-sm focus:ring-blue-500 focus:border-blue-500 bg-slate-50 focus:bg-white p-4 font-medium text-slate-700 leading-relaxed placeholder:text-slate-400" placeholder="Tulis pertanyaan di sini... (Gunakan $...$ untuk rumus matematika)"></textarea>
                                </div>

                                
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Gambar (Opsional)</label>
                                    <template x-if="createPreview">
                                        <div class="mb-3 relative w-full group">
                                            <img :src="createPreview" class="w-full h-40 object-cover rounded-2xl border border-slate-200">
                                            <button type="button" @click="createPreview = null; $refs.fileInput.value = ''" class="absolute top-2 right-2 bg-rose-500 text-white p-1.5 rounded-xl shadow-lg hover:bg-rose-600 transition transform hover:scale-110">
                                                <i class="ph-bold ph-trash"></i>
                                            </button>
                                        </div>
                                    </template>
                                    <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-slate-200 rounded-2xl cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition-all group/upload bg-slate-50">
                                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                            <i class="ph-duotone ph-image text-2xl text-slate-300 group-hover/upload:text-blue-500 mb-1 transition-colors"></i>
                                            <p class="text-xs text-slate-400 group-hover/upload:text-slate-600 font-bold">Upload Gambar</p>
                                        </div>
                                        <input type="file" x-ref="fileInput" name="question_image" @change="createPreview = URL.createObjectURL($event.target.files[0])" accept="image/*" class="hidden">
                                    </label>
                                </div>

                                
                                <div class="space-y-3 pt-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase ml-1">Pilihan Jawaban</label>
                                    <?php $__currentLoopData = ['A','B','C','D']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex gap-3 items-center group">
                                        <span class="w-10 h-10 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center font-black text-sm group-focus-within:bg-blue-600 group-focus-within:text-white transition-colors shrink-0"><?php echo e($opt); ?></span>
                                        <input type="text" name="option_<?php echo e($opt); ?>" required class="flex-1 rounded-xl border-slate-200 bg-white text-sm py-2.5 px-4 focus:ring-blue-500 focus:border-blue-500 font-medium transition-shadow focus:shadow-md" placeholder="Jawaban <?php echo e($opt); ?>">
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>

                                
                                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-100">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Kunci Jawaban</label>
                                        <div class="relative">
                                            <select name="correct_answer" required class="w-full rounded-xl border-slate-200 text-sm font-bold text-slate-700 bg-slate-50 cursor-pointer h-11 px-4 appearance-none focus:ring-blue-500 focus:border-blue-500">
                                                <option value="A">A</option>
                                                <option value="B">B</option>
                                                <option value="C">C</option>
                                                <option value="D">D</option>
                                            </select>
                                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Bobot Poin</label>
                                        <input type="number" name="score_weight" value="2" required class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm font-bold text-center h-11 focus:ring-blue-500 focus:border-blue-500">
                                    </div>
                                </div>

                                <button type="submit" class="w-full py-3.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-500/20 flex items-center justify-center gap-2 transform active:scale-95">
                                    <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan Soal
                                </button>
                            </form>
                        </div>
                    </div>

                    
                    <div class="w-full lg:w-3/5 order-1 lg:order-2 space-y-6">
                        <div class="flex flex-col sm:flex-row justify-between items-center px-2 gap-4">
                            <h3 class="font-black text-slate-800 text-lg flex items-center gap-2">
                                <i class="ph-fill ph-list-dashes text-blue-500"></i> Daftar Soal
                                <span class="text-xs font-bold text-slate-500 bg-white border border-slate-200 px-3 py-1.5 rounded-lg shadow-sm">
                                    <?php echo e($exam->questions->count()); ?>

                                </span>
                            </h3>
                            
                            
                            <div class="relative w-full sm:w-64">
                                <i class="ph-bold ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="text" x-model="questionSearch" placeholder="Cari isi pertanyaan..." 
                                       class="w-full pl-10 pr-4 py-2 text-sm font-bold border-slate-200 rounded-xl focus:ring-blue-500 bg-white shadow-sm">
                            </div>
                        </div>
                        
                        <?php $__empty_1 = true; $__currentLoopData = $exam->questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            
                            <div x-show="questionSearch === '' || '<?php echo e(strtolower(addslashes($q->question_text))); ?>'.includes(questionSearch.toLowerCase())"
                                 class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm relative group hover:border-blue-200 hover:shadow-lg transition-all duration-300">
                                
                                <!-- Nomor -->
                                <div class="absolute top-6 left-6 w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center font-black text-slate-500 text-sm group-hover:bg-blue-600 group-hover:text-white transition-colors shadow-inner">
                                    <?php echo e($index + 1); ?>

                                </div>
                                
                                <!-- Konten -->
                                <div class="pl-16">
                                    <?php if($q->question_image): ?>
                                        <div class="mb-4 group/img relative w-fit">
                                            
                                            <img src="<?php echo e(asset('storage/' . $q->question_image)); ?>" 
                                                 class="max-h-48 rounded-2xl border border-slate-100 shadow-sm object-cover cursor-zoom-in hover:opacity-90 transition" 
                                                 alt="Gambar Soal"
                                                 onclick="viewImage('<?php echo e(asset('storage/' . $q->question_image)); ?>')">
                                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-0 group-hover/img:opacity-100 transition">
                                                <span class="bg-black/50 text-white p-2 rounded-full backdrop-blur-sm"><i class="ph-bold ph-magnifying-glass-plus"></i></span>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <p class="text-slate-800 font-bold text-lg mb-5 leading-relaxed whitespace-pre-line"><?php echo e($q->question_text); ?></p>
                                    
                                    <!-- Opsi -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                        <?php $__currentLoopData = ['A','B','C','D']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php $val = isset($q->{'option_'.$opt}) ? $q->{'option_'.$opt} : ($q->options[$opt] ?? '-'); ?>
                                            <div class="flex items-start gap-3 p-2.5 rounded-xl border transition-colors <?php echo e($opt == $q->correct_answer ? 'bg-emerald-50 border-emerald-200' : 'bg-slate-50/50 border-transparent'); ?>">
                                                <span class="w-6 h-6 flex items-center justify-center rounded-lg border <?php echo e($opt == $q->correct_answer ? 'border-emerald-500 bg-emerald-500 text-white' : 'border-slate-300 text-slate-400 bg-white'); ?> text-[10px] font-black shrink-0 mt-0.5">
                                                    <?php echo e($opt); ?>

                                                </span>
                                                <span class="leading-relaxed <?php echo e($opt == $q->correct_answer ? 'text-emerald-800 font-bold' : 'text-slate-600 font-medium'); ?>"><?php echo e($val); ?></span>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                    
                                    <div class="mt-5 pt-3 border-t border-slate-50 flex items-center gap-3">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase bg-slate-50 px-2 py-1 rounded-md border border-slate-100">Bobot: <?php echo e($q->score_weight); ?> Poin</span>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="absolute top-6 right-6 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <button 
                                        type="button"
                                        @click="openEdit({
                                            question_text: <?php echo e(json_encode($q->question_text)); ?>,
                                            question_image: <?php echo e(json_encode($q->question_image)); ?>, 
                                            option_A: <?php echo e(json_encode($q->option_A ?? '')); ?>,
                                            option_B: <?php echo e(json_encode($q->option_B ?? '')); ?>,
                                            option_C: <?php echo e(json_encode($q->option_C ?? '')); ?>,
                                            option_D: <?php echo e(json_encode($q->option_D ?? '')); ?>,
                                            correct_answer: '<?php echo e($q->correct_answer); ?>',
                                            score_weight: <?php echo e($q->score_weight); ?>

                                        }, '<?php echo e(route('cbt.questions.update', $q->id)); ?>')"
                                        class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-blue-600 hover:border-blue-200 shadow-sm flex items-center justify-center transition-all hover:scale-105" title="Edit">
                                        <i class="ph-bold ph-pencil-simple text-lg"></i>
                                    </button>

                                    <form action="<?php echo e(route('cbt.questions.destroy', $q->id)); ?>" method="POST" class="delete-form">
                                        <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        <button type="button" class="w-9 h-9 rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-rose-600 hover:border-rose-200 shadow-sm flex items-center justify-center transition-all hover:scale-105 btn-delete" title="Hapus">
                                            <i class="ph-bold ph-trash text-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center py-16 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200">
                                <div class="w-20 h-20 bg-slate-50 rounded-[2rem] flex items-center justify-center mx-auto mb-4 text-slate-300 animate-pulse">
                                    <i class="ph-duotone ph-clipboard-text text-4xl"></i>
                                </div>
                                <h3 class="text-slate-800 font-black text-xl mb-1">Bank Soal Kosong</h3>
                                <p class="text-slate-500 text-sm max-w-xs mx-auto font-medium">Mulai tambahkan soal secara manual atau import dari file Excel.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        
        <div x-show="showImportModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
             <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showImportModal = false"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-[2.5rem] bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-md border border-slate-100">
                    <div class="bg-white p-8 text-center">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-3xl bg-emerald-50 mb-6 border border-emerald-100 shadow-sm">
                            <i class="ph-duotone ph-microsoft-excel-logo text-emerald-600 text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-black text-slate-800 mb-2">Import Soal Excel</h3>
                        
                        <p class="text-sm text-slate-500 mb-2 font-medium">Silakan download template di bawah ini agar format sesuai.</p>
                        
                        <div class="mb-6">
                            <a href="<?php echo e(route('cbt.questions.template')); ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-100 text-slate-600 rounded-xl text-xs font-bold hover:bg-slate-200 hover:text-slate-800 transition border border-slate-200">
                                <i class="ph-bold ph-download-simple text-lg"></i> Download Template Excel
                            </a>
                        </div>
                        
                        <form action="<?php echo e(route('cbt.questions.import', $exam->id)); ?>" method="POST" enctype="multipart/form-data" class="space-y-5 text-left" id="importQuestionForm">
                            <?php echo csrf_field(); ?>
                            <label class="block w-full border-2 border-dashed border-slate-200 rounded-3xl p-8 text-center hover:bg-slate-50 hover:border-emerald-300 transition-all cursor-pointer relative group">
                                <input type="file" name="file" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                <i class="ph-duotone ph-cloud-arrow-up text-4xl text-slate-300 group-hover:text-emerald-500 mb-3 transition-colors inline-block"></i>
                                <p class="text-sm font-bold text-slate-600 group-hover:text-emerald-700">Klik untuk upload file</p>
                                <p class="text-[10px] text-slate-400 mt-1 font-bold uppercase tracking-wider">Format: .xlsx</p>
                            </label>
                            
                            <div class="flex gap-3">
                                <button type="button" @click="showImportModal = false" class="flex-1 py-3.5 bg-white border border-slate-200 text-slate-600 font-bold rounded-2xl hover:bg-slate-50 transition shadow-sm text-sm">Batal</button>
                                <button type="submit" class="flex-1 py-3.5 bg-emerald-600 text-white font-bold rounded-2xl hover:bg-emerald-700 shadow-lg shadow-emerald-500/20 transition text-sm">Proses Import</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        
        <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
             <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showEditModal = false"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-[2.5rem] bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-2xl border border-slate-100">
                    <form :action="editState.url" method="POST" enctype="multipart/form-data" id="editQuestionForm">
                        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
                        <input type="hidden" name="delete_image" x-model="deleteImage">

                        <div class="bg-white px-8 py-6 border-b border-slate-100 flex justify-between items-center">
                            <h3 class="text-xl font-black text-slate-800 flex items-center gap-2">
                                <i class="ph-fill ph-pencil-simple text-blue-600"></i> Edit Soal
                            </h3>
                            <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-rose-500 transition bg-slate-50 w-10 h-10 rounded-xl flex items-center justify-center">
                                <i class="ph-bold ph-x text-lg"></i>
                            </button>
                        </div>

                        <div class="p-8 space-y-6 max-h-[70vh] overflow-y-auto custom-scrollbar bg-slate-50/30">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Pertanyaan</label>
                                <textarea name="question_text" x-model="editState.question_text" rows="4" required class="w-full rounded-2xl border-slate-200 text-sm focus:ring-blue-500 focus:border-blue-500 bg-white font-medium p-4"></textarea>
                            </div>

                            <!-- Gambar Update -->
                            <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100">
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-3 ml-1">Gambar Soal</label>
                                <div class="flex flex-col gap-4">
                                    <template x-if="newImagePreview">
                                        <div class="relative w-fit">
                                            <p class="text-[10px] font-bold text-emerald-600 mb-2 flex items-center gap-1 bg-emerald-50 px-2 py-1 rounded-md w-fit"><i class="ph-bold ph-check"></i> Akan Diganti:</p>
                                            <img :src="newImagePreview" class="h-32 rounded-xl border-2 border-emerald-500 shadow-sm object-cover">
                                            <button type="button" @click="newImagePreview = null; $refs.editFileInput.value = ''" class="absolute -top-2 -right-2 bg-rose-500 text-white rounded-xl p-1.5 shadow-lg hover:bg-rose-600 transition">
                                                <i class="ph-bold ph-x text-sm"></i>
                                            </button>
                                        </div>
                                    </template>

                                    <template x-if="!newImagePreview && editState.question_image && !deleteImage">
                                        <div class="relative w-fit group">
                                            <p class="text-[10px] font-bold text-slate-400 mb-2">Gambar Saat Ini:</p>
                                            <img :src="'/storage/' + editState.question_image" class="h-24 rounded-xl border border-slate-200 shadow-sm object-cover opacity-90">
                                            <button type="button" @click="removeCurrentImage()" class="absolute inset-0 bg-rose-900/80 backdrop-blur-[1px] text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition rounded-xl font-bold text-xs gap-2">
                                                <i class="ph-bold ph-trash"></i> Hapus
                                            </button>
                                        </div>
                                    </template>
                                    
                                    <template x-if="deleteImage && !newImagePreview">
                                        <div class="text-xs text-rose-600 font-bold bg-rose-50 p-3 rounded-xl border border-rose-100 flex items-center gap-2">
                                            <i class="ph-bold ph-trash"></i> Gambar akan dihapus saat disimpan.
                                            <button type="button" @click="deleteImage = false" class="text-blue-600 underline ml-auto">Batalkan</button>
                                        </div>
                                    </template>

                                    <div class="flex-1">
                                        <input type="file" 
                                               x-ref="editFileInput"
                                               name="question_image" 
                                               @change="handleEditImage"
                                               accept="image/*"
                                               class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-white file:text-blue-600 hover:file:bg-blue-50 cursor-pointer border border-slate-200 rounded-xl bg-slate-50">
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <label class="block text-xs font-bold text-slate-400 uppercase ml-1">Edit Pilihan</label>
                                <div class="grid grid-cols-1 gap-3">
                                    <?php $__currentLoopData = ['A','B','C','D']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex gap-3 items-center">
                                        <span class="w-10 h-10 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center font-black text-sm"><?php echo e($opt); ?></span>
                                        <input type="text" name="option_<?php echo e($opt); ?>" x-model="editState.option_<?php echo e($opt); ?>" required class="flex-1 rounded-xl border-slate-200 text-sm py-2.5 px-4 focus:ring-blue-500 font-medium">
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Kunci</label>
                                    <div class="relative">
                                        <select name="correct_answer" x-model="editState.correct_answer" required class="w-full rounded-xl border-slate-200 text-sm font-bold text-slate-700 bg-white h-11 appearance-none px-4">
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="C">C</option>
                                            <option value="D">D</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Bobot</label>
                                    <input type="number" name="score_weight" x-model="editState.score_weight" required class="w-full rounded-xl border-slate-200 text-sm font-bold text-center h-11">
                                </div>
                            </div>
                        </div>

                        <div class="bg-white px-8 py-5 flex justify-end gap-3 rounded-b-[2.5rem] border-t border-slate-100 shadow-[0_-10px_40px_rgba(0,0,0,0.05)] relative z-10">
                            <button type="button" @click="showEditModal = false" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition text-sm">
                                Batal
                            </button>
                            <button type="submit" class="px-6 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-500/30 text-sm flex items-center gap-2">
                                <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('.delete-form');
                    Swal.fire({
                        title: 'Hapus Soal Ini?',
                        text: "Soal yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e11d48',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        customClass: { popup: 'rounded-[2rem]' }
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });

            const setupLoading = (formId, text = 'Menyimpan Soal...') => {
                const form = document.getElementById(formId);
                if(form) {
                    form.addEventListener('submit', function() {
                        Swal.fire({
                            title: text,
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading(),
                            customClass: { popup: 'rounded-[2rem]' }
                        });
                    });
                }
            };
            setupLoading('createQuestionForm');
            setupLoading('editQuestionForm', 'Memperbarui Soal...');
            setupLoading('importQuestionForm', 'Mengimport Soal...');

            <?php if(session('success')): ?>
                Swal.fire({
                    icon: 'success', title: 'Berhasil!', text: "<?php echo e(session('success')); ?>",
                    toast: true, position: 'top-end', showConfirmButton: false, timer: 3000,
                    customClass: { popup: 'rounded-xl' }
                });
            <?php endif; ?>
        });

        function viewImage(url) {
            Swal.fire({
                imageUrl: url,
                imageAlt: 'Gambar Soal',
                showConfirmButton: false,
                showCloseButton: true,
                customClass: {
                    popup: 'rounded-[2rem]',
                    image: 'rounded-2xl'
                },
                width: 'auto'
            });
        }
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
<?php endif; ?><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/cbt/manage_questions.blade.php ENDPATH**/ ?>