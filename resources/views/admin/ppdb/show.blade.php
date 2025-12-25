<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.ppdb.index') }}" class="p-2 rounded-full hover:bg-slate-200 transition"><i class="ph-bold ph-arrow-left text-xl"></i></a>
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Detail Pendaftar') }} : {{ $registrant->full_name }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Panel Kiri: Identitas & Status -->
                <div class="lg:col-span-1 space-y-6">
                    
                    <!-- Card Status -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6">
                        <div class="text-center mb-6">
                            @if($registrant->file_photo)
                                <img src="{{ asset('storage/' . $registrant->file_photo) }}" class="w-32 h-40 object-cover rounded-lg mx-auto shadow-md border-2 border-white ring-1 ring-slate-200">
                            @else
                                <div class="w-32 h-40 bg-slate-100 rounded-lg mx-auto flex items-center justify-center text-slate-400">
                                    <i class="ph-fill ph-user text-4xl"></i>
                                </div>
                            @endif
                            
                            <h3 class="mt-4 font-bold text-lg text-slate-900">{{ $registrant->full_name }}</h3>
                            <p class="text-sm text-slate-500 font-mono">{{ $registrant->registration_number }}</p>
                        </div>

                        <div class="space-y-3 pt-6 border-t border-slate-100">
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">Status Saat Ini</span>
                                <span class="font-bold uppercase 
                                    {{ $registrant->status == 'accepted' ? 'text-emerald-600' : 
                                      ($registrant->status == 'rejected' ? 'text-red-600' : 'text-yellow-600') }}">
                                    {{ $registrant->status }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">Jalur</span>
                                <span class="font-bold capitalize">{{ $registrant->track }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-slate-500">Nilai Rata-rata</span>
                                <span class="font-bold">{{ $registrant->average_grade }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card Aksi Admin -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6">
                        <h4 class="font-bold text-slate-800 mb-4 flex items-center gap-2"><i class="ph-fill ph-gavel"></i> Keputusan Panitia</h4>
                        
                        <form action="{{ route('admin.ppdb.update_status', $registrant->id) }}" method="POST" class="space-y-3">
                            @csrf
                            @method('PATCH')
                            
                            <button type="submit" name="status" value="verified" class="w-full py-2 px-4 rounded-lg border border-indigo-200 bg-indigo-50 text-indigo-700 font-bold text-sm hover:bg-indigo-100 transition flex justify-center gap-2">
                                <i class="ph-bold ph-check-circle"></i> Verifikasi Berkas
                            </button>
                            
                            <div class="flex gap-2">
                                <button type="submit" name="status" value="accepted" class="flex-1 py-2 px-4 rounded-lg bg-emerald-600 text-white font-bold text-sm hover:bg-emerald-700 transition shadow-lg shadow-emerald-500/30">
                                    Terima Siswa
                                </button>
                                <button type="submit" name="status" value="rejected" class="flex-1 py-2 px-4 rounded-lg bg-red-600 text-white font-bold text-sm hover:bg-red-700 transition shadow-lg shadow-red-500/30">
                                    Tolak
                                </button>
                            </div>
                            
                            <div class="pt-2">
                                <label class="text-xs font-bold text-slate-500">Catatan (Opsional)</label>
                                <textarea name="admin_note" rows="2" class="w-full text-sm rounded-lg border-slate-300 focus:ring-blue-500 focus:border-blue-500 mt-1" placeholder="Alasan diterima/ditolak...">{{ $registrant->admin_note }}</textarea>
                            </div>
                        </form>
                    </div>

                     <!-- Delete Zone -->
                     <div class="bg-red-50 rounded-xl p-4 border border-red-100 text-center">
                        <form action="{{ route('admin.ppdb.destroy', $registrant->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini secara permanen?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs font-bold text-red-600 hover:text-red-800 flex items-center justify-center gap-1 mx-auto">
                                <i class="ph-bold ph-trash"></i> Hapus Permanen
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Panel Kanan: Data Detail -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Data Diri -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6">
                        <h4 class="font-bold text-lg text-slate-800 border-b border-slate-100 pb-2 mb-4">Data Pribadi</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8 text-sm">
                            <div>
                                <p class="text-slate-500 text-xs uppercase tracking-wider font-bold">NISN</p>
                                <p class="font-medium text-slate-900">{{ $registrant->nisn }}</p>
                            </div>
                            <div>
                                <p class="text-slate-500 text-xs uppercase tracking-wider font-bold">NIK</p>
                                <p class="font-medium text-slate-900">{{ $registrant->nik ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-slate-500 text-xs uppercase tracking-wider font-bold">Tempat, Tanggal Lahir</p>
                                <p class="font-medium text-slate-900">{{ $registrant->birth_place }}, {{ $registrant->birth_date->format('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-slate-500 text-xs uppercase tracking-wider font-bold">Jenis Kelamin</p>
                                <p class="font-medium text-slate-900">{{ $registrant->gender == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                            </div>
                            <div class="md:col-span-2">
                                <p class="text-slate-500 text-xs uppercase tracking-wider font-bold">Alamat</p>
                                <p class="font-medium text-slate-900">{{ $registrant->address }}</p>
                            </div>
                             <div>
                                <p class="text-slate-500 text-xs uppercase tracking-wider font-bold">Sekolah Asal</p>
                                <p class="font-medium text-slate-900">{{ $registrant->school_origin }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Data Orang Tua -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6">
                        <h4 class="font-bold text-lg text-slate-800 border-b border-slate-100 pb-2 mb-4">Data Orang Tua</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8 text-sm">
                            <div>
                                <p class="text-slate-500 text-xs uppercase tracking-wider font-bold">Nama Ayah</p>
                                <p class="font-medium text-slate-900">{{ $registrant->father_name }}</p>
                            </div>
                            <div>
                                <p class="text-slate-500 text-xs uppercase tracking-wider font-bold">Nama Ibu</p>
                                <p class="font-medium text-slate-900">{{ $registrant->mother_name }}</p>
                            </div>
                             <div>
                                <p class="text-slate-500 text-xs uppercase tracking-wider font-bold">Kontak HP/WA</p>
                                <a href="https://wa.me/{{ $registrant->parent_phone }}" target="_blank" class="font-bold text-green-600 hover:underline flex items-center gap-1">
                                    <i class="ph-bold ph-whatsapp-logo"></i> {{ $registrant->parent_phone }}
                                </a>
                            </div>
                            <div>
                                <p class="text-slate-500 text-xs uppercase tracking-wider font-bold">Pekerjaan</p>
                                <p class="font-medium text-slate-900">{{ $registrant->parent_job ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Berkas Dokumen -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6">
                        <h4 class="font-bold text-lg text-slate-800 border-b border-slate-100 pb-2 mb-4">Berkas Dokumen</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach(['file_kk' => 'Kartu Keluarga', 'file_akta' => 'Akta Kelahiran', 'file_grades' => 'Rapor/Nilai', 'file_kip' => 'Kartu KIP/KPS'] as $field => $label)
                                @if($registrant->$field)
                                    <div class="border border-slate-200 rounded-lg p-3 flex items-center gap-3 hover:bg-slate-50 transition">
                                        <div class="p-2 bg-red-100 text-red-600 rounded-lg">
                                            <i class="ph-fill ph-file-pdf text-xl"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-bold text-slate-500 uppercase">{{ $label }}</p>
                                            <a href="{{ asset('storage/' . $registrant->$field) }}" target="_blank" class="text-sm font-bold text-blue-600 hover:underline truncate block">
                                                Lihat File
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>