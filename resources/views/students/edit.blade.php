{{-- Halaman ini adalah tampilan untuk resources/views/students/edit.blade.php --}}
<x-app-layout>
    {{-- Flatpickr CSS (Untuk Datepicker Cantik) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/airbnb.css">

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header --}}
            <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-end gap-4">
                <div>
                    <h1 class="text-3xl font-black text-blue-900 tracking-tight">Edit Buku Induk Siswa</h1>
                    <p class="text-slate-500 text-sm mt-1">Lengkapi data detail siswa: <span class="font-bold text-blue-900 bg-blue-50 px-2 py-0.5 rounded">{{ $student->name }}</span></p>
                </div>
                {{-- Tombol Kembali menyesuaikan status siswa --}}
                @php
                    $backUrl = $student->status === 'graduated' ? route('admin.alumni.index') : route('students.index', request()->query());
                @endphp
                <a href="{{ $backUrl }}" class="btn-back-confirm px-5 py-2.5 bg-white border border-slate-300 rounded-xl text-sm font-bold text-slate-600 hover:bg-blue-50 hover:text-blue-900 hover:border-blue-200 transition-all shadow-sm flex items-center gap-2 group">
                    <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali
                </a>
            </div>

            {{-- Tampilkan Error Validasi --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-xl text-sm flex items-start gap-3 shadow-sm">
                    <i class="ph-fill ph-warning-circle text-lg shrink-0 mt-0.5"></i>
                    <div>
                        <span class="font-bold block mb-1">Gagal Menyimpan Perubahan:</span>
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- ========================================================= --}}
            {{-- BANNER PERINGATAN JIKA STATUS SISWA ADALAH ALUMNI / LULUS --}}
            {{-- ========================================================= --}}
            @if($student->status === 'graduated')
                <div class="mb-6 p-5 bg-amber-50 border border-amber-200 rounded-2xl flex flex-col md:flex-row items-start md:items-center gap-4 shadow-sm relative overflow-hidden">
                    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.05]"></div>
                    <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center shrink-0 text-amber-600 border border-amber-200 relative z-10">
                        <i class="ph-duotone ph-warning text-2xl"></i>
                    </div>
                    <div class="relative z-10 flex-1">
                        <h3 class="font-black text-amber-800 text-lg">Mode Edit Arsip Alumni Terbuka</h3>
                        <p class="text-amber-700/80 text-sm font-medium mt-0.5">
                            Siswa ini telah berstatus <b>Alumni (Lulus)</b>. Segala perubahan yang Anda lakukan akan langsung memperbarui arsip permanen Buku Induk sekolah.
                        </p>
                    </div>
                    <div class="relative z-10 shrink-0 mt-2 md:mt-0">
                        <a href="{{ route('admin.alumni.edit', $student->id) }}" class="px-4 py-2 bg-white border border-amber-300 text-amber-700 font-bold rounded-xl hover:bg-amber-100 transition-all text-sm flex items-center gap-2 shadow-sm">
                            <i class="ph-bold ph-graduation-cap"></i> Form Tracer Study
                        </a>
                    </div>
                </div>
            @endif

            {{-- FORM TAMPIL UNTUK SEMUA --}}
            <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden" x-data="{ tab: 'pribadi' }">
                
                {{-- TAB NAVIGATION --}}
                <div class="bg-blue-50/30 border-b border-slate-200 px-6 pt-4 flex gap-2 overflow-x-auto custom-scrollbar">
                        @foreach(['pribadi' => 'A. Pribadi', 'tempat_tinggal' => 'B. Alamat', 'kesehatan' => 'C. Kesehatan', 'pendidikan' => 'D. Pendidikan', 'orangtua' => 'E. Ortu & Wali', 'tamat' => 'F. Mutasi & Tamat'] as $key => $label)
                            <button type="button" @click="tab = '{{ $key }}'" 
                                :class="{ 
                                    'bg-white text-blue-900 shadow-sm border-slate-200 border-b-white ring-t-2 ring-blue-900': tab === '{{ $key }}', 
                                    'text-slate-500 hover:text-blue-700 hover:bg-blue-50/50 border-transparent': tab !== '{{ $key }}' 
                                }" 
                                class="px-5 py-3 rounded-t-xl border-t border-x font-bold text-sm whitespace-nowrap transition-all relative z-10 flex items-center gap-2">
                                <span x-show="tab === '{{ $key }}'" class="w-1.5 h-1.5 rounded-full bg-blue-900"></span>
                                {{ $label }}
                            </button>
                        @endforeach
                    </div>

                    {{-- FORM UPDATE dengan parameter query aktif dibawa saat submit --}}
                    <form id="edit-student-form" action="{{ route('students.update', array_merge(['student' => $student->id], request()->query())) }}" method="POST" enctype="multipart/form-data" class="p-8">
                        @csrf
                        @method('PUT')
                        
                        {{-- TAB A: PRIBADI --}}
                        <div x-show="tab === 'pribadi'" class="space-y-8">
                            
                            {{-- FOTO & IDENTITAS --}}
                            <div class="flex flex-col md:flex-row gap-8 items-start border-b border-slate-100 pb-8">
                                <div class="w-full md:w-1/4 flex flex-col items-center gap-4">
                                    <div class="relative group">
                                        <div class="w-40 h-48 bg-slate-50 rounded-2xl border-2 border-dashed border-slate-300 group-hover:border-blue-500 flex items-center justify-center overflow-hidden shadow-sm transition-colors">
                                            @if($student->photo_path)
                                                <img id="photo-preview" src="{{ asset('storage/' . $student->photo_path) }}" alt="Foto Siswa" class="w-full h-full object-cover">
                                            @else
                                                <img id="photo-preview" src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=random&size=200" alt="Placeholder" class="w-full h-full object-cover opacity-50">
                                            @endif
                                        </div>
                                        <label for="photo-input" class="absolute inset-0 flex items-center justify-center bg-blue-900/60 backdrop-blur-[2px] text-white text-xs font-bold opacity-0 group-hover:opacity-100 transition-all cursor-pointer rounded-2xl">
                                            <i class="ph-bold ph-camera mr-2"></i> Ganti Foto
                                        </label>
                                    </div>
                                    <div class="text-center">
                                        <input type="file" name="photo" id="photo-input" accept="image/*" class="hidden"/>
                                        <p class="text-[10px] text-slate-400 mt-1 font-medium">Format: JPG/PNG, Max: 2MB</p>
                                    </div>
                                </div>

                                <div class="w-full md:w-3/4 grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Nama Lengkap *</label>
                                        <input type="text" name="name" value="{{ old('name', $student->name) }}" required class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 font-bold text-slate-800 tracking-wide input-watch">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Nama Panggilan</label>
                                        <input type="text" name="nickname" value="{{ old('nickname', $student->nickname) }}" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">NIS (Sekolah)</label>
                                        <input type="text" name="nis" value="{{ old('nis', $student->nis) }}" placeholder="Nomor Induk Sekolah" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 font-mono input-watch">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">NISN (Nasional) *</label>
                                        <input type="text" name="student_id" value="{{ old('student_id', $student->student_id) }}" required class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 font-mono bg-blue-50/30 text-blue-900 font-bold input-watch">
                                    </div>

                                    {{-- UPDATE: PENAMBAHAN NIK --}}
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">NIK (Kependudukan)</label>
                                        <input type="text" name="nik" value="{{ old('nik', $student->nik) }}" placeholder="Nomor Induk Kependudukan 16 Digit" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 font-mono input-watch">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Kelas Saat Ini {{ $student->status === 'graduated' ? '' : '*' }}</label>
                                        <select name="class_id" {{ $student->status === 'graduated' ? '' : 'required' }} class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch">
                                            <option value="">-- Pilih Kelas --</option>
                                            @foreach($classes as $c)
                                                <option value="{{ $c->id }}" {{ old('class_id', $student->class_id) == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Tempat, Tanggal Lahir</label>
                                        <div class="flex gap-2">
                                            <input type="text" name="pob" value="{{ old('pob', $student->pob) }}" placeholder="Kota" class="w-1/2 rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch">
                                            <div class="relative w-1/2">
                                                <input type="text" name="dob" value="{{ old('dob', $student->dob ? \Carbon\Carbon::parse($student->dob)->format('Y-m-d') : '') }}" placeholder="dd/mm/yyyy" class="datepicker w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 bg-white input-watch">
                                                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-calendar-blank"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Jenis Kelamin</label>
                                        <select name="gender" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch">
                                            <option value="L" {{ old('gender', $student->gender) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                            <option value="P" {{ old('gender', $student->gender) == 'P' ? 'selected' : '' }}>Perempuan</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Agama</label>
                                        <select name="religion" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch">
                                            <option value="Islam" {{ old('religion', $student->religion) == 'Islam' ? 'selected' : '' }}>Islam</option>
                                            <option value="Kristen" {{ old('religion', $student->religion) == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                                            <option value="Katolik" {{ old('religion', $student->religion) == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                                            <option value="Hindu" {{ old('religion', $student->religion) == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                            <option value="Buddha" {{ old('religion', $student->religion) == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                            <option value="Konghucu" {{ old('religion', $student->religion) == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Kewarganegaraan</label>
                                        <input type="text" name="citizenship" value="{{ old('citizenship', $student->citizenship ?? 'WNI') }}" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch">
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Anak ke-</label>
                                        <input type="number" name="birth_order" value="{{ old('birth_order', $student->birth_order) }}" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Status Yatim</label>
                                        <select name="orphan_status" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch">
                                            <option value="Lengkap" {{ old('orphan_status', $student->orphan_status) == 'Lengkap' ? 'selected' : '' }}>Lengkap</option>
                                            <option value="Yatim" {{ old('orphan_status', $student->orphan_status) == 'Yatim' ? 'selected' : '' }}>Yatim</option>
                                            <option value="Piatu" {{ old('orphan_status', $student->orphan_status) == 'Piatu' ? 'selected' : '' }}>Piatu</option>
                                            <option value="Yatim Piatu" {{ old('orphan_status', $student->orphan_status) == 'Yatim Piatu' ? 'selected' : '' }}>Yatim Piatu</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Jumlah Saudara</label>
                                        <div class="flex gap-2 text-sm items-center">
                                            <input type="number" name="siblings_count" value="{{ old('siblings_count', $student->siblings_count) }}" placeholder="Kandung" class="w-1/3 rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 text-center input-watch" title="Kandung">
                                            <input type="number" name="step_siblings_count" value="{{ old('step_siblings_count', $student->step_siblings_count) }}" placeholder="Tiri" class="w-1/3 rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 text-center input-watch" title="Tiri">
                                            <input type="number" name="adoptive_siblings_count" value="{{ old('adoptive_siblings_count', $student->adoptive_siblings_count) }}" placeholder="Angkat" class="w-1/3 rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 text-center input-watch" title="Angkat">
                                        </div>
                                        <p class="text-[10px] text-slate-400 mt-1 ml-1">Urutan: Kandung / Tiri / Angkat</p>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Bahasa Sehari-hari</label>
                                        <input type="text" name="daily_language" value="{{ old('daily_language', $student->daily_language) }}" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch">
                                    </div>
                                    
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">RFID ID (Kartu Absensi / Perpus)</label>
                                        <input type="text" name="rfid_id" value="{{ old('rfid_id', $student->rfid_id) }}" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 font-mono bg-slate-50 input-watch">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- TAB B: TEMPAT TINGGAL --}}
                        <div x-show="tab === 'tempat_tinggal'" class="space-y-6" style="display: none;">
                            <h3 class="text-lg font-bold text-blue-900 border-b border-slate-100 pb-2 mb-4">Keterangan Tempat Tinggal</h3>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Alamat Lengkap</label>
                                <textarea name="address" rows="3" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch">{{ old('address', $student->address) }}</textarea>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Nomor Telepon/HP (Siswa)</label>
                                    <input type="text" name="phone" value="{{ old('phone', $student->phone) }}" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Tinggal Bersama</label>
                                    <select name="living_with" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch">
                                        <option value="Orang Tua" {{ old('living_with', $student->living_with) == 'Orang Tua' ? 'selected' : '' }}>Orang Tua</option>
                                        <option value="Wali" {{ old('living_with', $student->living_with) == 'Wali' ? 'selected' : '' }}>Wali</option>
                                        <option value="Asrama" {{ old('living_with', $student->living_with) == 'Asrama' ? 'selected' : '' }}>Asrama</option>
                                        <option value="Kost" {{ old('living_with', $student->living_with) == 'Kost' ? 'selected' : '' }}>Kost</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Jarak ke Sekolah</label>
                                    <input type="text" name="distance_to_school" value="{{ old('distance_to_school', $student->distance_to_school) }}" placeholder="Contoh: 1 km" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Transportasi</label>
                                    <input type="text" name="transport_mode" value="{{ old('transport_mode', $student->transport_mode) }}" placeholder="Jalan Kaki/Motor/Angkot" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch">
                                </div>
                            </div>
                        </div>

                        {{-- TAB C: KESEHATAN --}}
                        <div x-show="tab === 'kesehatan'" class="space-y-6" style="display: none;">
                            <h3 class="text-lg font-bold text-blue-900 border-b border-slate-100 pb-2 mb-4">Keterangan Kesehatan</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Golongan Darah</label>
                                    <select name="blood_type" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch">
                                        <option value="">-</option>
                                        @foreach(['A', 'B', 'AB', 'O'] as $b)
                                            <option value="{{ $b }}" {{ old('blood_type', $student->blood_type) == $b ? 'selected' : '' }}>{{ $b }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Berat Badan (kg)</label>
                                    <input type="number" name="weight" value="{{ old('weight', $student->weight) }}" step="0.1" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Tinggi Badan (cm)</label>
                                    <input type="number" name="height" value="{{ old('height', $student->height) }}" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Riwayat Penyakit</label>
                                <input type="text" name="history_disease" value="{{ old('history_disease', $student->history_disease) }}" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Kelainan Jasmani</label>
                                <input type="text" name="physical_abnormalities" value="{{ old('physical_abnormalities', $student->physical_abnormalities) }}" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch">
                            </div>
                        </div>

                        {{-- TAB D: PENDIDIKAN --}}
                        <div x-show="tab === 'pendidikan'" class="space-y-6" style="display: none;">
                            <h3 class="text-lg font-bold text-blue-900 border-b border-slate-100 pb-2 mb-4">Pendidikan Sebelumnya</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Asal Sekolah Dasar (SD)</label>
                                    <input type="text" name="school_origin" value="{{ old('school_origin', $student->school_origin) }}" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">No. Ijazah</label>
                                    <input type="text" name="prev_diploma_no" value="{{ old('prev_diploma_no', $student->prev_diploma_no) }}" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Tanggal Ijazah</label>
                                    <div class="relative">
                                        <input type="text" name="prev_exam_date" value="{{ old('prev_exam_date', $student->prev_exam_date ? \Carbon\Carbon::parse($student->prev_exam_date)->format('Y-m-d') : '') }}" class="datepicker w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch" placeholder="dd/mm/yyyy">
                                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-calendar-blank"></i></div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Tanggal Diterima di Sekolah Ini</label>
                                    <div class="relative">
                                        <input type="text" name="accepted_date" value="{{ old('accepted_date', $student->accepted_date ? \Carbon\Carbon::parse($student->accepted_date)->format('Y-m-d') : '') }}" class="datepicker w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch" placeholder="dd/mm/yyyy">
                                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-calendar-blank"></i></div>
                                    </div>
                                </div>
                                
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Pindahan Dari Sekolah (Jika Pindahan)</label>
                                    <input type="text" name="transfer_from_school" value="{{ old('transfer_from_school', $student->transfer_from_school) }}" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch">
                                </div>
                            </div>
                        </div>

                        {{-- TAB E: ORANG TUA & WALI --}}
                        <div x-show="tab === 'orangtua'" class="space-y-6" style="display: none;">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="space-y-4">
                                    <h4 class="font-bold text-blue-900 border-b border-blue-100 pb-2">Data Ayah Kandung</h4>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Nama Ayah</label>
                                        <input type="text" name="father_name" value="{{ old('father_name', $student->father_name) }}" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Tempat, Tanggal Lahir</label>
                                        <div class="flex gap-2">
                                            <input type="text" name="father_pob" value="{{ old('father_pob', $student->father_pob) }}" placeholder="Kota" class="w-1/2 rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch">
                                            <div class="relative w-1/2">
                                                <input type="text" name="father_birth_year" value="{{ old('father_birth_year', $student->father_birth_year ? \Carbon\Carbon::parse($student->father_birth_year)->format('Y-m-d') : '') }}" class="datepicker w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch" placeholder="dd/mm/yyyy">
                                                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-calendar-blank"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- UPDATE: TAMBAHAN PENDIDIKAN AYAH --}}
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Pendidikan Tertinggi</label>
                                        <select name="father_education" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch">
                                            <option value="">- Pilih Pendidikan -</option>
                                            @foreach(['SD/Sederajat', 'SMP/Sederajat', 'SMA/Sederajat', 'D1/D2/D3', 'S1/D4', 'S2', 'S3'] as $edu)
                                                <option value="{{ $edu }}" {{ old('father_education', $student->father_education) == $edu ? 'selected' : '' }}>{{ $edu }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Pekerjaan</label>
                                        <input type="text" name="father_job" value="{{ old('father_job', $student->father_job) }}" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Penghasilan / Bulan</label>
                                        <input type="text" name="father_income" value="{{ old('father_income', $student->father_income) }}" placeholder="Misal: Rp 3.000.000" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch">
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    <h4 class="font-bold text-pink-600 border-b border-pink-100 pb-2">Data Ibu Kandung</h4>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Nama Ibu</label>
                                        <input type="text" name="mother_name" value="{{ old('mother_name', $student->mother_name) }}" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Tempat, Tanggal Lahir</label>
                                        <div class="flex gap-2">
                                            <input type="text" name="mother_pob" value="{{ old('mother_pob', $student->mother_pob) }}" placeholder="Kota" class="w-1/2 rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch">
                                            <div class="relative w-1/2">
                                                <input type="text" name="mother_birth_year" value="{{ old('mother_birth_year', $student->mother_birth_year ? \Carbon\Carbon::parse($student->mother_birth_year)->format('Y-m-d') : '') }}" class="datepicker w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch" placeholder="dd/mm/yyyy">
                                                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-calendar-blank"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- UPDATE: TAMBAHAN PENDIDIKAN IBU --}}
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Pendidikan Tertinggi</label>
                                        <select name="mother_education" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch">
                                            <option value="">- Pilih Pendidikan -</option>
                                            @foreach(['SD/Sederajat', 'SMP/Sederajat', 'SMA/Sederajat', 'D1/D2/D3', 'S1/D4', 'S2', 'S3'] as $edu)
                                                <option value="{{ $edu }}" {{ old('mother_education', $student->mother_education) == $edu ? 'selected' : '' }}>{{ $edu }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Pekerjaan</label>
                                        <input type="text" name="mother_job" value="{{ old('mother_job', $student->mother_job) }}" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch">
                                    </div>
                                    <div>
                                       <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Penghasilan / Bulan</label>
                                        <input type="text" name="mother_income" value="{{ old('mother_income', $student->mother_income) }}" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch">
                                    </div>
                                </div>
                            </div>

                            <div class="border-t border-slate-100 pt-6 mt-2">
                                <h4 class="font-bold text-emerald-700 border-b border-emerald-100 pb-2 mb-4">Kontak Utama Orang Tua (Notifikasi WA)</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">No. WhatsApp Wali Murid</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-emerald-500"><i class="ph-bold ph-whatsapp-logo"></i></div>
                                            <input type="text" name="parent_wa_number" value="{{ old('parent_wa_number', $student->parent_wa_number) }}" placeholder="Contoh: 081234567890" class="w-full pl-9 rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch font-mono text-slate-700">
                                        </div>
                                        <p class="text-[10px] text-slate-400 mt-1 ml-1">Digunakan sebagai tujuan utama notifikasi absensi dan broadcast.</p>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">No. Telepon Alternatif Ortu</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-phone"></i></div>
                                            <input type="text" name="parent_phone" value="{{ old('parent_phone', $student->parent_phone) }}" placeholder="Contoh: 081234567890" class="w-full pl-9 rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch font-mono text-slate-700">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="border-t border-slate-100 pt-6">
                                <h4 class="font-bold text-slate-700 border-b border-slate-100 pb-2 mb-4">Data Wali (Jika Ada)</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Nama Wali</label>
                                        <input type="text" name="guardian_name" value="{{ old('guardian_name', $student->guardian_name) }}" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Tempat, Tanggal Lahir</label>
                                        <div class="flex gap-2">
                                            <input type="text" name="guardian_pob" value="{{ old('guardian_pob', $student->guardian_pob) }}" placeholder="Kota" class="w-1/2 rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch">
                                            <div class="relative w-1/2">
                                                <input type="text" name="guardian_dob" value="{{ old('guardian_dob', $student->guardian_dob ? \Carbon\Carbon::parse($student->guardian_dob)->format('Y-m-d') : '') }}" class="datepicker w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch" placeholder="dd/mm/yyyy">
                                                <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-calendar-blank"></i></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Kewarganegaraan</label>
                                        <input type="text" name="guardian_citizenship" value="{{ old('guardian_citizenship', $student->guardian_citizenship) }}" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch" placeholder="WNI">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Hubungan Keluarga</label>
                                        <input type="text" name="guardian_relationship" value="{{ old('guardian_relationship', $student->guardian_relationship) }}" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch" placeholder="Paman / Kakek">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Pekerjaan</label>
                                        <input type="text" name="guardian_job" value="{{ old('guardian_job', $student->guardian_job) }}" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Penghasilan / Bulan</label>
                                        <input type="text" name="guardian_income" value="{{ old('guardian_income', $student->guardian_income) }}" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch">
                                    </div>
                                    {{-- UPDATE: TAMBAHAN NO HP WALI --}}
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">No. HP / Telepon Wali</label>
                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-phone"></i></div>
                                            <input type="text" name="guardian_phone" value="{{ old('guardian_phone', $student->guardian_phone) }}" class="w-full pl-9 rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch font-mono">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-1 ml-1">Alamat Lengkap Wali</label>
                                        <input type="text" name="guardian_address" value="{{ old('guardian_address', $student->guardian_address) }}" class="w-full rounded-xl border-slate-300 focus:border-blue-900 focus:ring-blue-900 input-watch">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- TAB F: MUTASI / TAMAT --}}
                        <div x-show="tab === 'tamat'" class="space-y-8" style="display: none;">
                            
                            {{-- MENINGGALKAN SEKOLAH (LULUS) --}}
                            <div class="bg-emerald-50 p-6 rounded-2xl border border-emerald-100">
                                <h3 class="font-bold text-emerald-800 mb-4">I. Tamat Belajar (Lulus)</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-xs font-bold text-emerald-600 uppercase mb-1 ml-1">Tanggal Tamat</label>
                                        <div class="relative">
                                            <input type="text" name="graduated_date" value="{{ old('graduated_date', $student->graduated_date ? \Carbon\Carbon::parse($student->graduated_date)->format('Y-m-d') : '') }}" class="datepicker w-full rounded-xl border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500 bg-white input-watch" placeholder="dd/mm/yyyy">
                                            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-emerald-400"><i class="ph-bold ph-calendar-blank"></i></div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-emerald-600 uppercase mb-1 ml-1">No. Ijazah</label>
                                        <input type="text" name="graduated_diploma_no" value="{{ old('graduated_diploma_no', $student->graduated_diploma_no) }}" class="w-full rounded-xl border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500 input-watch">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-emerald-600 uppercase mb-1 ml-1">Melanjutkan Ke</label>
                                        <input type="text" name="continuing_to_school" value="{{ old('continuing_to_school', $student->continuing_to_school) }}" placeholder="Nama SMA/SMK" class="w-full rounded-xl border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500 input-watch">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-emerald-600 uppercase mb-1 ml-1">Alamat Sekolah Lanjutan</label>
                                        <input type="text" name="continuing_school_address" value="{{ old('continuing_school_address', $student->continuing_school_address) }}" class="w-full rounded-xl border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500 input-watch">
                                    </div>
                                </div>
                            </div>

                            {{-- PINDAH SEKOLAH --}}
                            <div class="bg-amber-50 p-6 rounded-2xl border border-amber-100">
                                <h3 class="font-bold text-amber-800 mb-4">II. Pindah Sekolah (Mutasi Keluar)</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-xs font-bold text-amber-600 uppercase mb-1 ml-1">Tanggal Pindah</label>
                                        <div class="relative">
                                            <input type="text" name="leaving_date" value="{{ old('leaving_date', $student->leaving_date ? \Carbon\Carbon::parse($student->leaving_date)->format('Y-m-d') : '') }}" class="datepicker w-full rounded-xl border-amber-200 focus:border-amber-500 focus:ring-amber-500 bg-white input-watch" placeholder="dd/mm/yyyy">
                                            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-amber-400"><i class="ph-bold ph-calendar-blank"></i></div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-amber-600 uppercase mb-1 ml-1">Dari Kelas</label>
                                        <input type="text" name="leaving_class" value="{{ old('leaving_class', $student->leaving_class) }}" class="w-full rounded-xl border-amber-200 focus:border-amber-500 focus:ring-amber-500 input-watch">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-amber-600 uppercase mb-1 ml-1">Pindah Ke Sekolah</label>
                                        <input type="text" name="leaving_to_school" value="{{ old('leaving_to_school', $student->leaving_to_school) }}" class="w-full rounded-xl border-amber-200 focus:border-amber-500 focus:ring-amber-500 input-watch">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-amber-600 uppercase mb-1 ml-1">Alasan Pindah</label>
                                        <input type="text" name="leaving_reason" value="{{ old('leaving_reason', $student->leaving_reason) }}" class="w-full rounded-xl border-amber-200 focus:border-amber-500 focus:ring-amber-500 input-watch">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-rose-50 p-6 rounded-2xl border border-rose-100">
                                <h3 class="font-bold text-rose-800 mb-4">Putus Sekolah</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-xs font-bold text-rose-600 uppercase mb-1 ml-1">Tanggal Putus</label>
                                        <div class="relative">
                                            <input type="text" name="dropout_date" value="{{ old('dropout_date', $student->dropout_date ? \Carbon\Carbon::parse($student->dropout_date)->format('Y-m-d') : '') }}" class="datepicker w-full rounded-xl border-rose-200 focus:border-rose-500 focus:ring-rose-500 bg-white input-watch" placeholder="dd/mm/yyyy">
                                            <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-rose-400"><i class="ph-bold ph-calendar-blank"></i></div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-rose-600 uppercase mb-1 ml-1">Alasan</label>
                                        <input type="text" name="dropout_reason" value="{{ old('dropout_reason', $student->dropout_reason) }}" class="w-full rounded-xl border-rose-200 focus:border-rose-500 focus:ring-rose-500 input-watch">
                                    </div>
                                </div>
                            </div>

                            <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200">
                                <h3 class="font-bold text-slate-800 mb-4">Lain-Lain</h3>
                                <div class="space-y-4">
                                    {{-- UPDATE: TAMBAHAN PRESTASI --}}
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1 ml-1">Prestasi Siswa</label>
                                        <textarea name="achievements" rows="2" placeholder="Contoh: Juara 1 Lomba Pidato Tingkat Kabupaten" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:border-blue-900 focus:ring-blue-900 input-watch">{{ old('achievements', $student->achievements) }}</textarea>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1 ml-1">Beasiswa (Tahun / Kelas / Sumber)</label>
                                        <textarea name="scholarship_info" rows="2" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:border-blue-900 focus:ring-blue-900 input-watch">{{ old('scholarship_info', $student->scholarship_info) }}</textarea>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1 ml-1">Catatan Penting Selama Siswa Belajar</label>
                                        <textarea name="general_notes" rows="3" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:border-blue-900 focus:ring-blue-900 input-watch">{{ old('general_notes', $student->general_notes) }}</textarea>
                                    </div>
                                </div>
                            </div>

                        </div>


                        {{-- FOOTER --}}
                        <div class="mt-8 pt-6 border-t border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
                            <p class="text-xs text-slate-400 italic text-center md:text-left">* Pastikan data sudah benar sebelum disimpan.</p>
                            <div class="flex items-center gap-3 w-full md:w-auto">
                                <a href="{{ $backUrl }}" class="btn-back-confirm px-6 py-3 bg-white text-slate-600 border border-slate-300 font-bold rounded-xl hover:bg-slate-50 transition-all text-center flex-1 md:flex-none">
                                    Batal
                                </a>
                                <button type="submit" class="px-8 py-3 bg-blue-900 text-white font-bold rounded-xl hover:bg-blue-800 shadow-lg shadow-blue-900/30 transition-all transform active:scale-95 flex items-center justify-center gap-2 flex-1 md:flex-none">
                                    <i class="ph-bold ph-floppy-disk"></i>
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>

                    </form>
                </div>
        </div>
    </div>

    {{-- Script Flatpickr & SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    {{-- Bahasa Indonesia untuk Flatpickr --}}
    <script src="https://npmcdn.com/flatpickr/dist/l10n/id.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. INISIALISASI DATEPICKER (Flatpickr)
            flatpickr(".datepicker", {
                altInput: true,
                altFormat: "d/m/Y",
                dateFormat: "Y-m-d",
                locale: "id",
                disableMobile: "true"
            });

            // 2. PREVIEW FOTO
            const photoInput = document.getElementById('photo-input');
            if(photoInput) {
                photoInput.addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            document.getElementById('photo-preview').src = e.target.result;
                            formIsDirty = true; // Tandai form berubah jika ganti foto
                        }
                        reader.readAsDataURL(file);
                    }
                });
            }

            // 3. LOGIKA PROTEKSI KEMBALI (DIRTY CHECKING)
            let formIsDirty = false;
            // Pantau semua input yang punya class 'input-watch'
            const watchElements = document.querySelectorAll('.input-watch');
            watchElements.forEach(el => {
                el.addEventListener('change', () => { formIsDirty = true; });
                el.addEventListener('input', () => { formIsDirty = true; });
            });

            const btnBackElements = document.querySelectorAll('.btn-back-confirm');
            btnBackElements.forEach(btnBack => {
                btnBack.addEventListener('click', function(e) {
                    if (formIsDirty) {
                        e.preventDefault();
                        const targetUrl = this.getAttribute('href');
                        
                        Swal.fire({
                            title: 'Belum Disimpan!',
                            text: 'Ada perubahan data yang belum disimpan. Yakin ingin kembali?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#e11d48', // Merah (Rose 600)
                            cancelButtonColor: '#64748b', // Abu-abu (Slate 500)
                            confirmButtonText: 'Ya, Buang Perubahan',
                            cancelButtonText: 'Batal',
                            reverseButtons: true,
                            customClass: {
                                popup: 'rounded-[2rem]'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = targetUrl;
                            }
                        });
                    }
                });
            });

            // 4. LOADING SAAT SIMPAN
            const form = document.getElementById('edit-student-form');
            if(form) {
                form.addEventListener('submit', function() {
                    formIsDirty = false; // Bypass peringatan saat submit murni
                    Swal.fire({
                        title: 'Menyimpan Data...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        },
                        customClass: {
                            popup: 'rounded-[2rem]'
                        }
                    });
                });
            }
        });
    </script>
</x-app-layout>