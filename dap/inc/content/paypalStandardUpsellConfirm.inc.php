<?php
	$lldocroot = defined('SITEROOT') ? SITEROOT : $_SERVER['DOCUMENT_ROOT'];
	if(file_exists($lldocroot . "/dap/dap-config.php")) include_once ($lldocroot . "/dap/dap-config.php");
	
	$paypalstandardupsellpagetemplate=$_SESSION['paypalstandardupsellpagetemplate'];
		
	$blogpath=urldecode($_SESSION["blogpath"]);
	$csspath=$blogpath."/wp-content/plugins/paypalstandardupsell/templates/" . $paypalstandardupsellpagetemplate . "/includes/css/";
	
	logToFile("paypalStandardUpsellConfirm.inc:blogpath = ". $blogpath);
	
	$formcss=$csspath."paypalstandardupsell.css";
	$customcss=$csspath."custompaypalstandardupsell.css";
	
	logToFile("paypalStandardUpsellConfirm.inc:formcss = ". $formcss);
	logToFile("paypalStandardUpsellConfirm.inc:formcss = ". $customcss);
//	logToFile("paypalStandardUpsellConfirm.inc:PAYER EEMAIL = ". $_SESSION['paymentObj']->getEmail());

	if(file_exists($customcss)) {
		$templatecss = $_SESSION['customtemplatecss'];
	}
	else {
		$templatecss = $_SESSION['templatecss'];
	}
	
	$showproductimage=$_SESSION["showproductimage"];
	$blogpath=urldecode($_SESSION["blogpath"]);
	
	if($showproductimage=="YES") {
		$template_path=$blogpath."/wp-content/plugins/paypalstandardupsell/templates/" . $paypalstandardupsellpagetemplate . "/custompaypalstandardupsellpagetemplate.html";
		if(file_exists($template_path)==FALSE) {
		  $template_path=$blogpath."/wp-content/plugins/paypalstandardupsell/templates/" . $paypalstandardupsellpagetemplate . "/paypalstandardupsellpagetemplate.html";
		}
	}
	else {
		$template_path=$blogpath."/wp-content/plugins/paypalstandardupsell/templates/" . $paypalstandardupsellpagetemplate . "/custompaypalstandardupsellpagetemplatenoimage.html";
		if(file_exists($template_path)==FALSE) {
		 $template_path=$blogpath."/wp-content/plugins/paypalstandardupsell/templates/" . $paypalstandardupsellpagetemplate . "/paypalstandardupsellpagetemplatenoimage.html";
		}
	}
	
	logToFile("paypalStandardUpsellConfirm.inc:template_path for page NAME=" . $template_path);
	$product_template = file_get_contents($template_path);
	
	$template_path=$blogpath."/wp-content/plugins/paypalstandardupsell/templates/" . $paypalstandardupsellpagetemplate . "/custompaypalstandardupsellbuttontemplate.html";	
	if(file_exists($template_path)==FALSE) {
		$template_path=$blogpath."/wp-content/plugins/paypalstandardupsell/templates/" . $paypalstandardupsellpagetemplate . "/paypalstandardupsellbuttontemplate.html";
	}
	
	logToFile("paypalStandardUpsellConfirm.inc:template_path for button, NAME=" . $template_path);
	
	$button_template = file_get_contents($template_path);
	
	if (!isset($_SESSION['num_cart'])) 
	  $_SESSION['num_cart'] = 0;
	if (!isset($_SESSION['recur_count'])) 
	  $_SESSION['recur_count'] = 0;	
	  
	$atleastoneitemincart=0;
  
  	//$templatecss = $_SESSION['templatecss'];
	/*$templatecss = $_SESSION['templatecss'];
	$customtemplatecss = $_SESSION['customtemplatecss'];
  
  	$customtemplatecss = "http://techizens.com/wp-content/plugins/paypalstandardupsell/templates/template1/includes/css/custompaypalstandardupsell.css";
	if(isset($customtemplatecss)) {
		if(file_exists($customtemplatecss)) {
	  		$templatecss = $customtemplatecss;
	  		logToFile("paypalStandardUpsellConfirm.inc: TAKING custom css: ". $templatecss);	
  		}
	}
	else if(isset($templatecss)) {
		$parse = parse_url($templatecss);
		$templatecss = $parse["path"];
		logToFile("paypalStandardUpsellConfirm.inc: TAKING NON custom css=" . $templatecss);
	}*/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<title>Checkout Confirmation</title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="<?php echo $templatecss; ?>" />

