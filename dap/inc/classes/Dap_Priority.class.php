<?php

class Dap_Priority {
	var $priority;
	var $target_usergroupId;
   
	function getId() {
		return $this->id;
	}
	function setId($o) {
		$this->id = $o;
	}

	function getPriority() {
		return $this->code;
	}
	function setPriority($o) {
		$this->code = $o;
	}

	function getTargetUsergroupId() {
		return $this->target_usergroupId;
	}
	function setTargetUsergroupId($o) {
		$this->target_usergroupId = $o;
	}

	public function assignPriority($target_usergroupId, $priority) {
		try {
		  $dap_dbh = Dap_Connection::getConnection();
		  
		  $sql = "select *
		  from
			  dap_forum_priority
		  where
			  target_usergroupId = :target_usergroupId";
  
  		  logToFile("Dap_Priority.class.php . assignPriority, priority=" . $priority,LOG_INFO_DAP);
		  logToFile("Dap_Priority.class.php . assignPriority, target_usergroupId=" . $target_usergroupId,LOG_INFO_DAP);
		  $stmt = $dap_dbh->prepare($sql);
		  $stmt->bindParam(':target_usergroupId', $target_usergroupId, PDO::PARAM_STR);
		  $stmt->execute();
		  if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			//update
			logToFile("Dap_Priority.class.php . assignPriority, update",LOG_INFO_DAP);
			$sql = "update dap_forum_priority set
					priority = :priority
					where target_usergroupId = :target_usergroupId";

			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':target_usergroupId', $target_usergroupId, PDO::PARAM_STR);
			$stmt->bindParam(':priority', $priority, PDO::PARAM_STR);
			$stmt->execute();
			logToFile("Dap_Priority.class.php . assignPriority, update complete",LOG_INFO_DAP);
			return 0;  
		  }
		  else {
			//insert	
			logToFile("Dap_Priority.class.php . assignPriority, insert",LOG_INFO_DAP);
			  $sql = "insert into dap_forum_priority
						  (priority, target_usergroupId)
					  values
						  (:priority, :target_usergroupId)";
			  $stmt = $dap_dbh->prepare($sql);
			  
			  $stmt->bindParam(':target_usergroupId', $target_usergroupId, PDO::PARAM_STR);
			  $stmt->bindParam(':priority', $priority, PDO::PARAM_STR);
			  
			  $stmt->execute();
			  return 0;
		  }
			  
		  $stmt = null;
		  $dap_dbh = null;
		  logToFile("Dap_Priority.class.php . assignPriority, -1",LOG_INFO_DAP);
	      return -1;
		  
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}
	
	//Load coupons matching filter criteria
	public static function loadPriority() {
		
		logToFile("Dap_Priority.class:  Enter loadPriority",LOG_DEBUG_DAP);
		
		$priorityArray = array();
		
		$sql = "select * from dap_forum_priority";

		try {
			$dap_dbh = Dap_Connection::getConnection();
			$stmt = $dap_dbh->prepare($sql);
			$stmt->execute();
			
			
			
			while ($row = $stmt->fetch()) {
				/*$priority = new Dap_Priority();
				
				$priority->setId( $row["id"] );
				$priority->setPriority( stripslashes($row["priority"]) );
				$priority->setTargetUsergroupId( stripslashes($row["target_usergroupId"]) );
			
				$priorityArray[] = $priority;*/
				$priorityArray[] = $row;
			}
			logToFile("Dap_Priority.class:  Exit loadPriority",LOG_DEBUG_DAP);
			return $priorityArray;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
		
		return NULL;
	}

	public static function deletePriority($id) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$dap_dbh->beginTransaction(); //begin the transaction
			$response = "SUCCESS! usergroup priority was deleted from the database";
			$count = 0;
		
			//If none, then delete from dap_users_coupons_jn table
			$sql = "delete from dap_forum_priority where id = :id";
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

}
?>