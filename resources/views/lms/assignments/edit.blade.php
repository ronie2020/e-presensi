<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Edit Tugas') }}
        </h2>
    </x-slot>

    {{-- CUSTOM STYLES FLUENT --}}
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        .fluent-card { box-shadow: 0 1.6px 3.6px 0 rgba(0, 0, 0, 0.132), 0 0.3px 0.9px 0 rgba(0, 0, 0, 0.108); border: 1px solid rgba(0, 0, 0, 0.05); transition: all 0.3s ease; }
        .fluent-modal { box-shadow: 0 25.6px 57.6px 0 rgba(0, 0, 0, 0.22), 0 4.8px 14.4px 0 rgba(0, 0, 0, 0.18); border: 1px solid rgba(0, 0, 0, 0.05); }
    </style>

    <div class="py-8 font-sans text-slate-800 pb-20">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO HEADER ELEVATE --}}
            <div class="relative rounded-xl bg-gradient-to-br from-[#25D0FF] via-[#5295FF] to-[#FFC9B9] p-8 mb-8 text-[#2A3B52] shadow-[0_10px_40px_-10px_rgba(37,208,255,0.4)] overflow-hidden border border-white/40 group animate-enter">
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/30 rounded-full blur-[80px] pointer-events-none group-hover:bg-white/40 transition-all duration-700"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h1 class="text-2xl font-extrabold mb-1 tracking-tight">Edit Penugasan</h1>
                        <p class="text-[#2A3B52]/80 text-sm font-medium">Perbarui informasi, deadline, atau instruksi tugas.</p>
                    </div>
                    <a href="{{ route('lms.assignments.index') }}" class="hidden md:flex items-center gap-2 px-4 py-2 bg-white/40 hover:bg-white/60 rounded-xl text-sm font-bold backdrop-blur-sm transition text-[#2A3B52] border border-white/50 shadow-sm active:scale-95 btn-cancel-confirm">
                        <i class="ph-bold ph-arrow-left"></i> Batal
                    </a>
                </div>
            </div>

            {{-- ERROR BLOCK --}}
            @if ($errors->any())
                <div class="mb-8 bg-[#FDE7E9] border border-[#F4C3C9] p-5 rounded-xl flex items-start gap-4 shadow-sm animate-pulse fluent-card animate-enter">
                    <div class="p-2 bg-white text-[#D13438] rounded-lg shrink-0 border border-[#F4C3C9] shadow-sm">
                        <i class="ph-bold ph-warning-octagon text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-[#D13438] uppercase tracking-wide mb-1">Gagal Menyimpan</h3>
                        <ul class="list-disc list-inside text-sm text-[#D13438] space-y-1 font-medium">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- FORM CARD --}}
            <div class="bg-white rounded-xl fluent-card overflow-hidden animate-enter" style="animation-delay: 100ms">
                <form action="{{ route('lms.assignments.update', $assignment->id) }}" method="POST" id="editAssignmentForm" 
                      x-data="{ 
                          assignmentType: '{{ $assignment->assignment_type }}', 
                          questions: {{ $assignment->assignment_type == 'quiz' ? json_encode($assignment->questions) : '[]' }} 
                      }">
                    @csrf
                    @method('PUT')

                    <div class="p-8 space-y-8">
                        
                        <!-- 1. IDENTITAS TUGAS -->
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-[#F3F9FD] text-[#5295FF] flex items-center justify-center text-xl border border-[#D0E7F8] shadow-sm"><i class="ph-bold ph-pencil-simple"></i></div>
                                <h3 class="text-lg font-black text-[#2A3B52]">Informasi Dasar</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="col-span-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Judul Tugas <span class="text-[#D13438]">*</span></label>
                                    <input type="text" name="title" value="{{ old('title', $assignment->title) }}" required class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-[#2A3B52] focus:ring-[#5295FF] focus:border-[#5295FF] h-12 px-4 transition-colors">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Mata Pelajaran <span class="text-[#D13438]">*</span></label>
                                    <div class="relative">
                                        <select name="subject_id" required class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-[#2A3B52] focus:ring-[#5295FF] focus:border-[#5295FF] h-12 px-4 appearance-none transition-colors cursor-pointer">
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}" {{ $assignment->subject_id == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Deadline <span class="text-[#D13438]">*</span></label>
                                    <input type="datetime-local" name="deadline" value="{{ old('deadline', $assignment->deadline->format('Y-m-d\TH:i')) }}" required class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-[#2A3B52] focus:ring-[#5295FF] focus:border-[#5295FF] h-12 px-4 transition-colors">
                                </div>

                                <div class="col-span-2">
                                    <label class="inline-flex items-center cursor-pointer group">
                                        <div class="relative">
                                            <input type="checkbox" name="allow_late_submission" class="sr-only peer" {{ $assignment->allow_late_submission ? 'checked' : '' }}>
                                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#5295FF]"></div>
                                        </div>
                                        <span class="ml-3 text-sm font-bold text-slate-600 group-hover:text-[#5295FF] transition">Izinkan pengumpulan terlambat</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="h-px bg-slate-100"></div>

                        <!-- 2. INFO TARGET & TIPE (READ ONLY) -->
                        <div class="bg-[#F3F9FD] p-6 rounded-xl border border-[#D0E7F8] flex flex-col md:flex-row gap-6">
                            <div class="flex-1">
                                <label class="block text-xs font-black text-[#5295FF] uppercase tracking-wider mb-2 flex items-center gap-1.5"><i class="ph-fill ph-lock-key text-slate-400"></i> Target Penerima</label>
                                <div class="flex items-center gap-2 text-[#2A3B52] font-bold bg-white px-4 py-3 rounded-lg border border-slate-200 shadow-sm">
                                    <i class="ph-fill ph-users-three text-[#5295FF] text-lg"></i>
                                    @if($isBulk)
                                        Semua Kelas {{ $assignment->schoolClass ? substr($assignment->schoolClass->name, 0, 1) : 'Jenjang' }} (Mode Massal)
                                    @else
                                        Kelas {{ $assignment->schoolClass->name ?? '-' }}
                                    @endif
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1.5 italic">*Target kelas tidak dapat diubah saat mengedit.</p>
                            </div>
                            <div class="flex-1">
                                <label class="block text-xs font-black text-[#5295FF] uppercase tracking-wider mb-2 flex items-center gap-1.5"><i class="ph-fill ph-lock-key text-slate-400"></i> Jenis Tugas</label>
                                <div class="flex items-center gap-2 text-[#2A3B52] font-bold bg-white px-4 py-3 rounded-lg border border-slate-200 shadow-sm">
                                    @if($assignment->assignment_type == 'file_upload')
                                        <i class="ph-duotone ph-upload-simple text-[#5295FF] text-lg"></i> Upload File
                                    @elseif($assignment->assignment_type == 'quiz')
                                        <i class="ph-duotone ph-brain text-purple-500 text-lg"></i> Kuis Online
                                    @else
                                        <i class="ph-duotone ph-link text-[#D83B01] text-lg"></i> Link Eksternal
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="h-px bg-slate-100"></div>

                        <!-- 3. KONTEN DINAMIS -->
                        <div class="bg-slate-50 rounded-xl p-6 border border-slate-100">
                            
                            <!-- A. JIKA UPLOAD FILE -->
                            <div x-show="assignmentType === 'file_upload'">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Instruksi / Soal</label>
                                <textarea name="description" rows="5" class="w-full rounded-xl border-slate-200 bg-white focus:ring-[#5295FF] focus:border-[#5295FF] p-4 text-[#2A3B52] font-medium transition-colors shadow-sm">{{ old('description', $assignment->description) }}</textarea>
                            </div>

                            <!-- B. JIKA LINK EKSTERNAL -->
                            <div x-show="assignmentType === 'link'">
                                <div class="mb-4">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">URL Link Tugas <span class="text-[#D13438]">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-link"></i></div>
                                        <input type="url" name="link_url" value="{{ old('link_url', $assignment->link_url) }}" class="w-full rounded-xl border-slate-200 bg-white pl-10 font-bold text-[#5295FF] focus:ring-[#5295FF] h-12 transition-colors shadow-sm">
                                    </div>
                                </div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Instruksi Tambahan</label>
                                <textarea name="description" rows="3" class="w-full rounded-xl border-slate-200 bg-white focus:ring-[#5295FF] focus:border-[#5295FF] p-4 font-medium transition-colors shadow-sm">{{ old('description', $assignment->description) }}</textarea>
                            </div>

                            <!-- C. JIKA KUIS ONLINE -->
                            <div x-show="assignmentType === 'quiz'">
                                <div class="mb-6 flex flex-col md:flex-row gap-4">
                                    <div class="flex-1">
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Instruksi Kuis</label>
                                        <textarea name="description" rows="2" class="w-full rounded-xl border-slate-200 bg-white focus:ring-purple-500 focus:border-purple-500 p-3 transition-colors shadow-sm">{{ old('description', $assignment->description) }}</textarea>
                                    </div>
                                    <div class="w-full md:w-1/3">
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Durasi (Menit) <span class="text-[#D13438]">*</span></label>
                                        <div class="relative">
                                            <input type="number" name="duration_minutes" value="{{ old('duration_minutes', $assignment->duration_minutes) }}" class="w-full rounded-xl border-slate-200 bg-white font-bold text-[#2A3B52] focus:ring-purple-500 focus:border-purple-500 h-11 pl-4 pr-10 transition-colors shadow-sm">
                                            <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400 text-xs font-bold">MIN</div>
                                        </div>
                                    </div>
                                </div>

                                {{-- INFO EDIT SOAL --}}
                                <div class="bg-[#FFEFD6] border border-[#FFD8A8] rounded-xl p-4 flex items-start gap-3 mb-4 shadow-sm">
                                    <div class="p-2 bg-white text-[#D83B01] rounded-lg shrink-0 shadow-sm border border-[#FFD8A8]"><i class="ph-bold ph-warning"></i></div>
                                    <div>
                                        <h4 class="font-bold text-[#D83B01] text-sm">Peringatan Edit Soal</h4>
                                        <p class="text-xs text-[#D83B01]/80 mt-1 font-medium">
                                            Saat ini fitur edit <b>detail butir soal</b> belum tersedia di halaman ini. Jika ada kesalahan fatal pada soal, disarankan untuk membuat tugas baru agar nilai siswa tidak rusak. Anda hanya dapat mengubah instruksi dan durasi waktu di sini.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- FOOTER ACTIONS -->
                    <div class="bg-slate-50 px-8 py-6 flex justify-end gap-3 border-t border-slate-100">
                        <a href="{{ route('lms.assignments.index') }}" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-100 transition text-center text-sm btn-cancel-confirm active:scale-95 shadow-sm">Batal</a>
                        
                        <button type="submit" class="px-8 py-3 bg-[#2A3B52] text-white font-bold rounded-xl shadow-md hover:bg-[#182436] transition flex items-center justify-center gap-2 text-sm border border-transparent active:scale-95">
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
            
            // 1. Proteksi Tombol Batal/Kembali
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
                        confirmButtonColor: '#64748b', 
                        cancelButtonColor: '#cbd5e1', 
                        confirmButtonText: 'Ya, Batalkan',
                        cancelButtonText: 'Lanjut Edit',
                        customClass: {
                            popup: 'rounded-xl fluent-modal font-sans border-0',
                            confirmButton: 'rounded-lg px-4 py-2 font-bold shadow-sm',
                            cancelButton: 'rounded-lg px-4 py-2 font-bold text-slate-600'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = href;
                        }
                    });
                });
            });

            // 2. Loading saat Submit
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
                        didOpen: () => {
                            Swal.showLoading();
                        },
                        customClass: {
                            popup: 'rounded-xl fluent-modal font-sans border-0',
                            title: 'text-xl font-bold text-[#2A3B52]'
                        }
                    });

                    setTimeout(() => {
                        this.submit();
                    }, 500);
                });
            }
        });
    </script>
    @endpush
</x-app-layout>