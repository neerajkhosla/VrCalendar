<?php
class VRCTransactionalEmail extends VRCSingleton {

    private function sendEmail($subject, $content, $data, $to, $from='') {
        $VRCEmail = VRCEmail::getInstance();
        foreach($data as $placeholder=>$substitute) {
            $subject = str_replace('%'.$placeholder.'%', $substitute, $subject);
            $content = str_replace('%'.$placeholder.'%', $substitute, $content);
        }
        $VRCEmail->sendMail($to, $subject, $content, 'general', $from);
        return true;
    }
	function getEmailAddress($data) {
		global $wpdb;
		$calendar_id_email = $data['calendar_id'];
		$this->table_name = $wpdb->prefix.'vrcalandar';
		$sql = "select email_address_template, email_send_to from {$this->table_name} WHERE calendar_id =".$calendar_id_email;
		$cals = $wpdb->get_results($sql);
		return $cals;
	}
    function sendBookingRemoved($data, $to, $from='') {

        /*$subject = 'Your booking is removed';
        $content = <<<E
Hi %booking_user_fname% %booking_user_lname%,
<p>Your Booking is removed by the admin, following were your booking details for reference:</p>
<p>Booking ID: %booking_id%</p>
<p>Booking Date: %booking_created_on%</p>
<p>From: %booking_date_from%</p>
<p>To: %booking_date_to%</p>
<p>Guests: %booking_guests%</p>
E;*/
        $VRemailTemplateSubject =  VRCalendarEntity::getEmailTemplate($data['calendar_id'], 'your_booking_is_removed', 'subject');
        $VRemailTemplateBody = VRCalendarEntity::getEmailTemplate($data['calendar_id'], 'your_booking_is_removed', 'body');

        $subject = $VRemailTemplateSubject['subject'];
        $content = $VRemailTemplateBody['body'];

        return $this->sendEmail($subject, $content, $data, $to, $from);
    }

    function sendBookingApprovedNoPayment($data, $to, $from='') {
        /*$subject = 'Your booking is approved';
        $content = <<<E
Hi %booking_user_fname% %booking_user_lname%,
<br/>
Your Booking is confirmed.
E;*/
        $VRemailTemplateSubject = VRCalendarEntity::getEmailTemplate($data['calendar_id'], 'your_booking_is_approved', 'subject');
        $VRemailTemplateBody = VRCalendarEntity::getEmailTemplate($data['calendar_id'], 'your_booking_is_approved', 'body');

        $subject = $VRemailTemplateSubject['subject'];
        $content = $VRemailTemplateBody['body'];

        return $this->sendEmail($subject, $content, $data, $to, $from);
    }

    function sendBookingApprovedPayment($data, $to, $from='') {
        /*$subject = 'Your booking is approved';
        $content = <<<E
Hi %booking_user_fname% %booking_user_lname%,
<br/>
Your Booking is confirmed, now you can make payment for your booking by following this link %booking_payment_link%.
E;*/
        $VRemailTemplateSubject = VRCalendarEntity::getEmailTemplate($data['calendar_id'], 'your_booking_is_approved_make_payment', 'subject');
        $VRemailTemplateBody = VRCalendarEntity::getEmailTemplate($data['calendar_id'], 'your_booking_is_approved_make_payment', 'body');

        $subject = $VRemailTemplateSubject['subject'];
        $content = $VRemailTemplateBody['body'];
    
        return $this->sendEmail($subject, $content, $data, $to, $from);
    }

    function sendSyncConflict($data, $to, $from='') {
        
        $get_email_Address = $this->getEmailAddress($data);
		if(isset($get_email_Address)){
			foreach($get_email_Address as $get_email_Addr){
				$new_booking = $get_email_Addr->email_send_to;
				$email_new_booking = $get_email_Addr->email_address_template;
			}
		}
		if(isset($new_booking) && $new_booking == 'both' && $email_new_booking != ''){
			$to[] = $email_new_booking;
		}else if(isset($new_booking) && $new_booking == 'single'  && $email_new_booking != ''){
			$to = array($email_new_booking);
		}else{
			$to = $to;
		}

        /*$subject = "Conflict aroused while synchronizing calendar: %calendar_name%";
        $content =<<<E
 Conflict aroused while synchronizing calendar: %calendar_name%<br>
 Conflicting Source: %booking_source%<br>
 Event Start Date: %booking_date_from%<br>
 Event End Date: %booking_date_to%<br>
 Event Summary: %booking_summary%<br>
E;*/
        $VRemailTemplateSubject = VRCalendarEntity::getEmailTemplate($data['calendar_id'], 'conflict_aroused_while_synchronizing_calendar', 'subject');
        $VRemailTemplateBody = VRCalendarEntity::getEmailTemplate($data['calendar_id'], 'conflict_aroused_while_synchronizing_calendar', 'body');

        $subject = $VRemailTemplateSubject['subject'];
        $content = $VRemailTemplateBody['body'];
        
        return $this->sendEmail($subject, $content, $data, $to, $from);
    }

