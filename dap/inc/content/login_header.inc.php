<?php
	logToFile("in login.php",LOG_DEBUG_DAP);
	$signup_page = Dap_Config::get("SITE_URL_DAP");
	
	if(isset($_GET['request'])) {
		$product = Dap_Product::getProductDetailsByResource($_GET['request']);
		if( !empty($product) && ($product->getSales_page_url() != "") ) {
			$signup_page = $product->getSales_page_url();
		}
	} 		

	if(!Dap_Session::isLoggedIn()) {
		logToFile("not loggedIn",LOG_DEBUG_DAP);
		if( isset($_POST['submitted']) && ($_POST['submitted'] == "Y") ) {
			//logToFile("login form submitted",LOG_DEBUG_DAP); 
			$email = $_POST['email'];
			
			if( isset($_POST['forgot']) && ($_POST['forgot'] == "Y") ) {
				//executing 'forgot password'
				//logToFile("forgot password",LOG_DEBUG_DAP); 
				if($email != "") {
					$msg = sendPasswordByEmail($email);
					header("Location: " . Dap_Config::get("LOGIN_URL") . "?msg=" . htmlentities($msg));
					exit;
				}
			}
			
			//logToFile("not forgot - regular authentication",LOG_DEBUG_DAP); 
			//if not "forgot", then do regular authentication
			$password = md5($_POST['password']);
			$rememberMe = isset($_POST['rememberMe']) ? $_POST['rememberMe'] : "";
			
			//logToFile("password: $password",LOG_DEBUG_DAP); 
			if(($email != "") && ($password != "") && validate($email,$password) && validateLogins($email, getIpOfUser())) {
				if($rememberMe == "rememberMe") {
					logToFile("remembering...",LOG_DEBUG_DAP); 
					//Set cookie for 2 weeks
					setcookie("dapcookie_email",$email,time()+3600*24*3650,"/");
					setcookie("dapcookie_password",$password,time()+3600*24*3650,"/");
				}
				include_once("admin/affiliateResolution.php");
			} else {
				header("Location: ".Dap_Config::get("LOGIN_URL")."?msg=" . htmlentities(INVALID_PASSWORD_MSG));
				exit;
			}
		} 
	}

	if(Dap_Session::isLoggedIn()) {
		//Check if there is a request uri
		logToFile("loggedIn",LOG_DEBUG_DAP);
		
		if(isset($_GET['request'])) {
			header("Location:".SITE_URL_DAP."/".$_POST['request']);
		} else {
			$session = DAP_Session::getSession();
			if($session->isAdmin()) {
				header("Location:/dap/admin/");
				exit;
			} else {
				//TODO: Redirect to site-wide landing page or products auto-gen page.
				header("Location:".Dap_Config::get("LOGGED_IN_URL"));
				exit;
			}
		}
	}
?>