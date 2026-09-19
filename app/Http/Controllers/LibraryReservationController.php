<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookReservation;
use App\Models\Student;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LibraryReservationController extends Controller
{
    /**
     * Halaman Manajemen Reservasi
     */
    public function index(Request $request)
    {
        $reservations = BookReservation::with(['student.schoolClass', 'book'])
            ->whereIn('status', ['waiting', 'ready'])
            ->orderBy('created_at', 'asc')
            ->paginate(20);

        $stats = [
            'total_waiting' => BookReservation::where('status', 'waiting')->count(),
            'total_ready'   => BookReservation::where('status', 'ready')->count(),
            'total_expired' => BookReservation::where('status', 'expired')->count(),
        ];

        return view('library.reservations.index', compact('reservations', 'stats'));
    }

    /**
     * Buat Reservasi Baru (AJAX dari Kiosk/Sirkulasi)
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'book_id'    => 'required|exists:books,id',
        ]);

        $book    = Book::findOrFail($request->book_id);
        $student = Student::findOrFail($request->student_id);

        // Cek: buku masih ada stok? Kalau ada, tidak perlu reservasi
        if ($book->stock > 0) {
            return response()->json(['success' => false, 'message' => 'Stok buku masih tersedia. Langsung pinjam saja!']);
        }

        // Cek: apakah siswa sudah punya reservasi aktif untuk buku ini?
        $exists = BookReservation::where('student_id', $student->id)
            ->where('book_id', $book->id)
            ->whereIn('status', ['waiting', 'ready'])
            ->exists();

        if ($exists) {
            return response()->json(['success' => false, 'message' => 'Anda sudah berada dalam antrean untuk buku ini.']);
        }

        // Cek: maks 1 reservasi aktif per siswa (lintas buku)
        $activeCount = BookReservation::where('student_id', $student->id)
            ->whereIn('status', ['waiting', 'ready'])
            ->count();

        if ($activeCount >= 3) {
            return response()->json(['success' => false, 'message' => 'Batas maksimal 3 reservasi aktif. Batalkan salah satu dulu.']);
        }

        // Hitung posisi antrean
        $queuePosition = BookReservation::where('book_id', $book->id)
            ->where('status', 'waiting')
            ->count() + 1;

        BookReservation::create([
            'student_id' => $student->id,
            'book_id'    => $book->id,
            'status'     => 'waiting',
        ]);

        return response()->json([
            'success'        => true,
            'message'        => "Berhasil masuk antrean #{$queuePosition} untuk buku \"{$book->title}\".",
            'queue_position' => $queuePosition,
        ]);
    }

    /**
     * Batalkan Reservasi (AJAX)
     */
    public function cancel(BookReservation $reservation)
    {
        $reservation->update(['status' => 'cancelled']);

        return response()->json(['success' => true, 'message' => 'Reservasi berhasil dibatalkan.']);
    }

    /**
     * STATIC: Trigger notifikasi antrean ketika buku dikembalikan
     * Dipanggil dari LibraryCirculationController::returnBook()
     */
    public static function notifyNextInQueue(int $bookId): void
    {
        $next = BookReservation::where('book_id', $bookId)
            ->where('status', 'waiting')
            ->orderBy('created_at', 'asc')
            ->first();

        if ($next) {
            $next->update([
                'status'       => 'ready',
                'notified_at'  => now(),
                'expires_at'   => Carbon::now()->addDays(3), // 3 hari untuk ambil
            ]);

            // Simpan ke library_notifications
            try {
                \Illuminate\Support\Facades\DB::table('library_notifications')->insert([
                    'borrowing_id' => 0, // tidak terkait borrowing langsung
                    'student_id'   => $next->student_id,
                    'type'         => 'reservation_ready',
                    'message'      => "Buku \"{$next->book?->title}\" yang Anda reservasi sudah tersedia! Ambil sebelum " . Carbon::now()->addDays(3)->format('d/m/Y') . ".",
                    'date'         => now()->toDateString(),
                    'is_read'      => false,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);
            } catch (\Exception $e) {
                // Notifikasi gagal tidak boleh menggagalkan pengembalian buku
            }
        }
    }
}
