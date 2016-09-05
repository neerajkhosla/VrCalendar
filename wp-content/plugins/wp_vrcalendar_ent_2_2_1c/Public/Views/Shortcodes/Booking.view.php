<style>
    .ui-widget-header {
        background: none;
        border: none;
    }
    .ui-state-default, .ui-widget-content .ui-state-default,
    .ui-widget-header .ui-state-default {
        background: <?php echo $data['calendar_data']->calendar_layout_options['available_bg_color'] ?>;
        border: none;
    }
    .ui-state-disabled, .ui-widget-content .ui-state-disabled,
    .ui-widget-header .ui-state-disabled {
        opacity: 1;
        filter: Alpha(Opacity=100);
    }
    .ui-state-default, .ui-widget-content .ui-state-disabled .ui-state-default,
    .ui-widget-header .ui-state-disabled .ui-state-default {
        background: <?php echo $data['calendar_data']->calendar_layout_options['unavailable_bg_color'] ?>;
    }
	.rowhide,.spanhide{display:none;}
</style>
<link href="<?php echo  plugins_url("/wp_vrcalendar_ent_2_2_1c/FrontAdmin/css/frontend.css"); ?>" rel="stylesheet"/> 
<?php
/* Add calendar style */

?>

<?php
$updated_price = 'style="display: none;"';
$default_price = '';
if($data['booking_price']['deposit_enable_by_date'] == 1)
{
    $updated_price = 'style="display: block;"';
    $default_price = 'style="display: none;"';
}
/* Add calendar style */


//print_r($_REQUEST['cid']);
/*$getDepositPaymentPayByLink = VRCalendarEntity::getDepositPaymentPayByLink();

echo "<pre>";
print_r($getDepositPaymentPayByLink);
echo "</pre>";*/


$pro_one_day_book = $data['calendar_data']->pro_one_day_book;


$hourly_booking = $data['calendar_data']->hourly_booking;

