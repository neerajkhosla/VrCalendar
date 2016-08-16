<?php
	$configFile = "../dap-config.php";
?>

<html>
<head>
<title>Installation: DigitalAccessPass.com</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link href="../inc/content/dap.css" rel="stylesheet" type="text/css">
<script language="javascript" type="text/javascript" src="../javascript/ajaxWrapper.js"></script>
<script language="javascript" type="text/javascript" src="../javascript/common.js"></script>
<script language="javascript">

function processChange(responseText, responseStatus, responseXML) {
	if (responseStatus == 200) {// 200 means "OK"
		var resource = eval('(' + responseText + ')');
		if(resource.whatRequest == 'createConfig') {
			changeDiv("create_config_div", resource.responseJSON);
			if ( (resource.responseJSON).indexOf("Oops") == -1 ) {
				copyTemplateFiles();
			}
		} else if(resource.whatRequest == 'copyTemplateFiles') {
			changeDiv("copy_template_files_div", resource.responseJSON);
			if ( (resource.responseJSON).indexOf("Oops") == -1 ) {
				createDBTables();
			}
		}  else if(resource.whatRequest == 'createDBTables') {
			changeDiv("create_dbtables_div", resource.responseJSON);
		} 
	} else {// anything else means a problem
		alert("There was a problem in the returned data:\n");
	}
}


function copyTemplateFiles() {
	changeDiv("copy_template_files_div",'Please wait... Copying Template Files...<br><img src="../images/progressbar.gif">');
	url  =  'ajax/copyTemplateFilesAjax.php';
	var request = new ajaxObject(url,processChange);
	request.update(
		'DB_NAME_DAP=' + form.DB_NAME_DAP.value + 
		'&DB_USER_DAP=' + form.DB_USER_DAP.value + 
		'&DB_PASSWORD_DAP=' + form.DB_PASSWORD_DAP.value + 
		'&DB_HOST_DAP=' + form.DB_HOST_DAP.value + 
		'&SITE_URL_DAP=' + form.SITE_URL_DAP.value + 
		'&whatRequest=copyTemplateFiles',
		'POST'
	);
}


function createConfig() {
	form = document.ConfigForm;
	clearDiv("create_config_div");
	clearDiv("create_dbtables_div");
	clearDiv("copy_template_files_div");
	
	if(!validateForm(form)) {
		return false;
	}
	changeDiv("create_config_div",'Please wait... Creating Configuration File...<br><img src="../images/progressbar.gif">');
	url  =  'ajax/createConfigAjax.php';
	var request = new ajaxObject(url,processChange);
	request.update(
		'DB_NAME_DAP=' + form.DB_NAME_DAP.value + 
		'&DB_USER_DAP=' + form.DB_USER_DAP.value + 
		'&DB_PASSWORD_DAP=' + form.DB_PASSWORD_DAP.value + 
		'&DB_HOST_DAP=' + form.DB_HOST_DAP.value + 
		'&SITE_URL_DAP=' + form.SITE_URL_DAP.value + 
		'&whatRequest=createConfig',
		'POST'
	);
}

function validateForm(form) {
	for(i=0; i<form.elements.length; i++){
		if( (form.elements[i].name != "Submit") && (form.elements[i].name != "mode") ) {
			//alert(form.elements[i].name);
			if(form.elements[i].value == "") {
				alert("Sorry, '" + form.elements[i].id + "' is a required field");
				form.elements[i].focus();
				return false;
			}
		}
   	}

	if( form.SITE_URL_DAP.value.indexOf("http") == -1 ) {
		alert("Sorry, '" + form.SITE_URL_DAP.id + "' must start with 'http://'");
		form.SITE_URL_DAP.focus();
		return false;
	}
	
	return true;
}

function changeDiv(divName, response) {
	document.getElementById(divName).innerHTML = response;
	NLBfadeBg(divName,'#009900','#FFFFFF','1000');
}

