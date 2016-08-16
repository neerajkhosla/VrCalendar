<?php 

/*
Plugin Name: DAP Shopping Cart
Plugin URI: http://www.WickedCoolPlugins.com
Description: DAP Shopping Cart that allows unlimited upsells/downsells with Authorize.net / Paypal Payments PRO and comes FREE with the purchase of your DigitalAccessPass.com (DAP) license.
Version: 1.11
Author: Veena Prashanth & Ravi Jayagopal
Author URI: http://www.WickedCoolPlugins.com
*/

// plugin root folder
$dapshoppingcart_base_dir = WP_PLUGIN_URL . '/' . str_replace(basename( __FILE__), "" ,plugin_basename(__FILE__));
require_once(WP_PLUGIN_DIR . "/dapshoppingcart/_dapshoppingcart.php");

register_activation_hook(__FILE__,'dapshoppingcart_install');
global $dapshoppingcart_db_version;
$dapshoppingcart_db_version = "1.0";

?>