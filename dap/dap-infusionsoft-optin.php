<?php

include_once ("dap-config.php");
$default_product_id = 1; //Change this to any product id from DAP

$product = Dap_Product::loadProduct($default_product_id);

if(!isset($product)) {
	header("Location:error.php?msg=Sorry, no such product.");
	exit;		
}

$post = array();

foreach ($_REQUEST as $key => $value) {
	$value = urlencode(stripslashes($value));
	logToFile("dap-infusion-optin.php: " . $key . "=" . $value);
}

$post['email'] =   $_REQUEST['Email']; // email
$post['first_name'] = $_REQUEST['FirstName'];// firtname;
$post['last_name'] = $_REQUEST['LastName']; // lastname;

$post['item_name'] = $product->getName(); // product name

$post['address1'] = $_REQUEST['StreetAddress1']; // address
$post['address2'] = $_REQUEST['Address2Street1']; // address
$post['city'] = $_REQUEST['City']; // city
$post['state'] = $_REQUEST['State']; //state
$post['zip'] = $_REQUEST['PostalCode']; //zip
$post['phone'] = $_REQUEST['Phone1']; //phone
$post['company'] = $_REQUEST['CompanyID']; //company

$post['txn_id'] =  rand(1, 1000000); 

$post['ispaid'] = "n";

if( ($post['first_name'] == "") || ($post['email'] == "") || ($post['item_name'] == "") || strpos($email, "\r") || strpos($email, "\n")	) {
	header("Location:error.php?msg=Sorry, all fields are mandatory. Please go 'back' and fill up the missing information.");
	exit;
} 
				  
$ret = registerInfusionsoftUser($post);

if ($ret == 0) {
logToFile("dap-infusionsoft-optin.php :Added " . $post['first_name'] . ":" . $post['email'] . " with ispaid status of " . $post['ispaid']);
}

$redirect = Dap_Config::get("LOGIN_URL"); // user will be redirected to the login page upon signup
$redirURL =  $redirect . "?msg=" . $msg;
header("Location: " . $redirURL);


function registerInfusionsoftUser($post) 
{
	//translate
	
	logToFile("dap-infusionsoft-optin: infusionsoft-optin", LOG_DEBUG_DAP);
		
	if ($post['item_name'] == "") {
		$dap_error = "missing product name in the infusionsoft-optin notification for the user: " . $post['email'];
		logToFile("dap-infusionsoft-optin.php: " . $dap_error, LOG_DEBUG_DAP);
		sendAdminEmail("dap-infusionsoft-optin.php: missing product name in the infusionsoft-optin notification", $dap_error);
		return -1;
	}
	
	$post['txn_type'] = "infusion-optin";
	
	//Lets try to get user and see if it exists
	$user = Dap_User::loadUserByEmail($post['email']);
	
	if(isset($user)) {
		logToFile("dap-infusionsoft-optin: User " . $post['email'] . ":" . $post['first_name'] . " exists. Checking if user is already tied to product");
		
		$product = Dap_Product::loadProductByName(trim($post['item_name']));
		$userProduct = Dap_UsersProducts::load($user->getId(), $product->getId());
		
		if( isset($userProduct) || ($userProduct != NULL) ) {
			logToFile("dap-infusionsoft-optin: User " . $post['email'] . ":" . $post['first_name'] . " already exists. user already tied to product");
			return -1;
		}
		
		$uid = Dap_UsersProducts::addUserToProduct($post['email'], $post['first_name'], $post['last_name'], $product->getId(), $ispaid, "A");
		
		return 0;
	}
	

	$user = new Dap_User();
	logToFile("dap-infusionsoft-optin :adding user: New User, FirstName:".$post['first_name']);

	$user->setFirst_name( $post["first_name"] );
	$user->setLast_name( $post["last_name"] );
	
	if (array_key_exists('address1',$post)) {
		$user->setAddress1( $post["address1"] );
	}
	
	if (array_key_exists('address2',$post)) {
		$user->setAddress2( $post["address2"] );
	}
	
	if(array_key_exists('city',$post)) {
		$user->setCity( $post["city"] );
	} 

	if(array_key_exists('state',$post)) {
		$user->setState( $post["state"] );
	}
	
	if(array_key_exists('zip',$post)) {
		$user->setZip( $post["zip"] );
	} 
	
	if(array_key_exists('country',$post)) {
		$user->setCountry( $post["country"] );
	}
	
	if(array_key_exists('phone',$post)) {
		$user->setPhone( $post["phone"] );
	} 
	if(array_key_exists('fax',$post)) {
		$user->setFax( $post["fax"] );
	} 
	if(array_key_exists('company',$post)) {
		$user->setCompany( $post["company"] );
	} 
			
	$user->setEmail( $post["email"]);		
	$user->setStatus("A");
	
	$user->create();
	
	if (($post['ispaid'] != '') && (($post['ispaid'] == 'Y') || ($post['ispaid'] == 'y')))
		$ispaid =  "y";
	else 
		$ispaid = "n";
	
	logToFile("dap-infusionsoft-optin.php :added " . $post['email'].":".$post['first_name']);
	
	$product = Dap_Product::loadProductByName(trim($post['item_name']));
	if( !isset($product) || ($product == NULL) ) {
		logToFile("dap-infusionsoft-optin.php: Added user " . $post['email'] . ":" . $post['first_name'] . "but Product " . $post['item_name'] . " not found in DAP");
		
		return -1;
	}
	
	$uid = Dap_UsersProducts::addUserToProduct($post['email'], $post['first_name'], $post['last_name'], $product->getId(), $ispaid, "A");

	logToFile("dap-infusionsoft-optin.php :added user to product " . $uid.":".$post['item_name']);
	
	return 0;
}

?>