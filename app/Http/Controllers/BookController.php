<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Menampilkan daftar buku.
     */
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

    /**
     * Menampilkan form tambah buku.
     */
    public function create()
    {
        $categories = BookCategory::orderBy('name')->get();
        // Generate kode buku otomatis (opsional, misal: BK-Timestamp)
        $autoCode = 'BK-' . time(); 
        
        return view('books.create', compact('categories', 'autoCode'));
    }

    /**
     * Menyimpan buku baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'book_code' => 'required|unique:books,book_code|max:50',
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:book_categories,id',
            'author' => 'nullable|string|max:255',
            'stock' => 'required|integer|min:0',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'cover' => 'nullable|image|max:2048', // Max 2MB
        ]);

        $data = $request->all();

        // Handle Upload Cover
        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('covers', 'public');
            $data['cover_path'] = $path;
        }

        Book::create($data);

        return redirect()->route('books.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit buku.
     */
    public function edit(Book $book)
    {
        $categories = BookCategory::orderBy('name')->get();
        return view('books.edit', compact('book', 'categories'));
    }

    /**
     * Update data buku.
     */
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'book_code' => ['required', 'max:50', Rule::unique('books')->ignore($book->id)],
            'title' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            'cover' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        // Handle Ganti Cover
        if ($request->hasFile('cover')) {
            // Hapus cover lama jika ada
            if ($book->cover_path && Storage::disk('public')->exists($book->cover_path)) {
                Storage::disk('public')->delete($book->cover_path);
            }
            $path = $request->file('cover')->store('covers', 'public');
            $data['cover_path'] = $path;
        }

        $book->update($data);

        return redirect()->route('books.index')->with('success', 'Data buku diperbarui.');
    }

    /**
     * Hapus buku.
     */
    public function destroy(Book $book)
    {
        // Hapus file cover
        if ($book->cover_path && Storage::disk('public')->exists($book->cover_path)) {
            Storage::disk('public')->delete($book->cover_path);
        }
        
        $book->delete();
        return redirect()->route('books.index')->with('success', 'Buku berhasil dihapus.');
    }

    /**
     * Import Buku dari Excel
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,xls,xlsx'
        ]);

        try {
            Excel::import(new BooksImport, $request->file('file'));
            return redirect()->route('library.books.index')->with('success', 'Data buku berhasil diimpor.');
        } catch (\Exception $e) {
            return redirect()->route('library.books.index')->with('error', 'Gagal impor: ' . $e->getMessage());
        }
    }
}