<script type='text/javascript' language='javascript'>

function processUpsell() {
	//alert("in procss upsell");
	document.getElementById('checkout').style.display = 'none';
<?php

//logToFile("paypalStandardUpsellConfirm.inc:processUpsell:PAYER EEMAIL = ". $_SESSION['paymentObj']->getEmail());

if (isset($_SESSION['num_cart']) && $_SESSION['num_cart'] != 0) {
?>	
document.formpaypalstandardupsell.submit();
<?php
}
else {
?>
var buttonSub = document.getElementById('btnSubmit');
buttonSub.disabled = true; 
<?php	
}
?>
document.getElementById('pleasewait').style.display = 'block';
}
function processCancel(cancelurl) {
	<?php
	logToFile("paypalStandardUpsellConfirm.inc:PAYER EEMAIL = ". $_SESSION['payment_cancel_page']);

	?>
document.formpaypalstandardupsell.action = "/dap/PaypalUpsellCancel.php?cancelurl="+cancelurl;
document.formpaypalstandardupsell.submit();
}

</script>


</head>

<body>
<?php displayTemplate("HEADER_CONTENT"); ?>

<center>
<div id="errorText">
<?php 
$err_msg = $_REQUEST['msg'];
if ( ($err_msg == "") && (isset($_SESSION['msg']) && ($_SESSION['msg'] != "")) ) {
  $err_msg = $_SESSION['msg'];
  unset($_SESSION['msg']);
}
?>
</div>
       
<form name="formpaypalstandardupsell" id="formpaypalstandardupsell" autocomplete="off" method="post" action="/dap/PaypalExpressCheckoutBuyNow.php">	
<?php 
  useTemplate1ConfirmationPage($product_template, $button_template);
?>
<input type="hidden" name="payment_succ_page" value="<?php echo $_SESSION["payment_succ_page"];?>" />
</form>
</center>
</body>
</html>

