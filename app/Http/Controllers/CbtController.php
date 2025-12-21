<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CbtExam;
use App\Models\CbtQuestion;
use App\Models\Student;
use Illuminate\Support\Facades\Storage; // Tambahkan ini untuk handle hapus gambar
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\QuestionsImport;

class CbtController extends Controller
{
    /**
     * Menampilkan Dashboard CBT
     */
    public function index()
    {
        $stats = [
            'active_exams' => CbtExam::where('is_active', true)->count(),
            'total_questions' => DB::table('cbt_questions')->count(),
            'students_working' => DB::table('cbt_student_exams')->where('status', 'ongoing')->count(),
            'avg_score' => DB::table('cbt_student_exams')->whereNotNull('total_score')->avg('total_score') ?? 0,
        ];

        $exams = CbtExam::latest()->take(10)->get();

        return view('cbt.index', compact('stats', 'exams'));
    }

    /**
     * Halaman Buat Jadwal Ujian
     */
    public function create()
    {
        return view('cbt.create');
    }

    /**
     * Simpan Jadwal Ujian Baru
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subject_name' => 'required|string',
            'class_level' => 'required',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'duration_minutes' => 'required|integer|min:1',
            'passing_grade' => 'required|integer|min:0|max:100',
            'token' => 'nullable|string|max:6',
        ]);

        $validated['is_active'] = $request->has('is_active');
        CbtExam::create($validated);

        return redirect()->route('cbt.index')->with('success', 'Jadwal ujian berhasil dibuat!');
    }

    /**
     * HAPUS DATA UJIAN (Jadwal, Soal, & Hasil)
     * Method ini dipanggil saat tombol hapus di dashboard ditekan.
     */
    public function destroy($id)
    {
        // Cari ujian beserta soalnya
        $exam = CbtExam::with('questions')->findOrFail($id);

        // 1. Bersihkan Gambar Fisik Soal (Agar storage tidak penuh sampah)
        foreach ($exam->questions as $question) {
            if ($question->question_image && Storage::exists('public/' . $question->question_image)) {
                Storage::delete('public/' . $question->question_image);
            }
        }
        // 2. Hapus Record Ujian
        // Database akan otomatis menghapus soal & jawaban siswa jika Foreign Key menggunakan 'onDelete cascade'.
        // Jika tidak yakin dengan setting database, kita bisa hapus manual relasinya:
        // $exam->questions()->delete(); 
        // DB::table('cbt_student_exams')->where('cbt_exam_id', $id)->delete();
        
        $exam->delete();

        return redirect()->route('cbt.index')->with('success', 'Data ujian beserta soal dan nilainya berhasil dihapus.');
    }

    /**
     * Halaman Kelola Soal
     */
    public function manageQuestions($id)
    {
        $exam = CbtExam::with('questions')->findOrFail($id);
        return view('cbt.manage_questions', compact('exam'));
    }

    /**
     * Simpan Soal Manual
     */
    public function storeQuestion(Request $request, $id)
    {
        $request->validate([
            'question_text' => 'required',
            'option_A' => 'required',
            'option_B' => 'required',
            'correct_answer' => 'required|in:A,B,C,D,E',
            'score_weight' => 'required|integer|min:1',
            'question_image' => 'nullable|image|max:2048'
        ]);

        $exam = CbtExam::findOrFail($id);

        $imagePath = null;
        if ($request->hasFile('question_image')) {
            $imagePath = $request->file('question_image')->store('soal', 'public');
        }

        // Filter opsi agar dinamis (bisa 4 atau 5 opsi)
        $options = [
            'A' => $request->option_A,
            'B' => $request->option_B,
            'C' => $request->option_C,
            'D' => $request->option_D,
            'E' => $request->option_E ?? null,
        ];

        $options = array_filter($options, fn($value) => !is_null($value) && $value !== '');

        CbtQuestion::create([
            'cbt_exam_id' => $exam->id,
            'question_text' => $request->question_text,
            'question_image' => $imagePath,
            'options' => $options, 
            'correct_answer' => $request->correct_answer,
            'score_weight' => $request->score_weight
        ]);

        return back()->with('success', 'Soal berhasil ditambahkan!');
    }

