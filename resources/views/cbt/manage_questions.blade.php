<x-app-layout>
    <!-- 
        UPDATE: 
        1. Menambahkan logic 'deleteImage' untuk menghapus gambar.
        2. Memperbaiki binding data opsi jawaban agar lebih aman (fallback ke string kosong).
    -->
    <div x-data="{ 
        showImportModal: false, 
        showEditModal: false,
        editState: {
            url: '',
            question_text: '',
            question_image: '', 
            option_A: '',
            option_B: '',
            option_C: '',
            option_D: '',
            correct_answer: 'A',
            score_weight: 2
        },
        newImagePreview: null,
        deleteImage: false, // Flag untuk menghapus gambar
        
        openEdit(data, url) {
            this.editState = { ...data, url: url };
            this.newImagePreview = null;
            this.deleteImage = false; // Reset flag hapus
            this.showEditModal = true;
        },
        
        handleEditImage(event) {
            const file = event.target.files[0];
            if (file) {
                this.newImagePreview = URL.createObjectURL(file);
                this.deleteImage = false; // Jika upload baru, jangan hapus
            }
        },

        removeCurrentImage() {
            this.deleteImage = true;
            this.newImagePreview = null;
            this.$refs.editFileInput.value = ''; // Reset input file
        }
    }">
        
        <x-slot name="header">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Kelola Soal Ujian') }}
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">Bank Soal & Pengaturan Bobot</p>
                </div>
                <a href="{{ route('cbt.index') }}" class="text-sm font-bold text-slate-500 hover:text-blue-600 transition flex items-center gap-1">
                    <i class="ph-bold ph-arrow-left"></i> Kembali ke Dashboard
                </a>
            </div>
        </x-slot>

        <div class="py-6 md:py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
                
                <!-- Info Ujian & Tombol Import -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row justify-between items-center gap-6">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide bg-blue-100 text-blue-700">
                                {{ $exam->subject_name }}
                            </span>
                            <span class="text-xs font-bold text-slate-400">Kelas {{ $exam->class_level }}</span>
                        </div>
                        <h3 class="text-xl font-black text-slate-800">{{ $exam->title }}</h3>
                    </div>
                    
                    <div class="flex items-center gap-4 w-full md:w-auto">
                        <button @click="showImportModal = true" class="flex-1 md:flex-none px-5 py-2.5 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 transition flex items-center justify-center gap-2 shadow-lg shadow-green-500/20">
                            <i class="ph-bold ph-microsoft-excel-logo text-lg"></i> 
                            <span>Import Excel</span>
                        </button>

                        <div class="hidden md:block text-right pl-6 border-l border-slate-100">
                            <p class="text-3xl font-black text-blue-600 leading-none">{{ $exam->questions->count() }}</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Soal</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col lg:flex-row gap-8 items-start">
                    
                    <!-- FORM INPUT MANUAL (Kiri) -->
                    <div class="w-full lg:w-2/5 order-2 lg:order-1">
                        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm lg:sticky lg:top-8"
                             x-data="{ createPreview: null }">
                             
                            <div class="flex items-center gap-2 mb-6 pb-4 border-b border-slate-100">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                                    <i class="ph-fill ph-plus-circle text-xl"></i>
                                </div>
                                <h3 class="font-bold text-slate-800">Tambah Soal Manual</h3>
                            </div>

                            <form action="{{ route('cbt.questions.store', $exam->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                                @csrf
                                
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Pertanyaan</label>
                                    <textarea name="question_text" rows="4" required class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500 focus:border-blue-500 bg-slate-50 focus:bg-white transition" placeholder="Tulis pertanyaan di sini..."></textarea>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Gambar Pendukung (Opsional)</label>
                                    <template x-if="createPreview">
                                        <div class="mb-3 relative w-full">
                                            <img :src="createPreview" class="w-full h-40 object-cover rounded-xl border border-slate-200">
                                            <button type="button" @click="createPreview = null; $refs.fileInput.value = ''" class="absolute top-2 right-2 bg-red-500 text-white p-1 rounded-full shadow hover:bg-red-600 transition">
                                                <i class="ph-bold ph-x"></i>
                                            </button>
                                        </div>
                                    </template>
                                    <input type="file" x-ref="fileInput" name="question_image" @change="createPreview = URL.createObjectURL($event.target.files[0])" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer border border-slate-200 rounded-xl bg-white">
                                </div>

                                <div class="space-y-3 pt-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase">Pilihan Jawaban</label>
                                    @foreach(['A','B','C','D'] as $opt)
                                    <div class="flex gap-3 items-center group">
                                        <span class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-xs group-focus-within:bg-blue-600 group-focus-within:text-white transition-colors">{{ $opt }}</span>
                                        <input type="text" name="option_{{ $opt }}" required class="flex-1 rounded-xl border-slate-200 text-sm py-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Pilihan {{ $opt }}">
                                    </div>
                                    @endforeach
                                </div>

                                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-100">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Kunci Jawaban</label>
                                        <select name="correct_answer" required class="w-full rounded-xl border-slate-200 text-sm font-bold text-slate-700 bg-slate-50 cursor-pointer">
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="C">C</option>
                                            <option value="D">D</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Bobot Nilai</label>
                                        <input type="number" name="score_weight" value="2" required class="w-full rounded-xl border-slate-200 text-sm font-bold text-center">
                                    </div>
                                </div>

                                <button type="submit" class="w-full py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-500/20 flex items-center justify-center gap-2">
                                    <i class="ph-bold ph-floppy-disk"></i> Simpan Soal
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- LIST SOAL (Kanan) -->
                    <div class="w-full lg:w-3/5 order-1 lg:order-2 space-y-5">
                        <div class="flex justify-between items-center px-2">
                            <h3 class="font-bold text-slate-800">Daftar Soal</h3>
                            <span class="text-xs font-bold text-slate-400 bg-white border border-slate-200 px-3 py-1 rounded-full">
                                {{ $exam->questions->count() }} Butir
                            </span>
                        </div>
                        
                        @forelse($exam->questions as $index => $q)
                            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm relative group hover:border-blue-300 hover:shadow-md transition-all">
                                <!-- Nomor -->
                                <div class="absolute top-5 left-5 w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center font-black text-slate-500 text-sm group-hover:bg-blue-600 group-hover:text-white transition-colors">
                                    {{ $index + 1 }}
                                </div>
                                
                                <!-- Konten -->
                                <div class="pl-12">
                                    @if($q->question_image)
                                        <div class="mb-4">
                                            <img src="{{ asset('storage/' . $q->question_image) }}" class="max-h-48 rounded-xl border border-slate-100 shadow-sm object-cover" alt="Gambar Soal">
                                        </div>
                                    @endif
                                    
                                    <p class="text-slate-800 font-bold text-lg mb-4 leading-relaxed whitespace-pre-line">{{ $q->question_text }}</p>
                                    
                                    <!-- Opsi -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                                        @foreach(['A','B','C','D'] as $opt)
                                            <!-- Handle data options apakah array atau kolom terpisah, di sini asumsi kolom terpisah sesuai input manual -->
                                            @php 
                                                // Fallback logic jika struktur DB berbeda
                                                $val = isset($q->{'option_'.$opt}) ? $q->{'option_'.$opt} : ($q->options[$opt] ?? '-'); 
                                            @endphp
                                            <div class="flex items-start gap-3 p-2 rounded-lg {{ $opt == $q->correct_answer ? 'bg-green-50 border border-green-100' : 'hover:bg-slate-50 border border-transparent' }}">
                                                <span class="w-6 h-6 flex items-center justify-center rounded-md border {{ $opt == $q->correct_answer ? 'border-green-500 bg-green-500 text-white' : 'border-slate-300 text-slate-400' }} text-[10px] font-bold shrink-0 mt-0.5">
                                                    {{ $opt }}
                                                </span>
                                                <span class="{{ $opt == $q->correct_answer ? 'text-green-800 font-bold' : 'text-slate-600' }}">{{ $val }}</span>
                                                @if($opt == $q->correct_answer)
                                                    <i class="ph-fill ph-check-circle text-green-500 ml-auto"></i>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    <div class="mt-4 pt-3 border-t border-slate-50 flex items-center gap-2">
                                        <span class="text-[10px] font-bold text-slate-400 uppercase">Bobot: {{ $q->score_weight }} Poin</span>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="absolute top-4 right-4 flex gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button 
                                        type="button"
                                        @click="openEdit({
                                            question_text: {{ json_encode($q->question_text) }},
                                            question_image: {{ json_encode($q->question_image) }}, 
                                            option_A: {{ json_encode($q->option_A ?? '') }},
                                            option_B: {{ json_encode($q->option_B ?? '') }},
                                            option_C: {{ json_encode($q->option_C ?? '') }},
                                            option_D: {{ json_encode($q->option_D ?? '') }},
                                            correct_answer: '{{ $q->correct_answer }}',
                                            score_weight: {{ $q->score_weight }}
                                        }, '{{ route('cbt.questions.update', $q->id) }}')"
                                        class="p-2 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-blue-600 hover:border-blue-200 shadow-sm transition" 
                                        title="Edit Soal">
                                        <i class="ph-bold ph-pencil-simple"></i>
                                    </button>

                                    <form action="{{ route('cbt.questions.destroy', $q->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus soal ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-red-600 hover:border-red-200 shadow-sm transition" title="Hapus Soal">
                                            <i class="ph-bold ph-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-16 bg-white rounded-3xl border-2 border-dashed border-slate-200">
                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300 animate-pulse">
                                    <i class="ph-duotone ph-clipboard-text text-4xl"></i>
                                </div>
                                <h3 class="text-slate-800 font-bold text-lg">Bank Soal Kosong</h3>
                                <p class="text-slate-500 text-sm mt-1 mb-6 max-w-xs mx-auto">Mulai tambahkan soal secara manual atau import dari file Excel.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL IMPORT (Tidak berubah) -->
        <div x-show="showImportModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showImportModal = false"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-md">
                    <div class="bg-white p-8 text-center">
                        <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-green-50 mb-6">
                            <i class="ph-duotone ph-microsoft-excel-logo text-green-600 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-black text-slate-800 mb-2">Import Soal Excel</h3>
                        <p class="text-sm text-slate-500 mb-6">Upload file .xlsx sesuai template.</p>
                        
                        <form action="{{ route('cbt.questions.import', $exam->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-left">
                            @csrf
                            <div class="border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center hover:bg-slate-50 hover:border-blue-300 transition cursor-pointer relative group">
                                <input type="file" name="file" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                <i class="ph-duotone ph-cloud-arrow-up text-4xl text-slate-300 group-hover:text-blue-500 mb-2 transition-colors"></i>
                                <p class="text-sm font-bold text-slate-600 group-hover:text-blue-600">Klik untuk upload file</p>
                                <p class="text-xs text-slate-400 mt-1">Format: .xlsx, .csv</p>
                            </div>
                            <div class="flex gap-3">
                                <button type="button" @click="showImportModal = false" class="flex-1 py-3 bg-white border border-slate-200 text-slate-700 font-bold rounded-xl hover:bg-slate-50">Batal</button>
                                <button type="submit" class="flex-1 py-3 bg-green-600 text-white font-bold rounded-xl hover:bg-green-700 shadow-lg shadow-green-500/30">Proses</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL EDIT SOAL (DIPERBAIKI) -->
        <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" @click="showEditModal = false"></div>
            <div class="flex min-h-screen items-center justify-center p-4">
                <div class="relative transform overflow-hidden rounded-3xl bg-white text-left shadow-2xl transition-all sm:w-full sm:max-w-2xl">
                    <form :action="editState.url" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')
                        
                        <!-- Input Hidden untuk Logic Hapus Gambar -->
                        <input type="hidden" name="delete_image" x-model="deleteImage">

                        <div class="bg-white px-6 py-5 border-b border-slate-100 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                <i class="ph-fill ph-pencil-simple text-blue-600"></i> Edit Soal
                            </h3>
                            <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-rose-500 transition">
                                <i class="ph-bold ph-x text-xl"></i>
                            </button>
                        </div>

                        <div class="p-6 space-y-5 max-h-[70vh] overflow-y-auto custom-scrollbar">
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Pertanyaan</label>
                                <textarea name="question_text" x-model="editState.question_text" rows="4" required class="w-full rounded-xl border-slate-200 text-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>

                            <!-- Gambar Update -->
                            <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                <label class="block text-xs font-bold text-slate-400 uppercase mb-3">Gambar Soal</label>
                                
                                <div class="flex flex-col gap-4">
                                    <!-- Preview Gambar Baru -->
                                    <template x-if="newImagePreview">
                                        <div class="relative w-fit">
                                            <p class="text-[10px] font-bold text-green-600 mb-1 flex items-center gap-1"><i class="ph-bold ph-check"></i> Akan Diganti:</p>
                                            <img :src="newImagePreview" class="h-32 rounded-lg border-2 border-green-500 shadow-sm object-cover">
                                            <button type="button" @click="newImagePreview = null; $refs.editFileInput.value = ''" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full p-1 shadow">
                                                <i class="ph-bold ph-x"></i>
                                            </button>
                                        </div>
                                    </template>

                                    <!-- Preview Gambar Lama -->
                                    <template x-if="!newImagePreview && editState.question_image && !deleteImage">
                                        <div class="relative w-fit group">
                                            <p class="text-[10px] font-bold text-slate-400 mb-1">Gambar Saat Ini:</p>
                                            <img :src="'/storage/' + editState.question_image" class="h-24 rounded-lg border border-slate-200 shadow-sm object-cover opacity-80">
                                            
                                            <!-- Tombol Hapus Gambar Lama -->
                                            <button type="button" @click="removeCurrentImage()" class="absolute inset-0 bg-red-500/80 text-white flex items-center justify-center opacity-0 group-hover:opacity-100 transition rounded-lg font-bold text-xs gap-1">
                                                <i class="ph-bold ph-trash"></i> Hapus
                                            </button>
                                        </div>
                                    </template>
                                    
                                    <!-- Info jika gambar dihapus -->
                                    <template x-if="deleteImage && !newImagePreview">
                                        <div class="text-xs text-rose-600 font-bold bg-rose-50 p-2 rounded border border-rose-100 flex items-center gap-2">
                                            <i class="ph-bold ph-trash"></i> Gambar akan dihapus.
                                            <button type="button" @click="deleteImage = false" class="text-blue-600 underline ml-auto">Batal</button>
                                        </div>
                                    </template>

                                    <div class="flex-1">
                                        <input type="file" 
                                               x-ref="editFileInput"
                                               name="question_image" 
                                               @change="handleEditImage"
                                               accept="image/*"
                                               class="block w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-white file:text-blue-600 hover:file:bg-blue-50 cursor-pointer border border-slate-200 rounded-lg bg-white">
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-3">
                                <label class="block text-xs font-bold text-slate-400 uppercase">Edit Pilihan Jawaban</label>
                                <div class="grid grid-cols-1 gap-3">
                                    @foreach(['A','B','C','D'] as $opt)
                                    <div class="flex gap-3 items-center">
                                        <span class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-xs">{{ $opt }}</span>
                                        <input type="text" name="option_{{ $opt }}" x-model="editState.option_{{ $opt }}" required class="flex-1 rounded-xl border-slate-200 text-sm py-2">
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Kunci Jawaban</label>
                                    <select name="correct_answer" x-model="editState.correct_answer" required class="w-full rounded-xl border-slate-200 text-sm font-bold text-slate-700 bg-slate-50">
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                        <option value="D">D</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Bobot Nilai</label>
                                    <input type="number" name="score_weight" x-model="editState.score_weight" required class="w-full rounded-xl border-slate-200 text-sm font-bold text-center">
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-50 px-6 py-4 flex justify-end gap-3 rounded-b-3xl border-t border-slate-100">
                            <button type="button" @click="showEditModal = false" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-500/30">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>