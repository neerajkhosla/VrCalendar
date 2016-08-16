<?php
	
class Dap_Credits {
   	var $userid;
   	var $type;
   	var $used;
   	var $postid;
	var $credits;
	
	function getUserid() {
		return $this->userid;
	}
	function setUserid($o) {
		$this->userid = $o;
	}
	
	function getType() {
		return $this->type;
	}
	function setType($o) {
		$this->type = $o;
	}

	function getUsed() {
		return $this->used;
	}
	function setUsed($o) {
		$this->used = $o;
	}
	
	function getPostid() {
		return $this->postid;
	}
	function setPostid($o) {
		$this->postid = $o;
	}
	
	function getTitle() {
		return $this->title;
	}
	function setTitle($o) {
		$this->title = $o;
	}
	
	function getCredits() {
		return $this->credits;
	}
	function setCredits($o) {
		$this->credits = $o;
	}	


	public static function loadCreditsUsedByUserIdAndPostId($userid,$postid) {
		$userCreditsUsedList = array();
		
		try {
			$dap_dbh = Dap_Connection::getConnection();
			
			$sql = "SELECT 
						*
					FROM
						dap_credits
					WHERE
						userid =:userid AND
						postid =:postid
					";
			//logToFile($sql,LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			
			$stmt->bindParam(':userid', $userid, PDO::PARAM_STR);
			$stmt->bindParam(':postid', $postid, PDO::PARAM_STR);
			
			$stmt->execute();
	
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$dapcredits = new Dap_Credits();
				/*logToFile("Dap_Credits.class.php: loadCreditsUsedByUserIdAndPostId, userid=".$row["userid"]);
				logToFile("Dap_Credits.class.php: loadCreditsUsedByUserIdAndPostId,type=".$row["type"]);
				logToFile("Dap_Credits.class.php: loadCreditsUsedByUserIdAndPostId, used=".$row["used"]);
				logToFile("Dap_Credits.class.php: loadCreditsUsedByUserIdAndPostId, postid=".$row["postid"]);
				logToFile("Dap_Credits.class.php: loadCreditsUsedByUserIdAndPostId, title=".$row["title"]);
				*/
				$dapcredits->setUserid( stripslashes($row["userid"]) );
				$dapcredits->setType( stripslashes($row["type"]) );
				$dapcredits->setUsed( stripslashes($row["used"]) );
				$dapcredits->setPostid( stripslashes($row["postid"]) );
				$dapcredits->setTitle( stripslashes($row["title"]) );
				$dapcredits->setCredits( stripslashes($row["credits"]) );
				
				$userCreditsUsedList[] = $dapcredits;
			}
			
			return $userCreditsUsedList;

		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
		
		return NULL;
	}
	
	public static function loadCreditsUsedByUserIdAndPostIdAndType($userid,$postid,$type) {
		$userCreditsUsedList = array();
		
		try {
			$dap_dbh = Dap_Connection::getConnection();
			
			$sql = "SELECT 
						*
					FROM
						dap_credits
					WHERE
						userid =:userid AND
						postid =:postid AND
						type = :type
					";
			//logToFile($sql,LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			
			$stmt->bindParam(':userid', $userid, PDO::PARAM_STR);
			$stmt->bindParam(':postid', $postid, PDO::PARAM_STR);
			$stmt->bindParam(':type', $type, PDO::PARAM_STR);
			
			$stmt->execute();
	
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$dapcredits = new Dap_Credits();
				/*logToFile("Dap_Credits.class.php: loadCreditsUsedByUserIdAndPostId, userid=".$row["userid"]);
				logToFile("Dap_Credits.class.php: loadCreditsUsedByUserIdAndPostId,type=".$row["type"]);
				logToFile("Dap_Credits.class.php: loadCreditsUsedByUserIdAndPostId, used=".$row["used"]);
				logToFile("Dap_Credits.class.php: loadCreditsUsedByUserIdAndPostId, postid=".$row["postid"]);
				logToFile("Dap_Credits.class.php: loadCreditsUsedByUserIdAndPostId, title=".$row["title"]);
				*/
				$dapcredits->setUserid( stripslashes($row["userid"]) );
				$dapcredits->setType( stripslashes($row["type"]) );
				$dapcredits->setUsed( stripslashes($row["used"]) );
				$dapcredits->setPostid( stripslashes($row["postid"]) );
				$dapcredits->setTitle( stripslashes($row["title"]) );
				$dapcredits->setCredits( stripslashes($row["credits"]) );
				
				$userCreditsUsedList[] = $dapcredits;
			}
			
			return $userCreditsUsedList;

		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
		
		return NULL;
	}
	
