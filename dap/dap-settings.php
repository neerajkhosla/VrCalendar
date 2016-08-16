<?php
// Change to E_ALL for development/debugging
//error_reporting(E_ALL & ~ (E_STRICT|E_NOTICE));
//error_reporting(E_ALL);
ini_set('session.cookie_domain',str_replace("www.", "", "." . $_SERVER['HTTP_HOST']));
//error_reporting(0);

if( function_exists("date_default_timezone_set") && function_exists("date_default_timezone_get") ) {
	if(defined('DAPTIMEZONE')) {
		@date_default_timezone_set(DAPTIMEZONE);
	} else {
		$timezone = @date_default_timezone_get();
		if( is_null($timezone) || ($timezone == "") ) {
			@date_default_timezone_set('America/Los_Angeles');
		}
	}
}

define('DAP_INC', '/inc');
define('DAP_INC_TP', '/inc/tp');
define('THIS_SCRIPT', __FILE__);

if (!defined('DAP_ROOT'))
  define('DAP_ROOT', dirname(__FILE__).'/');
  
require_once (DAP_ROOT . DAP_INC . '/config_internal.php');

if( file_exists(DAP_ROOT . DAP_INC . 'customsettings.php') ) {
	require_once (DAP_ROOT . DAP_INC . '/customsettings.php');
} else {
	require_once (DAP_ROOT . DAP_INC . '/settings.php');
}

if( file_exists(DAP_ROOT . DAP_INC . '/language/custom.php') ) {
	require_once (DAP_ROOT . DAP_INC . '/language/custom.php');
} else {
	require_once (DAP_ROOT . DAP_INC . '/language/english.php');
}

//Includes for user resource checking
require_once (DAP_ROOT . DAP_INC . '/classes/Dap_Base.class.php');
require_once (DAP_ROOT . DAP_INC . '/classes/Dap_Session.class.php');
require_once (DAP_ROOT . DAP_INC . '/classes/Dap_Config.class.php');
require_once (DAP_ROOT . DAP_INC . '/classes/Dap_CustomFields.class.php');
require_once (DAP_ROOT . DAP_INC . '/classes/Dap_UserCustomFields.class.php');
require_once (DAP_ROOT . DAP_INC . '/classes/Dap_User.class.php');
require_once (DAP_ROOT . DAP_INC . '/classes/Dap_Resource.class.php');
require_once (DAP_ROOT . DAP_INC . '/classes/Dap_FileResource.class.php');
require_once (DAP_ROOT . DAP_INC . '/classes/Dap_ExceptionHandler.class.php');
require_once (DAP_ROOT . DAP_INC . '/classes/Dap_ErrorException.class.php');
require_once (DAP_ROOT . DAP_INC . '/classes/Dap_Product.class.php');
require_once (DAP_ROOT . DAP_INC . '/classes/Dap_Coupon.class.php');
require_once (DAP_ROOT . DAP_INC . '/classes/Dap_ProductCategory.class.php');
require_once (DAP_ROOT . DAP_INC . '/classes/Dap_Category.class.php');
require_once (DAP_ROOT . DAP_INC . '/classes/Dap_ProductCoupon.class.php');
require_once (DAP_ROOT . DAP_INC . '/classes/Dap_MasterChildSSS.class.php');
require_once (DAP_ROOT . DAP_INC . '/classes/Dap_Connection.class.php');
require_once (DAP_ROOT . DAP_INC . '/classes/Dap_Log.class.php');
require_once (DAP_ROOT . DAP_INC . '/classes/Dap_Templates.class.php');
require_once (DAP_ROOT . DAP_INC_TP . '/class.phpmailer.php');
require_once (DAP_ROOT . DAP_INC . '/classes/Dap_UsersProducts.class.php');
require_once (DAP_ROOT . DAP_INC . '/classes/Dap_ProductChaining.class.php');
require_once (DAP_ROOT . DAP_INC . '/classes/Dap_SupportTicket.class.php');
require_once (DAP_ROOT . DAP_INC . '/classes/Dap_StoreFrontProducts.class.php');
require_once (DAP_ROOT . DAP_INC . '/classes/Dap_Credits.class.php');
require_once (DAP_ROOT . DAP_INC . '/classes/Dap_UserCredits.class.php');	
require_once (DAP_ROOT . DAP_INC . '/classes/Dap_EmailAlias.class.php');
	
if(defined("VBFORUMPATH"))
	require_once (DAP_ROOT . DAP_INC . '/classes/Dap_VBForum.class.php');

require_once (DAP_ROOT . DAP_INC . '/classes/Dap_Priority.class.php');
require_once (DAP_ROOT . DAP_INC . '/classes/Dap_Payment.class.php');
require_once (DAP_ROOT . DAP_INC . '/classes/Dap_API.class.php');


