<?php
//System Wide Configs
define('LOG_KEEP_DAYS','1'); //How long to keep logs after which they will be deleted
define('ACCESS_CACHE_CONTROL_DB', 'N');

//Email Stuff
define ('USERPRODUCT_NOTIFY_EMAIL_SUBJECT', 'New User Signup (3rd Party Notification)');

//User Error Messages
define('ERROR_DB_CONNECTION', 'Oops, could not connect to the database. Please try again or contact site owner.');
define('ERROR_DB_OPERATION','Oops, an error occurred in the database. Please try again or contact site owner<br/><br/>');
define('ERROR_GENERAL', 'Oops, the system caused a boo-boo. Please try again or contact site owner.');

//Miscellaneous
define('CUSTOM_CSS','customuserfacing.css');
define('CUSTOM_CART_CSS','customdapcart.css');
define('CUSTOM_CHECKOUT_CSS','customcheckoutconfirm.css');
define('CUSTOM_SSS_CSS','customselfservice.css');

define ('ERROR_PAGE', 'error.php');
?>