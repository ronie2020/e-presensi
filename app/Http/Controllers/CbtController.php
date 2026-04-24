<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\CbtExam;
use App\Models\CbtQuestion;
use App\Models\Student;
use App\Models\CbtEvent; 
use Illuminate\Support\Facades\Storage; 
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\QuestionsImport;
use App\Exports\CbtScoreExport; 
use App\Exports\QuestionTemplateExport; 
use App\Models\LmsAssignment;
use App\Models\LmsGrade;       
use App\Models\LmsSubmission;  
use App\Models\Subject;
use App\Models\SchoolClass;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Auth; 

class CbtController extends Controller
{   

     // --- 1. FITUR QUICK TOGGLE STATUS ---
    public function toggleStatus(Request $request, $id)
    {
        $exam = CbtExam::findOrFail($id);
        $exam->update([
            'is_active' => !$exam->is_active
        ]);

        return response()->json([
            'success' => true,
            'is_active' => $exam->is_active,
            'message' => $exam->is_active ? 'Ujian diaktifkan!' : 'Ujian dinonaktifkan!'
        ]);
    }

    // --- 5. FITUR DUPLIKASI UJIAN ---
    public function cloneExam($id)
    {
        $exam = CbtExam::with('questions')->findOrFail($id);

        DB::beginTransaction();
        try {
            // Duplikasi data ujian utama
            $newExam = $exam->replicate();
            $newExam->title = $exam->title . ' (Salinan)';
            $newExam->token = strtoupper(Str::random(5)); // Token baru
            $newExam->is_active = false; // Matikan default agar aman
            $newExam->save();

            foreach ($exam->questions as $q) {
                $newQ = $q->replicate();
                $newQ->cbt_exam_id = $newExam->id;
                
                if ($q->question_image && Storage::exists('public/' . $q->question_image)) {
                    $newPath = 'soal/copy_' . time() . '_' . basename($q->question_image);
                    Storage::copy('public/' . $q->question_image, 'public/' . $newPath);
                    $newQ->question_image = $newPath;
                }
                
                $opts = is_string($q->options) ? json_decode($q->options, true) : ($q->options ?? []);
                $newOpts = $opts;
                foreach(['A', 'B', 'C', 'D', 'E'] as $opt) {
                    if(isset($opts["image_$opt"]) && Storage::exists('public/' . $opts["image_$opt"])) {
                        $newOptPath = 'soal/copy_' . time() . '_' . basename($opts["image_$opt"]);
                        Storage::copy('public/' . $opts["image_$opt"], 'public/' . $newOptPath);
                        $newOpts["image_$opt"] = $newOptPath;
                    }
                }
                $newQ->options = $newOpts;
                $newQ->save();
            }

            DB::commit();
            return redirect()->route('cbt.index')->with('success', 'Jadwal ujian beserta soal berhasil diduplikasi!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menduplikasi jadwal: ' . $e->getMessage());
        }
    }

     public function create(Request $request)
    {
        $subjects = Subject::orderBy('name')->get();
        $events = CbtEvent::orderBy('created_at', 'desc')->get(); // Ambil data Kegiatan
        $selectedEventId = $request->event_id; // Agar pre-selected jika dari halaman showEvent

        return view('cbt.create', compact('subjects', 'events', 'selectedEventId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cbt_event_id' => 'required|exists:cbt_events,id', // Wajib pilih kegiatan
            'title' => 'required|string|max:255',
            'exam_type' => 'required|in:cbt,google_form',
            'subject_name' => 'required|string',
        ]);

        $data = [
            'cbt_event_id' => $request->cbt_event_id,
            'title' => $request->title,
            'exam_type' => $request->exam_type,
            'google_form_url' => $request->exam_type === 'google_form' ? $request->google_form_url : null,
            'subject_name' => $request->subject_name,
            'class_level' => $request->class_level,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'duration_minutes' => $request->duration_minutes,
            'question_limit' => $request->exam_type === 'google_form' ? 0 : ($request->question_limit ?? 0),
            'passing_grade' => $request->exam_type === 'google_form' ? 0 : ($request->passing_grade ?? 0),
            'token' => $request->filled('token') ? strtoupper($request->token) : strtoupper(Str::random(5)),
            'is_active' => $request->has('is_active'),
            'randomize_questions' => $request->exam_type === 'google_form' ? false : $request->has('randomize_questions'),
            'randomize_options' => $request->exam_type === 'google_form' ? false : $request->has('randomize_options'),
        ];

        CbtExam::create($data);

        // Kembali ke halaman detail kegiatan agar langsung terlihat ujian yang baru dibuat
        return redirect()->route('cbt.events.show', $request->cbt_event_id)->with('success', 'Jadwal ujian berhasil dibuat!');
    }

