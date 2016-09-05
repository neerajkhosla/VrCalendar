<div class="wrap vrcal-content-wrapper">
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
								<input type="text" class="regular-text" value="" id="searchbarname" name="searchbarname">
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
									if($k == 'result')
										$checked = 'checked="checked"';
									?>								
								<label title='<?php echo $v; ?>'><input type="radio" name="result_page" value="<?php echo $k; ?>"  <?php echo $checked; ?> /> <span><?php echo $v; ?></span></label> &nbsp;
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
									if($k == 'yes')
										$checked = 'checked="checked"';
									?>								
								<label title='<?php echo $v; ?>'><input type="radio" name="use_price_filter" value="<?php echo $k; ?>"  <?php echo $checked; ?> /> <span><?php echo $v; ?></span></label> &nbsp;
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
									if($k == 'yes')
										$checked = 'checked="checked"';
									?>								
								<label title='<?php echo $v; ?>'><input type="radio" name="show_image" value="<?php echo $k; ?>"  <?php echo $checked; ?> /> <span><?php echo $v; ?></span></label> &nbsp;
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
									if($k == 'yes')
										$checked = 'checked="checked"';
									?>								
								<label title='<?php echo $v; ?>'><input type="radio" name="show_address" value="<?php echo $k; ?>"  <?php echo $checked; ?> /> <span><?php echo $v; ?></span></label> &nbsp;
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
							<th colspan ="2">
								<?php _e('All', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?> <input type = "checkbox" id="allcal" name ="allcal">
								</th>
							
						</tr>
					</tbody>
				    </table> 
					<div class ="searchbar_cals" style="margin-left:10px;">
					<table class="form-table">
					<tbody>
					   <?php foreach($cals as $cal){                            
							$calname =($cal->calendar_name != '') ? $cal->calendar_name : __("No Name", VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
						<tr valign="top">
							<th>
							<?php echo $calname; ?>
							</th>
							<td>
								<?php echo '<input type = "checkbox" id= "'.$cal->calendar_id.'" name ="calendars['.$cal->calendar_id.']">'; ?>
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
								<input type="text" name="searchbar_color_options[search_button_color]" value="" class="vrc-color-picker" data-default-color="">
							</td>
						</tr>
						<tr valign="top">
							<th>
								<?php _e('Search Button Font Color', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
							</th>
							<td>
								<input type="text" name="searchbar_color_options[search_button_font_color]" value="" class="vrc-color-picker" data-default-color="">
							</td>
						</tr>
						<tr valign="top">
							<th>
								<?php _e('Search Font Color', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
							</th>
							<td>
								<input type="text" name="searchbar_color_options[search_font_color]" value="" class="vrc-color-picker" data-default-color="">
							</td>
						</tr>
						<tr valign="top">
							<th>
								<?php _e('Search Box Background Color', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
							</th>
							<td>
								<input type="text" name="searchbar_color_options[search_box_background_color]" value="" class="vrc-color-picker" data-default-color="">
							</td>
						</tr>
						
					</tbody>
				</table> 
                </div>
                <div> 				   
                    <input type="submit" value="<?php _e('Save', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>" name = "search_bar_save" class="button button-primary">
                </div>
            </form>
        </div>
    </div>
</div>