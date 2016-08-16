<?php

class Dap_CustomFields {
   	var $id;
   	var $name;
	var $label;
	var $description;
	var $showonlytoadmin;
	var $allowDelete;
	var $required;
	
	function getId() {
		return $this->id;
	}
	function setId($o) {
		$this->id = $o;
	}

	function getName() {
		return $this->code;
	}
	function setName($o) {
		$this->code = $o;
	}

	function getLabel() {
		return $this->label;
	}
	function setLabel($o) {
		$this->label = $o;
	}
	
	function getDescription() {
		return $this->description;
	}
	function setDescription($o) {
		$this->description = $o;
	}

	function getShowonlytoadmin() {
		return $this->showonlytoadmin;
	}
	function setShowonlytoadmin($o) {
		$this->showonlytoadmin = $o;
	}
	
	function getAllowDelete() {
		return $this->allowDelete;
	}
	function setAllowDelete($o) {
		$this->allowDelete = $o;
	}
	
	function getRequired() {
		return $this->required;
	}
	function setRequired($o) {
		$this->required = $o;
	}	

	public function create() {
		try {
			$dap_dbh = Dap_Connection::getConnection();
		
			$sql = "insert into dap_custom_fields
						(name, label, description, showonlytoadmin, allow_delete, required)
					values
						(:name, :label, :description, :showonlytoadmin, :allowDelete, :required)";

			$stmt = $dap_dbh->prepare($sql);
			
			$stmt->bindParam(':name', $this->getName(), PDO::PARAM_STR);
			$stmt->bindParam(':label', $this->getLabel(), PDO::PARAM_STR);
			$stmt->bindParam(':description', $this->getDescription(), PDO::PARAM_STR);
			$stmt->bindParam(':showonlytoadmin', $this->getShowonlytoadmin(), PDO::PARAM_STR);
			$stmt->bindParam(':allowDelete', $this->getAllowDelete(), PDO::PARAM_STR);
			$stmt->bindParam(':required', $this->getRequired(), PDO::PARAM_STR);
			
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
			
			
				$sql = "update 
							dap_custom_fields 
						set
							name = :name,
							label = :label,
							showonlytoadmin = :showonlytoadmin,
							description = :description,
							allow_delete = :allowDelete,
							required = :required
						where id = :keyId";

			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':name', $this->getName(), PDO::PARAM_STR);
			$stmt->bindParam(':label', $this->getLabel(), PDO::PARAM_STR);
			$stmt->bindParam(':description', $this->getDescription(), PDO::PARAM_STR);
			$stmt->bindParam(':showonlytoadmin', $this->getShowonlytoadmin(), PDO::PARAM_STR);
			$stmt->bindParam(':allowDelete', $this->getAllowDelete(), PDO::PARAM_STR);
			$stmt->bindParam(':required', $this->getRequired(), PDO::PARAM_STR);
			$stmt->bindParam(':keyId', $this->getId(), PDO::PARAM_INT);

			logToFile("functions admin, name=" . $this->getName(),LOG_INFO_DAP);
			logToFile("functions admin, label=" . $this->getLabel(),LOG_INFO_DAP);
			logToFile("functions admin, description=" . $this->getDescription(),LOG_INFO_DAP);
			logToFile("functions admin, showonlytoadmin=" . $this->getShowonlytoadmin(),LOG_INFO_DAP);
			logToFile("functions admin, allowDelete=" . $this->getAllowDelete(),LOG_INFO_DAP);
			logToFile("functions admin, required=" . $this->getRequired(),LOG_INFO_DAP);
			
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
	

	public static function loadAllCustomFields() {
		$dap_dbh = Dap_Connection::getConnection();
		$CustomFields = null;
		//logToFile("Dap_CustomFields.class: loadCustomFields() enter");
		
		//Load CustomFields details from database
		$sql = "select * from dap_custom_fields";

		$stmt = $dap_dbh->prepare($sql);
		//$stmt->bindParam(':id', $keyId, PDO::PARAM_STR);
		$stmt->execute();

		$CustomFields = array();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$CustomFields[] = $row;
			//logToFile("Dap_CustomFields.class: loadCustomFields(): name=" . $row['name']);
		}
		
		
		return $CustomFields;
	}
	
	public static function loadUserFacingCustomFields() {
		$dap_dbh = Dap_Connection::getConnection();
		$CustomFields = null;
		//logToFile("Dap_CustomFields.class: loadCustomFields() enter");
		
		//Load CustomFields details from database
		$sql = "select * from dap_custom_fields where allow_delete = 'Y' and showonlytoadmin = 'N'";

		$stmt = $dap_dbh->prepare($sql);
		//$stmt->bindParam(':id', $keyId, PDO::PARAM_STR);
		$stmt->execute();

		$CustomFields = array();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$CustomFields[] = $row;
			//logToFile("Dap_CustomFields.class: loadCustomFields(): name=" . $row['name']);
		}
		
