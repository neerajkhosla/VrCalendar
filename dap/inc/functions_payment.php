<?php

	function validateCoupon($productId, $couponCode) {
		
		if ($couponCode != "") {
			$coupon = Dap_Coupon::loadCouponByCode($couponCode); //loads coupon details from db
			$couponId=$coupon->getId();
			
			if (isset($coupon) && (isValidCoupon($coupon) == TRUE)) {
				logToFile("functions.php: before calling findCouponIdAndProductId(), couponId=" . $couponId . " productId=" . $productId);
				$productCoupon =	Dap_ProductCoupon::findCouponIdAndProductId($productId, $couponId);
					//See if match found for coupon and product
				if (isset($productCoupon)) {
					$discount_amount = $coupon->getDiscount_amt();
					logToFile("functions.php: after calling findCouponIdAndProductId(), discount_amount=" . $discount_amount);
					return 1;
					
				}
			} //coupon found in db
			else {
				$err = "functions.php: coupon code=" . $coupon_code . " exists but not valid. Either coupon start date is in future, or coupon has expired or max_usage limit reached. ";
				logToFile($err);
				return 0;
			}
			
			if ((!isset($coupon)) || (!isset($productCoupon))) {
				$err = "functions.php: coupon code=" . $coupon_code . " not found";
				logToFile($err);
				return 0;
			}
			
		} // coupon code set 
		
	} //validateCoupon
	
	
	function validateCouponForCART ($couponCode) {
		
		if ($couponCode != "") {
			$coupon = Dap_Coupon::loadCouponByCode($couponCode); //loads coupon details from db
			$couponId=$coupon->getId();
			
			if (isset($coupon) && (isValidCoupon($coupon) == TRUE)) {
				logToFile("functions.php: is valid coupon, couponId=" . $couponId);
			} //coupon found in db
			else {
				$err = "functions.php: coupon code=" . $coupon_code . " exists but not valid. Either coupon start date is in future, or coupon has expired or max_usage limit reached. ";
				logToFile($err);
				return 0;
			}
			
			if ((!isset($coupon)) || (!isset($productCoupon))) {
				$err = "functions.php: coupon code=" . $coupon_code . " not found";
				logToFile($err);
				return 0;
			}
			
		} // coupon code set 
		
		
	} //validateCouponForCART
	
	function isValidCoupon($coupon) {
		
		if (isset($coupon)) {
			$couponId = $coupon->getId();
			
			$start_date = $coupon->getStart_date();
			$end_date = $coupon->getEnd_date();
			$actual_usage = $coupon->getActual_usage();
			$max_usage = $coupon->getMax_usage();
			
			$current_date = date('Y-m-d', strtotime('now'));

			if ($start_date > $current_date ) {
				logToFile("functions.php: couponId=" . $couponId . " not effective yet. Start date=" . $start_date . " but current_date=" . $current_date);
				return FALSE;
			}
			
			if ($current_date > $end_date ) {
				logToFile("functions.php: couponId=" . $couponId . " has expired. End date=" . $end_date . " but current_date=" . $current_date);
				return FALSE;
			}
			
			if ($current_date > $end_date ) {
				logToFile("functions.php: couponId=" . $couponId . " has expired. End date=" . $end_date . " but current_date=" . $current_date);
				return FALSE;
			}
			
			if ($actual_usage >= $max_usage ) {
				logToFile("functions.php: couponId=" . $couponId . " max usage limit reached, actual_usage=" . $actual_usage  . " but max_usage=" . $max_usage);
				return FALSE;
			}
		
			logToFile("functions.php: couponId=" . $couponId . " is valid");
			return TRUE;
		} //coupon found in db
		else {
			$err = "functions_payment.php: coupon not found";
			logToFile($err);
			return FALSE;
		}
		
	} //validateCoupon
	
	
	function validateProduct($product, &$post, $itemName, $couponCode, $useTrialPrice="") {
		if(!isset($product)) {
			$msg = "DAP Paypal IPN Error (Rejected): IPN Product Name = ". $itemName . " for user=" . $post['payer_email'] . " does not match any DAP Product Name";
			logToFile($msg);
			sendAdminEmail("DAP paypal IPN error", $msg);
			return FALSE;
		}
		
		$productId= $product->getId();
		$amount = $post['mc_gross'];
		$productName = $product->getName();
		
		if ($post['discount']) {
			$amount = $post['mc_gross'] + $post['discount'];
		}
		
		$dap_price = $product->getPrice();
		
		if ($post['txn_type'] == "subscr_payment") {
			if ($product->getTrial_price() > 0) 
				$dap_price = $product->getTrial_price();
		   else 
				$dap_price =  $product->getPrice();
		}
		
		logToFile("functions_payment.php: dap derived price=" . $dap_price);
		logToFile("functions_payment.php: dap product trial price=" . $product->getTrial_price());
		logToFile("functions_payment.php: dap product price=" . $product->getPrice());
				
		logToFile("functions_payment.php: txn_type=" . $post['txn_type']);
		logToFile("functions_payment.php: payment amount=" . $amount);
		logToFile("functions_payment.php: useTrialPrice=" . $useTrialPrice);
		
		if ($dap_price > 0) {
		  logToFile("functions_payment.php: dap price=" . $dap_price);
		  logToFile("functions_payment.php: amount=" . $amount);
		  
		  logToFile("functions_payment.php: discount=" . $post['discount']);
		  logToFile("functions_payment.php: txn_type=" . $post['txn_type']);
				  
		  if (($post['txn_type'] != "subscr_payment") && ($amount < $dap_price)) {
			logToFile("functions_payment.php: User Paid less than dap price");
			$error="N";  
			  //check if matches special storefront price
		/*	$storefrontProductOptions = Dap_StoreFrontProducts::loadStoreFrontOptions($product->getId());
			if(isset($storefrontProductOptions)) {
			  $storefront_price=$storefrontProductOptions->getStorefront_price();
			  logToFile("functions_payment.php: STOREFRONT PRICE=" . $storefront_price);
			  if(intval($amount)==intval($storefront_price)) {
				  logToFile("functions_payment.php: STOREFRONT PRICE=" . $storefront_price . " matches the incoming price. Validation PASS");
				  $error="N";
			  }
			}*/
			if($error=="Y") { 
			  $msg = "DAP Paypal IPN Error (Rejected)...:  Per the Paypal IPN, user=" . $post['payer_email'] . " purchased product=". $itemName . " for amount=" . $post['mc_gross'] . " after a discount of " . $post['discount'] . " (via Coupon Code=" . $couponCode . ") was applied. But adding up the discount amount with the purchase amount still does not match the trial amount=" . $dap_trial_amount . " or the price=" . $dap_price . " defined in DAP Products Page. The IPN was rejected due to price mismatch to make sure the transaction was not hacked. Please validate this transaction in your Paypal account and provide access via DAP admin panel for a valid purchase";
			  
			  logToFile($msg);
			  sendAdminEmail("DAP paypal IPN error", $msg);
			  return FALSE;	
			}
		  }
		  else if ($post['txn_type'] == "subscr_payment") {
			$discount_amt = "";
			$recurring_discount_amt = "";
			$initial_amount = "";
			$initial_trial_amount = "";
			
			if ($couponCode != "") {
				$coupon = Dap_Coupon::loadCouponByCode($couponCode); //loads coupon details from db
				if (isset($coupon)) {
					$post['coupon_id'] = $coupon->getId();
					logToFile("functions_payment.php: set coupon Id = " . $post['coupon_id']);
					$discount_amt = $coupon->getDiscount_amt();
					$recurring_discount_amt = $coupon->getRecurringDiscount_amt();
					
					$initial_amount = $product->getPrice() - $discount_amt;
					$initial_trial_amount = $product->getTrial_price() - $discount_amt;
					
					$recurring_amount = $product->getPrice() - $recurring_discount_amt;
					
					logToFile("validateProduct(): subscription: discount_amt=" . $discount_amt);
					logToFile("validateProduct(): subscription: recurring_discount_amt=" . $recurring_discount_amt);
				}
			}
			
			if (($useTrialPrice == "N") && (($dap_price < $product->getPrice()) && ($dap_price < $recurring_amount))) {
			
			    if(($initial_amount > 0) || ($initial_trial_amount > 0) || ($recurring_amount > 0) || ($post['mc_gross']>=$initial_amount))
			      return TRUE;
				
				$msg = "DAP Paypal IPN Error (Rejected):  Per the Paypal IPN, user=" . $post['payer_email'] . " purchased product=". $productName . " for amount=" . $dap_price . ". But the purchase amount=" . $post['mc_gross'] . " does not match the product price amount=" . $product->getPrice() . " defined in DAP Products Page OR the recurring discount amount=" . $recurring_amount. ". The IPN was rejected due to price mismatch to make sure the transaction was not hacked. Please validate this transaction in your Paypal account and provide access via DAP admin panel for a valid purchase";
				
				logToFile($msg);
				sendAdminEmail("DAP paypal IPN error", $msg);
				return FALSE;	
			}
			else if (($useTrialPrice != "N") && (($dap_price < $product->getPrice()) && ($dap_price < $product->getTrial_price()) && ($dap_price < $initial_amount) & ($dap_price < $initial_trial_amount))) {
				$msg = "DAP Paypal IPN Error (Rejected):  Per the Paypal IPN, user=" . $post['payer_email'] . " purchased product=". $productName . " for amount=" . $post['mc_gross']. ". But the purchase amount does not match the trial amount=" . $product->getTrial_price() . " or the price=" . $dap_price . " defined in DAP Products Page. It also didnot match the discount trial amount=" . $initial_trial_amount . " Or the discount amount=" . $initial_amount . ". The IPN was rejected due to price mismatch to make sure the transaction was not hacked. Please validate this transaction in your Paypal account and provide access via DAP admin panel for a valid purchase";
				logToFile($msg);
				sendAdminEmail("DAP paypal IPN error", $msg);
				return FALSE;																																				
			}
		  }
		  else if (intval($amount) < intval($dap_price)) {
			$msg = "DAP Paypal IPN Error (Rejected) -  The user=" . $post['payer_email'] . " purchased product=". $productName . " for amount=" . $post['mc_gross']. "But the purchase amount does not match the trial amount=" . $dap_trial_amount . " or the price=" . $dap_price . " defined in DAP Products Page. The IPN was rejected due to price mismatch to make sure the transaction was not hacked. Please validate this transaction in your Paypal account and provide access via DAP admin panel for a valid purchase";
			
			logToFile($msg);
			sendAdminEmail("DAP paypal IPN error", $msg);
			return FALSE;	
		  }
		}
		
		if ( (isset($post['discount'])) && ($post['discount'] > 0)) {
			logToFile("functions_payment.php: discount found=" . $post['discount']);
			logToFile("functions_payment.php: couponCode=" . $couponCode);
			
			$coupon = Dap_Coupon::loadCouponByCode($couponCode); //loads coupon details from db
			if(isset($coupon)) {
				$post['coupon_id'] = $coupon->getId();
				logToFile("functions_payment.php: set coupon Id = " . $post['coupon_id']);
			}
		}
					  
		logToFile("functions_payment.php: product price validation successful");
		return TRUE;
	
	}
	
	function validateCartForDAPGenBTN(&$post, $itemName, $couponCode) {
		$product = Dap_Product::loadProductByName($post['item_name']);
					
		// Extra security check for dap generated buttons, coupons
		if(!isset($product)) {
			$msg = "DAP Paypal IPN Error (Rejected): IPN Product Name = ". $post['item_name'] . " for user=" . $post['payer_email'] . "does not match any DAP Product Name";
			logToFile($msg);
			sendAdminEmail("DAP paypal IPN error", $msg);
			return FALSE;
		}
		
		$productId= $product->getId();
		
		if ($product->getTrial_price() > 0) 
			$dap_price = $product->getTrial_price();
		 else 
			$dap_price =  $product->getPrice();
				
		$price= intval($dap_price * $post['quantity']);
		$gross = intval($post['mc_gross']);
		logToFile("functions_payment: DAP product price=" . $price . " product quantity=" . $post['quantity']);
		logToFile("functions_payment: paypal mc_gross=" . $post['mc_gross']);
		
		
		if ((strstr($itemName,"DCA:") != FALSE) && ($couponCode != "")) {
			logToFile("functions_payment: coupon used in DAP Cart - addtocart context, validatecoupon now, productId=".$productId, LOG_DEBUG_DAP);		
			$discount_amt = validateCoupon($productId, $couponCode);
			logToFile("functions_payment: coupon used in DAP Cart - addtocart context, discount_amt=".$discount_amt, LOG_DEBUG_DAP);			
			if($discount_amt > 0) {
			  logToFile("functions_payment: coupon used in DAP Cart - addtocart context, discount_amt=".$discount_amt . "quantity=".$post["quantity"], LOG_DEBUG_DAP);	
			  $coupon = Dap_Coupon::loadCouponByCode($couponCode); //loads coupon details from db
			  $post['coupon_id'] = $coupon->getId();
			  
			  logToFile("functions_payment: coupon used in DAP Cart: after discount, new coupon=" . $post['coupon_id'], LOG_DEBUG_DAP);	
			  return TRUE;
			}

			else {
			  $post['coupon_id'] = NULL;
			  logToFile("functions_payment: coupon EMPTY for productId=".$productId, LOG_DEBUG_DAP);	
			  return TRUE;
			}
		}
		
		
		if (($product->getPrice() > 0) && ($gross < $price) && ($couponCode != "")) {
			$msg = "DAP Paypal IPN Error (Rejected): IPN Product Name = ". $post['item_name'] . ", for user=" . $post['payer_email'] . ", was for amount=" . $post['mc_gross'] . ", but it does not match the price defined in DAP Products Page. The DAP Products Price is " . $product->getPrice() . ". The IPN was rejected.";
			logToFile($msg);
			sendAdminEmail("DAP paypal IPN error", $msg);
			return FALSE;	
		}
		else {
			logToFile("functions_payment: price check(=" . $price . ") passed for item=" . $post['item_name'] , LOG_DEBUG_DAP);
		}
		
		logToFile("functions_payment: current mc_gross=" . $post['mc_gross'], LOG_DEBUG_DAP);
		
		logToFile("functions_payment: discount_amount=" . $post['discount'], LOG_DEBUG_DAP);	
		logToFile("functions_payment: Apply coupon to right product, custom=" . $post['custom'], LOG_DEBUG_DAP);	
		logToFile("functions_payment: Apply coupon to right product, item_name in post=" . $post['item_name'], LOG_DEBUG_DAP);	
		logToFile("functions_payment: Apply coupon to right product, item_name in custom=" . $itemName, LOG_DEBUG_DAP);	
		logToFile("functions_payment: Apply coupon to right product, couponCode=" . $couponCode, LOG_DEBUG_DAP);
		
		
		
			
		if ( ($post['discount'] > 0)  && ($couponCode != "") ) {
			logToFile("functions_payment: coupon used, apply price check for discount amount to make sure it matches the discount amount in DAP", LOG_DEBUG_DAP);	
			$discount_amt = validateCoupon($productId, $couponCode);
			if ($discount_amt > 0) {
				
				if (($discount_amt * $post["quantity"]) != ($post['discount'])) {
					$msg = "functions_payment: DAP Paypal IPN Error (Rejected): coupon code = ". $couponCode . " has the discount amount set to " . $discount_amt . " in DAP but it does not match the discount amount=" . $post['discount'] . " in the Paypal IPN notification for the product=" . $itemName . " and for the user=" . $post['payer_email']. ". Could not process paypal transaction for user=" . $post['payer_email'];
					logToFile($msg);
					sendAdminEmail("DAP paypal IPN error", $msg);
					return FALSE;
				}
				
				logToFile("functions_payment: coupon used, price check passed", LOG_DEBUG_DAP);
				logToFile("functions_payment: before discount, old mc_gross=" . $post['mc_gross'], LOG_DEBUG_DAP);
				
				$post['mc_gross'] = $post['mc_gross'] - ( $discount_amt * $post['quantity'] );
			
				$coupon = Dap_Coupon::loadCouponByCode($couponCode); //loads coupon details from db
				$post['coupon_id'] = $coupon->getId();
				
				logToFile("functions_payment: after discount, new mc_gross=" . $post['mc_gross'], LOG_DEBUG_DAP);	
			
			}
			else {
				$msg = "functions_payment: DAP Paypal IPN Error (Rejected): coupon code = ". $couponCode . ", used by the user=" . $post['payer_email'] . ", is not tied to the product=" . $itemName . ". Could not process paypal transaction for user=" . $post['payer_email'];
				logToFile($msg);
				sendAdminEmail("DAP paypal IPN error", $msg);
				return FALSE;		
			}
			
		}
		else {
			logToFile("functions_payment: validateCartForDAPGenBTN completed validation successfully", LOG_DEBUG_DAP);	
		}
						
		return TRUE;
	}
	
		
	function calculateTimedPricing($productId, $use_amount) {
		
		$product  = Dap_Product::loadProduct($productId);
		
		$amount = 0;
		$timed_price_start_date = $product->getTimed_pricing_start_date();
		
		$price_increment = $product->getPrice_increment();
		$price_increment_ceil = $product->getPrice_increment_ceil();
		
		$num_sales = $product->getNum_sales();
		$num_days = $product->getNum_days();
		
		logToFile("functions_payment.php: num_sales=" . $num_sales, LOG_DEBUG_DAP);		
		logToFile("functions_payment.php: num_days=" . $num_days, LOG_DEBUG_DAP);		
		logToFile("functions_payment.php: timed_price_start_date=" . $timed_price_start_date, LOG_DEBUG_DAP);		
		
		if ( (($num_sales > 0) || ($num_days > 0)) && (isset($timed_price_start_date)) && ($timed_price_start_date != "0000-00-00") && ($timed_price_start_date != "")) {
			//
			$today = date("Y-m-d");
			
			// calculate days elapsed since timed pricing took effect
			if ($timed_price_start_date > $today) {
				 logToFile("functions_payment.php: timed pricing set to a future date, time_price_start_date=" . $timed_price_start_date, LOG_DEBUG_DAP);		
			}
			else {
				if ($num_sales > 0) {
					$count = Dap_Transactions::loadNumSalesPerProduct($productId, $timed_price_start_date);
					logToFile("functions_payment.php: actual sales count=" . $count, LOG_DEBUG_DAP);	
					if ($count >= $num_sales) {
						$diff = (int)($count / $num_sales);
						$price_inc = $price_increment * $diff;
						$amount = $use_amount + $price_inc;
						logToFile("functions_payment.php: num sales, calculated amount=" . $amount, LOG_DEBUG_DAP);	
					}
				}
				else if ($num_days > 0) {
					$today_time = strtotime($today);
					$timed_price_start_time = strtotime($timed_price_start_date);
					$num_days_elapsed = (floor($today_time - $timed_price_start_time)/86400) + 1;
					
					//$num_days_elapsed = $today - $timed_price_start_date + 1;
					logToFile("functions_payment.php: num days elapsed=" . $num_days_elapsed, LOG_DEBUG_DAP);		
					
					if ($num_days_elapsed >= $num_days) {
						$diff = (int)($num_days_elapsed / $num_days);
						
					//calculate price increment				 
						$price_inc = $price_increment * $diff;
						logToFile("functions_payment.php: price_inc=" . $price_inc, LOG_DEBUG_DAP);		
						
						$amount = $use_amount + $price_inc;
						
						$amount = 0.01*round($amount*100);
						
						logToFile("functions_payment.php: timed price=" . $amount, LOG_DEBUG_DAP);	
					}
				}
			}
		}
		
		if ($price_increment_ceil != 0 && $amount > $price_increment_ceil) {
			logToFile("functions_payment.php: amount=" . $amount . " is greater than max ceiling. Set amount to max ceil value", LOG_DEBUG_DAP);	
			$amount = $price_increment_ceil;
		}
		
		return $amount;
	}
	
	
	function checkIfProratedApplies($product, $upgFromProduct, $params, $caller) {
 
	//get details of upgradeto ($product) and upgradefrom and make sure recurringcycles match
	//get access end of upgradeto product
	//checkEligibilityForProrated($product,$upgradeFrom);
	 
	$proratedval="";
	$upgFromR1=$upgFromProduct->getRecurring_cycle_1();
	$upgFromR3=$upgFromProduct->getRecurring_cycle_3();
	
	$upgToR1=$product->getRecurring_cycle_1();
	$upgToR3=$product->getRecurring_cycle_3();
	
	if( Dap_Session::isLoggedIn() ) { 
	//get userid
	  $session = Dap_Session::getSession();
	  $user = $session->getUser();
	  $user = Dap_User::loadUserById($user->getId()); //reload User object
	 // if(isset($user) &&  (!$session->isAdmin())) {
	  if(isset($user)) {
		$email=$user->getEmail();
	  }
	  else {
		logToFile("$caller: use a different browser, login as regular user (not dap admin), to test upgrade flow", LOG_DEBUG_DAP);	
		return false;
	  }
	}
	else {
		if(strstr($caller,"paypalCoupon.php") == FALSE) {
		  header("Location: ".Dap_Config::get("LOGIN_URL")."?msg=MSG_PLS_LOGIN&request=" . $_SERVER['REQUEST_URI']);
		  return false;
		}
		else return false;
	}
	
	if(($upgFromR1 != $upgToR1) && ($upgFromR3 != $upgToR3)) {
		logToFile("$caller: user not logged in, can't do upgrade flow, charge user full amount", LOG_DEBUG_DAP);	
		return false;
	}
	else {
	  logToFile("$caller: recurring cycle of  upgradeTo and upgradeFrom products match. Doing prorated. Simply charge user prorated amount for remaining period of current cycle", LOG_DEBUG_DAP);	
	 // $params['p1'] = $product->getRecurring_cycle_1();	
	  if(isset($user)) {
		$userId = $user->getId();
		$productId=$upgFromProduct->getId();
		$userProduct = Dap_UsersProducts::load($userId, $productId);
		
		if(isset($userProduct)) {
		  $accessEndDateOfUpgFrom = $userProduct->getAccess_end_date();
		  logToFile("$caller: prorated set, set billing date of upgTo to the access end date of upgfrom product=, accessEndDateOfUpgFrom=" . $accessEndDateOfUpgFrom, LOG_DEBUG_DAP);
		  $currentDate = date("Y-m-d", time());
		  logToFile("$caller: prorated set, currentDate=".$currentDate, LOG_DEBUG_DAP);
		  $ct = strtotime($currentDate);
		  $aet = strtotime($accessEndDateOfUpgFrom);
		  $numdays = ( ($aet - $ct) / (60*60*24) );	
		  //$numdays = $numdays + 1;
		  $noprorated=false;
		  
		  $accessStartDateOfUpgFrom = $userProduct->getAccess_start_date();
		  $ast = strtotime($accessStartDateOfUpgFrom);
		  $checknumdaysmemberofupgfrom = ($aet - $ast) / (60*60*24);	
		 
		  $numdaysuserhasbeenamember = ( ($ct - $ast) / (60*60*24) );	
		  logToFile("$caller: numdaysuserhasbeenamember=".$numdaysuserhasbeenamember, LOG_DEBUG_DAP);
		  logToFile("$caller: rcycle1=".$product->getRecurring_cycle_1(), LOG_DEBUG_DAP);
		   logToFile("$caller: numdays=".$numdays, LOG_DEBUG_DAP);
		  if(intval($numdaysuserhasbeenamember) < intval($product->getRecurring_cycle_1()) ) {
			  // user still in trial period of upgradeFrom so charge him full trial of upgradeTo, no prorated
			  logToFile("$caller: user still in first payment period of upgradeFrom, calculate prorated using initial /trial amount", LOG_DEBUG_DAP);
			  
			  $upgTo_trial_amount = $product->getTrial_price();
			  $upgTo_recurring_amount = $product->getPrice();
			  $upgFrom_trial_amount = $upgFromProduct->getTrial_price();
			  $upgFrom_recurring_amount = $upgFromProduct->getPrice(); 
			  
			  $recurring_cycle = $product->getRecurring_cycle_1();
			  if( ($upgFrom_trial_amount > 0) && ($upgTo_trial_amount > 0)) {
				  logToFile("$caller: user still in trial period of upgradeFrom and trial amount is set", LOG_DEBUG_DAP);
				  
				$val = $upgTo_trial_amount - $upgFrom_trial_amount;
				if($val <= 0) {
					//there is a trial but the trial amount is the same for old and new and the user is currently in trial period
					// so just charge a penny upto the next recurring cycle
					$val=0.01; // charge a penny instead of $0
				}
				
				$_SESSION['dctrial'] = "Y";
			  }
			  else if( ($upgFrom_trial_amount > 0) && ($upgTo_trial_amount <= 0)) {
				logToFile("$caller: user still in trial period of upgradeFrom and trial amount is set for upgfrom but not for upgto, use recurring amount of upgTo", LOG_DEBUG_DAP);
				  
				$val = $upgTo_recurring_amount - $upgFrom_trial_amount;
				if($val <= 0) {
					//there is a trial but the trial amount is the same for old and new and the user is currently in trial period
					// so just charge a penny upto the next recurring cycle
					$val=0.01; // charge a penny instead of $0
				}
				
				$_SESSION['dctrial'] = "Y";
			  }
			  else if( ($upgFrom_trial_amount <= 0) && ($upgTo_trial_amount > 0)) {
				logToFile("$caller: user still in trial period of upgradeFrom and trial amount is set for upgto but not for upgfrom, use recurring amount of upgfrom and trial amount of upgto.", LOG_DEBUG_DAP);
				  
				$val = $upgTo_trial_amount - $upgFrom_recurring_amount;
				if($val <= 0) { // user has already paid more than what the upgto's trial requires. so no charge for first cycle of upgto
					//there is a trial but the trial amount is the same for old and new and the user is currently in trial period
					// so just charge a penny upto the next recurring cycle
					$val=0.01; // charge a penny instead of $0
				}
				
				$_SESSION['dctrial'] = "Y";
			  }
			  else {
				logToFile("$caller: user still in trial period of upgradeFrom but no trial amount set so calculate prorated using recurring amount");	  
			 	$_SESSION['dctrial'] = "N";  
				$recurring_cycle = $product->getRecurring_cycle_1();
				$priceofUpgTo = $product->getPrice();
				$priceofUpgFrom = $upgFromProduct->getPrice();
				$val = $priceofUpgTo - $priceofUpgFrom;
				if($val<=0) {
					  //can't do prorated as the price of old/new both not set
					  // set initial price to 0.01 to charge user minimal fee upto the first recurring cycle
					 logToFile("$caller:amountdiff=0, cant prorated, cancel old, start new subscription. Simply charge user full amount for upgradeTo right away" . $_SESSION['dcp1'], LOG_DEBUG_DAP);
					 $val=0.01; 
				}	
					  
					  
					  
			  }
				 
			 
		  } // user has made just 1 payment (trial or initial)
		  else {
			$_SESSION['dctrial'] = "N";  
			$recurring_cycle = $product->getRecurring_cycle_3();
			$priceofUpgTo = $product->getPrice();
			$priceofUpgFrom = $upgFromProduct->getPrice();
			$val = $priceofUpgTo - $priceofUpgFrom;
			if($val<=0) {
				  //can't do prorated as the price of old/new both not set
				  // set initial price to 0.01 to charge user minimal fee upto the first recurring cycle
				 logToFile("$caller:amountdiff=0, cant prorated, cancel old, start new subscription. Simply charge user full amount for upgradeTo right away" . $_SESSION['dcp1'], LOG_DEBUG_DAP);
				 $val=0.01; 
			}	
			
		  }
		  
		  logToFile("$caller: prorated set,numdays to charge =".$numdays, LOG_DEBUG_DAP);
		  
		  $_SESSION['dcp1'] =  0; // when to start billing the user for upgradeTo product
		  
		  if($numdays) { 
			  
			  //recurring price of new - recurring price of old = $val
			  //Prorated = ( $val / subsequent recurring cycle of upgfrom  ) * (10 days)
			
			  logToFile("$caller:val= " . $val, LOG_DEBUG_DAP);
			  $proratedval = round(($val / $recurring_cycle)  * $numdays,2);
			  
			  //$params['a1'] = $prorated;
			  if($proratedval <= 0) $proratedval=0.01;
			  $_SESSION['dca1'] = $proratedval;
			  $_SESSION['dcp1'] =  $numdays; 
			  
			  logToFile("$caller:prorated=" . $proratedval, LOG_DEBUG_DAP);
			  return true;
			
		  }
		  else {
			  logToFile("$caller:user on last day of recurring for current product, start recurring payment (recurringcycle3) for next product right away,no prorated" . $proratedval, LOG_DEBUG_DAP);
			  return true;
		  }
		  
		}//userproduct exists
		else {
			logToFile("$caller:user does not have access to the upgradeTo product, no prorated", LOG_DEBUG_DAP);	
		}
	  } /* user exists, user needs to be logged in for prorated calculation  else user will be charged fully for the recurring cycle of upgradeto and dap will fully cancel upgradefrom*/
	}// else prorated
	
	return false;
	}  // function
		

	function processDAPSubscriptionRefund($post, $script_name) {
		logToFile("$script_name: processRefund(): ENTER processRefund()", LOG_DEBUG_DAP);
	    //  $post["payer_email"] = $post["email"];
		$user = Dap_User::loadUserByEmail($post["payer_email"]);
		logToFile("$script_name: processRefund(): user found: email=".$post["payer_email"], LOG_DEBUG_DAP);
		if($user) {
		  $userId = $user->getId();
		  $product = Dap_Product::loadProductByName($post["item_name"]);
		  if($product) {
			logToFile("$script_name: processRefund(): product found: product=".$post["item_name"], LOG_DEBUG_DAP);
			$productId=$product->getId();
			$userProduct = Dap_UsersProducts::load($userId, $productId);
			if(isset($userProduct)) {
				$transactionId = $userProduct->getTransaction_id();
				logToFile("$script_name: processRefund(): transactionId=".$transactionId);
				$transactionIdRefund = Dap_Transactions::negate($transactionId);
				if($transactionIdRefund){
				   logToFile("$script_name: processRefund(): refund complete, transactionIdRefund=".$transactionIdRefund, LOG_DEBUG_DAP); 
				   //remove user's access to product
				  // Dap_UsersProducts::removeUsersProducts($userId, $productId);
				   
				   logToFile("$script_name: processRefund(): refund complete, remove user from product completed successfully", LOG_DEBUG_DAP); 
				}
			}
		  }
		}
	}

	function processDAPSubscriptionCancellation($post, $script_name) {
		//$post["payer_email"] = $post["email"];
		logToFile("$script_name: processSubscriptionCancellation(): payer_email= " . $post["payer_email"], LOG_DEBUG_DAP );
		if(isset($post["payer_email"])) {
			
		  $user = Dap_User::loadUserByEmail($post["payer_email"]);
		  logToFile("$script_name: processSubscriptionCancellation(): load user: " . $post["payer_email"], LOG_DEBUG_DAP); 
		  if($user) {
			$userId = $user->getId();  
			logToFile("$script_name: processSubscriptionCancellation(): loadProductByName : " . $post["item_name"], LOG_DEBUG_DAP); 
			$product = Dap_Product::loadProductByName($post["item_name"]);
			if($product) {
			  $productId=$product->getId();
			  $userProduct = Dap_UsersProducts::load($userId, $productId);
			  logToFile("$script_name: processSubscriptionCancellation(): userId=".$userId.", productId=".$productId, LOG_DEBUG_DAP);
			
			  if($userProduct != NULL) {
				  $expaction=$product->getAccessExpirationAction();
				  if(($product->getAccessExpirationAction() == "EXPIREACCESS")) {
					  
					logToFile("EXPIRE ACCESS END DATE TO TODAY", LOG_DEBUG_DAP);
				
					$accessEndDate = date("Y-m-d", time());
					$yesterday =  date("Y-m-d", time() - 3600*24);
					$access_start_date =  $userProduct->getAccess_start_date();
					
					$asd = strtotime($access_start_date);
					$t = strtotime($accessEndDate);
					$numdays = $t - $asd;		  
					if($numdays) { 
					   // access end date to yesterday if access start date < today
					   $accessEndDate = $yesterday;
					   logToFile("dap-changeSubscriptionStatus: numdays=" . $numdays . " AND access end to yesterday=" . $accessEndDate, LOG_DEBUG_DAP);
					}
					//always set access end date to today so prevent accidental remove due to timing issue in payment notification
					logToFile("dap-changeSubscriptionStatus: access end=" . $accessEndDate, LOG_DEBUG_DAP);
					$userProduct->setAccess_end_date($accessEndDate);	
					$userProduct->update();
					  
					//Dap_UsersProducts::expireUserProductAccessOnCancel($userId,$productId);
					logToFile("$script_name: processSubscriptionCancellation(): called expireUserProductAccessOnCancel() to expire userId=$userId access to $productId", LOG_DEBUG_DAP);
				  }
				  else  if(($product->getAccessExpirationAction() == "NOACTION")) {
					logToFile("$script_name: processSubscriptionCancellation(): NO ACTION TAKEN PER PRODUCT EXPIRATION SETTING", LOG_DEBUG_DAP);
				  }
				  else { // remove product row fully  
					logToFile("$script_name: processSubscriptionCancellation(): removeUsersProducts()", LOG_DEBUG_DAP);
					$complete=1;
					Dap_UsersProducts::removeUsersProducts($userId, $productId, -1, $complete);
					logToFile("$script_name: processSubscriptionCancellation(): removeUsersProducts: cancellation complete", LOG_DEBUG_DAP);
				  }
			  }
			  else logToFile("$script_name: processSubscriptionCancellation(): USERPROUDUCT NOT FOUND", LOG_DEBUG_DAP);
			} //$product
		  }//if($user) {
		} //if(isset($post["payer_email"])) 
		
	}

?>