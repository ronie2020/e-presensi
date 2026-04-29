<x-app-layout>
    {{-- CUSTOM STYLES & MICROSOFT FLUENT ELEVATION --}}
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        
        .fluent-card {
            box-shadow: 0 1.6px 3.6px 0 rgba(0, 0, 0, 0.132), 0 0.3px 0.9px 0 rgba(0, 0, 0, 0.108);
            border: 1px solid rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        .fluent-modal {
            box-shadow: 0 25.6px 57.6px 0 rgba(0, 0, 0, 0.22), 0 4.8px 14.4px 0 rgba(0, 0, 0, 0.18);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
    </style>

    <div class="py-8 sm:py-12 font-sans text-elevate-dark bg-elevate-surface min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Navigasi Top --}}
            <div class="animate-enter flex items-center justify-between mb-6">
                <a href="{{ route('admin.ppdb.index') }}" class="group inline-flex items-center gap-3 text-slate-500 font-bold text-sm hover:text-elevate-primary transition-colors">
                    <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center shadow-sm group-hover:border-elevate-accent/30 group-hover:bg-elevate-soft group-hover:text-elevate-primary transition-all">
                        <i class="ph-bold ph-arrow-left"></i>
                    </div>
                    Kembali
                </a>
                
                <div class="flex gap-3">
                    <a href="{{ route('admin.ppdb.print', $registrant->id) }}" target="_blank" class="px-5 py-2.5 bg-white border border-slate-200 text-elevate-dark font-bold rounded-xl text-sm hover:bg-slate-50 transition shadow-sm flex items-center gap-2">
                        <i class="ph-bold ph-printer"></i> Cetak Bukti
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                {{-- KOLOM KIRI: Profile & Control --}}
                <div class="space-y-6 animate-enter delay-100">
                    
                    {{-- PROFILE CARD --}}
                    <div class="bg-white rounded-2xl fluent-card overflow-hidden relative group">
                        {{-- Banner Header --}}
                        <div class="h-28 bg-elevate-gradient-main relative overflow-hidden border-b border-slate-100/50">
                             <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.03]"></div>
                             <div class="absolute -right-10 top-0 w-32 h-32 bg-white/30 rounded-full blur-2xl"></div>
                        </div>
                        <div class="px-6 pb-8 text-center relative">
                            <div class="w-28 h-28 mx-auto rounded-full bg-white p-1.5 shadow-md -mt-14 mb-4 relative z-10 border border-slate-50">
                                <div class="w-full h-full rounded-full bg-slate-50 overflow-hidden flex items-center justify-center border border-slate-200">
                                    @if($registrant->file_photo)
                                        <img src="{{ asset('storage/' . $registrant->file_photo) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="bg-gradient-to-br from-slate-100 to-slate-200 w-full h-full flex items-center justify-center">
                                            <span class="text-4xl font-black text-slate-400">{{ substr($registrant->full_name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <h2 class="text-xl font-black text-elevate-dark leading-tight mb-1">{{ $registrant->full_name }}</h2>
                            <p class="text-xs font-bold text-slate-500 mb-4">{{ $registrant->school_origin }}</p>
                            
                            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-slate-50 border border-slate-200 rounded-md text-xs font-bold text-slate-600 shadow-sm">
                                <i class="ph-bold ph-identification-card"></i> {{ $registrant->registration_number }}
                            </div>
                        </div>
                    </div>

                    {{-- CONTROL PANEL (Status) --}}
                    <div class="bg-white rounded-2xl fluent-card p-6">
                        <h3 class="text-sm font-bold text-elevate-dark mb-5 flex items-center gap-2">
                             <i class="ph-fill ph-sliders-horizontal text-elevate-primary"></i> Panel Kelulusan
                        </h3>
                        
                        <form action="{{ route('admin.ppdb.update_status', $registrant->id) }}" method="POST" class="space-y-4">
                            @csrf @method('PATCH')
                            
                            <div>
                                <label class="text-xs font-bold text-slate-500 mb-2 block">Status Seleksi</label>
                                <select name="status" class="w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 text-sm font-bold focus:ring-elevate-accent/30 focus:border-elevate-accent transition-all cursor-pointer">
                                    <option value="pending" {{ $registrant->status == 'pending' ? 'selected' : '' }}>⏳ Menunggu (Pending)</option>
                                    <option value="verified" {{ $registrant->status == 'verified' ? 'selected' : '' }}>✅ Terverifikasi</option>
                                    <option value="accepted" {{ $registrant->status == 'accepted' ? 'selected' : '' }}>🏆 DITERIMA</option>
                                    <option value="rejected" {{ $registrant->status == 'rejected' ? 'selected' : '' }}>❌ Ditolak</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="text-xs font-bold text-slate-500 mb-2 block">Catatan Panitia</label>
                                <textarea name="admin_note" rows="3" class="w-full px-4 py-3 rounded-xl border-slate-200 bg-white text-sm focus:ring-elevate-accent/30 focus:border-elevate-accent placeholder:text-slate-400 placeholder:font-normal font-bold transition-all" placeholder="Contoh: Lulus jalur prestasi...">{{ $registrant->admin_note }}</textarea>
                            </div>

                            <button type="submit" class="w-full py-3 bg-elevate-dark text-white font-bold rounded-xl text-sm hover:bg-elevate-primary transition shadow-sm flex items-center justify-center gap-2">
                                <i class="ph-bold ph-floppy-disk"></i> Simpan Perubahan
                            </button>
                        </form>

                        @if($registrant->status === 'accepted')
                            <div class="mt-5 pt-5 border-t border-slate-100">
                                @if(!$isPromoted)
                                    <form id="promoteForm" action="{{ route('admin.ppdb.promote', $registrant->id) }}" method="POST">
                                        @csrf
                                        <button type="button" onclick="confirmPromote()" class="w-full py-3 bg-emerald-600 text-white font-bold rounded-xl text-sm hover:bg-emerald-700 transition shadow-sm flex items-center justify-center gap-2 group">
                                            <i class="ph-bold ph-user-plus text-lg group-hover:scale-110 transition-transform"></i> Pindahkan ke Siswa Aktif
                                        </button>
                                    </form>
                                @else
                                    <div class="w-full py-3 bg-emerald-50 text-emerald-700 font-bold rounded-xl text-sm border border-emerald-200 flex items-center justify-center gap-2">
                                        <i class="ph-fill ph-check-circle text-lg"></i> Sudah Dipindahkan
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- INFO JALUR --}}
                    <div class="bg-elevate-soft rounded-2xl p-6 border border-elevate-accent/30 fluent-card">
                        <div class="flex items-start justify-between mb-6">
                            <div>
                                <p class="text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-1">Jalur Masuk</p>
                                <p class="text-2xl font-black text-elevate-dark capitalize">{{ $registrant->track }}</p>
                            </div>
                            <div class="w-12 h-12 rounded-xl bg-white text-elevate-primary border border-elevate-accent/30 flex items-center justify-center shadow-sm">
                                <i class="ph-fill ph-path text-2xl"></i>
                            </div>
                        </div>
                        <div class="bg-white rounded-xl p-4 border border-elevate-accent/30 flex items-center justify-between shadow-sm">
                            <span class="text-xs font-bold text-slate-500 uppercase">Nilai Rapor</span>
                            <span class="text-xl font-black text-elevate-dark">{{ $registrant->average_grade }}</span>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: DATA DETAIL --}}
                <div class="lg:col-span-2 space-y-6 animate-enter delay-200">
                    
                    {{-- BIODATA --}}
                    <div class="bg-white rounded-2xl fluent-card p-6 md:p-8 relative overflow-hidden group">
                        <h3 class="text-lg font-bold text-elevate-dark mb-6 flex items-center gap-3 relative z-10 border-b border-slate-100 pb-4">
                            <div class="w-10 h-10 rounded-xl bg-elevate-soft text-elevate-primary flex items-center justify-center text-xl border border-elevate-accent/30">
                                <i class="ph-duotone ph-identification-badge"></i>
                            </div>
                            Identitas Siswa
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-8 relative z-10">
                            @foreach([
                                'NISN' => $registrant->nisn,
                                'NIK' => $registrant->nik,
                                'Tempat Lahir' => $registrant->birth_place,
                                'Tanggal Lahir' => \Carbon\Carbon::parse($registrant->birth_date)->translatedFormat('d F Y'),
                                'Jenis Kelamin' => $registrant->gender == 'L' ? 'Laki-laki' : 'Perempuan',
                                'Agama' => $registrant->religion,
                                'No. HP' => $registrant->student_phone ?? '-'
                            ] as $label => $val)
                            <div class="border-l-2 border-elevate-accent/30 pl-3">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ $label }}</p>
                                <p class="font-bold text-elevate-dark text-sm">{{ $val }}</p>
                            </div>
                            @endforeach
                            <div class="sm:col-span-2 border-l-2 border-elevate-accent/30 pl-3">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Alamat Lengkap</p>
                                <p class="font-bold text-elevate-dark text-sm leading-relaxed">{{ $registrant->address }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- ORANG TUA --}}
                    <div class="bg-white rounded-2xl fluent-card p-6 md:p-8 relative overflow-hidden group">
                        <h3 class="text-lg font-bold text-elevate-dark mb-6 flex items-center gap-3 relative z-10 border-b border-slate-100 pb-4">
                             <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl border border-amber-200">
                                <i class="ph-duotone ph-users-three"></i>
                            </div>
                            Data Orang Tua / Wali
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-6 gap-x-8 relative z-10">
                            @foreach([
                                'Ayah' => $registrant->father_name,
                                'Ibu' => $registrant->mother_name,
                                'Pekerjaan' => $registrant->parent_job ?? '-',
                                'Penghasilan' => $registrant->parent_income ?? '-',
                                'No. WhatsApp' => $registrant->parent_phone
                            ] as $label => $val)
                            <div class="border-l-2 border-amber-200 pl-3">
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">{{ $label }}</p>
                                <p class="font-bold text-elevate-dark text-sm">{{ $val }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- DOKUMEN --}}
                    <div class="bg-white rounded-2xl fluent-card p-6 md:p-8">
                        <h3 class="text-lg font-bold text-elevate-dark mb-6 flex items-center gap-3 border-b border-slate-100 pb-4">
                             <div class="w-10 h-10 rounded-xl bg-slate-50 text-elevate-dark flex items-center justify-center text-xl border border-slate-200">
                                <i class="ph-duotone ph-files"></i>
                            </div>
                            Berkas Lampiran Dokumen
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @foreach([
                                'file_kk' => 'Kartu Keluarga',
                                'file_akta' => 'Akta Kelahiran',
                                'file_grades' => 'Scan Rapor',
                                'file_kip' => 'Kartu KIP/PKH',
                                'file_achievement' => 'Sertifikat Prestasi'
                            ] as $field => $label)
                                @if($registrant->$field)
                                    <a href="{{ asset('storage/' . $registrant->$field) }}" target="_blank" class="flex items-center gap-4 p-4 rounded-xl border border-slate-200 bg-slate-50 hover:bg-white hover:border-elevate-accent hover:shadow-md transition-all duration-300 group">
                                        <div class="w-10 h-10 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-slate-400 group-hover:bg-elevate-soft group-hover:text-elevate-primary group-hover:border-elevate-accent/30 transition-colors shadow-sm">
                                            <i class="ph-fill ph-file-text text-xl"></i>
                                        </div>
                                        <div>
                                            <p class="font-bold text-elevate-dark group-hover:text-elevate-primary transition-colors text-sm">{{ $label }}</p>
                                            <p class="text-[10px] text-slate-400 font-bold uppercase mt-0.5 flex items-center gap-1 group-hover:text-elevate-primary">
                                                <i class="ph-bold ph-eye"></i> Lihat Berkas
                                            </p>
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
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmPromote() {
            Swal.fire({
                title: 'Pindahkan ke Siswa Aktif?',
                text: "Pastikan data sudah benar. Data akan masuk ke database utama sekolah.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Pindahkan',
                confirmButtonColor: '#10b981', // Tailwind Emerald
                cancelButtonColor: '#2c3f61', // Elevate Dark
                customClass: { popup: 'rounded-[2rem]', confirmButton: 'rounded-xl font-bold', cancelButton: 'rounded-xl font-bold' }
            }).then((res) => { if(res.isConfirmed) document.getElementById('promoteForm').submit(); });
        }
    </script>
</x-app-layout>