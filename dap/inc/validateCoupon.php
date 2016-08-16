<?php

if( (isset($_SESSION["lldocroot"])) && ($_SESSION["lldocroot"] != ""))
	$lldocroot=$_SESSION["lldocroot"];
else if( (isset($_REQUEST["lldocroot"])) && ($_REQUEST["lldocroot"] != ""))
	$lldocroot=$_REQUEST["lldocroot"];
else
	$lldocroot = defined('SITEROOT') ? SITEROOT : $_SERVER['DOCUMENT_ROOT'];

if(file_exists($lldocroot . "/dap/dap-config.php")) include_once ($lldocroot . "/dap/dap-config.php");

$_SESSION['item_name'] = $_REQUEST['item_name'];
logToFile("validateCoupon: session item_name" . $_SESSION['item_name'], LOG_DEBUG_DAP);

if(isset($_REQUEST['payment_gateway']))
  $_SESSION['payment_gateway'] = $_REQUEST['payment_gateway'];

$_SESSION['payment_succ_page'] = $_REQUEST['payment_succ_page'];
logToFile("validateCoupon.php: is valid coupon, payment_gateway=" . $_REQUEST['payment_gateway']);

if(isset($_REQUEST['btntype']))
$_SESSION['btntype'] = $_REQUEST['btntype'];

$_SESSION['currency_symbol'] = $_REQUEST['currency_symbol'];
$_SESSION['currency'] = $_REQUEST['currency'];

logToFile("validateCoupon.php: btntype=" . $_SESSION['btntype']);

$redirect=urldecode($_REQUEST['err_redirect']);

/*if(strstr($redirect,"?")==0) {
  $url=explode("?",$redirect);	
  $redirect=$url[0];
}*/
$redirect=removeQueryStringDSH($redirect);

