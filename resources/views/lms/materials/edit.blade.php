<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Edit Materi') }}
        </h2>
    </x-slot>

    <div class="py-8 font-sans text-slate-800">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO HEADER --}}
            <div class="relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-amber-600 via-orange-600 to-rose-600 p-8 mb-8 text-white shadow-xl shadow-orange-900/20 overflow-hidden border border-white/10">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                
                <div class="relative z-10 flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-extrabold mb-1 tracking-tight">Edit Materi Pelajaran</h1>
                        <p class="text-white/80 text-sm font-medium">Perbarui informasi, file, atau lampiran materi ini.</p>
                    </div>
                    <a href="{{ route('lms.materials.index') }}" class="hidden md:flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 rounded-xl text-sm font-bold backdrop-blur-sm transition text-white border border-white/10 btn-cancel-confirm">
                        <i class="ph-bold ph-arrow-left"></i> Batal
                    </a>
                </div>
            </div>

            {{-- ERROR BLOCK --}}
            @if ($errors->any())
                <div class="mb-8 bg-rose-50 border border-rose-100 p-5 rounded-[1.5rem] flex items-start gap-4 shadow-sm animate-pulse">
                    <div class="p-2 bg-rose-100 text-rose-600 rounded-xl shrink-0">
                        <i class="ph-bold ph-warning-octagon text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-rose-800 uppercase tracking-wide mb-1">Gagal Menyimpan</h3>
                        <ul class="list-disc list-inside text-sm text-rose-700 space-y-1 font-medium">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif
            
            {{-- FORM CARD --}}
            <div class="bg-white shadow-xl shadow-slate-200/50 rounded-[2rem] border border-slate-100 overflow-hidden">
                <form action="{{ route('lms.materials.update', $material->id) }}" method="POST" enctype="multipart/form-data" 
                      x-data="{ attachments: [] }"
                      id="updateForm">
                    @csrf
                    @method('PUT')

                    <div class="p-8 space-y-8">
                        
                        <!-- BAGIAN 1: INFORMASI UTAMA -->
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-xl"><i class="ph-bold ph-pencil-simple"></i></div>
                                <h3 class="text-lg font-black text-slate-800">Informasi Dasar</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="col-span-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Judul Materi <span class="text-rose-500">*</span></label>
                                    <input type="text" name="title" value="{{ old('title', $material->title) }}" required 
                                           class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-slate-800 focus:ring-orange-500 focus:border-orange-500 h-12 px-4 transition-colors">
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Mata Pelajaran <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <select name="subject_id" required class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-slate-800 focus:ring-orange-500 focus:border-orange-500 h-12 px-4 appearance-none">
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}" {{ $material->subject_id == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Kelas <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <select name="class_id" required class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-slate-800 focus:ring-orange-500 focus:border-orange-500 h-12 px-4 appearance-none">
                                            @foreach($classes as $class)
                                                <option value="{{ $class->id }}" {{ $material->class_id == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-1 italic">*Jika ini materi jenjang, pengeditan ini hanya mengubah untuk kelas ini saja.</p>
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
                                <textarea name="resume" rows="6" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:ring-orange-500 focus:border-orange-500 shadow-sm p-4 text-slate-700 leading-relaxed font-medium transition-colors">{{ old('resume', $material->resume) }}</textarea>
                                <div class="absolute bottom-3 right-3 text-slate-300 pointer-events-none"><i class="ph-bold ph-text-aa text-xl"></i></div>
                            </div>
                        </div>

                        <div class="h-px bg-slate-100"></div>

                        <!-- BAGIAN 3: LAMPIRAN (EXISTING & NEW) -->
                        <div class="bg-slate-50 rounded-2xl border border-slate-100 p-6">
                            <label class="block text-sm font-black text-slate-800 flex items-center gap-2 mb-4">
                                <i class="ph-fill ph-paperclip text-orange-600"></i> Kelola Lampiran
                            </label>

                            <!-- Lampiran Lama -->
                            @if($material->attachments->count() > 0)
                                <div class="space-y-3 mb-6">
                                    <p class="text-xs font-bold text-slate-400 uppercase">Lampiran Tersimpan:</p>
                                    @foreach($material->attachments as $att)
                                        <div class="flex items-center justify-between p-3 bg-white border border-slate-200 rounded-xl">
                                            <div class="flex items-center gap-3 overflow-hidden">
                                                <div class="w-8 h-8 rounded-lg bg-slate-100 flex items-center justify-center text-slate-500 shrink-0">
                                                    <i class="ph-bold {{ $att->file_type == 'file' ? 'ph-file-pdf' : 'ph-link' }}"></i>
                                                </div>
                                                <div class="truncate">
                                                    <p class="text-sm font-bold text-slate-700 truncate">{{ $att->file_name }}</p>
                                                    <a href="{{ $att->file_type == 'file' ? asset('storage/'.$att->file_path) : $att->file_path }}" target="_blank" class="text-[10px] text-blue-500 hover:underline">Lihat File</a>
                                                </div>
                                            </div>
                                            <label class="flex items-center gap-2 cursor-pointer bg-rose-50 px-3 py-1.5 rounded-lg border border-rose-100 hover:bg-rose-100 transition">
                                                <input type="checkbox" name="delete_attachments[]" value="{{ $att->id }}" class="rounded text-rose-500 focus:ring-rose-500 border-rose-300">
                                                <span class="text-xs font-bold text-rose-600">Hapus</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            <!-- Lampiran Baru -->
                            <div class="flex justify-between items-center mb-4">
                                <p class="text-xs font-bold text-slate-400 uppercase">Tambah Lampiran Baru:</p>
                                <button type="button" @click="attachments.push({id: Date.now(), type: 'file'})" 
                                        class="text-xs bg-white border border-slate-200 text-blue-700 px-4 py-2 rounded-xl font-bold hover:bg-blue-50 hover:border-blue-200 transition shadow-sm flex items-center gap-2">
                                    <i class="ph-bold ph-plus"></i> Tambah Baris
                                </button>
                            </div>

                            <div class="space-y-3">
                                <template x-for="(att, index) in attachments" :key="att.id">
                                    <div class="flex flex-col md:flex-row items-start gap-3 p-4 bg-white rounded-xl border border-slate-200 relative group animate-enter">
                                        <div class="flex-1 w-full grid grid-cols-1 md:grid-cols-12 gap-3">
                                            <div class="md:col-span-3">
                                                <select :name="'new_attachments['+index+'][type]'" x-model="att.type" class="w-full text-sm font-bold rounded-lg border-slate-200 bg-slate-50 cursor-pointer h-10">
                                                    <option value="file">📄 Dokumen</option>
                                                    <option value="video">📺 Video</option>
                                                    <option value="link">🔗 Link</option>
                                                </select>
                                            </div>
                                            <div class="md:col-span-5">
                                                <input x-show="att.type === 'file'" type="file" :name="'new_attachments['+index+'][file]'" class="block w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 h-10 border border-slate-100 rounded-lg bg-white">
                                                <input x-show="att.type !== 'file'" type="text" :name="'new_attachments['+index+'][link]'" class="w-full text-sm font-medium rounded-lg border-slate-200 h-10 placeholder:text-slate-400" placeholder="https://...">
                                            </div>
                                            <div class="md:col-span-4">
                                                <input type="text" :name="'new_attachments['+index+'][name]'" class="w-full text-sm font-medium rounded-lg border-slate-200 h-10 placeholder:text-slate-400" placeholder="Label (Opsional)">
                                            </div>
                                        </div>
                                        <button type="button" @click="attachments = attachments.filter(i => i.id !== att.id)" class="absolute -top-2 -right-2 md:static md:mt-1 w-8 h-8 flex items-center justify-center rounded-lg bg-rose-50 text-rose-400 hover:bg-rose-500 hover:text-white transition shadow-sm border border-rose-100">
                                            <i class="ph-bold ph-trash"></i>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>

                    </div>

                    <!-- FOOTER ACTIONS -->
                    <div class="bg-slate-50 px-8 py-6 flex flex-col md:flex-row justify-end gap-3 border-t border-slate-100">
                        <a href="{{ route('lms.materials.index') }}" class="px-6 py-3 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-100 transition text-center text-sm">Batal</a>
                        
                        <button type="submit" class="px-8 py-3 bg-orange-600 text-white font-bold rounded-xl shadow-lg shadow-orange-900/20 hover:bg-orange-700 hover:-translate-y-0.5 transition transform flex items-center justify-center gap-2 text-sm">
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
                        customClass: { popup: 'rounded-[2rem]' }
                    });
                    setTimeout(() => { this.submit(); }, 300);
                });
            }
        });
    </script>
    @endpush
</x-app-layout>