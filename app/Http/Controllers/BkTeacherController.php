<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BkSession;
use App\Models\BkRecord;
use App\Jobs\SendGeneralWaJob; 
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class BkTeacherController extends Controller
{
    /**
     * Menampilkan daftar antrian konseling.
     */
   public function index(Request $request)
    {
        // 1. MENGHITUNG STATISTIK UNTUK CARDS 
        $stats = [
            'pending' => \App\Models\BkSession::where('status', 'pending')->count(),
            'approved' => \App\Models\BkSession::where('status', 'approved')->count(),
            'finished' => \App\Models\BkSession::where('status', 'finished')->whereMonth('created_at', now()->month)->count(),
            'rejected' => \App\Models\BkSession::where('status', 'rejected')->whereMonth('created_at', now()->month)->count(),
        ];

        // 2. QUERY UTAMA (Eager Loading)
        $query = \App\Models\BkSession::with(['student.schoolClass', 'category']);

        // =========================================================
        // FITUR BARU 1: FILTER KELAS (ADVANCED FILTER)
        // =========================================================
        if ($request->has('class_id') && $request->class_id != '') {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        // =========================================================
        // FITUR BARU 2: FILTER TANGGAL PENGAJUAN (ADVANCED FILTER)
        // =========================================================
        if ($request->has('start_date') && $request->start_date != '' && $request->has('end_date') && $request->end_date != '') {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00', 
                $request->end_date . ' 23:59:59'
            ]);
        }

        // 3. FITUR PENCARIAN (Logika Asli)
        if ($request->has('search') && $request->search != '') {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                // Cari berdasarkan nama siswa
                $q->whereHas('student', function($sq) use ($searchTerm) {
                    $sq->where('name', 'like', '%' . $searchTerm . '%');
                })
                // Atau cari berdasarkan nama kategori/topik
                ->orWhereHas('category', function($sq) use ($searchTerm) {
                    $sq->where('name', 'like', '%' . $searchTerm . '%');
                })
                // Atau cari di dalam pesan awal
                ->orWhere('initial_message', 'like', '%' . $searchTerm . '%');
            });
        }

        // 4. FILTER STATUS (Logika Asli)
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        } else {
            // Default sorting: Pending paling atas
            $query->orderByRaw("FIELD(status, 'pending', 'approved', 'ongoing', 'finished', 'rejected')");
        }

        // 5. FILTER TIPE KATEGORI (Logika Asli)
        if ($request->has('type') && $request->type != 'all') {
            if ($request->type == 'bermasalah') {
                $query->where('is_system_generated', true)
                      ->where('initial_message', 'like', '%PELANGGARAN%');
            } elseif ($request->type == 'berprestasi') {
                $query->where('is_system_generated', true)
                      ->where('initial_message', 'like', '%PRESTASI%');
            } elseif ($request->type == 'mandiri') {
                $query->where(function($q) {
                    $q->where('is_system_generated', false)
                      ->orWhereNull('is_system_generated');
                });
            }
        }

        // 6. FITUR EXPORT PDF & EXCEL (Logika Asli)
        if ($request->has('export')) {
            $sessionsExport = $query->latest()->get(); // Ambil semua data sesuai filter (tanpa pagination)

            // Export Mode PDF/Print
            if ($request->export == 'pdf') {
                return view('admin.bk.print', ['sessions' => $sessionsExport]);
            }

            // Export Mode Excel (CSV Format)
            if ($request->export == 'excel') {
                $fileName = 'Laporan_Konseling_' . date('Y-m-d') . '.csv';

                $headers = array(
                    "Content-type"        => "text/csv; charset=UTF-8",
                    "Content-Disposition" => "attachment; filename=$fileName",
                    "Pragma"              => "no-cache",
                    "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                    "Expires"             => "0"
                );

                $columns = array('No', 'Nama Siswa', 'Kelas', 'Kategori/Topik', 'Pesan Pengajuan', 'Metode', 'Status', 'Tanggal Pengajuan');

                $callback = function() use($sessionsExport, $columns) {
                    $file = fopen('php://output', 'w');
                    // Tambahkan BOM agar karakter khusus dibaca UTF-8 dengan benar di MS Excel
                    fputs($file, $bom =(chr(0xEF) . chr(0xBB) . chr(0xBF))); 
                    fputcsv($file, $columns);

                    $no = 1;
                    foreach ($sessionsExport as $session) {
                        $row['No']  = $no++;
                        $row['Nama Siswa']    = $session->student->name ?? 'Data Terhapus';
                        $row['Kelas']    = $session->student->schoolClass->name ?? '-';
                        $row['Kategori/Topik']  = $session->category->name ?? 'Umum';
                        $row['Pesan Pengajuan']  = str_replace(["\r", "\n"], " ", $session->initial_message); // Bersihkan enter
                        $row['Metode']  = $session->method == 'online' ? 'Online' : 'Tatap Muka';
                        
                        $statusMap = ['pending' => 'Pending', 'approved' => 'Terjadwal', 'ongoing' => 'Berlangsung', 'finished' => 'Selesai', 'rejected' => 'Ditolak'];
                        $row['Status']  = $statusMap[$session->status] ?? $session->status;
                        
                        $row['Tanggal Pengajuan']  = $session->created_at->format('Y-m-d H:i');

                        fputcsv($file, array($row['No'], $row['Nama Siswa'], $row['Kelas'], $row['Kategori/Topik'], $row['Pesan Pengajuan'], $row['Metode'], $row['Status'], $row['Tanggal Pengajuan']));
                    }
                    fclose($file);
                };

                return response()->stream($callback, 200, $headers);
            }
        }

        $sessions = $query->latest()->paginate(15)->withQueryString(); 
        
        // =========================================================
        // FITUR BARU 3: AMBIL DATA KELAS UNTUK DROPDOWN FILTER
        // =========================================================
        $classes = \App\Models\SchoolClass::orderBy('name')->get();

        // Tambahkan $classes ke dalam fungsi compact()
        return view('admin.bk.index', compact('sessions', 'stats', 'classes'));
    }

    /**
     * Menampilkan detail sesi dan form approval/jurnal.
     */
    public function show($id)
    {        
        $session = BkSession::with(['student.schoolClass', 'category', 'record'])->findOrFail($id);
        
        return view('admin.bk.show', compact('session'));
    }

    /**
     * Mengupdate status pengajuan (Setujui/Tolak/Selesai).
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected,finished,ongoing',
            'scheduled_at' => 'required_if:status,approved|nullable|date',
            'response_message' => 'nullable|string'
        ]);

        $session = BkSession::with('student')->findOrFail($id);
        
        $session->update([
            'status' => $request->status,
            'scheduled_at' => ($request->status == 'approved') ? $request->scheduled_at : $session->scheduled_at,
            'response_message' => $request->response_message,
            'teacher_id' => Auth::id(),
        ]);

         // --- LOGIKA NOTIFIKASI WA (DIPERBARUI UNTUK MENDUKUNG APRESIASI LANGSUNG) ---
        if (in_array($request->status, ['approved', 'finished']) && $session->student && $session->student->parent_wa_number) {
            $guru = Auth::user()->name;
            $message = "";
                         
            if ($request->status == 'approved') {
                $date = Carbon::parse($request->scheduled_at)->translatedFormat('l, d F Y H:i');
                $message = "Halo *{$session->student->name}*,\n\nPengajuan konseling kamu telah *DISETUJUI*.\n\n👨‍🏫 Guru: {$guru}\n📅 Jadwal: {$date} WIB\n📍 Lokasi: Ruang BK\n💬 Pesan: _{$request->response_message}_";
            } elseif ($request->status == 'ongoing') {
                // Notifikasi untuk memulai chat online
                $message = "Halo *{$session->student->name}*,\n\nSaya telah menerima pengajuan konseling *Online* kamu. Mari kita berdiskusi melalui sistem atau chat WhatsApp sekarang.\n\n💬 Pesan Guru: _{$request->response_message}_";
            } elseif ($request->status == 'finished') {
                $message = "Pemberitahuan dari BK untuk *{$session->student->name}*:\n\n{$request->response_message}\n\n👨‍🏫 Sesi konseling telah dinyatakan Selesai.";
            }

            try {
                SendGeneralWaJob::dispatch($session->student->parent_wa_number, $message);
            } catch (\Exception $e) {
                \Log::error("Gagal kirim WA: " . $e->getMessage());
            }
        }

        // Jika aksi adalah "Pemberitahuan Langsung" (Selesai), kita buat jurnal otomatis
        if ($request->status == 'finished') {
            BkRecord::updateOrCreate(
                ['bk_session_id' => $session->id],
                [
                    'problem_analysis' => 'Penyampaian informasi atau apresiasi secara langsung via Sistem dan WhatsApp.',
                    'solution' => 'Pesan pemberitahuan telah berhasil dikirim tanpa memerlukan tatap muka.',
                    'result' => $request->response_message,
                    'is_confidential' => 0,
                ]
            );
        }

        return back()->with('success', 'Status konseling & notifikasi berhasil diperbarui.');
    }

    /**
     * Menyimpan hasil konseling.
     */
    public function storeRecord(Request $request, $id)
    {
        $request->validate([
            'problem_analysis' => 'required|string',
            'solution' => 'required|string',
            'result' => 'nullable|string',
        ]);

        $session = BkSession::findOrFail($id);

        BkRecord::updateOrCreate(
            ['bk_session_id' => $session->id],
            [
                'problem_analysis' => $request->problem_analysis,
                'solution' => $request->solution,
                'result' => $request->result,
                'is_confidential' => $request->has('is_confidential') ? 1 : 0,
            ]
        );

        $session->update(['status' => 'finished']);

        return redirect()->route('admin.bk.index')->with('success', 'Jurnal konseling berhasil disimpan.');
    }

   // =========================================================================
    // API CHAT UNTUK GURU
    // =========================================================================

    public function getMessages($id)
    {
        if (!class_exists('\App\Models\BkChat')) { return response()->json([]); }
        $messages = \App\Models\BkChat::where('bk_session_id', $id)->oldest()->get();
        return response()->json($messages);
    }

    public function sendMessage(Request $request, $id)
    {
        $request->validate(['message' => 'required|string']);

        $chat = new \App\Models\BkChat();
        $chat->bk_session_id = $id;
        $chat->user_id = Auth::id(); // ID Guru yang merespon
        $chat->message = $request->message;
        $chat->sender_type = 'teacher';
        $chat->save();

        return response()->json($chat);
    }

    /**
     * Memproses Bulk Action (Tandai Selesai / Kirim WA Massal)
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:bk_sessions,id',
            'action_type' => 'required|in:finish,wa'
        ]);

        $ids = $request->ids;

        // AKSI 1: TANDAI SELESAI MASSAL
        if ($request->action_type === 'finish') {
            \App\Models\BkSession::whereIn('id', $ids)->update([
                'status' => 'finished',
                'response_message' => 'Sesi diselesaikan secara massal oleh Guru BK.',
                'updated_at' => now()
            ]);
            
            return redirect()->back()->with('success', count($ids) . ' Sesi berhasil ditandai selesai.');
        }

        // AKSI 2: PANGGILAN WA MASSAL
        if ($request->action_type === 'wa') {
            // Eager load relasi student untuk mengambil no WA
            $sessions = \App\Models\BkSession::with('student')->whereIn('id', $ids)->get();
            $count = 0;

            foreach ($sessions as $session) {
                if ($session->student && $session->student->parent_wa_number) {
                    $studentName = $session->student->name;
                    $msg = "Yth. Bapak/Ibu Orang Tua/Wali dari {$studentName},\n\nKami mengundang kehadiran Bapak/Ibu ke sekolah untuk mendiskusikan perkembangan dan rekap kedisiplinan ananda. Mohon konfirmasi kesediaan waktunya untuk komunikasi lebih lanjut.\n\nSalam hangat dari Bimbingan Konseling Sekolah.";
                    
                    // Dispatch Job WA (Gunakan job yang sudah kamu buat sebelumnya)
                    \App\Jobs\SendGeneralWaJob::dispatch($session->student->parent_wa_number, $msg);
                    $count++;
                }
            }

            return redirect()->back()->with('success', "Permintaan panggilan WA berhasil diproses untuk {$count} orang tua siswa.");
        }

        return redirect()->back()->with('error', 'Aksi tidak dikenali.');
    }
}