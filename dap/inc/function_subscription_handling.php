<?php

$lldocroot = defined('SITEROOT') ? SITEROOT : $_SERVER['DOCUMENT_ROOT'];
if(file_exists($lldocroot . "/dap/dap-config.php")) include_once ($lldocroot . "/dap/dap-config.php");

if(file_exists($lldocroot . "/dap/authnet-function.php")) include_once ($lldocroot . "/dap/authnet-function.php");
if(file_exists($lldocroot . "/dap/paypal-function.php")) include_once ($lldocroot . "/dap/paypal-function.php");
 if(file_exists($lldocroot . "/dap/inc/tp/stripe/lib/Stripe.php")) include_once ($lldocroot . "/dap/inc/tp/stripe/lib/Stripe.php");
 
function cancelAuthnetSubscriptionUpgrade($upg_from,$post) {
  logToFile("function_subscription_handling: cancelPaypalSubscriptionUpgrade(): Call authnet to cancel (Upgrade FLOW) for prod=".$upg_from.", and for the user=".$post["x_email"]);
  
  $email = $post["x_email"];
  $productId=$upg_from;
  $action="Cancel";
  if(($email != "") && ($productId != ""))
	  findAndProcessTransaction($email, $productId, $action);
  
}


function cancelPaypalSubscriptionUpgrade($upg_from,$post) {
  logToFile("function_subscription_handling: cancelPaypalSubscriptionUpgrade(): Call Paypal to cancel (Upgrade FLOW)");
  
  $email = $post["payer_email"];
  $productId=$upg_from;
  $action="Cancel";
  if(($email != "") && ($productId != ""))
	  findAndProcessTransaction($email, $productId, $action);
  
}

function findAndProcessTransaction($email,$productId,$action="Cancel") {
  $emailFilter = $email;
  $productIdFilter = $productId;
  $statusFilter = "";
  
  logToFile("emailFilter=".$emailFilter,LOG_INFO_DAP);
  logToFile("productIdFilter=".$productIdFilter,LOG_INFO_DAP);

  $TransactionsList = Dap_Transactions::loadTransactions($transNumFilter, $emailFilter, $productIdFilter, $statusFilter);
  $foundTransaction=false;
  $authnet=false;
  $paypal=false;
  $recurring_id ="";
  $cancelled_subscription="";
  foreach ($TransactionsList as $transaction) {
	  parse_str($transaction->getTrans_blob(), $list);
	  logToFile("function_subscription_handling:  findAndProcessTransaction(): Payment processor is paypal, setting address details before list",LOG_INFO_DAP); 
	  
	  if (($list == NULL) || !isset($list))
		  return;
		  
	  logToFile("function_subscription_handling:  findAndProcessTransaction(): LIST EMPTY"); 
		
	  foreach ($list as $key => $value) {
	   logToFile("function_subscription_handling:  findAndProcessTransaction(): Key=".$key.", Value=".$value); 
	  }
	  
	  if(array_key_exists('recurring_payment_id',$list)) {
		$recurring_id = $list["recurring_payment_id"];
	  }
	  else if(array_key_exists('subscr_id',$list)) {
		$recurring_id = $list["subscr_id"];
	  }
	  else if(array_key_exists('sub_id',$list)) {
		$recurring_id = $list["sub_id"];
	  }
	  
	  if(array_key_exists('cancel',$list)) {
		$cancelled_subscription = $list["cancel"];
		logToFile("function_subscription_handling::cancelled_subscription".$cancelled_subscription); 
	  }
	  
	  logToFile("function_subscription_handling.php::recurring_id".$recurring_id); 
	  
	  $payment_processor = $transaction->getPayment_processor();
	  if ($payment_processor == "AUTHNET") {
		$authnet=true;
	  }
	  if (strstr($payment_processor,"PAYPAL")) {
		$paypal=true;
	  }
	  
	  $transaction_id=$transaction->getTrans_num();
	  
	  $foundTransaction=true;
	  break; 
  }
  
  $upgproduct  = Dap_Product::loadProduct($productId);
  if($upgproduct) {
	$item_name=$upgproduct->getName();
	logToFile("ENTER dap-changeSubscriptionStatus() item_name=".$item_name);
  }
  
  $aresult=array();
  $aresult["item_name"]=$item_name;
  $aresult["email"]=$email;
  
  if(!isset($user)) {
	 $user = Dap_User::loadUserByEmail($email);
	 logToFile("function_subscription_handling: user found");	   
  }
  
  logToFile("function_subscription_handling: authnet=$authnet, paypal=$paypal, recurring_id=$recurring_id");
  if(($authnet || $paypal) && ($recurring_id != "")) {
	logToFile("function_subscription_handling: If authnet=$authnet, paypal=$paypal, recurring_id=$recurring_id");
    $ret=false;
	if($paypal) {
	  //logToFile("function_subscription_handling: cancelPaypalSubscription(): $recurring_id $action,$user,$recurring_id");
	  $ret = cancelPaypalSubscription($recurring_id,$action,$aresult,$user,$recurring_id);
	  logToFile("function_subscription_handling: cancelPaypalSubscription(): RETRUNR=$ret");
	  
	}
	else {
	  //logToFile("function_subscription_handling: cancelPaypalSubscription(): $recurring_id $action,$user,$recurring_id");
	  $ret = cancelAuthnetSubscription($recurring_id,$action,$aresult,$user,$recurring_id);
	  logToFile("function_subscription_handling: cancelPaypalSubscription(): RETRUNR=$ret");
	  
	}
	
   if($transaction) {
	  $blob=$transaction->getTrans_blob();
	  if($source!="Admin") {
		$blob.="&cancel=USERCANCELLED";
	  }
	  else {
		$blob.="&cancel=ADMINCANCELLED";
	  }
	  logToFile("dap-changeSubscriptionStatus:. update blob with cancel status=".$blob, LOG_DEBUG_DAP);
	  
	  $transaction->setTrans_blob($blob);
	  $transaction->updateBlob();  
	}
	
	return $ret;
	
  }	
}

