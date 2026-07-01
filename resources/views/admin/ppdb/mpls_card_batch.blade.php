<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Kartu MPLS - Desain Premium</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Montserrat -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Phosphor Icons untuk Ilustrasi -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <style>
        /* Pengaturan Cetak Wajib (Memaksa background tercetak) */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Pengaturan Kertas A4 Portrait (4 Kartu) */
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
            padding: 20px 0;
        }

        /* Container A4 Grid 2x2 */
        .a4-page {
            width: 210mm;
            height: 297mm;
            background: white;
            margin-bottom: 20px;
            display: grid;
            grid-template-columns: repeat(2, 95mm); 
            grid-template-rows: repeat(2, 135mm);   
            gap: 6mm;
            justify-content: center;
            align-content: center;
            padding: 10mm;
            box-sizing: border-box;
            page-break-after: always;
            box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
        }

        /* Kartu Utama */
        .mpls-card {
            width: 95mm;
            height: 135mm;
            background-color: #f8fafc;
            /* Border luar menggunakan warna dinamis dari CSS variable */
            border: 1px dashed #94a3b8; 
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            border-radius: 6px;
        }

        /* ================= DEKORASI BACKGROUND ================= */
        
        /* GAMBAR BACKGROUND SEKOLAH */
        .school-bg-img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: 0;
            opacity: 0.25; 
            filter: grayscale(10%); 
        }

        /* Pola Titik Latar Belakang */
        .bg-pattern {
            position: absolute; inset: 0; z-index: 1; opacity: 0.6;
            background-image: radial-gradient(#64748b 1.5px, transparent 1.5px);
            background-size: 12px 12px;
        }

        /* [MASUKAN #5] Diagonal Stripes */
        .diagonal-stripes {
            position: absolute; inset: 0; z-index: 1; opacity: 0.05;
            background-image: repeating-linear-gradient(
                45deg,
                #000,
                #000 1.5px,
                transparent 1.5px,
                transparent 12px
            );
        }

        /* Garis Bingkai Dalam (Warna Dinamis) */
        .inner-frame {
            position: absolute; inset: 4px; z-index: 20;
            border: 2px solid var(--theme-color);
            border-radius: 6px;
            pointer-events: none;
            opacity: 0.85;
        }

        /* WATERMARK RAKSASA */
        .watermark-left {
            position: absolute; left: -20px; top: 40%;
            font-size: 140px; color: #3b82f6; opacity: 0.04;
            transform: rotate(-15deg); z-index: 1;
        }
        .watermark-right {
            position: absolute; right: -25px; top: 15%;
            font-size: 130px; color: #3b82f6; opacity: 0.04;
            transform: rotate(10deg); z-index: 1;
        }

        /* GELOMBANG POJOK */
        .wave-tl { position: absolute; top: -40px; left: -40px; width: 130px; height: 130px; background: #bfdbfe; border-radius: 50%; z-index: 2; opacity: 0.8; }
        .wave-tr { position: absolute; top: -50px; right: -20px; width: 140px; height: 140px; background: #dbeafe; border-radius: 50%; z-index: 2; opacity: 0.6; }
        .wave-bl { position: absolute; bottom: 20px; left: -40px; width: 120px; height: 120px; background: #93c5fd; border-radius: 50%; z-index: 2; opacity: 0.4; }
        
        .wave-br-dark { position: absolute; bottom: 15px; right: -20px; width: 160px; height: 110px; background: var(--theme-color); border-radius: 70% 30% 0 0; z-index: 3; transform: rotate(-5deg); opacity: 0.9;}
        .wave-br-light { position: absolute; bottom: 30px; right: 0; width: 190px; height: 120px; background: #3b82f6; border-radius: 50% 50% 0 0; z-index: 2; opacity: 0.5;}

        /* ORNAMEN & ILUSTRASI SAMPING */
        .ill-left { position: absolute; top: 38%; left: 8px; z-index: 5; display: flex; flex-direction: column; align-items: center; opacity: 0.85; }
        .ill-right { position: absolute; top: 33%; right: 10px; z-index: 5; display: flex; flex-direction: column; align-items: center; opacity: 0.85; }
        .sparkle-1 { position: absolute; top: 26%; left: 18%; color: #3b82f6; font-size: 16px; opacity: 0.7; z-index: 5; }
        .sparkle-2 { position: absolute; top: 40%; right: 15%; color: #f59e0b; font-size: 14px; opacity: 0.9; z-index: 5; }

        /* ================= KONTEN ================= */
        .content-wrapper { z-index: 10; position: relative; width: 100%; height: 100%; display: flex; flex-direction: column; }

        /* HEADER */
        .header-area {
            display: flex; justify-content: space-between; align-items: flex-start;
            padding: 14px 12px 0 12px; width: 100%; z-index: 15;
        }
        .logo-img { height: 38px; width: auto; object-fit: contain; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.1)); }
        .header-text { text-align: center; flex-grow: 1; padding: 0 5px; }

        /* KOTAK FOTO */
        .photo-container {
            width: 70px; height: 95px; /* Dikecilkan sedikit agar muat layout baru */
            background-color: #e2e8f0;
            border-radius: 8px;
            margin: 16px auto 6px auto; 
            box-shadow: 0 0 0 3px #ffffff, 0 6px 12px rgba(0, 0, 0, 0.1);
            display: flex; flex-direction: column; justify-content: center; align-items: center;
            color: #94a3b8; font-size: 9px; font-weight: 700; text-align: center; line-height: 1.2;
            position: relative; z-index: 15;
        }

        /* AREA TEKS INFO */
        .info-area { text-align: center; padding: 0 12px; flex-grow: 1; z-index: 15; }
        
        /* [MASUKAN #1] Glassmorphism Box */
        .glass-box {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            padding: 6px 10px;
            margin-top: 4px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .label-text { font-size: 9px; font-weight: 700; color: #64748b; margin-top: 3px; text-transform: uppercase; letter-spacing: 0.5px; }
        .value-text { font-size: 11px; font-weight: 900; color: #0f172a; line-height: 1.1; margin-top: 1px; }
        
        .divider-line { width: 40px; height: 2.5px; background: var(--theme-color); margin: 3px auto 4px auto; border-radius: 2px; }

        /* AREA BAWAH */
        .bottom-area {
            position: absolute; bottom: 22px; left: 0; width: 100%;
            padding: 0 12px; display: flex; justify-content: space-between; align-items: flex-end;
            z-index: 15;
        }
        
        .qr-box {
            background: white; padding: 3px; border-radius: 6px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            display: flex; flex-direction: column; align-items: center;
            border: 1px solid #e2e8f0;
        }

        /* FOOTER BAR (Warna Dinamis) */
        .footer-bar {
            position: absolute; bottom: 0; left: 0; width: 100%; height: 22px;
            background-color: var(--theme-color); display: flex; justify-content: center; align-items: center;
            color: white; font-size: 8px; font-weight: 700; letter-spacing: 0.5px; z-index: 15;
        }

        /* Box Peringatan Print */
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
            <p class="text-xs text-slate-500 font-medium">Desain Premium &bull; Ukuran Besar (4/Halaman)</p>
        </div>
        <button onclick="window.print()" class="px-5 py-2.5 bg-blue-600 text-white font-bold rounded-xl text-sm hover:bg-blue-700 shadow-md shadow-blue-500/30 flex items-center gap-2 transition-transform active:scale-95">
            <i class="ph-bold ph-printer text-lg"></i> Cetak Kartu
        </button>
    </div>

    <!-- Peringatan Grafis Latar Belakang -->
    <div class="no-print print-alert">
        <i class="ph-fill ph-warning-circle text-xl text-amber-500"></i>
        PENTING: Saat menekan tombol cetak, pastikan opsi "Background graphics" (Grafis Latar Belakang) di pengaturan printer Anda sudah DICENTANG agar warna dan ornamen kartu tercetak sempurna.
    </div>

    @foreach($students->chunk(4) as $chunk)
    <div class="a4-page">
        @foreach($chunk as $student)
            
            <!-- [MASUKAN #2] Color Coding Berdasarkan Kelas -->
            @php
                // Mengambil nama kelas, jika tidak ada set kosong
                $className = $student->schoolClass ? $student->schoolClass->name : '';
                // Mengambil karakter terakhir dari nama kelas (misal "7A" -> "A")
                $classLetter = strtoupper(substr(trim($className), -1));
                
                // Menentukan warna utama kartu
                $themeColor = match($classLetter) {
                    'A' => '#ef4444', // Merah
                    'B' => '#22c55e', // Hijau
                    'C' => '#eab308', // Kuning
                    'D' => '#a855f7', // Ungu
                    'E' => '#ec4899', // Pink
                    'F' => '#14b8a6', // Tosca/Teal
                    default => '#1e3a8a', // Biru Tua (Default jika tidak sesuai)
                };
            @endphp

            <!-- Kartu dengan inject variable warna dinamis -->
            <div class="mpls-card" style="--theme-color: {{ $themeColor }};">
                
                <!-- GAMBAR BACKGROUND SEKOLAH -->
                <img src="{{ asset('images/netila.jpg') }}" onerror="this.src='https://images.unsplash.com/photo-1541829070764-84a7d30dd3f3?q=80&w=800&auto=format&fit=crop'" class="school-bg-img">
                
                <!-- Elemen Background Pattern & Bingkai -->
                <div class="bg-pattern"></div>
                <div class="diagonal-stripes"></div>
                <div class="inner-frame"></div>
                
                <!-- Watermark Raksasa -->
                <i class="ph-fill ph-graduation-cap watermark-left"></i>
                <i class="ph-fill ph-buildings watermark-right"></i>
                
                <!-- Gelombang Pojok -->
                <div class="wave-tl"></div>
                <div class="wave-tr"></div>
                <div class="wave-bl"></div>
                <div class="wave-br-light"></div>
                <div class="wave-br-dark"></div>

                <!-- Ilustrasi Samping -->
                <div class="ill-left">
                    <i class="ph-duotone ph-student text-4xl text-blue-700"></i>
                    <i class="ph-fill ph-backpack text-2xl text-blue-500 -mt-2 ml-4"></i>
                </div>
                <div class="ill-right">
                    <i class="ph-duotone ph-books text-3xl text-blue-600 mb-1"></i>
                    <i class="ph-duotone ph-chalkboard-teacher text-[40px] text-blue-800"></i>
                </div>

                <!-- Bintang Estetik -->
                <i class="ph-fill ph-sparkle sparkle-1"></i>
                <i class="ph-fill ph-sparkle sparkle-2"></i>

                <!-- KONTEN UTAMA -->
                <div class="content-wrapper">
                    
                    <!-- HEADER -->
                    <div class="header-area">
                        <img src="{{ asset('images/tut_wuri.png') }}" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/b/b3/Logo_Tut_Wuri_Handayani.svg'" class="logo-img">
                        <div class="header-text">
                            <h1 class="text-[15px] font-black text-slate-900 tracking-tight leading-tight uppercase">MPLS RAMAH {{ $year ?? date('Y') }}</h1>
                            <h2 class="text-[13px] font-black text-slate-800 uppercase mt-0.5 drop-shadow-sm">SMP NEGERI 3 LAKBOK</h2>
                            <p class="text-[11px] font-bold text-slate-600 mt-0.5">Tahun Ajaran {{ $year ?? date('Y') }}/{{ ($year ?? date('Y'))+1 }}</p>
                            <!-- Garis pembatas -->
                            <div class="w-full max-w-[100px] mx-auto h-[1.5px] bg-gradient-to-r from-transparent via-blue-400 to-transparent mt-1.5"></div>
                        </div>
                        <img src="{{ asset('images/logo.png') }}" onerror="this.src='https://via.placeholder.com/100?text=LOGO'" class="logo-img">
                    </div>

                    <!-- FOTO -->
                    <div class="photo-container">
                        <i class="ph-duotone ph-image text-3xl text-slate-300 mb-1"></i>
                        FOTO<br>3x4
                    </div>

                    <!-- DATA SISWA -->
                    <div class="info-area">
                        <!-- Nama -->                        
                        <h3 class="text-[17px] font-black text-slate-900 uppercase leading-tight drop-shadow-sm">
                            {{ \Illuminate\Support\Str::limit($student->name, 26) }}
                        </h3>
                        <div class="divider-line"></div>
                        
                        <!-- Area Data dengan Efek Glassmorphism -->
                        <div class="glass-box">
                            <div class="label-text mt-0">Nomor Peserta</div>
                            <!-- [MASUKAN #4] Highlight NISN/Nomor -->
                            <div class="inline-block bg-slate-800 text-white px-2.5 py-0.5 rounded font-mono text-[11px] font-bold mt-1 shadow-sm tracking-wider">
                                {{ $student->nisn }}
                            </div>

                            <div class="label-text">Kelas</div>
                            <div class="value-text" style="color: var(--theme-color);">
                                {{ $student->schoolClass ? $student->schoolClass->name : '-' }}
                            </div>

                            <div class="label-text">Asal Sekolah</div>
                            <div class="value-text">{{ \Illuminate\Support\Str::limit($student->school_origin, 22) }}</div>
                        </div>
                    </div>

                    <!-- AREA BAWAH SEBELUM FOOTER -->
                    <div class="bottom-area">
                        <!-- Tameng Kiri -->
                        <div class="flex flex-col z-10 pb-1 items-center w-[50px]">
                            <i class="ph-fill ph-shield-check text-[32px] text-blue-800 drop-shadow-md mb-1"></i>
                            <h4 class="text-[9px] font-black text-slate-900 uppercase tracking-wide text-center leading-tight">PESERTA<br>MPLS</h4>
                        </div>

                        <!-- [MASUKAN #3] Garis Tanda Tangan Tengah -->
                        <div class="flex flex-col items-center justify-end z-10 pb-1.5 w-[60px]">
                            <span class="text-[6.5px] font-bold text-slate-800 leading-tight mb-[16px]">Ketua Panitia MPLS</span>
                            <div class="w-full border-b-[1px] border-slate-700"></div>
                        </div>

                        <!-- QR Kanan -->
                        <div class="qr-box z-10">
                            <!-- [MASUKAN #6] Ukuran QR disesuaikan, warna menjadi Navy -->
                            {!! QrCode::size(48)->margin(1)->color(30, 58, 138)->generate($student->nisn) !!}
                            <span class="text-[6px] font-black mt-1 text-slate-700 tracking-wider">ID: {{ $student->nisn }}</span>
                        </div>
                    </div>

                    <!-- FOOTER BAR -->
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