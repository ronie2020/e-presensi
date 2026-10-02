<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$users = App\Models\User::all();
foreach ($users as $u) {
    echo "ID: {$u->id} | Name: {$u->name} | Role: {$u->role}\n";
    $classIdsFromLoad = App\Models\TeachingLoad::where('teacher_id', $u->id)->pluck('class_id');
    $classIdsFromTimetable = App\Models\Timetable::where('teacher_id', $u->id)->pluck('class_id');
    $classIdsFromMaterials = App\Models\LmsMaterial::where('teacher_id', $u->id)->pluck('class_id');
    echo "   Loads: " . $classIdsFromLoad->implode(',') . "\n";
    echo "   Timetables: " . $classIdsFromTimetable->implode(',') . "\n";
    echo "   Materials: " . $classIdsFromMaterials->implode(',') . "\n";
}
