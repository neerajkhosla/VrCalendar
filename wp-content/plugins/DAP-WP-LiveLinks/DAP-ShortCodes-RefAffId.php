<?php 

add_shortcode('DAPRefAffId', 'dap_refaffid');

function dap_refaffid($atts, $content=null){ 
	
	$session = Dap_Session::getSession();
	$user = $session->getUser();
	$userId = 0;
	
	if(isset($user)) { 
		//If user is logged in, get affiliate id who referred this user
		$user = Dap_User::loadUserById($user->getId());
		$refAffId = Dap_User::getAffiliateForUser($user->getId());
		if(isset($refAffId)) return $refAffId;
	} else {
		//user not logged in - just a visitor
		//Check to see if there's an aff cookie
		if(isset($_COOKIE['dapa'])) {
			return $_COOKIE['dapa'];
		}
	}
	
	//If neither... a) logged-in but no aff in DAP... AND b) visitor, but no aff cookie
	//Then return admin id
	$userAdminObj = Dap_User::loadAdminUserByMinId();
	$userIdAdmin = $userAdminObj->getId();
	return $userIdAdmin;
}


?>