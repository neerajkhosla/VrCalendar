<?php

class Dap_UserCustomFields extends Dap_Base {

   	var $user_id;
   	var $custom_id;
   	var $custom_value;
   		
		function getUser_id()  {
	       return $this->user_id;
		}
		function setUser_id($o) {
	      $this->user_id = $o;
		}	
		
		function getCustom_id()  {
	       return $this->custom_id;
		}
		function setCustom_id($o) {
	      $this->custom_id = $o;
		}	
			
		function getCustom_value()  {
	       return $this->custom_value;
		}
		function setCustom_value($o) {
	      $this->custom_value = $o;
		}	
		
		
		public function create() {
		try {
			$dap_dbh = Dap_Connection::getConnection();
		
			$sql = "insert into dap_users_custom
						(user_id, custom_id, custom_value)
					values
						(:userId, :customId, :customValue)";

			$stmt = $dap_dbh->prepare($sql);
			
			$stmt->bindParam(':userId', $this->getUser_id(), PDO::PARAM_INT);
			$stmt->bindParam(':customId', $this->getCustom_id(), PDO::PARAM_INT);
			$stmt->bindParam(':customValue', $this->getCustom_value(), PDO::PARAM_STR);
		
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
			
			
				$sql = "update dap_users_custom set
							custom_value = :customValue
						where user_id = :userId and
						custom_id = :customId
						";

			$stmt = $dap_dbh->prepare($sql);
			
			$stmt->bindParam(':userId', $this->getUser_id(), PDO::PARAM_INT);
			$stmt->bindParam(':customId', $this->getCustom_id(), PDO::PARAM_INT);
			$stmt->bindParam(':customValue', $this->getCustom_value(), PDO::PARAM_STR);
		
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
	

	public static function loadALLUserCustomFields() {
		$dap_dbh = Dap_Connection::getConnection();
		$CustomFields = null;
		logToFile("Dap_UserCustomFields.class: loadUserCustomFields() enter");
		
		//Load CustomFields details from database
		$sql = "select *
			from
				dap_users_custom";

		$stmt = $dap_dbh->prepare($sql);
		//$stmt->bindParam(':id', $keyId, PDO::PARAM_STR);
		$stmt->execute();

		$userCustomFields = array();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$userCustomFields[] = $row;
		}
		
		logToFile("Dap_UserCustomFields.class: loadUserCustomFields() exit");
		return $userCustomFields;
	}
	
	public static function loadUserCustomFields($userId) {
		
		//logToFile("Dap_UserCustomFields.class.php: loadUserCustomField ()",LOG_INFO_DAP);
		
		$dap_dbh = Dap_Connection::getConnection();
		$userCustomFields = null;
	
		//Load CustomFields details from database
		$sql = "select 
					a.user_id,
					a.custom_id,
					a.custom_value,
					b.name,
					b.description 
				from 
					dap_users_custom a, 
					dap_custom_fields b 
				where 
					user_id=:userId and 
					a.custom_id = b.id";

		$stmt = $dap_dbh->prepare($sql);
		$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
		$stmt->execute();
		
		$userCustomFields = array();
		
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
//			logToFile("Dap_UserCustomFields.class.php: loadCustomFieldById: instantiate Dap_UserCustomFields",LOG_INFO_DAP);
			$userCustomFields[] = $row;
	//		logToFile("Dap_UserCustomFields.class.php: loadCustomFieldById: custom value=" . $row["custom_value"],LOG_INFO_DAP);
		}
		
		return $userCustomFields;
	}
	
	public static function loadUserCustomFieldsByCustomFieldId($cId, $userId) {
		
//		logToFile("Dap_UserCustomFields.class.php: loadUserCustomFieldsByCustomFieldId (), cid=" . $cId . " uId=" . $userId,LOG_INFO_DAP);
		
		$dap_dbh = Dap_Connection::getConnection();
		$userCustomFields = null;
	
		//Load CustomFields details from database
		$sql = "select 
					a.user_id,
					a.custom_id,
					a.custom_value,
					b.name,
					b.description,
					b.required
				from 
					dap_users_custom a, 
					dap_custom_fields b 
				where 
					user_id = :userId and 
					a.custom_id = b.id and 
					a.custom_id = :cId";

		$stmt = $dap_dbh->prepare($sql);
		$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
		$stmt->bindParam(':cId', $cId, PDO::PARAM_INT);
		$stmt->execute();
		
		$userCustomFields = array();
		
		if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			//logToFile("Dap_UserCustomFields.class.php: loadCustomFieldById: instantiate Dap_UserCustomFields",LOG_INFO_DAP);
			$userCustomFields[] = $row;
			//logToFile("Dap_UserCustomFields.class.php: loadCustomFieldById: custom value=" . $row["custom_value"],LOG_INFO_DAP);
		}
		
		return $userCustomFields;
	}
	

	public static function loadUserCustomFieldsByCustomFieldName($cName, $userId) {
		
		logToFile("Dap_UserCustomFields.class.php: loadUserCustomFieldsByCustomFieldId (), cName=" . $cName . " uId=" . $userId,LOG_INFO_DAP);
		
		$dap_dbh = Dap_Connection::getConnection();
		$userCustomFields = null;
	
		//Load CustomFields details from database
		$sql = "select a.user_id,a.custom_id,a.custom_value,b.name,b.description from dap_users_custom a, dap_custom_fields b 
		where user_id=:userId and a.custom_id = b.id and UPPER(b.name)=:cName";

		$stmt = $dap_dbh->prepare($sql);
		$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
		$stmt->bindParam(':cName', strtoupper($cName), PDO::PARAM_INT);
		$stmt->execute();
		
		$userCustomFields = array();
		
		if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			logToFile("Dap_UserCustomFields.class.php: loadUserCustomFieldsByCustomFieldName: instantiate Dap_UserCustomFields",LOG_INFO_DAP);
			$userCustomFields[] = $row;
			logToFile("Dap_UserCustomFields.class.php: loadUserCustomFieldsByCustomFieldName: custom value=" . $row["custom_value"],LOG_INFO_DAP);
		}
		
		return $userCustomFields;
	}
	
	
	public static function deleteUserCustom($userId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$dap_dbh->beginTransaction(); //begin the transaction
			
			//Delete from dap_users table
			$sql = "delete from dap_users_custom where user_id = :userId";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->execute();
				
			$dap_dbh->commit(); //commit the transaction
			$stmt = null;
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