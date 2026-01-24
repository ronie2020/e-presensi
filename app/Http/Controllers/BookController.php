<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\BooksImport;

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
        // UPDATE VALIDASI SIZE DI SINI
        $request->validate([
            'book_code' => 'required|unique:books,book_code|max:50',
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:book_categories,id',
            'author' => 'nullable|string|max:255',
            'stock' => 'required|integer|min:0',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            // Cover dinaikkan ke 5MB (5120 KB)
            'cover' => 'nullable|image|max:5120', 
            // E-Book dinaikkan ke 50MB (51200 KB)
            'ebook_file' => 'nullable|mimes:pdf|max:51200', 
        ]);

        $data = $request->except(['cover', 'ebook_file']);

        if ($request->hasFile('cover')) {
            $path = $request->file('cover')->store('covers', 'public');
            $data['cover_path'] = $path;
        }

        if ($request->hasFile('ebook_file')) {
            $ebookPath = $request->file('ebook_file')->store('ebooks', 'public');
            $data['ebook_path'] = $ebookPath;
        }

        Book::create($data);

        return redirect()->route('library.books.index')->with('success', 'Buku berhasil ditambahkan.');
    }

    public function edit(Book $book)
    {
        $categories = BookCategory::orderBy('name')->get();
        return view('books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        // UPDATE VALIDASI SIZE DI SINI JUGA
        $request->validate([
            'book_code' => ['required', 'max:50', Rule::unique('books')->ignore($book->id)],
            'title' => 'required|string|max:255',
            'stock' => 'required|integer|min:0',
            // Cover dinaikkan ke 5MB
            'cover' => 'nullable|image|max:5120',
            // E-Book dinaikkan ke 50MB
            'ebook_file' => 'nullable|mimes:pdf|max:51200',
        ]);

        $data = $request->except(['cover', 'ebook_file']);

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

    public function read(Book $book)
    {
        if (!$book->ebook_path || !Storage::disk('public')->exists($book->ebook_path)) {
            return back()->with('error', 'File E-Book tidak tersedia atau rusak.');
        }

        return view('books.read', compact('book'));
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
}