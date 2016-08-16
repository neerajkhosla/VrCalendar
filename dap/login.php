<?php 
	include_once ("dap-config.php");
	include_once ("inc/content/login_header.inc.php"); 
	
	if( isset($_GET['msg']) && ($_GET['msg'] != "") ) {
		$_GET['msg'] = htmlspecialchars($_GET['msg']);
		if ( 
			(stripos($_GET['msg'], "alert")) ||
			(stripos($_GET['msg'], "script")) ||
			(stripos($_GET['msg'], "javascript")) ||
			(stripos($_GET['msg'], "cookie")) ||
			(stripos($_GET['msg'], "code")) ||
			(stripos($_GET['msg'], "print")) ||
			(stripos($_GET['msg'], "document")) ||
			(stripos($_GET['msg'], "write"))
		) {
			$_GET['msg'] = "Sorry, this is an invalid request";
		}
		if( defined($_GET['msg']) ) {
			$_GET['msg'] = constant($_GET['msg']);
		} else {
			$_GET['msg'] = str_replace("_"," ",$_GET['msg']);
		}
		if(isset($_GET['email'])) $_GET['msg'] = $_GET['msg'] . " " . $_SESSION['email'];
	}
	
?>
<html>
<head>
<title>Login - Powered by DigitalAccessPass.com</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link href="inc/content/dap.css" rel="stylesheet" type="text/css">
</head>
<body bgcolor="#FFFFFF" text="#000000"> 
<?php displayTemplate("HEADER_CONTENT"); ?> 
<?php include_once ("inc/content/login.inc.php"); ?>
<p>&nbsp;</p> 
<p> 
<?php displayTemplate("FOOTER_CONTENT"); ?> 
</p> 
</body>
</html>
