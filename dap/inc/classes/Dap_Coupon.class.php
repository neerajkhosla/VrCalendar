<?php

class Dap_Coupon {
   var $id;
   var $code;
  	var $description;
  	var $start_date;
 	var $end_date;
	var $discount_amt;
	var $discount_rate;
	var $max_usage;
   var $actual_usage;
	
	function getId() {
		return $this->id;
	}
	function setId($o) {
		$this->id = $o;
	}

	function getCode() {
		return $this->code;
	}
	function setCode($o) {
		$this->code = $o;
	}

	function getDescription() {
		return $this->description;
	}
	function setDescription($o) {
		$this->description = $o;
	}

	function getStart_date() {
		return $this->start_date;
	}
	function setStart_date($o) {
		$this->start_date = $o;
	}

	function getEnd_date() {
		return $this->end_date;
	}
	function setEnd_date($o) {
		$this->end_date = $o;
	}

	function getDiscount_amt() {
		return $this->discount_amt;
	}
	function setDiscount_amt($o) {
		$this->discount_amt = $o;
	}
	
	function getRecurringDiscount_amt() {
		return $this->recurring_discount_amt;
	}
	function setRecurringDiscount_amt($o) {
		$this->recurring_discount_amt = $o;
	}
		
	function getDiscount_rate() {
		return $this->discount_rate;
	}
	function setDiscount_rate($o) {
		$this->discount_rate = $o;
	}
		
	function getMax_usage() {
		return $this->max_usage;
	}
	function setMax_usage($o) {
		$this->max_usage = $o;
	}
	
	function getActual_usage() {
		return $this->actual_usage;
	}
	function setActual_usage($o) {
		$this->actual_usage = $o;
	}

	public function create() {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$price = "0.00"; //We don't care about price at this time

			$sql = "insert into dap_coupons
						(code, description, start_date, end_date, discount_amt, recurring_discount_amt, discount_rate, max_usage, actual_usage)
					values
						(:code, :description, :start_date, :end_date, :discount_amt, :recurring_discount_amt, :discount_rate, :max_usage, :actual_usage)";

			$stmt = $dap_dbh->prepare($sql);
			
			$stmt->bindParam(':code', $this->getCode(), PDO::PARAM_STR);
			$stmt->bindParam(':description', $this->getDescription(), PDO::PARAM_STR);
			$stmt->bindParam(':start_date', $this->getStart_date(), PDO::PARAM_STR);
			$stmt->bindParam(':end_date', $this->getEnd_date(), PDO::PARAM_STR);
			$stmt->bindParam(':discount_amt', $this->getDiscount_amt(), PDO::PARAM_STR);
			$stmt->bindParam(':recurring_discount_amt', $this->getRecurringDiscount_amt(), PDO::PARAM_STR);
			$stmt->bindParam(':discount_rate', $this->getDiscount_rate(), PDO::PARAM_STR);
			$stmt->bindParam(':max_usage', $this->getMax_usage(), PDO::PARAM_INT);
			$stmt->bindParam(':actual_usage', $this->getActual_usage(), PDO::PARAM_INT);
		
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

	public function update() {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			
			logToFile("Dap_coupon.class.php: discount_amt=". $this->getDiscount_amt(),LOG_DEBUG_DAP);
			logToFile("Dap_coupon.class.php: recurring_discount_amt=". $this->getRecurringDiscount_amt(),LOG_DEBUG_DAP);
	
				
				$sql = "update dap_coupons set
							code = :code,
							description = :description,
							start_date = :start_date,
							end_date = :end_date,
							discount_amt = :discount_amt,
							recurring_discount_amt = :recurring_discount_amt,
							discount_rate = :discount_rate,
							max_usage = :max_usage,
							actual_usage = :actual_usage
						where id = :couponId";

			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':code', $this->getCode(), PDO::PARAM_STR);
			$stmt->bindParam(':description', $this->getDescription(), PDO::PARAM_STR);
			$stmt->bindParam(':start_date', $this->getStart_date(), PDO::PARAM_STR);
			$stmt->bindParam(':end_date', $this->getEnd_date(), PDO::PARAM_STR);
			$stmt->bindParam(':discount_amt', $this->getDiscount_amt(), PDO::PARAM_STR);
			$stmt->bindParam(':recurring_discount_amt', $this->getRecurringDiscount_amt(), PDO::PARAM_STR);
			$stmt->bindParam(':discount_rate', $this->getDiscount_rate(), PDO::PARAM_STR);
			$stmt->bindParam(':max_usage', $this->getMax_usage(), PDO::PARAM_INT);
			$stmt->bindParam(':actual_usage', $this->getActual_usage(), PDO::PARAM_INT);
			$stmt->bindParam(':couponId', $this->getId(), PDO::PARAM_INT);

			$stmt->execute();
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
	}
	
	public function updateUsage($actual_usage) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			
			logToFile("Dap_coupon.class.php: couponId=". $this->getId(),LOG_DEBUG_DAP);
			logToFile("Dap_coupon.class.php: actual_usage=". $this->getActual_usage(),LOG_DEBUG_DAP);
			
				
				$sql = "update dap_coupons 
						set actual_usage = :actual_usage
						where id = :couponId";

			$stmt = $dap_dbh->prepare($sql);
			
			$stmt->bindParam(':actual_usage', $actual_usage, PDO::PARAM_INT);
			$stmt->bindParam(':couponId', $this->getId(), PDO::PARAM_INT);

