<?php
class VRCalendarSearchbarShortcode extends VRCShortcode {

    protected $slug = 'vrcalendar_searchbar';

    function shortcode_handler($atts, $content = "") {
        if (edd_sample_check_license_new()){
		global $wpdb;
        $this->atts = shortcode_atts(
            array(
                'id'=>false,                
            ),$atts, 'vrcalendar_searchbar');

        if(!$this->atts['id'])
            return __('Searchbar id is missing', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
	
        $text = (trim(($content))!='')? $content:__('Search Properties', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
		/**custom code **/
        $VRCalendarEntity = VRCalendarEntity::getInstance();
		$base64value=base64_decode($this->atts['id']);
		$baseArray=explode('--',$base64value);
		$this->atts['id'] = isset($baseArray[0])?$baseArray[0]:0;
		$data = $VRCalendarEntity->getSearchbar($this->atts['id']); 
		/**custom code **/
		
        if(empty($data->calendars))
			return;

		$precals=array();
		foreach($data->calendars as $k=>$v){
			$precals[]= $k;
		}
	//	if(is_single()){
			$outputstyle =  '<style>
					.vrc .searchbar input {
						font-size:13px;
					}
					.col-sm-4, .col-sm-3{min-height:50px;}
					.vrc .searchbar .col-sm-3, .vrc .searchbar .col-sm-4 {
						padding-right:0px;
		            }
					.vrc .searchbar select {
						padding:6px 2px;
						font-size:13px;
						width:100%;
					}
					.vrc .searchbar input[type="submit"] {
						font-size:14px;
						padding:6px 15px;
					}
					.vrc .search-hdr {
						font-size:15px !important;
						padding:0px;
						font-weight:bold;
						margin-bottom:10px;
					}
					.searchbar {
						margin:10px 0px;
						padding: 0;
					}
					.searchbar .col-sm-4:first-child, .searchbar .col-sm-3:first-child {
						padding-left:0px !important;
					}
					.vrc .table.booking-list > thead > tr > th {
						font-size:14px;
						vertical-align:top;
					}
					.vrc .booking-list td {
						font-size:14px;
					}
					.ui-slider .ui-slider-handle{z-index:1 !important;}
				</style>';
		//} 
        if(!empty($data)){
			$checkindate ='';
			$checkoutdate =''; 
			$bpricemin = 10;
			$bpricemax = 500;
			$guest = 0;
			if(isset($_POST['submit_searchbar'])){
				
				$checkindate   = $_POST['checkindate'];
				$checkoutdate  = $_POST['checkoutdate'];
				$guest         = ($_POST['totalguests'])?$_POST['totalguests']:0;
				$bpricemin     = (isset($_POST['searchbar_booking_price_min']))? $_POST['searchbar_booking_price_min']:10;
				$bpricemax     = (isset($_POST['searchbar_booking_price_max']))? $_POST['searchbar_booking_price_max']:500;
			}
			$searchaction ='';
			$divcolumnclass =  'col-sm-3';
			if($data->result_page == 'result'){
				$VRCalendarSettings = VRCalendarSettings::getInstance();
				$searchaction = get_permalink($VRCalendarSettings->getSettings('searchbar_result_page'));
			}
            if($data->use_price_filter == 'yes'){
                $divcolumnclass =  'col-sm-4';
			}
            $output  = '<form method = "POST" action = "'.$searchaction.'"><div class ="col-sm-12 searchbar" style="background-color:'.$data->color_options['search_box_background_color'].';color:'.$data->color_options['search_font_color'].';"><div class="col-sm-12 search-hdr">'.$text.' </div><div class="'.$divcolumnclass.'"><input type="text" placeholder = "'.__('Check in Date', VRCALENDAR_PLUGIN_TEXT_DOMAIN).'" name="checkindate" id="searchbar_checkindate" value= "'.$checkindate.'" class ="vrc-calendar-searchbar"></div><div class="'.$divcolumnclass.'"><input type="text"  placeholder = "'.__('Check Out Date', VRCALENDAR_PLUGIN_TEXT_DOMAIN).'" name="checkoutdate" id="searchbar_checkoutdate" value= "'.$checkoutdate.'" class="vrc-calendar-searchbar"></div>';
			$output .= '<div class="'.$divcolumnclass.'"><select name="totalguests"><option value="">'.__('# of Guests', VRCALENDAR_PLUGIN_TEXT_DOMAIN).'</option>';
			//foreach(range(1,15) as $v){
			for($v=1;$v <= $data->maximumguests; $v++){
				if($guest == $v) {
					$output .=  '<option value="'.$v.'" selected>'.$v.'</option>';
				} else {
					$output .=  '<option value="'.$v.'" >'.$v.'</option>';
				}
			}
			$output .=	'</select></div>';
			if($data->use_price_filter == 'yes'){
			    $output .=	'<div class="'.$divcolumnclass.'" style="margin:7px 0px;">'.__('View Booking Price', VRCALENDAR_PLUGIN_TEXT_DOMAIN).' : <span style="font-weight:bold;">'.renderCurrency().'<span id="searchbar-booking-price-from"> </span> - '.renderCurrency().'<span id="searchbar-booking-price-to"></span></span> </div><div class="'.$divcolumnclass.'" style="margin:12px auto;"><div id="searchbar-price-range"></div><input type="hidden" id ="searchbar-booking-price-min" name="searchbar_booking_price_min" value="'.$bpricemin.'"><input type="hidden" id ="searchbar-booking-price-max" name="searchbar_booking_price_max" value="'.$bpricemax.'"></div>';
			}
			$output .=	'<div class="'.$divcolumnclass.'"><input type="hidden" name="searchbar_id" value="'.$this->atts['id'].'"> <input type="submit" name="submit_searchbar" value="'.__('search', VRCALENDAR_PLUGIN_TEXT_DOMAIN).'" class="submit-search"style="background-color:'.$data->color_options['search_button_color'].' !important;color:'.$data->color_options['search_button_font_color'].' !important;"></div></div></form>';
		}
        $formoutput = $output.$outputstyle;
		$resultoutput ="";
		if(isset($_POST['submit_searchbar'])){
		$resultoutput .= '<table class="table table-hover booking-list" id="searchbar-result">
					<thead>
						<tr><th>'.__('Calendar Name', VRCALENDAR_PLUGIN_TEXT_DOMAIN).'</th><th>'.__('Listing Detail', VRCALENDAR_PLUGIN_TEXT_DOMAIN).'</th><th>'.__('Booking Price', VRCALENDAR_PLUGIN_TEXT_DOMAIN).'</th><th>'.__('Booking Link', VRCALENDAR_PLUGIN_TEXT_DOMAIN).'</th>	</tr>
					</thead>
					<tbody>	';				
			$count =0;
			foreach($precals as $v){
				$cal_data = $VRCalendarEntity->getCalendar( $v );
				if($cal_data->calendar_custom_field_name != ''){		
					$ExtraFees = __($cal_data->calendar_custom_field_name, VRCALENDAR_PLUGIN_TEXT_DOMAIN);
				}else{
					$ExtraFees = __('Extra Fees', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
				}
				$guest_no = $cal_data->calendar_max_guest_no;
				if($guest <= $guest_no){
					$booking_price = $VRCalendarEntity->getBookingPrice($v,$checkindate, $checkoutdate, $guest);
					$booking_price_current = $booking_price['price_per_night'];
					$tax = $cal_data->calendar_tax_per_stay;
					$tax_type = $cal_data->calendar_tax_type;
					$tax_amt = $tax;
					if($data->use_price_filter == 'yes'){
						if($booking_price_current >= $_POST['searchbar_booking_price_min'] && $booking_price_current <= $_POST['searchbar_booking_price_max']){ 
							$VRCalendarBooking = VRCalendarBooking::getInstance();
                            $item = $VRCalendarBooking->isDateRangeAvailableNew($cal_data, $_POST['checkindate'], $_POST['checkoutdate'], $_POST['checkindate']);
							if($item == 'T'){
								$calname = ($cal_data->calendar_name !='')?$cal_data->calendar_name:'No Name';
								$isbookingenable = $cal_data->calendar_enable_booking;
								$bookingdata = __('Booking is disabled ', VRCALENDAR_PLUGIN_TEXT_DOMAIN );
								if($isbookingenable == 'yes'){
                                    $booking_price = $VRCalendarEntity->getBookingPrice($v,$checkindate, $checkoutdate, $guest);
									 $bookingdata = __('Default Price Per Night', VRCALENDAR_PLUGIN_TEXT_DOMAIN)." : ".renderCurrency().$booking_price['price_per_night']." <br/> ";
									 $bookingdata .= __('Booking base price', VRCALENDAR_PLUGIN_TEXT_DOMAIN)." : ".renderCurrency().$booking_price['base_booking_price']." <br/> ";
                                     /*if(($cal_data->calendar_offer_monthly == 'yes') || ($cal_data->calendar_offer_weekly == 'yes')){
										if($booking_price['booking_dys'] == $booking_price['booking_days'] && $booking_price['booking_months'] == 0 && $booking_price['booking_weeks'] == 0){
									   }else{
                                            $bookingdata .=	 $booking_price['special_offer_text'].": ". renderCurrency().$booking_price['base_booking_price']." <br/>";
									   }                                       
									}*/
                                    if($booking_price['totaladditionalcharge'] > 0)
									$bookingdata .=	 __('Extra Guest Fees Per Night', VRCALENDAR_PLUGIN_TEXT_DOMAIN)." : ".renderCurrency().$booking_price['totaladditionalcharge']." <br/>";

									$bookingdata .=	 __('Cleaning Fee Per Stay', VRCALENDAR_PLUGIN_TEXT_DOMAIN)." : ".renderCurrency(). $booking_price['cleaning_fee'] ."<br/>".$ExtraFees." : ".renderCurrency().$booking_price['extra_fees']."<br/>Tax : ".renderCurrency(). $booking_price['tax_amt'];
								}
								$VRCalendarSettings = VRCalendarSettings::getInstance();
								$bdate = date('Y-m-d');
								$booking_url = add_query_arg(array('cid'=>$v, 'sbcindate'=>$checkindate, 'sbcoutdate'=>$checkoutdate, 'gno'=>$guest), get_permalink($VRCalendarSettings->getSettings('booking_page')) );
								
								$bookinglink = "<a href='{$booking_url}'>".__('Book Now', VRCALENDAR_PLUGIN_TEXT_DOMAIN)."</a>";
								$resultoutput.= "<tr><td>$calname</td><td>";
								$calendar_booking_url = $cal_data->calendar_booking_url;
								if($calendar_booking_url){
									$resultoutput.= "<a href='".$calendar_booking_url."'>".__('Listing Detail', VRCALENDAR_PLUGIN_TEXT_DOMAIN)."</a>";
								}else{
									$resultoutput.= __("N/A", VRCALENDAR_PLUGIN_TEXT_DOMAIN);
								}
								$resultoutput.= "</td><td>$bookingdata</td><td>";
								if($isbookingenable == 'yes'){ $resultoutput.= $bookinglink;}else{
									$resultoutput.= __('Booking is disabled ', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
								}
								$resultoutput.= "</td></tr>";
							$count++;}
						}
				    }else{
						$VRCalendarBooking = VRCalendarBooking::getInstance();	
						
						$item = $VRCalendarBooking->isDateRangeAvailableNew($cal_data, $_POST['checkindate'], $_POST['checkoutdate'], $_POST['checkindate']);	

						if($item == 'T'){						          
							$calname = ($cal_data->calendar_name !='')?$cal_data->calendar_name:'No Name';
							$isbookingenable = $cal_data->calendar_enable_booking;
							$bookingdata = __('Booking is disabled ', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
							if($isbookingenable == 'yes'){							        
									$booking_price = $VRCalendarEntity->getBookingPrice($v,$checkindate, $checkoutdate, $guest);
								    $bookingdata = __('Default Price Per Night', VRCALENDAR_PLUGIN_TEXT_DOMAIN)." : ".renderCurrency().$booking_price['price_per_night']." <br/> ";
									$bookingdata .= __('Booking base price', VRCALENDAR_PLUGIN_TEXT_DOMAIN)." : ".renderCurrency().$booking_price['base_booking_price']." <br/> ";
                                    /*if(($cal_data->calendar_offer_monthly == 'yes') || ($cal_data->calendar_offer_weekly == 'yes')){
										if($booking_price['booking_dys'] == $booking_price['booking_days'] && $booking_price['booking_months'] == 0 && $booking_price['booking_weeks'] == 0){
									   }else{
                                            $bookingdata .=	 $booking_price['special_offer_text'].": ". renderCurrency().$booking_price['base_booking_price']." <br/>";
									   }                                       
									}*/
                                    if($booking_price['totaladditionalcharge'] > 0)
									$bookingdata .=	 __('Extra Guest Fees Per Night', VRCALENDAR_PLUGIN_TEXT_DOMAIN)." : ".renderCurrency().$booking_price['totaladditionalcharge']." <br/>";

									$bookingdata .=	 __('Cleaning Fee Per Stay', VRCALENDAR_PLUGIN_TEXT_DOMAIN)." : ".renderCurrency(). $booking_price['cleaning_fee'] ."<br/> ".__('View', VRCALENDAR_PLUGIN_TEXT_DOMAIN)." ".__($ExtraFees, VRCALENDAR_PLUGIN_TEXT_DOMAIN)." : ".renderCurrency().$booking_price['extra_fees']."<br/>Tax : ".renderCurrency(). $booking_price['tax_amt'];
							}
							$VRCalendarSettings = VRCalendarSettings::getInstance();
							$bdate = date('Y-m-d');
							$booking_url = add_query_arg(array('cid'=>$v, 'sbcindate'=>$checkindate, 'sbcoutdate'=>$checkoutdate, 'gno'=>$guest), get_permalink($VRCalendarSettings->getSettings('booking_page')) );
							
							$bookinglink = "<a href='{$booking_url}'>".__('Book Now', VRCALENDAR_PLUGIN_TEXT_DOMAIN)."</a>";
							$resultoutput.= "<tr><td>$calname</td><td>";
							$calendar_booking_url = $cal_data->calendar_booking_url;
							if($calendar_booking_url){
								$resultoutput.= "<a href='".$calendar_booking_url."'>".__('Listing Detail', VRCALENDAR_PLUGIN_TEXT_DOMAIN)."</a>";
							}else{
								$resultoutput.= __("N/A", VRCALENDAR_PLUGIN_TEXT_DOMAIN);
							}
							$resultoutput.= "</td><td>$bookingdata</td><td>";
							if($isbookingenable == 'yes'){ $resultoutput.= $bookinglink;}else{
								$resultoutput.= __('Booking is disabled', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
							}
							$resultoutput.= "</td></tr>";
						$count++;}
					}
			   }
			}
			if($count < 1){
                $resultoutput.= "<tr><td>".__('Sorry! No results found. Please search again.', VRCALENDAR_PLUGIN_TEXT_DOMAIN)."</td><td></td><td></td><td></td></tr>";
			}
			$resultoutput.= "</tbody></table>";
		}
		return "<div class='vrc row' style='width:100%;float:left;'>".$formoutput.$resultoutput."</div>";
            
        } else {
             _e('****VRCalendarsync License Expired Please Renew****', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        }

    }
        
}