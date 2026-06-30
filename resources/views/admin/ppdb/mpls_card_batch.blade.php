<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kartu MPLS - Ukuran Besar</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* Menggunakan A4 Portrait untuk 4 Kartu */
        @page { 
            size: A4 portrait; 
            margin: 0; 
        }
        
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #cbd5e1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 20px 0;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Konfigurasi Kertas A4 (210mm x 297mm) */
        .a4-page {
            width: 210mm;
            height: 297mm;
            background: white;
            margin-bottom: 20px;
            /* Layout Grid 2x2 */
            display: grid;
            grid-template-columns: repeat(2, 95mm); /* Lebar Kartu 95mm */
            grid-template-rows: repeat(2, 135mm);   /* Tinggi Kartu 135mm */
            gap: 6mm; /* Jarak antar kartu */
            justify-content: center;
            align-content: center;
            padding: 10mm;
            box-sizing: border-box;
            page-break-after: always;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        }

        /* Ukuran Kartu Diperbesar */
        .mpls-card {
            width: 95mm;
            height: 135mm;
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%);
            border: 1px dashed #94a3b8; /* Garis potong */
            border-radius: 12px; /* Radius sedikit diperbesar */
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Ornamen Wave Skala Besar */
        .blue-wave-top {
            position: absolute; top: -30px; left: -10%; width: 120%; height: 95px;
            background: #1e3a8a; border-radius: 0 0 50% 50%; z-index: 1;
        }
        .blue-wave-bottom {
            position: absolute; bottom: -40px; right: -20%; width: 140%; height: 110px;
            background: #1e40af; border-radius: 50% 50% 0 0; transform: rotate(-10deg); z-index: 1;
        }
        
        .content-z { z-index: 10; position: relative; width: 100%; display: flex; flex-direction: column; align-items: center; }
        
        /* Kotak Foto 3x4 (Proporsional dengan kartu besar) */
        .photo-box {
            width: 80px; height: 106px;
            background: white; border: 3px solid #3b82f6; border-radius: 6px;
            margin-top: 60px; display: flex; align-items: center; justify-content: center; flex-direction: column;
            font-size: 10px; font-weight: 700; color: #94a3b8; text-align: center; line-height: 1.4;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }
        
        /* Tabel Data Skala Besar */
        .data-table {
            width: 85%; font-size: 10px; font-weight: 700; color: #1e293b;
            margin-top: 12px; background: rgba(255,255,255,0.85); border-radius: 8px; padding: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }
        .data-table td { padding: 4px; vertical-align: top;}
        .data-label { width: 35%; }
        .data-separator { width: 5%; text-align: center; }

        @media print {
            body { background-color: white; padding: 0; }
            .a4-page { margin: 0; box-shadow: none; border: none; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <!-- Toolbar -->
    <div class="no-print w-full bg-white p-5 mb-6 flex justify-between items-center max-w-[210mm] rounded-xl shadow-sm border border-slate-200">
        <div>
            <h1 class="font-black text-slate-800 text-lg">Cetak ID Card MPLS (Besar)</h1>
            <p class="text-xs text-slate-500 font-medium">Tahun Ajaran {{ $year }} &bull; Format 4 Kartu / Lembar A4</p>
        </div>
        <button onclick="window.print()" class="px-5 py-2.5 bg-blue-600 text-white font-bold rounded-xl text-sm hover:bg-blue-700 shadow-md shadow-blue-500/30 flex items-center gap-2 transition-transform active:scale-95">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            Cetak Kartu
        </button>
    </div>

    <!-- Perhatikan: chunk diubah dari 10 menjadi 4 per halaman -->
    @foreach($students->chunk(4) as $chunk)
    <div class="a4-page">
        @foreach($chunk as $student)
            <div class="mpls-card">
                <!-- Gelombang Latar -->
                <div class="blue-wave-top"></div>
                <div class="blue-wave-bottom"></div>
                
                <div class="content-z w-full px-4">
                    <!-- Teks Header Diperbesar -->
                    <h1 class="text-[16px] font-black text-white mt-3 uppercase tracking-wide drop-shadow-md">MPLS RAMAH {{ $year }}</h1>
                    <h2 class="text-[10px] font-bold text-blue-100 uppercase tracking-widest mt-0.5">SMP NEGERI 3 LAKBOK</h2>
                    
                    <!-- Area Foto Skala Besar -->
                    <div class="photo-box mx-auto">
                        <svg class="w-8 h-8 text-blue-300 mb-1" fill="currentColor" viewBox="0 0 24 24"><path d="M24 22h-24v-20h24v20zm-1-19h-22v18h22v-18zm-1 16h-19l4-7.492 3 3.048 5.013-7.556 6.987 12zm-11.848-2.865l-2.91-2.956-2.574 4.821h15.593l-5.303-9.108-4.806 7.243zm-4.652-11.135c1.38 0 2.5 1.12 2.5 2.5s-1.12 2.5-2.5 2.5-2.5-1.12-2.5-2.5 1.12-2.5 2.5-2.5zm0 1c-.828 0-1.5.672-1.5 1.5s.672 1.5 1.5 1.5 1.5-.672 1.5-1.5-.672-1.5-1.5-1.5z"/></svg>
                        <span>TEMPEL<br>FOTO 3x4</span>
                    </div>

                    <!-- Tabel Data -->
                    <table class="data-table mx-auto">
                        <tr>
                            <td class="data-label text-slate-500">NAMA</td>
                            <td class="data-separator">:</td>
                            <td class="text-blue-900 font-black">{{ \Illuminate\Support\Str::limit($student->name, 25) }}</td>
                        </tr>
                        <tr>
                            <td class="data-label text-slate-500">PESERTA</td>
                            <td class="data-separator">:</td>
                            <td class="text-blue-900 font-bold font-mono">{{ $student->registration_number }}</td>
                        </tr>
                        <tr>
                            <td class="data-label text-slate-500">KELAS</td>
                            <td class="data-separator">:</td>
                            <td class="text-blue-900 font-bold">{{ $student->schoolClass ? $student->schoolClass->name : '-' }}</td>
                        </tr>
                        <tr>
                            <td class="data-label text-slate-500">ASAL SEK.</td>
                            <td class="data-separator">:</td>
                            <td class="text-blue-900 font-bold">{{ \Illuminate\Support\Str::limit($student->school_origin, 22) }}</td>
                        </tr>
                    </table>

                    <!-- Area Bawah (QR Code & Badge) -->
                    <div class="flex w-[85%] justify-between items-end mt-4 mx-auto">
                        <div class="bg-white p-1.5 rounded shadow-sm border border-blue-200">
                            <!-- QR Code NISN - Diperbesar (size 55) -->
                            {!! QrCode::size(55)->margin(1)->generate($student->nisn) !!}
                        </div>
                        <div class="text-right pb-1">
                            <span class="block text-[6px] font-bold text-blue-200 uppercase mb-1 tracking-wider">Pindai Absensi</span>
                            <span class="text-[9px] font-black text-white bg-amber-500 px-3 py-1.5 rounded shadow border border-amber-600">ID: {{ $student->nisn }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @endforeach
</body>
</html>