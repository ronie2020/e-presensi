<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kartu MPLS - Premium Portrait 7x11</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Montserrat -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Phosphor Icons -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <style>
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Kertas A4 Portrait */
        @page { 
            size: A4 portrait; 
            margin: 0; 
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #cbd5e1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 15mm 0;
        }

        /* Container A4 Grid 2x2 (4 Kartu) */
        .a4-page {
            width: 210mm;
            height: 297mm;
            background: white;
            margin-bottom: 20px;
            display: grid;
            /* Ukuran Fisik BARU: Lebar 70mm, Tinggi 110mm */
            grid-template-columns: repeat(2, 70mm); 
            grid-template-rows: repeat(2, 110mm);   
            gap: 10mm; /* Jarak antar kartu */
            justify-content: center;
            align-content: center;
            padding: 10mm;
            box-sizing: border-box;
            page-break-after: always;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        }

        /* Kartu Utama */
        .mpls-card {
            width: 70mm; /* Diperbarui dari 80mm */
            height: 110mm; /* Diperbarui dari 120mm */
            background-color: #f8fafc;
            border: 1.5px dashed #475569; /* Garis potong diperjelas (lebih tebal dan gelap) */
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            border-radius: 6px;
        }

        /* ================= DEKORASI BACKGROUND ================= */
        .school-bg-img {
            position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover;
            z-index: 0; opacity: 0.12; /* Dibuat lebih tipis/transparan dari 0.25 */ 
            filter: grayscale(10%); 
        }

        .bg-pattern {
            position: absolute; inset: 0; z-index: 1; opacity: 0.35; /* Dibuat lebih tipis dari 0.6 */
            background-image: radial-gradient(#64748b 0.1px, transparent 1px); /* Titik diperkecil dari 1.5px jadi 1px */
            background-size: 12px 12px;
        }

        .diagonal-stripes {
            position: absolute; inset: 0; z-index: 1; opacity: 0.025; /* Dibuat lebih tipis dari 0.05 */
            background-image: repeating-linear-gradient(45deg, #000, #000 0.1px, transparent 0.1px, transparent 12px); /* Garis diperkecil dari 1.5px jadi 1px */
        }

        .inner-frame {
            position: absolute; inset: 4px; z-index: 20;
            border: 3px solid var(--theme-color); /* Bingkai warna dipertebal dari 2px jadi 3px */
            border-radius: 6px; pointer-events: none; opacity: 1; /* Efek transparan dihilangkan agar warna tajam */
        }

        /* Watermark Raksasa */
        .watermark-left { position: absolute; left: -15px; top: 40%; font-size: 100px; color: #3b82f6; opacity: 0.04; transform: rotate(-15deg); z-index: 1; }
        .watermark-right { position: absolute; right: -20px; top: 15%; font-size: 90px; color: #3b82f6; opacity: 0.04; transform: rotate(10deg); z-index: 1; }

        /* Gelombang Ornamen - Ukuran disesuaikan untuk 7x11 */
        .wave-tl { position: absolute; top: -25px; left: -25px; width: 85px; height: 85px; background: #bfdbfe; border-radius: 50%; z-index: 2; opacity: 0.8; }
        .wave-tr { position: absolute; top: -35px; right: -15px; width: 95px; height: 95px; background: #dbeafe; border-radius: 50%; z-index: 2; opacity: 0.6; }
        .wave-bl { position: absolute; bottom: 20px; left: -25px; width: 85px; height: 85px; background: #93c5fd; border-radius: 50%; z-index: 2; opacity: 0.4; }
        
        .wave-br-dark { position: absolute; bottom: 10px; right: -15px; width: 120px; height: 80px; background: var(--theme-color); border-radius: 70% 30% 0 0; z-index: 3; transform: rotate(-5deg); opacity: 0.9;}
        .wave-br-light { position: absolute; bottom: 25px; right: 0; width: 140px; height: 90px; background: #3b82f6; border-radius: 50% 50% 0 0; z-index: 2; opacity: 0.5;}

        /* Wrapper Konten Utama */
        .content-wrapper { z-index: 10; position: relative; width: 100%; height: 100%; display: flex; flex-direction: column; }

        /* HEADER */
        .header-area {
            display: flex; justify-content: space-between; align-items: flex-start;
            padding: 8px 8px 0 8px; width: 100%; z-index: 15;
        }
        .logo-img { height: 22px; width: auto; object-fit: contain; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1)); }
        .header-text { text-align: center; flex-grow: 1; padding: 0 4px; }

        /* KOTAK FOTO 3x4 (Disesuaikan untuk lebar kartu 70mm) */
        .photo-container {
            width: 22mm; height: 30mm; 
            background-color: #e2e8f0;
            border-radius: 6px;
            margin: 6px auto 2px auto; 
            box-shadow: 0 0 0 2px #ffffff, 0 4px 8px rgba(0, 0, 0, 0.1);
            display: flex; flex-direction: column; justify-content: center; align-items: center;
            color: #94a3b8; font-size: 7px; font-weight: 700; text-align: center; line-height: 1.2;
            position: relative; z-index: 15;
        }

        /* AREA TEKS INFO */
        .info-area { text-align: center; padding: 0 8px; flex-grow: 1; z-index: 15; display: flex; flex-direction: column; align-items: center; }
        
        .glass-box {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.9);
            border-radius: 6px;
            padding: 4px 6px;
            margin-top: 2px;
            width: 100%;
            max-width: 60mm;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .label-text { font-size: 8px; font-weight: 700; color: #64748b; margin-top: 2px; text-transform: uppercase; letter-spacing: 0.5px; }
        .value-text { font-size: 11px; font-weight: 900; color: #0f172a; line-height: 1.1; margin-top: 1px; }
        .divider-line { width: 40px; height: 2.5px; background: var(--theme-color); margin: 3px auto 3px auto; border-radius: 2px; }

        /* AREA BAWAH */
        .bottom-area {
            position: absolute; bottom: 26px; left: 0; width: 100%;
            padding: 0 8px; display: flex; justify-content: flex-end; align-items: flex-end;
            z-index: 15;
        }
        
        .qr-box {
            background: white; padding: 2px; border-radius: 6px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            display: flex; flex-direction: column; align-items: center;
            border: 2px solid var(--theme-color);
        }

        /* FOOTER BAR */
        .footer-bar {
            position: absolute; bottom: 0; left: 0; width: 100%; height: 6mm;
            background-color: var(--theme-color); display: flex; justify-content: center; align-items: center;
            color: white; font-size: 6.5px; font-weight: 700; letter-spacing: 0.5px; z-index: 15;
        }

        .print-alert { background: #fef3c7; border: 1px solid #f59e0b; color: #92400e; padding: 10px 15px; border-radius: 8px; font-size: 12px; margin-bottom: 15px; max-width: 210mm; display: flex; align-items: center; gap: 10px; font-weight: 600; }

        @media print {
            body { background-color: white; padding: 0; }
            .a4-page { margin: 0; box-shadow: none; border: none; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <!-- Toolbar (No Print) -->
    <div class="no-print w-full bg-white p-5 mb-4 flex justify-between items-center max-w-[210mm] rounded-xl shadow-sm border border-slate-200">
        <div>
            <h1 class="font-black text-slate-800 text-lg">Cetak ID Card MPLS</h1>
            <p class="text-xs text-slate-500 font-medium">Desain Premium &bull; Ukuran 7x11 cm (Portrait)</p>
        </div>
        <button onclick="window.print()" class="px-5 py-2.5 bg-blue-600 text-white font-bold rounded-xl text-sm hover:bg-blue-700 shadow-md shadow-blue-500/30 flex items-center gap-2 transition-transform active:scale-95">
            <i class="ph-bold ph-printer text-lg"></i> Cetak Kartu
        </button>
    </div>

    <!-- Peringatan Grafis Latar Belakang -->
    <div class="no-print print-alert">
        <i class="ph-fill ph-warning-circle text-xl text-amber-500"></i>
        PENTING: Atur format kertas ke A4 (Portrait) dan pastikan opsi "Background graphics" (Grafis Latar Belakang) DICENTANG.
    </div>

    <!-- LOGIKA LARAVEL: Loop & Chunk Data (TIDAK ADA YANG DIUBAH) -->
    @foreach($students->chunk(4) as $chunk)
    <div class="a4-page">
        @foreach($chunk as $student)
            
            <!-- LOGIKA LARAVEL: Warna Tema berdasarkan Kelas -->
            @php
                $className = $student->schoolClass ? $student->schoolClass->name : '';
                $classLetter = strtoupper(substr(trim($className), -1));
                
                $themeColor = match($classLetter) {
                    'A' => '#ef4444', 
                    'B' => '#22c55e', 
                    'C' => '#eab308', 
                    'D' => '#a855f7', 
                    'E' => '#f97316', 
                    'F' => '#14b8a6', 
                    default => '#1e3a8a', 
                };
            @endphp

            <!-- Pembungkus Kartu -->
            <div class="mpls-card" style="--theme-color: {{ $themeColor }};">
                
                <!-- Background Sekolah & Ornamen CSS -->
                <img src="{{ asset('images/netila.jpg') }}" onerror="this.src='https://images.unsplash.com/photo-1541829070764-84a7d30dd3f3?q=80&w=800&auto=format&fit=crop'" class="school-bg-img">
                <div class="bg-pattern"></div>
                <div class="diagonal-stripes"></div>
                <div class="inner-frame"></div>
                
                <i class="ph-fill ph-graduation-cap watermark-left"></i>
                <i class="ph-fill ph-buildings watermark-right"></i>
                
                <div class="wave-tl"></div><div class="wave-tr"></div><div class="wave-bl"></div>
                <div class="wave-br-light"></div><div class="wave-br-dark"></div>

                <!-- Konten Utama (Data Siswa) -->
                <div class="content-wrapper">
                    
                    <!-- HEADER -->
                    <div class="header-area">
                        <img src="{{ asset('images/tut_wuri.png') }}" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/b/b3/Logo_Tut_Wuri_Handayani.svg'" class="logo-img">
                        <div class="header-text">
                            <!-- LOGIKA LARAVEL: Variabel Tahun Ajaran -->
                            <h1 class="text-[12px] font-black text-slate-900 tracking-tight leading-tight uppercase">MPLS RAMAH {{ $year ?? date('Y') }}</h1>
                            <h2 class="text-[10px] font-black text-slate-800 uppercase mt-0.5 drop-shadow-sm">SMP NEGERI 3 LAKBOK</h2>
                            <p class="text-[7px] font-bold text-slate-600 mt-0.5">Tahun Ajaran {{ $year ?? date('Y') }}/{{ ($year ?? date('Y'))+1 }}</p>
                            <div class="w-full max-w-[70px] mx-auto h-[1.5px] bg-gradient-to-r from-transparent via-slate-400 to-transparent mt-0.5"></div>
                        </div>
                        <img src="{{ asset('images/logo.png') }}" onerror="this.src='https://via.placeholder.com/100?text=LOGO'" class="logo-img">
                    </div>

                    <!-- FOTO -->
                    <div class="photo-container">
                        <i class="ph-duotone ph-image text-xl text-slate-300 mb-0.5"></i>
                        FOTO<br>3x4
                    </div>

                    <!-- AREA TEKS DATA -->
                    <div class="info-area">
                        <!-- LOGIKA LARAVEL: Nama Siswa (Diperbesar) --> 
                        <h3 class="text-[18px] font-black text-slate-900 uppercase leading-tight drop-shadow-sm mt-1">
                            {{ \Illuminate\Support\Str::limit($student->name, 25) }}
                        </h3>
                        <div class="divider-line"></div>
                        
                        <!-- Box Transparan (Hanya Kelas & Asal Sekolah, Diperbesar) -->
                        <div class="glass-box">
                            <div class="label-text mt-0">Kelas</div>
                            <!-- LOGIKA LARAVEL: Relasi Nama Kelas --> 
                            <div class="value-text" style="color: var(--theme-color);">
                                {{ $student->schoolClass ? $student->schoolClass->name : '-' }}
                            </div>

                            <div class="label-text mt-1.5">Asal Sekolah</div>
                            <!-- LOGIKA LARAVEL: Asal Sekolah --> 
                            <div class="value-text">{{ \Illuminate\Support\Str::limit($student->school_origin, 22) }}</div>
                        </div>

                        <!-- LOGIKA LARAVEL: Generate QR Code (Dipindah ke tengah bawah data) -->
                        <div class="qr-box z-10 mt-2">
                            {!! QrCode::size(52)->margin(1)->color(15, 23, 42)->generate($student->nisn) !!}
                            <span class="text-[6.5px] font-black mt-1 text-slate-700 tracking-wider">ID: {{ $student->nisn }}</span>
                        </div>
                    </div>

                    <!-- AREA BAWAH -->
                     <div class="bottom-area">
                        <!-- Label Bawah (Disusun vertikal dan background putih gambar dihilangkan) -->
                        <div class="flex flex-col z-10 pb-1 items-center opacity-95 mr-2">
                            <!-- Tambahan class 'mix-blend-multiply' untuk membuat background putih otomatis transparan -->
                            <img src="{{ asset('images/mpls.png') }}" class="h-6 w-auto object-contain mix-blend-multiply" alt="Icon" onerror="this.style.display='none';">
                            <h4 class="text-[6.5px] font-black text-slate-900 uppercase tracking-widest leading-tight text-center mt-0.5">PESERTA<br>MPLS</h4>
                        </div>
                    </div>

                    <!-- FOOTER -->
                    <div class="footer-bar">
                        Berkarakter &bull; Berprestasi &bull; Berbudaya
                    </div>

                </div>
            </div>
        @endforeach
    </div>
    @endforeach
</body>
</html>