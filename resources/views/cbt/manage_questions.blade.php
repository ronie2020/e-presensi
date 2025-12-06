<x-app-layout>
    <!-- 
        UPDATE: x-data diperluas untuk menangani Modal Edit 
        Kita menyimpan data form edit di dalam object 'editState'
    -->
    <div x-data="{ 
        showImportModal: false, 
        showEditModal: false,
        editState: {
            url: '',
            question_text: '',
            option_A: '',
            option_B: '',
            option_C: '',
            option_D: '',
            correct_answer: 'A',
            score_weight: 2
        },
        openEdit(data, url) {
            this.editState = { ...data, url: url };
            this.showEditModal = true;
        }
    }">
        
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Kelola Soal Ujian') }}
                </h2>
                <a href="{{ route('cbt.index') }}" class="text-sm text-slate-500 hover:text-slate-700">&larr; Kembali ke Dashboard</a>
            </div>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                
                <!-- Info Ujian & Tombol Import -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex flex-col md:flex-row justify-between items-center gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">{{ $exam->title }}</h3>
                        <p class="text-slate-500 text-sm">{{ $exam->subject_name }} - Kelas {{ $exam->class_level }}</p>
                    </div>
                    <div class="flex items-center gap-4">
                        <button @click="showImportModal = true" class="px-4 py-2 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition flex items-center gap-2 shadow-lg shadow-green-500/30">
                            <i class="ph-bold ph-microsoft-excel-logo"></i> Import Excel
                        </button>

                        <div class="text-right pl-4 border-l border-slate-200">
                            <p class="text-3xl font-black text-blue-600">{{ $exam->questions->count() }}</p>
                            <p class="text-xs font-bold text-slate-400 uppercase">Total Soal</p>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col lg:flex-row gap-6">
                    <!-- FORM INPUT MANUAL (Kiri) -->
                    <div class="lg:w-2/5">
                        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm sticky top-6">
                            <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                                <i class="ph-fill ph-plus-circle text-blue-600"></i> Tambah Soal Manual
                            </h3>

                            <form action="{{ route('cbt.questions.store', $exam->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                
                                <!-- Pertanyaan -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Pertanyaan</label>
                                    <textarea name="question_text" rows="3" required class="w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500" placeholder="Tulis pertanyaan di sini..."></textarea>
                                </div>

                                <!-- Gambar (Opsional) -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Gambar (Opsional)</label>
                                    <input type="file" name="question_image" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                </div>

                                <!-- Opsi Jawaban -->
                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-slate-500 uppercase">Pilihan Jawaban</label>
                                    <div class="flex gap-2 items-center">
                                        <span class="w-6 text-center font-bold text-slate-400">A</span>
                                        <input type="text" name="option_A" required class="flex-1 rounded-lg border-slate-300 text-sm py-1.5" placeholder="Pilihan A">
                                    </div>
                                    <div class="flex gap-2 items-center">
                                        <span class="w-6 text-center font-bold text-slate-400">B</span>
                                        <input type="text" name="option_B" required class="flex-1 rounded-lg border-slate-300 text-sm py-1.5" placeholder="Pilihan B">
                                    </div>
                                    <div class="flex gap-2 items-center">
                                        <span class="w-6 text-center font-bold text-slate-400">C</span>
                                        <input type="text" name="option_C" required class="flex-1 rounded-lg border-slate-300 text-sm py-1.5" placeholder="Pilihan C">
                                    </div>
                                    <div class="flex gap-2 items-center">
                                        <span class="w-6 text-center font-bold text-slate-400">D</span>
                                        <input type="text" name="option_D" required class="flex-1 rounded-lg border-slate-300 text-sm py-1.5" placeholder="Pilihan D">
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4 pt-2">
                                    <!-- Kunci Jawaban -->
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Kunci Jawaban</label>
                                        <select name="correct_answer" required class="w-full rounded-lg border-slate-300 text-sm font-bold text-slate-700">
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="C">C</option>
                                            <option value="D">D</option>
                                        </select>
                                    </div>
                                    <!-- Bobot Nilai -->
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Bobot Nilai</label>
                                        <input type="number" name="score_weight" value="2" required class="w-full rounded-lg border-slate-300 text-sm">
                                    </div>
                                </div>

                                <div class="flex gap-3 pt-2">
                                    <button type="submit" class="flex-1 py-2.5 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition shadow-lg shadow-blue-500/30">Simpan Soal</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- LIST SOAL (Kanan) -->
                    <div class="lg:w-3/5 space-y-4">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="font-bold text-slate-800">Daftar Soal</h3>
                            <a href="{{ route('cbt.index') }}" class="px-4 py-2 bg-white border border-slate-300 rounded-lg text-sm font-bold text-slate-600 hover:bg-slate-50 transition shadow-sm flex items-center gap-2">
                                <i class="ph-bold ph-arrow-left"></i> Selesai & Kembali
                            </a>
                        </div>
                        
                        @forelse($exam->questions as $index => $q)
                            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm relative group hover:border-blue-300 transition-colors">
                                <!-- Nomor -->
                                <div class="absolute top-4 left-4 w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center font-bold text-slate-500 text-sm group-hover:bg-blue-100 group-hover:text-blue-600 transition-colors">
                                    {{ $index + 1 }}
                                </div>
                                
                                <!-- Konten -->
                                <div class="pl-12">
                                    @if($q->question_image)
                                        <img src="{{ asset('storage/' . $q->question_image) }}" class="max-h-40 rounded-lg mb-3 border border-slate-100">
                                    @endif
                                    <p class="text-slate-800 font-medium mb-3">{{ $q->question_text }}</p>
                                    
                                    <!-- Opsi -->
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-sm">
                                        @foreach($q->options as $key => $val)
                                            <div class="flex items-center gap-2 {{ $key == $q->correct_answer ? 'text-green-600 font-bold' : 'text-slate-500' }}">
                                                <span class="w-5 h-5 flex items-center justify-center rounded border {{ $key == $q->correct_answer ? 'border-green-500 bg-green-50' : 'border-slate-300' }} text-xs">
                                                    {{ $key }}
                                                </span>
                                                <span>{{ $val }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Action Buttons (Edit & Delete) -->
                                <div class="absolute top-4 right-4 flex gap-2">
                                    <!-- TOMBOL EDIT: Mengirim data JSON ke fungsi Alpine -->
                                    <button 
                                        type="button"
                                        @click="openEdit({
                                            question_text: {{ json_encode($q->question_text) }},
                                            option_A: {{ json_encode($q->options['A'] ?? '') }},
                                            option_B: {{ json_encode($q->options['B'] ?? '') }},
                                            option_C: {{ json_encode($q->options['C'] ?? '') }},
                                            option_D: {{ json_encode($q->options['D'] ?? '') }},
                                            correct_answer: '{{ $q->correct_answer }}',
                                            score_weight: {{ $q->score_weight }}
                                        }, '{{ route('cbt.questions.update', $q->id) }}')"
                                        class="p-2 rounded-lg text-slate-300 hover:text-blue-500 hover:bg-blue-50 transition" 
                                        title="Edit Soal">
                                        <i class="ph-bold ph-pencil-simple text-lg"></i>
                                    </button>

                                    <!-- Tombol Delete -->
                                    <form action="{{ route('cbt.questions.destroy', $q->id) }}" method="POST" onsubmit="return confirm('Hapus soal ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 rounded-lg text-slate-300 hover:text-red-500 hover:bg-red-50 transition" title="Hapus Soal">
                                            <i class="ph-bold ph-trash text-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12 bg-white rounded-2xl border border-dashed border-slate-300">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-300">
                                    <i class="ph-duotone ph-list-dashes text-3xl"></i>
                                </div>
                                <p class="text-slate-500 font-medium">Belum ada soal yang dibuat.</p>
                                <p class="text-slate-400 text-sm">Silakan input soal melalui formulir di sebelah kiri atau import Excel.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL IMPORT EXCEL (Sama seperti sebelumnya) -->
        <div x-show="showImportModal" style="display: none;" 
             class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <div x-show="showImportModal" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="showImportModal = false"></div>
            <div class="flex min-h-screen items-center justify-center p-4 text-center sm:p-0">
                <div x-show="showImportModal" class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white px-6 py-6 pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-green-100 sm:mx-0 sm:h-10 sm:w-10">
                                <i class="ph-bold ph-microsoft-excel-logo text-green-600 text-xl"></i>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg font-bold leading-6 text-slate-900">Import Soal dari Excel</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-slate-500 mb-4">Silakan upload file Excel (.xlsx/.csv). Pastikan format kolom sesuai template.</p>
                                    <a href="{{ route('cbt.questions.template') }}" class="inline-flex items-center gap-2 text-sm font-bold text-blue-600 hover:text-blue-800 mb-6 bg-blue-50 px-3 py-2 rounded-lg border border-blue-100 w-full justify-center">
                                        <i class="ph-bold ph-download-simple"></i> Download Template CSV
                                    </a>
                                    <form action="{{ route('cbt.questions.import', $exam->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                        @csrf
                                        <div class="border-2 border-dashed border-slate-300 rounded-xl p-6 text-center hover:bg-slate-50 transition cursor-pointer relative">
                                            <input type="file" name="file" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                            <i class="ph-duotone ph-upload-simple text-3xl text-slate-400 mb-2"></i>
                                            <p class="text-sm font-medium text-slate-600">Klik untuk pilih file</p>
                                        </div>
                                        <div class="flex justify-end gap-3 mt-6">
                                            <button type="button" @click="showImportModal = false" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 font-bold rounded-lg hover:bg-slate-50">Batal</button>
                                            <button type="submit" class="px-4 py-2 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 shadow-lg shadow-green-500/30">Upload & Proses</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL EDIT SOAL (BARU) -->
        <div x-show="showEditModal" style="display: none;" 
             class="fixed inset-0 z-50 overflow-y-auto" role="dialog" aria-modal="true">
            <!-- Backdrop -->
            <div x-show="showEditModal" 
                 x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                 x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
                 class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" 
                 @click="showEditModal = false"></div>

            <div class="flex min-h-screen items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="showEditModal" 
                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-2xl">
                    
                    <!-- Form Edit -->
                    <form :action="editState.url" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') <!-- Penting: Method Spoofing untuk Update -->
                        
                        <div class="bg-white px-6 py-6 border-b border-slate-100 flex justify-between items-center">
                            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                <i class="ph-fill ph-pencil-simple text-blue-600"></i> Edit Soal
                            </h3>
                            <button type="button" @click="showEditModal = false" class="text-slate-400 hover:text-slate-600">
                                <i class="ph-bold ph-x text-lg"></i>
                            </button>
                        </div>

                        <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                            <!-- Pertanyaan -->
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Pertanyaan</label>
                                <textarea name="question_text" x-model="editState.question_text" rows="3" required class="w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>

                            <!-- Gambar Update -->
                            <div class="bg-blue-50 p-3 rounded-lg border border-blue-100">
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Ganti Gambar (Opsional)</label>
                                <input type="file" name="question_image" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-white file:text-blue-700 hover:file:bg-blue-50">
                                <p class="text-[10px] text-slate-500 mt-1 italic">*Biarkan kosong jika tidak ingin mengubah gambar.</p>
                            </div>

                            <!-- Opsi Jawaban -->
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-slate-500 uppercase">Pilihan Jawaban</label>
                                <div class="flex gap-2 items-center">
                                    <span class="w-6 text-center font-bold text-slate-400">A</span>
                                    <input type="text" name="option_A" x-model="editState.option_A" required class="flex-1 rounded-lg border-slate-300 text-sm py-1.5">
                                </div>
                                <div class="flex gap-2 items-center">
                                    <span class="w-6 text-center font-bold text-slate-400">B</span>
                                    <input type="text" name="option_B" x-model="editState.option_B" required class="flex-1 rounded-lg border-slate-300 text-sm py-1.5">
                                </div>
                                <div class="flex gap-2 items-center">
                                    <span class="w-6 text-center font-bold text-slate-400">C</span>
                                    <input type="text" name="option_C" x-model="editState.option_C" required class="flex-1 rounded-lg border-slate-300 text-sm py-1.5">
                                </div>
                                <div class="flex gap-2 items-center">
                                    <span class="w-6 text-center font-bold text-slate-400">D</span>
                                    <input type="text" name="option_D" x-model="editState.option_D" required class="flex-1 rounded-lg border-slate-300 text-sm py-1.5">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <!-- Kunci Jawaban -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Kunci Jawaban</label>
                                    <select name="correct_answer" x-model="editState.correct_answer" required class="w-full rounded-lg border-slate-300 text-sm font-bold text-slate-700">
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                        <option value="D">D</option>
                                    </select>
                                </div>
                                <!-- Bobot Nilai -->
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1">Bobot Nilai</label>
                                    <input type="number" name="score_weight" x-model="editState.score_weight" required class="w-full rounded-lg border-slate-300 text-sm">
                                </div>
                            </div>
                        </div>

                        <div class="bg-slate-50 px-6 py-4 flex justify-end gap-3 rounded-b-2xl">
                            <button type="button" @click="showEditModal = false" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 font-bold rounded-lg hover:bg-slate-100 transition">
                                Batal
                            </button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition shadow-lg shadow-blue-500/30">
                                Update Soal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>