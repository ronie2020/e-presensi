<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans text-white bg-elevate-surface min-h-screen" 
         x-data="{
            checkAll: true,
            targetAction: '{{ old('target_action') }}',
            academicYear: '{{ old('academic_year') }}',
            searchQuery: '',
            selectedCount: 0,
            
            init() {
                setTimeout(() => { this.updateCheckAll(); }, 100);
            },
            
            toggleAll() {
                const checkboxes = document.querySelectorAll('.student-checkbox');
                checkboxes.forEach(cb => {
                    if(cb.closest('tr').style.display !== 'none') {
                        cb.checked = this.checkAll;
                    }
                });
                this.calculateSelected();
            },
            
            updateCheckAll() {
                const checkboxes = document.querySelectorAll('.student-checkbox');
                if(checkboxes.length === 0) return;
                
                const visibleCheckboxes = Array.from(checkboxes).filter(cb => cb.closest('tr').style.display !== 'none');
                if (visibleCheckboxes.length === 0) {
                    this.checkAll = false;
                    this.calculateSelected();
                    return;
                }
                
                this.checkAll = visibleCheckboxes.every(cb => cb.checked);
                this.calculateSelected();
            },

            toggleRow(id) {
                const cb = document.getElementById('chk-' + id);
                if(cb) {
                    cb.checked = !cb.checked;
                    this.updateCheckAll();
                }
            },

            calculateSelected() {
                this.selectedCount = document.querySelectorAll('.student-checkbox:checked').length;
            },

            filterSearch() {
                const filter = this.searchQuery.toLowerCase();
                const rows = document.querySelectorAll('.student-row');
                
                rows.forEach(row => {
                    const name = row.querySelector('.student-name').textContent.toLowerCase();
                    const nis = row.querySelector('.student-nis').textContent.toLowerCase();
                    if (name.includes(filter) || nis.includes(filter)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                        const cb = row.querySelector('.student-checkbox');
                        if(cb) cb.checked = false; 
                    }
                });
                this.updateCheckAll();
            },

            confirmProcess() {
                if (this.selectedCount === 0) {
                    Swal.fire({ icon: 'warning', title: 'Pilih Siswa!', text: 'Anda belum memilih satupun siswa yang akan diproses.', confirmButtonColor: '#0d52a1', customClass: { popup: 'rounded-[2.5rem]' } });
                    return;
                }

                if (!this.targetAction || !this.academicYear) {
                    Swal.fire({ icon: 'warning', title: 'Data Belum Lengkap!', text: 'Pastikan Anda telah memilih tujuan pemindahan dan mengisi Tahun Ajaran Lanjutan.', confirmButtonColor: '#0d52a1', customClass: { popup: 'rounded-[2.5rem]' } });
                    return;
                }

                const yearPattern = /^\d{4}\/\d{4}$/;
                if (this.academicYear.trim() === '' || !yearPattern.test(this.academicYear.trim())) {
                    Swal.fire({ icon: 'warning', title: 'Format Tahun Ajaran Salah', text: 'Silakan isi Tahun Ajaran dengan format YYYY/YYYY (Contoh: 2024/2025).', confirmButtonColor: '#0d52a1', customClass: { popup: 'rounded-[2.5rem]' } });
                    return;
                }

                let actionText = '';
                if(this.targetAction === 'alumni') actionText = 'Meluluskan (Menjadi Alumni)';
                else if(this.targetAction.startsWith('roll_')) actionText = 'Mengacak (Rolling) dan Menaikkan Tingkat';
                else actionText = 'Memindahkan Kelas';

                Swal.fire({
                    title: 'Konfirmasi Tindakan',
                    html: `Anda akan <b>${actionText}</b> untuk <b>${this.selectedCount} siswa</b> di Tahun Ajaran <b>${this.academicYear}</b>. Lanjutkan?`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#0d52a1', 
                    cancelButtonColor: '#64748b', 
                    confirmButtonText: 'Ya, Proses Sekarang!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-[2.5rem] font-sans border border-white/15 shadow-2xl',
                        confirmButton: 'bg-gradient-to-r from-[#0d52a1] to-sky-600 text-white px-6 py-3 rounded-xl font-bold hover:from-sky-600 hover:to-sky-400 transition-all mx-2 shadow-lg',
                        cancelButton: 'bg-white/10 text-slate-300 px-6 py-3 rounded-xl font-bold hover:bg-white/20 transition-all mx-2'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Memproses Data...', text: 'Algoritma sedang berjalan, mohon tunggu...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); }, customClass: { popup: 'rounded-[2.5rem] font-sans' }
                        });
                        document.getElementById('promotionForm').submit();
                    }
                });
            }
         }">
         
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO SECTION --}}
            <div class="mb-8 relative z-10">
                <x-hero-section
                    badge="MUTASI & ROLLING MASSAL"
                    badgeIcon="ph-fill ph-arrows-left-right"
                    showcaseIcon="ph-duotone ph-arrows-split"
                    showcaseTitle="Rolling Otomatis"
                    showcaseSubtitle="Distribusi Seimbang">
                    <x-slot:title>
                        <span class="block text-slate-100">Kenaikan & Mutasi</span>
                        <span class="block mt-1 sm:mt-1.5 text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] via-sky-200 to-[#38bdf8]">
                            Acak Kelas Siswa
                        </span>
                    </x-slot:title>
                    <x-slot:description>
                        Naikkan seluruh siswa di satu tingkat secara bersamaan dan acak mereka secara adil berdasarkan gender (Rolling), atau pindahkan per rombel.
                    </x-slot:description>
                    <x-slot:chips>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-shuffle text-sky-400"></i> Smart Rolling Gender
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-graduation-cap text-emerald-400"></i> Kelulusan Alumni
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 border border-white/15 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-calendar-check text-cyan-400"></i> Tahun Ajaran Baru
                        </span>
                    </x-slot:chips>
                    <x-slot:showcaseStats>
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-2xl bg-white/10 border border-white/15 backdrop-blur-md">
                            <i class="ph-fill ph-check-square text-sky-400 text-sm"></i>
                            <span class="text-xs font-bold text-slate-300">Terpilih:</span>
                            <span class="text-sm font-black text-white font-mono" x-text="selectedCount">0</span>
                            <span class="text-xs text-slate-400 font-medium">Siswa</span>
                        </div>
                    </x-slot:showcaseStats>
                </x-hero-section>
            </div>

            @if($errors->any())
                <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/30 text-rose-200 rounded-[1.5rem] flex items-start gap-3 shadow-xl backdrop-blur-md animate-enter">
                    <i class="ph-fill ph-warning-circle text-2xl text-rose-400 mt-0.5 shrink-0"></i>
                    <div>
                        <p class="font-bold text-sm mb-1 text-rose-300">Gagal memproses permintaan:</p>
                        <ul class="list-disc list-inside text-xs font-medium space-y-1 text-rose-200/90">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                
                {{-- KOLOM KIRI: FILTER KELAS ASAL --}}
                <div class="lg:col-span-1">
                    <div class="bg-gradient-to-br from-[#031d3d]/90 via-[#021124]/95 to-[#021124] rounded-[2.5rem] p-6 shadow-2xl border border-white/15 backdrop-blur-xl sticky top-24">
                        <form method="GET" action="{{ route('promotions.index') }}" id="filterForm">
                            <h3 class="font-black text-sky-300 mb-4 flex items-center gap-2 text-xs uppercase tracking-wider">
                                <i class="ph-bold ph-funnel text-sky-400"></i> Pilih Target Asal
                            </h3>
                            
                            <div class="relative">
                                <select name="from_class_id" onchange="document.getElementById('filterForm').submit()" 
                                        class="w-full rounded-xl border-white/15 bg-[#021124]/90 font-bold text-sm text-white focus:border-[#56bbf1] focus:ring-2 focus:ring-[#56bbf1]/30 mb-4 py-3 px-4 appearance-none cursor-pointer">
                                    <option value="">-- Silakan Pilih --</option>
                                    
                                    {{-- TAMBAHAN: GRUP LEVEL (SEMUA TINGKAT) --}}
                                    <optgroup label="Pilih Semua di Tingkat (Untuk Rolling)">
                                        <option value="level_7" {{ request('from_class_id') == 'level_7' ? 'selected' : '' }}>Semua Siswa Kelas 7</option>
                                        <option value="level_8" {{ request('from_class_id') == 'level_8' ? 'selected' : '' }}>Semua Siswa Kelas 8</option>
                                        <option value="level_9" {{ request('from_class_id') == 'level_9' ? 'selected' : '' }}>Semua Siswa Kelas 9</option>
                                    </optgroup>

                                    <optgroup label="Pilih Kelas Spesifik (Satuan)">
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" {{ request('from_class_id') == $class->id ? 'selected' : '' }}>
                                                Kelas {{ $class->name }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                </select>
                                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400 mb-4"><i class="ph-bold ph-caret-down"></i></div>
                            </div>
                            
                            <div class="p-4 bg-sky-500/10 rounded-xl border border-sky-400/20 text-xs text-slate-300 font-medium leading-relaxed backdrop-blur-md">
                                <i class="ph-fill ph-info block text-lg mb-1 text-sky-400"></i>
                                Untuk melakukan pengacakan (Rolling), silakan pilih <b>"Semua Siswa Kelas X"</b> di atas agar ratusan siswa muncul bersamaan.
                            </div>
                        </form>
                    </div>
                </div>

                {{-- KOLOM KANAN: TABEL SISWA & AKSI --}}
                <div class="lg:col-span-3">
                    @if(request('from_class_id') && count($students ?? []) > 0)
                        
                        <form action="{{ route('promotions.process') }}" method="POST" id="promotionForm">
                            @csrf
                            <input type="hidden" name="from_class_id" value="{{ request('from_class_id') }}">
                            
                            {{-- BAR AKSI TARGET --}}
                            <div class="bg-gradient-to-br from-[#031d3d]/90 via-[#021124]/95 to-[#021124] rounded-[2.5rem] p-6 shadow-2xl border border-white/15 backdrop-blur-xl mb-6 grid grid-cols-1 md:grid-cols-12 gap-4 items-end relative overflow-hidden">
                                
                                {{-- INPUT TAHUN AJARAN (Span 3) --}}
                                <div class="md:col-span-3 relative z-10">
                                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2 ml-1">Tahun Ajaran Lanjutan</label>
                                    <input type="text" name="academic_year" x-model="academicYear" placeholder="Cth: 2024/2025" required 
                                            pattern="\d{4}/\d{4}" title="Gunakan format YYYY/YYYY, contoh: 2024/2025"
                                            class="w-full rounded-xl border-white/15 bg-[#021124]/90 font-bold text-sm text-white focus:border-[#56bbf1] focus:ring-2 focus:ring-[#56bbf1]/30 h-12 transition-all px-4 {{ $errors->has('academic_year') ? 'border-rose-500 bg-rose-500/10' : '' }}">
                                </div>

                                {{-- Tujuan Pemindahan (Span 6) --}}
                                <div class="md:col-span-6 relative z-10">
                                    <label class="block text-xs font-bold text-slate-300 uppercase tracking-wider mb-2 ml-1">Tujuan Pemindahan</label>
                                    <div class="relative">
                                        <select name="target_action" x-model="targetAction" required 
                                                class="w-full rounded-xl border-white/15 bg-[#021124]/90 font-bold text-sm text-white focus:border-[#56bbf1] focus:ring-2 focus:ring-[#56bbf1]/30 h-12 transition-all px-4 cursor-pointer appearance-none">
                                            <option value="">-- Pilih Kelas Tujuan --</option>
                                            <optgroup label="Acak & Naik Tingkat (Rolling)">
                                                <option value="roll_7">Acak & Pindahkan merata ke Tingkat 7 (7A-7F)</option>
                                                <option value="roll_8">Acak & Pindahkan merata ke Tingkat 8 (8A-8F)</option>
                                                <option value="roll_9">Acak & Pindahkan merata ke Tingkat 9 (9A-9F)</option>
                                            </optgroup>
                                            <optgroup label="Pindah ke Kelas Spesifik (Tidak Diacak)">
                                                @foreach($classes as $class)
                                                    @if($class->id != request('from_class_id'))
                                                        <option value="{{ $class->id }}">Pindahkan ke {{ $class->name }}</option>
                                                    @endif
                                                @endforeach
                                            </optgroup>
                                            <optgroup label="Kelulusan">
                                                <option value="alumni">Luluskan Siswa (Jadikan Alumni)</option>
                                            </optgroup>
                                        </select>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                    </div>
                                </div>
                                
                               {{-- Tombol Submit (Span 3) --}}
                                <div class="md:col-span-3">
                                    <button type="button" @click="confirmProcess()" 
                                            class="w-full px-8 py-3 bg-gradient-to-r from-[#0d52a1] via-sky-600 to-[#56bbf1] hover:from-sky-600 hover:to-sky-400 text-white font-bold rounded-xl shadow-lg shadow-sky-600/30 border border-white/20 transition-all flex items-center justify-center gap-2 h-12 active:scale-95">
                                        <i class="ph-bold ph-magic-wand text-lg"></i> Eksekusi
                                    </button>
                                </div>
                                
                                {{-- Background decorative layers tetap di bawah --}}
                                <div x-show="targetAction === 'alumni'" x-transition.opacity class="absolute inset-0 bg-gradient-to-r from-amber-500/10 to-orange-500/10 pointer-events-none z-0"></div>
                                <div x-show="targetAction.startsWith('roll_')" x-transition.opacity class="absolute inset-0 bg-gradient-to-r from-indigo-500/10 to-purple-500/10 pointer-events-none z-0"></div>
                            </div>

                            {{-- TABEL DAFTAR SISWA --}}
                            <div class="bg-gradient-to-br from-[#031d3d]/90 via-[#021124]/95 to-[#021124] rounded-[2.5rem] shadow-2xl border border-white/15 overflow-hidden flex flex-col min-h-[600px] backdrop-blur-xl">
                                
                                <div class="p-6 border-b border-white/10 bg-[#021124]/60 flex flex-col md:flex-row gap-4 justify-between items-center">
                                    <h3 class="font-black text-white text-lg flex items-center gap-2 shrink-0">
                                        <i class="ph-fill ph-users-three text-sky-400"></i> Daftar Siswa Terpilih
                                    </h3>

                                    <div class="flex items-center gap-2 w-full md:w-auto">
                                        <div class="relative flex-1 md:w-64">
                                            <i class="ph-bold ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                            <input type="text" x-model="searchQuery" @keyup="filterSearch()" placeholder="Cari nama atau NIS..." 
                                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-white/15 bg-[#021124]/90 focus:border-[#56bbf1] text-xs font-bold text-white shadow-inner">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex-1 overflow-x-auto custom-scrollbar relative">
                                    <table class="w-full text-sm text-left text-slate-300" id="students-table">
                                        <thead class="text-xs font-bold text-sky-300 uppercase bg-[#031d3d] border-b border-white/10 sticky top-0 z-20 shadow-sm">
                                            <tr>
                                                <th class="px-6 py-4 w-16 text-center">
                                                    <input type="checkbox" x-model="checkAll" @change="toggleAll()" class="rounded border-white/20 bg-[#021124] text-sky-400 focus:ring-sky-400 w-5 h-5 cursor-pointer shadow-sm">
                                                </th>
                                                <th class="px-6 py-4">Nama Lengkap & Kelas Asal</th>
                                                <th class="px-6 py-4">NIS / NISN</th>
                                                <th class="px-6 py-4">Jenis Kelamin</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-white/5">
                                            @foreach($students as $student)
                                            <tr class="hover:bg-white/5 transition-colors cursor-pointer student-row group" @click="toggleRow('{{ $student->id }}')">
                                                <td class="px-6 py-4 text-center">
                                                    <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" id="chk-{{ $student->id }}"
                                                           class="student-checkbox rounded border-white/20 bg-[#021124] text-sky-400 focus:ring-sky-400 w-5 h-5 cursor-pointer shadow-sm"
                                                           {{ (is_array(old('student_ids')) && in_array($student->id, old('student_ids'))) || !old('student_ids') ? 'checked' : '' }}
                                                           @click.stop="updateCheckAll()">
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-8 h-8 rounded-full bg-sky-500/10 border border-sky-400/20 flex items-center justify-center text-xs text-sky-300 font-bold overflow-hidden shrink-0 group-hover:bg-sky-500 group-hover:text-white transition-colors">
                                                            @if($student->photo_path)
                                                                <img src="{{ asset('storage/'.$student->photo_path) }}" class="w-full h-full object-cover">
                                                            @else
                                                                {{ substr($student->name, 0, 1) }}
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <div class="font-bold text-white group-hover:text-sky-300 transition-colors student-name">{{ $student->name }}</div>
                                                            <div class="text-[11px] text-slate-400 mt-0.5">Kelas Asal: <span class="font-bold text-slate-300">{{ $student->schoolClass ? $student->schoolClass->name : '-' }}</span></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 font-mono text-xs text-slate-300 student-nis">
                                                    {{ $student->nisn ?? $student->student_id }}
                                                </td>
                                                <td class="px-6 py-4">
                                                    @if($student->gender === 'L')
                                                        <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-bold bg-sky-500/20 text-sky-300 border border-sky-400/30">Laki-laki</span>
                                                    @elseif($student->gender === 'P')
                                                        <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-bold bg-pink-500/20 text-pink-300 border border-pink-400/30">Perempuan</span>
                                                    @else
                                                        <span class="inline-flex px-2.5 py-1 rounded-lg text-[10px] font-bold bg-white/10 text-slate-400 border border-white/15">Belum Diisi</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="p-4 border-t border-white/10 bg-[#021124]/60 flex justify-between items-center text-xs font-bold text-slate-300">
                                    <span>Total: {{ count($students ?? []) }} Siswa</span>
                                    <span>Terpilih: <span x-text="selectedCount" class="text-sky-400 font-black">0</span></span>
                                </div>
                            </div>
                        </form>

                    @elseif(request('from_class_id'))
                        <div class="bg-gradient-to-br from-[#031d3d]/90 via-[#021124]/95 to-[#021124] rounded-[2.5rem] p-16 text-center shadow-2xl border border-white/15 backdrop-blur-xl mt-6 lg:mt-0">
                            <div class="w-20 h-20 bg-white/5 border border-white/10 rounded-full flex items-center justify-center mx-auto mb-6 text-sky-400">
                                <i class="ph-duotone ph-users-three text-4xl"></i>
                            </div>
                            <h3 class="text-lg font-black text-white mb-2">Tidak Ada Siswa</h3>
                            <p class="text-slate-300 text-sm max-w-sm mx-auto">Tidak ada siswa aktif yang ditemukan di kriteria target asal ini.</p>
                        </div>
                    @else
                        <div class="bg-[#031d3d]/50 rounded-[2.5rem] p-16 text-center border-2 border-dashed border-white/15 h-full min-h-[400px] flex flex-col items-center justify-center mt-6 lg:mt-0 backdrop-blur-md">
                            <i class="ph-duotone ph-arrow-left text-4xl text-sky-400 mb-4 animate-bounce"></i>
                            <h3 class="text-base font-bold text-white mb-1">Menunggu Pilihan Kelas Asal</h3>
                            <p class="text-slate-300 text-sm font-medium">Pilih "Semua Siswa" atau kelas satuan di menu sebelah kiri.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", confirmButtonColor: '#0d52a1', customClass: { popup: 'rounded-[2.5rem]' } });
            @endif
            @if(session('error'))
                Swal.fire({ icon: 'error', title: 'Oops...', text: "{{ session('error') }}", confirmButtonColor: '#e11d48', customClass: { popup: 'rounded-[2.5rem]' } });
            @endif
        });
    </script>
</x-app-layout>