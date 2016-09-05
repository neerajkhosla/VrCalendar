<div class="wrap vrcal-content-wrapper cont-fuild vr-dashboard vr-search-bar vr-search-bar-edit edit_dashboard">
	
	<div class="left-panel-vr-plg">
		<?php include('sidebar.php'); ?>
	</div>
	<div class="right-panel-vr-plg">
	<h2><?php _e('Add Search Bar', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></h2>
    <div class="tabs-wrapper">
        <h2 class="nav-tab-wrapper">
            <a class='nav-tab nav-tab-active' href='#general-options'><?php _e('General', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></a>
            <a class='nav-tab' href='#color-options'><?php _e('Color Options', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></a>
        </h2>
        <div class="tabs-content-wrapper">
            <form method="post" action="" >
                <div id="general-options" class="tab-content tab-content-active">
				    <table class="form-table">
					<tbody>
						<tr valign="top">
							<th>
								<?php _e('Search Bar Name', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
							</th>
							<td>
								<input type="text" class="regular-text" value="<?php echo $searchbardata->name;?>" id="searchbarname" name="searchbarname">
							</td>
						</tr>
						<tr valign="top">
							<th>
								<?php _e('Maximum Guests', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
							</th>
							<td>
								<input type="number" class="regular-text" value="<?php if($searchbardata->maximumguests != ''){echo $searchbardata->maximumguests; }else{ echo '15'; };?>" id="maximumguests" name="maximumguests">
							</td>
						</tr>
						<tr valign="top">
							<th>
								<?php _e('Display Search Results on', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
							</th>
							<td>
								<?php foreach($seresultpage as $k=>$v): 
                                    $checked = '';
									if($k == $searchbardata->result_page)
										$checked = 'checked="checked"';
									?>							
								<label class="custom_checkbox" title='<?php echo $v; ?>'><input type="radio" name="result_page" value="<?php echo $k; ?>" <?php echo $checked; ?>   /> <i></i>  <span><?php echo $v; ?></span></label> &nbsp;
							<?php endforeach; ?>
							</td>
						</tr>
						<tr valign="top">
							<th>
								<?php _e('Use Price Filter', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
							</th>
							<td>
								<?php foreach($se_useprice_filter as $k=>$v): 
                                    $checked = '';
									if($k == $searchbardata->use_price_filter)
										$checked = 'checked="checked"';
									?>							
								<label class="custom_checkbox" title='<?php echo $v; ?>'><input type="radio" name="use_price_filter" value="<?php echo $k; ?>" <?php echo $checked; ?>   /><i></i>  <span><?php echo $v; ?></span></label> &nbsp;
							<?php endforeach; ?>
							</td>
						</tr>
						<tr valign="top">
							<th>
								<?php _e('Show Image', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
							</th>
							<td>
								<?php foreach($se_show_image as $k=>$v): 
                                    $checked = '';
									if($k == $searchbardata->show_image)
										$checked = 'checked="checked"';
									?>							
								<label class="custom_checkbox" title='<?php echo $v; ?>'><input type="radio" name="show_image" value="<?php echo $k; ?>" <?php echo $checked; ?>   /><i></i>  <span><?php echo $v; ?></span></label> &nbsp;
							<?php endforeach; ?>
							</td>
						</tr>
						<tr valign="top">
							<th>
								<?php _e('Show Address', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
							</th>
							<td>
								<?php foreach($se_show_address as $k=>$v): 
                                    $checked = '';
									if($k == $searchbardata->show_address)
										$checked = 'checked="checked"';
									?>							
								<label class="custom_checkbox" title='<?php echo $v; ?>'><input type="radio" name="show_address" value="<?php echo $k; ?>" <?php echo $checked; ?>   /> <i></i> <span><?php echo $v; ?></span></label> &nbsp;
							<?php endforeach; ?>
							</td>
						</tr>
					</tbody>
				    </table>          
					<table class="form-table">	
                    <?php if(count($cals) > 0){ ?>
                        <tbody>
						<tr valign="top">
							<th colspan ="2">
								<?php _e('Search should reference which calendars?', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
								</th>
							
						</tr>
						<tr valign="top">
                            
							<th>
								<?php _e('All', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></th> 
								<td><label class="custom_checkbox"><input type = "checkbox" id="allcal" name ="allcal"> <i></i> </label></td>
							
						</tr>
					</tbody>
				    </table> 
					<div class ="searchbar_cals">
					<table class="form-table">
					<tbody>
					   <?php foreach($cals as $cal){                            
							$calname =($cal->calendar_name != '') ? $cal->calendar_name : "No Name"; ?>
						<tr valign="top">
							<th>
							<?php echo $calname; ?>
							</th>
							<td>
								<?php $precals=array();
							    foreach($searchbardata->calendars as $k=>$v){
                                    $precals[]= $k;
								}							
                                $checked =(in_array($cal->calendar_id, $precals))?'checked="checked"':'';
							 
								echo '<label class="custom_checkbox"><input type = "checkbox" id= "'.$cal->calendar_id.'" name ="calendars['.$cal->calendar_id.']" '. $checked.'><i></i></label>' ;
							   ?>
							</td>
						</tr>
						<?php }?>
					</tbody>
				    </table> 
					</div>
					<?php }?>
                </div>
                <div id="color-options" class="tab-content">
                    <table class="form-table">
					<tbody>
						<tr valign="top">
							<th>
								<?php _e('Search Button Color', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
							</th>
							<td>
								<input type="text" name="searchbar_color_options[search_button_color]" value="<?php echo $searchbardata->color_options['search_button_color']; ?>" class="vrc-color-picker" data-default-color="<?php echo $searchbardata->color_options['search_button_color']; ?>">
							</td>
						</tr>
						<tr valign="top">
							<th>
								<?php _e('Search Button Font Color', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
							</th>
							<td>
								<input type="text" name="searchbar_color_options[search_button_font_color]" value="<?php echo $searchbardata->color_options['search_button_font_color']; ?>" class="vrc-color-picker" data-default-color="<?php echo $searchbardata->color_options['search_button_font_color']; ?>">
							</td>
						</tr>
						<tr valign="top">
							<th>
								<?php _e('Search Font Color', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
							</th>
							<td>
								<input type="text" name="searchbar_color_options[search_font_color]" value="<?php echo $searchbardata->color_options['search_font_color']; ?>" class="vrc-color-picker" data-default-color="<?php echo $searchbardata->color_options['search_font_color']; ?>">
							</td>
						</tr>
						<tr valign="top">
							<th>
								<?php _e('Search Box Background Color', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
							</th>
							<td>
								<input type="text" name="searchbar_color_options[search_box_background_color]" value="<?php echo $searchbardata->color_options['search_box_background_color']; ?>" class="vrc-color-picker" data-default-color="<?php echo $searchbardata->color_options['search_box_background_color']; ?>">
							</td>
						</tr>
						
					</tbody>
				</table> 
                </div>
                <div> 
				    <input type="hidden" name="created_on" value="<?php echo $searchbardata->created_on; ?>" />
					<input type="hidden" name="author" value="<?php echo $searchbardata->author; ?>" />
					<input type="hidden" name="searchbar_id" id="searchbar_id" value="<?php echo $searchbardata->id; ?>" />
                                        <input type="hidden" name="vrc_cmd" id="vrc_cmd" value="VRCalendarFrontAdmin:addSearchbar" />
                    <input type="submit" value="<?php _e('Update', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>" name = "search_bar_save" class="button button-primary">
                </div>
            </form>
        </div>
    </div>
</div>
</div>