function cancelAuthnetSubscription($profile_id,$action,$aresult,$user,$subscriptionId) {
  logToFile("function_subscription_handling: cancelAuthnetSubscription(): Call Authnet to cancel");
  //$subscription_id=
  $ret = update_authnet_recurring_subscription($action,$aresult,$subscriptionId,$user);
  
  return $ret;
   
}

function cancelStripeSubscription($profile_id,$action,$aresult,$user,$customerId,$item_name) {
  logToFile("function_subscription_handling: cancelStripeSubscription(): Call Stripe to cancel item=$item_name, customerId=".$customerId);
  //$subscription_id=
  
  try {
	  $stripePublishableKey=Dap_Config::get('STRIPE_SECRET_KEY');
	  
	  logToFile("function_subscription_handling: cancelStripeSubscription(): set stripe api key");
	  Stripe::setApiKey($stripePublishableKey);
	  
	  logToFile("function_subscription_handling: cancelStripeSubscription(): get stripe customer for id=".$customerId); 
	  $customer = Stripe_Customer::retrieve($customerId);	
	  
//	  $subscriptions = Stripe_Customer::retrieve($customerId)->subscriptions->all();
	  //$subscriptions = Stripe_Customer::retrieve($customerId)->subscriptions->all();
	  
		  
	/* $subscription = $customer->subscriptions->retrieve({SUBSCRIPTION_ID}));
	  if (!empty($subscriptions)) {
	     logToFile("function_subscription_handling: cancelStripeSubscription(): got subscriptions from stripe".$planname);  
	  }
	  else {
		 logToFile("function_subscription_handling: cancelStripeSubscription(): no subscriptions in stripe".$planname);   
	  }
	  */
	  $foundplan=false;
	  
	  foreach ($customer['subscriptions']['data'] as $subscription) {
		logToFile("function_subscription_handling: cancelStripeSubscription():iterating to find the subscription to cancel"); 
		 
		$id=$subscription['id'];
		logToFile("function_subscription_handling: cancelStripeSubscription():subid=".$id); 
	
		$planname=$subscription['plan']['name'];
		logToFile("function_subscription_handling: cancelStripeSubscription():planname=".$planname); 
		if (strcasecmp($planname, $item_name) == 0) {
	 		logToFile("function_subscription_handling: cancelStripeSubscription(): plan to be cancelled found, subscriptionId=$id, planName=".$planname); 
			$foundplan=true;
			break;
		}
		else {
			logToFile("function_subscription_handling: cancelStripeSubscription(): plan didnot match=".$planname); 
			
		}
	  }
	  
	  if($foundplan==false) {
		logToFile("function_subscription_handling: cancelStripeSubscription(): no active subscription found in stripe=".$planname);  
	  	return false;
      }
	  /*
	  if (!empty($customer->subscription)) {
	  	if (!empty($customer->subscription->data[0])) {
			if (!empty($customer->subscription->data[0][id]))
				$subscriptionId = $customer->subscriptions->data[0][id];  // $customer['subscriptions']['data']['id'];
			else {
				logToFile("function_subscription_handling: cancelStripeSubscription(): subscriptionId is empty"); 
				return false;
			}
		}
		else {
			logToFile("function_subscription_handling: cancelStripeSubscription(): data object is empty"); 
			return false;
		}
	  }
	  else {
		 logToFile("function_subscription_handling: cancelStripeSubscription(): customer  object is empty");   
		 return false;
	  }*/
	  
	//  $subscriptionId =  $customer['subscriptions']['data']['id'];
	
	  logToFile("function_subscription_handling: cancelStripeSubscription(): now delete/cancel subscription for subcriptionId=$id, stripe customerId=".$customerId); 
	//  $customer->subscriptions->retrieve($id)->cancel();
	   
//	  $subscription = Stripe::subscriptions()->cancel('customer' => $customerId,'id'=>$id]);

	  //$customer['subscriptions']->retrieve($id)->cancel();	  
	 // $customer.subscriptions.retrieve("subscriptionId").delete();
	  $subscription = $customer->subscriptions->retrieve($id);
	  logToFile("function_subscription_handling: cancelStripeSubscription(): got subscription"); 
	  
	  if (!empty($subscription)) {
		 logToFile("function_subscription_handling: cancelStripeSubscription(): cancel subscription for customer id=".$customerId); 
		 try {
	 		 $result=$subscription->cancel();
		 } catch (Stripe_InvalidRequestError $e) { 
		 	logToFile("function_subscription_handling: cancelStripeSubscription(): Stripe_InvalidRequestError"); 
		 // Invalid parameters were supplied to Stripe's API
		 } catch (Stripe_AuthenticationError $e) { // Authentication with Stripe's API failed // (maybe you changed API keys recently)
			logToFile("function_subscription_handling: cancelStripeSubscription(): Stripe_AuthenticationError");
		 } catch (Stripe_ApiConnectionError $e) { // Network communication with Stripe failed 
		   logToFile("function_subscription_handling: cancelStripeSubscription(): Stripe_ApiConnectionError");
		 } catch (Stripe_Error $e) { // Display a very generic error to the user, and maybe send // yourself an email
		   logToFile("function_subscription_handling: cancelStripeSubscription(): Stripe_Error");
		 } catch (Exception $e) { // Something else happened, completely unrelated to Stripe 
		   logToFile("function_subscription_handling: cancelStripeSubscription(): Exception");
		 }
		 
		 if (!empty($result)) {
			logToFile("function_subscription_handling: cancelStripeSubscription(): successfully cancelled stripe subscription for customer id=".$customerId); 	 		 }
		 else {
			logToFile("function_subscription_handling: cancelStripeSubscription(): cancellation failed"); 
			return false;
		 }
	  }
	  else {
		   logToFile("function_subscription_handling: cancelStripeSubscription(): empty subscription"); 
		   return false;
	  }

  //$ret = update_stripe_recurring_subscription($action,$aresult,$customerId,$subscriptionId,$user);
  }
  catch (Exception $e) {
	  $errmsg=$e->getMessage();
	  logToFile("function_subscription_handling: cancelStripeSubscription(): failed ($errmsg) to cancel stripe subscription for customer id=".$customerId); 
	  return false;
  }
  
  return true;
   
}

