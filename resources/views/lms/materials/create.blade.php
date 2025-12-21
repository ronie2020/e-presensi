<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Upload Materi Baru') }}
        </h2>
    </x-slot>

    <div class="py-8 font-sans text-slate-800">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO HEADER --}}
            <div class="relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 p-8 mb-8 text-white shadow-xl shadow-blue-900/30 overflow-hidden border border-white/10">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-extrabold mb-1 tracking-tight">Upload Materi Baru</h1>
                        <p class="text-blue-300 text-sm font-medium">Lengkapi formulir di bawah ini untuk membagikan materi.</p>
                    </div>
                    <a href="{{ route('lms.materials.index') }}" class="hidden md:flex items-center gap-2 px-4 py-2 bg-white/10 hover:bg-white/20 rounded-xl text-sm font-bold backdrop-blur-sm transition text-white border border-white/10">
                        <i class="ph-bold ph-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>

            {{-- ERROR BLOCK --}}
            @if ($errors->any())
                <div class="mb-8 bg-rose-50 border border-rose-100 p-5 rounded-[1.5rem] flex items-start gap-4 shadow-sm">
                    <div class="p-2 bg-rose-100 text-rose-600 rounded-xl shrink-0">
                        <i class="ph-bold ph-warning-octagon text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-rose-800 uppercase tracking-wide mb-1">Gagal Mengupload</h3>
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
                <form action="{{ route('lms.materials.store') }}" method="POST" enctype="multipart/form-data" 
                      x-data="{ targetType: 'class', attachments: [{id: 1, type: 'file'}] }">
                    @csrf

                    <div class="p-8 space-y-8">
                        
                        <!-- BAGIAN 1: INFORMASI UTAMA -->
                        <div>
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl"><i class="ph-bold ph-info"></i></div>
                                <h3 class="text-lg font-black text-slate-800">Informasi Dasar</h3>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="col-span-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Judul Materi <span class="text-rose-500">*</span></label>
                                    <input type="text" name="title" value="{{ old('title') }}" required 
                                           class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-slate-800 focus:ring-blue-500 focus:border-blue-500 h-12 px-4 placeholder:font-normal placeholder:text-slate-400" 
                                           placeholder="Contoh: Bab 1 - Ekosistem">
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Mata Pelajaran <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <select name="subject_id" required class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-slate-800 focus:ring-blue-500 focus:border-blue-500 h-12 px-4 appearance-none">
                                            <option value="">-- Pilih Mapel --</option>
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>{{ $subject->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                                    </div>
                                </div>

                                {{-- Pilihan Target Siswa --}}
                                <div class="bg-blue-50/50 p-5 rounded-2xl border border-blue-100/50">
                                    <label class="block text-xs font-black text-blue-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                                        <i class="ph-fill ph-users-three"></i> Bagikan Kepada:
                                    </label>
                                    <div class="space-y-4">
                                        <div class="flex gap-4">
                                            <label class="inline-flex items-center cursor-pointer group">
                                                <div class="relative flex items-center">
                                                    <input type="radio" name="target_type" value="class" x-model="targetType" class="peer sr-only">
                                                    <div class="w-5 h-5 border-2 border-slate-300 rounded-full peer-checked:border-blue-600 peer-checked:bg-blue-600 transition"></div>
                                                </div>
                                                <span class="ml-2 text-sm font-bold text-slate-600 group-hover:text-blue-700 transition">Satu Kelas</span>
                                            </label>
                                            <label class="inline-flex items-center cursor-pointer group">
                                                <div class="relative flex items-center">
                                                    <input type="radio" name="target_type" value="grade" x-model="targetType" class="peer sr-only">
                                                    <div class="w-5 h-5 border-2 border-slate-300 rounded-full peer-checked:border-blue-600 peer-checked:bg-blue-600 transition"></div>
                                                </div>
                                                <span class="ml-2 text-sm font-bold text-slate-600 group-hover:text-blue-700 transition">Satu Jenjang</span>
                                            </label>
                                        </div>
                                        
                                        <div x-show="targetType === 'class'">
                                            <div class="relative">
                                                <select name="class_id" class="w-full text-sm font-bold rounded-xl border-slate-200 bg-white focus:ring-blue-500 h-11 px-3 appearance-none">
                                                    <option value="">-- Pilih Kelas Spesifik --</option>
                                                    @foreach($classes as $class)
                                                        <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                                            </div>
                                        </div>
                                        <div x-show="targetType === 'grade'" style="display: none;">
                                            <div class="relative">
                                                <select name="target_grade" class="w-full text-sm font-bold rounded-xl border-slate-200 bg-white focus:ring-blue-500 h-11 px-3 appearance-none">
                                                    <option value="7">Semua Kelas 7</option>
                                                    <option value="8">Semua Kelas 8</option>
                                                    <option value="9">Semua Kelas 9</option>
                                                </select>
                                                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                                            </div>
                                            <p class="text-[10px] text-slate-400 font-bold mt-1.5 flex items-center gap-1"><i class="ph-bold ph-info"></i> Sistem akan membagikan ke seluruh kelas dengan awalan angka tersebut.</p>
                                        </div>
                                    </div>
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
                                <textarea name="resume" rows="6" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:ring-blue-500 focus:border-blue-500 shadow-sm p-4 text-slate-700 leading-relaxed font-medium placeholder:font-normal placeholder:text-slate-400" placeholder="Tuliskan rangkuman materi, tujuan pembelajaran, atau instruksi untuk siswa..."></textarea>
                                <div class="absolute bottom-3 right-3 text-slate-300 pointer-events-none"><i class="ph-bold ph-text-aa text-xl"></i></div>
                            </div>
                        </div>

                        <div class="h-px bg-slate-100"></div>

                        <!-- BAGIAN 3: LAMPIRAN (DYNAMIC) -->
                        <div class="bg-slate-50 rounded-2xl border border-slate-100 p-6">
                            <div class="flex justify-between items-center mb-6">
                                <div>
                                    <label class="block text-sm font-black text-slate-800 flex items-center gap-2">
                                        <i class="ph-fill ph-paperclip text-blue-600"></i> Referensi & Lampiran
                                    </label>
                                    <p class="text-xs text-slate-400 mt-1">Upload file dokumen atau tautkan video pembelajaran.</p>
                                </div>
                                <button type="button" @click="attachments.push({id: Date.now(), type: 'file'})" 
                                        class="text-xs bg-white border border-slate-200 text-blue-700 px-4 py-2 rounded-xl font-bold hover:bg-blue-50 hover:border-blue-200 transition shadow-sm flex items-center gap-2">
                                    <i class="ph-bold ph-plus"></i> Tambah Baris
                                </button>
                            </div>

                            <div class="space-y-3">
                                <template x-for="(att, index) in attachments" :key="att.id">
                                    <div class="flex flex-col md:flex-row items-start gap-3 p-4 bg-white rounded-xl border border-slate-200 relative group transition-all hover:border-blue-300 hover:shadow-md">
                                        
                                        <!-- Nomor -->
                                        <div class="hidden md:flex w-8 h-8 rounded-lg bg-slate-100 text-slate-500 text-xs font-bold items-center justify-center border border-slate-200 shrink-0 mt-1" x-text="index + 1"></div>

                                        <div class="flex-1 w-full grid grid-cols-1 md:grid-cols-12 gap-3">
                                            <!-- Pilihan Tipe -->
                                            <div class="md:col-span-3">
                                                <div class="relative">
                                                    <select :name="'attachments['+index+'][type]'" x-model="att.type" class="w-full text-sm font-bold rounded-lg border-slate-200 focus:ring-blue-500 bg-slate-50 cursor-pointer h-10 px-3 appearance-none">
                                                        <option value="file">📄 Dokumen (File)</option>
                                                        <option value="video">📺 Video (YouTube)</option>
                                                        <option value="link">🔗 Link Website</option>
                                                    </select>
                                                    <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                                </div>
                                            </div>

                                            <!-- Input File / Link -->
                                            <div class="md:col-span-5">
                                                <!-- Jika File -->
                                                <input x-show="att.type === 'file'" type="file" :name="'attachments['+index+'][file]'" class="block w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition cursor-pointer h-10 border border-slate-100 rounded-lg bg-white">
                                                
                                                <!-- Jika Link/Video -->
                                                <input x-show="att.type !== 'file'" type="text" :name="'attachments['+index+'][link]'" class="w-full text-sm font-medium rounded-lg border-slate-200 h-10 placeholder:text-slate-400 focus:ring-blue-500" placeholder="https://...">
                                            </div>

                                            <!-- Nama Label (Opsional) -->
                                            <div class="md:col-span-4">
                                                <input type="text" :name="'attachments['+index+'][name]'" class="w-full text-sm font-medium rounded-lg border-slate-200 h-10 placeholder:text-slate-400 focus:ring-blue-500" placeholder="Label (Opsional)">
                                            </div>
                                        </div>

                                        <!-- Hapus Baris -->
                                        <button type="button" @click="attachments = attachments.filter(i => i.id !== att.id)" class="absolute -top-2 -right-2 md:static md:mt-1 w-8 h-8 flex items-center justify-center rounded-lg bg-rose-50 text-rose-400 hover:bg-rose-500 hover:text-white transition shadow-sm border border-rose-100" title="Hapus Baris">
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
                        <button type="submit" class="px-8 py-3 bg-blue-900 text-white font-bold rounded-xl shadow-lg shadow-blue-900/20 hover:bg-blue-800 hover:-translate-y-0.5 transition transform flex items-center justify-center gap-2 text-sm">
                            <i class="ph-bold ph-check-circle text-lg"></i>
                            <span>Simpan & Terbitkan</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>