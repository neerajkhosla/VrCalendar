<?php

class Dap_AffCommissions extends Dap_Base {
   	var $id;
	//affiliate  id
   	var $user_id;
   	var $product_id;
   	var $per_lead_fixed;
   	var $per_sale_fixed;
	var $per_sale_percentage;

   	var $per_lead_fixed_credits;
   	var $per_sale_fixed_credits;
	var $per_sale_percentage_credits;

	var $is_comm_recurring;
	var $tier;
	
	var $email;
	var $product_name;
	
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
	
	function getPer_lead_fixed() {
	        return $this->per_lead_fixed;
	}
	function setPer_lead_fixed($o) {
		$this->per_lead_fixed = $o;
	}
	
	function getPer_sale_fixed() {
	        return $this->per_sale_fixed;
	}
	function setPer_sale_fixed($o) {
		$this->per_sale_fixed = $o;
	}

	function getPer_sale_percentage() {
		return $this->per_sale_percentage;
	}
	function setPer_sale_percentage($o) {
		$this->per_sale_percentage = $o;
	}
	



	function getPer_lead_fixed_credits() {
	        return $this->per_lead_fixed_credits;
	}
	function setPer_lead_fixed_credits($o) {
		$this->per_lead_fixed_credits = $o;
	}
	
	function getPer_sale_fixed_credits() {
	        return $this->per_sale_fixed_credits;
	}
	function setPer_sale_fixed_credits($o) {
		$this->per_sale_fixed_credits = $o;
	}

	function getPer_sale_percentage_credits() {
		return $this->per_sale_percentage_credits;
	}
	function setPer_sale_percentage_credits($o) {
		$this->per_sale_percentage_credits = $o;
	}


	function getIs_comm_recurring() {
		return $this->is_comm_recurring;
	}
	function setIs_comm_recurring($o) {
		$this->is_comm_recurring = $o;
	}
	
	function getTier() {
		return $this->tier;
	}
	function setTier($o) {
		$this->tier = $o;
	}
	
	

	function getEmail() {
	    return $this->email;
	}
	function setEmail($o) {
		$this->email = $o;
	}
	
	function getProduct_name() {
	    return $this->product_name;
	}
	function setProduct_name($o) {
		$this->product_name = $o;
	}
	
	
	function isSaleCommission() {
		logToFile("isSaleCommission() : Per Sale: $per_sale_fixed ,  Per Sale Percentage: $per_sale_percentage");
		if($this->per_sale_fixed > 0 || $this->per_sale_percentage > 0) {
			return true;
		}
		return false;
	}

	function isSaleCommissionCredits() {
		logToFile("isSaleCommissionCredits() : Per Sale Credits: $per_sale_fixed_credits ,  Per Sale Percentage Credits: $per_sale_percentage_credits");
		if($this->per_sale_fixed_credits > 0 || $this->per_sale_percentage_credits > 0) {
			return true;
		}
		return false;
	}
	
	function calculateSaleCommission($sale_value) {
		$retval = 0;
		if($this->isSaleCommission() === false) {
			return $retval;
		}
		$retval = $this->per_sale_fixed;
		$retval = $retval + ($sale_value * ($this->per_sale_percentage / 100));
		return $retval;
	}

	function calculateSaleCommissionCredits($sale_value) {
		$retval = 0;
		if($this->isSaleCommissionCredits() === false) {
			return $retval;
		}
		$retval = $this->per_sale_fixed_credits;
		$retval = $retval + ($sale_value * ($this->per_sale_percentage_credits / 100));
		return intval($retval);
	}


