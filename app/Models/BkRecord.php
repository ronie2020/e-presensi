<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BkRecord extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'is_confidential' => 'boolean',
    ];

    public function session()
    {
        return $this->belongsTo(BkSession::class, 'bk_session_id');
    }
}