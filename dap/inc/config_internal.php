<?php
//Pls do not modify anything in this file
define('DAP_VERSION', '4.7.2');
$versions = array(
		'0.1' => '00to01.php',
		'0.2' => '01to02.php',
		'0.3' => '02to03.php', 
		'0.4' => '03to04.php',
		'0.5' => '04to05.php',
		'0.6' => '05to06.php',
		'0.7' => '06to07.php',
		'0.8' => '07to08.php',
		'0.9' => '08to09.php',
		'1.0' => '09to10.php',
		'2.0' => '1.0to2.0.php',
		'2.1' => '2.0to2.1.php',
		'2.2' => '2.1to2.2.php',
		'2.3' => '2.2to2.3.php',
		'3.0' => '2.3to3.0.php',
		'3.1' => '3.0to3.1.php',
		'3.2' => '3.1to3.2.php',
		'3.3' => '3.2to3.3.php',
		'3.4' => '3.3to3.4.php',
		'3.5' => '3.4to3.5.php',
		'3.6' => '3.5to3.6.php',
		'3.7' => '3.6to3.7.php',
		'3.8' => '3.7to3.8.php',
		'3.9' => '3.8to3.9.php',
		'4.0' => '3.9to4.0.php',
		'4.1' => '4.0to4.1.php',
		'4.2' => '4.1to4.2.php',
		'4.2.1' => '4.2to4.2.1.php',
		'4.3' => '4.2.1to4.3.php',
		'4.3.1' => '4.3to4.3.1.php',
		'4.4' => '4.3.1to4.4.php',
		'4.4.1' => '4.4to4.4.1.php',
		'4.4.2' => '4.4.1to4.4.2.php',
		'4.4.3' => '4.4.2to4.4.3.php',
		'4.5' => '4.4.3to4.5.php',
		'4.5.1' => '4.5to4.5.1.php',
		'4.5.2' => '4.5.1to4.5.2.php',
		'4.6' => '4.5.2to4.6.php',
		'4.6.1' => '4.6to4.6.1.php',
		'4.6.2' => '4.6.1to4.6.2.php',
		'4.7' => '4.6.2to4.7.php',
		'4.7.1' => '4.7to4.7.1.php',
		'4.7.2' => '4.7.1to4.7.2.php'
		);

define('DAP_CONTENT', '/inc/content');
define ('LOGIN_PAGE', 'login.php');
define('BULKFOLDER', "bulk");
define('ENCKEY', "MCRYPTRIJNDAEL256");
define('TEMPLATES_WEB', "/templates/web");
define('MEMBER_HOME_PAGE', "index.php");
define('SN_OPEN_URIS','Open_URIs');
define('SN_AUTH_URIS','Authenticated_URIs');

//Logging related
define('LOG_DEBUG_DAP', 5);
define('LOG_INFO_DAP', 4);
define('LOG_WARNING_DAP', 3);
define('LOG_ERROR_DAP', 2);
define('LOG_FATAL_DAP', 1);

//Admin Error Messages
define('ERROR_DB_RESOURCE_ALREADY_ASSIGNED','Already added:');
define('ERROR_DB_RESOURCE_ALREADY_ASSIGNED_SAME_COMBO','<strong>That email has already been set to go out on Day #1. <br/>First change that to a later day, and then add it again.</strong><br/><br/>');
define('ERROR_DB_INSERT','Oops, could not insert into the database. Please try again or contact site owner<br/><br/>');
define('ERROR_DB_DELETE','Oops, could not delete data from the database. Please try again or contact site owner<br/><br/>');
define('INFO_FILE_DELETED', '<b>SUCCESS! The content has been deleted from this Product.</b><br/><br/>');
define('INFO_FILES_DELETED', '<b>SUCCESS! All protected content has been deleted from this Product.</b><br/><br/>');
define('INFO_EMAIL_DELETED', '<b>SUCCESS! The email has been deleted from this Product.</b><br/><br/>');
define('INFO_EMAILS_DELETED', '<b>SUCCESS! All emails have been deleted from this Product.</b><br/><br/>');
define('ERROR_DB_INSTALLATION', '<b>Oops, there was a problem with your database settings (incorrect host name, user name or password). <br/>Please double check and re-try.</b><br/><br/>');
define('ERROR_CONFIG_EXISTS', '<b>Oops, there is already a file by name "dap-config.php" in your "dap" directory. Please delete that first and re-try.</b><br/><br/>');
define('ADMIN_SUBJECT_PREFIX', '[DAP] ');
define ('PAYMENT_NOTIFY_EMAIL_SUBJECT', 'Payment Received. Processor Is: %%PAYMENT_PROCESSOR%%');

define('HTMLSEPARATOR','[HTML_START]');

?>