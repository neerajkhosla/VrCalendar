<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * The Recurring Reminders Class
 *
 * @since  2.4
 */
class EDD_Recurring_Cron {

	protected $db;

	/**
	 *
	 * @since  2.4
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Set up our actions and properties
	 *
	 * @since  2.4
	 */
	public function init() {

		$this->db = new EDD_Subscriptions_DB;

		add_action( 'edd_daily_scheduled_events', array( $this, 'check_for_expired_subscriptions' ) );
	}

	/**
	 * Check for expired subscriptions once per day and mark them as expired
	 *
	 * @since  2.4
	 */
	public function check_for_expired_subscriptions() {

		$args = array(
			'status'     => 'active',
			'number'     => 999999,
			'expiration' => array(
				'start'  => date( 'Y-n-d 00:00:00', strtotime( '-1 day', current_time( 'timestamp' ) ) ),
				'end'    => date( 'Y-n-d 23:59:59', strtotime( '-1 day', current_time( 'timestamp' ) ) )
			)

		);

		$subs = $this->db->get_subscriptions( $args );

		if( ! empty( $subs ) ) {

			foreach( $subs as $sub ) {

				/*
				 * In the future we can query the merchant processor to confirm the subscription is actually expired
				 *
				 * See https://github.com/easydigitaldownloads/edd-recurring/issues/101
				 */

				$sub->expire();

			}

		}

	}

}