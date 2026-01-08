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

    public function index(Request $request)
    {
        $query = LiaisonBook::with(['student.schoolClass', 'teacher'])->latest();

        if ($request->filled('search')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $messages = $query->paginate(10)->withQueryString();
        $classes = SchoolClass::orderBy('name')->get();

        return view('liaison.index', compact('messages', 'classes'));
    }

    public function indexStudent()
    {
        $studentId = Auth::guard('student')->id();

        $messages = LiaisonBook::where('student_id', $studentId)
                        ->with('teacher')
                        ->latest()
                        ->paginate(10);
        
        return view('liaison.student_index', compact('messages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'title'      => 'required|string|max:100',
            'type'       => 'required|in:info,warning,achievement,call',
            'message'    => 'required|string',
        ]);

        LiaisonBook::create([
            'student_id' => $request->student_id,
            'teacher_id' => Auth::id(),
            'title'      => $request->title,
            'type'       => $request->type,
            'message'    => $request->message,
            'is_read_by_parent' => false,
        ]);

        return redirect()->route('liaison.index')->with('success', 'Catatan berhasil dikirim ke siswa.');
    }

    public function destroy($id)
    {
        $liaison = LiaisonBook::findOrFail($id);
        
        if($liaison->teacher_id == Auth::id() || Auth::user()->role == 'admin') {
            $liaison->delete();
            return redirect()->route('liaison.index')->with('success', 'Catatan berhasil dihapus.');
        }

        return redirect()->route('liaison.index')->with('error', 'Anda tidak memiliki akses.');
    }

    /**
     * API Helper: Mengambil siswa berdasarkan kelas (Form Catatan)
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
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    // =========================================================================
    //  BAGIAN 2: FITUR CHAT (PESAN ORANG TUA - SISI GURU)
    // =========================================================================

    public function getChatContacts(Request $request)
    {
        $classId = $request->query('class_id');
        $search = $request->query('search'); 
        
        // 1. Deteksi Nama Kolom Kelas (Antisipasi beda nama kolom di DB)
        $classColumn = $this->detectClassColumn();

        $query = Student::query();
        
        // Filter Kelas
        if ($classId) {
            $query->where($classColumn, $classId);
        }

        // Filter Pencarian Nama
        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        // 2. Cek relasi untuk nama kelas (Optional relation loading)
        $withRelations = [];
        try {
            // Kita cek dummy instance untuk melihat relasi apa yang tersedia di Model Student
            $dummy = new Student();
            if (method_exists($dummy, 'schoolClass')) {
                $withRelations[] = 'schoolClass:id,name';
            } elseif (method_exists($dummy, 'classroom')) { 
                $withRelations[] = 'classroom:id,name';
            }
        } catch (\Exception $e) {
            // Abaikan error jika pengecekan relasi gagal, lanjut load data siswa saja
        }

        /**
         * 3. Build Query Utama
         */
        $students = $query->select('id', 'name', $classColumn)
            ->when(!empty($withRelations), function($q) use ($withRelations) {
                return $q->with($withRelations);
            })
            // Menghitung jumlah pesan yang belum dibaca dari ortu (sender != teacher)
            ->withCount(['liaisonChats as unread_count' => function($q) {
                $q->where('sender_type', '!=', 'teacher')->where('is_read', false);
            }])
            // Mengambil cuplikan pesan terakhir
            ->addSelect(['last_message' => LiaisonChat::select('message')
                ->whereColumn('student_id', 'students.id')
                ->latest()
                ->limit(1)
            ])
            // Mengambil waktu pesan terakhir untuk sorting
            ->addSelect(['last_message_time' => LiaisonChat::select('created_at')
                ->whereColumn('student_id', 'students.id')
                ->latest()
                ->limit(1)
            ])
            ->orderByDesc('last_message_time') // Urutkan siswa yang chat paling baru di atas
            ->orderBy('name')
            ->paginate(20);

        return response()->json($students);
    }

    public function getChatMessages($studentId)
    {
        // Ambil semua chat history
        $messages = LiaisonChat::where('student_id', $studentId)->oldest()->get();

        // Tandai pesan dari Ortu sebagai 'sudah dibaca' (is_read = true)
        LiaisonChat::where('student_id', $studentId)
            ->where('sender_type', '!=', 'teacher')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json($messages);
    }

    public function sendChatMessage(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'message' => 'required|string',
        ]);

        $chat = LiaisonChat::create([
            'student_id' => $request->student_id,
            'teacher_id' => Auth::id(), // Pastikan Auth Teacher aktif
            'message' => $request->message,
            'sender_type' => 'teacher',
            'is_read' => false,
        ]);

        return response()->json($chat);
    }

    // =========================================================================
    //  BAGIAN 3: FITUR CHAT (PESAN ORANG TUA - SISI SISWA)
    //  (Ditambahkan Baru)
    // =========================================================================

    public function getStudentChatMessages()
    {
        $studentId = Auth::guard('student')->id();
        
        // Ambil semua chat milik siswa ini
        $messages = LiaisonChat::where('student_id', $studentId)
            ->with('teacher:id,name') // Ambil nama guru jika ada
            ->oldest()
            ->get();

        // Tandai pesan dari guru sebagai sudah dibaca oleh siswa/ortu
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

        // Simpan pesan
        // teacher_id dikosongkan (null) karena pesan ditujukan ke sekolah/wali kelas umum
        $chat = LiaisonChat::create([
            'student_id' => $studentId,
            'teacher_id' => null, 
            'message' => $request->message,
            'sender_type' => 'parent', // Sesuai enum di database untuk Sisi Siswa/Ortu
            'is_read' => false,
        ]);

        return response()->json($chat);
    }

    // =========================================================================
    //  HELPER FUNCTIONS
    // =========================================================================

    /**
     * Helper: Mendeteksi nama kolom Foreign Key kelas di tabel students.
     * Menggunakan Schema::hasColumn agar 100% akurat sesuai struktur DB.
     */
    private function detectClassColumn()
    {
        // Daftar prioritas nama kolom yang mungkin dipakai di database Anda
        $candidates = [
            'school_class_id', // Standar Laravel
            'class_id',        // Umum
            'classroom_id',    // Variasi
            'rombel_id',       // Istilah Dapodik/Indonesia
            'grade_id',        // Variasi lain
            'group_id',
            'kelas_id'         // Tambahan untuk Bahasa Indonesia
        ];

        // Cek langsung ke struktur tabel database
        foreach ($candidates as $col) {
            if (Schema::hasColumn('students', $col)) {
                return $col;
            }
        }
        
        return 'school_class_id'; // Default Fallback
    }
}