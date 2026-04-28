<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans text-elevate-dark relative overflow-hidden">
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <a href="{{ route('library.circulation.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-elevate-dark/60 hover:text-elevate-primary mb-6 transition-colors group">
                <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Sirkulasi
            </a>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-start gap-3 shadow-sm">
                    <i class="ph-fill ph-warning-circle text-rose-500 text-xl mt-0.5"></i>
                    <div>
                        <h3 class="text-sm font-bold text-rose-700">Gagal Memproses Distribusi</h3>
                        <ul class="list-disc list-inside text-xs text-rose-600 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3 shadow-sm">
                    <i class="ph-fill ph-check-circle text-emerald-500 text-xl"></i>
                    <p class="text-sm font-bold text-emerald-700">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-start gap-3 shadow-sm animate-fade-in-down">
                    <i class="ph-fill ph-warning-circle text-rose-500 text-xl mt-0.5"></i>
                    <div>
                        <h3 class="text-sm font-bold text-rose-700">Distribusi Terhenti</h3>
                        <p class="text-xs font-bold text-rose-600 mt-1">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                
                <div class="bg-elevate-gradient-main p-8 text-elevate-dark relative overflow-hidden border-b border-white/60">
                    <div class="absolute -right-6 -top-6 text-white/40 text-9xl pointer-events-none mix-blend-overlay">
                        <i class="ph-fill ph-barcode"></i>
                    </div>
                    <div class="relative z-10 flex items-center gap-3 mb-2">
                        <span class="px-3 py-1 bg-white/50 rounded-lg text-[10px] font-black uppercase tracking-widest border border-white/60 backdrop-blur-md shadow-sm">
                            Mode Pindai Eksemplar
                        </span>
                    </div>
                    <h2 class="text-3xl font-black relative z-10 tracking-tight">Distribusi Buku Paket</h2>
                    <p class="text-elevate-dark/80 text-sm font-semibold relative z-10 mt-2 max-w-xl leading-relaxed">
                        Pindai barcode unik dari stiker masing-masing fisik buku dan pasangkan dengan nama siswa. Ini mencegah siswa menukar buku saat pengembalian.
                    </p>
                </div>

                <div class="p-8 bg-elevate-surface">
                    <form action="{{ route('library.circulation.storeBulk') }}" method="POST" id="bulkBorrowForm">
                        @csrf
                        
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            
                            {{-- KOLOM KIRI: SETTING --}}
                            <div class="lg:col-span-1 space-y-6">
                                <div class="bg-elevate-soft p-6 rounded-[2rem] border border-slate-200">
                                    <h3 class="text-xs font-black text-elevate-dark/60 uppercase tracking-widest mb-6 flex items-center gap-2">
                                        <i class="ph-fill ph-sliders text-elevate-primary text-lg"></i> Pengaturan
                                    </h3>
                                    
                                    <div class="space-y-5">
                                        <div>
                                            <label class="block text-xs font-bold text-elevate-primary uppercase mb-2 ml-1">Pilih Kelas <span class="text-rose-500">*</span></label>
                                            <div class="relative group">
                                                <i class="ph-bold ph-users-three absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-elevate-primary transition-colors"></i>
                                                <select name="class_id" id="class_id" required class="w-full pl-11 pr-10 py-3.5 rounded-2xl border-slate-200 bg-white font-bold text-elevate-dark focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent transition-all shadow-sm">
                                                    <option value="">-- Pilih Kelas --</option>
                                                    @foreach($classes as $class)
                                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-elevate-primary uppercase mb-2 ml-1">Buku Paket <span class="text-rose-500">*</span></label>
                                            <div class="relative group">
                                                <i class="ph-bold ph-books absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-elevate-primary transition-colors"></i>
                                                <select name="book_id" id="book_id" required class="w-full pl-11 pr-10 py-3.5 rounded-2xl border-slate-200 bg-white font-bold text-elevate-dark focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent transition-all shadow-sm">
                                                    <option value="">-- Pilih Buku Paket --</option>
                                                    @foreach($textbooks as $book)
                                                        <option value="{{ $book->id }}" data-stock="{{ $book->stock }}">
                                                            {{ $book->title }} (Stok: {{ $book->stock }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-elevate-primary uppercase mb-2 ml-1">Tenggat Waktu <span class="text-rose-500">*</span></label>
                                            <div class="relative group">
                                                <i class="ph-bold ph-calendar-blank absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-elevate-primary transition-colors"></i>
                                                @php $defaultDueDate = \Carbon\Carbon::create(date('Y') + 1, 6, 15)->format('Y-m-d'); @endphp
                                                <input type="date" name="due_date" value="{{ $defaultDueDate }}" required class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-slate-200 bg-white font-bold text-elevate-dark focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent transition-all shadow-sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- KOLOM KANAN: DAFTAR SISWA & SCAN --}}
                            <div class="lg:col-span-2 flex flex-col">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-black text-elevate-dark">Daftar Penerima & Scan Buku</h3>
                                    <div class="px-3 py-1.5 bg-elevate-peach-light/40 text-elevate-primary rounded-lg border border-elevate-peach/30 text-xs font-bold flex items-center gap-2 shadow-sm">
                                        <i class="ph-bold ph-barcode"></i> Terisi: <span id="scannedCount">0</span> Siswa
                                    </div>
                                </div>

                                <div class="bg-white border border-slate-200 rounded-[2rem] flex-1 overflow-hidden flex flex-col shadow-sm relative min-h-[400px]">
                                    
                                    <div id="emptyState" class="absolute inset-0 flex flex-col items-center justify-center bg-elevate-soft/50 z-10 transition-opacity">
                                        <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center shadow-sm mb-4 border border-slate-200 text-elevate-primary">
                                            <i class="ph-duotone ph-student text-4xl"></i>
                                        </div>
                                        <h4 class="font-black text-elevate-dark/60">Pilih Kelas Terlebih Dahulu</h4>
                                    </div>

                                    <div id="loadingState" class="absolute inset-0 flex flex-col items-center justify-center bg-white/80 backdrop-blur-sm z-20 hidden">
                                        <i class="ph-bold ph-spinner animate-spin text-4xl text-elevate-primary mb-2"></i>
                                    </div>

                                    <div class="overflow-y-auto custom-scrollbar flex-1">
                                        <table class="w-full text-left border-collapse" id="studentTable">
                                            <thead class="sticky top-0 z-10">
                                                <tr class="bg-elevate-soft text-xs uppercase tracking-wider text-elevate-primary font-bold border-b border-slate-200">
                                                    <th class="px-6 py-4">Nama Siswa</th>
                                                    <th class="px-6 py-4 w-1/2">Scan Barcode (Stiker Buku)</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-slate-100" id="studentListContainer">
                                                <!-- Via JS -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-slate-100 flex items-center justify-between">
                            <p class="text-xs text-elevate-dark/50 font-medium max-w-sm">
                                <i class="ph-fill ph-info text-elevate-primary"></i> Pastikan tidak ada barcode merah (ganda). Tekan Enter setelah scan untuk lanjut ke baris berikutnya.
                            </p>
                            <button type="button" id="btnSubmit" onclick="confirmBulkSubmit()" disabled class="px-8 py-3.5 bg-elevate-dark text-white font-bold rounded-2xl hover:bg-elevate-primary shadow-lg shadow-elevate-dark/20 transition-all transform hover:-translate-y-0.5 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed border border-transparent">
                                <i class="ph-bold ph-paper-plane-right text-lg"></i> Simpan Distribusi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const classSelect = document.getElementById('class_id');
            const studentContainer = document.getElementById('studentListContainer');
            const emptyState = document.getElementById('emptyState');
            const loadingState = document.getElementById('loadingState');
            const scannedCountDisplay = document.getElementById('scannedCount');
            const btnSubmit = document.getElementById('btnSubmit');

            classSelect.addEventListener('change', async function() {
                const classId = this.value;
                if(!classId) {
                    emptyState.classList.remove('hidden');
                    studentContainer.innerHTML = '';
                    updateCounter();
                    return;
                }

                emptyState.classList.add('hidden');
                loadingState.classList.remove('hidden');

                try {
                    const response = await fetch(`{{ url('/library/tools/api/students-by-class') }}/${classId}`);
                    const data = await response.json();
                    
                    if(data.success && data.students.length > 0) {
                        renderStudents(data.students);
                    } else {
                        studentContainer.innerHTML = `<tr><td colspan="2" class="px-6 py-10 text-center text-elevate-dark/40">Data siswa kosong.</td></tr>`;
                    }
                } catch (error) {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal memuat data.', confirmButtonColor: '#2c3f61', customClass: { popup: 'rounded-[2rem]' } });
                } finally {
                    loadingState.classList.add('hidden');
                    updateCounter();
                }
            });

            function renderStudents(students) {
                let html = '';
                students.forEach((student, index) => {
                    html += `
                        <tr class="hover:bg-elevate-soft/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="font-bold text-elevate-dark text-sm group-hover:text-elevate-primary">${student.name}</div>
                                <div class="text-xs text-elevate-dark/50 font-mono">${student.nisn || student.student_id || '-'}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="relative">
                                    <i class="ph-bold ph-barcode absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                    <input type="text" name="item_codes[${student.id}]" class="item-code-input w-full pl-9 pr-4 py-2.5 rounded-xl border-slate-200 bg-elevate-soft focus:bg-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 font-mono font-bold text-elevate-dark text-sm transition-all shadow-sm" placeholder="Scan barcode stiker..." oninput="updateCounter()" onkeydown="focusNext(event, ${index})">
                                </div>
                            </td>
                        </tr>
                    `;
                });
                studentContainer.innerHTML = html;
            }

            window.focusNext = function(event, currentIndex) {
                if(event.key === 'Enter') {
                    event.preventDefault();
                    const inputs = document.querySelectorAll('.item-code-input');
                    if(inputs[currentIndex + 1]) {
                        inputs[currentIndex + 1].focus();
                    }
                }
            }

            window.updateCounter = function() {
                const inputs = document.querySelectorAll('.item-code-input');
                let filledCount = 0;
                let scannedCodes = [];
                let hasDuplicate = false;

                inputs.forEach(input => { 
                    const val = input.value.trim();
                    if(val !== '') { 
                        filledCount++; 
                        
                        if(scannedCodes.includes(val)) {
                            hasDuplicate = true;
                            input.classList.add('border-rose-500', 'bg-rose-50', 'text-rose-600', 'focus:border-rose-500', 'focus:ring-rose-500');
                            input.classList.remove('border-slate-200', 'bg-elevate-soft', 'text-elevate-dark', 'focus:border-elevate-accent', 'focus:ring-elevate-accent/20');
                        } else {
                            scannedCodes.push(val);
                            input.classList.remove('border-rose-500', 'bg-rose-50', 'text-rose-600', 'focus:border-rose-500', 'focus:ring-rose-500');
                            input.classList.add('border-slate-200', 'bg-elevate-soft', 'text-elevate-dark', 'focus:border-elevate-accent', 'focus:ring-elevate-accent/20');
                        }
                    } else {
                        input.classList.remove('border-rose-500', 'bg-rose-50', 'text-rose-600', 'focus:border-rose-500', 'focus:ring-rose-500');
                        input.classList.add('border-slate-200', 'bg-elevate-soft', 'text-elevate-dark', 'focus:border-elevate-accent', 'focus:ring-elevate-accent/20');
                    }
                });
                
                scannedCountDisplay.innerText = filledCount;
                const bookSelected = document.getElementById('book_id').value !== '';
                
                if (hasDuplicate) {
                    btnSubmit.disabled = true;
                    btnSubmit.innerHTML = '<i class="ph-bold ph-warning text-lg"></i> Ada Barcode Ganda';
                    btnSubmit.classList.replace('bg-elevate-dark', 'bg-rose-600');
                    btnSubmit.classList.replace('hover:bg-elevate-primary', 'hover:bg-rose-700');
                } else {
                    btnSubmit.disabled = filledCount === 0 || !bookSelected;
                    btnSubmit.innerHTML = '<i class="ph-bold ph-paper-plane-right text-lg"></i> Simpan Distribusi';
                    btnSubmit.classList.replace('bg-rose-600', 'bg-elevate-dark');
                    btnSubmit.classList.replace('hover:bg-rose-700', 'hover:bg-elevate-primary');
                }
            }
            document.getElementById('book_id').addEventListener('change', updateCounter);
        });

        window.confirmBulkSubmit = function() {
            const inputs = document.querySelectorAll('.item-code-input');
            let filledCount = 0;
            inputs.forEach(input => { if(input.value.trim() !== '') filledCount++; });

            const bookSelect = document.getElementById('book_id');
            const stockAvailable = parseInt(bookSelect.options[bookSelect.selectedIndex].getAttribute('data-stock'));

            if (filledCount > stockAvailable) {
                Swal.fire({ icon: 'error', title: 'Stok Kurang!', text: `Anda men-scan ${filledCount} buku, tapi stok tersisa ${stockAvailable}.`, confirmButtonColor: '#2c3f61', customClass: { popup: 'rounded-[2rem]' } });
                return;
            }

            Swal.fire({
                title: 'Simpan Distribusi?',
                html: `Memproses peminjaman untuk <strong class="text-elevate-primary">${filledCount} Siswa</strong>.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan!',
                confirmButtonColor: '#2c3f61',
                customClass: { popup: 'rounded-[2rem]' }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, customClass: { popup: 'rounded-[2rem]' }, didOpen: () => Swal.showLoading() });
                    document.getElementById('bulkBorrowForm').submit();
                }
            });
        }
    </script>
</x-app-layout>