<?php 
/* 
	Plugin Name: S3MediaVault Pro
	Plugin URI: http://S3MediaVault.com
	Description: S3MediaVault Pro: HTML5 Video Player with full support for Apple iOS devices, with Flash fallback, 9 ready-made Custom skins, Splash image, Customizable player buttons, Google Analytics Support that tracks how long your video was played by each visitor, Ability to redirect at the end of video play to any URL, Ability to display timed HTML (like buy button) within video at any point (Cuepoint) as well as below video
	Author: Veena Prashanth & Ravi Jayagopal
	Author URI: http://S3MediaVault.com
	Version: 3.4
*/


require_once("_S3MediaVault.php");
require_once("S3MediaVault-ShortCodes.php");
require_once("S3MVPro.php");

$s3mv_base_dir = WP_PLUGIN_URL . '/' . str_replace(basename( __FILE__), "" ,plugin_basename(__FILE__));

?>