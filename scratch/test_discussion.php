<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $material = App\Models\LmsMaterial::first();
    if (!$material) {
        echo "No material found\n";
        exit;
    }
    $d = App\Models\LmsDiscussion::create([
        'material_id' => $material->id,
        'student_id' => 1,
        'author_name' => 'ABDUL ROSYID',
        'author_role' => 'Siswa',
        'comment' => 'Tes Pertanyaan Diskusi LMS',
        'is_verified' => false
    ]);
    echo "SUCCESS! Created Discussion ID: " . $d->id . " for Material ID: " . $material->id . "\n";
    
    $fetch = App\Models\LmsDiscussion::where('material_id', $material->id)->get();
    echo "Fetched discussions count: " . $fetch->count() . "\n";

    $d->delete();
    echo "Cleaned up test record.\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
