<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PiketInval extends Model
{
    use HasFactory;

    protected $table = 'piket_invals';

    protected $fillable = [
        'piket_journal_id',
        'absent_teacher_id',
        'reason',
        'class_id',
        'subject_id',
        'timeslot_info',
        'inval_teacher_id',
        'assignment_notes',
        'status',
    ];

    public function journal()
    {
        return $this->belongsTo(PiketJournal::class, 'piket_journal_id');
    }

    public function absentTeacher()
    {
        return $this->belongsTo(User::class, 'absent_teacher_id');
    }

    public function invalTeacher()
    {
        return $this->belongsTo(User::class, 'inval_teacher_id');
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
