<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
|--------------------------------------------------------------------------
| Variable Prices
|--------------------------------------------------------------------------
*/


/**
 * Meta box table header
 *
 * @access      public
 * @since       1.0
 * @return      void
 */

function edd_recurring_metabox_head( $download_id ) {
	?>
	<th><?php _e( 'Recurring', 'edd-recurring' ); ?></th>
	<th><?php _e( 'Period', 'edd-recurring' ); ?></th>
	<th><?php echo _x( 'Times', 'Referring to billing period', 'edd-recurring' ); ?></th>
	<th><?php echo _x( 'Signup Fee', 'Referring to subscription signup fee', 'edd-recurring' ); ?></th>
	<?php
}

add_action( 'edd_download_price_table_head', 'edd_recurring_metabox_head', 999 );


/**
 * Meta box is recurring yes/no field
 *
 * @access      public
 * @since       1.0
 * @return      void
 */

function edd_recurring_metabox_recurring( $download_id, $price_id, $args ) {

	$recurring = EDD_Recurring()->is_price_recurring( $download_id, $price_id );

	?>
	<td class="edd-recurring-enabled">
		<select name="edd_variable_prices[<?php echo $price_id; ?>][recurring]" id="edd_variable_prices[<?php echo $price_id; ?>][recurring]">
			<option value="no" <?php selected( $recurring, false ); ?>><?php echo esc_attr_e( 'No', 'edd-recurring' ); ?></option>
			<option value="yes" <?php selected( $recurring, true ); ?>><?php echo esc_attr_e( 'Yes', 'edd-recurring' ); ?></option>
		</select>
	</td>
	<?php
}

add_action( 'edd_download_price_table_row', 'edd_recurring_metabox_recurring', 999, 3 );


/**
 * Meta box recurring period field
 *
 * @access      public
 * @since       1.0
 * @return      void
 */

function edd_recurring_metabox_period( $download_id, $price_id, $args ) {

	$recurring = EDD_Recurring()->is_price_recurring( $download_id, $price_id );
	$periods   = EDD_Recurring()->periods();
	$period    = EDD_Recurring()->get_period( $price_id );

	$disabled = $recurring ? '' : 'disabled="disabled" ';

	?>
	<td class="edd-recurring-period">
		<select <?php echo $disabled; ?>name="edd_variable_prices[<?php echo $price_id; ?>][period]" id="edd_variable_prices[<?php echo $price_id; ?>][period]">
			<?php foreach ( $periods as $key => $value ) : ?>
				<option value="<?php echo $key; ?>" <?php selected( $period, $key ); ?>><?php echo esc_attr( $value ); ?></option>
			<?php endforeach; ?>
		</select>
	</td>
	<?php
}

add_action( 'edd_download_price_table_row', 'edd_recurring_metabox_period', 999, 3 );


/**
 * Meta box recurring times field
 *
 * @access      public
 * @since       1.0
 * @return      void
 */

function edd_recurring_metabox_times( $download_id, $price_id, $args ) {

	$recurring = EDD_Recurring()->is_price_recurring( $download_id, $price_id );
	$times     = EDD_Recurring()->get_times( $price_id );
	$period    = EDD_Recurring()->get_period( $price_id );

	$disabled = $recurring ? '' : 'disabled="disabled" ';

	?>
	<td class="times">
		<input <?php echo $disabled; ?>type="number" min="0" step="1" name="edd_variable_prices[<?php echo $price_id; ?>][times]" id="edd_variable_prices[<?php echo $price_id; ?>][times]" size="4" style="width: 40px" value="<?php echo $times; ?>" />
	</td>
	<?php
}

add_action( 'edd_download_price_table_row', 'edd_recurring_metabox_times', 999, 3 );


/**
 * Meta box recurring fee field
 *
 * @access      public
 * @since       1.1
 * @return      void
 */

function edd_recurring_metabox_signup_fee( $download_id, $price_id, $args ) {

	$recurring  = EDD_Recurring()->is_price_recurring( $download_id, $price_id );
	$signup_fee = EDD_Recurring()->get_signup_fee( $price_id, $download_id );

	$disabled = $recurring ? '' : 'disabled="disabled" ';

	?>
	<td class="signup_fee">
		<input <?php echo $disabled; ?>type="number" step="0.01" name="edd_variable_prices[<?php echo $price_id; ?>][signup_fee]" id="edd_variable_prices[<?php echo $price_id; ?>][signup_fee]" size="4" style="width: 60px" value="<?php echo $signup_fee; ?>" />
	</td>
	<?php
}

