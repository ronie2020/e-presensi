<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formulir PPDB Online - SMP Negeri 3 Lakbok</title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .form-input { @apply w-full rounded-xl border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5; }
        .form-label { @apply block text-sm font-bold text-slate-700 mb-1.5; }
        .section-title { @apply text-lg font-bold text-slate-900 border-b border-slate-100 pb-2 mb-4 flex items-center gap-2; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">

    <!-- Navbar Sederhana -->
    <div class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-2 text-slate-700 hover:text-blue-600 transition">
                <i class="ph-bold ph-arrow-left text-xl"></i>
                <span class="font-bold text-sm">Kembali ke Beranda</span>
            </a>
            <span class="font-extrabold text-blue-900 text-lg tracking-tight">PPDB <span class="text-blue-600">ONLINE</span></span>
        </div>
    </div>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <!-- Header Section -->
        <div class="text-center mb-10">
            <h1 class="text-3xl font-extrabold text-slate-900">Formulir Pendaftaran Siswa Baru</h1>
            <p class="text-slate-500 mt-2">Tahun Ajaran {{ date('Y') }}/{{ date('Y')+1 }}</p>
        </div>

        <!-- Alert Error Global -->
        @if ($errors->any())
            <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-3">
                <i class="ph-fill ph-warning-circle text-red-500 text-xl mt-0.5"></i>
                <div>
                    <h3 class="text-sm font-bold text-red-800">Mohon perbaiki kesalahan berikut:</h3>
                    <ul class="list-disc list-inside text-sm text-red-600 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('ppdb.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- 1. JALUR PENDAFTARAN -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
                <h3 class="section-title"><i class="ph-fill ph-path text-blue-600"></i> Jalur Pendaftaran</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label">Pilih Jalur <span class="text-red-500">*</span></label>
                        <select name="track" class="form-input bg-blue-50/50" required>
                            <option value="">-- Pilih Jalur --</option>
                            <option value="zonasi" {{ old('track') == 'zonasi' ? 'selected' : '' }}>Zonasi (Jarak Tempat Tinggal)</option>
                            <option value="prestasi" {{ old('track') == 'prestasi' ? 'selected' : '' }}>Prestasi (Akademik/Non-Akademik)</option>
                            <option value="afirmasi" {{ old('track') == 'afirmasi' ? 'selected' : '' }}>Afirmasi (KIP/KPS/PKH)</option>
                            <option value="pindah_tugas" {{ old('track') == 'pindah_tugas' ? 'selected' : '' }}>Perpindahan Tugas Orang Tua</option>
                        </select>
                        <p class="text-xs text-slate-500 mt-2">Pastikan memilih jalur sesuai dengan dokumen yang dimiliki.</p>
                    </div>
                </div>
            </div>

            <!-- 2. DATA SISWA -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
                <h3 class="section-title"><i class="ph-fill ph-student text-blue-600"></i> Data Calon Siswa</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="form-label">Nama Lengkap <span class="text-red-500">*</span></label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}" class="form-input" placeholder="Sesuai Ijazah SD/MI" required>
                    </div>
                    <div>
                        <label class="form-label">NISN <span class="text-red-500">*</span></label>
                        <input type="number" name="nisn" value="{{ old('nisn') }}" class="form-input" placeholder="10 Digit Angka" required>
                    </div>
                    <div>
                        <label class="form-label">NIK <span class="text-red-500">*</span></label>
                        <input type="number" name="nik" value="{{ old('nik') }}" class="form-input" placeholder="16 Digit Angka (Lihat KK)" required>
                    </div>
                    <div>
                        <label class="form-label">Tempat Lahir <span class="text-red-500">*</span></label>
                        <input type="text" name="birth_place" value="{{ old('birth_place') }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" name="birth_date" value="{{ old('birth_date') }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select name="gender" class="form-input" required>
                            <option value="">-- Pilih --</option>
                            <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Agama</label>
                        <select name="religion" class="form-input">
                            <option value="Islam" selected>Islam</option>
                            <option value="Kristen">Kristen</option>
                            <option value="Katolik">Katolik</option>
                            <option value="Hindu">Hindu</option>
                            <option value="Buddha">Buddha</option>
                            <option value="Konghucu">Konghucu</option>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="form-label">Alamat Lengkap <span class="text-red-500">*</span></label>
                        <textarea name="address" rows="3" class="form-input" placeholder="Nama Jalan, RT/RW, Desa/Kelurahan, Kecamatan" required>{{ old('address') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- 3. DATA SEKOLAH ASAL -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
                <h3 class="section-title"><i class="ph-fill ph-buildings text-blue-600"></i> Sekolah Asal & Nilai</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label">Nama Sekolah Asal (SD/MI) <span class="text-red-500">*</span></label>
                        <input type="text" name="school_origin" value="{{ old('school_origin') }}" class="form-input" placeholder="Contoh: SDN 1 Lakbok" required>
                    </div>
                    <div>
                        <label class="form-label">NPSN Sekolah Asal</label>
                        <input type="number" name="npsn_school_origin" value="{{ old('npsn_school_origin') }}" class="form-input" placeholder="Jika ada">
                    </div>
                    <div>
                        <label class="form-label">Rata-rata Nilai Rapor <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="average_grade" value="{{ old('average_grade') }}" class="form-input" placeholder="Skala 100 (Contoh: 85.50)" required>
                        <p class="text-xs text-slate-500 mt-1">Rata-rata nilai rapor kelas 4, 5, dan 6 (Semester 1).</p>
                    </div>
                </div>
            </div>

            <!-- 4. DATA ORANG TUA -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
                <h3 class="section-title"><i class="ph-fill ph-users-three text-blue-600"></i> Data Orang Tua / Wali</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="form-label">Nama Ayah <span class="text-red-500">*</span></label>
                        <input type="text" name="father_name" value="{{ old('father_name') }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Nama Ibu <span class="text-red-500">*</span></label>
                        <input type="text" name="mother_name" value="{{ old('mother_name') }}" class="form-input" required>
                    </div>
                    <div>
                        <label class="form-label">Nomor WhatsApp Aktif <span class="text-red-500">*</span></label>
                        <input type="number" name="parent_phone" value="{{ old('parent_phone') }}" class="form-input" placeholder="08xxxxxxxxxx" required>
                        <p class="text-xs text-slate-500 mt-1">Digunakan untuk informasi pengumuman.</p>
                    </div>
                    <div>
                        <label class="form-label">Pekerjaan Orang Tua</label>
                        <input type="text" name="parent_job" value="{{ old('parent_job') }}" class="form-input">
                    </div>
                </div>
            </div>

            <!-- 5. UPLOAD BERKAS -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
                <h3 class="section-title"><i class="ph-fill ph-upload-simple text-blue-600"></i> Upload Dokumen</h3>
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-6 text-sm text-blue-800">
                    <p class="font-bold mb-1">Ketentuan File:</p>
                    <ul class="list-disc list-inside">
                        <li>Format yang diperbolehkan: <strong>JPG, PNG, PDF</strong>.</li>
                        <li>Ukuran maksimal per file: <strong>2 MB</strong>.</li>
                        <li>Pastikan dokumen terbaca dengan jelas (tidak buram).</li>
                    </ul>
                </div>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center border-b border-slate-50 pb-4">
                        <div class="md:col-span-1">
                            <label class="form-label mb-0">Pas Foto (3x4) <span class="text-red-500">*</span></label>
                            <p class="text-xs text-slate-500">Latar belakang merah/biru.</p>
                        </div>
                        <div class="md:col-span-2">
                            <input type="file" name="file_photo" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center border-b border-slate-50 pb-4">
                        <div class="md:col-span-1">
                            <label class="form-label mb-0">Kartu Keluarga (KK) <span class="text-red-500">*</span></label>
                        </div>
                        <div class="md:col-span-2">
                            <input type="file" name="file_kk" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center border-b border-slate-50 pb-4">
                        <div class="md:col-span-1">
                            <label class="form-label mb-0">Akta Kelahiran <span class="text-red-500">*</span></label>
                        </div>
                        <div class="md:col-span-2">
                            <input type="file" name="file_akta" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                        <div class="md:col-span-1">
                            <label class="form-label mb-0">Kartu Indonesia Pintar (KIP)</label>
                            <p class="text-xs text-slate-500">Wajib jika memilih jalur Afirmasi.</p>
                        </div>
                        <div class="md:col-span-2">
                            <input type="file" name="file_kip" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition">
                        </div>
                    </div>
                </div>
            </div>

            <!-- BUTTON -->
            <div class="flex items-center justify-end gap-4 pt-4">
                <a href="{{ url('/') }}" class="px-6 py-3 rounded-xl bg-slate-200 text-slate-700 font-bold text-sm hover:bg-slate-300 transition">Batal</a>
                <button type="submit" class="px-8 py-3 rounded-xl bg-blue-600 text-white font-bold text-sm shadow-lg shadow-blue-600/30 hover:bg-blue-700 hover:-translate-y-1 transition-all flex items-center gap-2">
                    <i class="ph-bold ph-paper-plane-right"></i> Kirim Pendaftaran
                </button>
            </div>
        </form>

        <div class="mt-12 text-center text-xs text-slate-400">
            &copy; {{ date('Y') }} SMP Negeri 3 Lakbok. PPDB Online System.
        </div>
    </div>
</body>
</html>