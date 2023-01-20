<?php
	//getting all images in a directory
	$dir = "./beforeResize";
	$dh  = opendir($dir);
	while (false !== ($filename = readdir($dh))) {
		  $files[] = $filename;
	}

	$pdfFiles = preg_grep ('/\.pdf$/i', $files);
	$pdfFiles = array_merge($pdfFiles, (preg_grep ('/\.PDF$/i', $files)));

	$pdfFiles = array_unique($pdfFiles);
	//$images is array of all image file names

	//Creating folder for target
	if (! file_exists("./resized")) {
		  mkdir("./resized", 0777, true);
	}
	$targetFolder = "./resized";

	foreach ($pdfFiles as $pdfFile) {
		//source_folder + file_name (string) 
		$source = $dir."/".$pdfFile; 
		//targete_folder + file_name (string) 
		$target = $targetFolder."/".$pdfFile; 
		echo "compressing : $source ...\r\n";

		$tmp = $target;

		$cmdstr = "gs -dNOPAUSE -dBATCH -sDEVICE=pdfwrite -dPDFSETTINGS=/ebook -dCompatibilityLevel=1.3 -sOutputFile=".$target. ' '.$source;

		$output = shell_exec($cmdstr);
		$size_compressed      = filesize($target);
		$size_org      = filesize($source);


		if (($output != null) && ($size_compressed < $size_org)) {
			echo "$source - original file size = {$size_org}, after compression = {$size_compressed}\r\n";
		} else {
			echo "$source - pdf resize was not effective.\r\n";
			$cmdstr = "rm $target";
			shell_exec($cmdstr);
		}
		echo "--------------------------------------------------------------------------------------------------\r\n";
}
?>
