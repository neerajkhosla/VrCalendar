<?php

class Dap_Config extends Dap_Base {
	
	
	public static function loadConfig($reload=true, $cat="all") {
		if( !isset($reload) || ($reload != true) ) {
			return;
		}

		try {
			$dap_dbh = Dap_Connection::getConnection();
			
			if($cat == "all") {
				$sql = "select id, name, value from dap_config order by editable desc, id asc";
			} else {
				$sql = "select id, name, value from dap_config where category = '".$cat."' order by editable desc, display_order asc";
			}
			
			$stmt = $dap_dbh->prepare($sql);
			$stmt->execute();
			
			$config = array();
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$config[] = $row;
			}
			
			Dap_Session::set('config',$config);
			
			$stmt = null;
			$dap_dbh = null;
			return $config;			
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			$stmt = null;
			$dap_dbh = null;
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			$stmt = null;
			$dap_dbh = null;
			throw $e;
		}	
	}


	public static function updateConfig($id, $value) {
		try {
			//logToFile("(updateConfig)id: $id, value: $value",LOG_DEBUG_DAP); 
			$dap_dbh = Dap_Connection::getConnection();
	
			$sql = "update 
						dap_config
					set 
						value = :value
					where
						id = :id
					";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->bindParam(':value', $value, PDO::PARAM_STR);
			$stmt->execute();
			Dap_Config::loadConfig(true);
			$stmt = null;
			$dap_dbh = null;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			$stmt = null;
			$dap_dbh = null;
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			$stmt = null;
			$dap_dbh = null;
			throw $e;
		}		
	}

	public static function updateConfigName($name, $value) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
	
			$sql = "update 
						dap_config
					set 
						value = :value
					where
						name = :name
					";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':name', $name, PDO::PARAM_INT);
			$stmt->bindParam(':value', $value, PDO::PARAM_STR);
			$stmt->execute();
			Dap_Config::loadConfig(true);
			
			$stmt = null;
			$dap_dbh = null;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			$stmt = null;
			$dap_dbh = null;
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			$stmt = null;
			$dap_dbh = null;
			throw $e;
		}		
	}


	public static function getSitewide_error_page() {
		return "/dap/product-error.php";
	}
	
	
	public static function get($key,$what="value") {
		if(defined($key)) {
			return constant($key);
		}
		
		$config = Dap_Session::get('config');
		if( !isset($config) ||  ($config == "") ) {
			Dap_Config::loadConfig(true);
			$config = Dap_Session::get('config');
		}
		
		foreach ($config as $row) {
			if($row["name"] == $key) {
				//logToFile($row[$what],LOG_DEBUG_DAP);
				return $row[$what];
			}
		}
		
		return "";
	}
	


	public static function loadConfigArray($reload=true, $cat="all") {
		if( !isset($reload) || ($reload != true) ) {
			return;
		}

		try {
			$dap_dbh = Dap_Connection::getConnection();
			
			if($cat == "all") {
				$sql = "select * from dap_config order by editable desc, id asc";
			} else {
				$sql = "select * from dap_config where category = '".$cat."' order by editable desc, display_order asc";
			}
			
			$stmt = $dap_dbh->prepare($sql);
			$stmt->execute();
			
			$config = array();
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$config[$row["name"]] = $row;
			}
			
			Dap_Session::set('config',$config);
			
			$stmt = null;
			$dap_dbh = null;
			return $config;			
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			$stmt = null;
			$dap_dbh = null;
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			$stmt = null;
			$dap_dbh = null;
			throw $e;
		}	
	}



	public static function updateConfigNameArray($configArray) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			
			foreach ($configArray as $key=>$value) {
				//if($key == "SHOW_REFERRAL_DETAIL") {
					//logToFile("Inserting key: $key, value: $value"); 
				//}
				$sql = "update 
							dap_config
						set 
							value = :value
						where
							name = :name
						";
				$stmt = $dap_dbh->prepare($sql);
				$stmt->bindParam(':name', $key, PDO::PARAM_STR);
				$stmt->bindParam(':value', $value, PDO::PARAM_STR);
				$stmt->execute();
				$sql = null;
				$stmt = null;
			}
			
			Dap_Config::loadConfig(true);
			
			$dap_dbh = null;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			$stmt = null;
			$dap_dbh = null;
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			$stmt = null;
			$dap_dbh = null;
			throw $e;
		}		
	}

}

?>
