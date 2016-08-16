<?php
class Dap_Connection extends Dap_Base {

	public static $dsn;
	public static $user;
	public static $password;
	public static $dap_dbh;

	public static function getConnection($dap_dbh=NULL, $db_name=DB_NAME_DAP, $db_user=DB_USER_DAP, $db_password=DB_PASSWORD_DAP, $db_host=DB_HOST_DAP) {
		if(empty($dap_dbh) === FALSE) {
			return $dap_dbh;
		}
		try {
			if(strstr($db_host,":")!=false) {
				if(! is_numeric ($db_host[0]) ) {
					$result=explode(":",$db_host);
					$db_host=$result[0];
				}
			}
			
			$dsn = "mysql:host=" . $db_host . ";dbname=" . $db_name;
			$user = $db_user;
			$password = $db_password;				

			$dap_dbh = new PDO($dsn, $user, $password, array( PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8' ));
			
			if(defined('DAPTIMEZONE')) {
				$dap_dbh->exec("SET time_zone='".DAPTIMEZONE."';");
			}
			
			$dap_dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$dap_dbh->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
			//$dap_dbh->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES utf8");
		} catch (PDOException $e) {
			//logToFile($e->getMessage(),LOG_DEBUG_DAP);
			throw $e;
		} catch (Exception $e) {
			//logToFile($e->getMessage(),LOG_DEBUG_DAP);
			throw $e;
		}
		return $dap_dbh;
	}

}	
	
?>