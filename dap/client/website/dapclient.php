<?php
define('USER_VIEW', 'Y');
include_once dirname(__FILE__) . "/../../dap-config.php";
//$url = $_SERVER['REQUEST_URI'];
//DAPREF - gets the .htaccess altered resource where as REQUEST_URI is the original user request.
// for request like /xyx/ - directory request, REQUEST_URI  doesnt work. We have to take the DAPREF. 
$url = $_GET['dapref']; //$_SERVER['REQUEST_URI'];
$url = getResourceFromString($url); //getResourceFromUserRequest(); //$_GET['dapref']; //$_SERVER['REQUEST_URI'];
//$url = $_SERVER['REQUEST_URI'];
$uri = $_SERVER['REQUEST_URI'];
$file = $_SERVER['SCRIPT_FILENAME'];
//echo $file;
//logToFile("DAP WebSite Plugin..");
//logToFile("Request Coming In: Original URI:".$uri);
//logToFile("Request Coming In: Modified URI:".$url);
//logToFile("Request Coming In: File:".$file);
//logToFile("Request Coming In: Query String:".$_SERVER['QUERY_STRING']);

logToFile("About to call isURLAllowed from dapclient.php"); 
$resource = isURLAllowed($url);
$errId = $resource->getError_id();
if(!isset($errId)) {
	$session = Dap_Session::getSession();
	$user = $session->getUser(); 
	if( isset($user) && !is_null($user) ) {
		Dap_Resource::isResourceClickCountOK($user->getId(), $requestUrl);
	}	
	serveFile($url);
} else {
	$url = $resource->getError(1) . "?msg=" . $resource->getError(0) . "&request=" . $url;
	header("Location: $url");
	return;
}

?>
