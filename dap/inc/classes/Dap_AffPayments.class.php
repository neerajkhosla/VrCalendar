<?php

class Dap_AffPayments extends Dap_Base {

	var $id;
	var $affiliate_id;
	var $amount_paid;
	var $datetime;
	var $comments;
	

	function getId() {
	        return $this->id;
	}
	function setId($o) {
	        $this->id = $o;
	}

	function getAffiliate_id() {
	        return $this->affiliate_id;
	}
	function setAffiliate_id($o) {
	        $this->affiliate_id = $o;
	}

	function getAmount_paid() {
	        return $this->amount_paid;
	}
	function setAmount_paid($o) {
	        $this->amount_paid = $o;
	}
	
	function getDatetime() {
	        return $this->datetime;
	}
	function setDatetime($o) {
	        $this->datetime = $o;
	}

	function getComments() {
	        return $this->comments;
	}
	function setComments($o) {
	        $this->comments = $o;
	}
	
	function getEarning_type() {
	        return $this->earning_type;
	}
	function setEarning_type($o) {
	        $this->earning_type = $o;
	}
	
	
	
	public function load() {
		try {

			//Load resource details from database
			$dap_dbh = Dap_Connection::getConnection();
	
			$sql = "select 
						affiliate_id,
						amount_paid,
						datetime,
						comments,
						earning_type
					from
						dap_aff_payments
					where
						id = :id";

			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $this->getId(), PDO::PARAM_INT);
			$stmt->execute();
			
			if ($row = $stmt->fetch()) {
				$this->setAffiliate_id($row["affiliate_id"]) ;
				$this->setAmount_paid($row["amount_paid"]) ;
				$this->setDatetime($row["datetime"]) ;
				$this->setComments($row["comments"]) ;
				$this->setEarning_type($row["earning_type"]) ;
			}
			
			return;
		
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}


	public function create() {
		try {
			$dap_dbh = Dap_Connection::getConnection();
	
			$sql = "insert into 
						dap_aff_payments
						(affiliate_id, amount_paid, datetime, comments)
					values 
						(:affiliate_id, :amount_paid, now(), :comments)
				";

			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':affiliate_id', $this->getAffiliate_id(), PDO::PARAM_STR);
			$stmt->bindParam(':amount_paid', $this->getAmount_paid(), PDO::PARAM_STR);
			$stmt->bindParam(':comments', $this->getComments(), PDO::PARAM_STR);
			$stmt->execute();

			return $dap_dbh->lastInsertId();
			$stmt = null;
			$dap_dbh = null;
		
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}
	
