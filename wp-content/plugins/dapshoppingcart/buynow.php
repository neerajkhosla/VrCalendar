<?php 
/*	
	1. read product info from products
	2. read post params into variables
	3. if missing form input, replace from products
	4. if no session, display form with pre-filled values and editable text box
	5. if session, submit to paymentsubmit.php
*/

$content="";
$lldocroot = defined('SITEROOT') ? SITEROOT : $_SERVER['DOCUMENT_ROOT'];
if(file_exists($lldocroot . "/dap/dap-config.php")) include_once ($lldocroot . "/dap/dap-config.php");
if(file_exists($lldocroot . "/dap/inc/classes/Dap_CartOptions.class.php")) 
	include_once ($lldocroot . "/dap/inc/classes/Dap_CartOptions.class.php");

if(file_exists($lldocroot . "/dap/securimage.php")) include_once ($lldocroot . "/dap/securimage.php");
if(file_exists($lldocroot . "/dap/set-payment-params-buynow.php")) include($lldocroot . "/dap/set-payment-params-buynow.php");

$_SESSION["GA_PURCHASE_EMAIL"]="";
$_SESSION["GA_TXN_ID"]="";

if( (isset($_SESSION["gatracking"])) && ($_SESSION["gatracking"]!="")) {
  $gatracking=$_SESSION["gatracking"];
  $_SESSION["gatracking"]="";
}
else if( (isset($_REQUEST["gatracking"])) && ($_REQUEST["gatracking"]!="")) {
  $gatracking=$_REQUEST["gatracking"];
}

//$btntype = $_REQUEST['btntype'];
logToFile("buynow.php: _REQUEST: btntype=".$_REQUEST['btntype'],LOG_DEBUG_DAP);	
logToFile("buynow.php: ENTER buynow.. num_cart=".$_SESSION['num_cart'],LOG_DEBUG_DAP);	

clearCartSession();
$createValidationList="";

//logToFile("buynow.php: _REQUEST:clearCartSession complete, numcart=".$_SESSION['num_cart']. ", $item_name=".$item_name  ,LOG_DEBUG_DAP);	
if (  ((!isset($_SESSION['num_cart'])) || ($_SESSION['num_cart'] == 0)) && ( ($item_name == "") && ($item_number=="")) ){
	
logToFile("buynow.php: _REQUEST:set error" ,LOG_DEBUG_DAP);	
	$error_url = "Sorry, this page should not be accessed directly. It should only be accessed via a Payment Link/Button";
	return $error_url;
	exit;
		
}

logToFile("buynow.php: _REQUEST:set lldocroot" ,LOG_DEBUG_DAP);	


$_SESSION["lldocroot"]=$lldocroot;
logToFile("buynow.php: _REQUEST: lldocroot=".$_SESSION['lldocroot'],LOG_DEBUG_DAP);

if(isset($_REQUEST['btntype']))
  $_SESSION['btntype'] = $_REQUEST['btntype'];
  
if (!isset($btntype) || ($btntype == "")) 
	$btntype = $_SESSION['btntype'];


if(isset($_REQUEST["stripe_instant_recurring_charge"])) {
		$stripe_instant_recurring_charge = $_REQUEST["stripe_instant_recurring_charge"];
		$_SESSION["stripe_instant_recurring_charge"]=$stripe_instant_recurring_charge;
		logToFile("buynow.php: request: hidden field: stripe_instant_recurring_charge==" .$_REQUEST["stripe_instant_recurring_charge"]);
}
else if(isset($_SESSION["stripe_instant_recurring_charge"])) {
		$stripe_instant_recurring_charge = $_SESSION["stripe_instant_recurring_charge"];
		logToFile("buynow.php: session: hidden field: stripe_instant_recurring_charge==" .$_SESSION["stripe_instant_recurring_charge"]);
}


	
if ($_REQUEST['currency_symbol'] == "") {
	if ($_SESSION['currency_symbol'] != "") {
		$currency_symbol=$_SESSION['currency_symbol'];
		$currency = $_SESSION['currency'];
		logToFile("buynow.php: session currency_symbol=".$currency_symbol,LOG_DEBUG_DAP);
		logToFile("buynow.php: session currency=".$currency,LOG_DEBUG_DAP);
	}
	else {
		$currency_symbol="$";
		$currency=Dap_Config::get('CURRENCY_TEXT');
	    logToFile("buynow.php: currency_symbol=".$currency_symbol,LOG_DEBUG_DAP);
	}
}
else {
	$currency_symbol=$_REQUEST['currency_symbol'];
	$currency = $_REQUEST['currency'];
}

logToFile("buynow.php: currency_symbol=".$currency_symbol,LOG_DEBUG_DAP);	
logToFile("buynow.php: currency=".$currency,LOG_DEBUG_DAP);	

if ((!isset($btntype)) || ((isset($btntype) && strcmp($btntype, 'buynow') != 0))) 
	$productId = -1;

if(isset($paypalimg) && ($paypalimg !='')) 
	$paypalimg=$paypalimg;
else
	$paypalimg='/dap/images/checkout/btn_paypalb.png';

if(isset($firstimg) && ($firstimg !=''))
	$firstimg=$firstimg;
else
	$firstimg='';

if(isset($secondimg) && ($secondimg !=''))
	$secondimg=$secondimg;
else
	$secondimg='';
	
$stripePublishableKey=Dap_Config::get('STRIPE_PUBLISH_KEY');
//logToFile("buynow.php: stripePublishableKey=".$stripePublishableKey,LOG_DEBUG_DAP);	
//logToFile("buynow.php: productId=".$productId,LOG_DEBUG_DAP);	
//logToFile("buynow.php: current session amount=".$_SESSION['new_amount'],LOG_DEBUG_DAP);	
//logToFile("buynow.php: current session couponCode =".$_SESSION['couponCode'],LOG_DEBUG_DAP);	

if (($_REQUEST['is_submitted'] == "Y") && (strcmp($btntype, 'buynow') == 0)) {
  //logToFile("buynow.php: UNSET SESSION",LOG_DEBUG_DAP);	

  unset($_SESSION['new_amount']);
  unset($_SESSION['couponCode']);
  unset($_SESSION['num_cart']);
  unset($_SESSION['currency']);
  unset($_SESSION['currency_symbol']);
  $_SESSION['currency_symbol']="";
  $_SESSION['currency']="";
}


//logToFile("buynow.php: new session amount=".$_SESSION['new_amount'],LOG_DEBUG_DAP);	
$loggedIn=false;
if( Dap_Session::isLoggedIn() ) { 
	//get userid
	$session = Dap_Session::getSession();
	$user = $session->getUser();
	$user = Dap_User::loadUserById($user->getId()); //reload User object
	if(isset($user)) {
	  $sessionemail=$user->getEmail();
	  $sessionfirstname=$user->getFirst_name();
	  $sessionlastname=$user->getLast_name();
	  $sessionaddress1=$user->getAddress1();
	  $sessioncity=$user->getCity();
	  $sessionzip=$user->getZip();
	  $sessionstate=$user->getState();
	  $sessioncountry=$user->getCountry();
	  $sessioncompany=$user->getCompany();
	  $sessionphone=$user->getPhone();
/*	  $sessionshiptofirstname=$user->getShip_to_first_name();
	  $sessionshiptolastname=$user->getShip_to_last_name();
	  $sessionshiptaddress1=$user->getShip_to_address1();
	  $sessionshiptaddress2=$user->getShip_to_address2();
	  $sessionshiptocity=$user->getShip_to_city();
	  $sessionshiptostate=$user->getShip_to_state();
	  $sessionshiptozip=$user->getShip_to_zip();*/
	  $loggedIn=true;
	}
}

$paypal_business_email = trim(Dap_Config::get('PAYPAL_BUS_EMAIL'));
$notify_url = SITE_URL_DAP . "/dap/dap-paypal.php";

$siteurlhttps=SITE_URL_DAP;

if ($_SERVER["HTTPS"] == "on") {
 $siteurlhttps = str_replace ( "http:", "https:", $siteurlhttps );
// logToFile("buynow.php:HTTPS is on");
}

if ( (isset($_REQUEST["blogpath"])) && ( $_REQUEST["blogpath"] != "" ))
	$blogpath=$_REQUEST["blogpath"];

if($blogpath == "")
	$blogpath=$_SESSION["blogpath"];

	
if(isset($pagetemplate) && ($pagetemplate !=''))
		$template=$pagetemplate;
else {
	if( (isset($_SESSION['template'])) && ($_SESSION['template'] != "")) {
		$template=$_SESSION['template'];
		$_SESSION['template']="";
	}
	else {
		$template='template1';
		$_SESSION['template']=$template;
	}
}

//logToFile("buynow.php:template1=".$template1);

$fullcustomheaderhtml =  $blogpath ."/wp-content/plugins/dapshoppingcart/includes/templates/dapcart/".$template."/customcheckout.html";   
if(file_exists($fullcustomheaderhtml)) {
	 $checkouttemplate=$fullcustomheaderhtml;
}
else
{
	 //$checkouttemplate=$lldocroot ."/dap/inc/template/".$template."/checkout.html";
	$checkouttemplate =  $blogpath ."/wp-content/plugins/dapshoppingcart/includes/templates/dapcart/".$template."/checkout.html";   
}
//$checkouttemplate=$lldocroot ."/dap/inc/template/checkout.html";
$tempcheckout = file_get_contents($checkouttemplate);
$tempcontent = $tempcheckout; 
$current_msg=$tempcontent;

$productinfotemplate =  $blogpath ."/wp-content/plugins/dapshoppingcart/includes/templates/dapcart/".$template."/customproduct.html";   
if(file_exists($productinfotemplate)) {
	 $checkouttemplate=$productinfotemplate;
}
else
{
	 //$checkouttemplate=$lldocroot ."/dap/inc/template/".$template."/checkout.html";
	$checkouttemplate =  $blogpath ."/wp-content/plugins/dapshoppingcart/includes/templates/dapcart/".$template."/product.html";   
}
	//$checkouttemplate=$lldocroot ."/dap/inc/template/checkout.html";
$prod = file_get_contents($checkouttemplate);
//logToFile("buynow.php:ENTER show paypal=".$showpaypal);

if(isset($_REQUEST['paypal_landing_page'])) {
	$redirectpage=$_REQUEST['paypal_landing_page'];
	$_SESSION['paypal_landing_page']=$redirectpage;
}
else if(isset($_SESSION['paypal_landing_page']))
	$redirectpage=$_SESSION['paypal_landing_page'];
else if(isset($_REQUEST['payment_succ_page']))			 
	$redirectpage=$_REQUEST['payment_succ_page'];
else
	$redirectpage=$_SESSION['payment_succ_page'];
	
//	$loadingimg = $loadingimg"];
//logToFile("buynow.php: ". $loadingimg,LOG_DEBUG_DAP);

if(isset($loadingimg)) 
	$loadingimg=urlencode($loadingimg);

if(isset($_REQUEST["is_last_upsell"])) {
	$is_last_upsell = $_REQUEST["is_last_upsell"];
	$_SESSION["is_last_upsell"]=$is_last_upsell;
	//logToFile("buynow.php: request: hidden field: is_last_upsell==" .$_REQUEST["is_last_upsell"]);
}
else if(isset($_SESSION["is_last_upsell"])) {
	$is_last_upsell = $_SESSION["is_last_upsell"];
	//logToFile("buynow.php: session: hidden field: is_last_upsell==" .$_SESSION["is_last_upsell"]);
}
												  
