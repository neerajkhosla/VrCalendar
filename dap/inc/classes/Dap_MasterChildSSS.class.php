<?php

class Dap_MasterChildSSS {
   var $id;
   var $master_product_id;
  	var $child_product_id;
 
	function getId() {
		return $this->id;
	}
	function setId($o) {
		$this->id = $o;
	}

	function getMaster_product_id() {
		return $this->master_product_id;
	}
	function setMaster_product_id($o) {
		$this->master_product_id = $o;
	}

	function getChild_product_id() {
		return $this->child_product_id;
	}
	function setChild_product_id($o) {
		$this->child_product_id = $o;
	}
	
	public function create($childProductId, $masterProductId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			
			$sql = "insert into dap_products_mc_sss_jn
						(child_product_id, master_product_id)
					values
						(:child_product_id, :master_product_id)";

			$stmt = $dap_dbh->prepare($sql);
			
			//logToFile("Dap_MasterChildSSS.class.php. master_product_id=" . $couponID,LOG_DEBUG_DAP);
			
			$stmt->bindParam(':child_product_id', $childProductId, PDO::PARAM_INT);
			$stmt->bindParam(':master_product_id', $masterProductId, PDO::PARAM_INT);
			
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

	public static function update($childProductId, $masterProductId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			
			$sql = "update dap_products_coupons set
							child_product_id = :child_product_id,
							master_product_id = :master_product_id							
						where id = :id";

			$stmt = $dap_dbh->prepare($sql);
			
			$stmt->bindParam(':child_product_id', $childProductId, PDO::PARAM_INT);
			$stmt->bindParam(':master_product_id', $masterProductId, PDO::PARAM_INT);
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

	public static function findMasterProductIdAndChildProductId ($childProductId, $masterProductId) {
		$dap_dbh = Dap_Connection::getConnection();

		try {
			//Load coupon details from database
			$sql = "select *
				from
					dap_products_mc_sss_jn
					where 
							child_product_id=:childProductId and
							master_product_id=:masterProductId";
	
			$stmt = $dap_dbh->prepare($sql);
			
			$stmt->bindParam(':childProductId', $childProductId, PDO::PARAM_INT);
			$stmt->bindParam(':masterProductId', $masterProductId, PDO::PARAM_INT);
			
			$stmt->execute();
	
			//echo "sql: $sql<br>"; exit;
			//$result = mysql_query($sql);
			//echo "rows returned: " . mysql_num_rows($result) . "<br>";
	
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$productcoupon = new Dap_MasterChildSSS();
				$productcoupon->setId( $row["id"] );
				$productcoupon->setMaster_product_id( stripslashes($row["master_product_id"]) );
				$productcoupon->setChild_product_id( stripslashes($row["child_product_id"]) );
			
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

	public static function loadMasterChild () {
		try {
		
			$dap_dbh = Dap_Connection::getConnection();
			$masterChildArray = array();
	
			$sql = "select *
			from
				dap_products_mc_sss_jn
				where master_product_id in (select id from dap_products) and
				child_product_id in (select id from dap_products)
				order by
						child_product_id, master_product_id";

	
			//logToFile($sql,LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			
			$stmt->execute();	
			
			while ($obj = $stmt->fetch()) {
				$masterChildArray[] = $obj;
			}
	
			return $masterChildArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}
	

		//Delete
	public static function removeMasterChildAssociation($id) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			//delete from usersproducts table
			$sql = "delete from  
					dap_products_mc_sss_jn
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