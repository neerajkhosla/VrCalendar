<?php 
	
	/*
		Just get all needed ele ments of the incoming paypal request and record it along with the blob.
		This does not verify anything. Just parse and record.
		TODO: REMOVE THE EXAMPLE BELOW FOR PROD.
		Returns: the last insert id of mysql. Caller should use this to invoke processPaypalTransaction method . That methods needs it as handle.
	*/
	function recordPaypalIncoming($post) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			//loop over incoming post array
			$req = "";
			foreach ($post as $key => $value) {
				$value = urlencode(stripslashes($value));
				$req .= "&$key=$value";
				logToFile("recordPaypalIncoming. POST. Key=".$key.",value=".$value); 
			}
			logToFile("PAYPAL_INCOMING:".$req, LOG_INFO_DAP);
			//lets do the insert and get the newly created  id out.
			$transaction = new Dap_Transactions();
			$transaction->setTrans_num($post['txn_id']);
			$transaction->setTrans_type($post['txn_type']);
			$transaction->setPayment_status($post['payment_status']);
			$transaction->setPayment_currency($post['mc_currency']);
			$transaction->setPayment_value($post['mc_gross']);
			$transaction->setPayer_email($post['payer_email']);
			$transaction->setPayment_processor("PAYPAL");
			$transaction->setProduct_id($post['item_number']);
			$transaction->setTrans_blob($req);
			$transaction->create();
			logToFile("recordPaypalIncoming. Insert into transactions Done.."); 
			
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
		Lets you set the dap internal status on the transaction record. 
		
		0 -  Init status - db default
		1 - Paypal Verified (payment processor verified)
		2 - Paypal Invalid (payment processor declined) - admin  reprocessible
		3 - Paypal Communication Error (payment processor cannot be reached) -  admin reprocessible
		4 - Misc Error - admin reprocessible
		5 - Processed successfully.
		6 - Processed ERROR.
	*/
	/*
	function setRecordStatus($record_id, $status) {
		logToFile("init..(functions_paypal)(setRecordStatus)");
		try {
			$dap_dbh = Dap_Connection::getConnection();	
			$sql = "UPDATE dap_transactions set status = $status where id = $record_id ";
			$dap_dbh->query($sql);	
			return TRUE;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			//TODO - SEND EMAIL IN CASE OF EXCEPTION ?
			//throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			//throw $e;
		}
		return FALSE;
	}
	*/
	
	

	
	/*
		Workhorse of paypal processing AFTER its either VERIFIED or Admin Adjusted to VERIFIED.
		This will not PROCESS anything other status = 1 and payment_status as 'Completed' from paypal.
		
		Input: Record ID (database) of the transaction we want to process.
		Output: TRUE for successful processing and FALSE for errored processing. Status will be set in db accordingly. 
		
		Notes:
		1. 
		
		TODO: THIS IS ONLY ADD PRODUCTS, NEED TO FINISH REMOVE PRODUCTS
	
	*/
	function processPaypalTransaction3($record_id) {
		logToFile("Processing Transaction With ID:".$record_id);
		//Lets get payer email and product id on the transaction that is this id and paypal payment status as complete and status as 1 (Paypal verified).
		$Transaction  =  loadVerifiedPaypalTransaction($record_id); 
		//Verify the price on incoming paypal is same as the one in our db. 
		$productId = $Transaction->getProduct_id();
		$product  = Dap_Product::loadProduct($productId);
		logToFile("(functions_paypal)(processPaypalTransaction) - Product Price:".$product->getPrice().": Transaction Payment Amount:".$Transaction->getPayment_value());
		if(!isset($product) ||  $product->getPrice() != $Transaction->getPayment_value()) {
			//mark Transaction as admin correctible error.
			Dap_Transaction::setRecordStatus($record_id, 4);
			return;
		}
		//
		if (isset($Transaction)) {
			$payment_status = $Transaction->getPayment_status();
			//Lets try to get user and see if it exists
			$user = Dap_User::loadUserByEmail($Transaction->getPayer_email());
			//TODO: Should we check for id > 0 or something like that ?
			logToFile("processPaypalTransaction:CheckingUser:".$user->getEmail());
			if(isset($user)) {
				if($payment_status == "Completed") {
					//We have user, now try to add to user products association.
					$uid = $user->getId();
					$usersproducts = Dap_UsersProducts::addUsersProducts($uid, $Transaction->getProduct_id(), $record_id);
					if(isset($usersproducts)) {
						Dap_Transaction::setRecordStatus($record_id, 5);
					}
					return $usersproducts;		
				}
				if($payment_status == "Refund") {
					//We have refund, try to negate stuff.
					$uid = $user->getId();
					$retval = removeUsersProducts($uid, $Transaction->getProduct_id(), $record_id);
					if($retval == TRUE) {
						Dap_Transaction::setRecordStatus($record_id, 5);
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
			//see if its paypal IPN, CB call back or other email parsed data.
			if(array_key_exists('first_name',$list)) {
				$user->setFirst_name( $list["first_name"] );
				$user->setLast_name( $list["last_name"] );
			} else if(array_key_exists('cname',$list)) {
				$user->setFirst_name( $list["cname"] );
				$user->setLast_name( $list["cname"] );
			}

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
						Dap_Transaction::setRecordStatus($record_id, 5);
					}
					return $usersproducts;		
				}
				if($payment_status == "Refund") {
					//We have refund, try to negate stuff.
					$uid = $user->getId();
					$retval = removeUsersProducts($uid, $Transaction->getProduct_id(), $record_id);
					if($retval == TRUE) {
						Dap_Transaction::setRecordStatus($record_id, 5);
					}
					//TODO: REVIEW THIS AND SEE IF WE SHOULD RETURN ANYTHING IN SUCCESS/FAILURE SCENARIOS.
					return;		
				}
			}
		}
		return;
	}
	
	
	function processPaypalTransaction2($record_id) {
		logToFile("Processing Transaction With ID:".$record_id);
		//Lets get payer email and product id on the transaction that is this id and paypal payment status as complete and status as 1 (Paypal verified).
		$sql = "select 
				payer_email, 
				product_id, 
				trans_blob,
				payment_status
				from dap_transactions
				where 
				id = '$record_id' and
				status = 1 and
				payment_status in ('Completed', 'Refund') and
				trans_type != 'subscr_signup'
				";
		//echo "sql: $sql<br>"; exit;
		logToFile("processPaypalTransaction:SQL:".$sql);
		$result = mysql_query($sql);
		//we got some results - it should only be one. 
		if ($row = @mysql_fetch_assoc($result)) {
			$payment_status = $row['payment_status'];
			//Lets try to get user and see if it exists
			$user = Dap_User::loadUserByEmail($row['payer_email']);
			//TODO: Should we check for id > 0 or something like that ?
			logToFile("processPaypalTransaction:CheckingUser:".$user->getEmail());
			if(isset($user)) {
				if($payment_status == "Completed") {
					//We have user, now try to add to user products association.
					$uid = $user->getId();
					$usersproducts = addUsersProducts($uid, $row['product_id'], $record_id);
					if(isset($usersproducts)) {
						Dap_Transaction::setRecordStatus($record_id, 5);
					}
					return $usersproducts;		
				}
				if($payment_status == "Refund") {
					//We have refund, try to negate stuff.
					$uid = $user->getId();
					$retval = removeUsersProducts($uid, $row['product_id'], $record_id);
					if($retval == TRUE) {
						Dap_Transaction::setRecordStatus($record_id, 5);
					}
					//TODO: REVIEW THIS AND SEE IF WE SHOULD RETURN ANYTHING IN SUCCESS/FAILURE SCENARIOS.
					return;		
				}
			}
			//no user and this is refund. 
			// TODO: IS IT POSSIBLE THAT USER IS INACTIVE AND REFUND IS COMING IN.
			// for now, return nothing and do nothing.
			if($payment_status == "Refund") return;
			//we dont have user. 
			//TODO: FIX THIS TO CREATE NEW USER AND RETRY ADD USER TO PRODUCT.
			//lets create a user object and then pass it to createUser.
			parse_str($row['trans_blob'], $list);
			//lets make sure required variables are set.  
			// Required are first name and email.
			$user = new Dap_User();
			$user->setFirst_name( $list["first_name"] );
			$user->setLast_name( $list["last_name"] );
			$user->setEmail( $row["payer_email"] );				
			$user->setStatus("A");
			$user->create();
			//TODO : POPULATE MORE ITEMS HERE, IF POSSIBEL.
			if(isset($user)) {
				//We have user, now try to add to user products association.
				$uid = $user->getId();
				$usersproducts = addUsersProducts($uid, $row['product_id'], $record_id);
				if(isset($usersproducts)) {
					Dap_Transaction::setRecordStatus($record_id, 5);
				}				
				return $usersproducts;			
			}
		}
		return;
	}	



?>
