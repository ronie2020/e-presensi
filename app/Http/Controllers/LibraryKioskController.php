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
        // 1. Ambil data kunjungan hari ini
        $recentVisits = LibraryVisit::with('student')
                        ->whereDate('date', Carbon::today())
                        ->latest()
                        ->take(10)
                        ->get()
                        ->map(function ($visit) {
                            return [
                                'name' => optional($visit->student)->name ?? 'Siswa (Dihapus)',
                                'status' => true,
                                'message' => 'Tercatat',
                                'time_log' => Carbon::parse($visit->time)->format('H:i') 
                            ];
                        });

        // 2. Rekomendasi Buku
        $recommendations = Book::inRandomOrder()
                            ->where('stock', '>', 0)
                            ->take(3)
                            ->get();

        return view('library.kiosk', compact('recentVisits', 'recommendations'));
    }

    public function process(Request $request)
    {
        // PERBAIKAN: Pindahkan try-catch ke paling atas agar semua error tertangkap
        try {
            $scanData = $request->scan_data;
            $mode = $request->mode ?? 'attendance'; 

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

            // 2. Cek Overdue (Tunggakan)
            $overdueBooks = Borrowing::with('book')
                            ->where('student_id', $student->id)
                            ->where('status', 'borrowed')
                            ->where('due_date', '<', now())
                            ->get();
            
            $hasOverdue = $overdueBooks->count() > 0;
            $overdueTitles = $overdueBooks->map(function($b) {
                return optional($b->book)->title ?? 'Buku Tidak Dikenal';
            })->implode(', ');

            // 3. Cek Ulang Tahun (Tambahkan validasi isValid)
            $isBirthday = false;
            if ($student->dob) {
                try {
                    $isBirthday = Carbon::parse($student->dob)->isBirthday();
                } catch (\Exception $e) {
                    // Abaikan jika format tanggal lahir di DB cacat
                    $isBirthday = false; 
                }
            }

            // 4. Hitung Statistik Kunjungan
            $visitCount = LibraryVisit::where('student_id', $student->id)->count() + 1;
            
            // Hitung Kunjungan Minggu Ini
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

            // 6. Simpan Kunjungan
            LibraryVisit::create([
                'student_id' => $student->id,
                'date' => $today,
                'time' => now()->format('H:i:s'), // Format aman untuk kolom TIME SQL
            ]);

            // --- LOGIKA PENENTUAN PESAN (Message Priority) ---
            $firstName = explode(' ', trim($student->name))[0];
            $titleMsg = "Selamat Datang!";
            $subMsg = "Kunjungan tercatat.";

            if ($isBirthday) {
                $titleMsg = "Selamat Ulang Tahun, {$firstName}! 🎂";
                $subMsg = "Semoga panjang umur & rajin membaca!";
            } elseif ($visitCount % 10 == 0) {
                $titleMsg = "Wow! Kunjungan ke-$visitCount 🎉";
                $subMsg = "Luar biasa! Kamu pengunjung yang rajin.";
            } elseif ($weeklyVisits > 1) {
                $titleMsg = "Halo {$firstName}!";
                $subMsg = "Ini kunjungan ke-{$weeklyVisits} kamu minggu ini. Pertahankan!";
            }

            return response()->json([
                'success' => true,
                'mode' => 'attendance',
                'student_name' => $student->name,
                'message' => $titleMsg,        
                'sub_message' => $subMsg,     
                'is_birthday' => $isBirthday,
                'visit_count' => $visitCount,
                'has_overdue' => $hasOverdue,
                'overdue_titles' => $overdueTitles
            ]);

        } catch (\Exception $e) {
            // JIKA TERJADI ERROR, KEMBALIKAN JSON (Bukan halaman HTML)
            Log::error("Kiosk Process Error: " . $e->getMessage() . " di baris " . $e->getLine());

            return response()->json([
                'success' => false,
                'error_type' => 'server_error',
                'student_name' => $request->scan_data ?? 'Sistem',
                'message' => 'Sistem Sibuk / Error Internal',
                'debug_msg' => config('app.debug') ? $e->getMessage() : null
            ], 500); // Set status 500
        }
    }
}