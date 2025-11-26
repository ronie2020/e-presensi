{{-- Halaman ini adalah tampilan untuk resources/views/students/edit.blade.php --}}
<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header --}}
            <div class="mb-6 flex justify-between items-end">
                <div>
                    <h1 class="text-3xl font-black text-gray-800">Edit Buku Induk Siswa</h1>
                    <p class="text-gray-500 text-sm">Lengkapi data detail siswa: <span class="font-bold text-violet-600">{{ $student->name }}</span></p>
                </div>
                <a href="{{ route('students.index') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-xl text-sm font-bold text-gray-700 hover:bg-gray-50">
                    &larr; Kembali
                </a>
            </div>

            {{-- Tampilkan Error Validasi --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-xl text-sm">
                    <ul class="list-disc list-inside font-bold">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden" x-data="{ tab: 'pribadi' }">
                
               
                {{-- TAB NAVIGATION --}}
                <div class="bg-gray-50 border-b border-gray-200 px-6 pt-4 flex gap-6 overflow-x-auto">
                    <button type="button" @click="tab = 'pribadi'" :class="{ 'border-violet-600 text-violet-700': tab === 'pribadi', 'border-transparent text-gray-500 hover:text-gray-700': tab !== 'pribadi' }" class="pb-3 border-b-2 font-bold text-sm whitespace-nowrap transition-colors">
                        A. Pribadi
                    </button>
                    <button type="button" @click="tab = 'tempat_tinggal'" :class="{ 'border-violet-600 text-violet-700': tab === 'tempat_tinggal', 'border-transparent text-gray-500 hover:text-gray-700': tab !== 'tempat_tinggal' }" class="pb-3 border-b-2 font-bold text-sm whitespace-nowrap transition-colors">
                        B. Alamat
                    </button>
                    <button type="button" @click="tab = 'kesehatan'" :class="{ 'border-violet-600 text-violet-700': tab === 'kesehatan', 'border-transparent text-gray-500 hover:text-gray-700': tab !== 'kesehatan' }" class="pb-3 border-b-2 font-bold text-sm whitespace-nowrap transition-colors">
                        C. Kesehatan
                    </button>
                    <button type="button" @click="tab = 'pendidikan'" :class="{ 'border-violet-600 text-violet-700': tab === 'pendidikan', 'border-transparent text-gray-500 hover:text-gray-700': tab !== 'pendidikan' }" class="pb-3 border-b-2 font-bold text-sm whitespace-nowrap transition-colors">
                        D. Pendidikan
                    </button>
                    <button type="button" @click="tab = 'orangtua'" :class="{ 'border-violet-600 text-violet-700': tab === 'orangtua', 'border-transparent text-gray-500 hover:text-gray-700': tab !== 'orangtua' }" class="pb-3 border-b-2 font-bold text-sm whitespace-nowrap transition-colors">
                        E. Ortu & Wali
                    </button>
                    {{-- TAB BARU --}}
                    <button type="button" @click="tab = 'tamat'" :class="{ 'border-violet-600 text-violet-700': tab === 'tamat', 'border-transparent text-gray-500 hover:text-gray-700': tab !== 'tamat' }" class="pb-3 border-b-2 font-bold text-sm whitespace-nowrap transition-colors">
                        F. Mutasi & Tamat
                    </button>
                </div>

                <form action="{{ route('students.update', $student->id) }}" method="POST" enctype="multipart/form-data" class="p-8">
                    @csrf
                    @method('PUT')
                    
                    {{-- TAB A: PRIBADI --}}
                    <div x-show="tab === 'pribadi'" class="space-y-8">
                        
                        {{-- BAGIAN UPLOAD FOTO --}}
                        <div class="flex flex-col md:flex-row gap-8 items-start border-b border-gray-100 pb-8">
                            <div class="w-full md:w-1/4 flex flex-col items-center gap-4">
                                <div class="relative group">
                                    <div class="w-40 h-48 bg-gray-100 rounded-2xl border-2 border-dashed border-gray-300 flex items-center justify-center overflow-hidden shadow-sm">
                                        @if($student->photo_path)
                                            <img id="photo-preview" src="{{ asset('storage/' . $student->photo_path) }}" alt="Foto Siswa" class="w-full h-full object-cover">
                                        @else
                                            <img id="photo-preview" src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=random&size=200" alt="Placeholder" class="w-full h-full object-cover opacity-50">
                                        @endif
                                    </div>
                                    <label for="photo-input" class="absolute inset-0 flex items-center justify-center bg-black/50 text-white text-xs font-bold opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer rounded-2xl">
                                        Ganti Foto
                                    </label>
                                </div>
                                <div class="text-center">
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Pas Foto (3x4)</label>
                                    <input type="file" name="photo" id="photo-input" accept="image/*" class="block w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100"/>
                                    <p class="text-[10px] text-gray-400 mt-1">Format: JPG/PNG, Max: 2MB</p>
                                </div>
                            </div>

                            <div class="w-full md:w-3/4 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Lengkap *</label>
                                    <input type="text" name="name" value="{{ old('name', $student->name) }}" required class="w-full rounded-xl border-gray-300 focus:border-violet-500 focus:ring-violet-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Panggilan</label>
                                    <input type="text" name="nickname" value="{{ old('nickname', $student->nickname) }}" class="w-full rounded-xl border-gray-300 focus:border-violet-500 focus:ring-violet-500">
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">NIS (Sekolah)</label>
                                    <input type="text" name="nis" value="{{ old('nis', $student->nis) }}" placeholder="2021..." class="w-full rounded-xl border-gray-300 focus:border-violet-500 font-mono">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">NISN (Nasional) *</label>
                                    <input type="text" name="student_id" value="{{ old('student_id', $student->student_id) }}" required class="w-full rounded-xl border-gray-300 focus:border-violet-500 font-mono bg-gray-50">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tempat, Tanggal Lahir</label>
                                    <div class="flex gap-2">
                                        <input type="text" name="pob" value="{{ old('pob', $student->pob) }}" placeholder="Kota" class="w-1/2 rounded-xl border-gray-300 focus:border-violet-500">
                                        <input type="date" name="dob" value="{{ old('dob', $student->dob) }}" class="w-1/2 rounded-xl border-gray-300 focus:border-violet-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Jenis Kelamin</label>
                                    <select name="gender" class="w-full rounded-xl border-gray-300 focus:border-violet-500">
                                        <option value="L" {{ old('gender', $student->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="P" {{ old('gender', $student->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Agama</label>
                                    <select name="religion" class="w-full rounded-xl border-gray-300 focus:border-violet-500">
                                        <option value="Islam" {{ old('religion', $student->religion) == 'Islam' ? 'selected' : '' }}>Islam</option>
                                        <option value="Kristen" {{ old('religion', $student->religion) == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                        <option value="Katolik" {{ old('religion', $student->religion) == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                        <option value="Hindu" {{ old('religion', $student->religion) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                        <option value="Buddha" {{ old('religion', $student->religion) == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                        <option value="Konghucu" {{ old('religion', $student->religion) == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Kewarganegaraan</label>
                                    <input type="text" name="citizenship" value="{{ old('citizenship', $student->citizenship ?? 'WNI') }}" class="w-full rounded-xl border-gray-300 focus:border-violet-500">
                                </div>

                                {{-- FIELD TAMBAHAN YANG HILANG KEMARIN --}}
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Anak ke-</label>
                                        <input type="number" name="birth_order" value="{{ old('birth_order', $student->birth_order) }}" class="w-full rounded-xl border-gray-300 focus:border-violet-500">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Status Yatim</label>
                                        <select name="orphan_status" class="w-full rounded-xl border-gray-300 focus:border-violet-500">
                                            <option value="Lengkap" {{ old('orphan_status', $student->orphan_status) == 'Lengkap' ? 'selected' : '' }}>Lengkap</option>
                                            <option value="Yatim" {{ old('orphan_status', $student->orphan_status) == 'Yatim' ? 'selected' : '' }}>Yatim</option>
                                            <option value="Piatu" {{ old('orphan_status', $student->orphan_status) == 'Piatu' ? 'selected' : '' }}>Piatu</option>
                                            <option value="Yatim Piatu" {{ old('orphan_status', $student->orphan_status) == 'Yatim Piatu' ? 'selected' : '' }}>Yatim Piatu</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Jumlah Saudara</label>
                                    <div class="flex gap-2 text-sm items-center">
                                        <input type="number" name="siblings_count" value="{{ old('siblings_count', $student->siblings_count) }}" placeholder="Kandung" class="w-1/3 rounded-xl border-gray-300 focus:border-violet-500" title="Kandung">
                                        <input type="number" name="step_siblings_count" value="{{ old('step_siblings_count', $student->step_siblings_count) }}" placeholder="Tiri" class="w-1/3 rounded-xl border-gray-300 focus:border-violet-500" title="Tiri">
                                        <input type="number" name="adoptive_siblings_count" value="{{ old('adoptive_siblings_count', $student->adoptive_siblings_count) }}" placeholder="Angkat" class="w-1/3 rounded-xl border-gray-300 focus:border-violet-500" title="Angkat">
                                    </div>
                                    <p class="text-[10px] text-gray-400 mt-1 ml-1">Urutan: Kandung / Tiri / Angkat</p>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Bahasa Sehari-hari</label>
                                    <input type="text" name="daily_language" value="{{ old('daily_language', $student->daily_language) }}" class="w-full rounded-xl border-gray-300 focus:border-violet-500">
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Kelas Saat Ini *</label>
                                    <select name="class_id" required class="w-full rounded-xl border-gray-300 focus:border-violet-500 focus:ring-violet-500">
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach($classes as $c)
                                            <option value="{{ $c->id }}" {{ old('class_id', $student->class_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">RFID ID (Kartu)</label>
                                    <input type="text" name="rfid_id" value="{{ old('rfid_id', $student->rfid_id) }}" class="w-full rounded-xl border-gray-300 focus:border-violet-500 font-mono bg-gray-50">
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- TAB B: TEMPAT TINGGAL --}}
                    <div x-show="tab === 'tempat_tinggal'" class="space-y-6" style="display: none;">
                        <h3 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4">Keterangan Tempat Tinggal</h3>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Alamat Lengkap</label>
                            <textarea name="address" rows="3" class="w-full rounded-xl border-gray-300 focus:border-violet-500 focus:ring-violet-500">{{ old('address', $student->address) }}</textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nomor Telepon/HP</label>
                                <input type="text" name="phone" value="{{ old('phone', $student->phone) }}" class="w-full rounded-xl border-gray-300 focus:border-violet-500 focus:ring-violet-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tinggal Bersama</label>
                                <select name="living_with" class="w-full rounded-xl border-gray-300 focus:border-violet-500 focus:ring-violet-500">
                                    <option value="Orang Tua" {{ old('living_with', $student->living_with) == 'Orang Tua' ? 'selected' : '' }}>Orang Tua</option>
                                    <option value="Wali" {{ old('living_with', $student->living_with) == 'Wali' ? 'selected' : '' }}>Wali</option>
                                    <option value="Asrama" {{ old('living_with', $student->living_with) == 'Asrama' ? 'selected' : '' }}>Asrama</option>
                                    <option value="Kost" {{ old('living_with', $student->living_with) == 'Kost' ? 'selected' : '' }}>Kost</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Jarak ke Sekolah</label>
                                <input type="text" name="distance_to_school" value="{{ old('distance_to_school', $student->distance_to_school) }}" placeholder="Contoh: 1 km" class="w-full rounded-xl border-gray-300 focus:border-violet-500 focus:ring-violet-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Transportasi</label>
                                <input type="text" name="transport_mode" value="{{ old('transport_mode', $student->transport_mode) }}" placeholder="Jalan Kaki/Motor/Angkot" class="w-full rounded-xl border-gray-300 focus:border-violet-500 focus:ring-violet-500">
                            </div>
                        </div>
                    </div>

                    {{-- TAB C: KESEHATAN --}}
                    <div x-show="tab === 'kesehatan'" class="space-y-6" style="display: none;">
                        <h3 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4">Keterangan Kesehatan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Golongan Darah</label>
                                <select name="blood_type" class="w-full rounded-xl border-gray-300 focus:border-violet-500 focus:ring-violet-500">
                                    <option value="-" {{ old('blood_type', $student->blood_type) == '-' ? 'selected' : '' }}>-</option>
                                    <option value="A" {{ old('blood_type', $student->blood_type) == 'A' ? 'selected' : '' }}>A</option>
                                    <option value="B" {{ old('blood_type', $student->blood_type) == 'B' ? 'selected' : '' }}>B</option>
                                    <option value="AB" {{ old('blood_type', $student->blood_type) == 'AB' ? 'selected' : '' }}>AB</option>
                                    <option value="O" {{ old('blood_type', $student->blood_type) == 'O' ? 'selected' : '' }}>O</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Berat Badan (kg)</label>
                                <input type="number" name="weight" value="{{ old('weight', $student->weight) }}" step="0.1" class="w-full rounded-xl border-gray-300 focus:border-violet-500 focus:ring-violet-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tinggi Badan (cm)</label>
                                <input type="number" name="height" value="{{ old('height', $student->height) }}" class="w-full rounded-xl border-gray-300 focus:border-violet-500 focus:ring-violet-500">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Riwayat Penyakit</label>
                            <input type="text" name="history_disease" value="{{ old('history_disease', $student->history_disease) }}" class="w-full rounded-xl border-gray-300 focus:border-violet-500 focus:ring-violet-500">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Kelainan Jasmani</label>
                            <input type="text" name="physical_abnormalities" value="{{ old('physical_abnormalities', $student->physical_abnormalities) }}" class="w-full rounded-xl border-gray-300 focus:border-violet-500 focus:ring-violet-500">
                        </div>
                    </div>

                    {{-- TAB D: PENDIDIKAN --}}
                    <div x-show="tab === 'pendidikan'" class="space-y-6" style="display: none;">
                        <h3 class="text-lg font-bold text-gray-800 border-b pb-2 mb-4">Pendidikan Sebelumnya</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Asal Sekolah Dasar (SD)</label>
                                <input type="text" name="prev_school_name" value="{{ old('prev_school_name', $student->prev_school_name) }}" class="w-full rounded-xl border-gray-300 focus:border-violet-500 focus:ring-violet-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">No. Ijazah</label>
                                <input type="text" name="prev_diploma_no" value="{{ old('prev_diploma_no', $student->prev_diploma_no) }}" class="w-full rounded-xl border-gray-300 focus:border-violet-500 focus:ring-violet-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tanggal Ijazah</label>
                                <input type="date" name="prev_exam_date" value="{{ old('prev_exam_date', $student->prev_exam_date) }}" class="w-full rounded-xl border-gray-300 focus:border-violet-500 focus:ring-violet-500">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tanggal Diterima di Sekolah Ini</label>
                                <input type="date" name="accepted_date" value="{{ old('accepted_date', $student->accepted_date) }}" class="w-full rounded-xl border-gray-300 focus:border-violet-500 focus:ring-violet-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Pindahan Dari Sekolah (Jika Pindahan)</label>
                                <input type="text" name="transfer_from_school" value="{{ old('transfer_from_school', $student->transfer_from_school) }}" class="w-full rounded-xl border-gray-300 focus:border-violet-500 focus:ring-violet-500">
                            </div>
                        </div>
                    </div>
  {{-- UPDATE TAB E: ORANG TUA & WALI --}}
                    <div x-show="tab === 'orangtua'" class="space-y-6" style="display: none;">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            {{-- Data Ayah & Ibu (Sama seperti sebelumnya) --}}
                            {{-- ... paste kode input ayah/ibu disini ... --}}
                            <div class="space-y-4">
                                <h4 class="font-bold text-violet-700 border-b pb-2">Data Ayah Kandung</h4>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Ayah</label>
                                    <input type="text" name="father_name" value="{{ old('father_name', $student->father_name) }}" class="w-full rounded-xl border-gray-300 focus:border-violet-500 focus:ring-violet-500">
                                </div>
                                {{-- ... field ayah lainnya ... --}}
                            </div>
                            <div class="space-y-4">
                                <h4 class="font-bold text-pink-600 border-b pb-2">Data Ibu Kandung</h4>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Ibu</label>
                                    <input type="text" name="mother_name" value="{{ old('mother_name', $student->mother_name) }}" class="w-full rounded-xl border-gray-300 focus:border-violet-500 focus:ring-violet-500">
                                </div>
                                {{-- ... field ibu lainnya ... --}}
                            </div>
                        </div>
                        
                        {{-- UPDATE DATA WALI --}}
                        <div class="border-t pt-6">
                            <h4 class="font-bold text-gray-800 border-b pb-2 mb-4">Data Wali (Sesuai Foto Baru)</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nama Wali</label>
                                    <input type="text" name="guardian_name" value="{{ old('guardian_name', $student->guardian_name) }}" class="w-full rounded-xl border-gray-300 focus:border-violet-500 focus:ring-violet-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Tempat, Tanggal Lahir</label>
                                    <div class="flex gap-2">
                                        <input type="text" name="guardian_pob" value="{{ old('guardian_pob', $student->guardian_pob) }}" placeholder="Kota" class="w-1/2 rounded-xl border-gray-300 focus:border-violet-500">
                                        <input type="date" name="guardian_dob" value="{{ old('guardian_dob', $student->guardian_dob) }}" class="w-1/2 rounded-xl border-gray-300 focus:border-violet-500">
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Kewarganegaraan</label>
                                    <input type="text" name="guardian_citizenship" value="{{ old('guardian_citizenship', $student->guardian_citizenship) }}" class="w-full rounded-xl border-gray-300 focus:border-violet-500" placeholder="WNI">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Pekerjaan</label>
                                    <input type="text" name="guardian_job" value="{{ old('guardian_job', $student->guardian_job) }}" class="w-full rounded-xl border-gray-300 focus:border-violet-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Penghasilan / Bulan</label>
                                    <input type="text" name="guardian_income" value="{{ old('guardian_income', $student->guardian_income) }}" class="w-full rounded-xl border-gray-300 focus:border-violet-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Alamat Wali</label>
                                    <input type="text" name="guardian_address" value="{{ old('guardian_address', $student->guardian_address) }}" class="w-full rounded-xl border-gray-300 focus:border-violet-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Hubungan Keluarga</label>
                                    <input type="text" name="guardian_relationship" value="{{ old('guardian_relationship', $student->guardian_relationship) }}" class="w-full rounded-xl border-gray-300 focus:border-violet-500" placeholder="Paman / Kakek">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TAB F: MENINGGALKAN SEKOLAH (BARU) --}}
                    <div x-show="tab === 'tamat'" class="space-y-8" style="display: none;">
                        
                        {{-- MENINGGALKAN SEKOLAH (LULUS) --}}
                        <div class="bg-emerald-50 p-6 rounded-2xl border border-emerald-100">
                            <h3 class="font-bold text-emerald-800 mb-4">I. Tamat Belajar (Lulus)</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-emerald-600 uppercase mb-1">Tanggal Tamat</label>
                                    <input type="date" name="graduated_date" value="{{ old('graduated_date', $student->graduated_date) }}" class="w-full rounded-xl border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-emerald-600 uppercase mb-1">No. Ijazah</label>
                                    <input type="text" name="graduated_diploma_no" value="{{ old('graduated_diploma_no', $student->graduated_diploma_no) }}" class="w-full rounded-xl border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-emerald-600 uppercase mb-1">Melanjutkan Ke</label>
                                    <input type="text" name="continuing_to_school" value="{{ old('continuing_to_school', $student->continuing_to_school) }}" placeholder="Nama SMA/SMK" class="w-full rounded-xl border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-emerald-600 uppercase mb-1">Alamat Sekolah Lanjutan</label>
                                    <input type="text" name="continuing_school_address" value="{{ old('continuing_school_address', $student->continuing_school_address) }}" class="w-full rounded-xl border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500">
                                </div>
                            </div>
                        </div>

                        {{-- PINDAH SEKOLAH --}}
                        <div class="bg-amber-50 p-6 rounded-2xl border border-amber-100">
                            <h3 class="font-bold text-amber-800 mb-4">II. Pindah Sekolah (Mutasi Keluar)</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-amber-600 uppercase mb-1">Tanggal Pindah</label>
                                    <input type="date" name="leaving_date" value="{{ old('leaving_date', $student->leaving_date) }}" class="w-full rounded-xl border-amber-200 focus:border-amber-500 focus:ring-amber-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-amber-600 uppercase mb-1">Dari Kelas</label>
                                    <input type="text" name="leaving_class" value="{{ old('leaving_class', $student->leaving_class) }}" class="w-full rounded-xl border-amber-200 focus:border-amber-500 focus:ring-amber-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-amber-600 uppercase mb-1">Pindah Ke Sekolah</label>
                                    <input type="text" name="leaving_to_school" value="{{ old('leaving_to_school', $student->leaving_to_school) }}" class="w-full rounded-xl border-amber-200 focus:border-amber-500 focus:ring-amber-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-amber-600 uppercase mb-1">Alasan Pindah</label>
                                    <input type="text" name="leaving_reason" value="{{ old('leaving_reason', $student->leaving_reason) }}" class="w-full rounded-xl border-amber-200 focus:border-amber-500 focus:ring-amber-500">
                                </div>
                            </div>
                        </div>

                        {{-- PUTUS SEKOLAH --}}
                        <div class="bg-rose-50 p-6 rounded-2xl border border-rose-100">
                            <h3 class="font-bold text-rose-800 mb-4">III. Putus Sekolah</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-rose-600 uppercase mb-1">Tanggal Putus</label>
                                    <input type="date" name="dropout_date" value="{{ old('dropout_date', $student->dropout_date) }}" class="w-full rounded-xl border-rose-200 focus:border-rose-500 focus:ring-rose-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-rose-600 uppercase mb-1">Alasan</label>
                                    <input type="text" name="dropout_reason" value="{{ old('dropout_reason', $student->dropout_reason) }}" class="w-full rounded-xl border-rose-200 focus:border-rose-500 focus:ring-rose-500">
                                </div>
                            </div>
                        </div>

                        {{-- LAIN-LAIN & BEASISWA --}}
                        <div class="bg-gray-50 p-6 rounded-2xl border border-gray-200">
                            <h3 class="font-bold text-gray-800 mb-4">Lain-lain</h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Beasiswa (Tahun / Kelas / Sumber)</label>
                                    <textarea name="scholarship_info" rows="2" class="w-full rounded-xl border-gray-300 focus:border-violet-500">{{ old('scholarship_info', $student->scholarship_info) }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Catatan Penting Selama Siswa Belajar</label>
                                    <textarea name="general_notes" rows="3" class="w-full rounded-xl border-gray-300 focus:border-violet-500">{{ old('general_notes', $student->general_notes) }}</textarea>
                                </div>
                            </div>
                        </div>

                    </div>


                    {{-- FOOTER --}}
                    <div class="mt-8 pt-6 border-t border-gray-100 flex justify-between items-center">
                        <p class="text-xs text-gray-400 italic">* Pastikan data sudah benar sebelum disimpan.</p>
                        <button type="submit" class="px-8 py-3 bg-violet-600 text-white font-bold rounded-xl hover:bg-violet-700 shadow-lg shadow-violet-200 transition-all transform hover:scale-105">
                            Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
        // Script sederhana untuk preview foto sebelum upload
        document.getElementById('photo-input').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('photo-preview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
</x-app-layout>