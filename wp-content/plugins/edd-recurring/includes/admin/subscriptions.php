<?php
/**
 * Render the Subscriptions table
 *
 * @access      public
 * @since       2.4
 * @return      void
 */
function edd_subscriptions_page() {

	if ( ! empty( $_GET['id'] ) ) {
		edd_recurring_subscription_details();

		return;
	}

	?>
	<div class="wrap">

		<h2><?php _e( 'Subscriptions', 'edd-recurring' ); ?></h2>
		<?php
		$subscribers_table = new EDD_Subscription_Reports_Table();
		$subscribers_table->prepare_items();
		?>

		<form id="subscribers-filter" method="get">

			<input type="hidden" name="post_type" value="download" />
			<input type="hidden" name="page" value="edd-subscriptions" />
			<?php $subscribers_table->views() ?>
			<?php $subscribers_table->display() ?>

		</form>
	</div>
	<?php
}

/**
 * Recurring Subscription Details
 * @description Outputs the subscriber details
 * @since       2.4
 *
 */
function edd_recurring_subscription_details() {

	$render = true;

	if ( ! current_user_can( 'view_shop_reports' ) ) {
		edd_set_error( 'edd-no-access', __( 'You are not permitted to view this data.', 'edd-recurring' ) );
		$render = false;
	}

	if ( ! isset( $_GET['id'] ) || ! is_numeric( $_GET['id'] ) ) {
		edd_set_error( 'edd-invalid_subscription', __( 'Invalid subscription ID Provided.', 'edd-recurring' ) );
		$render = false;
	}

	$sub_id  = (int) $_GET['id'];
	$sub     = new EDD_Subscription( $sub_id );

	if ( empty( $sub ) ) {
		edd_set_error( 'edd-invalid_subscription', __( 'Invalid subscription ID Provided.', 'edd-recurring' ) );
		$render = false;
	}

	?>
	<div class="wrap">
		<h2><?php _e( 'Subscription Details', 'edd-recurring' ); ?></h2>
		<?php if ( edd_get_errors() ) : ?>
			<div class="error settings-error">
				<?php edd_print_errors(); ?>
			</div>
		<?php endif; ?>

		<?php if ( $sub && $render ) : ?>

			<div id="edd-item-card-wrapper">

				<?php do_action( 'edd_subscription_card_top', $sub ); ?>

				<div class="info-wrapper item-section">

					<form id="edit-item-info" method="post" action="<?php echo admin_url( 'edit.php?post_type=download&page=edd-subscriptions&id=' . $sub->id ); ?>">

						<div class="item-info">

							<table class="widefat striped">
								<tbody>
									<tr>
										<td class="row-title">
											<label for="tablecell"><?php _e( 'Billing Cycle:', 'edd-recurring' ); ?></label>
										</td>
										<td>
											<?php
											$frequency = EDD_Recurring()->get_pretty_subscription_frequency( $sub->period );
											$billing   = edd_currency_filter( edd_format_amount( $sub->recurring_amount ), edd_get_payment_currency_code( $sub->parent_payment_id ) ) . ' / ' . $frequency;
											$initial   = edd_currency_filter( edd_format_amount( $sub->initial_amount ), edd_get_payment_currency_code( $sub->parent_payment_id ) );
											printf( _x( '%s then %s', 'Inital subscription amount then billing cycle and amount', 'edd-recurring' ), $initial, $billing );
											?>
										</td>
									</tr>
									<tr>
										<td class="row-title">
											<label for="tablecell"><?php _e( 'Times Billed:', 'edd-recurring' ); ?></label>
										</td>
										<td><?php echo $sub->get_total_payments() . ' / ' . ( ( $sub->bill_times == 0 ) ? 'Until Cancelled' : $sub->bill_times ); ?></td>
									</tr>
									<tr>
										<td class="row-title">
											<label for="tablecell"><?php _e( 'Customer:', 'edd-recurring' ); ?></label>
										</td>
										<td>
											<?php $subscriber = new EDD_Recurring_Subscriber( $sub->customer_id ); ?>
											<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=download&page=edd-customers&view=overview&id=' . $subscriber->id ) ); ?>"><?php echo $subscriber->name; ?></a>
										</td>
									</tr>
									<tr>
										<td class="row-title">
											<label for="tablecell"><?php _e( 'Initial Purchase ID:', 'edd-recurring' ); ?></label>
										</td>
										<td><?php echo '<a href="' . add_query_arg( 'id', $sub->parent_payment_id, admin_url( 'edit.php?post_type=download&page=edd-payment-history&view=view-order-details' ) ) . '">' . $sub->parent_payment_id . '</a>'; ?></td>
									</tr>
									<tr>
										<td class="row-title">
											<label for="tablecell"><?php _e( 'Product:', 'edd-recurring' ); ?></label>
										</td>
										<td>
											<a href="<?php echo esc_url( add_query_arg( array(
													'post'   => $sub->product_id,
													'action' => 'edit'
												), admin_url( 'post.php' ) ) ); ?>"><?php echo get_the_title( $sub->product_id ); ?></a>
										</td>
									</tr>
									<tr>
										<td class="row-title">
											<label for="tablecell"><?php _e( 'Payment Method:', 'edd-recurring' ); ?></label>
										</td>
										<td><?php echo edd_get_gateway_admin_label( edd_get_payment_gateway( $sub->parent_payment_id ) ); ?></td>
									</tr>
									<tr>
										<td class="row-title">
											<label for="tablecell"><?php _e( 'Profile ID:', 'edd-recurring' ); ?></label>
										</td>
										<td>
											<span class="edd-sub-profile-id">
												<?php echo apply_filters( 'edd_subscription_profile_link_' . $sub->gateway, $sub->profile_id, $sub ); ?>
											</span>
											<input type="text" name="profile_id" class="hidden edd-sub-profile-id" value="<?php echo esc_attr( $sub->profile_id ); ?>" />
											<span>&nbsp;&ndash;&nbsp;</span>
											<a href="#" class="edd-edit-sub-profile-id"><?php _e( 'Edit', 'edd-recurring' ); ?></a>
										</td>
									</tr>
									<tr>
										<td class="row-title">
											<label for="tablecell"><?php _e( 'Transaction ID:', 'edd-recurring' ); ?></label>
										</td>
										<td>
											<span class="edd-sub-transaction-id"><?php echo $sub->get_transaction_id(); ?></span>
											<input type="text" name="transaction_id" class="hidden edd-sub-transaction-id" value="<?php echo esc_attr( $sub->get_transaction_id() ); ?>" />
											<span>&nbsp;&ndash;&nbsp;</span>
											<a href="#" class="edd-edit-sub-transaction-id"><?php _e( 'Edit', 'edd-recurring' ); ?></a>
										</td>
									</tr>
									<tr>
										<td class="row-title">
											<label for="tablecell"><?php _e( 'Date Created:', 'edd-recurring' ); ?></label>
										</td>
										<td><?php echo date_i18n( get_option( 'date_format' ), strtotime( $sub->created, current_time( 'timestamp' ) ) ); ?></td>
									</tr>
									<tr>
										<td class="row-title">
											<label for="tablecell"><?php _e( 'Expiration Date:', 'edd-recurring' ); ?></label>
										</td>
										<td>
											<span class="edd-sub-expiration"><?php echo date_i18n( get_option( 'date_format' ), strtotime( $sub->expiration, current_time( 'timestamp' ) ) ); ?></span>
											<input type="text" name="expiration" class="edd_datepicker hidden edd-sub-expiration" value="<?php echo esc_attr( $sub->expiration ); ?>" />
											<span>&nbsp;&ndash;&nbsp;</span>
											<a href="#" class="edd-edit-sub-expiration"><?php _e( 'Edit', 'edd-recurring' ); ?></a>
										</td>
									</tr>
									<tr>
										<td class="row-title">
											<label for="tablecell"><?php _e( 'Subscription Status:', 'edd-recurring' ); ?></label>
										</td>
										<td>
											<select name="status">
												<option value="pending"<?php selected( 'pending', $sub->status ); ?>><?php _e( 'Pending', 'edd-recurring' ); ?></option>
												<option value="active"<?php selected( 'active', $sub->status ); ?>><?php _e( 'Active', 'edd-recurring' ); ?></option>
												<option value="cancelled"<?php selected( 'cancelled', $sub->status ); ?>><?php _e( 'Cancelled', 'edd-recurring' ); ?></option>
												<option value="expired"<?php selected( 'expired', $sub->status ); ?>><?php _e( 'Expired', 'edd-recurring' ); ?></option>
												<option value="failing"<?php selected( 'failing', $sub->status ); ?>><?php _e( 'Failing', 'edd-recurring' ); ?></option>
												<option value="completed"<?php selected( 'completed', $sub->status ); ?>><?php _e( 'Completed', 'edd-recurring' ); ?></option>
											</select>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
						<div id="edd-sub-notices">
							<div class="notice notice-info inline hidden" id="edd-sub-expiration-update-notice"><p><?php _e( 'Changing the expiration date will not affect when renewal payments are processed.', 'edd-recurring' ); ?></p></div>
							<div class="notice notice-warning inline hidden" id="edd-sub-profile-id-update-notice"><p><?php _e( 'Changing the profile ID can result in renewals not being processed. Do this with caution.', 'edd-recurring' ); ?></p></div>
						</div>
						<div id="item-edit-actions" class="edit-item" style="float:right; margin: 10px 0 0; display: block;">
							<?php wp_nonce_field( 'edd-recurring-update', 'edd-recurring-update-nonce', false, true ); ?>
							<input type="submit" name="edd_update_subscription" id="edd_update_subscription" class="button button-primary" value="<?php _e( 'Update Subscription', 'edd-recurring' ); ?>"/>
							<input type="hidden" name="sub_id" value="<?php echo absint( $sub->id ); ?>" />
							<?php if( $sub->can_cancel() ) : ?>
								<a class="button button-primary" href="<?php echo $sub->get_cancel_url(); ?>" ><?php _e( 'Cancel Subscription', 'edd-recurring' ); ?></a>
							<?php endif; ?>
							&nbsp;<input type="submit" name="edd_delete_subscription" class="edd-delete-subscription button" value="<?php _e( 'Delete Subscription', 'edd-recurring' ); ?>"/>
						</div>

					</form>
				</div>

				<?php do_action( 'edd_subscription_before_stats', $sub ); ?>

				<div id="item-stats-wrapper" class="item-section" style="margin:25px 0; font-size: 20px;">
					<ul>
						<li>
							<span class="dashicons dashicons-chart-area"></span>
							<?php echo edd_currency_filter( edd_format_amount( $sub->get_lifetime_value() ), edd_get_payment_currency_code( $sub->parent_payment_id ) ); ?>
						</li>
						<?php do_action( 'edd_subscription_stats_list', $sub ); ?>
					</ul>
				</div>

				<?php do_action( 'edd_subscription_before_tables_wrapper', $sub ); ?>

				<div id="item-tables-wrapper" class="item-section">

					<?php do_action( 'edd_subscription_before_tables', $sub ); ?>

					<h3><?php _e( 'Renewal Payments:', 'edd-recurring' ); ?></h3>
					<?php $payments = $sub->get_child_payments(); ?>
					<table class="wp-list-table widefat striped payments">
						<thead>
						<tr>
							<th><?php _e( 'ID', 'edd-recurring' ); ?></th>
							<th><?php _e( 'Amount', 'edd-recurring' ); ?></th>
							<th><?php _e( 'Date', 'edd-recurring' ); ?></th>
							<th><?php _e( 'Status', 'edd-recurring' ); ?></th>
							<th><?php _e( 'Actions', 'edd-recurring' ); ?></th>
						</tr>
						</thead>
						<tbody>
						<?php if ( ! empty( $payments ) ) : ?>
							<?php foreach ( $payments as $payment ) : ?>
								<tr>
									<td><?php echo $payment->ID; ?></td>
									<td><?php echo edd_payment_amount( $payment->ID ); ?></td>
									<td><?php echo date_i18n( get_option( 'date_format' ), strtotime( $payment->post_date ) ); ?></td>
									<td><?php echo edd_get_payment_status( $payment, true ); ?></td>
									<td>
										<a title="<?php _e( 'View Details for Payment', 'edd-recurring' );
										echo ' ' . $payment->ID; ?>" href="<?php echo admin_url( 'edit.php?post_type=download&page=edd-payment-history&view=view-order-details&id=' . $payment->ID ); ?>">
											<?php _e( 'View Details', 'edd-recurring' ); ?>
										</a>
										<?php do_action( 'edd_subscription_payments_actions', $sub, $payment ); ?>
									</td>
								</tr>
							<?php endforeach; ?>
						<?php else: ?>
							<tr>
								<td colspan="5"><?php _e( 'No Payments Found', 'edd-recurring' ); ?></td>
							</tr>
						<?php endif; ?>
						</tbody>
						<tfoot>
							<tr class="alternate">
								<td colspan="5">
									<form id="edd-sub-add-renewal" method="POST">
										<p><?php _e( 'Use this form to manually record a renewal payment.', 'edd-recurring' ); ?></p>
										<p>
											<label>
												<span style="display: inline-block; width: 150px; padding: 3px;"><?php _e( 'Amount:', 'edd-recurring' ); ?></span>
												<input type="text" class="regular-text" style="width: 100px; padding: 3px;" name="amount" value="" placeholder="0.00"/>
											</label>
										</p>
										<p>
											<label>
												<span style="display: inline-block; width: 150px; padding: 3px;"><?php _e( 'Transaction ID:', 'edd-recurring' ); ?></span>
												<input type="text" class="regular-text" style="width: 100px; padding: 3px;" name="txn_id" value="" placeholder=""/>
											</label>
										</p>
										<?php wp_nonce_field( 'edd-recurring-add-renewal-payment', '_wpnonce', false, true ); ?>
										<input type="hidden" name="sub_id" value="<?php echo absint( $sub->id ); ?>" />
										<input type="hidden" name="edd_action" value="add_renewal_payment" />
										<input type="submit" class="button alignright" value="<?php esc_attr_e( 'Add Renewal', 'edd-recurring' ); ?>"/>
									</form>
								</td>
							</tr>
						</tfoot>
					</table>

					<?php do_action( 'edd_subscription_after_tables', $sub ); ?>

				</div>

				<?php do_action( 'edd_subscription_card_bottom', $sub ); ?>
			</div>

		<?php endif; ?>

	</div>
	<?php
}

