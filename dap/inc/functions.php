<?php

define('CHUNK_SIZE', 1024*1024); // Size (in bytes) of tiles chunk						
	
	function getFileContent($filename) {
		$blogpath = urldecode($_SESSION["blogpath"]);
		$showsidebar = urldecode($_SESSION["showsidebar"]);
		$sidebartemplate = urldecode($_SESSION["sidebartemplate"]);
		$pagetemplate = urldecode($_SESSION["pagetemplate"]);
		
		if($showsidebar == "YES") {
		  $template_path=$blogpath."/wp-content/plugins/dapsociallogin/includes/templates/" . $sidebartemplate . "/custom" . $filename;
		}
		else {
		  $template_path=$blogpath."/wp-content/plugins/dapsociallogin/includes/templates/" . $pagetemplate . "/custom" . $filename;
		}
		//logToFile("creditStore.inc: template_path NAME=" . $template_path);
		
		if(file_exists($template_path)) {
			
		  $daplogin_form_content = file_get_contents($template_path);
		}
		else {
		
		  if($showsidebar == "YES") {
			$template_path=$blogpath."/wp-content/plugins/dapsociallogin/includes/templates/" . $sidebartemplate .  "/" . $filename;
		  }
		  else {
			$template_path=$blogpath."/wp-content/plugins/dapsociallogin/includes/templates/" . $pagetemplate . "/" .  $filename;
		  }
		
		  $login_form_content = file_get_contents($template_path);
		}
		
		return $login_form_content;
	}
	
	
	function getTheWPFolderName($url)
	{
		$nowww = preg_replace('/www\./','',$url);
		$domain = parse_url($nowww);
		//logToFile("domain=" .$domain["host"],LOG_DEBUG_DAP);
		return $domain["path"];
	}
	
	function removeQueryStringDSH($current_url_with_query_string)
	{
	//logToFile("current_url_with_query_string=".$current_url_with_query_string,LOG_DEBUG_DAP);
	 
		if(strpos($current_url_with_query_string, '?') > 0) {
		  $current_url_without_query_string = substr($current_url_with_query_string, 0, strpos($current_url_with_query_string, '?')); // This line is the key
		  //logToFile("current_url_without_query_string=".$current_url_without_query_string,LOG_DEBUG_DAP);
		  return $current_url_without_query_string;
		}
		  
		return $current_url_with_query_string;
	}

	function getIpOfUser() {
		$ip = "";
		if (isset($_SERVER['HTTP_X_FORWARD_FOR'])) {
			$ip = $_SERVER['HTTP_X_FORWARD_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		logToFile("ip address: $ip",LOG_INFO_DAP);
		return $ip;
	}
	
	
	function processDAPCredits ($user) {
//	   logToFile("processdapcredits(): COOKIE VAL, ENTER=". $_COOKIE["cssocialmedia"]);	 
	 // if ($_COOKIE["cssocialmedia"] != '') { 
		$session = Dap_Session::getSession();
		$user = $session->getUser();
		$user = Dap_User::loadUserById($user->getId()); //reload User object
		
		$usercreditsperpost = new Dap_Credits();
		$usercreditsperpost->setUserid($user->getId());
		$credits_available_now = $user->getCredits_available();
		
		//Linkedin
		if ($_COOKIE["daplinkedincredits"] != '') {
		  $usercreditsperpost->setType("LINKEDIN");	
		  $creditsstring=$_COOKIE["daplinkedincredits"];
		  $credits_available_now=$credits_available_now+$daplinkedincredits;
		  //logToFile("processdapcredits(): COOKIE VAL, daplinkedincredits=". $_COOKIE["daplinkedincredits"]);	 
		  assignDAPCredits($user,$usercreditsperpost,$creditsstring,$credits_available_now,$creditsAquired);
		  setcookie("daplinkedincredits", '', time() - 3600, '/');	//expire cookie
		}
		if ($_COOKIE["dapgpluscredits"] != '') {
		  $usercreditsperpost->setType("GPLUS");	
		  $creditsstring=$_COOKIE["dapgpluscredits"];
		  $credits_available_now=$credits_available_now+$dapgpluscredits;
		  //logToFile("processdapcredits(): COOKIE VAL, dapgpluscredits=". $_COOKIE["dapgpluscredits"]);	 
		  assignDAPCredits($user,$usercreditsperpost,$creditsstring,$credits_available_now,$creditsAquired);
		  setcookie("dapgpluscredits", '', time() - 3600, '/');	//expire cookie
		} 
		if ($_COOKIE["daptwittercredits"] != '') {
		  $usercreditsperpost->setType("TWITTER");	
		  $creditsstring=$_COOKIE["daptwittercredits"];
		  $credits_available_now=$credits_available_now+$daptwittercredits;
		  //logToFile("processdapcredits(): COOKIE VAL, daptwittercredits=". $_COOKIE["daptwittercredits"]);	 
		  assignDAPCredits($user,$usercreditsperpost,$creditsstring,$credits_available_now,$creditsAquired);
		  setcookie("daptwittercredits", '', time() - 3600, '/');	//expire cookie
		} 
		if ($_COOKIE["dapfbcredits"] != '') {
		  $usercreditsperpost->setType("FACEBOOK");	
		  $creditsstring=$_COOKIE["dapfbcredits"];
		  $credits_available_now=$credits_available_now+$dapfbcredits;
		  //logToFile("processdapcredits(): COOKIE VAL, dapfbcredits=". $_COOKIE["dapfbcredits"]);	 
		  assignDAPCredits($user,$usercreditsperpost,$creditsstring,$credits_available_now,$creditsAquired);
		  setcookie("dapfbcredits", '', time() - 3600, '/');	//expire cookie
		} 
		
		//if ($_COOKIE["daplinkedincredits"] != '') {
//		 setcookie("cssocialmedia", '', time() - 3600, '/');	//expire cookie
	  //} //if ($_COOKIE["cssocialmedia"] != '') 
	  
	} //function


	function assignDAPCredits ($user,$usercreditsperpost,$creditsstring,$credits_available_now,$creditsAquired) {
	  $updateuser=false;
	  $cstype = $usercreditsperpost->getType();	
	  if($cstype == "FACEBOOK") $addorupdatefb="A";
	  if($cstype == "TWITTER") $addorupdatetwitter="A";
	  if($cstype == "LINKEDIN") $addorupdatelinkedin="A";
	  if($cstype == "GPLUS") $addorupdategplus="A";
	  
	  $used=0;
	  $fbused=0;
	  $twitterused=0;
	  $gplusused==0;
	  $linkedinused=0;
		  
	  $cscredits = explode("||", $creditsstring);
	  $count = count($cscredits);      
	  for($i=0;$i<$count;$i++){
		//logToFile("processdapcredits(): COUNT=". $count);	 
		//logToFile("processdapcredits(): i=" .  $i . ", cscredits=". $cscredits[$i]);
		
		$cscredit = explode("::", $cscredits[$i]);
		//logToFile("processdapcredits(): COUNT CREDITS=". count($cscredits));	 
		
		if(count($cscredit)) {
		  $socialmedia=	$cscredit[0];
		  $permalink = $cscredit[1];
		//  logToFile("processdapcredits(): COUNT   permalink=". $permalink );	 
		  $current_postid = $cscredit[2];
		  //logToFile("processdapcredits(): COUNT current_postid =". $current_postid);	 
		  $creditsAquired = $cscredit[3];      
		 // logToFile("processdapcredits(): COUNT creditsAquired =". $creditsAquired);	 
		  $usercreditsperpost->setPostid($current_postid);
		  $dapcreditsqueryresults = Dap_Credits::loadCreditsUsedByUserIdAndPostIdAndType($user->getId(),$current_postid,$socialmedia);
		  
	      $usercreditsperpost->setTitle($permalink);
		  foreach ($dapcreditsqueryresults as $result ) { 
			if($result) {
			  $type=$result->getType();
			  $currentCredits=$result->getCredits();
			  if($type=="FACEBOOK") {
				$fbused=$result->getUsed();
				$addorupdatefb="U";
			  }else if($type=="TWITTER") {
				$twitterused=$result->getUsed();
				$addorupdatetwitter="U";
			  }else if($type=="LINKEDIN") {
				$linkedinused=$result->getUsed();
				//logToFile("dapcredits found linkedinused=". $linkedinused);	
				$addorupdatelinkedin="U";
			  }else if($type=="GPLUS") {
				$gplusused=$result->getUsed();
				$addorupdategplus="U";
			  }
			  $used=$used+$result->getUsed();;
			}
		  } //end for
		  
		  if ($fbused >= $maxlikesallowedperbutton) {//for the post
			$givefbcredits="N";
		  }
		  if ($twitterused >= $maxlikesallowedperbutton) {//for the post
			$givetwittercredits="N";
		  }
		  if ($linkedinused >= $maxlikesallowedperbutton) {//for the post
			logToFile("linkedinused=". $linkedinused . " and maxallowred=" . $maxlikesallowedperbutton);	
			$givelinkedincredits="N";
		  }
		  if ($gplusused >= $maxlikesallowedperbutton) {//for the post
			$givegpluscredits="N";
		  }
		  
		  /*logToFile("functions_admin.php: processDAPCredits(): daplinkedincredits current_postid: " . $current_postid,LOG_DEBUG_DAP);
		  logToFile("functions_admin.php: processDAPCredits(): daplinkedincredits permalink: " . $permalink,LOG_DEBUG_DAP);
		  logToFile("functions_admin.php: processDAPCredits(): daplinkedincredits credits_available_now: " . $credits_available_now,LOG_DEBUG_DAP);
		  */
		  
		  if($addorupdatelinkedin=="A") {
			$updateuser=true;  
			$usercreditsperpost->setUsed(1);
			$usercreditsperpost->setCredits($creditsAquired);
			$usercreditsperpost->create();
			//logToFile("creditStore_submit.php: added daplinkedincredits credits=1",LOG_DEBUG_DAP);
		  } else if ($addorupdatelinkedin == "U") {
			$updateuser=true;  
			$usercreditsperpost->setUsed($linkedinused+1);
			
		//	logToFile("creditStore_submit.php: updated linkedinused, currentCredits=".$currentCredits,LOG_DEBUG_DAP);
	   //	logToFile("creditStore_submit.php: updated linkedinused, creditsAquired=".$creditsAquired,LOG_DEBUG_DAP);
			$usercreditsperpost->setCredits($creditsAquired+$currentCredits);
			$usercreditsperpost->update(); 
		//  logToFile("creditStore_submit.php: updated linkedinused=".$linkedinused,LOG_DEBUG_DAP);
		  }
		
		  if($addorupdatetwitter=="A") {
			  $updateuser=true;
			$usercreditsperpost->setUsed(1);
			$usercreditsperpost->setCredits($creditsAquired);
			$usercreditsperpost->create();
			//logToFile("creditStore_submit.php: added daptwittercredits credits=1",LOG_DEBUG_DAP);
		  } else if ($addorupdatetwitter == "U") {
			  $updateuser=true;
			$usercreditsperpost->setUsed($twitterused+1);
			//$currentCredits=$usercreditsperpost->getCredits();
			$usercreditsperpost->setCredits($creditsAquired+$currentCredits);
			$usercreditsperpost->update(); 
			//logToFile("creditStore_submit.php: updated twitterused=".$twitterused,LOG_DEBUG_DAP);
		  }
		  
		  if($addorupdatefb=="A") {
			  $updateuser=true;
			$usercreditsperpost->setUsed(1);
			$usercreditsperpost->setCredits($creditsAquired);
			$usercreditsperpost->create();
			//logToFile("creditStore_submit.php: added dapfbcredits credits=1",LOG_DEBUG_DAP);
		  } else if ($addorupdatefb == "U") {
			$updateuser=true;
			if($creditsAquired < 0) {
			   logToFile("functions.php: assignDAPCredits: dapfbcredits unlike event.. reduce earned credits: " . $dapfbcredits,LOG_DEBUG_DAP);
			   $usercreditsperpost->setUsed($fbused-1);
			   $usercreditsperpost->setCredits($currentCredits+$creditsAquired);
			   $newcredits=$currentCredits+$creditsAquired;
			   logToFile("functions.php: assignDAPCredits: dapfbcredits unlike complete, newcredits=" . $newcredits,LOG_DEBUG_DAP);
			   
			   setcookie("cssocialmedia" . $current_postid, '', time() - 3600, '/');	//expire cookie
		    }
			else {
				$usercreditsperpost->setUsed($fbinused+1);
				$usercreditsperpost->setCredits($creditsAquired+$currentCredits);
			}
			//$currentCredits=$usercreditsperpost->getCredits();
			
			$usercreditsperpost->update(); 
			//logToFile("creditStore_submit.php: updated fbinused=".$fbinused,LOG_DEBUG_DAP);
		  }
		  
		  if($addorupdategplus=="A") {
			  $updateuser=true;
			$usercreditsperpost->setUsed(1);
			$usercreditsperpost->setCredits($creditsAquired);
			$usercreditsperpost->create();
			//logToFile("creditStore_submit.php: added dapgpluscredits credits=1",LOG_DEBUG_DAP);
		  } else if ($addorupdategplus == "U")  {
			  $updateuser=true;
			$usercreditsperpost->setUsed($gplusused+1);
			//$currentCredits=$usercreditsperpost->getCredits();
			$usercreditsperpost->setCredits($creditsAquired+$currentCredits);
			$usercreditsperpost->update(); 
			//logToFile("creditStore_submit.php: updated gplusused=".$gplusused,LOG_DEBUG_DAP);
		  }
		  if($updateuser) {
			$credits_available_now = $credits_available_now + $creditsAquired;
		    $user->updateCredits($credits_available_now);
		  }
		}//each credit info - count(linkedincredit) > 0
	  } //for multiple credit updates in cookie (linkedincredits)
	}
		  
		  
	function validate($email, $user_password) {
		//This function expects plain-text password, as authenticateUser will encrypt it later
		$authenticated = false;
		logToFile("Validate email: ".$email);
		if ( ($email != "") && ($user_password != "") ) {
			$user = Dap_User::authenticateUser($email, $user_password);
			if(!empty($user)) {
				logToFile("Authenticated OK",LOG_DEBUG_DAP); 
				$authenticated = true;
				$session = Dap_Session::getSession();
				$user = Dap_User::loadUserByEmail($email);
				$session->setUser($user);
				$_SESSION['dap_session'] = $session;
				//for external systems - wordpress, etc
				$_SESSION['dap_first_name'] = $user->getFirst_name();
				$_SESSION['dap_email'] = $user->getEmail();
				//$_SESSION['dap_member_home_page'] = Dap_Config::get("MEMBER_HOME_PAGE");
				//$_SESSION['dap_password'] = $user->getPassword();
			}
		}
		$authenticatedString = ($authenticated) ? 'true' : 'false';
		logToFile("User: ".$email.", Authenticated: ".$authenticatedString,LOG_INFO_DAP);
		return $authenticated;
	}

	
	/**
		This function is for DAP admin to log in AS another user
		Expects email & password of DAP admin, as well as email of another user
	*/
	
	function validateAs($email, $user_password, $emailAs) {
		//This function expects plain-text password, as authenticateUser will encrypt it later
		$authenticated = false;
		logToFile("Validate email: ".$email);
		if ( ($email != "") && ($user_password != "") ) {
			$user = Dap_User::authenticateUser($email, $user_password);
			if(!empty($user)) {
				logToFile("DAP Admin Authenticated OK",LOG_DEBUG_DAP); 
				$authenticated = true;
				
				//Now log in the emailAs user
				$session = Dap_Session::getSession();
				$user = Dap_User::loadUserByEmail($emailAs);
				$session->setUser($user);
				$_SESSION['dap_session'] = $session;
				//for external systems - wordpress, etc
				$_SESSION['dap_first_name'] = $user->getFirst_name();
				$_SESSION['dap_email'] = $user->getEmail();
				//$_SESSION['dap_member_home_page'] = Dap_Config::get("MEMBER_HOME_PAGE");
				//$_SESSION['dap_password'] = $user->getPassword();
			}
		}
		$authenticatedString = ($authenticated) ? 'true' : 'false';
		logToFile("User: ".$email.", Authenticated: ".$authenticatedString,LOG_INFO_DAP);
		return $authenticated;
	}

	/**
		Authenticate the request url.
		Return TRUE if authorized.
		Return FALSE if not authorized.


		Note: This function need to return as fast as it could possibly.
			Return TRUE (authorized)  could possibly mean the resource is OPEN .
			Use this function ONLY to decide to serve up the url or not.
		NOTE: This method assumes relatively short session times and DOES NOT consider
			situations such as a resource becoming protected between the time it was OPEN and the time user access it.
			Basic data sync between session and database issues need to be resolved.
		NOTE: requestUrl should be cleanced enough not to create situations where user can craft it such that it can become array of entries.
	**/

	function isURLAllowed($requestUrl) {

		$ERROR_CODES = array(
							"DAP001" => "Sorry, you do not have access to this content.",
							"DAP002" => "Sorry, your access to this content has expired.",
							"DAP003" => "Sorry, this content is not yet available to you.",
							"DAP004" => "Sorry, your access to this content has expired.",
							"DAP005" => "Sorry, this is premium content available only to paid members.",
							"DAP006" => "Sorry, this is Members-Only content. Please login to access this..."
						);
		
		$lockImage = "/dap/images/lock.gif";
		
		if( file_exists($_SERVER['DOCUMENT_ROOT'] . "/dap/images/customlock.gif") ) {
			$lockImage = "/dap/images/customlock.gif";
		} else if( file_exists($_SERVER['DOCUMENT_ROOT'] . "/dap/images/customlock.jpg") ) {
			$lockImage = "/dap/images/customlock.jpg";
		}
		
		$TEXT_CODES = array(
							"DAP001" => "<img src='" . $lockImage . "' align='center'><h2>Sorry, you do not have access to this content.</h2>",
							"DAP002" => "<img align='center' src='" . $lockImage . "'><h2>Sorry, your access to this content has expired.</h2>",
							"DAP003" => "<img align='center' src='" . $lockImage . "'><h2>Sorry, this content is not yet available to you.</h2>",
							"DAP004" => "<img align='center' src='" . $lockImage . "'><h2>Sorry, your access to this content has expired.</h2>",
							"DAP005" => "<img align='center' src='" . $lockImage . "'><h2>Sorry, this is premium content available only to paid members.</h2>",
							"DAP006" => "<img align='center' src='" . $lockImage . "'><h2>Sorry, this is Members-Only content</h2>"
						);							

		$resource = new Dap_FileResource();
		$resource->setUrl($requestUrl);

		if(!isset($requestUrl)) {
			$requestUrl = $_SERVER['REQUEST_URI'];
			//logToFile("Request URI:".$requestUrl);
		}
	//	logToFile("--------------------------");
	//	logToFile("Request URI:".$requestUrl);
		//check if the requestUrl is part of already authenticated urls list from session.
		$cache = Dap_Config::get("ACCESS_CACHE_CONTROL_DB");
		//logToFile("DB Cache ?:".$cache);
		try {
			//if( ($cache == 'Y') && isUrlInSession($requestUrl) ) {
				//return $resource;
				//return TRUE;
			//}
			//if the url is not protected, then add it to session.
			if(!Dap_Resource::isResourceProtected($requestUrl)) {
				//addUrlToSession($requestUrl,TRUE);
				//logToFile("This URI is OPEN:".$requestUrl);
				return $resource;
				//return TRUE;
			}
		} catch (Exception $e) {
			logToFile($e->getMessage(),LOG_FATAL_DAP);
			$resource->setError_id("DAP004");
			$resource->addError($ERROR_CODES["DAP004"]);
			$resource->addError(getErrorPageByResource($requestUrl));
			$resource->addError($TEXT_CODES["DAP004"]);
			return $resource;
		}	
		//we are at stage where url is protected and we need to decide if we let this user access the resource.
		// Either user purchased a product and expired access
		//   Or user did not purchase the product
		//lets check purchased products to see if this request qualifies
		// NOTE:  For now we assume user id is  'uid' in session.
		$session = Dap_Session::getSession();
		$user = $session->getUser(); ////Dap_User::loadLoggedInUser();
		$uid = NULL;
		if(is_null($user)) {
		//	logToFile("user object is null"); 
			//Check if feed user
			if(defined('DAP_FEED_USER_ID')) {
				$uid = DAP_FEED_USER_ID;
			} else {
				//logToFile("No user found or User Is Not Logged In..");
				$resource->setError_id("DAP006");
				$resource->addError($ERROR_CODES["DAP006"]);
				$resource->addError(Dap_Config::get("LOGIN_URL"));
				$resource->addError($TEXT_CODES["DAP006"]);
				//$resource->addError(getErrorPageByResource($requestUrl));
				return $resource;
				//$_SESSION['ERROR'] = $ERROR_CODES["DAP006"];
				//$_SESSION['ERROR_URL'] = getErrorPageByResource($requestUrl);
				//return FALSE;
			}
		} else {
			$uid = $user->getId();
			//logToFile("uid: $uid"); 
		}
		//NOTE: the following query should result in only one resource. If we get multiple, just take first one.
		//implement simple logic to loop thru and give access if you can. Or route to last product error pages.
		$sql = "select
					upj.transaction_id as transid,
					TO_DAYS(now()) as today,
					TO_DAYS(upj.access_start_date) as access_start_days,
					TO_DAYS(upj.access_end_date) as access_end_days,
					prj.is_free as is_free,
					prj.start_day as start_day,
					prj.end_day as end_day,
					TO_DAYS(prj.start_date) as res_start_days,
					TO_DAYS(prj.end_date) as res_end_days,
					prj.num_clicks as num_clicks,
					prj.product_id as product_id,
					prj.resource_id as resource_id,
					prj.credits_assigned as credits_assigned,
					p.error_page_url as error_page_url
			from
				dap_products p,
				dap_products_resources_jn prj,
				dap_file_resources fr,
				dap_users u,
				dap_users_products_jn upj
			where
				u.id =:uid and
				u.status = 'A' and
				fr.url =:requesturl and
				prj.resource_id = fr.id and
				prj.resource_type = 'F' and
				p.id = prj.product_id and
				p.status = 'A' and
				upj.user_id = u.id and
				upj.product_id = p.id and
				upj.status = 'A' and
				(
					(
						prj.start_day != 0 and
						( (TO_DAYS(now()) - TO_DAYS(upj.access_start_date) + 1) >= prj.start_day ) and
						( (TO_DAYS(now()) - TO_DAYS(upj.access_end_date) + 1) <= prj.end_day)
					) 
					or
					(
						prj.start_date != 0 and
						( date(now()) >= prj.start_date )
					) 
					or
					(
						( date(now()) >= upj.access_start_date ) and
						( date(now()) <= upj.access_end_date )
					) 
				)
			order by
				upj.access_end_date desc,
				prj.end_day desc,
				access_end_days desc,
				prj.num_clicks asc
				";
			//	now() between upj.access_start_date and upj.access_end_date	and
			//	(TO_DAYS(NOW()) - TO_DAYS(access_start_date)) between prj.start_day and prj.end_day

		//echo "sql: $sql<br>"; exit;
		//logToFile("SQL:".$sql);
		$dap_dbh = Dap_Connection::getConnection();
		$stmt = $dap_dbh->prepare($sql);
		$stmt->bindParam(':uid', $uid, PDO::PARAM_INT);
		$stmt->bindParam(':requesturl', $requestUrl, PDO::PARAM_STR);
		$stmt->execute();
		//$result = mysql_query($sql) or trigger_error("Database Error",E_USER_WARNING);
		/*
			Single resource can be assigned to multiple products.
			We need to loop thru each product resource relationship and see if any row is eligible for access.

			We do series of checks to if url is blocked. If any test decides its blocked, we goto next item in loop.
			If none of the tests for a single row decides its blocked, then we break the loop and give access.

		*/
		$post_cancel_access = Dap_Config::get("POST_CANCEL_ACCESS");
		if(isset($post_cancel_access)) {
			$post_cancel_access = strtolower($post_cancel_access);
		}
		//$post_cancel_access = Dap_Config::
		while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

			//we have atleast one product that user signed up
			// NOTE: SIGNUP IS DIFFERENT FROM PAID.
			//logToFile("Today:".$row["today"]);
			//echo "<br>";
			//logToFile("Product Access Start Days:".$row["access_start_days"]);
			//echo "<br>";
			//logToFile("Product Access End Days:" . $row["access_end_days"]);
			//echo "<br>";
			//logToFile("Resource Start Day:" . $row["start_day"]);
			//echo "<br>";
			//logToFile("Resource End Day:" . $row["end_day"]);
			//echo "<br>";
			//logToFile("Resource Start (Date)DAYS:" . $row["res_start_days"]);
			//echo "<br>";
			//logToFile("Resource End (Date)DAYS:" . $row["res_end_days"]);
			//echo "<br>";
			//logToFile("Product Resource Num Clicks:" . $row["num_clicks"]);
			//echo "<br>";

			//product is SIGNUP only, then check if resource is free
			// -1 - direct sign up for free resources
			// -2 - admin sign up - for free resources
			// -3 - admin paid sign up - for all resources
			
			/** 
				09/12/2011: No longer matter if user is free or content is free.
				If user has access to the product, then he gets access to the content
				So commenting out if... section just below
			*/
			
			/**
			if((($row["transid"] == "-2") || ($row["transid"] == "-1")) && (strtolower($row["is_free"]) != "y")) {
				//logToFile("Not Free Resource, but User is FREE...");
				$resource->setError_id("DAP005");
				$resource->addError($ERROR_CODES["DAP005"]);
				$resource->addError( $row['error_page_url']);
				$resource->addError($TEXT_CODES["DAP005"]);
				continue;
				//return $resource;
				//$_SESSION['DAP_ERROR'] = $ERROR_CODES["DAP005"];
				//$_SESSION['DAP_ERROR_URL'] = $row['error_page_url'];
				//return FALSE;
			}
			*/

			//lets present two modes of operation 
			
			//logToFile("SSS:  uid:".$uid." product_id:".$row["product_id"].":resource_id:".$row["resource_id"].":credits_assigned:".$row["credits_assigned"]);
			$product = Dap_Product::loadProduct($row["product_id"]);
	  		
			if( (($row["credits_assigned"] != NULL) && ($row["credits_assigned"] > 0)) || ((isset($product) && ($product->getSelf_service_allowed() == "Y") && ($product->getIs_master() == "N"))) ){
				// check user credits
				//logToFile("SSS:  uid:".$uid." product_id:".$row["product_id"].":resource_id:".$row["resource_id"]);
				
				$usercred = Dap_UserCredits::loadCreditsPerResource($uid, $row["product_id"], $row["resource_id"]);
				
				$allowAccessToFutureContentAutomatically="N";
				if(isset($product)) {
					$allowAccessToFutureContentAutomatically=$product->getAllowAccessToFutureContent();
					//logToFile("functions.php:  allowAccessToFutureContentAutomatically=" . $allowAccessToFutureContentAutomatically);
				}
				if ( ($usercred == null) || ($usercred == "") ) {
					
					//check if user has access to full product
					//$userprodcred = Dap_UserCredits::loadCreditsPerProduct($uid, $row["product_id"]);
					/*if($userprodcred == null) {
					  logToFile("usercredit missing",LOG_DEBUG_DAP); 
					  $resource->setError_id("DAP001");
					  $resource->addError($ERROR_CODES["DAP001"]);
					  $resource->addError( $row['error_page_url']);
					  $resource->addError($TEXT_CODES["DAP001"]);
					  continue;	
					}*/
					
					// didnot find the resource in usercredits, 
					//check if the user purchased FULL product before and this content was added later to product
					//... if yes, then check if admin says give user access to future content automatically
					
					if($allowAccessToFutureContentAutomatically == "Y") {
						 $fullproductpurchasedbyuser = Dap_UserCredits::hasAccessTo($uid, $row["product_id"]);
						 if($fullproductpurchasedbyuser == false) {
							//logToFile("usercredit missing",LOG_DEBUG_DAP); 
							$resource->setError_id("DAP001");
							$resource->addError($ERROR_CODES["DAP001"]);
							$resource->addError( $row['error_page_url']);
							$resource->addError($TEXT_CODES["DAP001"]);
							continue;	
						}
						else {
							logToFile("Admin says allowAccessToFutureContentAutomatically, user purchased full product before...allowing access to future content automatically (content added after user purchased the full product before");
						}
					}
					else {
						//logToFile("usercredit missing",LOG_DEBUG_DAP); 
						$resource->setError_id("DAP001");
						$resource->addError($ERROR_CODES["DAP001"]);
						$resource->addError( $row['error_page_url']);
						$resource->addError($TEXT_CODES["DAP001"]);
						continue;	
					}
				}
			
			} else if($post_cancel_access == 'y') {
				//logToFile("Post cancel = Y"); 
				
				//Product did not launch yet.
				if($row["today"] < $row["access_start_days"]) {
					//logToFile("Product Start Date is in future...");
					$resource->setError_id("DAP001");
					$resource->addError($ERROR_CODES["DAP001"]);
					$resource->addError( $row['error_page_url']);
					$resource->addError($TEXT_CODES["DAP001"]);
					continue;
				}
				
				//logToFile("SSS:  uid:".$uid." product_id:".$row["product_id"].":resource_id:".$row["resource_id"]);
				// ADDED FOR SSS - 05/14/2011
				
				//logToFile("row[today]: " . $row["today"]); 
				
				//we have dates on the resource
				if($row["res_start_days"] <> 0 && $row["res_start_days"] <> "" &&
					$row["res_end_days"] <> 0 && $row["res_end_days"] <> "" ) {
					//set resource start days 
					$resource_start_days = $row["res_start_days"];
					$resource_end_days = $row["res_end_days"];
				}
							
				//we have days on the resource
				if($row["start_day"] <> 0 && $row["start_day"] <> "" &&
					$row["end_day"] <> 0 && $row["end_day"] <> "" ) {
					//set resource start days 
					$resource_start_days = $row["access_start_days"] + $row["start_day"] - 1;
					$resource_end_days = $row["access_start_days"] + $row["end_day"] - 1;

					//logToFile("We have 'days' on the resource"); 
					//logToFile("resource_start_days: $resource_start_days"); 
					//logToFile("resource_end_days: $resource_end_days"); 
					//logToFile("User's row[access_start_days]: " . $row["access_start_days"]); 

					/**
						So if resource "days" are hard-coded, but resource end day is in the past,
						then do not allow access. Will help offer expiring bonuses even though post-cancel-access is yes.
					*/
					if($row["today"] > $resource_end_days ) { //Expiring Bonuses
						//logToFile("Sorry: The 'end day' for this content is in the past...");
						$resource->setError_id("DAP001");
						$resource->addError($ERROR_CODES["DAP001"]);
						$resource->addError( $row['error_page_url']);
						$resource->addError($TEXT_CODES["DAP001"]);
						continue;
					}
				}
				//logToFile("Resource Start Days:".$resource_start_days);
				//logToFile("Resource End Days:".$resource_end_days);
				
				//if resource starts in future, lets not grant access.
				if($row["today"] < $resource_start_days ) {
					//logToFile("Resource Start Date is in future...");
					$resource->setError_id("DAP001");
					$resource->addError($ERROR_CODES["DAP001"]);
					$resource->addError( $row['error_page_url']);
					$resource->addError($TEXT_CODES["DAP001"]);
					continue;
				}					

				//
				// If resource ends before upj start date - then no acesss. This could only happen 
				//    when calendar dates are used at the resource level.
				// If resource starts after upj end date - then no access. This could only happen
				//    when calendar dates are used at the resource level.
				//
				//
				if( ($resource_end_days < $row["access_start_days"]) || ($resource_start_days > $row["access_end_days"]) ) {
					logToFile("Product Start Date is in future...");
					logToFile("Access Start Days:".$row["access_start_days"]);
					logToFile("Access End Days:".$row["access_end_days"]);
					$resource->setError_id("DAP005");
					$resource->addError($ERROR_CODES["DAP005"]);
					$resource->addError( $row['error_page_url']);
					$resource->addError($TEXT_CODES["DAP005"]);
					continue;						
				}				
			
			} else { //Post Cancel = "N"
				
				//Product did not lauch yet.
				if($row["today"] < $row["access_start_days"]) {
					//logToFile("Product Start Date is in future...");
					$resource->setError_id("DAP001");
					$resource->addError($ERROR_CODES["DAP001"]);
					$resource->addError( $row['error_page_url']);
					$resource->addError($TEXT_CODES["DAP001"]);
					continue;
					//return $resource;
					//$_SESSION['DAP_ERROR'] = $ERROR_CODES["DAP001"];
					//$_SESSION['ERROR_URL'] = $row['error_page_url'];
					//return FALSE;
				}
				//Product expired
				if($row["today"] > $row["access_end_days"]) {
					//logToFile("Product End Date is in past...");
					$resource->setError_id("DAP002");
					$resource->addError($ERROR_CODES["DAP002"]);
					$resource->addError( $row['error_page_url']);
					$resource->addError($TEXT_CODES["DAP002"]);
					continue;
					//return $resource;
					//$_SESSION['DAP_ERROR'] = $ERROR_CODES["DAP002"];
					//$_SESSION['DAP_ERROR_URL'] = $row['error_page_url'];
					//return FALSE;
				}
				//logToFile("SSS:  credits assigned:".$row["credits_assigned"]);
				
				//logToFile("SSS:  uid:".$uid." product_id:".$row["product_id"].":resource_id:".$row["resource_id"]);
				// ADDED FOR SSS - 05/14
				if(($row["credits_assigned"] != NULL) && ($row["credits_assigned"] > 0)) {
					// check user credits
					//logToFile("SSS:  uid:".$uid." product_id:".$row["product_id"].":resource_id:".$row["resource_id"]);
					logToFile("SSS: loadCreditsPerResource");
					$usercred = Dap_UserCredits::checkUserAccessToThisCreditsResource($uid, $row["product_id"], $row["resource_id"]);
			
					if (($usercred != null) && ($usercred != "")) {
						logToFile("usercredit row exists",LOG_DEBUG_DAP); 
						
					}
					else {
						//logToFile("usercredit missing",LOG_DEBUG_DAP); 
						$resource->setError_id("DAP001");
						$resource->addError($ERROR_CODES["DAP001"]);
						$resource->addError( $row['error_page_url']);
						$resource->addError($TEXT_CODES["DAP001"]);
						continue;	
					}
				} else {  //added upto here for SSS
				
					//product is available.
					//check start date(days).
					if($row["res_start_days"] <> 0 && $row["res_start_days"] <> "" &&
						$row["res_end_days"] <> 0 && $row["res_end_days"] <> "" ) {
		
						//resource is available in future.
						if($row["today"] < $row["res_start_days"]) {
							//logToFile("Resource Start Date  is in future...");
							$resource->setError_id("DAP003");
							$resource->addError($ERROR_CODES["DAP003"]);
							$resource->addError( $row['error_page_url']);
							$resource->addError($TEXT_CODES["DAP003"]);
							continue;
						}
						//resource  expired.
						if($row["today"] > $row["res_end_days"]) {
							//logToFile("Resource End Date  is in past...");
							$resource->setError_id("DAP004");
							$resource->addError($ERROR_CODES["DAP004"]);
							$resource->addError( $row['error_page_url']);
							$resource->addError($TEXT_CODES["DAP004"]);
							continue;
						}
		
					} //else {
						//logToFile("Start Days(Date) and End Days(Date) are empty or ZERO.. not checking...");
					//}
					//check start day
					$lag_days = $row["today"] - $row["access_start_days"] + 1;
					//check resource start and end day only if they are both non zero. 
					if($row["start_day"] <> 0 && $row["start_day"] <> "" &&
						$row["end_day"] <> 0 && $row["end_day"] <> "" ) {
		
						//resource is available in future.
						if($lag_days < $row["start_day"]) {
							$resource->setError_id("DAP003");
							$resource->addError($ERROR_CODES["DAP003"]);
							$resource->addError( $row['error_page_url']);
							$resource->addError($TEXT_CODES["DAP003"]);
							continue;
							//return $resource;
							//$_SESSION['DAP_ERROR'] = $ERROR_CODES["DAP003"];
							//$_SESSION['DAP_ERROR_URL'] = $row['error_page_url'];
							//return FALSE;
						}
						//resource availability expired.
						if($lag_days > $row["end_day"]) {
							//logToFile("Lag Days:".$lag_days);
							//logToFile("End Days:".$row["end_day"]);
							$resource->setError_id("DAP004");
							$resource->addError($ERROR_CODES["DAP004"]);
							$resource->addError( $row['error_page_url']);
							$resource->addError($TEXT_CODES["DAP004"]);
							continue;
							//return $resource;
							//$_SESSION['DAP_ERROR'] = $ERROR_CODES["DAP004"];
							//$_SESSION['DAP_ERROR_URL'] = $row['error_page_url'];
						//return FALSE;
						}
					} //else {
						//logToFile("Start Day and End Day are empty or ZERO.. not checking...");
					//}
					//resource is available in future.
				} /// SSS - end else
			}
			//grant access - we should reach here ONLY IF THE PRODUCT RESOURCE RELATIONSHIP IS CLEAN AND ALLOWED.
			
			/**
			try {
				//logToFile("About to call addUrlToSession()"); 
				//addUrlToSession($requestUrl, FALSE);
			} catch (Exception $e) {
				logToFile($e->getMessage(),LOG_FATAL_DAP);
				$resource->setError_id("DAP004");
				$resource->addError($ERROR_CODES["DAP004"]);
				$resource->addError(getErrorPageByResource($requestUrl));
				$resource->addError($TEXT_CODES["DAP004"]);
			}
			*/
			//reset any Resource errrors detected in previous loops and clear them before we return true.
			return $resource;
			//return TRUE;
		}


		//else {
			//we have some product that has this resource, but user did not sign up for it yet.
		//	$_SESSION['DAP_ERROR'] = $ERROR_CODES["DAP005"];
		//	$_SESSION['DAP_ERROR_URL'] = getErrorPageByResource($requestUrl); //$row['error_page_url'];
			//return FALSE;
		//}
		//now check if we have any error messages. If we do, that means we failed access check - no access.
		$errId = $resource->getError_id();
		//logToFile("FINAL: error:".$errId); 
		
		if(!isset($errId)) {
			//we have some product that has this resource, but user did not sign up for it yet.
			$resource->setError_id("DAP005");
			$resource->addError($ERROR_CODES["DAP005"]);
			$resource->addError(getErrorPageByResource($requestUrl));
			$res=getErrorPageByResource($requestUrl);
		//	logToFile("FINAL: res:".$res); 
			$resource->addError($TEXT_CODES["DAP005"]);
		}
		return $resource;
	}

	function isUrlInSession($requestUrl) {
		//check open url session array
		$urls = Dap_Session::get(SN_OPEN_URIS);
		if(isset($urls)) {
			//get array of authenticated urls
			$Authenticated_URLs = $urls;
			if (in_array($requestUrl, $Authenticated_URLs)) {
				//echo "URLAllowed from SESSION.<br>";
				//logToFile("Allow URL From Session:".$requestUrl);
				return TRUE;
			}
		}
		//check Authenticated urls
		$urls = Dap_Session::get(SN_AUTH_URIS);
		if(isset($urls)) {
			//get array of authenticated urls
			$Authenticated_URLs = $urls;
			if (in_array($requestUrl, $Authenticated_URLs)) {
				//echo "URLAllowed from SESSION.<br>";
				//logToFile("Allow URL From Session:".$requestUrl);
				//lets do the resource check only on auth urls
				$session = Dap_Session::getSession();
				$user = $session->getUser(); 
				//if($user != NULL && isset($user)) {
					//Dap_Resource::isResourceClickCountOK($user->getId(), $requestUrl);
				//}
				return true;
			}
		}		//
		return false;
	}

	/*
	This adds a given url to session cache.
	If $open is TRUE, then it adds to cache of open urls
	If $open is FALSE, then it adds to cache of protected urls. - THIS SECURITY SHOULD HAVE BEEN CHECKED ALREADY.

	*/
	function addUrlToSession($requestUrl,$open) {
		//logToFile("Adding URL To Session:".$requestUrl);
		if($open) {
			$urls = Dap_Session::get(SN_OPEN_URIS);
			if(isset($urls)) {
				$Open_URLs = $urls;
				array_push($Open_URLs,$requestUrl);
				Dap_Session::set(SN_OPEN_URIS,$Open_URLs);
			} else {
				$Open_URLs = array();
				array_push($Open_URLs,$requestUrl);
				Dap_Session::set(SN_OPEN_URIS,$Open_URLs);
			}
		} else {
			$urls = Dap_Session::get(SN_AUTH_URIS);
			if(isset($urls)) {
				$Authenticated_URLs = $urls;
				array_push($Authenticated_URLs,$requestUrl);
				Dap_Session::set(SN_AUTH_URIS,$Authenticated_URLs);
			} else {
				$Authenticated_URLs = array();
				array_push($Authenticated_URLs,$requestUrl);
				Dap_Session::set(SN_AUTH_URIS,$Authenticated_URLs);
			}
			//lets do the resource check only on auth urls
			//$session = Dap_Session::getSession();
			//$user = $session->getUser(); 
			//if($user != NULL && isset($user)) {
				//Dap_Resource::isResourceClickCountOK($user->getId(), $requestUrl);
			//}
			//TODO INCREMENT CLICK COUNT
			//Dap_UserResource::incClick_count($_SESSION[Dap_Config::getSession_user_id()]);
		}
	}




	/*
		Return a product error page given a resource url.
		Look up resource url into product_resource_jn and then lookup product to deduce the error page url.
		Always take only records that are Active (A)
		If nothing is found, then return site level error page.

	*/
	function getErrorPageByResource($requestUrl) {
		return Dap_Product::getErrorPageByResource($requestUrl);
	}


	// Read a file and display its content chunk by chunk
	if( !function_exists("readfile_chunked_dap") ) {
		function readfile_chunked_dap($filename, $retbytes = TRUE) {
			$buffer = '';
			$cnt =0;
			//$handle = fopen($filename, 'rb');
			$handle = fopen($filename, 'rb');
			if ($handle === false) {
			  return false;
			}
			while (!feof($handle)) {
				$buffer = fread($handle, CHUNK_SIZE);
				echo $buffer;
				ob_flush();
				flush();
				if ($retbytes) {
					$cnt += strlen($buffer);
				}
			}
			$status = fclose($handle);
			if ($retbytes && $status) {
			  return $cnt; // return num. bytes delivered like readfile() does.
			}
			return $status;
		}
	}


	/*
	//Serve the file requested.
	// If the file type if php, then include it.
	*/


	function serveFile($filename) {
		$filename = trim($filename, '/');
		$filename = DAP_ROOT . "../" . $filename;
	 	logToFile("Serving...*".$filename."*");
	 	logToFile("Directory Of Request...*".dirname($filename)."*");
		$cwdname = dirname($filename);
		if (!file_exists($filename)) {
	 		logToFile("File Not Found..*".$filename."*", LOG_INFO_DAP);
			header('Status: 404 Not Found1');
			header('HTTP/1.0 404 Not Found1');
			return; //die();
			//die("NO FILE HERE");
		}

		$ctype = getMimeType($filename);

		switch ($ctype) {
			//executable php
			case "php":
				$wd_was = getcwd();
				foreach ($_REQUEST as $key => $value) {
					logToFile( "Request:$key => $value" );
				}
				logToFile("Changing Directory To: $cwdname");
				if(chdir($cwdname) === TRUE) {
					logToFile("File Dir Is:".dirname(__FILE__));
					logToFile("Successfully Changed The Dir.".getcwd());
				}
				logToFile("Including Php File: ".basename($filename));
				logToFile("Including Php File: ".$filename);

				//need to set some variables such as php_self as otherwise they will be set to the dapclient php
				$_SERVER['PHP_SELF'] = $_GET['dapref'];
				#include basename($filename);
				//include $absolute_file;
				include $filename;
				chdir($wd_was);
				return;
				break;
		}

		header("Content-Type: $ctype");
		header("Content-Length: ".@filesize($filename));
		if( !ini_get('safe_mode') ){
        	@set_time_limit(300);
        } 
		//set_time_limit(0);
		//@readfile("$filename") or die("File not found.");
		@readfile_chunked_dap($filename);
		flush();
    	ob_flush();
	}

	function dapDebug($msg) {
		if(isset($_SESSION['log'])) {
			$log = $_SESSION['log'];
			array_push($log,$msg);
			$_SESSION['log'] = $log;
		} else {
			//$log = array();
			//unset($_SESSION['log']);
			$log = array();
			$_SESSION['log'] = $log;
			logToFile("Init Log.");
		}
	}

	function replaceFillers($fileName, $orig, $dest) {
		$data = file_get_contents($fileName);
		$data = str_replace ($orig, $dest, $data);
		return $data;
	} // End replaceFillers()

	function dapErrorHandler($errnum,$errmsg,$file,$lineno){
	  if($errnum==E_USER_WARNING){
	    echo 'Error: '.$errmsg.'<br />File: '.$file.'<br />Line: '.$lineno;
		//logToFile("createUser:".$sql.":".mysql_error());
	    exit();
	  }
	}

	//get just the resource for dap from a string. String could be just the request_uri or it could be full url
	function getResourceFromString($url) {
		$url = @parse_url($url, PHP_URL_PATH);
		//echo $url;
		$url = rawurldecode($url);
		//echo $url;
		$parse_vars = explode("&",$url);
		//parse_str($url, $parse_vars);
		//print_r($parse_vars);
		//$keys = array_keys($parse_vars);
		//$url = $keys[0];
		$url = $parse_vars[0];
		$url = trim($url, "/");
		$url = trim($url, "_");
		$url = trim($url, " ");
		$url = "/".$url;
		return $url;
	}
	
	function getResourceFromUserRequest() {
		$url = $_SERVER['REQUEST_URI'];
		$file = $_SERVER['SCRIPT_FILENAME'];
		$path_info = $_SERVER['PATH_INFO'];
		$req_uri = $_SERVER['REQUEST_URI'];
		//$req_uri_array = explode('?', $req_uri);
		//$req_uri = $req_uri_array[0];
		//decode the request uri
		//$req_uri = rawurldecode($req_uri);
		//
		//parse_str($req_uri, $parse_vars);
		//print_r($parse_vars);
		//$keys = array_keys($parse_vars);
		//$url = $keys[0];
		//$url = trim($url, "/");
		$url = getResourceFromString($req_uri);
		logToFile("Request Coming In: URI:".$url);
		logToFile("Request Coming In: File:".$file);
		logToFile("Dap Ref:".$_GET['dapref']);
		logToFile("Path Info:".$path_info);
		// build the URL in the address bar
		//$requested_url  = ( isset($_SERVER['HTTPS'] ) && strtolower($_SERVER['HTTPS']) == 'on' ) ? 'https://' : 'http://';
		//$requested_url .= $_SERVER['HTTP_HOST'];
		//$requested_url .= $_SERVER['REQUEST_URI'];
		//logToFile("Request_URL:".$requested_url);
		//$original = @parse_url($requested_url);
		//$url = $original['path'];
		return $url;
	}
	
	function add_include_path ($path)
	{
		foreach (func_get_args() AS $path)
		{
			if (!file_exists($path) OR (file_exists($path) && filetype($path) !== 'dir'))
			{
				trigger_error("Include path '{$path}' not exists", E_USER_WARNING);
				continue;
			}
	
			$paths = explode(PATH_SEPARATOR, get_include_path());
	
			if (array_search($path, $paths) === false)
				array_push($paths, $path);
	
			set_include_path(implode(PATH_SEPARATOR, $paths));
		}
	}
	
	function remove_include_path ($path)
	{
		foreach (func_get_args() AS $path)
		{
			$paths = explode(PATH_SEPARATOR, get_include_path());
	
			if (($k = array_search($path, $paths)) !== false)
				unset($paths[$k]);
			else
				continue;
	
			if (!count($paths))
			{
				trigger_error("Include path '{$path}' can not be removed because it is the only", E_USER_NOTICE);
				continue;
			}
	
			set_include_path(implode(PATH_SEPARATOR, $paths));
		}
	}


	function validateLogins($email, $ip) {
		return Dap_User::authenticateLogins($email, $ip);
	}
	
	function pluginLogin($email,$password="",$rememberMe=false) {
		$includeList = array();
		//logToFile( "functions.php : ENTER pluginLogin()" );
		registeredPlugins($includeList, $email, "login");
		//logToFile( "functions.php : called  registeredPlugins()" );
		$lldocroot = defined('SITEROOT') ? SITEROOT : $_SERVER['DOCUMENT_ROOT'];
		
		foreach ($includeList as $key => $value) {
			logToFile( "functions.php : pluginLogin(): " . $key . "=" . $value );
			
			if (stristr($value, "dap_xenapi")) {
				//require_once($lldocroot.'/dap/plugins/dap_xenapi/XenForoSDK.php');
				$filename = $lldocroot . '/dap/plugins/dap_xenapi/XenForoSDK.php';
		
				require_once($filename);
			//	logToFile( "functions.php : pluginLogin(): included xenforosdk.php");
				$sdk = new XenForoSDK;
				$rememberMe=true;
				//logToFile( "functions.php : pluginLogin(): xenforo: validateLogin(): $email $password");
				$xenforouser = $sdk->validateLogin($email,$password, $rememberMe, true);
				
				if(!isset($xenforouser) || ($xenforouser=="")) {
					logToFile( "functions.php : pluginLogin(): xenforo login failed for user=" . $email);
				}
				else {
				// Login user
					$user = $sdk->login($xenforouser, $rememberMe); // no validation
					//logToFile( "functions.php : pluginLogin(): xenforo login SUCCESSFUL for user=" . $email);
				}

			}
			else if (stristr($value, "vbulletin")) {
			  include_once ($value);
			  $forum = new Dap_Vbulletin();
			  $errmsg = $forum->login($email,$password);
			}
		}
	}
	
	
	function pluginLogout() {
		$includeList = array();
		$email="";
		registeredPlugins($includeList, $email, "logout");
		$lldocroot = defined('SITEROOT') ? SITEROOT : $_SERVER['DOCUMENT_ROOT'];
		foreach ($includeList as $key => $value) {
			
			logToFile( "functions.php : pluginLogout(): " . $key . "=" . $value );
			
			if (stristr($value, "dap_xenapi")) {
				//require_once($lldocroot.'/dap/plugins/dap_xenapi/XenForoSDK.php');
				$filename = $lldocroot . '/dap/plugins/dap_xenapi/XenForoSDK.php';
				require_once($filename);
			//	logToFile( "functions.php : pluginLogin(): included xenforosdk.php");
				$sdk = new XenForoSDK;
				$sdk->logout();
			}
			else if (stristr($value, "vbulletin")) {
				include_once ($value);
				$forum = new Dap_Vbulletin();
				$errmsg = $forum->logout();
			}
		}
	}
	
	function registeredPlugins (&$includeList, $email, $action, &$product="") {
		// get the list of all plugins to include based on actiontype (login/add/remove/update)
		
		//logToFile( "functions.php : registeredPlugins(): ENTER");
				
		if(defined("VBFORUMPATH")) {
			$lldocroot = defined('SITEROOT') ? SITEROOT : $_SERVER['DOCUMENT_ROOT'];
			$filename = $lldocroot . '/dap/plugins/vbulletin/Dap_Vbulletin.class.php';
			if (file_exists($filename)) {
					$includeList[] = 	$filename;
			}
		}
		
		$lldocroot = defined('SITEROOT') ? SITEROOT : $_SERVER['DOCUMENT_ROOT'];
		$filename = $lldocroot . '/dap/plugins/dap_xenapi/dap_xenapi.class.php';
		
		if(defined("XENFOROFORUMPATH") && ( ($action=="login") || ($action=="logout") ) ) {
			//logToFile( "functions.php : registeredPlugins(): added xenforo to login list");
			$includeList[] = 	$filename;
		}
	
		$subscribeList = "";
		if ($product != "") {
			if ($action == "Add") {
				$subscribeList = trim($product->getSubscribeTo());
			}
		 	else if ($action == "Remove") {
				$subscribeList = trim($product->getUnsubscribeFrom());
			}
			
		}
		
		if($subscribeList == "") return;
			
		$subscribtions = explode(",",$subscribeList);
		foreach ($subscribtions as $subscribeTo) {
			//if (file_exists($filename)) {
					$includeList[] = $subscribeTo;
			//		$path[] = $filename;
			//}
		}
		
		foreach ($includeList as $include) {
				logToFile( "functions.php : registeredPlugins() Plugin Name = : " . $include );
			//	logToFile( "functions.php : registeredPlugins() Plugin Name = : " . $filename );
		}
	}
	
	
	function displayTemplate($name) {
      //$data = Dap_Templates::getContentByName("ACTIVATION_EMAIL_CONTENT");
      $data = Dap_Templates::getContentByName($name);
      echo $data;
  	}
	
	
	function removeSlashesDAP($item) { 
		if (get_magic_quotes_gpc() == 0) return($item);
		if (is_array($item)) {
			while(list($k,$v) = each($item)) {
				$item[$k]= removeSlashesDAP($v);
			}
		} else {
			if (is_string($item)) {
				$item= stripslashes($item);
			}
		}
		return($item);
	}  
	
	
	function registerUser($source, $autoLogin="Y", $productId) {
	  
	  logToFile("email=".$_POST["email_address"]);
	  
	  if($_REQUEST["daplistbuildercredits"]=="Y") 
	    $daplistbuildercredits="Y";
		
	  //Since DAP v4.3: First check if product is even eligible for free signup
	  foreach ($_REQUEST as $key => $value) {
		  if($daplistbuildercredits=="Y") {
		  	$value = urldecode(stripslashes($value));
			if($value=="undefined")$value="";
		  }
		  //$req .= "&$key=$value";
		  $_POST[$key] = $value;
		  logToFile($source . ": " . $key . "=" . $value);
	  }
	    
	  
	  $product = Dap_Product::loadProduct($productId);
	  if( !isset($product) || ($product == NULL) || ($product->getStatus() == "I") ) {
		  if($daplistbuildercredits=="Y") {
		    //echo MSG_INVALID_PRODUCT;
			return MSG_INVALID_PRODUCT;
		  }
		  header("Location:error.php?msg=MSG_INVALID_PRODUCT");
		  exit;
	  }
	  $allow_free_signup = $product->getAllow_free_signup();
	  
	  if( is_null($allow_free_signup) || ($allow_free_signup == "") ) {
		  $allow_free_signup = "N";
	  }
	  if($allow_free_signup == "N") {
		  if($daplistbuildercredits=="Y") {
		    //echo MSG_INVALID_PRODUCT;
			return MSG_INVALID_PRODUCT;
		  }
		  header("Location:error.php?msg=MSG_INVALID_PRODUCT");
		  exit;
	  }
	  //End v4.3 allow_free_signup check
	  
	  
	  /*
		  Three possible use cases.
		  1) New user (so new product association)
		  2) Existing member, new product
		  3) Existing member, old product (don't extend)
	  */
  
	  //Core "User" data
	  
	  logToFile($source . ": PRODUCTID = " . $productId);
	  
	  
	  
	  $first_name = "";
	  $last_name = "";
	  $email = "";
	  
	  if( isset($_POST['email']) && ($_POST['email'] != "")) {
		$email = $_POST["email"];
	  }
	  else if( isset($_POST['email_address']) && ($_POST['email_address'] != "")) {
		$_POST["email"] = $_POST["email_address"];
		$email = $_POST["email_address"];
	  }
	  
	  if( (isset($_POST['first_name'])) && ($_POST['first_name'] != "")) {
		  $first_name =  addslashes($_POST['first_name']);
	  } else if( (isset($_POST['name'])) && ($_POST['name'] != "")) {
		  $pieces = explode("+",addslashes($_POST['name']));
		  $first_name = $pieces[0];
		  $last_name = $pieces[1];
	  } else if( (isset($_POST['requireUsername'])) && ($_POST['requireUsername'] != "")) {
		  $first_name =  addslashes($_POST['user_name']);
	  } else {
		  $pieces = explode("@",$_POST['email']);
		  $first_name = $pieces[0];
	  }
	  
	  logToFile("email in signup_submit.php: " . $email); 
  
	  if( ($last_name == "") && isset($_POST['last_name']) ) {
		  $last_name = addslashes($_POST['last_name']);
	  }
	  
	  logToFile("first_name in signup_submit.php: " . $first_name); 
	  logToFile("last_name in signup_submit.php: " . $last_name); 
  
	  $user_name = isset($_POST['user_name']) ? addslashes($_POST['user_name']) : "";
	  $password = isset($_POST['password']) ? addslashes($_POST['password']) : "";
	  $address1 = isset($_POST['address1']) ? addslashes($_POST['address1']) : "";
	  $address2 = isset($_POST['address2']) ? addslashes($_POST['address2']) : "";
	  $zip = isset($_POST['zip']) ? addslashes($_POST['zip']) : "";
	  
	  //arpreach
	  $address1 = isset($_POST['address_1']) ? addslashes($_POST['address_1']) : $address1;
	  $address2 = isset($_POST['address_2']) ? addslashes($_POST['address_2']) : $address2;
	  $zip = isset($_POST['postal_code']) ? addslashes($_POST['postal_code']) : $zip;
	  
	  
	  
	  $city = isset($_POST['city']) ? addslashes($_POST['city']) : "";
	  $state = isset($_POST['state']) ? addslashes($_POST['state']) : "";
	  
	  $country = isset($_POST['country']) ? addslashes($_POST['country']) : "";
	  $phone = isset($_POST['phone']) ? addslashes($_POST['phone']) : "";
	  $fax = isset($_POST['fax']) ? addslashes($_POST['fax']) : "";
	  $company = isset($_POST['company']) ? addslashes($_POST['company']) : "";
	  $title = isset($_POST['title']) ? addslashes($_POST['title']) : "";
	  $paypal_email = isset($_POST['paypal_email']) ? addslashes($_POST['paypal_email']) : "";
	  
	  //logToFile("signup_submit.php:  paypal_email=". $paypal_email, LOG_FATAL_DAP);
	  
	  //Non-user data
	  if (isset($_POST['coupon_code'])) {
		  if ($_POST['coupon_code'] == "") {
			  if($daplistbuildercredits=="Y") {
				//echo MSG_MISSING_COUPON;
				return MSG_MISSING_COUPON;
			  }
			  header("Location:error.php?msg=MSG_MISSING_COUPON");
			  exit;	
		  }
	  }
	  
	  $coupon_code = isset($_POST['coupon_code']) ? addslashes($_POST['coupon_code']) : "";
						  
	  if( $coupon_code != "" ) {
		  if (validateCouponCode($productId, $coupon_code) == false ) {
			  if($daplistbuildercredits=="Y") {
				//echo MSG_INVALID_COUPON;
				return MSG_INVALID_COUPON;
			  }
			  header("Location:error.php?msg=MSG_INVALID_COUPON");
			  exit;
		  } else {
			  if (!updateUsage($coupon_code)) {
				  logToFile("signup_submit.php: Failed to update coupon actual usage of coupon code: $coupon_code",LOG_FATAL_DAP);
			  }
		  }
		  
		  $coupon = Dap_Coupon::loadCouponByCode($coupon_code); //loads coupon details from db
		  $couponId = $coupon->getId();
	  }	
	  
	  
	  $ipaddress = getIpOfUser();
	  $thirdParty = true;
	  $redirect = isset($_POST['redirect']) ? addslashes($_POST['redirect']) : "";
	  if($redirect == "") {
		  $redirect = Dap_Config::get("LOGIN_URL");
		  $thirdParty = false;
	  }
	  
	  logToFile($source . ": redirect=" . $redirect,LOG_FATAL_DAP);
	   
	  logToFile("signup_submit.php:call requireUsername=Y",LOG_DEBUG_DAP);
	  
	  $redirURL = "";
	  $msg = "SUCCESS_CREATION";
	  $user = null;
	  
	  //Basic validation
	  if( ($first_name == "") || 
		  ($email == "") || 
		  ($productId == "") || 
		  strpos($_POST['email'], "\r") || 
		  strpos($_POST['email'], "\n") || 
		  ( isset($_POST['user_name']) && ($_POST['user_name'] == "") && ($_POST['requireUsername'] == "Y") )
	  ) {
		  if($daplistbuildercredits=="Y") {
			  //echo MSG_MANDATORY;
			  return MSG_MANDATORY;
		  }
		  header("Location:error.php?msg=MSG_MANDATORY");
		  exit;
	  }
	  
	  if( ($first_name == "Your First Name") || 
		  ($email == "Your E-mail Address") ) {
		  if($daplistbuildercredits=="Y") {
			  //echo MSG_MANDATORY;
			  return MSG_MANDATORY;
		  }
	  }
	  
	  $email = $email;
	  logToFile($source . ": email=" . $email,LOG_FATAL_DAP);
	  
	  if( validateEmailFormat($email) === false ) {
		  logToFile("signup_submit.php:call validateEmailFormat()",LOG_FATAL_DAP);
		  if($daplistbuildercredits=="Y") {
			  //echo MSG_INVALID_EMAIL;
			  return MSG_INVALID_EMAIL;
		  }
		  header("Location:error.php?msg=MSG_INVALID_EMAIL");
		  exit;
	  }
	  
	  if($password != "") {
		  if( !preg_match("/^[a-zA-Z0-9]+$/", $password) ) {
			  if($daplistbuildercredits=="Y") {
			  	//echo MSG_INVALID_EMAIL;
			  	return MSG_INVALID_EMAIL;
		  	  }
			  header("Location:error.php?msg=MSG_INVALID_PASS");
			  exit;
		  }
	  }
	  
	  
	   logToFile("signup_submit.php:call USERNAME=" . $_REQUEST["user_name"],LOG_DEBUG_DAP);
	  if( isset($_REQUEST["user_name"]) && ($_REQUEST["user_name"]!="") && ($_REQUEST["requireUsername"] == "Y")) {
		   logToFile("signup_submit.php:call requireUsername=Y",LOG_DEBUG_DAP);
		   $user = Dap_User::loadUserByUsername($email,$_REQUEST["user_name"]);
		   if($user) {
				header("Location:error.php?msg=MSG_USERNAME_TAKEN");
				exit;
		   }
	  }
	  //logToFile("About to loadUserByEmail"); 
	  $user = Dap_User::loadUserByEmail($email);
	  
	  /**
	  	If user status is Inactive, or user/product status is Inactive
	  	then DO NOT proceed. Enough to check for user/product inactive
	  	because user cannot be inactive when userproduct is active. And
		when activation link is clicked, it will also activate user
		status. So checking for one is sufficient.
	  */
	  $skipAdd=false;
	  if ( isset($user) ) {
	  	  $userProduct = Dap_UsersProducts::load($user->getId(), $productId);
		  if(($userProduct) && ($userProduct->getStatus() == "I")) {
			  logToFile("UserProduct status is Inactive"); 
			  //Resend activation email
			  sendUserProductActivationEmail($user, $productId);
			   if($daplistbuildercredits=="Y") {
			  	//echo MSG_INVALID_EMAIL;
			  	return MSG_ALREADY_INACTIVE;
		  	  }
			  header("Location:error.php?msg=MSG_ALREADY_INACTIVE");
			  return;
		  }
		  else if(($userProduct) && ($userProduct->getStatus() != "I")){
		   logToFile("UserProduct status is active.. user signing up again for the same product. check if SKIPADDIFUSERPRODUCTEXISTS is set to Y in dap-config.php"); 
		  if(defined(SKIPADDIFUSERPRODUCTEXISTS) && (SKIPADDIFUSERPRODUCTEXISTS=="Y") )
			logToFile("SKIPADDIFUSERPRODUCTEXISTS: ".SKIPADDIFUSERPRODUCTEXISTS);
			$skipAdd=true;
			$msg = MSG_ALREADY_SIGNEDUP;
		  }
	  }
	  
	  //$autoLogin="Y";
	  $doubleoptinproduct=false;
	  if ( isset($user) || ($product->getDouble_optin_subject() != "") || ($product->getDouble_optin_body() != "") ) { 
		  logToFile("user is set or double optin enabled, so autoLogin = 'N'"); 
		  if ( ($product->getDouble_optin_subject() != "") || ($product->getDouble_optin_body() != "") ) {
			  $doubleoptinproduct=true;
		  }
		  $autoLogin = "N"; 
	  }
				
	  if(!$skipAdd) {			
	  try {
		  $user = new Dap_User();
		  $user->setFirst_name($first_name);
		  $user->setLast_name($last_name);
		  $user->setUser_name($user_name);
		  $user->setEmail($email);
		  
		  if($password != "") $user->setPassword($password);
		  
		  $user->setAddress1($address1);
		  $user->setAddress2($address2);
		  $user->setCity($city);
		  $user->setState($state);
		  $user->setZip($zip);
		  $user->setCountry($country);
		  $user->setPhone($phone);
		  $user->setFax($fax);
		  $user->setCompany($company);
		  $user->setTitle($title);
		  $user->setPaypal_email(urldecode($paypal_email));
		  
		  //logToFile("About to call directSignupSubmit"); 
		  $user = Dap_UsersProducts::directSignupSubmit($user, $coupon_code, $productId, $couponId);
	  
		  if(isset($user)) {
			  $uid = $user->getId();
			  $_SESSION['uid'] = $uid;
			  $_SESSION['AFF_LINK'] = SITE_URL_DAP . "/dap/a/?a=".$uid;
			  
			  // check if custom field present
			  foreach($_REQUEST as $key=>$value) {
				  //logToFile("signup_submit.php: key=" . $key . " value=" . $value, LOG_DEBUG_DAP);		
					  
				  if (strstr($key, "custom_")) {	
					  if ($keyval = substr($key, 7)) {
						  $customFld = Dap_CustomFields::loadCustomfieldsByName($keyval);
						  //logToFile("signup_submit.php: loadCustomfieldsByName(): keyval=" . $keyval, LOG_DEBUG_DAP);		
						  
						  if ($customFld) {
							  $id = $customFld->getId();
							  //logToFile("signup_submit.php: customFld Id = " . $id, LOG_DEBUG_DAP);		
							  
							  $usercustom = new Dap_UserCustomFields();
							  $usercustom->setUser_id($uid);
							  $usercustom->setCustom_id($id);
							  $usercustom->setCustom_value($value);
							  
							  $cf = Dap_UserCustomFields::loadUserCustomFieldsByCustomFieldId($id, $uid);
							  if ($cf) {
								  //logToFile("signup_submit.php: call update() to update value=" . $value, LOG_DEBUG_DAP);
								  $usercustom->update();
							  }
							  else {
								  //logToFile("signup_submit.php: call create() to add custom value=" . $nv[1], LOG_DEBUG_DAP);
								  $usercustom->create();
							  }
						  }
					  }
				  }
			  }
			  
			  
			  /** ------------------ START AFFILIATE RESOLUTION -------------------- */		
			  
			  //Credit affiliate (if one exists) for free signup
			  if( isset($user) && isset($_COOKIE['dapa'])) {
				  $affiliate_id = "";
				  //$session = Dap_Session::getSession();
				  //$user = $session->getUser();
				  $user_id = $user->getId();
				  $user = Dap_User::loadUserById($user_id); //reload User object
				  $loginCount = $user->getLogin_Count();
				  //logToFile("loginCount: $loginCount"); 
				  
				  /**
					  First check if user has existing affilaite. 
					  If yes, then use that.
					  If no, then first check if loginCount is < 1. 
						  If yes, then use cookie affiliate
						  If no, then do nothing
				  */
					  
				  $existingAffiliateId = $user->getAffiliate();
				  if ( isset($existingAffiliateId) && ($existingAffiliateId != null) ) {
					  $affiliate_id = $existingAffiliateId;
					  //logToFile("affiliate id existing: $affiliate_id",LOG_DEBUG_DAP);
				  } else if( intval($loginCount) <= 1) { //affiliate credit only for first login
					  $affiliate_id = $_COOKIE['dapa'];
					  //logToFile("affiliate id from cookie: $affiliate_id",LOG_DEBUG_DAP);
				  }
				  
				  
				  if(
					 ($affiliate_id != "") 
					 &&
					 ( (Dap_Config::get("ALLOW_SELF_REFERRAL") == "Y") || ($affiliate_id != $user_id) )
					 &&  
					 ( intval($user_id) > intval($affiliate_id) ) 
				   ) {
					  //1. Figure out all products for this user, for which no affiliate id has been set
					  $ProductListArray = Dap_AffReferrals::getProductsPendingAffiliateStamping($user_id);
					  
					  if( sizeof($ProductListArray) != 0 ){
						  //Process Affiliate Lead
						  Dap_AffReferrals::processAffiliation($affiliate_id, $user_id,$ProductListArray);
						  
						  //Once lead has been paid, unset affiliate cookie
						  //setcookie("dapa","-1",time()-3600,"/");
						  //unset($_COOKIE['dapa']);
					  }
					  
					  //If no products found, still give credit to affiliate for referral
					  //if( sizeof($ProductListArray) == 0 ) {
						  //logToFile("No product found - but affiliate found, so giving credit for referral to $affiliate_id",LOG_DEBUG_DAP);
						  //$ProductListArray = array(0 => -1);
						  
						  //Process Affiliate Credit
						  //Dap_AffReferrals::processAffiliation($affiliate_id, $user_id, $ProductListArray);
					  //}
					  
				  } //end-if
  
			  } //end-if
			  
		  } //end if isset user
		  
		  /** ------------------ END AFFILIATE RESOLUTION -------------------- */		
		  
		  //Load full user and update other info if available
		  //$user = Dap_User::loadUserById($uid);
		  
	  } catch (PDOException $e) {
		  logToFile($e->getMessage(),LOG_FATAL_DAP);
		  $msg = $e->getMessage();
	  } catch (Exception $e) {
		  logToFile($e->getMessage(),LOG_FATAL_DAP);
		  $msg = $e->getMessage();
	  }		
	  
	  } // skipadd as user already has access to product 
	  else {
		  logToFile("signup_submit.php: user resigning to same product, skip adding user to product redirecting to authenticate.php to auto login user : " . $autoLogin);
		  
		  if ($daplistbuildercredits == "Y") { 
		  	return MSG_ALREADY_SIGNEDUP;
		  }
	  }
		  
	  logToFile("signup_submit.php: redirecting to authenticate.php to auto login user : " . $autoLogin);
	  
	  //autologin new user //11/06/2011
	  if(isset($user) && ($autoLogin == "Y")) {
		//logToFile("dap-authenticate.php: redirecting to authenticate.php to auto login user". $record_id);
		logToFile("signup_submit.php: redirecting to authenticate.php to auto login user");
		
		if ($daplistbuildercredits == "Y") {
			$password = $user->getPassword();
			$ret = authUser($email, $password);
			return $ret;
		}
		
		if (isset($_REQUEST['redirect'])) {
		  //logToFile("authenticate.php: redirecting to". $_REQUEST['redirect']);
		  //logToFile("authenticate.php: email=" . urlencode($email) . "&password=" . $user->getPassword() . "&submitted=Y&request=".$_REQUEST['redirect']);
		  header("Location: /dap/authenticate.php?email=" . urlencode($email) . "&password=" . $user->getPassword() . "&submitted=Y&request=".$_REQUEST['redirect']."&daplistbuildercredits=" . $daplistbuildercredits);
		} else {
		  header("Location: /dap/authenticate.php?email=" . urlencode($email) . "&password=" . $user->getPassword() . "&submitted=Y"."&daplistbuildercredits=" . $daplistbuildercredits);
		}
		
	   logToFile($source . ": autologin is YES, redirect=" . $redirect,LOG_FATAL_DAP);
	   return 0;
	  }
	  
	  
	   logToFile($source . ": no-autologin, redirect=" . $redirect,LOG_FATAL_DAP);
	   
	  //Redirect to 'redirect' (if available) or member home page
	  if ($skipAdd) {
	   logToFile("functions.php: registerUser(): skipAdd. user re-signing to same product, redirect to : " . $redirect,LOG_DEBUG_DAP); 
	   $redirect=getCallbackURLWithoutQueryString($redirect);
	   logToFile("functions.php: registerUser(): skipAdd. getCallbackURLWithoutQueryString=" . $redirect,LOG_DEBUG_DAP); 
	   $redirURL =  $redirect . "?msg=" . $msg;
	   logToFile("functions.php: registerUser(): skipAdd. user re-signing to same product, redirURL: " . $redirURL,LOG_DEBUG_DAP); 
	  }
	  else 	if($thirdParty == true) {
		  $redirURL = $redirect;
	  } else {
		  $redirURL =  $redirect . "?msg=" . $msg;
	  }
	  
	  if($daplistbuildercredits=="Y") {
		 logToFile("functions.php: daplistbuildercredits (double-optin product so no auto-login) but signup completed successfully",LOG_DEBUG_DAP); 
		 if( $doubleoptinproduct == false) {
			return 0; 
		 }
		 if(defined(MSG_THANKYOU_SIGNUP_ACTIVATION)) 
		 	return MSG_THANKYOU_SIGNUP_ACTIVATION;
		 else 
		 	return MSG_THANKYOU_SIGNUP; // double optin in signup successful but do not show hidden content to STU until user completes activation
		 
	  }
	  header("Location: " . urldecode($redirURL));
	  return;
	}
	
	function authUser($email, $password) {
				
		if( ($email != "") && ($password != "") && validateLogins($email, getIpOfUser()) && validate($email,$password) ) {
			logToFile("authUser(): Everything appears ok with login"); 
			
			logToFile("authUser(): pluginLogin: calling",LOG_DEBUG_DAP); 
			pluginLogin($email);
			logToFile("authUser(): pluginLogin: complete",LOG_DEBUG_DAP); 
			$session = Dap_Session::getSession();
			logToFile("authUser(): calling session get user",LOG_DEBUG_DAP); 
			$user = $session->getUser();
			
			if($user) {
			  $user = Dap_User::loadUserById($user->getId()); //reload User object
			  processDAPCredits($user);
			}
			
			logToFile("authUser(): got user",LOG_DEBUG_DAP);
			include_once("admin/affiliateResolution.php");
			logToFile("authUser(): included",LOG_DEBUG_DAP);
			
			return 0;
			
		} else {
			//Invalid password...
			return INVALID_PASSWORD_MSG;
		}
		
	}
	
	function getCallbackURLWithoutQueryString($current_url_with_query_string)
	{
	logToFile("current_url_with_query_string=".$current_url_with_query_string,LOG_DEBUG_DAP);
	 
	if(strpos($current_url_with_query_string, '?') > 0) {
	  $current_url_without_query_string = substr($current_url_with_query_string, 0, strpos($current_url_with_query_string, '?')); // This line is the key
	  logToFile("current_url_without_query_string=".$current_url_without_query_string,LOG_DEBUG_DAP);
	  return $current_url_without_query_string;
	}
	  
	return $current_url_with_query_string;
	}
	
	
	function createOrUpdateUserAccount($email,$cpassword,$caller="paypalcoupon:",$allowUpdate=false)  // Paypal express checkout 
	{
		include ("countryCodes.php");
		
		$notloggedinbutusingexistingemail=false;
		$user = Dap_User::loadUserByEmail($email);
		if(!isset($user)) {
			logToFile("$caller :see if the paypal email field has this user's email");
			$user = Dap_User::loadUserByPaypalEmail($email);
			if(isset($user)) 
				$notloggedinbutusingexistingemail=true;
		}
		else {
			$notloggedinbutusingexistingemail=true;
		}
		
		$newuser=false;
		if(!isset($user)) {
			$user = new Dap_User();
			logToFile("$caller :CheckingUser: New User");
			if(isset($_SESSION["first_name"]) && ($_SESSION["first_name"] != "")) {
				$user->setFirst_name( $_SESSION["first_name"] );
				logToFile("$caller: FIRST=" . $_SESSION["first_name"], LOG_DEBUG_DAP);
			}
		
			if(isset($_SESSION["last_name"]) && ($_SESSION["last_name"] != "")) {
				$user->setLast_name( $_SESSION["last_name"] );
			}
			
			$user->setEmail( $email );	
			if($cpassword != "")
				$user->setPassword($cpassword);
			
			$newuser=true;
		}
		
		if( ((isset($user)) && ($notloggedinbutusingexistingemail==false)) || ($allowUpdate==true)) {
			if(isset($_SESSION["address1"]) && ($_SESSION["address1"] != "")) {
				$user->setAddress1( $_SESSION["address1"] );
			}
			
			if(isset($_SESSION["city"]) && ($_SESSION["city"] != "")) {
				$user->setCity( $_SESSION["city"] );
			}
			
			if(isset($_SESSION["zip"]) && ($_SESSION["zip"] != "")) {
				$user->setZip( $_SESSION["zip"] );
			}
			
			if(isset($_SESSION["phone"]) && ($_SESSION["phone"] != "")) {
				$user->setPhone( $_SESSION["phone"] );
			}
			logToFile("$caller: createaccount: phone number=".$_SESSION["phone"], LOG_DEBUG_DAP);
			if(isset($_SESSION["company"]) && ($_SESSION["company"] != "")) {
				$user->setCompany( $_SESSION["company"] );
			}
			
			logToFile("$caller: createaccount: company=".$_SESSION["company"], LOG_DEBUG_DAP);
			
			if(isset($_SESSION["address2"]) && ($_SESSION["address2"] != "")) {
				$user->setAddress2( $_SESSION["address2"] );
			}
			logToFile("$caller: createaccount: address2=".$_SESSION["address2"], LOG_DEBUG_DAP);
			
			
			if(isset($_SESSION["country"]) && ($_SESSION["country"] != "")) {
				$code = array_search($_SESSION["country"], $countrycodes); 
				logToFile("paypalCoupon.php: COUNTRY=" . $_SESSION["country"], LOG_DEBUG_DAP);
				
				if($code != "") {
					$params['country']=$code;
					$user->setCountry( $code );
					if( ($code == "US") && (isset($_SESSION["state"])) && ($_SESSION["state"] != "")) {
						$statecode=convert_state_to_abbreviation($_SESSION["state"]);
						logToFile("$caller: STATE CODE=" . $statecode, LOG_DEBUG_DAP);
						if($statecode != "") {
							$user->setState( $statecode );
						}
					}
				}
			}
			$_SESSION["userexistsbutallow"]="Y";
			
			if($newuser) {
				logToFile("$caller: created NEW user successfully", LOG_DEBUG_DAP);
				$user->setStatus("P");
				$user->create();
			}
			else {
				$user->update();
			}
			
			$user = Dap_User::loadUserByEmail($email);
			logToFile("$caller: created NEW user successfully", LOG_DEBUG_DAP);
		
			$userId=$user->getId();
			logToFile("$caller: updateCustomFields(): allowUpdate= yes", LOG_DEBUG_DAP);
			if ( isset($_SESSION['howdidyouhearaboutus']) && ($_SESSION['howdidyouhearaboutus'] != "") ) {
				$customFld = Dap_CustomFields::loadCustomfieldsByName("howdidyouhearaboutus");
				logToFile("$caller: updateCustomFields(): called loadCustomfieldsByName", LOG_DEBUG_DAP);
				if ($customFld) {
					$id = $customFld->getId();
					logToFile("$caller: updateCustomFields(): id=" . $id, LOG_DEBUG_DAP);
					
					$usercustom = new Dap_UserCustomFields();
				
					$usercustom->setUser_id($userId);
					$usercustom->setCustom_value($_SESSION['howdidyouhearaboutus']);
					$usercustom->setCustom_id($id);
					
					$cf = Dap_UserCustomFields::loadUserCustomFieldsByCustomFieldId($id, $userId );
					if ($cf) {
						logToFile("$caller: updateCustomFields(): call update to update value=" . $_SESSION['howdidyouhearaboutus'], LOG_DEBUG_DAP);
						$usercustom->update();
					}
					else {
						logToFile("$caller: updateCustomFields(): call create to add custom value=" . $_SESSION['howdidyouhearaboutus'], LOG_DEBUG_DAP);
						$usercustom->create();
					}
				}
			}
			if ( isset($_SESSION['comments']) && ($_SESSION['comments'] != "") ) {
				$customFld = Dap_CustomFields::loadCustomfieldsByName("comments");
				logToFile("$caller: updateCustomFields(): called comments", LOG_DEBUG_DAP);
				if ($customFld) {
					$id = $customFld->getId();
					logToFile("$caller: updateCustomFields(): id=" . $id, LOG_DEBUG_DAP);
					
					$usercustom = new Dap_UserCustomFields();
				
					$usercustom->setUser_id($userId);
					$usercustom->setCustom_value($_SESSION['comments']);
					$usercustom->setCustom_id($id);
					
					$cf = Dap_UserCustomFields::loadUserCustomFieldsByCustomFieldId($id, $userId);
					if ($cf) {
						logToFile("$caller: updateCustomFields(): call update to update value=" . $_SESSION['comments'], LOG_DEBUG_DAP);
						$usercustom->update();
					}
					else {
						logToFile("$caller: updateCustomFields(): call create to add custom value=" . $_SESSION['comments'], LOG_DEBUG_DAP);
						$usercustom->create();
					}
				}
			}
		
			if(isset($_SESSION["customfieldval"])) {
				$customfieldval = $_SESSION["customfieldval"];
			  	$cfallarr = explode(",", $customfieldval);
				$count = count($cfallarr);      
				for($i=0;$i<$count;$i++){
				  //logToFile("processdapcredits(): COUNT=". $count);	 
				  //logToFile("processdapcredits(): i=" .  $i . ", cscredits=". $cscredits[$i]);
				    $cfarr = explode(":", $cfallarr[$i]);
					logToFile("$caller: custom field name=".  $cfarr[0]);	 
					logToFile("$caller: custom field val=".  $cfarr[1]);
					$name=$cfarr[0];
					if ($keyval = substr($name, 7)) {
						  $customFld = Dap_CustomFields::loadCustomfieldsByName($keyval);
						  logToFile("$caller: loadCustomfieldsByName(): keyval=" . $keyval, LOG_DEBUG_DAP);		
						  if ($customFld) {
							  $id = $customFld->getId();
							  logToFile("$caller: customFld Id = " . $id, LOG_DEBUG_DAP);		
							  
							  $usercustom = new Dap_UserCustomFields();
							  
							  $usercustom->setUser_id($userId);
							  $usercustom->setCustom_id($id);
							  $usercustom->setCustom_value($cfarr[1]);
							  
							  $cf = Dap_UserCustomFields::loadUserCustomFieldsByCustomFieldId($id, $userId);
							  if ($cf) {
								  logToFile("$caller:  updateCustomFields(): call update() to update value=" . $cfarr[1], LOG_DEBUG_DAP);
								  $usercustom->update();
							  }
							  else {
								  logToFile("$caller:  updateCustomFields(): call create() to add custom value=" . $cfarr[1], LOG_DEBUG_DAP);
								  $usercustom->create();
							  }
						  }
					}
					
				}
				
			}
		}
		
	}
	
	
	function convert_state_to_abbreviation($state_name) {
		switch ($state_name) {
		case "Alabama":
		return "AL";
		break;
		case "Alaska":
		return "AK";
		break;
		case "Arizona":
		return "AZ";
		break;
		case "Arkansas":
		return "AR";
		break;
		case "California":
		return "CA";
		break;
		case "Colorado":
		return "CO";
		break;
		case "Connecticut":
		return "CT";
		break;
		case "Delaware":
		return "DE";
		break;
		case "Florida":
		return "FL";
		break;
		case "Georgia":
		return "GA";
		break;
		case "Hawaii":
		return "HI";
		break;
		case "Idaho":
		return "ID";
		break;
		case "Illinois":
		return "IL";
		break;
		case "Indiana":
		return "IN";
		break;
		case "Iowa":
		return "IA";
		break;
		case "Kansas":
		return "KS";
		break;
		case "Kentucky":
		return "KY";
		break;
		case "Louisana":
		return "LA";
		break;
		case "Maine":
		return "ME";
		break;
		case "Maryland":
		return "MD";
		break;
		case "Massachusetts":
		return "MA";
		break;
		case "Michigan":
		return "MI";
		break;
		case "Minnesota":
		return "MN";
		break;
		case "Mississippi":
		return "MS";
		break;
		case "Missouri":
		return "MO";
		break;
		case "Montana":
		return "MT";
		break;
		case "Nebraska":
		return "NE";
		break;
		case "Nevada":
		return "NV";
		break;
		case "New Hampshire":
		return "NH";
		break;
		case "New Jersey":
		return "NJ";
		break;
		case "New Mexico":
		return "NM";
		break;
		case "New York":
		return "NY";
		break;
		case "North Carolina":
		return "NC";
		break;
		case "North Dakota":
		return "ND";
		break;
		case "Ohio":
		return "OH";
		break;
		case "Oklahoma":
		return "OK";
		break;
		case "Oregon":
		return "OR";
		break;
		case "Pennsylvania":
		return "PA";
		break;
		case "Rhode Island":
		return "RI";
		break;
		case "South Carolina":
		return "SC";
		break;
		case "South Dakota":
		return "SD";
		break;
		case "Tennessee":
		return "TN";
		break;
		case "Texas":
		return "TX";
		break;
		case "Utah":
		return "UT";
		break;
		case "Vermont":
		return "VT";
		break;
		case "Virginia":
		return "VA";
		break;
		case "Washington":
		return "WA";
		break;
		case "Washington D.C.":
		return "DC";
		break;
		case "West Virginia":
		return "WV";
		break;
		case "Wisconsin":
		return "WI";
		break;
		case "Wyoming":
		return "WY";
		break;
		case "Alberta":
		return "AB";
		break;
		case "British Columbia":
		return "BC";
		break;
		case "Manitoba":
		return "MB";
		break;
		case "New Brunswick":
		return "NB";
		break;
		case "Newfoundland & Labrador":
		return "NL";
		break;
		case "Northwest Territories":
		return "NT";
		break;
		case "Nova Scotia":
		return "NS";
		break;
		case "Nunavut":
		return "NU";
		break;
		case "Ontario":
		return "ON";
		break;
		case "Prince Edward Island":
		return "PE";
		break;
		case "Quebec":
		return "QC";
		break;
		case "Saskatchewan":
		return "SK";
		break;
		case "Yukon Territory":
		return "YT";
		break;
		default:
		return $state_name;
		}
    }


	function clearCartSession() {

		if(isset($_SESSION["stripeToken"])) {
			$_SESSION["stripeToken"]="";
			unset($_SESSION["stripeToken"]);
		}
		
		if(isset($_SESSION["cpassword"])) {
			$_SESSION["cpassword"]="";
			unset($_SESSION["cpassword"]);
		}
		
		if(isset($_SESSION["userexistsbutallow"])) {
			$_SESSION["userexistsbutallow"]="";
			unset($_SESSION["userexistsbutallow"]);
		}
		
		if(isset($_SESSION["first_name"])) {
			$_SESSION["first_name"]="";
			unset($_SESSION["first_name"]);
			
			$_SESSION["last_name"]="";
			unset($_SESSION["last_name"]);
			
			$_SESSION["address1"]="";
			unset($_SESSION["address1"]);
			
			$_SESSION["city"]="";
			unset($_SESSION["city"]);
			
			$_SESSION["state"]="";
			unset($_SESSION["state"]);
			
			$_SESSION["zip"]="";
			unset($_SESSION["zip"]);	
		}
	}
	
	function generateUsername($caller,$email,$firstname,$lastname) {
		//value=1 USERNAME_FIRST_LAST
		//value=2 USERNAME_1STPARTEM
		//value=3 USERNAME_FIRSTLAST3
		//value=4 USERNAME_RANDOM8
		//value=5 USERNAME_USERPICK
		
		$option1=Dap_Config::get("OPTION1");
		$option2=Dap_Config::get("OPTION2");
		$option3=Dap_Config::get("OPTION3");
		
		$optionArr=array();
		
		$optionArr[0]=$option1;
		$optionArr[1]=$option2;
		$optionArr[2]=$option3;

		$i=0;
		while($i<count($optionArr)) {
			logToFile("$caller: generateUsername(): $i, try option=" . $optionArr[$i], LOG_DEBUG_DAP);	
			switch(	$optionArr[$i] ) {
				case 1:
					$username=$firstname.$lastname;
					break;
				case 2:
					$namearr=explode("@",$email);
					$username=$namearr[0];
					break;
				case 3:
					$username=$firstname.$lastname.mt_rand(100,999);
					break;
				case 4:
					$username=random_string(8);
					break;
				case 5:
					$username="userpick";
					break;
			}
			logToFile("$caller: generateUsername():" . $username, LOG_DEBUG_DAP);	
			if($username!="") {
				//check if already taken, if yes continue, if available return this username
				if($username=="userpick")
					return "";
				$isInUse=Dap_User::isInUse("user_name",$username);
				if($isInUse==FALSE) {
					logToFile("$caller: generateUsername(): username=username generated successfully using " . $optionArr[$i], LOG_DEBUG_DAP);	
					return $username;
				}
			}
			$i++;
		}
		logToFile("$caller: generateUsername(): no username found: " . $username, LOG_DEBUG_DAP);	
	}
	
	
	function generateUsernameOLD($caller,$email,$firstname,$lastname) {
		
		
		$username_first_last=Dap_Config::get("USERNAME_FIRST_LAST");
		$username_1stpartem=Dap_Config::get("USERNAME_1STPARTEM");
		$username_firstlast3=Dap_Config::get("USERNAME_FIRSTLAST3");
	//	$username_random8=Dap_Config::get("USERNAME_RANDOM8");
	//	$username_userpick=Dap_Config::get("USERNAME_USERPICK");
		
		$optionArr=array();
		
		$optionArr[$username_first_last]="firstlast";
		$optionArr[$username_1stpartem]="1stpartem";
		$optionArr[$username_firstlast3]="firstlast3";
	//	$optionArr[$username_random8]="random8";
	//	$optionArr[$username_userpick]="userpick";
				
		$i=1;
		while($i<count($optionArr)) {
			logToFile("$caller: generateUsername(): $i, try option=" . $optionArr[$i], LOG_DEBUG_DAP);	
			switch(	$optionArr[$i] ) {
				case "firstlast":
					$username=$firstname.$lastname;
					break;
				case "1stpartem":
					$namearr=explode("@",$email);
					$username=$namearr[0];
					break;
				case "firstlast3":
					$username=$firstname.$lastname.mt_rand(100,999);
					break;
				case "random8":
					$username=random_string(8);
					break;
				case "userpick":
				$username="userpick";
				break;
			}
			logToFile("$caller: generateUsername():" . $username, LOG_DEBUG_DAP);	
			if($username!="") {
				//check if already taken, if yes continue, if available return this username
				if($username=="userpick")
					return "";
				$isInUse=Dap_User::isInUse("user_name",$username);
				if($isInUse==FALSE) {
					logToFile("$caller: generateUsername(): username=username generated successfully using " . $optionArr[$i], LOG_DEBUG_DAP);	
					return $username;
				}
			}
			$i++;
		}
		logToFile("$caller: generateUsername(): no username found: " . $username, LOG_DEBUG_DAP);	
	}
	
	
	function random_string($length) {
	  $key = '';
	  $keys = array_merge(range(0, 9), range('a', 'z'));
  
	  for ($i = 0; $i < $length; $i++) {
		  $key .= $keys[array_rand($keys)];
	  }
  
	  return $key;
	}

?>