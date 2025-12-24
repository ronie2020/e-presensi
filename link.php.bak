<?php
$target = __DIR__ . '/storage/app/public';
$link = __DIR__ . '/public/storage';

if (file_exists($link)) {
    echo "Link lama ditemukan, menghapus...\n";
    @unlink($link);
}

try {
    symlink($target, $link);
    echo "SUKSES: Symlink public/storage -> storage/app/public berhasil dibuat!\n";
} catch (Exception $e) {
    echo "GAGAL: " . $e->getMessage() . "\n";
}
?>
