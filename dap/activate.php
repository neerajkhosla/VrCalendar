<?php
	include_once ("dap-config.php");
	
	$c = isset($_GET['c']) ? $_GET['c'] : "";
	$p = isset($_GET['p']) ? $_GET['p'] : "";
	$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : Dap_Config::get("LOGIN_URL");
	$msg = "";

	if( ($c == "") || ($p == "") ){
		$msg = "Sorry, that was an invalid activation link. Please try again with a valid link.";
	} else {
		$user = Dap_User::loadUserByActivationKey($c);
		Dap_UsersProducts::activate($c, $user, $p);
		$msg = "SUCCESS_ACTIVATION";
	}
	
	$cname = "cssignuptounlock:" .  $p . ":INACT";
	$cstuname = "cssignuptounlock:" .  $p;
	
//	logToFile("activate.php: cstuname=".$cstuname . ", cname=".$cname.",cookieexpiry=".$_COOKIE[$cname]);
	
	if ($_COOKIE[$cname] != '') { 
		$cookieexpiry = $_COOKIE[$cname];
		$date_of_expiry = time() + 60 * 60 * 24 * $cookieexpiry ;
		setcookie($cstuname, intval($cookieexpiry), intval($date_of_expiry),'/');	//activate STU cookie
		setcookie($cname, '', time() - 3600,'/');	//expire INACT cookie
	}
	
	header("Location: ".$redirect."?msg=".$msg);
?>