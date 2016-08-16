<?php 

add_shortcode('DAPUpline', 'dap_upline');

/**
	Basically, when a user visits the page that has this shortcode, 
	shortcode will display information of that user's AFFILIATE 
	(or ADMIN, if no affiliate).

	[DAPUpline showField="first_name"] - To show affiliate's first name, use this
	[DAPUpline showField="last_name"]
	[DAPUpline showField="user_name"]
	[DAPUpline showField="email"]
	[DAPUpline showField="address1"]
	[DAPUpline showField="address2"]
	[DAPUpline showField="city"]
	[DAPUpline showField="state"]
	[DAPUpline showField="zip"]
	[DAPUpline showField="country"]
	[DAPUpline showField="phone"]
	[DAPUpline showField="fax"]
	[DAPUpline showField="company"]
	[DAPUpline showField="title"]
	[DAPUpline showField="paypal_email"]

	For Custom fields...
	[DAPUpline showField="custom_<customfieldname>"]
	
	So, to show "cbnick" field of affiliate to the user, do this:
	[DAPUpline showField="custom_cbnick"]
	Custom field name must exactly match what you've created in DAP

	Logic for figuring out affiliate:
	1a) If user is logged in, get current assigned affiliate from DAP, if any
			If no affiliate set in db for user, skip to 2
	1b) If not logged in, then is visitor
			See if there's an affiliate cookie set, if yes, get id from that
				If not, skip to 2
	
	2) Nothing matched, so just get admin's user id and use that.
	
	3) Whatever id is selected, it's that user's information that is displayed back on the page
	
	
	- END - 

	[DAPUpline showWho="Parent" showField="first_name"] - for later - not used currently

*/

