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
    
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans text-slate-700 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            
            <div class="relative rounded-[2rem] bg-elevate-gradient-main p-8 mb-8 text-elevate-dark shadow-xl shadow-elevate-accent/10 overflow-hidden border border-white/60">
                <div class="absolute -top-10 -left-10 w-48 h-48 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/20 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>
                <div class="absolute top-10 right-32 w-24 h-24 bg-white/40 rounded-2xl rotate-45 pointer-events-none shadow-sm"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-center md:text-left">
                        <div class="flex items-center justify-center md:justify-start gap-2 mb-2">
                            <span class="text-[10px] font-bold text-elevate-dark/70 uppercase tracking-wider bg-white/50 px-3 py-1 rounded-full border border-white/60 backdrop-blur-sm shadow-sm">Administrasi Sekolah</span>
                        </div>
                        <h1 class="text-3xl font-extrabold tracking-tight mb-2 flex items-center justify-center md:justify-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-elevate-accent/20 text-elevate-primary flex items-center justify-center shrink-0">
                                <i class="ph-bold ph-tray-arrow-down text-xl"></i>
                            </div>
                            Arsip Surat Masuk
                        </h1>
                        <p class="text-elevate-dark/80 text-sm font-medium leading-relaxed max-w-lg ml-0 md:ml-12">
                            Kelola dokumen persuratan masuk. Integrasikan surat masuk dengan pembuatan Surat Perintah Tugas (SPT) secara otomatis.
                        </p>
                        
                        <div class="mt-6 flex flex-wrap justify-center md:justify-start gap-3 ml-0 md:ml-12">
                            <a href="<?php echo e(route('letters.incoming.create')); ?>" class="group bg-white text-elevate-dark px-5 py-3 rounded-2xl font-bold text-sm transition-all hover:bg-slate-50 flex items-center gap-2 shadow-lg shadow-elevate-dark/5 border border-white active:scale-95">
                                <div class="w-7 h-7 rounded-full bg-elevate-accent/20 text-elevate-primary flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <i class="ph-bold ph-plus text-sm"></i>
                                </div>
                                <span>Catat Surat Masuk</span>
                            </a>
                        </div>
                    </div>
                    
                    
                    <div class="flex gap-3">
                        <div class="bg-white/60 backdrop-blur-md px-6 py-5 rounded-[2rem] border border-white shadow-sm text-center min-w-[140px]">
                            <span class="block text-4xl font-black text-elevate-dark mb-1"><?php echo e($letters->total()); ?></span>
                            <span class="text-[10px] uppercase font-bold text-elevate-primary tracking-wider">Total Masuk</span>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                
                
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col xl:flex-row gap-4 justify-between items-center">
                    <h3 class="font-black text-elevate-dark text-lg flex items-center gap-2 whitespace-nowrap">
                        <i class="ph-fill ph-list-dashes text-elevate-primary"></i> Data Surat Masuk
                    </h3>
                    
                    <form action="<?php echo e(route('letters.incoming.index')); ?>" method="GET" class="flex flex-col sm:flex-row gap-3 w-full xl:w-auto">
                        <div class="relative w-full sm:w-64">
                            <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Cari nomor, perihal, pengirim..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm font-medium text-slate-700 transition-all">
                            <i class="ph-bold ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                        </div>
                        <div class="relative w-full sm:w-40">
                            <select name="sifat_surat" onchange="this.form.submit()" class="w-full pl-4 pr-10 py-2.5 rounded-xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm font-medium text-slate-700 appearance-none transition-all cursor-pointer">
                                <option value="">Semua Sifat</option>
                                <option value="Biasa" <?php echo e(request('sifat_surat') == 'Biasa' ? 'selected' : ''); ?>>Biasa</option>
                                <option value="Penting" <?php echo e(request('sifat_surat') == 'Penting' ? 'selected' : ''); ?>>Penting</option>
                                <option value="Segera" <?php echo e(request('sifat_surat') == 'Segera' ? 'selected' : ''); ?>>Segera</option>
                                <option value="Rahasia" <?php echo e(request('sifat_surat') == 'Rahasia' ? 'selected' : ''); ?>>Rahasia</option>
                            </select>
                            <i class="ph-bold ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                        </div>
                        <?php if(request('search') || request('sifat_surat')): ?>
                            <a href="<?php echo e(route('letters.incoming.index')); ?>" class="px-4 py-2.5 bg-slate-100 text-slate-500 hover:bg-rose-50 hover:text-rose-600 rounded-xl text-sm font-bold transition-colors flex items-center justify-center gap-2" title="Reset Filter">
                                <i class="ph-bold ph-x"></i>
                            </a>
                        <?php endif; ?>
                    </form>
                </div>

                
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50/80 text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-5 w-48">Agenda & Diterima</th>
                                <th class="px-6 py-5">Identitas Surat</th>
                                <th class="px-6 py-5 w-1/3">Asal & Perihal</th>
                                <th class="px-6 py-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php $__empty_1 = true; $__currentLoopData = $letters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $letter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-6 py-5 align-top">
                                    <div class="font-mono font-black text-elevate-primary bg-elevate-accent/10 px-3 py-1.5 rounded-lg border border-elevate-accent/20 inline-block text-sm mb-2 shadow-sm">
                                        #<?php echo e($letter->nomor_agenda); ?>

                                    </div>
                                    <div class="text-xs text-slate-500 font-medium flex items-center gap-1.5 mt-1" title="Tanggal Diterima">
                                        <i class="ph-bold ph-calendar-check text-elevate-primary"></i> <?php echo e(\Carbon\Carbon::parse($letter->tgl_diterima)->translatedFormat('d M Y')); ?>

                                    </div>
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <div class="font-bold text-elevate-dark text-sm mb-2 leading-snug"><?php echo e($letter->nomor_surat); ?></div>
                                    <span class="inline-flex px-2 py-1 rounded border border-slate-200 bg-slate-50 text-[10px] font-bold text-slate-600 tracking-wider">
                                        <?php echo e($letter->sifat_surat); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <div class="flex items-start gap-2 mb-2">
                                        <i class="ph-fill ph-buildings text-elevate-primary mt-0.5"></i>
                                        <span class="font-bold text-elevate-dark text-sm"><?php echo e($letter->asal_surat); ?></span>
                                    </div>
                                    <p class="text-sm text-slate-600 leading-relaxed line-clamp-2 font-medium">
                                        <?php echo e($letter->perihal); ?>

                                    </p>
                                </td>
                                <td class="px-6 py-5 align-top text-right">
                                    <div class="flex flex-col items-end gap-2">
                                        <?php if($letter->file_path): ?>
                                            <a href="<?php echo e(asset('storage/' . $letter->file_path)); ?>" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-elevate-accent/10 border border-elevate-accent/20 text-elevate-primary hover:bg-elevate-primary hover:text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                                                <i class="ph-bold ph-download-simple text-base"></i> Lampiran
                                            </a>
                                        <?php else: ?>
                                            <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-50 text-slate-400 rounded-lg text-xs font-medium border border-slate-100">
                                                <i class="ph-bold ph-file-dashed"></i> No File
                                            </span>
                                        <?php endif; ?>

                                        
                                        <div class="flex items-center gap-2 mt-1">
                                            <button type="button" onclick="showDetailModal(<?php echo e(json_encode($letter)); ?>)" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-sky-600 hover:border-sky-200 hover:bg-sky-50 hover:shadow-sm transition-all" title="Lihat Detail">
                                                <i class="ph-bold ph-eye text-lg"></i>
                                            </button>
                                            <a href="<?php echo e(route('letters.incoming.edit', $letter->id)); ?>" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-amber-600 hover:border-amber-200 hover:bg-amber-50 hover:shadow-sm transition-all" title="Edit">
                                                <i class="ph-bold ph-pencil-simple text-lg"></i>
                                            </a>
                                            <button type="button" onclick="confirmDelete('<?php echo e($letter->id); ?>')" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 hover:shadow-sm transition-all" title="Hapus">
                                                <i class="ph-bold ph-trash text-lg"></i>
                                            </button>
                                        </div>

                                        <form id="delete-form-<?php echo e($letter->id); ?>" action="<?php echo e(route('letters.incoming.destroy', $letter->id)); ?>" method="POST" class="hidden">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-20 text-center">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                        <i class="ph-duotone ph-magnifying-glass text-4xl"></i>
                                    </div>
                                    <h3 class="text-elevate-dark font-bold text-lg">Data Tidak Ditemukan</h3>
                                    <p class="text-slate-500 text-sm mt-1 mb-6">Belum ada surat masuk atau hasil pencarian Anda tidak cocok.</p>
                                    <a href="<?php echo e(route('letters.incoming.create')); ?>" class="inline-flex items-center gap-2 px-6 py-3.5 bg-elevate-dark text-white rounded-xl font-bold text-sm hover:bg-elevate-primary transition-colors shadow-lg shadow-elevate-dark/20">
                                        <i class="ph-bold ph-plus"></i> Catat Surat Baru
                                    </a>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                
                <div class="p-6 border-t border-slate-100 bg-slate-50/50">
                    <?php echo e($letters->links()); ?>

                </div>
            </div>
        </div>
    </div>

    
    <div id="detailModal" class="fixed inset-0 z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity opacity-0" id="modalBackdrop" onclick="closeDetailModal()"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto custom-scrollbar">
            
            <div class="flex min-h-full items-start justify-center p-4 py-16 sm:p-6 sm:py-24 text-center">
                <div id="modalPanel" class="relative transform overflow-hidden rounded-[2.5rem] bg-white text-left shadow-2xl transition-all w-full max-w-2xl border border-slate-100 opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95 duration-300">
                    
                    
                    <div class="bg-gradient-to-r from-elevate-dark to-elevate-primary p-6 text-white relative overflow-hidden">
                        <div class="absolute -right-6 -top-6 text-white/5 text-9xl pointer-events-none">
                            <i class="ph-fill ph-tray-arrow-down"></i>
                        </div>
                        <div class="flex justify-between items-center relative z-10">
                            <div>
                                <h3 class="text-xl font-black flex items-center gap-2">
                                    <i class="ph-duotone ph-info text-elevate-accent"></i> Detail Surat Masuk
                                </h3>
                                <p class="text-elevate-accent text-sm font-medium mt-1">
                                    Agenda: <span id="modal_agenda" class="font-mono bg-white/10 px-2 rounded font-bold"></span>
                                </p>
                            </div>
                            <button onclick="closeDetailModal()" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors">
                                <i class="ph-bold ph-x text-lg"></i>
                            </button>
                        </div>
                    </div>

                    
                    <div class="p-8">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nomor Surat</span>
                                <span id="modal_nomor" class="font-bold text-elevate-dark text-sm"></span>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Asal / Pengirim</span>
                                <span id="modal_asal" class="font-bold text-elevate-dark text-sm"></span>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tgl Surat Dibuat</span>
                                <span id="modal_tanggal_surat" class="font-bold text-elevate-dark text-sm"></span>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tgl Diterima</span>
                                <span id="modal_tanggal_diterima" class="font-bold text-elevate-dark text-sm text-elevate-primary"></span>
                            </div>
                        </div>
                        
                        <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100 mb-6 relative">
                            <div class="absolute top-4 right-4">
                                <span id="modal_sifat" class="inline-flex px-2.5 py-1 rounded-md border border-slate-200 bg-white text-[10px] font-bold text-slate-600"></span>
                            </div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Perihal / Isi Surat</span>
                            <p id="modal_perihal" class="text-sm font-medium text-slate-700 leading-relaxed pr-16"></p>
                        </div>

                        
                        <div class="pt-6 border-t border-slate-100 flex items-center justify-between">
                            <div id="modal_lampiran_container">
                                <!-- Tombol Lampiran diinject via JS -->
                            </div>
                            <button onclick="closeDetailModal()" class="px-6 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-200 transition-colors">
                                Tutup Panel
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    
    <script>
        <?php if(session('success')): ?>
            const Toast = Swal.mixin({
                toast: true, position: 'top-end', showConfirmButton: false, timer: 3000,
                timerProgressBar: true, customClass: { popup: 'rounded-[1.5rem] font-sans' }
            });
            Toast.fire({ icon: 'success', title: '<?php echo e(session('success')); ?>' });
        <?php endif; ?>

        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Surat Masuk?', 
                text: "Data surat beserta lampirannya akan dihapus secara permanen.",
                icon: 'warning', 
                showCancelButton: true,
                confirmButtonColor: '#e11d48', 
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Hapus!', 
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2.5rem] font-sans border-0 shadow-2xl',
                    confirmButton: 'bg-rose-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-2 shadow-lg shadow-rose-900/20',
                    cancelButton: 'bg-slate-100 text-slate-600 px-6 py-3 rounded-xl font-bold hover:bg-slate-200 transition-colors mx-2'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        // Fungsi JavaScript untuk Modal Detail
        function showDetailModal(letter) {
            const modal = document.getElementById('detailModal');
            const backdrop = document.getElementById('modalBackdrop');
            const panel = document.getElementById('modalPanel');

            // Format Tanggal
            const options = { day: 'numeric', month: 'long', year: 'numeric' };
            const dateSurat = new Date(letter.tgl_surat).toLocaleDateString('id-ID', options);
            const dateDiterima = new Date(letter.tgl_diterima).toLocaleDateString('id-ID', options);

            // Set Data
            document.getElementById('modal_agenda').innerText = '#' + letter.nomor_agenda;
            document.getElementById('modal_nomor').innerText = letter.nomor_surat;
            document.getElementById('modal_asal').innerText = letter.asal_surat;
            document.getElementById('modal_tanggal_surat').innerText = dateSurat;
            document.getElementById('modal_tanggal_diterima').innerText = dateDiterima;
            document.getElementById('modal_sifat').innerText = letter.sifat_surat;
            document.getElementById('modal_perihal').innerText = letter.perihal;

            // Set Link Lampiran Jika Ada
            const lampiranContainer = document.getElementById('modal_lampiran_container');
            if (letter.file_path) {
                const fileUrl = '<?php echo e(asset("storage/")); ?>/' + letter.file_path;
                lampiranContainer.innerHTML = `
                    <a href="${fileUrl}" target="_blank" class="inline-flex items-center gap-2 px-5 py-3 bg-elevate-accent/10 text-elevate-primary rounded-xl font-bold text-sm hover:bg-elevate-primary hover:text-white transition-all shadow-sm">
                        <i class="ph-bold ph-download-simple"></i> Unduh Lampiran
                    </a>
                `;
            } else {
                lampiranContainer.innerHTML = `
                    <span class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-slate-50 text-slate-400 rounded-xl text-xs font-medium border border-slate-100">
                        <i class="ph-bold ph-file-dashed text-sm"></i> Tidak Ada File
                    </span>
                `;
            }

            // Tampilkan Modal dengan Animasi
            modal.classList.remove('hidden');
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                panel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            }, 10);
        }

        function closeDetailModal() {
            const modal = document.getElementById('detailModal');
            const backdrop = document.getElementById('modalBackdrop');
            const panel = document.getElementById('modalPanel');

            // Sembunyikan dengan Animasi
            backdrop.classList.add('opacity-0');
            panel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300); // Tunggu durasi transisi Tailwind (300ms)
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
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views/letters/incoming/index.blade.php ENDPATH**/ ?>