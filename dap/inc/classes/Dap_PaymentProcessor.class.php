<?php

class Dap_PaymentProcessor extends Dap_Base {
	/*
		Just get all needed ele ments of the incoming paypal request and record it along with the blob.
		This does not verify anything. Just parse and record.
		Returns: the last insert id of mysql. Caller should use this to invoke processPaypalTransaction method . That methods needs it as handle.
	*/
	
	
	
	public static function recordStripeIncoming($post,$customerId) {
	  try {
		$pDesc = addslashes($post['item_name']);
		
		if (isset($post['product_name'])) {
			$pDesc = trim($post['product_name'], " \t\n\r");
		}
		else { 
			$pDesc = trim($post['item_name'], " \t\n\r");
		}
				
		logToFile("Dap_PaymentProcessor:recordStripeIncoming(). find product description match" . $pDesc, LOG_DEBUG_DAP);
				
		$product = Dap_Product::loadProductByDesc(trim($pDesc));
		
		logToFile("Dap_PaymentProcessor:recordStripeIncoming(). incoming recurring product description=" . $pDesc, LOG_DEBUG_DAP);
				
		if (!isset($product)) {
			$product = Dap_Product::loadProductByName(trim($pDesc));
		}
				  
		if(isset($product)) {
		  logToFile($product->getId(), LOG_INFO_DAP);
		  $post['item_number'] = $product->getId();				
		} 
		else {
		  $post['item_number'] = 0;
		  logToFile("Product Mismatch. Incoming:".$pDesc.":", LOG_FATAL_DAP);
		  $product_mismatch = Dap_Config::get("PRODUCT_MISMATCH");
		  if(isset($product_mismatch) && $product_mismatch == "Y") {
			sendAdminEmail("DAP and Stripe product mismatch error", "DAP Stripe Error (Accepted): Stripe Product Name = " . $pDesc . " does not match any DAP Product Name. But ACCEPTING MISMATCH because of your Config settings (Product Mismatch)");
			$post['item_number'] = 0;
		  } else {
		  //SEND ADMIN NOTIFICATION THAT WE ARE REJECTING THE IPN DATA.
			$msg = "DAP Stripe Error (Rejected): Stripe Product Name  = " . $pDesc . " does not match any DAP Product Name. But REJECTING MISMATCH  because of your Config settings (Product Mismatch)";
			logToFile($msg);
			sendAdminEmail("DAP and Stripe product mismatch error", $msg);
			return -1;
		  }
		}			
		  
		$req = "";
		foreach ($post as $key => $value) {
		  $value = stripslashes(urldecode($value));
		  $req .= "&$key=$value";
		  logToFile("Dap_PaymentProcessor::recordStripeIncoming(): Key=".$key.", Value=".$value); 
		}
	  	
		if($customerId != "") {
			logToFile("Dap_PaymentProcessor::recordStripeIncoming(): stripe_customer_id=".$customerId); 
			$req .= "&stripe_customer_id=$customerId";
		}
		//lets do the insert and get the newly created  id out.
		$transaction = new Dap_Transactions();
		$transaction->setTrans_num($post['txn_id']);
		$transaction->setTrans_type($post['txn_type']);
		$transaction->setPayment_status($post['payment_status']);
		$transaction->setPayment_currency($post['mc_currency']);
		$transaction->setPayment_value($post['amount']);
		$transaction->setPayer_email($post['email']);
		$transaction->setPayment_processor("STRIPE");
		$transaction->setProduct_id($post['item_number']);
		$transaction->setCoupon_id($post['coupon_id']);		 
		
		$transaction->setTrans_blob($req);
		$transaction->create();
		logToFile("Dap_PaymentProcessor::recordStripeIncoming(): Insert into transactions Done. Returning transaction id: " . $transaction->getId()); 
		sendTransactionNotificationEmail($transaction);
		
		if( $post['coupon_id'] != "") {
		  if (!updateUsageById($post['coupon_id'])) {
			  logToFile("Dap_PaymentProcessor::recordStripeIncoming() Failed to update actual usage of coupon code: " . $post['coupon_id'],LOG_FATAL_DAP);
		  }
		  else {
			  logToFile("Dap_PaymentProcessor::recordStripeIncoming(): updated actual usage of coupon code: " . $post['coupon_id'],LOG_DEBUG_DAP);
		  }
		}
			
		return $transaction->getId();
	  } catch (PDOException $e) {
		  logToFile($e->getMessage(),LOG_FATAL_DAP);
		  throw $e;
	  } catch (Exception $e) {
		  logToFile($e->getMessage(),LOG_FATAL_DAP);
		  throw $e;
	  }
	}
	
