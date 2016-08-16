<h3><?php echo $data['msg']; ?></h3>
<table>
    <tr>
        <td>
            <?php _e('Booking ID', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
        </td>
        <td>
            <?php echo $data['booking_data']->booking_id; ?>
        </td>
    </tr>
    <?php if(!empty($data['booking_data']->booking_payment_data['txn_id'])): ?>
    <tr>
        <td>
            <?php _e('Transaction ID', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
        </td>
        <td>
            <?php echo $data['booking_data']->booking_payment_data['txn_id']; ?>
        </td>
    </tr>
    <?php endif; ?>
</table>