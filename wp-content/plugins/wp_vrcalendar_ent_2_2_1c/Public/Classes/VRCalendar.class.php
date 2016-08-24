<?php
class VRCalendar extends VRCSingleton {
    /**
     * Plugin version, used for cache-busting of style and script file references.
     *
     * @since   1.0.0
     *
     * @var     string
     */
    const VERSION = '1.0.0';


    protected function __construct(){
        // Load plugin text domain
        add_action( 'init', array( $this, 'loadPluginTextdomain' ) );
        add_action( 'init', array( $this, 'initShortcodes' ) );
        add_action('init', array($this, 'handleCommands'));

        // Load public-facing style sheet and JavaScript.
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueueStyles' ), 1000 );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueueScripts' ) );

        add_action( 'wp_ajax_get_updated_price', array($this, 'getUpdatedPrice') );
        add_action( 'wp_ajax_nopriv_get_updated_price', array($this, 'getUpdatedPrice') );

        add_action( 'wp_ajax_get_available_range_end', array($this, 'getAvailableRangeEnd') );
        add_action( 'wp_ajax_nopriv_get_available_range_end', array($this, 'getAvailableRangeEnd') );

        add_action( 'wp_ajax_vrc_paypal_return', array($this, 'paypalReturn') );
        add_action( 'wp_ajax_nopriv_vrc_paypal_return', array($this, 'paypalReturn') );

        add_action( 'wp_ajax_vrc_paypal_cancel', array($this, 'paypalCancel') );
        add_action( 'wp_ajax_nopriv_vrc_paypal_cancel', array($this, 'paypalCancel') );

        add_action( 'wp_ajax_vrc_paypal_notify', array($this, 'paypalNotify') );
        add_action( 'wp_ajax_nopriv_vrc_paypal_notify', array($this, 'paypalNotify') );
		if(phpversion() >= '5.3.0'){
        add_action( 'wp_ajax_vrc_stripe_payment', array($this, 'stripePayment') );
        add_action( 'wp_ajax_nopriv_vrc_stripe_payment', array($this, 'stripePayment') );
		}
        add_action( 'vrc_cal_sync_hook', array($this, 'syncAllCalendars') );
        add_action( 'vrc_cal_payment_email_hook', array($this, 'sendPaymentEmailNot') );

        add_action( 'wp_ajax_get_updated_checkoudate', array($this, 'getUpdatedCheckoudate') );
        add_action( 'wp_ajax_nopriv_get_updated_checkoudate', array($this, 'getUpdatedCheckoudate') );
        
        add_action( 'wp_ajax_get_updated_checkindate', array($this, 'getUpdatedCheckinDate') );
        add_action( 'wp_ajax_nopriv_get_updated_checkindate', array($this, 'getUpdatedCheckinDate') );

        add_action( 'wp_ajax_getavailablerangebgcolorset', array($this, 'showgetavailablerangebgcolorset') );
        add_action( 'wp_ajax_nopriv_getavailablerangebgcolorset', array($this, 'showgetavailablerangebgcolorset') );


    }

    function getUpdatedCheckinDate()
    {
        $cal_id = $_POST['cal_id'];
        $inDate = $_POST['inDate'];
        
        $timeArray = array('0' => __('12:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '1' => __('1:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '2' => __('2:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '3' => __('3:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '4' => __('4:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '5' => __('5:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '6' => __('6:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '7' => __('7:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '8' => __('8:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '9' => __('9:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '10' => __('10:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '11' => __('11:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '12' => __('12:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '13' => __('1:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '14' => __('2:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN),'15' => __('3:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '16' => __('4:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '17' => __('5:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '18' => __('6:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '19' => __('7:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '20' => __('8:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '21' => __('9:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '22' => __('10:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '23' => __('11:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN));
        $timeKeysget = array('0' => __('00:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '1' => __('01:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '2' => __('02:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '3' => __('03:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '4' => __('04:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '5' => __('05:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '6' => __('06:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '7' => __('07:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '8' => __('08:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '9' => __('09:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '10' => __('10:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '11' => __('11:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '12' => __('12:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '13' => __('13:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '14' => __('14:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN),'15' => __('15:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '16' => __('16:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '17' => __('17:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '18' => __('18:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '19' => __('19:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '20' => __('20:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '21' => __('21:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '22' => __('22:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '23' => __('23:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN));
        
        $VRCalendarEntity = VRCalendarEntity::getInstance();
        $newcal_data = $VRCalendarEntity->getCalendar( $cal_id );
        
        $pro_one_day_book = $newcal_data->pro_one_day_book;

        $hourly_booking = $newcal_data->hourly_booking;

        $timeArray = array('0' => __('12:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '1' => __('1:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '2' => __('2:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '3' => __('3:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '4' => __('4:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '5' => __('5:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '6' => __('6:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '7' => __('7:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '8' => __('8:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '9' => __('9:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '10' => __('10:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '11' => __('11:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '12' => __('12:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '13' => __('1:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '14' => __('2:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN),'15' => __('3:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '16' => __('4:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '17' => __('5:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '18' => __('6:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '19' => __('7:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '20' => __('8:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '21' => __('9:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '22' => __('10:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '23' => __('11:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN));
        $timeKeysget = array('0' => __('00:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '1' => __('01:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '2' => __('02:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '3' => __('03:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '4' => __('04:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '5' => __('05:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '6' => __('06:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '7' => __('07:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '8' => __('08:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '9' => __('09:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '10' => __('10:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '11' => __('11:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '12' => __('12:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '13' => __('13:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '14' => __('14:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN),'15' => __('15:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '16' => __('16:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '17' => __('17:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '18' => __('18:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '19' => __('19:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '20' => __('20:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '21' => __('21:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '22' => __('22:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '23' => __('23:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN));
        $arrayinTimes = array();
        $disabledtextbox = '';
        if($hourly_booking == 'yes')
        {
            $hoursbookingdiifference = $newcal_data->hoursbookingdiifference;
            $disabledtextbox = 'disabled="disabled"';
            global $wpdb;
            $table_name = $wpdb->prefix . 'vrcalandar_bookings';
            $results = $wpdb->get_results( "SELECT booking_date_from, booking_date_to FROM $table_name WHERE booking_admin_approved = 'yes' AND booking_calendar_id = '".$cal_id."' AND DATE(booking_date_to) = '".$inDate."'", OBJECT );

            if(count($results) > 0)
            {
                
                foreach($results as $gettimes)
                {
                    foreach($timeKeysget as $key => $timeget)
                    {
                        $dates1 = date("H:i:s", strtotime('+'.$hoursbookingdiifference.' hours', strtotime($gettimes->booking_date_to)));
                        $dates2 = date("H:i:s", strtotime('-'.$hoursbookingdiifference.' hours', strtotime($gettimes->booking_date_from)));

                        if(date("Y-m-d", strtotime($gettimes->booking_date_from)) == $_GET['bdate'] && date("H:i:s", strtotime($gettimes->booking_date_from)) == '00:00:00')
                        {
                            $dates2 = date("H:i:s", strtotime($gettimes->booking_date_from));
                            if(strtotime($dates2) > strtotime($timeget)){
                                $arrayinTimes[$key] = $timeget;
                            }else if(strtotime($dates1) <= strtotime($timeget)){
                                $arrayinTimes[$key] = $timeget;
                            }
                        }
                        elseif(date("Y-m-d", strtotime('-'.$hoursbookingdiifference.' hours', strtotime($gettimes->booking_date_from))) == $_GET['bdate']){
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
            }
         else {
                $arrayinTimes = $timeKeysget;
            }
        }
        echo json_encode($arrayinTimes);
        die();
    }

    function syncAllCalendars() {
        $VRCalendarAdmin = VRCalendarAdmin::getInstance();
        $VRCalendarAdmin->syncAllCalendars();
    }
    function sendPaymentEmailNot() {
        $VRCalendarEntity = VRCalendarEntity::getInstance();
        $VRCalendarEntity->getDepositPaymentPayByLink();
    }

    function stripePayment() {
        $VRCalendarSettings = VRCalendarSettings::getInstance();
        $VRCalendarBooking = VRCalendarBooking::getInstance();
        $booking_id = $_REQUEST['bid'];
        $booking_data = $VRCalendarBooking->getBookingByID( $booking_id );
        $VRCalendarSettings = VRCalendarSettings::getInstance();
	$sel_currency = $VRCalendarSettings->getSettings('attr_currency');
        $card = array(
            'name'  => $_POST['strip_name_on_card'],
            'number' => $_POST['strip_card_number'],
            'cvv' => $_POST['strip_cvv'],
            'exp_month' => $_POST['strip_expiration_month'],
            'exp_year' => $_POST['strip_expiration_year'],
            'address_line1' => $_POST['strip_address_line_1'],
            'address_line2' => $_POST['strip_address_line_2'],
            'address_city' => $_POST['strip_address_city'],
            'address_state' => $_POST['strip_address_state'],
            'address_zip' => $_POST['strip_address_zip'],
            'address_country' => $_POST['strip_address_country'],
        );
        
        $priceOfValue = 0.00;
        $VRCalendarEntity = VRCalendarEntity::getInstance();
        $newcal_data = $VRCalendarEntity->getCalendar( $booking_id );
        
        
         if($_POST['next'] == 'deposit')
            {
                $priceOfValue = $booking_data->booking_sub_price['remining_price_amount'];
            }  else {
               $priceOfValue = $booking_data->booking_total_price;
            }
        
        
        
        $charge_data = array(
            'card' => $card,
            'amount' => floatval($priceOfValue)*100,
            'currency' => $sel_currency
        );
        
        
        
        try {
            \Stripe\Stripe::setApiKey($VRCalendarSettings->getSettings('stripe_api_key'));
            $charge = \Stripe\Charge::create($charge_data);
            $charge_obj = json_decode(json_encode($charge));
            if($charge->paid) {
                /* Update this booking */
                $booking_data =  json_decode(json_encode($booking_data), true);
                $booking_data['booking_status'] = 'confirmed';
                $booking_data['booking_payment_status'] = 'confirmed';
                $booking_data['booking_payment_data'] = array(
                    'txn_id'=>$charge->id,
                    'raw_data'=> base64_encode(json_encode($charge_obj)),
                    'payment_method'=>'stripe'
                );
                $data = array(
                    'bid'=>$booking_id
                );
                $VRCalendarBooking->saveBooking( $booking_data );
                echo json_encode(array('result'=>'success', 'txn_id'=>$charge->id, 'bid'=>$booking_id));
            }
        }
        catch(Exception $e) {
            echo json_encode(array('result'=>'error', 'msg'=>$e->getMessage()));
        }
        exit;
    }

    function paypalReturn() {
        $VRCalendarSettings = VRCalendarSettings::getInstance();
        $VRCalendarBooking = VRCalendarBooking::getInstance();

        $booking_id = $_REQUEST['bid'];
        $txn_id = $_POST['txn_id'];
        $payment_status = $_POST['payment_status'];

        $status = 'pending';
        if($payment_status == 'Pending' || $payment_status == 'Completed') {
            $status = 'confirmed';
        }


        $booking_data = $VRCalendarBooking->getBookingByID( $booking_id );
        $booking_data =  json_decode(json_encode($booking_data), true);
        $booking_data['booking_status'] = $status;
        $booking_data['booking_payment_status'] = $status;
        $booking_data['booking_payment_data'] = array(
            'txn_id'=>$_POST['txn_id'],
            'raw_data'=> base64_encode(json_encode($_POST)),
            'payment_method'=>'paypal'
        );
        $data = array(
            'bid'=>$booking_id
        );
        $VRCalendarBooking->saveBooking( $booking_data );
        /* Redirect to thank you page */
        wp_redirect( add_query_arg($data, get_permalink($VRCalendarSettings->getSettings('thank_you_page'))) );
        //echo 'Payment completed..';
        exit;
        //print_r($_REQUEST);die();
    }
    function paypalCancel() {
        $VRCalendarSettings = VRCalendarSettings::getInstance();
        $VRCalendarBooking = VRCalendarBooking::getInstance();
        /* Delete the booking */
        $booking_id = $_REQUEST['bid'];
        $VRCalendarBooking->deleteBooking($booking_id);

        wp_redirect( get_permalink($VRCalendarSettings->getSettings('payment_cancel_page')) );
        exit;
        //print_r($_REQUEST);die();
    }
    function paypalNotify() {
        //print_r($_REQUEST);die();
    }

    function handleCommands() {
        if(isset($_REQUEST['vrc_pcmd'])) {
            switch($_REQUEST['vrc_pcmd']) {
                case 'saveBooking':
                    $this->saveBooking();
                    break;
                case 'paypalPayment':
                    $this->paypalPayment();
                    break;
                case 'ical':
                    $this->exportICAL();
                    break;
            }
        }
    }

    function exportICAL() {
        $VRCalendarEntity = VRCalendarEntity::getInstance();
        $VRCalendarEntity->downloadCalendar( $_GET['cal_id'] );
        exit;
    }

    function paypalPayment() {
        $VRCalendarSettings = VRCalendarSettings::getInstance();
        $VRCalendarBooking = VRCalendarBooking::getInstance();
		$sel_currency = $VRCalendarSettings->getSettings('attr_currency');

        $booking_id = $_POST['bid'];
        $booking_data = $VRCalendarBooking->getBookingByID( $booking_id );
        
        
        $VRCalendarEntity = VRCalendarEntity::getInstance();
        $newcal_data = $VRCalendarEntity->getCalendar( $booking_data->booking_calendar_id );

        $paypal_vars = array();
        $paypal_vars['cmd'] = '_xclick';
        $paypal_vars['currency_code'] = $sel_currency;
        $paypal_vars['cbt'] = 'Click here to complete your order';

        $paypal_vars['business'] = $VRCalendarSettings->getSettings('paypal_email');
        $paypal_vars['item_name'] = 'Booking';
        $paypal_vars['item_number'] = $booking_id;
        
        //$paypal_vars['amount'] = $booking_data->booking_total_price;
        if($_GET['next'] == 'deposit')
        {
            $paypal_vars['amount'] = $booking_data->booking_sub_price['remining_price_amount'];
        }  else {
           $paypal_vars['amount'] = $booking_data->booking_total_price;
        }
      
        
        $paypal_vars['return'] = add_query_arg(array('bid'=>$booking_id, 'action'=>'vrc_paypal_return'), admin_url('admin-ajax.php'));
        $paypal_vars['cancel_return'] = add_query_arg(array('bid'=>$booking_id, 'action'=>'vrc_paypal_cancel'), admin_url('admin-ajax.php'));
        $paypal_vars['notify_url'] = add_query_arg(array('bid'=>$booking_id, 'action'=>'vrc_paypal_notify'), admin_url('admin-ajax.php'));

        $paypal_url = 'https://www.paypal.com/cgi-bin/webscr';

        if($VRCalendarSettings->getSettings('payment_mode') == 'sandbox')
            $paypal_url = 'https://www.sandbox.paypal.com/cgi-bin/webscr';

        $paypal_url = $paypal_url.'?'.http_build_query($paypal_vars);
        header('location:'.$paypal_url);
        exit;
    }

    function saveBooking() {		
        
       // $_SESSION['payfirst'] = 'one';
        
                
       $booking_checkin_date = $_POST['booking_checkin_date'];
        $booking_checkout_date = $_POST['booking_checkout_date'];
        
                
        $VRCalendarSettings = VRCalendarSettings::getInstance();
        $VRCalendarBooking = VRCalendarBooking::getInstance();
        $VRCalendarEntity = VRCalendarEntity::getInstance();

        $cal_data = $VRCalendarEntity->getCalendar( $_POST['cal_id'] );
      
        $hourly_booking = $cal_data->hourly_booking;
        
        $timeArray = array('0' => __('12:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '1' => __('1:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '2' => __('2:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '3' => __('3:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '4' => __('4:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '5' => __('5:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '6' => __('6:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '7' => __('7:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '8' => __('8:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '9' => __('9:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '10' => __('10:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '11' => __('11:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '12' => __('12:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '13' => __('1:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '14' => __('2:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN),'15' => __('3:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '16' => __('4:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '17' => __('5:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '18' => __('6:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '19' => __('7:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '20' => __('8:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '21' => __('9:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '22' => __('10:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '23' => __('11:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN));
        $timeKeysget = array('0' => __('00:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '1' => __('01:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '2' => __('02:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '3' => __('03:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '4' => __('04:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '5' => __('05:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '6' => __('06:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '7' => __('07:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '8' => __('08:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '9' => __('09:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '10' => __('10:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '11' => __('11:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '12' => __('12:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '13' => __('13:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '14' => __('14:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN),'15' => __('15:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '16' => __('16:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '17' => __('17:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '18' => __('18:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '19' => __('19:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '20' => __('20:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '21' => __('21:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '22' => __('22:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '23' => __('23:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN));
        
        if($hourly_booking == 'yes')
        {
            $booking_checkin_date = $_POST['date_of_only_hourly_booking']." ".$_POST['checkintime'];
            $booking_checkout_date = $_POST['date_of_only_hourly_booking']." ".$_POST['checkouttime'];
        }

      

        $cdate = date('Y-m-d H:i:s');
        $booking_price = $VRCalendarEntity->getBookingPrice($_POST['cal_id'], $_POST['booking_checkin_date'], $_POST['booking_checkout_date'], $_POST['booking_guests_count']);

        $booking_status = __('pending', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        $booking_payment_status = __('pending', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        /* Check id payment is required */
        $redirect_page =  'payment_page';
        $booking_admin_approved = __('yes', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        if($cal_data->calendar_requires_admin_approval == 'yes') {
            $booking_admin_approved = __('no', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
        }
        if($cal_data->calendar_payment_method == 'none') {
            $redirect_page =  'thank_you_page';
            $booking_payment_status = 'not_required';
            if($cal_data->calendar_requires_admin_approval == 'no') {
                $booking_status = 'confirmed';
            }
        } else {
            /* check if admin approval is needed */
            if($cal_data->calendar_requires_admin_approval == 'yes') {
                $redirect_page =  'thank_you_page';
            }
        }    

        if($cal_data->calendar_payment_method == 'deposit')
        {
            $redirect_page =  'thank_you_page';
        }
        
        

        $booking_data = array(
            'booking_calendar_id'=>$_POST['cal_id'],
            'booking_source'=>'website',
            'booking_date_from'=>$booking_checkin_date,
            'booking_date_to'=>$booking_checkout_date,
            'booking_guests'=>$_POST['booking_guests_count'],
            'booking_user_fname'=>$_POST['user_first_name'],
            'booking_user_lname'=>$_POST['user_last_name'],
            'booking_user_email'=>$_POST['user_email'],
            'booking_summary'=>$_POST['booking_note'],
            'booking_status'=>$booking_status,
            'booking_payment_status'=>$booking_payment_status,
            'booking_admin_approved'=>$booking_admin_approved,
            'booking_sub_price'=>$booking_price,
            'booking_total_price'=>$booking_price['booking_price_with_taxes'],
            'booking_created_on'=>$cdate,
            'booking_modified_on'=>$cdate
        );
        $booking_id = $VRCalendarBooking->saveBooking( $booking_data );
        /* Now send user on Next screen with payment Id */
        $payment_page = add_query_arg(array('bid'=>$booking_id), get_permalink($VRCalendarSettings->getSettings($redirect_page)) );
        wp_redirect($payment_page);
        exit;
    }

    function getUpdatedPrice() {
        
        $VRCalendarEntity = VRCalendarEntity::getInstance();

        $cal_id = $_POST['cal_id'];
        $checkin_date = $_POST['checkin_date'];
        $checkout_date = $_POST['checkout_date'];
        $total_guest_no = $_POST['booking_guests_count'];
        
        
        
        $booking_price = $VRCalendarEntity->getBookingPrice($cal_id, $checkin_date, $checkout_date, $total_guest_no);
        
        echo json_encode($booking_price);
        exit;
    }

    function getUpdatedCheckoudate()
    {
        
        $VRCalendarEntity = VRCalendarEntity::getInstance();
        $cal_data = $VRCalendarEntity->getCalendar( $_POST['cal_id'] );
        
        
        $pro_one_day_book = $cal_data->pro_one_day_book;

        $hourly_booking = $cal_data->hourly_booking;
        
        $timeArray = array('0' => __('12:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '1' => __('1:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '2' => __('2:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '3' => __('3:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '4' => __('4:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '5' => __('5:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '6' => __('6:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '7' => __('7:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '8' => __('8:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '9' => __('9:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '10' => __('10:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '11' => __('11:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '12' => __('12:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '13' => __('1:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '14' => __('2:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN),'15' => __('3:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '16' => __('4:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '17' => __('5:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '18' => __('6:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '19' => __('7:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '20' => __('8:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '21' => __('9:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '22' => __('10:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '23' => __('11:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN));
        $timeKeysget = array('0' => __('00:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '1' => __('01:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '2' => __('02:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '3' => __('03:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '4' => __('04:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '5' => __('05:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '6' => __('06:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '7' => __('07:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '8' => __('08:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '9' => __('09:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '10' => __('10:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '11' => __('11:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '12' => __('12:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '13' => __('13:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '14' => __('14:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN),'15' => __('15:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '16' => __('16:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '17' => __('17:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '18' => __('18:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '19' => __('19:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '20' => __('20:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '21' => __('21:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '22' => __('22:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '23' => __('23:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN));
        
        $hoursbookingdiifference = $cal_data->hoursbookingdiifference;
        $disabledtextbox = 'disabled="disabled"';
        global $wpdb;
        $table_name = $wpdb->prefix . 'vrcalandar_bookings';
        $results = $wpdb->get_results( "SELECT booking_date_from, booking_date_to FROM $table_name WHERE booking_admin_approved = 'yes' AND booking_calendar_id = '".$_POST['cal_id']."' AND DATE(booking_date_to) = '".$_POST['date_of_only_hourly_booking']."'", OBJECT );
        
        $ix = 0;
        if(count($results) > 0)
            {
                foreach($results as $gettimes)
                {
                    foreach($timeKeysget as $key => $timeget)
                    {
                        //$_POST['thisinvalue']
                        $dates1 = date("H:i:s", strtotime('+'.$hoursbookingdiifference.' hours', strtotime($gettimes->booking_date_to)));
                        $dates2 = date("H:i:s", strtotime('-'.$hoursbookingdiifference.' hours', strtotime($gettimes->booking_date_from)));
                        
                        if(strtotime($timeget) > strtotime($_POST['thisinvalue']) && strtotime($timeget) <= strtotime($dates2)){
                            $arrayinTimes[$key] = $timeget;
                        }
                        
                        if(strtotime($_POST['thisinvalue']) > strtotime($dates2) && strtotime($timeget) > strtotime($_POST['thisinvalue'])){
                            $arrayinTimes[$key] = $timeget;
                        }
                    }
                }
            }
         else {
                foreach($timeKeysget as $key => $timeget)
                {
                    if(strtotime($_POST['thisinvalue']) != strtotime($timeget))
                    {
                        if(strtotime($_POST['thisinvalue']) < strtotime($timeget))
                        {
                            $arrayinTimes[$key] = $timeget;
                        }
                    }
                }
            }
        
        echo json_encode($arrayinTimes);
        exit;
    }
    
    function getDatesFromRange($start, $end) 
                {
        $interval = new DateInterval('P1D');

        $realEnd = new DateTime($end);
        $realEnd->add($interval);

        $period = new DatePeriod(
             new DateTime($start),
             $interval,
             $realEnd
        );

        foreach($period as $date) { 
            $array[] = $date->format('Y-m-d'); 
        }

        return $array;
    }

    function showgetavailablerangebgcolorset()
    {
           global $wpdb;
           $table_name = $wpdb->prefix . 'vrcalandar_bookings';
           $start = $_POST['date1'];
           $end = $_POST['date2'];
           $get_calendar_id = $_POST['get_calendar_id'];
           
           if($end == '')
           {
               echo 3;
               exit;
           }
           /*$dates = array($start);
            
           while(end($dates) < $end)
            {
                $dates[] = date('Y-m-d', strtotime(end($dates).' +1 day'));
            }
            
            echo json_encode($dates);*/
           
           $check_valid_date = 0;
           if($end != '')
           {
            /*if(date('m', strtotime($start)) != date('m', strtotime($end)))
            {
                $check_valid_date = 1;
            }*/
           }
           
           $VRCalendarEntity = VRCalendarEntity::getInstance();
            $newcal_data = $VRCalendarEntity->getCalendar( $get_calendar_id );
           
            if($newcal_data->show_booking_from_one_page == 'yes')
            {
                
                $results = $wpdb->get_results( "SELECT * FROM {$table_name} WHERE `booking_calendar_id` = '{$get_calendar_id}' AND  `booking_status` = 'confirmed' AND  DATE(booking_date_from) <= '".$start."' AND DATE(booking_date_to) >= '".$start."'", OBJECT );
                
                
                if(count($results) > 0)
                {
                    
                    
                   $newdate = date('Y-m-d',date(strtotime("+1 day", strtotime($start))));
                   
                   $results_update = $wpdb->get_results( "SELECT * FROM {$table_name} WHERE  `booking_calendar_id` = '{$get_calendar_id}' AND `booking_status` = 'confirmed' AND DATE(booking_date_from) <= '".$newdate."' AND DATE(booking_date_to) >= '".$newdate."'", OBJECT );
                   if(count($results_update) == 0)
                   {
                       $check_valid_date = 0;
                   }
                   else
                   {
                       $check_valid_date = 1;
                   }
                   
                   if($end != '')
                   {
                       $dates = $this->getDatesFromRange($start, $end);
                    
                        if(count($dates) > 0)
                        {
                            foreach ($dates as $getSingalDate)
                            {
                                if($getSingalDate != $start)
                                {
                                    $results_update = $wpdb->get_results( "SELECT * FROM {$table_name} WHERE  `booking_calendar_id` = '{$get_calendar_id}' AND  `booking_status` = 'confirmed' AND DATE(booking_date_from) <= '".$getSingalDate."' AND DATE(booking_date_to) >= '".$getSingalDate."'", OBJECT );

                                    if(count($results_update) > 0)
                                    {
                                        $check_valid_date = 1;
                                        if($check_valid_date == 1)
                                        {
                                            $resultsupdate = $wpdb->get_results( "SELECT * FROM {$table_name} WHERE  `booking_calendar_id` = '{$get_calendar_id}' AND  `booking_status` = 'confirmed' AND DATE(booking_date_from) = '".$end."'", OBJECT );
                                            
                                            if(count($resultsupdate) > 0)
                                            {
                                                $check_valid_date = 0;
                                            }
                                        }
                                    }
                                }
                            }
                        }
                   }
                    
                    
                }else
                {
                    $dates = $this->getDatesFromRange($start, $end);
                    
                    if(count($dates) > 0)
                    {
                        foreach ($dates as $getSingalDate)
                        {
                            $results_update = $wpdb->get_results( "SELECT * FROM {$table_name} WHERE `booking_calendar_id` = '{$get_calendar_id}' AND `booking_status` = 'confirmed' AND DATE(booking_date_from) <= '".$getSingalDate."' AND DATE(booking_date_to) >= '".$getSingalDate."'", OBJECT );
                            
                            if(count($results_update) > 0)
                            {
                                $check_valid_date = 1;
                                $resultsupdate = $wpdb->get_results( "SELECT * FROM {$table_name} WHERE `booking_calendar_id` = '{$get_calendar_id}' AND `booking_status` = 'confirmed' AND DATE(booking_date_from) = '".$end."'", OBJECT );
                                            
                                if(count($resultsupdate) > 0)
                                {
                                    $check_valid_date = 0;
                                }
                            }
                        }
                    }
                }
            }
           
           
            echo $check_valid_date;
            exit;
    }

    function getAvailableRangeEnd() {
        $VRCalendarBooking = VRCalendarBooking::getInstance();
		if(!isset($_POST['cal_id']))
		exit;

        $start_date = (isset($_POST['start_date'])) ? $_POST['start_date'] : '' ;
        $cal_id = $_POST['cal_id'];
        $available_till = $VRCalendarBooking->availableTill($cal_id, $start_date);
        echo $available_till;
        exit;
    }

    function initShortcodes() {
        VRCalendarShortcode::getInstance();
        VRCalendarBookingBtnShortcode::getInstance();
		// .........add searchbars........................start..........................
        global $gbversiontype;
        if (($gbversiontype == "enterprisepaid") or ($gbversiontype == "enterprise500")){
            VRCalendarSearchbarShortcode::getInstance();
			VRCalendarSearchbarResultShortcode::getInstance();
        }
		// .... ....add searchbars.......................end..........................
        VRBookingShortcode::getInstance();
        VRPaymentShortcode::getInstance();
        VRThankyouShortcode::getInstance();
    }

    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    function loadPluginTextdomain() {
        $domain = VRCALENDAR_PLUGIN_TEXT_DOMAIN;
        $locale = apply_filters( 'plugin_locale', get_locale(), $domain );

        load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
        load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname(dirname( __FILE__ )) ) ) . '/Languages/' );
    }

    /**
     * Register and enqueue public-facing style sheet.
     *
     * @since    1.0.0
     */
    public function enqueueStyles()
    {
        $VRCalendarSettings = VRCalendarSettings::getInstance();
        wp_enqueue_style( VRCALENDAR_PLUGIN_SLUG . '-bootstrap-styles', VRCALENDAR_PLUGIN_URL.'assets/css/bootstrap.min.css', array(), self::VERSION );
        wp_enqueue_style( VRCALENDAR_PLUGIN_SLUG . '-owl-carousel-main', VRCALENDAR_PLUGIN_URL.'assets/plugins/owl-carousel/owl.carousel.min.css', array(), self::VERSION );
        wp_enqueue_style( VRCALENDAR_PLUGIN_SLUG . '-owl-carousel-theme', VRCALENDAR_PLUGIN_URL.'assets/plugins/owl-carousel/owl.theme.min.css', array(), self::VERSION );
        wp_enqueue_style( VRCALENDAR_PLUGIN_SLUG . '-owl-carousel-transitions', VRCALENDAR_PLUGIN_URL.'assets/plugins/owl-carousel/owl.transitions.min.css', array(), self::VERSION );
        wp_enqueue_style( VRCALENDAR_PLUGIN_SLUG . '-tipsy-style', VRCALENDAR_PLUGIN_URL.'assets/plugins/tipsy/tipsy.min.css', array(), self::VERSION );
        wp_enqueue_style( VRCALENDAR_PLUGIN_SLUG . '-calendar-styles', VRCALENDAR_PLUGIN_URL.'assets/css/calendar.min.css', array(), self::VERSION );

        if ($VRCalendarSettings->getSettings('load_jquery_ui_css', 'yes') == 'yes') {
			wp_register_style('jquery-ui-min', VRCALENDAR_PLUGIN_URL.'assets/css/jquery-ui-core.min.css', array(), self::VERSION );
			wp_enqueue_style('jquery-ui-min');
			//wp_enqueue_style( VRCALENDAR_PLUGIN_SLUG . 'jquery-ui-min', VRCALENDAR_PLUGIN_URL.'assets/css/jquery-ui-core.min.css', array(), self::VERSION );
        }

		wp_enqueue_style( VRCALENDAR_PLUGIN_SLUG . '-dataTable-styles', VRCALENDAR_PLUGIN_URL.'assets/css/jquery.dataTables.min.css', array(), self::VERSION );
        wp_enqueue_style( VRCALENDAR_PLUGIN_SLUG . '-plugin-styles', VRCALENDAR_PLUGIN_URL.'assets/css/public.min.css', array(), self::VERSION );
    }

    /**
     * Register and enqueues public-facing JavaScript files.
     *
     * @since    1.0.0
     */
    public function enqueueScripts()
    {
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $VRCalendarSettings = VRCalendarSettings::getInstance();
		wp_enqueue_script( 'jquery-form', VRCALENDAR_PLUGIN_URL.'assets/plugins/jquery.form.min.js', array( 'jquery' ), self::VERSION );
        wp_enqueue_script( VRCALENDAR_PLUGIN_SLUG . '-bootstrap-script', VRCALENDAR_PLUGIN_URL.'assets/js/bootstrap.min.js', array( 'jquery' ), self::VERSION );
        wp_enqueue_script( VRCALENDAR_PLUGIN_SLUG . '-owl-carousel-script', VRCALENDAR_PLUGIN_URL.'assets/plugins/owl-carousel/owl.carousel.min.js', array( 'jquery' ), self::VERSION );
        wp_enqueue_script( VRCALENDAR_PLUGIN_SLUG . '-tipsy-script', VRCALENDAR_PLUGIN_URL.'assets/plugins/tipsy/jquery.tipsy.min.js', array( 'jquery' ), self::VERSION );
        wp_enqueue_script( VRCALENDAR_PLUGIN_SLUG . '-validation-script', VRCALENDAR_PLUGIN_URL.'assets/plugins/validation/jquery.validate.min.js', array( 'jquery' ), self::VERSION );
		//wp_enqueue_script('jquery-ui-min', VRCALENDAR_PLUGIN_URL.'assets/js/jquery-ui.min.js', array( 'jquery' ), self::VERSION );
		wp_enqueue_script( 'jquery' );
		wp_register_script( 'jqueryui', VRCALENDAR_PLUGIN_URL.'assets/js/jquery-ui.min.js', array( 'jquery' ), self::VERSION );
		wp_enqueue_script( 'jqueryui' );
		//wp_enqueue_script('jquery-maps', $protocol.'maps.googleapis.com/maps/api/js', array( 'jquery' ));
		wp_enqueue_script( VRCALENDAR_PLUGIN_SLUG . '-dataTable-script', VRCALENDAR_PLUGIN_URL.'assets/js/jquery.dataTables.min.js'); 
        wp_enqueue_script( VRCALENDAR_PLUGIN_SLUG . '-plugin-script', VRCALENDAR_PLUGIN_URL.'assets/js/public.js', array( 'jquery', 'jquery-ui-datepicker' ), self::VERSION );       
        $vrc_data = array(
            'ajax_url'=>admin_url('admin-ajax.php'),
            'booking_url' => add_query_arg(array('cid'=>'{cid}'), get_permalink($VRCalendarSettings->getSettings('booking_page')) ),
            'thankyou_url' => add_query_arg(array('bid'=>'{bid}'), get_permalink($VRCalendarSettings->getSettings('thank_you_page')) )
        );
        wp_localize_script( VRCALENDAR_PLUGIN_SLUG . '-plugin-script', 'vrc_data', $vrc_data );
    }

    static function activate() {
		global $wpdb;
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$VRCalendarEntity = VRCalendarEntity::getInstance();
		$VRCalendarBooking = VRCalendarBooking::getInstance();
		$VRCalendarSettings = VRCalendarSettings::getInstance();
		if ( function_exists('is_multisite') && is_multisite() ) {
			$current_blog = $wpdb->blogid;
			$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
			foreach ( $blog_ids as $blog_id ) {
				switch_to_blog( $blog_id );
				$VRCalendarEntity->createTable();
				$VRCalendarBooking->createTable();

				$VRCalendarBooking->createTableUnableDate();

				$VRCalendarSettings = VRCalendarSettings::getInstance();
				wp_schedule_event( time(), $VRCalendarSettings->getSettings('auto_sync', 'daily'), 'vrc_cal_sync_hook' );
				wp_schedule_event( time(), 'daily', 'vrc_cal_payment_email_hook' );
				/* Create all required pages */
				$pages = array(
					'booking_page' => array('title'=>__('Booking Page', VRCALENDAR_PLUGIN_TEXT_DOMAIN), 'content'=>'[vrc_booking /]'),
					'payment_page' => array('title'=>__('Payment Page', VRCALENDAR_PLUGIN_TEXT_DOMAIN), 'content'=>'[vrc_payment /]'),
					'thank_you_page' => array('title'=>__('Thank you', VRCALENDAR_PLUGIN_TEXT_DOMAIN), 'content'=>'[vrc_thankyou /]'),
					'payment_cancel_page' => array('title'=>__('Payment Cancel', VRCALENDAR_PLUGIN_TEXT_DOMAIN), 'content'=>'Your Order is Canceled'),
					'searchbar_result_page' => array('title'=>__('Search Bar Result', VRCALENDAR_PLUGIN_TEXT_DOMAIN), 'content'=>'[vrcalendar_searchbar_result /]'),
				);
				foreach($pages as $setting=>$data) {
					$the_page = get_page_by_title( $data['title'] );
					if ( ! $the_page ) {
						$_p = array();
						$_p['post_title'] = $data['title'];
						$_p['post_content'] = $data['content'];
						$_p['post_status'] = 'publish';
						$_p['post_type'] = 'page';
						$_p['comment_status'] = 'closed';
						$_p['ping_status'] = 'closed';
						// Insert the post into the database
						$the_page_id = wp_insert_post( $_p );
					}
					else {
						$the_page_id = $the_page->ID;
						//make sure the page is not trashed...
						$the_page->post_status = 'publish';
						$the_page->post_content = $data['content'];
						$the_page_id = wp_update_post( $the_page );
					}
					$VRCalendarSettings->setSettings($setting, $the_page_id);
				}
				restore_current_blog();
			}
		} else {
			$VRCalendarEntity->createTable();
			$VRCalendarBooking->createTable();

			$VRCalendarBooking->createTableUnableDate();

			$VRCalendarSettings = VRCalendarSettings::getInstance();
			/* Setup Cal Sync Task */
			wp_schedule_event( time(), $VRCalendarSettings->getSettings('auto_sync', 'daily'), 'vrc_cal_sync_hook' );
			wp_schedule_event( time(), 'daily', 'vrc_cal_payment_email_hook' );
			/* Create all required pages */
			$pages = array(
				'booking_page' => array('title'=>__('Booking Page', VRCALENDAR_PLUGIN_TEXT_DOMAIN), 'content'=>'[vrc_booking /]'),
				'payment_page' => array('title'=>__('Payment Page', VRCALENDAR_PLUGIN_TEXT_DOMAIN), 'content'=>'[vrc_payment /]'),
				'thank_you_page' => array('title'=>__('Thank you', VRCALENDAR_PLUGIN_TEXT_DOMAIN), 'content'=>'[vrc_thankyou /]'),
				'payment_cancel_page' => array('title'=>__('Payment Cancel', VRCALENDAR_PLUGIN_TEXT_DOMAIN), 'content'=>'Your Order is Canceled'),
				'searchbar_result_page' => array('title'=>__('Search Bar Result', VRCALENDAR_PLUGIN_TEXT_DOMAIN), 'content'=>'[vrcalendar_searchbar_result /]'),
			);
			foreach($pages as $setting=>$data) {
				$the_page = get_page_by_title( $data['title'] );
				if ( ! $the_page ) {
					$_p = array();
					$_p['post_title'] = $data['title'];
					$_p['post_content'] = $data['content'];
					$_p['post_status'] = 'publish';
					$_p['post_type'] = 'page';
					$_p['comment_status'] = 'closed';
					$_p['ping_status'] = 'closed';
					// Insert the post into the database
					$the_page_id = wp_insert_post( $_p );
				}
				else {
					$the_page_id = $the_page->ID;
					//make sure the page is not trashed...
					$the_page->post_status = 'publish';
					$the_page->post_content = $data['content'];
					$the_page_id = wp_update_post( $the_page );
				}
				$VRCalendarSettings->setSettings($setting, $the_page_id);
			}
		}
    }

    static function deactivate() {
        wp_clear_scheduled_hook( 'vrc_cal_sync_hook' );
        wp_clear_scheduled_hook( 'vrc_cal_payment_email_hook' );
    }

}