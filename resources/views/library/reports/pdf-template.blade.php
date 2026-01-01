<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #000; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; }
        .header p { margin: 2px 0; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #333; padding: 6px 8px; text-align: left; }
        th { background-color: #f0f0f0; font-weight: bold; }
        
        .badge { padding: 2px 5px; border-radius: 4px; font-size: 10px; color: white; display: inline-block; }
        .bg-green { background-color: #16a34a; } /* Emerald-600 */
        .bg-red { background-color: #dc2626; } /* Red-600 */
        .bg-blue { background-color: #2563eb; } /* Blue-600 */
        
        .footer { margin-top: 30px; text-align: right; font-size: 10px; color: #666; }
    </style>
</head>
<body>

    <div class="header">
        <h1>SMP NEGERI 3 LAKBOK</h1>
        <p>Laporan Perpustakaan Digital</p>
        <p><strong>{{ $title }}</strong></p>
    </div>

    @if($type == 'monthly')
        {{-- TABEL LAPORAN SIRKULASI BULANAN --}}
        <table>
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th style="width: 15%">Tanggal</th>
                    <th style="width: 25%">Nama Siswa</th>
                    <th style="width: 30%">Judul Buku</th>
                    <th style="width: 15%">Status</th>
                    <th style="width: 10%">Denda</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $loan)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ \Carbon\Carbon::parse($loan->loan_date)->format('d/m/Y') }}</td>
                        <td>{{ $loan->student->name ?? '-' }}</td>
                        <td>{{ $loan->book->title ?? '-' }}</td>
                        <td>
                            @if($loan->return_date)
                                <span class="badge bg-green">Kembali</span>
                            @else
                                <span class="badge bg-blue">Dipinjam</span>
                            @endif
                        </td>
                        <td>
                            @if($loan->fine > 0)
                                Rp {{ number_format($loan->fine, 0, ',', '.') }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px;">Tidak ada data peminjaman pada periode ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    @elseif($type == 'top_books')
        {{-- TABEL LAPORAN BUKU TERPOPULER --}}
        <table>
            <thead>
                <tr>
                    <th style="width: 5%">Ranking</th>
                    <th style="width: 15%">Kode Buku</th>
                    <th style="width: 40%">Judul Buku</th>
                    <th style="width: 25%">Penulis</th>
                    <th style="width: 15%">Total Dipinjam</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $book)
                    <tr>
                        <td style="text-align: center; font-weight: bold;">{{ $index + 1 }}</td>
                        <td>{{ $book->book_code }}</td>
                        <td>{{ $book->title }}</td>
                        <td>{{ $book->author }}</td>
                        <td style="text-align: center;">{{ $book->loans_count }}x</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center;">Belum ada data peminjaman.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    @endif

    <div class="footer">
        Dicetak pada: {{ date('d F Y H:i') }} oleh Administrator
    </div>

</body>
</html>