		return $CustomFields;
	}
	
	
	public static function loadCustomFields() {
		$dap_dbh = Dap_Connection::getConnection();
		$CustomFields = null;
	//	logToFile("Dap_CustomFields.class: loadCustomFields() enter");
		
		//Load CustomFields details from database
		$sql = "select * from dap_custom_fields where allow_delete = 'Y'";

		$stmt = $dap_dbh->prepare($sql);
		//$stmt->bindParam(':id', $keyId, PDO::PARAM_STR);
		$stmt->execute();

		$CustomFields = array();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$CustomFields[] = $row;
			//logToFile("Dap_CustomFields.class: loadCustomFields(): name=" . $row['name']);
		}
		
		
		return $CustomFields;
	}
	
	public static function loadCustomFieldById($keyId) {
		
		logToFile("Dap_CustomFields.class.php, loadCustomFieldById ()",LOG_INFO_DAP);
		
		$dap_dbh = Dap_Connection::getConnection();
		$CustomFields = null;
	
		//Load CustomFields details from database
		$sql = "select * from dap_custom_fields where id=:key_id";

		$stmt = $dap_dbh->prepare($sql);
		$stmt->bindParam(':key_id', $keyId, PDO::PARAM_INT);
		$stmt->execute();
				
		if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			//logToFile("functions admin, loadCustomFieldById: instantiate Dap_CustomFields",LOG_INFO_DAP);
			$CustomFields = new Dap_CustomFields();
			$CustomFields->setId( $row["id"] );
			$CustomFields->setName( stripslashes($row["name"]) );
			$CustomFields->setLabel( stripslashes($row["label"]) );
			$CustomFields->setDescription( stripslashes($row["description"]) );
			$CustomFields->setShowonlytoadmin( stripslashes($row["showonlytoadmin"]) );
			$CustomFields->setAllowDelete( stripslashes($row["allow_delete"]) );
			$CustomFields->setRequired( stripslashes($row["required"]) );
			//logToFile("functions admin, loadCustomFieldById: description=" . $row["description"],LOG_INFO_DAP);
		}
		
		return $CustomFields;
	}
	
	public static function isExists($keyId) {
		$dap_dbh = Dap_Connection::getConnection();

		//Load CustomFields details from database
		$sql = "select id
			from
				dap_custom_fields
			where
				id = :keyId";

		$stmt = $dap_dbh->prepare($sql);
		$stmt->bindParam(':id', $keyId, PDO::PARAM_INT);
		$stmt->execute();

		//echo "sql: $sql<br>"; exit;
		//$result = mysql_query($sql);
		//echo "rows returned: " . mysql_num_rows($result) . "<br>";

		if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
		  return TRUE;
		}

		return FALSE;
	}	

	public static function loadCustomfieldsByName($name) {
		$dap_dbh = Dap_Connection::getConnection();

		//Load CustomFields details from database
		$sql = "select *
			from
				dap_custom_fields
			where
				name = :name";

		$stmt = $dap_dbh->prepare($sql);
		$stmt->bindParam(':name', $name, PDO::PARAM_STR);
		$stmt->execute();

		//echo "sql: $sql<br>"; exit;
		//$result = mysql_query($sql);
		//echo "rows returned: " . mysql_num_rows($result) . "<br>";

		if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$CustomFields = new Dap_CustomFields();
			$CustomFields->setId( $row["id"] );
			$CustomFields->setName( stripslashes($row["name"]) );
			$CustomFields->setLabel( stripslashes($row["label"]) );
			$CustomFields->setDescription( stripslashes($row["description"]) );
			$CustomFields->setShowonlytoadmin( stripslashes($row["showonlytoadmin"]) );	
			$CustomFields->setAllowDelete( stripslashes($row["allow_delete"]) );
			$CustomFields->setRequired( stripslashes($row["required"]) );
			
			return $CustomFields;
		}

		return;
	}

	

	public static function deleteCustomFields($id) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$dap_dbh->beginTransaction(); //begin the transaction
			$response = "SUCCESS! custom field was deleted from the database";
			$count = 0;

			//Check if there are any users associated with this CustomFields
			$sql = "select count(*) as count from dap_users_custom where custom_id = :id";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();

			if ($row = $stmt->fetch()) {
				$count = $row["count"];
				if($count > 0) {
					return "There are Users associated with this custom field. <br/>Remove them first before you can delete this custom field.";
				}
			}

			//If none, then delete from dap_custom_fields table
			$sql = "delete from dap_custom_fields where id = :id";
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

	public static function getMinKeyId() {
		$dap_dbh = Dap_Connection::getConnection();
		$id = 0;
		
		$sql = "select 
					min(id) as id
				from
					dap_custom_fields where allow_delete = 'Y'";
					
		$stmt = $dap_dbh->prepare($sql);
		$stmt->execute();

		if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$id = $row["id"];
		}

		return $id;
	}

}
?>