function cancelPaypalSubscription($profile_id,$action,$aresult,$user,$subscriptionId) {
  logToFile("function_subscription_handling: cancelPaypalSubscription(): Call paypal to cancel");
  
  $api_username = trim(Dap_Config::get('PAYPAL_API_LOGIN'));
  $api_password = trim(Dap_Config::get('PAYPAL_API_PASSWORD'));;
  $api_signature = trim(Dap_Config::get('PAYPAL_API_SIGNATURE'));
  $paypal_api_endpoint = trim(Dap_Config::get('PAYPAL_API_ENDPOINT'));

  $paypal_sandbox = trim(Dap_Config::get('PAYPAL_SANDBOX'));
  
  if ($paypal_sandbox == "Y") {
	  define('PAYPAL_SERVER_DAP', "https://api-3t.sandbox.paypal.com/nvp");
	  $api_url = "https://api-3t.sandbox.paypal.com/nvp";
  }
  else {
	  define('PAYPAL_SERVER_DAP', "https://api-3t.paypal.com/nvp");
	  $api_url = "https://api-3t.paypal.com/nvp";
  }
  
  //$api_url = "https://api-3t.sandbox.paypal.com/nvp";
  
  
  //https://api-3t.sandbox.paypal.com/nvp
  
  logToFile("function_subscription_handling: cancelPaypalSubscription(): api_username=".$api_username);
  logToFile("function_subscription_handling: cancelPaypalSubscription(): PWD=".$api_password);
  logToFile("function_subscription_handling: cancelPaypalSubscription(): SIGNATURE=".$api_signature);
  logToFile("function_subscription_handling: cancelPaypalSubscription(): api_url=".$api_url	);
  logToFile("function_subscription_handling: cancelPaypalSubscription(): profile_id=".$profile_id);
  logToFile("function_subscription_handling: cancelPaypalSubscription(): action=".$action);

  
  /*$api_request = 'USER=' . urlencode( $api_username )
			  .  '&PWD=' . urlencode( $api_password )
			  .  '&SIGNATURE=' . urlencode( $api_signature )
			  .  '&VERSION=76.0'
			  .  '&METHOD=ManageRecurringPaymentsProfileStatus'
			  .  '&PROFILEID=' . urlencode( $profile_id )
			  .  '&ACTION=' . urlencode( $action )
			  .  '&NOTE=' . urlencode( 'Recurring Profile cancelled in Paypal' );
*/

  $api_request = 'USER=' . $api_username
			  .  '&PWD=' . $api_password
			  .  '&SIGNATURE=' . $api_signature
			  .  '&VERSION=76.0'
			  .  '&METHOD=ManageRecurringPaymentsProfileStatus'
			  .  '&PROFILEID=' . $profile_id
			  .  '&ACTION=' . $action
			  .  '&NOTE=' . 'Recurring Profile cancelled in Paypal';
			  
  $ch = curl_init();
  curl_setopt( $ch, CURLOPT_URL, $api_url); // For live transactions, change to 'https://api-3t.paypal.com/nvp'
  curl_setopt( $ch, CURLOPT_VERBOSE, 1 );

  // Uncomment these to turn off server and peer verification
  // curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
  // curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );
  curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
  curl_setopt( $ch, CURLOPT_POST, 1 );

  // Set the API parameters for this transaction
  curl_setopt( $ch, CURLOPT_POSTFIELDS, $api_request );
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close', 'User-Agent: ' . SITE_URL_DAP));
// Request response from PayPal
  $response = curl_exec( $ch );

  if( curl_errno( $ch ) ) {
	  curl_close( $ch );
	  //logToFile("function_subscription_handling: Calling PayPal to change_subscription_status failed: " . curl_error( $ch ) . "," . curl_errno( $ch ));
	  return FALSE;
  }
  else {
	curl_close( $ch );
	//logToFile("function_subscription_handling: Calling PayPal to change_subscription_status success: " . curl_error( $ch ) . '(' . curl_errno( $ch ) . ')' );
	return TRUE;
  }
  curl_close( $ch );

	
	logToFile("function_subscription_handling: cancelPaypalSubscription(): all done");
	
}


