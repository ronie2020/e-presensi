<x-app-layout>
    <div class="py-8 sm:py-10 font-sans text-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER: Navigasi & Judul --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-black text-slate-800 tracking-tight flex items-center gap-3">
                        <i class="ph-duotone ph-student text-blue-600"></i> Detail Pendaftar
                    </h1>
                    <p class="text-slate-500 font-medium text-sm mt-1">
                        No. Reg: <span class="font-mono font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded">{{ $registrant->registration_number }}</span>
                    </p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('admin.ppdb.index') }}" class="px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-50 transition shadow-sm flex items-center gap-2">
                        <i class="ph-bold ph-arrow-left"></i> Kembali
                    </a>
                    
                    {{-- TOMBOL PRINT BUKTI --}}
                    <a href="{{ route('admin.ppdb.print', $registrant->id) }}" target="_blank" class="px-4 py-2.5 bg-slate-800 text-white rounded-xl text-sm font-bold hover:bg-slate-700 transition shadow-lg shadow-slate-900/20 flex items-center gap-2">
                        <i class="ph-bold ph-printer"></i> Cetak Bukti
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- KOLOM KIRI: STATUS & FOTO --}}
                <div class="space-y-8">
                    
                    {{-- CARD STATUS & ACTION --}}
                    <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden p-6">
                        <div class="text-center mb-6">
                            <div class="w-32 h-32 mx-auto rounded-full bg-slate-100 border-4 border-white shadow-lg overflow-hidden relative mb-4">
                                @if($registrant->file_photo)
                                    <img src="{{ asset('storage/' . $registrant->file_photo) }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-400">
                                        <i class="ph-duotone ph-user text-5xl"></i>
                                    </div>
                                @endif
                            </div>
                            <h2 class="text-xl font-bold text-slate-800">{{ $registrant->full_name }}</h2>
                            <p class="text-sm text-slate-500 font-medium">{{ $registrant->school_origin }}</p>
                        </div>

                        <form action="{{ route('admin.ppdb.update_status', $registrant->id) }}" method="POST" class="bg-slate-50 rounded-2xl p-5 border border-slate-200">
                            @csrf
                            @method('PATCH')
                            
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Update Status Seleksi</label>
                            <div class="flex gap-2 mb-4">
                                <select name="status" class="w-full rounded-xl border-slate-300 text-sm font-bold focus:ring-blue-500 focus:border-blue-500">
                                    <option value="pending" {{ $registrant->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="verified" {{ $registrant->status == 'verified' ? 'selected' : '' }}>Terverifikasi</option>
                                    <option value="accepted" {{ $registrant->status == 'accepted' ? 'selected' : '' }}>DITERIMA</option>
                                    <option value="rejected" {{ $registrant->status == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                                </select>
                            </div>
                            
                            <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Catatan Admin (Opsional)</label>
                            <textarea name="admin_note" rows="2" class="w-full rounded-xl border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500 mb-4" placeholder="Misal: Lulus jalur prestasi...">{{ $registrant->admin_note }}</textarea>

                            <button type="submit" class="w-full py-2.5 bg-blue-600 text-white font-bold rounded-xl text-sm hover:bg-blue-700 transition shadow-lg shadow-blue-500/20">
                                Simpan Status
                            </button>
                        </form>

                        {{-- === FITUR PROMOTE TO STUDENT === --}}
                        @if($registrant->status === 'accepted')
                            <div class="mt-6 pt-6 border-t border-slate-100">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wider mb-3 text-center">Tindak Lanjut</label>
                                
                                @if(!$isPromoted)
                                    <form action="{{ route('admin.ppdb.promote', $registrant->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin? Data siswa dan foto akan disalin ke Data Induk Siswa.');">
                                        @csrf
                                        <button type="submit" class="w-full py-3 bg-emerald-600 text-white font-bold rounded-xl text-sm hover:bg-emerald-700 transition shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2 group">
                                            <i class="ph-bold ph-user-plus text-lg group-hover:scale-110 transition-transform"></i>
                                            Pindahkan ke Data Siswa
                                        </button>
                                        <p class="text-[10px] text-slate-400 text-center mt-2 leading-tight">
                                            Klik tombol ini untuk memasukkan siswa ke database utama secara otomatis.
                                        </p>
                                    </form>
                                @else
                                    <div class="w-full py-3 bg-slate-100 text-slate-400 font-bold rounded-xl text-sm border border-slate-200 flex items-center justify-center gap-2 cursor-not-allowed">
                                        <i class="ph-fill ph-check-circle text-lg"></i>
                                        Sudah Masuk Data Siswa
                                    </div>
                                @endif
                            </div>
                        @endif

                    </div>

                    {{-- CARD INFO JALUR --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6">
                        <h3 class="text-sm font-extrabold text-slate-800 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Informasi Jalur</h3>
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs text-slate-500">Jalur Pendaftaran</p>
                                <p class="font-bold text-slate-800 capitalize flex items-center gap-2">
                                    @if($registrant->track == 'prestasi') <i class="ph-fill ph-trophy text-yellow-500"></i> @endif
                                    @if($registrant->track == 'zonasi') <i class="ph-fill ph-map-pin text-red-500"></i> @endif
                                    @if($registrant->track == 'afirmasi') <i class="ph-fill ph-hand-heart text-purple-500"></i> @endif
                                    {{ $registrant->track }}
                                </p>
                            </div>
                            
                            @if($registrant->track == 'prestasi')
                            <div class="bg-yellow-50 p-3 rounded-xl border border-yellow-100">
                                <p class="text-xs text-yellow-700 font-bold mb-1">Detail Prestasi:</p>
                                <p class="text-sm font-bold text-slate-800">{{ $registrant->achievement_name ?? '-' }}</p>
                                <p class="text-xs text-slate-600">{{ $registrant->achievement_level }} - {{ $registrant->achievement_rank }}</p>
                            </div>
                            @endif

                            <div>
                                <p class="text-xs text-slate-500">Nilai Rapor</p>
                                <p class="text-2xl font-black text-blue-600">{{ $registrant->average_grade }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: DETAIL DATA --}}
                <div class="lg:col-span-2 space-y-8">
                    
                    {{-- BIODATA --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 sm:p-8">
                        <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                            <i class="ph-duotone ph-identification-card text-blue-500 text-2xl"></i> Biodata Siswa
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-8">
                            <div>
                                <p class="text-xs text-slate-400 font-bold uppercase">NISN</p>
                                <p class="font-bold text-slate-700">{{ $registrant->nisn }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 font-bold uppercase">NIK</p>
                                <p class="font-bold text-slate-700">{{ $registrant->nik }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 font-bold uppercase">Tempat, Tanggal Lahir</p>
                                <p class="font-bold text-slate-700">
                                    {{ $registrant->birth_place }}, {{ \Carbon\Carbon::parse($registrant->birth_date)->translatedFormat('d F Y') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 font-bold uppercase">Jenis Kelamin</p>
                                <p class="font-bold text-slate-700">{{ $registrant->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 font-bold uppercase">Agama</p>
                                <p class="font-bold text-slate-700">{{ $registrant->religion }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 font-bold uppercase">No. HP Siswa</p>
                                <p class="font-bold text-slate-700">{{ $registrant->student_phone ?? '-' }}</p>
                            </div>
                            <div class="sm:col-span-2">
                                <p class="text-xs text-slate-400 font-bold uppercase">Alamat Lengkap</p>
                                <p class="font-bold text-slate-700">{{ $registrant->address }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- ORANG TUA --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 sm:p-8">
                        <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                            <i class="ph-duotone ph-users-three text-purple-500 text-2xl"></i> Data Orang Tua
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-8">
                            <div>
                                <p class="text-xs text-slate-400 font-bold uppercase">Nama Ayah</p>
                                <p class="font-bold text-slate-700">{{ $registrant->father_name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 font-bold uppercase">Nama Ibu</p>
                                <p class="font-bold text-slate-700">{{ $registrant->mother_name }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 font-bold uppercase">Pekerjaan</p>
                                <p class="font-bold text-slate-700">{{ $registrant->parent_job ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 font-bold uppercase">Penghasilan</p>
                                <p class="font-bold text-slate-700">{{ $registrant->parent_income ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 font-bold uppercase">No. HP Orang Tua</p>
                                <p class="font-bold text-slate-700 flex items-center gap-2">
                                    {{ $registrant->parent_phone }}
                                    <a href="https://wa.me/{{ preg_replace('/^0/', '62', $registrant->parent_phone) }}" target="_blank" class="text-green-500 hover:text-green-600">
                                        <i class="ph-fill ph-whatsapp-logo text-lg"></i>
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- DOKUMEN --}}
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-200 p-6 sm:p-8">
                        <h3 class="text-lg font-bold text-slate-800 mb-6 flex items-center gap-2">
                            <i class="ph-duotone ph-files text-orange-500 text-2xl"></i> Berkas Dokumen
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach([
                                'file_kk' => 'Kartu Keluarga',
                                'file_akta' => 'Akta Kelahiran',
                                'file_grades' => 'Scan Rapor',
                                'file_kip' => 'KIP/PKH',
                                'file_achievement' => 'Sertifikat Prestasi'
                            ] as $field => $label)
                                @if($registrant->$field)
                                    <a href="{{ asset('storage/' . $registrant->$field) }}" target="_blank" class="group p-4 rounded-xl border border-slate-200 bg-slate-50 hover:bg-white hover:border-blue-300 hover:shadow-md transition flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400 group-hover:text-blue-500 group-hover:border-blue-200 transition">
                                            <i class="ph-fill ph-file-text text-xl"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="font-bold text-sm text-slate-700 group-hover:text-blue-700 truncate">{{ $label }}</p>
                                            <p class="text-[10px] text-slate-400 uppercase font-bold">Klik Lihat</p>
                                        </div>
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>