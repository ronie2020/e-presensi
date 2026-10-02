<?php

namespace App\Traits;

use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\TeachingLoad;
use App\Models\Timetable;
use App\Models\LmsMaterial;
use App\Models\LmsAssignment;
use Illuminate\Support\Facades\Auth;

trait TeacherScopeTrait
{
    /**
     * Cek apakah user adalah admin / manajemen sekolah
     */
    protected function isUserAdmin($user = null): bool
    {
        $user = $user ?? Auth::user();
        if (!$user) {
            return false;
        }

        $role = strtolower($user->role ?? '');
        if (in_array($role, ['admin', 'superadmin', 'super-admin', 'administrator', 'kepala sekolah', 'kurikulum'])) {
            return true;
        }

        if (method_exists($user, 'hasRole')) {
            return $user->hasRole(['admin', 'superadmin', 'super-admin', 'Administrator', 'Admin', 'Superadmin', 'Kepala Sekolah', 'Kurikulum']);
        }

        return false;
    }

    /**
     * Dapatkan koleksi Mata Pelajaran yang diampu guru (atau seluruh mapel jika admin)
     */
    protected function getScopedSubjects($user = null)
    {
        $user = $user ?? Auth::user();

        if ($this->isUserAdmin($user)) {
            return Subject::orderBy('name')->get();
        }

        $teacherId = $user->id;

        // Prioritas 1: Beban Mengajar (TeachingLoad) & Jadwal Pelajaran (Timetable)
        $subjectIds = TeachingLoad::where('teacher_id', $teacherId)->pluck('subject_id')
            ->concat(Timetable::where('teacher_id', $teacherId)->pluck('subject_id'))
            ->unique()
            ->filter();

        // Fallback 1: Cocokkan dari field position di profil guru (misal: 'Bahasa Inggris', 'IPA')
        if ($subjectIds->isEmpty() && !empty($user->position)) {
            $matchedSubject = Subject::where(function($q) use ($user) {
                $q->where('name', 'like', '%' . trim($user->position) . '%')
                  ->orWhereRaw('? LIKE CONCAT("%", name, "%")', [trim($user->position)]);
            })->first();

            if ($matchedSubject) {
                $subjectIds = collect([$matchedSubject->id]);
            }
        }

        // Fallback 2: Riwayat materi / tugas
        if ($subjectIds->isEmpty()) {
            $subjectIds = LmsMaterial::where('teacher_id', $teacherId)->pluck('subject_id')
                ->concat(LmsAssignment::where('teacher_id', $teacherId)->pluck('subject_id'))
                ->unique()
                ->filter();
        }

        if ($subjectIds->isNotEmpty()) {
            return Subject::whereIn('id', $subjectIds)->orderBy('name')->get();
        }

        return collect();
    }

    /**
     * Dapatkan koleksi Kelas yang diajar guru (atau seluruh kelas jika admin)
     */
    protected function getScopedClasses($user = null)
    {
        $user = $user ?? Auth::user();

        if ($this->isUserAdmin($user)) {
            return SchoolClass::orderBy('name')->get();
        }

        $teacherId = $user->id;

        // Prioritas 1: Beban Mengajar (TeachingLoad) & Jadwal Pelajaran (Timetable)
        $classIds = TeachingLoad::where('teacher_id', $teacherId)->pluck('class_id')
            ->concat(Timetable::where('teacher_id', $teacherId)->pluck('class_id'))
            ->unique()
            ->filter();

        // Fallback: Riwayat materi / tugas
        if ($classIds->isEmpty()) {
            $classIds = LmsMaterial::where('teacher_id', $teacherId)->pluck('class_id')
                ->concat(LmsAssignment::where('teacher_id', $teacherId)->pluck('class_id'))
                ->unique()
                ->filter();
        }

        if ($classIds->isNotEmpty()) {
            return SchoolClass::whereIn('id', $classIds)->orderBy('name')->get();
        }

        return collect();
    }
}