require_once (DAP_ROOT . DAP_INC . '/functions.php');
require_once (DAP_ROOT . DAP_INC . '/functions_payment.php');
require_once (DAP_ROOT . DAP_INC . '/functions_coupon.php');
require_once (DAP_ROOT . DAP_INC . '/function_subscription_handling.php');
require_once (DAP_ROOT . DAP_INC . '/dap_mime.php');


//Includes for user home page

//Includes for admin
//require_once (DAP_ROOT . DAP_INC . '/classes/Dap_License.class.php');
if(!defined("USER_VIEW")) {
	//require_once (DAP_ROOT . DAP_INC_TP . '/class.smtp.php');
	//require_once (DAP_ROOT . DAP_INC_TP . '/class.pop3.php');
	//require_once (DAP_ROOT . DAP_INC_TP . '/class.phpmailer.php');
	require_once (DAP_ROOT . DAP_INC . '/functions_admin.php');
	require_once (DAP_ROOT . DAP_INC . '/functions_install.php');
	require_once (DAP_ROOT . DAP_INC . '/dap_main.php');
	require_once (DAP_ROOT . DAP_INC . '/functions_email.php'); 
	require_once (DAP_ROOT . DAP_INC . '/classes/Dap_Transactions.class.php');
	require_once (DAP_ROOT . DAP_INC . '/classes/Dap_PaymentProcessor.class.php');
	require_once (DAP_ROOT . DAP_INC . '/classes/Dap_IPNProcessor.class.php');
	require_once (DAP_ROOT . DAP_INC . '/classes/Dap_ErrorHandler.class.php');
	require_once (DAP_ROOT . DAP_INC . '/classes/Dap_EmailResource.class.php');
	require_once (DAP_ROOT . DAP_INC . '/classes/Dap_UserResource.class.php');
	require_once (DAP_ROOT . DAP_INC . '/classes/Dap_AffCommissions.class.php');
	require_once (DAP_ROOT . DAP_INC . '/classes/Dap_Cron.class.php');
	require_once (DAP_ROOT . DAP_INC . '/classes/Dap_AffReferrals.class.php');
	require_once (DAP_ROOT . DAP_INC . '/classes/Dap_AffPayments.class.php');
	require_once (DAP_ROOT . DAP_INC . '/classes/Dap_BulkEmail.class.php');
	require_once (DAP_ROOT . DAP_INC . '/classes/Dap_AffStats.class.php');
	require_once (DAP_ROOT . DAP_INC . '/classes/Dap_MassActions.class.php');
	require_once (DAP_ROOT . DAP_INC . '/classes/Dap_SMTPServer.class.php');
	require_once (DAP_ROOT . DAP_INC . '/classes/Dap_UserCredits.class.php');	
	require_once (DAP_ROOT . DAP_INC . '/classes/Dap_Credits.class.php');	
	require_once (DAP_ROOT . DAP_INC . '/classes/Dap_Reports.class.php');
	require_once (DAP_ROOT . DAP_INC . '/classes/Dap_Payment.class.php');
	require_once (DAP_ROOT . DAP_INC . '/classes/Dap_FolderResources.class.php');
}


//if($_SERVER['HTTP_HOST'] != "localhost") {
	//Set session life time to be 1 year (60*60*24*365) unless user logs out
	//session_set_cookie_params (31536000, '/', str_replace("www.", "", "." . $_SERVER['HTTP_HOST']));
//}

session_set_cookie_params (1800, '/', str_replace("www.", "", "." . $_SERVER['HTTP_HOST']));

//session_start();
if (session_id() == "") @session_start();

//global $session;
$session = Dap_Session::getSession();
set_exception_handler(array('Dap_ExceptionHandler', 'handle'));
//set_error_handler("myErrorHandler");
//Dap_ErrorHandler::Initialize();
//Dap_Config::loadConfig();
 
//Transaction Status Array
$transaction_statuses = 
	array(
		"0" => "New",
		"1" => "Verified",
		"2" => "Invalid",
		"3" => "Communication Error (Reprocessible)",
		"4" => "Check Product and Price(Reprocessible)",
		"5" => "Success",
		"6" => "Error",
		"7" => "Processed Affiliations Successfully"
	);


function myErrorHandler($errno, $errstr, $errfile, $errline, $errorcontext)
{
	echo "<br>";
	echo "Error Num: $errno";
	echo "<br>";
	echo "Error Str: $errstr";
	echo "<br>";
	echo "Error File: $errfile";
	echo "<br>";
	echo "Error Line: $errline";
	echo "<br>";
	//foreach ($errorcontext as $key => $value) {
		//echo "$key : $value <br>";
	//}
}

/* 5 - default */
function logToFile($msg, $level=5, $filename="") {
  Dap_Log::log($msg, $level);
}

		
?>