	public static function load($user_id, $product_id, $tier=1) {
		try {
			logToFile("in Dap_AffCommissions"); 
			logToFile("affiliate id: " . $user_id . ", product_id: " . $product_id . ", tier: " . $tier); 
			$affcommission = null;
			$dap_dbh = Dap_Connection::getConnection();
			$sql = "select 
						*
					from 
						dap_aff_comm
					where 
						product_id = :product_id and
						user_id in (:user_id, 0) and
						tier = :tier
					order by 
						user_id desc
					limit 1
					";
			//logToFile("Dap_AffCommissions.load() - SQL:".$sql);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
			$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
			$stmt->bindParam(':tier', $tier, PDO::PARAM_INT);
			$stmt->execute();
			
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$affcommission = new Dap_AffCommissions();
				$affcommission->setId( $row["id"] );
				$affcommission->setUser_id( $row["user_id"] );
				$affcommission->setProduct_id( $row["product_id"] );
				$affcommission->setPer_lead_fixed( $row["per_lead_fixed"] );
				$affcommission->setPer_sale_fixed( $row["per_sale_fixed"] );
				$affcommission->setPer_sale_percentage( $row["per_sale_percentage"] );

				$affcommission->setPer_lead_fixed_credits( $row["per_lead_fixed_credits"] );
				$affcommission->setPer_sale_fixed_credits( $row["per_sale_fixed_credits"] );
				$affcommission->setPer_sale_percentage_credits( $row["per_sale_percentage_credits"] );

				$affcommission->setIs_comm_recurring( $row["is_comm_recurring"] );
				$affcommission->setTier( $row["tier"] );
				
				/**
				logToFile('row["id"]: ' . $row["id"]); 
				logToFile('row["user_id"]: ' . $row["user_id"]); 
				logToFile('row["product_id"]: ' . $row["product_id"]); 
				logToFile('row["per_lead_fixed"]: ' . $row["per_lead_fixed"]); 
				logToFile('row["per_sale_fixed"]: ' . $row["per_sale_fixed"]); 
				logToFile('row["per_sale_percentage"]: ' . $row["per_sale_percentage"]); 
				*/
			}

			return $affcommission;

		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}			
		logToFile("Dap_AffCommissions.load() - Returning NULL AffCommission");
		return NULL;
	}


	public static function loadAllCommissions($user_id, $product_id) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			if( ($user_id==0) && ($product_id==0) ) {
				//All users, all products
				$sql = "select 
							ac.id,
							ac.user_id,
							ac.product_id,
							ac.per_lead_fixed,
							ac.per_sale_fixed,
							ac.per_sale_percentage,
							ac.per_lead_fixed_credits,
							ac.per_sale_fixed_credits,
							ac.per_sale_percentage_credits,
							ac.is_comm_recurring,
							ac.tier,
							u.email,
							p.name
						from 
							(dap_aff_comm ac LEFT JOIN dap_users u on ac.user_id = u.id), 
							dap_products p
						where
							ac.product_id = p.id
						order by 
							product_id asc, user_id asc, tier asc
						";
				$stmt = $dap_dbh->prepare($sql);
			}
			if( ($user_id==0) && ($product_id!=0) ) {
				//All users, specific products
				$sql = "select 
							ac.id,
							ac.user_id,
							ac.product_id,
							ac.per_lead_fixed,
							ac.per_sale_fixed,
							ac.per_sale_percentage,
							ac.per_lead_fixed_credits,
							ac.per_sale_fixed_credits,
							ac.per_sale_percentage_credits,
							ac.is_comm_recurring,
							ac.tier,
							u.email,
							p.name
						from 
							(dap_aff_comm ac LEFT JOIN dap_users u on ac.user_id = u.id), 
							dap_products p
						where
							product_id = :product_id and
							ac.product_id = p.id
						order by 
							product_id asc, user_id asc, tier asc
						";
				$stmt = $dap_dbh->prepare($sql);
				$stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
			}
			if( ($user_id!=0) && ($product_id==0) ) {
				//Specific user, all products
				$sql = "select 
							ac.id,
							ac.user_id,
							ac.product_id,
							ac.per_lead_fixed,
							ac.per_sale_fixed,
							ac.per_sale_percentage,
							ac.per_lead_fixed_credits,
							ac.per_sale_fixed_credits,
							ac.per_sale_percentage_credits,
							ac.is_comm_recurring,
							ac.tier,
							u.email,
							p.name
						from 
							dap_aff_comm ac, 
							dap_users u,
							dap_products p
						where
							ac.user_id = :user_id and
							ac.user_id = u.id and
							ac.product_id = p.id
						order by 
							ac.product_id asc, ac.user_id asc, tier asc
						";
						//logToFile("here: $sql",LOG_DEBUG_DAP);
				$stmt = $dap_dbh->prepare($sql);
				$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
			}
			
			//logToFile($sql,LOG_DEBUG_DAP);
			$stmt->execute();
			$affCommissionsArray = array();
			
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$affcommission = new Dap_AffCommissions();
				$affcommission->setId( $row["id"] );
				$affcommission->setUser_id( $row["user_id"] );
				$affcommission->setProduct_id( $row["product_id"] );
				$affcommission->setPer_lead_fixed( $row["per_lead_fixed"] );
				$affcommission->setPer_sale_fixed( $row["per_sale_fixed"] );
				$affcommission->setPer_sale_percentage( $row["per_sale_percentage"] );
				
				$affcommission->setPer_lead_fixed_credits( $row["per_lead_fixed_credits"] );
				$affcommission->setPer_sale_fixed_credits( $row["per_sale_fixed_credits"] );
				$affcommission->setPer_sale_percentage_credits( $row["per_sale_percentage_credits"] );
				
				$affcommission->setIs_comm_recurring( $row["is_comm_recurring"] );
				
				if( ($row["tier"] == "") || ($row["tier"] == "0") ) {
					$row["tier"] = "1";
				}
				
				$affcommission->setTier( $row["tier"] );
				$affcommission->setEmail( $row["email"] );
				$affcommission->setProduct_name( $row["name"] );
				
				$affCommissionsArray[] = $affcommission;
			}
			
			return $affCommissionsArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}			
	}

		
	public static function deleteCommission($id) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$sql = "delete 
					from dap_aff_comm
					where 
					id = :id
					";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}			
	}
	
	/**
		Create a negated sale earning using a given original trans id and new trans id.
		Now also using original transaction's time.
		Basically query by original trans id and copy that record over
		with backdating (used to be current date until 06/06/11, negated amount (0-amount) and new trans id.
		Needed to be changed to backdating aff earnings, because otherwise it won't be considered
		when making affiliate payment if date of refund falls outside of payment window
	*/
	
	//NOTE: MAKE SURE THERE ARE NOT TWO SALE COMMISSIONS FOR A SINGLE TRANSACTION.
	public static function negateSaleEarning($orig_trans_id, $new_trans_id, $dap_dbh=null) {
		try {
			if(!isset($dap_dbh) || ($dap_dbh == null)) {
				$dap_dbh = Dap_Connection::getConnection();
			}
			$sql = "SELECT 
						aff_referrals_id, (0-amount_earned) as amount_earned, datetime, earning_type
					from 
						dap_aff_earnings
					where 
						transaction_id = :orig_id";
					//and earning_type = 'S'";
					
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':orig_id', $orig_trans_id, PDO::PARAM_INT);
			//$stmt->bindParam(':new_id', $new_trans_id, PDO::PARAM_INT);
			$stmt->execute();
			
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$aff_referrals_id = $row["aff_referrals_id"];
				$amount_earned = $row["amount_earned"];
				$datetime = $row["datetime"];
				//$transaction_id = $new_trans_id;
				$earning_type = $row["earning_type"];
			
				$sql2 = "INSERT INTO dap_aff_earnings
						(aff_referrals_id, amount_earned, datetime, transaction_id, earning_type) 
						values
						(:aff_referrals_id, :amount_earned, :datetime, :transaction_id, :earning_type)
						";
				$stmt2 = $dap_dbh->prepare($sql2);
				$stmt2->bindParam(':aff_referrals_id', $aff_referrals_id, PDO::PARAM_INT);
				$stmt2->bindParam(':amount_earned', $amount_earned, PDO::PARAM_STR);
				$stmt2->bindParam(':datetime', $datetime, PDO::PARAM_STR);
				$stmt2->bindParam(':transaction_id', $new_trans_id, PDO::PARAM_INT);
				$stmt2->bindParam(':earning_type', $earning_type, PDO::PARAM_STR);
				$stmt2->execute();
				$stmt2 = null;
			}
			
			$stmt = null;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}	
	}	

	public static function updateCommission($id, $per_lead_fixed, $per_sale_fixed, $per_sale_percentage, $per_lead_fixed_credits,$per_sale_fixed_credits,$per_sale_percentage_credits, $is_comm_recurring, $tier) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$sql = "update dap_aff_comm
					set
						per_lead_fixed = :per_lead_fixed,
						per_sale_fixed = :per_sale_fixed,
						per_sale_percentage = :per_sale_percentage,
						per_lead_fixed_credits = :per_lead_fixed_credits,
						per_sale_fixed_credits = :per_sale_fixed_credits,
						per_sale_percentage_credits = :per_sale_percentage_credits,
						is_comm_recurring = :is_comm_recurring,
						tier = :tier
					where
						id = :id
					";
			//logToFile($sql,LOG_DEBUG_DAP);
			logToFile("$id,$per_lead_fixed,$per_sale_fixed,$per_sale_percentage,$is_comm_recurring",LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->bindParam(':per_lead_fixed', $per_lead_fixed, PDO::PARAM_INT);
			$stmt->bindParam(':per_sale_fixed', $per_sale_fixed, PDO::PARAM_INT);
			$stmt->bindParam(':per_sale_percentage', $per_sale_percentage, PDO::PARAM_INT);
			$stmt->bindParam(':per_lead_fixed_credits', $per_lead_fixed_credits, PDO::PARAM_INT);
			$stmt->bindParam(':per_sale_fixed_credits', $per_sale_fixed_credits, PDO::PARAM_INT);
			$stmt->bindParam(':per_sale_percentage_credits', $per_sale_percentage_credits, PDO::PARAM_INT);
			$stmt->bindParam(':is_comm_recurring', $is_comm_recurring, PDO::PARAM_STR);
			$stmt->bindParam(':tier', $tier, PDO::PARAM_INT);
			$stmt->execute();
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}			
	}

	function addCommission($userId,$productId,$per_lead_fixed,$per_sale_fixed,$per_sale_percentage,$per_lead_fixed_credits,$per_sale_fixed_credits,$per_sale_percentage_credits,$is_comm_recurring,$tier) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$sql = "insert into dap_aff_comm
					(user_id, product_id, per_lead_fixed, per_sale_fixed, per_sale_percentage, per_lead_fixed_credits, per_sale_fixed_credits, per_sale_percentage_credits, is_comm_recurring, tier)
					values
					(:user_id, :product_id, :per_lead_fixed, :per_sale_fixed, :per_sale_percentage, :per_lead_fixed_credits, :per_sale_fixed_credits, :per_sale_percentage_credits, :is_comm_recurring, :tier)
					";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
			$stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
			$stmt->bindParam(':per_lead_fixed', $per_lead_fixed, PDO::PARAM_INT);
			$stmt->bindParam(':per_sale_fixed', $per_sale_fixed, PDO::PARAM_INT);
			$stmt->bindParam(':per_sale_percentage', $per_sale_percentage, PDO::PARAM_INT);
			
			$stmt->bindParam(':per_lead_fixed_credits', $per_lead_fixed_credits, PDO::PARAM_INT);
			$stmt->bindParam(':per_sale_fixed_credits', $per_sale_fixed_credits, PDO::PARAM_INT);
			$stmt->bindParam(':per_sale_percentage_credits', $per_sale_percentage_credits, PDO::PARAM_INT);
			
			$stmt->bindParam(':is_comm_recurring', $is_comm_recurring, PDO::PARAM_STR);
			$stmt->bindParam(':tier', $tier, PDO::PARAM_INT);
			$stmt->execute();
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}			
	}

	public static function loadAffiliateEarningsSummary($email, $start_date, $end_date) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			if($start_date == "") $start_date = date("m-d-Y");
			if($end_date == "") $end_date = date("m-d-Y");
			$stmt = null;
			$sql = "";
			
			//logToFile("Email: " . $email . " - , " . time() . ", " . $start_date." ".$end_date,LOG_DEBUG_DAP);
			if($email=="") {
				$sql = "
						SELECT 
							ar.affiliate_id,
							u.first_name,
							u.last_name,
							u.email,
							sum(ae.amount_earned) as amt_earned
						FROM
							dap_aff_referrals ar,
							dap_aff_earnings ae,
							dap_users u
						WHERE
							ar.affiliate_id in (select affiliate_id from dap_aff_referrals) and
							ar.id = ae.aff_referrals_id and
							DATE(ae.datetime) between str_to_date(:start_date, '%m-%d-%Y') and str_to_date(:end_date, '%m-%d-%Y') and
							ar.affiliate_id = u.id
						GROUP BY
							ar.affiliate_id
						";
				$stmt = $dap_dbh->prepare($sql);
				$stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
				$stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
			} else if ($email != "") {
				$sql = "
						SELECT 
							ar.affiliate_id,
							u.first_name,
							u.last_name,
							u.email,
							sum(ae.amount_earned) as amt_earned
						FROM 
							dap_aff_referrals ar,
							dap_aff_earnings ae,
							dap_users u
						WHERE
							ar.id = ae.aff_referrals_id and
							ar.affiliate_id = u.id and
							u.email like :email and
							DATE(ae.datetime) between str_to_date(:start_date, '%m-%d-%Y') and str_to_date(:end_date, '%m-%d-%Y')
						GROUP BY
							ar.affiliate_id
						";
				$emailPercentage = "%".$email."%";
				$stmt = $dap_dbh->prepare($sql);
				$stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
				$stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
				$stmt->bindParam(':email', $emailPercentage, PDO::PARAM_STR);
			}
			//logToFile($sql);
			$stmt->execute();
			$resultsArray = array();
			
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$resultsArray[] = $row;
			}
			
			return $resultsArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}			
	}

	public static function loadAffiliateEarningsDetails($email, $start_date, $end_date, $earningTypes="'L','S','C','T2','T3'") {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			if($start_date == "") $start_date = date("m-d-Y");
			if($end_date == "") $end_date = date("m-d-Y");
			$earningTypes = stripslashes($earningTypes);
			logToFile("Start/end/earningTypes: $start_date / $end_date / $earningTypes",LOG_DEBUG_DAP);
			$session = Dap_Session::getSession();

			if($email=="") {
				logToFile("1"); 
				$sql = "select
							ar.id,
							ar.affiliate_id,
							ar.user_id,
							ar.product_id,
							ar.referral_date,
							u.first_name,
							u.last_name,
							u.email,
							ae.amount_earned,
							ae.datetime,
							ae.transaction_id,
							ae.earning_type,
							ae.aff_exports_id,
							p.name as product_name
						from
							dap_aff_referrals ar,
							dap_aff_earnings ae,
							dap_users u,
							dap_products p
						where
							ar.id = ae.aff_referrals_id	and
							ae.earning_type in ($earningTypes) and
							ar.affiliate_id = u.id and
							DATE(ae.datetime) between str_to_date(:start_date, '%m-%d-%Y') and str_to_date(:end_date, '%m-%d-%Y') and
							ar.product_id = p.id
						order by 
							ar.referral_date desc, ar.id desc
							";
				$stmt = $dap_dbh->prepare($sql);
				$stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
				$stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
			} else if ($email != "") {
				$sql = "";
				//if( isset($session) && Dap_Session::isLoggedIn() && $session->isAdmin() ) {
				logToFile("3"); 
				logToFile("email of affiliate in user affilate page: $email");
				$sql = "select
							ar.id,
							ar.affiliate_id,
							ar.user_id,
							ar.product_id,
							ar.referral_date,
							u.first_name,
							u.last_name,
							u.email,
							ae.amount_earned,
							ae.datetime,
							ae.transaction_id,
							ae.earning_type,
							ae.aff_exports_id,
							p.name as product_name,
							t.trans_num,
							uu.first_name as first_name_buyer,
							uu.last_name as last_name_buyer,
							uu.email as email_buyer,
							uu.phone as phone_buyer
						from
							dap_aff_referrals ar,
							dap_aff_earnings ae,
							dap_users u,
							dap_users uu,
							dap_products p,
							dap_transactions t
						where
							ar.id = ae.aff_referrals_id	and
							ar.affiliate_id = u.id and
							u.email like :email and
							DATE(ae.datetime) between str_to_date(:start_date, '%m-%d-%Y') and str_to_date(:end_date, '%m-%d-%Y') and
							ar.product_id = p.id and
							ae.transaction_id = t.id and
							ae.earning_type in ($earningTypes) and
							t.payer_email = uu.email
						order by 
							ar.referral_date desc, ar.id desc
							";
				
				$emailPercentage = "%".$email."%";
				$stmt = $dap_dbh->prepare($sql);
				$stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
				$stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
				$stmt->bindParam(':email', $emailPercentage, PDO::PARAM_STR);
			}
			//logToFile($sql);
			$stmt->execute();
			$resultsArray = array();
			
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				//logToFile("Amount earnied: " . $row["amount_earned"]);
				//logToFile("Old referral date: " . $row["referral_date"]); 
				//$transaction = Dap_Transactions::load($row["transaction_id"]);
				//$row["refund_date"] = $transaction->getTime();
				//logToFile("NEW referral date: " . $row["referral_date"]);
				//$row["trans_num"] = $transaction->getTrans_num();
				//logToFile("---------------"); 
				
				$row["refund_date"] = $row["datetime"];
				$resultsArray[] = $row;
			}
			
			
			$sql = "";
			$stmt = null;
			$row = null;
			
			//Load leads
			if ($email != "") {
				logToFile("4"); 
				$sql = "select
							ar.id,
							ar.affiliate_id,
							ar.user_id,
							ar.product_id,
							ar.referral_date,
							u.first_name,
							u.last_name,
							u.email,
							ae.amount_earned,
							ae.datetime,
							ae.transaction_id,
							ae.earning_type,
							ae.aff_exports_id,
							p.name as product_name,
							uu.first_name as first_name_buyer,
							uu.last_name as last_name_buyer,
							uu.email as email_buyer,
							uu.phone as phone_buyer
						from
							dap_aff_referrals ar,
							dap_aff_earnings ae,
							dap_users u,
							dap_users uu,
							dap_products p
						where
							ar.id = ae.aff_referrals_id	and
							ar.affiliate_id = u.id and
							ar.user_id = uu.id and
							u.email like :email and
							DATE(ae.datetime) between str_to_date(:start_date, '%m-%d-%Y') and str_to_date(:end_date, '%m-%d-%Y') and
							ar.product_id = p.id and
							ae.transaction_id = 0 and
							ae.earning_type in ($earningTypes)
						order by 
							ar.referral_date desc, ar.id desc
							";
				$emailPercentage = "%".$email."%";
				$stmt = $dap_dbh->prepare($sql);
				$stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
				$stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
				$stmt->bindParam(':email', $emailPercentage, PDO::PARAM_STR);
				
				//logToFile($sql);
				$stmt->execute();
				
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					$resultsArray[] = $row;
				}
			}
			
			
			//logToFileArray($resultsArray,LOG_DEBUG_DAP);
			return $resultsArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}			
	}


	public static function loadAffiliatePayments($email, $start_date, $end_date) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			if($start_date == "") $start_date = date("m-d-Y");
			if($end_date == "") $end_date = date("m-d-Y");
			
			//logToFile("(loadAffiliatePayments) email: $email, start_date: $start_date, end_date: $end_date",LOG_DEBUG_DAP);
			
			if($email=="") {
				$sql = "SELECT 
							ap.affiliate_id,
							ap.amount_paid,
							ap.datetime,
							ap.comments,
							ap.earning_type,
							u.first_name,
							u.last_name,
							u.email
						FROM 
							dap_aff_payments ap,
							dap_users u
						WHERE 
							ap.affiliate_id in
							(select affiliate_id from dap_aff_payments) and
							ap.affiliate_id = u.id and
							DATE(datetime) between str_to_date(:start_date, '%m-%d-%Y') and str_to_date(:end_date, '%m-%d-%Y')
							";
				$stmt = $dap_dbh->prepare($sql);
				$stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
				$stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
			} else if ($email != "") {
				$sql = "SELECT 
							ap.affiliate_id,
							ap.amount_paid,
							ap.datetime,
							ap.comments,
							ap.earning_type,
							u.first_name,
							u.last_name,
							u.email
						FROM 
							dap_aff_payments ap,
							dap_users u
						WHERE 
							ap.affiliate_id = u.id and
							u.email like :email and
							DATE(datetime) between str_to_date(:start_date, '%m-%d-%Y') and str_to_date(:end_date, '%m-%d-%Y')
							";
				$emailPercentage = "%".$email."%";
				$stmt = $dap_dbh->prepare($sql);
				$stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
				$stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
				$stmt->bindParam(':email', $emailPercentage, PDO::PARAM_STR);
			}

			//logToFile($sql);
			$stmt->execute();
			$resultsArray = array();
			
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$resultsArray[] = $row;
			}
			
			//logToFileArray($resultsArray,LOG_DEBUG_DAP);
			return $resultsArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}			
	}

	public static function loadPaymentsDue($email, $start_date, $end_date) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$refund_period = Dap_Config::get('REFUND_PERIOD');
			$time_period = mktime(0, 0, 0, date("m"), date("d")-$refund_period, date("y"));
			//No need for start date
			$end_date = date("m-d-Y", $time_period); 
			logToFile("$end_date",LOG_DEBUG_DAP);

			if($email=="") {
				logToFile("------------------------------ in loadPaymentsDue");
				$sql = "SELECT 
							ae.id,
							ar.affiliate_id,
							u.first_name,
							u.last_name,
							u.email,
							u.paypal_email,
							ae.earning_type,
							SUM(
								CASE ae.earning_type
								WHEN 'C' THEN ae.amount_earned
								ELSE 0
								END
							) AS amt_earned_credits,
							SUM(
								CASE ae.earning_type
								WHEN 'S' THEN ae.amount_earned
								WHEN 'L' THEN ae.amount_earned
								WHEN 'T2' THEN ae.amount_earned
								WHEN 'T3' THEN ae.amount_earned
								ELSE 0
								END
							) AS amt_earned_cash
						FROM
							dap_aff_referrals ar,
							dap_aff_earnings ae,
							dap_users u
						WHERE
							ar.id = ae.aff_referrals_id and
							ar.affiliate_id in (select affiliate_id from dap_aff_referrals) and
							ar.affiliate_id = u.id and
							ae.aff_exports_id is NULL and
							DATE(ae.datetime) < str_to_date(:end_date, '%m-%d-%Y')
						GROUP BY
							ar.affiliate_id";
				$stmt = $dap_dbh->prepare($sql);
				$stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
			} else if ($email != "") {
				$sql = "SELECT
							ar.affiliate_id,
							u.first_name,
							u.last_name,
							u.email,
							u.paypal_email,
							ae.earning_type,
							SUM(
								CASE ae.earning_type
								WHEN 'C' THEN ae.amount_earned
								ELSE 0
								END
							) AS amt_earned_credits,
							SUM(
								CASE ae.earning_type
								WHEN 'S' OR 'L' OR 'T2' OR 'T3' THEN ae.amount_earned
								ELSE 0
								END
							) AS amt_earned
						FROM
							dap_aff_referrals ar,
							dap_aff_earnings ae,
							dap_users u
						WHERE
							ar.id = ae.aff_referrals_id and
							ar.affiliate_id in (select affiliate_id from dap_aff_referrals) and
							ar.affiliate_id = u.id and
							u.email like :email and
							ae.aff_exports_id is NULL and
							DATE(ae.datetime) < str_to_date(:end_date, '%m-%d-%Y')
						GROUP BY
							ar.affiliate_id
							";
				$emailPercentage = "%".$email."%";
				$stmt = $dap_dbh->prepare($sql);
				$stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
				$stmt->bindParam(':email', $emailPercentage, PDO::PARAM_STR);
			}

			//logToFile($sql);
			$stmt->execute();
			$resultsArray = array();
			
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				//$affiliate_id = $row['affiliate_id'];
				
				//Use paypal email of affiliate, if set
				$affiliateEmail = ( isset($row['paypal_email']) && ($row['paypal_email'] != "") ) ? $row['paypal_email'] : $row['email']; 
				$row['email'] = $affiliateEmail;
				//logToFile("Aff id: $affiliate_id, amt_earned cash: " . $row['amt_earned_cash'],LOG_DEBUG_DAP);
				/*$sql2 = "SELECT
							sum(amount_paid) as amt_paid
						FROM
							dap_aff_payments
						WHERE
							affiliate_id = $affiliate_id and
							DATE(datetime) < str_to_date(:end_date, '%m-%d-%Y')
						GROUP BY
							affiliate_id";
				$stmt2 = $dap_dbh->prepare($sql2);
				$stmt2->bindParam(':end_date', $end_date, PDO::PARAM_STR);
				$stmt2->execute();
				if ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {				
					$row['amt_paid'] = $row2['amt_paid'];
				}*/
							
				$resultsArray[] = $row;
			}
			
			//logToFileArray($resultsArray,LOG_DEBUG_DAP);
			return $resultsArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}			
	}

	public static function exportAffiliatesForPayment() {
		/* 
		2. Create New Export id in dap_aff_exports
		3. Mark selected affiliate rows in dap_aff_earnings with this export_id
		1. Load Payments Due With new export_id
		4. Return selected affiliates and the export_id back to GUI
		*/
		
		logToFile("in exportAffiliatesForPayment()"); 
		$dap_dbh = null;
		$export_id = 0;
		$end_date = getAffPaymentsEndDate();
		
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$dap_dbh->beginTransaction(); //begin the transaction

			//Create New Export id in dap_aff_exports
			$sql = "insert into dap_aff_exports (payment_status, datetime) values(:payment_status, :datetime)";
			$payment_status = "exported";
			$datetime = date("Y-m-d H:i:s");
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':payment_status', $payment_status, PDO::PARAM_STR);
			$stmt->bindParam(':datetime', $datetime, PDO::PARAM_STR);
			$stmt->execute();		
			$export_id = $dap_dbh->lastInsertId();
			$stmt = null;
			
			logToFile("export_id: $export_id",LOG_DEBUG_DAP);
			
			/* First, select all eligible affiliates
			Then mark selected affiliate rows in dap_aff_earnings with this export_id */
			
			//Select all eligible affiliates
			$sql = "SELECT 
						ae.id
					FROM
						dap_aff_earnings ae,
						dap_aff_referrals ar,
						dap_users u
					WHERE
						ar.id = ae.aff_referrals_id and
						ar.affiliate_id in (select affiliate_id from dap_aff_referrals) and
						ar.affiliate_id = u.id and
						ae.aff_exports_id is NULL and
						DATE(ae.datetime) < str_to_date(:end_date, '%m-%d-%Y')
					";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
			$stmt->execute();
			
			//For each such found affiliate, mark row in dap_aff_earnings with this export_id
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$id = $row['id'];
				logToFile("Aff id being selected: $id",LOG_DEBUG_DAP);
				$sql2 = "UPDATE
							dap_aff_earnings
						SET 
							aff_exports_id = :export_id
						WHERE
							id = :id";
				$stmt2 = $dap_dbh->prepare($sql2);
				$stmt2->bindParam(':export_id', $export_id, PDO::PARAM_INT);
				$stmt2->bindParam(':id', $id, PDO::PARAM_INT);
				$stmt2->execute();
			}
			

			$export = Dap_AffCommissions::loadAffiliateExport($export_id, $dap_dbh);
			$data = "";
			$areThereCreditPayments = false;
	
			foreach ($export as $row) {
				//Ignore credit rows, because they need not be shown in export for payment
				logToFile("Earning type: " . $row['earning_type']);
				if($row['earning_type'] == "C") {
					logToFile("earning_type = C , so skipping this row"); 
					$areThereCreditPayments = true;
					continue;
				}
				
				$affiliate_id = $row['affiliate_id'];
				logToFile("Aff id: $affiliate_id, amt_earned: " . $row['amt_earned'],LOG_DEBUG_DAP);
				$amt_earned = isset($row['amt_earned']) ? $row['amt_earned'] : 0;
				
				$affiliateEmail = ( isset($row['paypal_email']) && ($row['paypal_email'] != "") ) ? $row['paypal_email'] : $row['email']; 
				$data .= $affiliateEmail."\t".$amt_earned ."\t" . Dap_Config::get('CURRENCY_TEXT') . "\r\n";
			}
			
			if( ($data == "") && ($areThereCreditPayments === false) ){
				$dap_dbh->rollback();
			} else {
				$dap_dbh->commit(); //commit the transaction
			}
			
			$stmt = null;
			$stmt2 = null;
			$dap_dbh = null;
			if( ($data == "") && ($areThereCreditPayments === true) ) {
				$data = "CREDITSONLY";
			}
			return $data;
		} catch (PDOException $e) {
			$dap_dbh->rollback();
			$dap_dbh = null;
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			$dap_dbh->rollback();
			$dap_dbh = null;
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
			
	}
	
	
	public static function loadAffiliateStats($email, $start_date, $end_date) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			if($start_date == "") $start_date = date("m-d-Y");
			if($end_date == "") $end_date = date("m-d-Y");
			//logToFile("start_date: $start_date, end_date: $end_date"); 
			
			if($email=="") {
				$sql = "SELECT 
							u.first_name,
							u.last_name,
							u.email,
							afs.id,
							afs.user_id,
							afs.http_referer,
							afs.datetime,
							afs.useragent,
							afs.ip,
							afs.dest_url
						FROM
							dap_aff_stats afs,
							dap_users u
						WHERE
							afs.user_id = u.id and
							DATE(datetime) between str_to_date(:start_date, '%m-%d-%Y') and str_to_date(:end_date, '%m-%d-%Y')
						order by 
							datetime desc
						limit 
							2000";
				$stmt = $dap_dbh->prepare($sql);
				$stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
				$stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
			} else if ($email != "") {
				$sql = "SELECT 
							afs.id,
							afs.user_id,
							afs.http_referer,
							afs.datetime,
							afs.useragent,
							afs.ip,
							afs.dest_url
						FROM
							dap_aff_stats afs,
							dap_users u
						WHERE
							u.email like :email and
							u.id = afs.user_id and
							DATE(datetime) between str_to_date(:start_date, '%m-%d-%Y') and str_to_date(:end_date, '%m-%d-%Y')
						order by 
							datetime desc
						limit 
							2000
							";
				$emailPercentage = "%".$email."%";
				$stmt = $dap_dbh->prepare($sql);
				$stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
				$stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
				$stmt->bindParam(':email', $emailPercentage, PDO::PARAM_STR);
			}

			//logToFile($sql);
			$stmt->execute();
			$resultsArray = array();
			
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {				
				$resultsArray[] = $row;
			}
							
			return $resultsArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}			
	}
	

	public static function loadAffiliateExports($payment_status="%") {
		$dap_dbh = Dap_Connection::getConnection();
		logToFile("payment_status: $payment_status",LOG_DEBUG_DAP);
		
		//Load product details from database
		$sql = "select *
			from
				dap_aff_exports
			where
				payment_status like :payment_status
			order by 
				datetime desc";

		$stmt = $dap_dbh->prepare($sql);
		$stmt->bindParam(':payment_status', $payment_status, PDO::PARAM_STR);
		$stmt->execute();
		$resultsArray = array();

		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$resultsArray[] = $row;
		}

		return $resultsArray;
	}
	
	
	public static function loadAffiliateExport($export_id, $dap_dbh=null, $earning_type="'L','S','T2','T3'") {
		try {

			logToFile("select affiliates with new export id: $export_id, earning_type: $earning_type",LOG_DEBUG_DAP);
			$dap_dbh = Dap_Connection::getConnection($dap_dbh);

			//Now select affiliates with new export id and return to GUI
			$sql = "SELECT 
						ae.id,
						ar.affiliate_id,
						u.first_name,
						u.last_name,
						u.email,
						u.paypal_email,
						ae.earning_type,
						sum(ae.amount_earned) as amt_earned
					FROM
						dap_aff_referrals ar,
						dap_aff_earnings ae,
						dap_users u
					WHERE
						ae.aff_exports_id = :export_id and
						ae.earning_type in (".$earning_type.") and
						ar.id = ae.aff_referrals_id and
						ar.affiliate_id in (select affiliate_id from dap_aff_referrals) and
						ar.affiliate_id = u.id 
					GROUP BY
						ar.affiliate_id";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':export_id', $export_id, PDO::PARAM_INT);
			//$stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
			$stmt->execute();
			$resultsArray = array();
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {	
				$resultsArray[] = $row;
			}
			return $resultsArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}
	
	
	
	public static function loadAffiliateExportForDisplay($export_id, $dap_dbh=null) {
		try {

			logToFile("select affiliates with new export id - $export_id, end_date: $end_date",LOG_DEBUG_DAP);
			$dap_dbh = Dap_Connection::getConnection($dap_dbh);

			//Now select affiliates with new export id and return to GUI
			$sql = "SELECT 
						ae.id,
						ar.affiliate_id,
						u.first_name,
						u.last_name,
						u.email,
						sum(ae.amount_earned) as amt_earned
					FROM
						dap_aff_referrals ar,
						dap_aff_earnings ae,
						dap_users u
					WHERE
						ae.aff_exports_id = :export_id and
						ae.earning_type in ('L','S','T2','T3') and
						ar.id = ae.aff_referrals_id and
						ar.affiliate_id in (select affiliate_id from dap_aff_referrals) and
						ar.affiliate_id = u.id 
					GROUP BY
						ar.affiliate_id";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':export_id', $export_id, PDO::PARAM_INT);
			//$stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
			$stmt->execute();
			$resultsArray = array();
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {	
				$resultsArray[] = $row;
			}
			return $resultsArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}	
			
	



	public static function bulkMarkAffiliatesAsPaid($export_id) {
		try {
			$payment_status = 'paid';
			if( ($export_id <=0) || ($payment_status == "") ) return "Oops! Sorry, invalid Export Id or Payment Status";
			
			$dap_dbh = Dap_Connection::getConnection();
			logToFile("export_id: $export_id, payment_status: $payment_status",LOG_DEBUG_DAP);
			
			$sql = "update 
						dap_aff_exports
					set
						payment_status = :payment_status
					where
						id = :export_id";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':export_id', $export_id, PDO::PARAM_INT);
			$stmt->bindParam(':payment_status', $payment_status, PDO::PARAM_STR);
			$stmt->execute();
			
			return;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}			
	}
	
	
	public static function loadPublisherEarningsSummary($start_date, $end_date) {
		try {
			logToFile("Start, end: $start_date, $end_date"); 
			$dap_dbh = Dap_Connection::getConnection();
			if($start_date == "") $start_date = date("m-d-Y");
			if($end_date == "") $end_date = date("m-d-Y");
			$stmt = null;
			$sql = "
					SELECT
						p.id,
						p.name, 
						p.description,
						p.thirdPartyEmailIds,
						sum(t.payment_value) as product_total_sales
					FROM
						dap_transactions t,
						dap_products p
					WHERE
						t.product_id = p.id and
						t.payment_status in ('Completed', 'Refund') and
						t.date between str_to_date(:start_date, '%m-%d-%Y') and str_to_date(:end_date, '%m-%d-%Y')
					GROUP BY
						t.product_id
					";
			logToFile($sql);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':start_date', $start_date, PDO::PARAM_STR);
			$stmt->bindParam(':end_date', $end_date, PDO::PARAM_STR);
			$stmt->execute();
			$resultsArray = array();
			
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$resultsArray[] = $row;
			}
			
			return $resultsArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}			
	}
	
	public static function processAffiliateCommissionsMultiTierRecursive($userId, $affId, $productId, $transId, $transValue, $tier = 2) {
		//Start n-tier processing here...
		//Get parentAffId of this affId
		logToFile($userId. "," .  $affId. "," .  $productId. "," .  $transId. "," .  $transValue. "," .  $tier); 
		$aff = Dap_User::loadUserById($affId);
		if(!isset($aff) || is_null($aff) ) {
			logToFile("$affId doesn't appear to be a valid affiliate id"); 
			return;
		}
		
		$affIdParent = $aff->getAffiliate();
		if( !isset($affIdParent) || ($affIdParent == 0) ) {
			//return, no affiliate
			return;
		}
		
		//Affiliate found, continue
		//Process Affiliate Credit
		$earning_type = "T" . $tier;
		try {
			$last_insert_id = Dap_AffReferrals::processAffiliationMultiTier($affIdParent, $userId, $productId, $transId, $transValue, $tier, $earning_type);
			if($last_insert_id != 0) {
				Dap_AffCommissions::processAffiliateCommissionsMultiTierRecursive($userId, $affIdParent, $productId, $transId, $transValue, ++$tier);
			}
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			//throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			//throw $e;
		}
		return;
	}	

}	
