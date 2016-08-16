<?php

class Dap_AffReferrals extends Dap_Base {
   	var $id;
   	var $user_id;
   	var $product_id;
   	var $affiliate_id;
   	var $referral_date;
	
	function getId() {
		return $this->id;
	}
	function setId($o) {
		$this->id = $o;
	}

	function getUser_id() {
	        return $this->user_id;
	}
	function setUser_id($o) {
		$this->user_id = $o;
	}
	
	function getProduct_id() {
	        return $this->product_id;
	}
	function setProduct_id($o) {
		$this->product_id = $o;
	}
	
	function getAffiliate_id() {
		return $this->affiliate_id;
	}
	function setAffiliate_id($o) {
		$this->affiliate_id = $o;
	}

	function getReferral_date() {
		return $this->referral_date;
	}
	function setReferral_date($o) {
		$this->referral_date = $o;
	}

	
	
	//Returns all products (as an array) for this user that do not have an affiliate referral specified
	public static function getProductsPendingAffiliateStamping($user_id) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
		
			$sql = "SELECT 
						upj.product_id 
					FROM 
						dap_users_products_jn upj
					where
						upj.user_id = :user_id and
						upj.product_id 
						not in
						(
							select
								ar.product_id 
							from
								dap_aff_referrals ar 
							where
								ar.user_id = :user_id
						)
						and
						upj.transaction_id not in ('-2','-3') 
					";
	
			logToFile("getProductsPendingAffiliateStamping: $sql",LOG_DEBUG_DAP); 
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
			$stmt->execute();	
	
			$ProductList = array();
			while ($row = $stmt->fetch()) {
				logToFile("pending: " . $row["product_id"],LOG_DEBUG_DAP);
				$ProductList[] = $row["product_id"];
			}
			
