<?php
	include_once "../dap-config.php";
	
	$e = isset($_GET['e']) ? $_GET['e'] : Dap_Config::get("ADMIN_EMAIL"); //e = email of referring affiliate
	$fn = isset($_GET['fn']) ? $_GET['fn'] : "NoName"; //fn = first name of referring affiliate
	$p = isset($_GET['p']) ? $_GET['p'] : SITE_URL_DAP; //p = page to be redirected after setting affiliate cookie
	
	//Get id for email
	$user = Dap_User::loadUserByEmail($e);
	$id = 0;
	$url = "";

	if( is_null($user) ) {
		$user = new Dap_User();
		$user->setFirst_name($fn);
		$user->setEmail($e);
		//$user->setStatus("A");
		$id = $user->create();
		$url = "/";
	} else {
		$id = $user->getId();
		//Redirect to real affiliate link
		$url = "/dap/a/?a=" . $id . "&p=" . $p;
	}
	
	header("Location: " . $url);
?>