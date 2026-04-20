<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BooksImport;
use App\Models\EbookRead; 
use Illuminate\Support\Facades\Auth;
use App\Models\BookCopy; // Tambahkan Model BookCopy
use Illuminate\Support\Facades\DB; // Tambahkan Facade DB

class BookController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $category_id = $request->get('category_id');

        $books = Book::with('category')
            ->when($search, function ($q) use ($search) {
                return $q->where('title', 'like', "%{$search}%")
                         ->orWhere('author', 'like', "%{$search}%")
                         ->orWhere('book_code', 'like', "%{$search}%");
            })
            ->when($category_id, function ($q) use ($category_id) {
                return $q->where('category_id', $category_id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->all());

        $categories = BookCategory::orderBy('name')->get();

        return view('books.index', compact('books', 'categories'));
    }

   public function create()
    {
        $categories = BookCategory::orderBy('name')->get();
        $autoCode = 'BK-' . time(); 
        
        return view('books.create', compact('categories', 'autoCode'));
    }

    public function store(Request $request)    
    {
        // 1. Tentukan Mode: Manual atau Auto
        $mode = $request->input('generation_mode', 'manual');

        // 2. Validasi Dinamis Tergantung Mode
        $rules = [
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:book_categories,id',
            'author' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),       
            'cover' => 'nullable|image|max:5120',        
            'ebook_file' => 'nullable|mimes:pdf|max:51200', 
        ];

        if ($mode === 'manual') {
            $rules['book_code'] = 'required|unique:books,book_code|max:50';
            // Pada mode manual, stok dianggap 1
        } else {
            $rules['jumlah_buku'] = 'required|integer|min:1|max:500';
            // book_code akan di-generate otomatis
        }

        $request->validate($rules);

        // 3. Simpan Data Menggunakan DB Transaction
        DB::beginTransaction();
        try {
            $data = $request->except(['cover', 'ebook_file', 'generation_mode', 'jumlah_buku']);

            // Handle is_textbook (karena checkbox tidak terkirim jika tidak dicentang)
            $data['is_textbook'] = $request->boolean('is_textbook');

            // Handle Upload File
            if ($request->hasFile('cover')) {
                $data['cover_path'] = $request->file('cover')->store('covers', 'public');
            }
            if ($request->hasFile('ebook_file')) {
                $data['ebook_path'] = $request->file('ebook_file')->store('ebooks', 'public');
            }

            // 4. Proses Eksemplar & Stok Induk
            $jumlahBuku = 1;
            if ($mode === 'auto') {
                $data['book_code'] = 'BK-' . time() . '-' . rand(100, 999); // Generate kode induk unik
                $jumlahBuku = $request->input('jumlah_buku');
            }
            
            $data['stock'] = $jumlahBuku; // Set stok di buku induk

            // Buat Induk Buku
            $book = Book::create($data);

            // 5. Generate Fisik Buku (BookCopy / Eksemplar)
            $copiesData = [];
            if ($mode === 'manual') {
                $copiesData[] = [
                    'book_id' => $book->id,
                    'copy_code' => $request->book_code, // Gunakan barcode scan
                    'status' => 'available',
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            } else {
                for ($i = 1; $i <= $jumlahBuku; $i++) {
                    $copiesData[] = [
                        'book_id' => $book->id,
                        'copy_code' => $book->book_code . '-' . str_pad($i, 3, '0', STR_PAD_LEFT), // Misal: BK-170123-123-001
                        'status' => 'available',
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }

            // Insert massal ke tabel BookCopy
            if (count($copiesData) > 0) {
                BookCopy::insert($copiesData);
            }

            DB::commit();
            return redirect()->route('library.books.index')->with('success', 'Buku dan eksemplar fisik berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Gagal menyimpan data: ' . $e->getMessage()]);
        }
    }

    public function edit(Book $book)
    {
        $categories = BookCategory::orderBy('name')->get();
        return view('books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'cover' => 'nullable|image|max:5120',
            'ebook_file' => 'nullable|mimes:pdf|max:51200',
        ]);

        // Abaikan book_code dan stock agar tidak bisa ditembus via Inspect Element
        $data = $request->except(['cover', 'ebook_file', 'book_code', 'stock']);
        
        // Simpan status buku paket
        $data['is_textbook'] = $request->boolean('is_textbook');

        if ($request->hasFile('cover')) {
            if ($book->cover_path && Storage::disk('public')->exists($book->cover_path)) {
                Storage::disk('public')->delete($book->cover_path);
            }
            $data['cover_path'] = $request->file('cover')->store('covers', 'public');
        }

        if ($request->hasFile('ebook_file')) {
            if ($book->ebook_path && Storage::disk('public')->exists($book->ebook_path)) {
                Storage::disk('public')->delete($book->ebook_path);
            }
            $data['ebook_path'] = $request->file('ebook_file')->store('ebooks', 'public');
        }

        $book->update($data);

        return redirect()->route('library.books.index')->with('success', 'Data buku diperbarui.');
    }


    public function destroy(Book $book)
    {
        if ($book->cover_path && Storage::disk('public')->exists($book->cover_path)) {
            Storage::disk('public')->delete($book->cover_path);
        }

        if ($book->ebook_path && Storage::disk('public')->exists($book->ebook_path)) {
            Storage::disk('public')->delete($book->ebook_path);
        }
        
        $book->delete();
        return redirect()->route('library.books.index')->with('success', 'Buku berhasil dihapus.');
    }   

    public function storeCategoryAjax(Request $request)
    {
        $request->validate(['name' => 'required|string|max:100|unique:book_categories,name']);
        try {
            $category = BookCategory::create(['name' => $request->name]);
            return response()->json([
                'success' => true, 
                'id' => $category->id, 
                'name' => $category->name, 
                'message' => 'Kategori berhasil ditambahkan!'
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:csv,xls,xlsx']);
        try {
            Excel::import(new BooksImport, $request->file('file'));
            return redirect()->route('library.books.index')->with('success', 'Data buku berhasil diimpor.');
        } catch (\Exception $e) {
            return redirect()->route('library.books.index')->with('error', 'Gagal impor: ' . $e->getMessage());
        }
    }

    public function catalogue(Request $request)
    {
        $search = $request->get('search');
        $category_id = $request->get('category_id');

        $books = Book::with('category')
            ->when($search, function ($q) use ($search) {
                return $q->where('title', 'like', "%{$search}%")
                         ->orWhere('author', 'like', "%{$search}%")
                         ->orWhere('book_code', 'like', "%{$search}%");
            })
            ->when($category_id, function ($q) use ($category_id) {
                return $q->where('category_id', $category_id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        $categories = BookCategory::orderBy('name')->get();

        return view('books.catalogue', compact('books', 'categories'));
    }

   /**
     * [UPDATE] Halaman Baca E-Book & Tracking Statistik
     */
    public function read(Book $book)
    {
        if (!$book->ebook_path || !Storage::disk('public')->exists($book->ebook_path)) {
            return back()->with('error', 'File E-Book tidak tersedia atau rusak.');
        }

        // --- LOGIKA TRACKING ANALITIK ---
        // Mencatat bahwa buku ini sedang dibaca
        try {
            EbookRead::create([
                'book_id' => $book->id,
                'student_id' => Auth::guard('student')->id() ?? null, // Null jika tamu/admin
            ]);
        } catch (\Exception $e) {
            // Silent fail: Jangan hentikan proses baca hanya karena gagal catat log
        }

        return view('books.read', compact('book'));
    }
}