function refundPaypalSubscription($trans_id,$action,$aresult,$user,$subscriptionId) {
  logToFile("function_subscription_handling: cancelPaypalSubscription(): Call paypal to cancel");
  
  $api_username = trim(Dap_Config::get('PAYPAL_API_LOGIN'));
  $api_password = trim(Dap_Config::get('PAYPAL_API_PASSWORD'));;
  $api_signature = trim(Dap_Config::get('PAYPAL_API_SIGNATURE'));
  $paypal_api_endpoint = trim(Dap_Config::get('PAYPAL_API_ENDPOINT'));

  $paypal_sandbox = trim(Dap_Config::get('PAYPAL_SANDBOX'));
  
  if ($paypal_sandbox == "Y") {
	  define('PAYPAL_SERVER_DAP', "https://api-3t.sandbox.paypal.com/nvp");
	  $api_url = "https://api-3t.sandbox.paypal.com/nvp";
  }
  else {
	  define('PAYPAL_SERVER_DAP', "https://api-3t.paypal.com/nvp");
	  $api_url = "https://api-3t.paypal.com/nvp";
  }
  
  //$api_url = "https://api-3t.sandbox.paypal.com/nvp";
  
  
  //https://api-3t.sandbox.paypal.com/nvp
  
  logToFile("function_subscription_handling: refundPaypalSubscription(): api_username=".$api_username);
  logToFile("function_subscription_handling: refundPaypalSubscription(): PWD=".$api_password);
  logToFile("function_subscription_handling: refundPaypalSubscription(): SIGNATURE=".$api_signature);
  logToFile("function_subscription_handling: refundPaypalSubscription(): api_url=".$api_url	);
  logToFile("function_subscription_handling: refundPaypalSubscription(): trans_id=".$trans_id);

   
  if(strstr($trans_id,":") != FALSE) {
  	$transarr=explode(":",$trans_id);
    if(isset($transarr[0])) {
		$trans_id=$transarr[0];	
		 logToFile("function_subscription_handling: refundPaypalSubscription(): NEW trans_id=".$trans_id);
	}
  }
  
  $api_request = 'USER=' . $api_username
			  .  '&PWD=' . $api_password
			  .  '&SIGNATURE=' . $api_signature
			  .  '&VERSION=76.0'
			  .  '&METHOD=RefundTransaction'
			  .  '&TRANSACTIONID=' . $trans_id
			  .  '&REFUNDTYPE=Full' 
			  .  '&NOTE=' . 'Payment refunded in Paypal';
			  
  $ch = curl_init();
  curl_setopt( $ch, CURLOPT_URL, $api_url); // For live transactions, change to 'https://api-3t.paypal.com/nvp'
  curl_setopt( $ch, CURLOPT_VERBOSE, 1 );

  // Uncomment these to turn off server and peer verification
  // curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, FALSE );
  // curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, FALSE );
  curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
  curl_setopt( $ch, CURLOPT_POST, 1 );
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Connection: Close', 'User-Agent: ' . SITE_URL_DAP));
  // Set the API parameters for this transaction
  curl_setopt( $ch, CURLOPT_POSTFIELDS, $api_request );

// Request response from PayPal
  $response = curl_exec( $ch );

  if( curl_errno( $ch ) ) {
	  logToFile("function_subscription_handling: Calling PayPal to change_subscription_status failed: " . curl_error( $ch ) . "," . curl_errno( $ch ));
	  curl_close( $ch );
	  
	  return FALSE;
  }
  else {
	logToFile("function_subscription_handling: Calling PayPal to change_subscription_status success: " . curl_error( $ch ) . '(' . curl_errno( $ch ) . ')' );
	curl_close( $ch );
	logToFile("function_subscription_handling: refundPaypalSubscription(): all done");
	return TRUE;
  }
  curl_close( $ch );

}


