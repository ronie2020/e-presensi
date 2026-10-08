<?php
$popupRaw = '<p>Line 1</p><p>Line 2</p>';
$popupAllowed = '<div><p><br><strong><b><em><i><u><s><ul><ol><li><h2><h3><h4><h5><h6><blockquote><span>';
$popupMessage = preg_replace_callback('/<([a-z0-9]+)\b[^>]*>/i', function ($m) {
    $keep = [];
    if (preg_match('/class\s*=\s*["\']([^"\']*)["\']/i', $m[0], $c)) {
        $keep = array_filter(preg_split('/\s+/', trim($c[1])), fn ($x) => preg_match('/^ql-align-(left|center|right|justify)$/', $x));
    }
    return '<' . strtolower($m[1]) . ($keep ? ' class="' . implode(' ', $keep) . '"' : '') . '>';
}, strip_tags($popupRaw, $popupAllowed));

echo "RESULT:\n" . $popupMessage . "\n";