    /**
     * UPDATE SOAL (BARU DITAMBAHKAN)
     */
    public function updateQuestion(Request $request, $id)
    {
        // 1. Validasi
        $request->validate([
            'question_text' => 'required',
            'option_A' => 'required',
            'option_B' => 'required',
            'correct_answer' => 'required|in:A,B,C,D,E',
            'score_weight' => 'required|integer|min:1',
            'question_image' => 'nullable|image|max:2048'
        ]);

        // 2. Cari Soal
        $question = CbtQuestion::findOrFail($id);

        // 3. Handle Gambar Baru
        if ($request->hasFile('question_image')) {
            // Hapus gambar lama jika ada
            if ($question->question_image && Storage::exists('public/' . $question->question_image)) {
                Storage::delete('public/' . $question->question_image);
            }
            // Simpan gambar baru
            $question->question_image = $request->file('question_image')->store('soal', 'public');
        }

        // 4. Update Opsi
        $options = [
            'A' => $request->option_A,
            'B' => $request->option_B,
            'C' => $request->option_C,
            'D' => $request->option_D,
            'E' => $request->option_E ?? null,
        ];
        // Filter null values
        $options = array_filter($options, fn($value) => !is_null($value) && $value !== '');

        // 5. Simpan Data Lainnya
        $question->question_text = $request->question_text;
        $question->options = $options; // Laravel otomatis convert array ke JSON jika di model di-cast
        $question->correct_answer = $request->correct_answer;
        $question->score_weight = $request->score_weight;
        
        $question->save();

        return back()->with('success', 'Soal berhasil diperbarui!');
    }

    /**
     * Hapus Soal
     */
    public function destroyQuestion($id)
    {
        $question = CbtQuestion::findOrFail($id);
        
        // Hapus gambar fisik jika ada
        if ($question->question_image && Storage::exists('public/' . $question->question_image)) {
            Storage::delete('public/' . $question->question_image);
        }
        
        $question->delete();
        return back()->with('success', 'Soal berhasil dihapus.');
    }

