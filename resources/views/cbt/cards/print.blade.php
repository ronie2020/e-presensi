<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kartu Peserta Ujian</title>
    <style>
        @page { size: A4; margin: 1cm; }
        body { font-family: 'Arial', sans-serif; -webkit-print-color-adjust: exact; }
        
        .container {
            display: grid;
            grid-template-columns: repeat(2, 1fr); /* 2 Kartu per baris */
            gap: 15px;
        }

        .card {
            border: 1px solid #000;
            padding: 0;
            position: relative;
            height: 220px; /* Tinggi Kartu */
            page-break-inside: avoid;
            background: #fff;
        }

        .header {
            background-color: #e2e8f0; /* Warna Header Abu muda */
            padding: 10px;
            border-bottom: 1px solid #000;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .logo {
            width: 40px;
            height: 40px;
            background: #ccc; 
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center; font-size: 8px;
            overflow: hidden;
        }
        
        /* Logo Image */
        .logo img { width: 100%; height: 100%; object-fit: cover; }

        .school-info h2 { margin: 0; font-size: 12pt; font-weight: bold; text-transform: uppercase; }
        .school-info p { margin: 0; font-size: 7pt; }

        .body { padding: 15px; display: flex; gap: 15px; }
        
        .photo-area {
            width: 70px;
            height: 90px;
            border: 1px solid #ddd;
            display: flex; align-items: center; justify-content: center;
            font-size: 8pt; color: #aaa;
            background: #f9f9f9;
            overflow: hidden; /* Agar foto tidak keluar kotak */
        }
        
        /* Style Foto Siswa */
        .photo-area img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .student-info { flex: 1; font-size: 10pt; }
        .student-info table { width: 100%; }
        .student-info td { padding: 2px 0; vertical-align: top; }
        .label { font-weight: bold; width: 60px; }

        .footer {
            position: absolute; bottom: 0; left: 0; right: 0;
            padding: 8px 15px;
            border-top: 1px dashed #ccc;
            font-size: 8pt;
            display: flex; justify-content: space-between; align-items: center;
        }

        .qr-code {
            position: absolute;
            bottom: 15px;
            right: 15px;
            width: 60px;
            height: 60px;
        }

        @media print {
            .no-print { display: none; }
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; font-weight: bold; background: #0f172a; color: white; border: none; cursor: pointer; border-radius: 5px;">🖨️ Cetak Kartu</button>
    </div>

    <div class="container">
        @foreach($students as $index => $student)
            <div class="card">
                {{-- Header Kartu --}}
                <div class="header">
                    {{-- Ganti src ini dengan logo sekolah Anda --}}
                    <div class="logo">
                        {{-- <img src="{{ asset('img/logo.png') }}" alt="Logo"> --}}
                        LOGO
                    </div> 
                    <div class="school-info">
                        <h2>KARTU PESERTA UJIAN</h2>
                        <p>SMP NEGERI 3 LAKBOK - TAHUN AJARAN {{ date('Y') }}/{{ date('Y')+1 }}</p>
                    </div>
                </div>

                {{-- Body Kartu --}}
                <div class="body">
                    <div class="photo-area">
                        {{-- 
                            LOGIK DETEKSI FOTO:
                            Mengecek kolom 'image', 'photo', atau 'profile_photo_path' di database.
                            Menggunakan asset() untuk mengakses folder storage/public.
                        --}}
                        @if(!empty($student->image))
                            <img src="{{ asset('storage/' . $student->image) }}" alt="Foto">
                        @elseif(!empty($student->photo))
                            <img src="{{ asset('storage/' . $student->photo) }}" alt="Foto">
                        @elseif(!empty($student->profile_photo_path))
                            <img src="{{ asset('storage/' . $student->profile_photo_path) }}" alt="Foto">
                        @else
                            {{-- Placeholder jika tidak ada foto --}}
                            FOTO
                        @endif
                    </div>
                    
                    <div class="student-info">
                        <table>
                            <tr><td class="label">Nama</td><td>: <b>{{ strtoupper($student->name) }}</b></td></tr>
                            <tr><td class="label">NISN</td><td>: {{ $student->student_id }}</td></tr>
                            <tr><td class="label">Kelas</td><td>: {{ $student->schoolClass->name ?? '-' }}</td></tr>
                            <tr><td class="label">Password</td><td>: <b>{{ $student->student_id }}</b></td></tr>
                        </table>
                    </div>
                </div>

                {{-- Footer & QR --}}
                <div class="footer">
                    <div>
                        <i>*Scan QR untuk login cepat</i><br>
                        <strong>Kepala Sekolah</strong>
                    </div>
                </div>

                {{-- Container QR Code --}}
                <div id="qr-{{ $index }}" class="qr-code"></div>
                
                <script>
                    new QRCode(document.getElementById("qr-{{ $index }}"), {
                        text: "{{ $student->login_url }}",
                        width: 60,
                        height: 60,
                        colorDark : "#000000",
                        colorLight : "#ffffff",
                        correctLevel : QRCode.CorrectLevel.L
                    });
                </script>
            </div>
        @endforeach
    </div>

</body>
</html>