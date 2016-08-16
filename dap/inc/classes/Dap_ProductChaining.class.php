<?php

class Dap_ProductChaining {

	var $id;
	var $source_operation;
	var $source_product_id;
	var $target_operation;
	var $target_product_id;

	function getId() {
		return $this->id;
	}
	function setId($o) {
		$this->id = $o;
	}
		
	function getSource_operation()  {
	       return $this->source_operation;
	}
	function setSource_operation($o) {
	      $this->source_operation = $o;
	}

	function getSource_product_id()  {
	       return $this->source_product_id;
	}
	function setSource_product_id($o) {
	      $this->source_product_id = $o;
	}	

	function getTarget_operation()  {
	       return $this->target_operation;
	}
	function setTarget_operation($o) {
	      $this->target_operation = $o;
	}	
	
	function getTarget_product_id()  {
	       return $this->target_product_id;
	}
	function setTarget_product_id($o) {
	      $this->target_product_id = $o;
	}		
	
	function getComment()  {
	       return $this->comment;
	}
	function setComment($o) {
	      $this->comment = $o;
	}		
	
	public static function loadChainedProducts () {
		try {
		
			$dap_dbh = Dap_Connection::getConnection();
			$productChainingArray = array();
	
			$sql = "select
						id,
						source_operation,
						source_product_id,
						target_operation,
						target_product_id,
						transaction_id,
						add_days,
						comment
					from 
						dap_productchaining
						where source_product_id in (select id from dap_products) and target_product_id in (select id from dap_products)";
	
			logToFile($sql,LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->execute();	
			
			while ($obj = $stmt->fetch()) {
				$productChainingArray[] = $obj;
			}
	
			return $productChainingArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}

	public static function loadChainedProductsByProductId ($productId, $sourceOperation = "") {
		try {
		
			$dap_dbh = Dap_Connection::getConnection();
			$productChainingArray = array();
			
			logToFile("source_operation=" . $sourceOperation,LOG_INFO_DAP);
			
			if ($sourceOperation == "") {
				$sql = "select
						id,
						source_operation,
						source_product_id,
						target_operation,
						target_product_id,
						transaction_id,
						add_days,
						comment
					from 
						dap_productchaining
					where source_product_id = :source_product_id and source_product_id in (select id from dap_products) and target_product_id in (select id from dap_products)";
					//logToFile($sql,LOG_DEBUG_DAP);
				$stmt = $dap_dbh->prepare($sql);
				$stmt->bindParam(':source_product_id', $productId, PDO::PARAM_INT);
				$stmt->execute();	
				while ($obj = $stmt->fetch()) {
					$productChainingArray[] = $obj;
				}
		
			}
			else {
				$sql = "select
						id,
						source_operation,
						source_product_id,
						target_operation,
						target_product_id,
						transaction_id,
						add_days,
						comment
					from 
						dap_productchaining
					where source_product_id = :source_product_id and source_product_id in (select id from dap_products) and target_product_id in (select id from dap_products) and
					source_operation = :source_operation";
					
				$stmt = $dap_dbh->prepare($sql);
				$stmt->bindParam(':source_product_id', $productId, PDO::PARAM_INT);
				$stmt->bindParam(':source_operation', $sourceOperation, PDO::PARAM_STR);
				$stmt->execute();	
				while ($obj = $stmt->fetch()) {
					$productChainingArray[] = $obj;
				}
		
			}
					
			return $productChainingArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}
	
	//Create
	public static function createProductChainingRules($sourceOperation, $sourceProductId, $targetOperation, $targetProductId, $transactionId, $addDays) {
		try {
			
			if($addDays=="")$addDays=0;
			
			$dap_dbh = Dap_Connection::getConnection();
			
			$sql = "insert into dap_productchaining
						(source_operation, source_product_id, target_operation, target_product_id, transaction_id, add_days, comment)
					values
						(:source_operation, :source_product_id, :target_operation, :target_product_id, :transaction_id, :add_days, :comment)";
					
			logToFile($sql,LOG_INFO_DAP);
			logToFile("source_operation=" . $sourceOperation,LOG_INFO_DAP);
			logToFile("source_product_id=" . $sourceProductId,LOG_INFO_DAP);
			logToFile("target_operation=" . $targetOperation,LOG_INFO_DAP);
			logToFile("target_product_id=" . $targetProductId,LOG_INFO_DAP);
			logToFile("add_days=" . $addDays,LOG_INFO_DAP);
			logToFile("trans_id=" . $transactionId,LOG_INFO_DAP);
			
			$stmt = $dap_dbh->prepare($sql);
			$comment="";
			
			$stmt->bindParam(':source_operation', $sourceOperation, PDO::PARAM_STR);
			$stmt->bindParam(':source_product_id', $sourceProductId, PDO::PARAM_INT);
			$stmt->bindParam(':target_operation', $targetOperation, PDO::PARAM_STR);
			$stmt->bindParam(':target_product_id', $targetProductId, PDO::PARAM_INT);
			$stmt->bindParam(':transaction_id', $transactionId, PDO::PARAM_INT);
			$stmt->bindParam(':add_days', $addDays, PDO::PARAM_INT);
			$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
			$stmt->execute();
			
			$lastid = $dap_dbh->lastInsertId();
			logToFile("lastid: $lastid"); 
			$stmt = null;
			
			$dap_dbh = null;
			return $lastid;
		} catch (PDOException $e) {
			if(stristr($e->getMessage(), "SQLSTATE[23000]: Integrity constraint violation: ") == TRUE) {
				logToFile($e->getMessage(),LOG_INFO_DAP);
				return 0;
			}
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
		
	}

	//Delete
	public static function removeRule($id) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			//delete from usersproducts table
			$sql = "delete from  
					dap_productchaining
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
	
	//Delete
	public static function removeRules($productId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			//delete from usersproducts table
			$sql = "delete from  
					dap_productchaining
					where source_product_id =:source_product_id";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':source_product_id', $productId, PDO::PARAM_INT);
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
