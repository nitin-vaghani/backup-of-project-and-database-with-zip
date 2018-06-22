<?php

// assuming file.zip is in the same directory as the executing script.
$file = 'file.zip';

// get the absolute path to $file
$path = realpath(dirname(__FILE__) . '/../') . '/app/';

$zip = new ZipArchive;
$res = $zip->open($file);
if ($res === TRUE) {
    // extract it to the path we determined above
    $zip->extractTo($path);
    $zip->close();
    echo "WOOT! $file extracted to $path";
} else {
    echo "Doh! I couldn't open $file";
}
