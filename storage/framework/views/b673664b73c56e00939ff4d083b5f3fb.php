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

    <div class="py-8 sm:py-10 font-sans text-elevate-text bg-slate-50 min-h-screen">
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
                                <i class="ph-bold ph-car-profile text-xl"></i>
                            </div>
                            Surat Perjalanan Dinas
                        </h1>
                        <p class="text-elevate-dark/80 text-sm font-medium leading-relaxed max-w-lg ml-0 md:ml-12">
                            Kelola SPPD, cetak dokumen perjalanan dinas, dan rekapitulasi biaya perjalanan dalam satu panel.
                        </p>
                        
                        <div class="mt-6 flex flex-wrap justify-center md:justify-start gap-3 ml-0 md:ml-12">
                            <a href="<?php echo e(route('sppd.create')); ?>" class="group bg-white text-elevate-dark px-5 py-3 rounded-2xl font-bold text-sm transition-all hover:bg-slate-50 flex items-center gap-2 shadow-lg shadow-elevate-dark/5 border border-white active:scale-95">
                                <div class="w-7 h-7 rounded-full bg-elevate-accent/20 text-elevate-primary flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <i class="ph-bold ph-plus text-sm"></i>
                                </div>
                                <span>Input SPPD Baru</span>
                            </a>
                        </div>
                    </div>
                    
                    
                    <div class="flex gap-3">
                        <div class="bg-white/60 backdrop-blur-md px-6 py-5 rounded-[2rem] border border-white shadow-sm text-center min-w-[140px]">
                            <span class="block text-4xl font-black text-elevate-dark mb-1"><?php echo e($sppds->total()); ?></span>
                            <span class="text-[10px] uppercase font-bold text-elevate-primary tracking-wider">Total SPPD</span>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                
                
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row gap-4 justify-between items-center">
                    <h3 class="font-black text-elevate-dark text-lg flex items-center gap-2">
                        <i class="ph-fill ph-list-dashes text-elevate-primary"></i> Riwayat SPPD
                    </h3>

                    <form action="<?php echo e(route('sppd.index')); ?>" method="GET" class="relative w-full sm:w-80 group">
                        <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="search" 
                               value="<?php echo e(request('search')); ?>" 
                               placeholder="Cari No SPPD / Tujuan / Pegawai..." 
                               class="w-full pl-11 pr-4 py-3 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm font-bold text-elevate-dark transition-all">
                    </form>
                </div>

                
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50/80 text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-5">No. SPPD & Pegawai</th>
                                <th class="px-6 py-5">Tujuan & Waktu</th>
                                <th class="px-6 py-5 w-1/3">Maksud Perjalanan</th>
                                <th class="px-6 py-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            <?php $__empty_1 = true; $__currentLoopData = $sppds; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sppd): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-6 py-5 align-top">
                                    <div class="font-mono font-bold text-elevate-primary bg-elevate-accent/10 px-3 py-1.5 rounded-lg border border-elevate-accent/20 inline-block text-xs mb-3">
                                        <?php echo e($sppd->nomor_sppd); ?>

                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 border border-slate-200 flex items-center justify-center font-bold text-xs shrink-0 group-hover:bg-elevate-primary group-hover:text-white transition-colors">
                                            <?php echo e(substr($sppd->user->name ?? '?', 0, 1)); ?>

                                        </div>
                                        <div>
                                            <div class="font-bold text-elevate-dark text-sm group-hover:text-elevate-primary transition-colors"><?php echo e($sppd->user->name ?? 'Pegawai Terhapus'); ?></div>
                                            <div class="text-[10px] text-slate-500 font-mono">NIP. <?php echo e($sppd->user->nip ?? '-'); ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <div class="font-bold text-elevate-dark text-sm mb-2 flex items-center gap-1.5">
                                        <i class="ph-fill ph-map-pin text-rose-500"></i> <?php echo e($sppd->tempat_tujuan); ?>

                                    </div>
                                    <div class="text-xs text-slate-500 flex flex-col gap-1 pl-5 font-medium">
                                        <span><?php echo e(\Carbon\Carbon::parse($sppd->tgl_berangkat)->format('d M Y')); ?></span>
                                        <span class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">s/d</span>
                                        <span><?php echo e(\Carbon\Carbon::parse($sppd->tgl_kembali)->format('d M Y')); ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <p class="text-sm text-slate-600 leading-relaxed line-clamp-3 font-medium">
                                        <?php echo e($sppd->maksud_perjalanan); ?>

                                    </p>
                                </td>
                                <td class="px-6 py-5 align-top text-right">
                                    <div class="flex flex-col items-end gap-2">
                                        <a href="<?php echo e(route('sppd.print', $sppd->id)); ?>" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-elevate-accent/10 border border-elevate-accent/20 text-elevate-primary hover:bg-elevate-primary hover:text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                                            <i class="ph-bold ph-printer text-base"></i> Cetak SPPD
                                        </a>
                                        
                                        <div class="flex items-center gap-2 mt-1">
                                            
                                            <button type="button" onclick="showDetailModal(<?php echo e(json_encode($sppd)); ?>)" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-sky-600 hover:border-sky-200 hover:bg-sky-50 hover:shadow-sm transition-all" title="Lihat Detail">
                                                <i class="ph-bold ph-eye text-lg"></i>
                                            </button>

                                            
                                            <a href="<?php echo e(route('sppd.edit', $sppd->id)); ?>" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-elevate-primary hover:border-elevate-accent hover:bg-elevate-soft hover:shadow-sm transition-all" title="Edit Data">
                                                <i class="ph-bold ph-pencil-simple text-lg"></i>
                                            </a>

                                            
                                            <button type="button" onclick="confirmDelete('<?php echo e($sppd->id); ?>')" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 hover:shadow-sm transition-all" title="Hapus">
                                                <i class="ph-bold ph-trash text-lg"></i>
                                            </button>
                                        </div>

                                        <form id="delete-form-<?php echo e($sppd->id); ?>" action="<?php echo e(route('sppd.destroy', $sppd->id)); ?>" method="POST" class="hidden">
                                            <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-20 text-center">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                        <i class="ph-duotone ph-car-profile text-4xl"></i>
                                    </div>
                                    <h3 class="text-elevate-dark font-bold text-lg">Belum ada data SPPD</h3>
                                    <p class="text-slate-500 text-sm mt-1">Silakan input SPPD baru melalui tombol di atas.</p>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-slate-100 bg-slate-50/50">
                    <?php echo e($sppds->withQueryString()->links()); ?>

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
                            <i class="ph-fill ph-car-profile"></i>
                        </div>
                        <div class="flex justify-between items-center relative z-10">
                            <div>
                                <h3 class="text-xl font-black flex items-center gap-2">
                                    <i class="ph-duotone ph-info text-elevate-accent"></i> Detail Surat Perjalanan Dinas
                                </h3>
                                <p class="text-elevate-accent text-sm font-medium mt-1 flex items-center gap-2">
                                    Nomor: <span id="modal_nomor" class="font-mono bg-white/10 px-2 rounded font-bold"></span>
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
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2"><i class="ph-fill ph-user text-elevate-primary"></i> Pegawai Utama</span>
                                <span id="modal_pegawai" class="font-bold text-elevate-dark text-sm flex flex-col gap-0.5 pl-4"></span>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2"><i class="ph-fill ph-map-pin text-rose-500"></i> Rute Perjalanan</span>
                                <span id="modal_rute" class="font-bold text-elevate-dark text-sm flex items-center gap-2 flex-wrap pl-4"></span>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2"><i class="ph-fill ph-car text-elevate-primary"></i> Transportasi</span>
                                <span id="modal_angkutan" class="font-bold text-elevate-dark text-sm pl-4"></span>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2"><i class="ph-fill ph-calendar-blank text-elevate-primary"></i> Waktu (<span id="modal_lama" class="text-elevate-primary"></span>)</span>
                                <span id="modal_waktu" class="font-bold text-elevate-dark text-sm pl-4"></span>
                            </div>
                        </div>

                        
                        <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100 mb-6">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Maksud / Tujuan Penugasan</span>
                            <p id="modal_maksud" class="text-sm font-medium text-slate-700 leading-relaxed"></p>
                        </div>

                        
                        <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100 mb-6 hidden" id="modal_pengikut_container">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-3"><i class="ph-bold ph-users text-elevate-primary"></i> Daftar Pengikut Dalam Perjalanan</span>
                            <ul id="modal_pengikut_list" class="space-y-2">
                                <!-- Data pengikut via JS -->
                            </ul>
                        </div>

                        
                        <div class="pt-6 border-t border-slate-100 flex items-center justify-between">
                            <a href="#" id="modal_btn_cetak" target="_blank" class="inline-flex items-center gap-2 px-6 py-3 bg-elevate-dark text-white rounded-xl font-bold text-sm hover:bg-elevate-primary transition-colors shadow-lg shadow-elevate-dark/20">
                                <i class="ph-bold ph-printer"></i> Cetak Dokumen SPPD
                            </a>
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
        // Notifikasi Sukses
        <?php if(session('success')): ?>
            const Toast = Swal.mixin({
                toast: true, position: 'top-end', showConfirmButton: false, timer: 3000,
                timerProgressBar: true, customClass: { popup: 'rounded-xl' }
            });
            Toast.fire({ icon: 'success', title: '<?php echo e(session("success")); ?>' });
        <?php endif; ?>

        // Konfirmasi Hapus
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus SPPD?', 
                text: "Data perjalanan dinas ini akan dihapus permanen.",
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
                if (result.isConfirmed) document.getElementById('delete-form-' + id).submit();
            })
        }

        // Fungsi JavaScript untuk Modal Detail SPPD
        function showDetailModal(sppd) {
            const modal = document.getElementById('detailModal');
            const backdrop = document.getElementById('modalBackdrop');
            const panel = document.getElementById('modalPanel');

            // Set Data Dasar
            document.getElementById('modal_nomor').innerText = sppd.nomor_sppd;
            document.getElementById('modal_rute').innerHTML = `${sppd.tempat_berangkat} <i class="ph-bold ph-arrow-right text-slate-400"></i> ${sppd.tempat_tujuan}`;
            document.getElementById('modal_angkutan').innerText = sppd.alat_angkut || 'Belum Ditentukan';
            document.getElementById('modal_lama').innerText = sppd.lama_hari + ' Hari';
            document.getElementById('modal_maksud').innerText = sppd.maksud_perjalanan;

            // Set Data Pegawai Utama
            if (sppd.user) {
                document.getElementById('modal_pegawai').innerHTML = `
                    <span>${sppd.user.name}</span>
                    <span class="text-[10px] text-slate-500 font-mono">NIP. ${sppd.user.nip || '-'}</span>
                `;
            } else {
                document.getElementById('modal_pegawai').innerHTML = '<span class="text-rose-500 italic text-xs">Data Pegawai Terhapus</span>';
            }

            // Format Tanggal Keberangkatan & Kembali
            const options = { day: 'numeric', month: 'long', year: 'numeric' };
            const tglBerangkat = new Date(sppd.tgl_berangkat).toLocaleDateString('id-ID', options);
            
            let waktuText = tglBerangkat;
            if(sppd.tgl_berangkat !== sppd.tgl_kembali && sppd.tgl_kembali) {
                const tglKembali = new Date(sppd.tgl_kembali).toLocaleDateString('id-ID', options);
                waktuText += ' <span class="text-[10px] text-slate-400 mx-1">s/d</span> ' + tglKembali;
            }
            document.getElementById('modal_waktu').innerHTML = waktuText;

            // Mapping Data Pengikut (Jika Ada)
            const pengikutContainer = document.getElementById('modal_pengikut_container');
            const pengikutList = document.getElementById('modal_pengikut_list');
            
            if (sppd.followers && sppd.followers.length > 0) {
                let htmlPengikut = '';
                sppd.followers.forEach((p, index) => {
                    htmlPengikut += `
                        <li class="flex items-center p-3 rounded-xl border border-slate-100 bg-white">
                            <div class="w-8 h-8 rounded-full bg-elevate-accent/10 text-elevate-primary flex items-center justify-center text-xs font-bold mr-3">${index + 1}</div>
                            <div class="flex flex-col flex-1">
                                <span class="text-sm font-bold text-slate-700">${p.nama}</span>
                                <span class="text-[10px] text-slate-500 font-mono">${p.nip ? 'NIP. ' + p.nip : 'Non-ASN / Honorer'}</span>
                            </div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase bg-slate-50 px-2 py-1 rounded">
                                ${p.keterangan || 'Anggota'}
                            </div>
                        </li>
                    `;
                });
                pengikutList.innerHTML = htmlPengikut;
                pengikutContainer.classList.remove('hidden');
            } else {
                pengikutContainer.classList.add('hidden'); // Sembunyikan bagian pengikut jika kosong
            }

            // Update Link Tombol Cetak
            const printUrl = `<?php echo e(url('sppd/print')); ?>/${sppd.id}`;
            document.getElementById('modal_btn_cetak').href = printUrl;

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
            }, 300); // Tunggu durasi transisi
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
<?php endif; ?><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/sppd/index.blade.php ENDPATH**/ ?>