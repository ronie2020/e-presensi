<?php

namespace App\Http\Controllers;

use App\Models\LiaisonBook;
use App\Models\LiaisonChat;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; 

class LiaisonBookController extends Controller
{
    // =========================================================================
    //  BAGIAN 1: MANAJEMEN BUKU PENGHUBUNG (CATATAN RESMI)
    // =========================================================================

    /**
     * Dashboard Guru: Daftar Catatan Resmi
     */
    public function index(Request $request)
    {
        $query = LiaisonBook::with(['student.schoolClass', 'teacher'])->latest();

        // Filter Pencarian
        if ($request->filled('search')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        // Filter Tipe Catatan
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $messages = $query->paginate(10)->withQueryString();
        $classes = SchoolClass::orderBy('name')->get();

        return view('liaison.index', compact('messages', 'classes'));
    }

    /**
     * Dashboard Siswa: Daftar Catatan dari Guru
     */
    public function indexStudent()
    {
        $studentId = Auth::guard('student')->id();

        $messages = LiaisonBook::where('student_id', $studentId)
                        ->with('teacher')
                        ->latest()
                        ->paginate(10);
        
        // Tandai semua catatan di halaman ini sebagai sudah dibaca
        LiaisonBook::where('student_id', $studentId)
                    ->where('is_read_by_parent', false)
                    ->update(['is_read_by_parent' => true]);
        
        return view('liaison.student_index', compact('messages'));
    }

    /**
     * Guru: Simpan Catatan Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'title'      => 'required|string|max:100',
            'type'       => 'required|in:info,warning,achievement,call',
            'message'    => 'required|string',
        ]);

        try {
            LiaisonBook::create([
                'student_id' => $request->student_id,
                'teacher_id' => Auth::id(),
                'title'      => $request->title,
                'type'       => $request->type,
                'message'    => $request->message,
                'is_read_by_parent' => false,
            ]);

            return redirect()->route('liaison.index')->with('success', 'Catatan resmi berhasil dikirim.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengirim catatan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $liaison = LiaisonBook::findOrFail($id);
        
        // hanya admin yang bisa hapus
        if($liaison->teacher_id == Auth::id() || Auth::user()->role == 'admin') {
            $liaison->delete();
            return redirect()->route('liaison.index')->with('success', 'Catatan berhasil dihapus.');
        }

        return redirect()->route('liaison.index')->with('error', 'Anda tidak memiliki hak akses.');
    }

    /**
     * API: Ambil daftar siswa per kelas (Helper Form)
     */
    public function getStudentsByClass($classId)
    {
        $column = $this->detectClassColumn(); 

        try {
            $students = Student::where($column, $classId)
                            ->orderBy('name')
                            ->select('id', 'name')
                            ->get();
            return response()->json($students);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Gagal memuat data siswa.'], 500);
        }
    }


    // =========================================================================
    //  BAGIAN 2: FITUR CHAT (SISI GURU)
    // =========================================================================

    /**
     * Guru: Ambil daftar kontak (Siswa) untuk di-chat
     */
    public function getChatContacts(Request $request)
    {
        $search = $request->query('search'); 
        $classColumn = $this->detectClassColumn();

        $query = Student::query();
        
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // Menambahkan ->with('schoolClass')
        $students = $query->select('id', 'name', $classColumn)
            ->with('schoolClass') // <--- PENTING: Memuat relasi agar nama kelas muncul
            ->withCount(['liaisonChats as unread_count' => function($q) {
                $q->where('sender_type', '!=', 'teacher')->where('is_read', false);
            }])
            ->addSelect(['last_message' => LiaisonChat::select('message')
                ->whereColumn('student_id', 'students.id')
                ->latest()->limit(1)
            ])
            ->addSelect(['last_message_time' => LiaisonChat::select('created_at')
                ->whereColumn('student_id', 'students.id')
                ->latest()->limit(1)
            ])
            ->orderByDesc('last_message_time')
            ->orderBy('name')
            ->get();

        return response()->json($students);
    }

    /**
     * Guru: Ambil riwayat chat dengan satu siswa
     */
    public function getChatMessages($studentId)
    {
        $messages = LiaisonChat::where('student_id', $studentId)
                        ->with('teacher:id,name')
                        ->oldest()
                        ->get();

        // Tandai sebagai dibaca
        LiaisonChat::where('student_id', $studentId)
            ->where('sender_type', '!=', 'teacher')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($messages);
    }

    /**
     * Guru: Kirim pesan chat
     */
    public function sendChatMessage(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'message' => 'required|string',
        ]);

        $chat = LiaisonChat::create([
            'student_id' => $request->student_id,
            'teacher_id' => Auth::id(),
            'message'    => $request->message,
            'sender_type' => 'teacher',
            'is_read'    => false,
        ]);

        return response()->json($chat->load('teacher:id,name'));
    }

    // =========================================================================
    //  BAGIAN 3: FITUR CHAT (SISI SISWA / ORANG TUA)
    // =========================================================================

    public function getStudentChatMessages()
    {
        $studentId = Auth::guard('student')->id();
        
        $messages = LiaisonChat::where('student_id', $studentId)
            ->with('teacher:id,name')
            ->oldest()
            ->get();

        // Tandai pesan guru sebagai sudah dibaca oleh siswa
        LiaisonChat::where('student_id', $studentId)
            ->where('sender_type', 'teacher')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($messages);
    }

    public function sendStudentChatMessage(Request $request)
    {
        $request->validate(['message' => 'required|string']);
        $studentId = Auth::guard('student')->id();

        $chat = LiaisonChat::create([
            'student_id' => $studentId,
            'teacher_id' => null, 
            'message'    => $request->message,
            'sender_type' => 'parent',
            'is_read'    => false,
        ]);

        return response()->json($chat);
    }

    // =========================================================================
    //  HELPER
    // =========================================================================

    private function detectClassColumn()
    {
        $candidates = ['school_class_id', 'class_id', 'classroom_id', 'rombel_id', 'kelas_id'];

        foreach ($candidates as $col) {
            if (Schema::hasColumn('students', $col)) {
                return $col;
            }
        }
        
        return 'school_class_id';
    }
}