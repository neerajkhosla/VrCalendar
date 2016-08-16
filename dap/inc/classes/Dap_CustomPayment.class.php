<?php

include_once "custom_messages.php";
include_once "authnet-function.php";

class Dap_CustomPayment {
	var $card_type;
	var $card_num;
	var $exp_date;
	var $card_code;
	var $first_name;
	var $last_name;
	var $company;
	var $address1;
	var $address2;
	var $city;
	var $state;
	var $statecode;
	var $zip;
	var $country;
	var $countrycode;
	var $email;
	var $payerId;
	var $phone;
	var $fax;
	var $ship_to_first_name;
	var $ship_to_last_name;
	var $ship_to_address1;
	var $ship_to_address2;
	var $ship_to_company;
	var $ship_to_city;
	var $ship_to_state;
	var $ship_to_statecode;
	var $ship_to_zip;
	var $ship_to_country;
	var $ship_to_countrycode;

	function getCard_type() {
		return $this->card_type;
	}
	function setCard_type($o) {
		$this->card_type = $o;
	}
	
	function getCard_num() {
		return $this->card_num;
	}
	function setCard_num($o) {
		$this->card_num = $o;
	}

	function getExp_date() {
		return $this->exp_date;
	}
	function setExp_date($o) {
		$this->exp_date = $o;
	}

	function getCard_code() {
		return $this->card_code;
	}	
	function setCard_code($o) {
		$this->card_code = $o;
	}

	function getFirst_name() {
		return $this->first_name;
	}
	function setFirst_name($o) {
		$this->first_name = $o;
	}

	function getLast_name() {
		return $this->last_name;
	}
	function setLast_name($o) {
		$this->last_name = $o;
	}

	function getCompany() {
		return $this->company;
	}
	function setCompany($o) {
		$this->company = $o;
	}

	function getAddress1() {
		return $this->address1;
	}
	function setAddress1($o) {
		$this->address1 = $o;
	}

	function getAddress2() {
		return $this->address2;
	}
	function setAddress2($o) {
		$this->address2 = $o;
	}

	function getCity() {
		return $this->city;
	}
	function setCity($o) {
		$this->city = $o;
	}

	function getState() {
		return $this->state;
	}
	function setState($o) {
		$this->state = $o;
	}
	
	function getStateCode() {
		return $this->statecode;
	}
	function setStateCode($o) {
		$this->statecode = $o;
	}

	function getZip() {
		return $this->zip;
	}
	function setZip($o) {
		$this->zip = $o;
	}

	function getCountry() {
		return $this->country;
	}
	function setCountry($o) {
		$this->country = $o;
	}

	function getCountryCode() {
		return $this->countrycode;
	}
	function setCountryCode($o) {
		$this->countrycode = $o;
	}
	
	function getEmail() {
		return $this->email;
	}
	function setEmail($o) {
		$this->email = $o;
	}

	function getPayerId() {
		return $this->PayerId;
	}
	function setPayerId($o) {
		$this->PayerId = $o;
	}
	
	function getPhone() {
		return $this->phone;
	}
	function setPhone($o) {
		$this->phone = $o;
	}

	function getFax() {
		return $this->fax;
	}
	function setFax($o) {
		$this->fax = $o;
	}

	function getShip_to_first_name() {
		return $this->ship_to_first_name;
	}
	function setShip_to_first_name($o) {
		$this->ship_to_first_name = $o;
	}

	function getShip_to_last_name() {
		return $this->ship_to_last_name;
	}
	function setShip_to_last_name($o) {
		$this->ship_to_last_name = $o;
	}

	function getShip_to_company() {
		return $this->ship_to_company;
	}
	function setShip_to_company($o) {
		$this->ship_to_company = $o;
	}

	function getShip_to_address1() {
		return $this->ship_to_address1;
	}
	function setShip_to_address1($o) {
		$this->ship_to_address1 = $o;
	}

	function getShip_to_address2() {
		return $this->ship_to_address2;
	}
	function setShip_to_address2($o) {
		$this->ship_to_address2 = $o;
	}

	function getShip_to_city() {
		return $this->ship_to_city;
	}
	function setShip_to_city($o) {
		$this->ship_to_city = $o;
	}

	function getShip_to_state() {
		return $this->ship_to_state;
	}
	function setShip_to_state($o) {
		$this->ship_to_state = $o;
	}

	function getShip_to_statecode() {
		return $this->ship_to_statecode;
	}
	function setShip_to_statecode($o) {
		$this->ship_to_statecode = $o;
	}

	function getShip_to_zip() {
		return $this->ship_to_zip;
	}
	function setShip_to_zip($o) {
		$this->ship_to_zip = $o;
	}

