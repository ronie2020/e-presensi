<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AcademicCalendar extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'is_all_day',
        'type',
        'background_color',
        'border_color',
        'text_color',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_all_day' => 'boolean',
    ];

    /**
     * Fungsi Helper untuk memformat data sesuai format FullCalendar
     */
    public function toCalendarEvent()
    {
        // Tentukan warna bawaan berdasarkan tipe
        $bgColor = $this->background_color;
        $borderColor = $this->border_color;

        if (!$bgColor) {
            switch ($this->type) {
                case 'libur':
                case 'nasional':
                    $bgColor = '#ef4444'; // Red
                    $borderColor = '#b91c1c';
                    break;
                case 'ujian':
                    $bgColor = '#f59e0b'; // Amber
                    $borderColor = '#d97706';
                    break;
                case 'kegiatan':
                default:
                    $bgColor = '#3b82f6'; // Blue
                    $borderColor = '#2563eb';
                    break;
            }
        }

        // PERBAIKAN: Logika Tanggal Selesai (End Date)
        $end = null;
        if ($this->end_date) {
            if ($this->is_all_day) {
                // FullCalendar butuh H+1 agar hari terakhir ikut diblok warna
                $end = \Carbon\Carbon::parse($this->end_date)->addDay()->format('Y-m-d');
            } else {
                $end = \Carbon\Carbon::parse($this->end_date)->toIso8601String();
            }
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            // Parsing aman memastikan format selalu terbaca string
            'start' => $this->is_all_day ? \Carbon\Carbon::parse($this->start_date)->format('Y-m-d') : \Carbon\Carbon::parse($this->start_date)->toIso8601String(),
            'end' => $end,
            'allDay' => (bool) $this->is_all_day,
            'backgroundColor' => $bgColor,
            'borderColor' => $borderColor,
            'textColor' => $this->text_color ?? '#ffffff',
            'extendedProps' => [
                'type' => ucfirst($this->type),
                'description' => $this->description,
            ]
        ];
    }
}