if ((!isset($_SESSION['num_cart'])) || ($_SESSION['num_cart'] == 0) || isset($item_name)) {
	if( (!isset($item_name)) && (!isset($item_number))) {
		$error_url = "Sorry, missing item_name";
		return $error_url;
		exit;
	}
	
	logToFile("buynow.php: item_name=" .$item_name, LOG_DEBUG_DAP);
	
	if( (isset($_REQUEST["freeTrial"])) && ($_REQUEST["freeTrial"] == "Y")) 
		$freeTrial=$_REQUEST["freeTrial"];
	else if( (isset($_SESSION["freeTrial"])) && ($_SESSION["freeTrial"] == "Y")) 
		$freeTrial=$_SESSION["freeTrial"];
	
	if($freeTrial=="Y")
		$stripe_instant_recurring_charge="N";
		
	if ($btntype == "buynow") {	
		$item_save="";
		// Create dropdown of products
//		logToFile("buynow.php: dropdown, item_number=".$item_number,LOG_DEBUG_DAP);	
		if(strstr($item_number,",")) {
			$items = explode(",", $item_number);
			$itemnamestr = '<select name="item_name_l" id="item_name_l" class="BodyTextSmall" style="font-weight:bold;" onchange="updateProductInfo();">';
			$counter=0;
			
			foreach($items as $key => $val) {
			//	logToFile("buynow.php: dropdown, key=".$key.", value=".$val,LOG_DEBUG_DAP);	
/*			 <select name="sourceOperation" class="BodyTextSmall" id="sourceOperation"> 
                <option value="Added To" selected>Added To</option> 
                <option value="Removed From">Removed From</option> 
            </select>	
*/			
				//logToFile("buynow.php: dropdown, item_selected=".$item_selected,LOG_DEBUG_DAP);	
				if( ($item_selected!="") && ($item_selected==$val)) {
					$product = Dap_Product::loadProduct(trim($item_selected));
					if(isset($product)) {
						$itemnamestr .= '<option value="' . $product->getId() . '" selected style="font-weight:bold;">'. $product->getName() . '</option>'; 	
						$_SESSION["item_selected"]=$product->getId();
						//logToFile("buynow.php: dropdown, 1st - item_selected=".$item_selected,LOG_DEBUG_DAP);	
						$item_name=$product->getName();
						$item_selected="";
					}
				}
				else {
					$product = Dap_Product::loadProduct(trim($val));
					if($counter==0)
						$item_save=$val;
					if(isset($product)) {
						$itemnamestr .= '<option style="font-weight:bold;" value="' . $product->getId() . '">'. $product->getName() . '</option>';
						//logToFile("buynow.php: dropdown, 3rd - item_selected=".$_SESSION["item_selected"],LOG_DEBUG_DAP);	
					}
				}
				
				$counter++;
			}
			$itemnamestr .= "</select>";
			//logToFile("buynow.php: dropdown, itemnamestr=".$itemnamestr,LOG_DEBUG_DAP);	
		}
		else if($item_number != "") {
			$product=Dap_Product::loadProduct(trim($item_number));
			
			if(!isset($product)) {
				$error_url = "Sorry, missing item_name";
				return $error_url;
			}
			
			$item_name=$product->getName();
			$itemnamestr=$item_name;
			//$current_msg = str_replace( '%%ITEMNAME%%', $item_name, $current_msg); 
		}
		else {	
			logToFile("buynow.php: item_name=" .$item_name, LOG_DEBUG_DAP);
			$itemnamestr=$item_name;
			//$current_msg = str_replace( '%%ITEMNAME%%', $item_name, $current_msg); 
			$product = Dap_Product::loadProductByName(trim($item_name));
		}
		
		if((isset($_SESSION["item_selected"])) && ($_SESSION["item_selected"]!="")) {
			//logToFile("buynow.php: FOUND...item_selected=" .$_SESSION["item_selected"], LOG_DEBUG_DAP);
			$product = Dap_Product::loadProduct(trim($_SESSION["item_selected"]));
		}
		else if($item_name != "") { 
	    	logToFile("buynow.php: FOUND item ...item_name=" .trim(urldecode($item_name)), LOG_DEBUG_DAP);
			$product = Dap_Product::loadProductByName(trim(urldecode($item_name)));
			if(isset($product))
				logToFile("buynow.php: ELSE FOUND...item_name=" .$item_name, LOG_DEBUG_DAP);
			else 
				logToFile("buynow.php: ELSE NOT FOUND...item_name=" .$item_name, LOG_DEBUG_DAP);
		}
		else if($item_save!="") 
			$product = Dap_Product::loadProduct(trim($item_save));

		
		$productId = $product->getId();
		
		logToFile("buynow.php: productId=" .$productId, LOG_DEBUG_DAP);
		
		$item_name = $product->getName();
		$description = $product->getDescription();
		$trial_amount = $product->getTrial_price();
		$amount = $product->getPrice();
		logToFile("buynow.php: productId.. trial_amount=" .$trial_amount, LOG_DEBUG_DAP);
		logToFile("buynow.php: productId.. amount=" .$amount, LOG_DEBUG_DAP);
		$is_recurring = $product->getIs_recurring();
		
		$recurring_cycle_1 = $product->getRecurring_cycle_1();
		$recurring_cycle_2 = $product->getRecurring_cycle_2();
		$recurring_cycle_3 = $product->getRecurring_cycle_3();
		$total_occurrences = $product->getTotal_occur();
				
		$discount_amt = "";
		$recurring_discount_amt = "";
		$initial_amount = "";
		$initial_trial_amount = "";
		
		logToFile("buynow.php: couponCode" .$_SESSION['couponCode'], LOG_DEBUG_DAP);
		
		if ($_SESSION['couponCode'] != "") {
			$couponCode = $_SESSION['couponCode'];
			$coupon = Dap_Coupon::loadCouponByCode($couponCode); //loads coupon details from db
			if (isset($coupon)) {
				$__REQUEST['coupon_id'] = $coupon->getId();
				logToFile("buynow.php: set coupon Id = " . $_EQUEST['coupon_id'] );
				$discount_amt = $coupon->getDiscount_amt();
				$recurring_discount_amt = $coupon->getRecurringDiscount_amt();
				
				if ($is_recurring == "Y") {
					if ( ($product->getTrial_price() > 0) || ( ($freeTrial == "Y") && ($payment_gateway=="stripe")) ) {
						if($product->getTrial_price() > 0)
							$trial_amount = $product->getTrial_price() - $discount_amt;
						else {
							$trial_amount = $product->getTrial_price();
							logToFile("functions_payment.php: yes free trial, so trial_amount=$0" . $trial_amount );
						}
					}
					else {
						$trial_amount  = $product->getPrice() - $discount_amt;
						logToFile("functions_payment.php: no free trial, so trial_amount=product price " . $trial_amount );
					}
		
					$amount  = $product->getPrice();
					if ($recurring_discount_amt > 0) {
						$amount = $product->getPrice() - $recurring_discount_amt;
					}
				}
				else {	
					$amount  = $product->getPrice() - $discount_amt;	
				}
				
				logToFile("buynow.php: subscription: trial=" . $product->getTrial_price());
				logToFile("buynow.php: subscription: actual product price =" . $product->getPrice());
				logToFile("buynow.php: subscription: final discounted initial amount=" . $trial_amount);
			
				logToFile("buynow.php: subscription: recurring_discount_amt=" . $recurring_discount_amt);
				logToFile("buynow.php: subscription: final recurring discounted amount=" . $amount);
			}
		}
		else {
			if ($is_recurring == "Y") {
				if ( ($product->getTrial_price() > 0) || ( ($freeTrial == "Y") && ($payment_gateway=="stripe")) ) {
					if($product->getTrial_price() > 0)
						$trial_amount = $product->getTrial_price();
					else {
						$trial_amount = $product->getTrial_price();
						logToFile("functions_payment.php:no coupon, yes free trial, so trial_amount=$0" . $trial_amount );
					}
				}
				else {
					$trial_amount  = $product->getPrice();
					logToFile("functions_payment.php: no coupon, trial = $0 but no free trial flag set, so trial_amount=product price " . $trial_amount );
				}
	
			}
			logToFile("buynow.php: subscription: trial=" . $product->getTrial_price());
			logToFile("buynow.php: subscription: actual product price =" . $product->getPrice());	
		}
		//$terms="$" . $trial_amount . " for first " . $recurring_cycle_1 . " days" . "\nThen $" . $amount . " for each " . $recurring_cycle_3 . " days, for " . $total_occurrences . " installments";
		$terms="";
		if ($is_recurring == "Y") {
			if($total_occurrences>=999) {
				$terms="<div style='font-size: 12px;'><strong>Subscription Terms: </strong>". $currency_symbol . $trial_amount . " for first " . $recurring_cycle_1 . " days" . "\nthen $currency_symbol" . $amount . " for each " . $recurring_cycle_3 . " days.</div>";
			}
			else {
				$terms="<div style='font-size: 12px;'><strong>Subscription Terms: </strong>". $currency_symbol . $trial_amount . " for first " . $recurring_cycle_1 . " days" . "\nthen $currency_symbol" . $amount . " for each " . $recurring_cycle_3 . " days, for " . $total_occurrences . " installments.</div>";
			}
		}
		//logToFile("buynow.php: subscription: trial=" . $product->getTrial_price());
		if ( ($trial_amount > 0) || ($freeTrial=="Y")) {
			$display_amount=$trial_amount;
			
			logToFile("buynow.php: display_amount: " . $display_amount);
		}
		else {
			$display_amount=$amount;
			$trial_amount = "0.00";
		}
	}
}

$user = Dap_User::loadAdminUserByMinId();
if (!isset($user)) {
		$error_url = "Sorry, Admin setup issue. Please contact the site-admin";
		return $error_url;
}
	
$password = $user->getPassword();
if (!isset ($password) || $password == '') {
	$error_url = "Sorry, Admin setup issue. Please contact the site-admin";
	return $error_url;
}

$_SESSION['password'] = $password;

if (isset($_REQUEST['payment_gateway'])) {
	logToFile("buynow.php: set payment gateway=".$_REQUEST['payment_gateway'],LOG_DEBUG_DAP);	
	$_SESSION['payment_gateway'] = $_REQUEST['payment_gateway'];
	$payment_gateway = $_SESSION['payment_gateway'];
}

// cart options
$tandc = "";
$show_coupon = "N";
$show_address2 = "N";
$show_howdidyouhearboutus = "N";
$show_shiptoaddress = "N";

$show_tandc = "N";
$require_tandc_acceptance = "N";
$request_phone = "N";
$request_fax = "N";
$request_address2 = "N";
$request_company = "N";
$show_comments = "N";
$cart_header = "";
$cart_footer = "";
$checkout_submit_image_url = "N";


$productCartOptions = Dap_CartOption::loadProductCartOptions($productId);
if(isset($productCartOptions)) {
	
	// cart options
	
	$show_coupon = $productCartOptions->getShowCouponCode();
	$show_howdidyouhearboutus = $productCartOptions->getHowDidYouHear();
	$show_shiptoaddress = $productCartOptions->getShowShipAddress();
	$show_address2 = $productCartOptions->getRAddress2();
	$show_tandc = $productCartOptions->getShowTAndC();
	$tandc = $productCartOptions->getTAndC();
	$require_tandc_acceptance = $productCartOptions->getRTAndC();
	$request_phone = $productCartOptions->getRPhone();
	$request_fax = $productCartOptions->getRFax();
	$request_address2 = $productCartOptions->getRAddress2();
	$requireBillingInfoForPaypalCheckout = $productCartOptions->getRequireBillingInfoForPaypalCheckout();
	$request_company = $productCartOptions->getRCompany();
	$show_comments = $productCartOptions->getShowComments();
	$cart_header = $productCartOptions->getCartHeader();
	$cart_footer = $productCartOptions->getCartFooter();
	$checkout_submit_image_url = $productCartOptions->getSubmitOrderImage();
	$choose_password  = $productCartOptions->getChoosePassword();
	$customFields = $productCartOptions->getCustomFields();
	
	//logToFile("buynow.php: require_tandc_acceptance=".$require_tandc_acceptance,LOG_DEBUG_DAP);	
}
else {
	$productCartOptions = Dap_CartOption::loadProductCartOptions("GENERAL SETTINGS");
	if(isset($productCartOptions)) {
	  $show_howdidyouhearboutus = $productCartOptions->getHowDidYouHear();
	  $show_shiptoaddress = $productCartOptions->getShowShipAddress();
	  $show_address2 = $productCartOptions->getRAddress2();
	  $show_tandc = $productCartOptions->getShowTAndC();
	  $tandc = $productCartOptions->getTAndC();
	  $require_tandc_acceptance = $productCartOptions->getRTAndC();
	  $request_phone = $productCartOptions->getRPhone();
	  $request_fax = $productCartOptions->getRFax();
	  $request_address2 = $productCartOptions->getRAddress2();
	  $requireBillingInfoForPaypalCheckout = $productCartOptions->getRequireBillingInfoForPaypalCheckout();
	  $request_company = $productCartOptions->getRCompany();
	  $show_comments = $productCartOptions->getShowComments();
	  $cart_header = $productCartOptions->getCartHeader();
	  $cart_footer = $productCartOptions->getCartFooter();
	  $checkout_submit_image_url = $productCartOptions->getSubmitOrderImage();
	  $choose_password  = $productCartOptions->getChoosePassword();
	  $customFields = $productCartOptions->getCustomFields();
	  logToFile("buynow.php: ADDTOCART OPTIONS",LOG_DEBUG_DAP);	
	}
	//logToFile("buynow.php: empty table",LOG_DEBUG_DAP);	
}	

