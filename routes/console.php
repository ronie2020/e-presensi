<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Kirim notifikasi jatuh tempo & overdue setiap pagi pukul 07.00
Schedule::command('library:send-reminders')->dailyAt('07:00')->appendOutputTo(storage_path('logs/library-reminders.log'));
