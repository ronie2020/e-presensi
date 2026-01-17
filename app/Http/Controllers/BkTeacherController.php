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
    // Dashboard Antrian Konseling
    public function index(Request $request)
    {
        // PERBAIKAN: Ubah 'student.class' menjadi 'student.schoolClass'
        $query = BkSession::with(['student.schoolClass', 'category']);

        // Filter Status
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        } else {
            // Default tampilkan yang aktif (pending, approved, ongoing) di atas
            $query->orderByRaw("FIELD(status, 'pending', 'approved', 'ongoing', 'finished', 'rejected')");
        }

        $sessions = $query->latest()->paginate(15);

        return view('admin.bk.index', compact('sessions'));
    }

    // Detail & Approval Halaman
    public function show($id)
    {
        // PERBAIKAN: Ubah 'student.class' menjadi 'student.schoolClass'
        $session = BkSession::with(['student.schoolClass', 'category', 'record'])->findOrFail($id);
        return view('admin.bk.show', compact('session'));
    }

    // PROSES 1: Update Status (Terima/Tolak/Jadwalkan)
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
            'teacher_id' => Auth::id(), // Set guru yang menangani
        ]);

        // --- KIRIM WA NOTIFIKASI KE SISWA ---
        if ($request->status == 'approved' && $session->student->parent_wa_number) {
            $date = Carbon::parse($request->scheduled_at)->translatedFormat('l, d F Y H:i');
            $guru = Auth::user()->name;
            
            $message = "Halo *{$session->student->name}*,\n\nPengajuan konseling kamu telah *DISETUJUI*.\n\n👨‍🏫 Guru: {$guru}\n📅 Jadwal: {$date} WIB\n📍 Metode: {$session->method}\n💬 Pesan: _{$request->response_message}_\n\nSilakan datang tepat waktu ya. Terima kasih.";
            
            // Dispatch Job WA (Pastikan queue worker jalan)
            SendGeneralWaJob::dispatch($session->student->parent_wa_number, $message);
        }

        return back()->with('success', 'Status konseling diperbarui.');
    }

    // PROSES 2: Simpan Hasil Konseling (Jurnal)
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

        // Otomatis set status jadi finished jika hasil disimpan
        $session->update(['status' => 'finished']);

        return redirect()->route('admin.bk.index')->with('success', 'Jurnal konseling berhasil disimpan.');
    }
}