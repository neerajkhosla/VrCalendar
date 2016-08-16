<?php
include_once ("dap-config.php");	

	Dap_Config::loadConfig(true);
	if(isset($_GET['request']) && isset($_GET['msg'])) {
		$_GET['msg'] = htmlspecialchars($_GET['msg']);
		$_GET['request'] = htmlspecialchars($_GET['request']);		
		
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

		if ( 
			(stripos($_GET['request'], "alert")) ||
			(stripos($_GET['request'], "script")) ||
			(stripos($_GET['request'], "javascript")) ||
			(stripos($_GET['request'], "cookie")) ||
			(stripos($_GET['request'], "code")) ||
			(stripos($_GET['request'], "print")) ||
			(stripos($_GET['request'], "document")) ||
			(stripos($_GET['request'], "write"))
		) {
			$_GET['msg'] = "Sorry, invalid request";
		}

		$msg = $_GET['msg'];
		$req = $_GET['request'];
		
		$product = Dap_Product::getProductDetailsByResource($req);
		$_SESSION['request'] = $req;
		$pos = strpos($msg, "DAP006");
		if(!($pos === false)) {
			header("Location:".Dap_Config::get("LOGIN_URL")."?msg=$msg&request=$req");
		}
	}
	
?>
<html>
<head>
<title><?php echo Dap_Config::get("SITE_NAME"); ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="./inc/content/dap.css" rel="stylesheet" type="text/css">
<script>

</script>
</head>
<body bgcolor="#FFFFFF" text="#000000"> 
<p> 
<?php displayTemplate("HEADER_CONTENT"); ?> 
</p> 
<table width="400" border="0" align="center" cellpadding="5" cellspacing="0"> 
  <tr> 
    <td class="headingSuperRed"><div align="center">Oops... </div></td> 
  </tr> 
  <tr> 
    <td class="bodytextArial"> <?php
		if( isset($product) && ($product->getId() > 0) ) {?>
			<p>&nbsp;</p>
      		<p>Sorry! You do not have access to this content:</p>      <p><span class="bodytextLarge"><a href="<?php echo $req; ?>"><?php echo $req; ?></a></span></p> 
      		<p><span class="scriptheader"><?php echo stripslashes($msg); ?></span></p>
			This URL is part of the product: <br/><br/>
			<a href="<?php echo $product->getSales_page_url(); ?>"><?php echo $product->getName(); ?></a><br/>
			<?php echo $product->getDescription(); ?>
		<?php } else { ?>
        <p>&nbsp;</p>
        Sorry, this appears to be an invalid request. Please try again or contact us through the <a href="/">main web site</a> if you have any questions or concerns.
        <p>&nbsp;</p>
        <?php }
	?> </td> 
  </tr>
  <tr>
    <td class="bodytextArial"><div align="center"><a href="/dap/">Home</a></div></td>
  </tr> 
</table> 
</p> 
<?php displayTemplate("FOOTER_CONTENT"); ?> 
</body>
</html>
