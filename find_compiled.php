<?php
$dir = 'storage/framework/views/';
$files = glob($dir . '*.php');
foreach($files as $file) {
    $c = file_get_contents($file);
    if(strpos($c, 'switchTab') !== false && strpos($c, 'Integrations') !== false) {
        $lines = explode("\n", $c);
        $total = count($lines);
        echo "File: $file, Total lines: $total\n";
        // Show lines 4278-4285
        for($i = 4277; $i <= min(4284, $total-1); $i++) {
            echo ($i+1).": ".$lines[$i]."\n";
        }
        break;
    }
}
