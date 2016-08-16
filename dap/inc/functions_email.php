<?php
	function sendWelcomeUserEmail($user) {
		logToFile("Sending Welcome Email To:".$user->getEmail());
		$data = Dap_Templates::getContentByName("WELCOME_EMAIL_CONTENT");
		$subject = Dap_Config::get("WELCOME_EMAIL_SUBJECT");
		if(empty($data) === FALSE) {
			$data = personalizeMessage($user, $data);
			$subject = personalizeMessage($user, $subject);
			sendEmail($user->getEmail(), $subject, $data);
			logToFile("Sent Welcome Email To:".$user->getEmail(), LOG_INFO_DAP);
		} else {
			logToFile("sendWelcomeUserEmail:Error In Sending(template not found) Welcome Email To:".$user->getEmail(), LOG_FATAL_DAP);
		}
	}
	
	function sendUserProductActivationEmail($user, $productId) {
		logToFile("Sending Activation Email To:".$user->getEmail());
		//$data = Dap_Templates::getContentByName("ACTIVATION_EMAIL_CONTENT");
		//$subject = Dap_Config::get("ACTIVATION_EMAIL_SUBJECT");
		$subject = "";
		$data = "";
		
		$product = Dap_Product::loadProduct($productId);
		if( ($product->getDouble_optin_subject() != "") && ($product->getDouble_optin_body() != "") ) {
			$subject = $product->getDouble_optin_subject();
			$data = $product->getDouble_optin_body();
		}
		
		logToFile("Sending Activation Email Subject:".$subject);
		
		if(empty($data) === FALSE) {
			$data = personalizeMessage($user, $data);
			//$data = str_replace("%%ACTIVATION_KEY%%", $user->getActivation_key(), $data);
			$activationLink = "%%SITE_URL_DAP%%/dap/preactivate.php?c=".$user->getActivation_key()."&p=".$productId."";
			$data = str_replace("%%ACTIVATION_LINK%%", $activationLink, $data);
			$subject = personalizeMessage($user, $subject);
			
			$data = stripslashes($data);
			$subject = stripslashes($subject);
			
			logToFile("Sending Activation Email Subject:".$subject);
			$result=sendEmail($user->getEmail(), $subject, $data);
			if($result=="")
				logToFile("Sent Activation Email To: ".$user->getEmail(), LOG_INFO_DAP);
			else
				logToFile("Could not send Activation Email To: ".$user->getEmail(). ". Failed with: " . $result, LOG_INFO_DAP);
			return $result;
		} else {
			logToFile("sendUserProductActivationEmail: Error In Sending(template not found) Activation Email To: ".$user->getEmail(), LOG_FATAL_DAP);
			return "";
		}
	}	
	
	
	function sendTestEmail($emailId,$subject,$data) {
		logToFile("Sending Test Email ");
		//$data = Dap_Templates::getContentByName("USERPRODUCT_WELCOME_EMAIL_CONTENT");
		//$subject = Dap_Templates::getContentByName("USERPRODUCT_WELCOME_EMAIL_SUBJECT");		
			
		$user = Dap_User::loadUserByEmail($emailId);
		$result="";
		
		if(isset($user)) {
			if ( (empty($data) === FALSE) || (empty($subject) === FALSE) ) {
				$data = personalizeMessage($user, $data);
				$subject = personalizeMessage($user, $subject);
				
				$data = stripslashes($data);
				$subject = stripslashes($subject);
				
				$result=sendEmail($user->getEmail(), $subject, $data);
				if($result=="") {
					logToFile("Sent Test Email To: ".$user->getEmail(), LOG_INFO_DAP);
					$result="Sent Test Email Successfully To " .  $user->getEmail();
				}
				else
					logToFile("Could not send Test Email To: ".$user->getEmail() . ". Failed with: " . $result, LOG_INFO_DAP);
				
				return $result;
			} 
			else {
				logToFile("ERROR..Sending Test Email. UserId: $uid ", LOG_FATAL_DAP);
				return "ERROR..Sending Test Email. Empty Email Subject OR Body";
			}
		} //user found
		else {
			logToFile("ERROR..Sending Test Email. user not found: " . $emailId, LOG_FATAL_DAP);
			return "ERROR..Sending Test Email. user not found: " . $emailId;
		}
		
	}
	
	
	function sendUserProductWelcomeEmail($uid, $productId) {
		logToFile("Sending UserProduct Notification Email ");
		//$data = Dap_Templates::getContentByName("USERPRODUCT_WELCOME_EMAIL_CONTENT");
		//$subject = Dap_Templates::getContentByName("USERPRODUCT_WELCOME_EMAIL_SUBJECT");		
		$subject = "";
		$data = "";
		
		$product = Dap_Product::loadProduct($productId);
		if( ($product->getThankyou_email_subject() != "") && ($product->getThankyou_email_body() != "") ) {
			$subject = $product->getThankyou_email_subject();
			$data = $product->getThankyou_email_body();
			
			$user = Dap_User::loadUserById($uid);
			if( (empty($user) === FALSE) || (empty($data) === FALSE) || (empty($product) === FALSE)) {
				$data = personalizeMessage($user, $data);
				$data = personalizeMessageProduct($product, $data);
				$subject = personalizeMessage($user, $subject);
				$subject = personalizeMessageProduct($product, $subject);			
				
				$data = stripslashes($data);
				$subject = stripslashes($subject);
				
				$result=sendEmail($user->getEmail(), $subject, $data);
				if($result=="")
					logToFile("Sent UserProduct Welcome Email To: ".$user->getEmail(), LOG_INFO_DAP);
				else
					logToFile("Could not send Welcome Email To: ".$user->getEmail() . ". Failed with: " . $result, LOG_INFO_DAP);
				
				return $result;
			} else {
				logToFile("ERROR..Sending UserProduct Notification Email. UserId: $uid, EmailList: $emaillist ", LOG_FATAL_DAP);
				return "";
			}
		}
		
	}

	//TODO: Deprecated. Remove after a while
	function sendUserActivationEmailUserOnly($user) {
		logToFile("Sending Activation Email To:".$user->getEmail());
		$data = Dap_Templates::getContentByName("ACTIVATION_EMAIL_CONTENT");
		$subject = Dap_Config::get("ACTIVATION_EMAIL_SUBJECT");
		logToFile("Sending Activation Email Subject:".$subject);
		if(empty($data) === FALSE) {
			$data = personalizeMessage($user, $data);
			$data = str_replace("%%ACTIVATION_KEY%%", $user->getActivation_key(), $data);
			$data = str_replace("%%PASSWORD%%", $user->getPassword(), $data);
			$subject = personalizeMessage($user, $subject);
			
			$data = stripslashes($data);
			$subject = stripslashes($subject);
			
			logToFile("Sending Activation Email Subject:".$subject);
			sendEmail($user->getEmail(), $subject, $data);
			logToFile("Sent Activation Email To:".$user->getEmail(), LOG_INFO_DAP);
		} else {
			logToFile("sendUserActivationEmailUserOnly: Error In Sending(template not found) Activation Email To:".$user->getEmail(), LOG_FATAL_DAP);
		}
	}

	//Send email to admin(s). Admin emails are extracted from ADMIN_EMAIL config value.
	function sendAdminEmail($subject, $bodyText) {
		//echo $email; exit;
		$email = Dap_Config::get("ADMIN_EMAIL");
		$site_url = Dap_Config::get("SITE_URL_DAP");
		$site_url = str_replace("http://","",$site_url);
		$site_url = str_replace("www.","",$site_url);
		$emails = explode(",",$email);
		$subject = ADMIN_SUBJECT_PREFIX . $site_url . ": " . $subject;
		foreach ($emails as $email) {
			sendEmail($email, $subject, $bodyText);
		}
	}
	
	//
	function sendMassActionFailedNE($action, $msg) {
		$subject = "Mass Action Processing Failed.";
		$body = "A Mass Action processing has failed. Please see details below. \n";
		$body = $body . "\n Action: $action \n Message: $msg";
		sendAdminEmail($subject, $body);
	}

	//
	function sendUserProductNE($uid, $product) {
		logToFile("Sending UserProduct Notification Email ");
		$emaillist = trim($product->getThirdPartyEmailIds());
		if($emaillist == "") return;
		$user = Dap_User::loadUserById($uid);
		if($user != NULL) {
			$subject = Dap_Config::get("USERPRODUCT_NOTIFY_EMAIL_SUBJECT");
			$emails = explode(",",$emaillist);
			$useremail = $user->getEmail();
			$bodyText = "Name: " . $user->getFirst_name() . " " . $user->getLast_name() . "\n" .
				"Email: " . $useremail . "\n" .
				"Product: " . $product->getName() . "\n";
			foreach ($emails as $email) {
				sendEmailWithFrom($user->getFirst_name()." ".$user->getLast_name(),$useremail, $email, $subject, $bodyText);
			}
		} else {
			logToFile("ERROR..Sending UserProduct Notification Email. UserId: $uid, EmailList: $emaillist ", LOG_FATAL_DAP);
		}
	}

	function sendTransactionNotificationEmail($transaction) {
		logToFile("Sending Payment Notification Email ");		
		$data = Dap_Templates::getContentByName("PAYMENT_NOTIFY_CONTENT");
		$subject = Dap_Config::get("PAYMENT_NOTIFY_EMAIL_SUBJECT");
		$email = getAdminEmail();
		if(empty($data) === FALSE) {
			$data = str_replace("%%PAYMENT_PROCESSOR%%", $transaction->getPayment_processor(), $data);
			$data = str_replace("%%PAYER_EMAIL%%", $transaction->getPayer_email(), $data);
			$data = str_replace("%%TRANS_NUM%%", $transaction->getTrans_num(), $data);
			$data = str_replace("%%PRODUCT_ID%%", $transaction->getProduct_id(), $data);
			$data = str_replace("%%DATA%%", $transaction->getTrans_blob(), $data);
			$subject = ADMIN_SUBJECT_PREFIX . " Payment Received. Processor: " . $transaction->getPayment_processor();
			//if(!mail($email, $subject, $data)) {
				//logToFile("Error in sending email to Admin. To: $to, Subject: $subject, Body: $data", LOG_FATAL_DAP);
			//}
			sendEmail($email, $subject, $data);
		} else {
			logToFile("Error In Sending(template not found) Email To:".$email, LOG_FATAL_DAP);
		}
	}
	
	//
	function sendEmail($email, $subject, $bodyText, $fromName="", $fromEmail="") {
		//logToFile("In functions_email:sendEmail(): From-name: $fromName, From-email: $fromEmail , To-email: $email, Subject: $subject, Body: $bodyText"); 
		//append footer
		$bodyText = $bodyText . "\n\n\n\n\n" . getEmailFooter();
		//echo $email; exit;
		$email = trim(stripslashes($email));
		$subject = trim(stripslashes($subject));
		$bodyText = trim(stripslashes($bodyText));
		$admin_name = Dap_Config::get("ADMIN_NAME");
		$admin_email = Dap_Config::get("ADMIN_EMAIL");
		//$admin_email = getAdminEmail();
		
		$user = Dap_User::loadUserByEmail($email);
		
		if( isset($user) && ($user != NULL) ) {
			//User found...so continue personalization	
			$bodyText = personalizeMessage($user, $bodyText);
		}
		
		logToFile("Sending email From: $admin_email , To: $email, Subject: $subject, Body: $bodyText, ");
		
		if( ($admin_email == "") || 
			 ($admin_name == "") || 
			 ($email == "") || 
			 ($subject == "") || 
			 ($bodyText == "") 
		) {
			logToFile("ERR1: Error Sending email (to, subject, body are possibly empty) To: $email, Subject: $subject, Body: $bodyText", LOG_FATAL_DAP);
			$errtext="Error Sending email (to, subject, body are possibly empty) To: $email, Subject: $subject, Body: $bodyText";
			return $errtext;
		}
		
		Dap_SMTPServer::init();
		$sent_email_counter = 0;
		
		//get smtp server
		$server = Dap_SMTPServer::get();
		if(!isset($server)) {
			logToFile("ERR2: in sendEmail: SMTPServer Not Available.");
			//break the loop if we dont have any more smtp servers to use;
			$errtext="Error Sending email To: $email. SMTPServer Not Available.";
			return $errtext;
			//return;
		}
		
		//get batch size for this smtp server
		$batch_size = $server->getUseableLimit();
		// no more delay
		//$delay = getEmailDelay();
																				
		//we have emails to send and we have smtp server handy to send emails.
		//create the mailer object using information from the smtp server config;
		$host = $server->getServer();
		logToFile("in sendEmail: SMTPServer: " . $host . ", Batch Size: ". $batch_size);
		
		$mail  = new PHPMailerDAP();
		$mail->SetLanguage('en','language/');
		$localhostForEmail=true;
		if ("local_web_host" != strtolower($host)) {	
			//$mail->SMTPDebug = true;
			$localhostForEmail=false;
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
		
		$pieces = explode(Dap_Config::get('HTMLSEPARATOR'),$bodyText);
		//logToFile("Text part before: A".$pieces[0]."B"); 
		$textBody = ($pieces[0] == "") ? "Sorry, this email is only being sent in HTML format." : $pieces[0];
		$htmlBody = $pieces[1];
		$mail->ClearAllRecipients();
		$mail->ClearAttachments();
		//set new stuff
		
		/** 
			If smtp server is authsmtp, then since authsmtp does not allow random email id's (of member)
			to be used as "From Email" (you have to whitelist all "from" emails at authsmtp.com
			within your account), then in this case, from name and from email will always be that set in DAP Config.
			If you use any other smtp service other than authsmtp, then from email can be that of member,
			which is what Aweber expects.
		*/
		
		/* 
			Update on 07/22/2012: From-name and From-email is now always used from Setup > Config
			due to many issues with using subscriber's from-name and from-email for sending
			emails from third-party servers
		*/
		
		/**
		if( ($host == "mail.authsmtp.com") || {
			$mail->From = $admin_email;
			$mail->FromName = $admin_name;
		} else {
			$mail->From = ( isset($fromEmail) && ($fromEmail != "") ) ? $fromEmail : $admin_email;
			$mail->FromName = ( isset($fromName) && ($fromName != "") ) ? $fromName : $admin_name;
		}
		*/
		
		$mail->From = $admin_email;
		$mail->FromName = $admin_name;
		$mail->SetFrom($admin_email, $admin_name);
		$mail->AddReplyTo($admin_email, $admin_name);
		
		
		$mail->AddAddress($email);
		$mail->Subject = stripslashes($subject);
		$mail->Body = stripslashes($textBody);
		if($pieces[1] != "") {
			$mail->Body = $htmlBody;
			$mail->isHTML = true;
			$mail->AltBody = $textBody;
		}
		//if(!mail($email, $subject, $bodyText, "From: \"" . $admin_name . "\" <" . $admin_email . ">")) {
		if (!$mail->Send()) {
			logToFile("ERR3: Error in sending email To: $email", LOG_FATAL_DAP);
			logToFile("mail->ErrorInfo: " . $mail->ErrorInfo);
			if($localhostForEmail==false)
				$errtext="Error Sending email To: $email. ERROR: " . $mail->ErrorInfo . ".  <br><br> Looks like you are using 3rd party SMTP Server to send emails in DAP Admin -> Email -> SMTP.  But you may have not configured the credentials correctly or you may not have whitelisted the From Email in your SMTP Provider. <br> <br>Please double check and make sure the credentials are correct (server name, port, userid, password etc in DAP EMAIL=>SMTP) and make sure to whitelist the FROM EMAIL in your SMTP Provider .";
			return $errtext;
		}
		
		return "";
	}

	function sendEmailWithFrom($fromName, $fromEmail, $to, $subject, $bodyText) {
		//append footer
		$bodyText = $bodyText . "\n\n\n";
		//echo $email; exit;
		$fromName = trim($fromName);
		$fromEmail = trim($fromEmail);
		$to = trim($to);
		$subject = trim(stripslashes($subject));
		$bodyText = trim(stripslashes($bodyText));
		if($fromName == "" || $fromEmail == "" || $to == "" || $subject == "" || $bodyText == "") {
			logToFile("Error Sending email (a required field is empty) From-Name: $fromName, From-Email: $fromEmail, To: $to, Subject: $subject, Body: $bodyText", LOG_FATAL_DAP);
			return;
		}
		logToFile("Sending email From-Name: $fromName, From-Email: $fromEmail, To: $to, Subject: $subject, Body: $bodyText");
		//if(!mail($to, $subject, $bodyText, "From: \"" . $fromName . "\" <" . $fromEmail . ">")) {
			//logToFile("Error in sending email From-Name: $fromName, From-Email: $fromEmail, To: $to, Subject: $subject, Body: $bodyText", LOG_FATAL_DAP);
		//}
		
		//Fix for when using 3rd party SMTP servers
		//sendEmail($to, $subject, $bodyText, $fromName, $fromEmail);
		sendEmail($to, $subject, $bodyText, "", "");
		return;
	}

	function sendNewUserInvite($email, $first_name, $activation_key, $password) {
		//TODO: fix this up properly to have nice subject and body
		logToFile("(functions_email.sendNewUserInvite()) Sending Email to new user: Name:".$first_name.", email:".$email);
	}


	function personalizeMessage($user, $message) {
		$site_url_dap = Dap_Config::get("SITE_URL_DAP");
		if( isset($user) && ($user != null) ) {
			$message = str_replace("%%AFF_LINK%%", "$site_url_dap/dap/a/?a=".$user->getId(), $message);
			$message = str_replace("%%UNSUB_LINK%%", "$site_url_dap/dap/unsub.php?e=".$user->getEmail()."&c=".$user->getActivation_key(), $message);
			
			$message = str_replace("%%EMAIL_ID%%", $user->getEmail(), $message);
			$message = str_replace("%%FIRST_NAME%%", $user->getFirst_name(), $message);
			$message = str_replace("%%LAST_NAME%%", $user->getLast_name(), $message);
			$message = str_replace("%%PASSWORD%%", $user->getPassword(), $message);
			$message = str_replace("%%ADDRESS1%%", $user->getAddress1(), $message);
			$message = str_replace("%%ADDRESS2%%", $user->getAddress2(), $message);
			$message = str_replace("%%CITY%%", $user->getCity(), $message);
			$message = str_replace("%%STATE%%", $user->getState(), $message);
			$message = str_replace("%%ZIP%%", $user->getZip(), $message);
			$message = str_replace("%%COUNTRY%%", $user->getCountry(), $message);
			$message = str_replace("%%PHONE%%", $user->getPhone(), $message);
			$message = str_replace("%%FAX%%", $user->getFax(), $message);
			$message = str_replace("%%CREDITS_AVAILABLE%%", $user->getCredits_available(), $message);
			
			$message = personalizeCustomFields($user->getId(), $message);
		}
		
		
		
		//$message = personalizeMessageDet($user->getEmail(), $user->getFirst_name(), $user->getLast_name(), $message, $user->getPassword());
		$message = personalizeMessageSite($message);
		return stripslashes($message);	
	}
	
	
	function personalizeCustomFields($userId, $message) {
		
//		hi, your tax id is %%custom_tax%% and your ssn is %%custom_ssn%%.
		
		$count = substr_count($message, "%%custom_"); 
		//count = 2
		logToFile("functions_email.php: occurences=" . $count);
		
		for($i=0; $i<$count;$i++) {
			
			$remaining_message = stristr($message,"%%custom_");
			
			//$remaining_message = tax%% and your ssn is %%custom_ssn%%
			logToFile("functions_email.php: remaining_message=" . $remaining_message);
			
			if($remaining_message) {
				$new_msg = substr($remaining_message,9);
				$pos = strpos($new_msg, "%%");
				//$pos=3
				logToFile("functions_email.php: pos=" . $pos);
						
				if ($pos !== false) {
					
					$custom_field_name = substr($new_msg, 0, intval($pos));
					//custom_field_name = tax
					logToFile("functions_email.php: custom_field_name=" . $custom_field_name);
						
					$user_custom_value = Dap_UserCustomFields::loadUserCustomFieldsByCustomFieldName($custom_field_name, $userId);
					$value = "";
				
					if ($user_custom_value) {
						foreach ($user_custom_value as $val) {
							logToFile("functions_email: loadCustomFields(): val=" . $val['custom_value']);
							$value = $val['custom_value'];
							$message = str_replace("%%custom_" . $custom_field_name . "%%", $value, $message);
				
						} //foreach
					} //if
					else {
							logToFile("functions_email: no custom val for field=" . $custom_field_name);
							$message = str_replace("%%custom_" . $custom_field_name . "%%", $value, $message);
					}
				}
				else { break; }
			} //if
			else break;
		} // for
		
		return $message;
	}

	function personalizeMessageDet($to, $first_name, $last_name, $message, $password=" ") {
		$message = str_replace("%%EMAIL_ID%%", $to, $message);
		$message = str_replace("%%FIRST_NAME%%", $first_name, $message);
		$message = str_replace("%%LAST_NAME%%", $last_name, $message);
		$message = str_replace("%%PASSWORD%%", $password, $message);

		$message = personalizeMessageSite($message);
		return stripslashes($message);
	}

	function personalizeMessageSite($message) {
		//TODO: More personalization
		//admin_name, admin_email, site_name_dap, site_url_dap
		$admin_name = Dap_Config::get("ADMIN_NAME");
		$admin_email = getAdminEmail();
		$site_name_dap = Dap_Config::get("SITE_NAME");
		$site_url_dap = Dap_Config::get("SITE_URL_DAP");
		$login_url = Dap_Config::get("LOGIN_URL");
		$message = str_replace("%%OPTOUT_LINK%%", "%%SITE_URL_DAP%%/dap/", $message);
		$message = str_replace("%%ADMIN_NAME%%", $admin_name, $message);
		$message = str_replace("%%ADMIN_EMAIL%%", $admin_email, $message);
		$message = str_replace("%%SITE_NAME%%", $site_name_dap, $message);
		$message = str_replace("%%SITE_URL_DAP%%", $site_url_dap, $message);
		$message = str_replace("%%LOGIN_URL%%", $login_url, $message);
		return stripslashes($message);
	}
	
	//Personalize the message based on information from passed in product.
	// assumed to have product always, so caller need to ensure that.
	function personalizeMessageProduct($product, $message) {
		//get product, personalize PRODUCT_NAME, PRODUCT_SALE_URL
		$product_name = $product->getName();
		$product_sale_url = $product->getSales_page_url();
		$message = str_replace("%%PRODUCT_NAME%%", $product_name, $message);
		$message = str_replace("%%PRODUCT_SALE_URL%%", $product_sale_url , $message);
		return stripslashes($message);
	}
	
	
	function personalizeMessageUserProduct($user, $product, $message="") {
		logToFile("in personalizeMessageUserProduct"); 
		$userId = $user->getId();
		$productId = $product->getId();
		$userProduct = Dap_UsersProducts::load($userId, $productId);
		if(is_null($userProduct)) return $message;
		
		$product_name = $product->getName();
		$product_sale_url = $product->getSales_page_url();
		$accessStartDate = $userProduct->getAccess_start_date();
		$accessEndDate = $userProduct->getAccess_end_date();
		$timeNow = strtotime("now");
		$accessEndTime = strtotime($accessEndDate);
		$daysToExpiry = ceil(abs($accessEndTime - $timeNow) / 86400);
		//$daysToExpiry = ceil(($accessEndTime - $timeNow) / 86400);
		logToFile("accessStartDate: $accessStartDate , accessEndDate: $accessEndDate , daysToExpiry: $daysToExpiry"); 
		
		$message = str_replace("%%PRODUCT_NAME%%", $product_name, $message);
		$message = str_replace("%%PRODUCT_SALE_URL%%", $product_sale_url , $message);
		$message = str_replace("%%ACCESS_START_DATE%%", $accessStartDate, $message);
		$message = str_replace("%%ACCESS_END_DATE%%", $accessEndDate, $message);
		$message = str_replace("%%NUM_DAYS_TO_EXPIRY%%", $daysToExpiry, $message);
		
		return stripslashes($message);
	}	
	

	function getAdminEmail() {
		$admin_email = Dap_Config::get("ADMIN_EMAIL");
		logToFile("getAdminEmail: Admin Email in config: $admin_email");
		if(strstr($admin_email,",")) {
		  logToFile("getAdminEmail: found comma seperated list of admin emails");
		  $emails = explode(",",$admin_email);
		  $size = sizeof($emails);
		  logToFile("getAdminEmail: Admin Emails: $admin_email , list size: $size, Email: $emails[0]");
		  if($size > 0) {
			  return $emails[0];
		  }
			}
		else return $admin_email;
	}
	

	function sendPasswordByEmail($email) {
		$_SESSION['email'] = $email;
		$user = Dap_User::loadUserByEmail($email);
		if(isset($user)) {
			$subject = Dap_Config::get("FORGOT_PASSWORD_EMAIL_SUBJECT");
			$body = Dap_Templates::getContentByName("FORGOT_PASSWORD_EMAIL_CONTENT");
			if(empty($body) === FALSE) {
				//$body = getDataFromFile($filename); //Dap_Config::get("PASSWORD_REMINDER_BODY");
				$body = personalizeMessage($user, $body);
				$body = str_replace("%%PASSWORD%%", $user->getPassword(), $body);
				$to = $user->getEmail();
				$body = stripslashes($body);
				$subject = stripslashes($subject);
				
				//$to = mb_convert_encoding($to, "UTF-8", "ISO-8859-1");
				//$body = mb_convert_encoding($body, "UTF-8", "ISO-8859-1");
				//$subject = mb_convert_encoding($subject, "UTF-8", "ISO-8859-1");
				
				sendEmail($to, $subject, $body);
				$output = "MSG_PASSWORD_SENT";
				return $output;
  			}
		} else {
			logToFile("ERROR..PasswordByEmail Request: No User Exists for Email: $email", LOG_FATAL_DAP);
			//logToFile("htmlentities(email): " . urlencode($email)); 
			//$output = mb_convert_encoding(MSG_SORRY_EMAIL_NOT_FOUND, "UTF-8", "ISO-8859-1") . " '" . urlencode($email) . "'.";
			$output = "MSG_SORRY_EMAIL_NOT_FOUND";
			return $output;
		}
	}

	function getDataFromFile($filename) {
		if(is_readable($filename)) {
			$file = fopen($filename,'r');
			$data = fread($file,filesize($filename));
			fclose($file);
		} else {
			logToFile("Error In Sending(template not found) Email To:".$email, LOG_FATAL_DAP);
		}
		return $data;
	}

	function getEmailFooter() {
		$body = Dap_Templates::getContentByName("EMAIL_FOOTER_CONTENT");
		if(empty($body) === FALSE) {
  		$body = personalizeMessageSite($body);
  		return $body;
  		}
	}

	function getEmailDelay() {
		$emails_count = Dap_Config::get("EMAIL_THOTTLE_LIMIT");
		if($emails_count == 0) return 0;
		$sleep_time = 3000 / $emails_count;
		if($sleep_time < 1) return 0;
		return $sleep_time;
	}

	function getEmailBatchSize() {
		$emails_count = Dap_Config::get("EMAIL_THROTTLE_LIMIT");
		if($emails_count == 0) return 60;
		return $emails_count;
	}
	
	
	function validateEmailFormat($email)
	{
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    	  return true;
		}
		else return false;
		
		/*if (!preg_match("/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,4})$/", $email)){
			return false;
		} else {
			return true;
		}		*/
	}


	function sendAffiliateNotificationEmail($affiliate_id, $product_id, $user_id, $earning_type = "S", $amountEarned="0.00") {
		logToFile("Sending Affiliate Notification Email");
		logToFile("$affiliate_id, $product_id, $user_id, $earning_type, $amountEarned");
		//$data = Dap_Templates::getContentByName("USERPRODUCT_WELCOME_EMAIL_CONTENT");
		//$subject = Dap_Templates::getContentByName("USERPRODUCT_WELCOME_EMAIL_SUBJECT");		
		$subject = ($earning_type == "L") ? 
			Dap_Templates::getContentByName("AFF_NOTIF_LEAD_SUBJ") :
			Dap_Templates::getContentByName("AFF_NOTIF_SALE_SUBJ") ;
		$bodyText = ($earning_type == "L") ? 
			Dap_Templates::getContentByName("AFF_NOTIF_LEAD_BODY") :
			Dap_Templates::getContentByName("AFF_NOTIF_SALE_BODY") ;
		
		$product = Dap_Product::loadProduct($product_id);
		$affiliate = Dap_User::loadUserById($affiliate_id);
		$user = Dap_User::loadUserById($user_id);
		$amountEarned = isset($amountEarned) ? number_format($amountEarned,2) : "0.00";
		
		if( (empty($user) === FALSE) || (empty($bodyText) === FALSE) || (empty($product) === FALSE)) {
			$subject = personalizeMessage($affiliate, $subject);			
			$subject = personalizeMessageProduct($product, $subject);			
			$subject = str_replace("%%AMOUNT_EARNED%%", Dap_Config::get("CURRENCY_SYMBOL").$amountEarned, $subject);
			$subject = stripslashes($subject);
			
			$bodyText = personalizeMessage($affiliate, $bodyText);
			$bodyText = personalizeMessageProduct($product, $bodyText);
			$bodyText = str_replace("%%AMOUNT_EARNED%%", Dap_Config::get("CURRENCY_SYMBOL").$amountEarned, $bodyText);
			$bodyText = str_replace("%%BUYER_FIRST_NAME%%", $user->getFirst_name(), $bodyText);
			$bodyText = str_replace("%%BUYER_LAST_NAME%%", $user->getLast_name(), $bodyText);
			$bodyText = str_replace("%%BUYER_EMAIL%%", $user->getEmail(), $bodyText);
			$bodyText = stripslashes($bodyText);
				
			if(isset($affiliate)) {
				$affemail=$affiliate->getEmail();
				if($affemail!="")
					sendEmail($affiliate->getEmail(), $subject, $bodyText);
				logToFile("Sent Affiliate Notification Email To: ".$affiliate->getEmail(), LOG_INFO_DAP);
			}
			
			//sendEmail($affiliate->getEmail(), $subject, $bodyText);
			logToFile("Sent Affiliate Notification Email To: ".$affiliate->getEmail(), LOG_INFO_DAP);
		} else {
			logToFile("ERROR..Sending UserProduct Notification Email. UserId: $user_id ", LOG_FATAL_DAP);
		}
	}
	
	function sendCardExpirationEmail($user, $product) {
		$userId=$user->getId();
		
		logToFile("Sending Card Expiration Notification Email to $userId");
		//$data = Dap_Templates::getContentByName("USERPRODUCT_WELCOME_EMAIL_CONTENT");
		//$subject = Dap_Templates::getContentByName("USERPRODUCT_WELCOME_EMAIL_SUBJECT");		
		$subject = Dap_Templates::getContentByName("CARD_EXPIRATION_REMINDER_SUBJ");
		$bodyText = Dap_Templates::getContentByName("CARD_EXPIRATION_REMINDER_BODY");
		if( (empty($user) === FALSE) || (empty($bodyText) === FALSE) || (empty($subject) === FALSE)) {
			$subject = personalizeMessage($user, $subject);			
			$subject = stripslashes($subject);
				
			$bodyText = personalizeMessage($user, $bodyText);
			$bodyText = stripslashes($bodyText);
					
			$ret=sendEmail($user->getEmail(), $subject, $bodyText);
			logToFile("sendCardExpirationEmail: sendEmail returned " . $ret, LOG_FATAL_DAP);
			return $ret;
			//if($ret=="")
				//logToFile("Sent Card Expiration Notification Email Successfully To: ".$user->getEmail(), LOG_INFO_DAP);
		}
		else {
			$ret="ERROR..Sending Card Expiration Notification Email. UserId: $user_id. Missing card expiration email subject/body in DAP setup -> templates";
			logToFile("ERROR..Sending Card Expiration Notification Email. UserId: $user_id ", LOG_FATAL_DAP);
		}
		
		//logToFile("sendCardExpirationEmail: returning " . $ret, LOG_FATAL_DAP);
		return $ret;
	}

?>
