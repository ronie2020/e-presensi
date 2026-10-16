<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PiketJournal extends Model
{
    use HasFactory;

    protected $table = 'piket_journals';

    protected $fillable = [
        'date',
        'academic_year_id',
        'coordinator_id',
        'weather',
        'general_notes',
        'status',
        'verified_by',
        'verified_at',
    ];

    protected $casts = [
        'date'        => 'date',
        'verified_at' => 'datetime',
    ];

    public function coordinator()
    {
        return $this->belongsTo(User::class, 'coordinator_id');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class, 'academic_year_id');
    }

    public function invals()
    {
        return $this->hasMany(PiketInval::class, 'piket_journal_id');
    }

    public function guests()
    {
        return $this->hasMany(PiketGuest::class, 'piket_journal_id');
    }
}
