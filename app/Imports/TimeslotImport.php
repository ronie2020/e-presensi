<?php

namespace App\Imports;

use App\Models\Timeslot;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;

class TimeslotImport implements ToCollection, WithHeadingRow
{
    /**
     * Memproses setiap baris dari file Excel
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Validasi: Pastikan baris ini tidak kosong
            if (!isset($row['nama_sesi']) || !isset($row['jam_mulai']) || !isset($row['jam_selesai'])) {
                continue; 
            }

            // --- KONVERSI WAKTU EXCEL KE PHP ---
            $startTime = $this->transformTime($row['jam_mulai']);
            $endTime = $this->transformTime($row['jam_selesai']);

            // --- KONVERSI FORMAT HARI ---
            $rawHari = isset($row['hari']) ? trim($row['hari']) : 'Semua Hari';
            // Bersihkan spasi berlebih dari koma (misal: "Selasa, Rabu" -> "Selasa", "Rabu")
            $dayParts = array_map('trim', explode(',', $rawHari));
            // Kapitalisasi huruf pertama
            $cleanDays = array_map(function($d) { return ucfirst(strtolower($d)); }, $dayParts);
            $dayOfWeek = implode(',', $cleanDays);
            if (empty($dayOfWeek)) { $dayOfWeek = 'Semua Hari'; }

            // --- PENENTUAN ISTIRAHAT ---
            $isBreakText = strtolower(trim($row['istirahat'] ?? 'tidak'));
            $isBreak = in_array($isBreakText, ['ya', '1', 'true', 'yes', 'y']);

            // --- URUTAN ---
            $orderSequence = isset($row['urutan']) ? (int) $row['urutan'] : 99;

            // PERBAIKAN FATAL: Gunakan KOMBINASI 'name' dan 'day_of_week' 
            // agar jadwal yang namanya sama tapi harinya beda tidak saling menimpa!
            Timeslot::updateOrCreate(
                [
                    'name' => trim($row['nama_sesi']),
                    'day_of_week' => $dayOfWeek,
                ],
                [
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'is_break' => $isBreak,
                    'order_sequence' => $orderSequence
                ]
            );
        }
    }

    /**
     * Mesin Penerjemah Waktu Super Cerdas (Tahan Error Tanda Baca & Bug Excel)
     */
    private function transformTime($value)
    {
        // 1. Pastikan nilainya berupa string yang bersih
        $value = trim(strval($value));
        if (empty($value)) return '00:00';

        // 2. Deteksi Teks Waktu Normal (Misal: "07:00", "07.30")
        if (str_contains($value, ':') || preg_match('/^\d{1,2}\.\d{2}$/', $value)) {
            $value = str_replace('.', ':', $value);
            $timestamp = strtotime($value);
            if ($timestamp !== false) {
                return date('H:i', $timestamp);
            }
        }

        // 3. Penanganan Bug Excel (Saat Excel mengubah teks menjadi angka)
        if (is_numeric($value)) {
            $floatVal = (float) $value;

            // Kasus A: Format Waktu Bawaan Excel (Fractions/Desimal di bawah 1)
            // Misal: 0.29166667 (yang artinya 07:00)
            if ($floatVal > 0 && $floatVal < 1) {
                $hours = floor($floatVal * 24);
                $mins = round((($floatVal * 24) - $hours) * 60);
                if ($mins == 60) { $hours += 1; $mins = 0; }
                return sprintf('%02d:%02d', $hours, $mins);
            }

            // Kasus B: User mengetik "07.40" namun dibaca komputer sebagai angka desimal murni "7.4"
            // Atau user mengetik "07.00" dan dibaca komputer sebagai angka bulat "7"
            if ($floatVal >= 1 || $floatVal == 0) {
                // Kita paksakan angka tersebut menjadi format 2 desimal (7.4 -> 7.40)
                $formatted = number_format($floatVal, 2, '.', '');
                $parts = explode('.', $formatted);
                
                $hours = str_pad($parts[0], 2, '0', STR_PAD_LEFT);
                $mins = str_pad($parts[1], 2, '0', STR_PAD_RIGHT);
                
                if ($hours >= 0 && $hours <= 23 && $mins >= 0 && $mins <= 59) {
                    return "$hours:$mins";
                }
            }
        }

        // 4. Fallback Terakhir
        try {
            return Carbon::parse($value)->format('H:i');
        } catch (\Exception $e) {
            return '00:00'; 
        }
    }
}