	function getShip_to_country() {
		return $this->ship_to_country;
	}
	function setShip_to_country($o) {
		$this->ship_to_country = $o;
	}

	function getShip_to_countrycode() {
		return $this->ship_to_countrycode;
	}
	function setShip_to_countrycode($o) {
		$this->ship_to_countrycode = $o;
	}
	
	public function validateInput($req) {
		if (!(isset ($req['login_name'], $req['transaction_key'], $req['gateway_url'])))
		{
			logToFile("Dap_CustomPayment:validateInput() - missing merchant's gateway login id or trans key or gateway url", LOG_DEBUG_DAP);
			return FALSE;
		}

		return TRUE;
	}
	
	public function validateProduct($req) {

		$pDesc = addslashes($req['item_name']);
		$product = Dap_Product::loadProductByName(trim($pDesc));

		$responseData = array();
		if(!isset($product)) {
			$response_text = "no product with this name = " . $req['item_name'];
			logToFile("Dap_CustomPayment: " . $response_text, LOG_DEBUG_DAP);
			echo "Response Code: " . MISSING_PRODUCT_CODE . "\n";
			echo "Response Msg: " . MISSING_PRODUCT_PARAMS_MSG . "\n";
			echo $response_text;
			
			/*
			$responseData['response_code'] = MISSING_PRODUCT_CODE;
			$responseData['response_msg'] = MISSING_PRODUCT_PARAMS_MSG;
			$responseData['response_text'] = "No product with this name = " . $req['Responseproduct'];
			sendResponseViaCurl ($post, $responseData);*/
			return -1;
		}
		
		$req['item_number'] = $product->getId();		
		logToFile("Dap_CustomPayment: productID=" . $req['item_number'], LOG_DEBUG_DAP);
		
		$req['item_name'] = $product->getName();
		$req['description'] = $product->getDescription();
		
		$req['is_recurring'] = $product->getIs_recurring();
		$req['total_occurrences'] = $product->getTotal_occur();
		$req['recurring_cycle_1'] = $product->getRecurring_cycle_1();
		$req['recurring_cycle_2'] = $product->getRecurring_cycle_2();
		$req['recurring_cycle_3'] = $product->getRecurring_cycle_3();
		
		$amount = $product->getPrice();

		if (isset($amount) && $amount != '' && $amount != "0" && $amount != "0.0" && $amount != "0.00") {
			$req['amount'] = $amount;
		}
		else {
			$response_text = "Response Text: amount not defined for the product = " . $req['item_name'];
			logToFile("Dap_CustomPayment: " . $response_text, LOG_DEBUG_DAP);
			echo "Response Code: " . MISSING_AMOUNT_CODE . "\n";
			echo "Response Msg: " . MISSING_AMOUNT_MSG . "\n";
			echo  $response_text;
			
			return -1;
		}
		
		$trial_amount = $product->getTrial_price();
		if (isset($trial_amount) && $trial_amount != '') {
			$req['trial_amount'] = $product->getTrial_price();
		}
		else {
			$req['trial_amount'] = "0.00";
		}
		
		if ($req['is_recurring'] == "Y" && ($req['recurring_cycle_1'] == '' || $req['recurring_cycle_3'] == '')) {
			$response_text = "Response Text: missing recurring_cycle_1 and/or recurring_cycle_3 settings for product = " . $req['item_name'];
			logToFile("Dap_CustomPayment: " . $response_text, LOG_DEBUG_DAP);
			echo "Response Code: " . MISSING_RECURRING_CYCLE_CODE . "\n";
			echo "Response Msg: " . MISSING_RECURRING_CYCLE_MSG . "\n";
			echo  $response_text;
	
			return -1;
		}
		
		if (isset($req['is_recurring']) && $req['is_recurring'] == "Y" && ($req['total_occurrences'] == '' || $req['total_occurrences'] == '0')) {
			$response_text = "Sorry, missing total_occurrences info for product = " . $req['item_name'];
			logToFile("Dap_CustomPayment: " . $response_text, LOG_DEBUG_DAP);
			echo "Response Code: " . MISSING_TOTAL_OCCURRENCES_CODE . "\n";
			echo "Response Msg: " . MISSING_TOTAL_OCCURRENCES_MSG . "\n";
			echo $response_text;
			
			return -1;
		}
		
		logToFile("Dap_CustomPayment: Validation Completed Successfully", LOG_DEBUG_DAP);
		
		//Validation Success
		return 0;
	}
		
			
	/*
	
	$responseData = array();
	
	if (!$this->validateInput($post)) {
		$responseData['response_code'] = MISSING_REQUEST_PARAMS_CODE;
		$responseData['response_msg'] = MISSING_REQUEST_PARAMS_MSG;
		sendResponseViaCurl ($post, $responseData);
		return;
	}
	*/
	