// ===================
function update_authnet_recurring_subscription($action,$aresult,$subscriptionId,$user) {
  $gateway_recur_url = trim(Dap_Config::get('GATEWAY_RECUR_URL'));
  logToFile("function_subscription_handling: update_authnet_recurring_subscription(): " . $gateway_recur_url, LOG_DEBUG_DAP);

  if(!($xmlpos = strpos ($gateway_recur_url, "xml"))) {
	  logToFile("function_subscription_handling: update_authnet_recurring_subscription(): Incorrect merchant url", LOG_DEBUG_DAP);
	  return FALSE;
  }

  $path = substr($gateway_recur_url, $xmlpos - 1); 
  $host = substr($gateway_recur_url, 8, $xmlpos - 8 - 1 ); // skip http:// (7 char) and "/" before xml (1 char)
  logToFile("function_subscription_handling: update_authnet_recurring_subscription() - path=" . $path . "host=" . $host ,LOG_DEBUG_DAP);

  //sequence number is randomly generated
  $sequence	= rand(1, 1000);
  //timestamp is generated
  $timestamp = time ();
  
  $login_name	= trim(Dap_Config::get('GATEWAY_API_LOGIN'));
  $transaction_key = trim(Dap_Config::get('GATEWAY_TRANS_KEY'));
  //$req['gateway_url'] = trim(Dap_Config::get('GATEWAY_URL'));
  
  $refId = $req['refId'];

  if(strstr($subscriptionId,":") != FALSE) {
  	$subscriptionidarr=explode(":",$subscriptionId);
    if(isset($subscriptionidarr[0])) {
		 $subscriptionId=$subscriptionidarr[0];	
		 logToFile("function_subscription_handling: update_authnet_recurring_subscription(): NEW subscriptionId=".$subscriptionId);
	}
  }
  
  //subscription name... same as product name
  $item_name = $aresult['item_name'];
  $email =  $aresult['email'];
  logToFile("function_subscription_handling: update_authnet_recurring_subscription() - item_name=" . $item_name . "transaction_key=" . $transaction_key,LOG_DEBUG_DAP);
  logToFile("function_subscription_handling: update_authnet_recurring_subscription() - subscriptionId=" . $subscriptionId,LOG_DEBUG_DAP);

  //build xml to post
  $content =
  "<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
  "<ARBCancelSubscriptionRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
  "<merchantAuthentication>".
	  "<name>" . $login_name . "</name>".
	  "<transactionKey>" . $transaction_key . "</transactionKey>".
  "</merchantAuthentication>".
  "<refId>" . $refId . "</refId>".
  "<subscriptionId>" . $subscriptionId . "</subscriptionId>".
  "</ARBCancelSubscriptionRequest>";

  //logToFile("Dap_Payment:XML content: " . $content, LOG_DEBUG_DAP);
  
  //send the xml via curl
  $response = send_request_via_curl($host,$path,$content);

//if the connection and send worked $response holds the return from Authorize.net
  if ($response)
  {
	  list ($ref_id, $result_code, $code, $text, $subscription_id) =parse_return($response);
	  if (!strcasecmp ($result_code,"Ok")) { //SUCCESS
		  logToFile("function_subscription_handling: update_authnet_recurring_subscription(). Dap_Payment successfully processed by authorize.net,  Subs Id: " . $subscriptionId , LOG_DEBUG_DAP);
		  
		  return TRUE;
	  }
	  else {
		  logToFile("function_subscription_handling: update_authnet_recurring_subscription(). Error Code: " . $result_code . " Reason Code: " . $code . " text: " . $text . " Subs Id: " . $subscriptionId , LOG_DEBUG_DAP);
		  
		  sendAdminEmail ("function_subscription_handling: update_authnet_recurring_subscription", "Authnet recurring subscription cancellation failed for " . $aresult['email'] . " with Error Code: " . $result_code . " Reason Code: " . $code . " text: " . $text);
		  
		  return FALSE;
	  }
  }	
  else
  {
	  logToFile("function_subscription_handling: update_authnet_recurring_subscription(). Failed to connect to authnet", LOG_DEBUG_DAP);
	  
	  sendAdminEmail("update_authnet_recurring_subscription(): Authnet connection could not be established for " . $aresult['email'], "Dap_Payment:update_authnet_recurring_subscription(). authnet connection for recurring payment could not be established for: " . $aresult['email'] . " for product = " . $aresult['item_name'] . " to " . $action . " subscription");
	  
	  return FALSE;
  }

  return TRUE;
}  // end function


