<?php
class VRCalendarBookingBtnShortcode extends VRCShortcode {

    protected $slug = 'vrcalendar_booking_btn';

    function shortcode_handler($atts, $content = "") {		
        $this->atts = shortcode_atts(
            array(
                'id'=>false,
                'class'=>''
            ),$atts, 'vrcalendar_booking_btn');

        if(!$this->atts['id'])
            return __('Calendar id is missing', VRCALENDAR_PLUGIN_TEXT_DOMAIN);

        $VRCalendarSettings = VRCalendarSettings::getInstance();
        $bdate = date('Y-m-d');
        $booking_url = add_query_arg(array('cid'=>$this->atts['id'], 'bdate'=>$bdate), get_permalink($VRCalendarSettings->getSettings('booking_page')) );

        $output = "<a href='{$booking_url}' class='{$this->atts['class']}'>{$content}</a>";

        return $output;
    }

}