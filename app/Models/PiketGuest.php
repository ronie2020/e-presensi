<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PiketGuest extends Model
{
    use HasFactory;

    protected $table = 'piket_guests';

    protected $fillable = [
        'piket_journal_id',
        'guest_name',
        'institution',
        'phone',
        'meet_with',
        'purpose',
        'time_in',
        'time_out',
        'notes',
        'is_vip',
    ];

    protected $casts = [
        'is_vip' => 'boolean',
    ];

    public function journal()
    {
        return $this->belongsTo(PiketJournal::class, 'piket_journal_id');
    }
}
