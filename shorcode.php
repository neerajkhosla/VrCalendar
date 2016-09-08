<?php 
header('X-Frame-Options: GOFORIT'); 
header('Access-Control-Allow-Origin:*'); 
include('wp-load.php'); 	
wp_head(); 
?>
<link href="<?php echo plugins_url( '/wp_vrcalendar_ent_2_2_1c/FrontAdmin/css/frontend.css'); ?>" rel="stylesheet"/>
<div id="main-content" class="search-bar-result">
<div class="container">
<div class="<?php if($_REQUEST['title']=='search'){ echo "search-bar"; } else { echo "caledar-section";  } ?>">
<?php
	## FOR SEARCH BAR ##
	if($_REQUEST['title']=='search'){ echo do_shortcode('[vrcalendar_searchbar id="'.$_REQUEST['api_key'].'"/]'); }
	## FOR CALENDAR ##
	else{ echo do_shortcode('[vrcalendar id="'.$_REQUEST['api_key'].'"/]'); }
?>
</div></div></div>

<?php wp_footer(); ?> 
