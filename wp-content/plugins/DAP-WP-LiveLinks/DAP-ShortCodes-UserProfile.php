<?php 

add_shortcode('DAPUserProfile', 'dap_userprofile');
add_action( 'wp_enqueue_scripts', 'addUserProfileJS' );

function addUserProfileJS() {
	$url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'] : "http://".$_SERVER['SERVER_NAME'];	
	wp_register_script('dap-ajaxWrapper', $url . '/dap/javascript/ajaxWrapper.js');
	wp_register_script('dap-common', $url . '/dap/javascript/common.js');
	wp_register_script('dap-UserProfile', $url . '/dap/javascript/UserProfileShortcode.js');
	//wp_register_script('dap-jsstrings', $url . '/dap/javascript/jsstrings.php');
    wp_enqueue_script( 'ajaxWrapper' );
    wp_enqueue_script( 'dap-common' );
    wp_enqueue_script( 'dap-UserProfile' );
    //wp_enqueue_script( 'dap-jsstrings' );

}

/**

	[DAPUserProfile showFirstName="Y" showLastName="Y" showCustomFields="cbnick" redirect="http://YourSite.com/url"]
	
	-OR-
	
	[DAPUserProfile 
	 showFirstName="Y" 
	 showLastName="Y" 
	 showUserName="Y" 
	 showEmail="Y"
	 showPassword="Y"
	 showAddress1="Y"
	 showAddress2="Y"
	 showCity="Y"
	 showState="Y"
	 showZip="Y"
	 showCountry="Y"
	 showPhone="Y"
	 showFax="Y"
	 showCompany="Y"
	 showTitle="Y"
	 showPaypalEmail="Y"
	 showOptedOut="Y"
	 showCustomFields="Y"]
*/

