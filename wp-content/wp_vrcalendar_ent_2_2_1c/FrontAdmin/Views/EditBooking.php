<?php 
$VRCalendarBooking = VRCalendarBooking::getInstance();
$booking_details = $VRCalendarBooking->getBookingByID($bid); 

$datef = explode(" ",$booking_details->booking_date_from);
$datet = explode(" ",$booking_details->booking_date_to);
$datec = explode(" ",$booking_details->booking_created_on);

$depositeprice = $booking_details->booking_total_price - $booking_details->booking_sub_price['remining_price_amount'];

if(isset($_POST['admin_booking_update'])){
	
	$data = $_POST;
	global $wpdb,$post;
	$table = $wpdb->prefix.'vrcalandar_bookings';
	$booking_details->booking_sub_price['remining_price_amount'] = $data['booking_remaining_amount'];
	$data['booking_sub_price']=  $booking_details->booking_sub_price;
	$data['booking_sub_price'] = json_encode($data['booking_sub_price']);
    $data['booking_summary'] = htmlentities($data['booking_summary'], ENT_QUOTES);
	$sql = "update {$table} set booking_source='{$data['booking_source']}', booking_date_from='{$data['booking_date_from']}', booking_date_to='{$data['booking_date_to']}', booking_guests='{$data['booking_guests']}', booking_user_fname='{$data['booking_user_fname']}', booking_user_lname='{$data['booking_user_lname']}', booking_user_email='{$data['booking_user_email']}', booking_summary='{$data['booking_summary']}', booking_status='{$data['booking_status']}', booking_payment_status='{$data['booking_payment_status']}', booking_user_phone ='{$data['booking_user_phone']}', booking_sub_price='{$data['booking_sub_price']}', booking_admin_approved='{$data['booking_admin_approved']}', booking_total_price='{$data['booking_total_price']}', booking_modified_on =Now(), booking_created_on='{$data['booking_created_on']}' where booking_id='{$data['update_booking_id']}';";

	if($wpdb->query($sql)){
        $redirect_url = site_url($post->post_name."/?page=".VRCALENDAR_PLUGIN_SLUG."-dashboard&view=bookings&cal_id=".$calendar_id);                
        echo '<script>window.location = "'.$redirect_url.'"</script>';
        exit;
	}
	
}
?><h2>Edit Booking</h2>
<div class="notice-warning notice"><p>Warning: You can change any setting of the booking below, however changing the price will not automatically charge or refund the guest, you must do it manually. Also, changing the reservation dates will not check for conflicts so you could easily double book a listing if you aren't careful. Good luck!</p></div>
<form method="POST" action=""><table class="form-table">
<input type ="hidden" name="update_booking_id" value="<?php echo $bid;?>" >
    <tbody>
        <tr valign="top">
            <th>
                <?php _e('Booking Name First', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                <input type="text" id="booking_user_fname" name="booking_user_fname" value="<?php echo $booking_details->booking_user_fname;?>" class="large-text">
            </td>
        </tr>
		<tr valign="top">
            <th>
                <?php _e('Booking Name Last', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                 <input type="text" id="booking_user_lname" name="booking_user_lname" value="<?php echo $booking_details->booking_user_lname;?>" class="large-text">
            </td>
        </tr>		
		<tr valign="top">
            <th>
                <?php _e('Booking Source', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                 <input type="text" id="booking_source" name="booking_source" value="<?php echo $booking_details->booking_source;?>" class="large-text">
            </td>
        </tr>
		<tr valign="top">
            <th>
                <?php _e('Booking Email', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                 <input type="text" id="booking_user_email" name="booking_user_email" value="<?php echo $booking_details->booking_user_email;?>" class="large-text">
            </td>
        </tr>
		<tr valign="top">
            <th>
                <?php _e('Booking Phone', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                 <input type="text" id="booking_user_phone" name="booking_user_phone" value="<?php echo $booking_details->booking_user_phone;?>" class="large-text">
            </td>
        </tr>
		<tr valign="top">
            <th>
                <?php _e('Booking From', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                 <input type="text" class ="vrc-calendar-admin" id="booking_date_from" name="booking_date_from" value="<?php echo $datef[0];?>" class="large-text">
            </td>
        </tr>
		<tr valign="top">
            <th>
                <?php _e('Booking To', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                 <input type="text" class ="vrc-calendar-admin" id="booking_date_to" name="booking_date_to" value="<?php echo $datet[0];?>" class="large-text">
            </td>
        </tr>
		<tr valign="top">
            <th>
                <?php _e('Guest', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                 <input type="number" id="booking_guests" name="booking_guests" value="<?php echo $booking_details->booking_guests;?>" class="large-text">
            </td>
        </tr>
		<tr valign="top">
            <th>
                <?php _e('Status', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
			    <select id="booking_status" name="booking_status">
				<option value ="pending" <?php echo ($booking_details->booking_status == 'pending')?'selected="selected"':'' ?>> Pending</option>  
				<option value ="confirmed" <?php echo ($booking_details->booking_status == 'confirmed')?'selected="selected"':'' ?>>Confirmed</option>  
				</select>               
            </td>
        </tr>
		<tr valign="top">
            <th>
                <?php _e('Payment Status', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
			    <select id="booking_payment_status" name="booking_payment_status">
				<option value ="pending" <?php echo ($booking_details->booking_payment_status == 'pending')?'selected="selected"':'' ?>> Pending</option>  
				<option value ="confirmed" <?php echo ($booking_details->booking_payment_status == 'confirmed')?'selected="selected"':''?>> Confirmed</option>  
				<option value ="confirmed" <?php echo ($booking_details->booking_payment_status == 'not_required')?'selected="selected"':'' ?>>Not Required</option> 
				</select>  
            </td>
        </tr>
		<tr valign="top">
            <th>
                <?php _e('Booking Approved', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
			    <select id="booking_admin_approved" name="booking_admin_approved">
				<option value ="yes" <?php echo ($booking_details->booking_admin_approved == 'yes')?'selected="selected"':'' ?>> Yes</option>  
				<option value ="no" <?php echo ($booking_details->booking_admin_approved == 'no')? 'selected="selected"':'' ?>>No</option>  
				</select>   
            </td>
        </tr>		
		<tr valign="top">
            <th>
                <?php _e('Booking Price ($)', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                 <input type="text" id="booking_total_price" name="booking_total_price" value="<?php echo $booking_details->booking_total_price;?>" class="large-text">
            </td>
        </tr>
		<tr valign="top">
            <th>
                <?php _e('Deposit Amount ($)', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                 <input type="text" id="booking_deposite_amount" name="booking_deposite_amount" value="<?php echo $depositeprice;?>" class="large-text">
            </td>
        </tr>
		<tr valign="top">
            <th>
                <?php _e('Remaining Amount ($)', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                 <input type="text" id="booking_remaining_amount" name="booking_remaining_amount" value="<?php echo $booking_details->booking_sub_price['remining_price_amount'];?>" class="large-text">
            </td>
        </tr>
		<tr valign="top">
            <th>
                <?php _e('Booking Date', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                 <input type="text" class ="vrc-calendar-admin" id="booking_created_on" name="booking_created_on" value="<?php echo $datec[0];?>" class="large-text">
            </td>
        </tr>
		<tr valign="top">
            <th>
                <?php _e('Summary', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
            </th>
            <td>
                 <textarea type="text" id="booking_summary" class="large-text" name="booking_summary"><?php echo $booking_details->booking_summary;?></textarea>
            </td>
        </tr>
		<tr valign="top">
            <th>
               
            </th>
            <td>
                 <input type="submit" id="admin_booking_update" name="admin_booking_update" value="Update" class="button button-primary">
            </td>
        </tr>
    </tbody>
</table></form>