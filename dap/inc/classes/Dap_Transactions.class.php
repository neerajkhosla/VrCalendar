<?php

/*
	DOC: NOTE: If you modify this list, also update the array in functions_admin.php
	
	Lets you set the dap internal status on the transaction record. 
	
	0 - Init status - db default
	1 - Paypal Verified (payment processor verified)
	2 - Paypal Invalid (payment processor declined) - admin  reprocessible
	3 - Paypal Communication Error (payment processor cannot be reached) -  admin reprocessible
	4 - Misc Error - admin reprocessible
	5 - Processed successfully.
	6 - Processed ERROR.
	7 - Processed Affiliations Successfully - Final State. 
*/


class Dap_Transactions extends Dap_Base {
   	var $id;
   	var $trans_num;
   	var $trans_type;
   	var $payment_status;
   	var $payment_currency;
	var $payment_value;
	var $payer_email;
   	var $sub_trans_num = 0;
   	var $payment_processor;
   	var $time;
   	var $date;
   	var $trans_blob;
   	var $product_id;
	var $user_id;
	var $coupon_id;
	var $status;
	
	function getId() {
		return $this->id;
	}
	function setId($o) {
		$this->id = $o;
	}
	
	function getTrans_num() {
	        return $this->trans_num;
	}
	
	function setTrans_num($o) {
	      $this->trans_num = $o;
	}
	
	function getTrans_type() {
	        return $this->trans_type;
	}
	
	function setTrans_type($o) {
	      $this->trans_type = $o;
	}
	
	function getPayment_status() {
	        return $this->payment_status;
	}
	
	function setPayment_status($o) {
	      $this->payment_status = $o;
	}
	
	function getPayment_currency() {
	        return $this->payment_currency;
	}
	
	function setPayment_currency($o) {
	      $this->payment_currency = $o;
	}
	
	function getPayment_value() {
	        return $this->payment_value;
	}
	
	function setPayment_value($o) {
	      $this->payment_value = $o;
	}	

	function getPayer_email() {
	        return $this->payer_email;
	}
	
	function setPayer_email($o) {
	      $this->payer_email = $o;
	}
	
	function getSub_trans_num() {
	        return $this->sub_trans_num;
	}
	
	function setSub_trans_num($o) {
	      $this->sub_trans_num = $o;
	}
	
	function getPayment_processor() {
	        return $this->payment_processor;
	}
	
	function setPayment_processor($o) {
	      $this->payment_processor = $o;
	}
	
	function getTime() {
	        return $this->time;
	}
	
	function setTime($o) {
	      $this->time = $o;
	}
	
	function getDate() {
	        return $this->date;
	}
	
	function setDate($o) {
	      $this->date = $o;
	}
	
	function getTrans_blob() {
	        return $this->trans_blob;
	}
	
	function setTrans_blob($o) {
	      $this->trans_blob = $o;
	}
	
	function getProduct_id() {
	        return $this->product_id;
	}
	
	function setProduct_id($o) {
	      $this->product_id = $o;
	}
	
	function getStatus() {
	        return $this->status;
	}
	
	function getUser_id() {
	        return $this->user_id;
	}
	
	function setUser_id($o) {
	      $this->user_id = $o;
	}
	
	function setStatus($o) {
	      $this->status = $o;
	}

	function getCoupon_id() {
	        return $this->coupon_id;
	}
	
	function setCoupon_id($o) {
	      $this->coupon_id = $o;
	}
	
	//Extra field
	function getProduct_name() {
	        return $this->product_name;
	}
	
	function setProduct_name($o) {
	      $this->product_name = $o;
	}
	
	//Load transactions matching filter criteria
	public static function loadNumSalesPerProduct($productId, $startdate="") {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$count = 0;
			$status=5;
			
			//Check if there are any users associated with this coupon
			if ($startdate == "") {
				$sql = "select count(*) as count from dap_transactions where product_id = :productId and status=:status";
				$stmt = $dap_dbh->prepare($sql);
				$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
				$stmt->bindParam(':status', $status, PDO::PARAM_INT);
				$stmt->execute();
			}
			else {
				$sql = "select count(*) as count from dap_transactions where product_id = :productId and status=:status and date>=:startdate";
				$stmt = $dap_dbh->prepare($sql);
				$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
				$stmt->bindParam(':status', $status, PDO::PARAM_INT);
				$stmt->bindParam(':startdate', $startdate, PDO::PARAM_INT);
				$stmt->execute();
			}

			if ($row = $stmt->fetch()) {
				$count = $row["count"];
			}
		} catch (PDOException $e) {
			$dap_dbh->rollback();
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			$dap_dbh->rollback();
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
		
		return $count;
	}
	
	
	//Load transactions matching filter criteria
	public static function loadTransactionsByProcessor($transNumFilter, $emailFilter, $productIdFilter, $statusFilter, $paymentProcessor) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$TransactionsList = array();
			$sql = "";
			
			if($productIdFilter == "none") {
					$productIdFilter = "";
					$sql = "select 
							t.id,
							t.trans_num,
							t.trans_type,
							t.trans_type, 
							t.payment_status, 
							t.payment_currency, 
							t.payment_value,
							t.payer_email, 
							t.sub_trans_num,
							t.payment_processor, 
							t.date, 
							t.time, 
							t.trans_blob, 
							t.product_id,
							t.coupon_id,
							t.user_id,
							t.status
							from 
								dap_transactions t
							where
								t.product_id not in (select id from dap_products)  ";
				} else {
					$sql = "select 
								t.id,
								t.trans_num,
								t.trans_type,
								t.trans_type, 
								t.payment_status, 
								t.payment_currency, 
								t.payment_value,
								t.payer_email, 
								t.sub_trans_num,
								t.payment_processor, 
								t.date, 
								t.time, 
								t.trans_blob, 
								t.product_id,
								t.coupon_id,
								t.user_id,
								t.status,
								p.name as product_name
								from 
									dap_transactions t,
									dap_products p
								where
									t.product_id = p.id 
									";
								
			}
			
			if($productIdFilter != "") $sql .= " and product_id = $productIdFilter ";
			if( ($statusFilter != "All") && ($statusFilter != "") ) $sql .= " and status = '$statusFilter'";
			if($transNumFilter != "") $sql .= " and trans_num like '%$transNumFilter%'";
			if($emailFilter != "") $sql .= " and payer_email like '%$emailFilter%'";
			if($paymentProcessor != "") $sql .= " and payment_processor like '%$paymentProcessor%'";
			
			$sql .= " order by t.id desc ";
			
//			logToFile($sql, LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->execute();
			
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$transaction = new Dap_Transactions();
	
				$transaction->setId( $row["id"] );
				$transaction->setTrans_num( $row["trans_num"] );
				$transaction->setTrans_type( $row["trans_type"] );
				$transaction->setPayment_status( $row["payment_status"] );
				$transaction->setPayment_currency( $row["payment_currency"] );
				$transaction->setPayment_value( $row["payment_value"] );
				$transaction->setPayer_email( $row["payer_email"] );
				$transaction->setSub_trans_num( $row["sub_trans_num"] );
				$transaction->setPayment_processor( $row["payment_processor"] );
				$transaction->setTime( $row["time"] );
				$transaction->setDate( $row["date"] );
				$transaction->settrans_blob( $row["trans_blob"] );
				$transaction->setProduct_id( $row["product_id"] );
				$transaction->setUser_id( $row["user_id"] );
				$transaction->setCoupon_id( $row["coupon_id"] );
				$transaction->setProduct_name( $row["product_name"] );
				$transaction->setStatus( $row["status"] );
	