	/** This function currently called only by dap-cron.php */
	public static function markExportsAsPaid($comments="", $dap_dbh=null) {
		try {
			logToFile("in markExportsAsPaid",LOG_DEBUG_DAP);
			$dap_dbh = Dap_Connection::getConnection($dap_dbh);
			$dap_dbh->beginTransaction(); //begin the transaction
			$end_date = getAffPaymentsEndDate();
			
			//Select all exports in 'paid' status
			$payment_status = "paid";
			$sqlm = "select 
						id as export_id,
						datetime
					from
						dap_aff_exports
					where
						payment_status = :payment_status";

			$stmtm = $dap_dbh->prepare($sqlm);
			$stmtm->bindParam(':payment_status', $payment_status, PDO::PARAM_STR);
			$stmtm->execute();
			
			while ($rowm = $stmtm->fetch(PDO::FETCH_ASSOC)) {
				$export_id = $rowm["export_id"];
				
				//For each export id, get affiliate earnings summary
				$data = Dap_AffCommissions::loadAffiliateExport($export_id, $dap_dbh, "'L','S','T2','T3'");
				logToFile("Processing CASH"); 

				//For each affiliate found, insert a row in dap_aff_payments
				foreach($data as $row) {
					$affiliate_id = $row['affiliate_id'];
					$amt_earned = isset($row['amt_earned']) ? $row['amt_earned'] : 0;
					if($amt_earned == 0) continue; //Skip to next row if nothing to pay
					
					logToFile("Aff id: $affiliate_id, amt_earned: ".$row['amt_earned'],LOG_DEBUG_DAP);
					logToFile("$export_id, $datetime, aff_exports_id ",LOG_DEBUG_DAP);
					//$datetime = date("Y-m-d H:i:s");
					//$data .= $row['email']."\t".$amt_earned ."\t" . Dap_Config::get('CURRENCY_TEXT') . "\r\n";
					
					$earning_type = "CASH"; //default value
					
					try {
						$sql = "insert
									into
								dap_aff_payments
									(affiliate_id, amount_paid, datetime, comments, aff_exports_id, earning_type)
								values
									(:affiliate_id, :amount_paid, now(), :comments, :aff_exports_id, :earning_type)
								";
	
						$stmt = $dap_dbh->prepare($sql);
						$stmt->bindParam(':affiliate_id', $affiliate_id, PDO::PARAM_INT);
						$stmt->bindParam(':amount_paid', $amt_earned, PDO::PARAM_STR);
						//$stmt->bindParam(':datetime', $datetime, PDO::PARAM_STR);
						$stmt->bindParam(':comments', $comments, PDO::PARAM_STR);
						$stmt->bindParam(':aff_exports_id', $export_id, PDO::PARAM_INT);
						$stmt->bindParam(':earning_type', $earning_type, PDO::PARAM_STR);
						$stmt->execute();
						
						$stmt = null;
					} catch (PDOException $e) {
						//logToFile($e->getMessage(),LOG_FATAL_DAP);
					} catch (Exception $e) {
						//logToFile($e->getMessage(),LOG_FATAL_DAP);
					}	
				}
				
				
				//For each export id, get affiliate earnings summary
				$data = Dap_AffCommissions::loadAffiliateExport($export_id, $dap_dbh, "'C'");
				logToFile("Processing CREDITS"); 

				//For each affiliate found, insert a row in dap_aff_payments
				foreach($data as $row) {
					$affiliate_id = $row['affiliate_id'];
					$amt_earned = isset($row['amt_earned']) ? $row['amt_earned'] : 0;
					if($amt_earned == 0) continue; //Skip to next row if nothing to pay
					
					logToFile("Aff id: $affiliate_id, amt_earned: $amt_earned",LOG_DEBUG_DAP);
					logToFile("aff_exports_id: $export_id",LOG_DEBUG_DAP);
					//$datetime = date("Y-m-d H:i:s");
					//$data .= $row['email']."\t".$amt_earned ."\t" . Dap_Config::get('CURRENCY_TEXT') . "\r\n";
					
					$earning_type = "CREDITS";
					
					try {
						$sql2 = "insert
									into
								dap_aff_payments
									(affiliate_id, amount_paid, datetime, comments, aff_exports_id, earning_type)
								values
									(:affiliate_id, :amount_paid, now(), :comments, :aff_exports_id, :earning_type)
								";
						$stmt2 = $dap_dbh->prepare($sql2);
						$stmt2->bindParam(':affiliate_id', $affiliate_id, PDO::PARAM_INT);
						$stmt2->bindParam(':amount_paid', $amt_earned, PDO::PARAM_STR);
						//$stmt2->bindParam(':datetime', $datetime, PDO::PARAM_STR);
						$stmt2->bindParam(':comments', $comments, PDO::PARAM_STR);
						$stmt2->bindParam(':aff_exports_id', $export_id, PDO::PARAM_INT);
						$stmt2->bindParam(':earning_type', $earning_type, PDO::PARAM_STR);
						$stmt2->execute();
						
						//Now get all 'C' (credit) earnings rows associated with this export and do 
						//actual credit inserts for that user into dap_users
						//logToFile("affiliate_id: $affiliate_id"); 
						Dap_UserCredits::addCredits($affiliate_id, -1, 0, $amt_earned, 0, "Affiliate Earnings");
						
						//$user = Dap_User::loadUserById($affiliate_id);
						//$credits_available_now = $user->getCredits_available() + $amt_earned;
						//logToFile("about to update credits"); 
						//$user->updateCredits($credits_available_now);
						//logToFile("Current credits: " . $user->getCredits_available()); 
						//logToFile("New credits total: " . $credits_available_now); 
						
						$stmt2 = null;
					} catch (PDOException $e) {
						//logToFile($e->getMessage(),LOG_FATAL_DAP);
					} catch (Exception $e) {
						//logToFile($e->getMessage(),LOG_FATAL_DAP);
					}	
				}
				
				
				//Finally, after all affiliates in that export have been paid (inserted into dap_aff_payments), mark this export as 'processed'
				$payment_status2 = 'processed';
				$sql2 = "update 
							dap_aff_exports 
						set 
							payment_status = :payment_status,
							datetime = now()
						where 
							id = :export_id
						";
	
				$stmt2 = $dap_dbh->prepare($sql2);
				$stmt2->bindParam(':export_id', $export_id, PDO::PARAM_INT);
				$stmt2->bindParam(':payment_status', $payment_status2, PDO::PARAM_STR);
				$stmt2->execute();
				$stmt2 = null;
				
			} //end-while - go to next export id that is 'paid'	
			

			$dap_dbh->commit(); //commit the transaction
			$stmtm = null;
			$dap_dbh = null;
			
		} catch (PDOException $e) {
			$dap_dbh->rollback();
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			$dap_dbh->rollback();
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}
	
	
}
?>