/**
 * Handles subscription update
 *
 * @access      public
 * @since       2.4
 * @return      void
 */
function edd_recurring_process_subscription_update() {

	if( empty( $_POST['sub_id'] ) ) {
		return;
	}

	if( empty( $_POST['edd_update_subscription'] ) ) {
		return;
	}

	if( ! current_user_can( 'edit_shop_payments') ) {
		return;
	}

	if( ! wp_verify_nonce( $_POST['edd-recurring-update-nonce'], 'edd-recurring-update' ) ) {
		wp_die( __( 'Nonce verification failed', 'edd-recurring' ), __( 'Error', 'edd-recurring' ), array( 'response' => 403 ) );
	}

	$expiration      = date( 'Y-m-d 23:59:59', strtotime( $_POST['expiration'] ) );
	$profile_id      = sanitize_text_field( $_POST['profile_id'] );
	$transaction_id  = sanitize_text_field( $_POST['transaction_id'] );
	$subscription    = new EDD_Subscription( absint( $_POST['sub_id'] ) );
	$subscription->update( array(
		'status'         => sanitize_text_field( $_POST['status'] ),
		'expiration'     => $expiration,
		'profile_id'     => $profile_id,
		'transaction_id' => $transaction_id,
	) );

	$status = sanitize_text_field( $_POST['status'] );

	switch( $status ) {

		case 'cancelled' :

			$subscription->cancel();
			break;

		case 'expired' :

			$subscription->expire();
			break;

		case 'completed' :

			$subscription->complete();
			break;

	}

	wp_redirect( admin_url( 'edit.php?post_type=download&page=edd-subscriptions&edd-message=updated&id=' . $subscription->id ) );
	exit;

}
add_action( 'admin_init', 'edd_recurring_process_subscription_update', 1 );

