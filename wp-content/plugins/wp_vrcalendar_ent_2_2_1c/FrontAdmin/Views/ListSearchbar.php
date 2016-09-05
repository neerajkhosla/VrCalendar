<?php
global $post;
	$allsearchbars = $VRCalendarEntity->getAllSearchbar();
 ?>
<div class="wrap vrcal-content-wrapper cont-fuild vr-dashboard vr-search-bar">

	<div class="left-panel-vr-plg">
		<?php include('sidebar.php'); ?>
	</div>
	<div class="right-panel-vr-plg">
	    <h2 class="heading_col">
		<?php _e('My Search Bars', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?> <a href="<?php echo site_url($post->post_name.'?page='.VRCALENDAR_PLUGIN_SLUG.'-add-search-bar&action=add') ?>" class="add-new-h2"><i aria-hidden="true" class="fa fa-search "></i> <?php _e('Add new', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></a>
    </h2>
	<?php if(!empty($allsearchbars)){ ?>
	<table class="wp-list-table widefat fixed striped ">
	<thead>
	<tr><th><?php _e('Title', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></th><th><?php _e('Shortcode', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></th><th><?php _e('Author', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></th><th><?php _e('Date added', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></th><th><?php _e('Action', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></th></tr>
	</thead>
	<tbody id="the-list">
        <?php foreach($allsearchbars as $searchbar){ 
		$name =__("No Name", VRCALENDAR_PLUGIN_TEXT_DOMAIN);
		if($searchbar->name !='')
                $name= $searchbar->name;
		$namewithlink= $name;
		if($searchbar->page_id > 0){$link =get_permalink($searchbar->page_id);$namewithlink="$name"; }?>
		<tr><td><?php echo $namewithlink; ?></td>
		
		<td><?php
			  $api_key= base64_encode($searchbar->id."--".uniqid());
			  $script= htmlspecialchars("<script>window.options = { api_key:'".$api_key."',height:'540px',width:'1000px' ,'title':'search'};var s = document.createElement('script');s.src = \"".site_url()."/embed.js\";s.async = true; document.body.appendChild(s);</script>");
			  
               echo '<a class="btn" data-popup-open="popup-'.$cal['calendar_id'].'" href="#">Get Embed Code</a><div class="popup" data-popup="popup-'.$cal['calendar_id'].'"><div class="popup-inner"><h4 class="embed-heading">Embed Code for '.$cal['calendar_name'].'</h4><p class="embed-section" ><span class="response" style="display:none;"></span><code id="'.$cal['calendar_id'].'">'.$script.'</code><button type="button" onclick=copyToClipboard("#'.$searchbar->id.'")>copy to clipboard</button></p><a class="popup-close" data-popup-close="popup-'.$cal['calendar_id'].'" href="#">x</a></div></div>';
		?></td>
		
		<td><?php echo get_the_author_meta( 'display_name', $searchbar->author); ?></td>
		
		<td><?php echo date('m/d/Y',strtotime($searchbar->created_on)); ?></td>
		
		<td><a href="<?php echo site_url($post->post_name."?page=".VRCALENDAR_PLUGIN_SLUG."-add-search-bar&action=edit&searchbar_id=".$searchbar->id); ?>"><i class="fa fa-edit"></i></a> | <a href="<?php echo  site_url($post->post_name."?page=".VRCALENDAR_PLUGIN_SLUG."-add-search-bar&action=del&searchbar_id=".$searchbar->id); ?>"><i class="fa fa-trash"></i></a> </td></tr>
		<?php }  ?>
    </tbody>
    </table>
	<?php }else{ ?>
		<?php _e('Sorry! No Searchbars exist. Please', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?> <a href="<?php echo  site_url($post->post_name.'?page='.VRCALENDAR_PLUGIN_SLUG.'-add-search-bar&action=add') ?>" ><?php _e('Add new', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></a>
	<?php } ?>
</div>
	<script>
	jQuery(function() {
			//----- OPEN
			jQuery('[data-popup-open]').on('click', function(e)  {
				var targeted_popup_class = jQuery(this).attr('data-popup-open');
				jQuery('[data-popup="' + targeted_popup_class + '"]').fadeIn(350);

				e.preventDefault();
			});

			//----- CLOSE
			jQuery('[data-popup-close]').on('click', function(e)  {
				var targeted_popup_class = jQuery(this).attr('data-popup-close');
				jQuery('[data-popup="' + targeted_popup_class + '"]').fadeOut(350);

				e.preventDefault();
			});
		});
		function copyToClipboard(element) {
	
	  var $temp = jQuery("<input>");
	  jQuery("body").append($temp);
	  $temp.val(jQuery(element).text()).select();
	  document.execCommand("copy");
	  $temp.remove();
	  jQuery(".response").show();
	  jQuery(".response").html('Copied').delay(2000).fadeOut('fast');
	}
	</script>
</div>