				$TransactionsList[] = $transaction;
			}
			
			return $TransactionsList;
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
	
	
	
	//Load transactions matching filter criteria
	public static function loadTransactions($transNumFilter, $emailFilter, $productIdFilter, $statusFilter, $couponIdFilter="", $userIdFilter="", $transIdFilter="", $startDateFilter="", $endDateFilter="") {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$TransactionsList = array();
			$sql = "";
			
			//logToFile("Dap_Transactions.class.php::loadTransactions():transIdFilter=".$transIdFilter); 
			
			//if start date is empty, set to one week ago
			//if ($startDateFilter=="") $startDateFilter = date("m-d-Y", mktime(0, 0, 0, date("m")  , date("d")-6, date("Y")));
			//if end date is empty, set to today's date
			//if ($endDateFilter=="") $endDateFilter = date("m-d-Y");
			
			//logToFile("startDateFilter: $startDateFilter , endDateFilter: $endDateFilter"); 
			
			if($productIdFilter == "none") {
					$productIdFilter = "";
					if($startDateFilter!="") {
					$sql = "select 
							t.id,
							t.trans_num,
							t.trans_type,
							t.trans_type, 
							t.payment_status, 
							t.payment_currency, 
							t.payment_value,
							t.payer_email, 
							t.sub_trans_num,
							t.payment_processor, 
							t.date, 
							t.time, 
							t.trans_blob, 
							t.product_id,
							t.coupon_id,
							t.user_id,
							t.status
							from 
								dap_transactions t
							where
								t.product_id not in (select id from dap_products) and
								t.date between str_to_date('".$startDateFilter."', '%m-%d-%Y') and str_to_date('".$endDateFilter."', '%m-%d-%Y')		
								
							";
					}
					else {
						$sql = "select 
							t.id,
							t.trans_num,
							t.trans_type,
							t.trans_type, 
							t.payment_status, 
							t.payment_currency, 
							t.payment_value,
							t.payer_email, 
							t.sub_trans_num,
							t.payment_processor, 
							t.date, 
							t.time, 
							t.trans_blob, 
							t.product_id,
							t.coupon_id,
							t.user_id,
							t.status
							from 
								dap_transactions t
							where
								t.product_id not in (select id from dap_products)								
							";
					}
				} else {
					if($startDateFilter!="") {
					$sql = "select 
								t.id,
								t.trans_num,
								t.trans_type,
								t.trans_type, 
								t.payment_status, 
								t.payment_currency, 
								t.payment_value,
								t.payer_email, 
								t.sub_trans_num,
								t.payment_processor, 
								t.date, 
								t.time, 
								t.trans_blob, 
								t.product_id,
								t.coupon_id,
								t.user_id,
								t.status,
								p.name as product_name
								from 
									dap_transactions t,
									dap_products p
								where
									t.product_id = p.id and
									t.date between str_to_date('".$startDateFilter."', '%m-%d-%Y') and str_to_date('".$endDateFilter."', '%m-%d-%Y')";
					}
					else {
						$sql = "select 
								t.id,
								t.trans_num,
								t.trans_type,
								t.trans_type, 
								t.payment_status, 
								t.payment_currency, 
								t.payment_value,
								t.payer_email, 
								t.sub_trans_num,
								t.payment_processor, 
								t.date, 
								t.time, 
								t.trans_blob, 
								t.product_id,
								t.coupon_id,
								t.user_id,
								t.status,
								p.name as product_name
								from 
									dap_transactions t,
									dap_products p
								where
									t.product_id = p.id";
					}
								
			}
			
			if($productIdFilter != "") $sql .= " and product_id = $productIdFilter ";
			if( ($statusFilter != "All") && ($statusFilter != "") ) $sql .= " and status = '$statusFilter'";
			if($transNumFilter != "") $sql .= " and trans_num like '%$transNumFilter%'";
			if($emailFilter != "") $sql .= " and payer_email like '%$emailFilter%'";
			if($couponIdFilter != "") $sql .= " and coupon_id like '%$couponIdFilter%'";
			if($userIdFilter != "") $sql .= " and user_id like '%$userIdFilter%'";
			if($transIdFilter != "") $sql .= " and t.id = '$transIdFilter'";
			
			$sql .= " order by t.id desc ";
			
			//logToFile($sql, LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->execute();
			
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$transaction = new Dap_Transactions();
	
				$transaction->setId( $row["id"] );
				$transaction->setTrans_num( $row["trans_num"] );
				$transaction->setTrans_type( $row["trans_type"] );
				$transaction->setPayment_status( $row["payment_status"] );
				$transaction->setPayment_currency( $row["payment_currency"] );
				$transaction->setPayment_value( $row["payment_value"] );
				$transaction->setPayer_email( $row["payer_email"] );
				$transaction->setSub_trans_num( $row["sub_trans_num"] );
				$transaction->setPayment_processor( $row["payment_processor"] );
				$transaction->setTime( $row["time"] );
				$transaction->setDate( $row["date"] );
				$transaction->settrans_blob( $row["trans_blob"] );
				$transaction->setProduct_id( $row["product_id"] );
				$transaction->setUser_id( $row["user_id"] );
				$transaction->setCoupon_id( $row["coupon_id"] );
				$transaction->setProduct_name( $row["product_name"] );
				$transaction->setStatus( $row["status"] );
	
				$TransactionsList[] = $transaction;
//				logToFile("Dap_Transactions.class.php::loadTransactions():found transaction=".$row["trans_num"] ); 
				
			}
			
			return $TransactionsList;
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
	
	
	//Load transactions matching filter criteria
	public static function loadTransactionsbyPaymentStatus($emailFilter, $productIdFilter, $paymentstatusFilter, $dateFilter, $paymentProcessor) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$TransactionsList = array();
			$sql = "";
			
			logToFile("Dap_Transactions.class.php::loadTransactions():productIdFilter=".$productIdFilter); 
			
			if ($dateFilter=="") $dateFilter = date("Y-m-d");
			
			//logToFile("startDateFilter: $startDateFilter , endDateFilter: $endDateFilter"); 
			
		
			$sql = "select 
							t.id,
							t.trans_num,
							t.trans_type,
							t.trans_type, 
							t.payment_status, 
							t.payment_currency, 
							t.payment_value,
							t.payer_email, 
							t.sub_trans_num,
							t.payment_processor, 
							t.date, 
							t.time, 
							t.trans_blob, 
							t.product_id,
							t.coupon_id,
							t.user_id,
							t.status
							from 
								dap_transactions t
							where
								t.product_id in (select id from dap_products) and
								t.date='$dateFilter'								
							";
				
			
			if($productIdFilter != "") $sql .= " and product_id = $productIdFilter";
			if( ($paymentstatusFilter != "All") && ($paymentstatusFilter != "") ) $sql .= " and payment_status like '%$paymentstatusFilter%'";
			if($emailFilter != "") $sql .= " and payer_email like '%$emailFilter%'";
			if( ($paymentProcessor != "All") && ($paymentProcessor != "") ) $sql .= " and payment_processor like '%$paymentProcessor%'";
		
			logToFile($sql, LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->execute();
			
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$transaction = new Dap_Transactions();
	
				$transaction->setId( $row["id"] );
				$transaction->setTrans_num( $row["trans_num"] );
				$transaction->setTrans_type( $row["trans_type"] );
				$transaction->setPayment_status( $row["payment_status"] );
				$transaction->setPayment_currency( $row["payment_currency"] );
				$transaction->setPayment_value( $row["payment_value"] );
				$transaction->setPayer_email( $row["payer_email"] );
				$transaction->setSub_trans_num( $row["sub_trans_num"] );
				$transaction->setPayment_processor( $row["payment_processor"] );
				$transaction->setTime( $row["time"] );
				$transaction->setDate( $row["date"] );
				$transaction->settrans_blob( $row["trans_blob"] );
				$transaction->setProduct_id( $row["product_id"] );
				$transaction->setUser_id( $row["user_id"] );
				$transaction->setCoupon_id( $row["coupon_id"] );
				$transaction->setProduct_name( $row["product_name"] );
				$transaction->setStatus( $row["status"] );
	
				$TransactionsList[] = $transaction;
//				logToFile("Dap_Transactions.class.php::loadTransactions():found transaction=".$row["trans_num"] ); 
				
			}
			
			return $TransactionsList;
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
	
	
	public static function loadAjax($id) {
		try {
			global $transaction_statuses;
			$productIdValid = true;
			$isRefunded = "N";
			$dap_dbh = Dap_Connection::getConnection();
			//See if product id is valid
			$sql = "select 
						product_id
					from 
						dap_transactions
					where 
						id = :id
					";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_STR);
			$stmt->execute();

			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				if( ($row["product_id"] == null) || ($row["product_id"] <= 0) ) {
					$productIdValid = false;
				} 
			}
			//check to see if this transaction is already refunded.
			$sql = "select 
						trans_num
					from 
						dap_transactions
					where 
						payment_status = 'Refund' and 
						payment_value < 0 and						
						sub_trans_num = (select trans_num from dap_transactions where id = :id)
						
					";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_STR);
			$stmt->execute();

			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$isRefunded = "Y";
			}			

			$sql = "select 
						t.id,
						t.trans_num,
						t.trans_type,
						t.trans_type, 
						t.payment_status, 
						t.payment_currency, 
						t.payment_value,
						t.payer_email, 
						t.sub_trans_num,
						t.payment_processor, 
						t.date, 
						t.time, 
						t.trans_blob, 
						t.product_id,
						t.user_id,
						t.coupon_id,
						t.status ";
						
			if($productIdValid == true) {
				$sql .= "	, p.name as product_name
						from 
							dap_transactions t,
							dap_products p
						where 
							t.id = :id and
							t.product_id = p.id
						";
			} else {//No product id found - which means invalid/unverifiable product id
				$sql .= "from 
							dap_transactions t
						where 
							id = :id
						";
			}
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_STR);
			$stmt->execute();
			
			$transactionArray = array();
			
			$paypal=false;
			$authnet=false;
			$stripe=false;
			
			logToFile("Dap_Transactions.class.php::isRefunded=".$isRefunded); 
			
			if ($obj = $stmt->fetch(PDO::FETCH_OBJ)) {
				$obj->statusDescription = $transaction_statuses[$obj->status];
				//$obj->status = $transaction_statuses[$obj->status];
				$obj->isRefunded = $isRefunded;
				parse_str($obj->trans_blob, $list);
				$cancelled_subscription="";
			//	logToFile("Dap_Transactions.class.php::BLOB=".$obj->trans_blob); 
			    if(array_key_exists('cancel',$list)) {
					logToFile("Dap_Transactions.class.php::cancelled_subscription".$cancelled_subscription); 
					$cancelled_subscription = $list["cancel"];
					if($cancelled_subscription=="USERCANCELLED")
					  $source="USER";
					else if($cancelled_subscription=="ADMINCANCELLED")
					  $source="ADMIN";
					logToFile("Dap_Transactions.class.php::cancelled_subscription".$cancelled_subscription); 
				}
				
				$profile_id="";
				if(array_key_exists('recurring_payment_id',$list)) {
					$profile_id = $list["recurring_payment_id"];
				}
				else if(array_key_exists('subscr_id',$list)) {
					logToFile("Dap_Transactions.class.php:: Found SUBSCR_ID: profile_id".$list["subscr_id"]); 
					$profile_id = $list["subscr_id"];
				}
				else if(array_key_exists('sub_id',$list)) {
					logToFile("Dap_Transactions.class.php:: Found SUBSCR_ID: profile_id".$list["subscr_id"]); 
					//sub_id=7575149:1
					$profile_id = $list["sub_id"];
					if(strstr($profile_id,":")) {
					  $data = explode(":",$profile_id);
					  $profile_id = $data[0];
					}
					logToFile("Dap_Transactions.class.php:: profileId=" . $list["sub_id"] . ", new sub_id".$profile_id, LOG_INFO_DAP);
				}
				else if(array_key_exists('stripe_customer_id',$list)) {
					logToFile("Dap_Transactions.class.php:: Found SUBSCR_ID: profile_id".$list["stripe_customer_id"]); 
					//sub_id=7575149:1
					$profile_id = $list["stripe_customer_id"];
					logToFile("Dap_Transactions.class.php:: stripe customer id=" . $list["stripe_customer_id"], LOG_INFO_DAP);
				}
				
				$payment_processor = $obj->payment_processor;
				if ($payment_processor == "AUTHNET") {
					$authnet=true;
				}
				if (stristr($payment_processor,"PAYPAL")) {
					$paypal=true;
				}
				if (stristr($payment_processor,"STRIPE")) {
					$stripe=true;
				}
				
				$obj->showCancel = "N";
				
				if(isset($obj->product_name)) {
					$item_name=$obj->product_name;
					$product = Dap_Product::loadProductByName($item_name);
					
					$isRecurring="Y";
					if($product) {
						if(strtolower($product->getIs_recurring()) == "y") {
							$isRecurring="Y";
						}
						else {
							$isRecurring="N";
						}
					}
				}
				
				if(($profile_id != "") && (($authnet) || ($paypal) || ($stripe))) {
				  logToFile("Dap_Transactions.class.php:: showCancel: profileId=" . $profile_id, LOG_INFO_DAP);
				  if( ($authnet || $stripe) && ($isRecurring=="Y")) {
				  	$obj->showCancel = "Y";
				  }
				  else if ($paypal) {
				  	$obj->showCancel = "Y";
				  }
			    }
			
				if($cancelled_subscription=="")
				  $obj->isCancelled = "N";
				else 
				  $obj->isCancelled = $source;
				  
				$transactionArray[] = $obj;
				
				/*$transaction = new Dap_Transactions();
	
				$transaction->setId( $obj->id );
				$transaction->setTrans_num( $obj->trans_num);
				$transaction->setTrans_type( $obj->trans_type);
				$transaction->setPayment_status( $obj->payment_status);
				$transaction->setPayment_currency( $obj->payment_currency);
				$transaction->setPayment_value( $obj->payment_value);
				$transaction->setPayer_email( $obj->payer_email);
				$transaction->setSub_trans_num( $obj->sub_trans_num);
				$transaction->setPayment_processor( $obj->payment_processor);
				$transaction->setTime( $obj->time);
				$transaction->setDate( $obj->date);
				$transaction->settrans_blob( $obj->trans_blob );
				$transaction->setProduct_id( $obj->product_id );
				$transaction->setUser_id( $obj->user_id);
				$transaction->setCoupon_id( $obj->coupon_id);
				$transaction->setProduct_name( $obj->product_name );
				$transaction->setStatus( $obj->status );
	
				$transactionArray[] = $transaction;*/
				
				
			}
			
			return $transactionArray;

			/*if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$this->setId( $row["id"] );
				$this->setTrans_num( $row["trans_num"] );
				$this->setTrans_type( $row["trans_type"] );
				$this->setPayment_status( $row["payment_status"] );
				$this->setPayment_currency( $row["payment_currency"] );
				$this->setPayment_value( $row["payment_value"] );
				$this->setSub_trans_num( $row["sub_trans_num"] );
				$this->setPayment_processor( $row["payment_processor"] );
				$this->setTime( $row["time"] );
				$this->setDate( $row["date"] );
				$this->setTrans_blob( $row["trans_blob"] );
				$this->setProduct_id( $row["product_id"] );
				$this->setStatus( $row["status"] );			
			}*/
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}			
		return;
	}	
	
	function isReprocessible() {
		if($this->getStatus() == 0 ||
			$this->getStatus() == 2 ||
			$this->getStatus() == 3 ||
			$this->getStatus() == 4 
		) {
			return TRUE;
		}
		return FALSE;

	}	
	/*
		DOC: NOTE: If you modify this list, also update the array in functions_admin.php
		
		Lets you set the dap internal status on the transaction record. 
		
		0 - Init status - db default
		1 - Paypal Verified (payment processor verified)
		2 - Paypal Invalid (payment processor declined) - admin  reprocessible
		3 - Paypal Communication Error (payment processor cannot be reached) -  admin reprocessible
		4 - Misc Error - admin reprocessible
		5 - Processed successfully.
		6 - Processed ERROR.
		7 - Processed Affiliations Successfully - Final State. 
	*/
	public static function setRecordStatus($record_id, $status, $dap_dbh=NULL ) {
		logToFile("Dap_Transactions::setRecordStatus init..Record Id: $record_id, Status: $status");
		try {
			$dap_dbh = Dap_Connection::getConnection($dap_dbh);
			//if(!isset($dap_dbh) || ($dap_dbh == null)) {
			//	$dap_dbh = Dap_Connection::getConnection();
				//logToFile("Creating new connection in Dap_Transactions::create()");
			//} else {
				//logToFile("Using exisitng connecting.Dap_Transactions::create()");
			//}
		
			//$dap_dbh = Dap_Connection::getConnection();	
			$time = date("Y-m-d H:i:s");
			
			$sql = "UPDATE 
						dap_transactions 
					set 
						status = $status,
						time= '$time' 
					where 
						id = $record_id ";
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

	public static function setPayerId($record_id, $user_id, $dap_dbh=NULL ) {
		logToFile("Dap_Transactions::setPayerEmail init..Record Id: $record_id, user_id: $user_id");
		try {
			$dap_dbh = Dap_Connection::getConnection($dap_dbh);
			//if(!isset($dap_dbh) || ($dap_dbh == null)) {
			//	$dap_dbh = Dap_Connection::getConnection();
				//logToFile("Creating new connection in Dap_Transactions::create()");
			//} else {
				//logToFile("Using exisitng connecting.Dap_Transactions::create()");
			//}
		
			//$dap_dbh = Dap_Connection::getConnection();	
			$sql = "UPDATE dap_transactions set user_id = $user_id where id = $record_id ";
			logToFile("Dap_Transactions::setPayerEmail: $sql");
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

	/*
		Process a transaction without knowing if its a paypal, clickbank or other type of transaction.
		Proper record status should be set prior to calling this method.
		TODO: check record type and call appropriate processor

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
	public static function processTransaction($record_id, $dap_dbh=NULL) {
		logToFile("Processing Transaction With ID:".$record_id);
		//Lets get payer email and product id on the transaction that is this id and paypal payment status as complete and status as 1 (Paypal verified).
		$Transaction  =  Dap_Transactions::loadVerifiedPaypalTransaction($record_id, $dap_dbh ); 
		if(!isset($Transaction)) {
			logToFile("Dap_Transactions::processTransaction. No valid transaction is found.");
			return;
		}
		//Verify the price on incoming paypal is same as the one in our db. 
		$productId = $Transaction->getProduct_id();
		$transId = $Transaction->getId();
		$product  = Dap_Product::loadProduct($productId);
		logToFile("Dap_Transactions::processTransaction Product Price:".$product->getPrice().": Transaction Payment Amount:".$Transaction->getPayment_value());
		if(!isset($product)) {
		//the condition is changed to disable price check
		//if(!isset($product) ||  ($Transaction->getPayment_processor() != "CLICKBANK" && $product->getPrice() != $Transaction->getPayment_value())) {
			//mark Transaction as admin correctible error.
			Dap_Transactions::setRecordStatus($record_id, 4, $dap_dbh);
			sendAdminEmail("Error In Transaction Processing", "Product Not Found: ".$productId);
			return;
		}
		//
		if (isset($Transaction)) {
			$payment_status = $Transaction->getPayment_status();
			//Lets try to get user and see if it exists
			$user = Dap_User::loadUserByEmail($Transaction->getPayer_email());
			
			$payment_processor = $Transaction->getPayment_processor();
			
			if ($payment_processor == "PaypalPDT") {
			  $payment_processor="PAYPAL";
			}
			
			if(!isset($user) && (strstr($payment_processor,"PAYPAL") == 0)) {
				logToFile("Dap_Transactions::processTransaction :load user by their paypal email");
				$user = Dap_User::loadUserByPaypalEmail($Transaction->getPayer_email());
			}
					
			//TODO: Should we check for id > 0 or something like that ?
			if(isset($user)) {
				
				logToFile("Dap_Transactions::processTransaction :CheckingUser:".$user->getEmail());
				if($payment_status == "Completed") {
					//We have user, now try to add to user products association.
					$uid = $user->getId();
				
					
					$usersproducts = Dap_UsersProducts::addUsersProducts($uid, $Transaction->getProduct_id(), $record_id,"",$Transaction->getCoupon_id());
					if(isset($usersproducts)) {
						logToFile("added exising user to products association 1",LOG_INFO_DAP); 
						Dap_Transactions::setRecordStatus($record_id, 5, $dap_dbh);
						//SSS
					/*	logToFile("SSS 1",LOG_DEBUG_DAP); 
						Dap_UserCredits::addCredits($uid, $Transaction->getProduct_id(), $Transaction->getId(), $product->getCredits(), 0, "By Purchase");*/
					}
					
					$user = Dap_User::loadUserById($uid);	
					$credits_available =  $user->getCredits_available();
					
					if (strstr($payment_processor,"PAYPAL") == 0) {
						logToFile("Payment processor is paypal, setting payer email",LOG_INFO_DAP); 
						$user->setPaypal_email($Transaction->getPayer_email());
					}
					
					$username = $user->getUser_name();
				
					if (!isset($username) || ($username == ""))
						$user->setUser_name(NULL);
					
					parse_str($Transaction->getTrans_blob(), $list);
					
					logToFile("Payment processor is $payment_processor, setting address details before list",LOG_INFO_DAP); 
					
					if (($list == NULL) || !isset($list))
						logToFile("Dap_Transactions::LIST EMPTY"); 
						
					foreach ($list as $key => $value) {
						logToFile("Dap_Transactions::LIST DETAILS(): Key=".$key.", Value=".$value); 
					}
								
					// NEW START - 3/18
						//store shipping info in user profile 
					if (strstr($payment_processor,"PAYPAL") == 0) {
						if(array_key_exists('payment_email',$list)) {
							$paypal_email = $list["payment_email"];
							logToFile("Dap_Transactions::paypal_email=".$paypal_email); 
						}
						
						if($paypal_email!="") {
							logToFile("Dap_Transactions::set user's paypal email in user account=".$paypal_email); 
							$user->setPaypal_email($paypal_email);
						}
						else {
							$user->setPaypal_email($Transaction->getPayer_email());
						}
					}
					
					//$msg_str = "authorize.net RENEWAL notification received for user=" . $Transaction->getPayer_email() . ". DAP will now update the user record for this user=" . $payer_email;
			
				//	logToFile($error_str, LOG_DEBUG_DAP);
					//sendAdminEmail("RECD AUTHNET RENEWAL NOTIFICATION", $msg_str);
					
					if ((array_key_exists('first_name',$list)) && ($list["first_name"] != "") ) {
						$user->setFirst_name( $list["first_name"] );
						logToFile("Dap_Transactions.class: got first_name: " . $user->getFirst_name() ,LOG_INFO_DAP); 
					}
					
					if ((array_key_exists('last_name',$list)) && ($list["last_name"] != "") ) {
						$user->setLast_name( $list["last_name"] );
						logToFile("Dap_Transactions.class: got last_name: " . $user->getLast_name() ,LOG_INFO_DAP); 
					}

					if( (array_key_exists('ship_to_address1',$list)) && ($list["ship_to_address1"] != "") ) {
						$user->setAddress1( $list["ship_to_address1"] );
						logToFile("Dap_Transactions.class: shipping address1: " . $user->getAddress1() ,LOG_INFO_DAP); 
					} else if ( (array_key_exists('address1',$list)) && ($list["address1"] != "") ) {
						$user->setAddress1( $list["address1"] );
						logToFile("Dap_Transactions.class: address1: " . $user->getAddress1() ,LOG_INFO_DAP); 
					}else if ((array_key_exists('address_street',$list)) && ($list["address_street"] != "") ) {
						$user->setAddress1( $list["address_street"] );
						logToFile("Dap_Transactions.class: address_street: " . $user->getAddress1() ,LOG_INFO_DAP); 
					}
					
					
					if (  (array_key_exists('ship_to_address2',$list)) && ($list["ship_to_address2"] != "") ){
						$user->setAddress2( $list["ship_to_address2"] );
					} else if ((array_key_exists('address2',$list)) && ($list["address2"] != "") ) {
						$user->setAddress2( $list["address2"] );
					}
					
					if((array_key_exists('ship_to_city',$list)) && ($list["ship_to_city"] != "") ){
						$user->setCity( $list["ship_to_city"] );
					} else if((array_key_exists('city',$list)) && ($list["city"] != "") ){
						$user->setCity( $list["city"] );
					}  else if((array_key_exists('address_city',$list)) && ($list["address_city"] != "") ){
						$user->setCity( $list["address_city"] );
						logToFile("Dap_Transactions.class: address_city: " . $user->getCity() ,LOG_INFO_DAP); 
					}
					
					if((array_key_exists('ship_to_state',$list)) && ($list["ship_to_state"] != "") ){
						$user->setState( $list["ship_to_state"] );
					}  else if((array_key_exists('ccuststate',$list)) && ($list["ccuststate"] != "") ){
						$user->setState( $list["ccuststate"] );
					}  else if((array_key_exists('address_state',$list)) && ($list["address_state"] != "") ){
						$user->setState( $list["address_state"] );
					} else if((array_key_exists('state',$list)) && ($list["state"] != "") ){
						$user->setState( $list["state"] );
					}
					
					
					if((array_key_exists('ship_to_zip',$list)) && ($list["ship_to_zip"] != "") ){
						$user->setZip( $list["ship_to_zip"] );
					} else if((array_key_exists('zip',$list)) && ($list["zip"] != "") ){
						$user->setZip( $list["zip"] );
					}  else if((array_key_exists('address_zip',$list)) && ($list["address_zip"] != "") ){
						$user->setZip( $list["address_zip"] );
					}
					
					if((array_key_exists('ship_to_country',$list)) && ($list["ship_to_zip"] != "")){
						$user->setCountry( $list["ship_to_country"] );
					} else if((array_key_exists('country',$list)) && ($list["country"] != "") ){
						$user->setCountry( $list["country"] );
					} else if((array_key_exists('ccustcc',$list)) && ($list["ccustcc"] != "") ){
						$user->setCountry( $list["ccustcc"] );
					} else if((array_key_exists('address_country',$list)) && ($list["address_country"] != "") ){
						$user->setCountry( $list["address_country"] );
					}
					
					if(array_key_exists('phone',$list)) {
						$user->setPhone( $list["phone"] );
					} 
					if(array_key_exists('fax',$list)) {
						$user->setFax( $list["fax"] );
					} 
					if(array_key_exists('company',$list)) {
						$user->setCompany( $list["company"] );
					} 
					
					// END - 3/18
					logToFile("DAP_Transactions.class.php:  processTransaction(): credits_available=".$credits_available,LOG_INFO_DAP);
					//$user->setCredits_available($credits_available);
					try {
						$user->update();
						$response = "SUCCESS! User has been successfully updated.";
						logToFile("User's paypal email= " . $Transaction->getPayer_email() . " successfully added to their DAP record",LOG_INFO_DAP);
					} catch (PDOException $e) {
						$response = ERROR_GENERAL;
						logToFile("User's paypal email= " . $Transaction->getPayer_email() . " could not be added: Exception: " . $response,LOG_INFO_DAP);
					} catch (Exception $e) {
						$response = ERROR_GENERAL;
						logToFile("User's paypal email= " . $Transaction->getPayer_email() . " could not be added: Exception: " . $response,LOG_INFO_DAP);
					}
					
					
					return $usersproducts;		
				}
				if($payment_status == "Refund") {
					//We have refund, try to negate stuff.
					//
					$uid = $user->getId();
					$retval = Dap_UsersProducts::removeUsersProducts($uid, $Transaction->getProduct_id(), $record_id, FALSE, $dap_dbh);
					if($retval == TRUE) {
						Dap_Transactions::setRecordStatus($record_id, 5, $dap_dbh);
					}
					//TODO: REVIEW THIS AND SEE IF WE SHOULD RETURN ANYTHING IN SUCCESS/FAILURE SCENARIOS.
					return;		
				}
			}
			logToFile("Dap_Transactions::processTransaction :CheckingUser: New User");
			//no user and this is refund. 
			// TODO: IS IT POSSIBLE THAT USER IS INACTIVE AND REFUND IS COMING IN.
			// for now, return nothing and do nothing.
			//if($payment_status == "Refund") return;
			//we dont have user. 
			//TODO: FIX THIS TO CREATE NEW USER AND RETRY ADD USER TO PRODUCT.
			//lets create a user object and then pass it to createUser.
			parse_str($Transaction->getTrans_blob(), $list);
			logToFile("Dap_Transactions::processTransaction :TransBlob".$Transaction->getTrans_blob());
			//lets make sure required variables are set.  
			// Required are first name and email.
			$user = new Dap_User();
			logToFile("Dap_Transactions::processTransaction :CheckingUser: New User, FirstName:".$list['first_name'].":".$list['cname']);
			//see if its paypal IPN, CB call back or other email parsed data.
			
			
			//store shipping info in user profile 
			if(array_key_exists('ship_to_first_name',$list) && ($list["ship_to_first_name"] != "")) {
				$user->setFirst_name( $list["ship_to_first_name"] );
				$user->setLast_name( $list["ship_to_last_name"] );
			}
			
			logToFile("address_street: " . $list["address1"]  ,LOG_INFO_DAP); 
			logToFile("city: " . $list["city"]  ,LOG_INFO_DAP); 
			logToFile("state: " . $list["state"]  ,LOG_INFO_DAP); 
			logToFile("zip: " . $list["zip"]  ,LOG_INFO_DAP); 
			
			if(array_key_exists('first_name',$list) && ($list["first_name"] != "")) {
				$user->setFirst_name( $list["first_name"] );
				$user->setLast_name( $list["last_name"] );
			} else if(array_key_exists('cname',$list) && ($list["cname"] != "")) {
					
				$arr_id=explode(" ",$list["cname"]);
				logToFile("cname decode=" . $arr_id["0"] ,LOG_INFO_DAP); 
				
				if(isset($arr_id["0"]))
					$user->setFirst_name( $arr_id["0"] );
				if(isset($arr_id["1"]))
					$user->setLast_name( $arr_id["1"] );
				else {
					$user->setFirst_name( $list["cname"] );
					$user->setLast_name( $list["cname"] );
				}
			} else if(array_key_exists('ccustname',$list) && ($list["ccustname"] != "")) {
				$pattern = "[{ }-]";
				$arr_id=split($pattern ,$list['ccustname']);
				$user->setFirst_name( $arr_id["0"] );
				$user->setLast_name( $arr_id["1"] );
			}
			
			if(array_key_exists('ship_to_address1',$list) && ($list["ship_to_address1"] != "")) {
				$user->setAddress1( $list["ship_to_address1"] );
			} else if (array_key_exists("address1",$list) && ($list["address1"] != "")) {
				$address1=$list["address1"];
				$user->setAddress1( $address1 );
				logToFile("address IS: " . $address1 ,LOG_INFO_DAP); 
			}else if (array_key_exists('address_street',$list)) {
				$user->setAddress1( $list["address_street"] );
				logToFile("address_street: " . $user->getAddress1() ,LOG_INFO_DAP); 
			}
			
			if(array_key_exists('ship_to_address2',$list) && ($list["ship_to_address2"] != "")) {
				$user->setAddress2( $list["ship_to_address2"] );
			} else if (array_key_exists('address2',$list) && ($list["address2"] != "")) {
				logToFile("address: " . $list["address2"]  ,LOG_INFO_DAP); 
				$user->setAddress2( $list["address2"] );
			}
							
			if(array_key_exists('ship_to_city',$list) && ($list["ship_to_city"] != "")) {
				$user->setCity( $list["ship_to_city"] );
			} else if(array_key_exists('city',$list) && ($list["city"] != "")) {
				$user->setCity( $list["city"] );
				logToFile("address: " . $list["city"]  ,LOG_INFO_DAP); 
			} else if(array_key_exists('address_city',$list)) {
				$user->setCity( $list["address_city"] );
			}
				
			if(array_key_exists('ship_to_state',$list) && ($list["ship_to_state"] != "")) {
				$user->setState( $list["ship_to_state"] );
			} else if(array_key_exists('state',$list) && ($list["state"] != "")) {
				$user->setState( $list["state"] );
			} else if(array_key_exists('ccuststate',$list) && ($list["ccuststate"] != "")) {
				$user->setState( $list["ccuststate"] );
			} else if(array_key_exists('address_state',$list)) {
				$user->setState( $list["address_state"] );
			}
						
			logToFile("state: " . $user->getCity() ,LOG_INFO_DAP); 
				
			if(array_key_exists('ship_to_zip',$list) && ($list["ship_to_zip"] != "")) {
				$user->setZip( $list["ship_to_zip"] );
			} else if(array_key_exists('zip',$list) && ($list["zip"] != "")) {
				$user->setZip( $list["zip"] );
			} else if(array_key_exists('address_zip',$list)) {
				$user->setZip( $list["address_zip"] );
			}
						 
			
			if(array_key_exists('ship_to_country',$list) && ($list["ship_to_country"] != "")) {
				$user->setCountry( $list["ship_to_country"] );
			} else if(array_key_exists('country',$list) && ($list["country"] != "")) {
				$user->setCountry( $list["country"] );
			} else if(array_key_exists('ccustcc',$list) && ($list["ccustcc"] != "")) {
				$user->setCountry( $list["ccustcc"] );
			} else if(array_key_exists('address_country',$list)) {
				$user->setCountry( $list["address_country"] );
			}
						
			
			if(array_key_exists('phone',$list)) {
				$user->setPhone( $list["phone"] );
			} 
			if(array_key_exists('fax',$list)) {
				$user->setFax( $list["fax"] );
			} 
			if(array_key_exists('company',$list)) {
				$user->setCompany( $list["company"] );
			} 
					
			$user->setEmail( $Transaction->getPayer_email() );	
			
			if (strstr($payment_processor,"PAYPAL") == 0) 
				$user->setPaypal_email( $Transaction->getPayer_email() );		
			
			logToFile("phone: " . $user->getPhone() ,LOG_INFO_DAP); 
				
			$user->setStatus("A");
			
			//$user->setUser_name(NULL);
			$email=$user->getEmail();
			$firstname=$user->getFirst_name();
			$lastname=$user->getLast_name();
			$uname=generateUsername("Dap_Transactions::processTransaction: ",$email,$firstname,$lastname);
			if($uname=="") {
				$uname=NULL;
				logToFile("Dap_Transactions::processTransaction: username set to NULL",LOG_INFO_DAP);
			}
			else 
				logToFile("Dap_Transactions::processTransaction: username set to".$uname,LOG_INFO_DAP);
				
			$user->setUser_name($uname);
			
			$user->create();
			
			
			//TODO : POPULATE MORE ITEMS HERE, IF POSSIBLE.
			logToFile("Dap_Transactions::processTransaction :NewUser: UID:".$user->getId());
			if(isset($user)) {
				
				$user = Dap_User::loadUserByEmail($Transaction->getPayer_email());
			    logToFile("address1 after re-read: " . $user->getAddress1() ,LOG_INFO_DAP); 
				
				//We have user, now try to add to user products association.
				if($payment_status == "Completed") {
					//We have user, now try to add to user products association.
					$uid = $user->getId();
					$usersproducts = Dap_UsersProducts::addUsersProducts($uid, $Transaction->getProduct_id(), $record_id, "", $Transaction->getCoupon_id());
					logToFile("added new user to products association 1",LOG_INFO_DAP); 
					if(isset($usersproducts)) {
						Dap_Transactions::setRecordStatus($record_id, 5, $dap_dbh);
						$productId = $Transaction->getProduct_id();
						$product  = Dap_Product::loadProduct($productId);
					/*	
						//SSS
						if (($product->getCredits() > 0) && ($product->getSelf_service_allowed() == "Y")) {
							logToFile("SSS 2",LOG_DEBUG_DAP); 
							Dap_UserCredits::addCredits($uid, $Transaction->getProduct_id(), $Transaction->getId(), $product->getCredits(), 0, "By Purchase");
						}*/
					}
					return $usersproducts;		
				}
				if($payment_status == "Refund") {
					//We have refund, try to negate stuff.
					$uid = $user->getId();
					$retval = removeUsersProducts($uid, $Transaction->getProduct_id(), $record_id);
					if($retval == TRUE) {
						Dap_Transactions::setRecordStatus($record_id, 5, $dap_dbh);
					}
					//TODO: REVIEW THIS AND SEE IF WE SHOULD RETURN ANYTHING IN SUCCESS/FAILURE SCENARIOS.
					return;		
				}
			}
		}
	
	}
	
	function create($dap_dbh=NULL) {
		try {
			$dap_dbh = Dap_Connection::getConnection($dap_dbh);
			//lets do the insert and get the newly created  id out.
			$sql = "INSERT into 
						dap_transactions 
							(trans_num, 
							trans_type, 
							payment_status, 
							payment_currency, 
							payment_value,
							payer_email, 
							sub_trans_num,
							payment_processor, 
							time, 
							date, 
							trans_blob, 
							product_id, 
							user_id, 
							coupon_id) 
						values 
							(:trans_num ,
							 :trans_type, 
							 :payment_status,
							 :payment_currency,
							 :payment_value,
							 :payer_email,
							 :sub_trans_num,
							 :payment_processor, 
							 :time,
							 :date,
							 :trans_blob,
							 :product_id,
							 :user_id,
							 :coupon_id)";
			logToFile("Dap_Transactions:create(). SQL:".$sql); 
			
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':trans_num', $this->getTrans_num(), PDO::PARAM_STR);
			$stmt->bindParam(':trans_type', $this->getTrans_type(), PDO::PARAM_STR);
			$stmt->bindParam(':payment_status', $this->getPayment_status(), PDO::PARAM_STR);
			$stmt->bindParam(':payment_currency', $this->getPayment_currency(), PDO::PARAM_STR);
			$stmt->bindParam(':payment_value', $this->getPayment_value(), PDO::PARAM_STR);
			$stmt->bindParam(':payer_email', $this->getPayer_email(), PDO::PARAM_STR);
			$stmt->bindParam(':sub_trans_num', $this->getSub_trans_num(), PDO::PARAM_STR);
			$stmt->bindParam(':payment_processor', $this->getPayment_processor(), PDO::PARAM_STR);
			$stmt->bindParam(':time', date("Y-m-d H:i:s"), PDO::PARAM_STR);
			$stmt->bindParam(':date', date("Y-m-d"), PDO::PARAM_STR);
			$stmt->bindParam(':trans_blob', $this->getTrans_blob(), PDO::PARAM_STR);
			$stmt->bindParam(':product_id', $this->getProduct_id(), PDO::PARAM_INT);
			$stmt->bindParam(':user_id', $this->getUser_id(), PDO::PARAM_INT);
			$stmt->bindParam(':coupon_id', $this->getCoupon_id(), PDO::PARAM_INT);
			$stmt->execute();
			logToFile("Dap_Transactions:create(). Insert into transactions Done.."); 
			$new_id = $dap_dbh->lastInsertId();
			$this->setId($new_id);
			$stmt = null;
			//$dap_dbh = null;
			return $new_id;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}

	public function updateBlob() {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$sql = "update dap_transactions
					set
						trans_blob = :trans_blob
					where
						id = :id
					";
					
			logToFile($sql,LOG_DEBUG_DAP);
			logToFile("TransactionId:$this->getId()",LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $this->getId(), PDO::PARAM_INT);
			//$stmt->bindParam(':trans_num', $this->getTrans_num, PDO::PARAM_STR);
			//$stmt->bindParam(':trans_type', $this->getTrans_type, PDO::PARAM_STR);
			//$stmt->bindParam(':payment_status', $this->getPayment_status, PDO::PARAM_STR);
			//$stmt->bindParam(':payment_currency', $this->getPayment_currency, PDO::PARAM_STR);
			//$stmt->bindParam(':payment_value', $this->getPayment_value(), PDO::PARAM_STR);
			$stmt->bindParam(':trans_blob', $this->getTrans_blob(), PDO::PARAM_STR);
			//$stmt->bindParam(':sub_trans_num', $this->getSub_trans_num, PDO::PARAM_STR);
			//$stmt->bindParam(':payment_processor', $this->getPayment_processor, PDO::PARAM_STR);
			//$stmt->bindParam(':time', $this->getTime, PDO::PARAM_STR);
			//$stmt->bindParam(':date', $this->getDate, PDO::PARAM_STR);
			//$stmt->bindParam(':trans_blob', $this->getTrans_blob, PDO::PARAM_STR);
			//$stmt->bindParam(':product_id', $this->getProduct_id(), PDO::PARAM_INT);
			//$stmt->bindParam(':status', $this->getStatus, PDO::PARAM_STR);
			$stmt->execute();

		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}			
		return;
	}	
	


	public function update() {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$sql = "update dap_transactions
					set
						payment_value = :payment_value,
						payer_email = :payer_email,
						product_id = :product_id
					where
						id = :id
					";
					
			logToFile($sql,LOG_DEBUG_DAP);
			logToFile("TransactionId:$this->getId()",LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $this->getId(), PDO::PARAM_INT);
			//$stmt->bindParam(':trans_num', $this->getTrans_num, PDO::PARAM_STR);
			//$stmt->bindParam(':trans_type', $this->getTrans_type, PDO::PARAM_STR);
			//$stmt->bindParam(':payment_status', $this->getPayment_status, PDO::PARAM_STR);
			//$stmt->bindParam(':payment_currency', $this->getPayment_currency, PDO::PARAM_STR);
			$stmt->bindParam(':payment_value', $this->getPayment_value(), PDO::PARAM_STR);
			$stmt->bindParam(':payer_email', $this->getPayer_email(), PDO::PARAM_STR);
			//$stmt->bindParam(':sub_trans_num', $this->getSub_trans_num, PDO::PARAM_STR);
			//$stmt->bindParam(':payment_processor', $this->getPayment_processor, PDO::PARAM_STR);
			//$stmt->bindParam(':time', $this->getTime, PDO::PARAM_STR);
			//$stmt->bindParam(':date', $this->getDate, PDO::PARAM_STR);
			//$stmt->bindParam(':trans_blob', $this->getTrans_blob, PDO::PARAM_STR);
			$stmt->bindParam(':product_id', $this->getProduct_id(), PDO::PARAM_INT);
			//$stmt->bindParam(':status', $this->getStatus, PDO::PARAM_STR);
			$stmt->execute();
			if($this->isReprocessible()) { 
		 		Dap_Transactions::setRecordStatus($this->getId(),1); 
				Dap_Transactions::processTransaction($this->getId());
			}
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}			
		return;
	}	
	
	/*
		Create refund transaction by copying the original transation in, 
		everything except the following
		- trans id - new trans id
		- trans num - take trans_num of original trans and append -R
		- sub_trans_num - put in the original trans_num
		- payment_status - Refund.
		- time - now()
		- date - now()
		- status - init/verified status
	*/
	public static function negate($trans_id) {
		$dap_dbh = null;
		if(!isset($trans_id)) return;		
		$transaction = Dap_Transactions::load($trans_id);
		if(!isset($transaction)) return;
		//handle trans_num
		$trans_num = $transaction->getTrans_num();
		$sub_trans_num = $trans_num;
		$trans_num = $trans_num . "-R";	
		//handle amount
		$amount = $transaction->getPayment_value();
		$amount = 0-$amount;
		$new_id = null;
			
		//lets set variables
		$transaction->setTrans_num($trans_num);
		$transaction->setSub_trans_num($sub_trans_num);
		//TODO: negate of refund will still end up with Refund. may be we should
		// mark as complete if refund is refunded ?
		$transaction->setPayment_status("Refund");
		$transaction->setPayment_value($amount);	
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$dap_dbh->beginTransaction(); //begin the transaction
		
			$new_id = $transaction->create($dap_dbh);
			//TODO - create trans cross reference, if we need it	
			if(isset($new_id)) {
				//set init status
				Dap_Transactions::setRecordStatus($new_id, 1, $dap_dbh);
				// process transaction - removeUsersProducts
				try {
					Dap_Transactions::processTransaction($new_id, $dap_dbh);
				} catch (Exception $e2) {
					//ignore this one, as it should only happen when 'removing' the
					// user-product fails becuase 
					logToFile($e2->getMessage(),LOG_FATAL_DAP);
				}
				// set the status such that aff commission isnt processed by cron again. 
				Dap_Transactions::setRecordStatus($new_id, 7, $dap_dbh);
				// negate the affiliate commission, if any
				logToFile("before negate",LOG_DEBUG_DAP); 
				Dap_AffCommissions::negateSaleEarning($trans_id, $new_id, $dap_dbh);
			}
			$dap_dbh->commit();
			logToFile("new id: $new_id",LOG_DEBUG_DAP); 
			return $new_id;
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
	
	public static function loadVerifiedPaypalTransaction($record_id, $dap_dbh=NULL) {
		try {
			$dap_dbh = Dap_Connection::getConnection($dap_dbh);
			$sql = "select *
					from dap_transactions
					where 
					id =:id and
					status = 1 and
					payment_status in ('Completed', 'Refund') and
					trans_type != 'subscr_signup'
					";
		//echo "sql: $sql<br>"; exit;
			logToFile($sql); 
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $record_id, PDO::PARAM_INT);	
			$stmt->execute();	
			//Statements to build the links
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$Transaction = new Dap_Transactions();
				$Transaction->setId( $row["id"] );
				$Transaction->setTrans_num( $row["trans_num"] );
				$Transaction->setTrans_type( $row["trans_type"] );
				$Transaction->setPayment_status( $row["payment_status"] );
				$Transaction->setPayment_currency( $row["payment_currency"] );
				$Transaction->setPayment_value( $row["payment_value"] );
				$Transaction->setPayer_email( $row["payer_email"] );
				$Transaction->setSub_trans_num( $row["sub_trans_num"] );
				$Transaction->setPayment_processor( $row["payment_processor"] );
				$Transaction->setTime( $row["time"] );
				$Transaction->setDate( $row["date"] );
				$Transaction->setTrans_blob( $row["trans_blob"] );
				$Transaction->setProduct_id( $row["product_id"] );
				$Transaction->setUser_id( $row["user_id"] );
				$Transaction->setCoupon_id( $row["coupon_id"] );
				$Transaction->setStatus( $row["status"] );	
				//
				logToFile("loadVerifiedPaypalTransaction:Returning Transaction:".$Transaction->getId());
				return $Transaction;
			}
			return; 
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
		logToFile("Returning Transaction: ZERO");
		return;
	}	

	public static function load($record_id) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$sql = "select *
					from dap_transactions
					where 
					id =:id
					";
			logToFile($sql); 
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $record_id, PDO::PARAM_INT);	
			$stmt->execute();	
			//Statements to build the links
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$Transaction = new Dap_Transactions();
				$Transaction->setId( $row["id"] );
				$Transaction->setTrans_num( $row["trans_num"] );
				$Transaction->setTrans_type( $row["trans_type"] );
				$Transaction->setPayment_status( $row["payment_status"] );
				$Transaction->setPayment_currency( $row["payment_currency"] );
				$Transaction->setPayment_value( $row["payment_value"] );
				$Transaction->setPayer_email( $row["payer_email"] );
				$Transaction->setSub_trans_num( $row["sub_trans_num"] );
				$Transaction->setPayment_processor( $row["payment_processor"] );
				$Transaction->setTime( $row["time"] );
				$Transaction->setDate( $row["date"] );
				$Transaction->setTrans_blob( $row["trans_blob"] );
				$Transaction->setProduct_id( $row["product_id"] );
				$Transaction->setUser_id( $row["user_id"] );
				$Transaction->setCoupon_id( $row["coupon_id"] );
				$Transaction->setStatus( $row["status"] );	
				//
				logToFile("load:Returning Transaction:".$Transaction->getId());
				return $Transaction;
			}
			return; 
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
		logToFile("Returning Transaction: ZERO");
		return;
	}	


	public static function updateEmail($oldEmail,$newEmail) {
		try {
			logToFile("in Dap_Transactions::updateEmail - $oldEmail , $newEmail"); 
			$dap_dbh = Dap_Connection::getConnection();
			$sql = "update dap_transactions
					set
						payer_email = :newEmail
					where
						payer_email = :oldEmail
					";
					
			logToFile($sql,LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':oldEmail', $oldEmail, PDO::PARAM_STR);
			$stmt->bindParam(':newEmail', $newEmail, PDO::PARAM_STR);
			$stmt->execute();
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null;
			return;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}			
		return;
	}	
	
	public function updateTrans() {
		try {
			
			logToFile("Dap_Transactions.class.php: updateTrans(): TransactionId:$this->getId()",LOG_DEBUG_DAP);
			logToFile("paymentStatus:$this->getPayment_status()",LOG_DEBUG_DAP);
			
			$dap_dbh = Dap_Connection::getConnection();
			$sql = "update dap_transactions
					set
						payment_status = :payment_status,
						trans_blob = :trans_blob
					where
						id = :id
					";
					
			logToFile($sql,LOG_DEBUG_DAP);
			
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $this->getId(), PDO::PARAM_INT);
			$stmt->bindParam(':payment_status',$this->getPayment_status(), PDO::PARAM_STR);
			$stmt->bindParam(':trans_blob', $this->getTrans_blob(), PDO::PARAM_STR);
			
			$stmt->execute();
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null;
			return;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}			
		return;
	}

}
?>
