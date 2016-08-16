<?php

$customfieldlist=array();

class Dap_API extends Dap_Base {

	
	/**
		This function returns true or false
		Checks if currently logged in user has access to given products
	*/
	public static function hasAccessTo($productId) {
		$hasAccess = false;
		if( Dap_Session::isLoggedIn() ) { 
			//get userid
			$session = Dap_Session::getSession();
			$user = $session->getUser();
			$hasAccess = $user->hasAccessTo($productId);
		}
		return $hasAccess;
	}
	
	
	public static function hasAccessToProducts($productIds) {
		$hasAccess = false;
		if( Dap_Session::isLoggedIn() ) { 
			//get userid
			$session = Dap_Session::getSession();
			$user = $session->getUser();
			$hasAccess = $user->hasAccessToProducts($productIds);
		}
		return $hasAccess;
	}	
	
	public static function loadCustomFieldsForUser($user) {	
	  //$session = Dap_Session::getSession();
	  //$user = $session->getUser();
	  $userId=$user->getId();
	  
	  $customFields = Dap_CustomFields::loadUserFacingCustomFields();
	  foreach ($customFields as $custom) {
	//	logToFile("DAP_API.class.php: loadCustomFieldsForUser(): id=" . $custom['id']);
	//	logToFile("DAP_API.class.php: loadCustomFieldsForUser(): userId=" . $userId);
		
		if ($custom['showonlytoadmin'] == "Y") {
		  //if (!$session->isAdmin())  continue;
		  continue;
		}
		//
		
		$user_custom_value = Dap_UserCustomFields::loadUserCustomFieldsByCustomFieldId($custom['id'], $userId);
		$value = "";
		if ($user_custom_value) {
		  foreach ($user_custom_value as $val) {
			$name=$custom['name'];
			$value= $val['custom_value'];
			$customfieldlist[$name] = $value;	
			logToFile("DAP_API.class.php: loadCustomFieldsForUser(): name=" . $name . ", val=" . $val['custom_value']);			  			
		  }
		}
	  } //end foreach
	  return $customfieldlist;
	  
	} //end retrieveCustomFieldForUser()
	
}
?>