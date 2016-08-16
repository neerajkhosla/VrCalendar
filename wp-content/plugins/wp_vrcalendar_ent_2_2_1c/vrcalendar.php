<?php
/**
 * @package   VR Calendar
 * @author    Innate Images, LLC
 * @license   GPL-2.0+
 * @link      http://vrcalendarsync.com
 * @copyright Innate Images, LLC
 *
 * @wordpress-plugin
 * Plugin Name:		  VR Calendar Enterprise
 * Plugin URI:        http://www.vrcalendarsync.com/
 * Description:       VR Calendar Plugin
 * Version:           2.2.1
 * Author:            Innate Images, LLC
 * Author URI:
 * Text Domain:       vr-calendar-locale
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}    
//Gloabal Variable to define version
$gbversiontype = "enterprisepaid";
$phpversionold = phpversion();
$phprequiredversion = "5.3.0";
define("VRCALENDAR_PLUGIN_DIR",addslashes(dirname(__FILE__)));
$pinfo = pathinfo(VRCALENDAR_PLUGIN_DIR);
define("VRCALENDAR_PLUGIN_FILE",addslashes(__FILE__));
define("VRCALENDAR_PLUGIN_URL",plugins_url().'/'.$pinfo['basename'].'/');

define("VRCALENDAR_PLUGIN_NAME",'VR Calendar');
define("VRCALENDAR_PLUGIN_SLUG",'vr-calendar');
define("VRCALENDAR_PLUGIN_TEXT_DOMAIN",'vr-calendar-locale');
if($phprequiredversion >= $phpversionold){
	die('This plugin require php version 5.3 or greater.');
}
require_once( VRCALENDAR_PLUGIN_DIR . '/Includes/Init.php' );
if(!function_exists('renderCurrency')) {
	function renderCurrency(){
		$VRCalendarSettings = VRCalendarSettings::getInstance();
		$currency = $VRCalendarSettings->getSettings('attr_currency');
		switch($currency){
			//case 'CAD':
			case 'USD':
                            return '$';
			case 'AUD':
                            return '$';
			break;
/*
			case 'GBP':
				return '�';
			break;
			case 'EUR':
				return '�';
			break;
 */
			default:
				return $currency.' ';
				break;
		}
	}
}    

	/**************** EDD Stuff ***************************/
    
    
    // this is the URL our updater / license checker pings. This should be the URL of the site with EDD installed
    define( 'EDD_SAMPLE_STORE_URL', 'http://vrcalendarsync.com' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file
    
    // the name of your product. This should match the download name in EDD exactly
    define( 'EDD_SAMPLE_ITEM_NAME', 'VR Calendar Sync - ENTERPRISE' ); // you should use your own CONSTANT name, and be sure to replace it throughout this file
    
    if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
        global $gbversiontype;
        
        //Don't load Updater for envato since it won't work
        if ($gbversiontype != "pro-envato"){
            // load our custom updater
            include( dirname( __FILE__ ) . '/EDD_SL_Plugin_Updater.php' );
        }
    }    

    function edd_sl_sample_plugin_updater() {
        
        global $gbversiontype;
        
        //Don't load Updater for envato since it won't work
        if ($gbversiontype != "pro-envato"){
            // retrieve our license key from the DB
            $license_key = trim( get_option( 'edd_sample_license_key' ) );
            
            // setup the updater
            $edd_updater = new EDD_SL_Plugin_Updater( EDD_SAMPLE_STORE_URL, __FILE__, array(
                    'version' 	=> '2.1.1', 	// current version number
                    'license' 	=> $license_key, // license key (used get_option above to retrieve from DB)
                    'item_name' => EDD_SAMPLE_ITEM_NAME, 	// name of this plugin
                    'author' 	=> 'Innate Images LLC'  // author of this plugin
            ));
        }
        
    }
    add_action( 'admin_init', 'edd_sl_sample_plugin_updater', 0 );
    
    /************************************
     * the code below is just a standard
     * options page. Substitute with
     * your own.
     *************************************/
    
function edd_sample_license_menu() {
    add_plugins_page( __('VR Calendar Sync License', VRCALENDAR_PLUGIN_TEXT_DOMAIN), __('VR Calendar Sync License', VRCALENDAR_PLUGIN_TEXT_DOMAIN), 'manage_options', 'pluginname-license', 'edd_sample_license_page' );
    }