    /**
     * Import Soal dari Excel
     */
    public function importQuestions(Request $request, $exam_id)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv'
        ]);

        try {
            Excel::import(new QuestionsImport($exam_id), $request->file('file'));
            return back()->with('success', 'Soal berhasil diimport dari Excel!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal import: ' . $e->getMessage());
        }
    }

    /**
     * Download Template Excel/CSV
     */
    public function downloadTemplate()
    {
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=template_soal_cbt.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['soal', 'opsi_a', 'opsi_b', 'opsi_c', 'opsi_d', 'jawaban', 'bobot'];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            // Contoh Data Dummy
            fputcsv($file, ['Siapa presiden pertama RI?', 'Soekarno', 'Suharto', 'Habibie', 'Jokowi', 'A', '2']);
            fputcsv($file, ['Ibu kota Jawa Barat adalah?', 'Bandung', 'Jakarta', 'Surabaya', 'Semarang', 'A', '2']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * IMPLEMENTASI MONITORING UJIAN (Real-time)
     */
    public function monitoring($id)
    {
        // 1. Ambil Data Ujian
        $exam = CbtExam::withCount('questions')->findOrFail($id);

        // 2. Ambil Siswa yang Seharusnya Mengikuti Ujian
        $students = Student::with('schoolClass')
            ->whereHas('schoolClass', function($query) use ($exam) {
                // Asumsi: Ada kolom 'level' di tabel school_classes atau sesuaikan dengan struktur database Anda
                // Jika pakai 'name' (misal 7A, 7B), gunakan: $query->where('name', 'like', $exam->class_level . '%');
                $query->where('name', 'like', $exam->class_level . '%');
            })
            ->orderBy('name')
            ->get();

        // 3. Ambil Progress Pengerjaan dari tabel 'cbt_student_exams'
        $sessions = DB::table('cbt_student_exams')
            ->where('cbt_exam_id', $id)
            ->get()
            ->keyBy('student_id');

        // 4. Gabungkan Data untuk View
        $monitoringData = $students->map(function($student) use ($sessions) {
            $session = $sessions->get($student->id);
            
            // Default State
            $status = 'Belum Mengerjakan';
            $badgeColor = 'slate';
            $startTime = '-';
            $score = '-';
            $isActive = false;

            if ($session) {
                $startTime = \Carbon\Carbon::parse($session->created_at)->format('H:i');
                
                if ($session->status == 'finished') {
                    $status = 'Selesai';
                    $badgeColor = 'green';
                    $score = $session->total_score ?? 0;
                } else {
                    $status = 'Sedang Mengerjakan';
                    $badgeColor = 'blue';
                    $isActive = true; // Flag untuk tombol reset
                    $score = $session->total_score ?? 0; // Nilai sementara
                }
            }

            return (object) [
                'id' => $student->id,
                'name' => $student->name,
                'class' => $student->schoolClass->name ?? '-',
                'status' => $status,
                'badge_color' => $badgeColor,
                'start_time' => $startTime,
                'score' => $score,
                'is_active' => $isActive,
            ];
        });

        // 5. Statistik Ringkas Real-time
        $stats = [
            'total_students' => $students->count(),
            'working' => $monitoringData->where('status', 'Sedang Mengerjakan')->count(),
            'finished' => $monitoringData->where('status', 'Selesai')->count(),
            'not_started' => $monitoringData->where('status', 'Belum Mengerjakan')->count(),
        ];

        return view('cbt.monitoring', compact('exam', 'monitoringData', 'stats'));
    }

    /**
     * RESET LOGIN SISWA
     */
    public function resetExam($exam_id, $student_id)
    {
        DB::table('cbt_student_exams')
            ->where('cbt_exam_id', $exam_id)
            ->where('student_id', $student_id)
            ->delete(); 

        return back()->with('success', 'Status ujian siswa berhasil di-reset. Siswa dapat login kembali.');
    }

    /**
     * DAFTAR HASIL UJIAN
     */
    public function results()
    {
        $results = DB::table('cbt_student_exams')
            ->join('students', 'cbt_student_exams.student_id', '=', 'students.id')
            ->join('cbt_exams', 'cbt_student_exams.cbt_exam_id', '=', 'cbt_exams.id')
            ->leftJoin('school_classes', 'students.school_class_id', '=', 'school_classes.id')
            ->where('cbt_student_exams.status', 'finished')
            ->select(
                'cbt_student_exams.*',
                'students.name as student_name',
                'school_classes.name as class_name',
                'cbt_exams.title as exam_title',
                'cbt_exams.subject_name'
            )
            ->orderBy('cbt_student_exams.created_at', 'desc')
            ->paginate(20);

        return view('cbt.results', compact('results'));
    }

    /**
     * Generate dan Download File Config (.seb)
     */
    public function download_seb($id)
    {
        $exam = CbtExam::findOrFail($id);

        // [PERBAIKAN] Gunakan route 'student.login' agar mengarah ke Login Siswa
        // Sebelumnya: route('login') -> mengarah ke Login Guru/Admin
        $startUrl = route('student.login'); 

        // Generate Config XML
        $sebConfig = '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
    <key>originatorVersion</key>
    <string>SEB_Win_2.4.1</string>
    <key>startURL</key>
    <string>' . $startUrl . '</string>
    <key>sendBrowserExamKey</key>
    <true/>
    <key>examKeySalt</key>
    <data>' . base64_encode(random_bytes(32)) . '</data>
    <key>allowQuit</key>
    <true/>
    <key>ignoreExitKeys</key>
    <false/>
    <key>showTaskBar</key>
    <true/>
    <key>showReloadButton</key>
    <true/>
    <key>showQuitButton</key>
    <true/>
</dict>
</plist>';

        $fileName = \Illuminate\Support\Str::slug($exam->title) . '.seb';

        return response()->streamDownload(function () use ($sebConfig) {
            echo $sebConfig;
        }, $fileName, [
            'Content-Type' => 'application/seb',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
        ]);
    }

    /**
     * [BARU] Refresh Token Manual
     */
    public function refreshToken($id)
    {
        $exam = CbtExam::findOrFail($id);
        
        // Generate Token Baru (Huruf Besar & Angka, 5 Karakter)
        // Menggunakan Str::upper(Str::random(5)) adalah cara termudah
        $newToken = strtoupper(\Illuminate\Support\Str::random(5));
        
        $exam->update([
            'token' => $newToken
        ]);

        return back()->with('success', 'Token ujian berhasil diperbarui: ' . $newToken);
    }
}