	function create_custom_authnet_subscription($post, $req)  // AIM
	{
		$req['gateway_url'] = trim(Dap_Config::get('GATEWAY_URL'));
		$req['login_name']	= trim(Dap_Config::get('GATEWAY_API_LOGIN'));
		$req['transaction_key'] = trim(Dap_Config::get('GATEWAY_TRANS_KEY'));
			
		$post_values = array();
		
		foreach( $req as $key => $value ) {
			logToFile("Dap_CustomPayment:create_custom_authnet_subscription(). REQ:  " . $key . "=" . $value, LOG_DEBUG_DAP);
		}
		
		$post_values['x_login']	= trim(Dap_Config::get('GATEWAY_API_LOGIN'));
		$post_values['x_tran_key'] = trim(Dap_Config::get('GATEWAY_TRANS_KEY'));
		$post_values['x_method'] = "CC";

		//Transaction Information
		$post_values['x_version'] ="3.1";
		$post_values['x_type'] = "AUTH_CAPTURE";
		$post_values['x_delim_data'] = "TRUE";
		$post_values['x_delim_char'] = "|";

		// if trial period, set amount to the trial amount
		if (strtoupper($req['is_recurring']) == "Y") {
			if ( isset($req['trial_amount']) && ($req['trial_amount'] != "0.00") && ($req['trial_amount'] != "0.0") && ($req['trial_amount'] != "0") ) {
				$amount = $req['trial_amount'];
			}
			else { 
				$amount = $req['amount'];
			}
			$post_values['x_amount'] = $amount;
		}
		else {
			$post_values['x_amount'] = $req['amount'];
			$amount = $req['amount'];
		}
		
		
		// Set x_recurring_billing to FALSE even if there is a subsequent recurring because setting to TRUE results in no validation for CC exp date and card code
		$post_values['x_recurring_billing'] = "FALSE";
		
		$post_values['x_card_num'] = $this->getCard_num();
		$post_values['x_exp_date'] = $this->getExp_date();
		$post_values['x_card_code'] = $this->getCard_code();
		//$post_values['x_test_request'] = $req['testmode'];
		$post_values['x_relay_response'] = "FALSE";

		//customer info
		$post_values['x_cust_id'] = $req['txn_id'];
		
		//Order Information
		$post_values['x_invoice_num'] = $req['txn_id'];
		$post_values['x_description'] = $req['item_name'];;

		//Itemized Order Information
		$delim = "<|>";
		$quantity = "1";
		$req['product_id'] = "1";
			 
		$post_values['x_line_item'] = $req['product_id'] . $delim . $req['item_name'] . $delim . $req['item_name'] . $delim . $quantity . $delim . $amount . $delim . "N";
		logToFile("Dap_CustomPayment:create_custom_authnet_subscription(). Authnet AIM line_item: " . $post_values['x_line_item'], LOG_DEBUG_DAP);

		//Billing Information
		$post_values['x_first_name'] = $this->getFirst_name();
		$post_values['x_last_name'] = $this->getLast_name();
		$post_values['x_address'] = $this->getAddress1();
		$post_values['x_city'] = $this->getCity();
		$post_values['x_state'] = $this->getState();
		$post_values['x_zip'] = $this->getZip();
		$post_values['x_country'] = $this->getCountry();			
		$post_values['x_phone'] = $this->getPhone();	
		$post_values['x_email'] = $this->getEmail();	

		//Shipping Information  
		$post_values['x_ship_to_first_name'] = $this->getShip_to_first_name();
		$post_values['x_ship_to_last_name'] = $this->getShip_to_last_name();
		$post_values['x_ship_to_address'] = $this->getShip_to_address1();
		$post_values['x_ship_to_city'] = $this->getShip_to_city();
		$post_values['x_ship_to_state'] = $this->getShip_to_state();
		$post_values['x_ship_to_zip'] = $this->getShip_to_zip();
		$post_values['x_ship_to_country'] = $this->getShip_to_country();

		// Convert to proper format for an http post
		$post_string = "";

		foreach( $post_values as $key => $value ) {
			$post_string .= "$key=" . urlencode( $value ) . "&"; 
			//logToFile("Dap_CustomPayment:create_custom_authnet_subscription(). Value= " . $value, LOG_DEBUG_DAP);
		}
		$post_string = rtrim( $post_string, "& " );

		logToFile("Dap_CustomPayment:create_custom_authnet_subscription(). Authnet AIM request: " . $post_string, LOG_DEBUG_DAP);
		
		// Use CURL to establish a connection, submit the post, and record the response.
		//$post_url = "https://test.authorize.net/gateway/transact.dll";
		$post_url=$req['gateway_url'];
		$request = curl_init($post_url); // initiate curl object
		curl_setopt($request, CURLOPT_HEADER, 0); // set to 0 to eliminate header info from response
		curl_setopt($request, CURLOPT_RETURNTRANSFER, 1); // Returns response data instead of TRUE(1)
		curl_setopt($request, CURLOPT_POSTFIELDS, $post_string); // use HTTP POST to send form data
		curl_setopt($request, CURLOPT_SSL_VERIFYPEER, FALSE); // uncomment this line if you get no gateway response.
		$post_response = curl_exec($request);
		curl_close ($request); // close curl object
		
		//if the connection and send worked $post_response holds the return from Authorize.net
		if (isset ($post_response)) // transaction approved
		{
		// This line takes the response and breaks it into an array using the specified delimiting character
			$response_array = explode($post_values["x_delim_char"], $post_response);
			// The results are output to the screen in the form of an html numbered list.
			/*foreach ($response_array as $value)
				logToFile("Dap_CustomPayment:create_custom_authnet_subscription(). Authnet AIM response: " . $value, LOG_DEBUG_DAP);*/

			$aresult = array();
			$aresult['response_code'] = $response_array[0];
			$aresult['response_subcode'] = $response_array[1];
			$aresult['response_reason_code'] = $response_array[2];
			$aresult['text'] = $response_array[3];
			
			logToFile("Dap_CustomPayment:create_custom_authnet_subscription(). response array: " . $response_array[0], LOG_DEBUG_DAP);
			if ( (stristr($response_array[0], '1') == TRUE) ){ //approved payment
				logToFile("Dap_CustomPayment:create_custom_authnet_subscription(). Approved payment for user: " . $this->getEmail(), LOG_DEBUG_DAP);
				if (($req['testmode'] == "Y") && ($response_array[6] == "0"))
					$aresult['txn_id'] =  $invoice . ":0"; 
				else
					$aresult['txn_id'] =  $response_array[6] . ":0";  
	
				logToFile("Dap_CustomPayment:create_custom_authnet_subscription(): AIM transaction ID=" . $aresult['txn_id'], LOG_DEBUG_DAP);
			 
				// set params
				$aresult['payment_num'] = "0"; //first payment via AIM
				$aresult['item_name'] = $req['item_name'];
				$aresult['description'] = $req['description'];
				$aresult['amount'] = $amount;
				$aresult['invoice'] = $invoice;
				$aresult['payer_email'] = urldecode($this->getEmail());
				$aresult['email'] = $this->getEmail();
				$aresult['phone'] = $this->getPhone();
				$aresult['fax'] = $this->getFax();
				
				$aresult['payment_gateway'] = $req['payment_gateway'];
				
				//record user->product in database
				if (!$this->processPaymentResponse ($aresult, "Dap_CustomPayment"))
				{
					$subject="DAP transaction recording failed for the user: " . $aresult['email'];
					$body="Dap_CustomPayment:processPaymentResponse(): " . $req['payment_gateway'] . " payment transaction successfully processed but DAP transaction recording failed for the user: " . $aresult['email'] . ", who bought the item: " . $aresult['item_name'] . ", for the amount: $" . $aresult['amount'] . ". But the Silent Post should arrive shortly that would provide DAP with another chance to create user->product relationship and record the transaction. If not, this transaction will have to be handled manually within DAP admin console";
					sendAdminEmail($subject, $body);
					logToFile($body, LOG_DEBUG_DAP);
				}
				else if (strtoupper($req['is_recurring']) == "Y")
				{
					// show success page for the successful initial transaction via AIM but send admin email for ARB (recurring) failure
					if (!$this->create_authnet_recurring_subscription ($req, $aresult))
					{
						$subject="Recurring subscription failed for the user: " . $aresult['email'];
						
						$body="Dap_CustomPayment:create_authnet_recurring_subscription():failed to process the recurring transaction for user: " . $aresult['email'] . ", who bought the item: " . $aresult['item_name'] . ", for the amount: $" . $aresult['amount'] . ". The recurring payment transaction will have to be handled manually within authorize.net.";
						
						sendAdminEmail($subject, $body);
						logToFile($body, LOG_DEBUG_DAP);
					}
				}
				
				logToFile("Dap_CustomPayment:processPaymentResponse(): Payment Success!");
			//	header("Location: " . $req['payment_succ_page']);
				return TRUE;
			}
			else if ( (stristr($response_array[0], '2') == TRUE) || (stristr($response_array[0], '3') == TRUE) || (stristr($response_array[0], '4') == TRUE)  ) { 
				// 2 = Declined, 3 = Error, 4 = Held for Review
				$response_text = "Response Text: Failed for user: " . $req['payer_email'] . " for product = " . $req['item_name'] . " with Error Code: " . $response_array[0] . ", Error Text: " . $aresult['text'];
								
				sendAdminEmail("Authnet AIM payment failed for " . $req['payer_email'], "Dap_CustomPayment:create_custom_authnet_subscription(): " . $response_text);
				
				logToFile("Dap_CustomPayment: " . $response_text, LOG_DEBUG_DAP);
				echo "Response Code: " . AUTHNET_TRANSACTION_DECLINED_CODE . "<br />";
				echo "Response Msg: " . AUTHNET_TRANSACTION_DECLINED_MSG . "<br />";
					
				echo $response_text . "<br />";
							
				return FALSE;
			}
			else {
				
				$response_text="Sorry, the Authorize.net transaction did not go through for user: " . $aresult['email'] . " for product = " . $aresult['item_name'] . " with Error Code: " . $response_array[0] . ", Error Text: " . $aresult['text'] . ". Please contact the Site Admin".
				
				sendAdminEmail("Sorry, the Authorize.net transaction did not go through for user: " . $aresult['email'], "Dap_CustomPayment:create_custom_authnet_subscription(): " . $response_text);
				
				logToFile("Dap_CustomPayment: " . $response_text, LOG_DEBUG_DAP);
				echo "Response Code: " . AUTHNET_TRANSACTION_FAILED_CODE . "<br />";
				echo "Response Msg: " . AUTHNET_TRANSACTION_FAILED_MSG . "<br />";
					
				echo $response_text . "<br />";
				return FALSE;
			}
		}
		else
		{
			$response_text ="Sorry, failed to connect to Authorize.net for user: " . $aresult['email'] . ". Please retry or contact the site admin";
		
			sendAdminEmail("Failed to connect to authnet for user: " . $aresult['email'], "Dap_CustomPayment:create_custom_authnet_subscription(): " . $response_text);
			
			logToFile("Dap_CustomPayment: " . $response_text, LOG_DEBUG_DAP);
			echo "Response Code: " . COULD_NOT_CONNECT_CODE . "<br />";
			echo "Response Msg: " . COULD_NOT_CONNECT_MSG . "<br />";
				
			echo $response_text;
			return FALSE;
			
		}
		
		return true;
		
	}  // end function


// ===================
	public function create_authnet_recurring_subscription($req, $aresult)  // ARB
	{
		$gateway_recur_url = trim(Dap_Config::get('GATEWAY_RECUR_URL'));
		logToFile("Dap_CustomPayment:create_authnet_recurring_subscription(): " . $gateway_recur_url, LOG_DEBUG_DAP);

		if(!$this->validateInput($req)) return FALSE;

		if(!($xmlpos = strpos ($gateway_recur_url, "xml"))) {
			$response_text ="Dap_CustomPayment:create_authnet_recurring_subscription(): Incorrect merchant url";
			logToFile("Dap_CustomPayment: " . $response_text, LOG_DEBUG_DAP);
			echo "Response Code: " . INCORRECT_MERCHANT_URL_CODE  . "<br />";
			echo "Response Msg: " . INCORRECT_MERCHANT_URL_MSG  . "<br />";
				
			echo $response_text  . "<br />";
			return FALSE;
		}

		$path = substr($gateway_recur_url, $xmlpos - 1); 
		$host = substr($gateway_recur_url, 8, $xmlpos - 8 - 1 ); // skip http:// (7 char) and "/" before xml (1 char)
		logToFile("Dap_CustomPayment:create_authnet_recurring_subscription() - path=" . $path . "host=" . $host ,LOG_DEBUG_DAP);

		//sequence number is randomly generated
		$sequence	= rand(1, 1000);
		//timestamp is generated
		$timestamp = time ();
		$login_name = $req['login_name'];
		$transaction_key = $req['transaction_key'];
		
		logToFile("Dap_CustomPayment:create_authnet_recurring_subscription() - login_name=" . $login_name . "transaction_key=" . $transaction_key,LOG_DEBUG_DAP);
		//The following lines generate the SIM fingerprint.  PHP versions 5.1.2 and
		//newer have the necessary hmac function built in.  For older versions, it
		//will try to use the mhash library.
		if( phpversion() >= '5.1.2' )
		{	
			$fingerprint = hash_hmac("md5", $login_name . "^" . $sequence . "^" . $timestamp . "^" . $amount . "^", $transaction_key); 
		}
		else 
		{ 
			$fingerprint = bin2hex(mhash(MHASH_MD5, $login_name . "^" . $sequence . "^" . $timestamp . "^" . $amount . "^", $transaction_key));									
		}

		$amount = isset($req['amount']) ? $req['amount'] : "0.01";
		$refId = $req['refId'];

		//subscription name... same as product name
		$item_name = $req['item_name'];
		
		logToFile("Dap_CustomPayment:create_authnet_recurring_subscription() - item_name=" . $item_name . "transaction_key=" . $transaction_key,LOG_DEBUG_DAP);
		
		$invoice = $aresult['invoice'] . "-0";

		//total occurrence should exclude the trial occurrence if any trial period
		$total_occurrences = isset($req['total_occurrences']) ? $req['total_occurrences']: "999";

		// if any trial period, it would be covered by initial subscription via AIM
		$trial_occurrences ="0";
		$trial_amount = "0.0";

		//frequency of billing occurrences
		$payment_length = $req['recurring_cycle_3'];
		$payment_unit = "days";

		// first recurring payment should occur at the end of first recurring period
		$start_date = date('Y-m-d', strtotime("+".$req['recurring_cycle_1']." ".$payment_unit));

		//build xml to post
		$content =
		"<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
		"<ARBCreateSubscriptionRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
		"<merchantAuthentication>".
			"<name>" . $req['login_name'] . "</name>".
			"<transactionKey>" . $req['transaction_key'] . "</transactionKey>".
		"</merchantAuthentication>".
		"<refId>" . $refId . "</refId>".
		"<subscription>".
			"<name><![CDATA[" . $item_name . "]]></name>".
			"<paymentSchedule>".
				"<interval>".
				"<length>". $payment_length ."</length>".
				"<unit>". $payment_unit ."</unit>".
				"</interval>".
				"<startDate>" . $start_date . "</startDate>".
				"<totalOccurrences>". $total_occurrences . "</totalOccurrences>".
				"<trialOccurrences>". $trial_occurrences . "</trialOccurrences>".
			"</paymentSchedule>".
			"<amount>". $amount ."</amount>".
			"<trialAmount>" . $trial_amount . "</trialAmount>".
			"<payment>".
				"<creditCard>".
					"<cardNumber>" . $this->getCard_num() . "</cardNumber>".
					"<expirationDate>" . $this->getExp_date() . "</expirationDate>".
				"</creditCard>".
			"</payment>".
			"<order>".
				"<invoiceNumber>". $invoice . "</invoiceNumber>".
				"<description><![CDATA[" . $item_name . "]]></description>".
			"</order>".
			"<customer>".
				"<id>". $invoice . "</id>".
				"<email>". $this->getEmail() . "</email>".
				"<phoneNumber>" . $this->getPhone() . "</phoneNumber>".
				"<faxNumber>" . $this->getFax() . "</faxNumber>".
			"</customer>".
			"<billTo>".
				"<firstName>". $this->getFirst_name() . "</firstName>".
				"<lastName>" . $this->getLast_name() . "</lastName>".
				"<address>" . $this->getAddress1() . "</address>".
				"<city>" . $this->getCity() . "</city>".
				"<state>" . $this->getState() . "</state>".
				"<zip>" . $this->getZip() . "</zip>".
				"<country>" . $this->getCountry() . "</country>".
			"</billTo>".
			"<shipTo>".
				"<firstName>". $this->getShip_to_first_name() . "</firstName>".
				"<lastName>" . $this->getShip_to_last_name() . "</lastName>".
				"<address>" . $this->getShip_to_address1() . "</address>".
				"<city>" . $this->getShip_to_city() . "</city>".
				"<state>" . $this->getShip_to_state() . "</state>".
				"<zip>" . $this->getShip_to_zip() . "</zip>".
				"<country>" . $this->getShip_to_country() . "</country>".
			"</shipTo>".
		"</subscription>".
		"</ARBCreateSubscriptionRequest>";

		logToFile("Dap_CustomPayment:XML content: " . $content, LOG_DEBUG_DAP);
		
		//send the xml via curl
		$response = send_request_via_curl($host,$path,$content);

	//if the connection and send worked $response holds the return from Authorize.net
		if ($response)
		{
			list ($ref_id, $result_code, $code, $text, $subscription_id) =parse_return($response);
			if (!strcasecmp ($result_code,"Ok")) { //SUCCESS
				logToFile("Dap_CustomPayment:create_authnet_recurring_subscription(). Dap_CustomPayment successfully processed by authorize.net", LOG_DEBUG_DAP);
				return TRUE;
			}
			else {
				logToFile("Dap_CustomPayment:create_authnet_recurring_subscription(). Error Code: " . $result_code . " Reason Code: " . $code . " text: " . $text . " Subs Id: " . $subscription_id , LOG_DEBUG_DAP);
				
				sendAdminEmail ("Authnet recurring subscription", "Authnet recurring subscription failed for " . $aresult['email'] . " with Error Code: " . $result_code . " Reason Code: " . $code . " text: " . $text);
				
				return FALSE;
			}
		}	
		else
		{
			logToFile("Dap_CustomPayment:create_authnet_recurring_subscription(). Failed to connect to authnet", LOG_DEBUG_DAP);
			
			sendAdminEmail("Authnet connection could not be established for " . $aresult['email'], "Dap_CustomPayment:create_custom_authnet_subscription(). Paypal connection for recurring payment could not be established for: " . $aresult['email'] . " for product = " . $aresult['item_name']);
			
			return FALSE;
		}

	}  // end function

