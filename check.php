<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$content = \App\Models\Announcement::where('title', 'like', '%PENGUMUMAN PENTING%')->first()->content ?? 'NOT FOUND';
echo "RAW_CONTENT:\n" . $content;
