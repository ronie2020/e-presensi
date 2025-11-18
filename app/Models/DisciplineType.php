<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DisciplineType extends Model
{
    use HasFactory;

    /**
     * Kolom yang boleh diisi secara massal.
     */
    protected $fillable = [
        'name',
        'type',
        'point_value',
    ];

    /**
     * Hubungan: Satu Tipe Disiplin (misal 'Terlambat') bisa memiliki BANYAK Catatan Kejadian.
     */
    public function records(): HasMany
    {
        return $this->hasMany(DisciplineRecord::class, 'discipline_type_id');
    }
}