<?php
	error_reporting(E_ALL);
	$no_install_issue = TRUE;
	$ver = phpversion();
	
	if($ver < 5) {
		echo "Sorry, you need a minimum of PHP version 5 in order to run DAP. Your current PHP version is: " . $ver . "<br/>";
		$no_install_issue = FALSE;
	} 
	
	if ( !class_exists('PDO') || !method_exists('PDO', 'query') || !class_exists('PDOStatement') || !class_exists('PDOException') || !in_array("mysql", PDO::getAvailableDrivers()) ) {
		echo "Sorry, it appears that a required library (PDO) for connecting to a MySQL database has not been enabled. Pls ask your web host if your web site can have 'PHP PDO Enabled for MySQL'<br/>";
		$no_install_issue = FALSE;
	}
	
	if($no_install_issue == FALSE) {
		exit;
	} else {
		header("Location: install2.php");
	}
?>
