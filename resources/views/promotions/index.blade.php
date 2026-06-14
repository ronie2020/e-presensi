<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans text-elevate-text bg-slate-50 min-h-screen" 
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
                    Swal.fire({ icon: 'warning', title: 'Pilih Siswa!', text: 'Anda belum memilih satupun siswa yang akan diproses.', confirmButtonColor: '#3b5889', customClass: { popup: 'rounded-[2.5rem]' } });
                    return;
                }

                if (!this.targetAction || !this.academicYear) {
                    Swal.fire({ icon: 'warning', title: 'Data Belum Lengkap!', text: 'Pastikan Anda telah memilih tujuan pemindahan dan mengisi Tahun Ajaran Lanjutan.', confirmButtonColor: '#3b5889', customClass: { popup: 'rounded-[2.5rem]' } });
                    return;
                }

                const yearPattern = /^\d{4}\/\d{4}$/;
                if (this.academicYear.trim() === '' || !yearPattern.test(this.academicYear.trim())) {
                    Swal.fire({ icon: 'warning', title: 'Format Tahun Ajaran Salah', text: 'Silakan isi Tahun Ajaran dengan format YYYY/YYYY (Contoh: 2024/2025).', confirmButtonColor: '#3b5889', customClass: { popup: 'rounded-[2.5rem]' } });
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
                    confirmButtonColor: '#3b5889', 
                    cancelButtonColor: '#94a3b8', 
                    confirmButtonText: 'Ya, Proses Sekarang!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    customClass: {
                        popup: 'rounded-[2.5rem] font-sans border-0 shadow-2xl',
                        confirmButton: 'bg-elevate-primary text-white px-6 py-3 rounded-xl font-bold hover:bg-elevate-dark transition-colors mx-2 shadow-lg shadow-elevate-primary/20',
                        cancelButton: 'bg-slate-100 text-slate-600 px-6 py-3 rounded-xl font-bold hover:bg-slate-200 transition-colors mx-2'
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
            <div class="relative rounded-[2rem] bg-elevate-gradient-main p-8 mb-8 text-elevate-dark shadow-xl shadow-elevate-accent/10 overflow-hidden border border-white/60">
                <div class="absolute -top-10 -left-10 w-48 h-48 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/20 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>
                <div class="absolute top-10 right-32 w-24 h-24 bg-white/40 rounded-2xl rotate-45 pointer-events-none shadow-sm"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/50 border border-white/60 text-elevate-dark text-[10px] font-bold uppercase tracking-widest mb-3 shadow-sm backdrop-blur-sm">
                            <i class="ph-fill ph-arrows-left-right"></i> Mutasi & Rolling Massal
                        </div>
                        <h1 class="text-3xl font-black text-elevate-dark tracking-tight mb-2">Kenaikan & Acak Kelas</h1>
                        <p class="text-elevate-dark/80 text-sm max-w-xl font-medium">
                            Naikkan seluruh siswa di satu tingkat secara bersamaan dan acak mereka secara adil berdasarkan gender (Rolling), atau pindahkan per kelas.
                        </p>
                    </div>
                </div>
            </div>

            @if($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-[1.5rem] flex items-start gap-3 shadow-sm animate-enter">
                    <i class="ph-fill ph-warning-circle text-xl mt-0.5"></i>
                    <div>
                        <p class="font-bold text-sm mb-1">Gagal memproses permintaan:</p>
                        <ul class="list-disc list-inside text-xs font-medium space-y-1">
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
                    <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 sticky top-24">
                        <form method="GET" action="{{ route('promotions.index') }}" id="filterForm">
                            <h3 class="font-black text-elevate-dark mb-4 flex items-center gap-2 text-sm uppercase tracking-wider">
                                <i class="ph-bold ph-funnel text-elevate-primary"></i> Pilih Target Asal
                            </h3>
                            
                            <div class="relative">
                                <select name="from_class_id" onchange="document.getElementById('filterForm').submit()" 
                                        class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-sm text-elevate-dark focus:ring-elevate-primary focus:border-elevate-primary mb-4 py-3 px-4 appearance-none cursor-pointer">
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
                            
                            <div class="p-4 bg-elevate-accent/10 rounded-xl border border-elevate-accent/20 text-[10px] text-elevate-dark font-medium leading-relaxed shadow-sm">
                                <i class="ph-fill ph-info block text-lg mb-1 text-elevate-primary"></i>
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
                            <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 mb-6 grid grid-cols-1 md:grid-cols-12 gap-4 items-end relative">
                                
                                {{-- INPUT TAHUN AJARAN (Span 3) --}}
                                <div class="md:col-span-3 relative z-10">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tahun Ajaran Lanjutan</label>
                                    <input type="text" name="academic_year" x-model="academicYear" placeholder="Cth: 2024/2025" required 
                                            pattern="\d{4}/\d{4}" title="Gunakan format YYYY/YYYY, contoh: 2024/2025"
                                            class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-sm text-elevate-dark focus:ring-elevate-primary focus:border-elevate-primary h-12 transition-all px-4 {{ $errors->has('academic_year') ? 'border-rose-500 bg-rose-50' : '' }}">
                                </div>

                                {{-- Tujuan Pemindahan (Span 6) --}}
                                <div class="md:col-span-6 relative z-10">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tujuan Pemindahan</label>
                                    <div class="relative">
                                        <select name="target_action" x-model="targetAction" required 
                                                class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-sm text-elevate-dark focus:ring-elevate-primary focus:border-elevate-primary h-12 transition-all px-4 cursor-pointer appearance-none">
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
                                            class="w-full px-8 py-3 bg-elevate-dark hover:bg-elevate-primary text-white font-bold rounded-xl shadow-lg shadow-elevate-dark/20 transition-all flex items-center justify-center gap-2 h-12 active:scale-95">
                                        <i class="ph-bold ph-magic-wand"></i> Eksekusi
                                    </button>
                                </div>
                                
                                {{-- Background decorative layers tetap di bawah --}}
                                <div x-show="targetAction === 'alumni'" x-transition.opacity class="absolute inset-0 bg-gradient-to-r from-amber-50 to-orange-50/50 pointer-events-none z-0"></div>
                                <div x-show="targetAction.startsWith('roll_')" x-transition.opacity class="absolute inset-0 bg-gradient-to-r from-indigo-50 to-purple-50/50 pointer-events-none z-0"></div>
                            </div>

                            {{-- TABEL DAFTAR SISWA --}}
                            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden flex flex-col min-h-[600px]">
                                
                                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row gap-4 justify-between items-center">
                                    <h3 class="font-black text-elevate-dark text-lg flex items-center gap-2 shrink-0">
                                        <i class="ph-fill ph-users-three text-elevate-primary"></i> Daftar Siswa Terpilih
                                    </h3>

                                    <div class="flex items-center gap-2 w-full md:w-auto">
                                        <div class="relative flex-1 md:w-64">
                                            <i class="ph-bold ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                            <input type="text" x-model="searchQuery" @keyup="filterSearch()" placeholder="Cari nama atau NIS..." 
                                                   class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 bg-white focus:border-elevate-primary focus:ring-elevate-primary text-xs font-bold text-elevate-dark shadow-sm">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex-1 overflow-x-auto custom-scrollbar relative">
                                    <table class="w-full text-sm text-left text-slate-600" id="students-table">
                                        <thead class="text-xs font-bold text-slate-400 uppercase bg-slate-50 border-b border-slate-100 sticky top-0 z-20 shadow-sm">
                                            <tr>
                                                <th class="px-6 py-4 w-16 text-center">
                                                    <input type="checkbox" x-model="checkAll" @change="toggleAll()" class="rounded border-slate-300 text-elevate-primary focus:ring-elevate-primary w-5 h-5 cursor-pointer shadow-sm">
                                                </th>
                                                <th class="px-6 py-4">Nama Lengkap & Kelas Asal</th>
                                                <th class="px-6 py-4">NIS / NISN</th>
                                                <th class="px-6 py-4">Jenis Kelamin</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-50">
                                            @foreach($students as $student)
                                            <tr class="hover:bg-slate-50/80 transition-colors cursor-pointer student-row group" @click="toggleRow('{{ $student->id }}')">
                                                <td class="px-6 py-4 text-center">
                                                    <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" id="chk-{{ $student->id }}"
                                                           class="student-checkbox rounded border-slate-300 text-elevate-primary focus:ring-elevate-primary w-5 h-5 cursor-pointer shadow-sm"
                                                           {{ (is_array(old('student_ids')) && in_array($student->id, old('student_ids'))) || !old('student_ids') ? 'checked' : '' }}
                                                           @click.stop="updateCheckAll()">
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-8 h-8 rounded-full bg-elevate-accent/10 border border-elevate-accent/20 flex items-center justify-center text-xs text-elevate-primary font-bold overflow-hidden shrink-0 group-hover:bg-elevate-primary group-hover:text-white transition-colors">
                                                            @if($student->photo_path)
                                                                <img src="{{ asset('storage/'.$student->photo_path) }}" class="w-full h-full object-cover">
                                                            @else
                                                                {{ substr($student->name, 0, 1) }}
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <div class="font-bold text-elevate-dark group-hover:text-elevate-primary transition-colors student-name">{{ $student->name }}</div>
                                                            {{-- TAMBAHAN: Tampilkan Kelas Asal di bawah nama --}}
                                                            <div class="text-[10px] text-slate-400 mt-0.5">Kelas Asal: <span class="font-bold text-slate-500">{{ $student->schoolClass ? $student->schoolClass->name : '-' }}</span></div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 font-mono text-xs text-slate-500 student-nis">
                                                    {{ $student->nisn ?? $student->student_id }}
                                                </td>
                                                <td class="px-6 py-4">
                                                    @if($student->gender === 'L')
                                                        <span class="inline-flex px-2 py-1 rounded-lg text-[10px] font-bold bg-elevate-accent/10 text-elevate-primary">Laki-laki</span>
                                                    @elseif($student->gender === 'P')
                                                        <span class="inline-flex px-2 py-1 rounded-lg text-[10px] font-bold bg-rose-50 text-rose-600">Perempuan</span>
                                                    @else
                                                        <span class="inline-flex px-2 py-1 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-500">Belum Diisi</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="p-4 border-t border-slate-100 bg-slate-50/50 flex justify-between items-center text-xs font-bold text-slate-500">
                                    <span>Total: {{ count($students ?? []) }} Siswa</span>
                                    <span>Terpilih: <span x-text="selectedCount" class="text-elevate-primary font-black">0</span></span>
                                </div>
                            </div>
                        </form>

                    @elseif(request('from_class_id'))
                        <div class="bg-white rounded-[2.5rem] p-16 text-center shadow-sm border border-slate-100 mt-6 lg:mt-0">
                            <div class="w-20 h-20 bg-slate-50 border border-slate-100 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                                <i class="ph-duotone ph-users-three text-4xl"></i>
                            </div>
                            <h3 class="text-lg font-black text-elevate-dark mb-2">Tidak Ada Siswa</h3>
                            <p class="text-slate-500 text-sm max-w-sm mx-auto">Tidak ada siswa aktif yang ditemukan di kriteria target asal ini.</p>
                        </div>
                    @else
                        <div class="bg-slate-50/50 rounded-[2.5rem] p-16 text-center border-2 border-dashed border-slate-200 h-full min-h-[400px] flex flex-col items-center justify-center mt-6 lg:mt-0">
                            <i class="ph-duotone ph-arrow-left text-4xl text-elevate-accent mb-4 animate-bounce"></i>
                            <h3 class="text-base font-bold text-elevate-dark mb-1">Menunggu Pilihan Kelas Asal</h3>
                            <p class="text-slate-500 text-sm font-medium">Pilih "Semua Siswa" atau kelas satuan di menu sebelah kiri.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({ icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}", confirmButtonColor: '#3b5889', customClass: { popup: 'rounded-[2.5rem]' } });
            @endif
            @if(session('error'))
                Swal.fire({ icon: 'error', title: 'Oops...', text: "{{ session('error') }}", confirmButtonColor: '#e11d48', customClass: { popup: 'rounded-[2.5rem]' } });
            @endif
        });
    </script>
</x-app-layout>