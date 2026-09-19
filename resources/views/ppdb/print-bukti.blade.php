@extends('layouts.public')

@section('content')

{{-- STYLE KHUSUS CETAK BUKTI PENDAFTARAN --}}
<style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

    @media print {
        body, html { background: white !important; margin: 0 !important; }
        .no-print, nav, footer { display: none !important; }
        .print-area {
            box-shadow: none !important;
            border: 1px solid #ccc !important;
            margin: 0 !important;
            padding: 20px !important;
            border-radius: 0 !important;
        }
        .print-header { display: block !important; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 6px 10px; font-size: 10pt; }
        th { background: #f3f4f6; font-weight: bold; -webkit-print-color-adjust: exact; }
    }

    .print-header { display: none; }
</style>

<div class="min-h-screen bg-elevate-surface font-sans py-10 px-4">

    {{-- Background --}}
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden">
        <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-elevate-primary/10 rounded-full blur-[100px] opacity-50"></div>
    </div>

    <div class="relative z-10 max-w-4xl mx-auto animate-enter">

        {{-- Action Bar (hanya tampil di layar) --}}
        <div class="flex items-center justify-between mb-6 no-print">
            <a href="{{ url()->previous() }}" class="group inline-flex items-center gap-2 text-slate-500 hover:text-elevate-primary transition font-bold text-sm bg-white px-5 py-2.5 rounded-2xl border border-slate-200 hover:shadow-sm">
                <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali
            </a>
            <button onclick="window.print()" class="inline-flex items-center gap-2 px-6 py-2.5 bg-elevate-dark hover:bg-elevate-primary text-white font-bold text-sm rounded-2xl shadow-lg shadow-elevate-dark/20 hover:-translate-y-0.5 transition-all">
                <i class="ph-bold ph-printer text-base"></i> Cetak Bukti
            </button>
        </div>

        {{-- Kop Surat (hanya tampil saat print) --}}
        <div class="print-header text-center border-b-2 border-double border-black pb-4 mb-6">
            <h3 class="font-bold text-sm">PANITIA PENERIMAAN PESERTA DIDIK BARU (PPDB)</h3>
            <h4 class="font-bold text-base uppercase">SMP Negeri 3 Lakbok</h4>
            <p class="text-sm">Jalan Mekarjaya No. 199 Sidaharja, Lakbok, Ciamis 46385</p>
            <h3 class="font-bold text-sm mt-4 underline">BUKTI PENDAFTARAN ONLINE</h3>
        </div>

        {{-- Kartu Bukti Pendaftaran --}}
        <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-100 overflow-hidden print-area">

            {{-- Header Kartu --}}
            <div class="bg-elevate-gradient-main p-6 md:p-8 border-b border-slate-100 no-print">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white/30 backdrop-blur-sm border border-white/50 rounded-2xl flex items-center justify-center text-elevate-dark shadow-sm">
                        <i class="ph-duotone ph-receipt text-3xl"></i>
                    </div>
                    <div>
                        <p class="text-xs text-elevate-dark/70 font-bold uppercase tracking-wider">Bukti Pendaftaran</p>
                        <h2 class="text-2xl font-black text-elevate-dark">PPDB Online {{ $registrant->academic_year }}</h2>
                        <p class="text-sm text-elevate-dark/80 font-medium">SMP Negeri 3 Lakbok</p>
                    </div>
                </div>
            </div>

            <div class="p-6 md:p-10">

                {{-- Nomor Registrasi & Status --}}
                <div class="flex flex-col sm:flex-row gap-4 mb-8 p-5 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="flex-1">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Nomor Pendaftaran</p>
                        <p class="text-2xl font-black font-mono text-elevate-primary">{{ $registrant->registration_number }}</p>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal Daftar</p>
                        <p class="text-lg font-bold text-elevate-dark">{{ $registrant->created_at->translatedFormat('d F Y, H:i') }} WIB</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Status</p>
                        @php $statusColors = ['pending' => 'bg-amber-100 text-amber-700 border-amber-200', 'verified' => 'bg-blue-100 text-blue-700 border-blue-200', 'accepted' => 'bg-emerald-100 text-emerald-700 border-emerald-200', 'rejected' => 'bg-rose-100 text-rose-700 border-rose-200']; @endphp
                        <span class="inline-block px-4 py-1.5 rounded-xl font-bold text-sm border {{ $statusColors[$registrant->status] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ strtoupper($registrant->status) }}
                        </span>
                    </div>
                </div>

                {{-- Data Siswa --}}
                <h3 class="text-sm font-black text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i class="ph-bold ph-user text-elevate-primary"></i> Data Pribadi Calon Siswa
                </h3>
                <div class="overflow-x-auto rounded-2xl border border-slate-200 mb-8">
                    <table class="w-full text-sm">
                        <tbody class="divide-y divide-slate-100">
                            <tr class="hover:bg-slate-50/50">
                                <th class="py-3 px-5 text-left text-xs font-bold text-slate-500 w-40 bg-slate-50">Nama Lengkap</th>
                                <td class="py-3 px-5 font-bold text-elevate-dark">{{ $registrant->full_name }}</td>
                                <th class="py-3 px-5 text-left text-xs font-bold text-slate-500 w-40 bg-slate-50">NISN</th>
                                <td class="py-3 px-5 font-mono font-bold">{{ $registrant->nisn }}</td>
                            </tr>
                            <tr class="hover:bg-slate-50/50">
                                <th class="py-3 px-5 text-left text-xs font-bold text-slate-500 bg-slate-50">NIK</th>
                                <td class="py-3 px-5 font-mono">{{ $registrant->nik ?? '-' }}</td>
                                <th class="py-3 px-5 text-left text-xs font-bold text-slate-500 bg-slate-50">Jenis Kelamin</th>
                                <td class="py-3 px-5">{{ $registrant->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                            </tr>
                            <tr class="hover:bg-slate-50/50">
                                <th class="py-3 px-5 text-left text-xs font-bold text-slate-500 bg-slate-50">Tempat Lahir</th>
                                <td class="py-3 px-5">{{ $registrant->birth_place }}</td>
                                <th class="py-3 px-5 text-left text-xs font-bold text-slate-500 bg-slate-50">Tanggal Lahir</th>
                                <td class="py-3 px-5">{{ $registrant->birth_date->translatedFormat('d F Y') }}</td>
                            </tr>
                            <tr class="hover:bg-slate-50/50">
                                <th class="py-3 px-5 text-left text-xs font-bold text-slate-500 bg-slate-50">Agama</th>
                                <td class="py-3 px-5">{{ $registrant->religion }}</td>
                                <th class="py-3 px-5 text-left text-xs font-bold text-slate-500 bg-slate-50">No. WA Siswa</th>
                                <td class="py-3 px-5">{{ $registrant->student_phone ?? '-' }}</td>
                            </tr>
                            <tr class="hover:bg-slate-50/50">
                                <th class="py-3 px-5 text-left text-xs font-bold text-slate-500 bg-slate-50">Alamat</th>
                                <td class="py-3 px-5" colspan="3">{{ $registrant->address }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Data Sekolah & Jalur --}}
                <h3 class="text-sm font-black text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i class="ph-bold ph-graduation-cap text-emerald-600"></i> Asal Sekolah & Jalur Seleksi
                </h3>
                <div class="overflow-x-auto rounded-2xl border border-slate-200 mb-8">
                    <table class="w-full text-sm">
                        <tbody class="divide-y divide-slate-100">
                            <tr class="hover:bg-slate-50/50">
                                <th class="py-3 px-5 text-left text-xs font-bold text-slate-500 w-40 bg-slate-50">Asal Sekolah</th>
                                <td class="py-3 px-5 font-bold">{{ $registrant->school_origin }}</td>
                                <th class="py-3 px-5 text-left text-xs font-bold text-slate-500 w-40 bg-slate-50">NPSN</th>
                                <td class="py-3 px-5 font-mono">{{ $registrant->npsn_school_origin ?? '-' }}</td>
                            </tr>
                            <tr class="hover:bg-slate-50/50">
                                <th class="py-3 px-5 text-left text-xs font-bold text-slate-500 bg-slate-50">Jalur</th>
                                <td class="py-3 px-5">
                                    <span class="capitalize font-bold">{{ str_replace('_', ' ', $registrant->track) }}</span>
                                </td>
                                <th class="py-3 px-5 text-left text-xs font-bold text-slate-500 bg-slate-50">Rata-rata Nilai</th>
                                <td class="py-3 px-5 font-bold text-emerald-700">{{ $registrant->average_grade }}</td>
                            </tr>
                            @if($registrant->achievement_type)
                            <tr class="hover:bg-slate-50/50">
                                <th class="py-3 px-5 text-left text-xs font-bold text-slate-500 bg-slate-50">Jenis Prestasi</th>
                                <td class="py-3 px-5 capitalize">{{ str_replace('_', ' ', $registrant->achievement_type) }}</td>
                                <th class="py-3 px-5 text-left text-xs font-bold text-slate-500 bg-slate-50">Nama Kejuaraan</th>
                                <td class="py-3 px-5">{{ $registrant->achievement_name ?? '-' }}</td>
                            </tr>
                            <tr class="hover:bg-slate-50/50">
                                <th class="py-3 px-5 text-left text-xs font-bold text-slate-500 bg-slate-50">Tingkat</th>
                                <td class="py-3 px-5">{{ $registrant->achievement_level ?? '-' }}</td>
                                <th class="py-3 px-5 text-left text-xs font-bold text-slate-500 bg-slate-50">Peringkat</th>
                                <td class="py-3 px-5">{{ $registrant->achievement_rank ?? '-' }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- Data Orang Tua --}}
                <h3 class="text-sm font-black text-slate-400 uppercase tracking-wider mb-4 flex items-center gap-2">
                    <i class="ph-bold ph-users-three text-amber-600"></i> Data Orang Tua / Wali
                </h3>
                <div class="overflow-x-auto rounded-2xl border border-slate-200 mb-8">
                    <table class="w-full text-sm">
                        <tbody class="divide-y divide-slate-100">
                            <tr class="hover:bg-slate-50/50">
                                <th class="py-3 px-5 text-left text-xs font-bold text-slate-500 w-40 bg-slate-50">Nama Ayah/Wali</th>
                                <td class="py-3 px-5 font-bold">{{ $registrant->father_name }}</td>
                                <th class="py-3 px-5 text-left text-xs font-bold text-slate-500 w-40 bg-slate-50">Nama Ibu</th>
                                <td class="py-3 px-5 font-bold">{{ $registrant->mother_name }}</td>
                            </tr>
                            <tr class="hover:bg-slate-50/50">
                                <th class="py-3 px-5 text-left text-xs font-bold text-slate-500 bg-slate-50">No. WA Orang Tua</th>
                                <td class="py-3 px-5 font-mono">{{ $registrant->parent_phone }}</td>
                                <th class="py-3 px-5 text-left text-xs font-bold text-slate-500 bg-slate-50">Pekerjaan</th>
                                <td class="py-3 px-5">{{ $registrant->parent_job ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Catatan Admin --}}
                @if($registrant->admin_note)
                <div class="p-5 bg-blue-50 border border-blue-100 rounded-2xl mb-8">
                    <p class="text-xs font-bold text-blue-600 uppercase tracking-wider mb-1"><i class="ph-fill ph-note"></i> Catatan Panitia</p>
                    <p class="text-sm text-blue-800 font-medium">{{ $registrant->admin_note }}</p>
                </div>
                @endif

                {{-- Footer bukti --}}
                <div class="pt-6 border-t border-slate-100 text-center">
                    <p class="text-xs text-slate-400 font-medium">
                        Bukti ini dicetak pada {{ now()->translatedFormat('d F Y, H:i') }} WIB &bull; Simpan sebagai referensi cek status di <strong>{{ url('/ppdb/check') }}</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
