<?php
	include_once ("dap-config.php");
	$_SESSION["clickedonfbconnect"]="N";
	if(isset($_SESSION["wpconfigpath"])) {
		$fileName = $_SESSION["wpconfigpath"];
		if(file_exists($fileName)) {
			include_once ($fileName);
			wp_logout();
		}
	}
	//echo $_SESSION["wpconfigpath"]; exit;
	$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : Dap_Config::get("LOGGED_OUT_URL");
	if($redirect == "") $redirect = "/";
	pluginLogout();
	Dap_Session::closeSession();
	header("Location: $redirect");
?>