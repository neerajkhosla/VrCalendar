<?php
class VRCalendarShortcode extends VRCShortcode {

    protected $slug = 'vrcalendar';

    function shortcode_handler($atts, $content = "") {
        
        if (edd_sample_check_license_new()){
        
        $this->atts = shortcode_atts(
            array(
                'id'=>false
            ),$atts, 'vrcalendar');

        if(!$this->atts['id'])
            return __('Calendar id is missing', VRCALENDAR_PLUGIN_TEXT_DOMAIN);

        $VRCalendarEntity = VRCalendarEntity::getInstance();
        $VRCalendarSettings = VRCalendarSettings::getInstance();
        
        $cal_data = $VRCalendarEntity->getCalendar( $this->atts['id'] );

         $pro_one_day_book = $cal_data->pro_one_day_book;
            $hourly_booking = $cal_data->hourly_booking;
            
        $minimum_number_of_night = $cal_data->minimum_number_of_night;
        $number_of_night = $cal_data->number_of_night;
        
        

        //style of left and right moves

        $CheckIn = __('Check In', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $CheckOut = __('Check Out', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $Guests = __('Guests', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $FirstName = __('First Name', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $LastName = __('Last Name', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $Email_booking = __('Email', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $PhoneNumber = __('Phone Number', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $NoteToHost = __('Note To Host', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $zero_value = __('00.00', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $ExtraGuestFees = __('Extra Guest Fees', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $CleaningFee = __('Cleaning Fee', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $Taxes = __('Taxes', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        //$ExtraFees = __('Extra Fees', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $TotalReservationAmount = __('Total Reservation Amount', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $RequesttoBook = __('Request to Book', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $CheckInDate = __('Check In Date', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $CheckOutDate = __('Check Out Date', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $remove_x = __('x', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $zero_days = __('0', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
		
		if($cal_data->calendar_custom_field_name != ''){		
			$ExtraFees = __($cal_data->calendar_custom_field_name, VRCALENDAR_PLUGIN_TEXT_DOMAIN);
		}else{
			$ExtraFees = __('Extra Fees', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
		} 
        
        $show_booking_from_one_page = $cal_data->show_booking_from_one_page;
            
            $booking_form_location_style = '';
            $booking_form_location_style .= '<style> .spanhide{display:none;} </style>';
            $nbsptext = '';
            $div_vrc_calendar_wrapper_open = '';
            $div_vrc_calendar_wrapper_close = '';
            if($show_booking_from_one_page == 'yes')
            {
                if($cal_data->booking_form_location == 'next_to_call')
                {
                        $nbsptext = '&nbsp;';
                       /*$booking_form_location_style = '<style> 
                                .calendar-slides .col-md-4{ width: 100%; }
                                .calendar-slides { width: 50%; float: left; }
                                .calendar-from { width: 50%; float: left; }
                                .calendar-from .col-sm-6, .col-sm-4{ width: 100% !important;}
                           </style>';*/
                        /*$booking_form_location_style .= '<style>
                                .vrc.vrc-calendar { width: 70%; float: left; }
                                .calendar-from { width: 30%; float: left; }
                                .calendar-from .col-sm-6, .col-sm-4{ width: 100% !important;}
                           </style>';*/

                        $div_vrc_calendar_wrapper_open = '<div class="vrc-calendar-wrapper vrc-layout-inline">';
                        $div_vrc_calendar_wrapper_close = '</div>';
                }
            }

            $booking_powered_style = '';
            $checkerDay = __('Per Night', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
            $check_in_time = '';
            $check_out_time = '';
            $valid_hourly_booking = 0;
            
            $dayNight = __('Night', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
            if($pro_one_day_book == 'yes')
            {
                $checkerDay = __('Per Day', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
                $dayNight = __('day', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
            }


            if($hourly_booking == 'yes')
            {
                $booking_powered_style = 'style="width:100%; float:left;"';
                $valid_hourly_booking = 1;
                $check_in_time .= '<div class="col-sm-6">
                            <div class="form-group">
                                <label for="booking_checkin_intime">'.__('Check In Time', VRCALENDAR_PLUGIN_TEXT_DOMAIN).'</label>
                                <select name="checkintime" id="checkintime" class="form-control required" onchange="timeChange(this.value)">
                                  </select>
                            </div>
                        </div>';
                
                $check_out_time .= '<div class="col-sm-6">
                            <div class="form-group">
                                <label for="checkouttime">'.__('Check Out Time', VRCALENDAR_PLUGIN_TEXT_DOMAIN).'</label>
                                <select name="checkouttime" id="checkouttime" class="form-control required">
                                  </select>
                            </div>
                        </div>';
                
                $check_in_time .=  "<script>
                    
                    function firstDatetimeChange(inDate)
                    {
                        var timeArray = {'0' : '12:00am', '1' : '1:00am', '2' : '2:00am', '3' : '3:00am', '4' : '4:00am', '5' : '5:00am', '6' : '6:00am', '7' : '7:00am', '8' : '8:00am', '9' : '9:00am', '10' : '10:00am', '11' : '11:00am', '12' : '12:00pm', '13' : '1:00pm', '14' : '2:00pm','15' : '3:00pm', '16' : '4:00pm', '17' : '5:00pm', '18' : '6:00pm', '19' : '7:00pm', '20' : '8:00pm', '21' : '9:00pm', '22' : '10:00pm', '23' : '11:00pm'};
                        var timeKeysget = {'0' : '00:00:00', '1' : '01:00:00', '2' : '02:00:00', '3' : '03:00:00', '4' : '04:00:00', '5' : '05:00:00', '6' : '06:00:00', '7' : '07:00:00', '8' : '08:00:00', '9' : '09:00:00', '10' : '10:00:00', '11' : '11:00:00', '12' : '12:00:00', '13' : '13:00:00', '14' : '14:00:00','15' : '15:00:00', '16' : '16:00:00', '17' : '17:00:00', '18' : '18:00:00', '19' : '19:00:00', '20' : '20:00:00', '21' : '21:00:00', '22' : '22:00:00', '23' : '23:00:00'};
                        var optionInValues = ''; 
                        var calendar_id = jQuery('#cal_id').val();
                        var data = {
                                'action': 'get_updated_checkindate',
                                'cal_id': calendar_id,
                                'inDate': inDate,
                            };
                        
                            jQuery.post(vrc_data.ajax_url, data, function(response) {
                                var obj = jQuery.parseJSON( response );
                            
                            jQuery.each(obj, function(key, value) {
                                optionInValues += '<option value='+timeKeysget[key]+'>'+timeArray[key]+'</option>';
                            });
                            jQuery('#checkintime').html(optionInValues);
                            
                            timeChange(jQuery('#checkintime').val());
                        });
                    }

                    function timeChange(thisinvalue)
                        {
                            var timeArray = {'0' : '12:00am', '1' : '1:00am', '2' : '2:00am', '3' : '3:00am', '4' : '4:00am', '5' : '5:00am', '6' : '6:00am', '7' : '7:00am', '8' : '8:00am', '9' : '9:00am', '10' : '10:00am', '11' : '11:00am', '12' : '12:00pm', '13' : '1:00pm', '14' : '2:00pm','15' : '3:00pm', '16' : '4:00pm', '17' : '5:00pm', '18' : '6:00pm', '19' : '7:00pm', '20' : '8:00pm', '21' : '9:00pm', '22' : '10:00pm', '23' : '11:00pm'};
                            var timeKeysget = {'0' : '00:00:00', '1' : '01:00:00', '2' : '02:00:00', '3' : '03:00:00', '4' : '04:00:00', '5' : '05:00:00', '6' : '06:00:00', '7' : '07:00:00', '8' : '08:00:00', '9' : '09:00:00', '10' : '10:00:00', '11' : '11:00:00', '12' : '12:00:00', '13' : '13:00:00', '14' : '14:00:00','15' : '15:00:00', '16' : '16:00:00', '17' : '17:00:00', '18' : '18:00:00', '19' : '19:00:00', '20' : '20:00:00', '21' : '21:00:00', '22' : '22:00:00', '23' : '23:00:00'};

                            
                            var cal_id = jQuery('#cal_id').val();
                            var date_of_only_hourly_booking = jQuery('#date_of_only_hourly_booking').val();

                            var data = {
                                'action': 'get_updated_checkoudate',
                                'cal_id': cal_id,
                                'thisinvalue': thisinvalue,
                                'date_of_only_hourly_booking': date_of_only_hourly_booking 
                            };
                            var optionValues = '';
                            jQuery.post(vrc_data.ajax_url, data, function(response) {

                               var obj = jQuery.parseJSON( response );
                               
                                  jQuery.each(obj, function(key, value) {

                                      optionValues += '<option value='+timeKeysget[key]+'>'+timeArray[key]+'</option>';
                                    });
                                   jQuery('#checkouttime').html(optionValues);
                            });

                        }
                    </script>";
            }

            $getRenderCurrency = renderCurrency();


            $lang_update = __('Calendar Updated on', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
            $lang_available = __('Available', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
            $lang_unavailable = __('Unavailable', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
            $last_sync_date = get_date_from_gmt($cal_data->calendar_last_synchronized, 'F d, Y \a\t h:i a');
        
        
        //Attribution Code here which adds a Powered by VR Cal Sync under the Calendar.
        //First, make sure it is set, if its not, set it to yes by default
        $Next_arrow = __('Next', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $doattribution = $VRCalendarSettings->getSettings('attribution');
        
         $textForTotal = '';
         $textForTotal .= "<div class='default_price'>Total</div>";
         $textForTotal .= "<div class='updated_price' style='display: none;'>".__('Deposit Due Today', VRCALENDAR_PLUGIN_TEXT_DOMAIN)." (".$cal_data->deposit_percentage."%)</div>";
        
        
        $calendar_extracharge_after_guest_no = 'style="display:none"';
        if($cal_data->calendar_extracharge_after_limited_guests > 0)
        {
            $calendar_extracharge_after_guest_no = 'style="display:block"';
        }
        
        $show_booking_from_one_page = $cal_data->show_booking_from_one_page;
            $showFormData = 'style="display:none"';
            if($show_booking_from_one_page == 'yes')
                $showFormData = 'style="display:block"';
        
        if (!isset($doattribution)) {
            $doattribution = 'yes';}
        
        //Next, If attribution is desired print it, otherwise print blank string
        if ($doattribution == 'yes'){
            $printattribution = "<div class=\"calendar-info\">".__('Powered By', VRCALENDAR_PLUGIN_TEXT_DOMAIN)." <a href=\"http://www.vrcalendarsync.com\" target=\"_blank\">".__('Vacation Rental Calendar Sync', VRCALENDAR_PLUGIN_TEXT_DOMAIN)."</a></div>";
        } else {
            $printattribution = "";}
        $calendar_display_num_months = '';
        if($cal_data->calendar_display_num_months != ''){
			$calendar_display_num_months = $cal_data->calendar_display_num_months;
			$navigation_block = '';
			if($cal_data->calendar_display_num_months <= 12){
				$navigation_block = 'display:none;';
			}else{
				$navigation_block = 'display:block;';
			}
		}else{
			$calendar_display_num_months = 36 ;
		}
        $calendar_html = $this->getCalendar($cal_data, $calendar_display_num_months);
        $calendar_css =$this->getCalendarCSS($cal_data);
        $uid = uniqid();
        $output = <<<E
{$div_vrc_calendar_wrapper_open}                
<div class="vrc vrc-calendar vrc-calendar-booking-{$cal_data->calendar_enable_booking} vrc-calendar-{$cal_data->calendar_layout_options['size']} vrc-calendar-id-{$cal_data->calendar_id}" id="vrc-calendar-uid-{$uid}">
    <div class="calendar-header">
        <div>$lang_update {$last_sync_date}</div>
        <div class="pull-left">
            <div class="calendar-legend">
                <div class="day-number normal-day day_number_header"></div>
                <div class="calendar-legend-text">$lang_available&nbsp;&nbsp;</div>
                <div class="day-number event-day day_number_header"></div>
                <div class="calendar-legend-text">$lang_unavailable</div>
            </div>
        </div>
        <div class="pull-right">
            <div class="button_calaner_header">
                <div class="customNavigation">
                    <a class="btn-prev pull-left">{$Previous_title}</a> <a class="btn-next pull-right">{$Next_arrow}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="calendar-slides">
        {$nbsptext}
        {$calendar_html}
    </div>
</div>
    <div class="calendar-from">
            <div class="vrc vrcbookingformwrapper" id="vrc-booking-form-wrapper" {$showFormData}>
                <form name="vrc-booking-form" id="vrc-booking-form" class="vrc-validate" method="post" action="" novalidate="novalidate">
                    <div class="booking-heading clearfix">
                        <div id="booking-price-per-night" class="pull-left">{$getRenderCurrency}<span id="price-per-night"></span></div>
                        <div class="pull-right color-white">{$checkerDay}</div>
                    </div>
                    <div id="booking-form-fields">
                        <div class="row">
                            
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="booking_checkin_date required">{$CheckIn}</label>
                                    <input type="text" class="form-control required hasDatepicker" name="booking_checkin_date" id="booking_checkin_date" readonly="" value="" placeholder="{$CheckInDate}" aria-required="true" aria-invalid="false">
                                </div>
                            </div>
                            {$check_in_time}
        
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="booking_checkout_date required">{$CheckOut}</label>
                                    <input type="text" class="form-control required hasDatepicker" name="booking_checkout_date" id="booking_checkout_date" readonly="" value="" placeholder="{$CheckOutDate}" aria-required="true">
                                </div>
                            </div>
                            {$check_out_time}
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label for="booking_guests_count required">{$Guests}</label>
                                    <input type="number" min="1" max="{$cal_data->calendar_max_guest_no}" class="form-control required" name="booking_guests_count" id="booking_guests_count" value="1" placeholder="{$Guests}" aria-required="true">
                                </div>
                            </div>
                        
                        </div>
                         <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="user_first_name">{$FirstName}</label>
                                    <input type="text" class="form-control required" name="user_first_name" id="user_first_name" placeholder="{$FirstName}" aria-required="true">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="user_last_name">{$LastName}</label>
                                    <input type="text" class="form-control required" name="user_last_name" id="user_last_name" placeholder="{$LastName}" aria-required="true">
                                </div>
                            </div>
                         </div>
                         
                         <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="user_email">{$Email_booking}</label>
                                    <input type="email" class="form-control required " name="user_email" id="user_email" placeholder="{$Email_booking}" aria-required="true">
                                </div>
                            </div>
                        
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="user_phone">{$PhoneNumber}</label>
                                    <input type="text" class="form-control required " name="user_phone" id="user_phone" placeholder="{$PhoneNumber}" aria-required="true" />
                                </div>
                            </div>
                         </div>
        
                         <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="booking_note">{$NoteToHost}</label>
                                    <textarea class="form-control" name="booking_note" id="booking_note" placeholder="{$NoteToHost}"></textarea>
                                </div>
                            </div>
                         </div>
            
                         
                    </div>
                    <div class="bookingformcharges" id="booking-form-charges" >
                        <table class="table table-hover ">
                          <tbody>
                              
								<tr>
									<td>
										<span id ="table-basetext"></span>
									</td>
                                      
									<td>
										<span id ="table-baseprice"></span>
									</td>
								</tr>
								<tr>
									<td>{$ExtraGuestFees}</td>
									<td>{$getRenderCurrency}<span id="table-price-additional">{$zero_value}</span></td>
								</tr>
                                   
                              <tr>
                                  <td>{$CleaningFee}</td>
                                  <td>{$getRenderCurrency}<span id="table-cleaning-fee">{$zero_value}</span></td>
                              </tr>
                              <tr>
                                   <td>{$Taxes}</td>
                                   <td>{$getRenderCurrency}<span id="table-tax-amt">{$zero_value}</span></td>
                              </tr>
                              <tr>
                                   <td>{$ExtraFees}</td>
                                   <td>{$getRenderCurrency}<span id="table-extra-fees">{$zero_value}</span></td>
                              </tr>
                              <tr>
                                   <td>{$TotalReservationAmount}</td>
                                   <td>{$getRenderCurrency}<span class="total_reservation_amount">{$zero_value}</span></td>
                              </tr> 
                              <tr>
                                   <td>
                                       {$textForTotal}
                                    </td>
                                   <td>{$getRenderCurrency}<span id="table-booking-price-with-taxes">{$zero_value}</span></td>
                              </tr>
                              <tr>
                                   <td colspan="2"><div class="updated_price"><span class="rest_of_dates_data"></span></div></td>
                              </tr>
                         </tbody>
                        </table>
                   </div>
                    
                    <div id="booking-form-action">
                        <div class="row">
                            <div class="col-xs-12"> 
                                <input type="hidden" id="nightCounter" name="nightCounter" value="0" />
                                <input type="hidden" name="valid_hourly_booking" id="valid_hourly_booking" value="{$valid_hourly_booking}" />
                                <input type="hidden" name="date_of_only_hourly_booking" id="date_of_only_hourly_booking" value="" />
                                <input type="hidden" id="nightlimit" name="nightlimit" value="0" />
                                <input type="hidden" id="proonedaybook" name="proonedaybook" value="0">
                                <input type="hidden" name="cal_id" id="cal_id" value="{$this->atts['id']}">
                                <input type="hidden" id="booked_dates" value="">
                                <input type="hidden" id="vrc_pcmd" name="vrc_pcmd" value="saveBooking">
                                <input type="submit" class="btn btn-danger btn-lg col-xs-12 color-white" value="{$RequesttoBook}">
                            </div>
                        </div>
                    </div>
                    
                </form>
            </div>
        </div>
        

<script>
    jQuery( "#vrc-booking-form" ).submit(function( event ) {
        if(jQuery('#nightlimit').val() == 1)
        {
            var nightCounter = jQuery('#nightCounter').val();
            alert("Sorry, the minimum booking is for "+nightCounter+" nights. Please book again for at least "+nightCounter+" nights.");
            return false;
        }
    });
</script>
{$div_vrc_calendar_wrapper_close}
{$booking_form_location_style}
<div {$booking_powered_style}>$printattribution</div>
{$calendar_css}
E;
;

        return $output;
} else {
            return __('License Expired Please Renew', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
}

    }

    function getCalendarCSS($cal_data) {
        $style = <<<E
<style>
    .vrc.vrc-calendar.vrc-calendar-id-{$cal_data->calendar_id} .calendar-month-container {
        background:{$cal_data->calendar_layout_options['default_bg_color']};
        color:{$cal_data->calendar_layout_options['default_font_color']};
        border-color:{$cal_data->calendar_layout_options['calendar_border_color']};
    }
    .vrc.vrc-calendar.vrc-calendar-id-{$cal_data->calendar_id} * {
        color:{$cal_data->calendar_layout_options['default_font_color']};

    }
    .vrc.vrc-calendar.vrc-calendar-id-{$cal_data->calendar_id} td.calendar-day-head {
        background:{$cal_data->calendar_layout_options['week_header_bg_color']};
        color:{$cal_data->calendar_layout_options['week_header_font_color']};
    }
    .vrc.vrc-calendar.vrc-calendar-id-{$cal_data->calendar_id} td.calendar-day .day-number,
    .vrc.vrc-calendar.vrc-calendar-id-{$cal_data->calendar_id} .day-number{
        background:{$cal_data->calendar_layout_options['available_bg_color']};
        color:{$cal_data->calendar_layout_options['available_font_color']};
    }
    .vrc.vrc-calendar.vrc-calendar-id-{$cal_data->calendar_id} td.calendar-day .day-number.event-day,
     .vrc.vrc-calendar.vrc-calendar-id-{$cal_data->calendar_id} .day-number.event-day{
        background:{$cal_data->calendar_layout_options['unavailable_bg_color']};
        color:{$cal_data->calendar_layout_options['unavailable_font_color']};
    }
    .vrc.vrc-calendar.vrc-calendar-id-{$cal_data->calendar_id} td.calendar-day .day-number.event-start {
        background: {$cal_data->calendar_layout_options['available_bg_color']}; /* Old browsers */
        background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIxMDAlIiB5Mj0iMTAwJSI+CiAgICA8c3RvcCBvZmZzZXQ9IjAlIiBzdG9wLWNvbG9yPSIjZGRmZmNjIiBzdG9wLW9wYWNpdHk9IjEiLz4KICAgIDxzdG9wIG9mZnNldD0iNTAlIiBzdG9wLWNvbG9yPSIjZGRmZmNjIiBzdG9wLW9wYWNpdHk9IjEiLz4KICAgIDxzdG9wIG9mZnNldD0iNTAlIiBzdG9wLWNvbG9yPSIjZmZjMGJkIiBzdG9wLW9wYWNpdHk9IjEiLz4KICA8L2xpbmVhckdyYWRpZW50PgogIDxyZWN0IHg9IjAiIHk9IjAiIHdpZHRoPSIxIiBoZWlnaHQ9IjEiIGZpbGw9InVybCgjZ3JhZC11Y2dnLWdlbmVyYXRlZCkiIC8+Cjwvc3ZnPg==);
        background: -moz-linear-gradient(-45deg,  {$cal_data->calendar_layout_options['available_bg_color']} 0%, {$cal_data->calendar_layout_options['available_bg_color']} 50%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 50%); /* FF3.6+ */
        background: -webkit-gradient(linear, left top, right bottom, color-stop(0%,{$cal_data->calendar_layout_options['available_bg_color']}), color-stop(50%,{$cal_data->calendar_layout_options['available_bg_color']}), color-stop(50%,{$cal_data->calendar_layout_options['unavailable_bg_color']})); /* Chrome,Safari4+ */
        background: -webkit-linear-gradient(-45deg,  {$cal_data->calendar_layout_options['available_bg_color']} 0%,{$cal_data->calendar_layout_options['available_bg_color']} 50%,{$cal_data->calendar_layout_options['unavailable_bg_color']} 50%); /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(-45deg,  {$cal_data->calendar_layout_options['available_bg_color']} 0%,{$cal_data->calendar_layout_options['available_bg_color']} 50%,{$cal_data->calendar_layout_options['unavailable_bg_color']} 50%); /* Opera 11.10+ */
        background: -ms-linear-gradient(-45deg,  {$cal_data->calendar_layout_options['available_bg_color']} 0%,{$cal_data->calendar_layout_options['available_bg_color']} 50%,{$cal_data->calendar_layout_options['unavailable_bg_color']} 50%); /* IE10+ */
        background: linear-gradient(135deg,  {$cal_data->calendar_layout_options['available_bg_color']} 0%,{$cal_data->calendar_layout_options['available_bg_color']} 50%,{$cal_data->calendar_layout_options['unavailable_bg_color']} 50%); /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='{$cal_data->calendar_layout_options['available_bg_color']}', endColorstr='{$cal_data->calendar_layout_options['unavailable_bg_color']}',GradientType=1 ); /* IE6-9 fallback on horizontal gradient */
    }
    .vrc.vrc-calendar.vrc-calendar-id-{$cal_data->calendar_id} td.calendar-day .day-number.event-end {
        background: {$cal_data->calendar_layout_options['unavailable_bg_color']}; /* Old browsers */
        /* IE9 SVG, needs conditional override of 'filter' to 'none' */
        background: url(data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/Pgo8c3ZnIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgdmlld0JveD0iMCAwIDEgMSIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+CiAgPGxpbmVhckdyYWRpZW50IGlkPSJncmFkLXVjZ2ctZ2VuZXJhdGVkIiBncmFkaWVudFVuaXRzPSJ1c2VyU3BhY2VPblVzZSIgeDE9IjAlIiB5MT0iMCUiIHgyPSIxMDAlIiB5Mj0iMTAwJSI+CiAgICA8c3RvcCBvZmZzZXQ9IjUwJSIgc3RvcC1jb2xvcj0iI2ZmYzBiZCIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjUwJSIgc3RvcC1jb2xvcj0iI2RkZmZjYyIgc3RvcC1vcGFjaXR5PSIxIi8+CiAgICA8c3RvcCBvZmZzZXQ9IjEwMCUiIHN0b3AtY29sb3I9IiNkZGZmY2MiIHN0b3Atb3BhY2l0eT0iMSIvPgogIDwvbGluZWFyR3JhZGllbnQ+CiAgPHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9IjEiIGhlaWdodD0iMSIgZmlsbD0idXJsKCNncmFkLXVjZ2ctZ2VuZXJhdGVkKSIgLz4KPC9zdmc+);
        background: -moz-linear-gradient(-45deg,  {$cal_data->calendar_layout_options['unavailable_bg_color']} 50%, {$cal_data->calendar_layout_options['available_bg_color']} 50%, {$cal_data->calendar_layout_options['available_bg_color']} 100%); /* FF3.6+ */
        background: -webkit-gradient(linear, left top, right bottom, color-stop(50%,{$cal_data->calendar_layout_options['unavailable_bg_color']}), color-stop(50%,{$cal_data->calendar_layout_options['available_bg_color']}), color-stop(100%,{$cal_data->calendar_layout_options['available_bg_color']})); /* Chrome,Safari4+ */
        background: -webkit-linear-gradient(-45deg,  {$cal_data->calendar_layout_options['unavailable_bg_color']} 50%,{$cal_data->calendar_layout_options['available_bg_color']}c 50%,{$cal_data->calendar_layout_options['available_bg_color']} 100%); /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(-45deg,  {$cal_data->calendar_layout_options['unavailable_bg_color']} 50%,{$cal_data->calendar_layout_options['available_bg_color']} 50%,{$cal_data->calendar_layout_options['available_bg_color']} 100%); /* Opera 11.10+ */
        background: -ms-linear-gradient(-45deg,  {$cal_data->calendar_layout_options['unavailable_bg_color']} 50%,{$cal_data->calendar_layout_options['available_bg_color']} 50%,{$cal_data->calendar_layout_options['available_bg_color']} 100%); /* IE10+ */
        background: linear-gradient(135deg,  {$cal_data->calendar_layout_options['unavailable_bg_color']} 50%,{$cal_data->calendar_layout_options['available_bg_color']} 50%,{$cal_data->calendar_layout_options['available_bg_color']} 100%); /* W3C */
        filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='{$cal_data->calendar_layout_options['unavailable_bg_color']}', endColorstr='{$cal_data->calendar_layout_options['available_bg_color']}',GradientType=1 ); /* IE6-8 fallback on horizontal gradient */
    }
    .vrc.vrc-calendar.vrc-calendar-id-{$cal_data->calendar_id} td.calendar-day .day-number.start-end-day {
        background: {$cal_data->calendar_layout_options['unavailable_bg_color']};
        background: -moz-linear-gradient(-45deg, {$cal_data->calendar_layout_options['unavailable_bg_color']} 0%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 46%, {$cal_data->calendar_layout_options['available_bg_color']} 47%, {$cal_data->calendar_layout_options['available_bg_color']} 50%, {$cal_data->calendar_layout_options['available_bg_color']} 54%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 55%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 100%);
        background: -webkit-gradient(left top, right bottom, color-stop(0%, {$cal_data->calendar_layout_options['unavailable_bg_color']}), color-stop(46%, {$cal_data->calendar_layout_options['unavailable_bg_color']}), color-stop(47%, {$cal_data->calendar_layout_options['available_bg_color']}), color-stop(50%, {$cal_data->calendar_layout_options['available_bg_color']}), color-stop(54%, {$cal_data->calendar_layout_options['available_bg_color']}), color-stop(55%, {$cal_data->calendar_layout_options['unavailable_bg_color']}), color-stop(100%, {$cal_data->calendar_layout_options['unavailable_bg_color']}));
        background: -webkit-linear-gradient(-45deg, {$cal_data->calendar_layout_options['unavailable_bg_color']} 0%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 46%, {$cal_data->calendar_layout_options['available_bg_color']} 47%, {$cal_data->calendar_layout_options['available_bg_color']} 50%, {$cal_data->calendar_layout_options['available_bg_color']} 54%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 55%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 100%);
        background: -o-linear-gradient(-45deg, {$cal_data->calendar_layout_options['unavailable_bg_color']} 0%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 46%, {$cal_data->calendar_layout_options['available_bg_color']} 47%, {$cal_data->calendar_layout_options['available_bg_color']} 50%, {$cal_data->calendar_layout_options['available_bg_color']} 54%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 55%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 100%);
        background: -ms-linear-gradient(-45deg, {$cal_data->calendar_layout_options['unavailable_bg_color']} 0%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 46%, {$cal_data->calendar_layout_options['available_bg_color']} 47%, {$cal_data->calendar_layout_options['available_bg_color']} 50%, {$cal_data->calendar_layout_options['available_bg_color']} 54%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 55%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 100%);
        background: linear-gradient(135deg, {$cal_data->calendar_layout_options['unavailable_bg_color']} 0%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 46%, {$cal_data->calendar_layout_options['available_bg_color']} 47%, {$cal_data->calendar_layout_options['available_bg_color']} 50%, {$cal_data->calendar_layout_options['available_bg_color']} 54%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 55%, {$cal_data->calendar_layout_options['unavailable_bg_color']} 100%);
        color: #000000 !important;
        /* filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='{$cal_data->calendar_layout_options['available_bg_color']}', endColorstr='{$cal_data->calendar_layout_options['unavailable_bg_color']}',GradientType=1 ); */
    }
</style>
E;
        return $style;
    }

    function getCalendar($cal_data, $months) {

        $show_booking_from_one_page = $cal_data->show_booking_from_one_page;

        $calendar_html = '';

        $months_per_page =  $cal_data->calendar_layout_options['rows'] * $cal_data->calendar_layout_options['columns'];

        $pages = ceil($months / $months_per_page);

        $next_month = 0;
        $page = 0;
        while($page<$pages) {
            $calendar_html .= '<div class="calendar-page">';
            for($row=1; $row<=$cal_data->calendar_layout_options['rows'] && $next_month<=$months; $row++) {
                $calendar_html .= '<div class="row">';
                for($col=1; $col<=$cal_data->calendar_layout_options['columns'] && $next_month<=$months; $col++) {

                    //$next_data = date('Y-m-d', strtotime("+{$next_month} months"));
                    $next_data = date('Y-m-d', mktime(0, 0, 0, date('m')+$next_month, 1, date('Y')));
                    $month = date('n', strtotime($next_data));
                    $year =  date('Y', strtotime($next_data));

                    $col_class = floor(12/$cal_data->calendar_layout_options['columns']);

                    $calendar_html .= '<div class="col-md-'.$col_class.'">';
                    $calendar_html .= $this->getMonthCalendar($cal_data, $month, $year);
                    $calendar_html .= '</div>';
                    $next_month++;
                }
                $calendar_html .= '</div>';
            }
            $calendar_html .= '</div>';
            $page++;
        }
        $onepagevalue = 0;
        if($show_booking_from_one_page == 'yes')
            $onepagevalue = 1;

        $calendar_html.= '<input type="hidden" name="onepagevalue" id="onepagevalue" value="'.$onepagevalue.'" />';
        return $calendar_html;
    }

    function getstatusOfCalender($calendar_id, $cDate)
    {
       
        $timeArray = array('0' => __('12:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '1' => __('1:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '2' => __('2:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '3' => __('3:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '4' => __('4:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '5' => __('5:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '6' => __('6:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '7' => __('7:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '8' => __('8:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '9' => __('9:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '10' => __('10:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '11' => __('11:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '12' => __('12:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '13' => __('1:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '14' => __('2:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN),'15' => __('3:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '16' => __('4:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '17' => __('5:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '18' => __('6:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '19' => __('7:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '20' => __('8:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '21' => __('9:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '22' => __('10:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '23' => __('11:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN));
        $timeKeysget = array('0' => __('00:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '1' => __('01:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '2' => __('02:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '3' => __('03:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '4' => __('04:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '5' => __('05:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '6' => __('06:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '7' => __('07:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '8' => __('08:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '9' => __('09:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '10' => __('10:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '11' => __('11:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '12' => __('12:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '13' => __('13:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '14' => __('14:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN),'15' => __('15:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '16' => __('16:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '17' => __('17:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '18' => __('18:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '19' => __('19:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '20' => __('20:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '21' => __('21:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '22' => __('22:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '23' => __('23:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN));

       global $wpdb;
        $table_name = $wpdb->prefix . 'vrcalandar_bookings';
        $results = $wpdb->get_results( "SELECT booking_date_from, booking_date_to FROM $table_name WHERE booking_admin_approved = 'yes' AND booking_calendar_id = '".$calendar_id."' AND DATE(booking_date_to) = '".$cDate."'", OBJECT );
        if(count($results) > 0)
        {
            $arrayinTimes = array();
                foreach($results as $gettimes)
                {
                    foreach($timeKeysget as $key => $timeget)
                    {
                        $dates1 = date("H:i:s", strtotime('+'.$hoursbookingdiifference.' hours', strtotime($gettimes->booking_date_to)));
                        $dates2 = date("H:i:s", strtotime('-'.$hoursbookingdiifference.' hours', strtotime($gettimes->booking_date_from)));


                        if(date("Y-m-d", strtotime('-'.$hoursbookingdiifference.' hours', strtotime($gettimes->booking_date_from))) == $_GET['bdate']){
                            if(strtotime($dates2) > strtotime($timeget)){
                                $arrayinTimes[$key] = $timeget;
                            }else if(strtotime($dates1) <= strtotime($timeget)){
                                $arrayinTimes[$key] = $timeget;
                            }
                        }else{
                                if(strtotime($dates2) > strtotime($timeget)){
                                    $arrayinTimes[$key] = $timeget;
                                }else if(strtotime($dates1) < strtotime($timeget)){
                                    $arrayinTimes[$key] = $timeget;
                                }
                        }
                    }
                }
                if(count($arrayinTimes) > 0)
                {
                    return 1;
                }
                else {
                    return 0;
                }
        } 
        else {
            return 0;
        }
    }

    function getMonthCalendar($cal_data, $month, $year) {        
        
        $bookingCalendarIds = $cal_data->calendar_id;
        global $wpdb;
        $unable_dates = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}vrcalandar_unable_booking` WHERE `booking_calendar_id` = '".$bookingCalendarIds."'");
        

        $VRCalendarBooking = VRCalendarBooking::getInstance();
        $VRCalendarEntity = VRCalendarEntity::getInstance();

        $month_name = date_i18n('F', strtotime("{$year}-{$month}-1"));
        $year_name = date_i18n('Y', strtotime("{$year}-{$month}-1"));
        
        /* draw table */
        $calendar = '<table cellpadding="0" cellspacing="0" class="calendar">';

        $headings = array(
            __('Sun', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
            __('Mon', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
            __('Tue', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
            __('Wed', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
            __('Thu', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
            __('Fri', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
            __('Sat', VRCALENDAR_PLUGIN_TEXT_DOMAIN)
        );
        $calendar.= '<tr class="calendar-row"><td class="calendar-day-head">'.implode('</td><td class="calendar-day-head">',$headings).'</td></tr>';

        /* days and weeks vars now ... */
        $running_day = date('w',mktime(0,0,0,$month,1,$year));
        $days_in_month = date('t',mktime(0,0,0,$month,1,$year));
        $days_in_this_week = 1;
        $day_counter = 0;
        $dates_array = array();

        /* row for week one */
        $calendar.= '<tr class="calendar-row">';

        /* print "blank" days until the first of the current week */
        for($x = 0; $x < $running_day; $x++):
            $calendar.= '<td class="calendar-day-np"> </td>';
            $days_in_this_week++;
        endfor;

        /* keep going with days.... */
        for($list_day = 1; $list_day <= $days_in_month; $list_day++):
            $cDate = date('Y-m-d', mktime(0,0,0,$month,$list_day,$year));

            //custom Code
            //19-02-2016
                //$VRCalendarSettings = VRCalendarSettings::getInstance();
                $pro_one_day_book = $cal_data->pro_one_day_book;
                $hourly_booking = $cal_data->hourly_booking;
                $hoursbookingdiifference = $cal_data->hoursbookingdiifference;
                $show_booking_from_one_page = $cal_data->show_booking_from_one_page;
                
                
                /* keep going with days.... */
                global $wpdb;
            $table_name = $wpdb->prefix . 'vrcalandar_bookings';
            //$results = $wpdb->get_results( "SELECT booking_date_to, booking_date_from FROM $table_name WHERE booking_admin_approved = 'yes' AND booking_calendar_id = '".$bookingCalendarIds."' AND DATE(booking_date_to) = '".$cDate."'", OBJECT );
            $results = $wpdb->get_results( "SELECT booking_date_to, booking_date_from FROM $table_name WHERE booking_admin_approved = 'yes' AND booking_calendar_id = '".$bookingCalendarIds."' AND DATE(booking_date_to) = '".$cDate."'", OBJECT );
            
            $newdates_fromdate = '';
            $newdates_toonlydate = '';
            $newdates_todate = '';
            
            if(count($results) > 0)
            {
                $newdates_fromdate = date('Y-m-d', strtotime($results[0]->booking_date_from));
                $newdates_toonlydate = date('Y-m-d', strtotime($results[0]->booking_date_to));
                $newdates_todate =  date('Y-m-d H:i', strtotime('+'.$hoursbookingdiifference.' hours', strtotime($results[0]->booking_date_to)));
            }

            //Custom Code

            if($VRCalendarBooking->isStartEndDate($cal_data, $cDate )) {
                
                $booked_class = 'start-end-day';
                //custom calender
                if($hourly_booking == 'yes'){
                    
                    $valuesOfData = $this->getstatusOfCalender($cal_data->calendar_id, $cDate); //20-02-2016
                    if($valuesOfData == 1)
                    {
                        $booked_class = 'no-event-day'; //'no-event-day';
                    }else{
                        $booked_class = 'event-day';
                    }
                }else if($pro_one_day_book == 'yes'){
                    $booked_class = 'event-day'; //'start-end-day';
                }
                //custom calender
            }
            else if($VRCalendarBooking->isStartDate($cal_data, $cDate )) {
                
                //$booked_class = 'event-start';
                $booked_class = 'no-event-day event-start';
                
                if($hourly_booking == 'yes'){
                    
                    $valuesOfData = $this->getstatusOfCalender($cal_data->calendar_id, $cDate); //20-02-2016
                    if($valuesOfData == 1)
                    {
                        $booked_class = 'no-event-day'; //'no-event-day';
                    }else{
                        $booked_class = 'event-day';
                    }
                }else if($pro_one_day_book == 'yes'){
                    $booked_class = 'event-day'; //'start-end-day';
                }
            }
            else if($VRCalendarBooking->isEndDate($cal_data, $cDate )) {
                $booked_class = 'no-event-day event-end';
                //custom Calender
                if($hourly_booking == 'yes')
                {
                    $booked_class = 'event-day';
                }
                else if($pro_one_day_book == 'yes')
                {
                    $booked_class = 'event-day';//'no-event-day event-end';
                }
                //Custom Calender
            }
            else if( $VRCalendarBooking->isDateAvailable($cal_data, $cDate )) {
                $booked_class = 'no-event-day';
            }
            else {
                $booked_class = 'event-day';
            }
            
            //custom calender
            //unable booking dates: code start
            if(count($unable_dates) > 0)
            {
                foreach ($unable_dates as $unableDates)
                {
                    $booking_date_from = date("Y-m-d", strtotime($unableDates->booking_date_from));
                    $booking_date_to = date("Y-m-d", strtotime($unableDates->booking_date_to));
                    
                    if($unableDates->booking_date_to == '0000-00-00 00:00:00')
                    {
                        if($booking_date_from == $cDate)
                        {
                            //event-day
                            $booked_class = 'event-day';
                        }
                    }
                    
                    if(strtotime($booking_date_from) <= strtotime($cDate) && strtotime($booking_date_to) >= strtotime($cDate))
                    {
                        //event-day
                            $booked_class = 'event-day';
                    }
                }
            }
            //unable booking dates: code start
            //custom calnder
            
                        
            $booking_price = $VRCalendarEntity->getSingleNightCost($cal_data, $cDate);
			$month_namedd = date_i18n('m', strtotime("{$year}-{$month}-1"));
			$year_namedd = date_i18n('Y', strtotime("{$year}-{$month}-1"));
			$date = $year_namedd.'/'.$month_namedd.'/'.$list_day;
			$ddd = date('l', strtotime($date));

			$getcalendardata = $VRCalendarEntity->getCalendar($bookingCalendarIds);
			if($getcalendardata->weekend_pricing == 'yes' && $getcalendardata->weekend_pricing != ''){
				switch($ddd){
					case 'Friday':
						if($getcalendardata->friday_night_discount != ''){
							$booking_price = number_format($getcalendardata->friday_night_discount, 2, '.', '');
						}
					break;
					case 'Saturday':
						if($getcalendardata->saturday_night_discount != ''){
							$booking_price = number_format($getcalendardata->saturday_night_discount, 2, '.', '');
						}
					break;
					case 'Sunday':
						if($getcalendardata->sunday_night_discount != ''){
							$booking_price = number_format($getcalendardata->sunday_night_discount, 2, '.', '');
						}
					break;
				}
			}
			foreach($cal_data->calendar_price_exception as $seasonalprices){
				$begin = strtotime($seasonalprices->start_date);
				$end = strtotime($seasonalprices->end_date);
				$currentdatetime = strtotime($date);
				if(isset($begin) && isset($end)){
					if($begin <= $currentdatetime && $currentdatetime <= $end){
						$booking_price = number_format($seasonalprices->price_per_night, 2, '.', '');
					}
				}
			}


            $calendar.= '<td class="calendar-day">';
            /* add in the day number */
            $calendar.= '<div class="day-number '.$booked_class.'" data-tooltip="'.$booking_price.'" data-calendar-id="'.$cal_data->calendar_id.'" data-booking-date="'.$cDate.'" >'.$list_day.'</div>';

            $calendar.= '</td>';
            if($running_day == 6):
                $calendar.= '</tr>';
                if(($day_counter+1) != $days_in_month):
                    $calendar.= '<tr class="calendar-row">';
                endif;
                $running_day = -1;
                $days_in_this_week = 0;
            endif;
            $days_in_this_week++; $running_day++; $day_counter++;
        endfor;

        /* finish the rest of the days in the week */
        if($days_in_this_week < 8 && $days_in_this_week>1):
            for($x = 1; $x <= (8 - $days_in_this_week); $x++):
                $calendar.= '<td class="calendar-day-np"> </td>';
            endfor;
        endif;

        /* final row */
        $calendar.= '</tr>';

        /* end the table */
        $calendar.= '</table>';


        $result = <<<E
<div class="calendar-month-container">
    <div class="calendar-month-name">{$month_name} {$year_name}</div>
    {$calendar}
</div>
E;
        return $result;
    }

}