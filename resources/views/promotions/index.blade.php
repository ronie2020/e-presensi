<x-app-layout>
    {{-- Alpine.js & SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 font-sans text-slate-800" x-data="promotionApp()">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER SECTION --}}
            <div class="relative rounded-[2.5rem] bg-slate-900 overflow-hidden p-8 sm:p-10 mb-8 shadow-xl">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none -translate-y-1/2 translate-x-1/2"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-300 text-[10px] font-bold uppercase tracking-widest mb-3">
                            <i class="ph-fill ph-arrows-left-right"></i> Mutasi Massal
                        </div>
                        <h1 class="text-3xl font-black text-white tracking-tight mb-2">Kenaikan Kelas & Kelulusan</h1>
                        <p class="text-slate-400 text-sm max-w-xl">
                            Kelola pemindahan siswa antar kelas saat pergantian tahun ajaran baru atau luluskan siswa kelas akhir ke Database Alumni.
                        </p>
                    </div>
                </div>
            </div>

            {{-- ERROR VALIDATION DISPLAY --}}
            @if($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl flex items-start gap-3 shadow-sm">
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
                    <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-slate-200/50 border border-slate-100 sticky top-8">
                        <form method="GET" action="{{ route('promotions.index') }}" id="filterForm">
                            <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2 text-sm uppercase tracking-wider">
                                <i class="ph-bold ph-funnel text-blue-500"></i> Pilih Kelas Asal
                            </h3>
                            
                            <select name="from_class_id" onchange="document.getElementById('filterForm').submit()" 
                                    class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-sm text-slate-600 focus:ring-blue-500 focus:border-blue-500 mb-4 h-11">
                                <option value="">-- Silakan Pilih --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" {{ request('from_class_id') == $class->id ? 'selected' : '' }}>
                                        Kelas {{ $class->name }}
                                    </option>
                                @endforeach
                            </select>
                            
                            <div class="p-4 bg-blue-50 rounded-xl border border-blue-100 text-[10px] text-blue-600 font-medium leading-relaxed">
                                <i class="ph-fill ph-info block text-lg mb-1"></i>
                                Pilih kelas di atas untuk memunculkan daftar siswa aktif. Hanya siswa yang dicentang yang akan diproses.
                            </div>
                        </form>
                    </div>
                </div>

                {{-- KOLOM KANAN: TABEL SISWA & AKSI --}}
                <div class="lg:col-span-3">
                    @if(request('from_class_id') && count($students) > 0)
                        
                        <form action="{{ route('promotions.process') }}" method="POST" id="promotionForm">
                            @csrf
                            <input type="hidden" name="from_class_id" value="{{ request('from_class_id') }}">
                            
                            {{-- BAR AKSI TARGET --}}
                            <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-slate-200/50 border border-slate-100 mb-6 flex flex-col md:flex-row items-end gap-4 relative overflow-hidden">
                                
                                {{-- Target Action Dropdown --}}
                                <div class="flex-1 w-full relative z-10">
                                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tujuan Pemindahan</label>
                                    <div class="flex gap-3">
                                        <select name="target_action" x-model="targetAction" required 
                                                class="flex-1 rounded-xl border-slate-200 bg-slate-50 font-bold text-sm text-slate-700 focus:ring-blue-500 h-12 transition-all cursor-pointer">
                                            <option value="">-- Pilih Kelas Tujuan --</option>
                                            
                                            <optgroup label="Tujuan: Pindah / Naik Kelas">
                                                @foreach($classes as $class)
                                                    @if($class->id != request('from_class_id'))
                                                        <option value="{{ $class->id }}">Pindahkan ke {{ $class->name }}</option>
                                                    @endif
                                                @endforeach
                                            </optgroup>
                                        </select>
                                    </div>
                                </div>
                                
                                {{-- Tombol Submit Eksekusi --}}
                                <button type="button" @click="confirmProcess()" 
                                        class="w-full md:w-auto px-8 py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl shadow-lg transition-all flex items-center justify-center gap-2 h-12 shrink-0 relative z-10">
                                    <i class="ph-bold ph-magic-wand"></i> Eksekusi
                                </button>
                                
                                {{-- Latar dinamis peringatan lulus --}}
                                <div x-show="targetAction === 'alumni'" x-transition.opacity 
                                     class="absolute inset-0 bg-gradient-to-r from-amber-50 to-orange-50/50 pointer-events-none z-0"></div>
                            </div>

                            {{-- TABEL DAFTAR SISWA --}}
                            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                                <div class="overflow-x-auto">
                                    <table class="w-full text-sm text-left text-slate-600">
                                        <thead class="text-xs font-bold text-slate-400 uppercase bg-slate-50 border-b border-slate-100">
                                            <tr>
                                                <th class="px-6 py-4 w-16 text-center">
                                                    {{-- MASTER CHECKBOX --}}
                                                    <input type="checkbox" x-model="checkAll" @change="toggleAll()" 
                                                           class="rounded border-slate-300 text-blue-600 focus:ring-blue-500 w-5 h-5 cursor-pointer shadow-sm">
                                                </th>
                                                <th class="px-6 py-4">Nama Lengkap Siswa</th>
                                                <th class="px-6 py-4">NIS / NISN</th>
                                                <th class="px-6 py-4">Jenis Kelamin</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-50">
                                            @foreach($students as $student)
                                            <tr class="hover:bg-slate-50/50 transition-colors" @click="toggleRow('{{ $student->id }}')">
                                                <td class="px-6 py-4 text-center">
                                                    <input type="checkbox" name="student_ids[]" value="{{ $student->id }}" id="chk-{{ $student->id }}"
                                                           class="student-checkbox rounded border-slate-300 text-blue-600 focus:ring-blue-500 w-5 h-5 cursor-pointer shadow-sm"
                                                           @click.stop="updateCheckAll()">
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-xs text-slate-500 font-bold overflow-hidden shrink-0">
                                                            @if($student->photo_path)
                                                                <img src="{{ asset('storage/'.$student->photo_path) }}" class="w-full h-full object-cover">
                                                            @else
                                                                {{ substr($student->name, 0, 1) }}
                                                            @endif
                                                        </div>
                                                        <span class="font-bold text-slate-800">{{ $student->name }}</span>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 font-mono text-xs text-slate-500">
                                                    {{ $student->nisn ?? $student->student_id }}
                                                </td>
                                                <td class="px-6 py-4">
                                                    <span class="inline-flex px-2 py-1 rounded-lg text-[10px] font-bold {{ $student->gender == 'L' ? 'bg-blue-50 text-blue-600' : 'bg-rose-50 text-rose-600' }}">
                                                        {{ $student->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100 text-xs text-slate-400 font-medium">
                                    Total: <span class="font-bold text-slate-700">{{ count($students) }}</span> siswa ditampilkan.
                                </div>
                            </div>
                        </form>

                    @elseif(request('from_class_id'))
                        {{-- KONDISI JIKA KELAS KOSONG --}}
                        <div class="bg-white rounded-[2.5rem] p-16 text-center shadow-xl shadow-slate-200/50 border border-slate-100">
                            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                                <i class="ph-duotone ph-users-three text-4xl"></i>
                            </div>
                            <h3 class="text-lg font-black text-slate-800 mb-2">Tidak Ada Siswa</h3>
                            <p class="text-slate-500 text-sm max-w-sm mx-auto">Tidak ada siswa aktif yang ditemukan di kelas ini. Mungkin sudah diluluskan atau dipindahkan semua.</p>
                        </div>
                    @else
                        {{-- KONDISI AWAL (BELUM PILIH KELAS) --}}
                        <div class="bg-slate-50/50 rounded-[2.5rem] p-16 text-center border-2 border-dashed border-slate-200 h-full flex flex-col items-center justify-center">
                            <i class="ph-duotone ph-arrow-left text-4xl text-slate-300 mb-4 animate-bounce"></i>
                            <h3 class="text-base font-bold text-slate-700 mb-1">Menunggu Pilihan Kelas</h3>
                            <p class="text-slate-500 text-sm font-medium">Pilih kelas asal di menu sebelah kiri untuk memulai.</p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    {{-- Script Logika Alpine JS & SweetAlert --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('promotionApp', () => ({
                checkAll: true,
                targetAction: '',
                
                init() {
                    // Otomatis centang semua saat halaman diload
                    this.toggleAll();
                },
                
                toggleAll() {
                    const checkboxes = document.querySelectorAll('.student-checkbox');
                    checkboxes.forEach(cb => cb.checked = this.checkAll);
                },
                
                updateCheckAll() {
                    const checkboxes = document.querySelectorAll('.student-checkbox');
                    const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                    this.checkAll = allChecked;
                },

                toggleRow(id) {
                    const cb = document.getElementById('chk-' + id);
                    if(cb) {
                        cb.checked = !cb.checked;
                        this.updateCheckAll();
                    }
                },

                confirmProcess() {
                    // Validasi lokal sebelum submit
                    const checkboxes = document.querySelectorAll('.student-checkbox:checked');
                    if (checkboxes.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Pilih Siswa',
                            text: 'Silakan centang minimal satu siswa untuk diproses.',
                            confirmButtonColor: '#3b82f6',
                            customClass: { popup: 'rounded-3xl' }
                        });
                        return;
                    }

                    if (this.targetAction === '') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Tujuan Belum Dipilih',
                            text: 'Pilih kelas tujuan terlebih dahulu.',
                            confirmButtonColor: '#3b82f6',
                            customClass: { popup: 'rounded-3xl' }
                        });
                        return;
                    }

                    Swal.fire({
                        title: 'Konfirmasi Pemindahan',
                        html: `Anda akan memindahkan <b>${checkboxes.length} siswa</b> ke kelas baru. Yakin ingin melanjutkan?`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3b82f6',
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Ya, Pindahkan',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        customClass: { popup: 'rounded-3xl' }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Tampilkan loading
                            Swal.fire({
                                title: 'Memproses Data...',
                                text: 'Mohon tunggu sebentar',
                                allowOutsideClick: false,
                                didOpen: () => { Swal.showLoading(); },
                                customClass: { popup: 'rounded-3xl' }
                            });
                            // Submit Form
                            document.getElementById('promotionForm').submit();
                        }
                    });
                }
            }));
        });

        // Flash Message SweetAlert
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#10b981',
                    customClass: { popup: 'rounded-3xl border border-emerald-100' }
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#f43f5e',
                    customClass: { popup: 'rounded-3xl border border-rose-100' }
                });
            @endif
        });
    </script>
</x-app-layout>