<?php

@date_default_timezone_set('America/Los_Angeles');

ini_set("memory_limit","128M");
ini_set('display_errors', '0');
error_reporting(0);

// ** MySQL settings ** //
define('DB_NAME_DAP', 'db565027689');    // The name of the database
define('DB_USER_DAP', 'dbo565027689');     // Your MySQL username
define('DB_PASSWORD_DAP', '<w8%NvcDik|g+@e'); // ...and password
define('DB_HOST_DAP', 'db565027689.db.1and1.com');    // 99% chance you won't need to change this value

define ('SITE_URL_DAP', 'http://vrcalendarsync.com');


define('DAP_ROOT', dirname(__FILE__).'/');
require_once("dap-settings.php");
?>