    public function edit($id)
    {
        $exam = CbtExam::findOrFail($id);
        $subjects = Subject::orderBy('name')->get(); 
        $events = CbtEvent::orderBy('created_at', 'desc')->get(); // Ambil data Kegiatan
        
        return view('cbt.edit', compact('exam', 'subjects', 'events'));
    }

    public function update(Request $request, $id)
    {
        $exam = CbtExam::findOrFail($id);

        $request->validate([
            'cbt_event_id' => 'required|exists:cbt_events,id', // Validasi Event
            'title' => 'required|string|max:255',
            'exam_type' => 'required|in:cbt,google_form',
            'subject_name' => 'required|string',
        ]);
       
        $data = [
            'cbt_event_id' => $request->cbt_event_id,
            'title' => $request->title,
            'exam_type' => $request->exam_type,
            'google_form_url' => $request->exam_type === 'google_form' ? $request->google_form_url : null,
            'subject_name' => $request->subject_name,
            'class_level' => $request->class_level,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'duration_minutes' => $request->duration_minutes,
            'question_limit' => $request->exam_type === 'google_form' ? 0 : ($request->question_limit ?? 0),
            'passing_grade' => $request->exam_type === 'google_form' ? 0 : ($request->passing_grade ?? 0),
            'is_active' => $request->has('is_active'),
            'randomize_questions' => $request->exam_type === 'google_form' ? false : $request->has('randomize_questions'),
            'randomize_options' => $request->exam_type === 'google_form' ? false : $request->has('randomize_options'),
        ];

        if ($request->filled('token')) {
            $data['token'] = strtoupper($request->token);
        }

        $exam->update($data);

        return redirect()->route('cbt.events.show', $request->cbt_event_id)->with('success', 'Jadwal ujian berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $exam = CbtExam::with('questions')->findOrFail($id);
        $eventId = $exam->cbt_event_id; // Simpan ID event untuk diredirect

        foreach ($exam->questions as $question) {
            if ($question->question_image && Storage::exists('public/' . $question->question_image)) {
                Storage::delete('public/' . $question->question_image);
            }
            
            $opts = is_string($question->options) ? json_decode($question->options, true) : ($question->options ?? []);
            foreach(['A', 'B', 'C', 'D', 'E'] as $opt) {
                if(isset($opts["image_$opt"]) && Storage::exists('public/' . $opts["image_$opt"])) {
                    Storage::delete('public/' . $opts["image_$opt"]);
                }
            }
            if(isset($opts['pairs'])) {
                foreach($opts['pairs'] as $pair) {
                    if(isset($pair['left_image']) && Storage::exists('public/' . $pair['left_image'])) {
                        Storage::delete('public/' . $pair['left_image']);
                    }
                    if(isset($pair['right_image']) && Storage::exists('public/' . $pair['right_image'])) {
                        Storage::delete('public/' . $pair['right_image']);
                    }
                }
            }
        }
        
       $exam->delete();

        return redirect()->route('cbt.events.show', $eventId)->with('success', 'Data ujian berhasil dihapus.');
    }
     
    public function refreshToken($id)
    {
        $exam = CbtExam::findOrFail($id);
        $newToken = strtoupper(Str::random(5));
        $exam->update(['token' => $newToken]);
        return back()->with('success', 'Token ujian berhasil diperbarui: ' . $newToken);
    }

    public function download_seb($id)
    {
        $exam = CbtExam::findOrFail($id);
        $startUrl = route('seb.login'); 
        $quitPassword = '12345'; 
        $sebConfig = '...'; 
        $fileName = Str::slug($exam->title) . '.seb';
        return response()->streamDownload(function () use ($sebConfig) { echo $sebConfig; }, $fileName, ['Content-Type' => 'application/seb']);
    }

    public function cardIndex()
    {
        $classes = SchoolClass::orderBy('name')->get();
        return view('cbt.cards.index', compact('classes'));
    }

    public function printCards(Request $request)
    {
        $query = Student::with('schoolClass')->orderBy('name');
        if ($request->mode == 'class') {
            if ($request->has('class_id')) {
                $query->where('class_id', $request->class_id);
            }
        } 
        elseif ($request->mode == 'level') {
            if ($request->has('level') && $request->level != 'all') {
                $query->whereHas('schoolClass', function($q) use ($request) {
                    $q->where('name', 'like', $request->level . '%');
                });
            }
        }
        $students = $query->get();
        if ($students->isEmpty()) {
            return response("<script>alert('Tidak ada siswa ditemukan pada kriteria tersebut.'); window.close();</script>");
        }
        foreach($students as $student) {
            $student->login_url = route('student.login', ['username' => $student->student_id]);
        }
        return view('cbt.cards.print', compact('students'));
    }
    
     // =========================================================================
    // FITUR BARU: PREVIEW & EXPORT WORD
    // =========================================================================

    /**
     * Mode Pratinjau (Preview) seperti tampilan siswa
     */
    public function preview($id)
    {
        $exam = CbtExam::with('questions')->findOrFail($id);
        return view('cbt.preview', compact('exam'));
    }

    /**
     * Export ke format Microsoft Word (.doc)
     */
    public function exportWord($id)
    {
        $exam = CbtExam::with('questions')->findOrFail($id);
        $fileName = 'Soal_Ujian_' . Str::slug($exam->title) . '_' . date('Ymd') . '.doc';

        $headers = [
            "Content-type" => "application/vnd.ms-word",
            "Content-Disposition" => "attachment;Filename={$fileName}",
            "Pragma" => "no-cache",
            "Expires" => "0"
        ];

        return response()->view('cbt.export_word', compact('exam'))->withHeaders($headers);
    }

    // =========================================================================
    // FITUR BARU: CETAK ADMINISTRASI UJIAN
    // =========================================================================

    /**
     * Cetak Daftar Hadir Peserta Ujian
     */
    public function attendanceList($id)
    {
        $exam = CbtExam::findOrFail($id);
        
        // Ambil data siswa berdasarkan kelas target ujian
        $students = Student::with('schoolClass')
            ->whereHas('schoolClass', function($query) use ($exam) {               
                $query->where('name', 'like', $exam->class_level . '%');
            })
             ->get()
            ->sortBy(function($student) {
                // Urutkan berdasarkan Nama Kelas lalu Nama Siswa
                return ($student->schoolClass->name ?? 'ZZZ') . ' - ' . $student->name;
            })
            ->values();
        
        $sessions = DB::table('cbt_student_exams')
            ->where('cbt_exam_id', $id)
            ->pluck('student_id')
            ->toArray();

        return view('cbt.daftar_hadir', compact('exam', 'students', 'sessions'));
    }

    /**
     * Cetak Berita Acara Pelaksanaan Ujian
     */
    public function minutes($id)
    {
        $exam = CbtExam::findOrFail($id);
        
        // Hitung total peserta seharusnya
        $totalStudents = Student::whereHas('schoolClass', function($query) use ($exam) {               
                $query->where('name', 'like', $exam->class_level . '%');
            })->count();

        // Hitung jumlah yang hadir (berdasarkan data sesi ujian)
        $presentStudents = DB::table('cbt_student_exams')
            ->where('cbt_exam_id', $id)
            ->count();
            
        $absentStudents = $totalStudents - $presentStudents;

        return view('cbt.berita_acara', compact('exam', 'totalStudents', 'presentStudents', 'absentStudents'));
    }
    
}