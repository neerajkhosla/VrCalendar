<?php
class VRCalendarSearchbarResultShortcode extends VRCShortcode {

    protected $slug = 'vrcalendar_searchbar_result';

    function shortcode_handler($atts, $content = "") {        
		global $wpdb;
        $this->atts = shortcode_atts(array(
                'id'=>false,                
            ),
           $atts, 'vrcalendar_searchbar_result');		
        if(isset($_POST['submit_searchbar'])){
            $VRCalendarEntity = VRCalendarEntity::getInstance();
			$searchbar_id =$_POST['searchbar_id'];
			$data = $VRCalendarEntity->getSearchbar($searchbar_id);             
			$checkindate   = $_POST['checkindate'];
			$checkoutdate  = $_POST['checkoutdate'];
			$guest         = ($_POST['totalguests'])?$_POST['totalguests']:0;
			$bpricemin     = (isset($_POST['searchbar_booking_price_min']))? $_POST['searchbar_booking_price_min']:10;
			$bpricemax     = (isset($_POST['searchbar_booking_price_max']))? $_POST['searchbar_booking_price_max']:500;

			$VRCalendarSettings = VRCalendarSettings::getInstance();
			$searchaction = get_permalink($VRCalendarSettings->getSettings('searchbar_result_page'));
			
			$divcolumnclass =  'col-sm-3';			
            if($data->use_price_filter == 'yes'){
                $divcolumnclass =  'col-sm-4';
			}
			$outputstyle =  '
            <link href="'.plugins_url("/wp_vrcalendar_ent_2_2_1c/FrontAdmin/css/frontend.css").'" rel="stylesheet"/> 
            <style>
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
				</style>  ';
            $output  = '<form method = "POST" ><div class ="col-sm-12 searchbar" style="background-color:'.$data->color_options['search_box_background_color'].';color:'.$data->color_options['search_font_color'].';"><div class="col-sm-12 search-hdr">'.__('Search Properties', VRCALENDAR_PLUGIN_TEXT_DOMAIN).' </div><div class="'.$divcolumnclass.'"><input type="text" placeholder = "'.__('Check in Date', VRCALENDAR_PLUGIN_TEXT_DOMAIN).'" name="checkindate" id="searchbar_checkindate" value= "'.$checkindate.'" class ="vrc-calendar-searchbar"></div><div class="'.$divcolumnclass.'"><input type="text"  placeholder = "'.__('Check Out Date', VRCALENDAR_PLUGIN_TEXT_DOMAIN).'" name="checkoutdate" id="searchbar_checkoutdate" value= "'.$checkoutdate.'" class="vrc-calendar-searchbar"></div>';
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
			    $output .=	'<div class="'.$divcolumnclass.'" style="margin:7px 0px;">'.__('Booking Price', VRCALENDAR_PLUGIN_TEXT_DOMAIN).': <span style="font-weight:bold;">'.renderCurrency().'<span id="searchbar-booking-price-from"> </span> - '.renderCurrency().'<span id="searchbar-booking-price-to"></span></span> </div><div class="'.$divcolumnclass.'" style="margin:12px auto;"><div id="searchbar-price-range"></div><input type="hidden" id ="searchbar-booking-price-min" name="searchbar_booking_price_min" value="'.$bpricemin.'"><input type="hidden" id ="searchbar-booking-price-max" name="searchbar_booking_price_max" value="'.$bpricemax.'"></div>';
			}
			$output .=	'<div class="'.$divcolumnclass.'"><input type="hidden" name="searchbar_id" value="'.$searchbar_id.'"> <input type="submit" name="submit_searchbar" value="'.__("search", VRCALENDAR_PLUGIN_TEXT_DOMAIN).'" class="submit-search"style="background-color:'.$data->color_options['search_button_color'].' !important;color:'.$data->color_options['search_button_font_color'].' !important;"></div></div></form>';
		
        $formoutput = $output.$outputstyle;
			$precals=array();
			foreach($data->calendars as $k=>$v){
				$precals[]= $k;
			}
			$resultoutput = '<style>.col-sm-3, .col-sm-6, .col-sm-9, .col-sm-12{margin-bottom:20px;}label{font-weight:normal !important;}.dataTables_length select{float:left !important;}</style><table class="table table-hover booking-list"><thead><tr><th></th></tr></thead>					
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
								$bookingdata = __('Booking is disabled', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
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

										$bookingdata .=	 __('Cleaning Fee Per Stay', VRCALENDAR_PLUGIN_TEXT_DOMAIN)." : ".renderCurrency(). $booking_price['cleaning_fee'] ."<br/>".__($ExtraFees, VRCALENDAR_PLUGIN_TEXT_DOMAIN)." : ".renderCurrency().$booking_price['extra_fees']."<br/>".__('Tax', VRCALENDAR_PLUGIN_TEXT_DOMAIN)." : ".renderCurrency(). $booking_price['tax_amt'];
								}
								$VRCalendarSettings = VRCalendarSettings::getInstance();
								$bdate = date('Y-m-d');
								$booking_url = add_query_arg(array('cid'=>$v, 'sbcindate'=>$checkindate, 'sbcoutdate'=>$checkoutdate, 'gno'=>$guest), get_permalink($VRCalendarSettings->getSettings('booking_page')) );						
								$bookinglink = "<a  style=\"float:right\" class=\"btn btn-info\" href='{$booking_url}'>".__('Book Now', VRCALENDAR_PLUGIN_TEXT_DOMAIN)."</a>";

								$resultoutput.= "<tr><td>";

								$imgdata ="<div style='width:100%;height:180px;background-color:#f5f5f5;'></div>";
								$cal_address="USA";
								if($cal_data->calendar_listing_image != '')
								$imgdata ="<img class=\"listing_image\" src=\"$cal_data->calendar_listing_image\" >";

								if($cal_data->calendar_listing_address != '')
								$cal_address = $cal_data->calendar_listing_address;

								if($data->show_image == 'yes' && $data->show_address == 'yes'){
									$resultoutput.= "<div class=\"col-sm-3\"> $imgdata </div><div id=\"googleMap$count\" class=\"col-sm-3  gmap\" rel =\"$cal_address\" style=\"height:180px;\" ></div><div class=\"col-sm-6\"> <p><strong>$calname</strong></p><p> $bookingdata </p> $cal_data->calendar_summary";
								}elseif($data->show_image == 'yes' && $data->show_address != 'yes'){
									$resultoutput.= "<div class=\"col-sm-3\"> $imgdata </div><div class=\"col-sm-9\"> <p><strong>$calname</strong></p><p> $bookingdata </p> $cal_data->calendar_summary";
								}elseif($data->show_image != 'yes' && $data->show_address == 'yes'){
									$resultoutput.= "<div id=\"googleMap$count\" style=\"height:180px;\"  rel =\"$cal_address\"  class=\"col-sm-3 gmap\" ></div><div class=\"col-sm-9\"> <p><strong>$calname</strong></p><p> $bookingdata </p> $cal_data->calendar_summary";
								}else{
									$resultoutput.= "<div class=\"col-sm-12\"> <p><strong>$calname</strong></p><p> $bookingdata </p> $cal_data->calendar_summary";
								}
					   
								$resultoutput .= "<p>";
								if($isbookingenable == 'yes'){ $resultoutput.= $bookinglink;}
								$calendar_booking_url = $cal_data->calendar_booking_url;
								if($calendar_booking_url){
									$resultoutput.= " <a style=\"float:right;margin-right:10px;\" class=\"btn btn-info\" href='".$calendar_booking_url."'>".__('View', VRCALENDAR_PLUGIN_TEXT_DOMAIN)."</a>";
								}
								$resultoutput .= "</p>";							
								$resultoutput.= "</div></td></tr>";
							$count++;}
						}
				    }else{
						$VRCalendarBooking = VRCalendarBooking::getInstance();					
						$item = $VRCalendarBooking->isDateRangeAvailableNew($cal_data, $_POST['checkindate'], $_POST['checkoutdate'], $_POST['checkindate']);	

						if($item == 'T'){						          
							$calname = ($cal_data->calendar_name !='')?$cal_data->calendar_name:'No Name';
							$isbookingenable = $cal_data->calendar_enable_booking;
							$bookingdata = __("Booking is disabled ", VRCALENDAR_PLUGIN_TEXT_DOMAIN);
							if($isbookingenable == 'yes'){
							       $booking_price = $VRCalendarEntity->getBookingPrice($v,$checkindate, $checkoutdate, $guest);
								    $bookingdata = "Default Price Per Night : ".renderCurrency().$booking_price['price_per_night']." <br/> "; 
									$bookingdata .= __('Booking base price', VRCALENDAR_PLUGIN_TEXT_DOMAIN)." : ".renderCurrency().$booking_price['base_booking_price']." <br/> ";
                                     /*if(($cal_data->calendar_offer_monthly == 'yes') || ($cal_data->calendar_offer_weekly == 'yes')){
										if($booking_price['booking_dys'] == $booking_price['booking_days'] && $booking_price['booking_months'] == 0 && $booking_price['booking_weeks'] == 0){
									   }else{
                                            $bookingdata .=	 $booking_price['special_offer_text'].": ". renderCurrency().$booking_price['base_booking_price']." <br/>";
									   }                                       
									}*/
                                    if($booking_price['totaladditionalcharge'] > 0)
									$bookingdata .=	 __('Extra Guest Fees Per Night', VRCALENDAR_PLUGIN_TEXT_DOMAIN)." : ".renderCurrency().$booking_price['totaladditionalcharge']." <br/>";

									$bookingdata .=	 __('Cleaning Fee Per Stay', VRCALENDAR_PLUGIN_TEXT_DOMAIN)." : ".renderCurrency(). $booking_price['cleaning_fee'] ."<br/>".__($ExtraFees, VRCALENDAR_PLUGIN_TEXT_DOMAIN)."  : ".renderCurrency().$booking_price['extra_fees']."<br/>".__('Tax', VRCALENDAR_PLUGIN_TEXT_DOMAIN)." : ".renderCurrency(). $booking_price['tax_amt'];
							}
							$VRCalendarSettings = VRCalendarSettings::getInstance();
							$bdate = date('Y-m-d');
							$booking_url = add_query_arg(array('cid'=>$v, 'sbcindate'=>$checkindate, 'sbcoutdate'=>$checkoutdate, 'gno'=>$guest), get_permalink($VRCalendarSettings->getSettings('booking_page')) );						
							$bookinglink = "<a  style=\"float:right\" class=\"btn btn-info\" href='{$booking_url}'>".__('Book Now', VRCALENDAR_PLUGIN_TEXT_DOMAIN)."</a>";

							$resultoutput.= "<tr><td>";

							$imgdata ="<div style='width:100%;height:180px;background-color:#f5f5f5;'></div>";
							$cal_address="USA";
							if($cal_data->calendar_listing_image != '')
							$imgdata ="<img src=\"$cal_data->calendar_listing_image\" >";

							if($cal_data->calendar_listing_address != '')
                            $cal_address = $cal_data->calendar_listing_address;

							if($data->show_image == 'yes' && $data->show_address == 'yes'){
							    $resultoutput.= "<div class=\"col-sm-3\"> $imgdata </div><div id=\"googleMap$count\" class=\"col-sm-3  gmap\" rel =\"$cal_address\" style=\"height:180px;\" ></div><div class=\"col-sm-6\"> <p><strong>$calname</strong></p><p> $bookingdata </p> $cal_data->calendar_summary";
							}elseif($data->show_image == 'yes' && $data->show_address != 'yes'){
                                $resultoutput.= "<div class=\"col-sm-3\"> $imgdata </div><div class=\"col-sm-9\"> <p><strong>$calname</strong></p><p> $bookingdata </p> $cal_data->calendar_summary";
							}elseif($data->show_image != 'yes' && $data->show_address == 'yes'){
                                $resultoutput.= "<div id=\"googleMap$count\" style=\"height:180px;\"  rel =\"$cal_address\"  class=\"col-sm-3 gmap\" ></div><div class=\"col-sm-9\"> <p><strong>$calname</strong></p><p> $bookingdata </p> $cal_data->calendar_summary";
							}else{
                                $resultoutput.= "<div class=\"col-sm-12\"> <p><strong>$calname</strong></p><p> $bookingdata </p> $cal_data->calendar_summary";
							}
                   
							$resultoutput .= "<p>";
							if($isbookingenable == 'yes'){ $resultoutput.= $bookinglink;}
							$calendar_booking_url = $cal_data->calendar_booking_url;
							if($calendar_booking_url){
								$resultoutput.= " <a style=\"float:right;margin-right:10px;\" class=\"btn btn-info\" href='".$calendar_booking_url."'>".__('View', VRCALENDAR_PLUGIN_TEXT_DOMAIN)."</a>";
							}
							$resultoutput .= "</p>";							
							$resultoutput.= "</div></td></tr>";
						$count++;}
					}
			   }
			}
			if($count < 1){
                $resultoutput.= "<tr><td>".__('Sorry! No results found. Please search again.', VRCALENDAR_PLUGIN_TEXT_DOMAIN)."</td></tr>";
			}
			$resultoutput.= "</tbody></table>";
			return "<div class='vrc row' style='width:100%;float:left;'>".$formoutput.$resultoutput."</div>";
		}
		return '';
    }    
}