<?php

class Dap_UserResource extends Dap_Base {

   	var $user_id;
   	var $resource_id;
   	var $click_count;
	

	function getUser_id() {
		return $this->user_id;
	}
	function setUser_id($o) {
		$this->user_id = $o;
	}
	
	function getResource_id() {
		return $this->resource_id;
	}
	function setResource_id($o) {
		$this->resource_id = $o;
	}	

	function getClick_count() {
		return $this->click_count;
	}
	function setClick_count($o) {
		$this->click_count = $o;
	}
	
	public static function incClick_count($userId, $resourceId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
	
			$sql = "insert into 
						dap_users_resources_jn 
					set
						user_id =:user_id ,
						resource_id =:resource_id ,
						click_count = 1
					on duplicate 
					update dap_users_resources_jn
					set click_count = click_count + 1
					where
						user_id =:user_id 
						and resource_id = :resource_id";
						
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
			$stmt->bindParam(':resource_id', $resourceId, PDO::PARAM_INT);
			//logToFile($sql,LOG_DEBUG_DAP);
			
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

}
?>