<?php
function useTemplate1ConfirmationPage($product_template, $button_template) {
	$showproductimage=$_SESSION["showproductimage"];
	if($showproductimage=="YES") {

?>	

    
<table cellspacing="0" cellpadding="0" border="0" class="cartSummary">
	<tr>
		<td colspan="4" class="cartHeader">Cart Summary</td>
	</tr>
	<tr class="summaryHeader">
		<td class="prodImg">Product</td>
		<td class="prodName">Name/ Description</td>
		<td class="prodAmount">Amount</td>
		<td class="iconDelete">&nbsp;</td>
	</tr>

<?php	
}
else {
?>
    
<table cellspacing="0" cellpadding="0" border="0" class="cartSummary">
	<tr>
		<td colspan="4" class="cartHeader">Cart Summary</td>
	</tr>
	<tr class="summaryHeader">
		<td class="prodImg">Product Name</td>
		<td class="prodName">Description</td>
		<td class="prodAmount">Amount</td>
		<td class="iconDelete">&nbsp;</td>
	</tr>

<?php 
}


$atleastoneitemincart=0;
logToFile("ENTER useTemplate1ConfirmationPage()");	

logToFile("paypalStandardUpsellConfirm.inc upsellsuccesspage=" . $_SESSION["payment_succ_page"]);
logToFile("paypalStandardUpsellConfirm.inc upsellcancelpage=" . $_SESSION["payment_cancel_page"]);
  
$num_cart_item = $_SESSION['num_cart'];

logToFile("paypalStandardUpsellConfirm.inc:num_cart_item=" . $num_cart_item);
$save_template = $product_template;
if(isset($_SESSION["productnoimg"]) && ($_SESSION["productnoimg"] !=''))
{
$productnoimg=$_SESSION["productnoimg"];
}
else
{
$productnoimg='/dap/images/noimagesfound.jpg';
}
for($i=0;$i<$num_cart_item;$i++) { 
  $product_template = $save_template;
  
  logToFile("paypalStandardUpsellConfirm.inc:ITEMNAME=" . $_SESSION['product_details'][$i]['L_NAME' . $i]);
  
  
  $atleastoneitemincart++;
  $name = $_SESSION['product_details'][$i]['L_NAME' . $i];
  $product = Dap_Product::loadProductByName(trim($name));
  
 if($product) {
  $image_src = $product->getProduct_image_url();
	if(isset($image_src) &&($image_src !=''))
	{
		$image_src=$image_src;
	}
	else
	{
		$image_src=$productnoimg;
	}
  }
  
  logToFile("paypalStandardUpsellConfirm.inc:image_src=" . $image_src);
  $product_template = str_replace( '[IMAGESRC]', $image_src, $product_template);  
  
  if ($_SESSION['showremove'] == "N"){
	$product_template = str_replace( '[DELETEICON]', "", $product_template); 
	$product_template = str_replace( '[IMAGETAG]','', $product_template);    
	$product_template = str_replace( '[DELETEIMAGESRC]', "", $product_template);     
  }
  else {
	$product_template = str_replace( '[IMAGETAG]','<img src="[DELETEIMAGESRC]" alt="" />', $product_template);    
	$product_template = str_replace( '[DELETEIMAGESRC]', $_SESSION["delete_image_src"], $product_template);  
  	$product_template = str_replace( '[DELETEICON]', "iconDelete", $product_template);     
  }
  
  $product_template = str_replace( '[ITEMNAME]',  $_SESSION['product_details'][$i]['L_NAME' . $i], $product_template); 
  $product_template = str_replace( '[REDIRECT]', $_SERVER['REQUEST_URI'], $product_template); 
  $product_template = str_replace( '[ITEMDESCRIPTION]',  $_SESSION['product_details'][$i]['L_DESC' . $i], $product_template); 
  $product_template = str_replace( '[ITEMPRICE]', trim(Dap_Config::get('CURRENCY_SYMBOL')) . number_format($_SESSION['product_details'][$i]['L_AMT' . $i], 2, '.', '') , $product_template); 	  
  
  echo $product_template;
}

$amt = 0;
for($i=0;$i<$num_cart_item;$i++) { 
  $amt = $amt +  $_SESSION['product_details'][$i]['L_AMT' . $i];
}

logToFile("paypalStandardUpsellConfirm.inc:CHECKOUT IMAGE=" . $_SESSION["checkoutimage"]);
logToFile("paypalStandardUpsellConfirm.inc:CANCEL IMAGE=" . $_SESSION["cancelimage"] );
logToFile("paypalStandardUpsellConfirm.inc:pleasewaitimage IMAGE=" . $_SESSION["pleasewaitimage"]);

$button_template = str_replace( '[CHECKOUTIMG]',  $_SESSION["checkoutimage"], $button_template); 
$button_template = str_replace( '[PLEASEWAITIMG]',  $_SESSION["pleasewaitimage"], $button_template); 

//$button_template = str_replace( '[CANCELIMG]',  $_SESSION["cancelimage"], $button_template); 
if ($_SESSION['showcancelimage'] == "N"){
$cancelimage='';
$button_template = str_replace( '[CANCELBUTTON]',$cancelimage  , $button_template); 
$button_template = str_replace( '[CANCELIMG]',  "", $button_template); 
}
else
{
$cancelimage ='<input type="image" src="[CANCELIMG]" id="cancel" onclick=processCancel(' . "'[CANCELPAGE]'" . '); ';
$cancelimage .=  " 'width='89' height='28'  value='Cancel' class='btnCancel'/>";
$button_template = str_replace( '[CANCELBUTTON]',$cancelimage  , $button_template); 
$button_template = str_replace( '[CANCELIMG]',  $_SESSION["cancelimage"], $button_template); 
}
$button_template = str_replace( '[CARTTOTAL]',  trim(Dap_Config::get('CURRENCY_SYMBOL')) . number_format($amt, 2, '.', '') , $button_template); 
$button_template = str_replace( '[SUCCPAGE]',  $_SESSION["payment_succ_page"], $button_template); 
$button_template = str_replace( '[CANCELPAGE]', $_SESSION["payment_cancel_page"], $button_template); 

echo $button_template;
?>

</table>
<?php
logToFile("EXIT useTemplate1ConfirmationPage()");	
return $atleastoneitemincart;
} // end function
?>