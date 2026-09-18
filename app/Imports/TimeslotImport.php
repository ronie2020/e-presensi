<?php

namespace App\Imports;

use App\Models\Timeslot;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class TimeslotImport implements ToCollection, WithHeadingRow
{
    public int $successCount = 0;
    public array $errors = [];

    /**
     * Memproses setiap baris dari file Excel
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $lineNumber = $index + 2; // header baris 1, data baris 2 dst

            // Validasi: Pastikan baris ini memiliki nama sesi
            if (!isset($row['nama_sesi']) || trim(strval($row['nama_sesi'])) === '') {
                continue; 
            }

            if (!isset($row['jam_mulai']) || !isset($row['jam_selesai'])) {
                $this->errors[] = "Baris {$lineNumber}: Kolom Jam Mulai atau Jam Selesai kosong.";
                continue;
            }

            // --- KONVERSI WAKTU EXCEL KE PHP ---
            $startTime = $this->transformTime($row['jam_mulai']);
            $endTime = $this->transformTime($row['jam_selesai']);

            if ($startTime === '00:00' && $endTime === '00:00' && !is_numeric($row['jam_mulai'])) {
                $this->errors[] = "Baris {$lineNumber}: Format waktu '{$row['jam_mulai']}' tidak valid.";
                continue;
            }

            // --- KONVERSI FORMAT HARI (AMANKAN DARI BUG LOWERCASE) ---
            $rawHari = isset($row['hari']) ? trim($row['hari']) : 'Semua Hari';
            if (empty($rawHari) || strcasecmp($rawHari, 'Semua Hari') === 0) {
                $dayOfWeek = 'Semua Hari';
            } elseif (strcasecmp($rawHari, 'Selain Senin') === 0) {
                $dayOfWeek = 'Selain Senin';
            } elseif (strcasecmp($rawHari, 'Selain Jumat') === 0) {
                $dayOfWeek = 'Selain Jumat';
            } else {
                $dayParts = array_map('trim', explode(',', $rawHari));
                $cleanDays = array_map(function($d) { return ucfirst(strtolower($d)); }, $dayParts);
                $dayOfWeek = implode(',', $cleanDays);
                if (empty($dayOfWeek)) { $dayOfWeek = 'Semua Hari'; }
            }

            // --- PENENTUAN ISTIRAHAT ---
            $isBreakText = strtolower(trim(strval($row['istirahat'] ?? 'tidak')));
            $isBreak = in_array($isBreakText, ['ya', '1', 'true', 'yes', 'y']);

            // --- URUTAN ---
            $orderSequence = isset($row['urutan']) && is_numeric($row['urutan']) ? (int) $row['urutan'] : ($this->successCount + 1);

            // Simpan / update ke database
            Timeslot::updateOrCreate(
                [
                    'name' => trim(strval($row['nama_sesi'])),
                    'day_of_week' => $dayOfWeek,
                ],
                [
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'is_break' => $isBreak,
                    'order_sequence' => $orderSequence
                ]
            );

            $this->successCount++;
        }
    }

    /**
     * Mesin Penerjemah Waktu Cerdas (Tahan Format Excel, Desimal, Serial Date & String)
     */
    private function transformTime($value)
    {
        $value = trim(strval($value));
        if (empty($value)) return '00:00';

        // 1. Deteksi Teks Waktu Normal (Misal: "07:00", "07.30")
        if (str_contains($value, ':') || preg_match('/^\d{1,2}\.\d{2}$/', $value)) {
            $value = str_replace('.', ':', $value);
            $timestamp = strtotime($value);
            if ($timestamp !== false) {
                return date('H:i', $timestamp);
            }
        }

        // 2. Penanganan Format Angka Bawaan Excel
        if (is_numeric($value)) {
            $floatVal = (float) $value;

            // Kasus A: Format Waktu Murni Excel (Desimal di bawah 1, misal 0.29166667 = 07:00)
            if ($floatVal > 0 && $floatVal < 1) {
                $hours = floor($floatVal * 24);
                $mins = round((($floatVal * 24) - $hours) * 60);
                if ($mins >= 60) { $hours += 1; $mins = 0; }
                return sprintf('%02d:%02d', $hours, $mins);
            }

            // Kasus B: Excel DateTime Serial Number (> 1000, misal tanggal + jam)
            if ($floatVal > 1000) {
                try {
                    return ExcelDate::excelToDateTimeObject($floatVal)->format('H:i');
                } catch (\Throwable $e) {
                    // fallback
                }
            }

            // Kasus C: User mengetik "07.40" terbaca "7.4", atau "07.00" terbaca "7"
            if ($floatVal >= 0 && $floatVal <= 24) {
                $formatted = number_format($floatVal, 2, '.', '');
                $parts = explode('.', $formatted);
                $hours = str_pad($parts[0], 2, '0', STR_PAD_LEFT);
                $mins = str_pad($parts[1], 2, '0', STR_PAD_RIGHT);
                if ($hours >= 0 && $hours <= 23 && $mins >= 0 && $mins <= 59) {
                    return "$hours:$mins";
                }
            }
        }

        // 3. Fallback Terakhir dengan Carbon
        try {
            return Carbon::parse($value)->format('H:i');
        } catch (\Throwable $e) {
            return '00:00'; 
        }
    }
}