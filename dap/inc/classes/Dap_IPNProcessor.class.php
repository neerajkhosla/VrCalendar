<?php

class Dap_IPNProcessor extends Dap_Base {
	/*
		Just get all needed ele ments of the incoming paypal request and record it along with the blob.
		This does not verify anything. Just parse and record.
		Returns: the last insert id of mysql. Caller should use this to invoke processPaypalTransaction method . That methods needs it as handle.
	*/

	public static function recordIPNIncoming($post) {
		try {
			$product = '';
			
			if (isset($post['item_name']) && $post['item_name'] != '') {
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
				logToFile("Dap_IPNProcessor::recordIncoming(): Key=".$key.", Value=".$value); 
			}
			
			logToFile("record transaction:".$req, LOG_INFO_DAP);
			//lets do the insert and get the newly created  id out.
			$transaction = new Dap_Transactions();
			
			if($post['userId'] != "") {
			  $transaction->setUser_id($post['userId']);
			  logToFile("Dap_IPNProcessor::recordIncoming(): USERID=" . $post['userId'] . " email = " . $post['ccustemail']);
			}
			else {
			  logToFile("Dap_IPNProcessor::recordIncoming():  USERID NOT FOUND=" . $post['userId']);	
			}
			
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
			logToFile("Dap_IPNProcessor::recordIncoming(): Insert into transactions Done. Returning transaction id: " . $transaction->getId()); 
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
