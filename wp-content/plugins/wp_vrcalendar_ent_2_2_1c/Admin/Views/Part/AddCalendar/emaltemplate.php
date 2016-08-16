<?php
//1
$your_booking_is_removed_subject = __("Your booking is removed", VRCALENDAR_PLUGIN_TEXT_DOMAIN);
$your_booking_is_removed_body =  __("Hi %booking_user_fname% %booking_user_lname%,
<p>Your Booking is removed by the admin, following were your booking details for reference:</p>
<p>Booking ID: %booking_id%</p>
<p>Booking Date: %booking_created_on%</p>
<p>From: %booking_date_from%</p>
<p>To: %booking_date_to%</p>
<p>Guests: %booking_guests%</p>", VRCALENDAR_PLUGIN_TEXT_DOMAIN);;

//2
$your_booking_is_approved_subject = __("Your booking is approved", VRCALENDAR_PLUGIN_TEXT_DOMAIN);
$your_booking_is_approved_body = __("Hi %booking_user_fname% %booking_user_lname%,
<br/>
Your Booking is confirmed.", VRCALENDAR_PLUGIN_TEXT_DOMAIN);

//3.
$your_booking_is_approved_make_payment_subject = __("Your booking is approved", VRCALENDAR_PLUGIN_TEXT_DOMAIN);
$your_booking_is_approved_make_payment_body = __("Hi %booking_user_fname% %booking_user_lname%,
<br/>
Your Booking is confirmed, now you can make payment for your booking by following this link %booking_payment_link%.", VRCALENDAR_PLUGIN_TEXT_DOMAIN);

//4.
$conflict_aroused_while_synchronizing_calendar_subject = __("Conflict aroused while synchronizing calendar: %calendar_name%", VRCALENDAR_PLUGIN_TEXT_DOMAIN);
$conflict_aroused_while_synchronizing_calendar_body = __("Conflict aroused while synchronizing calendar: %calendar_name%<br>
 Conflicting Source: %booking_source%<br>
 Event Start Date: %booking_date_from%<br>
 Event End Date: %booking_date_to%<br>
 Event Summary: %booking_summary%<br>", VRCALENDAR_PLUGIN_TEXT_DOMAIN);;

//5.
$approval_needed_on_new_booking_on_subject = __("Your booking is received on %blogname% and is pending for approval", VRCALENDAR_PLUGIN_TEXT_DOMAIN);;
$approval_needed_on_new_booking_on_body = __("Hi %booking_user_fname% %booking_user_lname%,
<p>Your booking is received on %blogname% and is pending for approval</p>
<p>Booking Details:</p>
<p>Booking ID: %booking_id%</p>
<p>From: %booking_date_from%</p>
<p>To: %booking_date_to%</p>
<p>Guests: %booking_guests%</p>
<p>Summary: %booking_summary%</p>", VRCALENDAR_PLUGIN_TEXT_DOMAIN);;

//6
$your_booking_is_received_is_pending_for_approval_subject = __("Your booking is received on %blogname% and is pending for approval", VRCALENDAR_PLUGIN_TEXT_DOMAIN);
$your_booking_is_received_is_pending_for_approval_body = __("Hi %booking_user_fname% %booking_user_lname%,
<p>Your booking is received on %blogname% and is pending for approval</p>
<p>Booking Details:</p>
<p>Booking ID: %booking_id%</p>
<p>From: %booking_date_from%</p>
<p>To: %booking_date_to%</p>
<p>Guests: %booking_guests%</p>
<p>Summary: %booking_summary%</p>", VRCALENDAR_PLUGIN_TEXT_DOMAIN);
//7
$new_booking_subject = __("New booking on %blogname%", VRCALENDAR_PLUGIN_TEXT_DOMAIN);
$new_booking_body = __('Hi,
<p>New booking is received on %blogname%</p>
<p>Booking Details:</p>
<p>Booking ID: %booking_id%</p>
<p>From: %booking_date_from%</p>
<p>To: %booking_date_to%</p>
<p>Guests: %booking_guests%</p>
<p>Summary: %booking_summary%</p>
<p><a href="%booking_admin_link%">Click here to view booking</a></p>
', VRCALENDAR_PLUGIN_TEXT_DOMAIN);

//8
$your_booking_is_received_subject = __("Your booking is received on %blogname%", VRCALENDAR_PLUGIN_TEXT_DOMAIN);
$your_booking_is_received_body = __("Hi %booking_user_fname% %booking_user_lname%,
<p>Your booking is received on %blogname%</p>
<p>Booking Details:</p>
<p>Booking ID: %booking_id%</p>
<p>From: %booking_date_from%</p>
<p>To: %booking_date_to%</p>
<p>Guests: %booking_guests%</p>
<p>Summary: %booking_summary%</p>", VRCALENDAR_PLUGIN_TEXT_DOMAIN);

//9
$new_booking_payment_received_subject  = __("New booking payment received on %blogname%", VRCALENDAR_PLUGIN_TEXT_DOMAIN);
$new_booking_payment_received_body  = __('Hi,
<p>New booking payment is received on %blogname%</p>
<p>Booking Details:</p>
<p>Booking ID: %booking_id%</p>
<p>Transaction ID: %booking_payment_data_txn_id%</p>
<p>Amount: %booking_total_price%</p>
<p>From: %booking_date_from%</p>
<p>To: %booking_date_to%</p>
<p>Guests: %booking_guests%</p>
<p>Summary: %booking_summary%</p>
<p><a href="%booking_admin_link%">Click here to view booking</a></p>', VRCALENDAR_PLUGIN_TEXT_DOMAIN);

//10
$your_bookingpayment_is_received_subject = __("Your booking payment is received on %blogname%", VRCALENDAR_PLUGIN_TEXT_DOMAIN);
$your_bookingpayment_is_received_body = __("Hi %booking_user_fname% %booking_user_lname%,
<p>Your booking payment is received on %blogname%</p>
<p>Booking Details:</p>
<p>Booking ID: %booking_id%</p>
<p>Transaction ID: %booking_payment_data_txn_id%</p>
<p>Amount : %booking_total_price%</p>
<p>From: %booking_date_from%</p>
<p>To: %booking_date_to%</p>
<p>Guests: %booking_guests%</p>
<p>Summary: %booking_summary%</p>", VRCALENDAR_PLUGIN_TEXT_DOMAIN);

//11
$approval_needed_on_new_booking_subject = __("Approval needed on new booking on %blogname%", VRCALENDAR_PLUGIN_TEXT_DOMAIN);
$approval_needed_on_new_booking_body = __('Hi,
<p>New booking is received on %blogname% and is pending for approval</p>
<p>Booking Details:</p>
<p>Booking ID: %booking_id%</p>
<p>From: %booking_date_from%</p>
<p>To: %booking_date_to%</p>
<p>Guests: %booking_guests%</p>
<p>Summary: %booking_summary%</p>
<p><a href="%booking_admin_link%">Click here to view booking</a></p>', VRCALENDAR_PLUGIN_TEXT_DOMAIN);

//12.
$if_deposit_payment_enable_subject = __("Your booking is approved", VRCALENDAR_PLUGIN_TEXT_DOMAIN);
$if_deposit_payment_enable_body = __("Hi %booking_user_fname% %booking_user_lname%,
<br/>
Your Booking is confirmed, now you can make payment for your booking by following this link %booking_payment_link%.", VRCALENDAR_PLUGIN_TEXT_DOMAIN);

if(count($cdata->email_template) > 0)
{
    foreach ($cdata->email_template[$cdata->calendar_id] as $key => $value) {
        # code...
        if($key == 'your_booking_is_removed')
        {
            //1
            $your_booking_is_removed_subject = $value['subject'];
            $your_booking_is_removed_body = $value['body'];
        }
        if($key == 'your_booking_is_approved')
        {
            //2
            $your_booking_is_approved_subject = $value['subject'];
            $your_booking_is_approved_body = $value['body'];
        }
        if($key == 'your_booking_is_approved_make_payment')
        {
            //3
            $your_booking_is_approved_make_payment_subject = $value['subject'];
            $your_booking_is_approved_make_payment_body = $value['body'];
        }
        if($key == 'conflict_aroused_while_synchronizing_calendar')
        {
            //4
            $conflict_aroused_while_synchronizing_calendar_subject = $value['subject'];
            $conflict_aroused_while_synchronizing_calendar_body = $value['body'];
        }
        if($key == 'approval_needed_on_new_booking_on')
        {
            //5
            $approval_needed_on_new_booking_on_subject = $value['subject'];
            $approval_needed_on_new_booking_on_body = $value['body'];
        }
        if($key == 'your_booking_is_received_is_pending_for_approval')
        {
            //6
            $your_booking_is_received_is_pending_for_approval_subject = $value['subject'];
            $your_booking_is_received_is_pending_for_approval_body = $value['body'];
        }
        if($key == 'new_booking')
        {
            //7
            $new_booking_subject = $value['subject'];
            $new_booking_body = $value['body'];
        }
        if($key == 'your_booking_is_received')
        {
            //8
            $your_booking_is_received_subject = $value['subject'];
            $your_booking_is_received_body = $value['body'];
        }
        if($key == 'new_booking_payment_received')
        {
            //7
            $new_booking_payment_received_subject = $value['subject'];
            $new_booking_payment_received_body = $value['body'];
        }

        if($key == 'your_bookingpayment_is_received')
        {
            //7
            $your_bookingpayment_is_received_subject = $value['subject'];
            $your_bookingpayment_is_received_body = $value['body'];
        }

        if($key == 'approval_needed_on_new_booking')
        {
            //7
            $approval_needed_on_new_booking_subject = $value['subject'];
            $approval_needed_on_new_booking_body = $value['body'];
        }
        
        if($key == 'if_deposit_payment_enable')
        {
            //12
            $if_deposit_payment_enable_subject = $value['subject'];
            $if_deposit_payment_enable_body = $value['body'];
        }
    }
}

//$template_object(subject, body)


//$template_object(subject, body)
global $wpdb;
$server = $_SERVER['REQUEST_URI'];
$explode = explode('=',$server);
$calendar_id_email = $explode[2];
$this->table_name = $wpdb->prefix.'vrcalandar';
$sql = "select email_address_template, email_send_to from {$this->table_name} WHERE calendar_id =".$calendar_id_email;
$cals = $wpdb->get_results($sql);
foreach($cals as $cal) {
	$emailaddress = $cal->email_address_template;
	$email_send_to = $cal->email_send_to;
}

?>





<table class="form-table">
    <tbody>
        <tr>
            <td>
                <table class="form-table">
					<tr>
						<th>
							<?php _e('To :', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
						</th> 
						<td>
							<fieldset>
								<label title='<?php if(isset($email_send_to)){ echo $email_send_to; } ?>'>
									<input type="radio" name="email_send_to" value="admin" <?php if(isset($email_send_to) && $email_send_to == 'admin'){ echo 'checked="checked"';}else {echo 'checked="checked"';} ?> /><span><?php echo 'Admin'; ?></span><br>
									<input type="radio" name="email_send_to" value="both" <?php if(isset($email_send_to) && $email_send_to == 'both'){ echo 'checked="checked"';} ?> /><span><?php echo 'Both'; ?></span><br>
									<input type="radio" name="email_send_to" value="single" <?php if(isset($email_send_to) && $email_send_to == 'single'){ echo 'checked="checked"';} ?> /><span><?php echo 'Custom'; ?></span><br>
									<input type="text" name="email_address_template" value="<?php if(isset($emailaddress)){ echo $emailaddress; } ?>" class="large-text" placeholder="Email Address"/>
								</label> &nbsp;
								
							</fieldset>
						</td>
					</tr>
				</table>
			</td>
		</tr>
        <tr valign="top">
           <th><?php _e('1. Your booking is removed', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></th>
        </tr>
        <tr>
            <td>
                <table class="form-table">
                    <tr>
                        <th>
                            <?php _e('Subject :', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                        </th> 
                        <td>     
                            <input type="text" name="emailtemplate[your_booking_is_removed][subject]" value="<?php echo $your_booking_is_removed_subject; ?>" class="large-text" />
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <?php _e('Body :', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?> 
                        </th>
                        <td>     
                            <?php
                                $editor_id = "emailtemplate[your_booking_is_removed][body]";
                                $content = $your_booking_is_removed_body;
                                wp_editor($content,$editor_id,array());
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <th>
                            &nbsp;
                        </th>
                        <td>
                            <?php _e('%booking_user_fname% = Booking User First Name.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_user_lname% = Booking User Last Name.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                           <?php _e('%booking_id% = System Generated Booking ID.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_created_on% = Booking Creation Date.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_date_from% = Booking From Date.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_date_to% = Booking To Date', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_guests% = Booked No Of Guests.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                        </td>
                     </tr>
                </table>
            </td>
        </tr>


        <tr valign="top">
           <th><?php _e('2. Your booking is approved', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></th>
        </tr>
        <tr>
            <td>
                <table class="form-table">
                    <tr>
                        <th>
                            <?php _e('Subject :', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                        </th> 
                        <td>     
                            <input type="text" name="emailtemplate[your_booking_is_approved][subject]" value="<?php echo $your_booking_is_approved_subject; ?>" class="large-text" />
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <?php _e('Body :', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?> 
                        </th>
                        <td>   
                        <?php
                                $editor_id = "emailtemplate[your_booking_is_approved][body]";
                                $content = $your_booking_is_approved_body;
                                wp_editor($content,$editor_id,array());
                            ?>  

                        </td>
                    </tr>
                    <tr>
                        <th>
                            &nbsp;
                        </th>
                        <td>
                            <?php _e('%booking_user_fname% = Booking User First Name.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_user_lname% = Booking User Last Name.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                        </td>
                     </tr>
                </table>
            </td>
        </tr>


        <tr valign="top">
           <th><?php _e('3. Your booking is approved', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></th>
        </tr>
        <tr>
            <td>
                <table class="form-table">
                    <tr>
                        <th>
                            <?php _e('Subject :', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                        </th> 
                        <td>     
                            <input type="text" name="emailtemplate[your_booking_is_approved_make_payment][subject]" value="<?php echo $your_booking_is_approved_make_payment_subject; ?>" class="large-text" />
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <?php _e('Body :', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?> 
                        </th>
                        <td>     
                            <?php
                                $editor_id = "emailtemplate[your_booking_is_approved_make_payment][body]";
                                $content = $your_booking_is_approved_make_payment_body;
                                wp_editor($content,$editor_id,array());
                            ?>  

                        </td>
                    </tr>
                    <tr>
                        <th>
                            &nbsp;
                        </th>
                        <td>
                            <?php _e('%booking_user_fname% = Booking User First Name.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_user_lname% = Booking User Last Name.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_payment_link% = Booking link (if booking approved by admin, than system send payment link via email to user).', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                        </td>
                     </tr>
                </table>
            </td>
        </tr>


         <tr valign="top">
           <th><?php _e('4. Conflict aroused while synchronizing calendar', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></th>
        </tr>
        <tr>
            <td>
                <table class="form-table">
                    <tr>
                        <th>
                            <?php _e('Subject :', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                        </th> 
                        <td>     
                            <input type="text" name="emailtemplate[conflict_aroused_while_synchronizing_calendar][subject]" value="<?php echo $conflict_aroused_while_synchronizing_calendar_subject; ?>" class="large-text" />
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <?php _e('Body :', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?> 
                        </th>
                        <td>     
                             <?php
                                $editor_id = "emailtemplate[conflict_aroused_while_synchronizing_calendar][body]";
                                $content = $conflict_aroused_while_synchronizing_calendar_body;
                                wp_editor($content,$editor_id,array());
                            ?>  

                        </td>
                    </tr>
                    <tr>
                        <th>
                            &nbsp;
                        </th>
                        <td>
                            <?php _e('%calendar_name% = Booking User First Name.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_source% = Booking source type (website / Calendar Links).', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_date_from% = Booking From Date.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_date_to% = Booking To Date.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                           <?php _e('%booking_summary% = Booking Summary (Note To Host).', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                        </td>
                     </tr>
                </table>
            </td>
        </tr>


        <tr valign="top">
           <th><?php _e('5. Approval needed on new booking on', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></th>
        </tr>
        <tr>
            <td>
                <table class="form-table">
                    <tr>
                        <th>
                            <?php _e('Subject :', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                        </th> 
                        <td>   
                            <input type="text" name="emailtemplate[approval_needed_on_new_booking_on][subject]" value="<?php echo $approval_needed_on_new_booking_on_subject; ?>" class="large-text" />  
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <?php _e('Body :', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?> 
                        </th>
                        <td>   
                        <?php
                                $editor_id = "emailtemplate[approval_needed_on_new_booking_on][body]";
                                $content = $approval_needed_on_new_booking_on_body;
                                wp_editor($content,$editor_id,array());
                            ?>    

                        </td>
                    </tr>
                    <tr>
                        <th>
                            &nbsp;
                        </th>
                        <td>
                            
                            <?php _e('%blogname% = Booking received User Name.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_user_fname% = Booking User First Name.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_user_lname% = Booking User Last Name.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                           <?php _e('%booking_id% = System Generated Booking ID.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_date_from% = Booking From Date.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_date_to% = Booking To Date.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_guests% = Number Of Guests.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_summary% = Booking Summary Note (Note To Host).', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                        </td>
                     </tr>
                </table>
            </td>
        </tr>


        <tr valign="top">
           <th><?php _e('6. Your booking is received on " " and is pending for approval', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></th>
        </tr>
        <tr>
            <td>
                <table class="form-table">
                    <tr>
                        <th>
                            <?php _e('Subject :', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                        </th> 
                        <td>     
                            <input type="text" name="emailtemplate[your_booking_is_received_is_pending_for_approval][subject]" value="<?php echo $your_booking_is_received_is_pending_for_approval_subject; ?>" class="large-text" />
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <?php _e('Body :', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?> 
                        </th>
                        <td>    
                        <?php
                                $editor_id = "emailtemplate[your_booking_is_received_is_pending_for_approval][body]";
                                $content = $your_booking_is_received_is_pending_for_approval_body;
                                wp_editor($content,$editor_id,array());
                            ?>     

                        </td>
                    </tr>
                    <tr>
                        <th>
                            &nbsp;
                        </th>
                        <td>
                            
                            
                            <?php _e('%booking_user_fname% = Booking User First Name.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_user_lname% = Booking User Last Name.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%blogname% = Booking received User Name.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                           <?php _e('%booking_id% = System Generated Booking ID.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_date_from% = Booking From Date.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_date_to% = Booking To Date.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_guests% = Number Of Guests.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_summary% = Booking Summary Note (Note To Host).', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                        </td>
                     </tr>
                </table>
            </td>
        </tr>


        <tr valign="top">
           <th><?php _e('7. New booking', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></th>
        </tr>
        <tr>
            <td>
                <table class="form-table">
                    <tr>
                        <th>
                            <?php _e('Subject :', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                        </th> 
                        <td>     
                            <input type="text" name="emailtemplate[new_booking][subject]" value="<?php echo $new_booking_subject; ?>" class="large-text" />
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <?php _e('Body :', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?> 
                        </th>
                        <td>    
                         <?php
                                $editor_id = "emailtemplate[new_booking][body]";
                                $content = $new_booking_body;
                                wp_editor($content,$editor_id,array());
                            ?>     
                        </td>
                    </tr>
                    <tr>
                        <th>
                            &nbsp;
                        </th>
                        <td>
                            <?php _e('%blogname% = Booking received User Name.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                           <?php _e('%booking_id% = System Generated Booking ID.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_date_from% = Booking From Date.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_date_to% = Booking To Date.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                             <?php _e('%booking_guests% = Number Of Guests.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_summary% = Booking Summary Note (Note To Host).', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_admin_link% = Manage Booking page link.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                        </td>
                     </tr>
                </table>
            </td>
        </tr>


        <tr valign="top">
           <th><?php _e('8. Your booking is received', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></th>
        </tr>
        <tr>
            <td>
                <table class="form-table">
                    <tr>
                        <th>
                           <?php _e('Subject :', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                        </th> 
                        <td>     
                            <input type="text" name="emailtemplate[your_booking_is_received][subject]" value="<?php echo $your_booking_is_received_subject; ?>" class="large-text" />
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <?php _e('Body :', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?> 
                        </th>
                        <td>    
                          <?php
                                $editor_id = "emailtemplate[your_booking_is_received][body]";
                                $content = $your_booking_is_received_body;
                                wp_editor($content,$editor_id,array());
                            ?>      

                        </td>
                    </tr>
                    <tr>
                        <th>
                            &nbsp;
                        </th>
                        <td>
                            <?php _e('%booking_user_fname% = Booking User First Name.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_user_lname% = Booking User Last Name.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%blogname% = Booking received User Name.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                           <?php _e('%booking_id% = System Generated Booking ID.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_date_from% = Booking From Date.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_date_to% = Booking To Date.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                             <?php _e('%booking_guests% = Number Of Guests.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_summary% = Booking Summary Note (Note To Host).', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                        </td>
                     </tr>
                </table>
            </td>
        </tr>


        <tr valign="top">
           <th><?php _e('9. New booking payment received', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></th>
        </tr>
        <tr>
            <td>
                <table class="form-table">
                    <tr>
                        <th>
                            <?php _e('Subject :', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                        </th> 
                        <td>     
                            <input type="text" name="emailtemplate[new_booking_payment_received][subject]" value="<?php echo $new_booking_payment_received_subject; ?>" class="large-text" />
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <?php _e('Body :', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?> 
                        </th>
                        <td>     
                             <?php
                                $editor_id = "emailtemplate[new_booking_payment_received][body]";
                                $content = $new_booking_payment_received_body;
                                wp_editor($content,$editor_id,array());
                            ?> 

                        </td>
                    </tr>
                    <tr>
                        <th>
                            &nbsp;
                        </th>
                        <td>
                            <?php _e('%blogname% = Booking received User Name.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                           <?php _e('%booking_id% = System Generated Booking ID.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_payment_data_txn_id% = Booking Payment Transaction ID.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_total_price% = Booking Total Amount.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_date_from% = Booking From Date.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_date_to% = Booking To Date.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_guests% = Number Of Guests.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_summary% = Booking Summary Note (Note To Host).', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_admin_link% = Manage Booking page link.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                        </td>
                     </tr>
                </table>
            </td>
        </tr>


        <tr valign="top">
           <th><?php _e('10. Your booking payment is received', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></th>
        </tr>
        <tr>
            <td>
                <table class="form-table">
                    <tr>
                        <th>
                            <?php _e('Subject :', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                        </th> 
                        <td>     
                            <input type="text" name="emailtemplate[your_bookingpayment_is_received][subject]" value="<?php echo $your_bookingpayment_is_received_subject; ?>" class="large-text" />
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <?php _e('Body :', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?> 
                        </th>
                        <td>   
                          <?php
                                $editor_id = "emailtemplate[your_bookingpayment_is_received][body]";
                                $content = $your_bookingpayment_is_received_body;
                                wp_editor($content,$editor_id,array());
                            ?>   

                        </td>
                    </tr>
                    <tr>
                        <th>&nbsp;</th>
                        <td>
                            <?php _e('%booking_user_fname% =  Booking User First name.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_user_lname% =  Booking User Last name.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%blogname% = Booking received User Name.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                           <?php _e('%booking_id% = System Generated Booking ID.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_payment_data_txn_id% = Booking Payment Transaction ID.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_total_price% = Booking Total Amount.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_date_from% = Booking From Date.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_date_to% = Booking To Date.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_guests% = Number of guests.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_summary% = Booking Summary Note (Note To Host).', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr valign="top">
           <th><?php _e('11. Your booking is received on " " and is pending for approval', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></th>
        </tr>
        <tr>
            <td>
                <table class="form-table">
                    <tr>
                        <th>
                            <?php _e('Subject :', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                        </th> 
                        <td>     
                            <input type="text" name="emailtemplate[approval_needed_on_new_booking][subject]" value="<?php echo $approval_needed_on_new_booking_subject; ?>" class="large-text" />
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <?php _e('Body :', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?> 
                        </th>
                        <td>     
                              <?php
                                $editor_id = "emailtemplate[approval_needed_on_new_booking][body]";
                                $content = $approval_needed_on_new_booking_body;
                                wp_editor($content,$editor_id,array());
                            ?>   

                        </td>
                    </tr>
                    <tr>
                        <th>
                            &nbsp;
                        </th>
                        <td>     
                             <?php _e('%blogname% = Booking received User Name.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                             <br />
                            <?php _e('%booking_id% = System Generated Booking ID.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                             <br />
                             <?php _e('%booking_date_from% = Booking From Date.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                             <br />
                             <?php _e('%booking_date_to% = Booking To Date.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                             <br />
                             <?php _e('%booking_guests% = Number Of Guests.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                             <br />
                             <?php _e('%booking_summary% = Booking Summary Note (Note To Host).', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                             <br />
                             <?php _e('%booking_admin_link% = Manage Booking page link.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr valign="top">
           <th><?php _e('12. If Deposit mode enable', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?></th>
        </tr>
        <tr>
            <td>
                <table class="form-table">
                    <tr>
                        <th>
                            <?php _e('Subject :', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                        </th> 
                        <td>     
                            <input type="text" name="emailtemplate[if_deposit_payment_enable][subject]" value="<?php echo $if_deposit_payment_enable_subject; ?>" class="large-text" />
                        </td>
                    </tr>
                    <tr>
                        <th>
                            <?php _e('Body :', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?> 
                        </th>
                        <td>     
                              <?php
                                $editor_id = "emailtemplate[if_deposit_payment_enable][body]";
                                $content = $if_deposit_payment_enable_body;
                                wp_editor($content,$editor_id,array());
                            ?>   

                        </td>
                    </tr>
                    <tr>
                        <th>
                            &nbsp;
                        </th>
                        <td>     
                             <?php _e('%booking_user_fname% = Booking User First Name.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_user_lname% = Booking User Last Name.', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                            <br />
                            <?php _e('%booking_payment_link% = Booking link (if booking approved by admin, than system send payment link via email to user).', VRCALENDAR_PLUGIN_TEXT_DOMAIN); ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </tbody>
</table>
