<?php if (isset($component)) { $__componentOriginal5acda7f50fc1fb55f4bf1672ea512a11 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5acda7f50fc1fb55f4bf1672ea512a11 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.student-learning-layout','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('student-learning-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        /* Hide scrollbar for tabs */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div x-data="{ activeTab: 'materi' }" class="min-h-screen bg-slate-50/50 pb-20">
        
        
        <div class="animate-enter relative bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 pb-24 pt-12 px-4 sm:px-6 lg:px-8 overflow-hidden rounded-b-[3rem] shadow-2xl shadow-blue-900/20">
            <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
            <div class="absolute top-0 right-0 w-80 h-80 bg-blue-500/10 rounded-full blur-[100px] -mr-20 -mt-20 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-indigo-500/10 rounded-full blur-[80px] -ml-10 -mb-10 pointer-events-none"></div>
            
            <div class="relative max-w-6xl mx-auto z-10">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    <div class="flex-1">
                        
                        <div class="flex items-center gap-4 mb-4">
                            <a href="<?php echo e(route('students.learning.index')); ?>" class="group flex items-center justify-center w-10 h-10 rounded-xl bg-white/10 backdrop-blur-md border border-white/10 text-slate-300 hover:bg-white/20 hover:text-white hover:border-white/30 transition-all active:scale-95 shrink-0 shadow-sm" title="Kembali">
                                <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-0.5 transition-transform"></i>
                            </a>
                            <span class="px-3 py-1 rounded-full bg-blue-500/10 border border-blue-400/20 text-blue-300 text-[10px] font-black uppercase tracking-widest backdrop-blur-md flex items-center gap-1.5">
                                <i class="ph-fill ph-student"></i>
                                <?php echo e(Auth::guard('student')->user()->schoolClass->name ?? 'Kelas Umum'); ?>

                            </span>
                        </div>

                        <h1 class="text-3xl md:text-5xl font-black text-white tracking-tight leading-tight mb-2">
                            <?php echo e($subject->name); ?>

                        </h1>
                        <p class="text-slate-400 text-sm md:text-base max-w-xl leading-relaxed font-medium">
                            Selamat belajar! Akses materi dan kerjakan tugas yang tersedia di bawah ini.
                        </p>
                    </div>
                    
                    
                    <div class="flex gap-3 w-full md:w-auto overflow-x-auto no-scrollbar pb-2 md:pb-0">
                        <div class="bg-white/5 backdrop-blur-md border border-white/10 p-4 rounded-2xl text-center min-w-[100px] shadow-lg flex-1 md:flex-none">
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Materi</p>
                            <p class="text-2xl font-black text-blue-300"><?php echo e($materials->count()); ?></p>
                        </div>
                        <div class="bg-white/5 backdrop-blur-md border border-white/10 p-4 rounded-2xl text-center min-w-[100px] shadow-lg flex-1 md:flex-none">
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Tugas</p>
                            <p class="text-2xl font-black text-yellow-400"><?php echo e($assignments->count()); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 relative z-20">
            
            
            <?php if(session('success')): ?>
                <div class="animate-enter mb-6 bg-emerald-50 border border-emerald-100 text-emerald-800 px-5 py-4 rounded-[1.5rem] flex items-center gap-3 shadow-lg shadow-emerald-900/5">
                    <div class="bg-emerald-100 p-2 rounded-xl text-emerald-600"><i class="ph-fill ph-check-circle text-xl"></i></div>
                    <span class="font-bold text-sm"><?php echo e(session('success')); ?></span>
                </div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="animate-enter mb-6 bg-rose-50 border border-rose-100 text-rose-800 px-5 py-4 rounded-[1.5rem] flex items-center gap-3 shadow-lg shadow-rose-900/5">
                    <div class="bg-rose-100 p-2 rounded-xl text-rose-600"><i class="ph-fill ph-warning-circle text-xl"></i></div>
                    <span class="font-bold text-sm"><?php echo e(session('error')); ?></span>
                </div>
            <?php endif; ?>

            
            <div class="bg-white p-2 rounded-[1.5rem] shadow-xl shadow-slate-200/50 border border-white flex gap-2 mb-8 overflow-x-auto no-scrollbar animate-enter">
                <button @click="activeTab = 'materi'" 
                        :class="activeTab === 'materi' 
                            ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/20' 
                            : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'"
                        class="flex-1 min-w-[140px] py-3.5 rounded-xl text-sm font-bold transition-all duration-300 flex items-center justify-center gap-2.5">
                    <i class="ph-bold ph-book-open-text text-lg" :class="activeTab === 'materi' ? 'text-blue-400' : ''"></i>
                    Materi Belajar
                </button>
                
                <button @click="activeTab = 'tugas'" 
                        :class="activeTab === 'tugas' 
                            ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' 
                            : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'"
                        class="flex-1 min-w-[140px] py-3.5 rounded-xl text-sm font-bold transition-all duration-300 flex items-center justify-center gap-2.5 relative">
                    <i class="ph-bold ph-pencil-simple-line text-lg" :class="activeTab === 'tugas' ? 'text-yellow-300' : ''"></i>
                    Tugas & PR
                    <?php if($assignments->where('deadline', '>', now())->count() > 0): ?>
                        <span class="absolute top-2 right-2 flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-rose-500 border border-white"></span>
                        </span>
                    <?php endif; ?>
                </button>
            </div>

            
            <div x-show="activeTab === 'materi'" class="space-y-6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <?php if($materials->isEmpty()): ?>
                    <div class="animate-enter text-center py-20 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200 group hover:border-blue-300 transition-colors">
                        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300 group-hover:text-blue-500 group-hover:bg-blue-50 transition-all duration-500 transform group-hover:scale-110">
                            <i class="ph-duotone ph-folder-notch-open text-5xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800">Belum Ada Materi</h3>
                        <p class="text-slate-500 font-medium mt-1">Guru belum mengunggah materi pelajaran.</p>
                    </div>
                <?php else: ?>
                    <?php $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="animate-enter bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden hover:shadow-xl hover:shadow-blue-900/5 transition-all duration-300 group" style="animation-delay: <?php echo e(($index + 1) * 100); ?>ms">
                            <div class="p-6 md:p-8 border-b border-slate-50 bg-gradient-to-r from-white to-slate-50/50">
                                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 border border-blue-100 shadow-sm group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                                            <i class="ph-duotone ph-book-bookmark text-2xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-xl text-slate-900 leading-tight group-hover:text-blue-800 transition-colors"><?php echo e($item->title); ?></h3>
                                            <div class="flex flex-wrap items-center gap-3 mt-2 text-xs font-bold text-slate-400">
                                                <span class="flex items-center gap-1 bg-slate-100 px-2 py-1 rounded-md text-slate-600 border border-slate-200">
                                                    <i class="ph-fill ph-calendar-blank"></i> <?php echo e($item->created_at->format('d M Y')); ?>

                                                </span>
                                                <span class="flex items-center gap-1">
                                                    <i class="ph-fill ph-clock"></i> <?php echo e($item->created_at->format('H:i')); ?>

                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="self-start bg-blue-50 text-blue-700 text-[10px] font-black px-3 py-1.5 rounded-lg uppercase tracking-widest border border-blue-100">
                                        Materi Pembelajaran
                                    </span>
                                </div>
                            </div>
                            <div class="p-6 md:p-8">
                                <?php if($item->resume): ?>
                                    <div class="prose prose-sm max-w-none text-slate-600 mb-8 bg-slate-50/80 p-6 rounded-2xl border border-slate-100/80 leading-relaxed relative">
                                        <div class="absolute -top-3 left-6 bg-white px-3 py-1 rounded-lg border border-slate-100 shadow-sm text-[10px] font-bold text-blue-600 uppercase tracking-widest flex items-center gap-1">
                                            <i class="ph-bold ph-info"></i> Ringkasan
                                        </div>
                                        <?php echo nl2br(e($item->resume)); ?>

                                    </div>
                                <?php elseif($item->description): ?>
                                    <p class="text-slate-600 mb-8 italic pl-4 border-l-4 border-slate-200 py-2"><?php echo e($item->description); ?></p>
                                <?php endif; ?>
                                <?php if($item->attachments->count() > 0): ?>
                                    <h4 class="font-black text-slate-800 text-xs uppercase tracking-widest mb-4 flex items-center gap-2">
                                        <i class="ph-fill ph-paperclip text-lg text-blue-500"></i> Lampiran & File
                                    </h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <?php $__currentLoopData = $item->attachments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $att): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="flex items-center gap-4 p-4 rounded-2xl border border-slate-200 bg-white hover:border-blue-400 hover:shadow-lg hover:shadow-blue-900/5 transition-all group/file cursor-pointer relative overflow-hidden"
                                                 onclick="window.open('<?php echo e($att->file_type == 'file' ? asset('storage/'.$att->file_path) : $att->file_path); ?>', '_blank')">
                                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0 shadow-sm transition-all duration-300
                                                    <?php echo e($att->file_type == 'file' ? 'bg-orange-50 text-orange-600 group-hover/file:bg-orange-600 group-hover/file:text-white' : ($att->file_type == 'video' ? 'bg-rose-50 text-rose-600 group-hover/file:bg-rose-600 group-hover/file:text-white' : 'bg-blue-50 text-blue-600 group-hover/file:bg-blue-600 group-hover/file:text-white')); ?>">
                                                    <?php if($att->file_type == 'file'): ?> <i class="ph-duotone ph-file-pdf text-3xl"></i>
                                                    <?php elseif($att->file_type == 'video'): ?> <i class="ph-duotone ph-youtube-logo text-3xl"></i>
                                                    <?php else: ?> <i class="ph-duotone ph-link text-3xl"></i> <?php endif; ?>
                                                </div>
                                                <div class="flex-1 min-w-0 z-10">
                                                    <p class="text-sm font-bold text-slate-700 truncate group-hover/file:text-blue-700 transition-colors">
                                                        <?php echo e($att->file_name ?? 'Lampiran Materi'); ?>

                                                    </p>
                                                    <p class="text-[10px] text-slate-400 uppercase font-black tracking-wider mt-1 flex items-center gap-1">
                                                        <?php echo e($att->file_type); ?> <i class="ph-bold ph-arrow-up-right text-xs opacity-0 group-hover/file:opacity-100 transition-opacity"></i>
                                                    </p>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </div>

            
            <div x-show="activeTab === 'tugas'" class="space-y-6" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <?php if($assignments->isEmpty()): ?>
                    <div class="animate-enter text-center py-20 bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200">
                        <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                            <i class="ph-duotone ph-confetti text-5xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800">Tidak Ada Tugas</h3>
                        <p class="text-slate-500 font-medium mt-1">Hore! Kamu bebas dari tugas untuk saat ini.</p>
                    </div>
                <?php else: ?>
                    <?php $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $mySubmission = $task->submissions->first(); 
                            $isLate = now() > $task->deadline;
                            $deadlineFormatted = $task->deadline->translatedFormat('l, d F Y • H:i');
                            
                            $typeIcon = 'ph-clipboard-text';
                            $typeColor = 'text-indigo-600 bg-indigo-50 border-indigo-100';
                            $typeLabel = 'Tugas File';
                            
                            if($task->assignment_type == 'quiz') {
                                $typeIcon = 'ph-list-checks';
                                $typeColor = 'text-purple-600 bg-purple-50 border-purple-100';
                                $typeLabel = 'Kuis Online';
                            } elseif($task->assignment_type == 'link') {
                                $typeIcon = 'ph-link';
                                $typeColor = 'text-amber-600 bg-amber-50 border-amber-100';
                                $typeLabel = 'Tugas Link';
                            }
                        ?>

                        <div class="animate-enter bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 group hover:-translate-y-1" style="animation-delay: <?php echo e(($index + 1) * 100); ?>ms" x-data="{ openUpload: false }">
                            
                            <div class="p-6 md:p-8 flex flex-col md:flex-row md:items-start justify-between gap-6 relative">
                                
                                <div class="absolute left-0 top-8 bottom-8 w-1.5 rounded-r-full <?php echo e($mySubmission ? 'bg-emerald-500' : ($isLate ? 'bg-rose-500' : 'bg-slate-300')); ?>"></div>

                                <div class="flex items-start gap-5 pl-4">
                                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0 <?php echo e($typeColor); ?> border shadow-sm">
                                        <i class="ph-duotone <?php echo e($typeIcon); ?> text-3xl"></i>
                                    </div>
                                    <div>
                                        <div class="flex flex-wrap items-center gap-2 mb-2">
                                            <span class="text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded-md bg-slate-100 text-slate-500 border border-slate-200"><?php echo e($typeLabel); ?></span>
                                            <?php if($isLate): ?>
                                                <span class="bg-rose-100 text-rose-700 px-2 py-1 rounded-md text-[10px] font-black uppercase tracking-widest border border-rose-200 flex items-center gap-1">
                                                    <i class="ph-fill ph-warning-circle"></i> Terlewat
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <h3 class="font-bold text-slate-900 text-xl group-hover:text-blue-700 transition-colors leading-tight mb-2"><?php echo e($task->title); ?></h3>
                                        <div class="flex items-center gap-4 text-xs font-bold text-slate-500">
                                            <span class="flex items-center gap-1.5 <?php echo e($isLate ? 'text-rose-600' : ''); ?>">
                                                <i class="ph-fill ph-clock"></i> Deadline: <?php echo e($deadlineFormatted); ?>

                                            </span>
                                        </div>
                                    </div>
                                </div>

                                
                                <div class="shrink-0 pl-4 md:pl-0 border-l-2 md:border-l-0 border-slate-100 md:text-right">
                                    <?php if($mySubmission): ?>
                                        <?php if(isset($mySubmission->grade)): ?>
                                            <div class="flex flex-col md:items-end">
                                                <span class="text-[10px] uppercase font-black text-slate-400 mb-1 tracking-wider">Nilai Kamu</span>
                                                <div class="px-6 py-2 bg-slate-900 text-yellow-400 rounded-xl text-2xl font-black shadow-lg shadow-slate-900/20 border border-slate-800">
                                                    <?php echo e($mySubmission->grade); ?>

                                                </div>
                                            </div>
                                        <?php else: ?>
                                            <div class="inline-flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 rounded-xl border border-blue-100 shadow-sm">
                                                <div class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></div>
                                                <span class="text-xs font-bold uppercase tracking-wide">Menunggu Dinilai</span>
                                            </div>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <?php if($isLate && !$task->allow_late_submission): ?>
                                            <div class="px-5 py-2.5 bg-slate-100 text-slate-500 rounded-xl font-bold text-xs uppercase border border-slate-200">
                                                <i class="ph-fill ph-lock-key"></i> Ditutup
                                            </div>
                                        <?php else: ?>
                                            <div class="px-5 py-2.5 bg-amber-50 text-amber-700 rounded-xl border border-amber-100 font-bold text-xs uppercase flex items-center gap-2 shadow-sm">
                                                <i class="ph-fill ph-warning-circle"></i> Belum Dikerjakan
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="px-6 md:px-8 pb-8">
                                <div class="prose prose-sm max-w-none text-slate-600 bg-slate-50 p-6 rounded-2xl border border-slate-100 mb-6 leading-relaxed">
                                    <?php echo e($task->description); ?>

                                </div>

                                
                                <?php if(!$mySubmission): ?>
                                    <?php if($isLate && !$task->allow_late_submission): ?>
                                        
                                    <?php else: ?>
                                        
                                        <?php if($task->assignment_type == 'quiz'): ?>
                                            <a href="<?php echo e(route('students.learning.assignment.quiz', $task->id)); ?>" class="w-full py-4 bg-purple-600 text-white font-bold rounded-2xl hover:bg-purple-700 transition shadow-lg shadow-purple-200 flex items-center justify-center gap-2 group/btn relative overflow-hidden active:scale-[0.98]">
                                                <div class="absolute inset-0 bg-white/10 translate-y-full group-hover/btn:translate-y-0 transition-transform duration-300"></div>
                                                <i class="ph-bold ph-play-circle text-xl"></i>
                                                <span class="relative">Mulai Kerjakan Kuis</span>
                                            </a>

                                        
                                        <?php elseif($task->assignment_type == 'link'): ?>
                                            <div class="flex flex-col sm:flex-row gap-4">
                                                <a href="<?php echo e($task->link_url); ?>" target="_blank" class="flex-1 py-3.5 bg-amber-50 text-amber-700 border border-amber-200 font-bold rounded-2xl hover:bg-amber-100 transition flex items-center justify-center gap-2 active:scale-[0.98]">
                                                    <i class="ph-bold ph-arrow-square-out text-lg"></i> Buka Link Soal
                                                </a>
                                                <form action="<?php echo e(route('students.learning.assignment.submit', $task->id)); ?>" method="POST" class="flex-1" id="form-complete-<?php echo e($task->id); ?>">
                                                    <?php echo csrf_field(); ?>
                                                    <button type="button" onclick="confirmTaskSubmit('<?php echo e($task->id); ?>')" class="w-full py-3.5 bg-emerald-600 text-white font-bold rounded-2xl hover:bg-emerald-700 transition shadow-lg shadow-emerald-200 flex items-center justify-center gap-2 active:scale-[0.98]">
                                                        <i class="ph-bold ph-check-circle text-lg"></i> Tandai Selesai
                                                    </button>
                                                </form>
                                            </div>

                                        
                                        <?php else: ?>
                                            <button @click="openUpload = !openUpload" class="w-full py-4 bg-slate-900 text-white font-bold rounded-2xl hover:bg-blue-600 transition shadow-lg shadow-slate-900/20 flex items-center justify-center gap-2 group/btn active:scale-[0.98]">
                                                <i class="ph-bold ph-upload-simple text-xl text-yellow-400 group-hover/btn:-translate-y-1 transition-transform"></i>
                                                <span x-text="openUpload ? 'Batal Upload' : 'Kerjakan & Upload File'"></span>
                                            </button>
                                            
                                            <div x-show="openUpload" x-transition class="mt-6 p-6 md:p-8 border-2 border-dashed border-blue-200 rounded-[2rem] bg-blue-50/50">
                                                <h4 class="font-black text-blue-900 mb-6 flex items-center gap-2 text-sm uppercase tracking-widest"><i class="ph-fill ph-cloud-arrow-up text-lg"></i> Form Pengumpulan</h4>
                                                <form action="<?php echo e(route('students.learning.assignment.submit', $task->id)); ?>" method="POST" enctype="multipart/form-data">
                                                    <?php echo csrf_field(); ?>
                                                    <div class="space-y-5">
                                                        <div>
                                                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">File Jawaban (PDF/JPG)</label>
                                                            <input type="file" name="file" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-900 file:text-white hover:file:bg-blue-800 border border-slate-200 rounded-2xl bg-white shadow-sm transition cursor-pointer">
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2 ml-1">Catatan (Opsional)</label>
                                                            <textarea name="student_note" rows="3" class="w-full rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm p-4 placeholder:text-slate-400 transition-shadow" placeholder="Tulis pesan untuk guru di sini..."></textarea>
                                                        </div>
                                                        <button type="submit" class="w-full py-4 bg-emerald-600 text-white font-bold rounded-2xl hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition flex items-center justify-center gap-2 active:scale-[0.98]">
                                                            <i class="ph-bold ph-paper-plane-right text-lg"></i> Kirim Jawaban
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                <?php else: ?>
                                    
                                    <div class="bg-emerald-50/50 border border-emerald-100 rounded-2xl p-6">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                            <div class="flex items-center gap-4">
                                                <div class="bg-emerald-100 text-emerald-600 p-3 rounded-xl shadow-sm">
                                                    <i class="ph-fill ph-check-fat text-2xl"></i>
                                                </div>
                                                <div>
                                                    <p class="font-bold text-emerald-900">Tugas Berhasil Terkirim</p>
                                                    <p class="text-xs text-emerald-600 font-medium mt-0.5">Dikirim pada <?php echo e($mySubmission->submitted_at->translatedFormat('d F Y • H:i')); ?></p>
                                                </div>
                                            </div>
                                            <?php if(!$mySubmission->grade && $task->assignment_type == 'file_upload'): ?>
                                                <button @click="openUpload = !openUpload" class="text-xs font-bold text-blue-600 bg-white border border-blue-100 px-4 py-2 rounded-xl hover:bg-blue-50 shadow-sm flex items-center gap-2 transition-all">
                                                    <i class="ph-bold ph-pencil-simple"></i> Edit Jawaban
                                                </button>
                                            <?php endif; ?>
                                        </div>

                                        <?php if($mySubmission->teacher_feedback): ?>
                                            <div class="mt-4 bg-white p-5 rounded-2xl border border-emerald-100 shadow-sm relative">
                                                <div class="absolute -top-2 left-8 w-4 h-4 bg-white border-t border-l border-emerald-100 transform rotate-45"></div>
                                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 flex items-center gap-1">
                                                    <i class="ph-bold ph-chat-text"></i> Catatan Guru
                                                </p>
                                                <p class="text-slate-700 text-sm leading-relaxed">"<?php echo e($mySubmission->teacher_feedback); ?>"</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>
            </div>

        </div>
    </div>

    
    <script>
        function confirmTaskSubmit(taskId) {
            Swal.fire({
                title: 'Tandai Selesai?',
                text: "Pastikan Anda sudah mengerjakan soal di link yang tersedia sebelum menandai ini selesai.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#059669', // emerald-600
                cancelButtonColor: '#64748b', // slate-500
                confirmButtonText: 'Ya, Tandai Selesai',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2rem] font-sans',
                    confirmButton: 'px-6 py-3 rounded-xl font-bold shadow-lg shadow-emerald-600/20',
                    cancelButton: 'px-6 py-3 rounded-xl font-bold hover:bg-slate-100 text-slate-600'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-complete-' + taskId).submit();
                }
            });
        }
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5acda7f50fc1fb55f4bf1672ea512a11)): ?>
<?php $attributes = $__attributesOriginal5acda7f50fc1fb55f4bf1672ea512a11; ?>
<?php unset($__attributesOriginal5acda7f50fc1fb55f4bf1672ea512a11); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5acda7f50fc1fb55f4bf1672ea512a11)): ?>
<?php $component = $__componentOriginal5acda7f50fc1fb55f4bf1672ea512a11; ?>
<?php unset($__componentOriginal5acda7f50fc1fb55f4bf1672ea512a11); ?>
<?php endif; ?><?php /**PATH E:\drive aplikasi\aplikasi terpadu\sistem_absensi_sekolah versi 3.00\resources\views/students/lms/show.blade.php ENDPATH**/ ?>