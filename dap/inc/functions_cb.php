<?php 
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
	function recordCBIncoming($post) {
		//loop over incoming post array
		
		$req = "";
		foreach ($post as $key => $value) {
			$value = urlencode(stripslashes($value));
			$req .= "&$key=$value";
		}
		logToFile("CLICKBANK_INCOMING:".$req, LOG_INFO_DAP);
		
		if(!isset($post['item']) ||
			!isset($post['cbreceipt']) ||
			!isset($post['cbreceipt'])) {
			return;
		}
		//product id
		$product_id = mysql_real_escape_string($post['item']);
		
		//transaction information
		$trans_num = mysql_real_escape_string($post['cbreceipt']);
		//$trans_type = mysql_real_escape_string($_POST['txn_type']);	
		$trans_type = "cb_online";
		
		//payment information
		$payment_processor = "CLICKBANK";
		$payment_status = "Completed";
		//$payment_amount = mysql_real_escape_string($_POST['mc_gross']);
		//$payment_currency = mysql_real_escape_string($_POST['mc_currency']);
		$payment_currency = "USD";

		//receiver and payer email ids
		//$receiver_email = mysql_real_escape_string(urldecode($post['cemail']));
		$payer_email = mysql_real_escape_string($post['cemail']);

		//lets do the insert and get the newly created  id out.
		$sql = "INSERT into dap_transactions (trans_num, trans_type, payment_status, payment_currency, payer_email, sub_trans_num,
							payment_processor, date, trans_blob, product_id) 
							values ('$trans_num', '$trans_type', '$payment_status', '$payment_currency', '$payer_email', '0',
							'$payment_processor', now(), '$req', $product_id)";
		logToFile("recordCBIncoming: $sql");

		try {
			$dap_dbh = Dap_Connection::getConnection();
			$stmt = $dap_dbh->prepare($sql);
			$stmt->execute();			
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
		
		return $last_insert_id;	
	}
	

	/*
		Workhorse of paypal processing AFTER its either VERIFIED or Admin Adjusted to VERIFIED.
		This will not PROCESS anything other status = 1 and payment_status as 'Completed' from paypal.
		
		Input: Record ID (database) of the transaction we want to process.
		Output: TRUE for successful processing and FALSE for errored processing. Status will be set in db accordingly. 
		
		Notes:
		1. 
		
		TODO: THIS IS ONLY ADD PRODUCTS, NEED TO FINISH REMOVE PRODUCTS
	
	*/
	function processCBTransaction($record_id) {
		logToFile("Processing Transaction With ID:".$record_id);
		//Lets get payer email and product id on the transaction that is this id and paypal payment status as complete and status as 1 (Paypal verified).
		$Transaction  =  loadVerifiedPaypalTransaction($record_id); 
		if (isset($Transaction)) {
			$payment_status = $Transaction->getPayment_status();
			//Lets try to get user and see if it exists
			$user = Dap_User::loadUserByEmail($Transaction->getPayer_email());
			//TODO: Should we check for id > 0 or something like that ?
			//logToFile("processPaypalTransaction:CheckingUser:".$user->getEmail());
			if(isset($user)) {
				if($payment_status == "Completed") {
					//We have user, now try to add to user products association.
					$uid = $user->getId();
					$usersproducts = addUsersProducts($uid, $Transaction->getProduct_id(), $record_id);
					if(isset($usersproducts)) {
						setRecordStatus($record_id, 5);
					}
					return $usersproducts;		
				}
				if($payment_status == "Refund") {
					//We have refund, try to negate stuff.
					$uid = $user->getId();
					$retval = removeUsersProducts($uid, $Transaction->getProduct_id(), $record_id);
					if($retval == TRUE) {
						setRecordStatus($record_id, 5);
					}
					//TODO: REVIEW THIS AND SEE IF WE SHOULD RETURN ANYTHING IN SUCCESS/FAILURE SCENARIOS.
					return;		
				}
			}
			//no user and this is refund. 
			// TODO: IS IT POSSIBLE THAT USER IS INACTIVE AND REFUND IS COMING IN.
			// for now, return nothing and do nothing.
			//if($payment_status == "Refund") return;
			//we dont have user. 
			//TODO: FIX THIS TO CREATE NEW USER AND RETRY ADD USER TO PRODUCT.
			//lets create a user object and then pass it to createUser.
			parse_str($Transaction->getTrans_blob(), $list);
			//lets make sure required variables are set.  
			// Required are first name and email.
			$user = new Dap_User();
			$user->setFirst_name( $list["cname"] );
			$user->setLast_name( $list["cname"] );
			$user->setEmail( $Transaction->getPayer_email() );				
			$user->setStatus("A");
			$user->create();
			//TODO : POPULATE MORE ITEMS HERE, IF POSSIBEL.
			if(isset($user)) {
				//We have user, now try to add to user products association.
				if($payment_status == "Completed") {
					//We have user, now try to add to user products association.
					$uid = $user->getId();
					$usersproducts = addUsersProducts($uid, $Transaction->getProduct_id(), $record_id);
					if(isset($usersproducts)) {
						setRecordStatus($record_id, 5);
					}
					return $usersproducts;		
				}
				if($payment_status == "Refund") {
					//We have refund, try to negate stuff.
					$uid = $user->getId();
					$retval = removeUsersProducts($uid, $Transaction->getProduct_id(), $record_id);
					if($retval == TRUE) {
						setRecordStatus($record_id, 5);
					}
					//TODO: REVIEW THIS AND SEE IF WE SHOULD RETURN ANYTHING IN SUCCESS/FAILURE SCENARIOS.
					return;		
				}
			}
		}
		return;
	}



?>
