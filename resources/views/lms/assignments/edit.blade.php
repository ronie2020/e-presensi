<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-elevate-dark leading-tight">
            {{ __('Edit Tugas') }}
        </h2>
    </x-slot>

    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>

    <div class="py-8 sm:py-10 font-sans text-elevate-dark bg-elevate-surface min-h-screen relative overflow-hidden pb-20">
        
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- HERO HEADER ELEVATE --}}
            <div class="relative rounded-[2rem] bg-gradient-to-r from-elevate-accent via-elevate-peach-light to-elevate-peach p-8 md:p-10 mb-8 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden border border-white/60 group animate-enter">
                <div class="absolute -top-10 -left-10 w-56 h-56 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/40 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <h1 class="text-3xl md:text-4xl font-black mb-2 tracking-tight">Edit Penugasan</h1>
                        <p class="text-elevate-dark/80 text-sm font-semibold max-w-lg leading-relaxed">Perbarui informasi, deadline, atau instruksi tugas.</p>
                    </div>
                    <a href="{{ route('lms.assignments.index') }}" class="w-full md:w-auto inline-flex justify-center items-center gap-2 px-6 py-3.5 bg-white/60 hover:bg-white rounded-xl text-sm font-bold backdrop-blur-md transition-colors text-elevate-dark border border-white/60 shadow-sm active:scale-95 btn-cancel-confirm shrink-0">
                        <i class="ph-bold ph-arrow-left"></i> Batal
                    </a>
                </div>
            </div>

            {{-- ERROR BLOCK --}}
            @if ($errors->any())
                <div class="mb-8 bg-[#FDE7E9] border border-[#F4C3C9] p-5 rounded-[1.5rem] flex items-start gap-4 shadow-sm animate-pulse animate-enter">
                    <div class="w-10 h-10 bg-white text-[#D13438] rounded-xl shrink-0 border border-[#F4C3C9] shadow-sm flex items-center justify-center">
                        <i class="ph-bold ph-warning-octagon text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xs font-black text-[#D13438] uppercase tracking-wider mb-1 mt-1">Gagal Menyimpan</h3>
                        <ul class="list-disc list-inside text-sm text-[#D13438] space-y-1 font-bold">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- FORM CARD --}}
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden animate-enter" style="animation-delay: 100ms">
                <form action="{{ route('lms.assignments.update', $assignment->id) }}" method="POST" id="editAssignmentForm" 
                      x-data="{ 
                          assignmentType: '{{ $assignment->assignment_type }}', 
                          questions: {{ $assignment->assignment_type == 'quiz' ? json_encode($assignment->questions) : '[]' }} 
                      }">
                    @csrf
                    @method('PUT')

                    <div class="p-6 md:p-10 space-y-10">
                        
                        <!-- 1. IDENTITAS TUGAS -->
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-12 h-12 rounded-xl bg-elevate-soft text-elevate-primary flex items-center justify-center text-2xl border border-slate-200 shadow-sm"><i class="ph-bold ph-pencil-simple"></i></div>
                                <h3 class="text-xl font-black text-elevate-dark">Informasi Dasar</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="col-span-2">
                                    <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Judul Tugas <span class="text-[#D13438]">*</span></label>
                                    <input type="text" name="title" value="{{ old('title', $assignment->title) }}" required class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-black text-elevate-dark focus:bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent h-14 px-5 transition-colors shadow-sm">
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Mata Pelajaran <span class="text-[#D13438]">*</span></label>
                                    <div class="relative group">
                                        <select name="subject_id" required class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-bold text-elevate-dark focus:bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent h-14 px-5 appearance-none transition-colors cursor-pointer shadow-sm">
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}" {{ $assignment->subject_id == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-500 group-focus-within:text-elevate-primary"><i class="ph-bold ph-caret-down text-lg"></i></div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Deadline <span class="text-[#D13438]">*</span></label>
                                    <input type="datetime-local" name="deadline" value="{{ old('deadline', $assignment->deadline->format('Y-m-d\TH:i')) }}" required class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-bold text-elevate-dark focus:bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent h-14 px-5 transition-colors shadow-sm">
                                </div>

                                <div class="col-span-2">
                                    <label class="inline-flex items-center cursor-pointer group">
                                        <div class="relative">
                                            <input type="checkbox" name="allow_late_submission" class="sr-only peer" {{ $assignment->allow_late_submission ? 'checked' : '' }}>
                                            <div class="w-12 h-7 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-elevate-primary shadow-inner"></div>
                                        </div>
                                        <span class="ml-3 text-sm font-bold text-slate-500 group-hover:text-elevate-primary transition-colors">Izinkan pengumpulan terlambat</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="h-px bg-slate-100"></div>

                        <!-- 2. INFO TARGET & TIPE (READ ONLY) -->
                        <div class="bg-elevate-soft/50 p-6 md:p-8 rounded-[2rem] border border-slate-100 flex flex-col md:flex-row gap-6">
                            <div class="flex-1">
                                <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-3 flex items-center gap-1.5"><i class="ph-fill ph-lock-key text-slate-400 text-sm"></i> Target Penerima</label>
                                <div class="flex items-center gap-3 text-elevate-dark font-black bg-white px-5 py-4 rounded-xl border border-slate-200 shadow-sm text-sm">
                                    <i class="ph-fill ph-users-three text-elevate-primary text-xl"></i>
                                    @if($assignment->is_bulk)
                                        Semua Kelas {{ $assignment->schoolClass ? substr($assignment->schoolClass->name, 0, 1) : 'Jenjang' }} (Mode Massal)
                                    @else
                                        Kelas {{ $assignment->schoolClass->name ?? '-' }}
                                    @endif
                                </div>
                                <p class="text-[10px] font-bold text-slate-400 mt-2 italic">*Target kelas tidak dapat diubah saat mengedit.</p>
                            </div>
                            <div class="flex-1">
                                <label class="block text-[10px] font-black text-elevate-primary uppercase tracking-widest mb-3 flex items-center gap-1.5"><i class="ph-fill ph-lock-key text-slate-400 text-sm"></i> Jenis Tugas</label>
                                <div class="flex items-center gap-3 text-elevate-dark font-black bg-white px-5 py-4 rounded-xl border border-slate-200 shadow-sm text-sm">
                                    @if($assignment->assignment_type == 'file_upload')
                                        <i class="ph-duotone ph-upload-simple text-elevate-primary text-xl"></i> Upload File
                                    @elseif($assignment->assignment_type == 'quiz')
                                        <i class="ph-duotone ph-brain text-purple-500 text-xl"></i> Kuis Online
                                    @else
                                        <i class="ph-duotone ph-link text-[#D83B01] text-xl"></i> Link Eksternal
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="h-px bg-slate-100"></div>

                        <!-- 3. KONTEN DINAMIS (FIX DISABLED) -->
                        <div class="bg-slate-50/50 rounded-[2rem] p-6 md:p-8 border border-slate-100">
                            
                            <!-- A. JIKA UPLOAD FILE -->
                            <div x-show="assignmentType === 'file_upload'">
                                <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Instruksi / Soal <span class="text-[#D13438]">*</span></label>
                                <textarea name="description" rows="5" 
                                          :required="assignmentType === 'file_upload'"
                                          :disabled="assignmentType !== 'file_upload'"
                                          class="w-full rounded-2xl border-slate-200 bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent p-5 text-elevate-dark font-medium transition-colors shadow-sm">{{ old('description', $assignment->description) }}</textarea>
                            </div>

                            <!-- B. JIKA LINK EKSTERNAL -->
                            <div x-show="assignmentType === 'link'">
                                <div class="mb-5">
                                    <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">URL Link Tugas <span class="text-[#D13438]">*</span></label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-elevate-primary"><i class="ph-bold ph-link text-lg"></i></div>
                                        <input type="url" name="link_url" value="{{ old('link_url', $assignment->link_url) }}" 
                                               :required="assignmentType === 'link'"
                                               :disabled="assignmentType !== 'link'"
                                               class="w-full rounded-2xl border-slate-200 bg-white pl-12 font-bold text-elevate-primary focus:ring-elevate-accent/30 focus:border-elevate-accent h-14 transition-colors shadow-sm">
                                    </div>
                                </div>
                                <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Instruksi Tambahan <span class="text-[#D13438]">*</span></label>
                                <textarea name="description" rows="4" 
                                          :required="assignmentType === 'link'"
                                          :disabled="assignmentType !== 'link'"
                                          class="w-full rounded-2xl border-slate-200 bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent p-5 font-medium transition-colors shadow-sm">{{ old('description', $assignment->description) }}</textarea>
                            </div>

                            <!-- C. JIKA KUIS ONLINE -->
                            <div x-show="assignmentType === 'quiz'">
                                <div class="mb-8 flex flex-col md:flex-row gap-5">
                                    <div class="flex-1">
                                        <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Instruksi Kuis <span class="text-[#D13438]">*</span></label>
                                        <textarea name="description" rows="2" 
                                                  :required="assignmentType === 'quiz'"
                                                  :disabled="assignmentType !== 'quiz'"
                                                  class="w-full rounded-2xl border-slate-200 bg-white focus:ring-purple-500/30 focus:border-purple-500 p-4 transition-colors shadow-sm">{{ old('description', $assignment->description) }}</textarea>
                                    </div>
                                    <div class="w-full md:w-1/3">
                                        <label class="block text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-2 ml-1">Durasi (Menit) <span class="text-[#D13438]">*</span></label>
                                        <div class="relative group">
                                            <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $assignment->duration_minutes) }}" 
                                                   :required="assignmentType === 'quiz'"
                                                   :disabled="assignmentType !== 'quiz'"
                                                   class="w-full rounded-2xl border-slate-200 bg-white font-black text-elevate-dark focus:ring-purple-500/30 focus:border-purple-500 h-14 pl-5 pr-12 transition-colors shadow-sm">
                                            <div class="absolute inset-y-0 right-5 flex items-center pointer-events-none text-slate-400 text-[10px] font-black tracking-widest">MIN</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- INFO EDIT SOAL --}}
                                <div class="bg-[#FFEFD6] border border-[#FFD8A8] rounded-2xl p-5 flex items-start gap-4 shadow-sm mb-4">
                                    <div class="p-2.5 bg-white text-[#D83B01] rounded-xl shrink-0 shadow-sm border border-[#FFD8A8]"><i class="ph-bold ph-warning text-xl"></i></div>
                                    <div>
                                        <h4 class="font-black text-[#D83B01] text-sm">Peringatan Edit Soal</h4>
                                        <p class="text-xs text-[#D83B01]/80 mt-1.5 font-bold leading-relaxed">
                                            Saat ini fitur edit <b>detail butir soal</b> belum tersedia di halaman ini. Jika ada kesalahan fatal pada soal, disarankan untuk membuat tugas baru agar nilai siswa tidak rusak. Anda hanya dapat mengubah instruksi dan durasi waktu di sini.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- FOOTER ACTIONS -->
                    <div class="bg-elevate-soft/30 px-6 py-6 md:px-10 md:py-8 flex flex-col sm:flex-row justify-end gap-4 border-t border-slate-100">
                        <a href="{{ route('lms.assignments.index') }}" class="w-full sm:w-auto px-8 py-4 bg-white border-2 border-slate-200 text-elevate-dark font-bold rounded-2xl hover:bg-elevate-soft transition-colors text-center text-sm btn-cancel-confirm active:scale-95 shadow-sm">Batal</a>
                        
                        <button type="submit" class="w-full sm:w-auto px-8 py-4 bg-elevate-dark text-white font-bold rounded-2xl shadow-lg shadow-elevate-dark/30 hover:bg-elevate-primary transition-all flex items-center justify-center gap-2 text-sm border border-transparent active:scale-95">
                            <i class="ph-bold ph-check-circle text-lg"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- SCRIPT SWEETALERT2 --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cancelButtons = document.querySelectorAll('.btn-cancel-confirm');
            cancelButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const href = this.getAttribute('href');
                    Swal.fire({
                        title: 'Batalkan Edit?',
                        text: "Perubahan yang belum disimpan akan hilang.",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#2c3f61', 
                        cancelButtonColor: '#e5eff5', 
                        confirmButtonText: 'Ya, Batalkan',
                        cancelButtonText: '<span class="text-elevate-dark">Lanjut Edit</span>',
                        customClass: { popup: 'rounded-[2rem] font-sans border-0 shadow-2xl', confirmButton: 'rounded-xl px-5 py-2.5 font-bold shadow-sm', cancelButton: 'rounded-xl px-5 py-2.5 font-bold shadow-sm' }
                    }).then((result) => { if (result.isConfirmed) { window.location.href = href; } });
                });
            });

            const form = document.getElementById('editAssignmentForm');
            if(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (!this.checkValidity()) { 
                        this.reportValidity(); 
                        return; 
                    }
                    Swal.fire({
                        title: 'Menyimpan Perubahan...',
                        text: 'Mohon tunggu sebentar.',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => { Swal.showLoading(); },
                        customClass: { popup: 'rounded-[2rem] font-sans border-0 shadow-2xl', title: 'text-xl font-black text-elevate-dark' }
                    });
                    setTimeout(() => { this.submit(); }, 500);
                });
            }
        });
    </script>
    @endpush
</x-app-layout>