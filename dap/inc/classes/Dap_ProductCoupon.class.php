<?php

class Dap_ProductCoupon {
   var $id;
   var $coupon_id;
  	var $product_id;
 
	function getId() {
		return $this->id;
	}
	function setId($o) {
		$this->id = $o;
	}

	function getCoupon_id() {
		return $this->coupon_id;
	}
	function setCoupon_id($o) {
		$this->coupon_id = $o;
	}

	function getProduct_id() {
		return $this->product_id;
	}
	function setProduct_id($o) {
		$this->product_id = $o;
	}
	
	public function create($productId, $couponId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			
			$sql = "insert into dap_products_coupons_jn
						(product_id, coupon_id)
					values
						(:product_id, :coupon_id)";

			$stmt = $dap_dbh->prepare($sql);
			
			//logToFile("Dap_ProductCoupon.class.php. coupon_id=" . $couponID,LOG_DEBUG_DAP);
			
			$stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
			$stmt->bindParam(':coupon_id', $couponId, PDO::PARAM_INT);
			
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

	public static function update($productId, $couponId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			
			$sql = "update dap_products_coupons set
							product_id = :product_id,
							coupon_id = :coupon_id							
						where id = :id";

			$stmt = $dap_dbh->prepare($sql);
			
			$stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
			$stmt->bindParam(':coupon_id', $couponId, PDO::PARAM_INT);
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

	public static function findCouponIdAndProductId ($productId, $couponId) {
		$dap_dbh = Dap_Connection::getConnection();

		try {
			//Load coupon details from database
			$sql = "select *
				from
					dap_products_coupons_jn
					where 
							product_id=:productId and
							coupon_id=:couponId";
	
			$stmt = $dap_dbh->prepare($sql);
			
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			$stmt->bindParam(':couponId', $couponId, PDO::PARAM_INT);
			
			$stmt->execute();
	
			//echo "sql: $sql<br>"; exit;
			//$result = mysql_query($sql);
			//echo "rows returned: " . mysql_num_rows($result) . "<br>";
	
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$productcoupon = new Dap_ProductCoupon();
				$productcoupon->setId( $row["id"] );
				$productcoupon->setCoupon_id( stripslashes($row["coupon_id"]) );
				$productcoupon->setProduct_id( stripslashes($row["product_id"]) );
			
				return $productcoupon;
			}
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
		
		return NULL;
	}

	public static function loadProductCoupon () {
		try {
		
			$dap_dbh = Dap_Connection::getConnection();
			$productCouponArray = array();
	
			$sql = "select * from dap_products_coupons_jn where product_id in (select id from dap_products) order by product_id, coupon_id";

	
			//logToFile($sql,LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			
			$stmt->execute();	
			
			while ($obj = $stmt->fetch()) {
				$productCouponArray[] = $obj;
			}
	
			return $productCouponArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}
	

	/*public static function loadCouponsByProductId ($productId) {
		try {
		
			$dap_dbh = Dap_Connection::getConnection();
			$productCouponArray = array();
	
			$sql = "select *
			from
				dap_products_coupons_jn
				where product_id=:productId";

	
			//logToFile($sql,LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':productId', $productId, PDO::PARAM_INT);
			
			$stmt = $dap_dbh->prepare($sql);
			$stmt->execute();	
			
			while ($obj = $stmt->fetch()) {
				$productCouponArray[] = $obj;
			}
	
			return $productCouponArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}*/
	
		//Delete
	public static function removeProductCouponAssociation($id) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			//delete from usersproducts table
			$sql = "delete from  
					dap_products_coupons_jn
					where id =:id";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			$stmt = null;
			$dap_dbh = null;		
			return;			
		} catch (PDOException $e) {
			$stmt = null;
			//$dap_dbh = null;				
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			$stmt = null;
			//$dap_dbh = null;			
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}	
	
	
}
?>