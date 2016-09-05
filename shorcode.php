<?php 
	header('X-Frame-Options: GOFORIT'); 
	header('Access-Control-Allow-Origin:*'); 
	
	include('/wp-load.php'); 
	
	wp_head(); 
	## FOR SEARCH BAR ##
	if($_REQUEST['title']=='search'){ echo do_shortcode('[vrcalendar_searchbar id="'.$_REQUEST['api_key'].'"/]'); }
	## FOR CALENDAR ##
	else{ echo do_shortcode('[vrcalendar id="'.$_REQUEST['api_key'].'"/]'); }
	
	wp_footer(); 
?> 