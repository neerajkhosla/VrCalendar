<?php

class Dap_Templates extends Dap_Base {

  	//Log the message to DB for now.
	public static function log($msg, $level=5) {
  		$logLevelDAP = Dap_Config::get("LOG_LEVEL_DAP");
  	
		if($logLevelDAP == "" ) {
			$logLevelDAP = 5;
		}
		//
  		if( $level <= $logLevelDAP ) {
			try {
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
	
	//Display the 'option' tags for select list
	public static function displaySelect() {
		try {
			$dap_dbh = Dap_Connection::getConnection();  
			$sql = "select id, name, description, content
				from dap_templates
				";		
			$stmt = $dap_dbh->prepare($sql);
			$stmt->execute();
			
			while ($row = $stmt->fetch()) {
		  echo "<option value=\"".$row['id']."\">".$row['description']."</option>";
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
  
	public static function getContent($templateId) {
		try {
		  $data = "";
			$dap_dbh = Dap_Connection::getConnection();  
			$sql = "select id, content
				from dap_templates
				where id = :id
				";		
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $templateId, PDO::PARAM_INT);    			
			$stmt->execute();
			
			if ($row = $stmt->fetch()) {
				$data = stripslashes($row['content']);
			}
			$stmt = null;
			$dap_dbh = null;
			return $data;
		} catch (PDOException $e) {
			//logToFile("bulkEmailInsertCollectionCode: " . $e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			//logToFile("bulkEmailInsertCollectionCode: " . $e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
  }	  
  
	public static function getContentByName($templateName) {
		try {
			$data = "";
			$dap_dbh = Dap_Connection::getConnection();  
			
			$sql = "select 
						id, content
					from 
						dap_templates
					where 
						name = :name
					";
			
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':name', $templateName, PDO::PARAM_STR);    			
			$stmt->execute();
			
			if ($row = $stmt->fetch()) {
				$data = $row['content'];
			}
			$stmt = null;
			$dap_dbh = null;
			
			return $data;
		} catch (PDOException $e) {
			//logToFile("bulkEmailInsertCollectionCode: " . $e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			//logToFile("bulkEmailInsertCollectionCode: " . $e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
  	}  

	public static function saveContent($templateId, $content) {
		try {
		  $data = "";
			$dap_dbh = Dap_Connection::getConnection();  
			$sql = "update dap_templates 
				set content =:content
				where id = :id
				";		
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $templateId, PDO::PARAM_INT);   
	  		$stmt->bindParam(':content', $content, PDO::PARAM_INT); 			
			$stmt->execute();
			$stmt = null;
			$dap_dbh = null;
			return $data;
		} catch (PDOException $e) {
			//logToFile("bulkEmailInsertCollectionCode: " . $e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			//logToFile("bulkEmailInsertCollectionCode: " . $e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
  	}	  
}

?>