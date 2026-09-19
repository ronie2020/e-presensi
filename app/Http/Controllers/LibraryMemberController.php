<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Borrowing;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LibraryMemberController extends Controller
{
    /**
     * Halaman Profil Pustaka Siswa (Riwayat Peminjaman)
     */
    public function show(Student $student)
    {
        // Eager load dengan pagination
        $loans = Borrowing::with('book')
            ->where('student_id', $student->id)
            ->orderBy('borrow_date', 'desc')
            ->paginate(15);

        // Statistik ringkasan
        $stats = [
            'total_loans'      => Borrowing::where('student_id', $student->id)->count(),
            'active_loans'     => Borrowing::where('student_id', $student->id)->where('status', 'borrowed')->count(),
            'overdue_loans'    => Borrowing::where('student_id', $student->id)
                                    ->where('status', 'borrowed')
                                    ->where('due_date', '<', now())
                                    ->count(),
            'total_fine'       => Borrowing::where('student_id', $student->id)->sum('fine_amount'),
            'unpaid_fine'      => Borrowing::where('student_id', $student->id)
                                    ->where('fine_amount', '>', 0)
                                    ->where('fine_paid', false)
                                    ->sum('fine_amount'),
            'favourite_genre'  => $this->getFavouriteGenre($student->id),
        ];

        // 5 buku terakhir dipinjam (untuk kartu visual)
        $recentBooks = Borrowing::with('book')
            ->where('student_id', $student->id)
            ->latest('borrow_date')
            ->take(5)
            ->get();

        return view('library.members.show', compact('student', 'loans', 'stats', 'recentBooks'));
    }

    /**
     * AJAX: Search Siswa untuk autocomplete
     */
    public function search(Request $request)
    {
        $q = $request->get('q');

        $students = Student::with('schoolClass')
            ->where('name', 'like', "%{$q}%")
            ->orWhere('student_id', $q)
            ->orWhere('nis', $q)
            ->orWhere('nisn', $q)
            ->limit(10)
            ->get()
            ->map(fn($s) => [
                'id'         => $s->id,
                'name'       => $s->name,
                'class'      => $s->schoolClass?->name ?? '-',
                'student_id' => $s->student_id,
                'photo'      => $s->photo ?? null,
                'url'        => route('library.members.show', $s),
            ]);

        return response()->json($students);
    }

    /**
     * Helper: Genre favorit siswa berdasarkan kategori buku terbanyak dipinjam
     */
    private function getFavouriteGenre(int $studentId): ?string
    {
        return DB::table('borrowings')
            ->join('books', 'borrowings.book_id', '=', 'books.id')
            ->join('book_categories', 'books.category_id', '=', 'book_categories.id')
            ->where('borrowings.student_id', $studentId)
            ->select('book_categories.name', DB::raw('count(*) as total'))
            ->groupBy('book_categories.name')
            ->orderByDesc('total')
            ->value('name');
    }
}