add_action( 'edd_download_price_table_row', 'edd_recurring_metabox_signup_fee', 999, 3 );


/**
 * Meta fields for EDD to save
 *
 * @access      public
 * @since       1.0
 * @return      array
 */

function edd_recurring_save_single( $fields ) {
	$fields[] = 'edd_period';
	$fields[] = 'edd_times';
	$fields[] = 'edd_recurring';
	$fields[] = 'edd_signup_fee';

	return $fields;
}

add_filter( 'edd_metabox_fields_save', 'edd_recurring_save_single' );


/**
 * Set colspan on submit row
 *
 * This is a little hacky, but it's the best way to adjust the colspan on the submit row to make sure it goes full width
 *
 * @access      private
 * @since       1.0
 * @return      void
 */

function edd_recurring_metabox_colspan() {
	echo '<script type="text/javascript">jQuery(function($){ $("#edd_price_fields td.submit").attr("colspan", 7)});</script>';
}

add_action( 'edd_meta_box_fields', 'edd_recurring_metabox_colspan', 20 );


/*
|--------------------------------------------------------------------------
| Single Price Options
|--------------------------------------------------------------------------
*/


/**
 * Meta box is recurring yes/no field
 *
 * @access      public
 * @since       1.0
 * @return      void
 */

function edd_recurring_metabox_single_recurring( $download_id ) {

	$recurring = EDD_Recurring()->is_recurring( $download_id );

	?>
	<label><?php _e( 'Recurring', 'edd-recurring' ); ?></label>
	<select name="edd_recurring" id="edd_recurring">
		<option value="no" <?php selected( $recurring, false ); ?>><?php echo esc_attr_e( 'No', 'edd-recurring' ); ?></option>
		<option value="yes" <?php selected( $recurring, true ); ?>><?php echo esc_attr_e( 'Yes', 'edd-recurring' ); ?></option>
	</select>
	<?php
}

add_action( 'edd_price_field', 'edd_recurring_metabox_single_recurring', 10 );


/**
 * Meta box recurring period field
 *
 * @access      public
 * @since       1.0
 * @return      void
 */

function edd_recurring_metabox_single_period( $download_id ) {

	$periods = EDD_Recurring()->periods();
	$period  = EDD_Recurring()->get_period_single( $download_id );
	?>
	<label><?php _e( 'Period', 'edd-recurring' ); ?></label>
	<select name="edd_period" id="edd_period">
		<?php foreach ( $periods as $key => $value ) : ?>
			<option value="<?php echo $key; ?>" <?php selected( $period, $key ); ?>><?php echo esc_attr( $value ); ?></option>
		<?php endforeach; ?>
	</select>
	<?php
}

add_action( 'edd_price_field', 'edd_recurring_metabox_single_period', 10 );


/**
 * Meta box recurring times field
 *
 * @access      public
 * @since       1.0
 * @return      void
 */

function edd_recurring_metabox_single_times( $download_id ) {

	$times = EDD_Recurring()->get_times_single( $download_id );
	?>

	<span class="times">
		<label><?php _e( 'Times', 'edd-recurring' ); ?></label>
		<input type="number" min="0" step="1" name="edd_times" id="edd_times" size="4" style="width: 40px" value="<?php echo $times; ?>" />
	</span>
	<?php
}

add_action( 'edd_price_field', 'edd_recurring_metabox_single_times', 20 );


/**
 * Meta box recurring signup fee field
 *
 * @access      public
 * @since       1.1
 * @return      void
 */

function edd_recurring_metabox_single_signup_fee( $download_id ) {

	$signup_fee = EDD_Recurring()->get_signup_fee_single( $download_id );
	?>

	<span class="signup_fee">
		<label><?php _e( 'Signup Fee', 'edd-recurring' ); ?></label>
		<input type="number" step="0.01" name="edd_signup_fee" id="edd_signup_fee" size="4" style="width: 60px" value="<?php echo $signup_fee; ?>" />
	</span>
	<?php
}

add_action( 'edd_price_field', 'edd_recurring_metabox_single_signup_fee', 20 );

/**
 * Display Subscription Payment Notice
 *
 * @description Adds a subscription payment indicator within the single payment view "Update Payment" metabox (top)
 * @since       2.4
 *
 * @param $payment_id
 *
 */