//echo "page template is".$template;
if(isset($requireBillingInfoForPaypalCheckout) && ($requireBillingInfoForPaypalCheckout !='')) 
	$billinginforequired=$requireBillingInfoForPaypalCheckout;
else {
	$billinginforequired='Y';
}

	

/*$firstmsg="<div id='orderTestimonials'>
<div class='testimonialTitle'>Nullam tristique cursus varius.</div>
Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Etiam egestas nisi a augue scelerisque dapibus. Duis blandit nunc ut quam.
<div class='testimonialUser'>&mdash; Veena Prashanth</div>
</div>";
*/

$firstmsg="";
if(isset($firstmsg) && ($firstmsg !=''))
	$firstmsg=urldecode($firstmsg);
	
$secondmsg="";
if(isset($secondmsg) && ($secondmsg !=''))
	$secondmsg=urldecode($secondmsg);
	
$thirdmsg="";
if(isset($thirdmsg) && ($thirdmsg !=''))
	$thirdmsg=urldecode($thirdmsg);

$fullformcss = $blogpath . "/wp-content/plugins/dapshoppingcart/includes/templates/dapcart/".$template."/css/buynow.css";
$fullcustomcss =  $blogpath . "/wp-content/plugins/dapshoppingcart/includes/templates/dapcart/".$template."/css/custombuynow.css";


//$customcss="/dap/inc/template/".$template."/css/custombuynow.css";
//$formcss="/dap/inc/template/".$template."/css/buynow.css";

	
if ( (isset($_REQUEST["wpfoldername"])) && ( $_REQUEST["wpfoldername"] != "" ))
  $wpfoldername=$_REQUEST["wpfoldername"];

if($wpfoldername == "")
	$wpfoldername=$_SESSION["wpfoldername"];

$customcss=$wpfoldername."/wp-content/plugins/dapshoppingcart/includes/templates/dapcart/" . $template . "/css/custombuynow.css";
//logToFile("buynow.php: custom css: ". $customcss);	

$formcss=$wpfoldername."/wp-content/plugins/dapshoppingcart/includes/templates/dapcart/" . $template . "/css/buynow.css";
//logToFile("buynow.php: formcss css: ". $formcss);	

if(file_exists($fullcustomcss)) {
   //logToFile("buynow.php: custom css: ". $customcss);	
   $formcss=$customcss;
   //logToFile("buynow.php: custom css: ". $customcss);
}
?>
<link rel='stylesheet' type='text/css' href='<?php echo $formcss; ?>'>
<link rel='stylesheet' type='text/css' href='/dap/inc/content/PaymentForm.css'>

<!--<link rel='stylesheet' type='text/css' href='/dap/inc/content/PaymentForm.css'>-->
<!--<script type='text/javascript' language='javascript' src="/dap/javascript/country-state.js"></script>-->
<!--<script type='text/javascript' language='javascript' src="/dap/javascript/dapcart/languages/jquery.validationEngine-en.js"></script>-->
<!--<script type='text/javascript' language='javascript' src="/dapcart/jquery.validationEngine.js"></script>-->


<script type='text/javascript' language='javascript' src='<?php echo $siteurlhttps; ?>/dap/javascript/dapcart/countries.js'></script>
<script type='text/javascript' language='javascript' src='<?php echo $siteurlhttps; ?>/dap/javascript/common.js'></script>


<script type="text/javascript" src="https://js.stripe.com/v1/"></script>

<script>
var require_tandc_acceptance = "<?php echo $require_tandc_acceptance; ?>";
var stripePublishableKey = "<?php echo $stripePublishableKey; ?>";

Stripe.setPublishableKey(stripePublishableKey);


jQuery(document).ready(function(){
	// binds form submission and fields to the validation engine
jQuery("#formPayment").validationEngine();
});

/**
*
* @param {jqObject} the field where the validation applies
* @param {Array[String]} validation rules for this field
* @param {int} rule index
* @param {Map} form options
* @return an error string if validation failed
*/
function checkHELLO(field, rules, i, options){
	if (field.val() != "HELLO") {
		// this allows to use i18 for the error msgs
		return options.allrules.validate2fields.alertText;
	}
}
</script>
<script>

function passwordCheck(form) {
	var pcmmsg = "<?php echo PASSWORD_CONFIRM_MATCH; ?>";
	var pmsg = "<?php echo PASSWORD_REQUIRED; ?>";
	var alphamsg = "<?php echo PASSWORD_ONLY_ALPHANUMERIC_ALLOWED; ?>";
	
	if(document.getElementById("cpassword")) {
		//alert("here");
		var cpassword=document.getElementById("cpassword").value;	
		var cpasswordconfirm="";
		if(document.getElementById("cpasswordconfirm")) {
			 cpasswordconfirm=document.getElementById("cpasswordconfirm").value;	
		}
		else {
			if(pmsg!=null)
				alert("<?php echo PASSWORD_CONFIRM_MATCH;?>");
			else  
				alert("Password is a required field. Please enter Password.");
		
			document.getElementById("cpassword").focus();
			return false;
		}
		
	
		if(cpassword == "") {
			document.getElementById('btnPleaseWait').style.display = 'none';
			document.getElementById('btnSubmit').style.display = 'initial';
			if(pmsg!=null)
				alert("<?php echo PASSWORD_REQUIRED;?>");
			else 
				alert("Password is a required field. Please enter Password.");
				document.getElementById("cpassword").focus();
				return false;
		}
		
		if((cpassword != cpasswordconfirm) || ( cpassword == "") ) {
			document.getElementById('btnPleaseWait').style.display = 'none';
			document.getElementById('btnSubmit').style.display = 'initial';
			if (pcmmsg!=null)
				alert("<?php echo PASSWORD_CONFIRM_MATCH;?>");
			else
				alert("Password and the re-typed Password fields must match.");	
			
			document.getElementById("cpassword").focus();
			return false;
		}
		
		
		if( /[^a-zA-Z0-9]/.test( cpassword ) ) {
			document.getElementById('btnPleaseWait').style.display = 'none';
			document.getElementById('btnSubmit').style.display = 'initial';
			if(alphamsg)
       			alert('<?php echo PASSWORD_ONLY_ALPHANUMERIC_ALLOWED;?>');
			else
				alert('Only alphanumber characters allowed in Password');
			
			document.getElementById("cpassword").focus();
       		return false;
    	}
		
	
	}
	
	return true;
}

function validateBillInfo(form, createValidationList, customFields)
{	
	var first_name=document.getElementById("first_name").value;
	var last_name=document.getElementById("last_name").value;
	var billing_first_name=document.getElementById("billing_first_name").value;
	var billing_last_name=document.getElementById("billing_last_name").value;
	var address=document.getElementById("address").value;
	var country=document.getElementById("country").value;
	var state=document.getElementById("state").value;
	var city=document.getElementById("city").value;
	var zip=document.getElementById("zip").value;
	var phone=document.getElementById("phone").value;
	var email=document.getElementById("email").value;
	var cpassword="";
	if(document.getElementById("cpassword")) {
		cpassword=document.getElementById("cpassword").value;
	}
	var customfieldval="";
	if((customFields) && (customFields != "")) {
		var n=customFields.split(",");
		//validate custom fields
		for (i = 0; i < n.length; i++) {
			if(document.getElementById("custom_" + n[i])) {
				var cval = document.getElementById("custom_" + n[i]).value;
				if(cval != "") {
					if(customfieldval == "")
						customfieldval=customfieldval + "custom_" + n[i] + ":" + cval;
					else 
						customfieldval=customfieldval + ",custom_" + n[i] + ":" + cval;
				}
			}
		}
		//alert("final custom val = " + customfieldval);
	}
	
	var howdidyouhearaboutus="";
	var comments="";
	
	if(document.getElementById("howdidyouhearaboutus")) {
		howdidyouhearaboutus=document.getElementById("howdidyouhearaboutus").value;
	}
	if(document.getElementById("comments")) {
		comments=document.getElementById("comments").value;
	}
	
	var lldocroot="<?php echo $lldocroot; ?>";
	//alert("cpassword="+cpassword);
	var submiturl='/dap/storeBillInfo.php';
		jQuery.ajax({
		url: submiturl,
		type: "POST",
		async: false,
		cache: false,
		data: {"first_name":first_name,"last_name":last_name,"billing_first_name":billing_first_name,"billing_last_name":billing_last_name,"address":address,"country":country,"state":state,"city":city,"zip":zip,"phone":phone,"email":email,"cpassword":cpassword,"customfieldval":customfieldval,"comments":comments,"howdidyouhearaboutus":howdidyouhearaboutus,"lldocroot":lldocroot},
		success: function (returnval) {
			//alert("Updated in session");
		}
	}); //ajax
	
	if( validateCustomerInfo(form) == false) {
		return false;	
	}
	
	if(document.getElementById("cpassword")) {
		
		if(!passwordCheck()) return false;
		cpassword=document.getElementById("cpassword").value;
	}
	
	var billinginforequired = "<?php echo $billinginforequired; ?>";
	if(billinginforequired == "Y") {
		var ret = validateBillingInfo(form);
		if(ret == false) {
			return false;	
		}
	}
	
	var str = createValidationList;
	var cfrmsg = "<?php echo CUSTOM_FIELD_REQUIRED; ?>";
	
	var customfieldval="";
	
	if((str) && (str != "")) {
		var n=str.split(",");
		//validate custom fields
		for (i = 0; i < n.length; i++) {
			
			var ln=n[i].split(":");
			if(ln[0]) {				
			  var cval = document.getElementById(ln[0]).value;
			  //alert("custom field name = "+ln[0]+", val = "+cval);
			  
			  if (cval=="") {
				  document.getElementById('btnPleaseWait').style.display = 'none';
				  document.getElementById('btnSubmit').style.display = 'initial';
				  if(cfrmsg)
					  alert(ln[1] + " " + cfrmsg);
				  else
					  alert(ln[1] + " cannot be empty. It's a required field.");
					  
				  document.getElementById(ln[0]).focus();
				  return false;		
			  }
			}
		}
	}
	
	if(require_tandc_acceptance=="Y") {
		if(!document.getElementById('tandc').checked) {
			var tandcmsg="<?php echo TANDC_REQUIRED;?>";
			if(tandcmsg != "")
				alert(tandcmsg);
			else
				alert("Please accept Terms & Conditions to Proceed!");
				
			return false;
		}
	}
	
	
	return true;
	//return validateBillingInfo(form);

}