	// This function called by both initial payment (AIM) as well as silent post (ARB - recurring)
	public function processCustomAuthnetResponse($post, $inp, $source) {
		logToFile("Dap_CustomPayment:processAuthnetResponse(): " . $source . " response: ", $inp['email']); 

		$inp['txn_type']= "subscr_payment";

		if(!isset($inp['mc_gross'])) { 
			$inp['mc_gross'] = '0.00';
		}
		if(isset($inp['mc_amount3'])) {   
			$inp['mc_gross'] = $inp['mc_amount3'];
		}
		if(isset($inp['mc_amount2'])) {
			$inp['mc_gross'] = $inp['mc_amount2'];
		}
		if(isset($inp['mc_amount1'])) {  
			$inp['mc_gross'] = $inp['mc_amount1'];
		}

		if(!isset($inp['payment_status'])) { 
			$inp['payment_status'] = 'Completed';
		}

		$inp['mc_currency'] = '$';
		$ignore_dup_and_proceed = false;
		
		// set params
		$inp['first_name'] = $this->getFirst_name();
		$inp['last_name'] = $this->getLast_name();
		$inp['address1'] = $this->getAddress1();
		$inp['city'] = $this->getCity();
		$inp['state'] = $this->getState();
		$inp['zip'] = $this->getZip();
		$inp['country'] = $this->getCountry();
		$inp['phone'] = $this->getPhone();
		$inp['fax'] = $this->getFax();
		
		$inp['ship_to_first_name'] = $this->getShip_to_first_name();
		$inp['ship_to_last_name'] = $this->getShip_to_last_name();
		$inp['ship_to_address1'] = $this->getShip_to_address1();
		$inp['ship_to_city'] = $this->getShip_to_city();
		$inp['ship_to_state'] = $this->getShip_to_state();
		$inp['ship_to_zip'] = $this->getShip_to_zip();
		$inp['ship_to_country'] = $this->getShip_to_country();
						
		try {
			$record_id = Dap_PaymentProcessor::recordAuthnetIncoming($inp);
			logToFile("Dap_CustomPayment:processAuthnetResponse(): recorded incoming authnet. id:". $record_id);
		} 
		catch (PDOException $e) {
			if(stristr($e->getMessage(), "SQLSTATE[23000]: Integrity constraint violation: ") == FALSE) {
				logToFile($e->getMessage(),LOG_FATAL_DAP);
				throw $e;
				return false;
			}
			else {
				$ignore_dup_and_proceed = true;
				logToFile("Dap_CustomPayment: " . $e->getMessage(),LOG_DEBUG_DAP);
			}
  		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
			return false;
		}

		if (!$ignore_dup_and_proceed) {
			Dap_Transactions::setRecordStatus($record_id, 1);
			logToFile("Dap_CustomPayment:processAuthnetResponse(): set record status to 1 for record_id=". $record_id);

			Dap_Transactions::processTransaction($record_id);
		}
		else if (strcmp($source, "Dap_CustomPayment") == 0) {
			logToFile("Dap_CustomPayment:processAuthnetResponse(): silent post came in first, ok to ignore initial AIM integrity constraint error, LOG_DEBUG_DAP");
		}
				
		return true;
	}
	
