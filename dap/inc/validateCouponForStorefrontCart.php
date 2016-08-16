<?php

$lldocroot = defined('SITEROOT') ? SITEROOT : $_SERVER['DOCUMENT_ROOT'];

if(file_exists($lldocroot . "/dap/dap-config.php")) include_once ($lldocroot . "/dap/dap-config.php");
logToFile("validateCouponForStorefrontCart.php: btntype=" . $_SESSION['btntype']);
logToFile("validateCouponForStorefrontCart.php: btntype=" . $_REQUEST[['coupon_code']);


$redirect=urldecode($_REQUEST['cart_err_redirect']);
if(strstr($redirect,"?")==0) {
  $url=explode("?",$redirect);	
  $redirect=$url[0];
}

logToFile("validateCouponForStorefrontCart.php: is valid coupon,  REDIRECT=" .  $redirect);

if (isset($_REQUEST['coupon_code']) && ($_REQUEST['coupon_code'] != "")) {
  $couponCode = $_REQUEST['coupon_code'];
  $coupon = Dap_Coupon::loadCouponByCode($couponCode); //loads coupon details from db
  if ($coupon != "") {
	$couponId=$coupon->getId();
	
	if (isset($coupon) && (isValidCoupon($coupon) == TRUE)) {
		logToFile("validateCouponForStorefrontCart.php: is valid coupon, couponId=" . $couponId);
	} //coupon found in db
	else {
	  $msg = "The Coupon Code is not valid. Either coupon start date is in future, or coupon has expired or max_usage limit reached";
	  logToFile($msg);
	  $_SESSION['couponCode']=NULL;
	  unset($_SESSION['couponCode']);
	  header('Location:'.trim($redirect) . "?err_text=" . $msg);
	  return;
	}
  }
  
  if (!isset($coupon)) {
	$err = "validateCouponForStorefrontCart.php: coupon code=" . $coupon_code . " not found";
	logToFile($err);
	$msg = "Coupon Code Not Found";
	$_SESSION['couponCode']=NULL;
	unset($_SESSION['couponCode']);
	header('Location:'.trim($redirect) . "?err_text=" . $msg);
	return;
  }
  
	  
} // coupon code set 
	
/*	//$_SESSION['storefront_newamt'] = $coupon->getDiscount_amt();
	$num_cart_item = $_SESSION['num_cart'];
	$amt = 0;
	for($i=0;$i<$num_cart_item;$i++) { 
		$amt = $amt +  ($_SESSION['product_details'][$i]['L_AMT' . $i] * $_SESSION['product_details'][$i]['L_QTY' . $i]) ;
	} */
$amt = 0;
$found = 0;

$num_cart_item = $_SESSION['storefront_num_cart'];
for($i=0;$i<$num_cart_item;$i++) { 
  $itemname = $_SESSION['storefront_product_details'][$i]['L_NAME' . $i];
  $product = Dap_Product::loadProductByName($itemname);
  $productCoupon =	Dap_ProductCoupon::findCouponIdAndProductId($product->getId(), $couponId);
  logToFile("itemname = " . $_SESSION['storefront_product_details'][$i]['L_NAME' . $i] . ", couponId = " . $couponId);

  //See if match found for coupon and product
  if (isset($productCoupon)) {
	$found = 1;
	$discount_amount = $coupon->getDiscount_amt();
	logToFile("validateCouponForStorefrontCart: product amount = " . $_SESSION['storefront_product_details'][$i]['L_AMT' . $i] . ", discount amount = " . $coupon->getDiscount_amt());
	$amt = $amt +  ( ($_SESSION['storefront_product_details'][$i]['L_AMT' . $i] -  $discount_amount) * $_SESSION['storefront_product_details'][$i]['L_QTY' . $i]) ;
  }
  else {
	$amt = $amt +  ( ($_SESSION['storefront_product_details'][$i]['L_AMT' . $i]) * $_SESSION['storefront_product_details'][$i]['L_QTY' . $i]) ;
  }
}

logToFile("final discounted amount = " . $amt);

if ($found == 0) {
  $_SESSION['couponCode']=NULL;
  unset($_SESSION['couponCode']);
  unset($_SESSION['storefront_new_amount']);
  $_SESSION['storefront_new_amount']=0;
  $msg = "Sorry, coupon not associated with any product";
  
  header('Location:'.trim($redirect) . "?err_text=" . $msg);
  return;
}

if ($amt <= 0) {
  $msg = "Sorry, after discount, amount cannot be <= 0";
  $_SESSION['couponCode']=NULL;
  unset($_SESSION['couponCode']);
  unset($_SESSION['storefront_new_amount']);
  $_SESSION['storefront_new_amount']=0;

  header('Location:'.trim($redirect) . "?err_text=" . $msg);
  return;
}

$_SESSION['storefront_new_amount']  = $amt;

if (!isset($_SESSION['couponCode']) || ($_SESSION['couponCode'] == ""))
  $_SESSION['couponCode'] = $couponCode;

logToFile("validatecoupon: SESSION['couponCode']=" .  $_SESSION['couponCode'], LOG_DEBUG_DAP);	

header('Location:'.trim($redirect));
					
?>