<?php 
header('X-Frame-Options: GOFORIT'); 
header('Access-Control-Allow-Origin:*'); 
include('/wp-load.php'); ?>
<?php wp_head(); ?>
<?php 
echo do_shortcode('[vrcalendar id="'.$_REQUEST['api_key'].'"/]'); ?>
<?php  get_footer(); ?>