    function sendAdminBookingPendingApproval($data, $to, $from='') {
		
		$get_email_Address = $this->getEmailAddress($data);
		if(isset($get_email_Address)){
			foreach($get_email_Address as $get_email_Addr){
				$new_booking = $get_email_Addr->email_send_to;
				$email_new_booking = $get_email_Addr->email_address_template;
			}
		}
		if(isset($new_booking) && $new_booking == 'both' && $email_new_booking != ''){
			$to[] = $email_new_booking;
		}else if(isset($new_booking) && $new_booking == 'single'  && $email_new_booking != ''){
			$to = array($email_new_booking);
		}else{
			$to = $to;
		}
		
        /*$subject = 'Approval needed on new booking on %blogname%';
        $content = <<<E
Hi,
<p>New booking is received on %blogname% and is pending for approval</p>
<p>Booking Details:</p>
<p>Booking ID: %booking_id%</p>
<p>From: %booking_date_from%</p>
<p>To: %booking_date_to%</p>
<p>Guests: %booking_guests%</p>
<p>Summary: %booking_summary%</p>
<p><a href="%booking_admin_link%">Click here to view booking</a></p>
E;*/
        $VRemailTemplateSubject = VRCalendarEntity::getEmailTemplate($data['calendar_id'], 'approval_needed_on_new_booking', 'subject');
        $VRemailTemplateBody = VRCalendarEntity::getEmailTemplate($data['calendar_id'], 'approval_needed_on_new_booking', 'body');
         $subject = $VRemailTemplateSubject['subject'];
         $content = $VRemailTemplateBody['body'];
        return $this->sendEmail($subject, $content, $data, $to, $from);
    }

    function sendUserBookingPendingApproval($data, $to, $from='') {
        /*$subject = 'Your booking is received on %blogname% and is pending for approval';
        $content = <<<E
Hi %booking_user_fname% %booking_user_lname%,
<p>Your booking is received on %blogname% and is pending for approval</p>
<p>Booking Details:</p>
<p>Booking ID: %booking_id%</p>
<p>From: %booking_date_from%</p>
<p>To: %booking_date_to%</p>
<p>Guests: %booking_guests%</p>
<p>Summary: %booking_summary%</p>
E;*/
        $VRemailTemplateSubject = VRCalendarEntity::getEmailTemplate($data['calendar_id'], 'your_booking_is_received_is_pending_for_approval', 'subject');
        $VRemailTemplateBody = VRCalendarEntity::getEmailTemplate($data['calendar_id'], 'your_booking_is_received_is_pending_for_approval', 'body');
         $subject = $VRemailTemplateSubject['subject'];
         $content = $VRemailTemplateBody['body'];
        return $this->sendEmail($subject, $content, $data, $to, $from);
    }

    function sendAdminBookingCompleted($data, $to, $from='') {

		$get_email_Address = $this->getEmailAddress($data);
		if(isset($get_email_Address)){
			foreach($get_email_Address as $get_email_Addr){
				$new_booking = $get_email_Addr->email_send_to;
				$email_new_booking = $get_email_Addr->email_address_template;
			}
		}
		if(isset($new_booking) && $new_booking == 'both' && $email_new_booking != ''){
			$to[] = $email_new_booking;
		}else if(isset($new_booking) && $new_booking == 'single'  && $email_new_booking != ''){
			$to = array($email_new_booking);
		}else{
			$to = $to;
		}

        /*$subject = 'New booking on %blogname%';
        $content = <<<E
Hi,
<p>New booking is received on %blogname%</p>
<p>Booking Details:</p>
<p>Booking ID: %booking_id%</p>
<p>From: %booking_date_from%</p>
<p>To: %booking_date_to%</p>
<p>Guests: %booking_guests%</p>
<p>Summary: %booking_summary%</p>
<p><a href="%booking_admin_link%">Click here to view booking</a></p>
E;*/
        $VRemailTemplateSubject = VRCalendarEntity::getEmailTemplate($data['calendar_id'], 'new_booking', 'subject');
        $VRemailTemplateBody = VRCalendarEntity::getEmailTemplate($data['calendar_id'], 'new_booking', 'body');
        $subject = $VRemailTemplateSubject['subject'];
        $content = $VRemailTemplateBody['body'];
        return $this->sendEmail($subject, $content, $data, $to, $from);
    }

