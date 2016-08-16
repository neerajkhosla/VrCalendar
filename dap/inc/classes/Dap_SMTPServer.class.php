<?php

class Dap_SMTPServer extends Dap_Base {

	var $id;
	var $description;
	var $server;
	var $port;
	var $ssl;
	var $userid;
	var $password;
 	var $limit_per_hour;
	var $running_total;
	var $active;
		
	function getId() {
	        return $this->id;
	}
	function setId($o) {
	      $this->id = $o;
	}
	
	function getDescription() {
	        return $this->description;
	}
	function setDescription($o) {
	      $this->description = $o;
	}
	
	function getServer() {
		return $this->server;
	}
	function setServer($o) {
		return $this->server = $o;
	}

	function getPort() {
		return $this->port;
	}
	function setPort($o) {
	      $this->port = $o;
	}

	function getSsl() {
	        return $this->ssl;
	}
	function setSsl($o) {
	      $this->ssl = $o;
	}
	
	function getUserid() {
	        return $this->userid;
	}
	function setUserid($o) {
	      $this->userid = $o;
	}

	function getPassword() {
	        return $this->password;
	}
	function setPassword($o) {
	      $this->password = $o;
	}
	
	function getLimit_per_hour() {
	        return $this->limit_per_hour;
	}
	function setLimit_per_hour($o) {
	      $this->limit_per_hour = $o;
	}

	function getRunning_total() {
	        return $this->running_total;
	}
	function setRunning_total($o) {
	      $this->running_total = $o;
	}

	function getActive() {
	        return $this->active;
	}
	function setActive($o) {
	      $this->active = $o;
	}
	


	//This function needs additional work to return the number of emails caller can 
	// send using this server before it should look for different server;
	function getUseableLimit() {
		return $this->limit_per_hour;
	}

	public static function get() {

		if(THIRD_PARTY_SMTP_ENABLED == "Y")  {
			$qualifier = "";
		} else {
			$qualifier = " and server = 'Local_Web_Host' ";
		}
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$sql = "select * from dap_smtp_servers
					where 
					active = 'Y' " .
					$qualifier .
					" and (limit_per_hour - running_total) > 0
					order by (limit_per_hour -running_total)*0.8 desc
					limit 1
				";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->execute();
			if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				//echo "Got Results";
				$server = new Dap_SMTPServer();
				$server->setId($row['id']);
				$server->setServer($row['server']);
				$server->setPort($row['port']);
				$server->setSsl($row['ssl']);
				$server->setUserid($row['userid']);
				$server->setPassword($row['password']);
				$server->setLimit_per_hour( ceil(($row['limit_per_hour'] - $row['running_total'])*0.8));
				return $server;
			}
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}	
		return;
	}

	public static function init() {
		try {
			$dap_dbh = Dap_Connection::getConnection();
	
			$sql = "update 
						dap_smtp_servers
					set 
						running_total = 0
					where
						active = 'Y'
					";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->execute();
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}

	public function updateRunningTotal($sent_count) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
	
			$sql = "update 
						dap_smtp_servers
					set 
						running_total = running_total + :count
					where
						id = :id
					";
			$stmt = $dap_dbh->prepare($sql);
			
			$stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
			$stmt->bindParam(':count', $sent_count, PDO::PARAM_STR);
			$stmt->execute();
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}

	public static function loadSMTP() {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			
			$sql = "select * from dap_smtp_servers order by id asc";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->execute();
			
			$smtp = array();
			
			while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
				$smtp[] = $row;
			}
			
			return $smtp;

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
			//logToFile("Dap_SMTPServer: " . $this->getSsl()); 
			//logToFile("id: " . $this->getId(), LOG_DEBUG_DAP);
	
			$sql = "update 
						dap_smtp_servers
					set 
						description = :description,
						server = :server,
						port = :port,
						`ssl` = :ssl,
						userid = :userid,
						password = :password,
						limit_per_hour = :limit_per_hour,
						running_total = :running_total,
						active = :active
					where
						id = :id
					";
			//logToFile("SQL: $sql"); 
			/**
			logToFile(
					  	$this->getId() . ", " . 
						$this->getDescription() . ", " . 
						$this->getServer() . ", " . 
						$this->getPort() . ", " . 
						$this->getSsl() . ", " . 
						$this->getUserid() . ", " . 
						$this->getPassword() . ", " . 
						$this->getLimit_per_hour() . ", " . 
						$this->getRunning_total() . ", " . 
						$this->getActive()
			); 
			*/
					
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $this->getId(), PDO::PARAM_INT);
			$stmt->bindParam(':description', $this->getDescription(), PDO::PARAM_STR);
			$stmt->bindParam(':server', $this->getServer(), PDO::PARAM_STR);
			$stmt->bindParam(':port', $this->getPort(), PDO::PARAM_INT);
			$stmt->bindParam(':ssl', $this->getSsl(), PDO::PARAM_STR);
			$stmt->bindParam(':userid', $this->getUserid(), PDO::PARAM_STR);
			$stmt->bindParam(':password', $this->getPassword(), PDO::PARAM_STR);
			$stmt->bindParam(':limit_per_hour', $this->getLimit_per_hour(), PDO::PARAM_INT);
			$stmt->bindParam(':running_total', $this->getRunning_total(), PDO::PARAM_INT);
			$stmt->bindParam(':active', $this->getActive(), PDO::PARAM_STR);
			$stmt->execute();
			
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}		
	}

	public static function deleteSMTP($id) {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			
			$sql = "delete from dap_smtp_servers where id = :id";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
			
			return;

		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}	
	}
	
	
	public function create() {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			
			/*
				WARNING: DO NOT REMOVE special single quotes from around `ssl` in the query below.
				Query will fail
			*/
			$sql = "insert into dap_smtp_servers
					(description, server, port, `ssl`, userid, password, limit_per_hour, running_total, active)
					values
					(:description, :server, :port, :ssl, :userid, :password, :limit_per_hour, :running_total, :active)
					";
					
			//logToFile("getactive: " . $this->getActive, LOG_DEBUG_DAP);

			$stmt = $dap_dbh->prepare($sql);
			$stmt->bindParam(':description', $this->getDescription(), PDO::PARAM_STR);
			$stmt->bindParam(':server', $this->getServer(), PDO::PARAM_STR);
			$stmt->bindParam(':port', $this->getPort(), PDO::PARAM_INT);
			$stmt->bindParam(':ssl', $this->getSsl(), PDO::PARAM_STR);
			$stmt->bindParam(':userid', $this->getUserid(), PDO::PARAM_STR);
			$stmt->bindParam(':password', $this->getPassword(), PDO::PARAM_STR);
			$stmt->bindParam(':limit_per_hour', $this->getLimit_per_hour(), PDO::PARAM_INT);
			$stmt->bindParam(':running_total', $this->getRunning_total(), PDO::PARAM_INT);
			$stmt->bindParam(':active', $this->getActive(), PDO::PARAM_STR);
			$stmt->execute();
			
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
