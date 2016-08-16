<?php

//abstract class
class Dap_Session extends Dap_Base {

	var $user;
	var $user_email;
	//var $admin = "Y";
	
	/*
		Return Dap_Session type object from the existing session or create new one if not exists.
	*/
	
	public static function getSession() {
		require_once (DAP_ROOT . DAP_INC . '/functions_admin.php');
		
		//logToFile("in getSession()",LOG_DEBUG_DAP);
		$session = null;
		
		if(isset($_SESSION['dap_session'])) {
			//logToFile("returning existing session",LOG_DEBUG_DAP);
			return $_SESSION['dap_session'];
		}
		//if not, first check if cookie exists - if yes, then create session from cookie user
		if( isset($_COOKIE['dapcookie_email']) && isset($_COOKIE['dapcookie_password']) ) {
			if( isset($_SESSION["dap-wp-nosync"]) ) unset($_SESSION["dap-wp-nosync"]);
			$_SESSION['dap_session'] = new Dap_Session();
			//logToFile("isLoggedIn() isset cookie",LOG_DEBUG_DAP);
			//Validate first
			if(validate($_COOKIE['dapcookie_email'],decryptPassword($_COOKIE['dapcookie_password']))) {
				return $_SESSION['dap_session'];
			}
		} 
		//if neither of the above, create new Dap_Session type object
		//logToFile("no existing session, creating new one",LOG_DEBUG_DAP);
		$session = new Dap_Session();
		$_SESSION['dap_session'] = $session;
		return $session;
	}
	
	public static function isLoggedIn() {
		//logToFile("isLoggedIn()",LOG_DEBUG_DAP);
		$session = Dap_Session::getSession();
		$user = $session->getUser();
		
		//logToFile("isLoggedIn() 1",LOG_DEBUG_DAP);
		
		//First check if user exists in session
		if(isset($user)) {
			//logToFile("isLoggedIn() isset(user)",LOG_DEBUG_DAP);
			return TRUE;
		} else if( isset($_COOKIE['dapcookie_email']) && isset($_COOKIE['dapcookie_password']) ) {
			//If not, check cookie and validate
			//logToFile("isLoggedIn() isset cookie",LOG_DEBUG_DAP);
		  	$email = $_COOKIE['dapcookie_email'];
		  	$password = decryptPassword($_COOKIE['dapcookie_password']);
			if(validate($email,$password)) {
				return TRUE;
			}
		} else {
		   	//Neither of them, so not logged in
			//logToFile("isLoggedIn() 4",LOG_DEBUG_DAP);
		  	return FALSE;
		}
	}
	
	public static function closeSession() {
		logToFile("In closeSession"); 
		// Unset cookie and all of the session variables.
		global $blogName;
		
		unset($_SESSION['dap_session']);
		unset($_SESSION['dap_url_cache']);
		unset($_SESSION['dap_list_pages_cached_already']);
		unset ($_SESSION["wpconfigpath"]);
		unset ($_SESSION["dap-wp-nosync"]);
		if( isset($_SESSION[$blogName]) && ($_SESSION[$blogName] != "") ) {
			unset ($_SESSION[$blogName]);
		}
		if( isset($_SESSION["wpUsername"]) && ($_SESSION["wpUsername"] != "") ) {
			unset ($_SESSION["wpUsername"]);
		}
		
		unset($_SESSION);
		
		$_SESSION = array();
		@setcookie("dapcookie_email", "", time()-42000, "/");
		@setcookie("dapcookie_password","", time()-42000, "/");
		$_COOKIE['dapcookie_email'] = "";
		$_COOKIE['dapcookie_password'] = "";
		//@session_start();
		@session_unset();
		@session_destroy();
	}
	
	public static function get($key) {
		if(isset($_SESSION[$key]))	return $_SESSION[$key];
		return;
	}
	
	public static function set($key, $o) {
		$_SESSION[$key] = $o;
	}
	
	public static function remove($key) {
		unset($_SESSION[$key]);
	}
	
	
	
	function getUser() {
		return $this->user;
	}
	
	function setUser($o) {
		$this->user = $o;
	}
	
	function getUserEmail() {
		return $this->user_email;
	}
	
	function setUserEmail($o) {
		$this->user_email = $o;
	}
	
	function isAdmin() {
		$user = $this->getUser();
		if(isset($user) && $user->getAccount_type() == "A") {
			//logToFile("User :".$user->getId()." Is Admin");
			return true;
		}
		//logToFile("User :  Is NOT Admin");
		return false;
	}

 	
}
?>