function askvalidation(form,createValidationList,require_tandc_acceptance)
{
	
	var cfrmsg = "<?php echo CUSTOM_FIELD_REQUIRED; ?>";
	
	var first_name=document.getElementById("first_name").value;
	var last_name=document.getElementById("last_name").value;
	var billing_first_name=document.getElementById("billing_first_name").value;
	var billing_last_name=document.getElementById("billing_last_name").value;
	var address=document.getElementById("address").value;
	var country=document.getElementById("country").value;
	var state=document.getElementById("state").value;
	var city=document.getElementById("city").value;
	var zip=document.getElementById("zip").value;
	var phone=document.getElementById("phone").value;
	var email=document.getElementById("email").value;
	var cpassword="";
	if(document.getElementById("cpassword")) {
		cpassword=document.getElementById("cpassword").value;
	}
	
	var howdidyouhearaboutus="";
	var comments="";
	
	if(billing_first_name)first_name=billing_first_name;
	if(billing_last_name)last_name=billing_last_name;
	
	if(document.getElementById("howdidyouhearaboutus")) {
		howdidyouhearaboutus=document.getElementById("howdidyouhearaboutus").value;
	}
	if(document.getElementById("comments")) {
		comments=document.getElementById("comments").value;
	}
	
	var lldocroot="<?php echo $lldocroot; ?>";
	
	
	var submiturl='/dap/storeBillInfo.php';
		jQuery.ajax({
		url: submiturl,
		type: "POST",
		async: false,
		cache: false,
		data: {"first_name":first_name,"last_name":last_name,"address":address,"country":country,"state":state,"city":city,"zip":zip,"phone":phone,"email":email,"cpassword":cpassword,"comments":comments,"howdidyouhearaboutus":howdidyouhearaboutus,"lldocroot":lldocroot},
		success: function (returnval) {
			//alert("Updated in session");
		}
	}); //ajax
	
	if( validateCustomerInfo(form) == false) {
	//	document.getElementById('btnPleaseWait').style.display = 'none';
	//	document.getElementById('btnSubmit').style.display = 'initial';
		return false;	
	}
		
	if(document.getElementById("cpassword")) {
		if(!passwordCheck()) return false;
		cpassword=document.getElementById("cpassword").value;
	}
		
	var billinginforequired = "<?php echo $billinginforequired; ?>";
	if(billinginforequired == "Y") {
		var ret = validateBillingInfo(form);
		if(ret == false) {
	//		document.getElementById('btnPleaseWait').style.display = 'none';
	//		document.getElementById('btnSubmit').style.display = 'initial';
			return false;	
		}
	}

	// validate custom field
	
	var str = createValidationList;
	if((str) && (str != "")) {
		var n=str.split(",");
		//validate custom fields
		for (i = 0; i < n.length; i++) {
			
			var ln=n[i].split(":");
			if(ln[0]) {				
				var cval = document.getElementById(ln[0]).value;
				if (cval=="") {
			//		document.getElementById('btnPleaseWait').style.display = 'none';
			//		document.getElementById('btnSubmit').style.display = 'initial';
					if(cfrmsg)
						alert(ln[1] + " " + cfrmsg);
					else
						alert(ln[1] + " cannot be empty. It's a required field.");
						
					document.getElementById(ln[0]).focus();
					return false;		
				}
			}
		}
	}
	
	if( validateCCInfo(form) == false) {
		return false;	
	}
	
	if(require_tandc_acceptance=="Y") {
		if(!document.getElementById('tandc').checked) {
			var tandcmsg="<?php echo TANDC_REQUIRED;?>";
			if(tandcmsg != "")
				alert(tandcmsg);
			else
				alert("Please accept Terms & Conditions to Proceed!");
				
			return false;
		}
	}
	
	var payment_gateway = "<?php echo $payment_gateway; ?>";
	var month = document.getElementById("exp_date");
	var monthval = month.options[month.selectedIndex].text;
	
	var year = document.getElementById("exp_date_year");
	var yearval = year.options[year.selectedIndex].text;
	
	//alert("payment gateway="+payment_gateway);
	if((payment_gateway)  && (payment_gateway == "stripe")) {
	  document.getElementById('btnSubmit').style.display = 'none';
	  document.getElementById('btnPleaseWait').style.display = 'initial';
		  
	   Stripe.createToken({
		  number: form.card_num.value,
		  cvc: form.card_code.value,
		  exp_month: monthval,
		  exp_year: yearval,
		  country: country,
		  address_state: state,
		  address_city: city,
		  address_zip: zip,
		  address_line1: address,
		  name: first_name + " " + last_name
		  }, stripeResponseHandler);
		return false; 
	}
	
	document.getElementById('btnSubmit').style.display = 'none';
	document.getElementById('btnPleaseWait').style.display = 'initial';
	document.getElementById("formPayment").submit();
	
}

function stripeResponseHandler(status, response) {
	if (response.error) {
	// re-enable the submit button
	
		 // jQuery('.btnSubmit').removeAttr("disabled");
		  
		  //setErrortext
		var lldocroot="<?php echo $lldocroot; ?>";
		
		var submiturl='/dap/setCartError.php';
		jQuery.ajax({
		url: submiturl,
		type: "POST",
		async: false,
		cache: false,
		data: {"err_text":escape(response.error.message),"lldocroot":lldocroot},
		success: function (returnval) {
			//alert("Updated in session");
			document.getElementById('btnSubmit').style.display = 'initial';
		    document.getElementById('btnPleaseWait').style.display = 'none';
			window.location.href=window.location.href;
		}
		}); //ajax
	} else {
		  var form$ = jQuery("#formPayment");
		  // token contains id, last4, and card type
		  var token = response['id'];
		  // insert the token into the form so it gets submitted to the server
		  form$.append("<input type='hidden' name='stripeToken' value='" + token + "' />");
		  document.getElementById('btnSubmit').style.display = 'none';
		  document.getElementById('btnPleaseWait').style.display = 'initial';
		  // and submit
		  form$.get(0).submit();
		  return;
	}
	document.getElementById('btnSubmit').style.display = 'initial';
	document.getElementById('btnPleaseWait').style.display = 'none';
}

</script>
<script type="text/javascript" language="javascript">
function initCardType() {
	var cardtype = document.getElementById('card_type');
	if (cardtype != null) {
		var val = 
		"<?php 
			if (isset($_SESSION['paymentObj'])) {
				$cardt = $_SESSION['paymentObj']->getCard_type(); 
				if ($cardt != '')
				{ 
					echo $cardt; 
				}
				else echo '';
			}
		?>";
	
		if (val != null && val != '') {
			//cardtype.options[cardtype.selectedIndex].text = val;
		  for(i=0; i<cardtype.options.length; i++) {
			 if(cardtype.options[i].value == val) {
				  cardtype.selectedIndex = i;
			 }
		  }
	   }
	}
}

function applyCoupon(form) {
	if (form.coupon_code.value == null || form.coupon_code.value == "") {
		alert("Please enter coupon code");	
		form.coupon_code.focus();
		return;
	}
// use coupon code to get discount amount
	form.action = "<?php echo '/dap/inc/validateCoupon.php'; ?>";
	form.submit();		
}

function paypalformsubmit()
{
	document.formPayment.action ="/dap/paypalCoupon.php";
//document.getElementById("formPayment").submit();
}

