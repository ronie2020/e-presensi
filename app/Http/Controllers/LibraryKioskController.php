<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\LibraryVisit;
use App\Models\Book;
use App\Models\Borrowing;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class LibraryKioskController extends Controller
{
    public function index()
    {
        // 1. Ambil data kunjungan hari ini (Logika Lama Tetap Ada)
        $recentVisits = LibraryVisit::with('student')
                        ->whereDate('date', Carbon::today())
                        ->latest()
                        ->take(10)
                        ->get()
                        ->map(function ($visit) {
                            return [
                                'name' => $visit->student->name,
                                'status' => true,
                                'message' => 'Tercatat',
                                'time_log' => Carbon::parse($visit->time)->format('H:i') 
                            ];
                        });

        // 2. Rekomendasi Buku (Logika Lama Tetap Ada)
        $recommendations = Book::inRandomOrder()
                            ->where('stock', '>', 0)
                            ->take(3)
                            ->get();

        return view('library.kiosk', compact('recentVisits', 'recommendations'));
    }

    public function process(Request $request)
    {
        $scanData = $request->scan_data;
        $mode = $request->mode ?? 'attendance'; // 'attendance' atau 'check'

        // 1. Cari Siswa
        $student = Student::where('rfid_id', $scanData)
                    ->orWhere('student_id', $scanData)
                    ->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'error_type' => 'not_found',
                'scanned_id' => $scanData,
                'message' => 'Kartu belum terdaftar.',
            ]);
        }

        // 2. [LOGIKA LAMA] Cek Overdue (Tunggakan)
        $overdueBooks = Borrowing::with('book')
                        ->where('student_id', $student->id)
                        ->where('status', 'borrowed')
                        ->where('due_date', '<', now())
                        ->get();
        
        $hasOverdue = $overdueBooks->count() > 0;
        $overdueTitles = $overdueBooks->map(fn($b) => $b->book->title)->implode(', ');

        // 3. [LOGIKA LAMA] Cek Ulang Tahun
        $isBirthday = $student->dob ? Carbon::parse($student->dob)->isBirthday() : false;

        // 4. [LOGIKA LAMA + BARU] Hitung Statistik Kunjungan
        $visitCount = LibraryVisit::where('student_id', $student->id)->count() + 1; // Total Kunjungan
        
        // [BARU] Hitung Kunjungan Minggu Ini
        $weeklyVisits = LibraryVisit::where('student_id', $student->id)
                        ->whereBetween('date', [now()->startOfWeek(), now()->endOfWeek()])
                        ->count() + 1;

        // --- MODE CEK STATUS (Self Check) ---
        if ($mode === 'check') {
             $activeLoans = Borrowing::where('student_id', $student->id)
                            ->where('status', 'borrowed')
                            ->count();
            
            return response()->json([
                'success' => true,
                'mode' => 'check',
                'student_name' => $student->name,
                'active_loans' => $activeLoans,
                'has_overdue' => $hasOverdue,
                'overdue_titles' => $overdueTitles,
                'message' => 'Cek status berhasil.'
            ]);
        }

        // --- MODE ATTENDANCE (Absensi) ---
        $today = Carbon::today();

        // 5. Cek Spam (5 Menit)
        $lastVisit = LibraryVisit::where('student_id', $student->id)
                        ->where('date', $today)
                        ->latest()
                        ->first();

        if ($lastVisit && Carbon::parse($lastVisit->time)->diffInMinutes(now()) < 5) {
            return response()->json([
                'success' => false,
                'error_type' => 'duplicate',
                'student_name' => $student->name,
                'message' => 'Anda sudah mengisi buku tamu barusan.',
            ]);
        }

        try {
            LibraryVisit::create([
                'student_id' => $student->id,
                'date' => $today,
                'time' => now(),
            ]);

            // --- [BARU] LOGIKA PENENTUAN PESAN (Message Priority) ---
            $firstName = explode(' ', trim($student->name))[0];
            
            // Default Message
            $titleMsg = "Selamat Datang!";
            $subMsg = "Kunjungan tercatat.";

            // Priority 1: Ulang Tahun
            if ($isBirthday) {
                $titleMsg = "Selamat Ulang Tahun, {$firstName}! 🎂";
                $subMsg = "Semoga panjang umur & rajin membaca!";
            } 
            // Priority 2: Milestone Kunjungan (ke-10, 20, 50, 100)
            elseif ($visitCount % 10 == 0) {
                $titleMsg = "Wow! Kunjungan ke-$visitCount 🎉";
                $subMsg = "Luar biasa! Kamu pengunjung yang rajin.";
            }
            // Priority 3: Habit Mingguan (Jika lebih dari 1x minggu ini)
            elseif ($weeklyVisits > 1) {
                $titleMsg = "Halo {$firstName}!";
                $subMsg = "Ini kunjungan ke-{$weeklyVisits} kamu minggu ini. Pertahankan!";
            }

            return response()->json([
                'success' => true,
                'mode' => 'attendance',
                'student_name' => $student->name,
                'message' => $titleMsg,        // Judul Utama
                'sub_message' => $subMsg,      // [PERBAIKAN] Subtitle motivasi dikirim ke view
                'is_birthday' => $isBirthday,
                'visit_count' => $visitCount,
                'has_overdue' => $hasOverdue,
                'overdue_titles' => $overdueTitles
            ]);
        } catch (\Exception $e) {
            Log::error("Kiosk Save Error for {$student->name}: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'error_type' => 'server_error',
                'student_name' => $student->name,
                'message' => 'Gagal menyimpan data.',
            ]);
        }
    }
}