<?php
class VRCalendarBooking extends VRCSingleton {

    public $table_name;

    private $calendar_id;

    protected function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix.'vrcalandar_bookings';
    }

    function createTable() {
        global $wpdb; 
		$this->table_name = $wpdb->prefix.'vrcalandar_bookings';
        $calendar_table_sql = "CREATE TABLE {$this->table_name} (
            booking_id INT(11) NOT NULL AUTO_INCREMENT,			
			booking_calendar_id INT(11),
			booking_source TEXT,
			booking_date_from DATETIME,
			booking_date_to DATETIME,
            booking_guests INT(11),
            booking_user_fname TEXT,
            booking_user_lname TEXT,
            booking_user_email TEXT,
            booking_user_phone VARCHAR(255),
            booking_summary TEXT,
            booking_status ENUM('pending','confirmed'),
            booking_payment_status ENUM('pending','confirmed','not_required'),
            booking_admin_approved ENUM('yes','no'),
            booking_payment_data TEXT,
            booking_sub_price TEXT,
            booking_total_price TEXT,
            booking_created_on DATETIME,
            booking_modified_on DATETIME,			
			PRIMARY KEY  (booking_id)
		);";
        dbDelta($calendar_table_sql);
        
    }

    function createTableUnableDate() {
         
         global $wpdb; 
         $vrcalandar_unable_booking = $wpdb->prefix.'vrcalandar_unable_booking';
        
        $unable_booking = "CREATE TABLE {$vrcalandar_unable_booking} (
        unable_calandar_id INT(11) NOT NULL AUTO_INCREMENT,
        booking_calendar_id INT(11),
        booking_date_from DATETIME,
        booking_date_to DATETIME,
        PRIMARY KEY  (unable_calandar_id));";
        dbDelta($unable_booking);
    }

    function saveUnableBookingDate($data) {
       
        $calendar_id = $data['calendar_id'];
        global $wpdb;
        $vrcalandar_unable_booking = $wpdb->prefix.'vrcalandar_unable_booking';
         
        $sql_delete = "DELETE FROM {$vrcalandar_unable_booking} WHERE booking_calendar_id = '".$calendar_id."'";
        $wpdb->query($sql_delete);
        
                
        if(count($data['from_to_unable_date']['from_date']) > 0)
        {
            foreach ($data['from_to_unable_date']['from_date'] as $keys=>$dates)
            {
                $from_date = $data['from_to_unable_date']['from_date'][$keys];
                $to_date = $data['from_to_unable_date']['to_date'][$keys];
                
                $sql_insert = "INSERT INTO {$vrcalandar_unable_booking} (`booking_calendar_id`, `booking_date_from`, `booking_date_to`) VALUES ('".$calendar_id."', '".$from_date."', '".$to_date."')";
                $wpdb->query($sql_insert);
            }
        }
        return true;
    }

    function saveBooking($data) {
        
        $user_phone = $_POST['user_phone'];
        
        global $wpdb;
        $data['booking_sub_price'] = json_encode($data['booking_sub_price']);
        $data['booking_payment_data'] = json_encode($data['booking_payment_data']);

        $data['booking_summary'] = htmlentities($data['booking_summary'], ENT_QUOTES);

        if(@$data['booking_id']>0) {
            $sql = "update {$this->table_name} set booking_calendar_id='{$data['booking_calendar_id']}', booking_source='{$data['booking_source']}', booking_date_from='{$data['booking_date_from']}', booking_date_to='{$data['booking_date_to']}', booking_guests='{$data['booking_guests']}', booking_user_fname='{$data['booking_user_fname']}', booking_user_lname='{$data['booking_user_lname']}', booking_user_email='{$data['booking_user_email']}', booking_summary='{$data['booking_summary']}', booking_status='{$data['booking_status']}', booking_payment_status='{$data['booking_payment_status']}', booking_admin_approved='{$data['booking_admin_approved']}', booking_payment_data='{$data['booking_payment_data']}', booking_sub_price='{$data['booking_sub_price']}', booking_total_price='{$data['booking_total_price']}', booking_modified_on='{$data['booking_modified_on']}', booking_user_phone='{$user_phone}' where booking_id='{$data['booking_id']}';";
        }
        else {
            $sql = "insert into {$this->table_name} (booking_calendar_id, booking_source, booking_date_from, booking_date_to, booking_guests, booking_user_fname, booking_user_lname, booking_user_email, booking_summary, booking_status, booking_payment_status, booking_admin_approved, booking_payment_data, booking_sub_price, booking_total_price, booking_created_on, booking_modified_on, booking_user_phone) values ('{$data['booking_calendar_id']}', '{$data['booking_source']}', '{$data['booking_date_from']}', '{$data['booking_date_to']}', '{$data['booking_guests']}', '{$data['booking_user_fname']}', '{$data['booking_user_lname']}', '{$data['booking_user_email']}', '{$data['booking_summary']}', '{$data['booking_status']}', '{$data['booking_payment_status']}', '{$data['booking_admin_approved']}', '{$data['booking_payment_data']}', '{$data['booking_sub_price']}', '{$data['booking_total_price']}', '{$data['booking_created_on']}', '{$data['booking_modified_on']}', '{$user_phone}');";
        }
        $wpdb->query($sql);

        if(@$data['booking_id']<=0 || !isset($data['booking_id']))
            return $wpdb->insert_id;

        return true;
    }

    function deleteBooking($booking_id) {
        global $wpdb;
        $sql = "delete from {$this->table_name} where booking_id='{$booking_id}'";
        $wpdb->query($sql);
        return true;
    }

    function approveBooking($booking_id) {
        $VRCalendarSettings = VRCalendarSettings::getInstance();
        $VRCalendar = VRCalendarEntity::getInstance();

        $booking_details = $this->getBookingByID($booking_id);
        $cal_data = $VRCalendar->getCalendar($booking_details->booking_calendar_id);


        $booking_details_data =  json_decode(json_encode($booking_details), true);
        $booking_details_data['booking_admin_approved'] = __('yes', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $booking_details_data['booking_status'] = __('confirmed', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        
               
        $this->saveBooking( $booking_details_data );

        $VRCTransactionalEmail = VRCTransactionalEmail::getInstance();

        $booking_payment_link = add_query_arg(array('bid'=>$booking_id), get_permalink($VRCalendarSettings->getSettings('payment_page')) );

        $email_data = array(
            'calendar_id'=>$booking_details->booking_calendar_id,
            'booking_user_fname'=>$booking_details->booking_user_fname,
            'booking_user_lname'=>$booking_details->booking_user_lname,
            'booking_payment_link'=>$booking_payment_link,
        );

        if($cal_data->calendar_payment_method == 'none') {
            $VRCTransactionalEmail->sendBookingApprovedNoPayment($email_data, array($booking_details->booking_user_email));
        } else {
            $VRCTransactionalEmail->sendBookingApprovedPayment($email_data, array($booking_details->booking_user_email));
        }
    }



    function getBookingByID($booking_id) {
        global $wpdb;
        $sql = "select * from {$this->table_name} where booking_id='{$booking_id}'";
        $data = $wpdb->get_row($sql);
        
        if(count($data) > 0)
        {
            $data->booking_sub_price = json_decode($data->booking_sub_price, true);
            $data->booking_payment_data = json_decode($data->booking_payment_data, true);
            $data->booking_summary = html_entity_decode($data->booking_summary, ENT_QUOTES);
        }   
        return $data;
    }

    function getBookings($calendar_id) {
        global $wpdb;
        $sql = "select booking_id from {$this->table_name} where booking_calendar_id='{$calendar_id}'";
        $data = $wpdb->get_results($sql);

        $arr = array();

        foreach($data as $tdata) {
            $arr[] = $this->getBookingByID($tdata->booking_id);
        }
        return $arr;
    }

    function removeBookingsBySource($calendar_id, $source) {
        global $wpdb;
        $sql = "delete from {$this->table_name} where booking_calendar_id='{$calendar_id}' and booking_source='{$source}'";
        $wpdb->query($sql);
        return true;
    }

    function removeBookingsExceptLocal($calendar_id) {
        global $wpdb;
        $sql = "delete from {$this->table_name} where booking_calendar_id='{$calendar_id}' and booking_source != 'website'";
        $wpdb->query($sql);
        return true;
    }
    function removeCalendarBookings($calendar_id) {
        global $wpdb;
        $sql = "delete from {$this->table_name} where booking_calendar_id='{$calendar_id}'";
        $wpdb->query($sql);
        return true;
    }

    function checkBookingConflicts( $booking_data ) {
        global $wpdb;
        /* Set booking till date to -1 */
        $booking_data['booking_date_to'] = date('Y-m-d', strtotime("-1 day", strtotime($booking_data['booking_date_to'])));
        $sql = "select count(booking_id) from {$this->table_name} where booking_calendar_id='{$booking_data['booking_calendar_id']}' and ( date(booking_date_from) BETWEEN date('{$booking_data['booking_date_from']}') and date('{$booking_data['booking_date_to']}') OR date(DATE_SUB(booking_date_to, INTERVAL 1 DAY)) BETWEEN date('{$booking_data['booking_date_from']}') and date('{$booking_data['booking_date_to']}') OR date('{$booking_data['booking_date_from']}') BETWEEN date(booking_date_from) and date(DATE_SUB(booking_date_to, INTERVAL 1 DAY)) )";
        $booking_count = $wpdb->get_var($sql);

        if($booking_count>0) {
            $VRCalendarEntity = VRCalendarEntity::getInstance();
            $cal = $VRCalendarEntity->getCalendar( $booking_data['booking_calendar_id'] );

            /* Send email to admin with details of conflict */
            $recipients = array(
                get_option('admin_email')
            );
            $email_data = array(
                'calendar_id'=>$booking_data['booking_calendar_id'],
                'calendar_name'=> $cal->calendar_name,
                'booking_source'=> $booking_data['booking_source'],
                'booking_date_from'=> $booking_data['booking_date_from'],
                'booking_date_to'=> $booking_data['booking_date_to'],
                'booking_summary'=> $booking_data['booking_summary'],
            );

            $VRCTransactionalEmail = VRCTransactionalEmail::getInstance();
            $VRCTransactionalEmail->sendSyncConflict($email_data, $recipients);

        }
    }
    function availableTill( $cal_id, $start_date ) {
        $sql = "select date(booking_date_from) from {$this->table_name} where booking_calendar_id='{$cal_id}'and booking_status = 'confirmed'  and date(booking_date_from) > date('{$start_date}') order by booking_date_from asc limit 1";		
        global $wpdb;
        $booking_range_ends = $wpdb->get_var($sql);
        /* Dec 1 date */
        $VRCalendarEntity = VRCalendarEntity::getInstance();
        $cal_data = $VRCalendarEntity->getCalendar( $cal_id );

        if(!empty($booking_range_ends)) {
            //$booking_range_ends = date('Y-m-d', strtotime("-1 day", strtotime($booking_range_ends)) );
            //$booking_range_ends = date('Y-m-d', strtotime($booking_range_ends) ); // comment 03-03-2016
            $VRCalendarSettings = VRCalendarSettings::getInstance();
            $pro_one_day_book = $cal_data->pro_one_day_book;
            $booking_range_ends = date('Y-m-d', strtotime($booking_range_ends) );
            if($pro_one_day_book == 'yes')
                $booking_range_ends = date ( 'Y-m-d', strtotime ( '-1 day' . $booking_range_ends ) );
        }
        return $booking_range_ends;
    }

    function getBookedDates( $cal_data ) {
        global $wpdb;

        /* loop from first of this month to 36 months */
        $unavailable_dates = array();
        for($i=0; $i<36; $i++) {
            $next_date = date('Y-m-d', strtotime("+{$i} months"));
            $month = date('n', strtotime($next_date));
            $year =  date('Y', strtotime($next_date));
            $days_in_month = date('t',mktime(0,0,0,$month,1,$year));

            for($list_day = 1; $list_day <= $days_in_month; $list_day++) {
                $cDate = date('Y-m-d', mktime(0,0,0,$month,$list_day,$year));
                if(!$this->isDateAvailable($cal_data, $cDate )) {
                    $unavailable_dates[] = $cDate;
                }
            }
        }
        return $unavailable_dates;
    }

    function isDateRangeAvailable($cal_data, $start_date, $end_date) {
        global $wpdb;
        //$cdate = $start_date;
        
        //Do I need to check dates to make sure not to go out of bounds? not sure yet
        //Start one day ahead - we should ignore start date in while loop
        $cdate = date('Y-m-d', strtotime("$start_date + 1 day"));
        
        //Take one day of end date - we should ignore end date in while loop
        $cdate_end = date('Y-m-d', strtotime("$end_date - 1 day"));
        
        //If same day return false right away
        if($this->isStartEndDate($cal_data, $start_date )) {
           return false;
        }
    
        //Next, do while loop through all days except start / end days to check if any are booked
        else {
            while(strtotime($cdate)<=strtotime($cdate_end)) {
                if(!$this->isDateAvailable($cal_data, $cdate)) {
                    return false;
                }
                $cdate = date('Y-m-d', strtotime("$cdate + 1 day"));
            } //end while
        } //end else
        
        
         //Now check start date, if its NOT available AND
         //if it is NOT on an end day of another reservation, then we exit
         if( (!$this->isDateAvailable($cal_data, $start_date) AND
             !$this->isEndDate($cal_data, $start_date))
            ){
             return false;
         }
        
         //Now check end date, if it is NOT available OR
         //if it is NOT ending on a start day of another reservation, then we exit
         else if( (!$this->isDateAvailable($cal_data, $end_date) AND
                   !$this->isStartDate($cal_data, $end_date))
                 ){
             return false;
         }
         
         //if we are here without returning false, then the date is avaiable so return true
         else{
             return true;
         }
       
    }
    function isDateRangeAvailableNew($cal_data, $_start_date, $end_date, $first_date) {
		global $wpdb;
		if($_start_date == '' || $end_date =='')
		return 'F';

		$_sdata = $_start_date;
		$_enddate = $end_date;
		$start_date = $sdata= strtotime($_start_date);
		$end_date	= strtotime($end_date);
				
		$count = 0;
		
		 for($start_date; $start_date < $end_date; $start_date = strtotime("$_start_date + 1 day") ) {
			$_start_date = date('Y-m-d',$start_date);
			$booking_date_from =  date('Y-m-d',$sdata);
			$booking_date_to  = date('Y-m-d',strtotime("$_start_date + 1 day"));			
			
			 $sql = "select count(booking_id) from {$this->table_name} where booking_calendar_id='{$cal_data->calendar_id}' and booking_status = 'confirmed' and ( date(booking_date_from) = date('{$booking_date_from}') AND date(booking_date_to) = date('{$booking_date_to}')) ";
			 //echo '<br>';
			$booking_count = $wpdb->get_var($sql);

			if($booking_count>0) {
				return 'F';
			}
			
			/*if($count>20){
				die('In loop');
				break;
			}*/

			$count++;
		 }
		 
		 $booking_date_from =  date('Y-m-d',strtotime("$_sdata + 1 day"));
		
		 if($booking_date_from ==  $_enddate){
			
			$sql = "select count(booking_id) from {$this->table_name} where booking_calendar_id='{$cal_data->calendar_id}' and booking_status = 'confirmed' and ( ( date(booking_date_from) <= date('{$first_date}') AND date(booking_date_to) > date('{$first_date}') ) OR ( date(booking_date_from) < date('{$_enddate}') AND date(booking_date_to) > date('{$_enddate}') ) )";			
			$booking_count = $wpdb->get_var($sql);
			if($booking_count>0) {	
				
				return 'F';
			}

			return 'T';
		 }	
		 return $this->isDateRangeAvailableNew($cal_data, $booking_date_from, $_enddate, $first_date); 
        
	}
    function isDateAvailable($cal_data, $date) {
        global $wpdb;
        $sql = "select count(booking_id) from {$this->table_name} where booking_calendar_id='{$cal_data->calendar_id}' and booking_status = 'confirmed' and ( date(booking_date_from) BETWEEN date('{$date}') and date('{$date}') OR date(booking_date_to) BETWEEN date('{$date}') and date('{$date}') OR date('{$date}') BETWEEN date(booking_date_from) and date(booking_date_to) )";
        $booking_count = $wpdb->get_var($sql);
        if($booking_count>0) {
            if($this->isStartEndDate($cal_data, $date)) {
                return false;
            }
            if(!$this->isEndDate($cal_data, $date)) {
                return false;
            }
        }

        /* Dates in past are also not allowed */
        $current_data = date('Y-m-d');
        if(strtotime($date)<strtotime($current_data))
            return false;

        return true;
    }

    function isStartEndDate($cal_data, $date ) {
        if($this->isStartDate($cal_data, $date) && $this->isEndDate($cal_data, $date))
            return true;

        return false;
    }
    function isStartDate($cal_data, $date ) {
        global $wpdb;
        $sql = "select count(booking_id) from {$this->table_name} where booking_calendar_id='{$cal_data->calendar_id}' and booking_status ='confirmed' and ( date(booking_date_from) = date('{$date}') )";
        $booking_count = $wpdb->get_var($sql);
        if($booking_count>0)
            return true;

        return false;
    }
    function isEndDate($cal_data, $date ) {
        global $wpdb;
        $sql = "select count(booking_id) from {$this->table_name} where booking_calendar_id='{$cal_data->calendar_id}' and booking_status ='confirmed' and ( date(booking_date_to) = date('{$date}') )";
        $booking_count = $wpdb->get_var($sql);
        if($booking_count>0)
            return true;

        return false;
    }
}