function loginsubmit(email,password,url)
{
		
	var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if(document.getElementById('dapemail').value=='' || !filter.test(document.getElementById('dapemail').value) )
	{
		alert('Please provide a valid email address');
		email.focus;
		return false;
	}
//	alert(email+password+url);
	//alert("password="+password);
	
	if(document.getElementById('dappassword').value=='' || document.getElementById('dappassword').value=='password')
	{
		alert("Please enter a valid password");
		return false;
	}
	


	document.getElementById('btnLoginSign').style.display = 'none';
	document.getElementById('btnLoginPleaseWait').style.display = 'initial';
	
	var submiturl='/dap/authenticate.php?email='+ email+'&password='+ password +'&submitted=Y&dapcart=Y';
		jQuery.ajax({
		url: submiturl,
		type: "POST",
		async: false,
		cache: false,
		success: function (returnval) {
		  if(returnval==0) {
			window.location.href=window.location.href;
		  }
		  else if(returnval==-1) {
			//alert("Invalid Password");
			window.location.href=window.location.href;
		  }
		}
	}); //ajax
	
	return;
}
function updateProductInfo()
{
	//alert("change option");
	var item_name_l=document.getElementById("item_name_l");
//    alert(sourceProductId.options[sourceProductId.selectedIndex].text);
	
//	if(item_name_l.options[item_name_l.selectedIndex].text == "
	var item_selected = item_name_l.options[item_name_l.selectedIndex].value;
	var  item_number = "<?php echo $_SESSION['item_number'];?>";
	
	var submiturl='/dap/updateProdInfo.php';
	//alert("submit url="+submiturl);
	
	jQuery.ajax({
			  url: submiturl,
			  type: "POST",
			  async: false,
			  cache: false,
			  data: {"item_selected":item_selected,"item_number":item_number},
			  success: function (returnval) {
				window.location.reload();
			  }
			}); //ajax
	return;
}
</script>
<?php if(isset($cart_header)) echo $cart_header;?>
<html>
<head>
</head>
<body>
<?php

	if(isset($_REQUEST["showcc"]) && ($_REQUEST["showcc"] !='')) {
		$showcc=$_REQUEST["showcc"];
		$_SESSION["showcc"]=$showcc;
	}
	else if(isset($_SESSION["showcc"]) && ($_SESSION["showcc"] !='')) {
		$showcc=$_SESSION["showcc"];
	}
	else
		$showcc="Y";
	
	//logToFile("buynow.php: showcc==" .$showcc);
	
	if(isset($_REQUEST["showpaypal"]) && ($_REQUEST["showpaypal"] !='')) {
		$showpaypal=$_REQUEST["showpaypal"];
		$_SESSION["showpaypal"]=$showpaypal;
	}
	else if(isset($_SESSION["showpaypal"]) && ($_SESSION["showpaypal"] !='')) 
		$showpaypal=$_SESSION["showpaypal"];	
	else 
		$showpaypal="N";
		
 	//echo "<pre>";
		//print_r($_SESSION);
		//exit();
	//print_r($_SESSION);
	//$fullcustomheaderhtml =  $lldocroot ."/dap/inc/template/".$template."/customcheckout.html";   

	//if (isset($item_name) && ($item_name != "")) 
			//$_SESSION['couponCode'] = NULL;
	//logToFile("buynow.php: enter err check",LOG_DEBUG_DAP);
	
    if ((isset($_SESSION['err_text'])) || (isset($_REQUEST['err_text']))) {	
	//	logToFile("buynow.php: session=" . $_SESSION['err_text'],LOG_DEBUG_DAP);
  //      logToFile("buynow.php: request=" . $_REQUEST['err_text'],LOG_DEBUG_DAP);
        
		if ( (isset($_SESSION['err_text'])) && ($_SESSION['err_text'] != "")) {
        //echo "ERROR: " . $_SESSION['err_text']; 
       		$errortext = "ERROR: " . $_SESSION['err_text'];
			//logToFile("buynow.php: session=" . $_SESSION['err_text'],LOG_DEBUG_DAP);
		}
		else if ( (isset($_REQUEST['err_text'])) && ($_REQUEST['err_text'] != "")) {
        //echo "ERROR: " . $_REQUEST['err_text']; 
        	$errortext ="ERROR: " . $_REQUEST['err_text'];
			//logToFile("buynow.php: request=" . $_REQUEST['err_text'],LOG_DEBUG_DAP);
		}
		
		unset($_SESSION['err_text']);
        $_SESSION['err_text']=NULL;
		$_SESSION['err_text']="";
    } 
	$current_msg = str_replace( '%%ERRORTEXT%%', $errortext, $current_msg);
    
	if( Dap_Session::isLoggedIn() ) 
   		$current_msg = str_replace( '%%LOGINVISIBILITY%%', 'none', $current_msg);
	else {
		$current_msg = str_replace( '%%LOGINVISIBILITY%%', 'block', $current_msg);
		if($loginmsg)
			$current_msg = str_replace( '%%LOGIN_MSG%%', $loginmsg, $current_msg);
		else
			$current_msg = str_replace( '%%LOGIN_MSG%%', 'Already A Member? Please Login Here...', $current_msg);
	}
	
	if( (isset($_REQUEST['btntype']) && (strcmp($_REQUEST['btntype'], 'buynow') == 0)) 
			|| (isset($btntype) && (strcmp($btntype, 'buynow') == 0)) 
			|| (isset($_SESSION['btntype']) && (strcmp($_SESSION['btntype'], 'buynow') == 0))){ 
		
		//logToFile("buynow.php: display product info?, session=" . $_SESSION['num_cart'],LOG_DEBUG_DAP);
//	 	$productinfotemplate=$lldocroot ."/dap/inc/template/".$template."/product.html";

		
		
		$content .= $prod;
		$qty=$_REQUEST['qty'];
		
		$itemqty=1;
		if(isset($qty) || ($qty !=''))
			$itemqty=$qty;
			
		//$currency_symbol=trim(Dap_Config::get('CURRENCY_SYMBOL'));
		$current_msg = str_replace( '%%PRODUCT%%', $prod, $current_msg);
		
		$current_msg = str_replace( '%%ITEMNAME%%', $itemnamestr, $current_msg); 
		logToFile("buynow.php: trial=" . $trial_amount . ", amt=" . $amount,LOG_DEBUG_DAP);
		if( ($trial_amount>0) || ($freeTrial == "Y"))
			$current_msg = str_replace( '%%AMOUNT%%',$currency_symbol.$trial_amount, $current_msg);
		else 
			$current_msg = str_replace( '%%AMOUNT%%',$currency_symbol.$amount, $current_msg);
		
		$current_msg = str_replace( '%%QTY%%',$itemqty, $current_msg); 
		$current_msg = str_replace( '%%DESCRIPTION%%', $description, $current_msg); 
		$current_msg = str_replace( '%%PRODUCT%%', $prod, $current_msg);
		$current_msg = str_replace( '%%PRODUCTSUMMARY%%', $terms, $current_msg);
		
		if (($show_coupon != "Y")) {
			if($display_amount==0)
				$carttotal = '<div class="cartTotal">Total: ' .$currency_symbol.$display_amount . " (Free Trial)";
			else
				$carttotal = '<div class="cartTotal">Total: ' .$currency_symbol.$display_amount;
			$carttotal .='</div>';
			$carttotal .='<div class="deleteProduct">&nbsp;</div>';
		} else { 
			$carttotal ='<div class="couponCode">Coupon Code: ';
			$carttotal .='<input type="text" id="coupon_code" name="coupon_code" title="Optional" value="(optional)" onFocus="clearDefaultText(this.form);" onBlur="resetDefaultText(this.form);" />';
			$carttotal .="<input type='button' class='couponbtn' id='apply_coupon' onclick='applyCoupon(document.formPayment);' value='Apply' />";
			$carttotal .='</div>';
			$carttotal .= '<div class="cartTotal">Total: ';
			
			$amt = $display_amount;
			if ($_SESSION['new_amount'] != "") 
				$amt = $_SESSION['new_amount'];
			
			$carttotal .= $currency_symbol . $amt;
			$carttotal .='</div><div class="deleteProduct">&nbsp;</div>';
		} 
		$current_msg = str_replace( '%%CARTTOTAL%%', $carttotal, $current_msg);
	//echo $content = $current_msg;
	} 
	else {  
		/****************************MODIFIED********************************/
		if (!isset($_SESSION['num_cart'])) 
			$_SESSION['num_cart'] = 0;
		if (!isset($_SESSION['recur_count']))  
			$_SESSION['recur_count'] = 0;	
		
		$total_non_recur = $_SESSION['num_cart'] - $_SESSION['recur_count'];
		
		$productinfotemplate =  $blogpath ."/wp-content/plugins/dapshoppingcart/includes/templates/dapcart/".$template."/customproduct.html";   
		if(file_exists($productinfotemplate)) {
			 $checkouttemplate=$productinfotemplate;
		}
		else
		{
			 //$checkouttemplate=$lldocroot ."/dap/inc/template/".$template."/checkout.html";
			$checkouttemplate =  $blogpath ."/wp-content/plugins/dapshoppingcart/includes/templates/dapcart/".$template."/product.html";   
		}
		//$checkouttemplate=$lldocroot ."/dap/inc/template/checkout.html";
		$prod = file_get_contents($checkouttemplate);
		
		//$paypalbutton ='<form method="post" name="paypalform" id="paypalform" action="">';
		$paypalbutton .='<input type="hidden" name="cmd" value="_cart">';
		$paypalbutton .='<input type="hidden" name="upload" value="1">';
		$paypalbutton .='<input type="hidden" name="business" value="'.$paypal_business_email.'">';
		$paypalbutton .='<input type="hidden" name="currency_code" value="'.$currency.'">';
		$paypalbutton .='<input type="hidden" name="return" value="'.$payment_succ_page.'">';
		
		logToFile("buynow.php:ADD TO CART.. total_non_recur " . $total_non_recur,LOG_DEBUG_DAP);
	
		if ($total_non_recur > 0) { 
			$num_cart_item = $_SESSION['num_cart'];
			
			for($i=0;$i<$num_cart_item;$i++) { 
				if ($_SESSION['product_details'][$i]['L_ISRECUR' . $i] != "Y") {
				/****************************End Modified****************************/
					$newproditem=$prod;
					
					$proditemname =$_SESSION['product_details'][$i]['L_NAME' . $i];
					$proditemprice =$_SESSION['product_details'][$i]['L_AMT' . $i];
					$proditemid=$_SESSION['product_details'][$i]['L_NUMBER' . $i];
					$newitempprodrice=$proditemprice * $_SESSION['product_details'][$i]['L_QTY' . $i];
					$proditemqty =$_SESSION['product_details'][$i]['L_QTY' . $i];
					$proditemdesc =$_SESSION['product_details'][$i]['L_DESC' . $i];
					
					$newproditem = str_replace( '%%ITEMNAME%%', $proditemname, $newproditem);
					$newproditem = str_replace( '%%QTY%%', $proditemqty, $newproditem); 
					$newproditem = str_replace( '%%AMOUNT%%',$currency_symbol.number_format($newitempprodrice, 2, '.', ''), $newproditem); 
					$newproditem = str_replace( '%%DESCRIPTION%%', $proditemdesc, $newproditem);
					$newproditem = str_replace( '%%PRODUCTSUMMARY%%', $terms, $newproditem);
					
					$lineproditem .=$newproditem; 
					$paypalbutton .='<input type="hidden" name="item_name_'.$i.'" value="'.$proditemname.'">';
					$paypalbutton .='<input type="hidden" name="item_number_'.$i.'" value="'.$proditemid.'">';
					$paypalbutton .='<input type="hidden" name="amount_'.$i.'" value="'.$proditemprice.'">';
					$paypalbutton .='<input type="hidden" name="quantity_'.$i.'" value="'.$proditemqty.'">';
		
				}
			}
			
			logToFile("buynow.php:ADD TO CART.. replace product with " . $lineproditem,LOG_DEBUG_DAP);
		}
		logToFile("buynow.php:ADD TO CART.. recur_count " . $_SESSION['recur_count'],LOG_DEBUG_DAP);
	
		if ($_SESSION['recur_count'] > 0) {
			$num_cart_item = $_SESSION['num_cart'];
			
			for($i=0;$i<$num_cart_item;$i++) { 
				if ($_SESSION['product_details'][$i]['L_ISRECUR' . $i] == "Y") {
					$newitem=$prod;
					
					$itemname =$_SESSION['product_details'][$i]['L_NAME' . $i];
					$itemprice =$_SESSION['product_details'][$i]['L_AMT' . $i];
					logToFile("buynow.php:ADD TO CART.. found recurring item =  " . $itemname,LOG_DEBUG_DAP);
					$itemid=$_SESSION['product_details'][$i]['L_NUMBER' . $i];
					$newitemprice=$itemprice * $_SESSION['product_details'][$i]['L_QTY' . $i];
					$itemqty =$_SESSION['product_details'][$i]['L_QTY' . $i];
					
					$recuritemprice=$_SESSION['product_details'][$i]['L_RECURAMT' . $i];
					$recurring_cycle_1=$_SESSION['product_details'][$i]['L_RECUR1'.$i];
					$recurring_cycle_3=$_SESSION['product_details'][$i]['L_RECUR3'.$i];
					$total_occurrences= $_SESSION['product_details'][$i]['L_TOTALOCCUR'.$i];
					
				//$newitemprice=$recuritemprice * $_SESSION['product_details'][$i]['L_QTY' . $i];
					$itemdesc =$_SESSION['product_details'][$i]['L_DESC' . $i];
					$paypalbutton .='<input type="hidden" name="item_name_'.$i.'" value="'.$itemname.'">';
					$paypalbutton .='<input type="hidden" name="item_number_'.$i.'" value="'.$itemid.'">';
					$paypalbutton .='<input type="hidden" name="amount_'.$i.'" value="'.$itemprice.'">';
					$paypalbutton .='<input type="hidden" name="quantity_'.$i.'" value="'.$itemqty.'">';
					
					$newitem = str_replace( '%%ITEMNAME%%', $itemname, $newitem); 
					$newitem = str_replace( '%%AMOUNT%%',$currency_symbol.number_format($newitemprice, 2, '.', ''), $newitem); 
					$newitem = str_replace( '%%QTY%%',$itemqty, $newitem);
					$newitem = str_replace( '%%DESCRIPTION%%', $itemdesc, $newitem);
					
					
			//		$terms="<div style='font-size: 12px;'><strong>Subscription Terms: </strong>"."$" . $newitemprice . " for first " . $recurring_cycle_1 . " days" . "\nthen $" . $recuritemprice . " for each " . $recurring_cycle_3 . " days, for " . $total_occurrences . " installments.</div>";
					
					$terms=$_SESSION['product_details'][$i]['L_TERMS'.$i];
					
					logToFile("buynow.php:ADD TO CART.. recur terms " . $terms,LOG_DEBUG_DAP);
					$newitem = str_replace( '%%PRODUCTSUMMARY%%', $terms, $newitem);
					$lineitem .=$newitem;
				}
			}
			//$current_msg = str_replace( '%%PRODUCT%%', $lineitem, $current_msg);
		}
		
		$fulllist=$lineproditem . $lineitem;
		
		$current_msg = str_replace( '%%PRODUCT%%', $fulllist, $current_msg);
		
		//$current_msg = str_replace( '%%PRODUCT%%', '', $current_msg);
	
	
		if($billinginforequired == 'N') 	 {
			$paypalbutton='<a href="%%LINK%%"><img src="%%PAYPALIMGSRC%%" alt=""  onclick="var ret=validateBillInfo(document.formPayment,' . "'" . $createValidationList . "'" . ",'" . $customFields . "'" . ');return ret;" /></a>';
	$link='/dap/paypalCoupon.php?cmd=_cart&loadingimg='.$loadingimg.'&upload=1'.'&currency_code='.$currency.'&redirect='.$redirectpage.'&coupon_code='.$_SESSION['couponCode'].'';
			//$paypalbutton .='<input type="image" height="40px" width="100px" class="validate-skip" id="skipbutton" src="'.$paypalimg.'" border="0" onclick="return paypalformsubmit();" name="paypalsubmit"  alt="Make payments with PayPal - it\'s fast, free and secure!">';
		 }
		 else  {
			 $paypalbutton='<a href="%%LINK%%"><img src="%%PAYPALIMGSRC%%" alt=""  onclick="var ret=validateBillInfo(document.formPayment,' . "'" . $createValidationList . "'" . ",'" . $customFields . "'" . ');return ret;" /></a>';
	$link='/dap/paypalCoupon.php?cmd=_cart&loadingimg='.$loadingimg.'&upload=1'.'&currency_code='.$currency.'&redirect='.$redirectpage.'&coupon_code='.$_SESSION['couponCode'].'';
			 //$paypalbutton .='<input type="image" height="40px" width="100px" src="'.$paypalimg.'" border="0" id="paypalsubmit" id="paypalsubmit" onclick="return paypalformsubmit();" name="paypalsubmit"  alt="Make payments with PayPal - it\'s fast, free and secure!">';
		 }
	//$paypalbutton .='</form>';
	
		if ($_SESSION['num_cart'] == 0 || $_SESSION['num_cart'] == "") {
			if (isset($_SESSION['product_details'])) 
				unset($_SESSION["product_details"]);
			
			$current_msg .='	<table cellspacing="0" cellpadding="0" border="0" class="cartSummary">';
			$current_msg .='<tr>';
			$current_msg .='<td colspan="5" class="cartHeader">Cart is Empty... continue shopping</td>';
			$current_msg .='</tr>';
			//echo "Cart is Empty... continue shopping";	
		}
		else {
			$carttotal ='<div class="couponCode">';
	//$carttotal .='Coupon Code:<input type="text" id="coupon_code" name="coupon_code" title="Optional" value="(optional)" onFocus="clearDefaultText(this.form);" onBlur="resetDefaultText(this.form);" />';
	//$carttotal .="<input type='button' class='couponbtn' id='apply_coupon' onclick='applyCoupon(document.formPayment);' value='Apply' />";
			$carttotal .='</div>';
			$carttotal .= '<div class="cartTotal">Total: ';
			$amt = 0;
            if ($_SESSION['new_amount'] != "") 
   	        	$amt = $_SESSION['new_amount'];
            else  {	
                for($i=0;$i<$num_cart_item;$i++) { 
                	$amt = $amt + ( $_SESSION['product_details'][$i]['L_AMT' . $i] * $_SESSION['product_details'][$i]['L_QTY' . $i] );
                }
            } 
                        
            $carttotal .=trim(Dap_Config::get('CURRENCY_SYMBOL')) . number_format($amt, 2, '.', '');
			$current_msg = str_replace( '%%CARTTOTAL%%', $carttotal, $current_msg);
			$content .=$current_msg;
		}
		
	 } // else btntype!=buynow
	 
	 $firstname="";
	 if($sessionfirstname!="") {
		  $billingfirstnameinsession ='<input type="hidden" id="first_name" name="first_name" value="'.$sessionfirstname.'" />';
		  $firstname='<label for="email" class="formLabelText">' . $sessionfirstname . '</label>'; 
	 }
	 else {
		if ($_SESSION['paymentObj'] != null) { $fname=$_SESSION['paymentObj']->getFirst_name(); } 
		$firstname ='<input type="text" id="first_name" name="first_name"  class="validate[required] text-input" maxLength="50" size="20" value="'.$fname.'"';
		$firstname .=' />';   
	 }
	 
	 
	 $lastname="";
	 if($sessionlastname!="") {
		  $billinglastnameinsession ='<input type="hidden" id="last_name" name="last_name" value="'.$sessionlastname.'" />';
		  $lastname='<label for="email" class="formLabelText">' . $sessionlastname . '</label>'; 
	 }
	 else {
		if ($_SESSION['paymentObj'] != null) { $lname=$_SESSION['paymentObj']->getLast_name(); } 
		$lastname ='<input type="text" id="last_name" name="last_name"  class="validate[required] text-input" maxLength="50" size="20" value="'.$lname.'"';
		$lastname .=' />';   
	 }
	 
	 
	 $bfirstname="";
	 if ($_SESSION['paymentObj'] != null) { 
	 	$bfname=$_SESSION['paymentObj']->getBillingFirst_name(); 
		if ($bfname=="")
			$bfname=$_SESSION['paymentObj']->getFirst_name();	
	} 
	 
	 if($bfname!="") {
		 $bfirstname ='<input type="text" id="billing_first_name" name="billing_first_name"  class="validate[required] text-input" maxLength="50" size="20" value="'.$bfname.'"';
		$bfirstname .=' />'; 
	 }
	 else {
		$bfirstname ='<input type="text" id="billing_first_name" name="billing_first_name"  class="validate[required] text-input" maxLength="50" size="20" value="'.$sessionfirstname.'"';
		$bfirstname .=' />';   
	 }
	 
	 
	 $blastname="";
	 if ($_SESSION['paymentObj'] != null) { 
	 	$blname=$_SESSION['paymentObj']->getBillingLast_name(); 
		if ($blname=="")
			$blname=$_SESSION['paymentObj']->getLast_name();
	 } 
	 if($blname!="") {
		$blastname ='<input type="text" id="billing_last_name" name="billing_last_name"  class="validate[required] text-input" maxLength="50" size="20" value="'.$blname.'"';
		$blastname .=' />';   
	 }
	 else {
		$blastname ='<input type="text" id="billing_last_name" name="billing_last_name"  class="validate[required] text-input" maxLength="50" size="20" value="'.$sessionlastname.'"';
		$blastname .=' />';   
	 }
	 
	//logToFile("buynow.php:billing showcc=".$showcc,LOG_DEBUG_DAP);
	if( ($showcc=="Y") || ($billinginforequired=="Y")) {
		$current_msg = str_replace( '%%BILLINGVISIBILITY%%', 'block', $current_msg);
		//logToFile("buynow.php:BILLINGVISIBILITY",LOG_DEBUG_DAP);
	}
	else { 	
		$current_msg = str_replace( '%%BILLINGVISIBILITY%%', 'none', $current_msg);
		//logToFile("buynow.php:BILLING NOT VISIBLE",LOG_DEBUG_DAP);
	}
	
	if ($_SESSION['paymentObj'] != null){$add1=$_SESSION['paymentObj']->getAddress1(); } else if ($sessionaddress1 != "") 
		$add1=$sessionaddress1;
	
	$address ='<input type="text" id="address" name="address"  class="validate[required] text-input" maxLength="100" size="20" value="'.$add1.'"';
	$address .=' />';
	
	if ($_SESSION['paymentObj'] != null) {$add2=$_SESSION['paymentObj']->getAddress2(); }else if ($sessionaddress2 != "") 
		$add2=$sessionaddress2;
	
	$address2 ='<input type="text" id="address2" name="address2" maxLength="100" size="20" value="'.$add2.'"';
	if ($show_address2 == "Y") 
		$current_msg = str_replace( '%%ADDRESS2VISIBILITY%%', 'block', $current_msg);
	else
		$current_msg = str_replace( '%%ADDRESS2VISIBILITY%%', 'none', $current_msg);	
	
	$address2 .=' />';
	if ($_SESSION['paymentObj'] != null) {$bcity=$_SESSION['paymentObj']->getCity(); }else if ($sessioncity != "") 
		$bcity=$sessioncity;
	
	$city ='<input type="text" id="city" name="city"   class="validate[required] text-input" maxLength="100" size="20"  value="'.$bcity.'"';
	$city .=' />';
	
	if ($_SESSION['paymentObj'] != null) {$bzip= $_SESSION['paymentObj']->getZip();} else if ($sessionzip != "") 
		$bzip=$sessionzip;
	
	$zip ='<input type="text" id="zip" name="zip"  class="validate[required] text-input" maxLength="10" size="20" value="'.$bzip.'"';
	$zip .=' />';
	
	if ($_SESSION['paymentObj'] != null) {$gcountry= $_SESSION['paymentObj']->getCountry();} else if ($sessioncountry != "") 
		$gcountry=$sessioncountry;
	
	if ($_SESSION['paymentObj'] != null) {$gstate= $_SESSION['paymentObj']->getState();} else if ($sessionstate != "") 
		$gstate=$sessionstate;
	
	$country ='<select onchange="print_state(';
	$country .="'state',this.selectedIndex,'".$gstate."');";
	$country .='" id="country" name ="country" class="validate[required]"></select>';
	$state='<select name ="state" id ="state" class="validate[required]"><option value="">Select state</option></select>';
	$state .='<script language="javascript">print_country("country","'.$gcountry.'","'.$gstate.'");</script>';
  
	$billingemail="";
	if ($sessionemail!="") {
		$billingemailinsession ='<input type="hidden" id="email" name="email" value="'.$sessionemail.'" />';
		$current_msg = str_replace( '%%EMAILVISIBILITY%%', 'block', $current_msg);
		$billingemail='<label for="email" class="formLabelText">' . $sessionemail . '</label>'; 
		$emailmsg="";
	}
	else {
		if ($_SESSION['paymentObj'] != null) 
			$bemail= $_SESSION['paymentObj']->getEmail(); 
		else if ($sessionemail != "") 		
			$bemail= $sessionemail;
	
		$billingemail ='<input type="text" id="email" name="email" maxLength="255" size="20" class="validate[required,custom[email]] text-input" value="'.$bemail.'"';
	
		$billingemail .=' />';
		$current_msg = str_replace( '%%EMAILVISIBILITY%%', 'block', $current_msg);
	}
	
	
	//$choosepassword="Y";
	//$customfields="TAX:tax";
	
	//password
	if ( ($choose_password == "Y") && ($loggedIn == FALSE)) {
		
		logToFile("buynow.php:choose password=" . $choose_password,LOG_DEBUG_DAP);
		$current_msg = str_replace( '%%PASSWORDVISIBILITY%%', 'block', $current_msg);
		$cpassword='<input type="password" id="cpassword" name="cpassword" maxLength="34" size="34" value="" class="validate[required] text-input"/>';
		$cpasswordconfirm='<input type="password" id="cpasswordconfirm" name="cpasswordconfirm" maxLength="34" size="34"  value="" class="validate[required] text-input"/>';
		$current_msg = str_replace( '%%PASSWORD%%', $cpassword, $current_msg);
		$current_msg = str_replace( '%%CONFIRMPASSWORD%%', $cpasswordconfirm, $current_msg);
	}
	else {
		//logToFile("buynow.php:choose password=" . $choose_password,LOG_DEBUG_DAP);
		//logToFile("buynow.php:choose loggedIn=" . $loggedIn,LOG_DEBUG_DAP);
		$current_msg = str_replace( '%%PASSWORDVISIBILITY%%', 'none', $current_msg);
	}
	
	$customfieldarr="";
	$customfieldstr="";
	//$createValidationList="";
	if($customFields != "") {
		$customfieldarr = explode(",", $customFields);
		if ($customfieldarr != "") {
			foreach($customfieldarr as $keyarr => $valarr) {
			 	$customFld = Dap_CustomFields::loadCustomfieldsByName($valarr);
				if ($customFld) {
					$id = $customFld->getId();
					$label = $customFld->getLabel();
					$required = $customFld->getRequired();
					
					if($label != "") {
					//	logToFile("buynow.php: fieldname= " . $label . ", required=" . $required,LOG_DEBUG_DAP);
						
						if($required == "Y") {
						  $customfieldstr .= '<li style="display:block">
						  <div class="formLabel"> * ' . $label . '</div>
						  <input type="text" id="custom_' . $valarr . '" name="custom_' . $valarr . '" size="20" value="" required />
						  </li>';
						  if($createValidationList=="")
						  	$createValidationList=$createValidationList."custom_".$valarr . ":" . $label;
						  else
							$createValidationList=$createValidationList.",custom_".$valarr . ":" . $label;
							
							//logToFile("buynow.php: createValidationList= " . $createValidationList,LOG_DEBUG_DAP);
						}
						else {
						$customfieldstr .= '<li style="display:block">
						  <div class="formLabel">' . $label . '</div>
						  <input type="text" id="custom_' . $valarr . '" name="custom_' . $valarr . '" size="20" value="" />
						  </li>';
						}
					}
				}
			}
		}
		$current_msg = str_replace( '%%CUSTOMFIELDS%%', $customfieldstr, $current_msg);
		$current_msg = str_replace( '%%ADDITIONALINFOVISIBILITY%%', "block", $current_msg);
	}
	else {
		$current_msg = str_replace( '%%CUSTOMFIELDS%%', "", $current_msg);
		$current_msg = str_replace( '%%ADDITIONALINFOVISIBILITY%%', "none", $current_msg);
	}
	
	if ($request_company == "Y") 
		$current_msg = str_replace( '%%COMPANYVISIBILITY%%', 'block', $current_msg);
	else
		$current_msg = str_replace( '%%COMPANYVISIBILITY%%', 'none', $current_msg);
	
	$bcompany="";
	if ($_SESSION['paymentObj'] != null) 
		$bcompany= $_SESSION['paymentObj']->getCompany();
	
	$company .='<input type="text" id="company" name="company" maxLength="25" size="20" value="';
	$company .='" />';
	
	if ($request_phone == "Y") 
		$current_msg = str_replace( '%%PHONEVISIBILITY%%', 'block', $current_msg);
	else
		$current_msg = str_replace( '%%PHONEVISIBILITY%%', 'none', $current_msg);
	
	if ($_SESSION['paymentObj'] != null) {$bphone= $_SESSION['paymentObj']->getPhone();} else if ($sessionphone != "") 
		$bphone=$sessionphone;
	$phone .='<input type="text" id="phone" name="phone" maxLength="25" size="20" class="validate[custom[phone]] text-input" value="';
	$phone .='" />';
	
	if ($request_fax == "Y") 
		$current_msg = str_replace( '%%FAXVISIBILITY%%', 'block', $current_msg);
	else
		$current_msg = str_replace( '%%FAXVISIBILITY%%', 'none', $current_msg);
	$fax .='<input type="text" id="fax" name="fax" maxLength="25" size="20" value="';
	$fax .='" />';
	
	/**************Shipping Info******************************/
	$sfname="";
	if ($_SESSION['paymentObj'] != null) 
		$sfname=$_SESSION['paymentObj']->getShip_to_first_name();
	
	$shippingfirstname ='<input type="text" id="ship_to_first_name" name="ship_to_first_name" maxLength="50" size="14" value="'.$sfname.'"';
	$shippingfirstname .='/>';   
	
	$slname="";
	if ($_SESSION['paymentObj'] != null) 
		$slname=$_SESSION['paymentObj']->getShip_to_last_name();
	$shippinglastname .='<input type="text" id="ship_to_last_name" name="ship_to_last_name" maxLength="50" size="14" value="'.$slname.'"';
	$shippinglastname .='/>';
	
	$sadd1="";
	if ($_SESSION['paymentObj'] != null) 
		$sadd1=$_SESSION['paymentObj']->getShip_to_address1();
	$shippingaddress ='<input type="text" id="ship_to_address" name="ship_to_address" maxLength="100" size="20" value="'.$sadd1.'"';
	if ($show_address2 == "Y") 
		$current_msg = str_replace( '%%SADDRESS2VISIBILITY%%', 'block', $current_msg);
	else
		$current_msg = str_replace( '%%SADDRESS2VISIBILITY%%', 'none', $current_msg);	
	$shippingaddress .=' />';
	
	$sadd2="";
	if ($_SESSION['paymentObj'] != null) 
		$sadd2= $_SESSION['paymentObj']->getShip_to_address2();
	$shippingaddress2 ='<input type="text" id="ship_to_address2" name="ship_to_address2" maxLength="100" size="20" value="'.$sadd2.'"';
	$shippingaddress2 .=' />';
	
	$scity="";
	if ($_SESSION['paymentObj'] != null) 
		$scity=$_SESSION['paymentObj']->getShip_to_city();
	$shippingcity .='<input type="text" id="ship_to_city" name="ship_to_city" maxLength="100" size="20" value="'.$scity.'"';
	$shippingcity .=' />';
	
	$sstate="";
	if ($_SESSION['paymentObj'] != null) 
		$sstate=$_SESSION['paymentObj']->getShip_to_state();
	
	$szip="";
	if ($_SESSION['paymentObj'] != null) 
		$szip= $_SESSION['paymentObj']->getShip_to_zip();
	$shippingzip ='<input type="text" id="ship_to_zip" name="ship_to_zip" maxLength="10" size="10" value="'.$szip.'"';
	$shippingzip .=' />';
	
	$shippingcountry ='<select onchange="print_shippingstate(';
	$shippingcountry .="'ship_to_state',this.selectedIndex);";
	$shippingcountry .= '" id="ship_to_country" name ="ship_to_country"></select>';
	$shippingstate = '<select name ="ship_to_state" id ="ship_to_state"><option value="">Select state</option></select>';
	$shippingstate .='<script language="javascript">print_shippingcountry("ship_to_country");</script>	';

	
	/*****************END shipping info**********************************/
	
	$current_msg = str_replace( '%%FIRSTNAME%%', $firstname, $current_msg);
	$current_msg = str_replace( '%%LASTNAME%%', $lastname, $current_msg);
	$current_msg = str_replace( '%%BFIRSTNAME%%', $bfirstname, $current_msg);
	$current_msg = str_replace( '%%BLASTNAME%%', $blastname, $current_msg);
	
	$current_msg = str_replace( '%%ADDRESS%%', $address, $current_msg);
	$current_msg = str_replace( '%%ADDRESS2%%', $address2, $current_msg);
	$current_msg = str_replace( '%%CITY%%', $city, $current_msg);
	$current_msg = str_replace( '%%STATE%%', $state, $current_msg);
	$current_msg = str_replace( '%%ZIPCODE%%', $zip, $current_msg);
	$current_msg = str_replace( '%%COUNTRY%%', $country, $current_msg);
	$current_msg = str_replace( '%%EMAIL%%', $billingemail, $current_msg);
	$current_msg = str_replace( '%%EMAILMSG%%', $emailmsg, $current_msg);
	
	$current_msg = str_replace( '%%COMPANY%%', $company, $current_msg);
	$current_msg = str_replace( '%%PHONE%%', $phone, $current_msg);
	$current_msg = str_replace( '%%FAX%%', $fax, $current_msg);
	$current_msg = str_replace( '%%SFIRSTNAME%%', $shippingfirstname, $current_msg);
	$current_msg = str_replace( '%%SLASTNAME%%', $shippinglastname, $current_msg);
	$current_msg = str_replace( '%%SADDRESS%%', $shippingaddress, $current_msg);
	$current_msg = str_replace( '%%SADDRESS2%%', $shippingaddress2, $current_msg);
	$current_msg = str_replace( '%%SCITY%%', $shippingcity, $current_msg);
	$current_msg = str_replace( '%%SSTATE%%', $shippingstate, $current_msg);
	$current_msg = str_replace( '%%SZIPCODE%%', $shippingzip, $current_msg);
	$current_msg = str_replace( '%%SCOUNTRY%%', $shippingcountry, $current_msg);
	
	$content .= $current_msg;
	if ($show_howdidyouhearboutus == "Y") 
		$current_msg = str_replace( '%%ADDITIONALVISIBILITY%%', 'block', $current_msg);
	else 		
   		$current_msg = str_replace( '%%ADDITIONALVISIBILITY%%', 'none', $current_msg);
	
	if ($show_shiptoaddress == "Y") 
		$current_msg = str_replace( '%%SHIPPINGVISIBILITY%%', 'block', $current_msg);
	else 		
   		$current_msg = str_replace( '%%SHIPPINGVISIBILITY%%', 'none', $current_msg);
		

	if ($show_comments == "Y") 
		$current_msg = str_replace( '%%COMMENTVISIBILITY%%', 'block', $current_msg);
	else 
		$current_msg = str_replace( '%%COMMENTVISIBILITY%%', 'none', $current_msg);	

	if ($show_tandc == "Y") { 
		$current_msg = str_replace( '%%TERMSVISIBILITY%%', 'block', $current_msg);
		if($require_tandc_acceptance=='Y')
			$termsbox ='<input type="checkbox" name="tandc" id="tandc" class="validate[required] checkbox" />';
		else
			$termsbox ='<input type="checkbox" name="tandc" id="tandc" class="checkbox" />';
	}
	else 
		$current_msg = str_replace( '%%TERMSVISIBILITY%%', 'none', $current_msg);	
		
	$cardnum="";
	$exp_date="";
	if ($_SESSION['paymentObj'] != null) 
		$cardnum=$_SESSION['paymentObj']->getCard_num();
	
	if ($_SESSION['paymentObj'] != null) 
		$exp_date=$_SESSION['paymentObj']->getExp_date();
	
	if ($_SESSION['paymentObj'] != null) 
		$expirationdate=$_SESSION['paymentObj']->getExp_date();
	
	
	//$expirationdate = str_split($exp_date, 2);
    //$fmonth=$expirationdate[0];
	//$fyear=$expirationdate[1];
	//logToFile("buynow.php: exp_date=" . $expirationdate,LOG_DEBUG_DAP);
	$fmonth = substr($expirationdate, 0, 2);
    $fyear = substr($expirationdate, 2, 4);
	
	if(($cctype) && ($cctype!="")) {
		//logToFile("buynow.php: cctype=" . $cctype,LOG_DEBUG_DAP);
		$cctype =   '<select id="cc_type" class="required" name="cc_type" class="creditCardType" tabindex="11">' . $cctype . '</select>';
    } else {
		$cctype='<select id="cc_type" class="required" name="cc_type" class="creditCardType" tabindex="11">
				  <option value="visa">Visa</option>
				  <option value="mastercard">MasterCard</option>
				  <option value="discover">Discover</option>
				  <option value="amex">American Express</option>
				 </select>';
	}
	
    $cardnumber='<input type="text" id="card_num" name="card_num" text-input" size="22" value="'.$cardnum.'"';
	$cardnumber .=' />';
	$expirationdate='<select name="exp_date" id="exp_date" class="selectc validate[condRequired[card_num,exp_date_year,card_code]]">';
	$expirationdate .='<option value=" ">Month</option>';
	for($month=1; $month <= 12; ++$month):
		if($month < 10) $month = "0".$month;
		if($fmonth==$month) 
			$expirationdate .='<option selected value="'.$fmonth.'">'.$month.'</option>';
		else		
 			$expirationdate .='<option value="'.$month.'">'.$month.'</option>';
 	endfor; 
	
	$expirationdate .='</select>';
	$expirationdate .='<select name="exp_date_year" id="exp_date_year" class="selectc validate[condRequired[card_num,exp_date,card_code]]">';
	$expirationdate .='<option value="">Year</option>';
	$year = date("Y") ;
	$yearvalue = date("y") ;
	

    for ($i = 0; $i <= 60; ++$i) {
		if($fyear==$year) { 
			$expirationdate .="<option selected value='$fyear'>$year</option>"; ++$year;++$yearvalue;	
		}
		else {
			$expirationdate .="<option value='$year'>$year</option>"; ++$year;++$yearvalue;
		}
	}
	