/**
 * Handles subscription cancellation
 *
 * @access      public
 * @since       2.4
 * @return      void
 */
function edd_recurring_process_subscription_cancel() {

	if( empty( $_POST['sub_id'] ) ) {
		return;
	}

	if( empty( $_POST['edd_cancel_subscription'] ) ) {
		return;
	}

	if( ! current_user_can( 'edit_shop_payments') ) {
		return;
	}

	if( ! wp_verify_nonce( $_POST['_wpnonce'], 'edd-recurring-cancel' ) ) {
		wp_die( __( 'Nonce verification failed', 'edd-recurring' ), __( 'Error', 'edd-recurring' ), array( 'response' => 403 ) );
	}

	$subscription    = new EDD_Subscription( absint( $_POST['sub_id'] ) );
	$subscription->cancel();

	wp_redirect( admin_url( 'edit.php?post_type=download&page=edd-subscriptions&edd-message=cancelled&id=' . $subscription->id ) );
	exit;

}
add_action( 'admin_init', 'edd_recurring_process_subscription_cancel', 1 );


/**
 * Handles adding a manual renewal payment
 *
 * @access      public
 * @since       2.4
 * @return      void
 */
function edd_recurring_process_add_renewal_payment() {

	if( empty( $_POST['sub_id'] ) ) {
		return;
	}

	if( ! current_user_can( 'edit_shop_payments') ) {
		return;
	}

	if( ! wp_verify_nonce( $_POST['_wpnonce'], 'edd-recurring-add-renewal-payment' ) ) {
		wp_die( __( 'Nonce verification failed', 'edd-recurring' ), __( 'Error', 'edd-recurring' ), array( 'response' => 403 ) );
	}

	$amount  = isset( $_POST['amount'] ) ? edd_sanitize_amount( $_POST['amount'] ) : '0.00';
	$txn_id  = isset( $_POST['txn_id'] ) ? sanitize_text_field( $_POST['txn_id'] ) : md5( strtotime( 'NOW' ) );
	$sub     = new EDD_Subscription( absint( $_POST['sub_id'] ) );
	$payment = $sub->add_payment( array(
		'amount'         => $amount,
		'transaction_id' => $txn_id
	) );

	if( $payment ) {
		$message = 'renewal-added';
	} else {
		$message = 'renewal-not-added';
	}

	wp_redirect( admin_url( 'edit.php?post_type=download&page=edd-subscriptions&edd-message=' . $message . '&id=' . $sub->id ) );
	exit;

}
add_action( 'edd_add_renewal_payment', 'edd_recurring_process_add_renewal_payment', 1 );

