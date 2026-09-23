<!-- MODAL POPUP (ANNOUNCEMENT) -->
    <div x-show="modalOpen" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-[#021124]/80 backdrop-blur-md transition-opacity" @click="closeAnnouncement()"></div>
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-2xl bg-[#021124]/90 backdrop-blur-2xl text-left shadow-[0_0_40px_rgba(0,0,0,0.5)] transition-all sm:my-8 sm:w-full sm:max-w-2xl border border-white/20">
                <div class="px-6 py-6 sm:p-8">
                    <div class="flex justify-between items-start mb-6">
                        <span class="px-2.5 py-1 rounded-md bg-elevate-accent/20 text-elevate-accent text-xs font-bold uppercase tracking-wide border border-elevate-accent/30" x-text="activeAnnouncement?.category || 'Pengumuman'">
                            
                        </span>
                        <button @click="closeAnnouncement()" class="text-slate-400 hover:text-rose-400 transition bg-white/5 hover:bg-white/10 p-1 rounded-full"><i class="ph-bold ph-x text-xl"></i></button>
                    </div>
                    <h3 class="text-2xl font-bold text-white leading-tight mb-4" x-text="activeAnnouncement?.title"></h3>
                    <div class="flex items-center gap-2 text-sm text-slate-400 mb-6 pb-6 border-b border-white/10">
                        <i class="ph-fill ph-calendar-blank text-elevate-accent"></i>
                        <span x-text="new Date(activeAnnouncement?.created_at).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' })"></span>
                    </div>
                    <template x-if="activeAnnouncement?.image">
                        <div class="mb-6 rounded-2xl overflow-hidden max-h-80 bg-white/5 p-2 flex items-center justify-center border border-white/10">
                            <img :src="'{{ asset('storage') }}/' + activeAnnouncement.image" alt="Gambar Pengumuman" class="max-w-full max-h-80 object-contain rounded-xl" onerror="this.parentElement.style.display='none'">
                        </div>
                    </template>
                    <div class="prose prose-invert max-w-none text-slate-300 leading-relaxed">
                        <div x-html="activeAnnouncement?.content"></div>
                    </div>
                </div>
                <div class="bg-white/5 px-6 py-4 flex justify-end gap-3 border-t border-white/10">
                    <button class="inline-flex w-full justify-center rounded-xl bg-white/10 px-5 py-2.5 text-sm font-bold text-white shadow-sm ring-1 ring-inset ring-white/20 hover:bg-white/20 sm:w-auto transition-colors" @click="closeAnnouncement()">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- GUEST BOOK FORM MODAL -->
    <div x-show="guestBookModalOpen" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="guestBookModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-[#021124]/80 backdrop-blur-md transition-opacity" @click="guestBookModalOpen = false"></div>
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div x-show="guestBookModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-2xl bg-[#021124]/90 backdrop-blur-2xl text-left shadow-[0_0_40px_rgba(0,0,0,0.5)] transition-all sm:my-8 sm:w-full sm:max-w-lg border border-white/20">
                <form action="{{ route('guestbook.store') }}" method="POST">
                    @csrf
                    {{-- Honeypot Anti-Spam (tersembunyi dari manusia, diisi bot) --}}
                    <input type="text" name="website_hp" style="display:none !important;" tabindex="-1" autocomplete="off" aria-hidden="true">
                    <div class="px-6 py-6 sm:p-8">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-2xl font-bold text-white">Buku Tamu Digital</h3>
                            <button type="button" @click="guestBookModalOpen = false" class="text-slate-400 hover:text-rose-400 transition bg-white/5 hover:bg-white/10 p-1 rounded-full"><i class="ph-bold ph-x text-xl"></i></button>
                        </div>
                        
                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-semibold text-slate-300 mb-1">Nama Lengkap</label>
                                <input type="text" name="name" id="name" required class="w-full rounded-lg bg-white/5 border-white/20 text-white shadow-sm focus:border-elevate-accent focus:ring-elevate-accent sm:text-sm py-2.5 px-3" placeholder="Masukkan nama lengkap Anda">
                            </div>
                            
                            <div>
                                <label for="institution" class="block text-sm font-semibold text-slate-300 mb-1">Asal Instansi / Umum</label>
                                <input type="text" name="institution" id="institution" required class="w-full rounded-lg bg-white/5 border-white/20 text-white shadow-sm focus:border-elevate-accent focus:ring-elevate-accent sm:text-sm py-2.5 px-3" placeholder="Contoh: Dinas Pendidikan / Wali Murid">
                            </div>

                            <div>
                                <label for="purpose" class="block text-sm font-semibold text-slate-300 mb-1">Tujuan Kunjungan</label>
                                <select name="purpose" id="purpose" class="w-full rounded-lg bg-[#021124] border-white/20 text-white shadow-sm focus:border-elevate-accent focus:ring-elevate-accent sm:text-sm py-2.5 px-3">
                                    <option value="Dinas">Kunjungan Dinas</option>
                                    <option value="Rapat">Rapat / Pertemuan</option>
                                    <option value="Wali Murid">Urusan Wali Murid</option>
                                    <option value="Lainnya">Lainnya</option>
                                </select>
                            </div>

                            <div>
                                <label for="message" class="block text-sm font-semibold text-slate-300 mb-1">Pesan & Saran</label>
                                <textarea name="message" id="message" rows="3" class="w-full rounded-lg bg-white/5 border-white/20 text-white shadow-sm focus:border-elevate-accent focus:ring-elevate-accent sm:text-sm py-2.5 px-3" placeholder="Tuliskan pesan atau saran Anda..."></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white/5 px-6 py-4 flex justify-end gap-3 border-t border-white/10">
                        <button type="button" class="inline-flex justify-center rounded-xl bg-white/10 px-5 py-2.5 text-sm font-bold text-white shadow-sm ring-1 ring-inset ring-white/20 hover:bg-white/20 transition-colors" @click="guestBookModalOpen = false">Batal</button>
                        <button type="submit" class="inline-flex justify-center rounded-xl bg-gradient-to-r from-elevate-accent to-elevate-primary px-5 py-2.5 text-sm font-bold text-white shadow-[0_0_15px_rgba(86,187,241,0.4)] hover:opacity-95 transition-opacity">Kirim Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ALL GUESTS LIST MODAL -->
    <div x-show="guestListModalOpen" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="guestListModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-[#021124]/80 backdrop-blur-md transition-opacity" @click="guestListModalOpen = false"></div>
        <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
            <div x-show="guestListModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative transform overflow-hidden rounded-2xl bg-[#021124]/90 backdrop-blur-2xl text-left shadow-[0_0_40px_rgba(0,0,0,0.5)] transition-all sm:my-8 w-full max-w-4xl border border-white/20 flex flex-col max-h-[90vh]">
                <div class="px-6 py-4 border-b border-white/10 flex justify-between items-center shrink-0">
                    <div>
                        <h3 class="text-xl font-bold text-white">Daftar Kunjungan Tamu</h3>
                        <p class="text-sm text-slate-300">Riwayat pengisian buku tamu sekolah.</p>
                    </div>
                    <button type="button" @click="guestListModalOpen = false" class="text-slate-400 hover:text-rose-400 transition bg-white/5 hover:bg-white/10 p-2 rounded-full"><i class="ph-bold ph-x text-xl"></i></button>
                </div>
                
                <div class="p-0 overflow-y-auto flex-1 bg-white/5">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-white/10 sticky top-0 z-10 backdrop-blur-md border-b border-white/10">
                            <tr>
                                <th class="px-6 py-3 text-xs font-bold text-white uppercase tracking-wider">Waktu</th>
                                <th class="px-6 py-3 text-xs font-bold text-white uppercase tracking-wider">Nama Pengunjung</th>
                                <th class="px-6 py-3 text-xs font-bold text-white uppercase tracking-wider">Instansi</th>
                                <th class="px-6 py-3 text-xs font-bold text-white uppercase tracking-wider">Pesan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/10">
                            @forelse($allGuestbooks ?? $guestbooks as $item)
                            <tr class="hover:bg-white/10 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-slate-300">
                                    {{ $item->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-elevate-accent/20 text-elevate-accent flex items-center justify-center font-bold text-xs border border-elevate-accent/30">
                                            {{ substr($item->name, 0, 1) }}
                                        </div>
                                        <span class="text-sm font-bold text-white">{{ $item->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-300">
                                    {{ $item->institution }}
                                    <span class="block text-[10px] text-elevate-accent mt-0.5">{{ $item->purpose ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-300 italic">
                                    "{{ $item->message }}"
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-400">
                                    Belum ada data buku tamu.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="bg-white/5 px-6 py-4 border-t border-white/10 flex justify-end shrink-0">
                    <button type="button" class="px-5 py-2.5 rounded-xl bg-white/10 border border-white/20 text-white text-sm font-bold hover:bg-white/20 transition shadow-sm" @click="guestListModalOpen = false">Tutup</button>
                </div>
            </div>
        </div>
    </div>