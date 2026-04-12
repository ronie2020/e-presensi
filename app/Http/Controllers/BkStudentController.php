<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\BkCategory;
use App\Models\BkSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class BkStudentController extends Controller 
{
    // Halaman Riwayat & Daftar Konseling
    public function index()
    {
        $studentId = Auth::guard('student')->id();
        $histories = BkSession::where('student_id', $studentId)
            ->with(['category', 'teacher'])
            ->latest()
            ->paginate(10);

        return view('students.bk.index', compact('histories'));
    }

    // Form Pengajuan Konseling Baru
    public function create()
    {
        $categories = BkCategory::all();       
        return view('students.bk.create', compact('categories'));
    }

    // Proses Simpan Pengajuan
    public function store(Request $request)
    {
        $request->validate([
            'bk_category_id' => 'required|exists:bk_categories,id',
            'initial_message' => 'required|string|min:10',
            'method' => 'required|in:offline,online',
        ]);

        BkSession::create([
            'student_id' => Auth::guard('student')->id(),
            'bk_category_id' => $request->bk_category_id,
            'initial_message' => $request->initial_message,
            'method' => $request->method,
            'status' => 'pending', 
        ]);
       
        return redirect()->route('student.bk.index')
            ->with('success', 'Pengajuan konseling berhasil dikirim. Menunggu respon Guru BK.');
    }

    // Detail Tiket Konseling & Ruang Chat
    public function show($id)
    {
        $session = BkSession::where('student_id', Auth::guard('student')->id())
            ->with(['record', 'teacher', 'category'])
            ->findOrFail($id);            
      
        // Mengarahkan ke view show khusus siswa
        return view('students.bk.show', compact('session'));
    }

    // =========================================================================
    // API CHAT UNTUK SISWA (Telah Diperbaiki & Kebal Error)
    // =========================================================================

    public function getMessages($id)
    {
        try {
            $studentId = Auth::guard('student')->id();
            $session = BkSession::where('id', $id)->where('student_id', $studentId)->firstOrFail();

            if (!class_exists('\App\Models\BkChat')) {
                return response()->json(DB::table('bk_chats')->where('bk_session_id', $id)->orderBy('created_at', 'asc')->get());
            }

            $messages = \App\Models\BkChat::where('bk_session_id', $id)->oldest()->get();
            return response()->json($messages);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function sendMessage(Request $request, $id)
    {
        try {
            $request->validate(['message' => 'required|string']);
            
            $studentId = Auth::guard('student')->id();
            $session = BkSession::where('id', $id)->where('student_id', $studentId)->firstOrFail();

            // Mencegah error Mass Assignment dengan menggunakan instansiasi langsung
            if (class_exists('\App\Models\BkChat')) {
                $chat = new \App\Models\BkChat();
                $chat->bk_session_id = $id;
                $chat->message = $request->message;
                $chat->sender_type = 'student';
                
                // Deteksi kolom agar tabel tidak error
                $columns = Schema::getColumnListing($chat->getTable());
                if (in_array('student_id', $columns)) {
                    $chat->student_id = $studentId;
                }
                $chat->save();
                
                return response()->json($chat);
            } else {
                // Fallback aman menggunakan DB Raw jika Model belum sempurna
                DB::table('bk_chats')->insert([
                    'bk_session_id' => $id,
                    'message' => $request->message,
                    'sender_type' => 'student',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                return response()->json(['status' => 'success']);
            }
        } catch (\Exception $e) {
            // Berikan balasan error yang jelas ke frontend
            return response()->json(['message' => 'Error Server: ' . $e->getMessage()], 500);
        }
    }
}