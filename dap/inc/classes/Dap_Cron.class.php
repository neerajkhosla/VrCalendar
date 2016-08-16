<?php

/**

	Broadcast Group 1: 
		All Users: BE-ALLUSERS
		Unconfirmed Users: BE-ALLUNCONF
		Users with access to no products: BE-ALLNOPROD
		All affiliates: BE-ALLAFF 
	
	Broadcast Group 2:
		Users with access to....: BE-QUERY - INCLUDES expired - only looks at date range

	Broadcast Group 3:
		All users of product: EMAILTOPRODUCT - EXCLUDES expired users
		
	Broadcast Group 4:
		All users expired before/after: BE-QUERY - INCLUDES expired - only looks at date range
		
*/

class Dap_Cron {

	var $id;
	var $last_update_ts;
	var $description;
	
	function getId() {
		return $this->id;
	}
	function setId($o) {
		$this->id = $o;
	}
	
	function getLastUpdateTs() {
		return $this->last_update_ts;
	}
	function setLastUpdateTs($o) {
		$this->last_update_ts = $o;
	}
	
	function getDescription() {
	     return $this->description;
	}
	
	function setDescription($o) {
	     $this->description = $o;
	}
	
	public static function handleSyncIssuesCSVReport() {
		logToFile("(Dap_Cron.handleSyncIssuesCSVReport() - Method Init.");
		$_SESSION["csvfile"]="";
		try {

			$dap_dbh = Dap_Connection::getConnection();
			$select_sql = "select
						id,
						payload,
						actionKey
					from
						dap_mass_actions
					where
						status in ('NEW', 'E') and
						actionType = 'REPORTSYNCISSUES'
					";
					
			$update_sql = "update
							dap_mass_actions
							set
							status = :status,
							comments = :comments
							where
							id = :id
							";
							
			$insert_sql = "insert into dap_mass_actions
								(actionType, actionKey, payload, status, priority)
							values ('DAPWPSYNCREPORT', :key, :payload, 'NEW', 4)
							";

			//logToFile($sql,LOG_DEBUG_DAP);
			$select_stmt = $dap_dbh->prepare($select_sql);
			$update_stmt = $dap_dbh->prepare($update_sql);
			$insert_stmt = $dap_dbh->prepare($insert_sql);

			//execute select
			$select_stmt->execute();

			if ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
				//get payload
				$payload = $row['payload'];
				$parentkey = $row['actionKey'];
				//parse payload
				$tokens = explode("||",$payload);
				
				//$payload = $csvFileName."||".$syncorder."||".$foldername;
				if(count($tokens) < 2) {
					$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
					$update_stmt->bindValue(':status', 'E', PDO::PARAM_STR);
					$update_stmt->bindValue(':comments', 'Payload Format Is Not Correct', PDO::PARAM_STR);
					$update_stmt->execute();
					logToFile("(Dap_Cron.handleSyncIssuesCSVReport() - tokens<3");
																   
					sendMassActionFailedNE("ReportSyncIssues", "Generate Sync Issues Request Format is Invalid");
					return;
				}
				//lets get values
				$csvfile = $tokens[0];
				$syncorder = $tokens[1];
			//	$foldername = $tokens[2];
				$csvdata = "";
				//see if file exists and read if it does
				//logToFile("(Dap_Cron.handleSyncIssuesCSVReport() - Opening CSV File:".$csvfile);
				
				$csvFileName = DAP_ROOT."/".BULKFOLDER."/" . $csvfile;
				$handle = fopen($csvFileName, "r");
				//logToFile("(Dap_Cron.handleSyncIssuesCSVReport() - Opening CSV File:".$handle);
				$resultArr=array();
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					logToFile("(Dap_Cron.handleSyncIssuesCSVReport() - CSV Row:".$data);
				    if(count($data) < 2) {
						$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
						$update_stmt->bindValue(':status', 'E', PDO::PARAM_STR);
						$update_stmt->bindValue(':comments', 'Error in CSV File'.$data, PDO::PARAM_STR);
						$update_stmt->execute();
						sendMassActionFailedNE("ReportSyncIssues", "Error in CSV File ... date=" . $data);
						fclose($handle);
						return;
					}
					
					if($syncorder=="DAP") {
					//	logToFile("(Dap_Cron.handleSyncIssuesCSVReport() - data[0]:".$data[0]); //email
					//	logToFile("(Dap_Cron.handleSyncIssuesCSVReport() - data[1]:".$data[1]); //username
					//	logToFile("(Dap_Cron.handleSyncIssuesCSVReport() - data[2]:".$data[2]); //firstname
					//	logToFile("(Dap_Cron.handleSyncIssuesCSVReport() - data[3]:".$data[3]); //lastname
						Dap_Cron::checkDAPWPSyncIssue($syncorder,$data[0],$data[1],$data[2],$data[3],$resultArr);																					}
					else if ($syncorder=="WP") {
					//	logToFile("(Dap_Cron.handleSyncIssuesCSVReport() - data[0]:".$data[0]); //username
					//	logToFile("(Dap_Cron.handleSyncIssuesCSVReport() - data[1]:".$data[1]); //email
					//	logToFile("(Dap_Cron.handleSyncIssuesCSVReport() - data[2]:".$data[2]); //nicename
																						   
					 	Dap_Cron::checkWPDAPSyncIssue($syncorder,$data[0],$data[1],$data[2], $resultArr);		
					}
					
					//logToFile("(Dap_Cron.handleSyncIssuesCSVReport() - csvline=".$csvline); //nicename
																					   
					//generate csv report
					
					//DAP Email, WP Email, DAP Username, WP Username, Comments
					
				}
				fclose($handle);
				$csvFileName = DAP_ROOT."/".BULKFOLDER."/viewresults_" . $csvfile;
				
				$i=0;
				while($i<7) {
					if(isset($resultArr[$i]))
						writeToFile($resultArr[$i],$csvFileName);
					$i++;
				}
				
				//$_SESSION["csvfile"]="RESULT_".$csvfile;
				//COMPLETED TASK SUCCESSFULLY
				$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
				$update_stmt->bindValue(':status', 'C', PDO::PARAM_STR);
				$update_stmt->bindValue(':comments', 'CSV NAME - ' . $csvfile, PDO::PARAM_STR);
				$update_stmt->execute();
			}
			//logToFile("(Dap_Cron.handleSyncIssuesCSVReport() - return");
			return;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}
	
	
	public static function getWPFolderName() {
		logToFile("(Dap_Cron.getWPFolderName() - Method Init.");
		
		try {

			$dap_dbh = Dap_Connection::getConnection();
			$select_sql = "select
						id,
						payload,
						actionKey
					from
						dap_mass_actions
					where
						status in ('NEW', 'E') and
						actionType = 'SYNCUSERSWPDAP'
					";
					
			$select_stmt = $dap_dbh->prepare($select_sql);
			$select_stmt->execute();
  
			if ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
				//get payload
				$payload = $row['payload'];
				$parentkey = $row['actionKey'];
				//parse payload
				$tokens = explode("||",$payload);
				
				//$payload = $csvFileName."||".$syncorder."||".$foldername;
				if(count($tokens) < 3) {
					$foldername="";
					return;
				}
			
				$foldername = $tokens[2];
				return $foldername;
			}
			
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			return -1;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			return -1;
		}
		
		return -1;
					
	}
	
	public static function fixSyncIssuesWPDAP() {
		logToFile("(Dap_Cron.fixSyncIssuesWPDAP() - Method Init.");
		$_SESSION["csvfile"]="";
		try {

			$dap_dbh = Dap_Connection::getConnection();
			$select_sql = "select
						id,
						payload,
						actionKey
					from
						dap_mass_actions
					where
						status in ('NEW', 'E') and
						actionType = 'SYNCUSERSWPDAP'
					";
					
			$update_sql = "update
							dap_mass_actions
							set
							status = :status,
							comments = :comments
							where
							id = :id
							";
							

			//logToFile($sql,LOG_DEBUG_DAP);
			$select_stmt = $dap_dbh->prepare($select_sql);
			$update_stmt = $dap_dbh->prepare($update_sql);

			//execute select
			$select_stmt->execute();

			if ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
				//get payload
				$payload = $row['payload'];
				$parentkey = $row['actionKey'];
				//parse payload
				$tokens = explode("||",$payload);
				
				//$payload = $csvFileName."||".$syncorder."||".$foldername;
				if(count($tokens) < 3) {
					$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
					$update_stmt->bindValue(':status', 'E', PDO::PARAM_STR);
					$update_stmt->bindValue(':comments', 'Payload Format Is Not Correct', PDO::PARAM_STR);
					$update_stmt->execute();
					sendMassActionFailedNE("SYNCUSERSWPDAP", "Sync WP/DAP Request Format is Invalid");
					return;
				}
				//lets get values
				$csvfile = $tokens[0];
				$pickacat = $tokens[1];
				$foldername = $tokens[2];
				
				$csvdata = "";
				//see if file exists and read if it does
				//logToFile("(Dap_Cron.fixSyncIssuesWPDAP() - Opening CSV File:".$csvfile);
				
				$csvFileName = DAP_ROOT."/".BULKFOLDER."/viewresults_" . $csvfile;
				$handle = fopen($csvFileName, "r");
				//logToFile("(Dap_Cron.fixSyncIssuesWPDAP() - Opening CSV File:".$handle);
				$resultArr=array();
				$count=0;
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					//logToFile("(Dap_Cron.handleSyncWPDAP() - CSV Row:".$data);
				    if(count($data) < 6) {
						$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
						$update_stmt->bindValue(':status', 'E', PDO::PARAM_STR);
						$update_stmt->bindValue(':comments', 'Error in CSV File'.$data, PDO::PARAM_STR);
						$update_stmt->execute();
						sendMassActionFailedNE("SYNCUSERSWPDAP", "Error in CSV File ... date=" . $data);
						fclose($handle);
						return;
					}
					
					$wpusername=trim($data[0]);
					$dapusername=trim($data[1]);
					$wpemail=trim($data[2]);
					$dapemail=trim($data[3]);
					$comment=trim($data[4]);
					$category=trim($data[5]);
					$syncdirection=trim($data[6]);
					
					$count++;
					
				//	logToFile("(Dap_Cron.fixSyncIssuesWPDAP() - comment:".$comment);
					//logToFile("(Dap_Cron.fixSyncIssuesWPDAP() - pickacat:".$pickacat);
					
					if( (stristr($comment,$pickacat)!=FALSE) || ( $pickacat=="ALL")){
						$result.=Dap_Cron::syncWPDAPSync($syncdirection,$foldername,$wpusername,$dapusername,$wpemail,$dapemail,$comment,$category,$resultArr);		
						
					}
				}
				
				fclose($handle);
				$csvFileName = DAP_ROOT."/".BULKFOLDER."/syncresults_" . $csvfile;
				writeToFile("Total Number Of Records: $count \n\n",$csvFileName,"w");
				
				$i=0;
				while($i<6) {
					if(isset($resultArr[$i])) {
						writeToFile($resultArr[$i][1] . ", COUNT=". $resultArr[$i][0] . "\n",$csvFileName);
					}
					
					$i++;
					
				}
				writeToFile("\n",$csvFileName);
				writeToFile($result,$csvFileName);
				
				//$_SESSION["csvfile"]="RESULT_".$csvfile;
				//COMPLETED TASK SUCCESSFULLY
				$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
				$update_stmt->bindValue(':status', 'C', PDO::PARAM_STR);
				$update_stmt->bindValue(':comments', 'CSV NAME - ' . $csvfile, PDO::PARAM_STR);
				$update_stmt->execute();
			}

			return;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}
	
	public static function syncUserFromDAPToWPOLD($syncDirection,$folderName,$wpUsername,$dapUsername,$wpEmail,$dapEmail) {
		
		$lldocroot = defined('SITEROOT') ? SITEROOT : $_SERVER['DOCUMENT_ROOT'];
		if(file_exists($lldocroot . "/dap/dap-config.php")) include_once ($lldocroot . "/dap/dap-config.php");
		
		

		$path=$lldocroot."/";
		
		if($foldername!="") {
			$path.=$folderName."/";
		}
		
		//require_once($path.'wp-config.php');
		
		global $current_user;
		
		if($syncDirection=="WP") {
			$user = Dap_User::loadUserByEmail($wpEmail);
		}
		else {
			$user = Dap_User::loadUserByEmail($dapEmail);
		}
		
		if(isset($user)) {
			// Email in WP matches email in dap 
			$dapUsername = $user->getUser_name();
			$dapEmail = $user->getEmail();
		   
			$userarrayupd = array();
			//logToFile("Dap_Cron.class.php: syncUserFromDAPToWP(): wpUsername=$wpUsername");
			
			if ( ($wpEmail!="") && ($wpUsername=="") ){
				$id = username_exists($wpUsername);
				if(!$id) {
				  $id=email_exists($wpEmail);
				  
				  $userarrayupd['ID'] = $id;
				  $wpUser = get_userdata($id);
				  
				  $wpUsername=$wpUser->user_login;
				  
				  if(stristr( $dapUsername , $wpUsername) == FALSE) {
					  //fix dap username to match WP username
					   $user->setUser_name($wpUsername);
					   $user->update();
					   $dapUsername=$wpUsername;
					  // logToFile("Dap_Cron.class.php: syncUserFromDAPToWP(): fixed dapuser=$dapUsername to match the wpusername=$wpUsername");
				  }
				 
				}
				
				if($id>0) {
					$userarrayupd['ID'] = $id;
					$wpUser = get_userdata($id);
					
					if( isset($dapUsername) && ($dapUsername!="") ) {
						$userarrayupd['user_login'] = $dapUsername;
					} else {
						$userarrayupd['user_login'] = $wpUsername;
					}
					
					$userarrayupd['user_pass'] = $user->getPassword();                 
					$userarrayupd['user_email'] = $user->getEmail();
					$userarrayupd['first_name'] = $user->getFirst_name();
					$userarrayupd['last_name'] = $user->getLast_name();
					//$userarrayupd['role'] = $userarray['role'];
					
					if($wpUser->display_name != "") {
						$userarrayupd['display_name'] = $wpUser->display_name;
					} else {
						$userarrayupd['display_name'] = $user->getFirst_name() . $user->getLast_name();
					}
					
					//logToFile("Dap_Cron.class.php: syncUserFromDAPToWP(): user found in WP ... updating user record in WP (wp_update_user)"); 
					
					try {		
						$_SESSION["dapcronupdate"] = "Y";
						wp_update_user($userarrayupd);
					   
					} catch (PDOException $e) {
						logToFile("Dap_Cron.class.php: syncUserFromDAPToWP(): " . $e->getMessage(),LOG_FATAL_DAP);
					} catch (Exception $e) {
						logToFile("Dap_Cron.class.php: syncUserFromDAPToWP(): " . $e->getMessage(),LOG_FATAL_DAP);
					}
				}
				else {
					  logToFile("Dap_Cron.class.php: syncUserFromDAPToWP(): user not found in WP"); 
				}
			}
			else {
				syncFromDAPToWP($syncDirection,$folderName,$wpUsername,$dapUsername,$wpEmail,$dapEmail);	
			}
	
			
			return;
		}
	
	} //end function
	
	
	public static function syncTheUser($syncDirection,$folderName,$wpUsername,$dapUsername,$wpEmail,$dapEmail,&$resultArr) {
		
		$lldocroot = defined('SITEROOT') ? SITEROOT : $_SERVER['DOCUMENT_ROOT'];
		if(file_exists($lldocroot . "/dap/dap-config.php")) include_once ($lldocroot . "/dap/dap-config.php");
		
		$path=$lldocroot."/";
		
		if($foldername!="") {
			$path.=$folderName."/";
		}
		
		//require_once($path.'wp-config.php');
		
		global $current_user;
		
		logToFile("Dap_Cron.class.php: syncDirection=$syncDirection, wpEmail=$wpEmail, wpUsername=$wpUsername"); 
		
		if( ($syncDirection=="WP") && ($wpEmail!="") ) {
			logToFile("Dap_Cron.class.php: syncTheUser(): wpEmail=$wpEmail"); 
			$user = Dap_User::loadUserByEmail($wpEmail);
			if(isset($user)) {
				//logToFile("Dap_Cron.class.php: syncTheUser(): found the user - wpEmail=$wpEmail"); 
				$return=Dap_Cron::syncFromWPToDAP($syncDirection,$folderName,$wpUsername,$dapUsername,$wpEmail,$dapEmail,$resultArr);
			}
			else {
				logToFile("Dap_Cron.class.php: syncTheUser(): could not find the user - wpEmail=$wpEmail"); 
			}
		}
		else {
			$return=Dap_Cron::syncFromDAPToWP($syncDirection,$folderName,$wpUsername,$dapUsername,$wpEmail,$dapEmail,$resultArr);	
			$user = Dap_User::loadUserByEmail($dapEmail);
		}
		
		return $return;
	
	} //end function
	
	public static function syncFromWPToDAP($syncDirection,$folderName,$wpUsername,$dapUsername,$wpEmail,$dapEmail,&$resultArr) {
		
		logToFile("Dap_Cron.class.php: syncFromWPToDAP(): ENTER. $syncDirection,$folderName,$wpUsername,$dapUsername,$wpEmail,$dapEmail"); 
	
		$user = Dap_User::loadUserByEmail($wpEmail);
		$username=$wpUsername;
		$userarray = array();
		   
		if(isset($user)) {
		  $dapUsername=$user->getUser_name();
		  
		  logToFile("Dap_Cron.class.php: syncFromWPToDAP(): found user wpemail=".$wpEmail); 	
		  if( (Dap_Config::get("WP_SYNC_PAID_ONLY")=="Y") && (!$user->isPaidUser()) ) {
			  //Sync only paid users is true - disable everyone else, except any WP admins
			  //logToFile("Not a paid user"); 
			  if ( username_exists($wpUsername) || email_exists($wpEmail) ) { //just do an update
				  $id = username_exists($wpUsername);
				  if(!$id)
					  $id = email_exists($wpEmail);
					  
				  //Now check if the WP user we just found has WP admin role.
				  //If yes, then DO NOT disable even though he may be free user
				  //$user = new WP_User( $user_id );
				  if ( isUserToBeSyncedAWPAdmin ( $id ) ){
					  logToFile("Dap_Cron.class.php: syncFromWPToDAP(): WP admin user ... leave)"); 
					  $resultArr[1][0]=$resultArr[1][0]+1;
					  $resultArr[1][1]="CANT SYNC ADMIN USER";
					  return 1;
				  }
				  
				  //Coming here means user is not paid user, and needs to be disabled
				  //So just updating role to blank, so user cannot use anything in WP
				  //that needs user to be logged in - like forums, commenting, etc
				  //for when user was previously eligible and now not eligible (eg., because of a refund)
				  //logToFile("Updating to nothing...");
				  $userarray['ID'] = $id;
				  $userarray['role'] = ""; //set role to blank
				//  wp_set_current_user($id, null);
				  wp_update_user($userarray);
				 // logToFile("Dap_Cron.class.php: syncFromWPToDAP():set role to blank as free users shd not be sync'd to WP"); 
				  
				  $resultArr[0][0]=$resultArr[0][0]+1;
				  $resultArr[0][1]="SYNCED_SUCCESSFULLY";
				  return 0;
			  } 
			  return;
		  } else {
			  //Sync everybody
			  //Check if username also exists in WP
			  //logToFile("About to Sync User now");
			  $id = username_exists($username);
			  
			  /**
				  This is the case where DAP username is blank
				  So update as usual
			  */
			  $userarrayupd = array();
			  $emailfound=false;
			  if(!$id) {
				  $id=email_exists($wpEmail);
				  $emailfound=true;
			  }
			  
			  if ( isUserToBeSyncedAWPAdmin ( $id ) ){
				  logToFile("Dap_Cron.class.php: syncFromWPToDAP(): WP admin user ... leave)"); 
				  $resultArr[1][0]=$resultArr[1][0]+1;
				  $resultArr[1][1]="CANT SYNC ADMIN USER";
				  return 1;
			  }
				  
			  if ( id ) {   //just do an update
				  $userarrayupd['ID'] = $id;
				  $wpUser = get_userdata($id);
				  $wpUsername=$wpUser->user_login;
				  $username=$wpUsername;
				  
				  if(stristr( $dapUsername , $wpUsername) == FALSE) {
					  //fix dap username to match WP username
					   $user->setUser_name($wpUsername);
					   $user->update();
					   $dapUsername=$wpUsername;
					   logToFile("Dap_Cron.class.php: syncUserFromDAPToWP(): fixed dapuser=$dapUsername to match the wpusername=$wpUsername");
				  }
				  
				 // $userarrayupd['user_login'] = $username;
				  $userarrayupd['user_pass'] = $user->getPassword();                 
				  $userarrayupd['user_email'] = $user->getEmail();
				 /* $userarrayupd['first_name'] = $user->getFirst_name();
				  $userarrayupd['last_name'] = $user->getLast_name();
				  if($wpUser->display_name != "") {
						$userarrayupd['display_name'] = $wpUser->display_name;
				  } else {
					  $userarrayupd['display_name'] = $user->getFirst_name() . $user->getLast_name();
				  }*/
				  
				  try {		
					  $_SESSION["dapcronupdate"] = "Y";
					  wp_update_user($userarrayupd);
					  logToFile("Dap_Cron.class.php: syncFromWPToDAP():sync'd DAP user ($wpEmail) to WP"); 
					  $resultArr[0][0]=$resultArr[0][0]+1;
					  $resultArr[0][1]="SYNCED_SUCCESSFULLY";
					  return 0;
				  } catch (PDOException $e) {
					  logToFile("Dap_Cron.class.php: syncUserFromDAPToWP(): " . $e->getMessage(),LOG_FATAL_DAP);
					  $resultArr[2][0]=$resultArr[2][0]+1;
					  $resultArr[2][1]="SYNC FAILED. EXCEPTION : " . $e->getMessage();
					  return 2;
				  } catch (Exception $e) {
					  logToFile("Dap_Cron.class.php: syncUserFromDAPToWP(): " . $e->getMessage(),LOG_FATAL_DAP);
					  $resultArr[2][0]=$resultArr[2][0]+1;
					  $resultArr[2][1]="SYNC FAILED. EXCEPTION : " . $e->getMessage();
					  return 2;
				  }
			  } 
			  else { 
				  //New User in WP - First time sync
				  //logToFile("Username $username does not exist in WP, so doing an insert");
				  //logToFile("New user"); 
				  //logToFile("username: $username"); 
				  $_SESSION["dapcronupdate"] = "Y";
				  $userarray['role'] = "";
				  $userarray['user_login'] = $dapUsername;
				  $userarray['display_name'] = $dapUsername;  
				  $userarray['user_email'] = $dapEmail;
				  
				  $user = Dap_User::loadUserByEmail($dapEmail);
				  $userarray['user_pass'] = $user->getPassword();                 
				  $userarray['first_name'] = $user->getFirst_name();
				  $userarray['last_name'] = $user->getLast_name(); 
				  $userarray['user_nicename'] = $user->getFirst_name() . $user->getLast_name();  
				  if ($user->isPaidUser()) {
					  $userarray['role'] = Dap_Config::get("WP_DEF_ROLE_PAID");
				  } else {
					  $userarray['role'] = Dap_Config::get("WP_DEF_ROLE_FREE");
				  }
				  $userarray['role'] = ($userarray['role'] == "") ? "subscriber" : $userarray['role']; //default to subscriber for v<=3.9
				  wp_insert_user($userarray); //otherwise create			
				  logToFile("Dap_Cron.class.php: syncUserFromDAPToWP(): NEW user ($dapEmail) in WP.. sync from DAP=>WP");	
				  
				  $resultArr[0][0]=$resultArr[0][0]+1;
				  $resultArr[0][1]="SYNCED_SUCCESSFULLY";
				  return 0;
			  }
		  

		  }
		}
	}
	
	public static function syncFromDAPToWP($syncDirection,$folderName,$wpUsername,$dapUsername,$wpEmail,$dapEmail,&$resultArr) {
	//logToFile("About to insert/update"); 
			$userarray = array();
			$username=$dapUsername;
			$userarray['user_login'] = $dapUsername;
			$userarray['display_name'] = $dapUsername;  
			$userarray['user_email'] = $dapEmail;
			
			$user = Dap_User::loadUserByEmail($dapEmail);
			$userarray['user_pass'] = $user->getPassword();                 
			$userarray['first_name'] = $user->getFirst_name();
			$userarray['last_name'] = $user->getLast_name(); 
			$userarray['user_nicename'] = $user->getFirst_name() . $user->getLast_name();  
			
			if( (Dap_Config::get("WP_SYNC_PAID_ONLY")=="Y") && (!$user->isPaidUser()) ) {
				//Sync only paid users is true - disable everyone else, except any WP admins
				//logToFile("Not a paid user"); 
				if ( username_exists($dapUsername) || email_exists($user->getEmail()) ) { //just do an update
					$id = username_exists($dapUsername);
					if(!$id)
						$id = email_exists($user->getEmail());
					//Now check if the WP user we just found has WP admin role.
					//If yes, then DO NOT disable even though he may be free user
					//$user = new WP_User( $user_id );
					if ( isUserToBeSyncedAWPAdmin ( $id ) ){
						$resultArr[1][0]=$resultArr[1][0]+1;
						$resultArr[1][1]="CANT SYNC ADMIN USER";
						return 1;
					}
					
					//Coming here means user is not paid user, and needs to be disabled
					//So just updating role to blank, so user cannot use anything in WP
					//that needs user to be logged in - like forums, commenting, etc
					//for when user was previously eligible and now not eligible (eg., because of a refund)
					//logToFile("Updating to nothing...");
					$userarray['ID'] = $id;
					$userarray['role'] = ""; //set role to blank
					wp_set_current_user($id, null);
					wp_update_user($userarray);
					$resultArr[0][0]=$resultArr[0][0]+1;
					$resultArr[0][1]="SYNCED_SUCCESSFULLY";
					return 0;
					
				} 
				return;
			} else {
				//Sync everybody
				//Check if username also exists in WP
				//logToFile("About to Sync User now");
				$id = 0;
				
				/**
					This is the case where DAP username is blank
					So update as usual
				*/
				
				if ( username_exists($username) ) {   //just do an update
					//logToFile("Username $username exists in WP, so just doing an update");
					$userarrayupd = array();
					$id = username_exists($username);
					if ( isUserToBeSyncedAWPAdmin ( $id ) ){
						$resultArr[1][0]=$resultArr[1][0]+1;
						$resultArr[1][1]="CANT SYNC ADMIN USER";
						return 1;
					}
					$userarrayupd['ID'] = $id;
					$userarrayupd['user_login'] = $username;
					$userarrayupd['user_pass'] = $user->getPassword();                 
					$userarrayupd['user_email'] = $user->getEmail();
					$userarrayupd['first_name'] = $user->getFirst_name();
					$userarrayupd['last_name'] = $user->getLast_name();
					//$userarrayupd['role'] = $userarray['role'];
					//$userarrayupd['display_name'] = $user->getFirst_name() . $user->getLast_name();
					//logToFile("Updating user role to: " . $userarrayupd['role']); 

					wp_update_user($userarrayupd);
					$resultArr[0][0]=$resultArr[0][0]+1;
					$resultArr[0][1]="SYNCED_SUCCESSFULLY";
					return 0;
					
				} 
				
				else { 
					//New User in WP - First time sync
					//logToFile("Username $username does not exist in WP, so doing an insert");
					//logToFile("New user"); 
					//logToFile("username: $username"); 
					$userarray['role'] = "";
					if ($user->isPaidUser()) {
						$userarray['role'] = Dap_Config::get("WP_DEF_ROLE_PAID");
					} else {
						$userarray['role'] = Dap_Config::get("WP_DEF_ROLE_FREE");
					}
					$userarray['role'] = ($userarray['role'] == "") ? "subscriber" : $userarray['role']; //default to subscriber for v<=3.9
					wp_insert_user($userarray); //otherwise create			
					
					$resultArr[0][0]=$resultArr[0][0]+1;
					$resultArr[0][1]="SYNCED_SUCCESSFULLY";
					return 0;
					
				}

			}			
	}
	
	
	public static function syncWPDAPSync($syncdirection,$foldername,$wpusername,$dapusername,$wpemail,$dapemail,$comment,$category,&$resultArr) {
		logToFile("(Dap_Cron.syncWPDAPSync() - $syncdirection,$foldername,$wpusername,$dapusername,$wpemail,$dapemail,$comment,$category");
		try {								   
			switch ($comment) {
			//	logToFile("(Dap_Cron.handleSyncWPDAP() request - $syncdirection,$foldername,$wpusername,$dapusername,$wpemail,$dapemail,$comment,$category,$resultArr");
													 
				case "MSG_WP_DAP_EMAIL_AND_USERNAME_MATCH" :
					
					logToFile("(Dap_Cron.syncWPDAPSync() 1 - category=$category,$email=$wpemail");
					$return=Dap_Cron::syncTheUser($syncdirection,$foldername,$wpusername,$dapusername,$wpemail,$dapemail,$resultArr);
					$syncmsg=$resultArr[$return][1];								   
					$result.="$wpusername,$dapusername,$wpemail,$dapemail,$comment,$syncdirection,$syncmsg - 1\n";
					logToFile("(Dap_Cron.syncWPDAPSync() 1 - Processing complete for wpemail=$wpemail");
						   
					break;
					
				case "MSG_WP_DAP_EMAIL_DONTMATCH" :
					
					logToFile("(Dap_Cron.syncWPDAPSync() 2 - category=$category,$email=$wpemail");
					///found the dap account for this WP user. 
					// but the username in WP belongs to email A and the same username in dap belongs to a different email.
					// email match wins.. so once we get the right dap account for the WP email,  then we need to give this account the same username as WP
					// but before we do that, we need to remove that username from the incorrect dap account
					//so nulligy that username on incorrect dap account and then save this username on the right email account in dap. then do a full sync.
					
					$rightdapuser = Dap_User::loadUserByEmail($wpemail);
					if( isset($rightdapuser) && ($syncdirection=="WP") ) {
						$wrongdapuser = Dap_User::loadUserByEmail($wpusername);
						if( isset($wrongdapuser) ) {
							$email=$wrongdapuser->getEmail();
							if(stristr($email,$wpemail)==FALSE) {
							//	logToFile("(Dap_Cron.syncWPDAPSync() - remove username from wrong dap user=".$email);
								$wrongdapuser->setUser_name("");
								$wrongdapuser->update();
							//	logToFile("(Dap_Cron.syncWPDAPSync() - give $wpusername to the right dap user=".$wpemail);
								$rightdapuser->setUser_name($wpusername);
								$rightdapuser->update();
						//		logToFile("(Dap_Cron.syncWPDAPSync() - now resync this dap account - $wpemail to WP");
								$syncmsg=$resultArr[$return][1];										   
								$return=Dap_Cron::syncTheUser($syncdirection,$foldername,$wpusername,$dapusername,$wpemail,$dapemail,$resultArr);
								
								$result.="$wpusername,$dapusername,$wpemail,$dapemail,$comment,$syncdirection,$syncmsg - 2\n";
							}
							else {
								// got into this category by mistake ?
								// set dap username to match WP username and sync
								$rightdapuser->setUser_name($wpusername);
								$rightdapuser->update();
								$syncmsg=$resultArr[$return][1];
								$return=Dap_Cron::syncTheUser($syncdirection,$foldername,$wpusername,$dapusername,$wpemail,$dapemail,$resultArr);
								$result.="$wpusername,$dapusername,$wpemail,$dapemail,$comment,$syncdirection,$syncmsg - 3\n";
							}
						}
						else {
							$resultArr[3][0]=$resultArr[3][0]+1;
							$resultArr[3][1]="NO_ACTION_TAKEN";
							$syncmsg=$resultArr[3][1];
							$result.="$wpusername,$dapusername,$wpemail,$dapemail,$comment,$syncdirection,$syncmsg - 4\n";	
						}
					}
					else {
						$resultArr[3][0]=$resultArr[3][0]+1;
						$resultArr[3][1]="NO_ACTION_TAKEN";
						$syncmsg=$resultArr[3][1];
						$result.="$wpusername,$dapusername,$wpemail,$dapemail,$comment,$syncdirection,$syncmsg - 5\n";	
					}
					
					logToFile("(Dap_Cron.syncWPDAPSync() 2 - Processing complete for wpemail=$wpemail");
					break;
					
				case "MSG_WPUSERNAME_MISSING_IN_DAP" :
				case "MSG_WP_DAP_USERNAME_CONFLICT" :
				case "MSG_WPUSER_NOT_FOUND_IN_DAP" :
					
					logToFile("(Dap_Cron.syncWPDAPSync() 3 - category=$category,$email=$wpemail");
					$user = Dap_User::loadUserByEmail($wpemail);
					if(isset($user)) {
					  // Email in WP matches email in dap 
					  $dapusername = $user->getUser_name();
					  $dapemail = $wpemail;
					  if($dapusername=="") {
						  //"MSG_WPUSERNAME_MISSING_IN_DAP" :
						$user->setUser_name($wpusername);
						$user->update();
						$return=Dap_Cron::syncTheUser($syncdirection,$foldername,$wpusername,$dapusername,$wpemail,$dapemail,$resultArr);
						$syncmsg=$resultArr[$return][1];
						$result.="$wpusername,$dapusername,$wpemail,$dapemail,$comment,$syncdirection,$syncmsg - 6\n"; 
					  }
					  else {
						  //"MSG_WP_DAP_USERNAME_CONFLICT"
						  //update the dap username to match the one in WP because wp username cant be changed
						  //
						  $wrongdapuser = Dap_User::loadByUsername($wpusername);
						  if(isset($wrongdapuser)) {
							$wrongdapuser->setUser_name("");
							$wrongdapuser->update();
							
							$user->setUser_name($wpusername);
							$user->update();
							$return=Dap_Cron::syncTheUser($syncdirection,$foldername,$wpusername,$dapusername,$wpemail,$dapemail,$resultArr);
							logToFile("(Dap_Cron.syncWPDAPSync() - return=".$return);
							$syncmsg=$resultArr[$return][1];
							$result.="$wpusername,$dapusername,$wpemail,$dapemail,$comment,$syncdirection,$syncmsg - 7\n"; 	
						  }
						  else {
							  $user->setUser_name($wpusername);
							  $user->update();
							  $return=Dap_Cron::syncTheUser($syncdirection,$foldername,$wpusername,$dapusername,$wpemail,$dapemail,$resultArr);
							  $syncmsg=$resultArr[$return][1];
							  $result.="$wpusername,$dapusername,$wpemail,$dapemail,$comment,$syncdirection,$syncmsg - 8\n"; 
							  
						  }
					  }
					}
					else {
						//"MSG_WPUSER_NOT_FOUND_IN_DAP"
						$resultArr[4][0]=$resultArr[4][0]+1;
						$resultArr[4][1]="MANUALLY_CREATE_DAP_ACCT_USING_WP_USERNAME_AND_EMAIL";
						$syncmsg=$resultArr[4][1];		
						$result.="$wpusername,$dapusername,$wpemail,$dapemail,$comment,$syncdirection,$syncmsg - 9\n";	
					}
					
					logToFile("(Dap_Cron.syncWPDAPSync() 3 - Processing complete for wpemail=$wpemail");
					break;
					
				case "MSG_WP_DAP_USERNAME_MISSING" :
				case "MSG_WPUSERNAME_MISSING" :
				case "MSG_WPEMAIL_NOT_FOUND_IN_DAP" :
					logToFile("(Dap_Cron.syncWPDAPSync() 4 - category=$category,$email=$wpemail");
					if($syncdirection=="WP") {
					  if($wpusername=="") {
					  	$user = Dap_User::loadUserByEmail($wpemail);
						if(isset($user)) {
							$username=$user->getUser_name();
							$firstname=$user->getFirst_name();
							$lastname=$user->getLast_name();
							if($username=="") {
								//generateUsername in DAP
								$username=generateUsername("Dap_Cron.class.php",$wpemail,$firstname,$lastname);
								$user->setUser_name($username);
								$user->update();
								$return=Dap_Cron::syncTheUser($syncdirection,$foldername,$wpusername,$dapusername,$wpemail,$dapemail,$resultArr);
								logToFile("(Dap_Cron.syncWPDAPSync() - return=".$return);
								$syncmsg=$resultArr[$return][1];								   
							    $result.="$wpusername,$dapusername,$wpemail,$dapemail,$comment,$syncdirection,$syncmsg - 10\n"; 
								
								break;
							}
							else {
								$return=Dap_Cron::syncTheUser($syncdirection,$foldername,$wpusername,$dapusername,$wpemail,$dapemail,$resultArr);
								logToFile("(Dap_Cron.syncWPDAPSync() - return=".$return);
								$syncmsg=$resultArr[$return][1];
							    $result.="$wpusername,$dapusername,$wpemail,$dapemail,$comment,$syncdirection,$syncmsg- 11\n"; 
								break;
							}
						}
						else {
							//case "MSG_WPEMAIL_NOT_FOUND_IN_DAP" :
							$resultArr[4][0]=$resultArr[4][0]+1;
							$resultArr[4][1]="MANUALLY_CREATE_DAP_ACCT_USING_WP_USERNAME_AND_EMAIL";
							$syncmsg=$resultArr[4][1];	
							$result.="$wpusername,$dapusername,$wpemail,$dapemail,$comment,$syncdirection,$syncmsg - 12\n";	
			
							break;
						}
					  }
					  else {
						  $resultArr[3][0]=$resultArr[3][0]+1;
						  $resultArr[3][1]="NO_ACTION_TAKEN";
						  $syncmsg=$resultArr[3][1];	
						  $result.="$wpusername,$dapusername,$wpemail,$dapemail,$comment,$syncdirection,$syncmsg - 13\n";	
						  break;
					  }
					}
					
					logToFile("(Dap_Cron.syncWPDAPSync() 4 - Processing complete for wpemail=$wpemail");
					break;
				
				default: 
					$resultArr[5][0]=$resultArr[5][0]+1;
					$resultArr[5][1]="DEFAULT_CATEGORY";
					$syncmsg=$resultArr[5][1];	
					$result.="$wpusername,$dapusername,$wpemail,$dapemail,$comment,$syncdirection,$syncmsg - 14\n";	
					logToFile("(Dap_Cron.syncWPDAPSync() (default) missing category=".$category);
					break;	
				
			}
		
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			$resultArr[2][0]=$resultArr[2][0]+1;
			$resultArr[2][1]="DEFAULT_CATEGORY";
			$result.="$wpusername,$dapusername,$wpemail,$dapemail,$comment,$syncdirection," .$e->getMessage()." - 15/n";	
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			$resultArr[2][0]=$resultArr[2][0]+1;
			$resultArr[2][1]="DEFAULT_CATEGORY";
			$result.="$wpusername,$dapusername,$wpemail,$dapemail,$comment,$syncdirection," .$e->getMessage()." - 15/n";	
		}
		return $result;
	}
	
	
	public static function checkDAPWPSyncIssue ($syncorder,$foldername, $wpusername, $wpemail, $wpnicename, &$resultArr) {
		
		return $csvline;
	}
	
	
	public static function checkWPDAPSyncIssue($syncorder, $wpusername, $wpemail, $wpnicename, &$resultArr) {
//		$comment="missing username in WP";
//		$csvline="$username,$email,$nicename,$comment";

		/*wp has username but dap does not - category 1

		  Email in WP matches email in dap 
			  check what the username is set to for that email in dap
				  if missing.. 
					  copy WP username to DAP username	-  sync'd successfully - category - 1  
					  Then sync from dap to WP (dap password to WP)  (REPORT - 1)
					  
				  if present
				  if means that email owner is tied to a different username in dap.. 
					  email matches but usernames in WP/DAP are different (what to do ?) - REPORT - 2
		  
		  Email in WP but no match in dap (missing user in dap) 
			  user account missing in dap -  (both wp username/email not in dap) -  REPORT 3
		*/
		
		logToFile("(Dap_Cron.checkWPDAPSyncIssue() - $syncorder, $wpusername, $wpemail, $wpnicename"); //nicename
													   
		//check if username exists in dap
		if($wpusername!="") {
			$user = Dap_User::loadByUsername($wpusername);
			if(isset($user)) {
				//check what email is tied to it in dap
				$dapemail = $user->getEmail();
				if(isset($user) && $user->getAccount_type() == "A") {
					logToFile("Dap_Cron.checkWPDAPSyncIssue(): skipping.. User :".$user->getId()." Is Admin");
					return;
				}
				$dapusername = $user->getUser_name();
				if(stristr($dapemail,$wpemail)!=FALSE) {
					//great, full match, dap username matches WP username, dap email matches WP email, sync user from dap->wp
					//$comment = "DAP email/username matches WP email/username. No conflict. Sync user from DAP=>WP";
					$comment="MSG_WP_DAP_EMAIL_AND_USERNAME_MATCH";
					$category=0;
					$resultArr[0].="$wpusername,$dapusername,$wpemail,$dapemail,$comment,$category,$syncorder \n";
					logToFile("(Dap_Cron.checkWPDAPSyncIssue()resultArr[0] - " . $resultArr[0]); //nicename
					//Dap_User::syncThisDAPUserToWP($user);
				}
				else {
					// 	Same username taken by different email Ids in DAP and WP
					// search by WP email
					// remove this username from the incorrect email id in dap.
					
					$comment="MSG_WP_DAP_EMAIL_DONTMATCH";
					//$comment = "Username matches but tied to different email Ids. Handle conflict manually";
					$category=1;
					$resultArr[1].="$wpusername,$dapusername,$wpemail,$dapemail,$comment,$category,$syncorder \n";
					//logToFile("(Dap_Cron.checkWPDAPSyncIssue()resultArr[1] - " . $resultArr[1]); //nicename
					
				}
			}
			else {
				// username not found in DAP, check if email exists
				$user = Dap_User::loadUserByEmail($wpemail);
				if(isset($user)) {
					// Email in WP matches email in dap 
					if($user->getAccount_type() == "A") {
						logToFile("Dap_Cron.checkWPDAPSyncIssue(): skipping... User :".$user->getId()." Is Admin");
						return;
					}
					$dapusername = $user->getUser_name();
					$dapemail = $wpemail;
					if($dapusername=="") {
						// missing username in dap
						$user->setUser_name($wpusername);
						$user->update();
						$category=2;
						//$comment = "Email matches. Username missing in DAP but set in WP. Set DAP Username to WP Username.";	
						$comment="MSG_WPUSERNAME_MISSING_IN_DAP";
						$resultArr[2].="$wpusername,$dapusername,$wpemail,$dapemail,$comment,$category,$syncorder \n";
						//Dap_User::syncThisDAPUserToWP($user);
					}
					else {
						//$comment = "Email matches but tied to different user names. set dap useerbane to wp username";	
						$comment="MSG_WP_DAP_USERNAME_CONFLICT";
						$category=3;
						$resultArr[3].="$wpusername,$dapusername,$wpemail,$dapemail,$comment,$category,$syncorder \n";
					}
				}
				else {
					//both WP username and email not found in DAP.
					$comment="MSG_WPUSER_NOT_FOUND_IN_DAP";
					//$comment = "BOTH WP Username and WP email not found in DAP. Handle conflict manually";	
					$category=4;
					$resultArr[4].="$wpusername,$dapusername,$wpemail,$dapemail,$comment,$category,$syncorder \n";
				}
			}
		}
		else {
			$user = Dap_User::loadUserByEmail($wpemail);
			if(isset($user)) {
				if($user->getAccount_type() == "A") {
					logToFile("Dap_Cron.checkWPDAPSyncIssue(): skipping... User :".$user->getId()." Is Admin");
					return;
				}
				// Email in WP matches email in dap 
				$dapusername = $user->getUser_name();
				$dapemail = $wpemail;
				
				$firstname = $user->getFirst_name();
				$lastname = $user->getLast_name();
				 
				if($dapusername=="") {
					// missing username in dap
					$user->setUser_name($wpusername);
					$user->update();
					
					$dapusername=$firstname.$lastname;
					$user->setUser_name($dapusername);
					$user->update();
					Dap_User::syncThisDAPUserToWP($user);
					$category=5;
					//$comment = "Sync DAP User To WP. Set username to first/last name";	
					
					$comment="MSG_WP_DAP_USERNAME_MISSING";
					$resultArr[5].="$wpusername,$dapusername,$wpemail,$dapemail,$comment,$category,$syncorder \n";
				}
				else {
					$comment="MSG_WPUSERNAME_MISSING";
					//$comment = "Email matches. Username set in DAP but missing in WP. Sync DAP User to WP.";	
					//Dap_User::syncThisDAPUserToWP($user);
					$category=6;
					$resultArr[6].="$wpusername,$dapusername,$wpemail,$dapemail,$comment,$category,$syncorder \n";
				}
			}
			else {
				//both WP username and email not found in DAP.	
				//$comment = "WP Username Missing. WP email not found in DAP. Missing DAP acoount. Handle conflict manually";	
				$comment="MSG_WPEMAIL_NOT_FOUND_IN_DAP";
				$category=7;
				$resultArr[7].="$wpusername,$dapusername,$wpemail,$dapemail,$comment,$category,$syncorder \n";
			}	
		}
		
		

		return $csvline;
	}
	
	/*
	Bunch of static methods to handle transactions and other functions for Cron.

	*/

	/*
	actionType = BULKADDCSVTOPRODUCT
	Payload format #PRODUCTID||CSVFILENAME||ISPAID(Y/N)

	*/
	public static function handleBulkAddCsvToProduct() {
		logToFile("(Dap_Cron.handleBulkAddCsvToProduct() - Method Init.");
		try {

			$dap_dbh = Dap_Connection::getConnection();
			$select_sql = "select
						id,
						payload,
						actionKey
					from
						dap_mass_actions
					where
						status in ('NEW', 'E') and
						actionType = 'BULKADDCSVTOPRODUCT'
					";
			$update_sql = "update
							dap_mass_actions
							set
							status = :status,
							comments = :comments
							where
							id = :id
							";
			$insert_sql = "insert into dap_mass_actions
								(actionType, actionKey, payload, status, priority)
							values ('ADDUSERTOPRODUCT', :key, :payload, 'NEW', 4)
							";

			//logToFile($sql,LOG_DEBUG_DAP);
			$select_stmt = $dap_dbh->prepare($select_sql);
			$update_stmt = $dap_dbh->prepare($update_sql);
			$insert_stmt = $dap_dbh->prepare($insert_sql);

			//execute select
			$select_stmt->execute();

			if ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
				//get payload
				$payload = $row['payload'];
				$parentkey = $row['actionKey'];
				//parse payload
				$tokens = explode("||",$payload);
				if(count($tokens) < 3) {
					$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
					$update_stmt->bindValue(':status', 'E', PDO::PARAM_STR);
					$update_stmt->bindValue(':comments', 'Payload Format Is Not Correct', PDO::PARAM_STR);
					$update_stmt->execute();
					sendMassActionFailedNE("BulkAddCSVToProduct", "Mass action request format is invalid");
					return;
				}
				//lets get values
				$product_id = $tokens[0];
				$csvfile = $tokens[1];
				$ispaid = $tokens[2];
				//see if file exists and read if it does
				logToFile("(Dap_Cron.handleBulkAddCsvToProduct() - Opening CSV File:".$csvfile);
				$handle = fopen($csvfile, "r");
				logToFile("(Dap_Cron.handleBulkAddCsvToProduct() - Opening CSV File:".$handle);
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					logToFile("(Dap_Cron.handleBulkAddCsvToProduct() - CSV Row:".$data);
				    	if(count($data) < 2) {
						$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
						$update_stmt->bindValue(':status', 'E', PDO::PARAM_STR);
						$update_stmt->bindValue(':comments', 'Error in CSV File'.$data, PDO::PARAM_STR);
						$update_stmt->execute();
						sendMassActionFailedNE("BulkAddCSVToProduct", "Error in CSV File");
						return;
					}
					
					logToFile("(Dap_Cron.handleBulkAddCsvToProduct() - data[0]:".$data[0]); //email
					logToFile("(Dap_Cron.handleBulkAddCsvToProduct() - data[1]:".$data[1]); //firstname
					logToFile("(Dap_Cron.handleBulkAddCsvToProduct() - data[2]:".$data[2]); //lastname
					logToFile("(Dap_Cron.handleBulkAddCsvToProduct() - data[3]:".$data[3]); //username
					
					
					$payload2 = $product_id . "||" . $data[0] . "||" . $data[1];
					if(isset($data[2])) {
						$payload2 = $payload2 . "||" . $data[2] . "||" . $ispaid;
					} else {
						$payload2 = $payload2 . "||" . "||" . $ispaid;
					}
					
					if(isset($data[3])) { //username 
						$payload2 = $payload2 . "||" . $data[3];
					} else {
						$payload2 = $payload2 . "||" . "||" . "||";
					}
					
					
					$key = $product_id.":".$data[0].":".$parentkey;
					logToFile("(Dap_Cron.handleBulkAddCsvToProduct() - Insert Payload:".$payload2);
					try {
						$insert_stmt->bindParam(':payload', $payload2, PDO::PARAM_STR);
						$insert_stmt->bindParam(':key', $key, PDO::PARAM_STR);
						$insert_stmt->execute();
					} catch (PDOException $e) {
						$msg =  $e->getMessage();
						sendMassActionFailedNE("BulkAddCSVToProduct", "Error inserting ADDUSERTOPRODUCT");
						echo $msg;
					}
				}
				fclose($handle);

				//COMPLETED TASK SUCCESSFULLY
				$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
				$update_stmt->bindValue(':status', 'C', PDO::PARAM_STR);
				$update_stmt->bindValue(':comments', 'Completed Successfully', PDO::PARAM_STR);
				$update_stmt->execute();
			}

			return;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}

	/*
	actionType=ADDUSERTOPRODUCT
	Payload format
	#PRODUCTID||EMAILID||FIRSTNAME||LASTNAME (optional)||ISPAID


	*/
	public static function handleAddNewUserToProduct() {
		logToFile("(Dap_Cron.handleAddNewUserToProduct() - Method Init.");
		try {

			$dap_dbh = Dap_Connection::getConnection();
			$select_sql = "select
						id,
						payload
					from
						dap_mass_actions
					where
						status in ('NEW', 'E') and
						actionType = 'ADDUSERTOPRODUCT'
					limit 100
					";
			$update_sql = "update
							dap_mass_actions
							set
							status = :status,
							comments = :comments
							where
							id = :id
							";

			//logToFile($sql,LOG_DEBUG_DAP);
			$select_stmt = $dap_dbh->prepare($select_sql);
			$update_stmt = $dap_dbh->prepare($update_sql);

			//execute select
			$select_stmt->execute();

			while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
				//get payload
				$payload = $row['payload'];
				//parse payload
				$tokens = explode("||",$payload);
				if(count($tokens) < 4) {
					$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
					$update_stmt->bindValue(':status', 'E', PDO::PARAM_STR);
					$update_stmt->bindValue(':comments', 'Payload Format Is Not Correct', PDO::PARAM_STR);
					$update_stmt->execute();
					sendMassActionFailedNE("AddUserToProduct", "Payload format is not correct");
					continue;
				}
				
				logToFile("(Dap_Cron.handleAddNewUserToProduct() - tokens[0]:".$tokens[0]); //productid
				logToFile("(Dap_Cron.handleAddNewUserToProduct() - tokens[1]:".$tokens[1]); //email
				logToFile("(Dap_Cron.handleAddNewUserToProduct() - tokens[2]:".$tokens[2]); //firstname
				logToFile("(Dap_Cron.handleAddNewUserToProduct() - tokens[3]:".$tokens[3]); //lastname
				logToFile("(Dap_Cron.handleAddNewUserToProduct() - tokens[4]:".$tokens[4]); // ispaid
				logToFile("(Dap_Cron.handleAddNewUserToProduct() - tokens[5]:".$tokens[5]); // username
																																																										
				//call addUserToProduct($email, $firstname, $lastname, $productid, $ispaid='n')
				logToFile("(Dap_Cron.handleAddNewUserToProduct() - Calling addNewUserToProduct"); // username
				$uid = Dap_UsersProducts::addNewUserToProduct($tokens[1], $tokens[2], $tokens[3], $tokens[5], $tokens[0], $tokens[4], "A");
				if ( !isset($uid) || is_null($uid) || ($uid == 0) ) {
					$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
					$update_stmt->bindValue(':status', 'E', PDO::PARAM_STR);
					$update_stmt->bindValue(':comments', 'Error Adding User (possibly duplicate)', PDO::PARAM_STR);
					$update_stmt->execute();
					sendMassActionFailedNE("AddUserToProduct", "Error Adding User (possibly duplicate)");
					continue;
				} else {
					logToFile("(Dap_Cron.handleAddNewUserToProduct() - Finished addNewUserToProduct"); // username
					//COMPLETED TASK SUCCESSFULLY
					$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
					$update_stmt->bindValue(':status', 'C', PDO::PARAM_STR);
					$update_stmt->bindValue(':comments', 'Completed Successfully', PDO::PARAM_STR);
					$update_stmt->execute();
				}
			}

			return;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}

	/*
	actionType=ADDUSERTOPRODUCT
	Payload format
	#PRODUCTID||EMAILID||FIRSTNAME||LASTNAME (optional)||ISPAID


	*/
	public static function handleAddUserToProduct() {
		logToFile("(Dap_Cron.handleAddUserToProduct() - Method Init.");
		try {

			$dap_dbh = Dap_Connection::getConnection();
			$select_sql = "select
						id,
						payload
					from
						dap_mass_actions
					where
						status in ('NEW', 'E') and
						actionType = 'ADDUSERTOPRODUCT'
					limit 100
					";
			$update_sql = "update
							dap_mass_actions
							set
							status = :status,
							comments = :comments
							where
							id = :id
							";

			//logToFile($sql,LOG_DEBUG_DAP);
			$select_stmt = $dap_dbh->prepare($select_sql);
			$update_stmt = $dap_dbh->prepare($update_sql);

			//execute select
			$select_stmt->execute();

			while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
				//get payload
				$payload = $row['payload'];
				//parse payload
				$tokens = explode("||",$payload);
				if(count($tokens) < 4) {
					$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
					$update_stmt->bindValue(':status', 'E', PDO::PARAM_STR);
					$update_stmt->bindValue(':comments', 'Payload Format Is Not Correct', PDO::PARAM_STR);
					$update_stmt->execute();
					sendMassActionFailedNE("AddUserToProduct", "Payload format is not correct");
					continue;
				}
				//call addUserToProduct($email, $firstname, $lastname, $productid, $ispaid='n')
				Dap_UsersProducts::addUserToProduct($tokens[1], $tokens[2], $tokens[3], $tokens[0], $tokens[4], "A");
				//COMPLETED TASK SUCCESSFULLY
				$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
				$update_stmt->bindValue(':status', 'C', PDO::PARAM_STR);
				$update_stmt->bindValue(':comments', 'Completed Successfully', PDO::PARAM_STR);
				$update_stmt->execute();
			}

			return;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}


	/*
	actionType = EMAILTOCSV
	Payload format #CSVFILENAME||SUBJECT||BODY||ATTACHMENTS


	*/
	public static function handleEmailToCsv() {
		logToFile("(Dap_Cron.handleEmailToCsv() - Method Init.");
		try {

			$dap_dbh = Dap_Connection::getConnection();
			$select_sql = "select
						id,
						actionKey,
						payload
					from
						dap_mass_actions
					where
						status in ('NEW', 'E') and
						actionType = 'EMAILTOCSV'
					";
			$update_sql = "update
							dap_mass_actions
							set
							status = :status,
							comments = :comments
							where
							id = :id
							";
			$insert_sql = "insert into dap_mass_actions
								(actionType, actionKey, payload, status, priority)
							values ('EMAIL', :key, :payload, 'NEW', 4)
							";

			//logToFile($sql,LOG_DEBUG_DAP);
			$select_stmt = $dap_dbh->prepare($select_sql);
			$update_stmt = $dap_dbh->prepare($update_sql);
			$insert_stmt = $dap_dbh->prepare($insert_sql);

			//execute select
			$select_stmt->execute();

			if ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
				//get payload
				$payload = $row['payload'];
				$parentkey = $row['actionKey'];
				//parse payload
				$tokens = explode("||",$payload);
				if(count($tokens) < 4) {
					$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
					$update_stmt->bindValue(':status', 'E', PDO::PARAM_STR);
					$update_stmt->bindValue(':comments', 'Payload Format Is Not Correct', PDO::PARAM_STR);
					$update_stmt->execute();
					return;
				}
				//lets get values
				$csvfile = $tokens[0];
				$subject = $tokens[1];
				$body = $tokens[2];
				$attachments = $tokens[3];
				$dae = Dap_Cron::doesAttachmentsExist($attachments);
				if(!$dae) {
					$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
					$update_stmt->bindValue(':status', 'E', PDO::PARAM_STR);
					$update_stmt->bindValue(':comments', 'One or more attachments does not exist'.$data, PDO::PARAM_STR);
					$update_stmt->execute();
					// TODO : continue or return ?
					return;
				}
				//see if file exists and read if it does
				logToFile("(Dap_Cron.handleEmailToCsv() - Opening CSV File:".$csvfile, LOG_INFO_DAP);
				$handle = fopen($csvfile, "r");
				logToFile("(Dap_Cron.handleEmailToCsv() - Opening CSV File:".$handle);
				while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
					$subjectLocal = $subject;
					$bodyLocal = $body;
					logToFile("(Dap_Cron.handleEmailToCsv() - CSV Row:".$data, LOG_INFO_DAP);
				    /**
					if(count($data) < 2) {
						$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
						$update_stmt->bindValue(':status', 'E', PDO::PARAM_STR);
						$update_stmt->bindValue(':comments', 'Error in CSV File'.$data, PDO::PARAM_STR);
						$update_stmt->execute();
						// TODO : continue or return ?
						return;
					}
					*/
					
					if(!isset($data[0])) continue; //if no email found in first column, continue to next row
					$data[1] = isset($data[1]) ? $data[1] : "-";
					$data[2] = isset($data[2]) ? $data[2] : "-";
					$payload2 = $data[0] . "||" . $data[1] . "||" . $data[2];

					/**
						Time to personalize first and last right now because 
						for CSV broadcast, there will be no user record to find later and personalize
						So have to do it at the time of insert
					*/
					
					$firstNameMergeTag = "%%FIRST_NAME%%";
					$lastNameMergeTag = "%%LAST_NAME%%";
					
					$subjectLocal = str_replace($firstNameMergeTag, $data[1], $subjectLocal);
					$subjectLocal = str_replace($lastNameMergeTag, $data[2], $subjectLocal);
					$bodyLocal = str_replace($firstNameMergeTag, $data[1], $bodyLocal);
					$bodyLocal = str_replace($lastNameMergeTag, $data[2], $bodyLocal);
					
					$key = $data[0].":".$parentkey;
					$payload2 = $payload2 . "||" . $subjectLocal . "||" . $bodyLocal . "||" . $attachments;


					logToFile("(Dap_Cron.handleEmailToCsv() - Insert Payload:".$payload2, LOG_INFO_DAP);
					$insert_stmt->bindParam(':key', $key, PDO::PARAM_STR);
					$insert_stmt->bindParam(':payload', $payload2, PDO::PARAM_STR);
					try {
						$insert_stmt->execute();
					} catch (PDOException $e) {
						logToFile($e->getMessage(),LOG_FATAL_DAP);
					}
				}
				fclose($handle);

				//COMPLETED TASK SUCCESSFULLY
				$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
				$update_stmt->bindValue(':status', 'C', PDO::PARAM_STR);
				$update_stmt->bindValue(':comments', 'Completed Successfully', PDO::PARAM_STR);
				$update_stmt->execute();
			}

			return;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}

	/*
	actionType = EMAILTOPRODUCT
	Payload format #PRODUCTID||SUBJECT||BODY||ATTACHMENTS


	*/
	public static function handleEmailToProduct() {
		logToFile("(Dap_Cron.handleEmailToProduct() - Method Init.");
		try {

			$dap_dbh = Dap_Connection::getConnection();
			$select_sql = "select
						id,
						payload,
						actionKey
					from
						dap_mass_actions
					where
						status in ('NEW', 'E') and
						actionType = 'EMAILTOPRODUCT'
					";
			$select_users_sql = "select
						u.id,
						u.first_name,
						u.last_name,
						u.email,
						u.password
					from
						dap_users_products_jn upj,
						dap_users u
					where
						upj.product_id = :product_id and
						upj.status =  'A' and
						u.id = upj.user_id and
						u.status = 'A' and 
						u.opted_out = 'N' and 
						CURDATE() <= upj.access_end_date
					";
			$update_sql = "update
							dap_mass_actions
							set
							status = :status,
							comments = :comments
							where
							id = :id
							";
			$insert_sql = "insert into dap_mass_actions
								(actionType, actionKey, payload, status, priority)
							values ('EMAIL', :key, :payload, 'NEW', 4)
							";

			//logToFile($sql,LOG_DEBUG_DAP);
			$select_stmt = $dap_dbh->prepare($select_sql);
			$select_users_stmt = $dap_dbh->prepare($select_users_sql);
			$update_stmt = $dap_dbh->prepare($update_sql);
			$insert_stmt = $dap_dbh->prepare($insert_sql);

			//execute select
			$select_stmt->execute();

			if ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
				//get payload
				$payload = $row['payload'];
				$parentkey = $row['actionKey'];
				//parse payload
				$tokens = explode("||",$payload);
				if(count($tokens) < 4) {
					$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
					$update_stmt->bindValue(':status', 'E', PDO::PARAM_STR);
					$update_stmt->bindValue(':comments', 'Payload Format Is Not Correct', PDO::PARAM_STR);
					$update_stmt->execute();
					return;
				}
				//lets get values
				$productId = $tokens[0];
				$subject = $tokens[1];
				$body = $tokens[2];
				$attachments = $tokens[3];
				$dae = Dap_Cron::doesAttachmentsExist($attachments);
				if(!$dae) {
					$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
					$update_stmt->bindValue(':status', 'E', PDO::PARAM_STR);
					$update_stmt->bindValue(':comments', 'One or more attachments does not exist'.$data, PDO::PARAM_STR);
					$update_stmt->execute();
					// TODO : continue or return ?
					return;
				}
				//see if file exists and read if it does
				logToFile("(Dap_Cron.handleEmailToProduct() - Product Id:".$productId, LOG_INFO_DAP);
				$select_users_stmt->bindParam(':product_id',$productId);
				$select_users_stmt->execute();
				$results2 = $select_users_stmt->fetchAll(PDO::FETCH_ASSOC);
				//lets loops thru all the user ids and create EMAIL transactions
				//while ($user_row = $select_users_stmt->fetch(PDO::FETCH_ASSOC)) {
				foreach ($results2 as $key2 => $user_row) {
				    logToFile("(Dap_Cron.handleEmailToProduct() - User Row Email:".$user_row['email'], LOG_INFO_DAP);
					
					$user = Dap_User::loadUserByEmail($user_row['email']);
					$product = Dap_Product::loadProduct($productId);
					$body = personalizeMessageUserProduct($user,$product,$body);

					$payload2 = $user_row['email'] . "||" . $user_row['first_name'] . "||" . $user_row['last_name'];

					$payload2 = $payload2 . "||" . $subject . "||" . $body . "||" . $attachments . "||" . $user_row['password'];
					$key = $parentkey.":".$user_row['email'];
					logToFile("(Dap_Cron.handleEmailToProduct() - Insert Payload:".$payload2, LOG_INFO_DAP);
					$insert_stmt->bindParam(':payload', $payload2, PDO::PARAM_STR);
					$insert_stmt->bindParam(':key', $key, PDO::PARAM_STR);
					try {
						$insert_stmt->execute();
					} catch (PDOException $e) {
						logToFile($e->getMessage(),LOG_FATAL_DAP);
					}
				}

				//COMPLETED TASK SUCCESSFULLY
				$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
				$update_stmt->bindValue(':status', 'C', PDO::PARAM_STR);
				$update_stmt->bindValue(':comments', 'Completed Successfully', PDO::PARAM_STR);
				$update_stmt->execute();
			}

			return;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}

	/*
		actionType = BE-ALLUSERS/BE-ALLAFF/BE-ALLUNCONF//BE-ALLNOPROD (basically all Group 1)
		Payload format SUBJECT||BODY||ATTACHMENTS
	*/
	public static function handleBEAllUsers() {
		logToFile("(Dap_Cron.handleBEAllUsers() - Method Init.");
		try {

			$dap_dbh = Dap_Connection::getConnection();
			$select_sql = "select
						id,
						actionType,
						actionKey,
						payload
					from
						dap_mass_actions
					where
						status in ('NEW', 'E') and
						actionType in ('BE-ALLUSERS','BE-ALLPAIDUSERS', 'BE-ALLPAIDACTUSERS', 'BE-ALLPAIDEXPUSERS', 'BE-ALLFREEDOUBOPT', 'BE-ALLAFF', 'BE-ALLUNCONF', 'BE-ALLNOPROD')
					";
			
			
			$select_users_sql_all_users = "select
						u.id,
						u.first_name,
						u.last_name,
						u.email,
						u.password
					from
						dap_users u
					where
						u.status =  'A' and
						u.opted_out = 'N'
					";
			
			$select_users_sql_all_active_paid_users = "SELECT 
					distinct u.id,
					u.first_name,
					u.last_name,
					u.email,
					u.password
				FROM
					dap_products p,
					dap_users u,
					dap_users_products_jn upj
				WHERE
					u.status = 'A'
					and u.opted_out = 'N'
					and p.status = 'A'
					and p.is_free_product = 'N'
					and upj.user_id = u.id
					and upj.product_id = p.id
					and upj.status = 'A'
					and ( datediff(upj.access_end_date, curdate()) >= 0 )
					";
					
			$select_users_sql_all_expired_paid_users = "SELECT 
					distinct u.id,
					u.first_name,
					u.last_name,
					u.email,
					u.password
				FROM
					dap_products p,
					dap_users u,
					dap_users_products_jn upj
				WHERE
					u.status = 'A'
					and u.opted_out = 'N'
					and p.status = 'A'
					and p.is_free_product = 'N'
					and upj.user_id = u.id
					and upj.product_id = p.id
					and upj.status = 'A'
					and ( datediff(upj.access_end_date, curdate()) < 0 ) and
					upj.user_id not in (select user_id from dap_users_products_jn where u.id=user_id and datediff(	access_end_date, curdate()) > 0)
					";
					
			$select_users_sql_all_paid_users = "SELECT 
					distinct u.id,
					u.first_name,
					u.last_name,
					u.email,
					u.password
				FROM
					dap_products p,
					dap_users u,
					dap_users_products_jn upj
				WHERE
					u.status = 'A'
					and u.opted_out = 'N'
					and p.status = 'A'
					and p.is_free_product = 'N'
					and upj.user_id = u.id
					and upj.product_id = p.id
					and upj.status = 'A'
					";
				
			$select_users_sql_all_free_double_opted_in_users = "SELECT 
					distinct u.id,
					u.first_name,
					u.last_name,
					u.email,
					u.password
				FROM
					dap_products p,
					dap_users u,
					dap_users_products_jn upj
				WHERE
					u.status = 'A'
					and u.opted_out = 'N'
					and p.status = 'A'
					and p.is_free_product = 'Y'
					and upj.user_id = u.id
					and upj.product_id = p.id
					and upj.status = 'A'
					";
					
			$select_users_sql_all_unconfirmed_users = "select
						u.id,
						u.first_name,
						u.last_name,
						u.email,
						u.password
					from
						dap_users u
					where
						u.status =  'U' and
						u.opted_out = 'N'
					";
			$select_users_sql_all_aff = "select
						u.id,
						u.first_name,
						u.last_name,
						u.email,
						u.password
					from
						dap_users u
					where
						u.status =  'A' and
						u.opted_out = 'N' and
						u.id in (select distinct(affiliate_id) from dap_aff_referrals)
					";
			$select_users_sql_all_noprod = "select
						u.id,
						u.first_name,
						u.last_name,
						u.email,
						u.password
					from 
						dap_users u
					where 
						u.id not in 
						(select distinct user_id from dap_users_products_jn) and
						u.opted_out = 'N'";
	
			
			
			$update_sql = "update
							dap_mass_actions
							set
							status = :status,
							comments = :comments
							where
							id = :id
							";
			
			$insert_sql = "insert into dap_mass_actions
								(actionType, actionKey, payload, status, priority)
							values ('EMAIL', :key, :payload, 'NEW', 4)
							";

			//logToFile($sql,LOG_DEBUG_DAP);
			$select_stmt = $dap_dbh->prepare($select_sql);
			$update_stmt = $dap_dbh->prepare($update_sql);
			$insert_stmt = $dap_dbh->prepare($insert_sql);

			//execute select
			$select_stmt->execute();
			$results = $select_stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($results as $key => $row) {
			//while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
				//get payload
				$payload = $row['payload'];
				$parentkey = $row['actionKey'];
				//parse payload
				$tokens = explode("||",$payload);
				if(count($tokens) < 3) {
					$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
					$update_stmt->bindValue(':status', 'E', PDO::PARAM_STR);
					$update_stmt->bindValue(':comments', 'Payload Format Is Not Correct', PDO::PARAM_STR);
					$update_stmt->execute();
					return;
				}
				//lets get values
				$subject = $tokens[0];
				$body = $tokens[1];
				$attachments = $tokens[2];
				$dae = Dap_Cron::doesAttachmentsExist($attachments);
				if(!$dae) {
					$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
					$update_stmt->bindValue(':status', 'E', PDO::PARAM_STR);
					$update_stmt->bindValue(':comments', 'One or more attachments does not exist'.$data, PDO::PARAM_STR);
					$update_stmt->execute();
					// TODO : continue or return ?
					return;
				}
				//see if file exists and read if it does
				if($row['actionType'] == "BE-ALLUSERS") {
					$select_users_stmt = $dap_dbh->prepare($select_users_sql_all_users);
				}
				else if($row['actionType'] == "BE-ALLPAIDUSERS") {
					$select_users_stmt = $dap_dbh->prepare($select_users_sql_all_paid_users);
				}
				else if($row['actionType'] == "BE-ALLPAIDACTUSERS") {
					$select_users_stmt = $dap_dbh->prepare($select_users_sql_all_active_paid_users);
				}
				else if($row['actionType'] == "BE-ALLPAIDEXPUSERS") {
					$select_users_stmt = $dap_dbh->prepare($select_users_sql_all_expired_paid_users);
				}
				else if($row['actionType'] == "BE-ALLFREEDOUBOPT") {
					$select_users_stmt = $dap_dbh->prepare($select_users_sql_all_free_double_opted_in_users);
				}
				else if($row['actionType'] == "BE-ALLAFF") {
					$select_users_stmt = $dap_dbh->prepare($select_users_sql_all_aff);
				} 
				else if($row['actionType'] == "BE-ALLUNCONF") {
					$select_users_stmt = $dap_dbh->prepare($select_users_sql_all_unconfirmed_users);
				}  
				else if($row['actionType'] == "BE-ALLNOPROD") {
					$select_users_stmt = $dap_dbh->prepare($select_users_sql_all_noprod);
				} else {
					continue;
				}
				
				$select_users_stmt->execute();
				$results2 = $select_users_stmt->fetchAll(PDO::FETCH_ASSOC);
				foreach ($results2 as $key => $user_row) {
				//lets loops thru all the user ids and create EMAIL transactions
				//while ($user_row = $select_users_stmt->fetch(PDO::FETCH_ASSOC)) {
				    logToFile("(Dap_Cron.handleBEAllUsers() - User Row Email:".$user_row['email'], LOG_INFO_DAP);
					$payload2 = $user_row['email'] . "||" . $user_row['first_name'] . "||" . $user_row['last_name'];

					$payload2 = $payload2 . "||" . $subject . "||" . $body . "||" . $attachments . "||" . $user_row['password'];
					$key = $user_row['email'] . ":" . $parentkey;
					logToFile("(Dap_Cron.handleBEAllUsers() - Insert Payload:".$payload2, LOG_INFO_DAP);
					$insert_stmt->bindParam(':payload', $payload2, PDO::PARAM_STR);
					$insert_stmt->bindParam(':key', $key, PDO::PARAM_STR);
					try {
						$insert_stmt->execute();
					} catch (PDOException $e) {
						logToFile($e->getMessage(),LOG_FATAL_DAP);
					} catch (Exception $e) {
						logToFile($e->getMessage(),LOG_FATAL_DAP);
					}
				}

				//COMPLETED TASK SUCCESSFULLY
				$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
				$update_stmt->bindValue(':status', 'C', PDO::PARAM_STR);
				$update_stmt->bindValue(':comments', 'Completed Successfully', PDO::PARAM_STR);
				$update_stmt->execute();
			}

			return;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}


	/*
	actionType=EMAIL
	Payload format
	#TOEMAILID||FIRSTNAME||LASTNAME (optional)||SUBJECT||BODY||COMMADELLISTOFATTACHFILES


	*/
	public static function handleEmail() {
		logToFile("(Dap_Cron.handleEmail() - Method Init.");
		try {
			//initialize the smtpserver config in db.
			Dap_SMTPServer::init();
			//lets go in a large loop
			for($i=0; $i<100;$i++) {
				$sent_email_counter = 0;


			//get smtp server
			$server = Dap_SMTPServer::get();
			if(!isset($server)) {
				logToFile("(Dap_Cron.handleEmail() - SMTPServer Not Available.");
				//break the loop if we dont have any more smtp servers to use;
				return;
			}
			//get batch size for this smtp server
			$batch_size = $server->getUseableLimit();
			//
			$dap_dbh = Dap_Connection::getConnection();
			$select_sql = "select
						id,
						payload
					from
						dap_mass_actions
					where
						status in ('NEW', 'E') and
						actionType = 'EMAIL'
					order by id asc
					limit
					";
			$update_sql = "update
							dap_mass_actions
							set
							status = :status,
							comments = :comments
							where
							id = :id
							";

			//$batch_size = getEmailBatchSize();
			// no more delay
			//$delay = getEmailDelay();
			$select_sql = $select_sql . $batch_size;
			logToFile("(Dap_Cron.handleEmail() - SMTPServer: " . $server->getServer() . "Batch Size: ". $batch_size);
			logToFile("(Dap_Cron.handleEmail() - SQL: $select_sql");

			//logToFile($sql,LOG_DEBUG_DAP);
			$select_stmt = $dap_dbh->prepare($select_sql);
			$update_stmt = $dap_dbh->prepare($update_sql);

			//execute select
			$select_stmt->execute();

			//lets see if we got results. if not, break; return 
			$cols = $select_stmt->columnCount();
			if($cols <= 0) {
				//we dont have any more emails to send from the db, lets break the large loop and return;
				return;
			}
			
			//we have emails to send and we have smtp server handy to send emails.

			//create the mailer object using information from the smtp server config;
			$host = $server->getServer();
			logToFile("SMTP Host To Be Used: $host");
			$mail  = new PHPMailerDAP();
			$mail->SetLanguage('en','language/');
			if("local_web_host" != strtolower($host)) {	
				//$mail->SMTPDebug = true;
				$mail->IsSMTP();
				$mail->SMTPAuth = true;
				$mail->Username = $server->getUserid();
				$mail->Password = $server->getPassword();
				//logToFile("Userid: $mail->Username, Password:$mail->Password,");
				$ssl = $server->getSsl();
				if("y" == strtolower($ssl)) {
					$mail->SMTPSecure = "ssl";
					//$mail->SMTPSecure = "tls";
				}
				$mail->Host = $host;
				$mail->Port = $server->getPort();
			}
			
				
			while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
				logToFile("(Dap_Cron.handleEmail() - Processing row.");
				//get payload
				$payload = $row['payload'];
				//parse payload
				$tokens = explode("||",$payload);
				if(count($tokens) < 6) {
					logToFile("(Dap_Cron.handleEmail() - Payload Format Is Not Correct", LOG_FATAL_DAP);
					$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
					$update_stmt->bindValue(':status', 'E', PDO::PARAM_STR);
					$update_stmt->bindValue(':comments', 'Payload Format Is Not Correct', PDO::PARAM_STR);
					$update_stmt->execute();
					continue;
				}
				//lets get data
				$to = $tokens[0];
				$first_name = $tokens[1];
				$last_name = $tokens[2];
				$subject = $tokens[3];
				$password = "";
				$productId = "";
				$product = null;
				
				if(isset($tokens[6]) && $tokens[6] != "") {
					$password = $tokens[6];
				}
				if(isset($tokens[7]) && $tokens[7] != "") {
					$productId = $tokens[7];
				}
				$subject = personalizeMessageDet($to, $first_name, $last_name, $subject, $password); //str_replace("%%FIRST_NAME%%", $first_name, $subject);
				//TODO: transform the subject
				$body = $tokens[4];
				
				$user = Dap_User::loadUserByEmail($to);
				//$body = personalizeMessageDet($to, $first_name, $last_name, $body, $password); //str_replace("%%FIRST_NAME%%", $first_name, $subject);
				$body = $body . "\n\n\n\n" . getEmailFooter();
				$userId = null;
				
				if(!is_null($user)) {
					$body = personalizeMessage($user, $body); 
					$userId = $user->getId();
					//logToFile("userId: " . $userId . " productId: " . $productId); 
				
					if($productId != "") {
						$product = Dap_Product::loadProduct($productId);
						if(!is_null($product) && !is_null($user)) {
							$userProduct = Dap_UsersProducts::load($userId, $product->getId());
							if(!is_null($userProduct)) {
								$body = personalizeMessageUserProduct($user, $product, $body); 
							}
						}
					}
				}
				
				//TODO: Transform the body
				$attach_str = $tokens[5];
				if(!isset($first_name) || $first_name == "") {
					logToFile("(Dap_Cron.handleEmail() - Missing First Name", LOG_ERROR_DAP);
					$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
					$update_stmt->bindValue(':status', 'E', PDO::PARAM_STR);
					$update_stmt->bindValue(':comments', 'Payload Format Is Not Correct - Missing First Name', PDO::PARAM_STR);
					$update_stmt->execute();
					continue;
				}
				//
				if(!isset($subject) || $subject == "") {
					logToFile("(Dap_Cron.handleEmail() - Missing Subject", LOG_ERROR_DAP);
					$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
					$update_stmt->bindValue(':status', 'E', PDO::PARAM_STR);
					$update_stmt->bindValue(':comments', 'Payload Format Is Not Correct - Missing Subject', PDO::PARAM_STR);
					$update_stmt->execute();
					continue;
				}
				//
				if(!isset($body) || $body == "") {
					logToFile("(Dap_Cron.handleEmail() - Missing Body", LOG_ERROR_DAP);
					$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
					$update_stmt->bindValue(':status', 'E', PDO::PARAM_STR);
					$update_stmt->bindValue(':comments', 'Payload Format Is Not Correct - Missing Body', PDO::PARAM_STR);
					$update_stmt->execute();
					continue;
				}
				logToFile("(Dap_Cron.handleEmail() - Attachment str:*".$attach_str."*");
				//lets get list of attachments
				$from = getAdminEmail();
				$admin_name = Dap_Config::get("ADMIN_NAME");
				//attach footer
				//$body = $body . "\n\n\n\n" . getEmailFooter();
				//set From
				//$body = str_replace("\\", '', $body);
				
				//clear any previous stuff.
				$mail->ClearAllRecipients();
				$mail->ClearAttachments();
				//set new stuff
				$mail->From = $from;
				$mail->FromName = $admin_name;
				$mail->AddAddress($to);
				$mail->Subject = $subject;
				//$mail->MsgHTML($body);
				$pieces = explode(Dap_Config::get('HTMLSEPARATOR'),$body);
				//logToFile("pieces[0]: $pieces[0]"); 
				//logToFile("pieces[1]: $pieces[1]"); 
				$textBody = ($pieces[0] == "") ? "Sorry, this email is only being sent in HTML format." : $pieces[0];
				$htmlBody = $pieces[1];
				$mail->Body = $textBody;
				if($htmlBody != "") {
					$mail->Body = $htmlBody;
					$mail->isHTML = true;
					$mail->AltBody = $textBody;
				}


				if(isset($attach_str) && trim($attach_str) != "")  {
					logToFile("(Dap_Cron.handleEmail() - Attachment str is not empty:*".$attach_str."*");
					//lets process attachments and make sure they are all readable and exists. If not, write error and continue with processing other EMAILs.
					$attachs = explode(",",$attach_str);
					//for each attachment
					foreach($attachs as $filename) {
						$filename = DAP_ROOT . '/admin/attachments/' . $filename;
						if(is_readable($filename)) {
						    $mail->AddAttachment($filename);
						} else {
							//mark this as error and move on
							logToFile("(Dap_Cron.handleEmail() - Attachment does not exists:*".$filename."*");
							$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
							$update_stmt->bindValue(':status', 'E', PDO::PARAM_STR);
							$update_stmt->bindValue(':comments', 'Attachment does not exist', PDO::PARAM_STR);
							$update_stmt->execute();
							continue 2;
						}
					}
				}

				// now we just send the message
				//if (@mail($to, $subject, $message, $headers)) {
				logToFile("Here, about to send...");
				if (!$mail->Send()) {
					logToFile("(Dap_Cron.handleEmail() - Message Could NOT Be Sent To:".$to."*".$mail->ErrorInfo, LOG_ERROR_DAP);
					$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
					$update_stmt->bindValue(':status', 'E', PDO::PARAM_STR);
					$update_stmt->bindValue(':comments', 'Failed.'.$mail->ErrorInfo, PDO::PARAM_STR);
					$update_stmt->execute();
				} else {
					//echo "Message Sent";
					logToFile("(Dap_Cron.handleEmail() - Message Sent To:".$to."*");
					//COMPLETED TASK SUCCESSFULLY
					$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
					$update_stmt->bindValue(':status', 'C', PDO::PARAM_STR);
					$update_stmt->bindValue(':comments', 'Completed Successfully', PDO::PARAM_STR);
					$update_stmt->execute();
					//up the sent email count
					$sent_email_counter++;
					//lets sleep a bit, if the message was sent successfully.
					//logToFile("(Dap_Cron.handleEmail() - Sleeping (Delay) For:".$delay."*", LOG_INFO_DAP);
					//sleep($delay);
				}
			}

			//we need to go and update the stats in SMTP Server count
			logToFile("(Dap_Cron.handleEmail() - Sent Email Counts:".$sent_email_counter."*");
			if(isset($server) && $sent_email_counter > 0) {
				$server->updateRunningTotal($sent_email_counter);
			}
			//end of large outer loop;
			}
			return;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}
	/*
	* Cron for paying affiliates for sale
	- Take all transactions in certain finished state (user product created) ---- //Take all transactions from dap trans table that are  NOT in aff_transactions.
	- Get userid and product id for each of these trans using payer email and product id.
	- Get affiliate id from aff_referrals for the given userid, product id.
	- Check aff_transactions to see if this product sale commission is already paid and its not recurring pay enabled. Exit if its not recurring
		and already sale paid. Mark the trans as already aff processed.
		//TODO: HOW TO MARK THE TRANS AS ALREADY PROCESSED IN THIS CASE ?
	- Check commission structure from aff_comm to see if this product id, affiliateId has per sale commission.
	- If yes, calculate sale commission from trans payment_value and type of sale commision (%age or fixed) and insert into
		aff_transactions with this transid and aff_referrals_id.
	- Mark the transaction as aff processed.
	*/


//Send sale email

		
	public static function processTransactionsForAffiliation() {
		logToFile("(Dap_Cron.processTransactionsForAffiliation() - Method Init.");
		try {
			$dap_dbh = Dap_Connection::getConnection();
			$trans_select_sql = "select *
					from dap_transactions
					where
					status = 5 and
					trans_type != 'subscr_signup'
					and (date between (now() - interval 7 day) and now() )
					";
			$referrals_select_sql = "select id, affiliate_id
					from dap_aff_referrals
					where
					user_id = :user_id and
					product_id = :product_id
					";
			$aff_trans_select_sql = "select *
					from dap_aff_earnings
					where
					aff_referrals_id = :aff_referrals_id and
					earning_type = 'S'
					";
			$aff_trans_insert_sql = "insert into dap_aff_earnings
					(aff_referrals_id, amount_earned, datetime, transaction_id, earning_type)
					values
					(:aff_referrals_id, :amount_earned, now(), :transaction_id, 'S')
					";
			$aff_trans_insert_sql_credits = "insert into dap_aff_earnings
					(aff_referrals_id, amount_earned, datetime, transaction_id, earning_type)
					values
					(:aff_referrals_id, :amount_earned, now(), :transaction_id, 'C')
					";
			//echo "sql: $sql<br>"; exit;
			//logToFile("processTransactionsForAffiliations: SQL: ".$trans_select_sql);
			$trans_select_stmt = $dap_dbh->prepare($trans_select_sql);
			$referrals_select_stmt = null;
			$aff_trans_select_stmt = null;
			$aff_trans_insert_stmt = null;
			$trans_select_stmt->execute();

			//Statements to build the links
			while ($trans_select_row = $trans_select_stmt->fetch(PDO::FETCH_ASSOC)) {
				
				try {
					$referrals_select_stmt = $dap_dbh->prepare($referrals_select_sql);
					$aff_trans_select_stmt = $dap_dbh->prepare($aff_trans_select_sql);
					$aff_trans_insert_stmt = $dap_dbh->prepare($aff_trans_insert_sql);
					$aff_trans_insert_stmt_credits = $dap_dbh->prepare($aff_trans_insert_sql_credits);
					
					//get userid and product id
					$user = Dap_User::loadUserByEmail($trans_select_row['payer_email']);
					if(!isset($user)) continue;
					$processAffiliation = false;
					$affiliateId = null;
					$aff_ref_id = null;
					
					$userId = $user->getId();
					$productId = $trans_select_row['product_id'];
					$transId = $trans_select_row['id'];
					$transValue = $trans_select_row['payment_value'];
					logToFile("Dap_Cron.processTransactionsForAffiliation() - Processing TransactionId: ".$transId.", UserId:".$userId.", ProductId:".$productId);
					
					
					//lets see if we got some referral id for this user/product
					$referrals_select_stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
					$referrals_select_stmt->bindParam(':product_id', $productId, PDO::PARAM_INT);
					$referrals_select_stmt->execute();
					
					//logToFile("Dap_Cron.processTransactionsForAffiliation() - Past fail block for transId: ".$transId.", UserId:".$userId.", ProductId:".$productId);
					$referrals_select_row = $referrals_select_stmt->fetch(PDO::FETCH_ASSOC);
					
					if( !isset($referrals_select_row) || ($referrals_select_row == null) ) {
						//No affiliate referral found - could have referral for a different product purchased previously
						logToFile("User id: $userId - no referral found for product $productId");
						
						//Check if affiliate exists for this user
						$affiliateId = $user->getAffiliate();
						if(isset($affiliateId)) {
							//Affiliate exists
							$processAffiliation = true;
							logToFile("Found old affiliateId: $affiliateId");
							
							//Stamp pending products of user with affiliate id
							if( (Dap_Config::get("ALLOW_SELF_REFERRAL") == "Y") || ($affiliateId != $userId) ) {
								logToFile("Figure out all products for user $userId for which no affiliate id has been set"); 
								//1. Figure out all products for this user, for which no affiliate id has been set
								$ProductListArray = Dap_AffReferrals::getProductsPendingAffiliateStamping($userId);
								
								if( sizeof($ProductListArray != 0) ){
									//Process Affiliate Referral Insertion & Process Lead
									logToFile("Processing affiliations for unstamped products for user $userId and affiliate $affiliateId"); 
									Dap_AffReferrals::processAffiliation($affiliateId, $userId, $ProductListArray);
								}
							}
						} 
						continue;
					} 
					else if($referrals_select_row) {
						//we have a referral
						$affiliateId = $referrals_select_row['affiliate_id'];
						$aff_ref_id = $referrals_select_row['id'];
						$processAffiliation = true;
						logToFile("Dap_Cron.processTransactionsForAffiliation() - We have referrer for the user/product. Affiliate Id:".$affiliateId, LOG_INFO_DAP);
					}
					
					//If either new affiliate, or old aff existed, process...
					if($processAffiliation) {
						//load dap_aff_comm (commision setup) data to see if this has any commission setup and is reccurring.
						//we have to pass affiliateId and not the userid of the person who bought th eproduct
						$dap_aff_comm = Dap_AffCommissions::load($affiliateId, $productId);
						if( is_null($dap_aff_comm) ) {
							//no commission setup, mark transaction as complete and go to next one
							Dap_Transactions::setRecordStatus($transId, 7);
							logToFile("Dap_Cron.processTransactionsForAffiliation() - NO COMMISSION SETUP. Marking Transaction as complete..TransId:".$transId);
							continue;
						}
						//check if this is sale commission setup
						if ( ($dap_aff_comm->isSaleCommission() === false) && ($dap_aff_comm->isSaleCommissionCredits() === false) ){
							//no sale commission setup for cash or credits, skip this transaction.
							Dap_Transactions::setRecordStatus($transId, 7);
							logToFile("Dap_Cron.processTransactionsForAffiliation() - NO SALE COMMISSION SETUP. Marking Transaction as complete..TransId:".$transId, LOG_INFO_DAP);
							continue;
						}
						
						//If you reach here, then we have sale commission for cash or credits
						if($dap_aff_comm->getIs_comm_recurring() == "N") {
							//lets check if we already paid this guy for sale and this is not recurring commissions. If yes, mark trans complete and continue.
							//not necessary to check for credit payments, because sale and credit payments would have happened together anyway.
							//So enough to check for just one
							$aff_trans_select_stmt->bindParam(':aff_referrals_id', $aff_ref_id, PDO::PARAM_INT);
							$aff_trans_select_stmt->execute();
							if ($aff_trans_select_row = $aff_trans_select_stmt->fetch(PDO::FETCH_ASSOC)) {
								//we already credited this guy before and this is not recurring. So mark trans and move on.
								Dap_Transactions::setRecordStatus($transId, 7);
								logToFile("Dap_Cron.processTransactionsForAffiliation() - WE ALREADY CREDITED THIS AFFILIATE AND ITS NOT RECURRING COMMISSION. Marking Transaction as complete..TransId:".$transId, LOG_INFO_DAP);
								continue;
							}
						}
						
						//We are here because either this is recurring commission and/or we did not credit this affiliate before.
						
						//First process cash commissions
						if ($dap_aff_comm->isSaleCommission() === true) {
							//Cash commission is set - either per-sale fixed or per-sale percentage
							$sale_commission = $dap_aff_comm->calculateSaleCommission($transValue);
							logToFile("Dap_Cron.processTransactionsForAffiliation() - Calculated Sale Commission:".$sale_commission, LOG_INFO_DAP);
							$aff_trans_insert_stmt->bindParam(':aff_referrals_id', $aff_ref_id, PDO::PARAM_INT);
							$aff_trans_insert_stmt->bindParam(':amount_earned', $sale_commission, PDO::PARAM_INT);
							$aff_trans_insert_stmt->bindParam(':transaction_id', $transId, PDO::PARAM_INT);
							$aff_trans_insert_stmt->execute();
						
							//Send sale email
							$sendAffEmailSale = Dap_Config::get("SEND_AFF_EMAIL_SALE");
							logToFile("sendAffEmailSale: $sendAffEmailSale"); 
							if ($sendAffEmailSale == "Y") {
								logToFile("sendAffEmailSale = Y"); 
								sendAffiliateNotificationEmail($affiliateId, $productId, $userId, "S", $sale_commission);
							}
							logToFile("Dap_Cron.processTransactionsForAffiliation() - Cash Commission Credited for the amount: $sale_commission . TransId: ".$transId, LOG_INFO_DAP);
						}
						
						//Now process credit commissions
						if ($dap_aff_comm->isSaleCommissionCredits() === true) {
							//Credit commission is set - either per-sale fixed credits or per-sale percentage credits
							$sale_commission_credits = $dap_aff_comm->calculateSaleCommissionCredits($transValue);
							logToFile("Dap_Cron.processTransactionsForAffiliation() - Calculated Sale Commission:".$sale_commission, LOG_INFO_DAP);
							$aff_trans_insert_stmt_credits->bindParam(':aff_referrals_id', $aff_ref_id, PDO::PARAM_INT);
							$aff_trans_insert_stmt_credits->bindParam(':amount_earned', $sale_commission_credits, PDO::PARAM_INT);
							$aff_trans_insert_stmt_credits->bindParam(':transaction_id', $transId, PDO::PARAM_INT);
							$aff_trans_insert_stmt_credits->execute();
							logToFile("Dap_Cron.processTransactionsForAffiliation() - Credit Commissions Credited for the amount: $sale_commission . TransId: ".$transId, LOG_INFO_DAP);
						}

						Dap_Transactions::setRecordStatus($transId, 7);
						logToFile("Dap_Cron.processTransactionsForAffiliation() Marking Transaction as complete... TransId: ".$transId, LOG_INFO_DAP);

						//Do N-tier processing
						//processAffiliateCommissionsRecursively($aff_ref_id, $userId, $affiliateId, $productId, $transId, $transValue, 2);
						Dap_AffCommissions::processAffiliateCommissionsMultiTierRecursive($userId, $affiliateId, $productId, $transId, $transValue, 2);
	
					} else {
						logToFile("Dap_Cron.processTransactionsForAffiliation() - We dont have referrer for the user/product.");
						continue;
					}
					
					$referrals_select_stmt = null;
					$referrals_select_row = null;
				
				} //end try inside while
				catch (PDOException $e) {
					logToFile($e->getMessage(),LOG_FATAL_DAP);
					throw $e;
				} catch (Exception $e) {
					logToFile($e->getMessage(),LOG_FATAL_DAP);
					throw $e;
				}
				
			} //end while
			
			$trans_select_stmt = null;
			$referrals_select_stmt = null;
			$aff_trans_select_stmt = null;
			$aff_trans_insert_stmt = null;
			$dap_dbh = null;
			
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}

	}


	/*
	actionType =  BULKPAYAFFTOCSV
	Payload format CSVFILENAME||COMMENTS

	*/
	public static function handleBulkPayAffiliates() {
		logToFile("(Dap_Cron.handleBulkPayAffiliates() - Method Init.");
		try {

			$dap_dbh = Dap_Connection::getConnection();
			$select_sql = "select
						id,
						payload,
						actionKey
					from
						dap_mass_actions
					where
						status in ('NEW', 'E') and
						actionType = 'BULKPAYAFFTOCSV'
					";
			$update_sql = "update
							dap_mass_actions
							set
							status = :status,
							comments = :comments
							where
							id = :id
							";
			$insert_sql = "insert into dap_mass_actions
								(actionType, actionKey, payload, status)
							values ('PAYAFFILIATE', :key, :payload, 'NEW')
							";

			//logToFile($sql,LOG_DEBUG_DAP);
			$select_stmt = $dap_dbh->prepare($select_sql);
			$update_stmt = $dap_dbh->prepare($update_sql);
			$insert_stmt = $dap_dbh->prepare($insert_sql);

			//execute select
			$select_stmt->execute();

			if ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
				//get payload
				$payload = $row['payload'];
				$parentkey = $row['actionKey'];
				//parse payload
				$tokens = explode("||",$payload);
				if(count($tokens) < 1) {
					$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
					$update_stmt->bindValue(':status', 'E', PDO::PARAM_STR);
					$update_stmt->bindValue(':comments', 'Payload Format Is Not Correct', PDO::PARAM_STR);
					$update_stmt->execute();
					return;
				}
				//lets get values
				$csvfile = $tokens[0];
				//TODO: confirm if comments are at file level or individual payment level
				if(isset($tokens[1])) {
					$comment = $tokens[1];
				} else {
					$comment = "Bulk Payments";
				}
				//see if file exists and read if it does
				if(!file_exists($csvfile)) {
					logToFile("(Dap_Cron.handleBulkPayAffiliates() - File Doesnt Exist:".$csvfile,LOG_ERROR_DAP);
					$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
					$update_stmt->bindValue(':status', 'E', PDO::PARAM_STR);
					$update_stmt->bindValue(':comments', 'CSV File Does not exist', PDO::PARAM_STR);
					$update_stmt->execute();
					return;
				}
				logToFile("(Dap_Cron.handleBulkPayAffiliates() - Opening CSV File:".$csvfile);
				$handle = fopen($csvfile, "r");
				if($handle === FALSE) {
					logToFile("(Dap_Cron.handleBulkPayAffiliates() - Error on file open".$csvfile, LOG_ERROR_DAP);
					$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
					$update_stmt->bindValue(':status', 'E', PDO::PARAM_STR);
					$update_stmt->bindValue(':comments', 'Error opening CSV file', PDO::PARAM_STR);
					$update_stmt->execute();
					return;
				}
				logToFile("(Dap_Cron.handleBulkPayAffiliates() - Opening CSV File:".$handle);
				while (($data = fgetcsv($handle, 1000, "\t")) !== FALSE) {
					logToFile("(Dap_Cron.handleBulkPayAffiliates() - CSV Row:".$data);
					if(count($data) < 2) {
						$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
						$update_stmt->bindValue(':status', 'E', PDO::PARAM_STR);
						$update_stmt->bindValue(':comments', 'Error in CSV File'.$data, PDO::PARAM_STR);
						$update_stmt->execute();
						return;
					}
					//data[0] is email id
					//data[1] is amount
					//then comments
					$payload2 = $data[0] . "||" . $data[1] . "||" . $comment;
					$key = $data[0] . ":" . $parentkey;
					logToFile("(Dap_Cron.handleBulkPayAffiliates() - Insert Payload:".$payload2, LOG_INFO_DAP);
					$insert_stmt->bindParam(':key', $key, PDO::PARAM_STR);
					$insert_stmt->bindParam(':payload', $payload2, PDO::PARAM_STR);
					$insert_stmt->execute();
				}
				fclose($handle);
				
				//COMPLETED TASK SUCCESSFULLY
				$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
				$update_stmt->bindValue(':status', 'C', PDO::PARAM_STR);
				$update_stmt->bindValue(':comments', 'Completed Successfully', PDO::PARAM_STR);
				$update_stmt->execute();
				//Delete the affiliate csv file now.
				unlink($csvfile);	
			}

			$select_stmt = null;
			$update_stmt = null;
			$insert_stmt = null;
			$dap_dbh = null;
			return;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}
	/*
	actionType=PAYAFFILIATE
	Payload format
	EMAIL||AMOUNT||COMMENT
	*/
	public static function handlePayAffiliate() {
		logToFile("(Dap_Cron.handlePayAffiliate() - Method Init.");
		try {

			$dap_dbh = Dap_Connection::getConnection();
			$select_sql = "select
						id,
						payload,
						actionKey
					from
						dap_mass_actions
					where
						status in ('NEW', 'E') and
						actionType = 'PAYAFFILIATE'
					";
			$update_sql = "update
							dap_mass_actions
							set
							status = :status,
							comments = :comments
							where
							id = :id
							";
			$insert_sql = "insert into dap_mass_actions
								(actionType, actionKey, payload, status, priority)
							values ('EMAIL', :key, :payload, 'NEW', 4)
							";

			//logToFile($sql,LOG_DEBUG_DAP);
			$select_stmt = $dap_dbh->prepare($select_sql);
			$update_stmt = $dap_dbh->prepare($update_sql);
			$insert_stmt = $dap_dbh->prepare($insert_sql);

			//execute select
			$select_stmt->execute();

			while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
				logToFile("(Dap_Cron.handlePayAffiliate() - Processing row.");
				//get payload
				$payload = $row['payload'];
				$parentkey = $row['actionKey'];
				//parse payload
				$tokens = explode("||",$payload);
				if(count($tokens) < 3) {
					logToFile("(Dap_Cron.handlePayAffiliate() - Payload Format Is Not Correct", LOG_ERROR_DAP);
					$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
					$update_stmt->bindValue(':status', 'E', PDO::PARAM_STR);
					$update_stmt->bindValue(':comments', 'Payload Format Is Not Correct', PDO::PARAM_STR);
					$update_stmt->execute();
					continue;
				}
				//lets get data
				$user = Dap_User::loadUserByEmail($tokens[0]);
				if(!isset($user)) {
					logToFile("(Dap_Cron.handlePayAffiliate() - Affiliate Doesn't Exist For User", LOG_ERROR_DAP);
					$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
					$update_stmt->bindValue(':status', 'E', PDO::PARAM_STR);
					$update_stmt->bindValue(':comments', 'Affiliate Does Not Exist For User', PDO::PARAM_STR);
					$update_stmt->execute();
					continue;

				}
				$userId = $user->getId();
				$to = $tokens[0];
				$amount = $tokens[1];
				$comment = $tokens[2];
				$first_name = $user->getFirst_name();
				$last_name = $user->getLast_name();
				//insert aff payment
				$affpayment = new Dap_AffPayments();
				$affpayment->setAffiliate_id($userId);
				$affpayment->setAmount_paid($amount);
				$affpayment->setComments($comment);
				$affpayment->create();
				//insert done
				//TODO - get subject, body from config
				$subject = Dap_Config::get("AFF_PAYMENT_EMAIL_SUBJECT"); //"Hi %%FIRST_NAME%%, You Got Affiliate Payment";
				$subject = personalizeMessage($user, $subject); //str_replace("%%FIRST_NAME%%", $first_name, $subject);
				//TODO
				$body = Dap_Config::get("AFF_PAYMENT_EMAIL_CONTENT"); //"Hello %%FIRST_NAME%% %%LAST_NAME%%, You got affliate payment of %%AMOUNT%%";
				$body = personalizeMessage($user, $body); //str_replace("%%FIRST_NAME%%", $first_name, $subject);
				$body = str_replace("%%AMOUNT%%", $amount, $body);
				//DO NOT PUT FOOTER HERE. It will be done in handle Email job
				//$body = $body . "\n\n\n\n" . getEmailFooter();
				//insert into the mass action email table. 
				$payload2 = $to . "||" . $first_name . "||" . $last_name;
				$payload2 = $payload2 . "||" . $subject . "||" . $body . "||" . $attachments . "||" ;
				$key = $user_row['email'] . ":" . $parentkey;
				logToFile("(Dap_Cron.handlePayAffiliates() - Insert Payload:".$payload2, LOG_INFO_DAP);
				$insert_stmt->bindParam(':payload', $payload2, PDO::PARAM_STR);
				$insert_stmt->bindParam(':key', $key, PDO::PARAM_STR);
				try {
					$insert_stmt->execute();
					$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
					$update_stmt->bindValue(':status', 'C', PDO::PARAM_STR);
					$update_stmt->bindValue(':comments', 'Completed Successfully', PDO::PARAM_STR);
					$update_stmt->execute();
					continue;
				} catch (PDOException $e) {
					logToFile($e->getMessage(),LOG_FATAL_DAP);
				} catch (Exception $e) {
					logToFile($e->getMessage(),LOG_FATAL_DAP);
				}
				logToFile("(Dap_Cron.handlePayAffiliates() - Processing Failed:".$payload, LOG_INFO_DAP);
				$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
				$update_stmt->bindValue(':status', 'E', PDO::PARAM_STR);
				$update_stmt->bindValue(':comments', 'Processing Failed...', PDO::PARAM_STR);
				$update_stmt->execute();
			}

			$select_stmt = null;
			$update_stmt = null;
			$insert_stmt = null;
			$dap_dbh = null;

			return;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}
	

	/*
		Identify users and their email resources to be sent today and add them to mass actions table with action type EMAIL
	*/
	public static function sendAutomatedEmailResources() {
		logToFile("(Dap_Cron.sendAutomatedEmailResources() - Method Init.");
		try {

			$dap_dbh = Dap_Connection::getConnection();
			$select_sql = "
				SELECT 
					u.email, 
					u.first_name, 
					u.last_name, 
					u.password, 
					curdate() as date,
					upj.transaction_id as transid, 
					prj.is_free,
					prj.product_id,
					prj.file_resource_id
				FROM
					dap_products p,
					dap_products_resources_jn prj,
					dap_users u,
					dap_users_products_jn upj
				WHERE
					u.status = 'A'
					and u.opted_out = 'N'
					and prj.status = 'A'
					and prj.resource_type = 'E'
					and prj.file_resource_id > 0
					and p.id = prj.product_id
					and p.status = 'A'
					and upj.user_id = u.id
					and upj.product_id = p.id
					and upj.status = 'A'
					and curdate() between upj.access_start_date and upj.access_end_date
					and ( datediff(curdate(), upj.access_start_date) = prj.start_day - 1  or
						prj.start_date = curdate() )
					";
			$insert_sql = "insert into dap_mass_actions
								(actionType, actionKey, payload, status, priority)
							values ('EMAIL', :key, :payload, 'NEW', 2)
							";

			//logToFile($sql,LOG_DEBUG_DAP);
			$select_stmt = $dap_dbh->prepare($select_sql);
			$insert_stmt = $dap_dbh->prepare($insert_sql);

			//execute select
			$select_stmt->execute();
			$results = $select_stmt->fetchAll(PDO::FETCH_ASSOC);
			//while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
			foreach ($results as $key => $row) {
				try {
					logToFile("(Dap_Cron.sendAutomatedEmailResources() - Processing row.");
				
					$email = $row['email'];
					logToFile("(Dap_Cron.sendAutomatedEmailResources() - $email");
					$firstname = $row['first_name'];
					$lastname = $row['last_name'];
					//$subject = $row['subject'];
					$password = $row['password'];
					$product_id = $row['product_id'];
					$file_resource_id = $row['file_resource_id'];
					
					$FileResource = Dap_FileResource::loadFileResourceAutomated($product_id, $file_resource_id);
					$resourceURL = $FileResource["url"];
					$name=$FileResource["name"];
					
					/*foreach($FileResources as $FileResource) {
					$resourceURL = $FileResource["url"];
					logToFile("(Dap_Cron.sendAutomatedEmailResources() FOR resourceURL= " . $resourceURL);
					break;
					}*/
					
				    logToFile("(Dap_Cron.sendAutomatedEmailResources() resourceURL= " . $resourceURL);
																	 
					//$subject = Dap_Config::get("AUTOMATED_AUTORESPONDER_EMAIL_SUBJECT");
					
					$subject = Dap_Config::get("AUTOMATED_AUTORESPONDER_EMAIL_SUBJECT");
					$subject = str_replace("%%CONTENT_NAME%%", $name, $subject);
						
					$body = Dap_Templates::getContentByName("AUTOMATED_AUTORESPONDER_EMAIL_CONTENT");
					
					logToFile("(Dap_Cron.sendAutomatedEmailResources() subject " . $subject);
					logToFile("(Dap_Cron.sendAutomatedEmailResources() body " . $body);
																	 
					if(empty($body)) {
					  logToFile("ERROR..GetAutomatedAutoresponderEmailMessage Request: No template found in dap setup=>templates", LOG_FATAL_DAP);
					  //logToFile("htmlentities(email): " . urlencode($email)); 
					  //$output = mb_convert_encoding(MSG_SORRY_EMAIL_NOT_FOUND, "UTF-8", "ISO-8859-1") . " '" . urlencode($email) . "'.";
					  $output = "MSG_SORRY_EMAIL_NOT_FOUND";
					  return $output;
					}
					
					$body = str_replace("%%CONTENT_ID%%", $resourceURL, $body);
					$body = str_replace("%%CONTENT_NAME%%", $name, $body);
					//$str="[HTML_START]<html><body><a href='/geeksonly/members'>Click here to access</a>[HTML_START]</body></html>";
					//$body = str_replace("%%CONTENT_ID%%", $str, $body);
					
					$key = "";
					
					//TODO transfer attachment into proper format
					
					$key = $row['id'] . ":" . $email . ":ER:" . $row['date'] . ":" . $row['file_resource_id'];
					logToFile("(Dap_Cron.sendAutomatedEmailResources() - Insert Key:".$key, LOG_INFO_DAP);
																	 
					$payload = $email . "||" . $firstname . "||" . $lastname . "||" . $subject . "||" . $body . "||" . $attachment . "||" . $password;
					
					logToFile("(Dap_Cron.sendAutomatedEmailResources() - Insert Payload:".$payload, LOG_INFO_DAP);
																	 
					$insert_stmt->bindParam(':payload', $payload, PDO::PARAM_STR);
					$insert_stmt->bindParam(':key', $key, PDO::PARAM_STR);
					$insert_stmt->execute();
					
				} catch (PDOException $e) {
					logToFile($e->getMessage(),LOG_FATAL_DAP);
					if( stristr($e->getMessage(), 'Integrity constraint violation') === FALSE ) {
						logToFile($e->getMessage(),LOG_FATAL_DAP);
					}
				} catch (Exception $e) {
					logToFile($e->getMessage(),LOG_FATAL_DAP);
				}
			}
			
			$select_stmt = null;
			$insert_stmt = null;
			$dap_dbh = null;

			return;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}
	
	/*
		Identify users and their email resources to be sent today 
		and add them to mass actions table with action type EMAIL
	*/
	public static function sendEmailResources() {
		logToFile("(Dap_Cron.sendEmailResources() - Method Init.");
		try {

			$dap_dbh = Dap_Connection::getConnection();
			
			//SQL for normal days and dates
			$select_sql[] = "
				SELECT 
					u.email, 
					u.first_name, 
					u.last_name, 
					u.password, 
					er.id, 
					er.subject, 
					er.message, 
					er.attachment, 
					er.sendTo3rdParty, 
					er.thirdPartyEmailId, 
					curdate() as date,
					upj.transaction_id as transid, 
					prj.is_free,
					prj.product_id
				FROM
					dap_products p,
					dap_products_resources_jn prj,
					dap_email_resources er,
					dap_users u,
					dap_users_products_jn upj
				WHERE
					u.status = 'A'
					and u.opted_out = 'N'
					and prj.resource_id = er.id
					and prj.status = 'A'
					and prj.resource_type = 'E'
					and p.id = prj.product_id
					and p.status = 'A'
					and upj.user_id = u.id
					and upj.product_id = p.id
					and upj.status = 'A'
					and curdate() between upj.access_start_date and upj.access_end_date
					and ( datediff(curdate(), upj.access_start_date) = prj.start_day - 1  or
						prj.start_date = curdate() )
					";
					
					
			//SQL for negative dripping	
			$select_sql[] = "
				SELECT 
					u.email, 
					u.first_name, 
					u.last_name, 
					u.password, 
					er.id, 
					er.subject, 
					er.message, 
					er.attachment, 
					er.sendTo3rdParty, 
					er.thirdPartyEmailId, 
					curdate() as date,
					upj.transaction_id as transid, 
					prj.is_free,
					prj.product_id
				FROM
					dap_products p,
					dap_products_resources_jn prj,
					dap_email_resources er,
					dap_users u,
					dap_users_products_jn upj
				WHERE
					u.status = 'A'
					and u.opted_out = 'N'
					and prj.resource_id = er.id
					and prj.status = 'A'
					and prj.resource_type = 'E'
					and p.id = prj.product_id
					and p.status = 'A'
					and upj.user_id = u.id
					and upj.product_id = p.id
					and upj.status = 'A'
					and curdate() between upj.access_start_date and upj.access_end_date
					and ( datediff(curdate(), upj.access_end_date) = prj.start_day )
					";					
					
					
			
			$insert_sql = "insert into dap_mass_actions
								(actionType, actionKey, payload, status, priority)
							values 
								('EMAIL', :key, :payload, 'NEW', 2)
							";

			//logToFile($sql,LOG_DEBUG_DAP);
			foreach ($select_sql as $sql) {
				$select_stmt = $dap_dbh->prepare($sql);
				$insert_stmt = $dap_dbh->prepare($insert_sql);
	
				//execute select
				$select_stmt->execute();
				$results = $select_stmt->fetchAll(PDO::FETCH_ASSOC);
				//while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
				foreach ($results as $key => $row) {
					try {
						logToFile("(Dap_Cron.sendEmailResources() - Processing row.");
						/**
						NO LONGER NEED TO CHECK for free user / paid email
						if((($row["transid"] == "-2") || ($row["transid"] == "-1")) && (strtolower($row["is_free"]) != "y")) {
							//user is direct signup/admin not paid but resource is not free, skip this row
							logToFile("(Dap_Cron.sendEmailResources() - Email Resource is not free but user is not paid, skipping. Resource Subject: ".$row['subject'], LOG_INFO_DAP);
							continue;
						}
						*/
						$email = $row['email'];
						$firstname = $row['first_name'];
						$lastname = $row['last_name'];
						$subject = $row['subject'];
						$body = $row['message'];
						$attachment = $row['attachment'];
						$password = $row['password'];
						$sendTo3rdParty = $row['sendTo3rdParty'];
						$thirdPartyEmailId = $row['thirdPartyEmailId'];
						$key = "";
						$productId = $row['product_id'];
						
						//TODO transfer attachment into proper format
						
						if($sendTo3rdParty == "Y") {					
							$body = personalizeMessage(Dap_User::loadUserByEmail($email), $body); //first personalize with user's email id
							$subject = personalizeMessage(Dap_User::loadUserByEmail($email), $subject); //first personalize with user's email id
							$key = $row['id'] . ":" . $thirdPartyEmailId . "-" . $row['email'] . ":ER:" . $row['date'];
							$email = $thirdPartyEmailId; //Switch primary recipient to thirdPartyEmailId instead of actual user
						} else {
							$key = $row['id'] . ":" . $email . ":ER:" . $row['date'];
						}
						
						$payload = $email . "||" . $firstname . "||" . $lastname . "||" . $subject . "||" . $body . "||" . $attachment . "||" . $password . "||" . $productId;
						
						logToFile("(Dap_Cron.sendEmailResources() - Insert Payload:".$payload, LOG_INFO_DAP);
						$insert_stmt->bindParam(':payload', $payload, PDO::PARAM_STR);
						$insert_stmt->bindParam(':key', $key, PDO::PARAM_STR);
						$insert_stmt->execute();
					} catch (PDOException $e) {
						if( stristr($e->getMessage(), 'Integrity constraint violation') === FALSE ) {
							logToFile($e->getMessage(),LOG_FATAL_DAP);
						}
					} catch (Exception $e) {
						logToFile($e->getMessage(),LOG_FATAL_DAP);
					}
				} //end foreach results
			} //end foreach sql
			$select_stmt = null;
			$insert_stmt = null;
			$dap_dbh = null;
			
			return;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}

	public static function doesAttachmentsExist($attach_str) {
		if(isset($attach_str) && trim($attach_str) != "")  {
			logToFile("(Dap_Cron.doesAttachmentsExist() - Attachment str is not empty:*".$attach_str."*");
			//lets process attachments and make sure they are all readable and exists. If not, write error and continue with processing other EMAILs.
			$attachs = explode(",",$attach_str);
			//for each attachment
			foreach($attachs as $filename) {
				$filename = DAP_ROOT . '/admin/attachments/' . $filename;
				if(is_readable($filename)) {
					//do nothing
				} else {
					logToFile("(Dap_Cron.doesAttachmentExist() - Attachment does not exists:*".$filename."*");
					return false;
				}
			}

		}
		return true;
	}
	
	
	
	/*
		actionType = BE-QUERY
		Payload format BE-QUERY||SUBJECT||BODY||ATTACHMENTS
	*/
	public static function handleBEQuery() {
		logToFile("(Dap_Cron.handleBEQuery() - Method Init.");
		try {

			$dap_dbh = Dap_Connection::getConnection();
			$select_sql = "select
						id,
						actionType,
						actionKey,
						payload
					from
						dap_mass_actions
					where
						status in ('NEW', 'E') and
						actionType = 'BE-QUERY'
					";
			
			$update_sql = "update
							dap_mass_actions
							set
							status = :status,
							comments = :comments
							where
							id = :id
							";
			
			$insert_sql = "insert into dap_mass_actions
								(actionType, actionKey, payload, status, priority)
							values ('EMAIL', :key, :payload, 'NEW', 4)
							";

			//logToFile($sql,LOG_DEBUG_DAP);
			$select_stmt = $dap_dbh->prepare($select_sql);
			$update_stmt = $dap_dbh->prepare($update_sql);
			$insert_stmt = $dap_dbh->prepare($insert_sql);

			//execute select
			$select_stmt->execute();
			$results = $select_stmt->fetchAll(PDO::FETCH_ASSOC);
			foreach ($results as $key => $row) {
				//get payload
				$payload = $row['payload'];
				$parentkey = $row['actionKey'];
				//parse payload
				$tokens = explode("||",$payload);
				//Update row if error in payload
				if(count($tokens) < 4) {
					$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
					$update_stmt->bindValue(':status', 'E', PDO::PARAM_STR);
					$update_stmt->bindValue(':comments', 'Payload Format Is Not Correct', PDO::PARAM_STR);
					$update_stmt->execute();
					return;
				}
				//No error in payload - so continue
				//Get values first
				$sql = $tokens[0];
				$subject = $tokens[1];
				$body = $tokens[2];
				$attachments = $tokens[3];
				$productId = "";
				
				if(isset($tokens[4]) && $tokens[4] != "") {
					$productId = $tokens[4];
				}
				
				$dae = Dap_Cron::doesAttachmentsExist($attachments);
				if(!$dae) {//attachment variable has data, but no such file found
					$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
					$update_stmt->bindValue(':status', 'E', PDO::PARAM_STR);
					$update_stmt->bindValue(':comments', 'One or more attachments does not exist'.$data, PDO::PARAM_STR);
					$update_stmt->execute();
					// TODO : continue or return ?
					return;
				}
				//Process query
				$select_users_stmt = $dap_dbh->prepare($sql);
				$select_users_stmt->execute();
				$results2 = $select_users_stmt->fetchAll(PDO::FETCH_ASSOC);
				foreach ($results2 as $key => $user_row) {
					//lets loop thru all the user ids and create EMAIL transactions
				    logToFile("(Dap_Cron.handleBEQuery() - User Row Email:".$user_row['email'], LOG_INFO_DAP);
					$payload2 = $user_row['email'] . "||" . $user_row['first_name'] . "||" . $user_row['last_name'];

					$payload2 = $payload2 . "||" . $subject . "||" . $body . "||" . $attachments . "||" . $user_row['password'] . "||" . $productId;
					$key = $user_row['email'] . ":" . $parentkey;
					logToFile("(Dap_Cron.handleBEQuery() - Insert Payload:".$payload2, LOG_INFO_DAP);
					$insert_stmt->bindParam(':payload', $payload2, PDO::PARAM_STR);
					$insert_stmt->bindParam(':key', $key, PDO::PARAM_STR);
					try {
						$insert_stmt->execute();
					} catch (PDOException $e) {
						logToFile($e->getMessage(),LOG_FATAL_DAP);
					} catch (Exception $e) {
						logToFile($e->getMessage(),LOG_FATAL_DAP);
					}
				}

				//COMPLETED TASK SUCCESSFULLY
				$update_stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
				$update_stmt->bindValue(':status', 'C', PDO::PARAM_STR);
				$update_stmt->bindValue(':comments', 'Completed Successfully', PDO::PARAM_STR);
				$update_stmt->execute();
			}

			$select_stmt = null;
			$update_stmt = null;
			$insert_stmt = null;
			$dap_dbh = null;
			
			return;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}
	
/*
SELECT u.email, er.subject, er.message, er.attachment

FROM
	dap_products p,
	dap_products_resources_jn prj,
	dap_email_resources er,
	dap_users u,
	dap_users_products_jn upj
WHERE
	u.status = 'A'
	and prj.resource_id = er.id
 	and prj.status = 'A'
	and prj.resource_type = 'E'
	and p.id = prj.product_id
	and p.status = 'A'
	and upj.user_id = u.id
	and upj.product_id = p.id
	and upj.status = 'A'
	and curdate() between upj.access_start_date and upj.access_end_date
	-- and difference (in days) between today and start date is equal to prj.start_day
	and datediff(curdate(), upj.access_start_date) = prj.start_day
*/

	public static function flushLoginHistory() {
		try {
			$dap_dbh = Dap_Connection::getConnection();
			
			$howOften = Dap_Config::get("FLUSH_IP_HOW_OFTEN");
			logToFile("howOften: $howOften");
			if($howOften == "Never") return;
			
			$dayMapping = 
				array(
					"Week" => 7,
					"2 Weeks" => 14,
					"Month" => 30
				);
			
			$lastFlushStr = Dap_Config::get("LAST_FLUSH_DATE");
			$lastFlushDate = date("Y-m-d", strtotime($lastFlushStr)); //date of last flush
			
			$date = date("Y-m-d"); //today's date
			
			//date of next flush based on last flush
			$nextFlushWhen = date("Y-m-d", strtotime("+".$dayMapping[$howOften]." days", strtotime($lastFlushStr)));
			
			logToFile("lastFlushDate: $lastFlushDate , date: $date , nextFlushWhen: $nextFlushWhen");
			
			if($date >= $nextFlushWhen) { //today's date greater than next flush date?
				logToFile("Yes, time to flush login history");
				$sql = "TRUNCATE TABLE dap_users_logins";
				$stmt = $dap_dbh->prepare($sql);
				$stmt->execute();
				$stmt = null;
				
				//Now update last flushed date
				$sql = "update
							dap_config
						set
							value = CURDATE()
						where
							name = 'LAST_FLUSH_DATE'
						";
				$stmt = $dap_dbh->prepare($sql);
				$stmt->execute();				
				
				$stmt = null;
				$dap_dbh = null;
			} else {
				logToFile("No, not yet time to flush login history");
				return;
			}
			
			
		} catch (PDOException $e) {
			throw $e;
		} catch (Exception $e) {
			throw $e;
		}   
  	}
	
    /*
		Identify users whose access to product has expired, and take action based on admin settings for the product
	*/
	
	public static function expireUserProductAccess() {
		logToFile("(Dap_Cron.expireUserProductAccess() - Method Init.");
		

		if($_REQUEST["forcerun"]=="Y") 
		  $run=true;
		
		$cron_start_date_time = date( "Y-m-d H:i:s", strtotime( "today 10:00" ) );
		$cron_end_date_time = date( "Y-m-d H:i:s", strtotime( "today 10:59" ) );
		
		$now = date( "Y-m-d H:i:s", strtotime( "now" ) );
		
		if( ($run != true) && ((strtotime($now) < strtotime($cron_start_date_time))  || (strtotime($now) > strtotime($cron_end_date_time)))) {
		  logToFile("(Dap_Cron expireUserProductAccess():  Not Yet Time to Run Cron() - cron can start and end between : " . $cron_start_date_time . " and " . $cron_start_date_time);
		  return;												
		}
		else {
		  logToFile("(Dap_Cron expireUserProductAccess(): it's time to run the cron, now = " . $now);
		}
		
		try {

			$dap_dbh = Dap_Connection::getConnection();
			$select_sql = "SELECT u.id as user_id, u.email as email, curdate( ) AS date, 
						   upj.access_end_date AS access_end_date, 
						   upj.status,
						   upj.product_id as product_id
						   FROM dap_products p, dap_users u, dap_users_products_jn upj
						   WHERE upj.user_id = u.id
						   AND upj.product_id = p.id
						   AND datediff( curdate( ) , upj.access_end_date ) >0";
			
			// move forward the entire block (set access end date to previous date from current date)
			$update_sql = "
			update dap_products p, dap_users_products_jn upj, dap_users u
						   set upj.access_start_date=DATE( now() - INTERVAL (DATEDIFF( upj.access_end_date,upj.access_start_date) + 1) DAY ),
						   upj.access_end_date =  DATE ( now() - INTERVAL (1) DAY )
						   where upj.user_id = u.id 
						   AND upj.product_id = p.id 
						   AND product_id = :productId
						   AND datediff( curdate( ) , upj.access_end_date ) >0
						   AND datediff( curdate( ) , DATE( now() - INTERVAL (DATEDIFF( upj.access_end_date,upj.access_start_date) + 1) DAY ) ) !=  curdate( )";
						  
			$delete_sql = "DELETE from dap_users_products_jn where product_id in (
						   SELECT p.id
						   FROM dap_products p, dap_users u
						   WHERE user_id = u.id
						   AND product_id = p.id 
						   AND product_id = :productId
						   AND datediff( curdate( ) , access_end_date ) >0 )";		
			
			$select_remove_sql = "SELECT u.id as user_id, u.email as email, curdate( ) AS date, 
						   upj.access_end_date AS access_end_date, 
						   upj.status,
						   upj.product_id as product_id
						   FROM dap_products p, dap_users u, dap_users_products_jn upj
						   WHERE upj.user_id = u.id
						   AND upj.product_id = p.id 
						   AND product_id = :productId
						   AND p.access_expiration_action = 'REMOVEUSERPRODUCTFULLY'
						   AND datediff( curdate( ) , upj.access_end_date ) > 1";

			//logToFile($sql,LOG_DEBUG_DAP);
			$select_stmt = $dap_dbh->prepare($select_sql);
			$update_stmt = $dap_dbh->prepare($update_sql);
			$delete_stmt = $dap_dbh->prepare($delete_sql);
			
			$productsList = Dap_Product::loadProducts("", "A");
			
			foreach ($productsList as $product) {
			  if(($product->getAccessExpirationAction() == "NOACTION") || ($product->getAccessExpirationAction() == "")) {
			    logToFile("Dap_Cron::expireUserProductAccess: NOACTION : productName=" . $product->getName(),LOG_DEBUG_DAP);  
			    continue;
			  }
			  else if(($product->getAccessExpirationAction() == "EXPIREACCESS")) {
				  if(defined('EXPIREACCESS')){
					  if(EXPIREACCESS=="Y") {
						logToFile("Dap_Cron::expireUserProductAccess: EXPIREACCESS : productName=" . $product->getName(),LOG_DEBUG_DAP);  
						$update_stmt->bindParam(':productId', $product->getId(), PDO::PARAM_INT);
						$update_stmt->execute();  
					  }
				  }
			  }
			  else if(($product->getAccessExpirationAction() == "REMOVEUSERPRODUCTFULLY")) {
				logToFile("Dap_Cron::expireUserProductAccess: REMOVEUSERPRODUCTFULLY : productName=" . $product->getName(),LOG_DEBUG_DAP);    
				//$delete_stmt->bindParam(':productId', $product->getId(), PDO::PARAM_INT);
				//$delete_stmt->execute();  
				
				$stmt = $dap_dbh->prepare($select_remove_sql);
				$stmt->bindParam(':productId', $product->getId(), PDO::PARAM_INT);
				$stmt->execute();
				
				//lets loop over the resource list
				while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
					$user_id=$row["user_id"];
					$product_id=$row["product_id"];
					$complete=1; // fully delete userproduct row from dap and 3rd party
					logToFile("Dap_Cron::expireUserProductAccess: REMOVEUSERPRODUCTFULLY : userId=$user_id, productId=$product_id,$complete=1",LOG_DEBUG_DAP);    
					Dap_UsersProducts::removeUsersProducts($user_id, $product_id, -1, $complete);
				}
				
			  }
			}
			
			$select_stmt = null;
			$update_stmt = null;
			$delete_stmt = null;
			$dap_dbh = null;

			return;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}

	public static function cardExpirationEmail() {
		
		if($_REQUEST["forcerun"]=="Y") 
		  $run=true;
		
		$cron_start_date_time = date( "Y-m-d H:i:s", strtotime( "today 4:00" ) );
		$cron_end_date_time = date( "Y-m-d H:i:s", strtotime( "today 4:59" ) );
		
		$now = date( "Y-m-d H:i:s", strtotime( "now" ) );
		
		if( ($run != true) && ((strtotime($now) < strtotime($cron_start_date_time))  || (strtotime($now) > strtotime($cron_end_date_time)))) {
		  logToFile("(Dap_Cron cardExpirationEmail():  Not Yet Time to Run Cron() - cron can start and end between : " . $cron_start_date_time . " and " . $cron_start_date_time);
		  return;												
		}
		else {
		  logToFile("(Dap_Cron cardExpirationEmail(): it's time to run the cron, now = " . $now);
		}
		
		logToFile("(Dap_Cron.cardExpirationEmail() - Method Init.");
		try {
			$dap_dbh = Dap_Connection::getConnection();
							$cardExpirationEmailDays = Dap_Config::get("CARD_EXPIRATION_DAYS");
							//$cardExpirationEmailDays = 17;
							logToFile("Dap_Cron.sendCardExpirationEmail(): $cardExpirationEmailDays"); 
							if ($cardExpirationEmailDays != "") {
								logToFile("Dap_Cron.cardExpirationEmailDays() is set... look for eliglible users whose card is set to expire in $cardExpirationEmailDays days"); 
								
								$rows=Dap_Cron::findCardAboutToExpireForActiveSubs();
								logToFile("Dap_Cron.cardExpirationEmail: called findActiveSubscriptions()- check if  eligible users found", LOG_INFO_DAP);
								if(isset($rows)) {
									logToFile("Dap_Cron.cardExpirationEmail: found eligible users that require card expiration email", LOG_INFO_DAP);					
									$i=0;
									foreach ($rows as $row) {
										logToFile("Dap_Cron.cardExpirationEmail: called findActiveSubscriptions(), userid=".$row["uid"].", productId=".$row["pid"].", expdays=".$row['expdays'].", customid=".$row["customId"], LOG_INFO_DAP);
										$user = Dap_User::loadUserById($row['uid']);
										$product = Dap_Product::loadProduct($row["pid"]);
										$ret = sendCardExpirationEmail($user, $product);
										Dap_Cron::insertIntoJobQueue($user,$product,$ret,$row["expdays"],$row["id"],$row["act"],$row["customId"]);
										$i++;
										
									}
								}
							}
							
							
				} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}

	}

	public static function updateCustomField($user,$expdays,$customId) {
		
		$userId=$user->getId();
		$usercustom = new Dap_UserCustomFields();
		$usercustom->setUser_id($userId);
		$usercustom->setCustom_id($customId);
		
		$cf = Dap_UserCustomFields::loadUserCustomFieldsByCustomFieldId($customId, $userId );
		if ($cf) {
			//logToFile("Dap_Payment.class.php: updateCustomFields(): call update to update value=cart", LOG_DEBUG_DAP);
			foreach ($cf as $val) {
				$value= $val['custom_value'];
				$name=$val['name'];
				logToFile("Dap_Cron.class: updateCustomFields(): call update to update value=$value, name=".$name, LOG_DEBUG_DAP);
			}
			//$expdate=$expdays.":".$value;
			
			$value=$value.":".$expdays;
			logToFile("Dap_Cron.class: updateCustomFields(): call update to update expdate=$value", LOG_DEBUG_DAP);
			$usercustom->setCustom_value($value);
			$usercustom->update();
			logToFile("Dap_Cron.class: updateCustomFields(): update custom field complete!", LOG_DEBUG_DAP);
		}
		
		
		
	}
	
	public static function findCardAboutToExpireForActiveSubs() {
		
		try {

			$dap_dbh = Dap_Connection::getConnection();
			
			$select_sql = "select
 						u.id as uid,
						p.id as pid,
						p.name as pname,
						upj.transaction_id as transid,
						TO_DAYS(now()) as today,
						TO_DAYS(upj.access_start_date) as access_start_days,
						TO_DAYS(upj.access_end_date) as access_end_days
				from
					dap_products p,
					dap_users u,
					dap_users_products_jn upj				
				where
				    u.status = 'A' and 
					p.status = 'A' and 
                    p.is_recurring='Y' and
					upj.user_id = u.id and
					upj.product_id = p.id and
					upj.status = 'A' and 
					upj.transaction_id != 0 and
					datediff( curdate( ) , upj.access_end_date ) <=0";
			
			//logToFile($sql,LOG_DEBUG_DAP);
			$select_stmt = $dap_dbh->prepare($select_sql);
			
			//execute select
			$select_stmt->execute();
			$cardExpirationEmailDays = Dap_Config::get("CARD_EXPIRATION_DAYS");
			//$cardExpirationEmailDays=17;
			$customFld = Dap_CustomFields::loadCustomfieldsByName("cart");
			$customId=$customFld->getId();
			$customfieldlist=array();
			$rows=array();
			$found=false;
			$i=0;
			while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
				//get payload
				//logToFile("Dap_Cron.class: findCardAboutToExpireForActiveSubs: userid=" . $row["uid"] . ", customfieldId=" . $customId,LOG_DEBUG_DAP);
				
				$cfv = Dap_UserCustomFields::loadUserCustomFieldsByCustomFieldId($customId, $row["uid"] );
			
				$value = "";
				if ($cfv) {
				  foreach ($cfv as $val) {
					$name=$customFld->getName();
					$value= $val['custom_value'];
					$customfieldlist[$name] = $value;	
					//logToFile("DAP_API.class.php: findCardAboutToExpireForActiveSubs(): name=" . $name . ", val=" . $value);			  			
				  }
				}
				
				$today = date("Y-m-d", time());
				$expdate =  $value;
				//convert mmyyyy to 2013-10-15 format
				$newexpdate = substr($expdate,2,4)."-".substr($expdate,0,2)."-01";
				
			//	logToFile("DAP_API.class.php: findCardAboutToExpireForActiveSubs(): newexpdate=" . $newexpdate);
			//	logToFile("DAP_API.class.php: findCardAboutToExpireForActiveSubs(): today=" . $today);
				
				$asd = strtotime($today);
				$t = strtotime($newexpdate);
				
			//	logToFile("DAP_API.class.php: findCardAboutToExpireForActiveSubs(): today time=" . $asd);
			//	logToFile("DAP_API.class.php: findCardAboutToExpireForActiveSubs(): exp time=" . $t);	
				
				
				$numdays = round(($t - $asd)/(60*60*24));	
				
				
				
				logToFile("DAP_API.class.php: findCardAboutToExpireForActiveSubs():, numdays=" . $numdays . "uid=" . $row["uid"]);
			//	logToFile("DAP_API.class.php: findCardAboutToExpireForActiveSubs(): today=" . $today);	
				
				if($cardExpirationEmailDays > 0) {
					$expirationInDays = explode(",",$cardExpirationEmailDays);
					
					foreach ($expirationInDays as $expdays) {
						//logToFile("DAP_Cron.class.php: configured days: $expdays, userid=".$row["uid"]);
						//logToFile("DAP_Cron.class.php: configured days: $expdays, user current expdays=".$value.", configured exp days=".$expdays);
								  
						if($numdays==$expdays) { 
							$found=true;
							$user = Dap_User::loadUserById($row["uid"]);
							$result=Dap_Cron::checkJobQueue($user);
							if($result == -1) {
								logToFile("DAP_Cron.class.php: checkJobQueue() Returned true. Email already sent for $expdays.");
								continue;
							}
							
							
							/*if(strstr($value,":".$expdays)!=false) {
								logToFile("DAP_Cron.class.php: email already sent for $expdays reminder. Reminder will not be sent again.");
								continue;
							}*/
							
							logToFile("DAP_Cron.class.php: checkJobQueue() Returned " .$result);
							logToFile("DAP_Cron.class.php: findCardAboutToExpireForActiveSubs - credit card of user=" .$row["uid"]." set to expire in $expdays days.");	
							$rows[$i]["uid"]=$row["uid"];
							$rows[$i]["pid"]=$row["pid"];
							$rows[$i]["pname"]=$row["pname"];
							$rows[$i]["expdays"]=$expdays;
							$rows[$i]["customId"]=$customId;
							$rows[$i]["id"]=$result;
							
							if($result==-2)
								$rows[$i]["act"]="INSERT";
							else 
								$rows[$i]["act"]="UPDATE";
								
							$i++;
						}
					}
				}
				
			}
			
			$select_stmt = null;
			$dap_dbh = null;
			if($found) {
				logToFile("DAP_Cron.class.php: findCardAboutToExpireForActiveSubs - found eligible users");	
				return $rows;
				
			}
			else {
				logToFile("DAP_Cron.class.php: findCardAboutToExpireForActiveSubs - no eligible users");	
				return NULL;
			}
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}
	
	public static function checkJobQueue($user) {
		logToFile("(Dap_Cron.checkJobQueue() - Method Init.");
		
		try {

			$dap_dbh = Dap_Connection::getConnection();
			$email = $user->getEmail();
			$today = date("Y-m-d");
			
			//logToFile("(Dap_Cron.checkJobQueue() - Processing row.");
												
			$select_sql = "select
						id,
						last_update_ts,
						payload,
						actionKey,
						status
					from
						dap_mass_actions
					where
						status in ('SUCCESS', 'E') and
						actionType = 'EMAIL' and
						payload like '$email%' and
						last_update_ts >= '$today'";
						
			$select_stmt = $dap_dbh->prepare($select_sql);

			//$select_stmt->bindParam(':email', $email."%", PDO::PARAM_INT);
			//$select_stmt->bindValue(':time', $today."%", PDO::PARAM_STR);
			
			logToFile("(Dap_Cron.checkJobQueue() - $select_sql");
			logToFile("(Dap_Cron.checkJobQueue() - $email");
			logToFile("(Dap_Cron.checkJobQueue() - $today");
			
			try {
			  logToFile("(Dap_Cron.checkJobQueue() - Processing row.");
			  //execute select
			  $select_stmt->execute();
  				
			  while ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
				 
				  if($row["status"]=="E") {
					logToFile("(Dap_Cron.checkJobQueue() - earlier attempt to send failed for , id- " . $row["id"] );
				    return $row["id"]; // return id which is > 0 if error
				  }
				  else {
				    logToFile("(Dap_Cron.checkJobQueue() - already sent email for today, id- " . $row["id"] );
				    return -1; //return -1 if already found jobin success state
				  }
			  }
			
			  return -2;
					
			} catch (PDOException $e) {
				logToFile($e->getMessage(),LOG_FATAL_DAP);
				if( stristr($e->getMessage(), 'Integrity constraint violation') === FALSE ) {
					logToFile($e->getMessage(),LOG_FATAL_DAP);
				}
			} catch (Exception $e) {
					logToFile($e->getMessage(),LOG_FATAL_DAP);
			}
			
			return -2; //return -2 if job not found in queue,it will be a new insert into queue
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}
	
	
	public static function insertIntoJobQueue($user,$product,$ret,$numdays,$id,$act,$customId) {
		logToFile("(Dap_Cron.insertIntoJobQueue() - Method Init.");
		
		try {

			$dap_dbh = Dap_Connection::getConnection();
			
			$update_sql = "update
							dap_mass_actions
							set
							status = :status,
							actionKey=:key
							where
							id = :id";
							
			$update_stmt = $dap_dbh->prepare($update_sql);
			
			$insert_sql = "insert into dap_mass_actions
								(actionType, actionKey, payload, status, priority, comments)
							values ('EMAIL', :key, :payload, :status, 2, 'Card Expiration Reminder')
							";

			$insert_stmt = $dap_dbh->prepare($insert_sql);

			
			try {
			  logToFile("(Dap_Cron.insertIntoJobQueue() - Processing row.");
			  $userId=$user->getId();
			  $email = $user->getEmail();
			 
			  
			  if($act=="INSERT") {
				  logToFile("(Dap_Cron.insertIntoJobQueue() INSERT... $email");
				  $firstname = $user->getFirst_name();
				  $lastname = $user->getLast_name();
				  
				  if(isset($product))
					  $productId = $product->getId();
				  
				  $today = date("Y-m-d", time());
				   
				  if($ret != "") {
					$msg = $ret;
					$status="E";
				  }
				  else  {
					$msg="Card expiration email for day=" . $numdays . " sent on " . $today;
					$status="SUCCESS";
				  }
				 
				  $key = "UserId=".$userId . ":Email=" . $email . ":" . $msg;
				  $payload = $email . "||" . $firstname . "||" . $lastname . "||" . $productId . "||" . $today;
				  
				  logToFile("(Dap_Cron.insertIntoJobQueue() - Insert Key:".$key, LOG_INFO_DAP);
				  logToFile("(Dap_Cron.insertIntoJobQueue() - Insert Payload:".$payload, LOG_INFO_DAP);
																   
				  $insert_stmt->bindParam(':payload', $payload, PDO::PARAM_STR);
				  $insert_stmt->bindParam(':key', $key, PDO::PARAM_STR);
				  $insert_stmt->bindParam(':status', $status, PDO::PARAM_STR);
				  $insert_stmt->execute();
			  }
			  else {
				  logToFile("(Dap_Cron.insertIntoJobQueue() UPDATE... $email");
				    $today = date("Y-m-d", time());
				  if($ret != "") {
					$msg = $ret;
					$status="E";
				  }
				  else  {
					$msg="Card exp. email for day=" . $numdays . " sent on " . $today;
					$status="SUCCESS";
				  }
				  
				  logToFile("(Dap_Cron.insertIntoJobQueue() - update id=".$id, LOG_INFO_DAP);
				//  logToFile("(Dap_Cron.insertIntoJobQueue() - Insert Payload:".$payload, LOG_INFO_DAP);
 				  $key = "UserId=".$userId . ":Email=" . $email . ":" . $msg;												   
				  $update_stmt->bindParam(':id', $id, PDO::PARAM_STR);
				  $update_stmt->bindParam(':status', $status, PDO::PARAM_STR);
				  $update_stmt->bindParam(':key', $key, PDO::PARAM_STR);
				  $update_stmt->execute();
			  }
					
			} catch (PDOException $e) {
				logToFile($e->getMessage(),LOG_FATAL_DAP);
				if( stristr($e->getMessage(), 'Integrity constraint violation') === FALSE ) {
					logToFile($e->getMessage(),LOG_FATAL_DAP);
					return;
				}
			} catch (Exception $e) {
					logToFile($e->getMessage(),LOG_FATAL_DAP);
					return;
			}
			
			if($status=="SUCCESS") {
				Dap_Cron::updateCustomField($user,$numdays, $customId);
				logToFile("Dap_Cron.cardExpirationEmailDays() - updated custom field ($customId) with exp email sent day = " . $numdays, LOG_INFO_DAP);		
			}
			
			$insert_stmt = null;
			$update_stmt=null;
			$dap_dbh = null;

			return;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
	}


	public function didCronRun($description="dap-cron") {
		logToFile("(Dap_Cron.didCronRun() - Method Init.");
		
		try {

			$dap_dbh = Dap_Connection::getConnection();
			$select_sql = "select id, description, last_update_ts
							from
							dap_cron where description=:description
							";
		
			//logToFile($sql,LOG_DEBUG_DAP);
			$select_stmt = $dap_dbh->prepare($select_sql);
			$select_stmt->bindParam(':description', $description, PDO::PARAM_STR);
			$select_stmt->execute();
				
			if ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
				logToFile("(Dap_Cron.didCronRun() - last_update_ts=" . $row["last_update_ts"]);
				return $row["last_update_ts"];
			}
			
			$dap_dbh = null;

			return FALSE;
		} catch (PDOException $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			throw $e;
		}
		return FALSE;
	}
	
	public function saveCronRunStatus ($description="dap-cron") {
		logToFile("(Dap_Cron.insertIntoJobQueue() - Method Init.");
		
		try {

			$dap_dbh = Dap_Connection::getConnection();
			$select_sql = "select id, description, last_update_ts
							from
							dap_cron where description=:description
							";
			$update_sql = "update
							dap_cron
							set last_update_ts=CURRENT_TIMESTAMP
							where id=:id
							";
			$insert_sql = "insert into dap_cron
								(description)
							values (:description)
							";

			//logToFile($sql,LOG_DEBUG_DAP);
			$select_stmt = $dap_dbh->prepare($select_sql);
			$update_stmt = $dap_dbh->prepare($update_sql);
			$insert_stmt = $dap_dbh->prepare($insert_sql);
			
			$select_stmt->bindParam(':description', $description, PDO::PARAM_STR);
			$select_stmt->execute();
			
			if ($row = $select_stmt->fetch(PDO::FETCH_ASSOC)) {
				logToFile("(Dap_Cron.saveCronRunStatus() - update");
		
				$update_stmt->bindParam(':id', $row["id"], PDO::PARAM_STR);
				$update_stmt->execute();
			}
			else {
				$insert_stmt->bindParam(':description', $description, PDO::PARAM_STR);
				$insert_stmt->execute();
			}
			
			$insert_stmt = null;
			$update_stmt=null;
			$dap_dbh = null;

			return;
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