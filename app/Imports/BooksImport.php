<?php

namespace App\Imports;

use App\Models\Book;
use App\Models\BookCategory;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Str;

class BooksImport implements ToModel, WithHeadingRow, WithValidation
{
    private $categories;

    public function __construct()
    {
        // Cache data kategori untuk lookup cepat (Nama -> ID)
        // Contoh: ['Buku Pelajaran' => 1, 'Fiksi' => 2]
        $this->categories = BookCategory::pluck('id', 'name')->mapWithKeys(function ($item, $key) {
            return [strtolower($key) => $item];
        });
    }

    /**
    * Mapping data baris Excel ke Model Book
    */
    public function model(array $row)
    {
        // Cari Kategori ID berdasarkan nama di Excel
        $categoryName = strtolower(trim($row['kategori'] ?? ''));
        $categoryId = $this->categories->get($categoryName);

        // Jika kategori tidak ditemukan, biarkan kosong atau set default (opsional)
        
        // Cek apakah buku sudah ada (berdasarkan Kode Buku) -> Update
        $book = Book::where('book_code', $row['kode_buku'])->first();

        if ($book) {
            $book->update([
                'title'          => $row['judul'],
                'category_id'    => $categoryId ?? $book->category_id,
                'author'         => $row['pengarang'],
                'publisher'      => $row['penerbit'],
                'year'           => $row['tahun'],
                'stock'          => $row['stok'] ?? $book->stock,
                'shelf_location' => $row['rak'],
            ]);
            return $book;
        }

        // Create Baru
        return new Book([
            'book_code'      => $row['kode_buku'],
            'title'          => $row['judul'],
            'category_id'    => $categoryId,
            'author'         => $row['pengarang'],
            'publisher'      => $row['penerbit'],
            'year'           => $row['tahun'],
            'stock'          => $row['stok'] ?? 0,
            'shelf_location' => $row['rak'],
            'description'    => $row['sinopsis'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'kode_buku' => 'required',
            'judul'     => 'required',
            'stok'      => 'required|numeric',
        ];
    }
}