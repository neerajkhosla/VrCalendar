<?php

class Dap_EmailAlias extends Dap_Base {

    var $id;
   	var $user_id;
   	var $email;
	var $source;
   	   		
		function getId()  {
	       return $this->id;
		}
		function setId($o) {
	      $this->id = $o;
		}	
		
		
		function getUser_id()  {
	       return $this->user_id;
		}
		function setUser_id($o) {
	      $this->user_id = $o;
		}	
		
		function getEmail()  {
	       return $this->email;
		}
		function setEmail($o) {
	      $this->email = $o;
		}
		
		function getSource()  {
	       return $this->source;
		}
		function setSource($o) {
	      $this->source = $o;
		}	
			
		
		
		public function create() {
		try {
			$dap_dbh = Dap_Connection::getConnection();
		
			$sql = "insert into dap_email_alias
						(user_id, email, source)
					values
						(:userId, :email, :source)";

			$stmt = $dap_dbh->prepare($sql);
			
			$stmt->bindParam(':userId', $this->getUser_id(), PDO::PARAM_STR);
			$stmt->bindParam(':email', $this->getEmail(), PDO::PARAM_STR);
			$stmt->bindParam(':source', $this->getSource(), PDO::PARAM_STR);
		
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
			
			
				$sql = "update dap_email_alias set
							email = :email, source=:source
						where id = :id";

			$stmt = $dap_dbh->prepare($sql);
			
			//$stmt->bindParam(':userId', $this->getUser_id(), PDO::PARAM_INT);
			$stmt->bindParam(':id', $this->getId(), PDO::PARAM_INT);
			$stmt->bindParam(':email', $this->getEmail(), PDO::PARAM_STR);
			$stmt->bindParam(':source', $this->getSource(), PDO::PARAM_STR);
			
		/*	logToFile("Dap_EmailAlias.class: findEmailAliasesByEmail() userid : " . $this->getUser_id());
			logToFile("Dap_EmailAlias.class: findEmailAliasesByEmail() id : " . $this->getId());
			logToFile("Dap_EmailAlias.class: findEmailAliasesByEmail() email : " . $this->getEmail());
			logToFile("Dap_EmailAlias.class: findEmailAliasesByEmail() source : " . $this->getSource());
		*/	
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
	
	public static function findEmailAliasesByUserId($userId) {
		$dap_dbh = Dap_Connection::getConnection();
		//logToFile("Dap_EmailAlias.class: findEmailAliasesByEmail() enter : " . $emailId);
		
		//Load CustomFields details from database
		
		$sql = "select * from dap_email_alias where user_id=:userId";

		$stmt = $dap_dbh->prepare($sql);
		$stmt->bindParam(':userId', $userId, PDO::PARAM_STR);
		$stmt->execute();
		
		try {
			$useremails = array();
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$emailalias = new Dap_EmailAlias();
				$emailalias->setId($row["id"]);
				$emailalias->setUser_id ($row["user_id"]);
				$emailalias->setEmail ($row["email"]);
				$emailalias->setSource ($row["source"]);
				$useremails[] = $emailalias;
			}
			
		} catch (PDOException $e) {
			//$dap_dbh->rollback();
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			//$dap_dbh->rollback();
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
		
		//logToFile("Dap_EmailAlias.class: findEmailAliasesByEmail() userId=". $userId);
		return $useremails;
	}
	
	public static function findEmailAliasesById($id) {
		$dap_dbh = Dap_Connection::getConnection();
		logToFile("Dap_EmailAlias.class: findEmailAliasesByEmail() enter : " . $id);
		
		//Load CustomFields details from database
		
		$sql = "select * from dap_email_alias where id=:id";

		$stmt = $dap_dbh->prepare($sql);
		$stmt->bindParam(':id', $id, PDO::PARAM_STR);
		$stmt->execute();
		
		try {
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				//logToFile("Dap_EmailAlias.class: findEmailAliasesByEmail() row found=". $userId);
				$emailalias = new Dap_EmailAlias();
				$emailalias->setId($row["id"]);
				$emailalias->setUser_id ($row["user_id"]);
				$emailalias->setEmail ($row["email"]);
				$emailalias->setSource ($row["source"]);
				return $emailalias;
			}
		} catch (PDOException $e) {
			//$dap_dbh->rollback();
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			//$dap_dbh->rollback();
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
		
		//logToFile("Dap_EmailAlias.class: findEmailAliasesByEmail() userId=". $userId);
		return $userId;
	}
	
	public static function findEmailAliasesByEmail($emailId) {
		$dap_dbh = Dap_Connection::getConnection();
		//logToFile("Dap_EmailAlias.class: findEmailAliasesByEmail() enter : " . $emailId);
		
		//Load CustomFields details from database
		
		$sql = "select user_id from dap_email_alias where email=:emailId";

		$stmt = $dap_dbh->prepare($sql);
		$stmt->bindParam(':emailId', $emailId, PDO::PARAM_STR);
		$stmt->execute();
		
		try {
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				//logToFile("Dap_EmailAlias.class: findEmailAliasesByEmail() row found=". $userId);
				$userId = $row["user_id"];
			}
		} catch (PDOException $e) {
			//$dap_dbh->rollback();
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			//$dap_dbh->rollback();
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
		
	//	logToFile("Dap_EmailAlias.class: findEmailAliasesByEmail() userId=". $userId);
		return $userId;
	}
	
	public static function loadAllUserEmails() {
		$dap_dbh = Dap_Connection::getConnection();
		$email = null;
//		logToFile("Dap_EmailAlias.class: loadAllUserEmails() enter");
		
		//Load CustomFields details from database
		$sql = "select *
			from
				dap_users_custom";

		$stmt = $dap_dbh->prepare($sql);
		//$stmt->bindParam(':id', $keyId, PDO::PARAM_STR);
		$stmt->execute();

		$useremails = array();
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$useremails[] = $row;
		}
		
		//logToFile("Dap_EmailAlias.class: loadAllUserEmails() exit");
		return $useremails;
	}
		
	
	public static function deleteEmailAlias($userId,$source="") {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$dap_dbh->beginTransaction(); //begin the transaction
			
			//Delete from dap_users table
			if($source!="") {
				$sql = "delete from dap_email_alias where user_id = :userId and source=:source";
				$stmt = $dap_dbh->prepare($sql);
				$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
				$stmt->bindParam(':source', $source, PDO::PARAM_INT);
				$stmt->execute();
			}
			else {
				$sql = "delete from dap_email_alias where user_id = :userId";
				$stmt = $dap_dbh->prepare($sql);
				$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			}
			
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
	
	public static function removeEmailAlias($id, $userId) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$dap_dbh->beginTransaction(); //begin the transaction
			
			//Delete from dap_users table
			$sql = "delete from dap_email_alias where user_id = :userId and id=:id";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
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