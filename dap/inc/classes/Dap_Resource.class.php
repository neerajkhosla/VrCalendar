<?php

//abstract class
class Dap_Resource extends DAP_Base {

	var $resource_type;
	var $name;
	var $description;
	var $status;
	
	
	function getResource_type() {
	        return $this->resource_type;
	}
	
	function setResource_type($o) {
	      $this->resource_type = $o;
	}
	
	function getName() {
	        return $this->name;
	}
	
	function setName($o) {
	      $this->name = $o;
	}
	
	function getDescription() {
	        return $this->description;
	}
	
	function setDescription($o) {
	      $this->description = $o;
	}
	
	function getStatus() {
	        return $this->status;
	}
	
	function setStatus($o) {
	      $this->status = $o;
	}
	
	public static function isResourceProtected($resource) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$isProtected = false;
			
			//Check if we have atleast one occurence of valid requestUrl in the db.
			$sql = "select fr.id
				from
					dap_file_resources fr
				where
					fr.url =:resource
					";
			//echo("Executing SQL:".$sql);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':resource', $resource, PDO::PARAM_STR);
			$stmt->execute();
			if($stmt->rowCount() > 0) {
				$isProtected = true;
			}
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null;
			return $isProtected;
			//incase of exception, we should assume that the resource is protected.
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			return TRUE;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			return TRUE;
		}		
		return FALSE;
	}	
	
	
	//see if this resource can be displayed on home page
	public static function displayResource($resource) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$canDisplay = false;
		
			//Check if we have atleast one occurence of valid requestUrl in the db.
			$sql = "select fr.id
				from
					dap_file_resources fr
				where
					fr.url =:resource
					and display_in_list = 'Y'
					";
			//echo("Executing SQL:".$sql);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':resource', $resource, PDO::PARAM_STR);
			$stmt->execute();
			if($stmt->rowCount() > 0) {
				$canDisplay = true;
			}
			
			$sql = null;
			$stmt = null;
			$dap_dbh = null;
			return $canDisplay;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
		}		
		return FALSE;
	}
	
	
	//get the click count available or not
	public static function isCountAvailable($user_id, $resource) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$isCountAvailable = true;
			
			//Check if we have atleast one occurence of valid requestUrl in the db.
			$sql = "select fr.id, urj.click_count
				from
					dap_file_resources fr,
					dap_users_resources_jn urj
				where
					fr.url =:resource 
					and urj.resource_id = fr.id
					and urj.user_id = :user_id
					and urj.click_count < 0
					";
			//echo("Executing SQL:".$sql);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':resource', $resource, PDO::PARAM_STR);
			$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
			$stmt->execute();
			if($stmt->rowCount() > 0) {
				$isCountAvailable = false;
			}
			
			return $isCountAvailable;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
		}		
		return TRUE;
	}	

	//This assumes only one resource in dap_file_resource with same url. 
	public static function isResourceClickCountOK($user_id, $resource) {
		$retval = TRUE;
		try {
			$dap_dbh = Dap_Connection::getConnection();
		
			//Check if we have atleast one occurence of valid requestUrl in the db.
			$select_sql = "select fr.id, urj.click_count
				from
					dap_file_resources fr,
					dap_users_resources_jn urj
				where
					fr.url =:resource 
					and urj.resource_id = fr.id
					and urj.user_id = :user_id
					";
			$update_sql = "update
						dap_users_resources_jn
						set 
						click_count = click_count - 1
						where 
						user_id = :user_id
						and resource_id = :resource_id
					";
			$check_sql = "select fr.id, urj.click_count
				from
					dap_file_resources fr,
					dap_users_resources_jn urj
				where
					fr.url =:resource 
					and urj.resource_id = fr.id
					and urj.user_id = :user_id
					and urj.click_count < 0
					";
			//echo("Executing SQL:".$sql);
			$select_stmt = $dap_dbh->prepare($select_sql);
			$check_stmt = $dap_dbh->prepare($check_sql);
			$update_stmt = $dap_dbh->prepare($update_sql);
			$select_stmt->bindParam(':resource', $resource, PDO::PARAM_STR);
			$select_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
			$select_stmt->execute();
			if ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
				//we have a urj entry with that resource, with that user. We need to update it -1 on click count.
				$resource_id = $row['id'];
				//lets update the urj
				$update_stmt->bindParam(':resource_id', $row['id'], PDO::PARAM_INT);
				$update_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
				$update_stmt->execute();
				//no check to see if we have click count < 0, if yes, we have issue.
				$check_stmt->bindParam(':resource', $resource, PDO::PARAM_STR);
				$check_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
				$check_stmt->execute();
				if($check_stmt->rowCount() > 0) {
					//we have issue with this click count as its negative
					$retval = FALSE;
					//return FALSE;	
				}

			}
			
			$select_sql = null;
			$update_sql = null;
			$check_sql = null;
			$select_stmt = null;
			$check_stmt = null;
			$update_stmt = null;
			$dap_dbh = null;
			
			//incase of error, we assume that the resource is not click count restricted.
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
		}	
		if($retval === FALSE) {
			throw new Exception("Resource Count Negative For " . $resource);
		}	
		//no urj is found, so assume this is not click count restricted.
		return $retval;
	}	
 	
 	
}
?>