			$stmt->execute();
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
	}

	public static function loadCoupon($couponId) {
		$dap_dbh = Dap_Connection::getConnection();
		$coupon = null;

		//Load coupon details from database
		$sql = "select *
			from
				dap_coupons
			where
				id = :couponId";

		$stmt = $dap_dbh->prepare($sql);
		$stmt->bindParam(':couponId', $couponId, PDO::PARAM_STR);
		$stmt->execute();

		//echo "sql: $sql<br>"; exit;
		//$result = mysql_query($sql);
		//echo "rows returned: " . mysql_num_rows($result) . "<br>";

			
		if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$coupon = new Dap_Coupon();
			$coupon->setId( $row["id"] );
			$coupon->setCode( stripslashes($row["code"]) );
			$coupon->setDescription( stripslashes($row["description"]) );
			$coupon->setStart_date( stripslashes($row["start_date"]) );
			$coupon->setEnd_date( stripslashes($row["end_date"]) );
			$coupon->setDiscount_amt( $row["discount_amt"] );
			$coupon->setRecurringDiscount_amt( $row["recurring_discount_amt"] );
			$coupon->setDiscount_rate( $row["discount_rate"] );
			$coupon->setMax_usage( $row["max_usage"] );
			$coupon->setActual_usage( $row["actual_usage"] );
		}

		return $coupon;
	}
	
	public static function isExists($couponId) {
		$dap_dbh = Dap_Connection::getConnection();

		//Load coupon details from database
		$sql = "select id
			from
				dap_coupons
			where
				id = :couponId";

		$stmt = $dap_dbh->prepare($sql);
		$stmt->bindParam(':couponId', $couponId, PDO::PARAM_STR);
		$stmt->execute();

		//echo "sql: $sql<br>"; exit;
		//$result = mysql_query($sql);
		//echo "rows returned: " . mysql_num_rows($result) . "<br>";

		if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		  return TRUE;
		}

		return FALSE;
	}	

	public static function loadCouponByCode($couponCode) {
		$dap_dbh = Dap_Connection::getConnection();

		//Load coupon details from database
		$sql = "select *
			from
				dap_coupons
			where
				code = :couponCode";

		$stmt = $dap_dbh->prepare($sql);
		$stmt->bindParam(':couponCode', $couponCode, PDO::PARAM_STR);
		$stmt->execute();

		//echo "sql: $sql<br>"; exit;
		//$result = mysql_query($sql);
		//echo "rows returned: " . mysql_num_rows($result) . "<br>";

		if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$coupon = new Dap_Coupon();
			$coupon->setId( $row["id"] );
			$coupon->setCode( stripslashes($row["code"]) );
			$coupon->setDescription( stripslashes($row["description"]) );
			$coupon->setStart_date( stripslashes($row["start_date"]) );
			$coupon->setEnd_date( stripslashes($row["end_date"]) );
			$coupon->setDiscount_amt( $row["discount_amt"] );
			$coupon->setRecurringDiscount_amt( $row["recurring_discount_amt"] );
			$coupon->setDiscount_rate( $row["discount_rate"] );
			$coupon->setMax_usage( $row["max_usage"] );
			$coupon->setActual_usage( $row["actual_usage"] );
			
			return $coupon;
		}

		return;
	}

	//Load coupons matching filter criteria
	public static function loadCoupons($couponFilter) {
		$CouponsList = array();

		if(trim($couponFilter) == "") {
			$sql = "select * from dap_coupons";
		} else {
			$sql = "select * from dap_coupons
					where
						(id = '$couponFilter' or
						code like '%$couponFilter%' or
						description like '%$couponFilter%' or
						start_date like '%$couponFilter%' or
						end_date like '%$couponFilter%')";
		}

		try {
			$dap_dbh = Dap_Connection::getConnection();
			$stmt = $dap_dbh->prepare($sql);
			$stmt->execute();

			while ($row = $stmt->fetch()) {
				$coupon = new Dap_Coupon();

				$coupon->setId( $row["id"] );
				$coupon->setCode( stripslashes($row["code"]) );
				$coupon->setDescription( stripslashes($row["description"]) );
				$coupon->setStart_date( stripslashes($row["start_date"]) );
				$coupon->setEnd_date( stripslashes($row["end_date"]) );
				$coupon->setDiscount_amt( $row["discount_amt"] );
				$coupon->setRecurringDiscount_amt( $row["recurring_discount_amt"] );
				$coupon->setDiscount_rate( $row["discount_rate"] );
				$coupon->setMax_usage( $row["max_usage"] );
				$coupon->setActual_usage( $row["actual_usage"] );
							
				$CouponsList[] = $coupon;
			}

			return $CouponsList;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}

	public static function deleteCoupon($id) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$dap_dbh->beginTransaction(); //begin the transaction
			$response = "SUCCESS! Coupon $couponId was deleted from the database";
			$count = 0;

			//Check if there are any users associated with this coupon
			$sql = "select count(*) as count from dap_users_coupons_jn where coupon_id = :id";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();

			if ($row = $stmt->fetch()) {
				$count = $row["count"];
				if($count > 0) {
					return "There are Users associated with this Coupon. <br/>Remove them first before you can delete this Coupon.";
				}
			}

			
			//If none, then delete from dap_users_coupons_jn table
			$sql = "delete from dap_users_coupons_jn where coupon_id = :id";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();

			$sql = "delete from dap_coupons where id = :id";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();

			$dap_dbh->commit(); //commit the transaction
			$dap_dbh = null;

			return $response;

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

	public static function getMinCId() {
		$dap_dbh = Dap_Connection::getConnection();
		$id = 0;
		
		$sql = "select 
					min(id) as id
				from
					dap_coupons";
					
		$stmt = $dap_dbh->prepare($sql);
		$stmt->execute();

		if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$id = $row["id"];
		}

		return $id;
	}

}
?>