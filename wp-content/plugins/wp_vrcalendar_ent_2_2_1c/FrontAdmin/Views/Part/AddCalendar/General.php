<?php
    $checked = '';
    $checked1 = '';
    $standardCss = 'style="display: none;"';
    $standardCss1 = 'style="display: none;"';
   
    if($cdata->pro_one_day_book == 'yes')
    {
        $standardCss1 = 'style="display: block;"';
    }
    
    if($cdata->hourly_booking == 'yes')
    {
        $standardCss = 'style="display: block;"';
    }
    $hoursbookingdiifference = $cdata->hoursbookingdiifference;
    
    ?>
<table class="form-table">
    <tbody>
        <tr valign="top">
            <th>
                <?php _e('Name', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <input type="text" id="calendar_name" name="calendar_name" value="<?php echo $cdata->calendar_name; ?>" class="large-text" placeholder="<?php _e('Name', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>">
                <?php if($cdata->calendar_id>0): ?>
                    <p class="description">iCal URL: <?php echo add_query_arg(array('vrc_pcmd'=>'ical','cal_id'=>$cdata->calendar_id), site_url()); ?></p>
                <?php endif; ?>
            </td>
        </tr>
        <tr valign="top">
            <th colspan="2">
			<div class="big_a"> 
                <span class="big-txt"><?php _e('Calendar Links', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></span> <a href="javascript:void(0)" class="add-new-h2" id="add-more-calendar-links"><?php _e('Add More', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></a></div>

                <table class="form-table" id="calendar-links">
                    <?php
                           
                    if(count($cdata->calendar_links)>0){
                        foreach($cdata->calendar_links as $clink) {
                         ?>
                            <!--tr valign="top" class="calendar_link_row">
                                <th>
                                    <?php //_e('Link', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                                </th>
                                <td>
                                    <input type="text" name="calendar_links[]" value="<?php //echo $clink; ?>" class="large-text" placeholder="<?php //_e('ics/ical Link', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>ics/ical Link">
                                    <a href="javascript:void(0)" class="remove-calendar-link vrc-remove-link"><?php //_e('Remove', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></a>
                                </td>
                            </tr-->

                            <tbody class="calendar_link_row">
                            <tr valign="top" >
                                <td>
                                    <table class="form-table">
                                        <tbody>
                                        <tr>
                                            <th>
                                                <?php _e('Name', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                                            </th>
                                            <td>
                                                <input type="text" name="calendar_links[name][]" value="<?php echo $clink->name; ?>" class="large-text" placeholder="<?php _e('Name', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>" />
                                                <a href="javascript:void(0)" class="remove-calendar-link vrc-remove-cal-link"><?php _e('Remove', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>
                                                <?php _e('Link', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                                            </th>
                                            <td>
                                                <input type="text" name="calendar_links[url][]" value="<?php echo $clink->url; ?>" class="large-text" placeholder="<?php _e('ics/ical Link', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>" />
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            </tbody>

                            <?php
                        }
                    }
                    ?>
                </table>
            </th>
        </tr>

        <tr valign="top">
            <th>
                <?php _e('Unavailable Dates', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <a href="javascript:void(0)" class="add-new-h2" id="addunabledatecalendarlinks"><?php _e('Add More', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></a>
            </td>
        </tr>
        <tr valign="top">
            <th>&nbsp;</th>
            <td>
                <div class="set_unabledate">
                    <?php
                    $counters_unable = 0;
                    
                    if(count($cdata->calender_unable) > 0)                    
                    {
                        foreach ($cdata->calender_unable['from_date'] as $keys=>$calender_unable)
                        {
                            $from_date_dbDate = $cdata->calender_unable['from_date'][$counters_unable];
                            $to_date_dbDate = $cdata->calender_unable['to_date'][$counters_unable];

                            $from_date = date('Y-m-d', strtotime($from_date_dbDate));
                            if($from_date_dbDate == '0000-00-00 00:00:00')
                            {
                                $from_date = '';
                            }

                            $to_date = date('Y-m-d', strtotime($to_date_dbDate));
                            if($to_date_dbDate == '0000-00-00 00:00:00')
                            {
                                $to_date = '';
                            }


                            ?>
                        <table class="unableclose<?php echo $counters_unable; ?>">
                            <tr>
                            <td><?php _e('From', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></td>
                            <td><input type="text" name="from_to_unable_date[from_date][]" id="from_unable_date<?php echo $counters_unable; ?>" class="from_to_unable_date" value="<?php echo $from_date; ?>" /></td>
                            <td><?php _e('To', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></td>
                            <td><input type="text" name="from_to_unable_date[to_date][]" id="to_unable_date<?php echo $counters_unable; ?>" class="from_to_unable_date" value="<?php echo $to_date; ?>" /></td>
                            <td><a href="javascript:set_unabledate('<?php echo $counters_unable; ?>')" class="remove_unable_datex"><?php _e('Remove', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></a></td>
                            </tr>
                        </table>
                            <?php

                            $counters_unable++;
                        }
                    }
                    ?>
                </div>
            </td>
        </tr>

        <tr valign="top">
            <th>
                <?php _e('Columns', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <select name="calendar_layout_options[columns]" class="large-text">
                <?php for($i=1;$i<=12; $i++):
                    $selected = '';
                    if($cdata->calendar_layout_options['columns'] == $i)
                        $selected = 'selected="selected"';
                    ?>
                    <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $i; ?></option>
                <?php endfor; ?>
                </select>
            </td>
        </tr>
        <tr valign="top">
            <th>
                <?php _e('Rows', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <select name="calendar_layout_options[rows]" class="large-text">
                    <?php for($i=1;$i<=12; $i++):
                        $selected = '';
                        if($cdata->calendar_layout_options['rows'] == $i)
                            $selected = 'selected="selected"';
                        ?>
                        <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
            </td>
        </tr>
		<tr valign="top">
            <th>
                <?php _e('Display Number of months', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <input type="number" id="calendar_display_num_months" name="calendar_display_num_months" value="<?php echo $cdata->calendar_display_num_months; ?>" class="large-text" placeholder="0" style="width:150px;">
            </td>
        </tr>
        <tr valign="top">
            <th>
                <?php _e('Size', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <fieldset>
                    <legend class="screen-reader-text"><span>input type="radio"</span></legend>
                    <?php foreach($layout_option_size as $sizek=>$sizev):
                        $checked = '';
                        if($sizek == $cdata->calendar_layout_options['size'])
                            $checked = 'checked="checked"';
                        ?>
                        <label title='<?php echo $sizev; ?>'><input type="radio" name="calendar_layout_options[size]" value="<?php echo $sizek; ?>" <?php echo $checked; ?> /> <span><?php echo $sizev; ?></span></label> &nbsp;
                    <?php endforeach; ?>
                </fieldset>
            </td>
        </tr>
        <tr>
            <th><?php _e('Standard One Day Booking', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></th>
            <td>
                <input type="checkbox" name="pro_one_day_book" id="pro_one_day_book"  value="<?php _e('yes', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>" <?php if($cdata->pro_one_day_book == 'yes'){ echo 'checked="checked"'; } ?> />
            </td>
        </tr>
        <tr valign="top">
           
            <td colspan="2" style="padding-left: 0;">
           <table class="hourly_booking" <?php echo $standardCss1; ?>>
               <tr valign="top">
                   <th>
                           <?php _e('Hourly Booking'); ?>&nbsp;&nbsp;
                   </th>
                   <td>
                       <input type="checkbox" name="hourly_booking" id="hourly_booking"  value="<?php _e('yes', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>" <?php if($cdata->hourly_booking == 'yes'){ echo 'checked="checked"'; } ?> />
                   </td>
               </tr>
               
       </table>
      </td>
    </tr>
    
    <tr valign="top">
        <td colspan="2" style="padding: 0;">
            <table class="hoursbookingdiifference" <?php echo $standardCss; ?>>
                <tr valign="top">
                   <th >
                       <?php _e('Difference Between each Hours', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                   </th>
                   <td >
                           <select name="hoursbookingdiifference" id="hoursbookingdiifference" >
                            <?php
                            for($i=1; $i<= 24; $i++)
                            {
                                if($hoursbookingdiifference == $i){
                                    if($cdata->hourly_booking == ''){
                                        ?>
                                            <option value="<?php echo $i; ?>" ><?php echo $i; ?> <?php _e('Hours', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></option>
                                        <?php
                                    }else{
                                        ?>
                                            <option value="<?php echo $i; ?>" selected="selected" ><?php echo $i; ?> <?php _e('Hours', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></option>
                                        <?php
                                    }
                                }else {
                                    ?>
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?> <?php _e('Hours', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </td>
               </tr>
            </table>
        </td>
    </tr>
       
    
    <tr valign="top">
        <th>
            <?php _e('Show Booking Form next to Calendar', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
        </th>
        <td>
            <input type="checkbox" name="show_booking_from_one_page" id="show_booking_from_one_page"  value="<?php _e('yes', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>" <?php if($cdata->show_booking_from_one_page == 'yes'){ echo 'checked="checked"'; } ?> />
        </td>
       </tr> 
        <?php
    //$booking_form_location = 'style="visibility:hidden;"';
        $booking_form_location = 'style="display: none;"';
    if($cdata->show_booking_from_one_page == 'yes')
    {
        //$booking_form_location = 'style="visibility:visible;"';
        $booking_form_location = 'style="display:block;"';
    }
    ?>
       <tr valign="top">
        <td colspan="2" style="padding-left: 0;">
            <table class="booking_form_location" <?php echo $booking_form_location; ?>>
                <th >
                    <?php _e('Booking Form Location', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                </th>
                <td >
                    <select name="booking_form_location" id="booking_form_location">
                        <option value="next_to_call" <?php if($cdata->booking_form_location == 'next_to_call'){ ?> selected="selected" <?php } ?>><?php _e('Next to Calendar', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></option>
                        <option value="below_to_call" <?php if($cdata->booking_form_location == 'below_to_call'){ ?> selected="selected" <?php } ?>><?php _e('Below to Calendar', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></option>
                    </select>
                </td>
            </table>
        </td>
       </tr>
    </tbody>
</table>

<!--table class="form-table" id="calendar-links-cloner">
    <tr valign="top" class="calendar_link_row">
        <th>
            <?php //e('Link', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
        </th>
        <td>
            <input type="text" name="calendar_links[]" value="" class="large-text" placeholder="<?php //_e('ics/ical Link', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>">
            <a href="javascript:void(0)" class="remove-calendar-link vrc-remove-link"><?php //_e('Remove', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></a>
        </td>
    </tr>
</table-->

<table class="form-table" id="calendar-links-cloner">
    <tbody class="calendar_link_row">
    <tr valign="top">
        <td>
            <table class="form-table">
                <tbody>
                <tr>
                    <th>
                        <?php _e('Name', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                    </th>
                    <td>
                        <input type="text" name="calendar_links[name][]" value="" class="large-text" placeholder="<?php _e('Name', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>" />
                        <a href="javascript:void(0)" class="remove-calendar-link vrc-remove-cal-link "><?php _e('Remove', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></a>
                    </td>
                </tr>
                <tr>
                    <th>
                        <?php _e('Link', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                    </th>
                    <td>
                        <input type="text" name="calendar_links[url][]" value="" class="large-text" placeholder="<?php _e('ics/ical Link', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>" />
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    
    </tbody>
</table>



<input type="hidden" name="app_blockdate" id="app_blockdate" value="<?php echo $counters_unable; ?>" />

<script>

jQuery(document).ready(function(){
 jQuery('.from_to_unable_date').datepicker({
        dateFormat: 'yy-mm-dd',
        minDate: -0,
        maxDate: "+3Y"
    });
});
    
function initDatePickerUnable() {
    jQuery('.from_to_unable_date').datepicker({
        dateFormat: 'yy-mm-dd',
        minDate: -0,
        maxDate: "+3Y"
    });
}

function set_unabledate(keyvalue)
{
    jQuery('.unableclose'+keyvalue).remove();
}
    
jQuery(document).ready(function(){
    
    jQuery("#addunabledatecalendarlinks").click(function(){
        var icounter = jQuery('#app_blockdate').val();
        var app_blockdate = '';
        app_blockdate += '<table class="unableclose'+icounter+'">';
        app_blockdate += '<tr>';
        app_blockdate += '<td>From</td>';
        app_blockdate += '<td><input type="text" name="from_to_unable_date[from_date][]" id="from_unable_date'+icounter+'" class="from_to_unable_date" /></td>';
        app_blockdate += '<td>To</td>';
        app_blockdate += '<td><input type="text" name="from_to_unable_date[to_date][]" id="to_unable_date'+icounter+'" class="from_to_unable_date" /></td>';
        app_blockdate += '<td><a href="javascript:set_unabledate('+icounter+')" class="remove_unable_datex">Remove</a></td>';
        app_blockdate += '</tr>';
        app_blockdate += '</table>';
        
        jQuery('.set_unabledate').append(app_blockdate);
       var icountera = parseInt(icounter)+parseInt(1);
        jQuery('#app_blockdate').val(icountera);
        initDatePickerUnable();
        
    });
});

    jQuery('#pro_one_day_book').click(function(){
        
        if(document.getElementById('pro_one_day_book').checked) {
            jQuery('.hourly_booking').show(500);
        }else{
            jQuery('.hourly_booking').hide(500);
            jQuery('.hoursbookingdiifference').hide(500);
            
        }
    });
    
    jQuery('#hourly_booking').click(function(){
        if(document.getElementById('hourly_booking').checked) {
            
            jQuery('.hoursbookingdiifference').show(500);

            jQuery('#minimum_number_of_night').attr('checked', false);
            //jQuery('.number_of_night').css('visibility','hidden');
            jQuery('.number_of_night').hide(500);

        }else{
            jQuery('.hoursbookingdiifference').hide(500);
        }
    });
    
    jQuery('#show_booking_from_one_page').click(function(){
        
        if(document.getElementById('show_booking_from_one_page').checked) {
            //jQuery('.booking_form_location').css('visibility','visible');
            jQuery('.booking_form_location').show(500);
        }else{
            //jQuery('.booking_form_location').css('visibility','hidden');
            jQuery('.booking_form_location').hide(500);
        }
    });



    
 
</script>