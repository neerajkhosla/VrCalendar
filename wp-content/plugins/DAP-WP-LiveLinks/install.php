<?php
	require('/home/effing/effingeek.com/geeksonly/wp-blog-header.php');
	require('/home/effing/effingeek.com/geeksonly/wp-admin/includes/file.php');
	//require_once('/home/effing/effingeek.com/geeksonly/wp-includes/pluggable.php');
	
	$ch = curl_init();
	$source = "http://DigitalAccessPass.com/daptest.zip";
	curl_setopt($ch, CURLOPT_URL, $source);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$data = curl_exec ($ch);
	curl_close ($ch);
	$targetDir = $_SERVER['DOCUMENT_ROOT'] . "/dap2";
	
	echo "1 done";
	
	if (!is_dir($targetDir)) {
    	mkdir($targetDir);
	}
	
	$targetFilename = "daptest.zip";
	$target = $targetDir . '/' . $targetFilename;
	
	$file = fopen($targetDir . '/' . $targetFilename, "w+");
	fputs($file, $data);
	fclose($file);
	
	WP_Filesystem();
	unzip_file( $target, $targetDir );
	echo "Ok!"; 

?>