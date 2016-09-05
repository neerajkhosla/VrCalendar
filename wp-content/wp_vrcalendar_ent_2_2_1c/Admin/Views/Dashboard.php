<div class="wrap vrcal-content-wrapper">
    <h2><?php echo VRCALENDAR_PLUGIN_NAME; ?></h2>
    <p class="vc-dash-banner">
        <a href="http://vrcalendarsync.com/" target="_blank"><img src="<?php echo VRCALENDAR_PLUGIN_URL ?>/assets/images/dashboard-banner.png" /></a>
    </p>
<?php
/* We'll do this later
if (!edd_sample_re_check_license()){
    echo "Please note your license has expired and you are no longer eligible for upgrades. Renew your license today at www.vrcalendarsync.com";
}
 */
    
if (edd_sample_check_license_new()){
    require(VRCALENDAR_PLUGIN_DIR.'/Admin/Views/Part/Dashboard/MyCalendars.php');
} else {
    _e('Please activate your license!', VRCALENDAR_PLUGIN_TEXT_DOMAIN);;
}
?>
</div>