<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// [PERBAIKAN] Gunakan Model yang benar: CbtExam
use App\Models\CbtExam; 

class SebController extends Controller
{
    // Halaman Landing jika siswa membuka via Chrome biasa
    public function landing($exam_id)
    {
        // [PERBAIKAN] Ganti Exam:: menjadi CbtExam::
        $exam = CbtExam::findOrFail($exam_id);
        
        return view('cbt.seb_landing', compact('exam'));
    }

    // Download File Config .seb
    public function downloadConfig($exam_id)
    {
        // [PERBAIKAN] Ganti Exam:: menjadi CbtExam::
        $exam = CbtExam::findOrFail($exam_id);
        
        // URL Ujian yang diproteksi (Halaman Start Confirmation)
        $startUrl = route('student.exam.showStart', $exam->id); 
        
        // Password keluar SEB (Opsional: 12345)
        // Hash password "12345"
        $quitPassword = hash('sha256', '12345'); 

        // XML Config SEB
        // Konfigurasi agar hanya URL ujian yang bisa dibuka
        $xmlContent = '<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE plist PUBLIC "-//Apple//DTD PLIST 1.0//EN" "http://www.apple.com/DTDs/PropertyList-1.0.dtd">
<plist version="1.0">
<dict>
    <key>StartURL</key>
    <string>'.$startUrl.'</string>
    <key>hashedQuitPassword</key>
    <string>'.$quitPassword.'</string>
    <key>allowQuit</key>
    <true/>
    <key>ignoreExitKeys</key>
    <true/>
    <key>allowSwitchToApplications</key>
    <false/>
    <key>allowWlan</key>
    <true/>
    <key>showTaskBar</key>
    <true/>
    <key>showReloadButton</key>
    <true/>
    <key>showTime</key>
    <true/>
    <key>showInputLanguage</key>
    <true/>
    <key>browserWindowAllowReload</key>
    <true/>
</dict>
</plist>';

        $fileName = 'ujian-' . \Str::slug($exam->title) . '.seb';

        return response($xmlContent)
            ->header('Content-Type', 'application/seb')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}