//	logToFile("buynow.php: fyear=" . $fyear,LOG_DEBUG_DAP);
//	logToFile("buynow.php: year=" . $year,LOG_DEBUG_DAP);
	
	$expirationdate .='</select>';
	
//	logToFile("buynow.php: expirationdate=" . $expirationdate,LOG_DEBUG_DAP);
	
	$paymentcardcode="";
	if ($_SESSION['paymentObj'] != null) 
		$paymentcardcode=$_SESSION['paymentObj']->getCard_code();
	
	$cardcode='<input type="text" id="card_code" name="card_code" maxLength="4" minLength="3"   class="validate[condRequired[card_num,exp_date_year,exp_date],custom[onlyNumberSp]] text-input"  size="4" value="'.$paymentcardcode.'"';
	$cardcode .=' />';
	
		
	if ($btntype == "addtocart") {
		$paypalbutton=$paypalbutton;
	}
	else if($is_recurring=='Y') {
		if($billinginforequired == "Y") {
			$paypalbutton='<a href="%%LINK%%"><img src="%%PAYPALIMGSRC%%" alt=""  onclick="var ret=validateBillInfo(document.formPayment,' . "'" . $createValidationList . "'" . ",'" . $customFields . "'" . ');return ret;" /></a>';
			$link='/dap/paypalCoupon.php?cmd=_xclick-subscriptions&loadingimg='.$loadingimg.'&item_number='.$productId.'&currency_code='.$currency.'&redirect='.$redirectpage.'&coupon_code='.$_SESSION['couponCode'].'';
		}
		else {
			$paypalbutton='<a href="%%LINK%%"><img src="%%PAYPALIMGSRC%%" alt="" onclick="var ret=validateBillInfo(document.formPayment,' . "'" . $createValidationList . "','" . $customFields . "'" . ');return ret;"/></a>';
		$link='/dap/paypalCoupon.php?cmd=_xclick-subscriptions&loadingimg='.$loadingimg.'&item_number='.$productId.'&currency_code='.$currency.'&redirect='.$redirectpage.'&coupon_code='.$_SESSION['couponCode'].'';

		}
	}
	else {
		if($billinginforequired == "Y") {
		  $paypalbutton='<a href="%%LINK%%"><img  src="%%PAYPALIMGSRC%%" alt=""  onclick="var ret=validateBillInfo(document.formPayment,' . "'" . $createValidationList . "','" . $customFields . "'" . ');return ret;" /></a>';
		  $link='/dap/paypalCoupon.php?cmd=_xclick&loadingimg='.$loadingimg.'&item_number='.$productId.'&currency_code='.$currency.'&redirect='.$redirectpage.'&coupon_code='.$_SESSION['couponCode'].'';	
		}
		else {
		  $paypalbutton='<a href="%%LINK%%"><img  src="%%PAYPALIMGSRC%%" alt=""  onclick="var ret=validateBillInfo(document.formPayment,' . "'" . $createValidationList . "','" . $customFields . "'" . ');return ret;"/></a>';
		  $link='/dap/paypalCoupon.php?cmd=_xclick&loadingimg='.$loadingimg.'&item_number='.$productId.'&currency_code='.$currency.'&redirect='.$redirectpage.'&coupon_code='.$_SESSION['couponCode'].'';	
		}
	}
	
	/************MODIFIED to show paypal button or not************/
	//logToFile("buynow.php:paypal=".$showpaypal);
	
	
	
	//$current_msg = str_replace( '%%CCVISIBILITY%%', 'block', $current_msg);
	
	//if(!isset($showcc))$showcc="";
	//if(!isset($showpaypal))$showpaypal="";
	
	if ($showcc == "N") {
		$current_msg = str_replace( '%%CCVISIBILITY%%', 'none', $current_msg);
		//logToFile("buynow.php:do show cc=".$showcc);
	}
	else {
		$current_msg = str_replace( '%%CCVISIBILITY%%', 'block', $current_msg);
	}
	
	if ($showpaypal == "N") {
		$current_msg = str_replace( '%%PAYPALVISIBILITY%%', 'none', $current_msg);	
		$current_msg = str_replace( '%%PAYPALORVISIBILITY%%', 'none', $current_msg);
		$current_msg = str_replace( '%%PAYPALCLASS%%', 'id="payPal"', $current_msg);
		//$current_msg = str_replace( '%%CCCLASS%%', 'id="payWithCCNoPaypal"', $current_msg);
		
		//logToFile("buynow.php:do show paypal=".$showpaypal);
	}
	else {
		
		$current_msg = str_replace( '%%PAYPALVISIBILITY%%', 'block', $current_msg);
		if($showcc=="N") {
			$current_msg = str_replace( '%%PAYPALCLASS%%', 'id="payPalNoCC"', $current_msg);
			$current_msg = str_replace( '%%PAYPALORVISIBILITY%%', 'none', $current_msg);
		}
		else {
		//	$current_msg = str_replace( '%%CCCLASS%%', 'id="payWithCC"', $current_msg);
			$current_msg = str_replace( '%%PAYPALCLASS%%', 'id="payPal"', $current_msg);
			$current_msg = str_replace( '%%PAYPALORVISIBILITY%%', 'block', $current_msg);
		}
		//logToFile("buynow.php:showpaypal=Y");
		
	}
	/************End MODIFIED to show paypal button or not************/
	if (($checkout_submit_image_url != "N") && ($checkout_submit_image_url != "")) {
		//logToFile("checkout.php:checkout_submit_image_url=" . $checkout_submit_image_url);
		
		$actionbutton='<input type="image" name="btnSubmit" id="btnSubmit" style="display:initial;" onClick='. "'return askvalidation(document.formPayment," . '"' . $createValidationList . '", "' . $require_tandc_acceptance . '"' . ');' . "' src='".$checkout_submit_image_url."' />";
		
		$actionbutton .='<input type="image" name="btnPleaseWait" id="btnPleaseWait" class="btnwait" style="display:none;" src="/dap/images/pleasewait.png" />';
	} else {
	//	logToFile("checkout.php: REQUEST URI=" .$_SERVER['REQUEST_URI']);
		$actionbutton ='<input name="btnSubmit" type="button" class="submitbtn" id="btnSubmit" style="display:initial;" onClick='. "'return askvalidation(document.formPayment," . '"' . $createValidationList . '", "' . $require_tandc_acceptance . '"' . ');' . "' value = 'Submit Order' tabindex='0' />";
		
		$actionbutton .='<input type="image" name="btnPleaseWait" id="btnPleaseWait" class="btnwait" style="display:none;" src="/dap/images/pleasewait.png" />';
	}
	
	 
	$hiddenfields = $billingemailinsession;
	$hiddenfields .= $billingfirstnameinsession;
	$hiddenfields .= $billinglastnameinsession;
	if($payment_gateway=="stripe")
		$hiddenfields .= '<input type="hidden" name="stripe_instant_recurring_charge" value="' .$stripe_instant_recurring_charge . '">';
	
	$hiddenfields .= '<input type="hidden" name="gatracking" value="'.$gatracking.'">';
	$hiddenfields .= '<input type="hidden" name="lldocroot" value="'.$lldocroot.'">';
	$hiddenfields .= '<input type="hidden" name="freeTrial" value="'.$freeTrial.'">';
	$hiddenfields .= '<input type="hidden" name="is_last_upsell" value="'.$is_last_upsell.'">';
	$hiddenfields .= '<input type="hidden" name="btntype" value="'.$btntype.'">';
    $hiddenfields .='  <input type="hidden" name="description" value="'.$description.'">';
    $hiddenfields .='  <input type="hidden" name="item_name" value="'.$item_name.'">';
    $hiddenfields .='  <input type="hidden" name="product_id" value="'.$productId.'">';
    $hiddenfields .='  <input type="hidden" name="cmcc_acctnum" value="'.$cmcc_acctnum.'">';
    $hiddenfields .='  <input type="hidden" name="payment_gateway" value="'.$payment_gateway.'">';
    $hiddenfields .='  <input type="hidden" name="payment_succ_page" value="'.$payment_succ_page.'">';
    $hiddenfields .='  <input type="hidden" name="payment_err_page" value="'.$payment_err_page.'">';
    $hiddenfields .='  <input type="hidden" name="amount" value="'.$amount.'">';
    $hiddenfields .='  <input type="hidden" name="trial_amount" value="'.$trial_amount.'">';
    $hiddenfields .='  <input type="hidden" name="total_occurrences" value="'.$total_occurrences.'">';
    $hiddenfields .='  <input type="hidden" name="invoice" value="'.$invoice.'">';
    $hiddenfields .='  <input type="hidden" name="is_recurring" value="'.$is_recurring.'">';
    $hiddenfields .='  <input type="hidden" name="recurring_cycle_1" value="'.$recurring_cycle_1.'">';
    $hiddenfields .='  <input type="hidden" name="recurring_cycle_2" value="'.$recurring_cycle_2.'">';
    $hiddenfields .='  <input type="hidden" name="recurring_cycle_3" value="'.$recurring_cycle_3.'">';
    $hiddenfields .='  <input type="hidden" name="testmode" value="'.$testmode.'">';
    $hiddenfields .='  <input type="hidden" id="cardtype" name="cardtype" value="">';
    $hiddenfields .='  <input type="hidden" id="statevar" name="statevar" value="">';
    $hiddenfields .='  <input type="hidden" id="statecode" name="statecode" value="">';
    $hiddenfields .='  <input type="hidden" id="countryvar" name="countryvar" value="">';
    $hiddenfields .='  <input type="hidden" id="countrycode" name="countrycode" value="">';
    $hiddenfields .='  <input type="hidden" id="shipstatevar" name="shipstatevar" value="">';
	if ($btntype == "addtocart") 
		$hiddenfields .='  <input type="hidden" id="coupon_code" name="coupon_code" value="'.$_SESSION['couponCode'].'">';
	
    $hiddenfields .='  <input type="hidden" id="shipstatecode" name="shipstatecode" value="">';
    $hiddenfields .='  <input type="hidden" id="shipcountryvar" name="shipcountryvar" value="">';
    $hiddenfields .='  <input type="hidden" id="shipcountrycode" name="shipcountrycode" value="">';
    $hiddenfields .='  <input type="hidden" id="tandc_acceptance" name="tandc_acceptance" value="';
	$hiddenfields .='echo $require_tandc_acceptance; ?>">';
    $hiddenfields .='  <input type="hidden" name="is_submitted" value="'.$is_submitted.'">';
    $hiddenfields .='  <input type="hidden" name="redirect" value="'.$_SERVER['PHP_SELF'].'">';
    $hiddenfields .='  <input type="hidden" name="err_redirect" value="'.$_SERVER['REQUEST_URI'].'">';
    $hiddenfields .='  <input type="hidden" name="currency" value="'.$currency.'">';
    $hiddenfields .='  <input type="hidden" name="currency_symbol" value="'.$currency_symbol.'">';
	$hiddenfields .='  <input type="hidden" name="loadingimg" value="'.$loadingimg.'">';

	$imagefirst='';
	$imagesecond='';
	if(isset($firstimg) && ($firstimg !=''))
		$imagefirst='<img src="'.$firstimg.'" alt="" />';
	
	if(isset($secondimg) && ($secondimg !=''))
		$imagesecond='<img src="'.$secondimg.'" alt="" />';
	
	/*	$firstmsg='<div id="orderTestimonials">
					<div class="testimonialTitle">Nullam tristique cursus varius.</div>
					Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Etiam egestas nisi a augue scelerisque dapibus. Duis blandit nunc ut quam.
					<div class="testimonialUser">&mdash; Veena Prashanth</div>
				</div>';
	*/
	if($ccimages && $ccimages != "") {
	}
	else {
		$ccimages= '<img src="/dap/images/checkout/cc_visa.png" alt="" />;
		  <img src="/dap/images/checkout/cc_master.png" alt="" />;
		  <img src="/dap/images/checkout/cc_amex.png" alt="" />;
		  <img src="/dap/images/checkout/cc_discover.png" alt="" />';
	}
								
	$current_msg = str_replace( '%%PAYPALBUTTON%%', $paypalbutton, $current_msg);
	$current_msg = str_replace( '%%FIRSTMESSAGE%%', htmlspecialchars_decode(urldecode($firstmsg)), $current_msg);
	$current_msg = str_replace( '%%SECONDMESSAGE%%', htmlspecialchars_decode(urldecode($secondmsg)), $current_msg);
	$current_msg = str_replace( '%%THIRDMESSAGE%%', htmlspecialchars_decode(urldecode($thirdmsg)), $current_msg);
	$current_msg = str_replace( '%%FIRSTIMAGE%%', $imagefirst, $current_msg);
	$current_msg = str_replace( '%%SECONDIMAGE%%', $imagesecond, $current_msg);
	$current_msg = str_replace( '%%LINK%%', $link, $current_msg);
	$current_msg = str_replace( '%%PAYPALIMGSRC%%', $paypalimg, $current_msg);
	$current_msg = str_replace( '%%CCIMAGES%%', $ccimages, $current_msg);
	$current_msg = str_replace( '%%CARDTYPE%%', $cctype, $current_msg);
	$current_msg = str_replace( '%%CARDNUM%%', $cardnumber, $current_msg);
	$current_msg = str_replace( '%%EXPIRATIONDATE%%', $expirationdate, $current_msg);
	$current_msg = str_replace( '%%CARDCODE%%', $cardcode, $current_msg);
	$current_msg = str_replace( '%%ACTIONBUTTON%%', $actionbutton, $current_msg);
	$current_msg = str_replace( '%%HIDDENFIELDS%%', $hiddenfields, $current_msg);
	$current_msg = str_replace( '%%TERMSTEXT%%', $tandc, $current_msg);
	$current_msg = str_replace( '%%TERMSBOX%%', $termsbox, $current_msg);
	
	echo $content = $current_msg;
	?>
    <!--End Payment Information--->
      
    
