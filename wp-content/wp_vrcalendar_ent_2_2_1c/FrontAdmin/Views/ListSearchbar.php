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
	<tr><th><?php _e('Title', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></th><!--<th><?php _e('Shortcode', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></th>--><th><?php _e('Author', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></th><th><?php _e('Date added', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></th><th><?php _e('Action', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></th></tr>
	</thead>
	<tbody id="the-list">
        <?php foreach($allsearchbars as $searchbar){ 
		$name =__("No Name", VRCALENDAR_PLUGIN_TEXT_DOMAIN);
		if($searchbar->name !='')
        $name= $searchbar->name;
		$namewithlink= $name;
		if($searchbar->page_id > 0){$link =get_permalink($searchbar->page_id);$namewithlink="$name"; }?>
		<tr><td><?php echo $namewithlink; ?></td>
		<!--<td><?php echo '[vrcalendar_searchbar id="'.$searchbar->id.'" /]'; ?></td>-->
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
</div>