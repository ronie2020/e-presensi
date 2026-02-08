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
        
        /* Pulse Animation for Low Time Warning */
        @keyframes pulse-red {
            0%, 100% { background-color: rgba(220, 38, 38, 1); box-shadow: 0 0 0 0 rgba(220, 38, 38, 0.7); }
            50% { background-color: rgba(185, 28, 28, 1); box-shadow: 0 0 0 10px rgba(220, 38, 38, 0); }
        }
        .timer-warning {
            animation: pulse-red 1.5s infinite;
            border-color: #fecaca !important;
        }
    </style>

    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    
    <div x-data="examTimer(<?php echo e($assignment->duration_minutes * 60); ?>)" x-init="startTimer()" class="relative">

        
        <div class="fixed top-4 right-4 z-[100] transition-all duration-300 transform"
             :class="timeLeft < 300 ? 'scale-110' : 'scale-100'"> <!-- Membesar jika sisa 5 menit -->
            <div class="flex items-center gap-2 px-5 py-2.5 rounded-full shadow-2xl border-2 backdrop-blur-md transition-colors duration-500"
                 :class="timeLeft < 300 ? 'bg-rose-600 border-rose-400 text-white timer-warning' : 'bg-slate-900/90 border-slate-700 text-white'">
                
                <i class="ph-bold ph-timer text-xl" :class="timeLeft < 300 ? 'text-white' : 'text-yellow-400 animate-pulse'"></i>
                <div class="flex flex-col items-start leading-none">
                    <span class="text-[10px] font-bold opacity-70 uppercase tracking-wider">Sisa Waktu</span>
                    <span class="font-mono text-lg font-black tracking-widest" x-text="formattedTime">00:00:00</span>
                </div>
            </div>
        </div>

        
        <div class="py-6 md:py-10 px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto min-h-screen pb-40 md:pb-32">
            
            
            <div class="animate-enter bg-gray-900 bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900 rounded-[2.5rem] shadow-2xl shadow-blue-900/20 border border-white/10 p-8 md:p-10 mb-8 relative overflow-hidden text-white group">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                
                <div class="relative z-10">
                    <div class="flex flex-wrap items-center gap-3 mb-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-blue-500/10 border border-blue-400/20 text-blue-300 text-[10px] font-black uppercase tracking-widest shadow-sm backdrop-blur-md">
                            <i class="ph-fill ph-monitor-play"></i> Kuis Online
                        </span>
                    </div>
                    
                    <h1 class="text-2xl md:text-4xl font-black text-white mb-3 tracking-tight leading-tight"><?php echo e($assignment->title); ?></h1>
                    <p class="text-slate-400 leading-relaxed max-w-2xl text-sm md:text-base font-medium"><?php echo e($assignment->description); ?></p>
                    
                    <div class="flex flex-wrap items-center gap-4 md:gap-6 mt-8 pt-6 border-t border-white/10">
                        <div class="flex items-center gap-2 text-sm font-bold text-slate-300 bg-white/5 px-4 py-2 rounded-xl border border-white/5">
                            <i class="ph-fill ph-clock text-yellow-400 text-lg"></i> 
                            <span><?php echo e($assignment->duration_minutes); ?> Menit</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm font-bold text-slate-300 bg-white/5 px-4 py-2 rounded-xl border border-white/5">
                            <i class="ph-fill ph-list-numbers text-purple-400 text-lg"></i> 
                            <span><?php echo e($assignment->questions->count()); ?> Soal</span>
                        </div>
                    </div>
                </div>
            </div>

            <form action="<?php echo e(route('students.learning.assignment.quiz.submit', $assignment->id)); ?>" method="POST" id="quizForm">
                <?php echo csrf_field(); ?>
                
                <div class="space-y-6 md:space-y-8">
                    <?php $__currentLoopData = $assignment->questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $q): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="animate-enter bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden relative hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-500 group" style="animation-delay: <?php echo e(($index + 1) * 100); ?>ms">
                            
                            
                            <div class="absolute top-0 left-0 bg-slate-100 text-slate-500 font-black text-xs px-4 py-2 rounded-br-2xl border-r border-b border-slate-200 z-10">
                                NO. <?php echo e($index + 1); ?>

                            </div>
                            
                            <div class="p-6 md:p-8 pt-12 md:pt-8">
                                
                                <div class="text-lg md:text-xl font-bold text-slate-800 mb-8 leading-relaxed pl-2 border-l-4 border-blue-500 rounded-sm">
                                    <?php echo nl2br(e($q->question_text)); ?>

                                </div>

                                <div class="space-y-3">
                                    <?php if($q->question_type == 'multiple_choice'): ?>
                                        <?php $__currentLoopData = ['A', 'B', 'C', 'D', 'E']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php if(isset($q->options[$opt]) && $q->options[$opt]): ?>
                                                <label class="relative flex items-center gap-4 p-4 md:p-5 rounded-2xl border-2 cursor-pointer transition-all duration-200 group/opt select-none bg-slate-50/30 hover:bg-blue-50/30 border-slate-100 hover:border-blue-200 active:scale-[0.99]">
                                                    
                                                    <input type="radio" 
                                                           name="answers[<?php echo e($q->id); ?>]" 
                                                           value="<?php echo e($opt); ?>" 
                                                           class="peer sr-only"
                                                           <?php echo e(old("answers.{$q->id}") == $opt ? 'checked' : ''); ?>>
                                                    
                                                    
                                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-sm shrink-0 transition-all border-2
                                                        bg-white border-slate-200 text-slate-500 
                                                        peer-checked:bg-blue-600 peer-checked:border-blue-600 peer-checked:text-white
                                                        group-hover/opt:border-blue-300 group-hover/opt:text-blue-600">
                                                        <?php echo e($opt); ?>

                                                    </div>
                                                    
                                                    
                                                    <span class="text-base font-medium text-slate-600 peer-checked:text-slate-900 peer-checked:font-bold transition-colors">
                                                        <?php echo e($q->options[$opt]); ?>

                                                    </span>
                                                    
                                                    
                                                    <div class="absolute right-4 text-blue-600 opacity-0 peer-checked:opacity-100 transition-all transform scale-50 peer-checked:scale-100">
                                                        <i class="ph-fill ph-check-circle text-2xl"></i>
                                                    </div>

                                                    
                                                    <div class="absolute inset-0 rounded-2xl ring-2 ring-blue-500 opacity-0 peer-checked:opacity-100 pointer-events-none transition-opacity"></div>
                                                </label>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php else: ?>
                                        
                                        <div class="relative">
                                            <textarea name="answers[<?php echo e($q->id); ?>]" rows="5" 
                                                class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-slate-700 placeholder:text-slate-400 p-4 font-medium transition-all resize-none shadow-inner" 
                                                placeholder="Ketik jawaban uraian Anda di sini..."><?php echo e(old("answers.{$q->id}")); ?></textarea>
                                            <div class="absolute bottom-3 right-3 text-slate-300 pointer-events-none"><i class="ph-bold ph-pencil-simple text-xl"></i></div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                
                <div class="fixed bottom-[80px] md:bottom-0 left-0 right-0 p-4 bg-white/80 backdrop-blur-lg border-t border-slate-200 flex justify-center z-[60] animate-enter" style="animation-delay: 800ms">
                    <div class="w-full max-w-4xl flex justify-end">
                        <button type="button" onclick="confirmSubmit()"
                            class="w-full md:w-auto px-8 py-4 bg-blue-900 text-white font-bold rounded-2xl shadow-xl shadow-blue-900/20 hover:bg-slate-900 hover:-translate-y-1 transition-all flex items-center justify-center gap-3 active:scale-95 group">
                            <span>Kumpulkan Jawaban</span>
                            <div class="bg-white/20 rounded-full p-1 group-hover:translate-x-1 transition-transform">
                                <i class="ph-bold ph-paper-plane-right text-lg"></i> 
                            </div>
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>

    
    <script>
        // ALPINE JS LOGIC UNTUK TIMER
        document.addEventListener('alpine:init', () => {
            Alpine.data('examTimer', (durationInSeconds) => ({
                timeLeft: durationInSeconds,
                formattedTime: '00:00:00',
                
                startTimer() {
                    const interval = setInterval(() => {
                        this.timeLeft--;
                        const h = Math.floor(this.timeLeft / 3600);
                        const m = Math.floor((this.timeLeft % 3600) / 60);
                        const s = this.timeLeft % 60;
                        this.formattedTime = (h < 10 ? "0" + h : h) + ":" + (m < 10 ? "0" + m : m) + ":" + (s < 10 ? "0" + s : s);
                        if (this.timeLeft <= 0) {
                            clearInterval(interval);
                            this.forceSubmit();
                        }
                    }, 1000);
                },

                forceSubmit() {
                    Swal.fire({
                        title: 'WAKTU HABIS!',
                        html: 'Sistem akan mengumpulkan jawaban Anda secara otomatis.',
                        icon: 'warning',
                        timer: 3000,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        didOpen: () => { Swal.showLoading(); }
                    }).then(() => {
                        document.getElementById('quizForm').submit();
                    });
                }
            }));
        });

        // Manual Submit Confirmation
        function confirmSubmit() {
            Swal.fire({
                title: 'Sudah Yakin?',
                text: "Pastikan semua soal sudah terjawab. Jawaban tidak bisa diubah setelah dikumpulkan.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#1e3a8a',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Kumpulkan!',
                cancelButtonText: 'Periksa Lagi',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2rem] font-sans',
                    confirmButton: 'px-6 py-3 rounded-xl font-bold shadow-lg shadow-blue-900/20',
                    cancelButton: 'px-6 py-3 rounded-xl font-bold hover:bg-slate-100 text-slate-600'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Mengirim Jawaban...',
                        html: 'Mohon tunggu sebentar.',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => { Swal.showLoading(); },
                        customClass: { popup: 'rounded-[2rem] font-sans' }
                    });
                    document.getElementById('quizForm').submit();
                }
            });
        }

        // [TAMBAHAN WAJIB] Listener untuk menangkap pesan Error/Success dari Controller
        document.addEventListener('DOMContentLoaded', function() {
            
            // [FIX PENTING] Gunakan json_encode agar karakter aneh tidak merusak JavaScript
            <?php if(session('error')): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Mengirim!',
                    text: <?php echo json_encode(session('error')); ?>, // Perbaikan disini
                    confirmButtonText: 'Coba Lagi',
                    confirmButtonColor: '#e11d48',
                    customClass: { popup: 'rounded-[2rem] font-sans' }
                });
            <?php endif; ?>

            // Jika ada pesan Sukses
            <?php if(session('success')): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: <?php echo json_encode(session('success')); ?>, // Perbaikan disini
                    confirmButtonText: 'Lanjut',
                    confirmButtonColor: '#1e3a8a',
                    customClass: { popup: 'rounded-[2rem] font-sans' }
                }).then(() => {
                    window.location.href = "<?php echo e(route('students.learning.index')); ?>";
                });
            <?php endif; ?>
        });
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\students\lms\quiz.blade.php ENDPATH**/ ?>