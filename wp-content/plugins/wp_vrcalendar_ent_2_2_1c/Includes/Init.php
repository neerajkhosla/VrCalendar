<?php
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die();
}

define( 'EDD_SAMPLE_STORE_URL', 'http://vrcalendarsync.com' );
define( 'EDD_SAMPLE_ITEM_NAME', 'VR Calendar Sync - ENTERPRISE' );

/* Load required files */
require_once( VRCALENDAR_PLUGIN_DIR . '/Includes/Vendor/Stripe/init.php' );

require_once( VRCALENDAR_PLUGIN_DIR . '/Includes/Classes/Abstract/Singleton/VRCSingleton.class.php' );
require_once( VRCALENDAR_PLUGIN_DIR . '/Includes/Classes/Abstract/Shortcode/VRCShortcode.class.php' );
require_once( VRCALENDAR_PLUGIN_DIR . '/Includes/Classes/Settings/VRCalendarSettings.class.php' );

require_once( VRCALENDAR_PLUGIN_DIR . '/Includes/Classes/Email/VRCEmail.class.php' );
require_once( VRCALENDAR_PLUGIN_DIR . '/Includes/Classes/Email/Transactional/VRCTransactionalEmail.class.php' );

require_once( VRCALENDAR_PLUGIN_DIR . '/Includes/Classes/Entity/VRCalendarEntity.class.php' );
require_once( VRCALENDAR_PLUGIN_DIR . '/Includes/Classes/Entity/Booking/VRCalendarBooking.class.php' );

require_once( VRCALENDAR_PLUGIN_DIR . '/Includes/Classes/ICal/Writer/Event/VRCCalendarEvent.class.php' );
require_once( VRCALENDAR_PLUGIN_DIR . '/Includes/Classes/ICal/Writer/VRCCalendar.class.php' );
require_once( VRCALENDAR_PLUGIN_DIR . '/Includes/Classes/ICal/VRCICal.class.php' );

require_once( VRCALENDAR_PLUGIN_DIR . '/Public/Classes/Shortcode/VRCalendarShortcode.class.php' );
require_once( VRCALENDAR_PLUGIN_DIR . '/Public/Classes/Shortcode/VRCalendarBookingBtnShortcode.class.php' );
// ..................add searchbars...................................start..........................
global $gbversiontype;
    if (($gbversiontype == "enterprisepaid") or ($gbversiontype == "enterprise500"))  {
        require_once( VRCALENDAR_PLUGIN_DIR . '/Public/Classes/Shortcode/VRCalendarSearchbarShortcode.class.php' );
		require_once( VRCALENDAR_PLUGIN_DIR . '/Public/Classes/Shortcode/VRCalendarSearchbarResultShortcode.class.php' );
    }
// ..................add searchbars...................................start..........................
require_once( VRCALENDAR_PLUGIN_DIR . '/Public/Classes/Shortcode/VRBookingShortcode.class.php' );
require_once( VRCALENDAR_PLUGIN_DIR . '/Public/Classes/Shortcode/VRPaymentShortcode.class.php' );
require_once( VRCALENDAR_PLUGIN_DIR . '/Public/Classes/Shortcode/VRThankyouShortcode.class.php' );

require_once( VRCALENDAR_PLUGIN_DIR . '/Public/Classes/VRCalendar.class.php' );
require_once( VRCALENDAR_PLUGIN_DIR . '/Admin/Classes/VRCalendarAdmin.class.php' );
//vr frontend class
require_once( VRCALENDAR_PLUGIN_DIR . '/FrontAdmin/Classes/VRCalendarFrontAdmin.class.php' );
if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
    // load our custom updater
    include( VRCALENDAR_PLUGIN_DIR . '/Includes/Vendor/EDD_SL_Plugin_Updater.php' );
}

$load_admin = false;
if( is_admin() ) {
    $load_admin = true;
}
else{

	/*----------------------------------------------------------------------------*
 * Fronend Dashboard and Functionality
 *----------------------------------------------------------------------------*/


add_action( 'plugins_loaded', array( 'VRCalendarFrontAdmin', 'getInstance' ) );

}

/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/


/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook( VRCALENDAR_PLUGIN_FILE, array( 'VRCalendar', 'activate' ) );
register_deactivation_hook( VRCALENDAR_PLUGIN_FILE, array( 'VRCalendar', 'deactivate' ) );


add_action( 'plugins_loaded', array( 'VRCalendar', 'getInstance' ) );

/*----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 *----------------------------------------------------------------------------*/

if ( $load_admin ) {
    add_action( 'plugins_loaded', array( 'VRCalendarAdmin', 'getInstance' ) );
}
//Global Variables for License Checking
$license_checked = false;
$license_valid = false;