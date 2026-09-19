<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LibraryFineController extends Controller
{
    /**
     * Halaman Manajemen Denda
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'unpaid'); // unpaid | all | paid

        $query = Borrowing::with(['student.schoolClass', 'book'])
            ->where('fine_amount', '>', 0);

        if ($filter === 'unpaid') {
            $query->where('fine_paid', false);
        } elseif ($filter === 'paid') {
            $query->where('fine_paid', true);
        }

        if ($request->filled('class_id')) {
            $query->whereHas('student', fn($q) => $q->where('class_id', $request->class_id));
        }

        $loans = $query->orderByRaw('fine_paid ASC, borrow_date DESC')->paginate(20)->withQueryString();

        $summary = [
            'total_unpaid'  => Borrowing::where('fine_amount', '>', 0)->where('fine_paid', false)->sum('fine_amount'),
            'total_paid'    => Borrowing::where('fine_amount', '>', 0)->where('fine_paid', true)->sum('fine_amount'),
            'count_unpaid'  => Borrowing::where('fine_amount', '>', 0)->where('fine_paid', false)->count(),
        ];

        $classes = \App\Models\SchoolClass::orderBy('name')->get();

        return view('library.fines.index', compact('loans', 'summary', 'classes', 'filter'));
    }

    /**
     * Tandai denda sebagai lunas (AJAX)
     */
    public function markPaid(Request $request, Borrowing $borrowing)
    {
        if ($borrowing->fine_amount <= 0) {
            return response()->json(['success' => false, 'message' => 'Tidak ada denda pada transaksi ini.']);
        }

        $borrowing->update([
            'fine_paid'    => true,
            'fine_paid_at' => now(),
            'fine_paid_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => "Denda Rp " . number_format($borrowing->fine_amount, 0, ',', '.') . " untuk {$borrowing->student?->name} telah ditandai lunas.",
        ]);
    }

    /**
     * Tandai BATAL lunas (undo) — AJAX
     */
    public function markUnpaid(Borrowing $borrowing)
    {
        $borrowing->update([
            'fine_paid'    => false,
            'fine_paid_at' => null,
            'fine_paid_by' => null,
        ]);

        return response()->json(['success' => true, 'message' => 'Status denda dikembalikan ke belum lunas.']);
    }
}
