<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DisciplineRecord extends Model
{
    use HasFactory;

    /**
     * PENTING: Memberi tahu Laravel agar model ini menggunakan tabel 'discipline_records'
     */
    protected $table = 'discipline_records';

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'student_id',
        'discipline_type_id',
        'recorded_by_user_id',
        'notes',
        'date',
    ];

    /**
     * Hubungan: Satu Catatan Disiplin ini dimiliki oleh SATU Siswa.
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    /**
     * Hubungan: Satu Catatan Disiplin ini mengacu pada SATU Tipe Disiplin.
     */
    public function disciplineType(): BelongsTo
    {
        return $this->belongsTo(DisciplineType::class, 'discipline_type_id');
    }

    /**
     * Hubungan: Satu Catatan Disiplin ini dicatat oleh SATU User (Guru/Admin).
     */
    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by_user_id');
    }
}