<table class="form-table">
    <tbody>
    <tr valign="top">
        <th>
            <?php _e('Booking Page', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
        </th>
        <td>
            <select id="booking_page" name="booking_page" class="large-text">
                <?php foreach($availablePages as $page):
                    $selected = '';
                    if( $page->ID == $VRCalendarSettings->getSettings('booking_page') )
                        $selected = 'selected="selected"';
                    ?>
                    <option value="<?php echo $page->ID; ?>" <?php echo $selected; ?>><?php echo $page->post_title; ?></option>
                <?php endforeach; ?>
            </select>
            <div class="desc"><small><?php _e('Selected page must have [vrc_booking /] shortcode', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></small></div>
        </td>
    </tr>
    <tr valign="top">
        <th>
            <?php _e('Payment Page', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
        </th>
        <td>
            <select id="payment_page" name="payment_page" class="large-text">
                <?php foreach($availablePages as $page):
                    $selected = '';
                    if( $page->ID == $VRCalendarSettings->getSettings('payment_page') )
                        $selected = 'selected="selected"';
                    ?>
                    <option value="<?php echo $page->ID; ?>" <?php echo $selected; ?>><?php echo $page->post_title; ?></option>
                <?php endforeach; ?>
            </select>
            <div class="desc"><small><?php _e('Selected page must have [vrc_payment /] shortcode', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></small></div>
        </td>
    </tr>
    <tr valign="top">
        <th>
            <?php _e('Thank you Page', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
        </th>
        <td>
            <select id="thank_you_page" name="thank_you_page" class="large-text">
                <?php foreach($availablePages as $page):
                    $selected = '';
                    if( $page->ID == $VRCalendarSettings->getSettings('thank_you_page') )
                        $selected = 'selected="selected"';
                    ?>
                    <option value="<?php echo $page->ID; ?>" <?php echo $selected; ?>><?php echo $page->post_title; ?></option>
                <?php endforeach; ?>
            </select>
            <div class="desc"><small><?php _e('Selected page must have [vrc_thankyou /] shortcode', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></small></div>
        </td>
    </tr>
    <tr valign="top">
        <th>
            <?php _e('Payment Cancel Page', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
        </th>
        <td>
            <select id="payment_cancel_page" name="payment_cancel_page" class="large-text">
                <?php foreach($availablePages as $page):
                    $selected = '';
                    if( $page->ID == $VRCalendarSettings->getSettings('payment_cancel_page') )
                        $selected = 'selected="selected"';
                    ?>
                    <option value="<?php echo $page->ID; ?>" <?php echo $selected; ?>><?php echo $page->post_title; ?></option>
                <?php endforeach; ?>
            </select>
            <div class="desc"></div>
        </td>
    </tr>
	<tr valign="top">
        <th>
            <?php _e('Search Bar Result Page', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
        </th>
        <td>
            <select id="searchbar_result_page" name="searchbar_result_page" class="large-text">
                <?php foreach($availablePages as $page):
                    $selected = '';
                    if( $page->ID == $VRCalendarSettings->getSettings('searchbar_result_page') )
                        $selected = 'selected="selected"';
                    ?>
                    <option value="<?php echo $page->ID; ?>" <?php echo $selected; ?>><?php echo $page->post_title; ?></option>
                <?php endforeach; ?>
            </select>
            <div class="desc"><small><?php _e('Selected page must have [vrcalendar_searchbar_result /] shortcode', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></small></div>
        </td>
    </tr>
    <tr valign="top">
        <th>
            <?php _e('PayPal Email ID', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
        </th>
        <td>
            <input type="text" id="paypal_email" name="paypal_email" value="<?php echo $VRCalendarSettings->getSettings('paypal_email'); ?>" class="large-text" placeholder="<?php _e('PayPal Email', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>">
        </td>
    </tr>
    <tr valign="top">
        <th>
            <?php _e('Stripe API Key (Private Key)', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
        </th>
        <td>
            <input type="text" id="stripe_api_key" name="stripe_api_key" value="<?php echo $VRCalendarSettings->getSettings('stripe_api_key'); ?>" class="large-text" placeholder="<?php _e('Stripe Api Key', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>">
        </td>
    </tr>
    <tr valign="top">
        <th>
            <?php _e('Payment Mode', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
        </th>
        <td>
            <select id="payment_mode" name="payment_mode" class="large-text">
                <?php foreach($payment_mode as $mode=>$text):
                    $selected = '';
                    if( $mode == $VRCalendarSettings->getSettings('payment_mode') )
                        $selected = 'selected="selected"';
                    ?>
                    <option value="<?php echo $mode; ?>" <?php echo $selected; ?>><?php echo $text; ?></option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    <tr valign="top">
        <th>
            <?php _e('Auto Sync', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
        </th>
        <td>
            <select id="auto_sync" name="auto_sync" class="large-text">
                <?php foreach($auto_sync as $val=>$text):
                    $selected = '';
                    if( $val == $VRCalendarSettings->getSettings('auto_sync', 'daily') )
                        $selected = 'selected="selected"';
                    ?>
                    <option value="<?php echo $val; ?>" <?php echo $selected; ?>><?php echo $text; ?></option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>
    <tr valign="top">
    <th>
        <?php _e('Show Attribution Link Below Calendar?', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
    </th>
    <td>
        <select id="attribution" name="attribution" class="large-text">
            <?php foreach($attribution as $val=>$text):
                $selected = '';
                if( $val == $VRCalendarSettings->getSettings('attribution') )
                    $selected = 'selected="selected"';
                ?>
            <option value="<?php echo $val; ?>" <?php echo $selected; ?>><?php echo $text; ?></option>
            <?php endforeach; ?>
        </select>
    </td>
    </tr>

    <tr valign="top">
        <th>
            <?php _e('Load jquery-ui.css?', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
        </th>
        <td>
            <select id="load_jquery_ui_css" name="load_jquery_ui_css" class="large-text">
                <?php foreach($load_jquery_ui_css as $val=>$text):
                    $selected = '';
                    if( $val == $VRCalendarSettings->getSettings('load_jquery_ui_css', 'yes') )
                        $selected = 'selected="selected"';
                    ?>
                    <option value="<?php echo $val; ?>" <?php echo $selected; ?>><?php echo $text; ?></option>
                <?php endforeach; ?>
            </select>
        </td>
    </tr>

	<tr valign="top">
    <th>
        <?php _e('Currency', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
    </th>
	<td>
        <select id="currency" name="attr_currency" class="large-text">
            <?php foreach($attr_currency as $curr=>$text):
                $selected = '';
                if( $curr == $VRCalendarSettings->getSettings('attr_currency') )
                    $selected = 'selected="selected"';
                ?>
            <option value="<?php echo $curr; ?>" <?php echo $selected; ?>><?php echo $text; ?></option>
            <?php endforeach; ?>
        </select>
    </td>
    </tr>
    <tr valign="top">
    <th>
        <?php _e('Select a Language', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
    </th>
    <td>
        <select id="language" name="language" class="large-text">
            <?php foreach($language as $val=>$text):
                $selected = '';
                if( $val == $VRCalendarSettings->getSettings('language') )
                    $selected = 'selected="selected"';
                ?>
                <option value="<?php echo $val; ?>" <?php echo $selected; ?>><?php echo $text; ?></option>
            <?php endforeach; ?>
        </select>
    </td>
    </tr>
</tbody>
</table>