<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

// --- LOGIKA E-COUNSELING ---
use App\Models\BkSession;
use App\Models\BkCategory;
use App\Models\DisciplineRecord;
use App\Models\AttendanceSiswa;
use Carbon\Carbon;

use Spatie\Permission\Traits\HasRoles;

class Student extends Authenticatable
{
     use HasFactory, SoftDeletes, Notifiable, HasRoles;

    /**
     * DAFTAR KOLOM YANG BOLEH DIISI (MASS ASSIGNMENT)
     */
    protected $fillable = [
        // 1. IDENTITAS UTAMA
        'student_id', 
        'nis', 
        'nisn', 
        'name', 
        'nickname', 
        'class_id',
        
        // 2. BIODATA PRIBADI
        'nik', 
        'gender', 
        'pob',          
        'dob',          
        'birth_place',  
        'birth_date',   
        'religion', 
        'citizenship',
        
        // 3. DATA KELUARGA
        'birth_order', 
        'siblings_count', 
        'step_siblings_count', 
        'adoptive_siblings_count', 
        'orphan_status', 
        'daily_language',
        
        // 4. ALAMAT & KONTAK
        'address', 
        'phone', 
        'living_with', 
        'distance_to_school', 
        'transport_mode',
        
        // 5. KESEHATAN
        'blood_type', 
        'weight', 
        'height', 
        'history_disease', 
        'physical_abnormalities',
        
        // 6. DATA ORANG TUA (AYAH)
        'father_name', 
        'father_pob', 
        'father_birth_year', 
        'father_education', 
        'father_job', 
        'father_income',
        
        // 7. DATA ORANG TUA (IBU)
        'mother_name', 
        'mother_pob', 
        'mother_birth_year', 
        'mother_education', 
        'mother_job', 
        'mother_income',
        
        // 8. DATA WALI
        'guardian_name', 
        'guardian_relationship', 
        'guardian_job', 
        'guardian_phone', 
        'guardian_pob', 
        'guardian_dob', 
        'guardian_citizenship',
        'guardian_address', 
        'guardian_income',
        
        // KONTAK ORTU (PPDB Compatibility)
        'parent_phone', 
        'parent_wa_number', 
        'parent_income',
        
        // 9. DATA AKADEMIK & SEJARAH
        'school_origin', 
        'prev_diploma_no', 
        'prev_exam_date', 
        'accepted_date',
        'transfer_from_school',
        
        // 10. PRESTASI & BEASISWA
        'achievements', 
        'iq_score', 
        'scholarship_info',
        
        // 11. DATA KELULUSAN / PINDAH / DO
        'graduated_date', 
        'graduated_diploma_no', 
        'continuing_to_school', 
        'continuing_school_address',
        'leaving_date', 
        'leaving_reason', 
        'leaving_to_school', 
        'leaving_class',
        'dropout_date', 
        'dropout_reason',
        
        // 12. LAINNYA
        'rfid_id', 
        'photo_path', 
        'status', 
        'score', 
        'ramadan_points',
        'join_date', 
        'general_notes'
    ];

    /**
     * ELOQUENT CASTS (PENTING!)    
     */
    protected $casts = [
        'dob' => 'date',
        'birth_date' => 'date',
        'accepted_date' => 'date',
        'prev_exam_date' => 'date',
        'father_birth_year' => 'date',
        'mother_birth_year' => 'date',
        'guardian_dob' => 'date',
        'graduated_date' => 'date',
        'leaving_date' => 'date',
        'dropout_date' => 'date',
        'join_date' => 'date',
    ];

    /**
     * Password untuk login siswa (default menggunakan NIS/Student ID)
     */
    public function getAuthPassword()
    {
        return $this->student_id; 
    }

    /**
     * Relasi ke Kelas
     */
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    /**
     * Relasi ke Data Kelulusan (Manajemen SKL & Nilai)
     */
    public function graduation(): HasOne
    {
        return $this->hasOne(Graduation::class, 'student_id');
    }

    /**
     * Relasi ke Absensi
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(AttendanceSiswa::class, 'student_id');
    }

    /**
     * Relasi ke Catatan Disiplin
     */
    public function disciplineRecords(): HasMany
    {
        return $this->hasMany(DisciplineRecord::class, 'student_id');
    }

