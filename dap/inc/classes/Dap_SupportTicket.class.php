<?php

class Dap_SupportTicket {

	var $id;
	var $source_product_id;
	var $comment;
	var $support_access_url;
	
	function getId() {
		return $this->id;
	}
	function setId($o) {
		$this->id = $o;
	}
		
	function getSource_product_id()  {
	       return $this->source_product_id;
	}
	function setSource_product_id($o) {
	      $this->source_product_id = $o;
	}	
	
	function getComment()  {
	       return $this->comment;
	}
	function setComment($o) {
	      $this->comment = $o;
	}		
	
	function getSupport_access_url()  {
	       return $this->support_access_url;
	}
	function setSupport_access_url($o) {
	      $this->support_access_url = $o;
	}	
		
	public static function loadSupportTicketProducts () {
		try {
		
			$dap_dbh = Dap_Connection::getConnection();
			$supportticketproductArray = array();
	
			$sql = "select
						id,						
						source_product_id,
						support_access_url,
						comment
					from 
						dap_supportticket
						where source_product_id in (select id from dap_products)";
	
			logToFile($sql,LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->execute();	
			
			while ($obj = $stmt->fetch()) {
				$supportticketproductArray[] = $obj;
			}
	
			return $supportticketproductArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}

	public static function loadSupportTicketProductsByProductId ($productId) {
		try {
		
			$dap_dbh = Dap_Connection::getConnection();
			$supportticketproductArray = array();
			
			logToFile("source_operation=" . $sourceOperation,LOG_INFO_DAP);
			
			if ($sourceOperation == "") {
				$sql = "select
						id,					
						source_product_id,
						support_access_url,
						comment
					from 
						dap_supportticket
					where source_product_id = :source_product_id";
					//logToFile($sql,LOG_DEBUG_DAP);
				$stmt = $dap_dbh->prepare($sql);
				$stmt->bindParam(':source_product_id', $productId, PDO::PARAM_INT);
				$stmt->execute();	
				while ($obj = $stmt->fetch()) {
					$supportticketproductArray[] = $obj;
				}
		
			}
			else {
				$sql = "select
						id,
						source_product_id,
						support_access_url,
						comment
					from 
						dap_supportticket
					where 
					source_product_id = :source_product_id";
					
				$stmt = $dap_dbh->prepare($sql);
				$stmt->bindParam(':source_product_id', $productId, PDO::PARAM_INT);
				
				$stmt->execute();	
				while ($obj = $stmt->fetch()) {
					$supportticketproductArray[] = $obj;
				}
		
			}
					
			return $supportticketproductArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}
	
	//Create
	public static function createSupportTicketIntegrationRules ($sourceProductId,$supportAccessURL) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			
			$sql = "insert into dap_supportticket
						(source_product_id, support_access_url, comment)
					values
						(:source_product_id, :support_access_url, :comment)";
					
			logToFile($sql,LOG_INFO_DAP);
			
			logToFile("source_product_id=" . $sourceProductId,LOG_INFO_DAP);
			logToFile("supportAccessURL=" . $supportAccessURL,LOG_INFO_DAP);
			
			$stmt = $dap_dbh->prepare($sql);
			$comment="";
			
		
			$stmt->bindParam(':source_product_id', $sourceProductId, PDO::PARAM_INT);
			$stmt->bindParam(':support_access_url', $supportAccessURL, PDO::PARAM_STR);
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
					dap_supportticket
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
					dap_supportticket
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