function dap_upline($atts, $content=null){ 
	extract(shortcode_atts(array(
		'showwho' => 'Parent', //not used currently
		'showfield' => 'first_name',
	), $atts));
	
	//$content = do_shortcode(dap_clean_shortcode_content($content));	
	$session = Dap_Session::getSession();
	$user = $session->getUser();
	$userId = 0;
	//$content = "";
	$userIdUpline = 0;
	
	if(isset($user)) { 
		//If user is logged in, get affiliate id who referred this user
		//If user doesn't have an affiliate, get admin id
		$user = Dap_User::loadUserById($user->getId());
		$userIdUpline = Dap_User::getAffiliateForUser($user->getId());
		//logToFile("userIdUpline: " . $userIdUpline); 
	} else {
		//user not logged in - just a visitor
		//Check to see if there's a cookie
		if(isset($_COOKIE['dapa'])) {
			$userIdUpline = $_COOKIE['dapa'];
		}
	}

	$userAdminObj = Dap_User::loadAdminUserByMinId();
	$userIdAdmin = $userAdminObj->getId();
	$userAdmin = Dap_User::loadUserById($userIdAdmin);
	$usingAdmin = false;

	//If nothing matched, then no affiliate found for user, use admin id
	if( is_null($userIdUpline) || ($userIdUpline == 0) ) { 
		$userIdUpline = $userAdmin->getId();
		$usingAdmin = true;
	}

	$userUpline = Dap_User::loadUserById($userIdUpline);
	
	if(strtolower($showfield) == "full_name") {
		$content = $userUpline->getFirst_name() . " " . $userUpline->getLast_name();
	}
	//If not already using Admin data, and there is an affiliate,
	//but affiliate field is empty, then load respective Admin field
	if ( !$usingAdmin && ($content == "") ) {
		$content = $userAdmin->getFirst_name() . " " . $userAdmin->getLast_name();
	}


	if(strtolower($showfield) == "first_name") {
		$content = $userUpline->getFirst_name();
	}
	if ( !$usingAdmin && ($content == "") ) {
		$content = $userAdmin->getFirst_name();
	}
	
	
	
	if(strtolower($showfield) == "last_name") {
		$content = $userUpline->getLast_name();
	}
	if ( !$usingAdmin && ($content == "") ) {
		$content = $userAdmin->getLast_name();
	}
	
	
	
	if(strtolower($showfield) == "email") {
		$content = $userUpline->getEmail();
	}
	if ( !$usingAdmin && ($content == "") ) {
		$content = $userAdmin->getEmail();
	}
	
	
	
	if(strtolower($showfield) == "user_name") {
		$content = $userUpline->getUser_name();
	}if ( !$usingAdmin && ($content == "") ) {
		$content = $userAdmin->getUser_name();
	}
	
	
	
	if(strtolower($showfield) == "address1") {
		$content = $userUpline->getAddress1();
	}
	if ( !$usingAdmin && ($content == "") ) {
		$content = $userAdmin->getAddress1();
	}
	
	
	
	if(strtolower($showfield) == "address2") {
		$content = $userUpline->getAddress2();
	}
	if ( !$usingAdmin && ($content == "") ) {
		$content = $userAdmin->getAddress2();
	}
	
	
	
	if(strtolower($showfield) == "city") {
		$content = $userUpline->getCity();
	}
	if ( !$usingAdmin && ($content == "") ) {
		$content = $userAdmin->getCity();
	}
	
	
	
	if(strtolower($showfield) == "state") {
		$content = $userUpline->getState();
	}
	if ( !$usingAdmin && ($content == "") ) {
		$content = $userAdmin->getState();
	}
	
	
	
	if(strtolower($showfield) == "zip") {
		$content = $userUpline->getZip();
	}
	if ( !$usingAdmin && ($content == "") ) {
		$content = $userAdmin->getZip();
	}
	
	
	
	if(strtolower($showfield) == "country") {
		$content = $userUpline->getCountry();
	}
	if ( !$usingAdmin && ($content == "") ) {
		$content = $userAdmin->getCountry();
	}
	
	
	
	if(strtolower($showfield) == "phone") {
		$content = $userUpline->getPhone();
	}
	if ( !$usingAdmin && ($content == "") ) {
		$content = $userAdmin->getPhone();
	}
	
	
	
	if(strtolower($showfield) == "fax") {
		$content = $userUpline->getFax();
	}
	if ( !$usingAdmin && ($content == "") ) {
		$content = $userAdmin->getFax();
	}
	
	
	
	if(strtolower($showfield) == "company") {
		$content = $userUpline->getCompany();
	}
	if ( !$usingAdmin && ($content == "") ) {
		$content = $userAdmin->getCompany();
	}
	
	
	
	if(strtolower($showfield) == "paypal_email") {
		$content = $userUpline->getPaypal_email();
	}
	if ( !$usingAdmin && ($content == "") ) {
		$content = $userUpline->getPaypal_email();
	}
	
	if(stripos($showfield,"custom_") !== false) {
		$customFields = Dap_CustomFields::loadUserFacingCustomFields();
		foreach ($customFields as $custom) {
			//logToFile("userprofile.inc.php: loadCustomFields(): id =" . $custom['id']);
			//logToFile("userprofile.inc.php: loadCustomFields(): userId =" . $userId);
			$customFieldName = str_replace("custom_","",$showfield);
			//logToFile("customFieldName: " . $customFieldName); 

			if( ($custom['showonlytoadmin'] == "Y") || ($customFieldName != $custom["name"]) ){
				continue;
			}

			$user_custom_value = Dap_UserCustomFields::loadUserCustomFieldsByCustomFieldId($custom['id'], $userUpline->getId());
			$value = "";
			
			if ($user_custom_value) {
				foreach ($user_custom_value as $val) {
					//logToFile("val=" . $val['custom_value']);
					$value = $val['custom_value'];
				}
			}
			
			
			if(trim($value) != "") {
				$content = $value;
			} else if($value == "") {
				foreach ($customFields as $custom) {
					//logToFile("userprofile.inc.php: loadCustomFields(): id =" . $custom['id']);
					//logToFile("userprofile.inc.php: loadCustomFields(): userId =" . $userId);
					$customFieldName = str_replace("custom_","",$showfield);
					//logToFile("customFieldName: " . $customFieldName); 

					if( ($custom['showonlytoadmin'] == "Y") || ($customFieldName != $custom["name"]) ){
						continue;
					}
					
					$user_custom_value = Dap_UserCustomFields::loadUserCustomFieldsByCustomFieldId($custom['id'], $userIdAdmin);
					$value = "";
					
					if ($user_custom_value) {
						foreach ($user_custom_value as $val) {
							//logToFile("val=" . $val['custom_value']);
							$value = $val['custom_value'];
						}
					}
					
					$content = $value;
				} //end foreach	
			} //end if value==""
		} //end foreach	
	}
	
	return $content;
}


?>