    /**
     * Relasi ke Data Alumni (Tracer Study)
     */
    public function alumniProfile(): HasOne
    {
        return $this->hasOne(AlumniProfile::class, 'student_id');
    }

    /**
     * Relasi ke Prestasi (Achievement)
     */
    public function achievements(): HasMany
    {
        return $this->hasMany(Achievement::class, 'student_id');
    }

     /**
     * Relasi ke Chat Liaison (Pesan Ortu)
     */
    public function liaisonChats(): HasMany
    {
        return $this->hasMany(LiaisonChat::class, 'student_id');
    }

    /**
     * Relasi ke Jurnal Kebiasaan (Habits)
     */
    public function habits(): HasMany
    {
        return $this->hasMany(StudentHabit::class, 'student_id');
    }

     /**
     * Jurnal Ramadhan.
     */
    public function ramadanLogs(): HasMany
    {
        return $this->hasMany(RamadanLog::class, 'student_id');
    }

    /**
     * Relasi ke Data Peminjaman Buku (Borrowing)     
     */
    public function borrowings(): HasMany
    {
        return $this->hasMany(Borrowing::class, 'student_id');
    }

 /**
     * Relasi ke Riwayat Akademik / Kenaikan Kelas
     */
    public function classHistories(): HasMany
    {       
        return $this->hasMany(StudentClassHistory::class, 'student_id')->latest('created_at');
    }

    // ====================================================================
    // TAMBAHAN BARU UNTUK BUKU INDUK (RAPORT)
    // ====================================================================

    protected $loadedGrades = null;

    /**
     * Relasi ke Data Raport (GradeRecord)
     */
    public function gradeRecords(): HasMany
    {
        return $this->hasMany(GradeRecord::class, 'student_id');
    }

    /**
     * Helper untuk mengambil Nilai berdasarkan Nama Mapel, Kelas, dan Semester
     */
    public function getScore($subjectName, $kelas, $semester)
    {
        // Load data raport HANYA SEKALI untuk menghemat query database
        if ($this->loadedGrades === null) {
            $this->loadedGrades = GradeRecord::with(['items.subject'])
                ->where('student_id', $this->id)
                ->get();
        }

        // Cari record yang cocok dengan Kelas dan Semester
        $record = $this->loadedGrades->first(function ($r) use ($kelas, $semester) {
            
            // NORMALISASI SEMESTER: Menyamakan 'Ganjil' = 1, 'Genap' = 2
            $dbSemester = strtolower(trim($r->semester));
            $isMatchSemester = false;
            
            if ($semester == 1 && in_array($dbSemester, ['1', 'ganjil'])) {
                $isMatchSemester = true;
            } elseif ($semester == 2 && in_array($dbSemester, ['2', 'genap'])) {
                $isMatchSemester = true;
            }

            if (!$isMatchSemester) return false;
            
            // Deteksi nama kelas (misal: "7A", "VII-A", "Kelas 8", "IX")
            $cn = strtoupper($r->class_name);
            $isKelas7 = str_contains($cn, '7') || (str_contains($cn, 'VII') && !str_contains($cn, 'VIII'));
            $isKelas8 = str_contains($cn, '8') || str_contains($cn, 'VIII');
            $isKelas9 = str_contains($cn, '9') || str_contains($cn, 'IX');
            
            if ($kelas == 7 && $isKelas7) return true;
            if ($kelas == 8 && $isKelas8) return true;
            if ($kelas == 9 && $isKelas9) return true;
            
            return false;
        });

        if (!$record) return '-';

        // Cari mapel yang namanya mengandung $subjectName (Toleransi beda penulisan)
        $item = $record->items->first(function ($i) use ($subjectName) {
            return stripos($i->subject->name ?? '', $subjectName) !== false;
        });

        return $item ? $item->score : '-';
    }

