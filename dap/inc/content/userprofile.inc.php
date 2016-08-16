<?php
	if( Dap_Session::isLoggedIn() ) { 
		$session = Dap_Session::getSession();
		$user = $session->getUser();
		if ($user) $userId = $user->getId();
	}
?>
<script language="javascript" type="text/javascript" src="/dap/javascript/ajaxWrapper.js"></script>
<script language="javascript" type="text/javascript" src="/dap/javascript/common.js"></script>
<script language="javascript" type="text/javascript" src="/dap/javascript/UserProfile.js"></script>
<script language="javascript" type="text/javascript" src="/dap/javascript/jsstrings.php"></script>
<script language="javascript">
var affId = <?php echo $user->getId(); ?>;
var site_url = "<?php echo SITE_URL_DAP; ?>";
</script>
<script language="javascript">
var customArray = new Array();
function addCustomValue(customFieldName) {
	var name = customFieldName.name;
	var value = customFieldName.value;
	
	//alert ("customFieldName=" + name);
	//alert ("customFieldName=" + value);
	var namevalue = name + "||" + value;
	customArray = removeItems(customArray, namevalue);
	customArray.push(namevalue);
	document.UserProfileForm.customArray.value = customArray;
   //alert ("back - customArray=" + customArray);
	
}
function removeItems(array, item) {
	var i = 0;
	while (i < array.length) {
	//	alert("array=" + array[i]);
	//	alert("item=" + item);
		if (array[i] == item) {
			array.splice(i, 1);
		} else {
			i++;
		}
	}
	return array;
}
</script>
<?php getCss(); ?>

<form name="UserProfileForm" id="UserProfileForm" action="">
  <a name="userprofile"></a>
  <div align="center" class="dap_userprofile_heading"><?php echo USER_PROFILE_HEADING_TEXT; ?></div>
  <div align="center" id="user_msg_div" class="bodytextLarge"></div>
  <table class="dap_user_profile_table">
    <tr>
      <td valign="top"><div align="left"><?php echo USER_PROFILE_FIRST_NAME_LABEL; ?></div></td>
      <td valign="top"><input name="first_name" type="text" id="first_name" size="20"></td>
    </tr>
    <tr>
      <td valign="top"><div align="left"><?php echo USER_PROFILE_LAST_NAME_LABEL; ?></div></td>
      <td valign="top"><input name="last_name" type="text" id="last_name" size="20"></td>
    </tr>
    <tr>
      <td valign="top"><div align="left"><?php echo USER_PROFILE_EMAIL_LABEL; ?></div></td>
      <td valign="top"><input name="email" type="text" id="email" size="20"></td>
    </tr>
    <tr>
      <td valign="top"><div align="left"><?php echo USER_PROFILE_USER_NAME_LABEL; ?></div></td>
      <td valign="top"><input name="user_name" type="text" id="user_name" size="20" maxlength="20"></td>
    </tr>
    <tr>
      <td valign="top"><div align="left"><?php echo USER_PROFILE_NEW_PASSWORD_LABEL; ?></div></td>
      <td valign="top"><input name="password" type="password" id="password" size="20"></td>
    </tr>
    <tr>
      <td valign="top"><div align="left"><?php echo USER_PROFILE_REPEAT_PASSWORD_LABEL; ?></div></td>
      <td valign="top"><input name="password_repeat" type="password" id="password_repeat" size="20"></td>
    </tr>
    <?php if( Dap_Config::get('DISPLAY_AFFILIATE') == "Y" ) { ?>
    <tr>
      <td valign="top"><div align="left">
          <p><?php echo USER_PROFILE_PAYPAL_EMAIL_LABEL; ?> <br />
            <?php echo USER_PROFILE_PAYPAL_EMAIL_EXTRA_LABEL; ?></p>
        </div></td>
      <td valign="top"><input name="paypal_email" type="text" id="paypal_email" size="20"></td>
    </tr>
    <?php } ?>
    <?php include (DAP_ROOT . "inc/content/profileExtended.inc.php"); ?>
    <?php 
				$session = Dap_Session::getSession();
				//$userCustomFields = Dap_UserCustomFields::loadUserCustomFields($userId);
				$customFields = Dap_CustomFields::loadUserFacingCustomFields();
				foreach ($customFields as $custom) {
					logToFile("userprofile.inc.php: loadCustomFields(): id=" . $custom['id']);
					logToFile("userprofile.inc.php: loadCustomFields(): userId=" . $userId);
					if ($custom['showonlytoadmin'] == "Y") {
							if (!$session->isAdmin()) 	{
								continue;
						}
					}
					$user_custom_value = Dap_UserCustomFields::loadUserCustomFieldsByCustomFieldId($custom['id'], $userId);
					$value = "";
					if ($user_custom_value) {
						foreach ($user_custom_value as $val) {
							logToFile("userprofile.inc.php: 	(): val=" . $val['custom_value']);
							$value = $val['custom_value'];
						}
					}
?>
    <tr valign="top">
      <td><div align="left"><?php echo $custom['label'] ?></div></td>
      <td><input name="<?php echo $custom['name']?>" type="text" id="<?php echo $custom['name']?>" value="<?php echo $value; ?>"  size="20" onChange="addCustomValue(<?php echo $custom['name']; ?>)"></td>
    </tr>
    <?php
	} //end foreach
?>
    <tr>
      <td valign="top"><div align="left"><?php echo USER_PROFILE_UNSUBSCRIBE_LABEL; ?></div></td>
      <td valign="top"><input name="opted_out" type="checkbox" id="opted_out" value="N" onClick="doWarning(this.form);"></td>
    </tr>
    <tr>
      <td colspan="2" valign="top">
      	<div align="center">
          <input name="button_edit_user" type="button" id="button_edit_user" value="<?php echo BUTTON_UPDATE; ?>" onClick="updateUser(this.form);">
        </div><br/><div align="center" id="user_msg_div_2" class="bodytextLarge"></div></td>
    </tr>
  </table>
  <?php if( Dap_Config::get('DISPLAY_AFFILIATE') != "Y" ) { ?>
  <input name="paypal_email" type="hidden" id="paypal_email" value="">
  <?php } ?>
  <input type="hidden" name="customArray" value="">
</form>
<p>&nbsp;</p>
<?php 
	if(file_exists(DAP_ROOT . "inc/content/photoUpload.inc.php")) {
		include (DAP_ROOT . "inc/content/photoUpload.inc.php");
	}
?>
<script language="javascript">
	loadUser();
</script>