	public static function recordStripeIncomingIPN($post,$customerId) {
	  try {
		$pDesc = addslashes($post['item_name']);
		
		if (isset($post['product_name'])) {
			$pDesc = trim($post['product_name'], " \t\n\r");
		}
		else { 
			$pDesc = trim($post['item_name'], " \t\n\r");
		}
				
		logToFile("Dap_PaymentProcessor::recordStripeIncomingIPN(). find product description match" . $pDesc, LOG_DEBUG_DAP);
				
		$product = Dap_Product::loadProductByDesc(trim($pDesc));
		
		logToFile("Dap_PaymentProcessor::recordStripeIncomingIPN(). incoming recurring product description=" . $pDesc, LOG_DEBUG_DAP);
				
		if (!isset($product)) {
			$product = Dap_Product::loadProductByName(trim($pDesc));
		}
				  
		if(isset($product)) {
		  logToFile($product->getId(), LOG_INFO_DAP);
		  $post['item_number'] = $product->getId();				
		} 
		else {
		  $post['item_number'] = 0;
		  logToFile("Dap_PaymentProcessor::recordStripeIncomingIPN: Product Mismatch. Incoming:".$pDesc.":", LOG_FATAL_DAP);
		  $product_mismatch = Dap_Config::get("PRODUCT_MISMATCH");
		  if(isset($product_mismatch) && $product_mismatch == "Y") {
			sendAdminEmail("DAP and Stripe product mismatch error", "DAP Stripe Error (Accepted): Stripe Product Name = " . $pDesc . " does not match any DAP Product Name. But ACCEPTING MISMATCH because of your Config settings (Product Mismatch)");
			$post['item_number'] = 0;
		  } else {
		  //SEND ADMIN NOTIFICATION THAT WE ARE REJECTING THE IPN DATA.
			$msg = "Dap_PaymentProcessor::recordStripeIncomingIPN: DAP Stripe Error (Rejected): Stripe Product Name  = " . $pDesc . " does not match any DAP Product Name. But REJECTING MISMATCH  because of your Config settings (Product Mismatch)";
			logToFile($msg);
			sendAdminEmail("DAP and Stripe product mismatch error", $msg);
			return -1;
		  }
		}			
		  
		$req = "";
		foreach ($post as $key => $value) {
		  $value = stripslashes(urldecode($value));
		  $req .= "&$key=$value";
		  logToFile("Dap_PaymentProcessor::recordStripeIncomingIPN(): Key=".$key.", Value=".$value); 
		}
	  	
		if($customerId != "") {
			logToFile("Dap_PaymentProcessor::recordStripeIncomingIPN(): stripe_customer_id=".$customerId); 
			$req .= "&stripe_customer_id=$customerId";
		}
		//lets do the insert and get the newly created  id out.
		$transaction = new Dap_Transactions();
		$transaction->setTrans_num($post['txn_id']);
		$transaction->setTrans_type($post['txn_type']);
		$transaction->setPayment_status($post['payment_status']);
		$transaction->setPayment_currency($post['payment_currency']);
		$transaction->setPayment_value($post['amount']);
		$transaction->setPayer_email($post['email']);
		$transaction->setPayment_processor("STRIPE");
		$transaction->setProduct_id($post['item_number']);
		$transaction->setCoupon_id($post['coupon_id']);		 
		
		$transaction->setTrans_blob($req);
		$transaction->create();
		logToFile("Dap_PaymentProcessor::recordStripeIncomingIPN(): Insert into transactions Done. Returning transaction id: " . $transaction->getId()); 
		sendTransactionNotificationEmail($transaction);
		
		if( $post['coupon_id'] != "") {
		  if (!updateUsageById($post['coupon_id'])) {
			  logToFile("Dap_PaymentProcessor::recordStripeIncomingIPN() Failed to update actual usage of coupon code: " . $post['coupon_id'],LOG_FATAL_DAP);
		  }
		  else {
			  logToFile("Dap_PaymentProcessor::recordStripeIncomingIPN(): updated actual usage of coupon code: " . $post['coupon_id'],LOG_DEBUG_DAP);
		  }
		}
			
		return $transaction->getId();
	  } catch (PDOException $e) {
		  logToFile($e->getMessage(),LOG_FATAL_DAP);
		  throw $e;
	  } catch (Exception $e) {
		  logToFile($e->getMessage(),LOG_FATAL_DAP);
		  throw $e;
	  }
	}
	
	
	public static function recordAuthnetIncoming($post) {
	  try {
		$pDesc = addslashes($post['item_name']);
		
		if (isset($post['product_name'])) {
			$pDesc = trim($post['product_name'], " \t\n\r");
		}
		else { 
			$pDesc = trim($post['item_name'], " \t\n\r");
		}
				
		logToFile("Dap_Payment:recordAuthnetIncoming(). find product description match" . $pDesc, LOG_DEBUG_DAP);
				
		$product = Dap_Product::loadProductByDesc(trim($pDesc));
		
		logToFile("Dap_Payment:recordAuthnetIncoming(). incoming recurring product description=" . $pDesc, LOG_DEBUG_DAP);
				
		if (!isset($product)) {
			$product = Dap_Product::loadProductByName(trim($pDesc));
		}
				  
		if(isset($product)) {
		  logToFile($product->getId(), LOG_INFO_DAP);
		  $post['item_number'] = $product->getId();				
		} 
		else {
		  $post['item_number'] = 0;
		  logToFile("Product Mismatch. Incoming:".$pDesc.":", LOG_FATAL_DAP);
		  $product_mismatch = Dap_Config::get("PRODUCT_MISMATCH");
		  if(isset($product_mismatch) && $product_mismatch == "Y") {
			sendAdminEmail("DAP and Authnet product mismatch error", "DAP Authnet Error (Accepted): Authnet Product Name = " . $pDesc . " does not match any DAP Product Name. But ACCEPTING MISMATCH because of your Config settings (Product Mismatch)");
			$post['item_number'] = 0;
		  } else {
		  //SEND ADMIN NOTIFICATION THAT WE ARE REJECTING THE IPN DATA.
			$msg = "DAP Authnet Error (Rejected): Authnet Product Name  = " . $pDesc . " does not match any DAP Product Name. But REJECTING MISMATCH  because of your Config settings (Product Mismatch)";
			logToFile($msg);
			sendAdminEmail("DAP and Authnet product mismatch error", $msg);
			return -1;
		  }
		}			
		  
		$req = "";
		foreach ($post as $key => $value) {
		  $value = stripslashes(urldecode($value));
		  $req .= "&$key=$value";
		  logToFile("Dap_PaymentProcessor::recordAuthnetIncoming(): Key=".$key.", Value=".$value); 
		}
	  
		//lets do the insert and get the newly created  id out.
		$transaction = new Dap_Transactions();
		$transaction->setTrans_num($post['txn_id']);
		$transaction->setTrans_type($post['txn_type']);
		$transaction->setPayment_status($post['payment_status']);
		$transaction->setPayment_currency($post['mc_currency']);
		$transaction->setPayment_value($post['amount']);
		$transaction->setPayer_email($post['payer_email']);
		$transaction->setPayment_processor("AUTHNET");
		$transaction->setProduct_id($post['item_number']);
		$transaction->setCoupon_id($post['coupon_id']);		  
		$transaction->setTrans_blob($req);
		$transaction->create();
		logToFile("Dap_PaymentProcessor::recordAuthnetIncoming(): Insert into transactions Done. Returning transaction id: " . $transaction->getId()); 
		sendTransactionNotificationEmail($transaction);
		
		if( $post['coupon_id'] != "") {
		  if (!updateUsageById($post['coupon_id'])) {
			  logToFile("Dap_PaymentProcessor::recordIncoming() Failed to update actual usage of coupon code: " . $post['coupon_id'],LOG_FATAL_DAP);
		  }
		  else {
			  logToFile("Dap_PaymentProcessor::recordIncoming(): updated actual usage of coupon code: " . $post['coupon_id'],LOG_DEBUG_DAP);
		  }
		}
			
		return $transaction->getId();
	  } catch (PDOException $e) {
		  logToFile($e->getMessage(),LOG_FATAL_DAP);
		  throw $e;
	  } catch (Exception $e) {
		  logToFile($e->getMessage(),LOG_FATAL_DAP);
		  throw $e;
	  }
	}