    /**
     * Helper untuk mengambil data Ketidakhadiran (S/I/A)
     */
    public function getAbsence($kelas, $semester)
    {
        if ($this->loadedGrades === null) {
            $this->loadedGrades = GradeRecord::with(['items.subject'])->where('student_id', $this->id)->get();
        }

        $record = $this->loadedGrades->first(function ($r) use ($kelas, $semester) {
            
            // NORMALISASI SEMESTER: Menyamakan 'Ganjil' = 1, 'Genap' = 2
            $dbSemester = strtolower(trim($r->semester));
            $isMatchSemester = false;
            
            if ($semester == 1 && in_array($dbSemester, ['1', 'ganjil'])) {
                $isMatchSemester = true;
            } elseif ($semester == 2 && in_array($dbSemester, ['2', 'genap'])) {
                $isMatchSemester = true;
            }

            if (!$isMatchSemester) return false;

            $cn = strtoupper($r->class_name);
            if ($kelas == 7 && (str_contains($cn, '7') || (str_contains($cn, 'VII') && !str_contains($cn, 'VIII')))) return true;
            if ($kelas == 8 && (str_contains($cn, '8') || str_contains($cn, 'VIII'))) return true;
            if ($kelas == 9 && (str_contains($cn, '9') || str_contains($cn, 'IX'))) return true;
            return false;
        });

        if (!$record) return '- / - / -';

        $s = $record->absent_s ?: '-';
        $i = $record->absent_i ?: '-';
        $a = $record->absent_a ?: '-';

        return "{$s} / {$i} / {$a}";
    }

    /**
     * Helper Static untuk Generate NIS Otomatis     
     */
     public static function generateNextNis()
    {
        $yearShort = date('y');
        $lastStudent = self::where('nis', 'like', $yearShort . '%')->orderBy('nis', 'desc')->first();
        $sequence = $lastStudent ? intval(substr($lastStudent->nis, -3)) + 1 : 1;
        return ['prefix' => $yearShort, 'sequence' => $sequence];
    }

    public function getCleanViolationPoints()
    {
        $records = $this->disciplineRecords()->with('disciplineType')->get();
        
        // 1. Hitung Pelanggaran Manual
        $manualMinus = $records->where('disciplineType.type', 'Pelanggaran')->sum('disciplineType.point_value');
        
        // 2. Hitung Pelanggaran Otomatis (Absensi)
        $attendances = AttendanceSiswa::where('student_id', $this->id)->get();
        $alfaCount = $attendances->whereIn('status', ['Alfa', 'Alpa', 'alpha', 'alfa', 'Tanpa Keterangan'])->count();
        $lateCount = $attendances->whereIn('status', ['Terlambat', 'terlambat'])->count();
        $autoMinus = ($alfaCount * 10) + ($lateCount * 5);

        // 3. Hitung Poin Pemulihan (Amnesti / Tugas Positif)
        // Kita mencari record kategori "Kebaikan" yang namanya mengandung "Amnesti" atau "Pemutihan"
        $amnestyPoints = $records->filter(function($r) {
            $name = strtolower($r->disciplineType->name ?? '');
            return $r->disciplineType->type === 'Kebaikan' && 
                   (str_contains($name, 'amnesti') || str_contains($name, 'pemutihan') || str_contains($name, 'decay'));
        })->sum('disciplineType.point_value');

        return max(0, ($manualMinus + $autoMinus) - $amnestyPoints);
    }

