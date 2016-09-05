<?php
    $user_id   = get_current_user_id();
    $user_meta = get_user_meta($user_id,'_user_settings');
    if(!empty($user_meta[0])){
    $user_data = unserialize($user_meta[0]);
    }
?>
<table class="form-table form-table05">
    <tbody>
    <tr valign="top">
        <th>
            <?php _e('Thank You Page', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
        </th>
        <td>
            <input type="text" id="thank_you_page" name="thank_you_page" value="<?php if(isset($user_data['thank_you_page'])){ echo $user_data['thank_you_page'];} ?>" class="large-text" placeholder="<?php _e('Thank you page url', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>">
        </td>
    </tr>
    <tr valign="top">
        <th>
            <?php _e('Cancel Payment Page', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
        </th>
        <td>
            <input type="text" id="cancel_payment_page" name="cancel_payment_page" value="<?php if(isset($user_data['cancel_payment_page'])){ echo $user_data['cancel_payment_page'];} ?>" class="large-text" placeholder="<?php _e('Cancel payment page url', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>">
        </td>
    </tr>
    <tr valign="top">
        <th>
            <?php _e('PayPal Email ID', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
        </th>
        <td>
            <input type="text" id="paypal_email" name="paypal_email" value="<?php if(isset($user_data['paypal_email'])){ echo $user_data['paypal_email'];} ?>" class="large-text" placeholder="<?php _e('PayPal Email', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>">
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
                    if(isset($user_data['payment_mode'])  && $mode == $user_data['payment_mode']) 
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
                    if( isset($user_data['auto_sync'])  && $val == $user_data['auto_sync'])
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
                if( isset($user_data['attr_currency']) && $curr == $user_data['attr_currency'] )
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
                if(isset($user_data['language']) &&  $val == $user_data['language'])
                    $selected = 'selected="selected"';
                ?>
                <option value="<?php echo $val; ?>" <?php echo $selected; ?>><?php echo $text; ?></option>
            <?php endforeach; ?>
        </select>
    </td>
    </tr>
</tbody>
</table>