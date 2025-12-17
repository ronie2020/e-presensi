<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Upload Materi Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-xl sm:rounded-2xl border border-gray-100 overflow-hidden">
                
                <form action="{{ route('lms.materials.store') }}" method="POST" enctype="multipart/form-data" 
                      x-data="{ targetType: 'class', attachments: [{id: 1, type: 'file'}] }">
                    @csrf

                    <!-- HEADER FORM -->
                    <div class="bg-gray-50 px-8 py-6 border-b border-gray-100 flex justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Konten Pembelajaran</h2>
                            <p class="text-sm text-gray-500">Buat materi lengkap dengan teks, dokumen, dan video.</p>
                        </div>
                        <a href="{{ route('lms.materials.index') }}" class="text-gray-500 hover:text-gray-700 font-medium text-sm flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Kembali
                        </a>
                    </div>

                    <div class="p-8 space-y-8">
                        
                        <!-- 1. INFORMASI DASAR -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="col-span-2">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Judul Materi <span class="text-red-500">*</span></label>
                                <input type="text" name="title" required class="w-full rounded-xl border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Contoh: Bab 1 - Ekosistem">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Mata Pelajaran <span class="text-red-500">*</span></label>
                                <select name="subject_id" required class="w-full rounded-xl border-gray-300 focus:ring-blue-500">
                                    <option value="">-- Pilih Mapel --</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Target Kelas -->
                            <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-100">
                                <label class="block text-xs font-bold text-blue-800 uppercase mb-3">Bagikan Kepada:</label>
                                <div class="space-y-3">
                                    <div class="flex gap-4">
                                        <label class="inline-flex items-center"><input type="radio" name="target_type" value="class" x-model="targetType" class="text-blue-600 focus:ring-blue-500"><span class="ml-2 text-sm">Satu Kelas</span></label>
                                        <label class="inline-flex items-center"><input type="radio" name="target_type" value="grade" x-model="targetType" class="text-blue-600 focus:ring-blue-500"><span class="ml-2 text-sm">Satu Jenjang</span></label>
                                    </div>
                                    
                                    <div x-show="targetType === 'class'">
                                        <select name="class_id" class="w-full text-sm rounded-lg border-gray-300">
                                            <option value="">-- Pilih Kelas --</option>
                                            @foreach($classes as $class)
                                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div x-show="targetType === 'grade'" style="display: none;">
                                        <select name="target_grade" class="w-full text-sm rounded-lg border-gray-300">
                                            <option value="7">Kelas 7</option>
                                            <option value="8">Kelas 8</option>
                                            <option value="9">Kelas 9</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="border-gray-100">

                        <!-- 2. RESUME / PENJELASAN MATERI -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">
                                📖 Pengantar & Resume Materi
                                <span class="text-xs font-normal text-gray-500 ml-1">(Rangkuman yang akan dibaca siswa)</span>
                            </label>
                            <textarea name="resume" rows="8" class="w-full rounded-xl border-gray-300 focus:ring-blue-500 shadow-sm" placeholder="Tuliskan rangkuman materi, tujuan pembelajaran, atau poin-poin penting disini..."></textarea>
                            <p class="text-xs text-gray-400 mt-2 text-right">Anda bisa menulis teks panjang disini.</p>
                        </div>

                        <hr class="border-gray-100">

                        <!-- 3. LAMPIRAN (MULTIPLE UPLOAD) -->
                        <div>
                            <div class="flex justify-between items-center mb-4">
                                <label class="block text-sm font-bold text-gray-700">📎 Referensi & Lampiran</label>
                                <button type="button" @click="attachments.push({id: Date.now(), type: 'file'})" 
                                        class="text-xs bg-blue-100 text-blue-700 px-3 py-1.5 rounded-lg font-bold hover:bg-blue-200 transition flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Tambah Baris
                                </button>
                            </div>

                            <div class="space-y-3">
                                <template x-for="(att, index) in attachments" :key="att.id">
                                    <div class="flex items-start gap-3 p-4 bg-gray-50 rounded-xl border border-gray-200 relative group transition hover:border-blue-300 hover:shadow-sm">
                                        
                                        <!-- Nomor -->
                                        <div class="w-6 h-6 rounded-full bg-white text-gray-500 text-xs font-bold flex items-center justify-center border border-gray-200 mt-2 shadow-sm" x-text="index + 1"></div>

                                        <div class="flex-1 grid grid-cols-1 md:grid-cols-12 gap-3">
                                            <!-- Pilihan Tipe -->
                                            <div class="md:col-span-3">
                                                <select :name="'attachments['+index+'][type]'" x-model="att.type" class="w-full text-sm rounded-lg border-gray-300 focus:ring-blue-500 bg-white cursor-pointer">
                                                    <option value="file">Dokumen (File)</option>
                                                    <option value="video">Video (YouTube)</option>
                                                    <option value="link">Link Website</option>
                                                </select>
                                            </div>

                                            <!-- Input File / Link -->
                                            <div class="md:col-span-5">
                                                <!-- Jika File -->
                                                <input x-show="att.type === 'file'" type="file" :name="'attachments['+index+'][file]'" class="block w-full text-sm text-slate-500 file:mr-2 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 transition cursor-pointer">
                                                
                                                <!-- Jika Link/Video -->
                                                <input x-show="att.type !== 'file'" type="text" :name="'attachments['+index+'][link]'" class="w-full text-sm rounded-lg border-gray-300" placeholder="https://...">
                                            </div>

                                            <!-- Nama Label (Opsional) -->
                                            <div class="md:col-span-4">
                                                <input type="text" :name="'attachments['+index+'][name]'" class="w-full text-sm rounded-lg border-gray-300" placeholder="Label (Opsional, cth: Slide Presentasi)">
                                            </div>
                                        </div>

                                        <!-- Hapus Baris -->
                                        <button type="button" @click="attachments = attachments.filter(i => i.id !== att.id)" class="text-gray-400 hover:text-red-500 p-2 mt-1 transition" title="Hapus Baris">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                            
                            <p class="text-xs text-gray-500 mt-2 ml-1">Tips: Klik "Tambah Baris" untuk memasukkan banyak file sekaligus.</p>
                        </div>

                    </div>

                    <!-- FOOTER ACTIONS -->
                    <div class="bg-gray-50 px-8 py-5 flex justify-end gap-3 border-t border-gray-100">
                        <a href="{{ route('lms.materials.index') }}" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-50 transition">Batal</a>
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-bold rounded-xl shadow-lg hover:bg-blue-700 hover:-translate-y-0.5 transition transform flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Simpan & Terbitkan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>