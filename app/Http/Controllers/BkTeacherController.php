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
        // PERBAIKAN: Hanya load 'student' dan 'category'. 
        // Menghapus 'student.schoolClass' untuk mencegah error "Relation Not Found" jika nama relasinya berbeda.
        $query = BkSession::with(['student', 'category']);

        // Filter Status (Logika Tetap)
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        } else {
            // Default sorting: Pending paling atas
            $query->orderByRaw("FIELD(status, 'pending', 'approved', 'ongoing', 'finished', 'rejected')");
        }

        $sessions = $query->latest()->paginate(15);

        return view('admin.bk.index', compact('sessions'));
    }

    /**
     * Menampilkan detail sesi dan form approval/jurnal.
     */
    public function show($id)
    {
        // PERBAIKAN: Sama seperti index, hapus nested relation yang berisiko
        $session = BkSession::with(['student', 'category', 'record'])->findOrFail($id);
        
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

        // --- LOGIKA NOTIFIKASI WA ---
        if ($request->status == 'approved' && $session->student && $session->student->parent_wa_number) {
            $date = Carbon::parse($request->scheduled_at)->translatedFormat('l, d F Y H:i');
            $guru = Auth::user()->name;
            
            $message = "Halo *{$session->student->name}*,\n\nPengajuan konseling kamu telah *DISETUJUI*.\n\n👨‍🏫 Guru: {$guru}\n📅 Jadwal: {$date} WIB\n💬 Metode: {$session->method}\n📝 Pesan: _{$request->response_message}_\n\nSilakan datang tepat waktu ya. Terima kasih.";
            
            try {
                SendGeneralWaJob::dispatch($session->student->parent_wa_number, $message);
            } catch (\Exception $e) {
                // Silent fail jika WA error agar tidak mengganggu proses simpan
                \Log::error("Gagal kirim WA: " . $e->getMessage());
            }
        }

        return back()->with('success', 'Status konseling berhasil diperbarui.');
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