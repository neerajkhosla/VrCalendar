<div id="vrc-payment-paypal">
<div class="message">Please click on the PayPal button for payment </div>
    <form method="post" action="">
        <input type="hidden" name="vrc_pcmd" id="vrc_pcmd" value="paypalPayment" />
        <input type="hidden" name="bid" id="bid" value="<?php echo $data['booking_data']->booking_id; ?>" />
        <input type="submit" class="btn btn-primary" value="<?php _e('Pay via PayPal', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>" />
    </form>
</div>
