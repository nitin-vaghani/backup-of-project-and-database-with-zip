<?php
/*
 * PHP: Recursively Backup Files & Folders to ZIP-File
 * (c) 2012-2014: Marvin Menzerath - http://menzerath.eu
*/
// Make sure the script can handle large folders/files
error_reporting(E_ALL);
ini_set('display_errors',-1);
ini_set('max_execution_time', 1200);
ini_set('memory_limit','1024M');


// Here the magic happens :)
function zipData($source, $destination) {
	if (extension_loaded('zip')) {
		if (file_exists($source)) {
			$zip = new ZipArchive();
			if ($zip->open($destination, ZIPARCHIVE::CREATE)) {
				$source = realpath($source);
				
				if (is_dir($source)) {
					$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
					foreach ($files as $file) {
						$file = realpath($file);
						
							if (is_dir($file)) {
								$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
							} else if (is_file($file)) {
								$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
							}
		
					}
				} else if (is_file($source)) {
					$zip->addFromString(basename($source), file_get_contents($source));
				}
					
			}
			return $zip->close();
		}
	}
	return false;
}

// Start the backup!
if(zipData('../app', '../../../backups/'.date('Y-m-d',time()).'_backup.zip'))
{
	echo 'Finished.';
}else{
	echo 'Not created.';
}

?>
