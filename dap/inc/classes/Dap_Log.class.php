<?php

class Dap_Log extends Dap_Base {

  //Log the message to DB for now.
	public static function log($msg, $level=5) {
  	$logLevelDAP = Dap_Config::get("LOG_LEVEL_DAP");
  	
  	if($logLevelDAP == "" ) {
  		$logLevelDAP = 5;
  	}
  	//
	
  	if( $level <= $logLevelDAP ) {
    		try {
    			$msg = substr($msg,0,200);
    			$dap_dbh = Dap_Connection::getConnection();  
    			$sql = "insert into dap_log
    						(logtime, level, msg)
    						values
    						(now(), :level, :msg)";		
    			$stmt = $dap_dbh->prepare($sql);
    			$stmt->bindParam(':level', $logLevelDAP, PDO::PARAM_INT);
    			$stmt->bindParam(':msg', $msg, PDO::PARAM_STR);
    			$stmt->execute();
    			
    			$stmt = null;
    			$dap_dbh = null;
    		} catch (PDOException $e) {
    			//logToFile("bulkEmailInsertCollectionCode: " . $e->getMessage(),LOG_FATAL_DAP);
    			throw $e;
    		} catch (Exception $e) {
    			//logToFile("bulkEmailInsertCollectionCode: " . $e->getMessage(),LOG_FATAL_DAP);
    			throw $e;
    		}
  	}	
		
	}
	
	//Display/Echo the log for one day (today);
	public static function getLog() {
    		try {
    			$dap_dbh = Dap_Connection::getConnection();  
    			$sql = "select logtime, level, msg
                    from dap_log
                    where
                    date(logtime) = curdate() 
                    ";		
    			$stmt = $dap_dbh->prepare($sql);
    			$stmt->execute();
    			
    			while ($row = $stmt->fetch()) {
              $log = $log . $row['logtime'] . ":" . $row["msg"] . "\n";
  			   }
    			$stmt = null;
    			$dap_dbh = null;
    			return $log;
    		} catch (PDOException $e) {
    			//logToFile("bulkEmailInsertCollectionCode: " . $e->getMessage(),LOG_FATAL_DAP);
    			throw $e;
    		} catch (Exception $e) {
    			//logToFile("bulkEmailInsertCollectionCode: " . $e->getMessage(),LOG_FATAL_DAP);
    			throw $e;
    		}   

  }	
  
  
	//Clear (truncate table) all log entries from the Dap_Log table, older than X days.
	//Currently X=5 (hardcoded)
	//To-do: Make "days" configurable
	public static function clear() {
		try {
			//$days = Dap_Config::get("LOG_KEEP_DAYS");
			$days = 5;
			
			$dap_dbh = Dap_Connection::getConnection();  
			$sql = 'delete from 
						dap_log
					where
						(logtime between "2008-01-01" and (now() - interval ' . $days . ' day) )
					';
			$stmt = $dap_dbh->prepare($sql);
			$stmt->execute();
			$stmt = null;
			$dap_dbh = null;
			
		} catch (PDOException $e) {
			throw $e;
		} catch (Exception $e) {
			throw $e;
		}   
  	}  

	//Fully truncate dap_log table
	public static function emptyLogs() {
		try {
			$dap_dbh = Dap_Connection::getConnection();  
			$sql = "TRUNCATE TABLE `dap_log`";		
			$stmt = $dap_dbh->prepare($sql);
			$stmt->execute();
			$stmt = null;
			$dap_dbh = null;
		} catch (PDOException $e) {
			throw $e;
		} catch (Exception $e) {
			throw $e;
		}   
  }  

}
?>