    function sendUserBookingCompleted($data, $to, $from='') {
        /*$subject = 'Your booking is received on %blogname%';
        $content = <<<E
Hi %booking_user_fname% %booking_user_lname%,
<p>Your booking is received on %blogname%</p>
<p>Booking Details:</p>
<p>Booking ID: %booking_id%</p>
<p>From: %booking_date_from%</p>
<p>To: %booking_date_to%</p>
<p>Guests: %booking_guests%</p>
<p>Summary: %booking_summary%</p>
E;*/
        $VRemailTemplateSubject = VRCalendarEntity::getEmailTemplate($data['calendar_id'], 'your_booking_is_received', 'subject');
        $VRemailTemplateBody = VRCalendarEntity::getEmailTemplate($data['calendar_id'], 'your_booking_is_received', 'body');
        $subject = $VRemailTemplateSubject['subject'];
        $content = $VRemailTemplateBody['body'];
        return $this->sendEmail($subject, $content, $data, $to, $from);
    }

    function sendAdminBookingPaymentCompleted($data, $to, $from='') {

		$get_email_Address = $this->getEmailAddress($data);
		if(isset($get_email_Address)){
			foreach($get_email_Address as $get_email_Addr){
				$new_booking = $get_email_Addr->email_send_to;
				$email_new_booking = $get_email_Addr->email_address_template;
			}
		}
		if(isset($new_booking) && $new_booking == 'both' && $email_new_booking != ''){
			$to[] = $email_new_booking;
		}else if(isset($new_booking) && $new_booking == 'single'  && $email_new_booking != ''){
			$to = array($email_new_booking);
		}else{
			$to = $to;
		}

        /*$subject = 'New booking payment received on %blogname%';
        $content = <<<E
Hi,
<p>New booking payment is received on %blogname%</p>
<p>Booking Details:</p>
<p>Booking ID: %booking_id%</p>
<p>Transaction ID: %booking_payment_data_txn_id%</p>
<p>Amount: %booking_total_price%</p>
<p>From: %booking_date_from%</p>
<p>To: %booking_date_to%</p>
<p>Guests: %booking_guests%</p>
<p>Summary: %booking_summary%</p>
<p><a href="%booking_admin_link%">Click here to view booking</a></p>
E;*/
        $VRemailTemplateSubject = VRCalendarEntity::getEmailTemplate($data['calendar_id'], 'new_booking_payment_received', 'subject');
        $VRemailTemplateBody = VRCalendarEntity::getEmailTemplate($data['calendar_id'], 'new_booking_payment_received', 'body');
        $subject = $VRemailTemplateSubject['subject'];
        $content = $VRemailTemplateBody['body'];
        return $this->sendEmail($subject, $content, $data, $to, $from);
    }

    function sendUserBookingPaymentCompleted($data, $to, $from='') {
        /*$subject = 'Your booking payment is received on %blogname%';
        $content = <<<E
Hi %booking_user_fname% %booking_user_lname%,
<p>Your booking payment is received on %blogname%</p>
<p>Booking Details:</p>
<p>Booking ID: %booking_id%</p>
<p>Transaction ID: %booking_payment_data_txn_id%</p>
<p>Amount : %booking_total_price%</p>
<p>From: %booking_date_from%</p>
<p>To: %booking_date_to%</p>
<p>Guests: %booking_guests%</p>
<p>Summary: %booking_summary%</p>
E;*/
        $VRemailTemplateSubject = VRCalendarEntity::getEmailTemplate($data['calendar_id'], 'your_bookingpayment_is_received', 'subject');
        $VRemailTemplateBody = VRCalendarEntity::getEmailTemplate($data['calendar_id'], 'your_bookingpayment_is_received', 'body');
        $subject = $VRemailTemplateSubject['subject'];
        $content = $VRemailTemplateBody['body'];
        return $this->sendEmail($subject, $content, $data, $to, $from);
    }
    
    //function sendBookingApprovedNoPayment($data, $to, $from='') {
    function sendIfDepositPaymentEnable($data, $to, $from='') {
        /*$subject = 'Your booking is approved';
        $content = <<<E
Hi %booking_user_fname% %booking_user_lname%,
<br/>
Your Booking is confirmed.
E;*/
        $VRemailTemplateSubject = VRCalendarEntity::getEmailTemplate($data['calendar_id'], 'if_deposit_payment_enable', 'subject');
        $VRemailTemplateBody = VRCalendarEntity::getEmailTemplate($data['calendar_id'], 'if_deposit_payment_enable', 'body');

        $subject = $VRemailTemplateSubject['subject'];
        $content = $VRemailTemplateBody['body'];

        return $this->sendEmail($subject, $content, $data, $to, $from);
    }
    
}