<!-- entire BODY -->
<script type='text/javascript' language='javascript' src='<?php echo $siteurlhttps; ?>/dap/javascript/dapcart/paymentvalidation.js'></script>
<script type='text/javascript' language='javascript' src='<?php echo $siteurlhttps; ?>/dap/javascript/dapcart/jquery.validate.js'></script>
<script type='text/javascript' language='javascript' src='<?php echo $siteurlhttps; ?>/dap/javascript/dapcart/languages/jquery.maskedinput.min.js'></script>
<script type='text/javascript' language='javascript' src='<?php echo $siteurlhttps; ?>/dap/javascript/dapcart/languages/validationMessages.js'></script>


<script language="javascript">
onPageLoad();
var showpaypal="<?php echo $showpaypal;?>";
var showcc="<?php echo $showcc;?>";


if(showpaypal=="N") {
jQuery("#payWithCC").css("width", "100%");
jQuery("#payWithCC .formLabel").css("width","200px");
jQuery(".checkoutForms .formLabel").css("float","left");
jQuery("#payWithCC .ccImages").css("float","left");
jQuery("#payWithCC input[type='text']").css("max-width","208px");
}
else if (showcc=="N") {
  jQuery("#payPal").css("width", "100%");
//  jQuery("#payPal img").css("width", "60px");
 // jQuery("#payPal img").css("height", "40px");
  jQuery("#payPal").css("height", "20px");
  jQuery("#paymentInformation").css("width", "100%");
  //jQuery("#paymentInformation").css("height", "50px");
  
}
else {
	jQuery("#payWithCC input[type='text']").css("max-width","168px");

}
//enableButton(document.getElementById('btnSubmit'),'Submit Order');
</script>

</body>
</html>
<?php if(isset($cart_footer)) echo $cart_footer;?>