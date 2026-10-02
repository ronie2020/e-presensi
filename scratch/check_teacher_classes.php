<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('name', 'like', '%Roni%')->first();
if (!$user) {
    echo "User not found\n";
    exit;
}

echo "User ID: " . $user->id . " | Name: " . $user->name . " | Role: " . $user->role . "\n";

$loads = App\Models\TeachingLoad::where('teacher_id', $user->id)->get();
echo "TeachingLoads count: " . $loads->count() . "\n";
foreach ($loads as $l) {
    echo " - Class ID: " . $l->class_id . " | Subject ID: " . $l->subject_id . "\n";
}

$timetables = App\Models\Timetable::where('teacher_id', $user->id)->get();
echo "Timetables count: " . $timetables->count() . "\n";
foreach ($timetables as $t) {
    echo " - Class ID: " . $t->class_id . " | Subject ID: " . $t->subject_id . "\n";
}

$materials = App\Models\LmsMaterial::where('teacher_id', $user->id)->get();
echo "LmsMaterials count: " . $materials->count() . "\n";
foreach ($materials as $m) {
    echo " - Title: " . $m->title . " | Class ID: " . $m->class_id . " | Subject ID: " . $m->subject_id . "\n";
}
