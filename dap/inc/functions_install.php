<?php

function dap_install($DB_NAME_DAP, $DB_USER_DAP, 
								$DB_PASSWORD_DAP, $DB_HOST_DAP, 
								$SITE_URL_DAP, 
								$ADMIN_FIRSTNAME_DAP, $ADMIN_EMAIL_DAP) {
	//logToFile(DAP_ROOT); 
	//lets do some checks, init setting etc
	
	$result = array();
	$ver = phpversion();
	
	if($ver < 5) {
		$result[0] = "100";
		$result[1] = "Sorry, you need a minimum of PHP version 5 in order to run DAP. Your current PHP version is: " . $ver . "<br/><br/> Installation failed. Please de-activate LiveLinks and re-activate it when you've resolved this issue.";
		return $result;
	} 
	
	if ( !class_exists('PDO') || !method_exists('PDO', 'query') || !class_exists('PDOStatement') || !class_exists('PDOException') || !in_array("mysql", PDO::getAvailableDrivers()) ) {
		$result[0] = "101";
		$result[1] = "Sorry, it appears that a required library (PDO) for connecting to a MySQL database has not been enabled. Pls ask your web host if your web site can have 'PHP PDO Enabled for MySQL'<br/><br/> Installation failed. Please de-activate LiveLinks and re-activate it when you've resolved this issue.";
		return $result;
	}
	
	//check if any of the required variables are not set/empty.
	if(empty($DB_NAME_DAP)) {
		$result[0] = "102";
		$result[1] = "Required field Database Name (DB_NAME_DAP) is missing. <br/> Installation failed. Please de-activate LiveLinks and re-activate it when you've fixed this issue.";
		return $result;	
	}
	
	$config_file = DAP_ROOT."dap-config.php";
	$config_template_file = DAP_ROOT."install/templates/config/dap-config-template.php";
	$install_ddl_file = DAP_ROOT."db/dap_ddl.php";
	$install_data_file = DAP_ROOT."db/dap_data.php";
	
	//if the dap-config is there, check if tables exist
	if(file_exists($config_file)) {
		//echo $config_file;
		//Check if tables exist too...
		//echo "Config file exists. Checking to see if database tables exist...<br/>";
		include_once($config_file);
		//echo "Creating connection for checking...<br>";

		try { //First check if the info in the dap-config file are correct
			$dap_dbh = Dap_Connection::getConnection();
		} catch (PDOException $e) {
			$result[0] = "103";
			//$result[1] = $e->getMessage() . $DB_ERROR_INCORRECT;
			$result[1] = $e->getMessage();
			return $result;
		} catch (Exception $e) { 
			$result[0] = "104";
			$result[1] = $e->getMessage() . $DB_ERROR_INCORRECT;
			return $result;
		}
		
		//echo "Obtained Connection...<br>";
		//echo "Checking if tables exist...<br/>";
		
		try{	
			$sql = "select count(*) as count from dap_config";
			$stmt = $dap_dbh->prepare($sql);
			$stmt->execute();
			if ( $row = $stmt->fetch() ) {
				$result[0] = "105";
				$result[1] = "WARNING: You probably already have a working installation of DAP. So, if you are trying to do a FRESH install of DAP, then delete any existing DAP tables and re-try.<br/><br/>But if you just happened to de-activate & activate LiveLinks for some other reason, then don't worry - nothing has changed in your DAP installation. You may continue using DAP as usual.";
				return $result;
			}
		} catch (PDOException $e) {
			//Eat it because if there is a PDO Exception, it means that
			//the database has not been created, and installation should continue
		} catch (Exception $e) {
			//Eat it because if there is a PDO Exception, it means that
			//the database has not been created, and installation should continue
		}
	} else { //config file doesn't exist, so create it
		$configTemplateFileText = file_get_contents($config_template_file);
		$fd = fopen($config_file, "w");
		fwrite($fd, $configTemplateFileText);
		fclose($fd);
		
		$configFileText = file_get_contents($config_file);
		$configFileText = str_replace("%%DB_NAME_DAP%%", $DB_NAME_DAP, $configFileText);
		$configFileText = str_replace("%%DB_USER_DAP%%", $DB_USER_DAP, $configFileText);
		$configFileText = str_replace("%%DB_PASSWORD_DAP%%", $DB_PASSWORD_DAP, $configFileText);
		$configFileText = str_replace("%%DB_HOST_DAP%%", $DB_HOST_DAP, $configFileText);
		$configFileText = str_replace("%%SITE_URL_DAP%%", $SITE_URL_DAP, $configFileText);
		$fd = fopen($config_file, "w");
		fwrite($fd, $configFileText);
		fclose($fd);		
		
		//After creation let us make sure we did file write, if not exit
		if(!file_exists($config_file)) {
			$result[0] = "106";
			$result[1] = "Oops! Could not create the config file (dap-config.php). Please make the 'dap' folder writable by doing CHMOD 755 (and if that doesn't work, then try CHMOD 777.) <br/> Installation failed. Please de-activate LiveLinks and re-activate it when you've fixed the issue.";
			return $result;		
		}
	}
	
	//Everything looks good at this point - so start installation

	include_once($config_file);	 	

	try {
		//echo "Creating Database Tables....<br>";
		$sql_ddl = array();
		$sql_data = array();
		include_once($install_ddl_file);
		$sql_ddl = array_merge($sql_ddl, $sql_data);
		//echo "Creating connection.....<br>";
		$dap_dbh = Dap_Connection::getConnection();
		//echo "Obtained Connection...<br>";
		//echo "Executing SQL for DDL.....<br>";
		$i=1;
		foreach($sql_ddl as $sql) {
			try {
				$stmt = $dap_dbh->prepare($sql);
				$stmt->execute();
				//echo $sql . "<br/>";
				$stmt = null;
			} catch (PDOException $e) {
				$result[0] = "107";
				$result[1] = $e->getMessage();
				return $result;
			} catch (Exception $e) {
				$result[0] = "108";
				$result[1] = $e->getMessage();
				return $result;
			}
		}
		//echo "Completed creation of tables.....<br>";

		//$response = "";

		$sql_ddl = array();
		$sql_data = array();
		include_once($install_data_file);
		$sql_data = array_merge($sql_ddl, $sql_data);
		//echo "Inserting Data into the Database....<br>";

		//Insert Data
		foreach($sql_data as $sql) {
			try {
				$stmt = $dap_dbh->prepare($sql);
				$stmt->execute();
				//echo $sql . "<br/>";
				$stmt = null;
			} catch (PDOException $e) {
				$result[0] = "109";
				$result[1] = $e->getMessage();
				return $result;
			} catch (Exception $e) {
				$result[0] = "110";
				$result[1] = $e->getMessage();
				return $result;
			}
		}
		
		//echo "Completed inserting data.....<br/><br/><br/>";
		
		$response = "SUCCESS! DAP installation is now ALMOST Done...<br/><br/>
		
		Just One Last Thing...<br/><br/> 

Go to <a href=\"options-permalink.php\" target=\"_blank\">'Settings > Permalinks'</a> (opens in a new window) and choose <strong>ANY permalink structure other than 'Default'</strong> and EVEN if you already have a non-default setting selected, you <strong>MUST MUST MUST click on 'Save Changes'</strong> for the DAP installation to be complete.<br/><br/>

Once you've done that, DAP installation is then complete!<br/><br/>

!!!******** IMPORTANT ********!!!<br/>
A new DAP Admin account has been created for you.<br/><br/>

When you log in as WP Admin, you will now also be logged in automatically as DAP Admin.<br/><br/>

For what it's worth, please store this DAP Admin email/password below somewhere safe...<br/><br/>

<b>Email</b>: $ADMIN_EMAIL_DAP<br/>
<b>Password</b>: dap<br/><br/> 
!!!***************************!!!<br/><br/>
		
You may now log in to DAP Admin Control Panel right here within your WordPress Admin Panel, by clicking on the new link 'DigitalAccessPass' towards the bottom left.<br/><br/>
(Or you may also log in directly, outside of WordPress, at: ".SITE_URL_DAP . "/dap/ )<br/><br/>

PLEASE NOTE:<br/>
When you login to DAP on *YOUR* site ( either by clicking on the new 'DigitalAccessPass' link towards the bottom left, or directly, outside of WordPress, at: ".SITE_URL_DAP . "/dap/ ) using the login info above, you will notice a \"license notification\" message.<br/><br/>

What you have to do is this...<br/><br/>

1) Login to OUR web site at http://digitalaccesspass.com/dap using the credentials we sent you when you first purchased DAP (if you do not know what it is, then just click on the forgot password link in the login page - http://www.digitalaccesspass.com/dap)
and after you login to this member's area on OUR site, click on the link that says 'Get License Key'.<br/><br/>

2) Copy that license key, come back to DAP admin panel on YOUR site right here, and follow the instructions there to update license key.<br/><br/>