// ===================
function getUpgradeFrom($custom, $source) {
  $upgfemail = strstr($custom,"UPGF");
  $upgradeFrom="";
  logToFile("$source : check if custom field contains UPGRADE FROM =" . $upgfemail, LOG_DEBUG_DAP);
  if(isset($upgfemail) && ($upgfemail)) {
	logToFile("$source: upgfemail is NOT EMPTY" . $upgfemail, LOG_DEBUG_DAP);
	$upgemailparam=explode("|",$upgfemail);
	logToFile("$source: upgfemail after EXPLODE NOT EMPTY" . $upgemailparam[0], LOG_DEBUG_DAP);
	logToFile("$source: yes, custom field contains UPGRADE FROM =" . $upgemailparam[0], LOG_DEBUG_DAP);
	if( isset($upgemailparam[0]) && ($upgemailparam[0] != "")) {
	  $upgemparamvalue=explode(":",$upgemailparam[0]);	
	  logToFile("$source: yes, custom field contains UPGRADE FROM AND EMAIL=" . $upgemparamvalue[1], LOG_DEBUG_DAP);
	}
	else {
	  $upgemparamvalue=explode(":",$upgfemail);		
	}
	
	if( isset($upgemparamvalue[1]) && ($upgemparamvalue[1] != "")) {
		$upgradeFrom=$upgemparamvalue[1];
		logToFile("$source: yes, custom field contains UPGRADE FROM ,upgradeFrom =" . $upgradeFrom, LOG_DEBUG_DAP);
	}
	
  }
  return $upgradeFrom;
}
	
	// ===================
function getLoggedInEmail($custom, $source) {
	$upgfemail = strstr($custom,"EM:");
	logToFile("$source: check if custom field contains Email =" . $upgfemail, LOG_DEBUG_DAP);
	$email = "";
	if( isset($upgfemail) && ($upgfemail!="") ) {
	  $pemail=explode(":",$upgfemail);	  
	  if( isset($pemail[1]) && ($pemail[1] != "") ) {
		  $email = $pemail[1];
	  }
	}
	return $email;
}

?>