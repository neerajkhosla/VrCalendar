<?php 
	header('X-Frame-Options: GOFORIT'); 
	header('Access-Control-Allow-Origin:*'); 
	include('wp-load.php'); 
	wp_head(); 
    ?>
    <link href="<?php echo plugins_url( '/wp_vrcalendar_ent_2_2_1c/FrontAdmin/css/frontend.css'); ?>" rel="stylesheet"/>
	<div id="main-content" class="search-bar-result">
		<div class="container">
			<div class="search-bar">
				<?php
					echo do_shortcode('[vrcalendar_searchbar id="'.$_REQUEST['api_key'].'"/]');
				?>
			</div>
		</div>
	</div>
<?php 	
	wp_footer(); 
?> 