add_action('admin_menu', 'edd_sample_license_menu');    
function edd_sample_license_page() {
        $license 	= get_option( 'edd_sample_license_key' );
        $status 	= get_option( 'edd_sample_license_status' );
        ?>
<div class="wrap">
<h2><?php _e('VR Calendar Sync License Options', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></h2>
<form method="post" action="options.php">
<?php settings_fields('edd_sample_license'); ?>
<table class="form-table">
<tbody>
<tr valign="top">
<th scope="row" valign="top">
<?php _e('License Key', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
</th>
<td>
<input id="edd_sample_license_key" name="edd_sample_license_key" type="text" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
<label class="description" for="edd_sample_license_key"><?php _e('Enter your license key', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></label>
</td>
</tr>
<?php if( false !== $license ) { ?>
<tr valign="top">
<th scope="row" valign="top">
<?php _e('Activate License', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
</th>
<td>
<?php if( $status !== false && $status == 'valid' ) { ?>
<span style="color:green;"><?php _e('active', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></span>
<?php wp_nonce_field( 'edd_sample_nonce', 'edd_sample_nonce' ); ?>
<input type="submit" class="button-secondary" name="edd_license_deactivate" value="<?php _e('Deactivate License', VRCALENDAR_PLUGIN_TEXT_DOMAIN ); ?>"/>
<?php } else {
    wp_nonce_field( 'edd_sample_nonce', 'edd_sample_nonce' ); ?>
<input type="submit" class="button-secondary" name="edd_license_activate" value="<?php _e('Activate License', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>"/>
<?php } ?>
</td>
</tr>
<?php } ?>
</tbody>
</table>
<?php submit_button(); ?>
</form>
<?php
    }    
    function edd_sample_register_option() {
        // creates our settings in the options table
        register_setting('edd_sample_license', 'edd_sample_license_key', 'edd_sanitize_license' );
    }
    add_action('admin_init', 'edd_sample_register_option');    
    function edd_sanitize_license( $new ) {
        $old = get_option( 'edd_sample_license_key' );
        if( $old && $old != $new ) {
            delete_option( 'edd_sample_license_status' ); // new license has been entered, so must reactivate
        }
        return $new;
    }     
    /************************************
     * this illustrates how to activate
     * a license key
     *************************************/
    
    function edd_sample_activate_license() {
        
        // listen for our activate button to be clicked
        if( isset( $_POST['edd_license_activate'] ) ) {
            
            // run a quick security check
            if( ! check_admin_referer( 'edd_sample_nonce', 'edd_sample_nonce' ) )
                return; // get out if we didn't click the Activate button
            
            // retrieve the license from the database
            $license = trim( get_option( 'edd_sample_license_key' ) );
            
            
            // data to send in our API request
            $api_params = array(
                                'edd_action'=> 'activate_license',
                                'license' 	=> $license,
                                'item_name' => urlencode( EDD_SAMPLE_ITEM_NAME ), // the name of our product in EDD
                                'url'       => home_url()
                                );
            
            // Call the custom API.
            $response = wp_remote_post( EDD_SAMPLE_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
            
            // make sure the response came back okay
            if ( is_wp_error( $response ) )
                return false;
            
            // decode the license data
            $license_data = json_decode( wp_remote_retrieve_body( $response ) );
            
            // $license_data->license will be either "valid" or "invalid"
            
            update_option( 'edd_sample_license_status', $license_data->license );
            
           
            //LICENSE: Update Settings with results
            $VRCalendarSettings = VRCalendarSettings::getInstance();
           
            if ($license_data->license == 'valid'){
                $VRCalendarSettings->setSettings('licensekey_active', true);
            } else {
                $VRCalendarSettings->setSettings('licensekey_active', false);
            }
            
        }
    }
    add_action('admin_init', 'edd_sample_activate_license');
    /***********************************************
     * Illustrates how to deactivate a license key.
     * This will descrease the site count
     ***********************************************/
    function edd_sample_deactivate_license() {
        
        // listen for our activate button to be clicked
        if( isset( $_POST['edd_license_deactivate'] ) ) {
            
            // run a quick security check
            if( ! check_admin_referer( 'edd_sample_nonce', 'edd_sample_nonce' ) )
                return; // get out if we didn't click the Activate button
            
            // retrieve the license from the database
            $license = trim( get_option( 'edd_sample_license_key' ) );
            
            
            // data to send in our API request
            $api_params = array(
                                'edd_action'=> 'deactivate_license',
                                'license' 	=> $license,
                                'item_name' => urlencode( EDD_SAMPLE_ITEM_NAME ), // the name of our product in EDD
                                'url'       => home_url()
                                );
            
            // Call the custom API.
            $response = wp_remote_post( EDD_SAMPLE_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
            
            // make sure the response came back okay
            if ( is_wp_error( $response ) )
                return false;
            
            // decode the license data
            $license_data = json_decode( wp_remote_retrieve_body( $response ) );
            
            // $license_data->license will be either "deactivated" or "failed"
            if( $license_data->license == 'deactivated' )
                delete_option( 'edd_sample_license_status' );
            
            //LICENSE: Update Settings with results from de-activation
            $VRCalendarSettings = VRCalendarSettings::getInstance();
            
            if ($license_data->license == 'deactivated'){
                $VRCalendarSettings->setSettings('licensekey_active', false);
            }

            
        }
    }
    add_action('admin_init', 'edd_sample_deactivate_license');    
    /************************************
     * this illustrates how to check if
     * a license key is still valid
     * the updater does this for you,
     * so this is only needed if you
     * want to do something custom
     *************************************/
function edd_sample_check_license() {
        
        global $wp_version;
        
        $license = trim( get_option( 'edd_sample_license_key' ) );
        
        $api_params = array(
                            'edd_action' => 'check_license',
                            'license' => $license,
                            'item_name' => urlencode( EDD_SAMPLE_ITEM_NAME ),
                            'url'       => home_url()
                            );
        
        // Call the custom API.
        $response = wp_remote_post( EDD_SAMPLE_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
        
        if ( is_wp_error( $response ) )
            return false;
        
        $license_data = json_decode( wp_remote_retrieve_body( $response ) );
        
        if( $license_data->license == 'valid' ) {
            echo 'valid'; exit;
            // this license is still valid
        } else {
            echo 'invalid'; exit;
            // this license is no longer valid
        }
} //end function edd_sample_check_license()  
function edd_sample_check_license_new() {

    //return true;

    //LICENSE: Check if license is ACTIVE and not envato type since we don't register envato plugins
    
    global $gbversiontype;
    
    $VRCalendarSettings = VRCalendarSettings::getInstance();    
    If ( ($VRCalendarSettings->getSettings('licensekey_active')) or ($gbversiontype == "pro-envato") ) {
        return true;
    } else {
        return true;
    }
    
} // end function edd_sample_check_license_new()
function edd_sample_check_license_valid() {

        //LICENSE: Check if license is VALID
        $VRCalendarSettings = VRCalendarSettings::getInstance();
        
        If ($VRCalendarSettings->getSettings('licensekey') ) {
            return true;
        } else {
            return false;
        }
        
} // end function edd_sample_check_license_valid()
function edd_sample_re_check_license() {

    global $wp_version;
    
    $VRCalendarSettings = VRCalendarSettings::getInstance();
    
    $license = trim( get_option( 'edd_sample_license_key' ) );
            
    $api_params = array(
                                'edd_action' => 'check_license',
                                'license' => $license,
                                'item_name' => urlencode( EDD_SAMPLE_ITEM_NAME ),
                                'url'       => home_url()
                                );
            
    // Call the custom API.
    $response = wp_remote_post( EDD_SAMPLE_STORE_URL, array( 'timeout' => 15, 'sslverify' => false, 'body' => $api_params ) );
            
    if ( is_wp_error( $response ) )
        return false;
            
    $license_data = json_decode( wp_remote_retrieve_body( $response ) );
            
    if( $license_data->license == 'valid' ) {
        // this license is still valid
        $VRCalendarSettings->setSettings('licensekey', true);
        return true;
       
    } else {  // this license is no longer valid
        $VRCalendarSettings->setSettings('licensekey', false);
        return false;
    }
} //end function edd_sample_re_check_license()