	// This function called by both initial payment (AIM/Direct Payment) as well as silent post / IPN notify for recurring
	public function processPaymentResponse($inp, $source) {
		logToFile("Dap_CustomPayment:processPaymentResponse(): " . $source . " response: ", $inp['email']); 

		if (!isset($inp['txn_type']) || ($inp['txn_type'] == "")) 
			$inp['txn_type'] = "subscr_payment";

		if(!isset($inp['mc_gross'])) { 
			$inp['mc_gross'] = '0.00';
		}
		if(isset($inp['mc_amount3'])) {   
			$inp['mc_gross'] = $inp['mc_amount3'];
		}
		if(isset($inp['mc_amount2'])) {
			$inp['mc_gross'] = $inp['mc_amount2'];
		}
		if(isset($inp['mc_amount1'])) {  
			$inp['mc_gross'] = $inp['mc_amount1'];
		}

		if(!isset($inp['payment_status'])) { 
			$inp['payment_status'] = 'Completed';
		}

		$inp['mc_currency'] = trim(Dap_Config::get('CURRENCY_SYMBOL'));	
		if (!isset($inp['mc_currency']) || $inp['mc_currency'] == '')
			$inp['mc_currency'] = urlencode("USD");
		
		$ignore_dup_and_proceed = false;
		
		// set params
		$inp['first_name'] = $this->getFirst_name();
		$inp['last_name'] = $this->getLast_name();
		$inp['address1'] = $this->getAddress1();
		$inp['city'] = $this->getCity();
		$inp['state'] = $this->getState();
		$inp['zip'] = $this->getZip();
		$inp['country'] = $this->getCountry();
		$inp['phone'] = $this->getPhone();
		$inp['fax'] = $this->getFax();
		
		$inp['ship_to_first_name'] = $this->getShip_to_first_name();
		$inp['ship_to_last_name'] = $this->getShip_to_last_name();
		$inp['ship_to_address1'] = $this->getShip_to_address1();
		$inp['ship_to_city'] = $this->getShip_to_city();
		$inp['ship_to_state'] = $this->getShip_to_state();
		$inp['ship_to_zip'] = $this->getShip_to_zip();
		$inp['ship_to_country'] = $this->getShip_to_country();
						
		try {
			if ($inp['payment_gateway'] == "paypal") {
				$record_id = Dap_PaymentProcessor::recordPaypalIncoming($inp);
				logToFile("Dap_CustomPayment:processPaymentResponse(): recorded incoming paypal. id:". $record_id);
			}
			else {
				$record_id = Dap_PaymentProcessor::recordAuthnetIncoming($inp);
				logToFile("Dap_CustomPayment:processPaymentResponse(): recorded incoming authnet. id:". $record_id);
			}
		} 
		catch (PDOException $e) {
			if(stristr($e->getMessage(), "SQLSTATE[23000]: Integrity constraint violation: ") == FALSE) {
				logToFile($e->getMessage(),LOG_FATAL_DAP);
				throw $e;
				return false;
			}
			else {
				$ignore_dup_and_proceed = true;
				logToFile("Dap_CustomPayment: " . $e->getMessage(),LOG_DEBUG_DAP);
			}
  		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
			return false;
		}

		if (!$ignore_dup_and_proceed) {
			Dap_Transactions::setRecordStatus($record_id, 1);
			logToFile("Dap_CustomPayment:processPaymentResponse(): set record status to 1 for record_id=". $record_id);

			Dap_Transactions::processTransaction($record_id);
		}
		else if (strcmp($source, "Dap_CustomPayment") == 0) {
			logToFile("Dap_CustomPayment:processPaymentResponse(): silent post/notification came in first, ok to ignore initial payment's integrity constraint error, LOG_DEBUG_DAP");
		}
				
		return true;
	}

	
	// This function called by both initial payment (AIM/Direct Payment) as well as silent post / IPN notify for recurring
	public function sendResponseViaCurl($post, $response) {
		// Convert to proper format for an http post
		$post_string = "&";

		foreach( $post_values as $key => $value ) {
			$post_string .= "$key=" . $value . "&"; 
			logToFile("Dap_CustomPayment:sendResponseViaCurl(). " . $key . "=" . $value	, LOG_DEBUG_DAP);
		}
		$post_string = rtrim( $post_string, "& " );

		logToFile("Dap_CustomPayment:sendResponseViaCurl(): Response String:  " . $post_string, LOG_DEBUG_DAP);
		
		// Use CURL to establish a connection, submit the post, and record the response.
		//$post_url = "https://test.authorize.net/gateway/transact.dll";
				
		$ch = curl_init();
		
		$req['api_endpoint'] = "/dap/processCustomAuthnetResponse.php";
		curl_setopt($ch, CURLOPT_URL, $req['api_endpoint']);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);

		//turning off the server and peer verification(TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch, CURLOPT_POST, 1);
		
	}
	
} //end class

?>