	public static function recordPaypalIncoming($post) {
		try {
			$product = '';
			if(isset($post['match_description']) && $post['match_description'] == 'Y') {
				logToFile("Dap_Payment:recordPaypalIncoming(). load product by description" . $pDesc, LOG_DEBUG_DAP);
				
				if (isset($post['product_name'])) {
					$pDesc = trim($post['product_name'], " \t\n\r");
				}
				else { 
					$pDesc = trim($post['item_name'], " \t\n\r");
				}
				
				logToFile("Dap_Payment:recordPaypalIncoming(). find product description match" . $pDesc, LOG_DEBUG_DAP);
				
				$product = Dap_Product::loadProductByDesc(trim($pDesc));
				logToFile("Dap_Payment:recordPaypalIncoming(). incoming recurring product description=" . $pDesc, LOG_DEBUG_DAP);
				
				if (!isset($product)) {
					$product = Dap_Product::loadProductByName(trim($pDesc));
				}
			}
			else if (isset($post['item_name']) && $post['item_name'] != '') {
				$pDesc = trim($post['item_name'], " \t\n\r\0\x0B.><*");
				$product = Dap_Product::loadProductByName(trim($pDesc));
			}
			else {
				$pDesc = trim($post['product_name'], " \t\n\r\0\x0B.><*");
				$product = Dap_Product::loadProductByName(trim($pDesc));
			}
			//echo sizeof($products);
			//if(sizeof($products) >0) {
			if(isset($product)) {
				logToFile($product->getId(), LOG_INFO_DAP);
				$post['item_number'] = $product->getId();				
			} else {
				$post['item_number'] = 0;
				logToFile("Product Mismatch. Incoming:".$pDesc.":", LOG_FATAL_DAP);
				$product_mismatch = Dap_Config::get("PRODUCT_MISMATCH");
				if(isset($product_mismatch) && $product_mismatch == "Y") {
					sendAdminEmail("DAP paypal IPN error", "DAP Paypal IPN Error (Accepted): IPN Product Name = " . $pDesc . " does not match any DAP Product Name. But ACCEPTING MISMATCH because of your Config settings (Product Mismatch)");
					$post['item_number'] = 0;
				} else {
					//SEND ADMIN NOTIFICATION THAT WE ARE REJECTING THE IPN DATA.
					$msg = "DAP Paypal IPN Error (Rejected): IPN Product Name = ". $pDesc . " does not match any DAP Product Name. But REJECTING MISMATCH it because of your Config settings (Product Mismatch)";
					logToFile($msg);
					sendAdminEmail("DAP paypal IPN error", $msg);
					return -1;
				}
			}			
			//$dap_dbh = Dap_Connection::getConnection();
			//loop over incoming post array
			$req = "";
			foreach ($post as $key => $value) {
				$value = urlencode(stripslashes($value));
				$req .= "&$key=$value";
				logToFile("Dap_PaymentProcessor::recordPaypalIncoming(): Key=".$key.", Value=".$value); 
			}
			logToFile("PAYPAL_INCOMING:".$req, LOG_INFO_DAP);
			//lets do the insert and get the newly created  id out.
			$transaction = new Dap_Transactions();
			$transaction->setTrans_num($post['txn_id']);
			$transaction->setTrans_type($post['txn_type']);
			$transaction->setPayment_status($post['payment_status']);
			
			if($post['currency'] != "")
			  $transaction->setPayment_currency($post['currency']);
			else
			  $transaction->setPayment_currency($post['mc_currency']);
			  
			$transaction->setPayment_value($post['mc_gross']);
			$transaction->setPayer_email($post['payer_email']);
			$transaction->setPayment_processor("PAYPAL");
			$transaction->setProduct_id($post['item_number']);
			$transaction->setCoupon_id($post['coupon_id']);
			
			$transaction->setTrans_blob($req);
			$transaction->create();
			logToFile("Dap_PaymentProcessor::recordPaypalIncoming(): Insert into transactions Done. Returning transaction id: " . $transaction->getId()); 
			sendTransactionNotificationEmail($transaction);
			
			if( $post['coupon_id'] != "") {
			  if (!updateUsageById($post['coupon_id'])) {
				  logToFile("Dap_PaymentProcessor::recordIncoming() Failed to update actual usage of coupon code: " . $post['coupon_id'],LOG_FATAL_DAP);
			  }
			  else {
				  logToFile("Dap_PaymentProcessor::recordIncoming(): updated actual usage of coupon code: " . $post['coupon_id'],LOG_DEBUG_DAP);
			  }
			}
			
			return $transaction->getId();
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}
	
	public static function recordIncoming($post) {
		try {
			$product = '';
			if(isset($post['match_description']) && $post['match_description'] == 'Y') {
				$pDesc = trim($post['product_name'], " \t\n\r\0\x0B.><*");
				$product = Dap_Product::loadProductByDesc(trim($pDesc));
				logToFile("Dap_Payment:recordIncoming(). incoming recurring product description=" . $pDesc, LOG_DEBUG_DAP);
			}
			else if (isset($post['item_name']) && $post['item_name'] != '') {
				logToFile("INCOMING:".$post, LOG_INFO_DAP);
				$pDesc = trim($post['item_name'], " \t\n\r\0\x0B.><*");
				$product = Dap_Product::loadProductByName(trim($pDesc));
			}
			else {
				$pDesc = trim($post['product_name'], " \t\n\r\0\x0B.><*");
				$product = Dap_Product::loadProductByName(trim($pDesc));
			}
			//echo sizeof($products);
			//if(sizeof($products) >0) {
			
			if(isset($product)) {
				logToFile($product->getId(), LOG_INFO_DAP);
				$post['item_number'] = $product->getId();				
			} else {
				$post['item_number'] = 0;
				logToFile("Product Mismatch. Incoming:".$pDesc.":", LOG_FATAL_DAP);
				$product_mismatch = Dap_Config::get("PRODUCT_MISMATCH");
				if(isset($product_mismatch) && $product_mismatch == "Y") {
					sendAdminEmail("IPN error", "IPN Error (Accepted): IPN Product Name = " . $pDesc . " does not match any DAP Product Name. But ACCEPTING MISMATCH because of your Config settings (Product Mismatch)");
					$post['item_number'] = 0;
				} else {
					//SEND ADMIN NOTIFICATION THAT WE ARE REJECTING THE IPN DATA.
					$msg = "IPN Error (Rejected): IPN Product Name = ". $pDesc . " does not match any DAP Product Name. But REJECTING MISMATCH it because of your Config settings (Product Mismatch)";
					logToFile($msg);
					sendAdminEmail("IPN error", $msg);
					return -1;
				}
			}			
			//$dap_dbh = Dap_Connection::getConnection();
			//loop over incoming post array
			$req = "";
			foreach ($post as $key => $value) {
				$value = urlencode(stripslashes($value));
				$req .= "&$key=$value";
				logToFile("Dap_PaymentProcessor::recordIncoming(): Key=".$key.", Value=".$value); 
			}
			
			logToFile("record transaction:".$req, LOG_INFO_DAP);
			//lets do the insert and get the newly created  id out.
			$transaction = new Dap_Transactions();
			$transaction->setTrans_num($post['txn_id']);
			$transaction->setTrans_type($post['txn_type']);
			$transaction->setPayment_status($post['payment_status']);
			$transaction->setPayment_currency($post['mc_currency']);
			$transaction->setPayment_value($post['mc_gross']);
			$transaction->setPayer_email($post['payer_email']);
			$transaction->setPayment_processor($post['payment_processor']);
			$transaction->setProduct_id($post['item_number']);
			$transaction->setCoupon_id($post['coupon_id']);
			$transaction->setTrans_blob($req);
			$transaction->create();
			logToFile("Dap_PaymentProcessor::recordIncoming(): Insert into transactions Done. Returning transaction id: " . $transaction->getId()); 
			sendTransactionNotificationEmail($transaction);
			
			if( $post['coupon_id'] != "") {
			  if (!updateUsageById($post['coupon_id'])) {
				  logToFile("Dap_PaymentProcessor::recordIncoming() Failed to update actual usage of coupon code: " . $post['coupon_id'],LOG_FATAL_DAP);
			  }
			  else {
				  logToFile("Dap_PaymentProcessor::recordIncoming(): updated actual usage of coupon code: " . $post['coupon_id'],LOG_DEBUG_DAP);
			  }
			}
		
			return $transaction->getId();
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}
	
	public static function recordInfusionSoftIncoming($post) {
		try {
			if (isset($post['item_name']) && $post['item_name'] != '')
				$pDesc = trim($post['item_name'], " \t\n\r\0\x0B.><*");
		 	else 
				$pDesc = trim($post['product_name'], " \t\n\r\0\x0B.><*");
				
			$product = Dap_Product::loadProductByName(trim($pDesc));
			//echo sizeof($products);
			//if(sizeof($products) >0) {
			if(isset($product)) {
				logToFile($product->getId(), LOG_INFO_DAP);
				$post['item_number'] = $product->getId();				
			} else {
				$post['item_number'] = 0;
				logToFile("Product Mismatch. Incoming:".$pDesc.":", LOG_FATAL_DAP);
				$product_mismatch = Dap_Config::get("PRODUCT_MISMATCH");
				if(isset($product_mismatch) && $product_mismatch == "Y") {
					logToFile("Dap_PaymentProcessor::recordInfusionSoftIncoming(): product_mismatch=" . $product_mismatch);
					//sendAdminEmail("DAP InfusionSoft IPN error", "DAP InfusionSoft IPN Error (Accepted): IPN Product Name = " . $pDesc . " does not match any DAP Product Name. But ACCEPTING MISMATCH because of your Config settings (Product Mismatch)");
					$post['item_number'] = 0;
					return -1;
				} else {
					//SEND ADMIN NOTIFICATION THAT WE ARE REJECTING THE IPN DATA.
					$msg = "DAP InfusionSoft IPN Error (Rejected): IPN Product Name = ". $pDesc . " does not match any DAP Product Name. But REJECTING MISMATCH it because of your Config settings (Product Mismatch)";
					logToFile($msg);
					//sendAdminEmail("DAP InfusionSoft IPN error", $msg);
					return -1;
				}
			}			
			//$dap_dbh = Dap_Connection::getConnection();
			//loop over incoming post array
			$req = "";
			foreach ($post as $key => $value) {
				$value = urlencode(stripslashes($value));
				$req .= "&$key=$value";
				logToFile("Dap_PaymentProcessor::recordInfusionSoftIncoming(): Key=".$key.", Value=".$value); 
			}
			logToFile("PAYPAL_INCOMING:".$req, LOG_INFO_DAP);
			
			
			$transNumFilter = $post['txn_id'];
			$emailFilter = "";
			$productIdFilter = "";
			$statusFilter = "";
		
			$TransactionsList = Dap_Transactions::loadTransactions($transNumFilter, $emailFilter, $productIdFilter, $statusFilter);
		
			if ($TransactionsList) {
				logToFile("Dap_PaymentProcessor::recordInfusionSoftIncoming(): transaction id exists, txn_id=" . $post['txn_id'], LOG_INFO_DAP);
				$post['txn_id'] = $post['txn_id'] . ":" . rand(10, 99); 
				logToFile("Dap_PaymentProcessor::recordInfusionSoftIncoming(): new transaction id=" . $post['txn_id'], LOG_INFO_DAP);
				
			}
			
			//lets do the insert and get the newly created  id out.
			$transaction = new Dap_Transactions();
			$transaction->setTrans_num($post['txn_id']);
			$transaction->setTrans_type($post['txn_type']);
			$transaction->setPayment_status($post['payment_status']);
			$transaction->setPayment_currency($post['mc_currency']);
			$transaction->setPayment_value($post['mc_gross']);
			$transaction->setPayer_email($post['payer_email']);
			$transaction->setPayment_processor("InfusionSoft");
			$transaction->setProduct_id($post['item_number']);
			$transaction->setTrans_blob($req);
			$transaction->create();
			logToFile("Dap_PaymentProcessor::recordInfusionSoftIncoming(): Insert into transactions Done. Returning transaction id: " . $transaction->getId()); 
			sendTransactionNotificationEmail($transaction);
			
			return $transaction->getId();
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}
	
	/*
		Just get all needed ele ments of the incoming clickbank request and record it along with the blob.
		This does not verify anything. Just parse and record.
		TODO: REMOVE THE EXAMPLE BELOW FOR PROD.
		Returns: the last insert id of mysql. Caller should use this to invoke processPaypalTransaction method . That methods needs it as handle.
		
					Example:http://www.mywebmasterinabox.com/cbdp/thankyou.php?
						item=27&
						cbreceipt=TEST8464&
						time=1204644040&
						cbpop=6FEFD25F&
						cbaffi=0&
						cname=jack+and+jill&
						cemail=test%40test.com&
						seed=MWJULAda&
					
	*/
	public static function recordCBIncoming($post) {
		try {
			logToFile("CLICK_INCOMING:".$req, LOG_INFO_DAP);
			logToFile("CLICK_INCOMING: cbreceipt=".$post['cbreceipt'], LOG_INFO_DAP);
			
			if(!isset($post['cproditem']) || 
				(!isset($post['ctransreceipt']) &&
				!isset($post['cbreceipt']))
			) {
				return;
			}
			//$dap_dbh = Dap_Connection::getConnection();
			//loop over incoming post array
			$req = "";
			foreach ($post as $key => $value) {
				$value = urlencode(stripslashes($value));
				$req .= "&$key=$value";
				logToFile("recordCBIncoming. POST. Key=".$key.",value=".$value); 
			}
			logToFile("CLICK_INCOMING:".$req, LOG_INFO_DAP);

			$pDesc="";
			if (isset($post['cprodtitle']) && $post['cprodtitle'] != '')
				$pDesc = trim($post['cprodtitle'], " \t\n\r\0\x0B.><*");
				
			$product = Dap_Product::loadProductByName(trim($pDesc));
			//echo sizeof($products);
			//if(sizeof($products) >0) {
			if(isset($product)) {
				logToFile($product->getId(), LOG_INFO_DAP);
				$post['item_number'] = $product->getId();				
			} else {
				$post['item_number'] = 0;
				logToFile("Product Mismatch. Incoming:".$pDesc.":", LOG_FATAL_DAP);
				$product_mismatch = Dap_Config::get("PRODUCT_MISMATCH");
				if(isset($product_mismatch) && $product_mismatch == "Y") {
					sendAdminEmail("DAP Clickbank INS error", "DAP Clickbank INS Error (Accepted): IPN Product Name = " . $pDesc . " does not match any DAP Product Name. But ACCEPTING MISMATCH because of your Config settings (Product Mismatch)");
					$post['item_number'] = 0;
				} else {
					//SEND ADMIN NOTIFICATION THAT WE ARE REJECTING THE IPN DATA.
					$msg = "DAP Clickbank INS Error (Rejected): INS Product Name = ". $pDesc . " does not match any DAP Product Name. But REJECTING MISMATCH it because of your Config settings (Product Mismatch)";
					logToFile($msg);
					sendAdminEmail("DAP Clickbank INS error", $msg);
					return -1;
				}
			}			
			
			//lets do the insert and get the newly created  id out.
			$transaction = new Dap_Transactions();
			$transaction->setTrans_num($post['ctransreceipt']);
			$transaction->setTrans_type("ctransaction");
			$transaction->setPayment_status("Completed");
			$transaction->setPayment_currency("USD");
		
			if( (!isset($post["payment_processor"])) && ($post["payment_processor"]=="")) {
				$transaction->setPayment_processor("CLICKBANK");
				if (isset($post['corderamount']) && ($post['corderamount'] > 0)) 
					$amount=$post['corderamount']/100;
				else 
					$amount=$post['ctransamount']/100;
			}
			else { 
			  $transaction->setPayment_processor($post["payment_processor"]);
			  if (isset($post['corderamount']) && ($post['corderamount'] > 0)) 
				$amount=$post['corderamount'];
			  else 
				$amount=$post['ctransamount'];
				
			  logToFile("recordCBIncoming:  jvzoo processor, amount=".$amount);
			}
				
			$transaction->setPayment_value($amount);
			//TODO: HOW DO WE KNOW THE AMOUNT FROM CLICKBANK ?
			//$transaction->setPayment_value($post['mc_gross']);
			$transaction->setPayer_email($post['ccustemail']);
			if($post['userId'] != "") {
			  $transaction->setUser_id($post['userId']);
			  logToFile("recordCBIncoming:  USERID=" . $post['userId'] . " email = " . $post['ccustemail']);
			}
			else {
			  logToFile("recordCBIncoming  USERID NOT FOUND=" . $post['userId']);	
			}
			
			  
			//$transaction->setProduct_id($post['item']);
			$transaction->setProduct_id($post['item_number']);
			$transaction->setTrans_blob($req);
			$transaction->create();
			logToFile("recordCBIncoming. Insert into transactions Done.."); 
			sendTransactionNotificationEmail($transaction);
			
			return $transaction->getId();
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}

	public static function recordDigiresultsIncoming($post) {
		try {
			if(!isset($post['cproditem']) || 
				!isset($post['ctransreceipt']) 
			) {
				return;
			}
			//$dap_dbh = Dap_Connection::getConnection();
			//loop over incoming post array
			$req = "";
			foreach ($post as $key => $value) {
				$value = urlencode(stripslashes($value));
				$req .= "&$key=$value";
				logToFile("recordDigiresultsIncoming. POST. Key=".$key.",value=".$value); 
			}
			logToFile("Digiresults_INCOMING:".$req, LOG_INFO_DAP);

			$pDesc="";
			if (isset($post['cprodtitle']) && $post['cprodtitle'] != '')
				$pDesc = trim($post['cprodtitle'], " \t\n\r\0\x0B.><*");
				
			$product = Dap_Product::loadProductByName(trim($pDesc));
			//echo sizeof($products);
			//if(sizeof($products) >0) {
			if(isset($product)) {
				logToFile($product->getId(), LOG_INFO_DAP);
				$post['item_number'] = $product->getId();				
			} else {
				$post['item_number'] = 0;
				logToFile("Product Mismatch. Incoming:".$pDesc.":", LOG_FATAL_DAP);
				$product_mismatch = Dap_Config::get("PRODUCT_MISMATCH");
				if(isset($product_mismatch) && $product_mismatch == "Y") {
					sendAdminEmail("DAP Digiresults INS error", "DAP Digiresults INS Error (Accepted): IPN Product Name = " . $pDesc . " does not match any DAP Product Name. But ACCEPTING MISMATCH because of your Config settings (Product Mismatch)");
					$post['item_number'] = 0;
				} else {
					//SEND ADMIN NOTIFICATION THAT WE ARE REJECTING THE IPN DATA.
					$msg = "DAP Digiresults INS Error (Rejected): INS Product Name = ". $pDesc . " does not match any DAP Product Name. But REJECTING MISMATCH it because of your Config settings (Product Mismatch)";
					logToFile($msg);
					sendAdminEmail("DAP Digiresults INS error", $msg);
					return -1;
				}
			}			
			
			//lets do the insert and get the newly created  id out.
			$transaction = new Dap_Transactions();
			$transaction->setTrans_num($post['ctransreceipt']);
			$transaction->setTrans_type("ctransaction");
			$transaction->setPayment_status("Completed");
			$transaction->setPayment_currency("USD");
			
			if (isset($post['corderamount']) && ($post['corderamount'] > 0)) 
				$amount=$post['corderamount']/100;
			else 
				$amount=$post['ctransamount']/100;
				
			$transaction->setPayment_value($amount);
			//TODO: HOW DO WE KNOW THE AMOUNT FROM CLICKBANK ?
			//$transaction->setPayment_value($post['mc_gross']);
			$transaction->setPayer_email($post['ccustemail']);
			$transaction->setPayment_processor("DIGIRESULTS");
			//$transaction->setProduct_id($post['item']);
			$transaction->setProduct_id($post['item_number']);
			$transaction->setTrans_blob($req);
			$transaction->create();
			logToFile("recordDigiresultsIncoming. Insert into transactions Done.."); 
			sendTransactionNotificationEmail($transaction);
			
			return $transaction->getId();
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}

	public static function recordEmailOrder($post) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			//loop over incoming post array
			$req = "";
			foreach ($post as $key => $value) {
				$value = urlencode(stripslashes($value));
				$req .= "&$key=$value";
				logToFile("recordEmailOrder. POST. Key=".$key.",value=".$value); 
			}
			logToFile("EMAIL_ORDER_INCOMING:".$req, LOG_INFO_DAP);
			//lets do the insert and get the newly created  id out.
			$transaction = new Dap_Transactions();
			$transaction->setTrans_num($post['txn_id']);
			$transaction->setTrans_type($post['txn_type']);
			$transaction->setPayment_status($post['payment_status']);
			$transaction->setPayment_currency($post['mc_currency']);
			$transaction->setPayment_value($post['mc_gross']);
			$transaction->setPayer_email($post['payer_email']);
			$transaction->setPayment_processor($post['processor']);
			$transaction->setProduct_id($post['item_number']);
			$transaction->setTrans_blob($req);
			$transaction->create();
			logToFile("recordEmailOrder. Insert into transactions Done.."); 
			sendTransactionNotificationEmail($transaction);
			
			return $transaction->getId();
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}
	//
	public static function getEmailOrderTemplates() {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$select_sql = "select
						id,
						processor,
						blocks,
						template
					from 
						dap_emailorder_templates		
					order by last_update_ts desc
					";
			//execute select
			$select_stmt = $dap_dbh->prepare($select_sql);
			$select_stmt->execute();	
			$results = $select_stmt->fetchAll(PDO::FETCH_ASSOC);	
			return $results;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
		}
		return;
	}
	
	public static function recordGenericIncoming($post, $processorName) {
		/*
			Required fields:
			item_name (product name - must be same as product name in DAP)
			payer_email
			trans_num (unique)
			payment_status ("Pending"/"Completed")
			public_key (will be matched against code on the server side)
		   
			Optional fields:
			payment_value (OPTIONAL)
			sub_trans_num (OPTIONAL)
			payment_currency (Default: USD)  
			trans_type (Default: "subscr_payment")
			payment_processor (Default: "Lifetime")
		*/

		try {
			$pDesc = trim($post['item_name'], " \t\n\r\0\x0B.><*");
			$product = Dap_Product::loadProductByName(trim($pDesc));
			
			//echo sizeof($products);
			//if(sizeof($products) >0) {
			if(isset($product)) {
				logToFile($product->getId(), LOG_INFO_DAP);
				$post['item_number'] = $product->getId();				
			} else  {
				$productMismatch = Dap_Config::get("PRODUCT_MISMATCH");
				if(strtolower($productMismatch) == "y") {
					$post['item_number'] = 0;
					logToFile("Product Mismatch. Incoming:".$pDesc.":", LOG_FATAL_DAP);
				} else if(strtolower($productMismatch) == "n") {
					return 0;
				}
			}					
			//$dap_dbh = Dap_Connection::getConnection();
			//loop over incoming post array
			$req = "";
			foreach ($post as $key => $value) {
				$value = urlencode(stripslashes($value));
				$req .= "&$key=$value";
				logToFile("record".$processorName."Incoming. POST. Key=".$key.",value=".$value); 
			}
			//TODO: Dap_Transactions processTransaction looks for following first, last name keys, 
			//  transpose if processor sends them with differnt names
			//				$user->setFirst_name( $list["first_name"] );
			//	$user->setLast_name( $list["last_name"] );
			//logToFile("INFUSION_INCOMING:".$req, LOG_INFO_DAP);
			//lets do the insert and get the newly created  id out.
			$transaction = new Dap_Transactions();
			$transaction->setTrans_num($post['trans_num']);
			$transaction->setTrans_type($post['trans_type']);
			$transaction->setPayment_status($post['payment_status']);
			$transaction->setPayment_currency($post['payment_currency']);
			$transaction->setPayment_value($post['payment_value']);
			$transaction->setPayer_email($post['payer_email']);
			$transaction->setPayment_processor($post['payment_processor']);
			$transaction->setProduct_id($post['item_number']);
			$transaction->setTrans_blob($req);
			$transaction->create();
			logToFile("recordInfusionIncoming. Insert into transactions Done.."); 
			sendTransactionNotificationEmail($transaction);
			
			return $transaction->getId();
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}	

}
?>
