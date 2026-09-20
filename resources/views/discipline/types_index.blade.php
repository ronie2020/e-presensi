<x-app-layout>
    <div class="py-8 sm:py-10 font-sans text-elevate-text">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <x-hero-section
                badge="Konfigurasi Aturan Poin"
                badgeIcon="gear"
                title="Master Data &"
                titleHighlight="Kategori Disiplin Siswa"
                description="Kelola daftar jenis pelanggaran, tingkatan konsekuensi, serta poin penghargaan karakter positif secara terpusat."
                :chips="['Katalog Pelanggaran Ringan-Berat', 'Standar Poin Konsekuensi', 'Bobot Poin Penghargaan']"
                :showcaseIcon="'list-checks'"
                showcaseLabel="Katalog Poin"
                showcaseStatus="Terverifikasi"
                :showcaseBubbles="[
                    ['icon' => 'warning', 'label' => 'Kategori Poin', 'pos' => '-top-2 -right-2']
                ]"
            >
                <x-slot:headerNav>
                    <div class="flex flex-wrap items-center gap-2">
                        <a href="{{ route('discipline.index') }}" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white border border-white/15 text-xs font-bold transition-all shadow-sm">
                            <i class="ph-bold ph-arrow-left text-sky-400"></i>
                            <span>Kembali ke Disiplin</span>
                        </a>
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 text-sky-300 text-xs font-bold uppercase tracking-wider backdrop-blur-md shadow-sm">
                            <i class="ph-fill ph-gear text-sky-400"></i> Konfigurasi Master
                        </div>
                    </div>
                </x-slot:headerNav>
            </x-hero-section>

            {{-- Flash Messages --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" class="mb-4 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-[1.5rem] flex items-center justify-between shadow-sm animate-enter">
                    <span class="font-bold text-sm flex items-center gap-2">
                        <i class="ph-fill ph-check-circle text-lg"></i> {{ session('success') }}
                    </span>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 p-1"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            {{-- TAMBAHAN: Handle Error Session (Penting untuk proteksi Alfa) --}}
            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" class="mb-4 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-[1.5rem] flex items-center justify-between shadow-sm animate-enter">
                    <span class="font-bold text-sm flex items-center gap-2">
                        <i class="ph-fill ph-warning-circle text-lg"></i> {{ session('error') }}
                    </span>
                    <button @click="show = false" class="text-rose-400 hover:text-rose-600 p-1"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif
            
            @if ($errors->any())
                <div class="mb-8 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-[1.5rem] shadow-sm">
                    <ul class="list-disc list-inside text-sm font-bold">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- FORM TAMBAH DATA (CARD MODERN) -->
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 mb-10 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-elevate-accent to-elevate-primary"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-6 border-b border-slate-50 pb-4">
                        <div class="w-10 h-10 bg-elevate-accent/10 text-elevate-primary rounded-xl flex items-center justify-center text-xl shadow-sm">
                            <i class="ph-fill ph-plus-circle"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-elevate-dark leading-none">Tambah Jenis Baru</h3>
                            <p class="text-xs font-bold text-slate-400 mt-1 uppercase tracking-wider">Pelanggaran / Kebaikan</p>
                        </div>
                    </div>

                    <form action="{{ route('discipline-types.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-4 gap-5 items-end">
                        @csrf
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Nama Kategori</label>
                            <input type="text" name="name" required placeholder="Contoh: Terlambat, Merapikan Kelas..." 
                                   class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 px-4 font-bold text-elevate-dark transition-all">
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tipe</label>
                            <div class="relative">
                                <select name="type" required class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 px-4 font-bold text-elevate-dark appearance-none cursor-pointer">
                                    <option value="Pelanggaran">🔴 Pelanggaran</option>
                                    <option value="Kebaikan">🟢 Kebaikan</option>
                                </select>
                                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Poin</label>
                            <input type="number" name="point_value" required min="1" 
                                   class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 px-4 font-bold text-center text-elevate-dark transition-all">
                        </div>
                        
                        <div class="md:col-span-4 flex justify-end mt-2">
                            <button type="submit" class="w-full md:w-auto bg-elevate-dark text-white px-8 py-3.5 rounded-2xl font-bold hover:bg-elevate-primary transition-all shadow-lg shadow-elevate-dark/20 flex items-center justify-center gap-2 transform active:scale-95">
                                <i class="ph-bold ph-floppy-disk text-lg"></i>
                                Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                
                <!-- TABEL PELANGGARAN -->
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden h-full flex flex-col">
                    <div class="p-6 bg-rose-50/50 border-b border-rose-100 flex items-center justify-between">
                        <h3 class="font-black text-rose-800 flex items-center gap-2 text-lg">
                            <i class="ph-fill ph-warning-octagon text-rose-500 text-xl"></i> Daftar Pelanggaran
                        </h3>
                        <span class="text-xs font-bold bg-white px-3 py-1 rounded-lg text-rose-600 border border-rose-100 shadow-sm">{{ $violationTypes->count() }} Item</span>
                    </div>
                    <div class="overflow-x-auto flex-1">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-white border-b border-slate-50">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase w-2/3">Nama</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-slate-400 uppercase">Poin</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-slate-400 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse ($violationTypes as $item)
                                    <tr class="hover:bg-rose-50/20 transition-colors group">
                                        <td class="px-6 py-4 text-sm font-bold text-elevate-dark">{{ $item->name }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-block px-2.5 py-1 rounded-lg bg-rose-100 text-rose-700 text-xs font-black">-{{ $item->point_value }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            {{-- Menggunakan SweetAlert2 --}}
                                            <form action="{{ route('discipline-types.destroy', $item->id) }}" method="POST" 
                                                  onsubmit="event.preventDefault(); 
                                                            const form = this;
                                                            Swal.fire({
                                                                title: 'Hapus Item?',
                                                                text: 'Yakin ingin menghapus jenis pelanggaran ini?',
                                                                icon: 'warning',
                                                                showCancelButton: true,
                                                                confirmButtonColor: '#e11d48',
                                                                cancelButtonColor: '#94a3b8',
                                                                confirmButtonText: 'Ya, Hapus!',
                                                                cancelButtonText: 'Batal',
                                                                reverseButtons: true,
                                                                customClass: {
                                                                    popup: 'rounded-[2rem] font-sans border-0 shadow-2xl',
                                                                    confirmButton: 'bg-rose-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-2 shadow-lg shadow-rose-900/20',
                                                                    cancelButton: 'bg-slate-100 text-slate-600 px-6 py-3 rounded-xl font-bold hover:bg-slate-200 transition-colors mx-2'
                                                                },
                                                                buttonsStyling: false
                                                            }).then((result) => {
                                                                if (result.isConfirmed) form.submit();
                                                            });">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-slate-300 hover:text-rose-500 transition-colors p-2 rounded-lg hover:bg-rose-50">
                                                    <i class="ph-bold ph-trash text-lg"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-6 py-12 text-center text-slate-400 font-medium">Belum ada data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- TABEL KEBAIKAN -->
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden h-full flex flex-col">
                    <div class="p-6 bg-emerald-50/50 border-b border-emerald-100 flex items-center justify-between">
                        <h3 class="font-black text-emerald-800 flex items-center gap-2 text-lg">
                            <i class="ph-fill ph-medal text-emerald-500 text-xl"></i> Daftar Kebaikan
                        </h3>
                        <span class="text-xs font-bold bg-white px-3 py-1 rounded-lg text-emerald-600 border border-emerald-100 shadow-sm">{{ $meritTypes->count() }} Item</span>
                    </div>
                    <div class="overflow-x-auto flex-1">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-white border-b border-slate-50">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase w-2/3">Nama</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-slate-400 uppercase">Poin</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-slate-400 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse ($meritTypes as $item)
                                    <tr class="hover:bg-emerald-50/20 transition-colors group">
                                        <td class="px-6 py-4 text-sm font-bold text-elevate-dark">{{ $item->name }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-block px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-700 text-xs font-black">+{{ $item->point_value }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            {{-- Menggunakan SweetAlert2 --}}
                                            <form action="{{ route('discipline-types.destroy', $item->id) }}" method="POST" 
                                                  onsubmit="event.preventDefault(); 
                                                            const form = this;
                                                            Swal.fire({
                                                                title: 'Hapus Item?',
                                                                text: 'Yakin ingin menghapus jenis kebaikan ini?',
                                                                icon: 'warning',
                                                                showCancelButton: true,
                                                                confirmButtonColor: '#e11d48',
                                                                cancelButtonColor: '#94a3b8',
                                                                confirmButtonText: 'Ya, Hapus!',
                                                                cancelButtonText: 'Batal',
                                                                reverseButtons: true,
                                                                customClass: {
                                                                    popup: 'rounded-[2rem] font-sans border-0 shadow-2xl',
                                                                    confirmButton: 'bg-rose-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-2 shadow-lg shadow-rose-900/20',
                                                                    cancelButton: 'bg-slate-100 text-slate-600 px-6 py-3 rounded-xl font-bold hover:bg-slate-200 transition-colors mx-2'
                                                                },
                                                                buttonsStyling: false
                                                            }).then((result) => {
                                                                if (result.isConfirmed) form.submit();
                                                            });">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-slate-300 hover:text-emerald-500 transition-colors p-2 rounded-lg hover:bg-emerald-50">
                                                    <i class="ph-bold ph-trash text-lg"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="px-6 py-12 text-center text-slate-400 font-medium">Belum ada data.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- SweetAlert2 Library --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</x-app-layout>