$timeArray = array('0' => __('12:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '1' => __('1:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '2' => __('2:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '3' => __('3:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '4' => __('4:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '5' => __('5:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '6' => __('6:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '7' => __('7:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '8' => __('8:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '9' => __('9:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '10' => __('10:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '11' => __('11:00am', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '12' => __('12:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '13' => __('1:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '14' => __('2:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN),'15' => __('3:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '16' => __('4:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '17' => __('5:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '18' => __('6:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '19' => __('7:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '20' => __('8:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '21' => __('9:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '22' => __('10:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '23' => __('11:00pm', VRCALENDAR_PLUGIN_TEXT_DOMAIN));
$timeKeysget = array('0' => __('00:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '1' => __('01:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '2' => __('02:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '3' => __('03:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '4' => __('04:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '5' => __('05:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '6' => __('06:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '7' => __('07:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '8' => __('08:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '9' => __('09:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '10' => __('10:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '11' => __('11:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '12' => __('12:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '13' => __('13:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '14' => __('14:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN),'15' => __('15:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '16' => __('16:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '17' => __('17:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '18' => __('18:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '19' => __('19:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '20' => __('20:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '21' => __('21:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '22' => __('22:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN), '23' => __('23:00:00', VRCALENDAR_PLUGIN_TEXT_DOMAIN));

$disabledtextbox = '';
if($hourly_booking == 'yes')
{
    $hoursbookingdiifference = $data['calendar_data']->hoursbookingdiifference;
    $disabledtextbox = 'disabled="disabled"';
    global $wpdb;
    $table_name = $wpdb->prefix . 'vrcalandar_bookings';
    $results = $wpdb->get_results( "SELECT booking_date_from, booking_date_to FROM $table_name WHERE booking_admin_approved = 'yes' AND booking_calendar_id = '".$data['calendar_data']->calendar_id."' AND DATE(booking_date_to) = '".$_GET['bdate']."'", OBJECT );
    
    if(count($results) > 0)
    {
        $arrayinTimes = array();
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
?>
<?php
	$booking_days = $data['booking_price']['booking_days'];
	$number_of_nights = $data['booking_price']['number_of_nights'];
	$minimum_number_of_nights = $data['booking_price']['minimum_number_of_nights'];
	$tableclass =' rowhide';
	$divclass ='';
	if($booking_days >= $number_of_nights){
		$tableclass='';
		$divclass =' rowhide';
	}
?>
<div class="vrc" id="vrc-booking-form-wrapper">
    <form name="vrc-booking-form" id="vrc-booking-form" class="vrc-validate" method="post" action="">
        <div class="booking-heading clearfix">
                <div id="booking-price-per-night" class="pull-left"><?php echo renderCurrency();?><span id="price-per-night"><?php echo $data['booking_price']['price_per_night']; ?></span></div>
            <div class="pull-right">
                <?php 
                
            $checkerDay = __('Per Night', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
            if($pro_one_day_book == 'yes')
                $checkerDay = __('Per Day', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
           
            _e($checkerDay, VRCALENDAR_PLUGIN_TEXT_DOMAIN); 
            ?>
            </div>
        </div>
        <div id="booking-form-fields">
            <div class="row">

                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="booking_checkin_date required"><?php _e('Check In', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></label>
                        <input type="text" class="form-control required" name="booking_checkin_date" id="booking_checkin_date" readonly value="<?php echo $data['check_in_date'] ?>" placeholder="<?php _e('Check In Date', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>" <?php echo $disabledtextbox; ?> >
                    </div>
                </div>

                <?php
                    if($hourly_booking == 'yes')
                    {
                        ?>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="booking_checkin_intime"><?php _e('Check In Time', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></label>
                                <!--input name="booking_checkin_intime" id="booking_checkin_intime" type="text" class="time ui-timepicker-input" autocomplete="off"-->
                                
                                <input type="hidden" name="date_of_only_hourly_booking" id="date_of_only_hourly_booking" value="<?php echo $_GET['bdate']; ?>" />
                                
                                <select name="checkintime" id="checkintime" class="form-control" onchange="timeChange(this.value)">
                                    <?php
                                    foreach ($arrayinTimes as $key => $timevalue)
                                    {
                                        //if($getDateOFSet)
                                        ?>
                                            <option value="<?php echo $timeKeysget[$key]; ?>"><?php echo $timeArray[$key]; ?></option>
                                        <?php
                                    }
                                ?>
                                  </select>
                            </div>
                        </div>
                        
                        <?php
                    }
                ?>

                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="booking_checkout_date required"><?php _e('Check Out', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></label>
                        <input type="text" class="form-control required" name="booking_checkout_date" id="booking_checkout_date" readonly value="<?php echo $data['check_out_date'] ?>" placeholder="<?php _e('Check Out Date', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>" <?php echo $disabledtextbox; ?> >
                    </div>
                </div>

                <?php
                    if($hourly_booking == 'yes')
                    {
                        ?>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="checkouttime"><?php _e('Check Out Time', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></label>
                                <!--input name="booking_checkin_intime" id="booking_checkin_intime" type="text" class="time ui-timepicker-input" autocomplete="off"-->
                                <select name="checkouttime" id="checkouttime" class="form-control">
                                    <?php
                                    foreach ($timeArray as  $key => $timevalue)
                                    {
                                        ?>
                                    <option value="<?php echo $timeKeysget[$key]; ?>" <?php echo $selected; ?> ><?php echo $timevalue; ?></option>
                                        <?php
                                    }
                                ?>
                                  </select>
                            </div>
                        </div>
                        <?php
                    }
                ?>

                <div class="col-sm-4">
                    <div class="form-group">
						<label for="booking_guests_count required"><?php _e('Guests', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></label>
                        <input type="number" min="1" max="<?php echo $data['calendar_data']->calendar_max_guest_no;?>" class="form-control required" name="booking_guests_count" id="booking_guests_count" value="<?php echo $data['total_guest_no'] ?>" placeholder="<?php _e('Guests', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>">
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="user_first_name"><?php _e('First Name', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></label>
                        <input type="text" class="form-control required" name="user_first_name" id="user_first_name" placeholder="<?php _e('First name', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>" />
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="user_last_name"><?php _e('Last Name', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></label>
                        <input type="text" class="form-control required" name="user_last_name" id="user_last_name" placeholder="<?php _e('Last name', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="user_email"><?php _e('Email', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></label>
                        <input type="email" class="form-control required " name="user_email" id="user_email" placeholder="<?php _e('Email', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>" />
                    </div>
                </div>
            
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="user_phone"><?php _e('Phone Number', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></label>
                        <input type="text" class="form-control required " name="user_phone" id="user_phone" placeholder="<?php _e('Phone Number', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>"  />
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="booking_note"><?php _e('Note To Host', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></label>
                        <textarea class="form-control" name="booking_note" id="booking_note" placeholder="<?php _e('Note To Host', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>"></textarea>
                    </div>
                </div>
            </div>
        </div>
		
        <div id="booking-form-charges">
			<table class="table table-hover numberofnights<?php echo $divclass; ?>">
				<tr>
					<td>
						<div>Plese choose minimum <?php echo $number_of_nights; ?> nights</div>
					</td>
				</tr>
			</table>
            <table class="table table-hover<?php echo $tableclass; ?>">
                <tr>				
                    <td>
					<span id ="table-basetext"><?php  echo $data['booking_price']['texthtml'];?></span>
                      
                    </td>
                    <td>
                       <span id ="table-baseprice"><?php  echo $data['booking_price']['pricehtml'];?></span>
					</td>
                </tr> 

				<?php $rowclass = 'rowhide';
				if($data['booking_price']['totaladditionalcharge'] > 0)
				  $rowclass = ''; ?>

                <tr id="table-row-additional" class="<?php echo $rowclass; ?>">
                    <td>
                        <?php _e('Extra Guest Fees', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                    </td>
                    <td>
                      <?php echo renderCurrency();?><span id="table-price-additional"><?php echo $data['booking_price']['totaladditionalcharge'];?></span>
                    </td>
                </tr> 
				<tr>
                    <td>
                        <?php _e('Cleaning Fee', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                    </td>
                    <td>
                        <?php echo renderCurrency();?><span id="table-cleaning-fee"><?php echo $data['booking_price']['cleaning_fee']; ?></span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php _e('Taxes', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                    </td>
                    <td>
                        <?php echo renderCurrency();?><span id="table-tax-amt"><?php echo $data['booking_price']['tax_amt']; ?></span>
                    </td>
                </tr>
                <tr>
				<!-- <tr>
                    <td>
                        <?php _e('Extra Fees', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                    </td>
                    <td>
                        <?php echo renderCurrency();?><span id="table-extra-fees"><?php echo $data['booking_price']['extra_fees']; ?></span>
                    </td>
                </tr> -->
				<tr>
                    <td>

                        <?php if($data['calendar_data']->calendar_custom_field_name != '') _e($data['calendar_data']->calendar_custom_field_name, VRCALENDAR_PLUGIN_TEXT_DOMAIN);
						else
						_e('Extra Fees', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                    </td>
                    <td>
                        <?php echo renderCurrency();?><span id="table-extra-fees"><?php echo $data['booking_price']['extra_fees']; ?></span>
                    </td>
                </tr>

                <tr>
                    <td><?php echo  _e('Total Reservation Amount', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></td>
                    <td><?php echo renderCurrency();?><span class="total_reservation_amount"><?php echo $data['booking_price']['total_reservation_amount']; ?></span></td>
                </tr>

                <tr>
                    <td>
                        <div class="default_price" <?php echo $default_price; ?>>
                            <?php echo  _e('Total', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                        </div>
                        <div class="updated_price" <?php echo $updated_price; ?>>
                            <?php
                            $deposit_percentage = $data['calendar_data']->deposit_percentage;
                            _e('Deposit Due Today ('.$deposit_percentage.'%)', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
                            ?>
                        </div>
                         <?php
                         
                        /*if($data['calendar_data']->deposit_enable == 'yes')
                        {
                            $deposit_percentage = $data['calendar_data']->deposit_percentage;
                        _e ('Total Booking Price Total Due Today ('.$deposit_percentage.'%)', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
                        }else{
                            _e('Total', VRCALENDAR_PLUGIN_TEXT_DOMAIN); 
                        }*/
                        ?>
                    </td>
                    <td>
                        <?php echo renderCurrency();?><span id="table-booking-price-with-taxes"><?php echo $data['booking_price']['booking_price_with_taxes']; ?></span>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <div class="updated_price" <?php echo $updated_price; ?>>
                            <div class="rest_of_dates_data">
                            <?php
                            if($data['booking_price']['remining_price_amount'] > 0)
                            {
                                _e('Balance of <b>'.renderCurrency().' '.$data['booking_price']['remining_price_amount'].'</b> will be due in <b>'.$data['booking_price']['rest_of_days'].'</b> days before arrival <b>'.$data['booking_price']['rest_of_pay_date'].'</b>', VRCALENDAR_PLUGIN_TEXT_DOMAIN);
                            }
                            ?>
                        </div>
                    </div>
                    </td>
                </tr>
            </table>
			
        </div>
		
        <div id="booking-form-action" class="booking-form-action<?php echo $tableclass; ?>">
            <div class="row">
                <div class="col-xs-12">	
                    <?php
                    $proonedaybook = 0;
                    if($pro_one_day_book == 'yes')
                    {
                        $proonedaybook = 1;
                    }
                    ?>
                    <input type="hidden" id="nightCounter" name="nightCounter" value="<?php echo $data['nightcounter']; ?>" />
                    <input type="hidden" name="valid_hourly_booking" id="valid_hourly_booking" value="0" />
                    <input type="hidden" id="nightlimit" name="nightlimit" value="<?php echo $data['nightlimit']; ?>" />
                    <input type="hidden" id="proonedaybook" name="proonedaybook" value="<?php echo $proonedaybook; ?>" />
                    <input type="hidden" name="cal_id" id="cal_id" value="<?php echo $data['calendar_data']->calendar_id ?>">
                    <input type="hidden" id="booked_dates" value='<?php echo json_encode($data['booked_dates']); ?>'>
                    <input type="hidden" id="vrc_pcmd" name="vrc_pcmd" value='saveBooking'>
                    <input type="submit" class="btn btn-danger btn-lg col-xs-12" value="<?php _e('Request to Book', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>" />
                </div>
            </div>
        </div>
    </form>
</div>

<?php
if($hourly_booking == 'yes')
{
?>
<script type="text/javascript">
    function timeChange(thisinvalue)
    {
        var timeArray = {'0' : '12:00am', '1' : '1:00am', '2' : '2:00am', '3' : '3:00am', '4' : '4:00am', '5' : '5:00am', '6' : '6:00am', '7' : '7:00am', '8' : '8:00am', '9' : '9:00am', '10' : '10:00am', '11' : '11:00am', '12' : '12:00pm', '13' : '1:00pm', '14' : '2:00pm','15' : '3:00pm', '16' : '4:00pm', '17' : '5:00pm', '18' : '6:00pm', '19' : '7:00pm', '20' : '8:00pm', '21' : '9:00pm', '22' : '10:00pm', '23' : '11:00pm'};
        var timeKeysget = {'0' : '00:00:00', '1' : '01:00:00', '2' : '02:00:00', '3' : '03:00:00', '4' : '04:00:00', '5' : '05:00:00', '6' : '06:00:00', '7' : '07:00:00', '8' : '08:00:00', '9' : '09:00:00', '10' : '10:00:00', '11' : '11:00:00', '12' : '12:00:00', '13' : '13:00:00', '14' : '14:00:00','15' : '15:00:00', '16' : '16:00:00', '17' : '17:00:00', '18' : '18:00:00', '19' : '19:00:00', '20' : '20:00:00', '21' : '21:00:00', '22' : '22:00:00', '23' : '23:00:00'};
        
        
        var cal_id = jQuery('#cal_id').val();
        var date_of_only_hourly_booking = jQuery('#date_of_only_hourly_booking').val();
        
        var data = {
            'action': 'get_updated_checkoudate',
            'cal_id': cal_id,
            'thisinvalue': thisinvalue,
            'date_of_only_hourly_booking': date_of_only_hourly_booking 
        };
        var optionValues = '';
        jQuery.post(vrc_data.ajax_url, data, function(response) {
           
           var obj = jQuery.parseJSON( response );
              jQuery.each(obj, function(key, value) {
                  
                  optionValues += '<option value='+timeKeysget[key]+'>'+timeArray[key]+'</option>';
                });
               jQuery('#checkouttime').html(optionValues);
        });
        
    }
    
    jQuery(document).ready(function(){
        var checkintime = jQuery('#checkintime').val();
        timeChange(checkintime);
    });
    //jQuery('#checkouttime')
</script>
<?php
}
?>
<script>
    //jQuery( "#vrc-booking-form" ).submit(function( event ) {
        //if(jQuery('#nightlimit').val() == 1)
        //{
            //var nightCounter = jQuery('#nightCounter').val();
            //alert('Sorry, the minimum booking is for '+nightCounter+' nights. Please book again for at least '+nightCounter+' nights.');
            //return false;
        //}
    //});
</script>
