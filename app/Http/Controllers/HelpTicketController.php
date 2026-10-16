<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HelpTicket;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class HelpTicketController extends Controller
{
    /**
     * Tampilkan daftar tiket ajuan kendala untuk Admin & Guru
     */
    public function index(Request $request)
    {
        $status       = $request->query('status');
        $reporterType = $request->query('reporter_type');
        $category     = $request->query('category');
        $search       = $request->query('search');

        $query = HelpTicket::with(['student.schoolClass', 'user', 'resolver'])
            ->filter([
                'status'        => $status,
                'reporter_type' => $reporterType,
                'category'      => $category,
                'search'        => $search,
            ])
            ->latest();

        $tickets = $query->paginate(15)->withQueryString();

        // Hitung statistik ringkas (KPI)
        $stats = [
            'total'       => HelpTicket::count(),
            'pending'     => HelpTicket::where('status', 'pending')->count(),
            'in_progress' => HelpTicket::where('status', 'in_progress')->count(),
            'resolved'    => HelpTicket::where('status', 'resolved')->count(),
            'siswa_count' => HelpTicket::where('reporter_type', 'siswa')->count(),
            'guru_count'  => HelpTicket::where('reporter_type', 'guru')->count(),
        ];

        return view('admin.help_tickets.index', compact('tickets', 'stats', 'status', 'reporterType', 'category', 'search'));
    }

    /**
     * Simpan pengajuan kendala / tiket bantuan baru dari siswa atau guru
     */
    public function store(Request $request)
    {
        $request->validate([
            'reporter_type'   => 'required|in:siswa,guru,umum',
            'name'            => 'required|string|max:150',
            'identity_number' => 'nullable|string|max:50',
            'class_or_role'   => 'nullable|string|max:100',
            'phone'           => 'nullable|string|max:30',
            'category'        => 'required|string|max:100',
            'page_url'        => 'nullable|string|max:255',
            'description'     => 'required|string|min:5',
            'screenshot'      => 'nullable|image|mimes:jpeg,png,jpg,webp|max:3072',
        ]);

        // Generate kode tiket unik: HLP-YYYYMMDD-XXXX
        $datePrefix = date('Ymd');
        $randomCode = strtoupper(substr(bin2hex(random_bytes(2)), 0, 4));
        $ticketCode = "HLP-{$datePrefix}-{$randomCode}";

        // Simpan screenshot jika ada
        $screenshotPath = null;
        if ($request->hasFile('screenshot')) {
            $screenshotPath = $request->file('screenshot')->store('help_tickets', 'public');
        }

        // Tentukan user_id atau student_id jika sedang login
        $userId = Auth::check() ? Auth::id() : null;
        $studentId = Auth::guard('student')->check() ? Auth::guard('student')->id() : null;

        // Jika tidak login tapi memasukkan NISN, coba tautkan ke student
        if (!$studentId && $request->filled('identity_number')) {
            $matchedStudent = Student::where('student_id', $request->identity_number)
                ->orWhere('nis', $request->identity_number)
                ->first();
            if ($matchedStudent) {
                $studentId = $matchedStudent->id;
            }
        }

        $ticket = HelpTicket::create([
            'ticket_code'     => $ticketCode,
            'reporter_type'   => $request->reporter_type,
            'user_id'         => $userId,
            'student_id'      => $studentId,
            'name'            => $request->name,
            'identity_number' => $request->identity_number,
            'class_or_role'   => $request->class_or_role,
            'phone'           => $request->phone,
            'category'        => $request->category,
            'page_url'        => $request->page_url ?: url()->previous(),
            'description'     => $request->description,
            'screenshot_path' => $screenshotPath,
            'status'          => 'pending',
        ]);

        // Buat URL pesan WhatsApp untuk opsi konfirmasi langsung
        $waMessage = "Halo Admin SIMADU SMPN 3 Lakbok, saya telah mengirimkan tiket bantuan:\n\n"
            . "• Kode Tiket: *{$ticketCode}*\n"
            . "• Nama: {$ticket->name} ({$ticket->class_or_role})\n"
            . "• Kendala: *{$ticket->category}*\n"
            . "• Keterangan: \"{$ticket->description}\"\n\n"
            . "Mohon bantuannya ya Admin. Terima kasih.";

        $waLink = "https://wa.me/6285135961994?text=" . urlencode($waMessage);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'     => true,
                'message'     => 'Ajuan kendala berhasil dikirim! Tim IT / Admin sekolah akan segera menindaklanjutinya.',
                'ticket_code' => $ticketCode,
                'ticket_id'   => $ticket->id,
                'wa_link'     => $waLink,
            ]);
        }

        return back()->with('success', "Ajuan kendala dengan kode [{$ticketCode}] berhasil dikirim.");
    }

    /**
     * Perbarui status penanganan tiket oleh Admin
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status'      => 'required|in:pending,in_progress,resolved,rejected',
            'admin_notes' => 'nullable|string',
        ]);

        $ticket = HelpTicket::findOrFail($id);

        $updateData = [
            'status'      => $request->status,
            'admin_notes' => $request->admin_notes,
        ];

        if ($request->status === 'resolved') {
            $updateData['resolved_by'] = Auth::id();
            $updateData['resolved_at'] = now();
        } elseif ($request->status === 'pending') {
            $updateData['resolved_by'] = null;
            $updateData['resolved_at'] = null;
        }

        $ticket->update($updateData);

        return back()->with('success', "Status tiket [{$ticket->ticket_code}] berhasil diperbarui.");
    }

    /**
     * Hapus tiket ajuan kendala
     */
    public function destroy($id)
    {
        $ticket = HelpTicket::findOrFail($id);

        if ($ticket->screenshot_path) {
            Storage::disk('public')->delete($ticket->screenshot_path);
        }

        $ticket->delete();

        return back()->with('success', 'Tiket bantuan berhasil dihapus.');
    }
}
