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
        // 1. MENGHITUNG STATISTIK UNTUK CARDS (Dipindah dari Blade ke Controller)
        $stats = [
            'pending' => BkSession::where('status', 'pending')->count(),
            'approved' => BkSession::where('status', 'approved')->count(),
            'finished' => BkSession::where('status', 'finished')->whereMonth('created_at', now()->month)->count(),
            'rejected' => BkSession::where('status', 'rejected')->whereMonth('created_at', now()->month)->count(),
        ];

        // 2. QUERY UTAMA (Tambahkan student.schoolClass agar tidak terjadi N+1 Problem di view)
        $query = BkSession::with(['student.schoolClass', 'category']);

        // 3. FITUR PENCARIAN (Sebelumnya belum ada logika ini)
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

        // 4. FILTER STATUS
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        } else {
            // Default sorting: Pending paling atas
            $query->orderByRaw("FIELD(status, 'pending', 'approved', 'ongoing', 'finished', 'rejected')");
        }

        // 5. FILTER TIPE KATEGORI (Memisahkan Bermasalah & Berprestasi)
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

        $sessions = $query->latest()->paginate(15)->withQueryString(); // Tambahkan withQueryString agar pagination tidak mereset pencarian

        return view('admin.bk.index', compact('sessions', 'stats'));
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
                // Template jika dijadwalkan
                $date = Carbon::parse($request->scheduled_at)->translatedFormat('l, d F Y H:i');
                $message = "Halo *{$session->student->name}*,\n\nPengajuan konseling kamu telah *DISETUJUI*.\n\n👨‍🏫 Guru: {$guru}\n📅 Jadwal: {$date} WIB\n📍 Metode: {$session->method}\n💬 Pesan: _{$request->response_message}_\n\nSilakan datang tepat waktu ya. Terima kasih.";
            } elseif ($request->status == 'finished') {
                // Template jika sekadar pemberitahuan/apresiasi tanpa jadwal
                $message = "Pemberitahuan dari BK untuk *{$session->student->name}*:\n\n{$request->response_message}\n\n👨‍🏫 Salam hangat, {$guru} (Guru BK)";
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
}