logToFile("validateCoupon.php: is valid coupon,  REDIRECT=" .  $redirect);

	if (isset($_REQUEST['coupon_code']) && ($_REQUEST['coupon_code'] != "")) {
		$couponCode = $_REQUEST['coupon_code'];
		logToFile("validateCoupon.php: couponCode=" . $couponCode);
		$coupon = Dap_Coupon::loadCouponByCode($couponCode); //loads coupon details from db
		if ($coupon != "") {
			$couponId=$coupon->getId();
			
			if (isset($coupon) && (isValidCoupon($coupon) == TRUE)) {
				logToFile("validateCoupon.php: is valid coupon, couponId=" . $couponId);
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
			$err = "validateCoupon.php: coupon code=" . $coupon_code . " not found";
			logToFile($err);
			$msg = "Coupon Code Not Found";
			$_SESSION['couponCode']=NULL;
			unset($_SESSION['couponCode']);
			header('Location:'.trim($redirect) . "?err_text=" . $msg);
			return;
		}
		
			
	} // coupon code set 
	
/*	//$_SESSION['new_amount'] = $coupon->getDiscount_amt();
	$num_cart_item = $_SESSION['num_cart'];
	$amt = 0;
	for($i=0;$i<$num_cart_item;$i++) { 
		$amt = $amt +  ($_SESSION['product_details'][$i]['L_AMT' . $i] * $_SESSION['product_details'][$i]['L_QTY' . $i]) ;
	} */
	$amt = 0;
	$found = 0;
	
	if ($_REQUEST['item_name'] != "") {
		
		$itemname = $_REQUEST['item_name'];
		$product = Dap_Product::loadProductByName($itemname);
		$productCoupon =	Dap_ProductCoupon::findCouponIdAndProductId($product->getId(), $couponId);
		logToFile("itemname = " . $itemname . ", couponId = " . $couponId);
		
		//See if match found for coupon and product
		if (isset($productCoupon)) {
			$found = 1;
			
			logToFile("checkout: couponCode" . $coupon_code, LOG_DEBUG_DAP);
			$discount_amt = $coupon->getDiscount_amt();
			
			if ($product->getTrial_price() > 0) {
				$amt = $product->getTrial_price() - $discount_amt;
			}
			else {
				$amt  = $product->getPrice() - $discount_amt;
			}

			logToFile("checkout.php: subscription: trial=" . $product->getTrial_price());
			logToFile("checkout.php: subscription: actual product price =" . $product->getPrice());
			logToFile("checkout: subscription: final discounted initial amount=" . $trial_amount);
		
			logToFile("checkout: subscription: recurring_discount_amt=" . $recurring_discount_amt);
			logToFile("checkout: subscription: final recurring discounted amount=" . $amount);
			
			
			logToFile("checkout.php: subscription: trial=" . $product->getTrial_price());
		
			
/*			
			$discount_amount = $coupon->getDiscount_amt();
			logToFile("itemname = " . $itemname . ", amount = " . $_REQUEST['amount'] );
			$amt = $_REQUEST['amount'] -  $discount_amount;*/

		}
		
	}
	else {	
		$num_cart_item = $_SESSION['num_cart'];
	
		for($i=0;$i<$num_cart_item;$i++) { 
			$itemname = $_SESSION['product_details'][$i]['L_NAME' . $i];
			$product = Dap_Product::loadProductByName($itemname);
			$productCoupon =	Dap_ProductCoupon::findCouponIdAndProductId($product->getId(), $couponId);
			logToFile("itemname = " . $_SESSION['product_details'][$i]['L_NAME' . $i] . ", couponId = " . $couponId);
		
			//See if match found for coupon and product
			if (isset($productCoupon)) {
				$found = 1;
				
				$discount_amount = $coupon->getDiscount_amt();
				$recur_discount_amount = $coupon->getRecurringDiscount_amt();
				
				logToFile("itemname = " . $_SESSION['product_details'][$i]['L_NAME' . $i] . ", discount amount = " . $coupon->getDiscount_amt());
				$amt = $amt +  ( ($_SESSION['product_details'][$i]['L_AMT' . $i] -  $discount_amount) * $_SESSION['product_details'][$i]['L_QTY' . $i]) ;
				$_SESSION['product_details'][$i]['L_COUPONAMT' . $i] = ($_SESSION['product_details'][$i]['L_AMT' . $i] -  $discount_amount) * $_SESSION['product_details'][$i]['L_QTY' . $i];
				
				$_SESSION['product_details'][$i]['L_RECURCOUPONAMT' . $i] = ($_SESSION['product_details'][$i]['L_RECURAMT' . $i] -  $recur_discount_amount) * $_SESSION['product_details'][$i]['L_QTY' . $i];
				
			}
			else {
				$amt = $amt +  ( ($_SESSION['product_details'][$i]['L_AMT' . $i]) * $_SESSION['product_details'][$i]['L_QTY' . $i]) ;
			}
		}
	}
	
	logToFile("final discounted amount = " . $amt);
	
	if ($found == 0) {
		$_SESSION['couponCode']=NULL;
		unset($_SESSION['couponCode']);
		
		$msg = "Sorry, coupon not associated with any product";
		
		header('Location:'.trim($redirect) . "?err_text=" . $msg);
		return;
	}
	
	if ($amt <= 0) {
		$msg = "Sorry, after discount, amount cannot be <= 0";
		$_SESSION['couponCode']=NULL;
		unset($_SESSION['couponCode']);
		header('Location:'.trim($redirect) . "?err_text=" . $msg);
		return;
	}

	$_SESSION['new_amount']  = $amt;
	
	if (!isset($_SESSION['couponCode']) || ($_SESSION['couponCode'] == ""))
	  $_SESSION['couponCode'] = $couponCode;
	else if ( (isset($_REQUEST['coupon_code'])) && ($_REQUEST['coupon_code'] != ""))
	 $_SESSION['couponCode'] = $couponCode;
	 
	logToFile("validatecoupon: SESSION['couponCode']=" .  $_SESSION['couponCode'], LOG_DEBUG_DAP);	
	$_SESSION["couponfound"]=1;
	header('Location:'.trim($redirect));
					
?>