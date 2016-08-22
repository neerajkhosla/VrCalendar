<table class="form-table">
    <tbody>
        <tr valign="top">
            <th>
                <?php _e('Enable Booking?', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <fieldset>
                    <legend class="screen-reader-text"><span>input type="radio"</span></legend>
                    <?php foreach($enable_booking as $k=>$v):
                        $checked = '';
                        if($k == $cdata->calendar_enable_booking)
                            $checked = 'checked="checked"';
                        ?>
                        <label title='<?php echo $v; ?>'><input type="radio" name="calendar_enable_booking" value="<?php echo $k; ?>" <?php echo $checked; ?> /> <span><?php echo $v; ?></span></label> &nbsp;
                    <?php endforeach; ?>
                </fieldset>
            </td>
        </tr>
		<tr valign="top">
            <th>
                <?php _e('Booking URL', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <input type="text" id="calendar_booking_url" name="calendar_booking_url" value="<?php echo $cdata->calendar_booking_url; ?>" class="large-text" placeholder="<?php _e('Booking Url', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>">
            </td>
        </tr>
		<tr valign="top">
            <th>
                <?php _e('Listing Image', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td> <button class="button" type="button" onclick="open_media_uploader_image()"> <?php _e('Add/Edit Image', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></button>
			<?php if($cdata->calendar_listing_image !=''){ ?>
			    <img style= "width:200px;height:auto;" id="calendar_listingimage" src="<?php echo $cdata->calendar_listing_image; ?>">
			<?php }else{ ?>
			     <img style= "width:200px;height:auto;display:none;" id="calendar_listingimage" src="<?php echo $cdata->calendar_listing_image; ?>">
			<?php } ?>
                <input type="hidden" id="calendar_listing_image" name="calendar_listing_image" value="<?php echo $cdata->calendar_listing_image; ?>" >
            </td>
        </tr>
		<tr valign="top">
            <th>
                <?php _e('Listing Address', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <input type="text" id="calendar_listing_address" name="calendar_listing_address" value="<?php echo $cdata->calendar_listing_address; ?>" class="large-text" placeholder="<?php _e('Listing Address', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>">
            </td>
        </tr>
		<tr valign="top">
            
            <td colspan="2">
                <label class="lable_heading"><?php _e('Summary', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></label>
                <?php wp_editor( $cdata->calendar_summary, 'calendar_summary', $settings = array('textarea_name' => 'calendar_summary') ); ?>               
            </td>
        </tr>
        <tr valign="top">
            <th>
                <?php _e('Default Price Per Night', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <input type="text" id="calendar_price_per_night" name="calendar_price_per_night" value="<?php echo $cdata->calendar_price_per_night; ?>" class="large-text" placeholder="<?php _e('Price Per Night', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>">
            </td>
        </tr>
        
        <?php
        //$number_of_night = 'style="visibility:hidden;"';
        $number_of_night = 'style="display: none;"';
        if($cdata->minimum_number_of_night == 'yes')
        {
            $number_of_night = 'style="display: block;"';
        }
        ?>
        <tr>
            <th class="minimum_number_of_night" ><?php _e('Minimum Number Of Nights', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></th>
            <td class="minimum_number_of_night" >
                <input type="checkbox" name="minimum_number_of_night" id="minimum_number_of_night"  value="<?php _e('yes', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>" <?php if($cdata->minimum_number_of_night == 'yes'){ echo 'checked="checked"'; } ?> />
            </td>
        </tr>
        <tr>
            <td colspan="2" style="padding-left: 0;">
                <table class="number_of_night" <?php echo $number_of_night; ?>>
                    <tr>
                        <th ><?php _e('Number Of Night', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></th>
                        <td >
                            <input type="text" name="number_of_night" id="number_of_night"  value="<?php if($cdata->number_of_night){ echo $cdata->number_of_night; }else{ echo '1'; } ?>" />
                        </td>
                    </tr>  
                </table>
            </td>
        </tr>
        
		<tr valign="top">
            <th>
                <?php _e('Offer Weekly Price?', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <fieldset>
                    <legend class="screen-reader-text"><span>input type="radio"</span></legend>
                    <?php foreach($offer_weekly as $k=>$v):
                        $checked = '';
                        if($k == $cdata->calendar_offer_weekly)
                            $checked = 'checked="checked"';
                        ?>
                        <label title='<?php echo $v; ?>'><input type="radio" name="calendar_offer_weekly" value="<?php echo $k; ?>" <?php echo $checked; ?> /> <span><?php echo $v; ?></span></label> &nbsp;
                    <?php endforeach; ?>
                </fieldset>
            </td>
        </tr>
		<tr valign="top"  id="weekly_row">
            <th>
                <?php _e('Weekly Price', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                 <input type="text" id="calendar_price_weekly" name="calendar_price_weekly" value="<?php echo $cdata->calendar_price_weekly; ?>" class="large-text" placeholder="<?php _e('Weekly Price', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>">
            </td>
        </tr>
		<tr valign="top">
            <th>
                <?php _e('Offer Monthly Price?', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <fieldset>
                    <legend class="screen-reader-text"><span>input type="radio"</span></legend>
                    <?php foreach($offer_monthly as $k=>$v):
                        $checked = '';
                        if($k == $cdata->calendar_offer_monthly)
                            $checked = 'checked="checked"';
                        ?>
                        <label title='<?php echo $v; ?>'><input type="radio" name="calendar_offer_monthly" value="<?php echo $k; ?>" <?php echo $checked; ?> /> <span><?php echo $v; ?></span></label> &nbsp;
                    <?php endforeach; ?>
                </fieldset>
            </td>
        </tr>
		<tr valign="top" id="monthly_row">
            <th>
                <?php _e('Monthly Price', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                 <input type="text" id="calendar_price_monthly" name="calendar_price_monthly" value="<?php echo $cdata->calendar_price_monthly; ?>" class="large-text" placeholder="<?php _e('Monthly Price', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>">
            </td>
        </tr>
        <tr valign="top">
            <th>
                <?php _e('Cleaning Fee Per Stay', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <input type="text" id="calendar_cfee_per_stay" name="calendar_cfee_per_stay" value="<?php echo $cdata->calendar_cfee_per_stay; ?>" class="large-text" placeholder="<?php _e('Cleaning Fee Per Stay', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>">
            </td>
        </tr>
        <tr valign="top">
            <th>
                <?php _e('Taxes Per Stay', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <input type="text" id="calendar_tax_per_stay" name="calendar_tax_per_stay" value="<?php echo $cdata->calendar_tax_per_stay; ?>" class="large-text" placeholder="<?php _e('Taxes Per Stay', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>">
            </td>
        </tr>
		<tr valign="top">
            <th>
                <?php _e('Max Guests No.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <input type="number" min ="1" id="calendar_max_guest_no" name="calendar_max_guest_no" value="<?php echo $cdata->calendar_max_guest_no; ?>" class="large-text" placeholder="<?php _e('Maximum No. of Guests', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>">
            </td>
        </tr>
		<tr valign="top">
            <th>
                <?php _e('Charge Extra after Guests No.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <input type="text" id="calendar_extracharge_after_guest_no" name="calendar_extracharge_after_guest_no" value="<?php echo $cdata->calendar_extracharge_after_guest_no; ?>" class="large-text" placeholder="<?php _e('Charge Extra after X guests', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>">
            </td>
        </tr>		
		<tr valign="top">
            <th>
                <?php _e('Extra Charge Price', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <input type="text" id="calendar_extracharge_after_limited_guests" name="calendar_extracharge_after_limited_guests" value="<?php echo $cdata->calendar_extracharge_after_limited_guests; ?>" class="large-text" placeholder="<?php _e('Extra Charge Price', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>">
            </td>
        </tr>
		<tr valign="top">
            <th>
				<?php if($cdata->calendar_custom_field_name != '')		
					_e($cdata->calendar_custom_field_name, VRCALENDAR_PLUGIN_TEXT_DOMAIN);
				else
					_e('Extra Fees', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <input type="text" id="calendar_extra_fees" name="calendar_extra_fees" value="<?php echo $cdata->calendar_extra_fees; ?>" class="large-text" placeholder="<?php _e('Extra Fees', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>">
            </td>
        </tr>
		<?php
		$extrafeescustom = '';
		if($cdata->calendar_custom_field_name != ''){		
			$extrafeescustom = $cdata->calendar_custom_field_name;
		}else{
			$extrafeescustom = 'Extra Fees'; 
		} ?>
		<tr valign="top">
            <th>
                <?php _e('Custom Label', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <input type="text" id="calendar_custom_field_name" name="calendar_custom_field_name" value="<?php echo $cdata->calendar_custom_field_name; ?>" class="large-text" placeholder="Custom Label Name" style="width:180px;"><br> (This will replace '<?php echo $extrafeescustom; ?>' on booking screen).
            </td>
        </tr>
		<tr valign="top">
            <th>
                <?php _e('Weekend Pricing', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <input type="checkbox" name="weekend_pricing" id="weekend_pricing" value="yes" <?php if($cdata->weekend_pricing == 'yes'){ echo 'checked="checked"'; } ?> />
            </td>
        </tr>
        
        <tr valign="top" class="special_discount hide" >
            <th class="special_discount_offers" >
                <?php _e('Friday night price :', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td class="special_discount_offers "  >
                <input type="text" name="friday_night_discount" id="friday_night_discount" value="<?php echo $cdata->friday_night_discount; ?>" />
            </td>
        </tr>
		<tr valign="top" class="special_discount hide">
            <th class="special_discount_offers "  >
                <?php _e('Sat Night Price :', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td class="special_discount_offers "  >
                <input type="text" name="saturday_night_discount" id="saturday_night_discount" value="<?php echo $cdata->saturday_night_discount; ?>" />
            </td>
        </tr>
		<tr valign="top" class="special_discount hide">
            <th class="special_discount_offers "  >
                <?php _e('Sunday Night Price :', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td class="special_discount_offers "  >
                <input type="text" name="sunday_night_discount" id="sunday_night_discount" value="<?php echo $cdata->sunday_night_discount; ?>" />
            </td>
        </tr>
        <tr valign="top">
            <th>
                <?php _e('Seasonal Pricing', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <a href="javascript:void(0)" class="add-new-h2" id="add-more-price-exception"><?php _e('Add More', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></a>
            </td>
        </tr>
        
        <tr valign="top">
            <td colspan="2" style="padding:0">
                <table class="form-table" id="custom-price">
                    <thead>
                        <tr>
                            <th>
                                <?php _e('Start Date', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            </th>
                            <th>
                                <?php _e('End Date', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            </th>
                            <th>
                                <?php _e('Price per night', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            </th>
							 <th>
                                 <?php _e('Price per week', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            </th>
							 <th>
                                 <?php _e('Price per month', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            </th>
                            <th>
                                <?php _e('Minimum night', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            </th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php					
                    
                    if(count($cdata->calendar_price_exception)>0){
                        foreach($cdata->calendar_price_exception as $pException) {
                            ?>
                            <tr>
                                <td>
                                    <input type="text" name="calendar_price_exception[start_date][]" value="<?php echo $pException->start_date; ?>" class="vrc-calendar large-text" placeholder="<?php _e('Start Date', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>" />
                                </td>
                                <td>
                                    <input type="text" name="calendar_price_exception[end_date][]" value="<?php echo $pException->end_date; ?>" class="vrc-calendar large-text" placeholder="<?php _e('End Date', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>" />
                                </td>
                                <td>
                                    <input type="text" name="calendar_price_exception[price_per_night][]" value="<?php echo $pException->price_per_night; ?>" class="large-text" placeholder="<?php _e('Price per night', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>" />
                                </td>
								<td>
                                    <input type="text" name="calendar_price_exception[price_per_week][]" value="<?php echo $pException->price_per_week; ?>" class="large-text" placeholder="<?php _e('Price per week', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>" />
                                </td>
								<td>
                                    <input type="text" name="calendar_price_exception[price_per_month][]" value="<?php echo $pException->price_per_month; ?>" class="large-text" placeholder="<?php _e('Price per month', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>" />
                                </td>
                                </td>
								<td>
                                    <input type="text" name="calendar_price_exception[seasonal_minimum_nights][]" value="<?php echo $pException->seasonal_minimum_nights; ?>" class="large-text"  />
                                </td>
                                <td>
                                    <a href="javascript:void(0)" class="vrc-remove-link"><?php _e('Remove', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></a>
                                </td>
                            </tr>
                        <?php
                        }
                    }
                    ?>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr valign="top">
            <th>
                Taxes Type
            </th>
            <td>
                <fieldset>
                    <legend class="screen-reader-text"><span>input type="radio"</span></legend>
                    <?php foreach($tax_type as $k=>$v):
                        $checked = '';
                        if($k == $cdata->calendar_tax_type)
                            $checked = 'checked="checked"';
                        ?>
                        <label title='<?php echo $v; ?>'><input type="radio" name="calendar_tax_type" value="<?php echo $k; ?>" <?php echo $checked; ?> /> <span><?php echo $v; ?></span></label> &nbsp;
                    <?php endforeach; ?>
                </fieldset>
            </td>
        </tr>
        <tr valign="top">
            <th>
                <?php _e('Payment Method', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <fieldset>
                    <legend class="screen-reader-text"><span>input type="radio"</span></legend>
                     <?php 
                    $diposit_div_block = '';
                    $diposit_div_class = 'hide';
                    
                    if($cdata->deposit_enable == 'yes')
                        {
                            $diposit_div_block = 'style="visibility: visible;"';
                            $diposit_div_class = ' ';
                        }
                    
                    foreach($payment_method as $k=>$v):
                        $checked = '';
                        if($k == $cdata->calendar_payment_method)
                        {
                            $checked = 'checked="checked"';
                            
                        }
                        
                            
                        ?>
                        <label title='<?php echo $v; ?>'><input type="radio" name="calendar_payment_method" value="<?php echo $k; ?>" <?php echo $checked; ?> /> <span><?php echo $v; ?></span></label> &nbsp;
                    <?php endforeach; ?>
                </fieldset>
            </td>
        </tr>
        
        <tr valign="top">
            <th>
                <?php _e('Deposit Enable', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <input type="checkbox" name="deposit_enable" id="deposit_enable" value="<?php _e('yes', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>" <?php if($cdata->deposit_enable == 'yes'){ echo 'checked="checked"'; } ?> />
            </td>
        </tr>
        
        <tr valign="top">
            <th class="deposit_percentage_amount <?php echo $diposit_div_class; ?>" <?php echo $diposit_div_block; ?> >
                <?php _e('Deposit Percentage', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td class="deposit_percentage_amount <?php echo $diposit_div_class; ?>" <?php echo $diposit_div_block; ?> >
                <input type="text" name="deposit_percentage" id="deposit_percentage" value="<?php echo $cdata->deposit_percentage; ?>" /> %
            </td>
        </tr>
        
        <tr valign="top">
            <th class="deposit_percentage_amount <?php echo $diposit_div_class; ?>" <?php echo $diposit_div_block; ?> >
                <?php //_e('Balance due in', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
				<?php _e('Balance due', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td class="deposit_percentage_amount <?php echo $diposit_div_class; ?>" <?php echo $diposit_div_block; ?> >
                <input type="text" name="rest_of_day" id="rest_of_day" value="<?php echo $cdata->rest_of_day; ?>" /> Days
            </td>
        </tr>
        
        <!-- <tr valign="top">
            <th>
                <?php //_e('Alert to Double Booking?', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <fieldset>
                    <legend class="screen-reader-text"><span>input type="radio"</span></legend>
                    <?php //foreach($alert_double_booking as $k=>$v):
                        //$checked = '';
                        //if($k == $cdata->calendar_alert_double_booking)
                            //$checked = 'checked="checked"';
                        ?>
                        <label title='<?php //echo $v; ?>'><input type="radio" name="calendar_alert_double_booking" value="<?php //echo $k; ?>" <?php //echo $checked; ?> /> <span><?php //echo $v; ?></span></label> &nbsp;
                    <?php //endforeach; ?>
                </fieldset>
            </td>
        </tr> -->
        <tr valign="top">
            <th>
                <?php _e('Requires Admin Approval?', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <fieldset>
                    <legend class="screen-reader-text"><span>input type="radio"</span></legend>
                    <?php foreach($requires_admin_approval as $k=>$v):
                        $checked = '';
                        if($k == $cdata->calendar_requires_admin_approval)
                            $checked = 'checked="checked"';
                        ?>
                        <label title='<?php echo $v; ?>'><input type="radio" name="calendar_requires_admin_approval" value="<?php echo $k; ?>" <?php echo $checked; ?> /> <span><?php echo $v; ?></span></label> &nbsp;
                    <?php endforeach; ?>
                </fieldset>
            </td>
        </tr>

    </tbody>
</table>
<table class="form-table" id="price-exception-clone">
    <tbody>
    <tr>
        <td>
            <input type="text" name="calendar_price_exception[start_date][]" value="" class="vrc-calendar large-text" placeholder="<?php _e('Start Date', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>" />
        </td>
        <td>
            <input type="text" name="calendar_price_exception[end_date][]" value="" class="vrc-calendar large-text" placeholder="<?php _e('End Date', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>" />
        </td>
        <td>
            <input type="text" name="calendar_price_exception[price_per_night][]" value="" class="large-text" placeholder="<?php _e('Price per night', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>" />
        </td>
		<td>
            <input type="text" name="calendar_price_exception[price_per_week][]" value="" class="large-text" placeholder="<?php _e('Price per week', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>" />
        </td>
	<td>
            <input type="text" name="calendar_price_exception[price_per_month][]" value="" class="large-text" placeholder="<?php _e('Price per month', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>" />
        </td>
        <td>
            <input type="text" name="calendar_price_exception[seasonal_minimum_nights][]" class="large-text" value="1" />
        </td>
        <td>
            <a href="javascript:void(0)" class="vrc-remove-link"><?php _e('Remove', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></a>
        </td>
    </tr>
    </tbody>
</table>
<script>
    jQuery("input[name=deposit_enable]").click(function(){
        if(document.getElementById('deposit_enable').checked) 
        {
            jQuery('.deposit_percentage_amount').removeClass('hide');
            jQuery('.deposit_percentage_amount').css('visibility', 'visible');
        } else {
            jQuery('.deposit_percentage_amount').addClass('hide');
            jQuery('.deposit_percentage_amount').css('visibility', 'hidden');
        }
       /*if(this.value == 'deposit')
       {
           jQuery('.deposit_percentage_amount').removeClass('hide');
           jQuery('.deposit_percentage_amount').css('visibility', 'visible');
       }
       else
       {
           jQuery('.deposit_percentage_amount').addClass('hide');
           jQuery('.deposit_percentage_amount').css('visibility', 'hidden');
       }*/
    });
    if(document.getElementById('weekend_pricing').checked) 
	{
		jQuery('.special_discount').removeClass('hide');
		jQuery('.special_discount').css('visibility', 'visible');
	} 
    jQuery("input[name=weekend_pricing]").click(function(){
		
        if(document.getElementById('weekend_pricing').checked) 
        {
            jQuery('.special_discount').removeClass('hide');
            jQuery('.special_discount').css('visibility', 'visible');
        } else {
            jQuery('.special_discount').addClass('hide');
            jQuery('.special_discount').css('visibility', 'hidden');
        }
       /*if(this.value == 'deposit')
       {
           jQuery('.deposit_percentage_amount').removeClass('hide');
           jQuery('.deposit_percentage_amount').css('visibility', 'visible');
       }
       else
       {
           jQuery('.deposit_percentage_amount').addClass('hide');
           jQuery('.deposit_percentage_amount').css('visibility', 'hidden');
       }*/
    });
    jQuery('#minimum_number_of_night').click(function(){
        
        if(document.getElementById('minimum_number_of_night').checked) {
                if(document.getElementById('hourly_booking').checked) 
                {
                    jQuery('#hourly_booking').attr('checked', false);
                    jQuery('#minimum_number_of_night').attr('checked', true);
                    jQuery('.hoursbookingdiifference').hide(500);
                }
                //jQuery('.number_of_night').css('visibility','visible');
                jQuery('.number_of_night').show(500);
        }else{
            //jQuery('.number_of_night').css('visibility','hidden');
            jQuery('.number_of_night').hide(500);
        }
    });
</script>