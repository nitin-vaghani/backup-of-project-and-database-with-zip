<?php

/*
 * PHP: Recursively Backup Files & Folders to ZIP-File
 * (c) 2012-2014: Marvin Menzerath - http://menzerath.eu
 */

// Make sure the script can handle large folders/files

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('max_execution_time', 1200);
ini_set('memory_limit', '2048M');
define("EXCEPT_FILE_NAME", 'cpanel_sprytar_22aug2017.zip');
define("EXCEPT_FOLDER_NAME", '.git/');

// Here the magic happens :)
function zipData($source, $destination) {
    try {
        if (extension_loaded('zip')) {
            if (file_exists($source)) {
                $zip = new ZipArchive();
                if ($zip->open($destination, ZIPARCHIVE::CREATE)) {
                    $source = realpath($source);

                    if (is_dir($source)) {
                        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
                        foreach ($files as $file) {
                            $file = realpath($file);
                            if (str_replace($source . '/', '', $file . '/') == EXCEPT_FOLDER_NAME) {
                                $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                            } else {
                                if (is_dir($file)) {
                                    $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                                } else if (is_file($file)) {
                                    if (basename($file) == EXCEPT_FILE_NAME) {
                                        $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                                    } else {
                                        $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                                    }
                                }
                            }
                        }
                    } else if (is_file($source)) {
                        if (basename($source) != EXCEPT_FILE_NAME) {
                            $zip->addFromString(basename($source), file_get_contents($source));
                        }
                    }
                }
                return $zip->close();
            }
        }
    } catch (Exception $e) {
        echo "<h3>Error in zipData</h3><br/>";
        echo '<pre>';
        print_r($e);
        exit;
    }
    return false;
}

try {

// Start the backup!
    if (zipData('./', date('Y-m-d', time()) . '_backup.zip')) {
        echo 'Finished.';
    } else {
        echo 'Not created.';
    }

    echo memory_get_usage() . "<br>\n";
    
    echo memory_get_peak_usage() . "<br>\n";
    
} catch (Exception $e) {
    echo '<pre>';
    print_r($e);
    exit;
}
?>
