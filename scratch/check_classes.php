<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$classes = App\Models\SchoolClass::withCount('students')->get();
foreach ($classes as $c) {
    if ($c->students_count > 0) {
        echo "Kelas: " . $c->name . " (ID: " . $c->id . ") -> " . $c->students_count . " siswa\n";
    }
}
