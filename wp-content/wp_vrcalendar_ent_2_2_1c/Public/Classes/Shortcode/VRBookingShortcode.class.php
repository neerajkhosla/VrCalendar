<?php
class VRBookingShortcode extends VRCShortcode {

    protected $slug = 'vrc_booking';

    function getDatesFromRange($startDate, $endDate)
    {
        $return = array($startDate);
        $start = $startDate;
        $i=1;
        if (strtotime($startDate) < strtotime($endDate))
        {
           while (strtotime($start) < strtotime($endDate))
            {
                $start = date('Y-m-d', strtotime($startDate.'+'.$i.' days'));
                $return[] = $start;
                $i++;
            }
        }

        return $return;
    }
    
    function shortcode_handler($atts, $content = "") {

        if(!$_GET['cid'])
            return __('Calendar id is missing', VRCALENDAR_PLUGIN_TEXT_DOMAIN);

        $VRCalendarBooking = VRCalendarBooking::getInstance();
        $VRCalendarEntity = VRCalendarEntity::getInstance();
		/**custom code 24-08-2016**/	
        $get_base64  = $_GET['cid'];	
		$cal_Details = base64_decode($get_base64);
		//die($cal_Details);
		$cal_array=explode("+",$cal_Details);		
		$cal_id=isset($cal_array[0]) ? $cal_array[0] : 0; // calendar id
		$checkin= isset($cal_array[1]) && $cal_array[1]!="" ? $cal_array[1]:date('Y-m-d'); // booking date
        //  $checkin = (isset($_GET['bdate'])) ? $_GET['bdate'] : date('Y-m-d');
        $cal_data = $VRCalendarEntity->getCalendar( $cal_id );

        if($cal_data->calendar_enable_booking != 'yes')
            return __('Bookings are disabled.', VRCALENDAR_PLUGIN_TEXT_DOMAIN);

        while(!$VRCalendarBooking->isDateAvailable($cal_data, $checkin)) 
             {
                $checkin = date('Y-m-d', strtotime("+1 day", strtotime($checkin)));
             }
        
        $checkout = date('Y-m-d', strtotime("+1 day", strtotime($checkin)));

        $pro_one_day_book = $cal_data->pro_one_day_book;

        $minimum_number_of_night = $cal_data->minimum_number_of_night;
         $number_of_night = $cal_data->number_of_night;
         
         $hourly_booking = $cal_data->hourly_booking;
         
         $nightCounter = $cal_data->number_of_night;
         
        if($pro_one_day_book == 'yes')
            {
                 global $wpdb;
                 $table_name = $wpdb->prefix.'vrcalandar_bookings';
                 $results = $wpdb->get_results( "SELECT * FROM $table_name WHERE `booking_calendar_id` = '".$cal_id."' AND DATE(booking_date_from) = '".$checkout."'", OBJECT );
                 if(count($results) > 0)
                 {
                    $checkout = $checkin;
                 }
                 
                 if($minimum_number_of_night != 'yes')
                 {
                     $checkout = $checkin;
                 }
                 
            }
        
        global $wpdb;
        
        $check_seasonal = $wpdb->get_results("SELECT * FROM `wp_vrcalandar_price_variation` WHERE `calendar_id` = '{$cal_id}' AND DATE(`variation_start_date`) <= '{$checkin}' AND DATE(`variation_end_date`) > '{$checkin}'", OBJECT );
        if(count($check_seasonal) > 0)
        {
                $nightCounter =  $check_seasonal[0]->seasonal_minimum_nights;
                $number_of_night= $check_seasonal[0]->seasonal_minimum_nights; 
                
               if($pro_one_day_book != 'yes')
               {
                $nightCounter =  $check_seasonal[0]->seasonal_minimum_nights;
                $number_of_night= $check_seasonal[0]->seasonal_minimum_nights; 
                $checkout = date("Y-m-d",  strtotime("+{$number_of_night} day", strtotime($checkin)));
               }
               
               $date1 = new DateTime($checkin);
               $date2 = new DateTime($checkout);
               $nightlimit_new = $date2->diff($date1)->format("%a");
               
               if($nightlimit_new >= $number_of_night)
               {
                   $nightlimit = 0;
               }else{
                   $nightlimit = 1;
               }
               //die();
               
        }
        elseif($minimum_number_of_night == 'yes')
        {
            
            $nightTesttDate = date("Y-m-d",  strtotime("+{$number_of_night} day", strtotime($checkin)));
            global $wpdb;
            $table_name = $wpdb->prefix.'vrcalandar_bookings';
            $results = $wpdb->get_results( "SELECT * FROM wp_vrcalandar_bookings WHERE `booking_calendar_id` = '{$cal_id}' AND `booking_status` = 'confirmed' AND (DATE(booking_date_from) <= '{$nightTesttDate}' AND DATE(booking_date_to) >= '{$nightTesttDate}')", OBJECT );
            
            
            if(count($results) == 0)
            {
                $checkout = $nightTesttDate;
                
            }
            else {
                $nightlimit = 1;
            }
            
        }elseif($hourly_booking == 'yes')
        {
           $checkout = $checkin; 
        }
        
        
        if(isset($_GET['sbcindate']) && isset($_GET['sbcoutdate'])){
            $checkin  = $_GET['sbcindate'];
            $checkout = $_GET['sbcoutdate'];			
		}
                
        
        $total_guest_no = 1;
        if(isset($_GET['gno']))
        {
            $total_guest_no = $_GET['gno'];
        }

        
        $booked_dates = $VRCalendarBooking->getBookedDates($cal_data);     


         $dates[] = array();
        
        if($pro_one_day_book == 'yes')
        {
            $booked_dates = array();
           global $wpdb;
           $table_name = $wpdb->prefix . 'vrcalandar_bookings';
           
           $results = $wpdb->get_results( "SELECT * FROM $table_name WHERE booking_calendar_id = '".$cal_data->calendar_id."'", OBJECT );
           
           foreach($results as $datesOfEnd)
           {
               $start = date('Y-m-d', strtotime($datesOfEnd->booking_date_from));
               $end = date('Y-m-d', strtotime($datesOfEnd->booking_date_to));
               
               
               $counttervalue = count($this->getDatesFromRange( $start, $end ));
                if($counttervalue > 0)
                {
                    $dateArray =  $this->getDatesFromRange( $start, $end );
                    foreach ($dateArray as $dateValue)
                    {
                        $booked_dates[] = $dateValue;
                    }
                }
           }
        }
        
        $booking_price = $VRCalendarEntity->getBookingPrice($cal_id, $checkin, $checkout, $total_guest_no);

      
        $tax_type = $cal_data->calendar_tax_type;
        $tax = $cal_data->calendar_tax_per_stay;
        if($tax_type == "percentage") {
            $tax = ($cal_data->calendar_price_per_night * $tax)/100;
        }
        

        $data = array(
            'calendar_data'=>$cal_data,
            'booking_price'=>$booking_price, //number_format((float)$booking_price, 2, '.', ''),
            'check_in_date'=>$checkin,
            'check_out_date'=>$checkout,
            'booked_dates'=>$booked_dates,
            'total_guest_no'=> $total_guest_no,
            'nightlimit' => $nightlimit,
            'nightcounter'=>$nightCounter
        );

        return $this->renderView('Booking', $data);

    }
}