function createDBTables() {
	changeDiv("create_dbtables_div", 'Please wait... Creating Database Tables...<br><img src="../images/progressbar.gif">');
	form = document.ConfigForm;
	url = 'ajax/createDBTablesAjax.php';
	var request = new ajaxObject(url,processChange);
	request.update(
		'ADMIN_FIRSTNAME_DAP=' + form.ADMIN_FIRSTNAME_DAP.value + 
		'&ADMIN_EMAIL_DAP=' + form.ADMIN_EMAIL_DAP.value + 
		'&whatRequest=createDBTables');
}

</script>
</head>
<body bgcolor="#FFFFFF"> 
<table width="100%" border="0" cellspacing="5" cellpadding="5"> 
  <tr> 
    <td> <p><font color="#336699" size="4" face="Verdana, Arial, Helvetica, sans-serif" class="headingSuperRed">&quot;One-Page, One-Click&quot; Installation of DigitalAccessPass.com</font></p> 
      <p><font face="Arial, Helvetica, sans-serif" size="2">For more details about what DigitalAccessPass is, and can do, visit <a href="http://www.DigitalAccessPass.com" target="_blank">DigitalAccessPass.com</a>.</font>      </p> 
      <form action="" method="post" name="ConfigForm"> 
        <table width="100%" border="0" cellpadding="3"> 
          <tr class="gb"> 
            <td width="180" valign="top" class="BodyText">Database Name</td> 
            <td width="231"><input name="DB_NAME_DAP" type="text" id="Database Name" value="" size="30" maxlength="30"></td> 
            <td width="483">&nbsp;</td> 
          </tr> 
          <tr class="gb"> 
            <td valign="top" class="BodyText">Database User Name </td> 
            <td><input name="DB_USER_DAP" type="text" id="Database User Name" value="" size="30" maxlength="30"></td> 
            <td>&nbsp;</td> 
          </tr> 
          <tr class="gb"> 
            <td valign="top" class="BodyText">Database Password </td> 
            <td><input name="DB_PASSWORD_DAP" type="text" id="Database Password" value="" size="30" maxlength="30"></td> 
            <td>&nbsp;</td> 
          </tr> 
          <tr class="gb"> 
            <td valign="top" class="BodyText">Database Host </td> 
            <td><input name="DB_HOST_DAP" type="text" id="Database Host Name" value="" size="30"></td> 
            <td><span class="BodyText"><strong>localhost</strong> (or) <strong>localhost.example.com</strong></span></td> 
          </tr> 
          <tr class="gb"> 
            <td valign="top" class="BodyText">Your Website's URL</td> 
            <td><input name="SITE_URL_DAP" type="text" id="Web Site URL" value="" size="30"> </td> 
            <td><span class="BodyText">(<strong>http://www.Example.com</strong>)</span></td> 
          </tr> 
          <tr class="gb"> 
            <td valign="top" class="BodyText">Admin First Name </td> 
            <td><input name="ADMIN_FIRSTNAME_DAP" type="text" id="Admin First Name" value="" size="30" maxlength="40"></td> 
            <td><span class="BodyText">(<strong>Your Name</strong>)</span></td> 
          </tr> 
          <tr class="gb"> 
            <td valign="top" class="BodyText">Admin Email </td> 
            <td><input name="ADMIN_EMAIL_DAP" type="text" id="Admin Email" value="" size="30" maxlength="40"></td> 
            <td><span class="BodyText">(<strong>You@YourSite.com</strong>)</span></td> 
          </tr> 
          <tr class="gb"> 
            <td width="180" valign="top" class="BodyText">&nbsp;</td> 
            <td width="231"> <input type="button" name="Submit" value="Start Installation" onClick="createDBTables();"> </td> 
            <td width="483">&nbsp;</td> 
          </tr> 
        </table> 
        <input type="hidden" name="mode" value="SUBMITTED"> 
      </form> 
      </p> 
      <ol> 
        <li> 
          <div id="create_config_div"></div> 
        </li> <br/>
        <li> 
          <div id="copy_template_files_div"></div> 
        </li> 
        <li> 
          <div id="create_dbtables_div"></div> 
        </li> 
      </ol></td> 
  </tr> 
</table> 
</body>
</html>
