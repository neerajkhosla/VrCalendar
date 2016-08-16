<?php 
	$responseArray = array();
	$response = "File copying done";
  
	if ( isset($_POST['whatRequest']) && ($_POST['whatRequest'] != "") ) {
		$whatRequest = $_POST['whatRequest'];
		$responseArray['whatRequest'] = $whatRequest;
	}
	
	try {
		$sourceDirs = array("../templates/content","../templates/email");
		$targetDirs = array("../../templates/content","../../templates/email");
		
		$i=-1;
		foreach($sourceDirs as $dir) {
			$i++;
			$files = scandir($dir);
			foreach($files as $file) {
				if( ($file != ".") && ($file != "..") ) {
					$source = $dir . "/" . $file;
					$target = $targetDirs[$i] . "/" . $file;
					if(!copy ($source, $target)) {
						$response .= "Oops, could not copy file '" . $source . "'<br/>";
					}
				}
			}
		}
	} catch (Exception $e) {
		$response .= $e->getMessage() . "<br/>";
	}
	
	$responseArray['responseJSON'] = $response;
	$responseJSON = json_encode($responseArray);
	echo $responseJSON;
?>