/**
 * Handles subscription deletion
 *
 * @access      public
 * @since       2.4
 * @return      void
 */
function edd_recurring_process_subscription_deletion() {

	if( empty( $_POST['sub_id'] ) ) {
		return;
	}

	if( empty( $_POST['edd_delete_subscription'] ) ) {
		return;
	}

	if( ! current_user_can( 'edit_shop_payments') ) {
		return;
	}

	if( ! wp_verify_nonce( $_POST['edd-recurring-update-nonce'], 'edd-recurring-update' ) ) {
		wp_die( __( 'Nonce verification failed', 'edd-recurring' ), __( 'Error', 'edd-recurring' ), array( 'response' => 403 ) );
	}

	$subscription = new EDD_Subscription( absint( $_POST['sub_id'] ) );

	delete_post_meta( $subscription->parent_payment_id, '_edd_subscription_payment' );

	$subscription->delete();

	wp_redirect( admin_url( 'edit.php?post_type=download&page=edd-subscriptions&edd-message=deleted' ) );
	exit;

}
add_action( 'admin_init', 'edd_recurring_process_subscription_deletion', 2 );

/**
 * Update customer ID on subscriptions when payment's customer ID is updated
 *
 * @access      public
 * @since       2.4.15
 * @return      void
 */
function edd_recurring_update_customer_id_on_payment_update( $meta_id, $object_id, $meta_key, $meta_value ) {

	if( '_edd_payment_customer_id' == $meta_key ) {

		$subs_db = new EDD_Subscriptions_DB;
		$subs    = $subs_db->get_subscriptions( array( 'parent_payment_id' => $object_id ) );
		if( $subs ) {

			foreach( $subs as $sub ) {

				$sub->update( array( 'customer_id' => $meta_value ) );

			}

		}

	}

}
add_action( 'updated_postmeta', 'edd_recurring_update_customer_id_on_payment_update', 10, 4 );

