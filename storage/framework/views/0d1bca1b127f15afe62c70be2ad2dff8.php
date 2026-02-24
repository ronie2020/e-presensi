  <!-- MODAL POPUP (ANNOUNCEMENT) -->
    <div x-show="modalOpen" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" @click="closeAnnouncement()"></div>
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-200">
                <div class="bg-white px-6 py-6 sm:p-8">
                    <div class="flex justify-between items-start mb-6">
                        <span class="px-2.5 py-1 rounded-md bg-blue-50 text-blue-600 text-xs font-bold uppercase tracking-wide border border-blue-100" x-text="activeAnnouncement?.category || 'Pengumuman'">
                            
                        </span>
                        <button @click="closeAnnouncement()" class="text-slate-400 hover:text-red-500 transition bg-slate-50 hover:bg-red-50 p-1 rounded-full"><i class="ph-bold ph-x text-xl"></i></button>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 leading-tight mb-4" x-text="activeAnnouncement?.title"></h3>
                    <div class="flex items-center gap-2 text-sm text-slate-400 mb-6 pb-6 border-b border-slate-100">
                        <i class="ph-fill ph-calendar-blank"></i>
                        <span x-text="new Date(activeAnnouncement?.created_at).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })"></span>
                    </div>
                    <div class="prose prose-slate max-w-none text-slate-600 leading-relaxed">
                        <div x-html="activeAnnouncement?.content.replace(/\n/g, '<br>')"></div>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex justify-end gap-3 border-t border-slate-100">
                    <button class="inline-flex w-full justify-center rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:w-auto transition-colors" @click="closeAnnouncement()">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- GUEST BOOK FORM MODAL -->
    <div x-show="guestBookModalOpen" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="guestBookModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" @click="guestBookModalOpen = false"></div>
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div x-show="guestBookModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200">
                <form action="<?php echo e(route('guestbook.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="bg-white px-6 py-6 sm:p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-2xl font-bold text-slate-900">Buku Tamu Digital</h3>
                            <button type="button" @click="guestBookModalOpen = false" class="text-slate-400 hover:text-red-500 transition bg-slate-50 hover:bg-red-50 p-1 rounded-full"><i class="ph-bold ph-x text-xl"></i></button>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1">Nama Lengkap</label>
                                <input type="text" name="name" id="name" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm py-2.5 px-3" placeholder="Masukkan nama lengkap Anda">
                            </div>
                            
                            <div>
                                <label for="institution" class="block text-sm font-semibold text-slate-700 mb-1">Asal Instansi / Umum</label>
                                <input type="text" name="institution" id="institution" required class="w-full rounded-lg border-slate-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm py-2.5 px-3" placeholder="Contoh: Dinas Pendidikan / Wali Murid">
                            </div>

                            <div>
                                <label for="purpose" class="block text-sm font-semibold text-slate-700 mb-1">Tujuan Kunjungan</label>
                                <select name="purpose" id="purpose" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm py-2.5 px-3">
                                    <option value="Dinas">Kunjungan Dinas</option>
                                    <option value="Rapat">Rapat / Pertemuan</option>
                                    <option value="Wali Murid">Urusan Wali Murid</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>

                            <div>
                                <label for="message" class="block text-sm font-semibold text-slate-700 mb-1">Pesan & Saran</label>
                                <textarea name="message" id="message" rows="3" class="w-full rounded-lg border-slate-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 sm:text-sm py-2.5 px-3" placeholder="Tuliskan pesan atau saran Anda..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 px-6 py-4 flex justify-end gap-3 border-t border-slate-100">
                        <button type="button" class="inline-flex justify-center rounded-xl bg-white px-5 py-2.5 text-sm font-bold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-colors" @click="guestBookModalOpen = false">Batal</button>
                        <button type="submit" class="inline-flex justify-center rounded-xl bg-pink-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-pink-700 transition-colors shadow-pink-500/30">Kirim Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ALL GUESTS LIST MODAL (NEW) -->
    <div x-show="guestListModalOpen" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="guestListModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" @click="guestListModalOpen = false"></div>
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div x-show="guestListModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 w-full max-w-4xl border border-slate-200 flex flex-col max-h-[90vh]">
                <div class="bg-white px-6 py-4 border-b border-slate-100 flex justify-between items-center shrink-0">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900">Daftar Kunjungan Tamu</h3>
                        <p class="text-sm text-slate-500">Riwayat pengisian buku tamu sekolah.</p>
                    </div>
                    <button type="button" @click="guestListModalOpen = false" class="text-slate-400 hover:text-red-500 transition bg-slate-50 hover:bg-red-50 p-2 rounded-full"><i class="ph-bold ph-x text-xl"></i></button>
                </div>
                
                <div class="p-0 overflow-y-auto flex-1">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 sticky top-0 z-10">
                            <tr>
                                <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">Waktu</th>
                                <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">Nama Pengunjung</th>
                                <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">Instansi</th>
                                <th class="px-6 py-3 text-xs font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200">Pesan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            <?php $__empty_1 = true; $__currentLoopData = $allGuestbooks ?? $guestbooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500">
                                    <?php echo e($item->created_at->format('d M Y, H:i')); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs">
                                            <?php echo e(substr($item->name, 0, 1)); ?>

                                        </div>
                                        <span class="text-sm font-bold text-slate-700"><?php echo e($item->name); ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                    <?php echo e($item->institution); ?>

                                    <span class="block text-[10px] text-slate-400 mt-0.5"><?php echo e($item->purpose ?? '-'); ?></span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600 italic">
                                    "<?php echo e($item->message); ?>"
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                    Belum ada data buku tamu.
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="bg-slate-50 px-6 py-4 border-t border-slate-100 flex justify-end shrink-0">
                    <button type="button" class="px-5 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-700 text-sm font-bold hover:bg-slate-50 transition shadow-sm" @click="guestListModalOpen = false">Tutup</button>
                </div>
            </div>
        </div>
    </div>
<?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/landing/modals.blade.php ENDPATH**/ ?>