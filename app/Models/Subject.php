<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'group',
        'order',
    ];

    public function topics() {
    return $this->hasMany(Topic::class)->orderBy('order_number', 'asc');
}

}