<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TeachingSession;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Subject;
use Carbon\Carbon;

class PrincipalReportController extends Controller
{
    /**
     * Dashboard Rekapitulasi Pembelajaran (Kepala Sekolah)
     */
    public function index(Request $request)
    {
        // Filter parameters
        $teacherId = $request->input('teacher_id');
        $subjectId = $request->input('subject_id');
        $classId = $request->input('class_id');
        $month = $request->input('month', now()->format('Y-m'));
        $materialStatus = $request->input('material_status');

        $startOfMonth = Carbon::parse($month . '-01')->startOfMonth();
        $endOfMonth = Carbon::parse($month . '-01')->endOfMonth();

        // Base Query untuk KBM (Jurnal Mengajar)
        $query = TeachingSession::with(['teacher', 'timetable.subject', 'timetable.studentClass'])
            ->withCount([
                'attendances as hadir_count' => function ($q) { $q->whereIn('status', ['present', 'Hadir']); },
                'attendances as late_count' => function ($q) { $q->whereIn('status', ['late', 'Terlambat']); },
                'attendances as alpha_count' => function ($q) { $q->whereIn('status', ['alpha', 'Alfa', 'Alpha']); },
                'attendances as sick_count' => function ($q) { $q->whereIn('status', ['sick', 'Sakit']); },
                'attendances as permit_count' => function ($q) { $q->whereIn('status', ['permit', 'Izin']); }
            ])
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->orderBy('date', 'desc')
            ->orderBy('started_at', 'desc');

        if ($teacherId) {
            $query->where('teacher_id', $teacherId);
        }
        if ($subjectId) {
            $query->whereHas('timetable', function ($q) use ($subjectId) {
                $q->where('subject_id', $subjectId);
            });
        }
        if ($classId) {
            $query->whereHas('timetable', function ($q) use ($classId) {
                $q->where('class_id', $classId);
            });
        }
        if ($materialStatus) {
            $query->where('material_status', $materialStatus);
        }

        $teachings = $query->paginate(20)->withQueryString();

        // Menghitung Metrik Ringkasan untuk bulan ini
        $totalSessions = TeachingSession::whereBetween('date', [$startOfMonth, $endOfMonth])->count();
        $completedMaterials = TeachingSession::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->where('material_status', 'completed')
            ->count();
            
        $completionRate = $totalSessions > 0 ? round(($completedMaterials / $totalSessions) * 100) : 0;

        // Data untuk Dropdown Filter
        $teachers = User::whereHas('roles', function($q) {
            $q->whereIn('name', [
                'Guru', 'Wali Kelas', 'Kepala Sekolah', 'Guru Mata Pelajaran', 
                'Admin', 'Guru Piket' 
            ]);
        })->orderBy('name')->get();

        if($teachers->isEmpty()) {
            $teachers = User::orderBy('name')->get();
        }

        $subjects = Subject::orderBy('name')->get();
        $classes = SchoolClass::orderBy('name')->get();

        return view('reports.principal.index', compact(
            'teachings', 'teachers', 'subjects', 'classes', 'totalSessions', 'completionRate', 'month'
        ));
    }

    /**
     * Lini Masa (Timeline) per Guru
     */
    public function showTeacher(Request $request, $id)
    {
        $teacher = User::findOrFail($id);
        $month = $request->input('month', now()->format('Y-m'));
        
        $startOfMonth = Carbon::parse($month . '-01')->startOfMonth();
        $endOfMonth = Carbon::parse($month . '-01')->endOfMonth();

        $teachings = TeachingSession::with(['timetable.subject', 'timetable.studentClass', 'attendances'])
            ->where('teacher_id', $id)
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->orderBy('date', 'desc')
            ->orderBy('started_at', 'desc')
            ->get();

        // Mengelompokkan berdasarkan tanggal
        $groupedTeachings = $teachings->groupBy(function ($item) {
            return $item->date->format('Y-m-d');
        });

        // Metrik Individu
        $totalSessions = $teachings->count();
        $completedMaterials = $teachings->where('material_status', 'completed')->count();
        $completionRate = $totalSessions > 0 ? round(($completedMaterials / $totalSessions) * 100) : 0;

        return view('reports.principal.teacher_timeline', compact(
            'teacher', 'groupedTeachings', 'totalSessions', 'completionRate', 'month'
        ));
    }
}