/**
 * Find all subscription IDs
 *
 * @since  2.4
 * @param  array $items Current items to remove from the reset
 * @return array        The items with all subscriptions
 */
function edd_recurring_reset_delete_subscriptions( $items ) {

	$db = new EDD_Subscriptions_DB;

	$args = array(
		'number'  => -1,
		'orderby' => 'id',
		'order'   => 'ASC',
	);

	$subscriptions = $db->get_subscriptions( $args );

	foreach ( $subscriptions as $subscription ) {
		$items[] = array(
			'id'   => (int) $subscription->id,
			'type' => 'edd_subscription',
		);
	}

	return $items;
}
add_filter( 'edd_reset_store_items', 'edd_recurring_reset_delete_subscriptions', 10, 1 );

/**
 * Isolate the subscription items during the reset process
 *
 * @since  2.4
 * @param  stirng $type The type of item to remove from the initial findings
 * @param  array  $item The item to remove
 * @return string       The determine item type
 */
function edd_recurring_reset_recurring_type( $type, $item ) {

	if ( 'edd_subscription' === $item['type'] ) {
		$type = $item['type'];
	}

	return $type;

}
add_filter( 'edd_reset_item_type', 'edd_recurring_reset_recurring_type', 10, 2 );

/**
 * Add an SQL item to the reset process for the given subscription IDs
 *
 * @since  2.4
 * @param  array  $sql An Array of SQL statements to run
 * @param  string $ids The IDs to remove for the given item type
 * @return array       Returns the array of SQL statements with subscription statement added
 */
function edd_recurring_reset_queries( $sql, $ids ) {

	global $wpdb;
	$table = $wpdb->prefix . 'edd_subscriptions';
	$sql[] = "DELETE FROM $table WHERE id IN ($ids)";

	return $sql;

}
add_filter( 'edd_reset_add_queries_edd_subscription', 'edd_recurring_reset_queries', 10, 2 );
