<?php
class VRCalendarEntity extends VRCSingleton {

    public $table_name;
    public $price_variation_table_name;
    public $searchbar_table_name;
    private $calendar_id;

    protected function __construct() {
        global $wpdb;
        $this->table_name = $wpdb->prefix.'vrcalandar';
        $this->price_variation_table_name = $wpdb->prefix.'vrcalandar_price_variation';
		$this->searchbar_table_name = $wpdb->prefix.'vrcalandar_searchbar';
    }
    function createTable() {

        global $wpdb;
		$this->table_name = $wpdb->prefix.'vrcalandar';
        $this->price_variation_table_name = $wpdb->prefix.'vrcalandar_price_variation';
		$this->searchbar_table_name = $wpdb->prefix.'vrcalandar_searchbar';

        $calendar_table_sql = "CREATE TABLE {$this->table_name} (
			calendar_id INT(11) NOT NULL AUTO_INCREMENT,
			calendar_name TEXT,
			calendar_links TEXT,
			calendar_layout_options TEXT,
			calendar_enable_booking ENUM('yes','no'),
            calendar_price_per_night FLOAT(10,2),
            calendar_cfee_per_stay FLOAT(10,2),
            calendar_tax_per_stay FLOAT(10,2),
            calendar_tax_type ENUM('flat','percentage'),
			calendar_payment_method TEXT,
			calendar_alert_double_booking ENUM('yes','no'),
			calendar_requires_admin_approval ENUM('yes','no'),
			calendar_author_id INT(11),
			calendar_is_synchronizing ENUM('yes','no') DEFAULT 'no',
			calendar_last_synchronized DATETIME,
			calendar_created_on DATETIME,
			calendar_modified_on DATETIME,
			calendar_booking_url TEXT,
			calendar_max_guest_no INT(11),
			calendar_extracharge_after_guest_no INT(11),
			calendar_extracharge_after_limited_guests FLOAT(10,2),
			calendar_extra_fees FLOAT(10,2),
			calendar_offer_weekly ENUM('yes','no'),
			calendar_offer_monthly ENUM('yes','no'),
			calendar_price_weekly FLOAT(10,2),
			calendar_price_monthly FLOAT(10,2),
			calendar_listing_image TEXT,
            calendar_listing_address TEXT,
			calendar_summary TEXT,
			calendar_display_num_months VARCHAR(11) NOT NULL,
			calendar_custom_field_name VARCHAR(100),
			weekend_pricing VARCHAR(10) NOT NULL,
			friday_night_discount VARCHAR(10) NOT NULL,
			saturday_night_discount VARCHAR(10) NOT NULL,
			sunday_night_discount VARCHAR(10) NOT NULL,
			email_address_template VARCHAR(200) NOT NULL,
			email_send_to VARCHAR(10) NOT NULL,
			PRIMARY KEY  (calendar_id)
		);";
        dbDelta($calendar_table_sql);       
        $calendar_price_variation_table_sql = "CREATE TABLE {$this->price_variation_table_name} (
			variation_id INT(11) NOT NULL AUTO_INCREMENT,
			calendar_id INT(11),
			variation_start_date DATE,
			variation_end_date DATE,
			variation_price_per_night FLOAT(10,2),
			variation_price_per_week FLOAT(10,2),
			variation_price_per_month FLOAT(10,2),
			PRIMARY KEY  (variation_id)
		);";
        $wpdb->query("ALTER TABLE `{$this->price_variation_table_name}` ADD `seasonal_minimum_nights` TEXT NOT NULL");
        dbDelta($calendar_price_variation_table_sql);
		$calendar_searchbar_table_sql = "CREATE TABLE {$this->searchbar_table_name} (		
		    id int(11) NOT NULL AUTO_INCREMENT,
		    calendars text NOT NULL,
			maximumguests VARCHAR(10) NOT NULL,
		    name varchar(255) NOT NULL,
		    color_options text NOT NULL,
		    author varchar(255) NOT NULL,
			page_id int(11) NOT NULL,
		    created_on date NOT NULL,
			result_page ENUM('result','same'),
			use_price_filter ENUM('yes','no'),
			show_image ENUM('yes','no'),
			show_address ENUM('yes','no'),
		    UNIQUE KEY id (id)
		);";
		dbDelta($calendar_searchbar_table_sql);

        //03-03-2016
        $alter_table_vrcalandar = "ALTER TABLE `{$this->table_name}` ADD `deposit_percentage` INT NOT NULL";
        $wpdb->query($alter_table_vrcalandar);
        //03-03-2016
        $alter_table_vrcalandar = "ALTER TABLE `{$this->table_name}` ADD `pro_one_day_book` TEXT NOT NULL";
        $wpdb->query($alter_table_vrcalandar);
        
        $alter_table_vrcalandar = "ALTER TABLE `{$this->table_name}` ADD `hourly_booking` TEXT NOT NULL";
        $wpdb->query($alter_table_vrcalandar);
        
        $alter_table_vrcalandar = "ALTER TABLE `{$this->table_name}` ADD `hoursbookingdiifference` TEXT NOT NULL";
        $wpdb->query($alter_table_vrcalandar);
        
        $alter_table_vrcalandar = "ALTER TABLE `{$this->table_name}` ADD `show_booking_from_one_page` TEXT NOT NULL";
        $wpdb->query($alter_table_vrcalandar);
        //03-03-2016
        $rest_of_day = "ALTER TABLE `{$this->table_name}` ADD `rest_of_day` INT NOT NULL";
        $wpdb->query($rest_of_day);
        
        $deposit_enable = "ALTER TABLE `{$this->table_name}` ADD `deposit_enable` ENUM(  'yes',  'no' ) NOT NULL DEFAULT  'no'";
        $wpdb->query($deposit_enable);

        /***/
        $minimum_number_of_night = "ALTER TABLE `{$this->table_name}` ADD `minimum_number_of_night` ENUM(  'yes',  'no' ) NOT NULL DEFAULT  'no'";
        $wpdb->query($minimum_number_of_night);

        $number_of_night = "ALTER TABLE `{$this->table_name}` ADD `number_of_night` TEXT NOT NULL";
        $wpdb->query($number_of_night);
        
        $seasonal_minimum_nights = "ALTER TABLE `{$this->table_name}` ADD `seasonal_minimum_nights` TEXT NOT NULL";
        $wpdb->query($seasonal_minimum_nights);
        
        /***/
        
         $alter_table_vrcalandar = "ALTER TABLE `{$this->table_name}` ADD `booking_form_location` TEXT NOT NULL";
        $wpdb->query($alter_table_vrcalandar);

        $email_template = $wpdb->prefix.'email_template';
        
        $email_template_table_sql = "CREATE TABLE {$email_template} (
            template_id INT(11) NOT NULL AUTO_INCREMENT,
                        calendar_id INT(11) NOT NULL,
                        template_kay TEXT,
            template_slug TEXT,
            template_text TEXT,
                        PRIMARY KEY  (template_id)
        );";
        dbDelta($email_template_table_sql);

    }
    function getAllCalendar() {
        global $wpdb;
        $sql = "select calendar_id from {$this->table_name} where calendar_author_id=".get_current_user_id()." ";
        $cals = $wpdb->get_results($sql); 
        $arr = array();
        foreach($cals as $cal) {
            $arr[] = $this->getCalendar($cal->calendar_id);
        }
        return $arr;
    }
    function saveCalendar($data) {
               
        global $wpdb;
        $data['calendar_name'] = htmlentities($data['calendar_name'],ENT_QUOTES);
        $data['calendar_links'] = json_encode($data['calendar_links']);
        $data['calendar_layout_options'] = json_encode($data['calendar_layout_options']);
        $calendar_id = $data['calendar_id'];

        $hourly_booking = '';
        $hoursbookingdiifference = '';
        
        if($data['pro_one_day_book'] == 'yes')
        {
            $hourly_booking = $data['hourly_booking'];
            $hoursbookingdiifference = $data['hoursbookingdiifference'];
        }
        
        if($data['deposit_enable'] == '')
        {
            $data['deposit_percentage'] = 0;
            $data['rest_of_day'] = 0;
            $data['deposit_enable'] = __('no', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        }
        
        $booking_form_location = '';
        if($data['show_booking_from_one_page'] == 'yes')
        {
            $booking_form_location = $data['booking_form_location'];
        }

        $minimum_number_of_night = __('no', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $number_of_night = 1;
        
        $seasonal_minimum_nights = 0;
        
        if($data['minimum_number_of_night'] == 'yes')
        {
            $minimum_number_of_night = __('yes', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
            $number_of_night = $data['number_of_night'];
            $seasonal_minimum_nights = 0;
        }

                    

        if($data['calendar_id']>0  && @$data['calendar_subcase'] != 'dup') {
            $cal_data = $this->getCalendar($data['calendar_id']);

            if(!isset($data['calendar_is_synchronizing']))
                $data['calendar_is_synchronizing']=$cal_data->calendar_is_synchronizing;
            if(!isset($data['calendar_last_synchronized']))
                $data['calendar_last_synchronized']=$cal_data->calendar_last_synchronized;

            $sql = "update {$this->table_name} set calendar_name='{$data['calendar_name']}', calendar_links='{$data['calendar_links']}', calendar_layout_options='{$data['calendar_layout_options']}', calendar_enable_booking='{$data['calendar_enable_booking']}', calendar_price_per_night='{$data['calendar_price_per_night']}', calendar_cfee_per_stay='{$data['calendar_cfee_per_stay']}', calendar_tax_per_stay='{$data['calendar_tax_per_stay']}', calendar_tax_type='{$data['calendar_tax_type']}', calendar_payment_method='{$data['calendar_payment_method']}', calendar_alert_double_booking='{$data['calendar_alert_double_booking']}', calendar_requires_admin_approval='{$data['calendar_requires_admin_approval']}', calendar_is_synchronizing = '{$data['calendar_is_synchronizing']}', calendar_last_synchronized='{$data['calendar_last_synchronized']}', calendar_modified_on='{$data['calendar_modified_on']}', calendar_booking_url='{$data['calendar_booking_url']}', calendar_max_guest_no='{$data['calendar_max_guest_no']}' ,calendar_extracharge_after_guest_no='{$data['calendar_extracharge_after_guest_no']}', calendar_extracharge_after_limited_guests='{$data['calendar_extracharge_after_limited_guests']}', calendar_extra_fees='{$data['calendar_extra_fees']}', calendar_offer_weekly='{$data['calendar_offer_weekly']}', calendar_offer_monthly='{$data['calendar_offer_monthly']}', calendar_price_weekly='{$data['calendar_price_weekly']}', calendar_price_monthly='{$data['calendar_price_monthly']}', calendar_listing_image='{$data['calendar_listing_image']}', calendar_listing_address='{$data['calendar_listing_address']}', calendar_summary='{$data['calendar_summary']}', calendar_display_num_months='{$data['calendar_display_num_months']}', calendar_custom_field_name='{$data['calendar_custom_field_name']}', weekend_pricing = '{$data['weekend_pricing']}', friday_night_discount = '{$data['friday_night_discount']}', saturday_night_discount = '{$data['saturday_night_discount']}', sunday_night_discount = '{$data['sunday_night_discount']}', email_address_template='{$data['email_address_template']}', email_send_to='{$data['email_send_to']}', deposit_percentage = 
			'{$data['deposit_percentage']}', rest_of_day = '{$data['rest_of_day']}', pro_one_day_book = '{$data['pro_one_day_book']}', hourly_booking = '{$hourly_booking}', hoursbookingdiifference = '{$hoursbookingdiifference}', show_booking_from_one_page = '{$data['show_booking_from_one_page']}', deposit_enable = '{$data['deposit_enable']}', booking_form_location='{$booking_form_location}', minimum_number_of_night = '{$minimum_number_of_night}', number_of_night = '{$number_of_night}', seasonal_minimum_nights = '{$seasonal_minimum_nights}'  where calendar_id='{$data['calendar_id']}';";
        }
        else {
            $sql = "insert into {$this->table_name} (calendar_name, calendar_links, calendar_layout_options, calendar_enable_booking, calendar_price_per_night, calendar_cfee_per_stay, calendar_tax_per_stay, calendar_tax_type, calendar_payment_method, calendar_alert_double_booking, calendar_requires_admin_approval, calendar_author_id, calendar_created_on, calendar_modified_on, calendar_booking_url, calendar_max_guest_no, calendar_extracharge_after_guest_no, calendar_extracharge_after_limited_guests, calendar_extra_fees, calendar_offer_weekly, calendar_offer_monthly, calendar_price_weekly, calendar_price_monthly, calendar_listing_image, calendar_listing_address, calendar_summary, calendar_display_num_months, calendar_custom_field_name, weekend_pricing, friday_night_discount, saturday_night_discount, sunday_night_discount, email_address_template, email_send_to, deposit_percentage, rest_of_day, pro_one_day_book, hourly_booking, hoursbookingdiifference, show_booking_from_one_page, deposit_enable, booking_form_location, minimum_number_of_night, number_of_night, seasonal_minimum_nights) values ('{$data['calendar_name']}', '{$data['calendar_links']}', '{$data['calendar_layout_options']}', '{$data['calendar_enable_booking']}', '{$data['calendar_price_per_night']}', '{$data['calendar_cfee_per_stay']}', '{$data['calendar_tax_per_stay']}', '{$data['calendar_tax_type']}', '{$data['calendar_payment_method']}', '{$data['calendar_alert_double_booking']}', '{$data['calendar_requires_admin_approval']}', '{$data['calendar_author_id']}', '{$data['calendar_created_on']}', '{$data['calendar_modified_on']}', '{$data['calendar_booking_url']}', '{$data['calendar_max_guest_no']}', '{$data['calendar_extracharge_after_guest_no']}', '{$data['calendar_extracharge_after_limited_guests']}', '{$data['calendar_extra_fees']}', '{$data['calendar_offer_weekly']}', '{$data['calendar_offer_monthly']}', '{$data['calendar_price_weekly']}', '{$data['calendar_price_monthly']}','{$data['calendar_listing_image']}', '{$data['calendar_listing_address']}', '{$data['calendar_summary']}', '{$data['calendar_display_num_months']}', '{$data['calendar_custom_field_name']}', '{$data['weekend_pricing']}', '{$data['friday_night_discount']}', '{$data['saturday_night_discount']}', '{$data['sunday_night_discount']}', '{$data['email_address_template']}', '{$data['email_send_to']}', '{$data['deposit_percentage']}', '{$data['rest_of_day']}', '{$data['pro_one_day_book']}', '{$hourly_booking}', '{$hoursbookingdiifference}', '{$data['show_booking_from_one_page']}', '{$data['deposit_enable']}', '{$booking_form_location}', '{$minimum_number_of_night}', '{$number_of_night}', '{$seasonal_minimum_nights}');";
        }
        $wpdb->query($sql);
        if($data['calendar_id']<=0 || ($data['calendar_id']>0 && @$data['calendar_subcase'] =='dup'))
            $calendar_id = $wpdb->insert_id;
        /* Remove all price variations */
        $sql = "delete from {$this->price_variation_table_name} where calendar_id='{$calendar_id}'";
        $wpdb->query($sql);
        /* Now insert new variations */
        
        
        foreach($data['calendar_price_exception'] as $price_exception) {
            $sql = "insert into {$this->price_variation_table_name} (calendar_id, variation_start_date, variation_end_date, variation_price_per_night, variation_price_per_week, variation_price_per_month, seasonal_minimum_nights) values ('{$calendar_id}', '{$price_exception['start_date']}', '{$price_exception['end_date']}', '{$price_exception['price_per_night']}', '{$price_exception['price_per_week']}', '{$price_exception['price_per_month']}', '{$price_exception['seasonal_minimum_nights']}')";
            $wpdb->query($sql);
        }

        //2016-03-03
        if(count($data['emailtemplate']))
        {
            $email_template_table = $wpdb->prefix.'email_template';
            $sql_delete_template = "DELETE FROM {$email_template_table} WHERE calendar_id = '".$calendar_id."'";
            $wpdb->query($sql_delete_template);
            foreach ($data['emailtemplate'] as $key => $emailtemplate)
            {
                foreach ($emailtemplate as $template_key => $text)
                {
                    $sql_email_sql = "INSERT INTO {$email_template_table} (`calendar_id`, `template_kay`, `template_slug`, `template_text`) VALUES ('".$calendar_id."', '".$key."', '".$template_key."', '".$emailtemplate[$template_key]."')";
                    $wpdb->query($sql_email_sql);
                }
            }
        }

        //return true;
        return $calendar_id;
    }   

    function getEmailTemplate($calendar_id, $template_kay, $template_slug) {
        global $wpdb;
        $email_template_table = $wpdb->prefix.'email_template';
        $sql_email_template = "SELECT template_text AS {$template_slug} FROM `".$email_template_table."` WHERE `calendar_id` = '{$calendar_id}' AND `template_kay` = '{$template_kay}' AND `template_slug` = '{$template_slug}'";
         return $wpdb->get_row($sql_email_template, ARRAY_A);
    }

	function saveSearchbar($data) {
        global $wpdb;
		$data['name'] = htmlentities($data['searchbarname'],ENT_QUOTES);
        $data['color_options'] = json_encode($data['searchbar_color_options']);
		$data['calendars'] = json_encode($data['calendars']);
		$data['author'] = $data['author'];
		$data['created_on'] = $data['created_on'] ;
		if(isset($data['searchbar_id'])){
			$searchbar_id = $data['searchbar_id'];
            $sql = "update {$this->searchbar_table_name} set calendars='{$data['calendars']}',  maximumguests='{$data['maximumguests']}', name='{$data['name']}', color_options='{$data['color_options']}', author='{$data['author']}', created_on='{$data['created_on']}', result_page='{$data['result_page']}', use_price_filter='{$data['use_price_filter']}', show_image='{$data['show_image']}', show_address='{$data['show_address']}' where id='{$searchbar_id}';";
			$wpdb->query($sql);
		}else{
            $sql = "insert into {$this->searchbar_table_name} (calendars, maximumguests, name, color_options, author, created_on, result_page, use_price_filter, show_image, show_address) values ('{$data['calendars']}', '{$data['maximumguests']}', '{$data['name']}', '{$data['color_options']}', '{$data['author']}', '{$data['created_on']}', '{$data['result_page']}', '{$data['use_price_filter']}', '{$data['show_image']}', '{$data['show_address']}');";
			$wpdb->query($sql);
			$lastid = $wpdb->insert_id;
			$the_page = get_page_by_title( $data['name'] );
            if ( ! $the_page ) {
                $_p = array();
                $_p['post_title'] = $data['name'];
                $_p['post_content'] = '[vrcalendar_searchbar id="'.$lastid.'" /]';
                $_p['post_status'] = __('publish', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
                $_p['post_type'] = __('page', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
                $_p['comment_status'] = __('closed', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
                $_p['ping_status'] = __('closed', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
                // Insert the post into the database
                $the_page_id = wp_insert_post( $_p );
            }
            else {
                $the_page_id = $the_page->ID;
                //make sure the page is not trashed...
                $the_page->post_status = __('publish', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
                $the_page->post_content = '[vrcalendar_searchbar id="'.$lastid.'" /]';
                $the_page_id = wp_update_post( $the_page );
            }
			$sql = "update {$this->searchbar_table_name} set page_id='{$the_page_id}' where id='{$lastid}';";
			$wpdb->query($sql);
		} 		
		return true;
	}
	function getSearchbar($searchbar_id) {
        global $wpdb;
        $sql = "select * from {$this->searchbar_table_name} where id='{$searchbar_id}'";
        $data = $wpdb->get_row($sql);

        if(isset($data->id)) {
            $data->name = html_entity_decode($data->name,ENT_QUOTES);
            $data->color_options = json_decode($data->color_options, true);
            $data->calendars = json_decode($data->calendars);
            $data->created_on = $data->created_on;
			$data->author = $data->author;			       
        }
        return $data;
    }
	function getAllSearchbar() {
        global $wpdb;
        $sql = "select id from {$this->searchbar_table_name} where author =".get_current_user_id()."";
        $bars = $wpdb->get_results($sql);
        $arr = array();
		if(!empty($bars)){
			foreach($bars as $bar) {
				$arr[] = $this->getSearchbar($bar->id);
			}
		}
        return $arr;
    }
	function deleteSearchbar($searchbar_id) {       
        global $wpdb;		
        $sql = "delete from {$this->searchbar_table_name} where id='{$searchbar_id}'";
        $wpdb->query($sql);        
        return true;
    }	
    function deleteCalendar($calendar_id) {
        $VRCalendarBooking = VRCalendarBooking::getInstance();
        global $wpdb;
        $sql = "delete from {$this->table_name} where calendar_id='{$calendar_id}'";
        $wpdb->query($sql);
        $sql = "delete from {$this->price_variation_table_name} where calendar_id='{$calendar_id}'";
        $wpdb->query($sql);

        $VRCalendarBooking->removeCalendarBookings($calendar_id);
        return true;
    }

    function saveEmailTemplate($calendar_id, $clone_calendar_id)
    {   
        global $wpdb;
        $table_name = $wpdb->prefix ."email_template";

        $results = $wpdb->get_results( "SELECT * FROM {$table_name} WHERE `calendar_id` = '".$calendar_id."'", OBJECT );
        foreach ($results as $key => $value) 
        {
            # code...
            $wpdb->query("INSERT INTO {$table_name} (`calendar_id`, `template_kay`, `template_slug`, `template_text`) VALUES ('".$clone_calendar_id."', '".$value->template_kay."', '".$value->template_slug."', '".$value->template_text."')");
        }
        
        return true;
    }

    function cloneCalendar($calendar_id) {
        
        $cal = $this->getCalendar($calendar_id);
        $cal = json_decode(json_encode($cal), true);
        $cal['calendar_id'] = 0;
        $cal['calendar_name'] = 'Copy-'.$cal['calendar_name'];
        $cal['calendar_created_on'] = date('Y-m-d H:i:s');
        $cal['calendar_modified_on'] = date('Y-m-d H:i:s');
        $cal['calendar_author_id'] = get_current_user_id();
        $clone_calendar_id = $this->saveCalendar($cal);

        $get_calendar_id = $this->saveEmailTemplate($calendar_id, $clone_calendar_id);

        return true;
    }

    function downloadCalendar($calendar_id) {

        $cal = $this->getCalendar($calendar_id);
        $VRCalendarBooking = VRCalendarBooking::getInstance();

        $bookings = $VRCalendarBooking->getBookings($calendar_id);

        $events = array();
        foreach($bookings as $booking) {
            $event_parameters = array(
                'uid' =>  $booking->booking_id,
                'summary' => $booking->booking_summary,
                'start' => new DateTime(''.$booking->booking_date_from ),
                'end' => new DateTime(''.$booking->booking_date_to),
            );
            $events[] = new VRCCalendarEvent($event_parameters);
        }

        $cal_parameters = array(
            'events'=>$events,
            'title'=>$cal->calendar_name,
            'author'=> get_the_author_meta('display_name', $cal->calendar_author_id)
        );

        $calendar = new VRCCalendar( $cal_parameters );

        $calendar->generateDownload();
    }

    function getCalendar($calendar_id, $fillter = false) {
        global $wpdb;
        //$sql = "select * from {$this->table_name} where calendar_id='{$calendar_id}'";
        $sql = "select * from {$this->table_name} where calendar_id='{$calendar_id}'";
       /* if(isset($fillter))
        {
            if($fillter == 'all')
            {
                $sql = "select * from {$this->table_name} where calendar_id='{$calendar_id}'";
                
            }else{
                $vrcalandar = $wpdb->prefix . 'vrcalandar';
                $vrcalandar_bookings = $wpdb->prefix . 'vrcalandar_bookings';
                if($fillter != '')
                {

                    $sql = "SELECT {$vrcalandar}. *  FROM {$vrcalandar} INNER JOIN {$vrcalandar_bookings} ON {$vrcalandar}.calendar_id = {$vrcalandar_bookings}.booking_calendar_id WHERE {$vrcalandar}.calendar_id =  '{$calendar_id}' AND {$vrcalandar_bookings}.booking_source LIKE  '%{$fillter}%'";
                }
            }
        }*/

        $data = $wpdb->get_row($sql);

        if(isset($data->calendar_id)) {
            $data->calendar_name = html_entity_decode($data->calendar_name,ENT_QUOTES);
            $data->calendar_layout_options = json_decode($data->calendar_layout_options, true);
            $data->calendar_links = json_decode($data->calendar_links);
            $data->calendar_price_exception = array();
            $sql = "select * from {$this->price_variation_table_name} where calendar_id='{$calendar_id}'";
            $data_variation = $wpdb->get_results($sql);

            foreach ($data_variation as $variation) {
                $tmp = new stdClass();
                $tmp->start_date = $variation->variation_start_date;
                $tmp->end_date = $variation->variation_end_date;
                $tmp->price_per_night = $variation->variation_price_per_night;
		$tmp->price_per_week = $variation->variation_price_per_week;
		$tmp->price_per_month = $variation->variation_price_per_month;
                $tmp->seasonal_minimum_nights = $variation->seasonal_minimum_nights;
                $data->calendar_price_exception[] = $tmp;
            }
        }

        $vrcalandar_unable_booking = $wpdb->prefix.'vrcalandar_unable_booking';
        
        $sql_select = "SELECT * FROM {$vrcalandar_unable_booking} WHERE booking_calendar_id = '".$calendar_id."' order by `unable_calandar_id` ASC";
        $unable_booking = $wpdb->get_results($sql_select, ARRAY_A);
        
        foreach ($unable_booking as $key=>$calenderUnable)
        {
            $data->calender_unable['from_date'][] = $calenderUnable['booking_date_from'];
            $data->calender_unable['to_date'][] = $calenderUnable['booking_date_to'];
        }

        $email_template_table = $wpdb->prefix.'email_template';
        $sql = "select * from {$email_template_table} where calendar_id='{$calendar_id}'";
        $template_variations = $wpdb->get_results($sql);
          if(count($template_variations) > 0)
          {
              foreach ($template_variations as $template)
              {
                  $data->email_template[$template->calendar_id][$template->template_kay][$template->template_slug] = $template->template_text;             
              }
          }
          
        return $data;
    }

    function getEmptyCalendar() {
        $calendar = new stdClass();
        $calendar->calendar_id = '';
        $calendar->calendar_name = '';
        $link_obj = new stdClass();
        $link_obj->name='';
        $link_obj->url='';
        $calendar->calendar_links = array(
            $link_obj
        );
        $calendar->calendar_layout_options = array (
            'columns' => __('3', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
            'rows' => __('4', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
            'size' => __('small', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
            'default_bg_color' => __('#FFFFFF', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
            'default_font_color' => __('#000000', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
            'calendar_border_color' => __('#CCCCCC', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
            'week_header_bg_color' => __('#F1F0F0', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
            'week_header_font_color' => __('#000000', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
            'available_bg_color' => __('#DDFFCC', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
            'available_font_color' => __('#000000', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
            'unavailable_bg_color' => __('#FFC0BD', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
            'unavailable_font_color' => __('#000000', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
        );
        $calendar->calendar_enable_booking = __('no', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $calendar->calendar_price_per_night = __('0', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $calendar->calendar_cfee_per_stay = __('0', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $calendar->calendar_tax_per_stay = __('0', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $calendar->calendar_tax_type = __('flat', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $calendar->calendar_payment_method = __('none', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $calendar->calendar_alert_double_booking = __('no', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $calendar->calendar_requires_admin_approval = __('no', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $calendar->calendar_price_exception = array();
        $calendar->calendar_booking_url ='';		
        $calendar->calendar_listing_image ='';
        $calendar->calendar_listing_address ='';
        $calendar->calendar_summary ='';
        $calendar->calendar_offer_weekly = __('no', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $calendar->calendar_offer_monthly = __('no', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $calendar->calendar_price_weekly = __('0', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $calendar->calendar_price_monthly = __('0', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $calendar->calendar_max_guest_no = __('1', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $calendar->calendar_extracharge_after_guest_no = __('0', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $calendar->calendar_extracharge_after_limited_guests = __('0', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $calendar->calendar_extra_fees = __('0', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
		$calendar->calendar_custom_field_name = __('', VRCALENDAR_PLUGIN_TEXT_DOMAIN);;
        
        return $calendar;
    }

    function synchronizeCalendar ($calendar_id) {
        $VRCalendarBooking = VRCalendarBooking::getInstance();
        $cal = $this->getCalendar($calendar_id);
        $calData =  json_decode(json_encode($cal), true);
        $calData['calendar_is_synchronizing'] = 'yes';
        $this->saveCalendar( $calData );
        $calLinks = $cal->calendar_links;

        /* First remove all bookings except local bookings */
        $VRCalendarBooking->removeBookingsExceptLocal($cal->calendar_id);
        foreach($calLinks as $calLink) {
            $ical   = new VRCICal($calLink->url);
            $events = $ical->events();
            if(is_array($events) && !empty($events)){
				foreach($events as $event) {
                    if (stripos($event['SUMMARY'], 'TENTATIVE') === false) {
                        $booking_data = array(
                            'booking_calendar_id' => $cal->calendar_id,
                            'booking_source' => $calLink->url,
                            'booking_date_from' => date('Y-m-d H:i:s', $ical->iCalDateToUnixTimestamp($event['DTSTART'])),
                            'booking_date_to' => date('Y-m-d H:i:s', $ical->iCalDateToUnixTimestamp($event['DTEND'])),
                            'booking_guests' => '',
                            'booking_user_fname' => '',
                            'booking_user_lname' => '',
                            'booking_user_email' => '',
                            'booking_summary' => $event['SUMMARY'],
                            'booking_status' => 'confirmed',
                            'booking_payment_status' => 'confirmed',
                            'booking_admin_approved' => 'yes',
                            'booking_payment_data' => '',
                            'booking_sub_price' => array(),
                            'booking_total_price' => 0,
                            'booking_created_on' => date('Y-m-d H:i:s'),
                            'booking_modified_on' => date('Y-m-d H:i:s'),
                        );
						if ($cal->calendar_alert_double_booking == 'yes') {
                            /* Check for double booking on this */
                            $VRCalendarBooking->checkBookingConflicts($booking_data);
                        }
                        $VRCalendarBooking->saveBooking($booking_data);
                    }
				}
			}
        }
        $calData['calendar_is_synchronizing'] = 'no';
        $calData['calendar_last_synchronized'] = date('Y-m-d H:i:s');
        $this->saveCalendar( $calData );
    }

    function getAvailablePriceVariations($cal_id, $check_in_date, $check_out_date) {
        global $wpdb;
        /* Check if a price variation is available in b/w these dates */
        $sql = "select * from {$this->price_variation_table_name} where calendar_id={$cal_id} and(
        date(variation_start_date) BETWEEN date('{$check_in_date}') and date('{$check_out_date}') OR
        date(variation_end_date) BETWEEN date('{$check_in_date}') and date('{$check_out_date}') OR
        date('{$check_in_date}') BETWEEN date(variation_start_date) and date(variation_end_date) )";

        $price_variations = $wpdb->get_results($sql);
        return $price_variations;
    }    
    function calculateNights($check_in_date, $check_out_date) {
		
        return ceil(abs(strtotime($check_out_date) - strtotime($check_in_date)) / 86400);
    }

    function getSingleNightCost($cal_data, $date) {
        $out_date = date( 'Y-m-d', strtotime("+1 day", strtotime($date)) );
        $variations = $this->getAvailablePriceVariations($cal_data->calendar_id, $date, $out_date);
                
        //$booking_price = $this->getPricePerNight($cal_data, $date, $out_date, $variations);
        $booking_price = $this->getMainCalendarPricePerNight($cal_data, $date, $out_date, $variations);
        
        return $booking_price;
    }

    function getMonthsWeeksDays($tdays, $value=array()){		
        if($tdays > 28){
			    if($tdays > 30){
				  $value["months"][] = floor($tdays/31);
				  $tdays = ($tdays%31);
				}elseif($tdays == 30){
				  $value["months"][] = floor($tdays/30);
				  $tdays = ($tdays%30);
				}elseif($tdays == 29){
				  $value["months"][] = floor($tdays/29);
				  $tdays = ($tdays%29);
				}
				return $this->getMonthsWeeksDays($tdays, $value);
		}elseif($tdays > 5){               
				  $value["weeks"] = floor($tdays/6);
				  $tdays = ($tdays%6);
				return $this->getMonthsWeeksDays($tdays,$value);
		}else{
            $value["days"]= $tdays;
		}
		return $value;
	}
    function getMonthsDays($tdays, $value=array()){		
        if($tdays > 28){
			    if($tdays > 30){
				  $value["months"][] = floor($tdays/31);
				  $tdays = ($tdays%31);
				}elseif($tdays == 30){
				  $value["months"][] = floor($tdays/30);
				  $tdays = ($tdays%30);
				}elseif($tdays == 29){
				  $value["months"][] = floor($tdays/29);
				  $tdays = ($tdays%29);
				}
				return $this->getMonthsDays($tdays, $value);
		}else{
            $value["days"]= $tdays;
		}
		return $value;
	}
    function getWeeksDays($tdays, $value=array()){		
        if($tdays > 5){               
				  $value["weeks"] = floor($tdays/6);
				  $tdays = ($tdays%6);
				return $this->getWeeksDays($tdays,$value);
		}else{
            $value["days"]= $tdays;
		}
		return $value;
	}
    function getPriceData($cal_data, $check_in_date, $check_out_date, $price_variations) {
        
        
        /* We have some variations */
        $priceByDates = 0;
        $dates = array();
        $dates_seasonal = array();
        $seasonal_data = array();
        
        if($cal_data->pro_one_day_book == 'yes')
        {
            for($date = $check_in_date; $date<=$check_out_date; $date=date( 'Y-m-d', strtotime("+1 day", strtotime($date)) ) ) 
            {
                $dates[$date] = $cal_data->calendar_price_per_night;
            }
        }else
        {
            for($date = $check_in_date; $date<$check_out_date; $date=date( 'Y-m-d', strtotime("+1 day", strtotime($date)) ) ) 
            {
                $dates[$date] = $cal_data->calendar_price_per_night;
            }
        }
                            
        /* Now update this array to get price from a variation */	
		$dates_seasonal =array();
		$seasonal_data = array();
        foreach($dates as $date=>$price) {
            //$price = $cal_data->calendar_price_per_night;
            foreach($price_variations as $variation) 
                {
                            
                if( strtotime($variation->variation_start_date) <= strtotime($date) &&  strtotime($variation->variation_end_date) >= strtotime($date) ) 
                    {
                        $price = $variation->variation_price_per_night;
                        $dates_seasonal[$date] = $price;
                        $seasonal_data[$variation->variation_id]['dates'][]=$date;
                        $seasonal_data[$variation->variation_id]['ppn']=$price;
                        $seasonal_data[$variation->variation_id]['ppw']=$variation->variation_price_per_week;
                        $seasonal_data[$variation->variation_id]['ppm']=$variation->variation_price_per_month;
                        break;
                    }
                }
            $dates[$date] = $price;
            $priceByDates = $priceByDates + $price;
        }
        
       
		$total_booking_days= count($dates);
                $seasonal_booking_days= count($dates_seasonal);
                
		$normal_booking_days= $total_booking_days - $seasonal_booking_days;
                
                $arr=array('total_booking_days'=>$total_booking_days,
			       'normal_booking_days'=>$normal_booking_days,
			       'seasonal_booking_days'=>$seasonal_booking_days,			       
			       'seasonal_data'=>$seasonal_data,
                               'priceByDates' => $priceByDates

		);
                
                $arr = array_merge($arr,$dates);
                
        return $arr;
    }
   
    function getMainCalendarPricePerNight($cal_data, $check_in_date, $check_out_date, $price_variations) {
        if(count($price_variations)<=0)
            return $cal_data->calendar_price_per_night;
        

        /* We have some variations */
        $dates = array();
        
        for($date = $check_in_date; $date<$check_out_date; $date=date( 'Y-m-d', strtotime("+1 day", strtotime($date)) ) ) {
                $dates[$date] = $cal_data->calendar_price_per_night;
        }
        
        
        
        /* Now update this array to get price from a variation */		
        foreach($dates as $date=>$price) {
            
            //$price = $cal_data->calendar_price_per_night;			
            foreach($price_variations as $variation) {
                if( strtotime($variation->variation_start_date) <= strtotime($date) &&  strtotime($variation->variation_end_date) >= strtotime($date) ) {
                    $price = $variation->variation_price_per_night;
                    break;
                }
            }
               $dates[$date] = $price;
        }
        
        return number_format((float)(array_sum($dates)/count($dates)), 2, '.', '');
    }
    function getPricePerNightSeasonal($cal_data, $check_in_date, $check_out_date, $price_variations) {
      if(count($price_variations)<=0)
            return $cal_data->calendar_price_per_night;
        

        /* We have some variations */
        $dates = array();
        
        for($date = $check_in_date; $date<=$check_out_date; $date=date( 'Y-m-d', strtotime("+1 day", strtotime($date)) ) ) {
                $dates[$date] = $cal_data->calendar_price_per_night;
        }
        
        
        
        /* Now update this array to get price from a variation */		
        foreach($dates as $date=>$price) {
            
            //$price = $cal_data->calendar_price_per_night;			
            foreach($price_variations as $variation) {
                if( strtotime($variation->variation_start_date) <= strtotime($date) &&  strtotime($variation->variation_end_date) >= strtotime($date) ) {
                    $price = $variation->variation_price_per_night;
                    break;
                }
            }
               $dates[$date] = $price;
        }
                
        return number_format((float)array_sum($dates), 2, '.', '');
   }
   function getPricePerNight($cal_data, $check_in_date, $check_out_date, $price_variations) {
       
       
        if(count($price_variations)<=0)
            return $cal_data->calendar_price_per_night;
        

        /* We have some variations */
        $dates = array();
        
        for($date = $check_in_date; $date<=$check_out_date; $date=date( 'Y-m-d', strtotime("+1 day", strtotime($date)) ) ) {
                $dates[$date] = $cal_data->calendar_price_per_night;
        }
        
        
        
        /* Now update this array to get price from a variation */		
        foreach($dates as $date=>$price) {
            
            //$price = $cal_data->calendar_price_per_night;			
            foreach($price_variations as $variation) {
                if( strtotime($variation->variation_start_date) <= strtotime($date) &&  strtotime($variation->variation_end_date) >= strtotime($date) ) {
                    $price = $variation->variation_price_per_night;
                    break;
                }
            }
               $dates[$date] = $price;
        }
        
        return number_format((float)(array_sum($dates)/count($dates)), 2, '.', '');
        
    }
	function getPricePerNightCustom($cal_data, $check_in_date, $check_out_date, $price_variations) {
       
       
        if(count($price_variations)<=0)
            return $cal_data->calendar_price_per_night;
        

        /* We have some variations */
        $dates = array();
        
        for($date = $check_in_date; $date<=$check_out_date; $date=date( 'Y-m-d', strtotime("+1 day", strtotime($date)) ) ) {
                $dates[$date] = $cal_data->calendar_price_per_night;
        }
        
        
        
        /* Now update this array to get price from a variation */		
        foreach($dates as $date=>$price) {
            
            //$price = $cal_data->calendar_price_per_night;			
            //foreach($price_variations as $variation) {
               // if( strtotime($variation->variation_start_date) <= strtotime($date) &&  strtotime($variation->variation_end_date) >= strtotime($date) ) {
                    //$price = $variation->variation_price_per_night;
                    //break;
                //}
           // }
               $dates[$date] = $price;
        }
        
        return number_format((float)(array_sum($dates)/count($dates)), 2, '.', '');
        
    }
    function getBaseBookingPrice($cal_data, $check_in_date, $check_out_date, $price_variations) {
        
        $booking_days = $this->calculateNights($check_in_date, $check_out_date);
        $price_per_day = $this->getPricePerNight($cal_data, $check_in_date, $check_out_date, $price_variations);
        
        return $booking_days*$price_per_day;

    }

    function getBookingPriceold($cal_id, $check_in_date, $check_out_date) {
        
        global $wp_db;
        $cal_data = $this->getCalendar($cal_id);

        $price_variations = $this->getAvailablePriceVariations($cal_id, $check_in_date, $check_out_date);

        $booking_days = $this->calculateNights($check_in_date, $check_out_date);

        $price_per_night = $this->getPricePerNight($cal_data, $check_in_date, $check_out_date, $price_variations);

        //$base_booking_price = $this->getBaseBookingPrice($cal_data, $check_in_date, $check_out_date, $price_variations);
        $base_booking_price = $booking_days*$price_per_night;

        $cleaning_fee = $cal_data->calendar_cfee_per_stay;
        $booking_price_without_taxes = $base_booking_price+$cleaning_fee;

        $tax = $cal_data->calendar_tax_per_stay;
        $tax_type = $cal_data->calendar_tax_type;
        $tax_amt = $tax;

        if($tax_type == 'percentage')
            $tax_amt = ($base_booking_price*$tax)/100;

        
        $booking_price_with_taxes =  $booking_price_without_taxes+$tax_amt;
        
        
        return array(
            'booking_days'=>$booking_days,
            'price_per_night'=>$price_per_night,
            'base_booking_price'=>$base_booking_price,
            'cleaning_fee'=>$cleaning_fee,
            'booking_price_without_taxes'=>$booking_price_without_taxes,
            'tax'=>$tax,
            'tax_type'=>$tax_type,
            'tax_amt'=>$tax_amt,
            'booking_price_with_taxes'=>$booking_price_with_taxes,
            /*'total_reservation_amount'=>*/
        );

    }

	function getWeekendBookingPrice($cal_id, $day){
		global $wp_db;
		$cal_data = $this->getCalendar($cal_id);
		$dayname = '';
		switch($day){
			case 'Friday':
				if($cal_data->friday_night_discount != ''){
					$dayname = $cal_data->friday_night_discount;
				}
				return $dayname;
			break;
			case 'Saturday':
				if($cal_data->saturday_night_discount != ''){
					$dayname = $cal_data->saturday_night_discount;
				}
				return $dayname;
			break;
			case 'Sunday':
				if($cal_data->sunday_night_discount != ''){
					$dayname = $cal_data->sunday_night_discount;
				}
				return $dayname;
			break;
		}

	}
	function getBookingPrice($cal_id, $check_in_date, $check_out_date, $total_guest_no = 1) {
        
        global $wp_db;
        $cal_data = $this->getCalendar($cal_id);
		
		//echo '<pre>';
		//print_r($cal_data);
		$from_date = $check_in_date;
		$to_date = $check_out_date;
		$from_date = new DateTime($from_date);
		$to_date = new DateTime($to_date);
		

        $pro_one_day_book = $cal_data->pro_one_day_book;
        $show_booking_from_one_page = $cal_data->show_booking_from_one_page;
        	
        $price_variations = $this->getAvailablePriceVariations($cal_id, $check_in_date, $check_out_date);
                
        $booking_days = $this->calculateNights($check_in_date, $check_out_date);    
        //custom code

         /*updated code 14-02-2016*/
        if($cal_data->calendar_offer_weekly == 'yes' && $cal_data->calendar_offer_monthly == 'yes'){
			$cprice_per_night = $this->getPricePerNight($cal_data, $check_in_date, $check_out_date, $price_variations);
		}else{
			$cprice_per_night = $this->getPricePerNightCustom($cal_data, $check_in_date, $check_out_date, $price_variations);

		}
        $PriceData = $this->getPriceData($cal_data, $check_in_date, $check_out_date, $price_variations);
                
        $base_booking_price = $this->getBaseBookingPrice($cal_data, $check_in_date, $check_out_date, $price_variations);

		

		/*=====================================*/
             
		
		$check_start_date = strtotime($check_in_date);
		$check_end_date = strtotime($check_out_date);		
			
			/*============================*/
			$seasonalarray = array();
			$default_array = array();
			$seasonalarrayraw = array();
			$default_price = $cal_data->calendar_price_per_night;
			$check_start_date = strtotime($check_in_date);
			$check_end_date = strtotime($check_out_date);
			$countseasonal = '';
			$seasonaltotalWeekEndPrice = 0;
			$countdefault = '';
			$defaulttotalWeekEndPrice = 0;
			$seasoalcount = 0;
			$seasonalarrayrawday =array();
			$seasonalarrayrawweek =array();
			$seasonalarrayrawmonth =array();
			if($cal_data->calendar_price_exception){
				foreach($cal_data->calendar_price_exception as $forseasonal){
					$forseasonal_start_date = strtotime($forseasonal->start_date);
					$forseasonal_end_date = strtotime($forseasonal->end_date);
					$forseasonal_price_per_night = $forseasonal->price_per_night;
					$forseasonal_price_per_week = $forseasonal->price_per_week;
					$forseasonal_price_per_month = $forseasonal->price_per_month;
					$seasonal_from_date = new DateTime($forseasonal->start_date);
					$seasonal_to_date = new DateTime($forseasonal->end_date);
					$seasonalarrayrawday[$seasoalcount]= $forseasonal_price_per_night;
					$seasonalarrayrawweek[$seasoalcount]= $forseasonal_price_per_week;
					$seasonalarrayrawmonth[$seasoalcount]= $forseasonal_price_per_month;
					for ($date = $seasonal_from_date; $date <= $seasonal_to_date; $date->modify('+1 day')) {
						$dateformate = $date->format('Y-m-d');
						$countseasonal++;
						$seasonaltotalWeekEndPrice += $forseasonal_price_per_night;
						$seasonalarrayraw[$dateformate] = $forseasonal_price_per_night;
						$seasonalarray[$seasoalcount][$dateformate] = array(
							'booking' => 'yes',	
							'amount' => $forseasonal_price_per_night,
							'default' => $default_price	
						);
						
					}
					$seasoalcount ++;
					
				}			
			}
			$monthly_price= $cal_data->calendar_price_monthly;
			$weekly_price = $cal_data->calendar_price_weekly;
		   
			$isValidMW = (($cal_data->calendar_offer_monthly == 'yes') && ($cal_data->calendar_offer_weekly == 'yes'))?true:false;
			$isValidM  = (($cal_data->calendar_offer_monthly == 'yes') && ($cal_data->calendar_offer_weekly != 'yes'))?true:false;
			$isValidW  = (($cal_data->calendar_offer_monthly != 'yes') && ($cal_data->calendar_offer_weekly == 'yes'))?true:false;


			$totalcountseasonal = count($seasonalarray);
			$nightsarray= array();
			$nightsarray['Friday']= 0;
			$nightsarray['Saturday']= 0;
			$nightsarray['Sunday']= 0;
            $nightsarray['Normal']= 0;
			$nightsarray['Seasonaltotal']= 0;
			$nightsarray['Seasonal']= array();
			$prices= array();	
			$eachdayarray =array();
			
			if($pro_one_day_book == 'yes'){
				for ($date = $from_date; $date <= $to_date; $date->modify('+1 day')) {	
					$daysname = $date->format('l');
					$dateformate1 = $date->format('Y-m-d');
					if(!isset($seasonalarrayraw[$dateformate1])){
					$eachdayarray[$dateformate1] = $daysname;}
					$newprice = $default_price;				
					if(isset($seasonalarrayraw[$dateformate1])){
						$newprice  = $seasonalarrayraw[$dateformate1];
						$nightsarray['Seasonaltotal']= $nightsarray['Seasonaltotal'] + 1;
						for($i = 0; $i < $totalcountseasonal;$i++){
							if(isset($seasonalarray[$i][$dateformate1])){
								$nightsarray['Seasonal'][$i][]= $seasonalarray[$i][$dateformate1]['amount'];
							}
						}
					}else{
						if($isValidMW || $isValidM || $isValidW){
							$nightsarray['Normal'] = $nightsarray['Normal'] + 1;
						}else{
						if(($daysname == 'Friday' || $daysname == 'Saturday' || $daysname == 'Sunday') && ($cal_data->weekend_pricing == 'yes')){
							if($daysname == 'Friday'){
							   $nightsarray['Friday'] = $nightsarray['Friday'] + 1;
							}
							if($daysname == 'Saturday'){
							   $nightsarray['Saturday'] = $nightsarray['Saturday'] + 1;
							}
							if($daysname == 'Sunday'){
							   $nightsarray['Sunday'] = $nightsarray['Sunday'] + 1;
							}
							$newprice  = $this->getWeekendBookingPrice($cal_id, $daysname);
						}else{
							  $nightsarray['Normal'] = $nightsarray['Normal'] + 1;
						}
						}
					}
					 
					$prices['Friday'] = number_format($nightsarray['Friday'] * $cal_data->friday_night_discount, 2, '.', '');
					$prices['Saturday'] = number_format($nightsarray['Saturday'] * $cal_data->saturday_night_discount, 2, '.', '');
					$prices['Sunday'] = number_format($nightsarray['Sunday'] * $cal_data->sunday_night_discount, 2, '.', '');
					$prices['Normal'] = $default_price;
					
					
					$defaulttotalWeekEndPrice += $default_price;
					$isweekendenabled = ($cal_data->weekend_pricing == 'yes')?'yes':'no';
					$ismvofferenabled ="no";
					if($isValidMW){
						$ismvofferenabled ="mw";
					}
					if($isValidM){
						$ismvofferenabled ="m";
					}
					if($isValidW){
						$ismvofferenabled ="w";
					}
					$default_array= array(
						'isweekendenabled' => $isweekendenabled,
						'ismvofferenabled' => $ismvofferenabled,
						'nightsper' =>   $nightsarray,
						'default' => $default_price	,
						'fridaynights' => number_format((float)$cal_data->friday_night_discount, 2, '.', ''),
						'saturdaynights' => number_format((float)$cal_data->saturday_night_discount, 2, '.', ''),
						'sundaynights' => number_format((float)$cal_data->sunday_night_discount, 2, '.', ''),
						'prices' => $prices
					);
					
				}  
            }else{
                for ($date = $from_date; $date < $to_date; $date->modify('+1 day')) {	
					$daysname = $date->format('l');
					$dateformate1 = $date->format('Y-m-d');
						if(!isset($seasonalarrayraw[$dateformate1])){
					$eachdayarray[$dateformate1] = $daysname;}
					$newprice = $default_price;				
					if(isset($seasonalarrayraw[$dateformate1])){
						$newprice  = $seasonalarrayraw[$dateformate1];
						$nightsarray['Seasonaltotal']= $nightsarray['Seasonaltotal'] + 1;
						for($i = 0; $i < $totalcountseasonal;$i++){
							if(isset($seasonalarray[$i][$dateformate1])){
								$nightsarray['Seasonal'][$i][]= $seasonalarray[$i][$dateformate1]['amount'];
							}
						}
					}else{
						if($isValidMW || $isValidM || $isValidW){
							$nightsarray['Normal'] = $nightsarray['Normal'] + 1;
						}else{
						if(($daysname == 'Friday' || $daysname == 'Saturday' || $daysname == 'Sunday') && ($cal_data->weekend_pricing == 'yes')){
							if($daysname == 'Friday'){
							   $nightsarray['Friday'] = $nightsarray['Friday'] + 1;
							}
							if($daysname == 'Saturday'){
							   $nightsarray['Saturday'] = $nightsarray['Saturday'] + 1;
							}
							if($daysname == 'Sunday'){
							   $nightsarray['Sunday'] = $nightsarray['Sunday'] + 1;
							}
							$newprice  = $this->getWeekendBookingPrice($cal_id, $daysname);
						}else{
							  $nightsarray['Normal'] = $nightsarray['Normal'] + 1;
						}
						}
					}
					 
					$prices['Friday'] = number_format($nightsarray['Friday'] * $cal_data->friday_night_discount, 2, '.', '');
					$prices['Saturday'] = number_format($nightsarray['Saturday'] * $cal_data->saturday_night_discount, 2, '.', '');
					$prices['Sunday'] = number_format($nightsarray['Sunday'] * $cal_data->sunday_night_discount, 2, '.', '');
					$prices['Normal'] = $default_price;
					
					
					$defaulttotalWeekEndPrice += $default_price;
					$isweekendenabled = ($cal_data->weekend_pricing == 'yes')?'yes':'no';
					$ismvofferenabled ="no";
					if($isValidMW){
						$ismvofferenabled ="mw";
					}
					if($isValidM){
						$ismvofferenabled ="m";
					}
					if($isValidW){
						$ismvofferenabled ="w";
					}
					$default_array= array(
						'isweekendenabled' => $isweekendenabled,
						'ismvofferenabled' => $ismvofferenabled,
						'nightsper' =>   $nightsarray,
						'default' => $default_price	,
						'fridaynights' => @number_format($cal_data->friday_night_discount, 2, '.', ''),
						'saturdaynights' => @number_format($cal_data->saturday_night_discount, 2, '.', ''),
						'sundaynights' => @number_format($cal_data->sunday_night_discount, 2, '.', ''),
						'prices' => $prices
					);
					
				}

			}

            if(!empty($default_array['nightsper']['Seasonal'])){
				foreach($default_array['nightsper']['Seasonal'] as $key=>$value){
					$saecount = count($value);
					$default_array['prices']['Seasonal'][$key.'_'.$saecount] = $value[0];
				}
						
				foreach($default_array['nightsper']['Seasonal'] as $key=>$value){
					$saecount = count($value);
					if($seasonalarrayrawweek[$key] > 0 && $seasonalarrayrawmonth[$key] > 0){
						$m_w_d = $this->getMonthsWeeksDays($saecount,  $value=array());
					}else if($seasonalarrayrawweek[$key] < 1 && $seasonalarrayrawmonth[$key] > 0) {
						$m_w_d = $this->getMonthsDays($saecount,  $value=array());
					}else if($seasonalarrayrawweek[$key] > 0 && $seasonalarrayrawmonth[$key] < 1) {
						$m_w_d = $this->getWeeksDays($saecount,  $value=array());
					}else{
					    $m_w_d['days']= $saecount;
					}
					
						$mcountval =(isset($m_w_d['months']))?$m_w_d['months']:0;
						$wcount =(isset($m_w_d['weeks']))?$m_w_d['weeks']:0;
						$dcount =(isset($m_w_d['days']))?$m_w_d['days']:0;

						if(is_array($mcountval)){
							$ccnt = 0;
						   foreach($mcountval as $v){
							   $ccnt = $ccnt + $v;
						   }
						   $mcount = $ccnt;
						}else{
							$mcount =  $mcountval;
						}
					
					$default_array['nightsper']['Seasonalarray'][$key]['months'] = $mcount;
					$default_array['nightsper']['Seasonalarray'][$key]['weeks'] = $wcount;
					$default_array['nightsper']['Seasonalarray'][$key]['days'] = $dcount;
					
					$default_array['prices']['Seasonalarray'][$key]['months'] = $seasonalarrayrawmonth[$key];
					$default_array['prices']['Seasonalarray'][$key]['weeks'] = $seasonalarrayrawweek[$key];

					if($seasonalarrayrawday[$key] < 1){
                        $seasonalarrayrawday[$key] = $default_price;
					}
					$default_array['prices']['Seasonalarray'][$key]['days'] = $seasonalarrayrawday[$key];

				}
				$totseasonprices = 0;
				$totseasons = count($default_array['nightsper']['Seasonalarray']);			
				if($totseasons > 0){
					for($i= 0;$i<$totseasons;$i++){
						$totseasonprices = $totseasonprices + ($default_array['nightsper']['Seasonalarray'][$i]['months'] * $default_array['prices']['Seasonalarray'][$i]['months']) +  ($default_array['nightsper']['Seasonalarray'][$i]['weeks'] * $default_array['prices']['Seasonalarray'][$i]['weeks']) + ($default_array['nightsper']['Seasonalarray'][$i]['days'] * $default_array['prices']['Seasonalarray'][$i]['days']);
					}
				}
			}
			

            if($default_array['nightsper']['Normal'] > 0){
				$totndays = $default_array['nightsper']['Normal'];
				if($isValidMW || $isValidM || $isValidW){
					$normal_m_w_d = $this->getWeeksDays($totndays,  $value=array());

					if($isValidMW)
                    $normal_m_w_d = $this->getMonthsWeeksDays($totndays,  $value=array());
					
                    if($isValidM)
                    $normal_m_w_d = $this->getMonthsDays($totndays,  $value=array());


					$normalmcountval =(isset($normal_m_w_d['months']))?$normal_m_w_d['months']:0;
					$normalwcount =(isset($normal_m_w_d['weeks']))?$normal_m_w_d['weeks']:0;
					$normaldcount =(isset($normal_m_w_d['days']))?$normal_m_w_d['days']:0;

					if(is_array($normalmcountval)){
						$ccnt = 0;
                       foreach($normalmcountval as $v){
                           $ccnt = $ccnt + $v;
					   }
                       $normalmcount = $ccnt;
					}else{
                     $normalmcount =  $normalmcountval;
					}
					
					$default_array['nightsper']['Normalarray']['months'] = $normalmcount;
					$default_array['nightsper']['Normalarray']['weeks'] = $normalwcount;
					$default_array['nightsper']['Normalarray']['days'] = $normaldcount;
					
					$default_array['prices']['Normalarray']['months'] = $monthly_price;
					$default_array['prices']['Normalarray']['weeks'] = $weekly_price;
					$default_array['prices']['Normalarray']['days'] = $default_array['prices']['Normal'];

					$fri = 0;
					$sat = 0;
					$sun = 0;
					$nor = 0;
                    
					if($normaldcount > 0){
						$neweachdayarray = array_slice($eachdayarray, -$normaldcount);
						foreach($neweachdayarray as $k=>$v){
							if(($v == 'Friday' || $v == 'Saturday' || $v == 'Sunday') && ($cal_data->weekend_pricing == 'yes')){

								if($v == 'Friday'){
								   $fri =$fri + 1;
								}elseif($v == 'Saturday'){
								   $sat =$sat + 1;
								}elseif($v == 'Sunday'){
								   $sun =$sun + 1;
								}else{
								   $nor =$nor + 1;
								}
							}else{
								$nor =$nor + 1;
							}
						}
				    }
                    $default_array['nightsper']['Normalarray']['fri'] = $fri;
					$default_array['nightsper']['Normalarray']['sat'] = $sat;					
					$default_array['nightsper']['Normalarray']['sun'] = $sun;
					$default_array['nightsper']['Normalarray']['nor'] = $nor;
				}
				 
			}

			//echo '<pre>';
			//print_r($default_array);
			$texthtml ='';
			$textnormalhtml='';
			$textseasonalhtml = '';

	        $pricehtml='';
			$pricenormalhtml='';
            $priceseasonalhtml = '';

            $wholecoreprice = 0; 
			$totalseasonalprice = 0;
			$totalnormalprice = 0;

			$totalseasonalcount = count($default_array['nightsper']['Seasonalarray']);
				for($i= 0;$i<$totalcountseasonal;$i++){
					$totalseasonalprice = $totalseasonalprice +( $default_array['nightsper']['Seasonalarray'][$i]['months'] * $default_array['prices']['Seasonalarray'][$i]['months']) + ( $default_array['nightsper']['Seasonalarray'][$i]['weeks'] * $default_array['prices']['Seasonalarray'][$i]['weeks']) + ( $default_array['nightsper']['Seasonalarray'][$i]['days'] * $default_array['prices']['Seasonalarray'][$i]['days']);

					$j = $i+1;
					if($default_array['nightsper']['Seasonalarray'][$i]['days'] > 0 || $default_array['nightsper']['Seasonalarray'][$i]['weeks'] > 0 || $default_array['nightsper']['Seasonalarray'][$i]['months'] > 0){
						$textseasonalhtml .= "<br/>Season $j:<br/>";
					}
					if($default_array['nightsper']['Seasonalarray'][$i]['months'] > 0){
						$textseasonalhtml .= renderCurrency().$default_array['prices']['Seasonalarray'][$i]['months']. " X " .$default_array['nightsper']['Seasonalarray'][$i]['months']." Months <br/>";
					}
					if($default_array['nightsper']['Seasonalarray'][$i]['weeks'] > 0){
						$textseasonalhtml .=renderCurrency().$default_array['prices']['Seasonalarray'][$i]['weeks']. " X " .$default_array['nightsper']['Seasonalarray'][$i]['weeks']. " Weeks<br/>";
					}
					if($default_array['nightsper']['Seasonalarray'][$i]['days'] > 0){
						$textseasonalhtml .=renderCurrency().$default_array['prices']['Seasonalarray'][$i]['days']. " X " .$default_array['nightsper']['Seasonalarray'][$i]['days']." Nights<br/>";
					}
					if($default_array['nightsper']['Seasonalarray'][$i]['days'] > 0 || $default_array['nightsper']['Seasonalarray'][$i]['weeks'] > 0 || $default_array['nightsper']['Seasonalarray'][$i]['months'] > 0 ){
						$priceseasonalhtml .= "<br/> &nbsp;<br/> ";
					}
				   
					if($default_array['nightsper']['Seasonalarray'][$i]['months'] > 0){
						$priceseasonalhtml .= renderCurrency().number_format(( $default_array['nightsper']['Seasonalarray'][$i]['months'] * $default_array['prices']['Seasonalarray'][$i]['months']), 2, '.', '')."  <br/>";
					}
					if($default_array['nightsper']['Seasonalarray'][$i]['weeks'] > 0){
						$priceseasonalhtml .= renderCurrency().number_format(( $default_array['nightsper']['Seasonalarray'][$i]['weeks'] * $default_array['prices']['Seasonalarray'][$i]['weeks']), 2, '.', ''). " <br/>";
					}
					if($default_array['nightsper']['Seasonalarray'][$i]['days'] > 0){
						$priceseasonalhtml .= renderCurrency().number_format(( $default_array['nightsper']['Seasonalarray'][$i]['days'] * $default_array['prices']['Seasonalarray'][$i]['days']), 2, '.', '')."<br/>";
					}
				   
				}

			if($default_array['isweekendenabled'] == 'no' && $default_array['ismvofferenabled'] == 'no'){	
				$totalnormalprice =  $default_array['nightsper']['Normal'] * $default_array['prices']['Normal'];
				
				if($default_array['nightsper']['Normal'] > 0){
					$textnormalhtml =    renderCurrency().$default_array['prices']['Normal']." X ". $default_array['nightsper']['Normal']." Nights";

					$pricenormalhtml =   renderCurrency().number_format($default_array['nightsper']['Normal'] * $default_array['prices']['Normal'], 2, '.', '');
				}
			}


			if($default_array['isweekendenabled'] == 'no' && ($default_array['ismvofferenabled'] == 'm' || $default_array['ismvofferenabled'] == 'mw' || $default_array['ismvofferenabled'] == 'w' )){              		    
                
				$totalnormalprice = ($default_array['nightsper']['Normalarray']['months'] * $default_array['prices']['Normalarray']['months']) + ($default_array['nightsper']['Normalarray']['weeks'] * $default_array['prices']['Normalarray']['weeks']) + ($default_array['nightsper']['Normalarray']['days'] * $default_array['prices']['Normalarray']['days']);
				
				if($default_array['nightsper']['Normalarray']['months'] > 0){
					$textnormalhtml .= renderCurrency().$default_array['prices']['Normalarray']['months'] ." X ".$default_array['nightsper']['Normalarray']['months']." Months<br/>";
				}
				if($default_array['nightsper']['Normalarray']['weeks'] > 0){
					$textnormalhtml .= renderCurrency().$default_array['prices']['Normalarray']['weeks'] ." X ".$default_array['nightsper']['Normalarray']['weeks']." Weeks<br/>";
				}
				if($default_array['nightsper']['Normalarray']['days'] > 0){
					$textnormalhtml .= renderCurrency().$default_array['prices']['Normalarray']['days'] ." X ".$default_array['nightsper']['Normalarray']['days']." Nights";
				}
				if($default_array['nightsper']['Normalarray']['months'] > 0){
					$pricenormalhtml .=  renderCurrency().number_format(($default_array['nightsper']['Normalarray']['months'] * $default_array['prices']['Normalarray']['months']), 2, '.', '') ."<br/>";
				}
				if($default_array['nightsper']['Normalarray']['weeks'] > 0){
					$pricenormalhtml .=  renderCurrency().number_format(($default_array['nightsper']['Normalarray']['weeks'] * $default_array['prices']['Normalarray']['weeks']), 2, '.', '') ."<br/>";
				}
				if($default_array['nightsper']['Normalarray']['days'] > 0){
					$pricenormalhtml .=  renderCurrency().number_format(($default_array['nightsper']['Normalarray']['days'] * $default_array['prices']['Normalarray']['days']), 2, '.', '');
				}
			}


			if($default_array['isweekendenabled'] == 'yes' && $default_array['ismvofferenabled'] =='no' ){
                $totalnormalprice = ($default_array['nightsper']['Normal'] * $default_array['prices']['Normal']) +  $default_array['prices']['Friday'] + $default_array['prices']['Saturday'] + $default_array['prices']['Sunday'] ; 
				
				if($default_array['nightsper']['Normal'] > 0){
					$textnormalhtml .= renderCurrency().$default_array['default']." X ". $default_array['nightsper']['Normal']." Nights <br/>";
				}
				if($default_array['nightsper']['Friday'] > 0){
					$textnormalhtml .= renderCurrency().$default_array['fridaynights']." X ". $default_array['nightsper']['Friday']." Friday Nights <br/>";
				}
				if($default_array['nightsper']['Saturday'] > 0){
					$textnormalhtml .= renderCurrency().$default_array['saturdaynights']." X ". $default_array['nightsper']['Saturday']." Saturday Nights <br/>";
				}
				if($default_array['nightsper']['Sunday'] > 0){
					$textnormalhtml .= renderCurrency().$default_array['sundaynights']." X ". $default_array['nightsper']['Sunday']." Sunday Nights";
				}
				if($default_array['nightsper']['Normal'] > 0){
					$pricenormalhtml .=  renderCurrency().number_format(($default_array['nightsper']['Normal'] * $default_array['prices']['Normal']), 2, '.', '') ."<br/>";
				}
				if($default_array['nightsper']['Friday'] > 0){
					$pricenormalhtml .=  renderCurrency().number_format($default_array['prices']['Friday'], 2, '.', '') ."<br/>";
				}
				if($default_array['nightsper']['Saturday'] > 0){
					$pricenormalhtml .=  renderCurrency().number_format($default_array['prices']['Saturday'], 2, '.', '') ."<br/>";
				}
				if($default_array['nightsper']['Sunday'] > 0){
					$pricenormalhtml .=  renderCurrency(). number_format($default_array['prices']['Sunday'], 2, '.', '') ; 
				}
			}


            if($default_array['isweekendenabled'] == 'yes' && ($default_array['ismvofferenabled'] == 'm' || $default_array['ismvofferenabled'] == 'mw' || $default_array['ismvofferenabled'] == 'w' )){
                $totalnormalprice =  ($default_array['nightsper']['Normalarray']['months'] * $default_array['prices']['Normalarray']['months']) + ($default_array['nightsper']['Normalarray']['weeks'] * $default_array['prices']['Normalarray']['weeks']) +  ($default_array['nightsper']['Normalarray']['fri'] * $default_array['fridaynights'])  +  ($default_array['nightsper']['Normalarray']['sat'] * $default_array['saturdaynights']) +  ($default_array['nightsper']['Normalarray']['sun'] * $default_array['sundaynights'])  +  ($default_array['nightsper']['Normalarray']['nor'] * $default_array['default']);
				
				if($default_array['nightsper']['Normalarray']['months'] > 0){
					$textnormalhtml .=  renderCurrency().$default_array['prices']['Normalarray']['months']." X ".$default_array['nightsper']['Normalarray']['months']." Months<br/>";
				}
				if($default_array['nightsper']['Normalarray']['weeks'] > 0){
					$textnormalhtml .=  renderCurrency().$default_array['prices']['Normalarray']['weeks']." X ".$default_array['nightsper']['Normalarray']['weeks']." Weeks<br/>";
				}
				if($default_array['nightsper']['Normalarray']['nor'] > 0){
					$textnormalhtml .=  renderCurrency().$default_array['default'] ." X ".$default_array['nightsper']['Normalarray']['nor']." Nights<br/>";
				}
				if($default_array['nightsper']['Normalarray']['fri'] > 0){
					$textnormalhtml .=  renderCurrency().$default_array['fridaynights'] ." X ".$default_array['nightsper']['Normalarray']['fri']." Friday Nights<br/>";
				}
				if($default_array['nightsper']['Normalarray']['sat'] > 0){
					$textnormalhtml .=  renderCurrency().$default_array['saturdaynights'] ." X ".$default_array['nightsper']['Normalarray']['sat']." Saturday Nights<br/>";
				}
				if($default_array['nightsper']['Normalarray']['sun'] > 0){
					$textnormalhtml .=  renderCurrency().$default_array['sundaynights'] ." X ".$default_array['nightsper']['Normalarray']['sun']." Sunday Nights";
				}
				if($default_array['nightsper']['Normalarray']['months'] > 0){
					$pricenormalhtml .= renderCurrency().number_format(($default_array['nightsper']['Normalarray']['months'] * $default_array['prices']['Normalarray']['months']), 2, '.', '') ."<br/>";
				}
				if($default_array['nightsper']['Normalarray']['weeks'] > 0){
					$pricenormalhtml .= renderCurrency(). number_format(($default_array['nightsper']['Normalarray']['weeks'] * $default_array['prices']['Normalarray']['weeks']), 2, '.', '')  ."<br/>";
				}
				if($default_array['nightsper']['Normalarray']['nor'] > 0){
					$pricenormalhtml .= renderCurrency(). number_format(($default_array['nightsper']['Normalarray']['nor'] * $default_array['default']), 2, '.', '')  	."<br/>";
				}
				if($default_array['nightsper']['Normalarray']['fri'] > 0){
					$pricenormalhtml .= renderCurrency(). number_format(($default_array['nightsper']['Normalarray']['fri'] * $default_array['fridaynights']), 2, '.', '')  ."<br/>";
				}
				if($default_array['nightsper']['Normalarray']['sat'] > 0){
					$pricenormalhtml .= renderCurrency(). number_format(($default_array['nightsper']['Normalarray']['sat'] * $default_array['saturdaynights']), 2, '.', '') ."<br/>";
				}
				if($default_array['nightsper']['Normalarray']['sun'] > 0){
					$pricenormalhtml .= renderCurrency(). number_format(($default_array['nightsper']['Normalarray']['sun'] * $default_array['sundaynights']), 2, '.', '') ;
				}
			}
            
			$wholecoreprice =  $totalseasonalprice + $totalnormalprice;
            $texthtml =	$textnormalhtml ."<br/>". $textseasonalhtml;
			if($pro_one_day_book == 'yes'){
              $texthtml = str_replace('Nights','Days', $texthtml);
			}
			$texthtml = preg_replace('{(<br[^>]*>\s*)+}', "<br/>", $texthtml);
			$pricehtml =	$pricenormalhtml    ."<br/>".     $priceseasonalhtml ;
			$pricehtml = preg_replace('{(<br[^>]*>\s*)+}', "<br/>", $pricehtml);
         if($pro_one_day_book == 'yes')
            {
                $datetime1 = new DateTime($check_in_date);
                $datetime2 = new DateTime($check_out_date);
                $difference = $datetime1->diff($datetime2);

                if($check_in_date == $check_out_date)
                {
                    $booking_days = 1;
                }else{
                    $booking_days = $difference->d+1;
                }
                $base_booking_price = $booking_days * $cprice_per_night;
            }
        
        //echo $base_booking_price;
        
		$totaladditionalcharge = 0;
        $max_guest_no     = $cal_data->calendar_max_guest_no;
		$guest_limit      = $cal_data->calendar_extracharge_after_guest_no;
		$additionalcharge = $cal_data->calendar_extracharge_after_limited_guests;
        if($total_guest_no > $guest_limit)
            {
                $increasedguests = $total_guest_no - $guest_limit;
                $totaladditionalcharge = $increasedguests * $additionalcharge;
            }
        
        $price_per_night=$cal_data->calendar_price_per_night;
        
        
        
         $base_booking_price = $wholecoreprice;
        if($cal_data->hourly_booking == 'yes' && $pro_one_day_book == 'yes'){
			$base_booking_price = $cprice_per_night;
		}
       
		$totaladditionalcharge = $booking_days * $totaladditionalcharge;
        $extra_fees =   $cal_data->calendar_extra_fees;
        $cleaning_fee = $cal_data->calendar_cfee_per_stay;
        $booking_price_without_taxes = $base_booking_price+$cleaning_fee+$extra_fees+$totaladditionalcharge;
    
        $tax = $cal_data->calendar_tax_per_stay;
        $tax_type = $cal_data->calendar_tax_type;
        $tax_amt = $tax;

        if($tax_type == 'percentage')
            $tax_amt = ($base_booking_price*$tax)/100;

			$booking_price_with_taxes =  $booking_price_without_taxes+$tax_amt;    
            $reminingPriceAmount = $booking_price_with_taxes;
            $rest_of_days = '';
            $deposit_enable = 0;
            $rest_of_pay_date = '';
            if($cal_data->deposit_enable == 'yes')
            {
                $rest_of_days = $cal_data->rest_of_day;
                
                if(strtotime(date( 'Y-m-d', strtotime("+{$rest_of_days} day", strtotime(date('Y-m-d'))) )) <= strtotime($check_out_date))
                 {
                     
                    $reminingPriceAmount = $booking_price_with_taxes - (($booking_price_with_taxes * $cal_data->deposit_percentage) / 100);
                    $booking_price_discounted = ($booking_price_with_taxes * $cal_data->deposit_percentage) / 100;
                    $booking_price_with_taxes = $booking_price_without_taxes+$tax_amt;
                    $deposit_enable = 1;
                     
                     //05:03:2016 Aaron changed date format from d-m-Y to Y-m-d to match other format
                      $rest_of_pay_date = date( 'Y-m-d', strtotime("-{$rest_of_days} day", strtotime($check_in_date)) );
                 }else{
					$reminingPriceAmount = $booking_price_without_taxes+$tax_amt;
                    $booking_price_with_taxes = $booking_price_without_taxes+$tax_amt;
					$booking_price_discounted =0;
				 }
                /*$newbase_booking_price = ($booking_price_with_taxes * $cal_data->deposit_percentage) / 100;
                
                $reminingPriceAmount = $booking_price_with_taxes - $newbase_booking_price;
                $booking_price_with_taxes = $newbase_booking_price;*/
                
                //$reminingPriceAmount = $base_booking_price+$cleaning_fee+$extra_fees+$totaladditionalcharge;
            }


             $minimum_number_of_night = $cal_data->minimum_number_of_night;
             $number_of_night = $cal_data->number_of_night;
             $nightCounter = $cal_data->number_of_night;
             $nightlimit = 0;
             global $wpdb;
             $table_name = $wpdb->prefix.'vrcalandar_bookings';
             $vrcalandar_price_variation = $wpdb->prefix."vrcalandar_price_variation";
             $wp_vrcalandar_bookings = $wpdb->prefix.'vrcalandar_bookings';
             $check_seasonal = $wpdb->get_results("SELECT * FROM {$vrcalandar_price_variation} WHERE `calendar_id` = '{$cal_id}' AND DATE(`variation_start_date`) <= '{$check_in_date}' AND DATE(`variation_end_date`) > '{$check_in_date}'", OBJECT );
                          
             if(count($check_seasonal) > 0)
             {
                    $nightCounter =  $check_seasonal[0]->seasonal_minimum_nights;
                    $number_of_night= $check_seasonal[0]->seasonal_minimum_nights; 
                    
                    $date1 = new DateTime($check_in_date);
                    $date2 = new DateTime($check_out_date);
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
                    $nightTesttDate = $check_out_date;
                    $results = $wpdb->get_results( "SELECT * FROM {$wp_vrcalandar_bookings} WHERE `booking_calendar_id` = '{$cal_id}' AND `booking_status` = 'confirmed' AND (DATE(booking_date_from) <= '{$nightTesttDate}' AND DATE(booking_date_to) >= '{$nightTesttDate}')", OBJECT );
                    
                    
                    if(count($results) == 0)
                    {
                        $checkout = $nightTesttDate;
                        //$check_in_date
                        //$check_out_date
                        $date1 = new DateTime($check_in_date);
                        $date2 = new DateTime($check_out_date);
                        $nightlimit_new = $date2->diff($date1)->format("%a");
                        if($nightlimit_new >= $number_of_night)
                        {
                            $nightlimit = 0;
                        }else{
                            $nightlimit = 1;
                        }
                        
                    }
                    else {
                        $nightlimit = 1;
                    }
                }elseif($pro_one_day_book != 'yes')
                {
                    $nightCounter = 0;
                    $nightlimit = 0;
                    if($check_in_date == $check_out_date)
                    {
                        $nightCounter = 1;
                        $nightlimit = 1;
                    }
                    
                }
                if($cal_data->hourly_booking == 'yes' && $pro_one_day_book == 'yes'){
					$texthtml = $cprice_per_night.' X 1 days';
					$pricehtml = renderCurrency().$cprice_per_night;
				}
           
				$texthtml = trim($texthtml, '<br/>' );
				$pricehtml = trim($pricehtml, '<br/>' );
			return array(
				'booking_days'=>$booking_days,
				'price_per_night'=>$cprice_per_night,			
				'base_booking_price'=>number_format($base_booking_price, 2, '.', ''),
				'cleaning_fee'=>number_format((float)$cleaning_fee, 2, '.', ''),
				'extra_fees'=>number_format((float)$extra_fees, 2, '.', ''),
				'booking_price_without_taxes'=>number_format((float)$booking_price_without_taxes, 2, '.', ''),
				'tax'=>$tax,
				'tax_type'=>$tax_type,
				'tax_amt'=>number_format((float)$tax_amt, 2, '.', ''),
				'booking_price_with_taxes'=>number_format($booking_price_with_taxes, 2, '.', ''),
				'booking_price_dsicounted'=>number_format($booking_price_discounted, 2, '.', ''),
				'totaladditionalcharge' =>number_format((float)$totaladditionalcharge, 2, '.', ''),
				'monthly_price'=>$monthly_price,
				'weekly_price'=>$weekly_price,				
				'remining_price_amount' => $reminingPriceAmount,
				'rest_of_days' => $rest_of_days,
				'nightlimit' => $nightlimit,
				'nightcounter'=>$nightCounter,
				'deposit_enable'=>$cal_data->deposit_enable,
				'deposit_enable_by_date'=>$deposit_enable,
				'total_reservation_amount'=>number_format($booking_price_with_taxes, 2, '.', ''),
				'rest_of_pay_date'=>$rest_of_pay_date,
				'render_currency'=>renderCurrency(),				
				'weekend_night_data' => $default_array,
				'number_of_nights' => $cal_data->number_of_night,
				'minimum_number_of_nights' => $cal_data->minimum_number_of_night,
				'texthtml'=>$texthtml,
				'pricehtml' => $pricehtml
			);
		
    }

	function getBookingPricejune1($cal_id, $check_in_date, $check_out_date, $total_guest_no = 1) {
        
        global $wp_db;
        $cal_data = $this->getCalendar($cal_id);
        
        $pro_one_day_book = $cal_data->pro_one_day_book;
        $show_booking_from_one_page = $cal_data->show_booking_from_one_page;
        	
        $price_variations = $this->getAvailablePriceVariations($cal_id, $check_in_date, $check_out_date);
                
        $booking_days = $this->calculateNights($check_in_date, $check_out_date);    
                
        //custom code
        
        
        global $wpdb;
        $vrcalandar_price_variation = $wpdb->prefix . 'vrcalandar_price_variation';
        $get_seasonal = $wpdb->get_row("SELECT * FROM `wp_vrcalandar_price_variation` WHERE `calendar_id` = '".$cal_id."' AND (DATE(variation_start_date) <= '".$check_in_date."' AND DATE(variation_end_date) >= '".$check_in_date."')");
        $priceOfseasonalE = 0;
        if(count($get_seasonal) > 0)
        {
            if(strtotime($check_out_date) > strtotime($get_seasonal->variation_end_date))
            {
             $totalPriceOfseasonal = $this->getPricePerNightSeasonal($cal_data, $check_in_date, $check_out_date, $price_variations);
             $cprice_per_night = $totalPriceOfseasonal / $booking_days;
             $priceOfseasonalE = 1;
            }else{
                $cprice_per_night = $this->getPricePerNight($cal_data, $check_in_date, $check_out_date, $price_variations);
            }
            
        }else
        {
            $cprice_per_night = $this->getPricePerNight($cal_data, $check_in_date, $check_out_date, $price_variations);
        }
        
                
        /*updated code 14-02-2016*/
        //$cprice_per_night = $this->getPricePerNight($cal_data, $check_in_date, $check_out_date, $price_variations);
        
        $PriceData = $this->getPriceData($cal_data, $check_in_date, $check_out_date, $price_variations);
                
        $base_booking_price = $this->getBaseBookingPrice($cal_data, $check_in_date, $check_out_date, $price_variations);
        
        
        
         if($pro_one_day_book == 'yes')
            {
                $datetime1 = new DateTime($check_in_date);
                $datetime2 = new DateTime($check_out_date);
                $difference = $datetime1->diff($datetime2);

                if($check_in_date == $check_out_date)
                {
                    $booking_days = 1;
                }else{
                    $booking_days = $difference->d+1;
                }
                $base_booking_price = $booking_days * $cprice_per_night;
            }
        
        //echo $base_booking_price;
        
		$totaladditionalcharge = 0;
                $max_guest_no     = $cal_data->calendar_max_guest_no;
		$guest_limit      = $cal_data->calendar_extracharge_after_guest_no;
		$additionalcharge = $cal_data->calendar_extracharge_after_limited_guests;
        if($total_guest_no > $guest_limit)
            {
                $increasedguests = $total_guest_no - $guest_limit;
                $totaladditionalcharge = $increasedguests * $additionalcharge;
            }
        
        $price_per_night=$cal_data->calendar_price_per_night;
        
        
        
        $monthly_price= $cal_data->calendar_price_monthly;
        $weekly_price = $cal_data->calendar_price_weekly;
        $booking_months= 0;
        $booking_weeks= 0;
        $booking_dys = 0;	
        //$base_booking_price = 0;
        $special_offer = __('none', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        
        /*echo "<pre>";
        print_r($cal_data);
        echo "</pre>";
        */
      
        
        
        $isValidMW = (($cal_data->calendar_offer_monthly == 'yes') && ($cal_data->calendar_offer_weekly == 'yes'))?true:false;
        $isValidM  = (($cal_data->calendar_offer_monthly == 'yes') && ($cal_data->calendar_offer_weekly != 'yes'))?true:false;
        $isValidW  = (($cal_data->calendar_offer_monthly != 'yes') && ($cal_data->calendar_offer_weekly == 'yes'))?true:false;
        
        
        if($isValidMW)
            {
            
                $special_offer = __('mw', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
                $base_booking_price_n = 0;
		$base_booking_price_s = 0;
		
                if(isset( $PriceData['normal_booking_days']) && $PriceData['normal_booking_days'] > 0)
                    {
                        $booking_months_n= 0;
                        $booking_weeks_n= 0;
			$booking_dys_n = 0;				
			$return = $this->getMonthsWeeksDays($PriceData['normal_booking_days'], $value=array());
                                                
                        if(isset($return['months']))
                            {
                                $mscnt  = count($return['months']) ;				
				for($i=0;$i<$mscnt;$i++)
                                {
                                    $booking_months_n= $booking_months_n + intval($return['months'][$i]);
				}
                            }
                            if(isset($return['weeks']))
                                {
                                    $booking_weeks_n= $booking_weeks_n + intval($return['weeks']);
				}
                            if(isset($return['days']))
                                {
                                    $booking_dys_n= $booking_dys_n + intval($return['days']);
				 }
                             /*if($pro_one_day_book == 'yes')
                                {
                                 $booking_dys_n = $booking_days;
                                }*/
                            $base_booking_price_n  = ($booking_months_n * $monthly_price) + ($booking_weeks_n * $weekly_price) + ($booking_dys_n * $price_per_night);
                            $booking_months= $booking_months + $booking_months_n;
                            $booking_weeks= $booking_weeks + $booking_weeks_n;
                            $booking_dys = $booking_dys + $booking_dys_n;
		   }	
        
         
        if(isset( $PriceData['seasonal_booking_days']) && $PriceData['seasonal_booking_days'] > 0)
            { 
            
                 foreach($PriceData['seasonal_data'] as $k=> $v)
                    {
                     
                        $booking_months_s= 0;
                        $booking_weeks_s= 0;
                        $booking_dys_s = 0;				
                        $totaldays = count($v['dates']);
                                                
                        if($totaldays > 0)
                          {
                            $return = $this->getMonthsWeeksDays($totaldays, $value=array());
                            
                            if(isset($return['months']))
                               {
                                 $mscnt  = count($return['months']) ;				
				
                                 for($i=0;$i<$mscnt;$i++)
                                    {
                                        $booking_months_s= $booking_months_s + intval($return['months'][$i]);
                                        $booking_months = $booking_months + $booking_months_s;
                                    }
                               }
                                   
                                if(isset($return['weeks']))
                                   {
                                        $booking_weeks_s= $booking_weeks_s + intval($return['weeks']);
                                        $booking_weeks = $booking_weeks + $booking_weeks_s;
                                   }
                                if(isset($return['days']))
                                    {
                                        $booking_dys_s= $booking_dys_s + intval($return['days']);
                                        $booking_dys = $booking_dys + $booking_dys_s;
                                    }
                                    
                                    //echo $booking_months_s ." -> ". $v['ppm']." -> ". $booking_weeks_s ." -> ". $v['ppw']." -> ".$booking_dys_s ." -> ". $v['ppn'];
                                    $monthPriceOfDate = ($booking_months_s * $v['ppm']);
                                    $weekPriceOfDate = ($booking_weeks_s * $v['ppw']);
                                    if(isset($return['weeks']) && $v['ppw'] == 0)
                                    {
                                        $weekPriceOfDate = ($return['weeks'] * 6) * $v['ppn'];
                                    }
                                    if(isset($return['months']) && $v['ppw'] == 0)
                                    {
                                            $datetime1 = new DateTime($check_in_date);
                                            $datetime2 = new DateTime($check_out_date);
                                            $difference = $datetime1->diff($datetime2);
                                            
                                            $monthPriceOfDate = ($return['months'] * ($difference->d+1)) * $v['ppn'];
                                    }
                                    $base_booking_price_ss[]  = $monthPriceOfDate + $weekPriceOfDate + ($booking_dys_s * $v['ppn']);
                                    //$base_booking_price_ss[]  = ($booking_months_s * $v['ppm']) + ($booking_weeks_s * $v['ppw']) + ($booking_dys_s * $v['ppn']);
                            }

			 }
                     $base_booking_price_s = array_sum($base_booking_price_ss);
                   }
                   
                   
                   $base_booking_price = intval($base_booking_price_n) + intval($base_booking_price_s);
                   /*if($pro_one_day_book == 'yes')
                   {
                        if(intval($base_booking_price_n) + intval($base_booking_price_s) > 0)
                        {
                           $base_booking_price = intval($base_booking_price_n) + intval($base_booking_price_s);
                           
                        }
                   }
                    else 
                    { 

                        $base_booking_price = intval($base_booking_price_n) + intval($base_booking_price_s); 
                    }*/
                          
		}elseif($isValidM){	  
			$special_offer = __('m', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
			$base_booking_price_n = 0;
		   $base_booking_price_s = 0;		   
			if(isset( $PriceData['normal_booking_days']) && $PriceData['normal_booking_days'] > 0){
			    $booking_months_n= 0;				
				$booking_dys_n = 0;				
			   $return = $this->getMonthsDays($PriceData['normal_booking_days'], $value=array());		  
				if(isset($return['months'])){
					$mscnt  = count($return['months']) ;				
					for($i=0;$i<$mscnt;$i++){
						$booking_months_n= $booking_months_n + intval($return['months'][$i]);
					}
				}				
				 if(isset($return['days'])){
				$booking_dys_n= $booking_dys_n + intval($return['days']);
				 }				
				$base_booking_price_n  = ($booking_months_n * $monthly_price) + ($booking_dys_n * $price_per_night);
				$booking_months= $booking_months + $booking_months_n;
				$booking_dys = $booking_dys + $booking_dys_n;
		   }		  
		   if(isset( $PriceData['seasonal_booking_days']) && $PriceData['seasonal_booking_days'] > 0){				 
			 $base_booking_price_ss = array();
             foreach($PriceData['seasonal_data'] as $k=> $v){
				$booking_months_s= 0;				
				$booking_dys_s = 0;				
				$totaldays = count($v['dates']);				
				 if($totaldays > 0){
                    $return = $this->getMonthsDays($totaldays, $value=array());
					if(isset($return['months'])){
						$mscnt  = count($return['months']) ;				
						for($i=0;$i<$mscnt;$i++){
							$booking_months_s= $booking_months_s + intval($return['months'][$i]);
							$booking_months = $booking_months + $booking_months_s;
						}
					}					
					 if(isset($return['days'])){
					$booking_dys_s= $booking_dys_s + intval($return['days']);
					$booking_dys = $booking_dys + $booking_dys_s;
					 }					
					$base_booking_price_ss[]  = ($booking_months_s * $v['ppm']) + ($booking_dys_s * $v['ppn']);                    
				 }

			 }
             $base_booking_price_s = array_sum($base_booking_price_ss);			 
		   }
           $base_booking_price = intval($base_booking_price_n) + intval($base_booking_price_s);	
           
           
		}elseif($isValidW){  
			$special_offer = 'w';
			$base_booking_price_n = 0;
		   $base_booking_price_s = 0;		   
			if(isset( $PriceData['normal_booking_days']) && $PriceData['normal_booking_days'] > 0){			    
				$booking_weeks_n= 0;
				$booking_dys_n = 0;				
			   $return = $this->getWeeksDays($PriceData['normal_booking_days'], $value=array());
				if(isset($return['weeks'])){
						$booking_weeks_n= $booking_weeks_n + intval($return['weeks']);
				}
				 if(isset($return['days'])){
				$booking_dys_n= $booking_dys_n + intval($return['days']);
				 }				
				$base_booking_price_n  =  ($booking_weeks_n * $weekly_price) + ($booking_dys_n * $price_per_night);
				$booking_weeks= $booking_weeks + $booking_weeks_n;
				$booking_dys = $booking_dys + $booking_dys_n;
		   }		  
		   if(isset( $PriceData['seasonal_booking_days']) && $PriceData['seasonal_booking_days'] > 0){				 
			 $base_booking_price_ss = array();
             foreach($PriceData['seasonal_data'] as $k=> $v){
				$booking_weeks_s= 0;
				$booking_dys_s = 0;				
				 $totaldays = count($v['dates']);
				 if($totaldays > 0){
                    $return = $this->getWeeksDays($totaldays, $value=array());
					if(isset($return['weeks'])){
							$booking_weeks_s= $booking_weeks_s + intval($return['weeks']);
							$booking_weeks = $booking_weeks + $booking_weeks_s;
					}
					 if(isset($return['days'])){
					$booking_dys_s= $booking_dys_s + intval($return['days']);
					$booking_dys = $booking_dys + $booking_dys_s;
					 }					
					$base_booking_price_ss[]  =  ($booking_weeks_s * $v['ppw']) + ($booking_dys_s * $v['ppn']);                    
				 }

			 }
             $base_booking_price_s = array_sum($base_booking_price_ss);
             
		   }
                    $base_booking_price = intval($base_booking_price_n) + intval($base_booking_price_s);
           
		}else{  			
                    $base_booking_price = $booking_days * $cprice_per_night;
                }
		$totaladditionalcharge = $booking_days * $totaladditionalcharge;
        $extra_fees =   $cal_data->calendar_extra_fees;
        $cleaning_fee = $cal_data->calendar_cfee_per_stay;
        $booking_price_without_taxes = $base_booking_price+$cleaning_fee+$extra_fees+$totaladditionalcharge;
    
        $tax = $cal_data->calendar_tax_per_stay;
        $tax_type = $cal_data->calendar_tax_type;
        $tax_amt = $tax;

        if($tax_type == 'percentage')
            $tax_amt = ($base_booking_price*$tax)/100;

        $booking_price_with_taxes =  $booking_price_without_taxes+$tax_amt;
        $specialpricearr = array();
		$special_offer_text ='';
			if($booking_months > 0)
				$specialpricearr[] = $booking_months.__(' Months', VRCALENDAR_PLUGIN_TEXT_DOMAIN) ;
			if($booking_weeks > 0)
				$specialpricearr[] = $booking_weeks.__(' Weeks', VRCALENDAR_PLUGIN_TEXT_DOMAIN) ;
		    if($booking_dys > 0)
				$specialpricearr[] = $booking_dys.__(' Nights ', VRCALENDAR_PLUGIN_TEXT_DOMAIN) ;
		    $specialprice =implode(' and ',$specialpricearr);
		    if($booking_months == 0 && $booking_weeks == 0){
		    }else{
				$special_offer_text =	 __("Special Price for : ", VRCALENDAR_PLUGIN_TEXT_DOMAIN).$specialprice;
		    }    
           

            $reminingPriceAmount = '';
            $rest_of_days = '';
            $deposit_enable = 0;
            $rest_of_pay_date = '';
            if($cal_data->deposit_enable == 'yes')
            {
                $rest_of_days = $cal_data->rest_of_day;
                
                if(strtotime(date( 'Y-m-d', strtotime("+{$rest_of_days} day", strtotime(date('Y-m-d'))) )) <= strtotime($check_out_date))
                 {
                     
                     $reminingPriceAmount = $booking_price_with_taxes - (($booking_price_with_taxes * $cal_data->deposit_percentage) / 100);
                     $booking_price_with_taxes = ($booking_price_with_taxes * $cal_data->deposit_percentage) / 100;
                     
                     $deposit_enable = 1;
                     
                     $rest_of_pay_date = date( 'd-m-Y', strtotime("-{$rest_of_days} day", strtotime($check_in_date)) );//date( 'd-m-Y', strtotime("+{$rest_of_days} day", strtotime(date('Y-m-d'))) );
                 }
                /*$newbase_booking_price = ($booking_price_with_taxes * $cal_data->deposit_percentage) / 100;
                
                $reminingPriceAmount = $booking_price_with_taxes - $newbase_booking_price;
                $booking_price_with_taxes = $newbase_booking_price;*/
                
                //$reminingPriceAmount = $base_booking_price+$cleaning_fee+$extra_fees+$totaladditionalcharge;
            }


             $minimum_number_of_night = $cal_data->minimum_number_of_night;
             $number_of_night = $cal_data->number_of_night;
             $nightCounter = $cal_data->number_of_night;
             $nightlimit = 0;
             global $wpdb;
             $table_name = $wpdb->prefix.'vrcalandar_bookings';
             $vrcalandar_price_variation = $wpdb->prefix."vrcalandar_price_variation";
             $wp_vrcalandar_bookings = $wpdb->prefix.'vrcalandar_bookings';
             $check_seasonal = $wpdb->get_results("SELECT * FROM {$vrcalandar_price_variation} WHERE `calendar_id` = '{$cal_id}' AND DATE(`variation_start_date`) <= '{$check_in_date}' AND DATE(`variation_end_date`) > '{$check_in_date}'", OBJECT );
                          
             if(count($check_seasonal) > 0)
             {
                    $nightCounter =  $check_seasonal[0]->seasonal_minimum_nights;
                    $number_of_night= $check_seasonal[0]->seasonal_minimum_nights; 
                    
                    $date1 = new DateTime($check_in_date);
                    $date2 = new DateTime($check_out_date);
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
                    $nightTesttDate = $check_out_date;
                    $results = $wpdb->get_results( "SELECT * FROM {$wp_vrcalandar_bookings} WHERE `booking_calendar_id` = '{$cal_id}' AND `booking_status` = 'confirmed' AND (DATE(booking_date_from) <= '{$nightTesttDate}' AND DATE(booking_date_to) >= '{$nightTesttDate}')", OBJECT );
                    
                    
                    if(count($results) == 0)
                    {
                        $checkout = $nightTesttDate;
                        //$check_in_date
                        //$check_out_date
                        $date1 = new DateTime($check_in_date);
                        $date2 = new DateTime($check_out_date);
                        $nightlimit_new = $date2->diff($date1)->format("%a");
                        if($nightlimit_new >= $number_of_night)
                        {
                            $nightlimit = 0;
                        }else{
                            $nightlimit = 1;
                        }
                        
                    }
                    else {
                        $nightlimit = 1;
                    }
                }elseif($pro_one_day_book != 'yes')
                {
                    $nightCounter = 0;
                    $nightlimit = 0;
                    if($check_in_date == $check_out_date)
                    {
                        $nightCounter = 1;
                        $nightlimit = 1;
                    }
                    
                }
                
        
        
       $total_reservation_amount = $reminingPriceAmount+$booking_price_with_taxes;
       
        return array(
            'booking_days'=>$booking_days,
            'price_per_night'=> number_format((float)$cprice_per_night, 2, '.', ''),
            'base_booking_price'=>number_format((float)$base_booking_price, 2, '.', ''),
            'cleaning_fee'=>number_format((float)$cleaning_fee, 2, '.', ''),
            'extra_fees'=>number_format((float)$extra_fees, 2, '.', ''),
            'booking_price_without_taxes'=>number_format((float)$booking_price_without_taxes, 2, '.', ''),
            'tax'=>$tax,
            'tax_type'=>$tax_type,
            'tax_amt'=>number_format((float)$tax_amt, 2, '.', ''),
            'booking_price_with_taxes'=>number_format((float)$booking_price_with_taxes, 2, '.', ''),
            'totaladditionalcharge' =>number_format((float)$totaladditionalcharge, 2, '.', ''),
            'monthly_price'=>$monthly_price,
            'weekly_price'=>$weekly_price,
            'booking_months'=>$booking_months,
            'booking_weeks'=>$booking_weeks,
            'booking_dys'=>$booking_dys,
            'special_offer' => $special_offer,
            'special_offer_text' =>$special_offer_text,
            'remining_price_amount' => $reminingPriceAmount,
            'rest_of_days' => $rest_of_days,
            'nightlimit' => $nightlimit,
            'nightcounter'=>$nightCounter,
            'deposit_enable_by_date'=>$deposit_enable,
            'total_reservation_amount'=>number_format((float)$total_reservation_amount, 2, '.', ''),
            'rest_of_pay_date'=>$rest_of_pay_date,
            'render_currency'=>renderCurrency()
        );

    }	

    function getDepositPaymentPayByLink() {
        
        $VRCalendarBooking = VRCalendarBooking::getInstance();
        $VRCalendarSettings = VRCalendarSettings::getInstance();
        $VRCTransactionalEmail = VRCTransactionalEmail::getInstance();
        
        global $wpdb; 
        $vrcalandar_bookings = $wpdb->prefix.'vrcalandar_bookings';
        
        $wp_vrcalandar = $wpdb->prefix.'vrcalandar';
         
        $sql_remainUserDetails = "SELECT * FROM {$vrcalandar_bookings}"; //WHERE `booking_payment_status` = 'confirmed' AND `booking_admin_approved` = 'yes'
        $remainUserDetails = $wpdb->get_results($sql_remainUserDetails);
        
        if(count($remainUserDetails) > 0)
        {
            foreach ($remainUserDetails as $keys => $remainUser)
            {
                $booking_calendar_id = $remainUser->booking_calendar_id;
                $booking_id = $remainUser->booking_id;
                
                if($wpdb->get_var("SELECT * FROM {$wp_vrcalandar} WHERE `calendar_id` = '{$booking_calendar_id}' AND `deposit_enable` = 'yes'") > 0)
                {
                    $booking_details = $VRCalendarBooking->getBookingByID($booking_id);
                    
                    //$booking_details->booking_date_from
                    if(count($booking_details->booking_sub_price) > 0)
                    {
                        if($booking_details->booking_sub_price['remining_price_amount'] > 0)
                        {
                            //$booking_id
                            $remining_price_amount = $booking_details->booking_sub_price['remining_price_amount'];
                            $rest_of_days = $booking_details->booking_sub_price['rest_of_days'];
         
                            $todayDate = date("Y-m-d");
                            
                            
                                
                            //if(date("Y-m-d", strtotime("+{$rest_of_days} day", strtotime($booking_details->booking_created_on))) == $todayDate)
                            if(date("Y-m-d", strtotime("-{$rest_of_days} day", strtotime($booking_details->booking_date_from))) == $todayDate)
                            {
                                $remining_price_amount = base64_encode($booking_details->booking_sub_price['remining_price_amount']) ; //;
                                
                                $booking_payment_link = add_query_arg(array('bid'=>$booking_id,'next'=>'deposit','p'=>$remining_price_amount), get_permalink($VRCalendarSettings->getSettings('payment_page')) );
                                
                                $email_data = array(
                                    'calendar_id'=>$booking_calendar_id,
                                    'booking_user_fname'=>$booking_details->booking_user_fname,
                                    'booking_user_lname'=>$booking_details->booking_user_lname,
                                    'booking_payment_link'=>$booking_payment_link,
                                );
                                //$VRCTransactionalEmail->sendBookingApprovedPayment($email_data, array($booking_details->booking_user_email));
                                $VRCTransactionalEmail->sendIfDepositPaymentEnable($email_data, array($booking_details->booking_user_email));
                            }
                        }
                        //rest_of_days
                    } 
                    
                }
            }
        }
                
    }
   
}