<?php

class Dap_MassActions {
   	var $id;
   	var $actionType;
   	var $actionKey;
   	var $payload;
   	var $status;
   	var $comments;
  	var $last_update_ts;
 
	function getId() {
		return $this->id;
	}
	function setId($o) {
		$this->id = $o;
	}
	
	function getActionType() {
		return $this->actionType;
	}
	function setActionType($o) {
		$this->actionType = $o;
	}
	
	function getActionKey() {
		return $this->actionKey;
	}
	function setActionKey($o) {
		$this->actionKey = $o;
	}
	
	function getPayload() {
		return $this->payload;
	}
	function setPayload($o) {
		$this->payload = $o;
	}

	function getStatus() {
		return $this->status;
	}
	function setStatus($o) {
		$this->status = $o;
	}
	
	function getComments() {
		return $this->comments;
	}
	function setComments($o) {
		$this->comments = $o;
	}
	
	function getLastUpdateTs() {
		return $this->last_update_ts;
	}
	function setLastUpdateTs($o) {
		$this->last_update_ts = $o;
	}
	
	
	public function loadPagination($start,$limit,$email,$dapjobtrackid,$fromDate,$toDate,$status,$pagination) {
		logToFile("in Dap_MassActions->load() start limit.. $start,$limit,$email,fromdate=$fromDate,$toDate,$status",LOG_DEBUG_DAP);
		
		if( !isset($start) || ($start == "undefined")  || ($start == "")) {
			//if(Dap_Session::get('start')
			//$limit = 0;
		}
		
		//if ($fromDate=="") $fromDate = date("m-d-Y", mktime(0, 0, 0, date("m")  , date("d")-6, date("Y")));
			//if end date is empty, set to today's date
		//if ($toDate=="") $toDate = date("m-d-Y");
			
		$dap_dbh = Dap_Connection::getConnection();
		
		$orderBy =" order by id desc ";
		$setLimit=" limit $start, $limit";
		
		if($status!='A')
			$sql = "select * from dap_mass_actions where status='$status' ";
		else 
			$sql = "select * from dap_mass_actions ";
		
		if($fromDate!="") {
			if(strstr($sql,"where") == FALSE)
				$sql.=" where";
			else 
				$sql.=" and";
			$fromToDate="  last_update_ts between str_to_date('".$fromDate."', '%m-%d-%Y') and str_to_date('".$toDate."', '%m-%d-%Y')";
		}
		
		if($fromToDate!="")
			$sql.=$fromToDate;
			
		if($email!="") {
			if(strstr($sql,"where") == FALSE)
				$sql.=" where";
			else 
				$sql.=" and";
				
			$sql.=" payload like '%" . $email . "%'"  ;
		}
		
		if($dapjobtrackid!="") {
			if(strstr($sql,"where") == FALSE)
				$sql.=" where";
			else 
				$sql.=" and";
				
			$sql.=" (actionKey like '%" . $dapjobtrackid . "%'  or payload like '%" . $dapjobtrackid . "%')  ";
		}
		
		$sql.=$orderBy;
		
		if($pagination=="Y")
			$sql.=$setLimit;
			
	//	logToFile("in Dap_MassActions->load() sql=".$sql,LOG_DEBUG_DAP);
		
		$resourceArray = array();

		$stmt = $dap_dbh->prepare($sql);
		$stmt->execute();

		while ($obj = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$resourceArray[] = $obj;
		}
		
		return $resourceArray;
	}
	
	public function getCount($email,$dapjobtrackid,$fromDate,$toDate,$status) {
		logToFile("in Dap_MassActions->load()",LOG_DEBUG_DAP);
		
		if( !isset($start) || ($start == "undefined")  || ($start == "")) {
			//if(Dap_Session::get('start')
			$start = 0;
		}
			
		$count=0;
		$dap_dbh = Dap_Connection::getConnection();
		
		if($status!='A')
			$sql = "select count(*) as count from dap_mass_actions where status='$status' ";
		else 
			$sql = "select count(*) as count  from dap_mass_actions ";
		
		if($fromDate!="") {
			if(strstr($sql,"where") == FALSE)
				$sql.=" where";
			else 
				$sql.=" and";
			$fromToDate="  last_update_ts between str_to_date('".$fromDate."', '%m-%d-%Y') and str_to_date('".$toDate."', '%m-%d-%Y')";
		}
		
		if($fromToDate!="")
			$sql.=$fromToDate;
			
		if($email!="") {
			if(strstr($sql,"where") == FALSE)
				$sql.=" where";
			else 
				$sql.=" and";
				
			$sql.=" payload like '%" . $email . "%'"  ;
		}
		
		if($dapjobtrackid!="") {
			if(strstr($sql,"where") == FALSE)
				$sql.=" where";
			else 
				$sql.=" and";
				
			$sql.=" (actionKey like '%" . $dapjobtrackid . "%'  or payload like '%" . $dapjobtrackid . "%')  ";
		}
		//logToFile("in Dap_MassActions->getCount(): $sql",LOG_DEBUG_DAP);
		
		$stmt = $dap_dbh->prepare($sql);
		$stmt->execute();
		
		if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$count = $row["count"];
			logToFile("Count: $count",LOG_DEBUG_DAP);
		}
		
		return $count;
	}
	
 	public function load() {
		//logToFile("in Dap_MassActions->load()",LOG_DEBUG_DAP);
		$dap_dbh = Dap_Connection::getConnection();

		$sql = "select * from dap_mass_actions
				order by id desc";
		$resourceArray = array();

		$stmt = $dap_dbh->prepare($sql);
		$stmt->execute();

		while ($obj = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$resourceArray[] = $obj;
		}
		
		return $resourceArray;
	}
			
 	
	public static function deleteMassActionsData($status) {
		//logToFile("in Dap_MassActions->load()",LOG_DEBUG_DAP);
		try {
			$dap_dbh = Dap_Connection::getConnection();
			
			$sql = "delete from dap_mass_actions where ";
			
			if($status == "E") {
				$sql .= " status = '$status' ";
			} else if($status == "C") {
				$sql .= " status = '$status' and DATE(last_update_ts) < \"".date("Y-m-"). (date("d")-1)."\"";
			} else if($status == "A") {
				$sql .= "1";
			}

			//logToFile("$sql",LOG_DEBUG_DAP);
			$stmt = $dap_dbh->prepare($sql);
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

	public static function deleteJobFromJobQueue($id) {
		//logToFile("in Dap_MassActions->deleteJobFromJobQueue(), id: $id",LOG_DEBUG_DAP);
		try {
			$dap_dbh = Dap_Connection::getConnection();
			
			$sql = "delete from dap_mass_actions where id = :id";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
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