			return $ProductList;
			
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_DEBUG_DAP);
			return $e->getMessage();
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_DEBUG_DAP);
			return $e->getMessage();
		}
	}
		

	public static function processAffiliation($affiliate_id, $user_id, $ProductListArray) {
		try {
			foreach ($ProductListArray as $product_id) {
				logToFile("Affiliate Id: $affiliate_id, User id: $user_id, Product id: $product_id",LOG_DEBUG_DAP);
				$dap_dbh = Dap_Connection::getConnection();
				//START TRANSACTION
				$dap_dbh->beginTransaction();
				
				//2. Stamp all active products with this affiliate id in dap_aff_referrals, insert referral date
				//Note last_insert_id.
				
				$sql = "insert into 
							dap_aff_referrals
							(user_id, affiliate_id, product_id, referral_date)
						values
							(:user_id, :affiliate_id, :product_id, :referral_date)
						";
				//logToFile("Dap_AffReferrals:processAffiliation(): $sql",LOG_DEBUG_DAP);
		
				$stmt = $dap_dbh->prepare($sql);
				$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
				$stmt->bindParam(':affiliate_id', $affiliate_id, PDO::PARAM_INT);
				$stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
				$stmt->bindParam(':referral_date', date("Y-m-d H:i:s"), PDO::PARAM_STR);
				$stmt->execute();
				$last_insert_id = $dap_dbh->lastInsertId();
				//logToFile("Last insert id: $last_insert_id",LOG_DEBUG_DAP);
				$stmt = null;
					
				//3. See if aff_id has an override row in dap_aff_comm. If not, pick up the product-wide row.
				$affCommission = Dap_AffCommissions::load($affiliate_id, $product_id);
				
				//logToFile("Aff commission: " . $affCommission->getPer_lead_fixed(),LOG_DEBUG_DAP);
				
				/*4. Pick up only the Per-Lead value, if set, and insert that amount,
					along with last_insert_id (aff_referrals_id) into dap_aff_transactions
					Mark earning_type = 'L' (for Lead).*/
				//if( ($affCommission != null) && ($affCommission->getPer_lead_fixed() != "") ){
				if($affCommission != null){
					//logToFile("affCommission is not null"); 
					$datetime = date("Y-m-d H:i:s");
					
					if (isset($_SESSION['adaptivePay'])) {
						$earning_type  = "A"; // adaptive
						$aff_exports_id = -1;
					  	$sql = "insert into 
								dap_aff_earnings
								(aff_referrals_id, amount_earned, datetime, earning_type, aff_exports_id)
							values
								(:aff_referrals_id, :amount_earned, :datetime, :earning_type, :aff_exports_id)
							";
							//logToFile("Dap_AffReferrals:processAffiliation(): $sql",LOG_DEBUG_DAP);
					  	$stmt = $dap_dbh->prepare($sql);
					  	$stmt->bindParam(':aff_referrals_id', $last_insert_id, PDO::PARAM_INT);
					  	$stmt->bindParam(':amount_earned', $affCommission->getPer_lead_fixed(), PDO::PARAM_STR);
					  	$stmt->bindParam(':datetime', $datetime, PDO::PARAM_STR);
					  	$stmt->bindParam(':earning_type', $earning_type, PDO::PARAM_STR);
					  	$stmt->bindParam(':aff_exports_id', $aff_exports_id, PDO::PARAM_INT);
					  	$stmt->execute();
					}
					else {
						//logToFile("in DAP_AffReferrals: affCommission IS null"); 
						$markTransactionAsAffProcessed = false;
						
						logToFile("Earnings type = L"); 
						$earning_type  = "L";
						$sql = "insert into 
							dap_aff_earnings
							(aff_referrals_id, amount_earned, datetime, earning_type)
							values
							(:aff_referrals_id, :amount_earned, :datetime, :earning_type)
						";
						//logToFile("Dap_AffReferrals:processAffiliation(): $sql",LOG_DEBUG_DAP);
						$stmt = $dap_dbh->prepare($sql);
						$stmt->bindParam(':aff_referrals_id', $last_insert_id, PDO::PARAM_INT);
						$stmt->bindParam(':amount_earned', $affCommission->getPer_lead_fixed(), PDO::PARAM_STR);
						$stmt->bindParam(':datetime', $datetime, PDO::PARAM_STR);
						$stmt->bindParam(':earning_type', $earning_type, PDO::PARAM_STR);
						$stmt->execute();
						
						$sendAffEmailLead = Dap_Config::get("SEND_AFF_EMAIL_LEAD");
						logToFile("sendAffEmailLead: $sendAffEmailLead"); 
						if ($sendAffEmailLead == "Y") {
							logToFile("sendAffEmailLead = Y"); 
							sendAffiliateNotificationEmail($affiliate_id, $product_id, $user_id, "L", $affCommission->getPer_lead_fixed());
						}
						
						if( $affCommission->getPer_lead_fixed() > 0.00 ) {
							$markTransactionAsAffProcessed = true;
						}
						
						if( ($affCommission->getPer_lead_fixed_credits() != "") && ($affCommission->getPer_lead_fixed_credits() != 0) ) {
							$markTransactionAsAffProcessed = true;
							
							//Now process per-lead credits
							logToFile("Earnings type = C"); 
							$earning_type  = "C";
							$sql = "insert into 
								dap_aff_earnings
								(aff_referrals_id, amount_earned, datetime, earning_type)
								values
								(:aff_referrals_id, :amount_earned, :datetime, :earning_type)
							";
							//logToFile("Dap_AffReferrals:processAffiliation(): $sql",LOG_DEBUG_DAP);
							$stmt = $dap_dbh->prepare($sql);
							$stmt->bindParam(':aff_referrals_id', $last_insert_id, PDO::PARAM_INT);
							$stmt->bindParam(':amount_earned', $affCommission->getPer_lead_fixed_credits(), PDO::PARAM_STR);
							$stmt->bindParam(':datetime', $datetime, PDO::PARAM_STR);
							$stmt->bindParam(':earning_type', $earning_type, PDO::PARAM_STR);
							$stmt->execute();
						}
						
						/**
						if($markTransactionAsAffProcessed == true) {
							//make sure transaction is set to 7 so that 
							//both lead and sale are not both processed for the same lead/sale
							$sql = "select
										t.id as transaction_id
									from
										dap_aff_referrals ar,
										dap_transactions t,
										dap_users u
									where
										ar.id = :aff_referrals_id and
										ar.user_id = :user_id and
										ar.affiliate_id = :affiliate_id and
										ar.product_id = :product_id and
										ar.user_id = u.id and
										u.email = t.payer_email";
										
							$stmt = $dap_dbh->prepare($sql);
							$stmt->bindParam(':aff_referrals_id', $last_insert_id, PDO::PARAM_INT);
							$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
							$stmt->bindParam(':affiliate_id', $affiliate_id, PDO::PARAM_INT);
							$stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
							$stmt->execute();	
							
							while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
								$transaction_id = $row["transaction_id"];
								logToFile("about to mark $transaction_id as 7");
								Dap_Transactions::setRecordStatus($transaction_id, 7);
							}
						}
						*/
						
					}
							
				}
				
				//END TRANSACTION
				$dap_dbh->commit(); //commit the transaction
				$stmt = null;
				$dap_dbh = null;
			} //end foreach product
		
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_DEBUG_DAP);
			return $e->getMessage();
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_DEBUG_DAP);
			return $e->getMessage();
		}
	}

	

	public static function processAffiliationMultiTier($affiliate_id, $user_id, $product_id, $transId, $transValue, $tier, $earning_type) {
		try {
			logToFile("Affiliate Id: $affiliate_id, User id: $user_id, Product id: $product_id",LOG_DEBUG_DAP);
			$dap_dbh = Dap_Connection::getConnection();
			//START TRANSACTION
			$dap_dbh->beginTransaction();
			
			//2. Stamp all active products with this affiliate id in dap_aff_referrals, insert referral date
			//Note last_insert_id.
			
			$sql = "insert into 
						dap_aff_referrals
						(user_id, affiliate_id, product_id, tier, referral_date)
					values
						(:user_id, :affiliate_id, :product_id, :tier, :referral_date)
					";
			//logToFile("Dap_AffReferrals:processAffiliation(): $sql",LOG_DEBUG_DAP);
	
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
			$stmt->bindParam(':affiliate_id', $affiliate_id, PDO::PARAM_INT);
			$stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
			$stmt->bindParam(':tier', $tier, PDO::PARAM_INT);
			$stmt->bindParam(':referral_date', date("Y-m-d H:i:s"), PDO::PARAM_STR);
			$stmt->execute();
			$last_insert_id = $dap_dbh->lastInsertId();
			logToFile("Last insert id: $last_insert_id",LOG_DEBUG_DAP);
			$stmt = null;
				
			//3. See if aff_id has an override row in dap_aff_comm. If not, pick up the product-wide row.
			$dap_aff_comm = Dap_AffCommissions::load($affiliate_id, $product_id, $tier);
			if( !isset($dap_aff_comm) || ($dap_aff_comm == null) ) {
				logToFile("dap_aff_comm is null"); 
				$dap_dbh->rollback();
				$stmt = null;
				$dap_dbh = null;
				return 0;
			}

			
			$sale_commission = $dap_aff_comm->calculateSaleCommission($transValue);
			
			//logToFile("Aff commission: " . $affCommission->getPer_lead_fixed(),LOG_DEBUG_DAP);
			if( $sale_commission >= 0.00 ){
				$sql = "insert into 
							dap_aff_earnings
							(aff_referrals_id, amount_earned, transaction_id, datetime, earning_type)
						values
							(:aff_referrals_id, :amount_earned, :transaction_id, :datetime, :earning_type)
						";
				$datetime = date("Y-m-d H:i:s");
				//$earning_type  = "L";
				
				//logToFile("Dap_AffReferrals:processAffiliation(): $sql",LOG_DEBUG_DAP);
				$stmt = $dap_dbh->prepare($sql);
				$stmt->bindParam(':aff_referrals_id', $last_insert_id, PDO::PARAM_INT);
				$stmt->bindParam(':amount_earned', $sale_commission, PDO::PARAM_STR);
				$stmt->bindParam(':transaction_id', $transId, PDO::PARAM_STR);
				$stmt->bindParam(':datetime', $datetime, PDO::PARAM_STR);
				$stmt->bindParam(':earning_type', $earning_type, PDO::PARAM_STR);
				$stmt->execute();
			}
			
			//END TRANSACTION
			$dap_dbh->commit(); //commit the transaction
			$stmt = null;
			$dap_dbh = null;
			return $last_insert_id;
		
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_DEBUG_DAP);
			$dap_dbh->rollback();
			$stmt = null;
			$dap_dbh = null;
			return $e->getMessage();
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_DEBUG_DAP);
			$dap_dbh->rollback();
			$stmt = null;
			$dap_dbh = null;
			return $e->getMessage();
		}
	}



	public static function removeAffiliate($userId, $oldAffId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			//START TRANSACTION
			$dap_dbh->beginTransaction();
			
			$sql = "delete from 
						dap_aff_referrals
					where
						user_id = :userId and
						affiliate_id = :oldAffId
					";
			//logToFile("Dap_AffReferrals:removeAffiliate(): $sql",LOG_DEBUG_DAP);
	
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->bindParam(':oldAffId', $oldAffId, PDO::PARAM_INT);
			$stmt->execute();
			
			//END TRANSACTION
			$dap_dbh->commit(); //commit the transaction
			$stmt = null;
			$dap_dbh = null;
			return "User $userId has been detached from Affiliate $oldAffId";
		
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_DEBUG_DAP);
			$dap_dbh->rollback();
			$stmt = null;
			$dap_dbh = null;
			return $e->getMessage();
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_DEBUG_DAP);
			$dap_dbh->rollback();
			$stmt = null;
			$dap_dbh = null;
			return $e->getMessage();
		}
	}


	public static function replaceAffiliate($userId, $oldAffId, $newAffId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			//START TRANSACTION
			$dap_dbh->beginTransaction();
			
			$sql = "update
						dap_aff_referrals
					set
						affiliate_id = :newAffId
					where
						user_id = :userId and
						affiliate_id = :oldAffId
					";
			//logToFile("Dap_AffReferrals:removeAffiliate(): $sql",LOG_DEBUG_DAP);
	
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->bindParam(':newAffId', $newAffId, PDO::PARAM_INT);
			$stmt->bindParam(':oldAffId', $oldAffId, PDO::PARAM_INT);
			$stmt->execute();
			
			//END TRANSACTION
			$dap_dbh->commit(); //commit the transaction
			$stmt = null;
			$dap_dbh = null;
			return "User $userId has been detached to new Affiliate $newAffId";
		
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_DEBUG_DAP);
			$dap_dbh->rollback();
			$stmt = null;
			$dap_dbh = null;
			return $e->getMessage();
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_DEBUG_DAP);
			$dap_dbh->rollback();
			$stmt = null;
			$dap_dbh = null;
			return $e->getMessage();
		}
	}

}	
