<!-- MODAL POPUP (ANNOUNCEMENT) -->
    <div x-show="modalOpen" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" @click="closeAnnouncement()"></div>
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-slate-200 dark:border-slate-700">
                <div class="bg-white dark:bg-slate-800 px-6 py-6 sm:p-8">
                    <div class="flex justify-between items-start mb-6">
                        <span class="px-2.5 py-1 rounded-md bg-cyan-50 dark:bg-cyan-900/30 text-cyan-600 dark:text-cyan-400 text-xs font-bold uppercase tracking-wide border border-cyan-100 dark:border-cyan-500/20" x-text="activeAnnouncement?.category || 'Pengumuman'">
                            
                        </span>
                        <button @click="closeAnnouncement()" class="text-slate-400 hover:text-red-500 transition bg-slate-50 dark:bg-slate-700 hover:bg-red-50 dark:hover:bg-red-900/30 p-1 rounded-full"><i class="ph-bold ph-x text-xl"></i></button>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 dark:text-white leading-tight mb-4" x-text="activeAnnouncement?.title"></h3>
                    <div class="flex items-center gap-2 text-sm text-slate-400 mb-6 pb-6 border-b border-slate-100 dark:border-slate-700">
                        <i class="ph-fill ph-calendar-blank"></i>
                        <span x-text="new Date(activeAnnouncement?.created_at).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })"></span>
                    </div>
                    <div class="prose prose-slate max-w-none text-slate-600 dark:text-slate-300 leading-relaxed">
                        <div x-html="activeAnnouncement?.content.replace(/\n/g, '<br>')"></div>
                    </div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-900/50 px-6 py-4 flex justify-end gap-3 border-t border-slate-100 dark:border-slate-700">
                    <button class="inline-flex w-full justify-center rounded-xl bg-white dark:bg-slate-700 px-5 py-2.5 text-sm font-bold text-slate-700 dark:text-slate-200 shadow-sm ring-1 ring-inset ring-slate-300 dark:ring-slate-600 hover:bg-slate-50 dark:hover:bg-slate-600 sm:w-auto transition-colors" @click="closeAnnouncement()">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- GUEST BOOK FORM MODAL -->
    <div x-show="guestBookModalOpen" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="guestBookModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" @click="guestBookModalOpen = false"></div>
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div x-show="guestBookModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-200 dark:border-slate-700">
                <form action="{{ route('guestbook.store') }}" method="POST">
                    @csrf
                    <div class="bg-white dark:bg-slate-800 px-6 py-6 sm:p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-2xl font-bold text-slate-900 dark:text-white">Buku Tamu Digital</h3>
                            <button type="button" @click="guestBookModalOpen = false" class="text-slate-400 hover:text-red-500 transition bg-slate-50 dark:bg-slate-700 hover:bg-red-50 dark:hover:bg-red-900/30 p-1 rounded-full"><i class="ph-bold ph-x text-xl"></i></button>
                        </div>
                        
                        <!-- Ring warna Pink diubah menjadi Blue -->
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap</label>
                                <input type="text" name="name" id="name" required class="w-full rounded-lg bg-white dark:bg-slate-900 border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5 px-3" placeholder="Masukkan nama lengkap Anda">
                            </div>
                            
                            <div>
                                <label for="institution" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Asal Instansi / Umum</label>
                                <input type="text" name="institution" id="institution" required class="w-full rounded-lg bg-white dark:bg-slate-900 border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5 px-3" placeholder="Contoh: Dinas Pendidikan / Wali Murid">
                            </div>

                            <div>
                                <label for="purpose" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Tujuan Kunjungan</label>
                                <select name="purpose" id="purpose" class="w-full rounded-lg bg-white dark:bg-slate-900 border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5 px-3">
                                    <option value="Dinas">Kunjungan Dinas</option>
                                    <option value="Rapat">Rapat / Pertemuan</option>
                                    <option value="Wali Murid">Urusan Wali Murid</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>

                            <div>
                                <label for="message" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Pesan & Saran</label>
                                <textarea name="message" id="message" rows="3" class="w-full rounded-lg bg-white dark:bg-slate-900 border-slate-300 dark:border-slate-600 text-slate-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm py-2.5 px-3" placeholder="Tuliskan pesan atau saran Anda..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-50 dark:bg-slate-900/50 px-6 py-4 flex justify-end gap-3 border-t border-slate-100 dark:border-slate-700">
                        <button type="button" class="inline-flex justify-center rounded-xl bg-white dark:bg-slate-700 px-5 py-2.5 text-sm font-bold text-slate-700 dark:text-slate-200 shadow-sm ring-1 ring-inset ring-slate-300 dark:ring-slate-600 hover:bg-slate-50 dark:hover:bg-slate-600 transition-colors" @click="guestBookModalOpen = false">Batal</button>
                        <!-- Tombol submit diubah ke warna biru -->
                        <button type="submit" class="inline-flex justify-center rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-blue-700 transition-colors shadow-blue-500/30">Kirim Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ALL GUESTS LIST MODAL (NEW) -->
    <div x-show="guestListModalOpen" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="guestListModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" @click="guestListModalOpen = false"></div>
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div x-show="guestListModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-800 text-left shadow-2xl transition-all sm:my-8 w-full max-w-4xl border border-slate-200 dark:border-slate-700 flex flex-col max-h-[90vh]">
                <div class="bg-white dark:bg-slate-800 px-6 py-4 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center shrink-0">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 dark:text-white">Daftar Kunjungan Tamu</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">Riwayat pengisian buku tamu sekolah.</p>
                    </div>
                    <button type="button" @click="guestListModalOpen = false" class="text-slate-400 hover:text-red-500 transition bg-slate-50 dark:bg-slate-700 hover:bg-red-50 dark:hover:bg-red-900/30 p-2 rounded-full"><i class="ph-bold ph-x text-xl"></i></button>
                </div>
                
                <div class="p-0 overflow-y-auto flex-1">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 dark:bg-slate-900/50 sticky top-0 z-10">
                            <tr>
                                <th class="px-6 py-3 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider border-b border-slate-200 dark:border-slate-700">Waktu</th>
                                <th class="px-6 py-3 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider border-b border-slate-200 dark:border-slate-700">Nama Pengunjung</th>
                                <th class="px-6 py-3 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider border-b border-slate-200 dark:border-slate-700">Instansi</th>
                                <th class="px-6 py-3 text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider border-b border-slate-200 dark:border-slate-700">Pesan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700 bg-white dark:bg-slate-800">
                            @forelse($allGuestbooks ?? $guestbooks as $item)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-500 dark:text-slate-400">
                                    {{ $item->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <!-- Avatar disesuaikan ke Cyan -->
                                        <div class="w-8 h-8 rounded-full bg-cyan-100 dark:bg-cyan-900/50 text-cyan-600 dark:text-cyan-400 flex items-center justify-center font-bold text-xs">
                                            {{ substr($item->name, 0, 1) }}
                                        </div>
                                        <span class="text-sm font-bold text-slate-700 dark:text-slate-200">{{ $item->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600 dark:text-slate-300">
                                    {{ $item->institution }}
                                    <span class="block text-[10px] text-slate-400 dark:text-slate-500 mt-0.5">{{ $item->purpose ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600 dark:text-slate-300 italic">
                                    "{{ $item->message }}"
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500">
                                    Belum ada data buku tamu.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="bg-slate-50 dark:bg-slate-900/50 px-6 py-4 border-t border-slate-100 dark:border-slate-700 flex justify-end shrink-0">
                    <button type="button" class="px-5 py-2.5 rounded-xl bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 text-sm font-bold hover:bg-slate-50 dark:hover:bg-slate-600 transition shadow-sm" @click="guestListModalOpen = false">Tutup</button>
                </div>
            </div>
        </div>
    </div>