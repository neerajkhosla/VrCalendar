<?php
	$allsearchbars = $VRCalendarEntity->getAllSearchbar();
 ?>
<div class="wrap vrcal-content-wrapper">
    <h2>
		<?php _e('My Search Bars', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?> <a href="<?php echo admin_url('admin.php?page='.VRCALENDAR_PLUGIN_SLUG.'-add-search-bar&action=add') ?>" class="add-new-h2"><?php _e('Add new', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></a>
    </h2>
	<div class="left-panel-vr-plg">
		<?php include('sidebar.php'); ?>
	</div>
	<div class="right-panel-vr-plg">
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
		if($searchbar->page_id > 0){$link =get_permalink($searchbar->page_id);$namewithlink="<a target=\"_blank\" href=\"$link\">$name</a>"; }?>
		<tr><td><?php echo $namewithlink; ?></td><td><?php echo '[vrcalendar_searchbar id="'.$searchbar->id.'" /]'; ?></td><td><?php echo get_the_author_meta( 'display_name', $searchbar->author); ?></td><td><?php echo date('m/d/Y',strtotime($searchbar->created_on)); ?></td><td><a href="<?php echo admin_url("admin.php?page=".VRCALENDAR_PLUGIN_SLUG."-add-search-bar&action=edit&searchbar_id=".$searchbar->id); ?>"><?php _e('Edit', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></a> | <a href="<?php echo  admin_url("admin.php?page=".VRCALENDAR_PLUGIN_SLUG."-add-search-bar&action=del&searchbar_id=".$searchbar->id); ?>"><?php _e('Delete', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></a> </td></tr>
		<?php }  ?>
    </tbody>
    </table>
	<?php }else{ ?>
		<?php _e('Sorry! No Searchbars exist. Please', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?> <a href="<?php echo admin_url('admin.php?page='.VRCALENDAR_PLUGIN_SLUG.'-add-search-bar&action=add') ?>" ><?php _e('Add new', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></a>
	<?php } ?>
</div>
</div>