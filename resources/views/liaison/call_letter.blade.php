<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Panggilan Orang Tua - {{ $student->name }}</title>

    {{-- Tailwind CSS via Vite & Phosphor Icons --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        @page {
            size: A4 portrait;
            margin: 20mm 20mm 15mm 20mm;
        }

        body {
            background-color: #f1f5f9;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .sheet {
            background: white;
            width: 210mm;
            min-height: 297mm;
            margin: 0 auto;
            padding: 20mm 25mm;
            box-sizing: border-box;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
            position: relative;
            font-family: "Times New Roman", Times, serif;
            color: #000;
            line-height: 1.6;
        }

        .kop-logo {
            width: 80px;
            height: auto;
            object-fit: contain;
        }

        .double-line {
            border-top: 3px solid #000;
            border-bottom: 1px solid #000;
            height: 4px;
            margin: 12px 0 24px 0;
        }

        @media print {
            body { background: none; margin: 0; padding: 0; }
            .no-print { display: none !important; }
            .sheet {
                width: 100% !important;
                box-shadow: none !important;
                padding: 0 !important;
                margin: 0 !important;
            }
        }
    </style>
</head>
<body class="text-black">

    <!-- TOOLBAR ATAS -->
    <div class="no-print fixed top-0 left-0 right-0 bg-white/95 backdrop-blur-md border-b border-slate-200 shadow-sm p-4 flex justify-between items-center z-50">
        <div class="flex items-center gap-3">
            <div class="bg-blue-600 text-white p-2.5 rounded-xl shadow">
                <i class="ph-bold ph-envelope-open text-xl"></i>
            </div>
            <div>
                <h1 class="font-bold text-slate-800 text-sm">Surat Panggilan Orang Tua / Wali</h1>
                <p class="text-xs text-slate-500">{{ $student->name }} - Kelas {{ $class->name ?? '-' }}</p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button onclick="window.close()" class="px-4 py-2 text-xs font-bold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition">
                <i class="ph-bold ph-x mr-1"></i> Tutup
            </button>
            <button onclick="window.print()" class="px-5 py-2 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 rounded-xl transition shadow flex items-center gap-1.5">
                <i class="ph-bold ph-printer text-base"></i> Cetak / Simpan PDF
            </button>
        </div>
    </div>

    <!-- Spacer Toolbar -->
    <div class="no-print h-24"></div>

    <!-- AREA KERTAS SURAT RESMI -->
    <div class="flex justify-center w-full pb-16">
        <div class="sheet text-sm">
            
            <!-- KOP SURAT RESMI DINAS PENDIDIKAN CIAMIS -->
            <div class="flex items-center justify-between pb-2">
                <div class="w-20 text-center">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/9/9c/Logo_Tut_Wuri_Handayani.png" alt="Logo" class="kop-logo mx-auto">
                </div>
                <div class="flex-1 text-center px-4">
                    <h3 class="text-base font-bold uppercase tracking-wide">Pemerintah Daerah Kabupaten Ciamis</h3>
                    <h2 class="text-lg font-black uppercase tracking-wider">Dinas Pendidikan</h2>
                    <h1 class="text-xl font-black uppercase tracking-widest">SMP NEGERI 3 LAKBOK</h1>
                    <p class="text-xs font-normal mt-0.5">Jalan Raya Cintaratu No. 01 Lakbok, Kabupaten Ciamis, Jawa Barat 46385</p>
                    <p class="text-xs font-normal">Laman: smpn3lakbok.sch.id | Pos-el: smpn3lakbok@gmail.com</p>
                </div>
                <div class="w-20"></div>
            </div>

            <!-- GARIS GANDA KOP -->
            <div class="double-line"></div>

            <!-- NOMOR & TANGGAL SURAT -->
            <div class="flex justify-between items-start mb-6">
                <table>
                    <tr>
                        <td class="w-20">Nomor</td>
                        <td class="w-4">:</td>
                        <td>421.3 / {{ str_pad($liaison->id, 3, '0', STR_PAD_LEFT) }} / SMP.03 / {{ date('Y') }}</td>
                    </tr>
                    <tr>
                        <td>Lampiran</td>
                        <td>:</td>
                        <td>-</td>
                    </tr>
                    <tr>
                        <td>Perihal</td>
                        <td>:</td>
                        <td><strong><u>Undangan Panggilan Orang Tua / Wali</u></strong></td>
                    </tr>
                </table>
                <div class="text-right">
                    <p>Lakbok, {{ \Carbon\Carbon::parse($liaison->created_at)->translatedFormat('d F Y') }}</p>
                </div>
            </div>

            <!-- TUJUAN SURAT -->
            <div class="mb-6">
                <p>Kepada Yth.</p>
                <p><strong>Bapak / Ibu Orang Tua / Wali Siswa</strong></p>
                <p>dari: <strong>{{ $student->name }}</strong> (Kelas: {{ $class->name ?? '-' }})</p>
                <p>di Tempat</p>
            </div>

            <!-- ISI SURAT -->
            <div class="space-y-4 text-justify">
                <p>
                    Dengan hormat,
                </p>
                <p class="indent-8">
                    Sehubungan dengan adanya hal penting terkait perkembangan proses belajar dan kedisiplinan putra/putri Bapak/Ibu di sekolah, dengan ini kami mengharapkan kehadiran Bapak/Ibu Orang Tua / Wali Murid pada:
                </p>

                <div class="pl-8 my-4">
                    <table class="w-full">
                        <tr>
                            <td class="w-36 py-1">Hari / Tanggal</td>
                            <td class="w-4 py-1">:</td>
                            <td class="py-1 font-bold">{{ \Carbon\Carbon::parse($liaison->created_at)->addDays(1)->translatedFormat('l, d F Y') }}</td>
                        </tr>
                        <tr>
                            <td class="py-1">Waktu</td>
                            <td class="py-1">:</td>
                            <td class="py-1">Pukul 08.30 WIB s.d. Selesai</td>
                        </tr>
                        <tr>
                            <td class="py-1">Tempat</td>
                            <td class="py-1">:</td>
                            <td class="py-1">Ruang Bimbingan Konseling (BK) / Ruang Guru SMPN 3 Lakbok</td>
                        </tr>
                        <tr>
                            <td class="py-1">Bertemu dengan</td>
                            <td class="py-1">:</td>
                            <td class="py-1">Wali Kelas / Guru BK ({{ $homeroomTeacher->name ?? 'Wali Kelas' }})</td>
                        </tr>
                        <tr>
                            <td class="py-1 align-top">Keperluan / Catatan</td>
                            <td class="py-1 align-top">:</td>
                            <td class="py-1 align-top font-semibold text-slate-900">
                                {{ $liaison->title }}
                                <div class="text-xs font-normal mt-1 italic text-slate-700 bg-slate-50 p-2 rounded border border-slate-200">
                                    "{{ $liaison->message }}"
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

                <p class="indent-8">
                    Mengingat pentingnya koordinasi ini demi masa depan dan kelancaran pendidikan putra/putri Bapak/Ibu, kami sangat mengharapkan kehadiran Bapak/Ibu tepat pada waktu yang telah ditentukan.
                </p>

                <p class="indent-8">
                    Demikian surat panggilan ini kami sampaikan. Atas perhatian, kerja sama, dan kehadiran Bapak/Ibu, kami ucapkan terima kasih.
                </p>
            </div>

            <!-- TANDA TANGAN -->
            <div class="mt-12 flex justify-between items-start">
                <div class="text-center w-64">
                    <p>Mengetahui,</p>
                    <p>Kepala SMP Negeri 3 Lakbok</p>
                    <div class="h-20"></div>
                    <p class="font-bold underline">TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.</p>
                    <p class="text-xs">NIP. 198028032008011003</p>
                </div>

                <div class="text-center w-64">
                    <p>Guru / Wali Kelas,</p>
                    <div class="h-24"></div>
                    <p class="font-bold underline">{{ $homeroomTeacher->name ?? 'Wali Kelas' }}</p>
                    <p class="text-xs">NIP. {{ $homeroomTeacher->nip ?? '-' }}</p>
                </div>
            </div>

        </div>
    </div>

</body>
</html>
