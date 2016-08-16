<?php
	error_reporting(E_ALL);
	$response = "";
	$configFile = "../dap-config.php";
	$ADMIN_FIRSTNAME_DAP = "Admin";
	preg_match("/[^www\.].+/", $_SERVER['HTTP_HOST'],$domain_name);
	$ADMIN_EMAIL_DAP = "admin@" . $domain_name[0];
	$DB_ERROR_INCORRECT = "<br/><br/><b>Oops, it appears that the database details you provided were incorrect.<br/>Please delete dap-config.php, check the database details and re-try.</b><br/><br/>" . $response;

	//No config file, no post variables exist. So, first time. Present form
	if( !isset($_POST['DB_NAME_DAP']) && !file_exists($configFile) ) {
		include "installation_form.html";
		exit;
	}

	//No config file, post variables exist. So, create config file from form
	if( isset($_POST['mode']) && !file_exists($configFile) ) {
		$DB_NAME_DAP = isset($_POST['DB_NAME_DAP']) ? $_POST['DB_NAME_DAP'] : "";
		$DB_USER_DAP = isset($_POST['DB_USER_DAP']) ? $_POST['DB_USER_DAP'] : "";
		$DB_PASSWORD_DAP = isset($_POST['DB_PASSWORD_DAP']) ? $_POST['DB_PASSWORD_DAP'] : "";
		$DB_HOST_DAP = isset($_POST['DB_HOST_DAP']) ? $_POST['DB_HOST_DAP'] : "";
		$SITE_URL_DAP = isset($_POST['SITE_URL_DAP']) ? $_POST['SITE_URL_DAP'] : "";
		$ADMIN_FIRSTNAME_DAP = addslashes(isset($_POST['ADMIN_FIRSTNAME_DAP']) ? $_POST['ADMIN_FIRSTNAME_DAP'] : $ADMIN_FIRSTNAME_DAP);
		$ADMIN_EMAIL_DAP = addslashes(isset($_POST['ADMIN_EMAIL_DAP']) ? $_POST['ADMIN_EMAIL_DAP'] : $ADMIN_EMAIL_DAP);

		//Start Config Creation
		echo "Starting Config Creation.....<br>";
		
		try {
			$response = "SUCCESS! Config file has been successfully created<br/>";
			$configTemplateFile = "templates/config/dap-config-template.php";
			$configTemplateFileText = file_get_contents($configTemplateFile);
			$fd = fopen($configFile, "w");
			fwrite($fd, $configTemplateFileText);
			fclose($fd);
			
			$configFileText = file_get_contents($configFile);
			$configFileText = preg_replace("/%%DB_NAME_DAP%%/", $DB_NAME_DAP, $configFileText);
			$configFileText = preg_replace("/%%DB_USER_DAP%%/", $DB_USER_DAP, $configFileText);
			$configFileText = preg_replace("/%%DB_PASSWORD_DAP%%/", $DB_PASSWORD_DAP, $configFileText);
			$configFileText = preg_replace("/%%DB_HOST_DAP%%/", $DB_HOST_DAP, $configFileText);
			$configFileText = preg_replace("/%%SITE_URL_DAP%%/", $SITE_URL_DAP, $configFileText);
			$fd = fopen($configFile, "w");
			fwrite($fd, $configFileText);
			fclose($fd);
			echo "Finished creating config file<br/>";
		} catch (PDOException $e) {
			$response .= "<b>Oops, there was a problem with your database settings (incorrect host name, user name or password). <br/>Please double check and re-try.</b><br/><br/>" . $e->getMessage() . "<br/>";
			die($response);
		} catch (Exception $e) {
			$response .= $e->getMessage() . "<br/>";
			die($response);
		}
		
	} //End Config Creation

	$response = "";
	if(file_exists($configFile)) {
		//Check if tables exist too...
		echo "Config file exists. Checking to see if database tables exist...<br/>";
		include($configFile);
		echo "Creating connection for checking...<br>";

		try { //First check if the info in the dap-config file are correct
			$dap_dbh = Dap_Connection::getConnection();
		} catch (PDOException $e) {
			$response .= $e->getMessage() . "<br/>";
			die($response . $DB_ERROR_INCORRECT);
		} catch (Exception $e) {
			$response .= $e->getMessage() . "<br/>";
			die($response . $DB_ERROR_INCORRECT);
		}
		
		echo "Obtained Connection...<br>";
		echo "Checking if tables exist...<br/>";
		
		try{	
			$sql = "select count(*) as count from dap_config";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->execute();
			if ( $row = $stmt->fetch() ) {
				$response .= "<br/><br/><b>Oops, it looks like the DAP database has already been created. Please delete any existing DAP tables and re-try.</b><br/><br/>";
				die($response);				
			}
			echo "<br/>";
		} catch (PDOException $e) {
			//Eat it because if there is a PDO Exception, it means that
			//the database has not been created, and installation should continue
		} catch (Exception $e) {
			//Eat it because if there is a PDO Exception, it means that
			//the database has not been created, and installation should continue
		}
	} 
	
	//Finally, file exists, but no tables exist. So execute sql.
	echo "No tables exist. So this is a clean install.....<br>";
	include_once($configFile);
	$response = "";
	
	try {
		echo "Creating Database Tables....<br>";
		$sql_ddl = array();
		include("../db/dap_ddl.php");
		echo "Creating connection.....<br>";
		$dap_dbh = Dap_Connection::getConnection();
		echo "Obtained Connection...<br>";
		echo "Executing SQL for DDL.....<br>";
		$i=1;
		foreach($sql_ddl as $sql) {
			try {
				$stmt = $dap_dbh->prepare($sql);
				$stmt->execute();
				echo $sql . "<br/>";
				$stmt = null;
			} catch (PDOException $e) {
				$response .= $e->getMessage() . "<br/>";
				die ($response);
			} catch (Exception $e) {
				$response .= $e->getMessage() . "<br/>";
				die ($response);
			}
		}
		echo "Completed creation of tables.....<br>";

		$response = "";

		$sql_data = array();
		include("../db/dap_data.php");
		echo "Inserting Data into the Database....<br>";

		//Insert Data
		foreach($sql_data as $sql) {
			try {
				$stmt = $dap_dbh->prepare($sql);
				$stmt->execute();
				echo $sql . "<br/>";
				$stmt = null;
			} catch (PDOException $e) {
				$response .= $e->getMessage() . "<br/>";
				echo ("**************".$response);
			} catch (Exception $e) {
				$response .= $e->getMessage() . "<br/>";
				echo ("**************".$response);
			}
		}
		
		echo "Completed inserting data.....<br/><br/><br/>";
		
		$response = "SUCCESS! DAP has been successfully installed.<br/>
The following admin account has been created for you:<br/><br/>
		
[WARNING: Don't forget to change your password when you first log in.]<br/>
		
<b>Login</b>: $ADMIN_EMAIL_DAP<br/>
<b>Password</b>: dap<br/><br/> 
		
Login at: <a href='" . SITE_URL_DAP . "/dap/'>" . SITE_URL_DAP . "/dap/</a>";

		echo $response;
		
		//Send email to admin with login info
		$subject = $ADMIN_FIRSTNAME_DAP . ", Your DAP Admin Login Info";
		$bodyText = "SUCCESS! DAP has been successfully installed.
The following admin account has been created for you:\n
			
[WARNING: Don't forget to change your password when you first log in.]\n

------------------------				
Login: $ADMIN_EMAIL_DAP
Password: dap
------------------------				
		
Login at: " . SITE_URL_DAP . "/dap/";
		if( $ADMIN_EMAIL_DAP !== ("admin@".$domain_name[0]) ) {
			sendAdminEmail($subject, $bodyText);
		} else {
			echo "<font color='#CC0000'><br/>WARNING! DANGER! DO NOT FORGET to change your email address from $ADMIN_EMAIL_DAP <br/>to an email address of your choice, as soon as you login.</font>";
		}
	} catch (PDOException $e) {
		die ($e->getMessage());
	} catch (Exception $e) {
		die ($e->getMessage());
	} 



?>
