<?php 
	$responseArray = array();
	$response = "";
  
	if ( isset($_POST['whatRequest']) && ($_POST['whatRequest'] != "") ) {
		$whatRequest = $_POST['whatRequest'];
		$responseArray['whatRequest'] = $whatRequest;
	}
	
	$DB_NAME_DAP = isset($_POST['DB_NAME_DAP']) ? $_POST['DB_NAME_DAP'] : "";
	$DB_USER_DAP = isset($_POST['DB_USER_DAP']) ? $_POST['DB_USER_DAP'] : "";
	$DB_PASSWORD_DAP = isset($_POST['DB_PASSWORD_DAP']) ? $_POST['DB_PASSWORD_DAP'] : "";
	$DB_HOST_DAP = isset($_POST['DB_HOST_DAP']) ? $_POST['DB_HOST_DAP'] : "";
	$SITE_URL_DAP = isset($_POST['SITE_URL_DAP']) ? $_POST['SITE_URL_DAP'] : "";

	try {
		$configFile = "../../dap-config.php";
		if(file_exists($configFile)) {
			$response = "<b>Oops, there is already a file by name 'dap-config.php' in your 'dap' directory. Please delete that first and re-try.</b><br/><br/>" . $response;
		} else {
			$response = "SUCCESS! Config file has been successfully created<br/>";
			$configTemplateFile = "../templates/config/dap-config-template.php";
			$configTemplateFileText = file_get_contents($configTemplateFile);
			$fd = fopen($configFile, "w");
			if(!$fd) {
				throw new Exception('Oops! Cannot read from template folder. Pls check the permissions.');
			}
			fwrite($fd, $configTemplateFileText);
			fclose($fd);
			
			$configFileText = file_get_contents($configFile);
			$configFileText = preg_replace("%%DB_NAME_DAP%%", $DB_NAME_DAP, $configFileText);
			$configFileText = preg_replace("%%DB_USER_DAP%%", $DB_USER_DAP, $configFileText);
			$configFileText = preg_replace("%%DB_PASSWORD_DAP%%", $DB_PASSWORD_DAP, $configFileText);
			$configFileText = preg_replace("%%DB_HOST_DAP%%", $DB_HOST_DAP, $configFileText);
			$configFileText = preg_replace("%%SITE_URL_DAP%%", $SITE_URL_DAP, $configFileText);
			$fd = fopen($configFile, "w");
			if(!$fd) {
				throw new Exception('Oops! Cannot create Config File. Pls check the permissions.');
			}
			fwrite($fd, $configFileText);
			fclose($fd);
		}
	} catch (Exception $e) {
		$response .= $e->getMessage() . "<br/>";
	} 
	
	$responseArray['responseJSON'] = $response;
	$responseJSON = json_encode($responseArray);
	echo $responseJSON;
?>