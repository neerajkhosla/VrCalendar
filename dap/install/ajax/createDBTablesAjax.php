<?php 
	include ("../../dap-config.php");

	$responseArray = array();
	$response = "";
	$mainResponse = "";
  
	if ( isset($_GET['whatRequest']) && ($_GET['whatRequest'] != "") ) {
		$whatRequest = $_GET['whatRequest'];
		$responseArray['whatRequest'] = $whatRequest;
	}


	try {
		$ADMIN_FIRSTNAME_DAP = addslashes(isset($_GET['ADMIN_FIRSTNAME_DAP']) ? $_GET['ADMIN_FIRSTNAME_DAP'] : "Admin");
		$ADMIN_EMAIL_DAP = addslashes(isset($_GET['ADMIN_EMAIL_DAP']) ? $_GET['ADMIN_EMAIL_DAP'] : "you@yoursite.com");
		
		$sql_ddl = array();
		include("../../db/dap_ddl.php");
		
		$dap_dbh = Dap_Connection::getConnection();

		//Create DB Tables
		foreach($sql_ddl as $sql) {
			try {
				$stmt = $dap_dbh->prepare($sql);
				$stmt->execute();
				$stmt = null;
			} catch (PDOException $e) {
				$response .= ERROR_GENERAL;
			} catch (Exception $e) {
				$response .= $e->getMessage() . "<br/>";
			}
		}

		if($response == "") {
			$mainResponse = "SUCCESS! Database Tables have been successfully created<br/>";
		} else {
			$mainResponse = $response;
		}
		
		$response = "";

		$sql_data = array();
		include("../../db/dap_data.php");
		
		//Insert Data
		foreach($sql_data as $sql) {
			try {
				$stmt = $dap_dbh->prepare($sql);
				$stmt->execute();
				$stmt = null;
			} catch (PDOException $e) {
				$response .= $e->getMessage() . "<br/>";
			} catch (Exception $e) {
				$response .= $e->getMessage() . "<br/>";
			}
		}
		
		if($response == "") {
			$mainResponse = "SUCCESS! DAP has been successfully installed.<br/>
			The following admin account has been created for you:<br/><br/>
			
			[WARNING: Don't forget to change your password when you first log in.]<br/>
			
			<b>Login</b>: $ADMIN_EMAIL_DAP<br/>
			<b>Password</b>: dap<br/><br/> 
			
			Login at: <a href='" . SITE_URL_DAP . "/dap/'>" . SITE_URL_DAP . "/dap/</a>";
			
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
		sendAdminEmail($subject, $bodyText);
		} else {
			$mainResponse .= $response;
		}
	} catch (PDOException $e) {
		$mainResponse .= ERROR_DB_INSTALLATION . $e->getMessage() . "<br/>";
	} catch (Exception $e) {
		$mainResponse .= ERROR_DB_INSTALLATION . $e->getMessage() . "<br/>";
	} 
	

	$responseArray['responseJSON'] = $mainResponse;
	$responseJSON = json_encode($responseArray);
	echo $responseJSON;
	
?>