	public static function loadCreditsUsedByUserId($userid) {
		$userCreditsUsedList = array();
		
		try {
			$dap_dbh = Dap_Connection::getConnection();
			
			$sql = "SELECT 
						*
					FROM
						dap_credits
					WHERE
						userid =:userid
					";
			//logToFile($sql,LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userid', $userid, PDO::PARAM_STR);
			$stmt->execute();
	
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$dapcredits = new Dap_Credits();
				
				$dapcredits->setUserid( stripslashes($row["userid"]) );
				$dapcredits->setType( stripslashes($row["type"]) );
				$dapcredits->setUsed( stripslashes($row["used"]) );
				$dapcredits->setPostid( stripslashes($row["postid"]) );
				$dapcredits->setTitle( stripslashes($row["title"]) );
				$dapcredits->setCredits( stripslashes($row["credits"]) );
				$userCreditsUsedList[] = $dapcredits;

			}
			
			
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
		
		return $userCreditsUsedList;
	}
	
	public function create() {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$sql = "insert into dap_credits
						(userid, type, used, postid, title, credits)
					values 
						(:userid, :type, :used, :postid, :title, :credits)";

			$stmt = $dap_dbh->prepare($sql);
			
			$stmt->bindParam(':userid', $this->getUserid(), PDO::PARAM_STR);
			$stmt->bindParam(':type', $this->getType(), PDO::PARAM_STR);
			$stmt->bindParam(':used', $this->getUsed(), PDO::PARAM_STR);
			$stmt->bindParam(':postid', $this->getPostid(), PDO::PARAM_STR);
			$stmt->bindParam(':title', $this->getTitle(), PDO::PARAM_STR);
			$stmt->bindParam(':credits', $this->getCredits(), PDO::PARAM_STR);
			$stmt->execute();
			
			$last_insert_id = $dap_dbh->lastInsertId();
			//$this->setId($last_insert_id);
			
			$stmt = null;
			$dap_dbh = null;
			return $last_insert_id;
	
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
			logToFile("(Dap_Credits.update()) userid: ".$this->getUserid());
			$dap_dbh = Dap_Connection::getConnection();
	
			$sql = "update 
					dap_credits
					set
						used =:used, credits=:credits
					where
						userid =:userid and type =:type and postid =:postid";
					
			$stmt = $dap_dbh->prepare($sql);
	
			$stmt->bindParam(':type', $this->getType(), PDO::PARAM_STR);
			$stmt->bindParam(':used', $this->getUsed(), PDO::PARAM_STR);
		 	$stmt->bindParam(':postid', $this->getPostid(), PDO::PARAM_STR);
			$stmt->bindParam(':userid', $this->getUserid(), PDO::PARAM_STR);
			$stmt->bindParam(':credits', $this->getCredits(), PDO::PARAM_STR);
			
			$stmt->execute();
			
			logToFile("update complete",LOG_DEBUG_DAP);
			
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

	public function removeRow() {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$dap_dbh->beginTransaction();
			
			$userid = $this->getUserid();
			$postid = $this->getPostid();
			$type = $this->getType();
					
			//delete from usersproducts table
			$sql = "delete from  
					dap_credits
					where userid =:userid and postid=:postid and type=:type";
			
			logToFile("(Dap_Credits.removeRow()) sql: ".$sql);
			logToFile("(Dap_Credits.removeRow()) type: ".$type);
			$stmt = $dap_dbh->prepare($sql);
			
			$stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
			$stmt->bindParam(':postid', $postid, PDO::PARAM_INT);
			$stmt->bindParam(':type', $type, PDO::PARAM_INT);
			
			$stmt->execute();
			
			$dap_dbh->commit(); 
			
			logToFile("(Dap_Credits.removeRow()) COMPLETED: ".$type);
			
			$stmt = null;
			$dap_dbh = null;		
			return;			
		} catch (PDOException $e) {
			$dap_dbh->rollback();
			$stmt = null;
			$dap_dbh = null;				
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			$dap_dbh->rollback();
			$stmt = null;
			$dap_dbh = null;			
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}
	
	public function delete() {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$dap_dbh->beginTransaction();
			
			$userid = $this->getUserid();
			//delete from usersproducts table
			$sql = "delete from  
					dap_credits
					where userid =:userid";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':userid', $userid, PDO::PARAM_INT);
			$stmt->execute();
			
			$dap_dbh->commit(); 
			
			$stmt = null;
			$dap_dbh = null;		
			return;			
		} catch (PDOException $e) {
			$dap_dbh->rollback();
			$stmt = null;
			$dap_dbh = null;				
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			$dap_dbh->rollback();
			$stmt = null;
			$dap_dbh = null;			
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}
	
}

?>