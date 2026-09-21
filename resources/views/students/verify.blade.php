@extends('layouts.public')

@section('content')
    {{-- Memastikan CDN yang dibutuhkan tersedia --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-12 bg-[#020b18] text-slate-100 min-h-screen font-sans relative overflow-hidden">
        {{-- Background Radiant Blur --}}
        <div class="bg-gradient-to-b from-[#0d52a1]/20 via-[#031d3d]/10 to-transparent opacity-30 pointer-events-none -z-10 blur-3xl absolute inset-0"></div>

        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- HEADER --}}
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-[#0d52a1]/20 border border-sky-400/30 text-sky-400 mb-4 shadow-lg backdrop-blur-md">
                    <i class="ph-duotone ph-shield-check text-4xl"></i>
                </div>
                <h1 class="text-3xl font-black text-white tracking-tight">Verifikasi Data Induk</h1>
                <p class="text-slate-400 text-sm mt-2 max-w-lg mx-auto font-medium">
                    Untuk keperluan sinkronisasi Dapodik, mohon pastikan Nomor Induk Kependudukan (NIK) dan NISN Anda sudah benar sesuai dokumen resmi.
                </p>
            </div>

            {{-- ALERT INFO --}}
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 rounded-2xl flex items-start gap-3 shadow-xl backdrop-blur-md">
                    <i class="ph-fill ph-check-circle text-emerald-400 text-xl shrink-0"></i>
                    <div>
                        <h4 class="font-bold text-emerald-300">Verifikasi Berhasil!</h4>
                        <p class="text-sm text-emerald-200/90 mt-0.5">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/30 text-rose-200 rounded-2xl flex items-start gap-3 shadow-xl backdrop-blur-md">
                    <i class="ph-fill ph-warning-circle text-rose-400 text-xl shrink-0 mt-0.5"></i>
                    <div>
                        <h4 class="font-bold text-rose-300">Gagal Memverifikasi Data:</h4>
                        <ul class="list-disc list-inside text-sm text-rose-200/90 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- KOTAK FORM --}}
            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2rem] shadow-2xl border border-white/10 overflow-hidden relative backdrop-blur-xl">
                
                {{-- Aksen Garis Atas --}}
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-[#0d52a1] via-[#56bbf1] to-[#0d52a1]"></div>

                <div class="p-8 sm:p-10">
                    
                    {{-- Identitas Read-Only --}}
                    <div class="flex items-center gap-4 p-4 bg-slate-900/60 rounded-2xl border border-white/10 mb-8">
                        <div class="w-14 h-14 rounded-full bg-[#0d52a1] text-white flex items-center justify-center font-bold text-xl shrink-0 border border-sky-400/30">
                            {{ substr(auth()->user()->name ?? 'S', 0, 1) }}
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-0.5">Login Sebagai:</p>
                            <h3 class="font-bold text-white text-lg leading-tight">{{ auth()->user()->name ?? $student->name }}</h3>
                            <p class="text-sm text-sky-400 font-medium">{{ $student->schoolClass->name ?? 'Kelas Tidak Ditemukan' }}</p>
                        </div>
                    </div>

                    {{-- CEK STATUS VALIDASI --}}
                    @php
                        $isValidated = $student->is_validated ?? false; 
                    @endphp

                    @if($isValidated)
                        {{-- TAMPILAN JIKA SUDAH VALID --}}
                        <div class="text-center py-6">
                            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-emerald-500/10 text-emerald-400 mb-4 ring-4 ring-emerald-500/20 border border-emerald-500/30">
                                <i class="ph-fill ph-seal-check text-5xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white">Data Telah Terverifikasi</h3>
                            <p class="text-sm text-slate-400 mt-2 max-w-md mx-auto">Terima kasih, data NIK dan NISN Anda telah dikunci ke dalam sistem. Jika terdapat kesalahan, silakan hubungi Operator Sekolah/Wali Kelas.</p>
                            
                            <div class="mt-8 grid grid-cols-2 gap-4 text-left max-w-sm mx-auto bg-slate-900/80 p-4 rounded-2xl border border-white/10">
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase mb-1">NISN</p>
                                    <p class="font-mono font-bold text-sky-300">{{ $student->student_id }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-400 uppercase mb-1">NIK</p>
                                    <p class="font-mono font-bold text-sky-300">{{ $student->nik }}</p>
                                </div>
                            </div>
                        </div>
                    @else
                        {{-- FORM INPUT JIKA BELUM VALID --}}
                        <form id="verify-form" action="{{ route('students.verify.process') }}" method="POST" class="space-y-6">
                            @csrf
                            
                            <div class="bg-amber-500/10 border border-amber-500/30 rounded-xl p-4 flex gap-3">
                                <i class="ph-fill ph-warning-circle text-amber-400 text-xl shrink-0"></i>
                                <p class="text-xs text-amber-200/90 font-medium">
                                    Pastikan data yang dimasukkan <strong>sama persis</strong> dengan dokumen Kartu Keluarga (KK) dan Ijazah terakhir. Data yang tersimpan tidak dapat diubah sendiri nantinya.
                                </p>
                            </div>

                            <div class="space-y-5 mt-6">
                                {{-- INPUT NISN --}}
                                <div>
                                    <div class="flex justify-between items-end mb-2">
                                        <label for="nisn" class="block text-sm font-bold text-slate-200">Nomor Induk Siswa Nasional (NISN) <span class="text-rose-400">*</span></label>
                                        <a href="https://nisn.data.kemdikbud.go.id" target="_blank" class="text-[10px] font-bold text-sky-300 hover:text-white flex items-center gap-1 bg-sky-500/10 border border-sky-400/30 px-2.5 py-1 rounded-lg transition-all">
                                            Cek NISN Online <i class="ph-bold ph-arrow-up-right"></i>
                                        </a>
                                    </div>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                            <i class="ph-bold ph-identification-card text-lg"></i>
                                        </div>
                                        <input type="text" name="nisn" id="nisn" value="{{ old('nisn', $student->student_id) }}" 
                                            required inputmode="numeric" pattern="[0-9]*" maxlength="10" minlength="10"
                                            class="w-full pl-11 rounded-2xl border-white/10 bg-slate-900/80 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 text-lg py-3 font-mono font-bold text-white transition-all placeholder:font-normal placeholder:text-slate-500 [color-scheme:dark]" 
                                            placeholder="Masukkan 10 digit NISN">
                                    </div>
                                    <p class="text-xs text-slate-400 mt-1.5 ml-1">Terdiri dari tepat 10 digit angka.</p>
                                </div>

                                {{-- INPUT NIK --}}
                                <div>
                                    <label for="nik" class="block text-sm font-bold text-slate-200 mb-2">Nomor Induk Kependudukan (NIK) <span class="text-rose-400">*</span></label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                            <i class="ph-bold ph-credit-card text-lg"></i>
                                        </div>
                                        <input type="text" name="nik" id="nik" value="{{ old('nik', $student->nik) }}" 
                                            required inputmode="numeric" pattern="[0-9]*" maxlength="16" minlength="16"
                                            class="w-full pl-11 rounded-2xl border-white/10 bg-slate-900/80 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 text-lg py-3 font-mono font-bold text-white transition-all placeholder:font-normal placeholder:text-slate-500 [color-scheme:dark]" 
                                            placeholder="Masukkan 16 digit NIK">
                                    </div>
                                    <p class="text-xs text-slate-400 mt-1.5 ml-1">Lihat pada Kartu Keluarga (KK). Terdiri dari 16 digit angka.</p>
                                </div>
                            </div>

                            <div class="border-t border-white/10 pt-6 mt-8">
                                <button type="button" onclick="confirmVerification()" class="w-full py-4 bg-gradient-to-r from-[#0d52a1] via-[#0d52a1] to-sky-600 hover:from-sky-600 hover:to-[#0d52a1] text-white font-bold rounded-2xl shadow-xl shadow-sky-950/40 transition-all transform active:scale-95 flex items-center justify-center gap-2 text-lg border border-sky-400/30">
                                    <i class="ph-bold ph-check-circle"></i>
                                    Validasi & Simpan Data
                                </button>
                            </div>
                        </form>
                    @endif

                </div>
            </div>
            
            {{-- Footer Bantuan --}}
            <div class="text-center mt-8">
                <p class="text-xs font-medium text-slate-400">
                    Mengalami kendala? Silakan lapor ke Tata Usaha atau Wali Kelas Anda.
                </p>
            </div>

        </div>
    </div>

    {{-- Script untuk Alert dan Validasi Angka --}}
    <script>
        // Mencegah input selain angka
        document.querySelectorAll('input[inputmode="numeric"]').forEach(input => {
            input.addEventListener('input', function (e) {
                this.value = this.value.replace(/[^0-9]/g, '');
            });
        });

        // Konfirmasi sebelum submit
        function confirmVerification() {
            const form = document.getElementById('verify-form');
            const nisn = document.getElementById('nisn').value;
            const nik = document.getElementById('nik').value;

            // Validasi client-side sederhana
            if (nisn.length !== 10) {
                Swal.fire({
                    icon: 'error',
                    title: 'NISN Tidak Valid',
                    text: 'NISN harus berisi tepat 10 digit angka.',
                    background: '#021124',
                    color: '#fff',
                    customClass: { popup: 'rounded-[2rem] font-sans border border-white/10 bg-[#021124] text-white shadow-2xl' }
                });
                return;
            }
            if (nik.length !== 16) {
                Swal.fire({
                    icon: 'error',
                    title: 'NIK Tidak Valid',
                    text: 'NIK harus berisi tepat 16 digit angka.',
                    background: '#021124',
                    color: '#fff',
                    customClass: { popup: 'rounded-[2rem] font-sans border border-white/10 bg-[#021124] text-white shadow-2xl' }
                });
                return;
            }

            Swal.fire({
                title: 'Apakah Data Sudah Benar?',
                html: `
                    <div class="text-left mt-4 text-sm text-slate-300">
                        <p class="mb-2"><strong>NISN:</strong> <span class="font-mono text-sky-400">${nisn}</span></p>
                        <p><strong>NIK:</strong> <span class="font-mono text-sky-400">${nik}</span></p>
                    </div>
                    <p class="text-xs text-rose-400 mt-4 font-bold">Data yang dikirim akan dikunci permanen!</p>
                `,
                icon: 'question',
                showCancelButton: true,
                background: '#021124',
                color: '#fff',
                confirmButtonColor: '#0d52a1',
                cancelButtonColor: '#475569',
                confirmButtonText: 'Ya, Data Sudah Benar',
                cancelButtonText: 'Cek Kembali',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2rem] font-sans border border-white/10 bg-[#021124] text-white shadow-2xl'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        allowOutsideClick: false,
                        background: '#021124',
                        color: '#fff',
                        didOpen: () => { Swal.showLoading(); },
                        customClass: { popup: 'rounded-[2rem] font-sans border border-white/10 bg-[#021124] text-white shadow-2xl' }
                    });
                    form.submit();
                }
            });
        }
    </script>
@endsection