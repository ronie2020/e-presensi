<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Peminjaman Pustaka - {{ $borrowing->student->name ?? 'Siswa' }}</title>
    <style>
        @page {
            size: 80mm auto;
            margin: 4mm 4mm 4mm 4mm;
        }

        body {
            font-family: 'Courier New', Courier, monospace, 'Lucida Console', Monaco;
            font-size: 11px;
            color: #000;
            background-color: #f8fafc;
            margin: 0;
            padding: 8px;
            line-height: 1.35;
        }

        .ticket {
            width: 72mm;
            max-width: 100%;
            margin: 0 auto;
            background: #fff;
            padding: 10px 8px;
            border: 1px dashed #cbd5e1;
            box-sizing: border-box;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }

        .divider {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        .double-divider {
            border-top: 1px double #000;
            border-bottom: 1px double #000;
            height: 2px;
            margin: 6px 0;
        }

        .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }

        .label {
            color: #1e293b;
            font-size: 10px;
        }

        .value {
            font-weight: bold;
            font-size: 10.5px;
            text-align: right;
            max-width: 55%;
            word-break: break-word;
        }

        .highlight-box {
            border: 1px solid #000;
            padding: 4px;
            margin: 6px 0;
            text-align: center;
            background: #f8fafc;
        }

        .qr-container {
            margin: 8px 0;
            text-align: center;
        }

        .qr-container svg {
            margin: 0 auto;
            display: block;
        }

        .no-print {
            margin-bottom: 10px;
            text-align: center;
        }

        .btn-print {
            background-color: #0284c7;
            color: white;
            padding: 6px 16px;
            font-size: 11px;
            font-weight: bold;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        @media print {
            body { background: transparent; padding: 0; }
            .ticket { border: none; padding: 0; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

    <div class="no-print">
        <button onclick="window.print()" class="btn-print">🖨️ Cetak Struk Pustaka</button>
        <button onclick="window.close()" class="btn-print" style="background:#64748b; margin-left:4px;">Tutup</button>
    </div>

    <div class="ticket">
        
        {{-- HEADER PERPUSTAKAAN --}}
        <div class="text-center">
            <div style="font-size: 10px; font-weight: bold; letter-spacing: 0.5px;">PERPUSTAKAAN DIGITAL</div>
            <div style="font-size: 12px; font-weight: 900; letter-spacing: 1px; margin: 1px 0;">SMP NEGERI 3 LAKBOK</div>
            <div style="font-size: 8.5px; opacity: 0.85;">Kab. Ciamis, Jawa Barat</div>
        </div>

        <div class="double-divider"></div>

        <div class="text-center font-bold" style="font-size: 10.5px; letter-spacing: 0.5px;">
            BUKTI PEMINJAMAN BUKU
        </div>

        <div class="divider"></div>

        {{-- INFO PEMINJAM --}}
        <div class="row">
            <span class="label">No. Transaksi</span>
            <span class="value">#LIB-{{ str_pad($borrowing->id, 5, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="row">
            <span class="label">Nama Siswa</span>
            <span class="value uppercase">{{ $borrowing->student->name ?? '-' }}</span>
        </div>
        <div class="row">
            <span class="label">Kelas / NISN</span>
            <span class="value">{{ $borrowing->student->schoolClass->name ?? '-' }} / {{ $borrowing->student->nisn ?? $borrowing->student->student_id }}</span>
        </div>

        <div class="divider"></div>

        {{-- INFO BUKU --}}
        <div class="row">
            <span class="label">Judul Buku</span>
            <span class="value">{{ $borrowing->book->title ?? '-' }}</span>
        </div>
        <div class="row">
            <span class="label">Kode Buku</span>
            <span class="value">{{ $borrowing->item_code ?? $borrowing->book->code ?? '-' }}</span>
        </div>
        <div class="row">
            <span class="label">Pengarang</span>
            <span class="value">{{ $borrowing->book->author ?? '-' }}</span>
        </div>

        <div class="divider"></div>

        {{-- TANGGAL PINJAM & BATAS KEMBALI --}}
        <div class="row">
            <span class="label">Tgl Pinjam</span>
            <span class="value">{{ $borrowing->borrow_date ? $borrowing->borrow_date->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}</span>
        </div>
        
        <div class="highlight-box">
            <div style="font-size: 9px; font-weight: bold; color: #475569;">BATAS PENGEMBALIAN:</div>
            <div style="font-size: 13px; font-weight: 900; color: #0f172a; margin-top: 1px;">
                {{ $borrowing->due_date ? $borrowing->due_date->format('d M Y') : '-' }}
            </div>
        </div>

        {{-- QR CODE VALIDASI --}}
        <div class="qr-container">
            @if(class_exists('\SimpleSoftwareIO\QrCode\Facades\QrCode'))
                {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(80)->generate(url('/library/circulation?search=' . $borrowing->id)) !!}
            @endif
            <div style="font-size: 8px; margin-top: 3px; opacity: 0.7;">Validasi Pustaka Digital</div>
        </div>

        <div class="divider"></div>

        {{-- FOOTER / CATATAN --}}
        <div style="font-size: 8.5px; text-align: center; line-height: 1.25; opacity: 0.9;">
            <p style="margin: 2px 0;">* Harap kembalikan buku sebelum jatuh tempo.</p>
            <p style="margin: 2px 0;">* Jagalah kebersihan dan keutuhan buku.</p>
            <p style="margin: 4px 0 0 0; font-weight: bold;">Terima Kasih & Selamat Membaca!</p>
        </div>

    </div>

</body>
</html>
