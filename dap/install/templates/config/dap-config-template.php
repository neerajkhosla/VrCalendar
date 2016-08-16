<?php

@date_default_timezone_set('America/Los_Angeles');

ini_set("memory_limit","128M");
ini_set('display_errors', '0');
error_reporting(0);

// ** MySQL settings ** //
define('DB_NAME_DAP', '%%DB_NAME_DAP%%');    // The name of the database
define('DB_USER_DAP', '%%DB_USER_DAP%%');     // Your MySQL username
define('DB_PASSWORD_DAP', '%%DB_PASSWORD_DAP%%'); // ...and password
define('DB_HOST_DAP', '%%DB_HOST_DAP%%');    // 99% chance you won't need to change this value

define ('SITE_URL_DAP', '%%SITE_URL_DAP%%');


define('DAP_ROOT', dirname(__FILE__).'/');
require_once("dap-settings.php");
?>