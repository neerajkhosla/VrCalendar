<?php
class VRPaymentShortcode extends VRCShortcode {

    protected $slug = 'vrc_payment';

    function shortcode_handler($atts, $content = "") {

        if(!$_GET['bid'])
            return __('Booking id is missing', VRCALENDAR_PLUGIN_TEXT_DOMAIN);

        $atts = shortcode_atts( array(
            'payment_received' => __('Your payment has been received and your booking is approved.', VRCALENDAR_PLUGIN_TEXT_DOMAIN)
        ), $atts );
        
        
        $VRCalendarEntity = VRCalendarEntity::getInstance();
        $VRCalendarBooking = VRCalendarBooking::getInstance();

        $booking_data = $VRCalendarBooking->getBookingByID( $_GET['bid'] );
        $cal_data = $VRCalendarEntity->getCalendar( $booking_data->booking_calendar_id );
        $data = array(
            'booking_data'=>$booking_data,
            'cal_data'=>$cal_data,
            'payment_received'=>$atts['payment_received']
        );
        
        if($booking_data->booking_payment_status == 'pending')
        {
            return $this->renderView('Payment', $data);
        }
        else
        {
            return $this->renderView('PaymentReceived', $data);
        }
    }
}