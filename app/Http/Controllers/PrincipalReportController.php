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
        // Langsung cetak jika parameter print disertakan
        if ($request->has('print')) {
            return $this->print($request);
        }

        // Filter parameters
        $teacherId = $request->input('teacher_id');
        $subjectId = $request->input('subject_id');
        $classId = $request->input('class_id');
        $materialStatus = $request->input('material_status');

        // Resolusi bulan yang cerdas: jika tidak dipilih, cek bulan berjalan atau bulan terakhir yang memiliki data
        $month = $request->input('month');
        if (!$month) {
            $hasCurrentMonth = TeachingSession::whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()])->exists();
            if (!$hasCurrentMonth) {
                $latestSession = TeachingSession::orderBy('date', 'desc')->first();
                $month = ($latestSession && $latestSession->date) ? Carbon::parse($latestSession->date)->format('Y-m') : now()->format('Y-m');
            } else {
                $month = now()->format('Y-m');
            }
        }

        $startOfMonth = Carbon::parse($month . '-01')->startOfMonth();
        $endOfMonth = Carbon::parse($month . '-01')->endOfMonth();

        // Base Query untuk KBM (Jurnal Mengajar)
        $query = TeachingSession::with(['teacher', 'timetable.subject', 'timetable.studentClass', 'schedule.subject', 'schedule.studentClass'])
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
            $query->where(function($q) use ($teacherId) {
                $q->where('teacher_id', $teacherId)
                  ->orWhereHas('schedule', function($q2) use ($teacherId) {
                      $q2->where('teacher_id', $teacherId);
                  });
            });
        }
        if ($subjectId) {
            $query->where(function($q) use ($subjectId) {
                $q->whereHas('timetable', function ($sq) use ($subjectId) {
                    $sq->where('subject_id', $subjectId);
                })->orWhereHas('schedule', function ($sq) use ($subjectId) {
                    $sq->where('subject_id', $subjectId);
                });
            });
        }
        if ($classId) {
            $query->where(function($q) use ($classId) {
                $q->whereHas('timetable', function ($sq) use ($classId) {
                    $sq->where('class_id', $classId);
                })->orWhereHas('schedule', function ($sq) use ($classId) {
                    $sq->where('class_id', $classId);
                });
            });
        }
        if ($materialStatus) {
            $query->where('material_status', $materialStatus);
        }

        // Metrik Ringkasan dihitung dari query yang telah difilter
        $totalSessions = (clone $query)->count();
        $completedMaterials = (clone $query)->where('material_status', 'completed')->count();
        $completionRate = $totalSessions > 0 ? round(($completedMaterials / $totalSessions) * 100) : 0;

        $teachings = $query->paginate(20)->withQueryString();

        // Data untuk Dropdown Filter
        $teachers = User::where(function($uq) {
            $uq->whereHas('roles', function($q) {
                $q->whereIn('name', [
                    'Guru', 'Wali Kelas', 'Kepala Sekolah', 'Guru Mata Pelajaran', 
                    'Admin', 'Guru Piket' 
                ]);
            })->orWhere('role', 'like', '%Guru%')
              ->orWhere('role', 'like', '%Wali Kelas%');
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
     * Cetak Laporan Rekapitulasi Pembelajaran (Kepala Sekolah)
     */
    public function print(Request $request)
    {
        $teacherId = $request->input('teacher_id');
        $subjectId = $request->input('subject_id');
        $classId = $request->input('class_id');
        $materialStatus = $request->input('material_status');

        $month = $request->input('month');
        if (!$month) {
            $hasCurrentMonth = TeachingSession::whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()])->exists();
            if (!$hasCurrentMonth) {
                $latestSession = TeachingSession::orderBy('date', 'desc')->first();
                $month = ($latestSession && $latestSession->date) ? Carbon::parse($latestSession->date)->format('Y-m') : now()->format('Y-m');
            } else {
                $month = now()->format('Y-m');
            }
        }

        $startOfMonth = Carbon::parse($month . '-01')->startOfMonth();
        $endOfMonth = Carbon::parse($month . '-01')->endOfMonth();

        $query = TeachingSession::with(['teacher', 'timetable.subject', 'timetable.studentClass', 'schedule.subject', 'schedule.studentClass'])
            ->withCount([
                'attendances as hadir_count' => function ($q) { $q->whereIn('status', ['present', 'Hadir']); },
                'attendances as late_count' => function ($q) { $q->whereIn('status', ['late', 'Terlambat']); },
                'attendances as alpha_count' => function ($q) { $q->whereIn('status', ['alpha', 'Alfa', 'Alpha']); },
                'attendances as sick_count' => function ($q) { $q->whereIn('status', ['sick', 'Sakit']); },
                'attendances as permit_count' => function ($q) { $q->whereIn('status', ['permit', 'Izin']); }
            ])
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->orderBy('date', 'asc')
            ->orderBy('started_at', 'asc');

        if ($teacherId) {
            $query->where(function($q) use ($teacherId) {
                $q->where('teacher_id', $teacherId)
                  ->orWhereHas('schedule', function($q2) use ($teacherId) {
                      $q2->where('teacher_id', $teacherId);
                  });
            });
        }
        if ($subjectId) {
            $query->where(function($q) use ($subjectId) {
                $q->whereHas('timetable', function ($sq) use ($subjectId) {
                    $sq->where('subject_id', $subjectId);
                })->orWhereHas('schedule', function ($sq) use ($subjectId) {
                    $sq->where('subject_id', $subjectId);
                });
            });
        }
        if ($classId) {
            $query->where(function($q) use ($classId) {
                $q->whereHas('timetable', function ($sq) use ($classId) {
                    $sq->where('class_id', $classId);
                })->orWhereHas('schedule', function ($sq) use ($classId) {
                    $sq->where('class_id', $classId);
                });
            });
        }
        if ($materialStatus) {
            $query->where('material_status', $materialStatus);
        }

        // Ambil semua data tanpa batasan pagination untuk cetak
        $teachings = $query->get();

        $totalSessions = $teachings->count();
        $completedMaterials = $teachings->where('material_status', 'completed')->count();
        $completionRate = $totalSessions > 0 ? round(($completedMaterials / $totalSessions) * 100) : 0;

        $selectedTeacher = $teacherId ? User::find($teacherId) : null;
        $selectedSubject = $subjectId ? Subject::find($subjectId) : null;
        $selectedClass = $classId ? SchoolClass::find($classId) : null;

        $totalHadir = $teachings->sum(fn($s) => ($s->hadir_count ?? 0) + ($s->late_count ?? 0));
        $totalSakit = $teachings->sum(fn($s) => $s->sick_count ?? 0);
        $totalIzin = $teachings->sum(fn($s) => $s->permit_count ?? 0);
        $totalAlpha = $teachings->sum(fn($s) => $s->alpha_count ?? 0);

        return view('reports.principal.print', compact(
            'teachings', 'month', 'totalSessions', 'completedMaterials', 'completionRate',
            'selectedTeacher', 'selectedSubject', 'selectedClass', 'materialStatus',
            'totalHadir', 'totalSakit', 'totalIzin', 'totalAlpha'
        ));
    }

    /**
     * Lini Masa (Timeline) per Guru
     */
    public function showTeacher(Request $request, $id)
    {
        if ($request->has('print')) {
            return $this->printTeacher($request, $id);
        }

        $teacher = User::findOrFail($id);
        $month = $request->input('month');
        if (!$month) {
            $hasCurrentMonth = TeachingSession::where('teacher_id', $id)->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()])->exists();
            if (!$hasCurrentMonth) {
                $latestSession = TeachingSession::where('teacher_id', $id)->orderBy('date', 'desc')->first();
                $month = ($latestSession && $latestSession->date) ? Carbon::parse($latestSession->date)->format('Y-m') : now()->format('Y-m');
            } else {
                $month = now()->format('Y-m');
            }
        }
        
        $startOfMonth = Carbon::parse($month . '-01')->startOfMonth();
        $endOfMonth = Carbon::parse($month . '-01')->endOfMonth();

        $teachings = TeachingSession::with(['timetable.subject', 'timetable.studentClass', 'schedule.subject', 'schedule.studentClass', 'attendances'])
            ->withCount([
                'attendances as hadir_count' => function ($q) { $q->whereIn('status', ['present', 'Hadir']); },
                'attendances as late_count' => function ($q) { $q->whereIn('status', ['late', 'Terlambat']); },
                'attendances as alpha_count' => function ($q) { $q->whereIn('status', ['alpha', 'Alfa', 'Alpha']); },
                'attendances as sick_count' => function ($q) { $q->whereIn('status', ['sick', 'Sakit']); },
                'attendances as permit_count' => function ($q) { $q->whereIn('status', ['permit', 'Izin']); }
            ])
            ->where(function($q) use ($id) {
                $q->where('teacher_id', $id)
                  ->orWhereHas('schedule', function($q2) use ($id) {
                      $q2->where('teacher_id', $id);
                  });
            })
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->orderBy('date', 'desc')
            ->orderBy('started_at', 'desc')
            ->get();

        // Mengelompokkan berdasarkan tanggal
        $groupedTeachings = $teachings->groupBy(function ($item) {
            return $item->date ? $item->date->format('Y-m-d') : '';
        });

        // Metrik Individu
        $totalSessions = $teachings->count();
        $completedMaterials = $teachings->where('material_status', 'completed')->count();
        $completionRate = $totalSessions > 0 ? round(($completedMaterials / $totalSessions) * 100) : 0;

        return view('reports.principal.teacher_timeline', compact(
            'teacher', 'groupedTeachings', 'totalSessions', 'completionRate', 'month'
        ));
    }

    /**
     * Cetak Lini Masa Guru (PDF / Print)
     */
    public function printTeacher(Request $request, $id)
    {
        $teacher = User::findOrFail($id);
        $month = $request->input('month');
        if (!$month) {
            $hasCurrentMonth = TeachingSession::where('teacher_id', $id)->whereBetween('date', [now()->startOfMonth(), now()->endOfMonth()])->exists();
            if (!$hasCurrentMonth) {
                $latestSession = TeachingSession::where('teacher_id', $id)->orderBy('date', 'desc')->first();
                $month = ($latestSession && $latestSession->date) ? Carbon::parse($latestSession->date)->format('Y-m') : now()->format('Y-m');
            } else {
                $month = now()->format('Y-m');
            }
        }
        
        $startOfMonth = Carbon::parse($month . '-01')->startOfMonth();
        $endOfMonth = Carbon::parse($month . '-01')->endOfMonth();

        $teachings = TeachingSession::with(['timetable.subject', 'timetable.studentClass', 'schedule.subject', 'schedule.studentClass', 'attendances'])
            ->withCount([
                'attendances as hadir_count' => function ($q) { $q->whereIn('status', ['present', 'Hadir']); },
                'attendances as late_count' => function ($q) { $q->whereIn('status', ['late', 'Terlambat']); },
                'attendances as alpha_count' => function ($q) { $q->whereIn('status', ['alpha', 'Alfa', 'Alpha']); },
                'attendances as sick_count' => function ($q) { $q->whereIn('status', ['sick', 'Sakit']); },
                'attendances as permit_count' => function ($q) { $q->whereIn('status', ['permit', 'Izin']); }
            ])
            ->where(function($q) use ($id) {
                $q->where('teacher_id', $id)
                  ->orWhereHas('schedule', function($q2) use ($id) {
                      $q2->where('teacher_id', $id);
                  });
            })
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->orderBy('date', 'asc')
            ->orderBy('started_at', 'asc')
            ->get();

        $groupedTeachings = $teachings->groupBy(function ($item) {
            return $item->date ? $item->date->format('Y-m-d') : '';
        });

        $totalSessions = $teachings->count();
        $completedMaterials = $teachings->where('material_status', 'completed')->count();
        $completionRate = $totalSessions > 0 ? round(($completedMaterials / $totalSessions) * 100) : 0;

        $totalHadir = $teachings->sum(fn($s) => ($s->hadir_count ?? 0) + ($s->late_count ?? 0));
        $totalSakit = $teachings->sum(fn($s) => $s->sick_count ?? 0);
        $totalIzin = $teachings->sum(fn($s) => $s->permit_count ?? 0);
        $totalAlpha = $teachings->sum(fn($s) => $s->alpha_count ?? 0);

        return view('reports.principal.print_teacher', compact(
            'teacher', 'teachings', 'groupedTeachings', 'totalSessions', 'completedMaterials', 
            'completionRate', 'month', 'totalHadir', 'totalSakit', 'totalIzin', 'totalAlpha'
        ));
    }
}
