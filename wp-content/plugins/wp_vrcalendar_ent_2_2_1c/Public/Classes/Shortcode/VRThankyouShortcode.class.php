<?php
class VRThankyouShortcode extends VRCShortcode {

    protected $slug = 'vrc_thankyou';

    function shortcode_handler($atts, $content = "") {

        $atts = shortcode_atts( array(
            'payment_success' => __('Your Payment is completed successfully.', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
            'waiting_for_approval' => __('Your details are sent for approval, you will receive the confirmation email on approval of your booking.', VRCALENDAR_PLUGIN_TEXT_DOMAIN),
            'booking_success'=>__('Your booking is completed successfully.', VRCALENDAR_PLUGIN_TEXT_DOMAIN)
        ), $atts );

        if(!$_GET['bid'])
            return __('Something went wrong!!!', VRCALENDAR_PLUGIN_TEXT_DOMAIN);

        $VRCalendarEntity = VRCalendarEntity::getInstance();
        $VRCalendarBooking = VRCalendarBooking::getInstance();

        $booking_data = $VRCalendarBooking->getBookingByID( $_GET['bid'] );
        $cal_data = $VRCalendarEntity->getCalendar( $booking_data->booking_calendar_id );

        $msg = $atts['payment_success'];

        if($cal_data->calendar_requires_admin_approval == 'yes' && $booking_data->booking_admin_approved == 'no'){
            $msg = $atts['waiting_for_approval'];
        }
        else if($cal_data->calendar_payment_method == 'none') {
            $msg = $atts['booking_success'];
        }
        $data = array(
            'booking_data'=>$booking_data,
            'cal_data'=>$cal_data,
            'msg'=>$msg
        );
        /* Send email to admin */
        $blogname = get_bloginfo('name');
        $admin_email = get_option( 'admin_email' );;
        $VRCTransactionalEmail = VRCTransactionalEmail::getInstance();

        $booking_admin_link = admin_url('admin.php?page=vr-calendar-dashboard&view=bookings&cal_id=' . $booking_data->booking_calendar_id);

        $email_data = array(
            'calendar_id'=>$booking_data->booking_calendar_id,
            'blogname'=>$blogname,
            'booking_user_fname'=>$booking_data->booking_user_fname,
            'booking_user_lname'=>$booking_data->booking_user_lname,
            'booking_id'=>$booking_data->booking_id,
            'booking_payment_data_txn_id'=>@$booking_data->booking_payment_data['txn_id'],
            'booking_total_price'=>$booking_data->booking_total_price,
            'booking_date_from'=>$booking_data->booking_date_from,
            'booking_date_to'=>$booking_data->booking_date_to,
            'booking_guests'=>$booking_data->booking_guests,
            'booking_summary'=>$booking_data->booking_summary,
            'booking_admin_link'=>$booking_admin_link,
        );

        if($cal_data->calendar_requires_admin_approval == 'yes' && $booking_data->booking_admin_approved == 'no') {

            $VRCTransactionalEmail->sendAdminBookingPendingApproval($email_data, array($admin_email));
            $VRCTransactionalEmail->sendUserBookingPendingApproval($email_data, array($booking_data->booking_user_email));
        }
        else if($cal_data->calendar_payment_method == 'none') {

            $VRCTransactionalEmail->sendAdminBookingCompleted($email_data, array($admin_email));
            $VRCTransactionalEmail->sendUserBookingCompleted($email_data, array($booking_data->booking_user_email));
        }
        else {

            $VRCTransactionalEmail->sendAdminBookingPaymentCompleted($email_data, array($admin_email));
            $VRCTransactionalEmail->sendUserBookingPaymentCompleted($email_data, array($booking_data->booking_user_email));
        }
        return $this->renderView('Thankyou', $data);
    }
}