That's it!<br/><br/>

";

		//Send email to admin with login info
		$subject = " Your DAP Admin Login Info";
		$bodyText = "SUCCESS! DAP has been successfully installed.\n\n
The following DAP Admin account has been created for you:\n
Please note that the 'DAP Admin' login is *COMPLETELY* separate from your 'WordPress Admin' login\n
So PLEASE store this important login information carefully...\n
			
------------------------				
Login: $ADMIN_EMAIL_DAP
Password: dap
------------------------				
		
You can manage DAP right from within your WordPress Admin Panel, by clicking on the new link 'DigitalAccessPass' (towards the bottom left in your WP Admin Panel.)\n\n
(Or you may also login to DAP (outside of WordPress), at: " . SITE_URL_DAP . "/dap/ )";

		if( $ADMIN_EMAIL_DAP !== ("admin@".$domain_name[0]) ) {
			sendAdminEmail($subject, $bodyText);
		}
		
		$result[0] = "111";
		$result[1] = $response;
		
		//Set cookie for 10 years for the new DAP-within-WP
		$password = md5("dap");
		setcookie("dapcookie_email",$ADMIN_EMAIL_DAP,time()+3600*24*3650,"/");
		setcookie("dapcookie_password",$password,time()+3600*24*3650,"/");		
		return $result;		
	} catch (PDOException $e) {
		$result[0] = "112";
		//$result[1] = $e->getMessage();
		$result[1] = $e->getMessage() . " - " . $config_file;
		return $result;
	} catch (Exception $e) {
		$result[0] = "113";
		$result[1] = $e->getMessage();
		return $result;
	}
	
}


?>