function dap_userprofile($atts, $content=null){ 
	extract(shortcode_atts(array(
		'showfirstname' => 'Y',
		'showlastname' => 'Y',
		'showusername' => 'Y',
		'showemail' => 'Y',
		'showpassword' => 'Y',
		'showaddress1' => 'Y',
		'showaddress2' => 'Y',
		'showcity' => 'Y',
		'showstate' => 'Y',
		'showzip' => 'Y',
		'showcountry' => 'Y',
		'showphone' => 'Y',
		'showfax' => 'Y',
		'showcompany' => 'Y',
		'showtitle' => 'Y',
		'showpaypalemail' => 'Y',
		'showoptedout' => 'Y',
		'showcustomfields' => 'Y',
		'redirect' => ''
	), $atts));
	
	$content = do_shortcode(dap_clean_shortcode_content($content));	
	$requestURI = explode("?",$_SERVER['REQUEST_URI']);
	$redirectTo = ($redirect == "") ? $requestURI[0] : $redirect;
	
	$session = Dap_Session::getSession();
	$user = $session->getUser();
	//$content = $content . "<br/><br/>";
	
	if( !Dap_Session::isLoggedIn() || !isset($user) ) {
		//logToFile("Not logged in, returning errmsgtemplate");
		$errorHTML = mb_convert_encoding(MSG_PLS_LOGIN, "UTF-8", "auto") . " <a href=\"" . Dap_Config::get("LOGIN_URL") . "\">". mb_convert_encoding(MSG_CLICK_HERE_TO_LOGIN, "UTF-8", "auto") . "</a>";
		return $errorHTML;
	}
	
	$userId = $user->getId();
	$user = Dap_User::loadUserById($user->getId());
	$content = '<script language="javascript" type="text/javascript" src="/dap/javascript/jsstrings.php"></script>';
	if( isset($_GET['msg']) && ($_GET['msg'] != "") ) {
		if( defined($_GET['msg']) ) {
			$_GET['msg'] = constant($_GET['msg']);
		} else {
			$_GET['msg'] = str_replace("_"," ",$_GET['msg']);
		}
		$content .= '<div class="confirmationMessage">'.$_GET['msg'].'</div>';
	}	
	$content .= '<form name="UserProfileForm" id="UserProfileForm" method="post" onSubmit="return updateUser(this);">';
	
	if($showuserprofileheading == 'Y') {
		$content .= '<div align="center" class="dap_userprofile_heading">
		'. USER_PROFILE_HEADING_TEXT. '</div>';
	}
	
  	$content .= '<div align="center" id="user_msg_div" class="bodytextLarge"></div>
  	<table class="dap_user_profile_table">';
  
  	
	//Start creating form fields
	if($showfirstname == "Y") {
		$content .= '
	<tr>
      <td valign="top"><div class="userProfileLabel">'. USER_PROFILE_FIRST_NAME_LABEL. '</div></td>
      <td valign="top"><input class="userProfileField" realname="First Name" required="Y" name="u_first_name" type="text" id="u_first_name" size="20" value="'.$user->getFirst_name().'"></td>
    </tr>
	';
	}

  	if($showlastname == "Y") {
		$content .= '
    <tr>
      <td valign="top"><div class="userProfileLabel">'.USER_PROFILE_LAST_NAME_LABEL.'</div></td>
      <td valign="top"><input class="userProfileField" realname="Last Name" name="u_last_name" type="text" id="u_last_name" size="20" value="'.$user->getLast_name().'"></td>
    </tr>
	';
	}

  	if($showusername == "Y") {
		$readonly = "";
		$style = "";
		if($user->getUser_name() != "") {
			$readonly = ' readonly="" ';
			$style='style="background-color: #CCCCCC;"';
		}
		$content .= '
    <tr>
      <td valign="top"><div class="userProfileLabel">'.USER_PROFILE_USER_NAME_LABEL.'</div></td>
      <td valign="top"><input class="userProfileField" '.$style.' realname="Username" name="u_user_name" type="text" id="u_user_name" size="20" maxlength="20" ' . $readonly . ' value="'.$user->getUser_name().'"></td>
    </tr>
	';
	}
	
	
	if($showemail == "Y") {
		$content .= '
    <tr>
      <td valign="top"><div class="userProfileLabel">'.USER_PROFILE_EMAIL_LABEL.'</div></td>
      <td valign="top"><input class="userProfileField" realname="Email" required="Y" name="u_email" type="text" id="u_email" size="20" value="'.$user->getEmail().'"></td>
    </tr>
	';
	}


	if($showpassword == "Y") {
		$content .= '
    <tr>
      <td valign="top"><div class="userProfileLabel">'.USER_PROFILE_NEW_PASSWORD_LABEL_DAPUSERPROFILE.'</div></td>
      <td valign="top"><input class="userProfileField" name="u_password" type="password" id="u_password" size="20"></td>
    </tr>
    <tr>
      <td valign="top"><div class="userProfileLabel">'.USER_PROFILE_REPEAT_PASSWORD_LABEL.'</div></td>
      <td valign="top"><input class="userProfileField" name="password_repeat" type="password" id="password_repeat" size="20"></td>
    </tr>
	';
	}
	
	if($showcompany == "Y") {
		$content .= '
    <tr>
      <td valign="top"><div class="userProfileLabel">'.USER_PROFILE_COMPANY_LABEL.'</div></td>
      <td valign="top"><input class="userProfileField" name="u_company" type="text" id="u_company" size="20" value="'.$user->getCompany().'"></td>
    </tr>
	';
	}
	
	if($showtitle == "Y") {
		$content .= '
    <tr>
      <td valign="top"><div class="userProfileLabel">'.USER_PROFILE_TITLE_LABEL.'</div></td>
      <td valign="top"><input class="userProfileField" name="u_title" type="text" id="u_title" size="20" value="'.$user->getTitle().'"></td>
    </tr>
	';
	}
	
	if($showaddress1 == "Y") {
		$content .= '
    <tr>
      <td valign="top"><div class="userProfileLabel">'.USER_PROFILE_ADDRESS1_LABEL.'</div></td>
      <td valign="top"><input class="userProfileField" name="u_address1" type="text" id="u_address1" size="20" value="'.$user->getAddress1().'"></td>
    </tr>
	';
	}
	
	if($showaddress2 == "Y") {
		$content .= '
    <tr>
      <td valign="top"><div class="userProfileLabel">'.USER_PROFILE_ADDRESS2_LABEL.'</div></td>
      <td valign="top"><input class="userProfileField" name="u_address2" type="text" id="u_address2" size="20" value="'.$user->getAddress2().'"></td>
    </tr>
	';
	}
	
	if($showcity == "Y") {
		$content .= '
    <tr>
      <td valign="top"><div class="userProfileLabel">'.USER_PROFILE_CITY_LABEL.'</div></td>
      <td valign="top"><input class="userProfileField" name="u_city" type="text" id="u_city" size="20" value="'.$user->getCity().'"></td>
    </tr>
	';
	}
	
	if($showstate == "Y") {
		$content .= '
    <tr>
      <td valign="top"><div class="userProfileLabel">'.USER_PROFILE_STATE_LABEL.'</div></td>
      <td valign="top"><input class="userProfileField" name="u_state" type="text" id="u_state" size="20" value="'.$user->getState().'"></td>
    </tr>
	';
	}
	
	
	if($showzip == "Y") {
		$content .= '
    <tr>
      <td valign="top"><div class="userProfileLabel">'.USER_PROFILE_ZIP_LABEL.'</div></td>
      <td valign="top"><input class="userProfileField" name="u_zip" type="text" id="u_zip" size="20" value="'.$user->getZip().'"></td>
    </tr>
	';
	}
	
	if($showcountry == "Y") {
		$content .= '
    <tr>
      <td valign="top"><div class="userProfileLabel">'.USER_PROFILE_COUNTRY_LABEL.'</div></td>
      <td valign="top"><input class="userProfileField" name="u_country" type="text" id="u_country" size="20" value="'.$user->getCountry().'"></td>
    </tr>
	';
	}
	
	if($showphone == "Y") {
		$content .= '
    <tr>
      <td valign="top"><div class="userProfileLabel">'.USER_PROFILE_PHONE_LABEL.'</div></td>
      <td valign="top"><input class="userProfileField" name="u_phone" type="text" id="u_phone" size="20" value="'.$user->getPhone().'"></td>
    </tr>
	';
	}
	
	if($showfax == "Y") {
		$content .= '
    <tr>
      <td valign="top"><div class="userProfileLabel">'.USER_PROFILE_FAX_LABEL.'</div></td>
      <td valign="top"><input class="userProfileField" name="u_fax" type="text" id="u_fax" size="20" value="'.$user->getFax().'"></td>
    </tr>
	';
	}
	
	
	if( (Dap_Config::get('DISPLAY_AFFILIATE') == "Y") && ($showpaypalemail == "Y") ) {
		$content .= '
    <tr>
      <td valign="top"><div class="userProfileLabel">'.USER_PROFILE_PAYPAL_EMAIL_LABEL.' <br/> '.USER_PROFILE_PAYPAL_EMAIL_EXTRA_LABEL.'</div></td>
      <td valign="top"><input class="userProfileField" name="u_paypal_email" type="text" id="u_paypal_email" size="20" value="'.$user->getPaypal_email().'"></td>
    </tr>
	';
	}
	
	
	
	
	
  	if($showoptedout == "Y") {
		$isChecked = ($user->getOpted_out() == "N") ? " checked='.$isChecked.' " : "";
		logToFile("isChecked: $isChecked , user->getOpted_out(): " . $user->getOpted_out());
		
		$content .= '
    <tr>
      <td valign="top"><div class="userProfileLabel">'.USER_PROFILE_UNSUBSCRIBE_LABEL.'</div></td>
      <td valign="top"><input name="u_opted_out" type="checkbox" id="u_opted_out" value="N" onClick="doWarning(this.form);" '.$isChecked.'></td>
    </tr>
	';
	}
	
	
	
	if( ($showcustomfields == "Y") || ($showcustomfields != "") ) {
		$customFieldsArray = null;
		if($showcustomfields != "Y") {
			$customFieldsArray = explode(",",$showcustomfields);
		}
		
		$customFields = Dap_CustomFields::loadUserFacingCustomFields();
		foreach ($customFields as $custom) {
			logToFile("userprofile.inc.php: loadCustomFields(): id =" . $custom['id']);
			logToFile("userprofile.inc.php: loadCustomFields(): userId =" . $userId);
			$required = " ";
			$requiredStar = "";

			if ($custom['showonlytoadmin'] == "Y") {
				continue;
			}
			
			//logToFile("custom[name]: " . $custom['name']); 
			if ( !is_null($customFieldsArray) && !in_array($custom['name'],$customFieldsArray) ){
				continue;
			}
			
			if($custom['required'] == "Y") {
				$required = "required";
				$requiredStar = "*";
			}
			
			$user_custom_value = Dap_UserCustomFields::loadUserCustomFieldsByCustomFieldId($custom['id'], $userId);
			$value = "";
			
			if ($user_custom_value) {
				foreach ($user_custom_value as $val) {
					//logToFile("val=" . $val['custom_value']);
					$value = $val['custom_value'];
				}
			}
			
			$content .= '<tr valign="top">
			  <td><div class="userProfileLabel">'.$custom['label'].$requiredStar.'</div></td>
			  <td><input class="userProfileField" name="custom_'.$custom["name"] . '" ' . $required . ' realname="'.$custom["label"].'" type="text" id="custom_'.$custom['name'].'" value="'.$value.'"  size="20"></td>
			</tr>';
			
		} //end foreach	
	}
	
	$content .= '<tr>
      <td colspan="2" valign="top">
      	<div align="center">
          <input class="userProfileSubmitButton" name="button_edit_user" type="submit" id="button_edit_user" value="'.BUTTON_UPDATE.'">
        </div><br/><div align="center" id="user_msg_div_2" class="bodytextLarge"></div></td>
    </tr>';
	
	$content .= '</table>
	
	<input type="hidden" name="redirect" value="' . $redirectTo . '">
	</form>
	' . MSG_REQUIRED_FIELD;

	return $content;
}


?>