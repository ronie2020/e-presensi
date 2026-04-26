<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Edit Materi') }}
        </h2>
    </x-slot>

    {{-- CUSTOM STYLES FLUENT --}}
    <style>
        .fluent-card { box-shadow: 0 1.6px 3.6px 0 rgba(0, 0, 0, 0.132), 0 0.3px 0.9px 0 rgba(0, 0, 0, 0.108); border: 1px solid rgba(0, 0, 0, 0.05); transition: all 0.3s ease; }
        .fluent-modal { box-shadow: 0 25.6px 57.6px 0 rgba(0, 0, 0, 0.22), 0 4.8px 14.4px 0 rgba(0, 0, 0, 0.18); border: 1px solid rgba(0, 0, 0, 0.05); }
    </style>

    <div class="py-8 font-sans text-slate-800">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO HEADER ELEVATE --}}
            <div class="relative rounded-xl bg-gradient-to-br from-[#25D0FF] via-[#5295FF] to-[#FFC9B9] p-8 mb-8 text-[#2A3B52] shadow-[0_10px_40px_-10px_rgba(37,208,255,0.4)] overflow-hidden border border-white/40">
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/30 rounded-full blur-[80px] pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h1 class="text-2xl font-extrabold mb-1 tracking-tight">Edit Materi Pelajaran</h1>
                        <p class="text-[#2A3B52]/80 text-sm font-medium">Perbarui informasi, file, atau lampiran materi ini.</p>
                    </div>
                    <a href="{{ route('lms.materials.index') }}" class="flex items-center justify-center gap-2 px-4 py-2 bg-white/40 hover:bg-white/60 rounded-xl text-sm font-bold backdrop-blur-sm transition text-[#2A3B52] border border-white/50 btn-cancel-confirm w-full md:w-auto shadow-sm active:scale-95">
                        <i class="ph-bold ph-arrow-left"></i> Batal
                    </a>
                </div>
            </div>

            {{-- ERROR BLOCK --}}
            @if ($errors->any())
                <div class="mb-8 bg-[#FDE7E9] border border-[#F4C3C9] p-5 rounded-xl flex items-start gap-4 shadow-sm fluent-card animate-pulse">
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

            {{-- LOGIKA PENGAMAN STATE ALPINE.JS UNTUK LAMPIRAN BARU --}}
            @php
                $oldNewAttachments = old('new_attachments', []);
                foreach($oldNewAttachments as $k => &$att) {
                    if(!isset($att['id'])) $att['id'] = time() + $k;
                }
            @endphp
            
            {{-- FORM CARD --}}
            <div class="bg-white rounded-xl fluent-card overflow-hidden">
                <form action="{{ route('lms.materials.update', $material->id) }}" method="POST" enctype="multipart/form-data" 
                      x-data="{ attachments: {{ json_encode($oldNewAttachments) }} }"
                      id="updateForm">
                    @csrf
                    @method('PUT')

                    <div class="p-8 space-y-8">
                        
                        <!-- BAGIAN 1: INFORMASI UTAMA -->
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-[#F3F9FD] text-[#5295FF] border border-[#D0E7F8] flex items-center justify-center text-xl shadow-sm"><i class="ph-bold ph-pencil-simple"></i></div>
                                <h3 class="text-lg font-black text-[#2A3B52]">Informasi Dasar</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="col-span-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Judul Materi <span class="text-[#D13438]">*</span></label>
                                    <input type="text" name="title" value="{{ old('title', $material->title) }}" required 
                                           class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-[#2A3B52] focus:ring-[#5295FF] focus:border-[#5295FF] h-12 px-4 transition-colors">
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Mata Pelajaran <span class="text-[#D13438]">*</span></label>
                                    <div class="relative">
                                        <select name="subject_id" required class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-[#2A3B52] focus:ring-[#5295FF] focus:border-[#5295FF] h-12 px-4 appearance-none cursor-pointer">
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}" {{ old('subject_id', $material->subject_id) == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Kelas <span class="text-[#D13438]">*</span></label>
                                    <div class="relative">
                                        <select name="class_id" required class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-[#2A3B52] focus:ring-[#5295FF] focus:border-[#5295FF] h-12 px-4 appearance-none cursor-pointer">
                                            @foreach($classes as $class)
                                                <option value="{{ $class->id }}" {{ old('class_id', $material->class_id) == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-1.5 flex items-center gap-1 italic"><i class="ph-bold ph-info"></i> *Jika ini materi jenjang, pengeditan ini hanya mengubah untuk kelas ini saja.</p>
                                </div>
                            </div>
                        </div>

                        <div class="h-px bg-slate-100"></div>

                        <!-- BAGIAN 2: DESKRIPSI -->
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">
                                Pengantar & Resume Materi
                            </label>
                            <div class="relative">
                                <textarea name="resume" rows="6" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:ring-[#5295FF] focus:border-[#5295FF] shadow-sm p-4 text-[#2A3B52] leading-relaxed font-medium transition-colors">{{ old('resume', $material->resume) }}</textarea>
                                <div class="absolute bottom-3 right-3 text-slate-300 pointer-events-none"><i class="ph-bold ph-text-aa text-xl"></i></div>
                            </div>
                        </div>

                        <div class="h-px bg-slate-100"></div>

                        <!-- BAGIAN 3: LAMPIRAN (EXISTING & NEW) -->
                        <div class="bg-slate-50 rounded-xl border border-slate-100 p-6">
                            <label class="block text-sm font-black text-[#2A3B52] flex items-center gap-2 mb-4">
                                <i class="ph-fill ph-paperclip text-[#5295FF]"></i> Kelola Lampiran
                            </label>

                            <!-- Lampiran Lama -->
                            @if($material->attachments->count() > 0)
                                <div class="space-y-3 mb-6">
                                    <p class="text-xs font-bold text-slate-400 uppercase">Lampiran Tersimpan:</p>
                                    @foreach($material->attachments as $att)
                                        <div class="flex items-center justify-between p-3 bg-white border border-slate-200 rounded-xl shadow-sm">
                                            <div class="flex items-center gap-3 overflow-hidden">
                                                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500 shrink-0">
                                                    <i class="ph-bold {{ $att->file_type == 'file' ? 'ph-file-pdf' : 'ph-link' }}"></i>
                                                </div>
                                                <div class="truncate">
                                                    <p class="text-sm font-bold text-[#2A3B52] truncate">{{ $att->file_name }}</p>
                                                    <a href="{{ $att->file_type == 'file' ? asset('storage/'.$att->file_path) : $att->file_path }}" target="_blank" class="text-[10px] text-[#5295FF] hover:underline font-bold uppercase tracking-wider">Lihat File</a>
                                                </div>
                                            </div>
                                            <label class="flex items-center gap-2 cursor-pointer bg-white px-3 py-1.5 rounded-lg border border-slate-200 hover:bg-[#FDE7E9] hover:border-[#F4C3C9] transition group">
                                                <input type="checkbox" name="delete_attachments[]" value="{{ $att->id }}" class="rounded text-[#D13438] focus:ring-[#D13438] border-slate-300">
                                                <span class="text-xs font-bold text-slate-500 group-hover:text-[#D13438] transition-colors">Hapus</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Lampiran Baru -->
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-3">
                                <p class="text-xs font-bold text-slate-400 uppercase">Tambah Lampiran Baru:</p>
                                <button type="button" @click="attachments.push({id: Date.now(), type: 'file', link: '', name: ''})" 
                                        class="w-full sm:w-auto text-xs bg-white border border-slate-200 text-[#5295FF] px-4 py-3 sm:py-2 rounded-lg font-bold hover:bg-[#F3F9FD] hover:border-[#D0E7F8] transition shadow-sm flex items-center justify-center gap-2 active:scale-95">
                                    <i class="ph-bold ph-plus"></i> Tambah Baris
                                </button>
                            </div>

                            <div class="space-y-3">
                                <template x-for="(att, index) in attachments" :key="att.id">
                                    <div class="flex flex-col md:flex-row items-start gap-3 p-4 bg-white rounded-xl border border-slate-200 relative group animate-enter hover:border-[#5295FF] transition-colors shadow-sm">
                                        <div class="flex-1 w-full grid grid-cols-1 md:grid-cols-12 gap-3">
                                            <div class="md:col-span-3">
                                                <div class="relative">
                                                    <select :name="'new_attachments['+index+'][type]'" x-model="att.type" class="w-full text-sm font-bold rounded-lg border-slate-200 bg-slate-50 cursor-pointer h-10 focus:ring-[#5295FF] text-[#2A3B52] appearance-none shadow-sm px-3">
                                                        <option value="file">📄 Dokumen</option>
                                                        <option value="video">📺 Video</option>
                                                        <option value="link">🔗 Link</option>
                                                    </select>
                                                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                                </div>
                                            </div>
                                            <div class="md:col-span-5">
                                                <input x-show="att.type === 'file'" type="file" :name="'new_attachments['+index+'][file]'" class="block w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-[#F3F9FD] file:text-[#5295FF] h-10 border border-slate-100 rounded-lg bg-white cursor-pointer hover:file:bg-[#E0F0FC]">
                                                <input x-show="att.type !== 'file'" type="text" :name="'new_attachments['+index+'][link]'" x-model="att.link" class="w-full text-sm font-medium rounded-lg border-slate-200 h-10 placeholder:text-slate-400 focus:ring-[#5295FF] text-[#2A3B52]" placeholder="https://...">
                                            </div>
                                            <div class="md:col-span-4">
                                                <input type="text" :name="'new_attachments['+index+'][name]'" x-model="att.name" class="w-full text-sm font-medium rounded-lg border-slate-200 h-10 placeholder:text-slate-400 focus:ring-[#5295FF] text-[#2A3B52]" placeholder="Label (Opsional)">
                                            </div>
                                        </div>
                                        <button type="button" @click="attachments = attachments.filter(i => i.id !== att.id)" class="absolute -top-2 -right-2 md:static md:mt-1 w-8 h-8 flex items-center justify-center rounded-lg bg-white text-[#D13438] hover:bg-[#FDE7E9] border border-[#F4C3C9] transition shadow-sm" title="Hapus Baris">
                                            <i class="ph-bold ph-trash"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>

                    </div>

                    <!-- FOOTER ACTIONS -->
                    <div class="bg-slate-50 px-8 py-6 flex flex-col md:flex-row justify-end gap-3 border-t border-slate-100">
                        <a href="{{ route('lms.materials.index') }}" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-100 transition text-center text-sm btn-cancel-confirm active:scale-95 shadow-sm">Batal</a>
                        
                        <button type="submit" class="px-8 py-3 bg-[#2A3B52] text-white font-bold rounded-xl shadow-md hover:bg-[#182436] transition transform flex items-center justify-center gap-2 text-sm border border-transparent active:scale-95">
                            <i class="ph-bold ph-check-circle text-lg"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

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
                        text: "Perubahan tidak akan disimpan.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#64748b', 
                        cancelButtonColor: '#cbd5e1', 
                        confirmButtonText: 'Ya, Batalkan',
                        cancelButtonText: 'Lanjut Mengedit',
                        customClass: {
                            popup: 'rounded-xl fluent-modal font-sans border-0',
                            confirmButton: 'rounded-lg px-4 py-2.5 font-bold',
                            cancelButton: 'rounded-lg px-4 py-2.5 font-bold text-slate-600'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = href;
                        }
                    });
                });
            });

            const updateForm = document.getElementById('updateForm');
            if(updateForm) {
                updateForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    if (!this.checkValidity()) {
                        this.reportValidity();
                        return;
                    }
                    Swal.fire({
                        title: 'Menyimpan Perubahan...',
                        html: 'Mohon tunggu sebentar.',
                        allowOutsideClick: false,
                        showConfirmButton: false,
                        didOpen: () => { Swal.showLoading(); },
                        customClass: { popup: 'rounded-xl fluent-modal font-sans border-0', title: 'text-xl font-bold text-[#2A3B52]' }
                    });
                    setTimeout(() => { this.submit(); }, 300);
                });
            }
        });
    </script>
    @endpush
</x-app-layout>