    /**
     * ====================================================================
     * FUNGSI GLOBAL: Cek Ambang Batas Poin untuk E-Counseling (BP/BK)
     * Menggabungkan Poin Manual, Absensi Otomatis, dan Fitur Pemulihan.
     * ====================================================================
     */
    public function checkBkThresholds()
    {
        $id = $this->id;
        $thisMonth = now()->month;
        $thisYear = now()->year;

        // 1. AMBIL DATA REKAM DISIPLIN (MANUAL)
        $records = DisciplineRecord::where('student_id', $id)->with('disciplineType')->get();
        $manualMinus = $records->where('disciplineType.type', 'Pelanggaran')->sum('disciplineType.point_value');
        $manualPlus = $records->where('disciplineType.type', 'Kebaikan')->sum('disciplineType.point_value');

        // 2. HITUNG POIN OTOMATIS (ABSENSI & KEAGAMAAN)
        $alfaCount = 0; $lateCount = 0; $prayerCount = 0;
        if (class_exists(AttendanceSiswa::class)) {
            $attendances = AttendanceSiswa::where('student_id', $id)->get();
            $alfaCount = $attendances->whereIn('status', ['Alfa', 'Alpa', 'alpha', 'alfa', 'Tanpa Keterangan'])->count();
            $lateCount = $attendances->whereIn('status', ['Terlambat', 'terlambat'])->count();
            
            // Logika baru dari Anda: Shalat Dhuha/Dhuhur sebagai poin prestasi
            $prayerCount = $attendances->filter(function($att) {
                $act = strtolower($att->activity ?? '');
                return strtolower($att->type ?? '') === 'keagamaan' && (str_contains($act, 'dhuha') || str_contains($act, 'dhuhur'));
            })->count();
        }

        // 3. HITUNG POIN PEMULIHAN / AMNESTI (DEDUCTION POIN MINUS)
        $recoveryPoints = $records->filter(function($r) {
            $name = strtolower($r->disciplineType->name ?? '');
            return str_contains($name, 'pemutihan') || str_contains($name, 'amnesti') || str_contains($name, 'bonus');
        })->sum('disciplineType.point_value');

        // 4. KALKULASI TOTAL AKHIR
        $grossMinus = $manualMinus + ($alfaCount * 10) + ($lateCount * 5);
        $totalMinusClean = max(0, $grossMinus - $recoveryPoints); // Poin minus bersih setelah amnesti
        $totalPlus = $manualPlus + ($prayerCount * 5);

        // 5. LOGIKA TRIGGER TIKET BK A: PELANGGARAN (>= 200)
        if ($totalMinusClean >= 200) {
            $existingViolationTicket = BkSession::where('student_id', $id)
                ->where('is_system_generated', true)
                ->where('initial_message', 'like', '%PELANGGARAN%')
                ->whereMonth('created_at', $thisMonth)
                ->whereYear('created_at', $thisYear)
                ->exists();

            if (!$existingViolationTicket) {
                $kategoriId = optional(BkCategory::where('name', 'like', '%Disiplin%')->first())->id ?? 1;
                BkSession::create([
                    'student_id' => $id,
                    'bk_category_id' => $kategoriId,
                    'initial_message' => "[SISTEM: PELANGGARAN BERAT]\nAkumulasi poin bersih siswa: {$totalMinusClean}. (Total Minus: {$grossMinus}, Amnesti: {$recoveryPoints}). Mohon pembinaan.",
                    'status' => 'pending',
                    'method' => 'offline',
                    'is_system_generated' => true,
                ]);
            }
        }

        // 6. LOGIKA TRIGGER TIKET BK B: APRESIASI PRESTASI (>= 100)
        if ($totalPlus >= 100) {
            $existingMeritTicket = BkSession::where('student_id', $id)
                ->where('is_system_generated', true)
                ->where('initial_message', 'like', '%PRESTASI%')
                ->whereMonth('created_at', $thisMonth)
                ->whereYear('created_at', $thisYear)
                ->exists();

            if (!$existingMeritTicket) {
                $kategoriId = optional(BkCategory::where('name', 'like', '%Prestasi%')->first())->id ?? 1;
                BkSession::create([
                    'student_id' => $id,
                    'bk_category_id' => $kategoriId,
                    'initial_message' => "[SISTEM: APRESIASI PRESTASI]\nSiswa memiliki rekam jejak sangat baik dengan akumulasi +{$totalPlus} poin kebaikan (Termasuk poin shalat berjamaah).",
                    'status' => 'pending',
                    'method' => 'offline',
                    'is_system_generated' => true,
                ]);
            }
        }
    }
    /**
     * Relasi ke riwayat poin tahunan (Arsip Tutup Buku)
     */
    public function pointHistories()
    {
        return $this->hasMany(StudentPointHistory::class, 'student_id')
                    ->orderBy('academic_year', 'desc');
    }

    public function literacyJournals()
    {
        return $this->hasMany(LiteracyJournal::class); // Sesuaikan dengan nama model literasi 
    }
}