function edd_display_subscription_payment_meta( $payment_id ) {

	$is_sub = edd_get_payment_meta( $payment_id, '_edd_subscription_payment' );

	if ( $is_sub ) :
		$subs_db = new EDD_Subscriptions_DB;
		$subs    = $subs_db->get_subscriptions( array( 'parent_payment_id' => $payment_id, 'order' => 'ASC' ) );
?>
		<div id="edd-order-subscriptions" class="postbox">
			<h3 class="hndle">
				<span><?php _e( 'Subscriptions', 'edd-recurring' ); ?></span>
			</h3>
			<div class="inside">

				<?php foreach( $subs as $sub ) : ?>
					<?php $sub_url = admin_url( 'edit.php?post_type=download&page=edd-subscriptions&id=' . $sub->id ); ?>
					<p>
						<span class="label"><span class="dashicons dashicons-update"></span> <?php printf( __( 'Subscription ID: <a href="%s">#%d</a>', 'edd_recurring' ), $sub_url, $sub->id ); ?></span>&nbsp;
					</p>
					<?php $payments = $sub->get_child_payments(); ?>
					<?php if( $payments ) : ?>
						<p><strong><?php _e( 'Renewal Payments:', 'edd-recurring' ); ?></strong></p>
						<ul id="edd-recurring-sub-payments">
						<?php foreach( $payments as $payment ) : ?>
							<li>
								<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=download&page=edd-payment-history&view=view-order-details&id=' . $payment->ID ) ); ?>">
									<?php if( function_exists( 'edd_get_payment_number' ) ) : ?>
										<?php echo '#' . edd_get_payment_number( $payment->ID ); ?>
									<?php else : ?>
										<?php echo '#' . $payment->ID; ?>
									<?php endif; ?>&nbsp;&ndash;&nbsp;
								</a>
								<span><?php echo date_i18n( get_option( 'date_format' ), strtotime( $payment->post_date ) ); ?>&nbsp;&ndash;&nbsp;</span>
								<span><?php echo edd_payment_amount( $payment->ID ); ?></span>
							</li>
						<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				<?php endforeach; ?>
			</div>
		</div>
<?php
	endif;
}
add_action( 'edd_view_order_details_sidebar_before', 'edd_display_subscription_payment_meta', 10, 1 );

/**
 * List subscription (sub) payments of a particular parent payment
 *
 * The parent payment ID is the very first payment made. All payments made after for the profile are sub.
 *
 * @since  1.0
 * @return void
 */

function edd_recurring_display_parent_payment( $payment_id = 0 ) {

	$payment = new EDD_Payment( $payment_id );

	if( $payment->parent_payment ) :

		$parent_url = admin_url( 'edit.php?post_type=download&page=edd-payment-history&view=view-order-details&id=' . $payment->parent_payment );
?>
		<div id="edd-order-subscription-payments" class="postbox">
			<h3 class="hndle">
				<span><?php _e( 'Subscription', 'edd-recurring' ); ?></span>
			</h3>
			<div class="inside">
				<p><?php printf( __( 'Parent Payment: <a href="%s">%s</a>' ), $parent_url, $payment->number ); ?></p>
			</div><!-- /.inside -->
		</div><!-- /#edd-order-subscription-payments -->
<?php
	endif;
}
add_action( 'edd_view_order_details_sidebar_before', 'edd_recurring_display_parent_payment', 10 );

/**
 * Display Subscription transaction IDs for parent payments
 *
 * @since 2.4.4
 * @param $payment_id
 */
function edd_display_subscription_txn_ids( $payment_id ) {

	$is_sub = edd_get_payment_meta( $payment_id, '_edd_subscription_payment' );

	if ( $is_sub ) :
		$subs_db = new EDD_Subscriptions_DB;
		$subs    = $subs_db->get_subscriptions( array( 'parent_payment_id' => $payment_id ) );

		if( ! $subs ) {
			return;
		}
?>
		<div class="edd-subscription-tx-id edd-admin-box-inside">
			<?php foreach( $subs as $sub ) : ?>
				<?php if( ! $sub->get_transaction_id() ) { continue; } ?>		
				<p>
					<span class="label"><?php _e( 'Subscription TXN ID:', 'edd-recurring' ); ?></span>&nbsp;
					<span><?php echo apply_filters( 'edd_payment_details_transaction_id-' . $sub->gateway, $sub->get_transaction_id(), $payment_id ); ?></span>
				</p>
			<?php endforeach; ?>
		</div>
<?php
	endif;
}
add_action( 'edd_view_order_details_payment_meta_after', 'edd_display_subscription_txn_ids', 10, 1 );